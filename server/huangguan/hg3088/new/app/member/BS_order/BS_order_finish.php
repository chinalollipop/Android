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
//接收传递过来的参数：其中赔率和位置需要进行判断
$gold=$_REQUEST['gold'];
$uid=$_REQUEST['uid'];
$active=$_REQUEST['active'];
$strong=$_REQUEST['strong'];
$line=$_REQUEST['line_type'];
$gid=$_REQUEST['gid'];
$type=$_REQUEST['type'];
$rtype=$_REQUEST['rtype'];
$gnum=$_REQUEST['gnum'];
$ioradio_r_h=$_REQUEST['ioradio_r_h'];
$ioradio_pd=$_REQUEST['ioradio_pd'];
$ioradio_f=$_REQUEST['ioradio_f'];
$odd_f_type=$_REQUEST['odd_f_type'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	setcookie('login_uid','');
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

//下注时的赔率：应该根据盘口进行转换后，与数据库中的赔率进行比较。若不相同，返回下注。
$dbLink  = Dbnew::getInstance('slave');
$sql = "select Language,OpenType,Pay_Type,UserName,Agents,World,Corprator,Super,Admin,ratio,Money,CurType,ID from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
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

$mysql = "select TG_Team,TG_Team_tw,TG_Team_en,MB_Team,MB_Team_tw,MB_Team_en,MB_MID,TG_MID,M_Date,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,MB_LetB_Rate,TG_LetB_Rate,M_LetB,MB_Dime_Rate,TG_Dime_Rate,MB_Dime,TG_Dime,S_Single_Rate,S_Double_Rate,S_1_2,S_3_4,S_5_6,S_7_8,S_9_10,S_11_12,S_13_14,S_15_16,S_17_18,S_19UP,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,MB_LetB_Rate_H,TG_LetB_Rate_H,M_LetB_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Dime_H,TG_Dime_H,M_League,M_League_tw,M_League_en from `".DBPREFIX."match_sports` where Type='BS' and `M_Start`>now() and `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!='' and MB_Team_tw!='' and MB_Team_en!=''";//判断此赛程是否已经关闭：取出此场次信息
$result = mysqli_query($dbMasterLink,$mysql);

$cou=mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);
if($cou==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit();
}else{
	//取出写入数据库的四种语言的客队名称
	$w_tg_team=$row['TG_Team'];
	$w_tg_team_tw=$row['TG_Team_tw'];
	$w_tg_team_en=$row['TG_Team_en'];
	
	//取出四种语言的主队名称，并去掉其中的“主”和“中”字样
	$w_mb_team=filiter_team(trim($row['MB_Team']));
	$w_mb_team_tw=filiter_team(trim($row['MB_Team_tw']));
	$w_mb_team_en=filiter_team(trim($row['MB_Team_en']));
	
	$w_mb_mid=$row['MB_MID'];
	$w_tg_mid=$row['TG_MID'];	
	
	//取出当前字库的主客队伍名称
	$s_mb_team=filiter_team($row[$mb_team]);
	$s_tg_team=filiter_team($row[$tg_team]);
	
	//联盟处理:生成写入数据库的联盟样式和显示的样式，二者有区别
	$s_sleague=$row[$m_league];
	
	//下注时间
	$m_date=$row["M_Date"];
	$showtype=$row["ShowTypeR"];
	$bettime=date('Y-m-d H:i:s');
		
	//根据下注的类型进行处理：构建成新的数据格式，准备写入数据库
	switch ($line){
  	case 1:
	  	$bet_type='独赢';
		$bet_type_tw='獨贏';
		$bet_type_en="1x2";
		$caption=$Order_BS.$Order_1_x_2_betting_order;
		switch ($type){
		case "H":
			$w_m_place=$w_mb_team;
			$w_m_place_tw=$w_mb_team_tw;
			$w_m_place_en=$w_mb_team_en;
			$s_m_place=$s_mb_team;
			$w_m_rate=change_rate($open,$row["MB_Win_Rate"]);
			$mtype='MH';
			break;
		case "C":
			$w_m_place=$w_tg_team;
			$w_m_place_tw=$w_tg_team_tw;
			$w_m_place_en=$w_tg_team_en;
			$s_m_place=$s_tg_team;
			$w_m_rate=change_rate($open,$row["TG_Win_Rate"]);
			$mtype='MC';
			break;
		case "N":
			$w_m_place="和局";
			$w_m_place_tw="和局";
			$w_m_place_en="Flat";
			$s_m_place=$Draw;
			$w_m_rate=change_rate($open,$row["M_Flat_Rate"]);
			$mtype='MN';
			break;
		}
		$Sign="VS.";
		$grape="";
		$gwin=($w_m_rate-1)*$gold;
		$ptype='M';
	break;
	case 2:
	  	$bet_type='让球';
		$bet_type_tw="讓球";
		$bet_type_en="Handicap";	
		$caption=$Order_BS.$Order_Handicap_betting_order;
		$rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100);
		switch ($type){
		case "H":
			$w_m_place=$w_mb_team;
			$w_m_place_tw=$w_mb_team_tw;
			$w_m_place_en=$w_mb_team_en;
			$s_m_place=$s_mb_team;
			$w_m_rate=change_rate($open,$rate[0]);
			$turn_url="/app/member/BS_order/BS_order_r.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
			$mtype='RH';
			break;
		case "C":
			$w_m_place=$w_tg_team;
			$w_m_place_tw=$w_tg_team_tw;
			$w_m_place_en=$w_tg_team_en;
			$s_m_place=$s_tg_team;
			$w_m_rate=change_rate($open,$rate[1]);
			$turn_url="/app/member/BS_order/BS_order_r.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
			$mtype='RC';
			break;
		}
		$Sign=$row['M_LetB'];
		$grape=$Sign;
		if ($showtype=="H"){
			$l_team=$s_mb_team;
			$r_team=$s_tg_team;
			$w_l_team=$w_mb_team;
			$w_l_team_tw=$w_mb_team_tw;
			$w_l_team_en=$w_mb_team_en;
			$w_r_team=$w_tg_team;
			$w_r_team_tw=$w_tg_team_tw;
			$w_r_team_en=$w_tg_team_en;
		}else{
			$r_team=$s_mb_team;
			$l_team=$s_tg_team;
			$w_r_team=$w_mb_team;
			$w_r_team_tw=$w_mb_team_tw;
			$w_r_team_en=$w_mb_team_en;
			$w_l_team=$w_tg_team;
			$w_l_team_tw=$w_tg_team_tw;
			$w_l_team_en=$w_tg_team_en;
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
		$ptype='R';
	break;
	case 3:
		$bet_type='大小';
		$bet_type_tw="大小";
		$bet_type_en="Over/Under";
		$caption=$Order_BS.$Order_Over_Under_betting_order;
		$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100);
		switch ($type){
		case "C":
			$w_m_place=$row["MB_Dime"];
			$w_m_place=str_replace('O','大&nbsp;',$w_m_place);
			$w_m_place_tw=$row["MB_Dime"];
			$w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
			$w_m_place_en=$row["MB_Dime"];
			$w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
			
			$m_place=$row["MB_Dime"];
			
			$s_m_place=$row["MB_Dime"];
			if ($langx=="zh-cn"){
	            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
		    }else if ($langx=="zh-cn"){
		        $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
		    }else if ($langx=="en-us" or $langx=="th-tis"){
		        $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
		    }
			$w_m_rate=change_rate($open,$rate[0]);
			$turn_url="/app/member/BS_order/BS_order_ou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$mtype='OUH';			
			break;
		case "H":
			$w_m_place=$row["TG_Dime"];
			$w_m_place=str_replace('U','小&nbsp;',$w_m_place);
			$w_m_place_tw=$row["TG_Dime"];
			$w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
			$w_m_place_en=$row["TG_Dime"];
			$w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);
			
			$m_place=$row["TG_Dime"];
			
			$s_m_place=$row["TG_Dime"];
			if ($langx=="zh-cn"){
	            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
		    }else if ($langx=="zh-cn"){
		        $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
		    }else if ($langx=="en-us" or $langx=="th-tis"){
		        $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
		    }
			
			$w_m_rate=change_rate($open,$rate[1]);
			$turn_url="/app/member/BS_order/BS_order_ou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$mtype='OUC';		
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
		$ptype='OU';		
	break;
	case 4:
	  	$bet_type='波胆';
		$bet_type_tw="波膽";
		$bet_type_en="Correct Score";
		$caption=$Order_BS.$Order_Correct_Score_betting_order;
		$Rate=str_replace("PDH","MBPDH",$rtype);
		$Rate=str_replace("PDC","TGPDC",$Rate);
		$w_m_rate=$row[$Rate];
		$Sign=str_replace("PDH","+",$rtype);
	    $Sign=str_replace("PDC","+",$Sign);
		$type=$rtype{2};
		if ($type=='H'){
			$w_m_place=$w_mb_team.$Sign;
			$w_m_place_tw=$w_mb_team_tw.$Sign;
			$w_m_place_en=$w_mb_team_en.$Sign;
			$s_m_place=$s_mb_team.$Sign;
		}else if ($type=='C'){
		    $w_m_place=$w_tg_team.$Sign;
			$w_m_place_tw=$w_tg_team_tw.$Sign;
			$w_m_place_en=$w_tg_team_en.$Sign;
			$s_m_place=$s_tg_team.$Sign;
		}
		$Sign="VS.";
		$grape="";
		$order='B';
		$gwin=($w_m_rate-1)*$gold;		
		$ptype='PD';		
		$mtype=$rtype;
	break;
	case 5:
		$bet_type='单双';
		$bet_type_tw="單雙";
		$bet_type_en="Odd/Even";
		$caption=$Order_BS.$Order_Odd_Even_betting_order;
		switch ($rtype){
		case "ODD":
			$w_m_place='单';
			$w_m_place_tw='單';
			$w_m_place_en='odd';
			$s_m_place='('.$Order_Odd.')';
			$w_m_rate=change_rate($open,$row["S_Single_Rate"]);
			break;
		case "EVEN":
			$w_m_place='双';
			$w_m_place_tw='雙';
			$w_m_place_en='even';
			$s_m_place='('.$Order_Even.')';
			$w_m_rate=change_rate($open,$row["S_Double_Rate"]);
			break;
		}
		$Sign="VS.";
		$order='B';
		$gwin=($w_m_rate-1)*$gold;
		$ptype='EO';	
		$mtype=$rtype;
	break;
	case 6:
		$bet_type='总得分';
		$bet_type_tw="總得分";
		$bet_type_en="Total";
		$caption=$Order_BS.$Order_Total_Goals_betting_order;
		switch ($rtype){
		case "1~2":
			$w_m_place='1~2';
			$w_m_place_tw='1~2';
			$w_m_place_en='1~2';
			$s_m_place='(1~2)';
			$w_m_rate=$row["S_1_2"];
			break;
		case "3~4":
			$w_m_place='3~4';
			$w_m_place_tw='3~4';
			$w_m_place_en='3~4';
			$s_m_place='(3~4)';
			$w_m_rate=$row["S_3_4"];
			break;
		case "5~6":
			$w_m_place='5~6';
			$w_m_place_tw='5~6';
			$w_m_place_en='5~6';
			$s_m_place='(5~6)';
			$w_m_rate=$row["S_5_6"];
			break;
		case "7~8":
			$w_m_place='7~8';
			$w_m_place_tw='7~8';
			$w_m_place_en='7~8';
			$s_m_place='(7~8)';
			$w_m_rate=$row["S_7_8"];
			break;			
		case "9~10":
			$w_m_place='9~10';
			$w_m_place_tw='9~10';
			$w_m_place_en='9~10';
			$s_m_place='(9~10)';
			$w_m_rate=$row["S_9_10"];
			break;			
		case "11~12":
			$w_m_place='11~12';
			$w_m_place_tw='11~12';
			$w_m_place_en='11~12';
			$s_m_place='(11~12)';
			$w_m_rate=$row["S_11_12"];
			break;			
		case "13~14":
			$w_m_place='13~14';
			$w_m_place_tw='13~14';
			$w_m_place_en='13~14';
			$s_m_place='(13~14)';
			$w_m_rate=$row["S_13_14"];
			break;			
		case "15~16":
			$w_m_place='15~16';
			$w_m_place_tw='15~16';
			$w_m_place_en='15~16';
			$s_m_place='(15~16)';
			$w_m_rate=$row["S_15_16"];
			break;			
		case "17~18":
			$w_m_place='17~18';
			$w_m_place_tw='17~18';
			$w_m_place_en='17~18';
			$s_m_place='(17~18)';
			$w_m_rate=$row["S_17_18"];
			break;						
		case "T19":
			$w_m_place='T19';
			$w_m_place_tw='T19';
			$w_m_place_en='T19';
			$s_m_place='(T19)';
			$w_m_rate=$row["S_19UP"];
			break;
		}
		$Sign="VS.";
		$order='B';
		$gwin=($w_m_rate-1)*$gold;
		$ptype='T';
		$mtype=$rtype;				
	break;
	case 11:
	  	$bet_type='半场独赢';
		$bet_type_tw="半場獨贏";
		$bet_type_en="1st Half 1x2";
		$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
		$caption=$Order_BS.$Order_1st_Half_1_x_2_betting_order;
		switch ($type){
		case "H":
			$w_m_place=$w_mb_team;
			$w_m_place_tw=$w_mb_team_tw;
			$w_m_place_en=$w_mb_team_en;
			$s_m_place=$row[$mb_team];
			$w_m_rate=change_rate($open,$row["MB_Win_Rate_H"]);
			$mtype='VMH';
			break;
		case "C":
			$w_m_place=$w_tg_team;
			$w_m_place_tw=$w_tg_team_tw;
			$w_m_place_en=$w_tg_team_en;
			$s_m_place=$row[$tg_team];
			$w_m_rate=change_rate($open,$row["TG_Win_Rate_H"]);
			$mtype='VMC';
			break;
		case "N":
			$w_m_place="和局";
			$w_m_place_tw="和局";
			$w_m_place_en="Flat";
			$s_m_place=$Draw;
			$w_m_rate=change_rate($open,$row["M_Flat_Rate_H"]);
			$mtype='VMN';
			break;
		}
		$Sign="VS.";
		$grape="";
		$gwin=($w_m_rate-1)*$gold;
		$ptype='VM';
	break;
	case 12:
		$bet_type='半场让球';
		$bet_type_tw="半場讓球";
		$bet_type_en="1st Half Handicap";
		$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
		$caption=$Order_BS.$Order_1st_Half_Handicap_betting_order;
		$rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_H"],$row["TG_LetB_Rate_H"],100);
		switch ($type){
		case "H":
			$w_m_place=$w_mb_team;
			$w_m_place_tw=$w_mb_team_tw;
			$w_m_place_en=$w_mb_team_en;
			$s_m_place=$row[$mb_team];
			$w_m_rate=change_rate($open,$rate[0]);
			$turn_url="/app/member/BS_order/BS_order_hr.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
			$mtype='VRH';
			break;
		case "C":
			$w_m_place=$w_tg_team;
			$w_m_place_tw=$w_tg_team_tw;
			$w_m_place_en=$w_tg_team_en;
			$s_m_place=$row[$tg_team];
			$w_m_rate=change_rate($open,$rate[1]);
			$turn_url="/app/member/BS_order/BS_order_hr.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
			$mtype='VRC';
			break;
		}
		$Sign=$row['M_LetB_H'];
		$grape=$Sign;
		if ($showtype=="H"){
			$l_team=$s_mb_team;
			$r_team=$s_tg_team;
			
			$w_l_team=$w_mb_team;
			$w_l_team_tw=$w_mb_team_tw;
			$w_l_team_en=$w_mb_team_en;
			$w_r_team=$w_tg_team;
			$w_r_team_tw=$w_tg_team_tw;
			$w_r_team_en=$w_tg_team_en;
		}else{
			$r_team=$s_mb_team;
			$l_team=$s_tg_team;
			$w_r_team=$w_mb_team;
			$w_r_team_tw=$w_mb_team_tw;
			$w_r_team_en=$w_mb_team_en;
			$w_l_team=$w_tg_team;
			$w_l_team_tw=$w_tg_team_tw;
			$w_l_team_en=$w_tg_team_en;
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
		$ptype='VR';
	break;
	case 13:
  		$bet_type='半场大小';
		$bet_type_tw="半場大小";
		$bet_type_en="1st Half Over/Under";
		$caption=$Order_BS.$Order_1st_Half_Over_Under_betting_order;
		$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
		$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_H"],$row["TG_Dime_Rate_H"],100);
		switch ($type){
		case "C":
			$w_m_place=$row["MB_Dime_H"];
			$w_m_place=str_replace('O','大&nbsp;',$w_m_place);
			$w_m_place_tw=$row["MB_Dime_H"];
			$w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
			$w_m_place_en=$row["MB_Dime_H"];
			$w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
			
			$m_place=$row["MB_Dime_H"];
			
			$s_m_place=$row["MB_Dime_H"];
			if ($langx=="zh-cn"){
	            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
		    }else if ($langx=="zh-cn"){
		        $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
		    }else if ($langx=="en-us" or $langx=="th-tis"){
		        $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
			}
			$w_m_rate=change_rate($open,$rate[0]);
			$turn_url="/app/member/BS_order/BS_order_hou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$mtype='VOUH';		
			break;
		case "H":
			$w_m_place=$row["TG_Dime_H"];
			$w_m_place=str_replace('U','小&nbsp;',$w_m_place);
			$w_m_place_tw=$row["TG_Dime_H"];
			$w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
			$w_m_place_en=$row["TG_Dime_H"];
			$w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);
			
			$m_place=$row["TG_Dime_H"];
			
			$s_m_place=$row["TG_Dime_H"];
			if ($langx=="zh-cn"){
	            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
		    }else if ($langx=="zh-cn"){
		        $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
		    }else if ($langx=="en-us" or $langx=="th-tis"){
		        $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
			}
			$w_m_rate=change_rate($open,$rate[1]);	
			$turn_url="/app/member/BS_order/BS_order_hou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$mtype='VOUC';
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
		$ptype='VOU';		
	break;
	}

	if ($line==11 or $line==12 or $line==13){
		$bottom1_tw="-&nbsp;<font color=#666666>[上半]</font>&nbsp;";
		$bottom1="-&nbsp;<font color=#666666>[上半]</font>&nbsp;";
		$bottom1_en="-&nbsp;<font color=#666666>[1st Half]</font>&nbsp;";
	}
	
	if ($line==2 or $line==3 or $line==12 or $line==13){
		if ($w_m_rate!=$ioradio_r_h){
		    $turn_url=$turn_url.'&error_flag=1';
		    echo "<script language='javascript'>self.location='$turn_url';</script>";
		    exit();
		}
		$oddstype=$odd_f_type;
	}else{
		$oddstype='';
	}
	$s_m_place=filiter_team(trim($s_m_place));
	
	$w_mid="<br>[".$row['MB_MID']."]vs[".$row['TG_MID']."]<br>";
	$lines=$row['M_League'].$w_mid.$w_mb_team."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team."<br>";
	$lines=$lines."<FONT color=#cc0000>$w_m_place</FONT>&nbsp;$bottom1@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";	
	$lines_tw=$row['M_League_tw'].$w_mid.$w_mb_team_tw."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_tw."<br>";
	$lines_tw=$lines_tw."<FONT color=#cc0000>$w_m_place_tw</FONT>&nbsp;$bottom1_tw@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";
	$lines_en=$row['M_League_en'].$w_mid.$w_mb_team_en."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_en."<br>";
	$lines_en=$lines_en."<FONT color=#cc0000>$w_m_place_en</FONT>&nbsp;$bottom1_en@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";	

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

$sql = "INSERT INTO ".DBPREFIX."web_report_data	(ID,MID,Active,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball) values ('$id','$gid','$active','$line','$mtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','BS','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball')";
mysqli_query($dbMasterLink,$sql) or die ("操作失败!");
$ouid=mysqli_insert_id($dbMasterLink);
$sql = "update ".DBPREFIX.MEMBERTABLE." set Money='$havemoney' where UserName='$memname'";
mysqli_query($dbMasterLink,$sql) or die ("操作失败!!");

if ($active==33){
	$caption=str_replace($Order_BS,$Order_BS.$Order_Early_Market,$caption);
}
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
  	<p class="team"><?php echo $s_sleague?>&nbsp;<?php echo $btype?>&nbsp;<?php echo date('m-d',strtotime($row["M_Date"]))?><BR><?php echo $s_mb_team?>&nbsp;&nbsp;<font color=#cc0000><?php echo $Sign?></font>&nbsp;&nbsp;<?php echo $s_tg_team?><br><em><?php echo $s_m_place?></em>&nbsp;@&nbsp;<em><strong><?php echo $w_m_rate?></strong></em></p>
  	<p class="deal-money"><?php echo $Order_Bet_Amount?><?php echo $gold?></p>
	<!--<p class="canwin-money"><?php/*=$Order_Estimated_Payout*/?><FONT id=pc color=#cc0000><?php/*=$gwin*/?></FONT></p>-->
    </div>
    <p class="foot">
      <input type="button" name="FINISH" value="<?php echo $Order_Quit?>" onClick="parent.close_bet();" class="no">
      <input type="button" name="PRINT" value="<?php echo $Order_Print?>" onClick="window.print()" class="yes">
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
<?php
}
?>