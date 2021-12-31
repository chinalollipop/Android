<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require_once ("../agents/include/config.inc.php");
require_once("../../../common/sportCenterData.php");
require ("../agents/include/address.mem.php");
require ("./include/curl_http.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$id = intval($_GET['uid']);
if(!is_array($_SESSION['notice_idstic'])) {
	$_SESSION['notice_idstic'] = array();
}
$lv = $_SESSION['admin_level'] ; // 操作者层级
$loginname=$_SESSION["username"] ;
$account = isset($_POST['account'])?$_POST['account']:'' ; // 刷水帐号

if($_GET['act'] == 'add') {

	if(!in_array($id,$_SESSION['notice_idstic'])) {
		$_SESSION['notice_idstic'][] = $id;
		
	}

}

if($_GET['act'] == 'del') {
	if(!empty($_SESSION['notice_idstic'])) {
		foreach ($_SESSION['notice_idstic'] as $k => $v) {
			if($v == $id) {
				unset($_SESSION['notice_idstic'][$k]);
			}
		}
	}
}

if($_POST['type'] == 'delAccEx'){ // 删除刷水帐号
	if(!$_POST['uid']){
		echo json_encode(array('status'=>1,'message'=>'缺少uid！'));
		exit;
	}
	
	if( !$_POST['id'] || !is_numeric($_POST['id']) ){
		echo json_encode(array('status'=>1,'message'=>'缺少参数'));
		exit;
	}

	$sql="delete from ".DATAHGPREFIX."web_getdata_account_expand where ID=".$_POST['id'];
	$res = mysqli_query($dbCenterMasterDbLink,$sql);
	if($res){
        $loginfo = $loginname.' 在系统参数设置中 <font class="red">删除</font> 了刷水帐号 <font class="green">'.$account.'</font>' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
		echo json_encode(array('status'=>0,'message'=>'删除成功!'));
	}else{
		echo json_encode(array('status'=>0,'message'=>'操作失败!'));
	}
	exit;
}

// 添加账号，uid刷水测试
if ($_POST['type'] == 'testExpandAccData'){

    if( !$_POST['typeEx'] || !is_string($_POST['typeEx']) ){
        $_POST['typeEx']='zh-cn';
    }

    if( !$_POST['urlEx'] || !is_string($_POST['urlEx']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数urlEx'));
        exit;
    }

    if( !$_POST['uidEx'] || !is_string($_POST['uidEx']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数uidEx'));
        exit;
    }

    $typeEx = $_POST['typeEx'];
    $urlEx = $_POST['urlEx'];
    $uidEx = $_POST['uidEx'];
    $list_date=date('Y-m-d',time()-24*60*60);

//    echo "".$urlEx."/app/member/result/result.php?game_type=FT&list_date=".$list_date."&uid=".$uidEx."&langx=".$typeEx;
//    echo '<br>';

    // 接比分
    $curl = new Curl_HTTP_Client();
    $curl->store_cookies("/tmp/cookies.txt");
    $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    $curl->set_referrer("".$urlEx."/app/member/FT_index.php?uid=".$uidEx."&langx=".$typeEx."&mtype=3");
    $html_data=$curl->fetch_url("".$urlEx."/app/member/result/result.php?game_type=FT&list_date=".$list_date."&uid=".$uidEx."&langx=".$typeEx);
    $data = get_content_deal($html_data);
    $cou=sizeof($data);
    if($cou>1) {
        exit( json_encode(array('status'=>0,'message'=>"刷水成功")));
    }else{
        exit( json_encode(array('status'=>1,'message'=>'刷水错误，请检查账号或者网络')));
    }

}

// 添加或者更新刷比分账号，uid刷水测试（新版、旧版）
if ($_POST['type'] == 'testScoreExpandAccData'){

    if( !$_POST['siteurl'] || !is_string($_POST['siteurl']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数siteurl'));
        exit;
    }

    if( !$_POST['siteNewUrl'] || !is_string($_POST['siteNewUrl']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数siteNewUrl'));
        exit;
    }

    if( !$_POST['siteUid'] || !is_string($_POST['siteUid']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数siteUid'));
        exit;
    }

    $typeEx = 'zh-cn';
    $siteurl = $_POST['siteurl'];
    $siteNewUrl = $_POST['siteNewUrl'];
    $siteUid = $_POST['siteUid'];
    $list_date=date('Y-m-d',time()-24*60*60);
    $gtype = 'FT';

    // 接比分旧版
    $curl = new Curl_HTTP_Client();
    $curl->store_cookies("/tmp/cookies.txt");
    $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    $curl->set_referrer("".$siteurl."/app/member/FT_index.php?uid=".$siteUid."&langx=".$typeEx."&mtype=3");
    $html_data=$curl->fetch_url("".$siteurl."/app/member/result/result.php?game_type=$gtype&list_date=".$list_date."&uid=".$siteUid."&langx=".$typeEx);
    $data = get_content_deal($html_data);
    $cou=sizeof($data);
    if($cou<=1) {
        exit( json_encode(array('status'=>1,'message'=>'刷水错误（旧版），请检查账号或者网络')));
    }

    // 接比分新版
    $filename="".$siteNewUrl."/app/member/account/result/result.php?game_type=$gtype&uid=$siteUid&langx=$typeEx&list_date=$list_date";
    $curl = new Curl_HTTP_Client();
    $curl->store_cookies("/tmp/cookies.txt");
    $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    // $curl->set_referrer("".$newsite."/app/member/FT_browse/index.php?rtype=re&uid=$newsuid&langx=$langx&mtype=3");
    $html_data=$curl->fetch_url($filename);
//    $html_data=str_replace($newsuid,$uid,$html_data);
    $res=explode('<div>',$html_data);
    if($res[1]=='') { // 没有数据
        exit( json_encode(array('status'=>1,'message'=>'刷水错误（新版），请检查账号或者网络')));
    }

    exit( json_encode(array('status'=>0,'message'=>"刷水成功，即刻更新")));

}
function get_content_deal($html_data){
    $html_data = strtolower($html_data);
    $a = array(
        "<script>",
        "</script>",
        '"',
        "\n\n",
        "<br>",
        " ",
        '</b></font>',
        "<td>",
        "<tdalign=left>",
        "<fontcolor=#cc0000>",
        "<fontcolor=red>",
        "<b>",
        "</b>",
        "</a>",
        "</font>",
        "<spanstyle=overflow:hidden;>",
        "</span>",
        "&nbsp;&nbsp;",
        "full_main_ftcal",
        "hr_main_ftcal"
    );
    $b = array(
        "",
        "",
        "",
        "",
        "-",
        "",
        '',
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "full_main_ft",
        "hr_main_ft"
    );
    $msg = str_replace($a,$b,$html_data);
    $data1=explode("<tableborder=0cellspacing=0cellpadding=0class=game>",$msg);
    $data=explode("<trclass=b_cenid=",$data1[2]);
    return $data;
}

// 更新刷水帐号 uid
if($_POST['type'] == 'updateAccEx'){
	
	$account_new = $_POST['account'];
	$passwd_new = $_POST['passwd'];
	$datasiteedt_new = $_POST['datasiteedt'];
	
	$sql1 = "update ".DATAHGPREFIX."web_getdata_account_expand set `datetime`='".$datetime."',Datasite='".$datasiteedt_new."',Name='".$account_new."',Passwd='".$passwd_new."',status=0 where ID=".$_POST['id'];
	$res1 = mysqli_query($dbCenterMasterDbLink,$sql1);

	if(!$_POST['uid']){
		echo json_encode(array('status'=>1,'message'=>'缺少uid！'));
		exit;
	}
	
	if( !$_POST['id'] || !is_numeric($_POST['id']) ){
		echo json_encode(array('status'=>1,'message'=>'缺少参数'));
		exit;
	}
	
	$sql = "select Datasite,ID,Name,Passwd,Type from ".DATAHGPREFIX."web_getdata_account_expand where ID=".$_POST['id'];
	$result = mysqli_query($dbCenterSlaveDbLink,$sql);
	$resultFetch = mysqli_fetch_assoc($result);
	 
	//登录获取UID
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt");
	$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");
	$login=array();
	$url=$resultFetch['Datasite'];
	$login['username']=$resultFetch['Name'];
	$login['passwords']=$resultFetch['Passwd'];
	$login['langx']=$resultFetch['Type'];
    $login['auto']='IGIAHZ';
    $login['nowsite']='new';

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
	
	$sql1 = "update ".DATAHGPREFIX."web_getdata_account_expand set `datetime`='".$datetime."',Uid='".$uid."',status=0 where ID=".$resultFetch['ID'];
	$res1 = mysqli_query($dbCenterMasterDbLink,$sql1);
	
	if($res1){
        $loginfo = $loginname.' 在系统参数设置中对刷水帐号 <font class="green">'.$account.'</font>, <font class="red">更新</font> 了 UID ' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
		echo json_encode(array('status'=>0,'message'=>"更新UID成功!"));
	}else{
		echo json_encode(array('status'=>0,'message'=>'操作失败!'));
	}
	exit;
}

// 更新刷水帐号（数据网址、用户名、密码、uid），不登录正网
if($_POST['type'] == 'updateAccExNoLogin'){

    if(!$_POST['uid']){
        echo json_encode(array('status'=>1,'message'=>'缺少uid！'));
        exit;
    }

    if( !$_POST['id'] || !is_numeric($_POST['id']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数id'));
        exit;
    }

    if( !$_POST['DatasiteEdt'] || !is_string($_POST['DatasiteEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数DatasiteEdt'));
        exit;
    }

    if( !$_POST['nameEdt'] || !is_string($_POST['nameEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数nameEdt'));
        exit;
    }

    if( !$_POST['passwdEdt'] || !is_string($_POST['passwdEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数passwdEdt'));
        exit;
    }

    if( !$_POST['uidEdt'] || !is_string($_POST['uidEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数uidEdt'));
        exit;
    }

    $datetime = date('Y-m-d H:i:s');
    $sql1 = "update ".DATAHGPREFIX."web_getdata_account_expand set `datetime`='".$datetime."',Datasite='".$_POST['DatasiteEdt']."',Name='".$_POST['nameEdt']."',Passwd='".$_POST['passwdEdt']."',Uid='".$_POST['uidEdt']."',status=0 where ID=".$_POST['id'];
    $res1 = mysqli_query($dbCenterMasterDbLink,$sql1);

    if($res1){
        $loginfo = $loginname.' 在系统参数设置中对刷水帐号 <font class="green">'.$account.'</font>, <font class="red">更新</font> 了 UID ' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
        echo json_encode(array('status'=>0,'message'=>"更新（数据网址、用户名、密码、UID）成功!"));
    }else{
        echo json_encode(array('status'=>0,'message'=>'操作失败!'));
    }
    exit;
}

// 更新刷水帐号（数据网址、用户名、密码、uid、cookie）
if($_POST['type'] == 'updateAcountExpandNologin'){
    if(!$_POST['uid']){
        echo json_encode(array('status'=>1,'message'=>'缺少uid！'));
        exit;
    }

    if( !$_POST['id'] || !is_numeric($_POST['id']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数id'));
        exit;
    }

    if( !$_POST['DatasiteEdt'] || !is_string($_POST['DatasiteEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数DatasiteEdt'));
        exit;
    }

    if( !$_POST['nameEdt'] || !is_string($_POST['nameEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数nameEdt'));
        exit;
    }

    if( !$_POST['passwdEdt'] || !is_string($_POST['passwdEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数passwdEdt'));
        exit;
    }

    if( !$_POST['uidEdt'] || !is_string($_POST['uidEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数uidEdt'));
        exit;
    }
/*
    if( !$_POST['cookieEdt'] || !is_string($_POST['cookieEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数cookieEdt'));
        exit;
    }*/

    if( !$_POST['verEdt'] || !is_string($_POST['verEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数verEdt'));
        exit;
    }

    $datetime = date('Y-m-d H:i:s');
    $sql1 = "update ".DATAHGPREFIX."web_getdata_account_expand set `datetime`='".$datetime."',Datasite='".$_POST['DatasiteEdt']."',Name='".$_POST['nameEdt']."',Passwd='".$_POST['passwdEdt']."',Uid='".$_POST['uidEdt']."',Ver='".$_POST['verEdt']."',status=0 where ID=".$_POST['id'];
    $res1 = mysqli_query($dbCenterMasterDbLink,$sql1);

    if($res1){
        $loginfo = $loginname.' 在系统参数设置中对刷水帐号 <font class="green">'.$account.'</font>, <font class="red">更新</font> 了 UID ' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
        echo json_encode(array('status'=>0,'message'=>"更新（数据网址、用户名、密码、UID、ver）成功!"));
    }else{
        echo json_encode(array('status'=>0,'message'=>'操作失败!'));
    }
    exit;
}


//添加扩展刷水账号不登录正网
if($_POST['type'] == 'accountExpandNoLogin') {

    //扩展uid
    $sql = "select Name from ".DATAHGPREFIX."web_getdata_account_expand";
    $result = mysqli_query($dbCenterSlaveDbLink,$sql);
    while($resultFetch = mysqli_fetch_assoc($result)){
        $exitAccountArr[] = $resultFetch['Name'];
    }

    if(in_array($_REQUEST['nameEx'],$exitAccountArr)){
        echo json_encode(array('status'=>1,'message'=>'账号已经存在不能重复添加'));
        exit;
    }

    $typeEx = $_POST['typeEx'];
    $urlEx = $_POST['urlEx'];
    $nameEx = $_POST['nameEx'];
    $passwdEx = $_POST['passwdEx'];
    $uidEx = $_POST['uidEx'];
    $curDate = date('y-m-d h:i:s',time());
    
    $login=array();
    $login['username']=$nameEx;
    $login['passwords']=$passwdEx;
    if($typeEx=="ms"){
        $login['langx']='zh-cn';
    }else{
        $login['langx']=$typeEx;
    }
    $login['auto']='IGIAHZ';
    $login['nowsite']='new';

    $sql="INSERT INTO ".DATAHGPREFIX."web_getdata_account_expand(`Type`,`Datasite`,`Uid`,`Name`,`Passwd`,`datetime`) VALUES('$typeEx','$urlEx','$uidEx','$nameEx','$passwdEx','$curDate')";
    $res = mysqli_query($dbCenterMasterDbLink,$sql);
    if($res){
        $loginfo = $loginname.' 在系统参数设置中 <font class="red">添加</font> 了刷水帐号 <font class="green">'.$nameEx.'</font>,数据网址为 <font class="blue">'.$urlEx.'</font>,帐号类型为 <font class="red">'.$typeEx.'</font>' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
        echo json_encode(array('status'=>0,'message'=>'添加成功！'));
    }else{
        echo json_encode(array('status'=>0,'message'=>'账号插入失败！'));
    }
    exit;

}

//添加扩展刷水账号
if($_POST['type'] == 'accountExpand') {
	if(!$_POST['uid']){
		echo json_encode(array('status'=>1,'message'=>'缺少uid！'));
		exit;
	}
    if(!$_POST['verEx']){
        echo json_encode(array('status'=>1,'message'=>'缺少verEx！'));
        exit;
    }
	$uid = $_POST['uid'];
    $verEx = $_POST['verEx'];   // 正网新版用到
	/*
	$sql = "select NAME,Name_tw,Name_en,Name_ms from ".DBPREFIX."web_system_data where Oid='$uid'";
	$result = mysqli_query($dbLink,$sql);
	$exitRow = mysqli_fetch_assoc($result);
	$exitAccountArr = array();
	foreach($exitRow as $k=>$v){//正在使用的uid
		$exitAccountArr[]=$v;		
	}
	*/
	//扩展uid
	$sql = "select Name,`Uid` from ".DATAHGPREFIX."web_getdata_account_expand";
	$result = mysqli_query($dbCenterSlaveDbLink,$sql);
	while($resultFetch = mysqli_fetch_assoc($result)){
		$exitAccountArr[] = $resultFetch['Name'];
        $exitUidArr[] = $resultFetch['Uid'];
	}

	if(in_array($_REQUEST['nameEx'],$exitAccountArr)){
		echo json_encode(array('status'=>1,'message'=>'账号已经存在不能重复添加'));
		exit;
	}
    if(in_array($uid,$exitUidArr)){
        echo json_encode(array('status'=>1,'message'=>'Uid不能重复添加'));
        exit;
    }
	
	$typeEx = $_POST['typeEx']; //zh-tw
	$urlEx = $_POST['urlEx'];   //网址
	$nameEx = $_POST['nameEx'];
	$passwdEx = $_POST['passwdEx'];
	$curDate = date('y-m-d h:i:s',time());
	
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt");
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$urlEx."");

	$login=array();
	$login['username']=$nameEx;
	$login['passwords']=$passwdEx;
	if($typeEx=="ms"){
		$login['langx']='zh-cn';	
	}else{
		$login['langx']=$typeEx;
	}

    $login['auto']='IGIAHZ';
    $login['nowsite']='new';

	$html_date=$curl->send_post_data("".$urlEx."/app/member/new_login.php",$login,"",10);

//	/200|100||00ngqz44bm17735472l2242972|4|zh-tw
	/*if (!$html_date){
	    echo json_encode(array('status'=>1,'message'=>'账号登陆错误,uid获取失败!'));
		exit;
	}else{
		$liveid='';
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
				$uidCreate=$uid[3];
				$liveid=$liveid[1];
			}else {
				echo json_encode(array('status'=>1,'message'=>'账号登陆错误,uid获取失败!'));
				exit;	
			}
		}*/

		$sql="INSERT INTO ".DBPREFIX."web_getdata_account_expand(`Type`,`Datasite`,`Uid`,`Name`,`Passwd`,`LiveID`,`datetime`,`ver`) VALUES('$typeEx','$urlEx','$uid','$nameEx','$passwdEx','$liveid','$curDate','$verEx')";
		$res = mysqli_query($dbCenterMasterDbLink,$sql);
		if($res){
            $loginfo = $loginname.' 在系统参数设置中 <font class="red">添加</font> 了刷水帐号 <font class="green">'.$nameEx.'</font>,数据网址为 <font class="blue">'.$urlEx.'</font>,帐号类型为 <font class="red">'.$typeEx.'</font>' ;
            innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
			echo json_encode(array('status'=>0,'message'=>'添加成功！'));
		}else{
			echo json_encode(array('status'=>0,'message'=>'账号插入失败！'));
		}
		exit;
	//}
}

// 添加旧站刷比分账号
if($_POST['type'] == 'updateMsAccount') {
	if(!$_POST['uid']){
		echo json_encode(array('status'=>1,'message'=>'缺少uid！'));
		exit;
	}
	$uid = $_POST['uid'];
	$urlEx = $_POST['urlEx'];
	$nameEx = $_POST['nameEx'];
	$passwdEx = $_POST['passwdEx'];
	$curDate = date('y-m-d h:i:s',time());
	
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt");
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$urlEx."");

	$login=array();
	$login['username']=$nameEx;
	$login['passwords']=$passwdEx;
	$login['langx']='zh-cn';
    $login['auto']='IGIAHZ';
    $login['nowsite']='new';

	$html_date=$curl->send_post_data("".$urlEx."/app/member/new_login.php",$login,"",10);

	if (!$html_date){
	    echo json_encode(array('status'=>1,'message'=>'账号登陆错误,uid获取失败!'));
		exit;
	}else{
		$liveid='';
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
				$uidCreate=$uid[3];
				$liveid=$liveid[1];
			}else {
				echo json_encode(array('status'=>1,'message'=>'账号登陆错误,uid获取失败!'));
				exit;	
			}
		}
		
		$sql="INSERT INTO ".DATAHGPREFIX."web_getdata_account_expand(`Type`,`Datasite`,`Uid`,`Name`,`Passwd`,`LiveID`,`datetime`) VALUES('$typeEx','$urlEx','$uidCreate','$nameEx','$passwdEx','$liveid','$curDate')";
		$res = mysqli_query($dbCenterMasterDbLink,$sql);
		
		$mysql="update ".DBPREFIX."web_system_data set datasite_ms='".$urlEx."',Name_ms='".$nameEx."',Passwd_ms='".$passwdEx."',Uid_ms='".$uidCreate."'";
		mysqli_query($dbMasterLink,$mysql);
		
		if($res){
            $loginfo = $loginname.' 在系统参数设置中 <font class="red">添加了</font> 旧站接比分网址 <font class="red">'.$urlEx.'</font>, 帐号为 <font class="green">'.$nameEx.'</font> ' ;
            innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
			echo json_encode(array('status'=>0,'message'=>'添加成功！'));
		}else{
			echo json_encode(array('status'=>0,'message'=>'账号插入失败！'));
		}
		exit;
	}
}


if($_POST['act'] == 'bankLevelEdit') {
	$bank_id = $_POST['bid'];

	//$pay_class = implode(',',json_decode($_POST['level']));
    $pay_class = implode(',' ,json_decode(stripslashes($_POST['level']))); //取消反斜线,解码，转成字符串

	if(empty($bank_id)){
		echo json_encode(array('status'=>1,'message'=>'第三方支付编号不能为空！'));exit;
	}
	if(empty($pay_class)){
		echo json_encode(array('status'=>1,'message'=>'用户层级不能为空！'));exit;
	}
	
	if($_POST['type']==1){
		$sql = "update ".DBPREFIX."gxfcy_pay set class = '{$pay_class}' where id = {$bank_id}";		
	}elseif($_POST['type']==2){
		$sql = "update ".DBPREFIX."gxfcy_bank_data set class = '{$pay_class}' where id = {$bank_id}";
	}
	
	$res = mysqli_query($dbMasterLink,$sql);
	if($res){
		echo json_encode(array('status'=>0,'message'=>'编辑成功！'));exit;		
	}else{
		echo json_encode(array('status'=>1,'message'=>'编辑失败！'));exit;
	}	
}
// 修改会员层级 和 新增
if($_POST['act'] == 'editUserLevel') {
	
 		if (!isset($_REQUEST['ename']) or empty($_REQUEST['ename']))
        {
            echo json_encode(array('status'=>1,'message'=>'英文唯一标识不能为空！'));exit;
        }
        if (!isset($_REQUEST['name']) or empty($_REQUEST['name']))
        {
            echo json_encode(array('status'=>1,'message'=>'层级名称不能为空！'));exit;
        }

        if (!isset($_REQUEST['start_time']) or empty($_REQUEST['start_time']))
        {
            echo json_encode(array('status'=>1,'message'=>'开始时间不能为空！'));exit;
        }
        if (!isset($_REQUEST['end_time']) or empty($_REQUEST['end_time']))
        {
            script_alert("alert('');history.go(-1);");
            echo json_encode(array('status'=>1,'message'=>'结束时间不能为空！'));exit;
        }
        if ((!isset($_REQUEST['deposit_num']) or empty($_REQUEST['deposit_num'])) and $_REQUEST['deposit_num'] !=0)
        {
            echo json_encode(array('status'=>1,'message'=>'存款次数不能为空！'));exit;
        }
        if ((!isset($_REQUEST['deposit_money']) or empty($_REQUEST['deposit_money'])) and $_REQUEST['deposit_money'] !=0)
        {
            echo json_encode(array('status'=>1,'message'=>'存款总额不能为空！'));exit;
        }
        if ((!isset($_REQUEST['max_deposit_money']) or empty($_REQUEST['max_deposit_money'])) and $_REQUEST['max_deposit_money'] !=0)
        {
            echo json_encode(array('status'=>1,'message'=>'最大存款额度不能为空！'));exit;
        }
        if ((!isset($_REQUEST['withdraw_num']) or empty($_REQUEST['withdraw_num'])) and $_REQUEST['withdraw_num'] !=0)
        {
            echo json_encode(array('status'=>1,'message'=>'提款次数不能为空！'));exit;
        }
        if ((!isset($_REQUEST['withdraw_money']) or empty($_REQUEST['withdraw_money'])) and $_REQUEST['withdraw_money'] !=0)
        {
            echo json_encode(array('status'=>1,'message'=>'提款总额不能为空！'));exit;
        }
        if ((!isset($_REQUEST['sort']) or empty($_REQUEST['sort'])))
        {
            echo json_encode(array('status'=>1,'message'=>'序号不能为空！'));exit;
        }
        
        if(isset($_REQUEST['id'])&&!empty($_REQUEST['id'])){ // 修改 会员层级
		        $id = $_REQUEST['id'];
		        $sql="select name from ".DBPREFIX."gxfcy_userlevel where id = ".$id;
		        $result = mysqli_query($dbLink,$sql);
				$userlevel = mysqli_fetch_assoc($result);
			    if($userlevel['name'] != $_REQUEST['name']){
		            $sql="select update_time,ename from ".DBPREFIX."gxfcy_userlevel where name = '".$_REQUEST['name']."' or ename='".$_REQUEST['ename']."' and id!=".$id;
		            $result = mysqli_query($dbLink,$sql);
		            $rowcount =mysqli_num_rows($result);
					    if($rowcount>0){
							echo json_encode(array('status'=>1,'message'=>'已经存在该层级名称或者英文唯一标识！'));exit;
			            }
		            }
		            
		            $data = $_REQUEST;
		            unset($data['id']);
		            unset($data['act']);
		            $data['update_time'] = date('Y-m-d H:i:s');
		            foreach($data as $key=>$val){
		            	$tmp[]=$key.'=\''.$val.'\'';
		        	}
		
		        $sql="update ".DBPREFIX."gxfcy_userlevel set ".implode(',',$tmp)." where id = $id";
		        if(mysqli_query($dbMasterLink,$sql)){

                    $loginfo = $loginname.' 层级管理中 <font class="red">修改了</font> 会员层级, id 为<font class="red">'.$id.'</font> ' ;
                    innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */

		        	echo json_encode(array('status'=>0,'message'=>'编辑成功。'));exit;	
		        }else{
		        	echo json_encode(array('status'=>1,'message'=>'编辑失败。'));exit;
		        }
        }else{ // 添加 会员层级
        		$sql="select update_time,update_time,ename from ".DBPREFIX."gxfcy_userlevel where name = '".$_REQUEST['name']."' or ename='".$_REQUEST['ename']."'";
        		$result = mysqli_query($dbLink,$sql);
	            $rowcount =mysqli_num_rows($result);
			    if($rowcount>0){
					echo json_encode(array('status'=>1,'message'=>'已经存在该层级名称或者英文唯一标识！'));
					exit;
	            }
		    	
	            $data = $_REQUEST;
	            unset($data['act']);
	            $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');
	            foreach($data as $key=>$val){
            		$tmp[]=$key.'=\''.$val.'\'';
        		}
		        $sql="insert into ".DBPREFIX."gxfcy_userlevel set ".implode(',',$tmp);
		        if(mysqli_query($dbMasterLink,$sql)){
                    /* 插入系统日志 */
                    $loginfo = $loginname.' 层级管理中 <font class="red">添加了</font> 会员层级, 层级名称为<font class="red">'.$_REQUEST['name'].'</font> ,层级为 <font class="green">'.$_REQUEST['level'].'</font> ' ;
                    innsertSystemLog($loginname,$lv,$loginfo);

			        	echo json_encode(array('status'=>0,'message'=>'添加层级成功。'));exit;	
			    }else{
			        	echo json_encode(array('status'=>1,'message'=>'添加层级失败。'));exit;
			    }
        }
        
}
// 删除会员层级
if($_POST['act'] == 'delUserLevel'){
	if(empty($_REQUEST[id])){
		echo json_encode(array('status'=>1,'message'=>'记录id不能为空！'));
		exit;
	}
	$id = $_REQUEST[id];
	$sql="delete from ".DBPREFIX."gxfcy_userlevel where id = $id";
	if(mysqli_query($dbMasterLink,$sql)){
        $loginfo = $loginname.' 层级管理中 <font class="red">删除了</font> 会员层级, id 为<font class="red">'.$id.'</font> ' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
		echo json_encode(array('status'=>0,'message'=>'删除层级成功。'));exit;	
	}else{
		echo json_encode(array('status'=>1,'message'=>'删除层级失败。'));exit;
	}
}

if($_POST['act'] == 'serchMemberSend'){
		$usernames = $_REQUEST['usernames'];
        if(empty($usernames)){
            echo json_encode(array('status'=>1,'message'=>'用户名不能为空！'));exit;
        }
        $userList = explode(',',$usernames);
        
        foreach($userList as $key=>$val){
            $tmp[]="'$val'";
        }
        $sql="select id,username,Agents,pay_class,`AddDate` from ".DBPREFIX.MEMBERTABLE." where username in(".implode(',',$tmp).")";
        $result = mysqli_query($dbLink,$sql);
        while($row = mysqli_fetch_assoc($result)){
        	$info[] = $row;	
        }
        $userinfo = array();
        foreach($info as $list) {
            $sql = "select deposit_num,deposit_money,max_deposit_money,withdraw_num,withdraw_money from ".DBPREFIX."gxfcy_usermoney_statistics where userid = {$list['id']}";
            $result = mysqli_query($dbLink,$sql);
       	 	$user_infos = mysqli_fetch_assoc($result);
            $list['deposit_num'] = empty($user_infos['deposit_num'])?0:$user_infos['deposit_num'];
            $list['deposit_money'] = empty($user_infos['deposit_money'])?0:$user_infos['deposit_money'];
            $list['max_deposit_money'] = empty($user_infos['max_deposit_money'])?0:$user_infos['max_deposit_money'];
            $list['withdraw_num'] = empty($user_infos['withdraw_num'])?0:$user_infos['withdraw_num'];
            $list['withdraw_money'] = empty($user_infos['withdraw_money'])?0:$user_infos['withdraw_money'];
            $userinfo[] = $list;
        }
        
        $sql="select * from ".DBPREFIX."gxfcy_userlevel order by sort asc";
		$result = mysqli_query($dbLink,$sql);
        while($row = mysqli_fetch_assoc($result)){
        	$level[] = $row;	
        }
        echo json_encode(array('status'=>0,'message'=>'请求成功！','user'=>$userinfo,'level'=>$level));exit;	
}



//【视屏采集账号】
if($_POST['type'] == 'accountExpandNoLoginVideo') {
    //扩展uid
    $sql = "select Name from ".DBPREFIX."web_official_account_expand";
    $result = mysqli_query($dbLink,$sql);
    while($resultFetch = mysqli_fetch_assoc($result)){
        $exitAccountArr[] = $resultFetch['Name'];
    }

    if(in_array($_REQUEST['nameEx'],$exitAccountArr)){
        echo json_encode(array('status'=>1,'message'=>'账号已经存在不能重复添加'));
        exit;
    }

    $urlEx = $_POST['urlEx'];
    $nameEx = $_POST['nameEx'];
    $passwdEx = $_POST['passwdEx'];
    $uidEx = $_POST['uidEx'];
    $livedEx = $_POST['livedEx'];
    $curDate = date('y-m-d h:i:s',time());
    
    $login=array();
    $login['username']=$nameEx;
    $login['passwords']=$passwdEx;
    if($typeEx=="ms"){
        $login['langx']='zh-cn';
    }else{
        $login['langx']=$typeEx;
    }
    $login['auto']='IGIAHZ';
    $login['nowsite']='new';

    $sql="INSERT INTO ".DBPREFIX."web_official_account_expand(`Datasite`,`Uid`,`Name`,`Passwd`,`datetime`,`LiveID`) VALUES('$urlEx','$uidEx','$nameEx','$passwdEx','$curDate','$livedEx')";
    $res = mysqli_query($dbMasterLink,$sql);
    if($res){
        $loginfo = $loginname.' 在系统参数设置中 <font class="red">添加</font> 了视屏采集帐号 <font class="green">'.$nameEx.'</font>,数据网址为 <font class="blue">'.$urlEx.'</font>,帐号类型为 <font class="red">'.$typeEx.'</font>' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
        echo json_encode(array('status'=>0,'message'=>'添加成功！'));
    }else{
        echo json_encode(array('status'=>0,'message'=>'账号插入失败！'));
    }
    exit;
}

//添加视屏采集账号
if($_POST['type'] == 'accountExpandVideo') {
	$sql = "select Name from ".DBPREFIX."web_official_account_expand";
	$result = mysqli_query($dbLink,$sql);
	while($resultFetch = mysqli_fetch_assoc($result)){
		$exitAccountArr[] = $resultFetch['Name'];	
	}
	if(count($exitAccountArr)>0){
		if(in_array($_REQUEST['nameEx'],$exitAccountArr)){
			echo json_encode(array('status'=>1,'message'=>'账号已经存在不能重复添加'));
			exit;
		}
	}
	
	$typeEx = 1;
	$urlEx = $_POST['urlEx'];
	$nameEx = $_POST['nameEx'];
	$passwdEx = $_POST['passwdEx'];
	$livedEx = $_POST['livedEx'];
	$curDate = date('y-m-d h:i:s',time());
	
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt");
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$urlEx."");

	$login=array();
	$login['username']=$nameEx;
	$login['passwords']=$passwdEx;
    $login['auto']='IGIAHZ';
    $login['nowsite']='new';

	$html_date=$curl->send_post_data("".$urlEx."/app/member/new_login.php",$login,"",10);
//	/200|100||00ngqz44bm17735472l2242972|4|zh-tw
	if (!$html_date){
	    echo json_encode(array('status'=>1,'message'=>'账号登陆错误,uid获取失败!'));
		exit;
	}else{
		if(strstr($html_date,"newdomain")){
			preg_match("/action='([^']+)/si",$html_date,$url);
			preg_match("/<input type='hidden' name='uid' value='([^']+)/si",$html_date,$uid);
			$url=$url[1];
			$uid=$uid[1];
			$liveid=strlen($liveid[1])>10 ? $liveid[1] : $livedEx;
		}else{
			preg_match("/top.uid = '([^']+)/si",$html_date,$uid);
			preg_match("/top.liveid = '([^']+)/si",$html_date,$liveid);
			$uid=explode("|",$html_date);
			if($uid[3]){
				$uidCreate=$uid[3];
				$liveid=strlen($liveid[1])>10 ? $liveid[1] : $livedEx;
			}else {
				echo json_encode(array('status'=>1,'message'=>'账号登陆错误,uid获取失败!'));
				exit;	
			}
		}

		$sql="INSERT INTO ".DBPREFIX."web_official_account_expand(`Type`,`Datasite`,`Uid`,`Name`,`Passwd`,`LiveID`,`datetime`) VALUES(1,'$urlEx','$uidCreate','$nameEx','$passwdEx','$liveid','$curDate')";
		$res = mysqli_query($dbMasterLink,$sql);
		if($res){
            $loginfo = $loginname.' 在系统参数设置中 <font class="red">添加</font> 了刷水帐号 <font class="green">'.$nameEx.'</font>,数据网址为 <font class="blue">'.$urlEx.'</font>' ;
            innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
			echo json_encode(array('status'=>0,'message'=>'添加成功！'));
		}else{
			echo json_encode(array('status'=>0,'message'=>'账号插入失败！'));
		}
		exit;
	}
}

// 添加旧站刷比分账号
if($_POST['type'] == 'updateMsAccountVideo') {
	if(!$_POST['uid']){
		echo json_encode(array('status'=>1,'message'=>'缺少uid！'));
		exit;
	}
	$uid = $_POST['uid'];
	$urlEx = $_POST['urlEx'];
	$nameEx = $_POST['nameEx'];
	$passwdEx = $_POST['passwdEx'];
	$curDate = date('y-m-d h:i:s',time());
	
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt");
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$urlEx."");

	$login=array();
	$login['username']=$nameEx;
	$login['passwords']=$passwdEx;
	$login['langx']='zh-cn';
    $login['auto']='IGIAHZ';
    $login['nowsite']='new';

	$html_date=$curl->send_post_data("".$urlEx."/app/member/new_login.php",$login,"",10);

	if (!$html_date){
	    echo json_encode(array('status'=>1,'message'=>'账号登陆错误,uid获取失败!'));
		exit;
	}else{
		$liveid='';
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
				$uidCreate=$uid[3];
				$liveid=$liveid[1];
			}else {
				echo json_encode(array('status'=>1,'message'=>'账号登陆错误,uid获取失败!'));
				exit;	
			}
		}
		
		$sql="INSERT INTO ".DBPREFIX."web_official_account_expand(`Type`,`Datasite`,`Uid`,`Name`,`Passwd`,`LiveID`,`datetime`) VALUES('$typeEx','$urlEx','$uidCreate','$nameEx','$passwdEx','$liveid','$curDate')";
		$res = mysqli_query($dbMasterLink,$sql);

		if($res){
            $loginfo = $loginname.' 在系统参数设置中 <font class="red">添加了</font> 旧站接比分网址 <font class="red">'.$urlEx.'</font>, 帐号为 <font class="green">'.$nameEx.'</font> ' ;
            innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
			echo json_encode(array('status'=>0,'message'=>'添加成功！'));
		}else{
			echo json_encode(array('status'=>0,'message'=>'账号插入失败！'));
		}
		exit;
	}
}

