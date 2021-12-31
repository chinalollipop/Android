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
$rtype=$_REQUEST['rtype'];
$odd_f_type=$_REQUEST['odd_f_type'];
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$sql = "select UserName,Money,CurType,Pay_Type,OpenType,Language from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);

$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);

$memname=$row['UserName'];
$credit=$row['Money'];
$curtype=$row['CurType'];
$pay_type=$row['Pay_Type'];
$btset=singleset('PD');
$GMIN_SINGLE=$btset[0];
$GMAX_SINGLE=TN_PD_Scene;
$GSINGLE_CREDIT=TN_PD_Bet;
$open=$row['OpenType'];
$langx=$row['Language'];
require ("../include/traditional.$langx.inc.php");


$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB2TG0,MB2TG1,MB3TG0,MB3TG1,MB3TG2,MB0TG2,MB1TG2,MB0TG3,MB1TG3,MB2TG3 from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and `M_Start`>now() and `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!='' and MB_Team_tw!='' and MB_Team_en!=''";
$result = mysqli_query($dbLink,$mysql);

$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit;
}else{
	
	if ($row['M_Date']==date('Y-m-d')){
		$active=4;
		$class="OTN";
		$caption=$Order_TN.$Order_Correct_Score_betting_order;
	}else{
		$active=44;
		$class="OTU";
		$caption=$Order_TN.$Order_Early_Market.$Order_Correct_Score_betting_order;
	}
	$M_League=$row['M_League'];
	$MB_Team=$row["MB_Team"];
	$TG_Team=$row["TG_Team"];
	$MB_Team=filiter_team($MB_Team);
	
	if ($rtype=="OVH"){
		$M_Place=$body_pd_up;
		$M_Sign="VS.";
		$M_Rate=$row['UP5'];
	}else{
		$M_Place="";
		$M_Sign=$rtype;
		$M_Sign=str_replace("H","(",$M_Sign);
		$M_Sign=str_replace("C",":",$M_Sign);
		$M_Sign=$M_Sign.")";
		$M_Rate=str_replace("H","MB",$rtype);
		$M_Rate=str_replace("C","TG",$M_Rate);
		$M_Rate=$row[$M_Rate];
	}

	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and MID='$gid' and linetype=4 and (Active=4 or Active=44)";
	$result = mysqli_query($dbLink,$havesql);
	$haverow = mysqli_fetch_assoc($result);
	$have_bet=$haverow['BetScore']+0;
	
    $sql = "select CS,PD from ".DBPREFIX."match_league where  $m_league='$M_League' and Type='TN'";
    $result = mysqli_query($dbLink,$sql);

    $league = mysqli_fetch_assoc($result);
    $bettop=$league['PD'];
	
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
<body id="OFT" class="bodyset" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<script language="JavaScript" src="../../../js/jquery.js"></script><script language="JavaScript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script language="JavaScript" src="../../../js/ft_pd_order.js?v=<?php echo AUTOVER; ?>"></script>
<form name="LAYOUTFORM" action="/app/member/TN_order/TN_order_finish.php" method="post" onSubmit="return false">
<div class="ord">
    <div class="title"><h1>網球 </h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="checkbox" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>
    <div class="main">
      <div class="leag"><?php echo $M_League?></div>
      <div class="gametype"><?php echo $U_42 ?></div>
      <div class="teamName"><span class="tName"><?php echo $MB_Team?> vs. <?php echo $TG_Team?></span></div>
      <p class="team"><em><?php echo $M_Sign?></em></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>
      <p class="auto"><input type="checkbox" id="autoOdd" name="autoOdd" onClick="onclickReloadAutoOdd()" checked value="Y"><span class="auto_info" title="在方格里打勾表示，如果投注单里的任何选项在确认投注时赔率变佳，系统会无提示的继续进行该下注。">自动接受较佳赔率</span></p>
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
<input type="hidden" name="line_type" value="4">
<input type="hidden" name="gid" value="<?php echo $gid?>">
<input type="hidden" name="type" value="">
<input type="hidden" name="rtype" value="<?php echo $rtype?>">
<input type="hidden" name="gnum" value="">
<input type="hidden" name="concede" value="<?php echo $M_Sign?>">
<!--<input type="hidden" id="ioradio_pd" name="ioradio_pd" value="--><?php//=$M_Rate?><!--">--><input type="hidden" id="ioradio_r_h" name="ioradio_r_h" value="<?php echo $M_Rate?>">
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
