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
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$gid=$_REQUEST['gid'];
$rtype=$_REQUEST['rtype'];
$wtype=$_REQUEST['wtype'];
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

if ($change==1){
	$bet_title=$nobettitle;
}
if($gid%2==0){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DBPREFIX."match_sports` where `m_start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and $mb_team!=''";
}elseif($gid%2==1){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DBPREFIX."match_sports` where `m_start`>now() and `MID`=$gid-1 and Cancel!=1 and Open=1 and $mb_team!=''";
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
}else{

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
	case "TSY": 
		$M_Place="是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_In_betting_order; 
		$linetype=65; 
		$gametype=$U_50;
		break; 
	case "TSN": 
		$M_Place="不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_In_betting_order; 
		$linetype=65; 
		$gametype=$U_50;
		break; 
	case "HTSY": 
		$M_Place="是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_In_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=165; 
		$gametype=$U_50."-".$U_00;
		break; 
	case "HTSN": 
		$M_Place="不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_In_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=165; 
		$gametype=$U_50."-".$U_00;
		break; 
	case "WMH1": 
		$M_Place=$MB_Team." - 净胜1球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=18; 
		$gametype=$U_53;
		break;	
	case "WM0": 
		$M_Place=" 0 - 0 和局"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=18; 
		$gametype=$U_53;
		break;	
	case "WMC1": 
		$M_Place=$TG_Team." - 净胜1球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=18; 
		$gametype=$U_53;
		break;
	case "WMH2": 
		$M_Place=$MB_Team." - 净胜2球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=18; 
		$gametype=$U_53;
		break;	
	case "WMN": 
		$M_Place="任何进球和局"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=18; 
		$gametype=$U_53;
		break;	
	case "WMC2": 
		$M_Place=$TG_Team." - 净胜2球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=18; 
		$gametype=$U_53;
		break;	
	case "WMH3": 
		$M_Place=$MB_Team." - 净胜3球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=18; 
		$gametype=$U_53;
		break;
	case "WMC3": 
		$M_Place=$TG_Team." - 净胜3球"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=18; 
		$gametype=$U_53;
		break;	
	case "WMHOV": 
		$M_Place=$MB_Team." - 净胜4球或更多";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=18; 
		$gametype=$U_53;
		break;	
	case "WMCOV": 
		$M_Place=$TG_Team." - 净胜4球或更多";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Net_Win_Ballnum;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=18; 
		$gametype=$U_53;
		break;
	case "DCHN": 
		$M_Place=$MB_Team." / "."和局"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=69; 
		$gametype=$U_54;
		break;	
	case "DCCN": 
		$M_Place=$TG_Team." / "."和局";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=69; 
		$gametype=$U_54;
		break;	
	case "DCHC": 
		$M_Place=$MB_Team." / ".$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=69; 
		$gametype=$U_54;
		break;
	case "CSH": 
		$M_Place=$MB_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Clean_Sheets;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=62; 
		$gametype=$U_55;
		break;
	case "CSC": 
		$M_Place=$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Clean_Sheets; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=62; 
		$gametype=$U_55;
		break;	
	case "WNH": 
		$M_Place=$MB_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Clean_Sheets_Win; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=61; 
		$gametype=$U_56;
		break;	
	case "WNC": 
		$M_Place=$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Clean_Sheets_Win;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=61; 
		$gametype=$U_56;
		break;
	case "MOUAHO":	//独赢 & 进球 大 /小  A
		$M_Place=$MB_Team." & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUANO":	 
		$M_Place="和局 & 大 1.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUACO":	 
		$M_Place=$TG_Team." & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;		
	case "MOUAHU":	
		$M_Place=$MB_Team." & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUANU":	 
		$M_Place="和局 & 小  1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUACU":	 
		$M_Place=$TG_Team." & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;
	case "MOUBHO":	//独赢 & 进球 大 / 小  B
		$M_Place=$MB_Team." & 大 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUBNO":	 
		$M_Place="和局 & 大 2.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUBCO":	 
		$M_Place=$TG_Team." & 大  2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;		
	case "MOUBHU":	
		$M_Place=$MB_Team." & 小 2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUBNU":	 
		$M_Place="和局 & 小 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUBCU":	 
		$M_Place=$TG_Team." & 小 2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;
	case "MOUCHO":	//独赢 & 进球 大 / 小  C
		$M_Place=$MB_Team." & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUCNO":	 
		$M_Place="和局 & 大 3.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUCCO":	 
		$M_Place=$TG_Team." & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;		
	case "MOUCHU":	
		$M_Place=$MB_Team." & 小 3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUCNU":	 
		$M_Place="和局 & 小 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUCCU":	 
		$M_Place=$TG_Team." & 小 3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;
	case "MOUDHO":	//独赢 & 进球 大 / 小  D
		$M_Place=$MB_Team." & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUDNO":	 
		$M_Place="和局 & 大 4.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUDCO":	 
		$M_Place=$TG_Team." & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;		
	case "MOUDHU":	
		$M_Place=$MB_Team." & 小 4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUDNU":	 
		$M_Place="和局 & 小 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;	
	case "MOUDCU":	 
		$M_Place=$TG_Team." & 小 4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_OU;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=22; 
		$gametype=$U_58;
		break;
	case "MTSHY": //独赢 & 双方球队进球
		$M_Place=$MB_Team." & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=23; 
		$gametype=$U_59;
		break; 
	case "MTSNY": 
		$M_Place="和局 & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=23; 
		$gametype=$U_59;
		break; 
	case "MTSCY": 
		$M_Place=$TG_Team." & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=23; 
		$gametype=$U_59;
		break; 
	case "MTSHN": 
		$M_Place=$MB_Team." & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=23; 
		$gametype=$U_59;
		break;
	case "MTSNN": 
		$M_Place="和局 & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=23; 
		$gametype=$U_59;
		break; 
	case "MTSCN": 
		$M_Place=$TG_Team." & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_Double_in; 
		$linetype=23; 
		$gametype=$U_59;
		break;
	case "OUTAOY":	//进球 大 / 小 & 双方球队进球	 A
		$M_Place="大 1.5 & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;	
	case "OUTAON":	 
		$M_Place="大 1.5 & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;		
	case "OUTAUY":	
		$M_Place="小 1.5 & 是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;	
	case "OUTAUN":	 
		$M_Place="小 1.5 & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;
	case "OUTBOY":	//进球 大 / 小 & 双方球队进球	 B
		$M_Place="大 2.5 & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;	
	case "OUTBON":	 
		$M_Place="大 2.5 & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;		
	case "OUTBUY":	
		$M_Place="小 2.5 & 是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;	
	case "OUTBUN":	 
		$M_Place="小 2.5 & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;
	case "OUTCOY":	//进球 大 / 小 & 双方球队进球	C
		$M_Place="大 3.5 & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;	
	case "OUTCON":	 
		$M_Place="大 3.5 & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;		
	case "OUTCUY":	
		$M_Place="小 3.5 & 是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;	
	case "OUTCUN":	 
		$M_Place="小 3.5 & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;
	case "OUTDOY":	//进球 大 / 小 & 双方球队进球	D
		$M_Place="大 4.5 & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;	
	case "OUTDON":	 
		$M_Place="大 4.5 & 不是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;		
	case "OUTDUY":	
		$M_Place="小 4.5 & 是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;	
	case "OUTDUN":	 
		$M_Place="小 4.5 & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_OU_Double_in;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=24; 
		$gametype=$U_60;
		break;
	case "MPGHH":	//独赢 & 最先进球	 
		$M_Place=$MB_Team.' & '.$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=25; 
		$gametype=$U_61;
		break;
	case "MPGNH":	
		$M_Place='和局 & '.$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=25; 
		$gametype=$U_61;
		break;
	case "MPGCH":	 
		$M_Place=$TG_Team.' & '.$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=25; 
		$gametype=$U_61;
		break;
	case "MPGHC":	
		$M_Place=$MB_Team.' & '.$TG_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=25; 
		$gametype=$U_61;
		break;
	case "MPGNC":	 
		$M_Place='和局 & '.$TG_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=25; 
		$gametype=$U_61;
		break;
	case "MPGCC":	 
		$M_Place=$TG_Team.' & '.$TG_Team."(最先进球)"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_M_Ball_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=25; 
		$gametype=$U_61;
		break;
	case "F2GH"://先进2球的一方	 
		$M_Place=$MB_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_In_2;
		$linetype=26; 
		$gametype=$U_75;
		break;
	case "F2GC":	 
		$M_Place=$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_In_2;
		$linetype=26; 
		$gametype=$U_75;
		break;
	case "F3GH"://先进3球的一方	 
		$M_Place=$MB_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_In_3;
		$linetype=27; 
		$gametype=$U_80;
		break;
	case "F3GC":	 
		$M_Place=$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_In_3;
		$linetype=27; 
		$gametype=$U_80;
		break;		
	case "HGH"://最多进球的半场	 
		$M_Place="上半场";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Most_Ball_In_Half;
		$linetype=28; 
		$gametype=$U_62;
		break;
	case "HGC":	 
		$M_Place="下半场"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Most_Ball_In_Half;
		$linetype=28; 
		$gametype=$U_62;
		break;
	case "MGH"://最多进球的半场 - 独赢	 
		$M_Place="上半场"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Most_Ball_In_Half_M;
		$linetype=29; 
		$gametype=$U_63;
		break;
	case "MGC":	 
		$M_Place="下半场";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Most_Ball_In_Half_M;
		$linetype=29; 
		$gametype=$U_63;
		break;
	case "MGN":	 
		$M_Place="和局"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Most_Ball_In_Half_M;
		$linetype=29; 
		$gametype=$U_63;
		break;	
	case "SBH"://双半场进球	 
		$M_Place=$MB_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_Half_Ball_In;
		$linetype=30; 
		$gametype=$U_64;
		break;
	case "SBC":	 
		$M_Place=$TG_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Double_Half_Ball_In;
		$linetype=30; 
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
		$linetype=32; 
		$gametype=$U_65;
		break;
	case "T3G2":	 
		$M_Place="第27分钟或之后	";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time_3P;
		$linetype=32; 
		$gametype=$U_65;
		break;
	case "T3GN":	 
		$M_Place="无进球	";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time_3P;
		$linetype=32; 
		$gametype=$U_65;
		break;
	case "T1G1"://首个进球时间
		$M_Place="上半场开场 - 14:59分钟";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=33; 
		$gametype=$U_66;
		break;
	case "T1G2":	 
		$M_Place="15:00分钟 - 29:59分钟";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=33; 
		$gametype=$U_66;
		break;
	case "T1G3":	 
		$M_Place="30:00分钟 - 半场";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=33; 
		$gametype=$U_66;
		break;
	case "T1G4":	 
		$M_Place="下半场开场 - 59:59分钟";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=33; 
		$gametype=$U_66;
		break;
	case "T1G5":	 
		$M_Place="60:00分钟 - 74:59分钟";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=33; 
		$gametype=$U_66;
		break;
	case "T1G6":	 
		$M_Place="75:00分钟 - 全场完场";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=33; 
		$gametype=$U_66;
		break;
	case "T1GN":	 
		$M_Place="无进球	";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Frist_Ball_In_Time;
		$linetype=33; 
		$gametype=$U_66;
		break;
	case "DUAHO":	//双重机会 & 进球 大 / 小  A
		$M_Place=$MB_Team."/和局"." & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUACO":	 
		$M_Place=$TG_Team."/和局  & 大 1.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUASO":	 
		$M_Place=$MB_Team."/".$TG_Team." & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;		
	case "DUAHU":	
		$M_Place=$MB_Team."/和局"." & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUACU":	 
		$M_Place=$TG_Team."和局 & 小  1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUASU":	 
		$M_Place=$MB_Team."/".$TG_Team." & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;
	case "DUBHO":	//双重机会 & 进球 大 / 小  B
		$M_Place=$MB_Team."/和局"." & 大 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUBCO":	 
		$M_Place=$TG_Team."/和局  & 大 2.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUBSO":	 
		$M_Place=$MB_Team."/".$TG_Team." & 大 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;		
	case "DUBHU":	
		$M_Place=$MB_Team."/和局"." & 小  2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUBCU":	 
		$M_Place=$TG_Team."和局 & 小  2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUBSU":	 
		$M_Place=$MB_Team."/".$TG_Team." & 小 2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
	$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;
	case "DUCHO":	//双重机会 & 进球 大 / 小  C
		$M_Place=$MB_Team."/和局"." & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUCCO":	 
		$M_Place=$TG_Team."/和局  & 大3.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUCSO":	 
		$M_Place=$MB_Team."/".$TG_Team." & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;		
	case "DUCHU":	
		$M_Place=$MB_Team."/和局"." & 小  3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUCCU":	 
		$M_Place=$TG_Team."和局 & 小  3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUCSU":	 
		$M_Place=$MB_Team."/".$TG_Team." & 小 3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;
	case "DUDHO":	//双重机会 & 进球 大 / 小  D
		$M_Place=$MB_Team."/和局"." & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUDCO":	 
		$M_Place=$TG_Team."/和局  & 大4.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUDSO":	 
		$M_Place=$MB_Team."/".$TG_Team." & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;		
	case "DUDHU":	
		$M_Place=$MB_Team."/和局"." & 小  4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUDCU":	 
		$M_Place=$TG_Team."和局 & 小  4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;	
		$linetype=34; 
		$gametype=$U_67;
		break;	
	case "DUDSU":	 
		$M_Place=$MB_Team."/".$TG_Team." & 小 4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_OU;
		$linetype=34; 
		$gametype=$U_67;
		break;
	case "DSHY":	//双重机会 & 双方球队进球
		$M_Place=$MB_Team." / 和局   & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=35; 
		$gametype=$U_68;
		break;	
	case "DSCY":	 
		$M_Place=$TG_Team." / 和局   & 是";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=35; 
		$gametype=$U_68;
		break;	
	case "DSSY":	 
		$M_Place=$MB_Team.' / '.$TG_Team." & 是"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=35; 
		$gametype=$U_68;
		break;		
	case "DSHN":	
		$M_Place=$MB_Team." / 和局   & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=35; 
		$gametype=$U_68;
		break;	
	case "DSCN":	 
		$M_Place=$TG_Team." / 和局   & 不是";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=35; 
		$gametype=$U_68;
		break;	
	case "DSSN":	 
		$M_Place=$MB_Team.' / '.$TG_Team." & 不是";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Double_In;
		$linetype=35; 
		$gametype=$U_68;
		break;
	case "DGHH":	//双重机会 & 最先进球	 
		$M_Place=$MB_Team." / 和局 & ".$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=36; 
		$gametype=$U_69;
		break;
	case "DGCH":	
		$M_Place=$TG_Team." / 和局 & ".$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=36; 
		$gametype=$U_69;
		break;
	case "DGSH":	 
		$M_Place=$MB_Team.' / '.$TG_Team.'&'.$MB_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=36; 
		$gametype=$U_69;
		break;
	case "DGHC":	
		$M_Place=$MB_Team." / 和局 & ".$TG_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=36; 
		$gametype=$U_69;
		break;
	case "DGCC":	 
		$M_Place=$TG_Team." / 和局 & ".$TG_Team."(最先进球)";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=36; 
		$gametype=$U_69;
		break;
	case "DGSC":	 
		$M_Place=$MB_Team.' / '.$TG_Team.'&'.$TG_Team."(最先进球)"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Chance_Double_And_Ball_In_First;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=36; 
		$gametype=$U_69;
		break;
	case "OUEAOO":	//进球 大 / 小 & 进球 单 / 双A
		$M_Place="单 & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUEAOE":	 
		$M_Place="双  & 大1.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUEAUO":	
		$M_Place="单  & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUEAUE":	 
		$M_Place="双  & 小  1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;
	case "OUEBOO":	//进球 大 / 小 & 进球 单 / 双B
		$M_Place="单 & 大 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUEBOE":	 
		$M_Place="双  & 大2.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUEBUO":	
		$M_Place="单  & 小  2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUEBUE":	 
		$M_Place="双  & 小  2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;
	case "OUECOO":	//进球 大 / 小 & 进球 单 / 双C
		$M_Place="单 & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUECOE":	 
		$M_Place="双  & 大3.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUECUO":	
		$M_Place="单  & 小  3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUECUE":	 
		$M_Place="双  & 小  3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;
	case "OUEDOO":	//进球 大 / 小 & 进球 单 / 双		D
		$M_Place="单 & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUEDOE":	 
		$M_Place="双  & 大4.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUEDUO":	
		$M_Place="单  & 小  4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;	
	case "OUEDUE":	 
		$M_Place="双  & 小  4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_OE;
		$linetype=37; 
		$gametype=$U_70;
		break;
	case "OUPAOH":	//进球 大 / 小 & 最先进球		A
		$M_Place=$MB_Team."(最先进球) & 大 1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPAOC":	 
		$M_Place=$TG_Team."(最先进球) & 大1.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPAUH":	
		$M_Place=$MB_Team."(最先进球) & 小  1.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPAUC":	 
		$M_Place=$TG_Team."(最先进球) & 小  1.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;
	case "OUPBOH":	//进球 大 / 小 & 最先进球		B
		$M_Place=$MB_Team."(最先进球) & 大 2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPBOC":	 
		$M_Place=$TG_Team."(最先进球) & 大2.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPBUH":	
		$M_Place=$MB_Team."(最先进球) & 小  2.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPBUC":	 
		$M_Place=$TG_Team."(最先进球) & 小  2.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPCOH":	//进球 大 / 小 & 最先进球		C
		$M_Place=$MB_Team."(最先进球) & 大 3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPCOC":	 
		$M_Place=$TG_Team."(最先进球) & 大3.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPCUH":	
		$M_Place=$MB_Team."(最先进球) & 小  3.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPCUC":	 
		$M_Place=$TG_Team."(最先进球) & 小  3.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPDOH":	//进球 大 / 小 & 最先进球		D
		$M_Place=$MB_Team."(最先进球) & 大 4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPDOC":	 
		$M_Place=$TG_Team."(最先进球) & 大4.5";
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPDUH":	
		$M_Place=$MB_Team."(最先进球) & 小  4.5";  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;	
	case "OUPDUC":	 
		$M_Place=$TG_Team."(最先进球) & 小  4.5"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_OU_And_Ball_In_First;
		$linetype=38; 
		$gametype=$U_71;
		break;
	case "W3H"://三项让球投注	 
		$M_Place=$MB_Team." ".$detailsData['ratio_'.strtolower($rtype)]; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_R_3;
		$linetype=39; 
		$gametype=$U_77;
		break;
	case "W3C":	 
		$M_Place=$TG_Team." ".$detailsData['ratio_'.strtolower($rtype)];  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_R_3;
		$linetype=39; 
		$gametype=$U_77;
		break;
	case "W3N":	 
		$M_Place="让球和局"." ".$detailsData['ratio_'.strtolower($rtype)]; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Ball_R_3;
		$linetype=39; 
		$gametype=$U_77;
		break;
	case "BHH"://落后反超获胜
		$M_Place=$MB_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Fall_Catchup_And_Win;
		$linetype=40; 
		$gametype=$U_78;
		break;
	case "BHC":	 
		$M_Place=$TG_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Fall_Catchup_And_Win;
		$linetype=40; 
		$gametype=$U_78;
		break;
	case "WEH"://赢得任一半场
		$M_Place=$MB_Team; 
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Win_Any_Half ;
		$linetype=41; 
		$gametype=$U_72;
		break;
	case "WEC":	 
		$M_Place=$TG_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Win_Any_Half ;
		$linetype=41; 
		$gametype=$U_72;
		break;
	case "WBH"://赢得所有半场
		$M_Place=$MB_Team;   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Win_All_Half;
		$linetype=42; 
		$gametype=$U_73;
		break;
	case "WBC":	 
		$M_Place=$TG_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Win_All_Half;
		$linetype=42; 
		$gametype=$U_73;
		break;
	case "TKH"://开球球队
		$M_Place=$MB_Team;   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_First_Ball;
		$linetype=43; 
		$gametype=$U_79;
		break;
	case "TKC":	 
		$M_Place=$TG_Team;  
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_First_Ball;
		$linetype=43; 
		$gametype=$U_79;
		break;
	case "OUHO"://球队进球数: 主队 - 大 
		$M_Place=$M_Place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
		$linetype=44; 
		$gametype=$U_51.' '.$MB_Team.' - 大/小';
		break;
	case "OUHU"://球队进球数: 主队 - 小 	
		$M_Place=$M_Place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
		$linetype=44; 
		$gametype=$U_51.' '.$MB_Team.' - 大/小';
		break;
	case "OUCO"://球队进球数: 客队 - 大 
		$M_Place=$M_Place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
		$linetype=44; 
		$gametype=$U_51.' '.$TG_Team.' - 大/小';
		break;
	case "OUCU"://球队进球数: 客队 - 小 	
		$M_Place=$M_Place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
		$linetype=44; 
		$gametype=$U_51.' '.$TG_Team.' - 大/小';
		break;
	case "HOUHO"://上半场	 球队进球数: 主队 - 大 
		$M_Place=$M_Place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
		$linetype=144; 
		$gametype=$U_00.' '.$U_51.' '.$MB_Team.' - 大/小';
		break;
	case "HOUHU"://上半场	球队进球数: 主队 - 小 	
		$M_Place=$M_Place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
		$linetype=144; 
		$gametype=$U_00.' '.$U_51.' '.$MB_Team.' - 大/小';
		break;
	case "HOUCO"://上半场	 球队进球数: 主队 - 大 
		$M_Place=$M_Place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
		$linetype=144; 
		$gametype=$U_00.' '.$U_51.' '.$TG_Team.' - 大/小';
		break;
	case "HOUCU"://上半场	球队进球数: 主队 - 小 	
		$M_Place=$M_Place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];   
		$M_Rate=change_rate($open,$ior_Rate);
		$caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
		$linetype=144; 
		$gametype=$U_00.' '.$U_51.' '.$TG_Team.' - 大/小';
		break;
	} 
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
	
	if(strlen($M_Rate)==0){
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

<?php if($wtype=='OUH' || $wtype=='OUC' || $wtype=='HOUH' || $wtype=='HOUC'){ ?> <!--球队进球数 大小不需要减去本金-->
    <script language="JavaScript" src="../../../js/football_order.js?v=<?php echo AUTOVER; ?>"></script>
<?php }else{ ?>
    <script language="JavaScript" src="../../../js/ft_pd_order.js?v=<?php echo AUTOVER; ?>"></script>
<?php }?>
<body id="OFT" class="bodyset" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<form name="LAYOUTFORM" action="/app/member/FT_order/FT_order_finish.php" method="post" onSubmit="return false">
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
          <p class="amount">交易金额：
              <?php
              if($wtype=='OUH' || $wtype=='OUC' || $wtype=='HOUH' || $wtype=='HOUC'){  // 球队进球数 大小不需要减去本金
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
      <?php
      if($wtype=='OUH' || $wtype=='OUC' || $wtype=='HOUH' || $wtype=='HOUC'){  // 球队进球数 大小不需要减去本金
          echo '<input type="button" name="Submit" value="确定交易" onClick="CountWinGold();return SubChk();" class="yes">' ;
      }else{
          echo '<input type="button" name="Submit" value="确定交易" onClick="CountWinGold_dy_ds_dyh();return SubChk();" class="yes">' ;
      }
      ?>
    <input type="button" name="btnCancel" value="取消" onClick="parent.close_bet();" class="no">

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
</form>
</body>
<SCRIPT LANGUAGE="JavaScript">document.all.gold.focus();</script>
</html>
<?php 
} 
?>