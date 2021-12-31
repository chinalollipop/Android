<?php header("content-Type: text/html; charset=UTF-8");?>
<?php
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/dbpay.php.log');

/*
 * $_REQUEST uid    50545a44f278f0197e1ara7
 * $_REQUEST langx  zh-cn
 * $_REQUEST  payid  97   支付渠道id
 * $_REQUEST  pid    3
 * $_REQUEST  banklist  银行代码  ABC
 * $_REQUEST order_amount 金额  200
 */
$iPayid = $_REQUEST['payid'];  //网银配置id
$uid  = $_REQUEST['uid'];
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

/* *
 *功能：得宝个人网银支付接口
 *版本：3.0
 **/

//-------------------------提交第三方数据 Start
/**
接口参数请参考得宝网银支付文档，除了sign参数，其他参数都要在这里初始化
 */
if($aThirdPay['business_code'] == '200004004012') { // hgw77
    include_once ("./db/hgw777_merchant.php"); // merchant_private_key，商户私钥;merchant_public_key,商户公钥;dinpay_public_key，得宝公钥
} elseif($aThirdPay['business_code'] == '200004004007') { // hg98985
    include_once ("./db/hg98985_merchant.php"); // merchant_private_key，商户私钥;merchant_public_key,商户公钥;dinpay_public_key，得宝公钥
}

$merchant_code = $aThirdPay['business_code']; //商户号
$service_type ="direct_pay";    //服务类型
$interface_version ="V3.0"; //参数名称：接口版本3.0
$sign_type ="RSA-S";    //签名方式
$input_charset = "UTF-8";
//同步和异步跳转地址
$notify_url = $aThirdPay['url'].'/offline_notify.php';//服务器异步通知地址
//$return_url = $aThirdPay['url'].'/db_page.php';//页面跳转同步通知地址,支付成功后，通过页面跳转的方式跳转到商家网站

$order_no = $orderNo = $sPrifix.date("YmdHis").$aThirdPay['id'].rand(10000000, 99999999); //订单号
$order_time = date( 'Y-m-d H:i:s' );    //商家订单时间
$order_amount = sprintf("%.2f", $fOrderAmount); //单位 元
$product_name ="debaopay";   //商品名称

//以下参数为可选参数，如有需要，可参考文档设定参数值
$return_url = $aThirdPay['url'].'/page_notify.php';	    //页面跳转同步通知地址,支付成功后，通过页面跳转的方式跳转到商家网站
$pay_type = "b2c";	    //支付类型, 取值如下（必须小写，多选时请用逗号隔开）,b2c(网银支付),weixin（微信扫码）,alipay_scan（支付宝扫码）,tenpay_scan（qq钱包扫码）
$redo_flag = "1";       //是否允许重复订单,当值为1时不允许商户订单号重复提交；当值为 0或空时允许商户订单号重复提交
$product_code = "";     //商品编号
$product_desc = "";     //商品描述
$product_num = "";      //商品数量必须是整型数字
$show_url = "";         //商品展示URL
$client_ip = getClientIp();   //客户端IP
$bank_code = "";       //银行代码  $iPayCode
//扩展参数  "name^john103|payid^97|uid^baf312a68778f0197e1ara3|code^ICBC";   跨境商家必选，非跨境商家可选
//$extend_param = "name^".$row['uname'].'|payid^'.$iPayid .'|uid^'.$uid.'|PayCode^'.$iPayCode;
$extend_param = "";
//商户如果支付请求是传递了该参数，则通知商户支付成功时会回传该参数,  回传参数  会员名称|渠道id|用户Oid|支付方式银行代码| 支付类型2为银行卡支付，4微信支付，5为支付宝,6为QQ扫码
$extra_return_param = $row['uname'].'|'.$iPayid .'|'.$row['ID'].'|'.$iPayCode.'|'.$aThirdPay['account_company'];

/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */

$signStr= "";

if($bank_code != ""){
    $signStr = $signStr."bank_code=".$bank_code."&";
}
if($client_ip != ""){
    $signStr = $signStr."client_ip=".$client_ip."&";
}
if($extend_param != ""){
    $signStr = $signStr."extend_param=".$extend_param."&";
}
if($extra_return_param != ""){
    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
}

