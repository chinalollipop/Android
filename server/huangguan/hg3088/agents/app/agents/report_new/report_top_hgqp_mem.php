<?php
/**
 * 皇冠棋牌会员统计列表
 * Date: 2018/11/12
 */
session_start();
include_once ("../../agents/include/address.mem.php");
include_once ("../../agents/include/config.inc.php");

$resdata = array();
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
// 验证同一账号不能同时登陆
if($action != 'agent_report_top_users') {
    checkAdminLogin();
}
$level=$_SESSION['admin_level'];
// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $level != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

// 接收参数
$loginName = $_SESSION['UserName'];
$uid = $_SESSION['Oid'];
$agent = isset($_REQUEST['agent']) && $_REQUEST['agent'] ? trim($_REQUEST['agent']) : $loginName;
$now = date('Y-m-d');
$startTime = isset($_REQUEST['date_start']) && $_REQUEST['date_start'] ? date('Y-m-d', strtotime($_REQUEST['date_start'])) : $now;
$endTime = isset($_REQUEST['date_end']) && $_REQUEST['date_end'] ? date('Y-m-d', strtotime($_REQUEST['date_end'])) : $now;

// 查询权限，管理员可以查询所有代理数据，代理仅查询自身下级会员
$result = mysqli_query($dbLink, "SELECT `Admin_Url` FROM " . DBPREFIX . "web_system_data WHERE `ID` = 1");
$row = mysqli_fetch_assoc($result);
$admin_url = explode(";", $row['Admin_Url']);

if (in_array($_SERVER['HTTP_HOST'], $admin_url)){ // 后台管理员
    $web = DBPREFIX . 'web_system_data';
    $World = 'cdm323'; // 总代
    $Corprator = 'bdm223'; // 股东
} else {
    $web = DBPREFIX . 'web_agents_data';
}

// 分段查询条件判断-暂引用之前的逻辑规则
$neededSearchCurrentBillTable = false;
if(isset($_REQUEST['date_end'])) {
    if($startTime == $now && $endTime >= $now ) {
        //搜索开始和结束时间均为当天
        //置需要从当前的订单表里面读取记录flag为真，并且设置好起始时间以及结束时间
        $neededSearchCurrentBillTable = true;
        $current_start_day = $now;
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $history_date_end = date("Y-m-d 23:59:59", strtotime("-1 day"));
    }elseif($startTime < $now && $endTime >= $now && (int)date("G") < 3) {
        //搜索开始时间小于当天，搜索结束时间为当天并且当前时间小于【美东时间】凌晨3点,则昨天的历史报表还未生成，需将搜索分为两段
        //置需要从当前的订单表里面读取记录flag为真，并且设置好起始时间以及结束时间
        $neededSearchCurrentBillTable = true;
        $current_start_day = date("Y-m-d", strtotime("-1 day"));
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $history_date_end = date("Y-m-d", strtotime("-2 day"));
    }else if($startTime < $now && $endTime == date("Y-m-d", strtotime("-1 day")) && (int)date("G") < 3) {
        //搜索结束时间为昨天并且当前时间小于【美东时间】凌晨3点,则昨天的历史报表未生成，需要从现有的订单记录里面计算
        $neededSearchCurrentBillTable = true;
        $current_start_day = date('Y-m-d', strtotime($_REQUEST['date_end']));
        $current_end_day = date('Y-m-d 23:59:59',strtotime($_REQUEST['date_end']));
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $history_date_end = date("Y-m-d", strtotime("-2 day"));
    }else if($startTime <= $now && $endTime >= $now && (int)date("G") >= 3) {
        //搜索结束时间为当天(或大于当天)并且当前时间大于【美东时间】凌晨3点,则昨天的历史报表已生成，只需要今天的报表从现有的订单记录里面计算
        $neededSearchCurrentBillTable = true;
        $current_start_day = $now;
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $history_date_end = date("Y-m-d 23:59:59", strtotime("-1 day"));
    }else{
        $history_date_end = $_REQUEST['date_end'];
    }
}

// 1.皇冠棋牌会员统计数据列表
// 1.1.批量查询历史统计
$sWhere = "`agents` = '{$agent}'";
$sql = "SELECT `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue`  
        FROM " . DBPREFIX . "ff_history_report 
        WHERE {$sWhere} AND `count_date` BETWEEN '{$startTime}' AND '{$endTime}' GROUP BY `username`";
