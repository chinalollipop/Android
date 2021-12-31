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
<title>滚球注单审核</title>
</head>
<frameset rows="241" cols="*">
  <frameset rows="*" cols="333,333,333">
    <frame src="ftre.php" />
    <frame src="fu2ft2.php" />
    <frame src="bu2bk.php" />
</frameset>
<noframes>
<body>
</body>
</noframes></html>
