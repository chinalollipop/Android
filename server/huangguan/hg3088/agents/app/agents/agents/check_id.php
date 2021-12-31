<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require_once ("../../agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

if( (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level']!='D') {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}


$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$lv=$_REQUEST["lv"];
$username = str_replace(' ','',$_REQUEST["username"]);
require ("../../agents/include/traditional.$langx.inc.php");

if($lv=='MEM'){
$data=DBPREFIX.MEMBERTABLE;
}else{
$data=DBPREFIX.'web_agents_data';
}
$sql = "select ID from $data where UserName='$username'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if ($cou<=0){
	echo "<SCRIPT language='javascript'>alert('$Mem_Account_Available!!');</script>";
}else{
	echo "<SCRIPT language='javascript'>alert('$Mem_Account_NO_Available!!');</script>";
}
?>