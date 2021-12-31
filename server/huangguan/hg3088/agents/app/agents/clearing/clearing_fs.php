<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(__FILE__)));
    require(CONFIG_DIR."/include/config.inc.php");
    require(CONFIG_DIR."/include/define_function.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../include/config.inc.php");
    require ("../include/define_function.php");
    require_once("../include/address.mem.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}

$mysql = "select * from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$settime=$row['udp_ft_score'];
$time=$row['udp_ft_results'];
$date=date('Y-m-d',time()-$time*60*60);
$mDate=date('Y-m-d',time()-$time*60*60);
$mmysql = "select * from `".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."` where date_format(M_Start,'%Y-%m-%d')='$mDate'  and Score=0 order by M_Start,MID";

$mresult1 = mysqli_query($dbLink,$mmysql);
while ($mrow=mysqli_fetch_assoc($mresult1)){
	   $gid=$mrow['MID'];
       $Mtype=$mrow['Gid'];
	   $mysql="select ID,Active,M_Name,LineType,OpenType,BetTime,OddsType,ShowType,Mtype,Gwin,BetType,M_Place,M_Rate,Middle,BetScore,A_Point,B_Point,C_Point,D_Point,Pay_Type,Checked from ".DBPREFIX."web_report_data where MID='$gid' and Mtype=$Mtype and Active=7 and Cancel=0 order by LineType";

	$result = mysqli_query($dbLink, $mysql);
	while ($row = mysqli_fetch_assoc($result)){
		$mtype=$row['Mtype'];
		$id=$row['ID'];
		$user=$row['M_Name'];
		$graded=$mrow['win'];
		switch ($graded){
		case 1:
			$g_res=$row['Gwin'];
			break;
		case -1:
			$g_res=-$row['BetScore'];
			break;
		default:
			$g_res=0;
			break;
		}
		//echo $row['BetScore'];
		$vgold=abs($graded)*$row['BetScore'];
		$betscore=$row['BetScore'];
		$d_point=$row['D_Point']/100;
		$c_point=$row['C_Point']/100;
		$b_point=$row['B_Point']/100;
		$a_point=$row['A_Point']/100;
		
		$members=$g_res;//和会员结帐的金额
		$agents=$g_res*(1-$d_point);//上缴总代理结帐的金额
		$world=$g_res*(1-$c_point-$d_point);//上缴股东结帐
		if (1-$b_point-$c_point-$d_point!=0){
			$corprator=$g_res*(1-$b_point-$c_point-$d_point);//上缴公司结帐
		}else{
			$corprator=$g_res*($b_point+$a_point);//和公司结帐
		}
		$super=$g_res*$a_point;//和公司结帐
		$agent=$g_res*1;//公司退水帐目
		

	       if ($row['Checked']==0){	
		       if ($row['Pay_Type']==1){
				   $cash=$row['BetScore']+$members;
				   $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$cash where UserName='$user'";
				  // echo  $mysql."<br>";
				   mysqli_query($dbMasterLink,$mysql) or die ("error!");

		       }
	  	   }
	  	   error_log($row['ID']."_X\n\r",3,'./error_log.txt');
		   $sql="update ".DBPREFIX."web_report_data set VGOLD='$vgold',M_Result='$members',D_Result='$agents',C_Result='$world',B_Result='$corprator',A_Result='$super',T_Result='$agent',Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
		   mysqli_query($dbMasterLink,$sql) or die ("error!");
		   //echo '<font color=white>'.$row['BetTime'].'</font><br>'.$row['M_Name'].'--<font color=red>('.$members.')</font><br>';	
	}
	$mysql="update ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." set Score=1 where MID='$gid'";
	mysqli_query($dbMasterLink,$mysql) or die ("error!!");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>足球结算</title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<script> 

var limit="<?php echo $settime?>" 
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
		curtime=curmin+"秒后自动获取最新数据！" 
	else 
		curtime=cursec+"秒后自动获取最新数据！" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 
window.onload=beginrefresh 

</script>
<body>
<table width="220" height="190" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="220" height="190" align="center"><?php echo $date?><br><br><span id="timeinfo"></span><br>
	<input type=button name=button value="足球冠军刷新" onClick="window.location.reload()"></td>  
  </tr>
</table>
</body>
</html>
