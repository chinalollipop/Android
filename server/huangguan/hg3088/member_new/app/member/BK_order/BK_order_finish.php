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
//接收传递过来的参数：其中赔率和位置需要进行判断
$w_m_bet_name= '';
$uid=(isset($_REQUEST['uid']) && $_REQUEST['uid'])? $_REQUEST['uid'] :$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$gold=$_REQUEST['gold'];
$active=$_REQUEST['active'];
$strong=$_REQUEST['strong'];
$line=$_REQUEST['line_type'];
$gid=$_REQUEST['gid'];
$idOrign=$_REQUEST['id'];
$type=$_REQUEST['type'];
$wtype=$_REQUEST['wtype'];
$rtype=$_REQUEST['rtype'];
$gnum=$_REQUEST['gnum'];
$ioradio_pd=$_REQUEST['ioradio_pd'];
$ioradio_f=$_REQUEST['ioradio_f'];
$odd_f_type=$_REQUEST['odd_f_type'];
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
//下注时的赔率：应该根据盘口进行转换后，与数据库中的赔率进行比较。若不相同，返回下注。
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
$memid=$_SESSION['userid'];
$test_flag=$_SESSION['test_flag'];

$mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!=''";
$resultL = mysqli_query($dbLink,$mysqlL);
$couL=mysqli_num_rows($resultL);
if($couL==0) {
    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
    exit();
}

$mysql = "select * from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Open=1 and MB_Team!=''";//判断此赛程是否已经关闭：取出此场次信息
$result = mysqli_query($dbCenterMasterDbLink,$mysql);

$cou=mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);

// -------------------------------------------------------更多玩法刷水 Start
$redisObj = new Ciredis();
if($_REQUEST['id']&&$_REQUEST['id']>0) {
    $gtype = 'BK';
    switch ($row['Type']){
        case 'BK':
            $showtype='FT';
            break;
        case 'BU':
            $showtype='FU';
            break;
    }
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
                                $details = json_encode($tmp_Obj,JSON_UNESCAPED_UNICODE);
                                $details = str_replace('\'', '', $details);
                                $setGames = mysqli_query($dbMasterLink, "replace into " . DBPREFIX . "match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
                                if ($setGames) {
                                    $comStatus = mysqli_query($dbMasterLink, "COMMIT");
                                    if ($comStatus) {
                                        $redisObj->getSET("gameMore_" . $gid, json_encode(array('status' => 1, 'tmp_Obj' => $tmp_Obj, 'gid_ary' => $gid_ary)));//写入redis
                                    } else {
                                        $redisObj->delete($gid . "_reflush_time");
                                        mysqli_query($dbMasterLink, "ROLLBACK");
                                    }
                                } else {
                                    $redisObj->delete($gid . "_reflush_time");
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                }
                            } else {
                                $redisObj->delete($gid . "_reflush_time");
                                mysqli_query($dbMasterLink, "ROLLBACK");
                            }
                        } else {
                            $redisObj->delete($gid . "_reflush_time");
                            mysqli_query($dbMasterLink, "ROLLBACK");
                        }
                    } else {
                        $redisObj->delete($gid . "_reflush_time");
                        mysqli_query($dbMasterLink, "ROLLBACK");
                    }
                }
            }
            //echo "in date <br/>";
            $games = $redisObj->getSimpleOne("gameMore_" . $gid);//在redis取出数据
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
                            $details = json_encode($tmp_Obj,JSON_UNESCAPED_UNICODE);
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
                                } else {
                                    $redisObj->delete($gid . "_reflush_time");
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                }
                            } else {
                                $redisObj->delete($gid . "_reflush_time");
                                mysqli_query($dbMasterLink, "ROLLBACK");
                            }
                        } else {
                            $redisObj->delete($gid . "_reflush_time");
                            mysqli_query($dbMasterLink, "ROLLBACK");
                        }
                    } else {
                        $redisObj->delete($gid . "_reflush_time");
                        mysqli_query($dbMasterLink, "ROLLBACK");
                    }
                } else {
                    $redisObj->delete($gid . "_reflush_time");
                    mysqli_query($dbMasterLink, "ROLLBACK");
                }
            } else {
                mysqli_query($dbMasterLink, "ROLLBACK");
                $games = $redisObj->getSimpleOne("gameMore_" . $gid);//在redis取出数据
//                echo $games;
//                exit();
            }
        }
    } else {
        $status = '401.9';
        $describe = "更多玩法数据为空3";
        original_phone_request_response($status, $describe);
    }
}
// -------------------------------------------------------更多玩法刷水 End

