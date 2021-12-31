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

$mysql = "select datasite_tw,Uid_tw,udp_vb_tw from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$uid =$row['Uid_tw'];
$site=$row['datasite_tw'];
$settime=$row['udp_vb_tw'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/VB_browse/index.php?rtype=re&uid=".$uid."&langx=zh-tw&mtype=3");
$html_data=$curl->fetch_url("".$site."/app/member/VB_browse/body_var.php?rtype=re&uid=".$uid."&langx=zh-tw&mtype=3");
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
	
	$date=explode('<br>',$datainfo[34]);
	$m_date=date('Y')."-".$date[0];
	$m_time=$date[1];

	$checksql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID` ='$datainfo[0]'";
	$checkresult = mysqli_query($dbLink,$checksql);	
	$check=mysqli_num_rows($checkresult);
	if($check==0){
		$sql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,Type,M_Date,M_Time,MB_Team_tw,TG_Team_tw,M_League_tw,MB_MID,TG_MID,ShowTypeRB,RB_Show) VALUES ('$datainfo[0]','VB','$m_date','$m_time','$datainfo[5]','$datainfo[6]','$datainfo[2]','$datainfo[3]','$datainfo[4]','$datainfo[7]','1')";
		mysqli_query($dbMasterLink,$sql) or die ("操作失敗!");
	}else{
		$sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set Type='VB',M_Date='$m_date',M_Time='$m_time',MB_Team_tw='$datainfo[5]',TG_Team_tw='$datainfo[6]',M_League_tw='$datainfo[2]',ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',TG_Dime_Rate='$datainfo[13]',MB_Dime_Rate_RB='$datainfo[14]',MB_Ball='$datainfo[18]',TG_Ball='$datainfo[19]',Eventid='$datainfo[31]',Hot='$datainfo[32]',Play='$datainfo[33]',RB_Show=1,S_Show=0 where MID='$datainfo[0]'";
		mysqli_query($dbMasterLink,$sql) or die ("操作失敗!!");
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
        走地數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
