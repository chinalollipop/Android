<?php
session_start();
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
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
if($HMoney < $gold || $gold<10 || $HMoney<=0){
	echo attention("$User_insufficient_balance",$uid,$langx);
	exit();
}
$w_current=$_SESSION['CurType'];
$havemoney=$HMoney-$gold;
$memid= $_SESSION['userid'];
$test_flag=$_SESSION['test_flag'];

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
		case '20':
			$sgid=$gid+1;
		    $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$sgid&uid=$suid&odd_f_type=$odd_f_type&type=$type&gnum=$gnum&langx=zh-cn&ptype=&imp=N&rtype=HROU{$type}&wtype=HROU");
			break;
		case '19':
			$sgid=$gid+1;
			$html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$sgid&uid=$suid&odd_f_type=$odd_f_type&type=$type&gnum=$gnum&strong=$strong&langx=zh-cn&ptype=&imp=N&rtype=HRE{$type}&wtype=HRE");
			break;
		case '31':
			$sgid=$gid+1;
			$html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$sgid&uid=$suid&odd_f_type=$odd_f_type&type=$type&gnum=$gnum&langx=zh-cn&ptype=&imp=N&rtype=HRM{$type}&wtype=HRM");
			break;
		case '204':
			$sgid=$gid+1;
			$rtypeSub=substr($rtype,1);
			$html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$sgid&uid=$suid&odd_f_type=$odd_f_type&langx=zh-cn&rtype=$rtypeSub&wtype=HRPD&imp=N&ptype=");
			break;
	}
	$msg_c=explode("@",$html_data);
	if(sizeof($msg_c)>1){
		break;
	}elseif($allcount==$accoutArrNum){
		echo attention("$Order_Odd_changed_please_game_again",$uid,$langx);
		exit();
	}
}
//------------------------------------------------------------


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

if($gid%2 == 1){
	$mysql = "select * from `".DBPREFIX."match_sports` where `MID`=$gid-1 and Open=1 and MB_Team!='' and MB_Team_tw!='' and MB_Team_en!=''";
}else{
	$mysql = "select * from `".DBPREFIX."match_sports` where `MID`='$gid' and Open=1 and MB_Team!='' and MB_Team_tw!='' and MB_Team_en!=''";
}

$result = mysqli_query($dbMasterLink,$mysql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);

