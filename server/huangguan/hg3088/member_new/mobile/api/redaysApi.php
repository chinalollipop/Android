<?php
/*
时间返回
*/
session_start();
include_once('../include/config.inc.php');

$todaytime=date("Y-m-d H:i:s"); // 当前时间
$todaydate=date('Y-m-d'); // 当天日期
$yesdate=date('Y-m-d',time()-1*24*60*60) ;// 昨天
$weekdate=date('Y-m-d',time()-7*24*60*60) ;// 一周前
$firstDate=date('Y-m-01', strtotime(date("Y-m-d"))); // 当月第一天
$endDate=date('Y-m-d', strtotime("$firstDate +1 month -1 day")); // 当月最后一天

$resdata = array(
    'nowtime'=>$todaytime,
    'today'=>$todaydate,
    'yestoday'=>$yesdate,
    'lastweek'=>$weekdate,
    'monfirst'=>$firstDate,
    'monend'=>$endDate,
);

// 近半个月日期

for($datei=1;$datei<16;$datei++){ // 从明天开始，往后 15 天数据
    $resdata['half'][]=array(
        'value'=>date('Y-m-d',time()+$datei*24*60*60),
        'str'=>date('m'.'月'.'d'.'日',time()+$datei*24*60*60)
    );
}

$status = '200';
$describe = '获取服务器时间成功';
original_phone_request_response($status,$describe,$resdata);

?>