<?php
session_start();
/**
 * /FT_order_re_finish_api.php
 * 足球滚球全场下注接口
 *
 * gid  比赛盘口唯一ID
 * active   1 足球滚球、今日赛事, 11 足球早餐，2 篮球滚球、今日赛事, 22 篮球早餐
 * line_type  玩法列号
 * odd_f_type      默认传参H。 H 香港盘，M 马来盘，I 印尼盘，E 欧洲盘
 * gold  金额
 * type   H 主队独赢 C 客队独赢 N 和局  C 滚球大小-小  H 滚球大小-大 C 球队得分大小-主队 H 球队得分大小-客队 H 主队让球 C 客队让球
 * pay_type   0 信用额投注  1 现金投注【此参数暂时不需要】
 * ioradio_r_h  赔率 （让球，大小，半场让球，半场大小 ）投注时，传参
 * rtype  单双玩法投注传参，让后赋值给mtype   ODD 单 EVEN 双
 * wtype 全场大小、半场大小、球队得分大小 OUH 大，OUC 小，ROUH 球队得分大小-大，ROUC 球队得分大小-小
 *
 * strong
 * gnum  投注的队伍ID
 * ioradio_r_h  赔率
 * randomNum 随机数
 * autoOdd 自动接收较佳赔率
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');
include "../include/address.mem.php";
include_once('../include/config.inc.php');
require_once("../../../common/sportCenterData.php");
require ("../include/curl_http.php");
//require ("../include/define_function_list.inc.php");
include_once("../../../common/sportapi/define_function_list.inc.php");


$langx=$_SESSION['Language'];
require ("../include/traditional.$langx.inc.php");

include_once "../../../common/order/FT_order_re_finish_api.php";
