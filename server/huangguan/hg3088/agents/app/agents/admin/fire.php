<?php
/**
 * 雷火电竞注单记录
 * Date: 2020/04/27
 */

session_start();
include_once '../include/config.inc.php';
include_once ("../include/address.mem.php");
require_once '../include/redis.php';
include_once '../../../../common/thunfire/api.php';
//include_once ("../include/IpSearch.php");

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

// 查询条件（日期、游戏、用户名）
$startTime = $_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : date('Y-m-d') . ' 00:00:00';
$endTime = $_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : date('Y-m-d') . ' 23:59:59';
$fireSearchTime = $_REQUEST['fireSearchTime'] ? intval($_REQUEST['fireSearchTime']) : 3;   // 查询时间

$gameType = $_REQUEST['game_type_id'] ? intval($_REQUEST['game_type_id']) : '';
$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : '';
$status = $_REQUEST['status'] ? trim($_REQUEST['status']) : '';


$sWhere = 1;

// '1' => '下注时间', '2' => '比赛时间', '3' => '结算时间', '4' => '更改时间'
if($fireSearchTime == 1) {
    $SearchTimeName = ' `date_created` ';
}elseif ($fireSearchTime == 2) {
    $SearchTimeName = ' `event_datetime` ';
}elseif ($fireSearchTime == 3) {
    $SearchTimeName = ' `settlement_datetime` ';
}elseif ($fireSearchTime == 4) {
    $SearchTimeName = ' `modified_datetime` ';
}

$sWhere .= " AND " .$SearchTimeName. " BETWEEN '{$startTime}' AND '{$endTime}'";

if($gameType)
    $sWhere .= " AND `game_type_id` = {$gameType}";
if($username)
    $sWhere .= " AND `username` like '%{$username}%'";
if($status)
    $sWhere .= " AND `settlement_status` = '{$status}'";    //注单状况

$mysql = "SELECT `userid`,`username`,`is_test`,`orderID`,`odds`,`malay_odds`,`euro_odds`,`member_odds`,`member_odds_style`,`game_type_id`,`game_type_name`,`game_market_name`,`market_option`,`map_num`,`bet_type_name`,`competition_name`,`event_id`,`event_name`,`event_datetime`,`date_created`,`settlement_datetime`,`modified_datetime`,`bet_selection`,`currency`,`amount`,`settlement_status`,`result_status`,`result`,`earnings`,`reward`,`is_combo`,`ticket_type`
          FROM `" . DBPREFIX . "fire_projects`
          WHERE $sWhere 
          ORDER BY `date_created` DESC";  // 按注单排序
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);

// 统计
$totalBet = $totalRealBet = $totalProfit = '0.00';
while($row = mysqli_fetch_assoc($result)){
    $totalBet += $row['amount'];
    $totalRealBet += $row['amount'];
    $totalProfit += $row['reward']; //会员总输赢

    //$totalProfit += ($row['earnings'] < 0 ? $row['earnings'] : $row['earnings']-$row['amount']);

}

// 分页
$page_size = 50;
$page_count = ceil($count / $page_size);
//if ($page==0 || $page==1) $page=0;
$offset = $page * $page_size;
$mysql = $mysql . "  limit $offset, $page_size";
//echo $mysql;//die;
$result = mysqli_query($dbLink, $mysql);

