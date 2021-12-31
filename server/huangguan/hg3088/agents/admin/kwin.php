<?php

require_once dirname(__FILE__).'/conjunction.php';

require_once dirname(__FILE__).'/config.php';


$result = mysql_query("Select *  from ka_tan where kithe='".$kithe."' and checked=1");  
while($rs = mysql_fetch_array($result)){
	//print_r($rs);
	if($rs['bm']==1){		//会员中奖
		$z_user=($rs['sum_m']*$rs['rate']-$rs['sum_m']+$rs['sum_m']*abs($rs['user_ds'])/100)+$rs['sum_m'];
		//中奖退水
		$z_user+=$rs['sum_m']*abs($rs['user_ds'])/100;
				 //$rs['sum_m']*$rs['rate']-$rs['sum_m']+$rs['sum_m']*abs($rs['user_ds'])/100
	}else{					//未中奖退水
		$z_user=$rs['sum_m']*abs($rs['user_ds'])/100;
		   //echo $rs['sum_m']*abs($rs['user_ds'])/100
	}
	$money[$rs['username']]+=$z_user;
	if($rs['username']=='jsh811'){
	echo $rs['username']."-".$rs['class1']."-".$rs['class2']."-".$rs['class3']."-".$rs['sum_m']."-".$rs['user_ds']."-".$rs['rate']."-".$z_user."<br>";
	}
	//mysql_query("update ka_tan set checked=1 where id='".$rs['id']."'");
	//echo "update ".DBPREFIX.MEMBERTABLE." set Credit=Credit+".$z_user.",Money=Money+".$z_user." where UserName='".$rs['username']."'"."<br>";
}
print_r($money);
?>