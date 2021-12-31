<?php
/**
 * /order/order_prepare_p3_api.php?teamcount=4&game=PRH,PRH,PRH,POUC&game_id=3363442,3363572,3363582,3363562  足球综合过关选择玩法和赔率，准备投注接口
 * @param  game
 * @param  game_id
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');

include_once('../../include/config.inc.php');
require ("../../include/curl_http.php");
include_once ("../../include/address.mem.php");
require_once("../../../../../common/sportCenterData.php");
//include_once ("../../include/define_function_list.inc.php");
include_once ("../../../../../common/sportapi/define_function_list.inc.php");
include_once ("../../include/traditional.zh-cn.inc.php");
ob_clean();
include_once "../../../../../common/order/order_prepare_p3_api.php";
