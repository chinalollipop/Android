<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
/*if($userid<=0){
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>alert('请登陆后再进行投单！');top.SI2_mem_index.mem_order.location.href='../select.php?uid=$uid&langx=zh-cn';</script>\n";exit;
}*/
require ("../include/define_function_list.inc.php");
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$teamcount=$_REQUEST['teamcount'];
$gold=$_REQUEST['gold'];
$active=$_REQUEST['active'];
$wagerDatas=$_REQUEST['wagerDatas'];
require ("../include/traditional.$langx.inc.php");
ini_set('display_errors','Off');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}else{

    $token=$_REQUEST['token'];
    if($token == $_SESSION['bet_token']){ // 防止重复订单
        echo resubmitAction() ;
        exit();
    }else{
        $_SESSION['bet_token'] = $token ;
    }

$sql = "select Money from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
$memrow = mysqli_fetch_assoc($result);
$open=$_SESSION['OpenType'];
$pay_type =$_SESSION['Pay_Type'];
$memname=$_SESSION['UserName'];
$agents=$_SESSION['Agents'];
$world=$_SESSION['World'];
$corprator=$_SESSION['Corprator'];
$super=$_SESSION['Super'];
$admin=$_SESSION['Admin'];
$w_ratio=$_SESSION['ratio'];
$w_current=$_SESSION['CurType'];
$HMoney=$memrow['Money'];
if($HMoney < $gold || $gold<10 || $HMoney<=0){
	echo attention("$User_insufficient_balance",$uid,$langx);
	exit();
}	

$memid= $_SESSION['userid'];
$test_flag=$_SESSION['test_flag'];
$wagerDatas_array=explode("|",$wagerDatas);

$rates=1;
$i=1;
for ($i=0;$i<$teamcount;$i++){
	 $data_array=explode(",",$wagerDatas_array[$i]);
	 $mid=$data_array[0];
	 $type=$data_array[1];
     $showType=$data_array[2];
//	 $rates=$rates*$data_array[5];
	 if($type!=""){

         $mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and MID='$mid' and Cancel!=1 and Open=1 and MB_Team!='' and MB_Team_tw!=''";
         $resultL = mysqli_query($dbLink,$mysqlL);
         $couL=mysqli_num_rows($resultL);
         if($couL==0) {
             echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
             exit();
         }

        $mysql = "select MB_Team,TG_Team,MB_Team_tw,TG_Team_tw,MB_Team_en,TG_Team_en,M_League,M_League_tw,M_League_en,MB_P_Win_Rate,MB_MID,TG_MID,MB_P_LetB_Rate,TG_P_Win_Rate,ShowTypeP,M_P_LetB,TG_P_LetB_Rate,MB_P_Dime,MB_P_Dime_Rate,TG_P_Dime,TG_P_Dime_Rate,S_P_Single_Rate,S_P_Double_Rate,MB_P_Dime_H,TG_P_Dime_H,MB_Dime_H, MB_Dime_S_H, TG_Dime_H, TG_Dime_S_H,MB_P_Dime_Rate_H, MB_P_Dime_Rate_S_H, TG_P_Dime_Rate_H, TG_P_Dime_Rate_S_H,M_Date from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and MID='$mid' and Open=1 and MB_Team!='' and MB_Team_tw!=''";
        $result = mysqli_query($dbCenterMasterDbLink,$mysql);
        $cou=mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);
        if($cou==0){
           echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
           exit();
        }else{
         //取出多种语言的主队名称，并去掉其中的“主”和“中”字样
        $w_mb_team=filiter_team(trim($row['MB_Team']));
        $w_mb_team_tw=filiter_team(trim($row['MB_Team_tw']));	
        $w_mb_team_en=filiter_team(trim($row['MB_Team_en']));
	
        $w_tg_team=filiter_team(trim($row['TG_Team']));
        $w_tg_team_tw=filiter_team(trim($row['TG_Team_tw']));
        $w_tg_team_en=filiter_team(trim($row['TG_Team_en']));
	
         //取出当前字库的主客队伍名称
        $s_mb_team=filiter_team($row[$mb_team]);
        $s_tg_team=filiter_team($row[$tg_team]);
	
         //联盟处理:生成写入数据库的联盟样式和显示的样式，二者有区别
		$w_league=$row['M_League'];
		$w_league_tw=$row['M_League_tw'];
		$w_league_en=$row['M_League_en'];
		$league=$row[$m_league];
			
		//根据下注的类型进行处理：构建成新的数据格式，准备写入数据库
		
		$bet_type=$teamcount."串1";
		$bet_type_tw=$teamcount."串1";
		$bet_type_en=$teamcount."Parlay1";
		$caption=$Order_Basketball.$Order_Handicap_Parlay_betting_order;
	    switch($type){
        case 'PMH': // 主队独赢
            $w_m_place=$w_mb_team;
            $w_m_place_tw=$w_mb_team_tw;
            $w_m_place_en=$w_mb_team_en;

            $place=$s_mb_team;
            $team=$s_mb_team;
            $s_mb_team=$s_tg_team;
            $s_tg_team=$team;

            $w_m_rate=change_rate($open,$row["MB_P_Win_Rate"]);
            $sign='VS.';
            $Mtype='MH';
            $mmid="(".$row['MB_MID'].")";
            $showtypeT=$U_30;
            break;
        case 'PMC': // 客队独赢
            $w_m_place=$w_tg_team;
            $w_m_place_tw=$w_tg_team_tw;
            $w_m_place_en=$w_tg_team_en;

            $place=$s_tg_team;
            $team=$s_mb_team;
            $s_mb_team=$s_tg_team;
            $s_tg_team=$team;

            $w_m_rate=change_rate($open,$row["TG_P_Win_Rate"]);
            $sign='VS.';
            $Mtype='MC';
            $mmid="(".$row['TG_MID'].")";
            $showtypeT=$U_30;
            break;

        case 'EOO': // 主队单
            $w_m_place=$w_mb_team;
            $w_m_place_tw=$w_mb_team_tw;
            $w_m_place_en=$w_mb_team_en;
            $place= $Order_Odd ;
           /* $place=$s_mb_team;
            $team=$s_mb_team;
            $s_mb_team=$s_tg_team;
            $s_tg_team=$team;*/

            $w_m_rate=change_rate($open,$row["S_P_Single_Rate"]);
            $sign='VS.';
            $Mtype='ODD';
            $mmid="(".$row['MB_MID'].")";
            $showtypeT=$U_31;
            break;
        case 'EOE': // 客队双
            $w_m_place=$w_tg_team;
            $w_m_place_tw=$w_tg_team_tw;
            $w_m_place_en=$w_tg_team_en;
            $place = $Order_Even ;
           /* $place=$s_tg_team;
            $team=$s_mb_team;
            $s_mb_team=$s_tg_team;
            $s_tg_team=$team;*/

            $w_m_rate=change_rate($open,$row["S_P_Double_Rate"]);
            $sign='VS.';
            $Mtype='EVEN';
            $mmid="(".$row['TG_MID'].")";
            $showtypeT=$U_31;
            break;

		case 'PRH': // 主队让球
			$w_m_place=$w_mb_team;
			$w_m_place_tw=$w_mb_team_tw;
			$w_m_place_en=$w_mb_team_en;

			$place=$s_mb_team;

			$w_m_rate=change_rate($open,$row["MB_P_LetB_Rate"]);
			if ($row['ShowTypeP']=='C'){
            $w_team=$w_mb_team;
			$w_mb_team=$w_tg_team;
			$w_tg_team=$w_team;
			$w_team_tw=$w_mb_team_tw;
			$w_mb_team_tw=$w_tg_team_tw;
			$w_tg_team_tw=$w_team_tw;
			$w_team_en=$w_mb_team_en;
			$w_mb_team_en=$w_tg_team_en;
			$w_tg_team_en=$w_team_en;
            $team=$s_mb_team;
            $s_mb_team=$s_tg_team;
            $s_tg_team=$team;
			}
			$Mtype='RH';
			$sign=$row['M_P_LetB'];
            if($sign==''){
                echo attention("让球参数异常，请刷新赛事~~",$uid,$langx);
                exit();
            }
			$m_place=$row['M_P_LetB'];
			$mmid="(".$row['MB_MID'].")";
			break;
		case 'PRC':  // 客队让球
			$w_m_place=$w_tg_team;
			$w_m_place_tw=$w_tg_team_tw;
			$w_m_place_en=$w_tg_team_en;

			$place=$s_tg_team;

            $w_m_rate=change_rate($open,$row["TG_P_LetB_Rate"]);
			if ($row['ShowTypeP']=='C'){
            $w_team=$w_mb_team;
			$w_mb_team=$w_tg_team;
			$w_tg_team=$w_team;
			$w_team_tw=$w_mb_team_tw;
			$w_mb_team_tw=$w_tg_team_tw;
			$w_tg_team_tw=$w_team_tw;
			$w_team_en=$w_mb_team_en;
			$w_mb_team_en=$w_tg_team_en;
			$w_tg_team_en=$w_team_en;
            $team=$s_mb_team;
            $s_mb_team=$s_tg_team;
            $s_tg_team=$team;
			}
			$Mtype='RC';
			$sign=$row['M_P_LetB'];
            if($sign==''){
                echo attention("让球参数异常，请刷新赛事~~",$uid,$langx);
                exit();
            }
			$m_place=$row['M_P_LetB'];
			$mmid="(".$row['TG_MID'].")";
			break;	
		case 'POUC': // 主队大小
			$w_m_place=$row["MB_P_Dime"];
			$w_m_place=str_replace('O','大&nbsp;',$w_m_place);
			$w_m_place_tw=$row["MB_P_Dime"];
			$w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
			$w_m_place_en=$row["MB_P_Dime"];
			$place=$row['MB_P_Dime'];
			if ($langx=="zh-cn"){
	            $place=str_replace('O','大&nbsp;',$place);
		    }else if ($langx=="zh-cn"){
		         $place=str_replace('O','大&nbsp;',$place);
		    }else if ($langx=="en-us" or $langx=="th-tis"){
			     $place=str_replace('O','over&nbsp;',$place);
			}
			$w_m_rate=change_rate($open,$row['MB_P_Dime_Rate']);
			$sign='VS.';
			$Mtype='OUH';
			$m_place=$row['MB_P_Dime'];
			$mmid="(".$row['MB_MID'].")";
			break;
		case 'POUH': // 客队大小
			$w_m_place=$row["TG_P_Dime"];
			$w_m_place=str_replace('U','小&nbsp;',$w_m_place);
			$w_m_place_tw=$row["TG_P_Dime"];
			$w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
			$w_m_place_en=$row["TG_P_Dime"];	
			$place=$row['TG_P_Dime'];
		    if ($langx=="zh-cn"){
		        $place=str_replace('U','小&nbsp;',$place);
		    }else if ($langx=="zh-cn"){
		        $place=str_replace('U','小&nbsp;',$place);
			}else if ($langx=="en-us" or $langx=="th-tis"){
			    $place=str_replace('U','under&nbsp;',$place);
			 }
			$w_m_rate=change_rate($open,$row['TG_P_Dime_Rate']);
			$sign='VS.';
			$Mtype='OUC';
			$m_place=$row['TG_P_Dime'];
			$mmid="(".$row['TG_MID'].")";												
			break;
        case 'POUHO': // 综合过关，得分大小，主队大
            $w_m_place=$row["MB_Dime_H"];
            $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
            $w_m_place_tw=$row["MB_Dime_H"];
            $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
            $w_m_place_en=$row["MB_Dime_H"];
            $place = $s_mb_team.'.'.$row['MB_Dime_H'];
            if ($langx=="zh-cn"){
                $place=str_replace('O','大&nbsp;',$place);
            }else if ($langx=="en-us" or $langx=="th-tis"){
                $place=str_replace('O','over&nbsp;',$place);
            }
            $w_m_rate=change_rate($open,$row['MB_P_Dime_Rate_H']);
            $Mtype='TOUH';
            $sign='VS.';
            $m_place=$row['MB_Dime_H'];
            $mmid="(".$row['MB_MID'].")";
            break;
        case 'POUHU':// 综合过关，得分大小，主队小
            $w_m_place=$row["MB_Dime_S_H"];
            $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
            $w_m_place_tw=$row["MB_Dime_S_H"];
            $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
            $w_m_place_en=$row["MB_Dime_S_H"];
            $place = $s_mb_team.'.'.$row['MB_Dime_S_H'];
            if ($langx=="zh-cn"){
                $place=str_replace('U','小&nbsp;',$place);
            }else if ($langx=="en-us" or $langx=="th-tis"){
                $place=str_replace('U','under&nbsp;',$place);
            }
            $w_m_rate=change_rate($open,$row['MB_P_Dime_Rate_S_H']);
            $Mtype='TOUH';
            $sign='VS.';
            $m_place=$row['MB_Dime_S_H'];
            $mmid="(".$row['MB_MID'].")";
            break;
        case 'POUCO': // 综合过关，得分大小，客队大
            $w_m_place=$row["TG_Dime_H"];
            $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
            $w_m_place_tw=$row["TG_Dime_H"];
            $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
            $w_m_place_en=$row["TG_Dime_H"];
            $place = $s_tg_team.'.'.$row['TG_Dime_H'];
            if ($langx=="zh-cn"){
                $place=str_replace('O','大&nbsp;',$place);
            }else if ($langx=="en-us" or $langx=="th-tis"){
                $place=str_replace('O','over&nbsp;',$place);
            }
            $w_m_rate=change_rate($open,$row['TG_P_Dime_Rate_H']);
            $m_place=$row['TG_Dime_H'];
            $Mtype='TOUC';
            $sign='VS.';
            $mmid="(".$row['TG_MID'].")";
            break;
        case 'POUCU': // 综合过关，得分大小，客队小
            $w_m_place=$row["TG_Dime_S_H"];
            $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
            $w_m_place_tw=$row["TG_Dime_S_H"];
            $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
            $w_m_place_en=$row["TG_Dime_S_H"];
            $place = $s_tg_team.'.'.$row['TG_Dime_S_H'];
            if ($langx=="zh-cn"){
                $place=str_replace('U','小&nbsp;',$place);
            }else if ($langx=="en-us" or $langx=="th-tis"){
                $place=str_replace('U','under&nbsp;',$place);
            }
            $w_m_rate=change_rate($open,$row['TG_P_Dime_Rate_S_H']);
            $m_place=$row['TG_Dime_S_H'];
            $Mtype='TOUC';
            $sign='VS.';
            $mmid="(".$row['TG_MID'].")";
            break;
    }

        if ($w_m_place=='' || $place==''){
            echo attention("玩法数据不完整，请刷新后再重新投注",$uid,$langx);
            exit();
        }
    
        if($w_m_rate=='' || $w_m_rate==0){
			echo attention("赔率为空,请稍后再试！",$uid,$langx);
			exit();
		}
    
		$date=date('m-d',strtotime($row["M_Date"]));
		$lines=$lines.$row['M_League']."&nbsp;".$date."<br>";
		$lines=$lines.$w_mb_team."&nbsp;<FONT color=#CC0000>".$sign."</FONT>&nbsp;".$w_tg_team."<br>";
		$lines=$lines."<FONT color=#cc0000>".$mmid."&nbsp;".$place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".number_format($w_m_rate,2)."</b></FONT><br>";
		
		$lines_tw=$lines_tw.$row['M_League_tw']."&nbsp;".$date."<br>";
		$lines_tw=$lines_tw.$w_mb_team_tw."&nbsp;<FONT color=#CC0000>".$sign."</FONT>&nbsp;".$w_tg_team_tw."<br>";
		$lines_tw=$lines_tw."<FONT color=#cc0000>".$mmid."&nbsp;".$place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".number_format($w_m_rate,2)."</b></FONT><br>";
		
		$lines_en=$lines_en.$row['M_League_en']."&nbsp;".$date."<br>";
		$lines_en=$lines_en.$w_mb_team_en."&nbsp;<FONT color=#CC0000>".$sign."</FONT>&nbsp;".$w_tg_team_en."<br>";
		$lines_en=$lines_en."<FONT color=#cc0000>".$mmid."&nbsp;".$place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".number_format($w_m_rate,2)."</b></FONT><br>";
			
		$betplace=$betplace.$league."&nbsp;".$date."<br>";
		$betplace=$betplace.$s_mb_team."&nbsp;<FONT color=#CC0000>".$sign."</FONT>&nbsp;".$s_tg_team."<br>";
		$betplace=$betplace."<FONT color='#cc0000' class='team_name'>".$place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b class='rate_p'>".number_format($w_m_rate,2)."</b></FONT><br><br>";
        // 原来的
//		$betplace=$betplace."<FONT color='#cc0000' class='team_name'>".$mmid."&nbsp;".$place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".number_format($w_m_rate,2)."</b></FONT><br><br>";
//        $betplace=$betplace."<FONT color='#cc0000' class='team_name'>".$place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b class='bet_rate'>".number_format($w_m_rate,2)."</b></FONT><br><br>";
		$m_gid[]=$mid;
		$m_rate[]=$w_m_rate;
		$ktype[]=$Mtype;
		$show_type[]=$row['ShowTypeP'];
		$r_place[]=$m_place;

            $rates=$rates*$w_m_rate; // 将全部的赔率互乘，方便计算可赢金额
	   }
	 }
}
$gid=implode(",",$m_gid);
$gtype=implode(",",$ktype);
$w_m_rate=implode(",",$m_rate);
$grape=implode(",",$r_place);
$showtype=implode(",",$show_type);
$gwin=round($gold*$rates-$gold,2);
$ptype='PR';
$line=8;
$date=$row["M_Date"];
$bettime=date('Y-m-d H:i:s');
$betid=strtoupper(substr(md5(time()),0,rand(17,20)));
$ip_addr = get_ip();

