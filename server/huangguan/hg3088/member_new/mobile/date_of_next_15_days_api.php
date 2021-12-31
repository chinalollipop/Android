<?php
/**
 * /date_of_next_15_days_api.php
 * 返回15天的日期
 */

include_once('include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {

    $status = '401.1';
    $describe = '你的登录信息已过期，请先登录!';
    original_phone_request_response($status,$describe);

}

$date_today = date('Y-m-d');
$date_15th_day = date('Y-m-d', strtotime('+14 day'));
$date = getDateFromRange($date_today,$date_15th_day);
$aData=[];
foreach ($date as $k => $v){
    $aData[$k]['date'] = $v;
    $aData[$k]['date_txt'] = date('m',strtotime($v)).'月'.date('d',strtotime($v)).'日';
}

$status = '200';
$describe = 'success';
original_phone_request_response($status,$describe,$aData);
