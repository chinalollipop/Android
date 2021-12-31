<?php
/**
 * 电子管理-FG电子
 * Date: 2018/11/7
 */
session_start();
require_once '../include/config.inc.php';
include ("../../agents/include/address.mem.php");
require_once '../include/redis.php';
require_once '../../../../common/fg/api.php';

// 验证同一账号不能同时登陆
checkAdminLogin();

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}


// 接收参数
$uid = isset($_REQUEST["uid"]) && $_REQUEST["uid"] ? $_REQUEST["uid"] : '';
$langx = isset($_REQUEST["langx"]) && $_REQUEST["langx"] ? $_REQUEST["langx"] : 'zh-cn';
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 0;

// 查询条件（日期、游戏、游戏单号、用户名）
$startTime = $_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : date('Y-m-d') . ' 00:00:00';
$endTime = $_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : date('Y-m-d') . ' 23:59:59';
$gameType = $_REQUEST['gameType'] ? strval($_REQUEST['gameType']) : '';
$gameCode = $_REQUEST['gameCode'] ? intval($_REQUEST['gameCode']) : '';
$orderid = $_REQUEST['orderid'] ? intval($_REQUEST['orderid']) : '';
$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

$sWhere = 1;
$sWhere .= " AND `endtime` BETWEEN '{$startTime}' AND '{$endTime}'";
if($gameType)
    $sWhere .= " AND `gt` = '{$gameType}' ";
if($gameCode)
    $sWhere .= " AND `game_id` = {$gameCode}";
if($orderid)
    $sWhere .= " AND `orderid` = {$orderid}";
if($username)
    $sWhere .= " AND `username` like '%{$username}%'";

$mysql = "SELECT `userid`, `username`,`orderid`,`game_id`,`gt`,`start_chips`,`end_chips`,`all_bets`,`all_wins`,`total_bets`,`jackpot_bonus`,`jp_contri`,`result`,`scene_id`,`bullet_count`,`type`,`begintime`,`endtime`,`device`
          FROM `" . DBPREFIX . "fg_projects`
          WHERE $sWhere 
          ORDER BY `endtime` DESC";
//echo $mysql;echo '<br>';
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);


// 统计
$totalBet = $totalRealBet = $totalProfit = $totalFee = '0.00';
while($row = mysqli_fetch_assoc($result)){
    $totalBet += $row['all_bets'];
    $totalRealBet += $row['all_bets'];
    $totalWins += $row['all_wins'];  //总奖金
    $totalProfit += ($row['all_wins'] - $row['all_bets']);    // 盈利  ($row['bet'] - $row['win'])
    //$totalFee += $row['rake'];  //抽水金额
    //$totalResult += $row['result'];  // 总收支
}

