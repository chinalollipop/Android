<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");

include_once ("../include/redis.php");
checkAdminLogin(); // 同一账号不能同时登陆

$resdata = array();
$indextype = isset($_REQUEST['indextype'])?$_REQUEST['indextype']:''; // 首页统计净输赢
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    if($action=='api'){
        $status = '400.01';
        $describe = '您的登录信息已过期,请重新登录!';
        original_phone_request_response($status,$describe,$resdata);
    }else{
        echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
        exit;
    }

}

if( (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level']!='D') {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_SESSION['Oid'];
$loginname=$_SESSION['UserName'];
$lever = $_SESSION['admin_level'] ;

/**
 * 系统管理员，搜索全部数据
 * 代理账号，只能搜索下级会员的数据
 */

/*$sql = "select Admin_Url from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$admin_url=explode(";",$row['Admin_Url']);
if (in_array($_SERVER['HTTP_HOST'],array($admin_url[0],$admin_url[1],$admin_url[2],$admin_url[3]))){*/
if($_SESSION['Level'] == 'M') {
    $web=DBPREFIX.'web_system_data';
    $World = 'cdm323'; // 总代理
    $Corprator='bdm223'; // 股东
}else{
    $web=DBPREFIX.'web_agents_data';
    $Agent=$loginname;
}
$mysql="select ID,UserName from $web where Oid='$uid' and UserName='$loginname'";
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);


// 分2段查询，历史报表，注单报表
// PS：泛亚电竞的报表是实时更新的
//$_REQUEST['date_start'] = '2018-03-01';
//$_REQUEST['date_end'] = '2018-03-28';

$date_start = date('Y-m-d',strtotime($_REQUEST['date_start']));
$date_end = date('Y-m-d',strtotime($_REQUEST['date_end']));

//echo $date_start.'=='.$date_end;

// 首页是否统计参数
$isHome = $_REQUEST['isHome'] ? trim($_REQUEST['isHome']) : 0;

$neededSearchCurrentBillTable = false;
if(isset($_REQUEST['date_end'])) {
    if($date_start == date("Y-m-d") && $date_end >= date("Y-m-d") ) {

        //搜索开始和结束时间均为当天
        //置需要从当前的订单表里面读取记录flag为真，并且设置好起始时间以及结束时间
        $neededSearchCurrentBillTable = true;
        $current_start_day = date("Y-m-d");
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $_REQUEST['date_end'] = date("Y-m-d 23:59:59", strtotime("-1 day"));

    }elseif($date_start < date("Y-m-d") && $date_end >= date("Y-m-d") && (int)date("G") < 3) {

        //搜索开始时间小于当天，搜索结束时间为当天并且当前时间小于【美东时间】凌晨3点,则昨天的历史报表还未生成，需将搜索分为两段
        //置需要从当前的订单表里面读取记录flag为真，并且设置好起始时间以及结束时间
        $neededSearchCurrentBillTable = true;
        $current_start_day = date("Y-m-d", strtotime("-1 day"));
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $_REQUEST['date_end'] = date("Y-m-d 23:59:59", strtotime("-2 day"));

    }else if($date_start < date("Y-m-d") && $date_end == date("Y-m-d", strtotime("-1 day")) && (int)date("G") < 3) {

        //搜索结束时间为昨天并且当前时间小于【美东时间】凌晨3点,则昨天的历史报表未生成，需要从现有的订单记录里面计算
        $neededSearchCurrentBillTable = true;
        $current_start_day = date('Y-m-d', strtotime($_REQUEST['date_end']));
        $current_end_day = date('Y-m-d 23:59:59',strtotime($_REQUEST['date_end']));
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $_REQUEST['date_end'] = date("Y-m-d 11:59:59", strtotime("-2 day"));

    }else if($date_start <= date("Y-m-d") && $date_end >= date("Y-m-d") && (int)date("G") >= 3) {

        //搜索结束时间为当天(或大于当天)并且当前时间大于【美东时间】凌晨3点,则昨天的历史报表已生成，只需要今天的报表从现有的订单记录里面计算
        $neededSearchCurrentBillTable = true;
        $current_start_day = date("Y-m-d");
        $current_end_day = date("Y-m-d 23:59:59");
        //从历史报表里面搜索的截止时间为前台晚上的23:59:59
        $_REQUEST['date_end'] = date("Y-m-d 23:59:59", strtotime("-1 day"));

    }

}

$sWhere = ' 1 ';
if ($web == DBPREFIX.'web_agents_data'){
    $agent_id = $row['ID'];
    $agent_username = $row['UserName'];
    $sWhere_hg = $sWhere. " and Agents = '$agent_username'";
    $sWhere_cp = $sWhere. " and hg_agent_uid = $agent_id ";
    $sWhere_ag = $sWhere. " and Agents = '$agent_username'";
    $sWhere_ky = $sWhere . " and agents = '$agent_username'";
    $sWhere_hgqp = $sWhere . " and agents = '$agent_username'";
    $sWhere_vgqp = $sWhere . " and agents = '$agent_username'";
    $sWhere_lyqp = $sWhere . " and agents = '$agent_username'";
    $sWhere_mg = $sWhere . " and agents = '$agent_username'";
    $sWhere_avia = $sWhere . " and agents = '$agent_username'";
    $sWhere_fire = $sWhere . " and agents = '$agent_username'";
    $sWhere_thirdcp = $sWhere . " and agents = '$agent_username'";
    $sWhere_og = $sWhere . " and agents = '$agent_username'";
    $sWhere_mw = $sWhere . " and agents = '$agent_username'";
    $sWhere_cq = $sWhere . " and agents = '$agent_username'";
    $sWhere_fg = $sWhere . " and agents = '$agent_username'";
    $sWhere_bbin = $sWhere . " and agents = '$agent_username'";
    $sWhere_kl = $sWhere . " and agents = '$agent_username'";
}else{
    $sWhere_hg = $sWhere;
    $sWhere_cp = $sWhere;
    $sWhere_ag = $sWhere;
    $sWhere_ky = $sWhere;
    $sWhere_hgqp = $sWhere;
    $sWhere_vgqp = $sWhere;
    $sWhere_lyqp = $sWhere;
    $sWhere_mg = $sWhere;
    $sWhere_avia = $sWhere;
    $sWhere_fire = $sWhere;
    $sWhere_thirdcp = $sWhere;
    $sWhere_og = $sWhere;
    $sWhere_mw = $sWhere;
    $sWhere_cq = $sWhere;
    $sWhere_fg = $sWhere;
    $sWhere_bbin = $sWhere;
    $sWhere_kl = $sWhere;
}
$sWhere_cp .= " and hg_agent_uid!=521 and hg_agent_uid!=522";

// 统计历史报表数据
// 体育主数据
//$res_hg = mysqli_query($dbLink, "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from ".DBPREFIX."web_report_history_report_data where $sWhere_hg AND M_Date BETWEEN '".$_REQUEST['date_start']."' and '".$_REQUEST['date_end']."' ");
$res_hg = mysqli_query($dbLink, "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from ".DBPREFIX."web_report_history_report_data where $sWhere_hg AND M_Date >= '".date('Y-m-d',strtotime($_REQUEST['date_start']))."' and M_Date<='".date('Y-m-d',strtotime($_REQUEST['date_end']))."' ");
$cou_hg = mysqli_num_rows($res_hg);
if ($cou_hg>0){
    $row_hg = mysqli_fetch_assoc($res_hg);
    $row_hg['user_win'] = $row_hg['user_win'] - $row_hg['user_win']*2;
    $data_history_hg = $row_hg;
    $data_total_hg = $data_history_hg;
}

// 彩票主数据，测试代理线不统计
//echo '<pre>';
//print_r( $_REQUEST['date_start'].'-'.$_REQUEST['date_end'] );
//echo '</pre>';
$start_day_cp = strtotime($_REQUEST['date_start']); // 转为北京时间时间戳
$end_day_cp = strtotime($_REQUEST['date_end']); // 转为北京时间时间戳
//echo $start_day_cp.'-'.$end_day_cp;
$aCp_default = $database['cpDefault'];
$cpDbLink = @mysqli_connect($aCp_default['host'],$aCp_default['user'],$aCp_default['password'],$aCp_default['dbname'],$aCp_default['port']) or die("mysqli connect error".mysqli_connect_error());
$sql = "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from gxfcy_history_bill_report_less_12hours where $sWhere_cp AND bet_time BETWEEN '".$start_day_cp."' and '".$end_day_cp."' ";
$res_cp = mysqli_query($cpDbLink, $sql);
//echo '<br>';
//print_r( $sql );
//echo '<br>';
$cou_cp = mysqli_num_rows($res_cp);
if ($cou_cp>0) {
    $row_cp = mysqli_fetch_assoc($res_cp);
    $row_cp['user_win'] = $row_cp['user_win'] - $row_cp['user_win']*2;
    $data_history_cp = $row_cp;
    $data_total_cp = $data_history_cp;
}

// AG视讯主数据
$res_ag = mysqli_query($dbLink, "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(profit) as user_win from ".DBPREFIX."ag_projects_history_report where $sWhere_ag AND bet_time BETWEEN '".$_REQUEST['date_start']."' and '".$_REQUEST['date_end']."' and game_code='BR'");
$cou_ag = mysqli_num_rows($res_ag);
if ($cou_ag>0) {
    $row_ag = mysqli_fetch_assoc($res_ag);
    $row_ag['user_win'] = $row_ag['user_win'] - $row_ag['user_win']*2;
    $data_history_ag = $row_ag;
    $data_total_ag = $data_history_ag;
}


// KY主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` 
        FROM " . DBPREFIX . "ky_history_report 
        WHERE {$sWhere_ky} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_ky = mysqli_query($dbLink, $sql);
$cou_ky = mysqli_num_rows($res_ky);
if ($cou_ky > 0) {
    $row_ky = mysqli_fetch_assoc($res_ky);
    $row_ky['user_win'] = -$row_ky['user_win'];
    $data_history_ky = $row_ky;
    $data_total_ky = $data_history_ky;
}

// AG电子主数据
$res_ag_dianzi = mysqli_query($dbLink, "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(profit) as user_win from ".DBPREFIX."ag_projects_history_report where $sWhere_ag AND bet_time BETWEEN '".$_REQUEST['date_start']."' and '".$_REQUEST['date_end']."' and (game_code='' or game_code='SLOT') ");
$cou_ag_dianzi = mysqli_num_rows($res_ag_dianzi);
if ($cou_ag_dianzi>0) {
    $row_ag_dianzi = mysqli_fetch_assoc($res_ag_dianzi);
    $row_ag_dianzi['user_win'] = $row_ag_dianzi['user_win'] - $row_ag_dianzi['user_win']*2;
    $data_history_ag_dianzi = $row_ag_dianzi;
    $data_total_ag_dianzi = $data_history_ag_dianzi;
}

// AG捕鱼王打鱼主数据
$res_ag_dayu = mysqli_query($dbLink, "select sum(BulletOutNum) as count_pay, sum(Cost) as total, sum(Cost) as valid_money, sum(Earn) as shouru from ".DBPREFIX."ag_buyu_scene where $sWhere_ag AND EndTime BETWEEN '".$_REQUEST['date_start']."' and '".$_REQUEST['date_end']."' ");
$cou_ag_dayu = mysqli_num_rows($res_ag_dayu);
if ($cou_ag_dayu>0) {
    $row_ag_dayu = mysqli_fetch_assoc($res_ag_dayu);
    //$row_ag_dayu['user_win'] = $row_ag_dayu['shouru'] - $row_ag_dayu['valid_money'];
    $row_ag_dayu['user_win'] = $row_ag_dayu['valid_money']- $row_ag_dayu['shouru']  ;
    $data_history_ag_dayu = $row_ag_dayu;
    $data_total_ag_dayu = $data_history_ag_dayu;
}

// HGQP主数据
//$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue`
//        FROM " . DBPREFIX . "ff_history_report
//        WHERE {$sWhere_hgqp} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
//$res_hgqp = mysqli_query($dbLink, $sql);
//$cou_hgqp = mysqli_num_rows($res_hgqp);
//if ($cou_hgqp > 0) {
//    $row_hgqp = mysqli_fetch_assoc($res_hgqp);
//    $row_hgqp['user_win'] = -$row_hgqp['user_win'];
//    $data_history_hgqp = $row_hgqp;
//    $data_total_hgqp = $data_history_hgqp;
//}


// VGQP主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` 
        FROM " . DBPREFIX . "vg_history_report 
        WHERE {$sWhere_vgqp} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_vgqp = mysqli_query($dbLink, $sql);
$cou_vgqp = mysqli_num_rows($res_vgqp);
if ($cou_vgqp > 0) {
    $row_vgqp = mysqli_fetch_assoc($res_vgqp);
    $row_vgqp['user_win'] = -$row_vgqp['user_win'];
    $data_history_vgqp = $row_vgqp;
    $data_total_vgqp = $data_history_vgqp;
}

// 乐游棋牌主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` 
        FROM " . DBPREFIX . "ly_history_report 
        WHERE {$sWhere_lyqp} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_lyqp = mysqli_query($dbLink, $sql);
$cou_lyqp = mysqli_num_rows($res_lyqp);
if ($cou_lyqp > 0) {
    $row_lyqp = mysqli_fetch_assoc($res_lyqp);
    $row_lyqp['user_win'] = -$row_lyqp['user_win'];
    $data_history_lyqp = $row_lyqp;
    $data_total_lyqp = $data_history_lyqp;
}

// 快乐棋牌主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "kl_history_report 
        WHERE {$sWhere_kl} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_kl = mysqli_query($dbLink, $sql);
$cou_kl = mysqli_num_rows($res_kl);
if ($cou_kl > 0) {
    $row_kl = mysqli_fetch_assoc($res_kl);
    $row_kl['user_win'] = -($row_kl['user_win']-$row_kl['total']);
    $data_history_klqp = $row_kl;
    $data_total_klqp = $data_history_klqp;
}

// MG电子主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "mg_history_report 
        WHERE {$sWhere_mg} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_mg = mysqli_query($dbLink, $sql);
$cou_mg = mysqli_num_rows($res_mg);
if ($cou_mg > 0) {
    $row_mg = mysqli_fetch_assoc($res_mg);
    $row_mg['user_win'] = -$row_mg['user_win'];
    $data_history_mg = $row_mg;
    $data_total_mg = $data_history_mg;
}

// 泛亚电竞主数据（实时统计报表、无需计算当天的报表数据）
if ($current_end_day){
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "avia_history_report 
        WHERE {$sWhere_avia} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$current_end_day}'";
}else{
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "avia_history_report 
        WHERE {$sWhere_avia} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
}
$res_avia = mysqli_query($dbLink, $sql);
$cou_avia = mysqli_num_rows($res_avia);
if ($cou_avia > 0) {
    $row_avia = mysqli_fetch_assoc($res_avia);
    $row_avia['user_win'] = -$row_avia['user_win'];
    $data_history_avia = $row_avia;
    $data_total_avia = $data_history_avia;
    $data_total_avia['count_pay'] =0; // 置为 0
}