$signStr = $signStr."input_charset=".$input_charset."&";
$signStr = $signStr."interface_version=".$interface_version."&";
$signStr = $signStr."merchant_code=".$merchant_code."&";
$signStr = $signStr."notify_url=".$notify_url."&";
$signStr = $signStr."order_amount=".$order_amount."&";
$signStr = $signStr."order_no=".$order_no."&";
$signStr = $signStr."order_time=".$order_time."&";

if($pay_type != ""){
    $signStr = $signStr."pay_type=".$pay_type."&";
}

if($product_code != ""){
    $signStr = $signStr."product_code=".$product_code."&";
}
if($product_desc != ""){
    $signStr = $signStr."product_desc=".$product_desc."&";
}

$signStr = $signStr."product_name=".$product_name."&";

if($product_num != ""){
    $signStr = $signStr."product_num=".$product_num."&";
}
if($redo_flag != ""){
    $signStr = $signStr."redo_flag=".$redo_flag."&";
}
if($return_url != ""){
    $signStr = $signStr."return_url=".$return_url."&";
}

$signStr = $signStr."service_type=".$service_type;

if($show_url != ""){

    $signStr = $signStr."&show_url=".$show_url;
}

//echo $signStr."<br>";

function getClientIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if (REQ_CUSTOMER_ID != null)
        $ip = REQ_CUSTOMER_ID;
    return $ip;
}

/////////////////////////////   获取sign值（RSA-S加密）  /////////////////////////////////

$merchant_private_key= openssl_get_privatekey($merchant_private_key);

openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);

$sign = base64_encode($sign_info);

// echo $sign;
//exit;
?>
<!-- 以post方式提交所有接口参数到得宝支付网关https://pay.debaozhifu.com/gateway?input_charset=UTF-8 -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body onLoad="document.dinpayForm.submit();">
<form id="dinpayForm" name="dinpayForm" method="post" action="https://pay.debaozhifu.com/gateway?input_charset=UTF-8" target="_self">
<!--<form name="dinpayForm" method="post" method="post" action="./offline_notify.php" target="_blank">-->
    <input type="hidden" name="sign"		  value="<?php echo $sign?>" />
    <input type="hidden" name="merchant_code" value="<?php echo $merchant_code?>" />
    <input type="hidden" name="bank_code"     value="<?php echo $bank_code?>"/>
    <input type="hidden" name="order_no"      value="<?php echo $order_no?>"/>
    <input type="hidden" name="order_amount"  value="<?php echo $order_amount?>"/>
    <input type="hidden" name="service_type"  value="<?php echo $service_type?>"/>
    <input type="hidden" name="input_charset" value="<?php echo $input_charset?>"/>
    <input type="hidden" name="notify_url"    value="<?php echo $notify_url?>">
    <input type="hidden" name="interface_version" value="<?php echo $interface_version?>"/>
    <input type="hidden" name="sign_type"     value="<?php echo $sign_type?>"/>
    <input type="hidden" name="order_time"    value="<?php echo $order_time?>"/>
    <input type="hidden" name="product_name"  value="<?php echo $product_name?>"/>
    <input Type="hidden" Name="client_ip"     value="<?php echo $client_ip?>"/>
    <input Type="hidden" Name="extend_param"  value="<?php echo $extend_param?>"/>
    <input Type="hidden" Name="extra_return_param" value="<?php echo $extra_return_param?>"/>
    <input Type="hidden" Name="pay_type"  value="<?php echo $pay_type?>"/>
    <input Type="hidden" Name="product_code"  value="<?php echo $product_code?>"/>
    <input Type="hidden" Name="product_desc"  value="<?php echo $product_desc?>"/>
    <input Type="hidden" Name="product_num"   value="<?php echo $product_num?>"/>
    <input Type="hidden" Name="return_url"    value="<?php echo $return_url?>"/>
    <input Type="hidden" Name="show_url"      value="<?php echo $show_url?>"/>
    <input Type="hidden" Name="redo_flag"     value="<?php echo $redo_flag?>"/>
</form>
</body>
</html>