<?php
/**
 * /order/order_prepare_p3_api.php?teamcount=4&game=PRH,PRH,PRH,POUC&game_id=3363442,3363572,3363582,3363562  足球综合过关选择玩法和赔率，准备投注接口
 * @param  game
 * @param  game_id
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');
//include "../include/address.mem.php";
//include_once('../include/config.inc.php');
//require_once("../../../common/sportCenterData.php");
//require ("../include/define_function_list.inc.php");
//require ("../include/curl_http.php");

$aGame = explode(',',$_REQUEST['game']);
$aGameId = explode(',',$_REQUEST['game_id']);
$aGidFs = explode(',',$_REQUEST['gid_fs']);

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status='401.1';
    $describe="请重新登录";
    original_phone_request_response($status,$describe);
}

//if ($error_flag==1){
//    $status='401.2';
//    $describe=$Order_Odd_changed_please_bet_again;
//    original_phone_request_response($status,$describe);
//}

if (count($aGame)>10){
    $status='401.6';
    $describe="不接受超过10串过关投注！";
    original_phone_request_response($status,$describe);
}

$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language']?$_SESSION['Language']:'zh-cn';
//require ("../include/traditional.$langx.inc.php");
$memname=$_SESSION['UserName'];
$open=$_SESSION['OpenType'];

$btset=singleset('P3');
$GMIN_SINGLE=$btset[0];
$GSINGLE_CREDIT=FT_PR_Bet;

foreach ($aGameId as $k =>$v){

    $res = $aGame[$k];
    if ($res!=""){
        $gid = $v;
        $gid_fs=$aGidFs[$k];
        $sign=$place=$ior_Rate='';

        /*$havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and FIND_IN_SET($gid,MID)>0 and linetype=8 and (Active=1 or Active=11)";
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
        }*/

        //先判断本地库
        /*$mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and MID='$gid' and Cancel!=1 and Open=1 and MB_Team!='' and MB_Team_tw!=''";//判断赛事是否关闭
        $resultL = mysqli_query($dbLink,$mysqlL);
        $couL=mysqli_num_rows($resultL);
        if ($couL==0){
            $status='401.3';
            $describe=$gid.$Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status,$describe);
        }*/

        // 获取附属盘口的赔率【今日赛事的让球、大小】
        $aRes = ['PRH','PRC','POUC','POUH','HPRH','HPRC','HPOUC','HPOUH'];
        $oeRes = ['PO','PE','PODD','PEVEN','PMH','PMC','PMN','HPMH','HPMC','HPMN'];    //附属盘口 【单双】
        $detailsData = [];
        $ior_Rate=$sign=$place = '';
