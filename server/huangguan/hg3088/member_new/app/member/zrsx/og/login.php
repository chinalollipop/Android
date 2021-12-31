<?php
/**
 * OG真人
 *    1 个人账号登录游戏
 *
 */

require ("../../include/config.inc.php");
require ("../../include/define_function_list.inc.php");
include_once "../../include/address.mem.php";

// 判断OG视讯是否维护-单页面维护功能
checkMaintain('og');

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

//$sql = "select username,is_test from `".DBPREFIX."og_member_data` where `userid` = '{$_SESSION['userid']}'";
//$result = mysqli_query($dbLink, $sql);
//$cou = mysqli_num_rows($result);

//if($cou==0){
//    echo "<script>alert('无OG账号，请先注册然后再进入~~~');window.close();</script>";
//}else{
    echo "<script>window.location.href='og_api.php?action=getLaunchGameUrl'</script>";
//}