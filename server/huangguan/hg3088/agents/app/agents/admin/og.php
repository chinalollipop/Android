<?php
/**
 * OG视讯注单记录
 * Date: 2019/7/1
 */

session_start();
require_once '../include/config.inc.php';
include_once "../include/address.mem.php";
require_once '../include/redis.php';
require_once '../../../../common/og/api.php';

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
$gamename = $_REQUEST['gamename'] ? trim($_REQUEST['gamename']) : '';
$bettingcode = $_REQUEST['bettingcode'] ? trim($_REQUEST['bettingcode']) : '';
$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

$sWhere = 1;
$sWhere .= " AND `md_bettingdate` BETWEEN '{$startTime}' AND '{$endTime}'";
if($gamename)
    $sWhere .= " AND `gamename` = '{$gamename}'";
if($bettingcode)
    $sWhere .= " AND `bettingcode` = '{$bettingcode}'";
if($username)
    $sWhere .= " AND `username` like '%{$username}'";

$mysql = "SELECT `userid`,`username`,`is_test`,`agents`,`world`,`corporator`,`super`,`admin`,`gamename`,`bettingcode`,`md_bettingdate`,`gameid`,`roundno`,`game_information`,`result`,`bet`,`winloseresult`,`bettingamount`,`validbet`,`winloseamount`,`balance`,`currency`,`status`,`gamecategory`,`created_at`
          FROM `" . DBPREFIX . "og_projects`
          WHERE $sWhere 
          ORDER BY `bettingdate` DESC";
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);

// 统计
$totalBet = $totalRealBet = $totalProfit = '0.00';
while($row = mysqli_fetch_assoc($result)){
    $totalBet += $row['bettingamount']; // 下注金额
    $totalRealBet += $row['validbet']; // 有效金额
    $totalProfit += $row['winloseamount']; // 输赢总额
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
    <title>OG视讯</title>
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
    <dt>OG视讯</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <select name="gamename">
                            <option value="">游戏名称</option>
                            <?php
                            foreach ($ogGamename as $key => $value) {
                                if ($key==$gamename) {
                                    echo '<option value="'.$key.'" seleted>'.$value.'</option>';
                                }
                                else{
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                                }

                            }
                            ?>
                        </select>
                        注单日期：
                        <input type="text" name="date_start" id="date_start" value="<?php echo $startTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至
                        <input type="text" name="date_end" id="date_end" value="<?php echo $endTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        订单号：
                        <input type="text" name="bettingcode" size=10 value="<?php echo $bettingcode;?>" maxlength=50 class="za_text">
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
            <td>注单编号</td>
            <td>交易时间</td>
            <td>游戏名称</td>
            <td>用户名</td>
            <td>桌台</td>
            <td>靴局</td>
            <td>下注区域</td>
            <td>下注金额</td>
            <td>玩家输赢金额</td>
            <td>玩家输赢结果</td>
            <td>余额</td>
            <td>有效投注金额(打码量)</td>
            <td>牌面讯息</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="13">暂无记录</td></tr>';
        }

        while($row = mysqli_fetch_assoc($result)){
            ?>
            <tr class="m_rig">
                <td width="5%"><?php echo $row['bettingcode']?></td>
                <td width="6%"><?php echo $row['md_bettingdate']?></td>
                <td width="5%" class="game_name"><?php echo $ogGamename[$row['gamename']]?></td>
                <td width="5%"><?php echo $row['username']?></td>
                <td width="4%"><?php echo $row['gameid']?></td>
                <td width="4%"><?php echo $row['roundno']?></td>
                <td width="8%">
                    <?php
                    echo $row['bet'];
                    ?>
                </td>
                <td width="5%"><?php echo $row['bettingamount']?></td>
                <td width="5%"><?php echo $row['winloseamount']?></td>
                <td width="5%"><?php echo $row['result'];?></td>
                <td width="5%"><?php echo $row['balance']?></td>
                <td width="5%"><?php echo $row['validbet']?></td>
                <td width="15%">
                    <?php
                    $aGameInformation = explode('@',$row['game_information']);

                    switch ($row['gamename']){
                        case 'Baccarat':
                            // 点数=牌/10 , 花色=牌%10
                            $card['player1'] = (floor($aGameInformation[0]/10));
                            $card['player2'] = (floor($aGameInformation[1]/10));
                            $card['player3'] = (floor($aGameInformation[2]/10));
                            $card['banker1'] = (floor($aGameInformation[3]/10));
                            $card['banker2'] = (floor($aGameInformation[4]/10));
                            $card['banker3'] = (floor($aGameInformation[5]/10));
                            echo '闲：'.$aCardsPoint[$card['player1']].$aCardsColor[$aGameInformation[0]%10].' ';
                            echo $aCardsPoint[$card['player2']].$aCardsColor[$aGameInformation[1]%10].' ';
                            echo $card['player3']>0?$aCardsPoint[$card['player3']].$aCardsColor[$aGameInformation[2]%10]:'';
                            echo '，';
                            echo '庄：'.$aCardsPoint[$card['banker1']].$aCardsColor[$aGameInformation[3]%10].' ';
                            echo $aCardsPoint[$card['banker2']].$aCardsColor[$aGameInformation[4]%10].' ';
                            echo $card['banker3']>0?$aCardsPoint[$card['banker3']].$aCardsColor[$aGameInformation[5]%10]:'';
                            break;
                        case 'Dragon Tiger':
                            $card['Dragon'] = (floor($aGameInformation[0]/10));
                            $card['Tiger'] = (floor($aGameInformation[1]/10));
                            echo '龙：'.$aCardsPoint[$card['Dragon']].$aCardsColor[$aGameInformation[0]%10].' ';
                            echo '，';
                            echo '虎：'.$aCardsPoint[$card['Tiger']].$aCardsColor[$aGameInformation[0]%10].' ';
                            break;
                        case 'Roulette':
                        case 'Sic Bo':
                        case 'Fantan':
                            echo $row['game_information'];
                            break;
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
