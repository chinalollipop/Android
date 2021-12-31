<?php
header("content-Type: text/html; charset=UTF-8");
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/flgpay.php.log');

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

include_once "../class/config.inc.php";
include_once "../class/address.mem.php";
include_once "../class/paytype.php";
require("./fkt/helper.php");    // 获取ip方法
include_once "./flg/config.php";


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

$merchantCode = $aThirdPay['business_code']; // 商户号

//请求地址
$api = $gateway.'/pay';

//请求格式
$data = [
    'merId' => $merchantCode,               //商户号
    'orderId' => $sPrifix.date("YmdHis").$aThirdPay['id'].rand(1000, 9999), //订单号,            //订单号，值允许英文数字
    'orderAmt' => $fOrderAmount,     //订单金额,单位元保留两位小数
    'channel' => $iPayCode,          //支付通道编码
    'desc' => 'feiligu',           //简单描述，只允许英文数字 最大64
    'attch' => $row['uname'].'-'.$iPayid .'-'.$row['ID'].'-'.$iPayCode.'-'.$aThirdPay['account_company'],             //附加信息,原样返回
    'smstyle' => '0',               //用于扫码模式（sm），仅带sm接口可用，默认0返回扫码图片，为1则返回扫码跳转地址。
    'userId' => $row['ID'],          //用于识别用户绑卡信息，仅快捷接口可用。
    'ip' => getClientIp(),           //用户的ip地址必传，风控需要
    'notifyUrl' => $aThirdPay['url'].'/flg_notify_url.php',   //异步返回地址
    'returnUrl' => $aThirdPay['url'].'/flg_return_url.php',   //同步返回地址
    'nonceStr' => Random::alnum('32')   //随机字符串不超过32位
];
//生成签名 请求参数按照Ascii编码排序

//私钥签名
$data['sign'] = sign($data,$md5Key,$privateKey);

$resp = Http::post($api, $data); // string
$aRes = json_decode($resp , true); //array


//string(195) "{"code":1,"msg":"请求成功!","time":"1570520183","data":{"payurl":"http:\/\/api.feiligu.cn\/Pay\/html\/orderno\/1570520183682739646","orderno":"1570520171","sysorderno":"1570520183682739646"}}"
//string(96) "{"code":0,"msg""暂无可用通道，金额规则不匹配！","time":"1570521089","data":null}"

if($aRes['code'] == 1) {
    $postUrl = $aRes['data']['payurl'];
    header("Location: " . $postUrl);
} else {
    var_dump($aRes);
}

exit;
/*
$sign = str_replace( '%2F', '/', $sign );
$sign = str_replace( '%3D', '=', $sign );
$sign = str_replace( '%2B', '+', $sign );*/
//print_r($data); die;

?>

