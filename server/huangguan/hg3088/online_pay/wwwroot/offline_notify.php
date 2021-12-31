<?php
/* *
 *功能：得宝个人网银支付异步通知接口
 *版本：3.0
 *日期：2017-06-30
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 *并非一定要使用该代码。该代码仅供学习和研究得宝接口使用，仅为提供一个参考。
 **/

//////////////////////////	接收得宝返回通知数据  /////////////////////////////////
/**
获取订单支付成功之后，得宝通知服务器以post方式返回来的订单通知数据，参数详情请看接口文档,
 */
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST[$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/offline_notify.php.log');

include_once "../class/config.inc.php";
include_once "../model/Pay.php";
include_once "../model/Userlock.php";

$MemberID = $merchantCode = $_REQUEST["merchant_code"]; //商户号
if($MemberID == '200004004012') { // hgw777
    include_once ("./db/hgw777_merchant.php"); // merchant_private_key，商户私钥;merchant_public_key,商户公钥;dinpay_public_key，得宝公钥
} elseif($MemberID == '200004004007') { // hg98985
    include_once ("./db/hg98985_merchant.php"); // merchant_private_key，商户私钥;merchant_public_key,商户公钥;dinpay_public_key，得宝公钥
}
$interface_version = $_REQUEST["interface_version"];  //接口版本  V3.0(大写)
$sign_type = $_REQUEST["sign_type"];   //签名方式  1.取值为：RSA或RSA-S
$dinpaySign = base64_decode($_REQUEST["sign"]);    //得宝返回签名数据  该参数用于验签

// 业务参数
$notify_type = $_REQUEST["notify_type"];    //通知方式 固定值 offline_notify
$notify_id = $_REQUEST["notify_id"];        // 通知校验ID  此版本不需要校验，但是参数依然保留
$order_no = $_REQUEST["order_no"];          //商家订单号  由字母、数字、下划线组成
$order_time = $_REQUEST["order_time"];      //商家订单时间
$orderAmount = $order_amount = $_REQUEST["order_amount"];  //商家订单金额  以元为单位，精确到小数点后两位.例如：12.01
$trade_status = $_REQUEST["trade_status"];  //订单状态  取值为“SUCCESS”，代表订单交易成功
$trade_time = $_REQUEST["trade_time"];       //得宝订单时间
$tradeNo = $trade_no = $_REQUEST["trade_no"];           //得宝支付平台订单号
//$tradeNo = $trade_no = $order_no;           //得宝支付平台订单号测试用
$bank_seq_no = $_REQUEST["bank_seq_no"];      //银行交易流水号
$extra_return_param = $_REQUEST["extra_return_param"];  //回传参数 , 商户如果支付请求是传递了该参数，则通知商户支付成功时会回传该参数

//回传参数包含会员表Oid , 网银配置表id  , 1。
// 返回数组   hg308877|13|userid|57 | 2  回传参数  会员名称|渠道id|用户id|支付方式银行代码| 支付类型2为银行卡支付，4微信支付，5为支付宝,6为QQ扫码
$aData = explode('|',$extra_return_param);

// 当前支付渠道
$sSql = "SELECT *  FROM `".DBPREFIX."gxfcy_pay` WHERE `id` =".$aData[1]." AND `status` = 1 limit 1";

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

/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */
$signStr = "";
if($bank_seq_no != ""){
    $signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
}
if($extra_return_param != ""){
    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
}
$signStr = $signStr."interface_version=".$interface_version."&";
$signStr = $signStr."merchant_code=".$MemberID."&";
$signStr = $signStr."notify_id=".$notify_id."&";
$signStr = $signStr."notify_type=".$notify_type."&";
$signStr = $signStr."order_amount=".$order_amount."&";
$signStr = $signStr."order_no=".$order_no."&";
$signStr = $signStr."order_time=".$order_time."&";
$signStr = $signStr."trade_no=".$trade_no."&";
$signStr = $signStr."trade_status=".$trade_status."&";
$signStr = $signStr."trade_time=".$trade_time;

/////////////////////////////   RSA-S验证  /////////////////////////////////
$dinpay_public_key = openssl_get_publickey($dinpay_public_key);
$flag = openssl_verify($signStr,$dinpaySign,$dinpay_public_key,OPENSSL_ALGO_MD5);

///////////////////////////   响应“SUCCESS” /////////////////////////////

if ($flag) {  //SUCCESS
    $oUserLock = new Userlock_model($dbMasterLink);
    $userInfo=$oUserLock->lock($row['ID']);
    $userInfoArr = json_decode($userInfo,true);
    if( is_array($userInfoArr) && count($userInfoArr)>0){
    	$oPayin = new Pay_model($dbMasterLink);
	    //校验通过开始处理订单,$row当前会员信息, $aData回传参数 (会员名称|渠道id|用户id|支付方式代码),$MemberID商户号,$tradeNo支付平台订单号
	    $result = $oPayin->UserPayin($userInfoArr, $aData, $MemberID, $tradeNo, $orderAmount, $aThirdPayRow);
	    $oUserLock->commit_lock();
	    echo "<script type='text/javascript'>alert('支付成功！');window.opener=null;window.open('', '_self');window.close();</script>";
    	exit;
    }else{
    	echo "<script type='text/javascript'>alert('交易失败,数据操作错误!');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    }
} else {
    echo "<script type='text/javascript'>alert('得宝异步通知验证数据错误!');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}


