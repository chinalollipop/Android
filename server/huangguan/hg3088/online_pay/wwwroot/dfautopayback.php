<?php
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date('Y-m-d H:i:s').'-'.serialize($_REQUEST).PHP_EOL, 3, '/tmp/dfautopayback.log');

include_once "../class/config.inc.php";
include_once "../class/huitao/PayUtils.php"; //多付引用汇淘文件 用于解密
include_once "../model/Pay.php";

//@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/aaa.log');

// 接收参数
$merchantId = $_REQUEST["merchantId"]; //商户号
$orderSn = $_REQUEST["orderSn"]; // 支付平台唯一订单号
$orderAmount = $_REQUEST["orderAmount"]; //商户订单总金额 元
$orderTime = $_REQUEST["orderTime"]; //商户订单时间
$transferTime = $_REQUEST["transferTime"]; // 支付平台订单 时间
$merOrderNo = $_REQUEST["merOrderNo"]; //商户唯一订单号 与请求代付订单号参数一致
$extendParams = $_REQUEST["extendParams"]; // 回传参数  是否需要 否
$transferStatus= $_REQUEST["transferStatus"]; // 交易状态  交易失败-FAILURE   处理中-PENDING   交易成功-SUCCESS
$signType = $_REQUEST["signType"]; //签名方式  MD5
$sign = $_REQUEST["sign"]; //签名

// 判断当前第三方自动出款支付渠道
$sSql = "SELECT business_code,business_pwd  FROM `".DBPREFIX."gxfcy_autopay` WHERE business_code ='$merchantId' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$duofuinfo = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到第三方出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

    $postParams["merOrderNo"]   = $merOrderNo;  //商户唯一订单号
    $postParams["merchantId"]   = $merchantId;  //商户号
    $postParams["orderAmount"]  = $orderAmount; //金额
    $postParams["orderTime"]    = $orderTime;   //商户订单时间
    $postParams["signType"]     = $signType;    // 签名方式
    $postParams["orderSn"]      = $orderSn;     // 支付平台唯一订单号
    $postParams["transferStatus"]  = $transferStatus; // 交易状态
    $postParams["transferTime"]    = $transferTime;   // 支付平台订单 时间
    $postParams["extendParams"] = $extendParams;   // 回传参数

    // 获取签名值
    $data['sign'] = md5Sign($postParams, $duofuinfo['business_pwd'], "UTF-8");

    if ($sign ==$data['sign']) {
        // $transferStatus   success 交易成功  failure 代付失败  pending 银行处理中
        if ($transferStatus == 'SUCCESS' || $transferStatus == 'PENDING') {
            echo 'SUCCESS';
            $oPayin = new Pay_model($dbMasterLink);
            $oPayin->updateAutoWithdrawer($merOrderNo, true);

        } else {
            $oPayin = new Pay_model($dbMasterLink);
            $oPayin->updateAutoWithdrawer($merOrderNo, false, 'fail');
            echo "多付代付失败";
        }
        exit;
    }else{
        echo "<script type='text/javascript'>alert('多付代付出款通知,MD5校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    }
