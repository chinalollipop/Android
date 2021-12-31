<?php
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date('Y-m-d H:i:s').'-'.serialize($_REQUEST).PHP_EOL, 3, '/tmp/bthautopayback.log');

include_once "../class/config.inc.php";
include_once "../class/bth/BthConstants.php";
include_once "../model/Pay.php";

// 接受参数
$bank_code = $_REQUEST["bank_code"]; //银行代码
$merchant_name = $_REQUEST["merchant_name"]; //商户名称
$merchant_order_id = $_REQUEST["merchant_order_id"]; //商户订单号
$status = $_REQUEST["status"];    // 状态
$timestamp = $_REQUEST["timestamp"]; //当前时间戳
$trans_amount = $_REQUEST["trans_amount"]; //商户订单总金额
$input_charset = $_REQUEST["input_charset"]; //编码
$outside_order_id= $_REQUEST["outside_order_id"]; // 平台订单号
$return_params = $_REQUEST["return_params"];    // 自定义参数
$type = $_REQUEST["type"]; //类型   b2c
$sign= $_REQUEST["sign"];   //签名


// 判断当前第三方自动出款支付渠道
$sSql = "SELECT business_code,business_pwd  FROM `".DBPREFIX."gxfcy_autopay` WHERE business_code ='$merchant_name' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$bthinfo = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到第三方出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

    $key_val=$bthinfo['business_pwd'];  //商户密钥

    /* 签名   对参数进行赋值 */
    $kvs = new KeyBthValues($key_val);
    $kvs->add(BthConstants::$INPUT_CHARSET, $input_charset);
    $kvs->add("outside_order_id", $outside_order_id);   // 代付通知订单号
    $kvs->add(BthConstants::$RETURN_PARAMS, $return_params);
    $kvs->add(BthConstants::$BANK_CODE, $bank_code);
    $kvs->add(BthConstants::$MERCHANT_NAME, $merchant_name);
    $kvs->add(BthConstants::$MERCHANT_ORDER_ID, $merchant_order_id);
    $kvs->add("status", $status);
    $kvs->add(BthConstants::$TIMESTAMP, $timestamp);
    $kvs->add(BthConstants::$TRANNS_AMOUNT, $trans_amount);
    $kvs->add(BthConstants::$TYPE, $type);

    /* 获取签名值  BTH 代付参数和加密规则待第三方确认  */
    $data['sign'] = $kvs->sign();


if ($sign == $data['sign']) {
    if ($status == '1' || $status == '2' || $status == '3') {  // status  1已提交2处理中3代付成功4代付失败5异常(不能失败处理)
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merchant_order_id, true);
        echo 'success';
    } else {
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merchant_order_id, false, 'fail');
        echo "代付失败";
    }
    exit;
}else{
    echo "<script type='text/javascript'>alert('BTH代付出款通知,MD5校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}
