<?php

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date("Y-m-d H:i:s") .'-'. serialize($_REQUEST).PHP_EOL, 3, '/tmp/wdf_page.log');

include ("../../class/config.inc.php");

$fxid = urldecode($_REQUEST["fxid"]);		//商户 ID 是 唯一号
$fxddh = $ordernumber = urldecode($_REQUEST["fxddh"]);		//商户订单号
$fxdesc = urldecode($_REQUEST["fxdesc"]);		//商品名称
$fxorder = urldecode($_REQUEST["fxorder"]);		//平台订单号
$fxfee = $paymoney = urldecode($_REQUEST["fxfee"]);		//提交金额 单位元
$fxtime= urldecode($_REQUEST["fxtime"]);		//支付时间
$fxstatus = urldecode($_REQUEST["fxstatus"]);	//订单状态
$Sign = urldecode($_REQUEST["fxsign"]);	//MD5签名

$attach = $_REQUEST['fxattch'];  // hg0086|102|113018|w_alipayh5  会员名称|渠道id|用户uid|支付方式代码
$aData = explode('|',$attach); //备注信息

// 查询第三方支付配置
$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` ='.$aData[1].' AND `status` = 1 limit 1';
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$aRow = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

$MemberID = $merchantCode  = $aRow['business_code'];   //商户号
$Md5key = $aRow['business_pwd'];

//MD5签名格式
$sign_str = strval($fxstatus) . strval($MemberID) . strval($fxddh) . strval($fxfee) . strval($Md5key);

if ($Sign == md5($sign_str)) {
    if($fxstatus == 1) {
        exit(preg_replace('/\s+/', '', 'success'));
    }else {
        exit("上分失败,支付失败!");
    }
} else {
    exit("Md5CheckFail'");//MD5校验失败
}
