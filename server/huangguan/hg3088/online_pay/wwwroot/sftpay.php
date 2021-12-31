<?php

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/sftpay.log');

$iPayid = $_REQUEST['payid'];
$iUid  = $_REQUEST['uid'];
$userid = $_REQUEST['userid'];
$fOrderAmount = $_REQUEST['order_amount']; // 元为单位
include "../class/config.inc.php";
include "../class/address.mem.php";
include "../class/paytype.php";
include "./sft/Pay.class.php";
include "./sft/pay.config.php";

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
$reqData = $_REQUEST;

/**
 * @param  $aThirdPay     当前第三方支付数据
 * @param  $reqData       当前请求参数
 * 返回支付方式 pay_type，支付方式代码 PayCode，第三方简写 sPrifix
 */
$payDatas = CompanyPayType($aThirdPay , $reqData);
$pay_type = $payDatas['pay_type'];
$iPayCode = $payDatas['iPayCode'];
$sPrifix = $payDatas['sPrifix'];
//-------------------------提交第三方数据 Start
// 商户APINMAE，WEB渠道一般支付
$data['service'] = $APINAME_PAY;
// 商户API版本
$data['version'] = $API_VERSION;
// 商户在支付平台的的平台号
$data['merId'] = $aThirdPay['business_code'];
//商户订单号
$data['tradeNo'] = $payDatas['sPrifix'].date("YmdHis").$aThirdPay['id'].rand(10000000, 99999999);
// 商户订单日期
$data['tradeDate'] = date("Ymd");//交易时间
// 商户交易金额
$data['amount'] =sprintf("%.2f", $fOrderAmount);
// 商户通知地址
$data['notifyUrl'] = $aThirdPay['url'].'/sft_return_url.php';
// 商户扩展字段
$data['extra'] = $row['uname'].'|'.$iPayid .'|'.$row['ID'].'|'.$iPayCode; // 回传参数  会员名称|渠道id|用户Oid|支付方式代码
//$data['extra'] = 'test'; // 回传参数  会员名称|渠道id|用户Oid|支付方式代码
// 商户交易摘要
$data['summary'] = 'shunfutongpay';
//超时时间
$data['expireTime'] = '30';
//客户端ip
$data['clientIp'] = get_ip();
// 接收银行代码
$data['bankId'] = $_POST['banklist'];

//--------------------------提交第三方数据 End

// 对含有中文的参数进行UTF-8编码
// 将中文转换为UTF-8
if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['notifyUrl']))
{
    $data['notifyUrl'] = iconv("GBK","UTF-8", $data['notifyUrl']);
}

if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['extra']))
{
    $data['extra'] = iconv("GBK","UTF-8", $data['extra']);
}

if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['summary']))
{
    $data['summary'] = iconv("GBK","UTF-8", $data['summary']);
}

// 初始化
$pPay = new Pay($KEY,$GATEWAY_URL);
// 准备待签名数据
$str_to_sign = $pPay->prepareSign($data);

// 数据签名
$signMsg = $pPay->sign($str_to_sign);
//$signMsg='d3dd8e066c9a94b72f1587ade65b4a9c';
$data['sign'] = $signMsg;

//echo '<pre>';
//var_DUmP($data);exit;
// 生成表单数据
echo $pPay->buildForm($data,$GATEWAY_URL);
//echo $pPay->buildForm($data,$MERCHANT_NOTIFY_URL);

?>

