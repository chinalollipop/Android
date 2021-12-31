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

$uid=$_REQUEST['uid'];
$gid=$_REQUEST['gid'];
$type=$_REQUEST['type'];
$gnum=$_REQUEST['gnum'];
$strong=$_REQUEST['strong'];
$odd_f_type=$_REQUEST['odd_f_type'];
$ioradio_r_h=$_REQUEST['ioradio_r_h'];
$gold=$_REQUEST['gold'];
$active=$_REQUEST['active'];
$line=$_REQUEST['line_type'];
$restcredit=$_REQUEST['restcredit'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$sql = "select UserName,Money,CurType,Pay_Type,OpenType,Language,Agents,World,Corprator,Super,Admin,ratio,ID from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);

$cou=mysqli_num_rows($result);

$memrow = mysqli_fetch_assoc($result);
$langx=$memrow['Language'];
$open=$memrow['OpenType'];
$pay_type =$memrow['Pay_Type'];
$memname=$memrow['UserName'];
$agents=$memrow['Agents'];
$world=$memrow['World'];
$corprator=$memrow['Corprator'];
$super=$memrow['Super'];
$admin=$memrow['Admin'];
$w_ratio=$memrow['ratio'];
$HMoney=$memrow['Money'];
if ($HMoney < $gold){
	echo attention("$User_insufficient_balance",$uid,$langx);
	exit();
}
$w_current=$memrow['CurType'];
$havemoney=$HMoney-$gold;
$memid=$memrow['ID'];
require ("../include/traditional.$langx.inc.php");

$mysql = "select datasite,uid from ".DBPREFIX."web_system_data where id=1";
$result = mysqli_query($dbLink,$mysql);

$row = mysqli_fetch_assoc($result);
$site=$row['datasite'];
$suid=$row['uid'];
$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt"); 
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/VB_index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=3");
switch ($line){
case '10':
	$html_data=$curl->fetch_url("".$site."/app/member/VB_order/VB_order_rou.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
	break;
case '9':
	$html_data=$curl->fetch_url("".$site."/app/member/VB_order/VB_order_re.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&strong=$strong&odd_f_type=$odd_f_type");
	break;
}
preg_match("/排球滚球/Usi",$html_data,$m_temp);
if(!$m_temp){ 
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit();
}


$mysql = "select * from `".DBPREFIX."match_sports` where Type='VB' and `MID`='$gid' and Open=1 and MB_Team!='' and MB_Team_tw!='' and MB_Team_en!=''";
$result = mysqli_query($dbMasterLink,$mysql);

$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
	//echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit();
}
	//主客队伍名称
	$w_tg_team=$row['TG_Team'];
	$w_tg_team_tw=$row['TG_Team_tw'];
	$w_tg_team_en=$row['TG_Team_en'];
	
	$w_mb_team=$row['MB_Team'];
	$w_mb_team_tw=$row['MB_Team_tw'];
	$w_mb_team_en=$row['MB_Team_en'];
	
	$w_mb_team=filiter_team(trim($row['MB_Team']));
	$w_tg_team=filiter_team(trim($row['TG_Team']));	
	$w_mb_team_tw=filiter_team(trim($row['MB_Team_tw']));
	$w_tg_team_tw=filiter_team(trim($row['TG_Team_tw']));
	$w_mb_team_en=filiter_team(trim($row['MB_Team_en']));
	$w_tg_team_en=filiter_team(trim($row['TG_Team_en']));
	
	//取出当前字库的主客队伍名称
	
	$s_mb_team=filiter_team($row[$mb_team]);
	$s_tg_team=filiter_team($row[$tg_team]);

	//下注时间
	$m_date=$row["M_Date"];
	$showtype=$row["ShowTypeRB"];
	$bettime=date('Y-m-d H:i:s');
	
	//联盟
	if ($row[$m_sleague]==''){
		$w_sleague=$row['M_League'];
		$w_sleague_tw=$row['M_League_tw'];
		$w_sleague_en=$row['M_League_en'];
		$s_sleague=$row[$m_league];
	}
	
	$inball=$row['MB_Ball'].":".$row['TG_Ball'];
	$inball1=$inball;
	$mb_ball = $row['MB_Ball'];
	$tg_ball = $row['TG_Ball'];
	switch ($line){
	case 9:
 		$bet_type='滚球让球';
		$bet_type_tw="滾球讓球";
		$bet_type_en="Running Ball";
		$caption=$Order_VB.$Order_Running_Ball_betting_order;
		$rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RB"],$row["TG_LetB_Rate_RB"],100);
		switch ($type){
		case "H":
			$w_m_place=$w_mb_team;
			$w_m_place_tw=$w_mb_team_tw;
			$w_m_place_en=$w_mb_team_en;
			$s_m_place=$s_mb_team;
			$w_m_rate=change_rate($open,$rate[0]);
			$turn_url="/app/member/VB_order/VB_order_re.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
			$w_gtype='RRH';
			break;
		case "C":
			$w_m_place=$w_tg_team;
			$w_m_place_tw=$w_tg_team_tw;
			$w_m_place_en=$w_tg_team_en;
			$s_m_place=$s_tg_team;
			$w_m_rate=change_rate($open,$rate[1]);
			$turn_url="/app/member/VB_order/VB_order_re.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
			$w_gtype='RRC';
			break;
		}
		$Sign=$row['M_LetB_RB'];
		$grape=$Sign;
		if (strtoupper($showtype)=="H"){
			$l_team=$s_mb_team;
			$r_team=$s_tg_team;
			$w_l_team=$w_mb_team;
			$w_l_team_tw=$w_mb_team_tw;
			$w_l_team_en=$w_mb_team_en;
			$w_r_team=$w_tg_team;
			$w_r_team_tw=$w_tg_team_tw;
			$w_r_team_en=$w_tg_team_en;	
			$inball=$row['MB_Ball'].":".$row['TG_Ball'];
		}else{
			$r_team=$s_mb_team;
			$l_team=$s_tg_team;
			$w_r_team=$w_mb_team;
			$w_r_team_tw=$w_mb_team_tw;
			$w_r_team_en=$w_mb_team_en;
			$w_l_team=$w_tg_team;
			$w_l_team_tw=$w_tg_team_tw;
			$w_l_team_en=$w_tg_team_en;
			$inball=$row['TG_Ball'].":".$row['MB_Ball'];
			
		}
		$s_mb_team=$l_team;
		$s_tg_team=$r_team;
		$w_mb_team=$w_l_team;
		$w_mb_team_tw=$w_l_team_tw;
		$w_mb_team_en=$w_l_team_en;
		$w_tg_team=$w_r_team;
		$w_tg_team_tw=$w_r_team_tw;
		$w_tg_team_en=$w_r_team_en;
		if ($odd_f_type=='H'){
		    $gwin=($w_m_rate)*$gold;
		}else if ($odd_f_type=='M' or $odd_f_type=='I'){
		    if ($w_m_rate<0){
				$gwin=$gold;
			}else{
				$gwin=($w_m_rate)*$gold;
			}
		}else if ($odd_f_type=='E'){
		    $gwin=($w_m_rate-1)*$gold;
		}
		$ptype='RE';
		break;
	case 10:	
		$bet_type='滚球大小';
		$bet_type_tw="滾球大小";
		$bet_type_en="Running Over/Under";
		$caption=$Order_VB.$Order_Running_Ball_Over_Under_betting_order;
		$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB"],$row["TG_Dime_Rate_RB"],100);
		switch ($type){
		case "C":
			$w_m_place=$row["MB_Dime_RB"];
			$w_m_place=str_replace('O','大&nbsp;',$w_m_place);
			$w_m_place_tw=$row["MB_Dime_RB"];
			$w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
			$w_m_place_en=$row["MB_Dime_RB"];
			$w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
			
			$m_place=$row["MB_Dime_RB"];
			
			$s_m_place=$row["MB_Dime_RB"];
			if ($langx=="zh-cn"){
	            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
		    }else if ($langx=="zh-cn"){
		        $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
		    }else if ($langx=="en-us" or $langx=='th-tis'){
		        $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
			}	
			$w_m_rate=change_rate($open,$rate[0]);
			$turn_url="/app/member/VB_order/VB_order_rou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$w_gtype='ROUH';
			break;
		case "H":
			$w_m_place=$row["TG_Dime_RB"];
			$w_m_place=str_replace('U','小&nbsp;',$w_m_place);
			$w_m_place_tw=$row["TG_Dime_RB"];
			$w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
			$w_m_place_en=$row["TG_Dime_RB"];
			$w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);
			
			$m_place=$row["TG_Dime_RB"];
			
			$s_m_place=$row["TG_Dime_RB"];
			if ($langx=="zh-cn"){
	            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
		    }else if ($langx=="zh-cn"){
		        $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
		    }else if ($langx=="en-us" or $langx=='th-tis'){
		        $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
			}
			$w_m_rate=change_rate($open,$rate[1]);
			$turn_url="/app/member/VB_order/VB_order_rou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$w_gtype='ROUC';
			break;
		}
		$Sign="VS.";
		$grape=$m_place;
		if ($odd_f_type=='H'){
		    $gwin=($w_m_rate)*$gold;
		}else if ($odd_f_type=='M' or $odd_f_type=='I'){
		    if ($w_m_rate<0){
				$gwin=$gold;
			}else{
				$gwin=($w_m_rate)*$gold;
			}
		}else if ($odd_f_type=='E'){
		    $gwin=($w_m_rate-1)*$gold;
		}
		$ptype='ROU';				
		break;
	}
	
	if ($gold<10){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}

	if ($w_m_rate=='' or $grape==''){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}
	if ($w_m_rate!=$ioradio_r_h){
		$turn_url=$turn_url.'&error_flag=1';
		echo "<script language='javascript'>self.location='$turn_url';</script>";
		exit;
	}	
	if ($s_m_place=='' or $w_m_place=='' or $w_m_place_tw=='' or $w_m_place_en==''){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}
	if ($line==9 or $line==10){
		$oddstype=$odd_f_type;
	}else{
		$oddstype='';
	}
	$w_mb_mid=$row['MB_MID'];
	$w_tg_mid=$row['TG_MID'];

	$lines=$row['M_League']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
	$lines=$lines."<FONT color=#cc0000>$w_m_place</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";	
	
	$lines_tw=$row['M_League_tw']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team_tw."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_tw."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
	$lines_tw=$lines_tw."<FONT color=#cc0000>$w_m_place_tw</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";
	
	$lines_en=$row['M_League_en']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team_en."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_en."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
	$lines_en=$lines_en."<FONT color=#cc0000>$w_m_place_en</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";	
	
