<?php

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}

//@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/flg_notify_url.log');
@error_log(date('Y-m-d H:i:s').'-php://input:'.file_get_contents("php://input").PHP_EOL, 3, '/tmp/flg_notify_url.log');

/*$_REQUEST = array(9) {
  ["merId"]=>
  string(8) "20190956"
  ["orderId"]=>
  string(26) "flgzfb20191008174739976821"
  ["sysOrderId"]=>
  string(19) "1570528059216448375"
  ["desc"]=>
  string(7) "feiligu"
  ["orderAmt"]=>
  string(6) "401.00"
  ["status"]=>
  string(1) "1"
  ["nonceStr"]=>
  string(32) "YWbrDhOxf6Zv7j4ylE2o9psJX5kg3KSd"
  ["attch"]=>
  string(34) "hongyancs-97-51019-Alipay_QRcode-5"
  ["sign"]=>
  string(344) "iCKHkGFEJdKwQOYcUIhnAb+YseMFX5XJlvDCCgkbgydce7JTcRMYM2h7qbeJ1qQBZVpcWILlL7AMvGteCxKLWIBo0iWtasGMFuc5VYbczvJtuDHZFhZuqxb4Ey1bzTtl928OomgYBee3Ulcav4gerDh8Wr9Th60OvRmDtoUvhWoJyBbRoK1ev9ybQbQKeXiGhuSMPf5HU0OQoIM5FxQxHLiLHiIJPUlGjjwCmm1o8+U75mnodpW/Os5Ha5NIhcnqOuvQk53uGASca7/8DD+/gcOlAzIrZ2VR+PbjTzVcAwwVhEV87gVRDbvShgVaMCUiF0nFnxcgvD3Fg0/VBJaw/A=="
}
*/

include_once "../class/config.inc.php";
include_once "../model/Userlock.php";
include_once "../model/Pay.php";
include_once "./flg/config.php";


$MemberID = $_REQUEST['merId'];
$TransID = $_REQUEST['orderId'];
$FactMoney = $_REQUEST['orderAmt'];
$AdditionalInfo=$_REQUEST['attch'];//订单附加消息   hg308877|13|0a74e7362d0192023a7bra4|57   会员名称|渠道id|用户Oid|支付方式代码


$aData = explode('-',$AdditionalInfo);
$sql = "select ID from ".DBPREFIX.MEMBERTABLE." where ID='$aData[2]' and Status<2";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('/tpl/logout_warn.html','_top')</script>";
    exit;
}

$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` ='.$aData[1].' AND `status` = 1 limit 1';
// 第三方支付
$oRes = mysqli_query($dbLink,$sSql);
if(!$oRes ){
    //echo mysqli_connect_error($dbLink); die;
    echo '渠道信息错误'; die;
}
$iCou = mysqli_num_rows($oRes);
$aRow = mysqli_fetch_assoc($oRes);
$md5Key = $aRow['business_pwd']; // 密钥
if($iCou==0){
    echo "<script type='text/javascript'>alert('该账户不存在！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}


//$_POST = file_get_contents("php://input");
$flag = verify($_REQUEST,$md5Key,$publicKey);

if ($flag) {
    $oUserLock = new Userlock_model($dbMasterLink);
    $userInfo=$oUserLock->lock($row['ID']);
    $userInfoArr = json_decode($userInfo,true);
    if( is_array($userInfoArr) && count($userInfoArr)>0){
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->userpayin($userInfoArr, $aData, $MemberID, $TransID, $FactMoney,$aRow);
        $oUserLock->commit_lock();
        //echo "success";
        //echo "<script type='text/javascript'>alert('支付成功！');window.opener=null;window.open('', '_self');window.close();</script>";

    }
    exit('success');
}

exit('sign error');