// 雷火电竞主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "fire_history_report 
        WHERE {$sWhere_fire} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_fire = mysqli_query($dbLink, $sql);
$cou_fire = mysqli_num_rows($res_fire);
if ($cou_fire > 0) {
    $row_fire = mysqli_fetch_assoc($res_fire);
    $row_fire['user_win'] = -$row_fire['user_win'];
    $data_history_fire = $row_fire;
    $data_total_fire = $data_history_fire;
}

// 第三方彩票信用主数据（报表数据）
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "web_third_ssc_history_report 
        WHERE {$sWhere_thirdcp} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_ssc = mysqli_query($dbLink, $sql);
$cou_ssc = mysqli_num_rows($res_ssc);
if ($cou_ssc > 0) {
    $row_ssc = mysqli_fetch_assoc($res_ssc);
    $row_ssc['user_win'] = $row_ssc['total']-$row_ssc['user_win'];
    $data_history_ssc = $row_ssc;
    $data_total_ssc = $data_history_ssc;
}

// 第三方彩票官方主数据（报表数据）
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "web_third_projects_history_report 
        WHERE {$sWhere_thirdcp} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_project = mysqli_query($dbLink, $sql);
$cou_project = mysqli_num_rows($res_project);
if ($cou_project > 0) {
    $row_project = mysqli_fetch_assoc($res_project);
    $row_project['user_win'] = $row_project['total']-$row_project['user_win'];
    $data_history_project = $row_project;
    $data_total_project = $data_history_project;
}

// 第三方彩票官方追号主数据（报表数据）
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "web_third_traces_history_report 
        WHERE {$sWhere_thirdcp} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_trace = mysqli_query($dbLink, $sql);
$cou_trace = mysqli_num_rows($res_trace);
if ($cou_trace > 0) {
    $row_trace = mysqli_fetch_assoc($res_trace);
    $row_trace['user_win'] = $row_trace['total']-$row_trace['user_win'];
    $data_history_trace = $row_trace;
    $data_total_trace = $data_history_trace;
}

// OG视讯主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "og_history_report 
        WHERE {$sWhere_og} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_og = mysqli_query($dbLink, $sql);
$cou_og = mysqli_num_rows($res_og);
if ($cou_og > 0) {
    $row_og = mysqli_fetch_assoc($res_og);
    $row_og['user_win'] = -$row_og['user_win'];
    $data_history_og = $row_og;
    $data_total_og = $data_history_og;
}

// WM电子主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "mw_history_report 
        WHERE {$sWhere_mw} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
//echo $sql;
$res_mw = mysqli_query($dbLink, $sql);
$cou_mw = mysqli_num_rows($res_mw);
if ($cou_mw > 0) {
    $row_mw = mysqli_fetch_assoc($res_mw);
    $row_mw['user_win'] = -$row_mw['user_win'];
    $data_history_mw = $row_mw;
    $data_total_mw = $data_history_mw;
}

// CQ9电子主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "cq9_history_report 
        WHERE {$sWhere_cq} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_cq = mysqli_query($dbLink, $sql);
$cou_cq = mysqli_num_rows($res_cq);
if ($cou_cq > 0) {
    $row_cq = mysqli_fetch_assoc($res_cq);
    $row_cq['user_win'] = -($row_cq['user_win']-$row_cq['total']);
    $data_history_cq = $row_cq;
    $data_total_cq = $data_history_cq;
}

// FG电子主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "fg_history_report 
        WHERE {$sWhere_fg} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_fg = mysqli_query($dbLink, $sql);
$cou_fg = mysqli_num_rows($res_fg);
if ($cou_fg > 0) {
    $row_fg = mysqli_fetch_assoc($res_fg);
    $row_fg['user_win'] = -($row_fg['user_win']-$row_fg['total']);
    $data_history_fg = $row_fg;
    $data_total_fg = $data_history_fg;
}

// BBIN真人视讯主数据
$sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "jx_bbin_history_report 
        WHERE {$sWhere_bbin} AND `count_date` BETWEEN '{$_REQUEST['date_start']}' AND '{$_REQUEST['date_end']}'";
$res_bbin = mysqli_query($dbLink, $sql);
$cou_bbin = mysqli_num_rows($res_bbin);
if ($cou_bbin > 0) {
    $row_bbin = mysqli_fetch_assoc($res_bbin);
    $row_bbin['user_win'] = -$row_bbin['user_win'];
    $data_history_bbin = $row_bbin;
    $data_total_bbin = $data_history_bbin;
}


// 主数据下注汇总
$data_total['count_pay'] = $data_history_hg['count_pay'] + $data_history_cp['count_pay'] + $data_history_ag['count_pay'] + $data_history_ag_dianzi['count_pay'] + $data_history_ag_dayu['count_pay'] + $data_history_ky['count_pay'] + $data_history_hgqp['count_pay'] +
    $data_history_vgqp['count_pay']+ $data_history_lyqp['count_pay']+ $data_history_mg['count_pay']+ $data_history_avia['count_pay']+ $data_history_fire['count_pay']+ $data_history_ssc['count_pay']+ $data_history_project['count_pay']+ $data_history_trace['count_pay'] +
    $data_history_og['count_pay'] + $data_history_mw['count_pay'] + $data_history_cq['count_pay'] + $data_history_fg['count_pay'] + $data_history_bbin['count_pay'] + $data_history_klqp['count_pay'];
$data_total['total'] = $data_history_hg['total'] + $data_history_cp['total'] + $data_history_ag['total'] + $data_history_ag_dianzi['total'] + $data_history_ag_dayu['total'] + $data_history_ky['total'] + $data_history_hgqp['total'] +
    $data_history_vgqp['total']+ $data_history_lyqp['total']+ $data_history_mg['total']+ $data_history_avia['total']+ $data_history_fire['total']+ $data_history_ssc['total']+ $data_history_project['total']+ $data_history_trace['total'] +
    $data_history_og['total'] + $data_history_mw['total'] + $data_history_cq['total'] + $data_history_fg['total']+ $data_history_bbin['total']+ $data_history_klqp['total'];
$data_total['user_win'] = $data_history_hg['user_win'] + $data_history_cp['user_win'] + $data_history_ag['user_win'] + $data_history_ag_dianzi['user_win'] + $data_history_ag_dayu['user_win'] + $data_history_ky['user_win'] + $data_history_hgqp['user_win'] +
    $data_history_vgqp['user_win']+ $data_history_lyqp['user_win']+ $data_history_mg['user_win']+ $data_history_avia['user_win']+ $data_history_fire['user_win']+ $data_history_ssc['user_win']+ $data_history_project['user_win']+ $data_history_trace['user_win'] +
    $data_history_og['user_win'] + $data_history_mw['user_win'] + $data_history_cq['user_win'] + $data_history_fg['user_win'] + $data_history_bbin['user_win']+ $data_history_klqp['user_win'];
$data_total['valid_money'] = $data_history_hg['valid_money'] + $data_history_cp['valid_money'] + $data_history_ag['valid_money'] + $data_history_ag_dianzi['valid_money'] + $data_history_ag_dayu['valid_money'] + $data_history_ky['valid_money'] + $data_history_hgqp['valid_money'] +
    $data_history_vgqp['valid_money'] + $data_history_lyqp['valid_money'] + $data_history_mg['valid_money']+ $data_history_avia['valid_money']+ $data_history_fire['valid_money']+ $data_history_ssc['valid_money']+ $data_history_project['valid_money']+ $data_history_trace['valid_money'] +
    $data_history_og['valid_money'] + $data_history_mw['valid_money'] + $data_history_cq['valid_money'] + $data_history_fg['valid_money']+ $data_history_bbin['valid_money']+ $data_history_klqp['valid_money'];

