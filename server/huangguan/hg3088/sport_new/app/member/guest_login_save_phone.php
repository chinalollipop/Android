<?php
/**
 * 试玩登陆，保存手机号、登入时间
 */
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("include/config.inc.php");
require ("include/address.mem.php");

$redisObj = new Ciredis();
/* type : 1 全站，2 登录，3 注册，4 登录/注册 */
$datastr = $redisObj->getSimpleOne('font_ip_limit');
$datastr = json_decode($datastr,true) ;
$iptype = $datastr['type'] ;
$dataiparr = explode(';',$datastr['list']);

$ip_addr=get_ip();

if(stripos($ip_addr,",")) {
    $ip_addr_array = explode(',',$ip_addr);
    foreach($ip_addr_array as $ip_addr) {
        if($iptype ==2 && in_array(trim($ip_addr),$dataiparr) || ( $iptype ==1 && in_array(trim($ip_addr),$dataiparr) ) || ( $iptype ==4 && in_array(trim($ip_addr),$dataiparr) ) ){
            $status='400.01';
            $describe="你已被禁止登陆!";
            original_phone_request_response($status,$describe);
        }
    }
}else {
    if($iptype ==2 && in_array(trim($ip_addr),$dataiparr) || ( $iptype ==1 && in_array(trim($ip_addr),$dataiparr) ) || ( $iptype ==4 && in_array(trim($ip_addr),$dataiparr) ) ){
        $status='400.011';
        $describe="你已被禁止登陆!";
        original_phone_request_response($status,$describe);
    }
}

$phone=$_REQUEST['phone']; //手机号
if(!isPhone($phone)){ // 手机号码验证
    $status='400.02';
    $describe="手机号码不符合规范!";
    original_phone_request_response($status,$describe);
}

// 新增验证码
if(!$_REQUEST['verifycode']){
    $status='400.03';
    $describe="请输入验证码!";
    original_phone_request_response($status,$describe);
}

if(strtolower($_REQUEST['verifycode']) != $_SESSION['authcode']){
    $status='400.04';
    $describe="验证码输入错误!";
    original_phone_request_response($status,$describe);
}

$sql="insert into ".DBPREFIX."web_guest_phone_data set ";
$sql.="phone='".$phone."',";
$sql.="login_time='".date('Y-m-d H:i:s')."'";

if(mysqli_query($dbMasterLink,$sql)) {
    $status='200';
    $describe="手机号提交成功";
    original_phone_request_response($status,$describe);
}else {
    $status='400.05';
    $describe="操作失败!!!";
    original_phone_request_response($status,$describe);
}
