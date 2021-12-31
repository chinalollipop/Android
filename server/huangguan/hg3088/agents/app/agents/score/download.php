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
<title>体育比分</title>
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
<frameset rows="237,238" cols="*">
  <frameset rows="*" cols="220,220,220,220">
    <frame src="score_ft.php" />
    <frame src="score_bk.php" />
    <frame src="score_bs.php">
    <frame src="score_ft_h.php">
  </frameset>
   <frameset rows="*" cols="220,220,220,220">
	  <frame src="score_tn.php" />
      <frame src="score_vb.php" />
      <frame src="score_op.php" />
      <frame src="../clearing/clearing_ft.php">
   </frameset>
</frameset>
 -->
 <frameset rows="*" cols="*">
  <frameset rows="*" cols="220,220,220">
    <frame src="score_ft.php" />
    <frame src="score_bk.php" />
    <!--<frame src="score_bs.php">
    <frame src="../clearing/clearing_ft.php">-->
    <frame src="score_ft_h.php">
  </frameset>
  <!-- 
   <frameset rows="*" cols="220,220,220,220">
	  <frame src="score_tn.php" />
      <frame src="score_vb.php" />
      <frame src="score_op.php" />
      <frame src="../clearing/clearing_ft.php">
   </frameset>
    -->
</frameset>
<noframes>
<body>
</body>
</noframes></html>