//得到第二部分实时的数据,并且合并到主数据里面（MW电子没有）
if($neededSearchCurrentBillTable) {
    $sWhere_today['sWhere_hg']=$sWhere_hg;
    $sWhere_today['sWhere_cp']=$sWhere_cp;
    $sWhere_today['sWhere_ag']=$sWhere_ag;
    $sWhere_today['sWhere_ky']=$sWhere_ky;
    $sWhere_today['sWhere_hgqp']=$sWhere_hgqp;
    $sWhere_today['sWhere_vgqp']=$sWhere_vgqp;
    $sWhere_today['sWhere_lyqp']=$sWhere_lyqp;
    $sWhere_today['sWhere_mg']=$sWhere_mg;
    $sWhere_today['sWhere_fire']=$sWhere_fire;
    $sWhere_today['sWhere_thirdcp']=$sWhere_thirdcp;
    $sWhere_today['sWhere_og']=$sWhere_og;
    $sWhere_today['sWhere_mw']=$sWhere_mw;
    $sWhere_today['sWhere_cq']=$sWhere_cq;
    $sWhere_today['sWhere_fg']=$sWhere_fg;
    $sWhere_today['sWhere_bbin']=$sWhere_bbin;
    $sWhere_today['sWhere_kl']=$sWhere_kl;
    $current_data = today_report_list($dbLink, $cpDbLink, $current_start_day, $current_end_day, $sWhere_today);

    // 合到体育主数据
    $data_total_hg['count_pay'] += $current_data['data_current_hg']['count_pay'];
    $data_total_hg['total'] += $current_data['data_current_hg']['total'];
    $data_total_hg['user_win'] += $current_data['data_current_hg']['user_win'];
    $data_total_hg['valid_money'] += $current_data['data_current_hg']['valid_money'];

    // 合到彩票主数据
    $data_total_cp['count_pay'] += $current_data['data_current_cp']['count_pay'];
    $data_total_cp['total'] += $current_data['data_current_cp']['total'];
    $data_total_cp['user_win'] += $current_data['data_current_cp']['user_win'];
    $data_total_cp['valid_money'] += $current_data['data_current_cp']['valid_money'];

    // 合到AG视讯主数据
    $data_total_ag['count_pay'] += $current_data['data_current_ag']['count_pay'];
    $data_total_ag['total'] += $current_data['data_current_ag']['total'];
    $data_total_ag['user_win'] += $current_data['data_current_ag']['user_win'];
    $data_total_ag['valid_money'] += $current_data['data_current_ag']['valid_money'];

    // 合到KY主数据
    $data_total_ky['count_pay'] += $current_data['data_current_ky']['count_pay'];
    $data_total_ky['total'] += $current_data['data_current_ky']['total'];
    $data_total_ky['user_win'] += $current_data['data_current_ky']['user_win'];
    $data_total_ky['valid_money'] += $current_data['data_current_ky']['valid_money'];

    // 合到AG电子主数据
    $data_total_ag_dianzi['count_pay'] += $current_data['data_current_ag_dianzi']['count_pay'];
    $data_total_ag_dianzi['total'] += $current_data['data_current_ag_dianzi']['total'];
    $data_total_ag_dianzi['user_win'] += $current_data['data_current_ag_dianzi']['user_win'];
    $data_total_ag_dianzi['valid_money'] += $current_data['data_current_ag_dianzi']['valid_money'];

    // 合到AG捕鱼王打鱼主数据
    $data_total_ag_dayu['count_pay'] += $current_data['data_current_ag_dayu']['count_pay'];
    $data_total_ag_dayu['total'] += $current_data['data_current_ag_dayu']['total'];
    $data_total_ag_dayu['user_win'] += $current_data['data_current_ag_dayu']['user_win'];
    $data_total_ag_dayu['valid_money'] += $current_data['data_current_ag_dayu']['valid_money'];

    // 合到HGQP主数据
    $data_total_hgqp['count_pay'] += $current_data['data_current_hgqp']['count_pay'];
    $data_total_hgqp['total'] += $current_data['data_current_hgqp']['total'];
    $data_total_hgqp['user_win'] += $current_data['data_current_hgqp']['user_win'];
    $data_total_hgqp['valid_money'] += $current_data['data_current_hgqp']['valid_money'];

    // 合到VGQP主数据
    $data_total_vgqp['count_pay'] += $current_data['data_current_vgqp']['count_pay'];
    $data_total_vgqp['total'] += $current_data['data_current_vgqp']['total'];
    $data_total_vgqp['user_win'] += $current_data['data_current_vgqp']['user_win'];
    $data_total_vgqp['valid_money'] += $current_data['data_current_vgqp']['valid_money'];

    // 合到乐游棋牌主数据
    $data_total_lyqp['count_pay'] += $current_data['data_current_lyqp']['count_pay'];
    $data_total_lyqp['total'] += $current_data['data_current_lyqp']['total'];
    $data_total_lyqp['user_win'] += $current_data['data_current_lyqp']['user_win'];
    $data_total_lyqp['valid_money'] += $current_data['data_current_lyqp']['valid_money'];

    // 合到MG电子主数据
    $data_total_mg['count_pay'] += $current_data['data_current_mg']['count_pay'];
    $data_total_mg['total'] += $current_data['data_current_mg']['total'];
    $data_total_mg['user_win'] += $current_data['data_current_mg']['user_win'];
    $data_total_mg['valid_money'] += $current_data['data_current_mg']['valid_money'];

    // 合到雷火电竞主数据
    $data_total_fire['count_pay'] += $current_data['data_current_fire']['count_pay'];
    $data_total_fire['total'] += $current_data['data_current_fire']['total'];
    $data_total_fire['user_win'] += $current_data['data_current_fire']['user_win'];
    $data_total_fire['valid_money'] += $current_data['data_current_fire']['valid_money'];

    // 合到第三方彩票信用主数据
    $data_total_ssc['count_pay'] += $current_data['data_current_ssc']['count_pay'];
    $data_total_ssc['total'] += $current_data['data_current_ssc']['total'];
    $data_total_ssc['user_win'] += $current_data['data_current_ssc']['user_win'];
    $data_total_ssc['valid_money'] += $current_data['data_current_ssc']['valid_money'];

    // 合到第三方彩票官方主数据
    $data_total_project['count_pay'] += $current_data['data_current_project']['count_pay'];
    $data_total_project['total'] += $current_data['data_current_project']['total'];
    $data_total_project['user_win'] += $current_data['data_current_project']['user_win'];
    $data_total_project['valid_money'] += $current_data['data_current_project']['valid_money'];

    // 合到第三方彩票官方追号
    $data_total_trace['count_pay'] += $current_data['data_current_trace']['count_pay'];
    $data_total_trace['total'] += $current_data['data_current_trace']['total'];
    $data_total_trace['user_win'] += $current_data['data_current_trace']['user_win'];
    $data_total_trace['valid_money'] += $current_data['data_current_trace']['valid_money'];

    // 合到OG视讯主数据
    $data_total_og['count_pay'] += $current_data['data_current_og']['count_pay'];
    $data_total_og['total'] += $current_data['data_current_og']['total'];
    $data_total_og['user_win'] += $current_data['data_current_og']['user_win'];
    $data_total_og['valid_money'] += $current_data['data_current_og']['valid_money'];

    // 合到MW电子主数据
    $data_total_mw['count_pay'] += $current_data['data_current_mw']['count_pay'];
    $data_total_mw['total'] += $current_data['data_current_mw']['total'];
    $data_total_mw['user_win'] += $current_data['data_current_mw']['user_win'];
    $data_total_mw['valid_money'] += $current_data['data_current_mw']['valid_money'];

    // 合到CQ电子主数据
    $data_total_cq['count_pay'] += $current_data['data_current_cq']['count_pay'];
    $data_total_cq['total'] += $current_data['data_current_cq']['total'];
    $data_total_cq['user_win'] += $current_data['data_current_cq']['user_win'];
    $data_total_cq['valid_money'] += $current_data['data_current_cq']['valid_money'];

    // 合到FG子主数据
    $data_total_fg['count_pay'] += $current_data['data_current_fg']['count_pay'];
    $data_total_fg['total'] += $current_data['data_current_fg']['total'];
    $data_total_fg['user_win'] += $current_data['data_current_fg']['user_win'];
    $data_total_fg['valid_money'] += $current_data['data_current_fg']['valid_money'];

    // 合到BBIN视讯子主数据
    $data_total_bbin['count_pay'] += $current_data['data_current_bbin']['count_pay'];
    $data_total_bbin['total'] += $current_data['data_current_bbin']['total'];
    $data_total_bbin['user_win'] += $current_data['data_current_bbin']['user_win'];
    $data_total_bbin['valid_money'] += $current_data['data_current_bbin']['valid_money'];

    // 合到快乐棋牌主数据
    $data_total_klqp['count_pay'] += $current_data['data_current_kl']['count_pay'];
    $data_total_klqp['total'] += $current_data['data_current_kl']['total'];
    $data_total_klqp['user_win'] += $current_data['data_current_kl']['user_win'];
    $data_total_klqp['valid_money'] += $current_data['data_current_kl']['valid_money'];

    // 主数据（历史+当天）
    $data_total['count_pay'] += $current_data['data_current_hg']['count_pay'] + $current_data['data_current_cp']['count_pay'] + $current_data['data_current_ag']['count_pay'] + $current_data['data_current_ag_dianzi']['count_pay'] +
        $current_data['data_current_ag_dayu']['count_pay'] + $current_data['data_current_ky']['count_pay'] + $current_data['data_current_hgqp']['count_pay'] + $current_data['data_current_vgqp']['count_pay'] +
        $current_data['data_current_lyqp']['count_pay'] + $current_data['data_current_mg']['count_pay'] + $current_data['data_current_fire']['count_pay'] + $current_data['data_current_ssc']['count_pay'] +
        $current_data['data_current_project']['count_pay'] + $current_data['data_current_trace']['count_pay'] + $current_data['data_current_og']['count_pay'] + $current_data['data_current_mw']['count_pay'] +
        $current_data['data_current_cq']['count_pay'] + $current_data['data_current_fg']['count_pay'] + $current_data['data_current_bbin']['count_pay']+ $current_data['data_current_kl']['count_pay'];
    $data_total['total'] += $current_data['data_current_hg']['total'] + $current_data['data_current_cp']['total'] + $current_data['data_current_ag']['total'] + $current_data['data_current_ag_dianzi']['total'] +
        $current_data['data_current_ag_dayu']['total'] + $current_data['data_current_ky']['total'] + $current_data['data_current_hgqp']['total'] +
        $current_data['data_current_vgqp']['total'] + $current_data['data_current_lyqp']['total'] + $current_data['data_current_mg']['total'] + $current_data['data_current_fire']['total'] + $current_data['data_current_ssc']['total'] +
        $current_data['data_current_project']['total'] + $current_data['data_current_trace']['total'] + $current_data['data_current_og']['total'] + $current_data['data_current_mw']['total'] +
        $current_data['data_current_cq']['total'] + $current_data['data_current_fg']['total'] + $current_data['data_current_bbin']['total']+ $current_data['data_current_kl']['total'];
    $data_total['user_win'] += $current_data['data_current_hg']['user_win'] + $current_data['data_current_cp']['user_win'] + $current_data['data_current_ag']['user_win'] + $current_data['data_current_ag_dianzi']['user_win'] +
        $current_data['data_current_ag_dayu']['user_win'] + $current_data['data_current_ky']['user_win'] + $current_data['data_current_hgqp']['user_win'] + $current_data['data_current_vgqp']['user_win'] +
        $current_data['data_current_lyqp']['user_win'] + $current_data['data_current_mg']['user_win'] + $current_data['data_current_fire']['user_win']+ $current_data['data_current_ssc']['user_win'] +
        $current_data['data_current_project']['user_win'] + $current_data['data_current_trace']['user_win'] + $current_data['data_current_og']['user_win'] + $current_data['data_current_mw']['user_win'] +
        $current_data['data_current_cq']['user_win'] + $current_data['data_current_fg']['user_win'] + $current_data['data_current_bbin']['user_win']+ $current_data['data_current_kl']['user_win'];
    $data_total['valid_money'] += $current_data['data_current_hg']['valid_money'] + $current_data['data_current_cp']['valid_money'] + $current_data['data_current_ag']['valid_money'] + $current_data['data_current_ag_dianzi']['valid_money'] +
        $current_data['data_current_ag_dayu']['valid_money'] + $current_data['data_current_ky']['valid_money'] + $current_data['data_current_hgqp']['valid_money'] + $current_data['data_current_vgqp']['valid_money'] +
        $current_data['data_current_lyqp']['valid_money'] + $current_data['data_current_mg']['valid_money'] + $current_data['data_current_fire']['valid_money'] + $current_data['data_current_ssc']['valid_money'] +
        $current_data['data_current_project']['valid_money'] + $current_data['data_current_trace']['valid_money'] + $current_data['data_current_og']['valid_money'] + $current_data['data_current_mw']['valid_money'] +
        $current_data['data_current_cq']['valid_money'] + $current_data['data_current_fg']['valid_money']+ $current_data['data_current_bbin']['valid_money']+ $current_data['data_current_kl']['valid_money'];

}