// 分页
$page_size = 50;
$page_count = ceil($count / $page_size);
$offset = $page * $page_size;
$mysql = $mysql . "  limit $offset, $page_size";
$result = mysqli_query($dbLink, $mysql);
?>
<html>
<head>
    <title>FG电子</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .main-ui{width: 100%}
        .mem_total_money td span{ color:red;}
        input.za_text {width: 142px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>FG电子</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <select name="gameType" id="gameType">
                            <option value="">游戏种类</option>
                            <?php foreach ($fgGameGts as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if($key == $gameType) echo "selected";?> ><?php echo $value?></option>
                            <?php }?>
                        </select>
                        <select name="gameCode" id="gameCode">
                            <option value="">全部游戏</option>
                            <?php foreach ($afgGameList as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if($key == $gameCode) echo "selected";?> ><?php echo $value?></option>
                            <?php }?>
                        </select>
                        &nbsp;
                        注单日期：
                        <input type="text" name="date_start" id="date_start" value="<?php echo $startTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至
                        <input type="text" name="date_end" id="date_end" value="<?php echo $endTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        单号：
                        <input type="text" name="orderid" size=10 value="<?php echo $orderid;?>" maxlength=50 class="za_text">
                        会员帐号：
                        <input type="text" name="username" size=10 value="<?php echo $username;?>" maxlength=20 class="za_text">

                        <input type="submit" name="SUBMIT" value="确认" class="za_button">

                        共<?php echo $count?>条
                        <select name='page' onChange="self.myFORM.submit()">
                            <?php
                            if ($page_count == 0){
                                $page_count = 1;
                            }
                            for($i = 0; $i < $page_count; $i++){
                                if ($i == $page){
                                    echo "<option selected value = '$i'>" . ($i + 1) . "</option>";
                                }else{
                                    echo "<option value = '$i'>" . ($i + 1) . "</option>";
                                }
                            }
                            ?>
                        </select> 共<?php echo $page_count?> 页
                    </td>
                </tr>

            </table>
        </form>
    </dd>
</dl>

<div class="main-ui">
    <table class="m_tab">
        <tr class="mem_total_money">
            <td colspan="17">
                总投注总额：<span><?php echo sprintf("%.2f", $totalBet);?></span>&nbsp;&nbsp;
                有效投注额：<span><?php echo sprintf("%.2f", $totalRealBet);?></span>&nbsp;&nbsp;
                总奖金：<span><?php echo sprintf("%.2f", $totalWins);?></span>&nbsp;
                <!--总收支：<span><?php /*echo sprintf("%.2f", $totalResult);*/?></span>&nbsp;-->
                <!--总抽水总额：<span><?php /*echo sprintf("%.2f", $totalFee);*/?></span>&nbsp;&nbsp;-->
                <?php
                if($username){ // 查询账号时才显示
                    echo '会员结果总额：<span>' . sprintf("%.2f", $totalProfit) . '</span>';
                }
                ?>
            </td>
        </tr>
        <tr class="m_title">
            <td>游戏单号</td>
            <td>游戏种类</td>
            <td>游戏名称</td>
            <td>用户名</td>
            <td>用户ID</td>
            <td>总投注(有效)</td>
            <td>总奖金</td>
            <td>收支</td>
            <td>开始筹码</td>
            <td>结束筹码</td>
            <td>JP奖金</td>
            <td>JP贡献</td>
            <?php
            if(!empty($gameType) and $gameType == 'hunter') { // 捕猎
                echo '<td>子弹个数</td>';
                echo '<td>捕猎类型</td>';
                echo '<td>开始时间</td>';
            }
            ?>
            <td>结束时间</td>
            <td>终端类型</td>

        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="15">暂无记录</td></tr>';
        }

        while($row = mysqli_fetch_assoc($result)){
            ?>
            <tr class="m_rig">
                <td width="5%"><?php echo $row['orderid']?></td>
                <td width="3%"><?php echo $fgGameGts[$row['gt']]?></td>
                <td width="3%"><?php echo $afgGameList[$row['game_id']]?></td>
                <td width="3%"><?php echo $row['username']?></td>
                <td width="3%"><?php echo $row['userid']?></td>
                <td width="3%"><?php echo $row['all_bets']?></td>
                <td width="3%"><?php echo $row['all_wins']?></td>
                <td width="3%"><?php echo $row['result']?></td>
                <td width="3%"><?php echo number_format($row['start_chips'],2)?></td>
                <td width="3%"><?php echo number_format($row['end_chips'],2)?></td>
                <td width="3%"><?php echo number_format($row['jackpot_bonus'],2)?></td>
                <td width="3%"><?php echo number_format($row['jp_contri'],2)?></td>
                <?php
                if(!empty($gameType) && $row['gt'] == 'hunter') { // 捕猎
                    echo '<td width="3%">'.$row['bullet_count'].'</td>';
                    echo '<td width="3%">'.$hfgTypes[$row['type']].'</td>';
                    echo '<td width="5%">'.$row['begintime'].'</td>';
                }
                ?>
                <td width="5%"><?php echo $row['endtime']?></td>
                <td width="3%"><?php echo $fgDevices[$row['device']]?></td>
                <!--<td width="5%" style="text-align:left;"><?php /*echo $row['gamerole'];*/?></td>-->

            </tr>
        <?php }?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
</html>




