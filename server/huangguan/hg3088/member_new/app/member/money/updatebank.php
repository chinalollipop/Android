<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid = $_REQUEST["uid"];
$bank_name = $_REQUEST['bank_name'] ; // 银行名称
$bank_address = $_REQUEST['bank_address'] ; // 开户行地址
$bank_account = $_REQUEST['bank_account'] ; // 开户行帐号
$usdt_address = $_REQUEST['usdt_address'] ; // USDT地址
$payPass = '';
if($_REQUEST['action'] == 'add'){
    $paypassword1 = $_REQUEST['paypassword1'];
    $paypassword2 = $_REQUEST['paypassword2'];
    if($paypassword1 != $paypassword2){
        exit(json_encode(['code' => -1, 'msg' => '抱歉，您输入的提款密码不一致！']));
    }
    if($paypassword1 && !isPayNumber($paypassword1)){ // 支付密码验证
        exit(json_encode(['code' => -1, 'msg' => '抱歉，支付密码不符合规范！']));
    }
    $payPass = ", Address = '$paypassword1'";
}

//if($usdt_address && !isUsdtAddress($usdt_address)){
//    exit(json_encode(['code' => -11, 'msg' => '抱歉，您输入的USDT地址不符合规范！']));
//}

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo json_encode(array('code'=>3)); // 登录信息过期
    exit;
} else{ // 更新数据
    $sql = "UPDATE `".DBPREFIX.MEMBERTABLE."` SET Bank_Name = '$bank_name' , Bank_Address = '$bank_address' , Bank_Account = '$bank_account' " . $payPass . ", Online=1 , OnlineTime=now() WHERE Oid='$uid' AND Status<2"; // , Usdt_Address = '$usdt_address'
    $result = mysqli_query($dbMasterLink,$sql);  // $dbMasterLink 主库  $dbLink 从库

}


$mysql="select UserName,Bank_Name,Bank_Account,Bank_Address,Usdt_Address from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status<2 ";
//echo $mysql; die;
$result = mysqli_query($dbMasterLink,$mysql);
// $aData = [];
while ($myrow=mysqli_fetch_assoc($result)){
    $aData=$myrow;
    $aData['Bank_Account_hide'] = returnBankAccount($myrow['Bank_Account']);
    $aData['Usdt_Address_hide'] = returnBankAccount($myrow['Usdt_Address']);
    $aData['Bank_Account'] = returnBankAccount($myrow['Bank_Account']);
    $aData['Usdt_Address'] = returnBankAccount($myrow['Usdt_Address']);
}
$aData = array('code'=>1,'resdata'=>$aData) ;
if($aData){
    echo json_encode($aData);
}else{ // 更换失败
    echo json_encode(array('code'=>2));
}


?>

