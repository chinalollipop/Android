<?php
/**
 * 仁信上分（银行、支付宝、微信、QQ）
 *
 *
 */
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/rxback_return.log');

include "../class/config.inc.php";
include "../model/Userlock.php";
include "../model/Pay.php";

$partner=$_REQUEST['partner'];
$ordernumber=$_REQUEST['ordernumber'];
$orderstatus=$_REQUEST['orderstatus'];
$paymoney=$_REQUEST['paymoney'];
$sysnumber=$_REQUEST['sysnumber'];
$attach=$_REQUEST['attach'];
$sign=$_REQUEST['sign'];

$aData = explode('|',$attach);

$sql = "select ID from ".DBPREFIX.MEMBERTABLE." where ID='$aData[2]' and Status<2";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('/tpl/logout_warn.html','_top')</script>";
    exit;
}

$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` ='.$aData[1].' AND `status` = 1 limit 1';
$oRes = mysqli_query($dbLink,$sSql);
if(!$oRes ){
   echo '渠道信息错误'; die;
}
$iCou = mysqli_num_rows($oRes);
$aRow = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('该账户不存在！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}
$Md5key = $aRow['business_pwd'];
$signSource = sprintf("partner=%s&ordernumber=%s&orderstatus=%s&paymoney=%s%s", $partner, $ordernumber, $orderstatus, $paymoney, $Md5key);
if ($sign == md5($signSource)) {
    $oUserLock = new Userlock_model($dbMasterLink);
    $userInfo=$oUserLock->lock($row['ID']);
    $userInfoArr = json_decode($userInfo,true);
    if( is_array($userInfoArr) && count($userInfoArr)>0){
    	$oPayin = new Pay_model($dbMasterLink);
	    //$ThirdPayCode=$aRow['thirdpay_code'];
	    // 用户个人信息，回传参数，订单号，金额，第三方简称（rx 仁信）
	    $oPayin->userpayin($userInfoArr, $aData, $MemberID, $ordernumber, $paymoney, $aRow);
        $oUserLock->commit_lock();
    }else{
    	 echo("上分失败,数据操作错误");
    }
} else {
   echo("Md5CheckFail'");//MD5校验失败
}
