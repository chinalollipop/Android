<?php
/**
 * /account/bank_type_ALISAOMA_api.php
 *  公司入款-支付宝二维码
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
$sSql = "SELECT id,bank_user,photo_name,bank_user,maxmoney,notice FROM `".DBPREFIX."gxfcy_bank_data` WHERE `id`= '$bankid' AND `bankcode` = 'ALISAOMA' AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class) AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ";
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
$aData[0]['notice']=$aRow['notice'];
original_phone_request_response($status,$describe,$aData);


