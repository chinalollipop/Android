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

$sAg_prefix = $agsxInitp['data_api_cagent'].$agsxInitp['data_api_user_prefix'].'_'; // AG用户名前缀 BT5A_，返水需要转为体育的用户名
$aCp_default = $database['cpDefault'];

// 1. 会员请求，验证时间每月三号前申请奖励，6668会员请求，美东时间1-10号申请奖励
// 2. 统计会员上月下注总额(体育，真人，彩票, 捕鱼王,棋牌)，充值天数， 确认领取奖金,6668统计只体育数据
// 3. 数据表插入
// 4. 如果会员存在，重新统计下注，充值天数，修改
//

$user_id = $_REQUEST['user_id'];
$username = $_REQUEST['username'];
$platfrom = $_REQUEST['platfrom']; // 平台 hg6668(6668 只计算体育的有效投注) , hg0086(包含所有投注)

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

// 美东时间1号到3号   北京时间每月1号中午12:00到4号中午12：00
$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
$endThismonth=mktime(23,59,59,date('m'),3,date('Y'));
if(TPL_FILE_NAME=='6668'){
    $endThismonth=mktime(23,59,59,date('m'),10,date('Y'));
}
if(TPL_FILE_NAME=='0086'){
    $endThismonth=mktime(23,59,59,date('m'),6,date('Y'));
}
//echo '本月开始时间截止时间'.date('Y-m-d H:i:s',$beginThismonth).'----'.date('Y-m-d H:i:s',$endThismonth);echo '<br>';
//2018-07-01 00:00:00----2018-07-03 23:59:59
if(time() < $beginThismonth || time() > $endThismonth){
    //echo json_encode(array('msg'=>'请于每月三号前申请奖励') ,JSON_UNESCAPED_UNICODE);
    $status = array('status'=>'0', 'info'=>'请于美东时间每月1号-3号之间申请奖励哦!');
    if(TPL_FILE_NAME=='6668'){
        $status = array('status'=>'0', 'info'=>'请于美东时间每月1号-10号之间申请奖励哦!');
    }
    if(TPL_FILE_NAME=='0086'){
        $status = array('status'=>'0', 'info'=>'请于美东时间每月1号-6号之间申请奖励哦!');
    }
    echo json_encode($status);exit;
}

$att_sql = "select * from ".DBPREFIX."web_attendance where userid='$user_id'";
$result = mysqli_query($dbLink,$att_sql);
$att_statis = mysqli_fetch_assoc($result);

//异常点击全勤奖
$redisObj = new Ciredis();
$attTime = $redisObj->getSimpleOne('activity_attendance_useid_'.$user_id);
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<2*60) {
        $status = array('status'=>'0', 'info'=>'不允许多次点击,请稍后申请!');
        echo json_encode($status);exit;
    }
}

// 插入当前申请时间，存入redis, 确保不允许重复申请
$redisObj->insert('activity_attendance_useid_'.$user_id, time(), 10*60);

// 活动类
$activity= new Activity();

// 上月存款天数
$time['begin_time'] = date('Y-m-01 00:00:00',strtotime('-1 month'));  // 上月开始时间截止时间
$time['end_time'] = date("Y-m-d 23:59:59", strtotime(-date('d').'day'));
$depositDays = $activity->lastMonthDeposit($user_id , $time);
if($depositDays < 10){
    $status = array('status'=>'0', 'info'=>'上月存款天数不满10天，不允许申请!');
    echo json_encode($status);exit;
}

// 判断用户当月是否领过
$month = date('Y-m',strtotime($att_statis['upd_time']));
if($att_statis['upd_time'] && ($month == date('Y-m'))){
    $status = array('status'=>'0', 'info'=>'您已在本月申请全勤奖，不允许重复申请哦!');
    echo json_encode($status);exit;
}

