<?php

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/rxpay_return.php.log');


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

$aUser = $row;
$aThirdPay = $aRow;

/**
 * @param  $aThirdPay     当前第三方支付数据
 * @param  $reqData       当前请求参数
 * 返回支付方式 pay_type，支付方式代码 PayCode，第三方简写 sPrifix
 */
$payDatas = CompanyPayType($aThirdPay , $_REQUEST);

//$pay_type = $payDatas['pay_type'];
$bankCode = $sPayCode = $payDatas['iPayCode'];  // 接收银行代码
$sPrifix = $payDatas['sPrifix'];

//-------------------------提交第三方数据 Start
$apiurl = "http://dpos.rxpay88.com/Online/GateWay";/*接口提交地址*/
$version= "3.0";/*接口版本号,目前固定值为3.0*/
$method = "Rx.online.pay";/*接口名称: Rx.online.pay*/
$partner = $aThirdPay['business_code'];//商户id,由API分配
$banktype =$bankCode;//银行类型 default为跳转到接口进行选择支付  商户号
$paymoney =$fOrderAmount;//单位元（人民币）,两位小数点
$ordernumber =$sPrifix.date("YmdHis").$aThirdPay['id'].rand(10000000, 99999999);//商户系统订单号，该订单号将作为接口的返回数据。该值需在商户系统内唯一
$mainhost = $aThirdPay['url'];
$callbackurl=$mainhost.'/rx_return_url.php';
$hrefbackurl=$mainhost.'/rx_page_url.php';

//$goodsname="rx-".$ordernumber;//商品名称。若该值包含中文，请注意编码
$goodsname='goods';
$attach = $aUser['uname'].'|'.$iPayid.'|'.$aUser['ID'].'|'.$bankCode;//备注信息，下行中会原样返回。若该值包含中文，请注意编码
$isshow=0;//该参数为支付宝扫码、微信、QQ钱包专用，默认为1，跳转到网关页面进行扫码，如设为0，则网关只返回二维码图片地址供用户自行调用
if($bankCode == 'WEIXIN' || $bankCode=='ALIPAY' || $bankCode=='QQ') $isshow=1;
$key = $aThirdPay ['business_pwd'];//商户Key,由API分配

$signSource = sprintf("version=%s&method=%s&partner=%s&banktype=%s&paymoney=%s&ordernumber=%s&callbackurl=%s%s", $version, $method, $partner, $banktype, $paymoney, $ordernumber, $callbackurl, $key);
$sign = md5($signSource);//32位小写MD5签名值，UTF-8编码


?>
<html ><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>充值接口-提交信息处理</title>

</head>
<body onload="document.form1.submit()">
<div>
    <form id="form1" name="form1" method="post" action="<?php echo $apiurl; ?>" target="_self">
<!--            <form id="form1" name="form1" method="post" action="./rx_return_url.php" target="_self">-->
        <input type='hidden' name='version' value="<?php echo $version;?>" />
        <input type='hidden' name='method' value="<?php echo $method;?>" />
        <input type='hidden' name='partner' value="<?php echo $partner;?>" />
        <input type='hidden' name='banktype' value="<?php echo $banktype;?>" />
        <input type='hidden' name='paymoney' value="<?php echo $paymoney;?>" />
        <input type='hidden' name='ordernumber' value="<?php echo $ordernumber;?>" />
        <input type='hidden' name='callbackurl' value="<?php echo $callbackurl;?>" />
        <input type='hidden' name='hrefbackurl' value="<?php echo $hrefbackurl;?>" />
        <input type='hidden' name='goodsname' value="<?php echo $goodsname;?>" />
        <input type='hidden' name='attach' value="<?php echo $attach;?>" />
        <input type='hidden' name='isshow' value="<?php echo $isshow;?>" />
        <input type='hidden' name='sign' value="<?php echo $sign;?>" />
    </form>
</div>
</body></html>
