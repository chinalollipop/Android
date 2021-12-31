<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$user=$_REQUEST['username'];
$pass=$_REQUEST['password'];
$action=$_REQUEST['action'];
require ("../include/traditional.$langx.inc.php");

if ($action==1){
	$password_safe=strtolower($_REQUEST["password_safe"]);
	$date=date("Y-m-d");
	$mysql="select LoginName from ".DBPREFIX.MEMBERTABLE." where LoginName='$password_safe'";
	$result = mysqli_query($dbLink,$mysql);
	$count=mysqli_num_rows($result);
    if ($count>0){
		echo wterror("您输入的登录帐号 $password_safe 已经有人使用了，请回上一页重新输入!!");
		exit;
    }else{
	    $mysql="update ".DBPREFIX.MEMBERTABLE." set LoginName='$password_safe',EditDate='$date' , Online=1 , OnlineTime=now() where UserName='$user' and Password='$pass'";
	    mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
	}
	echo "<Script language=javascript>alert('已成功修改登陆帐号~~请回首页重新登入');window.open('".BROWSER_IP."','_top')</script>";
}
	
?>
<html>
<head>
<title>變更登陆帐号</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/member/mem_order.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">
<!--
iframe { width:0; height:0; border:0; clear:right;}
-->
</style>
</head>
<script language="JavaScript" src="/js/<?php echo $langx?>.js?v=<?php echo AUTOVER; ?>" type="text/javascript"></script>
<script language="JavaScript" src="/js/chg_long_id.js?v=<?php echo AUTOVER; ?>" type="text/javascript"></script>
<script>
var passwd = "aaaaaa";
var langx='<?php echo $langx?>';
</script>

<body oncontextmenu="window.event.returnValue=false" bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" id="PWD">
<table width="450" border="0" align="center" cellpadding="1" cellspacing="1" class="pwd_side">
	<tr class="pwd_bg">
		<td colspan="2">
			<table border="0" cellpadding="1" cellspacing="1">
			  	<tr>
			  		<td width="400" class="pwd_title"><?php echo $Chg_Setting_Login_ID?></td>
					<td width="100" class="point" style="cursor:hand;" onClick="javascript:window.open('/tpl/member/<?php echo $langx?>/guide.html');" ><?php echo $Chg_Setting_Guide?></td>
			  	</tr>
		 	</table>
	 	</td>
	</tr>
	<tr>
		<td colspan="2" class="pwd_txt">
			<?php echo $Chg_Please_create_a_new_name_or_code_which_is_convenient_for_user_to_remember_as_your?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="pwd_txt">
			<?php echo $Chg_Setting_Rules?> : <?php echo $Chg_The_Login_ID_must_have_6_12_alphanumeric_with_at_least_2_capital_or_lowercase_letter_and_digit_0_9?>
			<br><?php echo $Chg_For_Example?>
		</td>
	</tr>	
	<form name="ChgPwdForm" method="post">
		<tr>
			<td width="100"align="right"   class="pwd_txt" ><?php echo $Chg_Login_ID?></td>
			<td width="350"class="pwd_txt">
				<input type="TEXT" name="password_safe" value="" size=12 maxlength=12 class="za_text_02">
				<input type="button" name="check" id='check' value="<?php echo $Chg_Check?>" class="za_button" onclick='ChkMem();'>
				<font class="red_txt"><?php echo $Chg_Note?>：</font><?php echo $Chg_The_setting_cannot_be_modified?>。
			</td>
		</tr>
		<tr >
			<td colspan="2" align="center"  class="pwd_bg">
				<input type="button" value="<?php echo $Chg_Submit?>" onClick="return SubChk();" class="za_button_01">&nbsp;
				<input type="button" name="cancel" value="<?php echo $Chg_Cancel?>" class="za_button_01" onClick="javascript:history.go(-2);">
				<input type="hidden" name="action" value="1">
				<input type="hidden" name="uid" value="<?php echo $uid?>">
				<input type="hidden" name="username" value="<?php echo $user?>">
				<input type="hidden" name="password" value="<?php echo $pass?>">
			</td>
		</tr>
	</form>
</table>
<table align="center">
	<tr >
		<td class="white">
			<ul><li><?php echo $Chg_After_completion?><font class="red_txt"><?php echo $Chg_members_have_to_use_the_new_Login_ID_to_login?></font><BR><?php echo $Chg_and_the_original_Members_ID_is_only_for_identification_purpose?></li></ul>
		</td>
	</tr>
</table>
<iframe id="getData"></iframe>
</body>
</html>
