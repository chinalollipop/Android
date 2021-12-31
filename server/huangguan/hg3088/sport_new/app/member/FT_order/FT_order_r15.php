<?php
session_start();
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";

require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
require ("../include/define_function_list.inc.php");

$uid=(isset($_REQUEST['uid']) && $_REQUEST['uid'])? $_REQUEST['uid'] :$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$gid=$_REQUEST['gid'];
$type=$_REQUEST['type'];
$wtype=$_REQUEST['wtype'];
$rtype=$_REQUEST['rtype'];
$gnum=$_REQUEST['gnum'];
$strong=$_REQUEST['strong'];
$odd_f_type=$_REQUEST['odd_f_type'];
$error_flag=$_REQUEST['error_flag'];
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

$GMAX_SINGLE=FT_R_Scene;	//赔率diff
$GSINGLE_CREDIT=FT_R_Bet;	//赔率diff

if ($error_flag==1){
	$bet_title="<tt>".$Order_Odd_changed_please_bet_again."</tt>";
}
$btset=singleset('R');
$GMIN_SINGLE=$btset[0];

$mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and $mb_team!=''";
$resultL = mysqli_query($dbLink,$mysqlL);
$couL=mysqli_num_rows($resultL);
if($couL==0){
    echo attention("$Order_This_match_is_closed_Please_try_again", $uid, $langx);
    exit;
}

$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeR from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
$result = mysqli_query($dbCenterSlaveDbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);

