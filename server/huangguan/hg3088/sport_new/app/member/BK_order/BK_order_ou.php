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
$uid=(isset($_REQUEST['uid']) && $_REQUEST['uid'])? $_REQUEST['uid'] :$_SESSION['Oid'];
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
	echo "<script>top.location.href='/'</script>";
	exit;
}
$gameswitch = judgeBetSwitch('BK') ; // 篮球投注开关
if($gameswitch){ // 停用 篮球
    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
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
$GMAX_SINGLE=BK_OU_Scene;
$GSINGLE_CREDIT=BK_OU_Bet;
$open=$_SESSION['OpenType'];

if ($error_flag==1){
	$bet_title="<tt>".$Order_Odd_changed_please_bet_again."</tt>";
}

$mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!=''";
$resultL = mysqli_query($dbLink,$mysqlL);
$couL=mysqli_num_rows($resultL);
if($couL==0) {
    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
    exit();
}

$mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
$result = mysqli_query($dbCenterMasterDbLink,$mysql);

$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($_REQUEST['id']>0){
	$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`=".$_REQUEST['id']);
	$rowMore = mysqli_fetch_assoc($moreRes);
	$couMore = mysqli_num_rows($moreRes);	
}else{
	$couMore=0;	
}
if($cou==0 && $couMore==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit;
}else{
	if($couMore>0){
		$detailsArr = json_decode($rowMore['details'],true);
		$detailsData =$detailsArr[$gid];
		if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
			$ior_Rate = $detailsData["ior_".$rtype];
			if($type=="H") $M_Place ='小'. $detailsData["ratio_u"];
			if($type=="C") $M_Place ='大'. $detailsData["ratio_o"];
		}	
	}else{
		$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100);
		if($type=='H'){
			$ior_Rate=$rate[1];
			$M_Place=$row["TG_Dime"];
		} 
		if($type=='C'){
			$ior_Rate=$rate[0];
			$M_Place=$row["MB_Dime"];
		} 
	}
	if(!$ior_Rate){
		echo attention("$Order_Odd_changed_please_bet_again",$uid,$langx);
		exit;
	}
	if($cou==0){
		$mysql = "select M_Date,M_League,MB_Team,TG_Team from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`='{$_REQUEST['id']}' and Open=1 and $mb_team!=''";
		$result = mysqli_query($dbMasterLink,$mysql);
		$row=mysqli_fetch_assoc($result);	
		$cou=mysqli_num_rows($result);
		if($cou==0){
			echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
			exit;		
		}
	}
	if ($row['M_Date']==date('Y-m-d')){
		$active=2;
		$class="OBK";
		$caption=$Order_Basketball.$Order_Over_Under_betting_order;
	}else{
		$active=22;
		$class="OBU";
		$caption=$Order_Basketball.$Order_Early_Market.$Order_Over_Under_betting_order;
	}
	$M_League=$row['M_League'];
	$MB_Team=filiter_team($row["MB_Team"]);
	$TG_Team=filiter_team($row["TG_Team"]);
	switch ($type){
		case "C":
			if ($langx=="zh-cn"){
		        $M_Place=str_replace('O','大&nbsp;',$M_Place);
			}else if ($langx=="zh-cn"){
		        $M_Place=str_replace('O','大&nbsp;',$M_Place);
			}else if ($langx=="en-us" or $langx=="th-tis"){
		        $M_Place=str_replace('O','over&nbsp;',$M_Place);
			}
			$M_Rate=change_rate($open,$ior_Rate);
			$mtype='OUH';		
			break;
		case "H":
			if ($langx=="zh-cn"){
		        $M_Place=str_replace('U','小&nbsp;',$M_Place);
			}else if ($langx=="zh-cn"){
		        $M_Place=str_replace('U','小&nbsp;',$M_Place);
			}else if ($langx=="en-us" or $langx=="th-tis"){
		        $M_Place=str_replace('U','under&nbsp;',$M_Place);
			}
			$M_Rate=change_rate($open,$ior_Rate);
			$mtype='OUC';	
			break;
	}
	
	$team=strip_tags($row["MB_Team"]);
	$Place=explode("-",$team);
	if ($Place[1]==""){
		$W_Place="";
	}else{
	    $W_Place="<font color=gray> - ".$Place[1]."</font>";
	}
	
	$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where M_Name='$memname' and MID='$gid' and LineType=3 and Mtype='$mtype' and (Active=2 or Active=22)";  
	$result = mysqli_query($dbLink,$havesql);
	$haverow = mysqli_fetch_assoc($result);  
	$have_bet=$haverow['BetScore']+0;  
	
    $sql = "select CS,OU from ".DBPREFIX."match_league where  $m_league='$M_League' and Type='BK'";
    $result = mysqli_query($dbLink,$sql);

    $league = mysqli_fetch_assoc($result);
    $mmb_team=explode("[",$row['MB_Team']);
    if ($mmb_team[1]==$Special1){
        $bettop=$league['CS'];
    }else{
        //$bettop=$league['OU'];
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
  <script type="text/javascript" src="../../../js/jquery.js"></script> <script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/football_order.js?v=<?php echo AUTOVER; ?>"></script>
<body id="OFT" class="bodyset bodyset_<?php echo TPL_FILE_NAME;?>" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<form name="LAYOUTFORM" action="/app/member/BK_order/BK_order_finish.php" method="post" onSubmit="return false">
  <div class="ord">
   	<div class="title"><h1><span class="info">i</span>篮球：<?php echo $BK_NFL.' '.$U_OU ?></h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="hidden" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>
      <div class="main">
            <div class="leag"><?php echo $M_League?></div>

            <div class="teamName"><span class="tName"><?php echo $MB_Team?> VS <?php echo $TG_Team?></span></div>
            <p class="team"><em><span class="radio"><?php echo $M_Place?></span></em> @ <strong class="light" id="ioradio_id"><?php echo $M_Rate?></strong></p>

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
<input type="hidden" name="line_type" value="3">
<input type="hidden" name="gid" value="<?php echo $gid?>"> 
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>">
<input type="hidden" name="type" value="<?php echo $type?>">
<input type="hidden" name="wtype" value="<?php echo  $wtype?>">
<input type="hidden" name="rtype" value="<?php echo  $rtype?>">
<input type="hidden" name="gnum" value="<?php echo $gnum?>">
<input type="hidden" name="concede_h" value="2">
<input type="hidden" name="radio_h" value="-50">
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
