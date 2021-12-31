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

<span><h1>足球早餐单式让球交易单</h1></span><div id="info">
                    <div class="fin_title">
                      <p class="fin_acc">成功提交注单！</p>
                      <p class="p-underline">交易成功单号：RXE806187299652100981</p>
                      <p class="error">危险球 - 待确认</p>
                    </div><p class="team">
                    世界杯2018(在俄罗斯)&nbsp;&nbsp;
                    06-19<BR>
                    哥伦比亚&nbsp;&nbsp;<font color=#cc0000>VS.</font>  日本<br><em>日本</em>&nbsp;@&nbsp;<em><strong>0.72</strong></em></p>         <p class="deal-money">交易金额：20</p></div><p class="foot">
                  <input type="button" name="FINISH" value=" 关闭 " onClick="parent.close_bet();" class="no">
                  <input type="button" name="PRINT" value=" 列印 " onClick="window.print()" class="yes">
                </p>
              </div>
            </body>
            </html>