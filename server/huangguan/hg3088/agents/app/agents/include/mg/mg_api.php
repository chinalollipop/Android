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

error_reporting(E_ALL);
ini_set('display_errors','Off');
define("ROOT_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
include_once "../../include/config.inc.php";
include_once "../../include/redis.php";
require_once ROOT_DIR.'/common/mg/api.php';

$username = isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : '';
if (strpos($username,'_')!==false){
    $username = explode('_',$username,2)[1];
}

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone`, `Alias` FROM " . DBPREFIX.MEMBERTABLE." where `UserName` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
if(!$stmt->affected_rows) {
    exit(json_encode( ['code' => '422', 'message' => '您的登录信息已过期，请您重新登录！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();
$userid = $aUser['ID'] ;

if ($aUser['test_flag']){
//    exit(json_encode( ['code' => '-2', 'msg' => '请使用真实账号登入MG电子'] ) );
    exit("<script>alert('请登录真实账号登入MG电子');window.close();</script>");
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
        exit(json_encode(['code' => -4, 'msg' => 'doLogin失败'.json_encode($resp)]));
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
                'hg_balance' => sprintf('%.2f', $aUser['Money'])
            ];
            exit(json_encode(['code' => 0, 'data' => $data]));
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
                    exit(json_encode(['code' => -5, 'msg' => 'MG账号异常，请您稍后重试！']));
                }else{
                    exit(json_encode(['code' => 222, 'msg' => 'MG账号初始化成功，请您继续转账']));
                }
            }else{
                exit(json_encode(['code' => -6, 'msg' => '注册MG账号失败'.json_encode($res)]));
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
            exit(json_encode( [ 'code'=>'-1','msg'=>'余额获取失败'.json_encode($res) ] ));
        }
        else{
            $data = [
                'mg_balance' => number_format($res["body"][0]['credit_balance'],2),
                'hg_balance' => sprintf('%.2f', $aUser['Money'])
            ];
            exit( json_encode( ['code'=>0, 'data' => $data ] ) );
        }

        break;
    case 'hg2mg':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $b))
            exit(json_encode([ 'code' => -2, 'msg' => '转账金额只支持正整数，请重新输入' ]));

        if ($b > 10000000){
            exit(json_encode([ 'code' => -7, 'msg'=>'单次上分不能超过一千万，请重新输入！' ]));
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
        $data['UserName']=$aUser['UserName'];
        $data['Agents']=$aUser['Agents'];
        $data['World']=$aUser['World'];
        $data['Corprator']=$aUser['Corprator'];
        $data['Super']=$aUser['Super'];
        $data['Admin']=$aUser['Admin'];
        $data['CurType']='RMB';
        $data['Date']=date("Y-m-d H:i:s",time());
        $data['Name']=$aUser['Alias'];
        $data['Waterno']='';
        $data['Phone']=$aUser['Phone'];
        $data['Notes']='即时入账';
        $data['test_flag'] = $aUser['test_flag'];
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
                exit(json_encode( [ 'code'=>'-17','msg'=>'添加用户锁失败' ] ));
            }
        }
        $lock = mysqli_query($dbMasterLink, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$userid} for update");
        if($lock){
            $lockMoney = mysqli_query($dbMasterLink, "select Money from `".DBPREFIX.MEMBERTABLE."` where `ID` = {$userid} for update");
            if ($lockMoney){

                $lockMoneyRes = mysqli_fetch_assoc($lockMoney);
                if ($lockMoneyRes['Money'] < $b){
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    exit(json_encode( [ 'code'=>'-6','msg'=>'余额不足~~' ] ));
                }

                // 更新玩家账户余额
                $up = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money-$fShiftMoney, Online=1 , OnlineTime=now() WHERE ID=".$userid);
                if($up){
                    //校验通过开始处理订单
                    $in = mysqli_query($dbMasterLink,"insert into `".DBPREFIX."web_sys800_data` set $sInsData");
                    if($in){
                        $moneyLogRes=addAccountRecords(array($userid,$aUser['UserName'],$aUser['test_flag'],$moneyf,$fShiftMoney*-1,$currency_after,36,6,$insertId,"MG电子游艺额度转换"));
                        if($moneyLogRes){
                            $milliseconds = round(microtime(true) * 1000);
                            $tx_ext_ref = "TX-ID:{$member_account_id}:{$milliseconds}";
                            $tx_category = "TRANSFER";
                            $tx_type = "CREDIT";
                            $tx_amount = $fShiftMoney;
                            $res = createTransactionByAccountId ($access_token, $member_account_id, $tx_ext_ref, $tx_category, "", $tx_type, $tx_amount);
                            if (!$res['success']){
                                mysqli_rollback($dbMasterLink);
                                exit(json_encode( [ 'code'=>'-12','msg'=>'mg上分失败' ] ));
                            }
                            else{
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);
                                $data = [
                                    'mg_balance' => sprintf('%.2f', $res['body'][1]['balance']), // 元模式
                                    'hg_balance' => sprintf('%.2f', $currency_after)
                                ];
                                exit( json_encode( ['code'=>0, 'msg'=>$data ] ) );
                            }
                        }else{
                            mysqli_rollback($dbMasterLink);
                            exit(json_encode( [ 'code'=>'-11','msg'=>'添加用户资金账变失败' ] ));
                        }
                    }else{
                        mysqli_rollback($dbMasterLink);
                        exit(json_encode( [ 'code'=>'-10','msg'=>'添加账变记录失败' ] ));
                    }
                }else{
                    mysqli_rollback($dbMasterLink);
                    exit(json_encode( [ 'code'=>'-9','msg'=>'更新余额失败' ] ));
                }
            }else{
                mysqli_rollback($dbMasterLink);
                exit(json_encode( [ 'code'=>'-8','msg'=>'添加用户资金锁失败' ] ));
            }
        }else{
            mysqli_rollback($dbMasterLink);
            exit(json_encode( [ 'code'=>'-8','msg'=>'添加用户锁失败' ] ));
        }


        break;
    case 'mg2hg':

        // 1.检查mg余额
        $res = getWalletDetails ($access_token, $member_account_id);
        if ( !$res['success'] ) {
            exit( json_encode( [ 'code'=>'-1','msg'=>'MG余额获取失败' ] ) );
        }else{
            $mgGetbalance["balance"] = $res["body"][0]['credit_balance'];
        }

        if (floatval($mgGetbalance["balance"]) < floatval($b)){
            exit(json_encode( [ 'code'=>'-6','msg'=>'MG余额不足~~' ] ));
        }

        $mgGetbalance["balance"] = number_format($mgGetbalance["balance"], 2, '.', ',');

        if ($b > 10000000){
            exit(json_encode( [ 'code'=>'-2','msg'=>'单次下分不能超过一千万，请重新输入！' ] ));
        }

        if(!preg_match("/^[1-9][0-9]*$/",$b)){
            exit(json_encode( [ 'code'=>'-7','msg'=>'转账只支持正整数，请重新输入' ] ));
        }

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
            exit(json_encode( [ 'code'=>'-12','msg'=>'下分失败' ] ));
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
            $data['UserName']=$aUser['UserName'];
            $data['Agents']=$aUser['Agents'];
            $data['World']=$aUser['World'];
            $data['Corprator']=$aUser['Corprator'];
            $data['Super']=$aUser['Super'];
            $data['Admin']=$aUser['Admin'];
            $data['CurType']='RMB';
            $data['Date']=date("Y-m-d H:i:s",time());
            $data['Name']=$aUser['Alias'];
            $data['Waterno']='';
            $data['Phone']=$aUser['Phone'];
            $data['Notes']='即时入账';
            $data['test_flag'] = $aUser['test_flag'];
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
                    exit(json_encode( [ 'code'=>'-17','msg'=>'添加用户锁失败' ] ));
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
                            $moneyLogRes=addAccountRecords(array($userid,$aUser['UserName'],$aUser['test_flag'],$rowMoney['Money'],$fShiftMoney,$rowMoney['Money']+$fShiftMoney,37,6,$insertId,"资金归集"));
                            if($moneyLogRes){
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);
                                $data = [
                                    'mg_balance' => sprintf('%.2f', $res['body'][0]['balance']), // 元模式
                                    'hg_balance' => sprintf('%.2f', $currency_after)
                                ];
                                exit( json_encode( ['code'=>0, 'msg'=>$data ] ) );
                            }else{
                                mysqli_rollback($dbMasterLink);
                                exit(json_encode( [ 'code'=>'-11','msg'=>'添加用户资金账变失败' ] ));
                            }
                        }else{
                            mysqli_rollback($dbMasterLink);
                            exit(json_encode( [ 'code'=>'-10','msg'=>'添加账变记录失败' ] ));
                        }

                    }else{
                        mysqli_rollback($dbMasterLink);
                        exit(json_encode( [ 'code'=>'-9','msg'=>'余额更新失败' ] ));
                    }
                }else{
                    mysqli_rollback($dbMasterLink);
                    exit(json_encode( [ 'code'=>'-8','msg'=>'添加用户资金锁失败' ] ));
                }
            }else{
                mysqli_rollback($dbMasterLink);
                exit(json_encode( [ 'code'=>'-8','msg'=>'添加用户锁失败' ] ));
            }

        }

        break;
    case 'getLaunchGameUrl':
        $game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:1035;
        $res = getLaunchGameUrl ($access_token, $member_account_id, $game_id, $language);
        if (!$res['success']){
//            exit(json_encode( [ 'code'=>'-1','msg'=>'游戏链接获取失败' ] ));
            exit("<script>alert('游戏链接获取失败".json_encode($res)."');window.close();</script>");
        }
        else{
            header("Location:".$res['body']);
        }
        break;
    case 'getDemoLaunchGameUrl':
        $game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:1035;
        $res = getDemoLaunchGameUrl ($access_token, $game_id, $language);
        if (!$res['success']){
            exit(json_encode( [ 'code'=>'-1','msg'=>'游戏链接获取失败' ] ));
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

