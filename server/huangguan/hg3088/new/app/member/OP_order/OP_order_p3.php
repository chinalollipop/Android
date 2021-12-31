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
if($userid<=0){
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>alert('请登陆后再进行投单！');top.SI2_mem_index.mem_order.location.href='../select.php?uid=$uid&langx=zh-cn';</script>\n";exit;
}
require ("../include/define_function_list.inc.php");
$uid=$_REQUEST['uid'];
$teamcount=$_REQUEST['teamcount'];
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$sql = "select UserName,Money,CurType,Pay_Type,OpenType,Language,OP_PR_Scene,OP_PR_Bet from ".DBPREFIX.MEMBERTABLE." where oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);

$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);

$langx=$row['Language'];
require ("../include/traditional.$langx.inc.php");
$pay_type=$row['Pay_Type'];
$curtype=$row['CurType'];
$open=$row['OpenType'];
$memname=$row['UserName'];
$credit=$row['Money'];
$btset=singleset('PC');
$GMIN_SINGLE=$btset[0];
$bettop=$btset[1];
$GMAX_SINGLE= OP_PR_Scene ;
$GSINGLE_CREDIT= OP_PR_Bet ;
$m_team=0;
for ($i=0;$i<$teamcount+1;$i++){
	 $res=$_REQUEST["game$i"];
	 if ($res!=""){
	     $gid=$_REQUEST["game_id$i"];
	     $havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and FIND_IN_SET($gid,MID)>0 and linetype=8 and (Active=6 or Active=66)";
	     $result = mysqli_query($dbLink,$havesql);

	     $haverow = mysqli_fetch_assoc($result);
	     $score=$haverow['BetScore'];
	     if ($score==''){
		     $score=0;
	     }
	     $score1=$score1+$score;
	     if ($have_bet==''){
		     $have_bet=$haverow['BetScore']." ";
	     }else{
		     $have_bet=$have_bet.$haverow['BetScore']." ";
	     }

	     $mysql = "select MID,M_Date,M_Time,MB_MID,TG_MID,ShowTypeP,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeP,MB_P_Win_Rate,TG_P_Win_Rate,M_P_Flat_Rate,MB_P_LetB_Rate,TG_P_LetB_Rate,M_P_LetB,MB_P_Dime,TG_P_Dime,MB_P_Dime_Rate,TG_P_Dime_Rate,S_P_Single_Rate,S_P_Double_Rate,ShowTypePV,MB_P_Win_Rate_V,TG_P_Win_Rate_V,M_P_Flat_Rate_V,MB_P_LetB_Rate_V,TG_P_LetB_Rate_V,M_P_LetB_V,MB_P_Dime_V,TG_P_Dime_V,MB_P_Dime_Rate_V,TG_P_Dime_Rate_V from `".DBPREFIX."match_sports` where Type='OP' and  `M_Start`>now() and MID='$gid' and Cancel!=1 and Open=1 and MB_Team!='' and MB_Team_tw!='' and MB_Team_en!=''";//判断赛事是否关闭
	     $result = mysqli_query($dbMasterLink,$mysql);

	     $cou=mysqli_num_rows($result);
	     if ($cou==0){
		     echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		     exit();
	     }
	     $row = mysqli_fetch_assoc($result);
	     $pdate=$row['M_Date'];
	     $start=$row['M_Start'];
		 $league=$row['M_League'];
	     $s_mb_team = filiter_team($row['MB_Team']);
		 $s_tg_team = filiter_team($row['TG_Team']);
	     $mb_mid=$row['MB_MID'];
		 $tg_mid=$row['TG_MID'];
		 if($teama==$row['MB_Team']){
			echo attention("$Order_The_game_is_covered_same_teams_Please_reset_again",$uid,$langx);
			exit();
		}else{
			$teama=$row['MB_Team'];
		}
	     switch($res){
		 case 'MH':
				$rate=$row['MB_P_Win_Rate'];
				$place=$s_mb_team;
				$sign   = 'VS.';
				$mmid="(".$row['MB_MID'].")";
				$showtypeT=$U_30;
				break;
		 case 'MC':
				$rate=$row['TG_P_Win_Rate'];			
				$place=$s_tg_team;
				$sign   = 'VS.';
				$mmid="(".$row['TG_MID'].")";
				$showtypeT=$U_30;
				break;
		 case 'MN':
				$rate=$row['M_P_Flat_Rate'];
				$place=$Draw;
				$sign   = 'VS.';
				$showtypeT=$U_30;
				$mmid="";
				break;
	     case 'PRH':
			  $place=$s_mb_team;
			  $rate=change_rate($open,$row["MB_P_LetB_Rate"]);
			  if ($row['ShowTypeP']=='C'){
				  $team=$s_mb_team;
				  $s_mb_team=$s_tg_team;
				  $s_tg_team=$team;			
			  }
			  $sign=$row['M_P_LetB'];
			  $mmid="(".$row['MB_MID'].")";
			  $showtypeT=$U_26;
			  break;
	     case 'PRC':
			  $place=$s_tg_team;
			  $rate=change_rate($open,$row["TG_P_LetB_Rate"]);
			  if ($row['ShowTypeP']=='C'){
				  $team=$s_mb_team;
				  $s_mb_team=$s_tg_team;
				  $s_tg_team=$team;			
			  }
			  $sign=$row['M_P_LetB'];
			  $mmid="(".$row['TG_MID'].")";
			  $showtypeT=$U_26;
			  break;	
	     case 'POUC':
			  $place=$row['MB_P_Dime'];
			  if ($langx=="zh-cn"){
			      $place=str_replace('O','大&nbsp;',$place);
			  }else if ($langx=="zh-cn"){
			      $place=str_replace('O','大&nbsp;',$place);
			  }else if ($langx=="en-us" or $langx=="th-tis"){
			      $place=str_replace('O','over&nbsp;',$place);
			  }
			  $rate=change_rate($open,$row['MB_P_Dime_Rate']);
			  $sign='VS.';	
			  $mmid="(".$row['MB_MID'].")";
			  $showtypeT=$U_27;
			  break;
	     case 'POUH':
			  $place=$row['TG_P_Dime'];
			  if ($langx=="zh-cn"){
			      $place=str_replace('U','小&nbsp;',$place);
			  }else if ($langx=="zh-cn"){
			      $place=str_replace('U','小&nbsp;',$place);
			  }else if ($langx=="en-us" or $langx=="th-tis"){
			      $place=str_replace('U','under&nbsp;',$place);
			  }
			  $rate=change_rate($open,$row['TG_P_Dime_Rate']);
			  $sign='VS.';
			  $mmid="(".$row['TG_MID'].")";	
			  $showtypeT=$U_27;				
			  break;
	     case 'PO':
			  $rate=$row['S_P_Single_Rate'];
			  $place="(".$Order_Odd.")";
			  $sign   = 'VS.';
			  $mmid="(".$row['MB_MID'].")";	
			  $showtypeT=$U_31;
			  break;
	     case 'PE':
			  $rate=$row['S_P_Double_Rate'];
			  $place="(".$Order_Even.")";
			  $sign   = 'VS.';
			  $mmid="(".$row['TG_MID'].")";	
			  $showtypeT=$U_31;
			  break;
	     case 'HPMH':
			  $rate=$row['MB_P_Win_Rate_V'];
			  $place=$s_mb_team;
			  $sign   = 'VS.';
			  $mmid="(".$row['MB_MID'].")";
			  $showtypeT=$U_32;
			  break;
		 case 'HPMC':
			  $rate=$row['TG_P_Win_Rate_V'];			
			  $place=$s_tg_team;
			  $sign   = 'VS.';
			  $mmid="(".$row['TG_MID'].")";
			  $showtypeT=$U_32;
			  break;
		 case 'HPMN':
			  $rate=$row['M_P_Flat_Rate_V'];
			  $place=$Draw;
			  $sign   = 'VS.';
			  $mmid="";
			   $showtypeT=$U_32;
			  break;
	     case 'HPRH':
			  $place=$s_mb_team;
			  $rate=change_rate($open,$row["MB_P_LetB_Rate_V"]);
			  if ($row['ShowTypeP']=='C'){
				  $team=$s_mb_team;
				  $s_mb_team=$s_tg_team;
				  $s_tg_team=$team;			
			  }
			  $sign=$row['M_P_LetB_V'];
			  $mmid="(".$row['MB_MID'].")";
			  $showtypeT=$U_34;
			  break;
	     case 'HPRC':
			  $place=$s_tg_team;
			  $rate=change_rate($open,$row["TG_P_LetB_Rate_V"]);
			  if ($row['ShowTypeP']=='C'){
				  $team=$s_mb_team;
				  $s_mb_team=$s_tg_team;
				  $s_tg_team=$team;			
			  }
			  $sign=$row['M_P_LetB_V'];
			  $mmid="(".$row['TG_MID'].")";
			  $showtypeT=$U_34;
			  break;	
	     case 'HPOUC':
			  $place=$row['MB_P_Dime_V'];
			  if ($langx=="zh-cn"){
			      $place=str_replace('O','大&nbsp;',$place);
			  }else if ($langx=="zh-cn"){
			      $place=str_replace('O','大&nbsp;',$place);
			  }else if ($langx=="en-us" or $langx=="th-tis"){
			      $place=str_replace('O','over&nbsp;',$place);
			  }
			  $rate=change_rate($open,$row['MB_P_Dime_Rate_V']);
			  $showtypeT=$U_33;
			  $sign='VS.';				
			  $mmid="(".$row['MB_MID'].")";
			  break;
	     case 'HPOUH':
			  $place=$row['TG_P_Dime_V'];
			  if ($langx=="zh-cn"){
			      $place=str_replace('U','小&nbsp;',$place);
			  }else if ($langx=="zh-cn"){
			      $place=str_replace('U','小&nbsp;',$place);
			  }else if ($langx=="en-us" or $langx=="th-tis"){
			      $place=str_replace('U','under&nbsp;',$place);
			  }
			  $rate=change_rate($open,$row['TG_P_Dime_Rate_V']);
			  $showtypeT=$U_33;
			  $sign='VS.';
			  $mmid="(".$row['TG_MID'].")";					
			  break;		  
	     }
		 if ($res=='HPMH' or $res=='HPMC' or $res=='HPMN' or $res=='HPRH' or $res=='HPRC' or $res=='HPOUH' or $res=='HPOUC'){
		     $title="<FONT COLOR=#BB0000> - [$Order_1st_Half]</FONT>";
		 }else{
		 	 $title="";
		 }		 
	     /*$betplace=$betplace."<div id=TR$i><p class=team id=P1>".$league.$title."-".$Mix_Parlay."<br>";
	     $betplace=$betplace.$s_mb_team."&nbsp;<FONT color=#CC0000>".$sign."</FONT>&nbsp;".$s_tg_team."<br>";
	     $betplace=$betplace."<FONT color=#cc0000>".$mmid."&nbsp;".$place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".number_format($rate,2)."</b></FONT>&nbsp;";
		 $betplace=$betplace."<input type=button name=delteam$i value=$Order_Delete onClick=delteams('$i') class=par></p></div>";*/
$betplace=$betplace.'<div id=TR'.$i.' class=ee6819>';
$betplace=$betplace.'<div class="ord_betArea"><div class="gametype1">'.$showtypeT.'</div><div class="leag"><span class="leag_txt">'.$league.'</span><span class="deletebtn"><input type="button" name="delteam1" value="" onClick="delteams(\''.$i.'\')" class="par"></span></div>';
$betplace=$betplace.'<div class="teamName"><span class="tName">'.$s_mb_team.' [主] <span class="radio">'.$sign.'</span>'.$s_tg_team.'</span></div>';
$betplace=$betplace.'<p class="team" id="team1"><em>'.$place.'</em> @ <strong class="light" id="P1">'.number_format($rate,2).'</strong></p></div>';
$betplace=$betplace.'<p class="errorP3" style="display: none"></p>';
$betplace=$betplace.'</div>';	
		 
	     $m_team=$m_team+1;
		 $m_rate[]=$rate;
		 $m_gid[]=$gid;
		 $type[]=$res;
		 $showtype[]=$row['ShowTypeP'];
	}
}
$rate=implode(" ",$m_rate);

