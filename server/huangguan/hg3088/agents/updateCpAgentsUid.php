<?php

ini_set('display_errors','On');

set_time_limit(0);
$timeStart = microtime(true);
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("app/agents/include/config.inc.php");

$userCParray=array();
$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
$userCP = mysqli_query($cpMasterDbLink,"SELECT hguid FROM `gxfcy_user` WHERE id>2765");
while($userCProw=mysqli_fetch_assoc($userCP)){
	$userCParray[]=$userCProw['hguid'];
}
$userCPStr=implode(',',$userCParray);


$dateBasic=array();
$dateBasicSql="SELECT m.id,m.username,m.Agents,a.id AS agentid FROM ".DBPREFIX.MEMBERTABLE." AS m LEFT JOIN ".DBPREFIX."web_agents_data AS a ON m.Agents=a.username WHERE m.ID in(".$userCPStr.") AND m.Agents!='' AND a.id!='' ";
$dateBasicRes = mysqli_query($dbLink,$dateBasicSql);
$i=0;
while($dateBasicRow=mysqli_fetch_assoc($dateBasicRes)){
	$i=$i+1;
	$userUpdateSql='';
	$userUpdateSql="UPDATE `gxfcy_user` SET hg_agent_uid=".$dateBasicRow['agentid']." WHERE hguid= ".$dateBasicRow['id']." AND username='".$dateBasicRow['username']."'";
	echo $userUpdateSql;
	echo "\n\r";
	$userUpdateResult = mysqli_query($cpMasterDbLink,$userUpdateSql);
	
	if(!$userUpdateResult){
		$userUpdateResultError[]=$dateBasicRow;	
	}
}

echo 'count'.$i."\n\r";
echo 'Error:';
echo "\n\r";
var_dump($userUpdateResultError);
echo "\n\r";















