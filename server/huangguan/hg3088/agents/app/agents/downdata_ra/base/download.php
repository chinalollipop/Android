<?php
require ("../../include/config.inc.php");
require ("../../include/curl_http.php");

require_once("../../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$mysql = "select * from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$site=$row['SunUrl'];
$open=$row['opentype'];
$uid =$row['passuid'];
$uid_tw =$row['passuid_tw'];
$uid_en =$row['passuid_en'];

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>棒球接收</title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<body>
<script> 

var limit="6:00" 
if (document.images){ 
	var parselimit=limit.split(":") 
	parselimit=parselimit[0]*60+parselimit[1]*1 
} 
function beginrefresh(){ 
	if (!document.images) 
		return 
	if (parselimit==1) 
		window.location.reload() 
	else{ 
		parselimit-=1 
		curmin=Math.floor(parselimit/60) 
		cursec=parselimit%60 
		if (curmin!=0) 
			curtime=curmin+"分"+cursec+"秒后自动登陆！" 
		else 
			curtime=cursec+"秒后自动登陆！" 
		//	timeinfo.innerText=curtime 
			setTimeout("beginrefresh()",1000) 
	} 
} 
window.onload=beginrefresh 

</script>
<table width="330" height="88"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="75" height="88" valign="top"> 
      <iframe width=110 height=110 src='bs_r.php?uid=<?php echo $uid?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_r']?>' frameborder=0 scrolling="no"></iframe> 
    </td>
    <td width="75" height="88" valign="top"> 
      <iframe width=110 height=110 src='bs_r_tw.php?uid=<?php echo $uid_tw?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_r']?>' frameborder=0 scrolling="no"></iframe> 
    </td>
    <td width="75" height="88" valign="top">
	  <iframe width=110 height=110 src='bs_r_en.php?uid=<?php echo $uid_en?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_r']?>' frameborder=0 scrolling="no"></iframe> 
    </td>
	<td width="75" height="88" valign="top"> 
      <iframe width=110 height=110 src='bs_hr_tw.php?uid=<?php echo $uid_tw?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_hr']?>' frameborder=0 scrolling="no"></iframe> 
    </td>
  
  </tr>
  <tr> 
    <td width="75" height="88" valign="top"> 
      <iframe width=110 height=110 src='bs_re.php?uid=<?php echo $uid?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_re']?>' frameborder=0 scrolling="no"></iframe> 
    </td>
    <td width="75" height="88" valign="top"> 
      <iframe width=110 height=110 src='bs_re_tw.php?uid=<?php echo $uid_tw?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_re']?>' frameborder=0 scrolling="no"></iframe> 
    </td>
    <td width="75" height="88" valign="top">
	  <iframe width=110 height=110 src='bs_re_en.php?uid=<?php echo $uid_en?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_re']?>' frameborder=0 scrolling="no"></iframe> 
    </td>
    <td width="75" height="88" valign="top"> 
      <iframe width=110 height=110 src='bs_pd_tw.php?uid=<?php echo $uid_tw?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_pd']?>' frameborder=0 scrolling="no"></iframe> 
    </td>	
  </tr>
  <tr> 
    <td width="75" height="88" valign="top"> 
      <iframe width=110 height=110 src='bs_t_tw.php?uid=<?php echo $uid_tw?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_t']?>' frameborder=0 scrolling="no"></iframe> 
    </td>
    <td width="75" height="88" valign="top">
	  <iframe width=110 height=110 src='bs_p_tw.php?uid=<?php echo $uid_tw?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_p']?>' frameborder=0 scrolling="no"></iframe> 
    </td>
 	<td width="75" height="88" valign="top"> 
      <iframe width=110 height=110 src='bs_pr_tw.php?uid=<?php echo $uid_tw?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_pr']?>' frameborder=0 scrolling="no"></iframe> 
    </td>
  </tr> 
</table>
</body>
</html>
