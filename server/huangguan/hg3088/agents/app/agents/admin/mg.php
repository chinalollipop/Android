<?php
/**
 * MG电子记录
 * Date: 2019/7/1
 */

session_start();
require_once '../include/config.inc.php';
include_once "../include/address.mem.php";
require_once '../include/redis.php';
require_once '../../../../common/mg/api.php';

// 验证同一账号不能同时登陆
checkAdminLogin();

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

//print_r($_REQUEST);die;

// 接收参数
$uid = isset($_REQUEST["uid"]) && $_REQUEST["uid"] ? $_REQUEST["uid"] : '';
$langx = isset($_REQUEST["langx"]) && $_REQUEST["langx"] ? $_REQUEST["langx"] : 'zh-cn';
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 0;

// 查询条件（日期、游戏、用户名）
$startTime = $_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : date('Y-m-d') . ' 00:00:00';
$endTime = $_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : date('Y-m-d') . ' 23:59:59';
$gameType = $_REQUEST['itemid'] ? intval($_REQUEST['itemid']) : 0;
$roundId = $_REQUEST['roundid'] ? trim($_REQUEST['roundid']) : '';
$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

$sWhere = 1;
$sWhere .= " AND `transaction_time` BETWEEN '{$startTime}' AND '{$endTime}'";
if($gameType)
    $sWhere .= " AND `itemid` = {$gameType}";
if($roundId)
    $sWhere .= " AND `roundid` = '{$roundId}'";
if($username)
    $sWhere .= " AND `username` like '%{$username}%'";

$mysql = "SELECT `userid`, `username`, `mgid`, `category`, `gameid`, `itemid`, `amount`, `platform`, `ext_item_id`, `roundid`, `itemid`, `balance`,`transaction_time` ,`created_at`
          FROM `" . DBPREFIX . "mg_projects`
          WHERE $sWhere 
          ORDER BY `transaction_time` DESC";
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);

// 统计
$totalBet = $totalRealBet = $totalProfit = '0.00';
while($row = mysqli_fetch_assoc($result)){
    if ($row['category'] == 'WAGER'){
        $totalBet += $row['amount'];
//    $totalRealBet += $row['cellscore'];
        $totalProfit += $row['amount'];
    }elseif ($row['category'] == 'PAYOUT'){
        $totalProfit -= $row['amount'];
    }
}

// 分页
$page_size = 50;
$page_count = ceil($count / $page_size);
$offset = $page * $page_size;
$mysql = $mysql . "  limit $offset, $page_size";
//echo $mysql;die;
$result = mysqli_query($dbLink, $mysql);
?>
<html>
<head>
    <title>MG电子</title>
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
    <dt>MG电子</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <select name="itemid">
                            <option value="">游戏名称</option>
                            <?php

                            foreach ($mgDianziGames as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if ($gameType==$key) echo 'selected';?> ><?php echo $value?></option>
                            <?php
                            }

                                ?>
                        </select>
                        注单日期：
                        <input type="text" name="date_start" id="date_start" value="<?php echo $startTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至
                        <input type="text" name="date_end" id="date_end" value="<?php echo $endTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
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
<!--                有效投注额：<span>--><?php //echo $totalRealBet;?><!--</span>&nbsp;&nbsp;-->
                <?php
                if($username){ // 查询账号时才显示
                    echo '会员结果总额：<span>' . $totalProfit . '</span>';
                }
                ?>
            </td>
        </tr>
        <tr class="m_title">
            <td>交易时间</td>
            <td>用户名</td>
            <td>游戏名称</td>
            <td>游戏平台</td>
            <td>注单号</td>
            <td>局号</td>
<!--            <td>局号</td>-->
            <td>交易类型</td>
            <td>发生金额</td>
            <td>交易后余额</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="13">暂无记录</td></tr>';
        }

        while($row = mysqli_fetch_assoc($result)){
            ?>
            <tr class="m_rig">
                <td width="8%"><?php echo $row['transaction_time']?></td>
                <td width="5%"><?php echo $row['username']?></td>
                <td width="5%" class="game_name"><?php echo $mgDianziGames[$row['itemid']]?></td>
                <td width="5%"><?php echo $row['platform']?></td>
                <td width="5%"><?php echo $row['mgid']?></td>
                <td width="8%"><?php echo $row['roundid']?></td>
<!--                <td width="5%">--><?php //echo $row['gameid']?><!--</td>-->
                <td width="5%"><?php echo $row['category'] == 'WAGER'?'赌注':'派彩'?></td>
                <td width="5%">
                    <?php
//                    echo ($row['amount'] === null ? '--' : number_format($row['amount'],2))
                    if ($row['category'] == 'WAGER'){
                        echo '-'.$row['amount'];
                    }else{
                        echo '<font color="red">'.$row['amount'].'</font>';
                    }
                    ?>
                </td>
                <td width="5%">
                    <?php
                    //                    echo ($row['amount'] === null ? '--' : number_format($row['amount'],2))
                    if ($row['category'] == 'WAGER'){
                        echo '-'.$row['balance'];
                    }else{
                        echo '<font color="red">'.$row['balance'].'</font>';
                    }
                    ?></td>
            </tr>
        <?php }?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>

</html>
