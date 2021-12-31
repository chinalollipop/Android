<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";

require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
require ("../include/define_function_list.inc.php");
require ("../include/curl_http.php");
$uid=(isset($_REQUEST['uid']) && $_REQUEST['uid'])? $_REQUEST['uid'] :$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$gid=$_REQUEST['gid'];
if($_REQUEST['id']&&$_REQUEST['id']>0){
	$rtype=$wtype=$_REQUEST['rtype'];
}else{
	$rtype=$wtype=$_REQUEST['rtype'];
	$wtype=$_REQUEST['wtype'];
}
$odd_f_type=$_REQUEST['odd_f_type'];
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
$memid=$_SESSION['userid'];

$btset=singleset('PD');
$GMIN_SINGLE=$btset[0];
$GMAX_SINGLE=FT_PD_Scene;
$GSINGLE_CREDIT=FT_PD_Bet;

if(!isset($GMAX_SINGLE)){
	$GMAX_SINGLE= Ft_Scene;
}

if(!isset($GSINGLE_CREDIT)){
	$GSINGLE_CREDIT= Ft_Bet ;
}

$btset=singleset('PD');
$GMIN_SINGLE=$btset[0];
if(!$GMIN_SINGLE){
	$GMIN_SINGLE = Ft_Bet_Min ;
}

if($gid%2 == 1){
    $mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!=''";
}else{
    //$mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid-1 and Cancel!=1 and Open=1 and MB_Team!=''";
    $mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!=''";
}
$resultL = mysqli_query($dbLink,$mysqlL);
$couL=mysqli_num_rows($resultL);
if($couL==0){
    echo attention("$Order_This_match_is_closed_Please_try_again", $uid, $langx);
    exit;
}

if($gid%2==0){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and MB_Team!=''";
}elseif($gid%2==1){
	//$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid-1 and Open=1 and MB_Team!=''";
    $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and MB_Team!=''";
}
$result = mysqli_query($dbCenterSlaveDbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
$rowMore = mysqli_fetch_assoc($moreRes);
$couMore = mysqli_num_rows($moreRes);

if($cou==0 && $couMore==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit;
}else{
	if($_REQUEST['id']&&$_REQUEST['id']>0){
		$detailsArr = json_decode($rowMore['details'],true);
		$detailsData =$detailsArr[$gid];
		$rtype= "H".$rtype;
		if($detailsData["sw_".$rtype]=="Y" && $detailsData["ior_".$rtype]>0){
			$ior_Rate = $detailsData["ior_".$rtype]; 
		}	
	}else{
		$rtype= "H".$rtype;
		if(in_array($rtype,array('HRH1C0','HRH2C0','HRH0C1','HRH0C2','HRH2C1','HRH1C2','HRH3C0','HRH0C3','HRH3C1','HRH1C3','HRH3C2',
			'HRH2C3','HRH4C0','HRH0C4','HRH4C1','HRH1C4','HRH4C2','HRH2C4','HRH4C3','HRH3C4','HRH0C0','HRH2C2','HRH1C1','HRH3C3','HRH4C4','HROVH'))){
		  	$rtype=substr($rtype,1);
			if($rtype=="ROVH"){
				$files = "RUP5";
			}else{
				$files = str_replace('H','MB',$rtype);
				$files = str_replace('C','TG',$files);
			}
	      	$files = "H".$files;
			$rbExpandRes = mysqli_query($dbLink,"select $files from ".DBPREFIX."match_sports_rb_expand where `MID`='$gid'");
			$rowExpandRes = mysqli_fetch_assoc($rbExpandRes);
			$ior_Rate = $rowExpandRes[$files];
		}
		$rtype= "H".$rtype;
	}

	if(!$ior_Rate){
		echo attention("$Order_Odd_changed_please_bet_again",$uid,$langx);
		exit;
	}
	
	$M_League=$row['M_League'];
	$MB_Team=$row["MB_Team"];
	$TG_Team=$row["TG_Team"];
	$MB_Team=filiter_team($MB_Team);
	
	if ($rtype=="ROVH"){
		$M_Place=$Order_Other_Score;
		$M_Sign=$Order_Other_Score." VS ";
		$M_Rate=change_rate($open,$ior_Rate);
	}else{
		$M_Place="";
		$M_Sign=$rtype;
		$M_Sign=str_replace("H","(",$M_Sign);
		$M_Sign=str_replace("C",":",$M_Sign);
		$M_Sign=$M_Sign.")";
		$M_Rate=change_rate($open,$ior_Rate);
	}
	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and MID='$gid' and linetype=14 and (Active=1 or Active=11)";
	$result = mysqli_query($dbLink,$havesql);
	$haverow = mysqli_fetch_assoc($result);
	$have_bet=$haverow['BetScore']+0;
	
    $sql = "select CS,PD from ".DBPREFIX."match_league where  $m_league='$M_League'";
    $result = mysqli_query($dbLink,$sql);
    $league = mysqli_fetch_assoc($result);
    //$bettop=$league['PD'];
	$bettop=$GSINGLE_CREDIT;
	
	if ($bettop<$GSINGLE_CREDIT){
		$bettop_money=$GSINGLE_CREDIT;
	}else{
		$bettop_money=$GSINGLE_CREDIT;
	}
	
	if(substr($rtype,0,1)=="H"){
		$gametype=$U_42H;
	}else{
		$gametype=$U_42;
	}
	$gametype = '('.$Running_Ball.') '.$gametype;
	
	if(strlen($M_Rate)==0 || $M_Rate==0){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}
	
?>
<html>
<head>
<title>ft_hpd_order</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--<link rel="stylesheet" href="/style/member/mem_order--><?php//=$css?><!--.css?v=<?php echo AUTOVER; ?>" type="text/css">-->
    <link href="/style/member/mem_order_ft.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<body id="<?php echo $class?>" class="bodyset bodyset_<?php echo TPL_FILE_NAME;?>" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<script language="JavaScript" src="../../../js/jquery.js"></script><script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script language="JavaScript" src="../../../js/ft_pd_order.js?v=<?php echo AUTOVER; ?>"></script>
<form name="LAYOUTFORM" action="/app/member/FT_order/FT_order_hre_finish.php" method="post" onSubmit="return false">
  <div class="ord">
      <div class="title"><h1><span class="info">i</span><?php echo $caption?>:<?php echo $gametype;?></h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="hidden" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>
      <div class="main">
          <div class="leag"><?php echo $M_League?></div>

          <div class="teamName"><span class="tName"><?php echo $MB_Team?> VS <?php echo $TG_Team?></span></div>
          <p class="team"><em><?php echo $M_Sign?></em></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>

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
          <p class="auto"><input type="checkbox" id="autoOdd" name="autoOdd" onClick="onclickReloadAutoOdd()" checked value="Y"><span class="auto_info" title="在方格里打勾表示，如果投注单里的任何选项在确认投注时赔率变佳，系统会无提示的继续进行该下注。">自动接受较佳赔率</span></p>
      </div>
  </div>  
<input type="hidden" name="uid" value="<?php echo $uid?>">
<input type="hidden" name="line_type" value="204">
<input type="hidden" name="gid" value="<?php echo $gid?>">
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>">
<input type="hidden" name="type" value="">
<input type="hidden" name="rtype" value="<?php echo $rtype?>">
<input type="hidden" name="wtype" value="<?php echo $wtype?>">
<input type="hidden" name="gnum" value="">
<input type="hidden" name="concede" value="<?php echo $M_Sign?>">
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
</form>
</body>
<SCRIPT LANGUAGE="JavaScript">document.all.gold.focus();</script>
</html>
<?php 
} 
?>