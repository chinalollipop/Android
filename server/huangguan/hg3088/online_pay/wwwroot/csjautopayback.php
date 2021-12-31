<?php
/**
 * 创世纪代付回调
 * Date: 2020/1/9
 */
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type:text/html;charset=utf8");

@error_log(date('Y-m-d H:i:s').'-'.serialize($_REQUEST).PHP_EOL, 3, '/tmp/csjautopayback.php.log');

//@error_log(date('Y-m-d H:i:s').'-php://input:'.file_get_contents("php://input").PHP_EOL, 3, '/tmp/csjautopayback.php.log');
//$post = 'account=%E4%B8%80%E9%9B%B6%E4%B8%89&amount=110.00&attach=11&bankName=%E4%B8%AD%E5%9B%BD%E5%86%9C%E4%B8%9A%E9%93%B6%E8%A1%8C&cardNo=1200320058412100&depositBank=%E9%99%95%E8%A5%BF%E8%A5%BF%E5%AE%89%E5%B8%82&merchno=4b284a5186&orderId=TK20210113235901321341317984&orderNo=p1610596751408&sign=MJgGY9qebxp3KceuQKSaRXJ4dffBwv5iSVQdugIoUvVvN4DZQ7iRVoO4yJfXKcxk%2FDhcgBem05wgoIOniPCWABuitJ20ND3ZHSPO2rcdd%2FFf%2BlwrVY219IMdFtEmDk0REXdwtgg%2F%2FzT6QHISf1%2BFqH%2F66OrwSxZc52X35tmIovZlCBzPldL5PgZfW9uwFn0hyujJXyUdRH5OnuD5uPMqz%2FApXbkYUhGWww42idAcEyxbz8J54V1qCvoLAVqJT4kQ4yXHYqE2g9OdjELyXgcV9qMm8mLDa4P15ZCrlvkzycM0U0GPrVBRRP77CnDHL5Dhwz0jMw0C6ZASJqXoPRnmbQ%3D%3D&status=1&timestamp=20210114115926&tradeType=1';
//$post = file_get_contents("php://input");
//$resultArr = urlToArray($post);

/*
   $_REQUEST = Array(
    [account] => 一零三
    [amount] => 110.00
    [attach] => john103|14|321341
    [bankName] => 中国农业银行
    [cardNo] => 1200320058412100
    [depositBank] => 陕西西安市
    [merchno] => 4b284a5186
    [orderId] => TK20210113220606321341914542
    [orderNo] => p1610589980516
    [sign] => a/HZofNLOTUIFHvcJCHK/0WcqZNg8V2RbI7LxTDknJ9+KPQxDCQNR44PoLRlQevV6yn3L7PX6T3rFieC63jWI7/qDAA9WtMM125SQ4sM2Jlnm/HogR4zHZ/IFu1bBt0NniVILjF5HMVabLG3lOm0lsCUCsHSUZ5jQhXyut9V+40MBEqwSCC24Ka+PrEfRx3m6xBFbFDhENrYnYmlNtoegaUtmMntc7WF599iqzojIOMR83RPRGmksNWrBx4s1jDf+gv7EYor59sqO4HuoZo2A1sUchLTQqnG01meycXaoYjsFyGsTJzYB8vMaxHx4Fh1PbsG8O08BCbdxeJtLBZQog==
    [status] => 1
    [timestamp] => 20210114100635
    [tradeType] => 1
)
*/

include_once "../class/config.inc.php";
include_once "../class/chuangshiji/Config.php";
include_once "../class/juming/ServiceUtil.php";
include_once "../model/Pay.php";

// 接收参数
$merchno = $_REQUEST["merchno"];                    //商户号
$merOrderNo = $order_id = $_REQUEST["orderId"];     // 交易唯一订单号
$status = $_REQUEST['status'];        //0：已受理  1：提现代付成功  2：提现代付失败


$sql = "SELECT * FROM " . DBPREFIX . "gxfcy_autopay WHERE `business_code` = '{$merchno}' AND `status` = 1 LIMIT 1";
$result = mysqli_query($dbLink, $sql);
$count = mysqli_num_rows($result);
if($count == 0) {
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到创世纪出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit();
}
$autoPayInfo = mysqli_fetch_assoc($result);
$appKey = strval($autoPayInfo['business_pwd']); // 商户秘钥

$signData = array(
    'account' => $_REQUEST["account"],             //收款账户名
    'amount' => $_REQUEST["amount"],               //单位元(人民币)，必须保留2位小数（例如：100.00）
    'attach' => $_REQUEST["attach"],               //备注信息
    'bankName' => trim($_REQUEST["bankName"]),     //银行名称
    'cardNo' => $_REQUEST["cardNo"],               //银行卡号
    'depositBank' => trim($_REQUEST["depositBank"]),     //支行名称
    'merchno' => $_REQUEST["merchno"],             //商户号
    'orderId' => $_REQUEST["orderId"],             //商户订单号
    'orderNo' => $_REQUEST["orderNo"],             //平台订单号创世纪系统生成的平台订单号
    'status' => $_REQUEST["status"],               //订单状态
    'timestamp' => $_REQUEST["timestamp"],         //时间戳北京时间
    'tradeType' => $_REQUEST["tradeType"],         //交易类1：对私；2：对公；（目前只支持对私交易）
);

$respSign = $_REQUEST['sign'];  //签名

//account=一零三&amount=100.00&attach=john103|24|51013&bankName=中国农业银行&cardNo=1200122212001200&depositBank=北京阜12成门&merchno=4b284a5186&orderId=TK2021011305394951013702861&orderNo=p1610530801083&status=1&time_stamp=&tradeType=1&secretKey=8305d54f304445aeba27d061c6919477
//$sign = getSign($signData, $appKey);

ksort($signData);
$sign_str = ServiceUtil::get_sign($signData);
$string = $sign_str . '&secretKey=' . $appKey;      // 末尾添加商户秘钥
$md5Sign  = strtolower(md5($string));               // md5生成信息摘要，并转为小写

$publickey = ServiceUtil::publicKeyStr(Config::publicKey);  // 平台公钥
$flag = ServiceUtil::verifyRSA2($md5Sign, $respSign, $publickey);   //验签

if($flag){
    if($status == 1){ // 0：初始状态，处理中  1：提现代付成功  2：提现代付失败

        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, true);
        @error_log("创世纪代付订单成功：".$merOrderNo."代付回调响应：success".PHP_EOL, 3, '/tmp/csjautopayback.php.log');
        exit('success');
    } else {

        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, false, 'fail');
        @error_log("创世纪代付订单失败：".$merOrderNo."代付回调响应：fail".PHP_EOL, 3, '/tmp/csjautopayback.php.log');
        exit("status: $status ,创世纪回调失败");
    }
}else{
    @error_log("代付订单验签失败：".$merOrderNo.PHP_EOL, 3, '/tmp/csjautopayback.php.log');
    exit('创世纪代付回调SIGN_ERROR,校验失败！');
}


function getSign($data, $appKey)
{
    if (!$appKey) {
        return false;
    }
    unset($data['sign']);
    ksort($data);
    $dataStr = '';
    foreach ($data as $k => $v) {
        $dataStr .= $k . '=' . $v . "&";
    }
    $signStr = trim($dataStr, "&") . "&secretKey=". $appKey;
    $sign = strtolower(md5($signStr));
    return $sign;
}