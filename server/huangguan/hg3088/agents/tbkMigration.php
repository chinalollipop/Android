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
$insertHeader="INSERT INTO ".DBPREFIX.MEMBERTABLE."(`UserName`,`LoginName`,`Alias`,`PassWord`,`Phone`,`Money`,`pay_class`,`Pay_Type`,`CurType`,`WinLossCredit`,`E_Mail`,`Points`,`AddDate`,`OpenType`,`admin`,`Agents`,`World`,`Corprator`,`SUPER`)VALUES";
$file = fopen('f651.csv','r');
while($data = fgetcsv($file)){
        $UserName = $data[1];
		$LoginName = $data[1];
        $Alias = $data[2];
        $PassWord =passwordEncryption('123edc',$data[1]);
        $Phone = $data[3];
        $Money = $data[4];
        $pay_class = 'a';
        $WinLossCredit = $data[5]*-1;
        $E_Mail = $data[7];
        $Points = $data[8];
		$AddDate = $data[9];
        $OpenType = 'C';
        $admin = 'admin';
		$Agents = $data[10];
        $World = 'cdm323';
        $Corprator = 'bdm223';
        $SUPER  = 'adm123';
        $pay_Type = 1;
        $CurType = 'RMB';

		$insertContent[]="('$UserName','$LoginName','$Alias','$PassWord','$Phone','$Money','$pay_class',$pay_Type,'$CurType','$WinLossCredit','$E_Mail','$Points','$AddDate','$OpenType','$admin','$Agents','$World','$Corprator','$SUPER')";
	if($i==99){
		$insertSql=$insertHeader.implode(',',$insertContent).";";
		/*echo '<br/>';
		echo $insertSql;
        echo '<br/>';
        die();*/
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
