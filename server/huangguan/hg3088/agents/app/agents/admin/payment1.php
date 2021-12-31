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
	$bankname=$_REQUEST['bankname'];//地址
	$username=$_REQUEST['username'];//商户码
	$banknum=$_REQUEST['banknum'];//密匙
	$address=$_REQUEST['address'];//商户码
	$url=$_REQUEST['url'];//密匙
	$mysql="insert into `".DBPREFIX."banks`(`bankname`,`username`,`banknum`,`address`,`url`)values('$bankname','$username','$banknum','$address','$url')";
	//echo $mysql;exit;
	mysqli_query($dbMasterLink,$mysql);
	echo "<script>alert('新增加一条内容');</script>";
}else if ($type=='edit'){
	if (empty($che)){
		echo "<script>alert('请选择要修改的内容');history.back(-1);</script>";
		exit;
	}
	foreach($che as $values){
		$bankname=$_REQUEST['bankname'];//地址
	$username=$_REQUEST['username'];//商户码
	$banknum=$_REQUEST['banknum'];//密匙
	$address=$_REQUEST['address'];//商户码
	$url=$_REQUEST['url'];//密匙
	$switch=$_REQUEST['Switch'.$values];//密匙
		$mysql="update `".DBPREFIX."banks` set `bankname`='$bankname',`username`='$username',`banknum`='$banknum',`address`='$address',`url`='$url',`Switch`='$switch' where id='$values'";
		mysqli_query($dbMasterLink,$mysql);
		echo "<script>alert('更新ID ".$values." 成功');</script>";
	}
}else if ($type=='del'){
	foreach($che as $values){
		$mysql="delete from `".DBPREFIX."banks` where `ID`='$values'";
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
	if (document.all.bankname.value==''){
		document.all.bankname.focus();
		alert("请输入银行名称!!");
		return false;
	}
	if (document.all.username.value==''){
		document.all.username.focus();
		alert("请输入账号户名!!");
		return false;
	}
	if (document.all.banknum.value==''){
		document.all.banknum.focus();
		alert("请输入银行账号!!");
		return false;
	}
	if (document.all.address.value==''){
		document.all.address.focus();
		alert("请输入开户地址!!");
		return false;
	}
	if (document.all.url.value==''){
		document.all.url.focus();
		alert("请输入银行网址!!");
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
    <td class="m_tline">&nbsp;&nbsp;银行资料&nbsp;&nbsp;&nbsp;&nbsp;<a href="?uid=<?php echo $uid?>&langx=<?php echo $langx?>&type=Y">新增</a></td>
    
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
    <td width=80>银行名称</td>
    <td width=230>账号户名</td>
    <td width="100">银行账号</td>
    <td width="529">开户地址</td>
    <td width="529">银行网址</td>
	</tr>
  <tr class=m_cen>
    <td>1</td>
    <td><input name="bankname" id="bankname" type="text" value="" style="width:100px;"></td>
    <td><input name="username" id="username" type="text" value="" style="width:100px;"></td>
    <td><input name="banknum" id="banknum" type="text" value="" style="width:100px;"></td>
    <td><input name="address" id="address" type="text" value="" style="width:300px;"></td>
    <td><input name="url" id="url" type="text" value="" style="width:300px;"></td>
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
    <td width=80>银行名称</td>
    <td width=230>账号户名</td>
    <td width="100">银行账号</td>
    <td width="465">开户地址</td>
    <td width="465">银行网址</td>
	</tr>
<?php
$i=1;
$mysql="select * from ".DBPREFIX."banks";
$result=mysqli_query($dbLink,$mysql);
while($row=mysqli_fetch_assoc($result)){
?>
  <tr class=m_cen>
    <td><?php echo $i?></td>
    <td><input type="checkbox" value="<?php echo $row['ID']?>" name="chk[]"></td>
    <td><input name="bankname<?php echo $row['ID']?>" id="bankname<?php echo $row['ID']?>" type="text" value="<?php echo $row['bankname']?>" style="width:100px;"></td>
    <td><input name="username<?php echo $row['ID']?>" id="username<?php echo $row['ID']?>" type="text" value="<?php echo $row['username']?>" style="width:100px;"></td>
    <td><input name="banknum<?php echo $row['ID']?>" id="banknum<?php echo $row['ID']?>" type="text" value="<?php echo $row['banknum']?>" style="width:100px;"></td>  
    <td><input name="address<?php echo $row['ID']?>" id="address<?php echo $row['ID']?>" type="text" value="<?php echo $row['address']?>" style="width:300px;"></td>
    <td><input name="url<?php echo $row['ID']?>" id="url<?php echo $row['ID']?>" type="text" value="<?php echo $row['url']?>" style="width:300px;"></td>  
  </tr>
<?php
$i++;
}
?>
  <tr class=m_cen>
    <td colspan="7"><input type="hidden" name="type">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="删除选中" class="za_button" onClick="return del();"></td>
    </tr>
</form>
</table>
<?php
}
?>
</body>
</html>