<?php
//session_start();
//include_once('../include/config.inc.php');

$test_flag = $_SESSION['test_flag']; // 0 正式帐号，1 测试账号
$user_id = $_SESSION['userid'];
$UserName = $_SESSION['UserName'];
$appRefer = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:''; // 13 苹果，14 安卓

/* 首页游戏人数 */
$numSport = 0;
$dcRedisObj = new Ciredis('datacenter');
$FT_Running_Num = $dcRedisObj->getSimpleOne("FT_Running_Num");
$FT_Today_Num = $dcRedisObj->getSimpleOne("FT_Today_Num");
$BK_Running_Num = $dcRedisObj->getSimpleOne("BK_Running_Num");
$BK_Today_Num = $dcRedisObj->getSimpleOne("BK_Today_Num");
$FT_Future_Num = $dcRedisObj->getSimpleOne("FT_Future_Num");
$BK_Future_Num = $dcRedisObj->getSimpleOne("BK_Future_Num");
$numSport = $FT_Running_Num+$FT_Today_Num+$BK_Running_Num+$BK_Today_Num ;

$arr = array(28,32,34,36,38,39,42,45,46,48,50,52,54,56,59,61,62,65,66,68,71,73,75,77,79,80,82,85,86,91,92,93,97);
shuffle($arr); // 打乱数组

$data = array();
$data['hgSportNum'] = $numSport;
$data['FT_Running_Num'] = $FT_Running_Num;
$data['FT_Today_Num'] = $FT_Today_Num;
$data['BK_Running_Num'] = $BK_Running_Num;
$data['BK_Today_Num'] = $BK_Today_Num;
$data['FT_Future_Num'] = $FT_Future_Num;
$data['BK_Future_Num'] = $BK_Future_Num;
$data['agLiveNum'] = $arr[1];
$data['ogLiveNum'] = $arr[2];
$data['bbinLiveNum'] = $arr[3];
$data['dsLiveNum'] = $arr[4];
$data['fydjNum'] = $arr[5];
$data['lhdjNum'] = $arr[12];
$data['vgChessNum'] = $arr[6];
$data['lyChessNum'] = $arr[7];
$data['kyChessNum'] = $arr[8];
$data['hgChessNum'] = $arr[9];
$data['klChessNum'] = $arr[11];
$data['lotteryChessNum'] = $arr[10];
$data['fishNum'] = '';
$data['fgGameNum'] = '';
$data['agGameNum'] = '';
$data['cqGameNum'] = '';
$data['mgGameNum'] = '';
$data['mwGameNum'] = '';

$status = '200';
$describe = '获取数据成功!';
original_phone_request_response($status,$describe,$data);

?>