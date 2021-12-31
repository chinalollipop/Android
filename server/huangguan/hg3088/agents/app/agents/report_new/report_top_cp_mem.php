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
include_once ("../include/redis.php");
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
$agent_id = $_REQUEST['agent_id'];
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
        $history_date_end = date("Y-m-d 23:59:59", strtotime("-2 day"));

    }else if($date_start < date("Y-m-d") && $date_end == date("Y-m-d", strtotime("-1 day")) && (int)date("G") < 3) {

        //搜索结束时间为昨天并且当前时间小于【美东时间】凌晨3点,则昨天的历史报表未生成，需要从现有的订单记录里面计算
        $neededSearchCurrentBillTable = true;
        $current_start_day = date('Y-m-d', strtotime($_REQUEST['date_end']));
        $current_end_day = date('Y-m-d 23:59:59',strtotime($_REQUEST['date_end']));
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $history_date_end = date("Y-m-d 23:59:59", strtotime("-2 day"));

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

$aCp_default = $database['cpDefault'];
$cpDbLink = @mysqli_connect($aCp_default['host'],$aCp_default['user'],$aCp_default['password'],$aCp_default['dbname'],$aCp_default['port']) or die("mysqli connect error".mysqli_connect_error());
$sWhere = ' 1 ';
if ($web == DBPREFIX.'web_agents_data'){
    $agent_id = $row['ID'];
    $agent_username = $row['UserName'];
    $sWhere_hg = $sWhere. " and hg_agent_uid = '$agent_id'";
}else{
    $sWhere_hg = $sWhere. " and hg_agent_uid = '$agent_id'";

}

if ($action == 'agent_report_top_users' || $action=='api'){

    $web=DBPREFIX.'web_agents_data';
    $mysql="select ID,UserName from $web where UserName='$Agent'";
    $result = mysqli_query($dbLink,$mysql);
    $cou=mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);

    $agent_id = $row['ID'];
    $sWhere_hg = $sWhere . " and hg_agent_uid = '$agent_id'";
}

// 统计历史报表数据
// 彩票主数据
$start_day_cp = strtotime($_REQUEST['date_start']);
$end_day_cp = strtotime($history_date_end);
$sql = "select username, sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from gxfcy_history_bill_report_less_12hours where $sWhere_hg AND bet_time BETWEEN '".$start_day_cp."' and '".$end_day_cp."' group by username ";
$res_cp = mysqli_query($cpDbLink, $sql);
$cou_cp = mysqli_num_rows($res_cp);
if ($cou_cp>0){
    while ($row_cp = mysqli_fetch_assoc($res_cp)){
        $row_cp['user_win'] = $row_cp['user_win'] - $row_cp['user_win']*2;
        $row_cp_mem[$row_cp['username']] = $row_cp;
    }
}
$data_total = $row_cp_mem;



//得到第二部分实时的数据,并且合并到主数据里面
if($neededSearchCurrentBillTable) {

    $current_data = cp_today_report_list($cpDbLink, $current_start_day, $current_end_day, $sWhere_hg);
//    echo '<pre>';
//    echo $current_start_day.'-'.$current_end_day;
//    print_r( $current_data );
//    echo '</pre>';

    // 主数据（历史+当天）
    foreach ($row_cp_mem as $k => $v){
        $data_total[$v['username']]['username'] = $v['username'];
        $data_total[$v['username']]['count_pay'] = $v['count_pay'] + $current_data[$v['username']]['count_pay'];
        $data_total[$v['username']]['total'] = $v['total'] + $current_data[$v['username']]['total'];
        $data_total[$v['username']]['user_win'] = $v['user_win'] + $current_data[$v['username']]['user_win'];
        $data_total[$v['username']]['valid_money'] = $v['valid_money'] + $current_data[$v['username']]['valid_money'];
        if (isset($current_data[$v['username']])) unset($current_data[$v['username']]);
    }
    $data_total = array_merge((array)$data_total,(array)$current_data);

}

