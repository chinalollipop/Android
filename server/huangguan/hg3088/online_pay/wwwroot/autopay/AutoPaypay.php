<?php

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
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
$iPayid = $_REQUEST['payid'];
$userid  = $_REQUEST['uid'];
$iHgUserid = $_REQUEST['userid'];
$fOrderAmount = $_REQUEST['order_amount'];
include "../../class/config.inc.php";
include "../../class/address.mem.php";
include "../../class/paytype.php";

$sql = "select ID,UserName as uname,LoginIP,online_status,Alias,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone,Notes from ".DBPREFIX.MEMBERTABLE." where ID='$iHgUserid' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
switch ($playSource){
    case 22: $clientTerminal=1; break;
    case 3:
    case 13: $clientTerminal=3; break;
    case 4:
    case 14: $clientTerminal=2; break;
    default: $clientTerminal=1; break;
}

// 第三方支付
$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` = '. $iPayid .' AND `status` = 1 limit 1';
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
if($iCou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$aRow = mysqli_fetch_assoc($oRes);

$aUser = $row;
$aThirdPay = $aRow;

$iPayCode=5; //网银支付

$sPrifix='atp';
$token = $aThirdPay['business_pwd'];
$userip = get_ip();   //支付用户 IP 地址必传，风控需要


$data['version'] = "V1.0";
$data['appId'] = $aThirdPay['business_code'];  //商户号
$data['orderType'] = $iPayCode;  // 支付宝扫码：1；支付宝APP：2；微信扫码：3；微信APP：4；网银支付：5
$data['merchOrderNo'] = $sPrifix.date("YmdHis").rand(100000,999999);  //订单号
$data['orderDate'] = date('YmdHis');  // 交易日期
$data['amount'] = number_format($fOrderAmount,2,'.','');  //单位：元。精确小数点后2位
$data['notifyUrl'] = $aThirdPay['url'].'/autopay/AutoPay_notify_url.php'; // 服务器异步通知商户接口路径，代收平台主动调商户接口通知订单代收结果。(若不需回调则传送空值)
$data['clientIp'] = $userip;
$data['clientAccount'] = $aUser['uname'];
$data['clientTerminal'] = $clientTerminal;
$data['merchRemark'] = $aUser['uname'].'|'.$iPayid.'|'.$aUser['ID'].'|'.$iPayCode; //会员名称|渠道id|用户Oid|支付方式代码

$sign_str ='amount='.$data['amount'].'&appId='.$data['appId'].'&clientAccount='.$data['clientAccount'].'&clientIp='.$data['clientIp'].'&merchOrderNo='.$data['merchOrderNo'].'&notifyUrl='.$data['notifyUrl'].'&orderDate='.$data['orderDate'].'&orderType='.$data['orderType'].'&key='.$token;
$data['sign'] = md5($sign_str);
$data['signType'] = 'MD5';
//$url="http://pay-test.autopayla.com/DMAW2KD7/autoPayDs/sendOrder.zv"; // 测试通道
$url="http://pay.autopayla.com/DMAW2KD7/autoPayDs/sendOrder.zv"; // 正式通道

//print_r($sign_str); die;
//print_r($data); die;

// 插入一条订单到数据库，方便查询会员三方的订单

$thirdData = [
    'userid' => $aUser['ID'],
    'UserName' => $aUser['uname'],
    'Alias' => $aUser['Alias'],
    'merchantName' => $aThirdPay['title'],
    'PayType' => $iPayid,
    'PayName' => $iPayCode,
    'thirdpay_code' => $aRow['thirdpay_code'],
    'Order_Code' => $data['merchOrderNo'],
    'thirdSysOrder' => '',
    'Gold' => $data['amount'],
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
//echo $sql1; die;
if(mysqli_query($dbMasterLink,$sql1)) {

}else{
    exit('充值订单入库失败，请重新下单充值');
}




?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body onLoad="document.dinpayForm.submit();">
<!--<body>-->
<form id="dinpayForm" name="dinpayForm" method="post" action="<?php echo $url;?>" target="_self">
    <?php foreach($data as $key=>$value):?>
        <div style="width: 600px; text-align: right;">
            <input type="hidden" name="<?=$key?>" value="<?=$value?>" size="50">
        </div>
    <?php endforeach;?>
</body>
</html>