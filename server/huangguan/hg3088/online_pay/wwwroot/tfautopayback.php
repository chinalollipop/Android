<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
/**
 * 腾飞代付回调
 * Date: 2020/1/9
 */
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type:text/html;charset=utf8");

//2021-09-29 03:36:43-php://input:amount=101.0000&mch_code=M00504&out_trade_no=TK20210928153455321899721808&sign=FFD4181DBB84C3D18D37E69DB0F93931&status=PAID
@error_log(date('Y-m-d H:i:s').'-php://input:'.file_get_contents("php://input").PHP_EOL, 3, '/tmp/tengfeiautopayback.log');
$jsondata = file_get_contents("php://input");
$resultArr = urlToArray($jsondata);

/*php://input:
amount=100.0000
mch_code=M00504
out_trade_no=TK20210928133151321899696750
sign=8D6D93822F2741675DE2363785F3C369
status=PAID
*/

include_once "../class/config.inc.php";
include_once "../model/Pay.php";

// 接收参数
$mch_id = $resultArr["mch_code"];              //商户号
$merOrderNo = $order_id = $resultArr["out_trade_no"];  // 交易唯一订单号
$pay_status = $status = $resultArr['status'];

$sql = "SELECT * FROM " . DBPREFIX . "gxfcy_autopay WHERE `business_code` = '{$mch_id}' AND `status` = 1 LIMIT 1";
$result = mysqli_query($dbLink, $sql);
$count = mysqli_num_rows($result);
if($count == 0) {
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到腾飞出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit();
}
$autoPayInfo = mysqli_fetch_assoc($result);

$signData = array(
    'mch_code' => $resultArr["mch_code"],
    'amount' => $resultArr["amount"],               //金额
    'out_trade_no' => $resultArr["out_trade_no"],
    'status' => $resultArr["status"],    //订单状态；INIT 初始化；WAIT 待支付；PROC 处理中；UNKNOW 待确认；FAIL 支付失败；PAID 已支付
);
$respSign = $resultArr['sign'];
$sign = getSign($signData, $autoPayInfo['business_pwd']);

//@error_log("respSign：".$respSign."--sign：".$sign.PHP_EOL, 3, '/tmp/tengfeiautopayback.log');

if($sign == $respSign){
    if($status == 'PAID'){ // 订单状态；INIT 初始化；WAIT 待支付；PROC 处理中；UNKNOW 待确认；FAIL 支付失败；PAID 已支付

        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, true);
        @error_log("代付订单成功：".$merOrderNo."代付回调响应：success".PHP_EOL, 3, '/tmp/tengfeiautopayback.log');
        exit('SUCCESS');
    } else {

        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, false, 'fail');
        @error_log("代付订单失败：".$merOrderNo."代付回调响应：fail".PHP_EOL, 3, '/tmp/tengfeiautopayback.log');
        exit("status: $status ,腾飞回调失败");
    }
}else{

    @error_log("代付订单验签失败：".$merOrderNo.PHP_EOL, 3, '/tmp/tengfeiautopayback.log');
    exit('腾飞代付回调SIGN_ERROR,校验失败！');
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
    $signStr = trim($dataStr, "&") . "&key=". $appKey;
    $sign = strtoupper(md5($signStr));
    return $sign;
}

//转为数组
function urlToArray($str)
{
    $str = trim($str,'&');
    $arr = explode('&',$str);
    foreach ($arr as  $v){
        $arrnew[] = explode('=',$v);
    }
    foreach ($arrnew as $k => $v){
        $array[$v[0]] = $v[1];
    }
    return $array;

}