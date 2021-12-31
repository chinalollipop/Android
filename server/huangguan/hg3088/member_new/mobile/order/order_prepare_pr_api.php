<?php
/**
 * /order/order_prepare_pr_api.php?game=PMH,PMH&game_id=2605878,2605892  篮球综合过关选择玩法和赔率，准备投注接口
 * @param  game
 * @param  game_id
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');
include "../include/address.mem.php";
include_once('../include/config.inc.php');
require_once("../../../common/sportCenterData.php");
//require ("../include/define_function_list.inc.php");
include_once("../../../common/sportapi/define_function_list.inc.php");
require ("../include/curl_http.php");

$aGame = explode(',',$_REQUEST['game']);
$aGameId = explode(',',$_REQUEST['game_id']);

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
$langx=$_SESSION['Language'];
require ("../include/traditional.$langx.inc.php");
$memname=$_SESSION['UserName'];
$open=$_SESSION['OpenType'];

$btset=singleset('P3');
$GMIN_SINGLE=$btset[0];
$GSINGLE_CREDIT=FT_PR_Bet;

foreach ($aGameId as $k =>$v) {

    $res = $aGame[$k];
    if ($res!=""){
        $gid = $v;
        $havesql="select sum(BetScore) as BetScore from ".DBPREFIX."web_report_data where m_name='$memname' and FIND_IN_SET($gid,MID)>0 and linetype=8 and (Active=2 or Active=22)";
        $result = mysqli_query($dbMasterLink,$havesql);

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
        }


        //先判断本地库
        $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and MID='$gid' and Cancel!=1 and Open=1 and MB_Team!='' and MB_Team_tw!=''";//判断赛事是否关闭
        $resultL = mysqli_query($dbLink,$mysqlL);
        $couL=mysqli_num_rows($resultL);
        if ($couL==0){
            $status='401.99';
            $describe=$gid.$Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status,$describe);
        }

        $mysql = "select MID,M_Date,M_Time,MB_MID,TG_MID,ShowTypeP,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_P_Win_Rate,TG_P_Win_Rate,ShowTypeP,MB_P_LetB_Rate,TG_P_LetB_Rate,M_P_LetB,MB_P_Dime,TG_P_Dime,MB_P_Dime_Rate,TG_P_Dime_Rate,S_Single_Rate,S_Double_Rate,MB_P_Dime_H,TG_P_Dime_H,MB_Dime_H, MB_Dime_S_H, TG_Dime_H, TG_Dime_S_H,MB_P_Dime_Rate_H, MB_P_Dime_Rate_S_H, TG_P_Dime_Rate_H, TG_P_Dime_Rate_S_H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and MID='$gid' and Open=1 and MB_Team!='' and MB_Team_tw!=''";//判断赛事是否关闭
        $result = mysqli_query($dbCenterMasterDbLink,$mysql);
        $cou=mysqli_num_rows($result);
        if ($cou==0){
            $status='401.3';
            $describe=$gid.$Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status,$describe);

        }
        $row = mysqli_fetch_assoc($result);
        $pdate=$row['M_Date'];
        $start=$row['M_Start'];
        $league=$row['M_League'];
        $s_mb_team = filiter_team($row['MB_Team']);
        $s_tg_team = filiter_team($row['TG_Team']);
        $mb_mid=$row['MB_MID'];
        $tg_mid=$row['TG_MID'];

        /*
        if($teama==$row['MB_Team']){
            echo attention("$Order_The_game_is_covered_same_teams_Please_reset_again",$uid,$langx);
            exit();
        }else{
            $teama=$row['MB_Team'];
        }*/
        if(in_array($row['MB_Team'],$teama_arr)){
            $status='401.4';
            $describe=$gid.$Order_The_game_is_covered_same_teams_Please_reset_again;
            original_phone_request_response($status,$describe);

        }else{
            $teama_arr[]=$row['MB_Team'];
        }
