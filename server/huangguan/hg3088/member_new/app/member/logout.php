<?php
session_start();
include "./include/address.mem.php";
require ("./include/config.inc.php");

$redisObj = new Ciredis();
$redisObj->delete($_SESSION['userid'].'_IS_HGUNIONCP');

$uid=$_REQUEST['uid'];
session_destroy();
unset($_SESSION);
$sql = "update ".DBPREFIX.MEMBERTABLE." set Oid='logout',online=0,LogoutTime=now() where Oid='$uid'";
$result = mysqli_query($dbMasterLink,$sql) or die ("操作失败!");
$urlLogout=HTTPS_HEAD.'://'.CP_URL.'.'.getMainHost().'/main/out.winer';
echo "<script src=".$urlLogout." ></script>";
echo "<script>top.location.href='/';</script>";
?>