//        if (in_array($res,$aRes)){
            if ($gid!=$gid_fs and $gid_fs!=''){ // 附属盘口从更多玩法中获取赔率、让球数、大小数
                $moreRes = mysqli_query($dbMasterLink, "select details from " . DBPREFIX . "match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'], true);
                if ($gid!=$gid_fs){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                // 串关需要转换为对应的 wtype  rtype，获取对应的数据
                switch ($res){
                    case 'PRH':
                        $wtype='R'; $rtype='RH';
                        break;
                    case 'PRC':
                        $wtype='R'; $rtype='RC';
                        break;
                    case 'POUC':
                        $wtype='OU'; $rtype='OUC';
                        break;
                    case 'POUH':
                        $wtype='OU'; $rtype='OUH';
                        break;
                    case 'HPRH':
                        $wtype='HR'; $rtype='HRH';
                        break;
                    case 'HPRC':
                        $wtype='HR'; $rtype='HRC';
                        break;
                    case 'HPOUC':
                        $wtype='HOU'; $rtype='HOUC';
                        break;
                    case 'HPOUH':
                        $wtype='HOU'; $rtype='HOUH';
                        break;
                    case 'PMH': // 独赢的兼容角球的准备投注
                        $wtype='M'; $rtype='MH';
                        break;
                    case 'PMN':
                        $wtype='M'; $rtype='MN';
                        break;
                    case 'PMC':
                        $wtype='M'; $rtype='MC';
                        break;
                    case 'HPMH':
                        $wtype='HM'; $rtype='HMH';
                        break;
                    case 'HPMN':
                        $wtype='HM'; $rtype='HMN';
                        break;
                    case 'HPMC':
                        $wtype='HM'; $rtype='HMC';
                        break;
                    case 'PO':  //附属盘口单
                        $wtype='EO'; $rtype='EOO';
                        break;
                    case 'PE':  //附属盘口双
                        $wtype='EO'; $rtype='EOE';
                        break;
                }

                if ($detailsData['sw_' . $wtype] == "Y" && $detailsData["ior_" . $rtype] > 0) {
                    $ior_Rate = $detailsData["ior_" . $rtype];
                    if ((in_array($res,$aRes) or $detailsData['description']=='角球') && !in_array($res, $oeRes)) {
                        $ior_Rate += 1; // 让球、大小，以及角球的非独赢的所有玩法可以从所有玩法获取赔率 然后加1
                    }
                    switch ($res){
                        case 'PRH':
                        case 'PRC':
                            $sign=$detailsData["ratio"];
                            break;
                        case 'POUC':
                            $place='O '.$detailsData["ratio_o"];
                            break;
                        case 'POUH':
                            $place='U '.$detailsData["ratio_u"];
                            break;
                        case 'HPRH':
                        case 'HPRC':
                            $sign=$detailsData["hratio"];
                            break;
                        case 'HPOUC':
                            $place='O '.$detailsData["ratio_ho"];
                            break;
                        case 'HPOUH':
                            $place='U '.$detailsData["ratio_hu"];
                            break;
                    }
                }
                if (!$ior_Rate) {
                    $status = '401.4';
                    $describe = $Order_Odd_changed_please_bet_again;
                    original_phone_request_response($status, $describe);
                }
            }
//        }

        //在判断数据中心
        $mysql = "select MID,M_Date,M_Time,MB_MID,TG_MID,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeP,MB_P_Win_Rate,TG_P_Win_Rate,M_P_Flat_Rate,MB_P_LetB_Rate,TG_P_LetB_Rate,M_P_LetB,MB_P_Dime,TG_P_Dime,MB_P_Dime_Rate,TG_P_Dime_Rate,S_P_Single_Rate,S_P_Double_Rate,ShowTypeP,MB_LetB_Rate_H,TG_LetB_Rate_H,M_LetB_H,MB_Dime_H,MB_Dime_S_H,TG_Dime_H,TG_Dime_S_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,MB_P_LetB_Rate_H,TG_P_LetB_Rate_H,MB_P_Dime_Rate_H,MB_P_Dime_Rate_S_H,TG_P_Dime_Rate_H,TG_P_Dime_Rate_S_H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  `M_Start`>now() and MID='$gid' and Open=1 and MB_Team!='' and MB_Team_tw!=''";//判断赛事是否关闭
        $result = mysqli_query($dbCenterMasterDbLink,$mysql);
        $cou=mysqli_num_rows($result);

        if ($cou==0){
            $status='401.3';
            $describe=$gid.$Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status,$describe);
        }
        $row = mysqli_fetch_assoc($result);
//        $pdate=$row['M_Date'];
//        $start=$row['M_Start'];
        $league=$row['M_League'];
        $s_mb_team = filiter_team($row['MB_Team']);
        $s_tg_team = filiter_team($row['TG_Team']);
//        $mb_mid=$row['MB_MID'];
//        $tg_mid=$row['TG_MID'];
        if(in_array($row['MB_Team'],$teama_arr)){
            $status='401.4';
            $describe=$gid.$Order_The_game_is_covered_same_teams_Please_reset_again;
            original_phone_request_response($status,$describe);

        }else{
            $teama_arr[]=$row['MB_Team'];
        }

        switch($res){
            case 'PMH': // 全场独赢主队
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["MB_P_Win_Rate"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["MB_P_Win_Rate"]); }else{ $rate = round_num($ior_Rate); }
                }
                $place=$s_mb_team;
                $sign   = '';
                $mmid="(".$row['MB_MID'].")";
                $showtypeT=$U_30;
                break;
            case 'PMC': // 全场独赢客队
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["TG_P_Win_Rate"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["TG_P_Win_Rate"]); }else{ $rate = round_num($ior_Rate); }
                }
                $place=$s_tg_team;
                $sign   = '';
                $mmid="(".$row['TG_MID'].")";
                $showtypeT=$U_30;
                break;
            case 'PMN': // 全场独赢和局
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["M_P_Flat_Rate"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["M_P_Flat_Rate"]); }else{ $rate = round_num($ior_Rate); }
                }
                $place=$Draw;
                $sign   = '';
                $mmid="";
                $showtypeT=$U_30;
                break;
            case 'PRH':  // 全场主队让球
                $place=$s_mb_team;
                if ($row['ShowTypeP']=='C'){
                    $team=$s_mb_team;
                    $s_mb_team=$s_tg_team;
                    $s_tg_team=$team;
                }
                if (!$sign and $sign!='0')$sign=$row['M_P_LetB'];
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["MB_P_LetB_Rate"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["MB_P_LetB_Rate"]); }else{ $rate = round_num($ior_Rate); }
                }
                $mmid="(".$row['MB_MID'].")";
                $showtypeT=$U_26;
                break;
            case 'PRC':   // 全场客队让球
                $place=$s_tg_team;
                if ($row['ShowTypeP']=='C'){
                    $team=$s_mb_team;
                    $s_mb_team=$s_tg_team;
                    $s_tg_team=$team;
                }
                if (!$sign and $sign!='0')$sign=$row['M_P_LetB'];
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["TG_P_LetB_Rate"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["TG_P_LetB_Rate"]); }else{ $rate = round_num($ior_Rate); }
                }
                $showtypeT=$U_26;
                $mmid="(".$row['TG_MID'].")";
                break;
            case 'POUC':    // 主队大小
                if (!$place)$place=$row['MB_P_Dime'];
                if ($langx=="zh-cn"){
                    $place=str_replace('O','大 ',$place);
                }else if ($langx=="zh-cn"){
                    $place=str_replace('O','大 ',$place);
                }else if ($langx=="en-us" or $langx=="th-tis"){
                    $place=str_replace('O','over ',$place);
                }
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["MB_P_Dime_Rate"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["MB_P_Dime_Rate"]); }else{ $rate = round_num($ior_Rate); }
                }
                $sign='';
                $mmid="(".$row['MB_MID'].")";
                $showtypeT=$U_27;
                break;
            case 'POUH':  // 客队大小
                if (!$place)$place=$row['TG_P_Dime'];
                if ($langx=="zh-cn"){
                    $place=str_replace('U','小 ',$place);
                }else if ($langx=="zh-cn"){
                    $place=str_replace('U','小 ',$place);
                }else if ($langx=="en-us" or $langx=="th-tis"){
                    $place=str_replace('U','under ',$place);
                }
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["TG_P_Dime_Rate"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["TG_P_Dime_Rate"]); }else{ $rate = round_num($ior_Rate); }
                }
                $sign='';
                $showtypeT=$U_27;
                $mmid="(".$row['TG_MID'].")";
                break;
            case 'PO': // 单
            case 'PODD': // 单
                //$rate=change_rate($open,$row['S_P_Single_Rate']);
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["S_P_Single_Rate"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["S_P_Single_Rate"]); }else{ $rate = round_num($ior_Rate); }
                }
                $place="(".$Order_Odd.")";
                $sign   = '';
                $mmid="(".$row['MB_MID'].")";
                $showtypeT=$U_31;
                break;
            case 'PE': // 双
            case 'PEVEN': // 双
                //$rate=change_rate($open,$row['S_P_Double_Rate']);
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["S_P_Double_Rate"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["S_P_Double_Rate"]); }else{ $rate = round_num($ior_Rate); }
                }
                $place="(".$Order_Even.")";
                $sign   = '';
                $mmid="(".$row['TG_MID'].")";
                $showtypeT=$U_31;
                break;
            case 'HPMH':  // 下半场 独赢 主队
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["MB_Win_Rate_H"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["MB_Win_Rate_H"]); }else{ $rate = round_num($ior_Rate); }
                }
                $place=$s_mb_team;
                $sign   = '';
                $mmid="(".$row['MB_MID'].")";
                $showtypeT=$U_32;
                break;
            case 'HPMC': // 下半场 独赢 客队
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["TG_Win_Rate_H"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["TG_Win_Rate_H"]); }else{ $rate = round_num($ior_Rate); }
                }
                $place=$s_tg_team;
                $sign   = '';
                $mmid="(".$row['TG_MID'].")";
                $showtypeT=$U_32;
                break;
            case 'HPMN': // 下半场 独赢 和局
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["M_Flat_Rate_H"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["M_Flat_Rate_H"]); }else{ $rate = round_num($ior_Rate); }
                }
                $place=$Draw;
                $sign   = '';
                $mmid="";
                $showtypeT=$U_32;
                break;
            case 'HPRH': // 半场让球主队
            case 'HPRC': // 半场让球客队
                if ($res=='HPRH'){
                    $place=$s_mb_team;
//                    $rate=change_rate($open,$row["MB_P_LetB_Rate_H"]) ;
                    if ($flushWay=='ra'){
                        if(!$ior_Rate)$ior_Rate=$row["MB_P_LetB_Rate_H"];
                        $rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!$ior_Rate){ $rate = round_num($row["MB_P_LetB_Rate_H"]); }else{ $rate = round_num($ior_Rate); }
                    }
                    if ($row['ShowTypeP']=='C'){
                        $team=$s_mb_team;
                        $s_mb_team=$s_tg_team;
                        $s_tg_team=$team;
                    }
                    $mmid="(".$row['MB_MID'].")";
                }else{
                    $place=$s_tg_team;
//                    $rate=change_rate($open,$row["TG_P_LetB_Rate_H"]) ;
                    if ($flushWay=='ra'){
                        if(!$ior_Rate)$ior_Rate=$row["TG_P_LetB_Rate_H"];
                        $rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!$ior_Rate){ $rate = round_num($row["TG_P_LetB_Rate_H"]); }else{ $rate = round_num($ior_Rate); }
                    }
                    if ($row['ShowTypeP']=='C'){
                        $team=$s_mb_team;
                        $s_mb_team=$s_tg_team;
                        $s_tg_team=$team;
                    }

                    $mmid="(".$row['TG_MID'].")";
                }
                if (!$sign and $sign!='0')$sign=$row['M_LetB_H'];
                $showtypeT=$U_34;

                break;
            case 'HPOUC': // 半场大小主队
                if (!$place)$place=$row['MB_Dime_H'];
                if ($langx=="zh-cn"){
                    $place=str_replace('O','大 ',$place);
                }else if ($langx=="zh-cn"){
                    $place=str_replace('O','大 ',$place);
                }else if ($langx=="en-us" or $langx=="th-tis"){
                    $place=str_replace('O','over ',$place);
                }
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["MB_P_Dime_Rate_H"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["MB_P_Dime_Rate_H"]); }else{ $rate = round_num($ior_Rate); }
                }
                $sign='';
                $mmid="(".$row['MB_MID'].")";
                $showtypeT=$U_33;
                break;
            case 'HPOUH': // 半场大小客队
                if (!$place)$place=$row['TG_Dime_H'];
                if ($langx=="zh-cn"){
                    $place=str_replace('U','小 ',$place);
                }else if ($langx=="zh-cn"){
                    $place=str_replace('U','小 ',$place);
                }else if ($langx=="en-us" or $langx=="th-tis"){
                    $place=str_replace('U','under ',$place);
                }
                if ($flushWay=='ra'){
                    if(!$ior_Rate)$ior_Rate=$row["TG_P_Dime_Rate_H"];
                    $rate = change_rate($open, $ior_Rate);
                }else{
                    if(!$ior_Rate){ $rate = round_num($row["TG_P_Dime_Rate_H"]); }else{ $rate = round_num($ior_Rate); }
                }
                $sign='';
                $mmid="(".$row['TG_MID'].")";
                $showtypeT=$U_33;
                break;

            case 'POUHO': // 综合过关，得分大小，主队大   篮球
            case 'POUHU': // 综合过关，得分大小，主队小
                if ($res=='POUHO'){
                    if (!$place)$place=$row['MB_Dime_H'];
                    if ($langx=="zh-cn"){
                        $place=str_replace('O','大 ',$place);
                    }else if ($langx=="zh-cn"){
                        $place=str_replace('O','大 ',$place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $place=str_replace('O','over ',$place);
                    }
                    if ($flushWay=='ra'){
                        if(!$ior_Rate)$ior_Rate=$row["MB_P_Dime_Rate_H"];
                        $rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!$ior_Rate){$ior_Rate = round_num($row["MB_P_Dime_Rate_H"]); }
                        $rate = round_num($ior_Rate);
                    }
                }elseif($res=='POUHU'){
                    if (!$place)$place=$row['MB_Dime_S_H'];
                    if ($langx=="zh-cn"){
                        $place=str_replace('U','小 ',$place);
                    }else if ($langx=="zh-cn"){
                        $place=str_replace('U','小 ',$place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $place=str_replace('U','under ',$place);
                    }
                    if ($flushWay=='ra'){
                        if(!$ior_Rate)$ior_Rate=$row["MB_P_Dime_Rate_S_H"];
                        $rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!$ior_Rate){$ior_Rate = round_num($row["MB_P_Dime_Rate_S_H"]); }
                        $rate = round_num($ior_Rate);
                    }
                }
                $sign='';   //VS.
                $mmid="(".$row['MB_MID'].")";
                $showtypeT="球队得分大小";
                break;
            case 'POUCO': // 综合过关，得分大小，客队大
            case 'POUCU': // 综合过关，得分大小，客队小
                if ($res=='POUCO'){
                    if (!$place)$place=$row['TG_Dime_H'];
                    if ($langx=="zh-cn"){
                        $place=str_replace('O','大 ',$place);
                    }else if ($langx=="zh-cn"){
                        $place=str_replace('O','大 ',$place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $place=str_replace('O','over ',$place);
                    }
                    if ($flushWay=='ra'){
                        if(!$ior_Rate)$ior_Rate=$row["TG_P_Dime_Rate_H"];
                        $rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!$ior_Rate){$ior_Rate = round_num($row["TG_P_Dime_Rate_H"]); }
                        $rate = round_num($ior_Rate);
                    }
                }elseif($res=='POUCU'){
                    if (!$place)$place=$row['TG_Dime_S_H'];
                    if ($langx=="zh-cn"){
                        $place=str_replace('U','小 ',$place);
                    }else if ($langx=="zh-cn"){
                        $place=str_replace('U','小 ',$place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $place=str_replace('U','under ',$place);
                    }
                    if ($flushWay=='ra'){
                        if(!$ior_Rate)$ior_Rate=$row["TG_P_Dime_Rate_S_H"];
                        $rate = change_rate($open, $ior_Rate);
                    }else{
                        if(!$ior_Rate){$ior_Rate = round_num($row["TG_P_Dime_Rate_S_H"]); }
                        $rate = round_num($ior_Rate);
                    }
                }
                $sign=''; //VS.
                $mmid="(".$row['MB_MID'].")";
                $showtypeT="球队得分大小";
                break;
        }

        if ($res=='HPMH' or $res=='HPMC' or $res=='HPMN' or $res=='HPRH' or $res=='HPRC' or $res=='HPOUH' or $res=='HPOUC'){
            $title=" - [$Order_1st_Half]";
        }else{
            $title="";
        }

        if($rate==0 || $rate==""){
            $status='401.5';
            $describe=$gid."赔率为空,请稍后再试！";
            original_phone_request_response($status,$describe);

        }

        $betplace=$betplace.'<div id=TR'.$i.'>';
        $betplace=$betplace.'<div class="leag"><span class="leag_txt">'.$league.'</span><span class="deletebtn"><input type="button" name="delteam1" value="" onClick="delteams(\''.$i.'\')" class="par"></span></div>';
        $betplace=$betplace.'<div class="gametype">'.$detailsData['description'].' '.$showtypeT.'</div>';
        $betplace=$betplace.'<div class="teamName"><span class="tName">'.$s_mb_team.' [主] <span class="radio">'.$sign.'</span>'.$s_tg_team.'</span></div>';
        $betplace=$betplace.'<p class="team" id="team1"><em>'.$place.'</em> @ <strong class="light" id="P1">'.number_format($rate,2).'</strong></p>';
        $betplace=$betplace.'<p class="errorP3" style="display: none"></p>';
        $betplace=$betplace.'</div>';

        $m_rate[]=$rate;
        $m_gid[]=$gid;
        $aGidFs[]=$gid_fs;
        $type[]=$res;
        $showtype[]=$row['ShowTypeP'];
        $leag[]=$league;
        $gametype[]=$detailsData['description'].' '.$showtypeT;
        $mb_teams[]=$s_mb_team.'[主]';
        $asign[]=$sign;
        $tg_teams[]=$s_tg_team;
        $places[]=$place;

    }

}

foreach ($m_rate as $k => $v){
    $data[$k]['m_rate'] = $v;
    $data[$k]['m_gid'] = $m_gid[$k];
    $data[$k]['m_gid_fs'] = $aGidFs[$k];    //新增返回附属gid_fs
    $data[$k]['type'] = $type[$k];
    $data[$k]['showtype'] = $showtype[$k];
    $data[$k]['leag'] = $leag[$k];
    $data[$k]['gametype'] = $gametype[$k];
    $data[$k]['mb_team'] = $mb_teams[$k];
    $data[$k]['sign'] = $asign[$k];
    $data[$k]['tg_team'] = $tg_teams[$k];
    $data[$k]['place'] = $places[$k];
}

$aData[0]['minBet'] = $GMIN_SINGLE;
$aData[0]['maxBet'] = $GSINGLE_CREDIT;
$aData[0]['betItem'] = $data;
$aData[0]['maxPayout'] = '1000000'; // 单注最高派彩额是RMB 1,000,000


$status = '200';
$describe = 'success';
original_phone_request_response($status, $describe, $aData);
