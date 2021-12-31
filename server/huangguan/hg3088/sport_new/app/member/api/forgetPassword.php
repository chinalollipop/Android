<?php

exit(json_encode(['status' => 0, 'describe' => '请联系客服！']));

/**
 * 忘记密码
 * Date: 2018/8/3
 */
require ("../include/config.inc.php");


$data=array();
$action_type = trim($_REQUEST['action_type']);
if($submitNum > 3){
    $status='401.1';
    $describe="已超过提交次数，请稍后再试！";
    original_phone_request_response($status,$describe,$data);
}

$submitNum ++;

$username = trim($_POST['username']);
$realname = trim($_POST['realname']);
$withdrawPwd = trim($_POST['withdraw_password']);
//$birthday = trim($_POST['birthday']);
$newPwd = trim($_POST['new_password']);
$confirmPwd = trim($_POST['password_confirmation']);
$steptype = $_POST['steptype'];

// 查询用户信息
$stmt = $dbLink->prepare('SELECT `ID`, `PassWord`, `Alias`, `Address`,Status,loginTimesOfFail,resetTimesOfFail,isAutoFreeze,AutoFreezeDate FROM ' . DBPREFIX.MEMBERTABLE.' WHERE `UserName` = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$aUser = $result->fetch_assoc();

// 24小时前自动冻结的自动解冻
if ($aUser['Status']==1){
    if ($aUser['isAutoFreeze']==0){
        $describe = "您的账号被冻结，请与在线客服联系。";
        $status = '400.7';
        original_phone_request_response($status,$describe,$aData);
    }
    else{
        // 冻结24小时解冻
        if ((time()-strtotime($aUser['AutoFreezeDate']))>24*60*60){
            $sql="update ".DBPREFIX.MEMBERTABLE." set Status=0,loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00' where UserName='$username'";
            $res = mysqli_query($dbMasterLink,$sql);
        }
        else{ // 冻结24小时内
            $describe = "您的账号已被冻结使用24小时，请与在线客服联系。";
            $status = '400.8';
            original_phone_request_response($status,$describe,$aData);
        }
    }
}

if($action_type == 'reset'){ // 直接修改密码，避免过多交互
    if($steptype=='one'){ // 第一步
        // 2.找回密码信息验证
        if(empty($realname)){
            $status='401.4';
            $describe="请输入真实姓名！";
            original_phone_request_response($status,$describe,$data);
        }
        // 1.账号验证
        if (empty($username)){
            $status='401.2';
            $describe="请输入会员帐号！";
            original_phone_request_response($status,$describe,$data);
        }

        if (!$result->num_rows){
            $status='401.3';
            $describe="当前帐号不存在，请重新输入！";
            original_phone_request_response($status,$describe,$data);
        }


        if(empty($withdrawPwd)){
            $status='401.5';
            $describe="请输入取款密码！";
            original_phone_request_response($status,$describe,$data);
        }
        // 新增验证码
        if(!$_POST['verifycode']){
            $status = '400.1';
            $describe = "请输入验证码！";
            original_phone_request_response($status,$describe,$data);

        }
        if(strtolower($_POST['verifycode']) != $_SESSION['authcode']){
            $status = '400.2';
            $describe = "验证码输入错误！";
            original_phone_request_response($status,$describe,$data);
        }

        if($aUser['Alias'] != $realname ){
            $status='401.6';
            $describe="您的真实姓名有误！";
            original_phone_request_response($status,$describe,$data);
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
                $status='401.6';
                $describe = "您的账号已被冻结使用24小时，请与在线客服联系。";
            }
            else{
                $status='401.7';
                $describe = "取款密码错误,您还有".(3-$iResetTimesOfFail)."次机会";
            }
            original_phone_request_response($status,$describe,$data);
        }
        original_phone_request_response(200.1, '验证信息成功!',$data);
    }else{ // 第二步

        // 3.重置密码
        if(empty($newPwd)){
            $status='401.7';
            $describe="请输入6-15位登录密码！";
            original_phone_request_response($status,$describe,$data);
        }

        if(empty($confirmPwd)){
            $status='401.8';
            $describe="请输入确认密码！";
            original_phone_request_response($status,$describe,$data);
        }

        if($newPwd != $confirmPwd){
            $status='401.9';
            $describe="密码与确认密码不一致！";
            original_phone_request_response($status,$describe,$data);
        }

        $md5NewPwd = passwordEncryption($newPwd,$username);
        if($aUser['PassWord'] == $md5NewPwd){
            $status='401.10';
            $describe="新密码与原密码一致，请登录！";
            original_phone_request_response($status,$describe,$data);
        }
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
    $stmtMember = $dbMasterLink->prepare("UPDATE " . DBPREFIX.MEMBERTABLE." SET `PassWord` = ?, `EditDate` = ? WHERE `UserName` = ?");
    $stmtMember->bind_param("sss", $md5NewPwd, $updatedTime, $username);
    $stmtMember->execute();
    $updateMember = $stmtMember->affected_rows;

    if($updateMember){
        if($iNum && $updateLottery || !$iNum){
            $dbMasterLink->commit();
            !$iNum or $cpMasterDbLink->commit();
            $dbMasterLink->autocommit(true);
            !$iNum or $cpMasterDbLink->autocommit(true);
            original_phone_request_response(200.2, '密码更改成功，请登录!',$data);
        }else{
            $dbMasterLink->rollback();
            !$iNum or $cpMasterDbLink->rollback();
            original_phone_request_response(401.11, '密码更改失败，请稍后重试!',$data);
        }
    }else{
        $dbMasterLink->rollback();
        !$iNum or $cpMasterDbLink->rollback();
        original_phone_request_response(401.12, '密码更改失败，请稍后重试!',$data);
    }
}










