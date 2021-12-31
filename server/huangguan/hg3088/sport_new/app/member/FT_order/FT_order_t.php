<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
include "../include/address.mem.php";

require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
require ("../include/define_function_list.inc.php");

$uid=(isset($_REQUEST['uid']) && $_REQUEST['uid'])? $_REQUEST['uid'] :$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$gid=$_REQUEST['gid'];
$rtype=$_REQUEST['rtype'];
$wtype=$_REQUEST['wtype'];
$odd_f_type=$_REQUEST['odd_f_type'];
$change=$_REQUEST['change'];
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>top.location.href='/'</script>";
	exit;
}
$sql = "select Money from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$credit=$row['Money'];
$pay_type=$_SESSION['Pay_Type'];
$memname=$_SESSION['UserName'];
$open=$_SESSION['OpenType'];

$btset=singleset('T');
$GMIN_SINGLE=$btset[0];
$GMAX_SINGLE1=FT_EO_Scene; 
$GSINGLE_CREDIT1=FT_EO_Bet; 
$GMAX_SINGLE2=FT_T_Scene; 
$GSINGLE_CREDIT2=FT_T_Bet; 

if ($change==1){
	$bet_title=$nobettitle;
}

if($gid%2==0){
    $mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and $mb_team!=''";
}elseif($gid%2==1){
    //$mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid-1 and Cancel!=1 and Open=1 and $mb_team!=''";
    $mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Cancel!=1 and Open=1 and $mb_team!=''";
}
$resultL = mysqli_query($dbLink,$mysqlL);
$couL=mysqli_num_rows($resultL);
if($couL==0) {
    echo attention("$Order_This_match_is_closed_Please_try_again", $uid, $langx);
    exit;
}

if($gid%2==0){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate,S_0_1,S_2_3,S_4_6,S_7UP from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
}elseif($gid%2==1){
	//$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate,S_0_1,S_2_3,S_4_6,S_7UP from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid-1 and Open=1 and $mb_team!=''";
    $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate,S_0_1,S_2_3,S_4_6,S_7UP from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Open=1 and $mb_team!=''";
}

if($_REQUEST['id']&&$_REQUEST['id']){
	$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
	$rowMore = mysqli_fetch_assoc($moreRes);
	$couMore = mysqli_num_rows($moreRes);
	$detailsArr = json_decode($rowMore['details'],true);
	$detailsData =$detailsArr[$gid];
	if(in_array($rtype,array('0~1','2~3','4~6','OVER'))){
		if($rtype=='0~1')	$rtypeNew="T01";
		if($rtype=='2~3') $rtypeNew="T23";
		if($rtype=='4~6')	$rtypeNew="T46";
		if($rtype=='OVER')	$rtypeNew="OVER";
		if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtypeNew]>0){
			$ior_Rate = $detailsData["ior_".$rtypeNew];
		}
	}elseif(in_array($rtype,array('HT0','HT1','HT2','HTOV'))){
		if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
			$ior_Rate = $detailsData["ior_".$rtype];
		}	
	}elseif(in_array($rtype,array('ODD','EVEN'))){ // 全场单双
		if($rtype=='ODD')	$rtypeNew="EOO";
		if($rtype=='EVEN') $rtypeNew="EOE";
		if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtypeNew]>0){
			$ior_Rate = $detailsData["ior_".$rtypeNew];
		}
	}elseif(in_array($rtype,array('HODD','HEVEN'))){ // 半场单双
		if($rtype=='HODD')	$rtypeNew="HEOO";
		if($rtype=='HEVEN') $rtypeNew="HEOE";
		if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtypeNew]>0){
			//$ior_Rate = $detailsData["ior_".$rtypeNew];
            $ior_Rate_arr=get_other_ioratio(GAME_POSITION,returnOddEvenRate($detailsData["ior_HEOO"]),returnOddEvenRate($detailsData["ior_HEOE"]),100);
            $ior_Rate[0] =returnOddEvenRate($ior_Rate_arr[0],'plus');
            $ior_Rate[1] =returnOddEvenRate($ior_Rate_arr[1],'plus');
            if($rtype=='HODD'){ //单
                $ior_Rate=$ior_Rate[0];
            }else{ // 双
                $ior_Rate=$ior_Rate[1];
            }
		}
	}
	
	if(!$ior_Rate){
		echo attention("$Order_Odd_changed_please_bet_again",$uid,$langx);
		exit;
	}
}

