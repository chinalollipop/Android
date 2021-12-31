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

$mysql = "select datasite_tw,Uid_tw,udp_bs_pd from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$uid =$row['Uid_tw'];
$site=$row['datasite_tw'];
$settime=$row['udp_bs_pd'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/BS_browse/index.php?rtype=pd&uid=".$uid."&langx=zh-tw&mtype=3");
$html_data=$curl->fetch_url("".$site."/app/member/BS_browse/body_var.php?rtype=pd&uid=".$uid."&langx=zh-tw&mtype=3");
//echo $html_data;exit;
$a = array(
"if(self == top)",
"<script>",
"</script>",
"]=new Array()",
"parent.GameFT=new Array();",
"\n\n",
"_.",
"g([",
"])"
);
$b = array(
"",
"",
"",
"",
"",
"",
"parent.",
"Array(",
")"
);
unset($matches);
unset($datainfo);
$msg = str_replace($a,$b,$html_data);
preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
//echo $msg;exit;
$cou=sizeof($matches[0]);
for($i=0;$i<$cou;$i++){
	$messages=$matches[0][$i];
	$messages=str_replace(");",")",$messages);
	$messages=str_replace("cha(9)","",$messages);
	$datainfo=eval("return $messages;");
	$icount=0;
	
	for($j=0;$j<sizeof($datainfo);$j++)		
	{

		if ($datainfo[$j]==0){
			$icount=$icount+1;
		}
	}

	if ($icount<31){
		//$sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB1TG0='$datainfo[8]',MB2TG0='$datainfo[9]',MB2TG1='$datainfo[10]',MB3TG0='$datainfo[11]',MB3TG1='$datainfo[12]',MB3TG2='$datainfo[13]',MB4TG0='$datainfo[14]',MB4TG1='$datainfo[15]',MB4TG2='$datainfo[16]',MB4TG3='$datainfo[17]',MB0TG0='$datainfo[18]',MB1TG1='$datainfo[19]',MB2TG2='$datainfo[20]',MB3TG3='$datainfo[21]',MB4TG4='$datainfo[22]',OVMB='$datainfo[23]',MB0TG1='$datainfo[24]',MB0TG2='$datainfo[25]',MB1TG2='$datainfo[26]',MB0TG3='$datainfo[27]',MB1TG3='$datainfo[28]',MB2TG3='$datainfo[29]',MB0TG4='$datainfo[30]',MB1TG4='$datainfo[31]',MB2TG4='$datainfo[32]',MB3TG4='$datainfo[33]',OVTG='$datainfo[34]', PD_Show=1 where MID='$datainfo[0]'";
		//mysqli_query($dbLink,$sql) or die ("操作失敗!!");
	}
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
      <input type=button name=button value="繁體 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
