<?php
/**
 * /var_by_league_api.php  联赛下面的盘口列表（让球、大小）
 *
 * @param  type   FT 足球，FU 足球早盘，BK 篮球，BU 篮球早盘
 * @param  more   s 今日赛事， r 滚球
 * @param  gid  3321118,3321062
 */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include_once('../../include/config.inc.php');
require ("../../include/curl_http.php");
//include_once ("../../include/define_function_list.inc.php");
include_once ("../../../../../common/sportapi/define_function_list.inc.php");
ob_clean();
include_once "../../../../../common/sportapi/var_by_league_api.php";
