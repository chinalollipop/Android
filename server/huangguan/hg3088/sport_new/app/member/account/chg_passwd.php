<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");

// 连接彩票主库
$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];


require ("../include/traditional.$langx.inc.php");
$mysql="Select ID,UserName,PassWord,Address,EditDate from ".DBPREFIX.MEMBERTABLE." where Oid='$uid'";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$aData = array() ;
$UserName=$row['UserName']; // 登录用户名
$password=$row['PassWord']; // 登录密码
$pay_password=$row['Address']; // 支付密码
$editdate=$row['EditDate'];

$flag_action=$_REQUEST["flag_action"]; // 用户判断是更改登录密码还是支付密码
$oldpasd = trim($_REQUEST["oldpassword"]); // 原登录密码
$pasd = trim($_REQUEST["password"]); // 新登录密码
if(TPL_FILE_NAME !='newhg'){ // 新皇冠不需要强制转小写
    $oldpasd = strtolower($oldpasd);
    $pasd = strtolower($pasd);
}
$mdoldpasd = passwordEncryption($oldpasd,$UserName);
$mdpasd = passwordEncryption($pasd,$UserName); // md5加密后


$date=date("Y-m-d");
$todaydate=strtotime(date("Y-m-d"));
$editdate=strtotime($editdate);
$time=($todaydate-$editdate)/86400;

// 新增验证码
if(!$_REQUEST['verifycode']){
    $status = '400.12';
    $describe = "请输入验证码！";
    original_phone_request_response($status,$describe,$aData);

}
if(strtolower($_REQUEST['verifycode']) != $_SESSION['authcode']){
    $status = '400.13';
    $describe = "验证码输入错误！";
    original_phone_request_response($status,$describe,$aData);
}

if($flag_action =='1'){ // 修改登录密码
    if(strlen($pasd)<6 || strlen($pasd) >15){
        $status = '400.11';
        $describe = '请输入6-15位登录密码!';
        original_phone_request_response($status,$describe,$aData);
    }
    if($mdoldpasd != $password){ // 原密码不正确
        $status = '400.1';
        $describe = '原登录密码错误!';
        original_phone_request_response($status,$describe,$aData);
    }

    $mysql = "update " . DBPREFIX.MEMBERTABLE." set PassWord='$mdpasd',EditDate='$date' , Online=1 , OnlineTime=now() where Oid='$uid'";
    $result = mysqli_query($dbMasterLink, $mysql) or die ("操作失败!");
    if (!$result) {
        // 更改失败还原会员密码
        $rallbacksql = "update " . DBPREFIX.MEMBERTABLE." set PassWord='$mdoldpasd',EditDate='$date' , Online=1 , OnlineTime=now() where Oid='$uid'";
        mysqli_query($dbMasterLink,$rallbacksql);

        $status = '400.2';
        $describe = '密码修改失败!';
        original_phone_request_response($status,$describe,$aData);
    }
    $cpsql = "UPDATE gxfcy_user SET userpsw='".$mdpasd."' where hguid=".$row['ID'];
    $updateUserPass = mysqli_query($cpMasterDbLink,$cpsql);//更新彩票用户密码
    if($updateUserPass) {

        $status = '200';
        $describe = '已成功的变更了您的密码~~请回首页重新登入!';
        original_phone_request_response($status,$describe,$aData);
    }
}else{ // 修改支付密码

    if(strlen($pasd) != 6){
        $status = '400.3';
        $describe = '请输入6位数字支付密码!';
        original_phone_request_response($status,$describe,$aData);
    }
    if(!isPayNumber($pasd)){
        $status = '400.4';
        $describe = '请输入6位数字支付密码!';
        original_phone_request_response($status,$describe,$aData);
    }

    if($oldpasd != $pay_password){ // 原密码不正确

        $status = '400.5';
        $describe = '原支付密码错误!';
        original_phone_request_response($status,$describe,$aData);
    }
    $mysql="update ".DBPREFIX.MEMBERTABLE." set Address='$pasd',EditDate='$date' , Online=1 , OnlineTime=now() where Oid='$uid'";
    mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
    $status = '200';
    $describe = '已成功的变更了您的密码!';
    original_phone_request_response($status,$describe,$aData);
}


?>
