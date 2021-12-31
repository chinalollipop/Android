<?php
/**
 * 代理商佣金
 *
 * 注意：按月查询
 * Date: 2019/12/10
 */
include_once ("../include/address.mem.php");
require ("../../agents/include/config.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$resdata = array();
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
// 普通代理商
if($_SESSION['Level'] == 'D'){
    $agent = $_SESSION['UserName'];
}else{
    $agent = isset($_REQUEST['agent']) && $_REQUEST['agent'] ? trim($_REQUEST['agent']) : '';
}

$date = !isset($_REQUEST['date']) ? date('Y-m', strtotime('-1 month')) : trim($_REQUEST['date']);
// 按照每月查询数据（查询月第一天&月最后一天）
$dateStart = date('Y-m-01 00:00:00', strtotime($date));
$dateEnd = date('Y-m-d 23:59:59', strtotime($date . '-' . date('t', strtotime($date)) . ''));
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 0;

// 查询时间月份范围
$dateRange = monthRange(3);
$index = array_search('2019-12', $dateRange); // 2019-12是开始时间点，之后默认显示3个月内数据
if($index !== false){
    array_splice($dateRange, $index + 1);
}

// 查询代理商
$sWhere = "`Level`= 'D'";
if($agent){
    $sWhere .= " AND `UserName`='$agent'";
}

$sql = "SELECT `ID`, `UserName` FROM " . DBPREFIX . "web_agents_data WHERE $sWhere";
$result = mysqli_query($dbLink, $sql);
$count = mysqli_num_rows($result);

// 分页
$page_size = 20;
$page_count = ceil($count / $page_size);
$offset = $page * $page_size;

// 分页查询结果
$agents = [];
$sql = $sql . "  LIMIT $offset, $page_size";
$result = mysqli_query($dbLink, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $agents[$row['UserName']] = [
        'id' => $row['ID'],
        'username' => $row['UserName'],
    ];
}

// 分页查询代理统计数据
$pageAgent = array_keys($agents);

// 1.从历史报表中获取查询的一个月的代理统计数据
$agentMonthData = countHistoryBetAgent($dateStart, $dateEnd, $pageAgent);

// 2.从返水报表中获取的一个月的代理统计数据
$agentRebateData = countAgentRebate($dateStart, $dateEnd, $pageAgent);

// 3.查询截止搜索月份代理商下会员数量
$agentMemberNum = countAgentMemberNum('1969-12-31', $dateEnd, $pageAgent);

// 4.查询代理存取款、优惠、彩金统计数据
$agentOrder = agentOrderCount($dateStart, $dateEnd, $pageAgent);

// 5.查询行政费用比例、出入款手续费
$redisObj = new Ciredis();
$agentsSetJson= $redisObj->getSimpleOne('agent_fee_set');
$agentsRateSet = json_decode($agentsSetJson,true);
$agentsFeeRate = $agentsRateSet['company_fee'];
$agentsDepositRate = $agentsRateSet['deposit_fee'];
$agentsDepositLimit = $agentsRateSet['deposit_fee_limit'];
$agentsWithdrawRate = $agentsRateSet['withdraw_fee'];
$agentsWithdrawLimit = $agentsRateSet['withdraw_fee_limit'];
$giftFeeRate = $agentsRateSet['gift_fee'];

// 6.三方平台抽水比例
$thirdWaterJson= $redisObj->getSimpleOne('third_water_set');
$thirdRateSet = json_decode($thirdWaterJson,true);

$monthData = [];
foreach ($agents as $key => $value){
    $monthData[$key] = [
        'member_num' => isset($agentMemberNum[$key]) ? $agentMemberNum[$key]['member_num'] : 0,
        'total_deposit' => isset($agentOrder[$key]) ? $agentOrder[$key]['total_deposit'] : 0,
        'total_withdraw' => isset($agentOrder[$key]) ? $agentOrder[$key]['total_withdraw'] : 0,
        'total_extra' => isset($agentOrder[$key]) ? $agentOrder[$key]['total_extra'] : 0,
        'total_gift' => isset($agentOrder[$key]) ? $agentOrder[$key]['total_gift'] : 0,
        'user_win' => [
            'hg' => isset($agentMonthData['hg'][$key]) ? $agentMonthData['hg'][$key]['user_win'] : 0,
            'cp' => isset($agentMonthData['cp'][$value['id']]) ? $agentMonthData['cp'][$value['id']]['user_win'] : 0,
            'ag' => isset($agentMonthData['ag'][$key]) ? $agentMonthData['ag'][$key]['user_win'] : 0,
            'ag_dianzi' => isset($agentMonthData['ag_dianzi'][$key]) ? $agentMonthData['ag_dianzi'][$key]['user_win'] : 0,
            'ag_dayu' => isset($agentMonthData['ag_dayu'][$key]) ? $agentMonthData['ag_dayu'][$key]['user_win'] : 0,
            'ky' => isset($agentMonthData['ky'][$key]) ? $agentMonthData['ky'][$key]['user_win'] : 0,
            'hgqp' => isset($agentMonthData['hgqp'][$key]) ? $agentMonthData['hgqp'][$key]['user_win'] : 0,
            'vgqp' => isset($agentMonthData['vgqp'][$key]) ? $agentMonthData['vgqp'][$key]['user_win'] : 0,
            'lyqp' => isset($agentMonthData['lyqp'][$key]) ? $agentMonthData['lyqp'][$key]['user_win'] : 0,
            'klqp' => isset($agentMonthData['klqp'][$key]) ? $agentMonthData['klqp'][$key]['user_win'] : 0,
            'mg' => isset($agentMonthData['mg'][$key]) ? $agentMonthData['mg'][$key]['user_win'] : 0,
            'avia' => isset($agentMonthData['avia'][$key]) ? $agentMonthData['avia'][$key]['user_win'] : 0,
            'fire' => isset($agentMonthData['fire'][$key]) ? $agentMonthData['fire'][$key]['user_win'] : 0,
            'ssc' => isset($agentMonthData['ssc'][$key]) ? $agentMonthData['ssc'][$key]['user_win'] : 0,
            'project' => isset($agentMonthData['project'][$key]) ? $agentMonthData['project'][$key]['user_win'] : 0,
            'trace' => isset($agentMonthData['trace'][$key]) ? $agentMonthData['trace'][$key]['user_win'] : 0,
            'og' => isset($agentMonthData['og'][$key]) ? $agentMonthData['og'][$key]['user_win'] : 0,
            'mw' => isset($agentMonthData['mw'][$key]) ? $agentMonthData['mw'][$key]['user_win'] : 0,
            'cq' => isset($agentMonthData['cq'][$key]) ? $agentMonthData['cq'][$key]['user_win'] : 0,
            'fg' => isset($agentMonthData['fg'][$key]) ? $agentMonthData['fg'][$key]['user_win'] : 0,
            'bbin' => isset($agentMonthData['bbin'][$key]) ? $agentMonthData['bbin'][$key]['user_win'] : 0,
        ],
        'valid_money' => [
            'hg' => isset($agentMonthData['hg'][$key]) ? $agentMonthData['hg'][$key]['valid_money'] : 0,
            'cp' => isset($agentMonthData['cp'][$value['id']]) ? $agentMonthData['cp'][$value['id']]['valid_money'] : 0,
            'ag' => isset($agentMonthData['ag'][$key]) ? $agentMonthData['ag'][$key]['valid_money'] : 0,
            'ag_dianzi' => isset($agentMonthData['ag_dianzi'][$key]) ? $agentMonthData['ag_dianzi'][$key]['valid_money'] : 0,
            'ag_dayu' => isset($agentMonthData['ag_dayu'][$key]) ? $agentMonthData['ag_dayu'][$key]['valid_money'] : 0,
            'ky' => isset($agentMonthData['ky'][$key]) ? $agentMonthData['ky'][$key]['valid_money'] : 0,
            'hgqp' => isset($agentMonthData['hgqp'][$key]) ? $agentMonthData['hgqp'][$key]['valid_money'] : 0,
            'vgqp' => isset($agentMonthData['vgqp'][$key]) ? $agentMonthData['vgqp'][$key]['valid_money'] : 0,
            'lyqp' => isset($agentMonthData['lyqp'][$key]) ? $agentMonthData['lyqp'][$key]['valid_money'] : 0,
            'klqp' => isset($agentMonthData['klqp'][$key]) ? $agentMonthData['klqp'][$key]['valid_money'] : 0,
            'mg' => isset($agentMonthData['mg'][$key]) ? $agentMonthData['mg'][$key]['valid_money'] : 0,
            'avia' => isset($agentMonthData['avia'][$key]) ? $agentMonthData['avia'][$key]['valid_money'] : 0,
            'fire' => isset($agentMonthData['fire'][$key]) ? $agentMonthData['fire'][$key]['valid_money'] : 0,
            'ssc' => isset($agentMonthData['ssc'][$key]) ? $agentMonthData['ssc'][$key]['valid_money'] : 0,
            'project' => isset($agentMonthData['project'][$key]) ? $agentMonthData['project'][$key]['valid_money'] : 0,
            'trace' => isset($agentMonthData['trace'][$key]) ? $agentMonthData['trace'][$key]['valid_money'] : 0,
            'og' => isset($agentMonthData['og'][$key]) ? $agentMonthData['og'][$key]['valid_money'] : 0,
            'mw' => isset($agentMonthData['mw'][$key]) ? $agentMonthData['mw'][$key]['valid_money'] : 0,
            'cq' => isset($agentMonthData['cq'][$key]) ? $agentMonthData['cq'][$key]['valid_money'] : 0,
            'fg' => isset($agentMonthData['fg'][$key]) ? $agentMonthData['fg'][$key]['valid_money'] : 0,
            'bbin' => isset($agentMonthData['bbin'][$key]) ? $agentMonthData['bbin'][$key]['valid_money'] : 0,
        ],
        'water_rate' => [
            'hg' => isset($agentMonthData['hg'][$key]) ? $agentMonthData['hg'][$key]['water_rate'] : 0,
            'cp' => isset($agentMonthData['cp'][$value['id']]) ? $agentMonthData['cp'][$value['id']]['water_rate'] : 0,
            'ag' => isset($agentMonthData['ag'][$key]) ? $agentMonthData['ag'][$key]['water_rate'] : 0,
            'ag_dianzi' => isset($agentMonthData['ag_dianzi'][$key]) ? $agentMonthData['ag_dianzi'][$key]['water_rate'] : 0,
            'ag_dayu' => isset($agentMonthData['ag_dayu'][$key]) ? $agentMonthData['ag_dayu'][$key]['water_rate'] : 0,
            'ky' => isset($agentMonthData['ky'][$key]) ? $agentMonthData['ky'][$key]['water_rate'] : 0,
            'hgqp' => isset($agentMonthData['hgqp'][$key]) ? $agentMonthData['hgqp'][$key]['water_rate'] : 0,
            'vgqp' => isset($agentMonthData['vgqp'][$key]) ? $agentMonthData['vgqp'][$key]['water_rate'] : 0,
            'lyqp' => isset($agentMonthData['lyqp'][$key]) ? $agentMonthData['lyqp'][$key]['water_rate'] : 0,
            'klqp' => isset($agentMonthData['klqp'][$key]) ? $agentMonthData['klqp'][$key]['water_rate'] : 0,
            'mg' => isset($agentMonthData['mg'][$key]) ? $agentMonthData['mg'][$key]['water_rate'] : 0,
            'avia' => isset($agentMonthData['avia'][$key]) ? $agentMonthData['avia'][$key]['water_rate'] : 0,
            'fire' => isset($agentMonthData['fire'][$key]) ? $agentMonthData['fire'][$key]['water_rate'] : 0,
            'ssc' => isset($agentMonthData['ssc'][$key]) ? $agentMonthData['ssc'][$key]['water_rate'] : 0,
            'project' => isset($agentMonthData['project'][$key]) ? $agentMonthData['project'][$key]['water_rate'] : 0,
            'trace' => isset($agentMonthData['trace'][$key]) ? $agentMonthData['trace'][$key]['water_rate'] : 0,
            'og' => isset($agentMonthData['og'][$key]) ? $agentMonthData['og'][$key]['water_rate'] : 0,
            'mw' => isset($agentMonthData['mw'][$key]) ? $agentMonthData['mw'][$key]['water_rate'] : 0,
            'cq' => isset($agentMonthData['cq'][$key]) ? $agentMonthData['cq'][$key]['water_rate'] : 0,
            'fg' => isset($agentMonthData['fg'][$key]) ? $agentMonthData['fg'][$key]['water_rate'] : 0,
            'bbin' => isset($agentMonthData['bbin'][$key]) ? $agentMonthData['bbin'][$key]['water_rate'] : 0,
        ],
        'commission_rate' => [
            'hg' => isset($agentMonthData['hg'][$key]) ? $agentMonthData['hg'][$key]['commission_rate'] : 0,
            'cp' => isset($agentMonthData['cp'][$value['id']]) ? $agentMonthData['cp'][$value['id']]['commission_rate'] : 0,
            'ag' => isset($agentMonthData['ag'][$key]) ? $agentMonthData['ag'][$key]['commission_rate'] : 0,
            'ag_dianzi' => isset($agentMonthData['ag_dianzi'][$key]) ? $agentMonthData['ag_dianzi'][$key]['commission_rate'] : 0,
            'ag_dayu' => isset($agentMonthData['ag_dayu'][$key]) ? $agentMonthData['ag_dayu'][$key]['commission_rate'] : 0,
            'ky' => isset($agentMonthData['ky'][$key]) ? $agentMonthData['ky'][$key]['commission_rate'] : 0,
            'hgqp' => isset($agentMonthData['hgqp'][$key]) ? $agentMonthData['hgqp'][$key]['commission_rate'] : 0,
            'vgqp' => isset($agentMonthData['vgqp'][$key]) ? $agentMonthData['vgqp'][$key]['commission_rate'] : 0,
            'lyqp' => isset($agentMonthData['lyqp'][$key]) ? $agentMonthData['lyqp'][$key]['commission_rate'] : 0,
            'klqp' => isset($agentMonthData['klqp'][$key]) ? $agentMonthData['klqp'][$key]['commission_rate'] : 0,
            'mg' => isset($agentMonthData['mg'][$key]) ? $agentMonthData['mg'][$key]['commission_rate'] : 0,
            'avia' => isset($agentMonthData['avia'][$key]) ? $agentMonthData['avia'][$key]['commission_rate'] : 0,
            'fire' => isset($agentMonthData['fire'][$key]) ? $agentMonthData['fire'][$key]['commission_rate'] : 0,
            'ssc' => isset($agentMonthData['ssc'][$key]) ? $agentMonthData['ssc'][$key]['commission_rate'] : 0,
            'project' => isset($agentMonthData['project'][$key]) ? $agentMonthData['project'][$key]['commission_rate'] : 0,
            'trace' => isset($agentMonthData['trace'][$key]) ? $agentMonthData['trace'][$key]['commission_rate'] : 0,
            'og' => isset($agentMonthData['og'][$key]) ? $agentMonthData['og'][$key]['commission_rate'] : 0,
            'mw' => isset($agentMonthData['mw'][$key]) ? $agentMonthData['mw'][$key]['commission_rate'] : 0,
            'cq' => isset($agentMonthData['cq'][$key]) ? $agentMonthData['cq'][$key]['commission_rate'] : 0,
            'fg' => isset($agentMonthData['fg'][$key]) ? $agentMonthData['fg'][$key]['commission_rate'] : 0,
            'bbin' => isset($agentMonthData['bbin'][$key]) ? $agentMonthData['bbin'][$key]['commission_rate'] : 0,
        ],
        'mem_rebate' => [
            'total' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['total'] : 0,
            'hg' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['hg'] : 0,
            'cp' => 0,
            'ag' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['ag'] : 0,
            'ag_dianzi' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['ag_dianzi'] : 0,
            'ag_dayu' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['ag_dayu'] : 0,
            'ky' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['ky'] : 0,
            'hgqp' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['hgqp'] : 0,
            'vgqp' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['vgqp'] : 0,
            'lyqp' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['lyqp'] : 0,
            'klqp' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['klqp'] : 0,
            'mg' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['mg'] : 0,
            'avia' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['avia'] : 0,
            'fire' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['fire'] : 0,
            'ssc' => 0,
            'project' => 0,
            'trace' => 0,
            'og' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['og'] : 0,
            'mw' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['mw'] : 0,
            'cq' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['cq'] : 0,
            'fg' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['fg'] : 0,
            'bbin' => isset($agentRebateData[$key]) ? $agentRebateData[$key]['bbin'] : 0,
        ],
        'agent_fee' => [ // 行政费：厅室输赢(取正数) x 行政费比例
            'hg' => isset($agentMonthData['hg'][$key]) ? abs($agentMonthData['hg'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'cp' => isset($agentMonthData['cp'][$value['id']]) ? abs($agentMonthData['cp'][$value['id']]['user_win']) * $agentsFeeRate / 100 : 0,
            'ag' => isset($agentMonthData['ag'][$key]) ? abs($agentMonthData['ag'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'ag_dianzi' => isset($agentMonthData['ag_dianzi'][$key]) ? abs($agentMonthData['ag_dianzi'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'ag_dayu' => isset($agentMonthData['ag_dayu'][$key]) ? abs($agentMonthData['ag_dayu'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'ky' => isset($agentMonthData['ky'][$key]) ? abs($agentMonthData['ky'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'hgqp' => isset($agentMonthData['hgqp'][$key]) ? abs($agentMonthData['hgqp'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'vgqp' => isset($agentMonthData['vgqp'][$key]) ? abs($agentMonthData['vgqp'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'lyqp' => isset($agentMonthData['lyqp'][$key]) ? abs($agentMonthData['lyqp'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'klqp' => isset($agentMonthData['klqp'][$key]) ? abs($agentMonthData['klqp'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'mg' => isset($agentMonthData['mg'][$key]) ? abs($agentMonthData['mg'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'avia' => isset($agentMonthData['avia'][$key]) ? abs($agentMonthData['avia'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'fire' => isset($agentMonthData['fire'][$key]) ? abs($agentMonthData['fire'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'ssc' => isset($agentMonthData['ssc'][$key]) ? abs($agentMonthData['ssc'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'project' => isset($agentMonthData['project'][$key]) ? abs($agentMonthData['project'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'trace' => isset($agentMonthData['trace'][$key]) ? abs($agentMonthData['trace'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'og' => isset($agentMonthData['og'][$key]) ? abs($agentMonthData['og'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'mw' => isset($agentMonthData['mw'][$key]) ? abs($agentMonthData['mw'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'cq' => isset($agentMonthData['cq'][$key]) ? abs($agentMonthData['cq'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'fg' => isset($agentMonthData['fg'][$key]) ? abs($agentMonthData['fg'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
            'bbin' => isset($agentMonthData['bbin'][$key]) ? abs($agentMonthData['bbin'][$key]['user_win']) * $agentsFeeRate / 100 : 0,
        ],
        'third_fee' => [ // 平台抽水：厅室输赢 x 抽水比例
            'hg' => isset($agentMonthData['hg'][$key]) ? abs($agentMonthData['hg'][$key]['user_win']) * $thirdRateSet['hg'] / 100 : 0,
            'cp' => isset($agentMonthData['cp'][$value['id']]) ? abs($agentMonthData['cp'][$value['id']]['user_win']) * $thirdRateSet['cp'] / 100 : 0,
            'ag' => isset($agentMonthData['ag'][$key]) ? abs($agentMonthData['ag'][$key]['user_win']) * $thirdRateSet['ag'] / 100 : 0,
            'ag_dianzi' => isset($agentMonthData['ag_dianzi'][$key]) ? abs($agentMonthData['ag_dianzi'][$key]['user_win']) * $thirdRateSet['ag_dianzi'] / 100 : 0,
            'ag_dayu' => isset($agentMonthData['ag_dayu'][$key]) ? abs($agentMonthData['ag_dayu'][$key]['user_win']) * $thirdRateSet['ag_dayu'] / 100 : 0,
            'ky' => isset($agentMonthData['ky'][$key]) ? abs($agentMonthData['ky'][$key]['user_win']) * $thirdRateSet['ky'] / 100 : 0,
            'hgqp' => isset($agentMonthData['hgqp'][$key]) ? abs($agentMonthData['hgqp'][$key]['user_win']) * $thirdRateSet['hgqp'] / 100 : 0,
            'vgqp' => isset($agentMonthData['vgqp'][$key]) ? abs($agentMonthData['vgqp'][$key]['user_win']) * $thirdRateSet['vgqp'] / 100 : 0,
            'lyqp' => isset($agentMonthData['lyqp'][$key]) ? abs($agentMonthData['lyqp'][$key]['user_win']) * $thirdRateSet['lyqp'] / 100 : 0,
            'klqp' => isset($agentMonthData['klqp'][$key]) ? abs($agentMonthData['klqp'][$key]['user_win']) * $thirdRateSet['klqp'] / 100 : 0,
            'mg' => isset($agentMonthData['mg'][$key]) ? abs($agentMonthData['mg'][$key]['user_win']) * $thirdRateSet['mg'] / 100 : 0,
            'avia' => isset($agentMonthData['avia'][$key]) ? abs($agentMonthData['avia'][$key]['user_win']) * $thirdRateSet['avia'] / 100 : 0,
            'fire' => isset($agentMonthData['fire'][$key]) ? abs($agentMonthData['fire'][$key]['user_win']) * $thirdRateSet['fire'] / 100 : 0,
            'ssc' => isset($agentMonthData['ssc'][$key]) ? abs($agentMonthData['ssc'][$key]['user_win']) * $thirdRateSet['ssc'] / 100 : 0,
            'project' => isset($agentMonthData['project'][$key]) ? abs($agentMonthData['project'][$key]['user_win']) * $thirdRateSet['project'] / 100 : 0,
            'trace' => isset($agentMonthData['trace'][$key]) ? abs($agentMonthData['trace'][$key]['user_win']) * $thirdRateSet['trace'] / 100 : 0,
            'og' => isset($agentMonthData['og'][$key]) ? abs($agentMonthData['og'][$key]['user_win']) * $thirdRateSet['og'] / 100 : 0,
            'mw' => isset($agentMonthData['mw'][$key]) ? abs($agentMonthData['mw'][$key]['user_win']) * $thirdRateSet['mw'] / 100 : 0,
            'cq' => isset($agentMonthData['cq'][$key]) ? abs($agentMonthData['cq'][$key]['user_win']) * $thirdRateSet['cq'] / 100 : 0,
            'fg' => isset($agentMonthData['fg'][$key]) ? abs($agentMonthData['fg'][$key]['user_win']) * $thirdRateSet['fg'] / 100 : 0,
            'bbin' => isset($agentMonthData['bbin'][$key]) ? abs($agentMonthData['bbin'][$key]['user_win']) * $thirdRateSet['bbin'] / 100 : 0,
        ]
    ];
}

if($action == 'api'){
foreach ($monthData as $key => $value) {
    // 佣金计算公式:(0 - 会员输赢 - 返水总额 - 行政费 - 三方抽水) x 退佣比例 + (有效投注 x 退水比例) = 厅室佣金
    $commission_hg = (0 - $value['user_win']['hg'] - $value['mem_rebate']['hg'] - $value['agent_fee']['hg'] - $value['third_fee']['hg']) * $value['commission_rate']['hg'] / 100 + ($value['valid_money']['hg'] * $value['water_rate']['hg'] / 100);
    $commission_cp = (0 - $value['user_win']['cp'] - $value['mem_rebate']['cp'] - $value['agent_fee']['cp'] - $value['third_fee']['cp']) * $value['commission_rate']['cp'] / 100 + ($value['valid_money']['cp'] * $value['water_rate']['cp'] / 100);
    $commission_ag = (0 - $value['user_win']['ag'] - $value['mem_rebate']['ag'] - $value['agent_fee']['ag'] - $value['third_fee']['ag']) * $value['commission_rate']['ag'] / 100 + ($value['valid_money']['ag'] * $value['water_rate']['ag'] / 100);
    $commission_ag_dianzi = (0 - $value['user_win']['ag_dianzi'] - $value['mem_rebate']['ag_dianzi'] - $value['agent_fee']['ag_dianzi'] - $value['third_fee']['ag_dianzi']) * $value['commission_rate']['ag_dianzi'] / 100 + ($value['valid_money']['ag_dianzi'] * $value['water_rate']['ag_dianzi'] / 100);
    $commission_ag_dayu = (0 - $value['user_win']['ag_dayu'] - $value['mem_rebate']['ag_dayu'] - $value['agent_fee']['ag_dayu'] - $value['third_fee']['ag_dayu']) * $value['commission_rate']['ag_dayu'] / 100 + ($value['valid_money']['ag_dayu'] * $value['water_rate']['ag_dayu'] / 100);
    $commission_ky = (0 - $value['user_win']['ky'] - $value['mem_rebate']['ky'] - $value['agent_fee']['ky'] - $value['third_fee']['ky']) * $value['commission_rate']['ky'] / 100 + ($value['valid_money']['ky'] * $value['water_rate']['ky'] / 100);
    $commission_hgqp = (0 - $value['user_win']['hgqp'] - $value['mem_rebate']['hgqp'] - $value['agent_fee']['hgqp'] - $value['third_fee']['hgqp']) * $value['commission_rate']['hgqp'] / 100 + ($value['valid_money']['hgqp'] * $value['water_rate']['hgqp'] / 100);
    $commission_vgqp = (0 - $value['user_win']['vgqp'] - $value['mem_rebate']['vgqp'] - $value['agent_fee']['vgqp'] - $value['third_fee']['vgqp']) * $value['commission_rate']['vgqp'] / 100 + ($value['valid_money']['vgqp'] * $value['water_rate']['vgqp'] / 100);
    $commission_lyqp = (0 - $value['user_win']['lyqp'] - $value['mem_rebate']['lyqp'] - $value['agent_fee']['lyqp'] - $value['third_fee']['lyqp']) * $value['commission_rate']['lyqp'] / 100 + ($value['valid_money']['lyqp'] * $value['water_rate']['lyqp'] / 100);
    $commission_klqp = (0 - $value['user_win']['klqp'] - $value['mem_rebate']['klqp'] - $value['agent_fee']['klqp'] - $value['third_fee']['klqp']) * $value['commission_rate']['klqp'] / 100 + ($value['valid_money']['klqp'] * $value['water_rate']['klqp'] / 100);
    $commission_mg = (0 - $value['user_win']['mg'] - $value['mem_rebate']['mg'] - $value['agent_fee']['mg'] - $value['third_fee']['mg']) * $value['commission_rate']['mg'] / 100 + ($value['valid_money']['mg'] * $value['water_rate']['mg'] / 100);
    $commission_avia = (0 - $value['user_win']['avia'] - $value['mem_rebate']['avia'] - $value['agent_fee']['avia'] - $value['third_fee']['avia']) * $value['commission_rate']['avia'] / 100 + ($value['valid_money']['avia'] * $value['water_rate']['avia'] / 100);
    $commission_fire = (0 - $value['user_win']['fire'] - $value['mem_rebate']['fire'] - $value['agent_fee']['fire'] - $value['third_fee']['fire']) * $value['commission_rate']['fire'] / 100 + ($value['valid_money']['fire'] * $value['water_rate']['fire'] / 100);
    $commission_ssc = (0 - $value['user_win']['ssc'] - $value['mem_rebate']['ssc'] - $value['agent_fee']['ssc'] - $value['third_fee']['ssc']) * $value['commission_rate']['ssc'] / 100 + ($value['valid_money']['ssc'] * $value['water_rate']['ssc'] / 100);
    $commission_project = (0 - $value['user_win']['project'] - $value['mem_rebate']['project'] - $value['agent_fee']['project'] - $value['third_fee']['project']) * $value['commission_rate']['project'] / 100 + ($value['valid_money']['project'] * $value['water_rate']['project'] / 100);
    $commission_trace = (0 - $value['user_win']['trace'] - $value['mem_rebate']['trace'] - $value['agent_fee']['trace'] - $value['third_fee']['trace']) * $value['commission_rate']['trace'] / 100 + ($value['valid_money']['trace'] * $value['water_rate']['trace'] / 100);
    $commission_og = (0 - $value['user_win']['og'] - $value['mem_rebate']['og'] - $value['agent_fee']['og'] - $value['third_fee']['og']) * $value['commission_rate']['og'] / 100 + ($value['valid_money']['og'] * $value['water_rate']['og'] / 100);
    $commission_mw = (0 - $value['user_win']['mw'] - $value['mem_rebate']['mw'] - $value['agent_fee']['mw'] - $value['third_fee']['mw']) * $value['commission_rate']['mw'] / 100 + ($value['valid_money']['mw'] * $value['water_rate']['mw'] / 100);
    $commission_cq = (0 - $value['user_win']['cq'] - $value['mem_rebate']['cq'] - $value['agent_fee']['cq'] - $value['third_fee']['cq']) * $value['commission_rate']['cq'] / 100 + ($value['valid_money']['cq'] * $value['water_rate']['cq'] / 100);
    $commission_fg = (0 - $value['user_win']['fg'] - $value['mem_rebate']['fg'] - $value['agent_fee']['fg'] - $value['third_fee']['fg']) * $value['commission_rate']['fg'] / 100 + ($value['valid_money']['fg'] * $value['water_rate']['fg'] / 100);
    $commission_bbin = (0 - $value['user_win']['bbin'] - $value['mem_rebate']['bbin'] - $value['agent_fee']['bbin'] - $value['third_fee']['bbin']) * $value['commission_rate']['bbin'] / 100 + ($value['valid_money']['bbin'] * $value['water_rate']['bbin'] / 100);
    // 代理行政费用
    $company_agent = $value['agent_fee']['hg'] + $value['agent_fee']['cp'] + $value['agent_fee']['ag'] + $value['agent_fee']['ag_dianzi'] + $value['agent_fee']['ag_dayu'] + $value['agent_fee']['ky'] +
        $value['agent_fee']['hgqp'] + $value['agent_fee']['vgqp'] + $value['agent_fee']['lyqp'] + $value['agent_fee']['klqp'] + $value['agent_fee']['mg'] + $value['agent_fee']['avia'] + $value['agent_fee']['fire'] + $value['agent_fee']['ssc'] +
        $value['agent_fee']['project'] + $value['agent_fee']['trace'] + $value['agent_fee']['og'] + $value['agent_fee']['mw'] + $value['agent_fee']['cq'] + $value['agent_fee']['fg'] + $value['agent_fee']['bbin'];
    // 体育赛事合计
    $total_sports = $value['user_win']['hg'];
    // 电子竞技合计
    $total_eSport = $value['user_win']['avia']+ $value['user_win']['fire'];
    // 真人视讯合计
    $total_video = $value['user_win']['ag'] + $value['user_win']['og'] + $value['user_win']['bbin'];
    // 彩票游戏合计
    $total_lottery = $value['user_win']['cp'] + $value['user_win']['project'] + $value['user_win']['ssc'] + $value['user_win']['trace'];
    // 棋牌对战游戏合计
    $total_chess = $value['user_win']['ky'] + $value['user_win']['hgqp'] + $value['user_win']['vgqp'] + $value['user_win']['lyqp'] + $value['user_win']['klqp'];
    // 电子游艺合计
    $total_games = $value['user_win']['ag_dianzi'] + $value['user_win']['mg'] + $value['user_win']['cq'] + $value['user_win']['mw'] + $value['user_win']['fg'] + $value['user_win']['ag_dayu'];
    // 各馆总输赢
    $total_win = $total_sports + $total_eSport + $total_video + $total_lottery + $total_chess + $total_games;
    // 入款手续费
    $total_deposit_fee = $value['total_deposit'] * $agentsDepositRate / 100;
    if (bccomp($total_deposit_fee, $agentsDepositLimit, 4) == 1) {
        $total_deposit_fee = $agentsDepositLimit;
    }
    // 出款手续费
    $total_withdraw_fee = $value['total_withdraw'] * $agentsWithdrawRate / 100;
    if (bccomp($total_withdraw_fee, $agentsWithdrawLimit, 4) == 1) {
        $total_withdraw_fee = $agentsWithdrawLimit;
    }
    // 彩金费用
    $total_gift_fee = $value['total_gift'] * $giftFeeRate / 100;
    // 代理佣金（各厅室佣金相加抵扣总和 - 存款手续费 - 取款手续费 - 彩金费用 = 可获佣金）
    $commission_agent = $commission_hg + $commission_cp + $commission_ag + $commission_ag_dianzi + $commission_ag_dayu + $commission_ky + $commission_hgqp + $commission_vgqp + $commission_lyqp + $commission_klqp +
        $commission_mg + $commission_avia + $commission_fire + $commission_ssc + $commission_project + $commission_trace + $commission_og + $commission_mw + $commission_cq + $commission_fg + $commission_bbin -
        $total_deposit_fee - $total_withdraw_fee - $total_gift_fee;
}
    $resdata = $value;
    $resdata['total_sports'] = $total_sports; // 体育总输赢
    $resdata['total_eSport'] = $total_eSport; // 电竞总输赢
    $resdata['total_video'] = $total_video; // 真人总输赢
    $resdata['total_lottery'] = $total_lottery; // 彩票总输赢
    $resdata['total_chess'] = $total_chess; // 棋牌总输赢
    $resdata['total_games'] = $total_games; // 电子总输赢
    $resdata['total_games'] = $total_games; // 电子总输赢
    $resdata['total_win'] = $total_win; // 各馆总输赢
    $resdata['commission_agent'] = $commission_agent; // 总佣金
    $resdata['total_deposit_fee'] = $total_deposit_fee; // 存款手续费
    $resdata['total_withdraw_fee'] = $total_withdraw_fee; // 出款手续费
    $resdata['company_agent'] = $company_agent; // 行政费用
    $status = '200';
    $describe = '查询成功!';
    original_phone_request_response($status,$describe,$resdata);
}

?>

<html>
<head>
    <title>佣金查询</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td { padding: 3.5px 0 0  8px;}
        .show_detail { display: inline-block; line-height: 25px;}
        input.za_text {width: 142px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>佣金查询</dt>
    <dd>
        <form id="myFORM" action="" method=get name="myFORM" >
            <table>
                <tr>
                    <td>
                        选择日期：
                        <select name="date" id="date" onChange="self.myFORM.submit()">
                            <?php
                            foreach ($dateRange as $value){?>
                                <option value="<?php echo $value?>" <?php if($value == $date) echo "selected";?> ><?php echo $value?></option>
                            <?php }?>
                        </select>
                        代理商：
                        <input type="text" name="agent" size=10 value="<?php echo $agent;?>" maxlength=30 class="za_text">
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
<div class="main-ui" style="width: 100%">
    <table class="m_tab">
        <thead>
        <tr class="m_title">
            <td>代理商<br>会员数</td>
            <td colspan="2">体育赛事</td>
            <td colspan="2">彩票游戏</td>
            <td colspan="2">真人视讯</td>
            <td colspan="2">电子竞技</td>
            <td colspan="2">电子游艺</td>
            <td colspan="2">对战游戏</td>
            <td colspan="2">佣金<br>手续费</td>
            <td colspan="2">手续汇总</td>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 0;
        foreach ($monthData as $key => $value) {
            // 佣金计算公式:(0 - 会员输赢 - 返水总额 - 行政费 - 三方抽水) x 退佣比例 + (有效投注 x 退水比例) = 厅室佣金
            $commission_hg = (0 - $value['user_win']['hg'] - $value['mem_rebate']['hg'] - $value['agent_fee']['hg'] - $value['third_fee']['hg']) * $value['commission_rate']['hg'] / 100 + ($value['valid_money']['hg'] * $value['water_rate']['hg'] / 100);
            $commission_cp = (0 - $value['user_win']['cp'] - $value['mem_rebate']['cp'] - $value['agent_fee']['cp'] - $value['third_fee']['cp']) * $value['commission_rate']['cp'] / 100 + ($value['valid_money']['cp'] * $value['water_rate']['cp'] / 100);
            $commission_ag = (0 - $value['user_win']['ag'] - $value['mem_rebate']['ag'] - $value['agent_fee']['ag'] - $value['third_fee']['ag']) * $value['commission_rate']['ag'] / 100 + ($value['valid_money']['ag'] * $value['water_rate']['ag'] / 100);
            $commission_ag_dianzi = (0 - $value['user_win']['ag_dianzi'] - $value['mem_rebate']['ag_dianzi'] - $value['agent_fee']['ag_dianzi'] - $value['third_fee']['ag_dianzi']) * $value['commission_rate']['ag_dianzi'] / 100 + ($value['valid_money']['ag_dianzi'] * $value['water_rate']['ag_dianzi'] / 100);
            $commission_ag_dayu = (0 - $value['user_win']['ag_dayu'] - $value['mem_rebate']['ag_dayu'] - $value['agent_fee']['ag_dayu'] - $value['third_fee']['ag_dayu']) * $value['commission_rate']['ag_dayu'] / 100 + ($value['valid_money']['ag_dayu'] * $value['water_rate']['ag_dayu'] / 100);
            $commission_ky = (0 - $value['user_win']['ky'] - $value['mem_rebate']['ky'] - $value['agent_fee']['ky'] - $value['third_fee']['ky']) * $value['commission_rate']['ky'] / 100 + ($value['valid_money']['ky'] * $value['water_rate']['ky'] / 100);
            $commission_hgqp = (0 - $value['user_win']['hgqp'] - $value['mem_rebate']['hgqp'] - $value['agent_fee']['hgqp'] - $value['third_fee']['hgqp']) * $value['commission_rate']['hgqp'] / 100 + ($value['valid_money']['hgqp'] * $value['water_rate']['hgqp'] / 100);
            $commission_vgqp = (0 - $value['user_win']['vgqp'] - $value['mem_rebate']['vgqp'] - $value['agent_fee']['vgqp'] - $value['third_fee']['vgqp']) * $value['commission_rate']['vgqp'] / 100 + ($value['valid_money']['vgqp'] * $value['water_rate']['vgqp'] / 100);
            $commission_lyqp = (0 - $value['user_win']['lyqp'] - $value['mem_rebate']['lyqp'] - $value['agent_fee']['lyqp'] - $value['third_fee']['lyqp']) * $value['commission_rate']['lyqp'] / 100 + ($value['valid_money']['lyqp'] * $value['water_rate']['lyqp'] / 100);
            $commission_klqp = (0 - $value['user_win']['klqp'] - $value['mem_rebate']['klqp'] - $value['agent_fee']['klqp'] - $value['third_fee']['klqp']) * $value['commission_rate']['klqp'] / 100 + ($value['valid_money']['klqp'] * $value['water_rate']['klqp'] / 100);
            $commission_mg = (0 - $value['user_win']['mg'] - $value['mem_rebate']['mg'] - $value['agent_fee']['mg'] - $value['third_fee']['mg']) * $value['commission_rate']['mg'] / 100 + ($value['valid_money']['mg'] * $value['water_rate']['mg'] / 100);
            $commission_avia = (0 - $value['user_win']['avia'] - $value['mem_rebate']['avia'] - $value['agent_fee']['avia'] - $value['third_fee']['avia']) * $value['commission_rate']['avia'] / 100 + ($value['valid_money']['avia'] * $value['water_rate']['avia'] / 100);
            $commission_fire = (0 - $value['user_win']['fire'] - $value['mem_rebate']['fire'] - $value['agent_fee']['fire'] - $value['third_fee']['fire']) * $value['commission_rate']['fire'] / 100 + ($value['valid_money']['fire'] * $value['water_rate']['fire'] / 100);
            $commission_ssc = (0 - $value['user_win']['ssc'] - $value['mem_rebate']['ssc'] - $value['agent_fee']['ssc'] - $value['third_fee']['ssc']) * $value['commission_rate']['ssc'] / 100 + ($value['valid_money']['ssc'] * $value['water_rate']['ssc'] / 100);
            $commission_project = (0 - $value['user_win']['project'] - $value['mem_rebate']['project'] - $value['agent_fee']['project'] - $value['third_fee']['project']) * $value['commission_rate']['project'] / 100 + ($value['valid_money']['project'] * $value['water_rate']['project'] / 100);
            $commission_trace = (0 - $value['user_win']['trace'] - $value['mem_rebate']['trace'] - $value['agent_fee']['trace'] - $value['third_fee']['trace']) * $value['commission_rate']['trace'] / 100 + ($value['valid_money']['trace'] * $value['water_rate']['trace'] / 100);
            $commission_og = (0 - $value['user_win']['og'] - $value['mem_rebate']['og'] - $value['agent_fee']['og'] - $value['third_fee']['og']) * $value['commission_rate']['og'] / 100 + ($value['valid_money']['og'] * $value['water_rate']['og'] / 100);
            $commission_mw = (0 - $value['user_win']['mw'] - $value['mem_rebate']['mw'] - $value['agent_fee']['mw'] - $value['third_fee']['mw']) * $value['commission_rate']['mw'] / 100 + ($value['valid_money']['mw'] * $value['water_rate']['mw'] / 100);
            $commission_cq = (0 - $value['user_win']['cq'] - $value['mem_rebate']['cq'] - $value['agent_fee']['cq'] - $value['third_fee']['cq']) * $value['commission_rate']['cq'] / 100 + ($value['valid_money']['cq'] * $value['water_rate']['cq'] / 100);
            $commission_fg = (0 - $value['user_win']['fg'] - $value['mem_rebate']['fg'] - $value['agent_fee']['fg'] - $value['third_fee']['fg']) * $value['commission_rate']['fg'] / 100 + ($value['valid_money']['fg'] * $value['water_rate']['fg'] / 100);
            $commission_bbin = (0 - $value['user_win']['bbin'] - $value['mem_rebate']['bbin'] - $value['agent_fee']['bbin'] - $value['third_fee']['bbin']) * $value['commission_rate']['bbin'] / 100 + ($value['valid_money']['bbin'] * $value['water_rate']['bbin'] / 100);
            // 代理行政费用
            $company_agent = $value['agent_fee']['hg'] + $value['agent_fee']['cp'] + $value['agent_fee']['ag'] + $value['agent_fee']['ag_dianzi'] + $value['agent_fee']['ag_dayu'] + $value['agent_fee']['ky'] +
                $value['agent_fee']['hgqp'] + $value['agent_fee']['vgqp'] + $value['agent_fee']['lyqp'] + $value['agent_fee']['klqp'] + $value['agent_fee']['mg'] + $value['agent_fee']['avia'] + $value['agent_fee']['fire'] + $value['agent_fee']['ssc'] +
                $value['agent_fee']['project'] + $value['agent_fee']['trace'] + $value['agent_fee']['og'] + $value['agent_fee']['mw'] + $value['agent_fee']['cq'] + $value['agent_fee']['fg'] + $value['agent_fee']['bbin'];
            // 体育赛事合计
            $total_sports = $value['user_win']['hg'];
            // 电子竞技合计
            $total_eSport = $value['user_win']['avia'] + $value['user_win']['fire'];
            // 真人视讯合计
            $total_video = $value['user_win']['ag'] + $value['user_win']['og'] + $value['user_win']['bbin'];
            // 彩票游戏合计
            $total_lottery = $value['user_win']['cp'] + $value['user_win']['project'] + $value['user_win']['ssc'] + $value['user_win']['trace'];
            // 棋牌对战游戏合计
            $total_chess = $value['user_win']['ky'] + $value['user_win']['hgqp'] + $value['user_win']['vgqp'] + $value['user_win']['lyqp'] + $value['user_win']['klqp'];
            // 电子游艺合计
            $total_games = $value['user_win']['ag_dianzi'] + $value['user_win']['mg'] + $value['user_win']['cq'] + $value['user_win']['mw'] + $value['user_win']['fg'] + $value['user_win']['ag_dayu'];
            // 各馆总输赢
            $total_win =  $total_sports + $total_eSport + $total_video + $total_lottery + $total_chess + $total_games;
            // 入款手续费
            $total_deposit_fee = $value['total_deposit'] * $agentsDepositRate / 100;
            if (bccomp($total_deposit_fee, $agentsDepositLimit, 4) == 1) {
                $total_deposit_fee = $agentsDepositLimit;
            }
            // 出款手续费
            $total_withdraw_fee = $value['total_withdraw'] * $agentsWithdrawRate / 100;
            if (bccomp($total_withdraw_fee, $agentsWithdrawLimit, 4) == 1) {
                $total_withdraw_fee = $agentsWithdrawLimit;
            }
            // 彩金费用
            $total_gift_fee = $value['total_gift'] * $giftFeeRate / 100;
            // 代理佣金（各厅室佣金相加抵扣总和 - 存款手续费 - 取款手续费 - 彩金费用 = 可获佣金）
            $commission_agent = $commission_hg + $commission_cp + $commission_ag + $commission_ag_dianzi + $commission_ag_dayu + $commission_ky + $commission_hgqp + $commission_vgqp + $commission_lyqp + $commission_klqp +
                $commission_mg + $commission_avia + $commission_fire + $commission_ssc + $commission_project + $commission_trace + $commission_og + $commission_mw + $commission_cq + $commission_fg + $commission_bbin -
                $total_deposit_fee - $total_withdraw_fee - $total_gift_fee;

            $i ++;
            $class = $i % 2 == 0 ? 'odd' : 'even';
            ?>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td rowspan="7"><?php echo $key;?><br><?php echo $value['member_num'];?></td>
                <td>皇冠体育<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['hg'];?>" data-valid="<?php echo $value['valid_money']['hg'];?>" data-win="<?php echo $value['user_win']['hg'];?>" data-water-rate="<?php echo $value['water_rate']['hg'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['hg'];?>" data-agent-fee="<?php echo $value['agent_fee']['hg'];?>" data-third-rate="<?php echo $thirdRateSet['hg'];?>" data-commission="<?php echo $commission_hg;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['hg'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['hg'];?></font></td>
                <?php if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票?>
                    <td>体育彩票<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['cp'];?>" data-valid="<?php echo $value['valid_money']['cp'];?>" data-win="<?php echo $value['user_win']['cp'];?>" data-water-rate="<?php echo $value['water_rate']['cp'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['cp'];?>" data-agent-fee="<?php echo $value['agent_fee']['cp'];?>" data-third-rate="<?php echo $thirdRateSet['cp'];?>" data-commission="<?php echo $commission_cp;?>">详情</a> </span></td>
                    <td><font color="<?php echo ($value['user_win']['cp'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['cp'];?></font></td>
                <?php } else {?>
                    <td>国民彩票官方盘<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['project'];?>" data-valid="<?php echo $value['valid_money']['project'];?>" data-win="<?php echo $value['user_win']['project'];?>" data-water-rate="<?php echo $value['water_rate']['project'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['project'];?>" data-agent-fee="<?php echo $value['agent_fee']['project'];?>" data-third-rate="<?php echo $thirdRateSet['project'];?>" data-commission="<?php echo $commission_project;?>">详情</a> </span></td>
                    <td><font color="<?php echo ($value['user_win']['project'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['project'];?></font></td>
                <?php }?>
                <td>AG视讯<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['ag'];?>" data-valid="<?php echo $value['valid_money']['ag'];?>" data-win="<?php echo $value['user_win']['ag'];?>" data-water-rate="<?php echo $value['water_rate']['ag'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['ag'];?>" data-agent-fee="<?php echo $value['agent_fee']['ag'];?>" data-third-rate="<?php echo $thirdRateSet['ag'];?>" data-commission="<?php echo $commission_ag;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['ag'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['ag'];?></font></td>
                <td>泛亚电竞<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['avia'];?>" data-valid="<?php echo $value['valid_money']['avia'];?>" data-win="<?php echo $value['user_win']['avia'];?>" data-water-rate="<?php echo $value['water_rate']['avia'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['avia'];?>" data-agent-fee="<?php echo $value['agent_fee']['avia'];?>" data-third-rate="<?php echo $thirdRateSet['avia'];?>" data-commission="<?php echo $commission_avia;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['avia'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['avia'];?></font></td>
                <td>AG电子<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['ag_dianzi'];?>" data-valid="<?php echo $value['valid_money']['ag_dianzi'];?>" data-win="<?php echo $value['user_win']['ag_dianzi'];?>" data-water-rate="<?php echo $value['water_rate']['ag_dianzi'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['ag_dianzi'];?>" data-agent-fee="<?php echo $value['agent_fee']['ag_dianzi'];?>" data-third-rate="<?php echo $thirdRateSet['ag_dianzi'];?>" data-commission="<?php echo $commission_ag_dianzi;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['ag_dianzi'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['ag_dianzi'];?></font></td>
                <td>开元棋牌<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['ky'];?>" data-valid="<?php echo $value['valid_money']['ky'];?>" data-win="<?php echo $value['user_win']['ky'];?>" data-water-rate="<?php echo $value['water_rate']['ky'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['ky'];?>" data-agent-fee="<?php echo $value['agent_fee']['ky'];?>" data-third-rate="<?php echo $thirdRateSet['ky'];?>" data-commission="<?php echo $commission_ky;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['ky'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['ky'];?></font></td>
                <td>可获佣金</td>
                <td><font color="<?php echo ($commission_agent > 0 ? "red" : 'green');?>"><?php echo $commission_agent;?></font></td>
                <td>返水总量</td>
                <td><font color="<?php echo ($value['mem_rebate']['total'] > 0 ? "red" : 'green');?>"><?php echo $value['mem_rebate']['total'];?></font></td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td><b>体育赛事合计</b></td>
                <td><b><?php echo $total_sports;?></b></td>
                <?php if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票?>
                    <td><b>彩票游戏合计</b></td>
                    <td><b><?php echo $total_lottery;?></b></td>
                <?php } else {?>
                    <td>国民彩票信用盘<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['ssc'];?>" data-valid="<?php echo $value['valid_money']['ssc'];?>" data-win="<?php echo $value['user_win']['ssc'];?>" data-water-rate="<?php echo $value['water_rate']['ssc'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['ssc'];?>" data-agent-fee="<?php echo $value['agent_fee']['ssc'];?>" data-third-rate="<?php echo $thirdRateSet['ssc'];?>" data-commission="<?php echo $commission_ssc;?>">详情</a> </span></td>
                    <td><font color="<?php echo ($value['user_win']['ssc'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['ssc'];?></font></td>
                <?php }?>
                <td>OG视讯<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['og'];?>" data-valid="<?php echo $value['valid_money']['og'];?>" data-win="<?php echo $value['user_win']['og'];?>" data-water-rate="<?php echo $value['water_rate']['og'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['og'];?>" data-agent-fee="<?php echo $value['agent_fee']['og'];?>" data-third-rate="<?php echo $thirdRateSet['og'];?>" data-commission="<?php echo $commission_og;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['og'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['og'];?></font></td>
                <td>雷火电竞<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['fire'];?>" data-valid="<?php echo $value['valid_money']['fire'];?>" data-win="<?php echo $value['user_win']['fire'];?>" data-water-rate="<?php echo $value['water_rate']['fire'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['fire'];?>" data-agent-fee="<?php echo $value['agent_fee']['fire'];?>" data-third-rate="<?php echo $thirdRateSet['fire'];?>" data-commission="<?php echo $commission_fire;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['fire'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['fire'];?></font></td>
                <td>MG电子<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['mg'];?>" data-valid="<?php echo $value['valid_money']['mg'];?>" data-win="<?php echo $value['user_win']['mg'];?>" data-water-rate="<?php echo $value['water_rate']['mg'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['mg'];?>" data-agent-fee="<?php echo $value['agent_fee']['mg'];?>" data-third-rate="<?php echo $thirdRateSet['mg'];?>" data-commission="<?php echo $commission_mg;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['mg'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['mg'];?></font></td>
                <td>VG棋牌<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['vgqp'];?>" data-valid="<?php echo $value['valid_money']['vgqp'];?>" data-win="<?php echo $value['user_win']['vgqp'];?>" data-water-rate="<?php echo $value['water_rate']['vgqp'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['vgqp'];?>" data-agent-fee="<?php echo $value['agent_fee']['vgqp'];?>" data-third-rate="<?php echo $thirdRateSet['vgqp'];?>" data-commission="<?php echo $commission_vgqp;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['vgqp'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['vgqp'];?></font></td>
                <td>存款手续费</td>
                <td><?php echo $total_deposit_fee;?></td>
                <td>入款总量</td>
                <td><?php echo $value['total_deposit'];?></td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <?php if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票?>
                    <td></td>
                    <td></td>
                <?php } else {?>
                    <td>国民彩票追号<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['trace'];?>" data-valid="<?php echo $value['valid_money']['trace'];?>" data-win="<?php echo $value['user_win']['trace'];?>" data-water-rate="<?php echo $value['water_rate']['trace'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['trace'];?>" data-agent-fee="<?php echo $value['agent_fee']['trace'];?>" data-third-rate="<?php echo $thirdRateSet['trace'];?>" data-commission="<?php echo $commission_trace;?>">详情</a> </span></td>
                    <td><font color="<?php echo ($value['user_win']['trace'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['trace'];?></font></td>
                <?php }?>
                <td>BBIN视讯<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['bbin'];?>" data-valid="<?php echo $value['valid_money']['bbin'];?>" data-win="<?php echo $value['user_win']['bbin'];?>" data-water-rate="<?php echo $value['water_rate']['bbin'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['bbin'];?>" data-agent-fee="<?php echo $value['agent_fee']['bbin'];?>" data-third-rate="<?php echo $thirdRateSet['bbin'];?>" data-commission="<?php echo $commission_bbin;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['bbin'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['bbin'];?></font></td>
                <td><b>电子竞技合计</b></td>
                <td><b><?php echo $total_eSport;?></b></td>
                <td>CQ9电子<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['cq'];?>" data-valid="<?php echo $value['valid_money']['cq'];?>" data-win="<?php echo $value['user_win']['cq'];?>" data-water-rate="<?php echo $value['water_rate']['cq'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['cq'];?>" data-agent-fee="<?php echo $value['agent_fee']['cq'];?>" data-third-rate="<?php echo $thirdRateSet['cq'];?>" data-commission="<?php echo $commission_cq;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['cq'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['cq'];?></font></td>
                <td>快乐棋牌<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['klqp'];?>" data-valid="<?php echo $value['valid_money']['klqp'];?>" data-win="<?php echo $value['user_win']['klqp'];?>" data-water-rate="<?php echo $value['water_rate']['klqp'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['klqp'];?>" data-agent-fee="<?php echo $value['agent_fee']['klqp'];?>" data-third-rate="<?php echo $thirdRateSet['klqp'];?>" data-commission="<?php echo $commission_klqp;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['klqp'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['klqp'];?></font></td>

                <td>取款手续费</td>
                <td><?php echo $total_withdraw_fee;?></td>
                <td>出款总量</td>
                <td><?php echo $value['total_withdraw'];?></td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <?php if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票?>
                    <td></td>
                    <td></td>
                <?php } else {?>
                    <td><b>彩票游戏合计</b></td>
                    <td><b><?php echo $total_lottery;?></b></td>
                <?php }?>
                <td><b>真人视讯合计</b></td>
                <td><b><?php echo $total_video;?></b></td>
                <td></td>
                <td></td>
                <td>MW电子<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['mw'];?>" data-valid="<?php echo $value['valid_money']['mw'];?>" data-win="<?php echo $value['user_win']['mw'];?>" data-water-rate="<?php echo $value['water_rate']['mw'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['mw'];?>" data-agent-fee="<?php echo $value['agent_fee']['mw'];?>" data-third-rate="<?php echo $thirdRateSet['mw'];?>" data-commission="<?php echo $commission_mw;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['mw'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['mw'];?></font></td>
                <td>乐游棋牌<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['lyqp'];?>" data-valid="<?php echo $value['valid_money']['lyqp'];?>" data-win="<?php echo $value['user_win']['lyqp'];?>" data-water-rate="<?php echo $value['water_rate']['lyqp'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['lyqp'];?>" data-agent-fee="<?php echo $value['agent_fee']['lyqp'];?>" data-third-rate="<?php echo $thirdRateSet['lyqp'];?>" data-commission="<?php echo $commission_lyqp;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['lyqp'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['lyqp'];?></font></td>
                <td>行政费用</td>
                <td><?php echo $company_agent;?></td>
                <td>优惠总量</td>
                <td><?php echo $value['total_extra'];?></td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>FG电子<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['fg'];?>" data-valid="<?php echo $value['valid_money']['fg'];?>" data-win="<?php echo $value['user_win']['fg'];?>" data-water-rate="<?php echo $value['water_rate']['fg'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['fg'];?>" data-agent-fee="<?php echo $value['agent_fee']['fg'];?>" data-third-rate="<?php echo $thirdRateSet['fg'];?>" data-commission="<?php echo $commission_fg;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['fg'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['fg'];?></font></td>
                <td></td>
                <td></td>
                <!--<td>皇冠棋牌<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php /*echo $key;*/?>" data-commission-rate="<?php /*echo $value['commission_rate']['hgqp'];*/?>" data-valid="<?php /*echo $value['valid_money']['hgqp'];*/?>" data-win="<?php /*echo $value['user_win']['hgqp'];*/?>" data-water-rate="<?php /*echo $value['water_rate']['hgqp'];*/?>" data-mem-rebate="<?php /*echo $value['mem_rebate']['hgqp'];*/?>" data-agent-fee="<?php /*echo $value['agent_fee']['hgqp'];*/?>" data-third-rate="<?php /*echo $thirdRateSet['hgqp'];*/?>" data-commission="<?php /*echo $commission_hgqp;*/?>">详情</a> </span></td>
                <td><font color="<?php /*echo ($value['user_win']['hgqp'] > 0 ? "red" : 'green');*/?>"><?php /*echo $value['user_win']['hgqp'];*/?></font></td>-->
                <?php if($_SESSION['Level'] == 'D'){?>
                    <td></td>
                    <td></td>
                <?php } else {?>
                    <td>彩金费用</td>
                    <td><?php echo $total_gift_fee;?></td>
                <?php }?>
                <td>彩金总量</td>
                <td><?php echo $value['total_gift'];?></td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>AG捕鱼王<span class="detail-wrapper"><a class="show_detail za_button" href="javascript:;" data-agent="<?php echo $key;?>" data-commission-rate="<?php echo $value['commission_rate']['ag_dayu'];?>" data-valid="<?php echo $value['valid_money']['ag_dayu'];?>" data-win="<?php echo $value['user_win']['ag_dayu'];?>" data-water-rate="<?php echo $value['water_rate']['ag_dayu'];?>" data-mem-rebate="<?php echo $value['mem_rebate']['ag_dayu'];?>" data-agent-fee="<?php echo $value['agent_fee']['ag_dayu'];?>" data-third-rate="<?php echo $thirdRateSet['ag_dayu'];?>" data-commission="<?php echo $commission_ag_dayu;?>">详情</a> </span></td>
                <td><font color="<?php echo ($value['user_win']['ag_dayu'] > 0 ? "red" : 'green');?>"><?php echo $value['user_win']['ag_dayu'];?></font></td>
                <td><b>对战游戏合计</b></td>
                <td><b><?php echo $total_chess;?></b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>电子游艺合计</b></td>
                <td><b><?php echo $total_games;?></b></td>
                <td></td>
                <td></td>
                <td><b>各馆总输赢：</b></td>
                <td><b><?php echo $total_win;?></b></td>
                <td></td>
                <td></td>
            </tr>
        <?php }?>
        <tr>
            <td colspan="17" style="text-align: left">
                注意：本月统计数据，于每日美东时间03:30更新<br><br>
                会员输赢和代理佣金计算说明：<br>
                <font color="red">会员输赢：</font><br>
                红色(正数)：代表玩家赢的钱。绿色(负数)：代表玩家输的钱<br><br>
                <font color="red">可获佣金：</font><br>
                红色(正数)：代表要支付代理费给代理商。绿色(亏损)：代表无需支付<br><br>
                <font color="red">佣金计算公式：</font><br>
                (0 - 会员输赢 - 返水总额 - 行政费 - 平台抽成) X 退佣比例 + (有效投注x退水比例) = 厅室佣金<br>
                行政费：厅室输赢(取正数) X 行政费比例<br>
                平台抽成：厅室输赢(取正数) X 抽水比例<br>
                <font color="red">可获佣金：</font>各厅室佣金相加抵扣总和 - 存款手续费 - 取款手续费<?php if($_SESSION['Level'] == 'M') { ?> - 彩金费<?php }?> = 可获佣金<br><br>
                <font color="red">入款总量和出款总量:</font><br>
                只用于计算本月代理下属会员的资金流水统计和计算手续费(手续费按每笔计算，并不按照总量计算)，所以仅供参考
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script>
    var level = '<?php echo $_SESSION['Level']?>';
    if(level == 'D'){
        $('.show_detail').hide();
    }
    showAgentDetail();
    $(document).ready(function() {
        $('.even,.odd').on('mouseover', function() {
            var index = $(this).attr('data-row-index');
            // var bgColor = $(this).css('background-color');
            var bgColor = 'rgb(204, 255, 204)';
            $("tr[data-row-index=" + index + "]").css('background-color', bgColor);
        });
        $('.even,.odd').on('mouseout', function() {
            var index = $(this).attr('data-row-index');
            $("tr[data-row-index=" + index + "]").css('background-color', '');
        });
    });

    function showAgentDetail() {
        $('.show_detail').on('click',function () {
            var game = $(this).context.parentElement.parentElement.innerText;
            var agent = $(this).data('agent');
            var valid_money = $(this).data('valid');
            var user_win = $(this).data('win');
            var water_rate = $(this).data('water-rate');
            var mem_rebate = $(this).data('mem-rebate');
            var agent_fee = $(this).data('agent-fee');
            var commission_rate = $(this).data('commission-rate');
            var third_rate = $(this).data('third-rate');
            var commission = $(this).data('commission');
            var agent_fee_rate = '<?php echo $agentsFeeRate / 100;?>';

            var str = '<table class="table" border="1" cellspacing="0" cellpadding="5" width="100%"> <tbody>' +
                '<tr>' +
                '<th colspan="2">代理：' + agent + '</th>' +
                '</tr>' +
                '<tr>' +
                '<td style="width: 50%">会员输赢</td>' +
                '<td style="width: 50%">' + user_win + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>有效投注</td>' +
                '<td>' + valid_money + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>退水比例</td>' +
                '<td>' + water_rate + '%</td>' +
                '</tr>' +
                '<tr>' +
                '<td>会员返水总额</td>' +
                '<td>' + mem_rebate + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>该馆行政费[' + agent_fee_rate + ']</td>' +
                '<td>' + agent_fee + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>退佣比例</td>' +
                '<td>' + commission_rate + '%</td>' +
                '</tr>' +
                '<tr>' +
                '<td>三方抽水比例</td>' +
                '<td>' + third_rate + '%</td>' +
                '</tr>' +
                '<tr>' +
                '<td>该馆所得佣金</td>' +
                '<td>' + commission + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td colspan="2" style="text-align:left;color: red; padding-left:10px;">' +
                '佣金计算公式:<br>' +
                '(0 - 会员输赢 - 返水总额 - 行政费 - 平台抽成) x 退佣比例 + (有效投注 x 退水比例) = 厅室佣金<br>' +
                '行政费：厅室输赢(取正数) x 行政费比例<br>' +
                '抽成费：厅室输赢(为正数) x 三方抽水比例<br>' +
                '</td>' +
                '</tr>' +
                '</tbody></table>';
            layer.alert(str, {title:game + ' :' });
        })
    }

    $('.show_detail').on('click', function () {
        $('#add_window').show();
    });

    $('.close_window').on('click', function () {
        $('#add_window').hide();
    });
</script>
</html>
