<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
require ("../include/curl_http.php");
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$gid=$_REQUEST['gid'];
$type=$_REQUEST['type'];
$rtype=$_REQUEST['rtype'];
$wtype=$_REQUEST['wtype'];
$gunm=$_REQUEST['gnum'];
$odd_f_type=$_REQUEST['odd_f_type'];
$change=$_REQUEST['change'];
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
$sql = "select Money from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$credit=$row['Money'];

$pay_type=$_SESSION['Pay_Type'];
$memname=$_SESSION['UserName'];
$open=$_SESSION['OpenType'];

$GMAX_SINGLE= Ft_Scene ;
$GSINGLE_CREDIT= Ft_Bet ;
$GMIN_SINGLE= Ft_Bet_Min ;

if($change==1){
	$bet_title=$nobettitle;
}

if($gid%2==0){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DBPREFIX."match_sports` where `MID`='$gid' and Cancel!=1 and Open=1 and $mb_team!=''";
}elseif($gid%2==1){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DBPREFIX."match_sports` where `MID`=$gid-1 and Cancel!=1 and Open=1 and $mb_team!=''";
}
$result = mysqli_query($dbLink,$mysql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);

$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
$rowMore = mysqli_fetch_assoc($moreRes);
$couMore = mysqli_num_rows($moreRes);
if($cou==0 || $couMore==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit;
}

	$detailsArr = json_decode($rowMore['details'],true);
	$detailsData =$detailsArr[$gid];
	
	if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
		$ior_Rate = $detailsData["ior_".$rtype];
	}
	if(!$ior_Rate){
		echo attention("$Order_Odd_changed_please_bet_again",$uid,$langx);
		exit;
	}

	if ($row['M_Date']==date('Y-m-d')){
		$active=1;
		$class="OFT";
		$like=$Order_FT;
	}else{
		$active=11;
		$class="OFU";
		$like=$Order_FT.$Order_Early_Market;
	} 
	$M_League=$row['M_League'];  
	$MB_Team=$row["MB_Team"];
	$TG_Team=$row["TG_Team"];
	$MB_Team=filiter_team($MB_Team);
	switch ($rtype){ 
	case "RTSY": 
		$M_Place="是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_In_betting_order; 
		$linetype=115; 
		$gametype=$U_50;
		break; 
	case "RTSN": 
		$M_Place="不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_In_betting_order; 
		$linetype=115; 
		$gametype=$U_50;
		break; 
	case "RHTSY": 
		$M_Place="是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_In_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=215; 
		$gametype=$U_50."-".$U_00;
		break; 
	case "RHTSN": 
		$M_Place="不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_In_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=215; 
		$gametype=$U_50."-".$U_00;
		break; 
	case "RWMH1": 
		$M_Place=$MB_Team." - 净胜1球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=118; 
		$gametype=$U_53;
		break;	
	case "RWM0": 
		$M_Place=" 0 - 0 和局"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=118; 
		$gametype=$U_53;
		break;	
	case "RWMC1": 
		$M_Place=$TG_Team." - 净胜1球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=118; 
		$gametype=$U_53;
		break;
	case "RWMH2": 
		$M_Place=$MB_Team." - 净胜2球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=118; 
		$gametype=$U_53;
		break;	
	case "RWMN": 
		$M_Place="任何进球和局"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=118; 
		$gametype=$U_53;
		break;	
	case "RWMC2": 
		$M_Place=$TG_Team." - 净胜2球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=118; 
		$gametype=$U_53;
		break;	
	case "RWMH3": 
		$M_Place=$MB_Team." - 净胜3球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=118; 
		$gametype=$U_53;
		break;
	case "RWMC3": 
		$M_Place=$TG_Team." - 净胜3球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=118; 
		$gametype=$U_53;
		break;	
	case "RWMHOV": 
		$M_Place=$MB_Team." - 净胜4球或更多";
		$M_Rate=change_rate($open,$ior_Rate); 
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=118; 
		$gametype=$U_53;
		break;	
	case "RWMCOV": 
		$M_Place=$TG_Team." - 净胜4球或更多"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=118; 
		$gametype=$U_53;
		break;
	case "RDCHN": 
		$M_Place=$MB_Team." / "."和局";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=119; 
		$gametype=$U_54;
		break;	
	case "RDCCN": 
		$M_Place=$TG_Team." / "."和局";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=119; 
		$gametype=$U_54;
		break;	
	case "RDCHC": 
		$M_Place=$MB_Team." / ".$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=119; 
		$gametype=$U_54;
		break;
	case "RCSH": 
		$M_Place=$MB_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Clean_Sheets;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=120; 
		$gametype=$U_55;
		break;
	case "RCSC": 
		$M_Place=$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Clean_Sheets; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=120; 
		$gametype=$U_55;
		break;	
	case "RWNH": 
		$M_Place=$MB_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Clean_Sheets_Win; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=161; 
		$gametype=$U_56;
		break;	
	case "RWNC": 
		$M_Place=$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Clean_Sheets_Win;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=161; 
		$gametype=$U_56;
		break;
	case "RMUAHO":	//独赢 & 进球 大 / 小  A
		$M_Place=$MB_Team." & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUANO":	 
		$M_Place="和局 & 大 1.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUACO":	 
		$M_Place=$TG_Team." & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;		
	case "RMUAHU":	
		$M_Place=$MB_Team." & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUANU":	 
		$M_Place="和局 & 小  1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUACU":	 
		$M_Place=$TG_Team." & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;
	case "RMUBHO":	//独赢 & 进球 大 / 小  B
		$M_Place=$MB_Team." & 大 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUBNO":	 
		$M_Place="和局 & 大 2.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUBCO":	 
		$M_Place=$TG_Team." & 大  2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;		
	case "RMUBHU":	
		$M_Place=$MB_Team." & 小 2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUBNU":	 
		$M_Place="和局 & 小 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUBCU":	 
		$M_Place=$TG_Team." & 小 2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;
	case "RMUCHO":	//独赢 & 进球 大 / 小  C
		$M_Place=$MB_Team." & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUCNO":	 
		$M_Place="和局 & 大 3.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUCCO":	 
		$M_Place=$TG_Team." & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;		
	case "RMUCHU":	
		$M_Place=$MB_Team." & 小 3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUCNU":	 
		$M_Place="和局 & 小 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUCCU":	 
		$M_Place=$TG_Team." & 小 3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;
	case "RMUDHO":	//独赢 & 进球 大 / 小  D
		$M_Place=$MB_Team." & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUDNO":	 
		$M_Place="和局 & 大 4.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUDCO":	 
		$M_Place=$TG_Team." & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;		
	case "RMUDHU":	
		$M_Place=$MB_Team." & 小 4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122;
		$gametype=$U_58;
		break;	
	case "RMUDNU":	 
		$M_Place="和局 & 小 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;	
	case "RMUDCU":	 
		$M_Place=$TG_Team." & 小 4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=122; 
		$gametype=$U_58;
		break;
	case "RMTSHY": //独赢 & 双方球队进球
		$M_Place=$MB_Team." & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=123; 
		$gametype=$U_59;
		break; 
	case "RMTSNY": 
		$M_Place="和局 & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=123; 
		$gametype=$U_59;
		break; 
	case "RMTSCY": 
		$M_Place=$TG_Team." & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=123; 
		$gametype=$U_59;
		break; 
	case "RMTSHN": 
		$M_Place=$MB_Team." & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=123; 
		$gametype=$U_59;
		break;
	case "RMTSNN": 
		$M_Place="和局 & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=123; 
		$gametype=$U_59;
		break; 
	case "RMTSCN": 
		$M_Place=$TG_Team." & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=123; 
		$gametype=$U_59;
		break;
	case "RUTAOY":	//进球 大 / 小 & 双方球队进球	 A
		$M_Place="大 1.5 & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;	
	case "RUTAON":	 
		$M_Place="大 1.5 & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;		
	case "RUTAUY":	
		$M_Place="小 1.5 & 是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;	
	case "RUTAUN":	 
		$M_Place="小 1.5 & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;
	case "RUTBOY":	//进球 大 / 小 & 双方球队进球	 B
		$M_Place="大 2.5 & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;	
	case "RUTBON":	 
		$M_Place="大 2.5 & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;		
	case "RUTBUY":	
		$M_Place="小 2.5 & 是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;	
	case "RUTBUN":	 
		$M_Place="小 2.5 & 不是";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;
	case "RUTCOY":	//进球 大 / 小 & 双方球队进球	C
		$M_Place="大 3.5 & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;	
	case "RUTCON":	 
		$M_Place="大 3.5 & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;		
	case "RUTCUY":	
		$M_Place="小 3.5 & 是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;	
	case "RUTCUN":	 
		$M_Place="小 3.5 & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;
	case "RUTDOY":	//进球 大 / 小 & 双方球队进球	D
		$M_Place="大 4.5 & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;	
	case "RUTDON":	 
		$M_Place="大 4.5 & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;		
	case "RUTDUY":	
		$M_Place="小 4.5 & 是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;	
	case "RUTDUN":	 
		$M_Place="小 4.5 & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=124; 
		$gametype=$U_60;
		break;
	case "MPGHH":	//独赢 & 最先进球	 
		$M_Place=$MB_Team.' & '.$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=125; 
		$gametype=$U_61;
		break;
	case "MPGNH":	
		$M_Place='和局 & '.$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=125; 
		$gametype=$U_61;
		break;
	case "MPGCH":	 
		$M_Place=$TG_Team.' & '.$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=125; 
		$gametype=$U_61;
		break;
	case "MPGHC":	
		$M_Place=$MB_Team.' & '.$TG_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=125; 
		$gametype=$U_61;
		break;
	case "MPGNC":	 
		$M_Place='和局 & '.$TG_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=125; 
		$gametype=$U_61;
		break;
	case "MPGCC":	 
		$M_Place=$TG_Team.' & '.$TG_Team."(最先进球)"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=125; 
		$gametype=$U_61;
		break;
	case "F2GH"://先进2球的一方	 
		$M_Place=$MB_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_In_2;
		$linetype=126; 
		$gametype=$U_75;
		break;
	case "F2GC":	 
		$M_Place=$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_In_2;
		$linetype=126; 
		$gametype=$U_75;
		break;
	case "F3GH"://先进3球的一方	 
		$M_Place=$MB_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_In_3;
		$linetype=127; 
		$gametype=$U_80;
		break;
	case "F3GC":	 
		$M_Place=$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_In_3;
		$linetype=127; 
		$gametype=$U_80;
		break;		
	case "HGH"://最多进球的半场	 
		$M_Place="上半场"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Most_Ball_In_Half;
		$linetype=128; 
		$gametype=$U_62;
		break;
	case "HGC":	 
		$M_Place="下半场"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Most_Ball_In_Half;
		$linetype=128; 
		$gametype=$U_62;
		break;
	case "MGH"://最多进球的半场 - 独赢	 
		$M_Place="上半场"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Most_Ball_In_Half_M;
		$linetype=129; 
		$gametype=$U_63;
		break;
	case "MGC":	 
		$M_Place="下半场";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Most_Ball_In_Half_M;
		$linetype=129; 
		$gametype=$U_63;
		break;
	case "MGN":	 
		$M_Place="和局"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Most_Ball_In_Half_M;
		$linetype=129; 
		$gametype=$U_63;
		break;	
	case "SBH"://双半场进球	 
		$M_Place=$MB_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_Half_Ball_In;
		$linetype=130; 
		$gametype=$U_64;
		break;
	case "SBC":	 
		$M_Place=$TG_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_Half_Ball_In;
		$linetype=130; 
		$gametype=$U_64;
		break;
	case "FGS"://首个进球方式	 
		$M_Place="射门";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Way;
		$linetype=131; 
		$gametype=$U_76;
		break;
	case "FGH":	 
		$M_Place="头球";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Way;
		$linetype=131; 
		$gametype=$U_76;
		break;
	case "FGN":
		$M_Place="无进球	";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Way;
		$linetype=131; 
		$gametype=$U_76;
		break;
	case "FGP":	 
		$M_Place="点球";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Way;
		$linetype=131; 
		$gametype=$U_76;
		break;
	case "FGF":	 
		$M_Place="任意球	";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Way;
		$linetype=131; 
		$gametype=$U_76;
		break;
	case "FGO":	 
		$M_Place="乌龙球	";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Way;
		$linetype=131; 
		$gametype=$U_76;
		break;
	case "T3G1"://首个进球时间-3项
		$M_Place="第26分钟或之前";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time_3P;
		$linetype=132; 
		$gametype=$U_65;
		break;
	case "T3G2":	 
		$M_Place="第27分钟或之后	";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time_3P;
		$linetype=132; 
		$gametype=$U_65;
		break;
	case "T3GN":	 
		$M_Place="无进球	";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time_3P;
		$linetype=132; 
		$gametype=$U_65;
		break;
	case "T1G1"://首个进球时间
		$M_Place="上半场开场 - 14:59分钟";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=133; 
		$gametype=$U_66;
		break;
	case "T1G2":	 
		$M_Place="15:00分钟 - 29:59分钟";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=133; 
		$gametype=$U_66;
		break;
	case "T1G3":	 
		$M_Place="30:00分钟 - 半场";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=133; 
		$gametype=$U_66;
		break;
	case "T1G4":	 
		$M_Place="下半场开场 - 59:59分钟";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=133; 
		$gametype=$U_66;
		break;
	case "T1G5":	 
		$M_Place="60:00分钟 - 74:59分钟";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=133; 
		$gametype=$U_66;
		break;
	case "T1G6":	 
		$M_Place="75:00分钟 - 全场完场";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=133; 
		$gametype=$U_66;
		break;
	case "T1GN":	 
		$M_Place="无进球	";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=133; 
		$gametype=$U_66;
		break;
	case "RDUAHO":	//双重机会 & 进球 大 / 小  A
		$M_Place=$MB_Team."/和局"." & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUACO":	 
		$M_Place=$TG_Team."/和局  & 大 1.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUASO":	 
		$M_Place=$MB_Team."/".$TG_Team." & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;		
	case "RDUAHU":	
		$M_Place=$MB_Team."/和局"." & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUACU":	 
		$M_Place=$TG_Team."和局 & 小  1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUASU":	 
		$M_Place=$MB_Team."/".$TG_Team." & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;
	case "RDUBHO":	//双重机会 & 进球 大 / 小  B
		$M_Place=$MB_Team."/和局"." & 大 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUBCO":	 
		$M_Place=$TG_Team."/和局  & 大 2.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUBSO":	 
		$M_Place=$MB_Team."/".$TG_Team." & 大 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;		
	case "RDUBHU":	
		$M_Place=$MB_Team."/和局"." & 小  2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUBCU":	 
		$M_Place=$TG_Team."和局 & 小  2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUBSU":	 
		$M_Place=$MB_Team."/".$TG_Team." & 小 2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
	$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;
	case "RDUCHO":	//双重机会 & 进球 大 / 小  C
		$M_Place=$MB_Team."/和局"." & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUCCO":	 
		$M_Place=$TG_Team."/和局  & 大3.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUCSO":	 
		$M_Place=$MB_Team."/".$TG_Team." & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;		
	case "RDUCHU":	
		$M_Place=$MB_Team."/和局"." & 小  3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUCCU":	 
		$M_Place=$TG_Team."和局 & 小  3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUCSU":	 
		$M_Place=$MB_Team."/".$TG_Team." & 小 3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;
	case "RDUDHO":	//双重机会 & 进球 大 / 小  D
		$M_Place=$MB_Team."/和局"." & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUDCO":	 
		$M_Place=$TG_Team."/和局  & 大4.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUDSO":	 
		$M_Place=$MB_Team."/".$TG_Team." & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;		
	case "RDUDHU":	
		$M_Place=$MB_Team."/和局"." & 小  4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUDCU":	 
		$M_Place=$TG_Team."和局 & 小  4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;	
		$linetype=134; 
		$gametype=$U_67;
		break;	
	case "RDUDSU":	 
		$M_Place=$MB_Team."/".$TG_Team." & 小 4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=134; 
		$gametype=$U_67;
		break;
	case "RDSHY":	//双重机会 & 双方球队进球
		$M_Place=$MB_Team." / 和局   & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=135; 
		$gametype=$U_68;
		break;	
	case "RDSCY":	 
		$M_Place=$TG_Team." / 和局   & 是";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=135; 
		$gametype=$U_68;
		break;	
	case "RDSSY":	 
		$M_Place=$MB_Team.' / '.$TG_Team." & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=135; 
		$gametype=$U_68;
		break;		
	case "RDSHN":	
		$M_Place=$MB_Team." / 和局   & 不是";
		$M_Rate=change_rate($open,$ior_Rate);  
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=135; 
		$gametype=$U_68;
		break;	
	case "RDSCN":	 
		$M_Place=$TG_Team." / 和局   & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=135; 
		$gametype=$U_68;
		break;	
	case "RDSSN":	 
		$M_Place=$MB_Team.' / '.$TG_Team." & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=135; 
		$gametype=$U_68;
		break;
	case "RDGHH":	//双重机会 & 最先进球	 
		$M_Place=$MB_Team." / 和局 & ".$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=136; 
		$gametype=$U_69;
		break;
	case "RDGCH":	
		$M_Place=$TG_Team." / 和局 & ".$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=136; 
		$gametype=$U_69;
		break;
	case "RDGSH":	 
		$M_Place=$MB_Team.' / '.$TG_Team.'&'.$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=136; 
		$gametype=$U_69;
		break;
	case "RDGHC":	
		$M_Place=$MB_Team." / 和局 & ".$TG_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=136; 
		$gametype=$U_69;
		break;
	case "RDGCC":	 
		$M_Place=$TG_Team." / 和局 & ".$TG_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=136; 
		$gametype=$U_69;
		break;
	case "RDGSC":	 
		$M_Place=$MB_Team.' / '.$TG_Team.'&'.$TG_Team."(最先进球)"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=136; 
		$gametype=$U_69;
		break;
	case "RUEAOO":	//进球 大 / 小 & 进球 单 / 双A
		$M_Place="单 & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUEAOE":	 
		$M_Place="双  & 大1.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUEAUO":	
		$M_Place="单  & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUEAUE":	 
		$M_Place="双  & 小  1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;
	case "RUEBOO":	//进球 大 / 小 & 进球 单 / 双B
		$M_Place="单 & 大 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUEBOE":	 
		$M_Place="双  & 大2.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUEBUO":	
		$M_Place="单  & 小  2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUEBUE":	 
		$M_Place="双  & 小  2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;
	case "RUECOO":	//进球 大 / 小 & 进球 单 / 双C
		$M_Place="单 & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUECOE":	 
		$M_Place="双  & 大3.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUECUO":	
		$M_Place="单  & 小  3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUECUE":	 
		$M_Place="双  & 小  3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;
	case "RUEDOO":	//进球 大 / 小 & 进球 单 / 双		D
		$M_Place="单 & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUEDOE":	 
		$M_Place="双  & 大4.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUEDUO":	
		$M_Place="单  & 小  4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;	
	case "RUEDUE":	 
		$M_Place="双  & 小  4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=137; 
		$gametype=$U_70;
		break;
	case "RUPAOH":	//进球 大 / 小 & 最先进球		A
		$M_Place=$MB_Team."(最先进球) & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPAOC":	 
		$M_Place=$TG_Team."(最先进球) & 大1.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPAUH":	
		$M_Place=$MB_Team."(最先进球) & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPAUC":	 
		$M_Place=$TG_Team."(最先进球) & 小  1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;
	case "RUPBOH":	//进球 大 / 小 & 最先进球		B
		$M_Place=$MB_Team."(最先进球) & 大 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPBOC":	 
		$M_Place=$TG_Team."(最先进球) & 大2.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPBUH":	
		$M_Place=$MB_Team."(最先进球) & 小  2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPBUC":	 
		$M_Place=$TG_Team."(最先进球) & 小  2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPCOH":	//进球 大 / 小 & 最先进球		C
		$M_Place=$MB_Team."(最先进球) & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPCOC":	 
		$M_Place=$TG_Team."(最先进球) & 大3.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPCUH":	
		$M_Place=$MB_Team."(最先进球) & 小  3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPCUC":	 
		$M_Place=$TG_Team."(最先进球) & 小  3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPDOH":	//进球 大 / 小 & 最先进球		D
		$M_Place=$MB_Team."(最先进球) & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPDOC":	 
		$M_Place=$TG_Team."(最先进球) & 大4.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPDUH":	
		$M_Place=$MB_Team."(最先进球) & 小  4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;	
	case "RUPDUC":	 
		$M_Place=$TG_Team."(最先进球) & 小  4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=138; 
		$gametype=$U_71;
		break;
	case "RW3H"://三项让球投注	 
		$M_Place=$MB_Team." ".$detailsData['ratio_'.strtolower($rtype)]; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_R_3;
		$linetype=139; 
		$gametype=$U_77;
		break;
	case "RW3C":	 
		$M_Place=$TG_Team." ".$detailsData['ratio_'.strtolower($rtype)]; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_R_3;
		$linetype=139; 
		$gametype=$U_77;
		break;
	case "RW3N":	 
		$M_Place="让球和局   ".$detailsData['ratio_'.strtolower($rtype)]; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_R_3;
		$linetype=139; 
		$gametype=$U_77;
		break;
	case "RBHH"://落后反超获胜
		$M_Place=$MB_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Fall_Catchup_And_Win;
		$linetype=140; 
		$gametype=$U_78;
		break;
	case "RBHC":	 
		$M_Place=$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Fall_Catchup_And_Win;
		$linetype=140; 
		$gametype=$U_78;
		break;
	case "RWEH"://赢得任一半场
		$M_Place=$MB_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Win_Any_Half ;
		$linetype=141; 
		$gametype=$U_72;
		break;
	case "RWEC":	 
		$M_Place=$TG_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Win_Any_Half ;
		$linetype=141; 
		$gametype=$U_72;
		break;
	case "RWBH"://赢得所有半场
		$M_Place=$MB_Team;   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Win_All_Half;
		$linetype=142; 
		$gametype=$U_73;
		break;
	case "RWBC":	 
		$M_Place=$TG_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Win_All_Half;
		$linetype=142; 
		$gametype=$U_73;
		break;
	case "RTKH"://开球球队
		$M_Place=$MB_Team;   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_First_Ball;
		$linetype=143; 
		$gametype=$U_79;
		break;
	case "RTKC":	 
		$M_Place=$TG_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_First_Ball;
		$linetype=143; 
		$gametype=$U_79;
		break;
	case "ROUHO"://球队进球数: 主队 - 大 
		$M_Place=$M_Place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
		$linetype=154; 
		$gametype=$U_51.' '.$MB_Team.' - 大/小';
		break;
	case "ROUHU"://球队进球数: 主队 - 小 	
		$M_Place=$M_Place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
		$linetype=154; 
		$gametype=$U_51.' '.$MB_Team.' - 大/小';
		break;
	case "ROUCO"://球队进球数: 客队 - 大 
		$M_Place=$M_Place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
		$linetype=154; 
		$gametype=$U_51.' '.$TG_Team.' - 大/小';
		break;
	case "ROUCU"://球队进球数: 客队 - 小 	
		$M_Place=$M_Place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
		$linetype=154; 
		$gametype=$U_51.' '.$TG_Team.' - 大/小';
		break;
	case "HRUHO"://上半场	 球队进球数: 主队 - 大 
		$M_Place=$M_Place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
		$linetype=244; 
		$gametype=$U_00.' '.$U_51.' '.$MB_Team.' - 大/小';
		break;
	case "HRUHU"://上半场	球队进球数: 主队 - 小 	
		$M_Place=$M_Place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
		$linetype=244; 
		$gametype=$U_00.' '.$U_51.' '.$MB_Team.' - 大/小';
		break;
	case "HRUCO"://上半场	 球队进球数: 主队 - 大 
		$M_Place=$M_Place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
		$linetype=244; 
		$gametype=$U_00.' '.$U_51.' '.$TG_Team.' - 大/小';
		break;
	case "HRUCU"://上半场	球队进球数: 主队 - 小 	
		$M_Place=$M_Place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
		$linetype=244; 
		$gametype=$U_00.' '.$U_51.' '.$TG_Team.' - 大/小';
		break;
	} 
	
	$gametype = "(".$Running_Ball.") ".$gametype; 
	
	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and MID='$gid' and linetype='$linetype' and (Active=1 or Active=11)";
	$result = mysqli_query($dbLink,$havesql);

	$haverow = mysqli_fetch_assoc($result); 
	$have_bet=$haverow['BetScore']+0; 

    $bettop=$GSINGLE_CREDIT;
	if ($bettop<$GSINGLE_CREDIT){
		$bettop_money=$GSINGLE_CREDIT;
	}else{
		$bettop_money=$GSINGLE_CREDIT;
	}
	
	if(strlen($M_Rate)==0 || $M_Rate==0){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="/style/member/mem_order_ft.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
  <script language="JavaScript" src="../../../js/jquery.js"></script><script language="JavaScript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<?php if($wtype=='HRUH' || $wtype=='HRUC'){ ?> <!--球队进球数 大小不需要减去本金-->
    <script language="JavaScript" src="../../../js/football_order.js?v=<?php echo AUTOVER; ?>"></script>
<?php }else{ ?>
    <script language="JavaScript" src="../../../js/ft_pd_order.js?v=<?php echo AUTOVER; ?>"></script>
<?php }?>

