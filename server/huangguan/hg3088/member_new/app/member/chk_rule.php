<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");   
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
$str = time();
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
require ("./include/traditional.$langx.inc.php");
require ("./include/config.inc.php");

$username=$_SESSION['UserName'];
$admin=$_SESSION['Admin'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	setcookie('login_uid','');
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}else{
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Welcome</title>
<link href="/style/member/mem_index_data.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<SCRIPT language="JavaScript" src="/js/top.js?v=<?php echo AUTOVER; ?>"></SCRIPT>
<body id="RCHK">

<div id="container">
  <div id="header"><h1><span></span></h1></div>
  
  <div id="info">
  
    <?php echo $rule?>
	<div class="chk">
	  <form action="logout.php" method="get" name="myForm">
        <input type="hidden" name="uid" value="<?php echo $uid?>">
        <input type="hidden" name="langx" value="<?php echo $langx?>">
        <input name="submit" type="submit" style="width:80px" value="<?php echo $rule8?>">
      </form>
	  <form action="./FT_index.php" method="get" name="myForm">
        <input type="hidden" name="uid" value="<?php echo $uid?>">
        <input type="hidden" name="langx" value="<?php echo $langx?>">
        <input type="hidden" name="mtype" value="3">
        <input name="submit2" type="submit" style="width:80px" value="<?php echo $rule9?>">
      </form>
	</div>
    <br class="clear" />
    </div><!-- rule end -->
  </div>
  <!-- info end -->

</div>
<?php echo $rule_bottom?>
</body>
</html>
<?php
$sql = "select message,message_tw from ".DBPREFIX."web_message_data where UserName='".$username."' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
$sql="select msg_member_alert,msg_member,msg_member_tw,msg_member_en from ".DBPREFIX."web_system_data where Admin='".$admin."' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
switch($langx){
case 'zh-cn':
	$talert=$row['msg_member_tw'];
	break;
case 'zh-cn':
	$talert=$row['msg_member'];
	break;
case 'en-us':
	$talert=$row['msg_member_en'];
	break;
case 'th-tis':
	$talert=$row['msg_member_tw'];
	break;
}

if ($row['msg_member_alert']==1 and $talert<>''){
	
	echo "<script>alert('$talert');</script>";
}
}else{
	switch($langx){
	case 'zh-cn':
		$talert=$row['message_tw'];
		break;
	case 'zh-cn':
		$talert=$row['message'];
		break;
	case 'en_us':
		$talert=$row['message_en'];
		break;
	case 'th-tis':
		$talert=$row['message_tw'];
		break;
	}
	if ($talert<>''){
	
		echo "<script>alert('$talert');</script>";
	}
	
}
}
?>
