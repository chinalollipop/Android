<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activity.class.php");

$user_id = $_SESSION['userid'];
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

/**
 *
 * 1. 会员请求，请求时间美东时间每周一00:00至次日00:00   北京时间周一中午12：00至次日12：00之前申请奖励
 * 2. 统计会员体育上周有效投注， 确认晋升彩金
 *      如果用户不存在 插入
 *      如果用户存在  获取最早添加时间  如果大于一月，更新数据。 小于一月，再次插入
 * 3. 数据表插入
 * 4. 如果会员存在，重新统计下注，充值天数，修改
 *
 */
$user_id = $_REQUEST['user_id'];
$username = $_REQUEST['username'];
$lastWeekBet = $_REQUEST['lastWeekBet'];

//活动申请时间为  美东时间每周一00:00至次日00:00   北京时间周一中午12：00至次日12：00之前
$nowMonday =  mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y')); //本周一start
$nowTuesday = mktime(0,0,0,date('m'),date('d')-date('w')+2,date('Y')); //本周二start

if(time() < $nowMonday || time() > $nowTuesday){
    //$status = array('status'=>'0', 'info'=>'请于美东时间每周一00:00至次日00:00申请彩金哦!');
    $status = array('status'=>'0', 'info'=>'请于北京时间每周一12:00至次日12:00申请彩金哦!');
    echo json_encode($status);exit;
}

// 如果上周打码量小于3w， 不允许会员申请
if($lastWeekBet < 30*1000){
    $status = array('status'=>'0', 'info'=>'投注额不符合要求，不允许申请!');
    echo json_encode($status);exit;
}

//异常点击vip晋升
$redisObj = new Ciredis();
$attTime = $redisObj->getSimpleOne('activity_promotion_useid_'.$user_id);
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<2*60) {
        $status = array('status'=>'0', 'info'=>'不允许多次点击,请稍后申请!');
        echo json_encode($status);exit;
    }
}
// 插入当前申请时间，存入redis, 确保不允许重复申请
$redisObj->insert('activity_promotion_useid_'.$user_id, time(), 10*60);

// 如果当前周一时间已申请过，不允许重复申请
$checkBeginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
$checkEndToday = $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
$check_att_sql = "select * from ".DBPREFIX."web_promotion where userid='$user_id' and add_time BETWEEN '".date("Y-m-d H:i:s",$checkBeginToday)."' and '".date("Y-m-d H:i:s",$checkEndToday)."'";
$checkresult = mysqli_query($dbLink,$check_att_sql);
$todayData = mysqli_fetch_assoc($checkresult);
if($todayData){
    $status = array('status'=>'0', 'info'=>'您已在本周申请过彩金，不允许重复申请哦!');
    echo json_encode($status);exit;
}

// (晋升彩金表只保留一月数据)//检查一个月以上数据
$checkTime = date("Y-m-d 23:59:59", strtotime(-date('d').'day')); //上月最后时间
$att_sql = "select * from ".DBPREFIX."web_promotion where userid='$user_id' and add_time <= '$checkTime'";
//echo $att_sql;
$result = mysqli_query($dbLink,$att_sql);
$att_statis = mysqli_fetch_assoc($result);

// 活动类
$activity= new Activity();
$type = 'vip';
//如果周一 申请 小于3点 需要在注单表查询用户周日投注总额，  大于3点 注单表已将昨天(周日)数据生成到历史报表
// 不需要，web_report_data 表最少保留15日
/*if(date('w') ==1 && (int)date("G") < 3) {
    $lastDayTime['begin_time'] = date('Y-m-d H:i:s',mktime(0,0,0,date('m'),date('d')-1,date('Y'))); // 昨天时间戳
    $lastDayTime['end_time'] = date('Y-m-d H:i:s',mktime(0,0,0,date('m'),date('d'),date('Y'))-1);
    $lastDayBets = $activity->lastDayBet($user_id,$username,$lastDayTime,'','',$type);
}*/

//获取上周起始时间戳和结束时间戳
/*$begin_time=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
$end_time=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
$lastWeekTime['begin_time'] = date('Y-m-d H:i:s',$begin_time);
$lastWeekTime['end_time'] = date('Y-m-d H:i:s',$end_time);  //2018-07-23 00:00:00----2018-07-29 23:59:59

// 会员上周下注投注总额(体育)
$lastNumBets = $activity->lastDayBet($user_id,$username,$lastWeekTime,'','',$type); //52685
$numBets = $lastNumBets + $lastDayBets;  //下注总额等于昨天投注和上周投注之和*/
$numBets = $lastWeekBet;  // 上周有效投注

// 查询全勤奖表是否有该用户数据
if(empty($att_statis)){  // 用户不存在
    $flag = 1;//进行插入操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['totalBet'] = $numBets; //下注总额
//    $data['totalBet'] = 500000; //下注总额
    $data['EventName'] = 'VIP晋升';
    $levelResult = $activity->levelLottery($data['totalBet']); //memLevel彩金层级  vipGold彩金 status状态
    $data['memLevel'] = $levelResult['memLevel'];
    $data['vipGold'] = sprintf("%.2f",$levelResult['vipGold']);  // 领取金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['review_time'] = ''; // 派发时间
    $data['status'] = $levelResult['status'];
//    $data['status'] = 2;
} else{  // 用户存在
    $flag = 0;//进行修改操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['totalBet'] = $numBets; //下注总额
//    $data['totalBet'] = 100000; //下注总额
    $levelResult = $activity->levelLottery($data['totalBet']); //memLevel彩金层级  vipGold彩金 status状态
    $data['memLevel'] = $levelResult['memLevel'];
    $data['vipGold'] = sprintf("%.2f",$levelResult['vipGold']);  // 领取金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['review_time'] = ''; // 派发时间
    $data['review_name'] = ''; // 审核人
    $data['status'] = $levelResult['status'];
}
foreach($data as $key=>$val){
    $tmp[]=$key.'=\''.$val.'\'';
}
if($flag==1){ // 用户不存在
    $sqlinsert="insert into ".DBPREFIX."web_promotion set ".implode(',',$tmp);
//    @error_log($sqlinsert.PHP_EOL,  3,  '/tmp/aaa.log');
    $res = mysqli_query($dbMasterLink,$sqlinsert);
}else{ // 用户存在
    $sqlupdate="update ".DBPREFIX."web_promotion set ".implode(',',$tmp)." where ID = {$att_statis['ID']}";
//    @error_log($sqlupdate.PHP_EOL,  3,  '/tmp/aaa.log');
    $res = mysqli_query($dbMasterLink,$sqlupdate);
}
if(!$res){
    $status = array('status'=>'0', 'info'=>'系统繁忙，请稍后再试!');
    echo json_encode($status);
} else {
    $status = array('status'=>'1', 'info'=>'已申请VIP晋升彩金,请联系客服等待派发!');
    echo json_encode($status);
}
exit;






?>