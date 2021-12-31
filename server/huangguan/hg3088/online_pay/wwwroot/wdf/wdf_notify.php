<?php
/**
 * 维多付上分（阿里网关:w_alibank 云闪付:w_union  支转银:w_alipay 支转支:w_alipayqr 微信扫码:w_wechat 微信转卡:w_wechath5）
 */

//ini_set("display_errors","Off");
//error_reporting(E_ALL);

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date("Y-m-d H:i:s") .'-'. serialize($_REQUEST).PHP_EOL, 3, '/tmp/wdf_notify.log');


// 回调地址示例
//http://pay.hg01455.com/wdf/wdf_notify.php?fxid=888185&fxddh=wdfzfb2020090807492010251974286&fxdesc=weiduofu&fxorder=&fxfee=300.00&fxattch=hg0086|102|113018|w_alipayh5&fxtime=1599565854&fxstatus=1&fxsign=01c473dd3c897bf30b6a9297b59742bc
//http://pay.hg01455.com/wdf/wdf_notify.php?fxid=888185&fxddh=wdf2020090811335810114572536&fxorder=04b633adab3ed999&fxdesc=weiduofu&fxfee=300.00&fxattch=hg0086|101|113018|w_union&fxstatus=1&fxtime=1599579383&fxsign=16e55eb100422eb08c472189e8870a39
/*
 * $_REQUEST = array(9) {
    ["fxid"]=> "888185",
    ["fxddh"]=>"wdfzfb2020090807492010251974286",
    ["fxdesc"]=>"weiduofu",
    ["fxorder"]=>"",
    ["fxfee"]=>"300.00",
    ["fxattch"]=>"hg0086|102|113018|w_alipayh5",
    ["fxtime"]=>"1599565854",
    ["fxstatus"]=>"1",
    ["fxsign"]=>"01c473dd3c897bf30b6a9297b59742bc",
}*/

$fxattch = isset($_REQUEST['fxattch']) ? $_REQUEST['fxattch'] : '';

unset($_GET['fxattch']);  //w_union 特殊字符
unset($_POST['fxattch']);  //w_union 特殊字符
unset($_REQUEST['fxattch']);  //w_union 特殊字符

//echo '<pre>';
//print_r($_GET);
//print_r($_POST);
//print_r($_REQUEST);exit;

include_once "../../class/config.inc.php";
include_once "../../model/Userlock.php";
include_once "../../model/Pay.php";

$_REQUEST['fxattch'] = $fxattch;

$fxid = urldecode($_REQUEST["fxid"]);		//商户 ID 是 唯一号
$fxddh = $ordernumber = urldecode($_REQUEST["fxddh"]);		//商户订单号
$fxdesc = urldecode($_REQUEST["fxdesc"]);		//商品名称
$fxorder = urldecode($_REQUEST["fxorder"]);		//平台订单号
$fxfee = $paymoney = urldecode($_REQUEST["fxfee"]);		//提交金额 单位元
$fxtime= urldecode($_REQUEST["fxtime"]);		//支付时间
$fxstatus = urldecode($_REQUEST["fxstatus"]);	//订单状态
$Sign = urldecode($_REQUEST["fxsign"]);	//MD5签名

$attach = $_REQUEST['fxattch'];  // hg0086|102|113018|w_alipayh5  会员名称|渠道id|用户uid|支付方式代码
$aData = explode('|',$attach); //备注信息

// 三方更新数据
$updThirdData = [
    'Order_Code' => $ordernumber,
    'thirdSysOrder' => $fxorder,
    'SysTime' => date('Y-m-d H:i:s',strtotime($fxtime)-12*60*60),
    'CallbackTime' => date("Y-m-d H:i:s"),
    'Status' => $fxstatus,
];

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

//签名【md5(订单状态+商务号+商户订单号+支付金额+商户秘钥)】
$sign_str = strval($fxstatus) . strval($MemberID) . strval($fxddh) . strval($fxfee) . strval($Md5key);

if ($Sign == md5($sign_str)) {
    $oUserLock = new Userlock_model($dbMasterLink);
    $userInfo=$oUserLock->lock($row['ID']);
    $userInfoArr = json_decode($userInfo,true);
    if( is_array($userInfoArr) && count($userInfoArr)>0) {
    	$oPayin = new Pay_model($dbMasterLink);

        // 用户个人信息，回传参数，商户号，订单号，金额，第三方简称（wdf 维多付）
        $result = $oPayin->userpayin($userInfoArr, $aData, $MemberID, $ordernumber, $paymoney, $aRow);

        $sSql = "select ID,userid,UserName,Gold from `".DBPREFIX."web_thirdpay_data` WHERE `Order_Code` = '{$ordernumber}' AND `Status` = 1";
        $oRes = mysqli_query($dbMasterLink,$sSql);
        $iCou = mysqli_num_rows($oRes);

        if($iCou > 0) {
            mysqli_rollback($dbMasterLink);
            echo '已上分成功，无需重复上分';
        }else {
            $callbackTime = date('Y-m-d H:i:s');
            $mysql="update ".DBPREFIX."web_thirdpay_data set thirdSysOrder='{$fxorder}',SysTime='{$callbackTime}',CallbackTime='{$callbackTime}',Status='1' where Order_Code='".$ordernumber."'";
//            echo $mysql;
            if(mysqli_query($dbMasterLink,$mysql)){
                $oUserLock->commit_lock();
                //exit("success");
                exit(preg_replace('/\s+/', '', 'success'));
            }else{
                mysqli_rollback($dbMasterLink);
                exit ('更新订单状态失败');
            }
        }

    }else{
        exit("上分失败,数据操作错误");
    }
} else {
    exit("Md5CheckFail'");//MD5校验失败
}
