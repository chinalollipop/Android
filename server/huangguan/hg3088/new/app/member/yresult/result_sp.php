<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/curl_http.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$langx='zh-cn';
$gid=$_REQUEST['gid'];
$gtype=$_REQUEST['gtype'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	setcookie('login_uid','');
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$langx=$_SESSION['Language'];
require ("../include/traditional.$langx.inc.php");

	$mysql = "select datasite,datasite_en,datasite_tw,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";
	$result = mysqli_query($dbLink,$mysql);
	$row = mysqli_fetch_assoc($result);
	switch($langx)	{
	case "zh-cn":
		$suid=$row['uid_tw'];
		break;
	case "zh-cn":
		$suid=$row['uid'];
		break;
	case "en-us":
		$suid=$row['uid_en'];
		break;
	case "th-tis":
		$suid=$row['uid_en'];
		break;
	}
	$site=$newdatabase;

	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$site."/app/member/FT_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	$html_data=$curl->fetch_url("".$site."/app/member/result/result_sp.php?gtype=$gtype&uid=$suid&langx=$langx&gid=$gid");
	//echo "".$site."/app/member/result/result.php?game_type=$gtype&uid=$suid&langx=$langx&list_date=$list_date";
	$html_data=str_replace($suid,$uid,$html_data);
	echo $html_data;
?>