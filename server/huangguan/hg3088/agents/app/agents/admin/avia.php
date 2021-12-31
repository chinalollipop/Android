<?php
/**
 * 泛亚电竞注单记录
 * Date: 2019/7/1
 */

session_start();
require_once '../include/config.inc.php';
include_once ("../include/address.mem.php");
require_once '../include/redis.php';
require_once '../../../../common/avia/api.php';
include_once ("../include/IpSearch.php");

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
$gameType = $_REQUEST['cateID'] ? trim($_REQUEST['cateID']) : '';
$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : '';
$status = $_REQUEST['status'] ? trim($_REQUEST['status']) : '';
$type = $_REQUEST['type'] ? trim($_REQUEST['type']) : ''; // dj 电竞游戏， smallgame 小游戏

// 泛亚电竞存储的是北京时间，查询的时候再加多12个小时
$startTimeBeijing = date('Y-m-d H:i:s',strtotime($startTime) + 12*60*60);
$endTimeBeijing = date('Y-m-d H:i:s',strtotime($endTime) + 12*60*60);

$sWhere = 1;
if ($type=='dj'){
    $sWhere .= " AND `cateID`>0 AND `updateAt` BETWEEN '{$startTimeBeijing}' AND '{$endTimeBeijing}'";
    if($gameType)
        $sWhere .= " AND `cateID` = {$gameType}";
}
elseif($type=='smallgame'){
    $sWhere .= " AND xxxindex>0";
    $sWhere .= " AND `createAt` BETWEEN '{$startTimeBeijing}' AND '{$endTimeBeijing}'";
//    $sWhere .= " AND `createAt` BETWEEN '{$startTime}' AND '{$endTime}'";
    if($gameType)
        $sWhere .= " AND `code` = '{$gameType}'";
}
else{
    $sWhere .= " AND `updateAt` BETWEEN '{$startTimeBeijing}' AND '{$endTimeBeijing}'";
}

if($username)
    $sWhere .= " AND `username` like '%{$username}%'";
if($status)
    $sWhere .= " AND `status` = '{$status}'";

$mysql = "SELECT *
          FROM `" . DBPREFIX . "avia_projects`
          WHERE $sWhere 
          ORDER BY `createAt` DESC";
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);

// 统计
$totalBet = $totalRealBet = $totalProfit = '0.00';
while($row = mysqli_fetch_assoc($result)){
    $totalBet += $row['betAmount'];
    $totalRealBet += $row['betMoney'];
    $totalProfit += $row['money'];
}

// 分页
$page_size = 50;
$page_count = ceil($count / $page_size);
if ($page==0 || $page==1) $page=0;
$offset = $page * $page_size;
$mysql = $mysql . "  limit $offset, $page_size";
//echo $mysql;die;
$result = mysqli_query($dbLink, $mysql);

$ipdatabase = '../include/ip.dat';
$reader = new IpSearch($ipdatabase);
?>
<html>
<head>
    <title>泛亚电竞</title>
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
    <dt>泛亚电竞</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <select name="type">
                            <option value="">游戏</option>
                            <option value="dj" <?php if ($type=='dj') echo 'selected';?>>电竞订单</option>
                            <option value="smallgame" <?php if ($type=='smallgame') echo 'selected';?>>小游戏订单</option>
                        </select>
                        <select name="cateID">
                            <option value="">游戏名称</option>
                            <?php

                            foreach ($aviaCategory as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if ($gameType==$key) echo 'selected';?> ><?php echo $value?></option>
                            <?php
                            }

                                ?>
                        </select>
                        <select name="status">
                            <option value="">订单状态</option>
                            <?php

                            foreach ($aviaProjectStatus as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php if ($status==$key) echo 'selected';?> ><?php echo $value?></option>
                            <?php
                            }

                            ?>
                        </select>
                        注单最新更改时间：
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

