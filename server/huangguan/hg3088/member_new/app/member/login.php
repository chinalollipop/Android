<?php
session_start(); 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "./include/address.mem.php";
include "./include/config.inc.php";
include dirname(dirname(dirname(__FILE__)))."/style/tncode/TnCode.class.php";
$or_Source = 0 ; // 0 旧版
$pctip= isset($_REQUEST['topc'] )?$_REQUEST['topc']:'' ; // 从手机端跳转到pc端标志
$realname = $_REQUEST['realname'];

$redisObj = new Ciredis();
/* type : 1 全站，2 登录，3 注册，4 登录/注册 */
$datastr = $redisObj->getSimpleOne('font_ip_limit');
$datastr = json_decode($datastr,true) ;
$iptype = $datastr['type'] ;
$dataiparr = explode(';',$datastr['list']);
$datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 取redis 设置的值
$datajson = json_decode($datajson,true) ; // 第三方配置信息

$date=date("Y-m-d");
$todaydate=strtotime(date("Y-m-d"));
$editdate=strtotime($demoUser['EditDate']);
$time=($todaydate-$editdate)/86400;
$datetime=strtotime(date("Y-m-d H:i:s"));
$ip_addr=get_ip();
$langx =$_SESSION['langx']?$_SESSION['langx']:'zh-cn';

if(stripos($ip_addr,",")) {
$ip_addr_array = explode(',',$ip_addr);

foreach($ip_addr_array as $ip_addr) {
if($iptype ==2 && in_array(trim($ip_addr),$dataiparr) || ( $iptype ==1 && in_array(trim($ip_addr),$dataiparr) ) || ( $iptype ==4 && in_array(trim($ip_addr),$dataiparr) ) ){
    exit("<script>alert('你已被禁止登录');top.location.href='".BROWSER_IP."';</script>");
}
}
}else {
if($iptype ==2 && in_array(trim($ip_addr),$dataiparr) || ( $iptype ==1 && in_array(trim($ip_addr),$dataiparr) ) || ( $iptype ==4 && in_array(trim($ip_addr),$dataiparr) ) ){
    exit("<script>alert('你已被禁止登录');top.location.href='".BROWSER_IP."';</script>");
}
}