if($_POST['type'] == 'delAccExVideo'){ // 删除刷水帐号
	if(!$_POST['uid']){
		echo json_encode(array('status'=>1,'message'=>'缺少uid！'));
		exit;
	}
	
	if( !$_POST['id'] || !is_numeric($_POST['id']) ){
		echo json_encode(array('status'=>1,'message'=>'缺少参数'));
		exit;
	}

	$sql="delete from ".DBPREFIX."web_official_account_expand where ID=".$_POST['id'];
	$res = mysqli_query($dbMasterLink,$sql);
	if($res){
        $loginfo = $loginname.' 在系统参数设置中 <font class="red">删除</font> 了刷水帐号 <font class="green">'.$account.'</font>' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
		echo json_encode(array('status'=>0,'message'=>'删除成功!'));
	}else{
		echo json_encode(array('status'=>0,'message'=>'操作失败!'));
	}
	exit;
}

// 更新刷水帐号 uid
if($_POST['type'] == 'updateAccExVideo'){
	
	$account_new = $_POST['account'];
	$passwd_new = $_POST['passwd'];
	$datasiteedt_new = $_POST['datasiteedt'];
	$liveid = $_POST['liveid'];
	
	$sql1 = "update ".DBPREFIX."web_official_account_expand set `datetime`='".$datetime."',Datasite='".$datasiteedt_new."',Name='".$account_new."',Passwd='".$passwd_new."',LiveID='".$liveid."',status=0 where ID=".$_POST['id'];
	$res1 = mysqli_query($dbMasterLink,$sql1);

	if(!$_POST['uid']){
		echo json_encode(array('status'=>1,'message'=>'缺少uid！'));
		exit;
	}
	
	if( !$_POST['id'] || !is_numeric($_POST['id']) ){
		echo json_encode(array('status'=>1,'message'=>'缺少参数'));
		exit;
	}
	
	$sql = "select Datasite,ID,Name,Passwd,Type from ".DBPREFIX."web_official_account_expand where ID=".$_POST['id'];
	$result = mysqli_query($dbLink,$sql);
	$resultFetch = mysqli_fetch_assoc($result);
	 
	//登录获取UID
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt");
	$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");
	$login=array();
	$url=$resultFetch['Datasite'];
	$login['username']=$resultFetch['Name'];
	$login['passwords']=$resultFetch['Passwd'];
	$login['langx']=$resultFetch['Type'];
    $login['auto']='IGIAHZ';
    $login['nowsite']='new';

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
	
	$sql1 = "update ".DBPREFIX."web_official_account_expand set `datetime`='".$datetime."',Uid='".$uid."',status=0 where ID=".$resultFetch['ID'];
	$res1 = mysqli_query($dbMasterLink,$sql1);
	
	if($res1){
        $loginfo = $loginname.' 在系统参数设置中对刷水帐号 <font class="green">'.$account.'</font>, <font class="red">更新</font> 了 UID ' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
		echo json_encode(array('status'=>0,'message'=>"更新UID成功!"));
	}else{
		echo json_encode(array('status'=>0,'message'=>'操作失败!'));
	}
	exit;
}

