<?php
session_start();
/**
 * 获取比赛赛事数量
 *
 */

require ("../include/config.inc.php");
include "../include/address.mem.php";
$aData = array() ;
//if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//    $status = '502';
//    $describe = '你已退出登录，请重新登录';
//    original_phone_request_response($status,$describe,$aData);
//}
$showtype = isset($_REQUEST['showtype'])?$_REQUEST['showtype']:'today'; // $type : today 默认今天 , future, rb


$dcRedisObj = new Ciredis('datacenter');
// ----------------------------------------------------------统计足球盘口数目 Start
$cou_num_ft_gq = $dcRedisObj->getSimpleOne("FT_Running_Num");// 滚球
$cou_num_ft_future = $dcRedisObj->getSimpleOne("FT_Future_Num");//R 早盘 盘口数
$cou_num_ft = $dcRedisObj->getSimpleOne("FT_Today_Num");//R  今日赛事 盘口数
// ----------------------------------------------------------统计足球盘口数目 End
// ----------------------------------------------------------统计篮球盘口数目 Start
$cou_num_bk_gq = $dcRedisObj->getSimpleOne("BK_Running_Num");// 滚球
$cou_num_bk_future = $dcRedisObj->getSimpleOne("BK_Future_Num");//R 早盘 盘口数
$cou_num_bk = $dcRedisObj->getSimpleOne("BK_Today_Num");//R  今日赛事 盘口数
// ----------------------------------------------------------统计篮球盘口数目 End


$ft_num = 0 ; // 足球
$bk_num = 0 ; // 篮球

switch ($showtype){
    case 'today': // 今日总计
        $ft_num = $cou_num_ft ;
        $bk_num = $cou_num_bk ;
        break;
    case 'future': // 早盘总计
        $ft_num = $cou_num_ft_future ;
        $bk_num = $cou_num_bk_future ;
        break;
    case 'rb': // 滚球总计
        $ft_num = $cou_num_ft_gq ;
        $bk_num = $cou_num_bk_gq ;
        break;
}

$aData = array(
    'FT_NUM' => $ft_num,
    'BK_NUM' => $bk_num,
    'TOTAL_TODAY_NUM' => $cou_num_ft + $cou_num_bk,
    'TOTAL_FUTURE_NUM' => $cou_num_ft_future + $cou_num_bk_future,
    'TOTAL_RB_NUM' => $cou_num_ft_gq + $cou_num_bk_gq,
);
$status = '200';
$describe = '获取数据成功';
original_phone_request_response($status,$describe,$aData);


