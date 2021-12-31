<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
require ("./include/config.inc.php");
require ("./include/define_function_list.inc.php");
require ("./include/curl_http.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
require ("./include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$admin=$_SESSION['Admin'];

$mysql = "select datasite,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$site=$row['datasite'];
switch($langx)	{
case "zh-cn":
	$suid=$row['uid_tw'];
	break;
case "zh-cn":
	$suid=$row['uid'];
	break;
case "en-us":
	$suid=$row['uid_en'];
	break;
case "th-tis":
	$suid=$row['uid_en'];
	break;
}

	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$site."/app/member/FT_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	//http://hg1088.com/app/member/scroll_history.php?uid=14940627m6323264l22938231&langx=zh-cn
	$html_data=$curl->fetch_url("".$site."/app/member/scroll_history.php?uid=$suid&langx=$langx");
//echo "".$site."/app/member/scroll_history.php?uid=$suid&langx=$langx&mtype=3";
	echo $html_data;exit;
?>
<html>
<head>
<title>History</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/member/mem_body<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
<!--
#MFT #box { width:480px;}
#MFT .news { white-space: normal!important; color:#300; text-align:left; padding:2px 4px;}
-->
</style>
</head>

<body id="MFT">
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td class="top">
  	  <h1><em><?php echo $News_History?></em></h1>
	</td>
  </tr>
  <tr>
    <td class="mem">
      <table border="0" cellspacing="1" cellpadding="0" class="game">
        <tr> 
          <th><?php echo $Scr_Number?></th>
          <th><?php echo $Scr_Time?></th>
          <th><?php echo $Scr_News?></th>
        </tr>
<?php
$icount=1;
$sql="select Date,$message as Message from ".DBPREFIX."web_marquee_data where  Level='MEM' order by ID desc limit 0,25";
$result = mysqli_query($dbLink,$sql);
while ($row = mysqli_fetch_assoc($result)){
$time=strtotime($row['Date']);
$times=date("y-m-d",$time);
?>
		<tr class="b_rig" style="display: block">
          <td align="center"><?php echo $icount?></td>
          <td align="center"><?php echo $times?></td>
          <td class="news"><?php echo trim($row['Message'])?></td>
        </tr>
<?php
$icount=$icount+1; 
}
?>
      </table> 
	</td>
  </tr>
  <tr><td id="foot"><b>&nbsp;</b></td></tr>
</table>

</body>
</html>