<body id="OFT" class="bodyset" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<form name="LAYOUTFORM" action="/app/member/FT_order/FT_order_re_finish.php" method="post" onSubmit="return false">
  <div class="ord">
   	<div class="title"><h1>足球</h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="checkbox" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>

	<div class="main">
        <div class="ord_betArea">
             <div class="gametype"><?php echo $gametype; ?></div>
              <div class="leag"><?php echo $M_League?></div>

              <div class="teamName"><span class="tName"><?php echo $MB_Team?> vs. <?php echo $TG_Team?></span></div>
              <p class="team"><em><?php echo $M_Place?></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>
        </div>
      <p class="auto"><input type="checkbox" id="autoOdd" name="autoOdd" onClick="onclickReloadAutoOdd()" checked value="Y"><span class="auto_info" title="在方格里打勾表示，如果投注单里的任何选项在确认投注时赔率变佳，系统会无提示的继续进行下注。">自动接受较佳赔率</span></p>
      <p class="error" style="display: none;"></p>
      <div class="betdata">
          <p class="amount">
              <?php
              if($wtype=='HRUH' || $wtype=='HRUC'){  // 球队进球数 大小不需要减去本金
                  echo '交易金额：<input name="gold" type="text" class="txt" id="gold" onKeyPress="return CheckKey(event)" onKeyUp="return CountWinGold()" size="8" maxlength="10" placeholder="投注额"> <span class="clean_bet_money" id="betClear"></span>' ;
              }else{
                  echo '交易金额：<input name="gold" type="text" class="txt" id="gold" onKeyPress="return CheckKey(event)" onKeyUp="return CountWinGold_dy_ds_dyh()" size="8" maxlength="10" placeholder="投注额"> <span class="clean_bet_money" id="betClear"></span>' ;
              }
              ?>
          </p>
          <p class="mayWin"><span class="bet_txt">可赢金额：</span><font id="pc">0</font></p>
          <p class="minBet"><span class="bet_txt">单注最低：</span><?php echo $GMIN_SINGLE?></p>
          <p class="maxBet"><span class="bet_txt">单注最高：</span><?php echo $GSINGLE_CREDIT?></p> <div class="betAmount"> </div>
    </div>
    </div>
      <div id="gWager" style="display: none;position: absolute;"></div>
      <div id="gbutton" style="display: block;position: absolute;"></div>
  <div class="betBox">
    <input type="button" name="btnCancel" value="取消" onClick="parent.close_bet();" class="no">
      <?php
      if($wtype=='HRUH' || $wtype=='HRUC'){  // 球队进球数 大小不需要减去本金
          echo '<input type="button" name="Submit" value="确定交易" onClick="CountWinGold();return SubChk();" class="yes">' ;
      }else{
          echo '<input type="button" name="Submit" value="确定交易" onClick="CountWinGold_dy_ds_dyh();return SubChk();" class="yes">' ;
      }
      ?>
  </div>
  </div>  
