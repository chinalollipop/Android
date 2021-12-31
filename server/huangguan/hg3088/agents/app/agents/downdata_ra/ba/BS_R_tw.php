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
$curl->set_referrer("http://bc080.com/app/member/browse/index.php?ptype=S&gtype=BS&&uid=".$uid."&langx=big5");
$thisHttp = new cHTTP();
$thisHttp->setReferer($base_url);
for($page_no=0;$page_no<8;$page_no++)
{
$filename="http://bc080.com/app/member/browse/var.php?ptype=S&rtype=&gtype=BS&pctype=&uid=".$uid."&langx=big5&page=".$page_no."&ltype=3";
echo $filename;
$thisHttp->getPage($filename);
$msg  = $thisHttp->getContent();

preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
echo $msg;
$cou=sizeof($matches[0]);

for($i=0;$i<$cou;$i++){
	$messages=$matches[0][$i];
	echo $messages;
	$messages=str_replace(");",")",$messages);
	$messages=str_replace("cha(9)","",$messages);
	$datainfo=eval("return $messages;");

    //$chs = new Chinese("UTF8","GB2312",trim($messages),$codeTablesDir); 
    //$MB_Team_tw = $chs->ConvertIT(); 
	//echo $MB_Team_tw;
	//$MB_Team_tw=explode("不€€抓不€€抓不€€抓",$MB_Team_tw);
    //echo $MB_Team_tw[0];
    //echo $MB_Team_tw[1];
   
	//$chs = new Chinese("UTF8","GB2312",trim($datainfo[5]),$codeTablesDir); 
   // $TG_Team_tw = $chs->ConvertIT(); 
	//$TG_Team_tw=explode("不€€抓不€€抓不€€抓",$TG_Team_tw);
	//echo $TG_Team_tw[0];
   // echo $TG_Team_tw[1];
   // echo $datainfo[10];
	//echo $datainfo[12];
	//echo $datainfo[15];
	//echo $datainfo[16];
	$mDate=explode('<BR>',strtoupper($datainfo[1]));
	$m_date=date('Y')."-".$mDate[0];
	$m_time=strtolower($mDate[1]);
	$hhmmstr=explode(":",$m_time);
	$hh=$hhmmstr[0];
	$ap=substr($m_time,strlen($m_time)-1,1);
	
	preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
	echo $msg;
	$cou=sizeof($matches[0]);
	
	for($i=0;$i<$cou;$i++){
		$messages=$matches[0][$i];
		echo $messages;
		$messages=str_replace(");",")",$messages);
		$messages=str_replace("cha(9)","",$messages);
		$datainfo=eval("return $messages;");
	
	    //$chs = new Chinese("UTF8","GB2312",trim($messages),$codeTablesDir); 
	    //$MB_Team_tw = $chs->ConvertIT(); 
		//echo $MB_Team_tw;
		//$MB_Team_tw=explode("不€€抓不€€抓不€€抓",$MB_Team_tw);
	    //echo $MB_Team_tw[0];
	    //echo $MB_Team_tw[1];
	   
		//$chs = new Chinese("UTF8","GB2312",trim($datainfo[5]),$codeTablesDir); 
	   // $TG_Team_tw = $chs->ConvertIT(); 
		//$TG_Team_tw=explode("不€€抓不€€抓不€€抓",$TG_Team_tw);
		//echo $TG_Team_tw[0];
	   // echo $TG_Team_tw[1];
	   // echo $datainfo[10];
		//echo $datainfo[12];
		//echo $datainfo[15];
		//echo $datainfo[16];
		$mDate=explode('<BR>',strtoupper($datainfo[1]));
		$m_date=date('Y')."-".$mDate[0];
		$m_time=strtolower($mDate[1]);
		$hhmmstr=explode(":",$m_time);
		$hh=$hhmmstr[0];
		$ap=substr($m_time,strlen($m_time)-1,1);
		
		if ($ap=='p' and $hh<>12){
			$hh+=12;
		}
		$timestamp = $m_date." ".$hh.":".substr($hhmmstr[1],0,strlen($hhmmstr[1])-1).":00";
		if (sizeof($mDate)>2){
			$m_Type=1;
			}
		else{
			$m_Type=0;
		}
		
		$checksql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID` ='$datainfo[38]'";
		$checkresult = mysqli_query($dbLink,$checksql);	
		$check=mysqli_num_rows($checkresult);
		if($check==0){
			$sql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,M_Start,M_Date,M_Time,MB_Team_tw,TG_Team_tw,M_League_tw,MB_MID,TG_MID,ShowType,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,TG_Dime_Rate,MB_Dime_Rate,MB_Win,TG_Win,M_Flat,M_Type,S_Show) VALUES 
				('$datainfo[38]','$timestamp','$m_date','$m_time','$datainfo[4]','$datainfo[5]','$datainfo[1]','$datainfo[2]','$datainfo[3]','$datainfo[7]','$datainfo[8]','$datainfo[9]','$datainfo[10]','$datainfo[11]','$datainfo[12]','$datainfo[13]','$datainfo[14]','$datainfo[15]','$datainfo[16]','$datainfo[17]','$m_Type','1')";
			mysqli_query($dbMasterLink,$sql) or die ("11");
		}else{
			$sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Team_tw='$datainfo[4]',TG_Team_tw='$datainfo[5]',M_Start='$timestamp',M_Date='$m_date',M_Time='$m_time',M_League_tw='$datainfo[1]',ShowType='$datainfo[7]',M_LetB='$datainfo[8]',MB_LetB_Rate='$datainfo[9]',TG_LetB_Rate='$datainfo[10]',MB_Dime='$datainfo[11]',TG_Dime='$datainfo[12]',TG_Dime_Rate='$datainfo[13]',MB_Dime_Rate='$datainfo[14]',MB_Win='$datainfo[15]',TG_Win='$datainfo[16]',M_Flat='$datainfo[17]',S_Show=1 where MID=$datainfo[38]";
			// echo $sql;
			mysqli_query($dbMasterLink,$sql) or die ("22!");
		}
	}
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
      單式數據正在接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
