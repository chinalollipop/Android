<?php
/**
 * autopay 代付回调
 * Date: 2020/1/9
 */
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type:text/html;charset=utf8");

include_once "../class/config.inc.php";
include_once "../model/Pay.php";

// 接收参数
$appId = $_REQUEST["appId"]; //商户号
$orderNo= $_REQUEST["orderNo"]; // 支付平台唯一订单号(AUTOPAY)
$merchOrderNo = $_REQUEST["merchOrderNo"]; // 商户系统产生的唯一订单号
$status = $_REQUEST['status']; // 订单状态：待处理(100)、处理中(200)、成功(300)、失败(400)、冲正(500)
$amount= $_REQUEST["amount"]; // 交易状态
$orderDate = $_REQUEST["orderDate"]; //交易日期

@file_put_contents('/tmp/autopayautopayback.php.log', date('Y-m-d H:i:s') . '-' . json_encode($_REQUEST) . "\n", FILE_APPEND);

$sql = "SELECT * FROM " . DBPREFIX . "gxfcy_autopay WHERE `business_code` = '{$appId}' AND `status` = 1 LIMIT 1";
$result = mysqli_query($dbLink, $sql);
$count = mysqli_num_rows($result);
if($count == 0) {
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到autopay出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit();
}
$autoPayInfo = mysqli_fetch_assoc($result);

$signData = array(
    'appId' => $_REQUEST["appId"],
    'orderNo' => $_REQUEST["orderNo"],
    'merchOrderNo' => $_REQUEST["merchOrderNo"],
    'status' => $_REQUEST["status"],
    'amount' => $_REQUEST["amount"],
    'orderDate' => $_REQUEST["orderDate"]
);
$respSign = $_POST['sign'];
$merchRemark = $_POST['merchRemark'];
$sign = getSign($signData, $autoPayInfo['business_pwd']);
if($sign == $respSign){
    if($status == 300){ // 订单状态：待处理(100)、处理中(200)、成功(300)、失败(400)、冲正(500)
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merchantOrderNo, true);
        exit('AUTOPAY');
    } else {
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merchantOrderNo, false, 'fail');
        exit("status: $status ,autopay回调失败");
    }
}else{
    exit('SIGN_ERROR');
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