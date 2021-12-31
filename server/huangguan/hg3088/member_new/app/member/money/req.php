<?php

/*
 * @Description 易宝支付产品通用支付接口范例 
 * @V3.0
 * @Author rui.xin
 */

include 'yeepayCommon.php';	
	//echo "$business";exit;	
#	商家设置用户购买商品的支付信息.
##易宝支付平台统一使用GBK/GB2312编码方式,参数如用到中文，请注意转码

#	商户订单号,选填.
##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，易宝支付会自动生成随机的商户订单号.
$p2_Order					= $_REQUEST['p2_Order'];
$p2_Order					= date("YmdHis",time()).rand(1000,9999);

#	支付金额,必填.
##单位:元，精确到分.
$p3_Amt						= $_REQUEST['p3_Amt'];

#	交易币种,固定值"CNY".
$p4_Cur						= "CNY";

#	商品名称
##用于支付时显示在易宝支付网关左侧的订单产品信息.
$p5_Pid						= $_REQUEST['p5_Pid'];

#	商品种类
$p6_Pcat					= $_REQUEST['p6_Pcat'];

#	商品描述
$p7_Pdesc					= $_REQUEST['p7_Pdesc'];

#	商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
$p8_Url						= $_REQUEST['p8_Url'];	

#	商户扩展信息
##商户可以任意填写1K 的字符串,支付成功时将原样返回.												
$pa_MP						= $_REQUEST['pa_MP'];

#	支付通道编码
##默认为""，到易宝支付网关.若不需显示易宝支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.			
$pd_FrpId					= $_REQUEST['pd_FrpId'];

#	应答机制
##为"1": 需要应答机制;为"0": 不需要应答机制.
$pr_NeedResponse	= $_REQUEST['pr_NeedResponse'];

#调用签名函数生成签名串
$hmac = getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);
     
?> 
<html>
<head>
<SCRIPT language=JavaScript>
<!--

