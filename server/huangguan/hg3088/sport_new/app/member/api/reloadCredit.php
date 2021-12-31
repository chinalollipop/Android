<?php
session_start();
require ("../include/config.inc.php");

$uid = $_SESSION['Oid'];
$m_date=date('Y-m-d');
$aData = [] ;
if( !isset($uid) || $uid == "" ) {
    $status = '502';
    $describe = '你已退出登录，请重新登录';
    original_phone_request_response($status,$describe,$aData);
}

if(isset($_SESSION['userid']) && $_SESSION['userid'] != "") {
	$sql="select money from ".DBPREFIX.MEMBERTABLE." where ID='".$_SESSION['userid']."' and Oid='".$uid."' and Status=0";
}else {
	$sql="select money from ".DBPREFIX.MEMBERTABLE." where Oid='".$uid."' and Status=0";
}
$result=mysqli_query($dbLink,$sql);
$rs=mysqli_fetch_array($result);
$cou=mysqli_num_rows($result);
if($cou==0){ // 已退出
    session_destroy();
    unset($_SESSION);
    $status = '502';
    $describe = '你已退出登录，请重新登录';
    original_phone_request_response($status,$describe,$aData);
}
$status = '200';
$describe = "获取余额成功！";
$aData = array(
    'monval'=> formatMoney($rs[0]) ,
);
original_phone_request_response($status,$describe,$aData);

?>
