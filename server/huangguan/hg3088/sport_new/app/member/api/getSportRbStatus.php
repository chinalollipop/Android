<?php
/**
 * 获取滚球确认状态
 *
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

include('../include/config.inc.php');

$data = array() ;
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '502';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe,$data);
}

$orderNum = trim($_REQUEST['orderNum']);

if(!$orderNum){
    $status='401.2';
    $describe = "没有订单号!";
    original_phone_request_response($status,$describe,$data);
}

$sql = "select Cancel,Danger from ".DBPREFIX."web_report_data where orderNo='$orderNum'";
//echo $sql;
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$data = array(
    'cancel'=>$row['Cancel'], // Cancel -1 取消 ，0 已确认
    'danger'=>$row['Danger']
);
$status='200';
$describe = "获取订单状态成功!";
original_phone_request_response($status,$describe,$data);