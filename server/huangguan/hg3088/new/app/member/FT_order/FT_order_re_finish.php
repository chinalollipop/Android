﻿<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
require ("../include/define_function_list.inc.php");
require ("../include/curl_http.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$gid=$_REQUEST['gid'];
$type=$_REQUEST['type'];
$rtype=$_REQUEST['rtype'];
$wtype=$_REQUEST['wtype'];
$gnum=$_REQUEST['gnum'];
$strong=$_REQUEST['strong'];
$odd_f_type=$_REQUEST['odd_f_type'];
$gold=$_REQUEST['gold'];
$active=1; // 强制足球滚球     //1 足球滚球、今日赛事, 11 足球早餐\r\n2 篮球滚球、今日赛事, 22 篮球早餐
$line=$_REQUEST['line_type'];
$restcredit=$_REQUEST['restcredit'];
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
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
$open=$_SESSION['OpenType'];
$pay_type =$_SESSION['Pay_Type'];
$memname=$_SESSION['UserName'];
$agents=$_SESSION['Agents'];
$world=$_SESSION['World'];
$corprator=$_SESSION['Corprator'];
$super=$_SESSION['Super'];
$admin=$_SESSION['Admin'];
$w_ratio=$_SESSION['ratio'];
$HMoney=$memrow['Money'];
$memid= $_SESSION['userid'];

if($HMoney < $gold || $gold<10 || $HMoney<=0){
	echo attention("$User_insufficient_balance",$uid,$langx);
	exit();
}

$w_current=$_SESSION['CurType'];
$memid= $_SESSION['userid'];
$test_flag=$_SESSION['test_flag'];

//------------------------------------------------------滚球效验 start
$allcount=0;
$accoutArr =$uniqueIpArray= array();
$accoutArr=getFlushWaterAccount();//数组随机排序
$accoutArrNum = count($accoutArr);
$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
	$allcount = $allcount + 1;
	$site=$value['Datasite'];
	$suid=$value['Uid'];
	
	$curl->set_referrer("".$site."/app/member/order.php?rtype=re&uid=$suid&langx=zh-cn&mtype=3");
	switch ($line){
		case '10':
            $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$gid&uid=$suid&odd_f_type=$odd_f_type&type=$type&gnum=$gnum&langx=zh-cn&ptype=&imp=N&rtype=ROU{$type}&wtype=ROU");
			break;
		case '9':
            $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$gid&uid=$suid&odd_f_type=$odd_f_type&type=$type&gnum=$gnum&strong=$strong&langx=zh-cn&ptype=&imp=N&rtype=RE{$type}&wtype=RE");
			break;
		case '21':
            $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$gid&uid=$suid&odd_f_type=$odd_f_type&type=$type&gnum=$gnum&langx=zh-cn&ptype=&imp=N&rtype=RM{$type}&wtype=RM");
			break;
		case '104':
			$html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$gid&uid=$suid&rtype=$rtype&gnum=$gnum&odd_f_type=$odd_f_type");
			break;
		case '107':
		    $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$gid&uid=$suid&odd_f_type=$odd_f_type&langx=".$langx."&rtype=".$rtype."&wtype=RF&imp=N&ptype=");
			break;
		case '154':	
			$html_data=$curl->fetch_url($site."/app/member/FT_order/FT_order_all.php?gid=".$gid."&uid=".$suid."&odd_f_type=".$odd_f_type."&langx=".$langx."&rtype=".$rtype."&gnum=".$gnum."&type=".$type."&wtype=".$wtype);
			break;
		case '105':
            $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$gid&uid=$suid&odd_f_type=$odd_f_type&langx=zh-cn&rtype=".$rtype."&wtype=HREO&imp=N&ptype=");
            break;
        case '106':
			$html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$gid&uid=$suid&odd_f_type=$odd_f_type&langx=".$langx."&rtype=".$rtype);
			break;
		case '205':
		case '206':	
			$sgid=$gid+1;
			$html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$sgid&uid=$suid&odd_f_type=$odd_f_type&langx=".$langx."&rtype=".$rtype);
			break;
        case '244':
            $sgid=$gid+1;
            $html_data=$curl->fetch_url($site."/app/member/FT_order/FT_order_all.php?gid=".$gid."&uid=".$suid."&odd_f_type=".$odd_f_type."&langx=".$langx."&rtype={$rtype}&gnum=".$gnum."&type=".$type."&wtype=ROU{$odd_f_type}&imp=N&ptype=");
            break;
		default :
			$html_data=$curl->fetch_url($site."/app/member/FT_order/FT_order_all.php?gid=$gid&uid=$suid&odd_f_type=$odd_f_type&langx=".$langx."&rtype=".$rtype."&wtype=".$wtype);
		}
	$msg_c=explode("@",$html_data);
	if(sizeof($msg_c)>1){
		break;
	}elseif($allcount==$accoutArrNum){
		echo attention("$Order_Odd_changed_please_game_again",$uid,$langx);
		exit();
	}
}

//------------------------------------------------------滚球效验 end