if($cou==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit();
}
	if($_REQUEST['id']&&$_REQUEST['id']>0){
		$moreMethod = array(204);
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
			  	 case 204:	$keyY = 'sw_'.$rtype; break;
			  	 default:	$keyY = 'sw_'.$wtype; break;
			}
			
			if($detailsData[$keyY]=="Y" && $detailsData["ior_".$rtype]>0){
				$ioradio_r_h = $detailsData["ior_".$rtype];
				if(!$ioradio_r_h){
					echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
					exit();	
				}
			}

            //更多玩法注入效验
            if(gameFtVerify($line,$wtype,$rtype)){
                if($line==19){
                    $type = substr($rtype,-1,1);
                    $Sign = $detailsData['ratio_hre'];
                }
                if($line==20){
                    if($rtype=='HROUC'){
                        $m_place = $detailsData['ratio_hrouo'];
                    }
                    if($rtype=='HROUH'){
                        $m_place = $detailsData['ratio_hrouu'];
                    }
                    $type = substr($rtype,-1,1);
                    $s_m_place = $w_m_place = $w_m_place_tw = $w_m_place_en =  $m_place;
                }
                if($line==31){ $type = substr($rtype,-1,1); }
            }else{
                echo attention("非法操作,请重新下注!",$uid,$langx);
                exit();
            }

		}	
	}else{
		if(in_array($rtype,array('HRH1C0','HRH2C0','HRH0C1','HRH0C2','HRH2C1','HRH1C2','HRH3C0','HRH0C3','HRH3C1','HRH1C3','HRH3C2','HRH2C3','HRH4C0',
									'HRH0C4','HRH4C1','HRH1C4','HRH4C2','HRH2C4','HRH4C3','HRH3C4','HRH0C0','HRH2C2','HRH1C1','HRH3C3','HRH4C4','HROVH'))){
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
			$ioradio_r_h = $rowExpandRes[$files];
			$rtype = "H".$rtype;
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
	$showtype=$row["ShowTypeHRB"];
	$bettime=date('Y-m-d H:i:s');
	$m_start=strtotime($row['M_Start']);
	$datetime=time();
//    if ($datetime-$m_start<120){
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
	case 31:
	  	$bet_type='半场滚球独赢';
		$bet_type_tw="半場滾球獨贏";
		$bet_type_en="1st Half Running 1x2";
		$btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
		$caption=$Order_FT.$Order_1st_Half_Running_1_x_2_betting_order;
		switch ($type){
		case "H":
			$w_m_place=$w_mb_team;
			$w_m_place_tw=$w_mb_team_tw;
			$w_m_place_en=$w_mb_team_en;
			$s_m_place=$row[$mb_team];
			$ioradio_r_h=$row["MB_Win_Rate_RB_H"];
			$w_m_rate=change_rate($open,$ioradio_r_h);
			$turn_url="/app/member/FT_order/FT_order_hrm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$w_gtype='VRMH';
			break;
		case "C":
			$w_m_place=$w_tg_team;
			$w_m_place_tw=$w_tg_team_tw;
			$w_m_place_en=$w_tg_team_en;
			$s_m_place=$row[$tg_team];
			$ioradio_r_h=$row["TG_Win_Rate_RB_H"];
			$w_m_rate=change_rate($open,$ioradio_r_h);
			$turn_url="/app/member/FT_order/FT_order_hrm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$w_gtype='VRMC';
			break;
		case "N":
			$w_m_place="和局";
			$w_m_place_tw="和局";
			$w_m_place_en="Flat";
			$s_m_place=$Draw;
			$ioradio_r_h=$row["M_Flat_Rate_RB_H"];
			$w_m_rate=change_rate($open,$ioradio_r_h);
			$turn_url="/app/member/FT_order/FT_order_hrm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$w_gtype='VRMN';
			break;
		}
		$Sign="VS.";
		$grape=$type;
		$gwin=($w_m_rate-1)*$gold;
		$ptype='VRM';		
		break;
	case 19:
 		$bet_type='半场滚球让球';
		$bet_type_tw="半場滾球讓球";
		$bet_type_en="1st Half Running Ball";
		$btype="-<font color=red><b>[$Order_1st_Half]</b></font>";	
		$caption=$Order_FT.$Order_1st_Half_Running_Ball_betting_order;
		$rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RB_H"],$row["TG_LetB_Rate_RB_H"],100);
		switch ($type){
		case "H":
			$w_m_place=$w_mb_team;
			$w_m_place_tw=$w_mb_team_tw;
			$w_m_place_en=$w_mb_team_en;
			$s_m_place=$s_mb_team;
			$ioradio_r_h=$rate[0];
			$w_m_rate=change_rate($open,$ioradio_r_h);
			$turn_url="/app/member/FT_order/FT_order_hre.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
			$w_gtype='VRRH';
			break;
		case "C":
			$w_m_place=$w_tg_team;
			$w_m_place_tw=$w_tg_team_tw;
			$w_m_place_en=$w_tg_team_en;
			$s_m_place=$s_tg_team;
			$ioradio_r_h=$rate[1];
			$w_m_rate=change_rate($open,$ioradio_r_h);
			$turn_url="/app/member/FT_order/FT_order_hre.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
			$w_gtype='VRRC';
			break;
		}

        if(!$Sign){$Sign=$row['M_LetB_RB_H'];}
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
		$ptype='VRE';
		break;
	case 20:	
		$bet_type='半场滚球大小';
		$bet_type_tw="半場滾球大小";
		$bet_type_en="1st Half Running Over/Under";
		$btype="- <font color=red><b>[$Order_1st_Half]</b></font>";		
		$caption=$Order_FT.$Order_1st_Half_Running_Ball_Over_Under_betting_order;
		$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB_H"],$row["TG_Dime_Rate_RB_H"],100);
		switch ($type){
		case "C":
		    if(!$w_m_place){
                $w_m_place=$row["MB_Dime_RB_H"];
                $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
            }
            if(!$w_m_place_tw){
                $w_m_place_tw=$row["MB_Dime_RB_H"];
                $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
            }
            if(!$w_m_place_en){
                $w_m_place_en=$row["MB_Dime_RB_H"];
                $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
            }

			if(!$m_place) $m_place=$row["MB_Dime_RB_H"];

			if(!$s_m_place){ $s_m_place=$row["MB_Dime_RB_H"]; }
			if ($langx=="zh-cn"){
	            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
		    }else if ($langx=="zh-cn"){
		        $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
		    }else if ($langx=="en-us" or $langx=='th-tis'){
		        $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
			}			
			$ioradio_r_h=$rate[0];
			$w_m_rate=change_rate($open,$ioradio_r_h);
			$turn_url="/app/member/FT_order/FT_order_hrou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$w_gtype='VROUH';
			break;
		case "H":
		    if(!$w_m_place){
                $w_m_place=$row["TG_Dime_RB_H"];
                $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
            }
            if(!$w_m_place_tw){
                $w_m_place_tw=$row["TG_Dime_RB_H"];
                $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
            }
            if(!$w_m_place_en){
                $w_m_place_en=$row["TG_Dime_RB_H"];
                $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);
            }

            if(!$m_place) $m_place=$row["TG_Dime_RB_H"];

		    if(!$s_m_place){ $s_m_place=$row["TG_Dime_RB_H"]; }
			if ($langx=="zh-cn"){
	            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
		    }else if ($langx=="zh-cn"){
		        $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
		    }else if ($langx=="en-us" or $langx=='th-tis'){
		        $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
			}
			$ioradio_r_h=$rate[1];
			$w_m_rate=change_rate($open,$ioradio_r_h);
			$turn_url="/app/member/FT_order/FT_order_hrou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
			$w_gtype='VROUC';
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
		$ptype='VROU';				
		break;
	case 204:
			$turn_url="/app/member/FT_order/FT_order_hrpd.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
		  	$bet_type='滚球  半场 - 波胆';
			$bet_type_tw='滾球 半场 -波膽';
			$bet_type_en="Running Half Correct Score";
			$caption=$Order_FT.' '.$Running_Ball.' '.$Res_Half.' '.$Order_Correct_Score_betting_order;
			$w_m_rate=change_rate($open,$ioradio_r_h);
			if($rtype=="HROVH"){		
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
	if ($s_m_place=='' or $w_m_place=='' or $w_m_place_tw==''){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}

    if(in_array($line,array(19)) && trim($grape) == "" ){
        echo attention("让球参数异常,请刷新赛事!",$uid,$langx);
        exit();
    }

	if(!isset($_REQUEST['autoOdd']) || $_REQUEST['autoOdd']!="Y"){
		if($w_m_rate!=$_REQUEST['ioradio_r_h']){
			$turn_url=$turn_url.'&error_flag=1';
			echo "<script language='javascript'>self.location='$turn_url';</script>";
			exit;
		}	
	}
	
	$oddstype=$odd_f_type;
	$bottom1="&nbsp;-&nbsp;<font color=#666666>[上半]</font>";
	$bottom1_tw="&nbsp;-&nbsp;<font color=#666666>[上半]</font>";
	$bottom1_en="&nbsp;-&nbsp;</font><font color=#666666>[1st Half]</font>";

	$w_mb_mid=$row['MB_MID'];
	$w_tg_mid=$row['TG_MID'];
	
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
	$sql = "INSERT INTO ".DBPREFIX."web_report_data	(userid,Glost,playSource,testflag,QQ83068506,danger,MID,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball) values ($memid,$Money,2,$test_flag,'$inball1','1','$gid','1','$showVoucher','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','FT','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball')";
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
	
// echo attention("$Order_OK");exit;
?>
<html>
<head>
<meta http-equiv='Content-Type' content="text/html; charset=utf-8">
<script language=javascript>
//window.setTimeout('sendsubmit()',500);
//function sendsubmit(){
//alert('<?php //echo $Order_Please_check_transaction_record?>//');
//
//}
</script>
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
       <p class="team"><?php echo $s_sleague?>&nbsp;<?php echo $btype?>&nbsp;<?php echo date('m-d',strtotime($row["M_Date"]))?><BR><?php echo $inball?>&nbsp;&nbsp;<?php echo $s_mb_team?>&nbsp;&nbsp;<font color=#cc0000><?php echo $Sign?></font>&nbsp;&nbsp;<?php echo $s_tg_team?> 
      <br><em><?php echo $s_m_place?></em> @ <em><strong><?php echo $w_m_rate?></strong></em></p>
       <p class="deal-money"><?php echo $Order_Bet_Amount?><?php echo $gold?></p>
       <!--<p class="canwin-money"><?php/*=$Order_Estimated_Payout*/?><FONT id=pc color=#cc0000><?php/*=$gwin*/?></FONT></p>-->
      </div>
       <p class="foot">
        <input type="BUTTON" name="FINISH" value="<?php echo $Order_Quit?>" onClick="parent.close_bet();" class="no">
      &nbsp;<input type="BUTTON" name="PRINT" value="<?php echo $Order_Print?>" onClick="window.print()" class="yes">
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