if ($action=='agent_report_top'){
    if ($web == DBPREFIX.'web_agents_data'){
        $data_total['name']= "$Agent";
    }else{
        $data_total['name']= "$Corprator";
    }
    exit(json_encode($data_total, JSON_UNESCAPED_UNICODE));
}

// 手机版代理后台
if($action =='api'){
    $status = '200';
    $describe = '获取数据成功!';
    $resdata['allBetNum'] = $data_total['count_pay']; // 总笔数
    $resdata['allBetTotal'] = sprintf('%.2f',$data_total['total']); // 总下注
    $resdata['allBetValid'] = sprintf('%.2f',$data_total['valid_money']); // 总实际投注
    $resdata['allBetWin'] = sprintf("%.2f",$data_total['user_win']); // 总盈利
    if($indextype=='index'){
        original_phone_request_response($status,$describe,$resdata);
    }
    // AG视讯
    $resdata['ag_allBetNum'] = $data_total_ag['count_pay']; // 总笔数
    $resdata['ag_allBetTotal'] = sprintf('%.2f',$data_total_ag['total']); // 总下注
    $resdata['ag_allBetValid'] = sprintf('%.2f',$data_total_ag['valid_money']); // 总实际投注
    $resdata['ag_allBetWin'] = sprintf("%.2f",$data_total_ag['user_win']); // 总盈利
    // AG电子
    $resdata['agGame_allBetNum'] = $data_total_ag_dianzi['count_pay']; // 总笔数
    $resdata['agGame_allBetTotal'] = sprintf('%.2f',$data_total_ag_dianzi['total']); // 总下注
    $resdata['agGame_allBetValid'] = sprintf('%.2f',$data_total_ag_dianzi['valid_money']); // 总实际投注
    $resdata['agGame_allBetWin'] = sprintf("%.2f",$data_total_ag_dianzi['user_win']); // 总盈利
    // AG捕鱼打鱼
    $resdata['agBy_allBetNum'] = $data_total_ag_dayu['count_pay']; // 总笔数
    $resdata['agBy_allBetTotal'] = sprintf('%.2f',$data_total_ag_dayu['total']); // 总下注
    $resdata['agBy_allBetValid'] = sprintf('%.2f',$data_total_ag_dayu['valid_money']); // 总实际投注
    $resdata['agBy_allBetWin'] = sprintf("%.2f",$data_total_ag_dayu['user_win']); // 总盈利
    // AG捕鱼养鱼
    $resdata['agYy_allBetNum'] = $data_total_ag_yangyu['count_pay']?$data_total_ag_yangyu['count_pay']:0; // 总笔数
    $resdata['agYy_allBetTotal'] = sprintf('%.2f',$data_total_ag_yangyu['total']); // 总下注
    $resdata['agYy_allBetValid'] = sprintf('%.2f',$data_total_ag_yangyu['valid_money']); // 总实际投注
    $resdata['agYy_allBetWin'] = sprintf("%.2f",$data_total_ag_yangyu['user_win']); // 总盈利
    // 皇冠体育
    $resdata['sport_allBetNum'] = $data_total_hg['count_pay']; // 总笔数
    $resdata['sport_allBetTotal'] = sprintf('%.2f',$data_total_hg['total']); // 总下注
    $resdata['sport_allBetValid'] = sprintf('%.2f',$data_total_hg['valid_money']); // 总实际投注
    $resdata['sport_allBetWin'] = sprintf("%.2f",$data_total_hg['user_win']); // 总盈利
    // 体育彩票
    $resdata['lottery_allBetNum'] = $data_total_cp['count_pay']; // 总笔数
    $resdata['lottery_allBetTotal'] = sprintf('%.2f',$data_total_cp['total']); // 总下注
    $resdata['lottery_allBetValid'] = sprintf('%.2f',$data_total_cp['valid_money']); // 总实际投注
    $resdata['lottery_allBetWin'] = sprintf("%.2f",$data_total_cp['user_win']); // 总盈利
    // 开元棋牌
    $resdata['kyChess_allBetNum'] = $data_total_ky['count_pay']; // 总笔数
    $resdata['kyChess_allBetTotal'] = sprintf('%.2f',$data_total_ky['total']); // 总下注
    $resdata['kyChess_allBetValid'] = sprintf('%.2f',$data_total_ky['valid_money']); // 总实际投注
    $resdata['kyChess_allBetWin'] = sprintf("%.2f",$data_total_ky['user_win']); // 总盈利
    // 乐游棋牌
    $resdata['lyChess_allBetNum'] = $data_total_lyqp['count_pay']; // 总笔数
    $resdata['lyChess_allBetTotal'] = sprintf('%.2f',$data_total_lyqp['total']); // 总下注
    $resdata['lyChess_allBetValid'] = sprintf('%.2f',$data_total_lyqp['valid_money']); // 总实际投注
    $resdata['lyChess_allBetWin'] = sprintf("%.2f",$data_total_lyqp['user_win']); // 总盈利
    // VG棋牌
    $resdata['vgChess_allBetNum'] = $data_total_vgqp['count_pay']; // 总笔数
    $resdata['vgChess_allBetTotal'] = sprintf('%.2f',$data_total_vgqp['total']); // 总下注
    $resdata['vgChess_allBetValid'] = sprintf('%.2f',$data_total_vgqp['valid_money']); // 总实际投注
    $resdata['vgChess_allBetWin'] = sprintf("%.2f",$data_total_vgqp['user_win']); // 总盈利
    // 皇冠棋牌
    $resdata['hgChess_allBetNum'] = $data_total_hgqp['count_pay']; // 总笔数
    $resdata['hgChess_allBetTotal'] = sprintf('%.2f',$data_total_hgqp['total']); // 总下注
    $resdata['hgChess_allBetValid'] = sprintf('%.2f',$data_total_hgqp['valid_money']); // 总实际投注
    $resdata['hgChess_allBetWin'] = sprintf("%.2f",$data_total_hgqp['user_win']); // 总盈利
    // 快乐棋牌
    $resdata['klChess_allBetNum'] = $data_total_klqp['count_pay']; // 总笔数
    $resdata['klChess_allBetTotal'] = sprintf('%.2f',$data_total_klqp['total']); // 总下注
    $resdata['klChess_allBetValid'] = sprintf('%.2f',$data_total_klqp['valid_money']); // 总实际投注
    $resdata['klChess_allBetWin'] = sprintf("%.2f",$data_total_klqp['user_win']); // 总盈利
    // 泛亚电竞
    $resdata['avia_allBetNum'] = $data_total_avia['count_pay']; // 总笔数
    $resdata['avia_allBetTotal'] = sprintf('%.2f',$data_total_avia['total']); // 总下注
    $resdata['avia_allBetValid'] = sprintf('%.2f',$data_total_avia['valid_money']); // 总实际投注
    $resdata['avia_allBetWin'] = sprintf("%.2f",$data_total_avia['user_win']); // 总盈利
    // 雷火电竞
    $resdata['fire_allBetNum'] = $data_total_fire['count_pay']; // 总笔数
    $resdata['fire_allBetTotal'] = sprintf('%.2f',$data_total_fire['total']); // 总下注
    $resdata['fire_allBetValid'] = sprintf('%.2f',$data_total_fire['valid_money']); // 总实际投注
    $resdata['fire_allBetWin'] = sprintf("%.2f",$data_total_fire['user_win']); // 总盈利
    // OG视讯
    $resdata['og_allBetNum'] = $data_total_og['count_pay']; // 总笔数
    $resdata['og_allBetTotal'] = sprintf('%.2f',$data_total_og['total']); // 总下注
    $resdata['og_allBetValid'] = sprintf('%.2f',$data_total_og['valid_money']); // 总实际投注
    $resdata['og_allBetWin'] = sprintf("%.2f",$data_total_og['user_win']); // 总盈利
    // BBIN视讯
    $resdata['bbin_allBetNum'] = $data_total_bbin['count_pay']; // 总笔数
    $resdata['bbin_allBetTotal'] = sprintf('%.2f',$data_total_bbin['total']); // 总下注
    $resdata['bbin_allBetValid'] = sprintf('%.2f',$data_total_bbin['valid_money']); // 总实际投注
    $resdata['bbin_allBetWin'] = sprintf("%.2f",$data_total_bbin['user_win']); // 总盈利
    // MG电子
    $resdata['mg_allBetNum'] = $data_total_mg['count_pay']; // 总笔数
    $resdata['mg_allBetTotal'] = sprintf('%.2f',$data_total_mg['total']); // 总下注
    $resdata['mg_allBetValid'] = sprintf('%.2f',$data_total_mg['valid_money']); // 总实际投注
    $resdata['mg_allBetWin'] = sprintf("%.2f",$data_total_mg['user_win']); // 总盈利
    // MW电子
    $resdata['mw_allBetNum'] = $data_total_mw['count_pay']; // 总笔数
    $resdata['mw_allBetTotal'] = sprintf('%.2f',$data_total_mw['total']); // 总下注
    $resdata['mw_allBetValid'] = sprintf('%.2f',$data_total_mw['valid_money']); // 总实际投注
    $resdata['mw_allBetWin'] = sprintf("%.2f",$data_total_mw['user_win']); // 总盈利
    // CQ9电子
    $resdata['cq_allBetNum'] = $data_total_cq['count_pay']; // 总笔数
    $resdata['cq_allBetTotal'] = sprintf('%.2f',$data_total_cq['total']); // 总下注
    $resdata['cq_allBetValid'] = sprintf('%.2f',$data_total_cq['valid_money']); // 总实际投注
    $resdata['cq_allBetWin'] = sprintf("%.2f",$data_total_cq['user_win']); // 总盈利
    // FG电子
    $resdata['fg_allBetNum'] = $data_total_fg['count_pay']; // 总笔数
    $resdata['fg_allBetTotal'] = sprintf('%.2f',$data_total_fg['total']); // 总下注
    $resdata['fg_allBetValid'] = sprintf('%.2f',$data_total_fg['valid_money']); // 总实际投注
    $resdata['fg_allBetWin'] = sprintf("%.2f",$data_total_fg['user_win']); // 总盈利
    // 彩票信用盘
    $resdata['cpxy_allBetNum'] = $data_total_ssc['count_pay']; // 总笔数
    $resdata['cpxy_allBetTotal'] = sprintf('%.2f',$data_total_ssc['total']); // 总下注
    $resdata['cpxy_allBetValid'] = sprintf('%.2f',$data_total_ssc['valid_money']); // 总实际投注
    $resdata['cpxy_allBetWin'] = sprintf("%.2f",$data_total_ssc['user_win']); // 总盈利
    // 彩票官方盘
    $resdata['cpgf_allBetNum'] = $data_total_project['count_pay']; // 总笔数
    $resdata['cpgf_allBetTotal'] = sprintf('%.2f',$data_total_project['total']); // 总下注
    $resdata['cpgf_allBetValid'] = sprintf('%.2f',$data_total_project['valid_money']); // 总实际投注
    $resdata['cpgf_allBetWin'] = sprintf("%.2f",$data_total_project['user_win']); // 总盈利
    // 彩票官方追号
    $resdata['cpgfzh_allBetNum'] = $data_total_trace['count_pay']; // 总笔数
    $resdata['cpgfzh_allBetTotal'] = sprintf('%.2f',$data_total_trace['total']); // 总下注
    $resdata['cpgfzh_allBetValid'] = sprintf('%.2f',$data_total_trace['valid_money']); // 总实际投注
    $resdata['cpgfzh_allBetWin'] = sprintf("%.2f",$data_total_trace['user_win']); // 总盈利
    original_phone_request_response($status,$describe,$resdata);
}


