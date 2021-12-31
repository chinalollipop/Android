<?php
/**
 * 系统管理员，搜索全部数据
 * 代理账号，只能搜索下级会员的数据
 *
 */

session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include_once ("../../agents/include/address.mem.php");
include_once ("../../agents/include/config.inc.php");
include_once ("../../agents/include/define_function_list.inc.php");
$resdata = array();
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
if($action != 'agent_report_top_users') {
    checkAdminLogin(); // 同一账号不能同时登陆
}

if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid = $_SESSION['Oid'];
$loginname=$_SESSION['UserName'];
$Agent = $_REQUEST['agent'];
$level = $_SESSION['admin_level'] ;


if($_SESSION['Level'] == 'M') {
	$web=DBPREFIX.'web_system_data';
    $World = 'cdm323'; // 总代理
	$Corprator='bdm223'; // 股东
}else{
    $web=DBPREFIX.'web_agents_data';
    $Agent=$loginname;
}
$mysql="select ID,UserName from $web where Oid='$uid' and UserName='$loginname'";
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);







// 分2段查询，历史报表，注单报表
//$_REQUEST['date_start'] = '2018-03-01';
//$_REQUEST['date_end'] = '2018-03-28';



$date_start = date('Y-m-d',strtotime($_REQUEST['date_start']));
$date_end = date('Y-m-d',strtotime($_REQUEST['date_end']));

$neededSearchCurrentBillTable = false;
if(isset($_REQUEST['date_end'])) {

    if($date_start == date("Y-m-d") && $date_end >= date("Y-m-d") ) {

        //搜索开始和结束时间均为当天
        //置需要从当前的订单表里面读取记录flag为真，并且设置好起始时间以及结束时间
        $neededSearchCurrentBillTable = true;
        $current_start_day = date("Y-m-d");
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $history_date_end = date("Y-m-d 23:59:59", strtotime("-1 day"));

    }elseif($date_start < date("Y-m-d") && $date_end >= date("Y-m-d") && (int)date("G") < 3) {

        //搜索开始时间小于当天，搜索结束时间为当天并且当前时间小于【美东时间】凌晨3点,则昨天的历史报表还未生成，需将搜索分为两段
        //置需要从当前的订单表里面读取记录flag为真，并且设置好起始时间以及结束时间
        $neededSearchCurrentBillTable = true;
        $current_start_day = date("Y-m-d", strtotime("-1 day"));
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $history_date_end = date("Y-m-d", strtotime("-2 day"));

    }else if($date_start < date("Y-m-d") && $date_end == date("Y-m-d", strtotime("-1 day")) && (int)date("G") < 3) {

        //搜索结束时间为昨天并且当前时间小于【美东时间】凌晨3点,则昨天的历史报表未生成，需要从现有的订单记录里面计算
        $neededSearchCurrentBillTable = true;
        $current_start_day = date('Y-m-d', strtotime($_REQUEST['date_end']));
        $current_end_day = date('Y-m-d 23:59:59',strtotime($_REQUEST['date_end']));
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $history_date_end = date("Y-m-d", strtotime("-2 day"));

    }else if($date_start <= date("Y-m-d") && $date_end >= date("Y-m-d") && (int)date("G") >= 3) {

        //搜索结束时间为当天(或大于当天)并且当前时间大于【美东时间】凌晨3点,则昨天的历史报表已生成，只需要今天的报表从现有的订单记录里面计算
        $neededSearchCurrentBillTable = true;
        $current_start_day = date("Y-m-d");
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $history_date_end = date("Y-m-d 23:59:59", strtotime("-1 day"));

    }else{
        $history_date_end = $_REQUEST['date_end'];
    }

}

$sWhere = ' 1 ';
if ($web == DBPREFIX.'web_agents_data'){
    $agent_id = $row['ID'];
    $agent_username = $row['UserName'];
    $sWhere_ag = $sWhere. " and Agents = '$agent_username'";
}else{
    $sWhere_ag = $sWhere. " and Agents = '$Agent'";

}


// 统计历史报表数据
// AG主数据
$res_ag = mysqli_query($dbLink, "select UserName, sum(BulletOutNum) as count_pay, sum(Cost) as total, sum(Cost) as valid_money, sum(Earn) as shouru from ".DBPREFIX."ag_buyu_scene where $sWhere_ag AND EndTime BETWEEN '".$_REQUEST['date_start']."' and '".$history_date_end."' group by UserName ");
$cou_ag = mysqli_num_rows($res_ag);
if ($cou_ag>0) {
    while ($row_ag = mysqli_fetch_assoc($res_ag)){
        //$row_ag['user_win'] = $row_ag['shouru'] - $row_ag['valid_money'];
        $row_ag['user_win'] = $row_ag['valid_money'] - $row_ag['shouru'];
        $row_ag_buyu_mem[$row_ag['UserName']] = $row_ag;
    }
}

