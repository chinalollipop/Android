<?php
require ("../include/config.inc.php");

require_once("../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$mysql = "select * from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$optiem=$row['udp_op_time'];
$tiemop=$row['udp_time_op'];
$time=date('Y-m-d H:i:s',time()-1*$optiem);
$times=date('Y-m-d H:i:s',time()-1*$tiemop);
$datetime=date('Y-m-d H:i:s');
$date=date('Y-m-d');
$sql = "update ".DBPREFIX."web_report_data set Danger=0,updateTime='".date('Y-m-d H:i:s',time())."' where M_Date='$date' and BetTime<'$times' and Active=5 and Danger=1 and (LineType=9 or LineType=10) and M_Result='' and Cancel!=1";
mysqli_query($dbMasterLink,$sql);
//mysqli_query($dbLink,$sql) or die ("操作失败!");

$sqls = "select ID,MID,Middle,BetScore,M_Name,M_Result,MB_Ball,TG_Ball from ".DBPREFIX."web_report_data  where M_Date='$date' and BetTime<'$time' and Active=5 and Danger=1 and (LineType=9 or LineType=10) and M_Result='' and Cancel!=1";
//$results = mysqli_query($dbLink,$sqls) or die ("注单显示失败!!");
$results = mysqli_query($dbLink,$sqls);
while ($rows = mysqli_fetch_assoc ($results)){
$id=$rows['ID'];
$mid=$rows['MID'];
$username=$rows['M_Name'];
$betscore=$rows['BetScore'];
$m_result=$rows['M_Result'];
$mb_ball=$rows['MB_Ball'];
$tg_ball=$rows['TG_Ball'];

$sqlf = "select MB_Ball,TG_Ball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where Type='VB' and MID='$mid'";
$resultf = mysqli_query($dbLink,$sqlf);
$rowf = mysqli_fetch_assoc ($resultf);

$vb_mb_ball=$rowf['MB_Ball'];
$vb_tg_ball=$rowf['TG_Ball'];

if ($mb_ball==$vb_mb_ball and  $tg_ball==$vb_tg_ball){
    $ft_sql = "update ".DBPREFIX."web_report_data set Danger=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
    mysqli_query($dbMasterLink,$ft_sql) or die ("通过操作失败!"); 
}else{
    $ft_sql = "update ".DBPREFIX."web_report_data set VGOLD=0,M_Result=0,A_Result=0,B_Result=0,C_Result=0,D_Result=0,T_Result=0,Cancel=1,MB_Ball='$vb_mb_ball',TG_Ball='$vb_tg_ball',Confirmed=-18,Danger=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
    mysqli_query($dbMasterLink,$ft_sql) or die ("进球注销操作失败!!!");
    if ($m_result==''){
        $u_sql = "update ".DBPREFIX.MEMBERTABLE." SET Money=Money+$betscore where UserName='$username' and Pay_Type=1";
        mysqli_query($dbMasterLink,$u_sql) or die ("现金恢复操作失败!");
    }
}

}
?>
<html>
<head>
<title>排球滚球注单审核</title>
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
    <td width="300" height="300" align="center"><?php echo $datetime?><br><br><font color="#FFFFFF"><span style="background-color: #FF0000">排球走地注单确认中，请勿关闭窗口...</span></font><br><br><span id="timeinfo"></span><br><br>
      <input type=button name=button value="排球更新" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
