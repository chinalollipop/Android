<?php
//ini_set('display_errors', '1');
//header ( 'Content-Type: text/html; charset=utf-8' );
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-type:text/html;charset=utf8");
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date('Y-m-d H:i:s').'-'.serialize($_REQUEST).PHP_EOL, 3, '/tmp/mayiautopayback.log');

include_once "../class/config.inc.php";
include_once "../class/mayi/des3cbc.php";
include_once "../model/Pay.php";

$mcode = $_REQUEST['mcode'];

$client_num = $resultArr["client_num"]; // 商户号
// 判断当前第三方自动出款支付渠道
$sSql = "SELECT business_code,business_pwd  FROM `".DBPREFIX."gxfcy_autopay` WHERE business_code ='$mcode' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$myinfo = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到第三方出款支付渠道！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

$cpClass = new \des3cbc($myinfo['business_pwd']);
$a = $cpClass->decrypt3DES(base64_decode($_REQUEST['params']));
$aParams = json_decode($a,true);
@error_log(date('Y-m-d H:i:s').'-'.serialize($aParams).PHP_EOL, 3, '/tmp/mayiautopayback.log');

/**
 * params加密前参数(json格式)：
business	业务 固定值:  Notify	string
business_type	业务编码:    30103	int
depositid	网银流水号	String
account	收款卡号	String
time	汇款时间	String
paccount	汇款卡号	string
popeningbank	汇款方开户银行(urlencode编码)	String
pname	汇款人名字(urlencode编码)	String
amount	收款金额	float
status	交易状态(urlencode编码)	String
remark	备注(urlencode编码)	String
timestamp	时间戳	int(10)
sign	签名字符(查看附签名规则)	string
 * */

$paramers = array(
    'business'=>$aParams['business'],
    'business_type'=>$aParams['business_type'],
    'api_sn'=>$aParams['api_sn'],
    'order_sn'=>$aParams['order_sn'],
    'money'=>$aParams['money'],
    'bene_no'=>$aParams['bene_no'],
    'bank_id'=>$aParams['bank_id'],
    'payee'=>$aParams['payee'],
    'status'=>$aParams['status'],
    'ctime'=>$aParams['ctime'],
    'feedback_error'=>$aParams['feedback_error'],
    'feedback_time'=>$aParams['feedback_time'],
    'debit_no'=>$aParams['debit_no'],
    'timestamp'=>$aParams['timestamp'],
);

//获取签名字符串
$sign_str = create_sign($paramers);
$string = $sign_str . 'key=' . $myinfo['business_pwd'];

//签名
$paramers['sign'] = strtoupper(md5($string));

if($aParams['sign'] == $paramers['sign']){
    $merOrderNo = $aParams['api_sn'];
    if($aParams["status"] == 50){
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, true);
        echo "success";
    } else {
        $oPayin = new Pay_model($dbMasterLink);
        $oPayin->updateAutoWithdrawer($merOrderNo, false, 'fail');
        echo "蚂蚁代付失败";
    }
    exit;
}else{
    echo "<script type='text/javascript'>alert('蚂蚁代付出款通知,校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}


function create_sign($array){
    ksort($array); #排列数组 将数组已a-z排序
    $result = '';
    foreach($array as $key=>$v){
        if ($key !== 'notifyurl' && $key !== 'sign'){
            $v = trim($v);
            //if($v != '0'){
                $result  .= $key  . '=' . $v . '&';
            //}
        }
    }
    return $result;
}