if($_REQUEST['demoplay']== 'Yes'){ //试玩登录
		if(!GUEST_LOGIN_MUST_INPUT_PHONE){
			if(LOGIN_IS_VERIFY_CODE) {
                if ($_SESSION['tncode_check'] == 'ok') {
                    $_SESSION['tncode_check'] = null;
                } else {
                    exit("<script>alert('验证码拖动错误!');top.location.href='" . BROWSER_IP . "';</script>");
                }
            }
		}
    	$or_password = 'qwertyu';

		$demoSql="SELECT ID,UserName,Money,Credit,EditDate,LoginName,Agents,Language,Pay_Type,Status,Admin,Alias,World,Corprator,Super,Bank_Name,Bank_Account,Bank_Address,Phone,Notes,OpenType,OnlineTime,AddDate,pay_class,layer,test_flag,PassWord FROM `".DBPREFIX.MEMBERTABLE."` WHERE Agents='demoguest' and Oid='logout' AND Online=0";
		$demoResult = mysqli_query($dbLink,$demoSql);
		$demoCou=mysqli_num_rows($demoResult);
		if($demoCou==0){
			$demoSql="SELECT ID,UserName,Money,Credit,EditDate,LoginName,Agents,Language,Pay_Type,Status,Admin,Alias,World,Corprator,Super,Bank_Name,Bank_Account,Bank_Address,Phone,Notes,OpenType,OnlineTime,AddDate,pay_class,layer,test_flag,PassWord FROM `".DBPREFIX.MEMBERTABLE."` WHERE Agents='demoguest'";
			$demoResult = mysqli_query($dbLink,$demoSql);
			$demoCou=mysqli_num_rows($demoResult);
			if($demoCou==0){
				echo "<script>alert('暂无空闲体验用户,请注册真实用户！');top.location.href='".BROWSER_IP."';</script>";
		    	exit;
			}
		}
		while($demoRow = mysqli_fetch_assoc($demoResult)){
			$demoUsers[$demoRow["ID"]]=$demoRow;
		}
		
		$demoUser=$demoUsers[array_rand($demoUsers)];
		
		$str = time('s');
		$uid=strtolower(substr(md5($str),0,10).substr(md5($demoUser['UserName']),0,10).'ra'.rand(0,9));
	    $redisObj->setOne('loginuser_'.$demoUser['ID'] , $uid);// 写入redis

		$credit=$demoUser['Credit'];


		//print_r($row);exit;
	
		$_SESSION['UserName']=$demoUser['UserName'];
		$_SESSION['Oid']=$uid;
		$_SESSION['userid']=$demoUser['ID'];
		
		$_SESSION['Agents']=$demoUser['Agents'];
		$_SESSION['Language']=$demoUser['Language'];
		$_SESSION['Pay_Type']=$demoUser['Pay_Type'];
		$_SESSION['Status']=$demoUser['Status'];
		$_SESSION['Admin']=$demoUser['Admin'];
		$_SESSION['Alias']=$demoUser['Alias'];
		$_SESSION['World']=$demoUser['World'];
		$_SESSION['Corprator']=$demoUser['Corprator'];
		$_SESSION['Super']=$demoUser['Super'];
        $_SESSION['Bank_Name']=$demoUser['Bank_Name'];
        $_SESSION['Bank_Account']=$demoUser['Bank_Account'];
        $_SESSION['Bank_Address']=$demoUser['Bank_Address'];
		$_SESSION['Phone']=$demoUser['Phone'];
		$_SESSION['Notes']=$demoUser['Notes'];
		$_SESSION['OpenType']=$demoUser['OpenType'];
		$_SESSION['OnlineTime']=$demoUser['OnlineTime']; // 登录时间
		$_SESSION['pay_class']=$demoUser['pay_class'];
		$_SESSION['layer']=$demoUser['layer'];
		$_SESSION['test_flag']=$demoUser['test_flag'];
		$_SESSION['password']=$demoUser['PassWord'];
    	$third_pwd = passwordThird($demoUser['UserName'],$or_password) ; // 国民第三方密码加密
		$_SESSION['thirdPassword']= $third_pwd;
		$_SESSION['third_PassWord']= ''; // 是否存在第三方密码，存在即是导过来的会员，不存在就是新注册的
		$_SESSION['originPassword']= $or_password;// 原始密码
		$_SESSION['thirdUserName']= $datajson['agentid'].'_'.$demoUser['UserName'] ;

		$onlinetime=strtotime($demoUser['OnlineTime']);
		$sql="update ".DBPREFIX.MEMBERTABLE." set online_status=0,Money=2000,Credit=0,WinLossCredit=0,Oid='$uid',LoginDate='$date', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ip_addr',Language='$langx',Url='".BROWSER_IP."' where ID=".$demoUser['ID']." and Status<=1";
		if(mysqli_query($dbMasterLink,$sql)){
            $loginArr = array(
                0=>$_SESSION['userid'] ,
                1=>$_SESSION['UserName'] ,
                2=>$_SESSION['Agents'] ,
                3=>0 , // 0会员，1代理商
                4=>0 , // 信用额度
                5=>$_SESSION['Alias']  ,
            ) ;
            addLoginIpLog($loginArr) ; // 记录登录 ip记录

            //清理测试用户历史数据
			mysqli_query($dbMasterLink,"delete from ".DBPREFIX."web_report_data where userid=".$demoUser['ID']." and M_Name='".$demoUser['UserName']."'");
			$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
			$userCpRow=mysqli_query($cpMasterDbLink,"select id from gxfcy_user where hguid=".$demoUser['ID']." and username='".$demoUser['UserName']."'");
			$userCp = mysqli_fetch_assoc($userCpRow);
			if($userCp['id']>1000){
				mysqli_query($cpMasterDbLink,"delete from gxfcy_bill where userid=".$userCp['id']." and username='".$demoUser['UserName']."'");
                mysqli_query($cpMasterDbLink,"update gxfcy_user set currency=2000,lcurrency=2000 where id=".$userCp['id']." and username='".$demoUser['UserName']."'");
			}
		
			echo "<script> 
			top.uid = '$uid';
			top.langx = '$langx';
			//top.liveid = '$liveid';
			top.casino = 'SI2';
			</script>";
			echo "<script>top.SI2_mem_index.location = '".BROWSER_IP."/app/member/FT_index.php?mtype=3&uid=$uid&langx=$langx';</script>";
			
		}else{
			echo "<script>alert('暂无空闲体验用户,请注册真实用户！');top.location.href='".BROWSER_IP."';</script>";
		    exit;	
		}
}else{ //常规登录
    $username = trim($_REQUEST['username']);
    $or_password= trim($_REQUEST['password']);
//	if(isset($_REQUEST['sign']) && $_REQUEST['sign']){ //新版切换旧版, 不需要验证码
//        $mdpassword= $or_password;
//	}else{
        $mdpassword = passwordEncryption($or_password,$username);
        if($username=='' || $or_password==''){ // 用户名或密码为空
            echo "<script>alert('登录错误！请检查用户名或密码');top.location.href='".BROWSER_IP."';</script>";
            exit;
        }

        if(LOGIN_IS_VERIFY_CODE) {
            // 新增验证码
            $yzm_input = $_REQUEST['yzm_input'];
            if(!$yzm_input){
                exit("<script>alert('请输入验证码!');top.location.href='".BROWSER_IP."';</script>");
            }

            $tn = new TnCode();
            if ($_SESSION['tncode_check'] == 'ok') {
                $_SESSION['tncode_check'] = null;
            } else {
                exit("<script>alert('验证码输入错误!');top.location.href='" . BROWSER_IP . "';</script>");
            }
        }

	// }
    // 导入创富会员，重名的用户需要把前缀去掉，来生成彩票登录的密码
    if (SAME_USERNAME_ADD_PREFIX )
    {
        // 用户名不带指定前缀的生成密码
        if (strpos($username, SAME_USERNAME_PREFIX) === false){
            $third_pwd = passwordThird($username,$or_password) ;
        }
        else{// 用户名自带指定前缀的，需要去掉前缀，然后生成密码
            $username_for_pw = explode(SAME_USERNAME_PREFIX, $username)[1];
            $third_pwd = passwordThird($username_for_pw,$or_password) ;
        }
    }
    else{
        $third_pwd = passwordThird($username,$or_password) ; // 国民第三方密码加密
    }


	// $sql = "SELECT test_flag,pay_class,layer,UserName,LoginName,OpenType,Pay_Type,PassWord,Credit,WinLossCredit,DepositTimes,LoginDate,OnlineTime,AddDate,EditDate,ID,Oid,Agents,Language,Status,Admin,Alias,World,Corprator,Super,Bank_Name,Bank_Account,Bank_Address,Phone,Notes,gameSwitch,ratio,CurType FROM `".DBPREFIX.MEMBERTABLE."` WHERE UserName='$username' AND PassWord='$mdpassword' AND Status<2 ";
	$sql = "SELECT test_flag,pay_class,layer,LoginName,OpenType,Pay_Type,PassWord,third_PassWord,Credit,WinLossCredit,DepositTimes,LoginDate,OnlineTime,AddDate,EditDate,ID,Oid,Agents,Language,Status,loginTimesOfFail,resetTimesOfFail,isAutoFreeze,AutoFreezeDate,
Admin,Alias,World,Corprator,Super,Bank_Name,Bank_Account,Bank_Address,Phone,Notes,gameSwitch,ratio,CurType FROM `".DBPREFIX.MEMBERTABLE."` WHERE UserName='$username' AND Status<3 ";
	$result = mysqli_query($dbLink,$sql);
	$row = mysqli_fetch_assoc($result);
	$cou = mysqli_num_rows($result);

    if ($row['Status']==1){
        if ($row['isAutoFreeze']==0){
            $describe = "您的账号被冻结，请与在线客服联系。";
            exit('<script type="text/javascript"> alert("'.$describe.'");top.location.href="/";</script>');
        }
        else{
            // 冻结24小时解冻
            if ((time()-strtotime($row['AutoFreezeDate']))>24*60*60){
                $sql="update ".DBPREFIX.MEMBERTABLE." set Status=0,loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00' where UserName='$username'";
                $res = mysqli_query($dbMasterLink,$sql);
                $row['Status']=0;
            }
            else{ // 冻结24小时内
                $describe = "您的账号已被冻结使用24小时，请与在线客服联系。";
                exit('<script type="text/javascript"> alert("'.$describe.'");top.location.href="/";</script>');
            }
        }
    }

    if ($row['Status']==2){ // 账号停用
        $describe = "由于阁下账号长时间未登陆，阁下账号已停用，请您联系客服进行启用，谢谢!";
        exit('<script type="text/javascript"> alert("'.$describe.'");top.location.href="/";</script>');
    }

    $describe = "登录错误！请检查用户名或密码！";
    if($row['PassWord']){ // 体育这边存在密码
        if($row['PassWord'] !=$mdpassword){

            $nowdate = date('Y-m-d H:i:s');
            $iloginTimesOfFail = $row['loginTimesOfFail']+1; // 失败次数
            // 登录失败大于等于5次，自动冻结
            if ($iloginTimesOfFail>=5){
                $sql="update ".DBPREFIX.MEMBERTABLE." set Status=1,loginTimesOfFail=".$iloginTimesOfFail.",isAutoFreeze=1,AutoFreezeDate='".$nowdate."' where UserName='$username'";
                $res = mysqli_query($dbMasterLink,$sql);
            }else{
                // 小于5次，更新失败次数和时间
                $sql="update ".DBPREFIX.MEMBERTABLE." set loginTimesOfFail=".$iloginTimesOfFail.",isAutoFreeze=1,AutoFreezeDate='".$nowdate."' where UserName='$username'";
                $res = mysqli_query($dbMasterLink,$sql);
            }

            if ($iloginTimesOfFail>=5){
                $describe = "您的账号已被冻结使用24小时，请与在线客服联系。";
            }
            else{
                $describe = "用户名或密码错误,您还有".(5-$iloginTimesOfFail)."次机会";
            }

            exit('<script type="text/javascript"> alert("'.$describe.'");top.location.href="/";</script>');
        }
    }else{ // 从第三方导入的会员
        if ( !passwordThirdCheck($third_pwd,$row['third_PassWord']) ){
            exit('<script type="text/javascript"> alert("'.$describe.'");top.location.href="/";</script>');
        }
    }

    $loginVerifyRealname = getSysConfig('login_verify_realname');
    if ($loginVerifyRealname == 1){
        if ($row['Alias'] != $realname){
            exit("<script>alert('登录错误！请输入正确的账户名字！');top.location.href='/';</script>");
        }
    }

    // 判断会员状态是否启用，否则退出
    if ($row['Status'] != 0) {
        echo "<script>alert('非常抱歉，您的账号已冻结或已停用，请您联系客服！');top.location.href='/';</script>";
        exit;
    }

    // 导入过来的彩票平台老用户，是否增加代理前缀（以全新用户来登录），TRUE 需要前缀，FALSE 不需要前缀
    if (USERNAME_NEED_CP_AGENT_PREFIX){
        $third_UserName = $datajson['agentid'].'_'.$username;
    }else{
        if(!$row['third_PassWord']){ // 体育这边新注册的帐号
            $third_UserName = $datajson['agentid'].'_'.$username ;
        }else{
            $third_UserName = $username ;
        }
    }

		if($row['Agents']=='demoguest'&&!$_REQUEST['sign']){
			echo "<script>alert('此账号为试玩账号,请注册正式用户!');top.location.href='".BROWSER_IP."/reg.php';</script>";
			exit;
		}
		
		$str = time('s');
		$uid=strtolower(substr(md5($str),0,10).substr(md5($username),0,10).'ra'.rand(0,9));
	
	    $redisObj->setOne('loginuser_'.$row['ID'] , $uid);// 写入redis
	

		$credit=$row['Credit'];

		$_SESSION['UserName']= $username;
		$_SESSION['Oid']=$uid;
		$_SESSION['userid']=$row['ID'];
        $_SESSION['DepositTimes']=$row['DepositTimes']; //存款次数
		$_SESSION['Agents']=$row['Agents'];
		$_SESSION['Language']=$row['Language'];
		$_SESSION['Pay_Type']=$row['Pay_Type'];
		$_SESSION['Status']=$row['Status'];
		$_SESSION['Admin']=$row['Admin'];
		$_SESSION['Alias']=$row['Alias'];
		$_SESSION['World']=$row['World'];
		$_SESSION['Corprator']=$row['Corprator'];
		$_SESSION['Super']=$row['Super'];
        $_SESSION['Bank_Name']=$row['Bank_Name'];
        $_SESSION['Bank_Account']=$row['Bank_Account'];
        $_SESSION['Bank_Address']=$row['Bank_Address'];
		$_SESSION['Phone']=$row['Phone'];
		$_SESSION['Notes']=$row['Notes'];
		$_SESSION['OpenType']=$row['OpenType'];
		$_SESSION['OnlineTime']=$row['OnlineTime'];
        $_SESSION['AddDate']=$row['AddDate'];
		$_SESSION['pay_class']=$row['pay_class'];
		$_SESSION['layer']=$row['layer'];
		$_SESSION['test_flag']=$row['test_flag'];
		$_SESSION['password']=$row['PassWord'];
		$_SESSION['thirdPassword']= $third_pwd;
		$_SESSION['third_PassWord']= $row['third_PassWord']?$row['third_PassWord']:''; // 是否存在第三方密码，存在即是导过来的会员，不存在就是新注册的
		$_SESSION['thirdUserName']= $third_UserName;
		$_SESSION['originPassword']= $or_password;// 原始密码
		$_SESSION['gameSwitch']=$row['gameSwitch'];
		$_SESSION['ratio']=$row['ratio'];
		$_SESSION['CurType']=$row['CurType'];

		$onlinetime=strtotime($row['OnlineTime']);
		if($row['PassWord']){ // 有登录密码
			$sql="update ".DBPREFIX.MEMBERTABLE." set online_status='$or_Source',Oid='$uid',Status=0,loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00', LoginDate='$date', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ip_addr',Language='$langx',Url='".BROWSER_IP."' where UserName='$username' and Status<=1";
		}else{ // 没有登录密码
			$sql="update ".DBPREFIX.MEMBERTABLE." set PassWord='$mdpassword',online_status='$or_Source',Oid='$uid',Status=0,loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00', LoginDate='$date', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ip_addr',Language='$langx',Url='".BROWSER_IP."' where UserName='$username' and Status<=1";
		}
		$res = mysqli_query($dbMasterLink,$sql) or die ("error!");

        $loginArr = array(
            0=>$_SESSION['userid'] ,
            1=>$_SESSION['UserName'] ,
            2=>$_SESSION['Agents'] ,
            3=>0 , // 0会员，1代理商
            4=>$row['WinLossCredit'] , // 信用额度
            5=>$_SESSION['Alias']  ,
        ) ;
		 addLoginIpLog($loginArr) ; // 记录登录 ip记录

		echo "<script> 
			top.uid = '$uid';
			top.langx = '$langx';
			//top.liveid = '$liveid';
			top.casino = 'SI2';
			</script>";
        echo "<script>top.location = '/?topc=$pctip';</script>";
//		if(isset($_REQUEST['sign']) && $_REQUEST['sign']){ // 新版登录
//            echo "<script>top.location = '/';</script>";
//		}else{
//            echo "<script>top.SI2_mem_index.location = '".BROWSER_IP."/app/member/FT_index.php?mtype=3&uid=$uid&langx=$langx';</script>";
//		}

		

}
?>