// 更新刷水帐号（数据网址、用户名、密码、uid），不登录正网
if($_POST['type'] == 'updateAccExNoLoginVideo'){

    if(!$_POST['uid']){
        echo json_encode(array('status'=>1,'message'=>'缺少uid！'));
        exit;
    }

    if( !$_POST['id'] || !is_numeric($_POST['id']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数id'));
        exit;
    }

    if( !$_POST['DatasiteEdt'] || !is_string($_POST['DatasiteEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数DatasiteEdt'));
        exit;
    }

    if( !$_POST['nameEdt'] || !is_string($_POST['nameEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数nameEdt'));
        exit;
    }

    if( !$_POST['passwdEdt'] || !is_string($_POST['passwdEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数passwdEdt'));
        exit;
    }

    if( !$_POST['uidEdt'] || !is_string($_POST['uidEdt']) ){
        echo json_encode(array('status'=>1,'message'=>'缺少参数uidEdt'));
        exit;
    }

    $datetime = date('Y-m-d H:i:s');
    $sql1 = "update ".DATAHGPREFIX."web_getdata_account_expand set `datetime`='".$datetime."',Datasite='".$_POST['DatasiteEdt']."',Name='".$_POST['nameEdt']."',Passwd='".$_POST['passwdEdt']."',Uid='".$_POST['uidEdt']."',status=0 where ID=".$_POST['id'];
    $res1 = mysqli_query($dbCenterMasterDbLink,$sql1);

    if($res1){
        $loginfo = $loginname.' 在系统参数设置中对刷水帐号 <font class="green">'.$account.'</font>, <font class="red">更新</font> 了 UID ' ;
        innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
        echo json_encode(array('status'=>0,'message'=>"更新（数据网址、用户名、密码、UID）成功!"));
    }else{
        echo json_encode(array('status'=>0,'message'=>'操作失败!'));
    }
    exit;
}


?>