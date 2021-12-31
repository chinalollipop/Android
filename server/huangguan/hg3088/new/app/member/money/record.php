<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
$file = str_replace("\\","/",dirname(dirname(__FILE__)));
include $file."/include/address.mem.php";
require ($file."/include/config.inc.php");
require ($file."/include/define_function_list.inc.php");

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$username=$_REQUEST['username'];
//require ($file."/include/traditional.$langx.inc.php");

$username=$_SESSION['UserName'];
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
?>
<html>
<head>
<title>History</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/member/mem_body<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
#MFT #box { width:780px;}
#MFT .news { white-space: normal!important; color:#300; text-align:left; padding:2px 4px;}
</style>
</HEAD>
<BODY id="MFT" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<?php
$name=$_SESSION['UserName'];
$nosql = "select id from ".DBPREFIX."web_sys800_data where UserName='$name' order by id desc";
$sql = "select Type,UserName,Phone,Date,CurType,Gold,Bank_Account,Bank_Address,Checked,Cancel,Payway from ".DBPREFIX."web_sys800_data where UserName='$name' order by id desc";
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);


$resultn = mysqli_query($dbLink,$nosql);
$nocount=mysqli_num_rows($resultn);
//$result = mysqli_query($dbLink,$sql);
//$cou=mysqli_num_rows($result);
$page=$_REQUEST['page'];
if ($page==''){
	$page=0;
}
$page_size=20;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
$result = mysqli_query($dbLink, $mysql);
?>
<form method="post" name="main" action="take.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>" onSubmit="return VerifyData()">
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td class="top">
  	  <h1><em>存款提款记录</em></h1>
	</td>
  </tr>
  <tr>
    <td class="mem">
      <table width="100%" border="0" cellpadding="0" cellspacing="1" class="game">
		<tr class="b_rig">
		  <td width="77" height="30" align="center">会员</td>
          <td width="100" align="center">联系电话</td>
          <td width="120" align="center">日期</td>
		  <td width="60" align="center">存储类型</td>
          <td width="60" align="center">使用币值</td>
          <td width="65" align="center">金额</td>
          <td width="160" align="center">银行资料</td>
		  <td width="169" align="center">备注</td>
		</tr>
<?php
if($cou==0){
?>
      <?php
}else{
?>
<?php
$tod_num=0;
$tod_bet=0;
$tod_win=0;
while ($myrow = mysqli_fetch_assoc($result)){
if($myrow['Type']=='S'){ $type = '存款';}
if($myrow['Type']=='T'){ $type = '提款';}
if($myrow['Type']=='Z'){ $type = '赠送';}
?>
		<tr class="b_rig">
		  <td height="30" align="center"><?php echo $myrow['UserName']?></td>
		  <td align="center"><?php echo $myrow['Phone']?></td>
          <td align="center"><?php echo $myrow['Date']?></td>
          <td align="center"><?php echo $type?></td>
          <td align="center"><?php echo $myrow['CurType']?></td>
          <td ><?php echo $myrow['Gold']?></td>
          <td align="center"><?php echo $myrow['Bank_Account']?><br><?php echo $myrow['Bank_Address']?><br><?php
		  if ($myrow['Checked']==0 and $myrow['Cancel']==0)
		  {
			  echo "<font color=blue>审核中，请等待...</font>";
		  }
		  else if($myrow['Checked']==2)
		  {
			  echo "<font color=red>未通过审核！</font>";
		  }
		  else
		  {
			  echo "<font color=green>已审核完毕！</font>";
		  }
		  ?></td>
            <td align="center">    <?php
		  if($myrow['Checked']==2 and $myrow['Cancel']==2)
		  {
			  echo "<font color=red>未全额投注</font>";
		  }
		  else if($myrow['Checked']==2 and $myrow['Cancel']==3)
		  {
			  echo "<font color=red>未完成领取优惠后提款所需的5倍流水</font>";
		  }
		  else if($myrow['Checked']==2 and $myrow['Cancel']==4)
		  {
			  echo "<font color=red>提款银行信息不完整或不正确</font>";
		  }
		  else if ($myrow['Type']=='S' and $myrow['Payway']=='O')
		  {
	          echo "<font color=green>已领10%存款优惠</font>";
		  }
		  else if ($myrow['Type']=='S' and $myrow['Payway']=='N')
		  {
	          echo "<font color=blue>公司入款</font>";
		  }
          else if ($myrow['Type']=='S' and $myrow['Payway']=='W')
          {
	          echo "<font color=blue>在线存款</font>";
		  }
          else if ($myrow['Type']=='S' and $myrow['Payway']=='A')
          {
	          echo "<font color=blue>代理佣金</font>";
		  }
		  ?></td>
		</tr>
<?php
$tod_num=$tod_num+1;
}
?>
		<tr class="b_rig">
		  <td height="30" colspan="8" align="center">共计 <?php echo $page_count?> 页 - 当前第 <?php echo $page+1;?> 页 <a style="font-weight: normal; color:#000;" href="?uid=<?php echo $uid?>&langx=<?php echo $langx?>&page=<?php echo ($page-1)?>">上一页</a> | <a style="font-weight: normal; color:#000;" href="?uid=<?php echo $uid?>&langx=<?php echo $langx?>&page=<?php echo ($page+1)?>">下一页</a></td>
		  </tr>
        <?php
}
?>
      </table>
    </td>
  </tr>
  <tr><td id="foot"><b>&nbsp;</b></td></tr>
</table>
</form>
</BODY>
</HTML>
