<?php
//header ( 'Content-Type: text/html; charset=utf-8' );
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type:text/html;charset=utf8");
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}

//接收异步通知数据
//2020-12-11 12:05:50-php://input:{"amount":"100","merchantId":"1001","merchantOrderNo":"TK2020121022435851013978602","sign":"NzrBDZxaFpnzmWZakTN61rzO1hU4zCmrH/gYcGzIamFT49GHIGPuX845NK5lyoPijLT06MSDD1c2t8j6qxBUdH3plVxEU9Fmwta15NzKmVnJ1HIXH5UCmTTClx+KBJ+bJFPQ4nVayyH85/MR3dD1S7bLQ1ILT2SXREKDEjx7BdI=","status":"1"}
@error_log(date('Y-m-d H:i:s').'-php://input:'.file_get_contents("php://input").PHP_EOL, 3, '/tmp/jmautopayback.log');

include_once "../class/config.inc.php";
include_once "../class/juming/Config.php";
include_once "../class/juming/ServiceUtil.php";
include_once "../model/Pay.php";



$post = file_get_contents("php://input");
//$post = '{"amount":"100","merchantId":"1001","merchantOrderNo":"TK2020121101590051013368548","sign":"a7yUQwnHBBKCbEHM9P8JbzomWPhI6jhqe/6BMnkd1wlmd0Whn1GweOWsKkcZ1Wk1kBs1QmQtnf5JDuv2uSydWHoWqtsiBH6RJUUIXh3eVVmYVfbhQlTUVC4E7BL3KghrPgLjM5iyRv/bjYqj38owcCCi16Clq5/q/VIzb7YK4nk=","status":"1"}';
$resultArr = json_decode($post, true);
/* 以这个为准
$resultArr = $post = Array(
    [amount] => 100
    [merchantId] => 1001
    [merchantOrderNo] => TK2020121101590051013368548
    [sign] => a7yUQwnHBBKCbEHM9P8JbzomWPhI6jhqe/6BMnkd1wlmd0Whn1GweOWsKkcZ1Wk1kBs1QmQtnf5JDuv2uSydWHoWqtsiBH6RJUUIXh3eVVmYVfbhQlTUVC4E7BL3KghrPgLjM5iyRv/bjYqj38owcCCi16Clq5/q/VIzb7YK4nk=
    [status] => 1
)
*/


$merchantId = $resultArr["merchantId"]; // 商户号
$merOrderNo = $resultArr["merchantOrderNo"]; // 代付下单订单号
// 判断当前第三方自动出款支付渠道
$sSql = "SELECT business_code,business_pwd  FROM `".DBPREFIX."gxfcy_autopay` WHERE business_code ='$merchantId' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$swinfo = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到第三方出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

$reSign = $resultArr["sign"];
unset($resultArr["sign"]);
ksort($resultArr);
$publickey = ServiceUtil::publicKeyStr(Config::publicKey);
$signData = ServiceUtil::get_sign($resultArr);

//验签
$flag = ServiceUtil::verify($signData, $reSign, $publickey);
//@error_log('jmautopaybackflag:'.$flag.PHP_EOL, 3, '/tmp/jmautopayback.log');


if($flag){
    //判断订单状态
    if($resultArr["status"] == "1"){
        //ServiceUtil::writelog("debug","代付订单成功：".$resultArr["merchantOrderNo"]);
        @error_log("代付订单成功：".$resultArr["merchantOrderNo"].PHP_EOL, 3, '/tmp/jmautopayback.log');
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, true);
        //ServiceUtil::writelog("debug","代付回调响应：success");
        @error_log("代付回调响应：success".PHP_EOL, 3, '/tmp/jmautopayback.log');
        echo "success";
    }
    if($resultArr["status"] == "2"){
        //ServiceUtil::writelog("debug","代付订单失败：".$resultArr["merchantOrderNo"]);
        @error_log("代付订单失败：".$resultArr["merchantOrderNo"].PHP_EOL, 3, '/tmp/jmautopayback.log');
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, false, 'fail');
        //ServiceUtil::writelog("debug","代付回调响应：fail");
        @error_log("代付回调响应：fail".PHP_EOL, 3, '/tmp/jmautopayback.log');
        echo "fail";
    }

    exit;
}else{
    //ServiceUtil::writelog("debug","代付订单验签失败：".$resultArr["merchantOrderNo"]);
    @error_log("代付订单验签失败：".$resultArr["merchantOrderNo"].PHP_EOL, 3, '/tmp/jmautopayback.log');
    echo "<script type='text/javascript'>alert('聚名代付出款通知,校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

