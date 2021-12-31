<?php
session_start();
/**
 * 提款银行卡绑定&更新
 * Date: 2018/8/6
 */

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include_once('../include/config.inc.php');
include_once "../../../common/bankNameList.php";

if(!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ){
    original_phone_request_response('401.1', '请重新登录!');
}
$action_type = $_REQUEST['action_type'];
$userid = $_SESSION['userid'];

if($action_type == 'bind' || $action_type == 'reset'){
    if(empty($_POST["bank_name"]))
        exit(json_encode(['status' => 0, 'describe' => '请选择开户银行！']));
    if(empty($_POST['bank_account']))
        exit(json_encode(['status' => 0, 'describe' => '请填写银行账户！']));
    if(empty($_POST["bank_address"]))
        exit(json_encode(['status' => 0, 'describe' => '请填写银行地址！']));
//    if(!empty($_POST["usdt_address"]) && !isUsdtAddress($_POST["usdt_address"]))
//        exit(json_encode(['status' => 0, 'describe' => '抱歉，您输入的USDT地址不符合规范！']));
}

if($action_type == 'bind'){
    if(empty($_POST["pay_password"]))
        exit(json_encode(['status' => 0, 'describe' => '请填写提款密码！']));
    if(!isPayNumber($_POST["pay_password"]))
        exit(json_encode(['status' => 0, 'describe' => '提款密码不符合规范！']));
    if($_POST["pay_password"] != $_POST["pay_password2"])
        exit(json_encode(['status' => 0, 'describe' => '两次输入的提款密码不一致！']));

    $mysql="UPDATE ".DBPREFIX.MEMBERTABLE." SET Bank_Name='".$_POST["bank_name"]."', Bank_Address='".$_POST["bank_address"]."',Bank_Account='".$_POST["bank_account"]."',Address='".$_POST["pay_password"]."', Online=1, OnlineTime=now() where ID='".$userid."'"; // ,Usdt_Address='".$_POST["usdt_address"]."'
    $result = mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
    if($result){
        original_phone_request_response('200', '银行账号信息设置成功！');
    }else{
        original_phone_request_response(0, '银行账号信息设置失败！');
    }
}elseif($action_type == 'reset'){

    $mysql="UPDATE ".DBPREFIX.MEMBERTABLE." SET Bank_Name='".$_POST["bank_name"]."', Bank_Address='".$_POST["bank_address"]."',Bank_Account='".$_POST["bank_account"]."' where ID='".$userid."'"; // ,Usdt_Address='".$_POST["usdt_address"]."'
    $result = mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
    if($result){
        original_phone_request_response('200', '银行账号信息重置成功！');
    }else{
        original_phone_request_response(0, '银行账号信息重置失败！');
    }
}elseif($action_type == 'banks'){ // 获取银行卡列表
    $aBank = returnBnakName();
    original_phone_request_response('200', 'success', $aBank);
}
