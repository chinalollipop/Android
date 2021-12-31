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

$i=$j=$k=$v=0;
$insertSql=$errorArr=array();
$insertContent=array();
$insertHeader="INSERT INTO ".DBPREFIX."web_agents_data(`UserName`,`LoginName`,`PassWord`,`Alias`,Level,`Status`,`AddDate`, `LoginIP`, `Bank_Address`, `Bank_Account`, `Bank_Name`,Competence)VALUES";
$file = fopen('web_agents_data_tmp.txt','r');
while($dataOri = fgetcsv($file)){
	$UserName=dealStr($dataOri[0]);
	$LoginName=dealStr($dataOri[0]);
	$PassWord=passwordEncryption(dealStr($dataOri[1]),$UserName);
	$Alias=dealStr($dataOri[2]);
	$Level='D';
	$Status=0;
	$AddDate=dealStr($dataOri[3]);
	$LoginIP=dealStr($dataOri[4]);
	$Bank_Address=dealStr($dataOri[5]);
	$Bank_Account=dealStr($dataOri[6]); 
	$Bank_Name=dealStr($dataOri[7]);
	$Competence='0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,1,1,0,1,';
	$insertContent[]="('$UserName','$LoginName','$PassWord','$Alias','$Level','$Status','$AddDate','$LoginIP','$Bank_Address','$Bank_Account','$Bank_Name','$Competence')";	
	
	if($i==99){
		$insertSql=$insertHeader.implode(',',$insertContent).";";
		$insertSqlRes = mysqli_query($dbMasterLink,$insertSql);
		if($insertSqlRes){
			$v += 100;
		}else{
			$errorArr[]=$insertSql;
		}
		unset($insertContent);
		$i=0;
	}else{
		$i++;
	}
	$k++;
}
fclose($file);

function dealStr($str){
	$str=str_replace('(','',$str);
	$str=str_replace(')','',$str);
	$str=str_replace('\'','',$str);
	return trim($str);
}

if(count($insertContent)>0){
	$insertSql=$insertHeader.implode(',',$insertContent).";";
	$insertSqlRes = mysqli_query($dbMasterLink,$insertSql);
	if($insertSqlRes){
		$v += count($insertContent);
	}else{
		$errorArr[]=$insertSql;
	}
}

$timeEnd = microtime(true);
echo "running ".round($timeEnd-$timeStart,4)." sec\n\r";
echo "Now memory_get_usage ".memory_get_usage()/(1024*1024)."M \n\r";
echo "total ".$k."\n\r";
echo "success ".$v."\n\r";
echo "failure\n\r";

for($f=0;$f<count($errorArr);$f++){
	echo "\n\r\n\r";
	echo "<pre>";
	print_r($errorSql[$f]);
	echo "\n\r\n\r";
}
