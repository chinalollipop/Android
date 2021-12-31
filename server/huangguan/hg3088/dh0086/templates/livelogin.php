<?php
/**
 * AG真人
 *    1 个人账号登录游戏
 *    2 试玩账号进入游戏
 *
 */

require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
include "../include/agproxy.php";

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$pam_username = $_REQUEST['username'];
$pam_gameType = $_REQUEST['gameid']; // 从电子列表进入对应游戏页，带入gameType
$pam_mh5 = $_REQUEST['mh5'];  //mh5=y 代表 AGIN 移动网页版


// ag数据初始化-------------------------------------------- Start

$domain_url = $agsxInit['domain_url'];
$api_url = $agsxInit['api_url'];
$game_api_url = $agsxInit['game_api_url'];
$cagent = $agsxInit['cagent'];
$md5_key = $agsxInit['md5_key'];
$des_key = $agsxInit['des_key'];
$testers = $agsxInit['tester'];
$cny = $agsxInit['cny'];
$oAg = new agproxy($domain_url, $api_url, $game_api_url, $cagent, $md5_key, $des_key, $testers, $cny);

// ag数据初始化-------------------------------------------- End


// 判断用户名，生成游戏链接
// 注意：目前配置文件中指定的测试号没有平台前缀 ---------------------------------------------20180525
$pam_username = $agsxInitp['data_api_cagent'].'_'.$pam_username;
if ( in_array($pam_username, explode(',',$testers)) ){

    $ag_sql = "select password,is_test from `".DBPREFIX."ag_users` where `username` = '{$pam_username}'";
    $ag_result = mysqli_query($dbLink, $ag_sql);
    $ag_cou = mysqli_num_rows($ag_result);
    if($ag_cou==0) {
        echo "<script>alert('无AG账号，请先注册然后再进入~~~')</script>";
    }else{
        $ag_row = mysqli_fetch_assoc($ag_result);
        $ag_forwardGameUrl = $oAg->player_login_url($pam_username, $ag_row['password'], 1, $ag_row['is_test'], $pam_gameType);
    }

}

echo "<script>window.location.href='{$ag_forwardGameUrl['url']}'</script>";