$ip_addr = get_ip();

$psql = "select A_Point,B_Point,C_Point,D_Point from ".DBPREFIX."web_agents_data where UserName='$agents'";
$result = mysqli_query($dbLink,$psql);
$prow = mysqli_fetch_assoc($result);
$a_point=$prow['A_Point']+0;
$b_point=$prow['B_Point']+0;
$c_point=$prow['C_Point']+0;
$d_point=$prow['D_Point']+0;

$max_sql = "select max(ID) max_id from ".DBPREFIX."web_report_data where BetTime<'$bettime'";
$max_result = mysqli_query($dbLink,$max_sql);

$max_row = mysqli_fetch_assoc($max_result);
$max_id=$max_row['max_id'];
$num=rand(10,50);
$id=$max_id+$num;


	$sql = "INSERT INTO ".DBPREFIX."web_report_data	(ID,QQ83068506,danger,MID,Active,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball) values ('$id','$inball1','1','$gid','$active','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','VB','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball')";

	mysqli_query($dbMasterLink,$sql) or die ("操作失败!");
	$ouid=mysqli_insert_id($dbMasterLink);
	$sql = "update ".DBPREFIX.MEMBERTABLE." set Money='$havemoney' where UserName='$memname'";
	mysqli_query($dbMasterLink,$sql) or die ("操作失败!!");
	// echo attention("$Order_OK");exit;
