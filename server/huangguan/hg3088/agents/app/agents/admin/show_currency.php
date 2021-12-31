<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$type=$_REQUEST["type"];

require ("../include/traditional.$langx.inc.php");

switch ($type){
case "Currency":
	$mysql="update ".DBPREFIX."web_type_class set RMB_Rate=".$_REQUEST['RMB_Rate'].",RMB_Rates=".$_REQUEST['RMB_Rates'].",HKD_Rate=".$_REQUEST['HKD_Rate'].",HKD_Rates=".$_REQUEST['HKD_Rates'].",USD_Rate=".$_REQUEST['USD_Rate'].",USD_Rates=".$_REQUEST['USD_Rates'].",MYR_Rate=".$_REQUEST['MYR_Rate'].",MYR_Rates=".$_REQUEST['MYR_Rates'].",SGD_Rate=".$_REQUEST['SGD_Rate'].",SGD_Rates=".$_REQUEST['SGD_Rates'].",THB_Rate=".$_REQUEST['THB_Rate'].",THB_Rates=".$_REQUEST['THB_Rates'].",GBP_Rate=".$_REQUEST['GBP_Rate'].",GBP_Rates=".$_REQUEST['GBP_Rates'].",JPY_Rate=".$_REQUEST['JPY_Rate'].",JPY_Rates=".$_REQUEST['JPY_Rates'].",EUR_Rate=".$_REQUEST['EUR_Rate'].",EUR_Rates=".$_REQUEST['EUR_Rates']." where ID='1'";
	mysqli_query($dbMasterLink,$mysql);
break;
}
$mysql = "select * from ".DBPREFIX."web_type_class  where ID='1'";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);	
?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<body >
<dl class="main-nav"><dt><?php echo $Mnu_Current?> <a href="?uid=<?php echo $uid?>&langx=<?php echo $langx?>&type=Y"></a></dt><dd> </dd></dl>
<div class="main-ui">
    <table class="m_tab">
  <form name=Currency action="" method=post>
  <tr class="m_title"> 
    <td width="148"><?php echo $Mem_currency?></td>
    <td width="148"><?php echo $Mem_code?></td>
    <td width="150"><?php echo $Mem_Today_Exchange?></td>
    <td width="149"><?php echo $Mem_Radioset?></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_RMB?></td>
    <td><?php echo $row['RMB']?></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['RMB_Rate']?>" name=RMB_Rate></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['RMB_Rates']?>" name=RMB_Rates></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_HKD?></td>
    <td><?php echo $row['HKD']?></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['HKD_Rate']?>" name=HKD_Rate></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['HKD_Rates']?>" name=HKD_Rates></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_USD?></td>
    <td><?php echo $row['USD']?></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['USD_Rate']?>" name=USD_Rate></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['USD_Rates']?>" name=USD_Rates></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_MYR?></td>
    <td><?php echo $row['MYR']?></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['MYR_Rate']?>" name=MYR_Rate></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['MYR_Rates']?>" name=MYR_Rates></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_SGD?></td>
    <td><?php echo $row['SGD']?></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['SGD_Rate']?>" name=SGD_Rate></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['SGD_Rates']?>" name=SGD_Rates></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_THB?></td>
    <td><?php echo $row['THB']?></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['THB_Rate']?>" name=THB_Rate></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['THB_Rates']?>" name=THB_Rates></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_GBP?></td>
    <td><?php echo $row['GBP']?></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['GBP_Rate']?>" name=GBP_Rate></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['GBP_Rates']?>" name=GBP_Rates></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_JPY?></td>
    <td><?php echo $row['JPY']?></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['JPY_Rate']?>" name=JPY_Rate></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['JPY_Rates']?>" name=JPY_Rates></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_EUR?></td>
    <td><?php echo $row['EUR']?></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['EUR_Rate']?>" name=EUR_Rate></td>
    <td><input class=za_text  maxLength=11 size=5 value="<?php echo $row['EUR_Rates']?>" name=EUR_Rates></td>
  </tr>
  <tr  class="m_cen" > 
    <td colspan="4"><input class=za_button type=submit value="确定" name=show_ok></td>
	<input type=hidden value="Currency" name=type>
    </tr>
  </form>
</table>
</div>
</body>
</html>