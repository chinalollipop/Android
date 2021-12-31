<?php
session_start();
include "../../agents/include/address.mem.php";
require ("../../agents//include/config.inc.php");
include_once ("../include/redis.php");
checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST["uid"];
$gid=$_REQUEST['gid'];
$set_acc=$_REQUEST['set_acc'];
$username = $_SESSION['UserName'];
$sql="select Mtype,LineType,D_Point,count(*) as cou,sum(BetScore) as BetScore,ShowType,MB_MID,TG_MID FROM  `".DBPREFIX."web_report_data` where FIND_IN_SET($gid,MID)>0 and Agents='".$username."' ";
$result = mysqli_query($dbLink,$sql); 
$row=mysqli_fetch_assoc($result);
$n1c=0;
$n1s=0;
$h1c=0;
$h1s=0;
$c1c=0;
$c1s=0;
$c2c=0;
$c2s=0;
$h2c=0;
$h2s=0;
$c3c=0;
$c3s=0;
$h3c=0;
$h3s=0;
$n4c=0;
$n4s=0;
$e5c=0;
$o5s=0;
$h6c=0;
$h6s=0;
$n7c=0;
$n7s=0;
$n8c=0;
$n8s=0;
$n11c=0;
$n11s=0;
$h11c=0;
$h11s=0;
$c11c=0;
$c11s=0;
$c12c=0;
$c12s=0;
$h12c=0;
$h12s=0;
$c13c=0;
$c13s=0;
$h13c=0;
$h13s=0;
$n14c=0;
$n14s=0;
if ($set_account==1){
	$Point=$data['D_Point']/100;
}else{
	$Point=1;
}
switch ($data['LineType']){
	case "1":
		if ($data['Mtype']=='MH'){
			$h1c=$data['cou']+0;
			$h1s=$data['BetScore']*$Point+0;
		}else if($data['Mtype']=='MC'){
			$c1c=$data['cou']+0;
			$c1s=$data['BetScore']*$Point+0;
		}else if($data['Mtype']=='MN'){
			$n1c=$data['cou']+0;
			$n1s=$data['BetScore']*$Point+0;
		}
		break;
	case "2":
		if ($data['Mtype']=='RH'){
			$h2c=$data['cou']+0;
			$h2s=$data['BetScore']*$Point+0;
		}else if($data['Mtype']=='RC'){
			$c2c=$data['cou']+0;
			$c2s=$data['BetScore']*$Point+0;
		}			
		break;
	case "3":
		if ($data['Mtype']=='OUC'){
			$h3c=$data['cou']+0;
			$h3s=$data['BetScore']*$Point+0;
		}else if($data['Mtype']=='OUH'){
			$c3c=$data['cou']+0;
			$c3s=$data['BetScore']*$Point+0;
		}	
		break;		
}
?>
<script>
top.divFT = Array('<?php echo $gid?>','','','<?php echo $row['MB_MID']?>','<?php echo $row['TG_MID']?>','','','<?php echo $row['ShowType']?>','','','','','','0','0','0','0','','','','','0','0','0','0','','','','0','0','0','<?php echo $h1c?>','<?php echo $c1c?>','<?php echo $n1c?>','<?php echo $h1s?>','<?php echo $c1s?>','<?php echo $n1s?>','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','','','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','<?php echo $gid?>','','','','','Y','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
parent.show_one();
</script>