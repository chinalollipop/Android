<?php
/**
 * 视讯管理-金星代理
 * Date: 2018/11/7
 */
session_start();
require_once '../../include/config.inc.php';
include_once("../../../agents/include/address.mem.php");
include_once ('../../../../../common/bbin/api.php');

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

$orderid = $_REQUEST['orderid'] ? intval($_REQUEST['orderid']) : '';
$username = $_REQUEST['username'] ? trim($_REQUEST['username']) : ''; //strtoupper($platform_owner_id.$value['UserName'])

$sWhere = 1;
$sWhere .= " AND `endtime` BETWEEN '{$startTime}' AND '{$endTime}'";

if($orderid)
    $sWhere .= " AND `orderid` like '%{$orderid}%'";
if($username)
    //   john103 变成 CHJOHN103
    $platform_username = strtoupper($bb_member_ext_ref.$username);
    $sWhere .= " AND `username` like '%{$platform_username}%'";

$mysql = "SELECT `id`, `username`,`ip`,`orderid`,`prebalance`,`opebalance`,`balance`,`starttime`,`endtime`,`createtime`,`reason`
          FROM `" . DBPREFIX . "jx_agent_point`
          WHERE $sWhere 
          ORDER BY `endtime` DESC";
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);


// 统计
$totalBet = $totalRealBet = $totalWins = $totalProfit  = '0.00';
while($row = mysqli_fetch_assoc($result)){
    //$totalBet += $row['BetAmount']; //下注金额
    //$totalRealBet += $row['Commissionable']; // 有效投注
    $totalWins += $row['opebalance'];  //上下分
    //$totalProfit += ($row['Payoff'] + $row['BetAmount']);    // 盈利 ($row['all_wins'] - $row['all_bets'])

}

// 分页
$page_size = 50;
$page_count = ceil($count / $page_size);
$offset = $page * $page_size;
$mysql = $mysql . "  limit $offset, $page_size";
$result = mysqli_query($dbLink, $mysql);
//echo $mysql;

// 代理查询
$md5Key = $bbinSxInit['data_api_md5_key'];
$pointResult =  agentV1GetAgentPoints($bbin_agent, $md5Key);

if($pointResult['success']) {
    $agentCode = $pointResult['body']['data']['code'];
    $agentName = $pointResult['body']['data']['name'];
    $agentBalance = $pointResult['body']['data']['balance'];
}

//查询代理点数变动
//agentV1GetPointsChange($bbin_agent, $md5Key);
?>
<html>
<head>
    <title>金星代理</title>
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
    <dt>金星-<?php echo $agentCode;?>-代理</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        &nbsp;
                        点数日期：
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

                总代理:  <?php echo $agentCode; ?>  -剩余分数：<span><?php echo   sprintf("%.2f", $agentBalance);?></span>&nbsp;&nbsp;&nbsp;
                近一月用分：<span><?php echo sprintf("%.2f", $totalWins);?></span>&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="color: red">注：正数为转入,负数为转出！</span>

            </td>
        </tr>
        <tr class="m_title">
            <!--<td>游戏名称</td>-->
            <td>用户名</td>
            <td>订单号</td>
            <td>之前点数</td>
            <td>变动点数</td>
            <td>当前点数</td>
            <td>开始时间</td>
            <td>结束时间</td>
            <td>代理备注</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="17">暂无记录</td></tr>';
        }

        $bbin_prefix = strtoupper($bbin_prefix); //前缀大写
        while($row = mysqli_fetch_assoc($result)){
            //$username = explode(strtoupper($bb_member_ext_ref),$row['username'], 2)['1'];
            $username =  strtolower(substr($row['username'], strlen($bbin_prefix)));
            ?>
            <tr class="m_rig">
                <td width="3%"><?php echo $username; ?></td>
                <td width="3%"><?php echo $row['orderid']?></td>
                <td width="3%"><?php echo number_format($row['prebalance'],2)?></td>
                <td width="3%"><?php echo number_format($row['opebalance'],2)?></td>
                <td width="3%"><?php echo number_format($row['balance'],2)?></td>
                <td width="3%"><?php echo $row['starttime']?></td>
                <td width="3%"><?php echo $row['endtime']?></td>
                <td width="3%"><?php echo $row['reason']?></td>
            </tr>
        <?php }?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../../js/agents/jquery.js"></script>
<script type="text/javascript">
</script>
</html>




