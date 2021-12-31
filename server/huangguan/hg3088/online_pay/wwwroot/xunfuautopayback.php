<?php
/**
 * xunfupay 代付回调
 * Date: 2020/12/28
 */
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type:text/html;charset=utf8");

include_once "../class/config.inc.php";
include_once "../model/Pay.php";
require_once '../class/xunfu/payment_common.php';

@file_put_contents('/tmp/xunfuautopayback.php.log', date('Y-m-d H:i:s') . "\n", FILE_APPEND);
@file_put_contents('/tmp/xunfuautopayback.php.log', $_REQUEST['merchant_code'] . "\n", FILE_APPEND);
@file_put_contents('/tmp/xunfuautopayback.php.log', $_REQUEST['data'] . "\n", FILE_APPEND);
@file_put_contents('/tmp/xunfuautopayback.php.log', $_REQUEST['sign'] . "\n", FILE_APPEND);

//接收回调信息
$m_sign = $_POST['sign'];
$m_merchant_code = $_POST['merchant_code'];
$m_data = $_POST['data'];

$sql = "SELECT * FROM " . DBPREFIX . "gxfcy_autopay WHERE `business_code` = '{$m_merchant_code}' AND `status` = 1 LIMIT 1";
//print_r($sql); die;
$result = mysqli_query($dbLink, $sql);
$count = mysqli_num_rows($result);
if($count == 0) {
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到xunfu出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit();
}


//解密信息
$payment_class = new payment_class($m_merchant_code,'');
$tmp_verifySign =$payment_class->verifySign($m_data,$m_sign);

//echo "-->".$tmp_verifySign."<BR>" ;


if ($tmp_verifySign == '1') {
    $tmp_decrypt= $payment_class -> depositCallback($m_data);
    $decrypt=json_decode($tmp_decrypt,true);
    if ($decrypt['status']==1){
    //    echo "-->".$tmp_decrypt."<BR>" ;
        // 接收参数
        $trans_id = $decrypt["trans_id"]; //商户号
        $merchant_user= $decrypt["merchant_user"]; // 商户用户号：可自定义
        $merchOrderNo = $decrypt["merchant_order_no"]; // 商户系统产生的唯一订单号
        $status = $decrypt['status']; // 1 表示成功，0表示失败
        $amount= $decrypt["amount"]; // 代付金额
        $process_time = $decrypt["process_time"]; //交易日期


        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merchOrderNo, true);
        echo "{\"error_msg\":\" \",\"status\":\"1\"}";
    }else{

        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merchOrderNo, false, 'fail');
        echo "{\"error_msg\":\"- \",\"status\":\"0\"}";
    }
}
else
{
    $oPayin = new Pay_model($dbMasterLink);
    $oPayin->updateAutoWithdrawer($merchOrderNo, false, 'fail');
    echo "{\"error_msg\":\"- \",\"status\":\"0\"}";
}

die;