$result = mysqli_query($dbCenterMasterDbLink,$mysql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit;
}else{

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
	case "ODD": 
		$M_Place="(".$Order_Odd.")"; 
		$M_Rate=change_rate($open,$row["S_Single_Rate"]);
		$GMAX_SINGLE=$GMAX_SINGLE1; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT1; 
		$caption=$Order_Odd_Even_betting_order; 
		$linetype=5; 
		break; 
	case "EVEN": 
		$M_Place="(".$Order_Even.")"; 
		$M_Rate=change_rate($open,$row["S_Double_Rate"]);
		$GMAX_SINGLE=$GMAX_SINGLE1; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT1; 
		$caption=$Order_Odd_Even_betting_order; 
		$linetype=5; 
		break; 
	case "HODD": 
		$M_Place="(".$Order_Odd.")";
		$M_Rate=change_rate($open,$ior_Rate);
		$GMAX_SINGLE=$GMAX_SINGLE1; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT1; 
		$caption=$Order_Odd_Even_betting_order; 
		$linetype=15; 
		break; 
	case "HEVEN": 
		$M_Place="(".$Order_Even.")"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$GMAX_SINGLE=$GMAX_SINGLE1; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT1; 
		$caption=$Order_Odd_Even_betting_order; 
		$linetype=15; 
		break; 
	case "0~1": 
		$M_Place="(0~1)"; 
		//$M_Rate=change_rate($open,$row["S_0_1"]);
        $M_Rate=change_rate($open,$ior_Rate);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=6;
		break; 
	case "2~3": 
		$M_Place="(2~3)"; 
		//$M_Rate=change_rate($open,$row["S_2_3"]);
        $M_Rate=change_rate($open,$ior_Rate);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=6; 
		break; 
	case "4~6": 
		$M_Place="(4~6)"; 
		//$M_Rate=change_rate($open,$row["S_4_6"]);
        $M_Rate=change_rate($open,$ior_Rate);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=6; 
		break; 
	case "OVER": 
		$M_Place="(7UP)"; 
		//$M_Rate=change_rate($open,$row["S_7UP"]);
        $M_Rate=change_rate($open,$ior_Rate);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=6; 
		break; 
	case "HT0": 
		$M_Place="0"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=46;
		break; 
	case "HT1": 
		$M_Place="1"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=46;
		break; 
	case "HT2": 
		$M_Place="2"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=46;
		break; 
	case "HTOV": 
		$M_Place="3或以上"; 
		$M_Rate=change_rate($open,$ior_Rate);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=46;
		break; 
	} 
	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and MID='$gid' and linetype='$linetype' and (Active=1 or Active=11)";
	$result = mysqli_query($dbLink,$havesql);
	$haverow = mysqli_fetch_assoc($result); 
	$have_bet=$haverow['BetScore']+0;
    $sql = "select EO,T from ".DBPREFIX."match_league where  $m_league='$M_League'";
    $result = mysqli_query($dbLink,$sql);
    $league = mysqli_fetch_assoc($result);
