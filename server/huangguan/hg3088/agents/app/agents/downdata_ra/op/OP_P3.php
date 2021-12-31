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


$mysql = "select datasite,Uid,udp_op_pr from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$uid =$row['Uid'];
$site=$row['datasite'];
$settime=$row['udp_op_pr'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/OP_browse/index.php?rtype=p3&uid=".$uid."&langx=zh-cn&mtype=3");
$html_data=$curl->fetch_url("".$site."/app/member/OP_browse/body_var.php?rtype=p3&uid=".$uid."&langx=zh-cn&mtype=3");
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
		if ($datainfo[$j]==''){
			$icount=$icount+1;
		}
	}
	if ($icount<5){
		$MID=$datainfo[0];		
		$sql = "update`".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ShowTypeP='$datainfo[7]',M_P_LetB='$datainfo[8]',MB_P_LetB_Rate='$datainfo[9]',TG_P_LetB_Rate='$datainfo[10]',MB_P_Dime='$datainfo[11]',TG_P_Dime='$datainfo[12]',MB_P_Dime_Rate='$datainfo[13]',TG_P_Dime_Rate='$datainfo[14]',S_P_Single_Rate='$datainfo[15]',S_P_Double_Rate='$datainfo[16]',MB_P_Win_Rate='$datainfo[17]',TG_P_Win_Rate='$datainfo[18]',M_P_Flat_Rate='$datainfo[19]',ShowTypeHP='$datainfo[61]',M_P_LetB_H='$datainfo[62]',MB_P_LetB_Rate_H='$datainfo[63]',TG_P_LetB_Rate_H='$datainfo[64]',MB_P_Dime_H='$datainfo[65]',TG_P_Dime_H='$datainfo[66]',MB_P_Dime_Rate_H='$datainfo[68]',TG_P_Dime_Rate_H='$datainfo[67]',MB_P_Win_Rate_H='$datainfo[96]',TG_P_Win_Rate_H='$datainfo[97]',M_P_Flat_Rate_H='$datainfo[98]',P3_Show=1 where MID='$datainfo[0]'";
		mysqli_query($dbMasterLink,$sql) or die ("操作失败!");		
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
    综合过关数据接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="刷新 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
