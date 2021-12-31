<?php
/*
会员短信
*/
session_start();
include_once('../include/config.inc.php');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '502';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}
$type = isset($_REQUEST['type'])?$_REQUEST['type']:0; // 0 系统短信，1 存款短信，2 取款短信
$username = $_SESSION['UserName']?$_SESSION['UserName']:$_REQUEST['username'];
$resdata = array();

$resdata = getMemberMessage($username,$type);

$status = '200';
$describe = '获取短信成功!';
original_phone_request_response($status,$describe,$resdata);

?>