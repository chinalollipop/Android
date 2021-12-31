<?php
/**
 * 易支付 需要查询订单更新出款状态
 * redis  list
 * key yzfpay_cash_autock
 * 获取注单号，代付查询
 */
ini_set("display_errors", "1");

if (php_sapi_name() != "cli") {
    exit("只能在_cli模式下面运行！");
}

///www/huangguan/hg3088/agents
define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/configUserCli.php";
require(CONFIG_DIR."/app/agents/include/define_function.php");
require(CONFIG_DIR."/app/agents/include/auto/yizhifu/payCommon.php");
require(CONFIG_DIR."/app/agents/model/Pay.php");

// 接收参数， 从redis 获取代付订单号
$redisObj = new Ciredis();

//redis取出对应的订单号出来
$orderNo = $redisObj->popMessage("yzfpay_cash_autock");
$orderNo_id = $orderNo[0];

if(!$orderNo_id) {
    return false;
}
//@error_log( 'agents_redis订单号:'.$orderNo_id . PHP_EOL,  3,  '/tmp/yzfautopayback.log');

$orderNoData = []; //声明一个数组队列储存单号
array_push($orderNoData,$orderNo_id);
sleep('120');
$orderNoItem = array_shift($orderNoData);


// 判断当前第三方自动出款支付渠道
$sSql = "SELECT business_code,business_pwd  FROM `".DBPREFIX."gxfcy_autopay` WHERE method ='yzfpay_cash_autock' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$yzfinfo = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "渠道信息错误,未找到第三方出款支付渠道！";
    exit;
}
    // 代付提交成功，不等于成功代付，还需要调用代付结果查询判断

    // 代付结果查询请求参数
    $postParams["payKey"]       = strval($yzfinfo['business_code']);  // 商户号
    $postParams["orderNo"]      = $merOrderNo = $orderNoItem;  // 代付订单号
    $postParams["signType"]     = "MD5";    //签名算法，固定MD5

    $postParams["sign"] = getSignMsg($postParams, $yzfinfo['business_pwd']); //上述非空字段签名值

    $gateQueryUrl = $agentPay . "query";  //易支付查询地址
    $result = httpCurl($gateQueryUrl,$postParams);  //发起请求
    $queryResult=json_decode($result,true); //响应报文

    //a:8:{s:4:"sign";s:32:"AEB84F4A4C13C7B8772FC2DE42660469";s:6:"result";s:7:"success";s:11:"sett_status";s:7:"success";s:8:"signType";s:3:"MD5";s:11:"settOrderNo";s:20:"4Y202006292012266307";s:10:"settAmount";s:5:"16.00";s:3:"msg";s:6:"成功";s:8:"order_no";s:28:"TK20200629085629124675104084";}
    //@error_log('queryResult:'.serialize($queryResult) . PHP_EOL,  3,  '/tmp/yzfautopayback.log');
    if($queryResult['result'] == 'success') {  //该订单号请求成功

        // sett_status代付结果 success-代付成功，false-代付失败，somefail-部分失败,process-代付中，nofind-代付单不存在
        if($queryResult['sett_status'] == 'success'){
            @error_log( '订单号:'.$merOrderNo. '--SUCCESS' . PHP_EOL,  3,  '/tmp/yzfautopayback.log');
            echo 'SUCCESS';
            $oPayin = new Pay_model($dbMasterLink);
            $oPayin->updateAutoWithdrawer($merOrderNo, true);
        }elseif($queryResult['sett_status'] == 'false'){
            @error_log( '订单号:'.$merOrderNo. '--易支付->代付失败' . PHP_EOL,  3,  '/tmp/yzfautopayback.log');
            $oPayin = new Pay_model($dbMasterLink);
            $oPayin->updateAutoWithdrawer($merOrderNo, false, 'fail');
            echo "易支付->代付失败";

        }elseif($queryResult['sett_status'] == 'somefail'){
            @error_log( '订单号:'.$merOrderNo. '--易支付->部分失败' . PHP_EOL,  3,  '/tmp/yzfautopayback.log');
            $oPayin = new Pay_model($dbMasterLink);
            $oPayin->updateAutoWithdrawer($merOrderNo, false, 'fail');
            echo "易支付->部分失败";

        }else {
            @error_log( '订单号:'.$postParams["orderNo"]. '--易支付->人工向本平台确认结果' . PHP_EOL,  3,  '/tmp/yzfautopayback.log');
            echo "易支付->人工向本平台确认结果";

        }
    } else{
        echo "易支付->订单号请求结果失败";
    }

    exit;