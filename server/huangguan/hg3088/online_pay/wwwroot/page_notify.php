<?php
/* *
 *功能：得宝个人网银支付页面通知接口
 *版本：3.0
 *日期：2016-07-01
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 *并非一定要使用该代码。该代码仅供学习和研究得宝接口使用，仅为提供一个参考。
 **/
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/page_notify.php.log');

//////////////////////////	接收得宝返回通知数据  /////////////////////////////////
/**
获取订单支付成功之后，得宝通知服务器以post方式返回来的订单通知数据，参数详情请看接口文档,
 */
include ("../class/config.inc.php");

$MemberID = $merchant_code	= $_REQUEST["merchant_code"];

if($MemberID == '200004004012') { // hgw777
    include_once ("./db/hgw777_merchant.php"); // merchant_private_key，商户私钥;merchant_public_key,商户公钥;dinpay_public_key，得宝公钥
} elseif($MemberID == '200004004007') { // hg98985
    include_once ("./db/hg98985_merchant.php"); // merchant_private_key，商户私钥;merchant_public_key,商户公钥;dinpay_public_key，得宝公钥
}

$interface_version = $_REQUEST["interface_version"];
$sign_type = $_REQUEST["sign_type"];
$dinpaySign = base64_decode($_REQUEST["sign"]);
$notify_type = $_REQUEST["notify_type"];
$notify_id = $_REQUEST["notify_id"];
$order_no = $_REQUEST["order_no"];
$order_time = $_REQUEST["order_time"];
$order_amount = $_REQUEST["order_amount"];
$trade_status = $_REQUEST["trade_status"];
$trade_time = $_REQUEST["trade_time"];
$trade_no = $_REQUEST["trade_no"];
$bank_seq_no = $_REQUEST["bank_seq_no"];
$extra_return_param = $_REQUEST["extra_return_param"];



/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */

$signStr = "";

if($bank_seq_no != ""){
    $signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
}

if($extra_return_param != ""){
    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
}

$signStr = $signStr."interface_version=".$interface_version."&";

$signStr = $signStr."merchant_code=".$merchant_code."&";

$signStr = $signStr."notify_id=".$notify_id."&";

$signStr = $signStr."notify_type=".$notify_type."&";

$signStr = $signStr."order_amount=".$order_amount."&";

$signStr = $signStr."order_no=".$order_no."&";

$signStr = $signStr."order_time=".$order_time."&";

$signStr = $signStr."trade_no=".$trade_no."&";

$signStr = $signStr."trade_status=".$trade_status."&";

$signStr = $signStr."trade_time=".$trade_time;

//echo $signStr;echo '<br>';


/////////////////////////////   RSA-S验证  /////////////////////////////////

$dinpay_public_key = openssl_get_publickey($dinpay_public_key);

$flag = openssl_verify($signStr,$dinpaySign,$dinpay_public_key,OPENSSL_ALGO_MD5);

//$result="";
if($flag==true){
    //$result="deposit successful";
    echo "<script type='text/javascript'>alert('存款成功！');window.opener=null;window.open('', '_self');window.close();</script>";

}else{;
    //$result="deposit failed";
    echo "<script type='text/javascript'>alert('存款失败！');window.opener=null;window.open('', '_self');window.close();</script>";
}



?>