$data_total_tmp = $row_ag_buyu_mem;

//得到第二部分实时的数据,并且合并到主数据里面
if($neededSearchCurrentBillTable) {

    $current_data = buyu_today_report_list($dbLink, $current_start_day, $current_end_day, $sWhere_ag);
    // 主数据（历史+当天）
    foreach ($row_ag_buyu_mem as $k => $v){
        $data_total_tmp[$v['UserName']]['UserName'] = $v['UserName'];
        $data_total_tmp[$v['UserName']]['count_pay'] += $current_data[$v['UserName']]['count_pay'];
        $data_total_tmp[$v['UserName']]['total'] += $current_data[$v['UserName']]['total'];
        $data_total_tmp[$v['UserName']]['user_win'] += $current_data[$v['UserName']]['user_win'];
        $data_total_tmp[$v['UserName']]['valid_money'] += $current_data[$v['UserName']]['valid_money'];
        if (isset($current_data[$v['username']])) unset($current_data[$v['UserName']]);
    }
    $data_total_tmp = array_merge((array)$data_total_tmp,(array)$current_data);
}

$data_total=[];
foreach ($data_total_tmp as $k => $v){
    $username = explode('_', $v['UserName'], 2)[1];
    $data_total[$username]['UserName'] = $username;
    $data_total[$username]['count_pay'] = $v['count_pay'];
    $data_total[$username]['total'] = $v['total'];
    $data_total[$username]['user_win'] = $v['user_win'];
    $data_total[$username]['valid_money'] = $v['valid_money'];
}


//计算当日的报表数据
function buyu_today_report_list($dbLink, $current_start_day, $current_end_day, $sWhere_ag){

    // AG主数据
    $sql = "select UserName, sum(BulletOutNum) as count_pay, sum(Cost) as total, sum(Cost) as valid_money, sum(Earn) as shouru from ".DBPREFIX."ag_buyu_scene where $sWhere_ag and EndTime BETWEEN '".$current_start_day."' and '".$current_end_day."' group by UserName";
    $res_ag = mysqli_query($dbLink, $sql);
    $cou_ag = mysqli_num_rows($res_ag);
    if ($cou_ag>0) {

        while ($row_ag = mysqli_fetch_assoc($res_ag)) {
//            if ($row_ag['count_pay']>0){
                //$row_ag['user_win'] = $row_ag['shouru'] - $row_ag['valid_money'];
                $row_ag['user_win'] = $row_ag['valid_money'] - $row_ag['shouru'];
                $data_current_ag[$row_ag['UserName']] = $row_ag;
//            }
        }
    }

    $data = $data_current_ag;

    return $data;

}

