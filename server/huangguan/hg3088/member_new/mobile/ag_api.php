<?php
session_start();
/**
 * AG真人接口
 *   检查或者注册AG账号（每次请求时）
 *   查询真人余额 action = b
 *   预备转账
 *   额度转换
 *   查询订单状态
 *
 */
include_once('include/config.inc.php');

$langx=$_SESSION['Language'];
require_once("../../common/ag/config.php");
require "include/agproxy.php";
include "include/model/aggame.php";

$cpDefault = $database['cpDefault'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '401.1';
    $describe = '你的登录信息已过期，请重新登录!';
    original_phone_request_response($status,$describe);
}
$uid=$_SESSION['Oid'];
$action = $_REQUEST['action'];
$f=$_REQUEST['f'];
$t=$_REQUEST['t'];
$b=$_REQUEST['b'];
if ($f=='hg'&&$t=='ag'){
    $action='hg2ag';
}
if ($f=='ag'&&$t=='hg'){
    $action='ag2hg';
}
$userid = $_SESSION['userid'] ;
$sql = "select `Money`,`layer` from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status<=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$aUser = $row;
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2ag' || $action == 'ag2hg'){
    // 检查当前会员是否设置不准操作额度分层
    // 检查分层是否开启 status 1 开启 0 关闭
    // layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金 5 仅限可以投注体育，不能额度转换去其它馆
    if ($sUserlayer==3 || $sUserlayer==5){
        $layer = getUserLayerById($sUserlayer);
        if ($layer['status']==1) {
            $status = '401.66';
            $describe = '账号分层异常，请联系我们在线客服';
            original_phone_request_response($status,$describe);
        }
    }
}
/*$sql = "select `Prefix` from `".DBPREFIX."web_agents_data` where `UserName`='{$_SESSION['Agents']}' ";
$result = mysqli_query($dbLink,$sql);
$cou = mysqli_num_rows($result);
if ($cou==0){
    exit(json_encode( [ 'err'=>'-11','msg'=>'代理错误' ] ) );
}
$row = mysqli_fetch_assoc($result);
$aAgent=$row;
$sPrefix = $aAgent['Prefix'];*/
$sPrefix = $agsxInitp['data_api_cagent']; // 新建账号增加AG代理前缀
$userPrefix = $agsxInitp['data_api_user_prefix'];// 新建账号增加AG用户前缀
$domain_url = $agsxInit['domain_url'];
$api_url = $agsxInit['api_url'];
$game_api_url = $agsxInit['game_api_url'];
$cagent = $agsxInit['cagent'];
$md5_key = $agsxInit['md5_key'];
$des_key = $agsxInit['des_key'];
$testers = $agsxInit['tester'];
$cny = $agsxInit['cur'];
$oAg = new agproxy($domain_url, $api_url, $game_api_url, $cagent, $md5_key, $des_key, $testers, $cny);

//判断终端类型
if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
    $playSource=$_REQUEST['appRefer'];
}
else{
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $playSource=3;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $playSource=4;
    }else{
        $playSource=5;
    }
}


