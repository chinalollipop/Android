<?php
/**
 * 棋牌管理-VG棋牌
 * Date: 2018/8/25
 */

session_start();
require_once '../include/config.inc.php';
include_once "../include/address.mem.php";
require_once '../include/redis.php';
require_once '../include/vgqp/vg_game_result.php';

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

// 查询条件（日期、游戏、房间、局号、用户名）
$startTime = $_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : date('Y-m-d') . ' 00:00:00';
$endTime = $_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : date('Y-m-d') . ' 23:59:59';
$gameType = $_REQUEST['gamename'] ? intval($_REQUEST['gamename']) : 0;
$serial = $_REQUEST['serial'] ? intval($_REQUEST['serial']) : '';   // 注单号
$roundId = $_REQUEST['roundid'] ? trim($_REQUEST['roundid']) : '';   // 局号
$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

$sWhere = 1;
$sWhere .= " AND `game_endtime` BETWEEN '{$startTime}' AND '{$endTime}'";
if($gameType)
    $sWhere .= " AND `gametype` = {$gameType}";
if($serial)
    $sWhere .= " AND `serial` = {$serial}";
if($roundId)
    $sWhere .= " AND `roundId` = '{$roundId}'";
if($username)
    $sWhere .= " AND `username` like '%{$username}%'";

$mysql = "SELECT `userid`,`username`,`createtime`,`gametype`,`serial`,`roomId`,`tableId`,`roundId`, `beforeBalance`,`betamount`,`validbetamount`,`betpoint`,`money`,`serviceMoney`,`game_endtime`,`isBanker`,`gameInfo`,`gameresult`,`info1`
          FROM `" . DBPREFIX . "vg_projects`
          WHERE $sWhere 
          ORDER BY `createtime` DESC";
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);
//echo $mysql.'<br>';
// 统计
$totalBet = $totalRealBet = $totalProfit = $totalFee = '0.00';
while($row = mysqli_fetch_assoc($result)){
    $totalBet += $row['betamount']; //投注额
    $totalRealBet += $row['validbetamount'];    // 有效投注额
    $totalFee += $row['serviceMoney'];   // 服务费
    $totalProfit += $row['money']; // 输赢金额
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
    <title>VG棋牌</title>
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
    <dt>VG棋牌</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <select name="gamename" id="gameType">
                            <option value="">全部</option>
                            <?php foreach ($vgGameType as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if($key == $gameType) echo "selected";?> ><?php echo $value?></option>
                            <?php }?>
                        </select>
                        注单日期：
                        <input type="text" name="date_start" id="date_start" value="<?php echo $startTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至
                        <input type="text" name="date_end" id="date_end" value="<?php echo $endTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        注单号：
                        <input type="text" name="serial" size=10 value="<?php echo $serial;?>" maxlength=50 class="za_text">
                        局号：
                        <input type="text" name="roundid" size=10 value="<?php echo $roundId;?>" maxlength=50 class="za_text">
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
            <td colspan="13">
                总投注总额：<span><?php echo $totalBet;?></span>&nbsp;&nbsp;
                有效投注额：<span><?php echo $totalRealBet;?></span>&nbsp;&nbsp;
                总抽水总额：<span><?php echo $totalFee;?></span>
                <?php
                if($username){ // 查询账号时才显示
                    echo '会员结果总额：<span>' . $totalProfit . '</span>';
                }
                ?>
            </td>
        </tr>
        <tr class="m_title">
            <td>游戏时间</td>
            <td>用户名</td>
            <td>游戏类型</td>
            <td>注单号</td>
            <td>房号</td>
            <td>局号</td>
            <td>初始金额</td>
            <td>投注额</td>
            <td>有效投注额</td>
            <td>输赢金额</td>
            <td>服务费</td>
            <td>游戏结果</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="13">暂无记录</td></tr>';
        }

        while($row = mysqli_fetch_assoc($result)){
            $cardValue = getCardValue($row['gametype'], $row['gameInfo'], $row['betpoint']);
            ?>
            <tr class="m_rig">
                <td width="8%"><?php echo $row['createtime']?></td>
                <td width="5%"><?php echo $row['username']?></td>
                <td width="5%" class="game_name"><?php echo $vgGameType[$row['gametype']]?></td>
                <td width="5%"><?php echo $row['serial']?></td>
                <td width="3%"><?php echo $vgRoomType[$row['roomId']]?></td>
                <td width="8%"><?php echo $row['roundId']?></td>
                <td width="5%"><?php echo $row['beforeBalance']?></td>
                <td width="3%"><?php echo $row['betamount']?></td>
                <td width="5%"><?php echo $row['validbetamount']?></td>
                <td width="5%"><?php echo number_format($row['money'],2)?></td>
                <td width="3%"><?php echo number_format($row['serviceMoney'],2)?></td>
                <td width="30%" style="text-align:left;"><?php echo $cardValue ? $cardValue : $row['gameInfo'];?></td>
            </tr>
        <?php }?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript"></script>
</html>