$psql = "select A_Point,B_Point,C_Point,D_Point from ".DBPREFIX."web_agents_data where UserName='$agents'";
$result = mysqli_query($dbLink,$psql);
$prow = mysqli_fetch_assoc($result);
$a_point=$prow['A_Point']+0;
$b_point=$prow['B_Point']+0;
$c_point=$prow['C_Point']+0;
$d_point=$prow['D_Point']+0;

$showVoucher=show_voucher($wtype);

	$begin = mysqli_query($dbMasterLink,"start transaction");
	$lockResult = mysqli_query($dbMasterLink,"select Money from ".DBPREFIX.MEMBERTABLE." where ID = ".$memid." for update");
	if($begin && $lockResult){
		$checkRow = mysqli_fetch_assoc($lockResult);
		$HMoney=$Money=$checkRow['Money'];
		$havemoney=$HMoney-$gold;
		if($havemoney<0 || $gold<=0 || $HMoney<=0){
			mysqli_query($dbMasterLink,"ROLLBACK");
			echo attention("$User_insufficient_balance",$uid,$langx);
			exit();
		}
		$sql = "INSERT INTO ".DBPREFIX."web_report_data	(MID,Glost,playSource,Userid,testflag,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,BetID,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball) values ('$gid',$Money,1,$memid,$test_flag,'$active','$showVoucher','$line','$gtype','$date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','BK','$w_current','$w_ratio','$betid','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball')";
		$insertBet=mysqli_query($dbMasterLink,$sql);
		if($insertBet){
            $lastId=mysqli_insert_id($dbMasterLink);
			$moneyLogRes=addAccountRecords(array($memid,$memname,$test_flag,$Money,$gold*-1,$havemoney,1,1,$lastId,"BK投注$gtype"));
			if($moneyLogRes){
				$sql1 = "update ".DBPREFIX.MEMBERTABLE." set Money=".$havemoney." , Online=1 , OnlineTime=now() where ID=".$memid;
				$updateMoney=mysqli_query($dbMasterLink,$sql1);	
				if($updateMoney){
					mysqli_query($dbMasterLink,"COMMIT");
				}else{
					mysqli_query($dbMasterLink,"ROLLBACK");
					die("操作失败3");		
				}
			}else{
				mysqli_query($dbMasterLink,"ROLLBACK");
				die("操作失败2");		
			}
		}else{
			mysqli_query($dbMasterLink,"ROLLBACK");
			die("操作失败1");		
		}
	}else{
		mysqli_query($dbMasterLink,"ROLLBACK");
		die("操作失败0");
	}

