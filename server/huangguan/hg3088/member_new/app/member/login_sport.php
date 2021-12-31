<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "./include/address.mem.php";
include "./include/config.inc.php";

// 验证token
$token = isset($_REQUEST['key'])?$_REQUEST['key']:'';
$token2 = md5(md5(trim($_REQUEST['username'])) . '_LOGIN_SPORT');
if(empty($token) || $token2 != $token){
    exit("<script>alert('抱歉，禁止登录!');history.go(-1);</script>");
}

$redisObj = new Ciredis();
/* type : 1 全站，2 登录，3 注册，4 登录/注册 */
$datastr = $redisObj->getSimpleOne('font_ip_limit');
$datastr = json_decode($datastr,true) ;
$iptype = $datastr['type'] ;
$dataiparr = explode(';',$datastr['list']);

$date=date("Y-m-d");
$todaydate=strtotime(date("Y-m-d"));
$editdate=strtotime($demoUser['EditDate']);
$time=($todaydate-$editdate)/86400;
$datetime=strtotime(date("Y-m-d H:i:s"));
$ip_addr=get_ip();


if($iptype ==2 && in_array($ip_addr,$dataiparr) || ( $iptype ==1 && in_array($ip_addr,$dataiparr) ) || ( $iptype ==4 && in_array($ip_addr,$dataiparr) ) ){
    exit("<script>alert('你已被禁止登录!');history.go(-1);</script>");
}

if($_REQUEST['demoplay']=='Yes'){//试玩登录
    $demoSql="SELECT ID,UserName,Money,Credit,EditDate,LoginName,Agents,Language,Pay_Type,Status,Admin,Alias,World,Corprator,Super,Bank_Name,Bank_Account,Bank_Address,Phone,Notes,OpenType,OnlineTime,AddDate,pay_class,test_flag,PassWord FROM `".DBPREFIX.MEMBERTABLE."` WHERE Agents='demoguest' and Oid='logout' AND Online=0";
    $demoResult = mysqli_query($dbLink,$demoSql);
    $demoCou=mysqli_num_rows($demoResult);
    if($demoCou==0){
        $demoSql="SELECT ID,UserName,Money,Credit,EditDate,LoginName,Agents,Language,Pay_Type,Status,Admin,Alias,World,Corprator,Super,Bank_Name,Bank_Account,Bank_Address,Phone,Notes,OpenType,OnlineTime,AddDate,pay_class,test_flag,PassWord FROM `".DBPREFIX.MEMBERTABLE."` WHERE Agents='demoguest'";
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
    $_SESSION['LoginName']=$demoUser['LoginName'];
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
    $_SESSION['OnlineTime']=$demoUser['OnlineTime'];
    $_SESSION['pay_class']=$demoUser['pay_class'];
    $_SESSION['test_flag']=$demoUser['test_flag'];
    $_SESSION['password']=$demoUser['PassWord'];


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
}else{//常规登录

    if(isset($_REQUEST['sign'])){ //新版切换旧版
        $langx=$_REQUEST['langx'];
        $_SESSION['langx']=$_REQUEST['langx'];
        $username = trim($_REQUEST['username']);
        $mdpassword= trim($_REQUEST['password']);
    }else{
        $uid=$_REQUEST['uid'];
        $langx=$_REQUEST['langx'];
        $_SESSION['langx']=$langx;
        $username = trim($_REQUEST['username']);
        $password= trim($_REQUEST['password']);
        //$mdpassword = pwdEncryption($password,$username);
        $mdpassword= trim($_REQUEST['password']);
        if($username=='' || $password==''){ // 用户名或密码为空
            echo "<script>alert('登录错误！请检查用户名或密码');top.location.href='".BROWSER_IP."';</script>";
            exit;
        }
    }

    if($_SESSION["randcode"]!=trim($_REQUEST['code']) && trim($_REQUEST['code'])!='first'){
        echo "<script>alert('登录错误！请输入验证码');top.location.href='".BROWSER_IP."';</script>";
        exit;
    }

    $sql = "SELECT test_flag,pay_class,UserName,LoginName,OpenType,Pay_Type,PassWord,Credit,WinLossCredit,DepositTimes,LoginDate,OnlineTime,AddDate,EditDate,ID,Oid,Agents,Language,Status,Admin,Alias,World,Corprator,Super,Bank_Name,Bank_Account,Bank_Address,Phone,Notes,gameSwitch,ratio,CurType FROM `".DBPREFIX.MEMBERTABLE."` WHERE UserName='$username' AND PassWord='$mdpassword' AND Status<2 ";
    $result = mysqli_query($dbMasterLink,$sql);

    $row = mysqli_fetch_assoc($result);
    $cou = mysqli_num_rows($result);
    if ($cou==0){
        echo "<script>alert('登录错误！请检查用户名或密码');top.location.href='".BROWSER_IP."';</script>";
        exit;
    }else{

//        if($row['Agents']=='demoguest'&&!$_REQUEST['sign']){
//            echo "<script>alert('此账号为试玩账号,请注册正式用户!');top.location.href='".BROWSER_IP."/reg.php';</script>";
//            exit;
//        }

        $str = time('s');
        $uid=strtolower(substr(md5($str),0,10).substr(md5($username),0,10).'ra'.rand(0,9));

        $redisObj->setOne('loginuser_'.$row['ID'] , $uid);// 写入redis


        $credit=$row['Credit'];

        $_SESSION['UserName']=$row['UserName'];
        $_SESSION['LoginName']=$row['LoginName'];
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
        $_SESSION['test_flag']=$row['test_flag'];
        $_SESSION['password']=$row['PassWord'];
        $_SESSION['gameSwitch']=$row['gameSwitch'];
        $_SESSION['ratio']=$row['ratio'];
        $_SESSION['CurType']=$row['CurType'];

        $onlinetime=strtotime($row['OnlineTime']);
        $sql="update ".DBPREFIX.MEMBERTABLE." set online_status=0,Oid='$uid',LoginDate='$date', LoginTime=now(),OnlineTime=now(),Online=1,LoginIP='$ip_addr',Language='$langx',Url='".BROWSER_IP."' where UserName='$username' and Status<=1";
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
        echo "<script>top.SI2_mem_index.location = '".BROWSER_IP."/app/member/FT_index.php?mtype=3&uid=$uid&langx=$langx';</script>";

    }
}
?>
