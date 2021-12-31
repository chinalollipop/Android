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

$btset=singleset('T');
$GMIN_SINGLE=$btset[0];
$GMAX_SINGLE1= FT_EO_Scene ;
$GSINGLE_CREDIT1= FT_EO_Bet ;
$GMAX_SINGLE2= FT_T_Scene ;
$GSINGLE_CREDIT2= FT_T_Bet ;

if ($change==1){
	$bet_title=$nobettitle;
}

if($gid%2==0){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate_RB,S_Double_Rate_RB from `".DBPREFIX."match_sports` where `MID`='$gid' and Cancel!=1 and Open=1 and $mb_team!=''";
}elseif($gid%2==1){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate_RB,S_Double_Rate_RB from `".DBPREFIX."match_sports` where `MID`=$gid-1 and Cancel!=1 and Open=1 and $mb_team!=''";
}
$result = mysqli_query($dbMasterLink,$mysql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit;
}else{
	if($_REQUEST['id']&&$_REQUEST['id']>0){
		$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
		$rowMore = mysqli_fetch_assoc($moreRes);
		$couMore = mysqli_num_rows($moreRes);
		$detailsArr = json_decode($rowMore['details'],true);
		$detailsData =$detailsArr[$gid];
		switch ($rtype){
			  	case 'R0~1':	$keyY="sw_RT"; $iorK = "ior_RT01"; break;//全场
			  	case 'R2~3':	$keyY="sw_RT"; $iorK = "ior_RT23"; break;
			  	case 'R4~6':	$keyY="sw_RT"; $iorK = "ior_RT46"; break;
			  	case 'ROVER':   $keyY="sw_RT"; $iorK = "ior_ROVER"; break;
			  	case 'HRT0':	$keyY="sw_HRT"; $iorK = "ior_HRT0"; break;//半场
			  	case 'HRT1':	$keyY="sw_HRT"; $iorK = "ior_HRT1"; break;
			  	case 'HRT2':	$keyY="sw_HRT"; $iorK = "ior_HRT2"; break;
			  	case 'HRTOV':   $keyY="sw_HRT"; $iorK = "ior_HRTOV"; break;
			  	case 'RODD':	$keyY="sw_REO"; $iorK = "ior_REOO"; break;
			  	case 'REVEN':    $keyY="sw_REO"; $iorK = "ior_REOE"; break;
			  	case 'HRODD':	$keyY="sw_HREO"; $iorK = "ior_HREOO"; break;
			  	case 'HREVEN':    $keyY="sw_HREO"; $iorK = "ior_HREOE"; break;
				default:	    $keyY='sw_'.$wtype; break;
		}
		if($detailsData[$keyY]=="Y" && $detailsData[$iorK]>0){
			$ioradio_r_h = $detailsData[$iorK]; 
			if(!$ioradio_r_h){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit();	
			}
		}		
	}else{
		if($rtype=="RODD" || $rtype=="REVEN"){
			if($rtype=="RODD") $ioradio_r_h = $row['S_Single_Rate_RB'];
			if($rtype=="REVEN") $ioradio_r_h = $row['S_Double_Rate_RB'];
			if(!$ioradio_r_h){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit;
			}	
		}else{
			$rbExpandRes = mysqli_query($dbLink,"select RS_0_1 AS 'R0~1',RS_2_3 AS 'R2~3',RS_4_6 AS 'R4~6',RS_7UP AS 'ROVER' from ".DBPREFIX."match_sports_rb_expand where `MID`='$gid'");
			$rowExpandRes = mysqli_fetch_assoc($rbExpandRes);
			$couExpandRes = mysqli_num_rows($rbExpandRes);
			$ioradio_r_h = $rowExpandRes[$rtype];
			if($couExpandRes==0 || !$ioradio_r_h){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit;
			}	
		}
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
	case "RODD": 
		$M_Place="(".$Order_Odd.")"; 
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE1; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT1; 
		$caption=$Order_Odd_Even_betting_order; 
		$linetype=105; 
		break; 
	case "REVEN": 
		$M_Place="(".$Order_Even.")"; 
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE1; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT1; 
		$caption=$Order_Odd_Even_betting_order; 
		$linetype=105; 
		break; 
	case "HRODD": 
		$M_Place="(".$Order_Odd.")"; 
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE1; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT1; 
		$caption=$Order_Odd_Even_betting_order; 
		$linetype=205; 
		break; 
	case "HREVEN": 
		$M_Place="(".$Order_Even.")"; 
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE1; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT1; 
		$caption=$Order_Odd_Even_betting_order; 
		$linetype=205; 
		break; 
	case "R0~1": 
		$M_Place="R(0~1)";
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=106; 
		break; 
	case "R2~3": 
		$M_Place="R(2~3)";
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=106; 
		break; 
	case "R4~6": 
		$M_Place="R(4~6)";
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=106; 
		break; 
	case "ROVER": 
		$M_Place="R(7UP)";
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=106; 
		break; 
	case "HRT0": 
		$M_Place="0"; 
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=206; 
		break; 
	case "HRT1": 
		$M_Place="1"; 
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=206; 
		break; 
	case "HRT2": 
		$M_Place="2"; 
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=206; 
		break; 
	case "HRTOV": 
		$M_Place="3或以上"; 
		$M_Rate=change_rate($open,$ioradio_r_h);
		$GMAX_SINGLE=$GMAX_SINGLE2; 
		$GSINGLE_CREDIT=$GSINGLE_CREDIT2; 
		$caption=$Order_Total_Goals_betting_order;
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=206; 
		break; 
	} 
	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and MID='$gid' and linetype='$linetype' and (Active=1 or Active=11)";
	$result = mysqli_query($dbLink,$havesql); 
	$haverow = mysqli_fetch_assoc($result); 
	$have_bet=$haverow['BetScore']+0; 
	
if ($rtype=='ODD' or $rtype=='EVEN'){
    $sql = "select CS,EO from ".DBPREFIX."match_league where  $m_league='$M_League'";
    $result = mysqli_query($dbLink,$sql);
    $league = mysqli_fetch_assoc($result);
    //$bettop=$league['EO'];
	$bettop=$GSINGLE_CREDIT;
}else{
    $sql = "select CS,T from ".DBPREFIX."match_league where  $m_league='$M_League'";
    $result = mysqli_query($dbLink,$sql);
    $league = mysqli_fetch_assoc($result);
    //$bettop=$league['T'];
	$bettop=$GSINGLE_CREDIT;
}
	if ($bettop<$GSINGLE_CREDIT){
		$bettop_money=$GSINGLE_CREDIT;
	}else{
		$bettop_money=$GSINGLE_CREDIT;
	}
	
	if($rtype=='REVEN' or $rtype=='RODD'){
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
	
	$gametype =	"(".$Running_Ball.") ".$gametype;
	
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
  <script language="JavaScript" src="../../../js/jquery.js"></script><script language="JavaScript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script language="JavaScript" src="../../../js/ft_pd_order.js?v=<?php echo AUTOVER; ?>"></script>
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
          <p class="amount">交易金额：<input name="gold" type="text" class="txt" id="gold" onKeyPress="return CheckKey(event)" onKeyUp="return CountWinGold_dy_ds_dyh()" size="8" maxlength="10" placeholder="投注额"><span class="clean_bet_money" id="betClear"></span></p>
          <p class="mayWin"><span class="bet_txt">可赢金额：</span><font id="pc">0</font></p>
          <p class="minBet"><span class="bet_txt">单注最低：</span><?php echo $GMIN_SINGLE?></p>
          <p class="maxBet"><span class="bet_txt">单注最高：</span><?php echo $GSINGLE_CREDIT?></p> <div class="betAmount"> </div>
    </div>
     
    </div>
      <div id="gWager" style="display: none;position: absolute;"></div>
      <div id="gbutton" style="display: block;position: absolute;"></div>
  <div class="betBox">
      <input type="button" name="Submit" value="确定交易" onClick="CountWinGold_dy_ds_dyh();return SubChk();" class="yes"><input type="button" name="btnCancel" value="取消" onClick="parent.close_bet();" class="no">

  </div>
  </div>  
<input type="hidden" name="uid" value="<?php echo $uid?>">
<input type="hidden" name="active" value="<?php echo $active?>">
<input type="hidden" name="rtype" value="<?php echo $rtype?>">
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>">
<input type="hidden" name="ordertype" value="1">
<input type="hidden" name="line_type" value="<?php echo $linetype?>">
<input type="hidden" name="gid" value="<?php echo $gid?>">
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
<?php 
if(isset($_REQUEST['radio']) && $_REQUEST['radio']>0 ){ 
?>
<input type="hidden" name="source" value="authority">
<?php 
}
?>
<?php 
if(isset($_REQUEST['flag']) && $_REQUEST['flag']=="all" ){ 
?>
<input type="hidden" name="dataSou" value="interface">
<?php 
}
?>
</form>
</body>
<SCRIPT LANGUAGE="JavaScript">document.all.gold.focus();</script>
</html>
<?php 
} 
?>