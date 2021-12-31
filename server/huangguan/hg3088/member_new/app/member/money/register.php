<?php
//ini_set("display_errors", "On");
//error_reporting(E_ALL);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
$file = str_replace("\\","/",dirname(dirname(__FILE__)));
//echo $file;die();
//echo $file."test.php";
//include $file."/test.php";
include $file."/include/address.mem.php";
require ($file."/include/config.inc.php");
require ($file."/include/define_function_list.inc.php");

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
require ($file."/include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$sql = "select Alias,UserName,Money,Phone,Address,Notes from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$paysql = "select Address from ".DBPREFIX."web_payment_data where Switch=1";
$payresult = mysqli_query($dbLink,$paysql);
$payrow=mysqli_fetch_assoc($payresult);
$address=$payrow['Address'];
?>
<html>
<head>
<title>History</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/member/mem_body<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
<!--

#MFT #box { width:780px;}
#MFT .news { white-space: normal!important; color:#300; text-align:left; padding:2px 4px;}
.STYLE1 {color: #FF0000}
-->
</style>
<script language="JAVAScript">

//去掉空格
function check_null(string) { 
var i=string.length;
var j = 0; 
var k = 0; 
var flag = true;
while (k<i){ 
if (string.charAt(k)!= " ") 
j = j+1; 
k = k+1; 
} 
if (j==0){ 
flag = false;
} 
return flag; 
}
function VerifyData() {
if (document.main.p3_Amt.value == "") {
			alert("请输入存款金额！")
			document.main.p3_Amt.focus();
			return false;
}
if (document.main.p3_Amt.value !="") {
		  if(document.main.p3_Amt.value <100 )
		  {
			alert("充值不能小于100元！")
			document.main.p3_Amt.focus();
			return false;
		  }
}/**/	
}

</script>
</HEAD>
<BODY id="MFT" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<form method="post" name="main" action="<?php echo $address ?>/req.php" onSubmit="return VerifyData()">
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td class="top">
  	  <h1><em>在线充值</em></h1>
	</td>
  </tr>
  <tr>
    <td class="mem">
      <table border="0" cellspacing="1" cellpadding="0" class="game">
		<tr class="b_rig">
		  <td width="15%" height="30"><div align="right">阁下姓名：</div></td>
		  <td width="87%"><div align="left"><?php echo $row['Alias']?></div></td>
		</tr>
		<tr class="b_rig">
		  <td width="15%" height="30"><div align="right">会员帐号：</div></td>
		  <td width="87%"><div align="left"><?php echo $row['UserName']?></div></td>
		</tr>
		<tr class="b_rig">
		  <td height="30"><div align="right">目前额度：</div></td>
		  <td width="87%"><div align="left"><?php echo $row['Money']?></div></td>
		</tr>
        <tr class="b_rig">
		  <td align="right" height="30"><div align="right">手机号码：</div></td>
		  <td width="87%"><div align="left"><?php echo $row['Phone']?></div></td>
		</tr>
		<tr class="b_rig" style="display:none;">
		  <td width="15%" height="30"><div align="right">QQ/MSN/Email：</div></td>
		  <td width="87%"><div align="left"><?php echo $row['Address']?></div></td>
		</tr>
		<tr class="b_rig">
		  <td height="30"><div align="right">备注：</div></td>
		  <td width="87%"><div align="left"><?php echo $row['Notes']?></div></td>
		</tr>
		<tr class="b_rig">
		  <td height="30"><div align="right">充值金额：</div></td>
		  <td width="87%"><div align="left">
		  <input id="p3_Amt" maxLength="12" size="12" name="p3_Amt" style="width:180px">&nbsp;*
		  <input type="hidden" name="pa_MP" id="pa_MP" value="<?php echo $row['UserName']?>" />
		  <input type="hidden" name="p8_Url" id="p8_Url" value="<?php echo $address?>/callback.php" />
		  <input size="50" type="hidden" name="pr_NeedResponse" id="pr_NeedResponse" value="1" />                           
		  <span class="style1">注:最低值100 单位:元</span></div></td>
		</tr>
		<tr class="b_rig">
		  <td height="30"><div align="right">选择银行：</div></td>
		  <td width="87%"><div align="left">
		     <table width="100%" border="0" cellpadding="0" cellspacing="0" style="left:0;">
               <tr class="b_rig">
                 <td><input name="pd_FrpId" type="radio" value="ICBC-NET" checked>工商银行</td>
                 <td><input name="pd_FrpId" type="radio" value="CCB-NET">建设银行</td>
                 <td><input name="pd_FrpId" type="radio" value="ABC-NET">农业银行</td>
                 <td><input name="pd_FrpId" type="radio" value="CMBCHINA-NET"  />招商银行</td>
               </tr>
               <tr class="b_rig"> 
                 <td><input name="pd_FrpId" type="radio" value="BOCO-NET">交通银行</td>
                 <td><input name="pd_FrpId" type="radio" value="CMBC-NET">民生银行</td>
                 <td><input name="pd_FrpId" type="radio" value="CIB-NET">兴业银行</td>
                 <td><input name="pd_FrpId" type="radio" value="BOC-NET">中国银行</td>
               </tr>
                <tr class="b_rig"> 
                 <td><input name="pd_FrpId" type="radio" value="POST-NET">邮政储蓄</td>
                 <td><input name="pd_FrpId" type="radio" value="SPDB-NET">浦发银行</td>
                 <td><input name="pd_FrpId" type="radio" value="GDB-NET">广发银行</td>
                 <td><input name="pd_FrpId" type="radio" value="SDB-NET">深发银行</td>
               </tr>
               <tr class="b_rig"> 
                 <td><input name="pd_FrpId" type="radio" value="CEB-NET">光大银行</td>
                 <td><input name="pd_FrpId" type="radio" value="ECITIC-NET">中信银行</td>
                 <td><input name="pd_FrpId" type="radio" value="HXB-NET">华夏银行</td>
                 <td><input name="pd_FrpId" type="radio" value="PINGANBANK-NET">平安银行</td>
               </tr>

		     </table>
		  </td>
		</tr>
		<tr class="b_rig">
		  <td height="30" colspan="2" align="center"><span class="STYLE1">注意：交易成功后请点击返回支付网站可以查看您的订单信息。</span></td>
		  </tr>
		<tr class="b_rig">
		  <td colSpan="2" height="30"><div align="center"> 
		  <input class="input" type="submit" value="立即充值" name="submit">
		  <input class="input" type="reset" value="重新填写" name="submit2"></div></td>
		</tr>
      </table>
    </td>
  </tr>
  <tr><td id="foot"><b>&nbsp;</b></td></tr>
</table>
</form>
</BODY>
</HTML>
