<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");  
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");    
header("Cache-Control: no-store, no-cache, must-revalidate");    
header("Cache-Control: post-check=0, pre-check=0", false);    
header("Pragma: no-cache"); 
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
require_once ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
require ("../../agents/include/traditional.$langx.inc.php");


switch ($lv){
case 'M':
	$user='Admin';
	break;	
case 'A':
	$user='Super';
	break;
case 'B':
	$user='Corprator';
	break;
case 'C':
	$user='World';
	break;
case 'D':
    $user='Agents';
	break;
}

	echo "<script languag='JavaScript'>self.location='user_list_800_2018.php?lv=$lv'</script>";

?>