<?php
/**
 * 自定义MG电子API
 *
 *  执行任务 action （默认检查MG账号，或者创建账号）
 *       b  获取余额
 *       hg2mg  平台上分到mg
 *       mg2hg  mg下分到平台
 *       getLaunchGameUrl  真钱模式
 *       getDemoLaunchGameUrl  试玩模式
 */

define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(__FILE__))))));
include_once "../include/config.inc.php";
require_once ROOT_DIR.'/common/mg/api.php';

if(!isset($_SESSION['Oid']) || $_SESSION['Oid'] == ''){
    $status = '401.1';
    $describe = '您的登录信息已过期，请您重新登录！';
    original_phone_request_response($status,$describe);
}

$uid = $_SESSION['Oid']?$_SESSION['Oid']:$_REQUEST['uid'];
$userid = $_SESSION['userid'] ;
$data = array();
// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM " . DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
if(!$stmt->affected_rows) {
    $status = '401.1';
    $describe = '您的登录信息已过期，请您重新登录！';
    original_phone_request_response($status,$describe,$data);
}
$aUser = $stmt->get_result()->fetch_assoc();
$userid = $aUser['ID'] ;

if ($aUser['test_flag']){
    $status = '401.2';
    $describe = '请使用真实账号登入MG电子';
    original_phone_request_response($status,$describe,$data);
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$b = $score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
if($exchangeFrom == 'hg' && $exchangeTo == 'mg'){
    $action = 'hg2mg';
}

if($exchangeFrom == 'mg' && $exchangeTo == 'hg'){
    $action = 'mg2hg';
}
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2mg' || $action == 'mg2hg'){
    // 检查当前会员是否设置不准操作额度分层
    // 检查分层是否开启 status 1 开启 0 关闭
    // layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金 5 仅限可以投注体育，不能额度转换去其它馆
    if ($sUserlayer==3 || $sUserlayer==5){
        $layer = getUserLayerById($sUserlayer);
        if ($layer['status']==1) {
            $status = '400.66';
            $describe = '账号分层异常，请联系我们在线客服' ;
            original_phone_request_response($status,$describe,$aData);
        }
    }
}

$pageMark='mg';
$maintenanceData = maintenance($pageMark);
if(isset($maintenanceData[$pageMark]) && $maintenanceData[$pageMark]['state'] == 1){
    $status = '403';
    $describe = '很抱歉，MG电子游戏临时维护中。您可以进行平台其他游戏！感谢您的耐心等候。';
    original_phone_request_response($status,$describe);
}

// 首先从redis读取access_token
// 为空则doLogin，保存access_token、refresh_token到redis，保存一小时
/**
 *
主要API讲解：
1. 登入: 在采取任何动作前，您需要有一个有效的Token。在确认您的身份后，才能使用其他的API窗口。辅助说明：
access_token与refresh_token的有效时限为一个小时，他们差别在于前者是用于其他窗口拨叫的认证用途，而后者是为以下重刷Token所用。

2. 重刷Token: 为了方便使用，我们鼓励用户们存取并在有效时限内，重复使用同一个Token，这样的大量的减少用户们对我们的登入窗口过度的依赖。
   如以上，每个Token的有效时限为一个小时，用户们只需要在每个Token过期前重刷一个新的Token来替代就行了。
 */
$redisObj = new Ciredis();
$resp = $redisObj->getSimpleOne('mg_access_token_refresh_token');
$resp = json_decode($resp,true);
if ($resp['success']){

    $resp_body = $resp["body"];
    $access_token = $resp_body["access_token"];
    $refresh_token = $resp_body["refresh_token"];

    // 超过1个小时则doRefreshToken
    $hour = 60*60;
    if ( (time()-strtotime($resp_body['token_last_update_time'])) > $hour){
//    if ( 1){
        // redis倒计时剩余1分钟时，进行refresh
        $resp = doRefreshToken($refresh_token);
        if ($resp['success']){
            $resp_body = $resp["body"];
            $access_token = $resp_body["access_token"];
            $refresh_token = $resp_body["refresh_token"];
            $resp['body']['token_last_update_time'] = date('Y-m-d H:i:s');
            $redisObj->setOne('mg_access_token_refresh_token',json_encode($resp));
        }else{
            $access_token = mg_do_login_redis_token();
        }
    }

}else{
    $access_token = mg_do_login_redis_token();
}