//计算当日的报表数据
function cp_today_report_list($cpDbLink, $current_start_day, $current_end_day, $sWhere_cp){

    $current_end_day = date('Y-m-d H:i:s',strtotime($current_end_day) + 86400);

//    echo '<pre>';
//    echo $current_start_day.'-'.$current_end_day;
//    echo '</pre>';
    // 彩票当天
//    echo '<pre>';
//    echo "select username, count(1) as count_pay, sum(drop_money) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from gxfcy_bill where $sWhere_cp and bet_time BETWEEN '".strtotime($current_start_day)."' and '".strtotime($current_end_day)."' ";
//    echo '</pre>';
    $current_start_day_cp = strtotime($current_start_day);
    $current_end_day_cp = strtotime($current_end_day);
    $res_cp = mysqli_query($cpDbLink, "select username, count(1) as count_pay, sum(drop_money) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from gxfcy_bill where $sWhere_cp and bet_time BETWEEN '".$current_start_day_cp."' and '".$current_end_day_cp."' group by username ");
    $cou_cp = mysqli_num_rows($res_cp);
    if ($cou_cp>0) {
        while ($row_cp = mysqli_fetch_assoc($res_cp)) {
            if ($row_cp['count_pay']>0) {
                $row_cp['user_win'] = $row_cp['user_win'] - $row_cp['user_win'] * 2;
                $data_cuttent_cp[$row_cp['username']] = $row_cp;
            }
        }
    }

    $data = $data_cuttent_cp;

    return $data;

}



if($action == 'api'){ // 手机版代理后台接口
    foreach ($data_total as $k => $v) {
        $resdata['rows'][] = $v;
        $resdata['count_pay'] += $v['count_pay'];
        $resdata['total'] += $v['total'];
        $resdata['valid_money'] += $v['valid_money'];
        $resdata['user_win'] += $v['user_win'];
    }
    $status = '200';
    $describe = '获取数据成功!';
    original_phone_request_response($status,$describe,$resdata);

}else if($action == 'agent_report_top_users'){
    foreach ($data_total as $k => $v){
        $data_users_plus[$k]['UserName'] = $v['username'];
        $data_users_plus[$k]['count_pay'] += $v['count_pay'];
        $data_users_plus[$k]['total'] += $v['total'];
        $data_users_plus[$k]['user_win'] += $v['user_win'];
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
            /* .main-nav dt{ width: 200px;}*/
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
        <dt>报表-彩票-会员</dt>
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
                <td rowspan="<?php echo count($data_total) + 2; ?>" class="td1">彩票</td>
                <td class="td2">会员用户名</td>
                <td>笔数</td>
                <td>下注金额</td>
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
                        echo "<a ".($level=='M'?"href='../admin/hgcp.php?uid=" . $uid . "&date_start=" . $_REQUEST['date_start'] . "&date_end=" . $date_end . "&username=" . $v['username'] . "'":"").">" . $v['username'] . "</a>";
                        ?>
                    </td>
                    <td><?php echo $v['count_pay']; ?></td>
                    <td><?php echo sprintf("%.2f", $v['total']); ?></td>
                    <td><?php echo $v['valid_money'] > 0 ? sprintf("%.2f", $v['valid_money']) : 0; ?></td>
                    <td><?php echo $v['user_win'] > 0 ? sprintf("%.2f", $v['user_win']) : '<span style="color: red;">' . sprintf("%.2f", $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo $v['user_win'] > 0 ? sprintf("%.2f", $v['user_win']) : '<span style="color: red;">' . sprintf("%.2f", $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo $v['user_win'] > 0 ? sprintf("%.2f", $v['user_win']) : '<span style="color: red;">' . sprintf("%.2f", $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo $v['user_win'] > 0 ? sprintf("%.2f", $v['user_win']) : '<span style="color: red;">' . sprintf("%.2f", $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo $v['user_win'] > 0 ? sprintf("%.2f", $v['user_win']) : '<span style="color: red;">' . sprintf("%.2f", $v['user_win']) . '</span>'; ?></td>
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
                <td><?php echo '<span ' . $sub_total_money_style . '>' . sprintf("%.2f", $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span ' . $sub_total_money_style . '>' . sprintf("%.2f", $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span ' . $sub_total_money_style . '>' . sprintf("%.2f", $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span ' . $sub_total_money_style . '>' . sprintf("%.2f", $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span ' . $sub_total_money_style . '>' . sprintf("%.2f", $subTotal['user_win']) . '</span>'; ?></td>
            </tr>
        </table>

    </div>
    </body>
    </html>
    <?php

    $loginfo = '报表详细-彩票-代理-会员';
    innsertSystemLog($loginname, $level, $loginfo);
}
?>