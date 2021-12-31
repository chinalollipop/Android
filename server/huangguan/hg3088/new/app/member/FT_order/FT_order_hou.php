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
$langx=$_SESSION['langx'];
$gid=$_REQUEST['gid'];
$type=$_REQUEST['type'];
$wtype=$_REQUEST['wtype'];
$rtype=$_REQUEST['rtype'];
$gnum=$_REQUEST['gnum'];
$odd_f_type=$_REQUEST['odd_f_type'];
$error_flag=$_REQUEST['error_flag'];
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
$btset=singleset('OU');
$GMIN_SINGLE=$btset[0];
$GMAX_SINGLE= FT_OU_Scene ;
$GSINGLE_CREDIT= FT_OU_Bet ;
$open=$_SESSION['OpenType'];
if ($error_flag==1){
	$bet_title="<tt>".$Order_Odd_changed_please_bet_again."</tt>";
}

if($gid%2==0){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H from `".DBPREFIX."match_sports` where `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and $mb_team!=''";
}elseif($gid%2==1){
	$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H from `".DBPREFIX."match_sports` where `M_Start`>now() and `MID`=$gid+1 and Cancel!=1 and Open=1 and $mb_team!=''";
}


$result = mysqli_query($dbMasterLink,$mysql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit;
}else{

	if($_REQUEST['flag']=="all"){ // 所有玩法判断
		$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
		$rowMore = mysqli_fetch_assoc($moreRes);
		$couMore = mysqli_num_rows($moreRes);
		$detailsArr = json_decode($rowMore['details'],true);
		$detailsData =$detailsArr[$gid];
		if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
			$ior_Rate = $detailsData["ior_".$rtype];
		}
		if(!$ior_Rate){
			echo attention("$Order_Odd_changed_please_bet_again",$uid,$langx);
            // echo '<script>self.history.back();top.SI2_mem_index.body.body_var.all_showdata.btnClickEvent("Refresh");</script>';
			exit;
		}
	}
	
	if ($row['M_Date']==date('Y-m-d')){
		$active=1;
		$class="OFT";
		$caption=$Order_FT.$Order_1st_Half_Over_Under_betting_order;
	}else{
		$active=11;
		$class="OFU";
		$caption=$Order_FT.$Order_Early_Market.$Order_1st_Half_Over_Under_betting_order;
	}
	$M_League=$row['M_League'];
	$MB_Team=$row["MB_Team"];
	$TG_Team=$row["TG_Team"];
	$MB_Team=filiter_team($MB_Team);
	$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_H"],$row["TG_Dime_Rate_H"],100);
	switch ($type){
	case "C":
		$M_Place=$row['MB_Dime_H'];
		if ($langx=="zh-cn"){
	        $M_Place=str_replace('O','大&nbsp;',$M_Place);
		}else if ($langx=="zh-cn"){
	        $M_Place=str_replace('O','大&nbsp;',$M_Place);
		}else if ($langx=="en-us" or $langx=="th-tis"){
	        $M_Place=str_replace('O','over&nbsp;',$M_Place);
		}
		if(!isset($ior_Rate))$ior_Rate=$rate[0];
		$M_Rate=change_rate($open,$ior_Rate);
		$mtype='VOUH';		
		break;
	case "H":
		$M_Place=$row["TG_Dime_H"];
		if ($langx=="zh-cn"){
	        $M_Place=str_replace('U','小&nbsp;',$M_Place);
		}else if ($langx=="zh-cn"){
	        $M_Place=str_replace('U','小&nbsp;',$M_Place);
		}else if ($langx=="en-us" or $langx=="th-tis"){
	        $M_Place=str_replace('U','under&nbsp;',$M_Place);
		}
		if(!isset($ior_Rate))$ior_Rate=$rate[1];
		$M_Rate=change_rate($open,$ior_Rate);
		$mtype='VOUC';		
		break;
	}
	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and MID='$gid' and linetype=13 and Mtype='$mtype' and (Active=1 or Active=11)";
	$result = mysqli_query($dbLink,$havesql);
	$haverow = mysqli_fetch_assoc($result);
	$have_bet=$haverow['BetScore']+0;
	
    $sql = "select CS,VOU from ".DBPREFIX."match_league where  $m_league='$M_League'";
    $result = mysqli_query($dbLink,$sql);
    $league = mysqli_fetch_assoc($result);
    $mmb_team=explode("[",$row['MB_Team']);
    if ($mmb_team[1]==$Special1){
        $bettop=$league['CS'];
    }else{
        //$bettop=$league['VOU'];
		$bettop=$GSINGLE_CREDIT;
    }
	
	if ($M_Rate==0 or $M_Rate=='' or $M_Place=='' or $M_Place=='O0' or $M_Place=='U0'){
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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="/style/member/mem_order_ft.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
  <script type="text/javascript" src="../../../js/jquery.js"></script> <script language="JavaScript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/football_order.js?v=<?php echo AUTOVER; ?>"></script>
<body id="OFT" class="bodyset" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<form name="LAYOUTFORM" action="/app/member/FT_order/FT_order_finish.php" method="post" onSubmit="return false">
  <div class="ord">
   	<div class="title"><h1>足球</h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="checkbox" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>
      <div class="main">
          <div class="ord_betArea">
            <div class="gametype"><?php echo $U_33 ?></div>
            <div class="leag"><?php echo $M_League?></div>

            <div class="teamName"><span class="tName"><?php echo $MB_Team?> vs. <?php echo $TG_Team?></span></div>
            <p class="team"><em><span class="radio"><?php echo $M_Place?></span></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>
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
<input type="hidden" name="line_type" value="13">
<input type="hidden" name="gid" value="<?php echo $gid?>"> 
<input type="hidden" name="type" value="<?php echo $type?>">
<input type="hidden" name="rtype" value="<?php echo $rtype?>">
<input type="hidden" name="wtype" value="<?php echo $wtype?>">
<input type="hidden" name="gnum" value="<?php echo $gnum?>">
<input type="hidden" name="concede_h" value="1">
<input type="hidden" name="radio_h" value="0">
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
<input type="hidden" name="dataSou" value="<?php if($_REQUEST['flag']=='all') echo "interface";?>">
</form>
</body>
<SCRIPT LANGUAGE="JavaScript">document.all.gold.focus();</script>
</html>
<?php
}
?>