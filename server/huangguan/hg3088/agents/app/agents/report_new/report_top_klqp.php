<?php
/**
 *快乐棋牌代理统计报表
 */
session_start();
include_once '../../agents/include/address.mem.php';
include_once ("../../agents/include/config.inc.php");
include_once ("../include/redis.php");

// 验证同一账号不能同时登陆
if($_REQUEST['action'] != 'agent_report_top_agents') {
    checkAdminLogin();
}

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

// 接收参数
$uid = $_SESSION['Oid'];
$world = isset($_REQUEST['world']) && $_REQUEST['world'] ? trim($_REQUEST['world']) : 'cdm323';

$now = date('Y-m-d');
$startTime = isset($_REQUEST['date_start']) && $_REQUEST['date_start'] ? date('Y-m-d', strtotime($_REQUEST['date_start'])) : $now;
$endTime = isset($_REQUEST['date_end']) && $_REQUEST['date_end'] ? date('Y-m-d', strtotime($_REQUEST['date_end'])) : $now;
$loginName = $_SESSION['UserName']; // 当前登录用户

// 查询权限，管理员可以查询所有代理数据，代理仅查询自身下级会员
$result = mysqli_query($dbLink, "SELECT `Admin_Url` FROM " . DBPREFIX . "web_system_data WHERE `ID` = 1");
$row = mysqli_fetch_assoc($result);
$admin_url = explode(";", $row['Admin_Url']);
$sWhereKl = '1';
$sGroupBy = '';
if (in_array($_SERVER['HTTP_HOST'], $admin_url)){ // 后台管理员
    $web = DBPREFIX . 'web_system_data';
    $World = 'cdm323'; // 总代
    $Corprator = 'bdm223'; // 股东
    $sGroupBy = 'GROUP BY `agents`'; // 后续根据条件查询代理列表统计数据
} else {
    $web = DBPREFIX . 'web_agents_data';
    $sWhereKl .= " AND `agents`  = '{$loginName}'"; // 后续根据条件查询某代理统计数据
}

// 分段查询条件判断-暂引用之前的逻辑规则
$neededSearchCurrentBillTable = false;
if(isset($_REQUEST['date_end'])) {
    if($startTime == $now && $endTime >= $now) {
        //搜索开始和结束时间均为当天
        //置需要从当前的订单表里面读取记录flky为真，并且设置好起始时间以及结束时间
        $neededSearchCurrentBillTable = true;
        $current_start_day = $now;
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $_REQUEST['date_end'] = date("Y-m-d 23:59:59", strtotime("-1 day"));
    }else if($startTime < $now && $endTime >= $now && (int)date("G") < 3) {
        //搜索开始时间小于当天，搜索结束时间为当天并且当前时间小于【美东时间】凌晨3点,则昨天的历史报表还未生成，需将搜索分为两段
        //置需要从当前的订单表里面读取记录flky为真，并且设置好起始时间以及结束时间
        $neededSearchCurrentBillTable = true;
        $current_start_day = date("Y-m-d", strtotime("-1 day"));
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $_REQUEST['date_end'] = date("Y-m-d 23:59:59", strtotime("-2 day"));
    }else if($startTime < $now && $endTime == date("Y-m-d", strtotime("-1 day")) && (int)date("G") < 3) {
        //搜索结束时间为昨天并且当前时间小于【美东时间】凌晨3点,则昨天的历史报表未生成，需要从现有的订单记录里面计算
        $neededSearchCurrentBillTable = true;
        $current_start_day = date('Y-m-d', strtotime($_REQUEST['date_end']));
        $current_end_day = date('Y-m-d 23:59:59',strtotime($_REQUEST['date_end']));
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $_REQUEST['date_end'] = date("Y-m-d 23:59:59", strtotime("-2 day"));
    }else if($startTime <= $now && $endTime >= $now && (int)date("G") >= 3) {
        //搜索结束时间为当天(或大于当天)并且当前时间大于【美东时间】凌晨3点,则昨天的历史报表已生成，只需要今天的报表从现有的订单记录里面计算
        $neededSearchCurrentBillTable = true;
        $current_start_day = $now;
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $_REQUEST['date_end'] = date("Y-m-d 23:59:59", strtotime("-1 day"));
    }
}

