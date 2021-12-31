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
require_once("../../../../common/sportCenterData.php");
require ("../include/define_function_list.inc.php");
require ("../include/curl_http.php");

$uid=(isset($_REQUEST['uid']) && $_REQUEST['uid'])? $_REQUEST['uid'] :$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$idOrign=$_REQUEST['id'];
$gid=$_REQUEST['gid'];
$type=$_REQUEST['type'];
$wtype=$_REQUEST['wtype'];
$rtype=$_REQUEST['rtype'];
$gnum=$_REQUEST['gnum'];
$strong=$_REQUEST['strong'];
$odd_f_type=$_REQUEST['odd_f_type'];
$gold=$_REQUEST['gold'];
$active=2; // 强制篮球滚球     //1 足球滚球、今日赛事, 11 足球早餐\r\n2 篮球滚球、今日赛事, 22 篮球早餐
$line=$_REQUEST['line_type'];
$restcredit=$_REQUEST['restcredit'];
require ("../include/traditional.$langx.inc.php");

if ($odd_f_type=='E'){
	$r_num=1;
}else{
	$r_num=0;
}

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
$memid= $_SESSION['userid'];
$test_flag=$_SESSION['test_flag'];

//------------------------------------------------------------------------------------
$allcount=0;
if($flushWay == 'ra') { //正网
    $accoutArr = getFlushWaterAccount();
}
$accoutArrNum = count($accoutArr);
$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
	$allcount = $allcount + 1;
	$site=$value['Datasite'];
	$suid=$value['Uid'];

    $curl->set_referrer("".$site."");
    $postdata = array(
        'p' => 'Other_order_view',
        'ver' => date('Y-m-d-H').$value['Ver'],
        'langx' => $langx,
        'uid' => $value['Uid'],
        'odd_f_type' => $odd_f_type,
        'gid' => $gid,
        'gtype' => 'BK',
        'wtype' => $wtype,
    );
	switch ($line){
		case '10':
            $postdata['wtype']='ROU';  // 大小
            $postdata['chose_team']=$type;
//			$html_data=$curl->fetch_url("".$site."/app/member/BK_order/BK_order_rou.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type&langx=$langx");
            $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
			break;
		case '9':
            $postdata['wtype']='RE';
            $postdata['chose_team']=$type;  // 让球
//			$html_data=$curl->fetch_url("".$site."/app/member/BK_order/BK_order_re.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&strong=$strong&odd_f_type=$odd_f_type&langx=$langx");
            $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
			break;
		case '131':
            $postdata['chose_team']=$rtype; //球队得分最后一位数
//            $html_data=$curl->fetch_url("".$site."/app/member/BK_order/BK_order_rpd.php?gid=$gid&uid=$suid&rtype=$rtype&langx=zh-cn&odd_f_type=$odd_f_type&langx=$langx");
            $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
			break;
		case '105':
            $postdata['chose_team']='R'.$rtype;//总分单双
            if ($rtype=='ODD' or $rtype=='EVEN') $postdata['wtype']='RT';
//			$html_data=$curl->fetch_url("".$site."/app/member/BK_order/BK_order_rt.php?gid=$gid&uid=$suid&rtype=$rtype&odd_f_type=$odd_f_type&langx=$langx");
            $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
			break;
	}
//	$msg_c=explode("@",$html_data);
    $aData = xmlToArray($xml_data);
//	if(sizeof($msg_c)>1){
    if($aData['ioratio']>0){
		break;
	}elseif($allcount==$accoutArrNum){
        // 判断刷水域名是否被踢出
        /*if(strpos($html_data ,'logout_warn') !== false){
            checkAccountExpand($html_data , $value['Name']);
        }

        // 判断是否存在跳转域名，刷水域名及时更新为新的域名
        if (strpos($html_data, 'newdomain') !==false){
            $aNewdomain = getNewdomain();
            $sql = "update ".DBPREFIX."web_getdata_account_expand set Datasite='".$aNewdomain['url']."' where Uid='".$aNewdomain['uid']."'";
            $update=mysqli_query($dbMasterLink,$sql);
            if($update){
            }else{
                die("update datesite fail");
            }
        }*/
		echo attention("$Order_Odd_changed_please_game_again",$uid,$langx);
		exit();
	}
}
//------------------------------------------------------------------------------------

