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

$mysql = "select passuid,passuid_tw,passuid_en from web_system";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$uid =$row['passuid_tw'];

$settime=$_REQUEST['settime'];
$site=$_REQUEST['sitename'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/browse_BS/loadgame_RB.php?langx=zh-tw&uid=".$uid);
$thisHttp = new cHTTP();
$thisHttp->setReferer($base_url);
for($page_no=0;$page_no<8;$page_no++)
{
$filename="".$site."/app/member/browse_BS/reloadgame_RB.php?langx=zh-tw&uid=".$uid."&LegGame=ALL&page_no=".$page_no;

preg_match_all("/new Array\(\'(.+?)\);/is",$meg,$matches);
$cou=sizeof($matches[0]);
for($i=0;$i<$cou;$i++){
	$messages=$matches[0][$i];
	echo $messages;
	$messages=str_replace("new Array(","",$messages);
	$messages=str_replace(")","",$messages);
	$messages=str_replace("'","",$messages);
	//转码
	$messages = iconv("big5","utf-8",$messages);
	$datainfo=explode(",",$messages);
	
	$m_date=date("m-d",strtotime($datainfo[1]));
	$m_time=date("h:iA",strtotime($datainfo[1]));
	$m_time=str_replace("M","",$m_time);
	
	$checksql = "select MID from base_match where `MID` ='$datainfo[0]'";
	$checkresult = mysqli_query($dbLink,$checksql);	
	$check=mysqli_num_rows($checkresult);
	if($check==0){
		$sql = "INSERT INTO base_match(MID,M_Date,M_Time,MB_Team_tw,TG_Team_tw,M_League_tw,MB_MID,TG_MID,ShowType) VALUES 
			('$datainfo[0]','$m_date','$m_time','$datainfo[4]','$datainfo[5]','$datainfo[2]','$datainfo[3]','$datainfo[4]','$datainfo[6]')";
		mysqli_query($dbMasterLink,$sql) or die ("操作失败!");
	}else{
		$sql = "update base_match set MB_Team_tw='$datainfo[4]',TG_Team_tw='$datainfo[5]',M_League_tw='$datainfo[2]',ShowType='$datainfo[6]',Re_Show=1 where MID=$datainfo[0]";
		mysqli_query($dbMasterLink,$sql) or die ("操作失败1!");
	}
}
}	
$sql="update base_match set RE_Show=0 where RE_Show=1 and locate(MID,'$gmid')<1";
mysqli_query($dbMasterLink,$sql) or die ("操作失敗!");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="/style/agents/style.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
}
-->
</style>
</head>

<body bgcolor="#AACCCC">
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
<table width="102" height="100" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="110" height="110" align="center">
        走地數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
