<?php

header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");


//echo date('Y-m-d H:i:s' , time());echo '<br>';
//echo  (new DateTime())->format("Y-m-d H:i:s");echo '<br>';exit;

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date("Y-m-d H:i:s") .'-'. serialize($_REQUEST).PHP_EOL, 3, '/tmp/zhongbaopay_return.log');

$iPayid = $_REQUEST['payid']; //第三方支付网银配置id
$iUid  = $_REQUEST['uid'];
$userid = $_REQUEST['userid'];

include "../../class/config.inc.php";
include "../../class/address.mem.php";
include "../../class/paytype.php";

$sql = "select ID,UserName as uname,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone,Notes from ".DBPREFIX.MEMBERTABLE." where ID='$userid' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

// 第三方支付
$sSql = 'SELECT id,title,account_company,business_code,business_pwd,url,depositNum,status,class,thirdpay_code FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` = '. $iPayid .' AND `status` = 1 limit 1';
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
//@error_log(date("Y-m-d H:i:s") . serialize($payDatas).PHP_EOL, 3, '/tmp/zhongbaopay_return.log');


/*
 * 1000:微信扫码 1002:微信直连-手机端(H5)
 * 1003:支付宝扫码 1004:支付宝直连-手机端(H5)
 * 1005:QQ钱包扫码 1006:QQ钱包直连-手机端(H5)
 * */
//$pay_type = $payDatas['pay_type'];
$bankCode = $iPayCode = $payDatas['iPayCode']; // 接收银行代码
$sPrifix = $payDatas['sPrifix'];

/* *
 * 当前接收参数
 * @param  uid    当前登录用户Oid
 * @param  userid 用户id
 * @param  langx  语言包
 * @param  payid  第三方支付id   98
 * @param  pid    支付渠道  0
 * @param  banklist  银行代码 962
 * @param  order_amount  转账金额
 * */

//-------------------------提交第三方数据 Start
$apiurl = "https://api.zmpechan.cc/gateway/pay";/*接口提交地址*/
$merchantid=$aThirdPay['business_code'];//商户号
$paytype = $sPayCode = $payDatas['iPayCode'];  // 接收银行代码跳转到接口进行选择支付
$orderAmount = sprintf("%.2f", $_REQUEST['order_amount']); //转账额度
$orderNo = $sPrifix.date("YmdHis").$aThirdPay['id'].rand(10000000, 99999999); //商户订单号

//同步和异步跳转地址
$return_url = '';//支付完成后无此参数，页面同步跳转到商户系统，告知是否成功，同步通知地址
$notify_url = $PageUrl=$aThirdPay['url'].'/'.$aThirdPay['thirdpay_code'].'/zb_notify.php';//服务器底层通知地址,支付完成后,异步通知地址

//echo  (new DateTime(null, new DateTimeZone('GMT+8')))->format("Y-m-d H:i:s");exit;
$request_time = (new DateTime(null, new DateTimeZone('GMT+8') ))->format("YmdHis");     //请求时间

$desc = $aUser['uname'].'|'.$iPayid.'|'.$aUser['ID'].'|'.$bankCode;//备注信息，会员名称|渠道id|用户Oid|支付方式代码，请注意编码



$key = $aThirdPay ['business_pwd'];//商户Key,由API分配
// 当前源字符串
$signSource = sprintf("merchantid=%s&paytype=%s&amount=%s&orderid=%s&notifyurl=%s&request_time=%s&key=%s", $merchantid, $paytype, $orderAmount, $orderNo, $notify_url, $request_time, $key);

// 正确源字符串
/*$sign_str = "merchantid=" .$merchantid."&paytype=".$paytype."&amount=".$orderAmount.
    "&orderid=".$orderNo."&notifyurl=".$notify_url."&request_time=".$request_time;

if($signSource != $sign_str) {
    echo '加签字符串错误';
    return false;
}*/

$sign = md5($signSource);//32位小写MD5签名值，UTF-8编码


?>

<html ><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>众宝充值接口-提交信息处理</title>

</head>
<body onload="">
<div>
    <form id="form1" name="form1" method="post" action="<?php echo $apiurl; ?>" target="_self">
        <!--            <form id="form1" name="form1" method="post" action="./rx_return_url.php" target="_self">-->
        <input type='hidden' name='merchantid' value="<?php echo $merchantid;?>" />
        <input type='hidden' name='paytype' value="<?php echo $paytype;?>" />
        <input type='hidden' name='amount' value="<?php echo $orderAmount;?>" />
        <input type='hidden' name='orderid' value="<?php echo $orderNo;?>" />
        <input type='hidden' name='notifyurl' value="<?php echo $notify_url;?>" />
        <input type='hidden' name='request_time' value="<?php echo $request_time;?>" />
        <input type='hidden' name='returnurl' value="<?php echo $return_url;?>" />
        <input type='hidden' name='desc' value="<?php echo $desc;?>" />
        <input type='hidden' name='sign' value="<?php echo $sign;?>" />
    </form>
    <script type="text/javascript">
        document.forms[0].submit();
    </script>
</div>
</body></html>