<div class="main-ui" style="width: 1400px">
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
            <td>用户名</td>
            <td>交易时间</td>
            <td>【美东】交易时间</td>
            <td>投注详情</td>
            <td>赔率</td>
            <td>投注额/有效投注</td>
            <td>盈亏</td>
            <td>赛果</td>
            <td>状态</td>
            <td>IP</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="13">暂无记录</td></tr>';
        }

        while($row = mysqli_fetch_assoc($result)){
            ?>
            <tr class="m_rig">
                <td><?php echo $row['username']?></td>
                <td style="text-align: left; padding-left: 15px;">
                    <?php
                    // 小游戏只显示下注时间、结算时间。没有开奖时间
                    echo
                        '订单号:'.$row['orderID'].'<br>'.
                        '下注:'.$row['createAt'].'<br>';
                    echo $row['cateID']==''?'':'开奖:'.$row['resultAt'].'<br>';
                    echo '结算:'.$row['rewardAt'].'<br>';
                    ?>
                </td>
                <td style="text-align: left; padding-left: 15px;">
                    <?php
                    echo
                        '订单号:'.$row['orderID'].'<br>'.
                        '下注:'.(date('Y-m-d H:i:s',strtotime($row['createAt'])-60*60*12)).'<br>';
                    if ($row['status']=='None'){
                        echo $row['cateID']==''?'':'开奖:'.$row['resultAt'].'<br>';
                        echo '结算:'.$row['rewardAt'].'<br>';
                    }
                    else{
                        echo $row['cateID']==''?'':'开奖:'.(date('Y-m-d H:i:s',strtotime($row['resultAt'])-60*60*12)).'<br>';
                        echo '结算:'.(date('Y-m-d H:i:s',strtotime($row['rewardAt'])-60*60*12)).'<br>';
                    }
                    ?>
                </td>
                <td style="text-align: right; padding-right: 15px;">
                    <?php

                    if (strlen($row['chgdetail'])>0){
                        echo '串关';
                    }
                    elseif (strlen($row['code'])>0){
                        $player = explode('.',$row['player']);
                        echo $smallGameMethod[$row['content']].'<br>'.
                            $smallGameMethodType[$player[2]].'<br>'.
                            $smallGameCategory[$row['code']];
                    }
                    else{
                        echo
                            $row['content'].'<br>'.
                            $row['bet'].'<br>'.
                            '<font color="#F001FF">'.$row['match_avia'].'</font><br>'.
                            $row['league'].' <font color="#009688">['.$aviaCategory[$row['cateID']].']</font><br>';
                    }

                    ?>
                </td>
                <td><?php echo $row['odds']?></td>
                <td>
                    <?php
                        echo $row['betAmount'].'<br>('.$row['betMoney'].')';
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['money']<0){
                        echo '<font color="red">'.$row['money'].'</font>';
                    }else{
                        echo $row['money'];
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if (strlen($row['code'])>0) {
                        echo $row['result'].'.'.$aviaHeros[$row['code']][$row['result']];
                    }
                    else{
                        echo $row['result'];
                    }
                    ?>
                </td>
                <td>
                    <?php
                    switch ($row['status']){
                        case 'None': echo '等待开奖'; break;
                        case 'Revoke': echo '退回本金'; break;
                        case 'Win': echo '赢'; break;
                        case 'Lose': echo '输'; break;
                        case 'WinHalf': echo '赢一半'; break;
                        case 'LoseHalf': echo '输一半'; break;
                        case 'Settlement': echo '结算中'; break;
                        case 'Cancel': echo '比赛取消'; break;
                    }
                    ?>
                </td>
                <?php
                $ipArea = $reader->get($row["ip"]);
                $aIpArea = explode('|',$ipArea);
                $aIpArea = array_slice($aIpArea,0,6);
                $sIpArea = implode('|',$aIpArea);
                ?>
                <td ><?php echo $row["ip"]; ?>&nbsp;|&nbsp; <?php echo $sIpArea ;?></td>
            </tr>
        <?php }?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>

</html>