// -------------------------------------------------------更多玩法刷水 Start
$redisObj = new Ciredis();
if($_REQUEST['id']&&$_REQUEST['id']>0) {
    $gtype = 'BK';
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
                                $details = json_encode($tmp_Obj,JSON_UNESCAPED_UNICODE);
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

$mysqlL = "select `MID` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
$resultL = mysqli_query($dbLink,$mysqlL);
$couL=mysqli_num_rows($resultL);
if($couL==0) {
    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
    exit();
}

$mysql = "select M_Start,MB_Team,TG_Team,MB_Team_tw,TG_Team_tw,MB_Team_en,TG_Team_en,M_Date,ShowTypeRB,M_League,M_League_tw,M_League_en,MB_Ball,TG_Ball,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,MB_Dime_Rate_RB,TG_Dime_Rate_RB,MB_Dime_Rate_RB_H,TG_Dime_Rate_RB_H,MB_Dime_RB,TG_Dime_RB,MB_Dime_RB_H,TG_Dime_RB_H,MB_Dime_RB_S_H,MB_Dime_Rate_RB_S_H,TG_Dime_RB_S_H,TG_Dime_Rate_RB_S_H,S_Single_Rate,S_Double_Rate,MB_LetB_Rate_RB,TG_LetB_Rate_RB,M_LetB_RB,MB_MID,TG_MID,M_Duration from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and `MID`='$gid' and Open=1 and MB_Team!=''";
$result = mysqli_query($dbCenterMasterDbLink,$mysql);

$row = mysqli_fetch_assoc($result);
if (strpos($row['M_League'],'(4x5分钟)')){ //电竞篮球联赛
    $BkTypeTime ='300';  //1-4每小节5分钟*60=300s
}elseif (strpos($row['M_League'],'美国职业篮球联赛')){ //NBA美国职业篮球联赛
    $BkTypeTime ='720';  //每小节12分钟  NBA比赛一节12分钟
}else {
    $BkTypeTime ='600';  //每小节10分钟
}

// 当前投注的注单是否第四节需要判断此篮球联赛的同队伍的全部盘口，nowSession 只要有1个Q4，则禁止投注
$mleague = $row['M_League'];
//$mbteam = explode('<font color=gray>',$row['MB_Team'])[0];
//$tgteam = explode('<font color=gray>',$row['TG_Team'])[0];
$mbteam = trim(explode('-',$row['MB_Team'])[0]);
$tgteam = trim(explode('-',$row['TG_Team'])[0]);
$mdate = $row['M_Date'];
$result = mysqli_query($dbCenterMasterDbLink,"select M_Date,MID,M_Duration,nowSession,MB_Team,TG_Team,M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and Checked=0 and Open=1 and M_League ='$mleague' and MB_Team like '$mbteam%' and TG_Team like '$tgteam%' and M_Date = '$mdate' ");
while ($row_q4 = mysqli_fetch_assoc($result)){
    if ($row_q4["nowSession"]=='Q4'||$row_q4["nowSession"]=='OT'){
        echo attention("$Order_Running_Ball_is_temporary_not_accepted_wagering",$uid,$langx);
        exit();
    }

    // 0086需要 TPL_FILE_NAME == '0086'
    if(in_array(TPL_FILE_NAME, TPL_FILE_NAMES)) {
        $aM_Duration = explode('-', $row_q4["M_Duration"]);
        // 如果是滚球第一节，则禁止投注第一节
        if ($row_q4["nowSession"] == 'Q1' and strpos($row['MB_Team'],'(第一节)')!==false and $aM_Duration[1]<$BkTypeTime and $gid == ($row_q4['MID'] + 3)) {
            echo attention("$Order_Running_Ball_is_temporary_not_accepted_wagering", $uid, $langx);
            exit();
        }
        // 如果是滚球第二节，则禁止投注上半场
        if ($row_q4["nowSession"] == 'Q2' and (strpos($row['MB_Team'],'(上半场)')!==false) || (strpos($row['MB_Team'],'(第二节)')!==false) and $aM_Duration[1]<$BkTypeTime and ($gid == ($row_q4['MID'] + 1) || $gid == ($row_q4['MID'] + 4))) {
            echo attention("$Order_Running_Ball_is_temporary_not_accepted_wagering", $uid, $langx);
            exit();
        }
        // 如果是滚球第三节，则禁止投注第三节
        if ($row_q4["nowSession"] == 'Q3' and strpos($row['MB_Team'],'(第三节)')!==false and $aM_Duration[1]<$BkTypeTime and $gid == ($row_q4['MID'] + 5)) {
            echo attention("$Order_Running_Ball_is_temporary_not_accepted_wagering", $uid, $langx);
            exit();
        }
    }
}

$cou=mysqli_num_rows($result);
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
}

	$detailsData=array();
	$moreMethod = array(131);
	if($_REQUEST['id']&&$_REQUEST['id']>0){
		array_push($moreMethod,$line);
	}
	if(in_array($line,$moreMethod)){
			$detailsArr = json_decode($rowMore['details'],true);
			$detailsData =$detailsArr[$gid];
            if($wtype=="RPD"){ $detailsData['sw_'.$wtype]="Y"; }
			if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
				$ioradio_r_h = $detailsData["ior_".$rtype];
				if(!$ioradio_r_h){
					echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
					exit();	
				}
			}
            if($line==131 && $couMore>0){
                if($wtype!='RPD' || !in_array($rtype,array('RPDH0','RPDH1','RPDH2','RPDH3','RPDH4','RPDC0','RPDC1','RPDC2','RPDC3','RPDC4'))){
                    echo attention("非法操作,请重新下注!",$uid,$langx);
                    exit();
                }
            }
        //更多玩法注入效验
        if(!gameBkVerify($line,$wtype,$rtype)){
            echo attention("非法操作~,请重新下注!",$uid,$langx);
            exit();
        }
	}
	
	if($line==105){
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
            if($wtype!='REO' || !in_array($rtype,array('ODD','EVEN'))){
                echo attention("非法操作,请重新下注!",$uid,$langx);
                exit();
            }
		}
	}

    if( $line==9 && $couMore>0 ){
        if($wtype != 'RE' && !in_array($rtype,array('REH','REC'))){
            echo attention("非法操作,请重新下注!",$uid,$langx);
            exit();
        }
    }
	
	if((isset($row['MB_Ball'])&&$row['MB_Ball']>0) || (isset($row['TG_Ball'])&&$row['TG_Ball']>0)){
		$inball=$row['MB_Ball'].":".$row['TG_Ball'];
		$inball1=$inball;
		$mb_ball = $row['MB_Ball'];
		$tg_ball = $row['TG_Ball'];	
	}else{
	    // 获取篮球时时比分，每场比赛大部分都有9个盘口，只有第一个有实时比分
        // 根据主队名称、客队名称去查出实时比分
        $row_MB_Team = explode(' - ',$row['MB_Team'])[0];
        $row_TG_Team = explode(' - ',$row['TG_Team'])[0];
		$mysqlBall = "select MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and Open=1 and MB_Team='".$row_MB_Team."' and TG_Team='".$row_TG_Team."' and M_League='".$row['M_League']."' and M_Start='".$row['M_Start']."' limit 1";
        $resultBall = mysqli_query($dbCenterMasterDbLink,$mysqlBall);
		$rowBall = mysqli_fetch_assoc($resultBall);
		$inball=$rowBall['MB_Ball'].":".$rowBall['TG_Ball'];
		$inball1=$inball;
		$mb_ball = $rowBall['MB_Ball'];
		$tg_ball = $rowBall['TG_Ball'];	
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
//	    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
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
		case 9:
	 		$bet_type='滚球让球';
			$bet_type_tw="滾球讓球";
			$bet_type_en="Running Ball";
			$caption=$Order_Basketball.$Order_Running_Ball_betting_order;
			$rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RB"],$row["TG_LetB_Rate_RB"],100);
			if ($rate[0]-$r_num>1.5 or $rate[1]-$r_num>1.5){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit();
			}
			switch ($type){
				case "H":
					$w_m_place=$w_mb_team;
					$w_m_place_tw=$w_mb_team_tw;
					$w_m_place_en=$w_mb_team_en;
					$s_m_place=$s_mb_team;
					if(!$ioradio_r_h){$ioradio_r_h=$rate[0];}
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/BK_order/BK_order_re.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
					$w_gtype='RRH';
					break;
				case "C":
					$w_m_place=$w_tg_team;
					$w_m_place_tw=$w_tg_team_tw;
					$w_m_place_en=$w_tg_team_en;
					$s_m_place=$s_tg_team;
					if(!$ioradio_r_h){$ioradio_r_h=$rate[1];}
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/BK_order/BK_order_re.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
					$w_gtype='RRC';
					break;
				}
				$Sign=$row['M_LetB_RB'];
                if ($Sign=='' || $Sign=='-1-100'){
                    echo attention("让球参数异常，请刷新赛事~~",$uid,$langx);
                    exit();
                }
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
                    if((isset($row['MB_Ball'])&&$row['MB_Ball']>0) || (isset($row['TG_Ball'])&&$row['TG_Ball']>0)){
                        $inball=$row['MB_Ball'].":".$row['TG_Ball'];
                    }else{
                        $inball=$rowBall['MB_Ball'].":".$rowBall['TG_Ball'];
                    }
				}else{
					$r_team=$s_mb_team;
					$l_team=$s_tg_team;
					$w_r_team=$w_mb_team;
					$w_r_team_tw=$w_mb_team_tw;
					$w_r_team_en=$w_mb_team_en;
					$w_l_team=$w_tg_team;
					$w_l_team_tw=$w_tg_team_tw;
					$w_l_team_en=$w_tg_team_en;
                    if((isset($row['MB_Ball'])&&$row['MB_Ball']>0) || (isset($row['TG_Ball'])&&$row['TG_Ball']>0)){
                        $inball=$row['TG_Ball'].":".$row['MB_Ball'];
                    }else{
                        $inball=$rowBall['TG_Ball'].":".$rowBall['MB_Ball'];
                    }
					
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
				$w_wtype='R';				
				break;
		case 10:	
			$bet_type='滚球大小';
			$bet_type_tw="滾球大小";
			$bet_type_en="Running Over/Under";
			$caption=$Order_Basketball.$Order_Running_Ball_Over_Under_betting_order;
			$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB"],$row["TG_Dime_Rate_RB"],100);
			if ($rate[0]-$r_num>1.5 or $rate[1]-$r_num>1.5){
				echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
				exit();
			}
			switch ($type){
				case "C":
					$ioradio_r_h=$rate[0];
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
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/BK_order/BK_order_rou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
					$w_gtype='ROUH';
					break;
				case "H":
					$ioradio_r_h=$rate[1];
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
					$w_m_rate=change_rate($open,$ioradio_r_h);
					$turn_url="/app/member/BK_order/BK_order_rou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
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
				$w_wtype='R';				
				break;
		case 131: //球队得分最后一位数
			$turn_url="/app/member/BK_order/BK_order_rpd.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
			$hcFlag = substr($rtype,3,1);
		  	if($hcFlag=="H"){
		  			$team=$w_mb_team;
					$team_tw=$w_mb_team_tw;
					$team_en=$w_mb_team_en;
					$mtype='RPDH';
					$ptype='RPDH';
		  	}elseif($hcFlag=="C"){
		  			$team=$w_tg_team;
					$team_tw=$w_tg_team_tw;
					$team_en=$w_tg_team_en;
					$mtype='RPDC';
					$ptype='RPDC';
		  	}
		  	
	    	$bet_type='球队得分'.":".$team."-".$U_91;
			$bet_type_tw='球队得分'.":".$team_tw."-".$U_91;
			$bet_type_en="球队得分".":".$team_en."-".$U_91;
			$caption=$Order_Basketball.'('.$Running_Ball.') '.$Order_Ball_Score.":".$team."-".$U_91;
			$w_gtype=$rtype;
			switch ($rtype){
				case "RPDH0":
				case "RPDC0": 
					$w_m_place="0 或 5";
					$w_m_place_tw="0 或 5";
					$w_m_place_en="0 or 5";
					$s_m_place="0 或 5";
		            $w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "RPDH1":
				case "RPDC1":  
					$w_m_place="1 或 6";
					$w_m_place_tw="1或 6";
					$w_m_place_en="1 or 6";
					$s_m_place="1 或 6";
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "RPDH2":
				case "RPDC2": 
					$w_m_place="2 或 7";
					$w_m_place_tw="2 或 7";
					$w_m_place_en="2 or 7";
					$s_m_place="2 或 7";
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "RPDH3":
				case "RPDC3": 
					$w_m_place="3 或 8";
					$w_m_place_tw="3 或 8";
					$w_m_place_en="3 or 8";
					$s_m_place="3 或 8";
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				case "RPDH4":
				case "RPDC4": 
					$w_m_place="4 或 9";
					$w_m_place_tw="4 或 9";
					$w_m_place_en="4 or 9";
					$s_m_place="4 或 9";
					$w_m_rate=change_rate($open,$ioradio_r_h);
					break;
				}
				$Sign="VS.";
				if($w_m_rate>1){
					$gwin=($w_m_rate-1)*$gold;
				}else{
					$gwin=($w_m_rate)*$gold;	
				}
				break;
		case 105: // 单双
			$turn_url="/app/member/BK_order/BK_order_rt.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
			$bet_type='滚球单双';
			$bet_type_tw="滚球單雙";
			$bet_type_en="Odd/Even";
			$caption=$Order_Basketball.'('.$Running_Ball.') '.$Order_Odd_Even_betting_order;
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
			$ptype='REO';	
			$w_gtype='R'.$rtype;
			break;
	}
	
	if ($gold<10){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}
	
	if ($w_m_rate=='' || $w_m_rate==0){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}
	
	if( $grape=='' && in_array($line,array(10))){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}
	
	if ($w_m_rate!=change_rate($open,$ioradio_r_h)){
		$turn_url=$turn_url.'&error_flag=1';
		echo "<script language='javascript'>self.location='$turn_url';</script>";
		exit;
	}	
	
	if ($s_m_place=='' or $w_m_place==''){
		echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
		exit;
	}

    if(in_array($line,array(9)) && trim($grape) == "" ){
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
		if($havemoney<0 || $gold<=0 || $HMoney<=0){
			mysqli_query($dbMasterLink,"ROLLBACK");
			echo attention("$User_insufficient_balance",$uid,$langx);
			exit();
		}
		$sql = "INSERT INTO ".DBPREFIX."web_report_data	(danger,Glost,playSource,MID,Userid,testflag,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball,betid) values (0,$Money,1,'$gid',$memid,$test_flag,'$active','$showVoucher','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','BK','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball','$betid')";
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
<!--<script>window.setTimeout("self.location='../select.php?uid=<?php/*=$uid*/?>'", 45000);</script>-->
</head>
<body id="OFIN" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
  <div class="ord">
    <span><h1><?php echo $caption?></h1></span>
      <div id="info">
       <!--<p><?php/*=$Order_Login_Name*/?><?php/*=$memname*/?></p>-->
       <!--<p class="mem-can-use"><?php/*=$Order_Credit_line*/?><?php/*=$havemoney*/?></p>-->
       <div class="fin_title"><p class="fin_acc">成功提交注单！</p><p class="p-underline"><?php echo $Order_Bet_success?>&nbsp;<?php echo $showVoucher;?></p><p class="error">危险球 - 待确认</p></div>
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
