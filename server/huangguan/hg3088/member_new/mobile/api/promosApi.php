<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once "../../../common/promosCommon.php";

$user_id = $_SESSION['userid']?$_SESSION['userid']:$_REQUEST['user_id'];
$username = $_SESSION['UserName']?$_SESSION['UserName']:$_REQUEST['username'];
$type_flag = isset($_REQUEST['type_flag'])?$_REQUEST['type_flag']:''; // 当前活动的 活动标识

promosApiAction($user_id,$username,$type_flag);


?>