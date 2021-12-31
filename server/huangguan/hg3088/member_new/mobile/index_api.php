<?php

include_once('include/config.inc.php');

//@error_log(json_encode($_SERVER).PHP_EOL,  3,  '/tmp/index_api.log');

$todaydate=date('Y-m-d');

$host = getMainHost();

$weburl= HTTPS_HEAD.'://'.$host.'?topc=yes'; // 电脑版网址
$username = $_SESSION['UserName']; // 拿到用户名
$oid = $_SESSION['Oid']; // 拿到oid
$hgid = $_SESSION['userid'] ;
$hgpwd = $_SESSION['password'];
$cpUrl=HTTPS_HEAD."://".CP_MOBILE_URL.'.'.$host."/";
$apptip = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:'' ; // app 登录 13 ios  13 安卓

if($username && $oid){
    //CP登录
    $redisObj = new Ciredis();
    $uniqueUnionCode = getUnionCode();

    $redisRes = $redisObj->setOne($hgid.'_HG_UNION_CP',serialize($uniqueUnionCode));
    $AgentsName = $_SESSION['Agents'] ;
    $resultA = mysqli_query($dbLink,"select ID from ".DBPREFIX."web_agents_data where UserName='$AgentsName'");
    $rowA = mysqli_fetch_assoc($resultA);
    $hg_union_agentid = CP_UNION_VALID;
    $id = CP_UNION_VALID - $hgid;
    $ida = $hg_union_agentid - $rowA['ID'];
    $name = $username ;
    $pwd = $hgpwd ;
    $key = md5($pwd.$uniqueUnionCode.md5($name));
    $test_flag = $_SESSION['test_flag'] ; // test_flag 0 为正式用户，1 为测试用户
    $urlLogin=HTTPS_HEAD.'://'.CP_MOBILE_URL.'.'.getMainHost().'/login/login_ok_api.winer?agent='.CP_AGENT.'&id='.$id.'&ida='.$ida.'&name='.$name.'&pwd='.$pwd.'&key='.$key.'&flag='.$test_flag.'&apptip='.$apptip ;

}

$status = '200';
$describe = '恭喜成功获取彩票登录地址和彩票域名！！！';
$aData[0]['cpUrl'] = $cpUrl;
$aData[0]['urlLogin'] = $urlLogin;
original_phone_request_response($status, $describe,$aData);



