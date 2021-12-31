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
$type=$_REQUEST['type'];
$wtype=$_REQUEST['wtype'];
$rtype=$_REQUEST['rtype'];
$gnum=$_REQUEST['gnum'];
$strong=$_REQUEST['strong'];
$odd_f_type=$_REQUEST['odd_f_type'];
$gold=$_REQUEST['gold'];
$active=$_REQUEST['active'];
$line=$_REQUEST['line_type'];
$restcredit=$_REQUEST['restcredit'];
$play_Source = 22 ; //'投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓,22 综合版',
require ("../include/traditional.$langx.inc.php");

if($odd_f_type=='E'){
	$r_num=1;
}else{
	$r_num=0;
}

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>top.location.href='/'</script>";
	exit;
}

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
$open= $_SESSION['OpenType'];
$pay_type = $_SESSION['Pay_Type'];
$memname= $_SESSION['UserName'];
$agents= trim($_SESSION['Agents']);
$world= $_SESSION['World'];
$corprator= $_SESSION['Corprator'];
$super= $_SESSION['Super'];
$admin= $_SESSION['Admin'];
$w_ratio= $_SESSION['ratio'];
$HMoney=$memrow['Money'];
if($HMoney < $gold || $gold<10 || $HMoney<=0){
	echo attention("$User_insufficient_balance",$uid,$langx);
	exit();
}
$w_current= $_SESSION['CurType'];
$memid= $_SESSION['userid'] ;
$test_flag= $_SESSION['test_flag'];

//___________________________________________________________________________
$allcount=0;
if($flushWay == 'ra') { //正网
    $accoutArr = getFlushWaterAccount();
}
$accoutArrNum = count($accoutArr);
$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
	$allcount = $allcount + 1;
	$site=$value['Datasite'];
	$suid=$value['Uid'];
//	$curl->set_referrer("".$site."/app/member/BK_index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=3");

    $curl->set_referrer("".$site."");
    $postdata = array(
        'p' => 'Other_order_view',
        'ver' => date('Y-m-d-H').$value['Ver'],
        'langx' => $langx,
        'uid' => $value['Uid'],
        'odd_f_type' => $odd_f_type,
        'gid' => $gid,
        'gtype' => 'BK',
    );
	// 滚球独赢
	switch ($line) {
	    case '21': // 滚球独赢
	        $html_data = $curl->fetch_url("" . $site . "/app/member/BK_order/BK_order_rm.php?gid=$gid&uid=$suid&type=$type&odd_f_type=$odd_f_type&langx=$langx");
	        break;
	    case '23': // 滚球得分大小
            $postdata['wtype']=$wtype;
            $postdata['chose_team']=$type;
//	        $html_data = $curl->fetch_url("" . $site . "/app/member/BK_order/BK_order_rouhc.php?gid=$gid&uid=$suid&wtype=$wtype&type=$type&odd_f_type=$odd_f_type&langx=$langx&langx=$langx");
            $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
	        break;
	}
//	$msg_c=explode("@",$html_data);
    $aData = xmlToArray($xml_data);
//	if(sizeof($msg_c)>1){
    if($aData['ioratio']>0){
		break;
	}elseif($allcount==$accoutArrNum){
	    echo attention("$Order_Odd_changed_please_game_again",$uid,$langx);
		exit();
	}
}
//___________________________________________________________________________


$mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
$resultL = mysqli_query($dbLink,$mysqlL);
$couL=mysqli_num_rows($resultL);
if($couL==0) {
    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
    exit();
}

$mysql = "select M_Start,MB_Team,TG_Team,MB_Team_tw,TG_Team_tw,MB_Team_en,TG_Team_en,M_Date,ShowTypeRB,M_League,M_League_tw,M_League_en,MB_Ball,TG_Ball,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,MB_Dime_Rate_RB_H,TG_Dime_Rate_RB_H,MB_Dime_RB,TG_Dime_RB,MB_Dime_RB_H,TG_Dime_RB_H,MB_Dime_RB_S_H,MB_Dime_Rate_RB_S_H,TG_Dime_RB_S_H,TG_Dime_Rate_RB_S_H,S_Single_Rate,S_Double_Rate,MB_LetB_Rate_RB,TG_LetB_Rate_RB,M_LetB_RB,MB_MID,TG_MID,M_Duration from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and `MID`='$gid' and Open=1 and MB_Team!=''";
$result = mysqli_query($dbCenterMasterDbLink,$mysql);

