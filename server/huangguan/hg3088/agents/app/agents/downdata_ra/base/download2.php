<?php
require"../../../../include/conn_ft8.php";
require"../../../../include/function.php";
require ("../../../../include/curl_http.php");

require_once("../../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$mysql = "select * from web_system";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$site=$row['passhttp'];
$open=$row['opentype'];
$uid =$row['passuid'];
$uid_tw =$row['passuid_tw'];
$uid_en =$row['passuid_en'];

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>棒球接收</title>
<link href="style.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
}
-->
</style>
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
<iframe width=1800 height=800 src='bs_r_tw2.php?uid=<?php echo $uid_tw?>&sitename=<?php echo $site?>&opentype=<?php echo $open?>&settime=<?php echo $row['udp_ft_r']?>' frameborder=0 scrolling="no"></iframe>

</body>
</html>