if ($row['M_Date']==date('Y-m-d')){
	$active=6;
	$class="OOP";
	$caption=$Order_Other.$Order_Mix_Parlay_betting_order;
}else{
	$active=66;
	$class="OOM";
	$caption=$Order_Other.$Order_Early_Market.$Order_Mix_Parlay_betting_order;
}
?>
<script>
var iorstr='<?php echo $rate?> ';
var minlimit='3';
var maxlimit='10';
</script>
<html>
<head>
<title>ft_p3_order</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/member/mem_order_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script>
var scripts=new Array();
var rtype="P3";
<?php
for ($i=0;$i<$m_team;$i++){
echo "scripts[$i]=new Array('$m_gid[$i]','$type[$i]','$showtype[$i]','1','0','$m_rate[$i]');\n";
} 
?>
</script>
</head>

<body id="OFTP3" class="bodyset" style="padding:0px; margin:0px; float:left;" onLoad="LoadSelect();" >
<script type="text/javascript" src="../../../js/jquery.js"></script> <script language="JavaScript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/ft_parlay_order.js?v=<?php echo AUTOVER; ?>"></script>
<form name="LAYOUTFORM" action="/app/member/OP_order/OP_order_p_finish.php" method="post" onSubmit="return false">
<div class="ord">
<div class="title"><h1>其它 - 综合过关</h1><div class="tiTimer" onClick="orderReload();"><span id="ODtimer">10</span><input type="checkbox" id="checkOrder" onClick="onclickReloadTime()" checked value="10"></div></div>
<div class="main">
	
		<?php echo  $betplace?>

      <p class="error" style="display: none"></p>
      <p class="error" id="err_div" style="display:none;">单注最高派彩额是RMB 1,000,000</p>

      <div class="betdata">
     
          <p class="amount">交易金额：<input name="gold" type="text" class="txt" id="gold" onKeyPress="return CheckKey(event)" onKeyUp="return ComCountWinGold('<?php echo $rate?>',<?php echo $i ?>)" size="8" maxlength="10">  </p>
          <p class="mayWin"><span class="bet_txt">可赢金额：</span><font id="pc">0</font></p>
          <p class="minBet"><span class="bet_txt">单注最低：</span><?php echo $GMIN_SINGLE?></p>
          <p class="maxBet"><span class="bet_txt">单注最高：</span><?php echo $GSINGLE_CREDIT?></p> <div class="betAmount"> </div>

    </div>
  
    </div>
    <div id="gWager" style="display: none;position: absolute;"></div>
    <div id="gbutton" style="display: block;position: absolute;"></div>
  <div class="betBox">
      <input type="button" name="SUBMIT" value="确定交易" onClick="ComCountWinGold('<?php echo $rate?>',3);return CheckSubmit();" class="yes"><input type="button" name="btnCancel" value="取消" onClick="Win_Redirect();" class="no">
    
  </div>
  </div>  
