<?php

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST[$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/jbf_notify_url.php.log');

include_once "../class/config.inc.php";
include_once "../model/Pay.php";
include_once "../model/Userlock.php";


if( $arr['orderStatus'] == 'Success' ){

    $extraReturnParam = $_REQUEST["extraReturnParam"];  //回传参数 , 商户如果支付请求是传递了该参数，则通知商户支付成功时会回传该参数
    $aData = explode('|',$extraReturnParam);

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

    $arr=$_REQUEST;
    //$arr=   array (
    //    'merchantCode' => 'M000TEST',
    //    'orderNo' => 'PcWx201706041124018280639',
    //    'amount' => '1',
    //    'successAmt' => '1',
    //    'payOrderNo' => 'WP20170604112401641242',
    //    'orderStatus' => '01',
    //    'signType' => 'RSA',
    //    'extraReturnParam' => 'aaa',
    //    'sign' => 'dU2eH1LDx8bk7BeHRy6mNIcyKAl9qYmybWEYkWPigPDlQMv+8ttNZGGaF37Bj90BABHjBfwp95XUYaSJn3O2jpLomkW8Llj14AUc32l3kkEqaW39fC88eufibnsfV6YjV6vWjbCqguJ9OTjwB7S23WgjwTbLweo/lDTqAN5y+KM=',
    //);
    //echo $arr."<br>";
    $public_content='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0sJMGLD0UQUYObjsMHBGUYQEV
EOCkBCNzzkYWSM0RYToK49hLpmxpNLbNcSMSUwOs6AfzDW9Tbpcotjg4JiphZqrB
jG4Vj2acQPxBp06oJBYdvoCM42AFFLthHNDTmP+O7OYPrwiTTSYPlIUO8HyojhfQ
6Dc9guiit7L98FWhmQIDAQAB
-----END PUBLIC KEY-----';
    $public_key=openssl_get_publickey($public_content);
    $sign=base64_decode($arr['sign']);
    //echo $sign."<br>";
    $original_str="merchantCode=".$arr['merchantCode']."&orderNo=".$arr['orderNo']."&amount=".$arr['amount']."&successAmt=".$arr['successAmt']."&payOrderNo=".$arr['payOrderNo']."&orderStatus=".$arr['orderStatus']."&extraReturnParam=".$arr['extraReturnParam'];//Obtained data
    //echo $original_str."<br>";
    $result= openssl_verify($original_str,$sign,$public_key,OPENSSL_ALGO_SHA1);
    //echo $result."<br>";
    if($result){
        $oUserLock = new Userlock_model($dbMasterLink);
        $userInfo=$oUserLock->lock($row['ID']);
        $userInfoArr = json_decode($userInfo,true);
        if( is_array($userInfoArr) && count($userInfoArr)>0){
            $oPayin = new Pay_model($dbMasterLink);
            //校验通过开始处理订单,$row当前会员信息, $aData回传参数 (会员名称|渠道id|用户id|支付方式代码),$MemberID商户号,$tradeNo支付平台订单号
            $result = $oPayin->UserPayin($userInfoArr, $aData, $MemberID, $tradeNo, $orderAmount, $aThirdPayRow);
            $oUserLock->commit_lock();

            if ($result='ok'){
                $oUserLock->commit_lock();
                echo "SUCCESS";
            }else{
                @error_log(date('Y-m-d H:i:s').'-上分失败'.PHP_EOL, 3, '/tmp/jbf_notify_url.php.log');
                @error_log($result.PHP_EOL, 3, '/tmp/jbf_notify_url.php.log');
            }
        }else{
            echo "<script type='text/javascript'>alert('交易失败,数据操作错误!');window.opener=null;window.open('', '_self');window.close();</script>";
            exit;
        }
    }else{
        @error_log(date('Y-m-d H:i:s').'-校验失败'.PHP_EOL, 3, '/tmp/jbf_notify_url.php.log');
        @error_log(json_encode($arr).PHP_EOL, 3, '/tmp/jbf_notify_url.php.log');
    }

}else{
    echo '支付失败';
}

