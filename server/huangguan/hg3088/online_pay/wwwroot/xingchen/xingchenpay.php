<?php

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
//判断终端类型
if ($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) { // 14 原生android，13 原生ios
    $playSource=$_REQUEST['appRefer'];
}else{
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $playSource=3;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $playSource=4;
    }else{
        $playSource=22;
    }
}


$iPayid = $_REQUEST['payid'];
$userid  = $_REQUEST['uid'];
$iHgUserid = $_REQUEST['userid'];
$fOrderAmount = $_REQUEST['order_amount'];
$banklist = isset($_REQUEST['banklist']) ? $_REQUEST['banklist'] : 0;
$onlineIntoBank = isset($_REQUEST['onlineIntoBank']) ? $_REQUEST['onlineIntoBank'] : 0;    // app


include "../../class/config.inc.php";
include "../../class/address.mem.php";
include "../../class/paytype.php";

if(!empty($banklist) ) { // pc m
    $_REQUEST['banklist'] = strval($banklist);
} elseif(!empty($onlineIntoBank) ) { // andiord ios
    $_REQUEST['banklist'] = strval($onlineIntoBank);
}

$sql = "select ID,UserName as uname,LoginIP,online_status,Alias,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone,Notes from ".DBPREFIX.MEMBERTABLE." where ID='$iHgUserid' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
switch ($playSource){
    case 22: $clientTerminal='pc'; break;
    case 3:
    case 13:
    case 4:
    case 14: $clientTerminal='mobile'; break;
    default: $clientTerminal='pc'; break;
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

switch ($_REQUEST['banklist']){
    case 'alipay_gateway':
    case 'bank_transfer':
    case 'cloud_quickpass':
    case 'alipay_card':
        $bank='online_bank'; // 网银支付
        $channel_id=$_REQUEST['banklist'];
        break;
}



$sPrifix='xingchen';
$token = $aThirdPay['business_pwd'];
$userip = get_ip();   //支付用户 IP 地址必传，风控需要


$data['mch_id'] = $aThirdPay['business_code'];  //商户号
$data['order_id'] = $sPrifix.date("YmdHis").rand(100000,999999);  //订单号
$data['amount'] = $fOrderAmount*100;  //总金额，以分为单位，不允许包含任何其它符号
$data['notify_url'] = $aThirdPay['url'].'/xingchen/xingchen_notify_url.php';
$data['client_ip'] = $userip;
$data['platform'] = $clientTerminal;
//$data['bank'] = $bank;  // alipay:支付宝支付  wechat:微信支付  online_bank:网银支付
$data['channel_id'] = $channel_id;  // pdd_mall: 拼多多商城   idle_fish_qrcode: 闲鱼扫码   idle_fish_sdk: 闲鱼SDK   alipay_gateway: 支付宝网银网关   bank_transfer: 网银网关   cloud_quickpass: 云闪付   phone_bill_alipay: 话费-支付宝   phone_bill_wechat: 话费-微信   taobao_red: 手淘红包   alipay_direct: 支付宝直付通   alipay: 支付宝原生支付   alipay_applet: 支付宝小程序   alipay_server: 支付宝服务商   aggregation_code:聚合码   alipay_h5: 支付宝个码H5   alipay_card: 支付宝转卡   alipay_transfer: 支付宝扫码转账   wechat: 微信商户原生支付   wechat_applet：微信小程序
$data['time_stamp'] = time()+12*60*60;  // 交易日期

$sign_str ='amount='.$data['amount'].'&channel_id='.$data['channel_id'].'&client_ip='.$data['client_ip'].'&mch_id='.$data['mch_id'].'&notify_url='.$data['notify_url'].'&order_id='.$data['order_id'].'&platform='.$data['platform'].'&time_stamp='.$data['time_stamp'].'&key='.$token;
$data['sign'] = md5($sign_str);

//@error_log(date('Y-m-d H:i:s').PHP_EOL, 3, '/tmp/xingchenpay.php.log');
//@error_log(serialize($sign_str).PHP_EOL, 3, '/tmp/xingchenpay.php.log');
//@error_log($data['sign'].PHP_EOL, 3, '/tmp/xingchenpay.php.log');

//$url="http://pay.jlwl33.com/api/pay/order_payment"; // 测试通道
$url="https://api.alipayliving.com/api/pay/order_payment"; // 正式通道

//print_r($sign_str); die;
//print_r($data); die;

// 插入一条订单到数据库，方便查询会员三方的订单

$thirdData = [
    'userid' => $aUser['ID'],
    'UserName' => $aUser['uname'],
    'Alias' => $aUser['Alias'],
    'merchantName' => $aThirdPay['title'],
    'PayType' => $iPayid,
    'PayName' => $bank,
    'thirdpay_code' => $aRow['thirdpay_code'],
    'Order_Code' => $data['order_id'],
    'thirdSysOrder' => '',
    'Gold' => $fOrderAmount,
    'UserTime' => date("Y-m-d H:i:s"),
    'SysTime' => '',
    'CallbackTime' => '',
    'AuditTime' => '',
    'Status' => '',
    'playSource' => $playSource,  //判断终端类型
    'ip' => $userip,
    'Remarks' => '',
    'Reviewer' => '',
];
$sInsData = '';
foreach ($thirdData as $key => $value){
    $sInsData.= "`$key` = '{$value}',";
}
$sInsData = rtrim($sInsData, ',');
$sql1 = "insert into `".DBPREFIX."web_thirdpay_data` set $sInsData";
//echo $sql1; die;
if(mysqli_query($dbMasterLink,$sql1)) {

}else{
    exit('充值订单入库失败，请重新下单充值');
}

$res = send_post($url, $data);
$aRes = json_decode($res, true);
//@error_log(serialize($aRes).PHP_EOL, 3, '/tmp/xingchenpay.php.log');
if ($aRes['code']=='0'){
    header('location:'.$aRes['pay_link']);
}else{
    echo '<script>alert('.$aRes['msg'].');</script>';
}


/**
 * 发送post请求
 * @param string $url 请求地址
 * @param array $post_data post键值对数据
 * @return string
 */
function send_post($url, $post_data) {

    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result;
}


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body onLoad="document.dinpayForm.submit();">
<!--<body>-->
<!--<form id="dinpayForm" name="dinpayForm" method="post" action="<?php /*echo $url;*/?>" target="_self">
    <?php /*foreach($data as $key=>$value):*/?>
        <input type="hidden" name="<?/*=$key*/?>" value="<?/*=$value*/?>">
    <?php /*endforeach;*/?>
</form>-->
</body>
</html>