<?php
/**
 * /var_by_league_api.php  联赛下面的盘口列表（让球、大小）
 *
 * @param  type   FT 足球，FU 足球早盘，BK 篮球，BU 篮球早盘
 * @param  more   s 今日赛事， r 滚球
 * @param  gid  3321118,3321062
 */

include_once('include/config.inc.php');
require ("include/curl_http.php");
require ("include/define_function_list.inc.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {

    $status='401.1';
    $describe="请重新登录";
    original_phone_request_response($status,$describe);

}
ob_clean();
include_once "../../common/sportapi/var_by_league_api.php";

