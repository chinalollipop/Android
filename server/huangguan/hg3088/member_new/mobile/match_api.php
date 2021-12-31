<?php
/**
 * /match_api.php  体育（足球-足球滚球-篮球-篮球滚球）玩法信息接口
 *
 * @param  type   FT 足球，BK 篮球
 * @param  more   s 今日赛事， r 滚球
 * @param  gid    mid,mid,mid....
 */

include_once('include/config.inc.php');

require ("include/define_function_list.inc.php");
require ("include/curl_http.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status='401.1';
    $describe="请重新登录";
    original_phone_request_response($status,$describe);
}

$open=$_SESSION['OpenType'];
$type = $_REQUEST['type'];
$gid = $_REQUEST['gid'];
$more = $_REQUEST['more'];
$m_date = date('Y-m-d');
$now = date('Y-m-d H:i:s');

switch ($type){
    case 'FT':
        switch ($more){
            case 's':// 足球今日赛事玩法信息 今日赛事 ShowTypeR，滚球 ShowTypeRB
                $mysql="select MID,Type,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='$type' and `M_Start` > '$now' AND `M_Date` ='$m_date' and MID IN ($gid) and `S_Show`=1 and MB_TEAM!='' and `Open`=1 order by M_Start,M_League,MB_TEAM,MB_MID";
                $result = mysqli_query($dbLink, $mysql);

                $cou=mysqli_num_rows($result);
                $aData=[];
                while ($row=mysqli_fetch_assoc($result)){
                    $row['MB_Win_Rate']=change_rate($open,$row["MB_Win_Rate"]);
                    $row['TG_Win_Rate']=change_rate($open,$row["TG_Win_Rate"]);
                    $row['M_Flat_Rate']=change_rate($open,$row["M_Flat_Rate"]);
                    // 全场让球处理
                    $ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_LetB_Rate"],$row["MB_LetB_Rate"],100); // 默认都是香港盘
                    $TG_LetB_Rate=$ra_rate[0];
                    $MB_LetB_Rate=$ra_rate[1];
                    $row['TG_LetB_Rate']=change_rate($open,$TG_LetB_Rate);
                    $row['MB_LetB_Rate']=change_rate($open,$MB_LetB_Rate);
                    // 全场大小处理
                    $ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_Dime_Rate"],$row["MB_Dime_Rate"],100); // 默认都是香港盘
                    $TG_Dime_Rate=$ra_rate[0];
                    $MB_Dime_Rate=$ra_rate[1];
                    $row['TG_Dime_Rate']=change_rate($open,$TG_Dime_Rate);
                    $row['MB_Dime_Rate']=change_rate($open,$MB_Dime_Rate);

                    $row['S_Single_Rate']=change_rate($open,$row['S_Single_Rate']);
                    $row['S_Double_Rate']=change_rate($open,$row['S_Double_Rate']);
                    $row['MB_Win_Rate_H']=change_rate($open,$row["MB_Win_Rate_H"]); // 全部独赢主队
                    $row['TG_Win_Rate_H']=change_rate($open,$row["TG_Win_Rate_H"]); // 全部独赢客队
                    $row['M_Flat_Rate_H']=change_rate($open,$row["M_Flat_Rate_H"]); // 全部独赢和局
                    // 半场让球单独处理
                    $h_ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate_H"],$row["TG_LetB_Rate_H"],100); // 默认都是香港盘
                    $MB_LetB_Rate_H=$h_ra_rate[0]; // 主队
                    $TG_LetB_Rate_H=$h_ra_rate[1]; // 客队
                    $row['MB_LetB_Rate_H']=change_rate($open,$MB_LetB_Rate_H);  // 半场让球主队
                    $row['TG_LetB_Rate_H']=change_rate($open,$TG_LetB_Rate_H); // 半场让球客队

                    // 半场大小处理
                    $h_ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_Dime_Rate_H"],$row["MB_Dime_Rate_H"],100); // 默认都是香港盘
                    $TG_Dime_Rate_H=$h_ra_rate[0];
                    $MB_Dime_Rate_H=$h_ra_rate[1];
                    $row['TG_Dime_Rate_H']=change_rate($open,$TG_Dime_Rate_H);  // 半场大小客队
                    $row['MB_Dime_Rate_H']=change_rate($open,$MB_Dime_Rate_H); // 半场大小主队


                    $aData[] = $row;
                }
                break;
            case 'r': // 足球滚球玩法信息
                $mysql = "select MID,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,
`MB_Win_Rate_RB`,`TG_Win_Rate_RB`,`M_Flat_Rate_RB`,`M_LetB_RB`,`T_LetB_RB`,`MB_LetB_Rate_RB`,`TG_LetB_Rate_RB`,`MB_Dime_RB`,`TG_Dime_RB`,`MB_Dime_Rate_RB`,`TG_Dime_Rate_RB`,`ShowTypeRB`,`ShowTypeHRB`,`MB_Win_Rate_RB_H`,`TG_Win_Rate_RB_H`,`M_Flat_Rate_RB_H`,`M_LetB_RB_H`,`MB_LetB_Rate_RB_H`,`TG_LetB_Rate_RB_H`,`MB_Dime_RB_H`,`MB_Dime_RB_S_H`,`TG_Dime_RB_H`,`TG_Dime_RB_S_H`,`MB_Dime_Rate_RB_H`,`MB_Dime_Rate_RB_S_H`,`TG_Dime_Rate_RB_H`,`TG_Dime_Rate_RB_S_H`,
MB_Ball,TG_Ball,MB_Inball_HR,TG_Inball_HR,
Eventid,Hot,Play,nowSession from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='$type' AND `M_Date` ='$m_date' and MID IN ($gid) and MB_TEAM!='' order by M_Start,MB_MID";
                $result = mysqli_query($dbLink, $mysql);

                $cou=mysqli_num_rows($result);
                $aData=[];
                while ($row=mysqli_fetch_assoc($result)){
                    $row['MB_Win_Rate_RB']=change_rate($open,$row['MB_Win_Rate_RB']);
                    $row['TG_Win_Rate_RB']=change_rate($open,$row['TG_Win_Rate_RB']);
                    $row['M_Flat_Rate_RB']=change_rate($open,$row['M_Flat_Rate_RB']);

                    // 全场让球单独处理
                    $ra_rate=get_other_ioratio(GAME_POSITION,$row['MB_LetB_Rate_RB'],$row['TG_LetB_Rate_RB'],100); // 默认都是香港盘
                    $row['MB_LetB_Rate_RB']=$ra_rate[0]; // 主队
                    $row['TG_LetB_Rate_RB']=$ra_rate[1]; // 客队
                    $row['MB_LetB_Rate_RB']=change_rate($open,$row['MB_LetB_Rate_RB']);
                    $row['TG_LetB_Rate_RB']=change_rate($open,$row['TG_LetB_Rate_RB']);

                    $ra_rate=get_other_ioratio(GAME_POSITION,$row['MB_Dime_Rate_RB'],$row['TG_Dime_Rate_RB'],100); // 默认都是香港盘
                    $row['MB_Dime_Rate_RB']=$ra_rate[0]; // 全场大小 大
                    $row['TG_Dime_Rate_RB']=$ra_rate[1]; // 全场大小 小
                    $row['MB_Dime_Rate_RB']=change_rate($open,$row['MB_Dime_Rate_RB']);
                    $row['TG_Dime_Rate_RB']=change_rate($open,$row['TG_Dime_Rate_RB']);

                    $row['MB_Win_Rate_RB_H']=change_rate($open,$row['MB_Win_Rate_RB_H']);
                    $row['TG_Win_Rate_RB_H']=change_rate($open,$row['TG_Win_Rate_RB_H']);
                    $row['M_Flat_Rate_RB_H']=change_rate($open,$row['M_Flat_Rate_RB_H']);

                    // 半场让球单独处理
                    $ra_rate=get_other_ioratio(GAME_POSITION,$row['MB_LetB_Rate_RB_H'],$row['TG_LetB_Rate_RB_H'],100); // 默认都是香港盘
                    $row['MB_LetB_Rate_RB_H']=$ra_rate[0]; // 主队
                    $row['TG_LetB_Rate_RB_H']=$ra_rate[1]; // 客队
                    $row['MB_LetB_Rate_RB_H']=change_rate($open,$row['MB_LetB_Rate_RB_H']);
                    $row['TG_LetB_Rate_RB_H']=change_rate($open,$row['TG_LetB_Rate_RB_H']);

                    $ra_rate=get_other_ioratio(GAME_POSITION,$row['MB_Dime_Rate_RB_H'],$row['MB_Dime_Rate_RB_S_H'],100); // 默认都是香港盘
                    $row['MB_Dime_Rate_RB_H']=$ra_rate[0];
                    $row['MB_Dime_Rate_RB_S_H']=$ra_rate[1];
                    $row['MB_Dime_Rate_RB_H']=change_rate($open,$row['MB_Dime_Rate_RB_H']);
                    $row['MB_Dime_Rate_RB_S_H']=change_rate($open,$row['MB_Dime_Rate_RB_S_H']);

                    $ra_rate=get_other_ioratio(GAME_POSITION,$row['TG_Dime_Rate_RB_H'],$row['TG_Dime_Rate_RB_S_H'],100); // 默认都是香港盘
                    $row['TG_Dime_Rate_RB_H']=$ra_rate[0];
                    $row['TG_Dime_Rate_RB_S_H']=$ra_rate[1];
                    $row['TG_Dime_Rate_RB_H']=change_rate($open,$row['TG_Dime_Rate_RB_H']);
                    $row['TG_Dime_Rate_RB_S_H']=change_rate($open,$row['TG_Dime_Rate_RB_S_H']);

                    $aData[] = $row;
                }
                break;
        }

        break;
    case 'FU':
        $mysql = "select MID,Type,M_Date,M_Time,M_Type,MB_MID,TG_MID,more,MB_Team,TG_Team,M_League,ShowTypeR,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FU' and `M_Date` >'$m_date' and MID IN ($gid) and `S_Show`=1 and `MB_Team`!='' ".$date." and `Open`=1 order by M_Start,MB_Team,MB_MID";
        $result = mysqli_query($dbLink, $mysql);
        $cou=mysqli_num_rows($result);
        $aData=[];
        while ($row=mysqli_fetch_assoc($result)){

            $row['MB_Win_Rate']=change_rate($open,$row["MB_Win_Rate"]);
            $row['TG_Win_Rate']=change_rate($open,$row["TG_Win_Rate"]);
            $row['M_Flat_Rate']=change_rate($open,$row["M_Flat_Rate"]);

            // 全场让球单独处理
            $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100); // 默认都是香港盘
            $MB_LetB_Rate=$ra_rate[0]; // 主队
            $TG_LetB_Rate=$ra_rate[1]; // 客队
            $row['MB_LetB_Rate']=change_rate($open,$MB_LetB_Rate);
            $row['TG_LetB_Rate']=change_rate($open,$TG_LetB_Rate);

            // 全场大小单独处理
            $dx_rate=get_other_ioratio(GAME_POSITION,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100); // 默认都是香港盘
            $MB_Dime_Rate=$dx_rate[0]; // 主队
            $TG_Dime_Rate=$dx_rate[1]; // 客队
            $row["MB_Dime_Rate"]=change_rate($open,$MB_Dime_Rate);
            $row["TG_Dime_Rate"]=change_rate($open,$TG_Dime_Rate);

            $row['S_Single_Rate']=change_rate($open,$row['S_Single_Rate']);
            $row['S_Double_Rate']=change_rate($open,$row['S_Double_Rate']);
            $row['MB_Win_Rate_H']=change_rate($open,$row["MB_Win_Rate_H"]); // 全部独赢主队
            $row['TG_Win_Rate_H']=change_rate($open,$row["TG_Win_Rate_H"]); // 全部独赢客队
            $row['M_Flat_Rate_H']=change_rate($open,$row["M_Flat_Rate_H"]); // 全部独赢和局

            // 半场让球单独处理
            $h_ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate_H"],$row["TG_LetB_Rate_H"],100); // 默认都是香港盘
            $MB_LetB_Rate_H=$h_ra_rate[0]; //半场-让球 主队赢
            $TG_LetB_Rate_H=$h_ra_rate[1]; //半场-让球 客队赢
            $row['MB_LetB_Rate_H']=change_rate($open,$MB_LetB_Rate_H);
            $row['TG_LetB_Rate_H']=change_rate($open,$TG_LetB_Rate_H);

            // 半场大小单独处理
            $h_dx_rate=get_other_ioratio(GAME_POSITION,$row["MB_Dime_Rate_H"],$row["TG_Dime_Rate_H"],100); // 默认都是香港盘
            $MB_Dime_Rate_H=$h_dx_rate[0]; // 主队
            $TG_Dime_Rate_H=$h_dx_rate[1]; // 客队
            $row['MB_Dime_Rate_H']=change_rate($open,$MB_Dime_Rate_H);
            $row['TG_Dime_Rate_H']=change_rate($open,$TG_Dime_Rate_H);

            $aData[] = $row;
        }
        break;
    case 'BK':
        switch ($more){
            case 's':// 篮球今日赛事玩法信息
                $mysql = "select MID,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,MB_Dime_S_H,TG_Dime_H,TG_Dime_S_H,MB_Dime_Rate_H,MB_Dime_Rate_S_H,TG_Dime_Rate_H,TG_Dime_Rate_S_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='$type' and `M_Start` > '$now' AND `M_Date` ='$m_date' and MID IN ($gid) and S_Show=1 and MB_TEAM!='' order by M_Start,MB_MID";
                $result = mysqli_query($dbLink, $mysql);

                $cou=mysqli_num_rows($result);
                $aData=[];
                while ($row=mysqli_fetch_assoc($result)){

                    $row["MB_Win_Rate"]=change_rate($open,$row["MB_Win_Rate"]); //主队独赢赔率
                    $row["TG_Win_Rate"]=change_rate($open,$row["TG_Win_Rate"]); //客队独赢赔率
                    $row["M_Flat_Rate"]=change_rate($open,$row["M_Flat_Rate"]);

                    // 全场让球单独处理
                    $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100); // 默认都是香港盘
                    $MB_LetB_Rate=$ra_rate[0]; // 主队
                    $TG_LetB_Rate=$ra_rate[1]; // 客队
                    $row['MB_LetB_Rate']=change_rate($open,$MB_LetB_Rate);
                    $row['TG_LetB_Rate']=change_rate($open,$TG_LetB_Rate);

                    $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100); // 默认都是香港盘
                    $MB_Dime_Rate=$ra_rate[0]; // 主队
                    $TG_Dime_Rate=$ra_rate[1]; // 客队
                    $row["MB_Dime_Rate"]=change_rate($open,$MB_Dime_Rate);
                    $row["TG_Dime_Rate"]=change_rate($open,$TG_Dime_Rate);

                    $row['S_Single_Rate']=change_rate($open,$row['S_Single_Rate']); // 主队单双
                    $row['S_Double_Rate']=change_rate($open,$row['S_Double_Rate']); // 客队单双

                    $row["MB_Win_Rate_H"]=change_rate($open,$row["MB_Win_Rate_H"]);
                    $row["TG_Win_Rate_H"]=change_rate($open,$row["TG_Win_Rate_H"]);
                    $row["M_Flat_Rate_H"]=change_rate($open,$row["M_Flat_Rate_H"]);
                    $row['MB_LetB_Rate_H']=change_rate($open,$row['MB_LetB_Rate_H']); // 半场主队让球赔率
                    $row['TG_LetB_Rate_H']=change_rate($open,$row['TG_LetB_Rate_H']); // 半场客队让球赔率

                    $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_Dime_Rate_H"],$row["MB_Dime_Rate_S_H"],100); // 默认都是香港盘
                    $MB_Dime_Rate_H=$ra_rate[0]; // 主队半场大的赔率
                    $MB_Dime_Rate_S_H=$ra_rate[1]; // 主队半场小的赔率
                    $row["MB_Dime_Rate_H"]=change_rate($open,$MB_Dime_Rate_H);
                    $row["MB_Dime_Rate_S_H"]=change_rate($open,$MB_Dime_Rate_S_H);

                    $ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_Dime_Rate_H"],$row["TG_Dime_Rate_S_H"],100); // 默认都是香港盘
                    $TG_Dime_Rate_H=$ra_rate[0]; // 客队半场大的赔率
                    $TG_Dime_Rate_S_H=$ra_rate[1]; //客队半场小的赔率
                    $row["TG_Dime_Rate_H"]=change_rate($open,$TG_Dime_Rate_H);
                    $row["TG_Dime_Rate_S_H"]=change_rate($open,$TG_Dime_Rate_S_H);

                    $aData[] = $row;

                }

                break;
            case 'r':// 篮球滚球玩法信息
                $mysql = "select MID,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,
