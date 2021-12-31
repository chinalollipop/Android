<?php
session_start();
header("Content-type: text/html; charset=utf-8");
include ("../include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$gid=$_REQUEST['gid'];
$gtype=$_REQUEST['gtype'];
$date_start=$_REQUEST['date_start'];
if ($gid!='' and $gtype!=''){
	$mysql="delete from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID='".$gid."' and  Type='".$gtype."'";
	mysqli_query($dbMasterLink,$mysql) or die("操作失败!");
	echo "<script language='javascript'>location.href='play_game.php?gtype=$gtype&uid=$uid&date_start=$date_start&langx=$langx';</script>";
	exit();
}
?>
