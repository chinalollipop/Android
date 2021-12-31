<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');

$jsondata=file_get_contents("php://input");

/**
 * 回调处理
 */
header ( 'Content-Type: text/html; charset=utf-8' );

@error_log($jsondata.PHP_EOL, 3, '/tmp/xingchen_notify_url.php.log');

$_REQUEST = json_decode($jsondata,true);

include_once "../../class/config.inc.php";
include_once "../../model/Pay.php";
include_once "../../model/Userlock.php";

$data['mch_id'] = $_REQUEST['mch_id'];
$data['order_id'] = $_REQUEST['order_id'];
$data['amount'] = $_REQUEST['amount'];
$data['bank_mark'] = $_REQUEST['bank_mark'];
$data['trade_no'] = $_REQUEST['trade_no'];
$data['pay_status'] = $_REQUEST['pay_status'];
$data['pay_time'] = $_REQUEST['pay_time'];
$data['time_stamp'] = $_REQUEST['time_stamp'];
$data['sign'] = $_REQUEST['sign'];

$redisObj = new Ciredis();


// 当前支付渠道
$sSql = "SELECT *  FROM `".DBPREFIX."gxfcy_pay` WHERE `business_code` =".$data['mch_id']." limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$aThirdPayRow = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到相关数据！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

$sql = "select userid from `".DBPREFIX."web_thirdpay_data` where Order_Code='".$data['order_id']."'";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    echo "<script type='text/javascript'>alert('该会员账户不存在！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

$MemberID  = $aThirdPayRow['business_code'];   //商户号
// 校验参数
// 拼接参数本地生成sign
$token = $aThirdPayRow['business_pwd'];

unset($_REQUEST['sign']);
ksort($_REQUEST);

$sign_str='';
foreach ($_REQUEST as $k => $v){
    if (strlen($v)>0){
        $sign_str.=$k.'='.$v.'&';
    }
}

$sign_str .='key='.$token;
//print_r($sign_str); die;
$localSign=strtoupper(md5($sign_str));


if($localSign === $data['sign']){
//if(1){
    if ($data['pay_status']==1) {

        $oUserLock = new Userlock_model($dbMasterLink);
        $userInfo = $oUserLock->lock($row['userid']);
        $userInfoArr = json_decode($userInfo, true);

        if (is_array($userInfoArr) && count($userInfoArr) > 0) {
            $oPayin = new Pay_model($dbMasterLink);
            $aData = array('', '', '', $data['bank_mark']);
            //校验通过开始处理订单,$row当前会员信息, $aData回传参数 (会员名称|渠道id|用户id|支付方式代码),$MemberID商户号,$tradeNo支付平台订单号
            $result = $oPayin->UserPayin($userInfoArr, $aData, $MemberID, $data['order_id'], $data['amount'], $aThirdPayRow);

            $sSql = "select ID,userid,UserName,Gold from `" . DBPREFIX . "web_thirdpay_data` WHERE `Order_Code` = '{$data['order_id']}' AND `Status` = 1";
            $oRes = mysqli_query($dbMasterLink, $sSql);
            $iCou = mysqli_num_rows($oRes);
            if ($iCou > 0) {
                mysqli_rollback($dbMasterLink);
                echo '已上分成功，无需重复上分';
            } else {
                $callbackTime = date('Y-m-d H:i:s');
                $mysql = "update " . DBPREFIX . "web_thirdpay_data set SysTime='{$callbackTime}',CallbackTime='{$callbackTime}',Status='1' where Order_Code='" . $data['order_id'] . "'";
//            echo $mysql;
                if (mysqli_query($dbMasterLink, $mysql)) {
                    $oUserLock->commit_lock();
                    echo "SUCCESS";
                    die;
                } else {
                    mysqli_rollback($dbMasterLink);
                    exit ('更新订单状态失败');
                }

            }
        } else {

            mysqli_rollback($dbMasterLink);
            echo "<script type='text/javascript'>alert('交易失败,数据操作错误!');window.opener=null;window.open('', '_self');window.close();</script>";
        }
    }else{
        exit('暂未支付或者超时支付失败');
    }
}else{
    echo 'sign校验失败';
}

