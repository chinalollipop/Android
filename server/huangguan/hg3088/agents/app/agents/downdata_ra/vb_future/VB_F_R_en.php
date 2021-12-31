<?php
require ("../../include/config.inc.php");
require ("../../include/curl_http.php");

require_once("../../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$mysql = "select datasite_en,Uid_en,udp_fu_r from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$uid =$row['Uid_en'];
$site=$row['datasite_en'];
$settime=$row['udp_fu_r'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/VB_future/index.php?rtype=r&uid=".$uid."&langx=en-us&mtype=3");
$html_data=$curl->fetch_url("".$site."/app/member/VB_future/body_var.php?rtype=r&uid=".$uid."&langx=en-us&mtype=3");

$a = array(
"if(self == top)",
"<script>",
"</script>",
"\n\n"
);
$b = array(
"",
"",
"",
""
);
unset($matches);
unset($datainfo);
$msg = str_replace($a,$b,$html_data);
preg_match_all("/]=new Array\((.+?)\);/is",$msg,$matches);
$cou=sizeof($matches[0]);
for($i=0;$i<$cou;$i++){
	$messages=$matches[0][$i];
	$messages=str_replace("]=new Array(","",$messages);
	$messages=str_replace("'","",$messages);
	$messages=str_replace(");","",$messages);
	$datainfo=explode(",",$messages);	
	$sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Team_en='$datainfo[5]',TG_Team_en='$datainfo[6]',M_League_en='$datainfo[2]' where MID=$datainfo[0]";
	mysqli_query($dbMasterLink,$sql) or die ("操作失败!");
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
		curtime=curmin+"秒后自动获取!" 
	else 
		curtime=cursec+"秒后自动获取!" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 

window.onload=beginrefresh 

</script>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" align="center">
      单式数据接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="英语 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
