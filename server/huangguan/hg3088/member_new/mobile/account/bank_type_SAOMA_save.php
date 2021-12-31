<?php
/**
 * /account/bank_type_SAOMA_save.php
 * 支付宝二维码。手工扫码付款
 */
include_once('../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '401.1';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);

}
checkDepositOrder();
$uid=$_SESSION['uid'];
$langx=$_SESSION['Language'];
$payid = $_REQUEST['payid'];
$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式
$sSql = "select id,maxmoney,bank_name,bank_account,bank_addres,bankcode from `".DBPREFIX."gxfcy_bank_data` where FIND_IN_SET('{$_SESSION['pay_class']}',class) AND `status` = 1 AND `id` = $payid  AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ";
$aRes = mysqli_query($dbLink,$sSql);
$aRow=mysqli_fetch_array($aRes);
$iCou=mysqli_num_rows($aRes);
if($iCou == 0){
    $status = '401.2';
    $describe = '支付方式有误，请重新选择~！';
    original_phone_request_response($status,$describe);

}

$aBankpay = $aRow;
$memo = $_REQUEST['memo'];
$bank_name = $_REQUEST['bank_user'];
$userid=$_SESSION['userid'];

$sql = "SELECT Money FROM `".DBPREFIX.MEMBERTABLE."` WHERE ID='$userid' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);

$cash = sprintf("%01.2f",$_REQUEST['v_amount']); // 充值金额
if ($cash<100){
    $status = '401.7';
    $describe = '汇款金额不能小于100元';
    original_phone_request_response($status,$describe);
}

$moneyf = sprintf("%01.2f",$row['Money']); // 用户充值前余额
$currency_after = $moneyf+$cash; // 用户充值后的余额

$myname = $_SESSION['Alias'];
$username = $_SESSION['UserName'];
$getday = $_REQUEST['cn_date'];
if ($aBankpay['bankcode']==='USDT'){
    $getday=date('Y-m-d H:i:s');
}
if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) { // 客户端汇款时间减12h， 和PC m版统一
    $getday = date('Y-m-d H:i:s' , strtotime($getday) - 12*60*60);
}


if (strlen($bank_name)>255){
    $status = '401.3';
    $describe = '银行名称异常请联系客服！';
    original_phone_request_response($status,$describe);

}
if (strlen($memo)>255){
    $status = '401.4';
    $describe = '订单号异常请重新输入！';
    original_phone_request_response($status,$describe);

}
if (!is_numeric($cash)){
    $status = '401.5';
    $describe = '汇款金额只能输入数字！';
    original_phone_request_response($status,$describe);

}
if ( $getday == ""){
    $status = '401.6';
    $describe = '您的名字和汇款日期必须填写完整！';
    original_phone_request_response($status,$describe);

}

$agents=$_SESSION['Agents'];
$world=$_SESSION['World'];
$corprator=$_SESSION['Corprator'];
$super=$_SESSION['Super'];
$admin=$_SESSION['Admin'];
$phone=$_SESSION['Phone'];
$contact="";
// $notes=$bank_name.'-'.$memo;
$notes= $memo;
$bank = $aBankpay['bank_name'];
$bank_account=$aBankpay['bank_account'];
$bank_address=$aBankpay['bank_addres'];
$order_code = date("YmdHis",time()).rand(100000,999999);
$paytype = $aBankpay['id']; // 公司入款汇款银行id
$payname = $aBankpay['bankcode'];
$test_flag=$_SESSION['test_flag'];
if ($aBankpay['bankcode'] =='USDT'){
    $rate = returnUsdtRate();
    $rate['usdt_amount'] = round($cash/ $rate['usdt_rate'],2);
    $IntoBank = $bank_name.'-'.$rate['usdt_rate'].'-'. $rate['usdt_amount'];
}else{
    $IntoBank = $bank_name.'-'.$notes ;
}
$sql = "insert into `".DBPREFIX."web_sys800_data` set  DepositAccount='$IntoBank',userid='$userid',Checked=0,Payway='N',Gold='$cash',moneyf='$moneyf',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='$username',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$myname',Waterno='',Bank='$bank',Cancel='0',contact='$contact',Bank_Account='$bank_account',Bank_Address='$bank_address',Phone='$phone',Order_Code='$order_code',PayType='$paytype',PayName='$payname',test_flag='$test_flag'";
//echo $sql;echo '<br>';
$id = mysqli_query($dbMasterLink,$sql);

if ($id){

    $status = '200';
    $describe = '您好：您的汇款信息已提交成功,请等待工作人员的审核，并请于10分钟之内查询您的帐户余额。';
    original_phone_request_response($status,$describe);

}else{

    $status = '500';
    $describe = '入款申请异常，请重试';
    original_phone_request_response($status,$describe);

}