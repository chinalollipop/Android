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

$mysql = "select uid,uid_tw,uid_en from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$uid =$row['uid_tw'];

$settime=$_REQUEST['settime'];
$site=$_REQUEST['sitename'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/BS_browse/index.php?rtype=re&uid=".$uid."&langx=zh-tw&mtype=3");
$thisHttp = new cHTTP();
$thisHttp->setReferer($base_url);
for($page_no=0;$page_no<8;$page_no++)
{
	$filename="".$site."/app/member/BS_browse/body_var.php?rtype=re&uid=".$uid."&langx=zh-tw&mtype=3";
	preg_match_all("/Array\((.+?)\);/is",$meg,$matches);
	$gmid='';
	$cou=sizeof($matches[0]);
	for($i=0;$i<$cou;$i++){
	$messages=$matches[0][$i];
		$messages=str_replace(");","",$messages);
		$messages=str_replace("cha(9)","",$messages);
		$messages=str_replace("'","",$messages);
		$messages=str_replace("Array(","",$messages);
		$datainfo=explode(",",$messages);
		
		$mDate=explode('<BR>',strtoupper($datainfo[1]));
		$m_date=date("m-d");
		$m_time=strtolower($mDate[1]);
		$hhmmstr=explode(":",$m_time);
		$hh=$hhmmstr[0];
		$ap=substr($m_time,strlen($m_time)-1,1);
		
		if ($ap=='p' and $hh<>12){
			$hh+=12;
		}
		$timestamp = date('Y')."-".$m_date." ".$hh.":".substr($hhmmstr[1],0,strlen($hhmmstr[1])-1).":00";
		
		if (sizeof($mDate)>2){
			$m_Type=1;
		}else{
			$m_Type=0;
		}
		if ($gmid==''){
			$gmid=$datainfo[0];
		}else{
			$gmid=$gmid.','.$datainfo[0];
		}
		$checksql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID` ='$datainfo[0]'";
		$checkresult = mysqli_query($dbLink,$checksql);	
		$check=mysqli_num_rows($checkresult);
		if($check==0){
			$sql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,M_Date,M_Time,MB_Team_tw,TG_Team_tw,M_League_tw,MB_MID,TG_MID,ShowType) VALUES 
				('$datainfo[0]','$m_date','$m_time','$datainfo[5]','$datainfo[6]','$datainfo[2]','$datainfo[3]','$datainfo[4]','$datainfo[7]')";
			mysqli_query($dbMasterLink,$sql) or die ("操作失敗!");
		}else{
			$sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Team_tw='$datainfo[5]',TG_Team_tw='$datainfo[6]',M_League_tw='$datainfo[2]',ShowType='$datainfo[7]',Re_Show=1 where MID=$datainfo[0]";
			mysqli_query($dbMasterLink,$sql) or die ("操作失敗!");
		}

	}
}	
$sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set RE_Show=0 where RE_Show=1 and locate(MID,'$gmid')<1";

mysqli_query($dbMasterLink,$sql) or die ("操作失敗!");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title></title>
<link href="/style/agents/style.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
}
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
		curtime=curmin+"秒後自動獲取本頁最新數據!" 
	else 
		curtime=cursec+"秒後自動獲取本頁最新數據!" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 
window.onload=beginrefresh 

</script>
<table width="102" height="100" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="110" height="110" align="center">
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
