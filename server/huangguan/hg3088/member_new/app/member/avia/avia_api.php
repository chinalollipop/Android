<?php
/**
 * 自定义泛亚电竞API
 *
 *  执行任务 action （默认检查泛亚账号，或者创建账号）
 *       b  获取余额
 *       hg2avia  平台上分到avia
 *       avia2hg  avia下分到平台
 *       getLaunchGameUrl  真钱模式
 *       getDemoLaunchGameUrl  试玩模式
 */

//error_reporting(E_ALL);
//ini_set('display_errors','On');
define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(__FILE__))))));
include_once "../include/config.inc.php";
require_once ROOT_DIR.'/common/avia/api.php';

$appRefer = isset($_REQUEST['appRefer']) && $_REQUEST['appRefer'] ? trim($_REQUEST['appRefer']) : 5;
$pageMark='avia';
$maintenanceData = maintenance($pageMark);
if(isset($maintenanceData[$pageMark]) && $maintenanceData[$pageMark]['state'] == 1 && ($_REQUEST['action'] != 'b') ){
//    $status = '403';
//    $describe = '很抱歉，MG电子游戏临时维护中。您可以进行平台其他游戏！感谢您的耐心等候。';
//    original_phone_request_response($status,$describe,$aData);
    $msg = '很抱歉，泛亚电竞游戏临时维护中。您可以进行平台其他游戏！感谢您的耐心等候。';
    exit(json_encode( ['err' => '-1', 'msg' => $msg], JSON_UNESCAPED_UNICODE ) );
}

