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

//更新比赛分数账号UID

$mysql = "select Uid_ms,datasite_ms,Name_ms,Passwd_ms from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbMasterLink,$mysql);
$row = mysqli_fetch_assoc($result);

$site=$row['datasite_ms'];
$name=$row['Name_ms'];
$passwd=$row['Passwd_ms'];

/*简体获取url,uid*/
$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");
$curl->set_referrer("".$site."");

$login=array();
$login['username']=$name;
$login['passwd']=$passwd;
$login['langx']="zh-cn";

$html_date=$curl->send_post_data("".$site."/app/member/new_login.php",$login,"",10);

if (!$html_date){
	echo "比分账号登陆错误!\\请检查登录地址!!3秒后重新刷新!";
    echo "<meta http-equiv=\"refresh\" content=\"3\" />";
	exit;
}else{
	if(strstr($html_date,"newdomain")){
		preg_match("/action='([^']+)/si",$html_date,$url);
		preg_match("/<input type='hidden' name='uid' value='([^']+)/si",$html_date,$uid);
		$url=$url[1];
		$uid=$uid[1];
		$liveid=$liveid[1];
		$mysql="update ".DBPREFIX."web_system_data set datasite_ms='".$url."',Uid_ms='".$uid."'";
		mysqli_query($dbMasterLink,$mysql);
		echo '成功获取比分账号的URL: '.$url.'<br>';
		echo '成功获取比分账号的uid: '.$uid.'<br>';
		echo '成功获取比分账号的liveid: '.$liveid.'<br><br>';
	}else{
		preg_match("/top.uid = '([^']+)/si",$html_date,$uid);
		preg_match("/top.liveid = '([^']+)/si",$html_date,$liveid);
		$uid=explode("|",$html_date);
		
		if($uid[3]){
			$uid=$uid[3];
			$liveid=$liveid[1];
			$mysql="update ".DBPREFIX."web_system_data set Uid_ms='$uid'";
			mysqli_query($dbMasterLink,$mysql);
			echo '成功获取比分账号的uid: '.$uid.'<br>';
			echo '成功获取比分账号的liveid: '.$liveid.'<br><br>';
		}else {
			echo "比分账号登陆错误!\\请检查简体用户名和密码!!<br><br>";	
		}
	}
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>比分UID接收</title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<script> 
var limit="7200" 
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
		curtime=curmin+"秒后自动获取UID!" 
	else 
		curtime=cursec+"秒后自动获取UID!" 
		ShowTime.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 

window.onload=beginrefresh 
 
</script>
<body onLoad="TimeClose();">
<table width="150" height="100" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="150" height="100" align="center">
      <span id="ShowTime"></span><br><br>
      <input type=button name=button value="重新登陆" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>