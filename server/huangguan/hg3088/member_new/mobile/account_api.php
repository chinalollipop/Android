<?php
include_once('include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {

    $status = '401.1';
    $describe = '你的登录信息已过期，请先登录!';
    original_phone_request_response($status,$describe);

}
$username = $_SESSION['UserName'];
$sql = "SELECT Money FROM `".DBPREFIX.MEMBERTABLE."`  WHERE UserName='$username' AND Status<2 for update ";
$result = mysqli_query($dbMasterLink,$sql);
if ($result){
    $row = mysqli_fetch_assoc($result);
}else{
    $status = '500.1';
    $describe = '查询中心钱包操作失败';
    original_phone_request_response($status,$describe);
}

//    $floatNum = _getFloatLength($row['Money']);
//    if ($floatNum == 3){
//        $row['Money'] = substr($row['Money'],0,-1);
//    }elseif ($floatNum == 4){
//        $row['Money'] = substr($row['Money'],0,-2);
//    }

$hgId=$_SESSION['userid'];
$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
if ($cpMasterDbLink){
    $sql = "select lcurrency from ".$database['cpDefault']['prefix']."user where hguid=".$hgId;
    $result = mysqli_query($cpMasterDbLink,$sql);
    if ($result){
        $cprow = mysqli_fetch_assoc($result);
        $cpcou = mysqli_num_rows($result);
        if($cpcou==0){
            $cpFund = '0.00';
        }else{
            $cpFund = $cprow['lcurrency']; // 彩票余额
        }
    }else{

        $status = '500.3';
        $describe = '查询彩票余额操作失败';
        original_phone_request_response($status,$describe);

    }

}else{

    $status = '500.2';
    $describe = '链接彩票库操作失败';
    original_phone_request_response($status,$describe);

}
// 会员注册天数
$AddDate = date("Y-m-d",strtotime($_SESSION['AddDate']));
$dateNow = date('Y-m-d');
$interval = (strtotime($dateNow)-strtotime($AddDate))/86400+1;

$status = '200';
$describe = 'success';
$aData=array(
    'balance_hg'=>formatMoney($row['Money']),
    'balance_cp'=>floor($cpFund),
    'joinDays'=>$interval,
);
original_phone_request_response($status,$describe,$aData);