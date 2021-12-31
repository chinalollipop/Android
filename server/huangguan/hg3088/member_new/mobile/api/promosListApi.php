<?php
/* 优惠活动接口 */
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");
include_once("../include/config.inc.php");

$resdata = array();
$lists = returnPromosList('',3); // 活动列表
$categoryList = returnPromosType(); // 分类列表

$resdata =[
    'promoList'=>$lists,
    'categoryList'=>$categoryList
];

$status = '200';
$describe = '请求数据成功';
original_phone_request_response($status,$describe,$resdata);



?>