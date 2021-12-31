<?php
//ini_set("display_errors","Off");
//error_reporting(E_ALL);

header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date("Y-m-d H:i:s") .'-'. serialize($_REQUEST).PHP_EOL, 3, '/tmp/csjpay_return.log');

/*$_REQUEST = Array
(
  ["uid"]=>"0e6a58e61aeec39b8f46ra2"
  ["userid"]=>"113018"
  ["langx"]=>"zh-cn"
  ["payid"]=>"104"
  ["pid"]=>"2"
  ["banklist"]=> "7"
  ["order_amount"]=>"100"
)*/

$iPayid = $_REQUEST['payid']; //第三方支付网银配置id
$iUid  = $_REQUEST['uid'];
$userid = $_REQUEST['userid'];
$banklist = isset($_REQUEST['banklist']) ? $_REQUEST['banklist'] : 0;
$onlineIntoBank = isset($_REQUEST['onlineIntoBank']) ? $_REQUEST['onlineIntoBank'] : 0;    // app
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

include_once "../../class/config.inc.php";
include_once "../../class/address.mem.php";
include_once "../../class/paytype.php";

if(isset($banklist) ) {
    $_REQUEST['banklist'] = strval($banklist);
} elseif(isset($onlineIntoBank) ) {
    $_REQUEST['banklist'] = strval($onlineIntoBank);
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
@error_log(date("Y-m-d H:i:s") . serialize($payDatas).PHP_EOL, 3, '/tmp/csjpay_return.log');

/*
* 0通道编码：0支付宝转卡【300-50000】
* 2通道编码：2银联扫码    【100-50000】
* 3通道编码：3网银支付    【100-50000】
* 4通道编码：8支付宝个码【1000-50000】
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
$apiurl = "https://api.bixin88.com/api/order/placeOrder";/*接口提交地址*/
$params['merchno'] = $merchno = strval($aThirdPay['business_code']);    //商户号
$params['orderId'] = $orderId = $orderNo = $sPrifix.'-'.date("YmdHis").$aThirdPay['id'].'_'.rand(1000000, 9999999); //商户订单号
$params['payType'] = $payType = $sPayCode = $bankCode;  // 接收银行代码跳转到接口进行选择支付
$params['amount'] = $amount = $orderAmount = sprintf("%.2f", $_REQUEST['order_amount']); //转账额度

//同步和异步跳转地址
$params['asyncUrl'] = $asyncUrl = $aThirdPay['url'].'/'.$aThirdPay['thirdpay_code'].'/csj_notify.php';   //服务器底层通知地址,支付完成后,异步通知地址
$params['syncUrl'] = $syncUrl = '';   //同步通知地址 不参与签名

$params['requestTime'] = $requestTime = (new DateTime(null, new DateTimeZone('GMT+8') ))->format("YmdHis");     //请求时间
$params['attach'] = $attach = $aUser['uname'].'|'.$iPayid.'|'.$aUser['ID'].'|'.$bankCode;//备注信息，会员名称|渠道id|用户id|支付方式代码，请注意编码

$key = $aThirdPay['business_pwd'];  //商户Key,由API分配

/*
amount=300.00
&asyncUrl=http://pay.hg01455.com/csj/csj_notify.php
&attach=john103|104|51013|8
&merchno=a6e7255dd1
&orderId=csj-20200916090423104_4390006
&payType=8
&requestTime=20200916210423
&secretKey=779858c4f4294556a818dba814296cdf
*/
$sign_str = create_sign($params);   //获取签名字符串
$string = $sign_str . 'secretKey=' . $key;echo '<br>';
//echo $string . '<br>';
//签名
$params['sign'] = $sign = strtolower(md5($string));
//echo 'sign:' . $params['sign'] . '<br>';
//exit;

// 插入一条订单到数据库，方便查询会员三方的订单
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
$userip = get_ip();   //支付用户 IP 地址必传，风控需要
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
$sInsData = '';
foreach ($thirdData as $key => $value){
    $sInsData.= "`$key` = '{$value}',";
}
$sInsData = rtrim($sInsData, ',');
$sql1 = "insert into `".DBPREFIX."web_thirdpay_data` set $sInsData";
mysqli_query($dbMasterLink,$sql1);

function create_sign($array){
    ksort($array); #排列数组 将数组已a-z排序
    $result = '';
    foreach($array as $key=>$v){
        if ($key !== 'syncUrl' && $key !== 'sign'){
            $v = trim($v);
            if($v != ''){
            $result  .= $key  . '=' . $v . '&';
            }
            //echo 'result:' .  $result;echo '<br>';
        }
    }
    return $result;
}

?>

<html ><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>创世纪充值接口-提交信息处理</title>

</head>
<body onload="">
<div>
    <form id="form1" name="form1" method="post" action="<?php echo $apiurl; ?>" target="_self">
        <input type='hidden' name='merchno' value="<?php echo $merchno;?>" />
        <input type='hidden' name='orderId' value="<?php echo $orderId;?>" />
        <input type='hidden' name='payType' value="<?php echo $payType;?>" />
        <input type='hidden' name='amount' value="<?php echo $amount;?>" />
        <input type='hidden' name='asyncUrl' value="<?php echo $asyncUrl;?>" />
        <!--<input type='hidden' name='syncUrl' value="<?php /*echo $syncUrl;*/?>" />-->
        <input type='hidden' name='requestTime' value="<?php echo $requestTime;?>" />
        <input type='hidden' name='attach' value="<?php echo $attach;?>" />
        <input type='hidden' name='sign' value="<?php echo $sign;?>" />
    </form>
    <script type="text/javascript">
        document.forms[0].submit();
    </script>
</div>
</body></html>