var boodschap = '';
function dgstatus()
{
  window.status = boodschap;
 timerID= setTimeout("dgstatus()", 0);
}
dgstatus();
//-->
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
<!-- Hide
function killErrors() {
return true;
}
window.onerror = killErrors;
// -->
</SCRIPT>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META content="MSHTML 6.00.2800.1226" name=GENERATOR>
<style type="text/css">
<!--
.STYLE31 {font-size: 12px}
body {
	margin-left: 4px;
	margin-top: 4px;
	background-color: #FFFFFF; color:#FFF;
	
}
-->
</style>
<style type="text/css">
<!--
.STYLE40 {color: #666666}
.STYLE43 {font-family: "Times New Roman", Times, serif}
.style44 {color: #FF0000}
-->
</style>
<title>网页支付平台</title></head>

<SCRIPT language=JavaScript type=text/JavaScript>
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
function click() {
if (event.button==2) {
alert('请点击立即支付！')
} }
document.onmousedown=click;
//-->
</SCRIPT>

<SCRIPT language=JavaScript type=text/JavaScript>
<!--
function disable() 
{ 
document.form1.Submit.disabled=true; 
} 
//-->
</SCRIPT>
<body text="#000000" vlink="#CC0000" alink="#CC0000" ondragstart="window.event.returnValue=false" oncontextmenu="window.event.returnValue=false" onselectstart="event.returnValue=false">

<form name='yeepay' action='<?php echo $reqURL_onLine; ?>' method='post' target="_blank">
<input type='hidden' name='p0_Cmd'					value='<?php echo $p0_Cmd; ?>'>
<input type='hidden' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
<input type='hidden' name='p2_Order'				value='<?php echo $p2_Order; ?>'>
<input type='hidden' name='p3_Amt'					value='<?php echo $p3_Amt; ?>'>
<input type='hidden' name='p4_Cur'					value='<?php echo $p4_Cur; ?>'>
<input type='hidden' name='p5_Pid'					value='<?php echo $p5_Pid; ?>'>
<input type='hidden' name='p6_Pcat'					value='<?php echo $p6_Pcat; ?>'>
<input type='hidden' name='p7_Pdesc'				value='<?php echo $p7_Pdesc; ?>'>
<input type='hidden' name='p8_Url'					value='<?php echo $p8_Url; ?>'>
<input type='hidden' name='p9_SAF'					value='<?php echo $p9_SAF; ?>'>
<input type='hidden' name='pa_MP'						value='<?php echo $pa_MP; ?>'>
<input type='hidden' name='pd_FrpId'				value='<?php echo $pd_FrpId; ?>'>
<input type='hidden' name='pr_NeedResponse'	value='<?php echo $pr_NeedResponse; ?>'>
<input type='hidden' name='hmac'						value='<?php echo $hmac; ?>'>
<input type="hidden" name="noLoadingPage" value="1"> 


<TABLE width=100% border=0 align="center" cellPadding=0 cellSpacing=1 bgcolor="#CFD0D1">
  <TBODY><TR>
    <TH width="100%" height="23" align="left" background="/12.files/reg_21.jpg" scope=col>&nbsp;&nbsp;<span class="STYLE31 STYLE40">&nbsp;<span class="STYLE43">::</span>订单信息<span class="STYLE43">::</span></span></TH>
  </TR><TR>
    <TH height="199" bgcolor="#ECEAEF" scope=col><DIV align=center><TABLE cellSpacing=0 cellPadding=0 width=580 border=0><TBODY><TR><TH scope=col><DIV align=left></DIV></TH></TR><TR><TH scope=col><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY><TR><TD>&nbsp;</TD>
    </TR><TR><TD background=../NPS_files/box_02.jpg><DIV align=center><TABLE width="94%" border=0 cellPadding=0 cellSpacing=1 borderColor=#ffffff bgcolor="#999999">
      <TBODY><TR><TD width="17%" height="30" align="right" bgcolor="#FFFFFF" class="STYLE40"><DIV align=right class="STYLE40 STYLE31">订单号码：</DIV></TD><TD width="83%" height="20" bgcolor="#FFFFFF" class="STYLE40"><span class="STYLE31">&nbsp;<?php echo $p2_Order; ?></span></TD>
    </TR><TR><TD height="30" align="right" bgcolor="#FFFFFF" class="STYLE40"><DIV align=right class="STYLE31">支付金额：</DIV></TD>
    <TD height="20" bgcolor="#FFFFFF" class="STYLE40">&nbsp;<span class="STYLE31"><?php echo $p3_Amt; ?></span></TD>
    </TR>
        <TR>
          <TD height="30" align="right" bgcolor="#FFFFFF" class="STYLE40"><DIV align=right class="STYLE31">交易日期：</DIV></TD>
      <TD height="20" bgcolor="#FFFFFF" class="STYLE40"><span class="STYLE31">&nbsp;<?php echo date('Y-m-d') ?></span></TD>
    </TR>
        <TR>
          <TD height="30" align="right" bgcolor="#FFFFFF" class="STYLE40"><DIV align=right class="STYLE31">充值账户：</DIV></TD>
      <TD height="20" bgcolor="#FFFFFF" class="STYLE40"><span class="STYLE31">&nbsp;<?php echo $pa_MP; ?></span></TD>
    </TR>
        </TBODY></TABLE>
    </DIV></TD></TR><TR><TD height=7>&nbsp;</TD>
    </TR></TBODY></TABLE></TH></TR><TR>
      <TD><span class="STYLE31 style44">注：请仔细核对以上信息并保存好订单号码以便查询。</span></TD>
    </TR><TR>
      <TD><DIV align=center><TABLE cellSpacing=0 cellPadding=10 width="100%" border=0><TBODY><TR>
        <TD align="center">
          <input type="image" name="imageField" src="115.gif">             </TD>
      </TR></TBODY></TABLE>
      </DIV></TD>
    </TR></TBODY></TABLE>
    </DIV></TH>
  </TR></TBODY></TABLE>
  </DIV>
<br>

</form>
</body>
</html>