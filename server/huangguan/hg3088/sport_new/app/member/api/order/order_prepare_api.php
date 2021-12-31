<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');
/**
 * 选择玩法和赔率，准备投注接口
 * order/order_prepare_api.php
 *
 * @param  order_method FT_rm 滚球独赢，FT_re 滚球让球，FT_rou 滚球大小，FT_rt 滚球单双，FT_hrm 滚球半场独赢，FT_hre 滚球半场让球，FT_hrou 滚球半场大小，FT_m 独赢，FT_r 让球，FT_ou 大小，FT_t 单双、单双 - 上半场、总进球数、总进球数-上半场，FT_hm 半场独赢，FT_hr 半场让球，FT_hou 半场大小，BK_re 滚球让球，BK_rou 滚球大小，BK_m 独赢，BK_r 让球，BK_ou 大小，BK_t 单双，BK_ouhc 球队得分大小
 * @param  gid
 * @param  type  H 主队 C 客队  N 和
 * @param  wtype  M 独赢，R 让球，大小 OU，单双 EO，半场独赢 HM，半场让球 HR，半场大小 HOU
 * @param  rtype  ODD 单 EVEN 双
 * @param  odd_f_type  H
 * @param  error_flag
 * @param  order_type
 * @param  flag  all 所有玩法
 */

ob_clean();
include_once('../../include/config.inc.php');
require ("../../include/curl_http.php");
include_once ("../../include/address.mem.php");
require_once("../../../../../common/sportCenterData.php");
//include_once ("../../include/define_function_list.inc.php");
include_once ("../../../../../common/sportapi/define_function_list.inc.php");
include_once ("../../include/traditional.zh-cn.inc.php");
include_once "../../../../../common/order/order_prepare_api.php";
