<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
require ("./include/config.inc.php");

$uid=$_REQUEST['uid'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$mtype=$_REQUEST['mtype'];
$langx=$_REQUEST['langx'];
$chgURL_domain="http://".$_REQUEST['chgURL_domain'];
$chgURL_domain="http://www.hg3088_dh.lcn";
$ts=date("Y-m-d", $_REQUEST['ts']);
?>
<html>
<head>
<SCRIPT language="JavaScript">
function onLoads(){
var obj = document.getElementById('newdomain');
obj.submit();
}
</SCRIPT></head>
<body onload='onLoads();'>
<form id='newdomain' action='<?php echo $chgURL_domain?>' method='POST' target='_top' >
<input type='hidden' name='uid' value='<?php echo $uid?>'><input type='hidden' name='mtype' value='3'><input type='hidden' name='today_gmt' value='<?php echo $ts?>'></form>
</body>
</html>
