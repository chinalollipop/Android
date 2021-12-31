<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activityHoliday.class.php");

/**
 * 1. 会员请求
 *      时间判断
 * 2. 数据表插入
 *      如果用户不存在 插入
 *      如果用户存在  获取最早添加时间  如果大于一月，更新数据。 小于一月，再次插入
 */
$user_id = $_REQUEST['user_id'];
$username = $_REQUEST['username'];
$phone = isset($_REQUEST['Phone'])?$_REQUEST['Phone']:'';

if(!$user_id) {
    exit(json_encode( ['status' => '0', 'info' => '请重新登录哦！']));
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
        exit(json_encode( ['status' => '401.66', 'info' => '账号分层异常，请联系我们在线客服']));
    }
}
if(!isPhone($phone)){ // 手机号码验证
    exit(json_encode( ['status' => '4025', 'info' => '手机号码不符合规范！']));
}

$moonTime['start'] =  mktime(0,0,0,9,16, date('Y'));    //9月16日 北京时间9月16号 12:00
$moonTime['end'] = mktime(23,59,59,9,16, date('Y'));  //9月17日 北京时间9月17号 12:00

if(time() < $moonTime['start'] || time() > $moonTime['end']){
    exit(json_encode( ['status' => '401.2', 'info' => '请于美东时间9月16号领取礼金哦！']));
}


//异常点击必发活动领取
$redisObj = new Ciredis();
$attTime = $redisObj->getSimpleOne('activity_xyzq_useid_'.$user_id);
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<2*60) {
        exit(json_encode( ['status' => '401.3', 'info' => '不允许多次点击,请稍后申请！']));
    }
}
// 插入当前申请时间，存入redis, 确保不允许重复申请
$redisObj->insert('activity_xyzq_useid_'.$user_id, time(), 2*60);

// 节日活动类
$activityHoliday = new ActivityHoliday();

// 根据当前日期选择对应时间戳  查询会员存款
$time['begin_time'] = date('Y-m-d 00:00:00', mktime(0,0,0,9,13, date('Y')));
$time['end_time'] = date("Y-m-d 23:59:59", mktime(0,0,0,9,15, date('Y')));
$depAmounts = $activityHoliday->getDeposits($user_id , $time);

// 如果当天小于最低存款， 不允许会员申请
if($depAmounts < 1000){
    exit(json_encode( ['status' => '401.4', 'info' => '中秋节日总存款不符合最低存款要求，请先充值哦！']));
}

// 如果当前日期已申请过，不允许重复申请
$check_att_sql = "select * from ".DBPREFIX."web_moon_festival where userid='$user_id' and add_time BETWEEN '".date("Y-m-d H:i:s",$moonTime['start'])."' and '".date("Y-m-d H:i:s",$moonTime['end'])."'";
$checkresult = mysqli_query($dbLink,$check_att_sql);
$todayData = mysqli_fetch_assoc($checkresult);
if($todayData){
    exit(json_encode( ['status' => '401.5', 'info' => '您已在当天申请过本活动，不允许重复申请哦!']));
}

// (喜迎中秋只保留一月数据)//检查一个月以上数据
$checkTime = date("Y-m-d 23:59:59", strtotime(-date('d').'day')); //上月最后时间
$att_sql = "select * from ".DBPREFIX."web_moon_festival where userid='$user_id' and add_time <= '$checkTime'";
$result = mysqli_query($dbLink,$att_sql);
$att_statis = mysqli_fetch_assoc($result);


// 获取当前会员信息  userid,UserName,IP,Phone,E_Mail
if(!empty($user_id)) {
    $member_sql = "select ID,UserName,AddDate,LoginIP,E_Mail,Phone from ".DBPREFIX.MEMBERTABLE." where ID='$user_id'";
    $member_query = mysqli_query($dbLink,$member_sql);
    $memberinfo = mysqli_fetch_assoc($member_query);
}


// 查询中秋佳节彩金申请表是否有该用户数据
if(empty($att_statis)){  // 用户不存在
    $flag = 1;//进行插入操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $result = $activityHoliday->getGiftAmount($depAmounts); //  giftGold彩金 status状态
    $data['GiftGold'] = sprintf("%.2f",$result['giftGold']);  // 领取金额
    $data['EventName'] = '中秋礼金';
    $data['Phone'] = strval($phone);
    $data['E_Mail'] = $memberinfo['E_Mail'];
    $data['AddDate'] = $memberinfo['AddDate'];
    $data['LastDeposit'] = $depAmounts; //存款金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['review_time'] = date("Y-m-d H:i:s"); // 派发时间
    $data['review_name'] = ''; // 审核人
    $data['status'] = $result['status'];
} else{  // 用户存在
    $flag = 0;//进行修改操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $result = $activityHoliday->getGiftAmount($depAmounts); //  giftGold彩金 status状态
    $data['GiftGold'] = sprintf("%.2f",$result['giftGold']);  // 领取金额
    $data['EventName'] = '中秋礼金';
    $data['Phone'] = strval($phone);
    $data['E_Mail'] = $memberinfo['E_Mail'];
    $data['AddDate'] = $memberinfo['AddDate'];
    $data['LastDeposit'] = $depAmounts; //存款金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['review_time'] = date("Y-m-d H:i:s"); // 派发时间
    $data['review_name'] = ''; // 审核人
    $data['status'] = $result['status'];
}
foreach($data as $key=>$val){
    $tmp[]=$key.'=\''.$val.'\'';
}
if($flag==1){ // 用户不存在
    $sqlinsert="insert into ".DBPREFIX."web_moon_festival set ".implode(',',$tmp);
    $res = mysqli_query($dbMasterLink,$sqlinsert);
}else{ // 用户存在
    $sqlupdate="update ".DBPREFIX."web_moon_festival set ".implode(',',$tmp)." where ID = {$att_statis['ID']}";
    //@error_log($sqlupdate.PHP_EOL,  3,  '/tmp/aaa.log');
    $res = mysqli_query($dbMasterLink,$sqlupdate);
}
if(!$res){
    $status = array('status'=>'0', 'info'=>'系统繁忙，请稍后再试!');
    echo json_encode($status);
} else {
    $status = array('status'=>'1', 'info'=>'已申请中秋礼金,稍后24小时内派发!');
    echo json_encode($status);
}
exit;






?>