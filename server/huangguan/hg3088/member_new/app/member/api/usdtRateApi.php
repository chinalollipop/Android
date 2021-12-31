<?php
/*
 * USDT汇率接口
 * */

include('../include/config.inc.php');

$action = $_REQUEST['action'];
if($action == 'getUsdtAddress') { // 获取会员的USDT地址、以及提款汇率

    $uid = $_SESSION['Oid']?$_SESSION['Oid']:$_REQUEST['uid'];
    if(!isset($uid) || $uid == ''){
        $status = '401.1';
        $describe = '您的登录信息已过期，请您重新登录！';
        original_phone_request_response($status,$describe);
    }
    $sql = "select ID,UserName,Money,Bank_Name,Bank_Account,Usdt_Address from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
    $result = mysqli_query($dbLink,$sql);
    $mrow=mysqli_fetch_assoc($result);
    $aData['Bank_Account']=returnBankAccount($mrow['Bank_Account']);
    $aData['Usdt_Address']=returnBankAccount($mrow['Usdt_Address']);

    $rate = returnUsdtRate();
    $aData['withdrawals_usdt_rate'] = $rate['withdrawals_usdt_rate'];
    $aData['jiaoyisuo'] = getSysConfig('usdt_jiaoyisuo');
    original_phone_request_response('200','USDT地址',$aData);
}
else{
    $rate = returnUsdtRate();
    original_phone_request_response('200', 'USDT存款汇率，USDT取款汇率', $rate);
}
