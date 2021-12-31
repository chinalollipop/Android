<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");  
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");    
header("Cache-Control: no-store, no-cache, must-revalidate");    
header("Cache-Control: post-check=0, pre-check=0", false);    
header("Pragma: no-cache"); 
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../../agents/include/config.inc.php");
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

if($_SESSION['Level'] == 'M') {
   $web=DBPREFIX.'web_system_data';
}else{
   $web=DBPREFIX.'web_agents_data';
}
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

//$sql = "select ID,UserName,Language,SubUser,SubName from $web where Oid='$uid' and UserName='$loginname'";
//$result = mysqli_query($dbLink,$sql);
//$row = mysqli_fetch_assoc($result);
$name=$_SESSION['UserName'];
$subUser=$_SESSION['SubUser'];
if ($subUser==0){
	$name=$_SESSION['UserName'];
}else{
	$name=$_SESSION['SubName'];
}
$sql = "select ID,UserName from ".DBPREFIX.MEMBERTABLE." where $user='$name' and Pay_Type=1";
$result = mysqli_query($dbLink,$sql);
//$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou<>0){
	echo "<script languag='JavaScript'>self.location='transfer_list_800.php?uid=$uid&lv=$lv&langx=$langx'</script>";
}else{
	echo "<script languag='JavaScript'>alert('目前还没有会员，请添加后再操作!!');history.go( -1 );</script>";
}
?>