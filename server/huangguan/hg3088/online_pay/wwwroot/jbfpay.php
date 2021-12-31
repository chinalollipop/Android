<?php
header("content-Type: text/html; charset=UTF-8");
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/jbfpay.php.log');

/*
 * $_REQUEST uid    50545a44f278f0197e1ara7
 * $_REQUEST langx  zh-cn
 * $_REQUEST  payid  97   支付渠道id
 * $_REQUEST  pid    3
 * $_REQUEST  banklist  银行代码  ABC
 * $_REQUEST order_amount 金额  200
 */
$iPayid = $_REQUEST['payid'];  //网银配置id
$userid = $_REQUEST['userid'];
$fOrderAmount = $_REQUEST['order_amount'];

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


$pay_type = $payDatas['pay_type'];  //支付类型
$iPayCode = $payDatas['iPayCode'];  // 银行简码
$sPrifix = $payDatas['sPrifix'];    //第三方简写


//-------------------------提交第三方数据 Start

$merchantCode = $aThirdPay['business_code']; // ==AA== 需要换成自己的
$private = '-----BEGIN PRIVATE KEY-----
MIICeQIBADANBgkqhkiG9w0BAQEFAASCAmMwggJfAgEAAoGBAPg63UWHywSgu5F7
w/cNIoTqV6TqYsxF/ehMqAZ+YqCc8JFd9Wgl5KYdQAXl/AJ2/IMLk24ddsxkIX41
jUb+tu5ozhrTfLI7SVFzV3egLZGbBBaGObvGCQQDFrVtMkQlED7ANDjXcm+DnlSn
X5DAYx3KqqT5iT3/81JKjXwoJvHXAgMBAAECgYEAs4PGb7kzjeY7n4u0/Z5HH35l
4cMLrhTT+cIuJXwTEXpN06Lyjd4RjDxNB7b52EJ6fL7LYO/38Ppc6mwJ/pTIbxdI
JWfrh3Zf0HCsXgQeYX/V5LNjVIn0pn+iPRQo6sbr1T9wyBM+BxE0d5GSw3vb35Rq
CNYgdQHXt3yWR4h+JUkCQQD9lcLj5JTFEOGRWPaJ4G+tKWCE2RaHSZ9sOzZ8izpj
vawZD22H99gN7uNLeBvmC76zhf1VIcDqslcyo4DeInWtAkEA+pgMJJZ7VsF3bQnT
yRjElKoA5l1pEmKB/NZnZKR5B9Lrm3ia6eY6tfWSEdq21q6/bE3P8Pe7jd0NiwIy
C2POEwJBAPoWTYslLlcfa+ZFX3bgoiKbcPXzhtVLlW9PAlBXmvEs6OIaJgJ3Olub
YgxW2uTIZn10QkBINpL/6SEmwPvR7k0CQQChQcjGnqN9/39XhnRnuu19cSylEUU1
FHjreBkOtZxAwaTl5iViEMqFHyLBJIp1+fuquSPvv6tMrgwyANatZ6tLAkEApImq
S4DrKZZa7F/KsPuBDPXGeQzZzwzI4WpelqrJiQsAqhRRBByppFakbpmfjnNiDOJl
Od59mZo3uss9YhwqNA==
-----END PRIVATE KEY-----'; // ==AA== 需要换成自己的

$amount = $fOrderAmount*100; // ==AA== 单位：分
$orderNo = $sPrifix.date("YmdHis").$aThirdPay['id'].rand(10000000, 99999999); //订单号
$extraReturnParam = $row['uname'].'|'.$iPayid .'|'.$row['ID'].'|'.$iPayCode.'|'.$aThirdPay['account_company'];
$notifyUrl = $aThirdPay['url'].'/jbf_notify_url.php'; // ==AA== 需要换成自己的
$returnUrl = $aThirdPay['url'].'/jbf_notify_url.php'; // ==AA== 需要换成自己的
// BANK  ALIPAY  ALIPAY_WAP  ALIPAY_H5  UNION_APP_QR  UNION_PC  UNION_WAP KUAIJIE_H5
$bankCode = $payDatas['iPayCode'];  // 接收银行代码

$postUrl = 'https://api.jubaopays.com/gateway/bank';
$charset = "UTF-8";
$remark = $orderNo;
$signData = "charset={$charset}&merchantCode={$merchantCode}&orderNo={$orderNo}"
    ."&amount={$amount}&channel=BANK&bankCode={$bankCode}&remark={$remark}"
    ."&notifyUrl={$notifyUrl}&returnUrl={$returnUrl}&extraReturnParam={$extraReturnParam}";

$pi_key = openssl_pkey_get_private($private);
openssl_sign($signData, $sign, $pi_key, OPENSSL_ALGO_SHA1);
$sign = base64_encode($sign);
$sign = urlencode($sign);

$sign = str_replace( '%2F', '/', $sign );
$sign = str_replace( '%3D', '=', $sign );
$sign = str_replace( '%2B', '+', $sign );

$data=array(
    'charset' => $charset,
    'merchantCode' => $merchantCode,
    'orderNo' => $orderNo,
    'amount' => $amount,
    'channel' => 'BANK',
    'bankCode' => $bankCode,
    'remark' => $remark,
    'notifyUrl' => $notifyUrl,
    'returnUrl' => $returnUrl,
    'extraReturnParam' => $extraReturnParam,
    'signType' => 'RSA',
    'sign' => $sign,
);

//print_r($data); die;


?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body onLoad="document.dinpayForm.submit();">
<!--<body>-->
<form id="dinpayForm" name="dinpayForm" method="post" action="<?php echo $postUrl;?>" target="_self">
    <?php foreach($data as $key=>$value):?>
        <div style="width: 600px; text-align: right;">
            <input type="hidden" name="<?=$key?>" value="<?=$value?>" size="50">
        </div>
    <?php endforeach;?>
</body>
</html>