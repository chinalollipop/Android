<?php

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}

$iPayid = $_REQUEST['payid'];
$iUid  = $_REQUEST['uid'];
$userid = $iHgUserid = $_REQUEST['userid'];
$fOrderAmount = $_REQUEST['order_amount'];
include "../../class/config.inc.php";
include "../../class/address.mem.php";
include "../../class/paytype.php";

$sql = "select ID,UserName as uname,Alias,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone,Notes from ".DBPREFIX.MEMBERTABLE." where ID='$iHgUserid' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
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

if ($aThirdPay['account_company']==5){ // 支付宝个码
    $iPayCode=8;
}else{
    $iPayCode=$_REQUEST['banklist'];   // 银行简码
}

$sPrifix='ccx';
$token = $aThirdPay['business_pwd'];

$data['merchno'] = $aThirdPay['business_code'];  //商户号
$data['orderId'] = $sPrifix.date("YmdHis").rand(100000,999999);;  //订单号
$data['payType'] = $iPayCode;  //支付类型有：（0：支付宝转卡;1:微信扫码【无可用】；2：银联扫码；3：综合支付;4：微信转账【维护】；5：支付宝转账【维护】；6：手机银行转账;7：银联快捷【维护】;8：支付宝个码【至少1000元】；9: 支付宝wap2/支付宝H5【无可用】）
$data['amount'] = number_format($fOrderAmount,2,'.','');;  //单位：元。精确小数点后2位
$data['asyncUrl'] = $aThirdPay['url'].'/ccx/ccx_notify_url.php'; // 异步通知过程的返回地址，需要以http://或者https://开头且没有任何参数(如存在特殊字符请转码,注:不支持参数)
$data['syncUrl'] = $aThirdPay['url'].'/ccx/ccx_notify_url.php'; // 同步通知过程的返回地址(在支付完成后璀璨星接口将会跳转到的商户系统链接地址)。注：若提交值无该参数，或者该参数值为空，则在支付完成后，璀璨星接口将不会跳转到商户系统(部分支付类型不进行同步通知)
$data['requestTime'] = date('YmdHis');
$data['attach'] = $aUser['uname'].'|'.$iPayid.'|'.$aUser['ID'].'|'.$iPayCode; //会员名称|渠道id|用户Oid|支付方式代码

$sign_str ='amount='.$data['amount'].'&asyncUrl='.$data['asyncUrl'].'&attach='.$data['attach'].'&merchno='.$data['merchno'].'&orderId='.$data['orderId'].'&payType='.$data['payType'].'&requestTime='.$data['requestTime'].'&syncUrl='.$data['syncUrl'].'&secretKey='.$token;
$data['sign']=md5($sign_str);
$url="https://api.cuicanxing888.com/api/order/placeOrder";

//print_r($data); die;

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
    'PayType' => $iPayid,
    'PayName' => $iPayCode,
    'thirdpay_code' => $aRow['thirdpay_code'],
    'Order_Code' => $data['orderId'],
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