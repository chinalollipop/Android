<?php
//session_start();
/**
 * /order/BK_order_p_finish_api.php 篮球综合过关下注接口
 * active   2 篮球今日赛事, 22 篮球早餐
 * teamcount
 * gold  金额
 * wagerDatas
 * randomNum 随机数
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');
//include('../include/address.mem.php');
//include_once('../include/config.inc.php');
//require_once("../../../common/sportCenterData.php");
//require ("../include/define_function_list.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {

        $status='401.1';
        $describe="请重新登录";
        original_phone_request_response($status,$describe);

}

$randomNum = $_REQUEST['randomNum']; // 随机整数
if(!$randomNum){
    $status='401.2';
    $describe="参数不对";
    original_phone_request_response($status,$describe);
}
if($randomNum == $_SESSION['randomNum']){ // 重复提交
    $status='401.3';
    $describe="请不要重复下注";
    original_phone_request_response($status,$describe);
}else { // 正常提交
    $_SESSION['randomNum'] = $randomNum;

    //接收传递过来的参数：其中赔率和位置需要进行判断
    $uid = $_SESSION['Oid'];
    $langx = $_SESSION['Language'];
    $teamcount=$_REQUEST['teamcount'];
    $gold=$_REQUEST['gold'];
    $active=$_REQUEST['active'];
    if ($active != 2 and $active != 22){
        $status='401.33';
        $describe="参数有误，请重新下单！";
        original_phone_request_response($status,$describe);
    }
    $wagerDatas=$_REQUEST['wagerDatas'];

    //require("../include/traditional.$langx.inc.php");
    //下注时的赔率：应该根据盘口进行转换后，与数据库中的赔率进行比较。若不相同，返回下注。
    $sql = "select ratio,Money,CurType,Status from ".DBPREFIX.MEMBERTABLE." where ID='{$_SESSION['userid']}'";
    $result = mysqli_query($dbMasterLink, $sql);
    $memrow = mysqli_fetch_assoc($result);
    $open = $_SESSION['OpenType'];
    $pay_type = $_SESSION['Pay_Type'];
    $memname = $_SESSION['UserName'];
    $agents = trim($_SESSION['Agents']); // 代理 D
    $world = $_SESSION['World']; // 总代 C
    $corprator = $_SESSION['Corprator']; // 股东 B
    $super = $_SESSION['Super']; // 公司 A
    $admin = $_SESSION['Admin']; // 管理员 （？子账号）
    $w_ratio = $memrow['ratio'];
    $HMoney = $Money = $memrow['Money'];
    if($HMoney < $gold || $gold<10 || $HMoney<=0){

        $status = '401.4';
        $describe = "下注金額不可大於信用額度。";
        original_phone_request_response($status, $describe);

    }

    if ($memrow['Status'] == 1) {

        $status = '403.5';
        $describe = "账户已冻结，请联系客服解冻";
        original_phone_request_response($status, $describe);

    }

    if ($memrow['Status'] == 2) {

        $status = '403.6';
        $describe = "账户已停用，请联系客服";
        original_phone_request_response($status, $describe);

    }
    $w_current = $memrow['CurType'];
    $memid = $_SESSION['userid'];
    $test_flag = $_SESSION['test_flag'];

    $wagerDatas_array=explode("|",$wagerDatas);
    $rates=1;
    $i=1;
    for ($i=0;$i<$teamcount;$i++){
        $data_array=explode(",",$wagerDatas_array[$i]);
        $mid=$data_array[0];
        $type=$data_array[1];
//        $rates=$rates*$data_array[2]; // 将全部的赔率互乘，方便计算可赢金额

        if($type!=""){

            $mysqlL = "select `MID` from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$mid and Cancel!=1 and Open=1 and MB_Team!='' and MB_Team_tw!='' ";
            $resultL = mysqli_query($dbMasterLink, $mysqlL);
            $couL = mysqli_num_rows($resultL);
            if($couL==0) {
                $status = '401.99';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);
            }

            $mysql = "select `Type`,MB_Team,TG_Team,MB_Team_tw,TG_Team_tw,MB_Team_en,TG_Team_en,M_League,M_League_tw,M_League_en,MB_P_Win_Rate,MB_MID,TG_MID,MB_P_LetB_Rate,TG_P_Win_Rate,ShowTypeP,M_P_LetB,TG_P_LetB_Rate,MB_P_Dime,MB_P_Dime_Rate,TG_P_Dime,TG_P_Dime_Rate,S_P_Single_Rate,S_P_Double_Rate,MB_P_Dime_H,TG_P_Dime_H,MB_Dime_H, MB_Dime_S_H, TG_Dime_H, TG_Dime_S_H,MB_P_Dime_Rate_H, MB_P_Dime_Rate_S_H, TG_P_Dime_Rate_H, TG_P_Dime_Rate_S_H,M_Date from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `M_Start`>now() and MID='$mid' and Open=1 and MB_Team!='' and MB_Team_tw!=''";
            $result = mysqli_query($dbCenterMasterDbLink,$mysql);
            $cou=mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if($cou==0){
                $status = '401.16';
                $describe = $Order_This_match_is_closed_Please_try_again;
                original_phone_request_response($status, $describe);

            }else{

                if ($row['Type'] != 'BK' and $row['Type'] != 'BU'){
                    $status='401.34';
                    $describe="参数有误，请重新下单！";
                    original_phone_request_response($status,$describe);
                }

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
                $caption=$Order_Basketball.$Order_Mix_Parlay_betting_order;
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

                    case 'PODD': // 主队单
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
                    case 'PEVEN': // 客队双
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
                        $team=$s_mb_team;
                        $s_mb_team=$s_tg_team;
                        $s_tg_team=$team;

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

                        }
                        $Mtype='RH';
                        $sign=$row['M_P_LetB'];
                        if ($sign==''){
                            $status='401.23';
                            $describe="让球参数异常，请刷新赛事~~";
                            original_phone_request_response($status,$describe);
                        }
                        $m_place=$row['M_P_LetB'];
                        $mmid="(".$row['MB_MID'].")";
                        break;
                    case 'PRC':  // 客队让球
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;

                        $place=$s_tg_team;
                        $team=$s_mb_team;
                        $s_mb_team=$s_tg_team;
                        $s_tg_team=$team;

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

                        }
                        $Mtype='RC';
                        $sign=$row['M_P_LetB'];
                        if ($sign==''){
                            $status='401.24';
                            $describe="让球参数异常，请刷新赛事~~";
                            original_phone_request_response($status,$describe);
                        }
                        $m_place=$row['M_P_LetB'];
                        $mmid="(".$row['TG_MID'].")";
                        break;
                    case 'POUC': // 主队大小
                        $w_m_place=$row["MB_P_Dime"];
                        $w_m_place=str_replace('O','大 ',$w_m_place);
                        $w_m_place_tw=$row["MB_P_Dime"];
                        $w_m_place_tw=str_replace('O','大 ',$w_m_place_tw);
                        $w_m_place_en=$row["MB_P_Dime"];
                        $place=$row['MB_P_Dime'];
                        if ($langx=="zh-cn"){
                            $place=str_replace('O','大 ',$place);
                        }else if ($langx=="zh-cn"){
                            $place=str_replace('O','大 ',$place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $place=str_replace('O','over ',$place);
                        }
                        $w_m_rate=change_rate($open,$row['MB_P_Dime_Rate']);
                        if ($row["MB_P_Dime"]=='' || $row['MB_P_Dime_Rate']<=1){
                            $status = '401.171';
                            $describe = "玩法数据不完整，请刷新后再重新投注" . rand(1, 199);
                            original_phone_request_response($status, $describe);
                        }
                        $sign='VS.';
                        $Mtype='OUH';
                        $m_place=$row['MB_P_Dime'];
                        $mmid="(".$row['MB_MID'].")";
                        break;
                    case 'POUH': // 客队大小
                        $w_m_place=$row["TG_P_Dime"];
                        $w_m_place=str_replace('U','小 ',$w_m_place);
                        $w_m_place_tw=$row["TG_P_Dime"];
                        $w_m_place_tw=str_replace('U','小 ',$w_m_place_tw);
                        $w_m_place_en=$row["TG_P_Dime"];
                        $place=$row['TG_P_Dime'];
                        if ($langx=="zh-cn"){
                            $place=str_replace('U','小 ',$place);
                        }else if ($langx=="zh-cn"){
                            $place=str_replace('U','小 ',$place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $place=str_replace('U','under ',$place);
                        }
                        $w_m_rate=change_rate($open,$row['TG_P_Dime_Rate']);
                        if ($row["TG_P_Dime"]=='' || $row['TG_P_Dime_Rate']<=1){
                            $status = '401.172';
                            $describe = "玩法数据不完整，请刷新后再重新投注" . rand(1, 199);
                            original_phone_request_response($status, $describe);
                        }
                        $sign='VS.';
                        $Mtype='OUC';
                        $m_place=$row['TG_P_Dime'];
                        $mmid="(".$row['TG_MID'].")";
                        break;
                    case 'POUHO': // 综合过关，得分大小，主队大
                        $w_m_place=$row["MB_Dime_H"];
                        $w_m_place=str_replace('O','大 ',$w_m_place);
                        $w_m_place_tw=$row["MB_Dime_H"];
                        $w_m_place_tw=str_replace('O','大 ',$w_m_place_tw);
                        $w_m_place_en=$row["MB_Dime_H"];
                        $place = $s_mb_team.'.'.$row['MB_Dime_H'];
                        if ($langx=="zh-cn"){
                            $place=str_replace('O','大 ',$place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $place=str_replace('O','over ',$place);
                        }
                        $w_m_rate=change_rate($open,$row['MB_P_Dime_Rate_H']);
                        if ($row["MB_Dime_H"]=='' || $row['MB_P_Dime_Rate_H']<=1){
                            $status = '401.173';
                            $describe = "玩法数据不完整，请刷新后再重新投注" . rand(1, 199);
                            original_phone_request_response($status, $describe);
                        }
                        $Mtype='TOUH';
                        $sign='VS.';
                        $m_place=$row['MB_Dime_H'];
                        $mmid="(".$row['MB_MID'].")";
                        break;
                    case 'POUHU':// 综合过关，得分大小，主队小
                        $w_m_place=$row["MB_Dime_S_H"];
                        $w_m_place=str_replace('U','小 ',$w_m_place);
                        $w_m_place_tw=$row["MB_Dime_S_H"];
                        $w_m_place_tw=str_replace('U','小 ',$w_m_place_tw);
                        $w_m_place_en=$row["MB_Dime_S_H"];
                        $place = $s_mb_team.'.'.$row['MB_Dime_S_H'];
                        if ($langx=="zh-cn"){
                            $place=str_replace('U','小 ',$place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $place=str_replace('U','under ',$place);
                        }
                        $w_m_rate=change_rate($open,$row['MB_P_Dime_Rate_S_H']);
                        if ($row["MB_Dime_S_H"]=='' || $row['MB_P_Dime_Rate_S_H']<=1){
                            $status = '401.174';
                            $describe = "玩法数据不完整，请刷新后再重新投注" . rand(1, 199);
                            original_phone_request_response($status, $describe);
                        }
                        $Mtype='TOUH';
                        $sign='VS.';
                        $m_place=$row['MB_Dime_S_H'];
                        $mmid="(".$row['MB_MID'].")";
                        break;
                    case 'POUCO': // 综合过关，得分大小，客队大
                        $w_m_place=$row["TG_Dime_H"];
                        $w_m_place=str_replace('O','大 ',$w_m_place);
                        $w_m_place_tw=$row["TG_Dime_H"];
                        $w_m_place_tw=str_replace('O','大 ',$w_m_place_tw);
                        $w_m_place_en=$row["TG_Dime_H"];
                        $place = $s_tg_team.'.'.$row['TG_Dime_H'];
                        if ($langx=="zh-cn"){
                            $place=str_replace('O','大 ',$place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $place=str_replace('O','over ',$place);
                        }
                        $w_m_rate=change_rate($open,$row['TG_P_Dime_Rate_H']);
                        if ($row["TG_Dime_H"]=='' || $row['TG_P_Dime_Rate_H']<=1){
                            $status = '401.175';
                            $describe = "玩法数据不完整，请刷新后再重新投注" . rand(1, 199);
                            original_phone_request_response($status, $describe);
                        }
                        $m_place=$row['TG_Dime_H'];
                        $Mtype='TOUC';
                        $sign='VS.';
                        $mmid="(".$row['TG_MID'].")";
                        break;
                    case 'POUCU': // 综合过关，得分大小，客队小
                        $w_m_place=$row["TG_Dime_S_H"];
                        $w_m_place=str_replace('U','小 ',$w_m_place);
                        $w_m_place_tw=$row["TG_Dime_S_H"];
                        $w_m_place_tw=str_replace('U','小 ',$w_m_place_tw);
                        $w_m_place_en=$row["TG_Dime_S_H"];
                        $place = $s_tg_team.'.'.$row['TG_Dime_S_H'];
                        if ($langx=="zh-cn"){
                            $place=str_replace('U','小 ',$place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $place=str_replace('U','under ',$place);
                        }
                        $w_m_rate=change_rate($open,$row['TG_P_Dime_Rate_S_H']);
                        if ($row["TG_Dime_S_H"]=='' || $row['TG_P_Dime_Rate_S_H']<=1){
                            $status = '401.176';
                            $describe = "玩法数据不完整，请刷新后再重新投注" . rand(1, 199);
                            original_phone_request_response($status, $describe);
                        }
                        $m_place=$row['TG_Dime_S_H'];
                        $Mtype='TOUC';
                        $sign='VS.';
                        $mmid="(".$row['TG_MID'].")";
                        break;
                }

                if ($w_m_place=='' || $place==''){
                    $status = '401.17';
                    $describe = "玩法数据不完整，请刷新后再重新投注" . rand(1, 199);
                    original_phone_request_response($status, $describe);
                }

                if($w_m_rate=='' || $w_m_rate==0){
                    $status = '401.17';
                    $describe = "赔率为空,请稍后再试！" . rand(1, 199);
                    original_phone_request_response($status, $describe);

                }

                $btype="";

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
                $m_gid[]=$mid;
                $m_rate[]=$w_m_rate;
                $ktype[]=$Mtype;
                $show_type[]=$row['ShowTypeP'];
                $r_place[]=$m_place;
                $s_mb_teams[]=$s_mb_team;
                $leagues[]=$league;
                $s_tg_teams[]=$s_tg_team;
                $places[]=$place;
                $signs[]=$sign;
                $btypes[]=$btype;

                $rates=$rates*$w_m_rate; // 将全部的赔率互乘，方便计算可赢金额
            }
        }
    }

}

$gid=implode(",",$m_gid);
$gtype=implode(",",$ktype);
$w_m_rate=implode(",",$m_rate);
$s_mb_team=implode(",",$s_mb_teams);
$league=implode(",",$leagues);
$s_tg_team=implode(",",$s_tg_teams);
$place=implode(",",$places);
$sign=implode(",",$signs);
$btype=implode(",",$btypes);
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

$showVoucher = show_voucher('');

//判断终端类型
if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
    $playSource=$_REQUEST['appRefer'];
}
else{
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $playSource=3;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $playSource=4;
    }else{
        $playSource=5;
    }
}

$begin = mysqli_query($dbMasterLink,"start transaction");
$lockResult = mysqli_query($dbMasterLink,"select Money from ".DBPREFIX.MEMBERTABLE." where ID = ".$memid." for update");
if($begin && $lockResult){
    $checkRow = mysqli_fetch_assoc($lockResult);
    $HMoney=$Money=$checkRow['Money'];
    $havemoney=$HMoney-$gold;
    if($havemoney<0 || $gold<=0 || $HMoney<=0){
        mysqli_query($dbMasterLink,"ROLLBACK");
        $status = '401.18';
        $describe = "下注金額不可大於信用額度。" . rand(1, 199);
        original_phone_request_response($status, $describe);
    }
    $sql = "INSERT INTO ".DBPREFIX."web_report_data	(MID,Glost,playSource,Userid,testflag,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,BetID,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball) values ('$gid',$Money,$playSource,$memid,$test_flag,'$active','$showVoucher','$line','$gtype','$date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','BK','$w_current','$w_ratio','$betid','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball')";
    $insertBet=mysqli_query($dbMasterLink,$sql);
    if($insertBet){
        $lastId=mysqli_insert_id($dbMasterLink);
        $moneyLogRes=addAccountRecords(array($memid,$memname,$test_flag,$Money,$gold*-1,$havemoney,1,$playSource,$lastId,"BK投注$gtype"));
        if($moneyLogRes){
            $sql1 = "update ".DBPREFIX.MEMBERTABLE." set Money=".$havemoney."  , Online=1 , OnlineTime=now() where ID=".$memid;
            $updateMoney=mysqli_query($dbMasterLink,$sql1);
            if($updateMoney){
                mysqli_query($dbMasterLink,"COMMIT");
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $status='401.19';
                $describe="操作失败!!" . rand(1, 199);
                original_phone_request_response($status,$describe);
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $status='401.20';
            $describe="操作失败2!!" . rand(1, 199);
            original_phone_request_response($status,$describe);
        }
    }else{
        mysqli_query($dbMasterLink,"ROLLBACK");
        $status='401.21';
        $describe="操作失败!" . rand(1, 199);
        original_phone_request_response($status,$describe);
    }
}else{
    mysqli_query($dbMasterLink,"ROLLBACK");
    $status='401.22';
    $describe="操作失败0!" . rand(1, 199);
    original_phone_request_response($status,$describe);

}

if ($active==22){
    $caption=str_replace($Order_Basketball,$Order_Basketball.$Order_Early_Market,$caption);
}


// 确定交易生成图片开关
if(GENERATE_IMA_SWITCH) {
    $data = array(
        'caption' => $caption, //标题
        'Order_Bet_success' => $Order_Bet_success, //交易成功单号
        'showVoucher' => $showVoucher, //单号
        's_league' => $league,
        'btype' => $btype, // 在联赛名称后面显示
        'M_Date' => date('m-d',strtotime($row["M_Date"])), //日期
        'sign' => $sign, // 让球数
        's_mb_team' => $s_mb_team,   // 主队
        's_tg_team' => $s_tg_team,  // 客队
        's_m_place' => $place,  // 选择所属队
        'w_m_rate' => $w_m_rate,  // 赔率
        'gold' => $gold, // 下注金额
        'playSource' => $playSource,  // 投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓，13原生苹果,14原生安卓
        'gwin' => $gwin, // 可赢金额
        'havemoney' => $havemoney, // 账户余额
        'userid' => $_SESSION['userid'],
        'is_zhgg' => 1, // 是否综合过关 1
        'teamcount' => $teamcount,
    );

    $redisObj = new Ciredis();
    $redisObj->setOne($showVoucher,serialize($data));
    $redisObj->pushMessage('general_order_image',$showVoucher);

}

$aData[0]['caption'] = $caption;
$aData[0]['Order_Bet_success'] = $Order_Bet_success;
$aData[0]['order'] = $showVoucher;
$aData[0]['s_league'] = $league;
$aData[0]['btype'] = $btype?$btype:'';
$aData[0]['M_Date'] = date('m-d',strtotime($row["M_Date"]));
$aData[0]['s_mb_team'] = $s_mb_team;
$aData[0]['sign'] = $sign?$sign:'';
$aData[0]['s_tg_team'] = $s_tg_team;
$aData[0]['s_m_place'] = $place;
$aData[0]['w_m_rate'] = $w_m_rate;
$aData[0]['gold'] = $gold; // 交易金额
$aData[0]['order_bet_amount'] = $gwin; // 可赢金额
$aData[0]['havemoney'] = $havemoney; // 账户余额

$status = '200';
$describe = '投注成功';
original_phone_request_response($status,$describe,$aData);
