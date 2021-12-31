<?php
/**
 * 回调处理
 */
//error_reporting(1);
//ini_set('display_errors','On');

$notifyData = file_get_contents('php://input');
if(!$notifyData){
    exit('fail. no data');
}
//@error_log($notifyData.PHP_EOL, 3, '/tmp/clzldz_notify_url.php.log');
$data=json_decode($notifyData,true);

/*header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST[$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
$data['merchant_no'] = $_REQUEST["merchant_no"];
$data['order_no'] = $_REQUEST["order_no"];
$data['system_no'] = $_REQUEST["system_no"];
$data['price'] = $_REQUEST["price"];
$data['status'] = $_REQUEST["status"];
$data['note'] = $_REQUEST["note"];
$data['sign'] = $_REQUEST["sign"];*/

//@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/clzldz_notify_url.php.log');

/*
1.	merchant_no	商户号	string(50)	商户号 如：1434743144234
2.	order_no	商户订单号	string(50)	商户订单号
3.	system_no	系统订单号	string(50)	系统订单号
4.	price	订单金额	float	订单金额 单位（元）
5.	status	订单状态	string(50)	订单状态 成功返回 SUCCESS 失败返回 ERROR
6.	errmsg	错误信息	string(255)	错误返回信息 当status返回ERROR时
7.	note	自定义数据	string(50)	提交订单时商户提交的自定义数据
8.	sign	签名	string(32)	将加密参数按顺序连Token一起用 + 号拼接，做md5-32位加密，取字符串小写。您需要在您的服务端按照同样的算法，自己验证此sign是否正确。只在正确时，执行您自己逻辑中支付成功代码。
（拼接顺序：merchant_no .'+'. order_no .'+'. system_no .'+'. price .'+'. status .'+'. token）
 * */

include_once "../class/config.inc.php";
include_once "../model/Pay.php";
include_once "../model/Userlock.php";


// 会员名称|渠道id|用户Oid|支付方式代码
$aData = explode('|',$data['note']);

// 当前支付渠道
$sSql = "SELECT *  FROM `".DBPREFIX."gxfcy_pay` WHERE `id` =".$aData[1]." AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$aThirdPayRow = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到相关数据！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

// 当前登录账号
$sql = "select ID from ".DBPREFIX.MEMBERTABLE." where ID='$aData[2]' and Status<2";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    echo "<script type='text/javascript'>alert('该会员账户不存在！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}


// 校验参数
// 拼接参数本地生成sign
$token = $aThirdPayRow['business_pwd'];
$sign_str =$data['merchant_no'].'+'.$data['order_no'].'+'.$data['system_no'].'+'.$data['price'].'+'.$data['status'].'+'.$token;
$localSign=md5($sign_str);

if($localSign === $data['sign']){
    $oUserLock = new Userlock_model($dbMasterLink);
    $userInfo=$oUserLock->lock($row['ID']);
    $userInfoArr = json_decode($userInfo,true);
    if( is_array($userInfoArr) && count($userInfoArr)>0){
        $oPayin = new Pay_model($dbMasterLink);
        //校验通过开始处理订单,$row当前会员信息, $aData回传参数 (会员名称|渠道id|用户id|支付方式代码),$MemberID商户号,$tradeNo支付平台订单号
        $result = $oPayin->UserPayin($userInfoArr, $aData, $MemberID, $data['order_no'], $data['price'], $aThirdPayRow);

        $sSql = "select ID,userid,UserName,Gold from `".DBPREFIX."web_thirdpay_data` WHERE `Order_Code` = '{$data['order_no']}' AND `Status` = 1";
        $oRes = mysqli_query($dbMasterLink,$sSql);
        $iCou = mysqli_num_rows($oRes);
        if($iCou > 0) {
            mysqli_rollback($dbMasterLink);
            echo '已上分成功，无需重复上分';
        }else {
            $callbackTime = date('Y-m-d H:i:s');
            $mysql="update ".DBPREFIX."web_thirdpay_data set thirdSysOrder='{$data['system_no']}',SysTime='{$callbackTime}',CallbackTime='{$callbackTime}',Status='1' where Order_Code='".$data['order_no']."'";
//            echo $mysql;
            if(mysqli_query($dbMasterLink,$mysql)){
                $oUserLock->commit_lock();
                echo "success";
            }else{
                mysqli_rollback($dbMasterLink);
                exit ('更新订单状态失败');
            }

        }


    }else{

        mysqli_rollback($dbMasterLink);
        echo "<script type='text/javascript'>alert('交易失败,数据操作错误!');window.opener=null;window.open('', '_self');window.close();</script>";
    }
}else{

    echo 'sign校验失败';
}

