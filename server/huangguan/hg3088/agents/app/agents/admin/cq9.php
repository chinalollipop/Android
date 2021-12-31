<?php
/**
 * 电子管理-CQ9电子
 * Date: 2018/11/7
 */
session_start();
require_once '../include/config.inc.php';
include_once "../include/address.mem.php";
require_once '../include/redis.php';
require_once '../../../../common/cq9/api.php';

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

// 查询条件（日期、游戏、局号、用户名）
$startTime = $_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : date('Y-m-d') . ' 00:00:00';
$endTime = $_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : date('Y-m-d') . ' 23:59:59';
$gameCode = $_REQUEST['gameCode'] ? intval($_REQUEST['gameCode']) : 0;
$round = $_REQUEST['round'] ? intval($_REQUEST['round']) : '';
$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

$sWhere = 1;
$sWhere .= " AND `endroundtime` BETWEEN '{$startTime}' AND '{$endTime}'";
if($gameCode)
    $sWhere .= " AND `gamecode` = {$gameCode}";
if($round)
    $sWhere .= " AND `round` = {$round}";
/*if($boardId)
    $sWhere .= " AND `board_id` = '{$boardId}'";*/
if($username)
    $sWhere .= " AND `username` like '%{$username}%'";

$mysql = "SELECT `userid`, `username`, `bettime`, `gametype`,`gamecode`,`round`,`balance`,`win`,`bet`,`jackpot`,`status`,`endroundtime`,`createtime`,`bettime`,`gamerole`,`rake`
          FROM `" . DBPREFIX . "cq9_projects`
          WHERE $sWhere 
          ORDER BY `endroundtime` DESC";
//echo $mysql;echo '<br>';
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);

// 统计
$totalBet = $totalRealBet = $totalProfit = $totalFee = '0.00';
while($row = mysqli_fetch_assoc($result)){
    $totalBet += $row['bet'];
    $totalRealBet += $row['bet'];
    $totalProfit += ($row['win'] - $row['bet']);    // 盈利
    $totalFee += $row['rake'];  //抽水金额
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
    <title>CQ9电子</title>
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
    <dt>CQ9电子</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <select name="gameCode" id="gameCode">
                            <option value="">全部</option>
                            <?php foreach ($cqDianziGames as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if($key == $gameCode) echo "selected";?> ><?php echo $value?></option>
                            <?php }?>
                        </select>
                        &nbsp;
                        注单日期：
                        <input type="text" name="date_start" id="date_start" value="<?php echo $startTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至
                        <input type="text" name="date_end" id="date_end" value="<?php echo $endTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        局号：
                        <input type="text" name="round" size=10 value="<?php echo $round;?>" maxlength=50 class="za_text">
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
                总投注总额：<span><?php echo sprintf("%.2f", $totalBet);?></span>&nbsp;&nbsp;
                有效投注额：<span><?php echo sprintf("%.2f", $totalRealBet);?></span>&nbsp;&nbsp;
                总抽水总额：<span><?php echo sprintf("%.2f", $totalFee);?></span>&nbsp;&nbsp;
                <?php
                if($username){ // 查询账号时才显示
                    echo '会员结果总额：<span>' . sprintf("%.2f", $totalProfit) . '</span>';
                }
                ?>
            </td>
        </tr>
        <tr class="m_title">
            <td>局号</td>
            <td>下注时间</td>
            <td>结算时间</td>
            <td>用户名</td>
            <td>游戏种类</td>
            <td>游戏代码</td>
            <td>会员余额</td>
            <td>投注金额</td>
            <td>游戏赢分</td>
            <td>抽水金额</td>
            <!--<td>庄闲</td>-->
            <td>状态</td>

        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="13">暂无记录</td></tr>';
        }

        while($row = mysqli_fetch_assoc($result)){
            ?>
            <tr class="m_rig">
                <td width="5%"><?php echo $row['round']?></td>
                <td width="5%"><?php echo $row['bettime']?></td>
                <td width="5%"><?php echo $row['createtime']?></td>
                <td width="5%"><?php echo $row['username']?></td>
                <td width="5%"><?php echo $row['gametype']?></td>
                <td width="5%" class="gamecode"><?php echo $cqDianziGames[$row['gamecode']]?></td>
                <td width="5%"><?php echo number_format($row['balance'],2)?></td>
                <td width="5%"><?php echo number_format($row['bet'],2)?></td>
                <td width="5%" class="yxtz" data-mon="<?php echo intval($row['win'])?>"><?php echo number_format($row['win'],2)?></td>
                <td width="5%" class="tzyl" data-mon="<?php echo intval($row['rake'])?>"><?php echo number_format($row['rake'],2)?></td>
                <!--<td width="5%" style="text-align:left;"><?php /*echo $row['gamerole'];*/?></td>-->
                <td width="5%" style="text-align:center;"><?php
                    switch ($row['status']){
                        case 'complete': echo '完成'; break;
                    }?></td>
            </tr>
        <?php }?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
</html>




