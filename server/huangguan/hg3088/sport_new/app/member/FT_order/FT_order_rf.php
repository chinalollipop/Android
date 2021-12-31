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
require ("../include/curl_http.php");

$uid=(isset($_REQUEST['uid']) && $_REQUEST['uid'])? $_REQUEST['uid'] :$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$gid=$_REQUEST['gid'];
$wtype=$_REQUEST['wtype'];
$rtype=$_REQUEST['rtype'];
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

$btset=singleset('F');
$GMIN_SINGLE=$btset[0];
$GMAX_SINGLE=FT_F_Scene;
$GSINGLE_CREDIT=FT_F_Bet;

if ($change==1){
	$bet_title=$nobettitle;
}

$mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Cancel!=1 and Open=1 and $mb_team!=''";
$resultL = mysqli_query($dbLink,$mysqlL);
$couL=mysqli_num_rows($resultL);
if($couL==0){
    echo attention("$Order_This_match_is_closed_Please_try_again", $uid, $langx);
    exit;
}

$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MBMB,MBFT,MBTG,FTMB,FTFT,FTTG,TGMB,TGFT,TGTG from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";
$result = mysqli_query($dbCenterSlaveDbLink,$mysql);
$row = mysqli_fetch_assoc($result);
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
		if($detailsData["sw_".$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
			$ioradio_r_h = $detailsData["ior_".$rtype]; 
			if(!$ioradio_r_h){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit();	
			}
		}	
	}else{
		if(in_array($rtype,array('RFHN','RFHC','RFNH','RFNN','RFNC','RFCH','RFCN','RFCC','RFHH'))){
			$files =str_replace("F","",$rtype);
		    $files =str_replace("H","MB",$files); 
		    $files =str_replace("C","TG",$files);
			$files =str_replace("N","FT",$files);
			
			$rbExpandRes = mysqli_query($dbLink,"select $files from ".DBPREFIX."match_sports_rb_expand where `MID`='$gid'");
			$rowExpandRes = mysqli_fetch_assoc($rbExpandRes);
			
			/*echo '<pre>';
			print_r($rowExpandRes);
			echo '<pre>';*/
			
			$couExpandRes = mysqli_num_rows($rbExpandRes);
			$ioradio_r_h = $rowExpandRes[$files];
			
			if($couExpandRes==0 || !$ioradio_r_h){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit;
			}	
		}
	}
	
	if ($row['M_Date']==date('Y-m-d')){
		$active=1;
		$class="OFT";
		$caption=$Order_FT.$Order_Half_Full_Time_betting_order;
	}else{
		$active=11;
		$class="OFU";
		$caption=$Order_FT.$Order_Early_Market.$Order_Half_Full_Time_betting_order;
	}
	$M_League=$row['M_League'];
	$TG_Team=$row["TG_Team"];
	$MB_Team=filiter_team($row["MB_Team"]);
	switch ($rtype){
		case "FHH":
			$M_Place=$MB_Team.'&nbsp;/&nbsp;'.$MB_Team;
			$M_Rate=change_rate($open,$row["MBMB"]);
			break;
		case "FHN":
			$M_Place=$MB_Team.'&nbsp;/&nbsp;'.$Draw;
			$M_Rate=change_rate($open,$row["MBFT"]);
			break;
		case "FHC":
			$M_Place=$MB_Team.'&nbsp;/&nbsp;'.$TG_Team;
			$M_Rate=change_rate($open,$row["MBTG"]);
			break;
		case "FNH":
			$M_Place=$Draw.'&nbsp;/&nbsp;'.$MB_Team;
			$M_Rate=change_rate($open,$row["FTMB"]);
			break;
		case "FNN":
			$M_Place=$Draw.'&nbsp;/&nbsp;'.$Draw;	
			$M_Rate=change_rate($open,$row["FTFT"]);	
			break;
		case "FNC":
			$M_Place=$Draw.'&nbsp;/&nbsp;'.$TG_Team;	
			$M_Rate=change_rate($open,$row["FTTG"]);		
			break;
		case "FCH":
			$M_Place=$TG_Team.'&nbsp;/&nbsp;'.$MB_Team;		
			$M_Rate=change_rate($open,$row["TGMB"]);		
			break;
		case "FCN":
			$M_Place=$TG_Team.'&nbsp;/&nbsp;'.$Draw;
			$M_Rate=change_rate($open,$row["TGFT"]);		
			break;
		case "FCC":
			$M_Place=$TG_Team.'&nbsp;/&nbsp;'.$TG_Team;
			$M_Rate=change_rate($open,$row["TGTG"]);		
			break;
		case "RFHH":
			$M_Place=$MB_Team.'&nbsp;/&nbsp;'.$MB_Team;
			$M_Rate=change_rate($open,$ioradio_r_h);		
			break;
		case "RFHN":
			$M_Place=$MB_Team.'&nbsp;/&nbsp;'.$Draw;
			$M_Rate=change_rate($open,$ioradio_r_h);		
			break;
		case "RFHC":
			$M_Place=$MB_Team.'&nbsp;/&nbsp;'.$TG_Team;
			$M_Rate=change_rate($open,$ioradio_r_h);		
			break;
		case "RFNH":
			$M_Place=$Draw.'&nbsp;/&nbsp;'.$MB_Team;
			$M_Rate=change_rate($open,$ioradio_r_h);		
			break;
		case "RFNN":
			$M_Place=$Draw.'&nbsp;/&nbsp;'.$Draw;		
			$M_Rate=change_rate($open,$ioradio_r_h);		
			break;
		case "RFNC":
			$M_Place=$Draw.'&nbsp;/&nbsp;'.$TG_Team;		
			$M_Rate=change_rate($open,$ioradio_r_h);		
			break;
		case "RFCH":
			$M_Place=$TG_Team.'&nbsp;/&nbsp;'.$MB_Team;		
			$M_Rate=change_rate($open,$ioradio_r_h);		
			break;
		case "RFCN":
			$M_Place=$TG_Team.'&nbsp;/&nbsp;'.$Draw;
			$M_Rate=change_rate($open,$ioradio_r_h);		
			break;
		case "RFCC":
			$M_Place=$TG_Team.'&nbsp;/&nbsp;'.$TG_Team;
			$M_Rate=change_rate($open,$ioradio_r_h);		
			break;
	}
	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and MID='$gid' and linetype=7 and (Active=1 or Active=11)";
	$result = mysqli_query($dbLink,$havesql);
	$haverow = mysqli_fetch_assoc($result);
	$have_bet=$haverow['BetScore']+0;
	
    $sql = "select CS,F from ".DBPREFIX."match_league where  $m_league='$M_League'";
    $result = mysqli_query($dbLink,$sql);
    $league = mysqli_fetch_assoc($result);
    //$bettop=$league['F'];
	$bettop=$GSINGLE_CREDIT;
	
	if ($bettop<$GSINGLE_CREDIT){
		$bettop_money=$GSINGLE_CREDIT;
	}else{
		$bettop_money=$GSINGLE_CREDIT;
	}
	
	if(strpos($rtype,"R")>-1){
		$method = "(".$Running_Ball.") ".$U_09;
		$line_type=107;
		$actionUrl="/app/member/FT_order/FT_order_re_finish.php";
	}else{
		$method = $U_09;
		$line_type=7;
		$actionUrl="/app/member/FT_order/FT_order_finish.php";
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
  <script language="JavaScript" src="../../../js/jquery.js"></script><script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script language="JavaScript" src="../../../js/football_order_m.js?v=<?php echo AUTOVER; ?>"></script>
<body id="OFT" class="bodyset bodyset_<?php echo TPL_FILE_NAME;?>" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<form name="LAYOUTFORM" action="<?php echo $actionUrl; ?>" method="post" onSubmit="return false">
  <div class="ord">
   	<div class="title"><h1><span class="info">i</span><?php echo $method ?></h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="hidden" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>
    <div class="main">
      <div class="leag"><?php echo $M_League?></div>

      <div class="teamName"><span class="tName"><?php echo $MB_Team?> VS <?php echo $TG_Team?></span></div>
      <p class="team"><em><?php echo $M_Place?></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>

      <p class="error" style="display: none;"></p>
      <div class="betdata">
          <p class="amount">交易金額：<input name="gold" type="text" class="txt" id="gold" onKeyPress="return CheckKey(event)" onKeyUp="return CountWinGold_dy_ds_dyh()" size="8" maxlength="10" placeholder="投注额"><span class="clean_bet_money" id="betClear"></span></p>
          <p class="mayWin"><span class="bet_txt">可贏金額：</span><font id="pc">0</font></p>

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
<input type="hidden" name="line_type" value="<?php echo $line_type;?>">
<input type="hidden" name="gid" value="<?php echo $gid?>">
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>">
<input type="hidden" name="type" value="">
<input type="hidden" name="rtype" value="<?php echo $rtype?>">
<input type="hidden" name="gnum" value="">
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
