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
$loginname=$username=$_SESSION['UserName'];

require ("../include/traditional.$langx.inc.php");


$mysql = "select * from ".DBPREFIX."web_type_class  where ID='1'";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);

?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<body >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <form name="LAYOUTFORM" action="" method=POST >
    <input type="HIDDEN" name="active" value="0">
    <tr> 
      <td class="m_tline"> 
        <table border="0" cellspacing="0" cellpadding="0" >
          <tr> 
            <td width="85" >&nbsp;&nbsp;<?php echo $Mnu_current?></td>
          </tr>
        </table>
      </td>

    </tr>
    <tr> 
      <td colspan="2" height="4"></td>
    </tr>
  </form>
</table>
  <table width="600" border="0" cellspacing="1" cellpadding="0"  bgcolor="4B8E6F" class="m_tab">
  <tr class="m_title_set"> 
    <td width="148" ><?php echo $Mem_currency?></td>
    <td width="148"><?php echo $Mem_code?></td>
    <td width="150"><?php echo $Mem_curradio?></td>
    <td width="149"><?php echo $Mem_radioset?></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_RMB?></td>
    <td><?php echo $row['RMB']?></td>
    <td><?php echo $row['RMB_Rate']?></td>
    <td><?php echo $row['RMB_Rates']?></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_HKD?></td>
    <td><?php echo $row['HKD']?></td>
    <td><?php echo $row['HKD_Rate']?></td>
    <td><?php echo $row['HKD_Rates']?></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_USD?></td>
    <td><?php echo $row['USD']?></td>
    <td><?php echo $row['USD_Rate']?></td>
    <td><?php echo $row['USD_Rates']?></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_MYR?></td>
    <td><?php echo $row['MYR']?></td>
    <td><?php echo $row['MYR_Rate']?></td>
    <td><?php echo $row['MYR_Rates']?></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_SGD?></td>
    <td><?php echo $row['SGD']?></td>
    <td><?php echo $row['SGD_Rate']?></td>
    <td><?php echo $row['SGD_Rates']?></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_THB?></td>
    <td><?php echo $row['THB']?></td>
    <td><?php echo $row['THB_Rate']?></td>
    <td><?php echo $row['THB_Rates']?></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_GBP?></td>
    <td><?php echo $row['GBP']?></td>
    <td><?php echo $row['GBP_Rate']?></td>
    <td><?php echo $row['GBP_Rates']?></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_JPY?></td>
    <td><?php echo $row['JPY']?></td>
    <td><?php echo $row['JPY_Rate']?></td>
    <td><?php echo $row['JPY_Rates']?></td>
  </tr>
  <tr  class="m_cen" > 
    <td><?php echo $Mem_radio_EUR?></td>
    <td><?php echo $row['EUR']?></td>
    <td><?php echo $row['EUR_Rate']?></td>
    <td><?php echo $row['EUR_Rates']?></td>
  </tr>
</table>
</body>
</html>
<?php
$ip_addr = get_ip();
$loginfo='查询币值';
$mysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$username',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
mysqli_query($dbMasterLink,$mysql);
?>