function mg_do_login_redis_token(){
    global $redisObj;
    $resp = doLogin();
    if ($resp['success']){
        $resp_body = $resp["body"];
        $access_token = $resp_body["access_token"];
        $refresh_token = $resp_body["refresh_token"];
        $resp['body']['token_last_update_time'] = date('Y-m-d H:i:s');
        $redisObj->setOne('mg_access_token_refresh_token',json_encode($resp));// 设置redis
        return $access_token;
    }else{
        $status = '401.4';
        $describe = 'doLogin失败，请联系技术';
        original_phone_request_response($status,$describe);
    }
}


$account_id = $parent_id;
$member_ext_ref = $mg_prefix.$aUser['UserName'];

//$res = getAccountDetails ($access_token, $account_id); // 账户信息
//$res = getAccountDetailsByExtRef ($access_token, $member_ext_ref); // 账户信息
//echo json_encode($res); die;



// 3.检测登录MG会员
$lyExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "mg_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $lyExist = mysqli_num_rows($result);
    if(!$lyExist){

        if($action == 'b'){ // 未创建账号前请求余额接口
            $data = [
                'mg_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];
//            exit(json_encode(['err' => 0, 'data' => $data]));
            $status = '200';
            $describe = '获取余额成功！';
            original_phone_request_response($status,$describe,$data);
        }else{

            $length = rand(6,20);
            $member_password = make_char($length);
            $res = createMember($access_token, $account_id, $member_ext_ref, $member_password, $member_ext_ref);
            if ($res['success']){
                $data = [
                    'userid' => $aUser['ID'],
                    'username' => $member_ext_ref,
                    'password' => $member_password,
                    'ext_ref' => $member_ext_ref,
                    'agents' => $aUser['Agents'],
                    'world' => $aUser['World'],
                    'corporator' => $aUser['Corprator'],
                    'super' => $aUser['Super'],
                    'admin' => $aUser['Admin'],
                    'register_time' => $now,
                    'last_launch_time' => $now,
                    'launch_times' => 1,
                    'is_test' => $aUser['test_flag'],
                    'mg_user_info' => json_encode($res)
                ];
                $sInsData = '';
                foreach ($data as $key => $value){
                    $sInsData .= "`$key` = '{$value}'" . ($key == 'mg_user_info' ? '' : ',');
                }
                $sql = "INSERT INTO `" . DBPREFIX . "mg_member_data` SET $sInsData";
//                echo $sql;die;
                if (!mysqli_query($dbMasterLink, $sql)) {
//                    exit(json_encode(['err' => -5, 'msg' => 'MG账号异常，请您稍后重试！']));
                    $status = '401.5';
                    $describe = 'MG账号异常，请您稍后重试！';
                    original_phone_request_response($status,$describe);
                }else{
//                    exit("<script>alert('MG账号初始化成功，请您重新转换金额');window.close();</script>");
                    $status = '201';
                    $describe = 'MG账号初始化成功，请您继续提交转账';
                    original_phone_request_response($status,$describe);
                }
            }else{
                @error_log(date('Y-m-d H:i:s').'-sportnew-'.json_encode($res).PHP_EOL, 3, '/tmp/mg_api.log');
//                exit(json_encode(['err' => -6, 'msg' => '注册MG账号失败'.json_encode($res)]));
                $status = '401.6';
                $describe = '注册MG账号失败，请联系技术';
                original_phone_request_response($status,$describe);
            }
        }
    }
}

