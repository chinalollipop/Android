<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activityLottery.class.php");


/* *
 * 1. 会员申请时间是北京时间12:00 - 次日11:59 之间(美东时间 00:00 - 23:59:59)
 * 2. 查询五分、三分、分分彩各彩种有效投注。
 *      满足根据对应彩种有效投注插入礼金, 不满足最低投注提示不满足要求.
 *      如果当天连续1分钟内重复申请，不让申请
 *      每位会员每天仅限申请一次，申请过不能申请
 * 3. 数据表插入
 * 4. 保留两个月以上数据
 */
$user_id = $_REQUEST['user_id'];
$username = $_REQUEST['username'];
$minValidBet = 3000;

$member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where ID='$user_id'";
$member_query = mysqli_query($dbLink,$member_sql);
$memberinfo = mysqli_fetch_assoc($member_query);
$sUserlayer = $memberinfo['layer'];
// 检查当前会员是否设置不准领取彩金分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
$layerId=4;
if ($sUserlayer==$layerId){
    $layer = getUserLayerById($layerId);
    if ($layer['status']==1) {
        $status = array('status'=>'401.66', 'info'=>'账号分层异常，请联系我们在线客服');
        echo json_encode($status);exit;
    }
}

$dateStart = date('Y-m-d 00:00:00', strtotime('-1 day')); // 昨日美东时间
$dateEnd = date('Y-m-d 23:59:59', strtotime('-1 day')); // 昨日美东时间 结束时间

// 当日开始时间截止时间（美东时间）
$time['beginToday'] = mktime(0,0,0,date('m'),date('d'),date('Y'));
$time['endToday'] = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

//1. 验证申请时间  2019-12-22 00:00:00----2019-12-22 23:59:59
if(time() < $time['beginToday'] || time() > $time['endToday']){
    $status = array('status'=>'0', 'info'=>'请于美东时间当日申请昨日彩金哦!');
    echo json_encode($status ,JSON_UNESCAPED_UNICODE);exit;
}


// 2. 当日是否申请
$lottery_sql = "select * from ".DBPREFIX."web_lottery_king where userid='$user_id' and add_time BETWEEN '".date("Y-m-d H:i:s",$time['beginToday'])."' and '".date("Y-m-d H:i:s",$time['endToday'])."'";
$result = mysqli_query($dbLink,$lottery_sql);
$king_statis = mysqli_fetch_assoc($result);

// 当日已领过
if(!empty($king_statis)) {
    $status = array('status'=>'0', 'info'=>'您当日已领取，不允许重复申请哦!');
    echo json_encode($status);exit;
}

//异常点击申请
$redisObj = new Ciredis();
$attTime = $redisObj->getSimpleOne('activity_king_useid_'.$user_id);
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<2*60) {
        $status = array('status'=>'0', 'info'=>'不允许多次点击,请稍后申请!');
        echo json_encode($status);exit;
    }
}

// 插入当前申请时间，存入redis, 确保不允许重复申请
$redisObj->insert('activity_king_useid_'.$user_id, time(), 10*60);

$activity= new ActivityLottery();  // 活动类

if(TPL_FILE_NAME=='0086' || TPL_FILE_NAME=='6668') {
    $aCp_default = $database['cpDefault'];
    $jsSSC_code = 207;//分分彩
    $sfcSSC_code = 407;// 三分彩 时时彩
    $wfcSSC_code = 507;// 五分彩 时时彩
}else{
    $jsSSC_code = 'ffc';//分分彩
    $sfcSSC_code = 'sfc';// 三分彩
    $wfcSSC_code = 'wfc';// 五分彩
}

// 3. 统计彩票时时彩昨日有效投注
if(TPL_FILE_NAME=='0086' || TPL_FILE_NAME=='6668'){
    $jsSSC_result = $activity->get_lottery_valid_money($jsSSC_code , $username, $aCp_default);
    $sfcSSC_result = $activity->get_lottery_valid_money($sfcSSC_code , $username, $aCp_default);
    $wfcSSC_result = $activity->get_lottery_valid_money($wfcSSC_code , $username, $aCp_default);
}else{
    $jsSSC_result = $activity->getLotteryValidMonery($jsSSC_code , $dateStart, $dateEnd,$user_id);
    $sfcSSC_result = $activity->getLotteryValidMonery($sfcSSC_code , $dateStart, $dateEnd,$user_id);
    $wfcSSC_result = $activity->getLotteryValidMonery($wfcSSC_code , $dateStart, $dateEnd,$user_id);
}
//$jsSSC_result['valid_money'] = 100000;
//$sfcSSC_result['valid_money'] = 80800;
//$wfcSSC_result['valid_money'] = 200;

