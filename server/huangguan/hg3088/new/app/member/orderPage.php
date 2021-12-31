<?php
// 此文件生成订单页，用于截图
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
require ("../include/traditional.zh-cn.inc.php");

?>
<html>
<head>
<meta http-equiv='Content-Type' content="text/html; charset=utf-8">
<script charset="utf-8" type="text/javascript" src="../../../js/jquery.js" ></script>
<script language=javascript></script>
<link rel="stylesheet" href="/style/member/mem_order<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<body id="OFIN" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
  <div class="ord">

