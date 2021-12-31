<?php
/**
 * 自定义MW电子API
 *
 *  执行任务 action （默认检查MW账号，或者创建账号）
 *
 *       getGames 获取游戏列表
 *       merchant 代理上信息查询
 *       gameLobby 进入游戏大厅
 *       appGameLobby APP进入游戏
 *       b 获取余额
 *       hg2mw 平台上分到mw
 *       mw2hg mw下分到平台
 */

//error_reporting(E_ALL);
//ini_set('display_errors','On');
define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(__FILE__))))));
include "../include/address.mem.php";
include_once "../include/config.inc.php";
require_once ROOT_DIR.'/common/mw/api.php';

$uid = $_SESSION['Oid']?$_SESSION['Oid']:$_REQUEST['uid'];
$userid = $_SESSION['userid'] ;

// 判断MW电子是否维护（m版）
$pageMark = 'mw';
$aRow = getMaintainDataByCategory($pageMark);
$aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
if ($aRow['state']==1 and in_array(1, $aTerminal)){
    exit(json_encode( ['err' => '-11', 'msg' => 'MW电子维护中，请选择其他游戏'] ) );
}

if(!isset($uid) || $uid == ''){
    exit("<script>alert('您的登录信息已过期，请您重新登录！');window.close();</script>");
}


// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM ".DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
if(!$stmt->affected_rows) {
    exit(json_encode( ['err' => '-1', 'msg' => '您的登录信息已过期，请您重新登录！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();

if ($aUser['test_flag']){
    exit(json_encode( ['err' => '-2', 'msg' => '请使用真实账号登入MW电子'] ) );
//    exit("<script>alert('请登录真实账号登入MW电子');window.close();</script>");
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$b = $score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
if($exchangeFrom == 'hg' && $exchangeTo == 'mw'){
    $action = 'hg2mw';
}

if($exchangeFrom == 'mw' && $exchangeTo == 'hg'){
    $action = 'mw2hg';
}
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2mw' || $action == 'mw2hg'){
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
// 公用获取最新的域名
$domainUrl= getDomainUrl();
$mw_uid = $mw_prefix.$aUser['UserName'];

// 3.检测登录MW会员
$lyExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "mw_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $lyExist = mysqli_num_rows($result);
    if(!$lyExist){

        if($action == 'b'){ // 未创建账号前请求余额接口

            $data = [
                'mw_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            exit( json_encode( ['err'=>0, 'msg'=>$data ] ) );
        }

        // 授权并创建账号（获取登陆接口以及参数）
        $toURL = $domainUrl.'api/oauth?';
        $res = oauth($toURL, 'oauth', $mw_uid, $utoken, '0');
        if ($res['ret']=='0000'){
            $data = [
                'userid' => $aUser['ID'],
                'username' => $mw_uid,
                'password' => $utoken,
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
            $sql = "INSERT INTO `" . DBPREFIX . "mw_member_data` SET $sInsData";
            if (!mysqli_query($dbMasterLink, $sql)) {
                exit(json_encode(['err' => -5, 'msg' => 'MW账号异常，请您稍后重试！'], JSON_UNESCAPED_UNICODE));
            }
            else{
                exit(json_encode(['err' => 200, 'msg' => 'MW账号初始化成功，请您继续转账'], JSON_UNESCAPED_UNICODE));
            }
        }else{
            @error_log(date('Y-m-d H:i:s').'-membernew-'.json_encode($res).PHP_EOL, 3, '/tmp/group/mw_api.log');
            exit(json_encode(['err' => -6, 'msg' => '注册MW账号失败'.json_encode($res)], JSON_UNESCAPED_UNICODE));
        }


    }
}

$result = mysqli_query($dbLink, "SELECT * FROM `" . DBPREFIX . "mw_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
$res = mysqli_fetch_assoc($result);
$member_account_id = json_decode($res['mw_user_info'],true)['body']['id'];

switch ($action){
    case 'getGames': // 获取游戏列表

        $toURL = $domainUrl.'api/gameInfo?';
        $aGames = getGames($toURL,'gameInfo', 0);
        echo json_encode($aGames);

        break;
    case 'merchant': // 代理商信息数据查询

        $toURL = $domainUrl.'api/merchant?';
        $aMerchants = getMerchant($toURL,'merchant');
        echo json_encode($aMerchants);

        break;
    case 'gameLobby':// 授权进入游戏大厅

        // 授权
        $toURL = $domainUrl.'api/oauth?';
        $res = oauth($toURL, 'oauth', $mw_uid, $utoken, '0');
        if ($res['ret']=='0000'){

            $toURL = $domainUrl.$res['interface']; // 进入游戏大厅
//            header("location:$toURL");
            exit(json_encode(['err' => 0, 'msg' => $toURL]));
        }
        else{
            exit(json_encode(['err' => -6, 'msg' => 'MW账号授权失败'.json_encode($res)], JSON_UNESCAPED_UNICODE));
        }

        break;
    case 'appGameLobby':// 授权进入APP游戏大厅

        // 授权
        $toURL = $domainUrl.'api/oauth?';
        $res = oauth($toURL, 'oauth', $mw_uid, $utoken, '2');
        if ($res['ret']=='0000'){

            $toURL = $domainUrl.$res['interface']; // 进入游戏大厅
            header("location:$toURL");
        }
        else{
            exit(json_encode(['err' => -6, 'msg' => 'MW账号授权失败'.json_encode($res)], JSON_UNESCAPED_UNICODE));
        }

        break;
    case 'b':
        $toURL = $domainUrl.'api/userInfo?';
        $res = getWalletDetails($toURL, 'userInfo', $mw_uid, $utoken);
//        print_r($res);die;
        if ($res['ret'] == '0000'){

            $data = [
                'mw_balance' => sprintf('%.2f', $res["userInfo"]['money']),
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            exit( json_encode( ['err'=>0, 'msg'=>$data ] ) );
        }else{
            exit(json_encode( [ 'err'=>'-1','msg'=>'MW余额获取失败'.json_encode($res, JSON_UNESCAPED_UNICODE) ] ));
        }
        break;
    case 'hg2mw':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $b))
            exit(json_encode([ 'err' => -2, 'msg' => '转账金额只支持正整数，请重新输入' ]));

        if ($b > 10000000){
            exit(json_encode([ 'err' => -7, 'msg'=>'单次上分不能超过一千万，请重新输入！' ]));
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']-$fShiftMoney; // 用户账变后的余额
        //mw生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $sTrans_no = 'MWIN' . $sTime8 . $sUser6; // 订单号生成规则

        $data['userid']= $userid ;
        $data['Checked']=1;
        $data['reason']='hg to mw';
        $data['AuditDate']=date("Y-m-d H:i:s");
        $data['Gold']=$fShiftMoney;
        $data['moneyf']=$moneyf;
        $data['currency_after']=$currency_after;
        $data['AddDate']=date("Y-m-d",time());
        $data['Type']='Q';
        $data['From']='hg';
        $data['To']='mw';
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
                        $moneyLogRes=addAccountRecords(array($userid,$_SESSION['UserName'],$_SESSION['test_flag'],$moneyf,$fShiftMoney*-1,$currency_after,44,1,$insertId,"MW电子游艺额度转换"));
                        if($moneyLogRes){
                            $toURL = $domainUrl.'api/transferPrepare?';
                            $inOrOut = 0; // 0:从体育转入MW  1:从MW转出到体育
                            $transferOrderNo = $sTrans_no;
                            $transferOrderTime = date('Y-m-d H:i:s',time()+12*60*60);
                            $transferClientIp = get_ip();
                            // 货币转入准备
                            $res = transferPrepare ($toURL, 'transferPrepare', $mw_uid, $utoken, $inOrOut, $fShiftMoney, $transferOrderNo, $transferOrderTime, $transferClientIp);
                            if ($res['ret']!='0000'){
                                mysqli_rollback($dbMasterLink);
                                exit(json_encode( [ 'err'=>'-12','msg'=>'mw上分货币转入准备失败-'.$res['msg'].'-'.$transferOrderTime ] ));
                            }
                            else{
                                $toURL = $domainUrl.'api/transferPay?';
                                $asinTransferOrderNo = $res['asinTransferOrderNo'];
                                $asinTransferOrderTime = $res['asinTransferDate'];
                                //货币转入确认
                                $res = transferPay ($toURL, 'transferPay', $mw_uid, $utoken, $asinTransferOrderNo, $asinTransferOrderTime, $transferOrderNo, $fShiftMoney, $transferClientIp);
                                if ($res['ret']!='0000'){
                                    mysqli_rollback($dbMasterLink);
                                    exit(json_encode( [ 'err'=>'-13','msg'=>'mw上分货币转入确认失败-'.$res['msg'] ] ));
                                }else{
                                    mysqli_commit($dbMasterLink);
                                    mysqli_close($dbMasterLink);
                                    $data = [
                                        'mw_balance' => 0.00,
                                        'hg_balance' => formatMoney($currency_after)
                                    ];
                                    $toURL = $domainUrl.'api/userInfo?';
                                    $res = getWalletDetails($toURL, 'userInfo', $mw_uid, $utoken);
                                    if ( $res['ret'] == '0000' ) {
                                        $data["mw_balance"] = $res["userInfo"]['money'];
                                    }
                                    exit( json_encode( ['err'=>0, 'msg'=>$data ] ) );
                                }
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
    case 'mw2hg':

        // 1.检查mw余额
        $toURL = $domainUrl.'api/userInfo?';
        $res = getWalletDetails($toURL, 'userInfo', $mw_uid, $utoken);
        if ( $res['ret'] == '0000' ) {
            $mwGetbalance["balance"] = $res["userInfo"]['money'];
        }else{
            exit(json_encode( [ 'err'=>'-1','msg'=>'MW余额获取失败'.json_encode($res, JSON_UNESCAPED_UNICODE) ] ));
        }

        if (floatval($mwGetbalance["balance"]) < floatval($b)){
            exit(json_encode( [ 'err'=>'-6','msg'=>'MW余额不足~~' ] ));
        }

        $mwGetbalance["balance"] = number_format($mwGetbalance["balance"], 2, '.', ',');

        if ($b > 10000000){
            exit(json_encode( [ 'err'=>'-2','msg'=>'单次下分不能超过一千万，请重新输入！' ] ));
        }

        if(!preg_match("/^[1-9][0-9]*$/",$b)){
            exit(json_encode( [ 'err'=>'-7','msg'=>'转账只支持正整数，请重新输入' ] ));
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']+$fShiftMoney; // 用户账变后的余额
        //MW生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $sTrans_no = 'MWOUT' . $sTime8 . $sUser6;

        // 下分
        $toURL = $domainUrl.'api/transferPrepare?';
        $inOrOut = 1; // 0:从体育转入MW  1:从MW转出到体育
        $transferOrderNo = $sTrans_no;
        $transferOrderTime = date('Y-m-d H:i:s',time()+12*60*60);
        $transferClientIp = get_ip();
        // 货币转出准备
        $res = transferPrepare ($toURL, 'transferPrepare', $mw_uid, $utoken, $inOrOut, $fShiftMoney, $transferOrderNo, $transferOrderTime, $transferClientIp);
        if ($res['ret']!='0000'){
            mysqli_rollback($dbMasterLink);
            exit(json_encode( [ 'err'=>'-12','msg'=>'mw下分货币转出准备失败-'.$res['msg'].'-'.$transferOrderTime ] ));
        }
        else{
            $toURL = $domainUrl.'api/transferPay?';
            $asinTransferOrderNo = $res['asinTransferOrderNo'];
            $asinTransferOrderTime = $res['asinTransferDate'];
            //货币转出确认
            $res = transferPay ($toURL, 'transferPay', $mw_uid, $utoken, $asinTransferOrderNo, $asinTransferOrderTime, $transferOrderNo, $fShiftMoney, $transferClientIp);
            if ($res['ret']!='0000'){
                mysqli_rollback($dbMasterLink);
                exit(json_encode( [ 'err'=>'-13','msg'=>'mw下分货币转出确认失败-'.$res['msg'] ] ));
            }
            else{
                $data['userid']= $userid ;
                $data['Checked']=1;
                $data['reason']='mw to hg';
                $data['AuditDate']=date("Y-m-d H:i:s");
                $data['Gold']=$fShiftMoney;
                $data['moneyf']=$moneyf;
                $data['currency_after']=$currency_after;
                $data['AddDate']=date("Y-m-d",time());
                $data['Type']='Q';
                $data['From']='mw';
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
                                $moneyLogRes=addAccountRecords(array($userid,$_SESSION['UserName'],$_SESSION['test_flag'],$rowMoney['Money'],$fShiftMoney,$rowMoney['Money']+$fShiftMoney,45,1,$insertId,"MW电子游艺额度转换"));
                                if($moneyLogRes){
                                    mysqli_commit($dbMasterLink);
                                    mysqli_close($dbMasterLink);
                                    $data = [
                                        'mw_balance' => 0.00,
                                        'hg_balance' => formatMoney($currency_after)
                                    ];
                                    $toURL = $domainUrl.'api/userInfo?';
                                    $res = getWalletDetails($toURL, 'userInfo', $mw_uid, $utoken);
                                    if ( $res['ret'] == '0000' ) {
                                        $data["mw_balance"] = $res["userInfo"]['money'];
                                    }
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

        }

        break;
    default:
        $status = '401.26';
        $describe = '抱歉，您的请求不予处理！';
        original_phone_request_response($status,$describe);
        break;
}