//计算当日的报表数据
function today_report_list($dbLink, $cpDbLink, $current_start_day, $current_end_day, $sWhere_today){

    $sWhere_hg = $sWhere_today['sWhere_hg'];
    $sWhere_cp = $sWhere_today['sWhere_cp'];
    $sWhere_ag = $sWhere_today['sWhere_ag'];
    $sWhere_ky = $sWhere_today['sWhere_ky'];
    $sWhere_hgqp = $sWhere_today['sWhere_hgqp'];
    $sWhere_vgqp = $sWhere_today['sWhere_vgqp'];
    $sWhere_lyqp = $sWhere_today['sWhere_lyqp'];
    $sWhere_mg = $sWhere_today['sWhere_mg'];
    $sWhere_fire = $sWhere_today['sWhere_fire'];
    $sWhere_thirdcp = $sWhere_today['sWhere_thirdcp'];
    $sWhere_og = $sWhere_today['sWhere_og'];
    $sWhere_mw = $sWhere_today['sWhere_mw'];
    $sWhere_cq = $sWhere_today['sWhere_cq'];
    $sWhere_fg = $sWhere_today['sWhere_fg'];
    $sWhere_bbin = $sWhere_today['sWhere_bbin'];
    $sWhere_kl = $sWhere_today['sWhere_kl'];

    // -----------------------------------------------体育当天Start
    // 下注金额
//    $res_hg = mysqli_query($dbLink, "select count(1) as count_pay, sum(BetScore) as total, sum(M_Result) as user_win from ".DBPREFIX."web_report_data WHERE $sWhere_hg and BetTime BETWEEN '".$current_start_day."' and '".$current_end_day."'");
    $res_hg = mysqli_query($dbLink, "select count(1) as count_pay, sum(BetScore) as total, sum(M_Result) as user_win from ".DBPREFIX."web_report_data WHERE $sWhere_hg and M_Date = '".$current_start_day."' and testflag=0 and `Cancel`=0");
    $cou_hg = mysqli_num_rows($res_hg);
    if ($cou_hg>0){
        $row_hg = mysqli_fetch_assoc($res_hg);
        $row_hg['user_win'] = $row_hg['user_win'] - $row_hg['user_win']*2;
        $data_current_hg = $row_hg;
    }

    // 实际投注金额（有效投注）
//    $res_hg_valid_money =  mysqli_query($dbLink, "select sum(BetScore) as valid_money from ".DBPREFIX."web_report_data WHERE $sWhere_hg and BetTime BETWEEN '".$current_start_day."' and '".$current_end_day."' and M_Result!=0 and Checked = 1");
    $res_hg_valid_money =  mysqli_query($dbLink, "select sum(VGOLD) as valid_money from ".DBPREFIX."web_report_data WHERE $sWhere_hg and M_Date = '".$current_start_day."' and checked = 1 and testflag=0 and `Cancel`=0 ");
    $cou_hg_valid_money = mysqli_num_rows($res_hg_valid_money);
    if($cou_hg_valid_money>0){
        $row_hg_valid_money = mysqli_fetch_assoc($res_hg_valid_money);
        $data_current_hg['valid_money'] = $row_hg_valid_money['valid_money'];
    }
    // ----------------------------------------------体育当天End

    // 彩票当天（使用美东时间）
    $current_start_day_cp = strtotime($current_start_day);
    $current_end_day_cp = strtotime($current_end_day);
    $res_cp = mysqli_query($cpDbLink, "select count(1) as count_pay, sum(drop_money) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from gxfcy_bill where $sWhere_cp and bet_time BETWEEN '".$current_start_day_cp."' and '".$current_end_day_cp."' ");
    $cou_cp = mysqli_num_rows($res_cp);
    if ($cou_cp>0) {
        $row_cp = mysqli_fetch_assoc($res_cp);
        $row_cp['user_win'] = $row_cp['user_win'] - $row_cp['user_win']*2;
        $data_cuttent_cp = $row_cp;
    }

    // AG视讯当天
    $res_ag = mysqli_query($dbLink, "select count(1) as count_pay, sum(amount) as total, sum(valid_money) as valid_money, sum(profit) as user_win from ".DBPREFIX."ag_projects where $sWhere_ag and bettime BETWEEN '".$current_start_day."' and '".$current_end_day."' and `type`='BR'");
    $cou_ag = mysqli_num_rows($res_ag);
    if ($cou_ag>0) {
        $row_ag = mysqli_fetch_assoc($res_ag);
        $row_ag['user_win'] = $row_ag['user_win'] - $row_ag['user_win']*2;
        $data_current_ag = $row_ag;
    }


    // KY主数据
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`cellscore`) AS `valid_money`, SUM(`allbet`) AS `total`, SUM(`profit`) AS `user_win`, SUM(`revenue`) AS `total_revenue`
            FROM " . DBPREFIX . "ky_projects 
            WHERE {$sWhere_ky} AND `game_endtime` BETWEEN '{$current_start_day}' AND '{$current_end_day}'";
    $res_ky = mysqli_query($dbLink, $sql);
    $cou_ky = mysqli_num_rows($res_ky);
    if ($cou_ky > 0) {
        $row_ky = mysqli_fetch_assoc($res_ky);
        $row_ky['user_win'] = -$row_ky['user_win'];
        $data_current_ky = $row_ky;
    }

    // AG电子当天
    $res_ag_dianzi = mysqli_query($dbLink, "select count(1) as count_pay, sum(amount) as total, sum(valid_money) as valid_money, sum(profit) as user_win from ".DBPREFIX."ag_projects where $sWhere_ag and bettime BETWEEN '".$current_start_day."' and '".$current_end_day."' and (`type`='' or `type`='SLOT')");
    $cou_ag_dianzi = mysqli_num_rows($res_ag_dianzi);
    if ($cou_ag_dianzi>0) {
        $row_ag_dianzi = mysqli_fetch_assoc($res_ag_dianzi);
        $row_ag_dianzi['user_win'] = $row_ag_dianzi['user_win'] - $row_ag_dianzi['user_win']*2;
        $data_current_ag_dianzi = $row_ag_dianzi;
    }

    // AG捕鱼王打鱼当天
    $res_ag_dayu = mysqli_query($dbLink, "select sum(BulletOutNum) as count_pay, sum(Cost) as valid_money, sum(Earn) as shouru from ".DBPREFIX."ag_buyu_scene where $sWhere_ag AND EndTime BETWEEN '".$current_start_day."' and '".$current_end_day."' ");
    $cou_ag_dayu = mysqli_num_rows($res_ag_dayu);
    if ($cou_ag_dayu>0) {
        $row_ag_dayu = mysqli_fetch_assoc($res_ag_dayu);
        //$row_ag_dayu['user_win'] = $row_ag_dayu['shouru'] - $row_ag_dayu['valid_money'];
        $row_ag_dayu['user_win'] =  $row_ag_dayu['valid_money'] - $row_ag_dayu['shouru'];
        $data_current_ag_dayu = $row_ag_dayu;
    }

    // HGQP数据
