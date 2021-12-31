<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../../agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$lv=$_REQUEST["lv"];
$tid=$_REQUEST["tid"];
$name=$_REQUEST["name"];
$linetype=$_REQUEST["line_type"];
require ("../../agents/include/traditional.$langx.inc.php");

$ip_addr = get_ip();
switch ($lv){
case 'A':
    $data=DBPREFIX.'web_system_data';
	$agents="Super='$name'";
	break;
case 'B':
    $Title=$Mem_Corprator;
    $data=DBPREFIX.'web_agents_data';
	$agents="Corprator='$name'";
	break;
case 'C':
    $Title=$Mem_World;
    $data=DBPREFIX.'web_agents_data';
	$agents="World='$name'";
	break;
case 'D':
    $Title=$Mem_Agents;
    $data=DBPREFIX.'web_agents_data';
	$agents="Agents='$name'";
	break;
case 'MEM':
    $Title=$Mem_Member;
    $data=DBPREFIX.'web_agents_data';
	$agents="UserName='$name'";
	break;
}
$loginfo='更改'.$Title.':'.$name.'正负水盘设定';

$username = $_SESSION['UserName'];

if ($lv!='MEM'){
    $mysql="update ".DBPREFIX."web_agents_data set LineType='$linetype' where ID='$tid' or $agents";
    mysqli_query($dbMasterLink,$mysql);
}
    $mysql="update ".DBPREFIX.MEMBERTABLE." set LineType='$linetype' where $agents";
    mysqli_query($dbMasterLink,$mysql);
	$logsql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$username',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
    mysqli_query($dbMasterLink,$logsql);
    echo "<Script Language=javascript>self.location='user_browse.php?uid=$uid&lv=$lv&langx=$langx';</script>";
	
		
?>
