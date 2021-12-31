<?php
/*
 *  这个文件没有用
 * */
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
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");

$curl->set_referrer("".$site."/app/member/browse_FS/loadgame_R.php?uid=".$uid."&langx=zh-tw&mtype=3");
$html_data=$curl->fetch_url("".$site."/app/member/browse_FS/reloadgame_R.php?uid=".$uid."&langx=zh-twchoice=ALL&LegGame=&pages=1&records=40&FStype=&area_id=&item_id=&rtype=fs");

//echo $html_data;exit;
$a = array(
"if(self == top)",
"<script>",
"</script>",
"]=new Array()",
"parent.GameBU=new Array();",
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
	$messages=iconv("BIG5","UTF-8",$messages);
	$datainfo=eval("return $messages;");
	$m_date=date("Y-m-d",strtotime($datainfo[1]));
	$m_start=$datainfo[1];
	$Time=date("H:i",strtotime($datainfo[1]));
	if($Time=='12:00' or $Time=='00:01'){
	  $m_time=$Time.'a';
	}else{
	  $m_time=$Time.'p';
	}
	$ntype = '';
    $ftype = '';
	$team = '';
    $rate = '';
    $num = $datainfo[5];
    for ($s=0; $s<$num; ++$s){
         $game_num = $s * 4 + 4;		 
         $ntype .= $datainfo[$game_num + 2].",";
         $ftype .= $datainfo[$game_num + 3].",";
		 $team = $team . $datainfo[$game_num + 4].",";
         $rate .= $datainfo[$game_num + 5].",";
	}
	$gtype=$datainfo[$game_num + 6];
	$checksql = "select MID from ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." where `MID` =$datainfo[0]";
	$checkresult = mysqli_query($dbLink,$checksql);	
	$check=mysqli_num_rows($checkresult);
	if ($check==0){
		$sql = "INSERT INTO ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."(MID,M_Start,M_Date,M_Time,M_League_tw,MB_Team_tw,M_Item_tw,Ytype,Num,Ntype,Ftype,M_Rate,Gtype,CS_Show) VALUES ('$datainfo[0]','$m_start','$m_date','$m_time','$datainfo[2]','$team','$datainfo[3]','$datainfo[4]','$num','$ntype','$ftype','$rate','$gtype','1')";
		mysqli_query($dbMasterLink,$sql) or die ("操作失敗!");
	}else{	
		$sql="update ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." set MB_Team_tw='$team',M_Date='$m_date',M_Time='$m_time',M_Start='$m_start',M_League_tw='$datainfo[2]',M_Item_tw='$datainfo[3]',Ytype='$datainfo[4]',Num='$num',Ntype='$ntype',Ftype='$ftype',M_Rate='$rate',Gtype='$gtype',CS_Show=1 where MID='".$datainfo[0]."'";
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
     冠軍數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>