<input type="hidden" name="uid" value="<?php echo $uid?>">
<input type="hidden" name="wid" value="">
<input type="hidden" name="active" value="<?php echo $active?>">
<input type="hidden" name="teamcount" value="<?php echo $m_team?>">
<input type="hidden" name="tcount" value="<?php echo $m_team?>">
<input type="hidden" name="username" value="<?php echo $memname?>">
<input type="hidden" name="singlecredit" value="<?php echo $GMAX_SINGLE?>">
<input type="hidden" name="singleorder" value="<?php echo $GSINGLE_CREDIT?>">
<input type="hidden" name="gmin_single" value="<?php echo $GMIN_SINGLE?>">
<input type="hidden" name="gmax_single" value="<?php echo $GMAX_SINGLE?>">
<input type="hidden" name="restcredit" value="<?php echo $credit?>">
<input type="hidden" name="wagerstotal" value="0">
<input type="hidden" name="pay_type" value="<?php echo $pay_type?>">
<input type="hidden" name="sc" value="0.0 0.0 0.0 0.0 0.0 ">
<input type="hidden" name="pdate" value="<?php echo $pdate?>">
<input type="hidden" id="wagerDatas" name="wagerDatas" value="">
<input type="hidden" id="maxgold" name="maxgold" value="<?php echo $GMAX_SINGLE?>">
</form>
<!--object id=closes type="application/x-oleobject" classid="clsid:adb880a6-d8ff-11cf-9377-00aa003b7a11">
    <param name="Command" value="Close"></object-->

</body>
<SCRIPT LANGUAGE="JavaScript">
function time_scrollTo(){
document.all.gold.focus();top.SI2_mem_index.mem_order.scrollTo(0,6000);	
}
//setTimeout('setTimeout()',2000);
</script>
</html>