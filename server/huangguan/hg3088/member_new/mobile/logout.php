<?php
include_once('include/config.inc.php');
$uid = $_SESSION['Oid'];
$sql = "update ".DBPREFIX.MEMBERTABLE." set Oid='logout',online=0,LogoutTime=now() where Oid='$uid'";
$result = mysqli_query($dbMasterLink,$sql) or die ("操作失败!");
session_destroy();
unset($_SESSION);
$status = '200';
$describe = '体育已退出登录';
original_phone_request_response($status,$describe);