// -------------------------------------------------------更多玩法刷水 Start
$redisObj = new Ciredis();
if($_REQUEST['id']&&$_REQUEST['id']>0) {
    $gtype = 'FT';
    $showtype = 'RB';
    $midLockSet = '';
    $midLockCheck = mysqli_query($dbLink, "select MID from " . DBPREFIX . "match_sports_more_midlock where `MID` = $gid");
    $cou = mysqli_num_rows($midLockCheck);
    if ($cou == 0) $midLockSet = mysqli_query($dbMasterLink, "INSERT INTO " . DBPREFIX . "match_sports_more_midlock(`MID`)VALUES({$gid})");
    if ($midLockSet || $cou == 1) {
        $valReflushTime = $redisObj->getSimpleOne($gid . "_reflush_time");
        if ($valReflushTime) { //存在赛事,更新数据库，redis
            if ($showtype == "RB") {
                $reflushTime = 5;
            } elseif ($showtype == "FU") {
                $reflushTime = 10;
            } else {
                $reflushTime = 20;
            }
            if (time() - $valReflushTime > $reflushTime) { //数据过期,重新抓取更新数据库,redis
                //echo "out date re get<br/>";
                $begin = mysqli_query($dbMasterLink, "start transaction");//开启事务$from
                $lockMid = mysqli_query($dbMasterLink, "select MID from " . DBPREFIX . "match_sports_more_midlock where `MID` = $gid for update");
                $valReflushTime1 = $redisObj->getSimpleOne($gid . "_reflush_time");
                if (time() - $valReflushTime1 > $reflushTime) {
                    if ($begin && $lockMid->num_rows == 1) {
                        $dataNew = getDataFromInterface($langx, $gtype, $showtype, $gid);
                        if ($dataNew['tmp_Obj'] && count($dataNew['tmp_Obj']) > 0 && $dataNew['gid_ary'] && count($dataNew['gid_ary']) > 0) {
                            $tmp_Obj = $dataNew['tmp_Obj'];
                            $gid_ary = $dataNew['gid_ary'];
                            $updateSt = $redisObj->getSET($gid . "_reflush_time", time());
                            if ($updateSt) {
                                $details = json_encode($tmp_Obj);
                                $details = str_replace('\'', '', $details);
                                $setGames = mysqli_query($dbMasterLink, "replace into " . DBPREFIX . "match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
                                if ($setGames) {
                                    $comStatus = mysqli_query($dbMasterLink, "COMMIT");
                                    if ($comStatus) {
                                        $redisObj->getSET("gameMore_" . $gid, json_encode(array('status' => 1, 'tmp_Obj' => $tmp_Obj, 'gid_ary' => $gid_ary)));//写入redis
                                    }
                                    else{
                                        $redisObj->delete($gid . "_reflush_time");
                                        mysqli_query($dbMasterLink, "ROLLBACK");
                                        echo attention("更多玩法数据为空1", $uid, $langx);
                                        exit();
                                    }
                                }
                                else{
                                    $redisObj->delete($gid . "_reflush_time");
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                    echo attention("更多玩法数据为空11", $uid, $langx);
                                    exit();
                                }
                            }
                            else{
                                $redisObj->delete($gid . "_reflush_time");
                                mysqli_query($dbMasterLink, "ROLLBACK");
                                echo attention("更多玩法数据为空111", $uid, $langx);
                                exit();
                            }
                        }
                        else{
                            $redisObj->delete($gid . "_reflush_time");
                            mysqli_query($dbMasterLink, "ROLLBACK");
                            echo attention("更多玩法数据为空1111", $uid, $langx);
                            exit();
                        }
                    }
                }
            }
            //echo "in date <br/>";
            $games = $redisObj->getSimpleOne("gameMore_" . $gid);//在redis取出数据
//            echo $games;
//            exit();
        } else {//不存在赛事：接口抓取数据，存入数据库，redis
            //echo 'new data<br/>';
            $begin = mysqli_query($dbMasterLink, "start transaction");//开启事务$from
            $lockMid = mysqli_query($dbMasterLink, "select MID from " . DBPREFIX . "match_sports_more_midlock where `MID` = $gid for update");
            $valReflushTime2 = $redisObj->getSimpleOne($gid . "_reflush_time");
            if (!$valReflushTime2) {
                if ($begin && $lockMid->num_rows == 1) {
                    $dataNew = getDataFromInterface($langx, $gtype, $showtype, $gid);
                    if ($dataNew['tmp_Obj'] && count($dataNew['tmp_Obj']) > 0 && $dataNew['gid_ary'] && count($dataNew['gid_ary']) > 0) {
                        $tmp_Obj = $dataNew['tmp_Obj'];
                        $gid_ary = $dataNew['gid_ary'];
                        $rtStatus = $redisObj->setOne($gid . "_reflush_time", time());//写入刷新时间
                        if ($rtStatus) {
                            $details = json_encode($tmp_Obj);
                            $details = str_replace('\'', '', $details);
                            $exitResult = mysqli_query($dbLink, "select MID from " . DBPREFIX . "match_sports_more where MID=" . $gid);
                            $exitsNum = mysqli_fetch_assoc($exitResult);
                            if (isset($exitsNum['MID']) == $exitsNum['MID']) {//更新
                                $setGames = mysqli_query($dbMasterLink, "replace into " . DBPREFIX . "match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
                            } else {//存入
                                $setGames = mysqli_query($dbMasterLink, "INSERT INTO " . DBPREFIX . "match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
                            }
                            if ($setGames) {
                                $comStatus = mysqli_query($dbMasterLink, "COMMIT");
                                if ($comStatus) {
                                    $redisObj->setOne("gameMore_" . $gid, json_encode(array('status' => 1, 'tmp_Obj' => $tmp_Obj, 'gid_ary' => $gid_ary)));//写入redis
                                }
                                else{
                                    $redisObj->delete($gid . "_reflush_time");
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                    echo attention("更多玩法数据为空2", $uid, $langx);
                                    exit();
                                }
                            }
                            else{
                                $redisObj->delete($gid . "_reflush_time");
                                mysqli_query($dbMasterLink, "ROLLBACK");
                                echo attention("更多玩法数据为空22", $uid, $langx);
                                exit();
                            }
                        }
                        else{
                            mysqli_query($dbMasterLink, "ROLLBACK");
                            echo attention("更多玩法数据为空222", $uid, $langx);
                            exit();
                        }
                    }
                }
            } else {
                mysqli_query($dbMasterLink, "ROLLBACK");
                $games = $redisObj->getSimpleOne("gameMore_" . $gid);//在redis取出数据
//                echo $games;
//                exit();
            }
        }
    } else {
        echo attention("更多玩法数据为空3", $uid, $langx);
        exit();
    }
}
// -------------------------------------------------------更多玩法刷水 End

		$mysql = "select * from `".DBPREFIX."match_sports` where `MID`='$gid' and Open=1 and MB_Team!=''";
		$result = mysqli_query($dbMasterLink,$mysql);
		$row = mysqli_fetch_assoc($result);
		$cou = mysqli_num_rows($result);
		if($cou==0){
			echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
			exit();
		}
	    if($_REQUEST['id']&&$_REQUEST['id']>0){
			$detailsData=array();
			$moreMethod = array(104,106,107,118,119,120,144,10,115,206,122,134,135,136,124,123,130,129,128,134,137,135,141,161,142,154,244,205,206);
			if(isset($_REQUEST['dataSou']) && $_REQUEST['dataSou']=="interface"){
				array_push($moreMethod,$line);
			}
			if(in_array($line,$moreMethod)){
				$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
				$rowMore = mysqli_fetch_assoc($moreRes);
				$couMore = mysqli_num_rows($moreRes);	
				$detailsArr = json_decode($rowMore['details'],true);
				$detailsData =$detailsArr[$gid];
				switch ($line){
				  	 case 104:	$keyY = sw_RPD;$iorK="ior_".$rtype; break;
				  	 case 105:	if($rtype=='RODD'){ $keyY="sw_REO"; $iorK = "ior_REOO";break;}  	
								if($rtype=='REVEN'){ $keyY="sw_REO"; $iorK = "ior_REOE";break;} 	
				  	 case 106:	if($rtype=='R0~1'){ $keyY="sw_RT"; $iorK = "ior_RT01";break;} 	
								if($rtype=='R2~3'){ $keyY="sw_RT"; $iorK = "ior_RT23";break;} 	
								if($rtype=='R4~6'){ $keyY="sw_RT"; $iorK = "ior_RT46";break;}  		
								if($rtype=='ROVER'){ $keyY="sw_RT"; $iorK = "ior_ROVER";break;}
					case 205:   if($rtype=='HRODD'){$keyY="sw_HREO"; $iorK = "ior_HREOO";break;}
			  					if($rtype=='HREVEN'){$keyY="sw_HREO"; $iorK = "ior_HREOE";break;}
					case 206:	if($rtype=='HRT0'){ $keyY="sw_HRT"; $iorK = "ior_HRT0";break;} 	
								if($rtype=='HRT1'){ $keyY="sw_HRT"; $iorK = "ior_HRT1";break;} 	
								if($rtype=='HRT2'){ $keyY="sw_HRT"; $iorK = "ior_HRT2";break;}  		
								if($rtype=='HRTOV'){ $keyY="sw_HRT"; $iorK = "ior_HRTOV";break;}
				  	 default:	$keyY = 'sw_'.$wtype; $iorK="ior_".$rtype; break;
				}
				if($line==244){ $detailsData[$keyY]="Y"; }
				if($detailsData[$keyY]=="Y" && $detailsData[$iorK]>0){
					$ioradio_r_h = $detailsData[$iorK];
					if(!$ioradio_r_h){
						echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
						exit();	
					}
				}

                //更多玩法注入效验
                if(gameFtVerify($line,$wtype,$rtype)){
                    if($line==9){
                        $Sign = $detailsData['ratio_re'];
                        $type = substr($rtype,-1,1);
                    }
                    if($line==10){
                        if($rtype=='ROUC'){
                            $m_place = $detailsData['ratio_rouo'];
                        }
                        if($rtype=='ROUH'){
                            $m_place = $detailsData['ratio_rouu'];
                        }
                        $type = substr($rtype,-1,1);
                        $s_m_place = $w_m_place = $w_m_place_tw = $w_m_place_en =  $m_place;
                    }
                    if($line==21){ $type = substr($rtype,-1,1); }
                }else{
                    echo attention("非法操作,请重新下注!",$uid,$langx);
                    exit();
                }

			}
		}else{
			if(in_array($rtype,array('RFHN','RFHC','RFNH','RFNN','RFNC','RFCH','RFCN','RFCC','RFHH'))){//半场全场
					$files =str_replace("F","",$rtype);
				    $files =str_replace("H","MB",$files); 
				    $files =str_replace("C","TG",$files);
					$files =str_replace("N","FT",$files);
					$rbExpandRes = mysqli_query($dbMasterLink,"select $files from ".DBPREFIX."match_sports_rb_expand where `MID`='$gid'");
					$rowExpandRes = mysqli_fetch_assoc($rbExpandRes);
					$couExpandRes = mysqli_num_rows($rbExpandRes);
					$ioradio_r_h = $rowExpandRes[$files];
					if(!$ioradio_r_h){
						echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
						exit;
					}
			}
			if(in_array($rtype,array('RH1C0','RH2C0','RH0C1','RH0C2','RH2C1','RH1C2','RH3C0','RH0C3','RH3C1','RH1C3','RH3C2','RH2C3','RH4C0',
										'RH0C4','RH4C1','RH1C4','RH4C2','RH2C4','RH4C3','RH3C4','RH0C0','RH2C2','RH1C1','RH3C3','RH4C4','ROVH'))){//波胆
				if($rtype=="ROVH"){
					$files = "RUP5";
				}else{
					$files = str_replace('H','MB',$rtype);
					$files = str_replace('C','TG',$files);
				}
				$rbExpandRes = mysqli_query($dbMasterLink,"select $files AS '$rtype' from ".DBPREFIX."match_sports_rb_expand where `MID`='$gid'");
				$rowExpandRes = mysqli_fetch_assoc($rbExpandRes);
				$couExpandRes = mysqli_num_rows($rbExpandRes);
				$ioradio_r_h = $rowExpandRes[$rtype];
				if($couExpandRes==0 || !$ioradio_r_h){
					echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
					exit;
				}
			}
			if(in_array($rtype,array('R0~1','R2~3','R4~6','ROVER'))){//总入球
					$rbExpandRes = mysqli_query($dbLink,"select RS_0_1 AS 'R0~1',RS_2_3 AS 'R2~3',RS_4_6 AS 'R4~6',RS_7UP AS 'ROVER' from ".DBPREFIX."match_sports_rb_expand where `MID`='$gid'");
					$rowExpandRes = mysqli_fetch_assoc($rbExpandRes);
					$couExpandRes = mysqli_num_rows($rbExpandRes);
					$ioradio_r_h = $rowExpandRes[$rtype];
					if($couExpandRes==0 || !$ioradio_r_h){
						echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
						exit;
					}	
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
//	if ($datetime-$m_start<120){//比赛前2分钟不能投注
//	    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
//	    exit();
//	}
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
	  	case 21:
		  	$bet_type='滚球独赢';
			$bet_type_tw='滾球獨贏';
			$bet_type_en="Running 1x2";
			$caption=$Order_FT.$Order_Running_1_x_2_betting_order;
			switch ($type){
				case "H":
					$w_m_place=$w_mb_team;
					$w_m_place_tw=$w_mb_team_tw;
					$w_m_place_en=$w_mb_team_en;
					$s_m_place=$s_mb_team;
					if(!$ioradio_r_h){$ioradio_r_h=$row["MB_Win_Rate_RB"];}
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/FT_order/FT_order_rm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
					$w_gtype='RMH';
					break;
				case "C":
					$w_m_place=$w_tg_team;
					$w_m_place_tw=$w_tg_team_tw;
					$w_m_place_en=$w_tg_team_en;
					$s_m_place=$s_tg_team;
					if(!$ioradio_r_h){$ioradio_r_h=$row["TG_Win_Rate_RB"];}
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/FT_order/FT_order_rm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
					$w_gtype='RMC';
					break;
				case "N":
					$w_m_place="和局";
					$w_m_place_tw="和局";
					$w_m_place_en="Flat";
					$s_m_place=$Draw;
					if(!$ioradio_r_h){$ioradio_r_h=$row["M_Flat_Rate_RB"];}
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/FT_order/FT_order_rm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
					$w_gtype='RMN';
					break;
			}
			$Sign="VS.";
			$grape=$type;
			$gwin=($w_m_rate-1)*$gold;
			$ptype='RM';
			break;	
		case 9:
	 		$bet_type='滚球让球';
			$bet_type_tw="滾球讓球";
			$bet_type_en="Running Ball";
			$caption=$Order_FT.$Order_Running_Ball_betting_order;
			$rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RB"],$row["TG_LetB_Rate_RB"],100);
			switch ($type){
				case "H":
					$w_m_place=$w_mb_team;
					$w_m_place_tw=$w_mb_team_tw;
					$w_m_place_en=$w_mb_team_en;
					$s_m_place=$s_mb_team;
					if(!$ioradio_r_h){$ioradio_r_h=$rate[0];}
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/FT_order/FT_order_re.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
					$w_gtype='RRH';
					break;
				case "C":
					$w_m_place=$w_tg_team;
					$w_m_place_tw=$w_tg_team_tw;
					$w_m_place_en=$w_tg_team_en;
					$s_m_place=$s_tg_team;
					if(!$ioradio_r_h){$ioradio_r_h=$rate[1];}
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/FT_order/FT_order_re.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
					$w_gtype='RRC';
					break;
			}
            if(!$Sign){$Sign=$row['M_LetB_RB'];}
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
			$caption=$Order_FT.$Order_Running_Ball_Over_Under_betting_order;
			$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB"],$row["TG_Dime_Rate_RB"],100);
			switch ($type){
				case "C":
					if(!$w_m_place){
                        $w_m_place=$row["MB_Dime_RB"];
                        $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
                    }
				    if(!$w_m_place_tw){
                        $w_m_place_tw=$row["MB_Dime_RB"];
                        $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
                    }
                    if(!$w_m_place_en){
                        $w_m_place_en=$row["MB_Dime_RB"];
                        $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
                    }

					if(!$m_place) $m_place=$row["MB_Dime_RB"];
					if(!$s_m_place){ $s_m_place=$row["MB_Dime_RB"]; }
					if ($langx=="zh-cn"){
			            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
				    }else if ($langx=="zh-cn"){
				        $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
				    }else if ($langx=="en-us" or $langx=='th-tis'){
				        $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
					}	
					if(!$ioradio_r_h){$ioradio_r_h=$rate[0];}
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/FT_order/FT_order_rou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
					$w_gtype='ROUH';
					break;
				case "H":
					if(!$w_m_place){
                        $w_m_place=$row["TG_Dime_RB"];
                        $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
                    }
                    if(!$w_m_place_tw){
                        $w_m_place_tw=$row["TG_Dime_RB"];
                        $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
                    }
                    if(!$w_m_place_en){
                        $w_m_place_en=$row["TG_Dime_RB"];
                        $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);
                    }

					if(!$m_place) $m_place=$row["TG_Dime_RB"];

					if(!$s_m_place){ $s_m_place=$row["TG_Dime_RB"]; }

					if ($langx=="zh-cn"){
			            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
				    }else if ($langx=="zh-cn"){
				        $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
				    }else if ($langx=="en-us" or $langx=='th-tis'){
				        $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
					}
					if(!$ioradio_r_h){$ioradio_r_h=$rate[1];}
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/FT_order/FT_order_rou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
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
		case 104:
			$turn_url="/app/member/FT_order/FT_order_rpd.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
		  	$bet_type='滚球  全场 - 波胆';
			$bet_type_tw='滾球  全场 -波膽';
			$bet_type_en="Running Correct Score";
			$caption=$Order_FT.' '.$Running_Ball.' '.$Order_Correct_Score_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			if($rtype=="ROVH"){		
				$s_m_place=$Order_Other_Score;
				$w_m_place='其它比分';
				$w_m_place_tw='其它比分';
				$w_m_place_en='Other Score';
				$Sign="VS.";
			}else{
				$s_m_place=$_REQUEST['concede'];
				$w_m_place=$_REQUEST['concede'];
				$w_m_place_tw=$_REQUEST['concede'];
				$w_m_place_en=$_REQUEST['concede'];
				$M_Sign=$rtype;
				$M_Sign=str_replace("MB","",$M_Sign);
				$M_Sign=str_replace("TG",":",$M_Sign);
				$Sign=$M_Sign."";
			}
			$Sign="VS.";
			$grape=$rtype;
			$gwin=($w_m_rate-1)*$gold;
			$ptype='RPD';
			$w_gtype=$rtype;
			break;
		case 105:
            $turn_url="/app/member/FT_order/FT_order_rt.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
            $bet_type='滚球单双';
            $bet_type_tw="滾球單雙";
            $bet_type_en="Running_Ball Odd/Even";
            $caption=$Order_FT." ".$Running_Ball.$Order_Odd_Even_betting_order;
            switch ($rtype){
                case "RODD":
                    $w_m_place='单';
                    $w_m_place_tw='單';
                    $w_m_place_en='odd';
                    $s_m_place='('.$Order_Odd.')';
                    if($ioradio_r_h>0){
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                    }else{
                        $ioradio_r_h=$row['S_Single_Rate_RB'];
                        $w_m_rate=change_rate($open,$row['S_Single_Rate_RB']);
                    }
                    break;
                case "REVEN":
                    $w_m_place='双';
                    $w_m_place_tw='雙';
                    $w_m_place_en='even';
                    $s_m_place='('.$Order_Even.')';
                    if($ioradio_r_h>0){
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                    }else{
                        $ioradio_r_h=$row['S_Double_Rate_RB'];
                        $w_m_rate=change_rate($open,$row['S_Double_Rate_RB']);
                    }
                    break;
                case "HRODD":
                    $btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                    $w_m_place='单';
                    $w_m_place_tw='單';
                    $w_m_place_en='odd';
                    $s_m_place='('.$Order_Odd.')';
                    $w_m_rate=change_rate($open,$ioradio_r_h);
                    break;
                case "HREVEN":
                    $btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                    $w_m_place='双';
                    $w_m_place_tw='雙';
                    $w_m_place_en='even';
                    $s_m_place='('.$Order_Even.')';
                    $w_m_rate=change_rate($open,$ioradio_r_h);
                    break;
            }

            $Sign="VS.";
            $gwin=($w_m_rate-1)*$gold;
            $ptype='EO';
            $w_gtype=$rtype;
            break;
        case 205:
			$turn_url="/app/member/FT_order/FT_order_rt.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
			$bet_type='半场滚球单双';
			$bet_type_tw="半場滾球單雙";
			$bet_type_en="Running_Ball Odd/Even";
			$caption=$Order_FT." ".$Running_Ball.$Order_Odd_Even_betting_order;
			switch ($rtype){
				case "RODD":
					$w_m_place='单';
					$w_m_place_tw='單';
					$w_m_place_en='odd';
					$s_m_place='('.$Order_Odd.')';
					if($ioradio_r_h>0){
						$w_m_rate=change_rate($open,$ioradio_r_h);	
					}else{
						$ioradio_r_h=$row['S_Single_Rate_RB'];
						$w_m_rate=change_rate($open,$row['S_Single_Rate_RB']);
					}
					break;
				case "REVEN":
					$w_m_place='双';
					$w_m_place_tw='雙';
					$w_m_place_en='even';
					$s_m_place='('.$Order_Even.')';
					if($ioradio_r_h>0){
						$w_m_rate=change_rate($open,$ioradio_r_h);	
					}else{
						$ioradio_r_h=$row['S_Double_Rate_RB'];
						$w_m_rate=change_rate($open,$row['S_Double_Rate_RB']);
					}
					break;
				case "HRODD":
					$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
					$w_m_place='单';
					$w_m_place_tw='單';
					$w_m_place_en='odd';
					$s_m_place='('.$Order_Odd.')';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "HREVEN":
					$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
					$w_m_place='双';
					$w_m_place_tw='雙';
					$w_m_place_en='even';
					$s_m_place='('.$Order_Even.')';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
			}
			
			$Sign="VS.";
			$gwin=($w_m_rate-1)*$gold;
			$ptype='EO';	
			$w_gtype=$rtype;
			break;		
		case 106:
			$turn_url="/app/member/FT_order/FT_order_rt.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
			$bet_type='滚球  全场 - 总进球数';
			$bet_type_tw="滚球  全场 - 總进球数";
			$bet_type_en="Running_Ball Total Count";
			$caption=$Order_FT." ".$Running_Ball.$Order_Total_Goals_betting_order;
			switch ($rtype){
				case "R0~1":
					$w_m_place='0~1';
					$w_m_place_tw='0~1';
					$w_m_place_en='0~1';
					$s_m_place='(0~1)';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "R2~3":
					$w_m_place='2~3';
					$w_m_place_tw='2~3';
					$w_m_place_en='2~3';
					$s_m_place='(2~3)';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "R4~6":
					$w_m_place='4~6';
					$w_m_place_tw='4~6';
					$w_m_place_en='4~6';
					$s_m_place='(4~6)';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "ROVER":
					$w_m_place='7up';
					$w_m_place_tw='7up';
					$w_m_place_en='7up';
					$s_m_place='(7up)';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
			}
			$Sign="VS.";
			if($w_m_rate>1){
		    	$gwin=($w_m_rate-1)*$gold;
		    }else{
		    	$gwin=($w_m_rate)*$gold;	
		    }
			$ptype='T';
			$grape=$rtype;
			$w_gtype=$rtype;				
			break;
		case 107:
			$turn_url="/app/member/FT_order/FT_order_rf.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
			$bet_type='半全场';
			$bet_type_tw="半全場";
			$bet_type_en="Half/Full Time";	
			$caption=$Order_FT.$Order_Half_Full_Time_betting_order;
			switch ($rtype){
			case "RFHH":
				$w_m_place=$w_mb_team.'&nbsp;/&nbsp;'.$w_mb_team;
				$w_m_place_tw=$w_mb_team_tw.'&nbsp;/&nbsp;'.$w_mb_team_tw;
				$w_m_place_en=$w_mb_team_en.'&nbsp;/&nbsp;'.$w_mb_team_en;		
				$s_m_place=$row[$mb_team].'&nbsp;/&nbsp;'.$row[$mb_team];
				$w_m_rate=change_rate($open,$ioradio_r_h);
				break;
			case "RFHN":
				$w_m_place=$w_mb_team.'&nbsp;/&nbsp;和局';
				$w_m_place_tw=$w_mb_team_tw.'&nbsp;/&nbsp;和局';
				$w_m_place_en=$w_mb_team_en.'&nbsp;/&nbsp;Flat';		
				$s_m_place=$row[$mb_team].'&nbsp;/&nbsp;'.$Draw;		
				$w_m_rate=change_rate($open,$ioradio_r_h);
				break;
			case "RFHC":
				$w_m_place=$w_mb_team.'&nbsp;/&nbsp;'.$w_tg_team;
				$w_m_place_tw=$w_mb_team_tw.'&nbsp;/&nbsp;'.$w_tg_team_tw;
				$w_m_place_en=$w_mb_team_en.'&nbsp;/&nbsp;'.$w_tg_team_en;
				$s_m_place=$row[$mb_team].'&nbsp;/&nbsp;'.$row[$tg_team];
				$w_m_rate=change_rate($open,$ioradio_r_h);
				break;
			case "RFNH":
				$w_m_place='和局&nbsp;/&nbsp;'.$w_mb_team;
				$w_m_place_tw='和局&nbsp;/&nbsp;'.$w_mb_team_tw;
				$w_m_place_en='Flat&nbsp;/&nbsp;'.$w_mb_team_en;
				$s_m_place=$Draw.'&nbsp;/&nbsp;'.$row[$mb_team];
				$w_m_rate=change_rate($open,$ioradio_r_h);
				break;
			case "RFNN":
				$w_m_place='和局&nbsp;/&nbsp;和局';
				$w_m_place_tw='和局&nbsp;/&nbsp;和局';
				$w_m_place_en='Flat&nbsp;/&nbsp;Flat';
				$s_m_place=$Draw.'&nbsp;/&nbsp;'.$Draw;
				$w_m_rate=change_rate($open,$ioradio_r_h);
				break;
			case "RFNC":
				$w_m_place='和局&nbsp;/&nbsp;'.$w_tg_team;
				$w_m_place_tw='和局&nbsp;/&nbsp;'.$w_tg_team_tw;
				$w_m_place_en='Flat&nbsp;/&nbsp;'.$w_tg_team_en;
				$s_m_place=$Draw.'&nbsp;/&nbsp;'.$row[$tg_team];	
				$w_m_rate=change_rate($open,$ioradio_r_h);
				break;
			case "RFCH":
				$w_m_place=$w_tg_team.'&nbsp;/&nbsp;'.$w_mb_team;
				$w_m_place_tw=$w_tg_team_tw.'&nbsp;/&nbsp;'.$w_mb_team_tw;
				$w_m_place_en=$w_tg_team_en.'&nbsp;/&nbsp;'.$w_mb_team_en;
				$s_m_place=$row[$tg_team].'&nbsp;/&nbsp;'.$row[$mb_team];
				$w_m_rate=change_rate($open,$ioradio_r_h);
				break;
			case "RFCN":
				$w_m_place=$w_tg_team.'&nbsp;/&nbsp;和局';
				$w_m_place_tw=$w_tg_team_tw.'&nbsp;/&nbsp;和局';
				$w_m_place_en=$w_tg_team_en.'&nbsp;/&nbsp;Flat';
				$s_m_place=$row[$tg_team].'&nbsp;/&nbsp;'.$Draw;
				$w_m_rate=change_rate($open,$ioradio_r_h);
				break;
			case "RFCC":
				$w_m_place=$w_tg_team.'&nbsp;/&nbsp;'.$w_tg_team;
				$w_m_place_tw=$w_tg_team_tw.'&nbsp;/&nbsp;'.$w_tg_team_tw;
				$w_m_place_en=$w_tg_team_en.'&nbsp;/&nbsp;'.$w_tg_team_en;
				$s_m_place=$row[$tg_team].'&nbsp;/&nbsp;'.$row[$tg_team];
				$w_m_rate=change_rate($open,$ioradio_r_h);
				break;
		}
			$Sign="VS.";
			$gwin=($w_m_rate-1)*$gold;		
			$ptype='F';
			$w_gtype=$rtype;
			break;	
		case 115:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='双方球队进球';
			$bet_type_tw="双方球队进球";
			$bet_type_en="Double In Correct Score";
			$caption=$Order_FT.$Order_Double_In_betting_order;
			if($rtype=="HTSY" ||$rtype=="HTSN"){$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";}
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RTSY":
					$w_m_place='是';
					$w_m_place_tw='是';
					$w_m_place_en='YES';
					$s_m_place='是';
					break;
				case "RTSN":
					$w_m_place='不是';
					$w_m_place_tw='不是';
					$w_m_place_en='NO';
					$s_m_place='不是';
					break;
				case "RHTSY":
					$w_m_place='是';
					$w_m_place_tw='是';
					$w_m_place_en='YES';
					$s_m_place='是';
					break;
				case "RHTSN":
					$w_m_place='不是';
					$w_m_place_tw='不是';
					$w_m_place_en='NO';
					$s_m_place='不是';
					break;
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='TS';
			$w_gtype=$rtype;
			break;
		case 118:
//            echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
//            exit;
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='净胜球数';
			$bet_type_tw="净胜球数";
			$bet_type_en="Order_Net_Win_Ballnum";
			$caption=$Order_FT.' '.$Running_Ball.' '.$Order_Net_Win_Ballnum.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RWMH1":
					$w_m_place=$w_mb_team." - 净胜1球";
					$w_m_place_tw=$w_mb_team_tw." - 净胜1球";
					$w_m_place_en=$w_mb_team_en." - net win one ball"; 
					$s_m_place=$s_mb_team." - 净胜1球";
					break;	
				case "RWM0": 
					$w_m_place=" 0 - 0 和局";
					$w_m_place_tw=" 0 - 0 和局";
					$w_m_place_en=" 0 - 0 Flat"; 
					$s_m_place=" 0 - 0 和局";
					break;	
				case "RWMC1":
					$w_m_place=$w_tg_team." - 净胜1球";
					$w_m_place_tw=$w_tg_team_tw." - 净胜1球";
					$w_m_place_en=$w_tg_team_en." - net win one ball";  
					$s_m_place=$s_tg_team." - 净胜1球";
					break;
				case "RWMH2": 
					$w_m_place=$w_mb_team." - 净胜2球";
					$w_m_place_tw=$w_mb_team_tw." - 净胜2球";
					$w_m_place_en=$w_mb_team_en." - net win 2 ball"; 
					$s_m_place=$s_mb_team." - 净胜2球";
					break;	
				case "RWMN": 
					$w_m_place="任何进球和局";
					$w_m_place_tw="任何进球和局";
					$w_m_place_en="any ball in Flat"; 
					$s_m_place="任何进球和局";
					break;	
				case "RWMC2": 
					$w_m_place=$w_tg_team." - 净胜2球";
					$w_m_place_tw=$w_tg_team_tw." - 净胜2球";
					$w_m_place_en=$w_tg_team_en." - net win 2 ball";  
					$s_m_place=$s_tg_team." - 净胜2球";
					break;	
				case "RWMH3": 
					$w_m_place=$w_mb_team." - 净胜3球";
					$w_m_place_tw=$w_mb_team_tw." - 净胜3球";
					$w_m_place_en=$w_mb_team_en." - net win 3 ball"; 
					$s_m_place=$s_mb_team." - 净胜3球";
					break;
				case "RWMC3": 
					$w_m_place=$w_tg_team." - 净胜3球";
					$w_m_place_tw=$w_tg_team_tw." - 净胜3球";
					$w_m_place_en=$w_tg_team_en." - net win 3 ball";  
					$s_m_place=$s_tg_team." - 净胜3球";
					break;	
				case "RWMHOV": 
					$M_Place=$MB_Team." - 净胜4球或更多"; 
					$w_m_place=$w_mb_team." - 净胜4球或更多";
					$w_m_place_tw=$w_mb_team_tw." - 净胜4球或更多";
					$w_m_place_en=$w_mb_team_en." - net win 4 ball or more"; 
					$s_m_place=$s_mb_team." - 净胜4球或更多";
					break;	
				case "RWMCOV": 
					$w_m_place=$w_tg_team." - 净胜4球或更多";
					$w_m_place_tw=$w_tg_team_tw." - 净胜4球或更多";
					$w_m_place_en=$w_tg_team_en." - net win 4 ball or more";  
					$s_m_place=$s_tg_team." - 净胜4球或更多";
					break; 
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='WM';
			$w_gtype=$rtype;
			break;
		case 119:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='双重机会';
			$bet_type_tw="双重机会";
			$bet_type_en="Order_Chance_Double";
			$caption=$Order_FT.' '.$Running_Ball.' '.$Order_Chance_Double.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RDCHN": 
					$w_m_place=$w_mb_team." / "."和局"; 
					$w_m_place_tw=$w_mb_team_tw." / "."和局";
					$w_m_place_en=$w_mb_team_en." / Flat";  
					$s_m_place=$s_mb_team." / "."和局"; 
					break; 
				case "RDCCN": 
					$w_m_place=$w_tg_team." / "."和局"; 
					$w_m_place_tw=$w_tg_team_tw." / "."和局"; 
					$w_m_place_en=$w_tg_team_en." / Flat";  
					$s_m_place=$s_tg_team." / "."和局"; 
					break; 
				case "RDCHC": 
					$w_m_place=$w_mb_team." / ".$w_tg_team;
					$w_m_place_tw=$w_mb_team_tw." / ".$w_tg_team_tw;
					$w_m_place_en=$w_mb_team_en." / ".$w_tg_team_en; 
					$s_m_place=$s_mb_team." / ".$s_tg_team;
					break; 
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='DC';
			$w_gtype=$rtype;
			break;
		case 120:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='零失球';
			$bet_type_tw="零失球";
			$bet_type_en="Order_Clean_Sheets";
			$caption=$Order_FT.'_'.$Order_Clean_Sheets.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RCSH": 
					$w_m_place=$w_mb_team; 
					$w_m_place_tw=$w_mb_team_tw;
					$w_m_place_en=$w_mb_team_en;  
					$s_m_place=$s_mb_team; 
					break; 
				case "RCSC": 
					$w_m_place=$w_tg_team; 
					$w_m_place_tw=$w_tg_team_tw; 
					$w_m_place_en=$w_tg_team_en;  
					$s_m_place=$s_tg_team; 
					break; 
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='CS';
			$w_gtype=$rtype;
			break;
		case 161:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='零失球获胜';
			$bet_type_tw="零失球获胜";
			$bet_type_en="Order_Clean_Sheets_Win";
			$caption=$Order_FT.'_'.$Order_Clean_Sheets_Win.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RWNH": 
					$w_m_place=$w_mb_team; 
					$w_m_place_tw=$w_mb_team_tw;
					$w_m_place_en=$w_mb_team_en;  
					$s_m_place=$s_mb_team; 
					break; 
				case "RWNC": 
					$w_m_place=$w_tg_team; 
					$w_m_place_tw=$w_tg_team_tw; 
					$w_m_place_en=$w_tg_team_en;  
					$s_m_place=$s_tg_team; 
					break; 
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='WN';
			$w_gtype=$rtype;
			break;
		case 122:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='独赢 & 进球 大 / 小';
			$bet_type_tw="独赢 & 进球 大  / 小";
			$bet_type_en="Order_M_Ball_OU";
			$caption=$Order_FT.'_'.$Order_M_Ball_OU.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RMUAHO":	//独赢 & 进球 大 / 小  A
					$M_Place=$MB_Team." & 大 1.5"; 
					$w_m_place=$w_mb_team.' & 大 1.5'; 
					$w_m_place_tw=$w_mb_team_tw.' & 大 1.5';
					$w_m_place_en=$w_mb_team_en.' & Over 1.5';  
					$s_m_place=$s_mb_team." ".$U_B1;
					break;	
				case "RMUANO":	 
					$w_m_place="和局"." & 大 1.5"; 
					$w_m_place_tw="和局"." & 大 1.5"; 
					$w_m_place_en="Flat".' & Over 1.5';  
					$s_m_place="和局"." ".$U_B1;
					break;	
				case "RMUACO":	 
					$w_m_place=$w_tg_team." & 大 1.5"; 
					$w_m_place_tw=$w_tg_team_tw." & 大 1.5"; 
					$w_m_place_en=$w_tg_team_en.' & Over 1.5';  
					$s_m_place=$s_tg_team." ".$U_B1; 
					break;		
				case "RMUAHU":	
					$M_Place=$MB_Team." & 小 1.5"; 
					$w_m_place=$w_mb_team.' & 小 1.5'; 
					$w_m_place_tw=$w_mb_team_tw.' & 小 1.5';
					$w_m_place_en=$w_mb_team_en.' & Under 1.5';  
					$s_m_place=$s_mb_team.$U_S1;
					break;	
				case "RMUANU":	 
					$w_m_place="和局"." & 小 1.5"; 
					$w_m_place_tw="和局"." & 小 1.5"; 
					$w_m_place_en="Flat".' & Under 1.5';  
					$s_m_place="和局"." ".$U_S1;
					break;	
				case "RMUACU":	 
					$w_m_place=$w_tg_team.' & 小 1.5'; 
					$w_m_place_tw=$w_tg_team_tw.' & 小 1.5';
					$w_m_place_en=$w_tg_team_en.' & Under 1.5';  
					$s_m_place=$s_tg_team." ".$U_S1;
					break;
				case "RMUBHO":	//独赢 & 进球 大 / 小  B
					$M_Place=$MB_Team." & 大2.5"; 
					$w_m_place=$w_mb_team.' & 大 2.5'; 
					$w_m_place_tw=$w_mb_team_tw.' & 大 2.5';
					$w_m_place_en=$w_mb_team_en.' & Over 2.5';  
					$s_m_place=$s_mb_team." ".$U_B2;
					break;	
				case "RMUBNO":	 
					$w_m_place="和局"." & 大 2.5"; 
					$w_m_place_tw="和局"." & 大 2.5"; 
					$w_m_place_en="Flat".' & Over 2.5';  
					$s_m_place="和局"." ".$U_B2;
					break;	
				case "RMUBCO":	 
					$w_m_place=$w_tg_team." & 大 2.5"; 
					$w_m_place_tw=$w_tg_team_tw." & 大 2.5"; 
					$w_m_place_en=$w_tg_team_en.' & Over 2.5';  
					$s_m_place=$s_tg_team." ".$U_B2; 
					break;		
				case "RMUBHU":	
					$M_Place=$MB_Team." & 小 2.5"; 
					$w_m_place=$w_mb_team.' & 小 2.5'; 
					$w_m_place_tw=$w_mb_team_tw.' & 小 2.5';
					$w_m_place_en=$w_mb_team_en.' & Under 2.5';  
					$s_m_place=$s_mb_team." ".$U_S2;
					break;	
				case "RMUBNU":	 
					$w_m_place="和局"." & 小 2.5"; 
					$w_m_place_tw="和局"." & 小 2.5"; 
					$w_m_place_en="Flat".' & Under 2.5';  
					$s_m_place="和局"." ".$U_S2;
					break;	
				case "RMUBCU":	 
					$w_m_place=$w_tg_team.' & 小 2.5'; 
					$w_m_place_tw=$w_tg_team_tw.' & 小 2.5';
					$w_m_place_en=$w_tg_team_en.' & Under 2.5';  
					$s_m_place=$s_tg_team." ".$U_S2;
					break;
				case "RMUCHO":	//独赢 & 进球 大 / 小  C
					$M_Place=$MB_Team." & 大3.5"; 
					$w_m_place=$w_mb_team.' & 大 3.5'; 
					$w_m_place_tw=$w_mb_team_tw.' & 大 3.5';
					$w_m_place_en=$w_mb_team_en.' & Over 3.5';  
					$s_m_place=$s_mb_team." ".$U_B3;
					break;	
				case "RMUCNO":	 
					$w_m_place="和局"." & 大 3.5"; 
					$w_m_place_tw="和局"." & 大 3.5"; 
					$w_m_place_en="Flat".' & Over 3.5';  
					$s_m_place="和局"." ".$U_B3;
					break;	
				case "RMUCCO":	 
					$w_m_place=$w_tg_team." & 大 3.5"; 
					$w_m_place_tw=$w_tg_team_tw." & 大 3.5"; 
					$w_m_place_en=$w_tg_team_en.' & Over 3.5';  
					$s_m_place=$s_tg_team." ".$U_B3; 
					break;		
				case "RMUCHU":	
					$M_Place=$MB_Team." & 小 3.5"; 
					$w_m_place=$w_mb_team.' & 小 3.5'; 
					$w_m_place_tw=$w_mb_team_tw.' & 小 3.5';
					$w_m_place_en=$w_mb_team_en.' & Under 3.5';  
					$s_m_place=$s_mb_team." ".$U_S3;
					break;	
				case "RMUCNU":	 
					$w_m_place="和局"." & 小 3.5"; 
					$w_m_place_tw="和局"." & 小 3.5"; 
					$w_m_place_en="Flat".' & Under 3.5';  
					$s_m_place="和局"." ".$U_S3;
					break;	
				case "RMUCCU":	 
					$w_m_place=$w_tg_team.' & 小 3.5'; 
					$w_m_place_tw=$w_tg_team_tw.' & 小 3.5';
					$w_m_place_en=$w_tg_team_en.' & Under 3.5';  
					$s_m_place=$s_tg_team." ".$U_S3;
					break; 
				case "RMUDHO":	//独赢 & 进球 大 / 小  D
					$w_m_place=$w_mb_team.' & 大 4.5'; 
					$w_m_place_tw=$w_mb_team_tw.' & 大 4.5';
					$w_m_place_en=$w_mb_team_en.' & Over 4.5';  
					$s_m_place=$s_mb_team." ".$U_B4;
					break;	
				case "RMUDNO":	 
					$w_m_place="和局"." & 大 4.5"; 
					$w_m_place_tw="和局"." & 大 4.5"; 
					$w_m_place_en="Flat".' & Over 4.5';  
					$s_m_place="和局"." ".$U_B4;
					break;	
				case "RMUDCO":	 
					$w_m_place=$w_tg_team." & 大 4.5"; 
					$w_m_place_tw=$w_tg_team_tw." & 大 4.5"; 
					$w_m_place_en=$w_tg_team_en.' & Over 4.5';  
					$s_m_place=$s_tg_team." ".$U_B4; 
					break;		
				case "RMUDHU":	
					$w_m_place=$w_mb_team.' & 小 4.5'; 
					$w_m_place_tw=$w_mb_team_tw.' & 小 4.5';
					$w_m_place_en=$w_mb_team_en.' & Under 4.5';  
					$s_m_place=$s_mb_team." ".$U_S4;
					break;	
				case "RMUDNU":	 
					$w_m_place="和局"." & 小 4.5"; 
					$w_m_place_tw="和局"." & 小 4.5"; 
					$w_m_place_en="Flat".' & Under 4.5';  
					$s_m_place="和局"." ".$U_S4;
					break;	
				case "RMUDCU":	 
					$w_m_place=$w_tg_team.' & 小 4.5'; 
					$w_m_place_tw=$w_tg_team_tw.' & 小 4.5';
					$w_m_place_en=$w_tg_team_en.' & Under 4.5';  
					$s_m_place=$s_tg_team." ".$U_S4;
					break; 
			}
			$abcdType = substr($rtype,3,1);
			$abcdTypeOU = substr($rtype,5,1);
			if($abcdType=="A") $grape=$abcdTypeOU."1.5";
			if($abcdType=="B") $grape=$abcdTypeOU."2.5";
			if($abcdType=="C") $grape=$abcdTypeOU."3.5";
			if($abcdType=="D") $grape=$abcdTypeOU."4.5";
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='MOU';
			$w_gtype=$rtype;
			break;	
		case 123:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='独赢 & 双方球队进球';
			$bet_type_tw="独赢 & 双方球队进球";
			$bet_type_en="Double In Correct Score";
			$caption=$Order_FT.$Order_M_Ball_Double_in;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
			case "RMTSHY":
				$w_m_place=$w_mb_team.' & 是';
				$w_m_place_tw=$w_mb_team_tw.' & 是';
				$w_m_place_en=$w_mb_team_en.' & YES';
				$s_m_place=$s_mb_team.' & 是';
				break;
			case "RMTSNY":
				$w_m_place='和局 & 是';
				$w_m_place_tw='和局 & 是';
				$w_m_place_en='Flat & YES';
				$s_m_place='和局 & 是';
				break;
			case "RMTSCY":
				$w_m_place=$w_tg_team.' & 是';
				$w_m_place_tw=$w_tg_team_tw.' & 是';
				$w_m_place_en=$w_tg_team_en.' & YES';
				$s_m_place=$s_tg_team.' & 是';
				break;
			case "RMTSHN":
				$w_m_place=$w_mb_team.' & 不是';
				$w_m_place_tw=$w_mb_team_tw.' & 不是';
				$w_m_place_en=$w_mb_team_en.' & NO';
				$s_m_place=$s_mb_team.' & 不是';
				break;
			case "RMTSNN":
				$w_m_place='和局  & 不是';
				$w_m_place_tw='和局  & 不是';
				$w_m_place_en='和局  & NO';
				$s_m_place='和局  & 不是';
				break;
			case "RMTSCN":
				$w_m_place=$w_tg_team.' & 不是';
				$w_m_place_tw=$w_tg_team_tw.' & 不是';
				$w_m_place_en=$w_tg_team_en.' & NO';
				$s_m_place=$s_tg_team.' & 不是';
				break;
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='MTS';
			$w_gtype=$rtype;
			break;
		case 124:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='进球 大 / 小 & 双方球队进球';
			$bet_type_tw="进球 大 / 小 & 双方球队进球";
			$bet_type_en="Order_Ball_OU_Double_in";
			$caption=$Order_FT.$Order_Ball_OU_Double_in;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
			case "RUTAOY":	//进球 大 / 小 & 双方球队进球	 A
				$w_m_place='大 1.5  & 是';
				$w_m_place_tw='大 1.5  & 是';
				$w_m_place_en='Over 1.5 & YES';
				$s_m_place=$U_B1.' & 是';
				break;	
			case "RUTAON":	 
				$w_m_place='大 1.5  & 不是';
				$w_m_place_tw='大 1.5  & 不是';
				$w_m_place_en='Over 1.5 & No';
				$s_m_place=$U_B1.' & 不是';
				break;		
			case "RUTAUY":	
				$w_m_place='小 1.5  & 是';
				$w_m_place_tw='小 1.5  & 是';
				$w_m_place_en='Under 1.5 & YES';
				$s_m_place=$U_S1.' & 是';
				break;	
			case "RUTAUN":	 
				$w_m_place='小 1.5  & 不是';
				$w_m_place_tw='小 1.5  & 不是';
				$w_m_place_en='Under 1.5 & No';
				$s_m_place=$U_S1.' & 不是';
				break;
			case "RUTBOY":	//进球 大 / 小 & 双方球队进球	 B
				$w_m_place='大 2.5  & 是';
				$w_m_place_tw='大 2.5  & 是';
				$w_m_place_en='Over 2.5 & YES';
				$s_m_place=$U_B2.' & 是';
				break;	
			case "RUTBON":	 
				$w_m_place='大 2.5  & 不是';
				$w_m_place_tw='大 2.5  & 不是';
				$w_m_place_en='Over 2.5 & No';
				$s_m_place=$U_B2.' & 不是';
				break;		
			case "RUTBUY":	
				$w_m_place='小 2.5  & 是';
				$w_m_place_tw='小 2.5  & 是';
				$w_m_place_en='Under 2.5 & YES';
				$s_m_place=$U_S2.' & 是';
				break;	
			case "RUTBUN":	 
				$w_m_place='小 2.5  & 不是';
				$w_m_place_tw='小 2.5  & 不是';
				$w_m_place_en='Under 2.5 & No';
				$s_m_place=$U_S2.' & 不是';
				break;
			case "RUTCOY":	//进球 大 / 小 & 双方球队进球	 C
				$w_m_place='大 3.5  & 是';
				$w_m_place_tw='大 3.5  & 是';
				$w_m_place_en='Over 3.5 & YES';
				$s_m_place=$U_B3.' & 是';
				break;	
			case "RUTCON":	 
				$w_m_place='大 3.5  & 不是';
				$w_m_place_tw='大 3.5  & 不是';
				$w_m_place_en='Over 3.5 & No';
				$s_m_place=$U_B3.' & 不是';
				break;		
			case "RUTCUY":	
				$w_m_place='小 3.5  & 是';
				$w_m_place_tw='小 3.5  & 是';
				$w_m_place_en='Under 3.5 & YES';
				$s_m_place=$U_S3.' & 是';
				break;	
			case "RUTCUN":	 
				$w_m_place='小 3.5  & 不是';
				$w_m_place_tw='小3.5  & 不是';
				$w_m_place_en='Under 3.5 & No';
				$s_m_place=$U_S3.' & 不是';
				break;
			case "RUTDOY":	//进球 大 / 小 & 双方球队进球	 D
				$w_m_place='大 4.5  & 是';
				$w_m_place_tw='大 4.5  & 是';
				$w_m_place_en='Over 4.5 & YES';
				$s_m_place=$U_B4.' & 是';
				break;	
			case "RUTDON":	 
				$w_m_place='大 4.5  & 不是';
				$w_m_place_tw='大 4.5  & 不是';
				$w_m_place_en='Over 4.5 & No';
				$s_m_place=$U_B4.' & 不是';
				break;		
			case "RUTDUY":	
				$w_m_place='小 4.5  & 是';
				$w_m_place_tw='小 4.5  & 是';
				$w_m_place_en='Under 4.5 & YES';
				$s_m_place=$U_S4.' & 是';
				break;	
			case "RUTDUN":	 
				$w_m_place='小 4.5  & 不是';
				$w_m_place_tw='小 4.5  & 不是';
				$w_m_place_en='Under 4.5 & No';
				$s_m_place=$U_S4.' & 不是';
				break;
			}
			$abcdType = substr($rtype,3,1);
			$abcdTypeOU = substr($rtype,4,1);
			if($abcdType=="A") $grape=$abcdTypeOU."1.5";
			if($abcdType=="B") $grape=$abcdTypeOU."2.5";
			if($abcdType=="C") $grape=$abcdTypeOU."3.5";
			if($abcdType=="D") $grape=$abcdTypeOU."4.5";
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='OUT';
			$w_gtype=$rtype;
			break;
		case 125:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='独赢 & 最先进球';
			$bet_type_tw="独赢 & 最先进球";
			$bet_type_en="Order_M_Ball_First";
			$caption=$Order_FT.$Order_M_Ball_First;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
			case "MPGHH":
				$w_m_place=$w_mb_team.' & '.$w_mb_team."(最先进球)";
				$w_m_place_tw=$w_mb_team_tw.' & '.$w_mb_team_tw."(最先进球)";
				$w_m_place_en=$w_mb_team_en.' & '.$w_mb_team_en."(first ball in)";
				$s_m_place=$s_mb_team.' & '.$s_mb_team.'(最先进球)';
				break;
			case "MPGNH":
				$w_m_place='和局 & '.$w_mb_team."(最先进球)";
				$w_m_place_tw='和局 & '.$w_mb_team_tw."(最先进球)";
				$w_m_place_en='Flat & '.$w_mb_team_en."(first ball in)";
				$s_m_place='和局 & '.$s_mb_team.'(最先进球)';
				break;
			case "MPGCH":
				$w_m_place=$w_tg_team.' & '.$w_mb_team."(最先进球)";
				$w_m_place_tw=$w_tg_team_tw.' & '.$w_mb_team_tw."(最先进球)";
				$w_m_place_en=$w_tg_team_en.' & '.$w_mb_team_en."(first ball in)";
				$s_m_place=$s_tg_team.' & '.$s_mb_team.'(最先进球)';
				break;
			case "MPGHC":
				$w_m_place=$w_mb_team.' & '.$w_tg_team."(最先进球)";
				$w_m_place_tw=$w_mb_team_tw.' & '.$w_tg_team_tw."(最先进球)";
				$w_m_place_en=$w_mb_team_en.' & '.$w_tg_team_en."(first ball in)";
				$s_m_place=$s_mb_team.' & '.$s_tg_team.'(最先进球)';
				break;
			case "MPGNC":
				$w_m_place='和局 & '.$w_tg_team."(最先进球)";
				$w_m_place_tw='和局 & '.$w_tg_team_tw."(最先进球)";
				$w_m_place_en='Flat & '.$w_tg_team_en."(first ball in)";
				$s_m_place='和局 & '.$s_tg_team.'(最先进球)';
				break;
			case "MPGCC":
				$w_m_place=$w_tg_team.' & '.$w_tg_team."(最先进球)";
				$w_m_place_tw=$w_tg_team_tw.' & '.$w_tg_team_tw."(最先进球)";
				$w_m_place_en=$w_tg_team_en.' & '.$w_tg_team_en."(first ball in)";
				$s_m_place=$s_tg_team.' & '.$s_tg_team.'(最先进球)';
				break;
			}
			
			$Sign="VS.";
			$gwin=($w_m_rate)*$gold;
			$ptype='MPG';
			$w_gtype=$rtype;
			break;
		case 126:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='先进2球的一方';
			$bet_type_tw="先进2球的一方";
			$bet_type_en="Order_Ball_In_2";
			$caption=$Order_FT.$Order_Ball_In_2.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
			case "F2GH":
				$w_m_place=$w_mb_team;
				$w_m_place_tw=$w_mb_team_tw;
				$w_m_place_en=$w_mb_team_en;
				$s_m_place=$s_mb_team;
				break;
			case "F2GC":
				$w_m_place=$w_tg_team;
				$w_m_place_tw=$w_tg_team_tw;
				$w_m_place_en=$w_tg_team_en;
				$s_m_place=$s_tg_team;
				break;
			}
			$Sign="VS.";
			$gwin=($w_m_rate)*$gold;
			$ptype='F2G';
			$w_gtype=$rtype;
			break;
		case 127:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='先进3球的一方';
			$bet_type_tw="先进3球的一方";
			$bet_type_en="Order_Ball_In_3";
			$caption=$Order_FT.$Order_Ball_In_3.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
			case "F3GH":
				$w_m_place=$w_mb_team;
				$w_m_place_tw=$w_mb_team_tw;
				$w_m_place_en=$w_mb_team_en;
				$s_m_place=$s_mb_team;
				break;
			case "F3GC":
				$w_m_place=$w_tg_team;
				$w_m_place_tw=$w_tg_team_tw;
				$w_m_place_en=$w_tg_team_en;
				$s_m_place=$s_tg_team;
				break;
			}
			$Sign="VS.";
			$gwin=($w_m_rate-1)*$gold;
			$ptype='F3G';
			$mtype=$rtype;
			break;
		case 128:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='最多进球的半场';
			$bet_type_tw="最多进球的半场";
			$bet_type_en="Order_Most_Ball_In_Half";
			$caption=$Order_FT.$Order_Most_Ball_In_Half.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
			case "RHGH":
				$w_m_place="上半场";
				$w_m_place_tw="上半场";
				$w_m_place_en="first half (of a game)";
				$s_m_place="上半场";
				break;
			case "RHGC":
				$w_m_place="下半场";
				$w_m_place_tw="下半场";
				$w_m_place_en="second half (of a game)";
				$s_m_place="下半场";
				break;
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='HG';
			$w_gtype=$rtype;
			break;
		case 129:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='最多进球的半场 - 独赢';
			$bet_type_tw="最多进球的半场 - 独赢";
			$bet_type_en="Order_Most_Ball_In_Half_M";
			$caption=$Order_FT.$Order_Most_Ball_In_Half_M.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
			case "RMGH":
				$w_m_place="上半场";
				$w_m_place_tw="上半场";
				$w_m_place_en="first half (of a game)";
				$s_m_place="上半场";
				break;
			case "RMGC":
				$w_m_place="下半场";
				$w_m_place_tw="下半场";
				$w_m_place_en="second half (of a game)";
				$s_m_place="下半场";
				break;
			case "RMGN":
				$w_m_place="和局";
				$w_m_place_tw="和局";
				$w_m_place_en="Flat (of a game)";
				$s_m_place="和局";
				break;
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='MG';
			$w_gtype=$rtype;
			break;
		case 130:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='双半场进球';
			$bet_type_tw="双半场进球";
			$bet_type_en="Order_Double_Half_Ball_In";
			$caption=$Order_FT.$Order_Double_Half_Ball_In.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
			case "RSBH":
				$w_m_place=$w_mb_team;
				$w_m_place_tw=$w_mb_team_tw;
				$w_m_place_en=$w_mb_team_en;
				$s_m_place=$s_mb_team;
				break;
			case "RSBC":
				$w_m_place=$w_tg_team;
				$w_m_place_tw=$w_tg_team_tw;
				$w_m_place_en=$w_tg_team_en;
				$s_m_place=$s_tg_team;
				break;
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='SB';
			$w_gtype=$rtype;
			break;
		case 131:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='首个进球方式';
			$bet_type_tw="首个进球方式";
			$bet_type_en="Order_Frist_Ball_In_Way";
			$caption=$Order_FT.$Order_Frist_Ball_In_Way.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "FGS":
					$w_m_place="射门";
					$w_m_place_tw="射门";
					$w_m_place_en="shoot (of a game)";
					$s_m_place="射门";
					break;
				case "FGH":
					$w_m_place="头球";
					$w_m_place_tw="头球";
					$w_m_place_en="Header";
					$s_m_place="头球";
					break;
				case "FGN":
					$w_m_place="无进球";
					$w_m_place_tw="无进球";
					$w_m_place_en="Without a goal";
					$s_m_place="无进球";
					break;
				case "FGP":
					$w_m_place="点球";
					$w_m_place_tw="点球";
					$w_m_place_en="point sphere";
					$s_m_place="点球";
					break;
				case "FGF":
					$w_m_place="任意球";
					$w_m_place_tw="任意球";
					$w_m_place_en="Free ball";
					$s_m_place="任意球";
					break;
				case "FGO":
					$w_m_place="乌龙球";
					$w_m_place_tw="乌龙球";
					$w_m_place_en="own goal";
					$s_m_place="乌龙球";
					break;
			}
			$Sign="VS.";
			$gwin=($w_m_rate)*$gold;
			$ptype='FG';
			$w_gtype=$rtype;
			break;
		case 132:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='首个进球时间-3项';
			$bet_type_tw="首个进球时间-3项";
			$bet_type_en="Order_Frist_Ball_In_Time_3P";
			$caption=$Order_FT.$Order_Frist_Ball_In_Time_3P.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
			case "T3G1":
				$w_m_place="第26分钟或之前";
				$w_m_place_tw="第26分钟或之前";
				$w_m_place_en="26min  or before";
				$s_m_place="第26分钟或之前";
				break;
			case "T3G2":
				$w_m_place="第27分钟或之后";
				$w_m_place_tw="第27分钟或之后";
				$w_m_place_en="27min  or after";
				$s_m_place="第27分钟或之后";
				break;
			case "T3GN":
				$w_m_place="无进球";
				$w_m_place_tw="无进球";
				$w_m_place_en="Without a goal";
				$s_m_place="无进球";
				break;
			}
			$Sign="VS.";
			$gwin=($w_m_rate)*$gold;
			$ptype='T3G';
			$w_gtype=$rtype;
			break;
		case 133:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='首个进球时间';
			$bet_type_tw="首个进球时间";
			$bet_type_en="Order_Frist_Ball_In_Time";
			$caption=$Order_FT.$Order_Frist_Ball_In_Time.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
			case "T1G1":
				$w_m_place="上半场开场 - 14:59分钟";
				$w_m_place_tw="上半场开场 - 14:59分钟";
				$w_m_place_en="first start  - 14:59";
				$s_m_place="上半场开场 - 14:59分钟";
				break;
			case "T1G2":
				$w_m_place="15:00分钟 - 29:59分钟";
				$w_m_place_tw="15:00分钟 - 29:59分钟";
				$w_m_place_en="15min  -  29:59";
				$s_m_place="15:00分钟 - 29:59分钟";
				break;
			case "T1G3":
				$w_m_place="30:00分钟 - 半场";
				$w_m_place_tw="30:00分钟 - 半场";
				$w_m_place_en="30min  -  half end";
				$s_m_place="30:00分钟 - 半场";
				break;
			case "T1G4":
				$w_m_place="下半场开场 - 59:59分钟";
				$w_m_place_tw="下半场开场 - 59:59分钟";
				$w_m_place_en="sec start - 59:59";
				$s_m_place="下半场开场 - 59:59分钟";
				break;
			case "T1G5":
				$w_m_place="60:00分钟 - 74:59分钟";
				$w_m_place_tw="60:00分钟 - 74:59分钟";
				$w_m_place_en="60min  -  74:59min";
				$s_m_place="60:00分钟 - 74:59分钟";
				break;
			case "T1G6":
				$w_m_place="75:00分钟 - 全场完场";
				$w_m_place_tw="75:00分钟 - 全场完场";
				$w_m_place_en="75min - all end";
				$s_m_place="75:00分钟 - 全场完场";
				break;
			case "T1GN":
				$w_m_place="无进球";
				$w_m_place_tw="无进球";
				$w_m_place_en="Without a goal";
				$s_m_place="无进球";
				break;
	
			}
			$Sign="VS.";
			$gwin=($w_m_rate)*$gold;
			$ptype='T1G';
			$w_gtype=$rtype;
			break;
		case 134:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='双重机会 & 进球 大 / 小';
			$bet_type_tw="双重机会 & 进球 大 / 小";
			$bet_type_en="Order_Chance_Double_And_OU";
			$caption=$Order_FT.$Order_Chance_Double_And_OU;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RDUAHO":	//进球 大 / 小 & 双方球队进球	 A
					$w_m_place=$w_mb_team.' / 和局  &  大 1.5 ';
					$w_m_place_tw=$w_mb_team_tw.' / 和局  &  大 1.5 ';
					$w_m_place_en=$w_mb_team_en.' / Flat  And Over 1.5 ';
					$s_m_place=$s_mb_team.' / 和局  &  '.$U_B1;
					break;	
				case "RDUACO":	 
					$w_m_place=$w_tg_team.' / 和局  大 1.5';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  大 1.5';
					$w_m_place_en=$w_tg_team_en.' / Flat  And  Over 1.5';
					$s_m_place=$s_tg_team.' /和局  &  '.$U_B1;
					break;		
				case "RDUASO":	
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  &  大 1.5';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.'  &  大 1.5';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.'	And	Over 1.5';
					$s_m_place=$s_mb_team." / ".$s_tg_team." & ".$U_B1;
					break;	
				case "RDUAHU":	 
					$w_m_place=$w_mb_team.' / 和局  &  小 1.5 ';
					$w_m_place_tw=$w_mb_team_tw.' / 和局  &  小 1.5 ';
					$w_m_place_en=$w_mb_team_en.' / Flat  And Under 1.5 ';
					$s_m_place=$s_mb_team.' / 和局  &  '.$U_S1;
					break;
				case "RDUACU":	
					$w_m_place=$w_tg_team.' / 和局  小 1.5';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  小 1.5';
					$w_m_place_en=$w_tg_team_en.' / Flat  And  Under 1.5';
					$s_m_place=$s_tg_team.' /和局  &  '.$U_S1;
					break;	
				case "RDUASU":	 
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  &  小 1.5';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.'  &  小 1.5';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.'	And	Under 1.5';
					$s_m_place=$s_mb_team." / ".$s_tg_team." & ".$U_S1;
					break;
				case "RDUBHO":	// B
					$w_m_place=$w_mb_team.' / 和局  &  大 2.5 ';
					$w_m_place_tw=$w_mb_team_tw.' / 和局  &  大 2.5 ';
					$w_m_place_en=$w_mb_team_en.' / Flat  And Over 2.5 ';
					$s_m_place=$s_mb_team.' / 和局  &  '.$U_B2;
					break;	
				case "RDUBCO":	 
					$w_m_place=$w_tg_team.' / 和局  大 2.5';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  大 2.5';
					$w_m_place_en=$w_tg_team_en.' / Flat  And  Over 2.5';
					$s_m_place=$s_tg_team.' /和局  &  '.$U_B2;
					break;		
				case "RDUBSO":	
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  &  大 2.5';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.'  &  大 2.5';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.'	And	Over 2.5';
					$s_m_place=$s_mb_team." / ".$s_tg_team." & ".$U_B2;
					break;	
				case "RDUBHU":	 
					$w_m_place=$w_mb_team.' / 和局  &  小 2.5 ';
					$w_m_place_tw=$w_mb_team_tw.' / 和局  &  小 2.5 ';
					$w_m_place_en=$w_mb_team_en.' / Flat  And Under 2.5 ';
					$s_m_place=$s_mb_team.' / 和局  &  '.$U_S2;
					break;
				case "RDUBCU":	
					$w_m_place=$w_tg_team.' / 和局  小 2.5';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  小 2.5';
					$w_m_place_en=$w_tg_team_en.' / Flat  And  Under 2.5';
					$s_m_place=$s_tg_team.' /和局  &  '.$U_S2;
					break;	
				case "RDUBSU":	 
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  &  小 2.5';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.'  &  小 2.5';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.'	And	Under 2.5';
					$s_m_place=$s_mb_team." / ".$s_tg_team." & ".$U_S2;
					break;
				case "RDUCHO":	//C
					$w_m_place=$w_mb_team.' / 和局  &  大 3.5 ';
					$w_m_place_tw=$w_mb_team_tw.' / 和局  &  大 3.5 ';
					$w_m_place_en=$w_mb_team_en.' / Flat  And Over 3.5 ';
					$s_m_place=$s_mb_team.' / 和局  &  '.$U_B2;
					break;	
				case "RDUCCO":	 
					$w_m_place=$w_tg_team.' / 和局  大 3.5';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  大 3.5';
					$w_m_place_en=$w_tg_team_en.' / Flat  And  Over 3.5';
					$s_m_place=$s_tg_team.' /和局  &  '.$U_B3;
					break;		
				case "RDUCSO":	
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  &  大 3.5';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.'  &  大 3.5';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.'	And	Over 3.5';
					$s_m_place=$s_mb_team." / ".$s_tg_team." & ".$U_B3;
					break;	
				case "RDUCHU":	 
					$w_m_place=$w_mb_team.' / 和局  &  小 3.5 ';
					$w_m_place_tw=$w_mb_team_tw.' / 和局  &  小 3.5 ';
					$w_m_place_en=$w_mb_team_en.' / Flat  And Under 3.5 ';
					$s_m_place=$s_mb_team.' / 和局  &  '.$U_S3;
					break;
				case "RDUCCU":	
					$w_m_place=$w_tg_team.' / 和局  小 3.5';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  小 3.5';
					$w_m_place_en=$w_tg_team_en.' / Flat  And  Under 3.5';
					$s_m_place=$s_tg_team.' /和局  &  '.$U_S3;
					break;	
				case "RDUCSU":	 
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  &  小 3.5';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.'  &  小 3.5';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.'	And	Under 3.5';
					$s_m_place=$s_mb_team." / ".$s_tg_team." & ".$U_S3;
					break;
				case "RDUDHO":	//D
					$w_m_place=$w_mb_team.' / 和局  &  大 4.5 ';
					$w_m_place_tw=$w_mb_team_tw.' / 和局  &  大 4.5 ';
					$w_m_place_en=$w_mb_team_en.' / Flat  And Over 4.5 ';
					$s_m_place=$s_mb_team.' / 和局  &  '.$U_B4;
					break;	
				case "RDUDCO":	 
					$w_m_place=$w_tg_team.' / 和局  大 4.5';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  大 4.5';
					$w_m_place_en=$w_tg_team_en.' / Flat  And  Over 4.5';
					$s_m_place=$s_tg_team.' /和局  &  '.$U_B4;
					break;		
				case "RDUDSO":	
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  &  大 4.5';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.'  &  大 4.5';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.'	And	Over 4.5';
					$s_m_place=$s_mb_team." / ".$s_tg_team." & ".$U_B2;
					break;	
				case "RDUDHU":	 
					$w_m_place=$w_mb_team.' / 和局  &  小 4.5 ';
					$w_m_place_tw=$w_mb_team_tw.' / 和局  &  小 4.5 ';
					$w_m_place_en=$w_mb_team_en.' / Flat  And Under 4.5 ';
					$s_m_place=$s_mb_team.' / 和局  &  '.$U_S4;
					break;
				case "RDUDCU":	
					$w_m_place=$w_tg_team.' / 和局  小 4.5';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  小 4.5';
					$w_m_place_en=$w_tg_team_en.' / Flat  And  Under 4.5';
					$s_m_place=$s_tg_team.' /和局  &  '.$U_S4;
					break;	
				case "RDUDSU":	 
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  &  小 4.5';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.'  &  小 4.5';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.'	And	Under 4.5';
					$s_m_place=$s_mb_team." / ".$s_tg_team." & ".$U_S4;
					break;
			}
			$abcdType = substr($rtype,3,1);
			$abcdTypeOU = substr($rtype,5,1);
			if($abcdType=="A") $grape=$abcdTypeOU."1.5";
			if($abcdType=="B") $grape=$abcdTypeOU."2.5";
			if($abcdType=="C") $grape=$abcdTypeOU."3.5";
			if($abcdType=="D") $grape=$abcdTypeOU."4.5";
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='DU';
			$w_gtype=$rtype;
			break;
		case 135:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='双重机会 & 双方球队进球';
			$bet_type_tw="双重机会 & 双方球队进球";
			$bet_type_en="Order_Chance_Double_And_Double_In";
			$caption=$Order_FT.$Order_Chance_Double_And_Double_In;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RDSHY":	
					$w_m_place=$w_mb_team.' / 和局 &  是';
					$w_m_place_tw=$w_mb_team_tw.'/ 和局  &  是';
					$w_m_place_en=$w_mb_team_en.'  /Flat  And  Yes';
					$s_m_place=$s_mb_team.'/ 和局   &  是';
					break;	
				case "RDSCY":	 
					$w_m_place=$w_tg_team.' / 和局  &  是';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  &  是';
					$w_m_place_en=$w_tg_team_en.'  /Flat  And Yes';
					$s_m_place=$s_tg_team.' / 和局  &  是';
					break;		
				case "RDSSY":	
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  & 是';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.'  & 是';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.' And Yes';
					$s_m_place=$s_mb_team." / ".$s_tg_team."  &  是";
					break;	
				case "RDSHN":	 
					$w_m_place=$w_mb_team.' / 和局  &  不是';
					$w_m_place_tw=$w_mb_team_tw.' / 和局  &  不是';
					$w_m_place_en=$w_mb_team_en.'  / Flat 	And	No';
					$s_m_place=$s_mb_team.' / 和局  &  不是';
					break;
				case "RDSCN":	
					$w_m_place=$w_tg_team.' / 和局  &  不是';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  &  不是';
					$w_m_place_en=$w_tg_team_en.'  / Flat And	No';
					$s_m_place=$s_tg_team.' / 和局  &  不是';
					break;	
				case "RDSSN":	 
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  &  不是';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.' &  不是';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.'	And	No';
					$s_m_place=$s_mb_team." / ".$s_tg_team." &  不是";
					break;
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='DS';
			$w_gtype=$rtype;
			break;	
		case 136:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='双重机会 & 最先进球';
			$bet_type_tw="双重机会 & 最先进球";
			$bet_type_en="Order_Chance_Double_And_Ball_In_First";
			$caption=$Order_FT.$Order_Chance_Double_And_Ball_In_First;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RDGHH":	
					$w_m_place=$w_mb_team.' / 和局 &  '.$w_mb_team.'(最先进球)';
					$w_m_place_tw=$w_mb_team_tw.'/ 和局  & '.$w_mb_team_tw.' (最先进球)';
					$w_m_place_en=$w_mb_team_en.'  /Flat '.$w_mb_team_en.' (And  Ball_In_First)';
					$s_m_place=$s_mb_team.'/ 和局   &  '.$s_mb_team.'(最先进球)';
					break;	
				case "RDGCH":	 
					$w_m_place=$w_tg_team.' / 和局  &  '.$w_mb_team.'(最先进球)';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  &  '.$w_mb_team_tw.' (最先进球)';
					$w_m_place_en=$w_tg_team_en.'  /Flat  '.$w_mb_team_en.'(And Ball_In_First)';
					$s_m_place=$s_tg_team.' / 和局  &  '.$s_mb_team.'(最先进球)';
					break;		
				case "RDGSH":	
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  & '.$w_mb_team.'(最先进球)';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.'  & '.$w_mb_team_tw.'(最先进球)';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.' And '.$w_mb_team_en.'(Ball_In_First)';
					$s_m_place=$s_mb_team." / ".$s_tg_team."  &  ".$s_mb_team."(最先进球)";
					break;	
				case "RDGHC":	 
					$w_m_place=$w_mb_team.' / 和局  &  '.$w_tg_team.'(最先进球)';
					$w_m_place_tw=$w_mb_team_tw.' / 和局  & '.$w_tg_team_tw.'(最先进球)';
					$w_m_place_en=$w_mb_team_en.'  / Flat  And	 '.$w_tg_team_en.' (Ball_In_First)';
					$s_m_place=$s_mb_team.' / 和局  &  '.$s_tg_team.'(最先进球)';
					break;
				case "RDGCC":	
					$w_m_place=$w_tg_team.' / 和局  &  '.$w_tg_team.'(最先进球)';
					$w_m_place_tw=$w_tg_team_tw.' / 和局  &  '.$w_tg_team_tw.'(最先进球)';
					$w_m_place_en=$w_tg_team_en.'  / Flat And  '.$w_tg_team_en.'(Ball_In_First)';
					$s_m_place=$s_tg_team.' / 和局  &  '.$s_tg_team.'(最先进球)';
					break;	
				case "RDGSC":	 
					$w_m_place=$w_mb_team.' / '.$w_tg_team.'  &  '.$w_tg_team.'(最先进球)';
					$w_m_place_tw=$w_mb_team_tw.' / '.$w_tg_team_tw.' &  '.$w_tg_team_tw.'(最先进球)';
					$w_m_place_en=$w_mb_team_en.' / '.$w_tg_team_en.'	And	'.$w_tg_team_en.'Ball_In_First';
					$s_m_place=$s_mb_team." / ".$s_tg_team." &  ".$s_tg_team."(最先进球)";
					break;
			}
			$Sign="VS.";
			$gwin=($w_m_rate)*$gold;
			$ptype='DG';
			$w_gtype=$rtype;
			break;
		case 137:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='进球 大 / 小 & 进球 单 / 双';
			$bet_type_tw="进球 大 / 小 & 进球 单 / 双";
			$bet_type_en="Order_OU_And_OE";
			$caption=$Order_FT.$Order_OU_And_OE;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RUEAOO":	
					$w_m_place='单  &  大 1.5 ';
					$w_m_place_tw='单 &  大 1.5 ';
					$w_m_place_en=' Odd  And Over 1.5 ';
					$s_m_place=' 单 &  '.$U_B1;
					break;	
				case "RUEAOE":	 
					$w_m_place=' 双  &  大 1.5';
					$w_m_place_tw=' 双  &   大 1.5';
					$w_m_place_en=' Even  And  Over 1.5';
					$s_m_place='双  & '.$U_B1;
					break;		
				case "RUEAUO":	 
					$w_m_place='单  &  小 1.5 ';
					$w_m_place_tw='单   &  小 1.5 ';
					$w_m_place_en='Odd  And Under 1.5 ';
					$s_m_place='单  &  '.$U_S1;
					break;
				case "RUEAUE":	
					$w_m_place=' 双  &  小 1.5';
					$w_m_place_tw='双  &  小 1.5';
					$w_m_place_en=' Even  And  Under 1.5';
					$s_m_place='双  &  '.$U_S1;
					break;	
				case "RUEBOO":	
					$w_m_place='单  &  大 2.5 ';
					$w_m_place_tw='单 &  大 2.5 ';
					$w_m_place_en=' Odd  And Over 2.5 ';
					$s_m_place=' 单 &  '.$U_B2;
					break;	
				case "RUEBOE":	 
					$w_m_place=' 双  &  大 2.5';
					$w_m_place_tw=' 双  &   大 2.5';
					$w_m_place_en=' Even  And  Over 2.5';
					$s_m_place='双  & '.$U_B2;
					break;		
				case "RUEBUO":	 
					$w_m_place='单  &  小 2.5 ';
					$w_m_place_tw='单   &  小 2.5 ';
					$w_m_place_en='Odd  And Under 2.5 ';
					$s_m_place='单  &  '.$U_S2;
					break;
				case "RUEBUE":	
					$w_m_place=' 双  &  小 2.5';
					$w_m_place_tw='双  &  小 2.5';
					$w_m_place_en=' Even  And  Under 2.5';
					$s_m_place='双  &  '.$U_S2;
					break;	
				case "RUECOO":	
					$w_m_place='单  &  大 3.5 ';
					$w_m_place_tw='单 &  大 3.5 ';
					$w_m_place_en=' Odd  And Over 3.5 ';
					$s_m_place=' 单 &  '.$U_B3;
					break;	
				case "RUECOE":	 
					$w_m_place=' 双  &  大 3.5';
					$w_m_place_tw=' 双  &   大 3.5';
					$w_m_place_en=' Even  And  Over 3.5';
					$s_m_place='双  & '.$U_B3;
					break;		
				case "RUECUO":	 
					$w_m_place='单  &  小 3.5 ';
					$w_m_place_tw='单   &  小 3.5 ';
					$w_m_place_en='Odd  And Under 3.5 ';
					$s_m_place='单  &  '.$U_S3;
					break;
				case "RUECUE":	
					$w_m_place=' 双  &  小 3.5';
					$w_m_place_tw='双  &  小 3.5';
					$w_m_place_en=' Even  And  Under 3.5';
					$s_m_place='双  &  '.$U_S3;
					break;	
				case "RUEDOO":	
					$w_m_place='单  &  大 4.5 ';
					$w_m_place_tw='单 &  大 4.5 ';
					$w_m_place_en=' Odd  And Over 4.5 ';
					$s_m_place=' 单 &  '.$U_B4;
					break;	
				case "RUEDOE":	 
					$w_m_place=' 双  &  大 4.5';
					$w_m_place_tw=' 双  &   大 4.5';
					$w_m_place_en=' Even  And  Over 4.5';
					$s_m_place='双  & '.$U_B4;
					break;		
				case "RUEDUO":	 
					$w_m_place='单  &  小 4.5 ';
					$w_m_place_tw='单   &  小 4.5 ';
					$w_m_place_en='Odd  And Under 4.5 ';
					$s_m_place='单  &  '.$U_S4;
					break;
				case "RUEDUE":	
					$w_m_place=' 双  &  小 4.5';
					$w_m_place_tw='双  &  小 4.5';
					$w_m_place_en=' Even  And  Under 4.5';
					$s_m_place='双  &  '.$U_S4;
					break;		
			}
			$abcdType = substr($rtype,3,1);
			$abcdTypeOU = substr($rtype,4,1);
			if($abcdType=="A") $grape=$abcdTypeOU."1.5";
			if($abcdType=="B") $grape=$abcdTypeOU."2.5";
			if($abcdType=="C") $grape=$abcdTypeOU."3.5";
			if($abcdType=="D") $grape=$abcdTypeOU."4.5";
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='OUE';
			$w_gtype=$rtype;
			break;	
		case 138:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='进球 大 / 小 & 最先进球';
			$bet_type_tw="进球 大 / 小 & 最先进球";
			$bet_type_en="Order_OU_And_Ball_In_First";
			$caption=$Order_FT.$Order_OU_And_Ball_In_First;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "OUPAOH":	//A
					$w_m_place=$w_mb_team.'(最先进球)'.' &  大 1.5 ';
					$w_m_place_tw=$w_mb_team_tw.' (最先进球)'.' &  大 1.5 ';
					$w_m_place_en=$w_mb_team_en.' (And  Ball_In_First)'.' And Over 1.5 ';
					$s_m_place=$s_mb_team.'(最先进球)'.' & '.$U_B1;
					break;	
				case "OUPAOC":	 
					$w_m_place=$w_tg_team.'(最先进球)'.' &  大 1.5';
					$w_m_place_tw=$w_tg_team_tw.'(最先进球)'.' &   大 1.5';
					$w_m_place_en=$w_tg_team_en.'(Ball_In_First)'.' Even  And  Over 1.5';
					$s_m_place=$s_tg_team.'(最先进球)'.'& '.$U_B1;
					break;		
				case "OUPAUH":	 
					$w_m_place=$w_mb_team.'(最先进球)'.' &  小 1.5 ';
					$w_m_place_tw=$w_mb_team_tw.' (最先进球)'.'   &  小 1.5 ';
					$w_m_place_en=$w_mb_team_en.' (And  Ball_In_First)'.'  And Under 1.5 ';
					$s_m_place=$s_mb_team.'(最先进球)'.'  &  '.$U_S1;
					break;
				case "OUPAUC":	
					$w_m_place=$w_tg_team.'(最先进球)'.' &  小 1.5';
					$w_m_place_tw=$w_tg_team_tw.'(最先进球)'.'  &  小 1.5';
					$w_m_place_en=$w_tg_team_en.'(Ball_In_First)'.'  And  Under 1.5';
					$s_m_place=$s_tg_team.'(最先进球)'.'  &  '.$U_S1;
					break;
				case "OUPBOH":	//B
					$w_m_place=$w_mb_team.'(最先进球)'.' &  大 2.5 ';
					$w_m_place_tw=$w_mb_team_tw.' (最先进球)'.' &  大 2.5 ';
					$w_m_place_en=$w_mb_team_en.' (And  Ball_In_First)'.' And Over 2.5 ';
					$s_m_place=$s_mb_team.'(最先进球)'.' & '.$U_B2;
					break;	
				case "OUPBOC":	 
					$w_m_place=$w_tg_team.'(最先进球)'.' &  大 2.5';
					$w_m_place_tw=$w_tg_team_tw.'(最先进球)'.' &   大 2.5';
					$w_m_place_en=$w_tg_team_en.'(Ball_In_First)'.' Even  And  Over 2.5';
					$s_m_place=$s_tg_team.'(最先进球)'.'& '.$U_B2;
					break;		
				case "OUPBUH":	 
					$w_m_place=$w_mb_team.'(最先进球)'.' &  小 2.5 ';
					$w_m_place_tw=$w_mb_team_tw.' (最先进球)'.'   &  小 2.5 ';
					$w_m_place_en=$w_mb_team_en.' (And  Ball_In_First)'.'  And Under 2.5 ';
					$s_m_place=$s_mb_team.'(最先进球)'.'  &  '.$U_S2;
					break;
				case "OUPBUC":	
					$w_m_place=$w_tg_team.'(最先进球)'.' &  小 2.5';
					$w_m_place_tw=$w_tg_team_tw.'(最先进球)'.'  &  小 2.5';
					$w_m_place_en=$w_tg_team_en.'(Ball_In_First)'.'  And  Under 2.5';
					$s_m_place=$s_tg_team.'(最先进球)'.'  &  '.$U_S2;
					break;
				case "OUPCOH":	//C
					$w_m_place=$w_mb_team.'(最先进球)'.' &  大 3.5 ';
					$w_m_place_tw=$w_mb_team_tw.' (最先进球)'.' &  大 3.5 ';
					$w_m_place_en=$w_mb_team_en.' (And  Ball_In_First)'.' And Over 3.5 ';
					$s_m_place=$s_mb_team.'(最先进球)'.' & '.$U_B3;
					break;	
				case "OUPCOC":	 
					$w_m_place=$w_tg_team.'(最先进球)'.' &  大 3.5';
					$w_m_place_tw=$w_tg_team_tw.'(最先进球)'.' &   大 3.5';
					$w_m_place_en=$w_tg_team_en.'(Ball_In_First)'.' Even  And  Over 3.5';
					$s_m_place=$s_tg_team.'(最先进球)'.'& '.$U_B3;
					break;		
				case "OUPCUH":	 
					$w_m_place=$w_mb_team.'(最先进球)'.' &  小 3.5 ';
					$w_m_place_tw=$w_mb_team_tw.' (最先进球)'.'   &  小 3.5 ';
					$w_m_place_en=$w_mb_team_en.' (And  Ball_In_First)'.'  And Under 3.5 ';
					$s_m_place=$s_mb_team.'(最先进球)'.'  &  '.$U_S3;
					break;
				case "OUPCUC":	
					$w_m_place=$w_tg_team.'(最先进球)'.' &  小 3.5';
					$w_m_place_tw=$w_tg_team_tw.'(最先进球)'.'  &  小 3.5';
					$w_m_place_en=$w_tg_team_en.'(Ball_In_First)'.'  And  Under 3.5';
					$s_m_place=$s_tg_team.'(最先进球)'.'  &  '.$U_S3;
					break;	
				case "OUPDOH":	//D
					$w_m_place=$w_mb_team.'(最先进球)'.' &  大 4.5 ';
					$w_m_place_tw=$w_mb_team_tw.' (最先进球)'.' &  大 4.5 ';
					$w_m_place_en=$w_mb_team_en.' (And  Ball_In_First)'.' And Over 4.5 ';
					$s_m_place=$s_mb_team.'(最先进球)'.' & '.$U_B4;
					break;	
				case "OUPDOC":	 
					$w_m_place=$w_tg_team.'(最先进球)'.' &  大 4.5';
					$w_m_place_tw=$w_tg_team_tw.'(最先进球)'.' &   大 4.5';
					$w_m_place_en=$w_tg_team_en.'(Ball_In_First)'.' Even  And  Over 4.5';
					$s_m_place=$s_tg_team.'(最先进球)'.'& '.$U_B4;
					break;		
				case "OUPDUH":	 
					$w_m_place=$w_mb_team.'(最先进球)'.' &  小 4.5 ';
					$w_m_place_tw=$w_mb_team_tw.' (最先进球)'.'   &  小 4.5 ';
					$w_m_place_en=$w_mb_team_en.' (And  Ball_In_First)'.'  And Under 4.5 ';
					$s_m_place=$s_mb_team.'(最先进球)'.'  &  '.$U_S4;
					break;
				case "OUPDUC":	
					$w_m_place=$w_tg_team.'(最先进球)'.' &  小 4.5';
					$w_m_place_tw=$w_tg_team_tw.'(最先进球)'.'  &  小 4.5';
					$w_m_place_en=$w_tg_team_en.'(Ball_In_First)'.'  And  Under 4.5';
					$s_m_place=$s_tg_team.'(最先进球)'.'  &  '.$U_S4;
					break;			
			}
			$abcdType = substr($rtype,3,1);
		    if($abcdType=="A") $grape=1.5;
			if($abcdType=="B") $grape=2.5;
			if($abcdType=="C") $grape=3.5;
			if($abcdType=="D") $grape=4.5;
			$Sign="VS.";
			$gwin=($w_m_rate)*$gold;
			$ptype='OUP';
			$w_gtype=$rtype;
			break;
	     case 139:
	     	$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='三项让球投注';
			$bet_type_tw="三项让球投注";
			$bet_type_en="Order_Ball_R_3";
			$caption=$Order_FT.$Order_Ball_R_3.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "W3H":
					$w_m_place=$w_mb_team." ".$detailsData['ratio_'.strtolower($rtype)];
					$w_m_place_tw=$w_mb_team_tw." ".$detailsData['ratio_'.strtolower($rtype)];
					$w_m_place_en=$w_mb_team_en." ".$detailsData['ratio_'.strtolower($rtype)];
					$s_m_place=$s_mb_team." ".$detailsData['ratio_'.strtolower($rtype)];
					break;
				case "W3C":
					$w_m_place=$w_tg_team." ".$detailsData['ratio_'.strtolower($rtype)];
					$w_m_place_tw=$w_tg_team_tw." ".$detailsData['ratio_'.strtolower($rtype)];
					$w_m_place_en=$w_tg_team_en." ".$detailsData['ratio_'.strtolower($rtype)];
					$s_m_place=$s_tg_team." ".$detailsData['ratio_'.strtolower($rtype)];
					break;
				case "W3N":
					$w_m_place="让球和局"." ".$detailsData['ratio_'.strtolower($rtype)];
					$w_m_place_tw="让球和局"." ".$detailsData['ratio_'.strtolower($rtype)];
					$w_m_place_en="Ball  R  Flat"." ".$detailsData['ratio_'.strtolower($rtype)];
					$s_m_place="让球和局"." ".$detailsData['ratio_'.strtolower($rtype)];
					break;
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			if(strlen($showtype)==0 || $showtype=''){
				$showtype=$detailsData['strong'];
			}
			$ptype='W3';
			$w_gtype=$rtype;
			break;
		case 140:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='落后反超获胜';
			$bet_type_tw="落后反超获胜";
			$bet_type_en="Order_Fall_Catchup_And_Win";
			$caption=$Order_FT.$Order_Fall_Catchup_And_Win.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "BHH":
					$w_m_place=$w_mb_team;
					$w_m_place_tw=$w_mb_team_tw;
					$w_m_place_en=$w_mb_team_en;
					$s_m_place=$s_mb_team;
					break;
				case "BHC":
					$w_m_place=$w_tg_team;
					$w_m_place_tw=$w_tg_team_tw;
					$w_m_place_en=$w_tg_team_en;
					$s_m_place=$s_tg_team;
					break;
			}
			$Sign="VS.";
			$gwin=($w_m_rate)*$gold;
			$ptype='BH';
			$w_gtype=$rtype;
			break;
		case 141:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='赢得任一半场';
			$bet_type_tw="赢得任一半场";
			$bet_type_en="Order_Win_Any_Half";
			$caption=$Order_FT.$Order_Win_Any_Half.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RWEH":
					$w_m_place=$w_mb_team;
					$w_m_place_tw=$w_mb_team_tw;
					$w_m_place_en=$w_mb_team_en;
					$s_m_place=$s_mb_team;
					break;
				case "RWEC":
					$w_m_place=$w_tg_team;
					$w_m_place_tw=$w_tg_team_tw;
					$w_m_place_en=$w_tg_team_en;
					$s_m_place=$s_tg_team;
					break;
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='WE';
			$w_gtype=$rtype;
			break;
		case 142:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='赢得所有半场';
			$bet_type_tw="赢得所有半场";
			$bet_type_en="Order_Win_All_Half";
			$caption=$Order_FT.$Order_Win_All_Half.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "RWBH":
					$w_m_place=$w_mb_team;
					$w_m_place_tw=$w_mb_team_tw;
					$w_m_place_en=$w_mb_team_en;
					$s_m_place=$s_mb_team;
					break;
				case "RWBC":
					$w_m_place=$w_tg_team;
					$w_m_place_tw=$w_tg_team_tw;
					$w_m_place_en=$w_tg_team_en;
					$s_m_place=$s_tg_team;
					break;
			}
			$Sign="VS.";
			if($w_m_rate>1){
				$gwin=($w_m_rate-1)*$gold;
			}else{
				$gwin=($w_m_rate)*$gold;	
			}
			$ptype='WE';
			$w_gtype=$rtype;
			break;	
		case 143:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		  	$bet_type='开球球队';
			$bet_type_tw="开球球队";
			$bet_type_en="Order_Team_First_Ball";
			$caption=$Order_FT.$Order_Team_First_Ball.$Order_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			switch ($rtype){
				case "TKH":
					$w_m_place=$w_mb_team;
					$w_m_place_tw=$w_mb_team_tw;
					$w_m_place_en=$w_mb_team_en;
					$s_m_place=$s_mb_team;
					break;
				case "TKC":
					$w_m_place=$w_tg_team;
					$w_m_place_tw=$w_tg_team_tw;
					$w_m_place_en=$w_tg_team_en;
					$s_m_place=$s_tg_team;
					break;
			}
			$Sign="VS.";
			$gwin=($w_m_rate)*$gold;
			$ptype='TK';
			$w_gtype=$rtype;
			break;		
		case 151:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
			if(substr($_REQUEST['wtype'],0,1)=="A"){ $gametype=$U_74.':'.$U_74A.'-'.$U_M; }
			if(substr($_REQUEST['wtype'],0,1)=="B"){ $gametype=$U_74.':'.$U_74B.'-'.$U_M; }
			if(substr($_REQUEST['wtype'],0,1)=="C"){ $gametype=$U_74.':'.$U_74C.'-'.$U_M; }
			if(substr($_REQUEST['wtype'],0,1)=="D"){ $gametype=$U_74.':'.$U_74D.'-'.$U_M; }
			if(substr($_REQUEST['wtype'],0,1)=="E"){ $gametype=$U_74.':'.$U_74E.'-'.$U_M; }
			if(substr($_REQUEST['wtype'],0,1)=="F"){ $gametype=$U_74.':'.$U_74F.'-'.$U_M; }
		  	$bet_type=$gametype;
			$bet_type_tw=$gametype;
			$bet_type_en="15min single win";
			$caption=$Order_FT." ".$gametype;
			if($detailsData["sw_".$wtype]=="Y" && $detailsData["ior_".$wtype."H"]>0 && $detailsData["ior_".$wtype."C"]>0 && $detailsData["ior_".$wtype."N"]>0){
				$ior_Rate_H = $detailsData["ior_".$wtype."H"]; 
				$ior_Rate_C = $detailsData["ior_".$wtype."C"]; 
				$ior_Rate_N = $detailsData["ior_".$wtype."N"]; 
			}
			switch ($type){
				case "H":
					$w_m_place=$w_mb_team;
					$w_m_place_tw=$w_mb_team_tw;
					$w_m_place_en=$w_mb_team_en;
					$s_m_place=$s_mb_team;
					$w_m_rate=change_rate($open,$ior_Rate_H);
					break;
				case "C":
					$w_m_place=$w_tg_team;
					$w_m_place_tw=$w_tg_team_tw;
					$w_m_place_en=$w_tg_team_en;
					$s_m_place=$s_tg_team;
					$w_m_rate=change_rate($open,$ior_Rate_C);
					break;
				case "N":
					$w_m_place="和局";
					$w_m_place_tw="和局";
					$w_m_place_en="Flat";
					$s_m_place=$Draw;
					$w_m_rate=change_rate($open,$ior_Rate_N);
					break;
			}
			$Sign="VS.";
			$grape="";
			$gwin=($w_m_rate)*$gold;
			$ptype='15M';
			$w_gtype=$rtype;
			break;	
		case 152:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
			if(substr($_REQUEST['wtype'],0,1)=="A"){ $gametype=$U_74.':'.$U_74A.'-'.$U_R; }
			if(substr($_REQUEST['wtype'],0,1)=="B"){ $gametype=$U_74.':'.$U_74B.'-'.$U_R; }
			if(substr($_REQUEST['wtype'],0,1)=="C"){ $gametype=$U_74.':'.$U_74C.'-'.$U_R; }
			if(substr($_REQUEST['wtype'],0,1)=="D"){ $gametype=$U_74.':'.$U_74D.'-'.$U_R; }
			if(substr($_REQUEST['wtype'],0,1)=="E"){ $gametype=$U_74.':'.$U_74E.'-'.$U_R; }
			if(substr($_REQUEST['wtype'],0,1)=="F"){ $gametype=$U_74.':'.$U_74F.'-'.$U_R; }
		  	$bet_type = $gametype;
			$bet_type_tw= $gametype;
			$bet_type_en=$gametype;	
			$caption=$Order_FT.' '.$gametype;
			if($detailsData["sw_".$wtype]=="Y" && $detailsData["ior_".$wtype."H"]>0 && $detailsData["ior_".$wtype."C"]>0){
				$ior_Rate_H = $detailsData["ior_".$wtype."H"]; 
				$ior_Rate_C = $detailsData["ior_".$wtype."C"]; 
			}
			$rate = get_other_ioratio($odd_f_type,$ior_Rate_H,$ior_Rate_C,100);
			
			switch ($type){
			case "H":
				$w_m_place=$w_mb_team;
				$w_m_place_tw=$w_mb_team_tw;
				$w_m_place_en=$w_mb_team_en;
				$s_m_place=$s_mb_team;
				$s_m_place.=$strong=="H" ? " ".$detailsData['ratio_'.strtolower($wtype)]:'';
				$w_m_rate=change_rate($open,$rate[0]);
				$grape=$strong=="H" ? $detailsData['ratio_'.strtolower($wtype)]:'';
				break;
			case "C":
				$w_m_place=$w_tg_team;
				$w_m_place_tw=$w_tg_team_tw;
				$w_m_place_en=$w_tg_team_en;
				$s_m_place=$s_tg_team;
				$s_m_place.=$strong=="C" ? " ".$detailsData['ratio_'.strtolower($wtype)]:'';
				$w_m_rate=change_rate($open,$rate[1]);
				$grape=$strong=="C" ? $detailsData['ratio_'.strtolower($wtype)]:'';
				break;
			}
			$ptype='15R';
			$w_gtype=$rtype;
			break;		
		case 153:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		    if(substr($_REQUEST['wtype'],0,1)=="A"){ $gametype=$U_74.':'.$U_74A.'-'.$U_OU; }
			if(substr($_REQUEST['wtype'],0,1)=="B"){ $gametype=$U_74.':'.$U_74B.'-'.$U_OU; }
			if(substr($_REQUEST['wtype'],0,1)=="C"){ $gametype=$U_74.':'.$U_74C.'-'.$U_OU; }
			if(substr($_REQUEST['wtype'],0,1)=="D"){ $gametype=$U_74.':'.$U_74D.'-'.$U_OU; }
			if(substr($_REQUEST['wtype'],0,1)=="E"){ $gametype=$U_74.':'.$U_74E.'-'.$U_OU; }
			if(substr($_REQUEST['wtype'],0,1)=="F"){ $gametype=$U_74.':'.$U_74F.'-'.$U_OU; }
			$bet_type=$gametype;
			$bet_type_tw=$gametype;
			$bet_type_en="15min Over/Under";
			$caption=$Order_FT." ".$gametype;
			if($detailsData["sw_".$wtype]=="Y" && $detailsData["ior_".$wtype."O"]>0 && $detailsData["ior_".$wtype."U"]>0){
				$ior_Rate_O = $detailsData["ior_".$wtype."O"]; 
				$ior_Rate_U = $detailsData["ior_".$wtype."U"]; 
			}
			$rate=get_other_ioratio($odd_f_type,$ior_Rate_O,$ior_Rate_U,100);
			switch ($type){
				case "O":
					$w_m_place='大&nbsp'.$detailsData['ratio_'.strtolower($rtype)];
					$w_m_place_tw='大&nbsp'.$detailsData['ratio_'.strtolower($rtype)];
					$w_m_place_en='Over&nbsp;'.$detailsData['ratio_'.strtolower($rtype)];
					$s_m_place=$w_m_place;
					$w_m_rate=change_rate($open,$rate[0]);
					break;
				case "U":
					$w_m_place='小&nbsp;'.$detailsData['ratio_'.strtolower($rtype)];
					$w_m_place_tw='小&nbsp;'.$detailsData['ratio_'.strtolower($rtype)];
					$w_m_place_en='Under&nbsp;'.$detailsData['ratio_'.strtolower($rtype)];
					$s_m_place=$w_m_place;
					$w_m_rate=change_rate($open,$rate[1]);
					break;
			}
			$Sign="VS.";
			if ($odd_f_type=='H'){
			    $gwin=($w_m_rate)*$gold;
			}else if ($odd_f_type=='M' or $odd_f_type=='I'){
			    if ($w_m_rate<0){
					$gwin=$gold;
				}else{
					$gwin=($w_m_rate)*$gold;
				}
			}else if ($odd_f_type=='E'){
			    $gwin=($w_m_rate)*$gold;
			}
			$ptype='15OU';	
			$w_gtype=$rtype;
			$grape=$detailsData['ratio_'.strtolower($rtype)];		
			break;		
		case 206:
			$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
			$bet_type='滚球  半场- 总进球数';
			$bet_type_tw="滚球 半场 - 總进球数";
			$bet_type_en="Running_Ball Half Total Count";
			$caption=$Order_FT."".$Running_Ball.$Order_Total_Goals_betting_order;
			switch ($rtype){
				case "HRT0":
					$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                    $w_m_place='0';
                    $w_m_place_tw='0';
                    $w_m_place_en='0';
                    $s_m_place='0';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "HRT1":
					$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                    $w_m_place='1';
                    $w_m_place_tw='1';
                    $w_m_place_en='1';
                    $s_m_place='1';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "HRT2":
					$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                    $w_m_place='2';
                    $w_m_place_tw='2';
                    $w_m_place_en='2';
                    $s_m_place='2';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "HRTOV":
					$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                    $w_m_place='3或以上';
                    $w_m_place_tw='3或以上';
                    $w_m_place_en='3或以上';
                    $s_m_place='3或以上';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
		}
		
		$Sign="VS.";
		if($w_m_rate>1){
	    	$gwin=($w_m_rate-1)*$gold;
	    }else{
	    	$gwin=($w_m_rate)*$gold;	
	    }
		$ptype='T';
		$grape=$rtype;
		$w_gtype=$rtype;				
		break;
	case 154:
	case 244:	
		$turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$_REQUEST['id'];
		if($wtype == "ROUH"){ // 主队
			if($rtype=='ROUHO'||$rtype=='ROUHU'){
				$bet_type='球队进球数'.' '.$w_mb_team.' -大/小';
				$bet_type_tw="球队进球数".' '.$w_mb_team_tw.' -大/小';
				$bet_type_en="Order_Team_Ball_In".' '.$w_mb_team_en.' -大/小';
				$caption=$Order_FT." ".$Running_Ball." ".$Order_Team_Ball_In.' '.$w_mb_team.' -大/小 '.$Order_betting_order;	
			}else{
				$bet_type='半场球队进球数'.' '.$w_mb_team.' -大/小';
				$bet_type_tw="半场球队进球数".' '.$w_mb_team_tw.' -大/小';
				$bet_type_en="Half_Order_Team_Ball_In".' '.$w_mb_team_en.' -大/小';
				$caption=$Order_FT." ".$Running_Ball." 半场  ".$Order_Team_Ball_In.' '.$w_mb_team.' -大/小 '.$Order_betting_order;
			}
			$ptype='OUH';
		}elseif($wtype == "ROUC"){ // 客队
			if($rtype=='ROUCO'||$rtype=='ROUCU'){
				$bet_type='球队进球数'.' '.$w_tg_team.' -大/小';
				$bet_type_tw="球队进球数".' '.$w_tg_team_tw.' -大/小';
				$bet_type_en="Order_Team_Ball_In".' '.$w_tg_team_en.' -大/小';
				$caption=$Order_FT." ".$Running_Ball." ".$Order_Team_Ball_In.' '.$w_tg_team.' -大/小 '.$Order_betting_order;
			}else{
				$bet_type='半场球队进球数'.' '.$w_tg_team.' -大/小';
				$bet_type_tw="半场球队进球数".' '.$w_tg_team_tw.' -大/小';
				$bet_type_en="Half_Order_Team_Ball_In".' '.$w_tg_team_en.' -大/小';
				$caption=$Order_FT." ".$Running_Ball." 半场    ".$Order_Team_Ball_In.' '.$w_tg_team.' -大/小 '.$Order_betting_order;
			}
			$ptype='OUC';	
		}
		$w_m_rate=change_rate($open,$ioradio_r_h);
		switch ($rtype){
			case "ROUHO":
				$w_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_tw="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_en="OVER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$s_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				break;
			case "ROUHU":
				$w_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_tw="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_en="UNDER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$s_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				break;
			case "ROUCO":
				$w_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_tw="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_en="OVER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$s_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				break;
			case "ROUCU":
				$w_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_tw="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_en="UNDER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$s_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				break;
			case "HRUHO":
				$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
				$w_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_tw="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_en="OVER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$s_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				break;
			case "HRUHU":
				$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
				$w_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_tw="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_en="UNDER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$s_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				break;
			case "HRUCO":
				$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
				$w_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_tw="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_en="OVER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$s_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				break;
			case "HRUCU":
				$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
				$w_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_tw="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$w_m_place_en="UNDER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				$s_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
				break;	
		}
		$Sign="VS.";
		$gwin=($w_m_rate)*$gold;
		$grape = $detailsData['ratio_'.strtolower($rtype)];
		$w_gtype=$rtype;
		break;	
	}
	
	if($gold<10){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}

    if(in_array($line,array(9)) && trim($grape) == "" ){
        echo attention("让球参数异常,请刷新赛事!",$uid,$langx);
        exit();
    }

    if(in_array($line,array(154,244)) && (trim($grape) == "" || trim($grape) <= 0) ){
        echo attention("大小球数参数异常,请刷新赛事!",$uid,$langx);
        exit();
    }

	if($w_m_rate!=change_rate($open,$ioradio_r_h)){
		$turn_url=$turn_url.'&error_flag=1';
		echo "<script language='javascript'>self.location='$turn_url';</script>";
		exit;
	}	
	
	if($s_m_place=='' or $w_m_place=='' or $w_m_place_tw==''){
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
	
	if(strlen($w_m_rate)==0 || $w_m_rate==0){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}
	
	$oddstype=$odd_f_type;
	$w_mb_mid=$row['MB_MID'];
	$w_tg_mid=$row['TG_MID'];

    if($line==205){
        $bottom1="&nbsp;-&nbsp;<font color=#666666>[上半]</font>";
        $bottom1_tw="&nbsp;-&nbsp;<font color=#666666>[上半]</font>";
        $bottom1_en="&nbsp;-&nbsp;</font><font color=#666666>[1st Half]</font>";
    }

	$lines=$row['M_League']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
	$lines=$lines."<FONT color=#cc0000>$w_m_place</FONT>".$bottom1."&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";
	
	$lines_tw=$row['M_League_tw']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team_tw."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_tw."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
	$lines_tw=$lines_tw."<FONT color=#cc0000>$w_m_place_tw</FONT>".$bottom1_tw."&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";
	
	$lines_en=$row['M_League_en']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team_en."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_en."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
	$lines_en=$lines_en."<FONT color=#cc0000>$w_m_place_en</FONT>".$bottom1_en."&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";

	$ip_addr = get_ip();
	
	$psql = "select A_Point,B_Point,C_Point,D_Point from ".DBPREFIX."web_agents_data where UserName='$agents'";
	$result = mysqli_query($dbMasterLink,$psql);
	$prow = mysqli_fetch_assoc($result);
	$a_point=$prow['A_Point']+0;
	$b_point=$prow['B_Point']+0;
	$c_point=$prow['C_Point']+0;
	$d_point=$prow['D_Point']+0;
	
	$showVoucher= show_voucher($wtype);
	
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
        $sql = "INSERT INTO ".DBPREFIX."web_report_data	(userid,Glost,playSource,QQ83068506,danger,MID,testflag,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball) values ($memid,$Money,2,'$inball1','1','$gid',$test_flag,'$active','$showVoucher','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','FT','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball')";
		$insertBet=mysqli_query($dbMasterLink,$sql);
		if($insertBet){
            $lastId=mysqli_insert_id($dbMasterLink);
			$moneyLogRes=addAccountRecords(array($memid,$memname,$test_flag,$Money,$gold*-1,$havemoney,1,2,$lastId,"FT投注$w_gtype"));
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
?>
<html>
<head>
<meta http-equiv='Content-Type' content="text/html; charset=utf-8">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<link rel="stylesheet" href="/style/member/mem_order<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<!--<script>window.setTimeout("self.location='../select.php?uid=<?php/*=$uid*/?>'", 45000);</script>-->
<body id="OFIN" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
  <div class="ord">
    <span><h1><?php echo $caption?></h1></span>
      <div id="info">
       <!--<p><?php/*=$Order_Login_Name*/?><?php/*=$memname*/?></p>-->
       <!--<p class="mem-can-use"><?php/*=$Order_Credit_line*/?><?php/*=$havemoney*/?></p>-->
       <div class="fin_title"><p class="fin_acc">成功提交注单！</p><p class="p-underline"><?php echo $Order_Bet_success?>&nbsp;<?php echo $showVoucher;?></p><p class="error">危险球 - 待确认</p></div>
       <p><center><strong><font color='#FFFFFF' style='background-color: #FF0000'>&nbsp;<?php echo $Order_Pending?>&nbsp;</font></strong></center></p>
       <p class="team"><?php echo $s_sleague?>&nbsp;<?php echo $btype?>&nbsp;<?php echo date('m-d',strtotime($row["M_Date"]))?><BR><?php echo $inball?>&nbsp;&nbsp;<?php echo $s_mb_team?>&nbsp;<font color=#cc0000><?php echo $Sign?></font>&nbsp;<?php echo $s_tg_team?><br><em><?php echo $s_m_place?></em>&nbsp;@&nbsp;<em><strong><?php echo $w_m_rate?></strong></em></p>
       <p class="deal-money"><?php echo $Order_Bet_Amount?><?php echo $gold?></p>
       <!--<p class="canwin-money"><?php/*=$Order_Estimated_Payout*/?><FONT id=pc color=#cc0000><?php/*=$gwin*/?></FONT></p>-->
      </div>
       <p class="foot">
        <input type="BUTTON" name="FINISH" value="<?php echo $Order_Quit?>" onClick="parent.close_bet();" class="no">
      &nbsp;&nbsp; <input type="BUTTON" name="PRINT" value="<?php echo $Order_Print?>" onClick="window.print()" class="yes">
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