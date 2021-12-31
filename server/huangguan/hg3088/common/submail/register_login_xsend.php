<?php
/**
 * 赛邮云通信
 */
include_once('../../config.php');
//载入 app_config 文件
include_once('app_config.php');
//载入 SUBMAILAutoload 文件
include_once('SUBMAILAutoload.php');

$logPath = '/tmp/message';
if (!file_exists($logPath)) {
    mkdir($logPath, 0777, true);
    chmod($logPath, 0777);
}

$logFile = $logPath . '/submail_' . date('Ymd');

// 接收参数
$mobile = trim($_REQUEST['mem_phone']);
if(!isPhone($mobile)){
    original_phone_request_response('4002','请输入有效的手机号!');
}

// redis记录发送时间，60s内不允许重复发
$redisObj = new Ciredis();
if(!$userMobileCount) {
    $cellPhone = $userid .'_mobile_'. $mobile; //redis key
    $cellPhoneTime = $redisObj->getSimpleOne($cellPhone);
    if($cellPhoneTime) {
        $allowtime = time()-$cellPhoneTime;
        if($allowtime<60) {
            $data = [
                'allowtime' => $allowtime,
            ];
            original_phone_request_response('4004','抱歉，请' . $allowtime . 's后再次发送！' , $data);
        }
    }
    $redisObj->insert($cellPhone, time(), 2*60);
}


// 3. 实例化MESSAGEXsend 类，message_configs = ["appid" , "appkey" , "sign_type" ,"server"]
$submail = new MESSAGEXsend($message_configs);
$submail->setTo($mobile);    //设置短信接收的11位手机号码
$submail->SetProject("pJQM3");  //设置短信项目标记
$code = generateRandNum(4);
$userMobileKey = $mobile . '_code';  // redis  key
$redisObj->insert($userMobileKey, $code, 5*60); //记录当前用户验证码有效期5分钟

$submail->AddVar('code',$code);
$send = $submail->xsend();  //短信发送
//$send['status'] == 'success';

// 4. 发送消息接口失败
if($send['status'] == 'error') {
    $describe = '短信请求失败!' . $send['code'] . $send['msg'];
    original_phone_request_response('5001' , $describe);
}

// 5. 发送消息接口成功
if($send['status'] == 'success') {
        $status  = 1;
        $aInsertData = $aTemp = [];
        $aTemp  = [
            'userid' => $userid,   //用户id
            'username' => $_SESSION['UserName'],
            'events' => '',   //短信发送情况请求request,成功delivered,失败dropped,正在发送sending,短信上行mo,未知网关unkown,模板通过template_accept,模板未通过template_reject
            'phone' => $mobile,
            'code' => $code,
            'send_id' => $send['send_id'],   // 唯一标识
            'fee' => $send['fee'],   // 此短信计费条数
           // 'timestamp' => '',     // 事件触发时间 strtotime(timestamp)
            'token' => '',
            'signature' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'status' => $status,
        ];
        $aInsertData[] = $aTemp;
        $keys = $values = '';
        $count = count($aInsertData);
        foreach ($aInsertData as $key => $value){
            $keys = '(' . implode(",", array_keys($value)) . ')';
            $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
        }

        // 入库web_member_sms 消息表
        $dbMasterLink->autocommit(false);
        $sql = "REPLACE INTO `" . DBPREFIX . "web_member_sms` {$keys} VALUES {$values}";
        if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
            writeLog("【" . date('Y-m-d H:i:s') . "】手机号登录:{$mobile}，错误【" . mysqli_error($dbMasterLink) . "】入库注单数据失败", 1);
            $dbMasterLink->rollback();
            return false;
        }

        $insertedRows = mysqli_affected_rows($dbMasterLink);
        $dbMasterLink->commit();
        writeLog("【" . date('Y-m-d H:i:s') . "】手机号登录:{$mobile}请求接口成功，验证码为【{$code}】，请在消息表查看！");
        $describe = '短信请求成功，请注意接收!';
        original_phone_request_response('200' , $describe);
}

$status = '5005';
$describe = '该手机号发送异常，请输入有效手机号或稍后再试！';
original_phone_request_response($status,$describe,$data);


/**
 * 记录日志文件
 * @param $log
 * @param bool $isError
 */
 function writeLog($log, $isError = false){
    global $logFile;
    if($isError)
        @file_put_contents($logFile, date('Y-m-d H:i:s') . '-mobile-' . $log . "\n", FILE_APPEND);
}
