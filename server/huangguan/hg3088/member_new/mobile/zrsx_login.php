<?php
/**
 * /zrsx_login.php
 * AG真人登录注册接口
 *    1 登录时检查AG账号，没有则新建
 *    2 个人账号登录游戏
 * @param   gameid  电子游戏参数
 */
ini_set('display_errors','OFF');
include_once('include/config.inc.php');

require ("include/define_function_list.inc.php");
include "include/agproxy.php";
$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:'' ; // 兼容改版手机版 进入游戏
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {

    if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
        $status = '401.1';
        $describe = '你的登录信息已过期，请先登录!';
        original_phone_request_response($status,$describe);
    }else {
        echo "<script>alert('你的登录信息已过期，请先登录!');window.location.href='../login.php';</script>";
        exit;
    }

}
$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$pam_gameType = $_REQUEST['gameid']; // 从电子列表进入对应游戏页，带入gameType
$pam_gameType = $pam_gameType ?$pam_gameType:$_REQUEST['game_id']; // 为了兼容,跟其他统一


// ag数据初始化-------------------------------------------- Start
//$aUser = $row;
$m_domain_url = $agsxInit['m_domain_url'];
$api_url = $agsxInit['api_url'];
$game_api_url = $agsxInit['game_api_url'];
$cagent = $agsxInit['cagent'];
$md5_key = $agsxInit['md5_key'];
$des_key = $agsxInit['des_key'];
$testers = $agsxInit['tester'];
$cny = $agsxInit['cny'];
$pam_mh5 = 'y';  //mh5=y 代表 AGIN 移动网页版
$oAg = new agproxy($m_domain_url, $api_url, $game_api_url, $cagent, $md5_key, $des_key, $testers, $cny);
// ag数据初始化-------------------------------------------- End

    $ag_sql = "select `username`,`password`,`is_test` from `".DBPREFIX."ag_users` where `userid` = '{$_SESSION['userid']}'";
    $ag_result = mysqli_query($dbLink, $ag_sql);
    $ag_cou = mysqli_num_rows($ag_result);

    // 检查AG账号，没有则新建
    if($ag_cou==0){

        $sPrefix = $agsxInitp['data_api_cagent']; // 新建账号增加AG代理前缀
        $userPrefix = $agsxInitp['data_api_user_prefix'];// 新建账号增加AG用户前缀
        $length = rand(5,20);
        $ag_pwd = $oAg->make_char($length);//AG创建的时候由于要传密码.
        $prefix_username = $sPrefix.$userPrefix.'_'.$_SESSION['UserName'];
//        print_r( $prefix_username.'-'.$ag_pwd ); die;

        if (AG_TRANSFER_SWITCH === TRUE){
            $url = AG_TRANSFER_URL.'?action=cga&username='.$prefix_username.'&password='.$ag_pwd.'&is_test='.$_SESSION['test_flag'];
            $res = file_get_contents($url);
            $res = json_decode($res,true);
        }
        else{
            $res = $oAg->ag_checkOrCreateGameAccount($prefix_username, $ag_pwd);
        }

        if($res['info'] == 0 && $res='info'!='error' ){

            $data['userid'] = $_SESSION['userid'];
            $data['username'] = $prefix_username;
            $data['Agents'] = $_SESSION['Agents'];
            $data['World'] = $_SESSION['World'];
            $data['Corprator'] = $_SESSION['Corprator'];
            $data['Super'] = $_SESSION['Super'];
            $data['Admin'] = $_SESSION['Admin'];
            $data['password'] = $ag_pwd;
            $data['register_time'] = date('Y-m-d H:i:s');
            $data['last_launch_time'] = date('Y-m-d H:i:s');
            $data['launch_number'] = 1;
//            if ( $prefix_username==$testers){ // AG测试账号
//                $data['is_test'] = 1;
//            }else{ // AG正式账号
//                $data['is_test'] = 0;
//            }
            $data['is_test'] = $_SESSION['test_flag'];

            $sInsData = '';
            foreach ($data as $key => $value){
                if ($key=='is_test') {
                    $sInsData.= "`$key` = '{$value}'";
                }else{
                    $sInsData.= "`$key` = '{$value}',";
                }
            }
            $sql = "insert into `".DBPREFIX."ag_users` set $sInsData";
            $in = mysqli_query($dbMasterLink,$sql);
            if (!$in){
                if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
                    $status = '500';
                    $describe = 'AG游戏账号创建失败，请稍后重试~~';
                    original_phone_request_response($status,$describe);
                }else {
                    echo "<script>alert('AG游戏账号创建失败，请稍后重试~~~'); window.close();</script>";
                }
            }
            if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
                $status = '200';
                $describe = 'AG游戏账号已创建，请进行游戏~~~';
                original_phone_request_response($status,$describe);
            }else {
                echo "<script>alert('AG游戏账号已创建，请进行游戏~~~'); window.close();</script>";
            }

        }else{
            if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
                $status = '401.2';
                $describe = 'AG游戏账号创建失败，请稍后重试~~';
                original_phone_request_response($status,$describe);
            }else {
                echo "<script>alert('AG游戏账号创建失败，请稍后重试~~'); window.close();</script>";
            }
        }


    }else{
        // 手机版未指定测试账号链接，是否测试账号取决于代理

        $ag_row = mysqli_fetch_assoc($ag_result);

        if (AG_TRANSFER_SWITCH === TRUE){
            $url = AG_TRANSFER_URL.'?action=get_play_url&username='.$ag_row['username'].'&password='.$ag_row['password'].'&is_test='.$ag_row['is_test'].'&gameType='.$pam_gameType.'&mh5='.$pam_mh5;
            $ag_forwardGameUrl = file_get_contents($url);
            $ag_forwardGameUrl = json_decode($ag_forwardGameUrl, true);
        }
        else{
            $ag_forwardGameUrl = $oAg->player_login_url($ag_row['username'],$ag_row['password'],1,$ag_row['is_test'], $pam_gameType, $pam_mh5);
        }
    }


if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14 || $gametype) {
    $status = '200';
    $describe = 'success';
    $aData['url'] = $ag_forwardGameUrl['url'];
    original_phone_request_response($status,$describe,$aData);
}else {
    echo "<script>window.location.href='{$ag_forwardGameUrl['url']}'</script>";
}
