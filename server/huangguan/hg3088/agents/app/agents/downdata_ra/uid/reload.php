<?php
require ("../../include/config.inc.php");
require_once("../../../../../common/sportCenterData.php");
require ("../../include/curl_http.php");

require_once("../../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$datetime = date('y-m-d H:i:s',time());
/*
 *刷水扩展账号刷新
 * */

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");

$sqlEx = "select ID,Datasite,Name,Passwd,Type from ".DATAHGPREFIX."web_getdata_account_expand where `Type`!='ms'";
$result = mysqli_query($dbCenterSlaveDbLink,$sqlEx);
while($resultFetch = mysqli_fetch_assoc($result)){
	//登录获取UID
	$login=array();
	$urlCur = $resultFetch['Datasite'];
	$login['username']=$resultFetch['Name'];
	$login['passwd']=$resultFetch['Passwd'];
	$login['langx']=$resultFetch['Type'];
	
	$curl->set_referrer("".$login['Datasite']."");
	$html_date=$curl->send_post_data("".$urlCur."/app/member/new_login.php",$login,"",10);
		
	if(strstr($html_date,"newdomain")){
		preg_match("/action='([^']+)/si",$html_date,$url);
		preg_match("/<input type='hidden' name='uid' value='([^']+)/si",$html_date,$uid);
		$url=$url[1];
		$uid=$uid[1];
		$liveid=$liveid[1];
	}else{
		preg_match("/top.uid = '([^']+)/si",$html_date,$uid);
		preg_match("/top.liveid = '([^']+)/si",$html_date,$liveid);
		$uid=explode("|",$html_date);
		if($uid[3]){
			$uid=$uid[3];
			$liveid=$liveid[1];
		}
	}
	
	$datetime = date('y-m-d H:i:s',time());
	if($uid && !is_array($uid)){
		$sql1 = "update ".DATAHGPREFIX."web_getdata_account_expand set `datetime`='".$datetime."' ,Uid='".$uid."',`status`=0 where ID=".$resultFetch['ID'];
		$res1 = mysqli_query($dbCenterMasterDbLink,$sql1);
		if($res1){
			echo "账号:".$resultFetch['Name']."更新UID成功：".$uid."<br/><br/>";
		}else{
			echo "账号:".$resultFetch['Name']."更新UID失败<br/><br/>";
		}
	}else{
		$sql1 = "update ".DATAHGPREFIX."web_getdata_account_expand set `datetime`='".$datetime."' ,`status`=1 where ID=".$resultFetch['ID'];	//未获取UID的账号状态置为1
		$res1 = mysqli_query($dbCenterMasterDbLink,$sql1);
		echo "账号:".$resultFetch['Name']."更新UID失败<br/><br/>";
	}	
}

/*简体接比分*/

$mysql = "select datasite_ms,Name_ms,Passwd_ms from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);

$site_ms=$row['datasite_ms'];
$name_ms=$row['Name_ms'];
$passwd_ms=$row['Passwd_ms'];

$loginMs=array();
$loginMs['username']=$name_ms;
$loginMs['passwd']=$passwd_ms;
$loginMs['langx']="zh-cn";

$curl->set_referrer("".$site."");
$html_date=$curl->send_post_data("".$site_ms."/app/member/new_login.php",$loginMs,"",10);

if (!$html_date){
    echo "比分账号登陆错误!\\请检查登录地址!!";
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
        if(strlen($liveid)>0) echo '成功获取比分账号的liveid: '.$liveid.'<br><br>';
        
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
            if(strlen($liveid)>0) echo '成功获取比分账号的liveid: '.$liveid.'<br><br>';
        }else {
            echo "比分账号登陆错误!\\请检查简体用户名和密码!!<br><br>";
        }
    }
}

/*
 * 登录失败用户切换账号
 * */
function changeAccount($dbLink,$dbMasterLink,$type,$name,$url){
	echo '登录失败正在切换账号……<br/>';
	$sql = "select ID,Name,Passwd from ".DATAHGPREFIX."web_getdata_account_expand where Type=\"$type\" and status=0";
	$result = mysqli_query($dbCenterSlaveDbLink,$sql);
	while($resultFetch = mysqli_fetch_assoc($result)){
		
		//登录获取UID
		$curl = new Curl_HTTP_Client();
		$curl->store_cookies("cookies.txt");
		$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");
		$login=array();
		$login['username']=$resultFetch['Name'];
		$login['passwd']=$resultFetch['Passwd'];
		$login['langx']=$type;
		$html_date=$curl->send_post_data("".$url."/app/member/new_login.php",$login,"",10);
		
		if(strstr($html_date,"newdomain")){
			preg_match("/action='([^']+)/si",$html_date,$url);
			preg_match("/<input type='hidden' name='uid' value='([^']+)/si",$html_date,$uid);
			$url=$url[1];
			$uid=$uid[1];
			$liveid=$liveid[1];
		}else{
			preg_match("/top.uid = '([^']+)/si",$html_date,$uid);
			preg_match("/top.liveid = '([^']+)/si",$html_date,$liveid);
			$uid=explode("|",$html_date);
			if($uid[3]){
				$uid=$uid[3];
				$liveid=$liveid[1];
			}
		}
		
		if($uid){
			//mysqli_query($dbMasterLink,'START TRANSACTION');
			if($type=="zh-tw"){//繁体
				$sql = "update ".DBPREFIX."web_system_data set Name_tw='".$resultFetch['Name']."',Passwd_tw='".$resultFetch['Passwd']."',Uid_tw='".$uid."',LiveId_tw='".$liveid."'where ID=1";	
			}elseif($type=="zh-cn"){//中文
				$sql = "update ".DBPREFIX."web_system_data set Name='".$resultFetch['Name']."',Passwd='".$resultFetch['Passwd']."',Uid='".$uid."',LiveId='".$liveid."'where ID=1";
			}elseif($type=="en-us"){//英文
				$sql = "update ".DBPREFIX."web_system_data set Name_en='".$resultFetch['Name']."',Passwd_en='".$resultFetch['Passwd']."',Uid_en='".$uid."',LiveId_en='".$liveid."' where ID=1";
			}
			
			$res = mysqli_query($dbMasterLink,$sql);
			if($res){
				$sql1 = "update ".DATAHGPREFIX."web_getdata_account_expand set `status`=1 ,Uid='".$uid."' where ID=".$resultFetch['ID'];
				$res1 = mysqli_query($dbCenterMasterDbLink,$sql1);
				
				if($res1){
					echo '账号切换成功！<br/><br/>';
					break;
				}
			}
			//mysqli_query($dbMasterLink,'COMMIT');
			//mysqli_query($dbMasterLink,'ROLLBACK');
		}
		echo '账号切换失败,请重置账号或添加新账号<br/><br/>';
	}
}


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>UID接收</title>
    <link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<script>
	var limit="72000" 
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
			curtime=curmin+"秒后自动更新UID" 
		else 
			curtime=cursec+"秒后自动更新UID" 
			timeinfo.innerText=curtime 
			setTimeout("beginrefresh()",1000) 
		} 
	} 
	window.onload=beginrefresh 
</script>
<body>
<table width="150" height="100" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="150" height="100" align="center">
        	<br><br>
        	<?php echo $datetime?>
        	<br><br><span id="timeinfo"></span><br><br>
            <span id="ShowTime"></span><br><br>
        	<input type=button name=button value="重新登陆" onClick="window.location.reload()">
       	</td>
    </tr>
</table>
</body>
</html>