//    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`valid_bet`) AS `valid_money`, SUM(`bet`) AS `total`, SUM(`wincoins`) AS `user_win`, SUM(`board_fee`) AS `total_revenue`
//            FROM " . DBPREFIX . "ff_projects
//            WHERE {$sWhere_hgqp} AND `game_endtime` BETWEEN '{$current_start_day}' AND '{$current_end_day}'";
//    $res_hgqp = mysqli_query($dbLink, $sql);
//    $cou_hgqp = mysqli_num_rows($res_hgqp);
//    if ($cou_hgqp > 0) {
//        $row_hgqp = mysqli_fetch_assoc($res_hgqp);
//        $row_hgqp['user_win'] = -$row_hgqp['user_win'];
//        $data_current_hgqp = $row_hgqp;
//    }

    // VGQP数据
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`validbetamount`) AS `valid_money`, SUM(`betamount`) AS `total`, SUM(`money`) AS `user_win`, SUM(`serviceMoney`) AS `total_revenue`
            FROM " . DBPREFIX . "vg_projects 
            WHERE {$sWhere_vgqp} AND `game_endtime` BETWEEN '{$current_start_day}' AND '{$current_end_day}'";
    $res_vgqp = mysqli_query($dbLink, $sql);
    $cou_vgqp = mysqli_num_rows($res_vgqp);
    if ($cou_vgqp > 0) {
        $row_vgqp = mysqli_fetch_assoc($res_vgqp);
        $row_vgqp['user_win'] = -$row_vgqp['user_win'];
        $data_current_vgqp = $row_vgqp;
    }

    // 乐游棋牌数据
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`cellscore`) AS `valid_money`, SUM(`allbet`) AS `total`, SUM(`profit`) AS `user_win`, SUM(`revenue`) AS `total_revenue`
            FROM " . DBPREFIX . "ly_projects 
            WHERE {$sWhere_lyqp} AND `game_endtime` BETWEEN '{$current_start_day}' AND '{$current_end_day}'";
    $res_lyqp = mysqli_query($dbLink, $sql);
    $cou_lyqp = mysqli_num_rows($res_lyqp);
    if ($cou_lyqp > 0) {
        $row_lyqp = mysqli_fetch_assoc($res_lyqp);
        $row_lyqp['user_win'] = -$row_lyqp['user_win'];
        $data_current_lyqp = $row_lyqp;
    }

    // MG电子当天数据
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`
            FROM " . DBPREFIX . "mg_projects 
            WHERE {$sWhere_mg} AND `transaction_time` BETWEEN '{$current_start_day}' AND '{$current_end_day}' AND category='WAGER'";
    $res_mg = mysqli_query($dbLink, $sql);
    $cou_mg = mysqli_num_rows($res_mg);
    if ($cou_mg > 0) {
        $row_mg = mysqli_fetch_assoc($res_mg);

        // 总盈利额
        $sql = "SELECT `userid`, `username`,SUM(`amount`) AS `total_payout`
            FROM " . DBPREFIX . "mg_projects 
            WHERE {$sWhere_mg} AND `transaction_time` >= '{$current_start_day}' AND `transaction_time` < '{$current_end_day}' AND category='PAYOUT'";
        $res_mg = mysqli_query($dbLink, $sql);
        $cou_mg = mysqli_num_rows($res_mg);
        if($cou_mg > 0){
            $row_mg_total_payout = mysqli_fetch_assoc($res_mg);
            $row_mg['user_win'] = ($row_mg['total'] - $row_mg_total_payout['total_payout']);
        }

        $data_current_mg = $row_mg;
    }

    // 雷火电竞当天数据（使用北京时间）
   $sql = "SELECT SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`, SUM(`reward`) AS `user_win`
            FROM " . DBPREFIX . "fire_projects 
            WHERE {$sWhere_fire} AND `settlement_datetime` BETWEEN '{$current_start_day}' AND '{$current_end_day}'";
    $res_fire = mysqli_query($dbLink, $sql);
    $cou_fire = mysqli_num_rows($res_fire);
    if ($cou_fire > 0) {
        $row_fire = mysqli_fetch_assoc($res_fire);
        $row_fire['user_win'] = -($row_fire['user_win']);
        $data_current_fire = $row_fire;
    }

    // 第三方彩票信用数据当天
    // status 0: 正常；1：已撤销；2：未中奖；3：已中奖；4：和局；5：系统撤销
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`money`) AS `valid_money`, SUM(`money`) AS `total`, SUM(`bonus`) AS `user_win`, SUM(`rebateMoney`) AS `total_revenue`
            FROM " . DBPREFIX . "web_third_ssc_data 
            WHERE {$sWhere_thirdcp} AND `counted_at` BETWEEN '{$current_start_day}' AND '{$current_end_day}' AND `status` NOT IN (5)";
    $res_cpssc = mysqli_query($dbLink, $sql);
    $cou_cpssc = mysqli_num_rows($res_cpssc);
    if ($cou_cpssc > 0) {
        $row_cpssc = mysqli_fetch_assoc($res_cpssc);
        $row_cpssc['user_win'] = ($row_cpssc['total']-$row_cpssc['user_win']);
        $data_current_cpssc = $row_cpssc;
    }

    // 第三方彩票官方数据当天
    // status 0: 正常；1：已撤销；2：未中奖；3：已中奖；4：已派奖；5：系统撤销
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`, SUM(`prize`) AS `user_win`, SUM(`status_prize`) AS `total_revenue`
            FROM " . DBPREFIX . "web_third_projects_data 
            WHERE {$sWhere_thirdcp} AND `counted_at` BETWEEN '{$current_start_day}' AND '{$current_end_day}' AND `status` NOT IN (1, 5)";
    $res_cpproject = mysqli_query($dbLink, $sql);
    $cou_cpproject = mysqli_num_rows($res_cpproject);
    if ($cou_cpproject > 0) {
        $row_cpproject = mysqli_fetch_assoc($res_cpproject);
        $row_cpproject['user_win'] = ($row_cpproject['total']-$row_cpproject['user_win']);
        $data_current_cpproject = $row_cpproject;
    }

    // 第三方彩票官方追号数据当天
    // status 0: 进行中；1：已完成；2：会员终止；3：管理员终止；4：系统终止
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`finished_amount`) AS `valid_money`, SUM(`finished_amount`) AS `total`, SUM(`prize`) AS `user_win`, SUM(`position`) AS `total_revenue`
            FROM " . DBPREFIX . "web_third_traces_data 
            WHERE {$sWhere_thirdcp} AND `bought_at` BETWEEN '{$current_start_day}' AND '{$current_end_day}' AND `status` NOT IN (2,3,4,5)";
    $res_cptrace = mysqli_query($dbLink, $sql);
    $cou_cptrace = mysqli_num_rows($res_cptrace);
    if ($cou_cptrace > 0) {
        $row_cptrace = mysqli_fetch_assoc($res_cptrace);
        $row_cptrace['user_win'] = ($row_cptrace['total']-$row_cptrace['user_win']);
        $data_current_cptrace = $row_cptrace;
    }

    // OG视讯当天数据（使用北京时间）
    $current_start_day_og = date('Y-m-d H:i:s',strtotime($current_start_day)+12*60*60);
    $current_end_day_og = date('Y-m-d H:i:s',strtotime($current_end_day)+12*60*60);
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`validbet`) AS `valid_money`, SUM(`bettingamount`) AS `total`, SUM(`winloseamount`) AS `user_win`
            FROM " . DBPREFIX . "og_projects 
            WHERE {$sWhere_og} AND `bettingdate` BETWEEN '{$current_start_day_og}' AND '{$current_end_day_og}'";
    $res_og = mysqli_query($dbLink, $sql);
    $cou_og = mysqli_num_rows($res_og);
    if ($cou_og > 0) {
        $row_og = mysqli_fetch_assoc($res_og);
        $row_og['user_win'] = -$row_og['user_win'];
        $data_current_og = $row_og;
    }

    // MW电子当天数据（使用北京时间）
    $current_start_day_mw = date('Y-m-d H:i:s',strtotime($current_start_day)+12*60*60);
    $current_end_day_mw = date('Y-m-d H:i:s',strtotime($current_end_day)+12*60*60);
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`playMoney`) AS `valid_money`, SUM(`playMoney`) AS `total`, SUM(`winMoney`) AS `user_win`
            FROM " . DBPREFIX . "mw_projects 
            WHERE {$sWhere_mw} AND `logDate` BETWEEN '{$current_start_day_mw}' AND '{$current_end_day_mw}'";
//    echo $sql;
    $res_mw = mysqli_query($dbLink, $sql);
    $cou_mw = mysqli_num_rows($res_mw);
    if ($cou_mw > 0) {
        $row_mw = mysqli_fetch_assoc($res_mw);
        $row_mw['user_win'] = -($row_mw['user_win']-$row_mw['total']);
        $data_current_mw = $row_mw;
    }

    // CQ9电子当天数据
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`bet`) AS `valid_money`, SUM(`bet`) AS `total`, SUM(`win`) AS `user_win`
            FROM " . DBPREFIX . "cq9_projects 
            WHERE {$sWhere_cq} AND `endroundtime` BETWEEN '{$current_start_day}' AND '{$current_end_day}'";
    $res_cq = mysqli_query($dbLink, $sql);
    $cou_cq = mysqli_num_rows($res_cq);
    if ($cou_cq > 0) {
        $row_cq = mysqli_fetch_assoc($res_cq);
        $row_cq['user_win'] = -($row_cq['user_win']-$row_cq['total']);
        $data_current_cq = $row_cq;
    }

    // FG电子当天数据（使用美东时间）
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`all_bets`) AS `valid_money`, SUM(`all_bets`) AS `total`, SUM(`all_wins`) AS `user_win`
            FROM " . DBPREFIX . "fg_projects 
            WHERE {$sWhere_fg} AND `endtime` BETWEEN '{$current_start_day}' AND '{$current_end_day}'";
    $res_fg = mysqli_query($dbLink, $sql);
    $cou_fg = mysqli_num_rows($res_fg);
    if ($cou_fg > 0) {
        $row_fg = mysqli_fetch_assoc($res_fg);
        $row_fg['user_win'] = $row_fg['total']-$row_fg['user_win'];
        $data_current_fg = $row_fg;
    }

    // BBIN真人视讯当天数据（使用北京时间）
    //$current_start_day_bbin = date('Y-m-d H:i:s',strtotime($current_start_day)+12*60*60);
    //$current_end_day_bbin = date('Y-m-d H:i:s',strtotime($current_end_day)+12*60*60);
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`BetAmount`) AS `valid_money`, SUM(`BetAmount`) AS `total`, SUM(`Payoff`) AS `user_win`
            FROM " . DBPREFIX . "jx_bbin_projects 
            WHERE {$sWhere_bbin} AND `WagersDate` BETWEEN '{$current_start_day}' AND '{$current_end_day}'";
    $res_bbin = mysqli_query($dbLink, $sql);
    $cou_bbin = mysqli_num_rows($res_bbin);
    if ($cou_bbin > 0) {
        $row_bbin = mysqli_fetch_assoc($res_bbin);
        $row_bbin['user_win'] =  -$row_bbin['user_win'];
        $data_current_bbin = $row_bbin;
    }

    // 快乐棋牌当天数据（使用北京时间）
    $sql = "SELECT SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`, SUM(`prize`) AS `user_win`
            FROM " . DBPREFIX . "kl_projects 
            WHERE {$sWhere_kl} AND `gametime` BETWEEN '{$current_start_day}' AND '{$current_end_day}'";
    $res_kl = mysqli_query($dbLink, $sql);
    $cou_kl = mysqli_num_rows($res_kl);
    if ($cou_kl > 0) {
        $row_kl = mysqli_fetch_assoc($res_kl);
        $row_kl['user_win'] = -($row_kl['user_win']-$row_kl['total']);
        $data_current_kl = $row_kl;
    }


    $data['data_current_hg'] = $data_current_hg;
    $data['data_current_cp'] = $data_cuttent_cp;
    $data['data_current_ag'] = $data_current_ag;
    $data['data_current_ky'] = $data_current_ky;
    $data['data_current_ag_dianzi'] = $data_current_ag_dianzi;
    $data['data_current_ag_dayu'] = $data_current_ag_dayu;
    $data['data_current_hgqp'] = $data_current_hgqp;
    $data['data_current_vgqp'] = $data_current_vgqp;
    $data['data_current_lyqp'] = $data_current_lyqp; // 乐游棋牌
    $data['data_current_mg'] = $data_current_mg; // MG电子
    $data['data_current_fire'] = $data_current_fire; // 雷火电竞
    $data['data_current_ssc'] = $data_current_cpssc; // 第三方彩票信用
    $data['data_current_project'] = $data_current_cpproject; // 第三方彩票官方
    $data['data_current_trace'] = $data_current_cptrace; // 第三方彩票官方追号
    $data['data_current_og'] = $data_current_og; // OG视讯
    $data['data_current_mw'] = $data_current_mw; // MW电子
    $data['data_current_cq'] = $data_current_cq; // CQ电子
    $data['data_current_fg'] = $data_current_fg; // FG电子
    $data['data_current_bbin'] = $data_current_bbin; // BBIN视讯
    $data['data_current_kl'] = $data_current_kl; // 快乐棋牌
    return $data;
}