// 1.快乐棋牌代理统计数据列表
// 1.1.批量查询历史统计
$agentsData = getKlqpHistoryData($sWhereKl, $sGroupBy);
// 1.2.批量查询当前统计
$currentData = getKlqpCurrentData($sWhereKl, $sGroupBy);
// 1.3.最终查询代理列表数据
if($agentsData){
    foreach ($agentsData as $key => $agentsCount){
        $agentsData[$key]['count_pay'] += $currentData[$key]['count_pay'];
        $agentsData[$key]['total'] += $currentData[$key]['total'];
        $agentsData[$key]['valid_money'] += $currentData[$key]['valid_money'];
        $agentsData[$key]['user_win'] += $currentData[$key]['user_win'];
    }
}else{
    $agentsData = $currentData;
}


// 2.统计当前代理数据总计
// 2.1.统计历史报表数据
$totalHistoryData = getKlqpHistoryData($sWhereKl);
// 2.2.作为最终统计数据
foreach ($totalHistoryData as $key => $historyData){
    $totalData = [
        'count_pay' => $historyData['count_pay'],
        'total' => $historyData['total'],
        'valid_money' => $historyData['valid_money'],
        'user_win' => $historyData['user_win'],
    ];
}

// 2.3.查询当前代理当前数据并加入最终统计数据中
if($neededSearchCurrentBillTable) {
    $totalCurrentData = getKlqpCurrentData($sWhereKl);
    if($totalHistoryData){
        foreach ($totalHistoryData as $key => $currentData){
            $totalData['count_pay'] += $totalCurrentData[$key]['count_pay'];
            $totalData['total'] += $totalCurrentData[$key]['total'];
            $totalData['valid_money'] += $totalCurrentData[$key]['valid_money'];
            $totalData['user_win'] += $totalCurrentData[$key]['user_win'];
        }
    }else{
        foreach ($totalCurrentData as $key => $currentData){
            $totalData['count_pay'] += $currentData['count_pay'];
            $totalData['total'] += $currentData['total'];
            $totalData['valid_money'] += $currentData['valid_money'];
            $totalData['user_win'] += $currentData['user_win'];
        }
    }
}

// 计算历史报表数据
function getKlqpHistoryData($sWhereKl, $sGroupBy = '')
{
    global $dbLink, $startTime, $endTime;
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`  
            FROM " . DBPREFIX . "kl_history_report 
            WHERE {$sWhereKl} AND `count_date` BETWEEN '{$startTime}' AND '{$endTime}' {$sGroupBy}";
    $result = mysqli_query($dbLink, $sql);
    $count = mysqli_num_rows($result);
    $agentsData = [];
    if ($count){
        while ($row = mysqli_fetch_assoc($result)){
            if(isset($row['agents'])){
                $agentsData[$row['agents']] = [
                    'agents' => $row['agents'],
                    'count_pay' => $row['count_pay'],
                    'total' => $row['total'],
                    'valid_money' => $row['valid_money'],
                    'user_win' => -($row['user_win']-$row['total'])
                ];
            }
        }
    }
    return $agentsData;
}

//计算当日的报表数据
function getKlqpCurrentData($sWhereKl, $sGroupBy = ''){
    global $dbLink, $current_start_day, $current_end_day;

    $sql = "SELECT `agents`, SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`, SUM(`prize`) AS `user_win`
            FROM " . DBPREFIX . "kl_projects 
            WHERE  {$sWhereKl} AND `gametime` BETWEEN '{$current_start_day}' AND '{$current_end_day}' {$sGroupBy}";
    $result = mysqli_query($dbLink, $sql);
    $count = mysqli_num_rows($result);
    $currentData = [];
    if ($count > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            $currentData[$row['agents']] = [
                'agents' => $row['agents'],
                'count_pay' => $row['count_pay'],
                'total' => $row['total'],
                'valid_money' => $row['valid_money'],
                'user_win' => -($row['user_win']-$row['total'])
            ];
        }
    }
    return $currentData;
}

