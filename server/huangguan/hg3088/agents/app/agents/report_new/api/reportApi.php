<?php
error_reporting(1);
ini_set('display_errors','On');

session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include_once ("../../../agents/include/address.mem.php");
include_once ("../../../agents/include/config.inc.php");
include_once ("../../../agents/include/define_function_list.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

if( (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level']!='D') {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

switch ($_REQUEST['action']){
    case 'agent_report_top_agents':

        include ("../report_top_hg.php");//体育
        include ("../report_top_ag.php");//AG电子
        include ("../report_top_ag_zhenren.php");//AG真人
        include ("../report_top_ag_buyu.php");//AG捕鱼
        include ("../report_top_cp.php");//体育彩票
        include ("../report_top_ky.php");//开元棋牌
       // include ("../report_top_hgqp.php");//皇冠棋牌
        include ("../report_top_vgqp.php");//VG棋牌
        include ("../report_top_lyqp.php");//乐游棋牌
        include ("../report_top_klqp.php");//快乐棋牌
        include ("../report_top_mg.php");//mg电子
        include ("../report_top_avia.php");//泛亚电竞
        include ("../report_top_fire.php");//雷火电竞
        include ("../report_top_ssc.php");//国民彩票信用盘
        include ("../report_top_project.php");//国民彩票官方盘
        include ("../report_top_trace.php");//国民彩票官方盘追号
        include ("../report_top_og.php");//OG视讯
        include ("../report_top_bbin.php");//BBIN视讯
        include ("../report_top_mw.php");//MW电子
        include ("../report_top_cq.php");//CQ9电子
        include ("../report_top_fg.php");//FG电子


        $data_agents_plus_subtotal['UserName'] = '小计：';
        $data_agents_plus_subtotal['count_pay'] = 0;
        $data_agents_plus_subtotal['total'] = 0;
        $data_agents_plus_subtotal['user_win'] = 0;
        $data_agents_plus_subtotal['valid_money'] = 0;
        foreach ($data_agents_plus as $k => $v){
            $data_agents_plus_subtotal['UserName'] = '小计：';
            $data_agents_plus_subtotal['count_pay'] += $v['count_pay'];
            $data_agents_plus_subtotal['total'] += $v['total'];
            $data_agents_plus_subtotal['user_win'] += $v['user_win'];
            $data_agents_plus_subtotal['valid_money'] += $v['valid_money'];
        }

        $data['agents'] = array_values($data_agents_plus);
        $data['subtotal'] = $data_agents_plus_subtotal;

        exit(json_encode($data, JSON_UNESCAPED_UNICODE));

        break;
    case 'agent_report_top_users':

        include ("../report_top_hg_mem.php");//体育
        include ("../report_top_ag_mem.php");//AG电子
        include ("../report_top_ag_zhenren_mem.php");//AG真人
        include ("../report_top_ag_buyu_mem.php");//AG捕鱼
        include ("../report_top_cp_mem.php");//体育彩票
        include ("../report_top_ky_mem.php");//开元棋牌
       // include ("../report_top_hgqp_mem.php");//皇冠棋牌
        include ("../report_top_vgqp_mem.php");//VG棋牌
        include ("../report_top_lyqp_mem.php");//乐游棋牌
        include ("../report_top_klqp_mem.php");//快乐棋牌
        include ("../report_top_mg_mem.php");//MG电子
        include ("../report_top_avia_mem.php");//泛亚电竞
        include ("../report_top_fire_mem.php");//雷火电竞
        include ("../report_top_ssc_mem.php");//国民彩票信用盘
        include ("../report_top_project_mem.php");//国民彩票官方盘
        include ("../report_top_trace_mem.php");//国民彩票官方盘追号
        include ("../report_top_og_mem.php");//OG视讯
        include ("../report_top_bbin_mem.php");//BBIN视讯
        include ("../report_top_mw_mem.php");//MW电子
        include ("../report_top_cq_mem.php");//CQ9电子
        include ("../report_top_fg_mem.php");//FG电子

        $data_users_plus_subtotal['UserName'] = '小计：';
        $data_users_plus_subtotal['count_pay'] = 0;
        $data_users_plus_subtotal['total'] = 0;
        $data_users_plus_subtotal['user_win'] = 0;
        $data_users_plus_subtotal['valid_money'] = 0;
        foreach ($data_users_plus as $k => $v){
            $data_users_plus_subtotal['UserName'] = '小计：';
            $data_users_plus_subtotal['count_pay'] += $v['count_pay'];
            $data_users_plus_subtotal['total'] += $v['total'];
            $data_users_plus_subtotal['user_win'] += $v['user_win'];
            $data_users_plus_subtotal['valid_money'] += $v['valid_money'];
        }
        $data['users'] = array_values($data_users_plus);
        $data['subtotal'] = $data_users_plus_subtotal;
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));

        break;
    default:
        exit('参数错误');
        break;
}

