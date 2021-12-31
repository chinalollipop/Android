<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activity.class.php");

/**
 * 中秋国庆
 * 0086
 * 1. 活动时间：美东时间9月22日-10月7日截止
 * 2. 申请限制
 *      前一天的存款总金额
 *      如果当天连续1分钟内重复申请，不让申请
 *      每位会员每天仅限申请一次，申请过不能申请
 *
 * 6668
 * 1. 活动时间：美东时间9月21日-10月7日
 * 2. 申请限制
 *      如果当天连续1分钟内申请，不让申请
 *      查找节日礼金表会员当天申请次数，最后一次申请时间
 *          如果当天内申请此活动大于3次，不让申请
 *          如果没有记录则插入（单笔存款额不满100元，不让申请）
 *          如果当天内申请此活动小于3次
 *              按上次申请时间为开始时间-结束时间(当前申请时间)，查找当前会员存款记录，
 *                  没有则不让申请，
 *                  有多笔则按单笔存款最大额申请存款彩金（单笔存款额不满100元，不让申请）
 */

$user_id = $_REQUEST['user_id'];
$username = $_REQUEST['username'];

//活动申请时间为  美东时间9月23日-10月7日  2018-09-23 00:00:00-2018-10-07 23:59:59  北京时间9月23号 12:00 -10月08号 12:00
$actBeginTime=  mktime(0,0,0,9,23,date('Y')); //节日活动开始时间
$actEndTime = mktime(23,59,59,10,7,date('Y')); //节日活动结束时间

if(time() < $actBeginTime || time() > $actEndTime){
    $status = array('status'=>'0', 'info'=>'请于美东时间9月23日-10月7日申请彩金哦!');
    echo json_encode($status);exit;
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
        $status = array('status'=>'401.66', 'info'=>'账号分层异常，请联系我们在线客服');
        echo json_encode($status);exit;
    }
}

//异常点击中秋国庆申请
$redisObj = new Ciredis();
$attTime = $redisObj->getSimpleOne('activity_national_useid_'.$user_id);
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<60) {
        $status = array('status'=>'0', 'info'=>'不允许多次点击,请稍后申请!');
        echo json_encode($status);exit;
    }
}
// 插入当前申请时间，存入redis, 确保不允许重复申请
$redisObj->insert('activity_national_useid_'.$user_id, time(), 10*60);

// 如果双节红包彩金表hgty78_web_double_holiday  会员当天已经申请过，则不需申请
$check_mematt_sql = "select * from ".DBPREFIX."web_double_holiday where userid='$user_id' and add_time BETWEEN '".date("Y-m-d 00:00:00",time())."' and '".date("Y-m-d 23:59:59",time())."'";
$checkresult = mysqli_query($dbLink,$check_mematt_sql);
$todayData = mysqli_fetch_assoc($checkresult);
if($todayData){
    $status = array('status'=>'0', 'info'=>'您已在今天申请过彩金，不允许重复申请!');
    echo json_encode($status);exit;
}

// 活动类
$activity= new Activity();

//统计会员昨日存款总金额
$depositAmountTime['begin_time'] = date('Y-m-d 00:00:00', strtotime("-1 day"));
$depositAmountTime['end_time'] = date("Y-m-d 23:59:59", strtotime("-1 day"));
$lastDayGold = $activity->depositAmount($userid,$depositAmountTime);
//@error_log("昨天存款总金额:"."$lastDayGold".PHP_EOL, 3, '/tmp/aaa.log');
// 如果前一天的存款总金额小于1000， 不允许会员申请
if($lastDayGold < 1000) {
    $status = array('status'=>'0', 'info'=>'前一天总存款金额不符合最低存款要求，不允许申请!');
    echo json_encode($status);exit;
}

// 双节红包彩金hgty78_web_double_holiday表 会员当天没有申请
if(empty($todayData)){
    $flag = 1;//进行插入操作
    $data['userid'] = intval($user_id);
    $data['UserName'] = trim($username);
    $holidayGiftData = $activity->getLastDepositGift($lastDayGold);  // 根据存款金额获取节日礼金  GiftGold , status
    $data['GiftGold'] = $holidayGiftData['GiftGold'] ; // 领取礼金

    $data['IP'] = $memberinfo['LoginIP'];
    $data['Phone'] = $memberinfo['Phone'];
    $data['E_Mail'] = $memberinfo['E_Mail'];
    $data['AddDate'] = $memberinfo['AddDate'];
    $data['LastDeposit'] = sprintf("%.2f",$lastDayGold);  // 昨日存款金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['review_time'] = ''; // 派发时间
    $data['status'] = $holidayGiftData['status']; //'状态：1已派发,2未审核,3不符合,4已拒绝'
}

foreach($data as $key=>$val){
    $tmp[]=$key.'=\''.$val.'\'';
}
if($flag==1){ // 用户未申请
    $sqlinsert="insert into ".DBPREFIX."web_double_holiday set ".implode(',',$tmp);
    $res = mysqli_query($dbMasterLink,$sqlinsert);
}
if(!$res){
    $status = array('status'=>'0', 'info'=>'系统繁忙，请稍后再试!');
    echo json_encode($status);
} else {
    $status = array('status'=>'1', 'info'=>'已申请双节彩金,请联系客服等待派发!');
    echo json_encode($status);
}
exit;




?>