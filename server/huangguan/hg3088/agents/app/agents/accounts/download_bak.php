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

<frameset rows="241,234" cols="*">
  <frameset rows="*" cols="329,330,322">
    <frame src="ftre.php" />
    <frame src="bsre.php" />
    <frame src="tnre.php">
  </frameset>
  
   <frameset rows="*" cols="329,330,322">
	  <frame src="vbre.php" />
      <frame src="opre.php" />
      <frame src="" />
   </frameset>
</frameset>

<noframes>
<body>
</body>
</noframes></html>
