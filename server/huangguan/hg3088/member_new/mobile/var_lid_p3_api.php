<?php
/**
 * /var_lid_p3_api.php  体育联赛数据接口_综合过关
 * @param  gtype   FT 足球，BK 篮球
 * @param  sorttype   league 联盟排序  time 时间排序
 * @param  mdate  日期 2018-09-15
 * @param  showtype
 * @param  M_League  欧洲冠军杯（显示此联赛全部冠军盘口，以及赔率）
 * @param  gid
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');
include_once('include/config.inc.php');
require ("include/curl_http.php");
require ("include/define_function_list.inc.php");


$langx=$_SESSION['Language']?$_SESSION['Language']:'zh-cn';
require ("include/traditional.$langx.inc.php");

include_once "../../common/sportapi/var_lid_p3_api.php";