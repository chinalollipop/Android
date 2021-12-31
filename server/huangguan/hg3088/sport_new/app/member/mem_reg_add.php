<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("include/config.inc.php");
require ("include/address.mem.php");
include dirname(dirname(dirname(__FILE__)))."/style/tncode/TnCode.class.php";

$redisObj = new Ciredis();
/* type : 1 全站，2 登录，3 注册，4 登录/注册 */
$datastr = $redisObj->getSimpleOne('font_ip_limit');
$datastr = json_decode($datastr,true) ;
$iptype = $datastr['type'] ;
$dataiparr = explode(';',$datastr['list']);

$datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 取redis 设置的值
$datajson = json_decode($datajson,true) ; // 第三方配置信息

$alias_allows_duplicate = getSysConfig('alias_allows_duplicate'); // 验证会员昵称是否重复

$aData = array();

$intr = isset($_REQUEST['introducer'])?$_REQUEST['introducer']:'';  // 介绍人
$main_host = getMainHost();
if ($intr=='' || $intr =='underfind'){
	$agent= DEFAULT_AGENT; // 默认代理
}else{
	$agent=$intr;
}
$or_Source = 22 ; // 22 综合版

$keys=$_REQUEST['keys'];
if ($keys=='add'){ // 注册优化-20181009
    $AddDate=date('Y-m-d H:i:s');//新增日期
    $EditDate=date('Y-m-d');//修改日期
    $alias=$_REQUEST['alias'];// 真实姓名
    $phone=$_REQUEST['phone']; //手机
    $wechat = $_REQUEST['wechat']; // 微信（增加微信、QQ注册选择-20200115）
    $qq = $_REQUEST['qq']; // QQ
    $username= trim($_REQUEST['username']);//帐号
    $password= trim($_REQUEST['password']);//密码
    $password2= trim($_REQUEST['password2']);// 确认密码
    $thirdLottery = isset($_REQUEST['thirdLottery'])?$_REQUEST['thirdLottery']:'' ; // 第三方 彩票code

//    if($thirdLottery){
//        $username = $thirdLottery.'_'.$username ;
//    }

    $source= isset($_REQUEST['know_site'])?$_REQUEST['know_site']:'';// 备注 notes 替换成 know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
//    $birthday = $_REQUEST['birthday'];
//    $question = $_REQUEST['question'];
//    $answer = $_REQUEST['answer'];
//    $w_url = $_POST['website'];// 注册来源网址
//    $paypassword=$_REQUEST['paypassword'];// 取款密码
//    $ratio=$_REQUEST['radio'];// 性别

    $ip_addr = get_ip();

    if(strlen($ip_addr)>0){
        $ip_addr_array = explode(',',$ip_addr);
        if(count($ip_addr_array)==1){
            if($iptype ==3 && in_array($ip_addr,$dataiparr) || ( $iptype ==1 && in_array($ip_addr,$dataiparr) ) || ( $iptype ==4 && in_array($ip_addr,$dataiparr) ) ){
                $m_status='501.1';
                $describe = "你已被禁止注册!";
                original_phone_request_response($m_status, $describe, '');
            }
        }elseif(count($ip_addr_array)>1){
            foreach($ip_addr_array as $ipk=>$ipval){
                if($iptype ==3 && in_array($ipval,$dataiparr) || ( $iptype ==1 && in_array($ipval,$dataiparr) ) || ( $iptype ==4 && in_array($ipval,$dataiparr) ) ){
                    $m_status='501.1';
                    $describe = "你已被禁止注册!";
                    original_phone_request_response($m_status, $describe, '');
                }
            }
        }
    }

    // 新增验证码
    if(!$_REQUEST['verifycode']){
        $status = '400.2';
        $describe = "请输入验证码！";
        original_phone_request_response($status,$describe,$aData);
       // exit("<script>alert('请输入验证码!');history.go(-1);</script>");

    }

    if(LOGIN_IS_VERIFY_CODE) {
        $tn = new TnCode();
        if ($_SESSION['tncode_check'] == 'ok') {
            $_SESSION['tncode_check'] = null;
        } else {
            $status = '400.3';
            $describe = "验证码输入错误！";
            original_phone_request_response($status, $describe, $aData);
        }
    }

    publicRegValidate($username,$intr,$password,$password2,$alias,$paypassword,$phone,$wechat,'','','api');

$sql = "select  ID,UserName,World,Corprator,Super,Admin,Sports,Lottery from ".DBPREFIX."web_agents_data where UserName='$agent'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cous = mysqli_num_rows($result);
if($cous==0){
// $agent='ddm999'; // ddm999 没有这个代理

    $status = '400.4';
    $describe = "您输入的推荐代理 $agent 不存在。请查证输入的浏览地址正确登记，谢谢！";
    original_phone_request_response($status,$describe,$aData);
}
$agent = $row['UserName'];
$agent_url = '' ;
$thisurl = getMainHost() ; // 获取当前url $_SERVER["HTTP_HOST"]
$urlsql = "select  ID,UserName,World,Corprator,Super,Admin,Sports,Lottery from ".DBPREFIX."web_agents_data where agent_url LIKE '%$thisurl%'"; // 匹配当前域名
$urlresult = mysqli_query($dbLink,$urlsql);
$urlcou = mysqli_num_rows($urlresult);
$urlrow = mysqli_fetch_assoc($urlresult);
if($urlcou>0){
    $agent = $urlrow['UserName'] ;
    $agent_url = $thisurl ;
    $row = $urlrow;
}

$world=$row['World'];
$corprator=$row['Corprator'];
$super=$row['Super'];
$admin=$row['Admin'];
$sports=$row['Sports'];
$lottery=$row['Lottery'];


$agent == TEST_AGENT ? $test_flag = 1 : $test_flag = 0; // 判断是否测试代理线

    if ($alias_allows_duplicate && !empty($alias)){
        $msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where Alias='$alias'";
        $mresult = mysqli_query($dbMasterLink,$msql);
        $mcou = mysqli_num_rows($mresult);
        if ($mcou>0){
            $status = '400.5';
            $describe = "真实姓名【{$alias}】已存在，请联系在线客服进行处理";
            original_phone_request_response($status,$describe,$aData);
        }
    }

$msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where UserName='$username'";
$mresult = mysqli_query($dbMasterLink,$msql);
$mcou = mysqli_num_rows($mresult);

if ($mcou>0){
//		echo "<script languag='JavaScript'>alert('帐户已经有人使用，请重新注册！');history.go(-1);</script>";
//		exit();
    $status = '400.5';
    $describe = "帐户已经有人使用，请重新注册！";
    original_phone_request_response($status,$describe,$aData);
}else{
$pay_class = 'a' ; // 支付分层，默认未分层 a
$Pay_Type = '1' ; // 用户所属盘口全部为D
$langx = 'zh-cn' ;
$mdpasswd = passwordEncryption($password,$username);

$sql="insert into ".DBPREFIX.MEMBERTABLE." set ";
$sql.="UserName='".$username."',";
$sql.="LoginName='".$username."',";
$sql.="PassWord='".$mdpasswd."',";
$sql.="Credit='0',";
$sql.="Money='0',";
$sql.="test_flag='".$test_flag."',";
$sql.="Alias='".$alias."',";
$sql.="Sports='".$sports."',";
$sql.="Lottery='".$lottery."',";
$sql.="AddDate='".$AddDate."',";
$sql.="EditDate='".$EditDate."',";
$sql.="Status='0',";
$sql.="CurType='RMB',";
$sql.="pay_class='".$pay_class."',"; // 支付分层，默认未分层 a
$sql.="Pay_Type='".$Pay_Type."',";
$sql.="Opentype='".REG_OPEN_TYPE."',"; // 用户所属盘口全部为D
$sql.="Agents='".$agent."',";
$sql.="agent_url='".$agent_url."',"; // 代理线
$sql.="World='".$world."',";
$sql.="Corprator='".$corprator."',";
$sql.="Super='".$super."',";
$sql.="Admin='".$admin."',";
$sql.="Phone='".$phone."',";
$sql.="E_Mail='".$wechat."',"; // 这个字段用于微信
$sql.="QQ='".$qq."',";
$sql.="Source='".$source."',";
$sql.="Language='".$langx."',";
//$sql.="birthday='".$birthday."',";
//$sql.="question='".$question."',";
//$sql.="answer='".$answer."',";
//$sql.="Url='".$w_url."',";
//$sql.="Address='".$paypassword."',";
//$sql.="ratio='".$ratio."',";
$sql.="RegisterIP='".$ip_addr."',";
$sql.="regSource='".$or_Source."',";
$sql.="Reg='1' ";

$beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
if(mysqli_query($dbMasterLink,$sql)) {

    // 注册成功，自动登录。并返回基本信息
    $sea_sql = "SELECT ID,Status,Notes,birthday FROM `".DBPREFIX.MEMBERTABLE."`  WHERE UserName='$username' AND PassWord='$mdpasswd' AND Status<2 ";
    $sea_result = mysqli_query($dbMasterLink,$sea_sql);
    $sea_row = mysqli_fetch_assoc($sea_result);
    $sea_cou = mysqli_num_rows($sea_result);

    if($sea_cou == 0){
        $status = '400.6';
        $describe = "登录失败！";
        original_phone_request_response($status,$describe,$aData);
    }else{

        $str = time('s');
        $uid = strtolower(substr(md5($str), 0, 10) . substr(md5($username), 0, 10) . 'ra' . rand(0, 9));

        $redisObj->setOne('loginuser_' . $sea_row['ID'], $uid);// 写入redis

        $loginsql = "update " . DBPREFIX.MEMBERTABLE." set online_status=$or_Source,Oid='$uid',LoginDate='$EditDate', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ip_addr',Url='" . BROWSER_IP . "' where UserName='$username' and Status<=1";
        $loginres = mysqli_query($dbMasterLink, $loginsql);

        if ($loginres) {
            $_SESSION['UserName']= $username ;
            $_SESSION['Oid']= $uid;
            $_SESSION['userid']=$sea_row['ID'];
            $_SESSION['DepositTimes']= '0' ; //存款次数
            $_SESSION['Agents']= $agent ;
            $_SESSION['Pay_Type']= $Pay_Type ;
            $_SESSION['Status']=$sea_row['Status'];
            $_SESSION['Admin']= $admin ;
            $_SESSION['Alias']= '';
            $_SESSION['payPassword']=$row['Address']; // 支付密码
            $_SESSION['World']= $world ;
            $_SESSION['Corprator']= $corprator ;
            $_SESSION['Super']= $super ;
            $_SESSION['Bank_Name']= '';
            $_SESSION['Bank_Account']= '';
            $_SESSION['Bank_Address']= '';
            $_SESSION['Usdt_Address']= '';
            $_SESSION['Phone']= $phone ;
            $_SESSION['Notes']=$sea_row['Notes'];
            $_SESSION['OpenType']= REG_OPEN_TYPE ;
            $_SESSION['OnlineTime']= $AddDate ; // 登录时间
            $_SESSION['AddDate']= $AddDate ;
            $_SESSION['pay_class'] = $pay_class;
            $_SESSION['test_flag']= $test_flag ;
            $_SESSION['password']= $mdpasswd ;
            $_SESSION['gameSwitch']= '';
            $_SESSION['CurType']= 'RMB';
            $_SESSION['E_Mail']='';
            $_SESSION['birthday']=$row['birthday'];
            $_SESSION['membermessage']= getMemberMessage($username,'0'); // 系统短信
            $_SESSION['thirdPassword'] = passwordThird($username,$password) ;
            $_SESSION['third_PassWord']= ''; // 是否存在第三方密码，存在即是导过来的会员，不存在就是新注册的
            $_SESSION['originPassword']= $password;// 原始密码
            $_SESSION['thirdUserName']= $datajson['agentid'].'_'.$username;

            $loginArr = array(
                0=>$_SESSION['userid'] ,
                1=>$_SESSION['UserName'] ,
                2=>$_SESSION['Agents'] ,
                3=>0 , // 0会员，1代理商
                4=>0 , // 信用额度
                5=>$_SESSION['Alias']  ,
            ) ;
            addLoginIpLog($loginArr) ; // 记录登录 ip记录

        } else {
//            echo "<script languag='JavaScript'>alert('更新状态失败！');history.go(-1);</script>";
//            die ("操作失败!!!");
            $status = '400.7';
            $describe = "更新状态失败！";
            original_phone_request_response($status,$describe,$aData);
        }

    }

		$mysql="update ".DBPREFIX."web_agents_data set Count=Count+1 where UserName='$agent'";
		if(mysqli_query($dbMasterLink,$mysql)) {
			mysqli_query($dbMasterLink, "COMMIT");
		}else {
			mysqli_query($dbMasterLink,"ROLLBACK");
			die ("操作失败!!!");
		}
}else {
	mysqli_query($dbMasterLink,"ROLLBACK");
	die ("操作失败!!!");
}

    $status = '200';
    $describe = "注册成功！";
    $aData = array(
         'username'=> $username,
         'password'=> $password,
         'phone'=> $phone,
    );
    original_phone_request_response($status,$describe,$aData);

}
}
?>
