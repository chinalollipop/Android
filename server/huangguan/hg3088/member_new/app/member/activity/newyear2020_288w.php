<?php
/*
 * 活动一：新年288万红包任你拿
1.所有注册在2020年1月10日前均可参与！
2.活动总金额为288万金额。
3.活动于北京时间1月24号（除夕）中午12:00-次日11：59开始，活动时间持续24小时，只能领取其中一个红包。
4.红包金额仅需1倍有效投注即可提款。
 **/

session_start();
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activity.class.php");

$user_id = $_SESSION['userid'];
$username = $_SESSION['UserName'];

if(!$user_id) {
    $status = '502.2';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}

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
        $status = '502.66';
        $describe = '账号分层异常，请联系我们在线客服';
        original_phone_request_response($status,$describe);
    }
}

// 活动于北京时间1月24号（除夕）中午12:00-次日11：59开始，活动时间持续24小时
$newYearBeginTime= '2020-01-24 00:00:00';
$newYearEndTime = '2020-01-24 23:59:59';
//$newYearBeginTime= '2020-01-13 00:00:00';
//$newYearEndTime = '2020-01-13 23:59:59';
$curtime = date("Y-m-d H:i:s",time());

if($curtime < $newYearBeginTime || $curtime > $newYearEndTime){
    $status = '401.2';
    $describe = '请请于北京时间1月24号（除夕）中午12:00-次日11:59期间领取红包哦!';
    original_phone_request_response($status,$describe);
}

// 检查会员注册时间：所有注册在2020年1月10日前，包含1月10日
if ($_SESSION['AddDate'] > '2020-01-10 23:59:59' ){
    $status = '401.3';
    $describe = '抱歉，您的账号注册时间超过1月10号，无法领取!';
    original_phone_request_response($status,$describe);
}

// 检查是否已申请过，不允许重复申请
$check_att_sql = "select * from ".DBPREFIX."newyear_2020_288w where userid='$user_id'";
$checkresult = mysqli_query($dbLink,$check_att_sql);
$todayData = mysqli_fetch_assoc($checkresult);
if($todayData){
    $status = '401.4';
    $describe = '您已申请过本活动，不允许重复申请哦!';
    original_phone_request_response($status,$describe);
}

/**
红包金额限制，
没有存款次数的会员，8元至18元
盈利会员，红包金额设置88元
负盈利5万内，红包金额设置138
负盈利5万至50万，红包金额设置388元
负盈利50万至100万，红包金额设置1888元
负盈利100万以上，红包金额设置5888元
 */

// 查询会员输赢  WinLossCredit  负数会员赢， 正数会员输
$sql = "SELECT test_flag,DepositTimes,WinLossCredit FROM `".DBPREFIX.MEMBERTABLE."` WHERE ID='$user_id' AND Status<2 ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
if ($cou==0){
    $status = '401.5';
    $describe = '账号异常!';
    original_phone_request_response($status,$describe);
}
if ($row['DepositTimes']<1){ // 没有存款次数
    $giftGold = 8;
}
else{
    $giftGold = goldLevel($row['WinLossCredit']);
}

// 入库存款彩金表，等待审核
$now = date('Y-m-d H:i:s');
$insertData = [
    'userid' => $user_id,
    'username' => trim($username),
    'EventName' => '新年288万红包任你拿',
    'registered_at' => $_SESSION['AddDate'],
    'DepositTimes' => $row['DepositTimes'],
    'WinLossCredit' => $row['WinLossCredit'],
    'gift_gold' => $giftGold,
    'status' => 2, // 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
    'created_at' => $now,
    'updated_at' => $now,
];
foreach($insertData as $key => $val){
    $tmp[] = $key.'=\''.$val.'\'';
}
$sql = "INSERT INTO `" . DBPREFIX . "newyear_2020_288w` SET " . implode(',', $tmp);
//echo $sql; die;
if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
    $status = '500.6';
    $describe = '系统繁忙，请稍后再试吧!';
    original_phone_request_response($status,$describe);
}else{
    $resdata = array('giftGold'=>$giftGold);
    $status = '200';
    $describe = '领取成功、系统自动派发。';
    original_phone_request_response($status,$describe,$resdata);
}


/**
 * 根据输赢返回红包金额
 *
 * @param $numBets
 * @return mixed
 */
function goldLevel($numBets){
    $thousand = 10000;
    if($numBets < 0) {
        $gold = '88';
    } elseif($numBets >= 0 && $numBets < 5*$thousand) {
        $gold = '138';
    } elseif($numBets >= 5*$thousand && $numBets < 50*$thousand) {
        $gold = '388';
    } elseif($numBets >= 50*$thousand && $numBets < 100*$thousand) {
        $gold = '1888';
    } elseif($numBets >= 100*$thousand) {
        $gold = '5888';
    }

    return $gold;
}