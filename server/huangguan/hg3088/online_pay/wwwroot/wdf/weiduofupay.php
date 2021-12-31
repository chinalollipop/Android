<?php
//ini_set("display_errors","On");
//error_reporting(E_ALL);

header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date("Y-m-d H:i:s") .'-'. serialize($_REQUEST).PHP_EOL, 3, '/tmp/weiduofu_return.log');

/*$_REQUEST = Array
(
    [uid] => e89d8e312b78f0197e1ara5
    [userid] => 51013
    [langx] => zh-cn
    [payid] => 101
    [pid] => 1
    [banklist] => w_union
    [order_amount] => 100
)*/
$iPayid = $_REQUEST['payid']; //第三方支付网银配置id
$iUid  = $_REQUEST['uid'];
$userid = $_REQUEST['userid'];
$banklist = isset($_REQUEST['banklist']) ? $_REQUEST['banklist'] : '';
$onlineIntoBank = isset($_REQUEST['onlineIntoBank']) ? $_REQUEST['onlineIntoBank'] : '';    // app
//判断终端类型
if ($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) { // 14 原生android，13 原生ios
    $playSource=$_REQUEST['appRefer'];
}else{
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $playSource=3;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $playSource=4;
    }else{
        $playSource=22;
    }
}

//unset($_GET['banklist']);  //w_union 特殊字符
//unset($_POST['banklist']);  //w_union 特殊字符
//unset($_REQUEST['banklist']);  //w_union 特殊字符
//unset($_GET['onlineIntoBank']);  //w_union 特殊字符
//unset($_POST['onlineIntoBank']);  //w_union 特殊字符
//unset($_REQUEST['onlineIntoBank']);  //w_union 特殊字符

include_once "../../class/config.inc.php";
include_once "../../class/address.mem.php";
include_once "../../class/paytype.php";
include_once "../../model/Pay.php";
include_once "../xft/utils.php";

if(!empty($banklist) ) {
    $_REQUEST['banklist'] = $fxpaytype[$banklist];
} elseif(!empty($onlineIntoBank) ) {
    $_REQUEST['banklist'] = $fxpaytype[$onlineIntoBank];
}

if(empty($iPayid)) {
    echo '请选择有效的充值通道';
    return false;
}


$sql = "select ID,UserName as uname,Alias,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone,Notes from ".DBPREFIX.MEMBERTABLE." where ID='$userid' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

