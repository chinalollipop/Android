<?
require ("../../include/config.inc.php");
require ("../../include/curl_http.php");


require_once("../../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$mysql = "select datasite,Name,Passwd,datasite_tw,Name_tw,Passwd_tw,datasite_en,Name_en,Passwd_en from web_system_data where ID=1";
$result = mysql_query($mysql);
$row = mysql_fetch_array($result);

$site=$row['datasite'];
$name=$row['Name'];
$passwd=$row['Passwd'];

$site_tw=$row['datasite_tw'];
$name_tw=$row['Name_tw'];
$passwd_tw=$row['Passwd_tw'];

$site_en=$row['datasite_en'];
$name_en=$row['Name_en'];
$passwd_en=$row['Passwd_en'];
/*简体获取url,uid*/
$curl = &new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
//$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");
$curl->set_referrer("".$site."");

$login=array();
$login['username']=$name;
$login['passwd']=$passwd;
$login['langx']="zh-cn";
$login['uid']=$tempUID;
print_r ($login);
$str=file_get_contents($site);print htmlspecialchars($str);
//$html_date=$curl->send_post_data("".$site."/app/member/new_login.php",$login,"",10);
$html_date=$curl->send_post_data("http://66.133.87.55/app/member/new_login.php",$login,"",10);
print htmlspecialchars($html_date);
//echo $html_date;exit;
if (!$html_date){
	echo "简体登陆错误!\\请检查登录地址!!3秒后重新刷新!";
    echo "<meta http-equiv=\"refresh\" content=\"3\" />";
	exit;
}else{
	
	if(strstr($html_date,"newdomain")){
		preg_match("/action='([^']+)/si",$html_date,$url);
		preg_match("/<input type='hidden' name='uid' value='([^']+)/si",$html_date,$uid);
		$url=$url[1];
		$uid=$uid[1];
		$liveid=$liveid[1];
		$mysql="update web_system_data set datasite='".$url."',Uid='".$uid."',LiveId='".$liveid."'";
		mysql_query($mysql);
		echo '成功获取简体的URL: '.$url.'<br>';
		echo '成功获取简体的uid: '.$uid.'<br>';
		echo '成功获取简体的liveid: '.$liveid.'<br><br>';
	}else{
		preg_match("/top.uid = '([^']+)/si",$html_date,$uid);
		preg_match("/top.liveid = '([^']+)/si",$html_date,$liveid);
		$uid=explode("|",$html_date);
		if($uid[3]){
			$uid=$uid[3];
			$liveid=$liveid[1];
			$mysql="update web_system_data set Uid='$uid',LiveId='$liveid'";
			mysql_query($mysql);
			echo '成功获取简体的uid: '.$uid.'<br>';
			echo '成功获取简体的liveid: '.$liveid.'<br><br>';
		}else {
			echo "简体登陆错误!\\请检查简体用户名和密码!!<br><br>";	
		}
	}
}
/*繁体获取url,uid*/
$curl = &new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site_tw."");

$login=array();
$login['username']=$name_tw;
$login['passwd']=$passwd_tw;
$login['langx']="zh-tw";
$login['uid']=$tempUID;
$html_date=$curl->send_post_data("".$site_tw."/app/member/new_login.php",$login,"",10);

if (!$html_date){
	echo "繁體登陸錯誤!\\请检查登录地址!!3秒后重新刷新";
    echo "<meta http-equiv=\"refresh\" content=\"3\" />";
	exit;
}else{
	if(strstr($html_date,"newdomain")){
		preg_match("/action='([^']+)/si",$html_date,$url);
		preg_match("/<input type='hidden' name='uid' value='([^']+)/si",$html_date,$uid);
		$url=$url[1];
		$uid=$uid[1];
		$liveid=$liveid[1];
		$mysql="update web_system_data set datasite_tw='".$url."',Uid_tw='".$uid."',LiveId_tw='".$liveid."'";
		mysql_query($mysql);
		echo '成功獲取繁體的URL: '.$url.'<br>';
		echo '成功獲取繁體的uid: '.$uid.'<br>';
		echo '成功獲取繁體的liveid: '.$liveid.'<br><br>';
	}else{
		preg_match("/top.uid = '([^']+)/si",$html_date,$uid);
		preg_match("/top.liveid = '([^']+)/si",$html_date,$liveid);
		$uid=explode("|",$html_date);
		if($uid[3]){
			$uid=$uid[3];
			$liveid=$liveid[1];
			$mysql="update web_system_data set Uid_tw='$uid',LiveId_tw='$liveid'";
			mysql_query($mysql);
			echo '成功獲取繁體的uid: '.$uid.'<br>';
			echo '成功獲取繁體的liveid: '.$liveid.'<br><br>';
		}else {
			echo "繁體登陸錯誤!\\請檢查繁體用戶名和密碼!!<br><br>";	
		}
	}
}
/*英文获取url,uid*/
$curl = &new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site_en."");

$login=array();
$login['username']=$name_en;
$login['passwd']=$passwd_en;
$login['langx']="en-us";
$login['uid']=$tempUID;
$html_date=$curl->send_post_data("".$site_en."/app/member/new_login.php",$login,"",10);
//print htmlspecialchars($html_date);

if (!$html_date){
	echo "英文登录错误!\\请检查登录地址!!3秒后重新刷新!";
    echo "<meta http-equiv=\"refresh\" content=\"3\" />";
	exit;
}else{
	if(strstr($html_date,"newdomain")){
		preg_match("/action='([^']+)/si",$html_date,$url);
		preg_match("/<input type='hidden' name='uid' value='([^']+)/si",$html_date,$uid);
		$url=$url[1];
		$uid=$uid[1];
		$liveid=$liveid[1];
		$mysql="update web_system_data set datasite_en='".$url."',Uid_en='".$uid."',LiveId_en='".$liveid."'";
		mysql_query($mysql);
		echo '成功获取英文的URL: '.$url.'<br>';
		echo '成功获取英文的uid: '.$uid.'<br>';
		echo '成功获取英文的liveid: '.$liveid.'<br><br>';
	}else{
		preg_match("/top.uid = '([^']+)/si",$html_date,$uid);
		preg_match("/top.liveid = '([^']+)/si",$html_date,$liveid);
		$uid=explode("|",$html_date);
		if($uid[3]){
			$uid=$uid[3];
			$liveid=$liveid[1];
			$mysql="update web_system_data set Uid_en='$uid',LiveId_en='$liveid'";
			mysql_query($mysql);
			echo '成功获取英文的uid: '.$uid.'<br>';
			echo '成功获取英文的liveid: '.$liveid.'<br><br>';
		}else {
			echo "英文登录错误!\\请检查英文的用户名和密码!!<br><br>";	
		}
	}
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>UID接收</title>
<link href="/style/agents/control_down.css" rel="stylesheet" type="text/css">
</head>
<!--<script language="javascript">
var cTime=20;//这个变量是倒计时的秒数设置为10就是10秒
function TimeClose(){
     window.setTimeout('TimeClose()',1000);//让程序每秒重复执行当前函数。
     if(cTime<=0)//判断秒数如果为0
       CloseWindow_Click();//执行关闭网页的操作
     this.ShowTime.innerHTML="倒计时"+cTime+"秒后关闭当前窗口";//显示倒计时时间
     cTime--;//减少秒数
}
function CloseWindow_Click(){
     window.open('','_parent','');//关闭当前窗口
     window.close();
}
</script>-->
<script> 
<!-- 
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
file://--> 
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