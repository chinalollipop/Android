<?php
/**
 * 星辰代付回调
 * Date: 2020/1/9
 */
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type:text/html;charset=utf8");

//2021-01-11 21:26:09-php://input:{"mch_id": "10002", "order_id": "TK2021011108260151013837035", "bank": "ABOC", "bank_account_name": "\u4e00\u96f6\u4e09", "bank_site": "\u5317\u4eac\u961c12\u6210\u95e8", "bank_account": "1200122212001200", "amount": 200500, "pay_time": "20210111212609", "pay_status": 1, "time_stamp": "1610371569", "sign": "ED278F51A9BF83D966375543BD9B4AD5"}
@error_log(date('Y-m-d H:i:s').'-php://input:'.file_get_contents("php://input").PHP_EOL, 3, '/tmp/xingchenautopayback.php.log');
$jsondata = file_get_contents("php://input");
$resultArr = json_decode($jsondata,true);

/*php: //input: {
    "mch_id": "10002",
    "order_id": "TK2021011108260151013837035",
    "bank": "ABOC",
    "bank_account_name": "\u4e00\u96f6\u4e09",
    "bank_site": "\u5317\u4eac\u961c12\u6210\u95e8",
    "bank_account": "1200122212001200",
    "amount": 200500,
    "pay_time": "20210111202820",
    "pay_status": 1,
    "time_stamp": "1610368100",
    "sign": "B29178596BEA3F99A26A2A89B7F28892"
}*/

include_once "../class/config.inc.php";
include_once "../model/Pay.php";

// 接收参数
$mch_id = $resultArr["mch_id"];              //商户号
$merOrderNo = $order_id = $resultArr["order_id"];  // 交易唯一订单号
$pay_status = $status = $resultArr['pay_status'];      //0：初始状态，处理中  1：提现代付成功  2：提现代付失败


$sql = "SELECT * FROM " . DBPREFIX . "gxfcy_autopay WHERE `business_code` = '{$mch_id}' AND `status` = 1 LIMIT 1";
$result = mysqli_query($dbLink, $sql);
$count = mysqli_num_rows($result);
if($count == 0) {
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到星辰出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit();
}
$autoPayInfo = mysqli_fetch_assoc($result);

$signData = array(
    'mch_id' => $resultArr["mch_id"],
    'order_id' => $resultArr["order_id"],
    'amount' => $resultArr["amount"],               //金额，以分为单位，不允许包含任何其它符号
    'bank' => $resultArr["bank"],                   //银行对照码
    'bank_site' => $resultArr["bank_site"],         //支行名称
    'bank_account' => $resultArr["bank_account"],   //银行卡号
    'bank_account_name' => $resultArr["bank_account_name"], //持卡人姓名
    'pay_time' => $resultArr["pay_time"],           //格式,例 20181202121545
    'pay_status' => $resultArr["pay_status"],
    'time_stamp' => $resultArr["time_stamp"],
);
$respSign = $resultArr['sign'];
$sign = getSign($signData, $autoPayInfo['business_pwd']);

//@error_log("respSign：".$respSign."--sign：".$sign.PHP_EOL, 3, '/tmp/xingchenautopayback.php.log');

if($sign == $respSign){
    if($status == 1){ // 0：初始状态，处理中  1：提现代付成功  2：提现代付失败

        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, true);
        @error_log("代付订单成功：".$resultArr["merchantOrderNo"]."代付回调响应：success".PHP_EOL, 3, '/tmp/xingchenautopayback.php.log');
        exit('SUCCESS');
    } else {

        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, false, 'fail');
        @error_log("代付订单失败：".$resultArr["merchantOrderNo"]."代付回调响应：fail".PHP_EOL, 3, '/tmp/xingchenautopayback.php.log');
        exit("status: $status ,星辰回调失败");
    }
}else{

    @error_log("代付订单验签失败：".$resultArr["merchantOrderNo"].PHP_EOL, 3, '/tmp/xingchenautopayback.php.log');
    exit('星辰代付回调SIGN_ERROR,校验失败！');
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