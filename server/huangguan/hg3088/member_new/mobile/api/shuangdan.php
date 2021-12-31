<?php
/**
 * 双旦迎春，年终钜惠
 * Date: 2019/12/30
 * Time: 14:29
 */
session_start();
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activity.class.php");

/**
 * 0086
 * 1、统计存款的时间段是美东时间 2020-01-01 00:00:00 到 2020-01-07 23:59:59
 * 2、可以领取时间段为美东时间 2020-01-08 00:00:00 到 2020-01-09 17:59:59
 * 3、每个会员限制领取一次
 */

// 会员信息
$user_id = $_REQUEST['user_id'];
$username = $_REQUEST['username'];

if(empty($user_id) || empty($username)){
    $status = array('status'=>'500.1', 'info'=>'请您先登录!');
    echo json_encode($status);exit;
}

if($_SESSION['Agents'] == 'demoguest'){
    $status = array('status'=>'500.2', 'info'=>'请您注册真实用户!');
    echo json_encode($status);exit;
}


$startTime = '2020-01-08 00:00:00'; // 申请开始时间
$endTime = '2020-01-12 17:59:59'; // 申请结束时间
$now = date('Y-m-d H:i:s');
if($now < $startTime || $now > $endTime){
    $status = array('status'=>'500.3', 'info'=>'请于美东时间2020-01-08 00:00:00到2020-01-12 17:59:59之间申请彩金哦!');
    echo json_encode($status);exit;
}

// 获取当前会员信息
$memberSql = "select ID,UserName,DepositTimes,AddDate,LoginIP,E_Mail,Phone,layer from ".DBPREFIX.MEMBERTABLE." where ID='$user_id'";
$userResult = mysqli_query($dbLink, $memberSql);
$userInfo = mysqli_fetch_assoc($userResult);
$sUserlayer = $userInfo['layer'];
// 检查当前会员是否设置不准领取彩金分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
$layerId=4;
if ($sUserlayer==$layerId){
    $layer = getUserLayerById($layerId);
    if ($layer['status']==1) {
        $status = array('status'=>'500.66', 'info'=>'账号分层异常，请联系我们在线客服');
        echo json_encode($status);exit;
    }
}

$sql = "select * from ".DBPREFIX."web_shuangdan_deposit where userid='$user_id'";
$result = mysqli_query($dbLink, $sql);
$num = mysqli_num_rows($result);
if ($num>0){
    $status = array('status'=>'500.5', 'info'=>'领取失败，每个会员仅限领取一次!');
    echo json_encode($status);exit;
}else{

    // 活动类
    $activity= new Activity();

    //统计会员活动期间存款总金额
    // 活动期间 2020-01-01 00:00:00 到 2020-01-07 23:59:59
    $depositAmountTime['begin_time'] = '2020-01-01 00:00:00';
    $depositAmountTime['end_time'] = '2020-01-07 23:59:59';
    $totalDepositGold = $activity->depositAmount($user_id,$depositAmountTime);

    if($totalDepositGold < 500) {
        $status = array('status'=>'500.6', 'info'=>'存款金额不符合最低存款要求，不允许申请!');
        echo json_encode($status);exit;
    }

    // 若未领取过存款礼金，且满足领取金额，入库存款礼金表，等待人工审核

    $insertData = [
        'userid' => $user_id,
        'username' => trim($username),
        'EventName' => '双旦迎春彩金',
        'registered_at' => $userInfo['AddDate'],
        'total_deposit_money' => $totalDepositGold,
        'gift_gold' => getShuangdanDepositGold($totalDepositGold),
        'status' => 2, // 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
        'created_at' => $now,
        'updated_at' => $now,
    ];
    foreach($insertData as $key => $val){
        $tmp[] = $key.'=\''.$val.'\'';
    }
    $sql = "INSERT INTO `" . DBPREFIX . "web_shuangdan_deposit` SET " . implode(',', $tmp);
//    echo $sql; die;
    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $status = array('status'=>'500.6', 'info'=>'系统繁忙，请稍后再试吧!');
        echo json_encode($status);exit;
    }else{
        $status = array('status'=>'0', 'info'=>'领取成功，请联系24小时客服进行审核');
        echo json_encode($status);exit;
    }


}

function getShuangdanDepositGold($numBets){
    $tenThousand = '10000';
    if($numBets >= 500 && $numBets < 2000) {
        $goldDeposit = '18';
    } elseif($numBets >= 2000 && $numBets < 5000) {
        $goldDeposit = '58';
    } elseif($numBets >= 5000 && $numBets < 1*$tenThousand) {
        $goldDeposit = '88';
    } elseif($numBets >= 1*$tenThousand && $numBets < 5*$tenThousand) {
        $goldDeposit = '188';
    } elseif($numBets >= 5*$tenThousand && $numBets < 10*$tenThousand) {
        $goldDeposit = '388';
    } elseif($numBets >= 10*$tenThousand && $numBets < 20*$tenThousand+2011) {
        $goldDeposit = '888';
    } elseif($numBets >= 20*$tenThousand+2011) {
        $goldDeposit = '1888';
    } else{ // 不满足条件 回馈金额0
        $goldDeposit = '0';
    }
    return $goldDeposit;
}
