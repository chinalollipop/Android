<?php
/**
 * /account/bank_type_USDT_api.php
 *  USDT虚拟币入款
 *  bankid
 */

include_once('../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '401.1';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);

}

$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$username = $_SESSION['UserName'];
$bankid=$_REQUEST['bankid'];
$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式
$sSql = "SELECT id,bank_user,photo_name,deposit_address,bank_user,maxmoney,notice FROM `".DBPREFIX."gxfcy_bank_data` WHERE `id`= '$bankid' AND `bankcode` = 'USDT' AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class) AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ";
$oRes = mysqli_query($dbMasterLink,$sSql);
$iCou=mysqli_num_rows($oRes);

if( $iCou == 0 ){
    $status = '401.2';
    $describe = '支付方式有误，请重新选择~！';
    original_phone_request_response($status,$describe);
}
$aRow = mysqli_fetch_assoc($oRes);

$status = '200';
$describe = 'success';
$aData[0]['id']=$aRow['id'];
$aData[0]['bank_user']=$aRow['bank_user'];
$aData[0]['photo_name']=$aRow['photo_name'];
$aData[0]['deposit_address']=$aRow['deposit_address'];
$aData[0]['notice']=$aRow['notice'];
$aData[0]['type'] = 'TRC20';
$aData[0]['usdt_name'] = 'USDT-极速';
$aData[0]['yuhui_rate'] = '2%';
$aData[0]['min_deposit'] = '100';

$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

$aData[0]['tutorial_url'] = $http_type . $_SERVER['HTTP_HOST'].'/static/usdtjc/usdtjc.html';

original_phone_request_response($status,$describe,$aData);


