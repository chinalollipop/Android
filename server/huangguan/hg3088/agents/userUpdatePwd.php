<?php
set_time_limit(0);
$timeStart = microtime(true);
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("app/agents/include/config.inc.php");

function passwordEncrypUpdate($password,$username){
	$passwordEncryptionSTR = md5(md5($password.sha1("DX70887")).strtolower(trim($username)));
	return $passwordEncryptionSTR;
}

$memberArr = $agentsArr = $systemArr = array();
$memberNum = $agentsNum = $systemNum = 0;

$memberSql = "select ID,UserName,PassWord from ".DBPREFIX.MEMBERTABLE."";
$agentsSql = "select ID,UserName,PassWord from ".DBPREFIX."web_agents_data";
$systemSql = "select ID,UserName,PassWord from ".DBPREFIX."web_system_data";

$memberRes = mysqli_query($dbLink,$memberSql);
while($rowM = mysqli_fetch_assoc($memberRes)){
		$update= "UPDATE ".DBPREFIX.MEMBERTABLE." SET PassWord='".passwordEncrypUpdate($rowM['PassWord'],$rowM['UserName'])."' WHERE ID=".$rowM['ID']."";
		if(mysqli_query($dbMasterLink,$update)){
			$memberNum=$memberNum+1;
		}else{
			$memberArr[]=$rowM['UserName'];
		}
}

echo "会员成功导入{$memberNum}个,";
if(count($memberArr)>0){
	echo implode(',',$memberArr).'导入失败\n\r\n\r';
}else{
	echo "全部成功导入\n\r\n\r";
}

$agentsRes = mysqli_query($dbLink,$agentsSql);
while($rowM = mysqli_fetch_assoc($agentsRes)){
		$update= "UPDATE ".DBPREFIX."web_agents_data SET PassWord='".passwordEncrypUpdate($rowM['PassWord'],$rowM['UserName'])."' WHERE ID=".$rowM['ID']."";
		if(mysqli_query($dbMasterLink,$update)){
			$agentsNum=$agentsNum+1; 
		}else{
			$agentsArr[]=$rowM['UserName'];
		}
}

echo "代理成功导入{$agentsNum}个,";
if(count($agentsArr)>0){
	echo implode(',',$agentsArr).'导入失败\n\r\n\r';
}else{
	echo "全部成功导入\n\r\n\r";
}

$systemRes = mysqli_query($dbLink,$systemSql);
while($rowM = mysqli_fetch_assoc($systemRes)){
		$update= "UPDATE ".DBPREFIX."web_system_data SET PassWord='".passwordEncrypUpdate($rowM['PassWord'],$rowM['UserName'])."' WHERE ID=".$rowM['ID']."";
		if(mysqli_query($dbMasterLink,$update)){
			$systemNum=$systemNum+1; 
		}else{
			$systemArr[]=$rowM['UserName'];
		}
}


echo "管理员成功导入{$systemNum}个,";
if(count($systemArr)>0){
	echo implode(',',$systemArr).'导入失败\n\r\n\r';
}else{
	echo "全部成功导入\n\r\n\r";
}