// 第三方支付
$sSql = 'SELECT id,title,account_company,business_code,business_pwd,url,depositNum,status,class,thirdpay_code FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` = '. $iPayid .' AND `status` = 1 limit 1';
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
if($iCou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$aRow = mysqli_fetch_assoc($oRes);

$aUser = $row;
$aThirdPay = $aRow;

/**
 * @param  $aThirdPay     当前第三方支付数据
 * @param  $reqData       当前请求参数
 * 返回支付方式 pay_type，支付方式代码 PayCode，第三方简写 sPrifix
 */
$payDatas = CompanyPayType($aThirdPay , $_REQUEST);
@error_log(date("Y-m-d H:i:s") . serialize($payDatas).PHP_EOL, 3, '/tmp/weiduofu_return.log');


/*
 * 1000:微信扫码 1002:微信直连-手机端(H5)
 * 1003:支付宝扫码 1004:支付宝直连-手机端(H5)
 * 1005:QQ钱包扫码 1006:QQ钱包直连-手机端(H5)
 * */
//$pay_type = $payDatas['pay_type'];
$bankCode = $iPayCode = $payDatas['iPayCode']; // 接收银行代码
$sPrifix = $payDatas['sPrifix'];

/* *
 * 当前接收参数
 * @param  uid    当前登录用户Oid
 * @param  userid 用户id
 * @param  langx  语言包
 * @param  payid  第三方支付id   98
 * @param  pid    支付渠道  0
 * @param  banklist  银行代码 962
 * @param  order_amount  转账金额
 * */

//-------------------------提交第三方数据 Start
$apiurl = "http://www.wppay.vip/pay";/*接口提交地址*/
$fxid = strval($aThirdPay['business_code']);    //商户号
$fxddh = $orderNo = $sPrifix.date("YmdHis").$aThirdPay['id'].rand(10000000, 99999999); //商户订单号
$fxdesc = 'weiduofu';
$fxfee = $orderAmount = sprintf("%.2f", $_REQUEST['order_amount']); //转账额度

//同步和异步跳转地址
$fxbackurl = $aThirdPay['url'].'/'.$aThirdPay['thirdpay_code'].'/wdf_page.php';    //同步通知地址 不参与签名
$fxnotifyurl = $PageUrl = $aThirdPay['url'].'/'.$aThirdPay['thirdpay_code'].'/wdf_notify.php';   //服务器底层通知地址,支付完成后,异步通知地址

$fxpay = $sPayCode = $payDatas['iPayCode'];  // 接收银行代码跳转到接口进行选择支付

/*
 * 异步数据类型 默认 1 返回数据为表单数据（Content-Type:multipart/form-data）
 * 2 返回 postjson 数据
 * */
$fxnotifystyle = '1';

$fxattch = $aUser['uname'].'|'.$iPayid.'|'.$aUser['ID'].'|'.$bankCode;//备注信息，会员名称|渠道id|用户id|支付方式代码，请注意编码
$fxip = $userip = get_ip();   //支付用户 IP 地址必传，风控需要

$key = $aThirdPay['business_pwd'];//商户Key,由API分配
// 当前源字符串 签名【md5(商务号+商户订单号+支付金额+异步通知地址+商户秘钥)】
$signSource = strval($fxid) . strval($fxddh) . strval($fxfee) . $fxnotifyurl. strval($key);
$sign = md5($signSource);//32位小写MD5签名值，UTF-8编码



$params = [
    'fxid' => $fxid,            // 商户号
    'fxddh' => $orderNo,        // 商户订单号
    'fxdesc' => $fxdesc,        // 商品名
    'fxfee' => $orderAmount,    // 金额
    'fxattch' => $fxattch,      // 备注信息
    'fxnotifyurl' => $fxnotifyurl,
    'fxbackurl' => $fxbackurl,  // 同步通知地址 不参与签名
    'fxpay' => $fxpay,          // 接收银行代码,请求通道
    'fxnotifystyle' => $fxnotifystyle,  //异步数据类型
    'fxip' => $fxip,            // 支付用户 IP
    'fxsign' => $sign,
];

$result = utils::curl_post($apiurl,$params);  // return true
// 失败
if((!$result['status']) || ($result['httpcode'] !== 200)) {
    print_r($result);
    /*$status = $result['status'];
    $describe = '获取失败!';
    $aData = $result['error'];
    original_phone_request_response($status, $describe, $aData);*/
}

if($result['status']) {
    // 插入三方数据
    $thirdData = [
        'userid' => $userid,
        'UserName' => $aUser['uname'],
        'Alias' => $aUser['Alias'],
        'merchantName' => $aThirdPay['title'],
        'PayType' => strval($aRow['id']), //
        'PayName' => $bankCode, //第三方充值渠道
        'thirdpay_code' => $aRow['thirdpay_code'],
        'Order_Code' => $orderNo,
        'thirdSysOrder' => '',
        'Gold' => $orderAmount,
        'UserTime' => date("Y-m-d H:i:s"),
        'SysTime' => '',
        'CallbackTime' => '',
        'AuditTime' => '',
        'Status' => '',
        'playSource' => $playSource,  //判断终端类型
        'ip' => $userip,
        'Remarks' => '',
        'Reviewer' => '',
    ];
    $oPayin = new Pay_model($dbMasterLink);
    $thirdResult = $oPayin->thirdDataOrder($thirdData);
    if(!$thirdResult) {
        $status = '401';
        $describe = '三方充值数据失败';
        original_phone_request_response('401',$describe,$aData);
    }

    /*if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
        $status = '200';
        $describe = '恭喜成功获取地址';
        $data['url'] = $result['payurl'];
        original_phone_request_response($status,$describe,$aData);
    } else {*/
        header('Location: ' . $result['payurl']);
        exit;
    //}
}

/*  {
    "status":0,
    "error":"获取支付链接失败.{
        "result":false,
        "errorMsg":{
            "code":101,
            "errorMsg":"Invalid_Data",
            "descript":"[ 104 ] 资料验证错误 - 订单金额超出范围 [ 300.00 - 9999.00 ]"
        },
        "data":null"
    }
    {
    "status":1,
    "payurl":"http:\\0929fpbb.cn/Pay/cardpay/id/7ac6c4d69f77a927"
    }
*/


?>

<!--<html ><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>维多付充值接口-提交信息处理</title>

</head>
<body onload="">
<div>
    <form id="form1" name="form1" method="post" action="<?php /*echo $apiurl; */?>" target="_self">
        <input type='hidden' name='fxid' value="<?php /*echo $fxid;*/?>" />
        <input type='hidden' name='fxddh' value="<?php /*echo $orderNo;*/?>" />
        <input type='hidden' name='fxdesc' value="<?php /*echo $fxdesc;*/?>" />
        <input type='hidden' name='fxfee' value="<?php /*echo $orderAmount;*/?>" />
        <input type='hidden' name='fxattch' value="<?php /*echo $fxattch;*/?>" />
        <input type='hidden' name='fxnotifyurl' value="<?php /*echo $fxnotifyurl;*/?>" />
        <input type='hidden' name='fxbackurl' value="<?php /*echo $fxbackurl;*/?>" />
        <input type='hidden' name='fxpay' value="<?php /*echo $fxpay;*/?>" />
        <input type='hidden' name='fxnotifystyle' value="<?php /*echo $fxnotifystyle;*/?>" />
        <input type='hidden' name='fxip' value="<?php /*echo $fxip;*/?>" />
        <input type='hidden' name='fxsign' value="<?php /*echo $sign;*/?>" />
    </form>
    <script type="text/javascript">
        document.forms[0].submit();
    </script>
</div>
</body></html>-->