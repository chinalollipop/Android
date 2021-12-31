<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];

require ("../include/traditional.$langx.inc.php");

$type=$_REQUEST['type'];
$che=$_REQUEST['chk'];

if ($type=='add'){
	$address=$_REQUEST['Address'];//地址
	$business=$_REQUEST['Business'];//商户码
	$keys=$_REQUEST['Keys'];//密匙
	$mysql="insert into `".DBPREFIX."web_payment_data`(`Address`,`Business`,`Keys`)values('$address','$business','$keys')";
	mysqli_query($dbMasterLink,$mysql);
	echo "<script>alert('新增加一条内容');</script>";
}else if ($type=='edit'){
	if (empty($che)){
		echo "<script>alert('请选择要修改的内容');history.back(-1);</script>";
		exit;
	}
	foreach($che as $values){
		$address=$_REQUEST['Address'.$values];//地址
		$business=$_REQUEST['Business'.$values];//商户码
		$keys=$_REQUEST['Keys'.$values];//密匙
		$switch=$_REQUEST['Switch'.$values];//密匙
		$mysql="update `".DBPREFIX."web_payment_data` set `Address`='$address',`Business`='$business',`Keys`='$keys',`Switch`='$switch' where id='$values'";
		mysqli_query($dbMasterLink,$mysql);
		echo "<script>alert('更新ID ".$values." 成功');</script>";
	}
}else if ($type=='del'){
	foreach($che as $values){
		$mysql="delete from `".DBPREFIX."web_payment_data` where `ID`='$values'";
		mysqli_query($dbMasterLink,$mysql);
		echo "<script>alert('删除ID ".$values." 成功');</script>";
	}
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script>
function sbar(st){
st.style.backgroundColor='#BFDFFF';
}
function cbar(st){
st.style.backgroundColor='';
}

function SubChk(){
	if (document.all.Address.value==''){
		document.all.Address.focus();
		alert("请输入返回地址!!");
		return false;
	}
	if (document.all.Business.value==''){
		document.all.Business.focus();
		alert("请输入商户号!!");
		return false;
	}
	if (document.all.Keys.value==''){
		document.all.Keys.focus();
		alert("请输入商户密匙!!");
		return false;
	}
}
function edit(){
	document.getElementById("type").value='edit';
}
function del(){
	if(!confirm("确认要删除吗")){
		return false;
	}
	document.getElementById("type").value='del';
	return true;
}
</script>
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" vlink="#0000FF" alink="#0000FF">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="m_tline">&nbsp;&nbsp;支付方式&nbsp;&nbsp;&nbsp;&nbsp;<a href="?uid=<?php echo $uid?>&langx=<?php echo $langx?>&type=Y">新增</a></td>
    
  </tr>
  <tr> 
    <td colspan="2" height="4"></td>
  </tr>
</table>
<?php
if ($type=='Y'){
?>
<table width="975" border="0" cellpadding="0" cellspacing="1" class="m_tab">
<form name="myform" action="" method="post" onSubmit="return SubChk();">  
  <tr class="m_title"> 
    <td width=30>ID</td>
    <td width=80>支付公司</td>
    <td width=230>返回地址</td>
    <td width="100">商户号</td>
    <td width="529">商户密匙</td>
    </tr>
  <tr class=m_cen>
    <td>1</td>
    <td>YeePay</td>
    <td><input name="Address" id="Address" type="text" value="" style="width:230px;"></td>
    <td><input name="Business" id="Business" type="text" value="" style="width:100px;"></td>
    <td><input name="Keys" id="Keys" type="text" value="" style="width:525px;"></td>
  </tr>
  <tr class=m_cen>
    <td colspan="6"><input class="za_button" type="submit" value="提交" name="cmdsubmit">&nbsp;&nbsp;&nbsp;&nbsp;<input class="za_button" type="reset" value="取消" name="cmdcancel"><input type="hidden" name="type" value="add"></td>
    </tr>
</form>
</table>
<?php
}else{
?>
<table width="975" border="0" cellpadding="0" cellspacing="1" class="m_tab">
<form name="myform" action="" method="post">  
  <tr class="m_title"> 
    <td width=30>ID</td>
    <td width=30>选中</td>
    <td width=30>启用</td>
    <td width=80>支付公司</td>
    <td width=230>返回地址</td>
    <td width="100">商户号</td>
    <td width="465">商户密匙</td>
    </tr>
<?php
$i=1;
$mysql="select * from ".DBPREFIX."web_payment_data";
$result=mysqli_query($dbLink,$mysql);
while($row=mysqli_fetch_assoc($result)){
?>
  <tr class=m_cen>
    <td><?php echo $i?></td>
    <td><input type="checkbox" value="<?php echo $row['ID']?>" name="chk[]"></td>
    <td><input name="Switch<?php echo $row['ID']?>" type="checkbox" id="Switch<?php echo $row['ID']?>" value="1" <?php if ($row['Switch']=='1'){echo 'checked';}?>></td>
    <td>YeePay</td>
    <td><input name="Address<?php echo $row['ID']?>" id="Address<?php echo $row['ID']?>" type="text" value="<?php echo $row['Address']?>" style="width:230px;"></td>
    <td><input name="Business<?php echo $row['ID']?>" id="Business<?php echo $row['ID']?>" type="text" value="<?php echo $row['Business']?>" style="width:100px;"></td>
    <td><input name="Keys<?php echo $row['ID']?>" id="Keys<?php echo $row['ID']?>" type="text" value="<?php echo $row['Keys']?>" style="width:465px;"></td>  
  </tr>
<?php
$i++;
}
?>
  <tr class=m_cen>
    <td colspan="7"><input type="hidden" name="type"><input type="submit" name="submit" value="修改选中" class="za_button" onClick="edit()">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="删除选中" class="za_button" onClick="return del();"></td>
    </tr>
</form>
</table>
<?php
}
?>
</body>
</html>