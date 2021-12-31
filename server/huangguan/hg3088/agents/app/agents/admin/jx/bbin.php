<?php
/**
 * 视讯管理-BBIN视讯
 * Date: 2018/11/7
 */
session_start();
require_once '../../include/config.inc.php';
include_once("../../../agents/include/address.mem.php");
require_once '../../../../../common/bbin/api.php';

// 验证同一账号不能同时登陆
checkAdminLogin();

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

// ajax获取游戏游戏类型
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getGameType'){
    $kindId = isset($_REQUEST['kindid']) && $_REQUEST['kindid'] ? intval($_REQUEST['kindid']) : 0;
    $roomType = [];
    if($kindId){
        $roomType = $bbGameCateType[$kindId];
    }else{
        foreach ($bbGameCateType as $gameRoom){
            foreach ($gameRoom as $key => $room){
                $roomType[$key] = $room;
            }
        }
    }
    exit(json_encode($roomType));
}

// 接收参数
$uid = isset($_REQUEST["uid"]) && $_REQUEST["uid"] ? $_REQUEST["uid"] : '';
$langx = isset($_REQUEST["langx"]) && $_REQUEST["langx"] ? $_REQUEST["langx"] : 'zh-cn';
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 0;

// 查询条件（日期、游戏、游戏单号、用户名）
$startTime = $_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : date('Y-m-d') . ' 00:00:00';
$endTime = $_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : date('Y-m-d') . ' 23:59:59';
$gameKind = $Kind= $_REQUEST['gameKind'] ? strval($_REQUEST['gameKind']) : '';
$gameType = $_REQUEST['GameType'] ? strval($_REQUEST['GameType']) : '';
//$gameCode = $_REQUEST['gameCode'] ? intval($_REQUEST['gameCode']) : '';
$orderid = $_REQUEST['WagersID'] ? intval($_REQUEST['WagersID']) : '';
$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

$sWhere = 1;
$sWhere .= " AND `WagersDate` BETWEEN '{$startTime}' AND '{$endTime}'";
if($gameKind)
    $sWhere .= " AND `GameKind` = '{$gameKind}' ";  //BBIN分类
if($gameCode)
    $sWhere .= " AND `game_id` = {$gameCode}"; //游戏种类
if($orderid)
    $sWhere .= " AND `WagersID` = {$orderid}";
if($username)
    //   john103 变成 CHDEVJOHN103
    $platform_username = strtoupper($bbin_prefix.$username);
    $sWhere .= " AND `username` like '%{$platform_username}%'";

$mysql = "SELECT `userid`, `username`,`agents`,`admin`,`WagersID`,`GameKind`,`GameType`,`Result`,`SerialID`,`RoundNo`,`WagerDetail`,`GameCode`,`ResultType`,`Card`,`BetAmount`,`Payoff`,`ExchangeRate`,`Commissionable`,`Commission`,`IsPaid`,`Origin`,`prefix`,`WagersDate`
          FROM `" . DBPREFIX . "jx_bbin_projects`
          WHERE $sWhere 
          ORDER BY `WagersDate` DESC";
//echo $mysql . '<br>';
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);


// 统计
$totalBet = $totalRealBet = $totalWins = $totalProfit  = '0.00';
while($row = mysqli_fetch_assoc($result)){
    $totalBet += $row['BetAmount']; //下注金额
    $totalRealBet += $row['Commissionable']; // 有效投注
    $totalWins += $row['Payoff'];  //总奖金
    //$totalProfit += ($row['Payoff'] + $row['BetAmount']);    // 盈利 ($row['all_wins'] - $row['all_bets'])

}

