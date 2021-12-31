<?php
set_time_limit(0);
$timeStart = microtime(true);
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("app/agents/include/config.inc.php");

$memberArr = $agentsArr = $systemArr = array();
$memberNum = $agentsNum = $systemNum = 0;

if(LOGIN_ENCRYPTIONCODE=='DX70887'){
	$idLimit="where id>50000 and id<51000";
}else{
	$idLimit="where id>80000 and id<81000";
}

$memberSql = "select ID,UserName,PassWord from ".DBPREFIX.MEMBERTABLE." ".$idLimit;
$memberRes = mysqli_query($dbLink,$memberSql);

while($rowM = mysqli_fetch_assoc($memberRes)){
		$update= "UPDATE ".DBPREFIX.MEMBERTABLE." SET PassWord='".passwordEncryption("demo1178",$rowM['UserName'])."' WHERE ID=".$rowM['ID']."";
		if(mysqli_query($dbMasterLink,$update)){
			$memberNum=$memberNum+1;
		}else{
			$memberArr[]=$rowM['UserName'];
		}
}

echo "试玩会员密码更新成功{$memberNum}\n\r";
echo "Error";
echo "\n\r";
echo "<pre>";
var_dump($memberArr);