//         $addrate = 0.98 ; // 半场让球和半场大小单独处理，2018 添加
        switch($res){
            case 'PMH': // 主队独赢
                $place=$s_mb_team;
//                 $rate=change_rate($open,$row["MB_Win_Rate"]);
                $rate=change_rate($open,$row["MB_P_Win_Rate"]);
                $Mtype='MH';
                $mmid="(".$row['MB_MID'].")";
                $showtypeT=$U_30;
                break;
            case 'PMC': // 客队独赢
                $place=$s_mb_team;
//                 $rate=change_rate($open,$row["TG_Win_Rate"]);
                $rate=change_rate($open,$row["TG_P_Win_Rate"]);
                $Mtype='MC';
                $mmid="(".$row['TG_MID'].")";
                $showtypeT=$U_30;
                break;
            case 'PRH': // 主队让球
                $place=$s_mb_team;
                $rate=change_rate($open,$row["MB_P_LetB_Rate"]);
                $Mtype='RH';
                if ($row['ShowTypeP']=='C'){
                    $team=$s_mb_team;
                    $s_mb_team=$s_tg_team;
                    $s_tg_team=$team;
                }
                $sign=$row['M_P_LetB'];
                $m_place=$row['M_P_LetB'];
                $mmid="(".$row['MB_MID'].")";
                $showtypeT=$U_43;
                break;
            case 'PRC': // 客队让球
                $place=$s_tg_team;
                $rate=change_rate($open,$row["TG_P_LetB_Rate"]);
                $Mtype='RC';
                if ($row['ShowTypeP']=='C'){
                    $team=$s_mb_team;
                    $s_mb_team=$s_tg_team;
                    $s_tg_team=$team;
                }
                $sign=$row['M_P_LetB'];
                $m_place=$row['M_P_LetB'];
                $mmid="(".$row['TG_MID'].")";
                $showtypeT=$U_43;
                break;
            case 'POUC': // 主队大小
                $place=$row['MB_P_Dime'];
                if ($langx=="zh-cn"){
                    $place=str_replace('O','大 ',$place);
                }else if ($langx=="zh-cn"){
                    $place=str_replace('O','大 ',$place);
                }else if ($langx=="en-us" or $langx=="th-tis"){
                    $place=str_replace('O','over ',$place);
                }
                $rate=change_rate($open,$row['MB_P_Dime_Rate']);
                $m_place=$row['MB_P_Dime'];
                $sign='VS.';
                $mmid="(".$row['MB_MID'].")";
                $showtypeT=$U_27;
                break;
            case 'POUH': // 客队大小
                $place=$row['TG_P_Dime'];
                if ($langx=="zh-cn"){
                    $place=str_replace('U','小 ',$place);
                }else if ($langx=="zh-cn"){
                    $place=str_replace('U','小 ',$place);
                }else if ($langx=="en-us" or $langx=="th-tis"){
                    $place=str_replace('U','under ',$place);
                }
                $rate=change_rate($open,$row['TG_P_Dime_Rate']);
                $m_place=$row['TG_P_Dime'];
                $sign='VS.';
                $mmid="(".$row['TG_MID'].")";
                $showtypeT=$U_27;
                break;
            case 'PODD': // 主队单
                $place= $Order_Odd ;
                $rate=change_rate($open,$row["S_Single_Rate"]);
                $Mtype='OEO';
                $mmid="(".$row['MB_MID'].")";
                $showtypeT=$U_31;
                break;
            case 'PEVEN': // 客队双
                $place = $Order_Even ;
                $rate=change_rate($open,$row["S_Double_Rate"]);
                $Mtype='OEE';
                $mmid="(".$row['TG_MID'].")";
                $showtypeT=$U_31;
                break;
            case 'POUHO': // 综合过关，得分大小，主队大
            case 'POUHU':// 综合过关，得分大小，主队小
                if ($res=='POUHO'){
                    $place = $s_mb_team.' .'.$row['MB_Dime_H'];
                    if ($langx=="zh-cn"){
                        $place=str_replace('O','大 ',$place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $place=str_replace('O','over ',$place);
                    }
                    $rate=change_rate($open,$row['MB_P_Dime_Rate_H']);
                }elseif($res=='POUHU'){
                    $place = $s_mb_team.' .'.$row['MB_Dime_S_H'];
                    if ($langx=="zh-cn"){
                        $place=str_replace('U','小 ',$place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $place=str_replace('U','under ',$place);
                    }
                    $rate=change_rate($open,$row['MB_P_Dime_Rate_S_H']);
                }
                $m_place=$row['MB_Dime_H'];
                $sign='VS.';
                $mmid="(".$row['MB_MID'].")";
                $showtypeT="球队得分大小";
                break;
            case 'POUCO': // 综合过关，得分大小，客队大
            case 'POUCU': // 综合过关，得分大小，客队小
                if ($res=='POUCO'){
                    $place = $s_tg_team.' .'.$row['TG_Dime_H'];
                    if ($langx=="zh-cn"){
                        $place=str_replace('O','大 ',$place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $place=str_replace('O','over ',$place);
                    }
                    $rate=change_rate($open,$row['TG_P_Dime_Rate_H']);
                }elseif($res=='POUCU'){
                    $place = $s_tg_team.' .'.$row['TG_Dime_S_H'];
                    if ($langx=="zh-cn"){
                        $place=str_replace('U','小 ',$place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $place=str_replace('U','under ',$place);
                    }
                    $rate=change_rate($open,$row['TG_P_Dime_Rate_S_H']);
                }
                $m_place=$row['TG_Dime_H'];
                $sign='VS.';
                $mmid="(".$row['TG_MID'].")";
                $showtypeT="球队得分大小";
                break;
        }

        if($rate==0 || $rate==""){
            $status='401.5';
            $describe=$gid."赔率为空,请稍后再试！";
            original_phone_request_response($status,$describe);

        }

        $betplace=$betplace.'<div id=TR'.$i.' class=ee6819>';
        $betplace=$betplace.'<div class="leag"><span class="leag_txt">'.$league.'</span><span class="deletebtn"><input type="button" name="delteam1" value="" onClick="delteams(\''.$i.'\')" class="par"></span></div>';
        $betplace=$betplace.'<div class="gametype1">'.$showtypeT.'</div>';
        $betplace=$betplace.'<div class="teamName"><span class="tName">'.$s_mb_team.' [主] <span class="radio">'.$sign.'</span>'.$s_tg_team.'</span></div>';
        $betplace=$betplace.'<p class="team" id="team1"><em>'.$place.'</em> @ <strong class="light" id="P1">'.number_format($rate,2).'</strong></p>';
        $betplace=$betplace.'<p class="errorP3" style="display: none"></p>';
        $betplace=$betplace.'</div>';

        $m_team=$m_team+1;
        $m_rate[]=$rate;
        $m_gid[]=$gid;
        $type[]=$res;
        $showtype[]=$row['ShowTypeP'];
        $leag[]=$league;
        $gametype[]=$showtypeT;
        $mb_teams[]=$s_mb_team.'[主]';
        $asign[]=$sign;
        $tg_teams[]=$s_tg_team;
        $places[]=$place;
    }
}



foreach ($m_rate as $k => $v){
    $data[$k]['m_rate'] = $v;
    $data[$k]['m_gid'] = $m_gid[$k];
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
