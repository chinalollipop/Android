<?php
/**
 * /var_lid_api.php  体育联赛数据接口
 *
 * @param  gtype   FT 足球，BK 篮球
 * @param  showtype   RB 滚球 FT 今日赛事 FU 早盘
 * @param  sorttype   league 联盟排序  time 时间排序
 * @param  mdate  早盘日期
 */

include_once('../../include/config.inc.php');
require ("../../include/curl_http.php");
require_once("../../../../../common/sportCenterData.php");
//include_once ("../../include/define_function_list.inc.php");
include_once ("../../../../../common/sportapi/define_function_list.inc.php");
include_once ("../../include/traditional.zh-cn.inc.php");
include_once "../../../../../common/sportapi/var_lid_api.php";


