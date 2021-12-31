<?php
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
$insertHeader="INSERT INTO ".DBPREFIX.MEMBERTABLE."(`UserName`,`LoginName`,`Money`,`Credit`,`AddDate`,`LoginIP`,`Agents`,`World`,`Address`,`PassWord`,`DepositTimes`,`WithdrawalTimes`,`Alias`,`Bank_Account`,`Bank_Address`)VALUES";
$file = fopen('web_member_data_tmp.txt','r');
while($data = fgetcsv($file)){
		$UserName = dealStr($data[1]);
		$LoginName = dealStr($data[1]);
		$Money = dealStr($data[2]);
		$AddDate = dealStr($data[3]);
		$LoginIP = dealStr($data[4]);
		$Agents = dealStr($data[5]);
		$World = dealStr($data[6]);
		$Address = dealStr($data[7]);
		$PassWord = dealStr($data[8]);
		$DepositTimes = dealStr($data[9]);
		$WithdrawalTime = dealStr($data[10]);
		$Credit = dealStr($data[11])-dealStr($data[12]);
		$Alias = dealStr($data[13]);
		$Bank_Account = dealStr($data[14]);
		$Bank_Address = dealStr($data[15]);
		$insertContent[]="('$UserName','$LoginName','$Money','$Credit','$AddDate','$LoginIP','$Agents','$World','$Address','$PassWord','$DepositTimes','$WithdrawalTime','$Alias','$Bank_Account','$Bank_Address')";	
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

for($f=0;$f<coun($errorArr);$f++){
	echo "\n\r\n\r";
	echo "<pre>";
	print_r($errorSql[$f]);
	echo "\n\r\n\r";
}
