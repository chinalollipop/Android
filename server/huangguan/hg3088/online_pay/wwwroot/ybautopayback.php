<?php
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date('Y-m-d H:i:s').'-'.serialize($_REQUEST).PHP_EOL, 3, '/tmp/ybautopayback.log');

include_once "../class/config.inc.php";
include_once "../class/yunbao/utils/yunbao_object.php";
include_once "../model/Pay.php";

// 接收公共参数
$merchantId = strval($_REQUEST["merchantId"]); //商户名称
$code = $_REQUEST["code"]; //受理结果 错误码
$msg = $_REQUEST["msg"]; // 结果描述 错误信息描述
$cipher= $_REQUEST["cipher"]; // 业务数据密文
$sign= $_REQUEST["sign"];   //签名参数 公共参数签名获得

//@error_log('backRequest:'. serialize($_REQUEST) . PHP_EOL,  3,  '/tmp/aaa.log');

// 判断当前第三方自动出款支付渠道
$sSql = "SELECT business_code,business_pwd  FROM `".DBPREFIX."gxfcy_autopay` WHERE business_code ='$merchantId' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$bthinfo = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到第三方出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

//验签返回数据
$respData = $_REQUEST;
$respsignature = $sign;  //签名参数 公共参数签名获得
unset($respData["sign"]);
ksort($respData);
$original_str = implode('&',array_map(function($key,$value){
    return $key.'='.$value;
},array_keys($respData),$respData));

//@error_log('requestUnsetSign:'.serialize($respData) . PHP_EOL,  3,  '/tmp/aaa.log');

// 商户 100000181
if($merchantId == '100000181') {
    $yb = new yb_object("../class/yunbao/certs/private_key.pem", "../class/yunbao/certs/platform_public_key.pem");
}elseif($merchantId == '100000200'){
    $yb = new yb_object("../class/yunbao/certs/private_key_200.pem", "../class/yunbao/certs/platform_public_key.pem");
}

if($yb->rsaVerify($respsignature, $original_str)) {
    // 受理成功则处理 数据密文
    if($respData["code"] == '00000') {
        $respEncPayload = $respData["cipher"];
        $respPlainPayload = $yb->rsaDecrypt($respEncPayload);
        //respPlainPayload:s:119:"{"reqNo":"TK20180908080311263223","transNo":"10000000004516179","transAmt":100,"status":1,"transTime":"20180908200517"}";
        //@error_log('respPlainPayload:'. serialize($respPlainPayload) . PHP_EOL,  3,  '/tmp/aaa.log');
        $res = json_decode($respPlainPayload, true);
        if ($res['status'] == '0' || $res['status'] == '1') {  //status 代付状态  0-处理中  1-支付成功  2-支付失败，己退汇
            $oPayin = new Pay_model($dbMasterLink);
            $oPayin->updateAutoWithdrawer($res['reqNo'], true);
            echo '00000';  //返回“00000”为通知成功标志。
        } elseif($res['status'] == '2' ) {
            $oPayin = new Pay_model($dbMasterLink);
            $oPayin->updateAutoWithdrawer($res['reqNo'], false, 'fail');
            echo "代付失败";
        }
        exit;
    }

} else {
    echo "<script type='text/javascript'>alert('云宝代付出款通知,回调验签校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}