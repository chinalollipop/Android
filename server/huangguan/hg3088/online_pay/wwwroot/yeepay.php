<?php

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/yeepay.log');

$iPayid = $_REQUEST['payid'];
$sOid  = $_REQUEST['uid'];
$userid = $_REQUEST['userid'];
$fOrderAmount = $_REQUEST['order_amount'];
$sBankPayid = $_REQUEST['bankPayId'];
include ("../class/config.inc.php");
include "../class/address.mem.php";

$sql = "select ID,UserName as uname,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone,Notes from ".DBPREFIX.MEMBERTABLE." where ID='$userid' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

// ������֧��
$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` = '. $iPayid .' AND `status` = 1 limit 1';
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
if($iCou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$aRow = mysqli_fetch_assoc($oRes);
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
include '../class/yeepayCommon.php';

$p1_MerId    = $aRow['business_code'];
$merchantKey = $aRow['business_pwd'];
#	�̼������û�������Ʒ��֧����Ϣ.
##�ױ�֧��ƽ̨ͳһʹ��GBK/GB2312���뷽ʽ,�������õ����ģ���ע��ת��
$data = array();
$data['p0_Cmd'] = "Buy";
$data['p1_MerId'] = $p1_MerId;
$data['p2_Order'] = 'yee'.date("YmdHis").rand(100000,999999);
$data['p3_Amt'] = $fOrderAmount;
$data['p4_Cur'] = "CNY";
$data['p5_Pid'] = 'test';
$data['p6_Pcat']= 'test';
$data['p7_Pdesc'] = 'test';
$data['p8_Url'] = $aRow['url'].'/yee_return_url.php';  // �첽֪ͨ
$data['p9_SAF'] = '0';
//$data['pb_ServerNotifyUrl']=$aRow['url'].'/yee_return_url1.php'; // ͬ����ת
$data['pa_MP'] = $row['uname'].'|'.$iPayid.'|'.$sOid.'|'.$sBankPayid;//����������Ϣ hg308877|13|4470|ICBC-NET-B2C  �û���|����id|�û�id|֧����ʽ����;
$data['pd_FrpId'] = ''; // 1��������д����ֱ����ת���ױ�֧����Ĭ��֧�����ء�2������д����ֱ��������Ӧ������֧��ҳ�档
$data['pm_Period'] = 7; // Ĭ��ֵ��7 ��������Ч�����ֵΪ 180 �죬���� 180 �찴 180 �����
$data['pn_Unit'] = 'day';
$data['pr_NeedResponse']= 1;
$data['pt_UserName'] = '';
$data['pt_PostalCode']= '';
$data['pt_Address'] = '';
$data['pt_TeleNo'] = '';
$data['pt_Mobile'] = '';
$data['pt_Email'] = '';
$data['pt_LeaveMessage'] = '';
$hmac_safe = gethamc_safe($data);
$hmac = HmacMd5(implode($data),$merchantKey);


//var_dump( $aRow );

?> 
<html>
<head>
<title>To YeePay Page</title>
</head>
<body onload="document.yeepay.submit();">
<form name='yeepay' action='<?php echo $reqURL_onLine; ?>' method='get'>
<input type='hidden' name='p0_Cmd'					value='<?php echo $data['p0_Cmd']; ?>'>
<input type='hidden' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
<input type='hidden' name='p2_Order'				value='<?php echo $data['p2_Order']; ?>'>
<input type='hidden' name='p3_Amt'					value='<?php echo $data['p3_Amt']; ?>'>
<input type='hidden' name='p4_Cur'					value='<?php echo $data['p4_Cur']; ?>'>
<input type='hidden' name='p5_Pid'					value='<?php echo $data['p5_Pid']; ?>'>
<input type='hidden' name='p6_Pcat'					value='<?php echo $data['p6_Pcat']; ?>'>
<input type='hidden' name='p7_Pdesc'				value='<?php echo $data['p7_Pdesc']; ?>'>
<input type='hidden' name='p8_Url'					value='<?php echo $data['p8_Url']; ?>'>
<input type='hidden' name='p9_SAF'					value='<?php echo $data['p9_SAF']; ?>'>
<input type='hidden' name='pa_MP'						value='<?php echo $data['pa_MP']; ?>'>
<input type='hidden' name='pd_FrpId'				value='<?php echo $data['pd_FrpId']; ?>'>
<input type='hidden' name='pm_Period'				value='<?php echo $data['pm_Period']; ?>'>
<input type='hidden' name='pn_Unit'				  value='<?php echo $data['pn_Unit']; ?>'>
<input type='hidden' name='pr_NeedResponse'	value='<?php echo $data['pr_NeedResponse']; ?>'>
<input type='hidden' name='pt_UserName'			value='<?php echo $data['pt_UserName']; ?>'>
<input type='hidden' name='pt_PostalCode'		value='<?php echo $data['pt_PostalCode']; ?>'>
<input type='hidden' name='pt_Address'			value='<?php echo $data['pt_Address']; ?>'>
<input type='hidden' name='pt_TeleNo'				value='<?php echo $data['pt_TeleNo']; ?>'>
<input type='hidden' name='pt_Mobile'				value='<?php echo $data['pt_Mobile']; ?>'>
<input type='hidden' name='pt_Email'			  value='<?php echo $data['pt_Email']; ?>'>
<input type='hidden' name='pt_LeaveMessage'	  value='<?php echo $data['pt_LeaveMessage']; ?>'>
<input type="hidden" name="hmac_safe"         value='<?php  echo $hmac_safe;?>'>
<input type='hidden' name='hmac'				  value='<?php echo $hmac; ?>'>
</form>
</body>
</html>