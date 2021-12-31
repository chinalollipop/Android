<?php
/**
 * 会员充值统计
 * Date: 2019/11/16
 */
include ("../../agents/include/address.mem.php");
require ("../../agents/include/config.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$today = date('Y-m-d');
$date_start = $_REQUEST['date_start'];
$date_end = $_REQUEST['date_end'];
$agent = $_REQUEST['agent']; // 代理商
if ($date_start == ''){
    $date_start = date('Y-m-d', strtotime('-7 day'));
    $date_end = $today;
}
if($date_start > $date_end){
    echo "<script>alert('您选择时间错误，开始时间不能大于结束时间!');history.go(-1);</script>";
    exit;
}

// 查询时间判断（每日15:00）
$isCurrent = false;
$isYesterday = false;
if($date_start == date("Y-m-d") && $date_end >= date("Y-m-d") ) {
    $isCurrent = true;
    $history_date_end = date("Y-m-d", strtotime("-1 day"));
}elseif($date_start < date("Y-m-d") && $date_end >= date("Y-m-d") && (int)date("G") < 3) {
    $isCurrent = true;
    $isYesterday = true;
    $history_date_end = date("Y-m-d", strtotime("-2 day"));
}else if($date_start < date("Y-m-d") && $date_end == date("Y-m-d", strtotime("-1 day")) && (int)date("G") < 3) {
    $isCurrent = true;
    $history_date_end = date("Y-m-d", strtotime("-2 day"));
}else if($date_start <= date("Y-m-d") && $date_end >= date("Y-m-d") && (int)date("G") >= 3) {
    $isCurrent = true;
    $history_date_end = date("Y-m-d", strtotime("-1 day"));
}else{
    $history_date_end = date("Y-m-d", strtotime("-1 day"));
}

$table = 'web_member_daily_count';
$where = ' WHERE `count_date` BETWEEN "' . $date_start . '" AND "' . $history_date_end . '"';
if($agent){
    $table = 'web_agents_daily_count';
    $where .= ' AND `agent_name` = "' . $agent . '"';
}
// 若统计时间为今日，则获取今日数据
$countDataToday = [];
if($isCurrent){
    if($isYesterday){
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $countMember = countMember($yesterday . ' 00:00:00', $yesterday . ' 23:59:59', $agent);
        $countDataToday[$yesterday] = [
            'total_reg' => $countMember['reg'],
            'total_reg_deposit' => $countMember['deposit']
        ];
    }
    $countMember = countMember($today . ' 00:00:00', $today . ' 23:59:59', $agent);
    $countDataToday[$today] = [
        'total_reg' => $countMember['reg'],
        'total_reg_deposit' => $countMember['deposit']
    ];
}

// 历史统计数据
$countDataHistory = [];
$sql = 'SELECT * FROM ' . DBPREFIX . $table . $where . ' ORDER BY `count_date` ASC';
$result = mysqli_query($dbLink, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $countDataHistory[$row['count_date']] = [
        'total_reg' => $row['total_reg'],
        'total_reg_deposit' => $row['total_reg_deposit']
    ];
}
$countData = array_merge($countDataHistory, $countDataToday);

$regCount = $regDepositCount = [];
$countDate = dayRange($date_start, $date_end,'day');
foreach ($countDate as $day){
    $regCount[] = isset($countData[$day]['total_reg']) ? $countData[$day]['total_reg'] : 0;
    $regDepositCount[] = isset($countData[$day]['total_reg_deposit']) ? $countData[$day]['total_reg_deposit'] : 0;
}

?>

<html>
<head>
    <title>会员充值统计</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        .myChart {
            max-width: 85% !important;
            max-height: 65% !important;
            margin:0 auto;
        }
        .toggleButton {
            position: fixed;
            right: 35%;
            margin-top: 15px;
            font-size: 13px;
        }
    </style>
</head>

<body>
<dl class="main-nav"><dt>会员充值统计</dt><dd>当日注册会员/当日注册且有充值会员数量</dd></dl>
<div class="main-ui">
    <div style="color: red; font-size: 14px; font-weight: bold; padding-left: 400px;">
        注意：<br>
        1、统计时间为美东时间凌晨三点<br>
        2、当日数据为实时数据<br><br>
    </div>
    <table class="m_tab">
        <FORM id="myFORM" ACTION="" METHOD=POST name="FrmData">
            <input type="hidden" name="action_type" value=""/>
            <tr class="m_title">
                <td colspan="10">
                    会员充值统计：
                    <!-- l 昨日  t 今日  n 明日  w 本星期  lw 上星期 m 本月 lm 上个月 -->
                    <input type="button" class="za_button" onClick="chg_date('t')" value="今日">
                    <input type="button" class="za_button" onClick="chg_date('l')" value="昨日">
                    <input type="button" class="za_button" onClick="chg_date('w')" value="本周">
                    <input type="button" class="za_button" onClick="chg_date('lw')" value="上周">
                    <input type="button" class="za_button" onClick="chg_date('m')" value="本月">
                    <input type="button" class="za_button" onClick="chg_date('lm')" value="上个月">
                </td>
            </tr>
            <tr class="m_title">
                <td colspan="9">
                    <span id="ShowTime"></span>
                    时间区间：
                    <input type="text" name="date_start" id="date_start" value="<?php echo $date_start?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" size=15 maxlength=11 class="za_text" readonly>
                    至
                    <input type="text" name="date_end" id="date_end" value="<?php echo $date_end?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" size=15 maxlength=11 class="za_text" readonly>
<!--                    &nbsp;代理商:-->
<!--                    <input type=TEXT name="agent" size=10 value="--><?php //echo $_REQUEST['agent']?><!--" maxlength=20 class="za_text">-->
                    <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
                </td>
            </tr>
        </FORM>
    </table>
    <button onclick="toggleChart()" class="toggleButton">柱形/折线</button>
    <canvas id="myChart" class="myChart chartjs-render-monitor" style="display: block; width: 1454px; height: 727px;" width="1454" height="727"></canvas>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script type="text/javascript" src="../../../js/agents/chat/chat.min.js"></script>
<script type="text/javascript" src="../../../js/agents/chat/chartjs-plugin-datalabels.min.js"></script>
<script language="javascript">
    var countDate = '<?php echo json_encode($countDate);?>';
    var regCount = '<?php echo json_encode($regCount);?>';
    var regDepositCount = '<?php echo json_encode($regDepositCount);?>';

    countDate = JSON.parse(countDate);
    regCount = JSON.parse(regCount);
    regDepositCount = JSON.parse(regDepositCount);

    var ctx = document.getElementById('myChart').getContext('2d');
    var config = {
        type: 'bar', // 'line'
        data: {
            labels: countDate,
            datasets: [{
                label: "注册会员数",
                fill: false,
                backgroundColor: 'rgb(0, 153, 255)',
                borderColor: 'rgb(0, 153, 255)',
                data: regCount,
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    offset: -8
                }
            }, {
                label: "注册且有充值会员数",
                fill: false,
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: regDepositCount,
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    offset: -8
                }
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    color: 'black',
                    display: true,
                    font: {
                        weight: 'bold',
                        size: 12
                    }
                }
            },
            title: {
                text: '每日-注册会员数/注册且有充值会员数',
                display: true,
                fontSize: 14
            }
        }
    };
    var chart = new Chart(ctx, config);

    function toggleChart() {
        config.type = config.type == 'line' ? 'bar' : 'line';
        chart.destroy();
        chart = new Chart(ctx, config);
    }

    function chg_date(range) {
        //  l 昨日  t 今日  n 明日  w 本星期  lw 上星期 m 本月 lm 上个月
        var date_start;
        var date_end;
        switch (range) {
            case 'l': // 昨日
                date_start = '<?php echo date('Y-m-d', strtotime($today) - 86400);?>';
                date_end = '<?php echo date('Y-m-d', strtotime($today) - 86400);?>';
                break;
            case 't': // 今日
                date_start = '<?php echo $today;?>';
                date_end = '<?php echo $today;?>';
                break;
            case 'n': // 明日
                date_start = '<?php echo date('Y-m-d', strtotime($today) + 86400);?>';
                date_end = '<?php echo date('Y-m-d', strtotime($today) + 86400);?>';
                break;
            case 'w': // 本周
                date_start = '<?php echo date('Y-m-d', strtotime("this week"));?>';
                date_end = '<?php echo date('Y-m-d', strtotime("last day next week"));?>';
                break;
            case 'lw': // 上周
                date_start = '<?php echo date('Y-m-d', strtotime("last week Monday"));?>';
                date_end = '<?php echo date('Y-m-d', strtotime("last week Sunday"));?>';
                break;
            case 'm': // 本月
                date_start = '<?php echo date('Y-m-d', strtotime(date('Y-m', time()) . '-01'));?>';
                date_end = '<?php echo date('Y-m-d', strtotime(date('Y-m', time()) . '-' . date('t', time()) . ''));?>';
                break;
            case 'lm': // 上个月
                date_start = '<?php echo date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m', time()) . '-01')));?>';
                date_end = '<?php echo date('Y-m-d', strtotime(date('Y-m', time()) . '-01') - 86400);?>';
                break;
        }
        FrmData.date_start.value = date_start;
        FrmData.date_end.value = date_end;
    }
</script>
</body>
</html>