$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
$rowMore = mysqli_fetch_assoc($moreRes);
$couMore = mysqli_num_rows($moreRes);
/*var_dump($cou);
var_dump($couMore);*/
if($cou==0 || $couMore==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit;
}else{
	$detailsArr = json_decode($rowMore['details'],true);
	$detailsData =$detailsArr[$gid];
	if($detailsData["sw_".$wtype]=="Y" && $detailsData["ior_".$wtype."H"]>0 && $detailsData["ior_".$wtype."C"]>0){
		$ior_Rate_H = $detailsData["ior_".$wtype."H"]; 
		$ior_Rate_C = $detailsData["ior_".$wtype."C"]; 
	}
	
	if(!$ior_Rate_H || !$ior_Rate_C){
	    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	    exit;
	}
	if ($row['M_Date']==date('Y-m-d')){
		$active=1;
		$class="OFT";
		$caption=$Order_FT.$Order_15_Min_betting_order;
	}else{
		$active=11;
		$class="OFU";
		$caption=$Order_FT.$Order_15_Min_betting_order;
	}
	$M_League=$row['M_League'];
	$MB_Team=$row["MB_Team"];
	$TG_Team=$row["TG_Team"];
	$MB_Team=filiter_team($MB_Team);
	$rate = get_other_ioratio($odd_f_type,$ior_Rate_H,$ior_Rate_C,100);
	
	switch ($type){
	case "H":
		$M_Place=$MB_Team;
		$M_Place.=$strong=="H" ? " ".$detailsData['ratio_'.strtolower($wtype)]:'';
		$M_Rate = change_rate($open,$rate[0]);
		$mtype='RH';
		break;
	case "C":
		$M_Place=$TG_Team;
		$M_Place.=$strong=="C" ? " ".$detailsData['ratio_'.strtolower($wtype)]:'';
		$M_Rate = change_rate($open,$rate[1]);
		$mtype='RC';
		break;
	}
	if ($row['ShowTypeR']=='C'){
		$Team=$MB_Team;
		$MB_Team=$TG_Team;
		$TG_Team=$Team;
	}
	
	if($M_Rate==0){
	    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	    exit;
	}
	
	//var_dump($M_Rate);
	//die();
	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and MID='$gid' and linetype=2 and Mtype='$mtype' and (Active=1 or Active=11)";
	$result = mysqli_query($dbLink,$havesql);
	$haverow = mysqli_fetch_assoc($result);
	$have_bet=$haverow['BetScore']+0;

    $sql = "select CS,R from ".DBPREFIX."match_league where  $m_league='$M_League'";
    $result = mysqli_query($dbLink,$sql);
    $league = mysqli_fetch_assoc($result);
    $mmb_team=explode("[",$row['MB_Team']);
    if ($mmb_team[1]==$Special0 or $mmb_team[1]==$Special1 or $mmb_team[1]==$Special2 or $mmb_team[1]==$Special3 or $mmb_team[1]==$Special4){
        $bettop=$league['CS'];
    }else{
        //$bettop=$league['R'];
		$bettop=$GSINGLE_CREDIT;
    }
	
	if ($odd_f_type=='E'){
		$count=1;
	}else{
		$count=0;
	}
	if ($GSINGLE_CREDIT>=500){
	    if ($M_Rate-$count<=1){
		    $num=1;
	    }else if ($M_Rate-$count>1 and $M_Rate-$count<=1.05){
		    $num=0.95;
		}else if ($M_Rate-$count>1.05 and $M_Rate-$count<=1.1){
		    $num=0.9;
		}else if ($M_Rate-$count>1.1 and $M_Rate-$count<=1.15){
		    $num=0.85;
	    }else if ($M_Rate-$count>1.15 and $M_Rate-$count<=1.2){
		    $num=0.8;
		}else if ($M_Rate-$count>1.2 and $M_Rate-$count<=1.25){
		    $num=0.75;
		}else if ($M_Rate-$count>1.25 and $M_Rate-$count<=1.3){
		    $num=0.7;
	    }else if ($M_Rate-$count>1.3 and $M_Rate-$count<=1.35){
		    $num=0.65;
	    }else if ($M_Rate-$count>1.35 and $M_Rate-$count<=1.4){
		    $num=0.6;
		}else if ($M_Rate-$count>1.4 and $M_Rate-$count<=1.45){
		    $num=0.55;
	    }else if ($M_Rate-$count>1.45 and $M_Rate-$count<=1.5){
		    $num=0.5;
		}else if ($M_Rate-$count>1.5){
		    $num=0.45;
	    }
		$number=100;
	}else{
		$num=1;
		$number=1;
	}
	if ($bettop<$GSINGLE_CREDIT){
		$bettop_money=$GSINGLE_CREDIT;
	}else{
		$bettop_money=floor($GSINGLE_CREDIT*$num/$number)*$number;
	}
		
	if(substr($_REQUEST['wtype'],0,1)=="A"){ $gametype=$U_74.':'.$U_74A.'-'.$U_R; }
	if(substr($_REQUEST['wtype'],0,1)=="B"){ $gametype=$U_74.':'.$U_74B.'-'.$U_R; }
	if(substr($_REQUEST['wtype'],0,1)=="C"){ $gametype=$U_74.':'.$U_74C.'-'.$U_R; }
	if(substr($_REQUEST['wtype'],0,1)=="D"){ $gametype=$U_74.':'.$U_74D.'-'.$U_R; }
	if(substr($_REQUEST['wtype'],0,1)=="E"){ $gametype=$U_74.':'.$U_74E.'-'.$U_R; }
	if(substr($_REQUEST['wtype'],0,1)=="F"){ $gametype=$U_74.':'.$U_74F.'-'.$U_R; }
	
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

<script type="text/javascript" src="../../../js/jquery.js"></script> <script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/football_order.js?v=<?php echo AUTOVER; ?>"></script>
<body id="OFT" class="bodyset bodyset_<?php echo TPL_FILE_NAME;?>" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<form name="LAYOUTFORM" action="/app/member/FT_order/FT_order_finish.php" method="post" onSubmit="return false">
  <div class="ord">
   	<div class="title"><h1><span class="info">i</span><?php echo $gametype; ?></h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="hidden" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>
    <div class="main">
      <div class="leag"><?php echo $M_League?></div>

      <div class="teamName"><span class="tName"><?php echo $MB_Team?> <span class="radio"><span class="radio"><?php echo $Sign?></span></span> <?php echo $TG_Team?></span></div>
      <p class="team"><em><?php echo $M_Place?></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>

      <p class="error" style="display: none;"></p>
      <div class="betdata">
          <p class="amount"><input name="gold" type="text" class="txt" id="gold" onKeyPress="return CheckKey(event)" onKeyUp="return CountWinGold()" size="8" maxlength="10" placeholder="投注额"><span class="clean_bet_money" id="betClear"></span></p>
          <p class="mayWin"><span class="bet_txt">可赢金额：</span><font id="pc">0</font></p>

           <div class="betAmount"> </div> <p class="minBet">限额：<span class="bet_txt">最低<?php echo $GMIN_SINGLE?> / 最高<?php echo $GSINGLE_CREDIT?></span></p>
    </div>
    </div>
  <div id="gWager" style="display: none;position: absolute;"></div>
  <div id="gbutton" style="display: block;position: absolute;"></div>
  <div class="betBox">
      <input type="button" name="Submit" value="确定交易" onClick="CountWinGold();return SubChk();" class="yes">
      <input type="button" name="btnCancel" value="取消" onClick="parent.close_bet();" class="no">
      <p class="auto"><input type="checkbox" id="autoOdd" name="autoOdd" onClick="onclickReloadAutoOdd()" checked value="Y"><span class="auto_info" title="在方格里打勾表示，如果投注单里的任何选项在确认投注时赔率变佳，系统会无提示的继续进行下注。">自动接受较佳赔率</span></p>
  </div>
</div>
  <div id="gfoot" style="display: block;position: absolute;"></div>

<input type="hidden" name="uid" value="<?php echo $uid?>">
<input type="hidden" name="active" value="<?php echo $active?>">
<input type="hidden" name="strong" value="<?php echo $strong?>">
<input type="hidden" name="line_type" value="52">
<input type="hidden" name="gid" value="<?php echo $gid?>">
<input type="hidden" name="type" value="<?php echo $type?>">
<input type="hidden" name="gnum" value="<?php echo $gnum?>">
<input type="hidden" name="concede_r" value="-1">
<input type="hidden" name="radio_r" value="-50">
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
<input type="hidden" name="rtype" value="<?php echo $rtype?>">
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
