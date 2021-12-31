<?php
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date('Y-m-d H:i:s').'-'.serialize($_REQUEST).PHP_EOL, 3, '/tmp/huitaoautopayback.log');

include_once "../class/config.inc.php";
include_once "../class/huitao/PayUtils.php";
include_once "../model/Pay.php";

// 接收参数
$merchantId = $_REQUEST["merchantId"]; //商户号
$tradeNo= $_REQUEST["tradeNo"]; // 支付平台唯一订单号
$orderAmount = $_REQUEST["orderAmount"]; //商户订单总金额 元
$orderTime = $_REQUEST["orderTime"]; //商户订单时间
$merOrderNo = $_REQUEST["merOrderNo"]; //商户唯一订单号 与请求代付订单号参数一致
$tradeTime= $_REQUEST["tradeTime"]; // 支付平台订单 时间
$tradeStatus= $_REQUEST["tradeStatus"]; // 交易状态
$signType = $_REQUEST["signType"]; //签名方式  MD5
$returnParams = $_REQUEST["returnParams"]; // 回传参数

$sign = $_REQUEST["sign"]; //签名

// 判断当前第三方自动出款支付渠道
$sSql = "SELECT business_code,business_pwd  FROM `".DBPREFIX."gxfcy_autopay` WHERE business_code ='$merchantId' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$huitaoinfo = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到第三方出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

    $postParams["merOrderNo"]   = $merOrderNo;  //商户唯一订单号
    $postParams["merchantId"]   = $merchantId;  //商户号
    $postParams["orderAmount"]  = $orderAmount; //金额
    $postParams["orderTime"]    = $orderTime;   //商户订单时间
    $postParams["signType"]     = $signType;    // 签名方式
    $postParams["tradeNo"]      = $tradeNo;     // 支付平台唯一订单号
    $postParams["tradeStatus"]  = $tradeStatus; // 交易状态
    $postParams["tradeTime"]    = $tradeTime;   // 支付平台订单 时间
    $postParams["returnParams"] = $returnParams;   // 支付平台订单 时间

    // 获取签名值
    $data['sign'] = md5Sign($postParams, $huitaoinfo['business_pwd'], "UTF-8");

    if ($sign ==$data['sign']) {
        if ($tradeStatus == 'success') {  // $tradeStatus  success 代付成功  failure 代付失败  pending 银行处理中
            $oPayin = new Pay_model($dbMasterLink);
            $oPayin->updateAutoWithdrawer($merOrderNo, true);
            echo 'success';
        } else {
            $oPayin = new Pay_model($dbMasterLink);
            $oPayin->updateAutoWithdrawer($merOrderNo, false, 'fail');
            echo "汇淘代付失败";
        }
        exit;
    }else{
        echo "<script type='text/javascript'>alert('汇淘代付出款通知,MD5校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    }
