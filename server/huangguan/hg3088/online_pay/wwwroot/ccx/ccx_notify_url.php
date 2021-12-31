<?php
/**
 * 回调处理
 */
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/ccx_notify_url.php.log');

include_once "../../class/config.inc.php";
include_once "../../model/Pay.php";
include_once "../../model/Userlock.php";

$data['merchno'] = $_REQUEST['merchno'];
$data['orderId'] = $_REQUEST['orderId'];
$data['payType'] = $_REQUEST['payType'];
$data['amount'] = $_REQUEST['amount'];
$data['attach'] = $_REQUEST['attach'];
$data['status'] = $_REQUEST['status'];
$data['sign'] = $_REQUEST['sign'];


// 会员名称|渠道id|用户Oid|支付方式代码
$aData = explode('|',$data['attach']);

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
$sign_str ='amount='.$data['amount'].'&attach='.$data['attach'].'&merchno='.$data['merchno'].'&orderId='.$data['orderId'].'&payType='.$data['payType'].'&status='.$data['status'].'&secretKey='.$token;
$localSign=md5($sign_str);

if($localSign === $data['sign']){
    $oUserLock = new Userlock_model($dbMasterLink);
    $userInfo=$oUserLock->lock($row['ID']);
    $userInfoArr = json_decode($userInfo,true);
    if( is_array($userInfoArr) && count($userInfoArr)>0){
        $oPayin = new Pay_model($dbMasterLink);
        //校验通过开始处理订单,$row当前会员信息, $aData回传参数 (会员名称|渠道id|用户id|支付方式代码),$MemberID商户号,$tradeNo支付平台订单号
        $result = $oPayin->UserPayin($userInfoArr, $aData, $MemberID, $data['orderId'], $data['amount'], $aThirdPayRow);

        $sSql = "select ID,userid,UserName,Gold from `".DBPREFIX."web_thirdpay_data` WHERE `Order_Code` = '{$data['orderId']}' AND `Status` = 1";
        $oRes = mysqli_query($dbMasterLink,$sSql);
        $iCou = mysqli_num_rows($oRes);
        if($iCou > 0) {
            mysqli_rollback($dbMasterLink);
            echo '已上分成功，无需重复上分';
        }else {
            $callbackTime = date('Y-m-d H:i:s');
            $mysql="update ".DBPREFIX."web_thirdpay_data set SysTime='{$callbackTime}',CallbackTime='{$callbackTime}',Status='1' where Order_Code='".$data['orderId']."'";
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

