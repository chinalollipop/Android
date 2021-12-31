<?php
/*信付通存款支付*/
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/xftpay.php.log');

/*
 * $_REQUEST uid    50545a44f278f0197e1ara7
 * $_REQUEST langx  langx
 * $_REQUEST  payid  69   支付渠道id
 * $_REQUEST  pid    1
 * $_REQUEST order_amount 金额
 */
$iPayid = $_REQUEST['payid'];
$uid  = $_REQUEST['uid'];
$userid = $_REQUEST['userid'];
$fOrderAmount = trim($_REQUEST['order_amount']);

$bankCode = !empty($_POST['banklist']) ? $_POST['banklist']: ""; // 接收银行代码

include_once "../class/config.inc.php";
include_once "../class/address.mem.php";
include_once "../class/paytype.php";
include_once "./xft/utils.php";

$sql = "select ID,UserName as uname,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone from ".DBPREFIX.MEMBERTABLE." where ID='$userid' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
if($cou==0){
    echo "<script>alert('会员查找失败，请重新登录！');window.close() ;</script>";
    exit;
}

// 第三方支付
$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` = '. $iPayid .' AND `status` = 1 limit 1';
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
if($iCou==0){
    echo "<script>alert('第三方支付渠道查找失败，请重新登录！');window.close() ;</script>";
    exit;
}
$aRow = mysqli_fetch_assoc($oRes);

$aUser = $row;  // 会员信息
$aThirdPay = $aRow; // 支付通道信息
$merchantId = strval($aThirdPay['business_code']);
$apiKey = strval($aThirdPay ['business_pwd']);//商户Key,由API分配
/**
 * @param  $aThirdPay     当前第三方支付数据
 * @param  $reqData       当前请求参数
 * 返回支付方式 pay_type，支付方式代码 PayCode，第三方简写 sPrifix
 */
$payDatas = CompanyPayType($aThirdPay , $_REQUEST);

$pay_type = $isApp = $payDatas['pay_type']; // 接入方式  web、H5、app
$bankCode = $sPayCode = $payDatas['iPayCode'];  // 接收银行代码
$sPrifix = $payDatas['sPrifix'];

//-------------------------提交第三方数据 Start
$apiurl = "https://ebank.xfuoo.com";    /*接口提交地址*/

$ordernumber =$sPrifix.date("YmdHis").$aThirdPay['id'].rand(10000000, 99999999); //商户系统订单号，该订单号将作为接口的返回数据。该值需在商户系统内唯一
$params['orderNo'] = $orderNo = trim($ordernumber);        //商户订单号，务必确保在系统中唯一，必填
$params['totalFee'] = $fOrderAmount;                //订单金额，单位为RMB元，必填
$params['paymethod'] = 'directPay';                     //支付方式，directPay：直连模式；bankPay：收银台模式，必填
$params['defaultbank'] = $bankCode;                      //网银代码，当支付方式为bankPay时，该值为空；支付方式为directPay时该值必传
$params['title'] = 'xftdeposit';                        //商品的名称，请勿包含字符，选填
$params['service'] = "online_pay";                      //固定值online_pay，表示网上支付，必填
$params['paymentType'] = "1";                           //支付类型，固定值为1，必填
$params['merchantId'] = $merchantId;                    //支付平台分配的商户ID，必填

//同步和异步跳转地址
$params['notifyUrl'] = trim($aThirdPay['url']).'/xft_return_url.php';//商户支付成功后，该地址将收到支付成功的异步通知信息，该地址收到的异步通知作为发货依据，必填
$params['returnUrl'] = trim($aThirdPay['url']).'/xft_page_url.php';  //支付成功跳转URL，仅适用于支付成功后立即返回商户界面，必填
$params['charset'] = "utf-8";                           //参数编码字符集，必填
$params['body'] = $aUser['uname'].'|'.$iPayid.'|'.$aUser['ID'].'|'.$bankCode; //备注信息，下行中会原样返回。若该值包含中文，请注意编码;                               //商品的具体描述，选

$params['isApp'] = $isApp;                     //接入方式  web、H5、app

$baseUri = $apiurl.'/payment/v1/order/'.$merchantId.'-'.$orderNo;
$params['sign'] = utils::Sign($params,$apiKey);
$params['signType'] = "SHA";//signType不参与加密，所以要放在最后

?>
<html ><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>充值接口-提交信息处理</title>

</head>
<!--<body onload="document.form1.submit()">-->
<body onload="document.form1.submit()">
<div>
    <form id="form1" name="form1" method="post" action="<?php echo $baseUri; ?>" target="_self">
<!--            <form id="form1" name="form1" method="post" action="./xft_return_url.php" target="_self">-->
        <input type='hidden' name='orderNo' value="<?php echo $orderNo;?>" />
        <input type='hidden' name='totalFee' value="<?php echo $fOrderAmount;?>" />
        <input type='hidden' name='defaultbank' value="<?php echo $bankCode;?>" />
        <input type='hidden' name='title' value="<?php echo $params['title'];?>" />
        <input type='hidden' name='paymethod' value="<?php echo $params['paymethod'];?>" />
        <input type='hidden' name='service' value="<?php echo $params['service'];?>" />
        <input type='hidden' name='paymentType' value="<?php echo $params['paymentType'];?>" />
        <input type='hidden' name='merchantId' value="<?php echo $params['merchantId'];?>" />
        <input type='hidden' name='returnUrl' value="<?php echo $params['returnUrl'];?>" />
        <input type='hidden' name='notifyUrl' value="<?php echo $params['notifyUrl'];?>" />
        <input type='hidden' name='charset' value="<?php echo $params['charset'];?>" />
        <input type='hidden' name='body' value="<?php echo $params['body'];?>" />
        <input type='hidden' name='isApp' value="<?php echo $params['isApp'];?>" />
        <input type='hidden' name='sign' value="<?php echo $params['sign'];?>" />
        <input type='hidden' name='signType' value="<?php echo $params['signType'];?>" />
    </form>
</div>
</body></html>
