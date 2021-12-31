<?php
/*
 * 冠军联赛数据接口
 * FStype   FT 足球 BK 篮球
 * mtype   4
 * showtype  future（早盘冠军）
 * M_League  欧洲冠军杯（显示此联赛全部冠军盘口，以及赔率）
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');

include_once('../../include/config.inc.php');
require ("../../include/curl_http.php");
include_once("../../../../../common/sportCenterData.php");
//include_once ("../../include/define_function_list.inc.php");
include_once ("../../../../../common/sportapi/define_function_list.inc.php");
include_once "../../../../../common/sportapi/get_game_allbets.php";