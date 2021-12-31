<?php
session_start();
/**
 * 返回彩票登录地址
 *
 */

require ("../include/config.inc.php");
include "../include/address.mem.php";

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '502';
    $describe = '你已退出登录，请重新登录';
    $aData = [] ;
    original_phone_request_response($status,$describe,$aData);
}

$redisObj = new Ciredis();
$username = $_SESSION['UserName'];
$test_flag =$_SESSION['test_flag'];
$pwd = $_SESSION['password'];

$uniqueUnionCode = getUnionCode();
$redisRes = $redisObj->setOne($_SESSION['userid'].'_HG_UNION_CP',serialize($uniqueUnionCode));
$AgentsName = $_SESSION['Agents'];
$resultA = mysqli_query($dbLink,"select ID,UserName,PassWord from ".DBPREFIX."web_agents_data where UserName='$AgentsName'");
$rowA = mysqli_fetch_assoc($resultA);
$hg_union_agentid = CP_UNION_VALID;
$id = CP_UNION_VALID - $_SESSION['userid'];
$ida = $hg_union_agentid - $rowA['ID'];
$name = $username ;
$key = md5($pwd.$uniqueUnionCode.md5($name));
$urlLogin=HTTPS_HEAD.'://'.CP_URL.'.'.getMainHost().'/login/login_ok_api.winer?agent='.CP_AGENT.'&id='.$id.'&ida='.$ida.'&name='.$name.'&pwd='.$pwd.'&key='.$key.'&flag='.$test_flag ;

$status = '200';
$describe = '链接请求成功';
$aData = array(
    'lotteryUrl'=> $urlLogin
) ;
original_phone_request_response($status,$describe,$aData);



