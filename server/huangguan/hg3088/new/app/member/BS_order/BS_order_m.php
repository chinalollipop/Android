<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
$uid=$_REQUEST['uid'];
$gid=$_REQUEST['gid'];
$type=$_REQUEST['type'];
$gnum=$_REQUEST['gnum'];
$odd_f_type=$_REQUEST['odd_f_type'];

$change=$_REQUEST['change'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	setcookie('login_uid','');
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$sql = "select UserName,Money,CurType,Pay_Type,BS_M_Scene,BS_M_Bet,OpenType,Language from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$memname=$row['UserName'];
$credit=$row['Money'];
$curtype=$row['CurType'];
$pay_type=$row['Pay_Type'];
$btset=singleset('M');
$GMIN_SINGLE=$btset[0];
$GMAX_SINGLE= BS_M_Scene ;
$GSINGLE_CREDIT= BS_M_Bet ;
$open=$row['OpenType'];
$langx=$row['Language'];
require ("../include/traditional.$langx.inc.php");
if ($change==1){
	$bet_title=$nobettitle;
}

$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate from `".DBPREFIX."match_sports` where `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and $mb_team!=''";
$result = mysqli_query($dbMasterLink,$mysql);

$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit;
}else{

	if ($row['M_Date']==date('Y-m-d')){
		$active=3;
		$class="OBS";
		$caption=$Order_BS.$Order_1_x_2_betting_order;
	}
	else{
		$active=33;
		$class="OBE";
		$caption=$Order_BS.$Order_Early_Market.$Order_1_x_2_betting_order;
	}
	$M_League=$row['M_League'];
	$MB_Team=$row["MB_Team"];
	$TG_Team=$row["TG_Team"];
	$MB_Team=filiter_team($MB_Team);
	switch ($type){
	case "H":
		$M_Place=$MB_Team;
		$M_Rate=change_rate($open,$row["MB_Win_Rate"]);
		$mtype='MH';
		break;
	case "C":
		$M_Place=$TG_Team;
		$M_Rate=change_rate($open,$row["TG_Win_Rate"]);
		$mtype='MC';
		break;
	case "N":
		$M_Place=$Draw;
		$M_Rate=change_rate($open,$row["M_Flat_Rate"]);
		$mtype='MN';
		break;
	}

	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and MID='$gid' and linetype=1 and Mtype='$mtype' and (Active=3 or Active=33)";
	$result = mysqli_query($dbLink,$havesql);
	$haverow = mysqli_fetch_assoc($result);
	$have_bet=$haverow['BetScore']+0;
	
    $sql = "select CS,OU,M from ".DBPREFIX."match_league where  $m_league='$M_League' and Type='BS'";
    $result = mysqli_query($dbLink,$sql);

    $league = mysqli_fetch_assoc($result);
    $bettop=$league['M'];
	
	if ($M_Rate==0){
        echo attention("$Order_Odd_changed_please_bet_again",$uid,$langx);
        // echo '<script>self.history.back();top.SI2_mem_index.body.body_var.location.reload();</script>';
	    exit;
	}
	if ($bettop<$GSINGLE_CREDIT){
		$bettop_money=$GSINGLE_CREDIT;
	}else{
		$bettop_money=$GSINGLE_CREDIT;
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="/style/member/mem_order_ft.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<script type="text/javascript" src="../../../js/jquery.js"></script> <script language="JavaScript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/football_order_m.js?v=<?php echo AUTOVER; ?>"></script>
<body id="OFT" class="bodyset" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<form name="LAYOUTFORM" action="/app/member/BS_order/BS_order_finish.php" method="post" onSubmit="return false">
  <div class="ord">
   	<div class="title"><h1>棒球</h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="checkbox" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>
    <div class="main">
        <div class="ord_betArea">
          <div class="gametype"><?php echo $U_30 ?></div>
          <div class="leag"><?php echo $M_League?></div>

          <div class="teamName"><span class="tName"><?php echo $MB_Team?> vs. <?php echo $TG_Team?></span></div>
          <p class="team"><em><?php echo $M_Place?></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>
        </div>
      <p class="auto"><input type="checkbox" id="autoOdd" name="autoOdd" onClick="onclickReloadAutoOdd()" checked value="Y"><span class="auto_info" title="在方格里打勾表示，如果投注单里的任何选项在确认投注时赔率变佳，系统会无提示的继续进行下注。">自动接受较佳赔率</span></p>
      <p class="error" style="display: none;"></p>
      <div class="betdata">
          <p class="amount">交易金额：<input name="gold" type="text" class="txt" id="gold" onKeyPress="return CheckKey(event)" onKeyUp="return CountWinGold()" size="8" maxlength="10"></p>
          <p class="mayWin"><span class="bet_txt">可赢金额：</span><font id="pc">0</font></p>
          <p class="minBet"><span class="bet_txt">单注最低：</span><?php echo $GMIN_SINGLE?></p>
          <p class="maxBet"><span class="bet_txt">单注最高：</span><?php echo $GSINGLE_CREDIT?></p> <div class="betAmount"> </div>
    </div>
    </div>
      <div id="gWager" style="display: none;position: absolute;"></div>
      <div id="gbutton" style="display: block;position: absolute;"></div>
      <div class="betBox">
          <input type="button" name="Submit" value="确定交易" onClick="CountWinGold();return SubChk();" class="yes"><input type="button" name="btnCancel" value="取消" onClick="parent.close_bet();" class="no">

      </div>
    </div>
      <div id="gfoot" style="display: block;position: absolute;"></div>
      <div class="ord" id="line_window" style="display: none;">
        <div class="betChk" id="gdiv_table">
          <span class="notice">*SHOW_STR*</span>
            <input type="button" name="wgSubmit" value="确定交易" onmousedown='Sure_wager();' class="yes"><input type="button" name="wgCancel" value="取消" onClick="Close_div();" class="no">

        </div>
      </div>
<input type="hidden" name="uid" value="<?php echo $uid?>">
<input type="hidden" name="active" value="<?php echo $active?>">
<input type="hidden" name="line_type" value="1">
<input type="hidden" name="gid" value="<?php echo $gid?>"> 
<input type="hidden" name="type" value="<?php echo $type?>">
<input type="hidden" name="gnum" value="<?php echo $gnum?>">
<input type="hidden" name="concede_h" value="1">
<input type="hidden" name="radio_h" value="100">
<input type="hidden" id="ioradio_r_h" name="ioradio_r_h" value="<?php echo $M_Rate?>">
<input type="hidden" name="gmax_single" value="<?php echo $bettop_money?>"> 
<input type="hidden" name="gmin_single" value="<?php echo $GMIN_SINGLE?>">
<input type="hidden" name="singlecredit" value="<?php echo $GMAX_SINGLE?>">
<input type="hidden" name="singleorder" value="<?php echo $GSINGLE_CREDIT?>">
<input type="hidden" name="restsinglecredit" value="<?php echo $have_bet?>">  
<input type="hidden" name="wagerstotal" value="<?php echo $GMAX_SINGLE?>"> 
<input type="hidden" name="restcredit" value="<?php echo $credit?>">
<input type="hidden" name="pay_type" value="<?php echo $pay_type?>">
<input type="hidden" name="odd_f_type" value="<?php echo $odd_f_type?>">
</form>
</body>
<SCRIPT LANGUAGE="JavaScript">document.all.gold.focus();</script>
</html>
<?php
}
?>