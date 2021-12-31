<?php

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/sfback_return.log');

include "../class/config.inc.php";
include "../model/Userlock.php";
include "../model/Pay.php";
$MemberID=$_REQUEST['MemberID'];//商户号
$TerminalID =$_REQUEST['TerminalID'];//商户终端号
$TransID =$_REQUEST['TransID'];//流水号
$Result=$_REQUEST['Result'];//支付结果
$ResultDesc=$_REQUEST['ResultDesc'];//支付结果描述
$FactMoney=$_REQUEST['FactMoney'];//实际成功金额
$AdditionalInfo=$_REQUEST['AdditionalInfo'];//订单附加消息   hg308877|13|0a74e7362d0192023a7bra4|57   会员名称|渠道id|用户Oid|支付方式代码
$SuccTime=$_REQUEST['SuccTime'];//支付完成时间
$Md5Sign=$_REQUEST['Md5Sign'];//md5签名

$aData = explode('|',$AdditionalInfo);
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
if($iCou==0){
    echo "<script type='text/javascript'>alert('该账户不存在！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

$Md5key = $aRow['business_pwd'];
$MARK = "~|~";
//MD5签名格式
$WaitSign=md5('MemberID='.$MemberID.$MARK.'TerminalID='.$TerminalID.$MARK.'TransID='.$TransID.$MARK.'Result='.$Result.$MARK.'ResultDesc='.$ResultDesc.$MARK.'FactMoney='.$FactMoney.$MARK.'AdditionalInfo='.$AdditionalInfo.$MARK.'SuccTime='.$SuccTime.$MARK.'Md5Sign='.$Md5key);
if ($Md5Sign == $WaitSign) {
    $oUserLock = new Userlock_model($dbMasterLink);
	$userInfo=$oUserLock->lock($row['ID']);
    $userInfoArr = json_decode($userInfo,true);
    if( is_array($userInfoArr) && count($userInfoArr)>0){
    	$oPayin = new Pay_model($dbMasterLink);
	    $oPayin->userpayin($userInfoArr, $aData, $MemberID, $TransID, $FactMoney,$aRow);
	    $oUserLock->commit_lock();
    }else{
    	echo("数据操作失败");//MD5校验失败    	
    }
} else {
    echo("Md5CheckFail");//MD5校验失败
}
