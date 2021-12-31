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

$uid=$_REQUEST['uid'];
$settime=$_REQUEST['settime'];
$site=$_REQUEST['sitename'];

$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");

$curl->set_referrer("".$site."/app/member/browse_BS/loadgame_P2.php?langx=zh-tw&uid=".$uid) ;
$html_data=$curl->fetch_url("".$site."/app/member/browse_BS/reloadgame_P2.php?langx=zh-tw&uid=".$uid."&LegGame=ALL");

$a = array(
"if(self == top)",
"<script>",
"\n\n"
);
$b = array(
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
	//转码
	$messages = iconv("big5","utf-8",$messages);
	$datainfo=explode(",",$messages);
	
	
	$mb_pr_dime=str_replace('15+100','O14.5',$datainfo[13]);
	$mb_pr_dime=str_replace('15+50','O14.5/15',$mb_pr_dime);
	$mb_pr_dime=str_replace('15+25','015+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('15+0','O15',$mb_pr_dime);
	$mb_pr_dime=str_replace('15-50','O15/15.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('14+100','O13.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('14+50','O13.5/14',$mb_pr_dime);
	$mb_pr_dime=str_replace('14+25','O14+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('14+0','O14',$mb_pr_dime);
	$mb_pr_dime=str_replace('14-50','O14/14.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('13+100','O12.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('13+50','O12.5/13',$mb_pr_dime);
	$mb_pr_dime=str_replace('13+25','O13+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('13+0','O13',$mb_pr_dime);
	$mb_pr_dime=str_replace('13-50','O13/13.5',$mb_pr_dime);
		
	$mb_pr_dime=str_replace('12+100','O11.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('12+50','O11.5/12',$mb_pr_dime);
	$mb_pr_dime=str_replace('12+25','O12+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('12+0','O12',$mb_pr_dime);
	$mb_pr_dime=str_replace('12-50','O12/12.5',$mb_pr_dime);
		
	$mb_pr_dime=str_replace('11+100','O10.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('11+50','O10.5/11',$mb_pr_dime);
	$mb_pr_dime=str_replace('11+25','O11+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('11+0','O11',$mb_pr_dime);
	$mb_pr_dime=str_replace('11-50','O11/11.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('10+100','O9.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('10+50','O9.5/10',$mb_pr_dime);
	$mb_pr_dime=str_replace('10+25','O10+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('10+0','O10',$mb_pr_dime);
	$mb_pr_dime=str_replace('10-50','O10/10.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('9+100','O8.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('9+50','O8.8/9',$mb_pr_dime);
	$mb_pr_dime=str_replace('9+25','O9+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('9+0','O9',$mb_pr_dime);
	$mb_pr_dime=str_replace('9-50','O9/9.5',$mb_pr_dime);
	
    $mb_pr_dime=str_replace('8+100','O7.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('8+50','O7.5/8',$mb_pr_dime);
	$mb_pr_dime=str_replace('8+25','O8+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('8+0','O8',$mb_pr_dime);
	$mb_pr_dime=str_replace('8-50','O8/8.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('7+100','O6.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('7+50','O6.5/7',$mb_pr_dime);
	$mb_pr_dime=str_replace('7+25','O7+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('7+0','O7',$mb_pr_dime);
	$mb_pr_dime=str_replace('7-50','O7/7.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('6+100','O5.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('6+50','O5.5/6',$mb_pr_dime);
	$mb_pr_dime=str_replace('6+25','O6+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('6+0','O6',$mb_pr_dime);
	$mb_pr_dime=str_replace('6-50','O6/6.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('5+100','O4.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('5+50','O4.5/5',$mb_pr_dime);
	$mb_pr_dime=str_replace('5+25','O5+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('5+0','O5',$mb_pr_dime);
	$mb_pr_dime=str_replace('5-50','O5/5.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('4+100','O3.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('4+50','O3.5/4',$mb_pr_dime);
	$mb_pr_dime=str_replace('4+25','O4+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('4+0','O4',$mb_pr_dime);
	$mb_pr_dime=str_replace('4-50','O4/4.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('3+100','O2.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('3+50','O2.5/3',$mb_pr_dime);
	$mb_pr_dime=str_replace('3+25','O3+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('3+0','O3',$mb_pr_dime);
	$mb_pr_dime=str_replace('3-50','O3/3.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('2+100','O1.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('2+50','O1.5/2',$mb_pr_dime);
	$mb_pr_dime=str_replace('2+25','O2+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('2+0','O2',$mb_pr_dime);
	$mb_pr_dime=str_replace('2-50','O2/2.5',$mb_pr_dime);
	
	$mb_pr_dime=str_replace('1+100','O0.5',$mb_pr_dime);
	$mb_pr_dime=str_replace('1+50','O0.5/1',$mb_pr_dime);
	$mb_pr_dime=str_replace('1+25','O1+25',$mb_pr_dime);
	$mb_pr_dime=str_replace('1+0','O1',$mb_pr_dime);
	$mb_pr_dime=str_replace('1-50','O1/1.5',$mb_pr_dime);

	
	$tg_pr_dime=str_replace('15+100','U14.5',$datainfo[13]);
	$tg_pr_dime=str_replace('15+50','U14.5/15',$tg_pr_dime);
	$tg_pr_dime=str_replace('15+25','U15-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('15+0','U15',$tg_pr_dime);
	$tg_pr_dime=str_replace('15-50','U15/15.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('14+100','U13.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('14+50','U13.5/14',$tg_pr_dime);
	$tg_pr_dime=str_replace('14+25','U14-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('14+0','U14',$tg_pr_dime);
	$tg_pr_dime=str_replace('14-50','U14/14.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('13+100','U12.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('13+50','U12.5/13',$tg_pr_dime);
	$tg_pr_dime=str_replace('13+25','U13-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('13+0','U13',$tg_pr_dime);
	$tg_pr_dime=str_replace('13-50','U13/13.5',$tg_pr_dime);
		
	$tg_pr_dime=str_replace('12+100','U11.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('12+50','U11.5/12',$tg_pr_dime);
	$tg_pr_dime=str_replace('12+25','U12-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('12+0','U12',$tg_pr_dime);
	$tg_pr_dime=str_replace('12-50','U12/12.5',$tg_pr_dime);
		
	$tg_pr_dime=str_replace('11+100','U10.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('11+50','U10.5/11',$tg_pr_dime);
	$tg_pr_dime=str_replace('11+25','U11-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('11+0','U11',$tg_pr_dime);
	$tg_pr_dime=str_replace('11-50','U11/11.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('10+100','U9.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('10+50','U9.5/10',$tg_pr_dime);
	$tg_pr_dime=str_replace('10+25','U10-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('10+0','U10',$tg_pr_dime);
	$tg_pr_dime=str_replace('10-50','U10/10.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('9+100','U8.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('9+50','U8.8/9',$tg_pr_dime);
	$tg_pr_dime=str_replace('9+25','U9-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('9+0','U9',$tg_pr_dime);
	$tg_pr_dime=str_replace('9-50','U9/9.5',$tg_pr_dime);
	
    $tg_pr_dime=str_replace('8+100','U7.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('8+50','U7.5/8',$tg_pr_dime);
	$tg_pr_dime=str_replace('8+25','U8-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('8+0','U8',$tg_pr_dime);
	$tg_pr_dime=str_replace('8-50','U8/8.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('7+100','U6.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('7+50','U6.5/7',$tg_pr_dime);
	$tg_pr_dime=str_replace('7+25','U7-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('7+0','U7',$tg_pr_dime);
	$tg_pr_dime=str_replace('7-50','U7/7.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('6+100','U5.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('6+50','U5.5/6',$tg_pr_dime);
	$tg_pr_dime=str_replace('6+25','U6-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('6+0','U6',$tg_pr_dime);
	$tg_pr_dime=str_replace('6-50','U6/6.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('5+100','U4.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('5+50','U4.5/5',$tg_pr_dime);
	$tg_pr_dime=str_replace('5+25','U5-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('5+0','U5',$tg_pr_dime);
	$tg_pr_dime=str_replace('5-50','U5/5.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('4+100','U3.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('4+50','U3.5/4',$tg_pr_dime);
	$tg_pr_dime=str_replace('4+25','U4-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('4+0','U4',$tg_pr_dime);
	$tg_pr_dime=str_replace('4-50','U4/4.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('3+100','U2.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('3+50','U2.5/3',$tg_pr_dime);
	$tg_pr_dime=str_replace('3+25','U3-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('3+0','U3',$tg_pr_dime);
	$tg_pr_dime=str_replace('3-50','U3/3.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('2+100','U1.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('2+50','U1.5/2',$tg_pr_dime);
	$tg_pr_dime=str_replace('2+25','U2-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('2+0','U2',$tg_pr_dime);
	$tg_pr_dime=str_replace('2-50','U2/2.5',$tg_pr_dime);
	
	$tg_pr_dime=str_replace('1+100','U0.5',$tg_pr_dime);
	$tg_pr_dime=str_replace('1+50','U0.5/1',$tg_pr_dime);
	$tg_pr_dime=str_replace('1+25','U1-25',$tg_pr_dime);
	$tg_pr_dime=str_replace('1+0','U1',$tg_pr_dime);
	$tg_pr_dime=str_replace('1-50','U1/1.5',$tg_pr_dime);
	
	$icount=0;
	for($j=0;$j<sizeof($datainfo);$j++)		
	{
		if ($datainfo[$j]==''){
			$icount=$icount+1;
		}
	}
	if ($icount<5){
		$MID=$datainfo[0];
		$sql = "update base_match set ShowTypeP='$datainfo[6]',M_PR_LetB='$datainfo[10]',MB_PR_LetB='$datainfo[11]',TG_PR_LetB='$datainfo[12]',MB_PR_Dime='$mb_pr_dime',TG_PR_Dime='$tg_pr_dime',MB_PR_Dime_Rate='$datainfo[14]',TG_PR_Dime_Rate='$datainfo[15]',PR_Show=1 where MID=$datainfo[0]";
		//echo $sql;
		mysqli_query($dbMasterLink,$sql) or die ("操作失敗!");		
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
<table width="135" height="100" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="110" height="110" align="center">
      讓球過關接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體<?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>