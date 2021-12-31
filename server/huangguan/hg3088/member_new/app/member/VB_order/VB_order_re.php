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
require ("../include/curl_http.php");
$gid=$_REQUEST['gid'];
$uid=$_REQUEST['uid'];
$type=$_REQUEST['type'];
$gnum=$_REQUEST['gnum'];
$strong=$_REQUEST['strong'];
$odd_f_type=$_REQUEST['odd_f_type'];
$error_flag=$_REQUEST['error_flag'];
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

$sql = "select UserName,Money,CurType,Pay_Type,OpenType,Language,VB_RE_Scene,VB_RE_Bet from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);

$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);

$memname=$row['UserName'];
$credit=$row['Money'];
$curtype=$row['CurType'];
$pay_type=$row['Pay_Type'];
$btset=singleset('RE');
$GMIN_SINGLE=$btset[0];
$GMAX_SINGLE=$row['VB_RE_Scene'];
$GSINGLE_CREDIT=$row['VB_RE_Bet'];
$open=$row['OpenType'];
$langx=$row['Language'];
require ("../include/traditional.$langx.inc.php");
if ($error_flag==1){
	$bet_title="<tt>".$Order_Odd_changed_please_bet_again."</tt>";
}
////////////////////////////////////////////////////

$mysql = "select datasite,uid from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$mysql);

$row = mysqli_fetch_assoc($result);
$site=$row['datasite'];
$suid=$row['uid'];
$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/VB_index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=3");
$html_data=$curl->fetch_url("".$site."/app/member/VB_order/VB_order_re.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&strong=$strong&odd_f_type=$odd_f_type");
preg_match("/排球滚球交易单/Usi",$html_data,$m_temp);
if(!$m_temp){ 
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit();
}

$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeR,M_LetB_RE,MB_LetB_Rate_RE,TG_LetB_Rate_RE,MB_Ball,TG_Ball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and `MID`='$gid' and Cancel!=1 and Open=1 and $mb_team!=''";
$result = mysqli_query($dbLink,$mysql);

$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit;
}else{
	$M_League=$row['M_League'];
	$MB_Team=$row["MB_Team"];
	$TG_Team=$row["TG_Team"];
	$MB_Team=filiter_team($MB_Team);
	$Sign=$row['M_LetB_RE'];
	$rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RE"],$row["TG_LetB_Rate_RE"],100);
	switch ($type){
	case "H":
		$M_Place=$MB_Team;
		$M_Rate=change_rate($open,$rate[0]);
		$mtype='RRH';
		break;
	case "C":
		$M_Place=$TG_Team;
		$M_Rate=change_rate($open,$rate[1]);
		$mtype='RRC';
		break;
	}
	$inball=$row['MB_Ball'].":".$row['TG_Ball'];
	if ($row['ShowTypeR']=='C'){
		$inball=$row['TG_Ball'].":".$row['MB_Ball'];
		$Team=$MB_Team;
		$MB_Team=$TG_Team;
		$TG_Team=$Team;
	}
	//$tmp_id='1'.substr(rand(time(),1),0,4);
	//$ratio_id=$tmp_id.$tmp_id*$M_Rate*100;
	$ratio_id=$M_Rate;

	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where M_Name='$memname' and MID='$gid' and LineType=9 and Mtype='$mtype' and Active=5";
	$result = mysqli_query($dbLink,$havesql);
	$haverow = mysqli_fetch_assoc($result);  
	$have_bet=$haverow['BetScore']+0;  
	
    $sql = "select CS,RB from ".DBPREFIX."match_league where  $m_league='$M_League' and Type='VB'";
    $result = mysqli_query($dbLink,$sql);

    $league = mysqli_fetch_assoc($result);
    $mmb_team=explode("[",$row['MB_Team']);
    if($mmb_team[1]==$Special1){
    $bettop=$league['CS'];
    }else{
    $bettop=$league['RB'];
    }
	
	if ($M_Rate==0 or $Sign==''){
	    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	    exit;
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
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="/style/member/mem_order_ft.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
  <script type="text/javascript" src="../../../js/jquery.js"></script> <script language="JavaScript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/football_order.js?v=<?php echo AUTOVER; ?>"></script>
<body id="OFT" class="bodyset" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<form name="LAYOUTFORM" action="/app/member/VB_order/VB_order_finish.php" method="post" onSubmit="return false">
  <div class="ord">
   	<div class="title"><h1>排球</h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="checkbox" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>

    <div class="main">
      <div class="leag"><?php echo $M_League?></div>
      <div class="gametype"><?php echo $U_44 ?></div>
      <div class="teamName"><span class="tName"><?php echo $MB_Team?> <span class="radio"><span class="radio"><?php echo $Sign?></span></span> <?php echo $TG_Team?></span></div>
      <p class="team"><em><?php echo $M_Place?></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>
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
  <div id="gfoot" style="display: block;position: absolute;"></div>
  <div class="ord" id="line_window" style="display: none;">
    <div class="betChk" id="gdiv_table">
      <span class="notice">*SHOW_STR*</span>
      <input type="button" name="wgCancel" value="取消" onClick="Close_div();" class="no">
      <input type="button" name="wgSubmit" value="确定交易" onmousedown='Sure_wager();' class="yes">
    </div>
  </div>
<input type="hidden" name="uid" value="<?php echo $uid?>">
<input type="hidden" name="active" value="5">
<input type="hidden" name="strong" value="<?php echo $strong?>">
<input type="hidden" name="line_type" value="9">
<input type="hidden" name="gid" value="<?php echo $gid?>">
<input type="hidden" name="type" value="<?php echo $type?>">
<input type="hidden" name="gnum" value="<?php echo $gnum?>">
<input type="hidden" name="concede_r" value="0">
<input type="hidden" name="radio_r" value="0">
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
