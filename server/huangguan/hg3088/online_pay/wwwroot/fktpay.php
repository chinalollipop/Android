<?php

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/fktpay_return.log');

$iPayid = $_REQUEST['payid']; //网银配置id
$iUid  = $_REQUEST['uid'];
$userid = $_REQUEST['userid'];

include "../class/config.inc.php";
include "../class/address.mem.php";
include "../class/paytype.php";

$sql = "select ID,UserName as uname,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone,Notes from ".DBPREFIX.MEMBERTABLE." where ID='$userid' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

// 第三方支付
$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` = '. $iPayid .' AND `status` = 1 limit 1';
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
if($iCou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$aRow = mysqli_fetch_assoc($oRes);
$aThirdPay = $aRow;

/**
 * @param  $aThirdPay     当前第三方支付数据
 * @param  $reqData       当前请求参数
 * 返回支付方式 pay_type，支付方式代码 PayCode，第三方简写 sPrifix
 */
$payDatas = CompanyPayType($aThirdPay , $_REQUEST);

$pay_type = $payDatas['pay_type'];
$iPayCode = $payDatas['iPayCode'];
$sPrifix = $payDatas['sPrifix'];

/* *
 * 当前接收参数
 * @param  uid    当前登录用户Oid
 * @param  langx  语言包
 * @param  payid  第三方支付id
 * @param  pid    支付渠道
 * @param  banklist  银行代码
 * @param  order_amount  转账金额
 * */
//------------------------提交第三方数据 Start
$mer_no=$aThirdPay['business_code'];//福卡通商户号

//同步和异步跳转地址
$back_notify_url = $PageUrl=$aThirdPay['url'].'/fkt_return.php';//服务器底层通知地址,支付完成后，支付平台后台通知当前支付是否成功的URL
$page_notify_url = $ReturnUrl=$aThirdPay['url'].'/fkt_page.php';//支付完成后，页面同步跳转到商户页面的URL，同时告知支付是否成功

require("./fkt/helper.php");
require("./fkt/AES.php");

if(isset($_POST['banklist'])) {
    // 接收银行代码
    $bankCode = $_POST['banklist'];
}else {
    $bankCode = "";
}

$orderNo = $sPrifix.date("YmdHis").$aThirdPay['id'].rand(10000000, 99999999); //订单号
$orderAmount_before_security = sprintf("%.2f", $_POST['order_amount']); //转账额度
$referer = $aRow['url'];
$customerIp = getClientIp();
$returnParams = $row['uname'].'|'.$iPayid .'|'.$row['ID'].'|'.$iPayCode; // 回传参数  会员名称|渠道id|用户Oid|支付方式代码
$currentDate = (new DateTime())->format("Y-m-d H:i:s");

//-------------------------提交第三方数据 End
$SalfStr = $aRow ['business_pwd'];  //商户密匙
$kvs = new KeyValues($SalfStr);
$kvs->add(AppConstants::$INPUT_CHARSET, "UTF-8");

$aes = new CryptAES();
$aes->set_key($SalfStr);
$aes->require_pkcs5();
$orderAmount = $aes->encrypt($orderAmount_before_security); // 加密后商户订单总金额
//$orderAmount = $orderAmount_before_security; // 商户订单总金额

$kvs->add('inform_url', $back_notify_url);
$kvs->add(AppConstants::$RETURN_URL, $page_notify_url);
$kvs->add(AppConstants::$PAY_TYPE, $pay_type);
$kvs->add(AppConstants::$BANK_CODE, $bankCode);
$mer_no = $aThirdPay ['business_code'];
$kvs->add(AppConstants::$MERCHANT_CODE, $mer_no);
$kvs->add(AppConstants::$ORDER_NO, $orderNo);
$kvs->add(AppConstants::$ORDER_AMOUNT, $orderAmount);
$kvs->add(AppConstants::$ORDER_TIME, $currentDate);
$kvs->add(AppConstants::$REQ_REFERER, $referer);
$kvs->add(AppConstants::$CUSTOMER_IP, $customerIp);
$kvs->add(AppConstants::$RETURN_PARAMS, $returnParams);
$sign = $kvs->sign();
$gatewayUrl = "http://pay.fktpay.vip/gateway/pay.html";  //网关地址

URLUtils::appendParam($gatewayUrl, AppConstants::$INPUT_CHARSET, "UTF-8", false);
URLUtils::appendParam($gatewayUrl, AppConstants::$NOTIFY_URL, $back_notify_url, true, "UTF-8");
URLUtils::appendParam($gatewayUrl, AppConstants::$RETURN_URL, $page_notify_url, true, "UTF-8");
URLUtils::appendParam($gatewayUrl, AppConstants::$PAY_TYPE, $pay_type);
URLUtils::appendParam($gatewayUrl, AppConstants::$BANK_CODE, $bankCode);

URLUtils::appendParam($gatewayUrl, AppConstants::$MERCHANT_CODE, $mer_no);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_NO, $orderNo);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_AMOUNT, $orderAmount);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_TIME, $currentDate);
URLUtils::appendParam($gatewayUrl, AppConstants::$REQ_REFERER, $referer, true, "UTF-8");
URLUtils::appendParam($gatewayUrl, AppConstants::$CUSTOMER_IP, $customerIp);
URLUtils::appendParam($gatewayUrl, AppConstants::$RETURN_PARAMS, $returnParams, true, "UTF-8");
URLUtils::appendParam($gatewayUrl, AppConstants::$SIGN, $sign);

?>

<html ><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>福卡通充值接口-提交信息处理</title>

</head>
<body onload="">
<div>
    <form id="form1" name="form1" method="post" action="<?php echo GATEWAY_URL; ?>" target="_self">
<!--    <form id="form1" name="form1" method="post" action="./fkt_return_url.php" target="_self">-->
        <input type="hidden" name="input_charset" value="UTF-8"/>
        <input type="hidden" name="inform_url" value="<?php echo $back_notify_url?>"/>
        <input type="hidden" name="return_url" value="<?php echo $page_notify_url?>"/>
        <input type="hidden" name="pay_type" value="<?php echo $pay_type?>"/>
        <input type="hidden" name="bank_code" value="<?php echo $bankCode?>"/>
        <input type="hidden" name="merchant_code" value="<?php echo $mer_no?>"/>
        <input type="hidden" name="order_no" value="<?php echo $orderNo?>"/>
        <input type="hidden" name="order_amount" value="<?php echo $orderAmount?>"/>
        <input type="hidden" name="order_time" value="<?php echo $currentDate?>"/>

        <input type="hidden" name="req_referer" value="<?php echo $referer?>"/>
        <input type="hidden" name="customer_ip" value="<?php echo $customerIp?>"/>

        <input type="hidden" name="return_params" value="<?php echo $returnParams?>"/>
        <input type="hidden" name="sign" value="<?php echo $sign?>"/>
    </form>
    <script type="text/javascript">
        document.forms[0].submit();
    </script>
</div>
</body></html>