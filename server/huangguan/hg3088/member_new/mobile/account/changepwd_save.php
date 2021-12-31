<?php
include_once('../include/config.inc.php');

// 连接彩票主库
$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());

$uid=$_SESSION['Oid'];
$UserName=$_SESSION['UserName']; // 登录用户名
$langx=$_SESSION['Language'];
$action=$_REQUEST['action'];

$mysql="Select ID,UserName,PassWord,Address,EditDate from ".DBPREFIX.MEMBERTABLE." where Oid='$uid'";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);

$password=$row['PassWord']; // 登录密码
$pay_password=$row['Address']; // 支付密码
$editdate=$row['EditDate'];

if ($action==1){
    $flag_action=$_REQUEST["flag_action"]; // 用户判断是更改登录密码还是支付密码
    $oldpasd = trim($_REQUEST["oldpassword"]); // 原登录密码
    $pasd = trim($_REQUEST["password"]); // 新登录密码
    if(TPL_FILE_NAME !='newhg'){ // 新皇冠不需要强制转小写
        $oldpasd = strtolower($oldpasd);
        $pasd = strtolower($pasd);
    }
    $mdoldpasd = passwordEncryption($oldpasd,$UserName);
    $mdpasd = passwordEncryption($pasd,$UserName); // md5加密后
    $pay_oldpasd=strtolower($_REQUEST["pay_oldpassword"]); // 原支付密码
    $pay_pasd=strtolower($_REQUEST["pay_password"]); // 新支付密码

    $date=date("Y-m-d");
    $todaydate=strtotime(date("Y-m-d"));
    $editdate=strtotime($editdate);
    $time=($todaydate-$editdate)/86400;
    if($flag_action =='1'){ // 修改登录密码

        if($mdoldpasd != $password){ // 原密码不正确

            $status='401.2';
            $describe = "原登录密码错误！";
            original_phone_request_response($status,$describe);
        }

        $mysql = "update ".DBPREFIX.MEMBERTABLE." set PassWord='$mdpasd',EditDate='$date' , Online=1 , OnlineTime=now() where Oid='$uid'";
        $result = mysqli_query($dbMasterLink,$mysql);
        if (!$result) {
            // 更改失败还原会员密码
            $rallbacksql = "update ".DBPREFIX.MEMBERTABLE." set PassWord='$mdoldpasd',EditDate='$date' , Online=1 , OnlineTime=now() where Oid='$uid'";
            mysqli_query($dbMasterLink,$rallbacksql);

            $status='401.3';
            $describe = "登录密码修改失败";
            original_phone_request_response($status,$describe);

        }
        $cpsql = "UPDATE gxfcy_user SET userpsw='".$mdpasd."' where hguid=".$row['ID'];
        $updateUserPass = mysqli_query($cpMasterDbLink,$cpsql);//更新彩票用户密码
        if($updateUserPass) {
            $status='200';
            $describe = "登录密码修改成功";
            original_phone_request_response($status,$describe);

//            if ($time>30){
//                echo "<Script language=javascript>alert('已成功的变更了您的密码~~请回首页重新登入');window.location='../logout.php';</script>";
//            }else{
//                echo "<Script language=javascript>alert('已成功的变更了您的密码~~请回首页重新登入');window.location='../logout.php';</script>";
//            }
        }
    }else{ // 修改支付密码
        if($pay_oldpasd != $pay_password){ // 原密码不正确

            $status='401.4';
            $describe = "原支付密码错误！";
            original_phone_request_response($status,$describe);

        }
        $mysql="update ".DBPREFIX.MEMBERTABLE." set Address='$pay_pasd',EditDate='$date', Online=1 , OnlineTime=now() where Oid='$uid'";
        $result = mysqli_query($dbMasterLink,$mysql);
        if (!$result){
            $status='401.5';
            $describe = "操作失败";
            original_phone_request_response($status,$describe);
        }
        $status='200';
        $describe = "支付密码修改成功";
        original_phone_request_response($status,$describe);

//        if ($time>30){
//            echo "<Script language=javascript>alert('已成功的变更了您的密码'); window.location='../account.php';</script>";
//        }else{
//            echo "<Script language=javascript>alert('已成功的变更了您的密码'); window.location='../account.php';</script>";
//        }
    }

}
