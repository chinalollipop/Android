<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
?>
<script language="javascript"> 
<!-- 
/*屏蔽所有的js??*/ 
function killerrors() { 
return true; 
} 
window.onerror = killerrors; 
//--> 
</script> 
<script>//if(self == top) parent.location='/'
</script>
<?php

require_once ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];

$sql = "select ID,Level,UserName,SubUser,SubName from ".DBPREFIX."web_system_data where Oid='$uid' and UserName='$loginname'";

$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);

$row = mysqli_fetch_assoc($result);


if($_POST['submit']) {
	if(!strlen($_POST['title']) || !strlen($_POST['content'])) {
		echo "<script>alert('請填寫標題和內容');</script>";
	}else {
		$mysql="update ".DBPREFIX."web_nconfig set title='".$_POST['title']."',content='".$_POST['content']."' limit 1;";
		mysqli_query($dbLink,$mysql) or die ("數據庫錯誤!");
		
		$msg = "<script>alert('操作成功');</script>";

	}
}

?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/control/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">
<!--
.m_mem_ed {  background-color: #bdd1de; text-align: right}
-->
</style>
<?php echo $msg?>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" vlink="#0000FF" alink="#0000FF" onLoad="">
<div id="Layer1" style="position:absolute; width:780px; height:26px; z-index:1; left: 0px; top: 268px; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000"></div>
 <FORM NAME="myFORM" ACTION="notice_config.php?uid=<?php echo $uid?>&mtype=<?php echo $mtype?>&langx=<?php echo $langx?>" METHOD=POST >

  <input type="hidden" name="uid" value="<?php echo $uid?>">

  <table width="780" border="0" cellspacing="0" cellpadding="0">

<?php
include("fckeditor/fckeditor.php") ;
$rs = mysqli_query($dbLink,"select * from ".DBPREFIX."web_nconfig");
$qs = mysqli_fetch_assoc($rs);


?>
<tr><td height="30"><h2>注冊歡迎信息設置</h2></td></tr>
<tr><td height="30">標題 <input name="title" type="text" value="<?php echo $qs['title']?>" size="50"/></td></tr>
<tr><td align="left" valign="top" height="150">內容 
<?php
// Automatically calculates the editor base path based on the _samples directory.
// This is usefull only for these samples. A real application should use something like this:
// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
$sBasePath = $_SERVER['PHP_SELF'] ;

$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "notice_send.php" ) ).'fckeditor/' ;

$oFCKeditor = new FCKeditor('content') ;
$oFCKeditor->BasePath	= $sBasePath ;
$oFCKeditor->Value		= $qs['content'] ;
$oFCKeditor->Create() ;
?>
</td></tr>
<tr><td><br><input name="submit" value="發送" type="submit"/></td></tr>

  </table>
</form>

</body>
</html>

