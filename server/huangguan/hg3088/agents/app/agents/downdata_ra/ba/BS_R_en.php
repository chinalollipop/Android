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
$curl->set_referrer("".$site."/app/member/BS_browse/index.php?rtype=r&uid=".$uid."&langx=en-us&mtype=3");
$thisHttp = new cHTTP();
$thisHttp->setReferer($base_url);
for($page_no=0;$page_no<8;$page_no++)
{
	$filename="".$site."/app/member/BS_browse/body_var.php?rtype=r&uid=$uid&langx=en-us&mtype=3";
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
	preg_match_all("/Array\((.+?)\);/is",$meg,$matches);
	$cou=sizeof($matches[0]);
	for($i=0;$i<$cou;$i++){
		$messages=$matches[0][$i];
		$messages=str_replace(");",")",$messages);
		$messages=str_replace("cha(9)","",$messages);
		$datainfo=eval("return $messages;");	
		$sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Team_en='$datainfo[5]',TG_Team_en='$datainfo[6]',M_League_en='$datainfo[2]' where MID=$datainfo[0]";
		mysqli_query($dbLink,$sql) or die ("操作失败1!");
	}
}
$abcd=explode("parent.msg='",$meg);
$msg_tw=explode("';",$abcd[1]);

$sql = "select msg_update from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$sql);
$row = mysql_fetch_array($result);
if ($row['msg_update']==1 and $msg_tw[0]!=''){
	$sql="update ".DBPREFIX."web_system_data set msg_member_en='$msg_tw[0]'";
	mysqli_query($dbMasterLink,$sql) or die ("公告更新操作失敗!");		
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
		curtime=curmin+"秒后自动获取本页最新数据！" 
	else 
		curtime=cursec+"秒后自动获取本页最新数据！" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 
window.onload=beginrefresh 

</script>
<table width="102" height="100" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="110" height="110" align="center">
         单式数据正在接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="英语 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
