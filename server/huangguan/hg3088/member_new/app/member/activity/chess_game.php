<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activityChess.class.php");

/**
 * 1. 会员请求，当日内申请
 * 2. 统计开元、乐游、皇冠、vg棋牌前一天有效投注， 确认领取彩金
 *      如果用户不存在 插入
 *      如果用户存在  获取最早添加时间  如果大于一月，更新数据。 小于一月，再次插入
 * 3. 数据表插入
 * 4. 如果会员存在，重新统计下注，充值天数，修改
 */
$user_id = $_REQUEST['user_id'];
$username = $_REQUEST['username'];

//活动申请时间为当日
$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));  //2019-05-18 00:00:00
$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1; //2019-05-18 23:59:59

if(!$user_id) {
    $status = array('status'=>'0', 'info'=>'请重新登录哦!');
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

if(time() < $beginToday || time() > $endToday){
    $status = array('status'=>'0', 'info'=>'请于当天申请棋牌彩金哦!');
    echo json_encode($status);exit;
}

//异常点击棋牌彩金领取
$redisObj = new Ciredis();
$attTime = $redisObj->getSimpleOne('activity_chess_useid_'.$user_id);
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<2*60) {
        $status = array('status'=>'0', 'info'=>'不允许多次点击,请稍后申请!');
        echo json_encode($status);exit;
    }
}
// 插入当前申请时间，存入redis, 确保不允许重复申请
$redisObj->insert('activity_chess_useid_'.$user_id, time(), 5*60);

//获取昨日起始时间戳和结束时间戳  用于注单搜索
$lastDayTime['beginYesterday'] = date('Y-m-d 00:00:00' , strtotime('-1 day'));
$lastDayTime['endYesterday'] = date('Y-m-d 23:59:59' , strtotime('-1 day'));
// 棋牌活动类
$activityChess = new ActivityChess();
// 查询棋牌有效投注 ， 注单表保留半个月以内数据，直接查询注单表。
$numBets = $activityChess->lastDayChessValidBet($user_id,$username,$lastDayTime);
//$numBets = 10001;

// 如果昨日打码量小于1千， 不允许会员申请
if($numBets < 1000){
    $status = array('status'=>'0', 'info'=>'投注额不符合要求，不允许申请!');
    echo json_encode($status);exit;
}


// 如果当天时间已申请过，不允许重复申请
$check_att_sql = "select * from ".DBPREFIX."web_chess where userid='$user_id' and add_time BETWEEN '".date("Y-m-d H:i:s",$beginToday)."' and '".date("Y-m-d H:i:s",$endToday)."'";
$checkresult = mysqli_query($dbLink,$check_att_sql);
$todayData = mysqli_fetch_assoc($checkresult);
if($todayData){
    $status = array('status'=>'0', 'info'=>'您已在当日申请过棋牌彩金，不允许重复申请哦!');
    echo json_encode($status);exit;
}

// (棋牌活动表只保留一月数据)//检查一个月以上数据
$checkTime = date("Y-m-d 23:59:59", strtotime(-date('d').'day')); //上月最后时间
$att_sql = "select * from ".DBPREFIX."web_chess where userid='$user_id' and add_time <= '$checkTime'";
//@error_log($att_sql.PHP_EOL,  3,  '/tmp/aaa.log');
//echo $att_sql;
$result = mysqli_query($dbLink,$att_sql);
$att_statis = mysqli_fetch_assoc($result);


// 查询棋牌活动申请表是否有该用户数据
if(empty($att_statis)){  // 用户不存在
    $flag = 1;//进行插入操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['totalBet'] = $numBets; //下注总额
    $data['EventName'] = '棋牌礼金';
    $levelResult = $activityChess->chessGameLevel($data['totalBet']); //  chessGold彩金 status状态
    $data['chessGold'] = sprintf("%.2f",$levelResult['chessGold']);  // 领取金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['review_time'] = date("Y-m-d H:i:s"); // 派发时间
    $data['review_name'] = ''; // 审核人
    $data['status'] = $levelResult['status'];
//    $data['status'] = 2;
} else{  // 用户存在
    $flag = 0;//进行修改操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['totalBet'] = $numBets; //下注总额
    $data['EventName'] = '棋牌礼金';
    $levelResult = $activityChess->chessGameLevel($data['totalBet']); //  chessGold彩金 status状态
    $data['chessGold'] = sprintf("%.2f",$levelResult['chessGold']);  // 领取金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['review_time'] = date("Y-m-d H:i:s");; // 派发时间
    $data['review_name'] = ''; // 审核人
    $data['status'] = $levelResult['status'];
}
foreach($data as $key=>$val){
    $tmp[]=$key.'=\''.$val.'\'';
}
if($flag==1){ // 用户不存在
    $sqlinsert="insert into ".DBPREFIX."web_chess set ".implode(',',$tmp);
    $res = mysqli_query($dbMasterLink,$sqlinsert);
}else{ // 用户存在
    $sqlupdate="update ".DBPREFIX."web_chess set ".implode(',',$tmp)." where ID = {$att_statis['ID']}";
    //@error_log($sqlupdate.PHP_EOL,  3,  '/tmp/aaa.log');
    $res = mysqli_query($dbMasterLink,$sqlupdate);
}
if(!$res){
    $status = array('status'=>'0', 'info'=>'系统繁忙，请稍后再试!');
    echo json_encode($status);
} else {
    $status = array('status'=>'1', 'info'=>'已申请棋牌活动彩金,请联系客服等待派发!');
    echo json_encode($status);
}
exit;






?>