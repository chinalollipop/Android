<?php
include ("address.mem.php");
require ("config.inc.php");
$uid=$_REQUEST['uid'];

$mysql = "select Language,OnlineTime from ".DBPREFIX.MEMBERTABLE." where oid='$uid'";
$result = mysqli_query($dbLink,$mysql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
	setcookie('login_uid','');
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
$langx=$row['Language'];
require ("traditional.$langx.inc.php");
$onlinetime=date("Y-m-d H:i:s");
$logouttime=date('Y-m-d H:i:s',time()-60*60);
$onlinetimes=strtotime(date("Y-m-d H:i:s"));
$time=strtotime($row['OnlineTime']);
$datetime=$onlinetimes-$time;

if($datetime>3600) {
	$sql = "update ".DBPREFIX.MEMBERTABLE." set Oid='logout',Online=0,LogoutTime='$onlinetime' where OnlineTime<'$logouttime'";
	mysqli_query($dbMasterLink,$sql) or die ("error!!");
	echo "<Script language=javascript>alert('您的帐号已经在线一小时无任何操作~~请回首页重新登入!');window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
}

?>