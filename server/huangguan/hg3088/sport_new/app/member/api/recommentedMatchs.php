<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
include_once("../include/activity.class.php");

$type = $_REQUEST['type']?$_REQUEST['type']:'';

$queryType = '';
switch ($type){
    case 'BK': $queryType = " and Type in ('BK','BU')"; break;
    case 'FT': $queryType = " and Type in ('FT','FU')"; break;
    default: break;
}

// 获取推荐赛事的接口
$redisObj = new Ciredis();
$sRecommendedMatchs = $redisObj->getSimpleOne('recommended_match');
$aRecommendedMatchs = json_decode($sRecommendedMatchs,true);
$aRecommendedMatchsMid = array_column($aRecommendedMatchs, 'MID');

//if (count($aRecommendedMatchsMid)<3){
//    $status = '400.1';
//    $describe = '推荐赛事至少3条，请联系管理员推荐赛事' ;
//    original_phone_request_response($status,$describe,$data);
//}

$sRecommendedMatchsMid = implode(',',$aRecommendedMatchsMid);
$sql = "SELECT `MID`,`Type`,`MB_Team`,`TG_Team`,`M_Date`,`M_Time`,`M_Start`,`M_Duration`,`M_League`,`MB_Dime`,`TG_Dime`,`MB_Win_Rate_RB`,`TG_Win_Rate_RB`,`M_Flat_Rate_RB`,`MB_Dime_RB`,`MB_Dime_Rate_RB`,`TG_Dime_RB`,`TG_Dime_Rate_RB`,`S_Single_Rate_RB`,`S_Double_Rate_RB`,
`MB_Win_Rate`,`TG_Win_Rate`,`M_Flat_Rate`,`S_Single_Rate`,`S_Double_Rate`,`M_LetB`,`MB_LetB_Rate`,`TG_LetB_Rate`,`MB_Dime`,`TG_Dime`,`MB_Dime_Rate`,`TG_Dime_Rate`
FROM `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE MID in ($sRecommendedMatchsMid) $queryType";
//$sql = "SELECT * FROM `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE MID in ($sRecommendedMatchsMid)";
$result = mysqli_query($dbCenterSlaveDbLink,$sql);
$now = date('Y-m-d H:i:s');
while ($row = mysqli_fetch_assoc($result)){
    $key = array_search($row['MID'], array_column($aRecommendedMatchs, 'MID'));
    $row['mb_team_logo_url'] = $aRecommendedMatchs[$key]['mb_team_logo_url'];
    $row['tg_team_logo_url'] = $aRecommendedMatchs[$key]['tg_team_logo_url'];
    if ($row['M_Start']>$now){
        $row['MB_Dime']="大" . str_replace('O', '', $row['MB_Dime_RB']);
        $row['TG_Dime']="小" . str_replace('U', '', $row['TG_Dime_RB']);
        $row['MB_Dime_Rate']=$row['MB_Dime_Rate_RB'];
        $row['TG_Dime_Rate']=$row['TG_Dime_Rate_RB'];
        $row['MB_Win_Rate']=$row['MB_Win_Rate_RB'];
        $row['TG_Win_Rate']=$row['TG_Win_Rate_RB'];
        $row['M_Flat_Rate']=$row['M_Flat_Rate_RB'];
        $row['S_Single_Rate']=$row['S_Single_Rate_RB'];
        $row['S_Double_Rate']=$row['S_Double_Rate_RB'];
    }
    else{
        $row['MB_Dime']="大" . str_replace('O', '', $row['MB_Dime']);
        $row['TG_Dime']="小" . str_replace('U', '', $row['TG_Dime']);
    }
    $data[] = $row;
}

$status = '200';
$describe = '推荐赛事获取成功' ;
original_phone_request_response($status,$describe,$data);
