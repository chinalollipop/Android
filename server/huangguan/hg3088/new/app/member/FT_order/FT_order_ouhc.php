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
$memname=$_SESSION['UserName'];
$pay_type=$_SESSION['Pay_Type'];
$open=$_SESSION['OpenType'];

$GMAX_SINGLE= Ft_Scene ;
$GSINGLE_CREDIT= Ft_Bet ;
$GMIN_SINGLE= Ft_Bet_Min ;

if ($change==1){
	$bet_title=$nobettitle;
}
if($gid%2==0){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate,S_0_1,S_2_3,S_4_6,S_7UP from `".DBPREFIX."match_sports` where `m_start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and $mb_team!=''";
}elseif($gid%2==1){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate,S_0_1,S_2_3,S_4_6,S_7UP from `".DBPREFIX."match_sports` where `m_start`>now() and `MID`=$gid-1 and Cancel!=1 and Open=1 and $mb_team!=''";
}

$result = mysqli_query($dbLink,$mysql);
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
	case "OUHO": 
		$M_Place="大"; 
		$gametype=$U_51.":".$MB_Team;
		$M_Rate=(isset($_REQUEST['radio']) && $_REQUEST['radio']>0)?$_REQUEST['radio']:0;
		$caption=$Order_Single_Ball_In_Ou_betting_order; 
		$linetype=16; 
		break; 
	case "OUHU": 
		$M_Place="小";
		$gametype=$U_51.":".$MB_Team; 
		$M_Rate=(isset($_REQUEST['radio']) && $_REQUEST['radio']>0)?$_REQUEST['radio']:0;
		$caption=$Order_Single_Ball_In_Ou_betting_order; 
		$linetype=16; 
		break; 
	case "OUCO": 
		$M_Place="小"; 
		$gametype=$U_51.":".$TG_Team;
		$M_Rate=(isset($_REQUEST['radio']) && $_REQUEST['radio']>0)?$_REQUEST['radio']:0; 
		$caption=$Order_Single_Ball_In_Ou_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=16; 
		break; 
	case "OUCU": 
		$M_Place="大"; 
		$gametype=$U_51.":".$TG_Team;
		$M_Rate=(isset($_REQUEST['radio']) && $_REQUEST['radio']>0)?$_REQUEST['radio']:0; 
		$caption=$Order_Single_Ball_In_Ou_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=16; 
		break;
	case "HOUHO": 
		$M_Place="大"; 
		$gametype=$U_51.":".$MB_Team."-".$U_00; 
		$M_Rate=(isset($_REQUEST['radio']) && $_REQUEST['radio']>0)?$_REQUEST['radio']:0;
		$caption=$Order_Single_Ball_In_Ou_betting_order; 
		$linetype=16; 
		break; 
	case "HOUHU": 
		$M_Place="小"; 
		$gametype=$U_51.":".$MB_Team."-".$U_00;
		$M_Rate=(isset($_REQUEST['radio']) && $_REQUEST['radio']>0)?$_REQUEST['radio']:0;
		$caption=$Order_Single_Ball_In_Ou_betting_order; 
		$linetype=16; 
		break; 
	case "HOUCO": 
		$M_Place="大";
		$gametype=$U_51.":".$TG_Team."-".$U_00;
		$M_Rate=(isset($_REQUEST['radio']) && $_REQUEST['radio']>0)?$_REQUEST['radio']:0; 
		$caption=$Order_Single_Ball_In_Ou_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=16; 
		break; 
	case "HOUCU": 
		$M_Place="大"; 
		$gametype=$U_51.":".$TG_Team."-".$U_00;
		$M_Rate=(isset($_REQUEST['radio']) && $_REQUEST['radio']>0)?$_REQUEST['radio']:0; 
		$caption=$Order_Single_Ball_In_Ou_betting_order; 
		$text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
		$linetype=16; 
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
<form name="LAYOUTFORM" action="/app/member/FT_order/FT_order_finish.php" method="post" onSubmit="return false">
  <div class="ord">
   	<div class="title"><h1>足球</h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="checkbox" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>

	<div class="main">
        <div class="ord_betArea">
          <div class="gametype"><?php echo $gametype;?></div>
          <div class="leag"><?php echo $M_League?></div>

          <div class="teamName"><span class="tName"><?php echo $MB_Team?> vs. <?php echo $TG_Team?></span></div>
          <p class="team"><em><?php echo $M_Place?></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>
        </div>
      <p class="auto"><input type="checkbox" id="autoOdd" name="autoOdd" onClick="onclickReloadAutoOdd()" checked value="Y"><span class="auto_info" title="在方格里打勾表示，如果投注单里的任何选项在确认投注时赔率变佳，系统会无提示的继续进行下注。">自动接受较佳赔率</span></p>
      <p class="error" style="display: none;"></p>
      <div class="betdata">
          <p class="amount">交易金额：<input name="gold" type="text" class="txt" id="gold" onKeyPress="return CheckKey(event)" onKeyUp="return CountWinGold()" size="8" maxlength="10" placeholder="投注额"><span class="clean_bet_money" id="betClear"></span></p>
          <p class="mayWin"><span class="bet_txt">可赢金额：</span><font id="pc">0</font></p>
          <p class="minBet"><span class="bet_txt">单注最低：</span><?php echo $GMIN_SINGLE?></p>
          <p class="maxBet"><span class="bet_txt">单注最高：</span><?php echo $GSINGLE_CREDIT?></p> <div class="betAmount"> </div>
    </div>
     
    </div>
      <div id="gWager" style="display: none;position: absolute;"></div>
      <div id="gbutton" style="display: block;position: absolute;"></div>
  <div class="betBox">
    <input type="button" name="btnCancel" value="取消" onClick="parent.close_bet();" class="no">
    <input type="button" name="Submit" value="确定交易" onClick="CountWinGold();return SubChk();" class="yes">
  </div>
  </div>  
<input type="hidden" name="uid" value="<?php echo $uid?>">
<input type="hidden" name="active" value="<?php echo $active?>">
<input type="hidden" name="rtype" value="<?php echo $rtype?>">
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
</form>
</body>
<SCRIPT LANGUAGE="JavaScript">document.all.gold.focus();</script>
</html>
<?php 
} 
?>