<?php
/**
 * 选择玩法和赔率，准备投注接口
 * order/order_prepare_api.php
 *
 * @param  order_method FT_rm 滚球独赢，FT_re 滚球让球，FT_rou 滚球大小，FT_rt 滚球单双，FT_hrm 滚球半场独赢，FT_hre 滚球半场让球，FT_hrou 滚球半场大小，FT_m 独赢，FT_r 让球，FT_ou 大小，FT_t 单双、单双 - 上半场、总进球数、总进球数-上半场，FT_hm 半场独赢，FT_hr 半场让球，FT_hou 半场大小，BK_re 滚球让球，BK_rou 滚球大小，BK_m 独赢，BK_r 让球，BK_ou 大小，BK_t 单双，BK_ouhc 球队得分大小
 * @param  gid
 * @param  type  H 主队 C 客队  N 和
 * @param  wtype  M 独赢，R 让球，大小 OU，单双 EO，半场独赢 HM，半场让球 HR，半场大小 HOU
 * @param  rtype  ODD 单 EVEN 双
 * @param  odd_f_type  H
 * @param  error_flag
 * @param  order_type
 * @param  flag  all 所有玩法
 */

//include "../include/address.mem.php";
//include_once('../include/config.inc.php');
//require_once("../../../common/sportCenterData.php");
//require ("../include/define_function_list.inc.php");
//require ("../include/curl_http.php");
ob_clean();
$order_method=$_REQUEST['order_method'];
$gid=trim($_REQUEST['gid']);
$gid_fs=trim($_REQUEST['gid_fs']);
//$_REQUEST['id']=$_REQUEST['gid'];
$gnum=$_REQUEST['gnum']; // 投注的队伍ID
$type=$_REQUEST['type'];
$wtype=$_REQUEST['wtype'];
$rtype=$_REQUEST['rtype'];
$odd_f_type=$_REQUEST['odd_f_type'];
$error_flag=$_REQUEST['error_flag'];
$order_type=$_REQUEST['order_type'];
$appRefer=$_REQUEST['appRefer']; // 13 ios, 14 安卓
$flag=$_REQUEST['flag'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status='401.1';
    $describe="请重新登录";
    original_phone_request_response($status,$describe);
}

if ($error_flag==1){
    $status='401.2';
    $describe=$Order_Odd_changed_please_bet_again;
    original_phone_request_response($status,$describe);
}

$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
//require ("../include/traditional.$langx.inc.php");
$memname=$_SESSION['UserName'];
$open=$_SESSION['OpenType'];

$btset=singleset('M');
$GMIN_SINGLE=$btset[0];

if($flag=='all'){ // 为了兼容APP，APP 没有传所有玩法标识 || $appRefer==13 || $appRefer==14
    $_REQUEST['id']=$gid; // 所有玩法
    $dataSou="interface";
}

if($order_method!="FT_nfs"){
    $mysqlL = "select `MID` from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Cancel!=1 and Open=1 and $mb_team!=''";
    $resultL = mysqli_query($dbLink, $mysqlL);
    $couL = mysqli_num_rows($resultL);
    if($couL==0) {
        $status = '401.99';
        $describe = $Order_This_match_is_closed_Please_try_again;
        original_phone_request_response($status, $describe);
    }
}

if ($flushWay!='ra' and $_REQUEST['flag'] == "all"){
    $_REQUEST['isMaster']='Y';
}