if($idOrign>0){
	$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`=".$idOrign);
	$rowMore = mysqli_fetch_assoc($moreRes);
	$couMore = mysqli_num_rows($moreRes);
}else{
	$couMore = 0;
}

if($cou==0 && $couMore==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit();
}else{
	$detailsData=array();
	$moreMethod = array(31);
	if($_REQUEST['id']&&$_REQUEST['id']>0){
		array_push($moreMethod,$line);
	}
	if(in_array($line,$moreMethod)){
		$moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$idOrign'");
		$rowMore = mysqli_fetch_assoc($moreRes);
		$couMore = mysqli_num_rows($moreRes);
		$detailsArr = json_decode($rowMore['details'],true);
		$detailsData =$detailsArr[$gid];
		if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
            $mplaceKey='ratio_'.strtolower($rtype);
			$ioradio_r_h = $detailsData["ior_".$rtype];
            $s_m_place = $detailsData[$mplaceKey];
			if(!$ioradio_r_h){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit();
			}
			if($line==31 && $couMore>0){
                if($wtype!='PD' || !in_array($rtype,array('PDH0','PDH1','PDH2','PDH3','PDH4','PDC0','PDC1','PDC2','PDC3','PDC4'))){
                    echo attention("非法操作,请重新下注!",$uid,$langx);
                    exit();
                }
            }
		}
        //更多玩法注入效验
        if(!gameBkVerify($line,$wtype,$rtype)){
            echo attention("非法操作~,请重新下注!",$uid,$langx);
            exit();
        }
	}

	if($line==1){
		if($couMore>0){
			$detailsArr = json_decode($rowMore['details'],true);
			$detailsData =$detailsArr[$gid];
			if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
				$ioradio_r_h = $detailsData["ior_".$rtype];
			}
			if(!$ioradio_r_h){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit;
			}
            if($wtype!='M' || !in_array($rtype,array('MH','MC'))){
                echo attention("非法操作,请重新下注!",$uid,$langx);
                exit();
            }
		}else{
			if($type=='H') $ioradio_r_h=$row["MB_Win_Rate"];
			if($type=='C') $ioradio_r_h=$row["TG_Win_Rate"];
			if($type=='N') $ioradio_r_h=$row["M_Flat_Rate"];
		}
	}
	if($line==2){
		if($couMore>0){
			$detailsArr = json_decode($rowMore['details'],true);
			$detailsData =$detailsArr[$gid];
			if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
				$ioradio_r_h = $detailsData["ior_".$rtype];
				if($type=="H") $w_m_place=$w_m_place_tw=$w_m_place_en =$m_place=$s_m_place=$detailsData["ratio_o"];
				if($type=="C") $w_m_place=$w_m_place_tw=$w_m_place_en =$m_place=$s_m_place=$detailsData["ratio_u"];
			}
			if(!$ioradio_r_h){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit;
			}
            if($wtype!='R' || !in_array($rtype,array('RH','RC'))){
                echo attention("非法操作,请重新下注!",$uid,$langx);
                exit();
            }
		}else{
			$rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100);
			if($type=='H') $ioradio_r_h=$rate[0];
			if($type=='C') $ioradio_r_h=$rate[1];
		}
	}

	if($line==3){
		if($couMore>0){
			$detailsArr = json_decode($rowMore['details'],true);
			$detailsData =$detailsArr[$gid];
			if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
				$ioradio_r_h = $detailsData["ior_".$rtype];
				if($type=="C") $w_m_place=$w_m_place_tw=$w_m_place_en =$m_place=$s_m_place=$detailsData["ratio_o"];
				if($type=="H") $w_m_place=$w_m_place_tw=$w_m_place_en =$m_place=$s_m_place=$detailsData["ratio_u"];
			}
			if(!$ioradio_r_h){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit;
			}
            if($wtype!='OU' || !in_array($rtype,array('OUC','OUH'))){
                echo attention("非法操作,请重新下注!",$uid,$langx);
                exit();
            }
		}else{
			$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100);
			if($type=='H'){
				$ioradio_r_h=$rate[1];
				$w_m_place=$w_m_place_tw=$w_m_place_en=$m_place=$s_m_place=$row["TG_Dime"];
			}
			if($type=='C'){
				$ioradio_r_h=$rate[0];
				$w_m_place=$w_m_place_tw=$w_m_place_en=$m_place=$s_m_place=$row["MB_Dime"];
			}
		}
	}

	if($line==5){
		if($row['S_Single_Rate'] && $row['S_Double_Rate']){
				if($rtype=='ODD')  $ioradio_r_h=$row['S_Single_Rate'];
				if($rtype=='EVEN') $ioradio_r_h=$row['S_Double_Rate'];
		}
		if($couMore>0){
			$detailsArr = json_decode($rowMore['details'],true);
			$detailsData =$detailsArr[$gid];
			if($rtype=="ODD"){$rateFlag=$wtype.'O';}
			if($rtype=="EVEN"){$rateFlag=$wtype.'E';}
			if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rateFlag]>0){
				$ioradio_r_h = $detailsData["ior_".$rateFlag];
			}
            if($wtype!='EO' || !in_array($rtype,array('ODD','EVEN'))){
                echo attention("非法操作,请重新下注!",$uid,$langx);
                exit();
            }
		}
		if(!$ioradio_r_h){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit;
		}
	}

	if($line==13){
        if($couMore>0){
            if(in_array($wtype,['OUH','OUC'])){
                if($wtype=='OUH' && !in_array($rtype,array('OUHO','OUHU'))){
                    echo attention("非法操作,请重新下注!",$uid,$langx);
                    exit();
                }
                if($wtype=='OUC' && !in_array($rtype,array('OUCU','OUCO'))){
                    echo attention("非法操作,请重新下注!",$uid,$langx);
                    exit();
                }
            }else{
                echo attention("非法操作,请重新下注!",$uid,$langx);
                exit();
            }
        }
    }

	if($cou==0){//更多玩法分支
		$mysql = "select * from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`='{$idOrign}' and Open=1 and $mb_team!=''";
		$result = mysqli_query($dbCenterMasterDbLink,$mysql);
		$row=mysqli_fetch_assoc($result);
		$cou=mysqli_num_rows($result);
		if($cou==0){
			echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
			exit;
		}
	}

	//取出写入数据库的四种语言的客队名称
	$w_tg_team=filiter_team(trim($row['TG_Team']));
	$w_tg_team_tw=filiter_team(trim($row['TG_Team_tw']));
	$w_tg_team_en=filiter_team(trim($row['TG_Team_en']));

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
    $m_start=strtotime($row['M_Start']);
    $datetime=time();
