<?php

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/fkt_page_url.log');

include ("../class/config.inc.php");

require("fkt/helper.php");
require("fkt/AES.php");

$merchantCode = $_POST["merchant_code"]; //商户号
$orderNo = $_POST["order_no"]; //商户唯一订单号
$orderTime = $_POST["order_time"]; //商户订单时间
$orderAmount = $_POST["order_amount"]; //商户订单总金额
$trade_status = $_POST["trade_status"]; //商户交易状态
$tradeNo = $_POST["trade_no"]; // 支付平台订单号
$returnParams = $_POST["return_params"]; //商户支付请求时传递，通知商户会回传该参数
$sign= $_POST["sign"];

$sSql = "SELECT * FROM `".DBPREFIX."gxfcy_pay` WHERE business_code ='".$merchantCode."' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
if(!$oRes ){
    //echo mysqli_connect_error($dbLink); die;
    echo '渠道信息错误'; die;
}
$iCou = mysqli_num_rows($oRes);
$aRow = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script>window.open('/tpl/logout_warn.html','_top')</script>";
    exit;
}
$Md5key = $aRow['business_pwd'];
$kvs = new KeyValues($Md5key);
$kvs->add("merchant_code", $merchantCode);
$kvs->add("order_no", $orderNo);
$kvs->add("order_time", $orderTime);
$kvs->add("order_amount", $orderAmount);
$kvs->add("trade_status", $trade_status);
$kvs->add("trade_no", $tradeNo);
$kvs->add("return_params", $returnParams);
$_sign = $kvs->sign();

//MD5签名格式
if ($_sign == $sign) {
    if ($trade_status == "success") {
        echo "<script type='text/javascript'>alert('支付成功！');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    } elseif($trade_status == "paying") {
        echo "<script type='text/javascript'>alert('交易中!');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    } elseif($trade_status == "failed") {
        echo "<script type='text/javascript'>alert('交易失败!');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    }
} else {
    echo "<script type='text/javascript'>alert('跳转到商户页,MD5校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
}