`MB_Win_Rate_RB`,`TG_Win_Rate_RB`,`M_Flat_Rate_RB`,`M_LetB_RB`,`T_LetB_RB`,`MB_LetB_Rate_RB`,`TG_LetB_Rate_RB`,MB_Dime_H,MB_Dime_S_H,TG_Dime_H,TG_Dime_S_H,MB_Dime_Rate_H,MB_Dime_Rate_S_H,TG_Dime_Rate_H,TG_Dime_Rate_S_H,
`MB_Dime_RB`,`TG_Dime_RB`,`MB_Dime_Rate_RB`,`TG_Dime_Rate_RB`,`ShowTypeRB`,`ShowTypeHRB`,`MB_Win_Rate_RB_H`,`TG_Win_Rate_RB_H`,`M_Flat_Rate_RB_H`,`M_LetB_RB_H`,`MB_LetB_Rate_RB_H`,`TG_LetB_Rate_RB_H`,`MB_Dime_RB_H`,`MB_Dime_RB_S_H`,`TG_Dime_RB_H`,`TG_Dime_RB_S_H`,`MB_Dime_Rate_RB_H`,`MB_Dime_Rate_RB_S_H`,`TG_Dime_Rate_RB_H`,`TG_Dime_Rate_RB_S_H`,
MB_Ball,TG_Ball,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play,nowSession from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='$type' AND `M_Date` ='$m_date' and MID IN ($gid) and MB_TEAM!='' order by MID";
                $result = mysqli_query($dbLink, $mysql);

                $cou=mysqli_num_rows($result);
                $aData=[];
                while ($row=mysqli_fetch_assoc($result)){

                    //篮球第四节开始后的盘口不允许投注（包含第四节、加时赛）
                    if ($row['nowSession']=='Q4' || $row['nowSession']=='OT'){
                        $row['MB_Win_Rate_RB'] = '';
                        $row['TG_Win_Rate_RB'] = '';
                        $row['M_Flat_Rate_RB'] = '';
                        $row['MB_LetB_Rate_RB'] = '';
                        $row['TG_LetB_Rate_RB'] = '';
                        $row["MB_Dime_Rate_RB"] = '';
                        $row["TG_Dime_Rate_RB"] = '';
                        $row['MB_Win_Rate_RB_H'] = '';
                        $row['TG_Win_Rate_RB_H'] = '';
                        $row['M_Flat_Rate_RB_H'] = '';
                        $row['MB_LetB_Rate_RB_H'] = '';
                        $row['TG_LetB_Rate_RB_H'] = '';
                        $row["MB_Dime_Rate_RB_H"] = '';
                        $row["MB_Dime_Rate_RB_S_H"] = '';
                        $row["TG_Dime_Rate_RB_H"] = '';
                        $row["TG_Dime_Rate_RB_S_H"] = '';
                    }else {

                        $row['MB_Win_Rate_RB'] = change_rate($open, $row['MB_Win_Rate_RB']);
                        $row['TG_Win_Rate_RB'] = change_rate($open, $row['TG_Win_Rate_RB']);
                        $row['M_Flat_Rate_RB'] = change_rate($open, $row['M_Flat_Rate_RB']);

                        // 全场让球单独处理
                        $ra_rate = get_other_ioratio(GAME_POSITION, $row["MB_LetB_Rate_RB"], $row["TG_LetB_Rate_RB"], 100); // 默认都是香港盘
                        $MB_LetB_Rate_RB = $ra_rate[0]; // 主队
                        $TG_LetB_Rate_RB = $ra_rate[1]; // 客队
                        $row['MB_LetB_Rate_RB'] = change_rate($open, $MB_LetB_Rate_RB);
                        $row['TG_LetB_Rate_RB'] = change_rate($open, $TG_LetB_Rate_RB);

                        // 全场大小单独处理
                        $ra_rate = get_other_ioratio(GAME_POSITION, $row["MB_Dime_Rate_RB"], $row["TG_Dime_Rate_RB"], 100); // 默认都是香港盘
                        $MB_Dime_Rate_RB = $ra_rate[0]; // 主队
                        $TG_Dime_Rate_RB = $ra_rate[1]; // 客队
                        $row["MB_Dime_Rate_RB"] = change_rate($open, $MB_Dime_Rate_RB);
                        $row["TG_Dime_Rate_RB"] = change_rate($open, $TG_Dime_Rate_RB);

                        $row['MB_Win_Rate_RB_H'] = change_rate($open, $row['MB_Win_Rate_RB_H']);
                        $row['TG_Win_Rate_RB_H'] = change_rate($open, $row['TG_Win_Rate_RB_H']);
                        $row['M_Flat_Rate_RB_H'] = change_rate($open, $row['M_Flat_Rate_RB_H']);

                        // 半场让球单独处理
                        $ra_rate = get_other_ioratio(GAME_POSITION, $row["MB_LetB_Rate_RB_H"], $row["TG_LetB_Rate_RB_H"], 100); // 默认都是香港盘
                        $MB_LetB_Rate_RB_H = $ra_rate[0]; // 主队
                        $TG_LetB_Rate_RB_H = $ra_rate[1]; // 客队
                        $row['MB_LetB_Rate_RB_H'] = change_rate($open, $MB_LetB_Rate_RB_H);
                        $row['TG_LetB_Rate_RB_H'] = change_rate($open, $TG_LetB_Rate_RB_H);

                        // 得分大小主队大小单独处理
                        $ra_rate = get_other_ioratio(GAME_POSITION, $row["MB_Dime_Rate_RB_H"], $row["MB_Dime_Rate_RB_S_H"], 100); // 默认都是香港盘
                        $MB_Dime_Rate_RB_H = $ra_rate[0]; // 大
                        $MB_Dime_Rate_RB_S_H = $ra_rate[1]; // 小
                        $row["MB_Dime_Rate_RB_H"] = change_rate($open, $MB_Dime_Rate_RB_H);
                        $row["MB_Dime_Rate_RB_S_H"] = change_rate($open, $MB_Dime_Rate_RB_S_H);

                        // 得分大小客队大小单独处理
                        $ra_rate = get_other_ioratio(GAME_POSITION, $row["TG_Dime_Rate_RB_H"], $row["TG_Dime_Rate_RB_S_H"], 100); // 默认都是香港盘
                        $TG_Dime_Rate_RB_H = $ra_rate[0]; // 大
                        $TG_Dime_Rate_RB_S_H = $ra_rate[1]; // 小
                        $row["TG_Dime_Rate_RB_H"] = change_rate($open, $TG_Dime_Rate_RB_H);
                        $row["TG_Dime_Rate_RB_S_H"] = change_rate($open, $TG_Dime_Rate_RB_S_H);
                    }

                    $aData[] = $row;
                }
                break;
        }
        break;
    case 'BU':
        $mysql = "select MID,M_Time,M_Date,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,MB_Dime_S_H,TG_Dime_H,TG_Dime_S_H,MB_Dime_Rate_H,MB_Dime_Rate_S_H,TG_Dime_Rate_H,TG_Dime_Rate_S_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,more,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BU' and `M_Date` >'$m_date' and MID IN ($gid) and S_Show=1 and MB_Team!='' ".$date." order by M_Start,MB_Team,MB_MID";
        $result = mysqli_query($dbLink, $mysql);
        $cou=mysqli_num_rows($result);
        $aData=[];
        while ($row=mysqli_fetch_assoc($result)){

            $row["MB_Win_Rate"]=change_rate($open,$row["MB_Win_Rate"]); //主队独赢赔率
            $row["TG_Win_Rate"]=change_rate($open,$row["TG_Win_Rate"]); //客队独赢赔率
            $row["M_Flat_Rate"]=change_rate($open,$row["M_Flat_Rate"]);
            $row['MB_LetB_Rate']=change_rate($open,$row['MB_LetB_Rate']);
            $row['TG_LetB_Rate']=change_rate($open,$row['TG_LetB_Rate']);
            $row["MB_Dime_Rate"]=change_rate($open,$row["MB_Dime_Rate"]);
            $row["TG_Dime_Rate"]=change_rate($open,$row["TG_Dime_Rate"]);
            $row['S_Single_Rate']=change_rate($open,$row['S_Single_Rate']); // 主队单双
            $row['S_Double_Rate']=change_rate($open,$row['S_Double_Rate']); // 客队单双

            $row["MB_Win_Rate_H"]=change_rate($open,$row["MB_Win_Rate_H"]);
            $row["TG_Win_Rate_H"]=change_rate($open,$row["TG_Win_Rate_H"]);
            $row["M_Flat_Rate_H"]=change_rate($open,$row["M_Flat_Rate_H"]);
            $row['MB_LetB_Rate_H']=change_rate($open,$row['MB_LetB_Rate_H']); // 半场主队让球赔率
            $row['TG_LetB_Rate_H']=change_rate($open,$row['TG_LetB_Rate_H']); // 半场客队让球赔率

            $row["MB_Dime_Rate_H"]=change_rate($open,$row["MB_Dime_Rate_H"]); // 主队半场大的赔率
            $row["MB_Dime_Rate_S_H"]=change_rate($open,$row["MB_Dime_Rate_S_H"]); // 主队半场小的赔率
            $row["TG_Dime_Rate_H"]=change_rate($open,$row["TG_Dime_Rate_H"]); // 客队半场大的赔率
            $row["TG_Dime_Rate_S_H"]=change_rate($open,$row["TG_Dime_Rate_S_H"]); //客队半场小的赔率

            $aData[] = $row;

        }
        break;
    default:break;
}


if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {

    $status = '200';
    $describe = 'success';
    original_phone_request_response($status,$describe,$aData);

}else {
    if ($cou == 0) {
        echo json_encode([]);
    } else {
        echo json_encode(array_values($aData));
    }
}
