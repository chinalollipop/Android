<?php
/**
 * 自定义OG电子API
 *
 *  执行任务 action （默认检查OG账号，或者创建账号）
 *       b  获取余额
 *       hg2og  平台上分到og
 *       og2hg  og下分到平台
 *       getLaunchGameUrl  真钱模式
 */

//error_reporting(E_ALL);
//ini_set('display_errors','On');
define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
include_once "../../include/config.inc.php";
require_once ROOT_DIR . '/common/og/api.php';

$uid = $_SESSION['Oid']?$_SESSION['Oid']:$_REQUEST['uid'];
if( !$uid || $uid == "" ) {
    exit(json_encode( ['err' => '-2', 'msg' => '您的登录信息已过期，请您重新登录！'] ) );
}
$userid = $_SESSION['userid'] ;

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone`, `birthday` FROM ".DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
if(!$stmt->affected_rows) {
    exit(json_encode( ['err' => '-1', 'msg' => '您的登录信息已过期，请您重新登录！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();

if ($aUser['test_flag']){
    exit(json_encode( ['err' => '-11', 'msg' => '请登录真实账号登入OG视讯！'] ) );
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$b = $score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
if($exchangeFrom == 'hg' && $exchangeTo == 'og'){
    $action = 'hg2og';
}

if($exchangeFrom == 'og' && $exchangeTo == 'hg'){
    $action = 'og2hg';
}
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2og' || $action == 'og2hg'){
    // 检查当前会员是否设置不准操作额度分层
    // 检查分层是否开启 status 1 开启 0 关闭
    // layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金 5 仅限可以投注体育，不能额度转换去其它馆
    if ($sUserlayer==3 || $sUserlayer==5){
        $layer = getUserLayerById($sUserlayer);
        if ($layer['status']==1) {
            exit(json_encode( [ 'err'=>'-66','msg'=>'账号分层异常，请联系我们在线客服' ] ));
        }
    }
}

// 首先从redis读取token
// 为空则getToken，保存token到redis，保存30分钟
/**
 *
主要API讲解：
1. 登入: 在采取任何动作前，您需要有一个有效的Token。在确认您的身份后，才能使用其他的API窗口。辅助说明：
token的有效时限为30分钟。

2. 重刷Token: 为了方便使用，我们鼓励用户们存取并在有效时限内，重复使用同一个Token，这样的大量的减少用户们对我们的登入窗口过度的依赖。
   如以上，每个Token的有效时限为30分钟，用户们只需要在每个Token过期前重刷一个新的Token来替代就行了。
 */

$redisObj = new Ciredis();
$resp = $redisObj->getSimpleOne('og_access_token_refresh_token');
$resp = json_decode($resp,true);
if ($resp['success']){

    $resp_body = $resp["body"];
    $access_token = $resp_body["token"];

    // 超过30分钟则重新getToken
    $half_hour = 30*60;
    if ( (time()-strtotime($resp_body['token_last_update_time'])) > $half_hour){
        // redis倒计时剩余1分钟时，进行refresh
        $access_token = og_do_login_redis_token();
    }
}else{
    $access_token = og_do_login_redis_token();
}

function og_do_login_redis_token(){
    global $redisObj;
    $resp = getToken ();

    if ($resp['success']){
        $resp_body = $resp["body"];
        $access_token = $resp_body["token"];
        $resp['body']['token_last_update_time'] = date('Y-m-d H:i:s');
        $redisObj->setOne('og_access_token_refresh_token',json_encode($resp));// 设置redis
        return $access_token;
    }else{
        exit(json_encode(['err' => -4, 'msg' => 'getToken失败'.json_encode($resp)]));
    }
}


// 3.检测登录OG会员
$lyExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "og_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $lyExist = mysqli_num_rows($result);
    if(!$lyExist){

        if($action == 'b'){ // 未创建账号前请求余额接口

            exit(json_encode(['err' => 0, 'balance_og' => '0.00']));
        }else{

            $ogUsername=$og_prefix.$aUser['UserName'];
            $country='China';
            $email=$x_operator.'@'.$x_operator.'.com'; // mog225hg@mog225hg.com
            $language='cn';
            $birthdate='1992-02-18';
            $res = createMember($access_token, $ogUsername, $country, $email, $language, $birthdate);
            if ($res['success']){
                $data = [
                    'userid' => $aUser['ID'],
                    'username' => $ogUsername,
                    'agents' => $aUser['Agents'],
                    'world' => $aUser['World'],
                    'corporator' => $aUser['Corprator'],
                    'super' => $aUser['Super'],
                    'admin' => $aUser['Admin'],
                    'register_time' => $now,
                    'last_launch_time' => $now,
                    'launch_times' => 1,
                    'is_test' => $aUser['test_flag'],
                ];
                $sInsData = '';
                foreach ($data as $key => $value){
                    $sInsData .= "`$key` = '{$value}'" . ($key == 'is_test' ? '' : ',');
                }
                $sql = "INSERT INTO `" . DBPREFIX . "og_member_data` SET $sInsData";
//                echo $sql;die;
                if (!mysqli_query($dbMasterLink, $sql)) {
                    exit(json_encode(['err' => -5, 'msg' => 'OG账号异常，请您稍后重试！']));
                }else{
//                    exit(json_encode(['err' => 222, 'msg' => 'OG账号初始化成功，请您继续转账']));
                    echo '<script>alert("OG账号初始化成功，请您继续转账");location.reload();</script>';
                }
            }else{
                exit(json_encode(['err' => -6, 'msg' => '注册OG账号失败'.json_encode($res)]));
            }
        }
    }
}

$pageMark='og';
$sql = 'SELECT `title`, `state`, `content`, `mark`,`terminal_id` FROM ' . DBPREFIX . 'cms_article WHERE `state` = 1 and mark = "' . $pageMark . '" LIMIT 1';
$oResult = mysqli_query($dbLink, $sql);
$maintenanceData = [];
$aRow = mysqli_fetch_assoc($oResult);
$aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
$maintenanceData[$aRow['mark']] = [
    'title' => $aRow['title'],
    'state' => $aRow['state'] == 1 && in_array(1, $aTerminal) ? 1 : 0,
    'content' => $aRow['content']
];
if(isset($maintenanceData[$pageMark]) && $maintenanceData[$pageMark]['state'] == 1){
    exit(json_encode( ['err' => '-11', 'msg' => '很抱歉，OG视讯游戏临时维护中。您可以进行平台其他游戏！感谢您的耐心等候。'] ) );
}

switch ($action){
    case 'b':
        $ogUsername=$og_prefix.$aUser['UserName'];
        $res = getWalletDetails ($access_token, $ogUsername);
        if (!$res['success']){
            exit(json_encode( [ 'err'=>'-1','msg'=>'余额获取失败'.json_encode($res) ] ));
        }
        else{
            exit( json_encode( ['err'=>0, 'balance_og'=>number_format($res["body"]['balance'],2) ] ) );
        }

        break;
    case 'hg2og':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $b))
            exit(json_encode([ 'err' => -2, 'msg' => '转账金额只支持正整数，请重新输入' ]));

        if ($b > 10000000){
            exit(json_encode([ 'err' => -7, 'msg'=>'单次上分不能超过一千万，请重新输入！' ]));
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']-$fShiftMoney; // 用户账变后的余额
        //og生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $sTrans_no = 'OGIN' . $sTime8 . $sUser6; // 订单号生成规则

        $data['userid']= $userid ;
        $data['Checked']=1;
        $data['reason']='hg to og';
        $data['AuditDate']=date("Y-m-d H:i:s");
        $data['Gold']=$fShiftMoney;
        $data['moneyf']=$moneyf;
        $data['currency_after']=$currency_after;
        $data['AddDate']=date("Y-m-d",time());
        $data['Type']='Q';
        $data['From']='hg';
        $data['To']='og';
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
                exit(json_encode( [ 'err'=>'-17','msg'=>'添加用户锁失败' ] ));
            }
        }
        $lock = mysqli_query($dbMasterLink, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$userid} for update");
        if($lock){
            $lockMoney = mysqli_query($dbMasterLink, "select Money from `".DBPREFIX.MEMBERTABLE."` where `ID` = {$userid} for update");
            if ($lockMoney){

                $lockMoneyRes = mysqli_fetch_assoc($lockMoney);
                if ($lockMoneyRes['Money'] < $b){
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    exit(json_encode( [ 'err'=>'-6','msg'=>'余额不足~~' ] ));
                }

                // 更新玩家账户余额
                $up = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money-$fShiftMoney, Online=1 , OnlineTime=now() WHERE ID=".$userid);
                if($up){
                    //校验通过开始处理订单
                    $in = mysqli_query($dbMasterLink,"insert into `".DBPREFIX."web_sys800_data` set $sInsData");
                    if($in){
                        $moneyLogRes=addAccountRecords(array($userid,$_SESSION['UserName'],$_SESSION['test_flag'],$moneyf,$fShiftMoney*-1,$currency_after,40,1,$insertId,"OG电子游艺额度转换"));
                        if($moneyLogRes){
                            $milliseconds = round(microtime(true) * 1000);
                            $ogUsername=$og_prefix.$aUser['UserName'];
                            $tx_amount = $fShiftMoney;
                            $res = createTransaction ($access_token, $ogUsername, "IN", $tx_amount, $sTrans_no);
                            if (!$res['success']){
                                mysqli_rollback($dbMasterLink);
                                exit(json_encode( [ 'err'=>'-12','msg'=>'OG上分失败' ] ));
                            }
                            else{
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);
                                $data = [
                                    'og_balance' => sprintf('%.2f', $res['body']['balance']), // 元模式
                                    'hg_balance' => sprintf('%.2f', $currency_after)
                                ];
                                exit( json_encode( ['err'=>0, 'msg'=>$data ] ) );
                            }
                        }else{
                            mysqli_rollback($dbMasterLink);
                            exit(json_encode( [ 'err'=>'-11','msg'=>'添加用户资金账变失败' ] ));
                        }
                    }else{
                        mysqli_rollback($dbMasterLink);
                        exit(json_encode( [ 'err'=>'-10','msg'=>'添加账变记录失败' ] ));
                    }
                }else{
                    mysqli_rollback($dbMasterLink);
                    exit(json_encode( [ 'err'=>'-9','msg'=>'更新余额失败' ] ));
                }
            }else{
                mysqli_rollback($dbMasterLink);
                exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户资金锁失败' ] ));
            }
        }else{
            mysqli_rollback($dbMasterLink);
            exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户锁失败' ] ));
        }


        break;
    case 'og2hg':
        $ogUsername=$og_prefix.$aUser['UserName'];
        // 1.检查og余额
        $res = getWalletDetails ($access_token, $ogUsername);
        if ( !$res['success'] ) {
            exit( json_encode( [ 'err'=>'-1','msg'=>'OG余额获取失败' ] ) );
        }else{
            $ogbalance["balance"] = $res["body"]['balance'];
        }

        if (floatval($ogbalance["balance"]) < floatval($b)){
            exit(json_encode( [ 'err'=>'-6','msg'=>'OG余额不足~~' ] ));
        }

        $ogbalance["balance"] = number_format($ogbalance["balance"], 2, '.', ',');

        if ($b > 10000000){
            exit(json_encode( [ 'err'=>'-2','msg'=>'单次下分不能超过一千万，请重新输入！' ] ));
        }

        if(!preg_match("/^[1-9][0-9]*$/",$b)){
            exit(json_encode( [ 'err'=>'-7','msg'=>'转账只支持正整数，请重新输入' ] ));
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']+$fShiftMoney; // 用户账变后的余额
        //og生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $sTrans_no = 'OGOUT' . $sTime8 . $sUser6;

        // 下分
        $res = createTransaction ($access_token, $ogUsername, 'OUT', $fShiftMoney, $sTrans_no);
        if (!$res['success']){
            mysqli_rollback($dbMasterLink);
            exit(json_encode( [ 'err'=>'-12','msg'=>'下分失败' ] ));
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
            $data['From']='og';
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
                    exit(json_encode( [ 'err'=>'-17','msg'=>'添加用户锁失败' ] ));
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
                            $moneyLogRes=addAccountRecords(array($userid,$_SESSION['UserName'],$_SESSION['test_flag'],$rowMoney['Money'],$fShiftMoney,$rowMoney['Money']+$fShiftMoney,41,1,$insertId,"OG电子游艺额度转换"));
                            if($moneyLogRes){
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);
                                $data = [
                                    'og_balance' => sprintf('%.2f', $res['body']['balance']), // 元模式
                                    'hg_balance' => sprintf('%.2f', $currency_after)
                                ];
                                exit( json_encode( ['err'=>0, 'msg'=>$data ] ) );
                            }else{
                                mysqli_rollback($dbMasterLink);
                                exit(json_encode( [ 'err'=>'-11','msg'=>'添加用户资金账变失败' ] ));
                            }
                        }else{
                            mysqli_rollback($dbMasterLink);
                            exit(json_encode( [ 'err'=>'-10','msg'=>'添加账变记录失败' ] ));
                        }

                    }else{
                        mysqli_rollback($dbMasterLink);
                        exit(json_encode( [ 'err'=>'-9','msg'=>'余额更新失败' ] ));
                    }
                }else{
                    mysqli_rollback($dbMasterLink);
                    exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户资金锁失败' ] ));
                }
            }else{
                mysqli_rollback($dbMasterLink);
                exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户锁失败' ] ));
            }

        }

        break;
    case 'getLaunchGameUrl':

        $ogUsername=$og_prefix.$aUser['UserName'];

        // 1、获得游戏金钥
        $resp = $redisObj->getSimpleOne('og_game_key_'.$ogUsername);
        $resp = json_decode($resp,true);
        if ($resp['success']){

            $resp_body = $resp["body"];
            $game_key = $resp_body["key"];
            // 超过30分钟则重新更新游戏金钥
            $half_hour = 30*60;
            if ( (time()-strtotime($resp_body['game_key_last_update_time'])) > $half_hour){
                // redis倒计时剩余1分钟时，进行refresh
                $game_key = og_game_key_redis_token();
            }
        }else{
            $game_key = og_game_key_redis_token();
        }

        // 2、根据游戏金钥 获得游戏链接
        $type = 'desktop'; // 网页端
        if ($_REQUEST['type']=='mobile'){
            $type=$_REQUEST['type'];
        }

        $res = getLaunchGameUrl ($game_key, $type);
        if (!$res['success']){
//            exit(json_encode( [ 'err'=>'-1','msg'=>'游戏链接获取失败' ] ));
            exit("<script>alert('游戏链接获取失败".json_encode($res)."');window.close();</script>");
        }
        else{
            header("Location:".$res['body']['url']);
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

// 取得游戏金钥
function og_game_key_redis_token(){
    global $redisObj, $access_token, $ogUsername;

    $resp = getGameKey($access_token, $ogUsername);
    if ($resp['success']){
        $resp_body = $resp["body"];
        $game_key = $resp_body["key"];
        $resp['body']['game_key_last_update_time'] = date('Y-m-d H:i:s');
        $redisObj->setOne('og_game_key_'.$ogUsername,json_encode($resp));// 设置redis
        return $game_key;
    }else{
        exit(json_encode(['err' => -4, 'msg' => '取得游戏金钥失败'.json_encode($resp)]));
    }
}