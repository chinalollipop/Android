<?php
/**
 * 设置真实姓名
 * Date: 2018/10/18
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

include('../include/config.inc.php');

$alias_allows_duplicate = getSysConfig('alias_allows_duplicate'); // 验证会员昵称是否重复

$data = array() ;
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '502';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe,$data);
}

$realname = trim($_REQUEST['realname']);
$payPassword = trim($_REQUEST['paypassword']);

if(!isTrueName($realname)){ // 真实姓名验证
    $status='401.2';
    $describe = "真实姓名不符合规范!";
    original_phone_request_response($status,$describe,$data);
}
if($realname){
    if($_SESSION['Alias'] && $realname != $_SESSION['Alias']){
        $status='401.3';
        $describe = "不能更改真实姓名!";
        original_phone_request_response($status,$describe,$data);
    }
    if($alias_allows_duplicate) {
        $msql = "select UserName from " . DBPREFIX . MEMBERTABLE . " where Alias='$realname'";
        $mresult = mysqli_query($dbLink, $msql);
        $mcou = mysqli_num_rows($mresult);
        if ($mcou > 0) {
            $status = '401.31';
            $describe = "真实姓名【{$realname}】已存在，请联系在线客服进行处理";
            original_phone_request_response($status, $describe, $aData);
        }
    }
}
// 提款密码验证
if(!isPayNumber($payPassword)){
    $status='401.4';
    $describe = "提款密码不符合规范!";
    original_phone_request_response($status,$describe,$data);
}

$mysql="update ".DBPREFIX.MEMBERTABLE." set Alias='".$realname."', Address='".$payPassword."', Online=1 , OnlineTime=now() where ID='".$_SESSION["userid"]."'";
$result = mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
if($result){
    // 更新session
    $_SESSION['Alias'] = $realname;
    $_SESSION['payPassword'] = $payPassword;
    $data = array(
        'realName'=>returnRealName($realname)
    );
    $status = '200';
    $describe = '您个人信息设置成功！';
    original_phone_request_response($status,$describe,$data);
}else{
    $status = '500.1';
    $describe = '您个人信息设置失败！';
    original_phone_request_response($status,$describe,$data);
}