// 首页统计，返回统计总值
if($isHome == 1){
    $betTotal = [
        'valid_money' => sprintf('%.2f', $data_total['valid_money']),
        'user_win' => sprintf('%.2f', $data_total['user_win'])
    ];
    echo json_encode($betTotal);
    exit();
}
?>
    <html>
    <head>
        <title>reports_top</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
        <style type="text/css">
            a{ color: #00f;}
            .main-ui{ width: 1000px; }
            .td1{width: 80px;}
        </style>
    </head>
    <body>
    <dl class="main-nav">
        <dt>报表详细</dt>
        <dd>
            <div class="header_info">
                <?php
                $date_end = isset($current_end_day)?$current_end_day:$_REQUEST['date_end'];
                echo $web == DBPREFIX.'web_agents_data' ? "代理名称：$Agent" : "股东：$Corprator -- 日期：".$_REQUEST['date_start'].' ~ '.$date_end."-- 报表分类：总账 -- 投注方式：全部 --下注管道：网络下注 --";
                ?>
                <a href="javascript:history.go(-1);" >回上一页</a>
            </div>
        </dd>
    </dl>
    <div class="main-ui">
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">所有下注</td>
                <td class="td2"><?php echo $web == DBPREFIX.'web_agents_data' ? '代理' : '股东' ?></td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td><?php echo $web==DBPREFIX.'web_agents_data'? $Agent : $Corprator;?></td>
                <td><?php echo $data_total['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total['valid_money']);?></td>
                <td><?php echo $data_total['user_win']>0?sprintf("%.2f",$data_total['user_win']):'<span style="color: red;">'.sprintf("%.2f",$data_total['user_win']).'</span>';?></td>
                <td><?php echo $data_total['user_win']>0?sprintf("%.2f",$data_total['user_win']):'<span style="color: red;">'.sprintf("%.2f",$data_total['user_win']).'</span>';?></td>
                <td><?php echo $data_total['user_win']>0?sprintf("%.2f",$data_total['user_win']):'<span style="color: red;">'.sprintf("%.2f",$data_total['user_win']).'</span>';?></td>
                <td><?php echo $data_total['user_win']>0?sprintf("%.2f",$data_total['user_win']):'<span style="color: red;">'.sprintf("%.2f",$data_total['user_win']).'</span>';?></td>
                <td><?php echo $data_total['user_win']>0?sprintf("%.2f",$data_total['user_win']):'<span style="color: red;">'.sprintf("%.2f",$data_total['user_win']).'</span>';?></td>
            </tr>
        </table>
        <br>

        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">AG视讯</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_ag.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."&type=BR' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_ag.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."&type=BR' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_ag['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_ag['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_ag['valid_money']);?></td>
                <td><?php echo $data_total_ag['user_win'] > 0 ? sprintf("%.2f",$data_total_ag['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag['user_win'] > 0 ? sprintf("%.2f",$data_total_ag['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag['user_win'] > 0 ? sprintf("%.2f",$data_total_ag['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag['user_win'] > 0 ? sprintf("%.2f",$data_total_ag['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag['user_win'] > 0 ? sprintf("%.2f",$data_total_ag['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag['user_win']).'</span>' ;?></td>
            </tr>
        </table>

        <br>

        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">AG电子</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_ag.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."&type=SLOT' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_ag.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."&type=SLOT' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_ag_dianzi['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_ag_dianzi['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_ag_dianzi['valid_money']);?></td>
                <td><?php echo $data_total_ag_dianzi['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_dianzi['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_dianzi['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_dianzi['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_dianzi['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_dianzi['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_dianzi['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_dianzi['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_dianzi['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_dianzi['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_dianzi['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_dianzi['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_dianzi['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_dianzi['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_dianzi['user_win']).'</span>' ;?></td>
            </tr>
        </table>

        <br>

        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">AG捕鱼王打鱼</td>
                <td class="td2">代理商</td>
                <td>子弹数</td>
                <td>子弹价值</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_ag_buyu.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_ag_buyu.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_ag_dayu['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_ag_dayu['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_ag_dayu['valid_money']);?></td>
                <td><?php echo $data_total_ag_dayu['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_dayu['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_dayu['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_dayu['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_dayu['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_dayu['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_dayu['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_dayu['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_dayu['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_dayu['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_dayu['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_dayu['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_dayu['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_dayu['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_dayu['user_win']).'</span>' ;?></td>
            </tr>
        </table>

        <br>

        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">AG捕鱼王养鱼</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_ag.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."&type=SLOT' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_ag.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."&type=SLOT' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_ag_yangyu['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_ag_yangyu['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_ag_yangyu['valid_money']);?></td>
                <td><?php echo $data_total_ag_yangyu['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_yangyu['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_yangyu['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_yangyu['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_yangyu['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_yangyu['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_yangyu['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_yangyu['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_yangyu['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_yangyu['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_yangyu['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_yangyu['user_win']).'</span>' ;?></td>
                <td><?php echo $data_total_ag_yangyu['user_win'] > 0 ? sprintf("%.2f",$data_total_ag_yangyu['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ag_yangyu['user_win']).'</span>' ;?></td>
            </tr>
        </table>

        <br>

        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">皇冠体育</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>

            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_hg.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_hg.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_hg['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_hg['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_hg['valid_money']);?></td>
                <td><?php echo $data_total_hg['user_win'] > 0 ? sprintf("%.2f",$data_total_hg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_hg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_hg['user_win'] > 0 ? sprintf("%.2f",$data_total_hg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_hg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_hg['user_win'] > 0 ? sprintf("%.2f",$data_total_hg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_hg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_hg['user_win'] > 0 ? sprintf("%.2f",$data_total_hg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_hg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_hg['user_win'] > 0 ? sprintf("%.2f",$data_total_hg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_hg['user_win']).'</span>';?></td>
            </tr>
        </table>


        <br>

        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">彩票</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_cp.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_cp.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_cp['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_cp['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_cp['valid_money']);?></td>
                <td><?php echo $data_total_cp['user_win'] > 0 ? sprintf("%.2f",$data_total_cp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_cp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_cp['user_win'] > 0 ? sprintf("%.2f",$data_total_cp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_cp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_cp['user_win'] > 0 ? sprintf("%.2f",$data_total_cp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_cp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_cp['user_win'] > 0 ? sprintf("%.2f",$data_total_cp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_cp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_cp['user_win'] > 0 ? sprintf("%.2f",$data_total_cp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_cp['user_win']).'</span>';?></td>
            </tr>
        </table>

        <br>

        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">开元棋牌</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web == DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_ky.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_ky.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_ky['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_ky['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_ky['valid_money']);?></td>
                <td><?php echo $data_total_ky['user_win'] > 0 ? sprintf("%.2f",$data_total_ky['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ky['user_win']).'</span>';?></td>
                <td><?php echo $data_total_ky['user_win'] > 0 ? sprintf("%.2f",$data_total_ky['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ky['user_win']).'</span>';?></td>
                <td><?php echo $data_total_ky['user_win'] > 0 ? sprintf("%.2f",$data_total_ky['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ky['user_win']).'</span>';?></td>
                <td><?php echo $data_total_ky['user_win'] > 0 ? sprintf("%.2f",$data_total_ky['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ky['user_win']).'</span>';?></td>
                <td><?php echo $data_total_ky['user_win'] > 0 ? sprintf("%.2f",$data_total_ky['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ky['user_win']).'</span>';?></td>
            </tr>
        </table>
        <!--<br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">皇冠棋牌</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
/*                    if($web == DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_hgqp.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_hgqp.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    */?>
                </td>
                <td><?php /*echo $data_total_hgqp['count_pay'];*/?></td>
                <td><?php /*echo sprintf("%.2f",$data_total_hgqp['total']);*/?></td>
                <td><?php /*echo sprintf("%.2f",$data_total_hgqp['valid_money']);*/?></td>
                <td><?php /*echo $data_total_hgqp['user_win'] > 0 ? sprintf("%.2f",$data_total_hgqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_hgqp['user_win']).'</span>';*/?></td>
                <td><?php /*echo $data_total_hgqp['user_win'] > 0 ? sprintf("%.2f",$data_total_hgqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_hgqp['user_win']).'</span>';*/?></td>
                <td><?php /*echo $data_total_hgqp['user_win'] > 0 ? sprintf("%.2f",$data_total_hgqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_hgqp['user_win']).'</span>';*/?></td>
                <td><?php /*echo $data_total_hgqp['user_win'] > 0 ? sprintf("%.2f",$data_total_hgqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_hgqp['user_win']).'</span>';*/?></td>
                <td><?php /*echo $data_total_hgqp['user_win'] > 0 ? sprintf("%.2f",$data_total_hgqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_hgqp['user_win']).'</span>';*/?></td>
            </tr>
        </table>-->
        <br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">VG棋牌</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web == DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_vgqp.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_vgqp.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_vgqp['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_vgqp['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_vgqp['valid_money']);?></td>
                <td><?php echo $data_total_vgqp['user_win'] > 0 ? sprintf("%.2f",$data_total_vgqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_vgqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_vgqp['user_win'] > 0 ? sprintf("%.2f",$data_total_vgqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_vgqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_vgqp['user_win'] > 0 ? sprintf("%.2f",$data_total_vgqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_vgqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_vgqp['user_win'] > 0 ? sprintf("%.2f",$data_total_vgqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_vgqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_vgqp['user_win'] > 0 ? sprintf("%.2f",$data_total_vgqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_vgqp['user_win']).'</span>';?></td>
            </tr>
        </table>
        <br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">乐游棋牌</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web == DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_lyqp.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_lyqp.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_lyqp['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_lyqp['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_lyqp['valid_money']);?></td>
                <td><?php echo $data_total_lyqp['user_win'] > 0 ? sprintf("%.2f",$data_total_lyqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_lyqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_lyqp['user_win'] > 0 ? sprintf("%.2f",$data_total_lyqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_lyqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_lyqp['user_win'] > 0 ? sprintf("%.2f",$data_total_lyqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_lyqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_lyqp['user_win'] > 0 ? sprintf("%.2f",$data_total_lyqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_lyqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_lyqp['user_win'] > 0 ? sprintf("%.2f",$data_total_lyqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_lyqp['user_win']).'</span>';?></td>
            </tr>
        </table>
        <br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">快乐棋牌</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web == DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_klqp.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_klqp.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_klqp['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_klqp['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_klqp['valid_money']);?></td>
                <td><?php echo $data_total_klqp['user_win'] > 0 ? sprintf("%.2f",$data_total_klqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_klqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_klqp['user_win'] > 0 ? sprintf("%.2f",$data_total_klqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_klqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_klqp['user_win'] > 0 ? sprintf("%.2f",$data_total_klqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_klqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_klqp['user_win'] > 0 ? sprintf("%.2f",$data_total_klqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_klqp['user_win']).'</span>';?></td>
                <td><?php echo $data_total_klqp['user_win'] > 0 ? sprintf("%.2f",$data_total_klqp['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_klqp['user_win']).'</span>';?></td>
            </tr>
        </table>
        <br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">MG电子</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web == DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_mg.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_mg.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_mg['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_mg['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_mg['valid_money']);?></td>
                <td><?php echo $data_total_mg['user_win'] > 0 ? sprintf("%.2f",$data_total_mg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_mg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_mg['user_win'] > 0 ? sprintf("%.2f",$data_total_mg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_mg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_mg['user_win'] > 0 ? sprintf("%.2f",$data_total_mg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_mg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_mg['user_win'] > 0 ? sprintf("%.2f",$data_total_mg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_mg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_mg['user_win'] > 0 ? sprintf("%.2f",$data_total_mg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_mg['user_win']).'</span>';?></td>
            </tr>
        </table>
        <br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">泛亚电竞</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web == DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_avia.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_avia.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_avia['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_avia['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_avia['valid_money']);?></td>
                <td><?php echo $data_total_avia['user_win'] > 0 ? sprintf("%.2f",$data_total_avia['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_avia['user_win']).'</span>';?></td>
                <td><?php echo $data_total_avia['user_win'] > 0 ? sprintf("%.2f",$data_total_avia['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_avia['user_win']).'</span>';?></td>
                <td><?php echo $data_total_avia['user_win'] > 0 ? sprintf("%.2f",$data_total_avia['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_avia['user_win']).'</span>';?></td>
                <td><?php echo $data_total_avia['user_win'] > 0 ? sprintf("%.2f",$data_total_avia['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_avia['user_win']).'</span>';?></td>
                <td><?php echo $data_total_avia['user_win'] > 0 ? sprintf("%.2f",$data_total_avia['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_avia['user_win']).'</span>';?></td>
            </tr>
        </table>

        <br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">雷火电竞</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web == DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_fire.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_fire.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_fire['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_fire['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_fire['valid_money']);?></td>
                <td><?php echo $data_total_fire['user_win'] > 0 ? sprintf("%.2f",$data_total_fire['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_fire['user_win']).'</span>';?></td>
                <td><?php echo $data_total_fire['user_win'] > 0 ? sprintf("%.2f",$data_total_fire['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_fire['user_win']).'</span>';?></td>
                <td><?php echo $data_total_fire['user_win'] > 0 ? sprintf("%.2f",$data_total_fire['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_fire['user_win']).'</span>';?></td>
                <td><?php echo $data_total_fire['user_win'] > 0 ? sprintf("%.2f",$data_total_fire['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_fire['user_win']).'</span>';?></td>
                <td><?php echo $data_total_fire['user_win'] > 0 ? sprintf("%.2f",$data_total_fire['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_fire['user_win']).'</span>';?></td>
            </tr>
        </table>

        <br>
        <!--第三方彩票start-->
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">彩票信用盘</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_ssc.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_ssc.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_ssc['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_ssc['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_ssc['valid_money']);?></td>
                <td><?php echo $data_total_ssc['user_win'] > 0 ? sprintf("%.2f",$data_total_ssc['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ssc['user_win']).'</span>';?></td>
                <td><?php echo $data_total_ssc['user_win'] > 0 ? sprintf("%.2f",$data_total_ssc['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ssc['user_win']).'</span>';?></td>
                <td><?php echo $data_total_ssc['user_win'] > 0 ? sprintf("%.2f",$data_total_ssc['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ssc['user_win']).'</span>';?></td>
                <td><?php echo $data_total_ssc['user_win'] > 0 ? sprintf("%.2f",$data_total_ssc['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ssc['user_win']).'</span>';?></td>
                <td><?php echo $data_total_ssc['user_win'] > 0 ? sprintf("%.2f",$data_total_ssc['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_ssc['user_win']).'</span>';?></td>
            </tr>
        </table>
        <br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">彩票官方盘</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_project.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_project.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_project['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_project['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_project['valid_money']);?></td>
                <td><?php echo $data_total_project['user_win'] > 0 ? sprintf("%.2f",$data_total_project['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_project['user_win']).'</span>';?></td>
                <td><?php echo $data_total_project['user_win'] > 0 ? sprintf("%.2f",$data_total_project['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_project['user_win']).'</span>';?></td>
                <td><?php echo $data_total_project['user_win'] > 0 ? sprintf("%.2f",$data_total_project['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_project['user_win']).'</span>';?></td>
                <td><?php echo $data_total_project['user_win'] > 0 ? sprintf("%.2f",$data_total_project['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_project['user_win']).'</span>';?></td>
                <td><?php echo $data_total_project['user_win'] > 0 ? sprintf("%.2f",$data_total_project['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_project['user_win']).'</span>';?></td>
            </tr>
        </table>
        <br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">彩票官方追号</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_trace.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_trace.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_trace['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_trace['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_trace['valid_money']);?></td>
                <td><?php echo $data_total_trace['user_win'] > 0 ? sprintf("%.2f",$data_total_trace['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_trace['user_win']).'</span>';?></td>
                <td><?php echo $data_total_trace['user_win'] > 0 ? sprintf("%.2f",$data_total_trace['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_trace['user_win']).'</span>';?></td>
                <td><?php echo $data_total_trace['user_win'] > 0 ? sprintf("%.2f",$data_total_trace['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_trace['user_win']).'</span>';?></td>
                <td><?php echo $data_total_trace['user_win'] > 0 ? sprintf("%.2f",$data_total_trace['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_trace['user_win']).'</span>';?></td>
                <td><?php echo $data_total_trace['user_win'] > 0 ? sprintf("%.2f",$data_total_trace['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_trace['user_win']).'</span>';?></td>
            </tr>
        </table>
        <!--第三方彩票end-->
        <br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">OG视讯</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_og.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_og.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_og['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_og['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_og['valid_money']);?></td>
                <td><?php echo $data_total_og['user_win'] > 0 ? sprintf("%.2f",$data_total_og['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_og['user_win']).'</span>';?></td>
                <td><?php echo $data_total_og['user_win'] > 0 ? sprintf("%.2f",$data_total_og['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_og['user_win']).'</span>';?></td>
                <td><?php echo $data_total_og['user_win'] > 0 ? sprintf("%.2f",$data_total_og['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_og['user_win']).'</span>';?></td>
                <td><?php echo $data_total_og['user_win'] > 0 ? sprintf("%.2f",$data_total_og['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_og['user_win']).'</span>';?></td>
                <td><?php echo $data_total_og['user_win'] > 0 ? sprintf("%.2f",$data_total_og['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_og['user_win']).'</span>';?></td>
            </tr>
        </table><br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">BBIN视讯</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_bbin.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_bbin.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_bbin['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_bbin['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_bbin['valid_money']);?></td>
                <td><?php echo $data_total_bbin['user_win'] > 0 ? sprintf("%.2f",$data_total_bbin['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_bbin['user_win']).'</span>';?></td>
                <td><?php echo $data_total_bbin['user_win'] > 0 ? sprintf("%.2f",$data_total_bbin['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_bbin['user_win']).'</span>';?></td>
                <td><?php echo $data_total_bbin['user_win'] > 0 ? sprintf("%.2f",$data_total_bbin['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_bbin['user_win']).'</span>';?></td>
                <td><?php echo $data_total_bbin['user_win'] > 0 ? sprintf("%.2f",$data_total_bbin['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_bbin['user_win']).'</span>';?></td>
                <td><?php echo $data_total_bbin['user_win'] > 0 ? sprintf("%.2f",$data_total_bbin['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_bbin['user_win']).'</span>';?></td>
            </tr>
        </table><br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">MW电子</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_mw.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_mw.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_mw['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_mw['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_mw['valid_money']);?></td>
                <td><?php echo $data_total_mw['user_win'] > 0 ? sprintf("%.2f",$data_total_mw['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_mw['user_win']).'</span>';?></td>
                <td><?php echo $data_total_mw['user_win'] > 0 ? sprintf("%.2f",$data_total_mw['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_mw['user_win']).'</span>';?></td>
                <td><?php echo $data_total_mw['user_win'] > 0 ? sprintf("%.2f",$data_total_mw['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_mw['user_win']).'</span>';?></td>
                <td><?php echo $data_total_mw['user_win'] > 0 ? sprintf("%.2f",$data_total_mw['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_mw['user_win']).'</span>';?></td>
                <td><?php echo $data_total_mw['user_win'] > 0 ? sprintf("%.2f",$data_total_mw['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_mw['user_win']).'</span>';?></td>
            </tr>
        </table><br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">CQ9电子</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_cq.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_cq.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_cq['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_cq['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_cq['valid_money']);?></td>
                <td><?php echo $data_total_cq['user_win'] > 0 ? sprintf("%.2f",$data_total_cq['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_cq['user_win']).'</span>';?></td>
                <td><?php echo $data_total_cq['user_win'] > 0 ? sprintf("%.2f",$data_total_cq['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_cq['user_win']).'</span>';?></td>
                <td><?php echo $data_total_cq['user_win'] > 0 ? sprintf("%.2f",$data_total_cq['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_cq['user_win']).'</span>';?></td>
                <td><?php echo $data_total_cq['user_win'] > 0 ? sprintf("%.2f",$data_total_cq['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_cq['user_win']).'</span>';?></td>
                <td><?php echo $data_total_cq['user_win'] > 0 ? sprintf("%.2f",$data_total_cq['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_cq['user_win']).'</span>';?></td>
            </tr>
        </table><br>
        <table class="m_tab">
            <tr>
                <td rowspan="2" class="td1">FG电子</td>
                <td class="td2">代理商</td>
                <td>笔数</td>
                <td>下注金额</td>
                <td>实际投注</td>
                <td>盈利</td>
                <td>代理商结果</td>
                <td>代理商交收</td>
                <td>总代理交收</td>
                <td>股东交收</td>
            </tr>
            <tr>
                <td>
                    <?php
                    if($web==DBPREFIX.'web_agents_data'){
                        echo "<a href='report_top_fg.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$Agent."' >".$Agent."</a>";
                    }else{
                        echo "<a href='report_top_fg.php?uid=".$uid."&date_start=".$_REQUEST['date_start']."&date_end=".$date_end."&world=".$World."' >".$World."</a>";
                    }
                    ?>
                </td>
                <td><?php echo $data_total_fg['count_pay'];?></td>
                <td><?php echo sprintf("%.2f",$data_total_fg['total']);?></td>
                <td><?php echo sprintf("%.2f",$data_total_fg['valid_money']);?></td>
                <td><?php echo $data_total_fg['user_win'] > 0 ? sprintf("%.2f",$data_total_fg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_fg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_fg['user_win'] > 0 ? sprintf("%.2f",$data_total_fg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_fg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_fg['user_win'] > 0 ? sprintf("%.2f",$data_total_fg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_fg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_fg['user_win'] > 0 ? sprintf("%.2f",$data_total_fg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_fg['user_win']).'</span>';?></td>
                <td><?php echo $data_total_fg['user_win'] > 0 ? sprintf("%.2f",$data_total_fg['user_win']) : '<span style="color: red;">'.sprintf("%.2f",$data_total_fg['user_win']).'</span>';?></td>
            </tr>
        </table>

    </div>
    </body>
    </html>
<?php

$loginfo='报表详细';
innsertSystemLog($loginname,$lever,$loginfo);
?>