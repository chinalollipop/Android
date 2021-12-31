<?php
/**
 * HiPay3127 回调
 * Date: 2020/1/9
 */
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type:text/html;charset=utf8");

include_once "../class/config.inc.php";
include_once "../model/Pay.php";

/**
 *  参数：
 *  商户订单号 --merchantOrderNo
 *  金额 --amount
 *  订单状态 (0出款失败，1出款成功) --status
 *  签名 --sign
 */

// 接收参数
$merchantOrderNo = $_REQUEST['merchantOrderNo'];
$amount = $_REQUEST['amount'];
$status = $_REQUEST['status'];
$sign = $_REQUEST['sign'];

$encode = json_encode($_REQUEST);

@file_put_contents('/tmp/hipay_back_api.log', date('Y-m-d H:i:s') . '-' . $encode . "\n", FILE_APPEND);

$sql = "SELECT * FROM " . DBPREFIX . 'gxfcy_autopay WHERE `title` = "hipay3721" AND `status` = 1 LIMIT 1';
$result = mysqli_query($dbLink, $sql);
$count = mysqli_num_rows($result);
if($count == 0) {
    //@file_put_contents('/tmp/hipay_back_api.log', date('Y-m-d H:i:s') . '-渠道信息错误,未找到HiPay出款支付渠道' . "\n", FILE_APPEND);
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到HiPay出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit();
}
$autoPayInfo = mysqli_fetch_assoc($result);

// 签名
$data = [
    'amount' => $amount,
    'merchantOrderNo' => $merchantOrderNo,
    'status' => $status,
    'key' => $autoPayInfo['business_pwd'],
];
$md5BackSign = getMDSignBack($data);
//@file_put_contents('/tmp/hipay_back_api.log', date('Y-m-d H:i:s') . '-' . $md5BackSign . "\n", FILE_APPEND);

if ($sign == $md5BackSign) { // 签名验证通过
    if($status == 1){ // 订单状态(0出款失败，1出款成功)
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merchantOrderNo, true);
        //@file_put_contents('/tmp/hipay_back_api.log', date('Y-m-d H:i:s') . '-success' . "\n", FILE_APPEND);
        echo "success";
    } else {
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merchantOrderNo, false, 'fail');
        @file_put_contents('/tmp/hipay_back_api.log', date('Y-m-d H:i:s') . '-HiPay3127失败' . "\n", FILE_APPEND);
        echo "HiPay3127失败";
    }
    exit();
} else {
    //@file_put_contents('/tmp/hipay_back_api.log', date('Y-m-d H:i:s') . '-HiPay3127出款签名校验失败'  . "\n", FILE_APPEND);
    echo "<script type='text/javascript'>alert('HiPay3127出款签名校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit();
}


/**
 * 获取sign,回调使用
 * @param $data
 * @return string
 */
function getMDSignBack($data)
{
    $parameter = '';
    if (!empty($data['amount'])) {
        $parameter .= 'amount='.$data['amount'];
    }
    if (!empty($data['merchantOrderNo'])) {
        $parameter .= '&merchantOrderNo='.$data['merchantOrderNo'];
    }
    if (!empty($data['status'])) {
        $parameter .= '&status='.$data['status'];
    }
    $parameter.= '&key='.$data['key'];

    return md5($parameter);
}