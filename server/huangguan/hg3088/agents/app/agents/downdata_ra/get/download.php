<?php

require_once("../../include/address.mem.php");
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
<title>UID断水检测</title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<body>
<table width="200" height="210"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
	<td width="200" height="70" valign="top"> 
      <iframe width=200 height=70 src='get_tw.php' frameborder=0 scrolling="no"></iframe> 
    </td>
  <tr>
  </tr>
    <td width="200" height="70" valign="top"> 
      <iframe width=200 height=70 src='get_cn.php' frameborder=0 scrolling="no"></iframe> 
    </td>
  <tr>
  </tr>
    <td width="200" height="70" valign="top">
	  <iframe width=200 height=70 src='get_en.php' frameborder=0 scrolling="no"></iframe> 
    </td>
  </tr>
</table>
</body>
</html>
