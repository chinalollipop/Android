<?php

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/yeeback_return.log');

include ("../class/config.inc.php");
include "../model/Userlock.php";
include "../model/Pay.php";
include '../class/yeepayCommon.php';
$data=array();
$data['p1_MerId']		 = $_REQUEST['p1_MerId'];
$data['r0_Cmd']		   = $_REQUEST['r0_Cmd'];
$data['r1_Code']	   = $_REQUEST['r1_Code'];
$data['r2_TrxId']    = $_REQUEST['r2_TrxId'];
$data['r3_Amt']      = $_REQUEST['r3_Amt'];
$data['r4_Cur']		   = $_REQUEST['r4_Cur'];
$data['r5_Pid']		   = $_REQUEST['r5_Pid'] ;
$data['r6_Order']	   = $_REQUEST['r6_Order'];
$data['r7_Uid']		   = $_REQUEST['r7_Uid'];
$data['r8_MP']		   = $_REQUEST['r8_MP'] ;
$data['r9_BType']	   = $_REQUEST['r9_BType'];
$data['hmac']			   = $_REQUEST['hmac'];
$data['hmac_safe']   = $_REQUEST['hmac_safe'];

$aData = explode('|',$AdditionalInfo);
/*
$sql = "select ID,UserName as uname,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone,Notes from ".DBPREFIX.MEMBERTABLE." where oid='$aData[2]' and Status<2";
$result = mysqli_query($dbMasterLink,$sql);
$row=mysqli_fetch_array($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('/tpl/logout_warn.html','_top')</script>";
    exit;
}

$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` ='.$aData[1].' AND `status` = 1 limit 1';
// 第三方支付
$oRes = mysqli_query($dbMasterLink,$sSql);
if(!$oRes ){
    echo mysqli_connect_error($dbMasterLink); die;
}
$iCou = mysqli_num_rows($oRes);
$aRow = mysqli_fetch_array($oRes);
if($iCou==0){
    echo "<script>window.open('/tpl/logout_warn.html','_top')</script>";
    exit;
}*/

//本地签名
$hmacLocal = HmacLocal($data);
// echo "</br>hmacLocal:".$hmacLocal;
$safeLocal= gethamc_safe($data);
// echo "</br>safeLocal:".$safeLocal;

//验签
if($data['hmac']	 != $hmacLocal    || $data['hmac_safe'] !=$safeLocal)
{
    echo "验签失败";
    return;
}else{
    if ($data['r1_Code']=="1" ){

        if($data['r9_BType']=="1"){

            echo  "支付成功！在线支付页面返回";
        }elseif($data['r9_BType']=="2"){
            #如果需要应答机制则必须回写success.
            echo "SUCCESS";
            return;
        }

    }
}