//$ipdatabase = '../include/ip.dat';
//$reader = new IpSearch($ipdatabase);
?>
<html>
<head>
    <title>雷火电竞</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .main-ui{width: 100%}
        .mem_total_money td span{ color:red;}
        input.za_text {width: 142px;}
        .td_950 span:nth-child(n+3) {display: none;}
        .td_730 img {margin-right: 3px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>雷火电竞</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <select name="game_type_id">
                            <option value="">游戏名称</option>
                            <?php

                            foreach ($thunFireCategory as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if ($gameType==$key) echo 'selected';?> ><?php echo $value?></option>
                            <?php
                            }

                                ?>
                        </select>
                        <select name="status">
                            <option value="">订单状态</option>
                            <?php

                            foreach ($thunFireSettlementStatus as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if ($status==$key) echo 'selected';?> ><?php echo $value?></option>
                            <?php
                            }

                                ?>
                        </select>
                        <select name="fireSearchTime">
                            <option value="4">注单最新时间：</option>
                            <?php
                            foreach ($thunFireSearchTime as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if ($fireSearchTime==$key) echo 'selected';?> ><?php echo $value?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <!--注单最新更改时间：-->
                        <input type="text" name="date_start" id="date_start" value="<?php echo $startTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至
                        <input type="text" name="date_end" id="date_end" value="<?php echo $endTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
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

<div class="main-ui" style="width: 1300px">
    <table class="m_tab">
        <tr class="mem_total_money">
            <td colspan="13">
                总投注总额：<span><?php echo $totalBet;?></span>&nbsp;&nbsp;
                有效投注额：<span><?php echo $totalRealBet;?></span>&nbsp;&nbsp;
                会员总输赢：<span><?php echo $totalProfit;?></span>
                <?php
                if($username){ // 查询账号时才显示
                    echo '会员结果总额：<span>' . $totalProfit . '</span>';
                }
                ?>
            </td>
        </tr>
        <tr class="m_title">
            <td>下注时间</td>
            <td>用户名</td>
            <td>注单号</td>
            <td>游戏|盘口</td>
            <td>赛事内容</td>
            <td>会员投注</td>
            <td>马来赔率</td>
            <td>欧盘赔率</td>
            <td>投注额 ¥</td>
            <td>盈利 ¥</td>
            <td>结果</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="13">暂无记录</td></tr>';
        }

        while($row = mysqli_fetch_assoc($result)){

            ?>
            <tr class="m_rig">
                <td width="10%"><?php echo $row['date_created']?></td>
                <td width="8%"><?php echo $row['username']?></td>
                <td width="10%"><?php echo $row['orderID']?></td>
                <td width="8%"><?php
                    if ($row['is_combo'] != 1) { //游戏|盘口 (连串不显示)
                        echo  $thunFireCategory[$row['game_type_id']] .'|<br>'.$thunFireBetTypeName[$row['bet_type_name']];
                    }
                    ?>
                </td>
                <td width="18%" style="text-align: left; padding-right: 15px;"><!--赛事内容-->
                    <?php
                    if ($row['is_combo'] != 1) {
                    echo
                        '['.$row['event_id'].']'.$row['event_name'].'|<br>'.   //赛事ID | 赛事名称
                        $row['event_datetime'].'<br><br>'.      // 赛事开始时间
                        $row['bet_selection'].'<br>'.           //下注选项
                        $ticket_type[$row['ticket_type']];      // 注单下注状况
                    }else{
                        echo '连串';
                    }
                    ?>
                </td>
                <td width="5%">
                    <?php
                    if ($row['is_combo'] != 1) {
                        echo $thunFireMemberOddsStyle[$row['member_odds_style']] .'<br>'. sprintf("%.2f",$row['member_odds']); }else{ echo ''; }?>
                </td>
                <td>
                    <?php
                    if ($row['is_combo'] != 1) {  echo sprintf("%.2f",$row['malay_odds']); }else{ echo ''; }?>
                </td>
                <td><?php echo sprintf("%.2f",$row['euro_odds'])  ?></td>
                <td><?php echo sprintf("%.2f",$row['amount'])  ?></td>
                <td>
                    <?php
                    if(!empty($row['result_status'])) { // 已结算
                        if($row['result_status'] == 'WIN') { //  赢
                            echo sprintf("%.2f",$row['earnings']-$row['amount'] );
                        } else if($row['result_status'] == 'LOSS') {    //输
                            echo '<font color="red">'. sprintf("%.2f",$row['earnings']) . '</font>';
                        } else if($row['result_status'] == 'DRAW') {    // 和
                            echo sprintf("%.2f",$row['earnings']-$row['amount'] );
                        } else if($row['result_status'] == 'CANCELLED') {  // 取消
                            echo "0.00";
                        }
                    } else {
                        echo "0.00"; // 未结算
                    }
                    ?>
                </td>
                <td style="text-align: left; padding-left: 15px;">
                    <?php
                    if(!empty($row['result_status'])) { // 已结算
                        if($row['result_status'] == 'WIN') { // 赢
                            echo '<font color="green">'.'<br>';
                        } else if($row['result_status'] == 'LOSS') {    //输
                            echo '<font color="red">'.'<br>';
                        } else if($row['result_status'] == 'DRAW') {    // 和
                            echo '<font color="blue">'.'<br>';
                        } else if($row['result_status'] == 'CANCELLED') {  // 取消
                            echo '<font color="orange">'.'<br>';   //#4d4d4d
                        }
                        echo '注单结果:'.$thunFireResultStatus[$row['result_status']].'<br>'.
                            '结算时间:'.$row['settlement_datetime'].'<br>'.
                            '开奖:' . $row['result'] .'</font><br>';
                    } else {
                        echo $thunFireSettlementStatus[$row['settlement_status']]; // 未结算
                    }
                    ?>
                </td>

            </tr>
        <?php }?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>

</html>
