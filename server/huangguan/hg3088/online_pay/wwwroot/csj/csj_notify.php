<?php
/**
 * 创世纪上分（支付类型 '0'=>'支付宝转卡','1'=>'微信扫码','2'=>'银联扫码','3'=>'网银支付','4'=>'微信转账','5'=>'支付宝转账','6'=>'手机银行转账','7'=>'银联快捷','8'=>'支付宝个码','9'=>'支付宝wap2/支付宝H5',）
 */

//ini_set("display_errors","On");
//error_reporting(E_ALL);

header( 'Content-Type: text/html; charset=utf-8');
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST[$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(date("Y-m-d H:i:s") .'-'. serialize($_REQUEST).PHP_EOL, 3, '/tmp/csj_notify.log');


// 回调地址示例   创世纪回调系统单号 thirdSysOrder为空
//http://pay.hg01455.com/csj/csj_notify.php?amount=300.00&attach=hg0086|106|113018|0&merchno=a6e7255dd1&orderId=csjzfb-20200915234228106_6709761&payType=0&sign=f8d7cd37ac1f183e312668d1d7e09516&status=2
/*
 * $_REQUEST = array(9) {
  ["merchno"]=>"a6e7255dd1"
  ["orderId"]=>"csjzfb-20200915234228106_6709761"
  ["amount"]=>"300.00"
  ["payType"]=>"0"
  ["attach"]=>"hg0086|106|113018|0"
  ["status"]=>"2"
  ["sign"]=>"f8d7cd37ac1f183e312668d1d7e09516"
}*/

include_once "../../class/config.inc.php";
include_once "../../model/Userlock.php";
include_once "../../model/Pay.php";

$params['merchno'] = $merchno = urldecode($_REQUEST["merchno"]);		//商户 ID 是 唯一号
$params['orderId'] = $orderId = $ordernumber = urldecode($_REQUEST["orderId"]);		//商户订单号
$params['amount'] = $paymoney = urldecode($_REQUEST["amount"]);		//订单金额
$params['payType'] = $payType = urldecode($_REQUEST["payType"]);		//支付类型
$params['attach'] = $attach = $_REQUEST['attach'];  // john103|104|51013|2  会员名称|渠道id|用户uid|支付方式代码
$params['status'] = $status = urldecode($_REQUEST["status"]);	//订单状态
$sign = urldecode($_REQUEST["sign"]);	//MD5签名


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

$sign_str = create_sign($params);   //获取签名字符串

// amount=300.00&attach=hg0086|106|113018|0&merchno=a6e7255dd1&orderId=csjzfb-20200915234228106_6709761&payType=0&status=2&secretKey=779858c4f4294556a818dba814296cdf
$string = $sign_str . 'secretKey=' . $Md5key;
//签名
//echo 'md5:' . strtolower(md5($string)) .'--sign:'.$sign. '<br>';
//exit;


function create_sign($array){
    ksort($array); #排列数组 将数组已a-z排序
    $result = '';
    foreach($array as $key=>$v){
        if ($key !== 'syncUrl' && $key !== 'sign'){
            $v = trim($v);
            if($v != ''){
                $result  .= $key  . '=' . $v . '&';
            }
            //echo 'result:' .  $result;echo '<br>';
        }
    }
    return $result;
}

if ($sign == strtolower(md5($string)) ) {
    $oUserLock = new Userlock_model($dbMasterLink);
    $userInfo=$oUserLock->lock($row['ID']);
    $userInfoArr = json_decode($userInfo,true);
    if( is_array($userInfoArr) && count($userInfoArr)>0) {
        $oPayin = new Pay_model($dbMasterLink);
        // 用户个人信息，回传参数，商户号，订单号，金额，第三方简称（ csj 创世纪）
        $result = $oPayin->userpayin($userInfoArr, $aData, $MemberID, $ordernumber, $paymoney, $aRow);

        $sSql = "select ID,userid,UserName,Gold from `".DBPREFIX."web_thirdpay_data` WHERE `Order_Code` = '{$ordernumber}' AND `Status` = 1";
        $oRes = mysqli_query($dbMasterLink,$sSql);
        $iCou = mysqli_num_rows($oRes);

        if($iCou > 0) {
            mysqli_rollback($dbMasterLink);
            echo '已上分成功，无需重复上分';
        }else {
            $callbackTime = date('Y-m-d H:i:s');
            $mysql="update ".DBPREFIX."web_thirdpay_data set thirdSysOrder='',SysTime='{$callbackTime}',CallbackTime='{$callbackTime}',Status='1' where Order_Code='".$ordernumber."'";
//            echo $mysql;
            if(mysqli_query($dbMasterLink,$mysql)){
                $oUserLock->commit_lock();
                exit(preg_replace('/\s+/', '', 'success'));
            }else{
                mysqli_rollback($dbMasterLink);
                exit('更新订单状态失败');
            }
        }

    }else{
        exit("上分失败,数据操作错误");
    }
} else {
    exit("Md5CheckFail'");//MD5校验失败
}
