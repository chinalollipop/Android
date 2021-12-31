<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
?>
<script language="javascript"> 

function killerrors() { 
return true; 
} 
window.onerror = killerrors; 

</script> 

<?php

require_once ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
if($_GET['act']=='delall') {
    unset($_SESSION['notice_idstic']);
    echo "<script>history.go(-1);</script>";
    exit();
}

$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];

$sql = "select ID,Level,UserName,SubUser,SubName from ".DBPREFIX."web_system_data where Oid='$uid' and UserName='$loginname'";

$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);

$agid=$row['ID'];


if($_POST['submit']) {
	if(!strlen($_POST['title']) || !strlen($_POST['content'])) {
		echo "<script>alert('請填寫標題和內容');</script>";
	}else {
		$mysql="insert into ".DBPREFIX."web_notices(title,content,addtime,type,addpople,reply_id) values ('".$_POST['title']."','".$_POST['content']."','".time()."','".$_POST['type']."','".$agid."',0)";
		mysqli_query($dbMasterLink,$mysql) or die ("數據庫錯誤!");
		
		
		if($_POST['type'] == 2 && !empty($_POST['ids'])) {
			$re = mysqli_query($dbLink,"select last_insert_id()");
			$re1= mysqli_fetch_assoc($re);
			
			foreach ($_POST['ids'] as $v) {
				$mysql="insert into ".DBPREFIX."web_notices_to(notice_id,notice_to) values ('".$re1[0]."','".$v."')";
				$re = mysqli_query($dbMasterLink,$mysql);
				unset($_SESSION['notice_idstic']);
			}
		}
		$msg = "<script>alert('操作成功');</script>";

	}
}

?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/control/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript"  src="../../../js/agents/jquery.js"></script>
<style type="text/css">
<!--
.m_mem_ed {  background-color: #bdd1de; text-align: right}
-->
</style>
<?php echo $msg?>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" vlink="#0000FF" alink="#0000FF" onLoad="">
<div id="Layer1" style="position:absolute; width:780px; height:26px; z-index:1; left: 0px; top: 268px; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000"></div>
 <FORM NAME="myFORM" ACTION="notice_send.php?uid=<?php echo $uid?>&mtype=<?php echo $mtype?>&langx=<?php echo $langx?>" METHOD=POST >

  <input type="hidden" name="uid" value="<?php echo $uid?>">

  <table width="780" border="0" cellspacing="0" cellpadding="0">
<tr>
                
            <td>
              
              <?php
              if(!empty($_SESSION['notice_idstic'])) {
              	
              	$str = '<h3>發送會員名單</h3><input name=\'type\' value=\'2\' type=\'hidden\'/><table  width="780" border="0" cellspacing="1" cellpadding="0" class="m_tab_ed"><tr bgcolor="#6FB5DF"><td style="border-bottom:1px ">會員名稱</td><td >會員帳號</td><td></td></tr>';
              	
              	
              	$whe =  "  in (".implode(',',$_SESSION['notice_idstic']).") ";
              	$sql = "select ID,UserName,PassWord,Alias,Money,ratio,date_format(AddDate,'%m-%d / %H:%i') as AddDate,pay_type,Agents,OpenType from ".DBPREFIX.MEMBERTABLE." where ID $whe";
              	$result = mysqli_query($dbLink, $sql);
              	//$cou=mysqli_num_rows($result);
              	//echo $cou;
              	while ($row = mysqli_fetch_assoc($result)){
              		$str .="<tr><td style='border-bottom:1px dashed #CCCCCC'>".$row['Alias']."&nbsp;</td><td  style='border-bottom:1px dashed #CCCCCC'>".$row['UserName']."</td><td style='border-bottom:1px dashed #CCCCCC;text-align:center'><input name='ids[]' id='del_".$row['ID']."' type='hidden' value='".$row['ID']."'/><input name='d' type='button' onclick=\"$(this).parent().parent().remove();\" value='刪除'/></td></tr>";
              	}
              	$str .= "</table>";
              	echo $str;
             // }else {
             //   echo "<h3>發送公告</h3><input name='type' value='1' type='hidden'/>";
              }
              ?>
              
             
	       </td>
      
</tr>
<?php
include("fckeditor/fckeditor.php") ;
?>
<tr><td height="30">標題 <input name="title" type="text" value="<?php echo $_POST['title']?>" size="50"/></td></tr>
<tr><td align="left" valign="top" height="150">內容 
<?php
// Automatically calculates the editor base path based on the _samples directory.
// This is usefull only for these samples. A real application should use something like this:
// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
$sBasePath = $_SERVER['PHP_SELF'] ;

$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "notice_send.php" ) ).'fckeditor/' ;

$oFCKeditor = new FCKeditor('content') ;
$oFCKeditor->BasePath	= $sBasePath ;
$oFCKeditor->Value		= $_POST['content'] ;
$oFCKeditor->Create() ;
?>
</td></tr>
<tr><td><br><input name="submit" value="發送" type="submit"/></td></tr>

  </table>
</form>

</body>
</html>