if ($active==22){
	$caption=str_replace($Order_Basketball,$Order_Basketball.$Order_Early_Market,$caption);
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
       <div class="fin_title"><p class="fin_acc">成功提交注单！</p><p class="p-underline"><?php echo $Order_Bet_success?>&nbsp;<?php echo $showVoucher;?></p><p class="error">危险球 - 待确认</p></div>
       <p class="team"><?php echo $betplace?></p>
       <p class="deal-money"><?php echo $Order_Bet_Amount?><?php echo $gold?></p>
       <!--<p class="canwin-money"><?php/*=$Order_Estimated_Payout*/?><FONT id=pc color=#cc0000><?php/*=$gwin*/?></FONT></p>-->
      </div>
       <p class="foot">
        <input type="button" name="FINISH" value="<?php echo $Order_Quit?>" onClick="parent.close_bet();" class="no">
      &nbsp;&nbsp; <input type="BUTTON" name="PRINT" value="<?php echo $Order_Print?>" onClick="window.print()" class="yes">
       </p>
  </div>  
</body>
<?php

// 确定交易生成图片开关
if(GENERATE_IMA_SWITCH) {
//    var_dump($betplace);
    // 需要参数  综合过关

    $data = array(
        'caption' => $caption, //标题
        'Order_Bet_success' => $Order_Bet_success, //交易成功单号
        'showVoucher' => $showVoucher, //单号
        'betplace' => $betplace, //新增 综合过关 三注截取图片
        'Order_Bet_Amount' => $Order_Bet_Amount,  // 交易金额：
        'gold' => $gold, //20
        'Order_Quit' => $Order_Quit, //关闭
        'Order_Print' => $Order_Print, //列印
        'userid' => $memid,
        'playSource' => 1,  //'投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓',
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
