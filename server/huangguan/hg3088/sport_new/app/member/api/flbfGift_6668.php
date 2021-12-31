<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activityFlbf.class_6668.php");

/**
 * 1. 会员请求
 *   每月6号    大于等于 166元   6元、
 *   每月16号   大于等于 666元   36元
 *   每月26号   大于等于 2666元  66元
 * 3. 数据表插入
 *      如果用户不存在 插入
 *      如果用户存在  获取最早添加时间  如果大于一月，更新数据。 小于一月，再次插入
 */
$user_id = $_SESSION['userid'];
$username = $_SESSION['UserName'];

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
        $status = '400.66';
        $describe = '账号分层异常，请联系我们在线客服';
        original_phone_request_response($status,$describe);
    }
}

//活动申请时间为  美东时间每月6号00:00至次日00:00   北京时间每月6号中午12：00至次日12：00之前
if(date('d') == '6') {
    $sixnumBets = 166;
    $sixTime['start'] =  mktime(0,0,0,date('m'),6,date('Y')); //当月6号start
    $sixTime['end'] = mktime(23,59,59,date('m'),6,date('Y')); //当月6号end
}elseif(date('d') == '16') {
    $sixnumBets = 666;
    $sixTime['start'] =  mktime(0,0,0,date('m'),16,date('Y')); //当月16号start
    $sixTime['end'] = mktime(23,59,59,date('m'),16,date('Y')); //当月16号end
}elseif(date('d') == '26') {
    $sixnumBets = 2666;
    $sixTime['start'] =  mktime(0,0,0,date('m'),26,date('Y')); //当月26号start
    $sixTime['end'] = mktime(23,59,59,date('m'),26,date('Y')); //当月26号end
}

if( !in_array( date('d') , array('6' , '16' , '26')) ) {
    $status = array('status'=>'0', 'info'=>'请于美东时间每月6,16,26号申请逢六活动哦!');
    echo json_encode($status);exit;
}


//异常点击必发活动领取
$redisObj = new Ciredis();
$attTime = $redisObj->getSimpleOne('activity_flbfgift_useid_'.$user_id);
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<2*60) {
        $status = array('status'=>'0', 'info'=>'不允许多次点击,请稍后申请!');
        echo json_encode($status);exit;
    }
}
// 插入当前申请时间，存入redis, 确保不允许重复申请
$redisObj->insert('activity_flbfgift_useid_'.$user_id, time(), 3*60);

//根据当前日期选择对应时间戳  查询当月逢6线下银行存款
$time['begin_time'] = date('Y-m-d H:i:s' , $sixTime['start']);
$time['end_time'] = date('Y-m-d H:i:s' , $sixTime['end']);

// 会员逢六活动类
$activityFlbf = new ActivityFlbf();
// 查询账单表
$numBets = $activityFlbf->getCompanyDeposit($user_id,$username,$time);

// 如果当天小于对应存款， 不允许会员申请
if($numBets < $sixnumBets){
    $status = array('status'=>'0', 'info'=>'当月该逢六'. date('d').'号公司存款不符合要求，不允许申请哦!');
    echo json_encode($status);exit;
}


// 如果当前日期已申请过，不允许重复申请
$check_att_sql = "select * from ".DBPREFIX."web_sixGold where userid='$user_id' and add_time BETWEEN '".date("Y-m-d H:i:s",$sixTime['start'])."' and '".date("Y-m-d H:i:s",$sixTime['end'])."'";
$checkresult = mysqli_query($dbLink,$check_att_sql);
$todayData = mysqli_fetch_assoc($checkresult);
if($todayData){
    $status = array('status'=>'0', 'info'=>'您已在当天申请过本活动，不允许重复申请哦!');
    echo json_encode($status);exit;
}

// (逢六活动表只保留一月数据)//检查一个月以上数据
$checkTime = date("Y-m-d 23:59:59", strtotime(-date('d').'day')); //上月最后时间
$att_sql = "select * from ".DBPREFIX."web_sixGold where userid='$user_id' and add_time <= '$checkTime'";
$result = mysqli_query($dbLink,$att_sql);
$att_statis = mysqli_fetch_assoc($result);


// 查询周周负盈利活动申请表是否有该用户数据
if(empty($att_statis)){  // 用户不存在
    $flag = 1;//进行插入操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['sixDeposit'] = $numBets; //逢6存款金额
    $data['EventName'] = '逢6彩金';
    $levelResult = $activityFlbf->bfGoldLevel($data['sixDeposit']); //  transferGold彩金 status状态
    $data['sixGold'] = sprintf("%.2f",$levelResult['sixGold']);  // 转运金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['review_time'] = date("Y-m-d H:i:s"); // 派发时间
    $data['review_name'] = ''; // 审核人
    $data['status'] = $levelResult['status'];
} else{  // 用户存在
    $flag = 0;//进行修改操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['sixDeposit'] = $numBets; //逢6存款金额
    $data['EventName'] = '逢6彩金';
    $levelResult = $activityFlbf->bfGoldLevel($data['sixDeposit']); //  transferGold彩金 status状态
    $data['sixGold'] = sprintf("%.2f",$levelResult['sixGold']);  // 领取金额
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
    $sqlinsert="insert into ".DBPREFIX."web_sixGold set ".implode(',',$tmp);
    $res = mysqli_query($dbMasterLink,$sqlinsert);
}else{ // 用户存在
    $sqlupdate="update ".DBPREFIX."web_sixGold set ".implode(',',$tmp)." where ID = {$att_statis['ID']}";
    //@error_log($sqlupdate.PHP_EOL,  3,  '/tmp/aaa.log');
    $res = mysqli_query($dbMasterLink,$sqlupdate);
}
if(!$res){
    $status = array('status'=>'0', 'info'=>'系统繁忙，请稍后再试!');
    echo json_encode($status);
} else {
    $status = array('status'=>'1', 'info'=>'已申请逢6彩金,请联系客服等待派发!');
    echo json_encode($status);
}
exit;






?>