if($action == 'api'){ // 手机版代理后台接口
    $ii=0;
    foreach ($data_total as $k => $v) {
       // $resdata['rows'][] = $v;
        $resdata['rows'][$ii]['username'] = $v['UserName']; // 防止返回给前端字段不统一
        $resdata['rows'][$ii]['count_pay'] = $v['count_pay'];
        $resdata['rows'][$ii]['total'] = $v['total'];
        $resdata['rows'][$ii]['user_win'] = $v['user_win'];
        $resdata['rows'][$ii]['valid_money'] = $v['valid_money'];
        // 总计
        $resdata['count_pay'] += $v['count_pay'];
        $resdata['total'] += $v['total'];
        $resdata['valid_money'] += $v['valid_money'];
        $resdata['user_win'] += $v['user_win'];
        $ii++;
    }
    $status = '200';
    $describe = '获取数据成功!';
    original_phone_request_response($status,$describe,$resdata);

}else if($action == 'agent_report_top_users'){
    foreach ($data_total as $k => $v){
        $data_users_plus[$k]['UserName'] = $v['UserName'];
        $data_users_plus[$k]['count_pay'] += $v['count_pay'];
        $data_users_plus[$k]['total'] += $v['total'];
        $data_users_plus[$k]['user_win'] += sprintf("%.2f" , $v['user_win']);
        $data_users_plus[$k]['valid_money'] += $v['valid_money'];
    }
    unset($data_total);
}else {


    ?>
    <html>
    <head>
        <title>reports_top</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
        <style type="text/css">
            .main-nav dt {
                width: 140px;
            }

            .main-ui {
                width: 1000px;
            }

            .td1 {
                width: 80px;
            }

            .td2v {
                text-align: left;
            }

            .td2v a {
                color: #00f;
            }
        </style>
    </head>
    <body>
    <dl class="main-nav">
        <dt>报表-打鱼-代理-会员</dt>
        <dd>
            <div class="header_info">
                <?php
                $date_end = isset($current_end_day) ? $current_end_day : $history_date_end;
                echo "总代理：$Agent 日期：" . $_REQUEST['date_start'] . ' ~ ' . $date_end . "-- 报表分类：总账 -- 投注方式：全部 --下注管道：网络下注 --";

                ?>
                <a href="javascript:history.back(-1);">回上一页</a>
            </div>
        </dd>
    </dl>
    <div class="main-ui">


        <table class="m_tab">
            <tr>
                <td rowspan="<?php echo count($data_total) + 2; ?>" class="td1"><?php echo $sAg_type_title; ?></td>
                <td class="td2">会员用户名</td>
                <td>子弹数</td>
                <td>子弹价值</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <?php
            $subTotal = array();
            foreach ($data_total as $k => $v) {
                $subTotal['count_pay'] += $v['count_pay'];
                $subTotal['total'] += $v['total'];
                $subTotal['valid_money'] += $v['valid_money'];
                $subTotal['user_win'] += $v['user_win'];
                ?>
                <tr>
                    <td class="td2v">
                        <?php
                        echo "<a ".($level=='M'?"href='../admin/ag_buyu_sence.php?uid=" . $uid . "&date_start=" . $_REQUEST['date_start'] . "&date_end=" . $date_end . "&username=" . $v['UserName'] . "'":"")." >" . $v['UserName'] . "</a>";
                        ?>
                    </td>
                    <td><?php echo $v['count_pay']; ?></td>
                    <td><?php echo sprintf("%.2f", $v['total']); ?></td>
                    <td><?php echo sprintf("%.2f", $v['valid_money']); ?></td>
                    <?php $user_win = sprintf("%.2f", $v['user_win']); ?>
                    <td><?php echo $user_win > 0 ? $user_win : '<span style="color: red;">' . $user_win . '</span>'; ?></td>
                    <td><?php echo $user_win > 0 ? $user_win : '<span style="color: red;">' . $user_win . '</span>'; ?></td>
                    <td><?php echo $user_win > 0 ? $user_win : '<span style="color: red;">' . $user_win . '</span>'; ?></td>
                    <td><?php echo $user_win > 0 ? $user_win : '<span style="color: red;">' . $user_win . '</span>'; ?></td>
                    <td><?php echo $user_win > 0 ? $user_win : '<span style="color: red;">' . $user_win . '</span>'; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <?php
                // 盈利金额小于等于0时，金额字体颜色显示红色
                $sub_total_money_style = '';
                if ($subTotal['user_win'] <= 0) $sub_total_money_style = ' style="color: red;" ';
                ?>
                <td class="td2"><?php echo '小计：' ?></td>
                <td><?php echo $subTotal['count_pay']; ?></td>
                <td><?php echo sprintf("%.2f", $subTotal['total']); ?></td>
                <td><?php echo sprintf("%.2f", $subTotal['valid_money']); ?></td>
                <?php $subTotal['user_win'] = sprintf("%.2f", $subTotal['user_win']); ?>
                <td><?php echo '<span ' . $sub_total_money_style . '>' . $subTotal['user_win'] . '</span>'; ?></td>
                <td><?php echo '<span ' . $sub_total_money_style . '>' . $subTotal['user_win'] . '</span>'; ?></td>
                <td><?php echo '<span ' . $sub_total_money_style . '>' . $subTotal['user_win'] . '</span>'; ?></td>
                <td><?php echo '<span ' . $sub_total_money_style . '>' . $subTotal['user_win'] . '</span>'; ?></td>
                <td><?php echo '<span ' . $sub_total_money_style . '>' . $subTotal['user_win'] . '</span>'; ?></td>
            </tr>
        </table>

    </div>
    </body>
    </html>
    <?php

    $loginfo = '报表详细-打鱼-代理-会员';
    innsertSystemLog($loginname, $level, $loginfo);
}
?>