// 分页
$page_size = 50;
$page_count = ceil($count / $page_size);
$offset = $page * $page_size;
$mysql = $mysql . "  limit $offset, $page_size";
$result = mysqli_query($dbLink, $mysql);
//echo $mysql;
?>
<html>
<head>
    <title>BBIN视讯</title>
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
    <dt>BBIN真人视讯</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <select name="gameKind" id="gameKind">
                            <option value="">BB游戏分类</option>
                            <?php foreach ($bbinGameKind as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if($key == $gameKind) echo "selected";?> ><?php echo $value?></option>
                            <?php }?>
                        </select>
                        <select name="gameType" id="gameType">
                            <option value="">全部游戏</option>
                            <?php foreach ($bbGameCateType as $gameKind => $bbGameCate) {
                                foreach ($bbGameCate as $key => $value) {?>
                                    <option value="<?php echo $key?>" <?php if($key == $gameType) echo "selected";?> ><?php echo $value?></option>
                                <?php }}?>
                        </select>
                        &nbsp;
                        注单日期：
                        <input type="text" name="date_start" id="date_start" value="<?php echo $startTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至
                        <input type="text" name="date_end" id="date_end" value="<?php echo $endTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        单号：
                        <input type="text" name="WagersID" size=10 value="<?php echo $orderid;?>" maxlength=50 class="za_text">
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

                <?php
                /*if($username){ // 查询账号时才显示
                    echo '会员结果总额：<span>' . sprintf("%.2f", $totalProfit) . '</span>';
                }*/
                ?>
            </td>
        </tr>
        <tr class="m_title">
            <td>BB种类</td>
            <td>游戏名称</td>
            <td>用户名</td>
            <td>用户ID</td>
            <td>游戏单号</td>
            <td>下注时间</td>
            <td>下注金额</td>
            <td>派彩结果</td>
            <td>会员有效投注额</td>
            <td>下注装置</td>
            <!--<td>前缀标识</td>-->
            <?php

            if(!empty($Kind) && $Kind == '3') { // 视讯注单显示
                echo '<td>局号</td>';
                echo '<td>场次</td>';
                echo '<td>玩法</td>';
                echo '<td>桌号</td>';
                //echo '<td>注单结果</td>';
                echo '<td>结果牌</td>';
            }
            if(!empty($Kind) && $Kind == '12') { // 彩票注单显示
                echo '<td>退水</td>';
            }
            ?>

        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="17">暂无记录</td></tr>';
        }

        $bbin_prefix = strtoupper($bbin_prefix); //前缀大写
        while($row = mysqli_fetch_assoc($result)){

            ?>
            <tr class="m_rig">
                <td width="3%"><?php echo $bbinGameKind[$row['GameKind']]?></td>
                <td width="3%"><?php echo $bbGameCateType[$row['GameKind']][$row['GameType']]?></td>
                <!-- CHDEVJOHN103 变成 john103 -->
                <td width="3%"><?php echo strtolower(substr($row['username'], strlen($bbin_prefix))); ?></td>
                <td width="3%"><?php echo $row['userid']?></td>
                <td width="3%"><?php echo $row['WagersID']?></td>
                <td width="5%"><?php echo $row['WagersDate']?></td>
                <td width="3%"><?php echo number_format($row['BetAmount'],2)?></td>
                <td width="3%"><?php echo number_format($row['Payoff'],2)?></td>
                <td width="3%"><?php echo number_format($row['Commissionable'],2)?></td>
                <td width="3%"><?php echo $Origins[$row['Origin']]?></td>

                <?php
                if(!empty($Kind) && $row['GameKind'] == '3') { // 真人视讯
                    echo '<td width="3%">'.$row['SerialID'].'</td>';
                    echo '<td width="3%">'.$row['RoundNo'].'</td>';
                    echo '<td width="5%">'.$row['WagerDetail'].'</td>';
                    echo '<td width="3%">'.$row['GameCode'].'</td>';
                    echo '<td width="5%">'.$row['Card'].'</td>';
                }
                if(!empty($Kind) && $row['GameKind'] == '12') { // 彩票
                    echo '<td width="3%">'.number_format($row['Commission'],2).'</td>';
                }
                ?>


            </tr>
        <?php }?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../../../js/agents/jquery.js"></script>
<script type="text/javascript">
    $("#gameKind").change(function () {
        var kindId = $(this).val();
        $.get("/app/agents/admin/jx/bbin.php?action=getGameType", { kindid: kindId }, function (data) {
            if (data) {
                data = $.parseJSON(data);
                var strHtml = "<option value=\"\">全部游戏</option>";
                $.each(data, function (key, item) {
                    strHtml += "<option value=\"" + key + "\">" + item + "</option>";
                });
                $("#gameType").empty().html(strHtml);
            }
        });
    });
</script>
</html>




