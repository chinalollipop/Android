<?php
die;
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");

// 连接彩票主库
$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());

$username=$_REQUEST['username'] ; // 用户名
$flag_action=$_REQUEST["action"]; // 用户判断是哪一级页面
$realname=$_REQUEST['realname'] ; //  真实姓名
$pay_pasd= $_REQUEST["paypassword"]; // 用户输入的支付密码
//$birthday= substr($_REQUEST["birthday"],0,10); // 用户输入的生日
$newpassword = $_REQUEST['newpassword'] ; // 新密码
$md5newpassword = passwordEncryption($newpassword,$username);
$date=date("Y-m-d");
$todaydate=strtotime(date("Y-m-d"));
$editdate=strtotime($editdate);
$time=($todaydate-$editdate)/86400;

$mysql="Select ID,Address,EditDate,Alias,password from ".DBPREFIX.MEMBERTABLE." where UserName='$username'";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$userID= $row['ID']; //用户id
$password= $row['password']; // 登录密码
$row_alias= $row['Alias']; // 真实姓名
$pay_password=$row['Address']; // 支付密码
// $row_birthday=substr($row['birthday'],0,10); // 生日
$editdate=$row['EditDate'];
$cou=mysqli_num_rows($result);

if($flag_action =='t1s'){ // 一级页面
    if($cou==0){ // 帐号不存在
        echo( json_encode( array( 'err'=>-1,'msg'=>"当前帐号不存在，请重新输入!") ) );
        exit;
    }else{ // 帐号存在
        echo( json_encode( array( 'err'=>1,'msg'=>"帐号输入正确!") ) );
        exit;
    }
}else if($flag_action =='t2s'){ // 二级页面
    if($realname !=$row_alias or  $pay_pasd != $pay_password ){ // 资料不一致
        echo( json_encode( array( 'err'=>-2,'msg'=>"您的真实姓名或取款密码有误!") ) );
        exit;
    }else{
        echo( json_encode( array( 'err'=>2,'msg'=>"信息提交正确!") ) );
        exit;
    }

}else if($flag_action =='t3s'){ // 三级页面
    $mysql="update ".DBPREFIX.MEMBERTABLE." set PassWord='$md5newpassword',EditDate='$date' , Online=1 , OnlineTime=now() where UserName='$username'";
    $memberResult = mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
    // 更改彩票会员密码
    $cpsql = "UPDATE gxfcy_user SET userpsw='".$md5newpassword."' where hguid=".$row['ID'];
    $updateUserPass = mysqli_query($cpMasterDbLink,$cpsql);//更新彩票用户密码
    if($memberResult && $updateUserPass) {
        echo( json_encode( array( 'err'=>3,'msg'=>"您的密码更改成功!") ) );
    } else {
        echo( json_encode( array( 'err'=>3,'msg'=>"您的密码更改失败，请重新修改!") ) );
    }
}


		
?>