switch ($order_method) {
    // -----------------------------------------  足球滚球Start
    case 'FT_rm': // 独赢

        $GSINGLE_CREDIT = FT_M_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate_RB,TG_Win_Rate_RB,M_Flat_Rate_RB,MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterSlaveDbLink, $mysql);

        $row = mysqli_fetch_assoc($result);
        $cou = mysqli_num_rows($result);

        if ($cou == 0) {
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        } else {
            $M_League = $row['M_League'];
            $MB_Team = $row["MB_Team"];
            $TG_Team = $row["TG_Team"];
            $MB_Team = filiter_team($MB_Team);
            $MB_Ball = $row['MB_Ball'];
            $TG_Ball = $row['TG_Ball'];

            if ($_REQUEST['flag'] == "all" and $_REQUEST['isMaster']!='N') { // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink, "select details from " . DBPREFIX . "match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'], true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if ($detailsData['sw_' . $wtype] == "Y" && $detailsData["ior_" . $rtype] > 0) {
                    $ior_Rate = $detailsData["ior_" . $rtype];
                }
                if (!$ior_Rate) {
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            switch ($type) {
                case "H":
                    $M_Place = $MB_Team;
                    if (!isset($ior_Rate)) $ior_Rate = $row["MB_Win_Rate_RB"];
                    $M_Rate = change_rate($open, $ior_Rate);
                    break;
                case "C":
                    $M_Place = $TG_Team;
                    if (!isset($ior_Rate)) $ior_Rate = $row["TG_Win_Rate_RB"];
                    $M_Rate = change_rate($open, $ior_Rate);
                    break;
                case "N":
                    $M_Place = $Draw;
                    if (!isset($ior_Rate)) $ior_Rate = $row["M_Flat_Rate_RB"];
                    $M_Rate = change_rate($open, $ior_Rate);
                    break;
            }

            if ($M_Rate == 0 || $M_Rate == '') {
                $status = '401.5';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);
            }
            $gametype = $U_37;
            $line_type=21;
        }
        break;
    case 'FT_re': // 让球

        $GSINGLE_CREDIT = FT_RE_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeRB,M_LetB_RB,MB_LetB_Rate_RB,TG_LetB_Rate_RB,MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterSlaveDbLink, $mysql);
        $row = mysqli_fetch_assoc($result);
        $cou = mysqli_num_rows($result);
        if ($cou == 0) {
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        } else {

            if ($_REQUEST['flag'] == "all" and $_REQUEST['isMaster']!='N') { // 所有玩法判断
                $moreRes = mysqli_query($dbLink, "select details from " . DBPREFIX . "match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'], true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if ($detailsData['sw_' . $wtype] == "Y" && $detailsData["ior_" . $rtype] > 0) {
                    $ior_Rate = $detailsData["ior_" . $rtype];
                    $Sign=$detailsData["ratio_re"];
                    $row['ShowTypeRB']=$detailsData["strong"];
                }
                if (!$ior_Rate) {
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League = $row['M_League'];
            $MB_Team = $row["MB_Team"];
            $TG_Team = $row["TG_Team"];
            $MB_Team = filiter_team($MB_Team);
            if(!isset($Sign))$Sign = $row['M_LetB_RB'];
            $rate = get_other_ioratio($odd_f_type, $row["MB_LetB_Rate_RB"], $row["TG_LetB_Rate_RB"], 100);
            switch ($type) {
                case "H":
                    $M_Place = $MB_Team;
                    if ($flushWay=='ra'){
                        if (!isset($ior_Rate)) $ior_Rate = $rate[0];
                        $M_Rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!isset($ior_Rate)){ $M_Rate = round_num($row["MB_LetB_Rate_RB"]); }else{ $M_Rate = round_num($ior_Rate); }
                    }
                    $mtype = 'RRH';
                    break;
                case "C":
                    $M_Place = $TG_Team;
                    if ($flushWay=='ra'){
                        if (!isset($ior_Rate)) $ior_Rate = $rate[1];
                        $M_Rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!isset($ior_Rate)){ $M_Rate = round_num($row["TG_LetB_Rate_RB"]); }else{ $M_Rate = round_num($ior_Rate); }
                    }
                    $mtype = 'RRC';
                    break;
            }
            $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
            if ($row['ShowTypeRB'] == 'C') {
                $inball = $row['TG_Ball'] . ":" . $row['MB_Ball'];
                $Team = $MB_Team;
                $MB_Team = $TG_Team;
                $TG_Team = $Team;
            }
            $gametype = $U_35;
            $line_type=9;
        }

        break;
    case 'FT_rou': // 大小

        $GSINGLE_CREDIT=FT_ROU_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime_RB,TG_Dime_RB,MB_Dime_Rate_RB,TG_Dime_Rate_RB,MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }else {

            if ($_REQUEST['flag'] == "all" and $_REQUEST['isMaster']!='N') { // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink, "select details from " . DBPREFIX . "match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'], true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if ($detailsData['sw_' . $wtype] == "Y" && $detailsData["ior_" . $rtype] > 0) {
                    $ior_Rate = $detailsData["ior_" . $rtype];
                    switch ($type){
                        case "C": $M_Place='O '.$detailsData["ratio_rouo"]; break;
                        case "H": $M_Place='U '.$detailsData["ratio_rouu"]; break;
                    }
                }
                if (!$ior_Rate) {
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League = $row['M_League'];
            $MB_Team = $row["MB_Team"];
            $TG_Team = $row["TG_Team"];
            $MB_Team = filiter_team($MB_Team);
            $rate = get_other_ioratio($odd_f_type, $row["MB_Dime_Rate_RB"], $row["TG_Dime_Rate_RB"], 100);
            switch ($type) {
                case "C":
                    if(!isset($M_Place))$M_Place = $row["MB_Dime_RB"];
                    if ($langx == "zh-cn") {
                        $M_Place = str_replace('O', '大 ', $M_Place);
                    } else if ($langx == "zh-cn") {
                        $M_Place = str_replace('O', '大 ', $M_Place);
                    } else if ($langx == "en-us" or $langx == "th-tis") {
                        $M_Place = str_replace('O', 'over ', $M_Place);
                    }
                    if ($flushWay=='ra'){
                        if (!isset($ior_Rate)) $ior_Rate = $rate[0];
                        $M_Rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!$ior_Rate) {
                            $ior_Rate = $row["MB_Dime_Rate_RB"];
                        }
                        $M_Rate = round_num($ior_Rate);
                    }
                    $mtype = 'ROUH';
                    break;
                case "H":
                    if(!isset($M_Place))$M_Place = $row["TG_Dime_RB"];
                    if ($langx == "zh-cn") {
                        $M_Place = str_replace('U', '小 ', $M_Place);
                    } else if ($langx == "zh-cn") {
                        $M_Place = str_replace('U', '小 ', $M_Place);
                    } else if ($langx == "en-us" or $langx == "th-tis") {
                        $M_Place = str_replace('U', 'under ', $M_Place);
                    }
                    if ($flushWay=='ra'){
                        if (!isset($ior_Rate)) $ior_Rate = $rate[1];
                        $M_Rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!$ior_Rate) {
                            $ior_Rate = $row["TG_Dime_Rate_RB"];
                        }
                        $M_Rate = round_num($ior_Rate);
                    }
                    $mtype = 'ROUC';
                    break;
            }
            $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
            if ($row['ShowTypeR'] == 'C') {
                $inball = $row['TG_Ball'] . ":" . $row['MB_Ball'];
                $Team = $MB_Team;
                $MB_Team = $TG_Team;
                $TG_Team = $Team;
            }

            if ($M_Rate == 0 or $M_Rate == '' or $M_Place == '' or $M_Place == 'O0' or $M_Place == 'U0') {
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_36;
            $line_type=10;
        }
        break;
    case 'FT_rt': // 单双 、总进球数
        $GSINGLE_CREDIT=FT_T_Bet;

        if($gid%2==0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate_RB,S_Double_Rate_RB from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`='$gid' and Open=1 and $mb_team!=''";
        }elseif($gid%2==1){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate_RB,S_Double_Rate_RB from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";
        }
        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        else {
            if ($_REQUEST['id'] && $_REQUEST['id'] > 0 and $_REQUEST['isMaster']!='N') {
                $moreRes = mysqli_query($dbLink, "select details from " . DBPREFIX . "match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'], true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                switch ($rtype) {
                    case 'R0~1':
                        $keyY = "sw_RT";
                        $iorK = "ior_RT01";
                        break;//全场
                    case 'R2~3':
                        $keyY = "sw_RT";
                        $iorK = "ior_RT23";
                        break;
                    case 'R4~6':
                        $keyY = "sw_RT";
                        $iorK = "ior_RT46";
                        break;
                    case 'ROVER':
                        $keyY = "sw_RT";
                        $iorK = "ior_ROVER";
                        break;
                    case 'HRT0':
                        $keyY = "sw_HRT";
                        $iorK = "ior_HRT0";
                        break;//半场
                    case 'HRT1':
                        $keyY = "sw_HRT";
                        $iorK = "ior_HRT1";
                        break;
                    case 'HRT2':
                        $keyY = "sw_HRT";
                        $iorK = "ior_HRT2";
                        break;
                    case 'HRTOV':
                        $keyY = "sw_HRT";
                        $iorK = "ior_HRTOV";
                        break;
                    case 'RODD':
                        $keyY = "sw_REO";
                        $iorK = "ior_REOO";
                        break;
                    case 'REVEN':
                        $keyY = "sw_REO";
                        $iorK = "ior_REOE";
                        break;
                    case 'HRODD':
                        $keyY = "sw_HREO";
                        $iorK = "ior_HREOO";
                        break;
                    case 'HREVEN':
                        $keyY = "sw_HREO";
                        $iorK = "ior_HREOE";
                        break;
                    default:
                        $keyY = 'sw_' . $wtype;
                        break;
                }
                if ($detailsData[$keyY] == "Y" && $detailsData[$iorK] > 0) {
                    $ioradio_r_h = $detailsData[$iorK];
                    if (!$ioradio_r_h) {
                        $status = '401.4';
                        $describe = $Order_This_match_is_closed_Please_try_again;
                        original_phone_request_response($status, $describe);
                    }
                }
            } else {
                if ($rtype == "RODD" || $rtype == "REVEN") {
                    if ($rtype == "RODD") $ioradio_r_h = $row['S_Single_Rate_RB'];
                    if ($rtype == "REVEN") $ioradio_r_h = $row['S_Double_Rate_RB'];
                    if (!$ioradio_r_h) {
                        $status = '401.5';
                        $describe = $Order_This_match_is_closed_Please_try_again;
                        original_phone_request_response($status, $describe);
                    }
                } else {
                    $rbExpandRes = mysqli_query($dbLink, "select RS_0_1 AS 'R0~1',RS_2_3 AS 'R2~3',RS_4_6 AS 'R4~6',RS_7UP AS 'ROVER' from " . DATAHGPREFIX . "match_sports_rb_expand where `MID`='$gid'");
                    $rowExpandRes = mysqli_fetch_assoc($rbExpandRes);
                    $couExpandRes = mysqli_num_rows($rbExpandRes);
                    $ioradio_r_h = $rowExpandRes[$rtype];
                    if ($couExpandRes == 0 || !$ioradio_r_h) {
                        $status = '401.6';
                        $describe = $Order_This_match_is_closed_Please_try_again;
                        original_phone_request_response($status, $describe);
                    }
                }
            }

            $M_League = $row['M_League'];
            $MB_Team = $row["MB_Team"];
            $TG_Team = $row["TG_Team"];
            $MB_Team = filiter_team($MB_Team);
            switch ($rtype) {
                case "RODD":
                    $M_Place = "(" . $Order_Odd . ")";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE1;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT1;
                    $caption = $Order_Odd_Even_betting_order;
                    $line_type = 105;
                    break;
                case "REVEN":
                    $M_Place = "(" . $Order_Even . ")";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE1;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT1;
                    $caption = $Order_Odd_Even_betting_order;
                    $line_type = 105;
                    break;
                case "HRODD":
                    $M_Place = "(" . $Order_Odd . ")";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE1;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT1;
                    $caption = $Order_Odd_Even_betting_order;
                    $line_type = 205;
                    break;
                case "HREVEN":
                    $M_Place = "(" . $Order_Even . ")";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE1;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT1;
                    $caption = $Order_Odd_Even_betting_order;
                    $line_type = 205;
                    break;
                case "R0~1":
                    $M_Place = "R(0~1)";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 106;
                    break;
                case "R2~3":
                    $M_Place = "R(2~3)";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 106;
                    break;
                case "R4~6":
                    $M_Place = "R(4~6)";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 106;
                    break;
                case "ROVER":
                    $M_Place = "R(7UP)";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 106;
                    break;
                case "HRT0":
                    $M_Place = "0";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 206;
                    break;
                case "HRT1":
                    $M_Place = "1";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 206;
                    break;
                case "HRT2":
                    $M_Place = "2";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 206;
                    break;
                case "HRTOV":
                    $M_Place = "3或以上";
                    $M_Rate = change_rate($open, $ioradio_r_h);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 206;
                    break;
            }

            if ($rtype == 'REVEN' or $rtype == 'RODD') {
                $gametype = $U_31;
                if (substr($_REQUEST['wtype'], 0, 1) == "H") {
                    $gametype = $U_31 . "-" . $U_00;
                }
            } elseif ($rtype == 'HEVEN' or $rtype == 'HODD') {
                $gametype = $U_31;
                if (substr($_REQUEST['wtype'], 0, 1) == "H") {
                    $gametype = $U_OE . "-" . $U_00;
                }
            } elseif (substr($_REQUEST['wtype'], 0, 1) == "H") {
                $gametype = $U_41H;
            } else {
                $gametype = $U_41;
            }

            $gametype = "(" . $Running_Ball . ") " . $gametype;

            if (strlen($M_Rate) == 0 || $M_Rate == 0) {
                $status = '401.7';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
        }
        break;
    case 'FT_hrm': // 半场独赢

        $GSINGLE_CREDIT=FT_M_Bet;

        if($gid%2 == 1) $gid = $gid;
        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate_RB_H,TG_Win_Rate_RB_H,M_Flat_Rate_RB_H,MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";

        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }else {
            if($_REQUEST['flag']=="all" and $_REQUEST['isMaster']!='N'){ // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ior_Rate = $detailsData["ior_".$rtype];
                }
                if(!$ior_Rate){
                    $status = '401.3';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);
            $inball=$row['MB_Ball'].":".$row['TG_Ball'];

            switch ($type){
                case "H":
                    $M_Place=$MB_Team;
                    if(!isset($ior_Rate))$ior_Rate=$row["MB_Win_Rate_RB_H"];
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
                case "C":
                    $M_Place=$TG_Team;
                    if(!isset($ior_Rate))$ior_Rate=$row["TG_Win_Rate_RB_H"];
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
                case "N":
                    $M_Place=$Draw;
                    if(!isset($ior_Rate))$ior_Rate=$row["M_Flat_Rate_RB_H"];
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
            }

            if ($M_Rate==0 || $M_Rate==""){
                $status = '401.3';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_38;
            $line_type=31;
        }
        break;
    case 'FT_hre': // 半场让球

        $GSINGLE_CREDIT=FT_RE_Bet;
        //gid单数 所有玩法gid双数
        if($gid%2 == 1) $gid = $gid;
        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeHRB,M_LetB_RB_H,MB_LetB_Rate_RB_H,TG_LetB_Rate_RB_H,MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and mb_team!='' and mb_team_tw!=''";

        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);

        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }else{

            if($_REQUEST['flag']=="all" and $_REQUEST['isMaster']!='N'){ // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ior_Rate = $detailsData["ior_".$rtype];
                    $Sign=$detailsData["ratio_hre"];
                    $row['ShowTypeHRB']=$detailsData["hstrong"];
                }
                if(!$ior_Rate){
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);
            $TG_Team=filiter_team($TG_Team);
            if(!isset($Sign))$Sign=$row['M_LetB_RB_H'];
            $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RB_H"],$row["TG_LetB_Rate_RB_H"],100);
            switch ($type){
                case "H":
                    $M_Place=$MB_Team;
                    if ($flushWay=='ra'){
                        if(!isset($ior_Rate))$ior_Rate=$rate[0];
                        $M_Rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!isset($ior_Rate)){ $M_Rate = round_num($row["MB_LetB_Rate_RB_H"]); }else{ $M_Rate = round_num($ior_Rate); }
                    }
                    $mtype='VRRH';
                    break;
                case "C":
                    $M_Place=$TG_Team;
                    if ($flushWay=='ra'){
                        if(!isset($ior_Rate))$ior_Rate=$rate[1];
                        $M_Rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!isset($ior_Rate)){ $M_Rate = round_num($row["TG_LetB_Rate_RB_H"]); }else{ $M_Rate = round_num($ior_Rate); }
                    }
                    $mtype='VRRC';
                    break;
            }
            $inball=$row['MB_Ball'].":".$row['TG_Ball'];

            if ($row['ShowTypeHRB']=='C'){
                $inball=$row['TG_Ball'].":".$row['MB_Ball'];
                $Team=$MB_Team;
                $MB_Team=$TG_Team;
                $TG_Team=$Team;
            }

            if ($M_Rate==0 or $M_Rate=='' or $Sign==''){
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);

            }

            $gametype=$U_40;
            $line_type=19;
        }
        break;
    case 'FT_hrou': // 半场大小

        $GSINGLE_CREDIT=FT_ROU_Bet;
        if($gid%2 == 1) $gid = $gid;
        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime_RB_H,TG_Dime_RB_H,MB_Dime_Rate_RB_H,TG_Dime_Rate_RB_H,MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";

        $result = mysqli_query($dbCenterMasterDbLink,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){

            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }else{
            if($_REQUEST['flag']=="all" and $_REQUEST['isMaster']!='N'){ // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ior_Rate = $detailsData["ior_".$rtype];
                    switch ($type){
                        case "C": $M_Place='O '.$detailsData["ratio_hrouo"]; break;
                        case "H": $M_Place='U '.$detailsData["ratio_hrouu"]; break;
                    }
                }
                if(!$ior_Rate){
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);
            $TG_Team=filiter_team($TG_Team);
            $rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB_H"],$row["TG_Dime_Rate_RB_H"],100);
            switch ($type){
                case "C":
                    if(!isset($M_Place))$M_Place=$row["MB_Dime_RB_H"];
                    if ($langx=="zh-cn"){
                        $M_Place=str_replace('O','大 ',$M_Place);
                    }else if ($langx=="zh-cn"){
                        $M_Place=str_replace('O','大 ',$M_Place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $M_Place=str_replace('O','over ',$M_Place);
                    }
                    if ($flushWay=='ra'){
                        if(!$ior_Rate)$ior_Rate=$rate[0];
                        $M_Rate=change_rate($open,$ior_Rate);
                    }else{
                        if(!$ior_Rate) {
                            $ior_Rate = $row["MB_Dime_Rate_RB_H"];
                        }
                        $M_Rate = round_num($ior_Rate);
                    }
                    $mtype='VROUH';
                    break;
                case "H":
                    if(!isset($M_Place))$M_Place=$row["TG_Dime_RB_H"];
                    if ($langx=="zh-cn"){
                        $M_Place=str_replace('U','小 ',$M_Place);
                    }else if ($langx=="zh-cn"){
                        $M_Place=str_replace('U','小 ',$M_Place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $M_Place=str_replace('U','under ',$M_Place);
                    }
                    if ($flushWay=='ra'){
                        if(!isset($ior_Rate))$ior_Rate=$rate[1];
                        $M_Rate=change_rate($open,$ior_Rate);
                    }else{
                        if(!$ior_Rate) {
                            $ior_Rate = $row["TG_Dime_Rate_RB_H"];
                        }
                        $M_Rate = round_num($ior_Rate);
                    }
                    $mtype='VROUC';
                    break;
            }
            $inball=$row['MB_Ball'].":".$row['TG_Ball'];

            if ($M_Rate==0 or $M_Rate=='' or $M_Place=='' or $M_Place=='O0' or $M_Place=='U0'){
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again.'-'.$M_Rate.'-'.$M_Place;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_39;
            $line_type=20;
        }
        break;
    case 'FT_rpd':
        $GSINGLE_CREDIT= Ft_Bet ;
        $_REQUEST['id']=''; // 滚球波胆不需要走所有玩法，因为所有玩法需要有人在前台点击后才能拉取到所有玩法的数据

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){

            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        else{
            if($_REQUEST['id']&&$_REQUEST['id']>0){
                $moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                $ior_Rate = $detailsData["ior_".$rtype];
            }else{
                if($rtype=="ROVH"){
                    $files = "RUP5";
                }else{
                    $files = str_replace('H','MB',$rtype);
                    $files = str_replace('C','TG',$files);
                }
                $rbExpandRes = mysqli_query($dbMasterLink,"select $files AS '$rtype' from ".DBPREFIX."match_sports_rb_expand where `MID`='$gid'");
                $rowExpandRes = mysqli_fetch_assoc($rbExpandRes);
                $couExpandRes = mysqli_num_rows($rbExpandRes);
                $ior_Rate = $rowExpandRes[$rtype];
                if($couExpandRes==0 || !$ior_Rate){

                    $status = '401.4';
                    $describe = $Order_This_match_is_closed_Please_try_again;
                    original_phone_request_response($status, $describe);

                }
            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);

            if ($rtype=="ROVH"){
                $M_Place=$Order_Other_Score;
                $M_Sign=$Order_Other_Score." VS ";
                $M_Rate=change_rate($open,$ior_Rate);
            }else{
                $M_Place= returnBoDanBetContent($rtype);
                $M_Sign=$rtype;
                $M_Sign=str_replace("H","(",$M_Sign);
                $M_Sign=str_replace("C",":",$M_Sign);
                $M_Sign=$M_Sign.")";
                $M_Rate=change_rate($open,$ior_Rate);
            }
            if(strlen($M_Rate)==0 || $M_Rate==0){

                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);

            }
            $gametype= "(".$Running_Ball.") ".$U_42;
            $line_type=104;
        }

        break;
    case 'FT_hrpd':
        $GSINGLE_CREDIT= Ft_Bet ;
        if($gid%2==0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and MB_Team!=''";
        }elseif($gid%2==1){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and MB_Team!=''";
        }
        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
        $rowMore = mysqli_fetch_assoc($moreRes);
        $couMore = mysqli_num_rows($moreRes);

        if($cou==0 && $couMore==0){

            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        else{
            if($_REQUEST['id']&&$_REQUEST['id']>0){
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                $rtype= "H".$rtype;
                if($detailsData["sw_".$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ior_Rate = $detailsData["ior_".$rtype];
                }
            }else{
                $rtype= "H".$rtype;
                if(in_array($rtype,array('HRH1C0','HRH2C0','HRH0C1','HRH0C2','HRH2C1','HRH1C2','HRH3C0','HRH0C3','HRH3C1','HRH1C3','HRH3C2',
                    'HRH2C3','HRH4C0','HRH0C4','HRH4C1','HRH1C4','HRH4C2','HRH2C4','HRH4C3','HRH3C4','HRH0C0','HRH2C2','HRH1C1','HRH3C3','HRH4C4','HROVH'))){
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
                    $ior_Rate = $rowExpandRes[$files];
                }
                $rtype= "H".$rtype;
            }

            if(!$ior_Rate){

                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);
            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);

            if ($rtype=="HROVH"){
                $M_Place=$Order_Other_Score;
                $M_Sign=$Order_Other_Score." VS ";
                $M_Rate=change_rate($open,$ior_Rate);
            }else{
                $M_Place= returnBoDanBetContent($rtype);
                $M_Sign=$rtype;
                $M_Sign=str_replace("H","(",$M_Sign);
                $M_Sign=str_replace("C",":",$M_Sign);
                $M_Sign=$M_Sign.")";
                $M_Rate=change_rate($open,$ior_Rate);
            }
            if(substr($rtype,0,1)=="H"){
                $gametype=$U_42H;
            }else{
                $gametype=$U_42;
            }
            $gametype = '('.$Running_Ball.') '.$gametype;

            if(strlen($M_Rate)==0 || $M_Rate==0){

                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
            $line_type=204;

        }
        break;
    case 'FT_rsingle': // 双方球队进球、净胜球数、双重机会、零失球、零失球获胜、独赢 & 进球大/小、独赢 & 双方球队进球、进球 大 / 小 & 双方球队进球、双重机会 & 进球 大 / 小、双重机会 & 双方球队进球、进球 大 / 小 & 进球 单 / 双
        $GSINGLE_CREDIT=Ft_Bet;

        if($gid%2==0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`='$gid' and Open=1 and $mb_team!=''";
        }elseif($gid%2==1){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";
        }
        $result = mysqli_query($dbCenterMasterDbLink,$mysql);
        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);

        $moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
        $rowMore = mysqli_fetch_assoc($moreRes);
        $couMore = mysqli_num_rows($moreRes);
        if($cou==0 || $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        $detailsArr = json_decode($rowMore['details'],true);
        if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
        if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
            $ior_Rate = $detailsData["ior_".$rtype];
        }

        if(!$ior_Rate){
            $status = '401.4';
            $describe = $Order_Odd_changed_please_bet_again;
            original_phone_request_response($status, $describe);
        }

        $M_League=$row['M_League'];
        $MB_Team=$row["MB_Team"];
        $TG_Team=$row["TG_Team"];
        $MB_Team=filiter_team($MB_Team);

        switch ($rtype){
            case "RTSY":
                $M_Place="是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Double_In_betting_order;
                $linetype=115;
                $gametype=$U_50;
                break;
            case "RTSN":
                $M_Place="不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Double_In_betting_order;
                $linetype=115;
                $gametype=$U_50;
                break;
            case "RHTSY":
                $M_Place="是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Double_In_betting_order;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=215;
                $gametype=$U_50."-".$U_00;
                break;
            case "RHTSN":
                $M_Place="不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Double_In_betting_order;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=215;
                $gametype=$U_50."-".$U_00;
                break;
            case "RWMH1":
                $M_Place=$MB_Team." - 净胜1球";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Net_Win_Ballnum;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=118;
                $gametype=$U_53;
                break;
            case "RWM0":
                $M_Place=" 0 - 0 和局";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Net_Win_Ballnum;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=118;
                $gametype=$U_53;
                break;
            case "RWMC1":
                $M_Place=$TG_Team." - 净胜1球";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Net_Win_Ballnum;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=118;
                $gametype=$U_53;
                break;
            case "RWMH2":
                $M_Place=$MB_Team." - 净胜2球";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Net_Win_Ballnum;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=118;
                $gametype=$U_53;
                break;
            case "RWMN":
                $M_Place="任何进球和局";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Net_Win_Ballnum;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=118;
                $gametype=$U_53;
                break;
            case "RWMC2":
                $M_Place=$TG_Team." - 净胜2球";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Net_Win_Ballnum;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=118;
                $gametype=$U_53;
                break;
            case "RWMH3":
                $M_Place=$MB_Team." - 净胜3球";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Net_Win_Ballnum;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=118;
                $gametype=$U_53;
                break;
            case "RWMC3":
                $M_Place=$TG_Team." - 净胜3球";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Net_Win_Ballnum;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=118;
                $gametype=$U_53;
                break;
            case "RWMHOV":
                $M_Place=$MB_Team." - 净胜4球或更多";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Net_Win_Ballnum;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=118;
                $gametype=$U_53;
                break;
            case "RWMCOV":
                $M_Place=$TG_Team." - 净胜4球或更多";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Net_Win_Ballnum;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=118;
                $gametype=$U_53;
                break;
            case "RDCHN":
                $M_Place=$MB_Team." / "."和局";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=119;
                $gametype=$U_54;
                break;
            case "RDCCN":
                $M_Place=$TG_Team." / "."和局";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=119;
                $gametype=$U_54;
                break;
            case "RDCHC":
                $M_Place=$MB_Team." / ".$TG_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=119;
                $gametype=$U_54;
                break;
            case "RCSH":
                $M_Place=$MB_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Clean_Sheets;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=120;
                $gametype=$U_55;
                break;
            case "RCSC":
                $M_Place=$TG_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Clean_Sheets;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=120;
                $gametype=$U_55;
                break;
            case "RWNH":
                $M_Place=$MB_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Clean_Sheets_Win;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=161;
                $gametype=$U_56;
                break;
            case "RWNC":
                $M_Place=$TG_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Clean_Sheets_Win;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=161;
                $gametype=$U_56;
                break;
            case "RMUAHO":	//独赢 & 进球 大 / 小  A
                $M_Place=$MB_Team." & 大 1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUANO":
                $M_Place="和局 & 大 1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUACO":
                $M_Place=$TG_Team." & 大 1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUAHU":
                $M_Place=$MB_Team." & 小  1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUANU":
                $M_Place="和局 & 小  1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUACU":
                $M_Place=$TG_Team." & 小  1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUBHO":	//独赢 & 进球 大 / 小  B
                $M_Place=$MB_Team." & 大 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUBNO":
                $M_Place="和局 & 大 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUBCO":
                $M_Place=$TG_Team." & 大  2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUBHU":
                $M_Place=$MB_Team." & 小 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUBNU":
                $M_Place="和局 & 小 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUBCU":
                $M_Place=$TG_Team." & 小 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUCHO":	//独赢 & 进球 大 / 小  C
                $M_Place=$MB_Team." & 大 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUCNO":
                $M_Place="和局 & 大 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUCCO":
                $M_Place=$TG_Team." & 大 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUCHU":
                $M_Place=$MB_Team." & 小 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUCNU":
                $M_Place="和局 & 小 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUCCU":
                $M_Place=$TG_Team." & 小 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUDHO":	//独赢 & 进球 大 / 小  D
                $M_Place=$MB_Team." & 大 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUDNO":
                $M_Place="和局 & 大 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUDCO":
                $M_Place=$TG_Team." & 大 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUDHU":
                $M_Place=$MB_Team." & 小 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUDNU":
                $M_Place="和局 & 小 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMUDCU":
                $M_Place=$TG_Team." & 小 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_OU;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=122;
                $gametype=$U_58;
                break;
            case "RMTSHY": //独赢 & 双方球队进球
                $M_Place=$MB_Team." & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_Double_in;
                $linetype=123;
                $gametype=$U_59;
                break;
            case "RMTSNY":
                $M_Place="和局 & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_Double_in;
                $linetype=123;
                $gametype=$U_59;
                break;
            case "RMTSCY":
                $M_Place=$TG_Team." & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_Double_in;
                $linetype=123;
                $gametype=$U_59;
                break;
            case "RMTSHN":
                $M_Place=$MB_Team." & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_Double_in;
                $linetype=123;
                $gametype=$U_59;
                break;
            case "RMTSNN":
                $M_Place="和局 & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_Double_in;
                $linetype=123;
                $gametype=$U_59;
                break;
            case "RMTSCN":
                $M_Place=$TG_Team." & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_Double_in;
                $linetype=123;
                $gametype=$U_59;
                break;
            case "RUTAOY":	//进球 大 / 小 & 双方球队进球	 A
                $M_Place="大 1.5 & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTAON":
                $M_Place="大 1.5 & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTAUY":
                $M_Place="小 1.5 & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTAUN":
                $M_Place="小 1.5 & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTBOY":	//进球 大 / 小 & 双方球队进球	 B
                $M_Place="大 2.5 & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTBON":
                $M_Place="大 2.5 & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTBUY":
                $M_Place="小 2.5 & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTBUN":
                $M_Place="小 2.5 & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTCOY":	//进球 大 / 小 & 双方球队进球	C
                $M_Place="大 3.5 & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTCON":
                $M_Place="大 3.5 & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTCUY":
                $M_Place="小 3.5 & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTCUN":
                $M_Place="小 3.5 & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTDOY":	//进球 大 / 小 & 双方球队进球	D
                $M_Place="大 4.5 & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTDON":
                $M_Place="大 4.5 & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTDUY":
                $M_Place="小 4.5 & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "RUTDUN":
                $M_Place="小 4.5 & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_OU_Double_in;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=124;
                $gametype=$U_60;
                break;
            case "MPGHH":	//独赢 & 最先进球
                $M_Place=$MB_Team.' & '.$MB_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=125;
                $gametype=$U_61;
                break;
            case "MPGNH":
                $M_Place='和局 & '.$MB_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=125;
                $gametype=$U_61;
                break;
            case "MPGCH":
                $M_Place=$TG_Team.' & '.$MB_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=125;
                $gametype=$U_61;
                break;
            case "MPGHC":
                $M_Place=$MB_Team.' & '.$TG_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=125;
                $gametype=$U_61;
                break;
            case "MPGNC":
                $M_Place='和局 & '.$TG_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=125;
                $gametype=$U_61;
                break;
            case "MPGCC":
                $M_Place=$TG_Team.' & '.$TG_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_M_Ball_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=125;
                $gametype=$U_61;
                break;
            case "F2GH"://先进2球的一方
                $M_Place=$MB_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_In_2;
                $linetype=126;
                $gametype=$U_75;
                break;
            case "F2GC":
                $M_Place=$TG_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_In_2;
                $linetype=126;
                $gametype=$U_75;
                break;
            case "F3GH"://先进3球的一方
                $M_Place=$MB_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_In_3;
                $linetype=127;
                $gametype=$U_80;
                break;
            case "F3GC":
                $M_Place=$TG_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_In_3;
                $linetype=127;
                $gametype=$U_80;
                break;
            case "RHGH"://最多进球的半场
                $M_Place="上半场";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Most_Ball_In_Half;
                $linetype=128;
                $gametype=$U_62;
                break;
            case "RHGC":
                $M_Place="下半场";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Most_Ball_In_Half;
                $linetype=128;
                $gametype=$U_62;
                break;
            case "RMGH"://最多进球的半场 - 独赢
                $M_Place="上半场";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Most_Ball_In_Half_M;
                $linetype=129;
                $gametype=$U_63;
                break;
            case "RMGC":
                $M_Place="下半场";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Most_Ball_In_Half_M;
                $linetype=129;
                $gametype=$U_63;
                break;
            case "RMGN":
                $M_Place="和局";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Most_Ball_In_Half_M;
                $linetype=129;
                $gametype=$U_63;
                break;
            case "RSBH"://双半场进球
                $M_Place=$MB_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Double_Half_Ball_In;
                $linetype=130;
                $gametype=$U_64;
                break;
            case "RSBC":
                $M_Place=$TG_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Double_Half_Ball_In;
                $linetype=130;
                $gametype=$U_64;
                break;
            case "FGS"://首个进球方式
                $M_Place="射门";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Way;
                $linetype=131;
                $gametype=$U_76;
                break;
            case "FGH":
                $M_Place="头球";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Way;
                $linetype=131;
                $gametype=$U_76;
                break;
            case "FGN":
                $M_Place="无进球	";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Way;
                $linetype=131;
                $gametype=$U_76;
                break;
            case "FGP":
                $M_Place="点球";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Way;
                $linetype=131;
                $gametype=$U_76;
                break;
            case "FGF":
                $M_Place="任意球	";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Way;
                $linetype=131;
                $gametype=$U_76;
                break;
            case "FGO":
                $M_Place="乌龙球	";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Way;
                $linetype=131;
                $gametype=$U_76;
                break;
            case "T3G1"://首个进球时间-3项
                $M_Place="第26分钟或之前";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Time_3P;
                $linetype=132;
                $gametype=$U_65;
                break;
            case "T3G2":
                $M_Place="第27分钟或之后	";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Time_3P;
                $linetype=132;
                $gametype=$U_65;
                break;
            case "T3GN":
                $M_Place="无进球	";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Time_3P;
                $linetype=132;
                $gametype=$U_65;
                break;
            case "T1G1"://首个进球时间
                $M_Place="上半场开场 - 14:59分钟";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Time;
                $linetype=133;
                $gametype=$U_66;
                break;
            case "T1G2":
                $M_Place="15:00分钟 - 29:59分钟";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Time;
                $linetype=133;
                $gametype=$U_66;
                break;
            case "T1G3":
                $M_Place="30:00分钟 - 半场";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Time;
                $linetype=133;
                $gametype=$U_66;
                break;
            case "T1G4":
                $M_Place="下半场开场 - 59:59分钟";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Time;
                $linetype=133;
                $gametype=$U_66;
                break;
            case "T1G5":
                $M_Place="60:00分钟 - 74:59分钟";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Time;
                $linetype=133;
                $gametype=$U_66;
                break;
            case "T1G6":
                $M_Place="75:00分钟 - 全场完场";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Time;
                $linetype=133;
                $gametype=$U_66;
                break;
            case "T1GN":
                $M_Place="无进球	";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Frist_Ball_In_Time;
                $linetype=133;
                $gametype=$U_66;
                break;
            case "RDUAHO":	//双重机会 & 进球 大 / 小  A
                $M_Place=$MB_Team."/和局"." & 大 1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUACO":
                $M_Place=$TG_Team."/和局  & 大 1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUASO":
                $M_Place=$MB_Team."/".$TG_Team." & 大 1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUAHU":
                $M_Place=$MB_Team."/和局"." & 小  1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUACU":
                $M_Place=$TG_Team."和局 & 小  1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUASU":
                $M_Place=$MB_Team."/".$TG_Team." & 小  1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUBHO":	//双重机会 & 进球 大 / 小  B
                $M_Place=$MB_Team."/和局"." & 大 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUBCO":
                $M_Place=$TG_Team."/和局  & 大 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUBSO":
                $M_Place=$MB_Team."/".$TG_Team." & 大 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUBHU":
                $M_Place=$MB_Team."/和局"." & 小  2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUBCU":
                $M_Place=$TG_Team."和局 & 小  2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUBSU":
                $M_Place=$MB_Team."/".$TG_Team." & 小 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUCHO":	//双重机会 & 进球 大 / 小  C
                $M_Place=$MB_Team."/和局"." & 大 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUCCO":
                $M_Place=$TG_Team."/和局  & 大3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUCSO":
                $M_Place=$MB_Team."/".$TG_Team." & 大 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUCHU":
                $M_Place=$MB_Team."/和局"." & 小  3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUCCU":
                $M_Place=$TG_Team."和局 & 小  3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUCSU":
                $M_Place=$MB_Team."/".$TG_Team." & 小 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUDHO":	//双重机会 & 进球 大 / 小  D
                $M_Place=$MB_Team."/和局"." & 大 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUDCO":
                $M_Place=$TG_Team."/和局  & 大4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUDSO":
                $M_Place=$MB_Team."/".$TG_Team." & 大 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUDHU":
                $M_Place=$MB_Team."/和局"." & 小  4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUDCU":
                $M_Place=$TG_Team."和局 & 小  4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDUDSU":
                $M_Place=$MB_Team."/".$TG_Team." & 小 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_OU;
                $linetype=134;
                $gametype=$U_67;
                break;
            case "RDSHY":	//双重机会 & 双方球队进球
                $M_Place=$MB_Team." / 和局   & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Double_In;
                $linetype=135;
                $gametype=$U_68;
                break;
            case "RDSCY":
                $M_Place=$TG_Team." / 和局   & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Double_In;
                $linetype=135;
                $gametype=$U_68;
                break;
            case "RDSSY":
                $M_Place=$MB_Team.' / '.$TG_Team." & 是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Double_In;
                $linetype=135;
                $gametype=$U_68;
                break;
            case "RDSHN":
                $M_Place=$MB_Team." / 和局   & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Double_In;
                $linetype=135;
                $gametype=$U_68;
                break;
            case "RDSCN":
                $M_Place=$TG_Team." / 和局   & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Double_In;
                $linetype=135;
                $gametype=$U_68;
                break;
            case "RDSSN":
                $M_Place=$MB_Team.' / '.$TG_Team." & 不是";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Double_In;
                $linetype=135;
                $gametype=$U_68;
                break;
            case "RDGHH":	//双重机会 & 最先进球
                $M_Place=$MB_Team." / 和局 & ".$MB_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Ball_In_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=136;
                $gametype=$U_69;
                break;
            case "RDGCH":
                $M_Place=$TG_Team." / 和局 & ".$MB_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Ball_In_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=136;
                $gametype=$U_69;
                break;
            case "RDGSH":
                $M_Place=$MB_Team.' / '.$TG_Team.'&'.$MB_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Ball_In_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=136;
                $gametype=$U_69;
                break;
            case "RDGHC":
                $M_Place=$MB_Team." / 和局 & ".$TG_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Ball_In_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=136;
                $gametype=$U_69;
                break;
            case "RDGCC":
                $M_Place=$TG_Team." / 和局 & ".$TG_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Ball_In_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=136;
                $gametype=$U_69;
                break;
            case "RDGSC":
                $M_Place=$MB_Team.' / '.$TG_Team.'&'.$TG_Team."(最先进球)";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Chance_Double_And_Ball_In_First;
                $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                $linetype=136;
                $gametype=$U_69;
                break;
            case "RUEAOO":	//进球 大 / 小 & 进球 单 / 双A
                $M_Place="单 & 大 1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEAOE":
                $M_Place="双  & 大1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEAUO":
                $M_Place="单  & 小  1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEAUE":
                $M_Place="双  & 小  1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEBOO":	//进球 大 / 小 & 进球 单 / 双B
                $M_Place="单 & 大 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEBOE":
                $M_Place="双  & 大2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEBUO":
                $M_Place="单  & 小  2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEBUE":
                $M_Place="双  & 小  2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUECOO":	//进球 大 / 小 & 进球 单 / 双C
                $M_Place="单 & 大 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUECOE":
                $M_Place="双  & 大3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUECUO":
                $M_Place="单  & 小  3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUECUE":
                $M_Place="双  & 小  3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEDOO":	//进球 大 / 小 & 进球 单 / 双		D
                $M_Place="单 & 大 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEDOE":
                $M_Place="双  & 大4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEDUO":
                $M_Place="单  & 小  4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUEDUE":
                $M_Place="双  & 小  4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_OE;
                $linetype=137;
                $gametype=$U_70;
                break;
            case "RUPAOH":	//进球 大 / 小 & 最先进球		A
                $M_Place=$MB_Team."(最先进球) & 大 1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPAOC":
                $M_Place=$TG_Team."(最先进球) & 大1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPAUH":
                $M_Place=$MB_Team."(最先进球) & 小  1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPAUC":
                $M_Place=$TG_Team."(最先进球) & 小  1.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPBOH":	//进球 大 / 小 & 最先进球		B
                $M_Place=$MB_Team."(最先进球) & 大 2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPBOC":
                $M_Place=$TG_Team."(最先进球) & 大2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPBUH":
                $M_Place=$MB_Team."(最先进球) & 小  2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPBUC":
                $M_Place=$TG_Team."(最先进球) & 小  2.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPCOH":	//进球 大 / 小 & 最先进球		C
                $M_Place=$MB_Team."(最先进球) & 大 3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPCOC":
                $M_Place=$TG_Team."(最先进球) & 大3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPCUH":
                $M_Place=$MB_Team."(最先进球) & 小  3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPCUC":
                $M_Place=$TG_Team."(最先进球) & 小  3.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPDOH":	//进球 大 / 小 & 最先进球		D
                $M_Place=$MB_Team."(最先进球) & 大 4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPDOC":
                $M_Place=$TG_Team."(最先进球) & 大4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPDUH":
                $M_Place=$MB_Team."(最先进球) & 小  4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RUPDUC":
                $M_Place=$TG_Team."(最先进球) & 小  4.5";
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_OU_And_Ball_In_First;
                $linetype=138;
                $gametype=$U_71;
                break;
            case "RW3H"://三项让球投注
                $M_Place=$MB_Team." ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_R_3;
                $linetype=139;
                $gametype=$U_77;
                break;
            case "RW3C":
                $M_Place=$TG_Team." ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_R_3;
                $linetype=139;
                $gametype=$U_77;
                break;
            case "RW3N":
                $M_Place="让球和局    ".$detailsData['ratio_'.strtolower($rtype)]; ;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Ball_R_3;
                $linetype=139;
                $gametype=$U_77;
                break;
            case "RBHH"://落后反超获胜
                $M_Place=$MB_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Fall_Catchup_And_Win;
                $linetype=140;
                $gametype=$U_78;
                break;
            case "RBHC":
                $M_Place=$TG_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Fall_Catchup_And_Win;
                $linetype=140;
                $gametype=$U_78;
                break;
            case "RWEH"://赢得任一半场
                $M_Place=$MB_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Win_Any_Half ;
                $linetype=141;
                $gametype=$U_72;
                break;
            case "RWEC":
                $M_Place=$TG_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Win_Any_Half ;
                $linetype=141;
                $gametype=$U_72;
                break;
            case "RWBH"://赢得所有半场
                $M_Place=$MB_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Win_All_Half;
                $linetype=142;
                $gametype=$U_73;
                break;
            case "RWBC":
                $M_Place=$TG_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Win_All_Half;
                $linetype=142;
                $gametype=$U_73;
                break;
            case "RTKH"://开球球队
                $M_Place=$MB_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_First_Ball;
                $linetype=143;
                $gametype=$U_79;
                break;
            case "RTKC":
                $M_Place=$TG_Team;
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_First_Ball;
                $linetype=143;
                $gametype=$U_79;
                break;
            case "ROUHO"://球队进球数: 主队 - 大
                $M_Place=$M_Place="大 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
                $linetype=154;
                $gametype=$U_51.' '.$MB_Team.' - 大/小';
                break;
            case "ROUHU"://球队进球数: 主队 - 小
                $M_Place=$M_Place="小 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
                $linetype=154;
                $gametype=$U_51.' '.$MB_Team.' - 大/小';
                break;
            case "ROUCO"://球队进球数: 客队 - 大
                $M_Place=$M_Place="大 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
                $linetype=154;
                $gametype=$U_51.' '.$TG_Team.' - 大/小';
                break;
            case "ROUCU"://球队进球数: 客队 - 小
                $M_Place=$M_Place="小 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
                $linetype=154;
                $gametype=$U_51.' '.$TG_Team.' - 大/小';
                break;
            case "HRUHO"://上半场	 球队进球数: 主队 - 大
                $M_Place=$M_Place="大 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
                $linetype=244;
                $gametype=$U_00.' '.$U_51.' '.$MB_Team.' - 大/小';
                break;
            case "HRUHU"://上半场	球队进球数: 主队 - 小
                $M_Place=$M_Place="小 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
                $linetype=244;
                $gametype=$U_00.' '.$U_51.' '.$MB_Team.' - 大/小';
                break;
            case "HRUCO"://上半场	 球队进球数: 主队 - 大
                $M_Place=$M_Place="大 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
                $linetype=244;
                $gametype=$U_00.' '.$U_51.' '.$TG_Team.' - 大/小';
                break;
            case "HRUCU"://上半场	球队进球数: 主队 - 小
                $M_Place=$M_Place="小 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
                $linetype=244;
                $gametype=$U_00.' '.$U_51.' '.$TG_Team.' - 大/小';
                break;
        }

        if(strlen($M_Rate)==0 || $M_Rate==0){

            $status = '401.5';
            $describe = $Order_Odd_changed_please_bet_again;
            original_phone_request_response($status, $describe);

        }

        $gametype = "(".$Running_Ball.") ".$gametype;
        $line_type = $linetype;

        break;
    case 'FT_rouhc':
        $GSINGLE_CREDIT=Ft_Bet;

        if($gid%2==0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`='$gid' and Open=1 and $mb_team!=''";
        }elseif($gid%2==1){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";
        }
        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
        $rowMore = mysqli_fetch_assoc($moreRes);
        $couMore = mysqli_num_rows($moreRes);
        if($cou==0 || $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);

        }
        $detailsArr = json_decode($rowMore['details'],true);
        if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}

        if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
            $ior_Rate = $detailsData["ior_".$rtype];
        }
        if(!$ior_Rate){
            $status = '401.4';
            $describe = $Order_Odd_changed_please_bet_again;
            original_phone_request_response($status, $describe);

        }

        $M_League=$row['M_League'];
        $MB_Team=$row["MB_Team"];
        $TG_Team=$row["TG_Team"];
        $MB_Team=filiter_team($MB_Team);
        switch ($rtype){
            case "ROUHO"://球队进球数: 主队 - 大
                $M_Place=$M_Place="大 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
                $linetype=154;
                $gametype=$U_51.' '.$MB_Team.' - 大/小';
                break;
            case "ROUHU"://球队进球数: 主队 - 小
                $M_Place=$M_Place="小 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
                $linetype=154;
                $gametype=$U_51.' '.$MB_Team.' - 大/小';
                break;
            case "ROUCO"://球队进球数: 客队 - 大
                $M_Place=$M_Place="大 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
                $linetype=154;
                $gametype=$U_51.' '.$TG_Team.' - 大/小';
                break;
            case "ROUCU"://球队进球数: 客队 - 小
                $M_Place=$M_Place="小 ".$detailsData['ratio_'.strtolower($rtype)];
                $M_Rate=change_rate($open,$ior_Rate);
                $caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
                $linetype=154;
                $gametype=$U_51.' '.$TG_Team.' - 大/小';
                break;
        }
        $gametype = "(".$Running_Ball.") ".$gametype;
        $line_type = $linetype;

        if(strlen($M_Rate)==0 || $M_Rate==0){
            $status = '401.5';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);

        }

        break;
    case 'FT_rf': // 半场/全场
        $GSINGLE_CREDIT=FT_F_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MBMB,MBFT,MBTG,FTMB,FTFT,FTTG,TGMB,TGFT,TGTG from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);

        }
        else{
//            if($_REQUEST['id']&&$_REQUEST['id']>0){
            if(1){ // 手机版的半场全场，走这里
                $moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($detailsData["sw_".$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ioradio_r_h = $detailsData["ior_".$rtype];
                    if(!$ioradio_r_h){
                        $status = '401.6';
                        $describe = $Order_This_match_is_closed_Please_try_again;
                        original_phone_request_response($status, $describe);
                    }
                }
            }else{
                if(in_array($rtype,array('RFHN','RFHC','RFNH','RFNN','RFNC','RFCH','RFCN','RFCC','RFHH'))){
                    $files =str_replace("F","",$rtype);
                    $files =str_replace("H","MB",$files);
                    $files =str_replace("C","TG",$files);
                    $files =str_replace("N","FT",$files);

                    $rbExpandRes = mysqli_query($dbLink,"select $files from ".DBPREFIX."match_sports_rb_expand where `MID`='$gid'");
                    $rowExpandRes = mysqli_fetch_assoc($rbExpandRes);

                    /*echo '<pre>';
                    print_r($rowExpandRes);
                    echo '<pre>';*/

                    $couExpandRes = mysqli_num_rows($rbExpandRes);
                    $ioradio_r_h = $rowExpandRes[$files];

                    if($couExpandRes==0 || !$ioradio_r_h){

                        $status = '401.7';
                        $describe = $Order_This_match_is_closed_Please_try_again;
                        original_phone_request_response($status, $describe);
                    }
                }
            }

            if ($row['M_Date']==date('Y-m-d')){
                $active=1;
                $class="OFT";
                $caption=$Order_FT.$Order_Half_Full_Time_betting_order;
            }else{
                $active=11;
                $class="OFU";
                $caption=$Order_FT.$Order_Early_Market.$Order_Half_Full_Time_betting_order;
            }
            $M_League=$row['M_League'];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($row["MB_Team"]);
            switch ($rtype){
                case "FHH":
                    $M_Place=$MB_Team.' / '.$MB_Team;
                    $M_Rate=change_rate($open,$row["MBMB"]);
                    break;
                case "FHN":
                    $M_Place=$MB_Team.' / '.$Draw;
                    $M_Rate=change_rate($open,$row["MBFT"]);
                    break;
                case "FHC":
                    $M_Place=$MB_Team.' / '.$TG_Team;
                    $M_Rate=change_rate($open,$row["MBTG"]);
                    break;
                case "FNH":
                    $M_Place=$Draw.' / '.$MB_Team;
                    $M_Rate=change_rate($open,$row["FTMB"]);
                    break;
                case "FNN":
                    $M_Place=$Draw.' / '.$Draw;
                    $M_Rate=change_rate($open,$row["FTFT"]);
                    break;
                case "FNC":
                    $M_Place=$Draw.' / '.$TG_Team;
                    $M_Rate=change_rate($open,$row["FTTG"]);
                    break;
                case "FCH":
                    $M_Place=$TG_Team.' / '.$MB_Team;
                    $M_Rate=change_rate($open,$row["TGMB"]);
                    break;
                case "FCN":
                    $M_Place=$TG_Team.' / '.$Draw;
                    $M_Rate=change_rate($open,$row["TGFT"]);
                    break;
                case "FCC":
                    $M_Place=$TG_Team.' / '.$TG_Team;
                    $M_Rate=change_rate($open,$row["TGTG"]);
                    break;
                case "RFHH":
                    $M_Place=$MB_Team.' / '.$MB_Team;
                    $M_Rate=change_rate($open,$ioradio_r_h);
                    break;
                case "RFHN":
                    $M_Place=$MB_Team.' / '.$Draw;
                    $M_Rate=change_rate($open,$ioradio_r_h);
                    break;
                case "RFHC":
                    $M_Place=$MB_Team.' / '.$TG_Team;
                    $M_Rate=change_rate($open,$ioradio_r_h);
                    break;
                case "RFNH":
                    $M_Place=$Draw.' / '.$MB_Team;
                    $M_Rate=change_rate($open,$ioradio_r_h);
                    break;
                case "RFNN":
                    $M_Place=$Draw.' / '.$Draw;
                    $M_Rate=change_rate($open,$ioradio_r_h);
                    break;
                case "RFNC":
                    $M_Place=$Draw.' / '.$TG_Team;
                    $M_Rate=change_rate($open,$ioradio_r_h);
                    break;
                case "RFCH":
                    $M_Place=$TG_Team.' / '.$MB_Team;
                    $M_Rate=change_rate($open,$ioradio_r_h);
                    break;
                case "RFCN":
                    $M_Place=$TG_Team.' / '.$Draw;
                    $M_Rate=change_rate($open,$ioradio_r_h);
                    break;
                case "RFCC":
                    $M_Place=$TG_Team.' / '.$TG_Team;
                    $M_Rate=change_rate($open,$ioradio_r_h);
                    break;
            }

            if(strlen($M_Rate)==0 || $M_Rate==0){
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }

            if(strpos($rtype,"R")>-1){
                $method = "(".$Running_Ball.") ".$U_09;
                $line_type=107;
            }else{
                $method = $U_09;
                $line_type=7;
            }
            $gametype = $method;
        }
        break;
// -----------------------------------------  足球滚球End

    // -----------------------------------------  足球今日赛事、足球早盘Start
    case 'FT_m': // 独赢

        $GSINGLE_CREDIT = FT_M_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterSlaveDbLink, $mysql);
        $row = mysqli_fetch_assoc($result);
        $cou = mysqli_num_rows($result);

        if ($cou == 0) {
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        } else {

            if ($_REQUEST['flag'] == "all") { // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink, "select details from " . DBPREFIX . "match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'], true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if ($detailsData['sw_' . $wtype] == "Y" && $detailsData["ior_" . $rtype] > 0) {
                    $ior_Rate = $detailsData["ior_" . $rtype];
                }
                if (!$ior_Rate) {
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League = $row['M_League'];
            $MB_Team = $row["MB_Team"];
            $TG_Team = $row["TG_Team"];
            $MB_Team = filiter_team($MB_Team);
            switch ($type) {
                case "H":
                    $M_Place = $MB_Team;
                    if (!isset($ior_Rate)) $ior_Rate = $row["MB_Win_Rate"];
                    $M_Rate = change_rate($open, $ior_Rate);
                    $mtype = 'MH';
                    break;
                case "C":
                    $M_Place = $TG_Team;
                    if (!isset($ior_Rate)) $ior_Rate = $row["TG_Win_Rate"];
                    $M_Rate = change_rate($open, $ior_Rate);
                    $mtype = 'MC';
                    break;
                case "N":
                    $M_Place = $Draw;
                    if (!isset($ior_Rate)) $ior_Rate = $row["M_Flat_Rate"];
                    $M_Rate = change_rate($open, $ior_Rate);
                    $mtype = 'MN';
                    break;
            }

            if ($M_Rate == 0 || $M_Rate == '') {
                $status = '401.5';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);
            }
            $gametype = $U_30;
            $line_type =1;
        }
        break;
    case 'FT_r': // 让球
        $GSINGLE_CREDIT=FT_R_Bet;
        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeR,MB_LetB_Rate,TG_LetB_Rate,M_LetB from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }else{

            if($_REQUEST['flag']=="all"){ // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ior_Rate = $detailsData["ior_".$rtype];
                    $Sign=$detailsData["ratio"];
                    $row['ShowTypeR']=$detailsData["strong"];
                }
                if(!$ior_Rate){
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);
            if(!isset($Sign))$Sign=$row['M_LetB'];
            $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100);
            switch ($type){
                case "H":
                    $M_Place=$MB_Team;
                    if ($flushWay=='ra'){
                        if(!isset($ior_Rate))$ior_Rate=$rate[0];
                        $M_Rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!isset($ior_Rate)){ $M_Rate = round_num($row["MB_LetB_Rate"]); }else{ $M_Rate = round_num($ior_Rate); }
                    }
                    $mtype='RH';
                    break;
                case "C":
                    $M_Place=$TG_Team;
                    if ($flushWay=='ra'){
                        if(!isset($ior_Rate))$ior_Rate=$rate[1];
                        $M_Rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!isset($ior_Rate)){ $M_Rate = round_num($row["TG_LetB_Rate"]); }else{ $M_Rate = round_num($ior_Rate); }
                    }
                    $mtype='RC';
                    break;
            }
            if ($row['ShowTypeR']=='C'){
                $Team=$MB_Team;
                $MB_Team=$TG_Team;
                $TG_Team=$Team;
            }

            if ($M_Rate==0 or $M_Rate=='' or $Sign==''){
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_02;
            $line_type=2;
        }

        break;
    case 'FT_ou': // 大小
        $GSINGLE_CREDIT=FT_OU_Bet;
        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }else {

            if ($_REQUEST['flag'] == "all") { // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink, "select details from " . DBPREFIX . "match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'], true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if ($detailsData['sw_' . $wtype] == "Y" && $detailsData["ior_" . $rtype] > 0) {
                    $ior_Rate = $detailsData["ior_" . $rtype];
                    switch ($type){
                        case "C": $M_Place='O '.$detailsData["ratio_o"]; break;
                        case "H": $M_Place='U '.$detailsData["ratio_u"]; break;
                    }
                }
                if (!$ior_Rate) {
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League = $row['M_League'];
            $MB_Team = $row["MB_Team"];
            $TG_Team = $row["TG_Team"];
            $MB_Team = filiter_team($MB_Team);
            $rate = get_other_ioratio($odd_f_type, $row["MB_Dime_Rate"], $row["TG_Dime_Rate"], 100);
            switch ($type) {
                case "C":
                    if(!isset($M_Place))$M_Place = $row['MB_Dime'];
                    if ($langx == "zh-cn") {
                        $M_Place = str_replace('O', '大 ', $M_Place);
                    } else if ($langx == "zh-cn") {
                        $M_Place = str_replace('O', '大 ', $M_Place);
                    } else if ($langx == "en-us" or $langx == "th-tis") {
                        $M_Place = str_replace('O', 'over ', $M_Place);
                    }
                    if (!isset($ior_Rate)) $ior_Rate = $rate[0];
                    $M_Rate = change_rate($open, $ior_Rate);
                    $mtype = 'OUH';
                    break;
                case "H":
                    if(!isset($M_Place))$M_Place = $row["TG_Dime"];
                    if ($langx == "zh-cn") {
                        $M_Place = str_replace('U', '小 ', $M_Place);
                    } else if ($langx == "zh-cn") {
                        $M_Place = str_replace('U', '小 ', $M_Place);
                    } else if ($langx == "en-us" or $langx == "th-tis") {
                        $M_Place = str_replace('U', 'under ', $M_Place);
                    }
                    if (!isset($ior_Rate)) $ior_Rate = $rate[1];
                    $M_Rate = change_rate($open, $ior_Rate);
                    $mtype = 'OUC';
                    break;
            }

            if ($M_Rate == 0 or $M_Rate == '' or $M_Place == '' or $M_Place == 'O0' or $M_Place == 'U0') {
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_03;
            $line_type=3;
        }

        break;
    case 'FT_t': // 单双、单双 - 上半场、总进球数、总进球数-上半场
        $GSINGLE_CREDIT=FT_T_Bet;

        if($gid%2==0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate,S_0_1,S_2_3,S_4_6,S_7UP from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        }elseif($gid%2==1){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate,S_0_1,S_2_3,S_4_6,S_7UP from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`=$gid and Open=1 and $mb_team!=''";
        }

        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.4';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        else {
            if($_REQUEST['id']&&$_REQUEST['id'] and $_REQUEST['isMaster']!='N'){
                $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if(in_array($rtype,array('0~1','2~3','4~6','OVER'))){
                    if($rtype=='0~1')	$rtypeNew="T01";
                    if($rtype=='2~3') $rtypeNew="T23";
                    if($rtype=='4~6')	$rtypeNew="T46";
                    if($rtype=='OVER')	$rtypeNew="OVER";
                    if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtypeNew]>0){
                        $ior_Rate = $detailsData["ior_".$rtypeNew];
                    }
                }elseif(in_array($rtype,array('HT0','HT1','HT2','HTOV'))){
                    if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                        $ior_Rate = $detailsData["ior_".$rtype];
                    }
                }elseif(in_array($rtype,array('ODD','EVEN'))){
                    if($rtype=='ODD')	$rtypeNew="EOO";
                    if($rtype=='EVEN') $rtypeNew="EOE";
                    if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtypeNew]>0){
                        $ior_Rate = $detailsData["ior_".$rtypeNew]; //附属盘 ior_EOO  ior_EOE
                    }
                }elseif(in_array($rtype,array('HODD','HEVEN'))){
                    if($rtype=='HODD')	$rtypeNew="HEOO";
                    if($rtype=='HEVEN') $rtypeNew="HEOE";
                    if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtypeNew]>0){
                        //$ior_Rate = $detailsData["ior_".$rtypeNew];
                        if ($flushWay=='ra'){
                            $ior_Rate_arr=get_other_ioratio(GAME_POSITION,returnOddEvenRate($detailsData["ior_HEOO"]),returnOddEvenRate($detailsData["ior_HEOE"]),100);
                            $ior_Rate[0] =returnOddEvenRate($ior_Rate_arr[0],'plus');
                            $ior_Rate[1] =returnOddEvenRate($ior_Rate_arr[1],'plus');
                            if($rtype=='HODD'){ //单
                                $ior_Rate=$ior_Rate[0];
                            }else{ // 双
                                $ior_Rate=$ior_Rate[1];
                            }
                        }else{
                            if($rtype=='HODD'){ //单
                                $ior_Rate=$detailsData["ior_HEOO"];
                            }else{ // 双
                                $ior_Rate=$detailsData["ior_HEOE"];
                            }
                        }
                    }
                }

                if(!$ior_Rate){
                    $status = '401.3';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            } else { // 主盘 flag=''
                if ($rtype == "ODD" || $rtype == "EVEN") {
                    if ($rtype == "ODD") $ior_Rate = $row['S_Single_Rate'];
                    if ($rtype == "EVEN") $ior_Rate = $row['S_Double_Rate'];
                    if (!$ior_Rate) {
                        $status = '401.47';
                        $describe = $Order_This_match_is_closed_Please_try_again;
                        original_phone_request_response($status, $describe);
                    }
                }
            }

            $M_League = $row['M_League'];
            $MB_Team = $row["MB_Team"];
            $TG_Team = $row["TG_Team"];
            $MB_Team = filiter_team($MB_Team);
            switch ($rtype) {
                case "ODD":
                    $M_Place = "(" . $Order_Odd . ")";
                    //$M_Rate = change_rate($open, $row["S_Single_Rate"]);
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE1;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT1;
                    $caption = $Order_Odd_Even_betting_order;
                    $line_type = 5;
                    break;
                case "EVEN":
                    $M_Place = "(" . $Order_Even . ")";
                    //$M_Rate = change_rate($open, $row["S_Double_Rate"]);
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE1;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT1;
                    $caption = $Order_Odd_Even_betting_order;
                    $line_type = 5;
                    break;
                case "HODD":
                    $M_Place = "(" . $Order_Odd . ")";
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE1;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT1;
                    $caption = $Order_Odd_Even_betting_order;
                    $line_type = 15;
                    break;
                case "HEVEN":
                    $M_Place = "(" . $Order_Even . ")";
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE1;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT1;
                    $caption = $Order_Odd_Even_betting_order;
                    $line_type = 15;
                    break;
                case "0~1":
                    $M_Place = "(0~1)";
                    //$M_Rate = change_rate($open, $row["S_0_1"]);
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 6;
                    break;
                case "2~3":
                    $M_Place = "(2~3)";
                    //$M_Rate = change_rate($open, $row["S_2_3"]);
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 6;
                    break;
                case "4~6":
                    $M_Place = "(4~6)";
                    //$M_Rate = change_rate($open, $row["S_4_6"]);
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 6;
                    break;
                case "OVER":
                    $M_Place = "(7UP)";
                    //$M_Rate = change_rate($open, $row["S_7UP"]);
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 6;
                    break;
                case "HT0":
                    $M_Place = "0";
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 46;
                    break;
                case "HT1":
                    $M_Place = "1";
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 46;
                    break;
                case "HT2":
                    $M_Place = "2";
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 46;
                    break;
                case "HTOV":
                    $M_Place = "3或以上";
                    $M_Rate = change_rate($open, $ior_Rate);
                    $GMAX_SINGLE = $GMAX_SINGLE2;
                    //$GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                    $caption = $Order_Total_Goals_betting_order;
                    $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                    $line_type = 46;
                    break;
            }

            if ($rtype == 'EVEN' or $rtype == 'ODD') {
                $gametype = $U_31;
                if (substr($_REQUEST['wtype'], 0, 1) == "H") {
                    $gametype = $U_31 . "-" . $U_00;
                }
            } elseif ($rtype == 'HEVEN' or $rtype == 'HODD') {
                $gametype = $U_31;
                if (substr($_REQUEST['wtype'], 0, 1) == "H") {
                    $gametype = $U_OE . "-" . $U_00;
                }
            } elseif (substr($_REQUEST['wtype'], 0, 1) == "H") {
                $gametype = $U_41H;
            } else {
                $gametype = $U_41;
            }

            if (strlen($M_Rate) == 0) {
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
        }

        break;
    case 'FT_hm': // 半场独赢

        $GSINGLE_CREDIT = FT_M_Bet;

        if($gid%2==0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        }elseif($gid%2==1){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`=$gid and Open=1 and $mb_team!=''";
        }
        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }else {

            if ($_REQUEST['flag'] == "all") { // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink, "select details from " . DBPREFIX . "match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'], true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if ($detailsData['sw_' . $wtype] == "Y" && $detailsData["ior_" . $rtype] > 0) {
                    $ior_Rate = $detailsData["ior_" . $rtype];
                }
                if (!$ior_Rate) {
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League = $row['M_League'];
            $MB_Team = $row["MB_Team"];
            $TG_Team = $row["TG_Team"];
            $MB_Team = filiter_team($MB_Team);
            switch ($type) {
                case "H":
                    $M_Place = $MB_Team;
                    if (!isset($ior_Rate)) $ior_Rate = $row["MB_Win_Rate_H"];
                    $M_Rate = change_rate($open, $ior_Rate);
                    $mtype = 'VMH';
                    break;
                case "C":
                    $M_Place = $TG_Team;
                    if (!isset($ior_Rate)) $ior_Rate = $row["TG_Win_Rate_H"];
                    $M_Rate = change_rate($open, $ior_Rate);
                    $mtype = 'VMC';
                    break;
                case "N":
                    $M_Place = $Draw;
                    if (!isset($ior_Rate)) $ior_Rate = $row["M_Flat_Rate_H"];
                    $M_Rate = change_rate($open, $ior_Rate);
                    $mtype = 'VMN';
                    break;
            }

            if ($M_Rate == 0 || $M_Rate == "") {
                $status = '401.5';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_32;
            $line_type=11;
        }
        break;
    case 'FT_hr': // 半场让球

        $GSINGLE_CREDIT=FT_R_Bet;
        if($gid%2==0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        }elseif($gid%2==1){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Open=1 and $mb_team!=''";
        }

        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }else {

            if ($_REQUEST['flag'] == "all") { // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink, "select details from " . DBPREFIX . "match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'], true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if ($detailsData['sw_' . $wtype] == "Y" && $detailsData["ior_" . $rtype] > 0) {
                    $ior_Rate = $detailsData["ior_" . $rtype];
                    $Sign=$detailsData["hratio"];
                    $row['ShowTypeHR']=$detailsData["hstrong"];
                }
                if (!$ior_Rate) {
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League = $row['M_League'];
            $MB_Team = $row["MB_Team"];
            $TG_Team = $row["TG_Team"];
            $MB_Team = filiter_team($MB_Team);
            if(!isset($Sign))$Sign=$row['M_LetB_H'];
            $rate = get_other_ioratio($odd_f_type, $row["MB_LetB_Rate_H"], $row["TG_LetB_Rate_H"], 100);
            switch ($type) {
                case "H":
                    $M_Place = $MB_Team;
                    if ($flushWay=='ra'){
                        if(!isset($ior_Rate))$ior_Rate=$rate[0];
                        $M_Rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!isset($ior_Rate)){ $M_Rate = round_num($row["MB_LetB_Rate_H"]); }else{ $M_Rate = round_num($ior_Rate); }
                    }
                    $mtype = 'VRH';
                    break;
                case "C":
                    $M_Place = $TG_Team;
                    if ($flushWay=='ra'){
                        if(!isset($ior_Rate))$ior_Rate=$rate[1];
                        $M_Rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!isset($ior_Rate)){ $M_Rate = round_num($row["TG_LetB_Rate_H"]); }else{ $M_Rate = round_num($ior_Rate); }
                    }
                    $mtype = 'VRC';
                    break;
            }

            if ($row['ShowTypeHR'] == 'C') {
                $Team = $MB_Team;
                $MB_Team = $TG_Team;
                $TG_Team = $Team;
            }
            $havesql = "select sum(BetScore) as BetScore from " . DBPREFIX . "web_report_data where m_name='$memname' and MID='$gid' and linetype=12 and Mtype='$mtype' and (Active=1 or Active=11)";
            $result = mysqli_query($dbLink, $havesql);
            $haverow = mysqli_fetch_assoc($result);
            $have_bet = $haverow['BetScore'] + 0;

            $sql = "select CS,VR from " . DBPREFIX . "match_league where  $m_league='$M_League'";
            $result = mysqli_query($dbLink, $sql);
            $league = mysqli_fetch_assoc($result);
            $mmb_team = explode("[", $row['MB_Team']);
            if ($mmb_team[1] == $Special1) {
                $bettop = $league['CS'];
            } else {
                //$bettop=$league['VR'];
                $bettop = $GSINGLE_CREDIT;
            }

            if ($M_Rate == 0 or $Sign == '') {
                $status = '401.4';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
            $gametype = $U_34;
            $line_type=12;
        }
        break;
    case 'FT_hou': // 半场大小
        $GSINGLE_CREDIT=FT_OU_Bet;
        if($gid%2==0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        }elseif($gid%2==1){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Open=1 and $mb_team!=''";
        }

        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }else {

            if ($_REQUEST['flag'] == "all") { // 所有玩法判断
                $moreRes = mysqli_query($dbMasterLink, "select details from " . DBPREFIX . "match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'], true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if ($detailsData['sw_' . $wtype] == "Y" && $detailsData["ior_" . $rtype] > 0) {
                    $ior_Rate = $detailsData["ior_" . $rtype];
                    switch ($type){
                        case "C": $M_Place='O '.$detailsData["ratio_ho"]; break;
                        case "H": $M_Place='U '.$detailsData["ratio_hu"]; break;
                    }
                }
                if (!$ior_Rate) {
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League = $row['M_League'];
            $MB_Team = $row["MB_Team"];
            $TG_Team = $row["TG_Team"];
            $MB_Team = filiter_team($MB_Team);
            $rate = get_other_ioratio($odd_f_type, $row["MB_Dime_Rate_H"], $row["TG_Dime_Rate_H"], 100);
            switch ($type) {
                case "C":
                    if(!isset($M_Place))$M_Place = $row['MB_Dime_H'];
                    if ($langx == "zh-cn") {
                        $M_Place = str_replace('O', '大 ', $M_Place);
                    } else if ($langx == "zh-cn") {
                        $M_Place = str_replace('O', '大 ', $M_Place);
                    } else if ($langx == "en-us" or $langx == "th-tis") {
                        $M_Place = str_replace('O', 'over ', $M_Place);
                    }
                    if (!isset($ior_Rate)) $ior_Rate = $rate[0];
                    $M_Rate = change_rate($open, $ior_Rate);
                    $mtype = 'VOUH';
                    break;
                case "H":
                    if(!isset($M_Place))$M_Place = $row["TG_Dime_H"];
                    if ($langx == "zh-cn") {
                        $M_Place = str_replace('U', '小 ', $M_Place);
                    } else if ($langx == "zh-cn") {
                        $M_Place = str_replace('U', '小 ', $M_Place);
                    } else if ($langx == "en-us" or $langx == "th-tis") {
                        $M_Place = str_replace('U', 'under ', $M_Place);
                    }
                    if (!isset($ior_Rate)) $ior_Rate = $rate[1];
                    $M_Rate = change_rate($open, $ior_Rate);
                    $mtype = 'VOUC';
                    break;
            }

            if ($M_Rate == 0 or $M_Rate == '' or $M_Place == '' or $M_Place == 'O0' or $M_Place == 'U0') {
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_33;
            $line_type=13;
        }

        break;
    case 'FT_pd':

        $GSINGLE_CREDIT=FT_PD_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB1TG0,MB2TG0,MB2TG1,MB3TG0,MB3TG1,MB3TG2,MB4TG0,MB4TG1,MB4TG2,MB4TG3,MB0TG0,MB1TG1,MB2TG2,MB3TG3,MB4TG4,UP5,MB0TG1,MB0TG2,MB1TG2,MB0TG3,MB1TG3,MB2TG3,MB0TG4,MB1TG4,MB2TG4,MB3TG4 from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Open=1 and MB_Team!=''";
        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);

        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        else{
            if($_REQUEST['id']&&$_REQUEST['id']>0){
                $moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                $ior_Rate = $detailsData["ior_".$rtype];
            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);

            if ($rtype=="OVH"){
                $M_Place=$Order_Other_Score;
                $M_Sign="其它比分";
                if(!$ior_Rate){$ior_Rate=$row['UP5'];}
                $M_Rate=change_rate($open,$ior_Rate);
            }else{
                $M_Place = returnBoDanBetContent($rtype) ;
                $M_Sign=$rtype;
                $M_Sign=str_replace("H","(",$M_Sign);
                $M_Sign=str_replace("C",":",$M_Sign);
                $M_Sign=$M_Sign.")";
                $M_Rate=str_replace("H","MB",$rtype);
                $M_Rate=str_replace("C","TG",$M_Rate);
                if(!$ior_Rate){$ior_Rate=$row[$M_Rate];}
                $M_Rate=change_rate($open,$ior_Rate);
            }

            if(strlen($M_Rate)==0 || $M_Rate==0){

                $status = '401.4';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);

            }
            $gametype=$U_42;
            $line_type=4;
        }
        break;
    case 'FT_hpd':
        $GSINGLE_CREDIT=FT_PD_Bet;

        if($gid%2==0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB1TG0H,MB2TG0H,MB2TG1H,MB3TG0H,MB3TG1H,MB3TG2H,MB4TG0H,MB4TG1H,MB4TG2H,MB4TG3H,MB0TG0H,MB1TG1H,MB2TG2H,MB3TG3H,MB4TG4H,UP5H,MB0TG1H,MB0TG2H,MB1TG2H,MB0TG3H,MB1TG3H,MB2TG3H,MB0TG4H,MB1TG4H,MB2TG4H,MB3TG4H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Open=1 and MB_Team!='' and MB_Team_tw!=''";
        }elseif($gid%2==1){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB1TG0H,MB2TG0H,MB2TG1H,MB3TG0H,MB3TG1H,MB3TG2H,MB4TG0H,MB4TG1H,MB4TG2H,MB4TG3H,MB0TG0H,MB1TG1H,MB2TG2H,MB3TG3H,MB4TG4H,UP5H,MB0TG1H,MB0TG2H,MB1TG2H,MB0TG3H,MB1TG3H,MB2TG3H,MB0TG4H,MB1TG4H,MB2TG4H,MB3TG4H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Open=1 and MB_Team!='' and MB_Team_tw!=''";
        }

        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);

        }
        else{
            if($_REQUEST['id']&&$_REQUEST['id']>0){
                $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                $rtype='H'.$rtype;
                if($detailsData["sw_".$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ior_Rate = $detailsData["ior_".$rtype];
                }
                if(!$ior_Rate){

                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);

                }
            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);

            if ($rtype=="HOVH"){
                $M_Place=$Order_Other_Score;
                $M_Sign="其它比分";
                if(!$ior_Rate){$ior_Rate=$row['UP5H'];}
                $M_Rate=change_rate($open,$ior_Rate);
            }else{
                $M_Place = returnBoDanBetContent($rtype) ;
                $M_Sign=$rtype;
                $M_Sign=str_replace("H","(",$M_Sign);
                $M_Sign=str_replace("C",":",$M_Sign);
                $M_Sign=$M_Sign.")";
                $M_Rate=str_replace("H","MB",$rtype);
                $M_Rate=str_replace("C","TG",$M_Rate);
                if(!$ior_Rate){$ior_Rate=$row[$M_Rate."H"];}
                $M_Rate=change_rate($open,$ior_Rate);
            }

            if(substr($_REQUEST['wtype'],0,1)=="H"){
                $gametype=$U_42H;
            }else{
                $gametype=$U_42;
            }

            if(strlen($M_Rate)==0){

                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);

            }

            $line_type=14;
        }

        break;
    case 'FT_single':
        // 主盘口（双方球队进球、双方球队进球-上半场、球队进球数-大小、球队进球数-大小 -上半场、净胜球、双重机会、零失球、零失球获胜、独赢 & 双方球队进球）
        // 进球盘口（最多进球的半场、最多进球的半场 - 独赢、双半场进球、双重机会 & 进球 大 / 小、双重机会 & 双方球队进球）
        // 其他盘口（其他盘口、赢得任一半场、赢得所有半场）

        $GSINGLE_CREDIT= Ft_Bet ;

        if($gid%2==0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        }elseif($gid%2==1){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`=$gid and Open=1 and $mb_team!=''";
        }
        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
        $rowMore = mysqli_fetch_assoc($moreRes);
        $couMore = mysqli_num_rows($moreRes);
        if($cou==0 || $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);

        }
        else{

            $detailsArr = json_decode($rowMore['details'],true);
            if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}

            if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                $ior_Rate = $detailsData["ior_".$rtype];
            }
            if(!$ior_Rate){
                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);

            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);
            switch ($rtype){
                case "TSY":
                    $M_Place="是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Double_In_betting_order;
                    $linetype=65;
                    $gametype=$U_50;
                    break;
                case "TSN":
                    $M_Place="不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Double_In_betting_order;
                    $linetype=65;
                    $gametype=$U_50;
                    break;
                case "HTSY":
                    $M_Place="是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Double_In_betting_order;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=165;
                    $gametype=$U_50."-".$U_00;
                    break;
                case "HTSN":
                    $M_Place="不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Double_In_betting_order;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=165;
                    $gametype=$U_50."-".$U_00;
                    break;
                case "WMH1":
                    $M_Place=$MB_Team." - 净胜1球";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Net_Win_Ballnum;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=18;
                    $gametype=$U_53;
                    break;
                case "WM0":
                    $M_Place=" 0 - 0 和局";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Net_Win_Ballnum;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=18;
                    $gametype=$U_53;
                    break;
                case "WMC1":
                    $M_Place=$TG_Team." - 净胜1球";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Net_Win_Ballnum;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=18;
                    $gametype=$U_53;
                    break;
                case "WMH2":
                    $M_Place=$MB_Team." - 净胜2球";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Net_Win_Ballnum;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=18;
                    $gametype=$U_53;
                    break;
                case "WMN":
                    $M_Place="任何进球和局";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Net_Win_Ballnum;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=18;
                    $gametype=$U_53;
                    break;
                case "WMC2":
                    $M_Place=$TG_Team." - 净胜2球";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Net_Win_Ballnum;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=18;
                    $gametype=$U_53;
                    break;
                case "WMH3":
                    $M_Place=$MB_Team." - 净胜3球";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Net_Win_Ballnum;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=18;
                    $gametype=$U_53;
                    break;
                case "WMC3":
                    $M_Place=$TG_Team." - 净胜3球";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Net_Win_Ballnum;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=18;
                    $gametype=$U_53;
                    break;
                case "WMHOV":
                    $M_Place=$MB_Team." - 净胜4球或更多";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Net_Win_Ballnum;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=18;
                    $gametype=$U_53;
                    break;
                case "WMCOV":
                    $M_Place=$TG_Team." - 净胜4球或更多";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Net_Win_Ballnum;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=18;
                    $gametype=$U_53;
                    break;
                case "DCHN":
                    $M_Place=$MB_Team." / "."和局";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=69;
                    $gametype=$U_54;
                    break;
                case "DCCN":
                    $M_Place=$TG_Team." / "."和局";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=69;
                    $gametype=$U_54;
                    break;
                case "DCHC":
                    $M_Place=$MB_Team." / ".$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=69;
                    $gametype=$U_54;
                    break;
                case "CSH":
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Clean_Sheets;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=62;
                    $gametype=$U_55;
                    break;
                case "CSC":
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Clean_Sheets;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=62;
                    $gametype=$U_55;
                    break;
                case "WNH":
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Clean_Sheets_Win;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=61;
                    $gametype=$U_56;
                    break;
                case "WNC":
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Clean_Sheets_Win;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=61;
                    $gametype=$U_56;
                    break;
                case "MOUAHO":	//独赢 & 进球 大 /小  A
                    $M_Place=$MB_Team." & 大 1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUANO":
                    $M_Place="和局 & 大 1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUACO":
                    $M_Place=$TG_Team." & 大 1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUAHU":
                    $M_Place=$MB_Team." & 小  1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUANU":
                    $M_Place="和局 & 小  1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUACU":
                    $M_Place=$TG_Team." & 小  1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUBHO":	//独赢 & 进球 大 / 小  B
                    $M_Place=$MB_Team." & 大 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUBNO":
                    $M_Place="和局 & 大 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUBCO":
                    $M_Place=$TG_Team." & 大  2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUBHU":
                    $M_Place=$MB_Team." & 小 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUBNU":
                    $M_Place="和局 & 小 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUBCU":
                    $M_Place=$TG_Team." & 小 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUCHO":	//独赢 & 进球 大 / 小  C
                    $M_Place=$MB_Team." & 大 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUCNO":
                    $M_Place="和局 & 大 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUCCO":
                    $M_Place=$TG_Team." & 大 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUCHU":
                    $M_Place=$MB_Team." & 小 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUCNU":
                    $M_Place="和局 & 小 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUCCU":
                    $M_Place=$TG_Team." & 小 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUDHO":	//独赢 & 进球 大 / 小  D
                    $M_Place=$MB_Team." & 大 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUDNO":
                    $M_Place="和局 & 大 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUDCO":
                    $M_Place=$TG_Team." & 大 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUDHU":
                    $M_Place=$MB_Team." & 小 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUDNU":
                    $M_Place="和局 & 小 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MOUDCU":
                    $M_Place=$TG_Team." & 小 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_OU;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=22;
                    $gametype=$U_58;
                    break;
                case "MTSHY": //独赢 & 双方球队进球
                    $M_Place=$MB_Team." & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_Double_in;
                    $linetype=23;
                    $gametype=$U_59;
                    break;
                case "MTSNY":
                    $M_Place="和局 & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_Double_in;
                    $linetype=23;
                    $gametype=$U_59;
                    break;
                case "MTSCY":
                    $M_Place=$TG_Team." & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_Double_in;
                    $linetype=23;
                    $gametype=$U_59;
                    break;
                case "MTSHN":
                    $M_Place=$MB_Team." & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_Double_in;
                    $linetype=23;
                    $gametype=$U_59;
                    break;
                case "MTSNN":
                    $M_Place="和局 & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_Double_in;
                    $linetype=23;
                    $gametype=$U_59;
                    break;
                case "MTSCN":
                    $M_Place=$TG_Team." & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_Double_in;
                    $linetype=23;
                    $gametype=$U_59;
                    break;
                case "OUTAOY":	//进球 大 / 小 & 双方球队进球	 A
                    $M_Place="大 1.5 & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTAON":
                    $M_Place="大 1.5 & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTAUY":
                    $M_Place="小 1.5 & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTAUN":
                    $M_Place="小 1.5 & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTBOY":	//进球 大 / 小 & 双方球队进球	 B
                    $M_Place="大 2.5 & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTBON":
                    $M_Place="大 2.5 & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTBUY":
                    $M_Place="小 2.5 & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTBUN":
                    $M_Place="小 2.5 & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTCOY":	//进球 大 / 小 & 双方球队进球	C
                    $M_Place="大 3.5 & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTCON":
                    $M_Place="大 3.5 & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTCUY":
                    $M_Place="小 3.5 & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTCUN":
                    $M_Place="小 3.5 & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTDOY":	//进球 大 / 小 & 双方球队进球	D
                    $M_Place="大 4.5 & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTDON":
                    $M_Place="大 4.5 & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTDUY":
                    $M_Place="小 4.5 & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "OUTDUN":
                    $M_Place="小 4.5 & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_OU_Double_in;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=24;
                    $gametype=$U_60;
                    break;
                case "MPGHH":	//独赢 & 最先进球
                    $M_Place=$MB_Team.' & '.$MB_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=25;
                    $gametype=$U_61;
                    break;
                case "MPGNH":
                    $M_Place='和局 & '.$MB_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=25;
                    $gametype=$U_61;
                    break;
                case "MPGCH":
                    $M_Place=$TG_Team.' & '.$MB_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=25;
                    $gametype=$U_61;
                    break;
                case "MPGHC":
                    $M_Place=$MB_Team.' & '.$TG_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=25;
                    $gametype=$U_61;
                    break;
                case "MPGNC":
                    $M_Place='和局 & '.$TG_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=25;
                    $gametype=$U_61;
                    break;
                case "MPGCC":
                    $M_Place=$TG_Team.' & '.$TG_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_M_Ball_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=25;
                    $gametype=$U_61;
                    break;
                case "F2GH"://先进2球的一方
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_In_2;
                    $linetype=26;
                    $gametype=$U_75;
                    break;
                case "F2GC":
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_In_2;
                    $linetype=26;
                    $gametype=$U_75;
                    break;
                case "F3GH"://先进3球的一方
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_In_3;
                    $linetype=27;
                    $gametype=$U_80;
                    break;
                case "F3GC":
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_In_3;
                    $linetype=27;
                    $gametype=$U_80;
                    break;
                case "HGH"://最多进球的半场
                    $M_Place="上半场";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Most_Ball_In_Half;
                    $linetype=28;
                    $gametype=$U_62;
                    break;
                case "HGC":
                    $M_Place="下半场";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Most_Ball_In_Half;
                    $linetype=28;
                    $gametype=$U_62;
                    break;
                case "MGH"://最多进球的半场 - 独赢
                    $M_Place="上半场";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Most_Ball_In_Half_M;
                    $linetype=29;
                    $gametype=$U_63;
                    break;
                case "MGC":
                    $M_Place="下半场";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Most_Ball_In_Half_M;
                    $linetype=29;
                    $gametype=$U_63;
                    break;
                case "MGN":
                    $M_Place="和局";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Most_Ball_In_Half_M;
                    $linetype=29;
                    $gametype=$U_63;
                    break;
                case "SBH"://双半场进球
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Double_Half_Ball_In;
                    $linetype=30;
                    $gametype=$U_64;
                    break;
                case "SBC":
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Double_Half_Ball_In;
                    $linetype=30;
                    $gametype=$U_64;
                    break;
                case "FGS"://首个进球方式
                    $M_Place="射门";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Way;
                    $linetype=131;
                    $gametype=$U_76;
                    break;
                case "FGH":
                    $M_Place="头球";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Way;
                    $linetype=131;
                    $gametype=$U_76;
                    break;
                case "FGN":
                    $M_Place="无进球	";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Way;
                    $linetype=131;
                    $gametype=$U_76;
                    break;
                case "FGP":
                    $M_Place="点球";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Way;
                    $linetype=131;
                    $gametype=$U_76;
                    break;
                case "FGF":
                    $M_Place="任意球	";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Way;
                    $linetype=131;
                    $gametype=$U_76;
                    break;
                case "FGO":
                    $M_Place="乌龙球	";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Way;
                    $linetype=131;
                    $gametype=$U_76;
                    break;
                case "T3G1"://首个进球时间-3项
                    $M_Place="第26分钟或之前";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Time_3P;
                    $linetype=32;
                    $gametype=$U_65;
                    break;
                case "T3G2":
                    $M_Place="第27分钟或之后	";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Time_3P;
                    $linetype=32;
                    $gametype=$U_65;
                    break;
                case "T3GN":
                    $M_Place="无进球	";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Time_3P;
                    $linetype=32;
                    $gametype=$U_65;
                    break;
                case "T1G1"://首个进球时间
                    $M_Place="上半场开场 - 14:59分钟";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Time;
                    $linetype=33;
                    $gametype=$U_66;
                    break;
                case "T1G2":
                    $M_Place="15:00分钟 - 29:59分钟";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Time;
                    $linetype=33;
                    $gametype=$U_66;
                    break;
                case "T1G3":
                    $M_Place="30:00分钟 - 半场";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Time;
                    $linetype=33;
                    $gametype=$U_66;
                    break;
                case "T1G4":
                    $M_Place="下半场开场 - 59:59分钟";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Time;
                    $linetype=33;
                    $gametype=$U_66;
                    break;
                case "T1G5":
                    $M_Place="60:00分钟 - 74:59分钟";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Time;
                    $linetype=33;
                    $gametype=$U_66;
                    break;
                case "T1G6":
                    $M_Place="75:00分钟 - 全场完场";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Time;
                    $linetype=33;
                    $gametype=$U_66;
                    break;
                case "T1GN":
                    $M_Place="无进球	";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Frist_Ball_In_Time;
                    $linetype=33;
                    $gametype=$U_66;
                    break;
                case "DUAHO":	//双重机会 & 进球 大 / 小  A
                    $M_Place=$MB_Team."/和局"." & 大 1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUACO":
                    $M_Place=$TG_Team."/和局  & 大 1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUASO":
                    $M_Place=$MB_Team."/".$TG_Team." & 大 1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUAHU":
                    $M_Place=$MB_Team."/和局"." & 小  1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUACU":
                    $M_Place=$TG_Team."和局 & 小  1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUASU":
                    $M_Place=$MB_Team."/".$TG_Team." & 小  1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUBHO":	//双重机会 & 进球 大 / 小  B
                    $M_Place=$MB_Team."/和局"." & 大 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUBCO":
                    $M_Place=$TG_Team."/和局  & 大 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUBSO":
                    $M_Place=$MB_Team."/".$TG_Team." & 大 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUBHU":
                    $M_Place=$MB_Team."/和局"." & 小  2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUBCU":
                    $M_Place=$TG_Team."和局 & 小  2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUBSU":
                    $M_Place=$MB_Team."/".$TG_Team." & 小 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUCHO":	//双重机会 & 进球 大 / 小  C
                    $M_Place=$MB_Team."/和局"." & 大 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUCCO":
                    $M_Place=$TG_Team."/和局  & 大3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUCSO":
                    $M_Place=$MB_Team."/".$TG_Team." & 大 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUCHU":
                    $M_Place=$MB_Team."/和局"." & 小  3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUCCU":
                    $M_Place=$TG_Team."和局 & 小  3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUCSU":
                    $M_Place=$MB_Team."/".$TG_Team." & 小 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUDHO":	//双重机会 & 进球 大 / 小  D
                    $M_Place=$MB_Team."/和局"." & 大 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUDCO":
                    $M_Place=$TG_Team."/和局  & 大4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUDSO":
                    $M_Place=$MB_Team."/".$TG_Team." & 大 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUDHU":
                    $M_Place=$MB_Team."/和局"." & 小  4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUDCU":
                    $M_Place=$TG_Team."和局 & 小  4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DUDSU":
                    $M_Place=$MB_Team."/".$TG_Team." & 小 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_OU;
                    $linetype=34;
                    $gametype=$U_67;
                    break;
                case "DSHY":	//双重机会 & 双方球队进球
                    $M_Place=$MB_Team." / 和局   & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Double_In;
                    $linetype=35;
                    $gametype=$U_68;
                    break;
                case "DSCY":
                    $M_Place=$TG_Team." / 和局   & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Double_In;
                    $linetype=35;
                    $gametype=$U_68;
                    break;
                case "DSSY":
                    $M_Place=$MB_Team.' / '.$TG_Team." & 是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Double_In;
                    $linetype=35;
                    $gametype=$U_68;
                    break;
                case "DSHN":
                    $M_Place=$MB_Team." / 和局   & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Double_In;
                    $linetype=35;
                    $gametype=$U_68;
                    break;
                case "DSCN":
                    $M_Place=$TG_Team." / 和局   & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Double_In;
                    $linetype=35;
                    $gametype=$U_68;
                    break;
                case "DSSN":
                    $M_Place=$MB_Team.' / '.$TG_Team." & 不是";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Double_In;
                    $linetype=35;
                    $gametype=$U_68;
                    break;
                case "DGHH":	//双重机会 & 最先进球
                    $M_Place=$MB_Team." / 和局 & ".$MB_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Ball_In_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=36;
                    $gametype=$U_69;
                    break;
                case "DGCH":
                    $M_Place=$TG_Team." / 和局 & ".$MB_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Ball_In_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=36;
                    $gametype=$U_69;
                    break;
                case "DGSH":
                    $M_Place=$MB_Team.' / '.$TG_Team.'&'.$MB_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Ball_In_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=36;
                    $gametype=$U_69;
                    break;
                case "DGHC":
                    $M_Place=$MB_Team." / 和局 & ".$TG_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Ball_In_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=36;
                    $gametype=$U_69;
                    break;
                case "DGCC":
                    $M_Place=$TG_Team." / 和局 & ".$TG_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Ball_In_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=36;
                    $gametype=$U_69;
                    break;
                case "DGSC":
                    $M_Place=$MB_Team.' / '.$TG_Team.'&'.$TG_Team."(最先进球)";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Chance_Double_And_Ball_In_First;
                    $text=$Order_The_maximum_payout_is_x_per_bet.'<br>';
                    $linetype=36;
                    $gametype=$U_69;
                    break;
                case "OUEAOO":	//进球 大 / 小 & 进球 单 / 双A
                    $M_Place="单 & 大 1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEAOE":
                    $M_Place="双  & 大1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEAUO":
                    $M_Place="单  & 小  1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEAUE":
                    $M_Place="双  & 小  1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEBOO":	//进球 大 / 小 & 进球 单 / 双B
                    $M_Place="单 & 大 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEBOE":
                    $M_Place="双  & 大2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEBUO":
                    $M_Place="单  & 小  2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEBUE":
                    $M_Place="双  & 小  2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUECOO":	//进球 大 / 小 & 进球 单 / 双C
                    $M_Place="单 & 大 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUECOE":
                    $M_Place="双  & 大3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUECUO":
                    $M_Place="单  & 小  3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUECUE":
                    $M_Place="双  & 小  3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEDOO":	//进球 大 / 小 & 进球 单 / 双		D
                    $M_Place="单 & 大 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEDOE":
                    $M_Place="双  & 大4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEDUO":
                    $M_Place="单  & 小  4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUEDUE":
                    $M_Place="双  & 小  4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_OE;
                    $linetype=37;
                    $gametype=$U_70;
                    break;
                case "OUPAOH":	//进球 大 / 小 & 最先进球		A
                    $M_Place=$MB_Team."(最先进球) & 大 1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPAOC":
                    $M_Place=$TG_Team."(最先进球) & 大1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPAUH":
                    $M_Place=$MB_Team."(最先进球) & 小  1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPAUC":
                    $M_Place=$TG_Team."(最先进球) & 小  1.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPBOH":	//进球 大 / 小 & 最先进球		B
                    $M_Place=$MB_Team."(最先进球) & 大 2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPBOC":
                    $M_Place=$TG_Team."(最先进球) & 大2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPBUH":
                    $M_Place=$MB_Team."(最先进球) & 小  2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPBUC":
                    $M_Place=$TG_Team."(最先进球) & 小  2.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPCOH":	//进球 大 / 小 & 最先进球		C
                    $M_Place=$MB_Team."(最先进球) & 大 3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPCOC":
                    $M_Place=$TG_Team."(最先进球) & 大3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPCUH":
                    $M_Place=$MB_Team."(最先进球) & 小  3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPCUC":
                    $M_Place=$TG_Team."(最先进球) & 小  3.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPDOH":	//进球 大 / 小 & 最先进球		D
                    $M_Place=$MB_Team."(最先进球) & 大 4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPDOC":
                    $M_Place=$TG_Team."(最先进球) & 大4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPDUH":
                    $M_Place=$MB_Team."(最先进球) & 小  4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "OUPDUC":
                    $M_Place=$TG_Team."(最先进球) & 小  4.5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_OU_And_Ball_In_First;
                    $linetype=38;
                    $gametype=$U_71;
                    break;
                case "W3H"://三项让球投注
                    $M_Place=$MB_Team." ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_R_3;
                    $linetype=39;
                    $gametype=$U_77;
                    break;
                case "W3C":
                    $M_Place=$TG_Team." ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_R_3;
                    $linetype=39;
                    $gametype=$U_77;
                    break;
                case "W3N":
                    $M_Place="让球和局"." ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Ball_R_3;
                    $linetype=39;
                    $gametype=$U_77;
                    break;
                case "BHH"://落后反超获胜
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Fall_Catchup_And_Win;
                    $linetype=40;
                    $gametype=$U_78;
                    break;
                case "BHC":
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Fall_Catchup_And_Win;
                    $linetype=40;
                    $gametype=$U_78;
                    break;
                case "WEH"://赢得任一半场
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Win_Any_Half ;
                    $linetype=41;
                    $gametype=$U_72;
                    break;
                case "WEC":
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Win_Any_Half ;
                    $linetype=41;
                    $gametype=$U_72;
                    break;
                case "WBH"://赢得所有半场
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Win_All_Half;
                    $linetype=42;
                    $gametype=$U_73;
                    break;
                case "WBC":
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Win_All_Half;
                    $linetype=42;
                    $gametype=$U_73;
                    break;
                case "TKH"://开球球队
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Team_First_Ball;
                    $linetype=43;
                    $gametype=$U_79;
                    break;
                case "TKC":
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Team_First_Ball;
                    $linetype=43;
                    $gametype=$U_79;
                    break;
                case "OUHO"://球队进球数: 主队 - 大
                    $M_Place=$M_Place="大 ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
                    $linetype=44;
                    $gametype=$U_51.' '.$MB_Team.' - 大/小';
                    break;
                case "OUHU"://球队进球数: 主队 - 小
                    $M_Place=$M_Place="小 ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
                    $linetype=44;
                    $gametype=$U_51.' '.$MB_Team.' - 大/小';
                    break;
                case "OUCO"://球队进球数: 客队 - 大
                    $M_Place=$M_Place="大 ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
                    $linetype=44;
                    $gametype=$U_51.' '.$TG_Team.' - 大/小';
                    break;
                case "OUCU"://球队进球数: 客队 - 小
                    $M_Place=$M_Place="小 ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
                    $linetype=44;
                    $gametype=$U_51.' '.$TG_Team.' - 大/小';
                    break;
                case "HOUHO"://上半场	 球队进球数: 主队 - 大
                    $M_Place=$M_Place="大 ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
                    $linetype=144;
                    $gametype=$U_00.' '.$U_51.' '.$MB_Team.' - 大/小';
                    break;
                case "HOUHU"://上半场	球队进球数: 主队 - 小
                    $M_Place=$M_Place="小 ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Team_Ball_In.' '.$MB_Team.' - 大/小';
                    $linetype=144;
                    $gametype=$U_00.' '.$U_51.' '.$MB_Team.' - 大/小';
                    break;
                case "HOUCO"://上半场	 球队进球数: 主队 - 大
                    $M_Place=$M_Place="大 ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
                    $linetype=144;
                    $gametype=$U_00.' '.$U_51.' '.$TG_Team.' - 大/小';
                    break;
                case "HOUCU"://上半场	球队进球数: 主队 - 小
                    $M_Place=$M_Place="小 ".$detailsData['ratio_'.strtolower($rtype)];
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Team_Ball_In.' '.$TG_Team.' - 大/小';
                    $linetype=144;
                    $gametype=$U_00.' '.$U_51.' '.$TG_Team.' - 大/小';
                    break;
            }

            if(strlen($M_Rate)==0){

                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);

            }
            $line_type=$linetype;
        }
        break;
    case 'FT_f': // 半场/全场

        $GSINGLE_CREDIT=FT_F_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MBMB,MBFT,MBTG,FTMB,FTFT,FTTG,TGMB,TGFT,TGTG from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=$gid and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterSlaveDbLink,$mysql);

        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);

        }
        else{
            if($_REQUEST['id']&&$_REQUEST['id']>0){
                $moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ior_Rate = $detailsData["ior_".$rtype];
                }
                if(!$ior_Rate){
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);

                }
            }

            $M_League=$row['M_League'];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($row["MB_Team"]);
            switch ($rtype){
                case "FHH":
                    $M_Place=$MB_Team.' / '.$MB_Team;
                    if(!$ior_Rate){$ior_Rate=$row["MBMB"];}
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
                case "FHN":
                    $M_Place=$MB_Team.' / '.$Draw;
                    if(!$ior_Rate){$ior_Rate=$row["MBFT"];}
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
                case "FHC":
                    $M_Place=$MB_Team.' / '.$TG_Team;
                    if(!$ior_Rate){$ior_Rate=$row["MBTG"];}
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
                case "FNH":
                    $M_Place=$Draw.' / '.$MB_Team;
                    if(!$ior_Rate){$ior_Rate=$row["FTMB"];}
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
                case "FNN":
                    $M_Place=$Draw.' / '.$Draw;
                    if(!$ior_Rate){$ior_Rate=$row["FTFT"];}
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
                case "FNC":
                    $M_Place=$Draw.' / '.$TG_Team;
                    if(!$ior_Rate){$ior_Rate=$row["FTTG"];}
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
                case "FCH":
                    $M_Place=$TG_Team.' / '.$MB_Team;
                    if(!$ior_Rate){$ior_Rate=$row["TGMB"];}
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
                case "FCN":
                    $M_Place=$TG_Team.' / '.$Draw;
                    if(!$ior_Rate){$ior_Rate=$row["TGFT"];}
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
                case "FCC":
                    $M_Place=$TG_Team.' / '.$TG_Team;
                    if(!$ior_Rate){$ior_Rate=$row["TGTG"];}
                    $M_Rate=change_rate($open,$ior_Rate);
                    break;
            }

            if(strlen($M_Rate)==0){
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);

            }
            $line_type=7;
            $gametype=$U_09;

        }
        break;
    case 'FT_nfs': // 冠军玩法（篮球与足球公用）
        $GSINGLE_CREDIT=FS_FS_Bet;

        $mysql = "select M_Start,$mb_team as MB_Team,$m_league as M_League,$m_item as M_Item, mcount as Num,mshow as Ftype,M_Rate from ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." where `M_Start`>now() and `MID`='$gid' and Gid='$rtype' and $mb_team!=''";
        $result = mysqli_query($dbMasterLink,$mysql);
        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);

        }
        else{
            $M_League=$row['M_League'];
            $MB_Team=$row['MB_Team'];
            $TG_Team='';
            $num=$row['Num'];
            $ftype=$row['Ftype'];
            $M_Item=$row['M_Item'];
            $M_Rate=change_rate($open,$row['M_Rate']);
            if ($M_Rate==0){
                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);

            }
            $line_type=16;

        }
        $M_Place = $M_Item;
        $gametype = '冠军' ;

        break;
