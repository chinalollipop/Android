<?php
header("Content-Type: application/xml; charset=utf-8");
require ("../../include/config.inc.php");
require ("../../include/curl_http.php");

require_once("../../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$mysql = "select datasite_tw,Uid_tw from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$site=$row['datasite_tw'];
$uid =$row['Uid_tw'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/FT_browse/index.php?rtype=hr&uid=".$uid."&langx=zh-tw&mtype=3");
$html_data=$curl->fetch_url("".$site."/app/member/FT_browse/body_var.php?rtype=hr&uid=".$uid."&langx=zh-tw&mtype=3");

if (strstr($html_data,'<html>')){
	echo "繁體接水正常<br>";
	echo date("Y-m-d H:i:s");
}else{
	echo "1";
}
?>