if(($jsSSC_result['valid_money'] < $minValidBet) && ($sfcSSC_result['valid_money'] < $minValidBet) && ($wfcSSC_result['valid_money'] < $minValidBet)) {
    $status = array('status'=>'0', 'info'=>'昨日五分彩、三分彩、分分彩系列未达到有效投注!');
    echo json_encode($status , JSON_UNESCAPED_UNICODE);
    return false;
}

if($jsSSC_result['valid_money'] >= $minValidBet) { //分分彩达到最低投注
    $tmpResult[$jsSSC_code] = $activity->LotterySeries($jsSSC_result['valid_money'] , $jsSSC_code);
    $tmpResult[$jsSSC_code]['valid_money'] = $jsSSC_result['valid_money'];
}

if($sfcSSC_result['valid_money'] >= $minValidBet) { //三分彩达到最低投注
    $tmpResult[$sfcSSC_code] = $activity->LotterySeries($sfcSSC_result['valid_money'] , $sfcSSC_code);
    $tmpResult[$sfcSSC_code]['valid_money'] = $sfcSSC_result['valid_money'];
}

if($wfcSSC_result['valid_money'] >= $minValidBet) { //五分彩达到最低投注
    $tmpResult[$wfcSSC_code] = $activity->LotterySeries($wfcSSC_result['valid_money'] , $wfcSSC_code);
    $tmpResult[$wfcSSC_code]['valid_money'] = $wfcSSC_result['valid_money'];
}

$kingData = $aTemp = [];
foreach ($tmpResult as $key => &$value) {   //数据转换
    $kingData[]  = $value;
}

//4. 整理入库数据
foreach ($kingData as $key => &$value) {
    $data = [
        'userid' => $user_id,
        'UserName' => trim($username),
        'totalBet' => $value['valid_money'],   //昨日有效金额
        'EventName' => $value['gameCode'],   //彩种
        'kingGold' => $value['kingGold'],   //彩金
        'add_time' =>  date("Y-m-d H:i:s"),  //添加时间
        'upd_time' =>  date("Y-m-d H:i:s"),  //修改时间
        'review_time' =>  date("Y-m-d H:i:s"),  // 派发时间
        'review_name' => '', // 审核人
        'status' =>  2,  //状态：1审核通过,2未审核,3不符合,4已拒绝
    ];

    $aInsertData[] = $data;
}

$count = count($aInsertData);
if ($count<1){
    $status = array('status'=>'0', 'info'=>'申请数据为空!');
    echo json_encode($status , JSON_UNESCAPED_UNICODE);
    return false;
}

$keys = $values = '';
foreach ($aInsertData as $key => $value){
    $keys = '(' . implode(",", array_keys($value)) . ')';
    $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
}

// 5. 数据插入
$sqlinsert="INSERT IGNORE INTO `" . DBPREFIX . "web_lottery_king` {$keys} VALUES {$values}";
$res = mysqli_query($dbMasterLink,$sqlinsert);

if(!$res){
    $status = array('status'=>'0', 'info'=>'系统繁忙，请稍后再试!');
    echo json_encode($status, JSON_UNESCAPED_UNICODE);
} else {
    $status = array('status'=>'1', 'info'=>'已申请彩金,请联系客服等待派发!');
    echo json_encode($status, JSON_UNESCAPED_UNICODE);
}

//6. 删除近两月以上数据
$monthTime = date("Y-m-d H:i:s", strtotime("-2 month"));
//@error_log("DELETE FROM " . DBPREFIX . "web_lottery_king WHERE  `add_time` <= '{$monthTime}'".PHP_EOL, 3, '/tmp/aaa.log');
mysqli_query($dbMasterLink, "DELETE FROM " . DBPREFIX . "web_lottery_king WHERE  `add_time` <= '{$monthTime}'");


exit;



?>