$result = mysqli_query($dbLink, $sql);
$count = mysqli_num_rows($result);
$historyData = [];
if ($count){
    while ($row = mysqli_fetch_assoc($result)){
        $historyData[$row['username']] = [
            'username' => $row['username'],
            'count_pay' => $row['count_pay'],
            'total' => $row['total'],
            'valid_money' => $row['valid_money'],
            'user_win' => - $row['user_win']
        ];
    }
}
//1.2.作为最终统计数据
$totalData = $historyData;
// 1.3.批量查询当前统计
if($neededSearchCurrentBillTable) {
    $sql = "SELECT `username`, SUM(1) AS `count_pay`, SUM(`valid_bet`) AS `valid_money`, SUM(`bet`) AS `total`, SUM(`wincoins`) AS `user_win`, SUM(`board_fee`) AS `total_revenue`
            FROM " . DBPREFIX . "ff_projects 
            WHERE  {$sWhere} AND `game_endtime` BETWEEN '{$current_start_day}' AND '{$current_end_day}' GROUP BY `username`";
    $result = mysqli_query($dbLink, $sql);
    $count = mysqli_num_rows($result);
    $currentData = [];
    if ($count > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $currentData[$row['username']] = [
                'username' => $row['username'],
                'count_pay' => $row['count_pay'],
                'total' => $row['total'],
                'valid_money' => $row['valid_money'],
                'user_win' => - $row['user_win']
            ];
        }
    }
    if($historyData){
        foreach ($historyData as $k => $v){
            $totalData[$v['username']]['username'] = $v['username'];
            $totalData[$v['username']]['count_pay'] = $v['count_pay'] + $currentData[$v['username']]['count_pay'];
            $totalData[$v['username']]['total'] = $v['total'] + $currentData[$v['username']]['total'];
            $totalData[$v['username']]['user_win'] = $v['user_win'] + $currentData[$v['username']]['user_win'];
            $totalData[$v['username']]['valid_money'] = $v['valid_money'] + $currentData[$v['username']]['valid_money'];
        }
    }else{
        $totalData = $currentData;
    }

    $totalData = $totalData + $currentData;
}

if($action == 'api'){ // 手机版代理后台接口
    $data_total = $totalData;
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
    $data_total = $totalData;
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
        <dt>报表-HGQP-会员</dt>
        <dd>
            <div class="header_info">
                <?php
                $realEndTime = isset($current_end_day) ? $current_end_day : $history_date_end;
                echo "总代理：$agent 日期：" . $_REQUEST['date_start'] . ' ~ ' . $realEndTime . "-- 报表分类：总账 -- 投注方式：全部 --下注管道：网络下注 --";
                ?>
                <a href="javascript:history.go(-1);">回上一页</a>
            </div>
        </dd>
    </dl>
    <div class="main-ui">
        <table class="m_tab">
            <tr>
                <td rowspan="<?php echo count($totalData) + 2; ?>" class="td1">HGQP</td>
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
            $subTotal = [];
            foreach ($totalData as $k => $v) {
                $subTotal['count_pay'] += $v['count_pay'];
                $subTotal['total'] += $v['total'];
                $subTotal['valid_money'] += $v['valid_money'];
                $subTotal['user_win'] += $v['user_win'];
                $money_style = '';
                if ($v['user_win'] <= 0) $money_style = ' style="color: red;" ';
                ?>
                <tr>
                    <td class="td2v">
                        <?php
                        echo "<a ".($level=='M'?"href='../admin/hgqp.php?uid=" . $uid . "&date_start=" . $_REQUEST['date_start'] . "&date_end=" . $realEndTime . "&username=" . $v['username'] . "'":"").">" . $v['username'] . "</a>";
                        ?>
                    </td>
                    <td><?php echo $v['count_pay']; ?></td>
                    <td><?php echo sprintf("%.2f", $v['total']); ?></td>
                    <td><?php echo sprintf("%.2f", $v['valid_money']); ?></td>
                    <td><?php echo '<span' . $money_style . '>' . sprintf("%.2f", $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo '<span' . $money_style . '>' . sprintf("%.2f", $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo '<span' . $money_style . '>' . sprintf("%.2f", $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo '<span' . $money_style . '>' . sprintf("%.2f", $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo '<span' . $money_style . '>' . sprintf("%.2f", $v['user_win']) . '</span>'; ?></td>
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
                <td><?php echo '<span' . $sub_total_money_style . '>' . sprintf("%.2f", $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $sub_total_money_style . '>' . sprintf("%.2f", $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $sub_total_money_style . '>' . sprintf("%.2f", $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $sub_total_money_style . '>' . sprintf("%.2f", $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $sub_total_money_style . '>' . sprintf("%.2f", $subTotal['user_win']) . '</span>'; ?></td>
            </tr>
        </table>
    </div>
    </body>
    </html>
    <?php
// 记录访问日志
    $loginfo = '报表详细-HGQP-代理-会员';
    innsertSystemLog($loginName, $level, $loginfo);
}
?>