?>
<html>
<head>
<meta http-equiv='Content-Type' content="text/html; charset=utf-8">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<link rel="stylesheet" href="/style/member/mem_order<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">
<!--<script>window.setTimeout("self.location='../select.php?uid=<?php/*=$uid*/?>'", 45000);</script>-->
</head>
<body id="OFIN" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
  <div class="ord">
    <span><h1><?php echo $caption?></h1></span>
      <div id="info">
       <!--<p><?php/*=$Order_Login_Name*/?><?php/*=$memname*/?></p>-->
       <!--<p class="mem-can-use"><?php/*=$Order_Credit_line*/?><?php/*=$havemoney*/?></p>-->
       <div class="fin_title"><p class="fin_acc">成功提交注单！</p><p class="p-underline"><?php echo $Order_Bet_success?>&nbsp;<?php echo show_voucher($line,$ouid)?></p><p class="error">危险球 - 待确认</p></div>
       <p><center><strong><font color='#FFFFFF' style='background-color: #FF0000'>&nbsp;<?php echo $Order_Pending?>&nbsp;</font></strong></center></p>
       <p class="team"><?php echo $s_sleague?>&nbsp;<?php echo $btype?>&nbsp;<?php echo date('m-d',strtotime($row["M_Date"]))?><BR><?php echo $inball?>&nbsp;&nbsp;<?php echo $s_mb_team?>&nbsp;<font color=#cc0000><?php echo $Sign?></font>&nbsp;<?php echo $s_tg_team?><br><em><?php echo $s_m_place?></em>&nbsp;@&nbsp;<em><strong><?php echo $w_m_rate?></strong></em></p>
       <p class="deal-money"><?php echo $Order_Bet_Amount?><?php echo $gold?></p>
       <!--<p class="canwin-money"><?php/*=$Order_Estimated_Payout*/?><FONT id=pc color=#cc0000><?php/*=$gwin*/?></FONT></p>-->
      </div>
       <p class="foot">
        <input type="BUTTON" name="FINISH" value="<?php echo $Order_Quit?>" onClick="self.location='/app/member/select.php?uid=<?php echo $uid?>'" class="za_button"> 
      &nbsp;&nbsp; <input type="BUTTON" name="PRINT" value="<?php echo $Order_Print?>" onClick="window.print()" class="za_button">
       </p>
  </div> 
</body>
<?php

// 确定交易生成图片开关
if(GENERATE_IMA_SWITCH) {
    // 需要参数

    $data = array(
        'caption' => $caption, //标题
        'Order_Bet_success' => $Order_Bet_success, //交易成功单号
        'showVoucher' => $showVoucher, //单号
        's_sleague' => $s_sleague,  //联盟处理:联盟样式和显示的样式
        'btype' => $btype,
        'M_Date' => date('m-d',strtotime($row["M_Date"])), //日期
        'Sign' => $Sign,
        's_mb_team' => $s_mb_team,   // 主队
        's_tg_team' => $s_tg_team,  // 客队
        's_m_place' => $s_m_place,  // 选择所属队
        'w_m_rate' => $w_m_rate,  // 赔率
        'Order_Bet_Amount' => $Order_Bet_Amount,  // 交易金额：
        'gold' => $gold, //20
        'Order_Quit' => $Order_Quit, //关闭
        'Order_Print' => $Order_Print, //列印
        'userid' => $memid,
        'playSource' => 2,  //'投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓',
    );

    $redisObj = new Ciredis();
    $redisObj->setOne($showVoucher,serialize($data));
    $redisObj->pushMessage('general_order_image',$showVoucher);
}
?>
</html>