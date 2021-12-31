<?php
/**
 * 众宝上分（银行、支付宝、微信、QQ）
 */
//ini_set("display_errors","Off");
//error_reporting(E_ALL);

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date("Y-m-d H:i:s") .'-'. serialize($_REQUEST).PHP_EOL, 3, '/tmp/zb_notify.log');

include "../../class/config.inc.php";
include "../../model/Userlock.php";
include "../../model/Pay.php";

// 回调地址示例
//http://pay.hg01455.com/zb/zb_notify.php?orderid=zbzfb2020090605585210031409100&result=1&amount=300.00&systemorderid=A20090617585264353198011&completetime=20200906180243&notifytime=20200906180243&sign=69f1d257247734223f7b9e91b67d7834&attach=hg0086%7c100%7c113018%7c1003&sourceamount=300.00

$OrderId = $ordernumber = urldecode($_REQUEST["orderid"]);		//商户系统传入的orderid
$Result = urldecode($_REQUEST["result"]);		//订单结果 0：支付成功
$Amount = urldecode($_REQUEST["amount"]);		//订单金额 单位元
$SourceAmount = $paymoney = urldecode($_REQUEST["sourceamount"]);		//提交金额 单位元

$Systemorderid= urldecode($_REQUEST["systemorderid"]);		//此次订单过程中系统内的订单Id
$Completetime = urldecode($_REQUEST["completetime"]);	//订单时间
$Sign = urldecode($_REQUEST["sign"]);	//MD5签名

$notifytime = $_REQUEST['notifytime'];
$attach = $_REQUEST['attach'];  // hg0086|100|113018|1003  会员名称|渠道id|用户uid|支付方式代码
$aData = explode('|',$attach); //备注信息

// 查询第三方支付配置
$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` ='.$aData[1].' AND `status` = 1 limit 1';
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$aRow = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}
$sql = "select ID from ".DBPREFIX.MEMBERTABLE." where ID='$aData[2]' and Status<2";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    echo "<script type='text/javascript'>alert('该账户不存在！');window.opener=null;window.open('', '_self');window.close();</script>";
    //echo "<script>window.open('/tpl/logout_warn.html','_top')</script>";
    exit;
}

$MemberID = $merchantCode  = $aRow['business_code'];   //商户号
$Md5key = $aRow['business_pwd'];
//加签源字符串
//$sign_str1 = "orderid=".$OrderId."&result=".$Result."&amount=".$Amount."&systemorderid=".$Systemorderid."&completetime=".$Completetime."&key=".$Md5key;
//$SignLocal = md5($sign_str);    //加签字符串
//echo 'sign_str:'.$sign_str1;echo '<br>';

$sign_str = sprintf("orderid=%s&result=%s&amount=%s&systemorderid=%s&completetime=%s&key=%s", $OrderId, $Result, $Amount, $Systemorderid, $Completetime, $Md5key);
if ($Sign == md5($sign_str)) {
    $oUserLock = new Userlock_model($dbMasterLink);
    $userInfo=$oUserLock->lock($row['ID']);
    $userInfoArr = json_decode($userInfo,true);
    if( is_array($userInfoArr) && count($userInfoArr)>0){
    	$oPayin = new Pay_model($dbMasterLink);
	    //$ThirdPayCode=$aRow['thirdpay_code'];
	    // 用户个人信息，回传参数，商户号，订单号，金额，第三方简称（zb 众宝）
	    $oPayin->userpayin($userInfoArr, $aData, $MemberID, $ordernumber, $paymoney, $aRow);
        $oUserLock->commit_lock();

        exit('success');
    }else{
        exit("上分失败,数据操作错误");
    }
} else {
    exit("Md5CheckFail'");//MD5校验失败
}