if ($rtype=='ODD' or $rtype=='EVEN'){
    //$bettop=$league['EO'];
	$bettop=$GSINGLE_CREDIT;
}else{
    //$bettop=$league['T'];
	$bettop=$GSINGLE_CREDIT;
}
	if ($bettop<$GSINGLE_CREDIT){
		$bettop_money=$GSINGLE_CREDIT;
	}else{
		$bettop_money=$GSINGLE_CREDIT;
	}
	
	if($rtype=='EVEN' or $rtype=='ODD'){
		$gametype=$U_31;
		if(substr($_REQUEST['wtype'],0,1)=="H"){
			$gametype=$U_31."-".$U_00;
		}
	}elseif($rtype=='HEVEN' or $rtype=='HODD'){
		$gametype=$U_31;
		if(substr($_REQUEST['wtype'],0,1)=="H"){
			$gametype=$U_OE."-".$U_00;
		}
	}elseif(substr($_REQUEST['wtype'],0,1)=="H"){
		$gametype=$U_41H;
	}else{ 
		$gametype=$U_41;
	}

	if(strlen($M_Rate)==0){
		echo attention("$Order_This_match_is_closed_Please_try_again!!",$uid,$langx);
		exit;
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="/style/member/mem_order_ft.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
  <script language="JavaScript" src="../../../js/jquery.js"></script><script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<?php if($rtype=='EVEN' or $rtype=='ODD'){ ?>
    <script language="JavaScript" src="../../../js/ft_t_order.js?v=<?php echo AUTOVER; ?>"></script>
<?php }else{?>
    <script language="JavaScript" src="../../../js/ft_pd_order.js?v=<?php echo AUTOVER; ?>"></script>
<?php } ?>
<body id="OFT" class="bodyset bodyset_<?php echo TPL_FILE_NAME;?>" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<form name="LAYOUTFORM" action="/app/member/FT_order/FT_order_finish.php" method="post" onSubmit="return false">
  <div class="ord">
   	<div class="title"><h1><span class="info">i</span><?php echo $gametype; ?></h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="hidden" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>

	<div class="main">
      <div class="leag"><?php echo $M_League?></div>

      <div class="teamName"><span class="tName"><?php echo $MB_Team?> VS <?php echo $TG_Team?></span></div>
      <p class="team"><em><?php echo $M_Place?></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>

      <p class="error" style="display: none;"></p>
      <div class="betdata">
          <p class="amount"><input name="gold" type="text" class="txt" id="gold" onKeyPress="return CheckKey(event)" onKeyUp="return CountWinGold_dy_ds_dyh()" size="8" maxlength="10" placeholder="投注额"><span class="clean_bet_money" id="betClear"></span></p>
          <p class="mayWin"><span class="bet_txt">可赢金额：</span><font id="pc">0</font></p>

           <div class="betAmount"> </div> <p class="minBet">限额：<span class="bet_txt">最低<?php echo $GMIN_SINGLE?> / 最高<?php echo $GSINGLE_CREDIT?></span></p>
    </div>
     
    </div>
      <div id="gWager" style="display: none;position: absolute;"></div>
      <div id="gbutton" style="display: block;position: absolute;"></div>
  <div class="betBox">

      <input type="button" name="Submit" value="确定交易" onClick="CountWinGold_dy_ds_dyh();return SubChk();" class="yes">
      <input type="button" name="btnCancel" value="取消" onClick="parent.close_bet();" class="no">
      <p class="auto"><input type="checkbox" id="autoOdd" name="autoOdd" onClick="onclickReloadAutoOdd()" checked value="Y"><span class="auto_info" title="在方格里打勾表示，如果投注单里的任何选项在确认投注时赔率变佳，系统会无提示的继续进行下注。">自动接受较佳赔率</span></p>
  </div>
  </div>  
<input type="hidden" name="uid" value="<?php echo $uid?>">
<input type="hidden" name="active" value="<?php echo $active?>">
<input type="hidden" name="rtype" value="<?php echo $rtype?>">
<input type="hidden" name="ordertype" value="1">
<input type="hidden" name="line_type" value="<?php echo $linetype?>">
<input type="hidden" name="gid" value="<?php echo $gid?>">
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>">
<input type="hidden" id="ioradio_r_h" name="ioradio_r_h" value="<?php echo $M_Rate?>">
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
