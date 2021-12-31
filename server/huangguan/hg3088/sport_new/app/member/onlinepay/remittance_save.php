<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$payid = $_REQUEST['payid'];
$cardName=$_REQUEST['v_Name'];
$memo = $_REQUEST['memo'];
$InType = isset($_REQUEST['InType'])?$_REQUEST['InType']:''; // 汇款方式
$money = $_REQUEST['v_amount'];
$cn_date = $_REQUEST['cn_date'];
$IntoBank = isset($_REQUEST['IntoBank'])?$_REQUEST['IntoBank']:''; // 汇款银行
if($InType=='0'){ // 其他汇款方式
    $InType = isset($_REQUEST['v_type'])?$_REQUEST['v_type']:'' ;
}

$aData = array() ;

// 默认查询当天的数据
$m_date=date('Y-m-d');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '400.1';
    $describe = '你的登录信息已过期!';
    original_phone_request_response($status,$describe,$aData);
}

checkDepositOrder();

require ("../include/traditional.$langx.inc.php");

$randomNum = $_REQUEST['randomnum']; // 随机整数
if(!$randomNum){
    $status = '400.2';
    $describe = '参数不对!';
    original_phone_request_response($status,$describe,$aData);
}
if($randomNum == $_SESSION['randomNum']){ // 重复提交

    $status = '400.3';
    $describe = '请不要重复提交!';
    original_phone_request_response($status,$describe,$aData);
}else { // 正常提交
    $_SESSION['randomNum'] = $randomNum;

$sSql = "select id,bank_name,bank_account,bank_addres,bankcode from `".DBPREFIX."gxfcy_bank_data` where FIND_IN_SET('{$_SESSION['pay_class']}',class) AND `status` = 1 and `id` = $payid ";
$aRes = mysqli_query($dbLink,$sSql);

$aRow=mysqli_fetch_array($aRes);
$iCou=mysqli_num_rows($aRes);
if($iCou == 0){
    $status = '400.04';
    $describe = '支付方式有误，请重新选择!';
    original_phone_request_response($status,$describe,$aData);
}

$aBankpay = $aRow;



$userid=$_SESSION['userid'];
$sql = "SELECT Money FROM `".DBPREFIX.MEMBERTABLE."` WHERE ID='$userid' ";
$result = mysqli_query($dbLink,$sql);
$cou = mysqli_num_rows($result);
if ($cou==0){

    $status = '400.4';
    $describe = '登录错误！请检查用户名或密码!';
    original_phone_request_response($status,$describe,$aData);
}
$row = mysqli_fetch_assoc($result);
$cash = $money; // 充值的额度
$moneyf = $row['Money']; // 用户充值前余额
$currency_after = $row['Money']+$money; // 用户充值后的余额
$realName = $_SESSION['Alias'];
$username = $_SESSION['UserName'];
$getday = $cn_date;

if (!is_numeric($cash)){
    $status = '400.5';
    $describe = '汇款金额只能输入数字!';
    original_phone_request_response($status,$describe,$aData);
}

if ($cardName == "" || $getday == ""){
    $status = '400.6';
    $describe = '您的名字和汇款日期必须填写完整!';
    original_phone_request_response($status,$describe,$aData);
}


$agents=$_SESSION['Agents'];
$world=$_SESSION['World'];
$corprator=$_SESSION['Corprator'];
$super=$_SESSION['Super'];
$admin=$_SESSION['Admin'];
$phone=$_SESSION['Phone'];
$contact="";
// $notes=$InType.'-'.$memo;
$notes=$memo; // 备注
$bank = $aBankpay['bank_name'];
$bank_account=$aBankpay['bank_account'];
$bank_address=$aBankpay['bank_addres'];
$order_code = date("YmdHis",time()).rand(100000,999999);
$paytype = $aBankpay['id']; // 公司入款汇款银行id
$payname = $aBankpay['bankcode'];
$test_flag=$_SESSION['test_flag'];
$sql = "insert into `".DBPREFIX."web_sys800_data` set DepositAccount='$IntoBank',InType='$InType',userid='$userid',Checked=0,Payway='N',Gold='$cash',moneyf='$moneyf',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='$username',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',CardName='$cardName',Name='$realName',Waterno='',Bank='$bank',Cancel='0',contact='$contact',notes='$notes',Bank_Account='$bank_account',Bank_Address='$bank_address',Phone='$phone',Order_Code='$order_code',PayType='$paytype',PayName='$payname',test_flag='$test_flag'";
$res = mysqli_query($dbMasterLink,$sql);
if (!$res){
    $status = '400.6';
    $describe = '汇款信息提交失败!';
    original_phone_request_response($status,$describe,$aData);
}

    $status = '200';
    $describe = '您好：您的汇款信息已提交成功,请等待工作人员的审核，并请于10分钟之内查询您的帐户余额!';
    original_phone_request_response($status,$describe,$aData);

}

?>







