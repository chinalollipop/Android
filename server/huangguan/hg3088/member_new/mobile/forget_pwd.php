<?php
exit(json_encode(['status' => 0, 'describe' => '请联系客服！']));
/**
 * 忘记密码
 * Date: 2018/8/3
 */
include_once('include/config.inc.php');

if(!isset($_REQUEST['appRefer']) || !isset($_REQUEST['action_type'])){
    $status=0;
    $describe='缺少参数！';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
    $terminalId = intval($_REQUEST['appRefer']);
}else{
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $terminalId=3;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $terminalId=4;
    }
}

$aTerminal = mysqli_query($dbLink, 'SELECT id FROM ' . DBPREFIX . 'web_terminals WHERE id = ' . $terminalId);
if(!mysqli_num_rows($aTerminal)){
//    exit(json_encode(['status' => 0, 'describe' => '非法终端！']));
    $status=0;
    $describe='非法终端！';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

$action_type = trim($_REQUEST['action_type']);
if($submitNum > 3){
    $status=0;
    $describe='已超过提交次数，请稍后再试！';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}
$submitNum ++;

$username = trim($_REQUEST['username']);
$realname = trim($_REQUEST['realname']);
$withdrawPwd = trim($_REQUEST['withdraw_password']);
//$birthday = trim($_REQUEST['birthday']);
$newPwd = trim($_REQUEST['new_password']);
$confirmPwd = trim($_REQUEST['password_confirmation']);

// 查询用户信息
$stmt = $dbLink->prepare('SELECT `ID`, `PassWord`, `Alias`, `Address`,Status,loginTimesOfFail,resetTimesOfFail,isAutoFreeze,AutoFreezeDate FROM '.DBPREFIX.MEMBERTABLE.' WHERE `UserName` = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$aUser = $result->fetch_assoc();

// 24小时前自动冻结的自动解冻
if ($aUser['Status']==1){
    if ($aUser['isAutoFreeze']==0){
        $status=0;
        $describe='您的账号被冻结，请与在线客服联系。';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    else{
        // 冻结24小时解冻
        if ((time()-strtotime($aUser['AutoFreezeDate']))>24*60*60){
            $sql="update ".DBPREFIX.MEMBERTABLE." set Status=0,loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00' where UserName='$username'";
            $res = mysqli_query($dbMasterLink,$sql);
        }
        else{ // 冻结24小时内
            $status=0;
            $describe='您的账号已被冻结使用24小时，请与在线客服联系。';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }
    }
}

if($action_type == 'check') {
    // 1.账号验证
    if (empty($username)){
//        exit(json_encode(['status' => 0, 'describe' => '请输入会员帐号！']));
        $status=0;
        $describe='请输入会员帐号！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }

    if (!$result->num_rows){
//        exit(json_encode(['status' => 0, 'describe' => '当前帐号不存在，请重新输入！']));
        $status=0;
        $describe='当前帐号不存在，请重新输入！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }

    original_phone_request_response(200, 'success');
}else if($action_type == 'recheck'){
    // 1.账号验证
    if (empty($username)){
//        exit(json_encode(['status' => 0, 'describe' => '请输入会员帐号！']));
        $status=0;
        $describe='请输入会员帐号！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }

    if (!$result->num_rows){
//        exit(json_encode(['status' => 0, 'describe' => '当前帐号不存在，请重新输入！']));
        $status=0;
        $describe='当前帐号不存在，请重新输入！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    // 2.找回密码信息验证
    if(empty($realname)){
//        exit(json_encode(['status' => 0, 'describe' => '请输入真实姓名！']));
        $status=0;
        $describe='请输入真实姓名！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    if(empty($withdrawPwd)){
//        exit(json_encode(['status' => 0, 'describe' => '请输入取款密码！']));
        $status=0;
        $describe='请输入取款密码！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
//    if(empty($birthday))
//        exit(json_encode(['status' => 0, 'describe' => '请选择您的生日！']));
    if($aUser['Alias'] != $realname || $aUser['Address'] !== $withdrawPwd ){
//        exit(json_encode(['status' => 0, 'describe' => '您的真实姓名或取款密码有误！']));
        $status=0;
        $describe='您的真实姓名或取款密码有误！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    original_phone_request_response(200, 'success');
}else if($action_type == 'reset'){ // 直接修改密码，避免过多交互
    // 1.账号验证
    if (empty($username)){
//        exit(json_encode(['status' => 0, 'describe' => '请输入会员帐号！']));

        $status=0;
        $describe='请输入会员帐号！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    if (!$result->num_rows){
//        exit(json_encode(['status' => 0, 'describe' => '当前帐号不存在，请重新输入！']));
        $status=0;
        $describe='当前帐号不存在，请重新输入！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }

    // 2.找回密码信息验证
    if(empty($realname)){
//        exit(json_encode(['status' => 0, 'describe' => '请输入真实姓名！']));
        $status=0;
        $describe='请输入真实姓名！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    if(empty($withdrawPwd)) {
//        exit(json_encode(['status' => 0, 'describe' => '请输入取款密码！']));
        $status=0;
        $describe='请输入取款密码！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
//    if(empty($birthday))
//        exit(json_encode(['status' => 0, 'describe' => '请选择您的生日！']));

    if($aUser['Alias'] != $realname ){
//        exit(json_encode(['status' => 0, 'describe' => '您的真实姓名有误！']));
        $status=0;
        $describe='您的真实姓名有误！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    if ($aUser['Address'] !== $withdrawPwd){

        $nowdate = date('Y-m-d H:i:s');
        $iResetTimesOfFail = $aUser['resetTimesOfFail']+1; // 失败次数
        // 登录失败>=5次 或者 修改密码 >= 3次，自动冻结
        if ($iResetTimesOfFail>=3){
            $sql="update ".DBPREFIX.MEMBERTABLE." set Status=1,resetTimesOfFail=".$iResetTimesOfFail.",isAutoFreeze=1,AutoFreezeDate='".$nowdate."' where UserName='$username'";
            $res = mysqli_query($dbMasterLink,$sql);
        }else{
            // 小于5次，更新失败次数和时间
            $sql="update ".DBPREFIX.MEMBERTABLE." set resetTimesOfFail=".$iResetTimesOfFail.",isAutoFreeze=1,AutoFreezeDate='".$nowdate."' where UserName='$username'";
            $res = mysqli_query($dbMasterLink,$sql);
        }
        if ($iResetTimesOfFail>=3){
            $describe = "您的账号已被冻结使用24小时，请与在线客服联系。";
        }
        else{
            $describe = "取款密码错误,您还有".(3-$iResetTimesOfFail)."次机会";
        }
//        exit(json_encode(['status' => 0, 'describe' => $describe]));
        $status=0;
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    // 3.重置密码
    if(empty($newPwd)){
//        exit(json_encode(['status' => 0, 'describe' => '请输入6-15位登录密码！']));
        $status=0;
        $describe='请输入6-15位登录密码！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    if(empty($confirmPwd)){
//        exit(json_encode(['status' => 0, 'describe' => '请输入确认密码！']));
        $status=0;
        $describe='请输入确认密码！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    if($newPwd != $confirmPwd){
//        exit(json_encode(['status' => 0, 'describe' => '密码与确认密码不一致！']));

        $status=0;
        $describe='密码与确认密码不一致！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    $md5NewPwd = passwordEncryption($newPwd,$username);
    if($aUser['PassWord'] == $md5NewPwd) {
//        exit(json_encode(['status' => 211, 'describe' => '新密码与原密码一致，请登录！']));
        $status=0;
        $describe='新密码与原密码一致，请登录！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
    $updatedTime = date('Y-m-d H:i:s');

    $cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'], $database['cpDefault']['user'], $database['cpDefault']['password'], $database['cpDefault']['dbname'],
        $database['cpDefault']['port']) or die(json_encode(['status' => 0, 'describe' => '网络繁忙，请稍后重试！']));
    // 查询彩票用户信息
    $oResult = mysqli_query($cpMasterDbLink,'SELECT hguid FROM xmcp_gxfc.gxfcy_user WHERE `hguid` = ' . $aUser['ID'] . ' LIMIT 1');
    $iNum = mysqli_num_rows($oResult);
    // 开启事务
    $dbMasterLink->autocommit(false);
    // 更改彩票会员密码
    if($iNum){
        $cpMasterDbLink->autocommit(false);
        $stmtLottery = $cpMasterDbLink->prepare("UPDATE gxfcy_user SET `userpsw` = ? WHERE `hguid` = ?");
        $stmtLottery->bind_param("ss", $md5NewPwd, $aUser['ID']);
        $stmtLottery->execute();
        $updateLottery = $stmtLottery->affected_rows;
    }
    // 更改会员密码
    $stmtMember = $dbMasterLink->prepare("UPDATE ".DBPREFIX.MEMBERTABLE." SET `PassWord` = ?, `EditDate` = ? WHERE `UserName` = ?");
    $stmtMember->bind_param("sss", $md5NewPwd, $updatedTime, $username);
    $stmtMember->execute();
    $updateMember = $stmtMember->affected_rows;

    if($updateMember){
        if($iNum && $updateLottery || !$iNum){
            $dbMasterLink->commit();
            !$iNum or $cpMasterDbLink->commit();
            $dbMasterLink->autocommit(true);
            !$iNum or $cpMasterDbLink->autocommit(true);
            original_phone_request_response(200, '密码更改成功!');
        }else{
            $dbMasterLink->rollback();
            !$iNum or $cpMasterDbLink->rollback();
//            exit(json_encode(['status' => 0, 'describe' => '密码更改失败，请稍后重试！']));

            $status=0;
            $describe='密码更改失败，请稍后重试！';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }
    }else{
        $dbMasterLink->rollback();
        !$iNum or $cpMasterDbLink->rollback();
//        exit(json_encode(['status' => 0, 'describe' => '密码更改失败，请稍后重试！']));
        $status=0;
        $describe='密码更改失败，请稍后重试！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
}









