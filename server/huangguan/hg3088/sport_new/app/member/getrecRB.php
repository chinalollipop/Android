<?php
require ("./include/config.inc.php");
require ("./include/curl_http.php");
$gtype=$_REQUEST['gtype'];
$uid=$_REQUEST['uid'];
$m_date=date('Y-m-d');

$mysql = "select datasite,uid from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_array($result);
$site=$newdatabase;
$suid=$row['uid'];
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$site."/app/member/FT_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	//http://hg1088.com/app/member/scroll_history.php?uid=14940627m6323264l22938231&langx=zh-cn
	$html_data=$curl->fetch_url("".$site."/app/member/getrecRB.php?gtype=$gtype&uid=$suid");
	//echo "".$site."/app/member/getrecRB.php?gtype=$gtype&uid=$suid";
//echo htmlentities($html_data);
	$html_data=str_replace("window.open('".$site."/tpl/logout_warn.html','_top')",'',$html_data);
	$html_data=str_replace("$site",'/',$html_data);
	//$html_data=$curl->fetch_url("http://www.hg3088.com/app/member/getrecRB.php?uid=46c023cam6687290l35341938&gtype=FT");
	//echo "".$site."/app/member/getrecRB.php?gtype=$gtype&uid=$suid";
	echo $html_data;exit;
?>
