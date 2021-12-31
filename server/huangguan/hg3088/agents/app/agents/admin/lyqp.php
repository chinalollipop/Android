<?php
/**
 * 棋牌管理-乐游棋牌
 * Date: 2018/8/25
 */

session_start();
require_once '../include/config.inc.php';
include_once "../include/address.mem.php";
require_once '../include/redis.php';
require_once '../include/ky/ky_game_result.php';

// 验证同一账号不能同时登陆
checkAdminLogin();

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

// ajax获取游戏房间类型
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getRoomType'){
    $kindId = isset($_REQUEST['kindid']) && $_REQUEST['kindid'] ? intval($_REQUEST['kindid']) : 0;
    $roomType = [];
    if($kindId){
        $roomType = $lyRoomType[$kindId];
    }else{
        foreach ($lyRoomType as $gameRoom){
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

// 查询条件（日期、游戏、房间、局号、用户名）
$startTime = $_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : date('Y-m-d') . ' 00:00:00';
$endTime = $_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : date('Y-m-d') . ' 23:59:59';
$gameType = $_REQUEST['kindid'] ? intval($_REQUEST['kindid']) : 0;
$roomType = $_REQUEST['serverid'] ? intval($_REQUEST['serverid']) : 0;
$gameId = $_REQUEST['gameid'] ? trim($_REQUEST['gameid']) : '';
$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

$sWhere = 1;
$sWhere .= " AND `game_endtime` BETWEEN '{$startTime}' AND '{$endTime}'";
if($gameType)
    $sWhere .= " AND `kindid` = {$gameType}";
if($roomType)
    $sWhere .= " AND `serverid` = {$roomType}";
if($gameId)
    $sWhere .= " AND `gameid` = '{$gameId}'";
if($username)
    $sWhere .= " AND `username` like '%{$username}%'";

$mysql = "SELECT `userid`, `username`, `gameid`,  `kindid`, `serverid`, `tableid`, `chairid`, `cardvalue`, `cellscore`, `allbet`, `curscore`, `profit`, `revenue`, `game_starttime`,`game_endtime` 
          FROM `" . DBPREFIX . "ly_projects`
          WHERE $sWhere 
          ORDER BY `game_endtime` DESC";
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);

// 统计
$totalBet = $totalRealBet = $totalProfit = '0.00';
while($row = mysqli_fetch_assoc($result)){
    $totalBet += $row['allbet'];
    $totalRealBet += $row['cellscore'];
    $totalProfit += $row['profit'];
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
    <title>乐游棋牌</title>
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
    <dt>乐游棋牌</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <select name="kindid" id="gameType">
                            <option value="">全部</option>
                            <?php foreach ($lyGameType as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if($key == $gameType) echo "selected";?> ><?php echo $value?></option>
                            <?php }?>
                        </select>
                        &nbsp;
                        <select name="serverid" id="roomType">
                            <option value="">全部房间</option>
                            <?php foreach ($lyRoomType as $gameType => $gameRoom) {
                                foreach ($gameRoom as $key => $value) {?>
                                    <option value="<?php echo $key?>" <?php if($key == $roomType) echo "selected";?> ><?php echo $value?></option>
                                <?php }}?>
                        </select>
                        注单日期：
                        <input type="text" name="date_start" id="date_start" value="<?php echo $startTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至
                        <input type="text" name="date_end" id="date_end" value="<?php echo $endTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        局号：
                        <input type="text" name="gameid" size=10 value="<?php echo $gameId;?>" maxlength=50 class="za_text">
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
                <?php
                if($username){ // 查询账号时才显示
                    echo '会员结果总额：<span>' . $totalProfit . '</span>';
                }
                ?>
            </td>
        </tr>
        <tr class="m_title">
            <td>开始时间</td>
            <td>结束时间</td>
            <td>用户名</td>
            <td>游戏类型</td>
            <td>房间类型</td>
            <td>桌子号</td>
            <td>座位号</td>
            <td>局号</td>
            <td>初始金额</td>
            <td>总投注</td>
            <td>有效投注额</td>
            <td>输赢金额</td>
            <td>游戏结果</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="13">暂无记录</td></tr>';
        }

        while($row = mysqli_fetch_assoc($result)){
            $cardValue = '';
            $cardPic = [];
            $cardMean = getCardValue($row['kindid'], $row['cardvalue']);
            foreach ($cardMean['cardChair'] as $chair => $card){
                $cardValue .= '<span>'.$chair . '-' . $card.'</span>' ;
            }
            $cardValue .= isset($cardMean['winChair']) && $cardMean['winChair'] ? $cardMean['winChair'] : ''; // 赢的座位号
            ?>
            <tr class="m_rig">
                <td width="8%"><?php echo $row['game_starttime']?></td>
                <td width="8%"><?php echo $row['game_endtime']?></td>
                <td width="5%"><?php echo $row['username']?></td>
                <td width="5%" class="game_name"><?php echo $lyGameType[$row['kindid']]?></td>
                <td width="7%"><?php echo $lyRoomType[$row['kindid']][$row['serverid']]?></td>
                <td width="5%"><?php echo $row['tableid']?></td>
                <td width="3%"><?php echo $row['chairid']?></td>
                <td width="10%"><?php echo $row['gameid']?></td>
                <td width="5%"><?php echo ($row['curscore'] === null ? '--' : number_format($row['curscore'],2))?></td>
                <td width="5%"><?php echo number_format($row['allbet'],2)?></td>
                <td width="5%" class="yxtz" data-mon="<?php echo intval($row['cellscore'])?>"><?php echo number_format($row['cellscore'],2)?></td>
                <td width="5%" class="tzyl" data-mon="<?php echo intval($row['profit'])?>"><?php echo number_format($row['profit'],2)?></td>
                <td width="30%" class="qp_result td_<?php echo $row['kindid'] ?>" style="text-align:left;"><?php echo $cardValue ? $cardValue : $row['cardvalue'];?></td>
            </tr>
        <?php }?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript">
    $("#gameType").change(function () {
        var kindId = $(this).val();
        $.get("/app/agents/admin/lyqp.php?action=getRoomType", { kindid: kindId }, function (data) {
            if (data) {
                data = $.parseJSON(data);
                var strHtml = "<option value=\"\">全部房间</option>";
                $.each(data, function (key, item) {
                    strHtml += "<option value=\"" + key + "\">" + item + "</option>";
                });
                $("#roomType").empty().html(strHtml);
            }
        });
    });
</script>
</html>
