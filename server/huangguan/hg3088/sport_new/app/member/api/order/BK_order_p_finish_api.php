<?php
session_start();
/**
 * /order/BK_order_p_finish_api.php 篮球综合过关下注接口
 * active   2 篮球今日赛事, 22 篮球早餐
 * teamcount
 * gold  金额
 * wagerDatas
 * randomNum 随机数
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
include_once "../../../../../common/order/BK_order_p_finish_api.php";
