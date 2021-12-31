<?php header("content-Type: text/html; charset=UTF-8");?>
<?php
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/zrbpay.php.log');

/*
 * $_REQUEST  uid    50545a44f278f0197e1ara7
 * $_REQUEST  userid  100
 * $_REQUEST  langx  zh-cn
 * $_REQUEST  payid  103   支付渠道id
 * $_REQUEST  pid    3
 * $_REQUEST  banklist  银行代码  ABC
 * $_REQUEST order_amount 金额  200
 */
$iPayid = $_REQUEST['payid'];  //网银配置id
$uid  = $_REQUEST['uid'];
$userid = $_REQUEST['userid'];
$fOrderAmount = $_REQUEST['order_amount'];

include_once "../class/config.inc.php";
include_once "../class/address.mem.php";
include_once "../class/paytype.php";
include_once "../class/zrb/payCommon.php";
//include_once "../class/zrb/merchantProperties.php";

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

//$pay_type = $payDatas['pay_type'];  //支付类型
$iPayCode = $payDatas['iPayCode'];  // 银行简码
$sPrifix = $payDatas['sPrifix'];    //第三方简写

//-------------------------提交第三方数据 Start

// ##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，API支付会自动生成随机的商户订单号.
$p2_Order = $orderNo = $sPrifix.date("YmdHis").$aThirdPay['id'].rand(10000000, 99999999);
// ##单位:元，精确到分.
$p3_Amt	= sprintf("%.2f", $fOrderAmount);
#	交易币种,固定值"CNY".
$p4_Cur	= "CNY";
#	商品名称 ##用于支付时显示在API支付网关左侧的订单产品信息.
$p5_Pid	= "productname";
#	商品种类
$p6_Pcat = "producttype";
#	商品描述
$p7_Pdesc = "productdesc";
#	商户接收支付成功数据的地址,支付成功后API支付会向该地址发送两次成功通知.
$p8_Url = $aThirdPay['url'].'/zrbcallback.php';
#	商户扩展信息 ##商户可以任意填写1K 的字符串,支付成功时将原样返回.
$pa_MP = $row['uname'].'|'.$iPayid .'|'.$row['ID'].'|'.$iPayCode.'|'.$aThirdPay['account_company'];
#	支付通道编码 ##默认为""，到API支付网关.若不需显示API支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.
$pd_FrpId = $iPayCode; // //智融宝 PC端 $iPayCode，'wxcode' PC端微信扫码，'alipay'PC端支付宝扫码
#	应答机制 ##默认为"1": 需要应答机制;
$pr_NeedResponse	= "1";


/**区分商户新增 商户号 , 商户密钥
 * 6668(98985)   1571   XIKTgE3R9VJlJANu507hua9obfjnwcGe
 * 0086(7557)    1572   DrVfedQ76eTK3mOZ8sgcIdpRTsdxwrJd
 */
$p1_MerId = $aThirdPay['business_code']; // 商户号
$merchantKey = $aThirdPay['business_pwd']; // 商户密钥

#调用签名函数生成签名串
$hmac = getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse, $p1_MerId,$merchantKey);

?>
<html>
<head>
    <title>To API Page</title>
</head>
<body onLoad="document.API.submit();">
<form name='API' action='<?php echo $reqURL_onLine; ?>' method='post'>
    <input type='hidden' name='p0_Cmd'					value='<?php echo $p0_Cmd; ?>'>
    <input type='hidden' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
    <input type='hidden' name='p2_Order'				value='<?php echo $p2_Order; ?>'>
    <input type='hidden' name='p3_Amt'					value='<?php echo $p3_Amt; ?>'>
    <input type='hidden' name='p4_Cur'					value='<?php echo $p4_Cur; ?>'>
    <input type='hidden' name='p5_Pid'					value='<?php echo $p5_Pid; ?>'>
    <input type='hidden' name='p6_Pcat'					value='<?php echo $p6_Pcat; ?>'>
    <input type='hidden' name='p7_Pdesc'				value='<?php echo $p7_Pdesc; ?>'>
    <input type='hidden' name='p8_Url'					value='<?php echo $p8_Url; ?>'>
    <input type='hidden' name='p9_SAF'					value='<?php echo $p9_SAF; ?>'>
    <input type='hidden' name='pa_MP'						value='<?php echo $pa_MP; ?>'>
    <input type='hidden' name='pd_FrpId'				value='<?php echo $pd_FrpId; ?>'>
    <input type='hidden' name='pr_NeedResponse'	value='<?php echo $pr_NeedResponse; ?>'>
    <input type='hidden' name='hmac'						value='<?php echo $hmac; ?>'>
</form>
</body>
</html>