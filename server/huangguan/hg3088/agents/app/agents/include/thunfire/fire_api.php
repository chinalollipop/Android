<?php
/**
 * 自定义雷火电竞API
 *
 *  执行任务 action （默认检查雷火账号，或者创建账号）
 *       b  获取余额
 *       hg2fire  平台上分到fire
 *       fire2hg  fire下分到平台
 *       getLaunchGameUrl  真钱模式
 *       getDemoLaunchGameUrl  试玩模式
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');
define("ROOT_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
include_once "../../include/config.inc.php";
include_once ROOT_DIR. '/common/thunfire/api.php';

$username = isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Oid`, `Money`, `layer`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM ".DBPREFIX.MEMBERTABLE." where `UserName` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
if(!$stmt->affected_rows) {
    exit(json_encode( ['code' => '-1', 'msg'=> '您的登录信息已过期，请您重新登录！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();
$mem_token = $aUser['Oid'];

if ($aUser['test_flag']){
    exit(json_encode( ['code' => '-2', 'msg'=> '请登录真实账号登入雷火电竞'] ) );
//    exit("<script>alert('请进入试玩，或者请登录真实账号登入雷火电竞');window.close();</script>");
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$b = $score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;


// 判断雷火电竞是否维护-单页面维护功能
if($action=='getLaunchGameUrl'){ // 打开游戏，判断维护
    checkMaintain('thunfire');
}

if($exchangeFrom == 'hg' && $exchangeTo == 'fire'){
    $action = 'hg2fire';
}

if($exchangeFrom == 'fire' && $exchangeTo == 'hg'){
    $action = 'fire2hg';
}
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2fire' || $action == 'fire2hg'){
    // 检查当前会员是否设置不准操作额度分层
    // 检查分层是否开启 status 1 开启 0 关闭
    // layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
    $layerId=3;
    if ($sUserlayer==$layerId){
        $layer = getUserLayerById($layerId);
        if ($layer['status']==1) {
            exit(json_encode( [ 'code' =>'-66','msg'=>'账号分层异常，请联系我们在线客服' ] ));
        }
    }
}

// 3.检测登录雷火会员
$tfExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "fire_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $tfExist = mysqli_num_rows($result);
    if(!$tfExist){

        if($action == 'b'){ // 未创建账号前请求余额接口
            $data = [
                'fire_balance' => '0.00',
                'hg_balance' => sprintf('%.2f',$aUser['Money'])
            ];
            exit( json_encode( ['code' =>0, 'data' =>$data ] ) );
        }
        else{
            // 创建账号接口更新
            $aRes = createFireMember ($operator_id, $aUser['UserName'], $mem_token);
            if ($aRes['success'] == true && $aRes['body']['member_code']) {
                $data = [
                    'userid' => $aUser['ID'],
                    'username' => $aRes['body']['member_code'],
                    'channel' => $operator_id,
                    'agents' => $aUser['Agents'],
                    'world' => $aUser['World'],
                    'corporator' => $aUser['Corprator'],
                    'super' => $aUser['Super'],
                    'admin' => $aUser['Admin'],
                    'register_time' => $now,
                    'last_launch_time' => $now,
                    'launch_times' => 1,
                    'token' => $aRes['body']['token'],
                    'is_test' => $aUser['test_flag'],
                ];
                $sInsData = '';
                foreach ($data as $key => $value){
                    $sInsData .= "`$key` = '{$value}'" . ($key == 'is_test' ? '' : ',');
                }
                $sql = "INSERT INTO `" . DBPREFIX . "fire_member_data` SET $sInsData";
                if (!mysqli_query($dbMasterLink, $sql)) {
                    exit(json_encode(['code' => -5, 'msg'=> 'thunFire账号异常，请您稍后重试！'], JSON_UNESCAPED_UNICODE));
                }else{
                    exit( json_encode(['code' => 200, 'msg'=> 'thunFire账号初始化成功' ], JSON_UNESCAPED_UNICODE ) );
//                    exit("<script>alert('thunFire账号初始化成功');window.close();</script>");
                }
            }else{
                exit(json_encode(['code' => -6, 'msg'=> '注册thunFire账号失败'], JSON_UNESCAPED_UNICODE));
            }
        }
    }
}

$userid = $aUser['ID'];
switch ($action){
    case 'b':
        $aRes = getMemberBalance ($aUser['UserName']);
        //$aRes = json_decode($res, true);
        if (!$aRes['success'] || $aRes['body']['count'] !== 1){
            exit(json_encode( [ 'code' =>'-1','msg'=>'余额获取失败' ] ));
        }
        else{
            $data = [
                'fire_balance' => sprintf('%.2f', $aRes['body']['results'][0]['balance']),
                'hg_balance' => sprintf('%.2f',$aUser['Money'])
            ];
            exit( json_encode( ['code' => 0, 'data' =>$data ] ) );
        }

        break;
    case 'hg2fire':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $b))
            exit(json_encode([ 'code' => -2, 'msg'=> '转账金额只支持正整数，请重新输入' ]));

        if ($b > 10000000){
            exit(json_encode([ 'code' => -7, 'msg'=>'单次上分不能超过一千万，请重新输入！' ]));
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']-$fShiftMoney; // 用户账变后的余额
        //avia生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sTrans_no = 'FIREIN' . date('YmdHis') . $sTime8; // 订单号生成规则

        $data['userid']= $userid ;
        $data['Checked']=1;
        $data['reason']='hg to fire';
        $data['AuditDate']=date("Y-m-d H:i:s");
        $data['Gold']=$fShiftMoney;
        $data['moneyf']=$moneyf;
        $data['currency_after']=$currency_after;
        $data['AddDate']=date("Y-m-d",time());
        $data['Type']='Q';
        $data['From']='hg';
        $data['To']='fire';
        $data['UserName']=$aUser['UserName'];
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
                exit(json_encode( [ 'code' =>'-17','msg'=>'添加用户锁失败' ] ));
            }
        }
        $lock = mysqli_query($dbMasterLink, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$userid} for update");
        if($lock){
            $lockMoney = mysqli_query($dbMasterLink, "select Money from `".DBPREFIX.MEMBERTABLE."` where `ID` = {$userid} for update");
            if ($lockMoney){

                $lockMoneyRes = mysqli_fetch_assoc($lockMoney);
                if ($lockMoneyRes['Money'] < $b){
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    exit(json_encode( [ 'code' =>'-6','msg'=>'余额不足~~' ] ));
                }

                // 更新玩家账户余额
                $up = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money-$fShiftMoney, Online=1 , OnlineTime=now() WHERE ID=".$userid);
                if($up){
                    //校验通过开始处理订单
                    $in = mysqli_query($dbMasterLink,"insert into `".DBPREFIX."web_sys800_data` set $sInsData");
                    if($in){
                        $insertId=mysqli_insert_id($dbMasterLink);
                        $moneyLogRes=addAccountRecords(array($userid,$aUser['UserName'],$_SESSION['test_flag'],$moneyf,$fShiftMoney*-1,$currency_after,59,6,$insertId,"额度转换体育到雷火电竞"));
                        if($moneyLogRes){

                            $aRes = depositMember($operator_id, $aUser['UserName'], $fShiftMoney, $sTrans_no);
                            if (!$aRes['success']){
                                mysqli_rollback($dbMasterLink);
                                exit(json_encode( [ 'code' =>'-12','msg'=>'FIRE上分失败' ] ));
                            }
                            else{
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);

                                /*$aRes = getMemberBalance ($aUser['UserName']);
                                //$aRes = json_decode($res, true);
                                if ( !$aRes['success'] ) {
                                    exit( json_encode( [ 'code' =>'-12','msg'=>'FIRE余额获取失败' ] ) );
                                }*/

                                $data = [
                                    'fire_balance' => sprintf('%.2f', $aRes['body']['balance_amount']),
                                    'hg_balance' => sprintf('%.2f', $currency_after)
                                ];
                                exit(json_encode(['code' => 0, 'msg' => $data]));
                            }
                        }else{
                            mysqli_rollback($dbMasterLink);
                            exit(json_encode( [ 'code' =>'-11','msg'=>'添加用户资金账变失败' ] ));
                        }
                    }else{
                        mysqli_rollback($dbMasterLink);
                        exit(json_encode( [ 'code' =>'-10','msg'=>'添加账变记录失败' ] ));
                    }
                }else{
                    mysqli_rollback($dbMasterLink);
                    exit(json_encode( [ 'code' =>'-9','msg'=>'更新余额失败' ] ));
                }
            }else{
                mysqli_rollback($dbMasterLink);
                exit(json_encode( [ 'code' =>'-8','msg'=>'添加用户资金锁失败' ] ));
            }
        }else{
            mysqli_rollback($dbMasterLink);
            exit(json_encode( [ 'code' =>'-8','msg'=>'添加用户锁失败' ] ));
        }


        break;
    case 'fire2hg':

        // 1.检查thunfire余额
        $aRes = getMemberBalance ($aUser['UserName']);
        //$aRes = json_decode($res, true);
        if ( !$aRes['success'] ) {
            exit( json_encode( [ 'code' =>'-1','msg'=>'FIRE余额获取失败' ] ) );
        }else{
            $fireGetbalance["balance"] = sprintf('%.2f', $aRes['body']['results'][0]['balance']);
        }

        if (floatval($fireGetbalance["balance"]) < floatval($b)){
            exit(json_encode( [ 'code' =>'-6','msg'=>'FIRE余额不足~~' ] ));
        }

        if ($b > 10000000){
            exit(json_encode( [ 'code' =>'-2','msg'=>'单次下分不能超过一千万，请重新输入！' ] ));
        }

        if(!preg_match("/^[1-9][0-9]*$/",$b)){
            exit(json_encode( [ 'code' =>'-7','msg'=>'转账只支持正整数，请重新输入' ] ));
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']+$fShiftMoney; // 用户账变后的余额
        //fire生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sTrans_no = 'FIREOUT' . date('YmdHis') . $sTime8; // 订单号生成规则

        // 下分
        $aRes = withdrawMember($operator_id, $aUser['UserName'], $fShiftMoney, $sTrans_no);
        //$aRes = json_decode($res, true);
        if (!$aRes['success']){
            mysqli_rollback($dbMasterLink);
            exit(json_encode( [ 'code' =>'-12','msg'=>'下分失败' ] ));
        }
        else{

            $data['userid']= $userid ;
            $data['Checked']=1;
            $data['reason']='fire to hg';
            $data['AuditDate']=date("Y-m-d H:i:s");
            $data['Gold']=$fShiftMoney;
            $data['moneyf']=$moneyf;
            $data['currency_after']=$currency_after;
            $data['AddDate']=date("Y-m-d",time());
            $data['Type']='Q';
            $data['From']='fire';
            $data['To']='hg';
            $data['UserName']=$aUser['UserName'];
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
                    exit(json_encode( [ 'code' =>'-17','msg'=>'添加用户锁失败' ] ));
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
                            $moneyLogRes=addAccountRecords(array($userid,$aUser['UserName'],$_SESSION['test_flag'],$rowMoney['Money'],$fShiftMoney,$rowMoney['Money']+$fShiftMoney,60,6,$insertId,"额度转换雷火电竞到体育"));
                            if($moneyLogRes){
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);

                                /*$aRes = getMemberBalance ($aUser['UserName']);
                                //$aRes = json_decode($res, true);
                                if ( !$aRes['success'] ) {
                                    exit( json_encode( [ 'code' =>'-12','msg'=>'FIRE余额获取失败' ] ) );
                                }*/
                                $data = [
                                    'fire_balance' => sprintf('%.2f', $aRes['body']['balance_amount']),
                                    'hg_balance' => sprintf('%.2f', $currency_after)
                                ];
                                exit(json_encode(['code' => 0, 'msg' => $data]));
                            }else{
                                mysqli_rollback($dbMasterLink);
                                exit(json_encode( [ 'code' =>'-11','msg'=>'添加用户资金账变失败' ] ));
                            }
                        }else{
                            mysqli_rollback($dbMasterLink);
                            exit(json_encode( [ 'code' =>'-10','msg'=>'添加账变记录失败' ] ));
                        }

                    }else{
                        mysqli_rollback($dbMasterLink);
                        exit(json_encode( [ 'code' =>'-9','msg'=>'余额更新失败' ] ));
                    }
                }else{
                    mysqli_rollback($dbMasterLink);
                    exit(json_encode( [ 'code' =>'-8','msg'=>'添加用户资金锁失败' ] ));
                }
            }else{
                mysqli_rollback($dbMasterLink);
                exit(json_encode( [ 'code' =>'-8','msg'=>'添加用户锁失败' ] ));
            }

        }

        break;
    case 'getLaunchGameUrl':

        // 获取游戏地址,进入大厅
        // https://gc-test.r4espt.com/launch.html?auth=75b7e803aaf542b99d45e235a1ad5050&token=fd92cd49015d8ecbae12ra2
        $resUrl = getIframeUrl($iframe_url, $public_token, $mem_token);

        if (!$resUrl['success']){
//            exit(json_encode( [ 'code' =>'-1','msg'=>'游戏链接获取失败' ] ));
            exit("<script>alert('游戏链接获取失败');window.close();</script>");
        }

        $toURL = $resUrl;

        if($platform==13 || $platform==14) {
            $status = '200';
            $describe = '恭喜成功获取APP地址';
            $data['url'] = $toURL;
            original_phone_request_response($status,$describe,$data);
        }
        else{
            header("Location:".$toURL);
        }
        break;
    case 'getGuestLaunchGameUrl': // 游客

        header("Location:".$thunfireTryDomain);
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
