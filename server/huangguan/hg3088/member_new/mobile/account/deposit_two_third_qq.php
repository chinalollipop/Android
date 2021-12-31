<?php
// 第三方QQ存款
// 输入金额，跳转第三方或者添加记录
include_once('../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '401.1';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}

$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$username = $_SESSION['UserName'];
$depositTimes = $_SESSION['DepositTimes']; //会员存款次数
// 第三方支付
$sSql = "SELECT id,thirdpay_code,url,minCurrency,maxCurrency,title FROM `".DBPREFIX."gxfcy_pay` WHERE `account_company` = 6 AND `depositNum` <= $depositTimes AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$oRes = mysqli_query($dbLink,$sSql);
$iCou=mysqli_num_rows($oRes);
if( $iCou==0 ){
    $status = '401.2';
    $describe = '支付方式有误，请重新选择~！';
    original_phone_request_response($status,$describe);
}

$aData = [];
while($aRow = mysqli_fetch_assoc($oRes)){
    $aData[]=$aRow;
}

$aPid = [];
$aUrl = [];
$aMinCurrency = [];
$aMaxCurrency = [];
foreach ($aData as $k => $v){
    $aPid[$k] = $v['id'];
    $aMinCurrency[$k] = bcmul(floatval($v['minCurrency']) , 1);
    $aMaxCurrency[$k] = bcmul(floatval($v['maxCurrency']) , 1);
    if ( $v['thirdpay_code'] == 'sf' ){ // 闪付QQ
        $aUrl[$k] =  $v['url'].'/sfpay.php';
    }
    if ( $v['thirdpay_code'] == 'rx' ){  // 仁信QQ
        $aUrl[$k] =  $v['url'].'/rxpay.php';
    }
    if ( $v['thirdpay_code'] == 'zb' ){ // 众宝
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/zhongbaopay.php';
    }
    if ( $v['thirdpay_code'] == 'wdf' ){ // 维多付
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/weiduofupay.php';
    }
    if ( $v['thirdpay_code'] == 'csj' ){ // 创世纪
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/csjpay.php';
    }
}

$status = '200';
$describe = 'success';

foreach ($aData as $k =>$v){
    $aData[$k]['userid']=$_SESSION['userid'];
    $aData[$k]['url'] = $aUrl[$k];
}

original_phone_request_response($status,$describe,$aData);

?>