$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit();
}
	
 	if((isset($row['MB_Ball'])&&$row['MB_Ball']>0) || (isset($row['TG_Ball'])&&$row['TG_Ball']>0)){
		$inball=$row['MB_Ball'].":".$row['TG_Ball'];
		$inball1=$inball;
		$mb_ball = $row['MB_Ball'];
		$tg_ball = $row['TG_Ball'];	
	}else{
		$mysqlBall = "select MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and Open=1 and MB_Team='".$row['MB_Team']."' and TG_Team='".$row['TG_Team']."' and M_League='".$row['M_League']." and M_Start='".$row['M_Start']."' limit 1";
		$resultBall = mysqli_query($dbCenterMasterDbLink,$mysqlBall);
		$rowBall = mysqli_fetch_assoc($resultBall);
		$inball=$rowBall['MB_Ball'].":".$rowBall['TG_Ball'];
		$inball1=$inball;
		$mb_ball = $rowBall['MB_Ball'];
		$tg_ball = $rowBall['TG_Ball'];	
	}	

	$detailsData=array();
	$moreMethod = array();
	if($_REQUEST['id']&&$_REQUEST['id']>0){
		array_push($moreMethod,$line);
	}
	if(in_array($line,$moreMethod)){
		$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`=".$_REQUEST['id']);
		$rowMore = mysqli_fetch_assoc($moreRes);
		$couMore = mysqli_num_rows($moreRes);
			$detailsArr = json_decode($rowMore['details'],true);
			$detailsData =$detailsArr[$gid];
			if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
				$ioradio_r_h = $detailsData["ior_".$rtype];
				if(!$ioradio_r_h){
					echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
					exit();	
				}
			}
        //更多玩法注入效验
        if(!gameBkVerify($line,$wtype,$rtype)){
            echo attention("非法操作~,请重新下注!",$uid,$langx);
            exit();
        }
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
	$m_start=strtotime($row['M_Start']);
	$datetime=time();
//    if ($datetime-$m_start<120){
//        echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
//	    exit();
//	}

	if($row['MB_Team']||$row['M_Duration']){
					$team_active=$team_time=$M_Duration='' ;
					$M_Duration = explode('-',$row['M_Duration']);
                    $mbTeamArr = explode('-',$row['MB_Team']);
                    preg_match('/\d+/',$mbTeamArr[1],$mbTeamArrList);
                	if($mbTeamArrList[0]==2){
                    	$team_active ='第二节';
                    	$newDataArray[$MID]['headShow']=0;
                    }elseif($mbTeamArrList[0]==3){
                    	$team_active ='第三节';
                    	$newDataArray[$MID]['headShow']=0;
                    }elseif($mbTeamArrList[0]==4){
                    	$team_active ='第四节';
                    	$newDataArray[$MID]['headShow']=0;
                    }else{
	                    switch ($M_Duration[0]) {
	                        case 'Q1':
	                            $team_active ='第一节';
	                            break;
	                        case 'Q2':
	                            $team_active ='第二节';
	                            break;
	                        case 'Q3':
	                            $team_active ='第三节';
	                            break;
	                        case 'Q4':
	                            $team_active ='第四节';
	                            break;
	                        case 'H1':
	                            $team_active ='上半场';
	                            break;
	                        case 'H2':
	                            $team_active ='下半场';
	                            break;
	                        case 'OT':
	                            $team_active ='加时';
	                            break;
	                        case 'HT':
	                            $team_active ='半场';
	                            break;
	                    }
                    }
                    
                    $team_time ='';
                    if($M_Duration[1] && $M_Duration[1] > 0){ // 转化时间
                        $team_hour = floor($M_Duration[1]/3600); // 小时不要
                        $team_minute = floor(($M_Duration[1]-3600 * $team_hour)/60);
                        $team_second = floor((($M_Duration[1]-3600 * $team_hour) - 60 * $team_minute) % 60);
                        $team_time = ($team_minute>9?$team_minute:"0".$team_minute).':'.($team_second>9?$team_second:"0".$team_second );
                    }
                    $betid = $team_active.$team_time;
	}else{
		$betid='';
	}
	
	
	//联盟
	if ($row[$m_sleague]==''){
		$w_sleague=$row['M_League'];
		$w_sleague_tw=$row['M_League_tw'];
		$w_sleague_en=$row['M_League_en'];
		$s_sleague=$row[$m_league];
	}

	switch ($line){
        case 21:
            $bet_type='滚球独赢';
            $bet_type_tw='滾球獨贏';
            $bet_type_en="Running 1x2";
            $caption=$BK_NFL.$Order_Running_1_x_2_betting_order;
            switch ($type){
                case "H":
                    $w_m_place=$w_mb_team;
                    $w_m_place_tw=$w_mb_team_tw;
                    $w_m_place_en=$w_mb_team_en;
                    $s_m_place=$s_mb_team;
                    if(!$ioradio_r_h){$ioradio_r_h=$row["MB_Win_Rate"];}
                    $w_m_rate=change_rate($open,$ioradio_r_h);
                    $turn_url="/app/member/BK_order/BK_order_rm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                    $w_gtype='RMH';
                    break;
                case "C":
                    $w_m_place=$w_tg_team;
                    $w_m_place_tw=$w_tg_team_tw;
                    $w_m_place_en=$w_tg_team_en;
                    $s_m_place=$s_tg_team;
            		if(!$ioradio_r_h){$ioradio_r_h=$row["TG_Win_Rate"];}
                    $w_m_rate=change_rate($open,$ioradio_r_h);
                    $turn_url="/app/member/BK_order/BK_order_rm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                    $w_gtype='RMC';
                    break;
                case "N":
                    $w_m_place="和局";
                    $w_m_place_tw="和局";
                    $w_m_place_en="Flat";
                    $s_m_place=$Draw;
            		if(!$ioradio_r_h){$ioradio_r_h=$row["M_Flat_Rate"];}
                    $w_m_rate=change_rate($open,$ioradio_r_h);
                    $turn_url="/app/member/BK_order/BK_order_rm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                    $w_gtype='RMN';
                    break;
            }

            $Sign="VS.";
            $grape=$type;
            $gwin=($w_m_rate-1)*$gold;
            $ptype='RM';
            break;
        case 23: 
	        $bet_type='滚球 全场-球队得分：大小';
	        $bet_type_tw="滚球 全场-球队得分：大小";
	        //$bet_type_en="running 1st Half Over/Under";
	        $caption=$Running_Ball.$BK_NFL.' '.$Order_Ball_Score.':'.$OU;
	        //$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
	        switch ($type){
	            case "O": // 主队大，客队大
	                //$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB_H"],$row["TG_Dime_Rate_RB_H"],100);
	            	if($wtype =='ROUH'){ // 主队
	                    $w_m_place=$row["MB_Dime_RB_H"];
	                    $w_m_place_tw=$row["MB_Dime_RB_H"];
	                    $w_m_place_en=$row["MB_Dime_RB_H"];
	                    $m_place=$row["MB_Dime_RB_H"];
	                    $s_m_place=$row["MB_Dime_RB_H"];
	                	if(!$ioradio_r_h){$ioradio_r_h=$row["MB_Dime_Rate_RB_H"];}
	                    $w_m_rate=change_rate($open,$ioradio_r_h); // 主队半场大的赔率
	                    $w_m_bet_name = $row['MB_Team']; // 主队
	                    $w_gtype='ROUH';
	                }else{ // 客队
	                    $w_m_place=$row["TG_Dime_RB_H"];
	                    $w_m_place_tw=$row["TG_Dime_RB_H"];
	                    $w_m_place_en=$row["TG_Dime_RB_H"];
	                    $m_place=$row["TG_Dime_RB_H"];
	                    $s_m_place=$row["TG_Dime_RB_H"];
	                	if(!$ioradio_r_h){$ioradio_r_h=$row["TG_Dime_Rate_RB_H"];}
	                    $w_m_rate=change_rate($open,$ioradio_r_h); // 客队半场大的赔率
	                    $w_m_bet_name = $row['TG_Team']; // 客队
	                     $w_gtype='ROUC';
	                }
	                $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
	                $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
	                $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
	                if ($langx=="zh-cn"){
	                    $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
	                }else if ($langx=="en-us" or $langx=="th-tis"){
	                    $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
	                }
	                // $w_m_rate=change_rate($open,$rate[0]); // 赔率
	                $turn_url="/app/member/BK_order/BK_order_rouhc.php?gid=$gid&uid=$suid&wtype=$wtype&type=$type&odd_f_type=$odd_f_type&langx=$langx";
	                 break;
	            case "U": // 主队小，客队小
	            	//$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB_S_H"],$row["TG_Dime_Rate_RB_S_H"],100);
	                if($wtype =='ROUH'){ // 主队
	                    $w_m_place=$row["MB_Dime_RB_S_H"];
	                    $m_place=$row["MB_Dime_RB_S_H"];
	                    $w_m_place_tw=$row["MB_Dime_RB_S_H"];
	                    $s_m_place=$row["MB_Dime_RB_S_H"];
	                    $w_m_place_en=$row["MB_Dime_RB_S_H"];
	                	if(!$ioradio_r_h){$ioradio_r_h=$row["MB_Dime_Rate_RB_S_H"];}
	                    $w_m_rate=change_rate($open,$ioradio_r_h); // 主队半场小的赔率
	                    $w_m_bet_name = $row['MB_Team']; // 主队
	                    $w_gtype='ROUH';
	                }else{ // 客队
	                    $w_m_place=$row["TG_Dime_RB_S_H"];
	                    $m_place=$row["TG_Dime_RB_S_H"];
	                    $w_m_place_tw=$row["TG_Dime_RB_S_H"];
	                    $s_m_place=$row["TG_Dime_RB_S_H"];
	                    $w_m_place_en=$row["TG_Dime_RB_S_H"];
	                	if(!$ioradio_r_h){$ioradio_r_h=$row["TG_Dime_Rate_RB_S_H"];}
	                    $w_m_rate=change_rate($open,$ioradio_r_h); //客队半场小的赔率
	                    $w_m_bet_name = $row['TG_Team']; // 主队
	                    $w_gtype='ROUC';
	                }
	
	                $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
	                $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
	                $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);
	
	                if ($langx=="zh-cn"){
	                    $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
	                }else if ($langx=="en-us" or $langx=="th-tis"){
	                    $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
	                }
	               // $w_m_rate=change_rate($open,$rate[1]); // 赔率
	                $turn_url="/app/member/BK_order/BK_order_rouhc.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
	                break;
	        }
	        
			if($wtype =='ROUH'){
				$bet_type='滚球 全场- 球队得分大小：主队';
	        	$bet_type_tw="滚球 全场- 球队得分大小：主队";
	        	$caption=$Running_Ball.$BK_NFL.' 主队  '.$Order_Ball_Score.':'.$OU;
	        	$w_m_place=$s_mb_team.' '.$w_m_place;
	        	$w_m_place_tw=$s_mb_team.' '.$w_m_place_tw;
	        	$w_m_place_en=$s_mb_team.' '.$w_m_place_en;	
	        }else{
	        	$bet_type='滚球 全场- 球队得分大小：客队';
	        	$bet_type_tw="滚球 全场- 球队得分大小：客队";
	        	$caption=$Running_Ball.$BK_NFL.' 客队'.$Order_Ball_Score.':'.$OU;
	        	$w_m_place=$s_tg_team.' '.$w_m_place;
	        	$w_m_place_tw=$s_tg_team.' '.$w_m_place_tw;
	        	$w_m_place_en=$s_tg_team.' '.$w_m_place_en;
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

	if ($gold<10){
        echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}

	if ($w_m_rate=='' or $w_m_rate==0 or $grape==''){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}

	if ($w_m_rate!=change_rate($open,$ioradio_r_h)){
		$turn_url=$turn_url.'&error_flag=1';
		echo "<script language='javascript'>self.location='$turn_url';</script>";
		exit;
	}
	if ($s_m_place=='' or $w_m_place=='' or $w_m_place_tw=='' or $w_m_place_en==''){
        echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}

	if(!isset($_REQUEST['autoOdd']) || $_REQUEST['autoOdd']!="Y"){
		if($w_m_rate!=$_REQUEST['ioradio_r_h']){
			$turn_url=$turn_url.'&error_flag=1';
			echo "<script language='javascript'>self.location='$turn_url';</script>";
			exit;
		}	
	}
	
	$oddstype=$odd_f_type;
	$team=strip_tags($row["MB_Team"]);
	$team_en=strip_tags($row["MB_Team_en"]);
	$place=explode("-",$team);
	$place_en=explode("-",$team_en);
	if ($place[1]==""){
		$s_w_place="";
		$s_w_place_en="";
	}else{
	    $s_w_place="<font color=gray> - ".$place[1]."</font>";
		$s_w_place_en="<font color=gray> - ".$place_en[1]."</font>";
	}
	$w_mb_mid=$row['MB_MID'];
	$w_tg_mid=$row['TG_MID'];

	$lines=$row['M_League']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
	$lines=$lines."<FONT color=#cc0000>".$w_m_place.$s_w_place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";	
	
	$lines_tw=$row['M_League_tw']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team_tw."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_tw."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
	$lines_tw=$lines_tw."<FONT color=#cc0000>".$w_m_place_tw.$s_w_place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";
	
	$lines_en=$row['M_League_en']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team_en."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_en."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
	$lines_en=$lines_en."<FONT color=#cc0000>".$w_m_place_en.$s_w_place_en."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";	
	
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
		if($havemoney < 0 || $gold<=0 || $HMoney<=0){
			mysqli_query($dbMasterLink,"ROLLBACK");
			echo attention("$User_insufficient_balance",$uid,$langx);
			exit();
		}
		$sql = "INSERT INTO ".DBPREFIX."web_report_data	(danger,Glost,playSource,MID,Userid,testflag,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball,betid) values (0,$Money,$play_Source,'$gid',$memid,$test_flag,'$active','$showVoucher','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','BK','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball','$betid')";
		$insertBet=mysqli_query($dbMasterLink,$sql);
        if($insertBet){
            $lastId=mysqli_insert_id($dbMasterLink);
			$moneyLogRes=addAccountRecords(array($memid,$memname,$test_flag,$Money,$gold*-1,$havemoney,1,1,$lastId,"BK投注$w_gtype"));
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
// echo attention("$Order_OK");exit;
?>
<html>
<head>
<meta http-equiv='Content-Type' content="text/html; charset=utf-8">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<link rel="stylesheet" href="/style/member/mem_order<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<body id="OFIN" class="order_finish_<?php echo TPL_FILE_NAME;?>" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
  <div class="ord">
    <span><h1><?php echo $caption?></h1></span>
      <div id="info">
       <!--<p><?php/*=$Order_Login_Name*/?><?php/*=$memname*/?></p>-->
       <!--<p class="mem-can-use"><?php/*=$Order_Credit_line*/?><?php/*=$havemoney*/?></p>-->
       <div class="fin_title">
           <p class="fin_acc">成功提交注单！</p>
           <p class="p-underline"><?php echo $Order_Bet_success?><?php echo $showVoucher;?></p>
           <p class="error">危险球 - 待确认</p>
       </div>
       <!--<p><center><strong><font color='#FFFFFF' style='background-color: #FF0000'>&nbsp;<?php echo $Order_Pending?>&nbsp;</font></strong></center></p>-->
       <p class="team"><?php echo $s_sleague?>&nbsp;<?php echo $btype?>&nbsp;<?php echo date('m-d',strtotime($row["M_Date"]))?><BR><?php echo $s_mb_team?>&nbsp;<font color=#cc0000><?php echo $Sign?></font>&nbsp;<?php echo $s_tg_team?><br><em><?php echo $s_m_place?><?php echo $s_w_place?></em>&nbsp;@&nbsp;<em><strong><?php echo $w_m_rate?></strong></em></p>
       <p class="deal-money"><?php echo $Order_Bet_Amount?><?php echo $gold?></p>
       <!--<p class="canwin-money"><?php/*=$Order_Estimated_Payout*/?><FONT id=pc color=#cc0000><?php/*=$gwin*/?></FONT></p>-->
      </div>

      <p class="foot">
          <input type="button" name="FINISH" value="<?php echo $Order_Quit?>" onClick="parent.close_bet();" class="no">
          <input type="button" name="PRINT" value="<?php echo $Order_Print?>" onClick="window.print()" class="yes">
      </p>
  </div>

<!--  <div id="countMask">

  </div>
<script type="text/javascript">
    // 确认弹窗
    function showCountMask(){
        var countS = 3;	//倒数时间（秒）
        var countMask=document.getElementById("countMask");
        var betstr = '<?php /*echo $Order_Bet_success*/?> <?php /*echo $showVoucher;*/?>' ;
        countMask.style.display="block";
        curTime = setInterval(function(){showTime();},1000);
        var showTime=function(){
            countMask.innerHTML="正在确认..."+countS+"<br>请勿关闭窗口!";
            if(countS > 0){
                countS--;
            } else{
                clearInterval(curTime);
                countMask.style.display="none";
                countMask.innerHTML="";
                document.getElementsByClassName('p-underline').innerHTML = betstr ;
               // window.setTimeout('sendsubmit()',500);
               // document.getElementById("frm_checkrep").submit();
            }
        }
    }
    showCountMask();
</script>-->
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
        'Order_Bet_Amount' => $Order_Bet_Amount,  // 交易金额
        'gold' => $gold, //20
        'Order_Quit' => $Order_Quit, //关闭
        'Order_Print' => $Order_Print, //列印
        'userid' => $memid,
        'playSource' => $play_Source,  //'投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓',
    );

    $redisObj = new Ciredis();
    $redisObj->setOne($showVoucher,serialize($data));
    $redisObj->pushMessage('general_order_image',$showVoucher);

}
?>
</html>
