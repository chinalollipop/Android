<?php
/**
 * BBIN真人
 *    1 个人账号登录游戏
 *
 */

session_start();
include_once('../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    exit("<script>alert('您的登录信息已过期，请您重新登录！');window.close();</script>");
}

if ($_SESSION['test_flag']){
    exit("<script>alert('请登录真实账号登入OG视讯');window.close();</script>");
}


$Status=$_SESSION['Status'];
if ($Status>0){
    exit;
}

//$sql = "select username,is_test from `".DBPREFIX."jx_bbin_member_data` where `userid` = '{$_SESSION['userid']}'";
//$result = mysqli_query($dbLink, $sql);
//$cou = mysqli_num_rows($result);

//if($cou==0){
//    echo "<script>alert('无BBIN账号，请先注册然后再进入~~~');window.close();</script>";
//}else{
    echo "<script>window.location.href='bbin_api.php?action=getLaunchGameUrl'</script>";
//}