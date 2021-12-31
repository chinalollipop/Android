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

$uid=$_REQUEST['uid'];
$settime=$_REQUEST['settime'];
$site=$_REQUEST['sitename'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");

$curl->set_referrer("".$site."/app/member/browse/index.php?ptype=PD&gtype=FU&&uid=".$uid."&langx=big5");
$html_data=$curl->fetch_url("".$site."/app/member/browse/var.php?ptype=PD&rtype=&gtype=FU&pctype=&uid=".$uid."&langx=big5&ltype=3");

$thisHttp->getPage($filename);
$msg  = $thisHttp->getContent();
//echo $msg;
preg_match_all("/gd\[(.+?)\](.+?);/is",$msg,$matches);
//拆mid
preg_match_all("/gs =(.+?);/is",$msg,$matchesGS);
$messagesGS=$matchesGS[0][0];
$messagesGS=str_replace("gs = Array(","",$messagesGS);
$messagesGS=str_replace(");","",$messagesGS);
$datainfoGS=explode(",",$messagesGS);

$cou=sizeof($matches[0]);
for($i=0;$i<$cou;$i++){
	$messages=$matches[0][$i];
	$messages=str_replace("gd[$i] = Array(","",$messages);
	$messages=str_replace("'","",$messages);
	$messages=str_replace(");","",$messages);
	$datainfo=explode(",",$messages);
	/*********************/
	$mid=$datainfoGS[$i];//对应的mid
	/********************/
	$sql = "update match_stock set MB0TG0='$datainfo[6]',MB1TG0='$datainfo[7]',MB2TG0='$datainfo[8]',MB3TG0='$datainfo[9]',MB4TG0='$datainfo[10]',MB5TG0='$datainfo[11]',MB6TG0='$datainfo[12]',MB7TG0='$datainfo[13]',MB8TG0='$datainfo[14]',MB9TG0='$datainfo[15]',PD_Show=1 where MID='$mid'";
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
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" align="center">
      波膽數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