<input type="hidden" name="uid" value="<?php echo $uid?>">
<input type="hidden" name="active" value="<?php echo $active?>">
<input type="hidden" name="rtype" value="<?php echo $rtype?>">
<input type="hidden" name="ordertype" value="1">
<input type="hidden" name="line_type" value="<?php echo $linetype?>">
<input type="hidden" name="gid" value="<?php echo $gid?>">
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>">
<input type="hidden" id="ioradio_r_h" name="ioradio_r_h" value="<?php echo $M_Rate; ?>">
<input type="hidden" name="gmax_single" value="<?php echo $bettop_money?>">
<input type="hidden" name="gmin_single" value="<?php echo $GMIN_SINGLE?>">
<input type="hidden" name="singlecredit" value="<?php echo $GMAX_SINGLE?>">
<input type="hidden" name="singleorder" value="<?php echo $GSINGLE_CREDIT?>">
<input type="hidden" name="restsinglecredit" value="<?php echo $have_bet?>">
<input type="hidden" name="wagerstotal" value="<?php echo $GMAX_SINGLE?>">
    <input type="hidden" name="restcredit" value="<?php echo  $credit?>"> <input type="hidden" name="token" value="<?php echo getRandomString(32)?>">
<input type="hidden" name="pay_type" value="<?php echo $pay_type?>">
<input type="hidden" name="odd_f_type" value="<?php echo $odd_f_type?>">
<input type="hidden" name="wtype" value="<?php echo $wtype?>">
<input type="hidden" name="gnum" value="<?php echo $gunm?>">
<input type="hidden" name="type" value="<?php echo $type?>">

<?php 
if(isset($_REQUEST['radio']) && $_REQUEST['radio']>0 ){ 
?>
<input type="hidden" name="source" value="authority">
<?php 
}
?>
</form>
</body>
<SCRIPT LANGUAGE="JavaScript">document.all.gold.focus();</script>
</html>