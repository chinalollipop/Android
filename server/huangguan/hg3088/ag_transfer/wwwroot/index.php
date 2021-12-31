<?php
/**
 * 响应各个平台AG前端的请求
 *   cga 创建账号
 *   b 获取余额
 *   hg2ag 体育转账到ag
 *   ag2hg ag转账到体育
 *   get_play_url 获取游戏链接
 */

error_reporting(1);
ini_set('display_errors','On');
require("../common/config.php");
include "../include/agproxy.php";

// 传进来的参数
$action = $_REQUEST['action']; // 动作指令
$domain_url = $_REQUEST['domain_url']; // 返回的网站域名，作为参数传过来
$is_test = $_REQUEST['is_test'];
$pam_username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$pam_gameType = $_REQUEST['gameType']; // 从电子列表进入对应游戏页，带入gameType
$pam_mh5 = $_REQUEST['mh5'];
// 固定的值
$api_url = $agsxInit['api_url']; // gi url
$game_api_url = $agsxInit['game_api_url']; // gci url
$cagent = $agsxInit['cagent'];
$md5_key = $agsxInit['md5_key'];
$des_key = $agsxInit['des_key'];
$testers = $agsxInit['tester'];
$cny = $agsxInit['cur'];
$oAg = new agproxy($domain_url, $api_url, $game_api_url, $cagent, $md5_key, $des_key, $testers, $cny);


switch ($action){
    case 'cga':

        $prefix_username = $pam_username;
        $test_flag = $is_test;

        // 创建账号
        if($test_flag == 1){ // AG测试账号
            $res = $oAg->ag_checkOrCreateGameAccount($prefix_username, $password, 1);
        }else{ // AG正式账号
            $res = $oAg->ag_checkOrCreateGameAccount($prefix_username, $password);
        }
        $res = json_encode($res,JSON_UNESCAPED_UNICODE);
        exit($res);
        break;
    case 'b':
        $agGetbalance = $oAg->ag_getBalance($pam_username, $password, $is_test);
        $agGetbalance = json_encode($agGetbalance,JSON_UNESCAPED_UNICODE);
        exit($agGetbalance);
        break;
    case 'hg2ag':

        $sTrans_no = $_REQUEST['transNo'];
        $fShiftMoney = $_REQUEST['shiftMoney'];
        $aResult = $oAg->player_deposit($pam_username,$password, $sTrans_no,$fShiftMoney);
        $sResult = json_encode($aResult, JSON_UNESCAPED_UNICODE);
        exit($sResult);
        break;
    case 'ag2hg':
        $sTrans_no = $_REQUEST['transNo'];
        $fShiftMoney = $_REQUEST['shiftMoney'];
        $aResult = $oAg->player_withdraw($pam_username,$password, $sTrans_no,$fShiftMoney);
        $sResult = json_encode($aResult, JSON_UNESCAPED_UNICODE);
        exit($sResult);
        break;
    case 'get_play_url':

        $ag_forwardGameUrl = $oAg->player_login_url($pam_username,$password,1,$is_test, $pam_gameType, $pam_mh5);
        $ag_forwardGameUrl = json_encode($ag_forwardGameUrl,JSON_UNESCAPED_UNICODE);
        exit( $ag_forwardGameUrl);
        break;
    default:
        exit('参数错误');
        break;
}

