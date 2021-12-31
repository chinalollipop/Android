<?php
/**
* 定时任务脚本,每天中午14点,也就是美东时间凌晨2点,检查美东时间昨天的所有的已经有比分的赛事,是否有未结算的注单,如果有,进行下结算
*/

if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");	
}

define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/configUserCli.php";
require(CONFIG_DIR."/app/agents/include/define_function.php");

$redisObj = new Ciredis();
$midList=array();
$dateCheck=date("Y-m-d",strtotime("-1 day"));
$sql = "SELECT ID,m.`MID`,M_Result,b.cancel,b.checked,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,score";
$sql .=" FROM hgty78_web_report_data AS b LEFT JOIN `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` AS m ON b.`MID`= m.`MID`";
$sql .=" WHERE m.M_Date ='{$dateCheck}' AND m.cancel=0 AND b.M_Result='' AND b.checked=0";

$result = mysqli_query($dbLink,$sql);
while($row=mysqli_fetch_assoc($result)){
	if(in_array($row['LineType'],array(11,12,13,14,204,15,16,205,206,19,20,31,50))){
		if( trim($row['MB_Inball_HR'])==='' || trim($row['TG_Inball_HR'])==='' ||  trim($row['MB_Inball_HR'])<0 || trim($row['TG_Inball_HR'])<0 ) {
			continue;
		}
	}else{
		if( trim($row['MB_Inball'])==='' || trim($row['TG_Inball'])==='' ||  trim($row['MB_Inball'])<0 || trim($row['TG_Inball'])<0 ) {
			continue;	
		}
	}
	
	if(!in_array($row['MID'],$midList)){
		$midList[]=$row['MID'];
		$redisObj->pushMessage('MatchScorefinishList',$row['MID']);//加入派奖队列
	} 
}

?>
