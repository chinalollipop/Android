<?php
/**
 * 设置真实姓名
 * Date: 2018/10/18
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

include_once('../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '401.1';
    $describe = '请重新登录!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

$alias_allows_duplicate = getSysConfig('alias_allows_duplicate'); // 验证会员昵称是否重复

$realname = trim($_REQUEST['realname']);
//$phone = trim($_REQUEST['phone']);
//$wechat = trim($_REQUEST['wechat']);
//$birthday = trim($_REQUEST['birthday']);
//
//$birthday_y = date('Y', strtotime($birthday));
//$birthday_m = date('m', strtotime($birthday));
//$birthday_d = date('d', strtotime($birthday));

if(!isTrueName($realname)){ // 真实姓名验证
    $status='401.2';
    $describe = "真实姓名不符合规范!";
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}
if($alias_allows_duplicate && !empty($realname)) {
    $msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where Alias='$realname'";
    $mresult = mysqli_query($dbLink,$msql);
    $mcou = mysqli_num_rows($mresult);
    if ($mcou>0){
        $status='401.21';
        $describe = "真实姓名已存在!";
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
}
//if(!isPhone($phone)){ // 手机号码验证
//    $status='401.3';
//    $describe = "手机号码不符合规范!";
//    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
//}
//if(!isWechat($wechat)){ // 微信号码验证
//    $status='401.4';
//    $describe = "微信号码不符合规范!";
//    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
//}
//if(!checkbirthdate($birthday_m,$birthday_d,$birthday_y)){ // 出生日期验证
//    $status='401.5';
//    $describe = "出生日期不符合规范!年龄必须满足18-122岁之间";
//    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
//}

$stmtMember = $dbMasterLink->prepare("UPDATE ".DBPREFIX.MEMBERTABLE." SET `Alias` = ?, `E_Mail` = ?, `birthday` = ?, `EditDate` = now(), Online=1 , OnlineTime = now() WHERE Oid = '". $_SESSION['Oid'] . "' AND Status < 2");
$stmtMember->bind_param("sss", $realname, $wechat, $birthday);
$stmtMember->execute();
$updateMember = $stmtMember->affected_rows;
if($updateMember){
    // 更新session
    $_SESSION['Alias'] = $realname;
    $_SESSION['E_Mail'] = $wechat; // 微信
//    $_SESSION['Phone'] = $phone;
    $_SESSION['birthday'] = $birthday;

    $status = '200';
    $describe = '您个人信息设置成功！';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}else{
    $status = '500.1';
    $describe = '您个人信息设置失败！';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}