$type = 'quanqin';
//判断是否是一号三点以前, 小于3点 需要在注单表查询用户投注总额，大于3点 注单表将昨天数据生成到历史报表
if(date('d') ==1 && (int)date("G") < 3) {
    $lastDayTime['begin_time'] = date('Y-m-d 00:00:01',strtotime(-date('d').'day'));  // 上月最后一天
    $lastDayTime['end_time'] = date("Y-m-d 23:59:59", strtotime(-date('d').'day'));
    //@error_log($lastDayTime['begin_time'].'--'.$lastDayTime['end_time'].PHP_EOL,  3,  '/tmp/aaa.log');
    $lastDayBets = $activity->lastDayBet($user_id,$username,$lastDayTime,$sAg_prefix,$aCp_default,$type,$platfrom);
}

// 会员上月下注投注总额(体育、真人、彩票 、 捕鱼王、棋牌)
$lastMonthTime['begin_time'] = date('Y-m-01 00:00:00',strtotime('-1 month'));  // 上月开始时间
$lastMonthTime['end_time'] = date("Y-m-d 23:59:59", strtotime(-date('d').'day')); //截止时间
//@error_log($lastMonthTime['begin_time'].'--'.$lastMonthTime['end_time'].PHP_EOL,  3,  '/tmp/aaa.log');
$lastNumBets = $activity->lastHistoryBet($user_id,$username,$lastMonthTime,$sAg_prefix,$aCp_default,$type,$platfrom); //52685

$numBets = $lastDayBets + $lastNumBets;  //下注总额等于上月投注和最后一天之和

if($numBets < 10*10000){
    $status = array('status'=>'0', 'info'=>'上月有效投注不满10万，不符合要求!');
    echo json_encode($status);exit;
}

//@error_log('存款天数'.$depositDays.'--上月最后一天投注:'.$lastDayBets.'--上月投注'.$lastNumBets.'总投注'.$numBets.PHP_EOL,  3,  '/tmp/aaa.log');

// 查询全勤奖表是否有该用户数据
if(empty($att_statis)){  // 用户不存在
    $flag = 1;//进行插入操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['totalBet'] = $numBets; //下注总额
    $data['rechargeDay'] = intval($depositDays); //充值天数

    //$data['rechargeDay'] = 21; //充值天数
    //$data['totalBet'] = 101*10000; //下注总额
    if(TPL_FILE_NAME=='6668'){
        $returnFeed = $activity->Loyalty($data['totalBet'] , $data['rechargeDay']);
    }else{
        $returnFeed = $activity->feedBack($data['totalBet'] , $data['rechargeDay']);
    }
    $data['cashBack'] = $returnFeed['cashBack'];  // 回馈金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['distribute_time'] = ''; // 派发时间
    $data['status'] = $returnFeed['cashStatus'];
} else{  // 用户存在
    $flag = 0;//进行修改操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['totalBet'] = $numBets; //下注总额
    $data['rechargeDay'] = intval($depositDays); //充值天数


    if(TPL_FILE_NAME=='6668'){
        $returnFeed = $activity->Loyalty($data['totalBet'] , $data['rechargeDay']);
    }else{
        $returnFeed = $activity->feedBack($data['totalBet'] , $data['rechargeDay']);
    }
    $data['cashBack'] = $returnFeed['cashBack'];  //回馈金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['distribute_time'] = ''; // 派发时间
    $data['status'] = $returnFeed['cashStatus']; //审核状态
}
foreach($data as $key=>$val){
    $tmp[]=$key.'=\''.$val.'\'';
}
if($flag==1){ // 用户不存在
    $sqlinsert="insert into ".DBPREFIX."web_attendance set ".implode(',',$tmp);
    $res = mysqli_query($dbMasterLink,$sqlinsert);
}else{ // 用户存在
    $sqlupdate="update ".DBPREFIX."web_attendance set ".implode(',',$tmp)." where ID = {$att_statis['ID']}";
    //@error_log($sqlupdate.PHP_EOL,  3,  '/tmp/aaa.log');
    $res = mysqli_query($dbMasterLink,$sqlupdate);
}
if(!$res){
    $status = array('status'=>'0', 'info'=>'系统繁忙，请稍后再试!');
    echo json_encode($status);
} else {
    $status = array('status'=>'1', 'info'=>'已申请全勤优惠,请联系客服等待派发!');
    echo json_encode($status);
}
exit;






?>