//    if ($m_start-$datetime<120){
//        echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
//        exit();
//    }

	//根据下注的类型进行处理：构建成新的数据格式，准备写入数据库
	switch ($line){
	  	case 1: // 全场独赢 让球
		  	$bet_type='独赢';
			$bet_type_tw='獨贏';
			$bet_type_en="1x2";
			$caption=$Order_Basketball.$Order_1_x_2_betting_order;
			switch ($type){
			case "H": // 独赢
				$w_m_place=$w_mb_team;
				$w_m_place_tw=$w_mb_team_tw;
				$w_m_place_en=$w_mb_team_en;
				$s_m_place=$s_mb_team;
	            $w_m_rate=change_rate($open,$ioradio_r_h);
				$mtype='MH';
				break;
			case "C": // 让球
				$w_m_place=$w_tg_team;
				$w_m_place_tw=$w_tg_team_tw;
				$w_m_place_en=$w_tg_team_en;
				$s_m_place=$s_tg_team;
				$w_m_rate=change_rate($open,$ioradio_r_h);
				$mtype='MC';
				break;
			case "N":
				$w_m_place="和局";
				$w_m_place_tw="和局";
				$w_m_place_en="Flat";
				$s_m_place=$Draw;
				$w_m_rate=change_rate($open,$ioradio_r_h);
				$mtype='MN';
				break;
			}
			$turn_url="/app/member/BK_order/BK_order_m.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type."&langx=".$langx;
			$Sign="VS.";
			$grape="";
			$gwin=($w_m_rate-1)*$gold;
			$ptype='M';
			break;
		case 2:
		  	$bet_type='让球';
			$bet_type_tw="讓球";
			$bet_type_en="Handicap";
			$caption=$Order_Basketball.$Order_Handicap_betting_order;
			$rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100);
			switch ($type){
			case "H":
				$w_m_place=$w_mb_team;
				$w_m_place_tw=$w_mb_team_tw;
				$w_m_place_en=$w_mb_team_en;
				$s_m_place=$s_mb_team;
				$w_m_rate=change_rate($open,$ioradio_r_h);
				$turn_url="/app/member/BK_order/BK_order_r.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
				$mtype='RH';
				break;
			case "C":
				$w_m_place=$w_tg_team;
				$w_m_place_tw=$w_tg_team_tw;
				$w_m_place_en=$w_tg_team_en;
				$s_m_place=$s_tg_team;
				$w_m_rate=change_rate($open,$ioradio_r_h);
				$turn_url="/app/member/BK_order/BK_order_r.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
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
		case 3: // 全场大小
			$bet_type='大小';
			$bet_type_tw="大小";
			$bet_type_en="Over/Under";
			$caption=$Order_Basketball.$Order_Over_Under_betting_order;
			$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100);
			switch ($type){
				case "C":  // 全场大小 主队
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/BK_order/BK_order_ou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
					$mtype='OUH';
					break;
				case "H":   // 全场大小 客队
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/BK_order/BK_order_ou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
					$mtype='OUC';
					break;
			}


            if ($langx=="zh-cn" || $langx=="zh-tw"){
                $s_m_place= returnSportBetDx($type,$s_m_place) ;
                $w_m_place= returnSportBetDx($type,$w_m_place) ;
                $w_m_place_tw= returnSportBetDx($type,$w_m_place_tw);
            }else if ($langx=="en-us" or $langx=="th-tis"){
                $s_m_place= returnSportBetDxEn($type,$s_m_place) ;
                $w_m_place_en = returnSportBetDxEn($type,$w_m_place_en) ;
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
			$turn_url="/app/member/BK_order/BK_order_pd.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
		  	$bet_type='波胆';
			$bet_type_tw="波膽";
			$bet_type_en="Correct Score";
			$caption=$Order_Basketball.$Order_Correct_Score_betting_order;
			if($rtype!='OVH'){
				$rtype=str_replace('C','TG',str_replace('H','MB',$rtype));
				$w_m_rate=change_rate($open,$row[$rtype]);
			}else{
				$w_m_rate=change_rate($open,$row['UP5']);
			}
			if ($rtype=="OVH"){
				$s_m_place=$body_pd_up;
				$w_m_place='其它比分';
				$w_m_place_tw='其它比分';
				$w_m_place_en='Other Score';
				$Sign="VS.";
			}else{
				$M_Place="";
				$M_Sign=$rtype;
				$M_Sign=str_replace("MB","",$M_Sign);
				$M_Sign=str_replace("TG",":",$M_Sign);
				$Sign=$M_Sign."";
			}
			$grape="";
			$order='B';
			$gwin=($w_m_rate-1)*$gold;
			$ptype='PD';
			$mtype=$rtype;
			break;
		case 5: // 单双
			$turn_url="/app/member/BK_order/BK_order_t.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
			$bet_type='单双';
			$bet_type_tw="單雙";
			$bet_type_en="Odd/Even";
			$caption=$Order_Basketball.$Order_Odd_Even_betting_order;
			switch ($rtype){
				case "ODD": // 单
					$w_m_place='单';
					$w_m_place_tw='單';
					$w_m_place_en='odd';
					$s_m_place='('.$Order_Odd.')';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "EVEN": // 双
					$w_m_place='双';
					$w_m_place_tw='雙';
					$w_m_place_en='even';
					$s_m_place='('.$Order_Even.')';
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				}
			$Sign="VS.";
			$order='B';
			$gwin=($w_m_rate-1)*$gold;
			$ptype='EO';
			$mtype=$rtype;
			break;
	    case 13:
	    	$bet_type_en="Order_Ball_Score Over/Under";
	        $caption=$Order_BK.$Order_Ball_Score.$OU;
	        switch ($type){
	            case "O": // 主队大，客队大
	                if($wtype =='OUH'){ // 主队
	                	$bet_type='球队得分大小：主队 ';
	        			$bet_type_tw="球队得分大小：主队 ";

                        if(!$s_m_place){$s_m_place=$row["MB_Dime_H"];}
	                    if(!$ioradio_r_h){$ioradio_r_h=$row["MB_Dime_Rate_H"];}
                        $w_m_place= $s_m_place;
                        $w_m_place_tw= $s_m_place;
                        $w_m_place_en= $s_m_place;
                        $m_place= $s_m_place;
	                    $w_m_rate=change_rate($open,$ioradio_r_h); // 赔率
	                }else{ // 客队
	                	$bet_type='球队得分大小：客队  ';
	        			$bet_type_tw="球队得分 大小：客队 ";
                        if(!$s_m_place){$s_m_place=$row["TG_Dime_H"];}
                        if(!$ioradio_r_h){$ioradio_r_h=$row["TG_Dime_Rate_H"];}

	                	$w_m_place= $s_m_place;
	                    $w_m_place_tw= $s_m_place;
	                    $w_m_place_en= $s_m_place;
	                    $m_place= $s_m_place ;
	                    $w_m_rate=change_rate($open,$ioradio_r_h); // 赔率
	                }

//	                $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
//	                $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
//	                $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
//
//
//	                if ($langx=="zh-cn"){
//	                    $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
//	                }else if ($langx=="en-us" or $langx=="th-tis"){
//	                    $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
//	                }
	                $turn_url="/app/member/BK_order/BK_order_ouhc.php?gid=".$gid."&uid=".$uid."&type=".$type."&wtype=".$wtype."&odd_f_type=".$odd_f_type;
	                $mtype=isset($rtype)&&strlen($rtype)>0?$rtype:$wtype;
	                break;
	            case "U": // 主队小，客队小
	                if($wtype =='OUH'){ // 主队
	                	$bet_type='球队得分大小：主队';
	        			$bet_type_tw="球队得分大小：主队";
                        if(!$s_m_place){$s_m_place=$row["MB_Dime_S_H"];}
                        if(!$ioradio_r_h){$ioradio_r_h=$row["MB_Dime_Rate_S_H"];}
	                	$w_m_place= $s_m_place;
	                    $m_place= $s_m_place;
	                    $w_m_place_tw= $s_m_place;
	                    $w_m_place_en= $s_m_place;
	                    $w_m_rate=change_rate($open,$ioradio_r_h); // 赔率
	                }else{ // 客队
	                	$bet_type='球队得分大小：客队';
	        			$bet_type_tw="球队得分大小：客队";
                        if(!$s_m_place){$s_m_place=$row["TG_Dime_S_H"];}
                        if(!$ioradio_r_h){$ioradio_r_h=$row["TG_Dime_Rate_S_H"];}
	                	$w_m_place= $s_m_place;
	                    $m_place= $s_m_place;
	                    $w_m_place_tw= $s_m_place;
	                    $w_m_place_en= $s_m_place;
	                    $w_m_rate=change_rate($open,$ioradio_r_h); // 赔率
	                }

//	                $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
//	                $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
//	                $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);
//
//	                if ($langx=="zh-cn"){
//	                    $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
//	                }else if ($langx=="en-us" or $langx=="th-tis"){
//	                    $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
//	                }
	                $turn_url="/app/member/BK_order/BK_order_ouhc.php?gid=".$gid."&uid=".$uid."&type=".$type."&wtype=".$wtype."&odd_f_type=".$odd_f_type;
	                $mtype=isset($rtype)&&strlen($rtype)>0?$rtype:$wtype;
	                break;
	        }

            if ($langx=="zh-cn" || $langx=="zh-tw"){
                $s_m_place= returnSportBetDx($type,$s_m_place) ;
                $w_m_place= returnSportBetDx($type,$w_m_place) ;
                $w_m_place_tw= returnSportBetDx($type,$w_m_place_tw);
            }else if ($langx=="en-us" or $langx=="th-tis"){
                $s_m_place= returnSportBetDxEn($type,$s_m_place) ;
                $w_m_place_en = returnSportBetDxEn($type,$w_m_place_en) ;
            }

	        if($wtype =='OUH'){
	        	$w_m_place=$s_mb_team.' '.$w_m_place;
	        	$w_m_place_tw=$s_mb_team.' '.$w_m_place_tw;
	        	$w_m_place_en=$s_mb_team.' '.$w_m_place_en;
	        }else{
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
	    case 31: //球队得分最后一位数
	    	$turn_url="/app/member/BK_order/BK_order_pd.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
	    	$hcFlag = substr($rtype,2,1);
		  	if($hcFlag=="H"){
		  			$team=$w_mb_team;
					$team_tw=$w_mb_team_tw;
					$team_en=$w_mb_team_en;
					$mtype='PDH';
					$ptype='PDH';
		  	}elseif($hcFlag=="C"){
		  			$team=$w_tg_team;
					$team_tw=$w_tg_team_tw;
					$team_en=$w_tg_team_en;
					$mtype='PDC';
					$ptype='PDC';
		  	}

	    	$bet_type='球队得分'.":".$team."-".$U_91;
			$bet_type_tw='球队得分'.":".$team_tw."-".$U_91;
			$bet_type_en="球队得分".":".$team_en."-".$U_91;
			$caption=$Order_Basketball.$Order_Ball_Score.":".$team."-".$U_91;
			$mtype=$rtype;
			switch ($rtype){
				case "PDH0":
				case "PDC0":
					$w_m_place="0 或 5";
					$w_m_place_tw="0 或 5";
					$w_m_place_en="0 or 5";
					$s_m_place="0 或 5";
		            $w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "PDH1":
				case "PDC1":
					$w_m_place="1 或 6";
					$w_m_place_tw="1或 6";
					$w_m_place_en="1 or 6";
					$s_m_place="1 或 6";
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "PDH2":
				case "PDC2":
					$w_m_place="2 或 7";
					$w_m_place_tw="2 或 7";
					$w_m_place_en="2 or 7";
					$s_m_place="2 或 7";
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "PDH3":
				case "PDC3":
					$w_m_place="3 或 8";
					$w_m_place_tw="3 或 8";
					$w_m_place_en="3 or 8";
					$s_m_place="3 或 8";
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "PDH4":
				case "PDC4":
					$w_m_place="4 或 9";
					$w_m_place_tw="4 或 9";
					$w_m_place_en="4 or 9";
					$s_m_place="4 或 9";
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				}
				$Sign="VS.";
				$grape="";
				if($w_m_rate>1){
					$gwin=($w_m_rate-1)*$gold;
				}else{
					$gwin=($w_m_rate)*$gold;
				}
				break;
	}

    if ($gold<10){
        echo attention("金额最低不能小于10元~~",$uid,$langx);
        exit;
    }

    if ($w_m_rate!=change_rate($open,$ioradio_r_h)){
	    $turn_url=$turn_url.'&error_flag=1';
	    echo "<script language='javascript'>self.location='$turn_url';</script>";
	    exit();
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

    if(in_array($line,array(2)) && trim($grape) == "" ){
        echo attention("让球参数异常,请刷新赛事!",$uid,$langx);
        exit();
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
	$s_m_place=filiter_team(trim($s_m_place));

	$w_mid="<br>[".$row['MB_MID']."]vs[".$row['TG_MID']."]<br>";
	$lines=$row['M_League'].$w_mid.$w_mb_team."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team."<br>";
	$lines=$lines."<FONT color=#cc0000>&nbsp;&nbsp;".$w_m_place.$s_w_place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";
	$lines_tw=$row['M_League_tw'].$w_mid.$w_mb_team_tw."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_tw."<br>";
	$lines_tw=$lines_tw."<FONT color=#cc0000>&nbsp;&nbsp;".$w_m_place_tw.$s_w_place."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";
	$lines_en=$row['M_League_en'].$w_mid.$w_mb_team_en."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_en."<br>";
	$lines_en=$lines_en."<FONT color=#cc0000>&nbsp;&nbsp;".$w_m_place_en.$s_w_place_en."</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";

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

	$sql = "INSERT INTO ".DBPREFIX."web_report_data	(MID,Glost,playSource,Userid,testflag,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball) values ('$gid',$Money,1,$memid,$test_flag,'$active','$showVoucher','$line','$mtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','BK','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball')";
	$insertBet=mysqli_query($dbMasterLink,$sql);
    if($insertBet){
        $lastId=mysqli_insert_id($dbMasterLink);
        $moneyLogRes=addAccountRecords(array($memid,$memname,$test_flag,$Money,$gold*-1,$havemoney,1,1,$lastId,"BK投注$mtype"));
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
  	<p class="team"><?php echo $s_sleague?>&nbsp;<?php echo $btype?>&nbsp;<?php echo date('m-d',strtotime($row["M_Date"]))?><BR>
        <?php echo $s_mb_team?>&nbsp;&nbsp;<font color=#cc0000><?php echo $Sign?></font>&nbsp;&nbsp;
        <?php echo $s_tg_team?><br>
        <em class="bet_type_rate"><?php echo $w_m_bet_name?> &nbsp;<?php echo $s_m_place?><?php echo $s_w_place?></em>&nbsp;@&nbsp;
        <em ><strong><?php echo $w_m_rate?></strong></em>
    </p>
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
        'Order_Bet_Amount' => $Order_Bet_Amount,  // 交易金额
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