$result = mysqli_query($dbLink, "SELECT * FROM `" . DBPREFIX . "mg_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
$res = mysqli_fetch_assoc($result);
$member_account_id = json_decode($res['mg_user_info'],true)['body']['id'];

switch ($action){
    case 'b':
        $res = getWalletDetails ($access_token, $member_account_id);
        if (!$res['success']){
//            exit(json_encode( [ 'err'=>'-1','msg'=>'余额获取失败'.json_encode($res) ] ));
            $status = '401.7';
            $describe = '余额获取失败，请联系技术';
            original_phone_request_response($status,$describe);
        }
        else{
            if ($res["body"][0]['credit_balance'] == 0){
                $res["body"][0]['credit_balance'] = '0.00';
            }
            $data = [
                'mg_balance' => $res["body"][0]['credit_balance'],
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            $status = '200';
            $describe = '获取余额成功！';
            original_phone_request_response($status,$describe,$data);
        }

        break;
    case 'hg2mg':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $b)){
            $status = '401.8';
            $describe = '转账金额只支持正整数，请重新输入';
            original_phone_request_response($status,$describe);
        }
        if ($b > 10000000){
//            exit(json_encode([ 'err' => -7, 'msg'=>'单次上分不能超过一千万，请重新输入！' ]));
            $status = '401.9';
            $describe = '单次上分不能超过一千万，请重新输入！';
            original_phone_request_response($status,$describe);
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']-$fShiftMoney; // 用户账变后的余额
        //mg生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $sTrans_no = 'MGIN' . $sTime8 . $sUser6; // 订单号生成规则

        $data['userid']= $userid ;
        $data['Checked']=1;
        $data['reason']='hg to mg';
        $data['AuditDate']=date("Y-m-d H:i:s");
        $data['Gold']=$fShiftMoney;
        $data['moneyf']=$moneyf;
        $data['currency_after']=$currency_after;
        $data['AddDate']=date("Y-m-d",time());
        $data['Type']='Q';
        $data['From']='hg';
        $data['To']='mg';
        $data['UserName']=$_SESSION['UserName'];
        $data['Agents']=$_SESSION['Agents'];
        $data['World']=$_SESSION['World'];
        $data['Corprator']=$_SESSION['Corprator'];
        $data['Super']=$_SESSION['Super'];
        $data['Admin']=$_SESSION['Admin'];
        $data['CurType']='RMB';
        $data['Date']=date("Y-m-d H:i:s",time());
        $data['Name']=$_SESSION['Alias'];
        $data['Waterno']='';
        $data['Phone']=$_SESSION['Phone'];
        $data['Notes']='即时入账';
        $data['test_flag'] = $_SESSION['test_flag'];
        $data['Order_Code']=$sTrans_no;

        $sInsData = '';
        foreach ($data as $key => $value){
            if ($key=='Order_Code') {
                $sInsData.= "`$key` = '{$value}'";
            }else{
                $sInsData.= "`$key` = '{$value}',";
            }
        }

        mysqli_autocommit($dbMasterLink,false);// 关闭本次数据库连接的自动命令提交事务模式
        $oRes = mysqli_query($dbLink, "SELECT userid FROM ".DBPREFIX."gxfcy_userlock WHERE userid = {$userid}");
        $iCou = mysqli_num_rows($oRes);
        if($iCou == 0){
            $insert_flag = mysqli_query($dbMasterLink, "insert into `".DBPREFIX."gxfcy_userlock` set `userid` = {$userid}");
            if(!$insert_flag) {
                mysqli_rollback($dbMasterLink);
//                exit(json_encode( [ 'err'=>'-17','msg'=>'添加用户锁失败' ] ));
                $status = '401.10';
                $describe = '添加用户锁失败';
                original_phone_request_response($status,$describe);
            }
        }
        $lock = mysqli_query($dbMasterLink, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$userid} for update");
        if($lock){
            $lockMoney = mysqli_query($dbMasterLink, "select Money from `".DBPREFIX.MEMBERTABLE."` where `ID` = {$userid} for update");
            if ($lockMoney){

                $lockMoneyRes = mysqli_fetch_assoc($lockMoney);
                if ($lockMoneyRes['Money'] < $b){
                    mysqli_query($dbMasterLink,"ROLLBACK");
//                    exit(json_encode( [ 'err'=>'-6','msg'=>'余额不足~~' ] ));
                    $status = '401.11';
                    $describe = '余额不足~~';
                    original_phone_request_response($status,$describe);
                }

                // 更新玩家账户余额
                $up = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money-$fShiftMoney, Online=1 , OnlineTime=now() WHERE ID=".$userid);
                if($up){
                    //校验通过开始处理订单
                    $in = mysqli_query($dbMasterLink,"insert into `".DBPREFIX."web_sys800_data` set $sInsData");
                    if($in){
                        $moneyLogRes=addAccountRecords(array($userid,$_SESSION['UserName'],$_SESSION['test_flag'],$moneyf,$fShiftMoney*-1,$currency_after,36,22,$insertId,"MG电子游艺额度转换"));
                        if($moneyLogRes){
                            $milliseconds = round(microtime(true) * 1000);
                            $tx_ext_ref = "TX-ID:{$member_account_id}:{$milliseconds}";
                            $tx_category = "TRANSFER";
                            $tx_type = "CREDIT";
                            $tx_amount = $fShiftMoney;
                            $res = createTransactionByAccountId ($access_token, $member_account_id, $tx_ext_ref, $tx_category, "", $tx_type, $tx_amount);
                            if (!$res['success']){
                                mysqli_rollback($dbMasterLink);
//                                exit(json_encode( [ 'err'=>'-12','msg'=>'mg上分失败' ] ));
                                $status = '401.12';
                                $describe = 'mg上分失败';
                                original_phone_request_response($status,$describe);
                            }
                            else{
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);
                                $data = [
                                    'mg_balance' => sprintf('%.2f', $res['body'][1]['balance']), // 元模式
                                    'hg_balance' => formatMoney($currency_after)
                                ];
//                                exit( json_encode( ['err'=>0, 'msg'=>$data ] ) );
                                $status = '200';
                                $describe = '恭喜上分成功';
                                original_phone_request_response($status,$describe,$data);
                            }
                        }else{
                            mysqli_rollback($dbMasterLink);
//                            exit(json_encode( [ 'err'=>'-11','msg'=>'添加用户资金账变失败' ] ));
                            $status = '401.13';
                            $describe = '添加用户资金账变失败';
                            original_phone_request_response($status,$describe);
                        }
                    }else{
                        mysqli_rollback($dbMasterLink);
//                        exit(json_encode( [ 'err'=>'-10','msg'=>'添加账变记录失败' ] ));
                        $status = '401.14';
                        $describe = '添加账变记录失败';
                        original_phone_request_response($status,$describe);
                    }
                }else{
                    mysqli_rollback($dbMasterLink);
//                    exit(json_encode( [ 'err'=>'-9','msg'=>'更新余额失败' ] ));
                    $status = '401.15';
                    $describe = '更新余额失败';
                    original_phone_request_response($status,$describe);
                }
            }else{
                mysqli_rollback($dbMasterLink);
//                exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户资金锁失败' ] ));
                $status = '401.16';
                $describe = '添加用户资金锁失败';
                original_phone_request_response($status,$describe);
            }
        }else{
            mysqli_rollback($dbMasterLink);
//            exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户锁失败' ] ));
            $status = '401.17';
            $describe = '添加用户锁失败';
            original_phone_request_response($status,$describe);
        }


        break;
    case 'mg2hg':

        // 1.检查mg余额
        $res = getWalletDetails ($access_token, $member_account_id);
        if ( !$res['success'] ) {
//            exit( json_encode( [ 'err'=>'-1','msg'=>'MG余额获取失败' ] ) );
            $status = '401.18';
            $describe = 'MG余额检查失败';
            original_phone_request_response($status,$describe);
        }else{
            $mgGetbalance["balance"] = $res["body"][0]['credit_balance'];
        }

        if (floatval($mgGetbalance["balance"]) < floatval($b)){
//            exit(json_encode( [ 'err'=>'-6','msg'=>'MG余额不足~~' ] ));
            $status = '401.19';
            $describe = 'MG余额不足~~';
            original_phone_request_response($status,$describe);
        }

        $mgGetbalance["balance"] = number_format($mgGetbalance["balance"], 2, '.', ',');

        if ($b > 10000000){
//            exit(json_encode( [ 'err'=>'-2','msg'=>'单次下分不能超过一千万，请重新输入！' ] ));
            $status = '401.20';
            $describe = '单次下分不能超过一千万，请重新输入！';
            original_phone_request_response($status,$describe);
        }

//        if(!preg_match("/^[1-9][0-9]*$/",$b)){
//            $status = '401.21';
//            $describe = '转账只支持正整数，请重新输入';
//            original_phone_request_response($status,$describe);
//        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']+$fShiftMoney; // 用户账变后的余额
        //ag生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $sTrans_no = 'MGOUT' . $sTime8 . $sUser6;

        // 下分
//        $member_account_id = 18718;
        $milliseconds = round(microtime(true) * 1000);
        $tx_ext_ref = "TX-ID:{$member_account_id}:{$milliseconds}";
        $tx_category = "TRANSFER";
        $tx_type = "DEBIT"; // CREDIT是转入MG, DEBIT是从MG转出
        $tx_amount = $fShiftMoney;
//        printLine("");
//        printLine("");
//        printLine("****************************");
//        printLine("Method: Transaction By Account ID");
//        printLine("****************************");
//        printLine("Resquest: {Member Account ID: {$member_account_id}, External Ref: {$tx_ext_ref}, Category: {$tx_category}, Amount: {$tx_amount}}");
//        printLine("Response: " . json_encode(createTransactionByAccountId ($access_token, $member_account_id, $tx_ext_ref, $tx_category, "", $tx_type, $tx_amount)));
        $res = createTransactionByAccountId ($access_token, $member_account_id, $tx_ext_ref, $tx_category, "", $tx_type, $tx_amount);
        if (!$res['success']){
            mysqli_rollback($dbMasterLink);
//            exit(json_encode( [ 'err'=>'-12','msg'=>'下分失败' ] ));
            $status = '401.22';
            $describe = '下分失败';
            original_phone_request_response($status,$describe);
        }
        else{

            $data['userid']= $userid ;
            $data['Checked']=1;
            $data['reason']='ag to hg';
            $data['AuditDate']=date("Y-m-d H:i:s");
            $data['Gold']=$fShiftMoney;
            $data['moneyf']=$moneyf;
            $data['currency_after']=$currency_after;
            $data['AddDate']=date("Y-m-d",time());
            $data['Type']='Q';
            $data['From']='mg';
            $data['To']='hg';
            $data['UserName']=$_SESSION['UserName'];
            $data['Agents']=$_SESSION['Agents'];
            $data['World']=$_SESSION['World'];
            $data['Corprator']=$_SESSION['Corprator'];
            $data['Super']=$_SESSION['Super'];
            $data['Admin']=$_SESSION['Admin'];
            $data['CurType']='RMB';
            $data['Date']=date("Y-m-d H:i:s",time());
            $data['Name']=$_SESSION['Alias'];
            $data['Waterno']='';
            $data['Phone']=$_SESSION['Phone'];
            $data['Notes']='即时入账';
            $data['test_flag'] = $_SESSION['test_flag'];
            $data['Order_Code']=$sTrans_no;

            $sInsData = '';
            foreach ($data as $key => $value){
                if ($key=='Order_Code') {
                    $sInsData.= "`$key` = '{$value}'";
                }else{
                    $sInsData.= "`$key` = '{$value}',";
                }
            }

            mysqli_autocommit($dbMasterLink,false);// 关闭本次数据库连接的自动命令提交事务模式
            $oRes = mysqli_query($dbLink, "SELECT userid FROM ".DBPREFIX."gxfcy_userlock WHERE userid = {$userid}");
            $iCou = mysqli_num_rows($oRes);
            if($iCou == 0){
                $insert_flag = mysqli_query($dbMasterLink, "insert into `".DBPREFIX."gxfcy_userlock` set `userid` = {$userid}");
                if(!$insert_flag) {
                    mysqli_rollback($dbMasterLink);
//                    exit(json_encode( [ 'err'=>'-17','msg'=>'添加用户锁失败' ] ));
                    $status = '401.23';
                    $describe = '添加用户锁失败';
                    original_phone_request_response($status,$describe);
                }
            }
            $lock = mysqli_query($dbMasterLink, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$userid} for update");
            if($lock){
                $lockMoney = mysqli_query($dbMasterLink, "select Money from `".DBPREFIX.MEMBERTABLE."` where `ID` = {$userid} for update");
                if($lockMoney){
                    // 更新玩家账户余额
                    $up = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money+$fShiftMoney  , Online=1 , OnlineTime=now() WHERE ID=".$userid);
                    if($up){
                        //校验通过开始处理订单
                        $in = mysqli_query($dbMasterLink,"insert into `".DBPREFIX."web_sys800_data` set $sInsData");
                        if($in){
                            //添加会员账变日志
                            $insertId=mysqli_insert_id($dbMasterLink);
                            $rowMoney=mysqli_fetch_assoc($lockMoney);
                            $moneyLogRes=addAccountRecords(array($userid,$_SESSION['UserName'],$_SESSION['test_flag'],$rowMoney['Money'],$fShiftMoney,$rowMoney['Money']+$fShiftMoney,37,22,$insertId,"MG电子游艺额度转换"));
                            if($moneyLogRes){
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);
                                $data = [
                                    'mg_balance' => sprintf('%.2f', $res['body'][0]['balance']), // 元模式
                                    'hg_balance' => formatMoney($currency_after)
                                ];
//                                exit( json_encode( ['err'=>0, 'msg'=>$data ] ) );
                                $status = '200';
                                $describe = '下分成功';
                                original_phone_request_response($status,$describe,$data);
                            }else{
                                mysqli_rollback($dbMasterLink);
//                                exit(json_encode( [ 'err'=>'-11','msg'=>'添加用户资金账变失败' ] ));
                                $status = '401.24';
                                $describe = '添加用户资金账变失败';
                                original_phone_request_response($status,$describe);
                            }
                        }else{
                            mysqli_rollback($dbMasterLink);
//                            exit(json_encode( [ 'err'=>'-10','msg'=>'添加账变记录失败' ] ));
                            $status = '401.25';
                            $describe = '添加账变记录失败';
                            original_phone_request_response($status,$describe);
                        }

                    }else{
                        mysqli_rollback($dbMasterLink);
//                        exit(json_encode( [ 'err'=>'-9','msg'=>'余额更新失败' ] ));
                        $status = '401.26';
                        $describe = '余额更新失败';
                        original_phone_request_response($status,$describe);
                    }
                }else{
                    mysqli_rollback($dbMasterLink);
//                    exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户资金锁失败' ] ));
                    $status = '401.27';
                    $describe = '添加用户资金锁失败';
                    original_phone_request_response($status,$describe);
                }
            }else{
                mysqli_rollback($dbMasterLink);
//                exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户锁失败' ] ));
                $status = '401.28';
                $describe = '添加用户锁失败';
                original_phone_request_response($status,$describe);
            }

        }

        break;
    case 'getLaunchGameUrl':
        $game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:1035;
        $res = getLaunchGameUrl ($access_token, $member_account_id, $game_id, $language);
        if (!$res['success']){
//            exit(json_encode( [ 'err'=>'-1','msg'=>'游戏链接获取失败' ] ));
//            exit("<script>alert('游戏链接获取失败".json_encode($res)."');window.close();</script>");
            $status = '401.29';
            $describe = '游戏链接获取失败';
            original_phone_request_response($status,$describe);
        }
        else{
            header("Location:".$res['body']);
        }
        break;
    case 'getDemoLaunchGameUrl':
        $game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:1035;
        $res = getDemoLaunchGameUrl ($access_token, $game_id, $language);
        if (!$res['success']){
//            exit(json_encode( [ 'err'=>'-1','msg'=>'游戏链接获取失败' ] ));
            $status = '401.30';
            $describe = 'MG电子测试账户链接获取失败';
            original_phone_request_response($status,$describe);
        }
        else{
            header("Location:".$res['body']);
        }
        break;
    default:
        $status = '401.26';
        $describe = '抱歉，您的请求不予处理！';
        original_phone_request_response($status,$describe);
        break;
}


function printLine ($message) {
    echo "<BR>{$message}";
}

/**
 * 生产密码
 * @param $length
 * @return string
 */
function make_char($length){
    // 密码字符集，可任意添加你需要的字符

    $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',

        'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',

        't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',

        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',

        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',

        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    // 在 $chars 中随机取 $length 个数组元素键名

    $char_txt = '';

    for($i = 0; $i < $length; $i++){

        // 将 $length 个数组元素连接成字符串

        $char_txt .= $chars[array_rand($chars)];

    }

    return $char_txt;
}

