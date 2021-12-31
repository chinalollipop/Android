<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(__FILE__)));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/define_function.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../include/config.inc.php");
    require_once("../../../../common/sportCenterData.php");
    require ("../include/define_function.php");
    require_once("../include/address.mem.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}

$date=date('Y-m-d',time()-$time*60*60);
$co=0;
$sqlft="update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set  Type='FT' where `Type`='FU' and `M_Date` <='$date'";
$result = mysqli_query($dbCenterMasterDbLink ,$sqlft);
$co=mysqli_affected_rows($dbCenterMasterDbLink);

?>
<html>
<head>
<title>足球早盘转今日赛事</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<script> 
var limit="10" 
if (document.images){ 
	var parselimit=limit
} 
function beginrefresh(){ 
if (!document.images) 
	return 
if (parselimit==1) 
	window.location.reload() 
else{ 
	parselimit-=1 
	curmin=Math.floor(parselimit) 
	if (curmin!=0) 
		curtime=curmin+" 秒后自动更新本页！" 
	else 
		curtime=cursec+" 秒后自动更新本页！" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 
window.onload=beginrefresh 

</script>
<body>
<table width="300" height="300" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="300" height="300" align="center"><?php echo $datetime?><br><br><font color="#FFFFFF"><span style="background-color: #FF0000">修正<?php echo $co?></span></font><br><br><span id="timeinfo"></span><br><br>
      <input type=button name=button value="足球指数校验" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