if( $action != 'cga' ){
    $ag_sql = "select username,password,is_test from `".DBPREFIX."ag_users` where `userid` = '{$userid}'";
    $ag_result = mysqli_query($dbLink, $ag_sql);
    $ag_cou = mysqli_num_rows($ag_result);
    if ($ag_cou == 0) {
//            $status = '401.2';
//            $describe = 'AG账号异常~~';
//            original_phone_request_response($status,$describe);

        //检查或者创建AG账号
        $length = rand(5,20);
        $ag_pwd = $oAg->make_char($length);//AG创建的时候由于要传密码.
        $prefix_username = $sPrefix.$userPrefix.'_'.$_SESSION['UserName'];

        if (AG_TRANSFER_SWITCH === TRUE) {
            $url = AG_TRANSFER_URL.'?action=cga&username='.$prefix_username.'&password='.$ag_pwd.'&is_test='.$_SESSION['test_flag'];
            $res = file_get_contents($url);
            $res = json_decode($res,true);
        }
        else{
            if($_SESSION['test_flag'] == 1){ // AG测试账号
                $res = $oAg->ag_checkOrCreateGameAccount($prefix_username, $ag_pwd, 1);
            }else{ // AG正式账号
                $res = $oAg->ag_checkOrCreateGameAccount($prefix_username, $ag_pwd);
            }
        }

        if($res['info'] == 0 && $res='info'!='error' ){

            $data['userid'] = $userid ;
            $data['username'] = $prefix_username;
            $data['Agents'] = $_SESSION['Agents'];
            $data['World'] = $_SESSION['World'];
            $data['Corprator'] = $_SESSION['Corprator'];
            $data['Super'] = $_SESSION['Super'];
            $data['Admin'] = $_SESSION['Admin'];
            $data['password'] = $ag_pwd;
            $data['register_time'] = date('Y-m-d H:i:s');
            $data['last_launch_time'] = date('Y-m-d H:i:s');
            $data['launch_number'] = 1;
//            if ( $pam_username==$testers){ // AG测试账号
//                $data['is_test'] = 1;
//            }else{ // AG正式账号
//                $data['is_test'] = 0;
//            }
            $data['is_test'] = $_SESSION['test_flag'];

            $sInsData = '';
            foreach ($data as $key => $value){
                if ($key=='is_test') {
                    $sInsData.= "`$key` = '{$value}'";
                }else{
                    $sInsData.= "`$key` = '{$value}',";
                }
            }
            $sql = "insert into `".DBPREFIX."ag_users` set $sInsData";
            $in = mysqli_query($dbMasterLink,$sql);
            if (!$in){
//                exit("AG账号入库异常");
                $status = '500.1';
                $describe = 'AG账号入库异常';
                original_phone_request_response($status,$describe);
            }

            if ($action == 'b'){

                $balance_cp = getCpMoney();

                $status = '200';
                $describe = '获取余额成功';
                $data = array(
                    'balance_ag'=>0,
                    'balance_hg'=>formatMoney($row['Money']),
                    'balance_cp'=>floor($balance_cp),
                );
                original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
            }
        }else{
            $status = '500.2';
            $describe = $res['msg'];
            original_phone_request_response($status,$describe);

        }
    }
    $ag_row = mysqli_fetch_assoc($ag_result);
}
switch ($action){
//    case 'cga': // 创建AG账号
//        $length = rand(1,20);
//        $ag_pwd = $oAg->make_char($length);//AG创建的时候由于要传密码.
//        $prefix_username = $sPrefix.$userPrefix.'_'.$_SESSION['UserName'];
//        if($_SESSION['test_flag'] == 1){ // AG测试账号k
//            $res = $oAg->ag_checkOrCreateGameAccount($prefix_username, $ag_pwd, 1);
//        }else{ // AG正式账号
//            $res = $oAg->ag_checkOrCreateGameAccount($prefix_username, $ag_pwd);
//        }
//
//        if($res['info'] == 0 && $res='info'!='error' ){
//
//            $data['userid'] = $userid;
//            $data['username'] = $prefix_username;
//            $data['Agents'] = $_SESSION['Agents'];
//            $data['World'] = $_SESSION['World'];
//            $data['Corprator'] = $_SESSION['Corprator'];
//            $data['Super'] = $_SESSION['Super'];
//            $data['Admin'] = $_SESSION['Admin'];
//            $data['password'] = $ag_pwd;
//            $data['register_time'] = date('Y-m-d H:i:s');
//            $data['last_launch_time'] = date('Y-m-d H:i:s');
//            $data['launch_number'] = 1;
////            if ( $pam_username==$testers){ // AG测试账号
////                $data['is_test'] = 1;
////            }else{ // AG正式账号
////                $data['is_test'] = 0;
////            }
//            $data['is_test'] = $_SESSION['test_flag'];
//
//            $sInsData = '';
//            foreach ($data as $key => $value){
//                if ($key=='is_test') {
//                    $sInsData.= "`$key` = '{$value}'";
//                }else{
//                    $sInsData.= "`$key` = '{$value}',";
//                }
//            }
//            $sql = "insert into `".DBPREFIX."ag_users` set $sInsData";
//            $in = mysqli_query($dbMasterLink,$sql);
//            if (!$in){
//                    $status = '500.1';
//                    $describe = 'AG账号入库异常';
//                    original_phone_request_response($status,$describe);
//
//            }
//                $status = '200';
//                $describe = 'AG帐号创建成功';
//                $data = $res;
//                original_phone_request_response($status,$describe,$data);
//
//
//        }else{
//                $status = '401.4';
//                $describe = $res['msg'];
//                original_phone_request_response($status,$describe);
//        }
//
//        break;
//    case 'check_game_account': //检查AG账号
//
//        $res = $oAg->ag_checkOrCreateGameAccount($ag_row['username'], $ag_row['password'], $ag_row['is_test']);
//
//            $status = '200';
//            $describe = 'success';
//            $data = $res;
//            original_phone_request_response($status,$describe,$data);
//
//        break;
    case 'b': // 获取余额

        if (AG_TRANSFER_SWITCH === TRUE) {
            $url = AG_TRANSFER_URL.'?action=b&username='.$ag_row['username'].'&password='.$ag_row['password'].'&is_test='.$ag_row['is_test'];
            $agGetbalance = file_get_contents($url);
            $agGetbalance = json_decode($agGetbalance, true);
        }
        else{
            $agGetbalance = $oAg->ag_getBalance($ag_row['username'], $ag_row['password'], $ag_row['is_test']);
        }
        if ( $agGetbalance['info'] != 'error' ) {
            $agGetbalance["balance"] = $agGetbalance["info"];
            $balance_ag = $agGetbalance["balance"];
        } else {
            $agGetbalance["balance"] = -1;
        }

        if ($agGetbalance["balance"] == -1){
            $balance_ag = 'AG余额获取失败';
        }

        $balance_cp = getCpMoney();

        $status = '200';
        $describe = '获取余额成功';
        $data = array(
            'balance_ag'=>floor($balance_ag),
            'balance_hg'=>formatMoney($row['Money']),
            'balance_cp'=>floor($balance_cp),
        );
        original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);


        break;
    case 'hg2ag': // 体育转账到AG真人，首先更新本地资金，然后调用AG转账接口

        if (intval($aUser['Money']) < intval($b)){
            $status = '401.6';
            $describe = '中心钱包不足~~';
            original_phone_request_response($status,$describe);
        }

        /*if ($b < 1 or $b > 50000){
            exit(json_encode( [ 'err'=>'-2','msg'=>'转账金额有误，请重新输入' ] ));
        }*/

        if(!preg_match("/^[1-9][0-9]*$/",$b)){
            $status = '401.7';
            $describe = '转账金额只支持正整数，请重新输入';
            original_phone_request_response($status,$describe);
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']-$fShiftMoney; // 用户账变后的余额
        //ag生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $sTrans_no = 'AGIN' . $sTime8 . $sUser6; //AG平台 订单号生成规则

        $data['userid']=$userid;
        $data['Checked']=1;
        $data['reason']='hg to ag';
        $data['AuditDate']=date("Y-m-d H:i:s");
        $data['Gold']=$fShiftMoney;
        $data['moneyf']=$moneyf;
        $data['currency_after']=$currency_after;
        $data['AddDate']=date("Y-m-d",time());
        $data['Type']='Q';
        $data['From']='hg';
        $data['To']='ag';
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

                $rowMoney = mysqli_fetch_assoc($lockMoney);
                if ($rowMoney['Money'] < $b){
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    exit(json_encode( [ 'err'=>'-6','msg'=>'中心钱包不足~~' ] ));
                }

                // 更新玩家账户余额
                $up = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money-$fShiftMoney, Online=1 , OnlineTime=now() WHERE ID=".$userid);
                if($up){
                    //校验通过开始处理订单
                    $in = mysqli_query($dbMasterLink,"insert into `".DBPREFIX."web_sys800_data` set $sInsData");
                    if($in){
                        $insertId=mysqli_insert_id($dbMasterLink);

                        if (AG_TRANSFER_SWITCH === TRUE){
                            $url = AG_TRANSFER_URL.'?action=hg2ag&username='.$ag_row['username'].'&password='.$ag_row['password'].'&transNo='.$sTrans_no.'&shiftMoney='.$fShiftMoney;
                            $sResult = file_get_contents($url);
                            $aResult = json_decode($sResult, true);
                        }
                        else{
                            $aResult = $oAg->player_deposit($ag_row['username'],$ag_row['password'], $sTrans_no,$fShiftMoney);
                        }
                        if ($aResult['code']==1){
                            //添加会员账变日志
                            $moneyLogRes=addAccountRecords(array($userid,$_SESSION['UserName'],$_SESSION['test_flag'],$rowMoney['Money'],$fShiftMoney*-1,$rowMoney['Money']-$fShiftMoney,23,$playSource,$insertId,"真人娱乐/电子游艺额度转换"));
                            if($moneyLogRes){
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);

                                $status = '200';
                                $describe = '转账成功';
                                original_phone_request_response($status, $describe);
                            }else{
                                mysqli_rollback($dbMasterLink);
                                $status = '500.2';
                                $describe = '添加用户资金账变失败';
                                original_phone_request_response($status,$describe);
                            }
                        }
                        else{
                            if (isset($aResult['code'])) {
                                if ($aResult['code'] == '-1.02') { // 预备转账失败
                                    $aErrData['code'] = $aResult['code'];
                                    error_log(date('Y-m-d H:i:s').'-'.serialize($aErrData).PHP_EOL, 3, '/tmp/AG_ERROR.log');
                                    mysqli_rollback($dbMasterLink);
                                    $status = '401.8';
                                    $describe = '预备转账失败';
                                    original_phone_request_response($status,$describe);

                                } elseif ($aResult['code'] == '-998') { //确认转帐失败，先不回滚，待确认。人工处理
                                    $oAgGame = new ag_game($dbMasterLink);
                                    $third_err = $oAgGame->third_deposit_or_withdraw_error_in($ag_row['username'], $sTrans_no, $fShiftMoney);
                                    mysqli_rollback($dbMasterLink);
                                    $status = '401.9';
                                    $describe = '确认转账失败，请与客服联系';
                                    original_phone_request_response($status,$describe);
                                }
                                else{
                                    mysqli_rollback($dbMasterLink);
                                    $status = '401.10';
                                    $describe = '确认转账失败，请与客服联系';
                                    original_phone_request_response($status,$describe);
                                }
                            }
                            else{
                                mysqli_rollback($dbMasterLink);
                                $status = '401.11';
                                $describe = '确认转账失败，请与客服联系';
                                original_phone_request_response($status,$describe);
                            }
                        }

                    }else{
                        mysqli_rollback($dbMasterLink);
                        $status = '500.3';
                        $describe = '添加账变记录失败';
                        original_phone_request_response($status,$describe);

                    }
                }else{
                    mysqli_rollback($dbMasterLink);
                    $status = '500.4';
                    $describe = '更新余额失败';
                    original_phone_request_response($status,$describe);

                }
            }else{
                mysqli_rollback($dbMasterLink);
                $status = '500.5';
                $describe = '添加用户资金锁失败';
                original_phone_request_response($status,$describe);

            }
        }else{
            mysqli_rollback($dbMasterLink);
            $status = '500.6';
            $describe = '添加用户锁失败';
            original_phone_request_response($status, $describe);

        }

        break;
    case 'ag2hg': // 从AG真人转到体育。首先调用接口，然后成功后再更新本地资金

        if (AG_TRANSFER_SWITCH === TRUE){
            $url = AG_TRANSFER_URL.'?action=b&username='.$ag_row['username'].'&password='.$ag_row['password'].'&is_test='.$ag_row['is_test'];
            $agGetbalance = file_get_contents($url);
            $agGetbalance = json_decode($agGetbalance, true);
        }
        else{
            $agGetbalance = $oAg->ag_getBalance($ag_row['username'], $ag_row['password'], $ag_row['is_test']);
        }

        if ( $agGetbalance['info'] != 'error' ) {
            $agGetbalance["balance"] = $agGetbalance["info"];
        } else {
            $status = '401.15';
            $describe = 'AG余额获取失败';
            original_phone_request_response($status, $describe);

        }

        if (floatval($agGetbalance["balance"]) < floatval($b)){
            $status = '401.16';
            $describe = 'AG余额不足~~';
            original_phone_request_response($status, $describe);
        }


        /*if ($b < 1 or $b > 50000){
            exit(json_encode( [ 'err'=>'-2','msg'=>'转账金额有误，请重新输入' ] ));
        }*/

        if(!preg_match("/^[1-9][0-9]*$/",$b)){
            $status = '401.17';
            $describe = '转账只支持正整数，请重新输入';
            original_phone_request_response($status, $describe);
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']+$fShiftMoney; // 用户账变后的余额
        //ag生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $sTrans_no = 'AGOUT' . $sTime8 . $sUser6; //AG平台 订单号生成规则

        if (AG_TRANSFER_SWITCH === TRUE){
            $url = AG_TRANSFER_URL.'?action=ag2hg&username='.$ag_row['username'].'&password='.$ag_row['password'].'&transNo='.$sTrans_no.'&shiftMoney='.$fShiftMoney;
            $sResult = file_get_contents($url);
            $aResult = json_decode($sResult, true);
        }
        else{
            $aResult = $oAg->player_withdraw($ag_row['username'],$ag_row['password'], $sTrans_no,$fShiftMoney);
        }

        if (isset($aResult['code'])) {

            if ($aResult['code'] == '-1.02') { // 预备转账失败

                $aErrData['code'] = $aResult['code'];
                $aErrData[''] = 'AGOUT';
                error_log(date('Y-m-d H:i:s').'-'.serialize($aErrData).PHP_EOL, 3, '/tmp/AG_ERROR.log');
                $status = '401.18';
                $describe = '预备转账失败';
                original_phone_request_response($status, $describe);

            } elseif ($aResult['code'] == '-998') { //确认转帐失败，先不回滚，待确认。人工处理

                $oAgGame = new ag_game($dbMasterLink);
                $third_err = $oAgGame->third_deposit_or_withdraw_error_in($ag_row['username'], $sTrans_no, $fShiftMoney);
                $status = '401.19';
                $describe = '确认转账失败，请与客服联系';
                original_phone_request_response($status, $describe);
            }
        }

        $data['userid']=$userid;
        $data['Checked']=1;
        $data['reason']='ag to hg';
        $data['AuditDate']=date("Y-m-d H:i:s");
        $data['Gold']=$fShiftMoney;
        $data['moneyf']=$moneyf;
        $data['currency_after']=$currency_after;
        $data['AddDate']=date("Y-m-d",time());
        $data['Type']='Q';
        $data['From']='ag';
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
                $up = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money+$fShiftMoney , Online=1 , OnlineTime=now() WHERE ID=".$userid);
                if($up){
                    //校验通过开始处理订单
                    $in = mysqli_query($dbMasterLink,"insert into `".DBPREFIX."web_sys800_data` set $sInsData");
                    if($in){
                        //添加会员账变日志
                        $insertId=mysqli_insert_id($dbMasterLink);
                        $rowMoney=mysqli_fetch_assoc($lockMoney);
                        $moneyLogRes=addAccountRecords(array($userid,$_SESSION['UserName'],$_SESSION['test_flag'],$rowMoney['Money'],$fShiftMoney,$rowMoney['Money']+$fShiftMoney,24,$playSource,$insertId,"真人娱乐/电子游艺额度转换"));
                        if($moneyLogRes){
                            mysqli_commit($dbMasterLink);
                            mysqli_close($dbMasterLink);
                        }else{
                            mysqli_rollback($dbMasterLink);
                            $status = '500.7';
                            $describe = '添加用户资金账变失败';
                            original_phone_request_response($status, $describe);
                        }
                    }else{
                        mysqli_rollback($dbMasterLink);
                        $status = '500.8';
                        $describe = '添加账变记录失败';
                        original_phone_request_response($status, $describe);
                    }

                }else{
                    mysqli_rollback($dbMasterLink);
                    $status = '500.9';
                    $describe = '余额更新失败';
                    original_phone_request_response($status, $describe);
                }
            }else{
                mysqli_rollback($dbMasterLink);
                $status = '500.10';
                $describe = '添加用户资金锁失败';
                original_phone_request_response($status, $describe);
            }
        }else{
            mysqli_rollback($dbMasterLink);
            $status = '500.11';
            $describe = '添加用户锁失败';
            original_phone_request_response($status, $describe);
        }

        $status = '200';
        $describe = '转账成功，请查看余额';
        original_phone_request_response($status, $describe);

        break;
    case 'gamelist_dianzi':

        foreach ($agXinGames as $k => $v){
            $agGameList[$k]['gameid'] = $v['gameTypeM'];
            $agGameList[$k]['name'] = $v['name'];
            $agGameList[$k]['gameurl'] = '/images/aggame/'.$v['gameurl'];
        }
        $aData= $agGameList;

        $status = '200';
        $describe = 'success';
        original_phone_request_response($status, $describe,$aData);

        break;
    case 'gamelist_zhenren':
        $aData = array(
            array(
                'name'=>'百家乐',
                'gameurl'=>'/images/live/girl1.png',
            ),
            array(
                'name'=>'龙虎斗',
                'gameurl'=>'/images/live/girl2.png',
            ),
            array(
                'name'=>'轮盘',
                'gameurl'=>'/images/live/girl3.png',
            ),
            array(
                'name'=>'骰子',
                'gameurl'=>'/images/live/girl4.png',
            ),
        );

        $status = '200';
        $describe = 'success';
        original_phone_request_response($status, $describe,$aData);
        break;
}

//原生接口获取（AG余额、HG余额、CP余额）
function getCpMoney(){
    global $cpDefault,$userid;

    $cpMasterDbLink = @mysqli_connect($cpDefault['host'],$cpDefault['user'],$cpDefault['password'],$cpDefault['dbname'],$cpDefault['port']) or die("mysqli connect error".mysqli_connect_error()) ;
    $cpsql = "select lcurrency from ".$cpDefault['prefix']."user where hguid=".$userid;
//            echo $cpsql; die;
    $cpresult = mysqli_query($cpMasterDbLink,$cpsql);
    if ($cpresult){
        $cprow = mysqli_fetch_assoc($cpresult);
        $cpFund = $cprow['lcurrency'];
        $balance_cp = $cpFund;
    }else{
        $cpFund = -1;
    }

    if ($cpFund == -1){
        $balance_cp = 'CP余额获取失败';
    }
    return $balance_cp;
}