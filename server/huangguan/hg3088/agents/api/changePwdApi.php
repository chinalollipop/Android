<?php

include ("../app/agents/include/address.mem.php");
require ("../app/agents/include/config.inc.php");

//checkAdminLogin(); // 同一账号不能同时登陆
$resdata = array();
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '400.01';
    $describe = '您的登录信息已过期,请重新登录!';
    original_phone_request_response($status,$describe,$resdata);
}
$langx = $_SESSION['langx'];
require ("../app/agents/include/traditional.$langx.inc.php");
$uid = $_SESSION['Oid'];
$passwd_old= trim($_REQUEST["passwd_old"]);
$newpwd = trim($_REQUEST["passwd"]); // 新密码
$newREpwd = trim($_REQUEST["REpasswd"]); // 确认新密码

if($_SESSION['Level'] == 'M') {  // 超级管理员
    $data=DBPREFIX.'web_system_data';
}else{  // 代理
    $data=DBPREFIX.'web_agents_data';
}

$sql = "select PassWord from $data where Oid='$uid'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$password=$row['PassWord'];
$UserName= $_SESSION['UserName'];

$passwdOldJM = passwordEncryption($passwd_old,$UserName);

if($passwdOldJM!=$password){
    $status = '400.02';
    $describe = '原始密码填写错误！';
    original_phone_request_response($status,$describe,$resdata);
}
if(strlen($newpwd) >15 || strlen($newpwd)<6){
    $status = '400.03';
    $describe = '密码填不符合规范！';
    original_phone_request_response($status,$describe,$resdata);
}
if($newpwd != $newREpwd){
    $status = '400.04';
    $describe = '确认密码填写错误！';
    original_phone_request_response($status,$describe,$resdata);
}
$passwd=passwordEncryption(strtolower($newpwd),$UserName);
$date=date("Y-m-d");

$mysql="update $data set PassWord='$passwd',EditDate='$date' where Oid='$uid'";
if(mysqli_query($dbMasterLink,$mysql)){
    $status = '200';
    $describe = $Mem_ChangePasswordSuccess;
    original_phone_request_response($status,$describe,$resdata);
}else{
    $status = '500';
    $describe = '操作失败!';
    original_phone_request_response($status,$describe,$resdata);
}