// -----------------------------------------  足球今日赛事、足球早盘 end

// -----------------------------------------  篮球滚球Start  nowSession:当前第几节投注 Q1 Q2 Q3 Q4
    case 'BK_rm': // 独赢

        $GSINGLE_CREDIT=BK_M_Bet;
        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate_RB,TG_Win_Rate_RB,M_Flat_Rate_RB,nowSession from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`='$gid' and Open=1 and $mb_team!=''";

        $result = mysqli_query($dbCenterMasterDbLink ,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }else{

            if($row["nowSession"]=='Q4'){ // 篮球滚球第四节不让投注
                $status = '401.44';
                $describe = $Order_Running_Ball_is_temporary_not_accepted_wagering;
                original_phone_request_response($status, $describe);
            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);
            switch ($type){
                case "H": // 全场主队独赢
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$row["MB_Win_Rate_RB"]);
                    $mtype='MH';
                    break;
                case "C":  // 全场客队独赢
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$row["TG_Win_Rate_RB"]);
                    $mtype='MC';
                    break;
                case "N":
                    $M_Place=$Draw;
                    $M_Rate=change_rate($open,$row["M_Flat_Rate_RB"]);
                    $mtype='MN';
                    break;
            }
            if ($M_Rate==0 or $M_Rate==''){
                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again.$M_Rate;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_30;
            $line_type=21;
        }

        break;
    case 'BK_re': // 让球

        $GSINGLE_CREDIT=BK_RE_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeRB,M_LetB_RB,MB_LetB_Rate_RB,TG_LetB_Rate_RB,nowSession from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink ,$mysql);

        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        } else{

            if($row["nowSession"]=='Q4'){ // 篮球滚球第四节不让投注
                $status = '401.44';
                $describe = $Order_Running_Ball_is_temporary_not_accepted_wagering;
                original_phone_request_response($status, $describe);
            }

            $detailsData=array();
            $moreMethod = array(131);
            if($_REQUEST['id']&&$_REQUEST['id']>0){
                array_push($moreMethod,$line);
            }
            if(in_array($line,$moreMethod)){
                $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ioradio_r_h = $detailsData["ior_".$rtype];
                    if(!$ioradio_r_h){
                        $status = '401.4';
                        $describe = $Order_This_match_is_closed_Please_try_again;
                        original_phone_request_response($status, $describe);
                    }
                }

            }

            $M_League=$row['M_League'];
            $MB_Team=filiter_team($row["MB_Team"]);
            $TG_Team=filiter_team($row["TG_Team"]);
            $Sign=$row['M_LetB_RB'];
            $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RB"],$row["TG_LetB_Rate_RB"],100);
            switch ($type){
                case "H":
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$rate[0]);
                    $mtype='RRH';
                    break;
                case "C":
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$rate[1]);
                    $mtype='RRC';
                    break;
            }

            if ($row['ShowTypeRB']=='C'){
                $Team=$MB_Team;
                $MB_Team=$TG_Team;
                $TG_Team=$Team;
            }
            if ($M_Rate==0 or $M_Rate=='' or $Sign==''){
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_44;
            $line_type=9;
        }

        break;
    case 'BK_rou': // 大小
        $GSINGLE_CREDIT=BK_ROU_Bet;
        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime_RB,TG_Dime_RB,MB_Dime_Rate_RB,TG_Dime_Rate_RB,nowSession from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and `MID`=$gid and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink ,$mysql);

        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        } else{
            if($row["nowSession"]=='Q4'){ // 篮球滚球第四节不让投注
                $status = '401.44';
                $describe = $Order_Running_Ball_is_temporary_not_accepted_wagering;
                original_phone_request_response($status, $describe);
            }

            $M_League=$row['M_League'];
            $MB_Team=filiter_team($row["MB_Team"]);
            $TG_Team=filiter_team($row["TG_Team"]);
            $rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB"],$row["TG_Dime_Rate_RB"],100);
            switch ($type){
                case "C":
                    $M_Place=$row["MB_Dime_RB"];
                    if ($langx=="zh-cn"){
                        $M_Place=str_replace('O','大 ',$M_Place);
                    }else if ($langx=="zh-cn"){
                        $M_Place=str_replace('O','大 ',$M_Place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $M_Place=str_replace('O','over ',$M_Place);
                    }
                    $M_Rate=change_rate($open,$rate[0]);
                    $mtype='ROUH';
                    break;
                case "H":
                    $M_Place=$row["TG_Dime_RB"];
                    if ($langx=="zh-cn"){
                        $M_Place=str_replace('U','小 ',$M_Place);
                    }else if ($langx=="zh-cn"){
                        $M_Place=str_replace('U','小 ',$M_Place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $M_Place=str_replace('U','under ',$M_Place);
                    }
                    $M_Rate=change_rate($open,$rate[1]);
                    $mtype='ROUC';
                    break;
            }
            if ($M_Rate==0 or $M_Rate=='' or $M_Place=='' or $M_Place=='O0' or $M_Place=='U0'){
                $status = '401.4';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }

            $gametype=$U_36;
            $line_type=10;
        }

        break;
    case 'BK_rt': // 单双

        $GSINGLE_CREDIT=BK_ROU_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate,nowSession from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and `MID`=$gid and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink ,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($_REQUEST['id']>0){
            $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`=".$_REQUEST['id']);
            $rowMore = mysqli_fetch_assoc($moreRes);
            $couMore = mysqli_num_rows($moreRes);
        }else{
            $couMore=0;
        }

        if($cou==0 && $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        } else{
            if($row["nowSession"]=='Q4'){ // 篮球滚球第四节不让投注
                $status = '401.44';
                $describe = $Order_Running_Ball_is_temporary_not_accepted_wagering;
                original_phone_request_response($status, $describe);
            }

            if($row['S_Single_Rate'] && $row['S_Double_Rate']){
                if($rtype=='ODD')  $ior_Rate=$row['S_Single_Rate'];
                if($rtype=='EVEN') $ior_Rate=$row['S_Double_Rate'];
            }

            if($couMore>0){
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($rtype=="ODD"){$rateFlag=$wtype.'O';}
                if($rtype=="EVEN"){$rateFlag=$wtype.'E';}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rateFlag]>0){
                    $ior_Rate = $detailsData["ior_".$rateFlag];
                }
            }

            if(!$ior_Rate){
                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);
            }
            if($cou==0){
                $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`='{$_REQUEST['id']}' and Open=1 and $mb_team!=''";
                $result = mysqli_query($dbCenterMasterDbLink ,$mysql);
                $row=mysqli_fetch_assoc($result);
                $cou=mysqli_num_rows($result);
                if($cou==0){
                    $status = '401.5';
                    $describe = $Order_This_match_is_closed_Please_try_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League=$row['M_League'];
            $MB_Team=filiter_team($row["MB_Team"]);
            $TG_Team=filiter_team($row["TG_Team"]);
            switch ($rtype){
                case "ODD":
                    $M_Place='(单)';
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='REO';
                    break;
                case "EVEN":
                    $M_Place='(双)';
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='REO';
                    break;
            }
            if ($M_Rate==0 or $M_Place=='' or $M_Place=='O0' or $M_Place=='U0'){
                $status = '401.6';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }

            $gametype=$Running_Ball.'-'.$OE;
            $line_type=105;
        }

        break;
    case 'BK_rouhc': // 球队得分大小

        $GSINGLE_CREDIT=BK_OU_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime_RB_H,MB_Dime_RB_S_H,TG_Dime_RB_H,TG_Dime_RB_S_H,MB_Dime_Rate_RB_H,MB_Dime_Rate_RB_S_H,TG_Dime_Rate_RB_H,TG_Dime_Rate_RB_S_H,MB_Dime_Rate,TG_Dime_Rate,nowSession from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink ,$mysql);

        $row=mysqli_fetch_array($result);
        $cou=mysqli_num_rows($result);
        if($cou==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        } else{
            if($row["nowSession"]=='Q4'){ // 篮球滚球第四节不让投注
                $status = '401.44';
                $describe = $Order_Running_Ball_is_temporary_not_accepted_wagering;
                original_phone_request_response($status, $describe);
            }

            if($_REQUEST['id']&&$_REQUEST['id']>0){
                $moreRes = mysqli_query($dbLink,"select details from `".DBPREFIX."match_sports_more` where `MID`=".$_REQUEST['id']);
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; } else{ $detailsData=$detailsArr[$gid];}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ior_Rate = $detailsData["ior_".$rtype];
                }
            }

            $M_League=$row['M_League'];
            $MB_Team=filiter_team($row["MB_Team"]);
            $TG_Team=filiter_team($row["TG_Team"]);
            //$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100);
            switch ($wtype){
                case "ROUH": // O 球队得分大小 主队
                    if($type =='O'){ // 大
                        $M_Place=$row['MB_Dime_RB_H'];
                        if(!$ior_Rate){$ior_Rate=$row["MB_Dime_Rate_RB_H"];}
                        $M_Rate=change_rate($open,$ior_Rate); // 赔率
                    }else{ // 小
                        $M_Place=$row['MB_Dime_RB_S_H'];
                        if(!$ior_Rate){$ior_Rate=$row["MB_Dime_Rate_RB_S_H"];}
                        $M_Rate=change_rate($open,$ior_Rate); // 赔率
                    }
                    $M_Place = $MB_Team.' '.$M_Place;
                    if ($langx=="zh-cn"){
                        $M_Place=str_replace('O','大 ',$M_Place);
                        $M_Place=str_replace('U','小 ',$M_Place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $M_Place=str_replace('O','over ',$M_Place);
                        $M_Place=str_replace('U','under ',$M_Place);
                    }
                    $teamCur="滚球-主队得分大小";
                    $mtype='ROUH';
                    break;
                case "ROUC": // U 球队得分大小 客队
                    if($type =='O'){ // 大
                        $M_Place=$row["TG_Dime_RB_H"];
                        if(!$ior_Rate){$ior_Rate=$row["TG_Dime_Rate_RB_H"];}
                        $M_Rate=change_rate($open,$ior_Rate); // 赔率
                    }else{ // 小
                        $M_Place=$row["TG_Dime_RB_S_H"];
                        if(!$ior_Rate){$ior_Rate=$row["TG_Dime_Rate_RB_S_H"];}
                        $M_Rate=change_rate($open,$ior_Rate); // 赔率
                    }
                    $M_Place = $TG_Team.' '.$M_Place;
                    if ($langx=="zh-cn"){
                        $M_Place=str_replace('O','大 ',$M_Place);
                        $M_Place=str_replace('U','小 ',$M_Place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $M_Place=str_replace('O','over ',$M_Place);
                        $M_Place=str_replace('U','under ',$M_Place);
                    }
                    $teamCur="滚球-客队得分大小";
                    $mtype='ROUC'; // 全场的大小
                    break;
            }

            if ($M_Rate==0 or $M_Rate=='' or $M_Place=='' or $M_Place=='O0' or $M_Place=='U0'){
                $status = '401.4';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }

            $gametype=$teamCur;
            $line_type=23;
        }
        break;
    case 'BK_rpd': // 滚球球队得分：最后一位数

        $GSINGLE_CREDIT=Bk_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,nowSession from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink ,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
        $rowMore = mysqli_fetch_assoc($moreRes);
        $couMore = mysqli_num_rows($moreRes);
        if($cou==0 || $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);

        } else{
            if($row["nowSession"]=='Q4'){ // 篮球滚球第四节不让投注
                $status = '401.44';
                $describe = $Order_Running_Ball_is_temporary_not_accepted_wagering;
                original_phone_request_response($status, $describe);
            }

            $detailsArr = json_decode($rowMore['details'],true);
            if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
            if($detailsData["ior_".$rtype]>0){
                $ior_Rate = $detailsData["ior_".$rtype];
            }

            if(!$ior_Rate){
                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);

            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);
            switch ($rtype){
                case "RPDH0":
                    $title=$U_90.":".$MB_Team."-".$U_91;
                    $M_Place="0 或 5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RPDH';
                    break;
                case "RPDH1":
                    $title=$U_90.":".$MB_Team."-".$U_91;
                    $M_Place="1 或 6";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RPDH';
                    break;
                case "RPDH2":
                    $title=$U_90.":".$MB_Team."-".$U_91;
                    $M_Place="2 或 7";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RPDH';
                    break;
                case "RPDH3":
                    $title=$U_90.":".$MB_Team."-".$U_91;
                    $M_Place="3 或 8";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RPDH';
                    break;
                case "RPDH4":
                    $title=$U_90.":".$MB_Team."-".$U_91;
                    $M_Place="4 或 9";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RPDH';
                    break;
                case "RPDC0":
                    $title=$U_90.":".$TG_Team."-".$U_91;
                    $M_Place="0 或 5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RPDC';
                    break;
                case "RPDC1":
                    $title=$U_90.":".$TG_Team."-".$U_91;
                    $M_Place="1 或 6";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RPDC';
                    break;
                case "RPDC2":
                    $title=$U_90.":".$TG_Team."-".$U_91;
                    $M_Place="2 或 7";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RPDC';
                    break;
                case "RPDC3":
                    $title=$U_90.":".$TG_Team."-".$U_91;
                    $M_Place="3 或 8";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RPDC';
                    break;
                case "RPDC4":
                    $title=$U_90.":".$TG_Team."-".$U_91;
                    $M_Place="4 或 9";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RPDC';
                    break;
            }
            $title = '('.$Running_Ball.') '.$title;
            $gametype=$title;
            $line_type=131;
        }
        break;
// -----------------------------------------  篮球滚球End

// -----------------------------------------  篮球今日赛事、篮球早盘Start
    case 'BK_m': // 独赢、独赢-上半场、独赢-第一节
        $GSINGLE_CREDIT=BK_M_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink ,$mysql);

        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);

        if($_REQUEST['id']>0){
            $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`=".$_REQUEST['id']);
            $rowMore = mysqli_fetch_assoc($moreRes);
            $couMore = mysqli_num_rows($moreRes);
        }else{
            $couMore=0;
        }
        if($cou==0 && $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        else{
            if($couMore>0){
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ior_Rate = $detailsData["ior_".$rtype];
                }
            }else{
                if($type=='H') $ior_Rate=$row["MB_Win_Rate"];
                if($type=='C') $ior_Rate=$row["TG_Win_Rate"];
                if($type=='N') $ior_Rate=$row["M_Flat_Rate"];
            }

            if(!$ior_Rate){
                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);

            }

            if($cou==0){
                $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`='{$_REQUEST['id']}' and Open=1 and $mb_team!=''";
                $result = mysqli_query($dbCenterMasterDbLink ,$mysql);
                $row=mysqli_fetch_assoc($result);
                $cou=mysqli_num_rows($result);
                if($cou==0){
                    $status = '401.5';
                    $describe = $Order_This_match_is_closed_Please_try_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);
            switch ($type){
                case "H": // 全场主队独赢
                    $M_Place=$MB_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='MH';
                    break;
                case "C":  // 全场客队独赢
                    $M_Place=$TG_Team;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='MC';
                    break;
                case "N":
                    $M_Place=$Draw;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='MN';
                    break;
            }
            if ($M_Rate==0 || $M_Rate==''){
                $status = '401.6';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_30;
            $line_type=1;
        }

        break;
    case 'BK_r': // 让球、让球-上半场、让球  - 第一节

        $GSINGLE_CREDIT=BK_R_Bet;
        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeR,MB_LetB_Rate,TG_LetB_Rate,M_LetB from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink ,$mysql);

        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($_REQUEST['id']>0){
            $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`=".$_REQUEST['id']);
            $rowMore = mysqli_fetch_assoc($moreRes);
            $couMore = mysqli_num_rows($moreRes);
        }else{
            $couMore=0;
        }
        if($cou==0 && $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        else{
            if($couMore>0){ // 更多赛事
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $ior_Rate = $detailsData["ior_".$rtype];
                    if($type=="H"){$Sign=$detailsData["ratio"];}
                    if($type=="C"){$Sign=$detailsData["ratio"];}
                }
            }else{ // 普通赛事
                $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100);
                $Sign=$row['M_LetB'];
                if($type=='H') $ior_Rate=$rate[0];
                if($type=='C') $ior_Rate=$rate[1];
            }
            if(!$ior_Rate){
                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);

            }

            if($cou==0){
                $mysql = "select * from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`='{$_REQUEST['id']}' and Open=1 and $mb_team!=''";
                $result = mysqli_query($dbCenterMasterDbLink ,$mysql);
                $row=mysqli_fetch_assoc($result);
                $cou=mysqli_num_rows($result);
                if($cou==0){
                    $status = '401.5';
                    $describe = $Order_This_match_is_closed_Please_try_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League=$row['M_League'];
            $MB_Team=filiter_team($row["MB_Team"]);
            $TG_Team=filiter_team($row["TG_Team"]);
            switch ($type){
                case "H":
                    $M_Place=$MB_Team.' '.$M_Place;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RH';
                    break;
                case "C":
                    $M_Place=$TG_Team.' '.$M_Place;
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='RC';
                    break;
            }
            if ($row['ShowTypeR']=='C'){
                $Team=$MB_Team;
                $MB_Team=$TG_Team;
                $TG_Team=$Team;
            }

            if ($M_Rate==0 or $M_Rate=='' or $Sign==''){
                $status = '401.6';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }

            $gametype=$U_43;
            $line_type=2;
        }

        break;
    case 'BK_ou': // 总分：大小、总分: 大 / 小  - 上半场、总分: 大 / 小  - 第一节

        $GSINGLE_CREDIT=BK_OU_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink ,$mysql);

        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        if($_REQUEST['id']>0){
            $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`=".$_REQUEST['id']);
            $rowMore = mysqli_fetch_assoc($moreRes);
            $couMore = mysqli_num_rows($moreRes);
        }else{
            $couMore=0;
        }
        if($cou==0 && $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        else{

            if($couMore>0){
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
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
                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);
            }

            $M_League=$row['M_League'];
            $MB_Team=filiter_team($row["MB_Team"]);
            $TG_Team=filiter_team($row["TG_Team"]);
            switch ($type){
                case "C":
                    if ($langx=="zh-cn"){
                        $M_Place=str_replace('O','大 ',$M_Place);
                    }else if ($langx=="zh-cn"){
                        $M_Place=str_replace('O','大 ',$M_Place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $M_Place=str_replace('O','over ',$M_Place);
                    }
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='OUH';
                    break;
                case "H":
                    if ($langx=="zh-cn"){
                        $M_Place=str_replace('U','小 ',$M_Place);
                    }else if ($langx=="zh-cn"){
                        $M_Place=str_replace('U','小 ',$M_Place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $M_Place=str_replace('U','under ',$M_Place);
                    }
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='OUC';
                    break;
            }
            if ($M_Rate==0 or $M_Rate=='' or $M_Place=='' or $M_Place=='O0' or $M_Place=='U0'){
                $status = '401.5';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }
            $gametype=$U_03;
            $line_type=3;
        }

        break;
    case 'BK_t': // 总分单双、总分: 单 / 双  - 上半场、总分: 单 / 双  - 第一节

        $GSINGLE_CREDIT=BK_EO_Bet;

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink ,$mysql);

        $row=mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);

        if($_REQUEST['id']>0){
            $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`=".$_REQUEST['id']);
            $rowMore = mysqli_fetch_assoc($moreRes);
            $couMore = mysqli_num_rows($moreRes);
        }else{
            $couMore=0;
        }

        if($cou==0 && $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        else{

            if($row['S_Single_Rate'] && $row['S_Double_Rate']){
                if($rtype=='ODD')  $ior_Rate=$row['S_Single_Rate'];
                if($rtype=='EVEN') $ior_Rate=$row['S_Double_Rate'];
            }

            if($couMore>0){
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($rtype=="ODD"){$rateFlag=$wtype.'O';}
                if($rtype=="EVEN"){$rateFlag=$wtype.'E';}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rateFlag]>0){
                    $ior_Rate = $detailsData["ior_".$rateFlag];
                }
            }

            if(!$ior_Rate){
                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);
            }

            if($cou==0){
                $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,S_Single_Rate,S_Double_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `m_start`>now() and `MID`='{$_REQUEST['id']}' and Open=1 and $mb_team!=''";
                $result = mysqli_query($dbCenterMasterDbLink ,$mysql);
                $row=mysqli_fetch_assoc($result);
                $cou=mysqli_num_rows($result);
                if($cou==0){
                    $status = '401.5';
                    $describe = $Order_This_match_is_closed_Please_try_again;
                    original_phone_request_response($status, $describe);
                }
            }

            $M_League=$row['M_League'];
            $MB_Team=filiter_team($row["MB_Team"]);
            $TG_Team=filiter_team($row["TG_Team"]);
            switch ($rtype){
                case "ODD":  // 单
                    $M_Place="(".$Order_Odd.")";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Odd_Even_betting_order;
                    $line_type=5;
                    break;
                case "EVEN":  // 双
                    $M_Place="(".$Order_Even.")";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $caption=$Order_Odd_Even_betting_order;
                    $line_type=5;
                    break;
            }
            if($rtype=='EVEN' or $rtype=='ODD'){
                $gametype=$U_31;
            }else{
                $gametype=$U_41;
            }

        }

        break;
    case 'BK_ouhc': // 球队得分大小、球队得分 - 大 / 小 - 上半场、球队得分: - 大 / 小 - 第一节

        $GSINGLE_CREDIT=BK_OU_Bet;
        if($_REQUEST['id']&&$_REQUEST['id']>0){
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime_H,MB_Dime_S_H,TG_Dime_H,TG_Dime_S_H,MB_Dime_Rate_H,MB_Dime_Rate_S_H,TG_Dime_Rate_H,TG_Dime_Rate_S_H,MB_Dime_Rate,TG_Dime_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=".$_REQUEST['id']." and Open=1 and $mb_team!=''";
        }else{
            $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Dime_H,MB_Dime_S_H,TG_Dime_H,TG_Dime_S_H,MB_Dime_Rate_H,MB_Dime_Rate_S_H,TG_Dime_Rate_H,TG_Dime_Rate_S_H,MB_Dime_Rate,TG_Dime_Rate from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`=".$gid." and Open=1 and $mb_team!=''";
        }

        $result = mysqli_query($dbCenterMasterDbLink,$mysql);
        $row=mysqli_fetch_array($result);
        $cou=mysqli_num_rows($result);

        if($_REQUEST['id']&&$_REQUEST['id']>0){
            $moreRes = mysqli_query($dbLink,"select details from `".DBPREFIX."match_sports_more` where `MID`=".$_REQUEST['id']);
            $rowMore = mysqli_fetch_assoc($moreRes);
            $couMore = mysqli_num_rows($moreRes);
        }else{
            $couMore = 0;
        }

        if($cou==0 && $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);
        }
        else{
            if($_REQUEST['id'] && $_REQUEST['id']>0 && count($rowMore)>0){
                $detailsArr = json_decode($rowMore['details'],true);
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                    $mplaceKey='ratio_'.strtolower($rtype);
                    $M_Place = $detailsData[$mplaceKey];
                    $ior_Rate = $detailsData["ior_".$rtype];
                }
            }

            $M_League=$row['M_League'];
            $MB_Team=filiter_team($row["MB_Team"]);
            $TG_Team=filiter_team($row["TG_Team"]);

            switch ($wtype){
                case "OUH": // O 球队得分大小 主队
                    //$rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_H"],$row["MB_Dime_Rate_S_H"],100);
                    if($type =='O'){ // 大
                        if(!$M_Place)$M_Place=$row['MB_Dime_H'];
                        if(!$ior_Rate){$ior_Rate=$row["MB_Dime_Rate_H"];}
                        $M_Rate=change_rate($open,$ior_Rate); // 赔率
                    }else{ // 小
                        if(!$M_Place)$M_Place=$row['MB_Dime_S_H'];
                        if(!$ior_Rate){$ior_Rate=$row["MB_Dime_Rate_S_H"];}
                        $M_Rate=change_rate($open,$ior_Rate); // 赔率
                    }
                    $M_Place = $MB_Team.' '.$M_Place;
                    if ($langx=="zh-cn"){
                        $M_Place=str_replace('O','大 ',$M_Place);
                        $M_Place=str_replace('U','小 ',$M_Place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $M_Place=str_replace('O','over ',$M_Place);
                        $M_Place=str_replace('U','under ',$M_Place);
                    }
                    $teamCur="主队得分大小";
                    $mtype='OUH'; // 上半场的大小
                    break;
                case "OUC": // U 球队得分大小 客队
                    //$rate=get_other_ioratio($odd_f_type,$row["TG_Dime_Rate_H"],$row["TG_Dime_Rate_S_H"],100);
                    if($type =='O'){ // 大
                        if(!$M_Place)$M_Place=$row["TG_Dime_H"];
                        if(!$ior_Rate){$ior_Rate=$row["TG_Dime_Rate_H"];}
                        $M_Rate=change_rate($open,$ior_Rate); // 赔率
                    }else{ // 小
                        if(!$M_Place)$M_Place=$row["TG_Dime_S_H"];
                        if(!$ior_Rate){$ior_Rate=$row["TG_Dime_Rate_S_H"];}
                        $M_Rate=change_rate($open,$ior_Rate); // 赔率
                    }
                    $M_Place = $TG_Team.' '.$M_Place;
                    if ($langx=="zh-cn"){
                        $M_Place=str_replace('O','大 ',$M_Place);
                        $M_Place=str_replace('U','小 ',$M_Place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $M_Place=str_replace('O','over ',$M_Place);
                        $M_Place=str_replace('U','under ',$M_Place);
                    }
                    $teamCur="客队得分大小";
                    $mtype='OUC'; // 全场的大小
                    break;
            }

            if ($M_Rate==0 or $M_Rate=='' or $M_Place=='' or $M_Place=='O0' or $M_Place=='U0'){
                $status = '401.4';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }

            $gametype=$teamCur;
            $line_type=13;
        }

        break;
    case 'BK_pd': // 球队得分：最后一位数

        $GSINGLE_CREDIT=Bk_Bet; //篮球默认单注限额

        $mysql = "select M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and `MID`='$gid' and Open=1 and $mb_team!=''";
        $result = mysqli_query($dbCenterMasterDbLink,$mysql);
        $row = mysqli_fetch_assoc($result);
        $cou=mysqli_num_rows($result);
        $moreRes = mysqli_query($dbLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
        $rowMore = mysqli_fetch_assoc($moreRes);
        $couMore = mysqli_num_rows($moreRes);

        if($cou==0 || $couMore==0){
            $status = '401.3';
            $describe = $Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status, $describe);

        }
        else{
            $detailsArr = json_decode($rowMore['details'],true);
            if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
            if($detailsData['sw_'.$wtype]=="Y" && $detailsData["ior_".$rtype]>0){
                $ior_Rate = $detailsData["ior_".$rtype];
            }

            if(!$ior_Rate){
                $status = '401.4';
                $describe = $Order_Odd_changed_please_bet_again;
                original_phone_request_response($status, $describe);

            }

            $M_League=$row['M_League'];
            $MB_Team=$row["MB_Team"];
            $TG_Team=$row["TG_Team"];
            $MB_Team=filiter_team($MB_Team);

            switch ($rtype){
                case "PDH0":
                    $title=$U_90.":".$MB_Team."-".$U_91;
                    $M_Place="0 或 5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='PDH';
                    break;
                case "PDH1":
                    $title=$U_90.":".$MB_Team."-".$U_91;
                    $M_Place="1 或 6";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='PDH';
                    break;
                case "PDH2":
                    $title=$U_90.":".$MB_Team."-".$U_91;
                    $M_Place="2 或 7";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='PDH';
                    break;
                case "PDH3":
                    $title=$U_90.":".$MB_Team."-".$U_91;
                    $M_Place="3 或 8";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='PDH';
                    break;
                case "PDH4":
                    $title=$U_90.":".$MB_Team."-".$U_91;
                    $M_Place="4 或 9";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='PDH';
                    break;
                case "PDC0":
                    $title=$U_90.":".$TG_Team."-".$U_91;
                    $M_Place="0 或 5";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='PDC';
                    break;
                case "PDC1":
                    $title=$U_90.":".$TG_Team."-".$U_91;
                    $M_Place="1 或 6";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='PDC';
                    break;
                case "PDC2":
                    $title=$U_90.":".$TG_Team."-".$U_91;
                    $M_Place="2 或 7";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='PDC';
                    break;
                case "PDC3":
                    $title=$U_90.":".$TG_Team."-".$U_91;
                    $M_Place="3 或 8";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='PDC';
                    break;
                case "PDC4":
                    $title=$U_90.":".$TG_Team."-".$U_91;
                    $M_Place="4 或 9";
                    $M_Rate=change_rate($open,$ior_Rate);
                    $mtype='PDC';
                    break;
            }
            $gametype=$title;
            $line_type=31;

        }
        break;
// -----------------------------------------  篮球今日赛事、篮球早盘End

    default:
        $status = '402';
        $describe = "order_method参数有误";
        original_phone_request_response($status, $describe);
        break;
}

// 篮球上半场html标签处理
if(strpos($order_method, 'BK') !== false){
    $MB_Team = str_replace('<font color=gray>','',$MB_Team);
    $MB_Team = str_replace('</font>','',$MB_Team);
    $TG_Team = str_replace('<font color=gray>','',$TG_Team);
    $TG_Team = str_replace('</font>','',$TG_Team);
    $M_Place = str_replace('<font color=gray>','',$M_Place);
    $M_Place = str_replace('</font>','',$M_Place);

    $gameswitch = judgeBetSwitch('BK') ; // 篮球投注开关
    if($gameswitch){ // 停用 篮球

        $status = '401.20';
        $describe = $Order_This_match_is_closed_Please_try_again;
        original_phone_request_response($status, $describe);

    }
}

$aData[0]['leag'] = $M_League;
$aData[0]['gametype'] = $detailsData['description'].' '.$gametype; // 全场 滚球 - 让球
$aData[0]['MB_Team'] = $MB_Team; // 队伍名称（显示在前面）
$aData[0]['TG_Team'] = $TG_Team; // 队伍名称（显示在后面）
$aData[0]['sign'] = is_null($Sign)?'':$Sign; // 让几个球
$aData[0]['ShowTypeRB'] = is_null($row['ShowTypeRB'])?'':$row['ShowTypeRB']; // 足球滚球、篮球滚球谁让（滚球让球专用）
$aData[0]['ShowTypeR'] = is_null($row['ShowTypeR'])?'':$row['ShowTypeR']; // 谁让（让球专用）
$aData[0]['inball'] = is_null($inball)?'':$inball; // 几比几   ShowTypeRB ShowTypeR 为C时，显示（客队进球数：主队进球数）
$aData[0]['M_Place'] = $M_Place; // 投注的位置（主队、客队 或者 和）
if($wtype=='FS'){
    $aData[0]['minBet'] = 50;
}else{
    $aData[0]['minBet'] = $GMIN_SINGLE;
}
$aData[0]['maxBet'] = $GSINGLE_CREDIT;
$aData[0]['line_type'] = $line_type;
$aData[0]['type'] = $type;
$aData[0]['rtype'] = $rtype;
$aData[0]['wtype'] = $wtype;
$aData[0]['gnum'] = is_null($gnum)?'':$gnum;
$aData[0]['ioradio_r_h'] = $M_Rate;
$aData[0]['odd_f_type'] = $odd_f_type;
$aData[0]['dataSou'] = is_null($dataSou)?'':$dataSou;


$status = '200';
$describe = 'success';
original_phone_request_response($status, $describe, $aData);
