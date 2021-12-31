<?php

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/sfpay_return.log');

$iPayid = $_REQUEST['payid'];
$iUid  = $_REQUEST['uid'];
$userid = $_REQUEST['userid'];
$fOrderAmount = $_REQUEST['order_amount'];
include "../class/config.inc.php";
include "../class/address.mem.php";
include "../class/paytype.php";

$sql = "select ID,UserName as uname,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone,Notes from ".DBPREFIX.MEMBERTABLE." where ID='$userid' ";
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

/**
 * @param  $aThirdPay     当前第三方支付数据
 * @param  $reqData       当前请求参数
 * 返回支付方式 pay_type，支付方式代码 PayCode，第三方简写 sPrifix
 */
$payDatas = CompanyPayType($aThirdPay , $_REQUEST);

//$pay_type = $payDatas['pay_type'];
$iPayCode = $payDatas['iPayCode'];
$sPrifix = $payDatas['sPrifix'];

//-------------------------提交第三方数据 Start
$MemberID=$aThirdPay['business_code'];//商户号
$TransID = $sPrifix.date("YmdHis").rand(100000,999999);
$PayID=$iPayCode;//支付方式
$TradeDate=date("Ymdhis");//交易时间
$OrderMoney=$fOrderAmount*100;//订单金额，以分为单位
$ProductName='goods';//产品名称
$Amount=1;//商品数量
$Username='';//支付用户名
$AdditionalInfo= $aUser['uname'].'|'.$iPayid.'|'.$aUser['ID'].'|'.$iPayCode;//订单附加消息   13|4470|57   渠道id|用户id|支付方式代码
$PageUrl=$aThirdPay['url'].'/sf_page_url.php';//通知商户页面端地址
$ReturnUrl=$aThirdPay['url'].'/sf_return_url.php';//服务器底层通知地址
$NoticeType=1;//通知类型
$Md5key=$aThirdPay['business_pwd'];//md5密钥（KEY）
$MARK = "|";

//MD5签名格式
$Signature=md5($MemberID.$MARK.$PayID.$MARK.$TradeDate.$MARK.$TransID.$MARK.$OrderMoney.$MARK.$PageUrl.$MARK.$ReturnUrl.$MARK.$NoticeType.$MARK.$Md5key);
$payUrl='https://gw.sslsf.com/v4.aspx';//网关地址
//$payUrl='./sf_return_url.php';//网关地址
$TerminalID = $aRow['pay_id'];
$InterfaceVersion = "4.0";
$KeyType = "1";
//$_SESSION['OrderMoney']=$OrderMoney; //设置提交金额的Session
//此处加入判断，如果前面出错了跳转到其他地方而不要进行提交

//-------------------------提交第三方数据 End

?>

<html ><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>充值接口-提交信息处理</title>

</head>
<body onload="document.form1.submit()">
<div>
    <form id="form1" name="form1" method="post" action="<?php echo $payUrl; ?>" target="_self">
        <input type='hidden' name='MemberID' value="<?php echo $MemberID; ?>" />
        <input type='hidden' name='TerminalID' value="<?php echo $TerminalID; ?>"/>
        <input type='hidden' name='InterfaceVersion' value="<?php echo $InterfaceVersion; ?>"/>
        <input type='hidden' name='KeyType' value="<?php echo $KeyType; ?>"/>
        <input type='hidden' name='PayID' value="<?php echo $PayID; ?>" />
        <input type='hidden' name='TradeDate' value="<?php echo $TradeDate; ?>" />
        <input type='hidden' name='TransID' value="<?php echo $TransID; ?>" />
        <input type='hidden' name='OrderMoney' value="<?php echo $OrderMoney; ?>" />
        <input type='hidden' name='ProductName' value="<?php echo $ProductName; ?>" />
        <input type='hidden' name='Amount' value="<?php echo $Amount; ?>" />
        <input type='hidden' name='Username' value="<?php echo $Username; ?>" />
        <input type='hidden' name='AdditionalInfo' value="<?php echo $AdditionalInfo; ?>" />
        <input type='hidden' name='PageUrl' value="<?php echo $PageUrl; ?>" />
        <input type='hidden' name='ReturnUrl' value="<?php echo $ReturnUrl; ?>" />
        <input type='hidden' name='Signature' value="<?php echo $Signature; ?>" />
        <input type='hidden' name='NoticeType' value="<?php echo $NoticeType; ?>" />
    </form>
</div>
</body></html>