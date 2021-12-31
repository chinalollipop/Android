<?php
/**
 * 支付宝，微信二维码。手工扫码付款
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
$aData = array();
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '400.1';
    $describe = '你已退出登录，请重新登录';
    original_phone_request_response($status,$describe,$aData);
}
checkDepositOrder();
$uid=$_SESSION['uid'];
$langx=$_SESSION['langx'];
$payid = $_REQUEST['payid'];
$sSql = "select id,maxmoney,bank_name,bank_account,bank_addres,bankcode from `".DBPREFIX."gxfcy_bank_data` where FIND_IN_SET('{$_SESSION['pay_class']}',class) AND `status` = 1 and `id` = $payid ";
$aRes = mysqli_query($dbMasterLink,$sSql);
$aRow=mysqli_fetch_array($aRes);
$iCou=mysqli_num_rows($aRes);
if($iCou == 0){
    $status = '400.2';
    $describe = '支付方式有误，请重新选择';
    original_phone_request_response($status,$describe,$aData);
}

$aBankpay = $aRow;  //公司支付类型
$memo = $_REQUEST['memo'];
$bank_name = $_REQUEST['bank_user'];
$userid=$_SESSION['userid'];
$sql = "SELECT Money FROM `".DBPREFIX.MEMBERTABLE."` WHERE ID='$userid' ";
$result = mysqli_query($dbMasterLink,$sql);
$cou = mysqli_num_rows($result);
if ($cou==0){

    $status = '400.3';
    $describe = '登录错误！请检查用户名或密码';
    original_phone_request_response($status,$describe,$aData);
}
$row = mysqli_fetch_assoc($result);

$cash = $_REQUEST['v_amount'];
$moneyf = sprintf("%01.2f",$row['Money']); // 用户充值前余额
$currency_after = $moneyf+$cash; // 用户充值后的余额
$myname = $_SESSION['Alias'];
$username = $_SESSION['UserName'];
$getday = $_REQUEST['cn_date'];
if ($aBankpay['bankcode']==='USDT'){
    $getday=date('Y-m-d H:i:s');
}
if (strlen($bank_name)>255){

    $status = '400.4';
    $describe = '银行名称异常请联系客服';
    original_phone_request_response($status,$describe,$aData);
}
if (strlen($memo)>255){
    $status = '400.5';
    $describe = '订单号异常请重新输入';
    original_phone_request_response($status,$describe,$aData);
}
if (!is_numeric($cash)){
    $status = '400.6';
    $describe = '汇款金额只能输入数字';
    original_phone_request_response($status,$describe,$aData);
}


if ($cash>$aBankpay['maxmoney']){
    $status = '400.7';
    $describe = '汇款金额不能超过'.$aBankpay['maxmoney'] ;
    original_phone_request_response($status,$describe,$aData);
}
if ( $getday == ""){
    $status = '400.8';
    $describe = '您的名字和汇款日期必须填写完整' ;
    original_phone_request_response($status,$describe,$aData);
}

$agents=$_SESSION['Agents'];
$world=$_SESSION['World'];
$corprator=$_SESSION['Corprator'];
$super=$_SESSION['Super'];
$admin=$_SESSION['Admin'];
$phone=$_SESSION['Phone'];
$contact="";
// $notes=$bank_name.'-'.$memo;
$notes= $memo;  // 交易订单号后四位
$bank = $aBankpay['bank_name'];
$bank_account=$aBankpay['bank_account'];
$bank_address=$aBankpay['bank_addres'];
$order_code = date("YmdHis",time()).rand(100000,999999);
$paytype = $aBankpay['id']; // 线下银行公司入款 支付宝微信扫码 id
$payname = $aBankpay['bankcode'];
$test_flag=$_SESSION['test_flag'];
if ($aBankpay['bankcode'] =='USDT'){
    $rate = returnUsdtRate();
    $rate['usdt_amount'] = round($cash/ $rate['usdt_rate'],2);
    $IntoBank = $bank_name.'-'.$rate['usdt_rate'].'-'. $rate['usdt_amount'];
}else{
    $IntoBank = $bank_name.'-'.$notes ;
}
$sql = "insert into `".DBPREFIX."web_sys800_data` set DepositAccount='$IntoBank',userid='$userid',Checked=0,Payway='N',Gold='$cash',moneyf='$moneyf',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='$username',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$myname',Waterno='',Bank='$bank',Cancel='0',contact='$contact',Bank_Account='$bank_account',Bank_Address='$bank_address',Phone='$phone',Order_Code='$order_code',PayType='$paytype',PayName='$payname',test_flag='$test_flag'";
//echo $sql;echo '<br>';
mysqli_query($dbMasterLink,$sql) or die(mysqli_connect_error());

$status = '200';
$describe = '您好：您的汇款信息已提交成功,请等待工作人员的审核，并请于10分钟之内查询您的帐户余额。' ;
original_phone_request_response($status,$describe,$aData);

?>
