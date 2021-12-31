<?php
/*
 * /get_game_allbets.php  更多玩法接口
 *
 * @param gid
 * @param gtype FT 足球 BK 篮球
 * @param showtype FU 早盘 FT 今日赛事 RB 滚球
 * */

//error_reporting(E_ALL);
//ini_set('display_errors','On');
include_once('../include/config.inc.php');
require_once("../../../common/sportCenterData.php");
include "../include/address.mem.php";
include('../include/curl_http.php');
include('../include/define_function_list.inc.php');
include_once "../../../common/sportapi/get_game_allbets.php";
