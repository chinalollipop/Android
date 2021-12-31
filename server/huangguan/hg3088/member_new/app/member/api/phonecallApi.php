<?php
session_start();
/**
 *电话回访
 *
 */

require ("../include/config.inc.php");
include "../include/address.mem.php";

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '502';
    $describe = '你已退出登录，请重新登录';
    $aData = [] ;
    original_phone_request_response($status,$describe,$aData);
}
$agents =$_SESSION['Agents'];
$world = $_SESSION['World'];
$corprator =$_SESSION['Corprator'];
$super = $_SESSION['Super'];
$admin = $_SESSION['Admin'];
$username = $_SESSION['UserName'];
$test_flag =$_SESSION['test_flag'];

$memuserid = $_SESSION['userid'] ; // 用户id
$memphone = isset($_REQUEST['userPhone'])?$_REQUEST['userPhone']:'' ; // 用户 提交手机

$today = date("Y-m-d",time());
$todaytime = date('Y-m-d H:i:s') ;

if(!isPhone($memphone)){
    $status = '401';
    $describe = '请输入合法手机号码';
    $aData = [] ;
    original_phone_request_response($status,$describe,$aData);
}

$redisObj = new Ciredis();
$datajson = $redisObj->getSimpleOne('phone_call_key_'.$memuserid); // 取redis 设置的值
$datajson = json_decode($datajson,true) ;
// echo $datajson['day'].'---'.$datajson['status'] ;
// 审核状态 0 首次提交 1 已回访 2 后期回访 -1拒绝回访
if(($datajson['day'] == $today && $datajson['status'] == 0) || ($datajson['day'] == $today && $datajson['status'] == 2)){ // 今天已有未处理的回访，不在提交新的申请
    $status = '402';
    $describe = '你有未处理的申请，请耐心等待。';
    $aData = [] ;
    original_phone_request_response($status,$describe,$aData);

}


$sql = "insert into `".DBPREFIX."web_member_phonecall` set userid='$memuserid',Checked=0,AddDate='".$today."',UserName='$username',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',Date='$todaytime',Phone='$memphone',test_flag='$test_flag',Type='P'";
$link = mysqli_query($dbMasterLink,$sql);
// echo $sql;die;
if ($link){
    $setdata = array(
        'day'=> $today ,
        'status'=> 0 ,
    );

    $redisObj->setOne('phone_call_key_'.$memuserid,json_encode($setdata)) ;
    $status = '200';
    $describe = '电话回拨成功，请保持电话畅通。';
    original_phone_request_response($status,$describe);

}else{
    $status = '500';
    $describe = '网络错误，请稍后重试';
    original_phone_request_response($status,$describe);

}
//$redisObj = new Ciredis();
//if($demourl || $apiurl || $ld_apiurl || $agentid || $deskey || $md5key){
//    $redisObj->setOne('lyqp_api_set',json_encode($lyqpdata)) ;
//}



