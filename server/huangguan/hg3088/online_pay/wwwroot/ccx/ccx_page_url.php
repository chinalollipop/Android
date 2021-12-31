<?php

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/ccx_page_url.php.log');

include_once "../../class/config.inc.php";

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

//MD5签名格式
if ($localSign == $data['sign']) {
    if ($data['status'] == "2") {
        echo "<script type='text/javascript'>alert('支付成功！');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    } elseif($data['status'] == "0") {
        echo "<script type='text/javascript'>alert('交易中!');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    } elseif($data['status'] == "3") {
        echo "<script type='text/javascript'>alert('交易失败!');window.opener=null;window.open('', '_self');window.close();</script>";
        exit;
    }
} else {
    echo "<script type='text/javascript'>alert('MD5校验失败！');window.opener=null;window.open('', '_self');window.close();</script>";
}
