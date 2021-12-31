<?php
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/fkt_return_url.log');

include "../class/config.inc.php";
require("fkt/helper.php");
require("fkt/AES.php");
include "../model/Pay.php";
include "../model/Userlock.php";

$MemberID = $merchantCode = $_REQUEST["merchant_code"]; //商户号
$orderNo = $_REQUEST["order_no"]; //商户唯一订单号
$orderAmount = $_REQUEST["order_amount"]; //商户订单总金额
$orderTime = $_REQUEST["order_time"]; //商户订单时间
$trade_status = $_REQUEST["trade_status"]; //商户交易状态
$tradeNo = $_REQUEST["trade_no"]; // 支付平台订单号
$returnParams = $_REQUEST["return_params"]; //商户支付请求时传递，通知商户会回传该参数
$sign= $_REQUEST["sign"];

//回传参数包含会员表Oid , 网银配置表id  , 1。  返回数组   hg308877|13|id|57   会员名称|渠道id|用户id|支付方式代码
$aData = explode('|',$returnParams);

// 当前支付渠道
$sSql = "SELECT * FROM `".DBPREFIX."gxfcy_pay` WHERE business_code ='".$merchantCode."' AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$aThirdPayRow = mysqli_fetch_assoc($oRes);
if($iCou==0){
    //echo "<script>window.open('/tpl/logout_warn.html','_top')</script>";
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到相关数据！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

// 当前登录账号

$sql = "select ID from ".DBPREFIX.MEMBERTABLE." where ID='$aData[2]' and Status<2";

$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    echo "<script type='text/javascript'>alert('该账户不存在！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

// _sign值生成参数
$Md5key = $aThirdPayRow['business_pwd'];
$kvs = new KeyValues($Md5key);
$kvs->add("merchant_code", $merchantCode);
$kvs->add("order_no", $orderNo);
$kvs->add("order_amount", $orderAmount);
$kvs->add("order_time", $orderTime);
$kvs->add("trade_status", $trade_status);
$kvs->add("trade_no", $tradeNo);
$kvs->add("return_params", $returnParams);
$_sign = $kvs->sign();
//echo $_sign .'      '.$_REQUEST["sign"];;echo '<br>';


//MD5签名格式
if ($_sign == $sign) {
    if ($trade_status == "success") {
        $oUserLock = new Userlock_model($dbMasterLink);
        $userInfo=$oUserLock->lock($row['ID']);
    	$userInfoArr = json_decode($userInfo,true);
	    if( is_array($userInfoArr) && count($userInfoArr)>0){
	    	$oPayin = new Pay_model($dbMasterLink);
	        //校验通过开始处理订单
	        $result = $oPayin->UserPayin($userInfoArr, $aData, $MemberID, $tradeNo, $orderAmount, $aThirdPayRow);
	        $oUserLock->commit_lock();
	        echo "<script type='text/javascript'>alert('支付成功！');window.opener=null;window.open('', '_self');window.close();</script>";
	        exit;
	    }else{
	    	echo "<script type='text/javascript'>alert('交易失败,数据操作错误!');window.opener=null;window.open('', '_self');window.close();</script>";
        	exit;
	    }
    } elseif($trade_status == "paying") {
        echo "<script type='text/javascript'>alert('交易中!');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    } elseif($trade_status == "failed") {
        echo "<script type='text/javascript'>alert('交易失败!');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    }
} else {
    echo "<script type='text/javascript'>alert('支付平台通知,MD5校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
    //echo "MD5校验失败,不合法数据！";  //MD5校验失败
    exit;
}


