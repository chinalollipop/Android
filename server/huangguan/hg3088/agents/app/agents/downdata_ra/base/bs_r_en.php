<?php
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

$curl->set_referrer("".$site."/app/member/browse_BS/loadgame_R.php?langx=en-us&uid=".$uid."");
$html_data=$curl->fetch_url("".$site."/app/member/browse_BS/reloadgame_R.php?langx=en-us&uid=".$uid."&LegGame=ALL&page_no=".$page_no);

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
echo $msg;
preg_match_all("/new Array\(\'(.+?)\);/is",$msg,$matches);
$cou=sizeof($matches[0]);
for($i=0;$i<$cou;$i++){
	$messages=$matches[0][$i];
	$messages=str_replace("new Array(","",$messages);
	$messages=str_replace(")","",$messages);
	$messages=str_replace("'","",$messages);
	$datainfo=explode(",",$messages);
	$sql = "update base_match set mb_team_en='$datainfo[4]--$datainfo[21]',tg_team_en='$datainfo[5]--$datainfo[22]',m_league_en='$datainfo[2]' where MID=$datainfo[0]";
	//echo $sql;
	mysqli_query($dbMasterLink,$sql) or die ("操作失败!");
}

$abcd=explode("parent.msg='",$meg);
$msg_tw=explode("';",$abcd[1]);

$sql = "select msg_update from web_system";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
if ($row['msg_update']==1 and $msg_tw[0]!=''){
	$sql="update web_system set msg_member_en='$msg_tw[0]'";
	mysqli_query($dbMasterLink,$sql) or die ("操作失败!");		
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
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
<table width="102" height="100" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="110" height="110" align="center">
	      单式数据接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="英语 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>