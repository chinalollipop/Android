<?php
set_time_limit(0);
require"../../../../include/conn_ft8.php";
require"../../../../include/function.php";
require ("../../../../include/curl_http.php");

require_once("../../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$uid=$_REQUEST['uid'];
$settime=$_REQUEST['settime'];
$site=$_REQUEST['sitename'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");

$curl->set_referrer("".$site."/app/member/browse_BS/loadgame_P1.php?langx=zh-tw&uid=".$uid);
$html_data=$curl->fetch_url("".$site."/app/member/browse_BS/reloadgame_P1.php?langx=zh-tw&uid=".$uid."&LegGame=ALL");


$a = array(
"if(self == top)",
"<script>",
"\n\n"
);
$b = array(
"",
"",
""
);
unset($matches);
unset($datainfo);
$msg = str_replace($a,$b,$html_data);
preg_match_all("/new Array\(\'(.+?)\);/is",$meg,$matches);
echo $meg;
$cou=sizeof($matches[0]);
for($i=0;$i<$cou;$i++){
	$messages=$matches[0][$i];
	$messages=str_replace("new Array(","",$messages);
	$messages=str_replace(")","",$messages);
	$messages=str_replace("'","",$messages);
	//转码
	$messages = iconv("big5","utf-8",$messages);
	
	$datainfo=explode(",",$messages);
	$sql = "update base_match set MB_P_Win='$datainfo[10]',TG_P_Win='$datainfo[11]',P_Show=1 where MID=$datainfo[0]";
	//echo $sql;
	mysqli_query($dbMasterLink,$sql) or die ("操作失敗!");	
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<body>
<script> 

var limit="<?php echo $settime?>" 
if (document.images){ 
	var parselimit=limit
} 
function beginrefresh(){ 
if (!document.images) 
	return 
if (parselimit==1) 
	window.location.reload() 
else{ 
	parselimit-=1 
	curmin=Math.floor(parselimit) 
	if (curmin!=0) 
		curtime=curmin+"秒後自動獲取!"
	else 
		curtime=cursec+"秒後自動獲取!"
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 
window.onload=beginrefresh 

</script>
<table width="135" height="100" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="110" height="110" align="center">
        標準過關數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
