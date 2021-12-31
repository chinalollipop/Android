<?php
	require_once("../include/address.mem.php");
	/*判断IP是否在白名单*/
	if(CHECK_IP_SWITCH) {
		if(!checkip()) {
			exit('登录失败!!\\n未被授权访问的IP!!');
		}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>今日数据刷新</title>
<script> 

var limit="10:00" 
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
</head>
<!-- 
<frameset rows="241,234" cols="*">
  <frameset rows="*" cols="329,330,322">
    <frame src="ft/download.php" />
    <frame src="op/download.php" />
    <frame src="bs/download.php">
  </frameset>
   <frameset rows="*" cols="329,330,322">
	  <frame src="bk/download.php" />
      <frame src="tn/download.php" />
      <frame src="vb/download.php" />
   </frameset>
</frameset>
-->
<frameset rows="241" cols="*">
  <frameset rows="*" cols="329,329">
    <frame src="ft/download.php" />
    <frame src="bk/download.php" />
  </frameset>
</frameset>
<noframes>
<body>
</body>
</noframes></html>