$uid = $_SESSION['Oid']?$_SESSION['Oid']:$_REQUEST['uid'];
$userid = $_SESSION['userid'] ;

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM ".DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
if(!$stmt->affected_rows) {
    exit(json_encode( ['err' => '-1', 'msg' => '您的登录信息已过期，请您重新登录！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();

if ($aUser['test_flag']){
    exit(json_encode( ['err' => '-2', 'msg' => '请登录真实账号登入泛亚电竞'] ) );
//    exit("<script>alert('请进入试玩，或者请登录真实账号登入泛亚电竞');window.close();</script>");
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$b = $score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
if($exchangeFrom == 'hg' && $exchangeTo == 'avia'){
    $action = 'hg2avia';
}

if($exchangeFrom == 'avia' && $exchangeTo == 'hg'){
    $action = 'avia2hg';
}
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2avia' || $action == 'avia2hg'){
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

// 3.检测登录泛亚会员
$lyExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "avia_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $lyExist = mysqli_num_rows($result);
    if(!$lyExist){
//    if(1){

        if($action == 'b'){ // 未创建账号前请求余额接口
            $data = [
                'avia_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            exit( json_encode( ['err'=>0, 'msg'=>$data ] ) );
        }
        else{

            $length = rand(5,16);
            $member_password = make_char($length);
            $res = createMember($aUser['UserName'], $member_password);
            $aRes = json_decode($res, true);
            if ($aRes['success']){
                $data = [
                    'userid' => $aUser['ID'],
                    'username' => $aUser['UserName'],
                    'password' => $member_password,
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
                $sql = "INSERT INTO `" . DBPREFIX . "avia_member_data` SET $sInsData";
//                echo $sql;die;
                if (!mysqli_query($dbMasterLink, $sql)) {
                    exit(json_encode(['err' => -5, 'msg' => 'AVIA账号异常，请您稍后重试！']));
                }else{

                    if($action=='getLaunchGameUrl' && !($appRefer==13 || $appRefer==14)){ // 登录：初始化账号后直接进入游戏
                        doLoginAction();
                        exit();
                    }else{ // 其他
                        exit(json_encode(['err' => 222, 'msg' => 'AVIA账号初始化成功']));
                    }
                }
            }else{
                exit(json_encode(['err' => -6, 'msg' => '注册AVIA账号失败'.$res]));
            }
        }
    }
}



switch ($action){
    case 'b':
        $res = getWalletDetails ($aUser['UserName']);
        $aRes = json_decode($res, true);
        if (!$aRes['success']){
            exit(json_encode( [ 'err'=>'-1','msg'=>'余额获取失败'.json_encode($aRes) ] ));
        }
        else{
            $data = [
                'avia_balance' => sprintf('%.2f', $aRes['info']['Money']),
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            exit( json_encode( ['err'=>0, 'msg'=>$data ] ) );
        }

        break;
    case 'hg2avia':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $b))
            exit(json_encode([ 'err' => -2, 'msg' => '转账金额只支持正整数，请重新输入' ]));

        if ($b > 10000000){
            exit(json_encode([ 'err' => -7, 'msg'=>'单次上分不能超过一千万，请重新输入！' ]));
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']-$fShiftMoney; // 用户账变后的余额
        //avia生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $sTrans_no = 'AVIAIN' . $sTime8 . $sUser6; // 订单号生成规则

        $data['userid']= $userid ;
        $data['Checked']=1;
        $data['reason']='hg to avia';
        $data['AuditDate']=date("Y-m-d H:i:s");
        $data['Gold']=$fShiftMoney;
        $data['moneyf']=$moneyf;
        $data['currency_after']=$currency_after;
        $data['AddDate']=date("Y-m-d",time());
        $data['Type']='Q';
        $data['From']='hg';
        $data['To']='avia';
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
                        $moneyLogRes=addAccountRecords(array($userid,$aUser['UserName'],$_SESSION['test_flag'],$moneyf,$fShiftMoney*-1,$currency_after,50,1,$insertId,"泛亚电竞额度转换"));
                        if($moneyLogRes){
                            $res = createTransaction ($aUser['UserName'], $fShiftMoney, 'IN', $sTrans_no);
                            $aRes = json_decode($res, true);
                            if (!$aRes['success']){
                                mysqli_rollback($dbMasterLink);
                                exit(json_encode( [ 'err'=>'-12','msg'=>'avia上分失败' ] ));
                            }
                            else{
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);

//                                $res = getWalletDetails ($aUser['UserName']);
//                                $aRes = json_decode($res, true);
//                                if ( !$aRes['success'] ) {
//                                    exit( json_encode( [ 'err'=>'-12','msg'=>'AVIA余额获取失败' ] ) );
//                                }
//
//                                $data = [
//                                    'avia_balance' => sprintf('%.2f', $aRes['info']['Money']),
//                                    'hg_balance' => sprintf('%.2f', $currency_after)
//                                ];
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
    case 'avia2hg':

        // 1.检查avia余额
        $res = getWalletDetails ($aUser['UserName']);
        $aRes = json_decode($res, true);
        if ( !$aRes['success'] ) {
            exit( json_encode( [ 'err'=>'-1','msg'=>'AVIA余额获取失败' ] ) );
        }else{
            $aviaGetbalance["balance"] = $aRes["info"]['Money'];
        }

        if (floatval($aviaGetbalance["balance"]) < floatval($b)){
            exit(json_encode( [ 'err'=>'-6','msg'=>'AVIA余额不足~~' ] ));
        }

        $aviaGetbalance["balance"] = number_format($aviaGetbalance["balance"], 2, '.', ',');

        if ($b > 10000000){
            exit(json_encode( [ 'err'=>'-2','msg'=>'单次下分不能超过一千万，请重新输入！' ] ));
        }

        if(!preg_match("/^[1-9][0-9]*$/",$b)){
            exit(json_encode( [ 'err'=>'-7','msg'=>'转账只支持正整数，请重新输入' ] ));
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        $moneyf = $aUser['Money']; // 用户账变前余额
        $currency_after = $aUser['Money']+$fShiftMoney; // 用户账变后的余额
        //ag生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $sTrans_no = 'AVIAOUT' . $sTime8 . $sUser6;

        // 下分
        $res = createTransaction ($aUser['UserName'], $fShiftMoney, 'OUT', $sTrans_no);
        $aRes = json_decode($res, true);
        if (!$aRes['success']){
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
            $data['From']='avia';
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
                            $moneyLogRes=addAccountRecords(array($userid,$aUser['UserName'],$_SESSION['test_flag'],$rowMoney['Money'],$fShiftMoney,$rowMoney['Money']+$fShiftMoney,51,1,$insertId,"泛亚电竞额度转换"));
                            if($moneyLogRes){
                                mysqli_commit($dbMasterLink);
                                mysqli_close($dbMasterLink);

//                                $res = getWalletDetails ($aUser['UserName']);
//                                $aRes = json_decode($res, true);
//                                if ( !$aRes['success'] ) {
//                                    exit( json_encode( [ 'err'=>'-12','msg'=>'AVIA余额获取失败' ] ) );
//                                }
//                                $data = [
//                                    'avia_balance' => sprintf('%.2f', $aRes["info"]['Money']),
//                                    'hg_balance' => sprintf('%.2f', $currency_after)
//                                ];
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
        doLoginAction();

        break;
    case 'getGuestLaunchGameUrl': // 游客
        $res = getGuestLaunchGameUrl ();
        $aRes = json_decode($res,true);
        if (!$res['success']){
            exit(json_encode( [ 'err'=>'-1','msg'=>'游戏链接获取失败' ] ));
        }
        else{
            header("Location:".$aRes['info']['Url']);
        }
        break;
    case 'getCategory': // 获取游戏分类
        $res = getCategory ();
        $aRes = json_decode($res,true);
        $aCategory = array();
        foreach ($aRes['info']['list'] as $k => $v){
            $aCategory[$k]['ID'] = $v['ID'];
            $aCategory[$k]['Name'] = $v['Name'];
            $aCategory[$k]['Avatar'] = $v['Avatar'];
            $aCategory[$k]['IsOpen'] = $v['IsOpen'];
        }
        print_r($aCategory);

        break;
    default:
        $status = '401.26';
        $describe = '抱歉，您的请求不予处理！';
        original_phone_request_response($status,$describe);
        break;
}

// 登录
function doLoginAction(){
    global $aUser;
    $res = doLogin ($aUser['UserName']);
    $aRes = json_decode($res,true);
    if (!$aRes['success']){
        exit("<script>alert('游戏链接获取失败".json_encode($aRes)."');window.close();</script>");
    }
    else{
        header("Location:".$aRes['info']['Url']);
    }
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