if($_REQUEST['action'] == 'agent_report_top_agents'){

    foreach ($agentsData as $k => $v){
        //$data_agents_plus[$k]['UserName'] = $v['UserName'];
        $data_agents_plus[$k]['UserName'] = $v['agents'];
        $data_agents_plus[$k]['count_pay'] += $v['count_pay'];
        $data_agents_plus[$k]['total'] += $v['total'];
        $data_agents_plus[$k]['user_win'] += $v['user_win'];
        $data_agents_plus[$k]['valid_money'] += $v['valid_money'];
    }

    unset($data_total);
    unset($aAgents);
}else {

    ?>
    <html>
    <head>
        <title>快乐棋牌日结报表</title>
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
        <dt>报表-KL-代理</dt>
        <dd>
            <div class="header_info">
                <?php
                $realEndTime = isset($current_end_day) ? $current_end_day : $_REQUEST['date_end'];
                echo "总代理：$world 日期：" . $_REQUEST['date_start'] . ' ~ ' . $realEndTime . "-- 报表分类：总账 -- 投注方式：全部 --下注管道：网络下注 --";
                ?>
                <a href="javascript:history.go(-1);">回上一页</a>
            </div>
        </dd>
    </dl>
    <div class="main-ui">
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">所有下注</td>
                <td class="td2"><?php echo $web == DBPREFIX . 'web_agents_data' ? '代理' : '股东' ?></td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <?php
                $total_money_style = '';
                if ($totalData['user_win'] <= 0) $total_money_style = ' style="color: red;" ';
                ?>
                <td><?php echo $web == DBPREFIX . 'web_agents_data' ? $loginName : $Corprator; ?></td>
                <td><?php echo $totalData['count_pay']; ?></td>
                <td><?php echo $totalData['total']; ?></td>
                <td><?php echo $totalData['valid_money']; ?></td>
                <td><?php echo '<span' . $total_money_style . '>' . sprintf('%.2f', $totalData['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $total_money_style . '>' . sprintf('%.2f', $totalData['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $total_money_style . '>' . sprintf('%.2f', $totalData['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $total_money_style . '>' . sprintf('%.2f', $totalData['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $total_money_style . '>' . sprintf('%.2f', $totalData['user_win']) . '</span>'; ?></td>
            </tr>
        </table>
        <br>
        <table class="m_tab">
            <tr>
                <td rowspan="<?php echo count($agentsData) + 2; ?>" class="td1">快乐棋牌</td>
                <td class="td2">代理商</td>
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
            foreach ($agentsData as $k => $v) {
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
                        echo "<a href='report_top_klqp_mem.php?uid=" . $uid . "&date_start=" . $_REQUEST['date_start'] . "&date_end=" . $realEndTime . "&agent=" . $v['agents'] . "'>" . $v['agents'] . "</a>";
                        ?>
                    </td>
                    <td><?php echo $v['count_pay']; ?></td>
                    <td><?php echo sprintf('%.2f', $v['total']); ?></td>
                    <td><?php echo sprintf('%.2f', $v['valid_money']); ?></td>
                    <td><?php echo '<span' . $money_style . '>' . sprintf('%.2f', $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo '<span' . $money_style . '>' . sprintf('%.2f', $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo '<span' . $money_style . '>' . sprintf('%.2f', $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo '<span' . $money_style . '>' . sprintf('%.2f', $v['user_win']) . '</span>'; ?></td>
                    <td><?php echo '<span' . $money_style . '>' . sprintf('%.2f', $v['user_win']) . '</span>'; ?></td>
                </tr>
                <?php
            } ?>
            <tr>
                <?php
                // 盈利金额小于等于0时，金额字体颜色显示红色
                $sub_total_money_style = '';
                if ($subTotal['user_win'] <= 0)
                    $sub_total_money_style = ' style="color: red;" ';
                ?>
                <td class="td2"><?php echo '小计：' ?></td>
                <td><?php echo $subTotal['count_pay']; ?></td>
                <td><?php echo $subTotal['total']; ?></td>
                <td><?php echo $subTotal['valid_money']; ?></td>
                <td><?php echo '<span' . $sub_total_money_style . '>' . sprintf('%.2f', $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $sub_total_money_style . '>' . sprintf('%.2f', $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $sub_total_money_style . '>' . sprintf('%.2f', $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $sub_total_money_style . '>' . sprintf('%.2f', $subTotal['user_win']) . '</span>'; ?></td>
                <td><?php echo '<span' . $sub_total_money_style . '>' . sprintf('%.2f', $subTotal['user_win']) . '</span>'; ?></td>
            </tr>
        </table>
    </div>
    </body>
    </html>
    <?php
// 记录访问日志
    $loginfo = '报表详细-KL-代理';
    innsertSystemLog($_SESSION['UserName'], $_SESSION['admin_level'], $loginfo);
}
?>