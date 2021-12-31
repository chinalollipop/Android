<?php
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date('Y-m-d H:i:s').'-'.serialize($_REQUEST).PHP_EOL, 3, '/tmp/zrbautopayback.log');

include_once "../class/config.inc.php";
include_once "../class/daifubao/payCommon.php";
include_once "../model/Pay.php";

//http://pay.hgw777.co/zrbautopayback.php?
//p1_MerId=1620&
//r1_Code=1&
//r2_TrxId=TK20190416132845218331&
//r3_Amt=100.00&
//r4_Order=TK20190416132845218331
//&hmac=dfe0bce89fa846177da91e0ceff43788


// 接受参数
$p1_MerId = $_REQUEST["p1_MerId"]; //商户名称
$r1_Code = $_REQUEST["r1_Code"]; //提交结果   1 成功
$r2_TrxId = $_REQUEST["r2_TrxId"]; //[API支付平台]平台产生的交易流水号，每笔订单唯一
$r3_Amt= $_REQUEST["r3_Amt"]; //商户订单金额，精确到分
$r4_Order = $merchant_order_id = $_REQUEST["r4_Order"]; //商户订单号
$hmac = $_REQUEST["hmac"]; //签名

// 判断当前第三方自动出款支付渠道
$sSql = "SELECT business_code,business_pwd  FROM `".DBPREFIX."gxfcy_autopay` WHERE business_code ='$p1_MerId' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$bthinfo = mysqli_fetch_assoc($oRes);

if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到第三方出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

    $key_val=$bthinfo['business_pwd'];  //商户密钥

    $data['hmac'] = callbackHmac($p1_MerId,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Order , $key_val);

//@error_log('data_hmac:'.$data['hmac'].'---hmac:'.$hmac.PHP_EOL, 3, '/tmp/zrbautopayback.log');
if ($hmac == $data['hmac']) {
    if ($r1_Code == 1) {
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
    echo "<script type='text/javascript'>alert('代付宝(聚宝付)出款通知,hmac校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}
