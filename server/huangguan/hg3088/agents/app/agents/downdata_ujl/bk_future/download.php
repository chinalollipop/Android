<?php
require_once("../../include/config.inc.php");
require_once("../../include/curl_http.php");
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>篮球早餐接收</title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<body>
<script>
    var limit="6:00";
    if (document.images){
        var parselimit=limit.split(":");
        parselimit=parselimit[0]*60+parselimit[1]*1;
    }
    function beginrefresh(){
        if (!document.images)
            return;
        if (parselimit==1)
            window.location.reload();
        else{
            parselimit-=1;
            curmin=Math.floor(parselimit/60);
            cursec=parselimit%60;
            if (curmin!=0)
                curtime=curmin+"分"+cursec+"秒后自动登陆！";
            else
                curtime=cursec+"秒后自动登陆！";
            setTimeout("beginrefresh()",1000);
        }
    }
    window.onload=beginrefresh;
</script>
<table width="300" height="140"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" valign="top"> 
      <iframe width=100 height=70 src='BK_ZC_TW.php' frameborder=0 scrolling="no"></iframe>
    </td>
  </tr>
</table>
</body>
</html>