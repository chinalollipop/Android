<?php
/**
 * 十一国庆活动
 * Date: 2019/9/19
 * Time: 13:03
 */
session_start();
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activity.class.php");

/**
 * 国庆
 * 0086
 * 1. 活动时间：美东时间10月1日-10月7日截止
 * 2. 申请限制
 *      注册时间在19年9月1日前，包含9月1日，存款次数5次及以上，免费送86，低于5次送38（首次点击送免费彩金）活动期间内首次点击领取，先送免费彩金，提示可继续参与存款礼金，领取过免费礼金的参加存款彩金后再点击的提示当天已领取
 *      当天的存款总金额
 *      如果当天连续1分钟内重复申请，不让申请
 *      每位会员每天仅限申请一次，申请过不能申请
 */

// 会员信息
$user_id = $_SESSION['userid'];
$username = $_SESSION['UserName'];

if(empty($user_id) || empty($username)){
    $status = array('status'=>'0', 'info'=>'请您先登录!');
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
        $status = array('status'=>'0', 'info'=>'账号分层异常，请联系我们在线客服');
        echo json_encode($status);exit;
    }
}

if($_SESSION['Agents'] == 'demoguest'){
    $status = array('status'=>'0', 'info'=>'请您注册真实用户!');
    echo json_encode($status);exit;
}

// 活动申请时间为
$regEndTime = '2019-09-01 23:59:59';
$startTime = '2019-10-01 00:00:00';
$endTime = '2019-10-07 23:59:59';
$now = date('Y-m-d H:i:s');
if($now < $startTime || $now > $endTime){
    $status = array('status'=>'0', 'info'=>'请于美东时间10月1日-10月7日申请彩金哦!');
    echo json_encode($status);exit;
}

// 异常点击国庆申请按钮
$redisObj = new Ciredis();
$attTime = $redisObj->getSimpleOne('activity_national_day_'.$user_id);
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<60) {
        $status = array('status'=>'0', 'info'=>'不允许多次点击,请稍后申请!');
        echo json_encode($status);exit;
    }
}
// 插入当前申请时间，存入redis, 确保不允许重复申请
$redisObj->insert('activity_national_day_'.$user_id, time(), 10*60);

// 获取当前会员信息
$memberSql = "select ID,UserName,DepositTimes,AddDate,LoginIP,E_Mail,Phone from ".DBPREFIX.MEMBERTABLE." where ID='$user_id'";
$userResult = mysqli_query($dbLink, $memberSql);
$userInfo = mysqli_fetch_assoc($userResult);

// 1.免费礼金（是否在10月1号到7号间领取）
$sql = "select * from ".DBPREFIX."web_national_register where userid='$user_id' and created_at BETWEEN '$startTime' and '$endTime'";
$result = mysqli_query($dbLink, $sql);
$num = mysqli_num_rows($result);

// 若未领取过免费礼金，第一次点击按钮送免费礼金
if(!$num){
    // 满足条件：1>注册时间在19年9月1号之前包括9月1号
    $goldFree = 0;
    if($userInfo['AddDate'] <= $regEndTime){
        // 满足条件：2>存款次数>=5，送86；<5，送38
        if($userInfo['DepositTimes'] >= 5){
            $goldFree = 86;
        }else{
            $goldFree = 38;
        }
    }
    // 条件1>和2>都满足，入库注册礼金表，等待人工审核
    if($goldFree){
        $insertData = [
            'userid' => $user_id,
            'username' => $username,
            'ip' => $userInfo['LoginIP'],
            'phone' => $userInfo['Phone'],
            'email' => $userInfo['E_Mail'],
            'registered_at' => $userInfo['AddDate'],
            'deposit_times' => $userInfo['DepositTimes'],
            'gold_free' => $goldFree,
            'status' => 2, // 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
            'created_at' => $now,
            'updated_at' => $now,
        ];
        foreach($insertData as $key => $val){
            $tmp[] = $key.'=\''.$val.'\'';
        }
        $sql = "INSERT INTO `" . DBPREFIX . "web_national_register` SET " . implode(',', $tmp);
        if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
            $status = array('status'=>'0', 'info'=>'系统繁忙，请稍后再试哦!');
            echo json_encode($status);exit;
        }else{
            $status = array('status'=>'0', 'info'=>'免费礼金申请成功，请您继续参与存款礼金!');
            echo json_encode($status);exit;
        }
    }
}

// 2.存款礼金
$depositSql = "select * from ".DBPREFIX."web_national_deposit where userid='$user_id' and created_at BETWEEN '".date("Y-m-d 00:00:00")."' and '".date("Y-m-d 23:59:59")."'";
$depositResult = mysqli_query($dbLink,$depositSql);
$depositNum = mysqli_num_rows($depositResult);
if($depositNum > 0){
    $status = array('status'=>'0', 'info'=>'您已在今天申请过存款彩金，不允许重复申请!');
    echo json_encode($status);exit;
}

// 活动类
$activity= new Activity();

//统计会员当天存款最大一单存款金额
$depositAmountTime['begin_time'] = date('Y-m-d 00:00:00');
$depositAmountTime['end_time'] = date('Y-m-d 23:59:59');
$maxDepositOfToday = $activity->depositMaxAmount($user_id, $depositAmountTime);

if($maxDepositOfToday < 100) {
    $status = array('status'=>'0', 'info'=>'存款金额不符合最低存款要求，不允许申请!');
    echo json_encode($status);exit;
}

// 若未领取过存款礼金，且满足领取金额，入库存款礼金表，等待人工审核
if(!$depositNum){
    $insertData = [
        'userid' => $user_id,
        'username' => trim($username),
        'ip' => $userInfo['LoginIP'],
        'phone' => $userInfo['Phone'],
        'email' => $userInfo['E_Mail'],
        'registered_at' => $userInfo['AddDate'],
        'max_deposit_money' => $maxDepositOfToday, // 单笔最大存款金额
        'gold_deposit' => $activity->getDepositGold($maxDepositOfToday),
        'status' => 2, // 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
        'created_at' => $now,
        'updated_at' => $now,
    ];
    foreach($insertData as $key => $val){
        $tmp[] = $key.'=\''.$val.'\'';
    }
    $sql = "INSERT INTO `" . DBPREFIX . "web_national_deposit` SET " . implode(',', $tmp);
    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $status = array('status'=>'0', 'info'=>'系统繁忙，请稍后再试吧!');
        echo json_encode($status);exit;
    }else{
        $status = array('status'=>'0', 'info'=>'请扫一扫关注微信公众号领取！');
        echo json_encode($status);exit;
    }
}