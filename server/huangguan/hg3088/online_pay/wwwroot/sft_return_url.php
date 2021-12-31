<?php

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/sft_return.log');

include "../class/config.inc.php";
include "../model/Userlock.php";
include "../model/Pay.php";
include "./sft/Pay.class.php";
include "./sft/pay.config.php";


// 请求数据赋值
$data = "";
$data['service'] = $_REQUEST["service"]; //接口名字  固定值TRADE.NOTIFY
// 商户号
$MemberID = $data['merId'] = $_REQUEST["merId"];
// 商户订单号
$data['tradeNo'] = $_REQUEST["tradeNo"];
// 商户交易日期
$data['tradeDate'] = $_REQUEST["tradeDate"];
// 支付平台订单号     商户参数，支付平台返回商户上传的参数，可以为空
$tradeNo = $data['opeNo'] = $_REQUEST["opeNo"];
// 支付平台订单日期
$data['opeDate'] = $_REQUEST["opeDate"];
// 支付金额(单位元，显示用)
$orderAmount = $data['amount'] = $_REQUEST["amount"];
// 订单状态   0-未支付，1-支付成功，2-失败，4-部分退款，5-退款，9-退款处理中
$data['status'] = $_REQUEST["status"];
// 商户参数  支付时上送的商户参数  支付账务日期
$data['extra'] = $_REQUEST["extra"];
// 支付时间
$data['payTime'] = $_REQUEST["payTime"];
// 签名数据
$data['sign'] = $_REQUEST["sign"];
// 通知类型   0-前端页面通知   1-后台服务器，商户需返回数据  该字段不参与签名
$data['notifyType'] = $_REQUEST["notifyType"];

//回传参数包含会员表Oid , 网银配置表id  , 1。  返回数组   hg308877|13|0a74e7362d0192023a7bra4|57   会员名称|渠道id|用户Oid|支付方式代码
$aData = explode('|',$_REQUEST["extra"]);


$sql = "select ID from ".DBPREFIX.MEMBERTABLE." where ID='$aData[2]' and Status<2";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    echo "<script type='text/javascript'>alert('该账户不存在！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` ='.$aData[1].' AND `status` = 1 limit 1';
// 第三方支付
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
if(!$iCou ){
    //echo mysqli_connect_error($dbLink); die;
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到相关数据！');</script>";
    exit;
}

$aThirdPayRow = mysqli_fetch_assoc($oRes);

// 初始化
$pPay = new Pay($KEY,$GATEWAY_URL);
// 准备准备验签数据
$str_to_sign = $pPay->prepareSign($data);
// 验证签名
$resultVerify = $pPay->verify($str_to_sign, $data['sign']);

if($resultVerify){
    // 当前会员加锁
    $oUserLock = new Userlock_model($dbMasterLink);
    $userInfo=$oUserLock->lock($row['ID']);
    $userInfoArr = json_decode($userInfo,true);
	if( is_array($userInfoArr) && count($userInfoArr)>0){
    	$oPayin = new Pay_model($dbMasterLink);
	     //校验通过开始处理订单，对当前会员进行上分
    	$oPayin->UserPayin($userInfoArr, $aData, $MemberID, $tradeNo, $orderAmount, $aThirdPayRow);
	    $oUserLock->commit_lock();
//		if($result) {
	        echo "<script type='text/javascript'>alert('支付成功！');window.opener=null;window.open('', '_self');window.close();</script>";
	        //后台服务器通知需要给接口返回数据。商户接收到平台支付订单支付通知后必须回写SUCCESS
	        return 'SUCCESS';
//	    }else{
//	        echo "<script type='text/javascript'>alert('顺付通平台通知,处理订单失败！');window.opener=null;window.open('', '_self');window.close();</script>";
//	    }
	    exit;
	}else{
    	echo "<script type='text/javascript'>alert('交易失败,数据操作错误!');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    }
} else {
    echo "<script type='text/javascript'>alert('顺付通平台通知,验证签名失败！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}