<?php
/**
 * 自定义乐游棋牌API
 * 1.登录游戏
 */

define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
ini_set('display_errors', 'OFF');
include_once('../include/config.inc.php');
require_once ROOT_DIR.'/common/lyqp/ApiProxy.php';

if(!isset($_SESSION['Oid']) || $_SESSION['Oid'] == ''){
    $status = '401.1';
    $describe = '您的登录信息已过期，请您重新登录！';
    original_phone_request_response($status,$describe);
}

$uid = $_SESSION['Oid'];

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM ".DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
if(!$stmt->affected_rows) {
    $status = '401.2';
    $describe = '您的登录信息已过期，请您重新登录！';
    original_phone_request_response($status,$describe);
}
$aUser = $stmt->get_result()->fetch_assoc();

//判断终端类型
if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
    $playSource=$_REQUEST['appRefer'];

    switch ($playSource){
        case 13:
            // 判断乐游棋牌是否维护（安卓）
            $pageMark = 'ly';
            $aRow = getMaintainDataByCategory($pageMark);
            $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
            if ($aRow['state']==1 and in_array(13, $aTerminal)){
                $status = '555';
                $describe = '乐游棋牌维护中，请选择其他游戏';
                original_phone_request_response($status,$describe);
            }
            break;
        case 14:
            // 判断乐游棋牌是否维护（苹果）
            $pageMark = 'ly';
            $aRow = getMaintainDataByCategory($pageMark);
            $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
            if ($aRow['state']==1 and in_array(14, $aTerminal)){
                $status = '555';
                $describe = '乐游棋牌维护中，请选择其他游戏';
                original_phone_request_response($status,$describe);
            }
            break;
    }
}
else{
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $playSource=3;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $playSource=4;
    }else{
        $playSource=5;
    }

    // 判断乐游棋牌是否维护（m版）
    $pageMark = 'ly';
    $aRow = getMaintainDataByCategory($pageMark);
    $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
    if ($aRow['state']==1 and in_array(1, $aTerminal)){
        $status = '555';
        $describe = $aRow['content'];
        original_phone_request_response($status,$describe);
    }
}

if ($_SESSION['Agents']=='demoguest'){
//    exit(json_encode( ['err' => '-2', 'msg' => '请使用真实账号登入乐游棋牌'] ) );
    $status = '401.2';
    $describe = '请使用真实账号登入乐游棋牌';
    original_phone_request_response($status,$describe);
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
if($exchangeFrom == 'hg' && $exchangeTo == 'ly'){
    $action = 'hg2ly';
}

if($exchangeFrom == 'ly' && $exchangeTo == 'hg'){
    $action = 'ly2hg';
}
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2ly' || $action == 'ly2hg'){
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
$objectLy = new LyApiProxy();

// 3.检测登录乐游会员
$lyExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "ly_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $lyExist = mysqli_num_rows($result);
    if(!$lyExist){ // 未创建账号前请求余额接口
        if($action == 'b'){
            $data = [
                'ly_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            original_phone_request_response('200', '获取用户余额成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
        }else if($action == 'hg2ly' || $action == 'ly2hg'){ // 未创建账号前请求额度转换接口
            $data = [
                'userid' => $aUser['ID'],
                'username' => $aUser['UserName'],
                'line_code' => $objectLy->lineCode,
                'agents' => $aUser['Agents'],
                'world' => $aUser['World'],
                'corporator' => $aUser['Corprator'],
                'super' => $aUser['Super'],
                'admin' => $aUser['Admin'],
                'register_time' => $now,
                'last_launch_time' => $now,
                'launch_times' => 1,
                'is_test' => $aUser['test_flag']
            ];
            $sInsData = '';
            foreach ($data as $key => $value){
                $sInsData .= "`$key` = '{$value}'" . ($key == 'is_test' ? '' : ',');
            }
            $sql = "INSERT INTO `" . DBPREFIX . "ly_member_data` SET $sInsData";
            if (!mysqli_query($dbMasterLink, $sql)) {
                $status = '401.3';
                $describe = '乐游账号异常，请您稍后重试！';
                original_phone_request_response($status,$describe);
            }
        }
    }
}

switch ($action){
    case "cm":

        $params = [
            's' => 0,
            'account' => $aUser['UserName'],
            'money' => 0 // 初始化金额为0
        ];
        $aResult = lyqpApi($params);

        if($aResult['d']['code'] == 0) { // 成功请求登录
            if($lyExist == 0){ // 若无平台乐游账号，则注册会员入库
                $data = [
                    'userid' => $aUser['ID'],
                    'username' => $aUser['UserName'],
                    'line_code' => $objectLy->lineCode,
                    'agents' => $aUser['Agents'],
                    'world' => $aUser['World'],
                    'corporator' => $aUser['Corprator'],
                    'super' => $aUser['Super'],
                    'admin' => $aUser['Admin'],
                    'register_time' => $now,
                    'last_launch_time' => $now,
                    'launch_times' => 1,
                    'is_test' => $aUser['test_flag']
                ];
                $sInsData = '';
                foreach ($data as $key => $value){
                    $sInsData .= "`$key` = '{$value}'" . ($key == 'is_test' ? '' : ',');
                }
                $sql = "INSERT INTO `" . DBPREFIX . "ly_member_data` SET $sInsData";
                if (!mysqli_query($dbMasterLink, $sql)) {
                    $status = '401.4';
                    $describe = '乐游账号异常，请您稍后重试！';
                    original_phone_request_response($status,$describe,$aData);
                }
            }else{ // 若有平台开元账号，则更新登录信息
                mysqli_query($dbMasterLink, "update " . DBPREFIX . "ly_member_data set launch_times = launch_times + 1, last_launch_time = '$now'  WHERE userid = '{$aUser['ID']}'");
            }
            if (empty($aResult['d'])){
                $status = '401.27';
                $describe = '乐游登录结果获取失败';
                original_phone_request_response($status,$describe);
            }
            $status = '200';
            $describe = '恭喜！创建乐游账号成功！';
            original_phone_request_response($status,$describe,$aResult['d']);

        } else {

            $status = '401.5';
            $describe = $aResult['d']['code'].' 乐游账号异常，请您稍后重试！';
            original_phone_request_response($status,$describe);
        }
        break;
    case 'b':
        $aResult = checkBalance($aUser['UserName']);
        $data = [];
        if($aResult['d']['code'] === 0){
            $data = [
                'ly_balance' => sprintf('%.2f', $aResult['d']['money']),
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            original_phone_request_response('200', '获取用户余额成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
        }else{

            $status = '401.6';
            $describe = $aResult['d']['code'].'乐游余额获取失败，请稍后重试！';
            original_phone_request_response($status,$describe);
        }
        break;
    case 'hg2ly':
        // 1.参数校验

        if(!preg_match("/^[1-9][0-9]*$/", $score)){
            $status = '401.7';
            $describe = '转账金额格式错误，请重新输入!';
            original_phone_request_response($status,$describe);
        }

        if ($score > 10000000){
            $status = '401.8';
            $describe = '单次上分不能超过一千万，请重新输入！';
            original_phone_request_response($status,$describe);
        }

        // 2.事务处理
        $dbMasterLink->autocommit(false);

        // 2.1.事务内查询用户余额，后续用于更新用户余额
        $result = mysqli_query($dbMasterLink, 'SELECT `ID`, `Money` FROM '.DBPREFIX.MEMBERTABLE.' WHERE `ID` = ' . $aUser['ID'] . ' FOR UPDATE');
        $aForUpdate = mysqli_fetch_assoc($result);
        $beforeBalance = $aForUpdate['Money']; // 转换之前余额
        if(intval($beforeBalance) < intval($score)) { // 余额不足
            $status = '401.9';
            $describe = '中心钱包不足！';
            original_phone_request_response($status,$describe);
        }
        $afterBalance = bcsub($beforeBalance, $score, 4); // 转换之后余额

        // 更新会员余额
        if(!$updated = mysqli_query($dbMasterLink, 'UPDATE '.DBPREFIX.MEMBERTABLE.' SET `Money` = ' . $afterBalance . ' WHERE `ID` = ' . $aUser['ID'])) {
            $dbMasterLink->rollback();
            $status = '401.10';
            $describe = '额度转换失败，请您稍后重试！';
            original_phone_request_response($status,$describe);
        }

        // 乐游-orderId
        $time_str = $objectLy->timestamp_str('YmdHis', 'Etc/GMT+4'); // 美东时间
        $orderId = $lyConfig['agent'] . $time_str . $aUser['UserName'];

        // 2.2.入库额度转换表
        $data = [
            'userid' => $aUser['ID'],
            'Checked' => 1,
            'Gold' => $score,
            'moneyf' => $beforeBalance,
            'currency_after' => $afterBalance,
            'AddDate' => $now,
            'Type' => 'Q',
            'From' => 'hg',
            'To' => 'ly',
            'UserName' => $aUser['UserName'],
            'Agents' => $aUser['Agents'],
            'World' => $aUser['World'],
            'Corprator' => $aUser['Corprator'],
            'Super' => $aUser['Super'],
            'Admin' => $aUser['Admin'],
            'CurType' => 'RMB',
            'Date' => $now,
            'Name' => $aUser['Alias'],
            'Waterno' => '',
            'Phone' => $aUser['Phone'],
            'Notes' => '即时入账',
            'Order_Code' => $orderId,
            'reason' => 'HG TO LY',
            'AuditDate' => $now,
            'test_flag' => $aUser['test_flag']
        ];

        $sInsData = '';
        foreach ($data as $key => $value){
            $sInsData .= "`$key` = '{$value}'" . ($key == 'test_flag' ? '' : ',');
        }
        $sql = "INSERT INTO `" . DBPREFIX . "web_sys800_data` SET $sInsData";
        if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
            $dbMasterLink->rollback();
            $status = '401.11';
            $describe = '额度转换失败，请您稍后重试！';
            original_phone_request_response($status,$describe);
        }
        $insertId = mysqli_insert_id($dbMasterLink); // 后续入账变

        // 2.3.入库账变
        $data = [
            $aUser['ID'],
            $aUser['UserName'],
            $aUser['test_flag'],
            $beforeBalance,
            -$score,
            $afterBalance,
            34, // type：35 乐游棋牌
            1, // 旧版本
            $insertId,
            '乐游棋牌额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();

            $status = '401.12';
            $describe = '额度转换失败，请您稍后重试！';
            original_phone_request_response($status,$describe);
        }

        // 2.4.调用三方上分接口
        $params = [
            's' => 2,
            'account' => $aUser['UserName'],
            'orderid' => $orderId,
            'money' => $score,
        ];
        $aResult = lyqpApi($params);
        if($aResult){
            if($aResult['d']['code'] === 0){
                $dbMasterLink->commit();
                $data = [
                    'ly_balance' => sprintf('%.2f', $aResult['d']['money']),
                    'hg_balance' => formatMoney($afterBalance)
                ];
                $status = '200';
                $describe = '额度转换成功';
                original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
            }else{
                $dbMasterLink->rollback();
                $status = '401.13';
                $describe = '额度转换失败，请您稍后重试！';
                original_phone_request_response($status,$describe);
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = checkOrder($orderId);
            if($aResult){
                if($aResult['d']['code'] === 0){
                    switch ($aResult['d']['status']){
                        case -1:
                        case 2:
                            $dbMasterLink->rollback();
                            $status = '401.14';
                            $describe = '额度转换失败，请您稍后重试！';
                            original_phone_request_response($status,$describe);
                            break;
                        case 0:
                            $dbMasterLink->commit();
                            $data = [
                                'ly_balance' => sprintf('%.2f', $aResult['d']['money']),
                                'hg_balance' => formatMoney($afterBalance)
                            ];
                            $status = '200';
                            $describe = '额度转换成功';
                            original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);

                            break;
                    }
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                $status = '401.15';
                $describe = '额度转换失败，请您稍后重试！';
                original_phone_request_response($status,$describe);
            }
        }
        break;
    case 'ly2hg':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score)){
            $status = '401.16';
            $describe = '转账金额格式错误，请重新输入!';
            original_phone_request_response($status,$describe);
        }

        if ($score > 10000000){
            $status = '401.17';
            $describe = '单次下分不能超过一千万，请重新输入！';
            original_phone_request_response($status,$describe);
        }
        // 2.查询开元棋牌可下分余额
        $aResult = checkBalance($aUser['UserName']);
        if(isset($aResult['d']) && $aResult['d']['code'] == 0){
            $lyBalance = $aResult['d']['money'];
        }else{

            $status = '401.18';
            $describe = '乐游棋牌余额获取失败，请稍后重试！';
            original_phone_request_response($status,$describe);
        }
        if(intval($lyBalance) < intval($score)){
            $status = '401.19';
            $describe = '乐游棋牌余额不足！';
            original_phone_request_response($status,$describe);
        }

        // 3.事务处理
        $dbMasterLink->autocommit(false);

        // 3.1.事务内查询用户余额，后续用于更新用户余额
        $result = mysqli_query($dbMasterLink, 'SELECT `ID`, `Money` FROM '.DBPREFIX.MEMBERTABLE.' WHERE `ID` = ' . $aUser['ID'] . ' FOR UPDATE');
        $aForUpdate = mysqli_fetch_assoc($result);
        $beforeBalance = $aForUpdate['Money']; // 下分转换之前余额
        $afterBalance = bcadd($beforeBalance, $score, 4); // 下分转换之后余额

        // 更新会员余额
        if(!$updated = mysqli_query($dbMasterLink, 'UPDATE '.DBPREFIX.MEMBERTABLE.' SET `Money` = ' . $afterBalance . ' WHERE `ID` = ' . $aUser['ID'])) {
            $dbMasterLink->rollback();

            $status = '401.20';
            $describe = '额度更新失败，请您稍后重试！';
            original_phone_request_response($status,$describe);
        }
        // 乐游-orderId
        $time_str = $objectLy->timestamp_str('YmdHis', 'Etc/GMT+4'); // 美东时间
        $orderId = $lyConfig['agent'] . $time_str . $aUser['UserName'];

        // 3.2.入库额度转换表
        $data = [
            'userid' => $aUser['ID'],
            'Checked' => 1,
            'Gold' => $score,
            'moneyf' => $beforeBalance,
            'currency_after' => $afterBalance,
            'AddDate' => $now,
            'Type' => 'Q',
            'From' => 'ly',
            'To' => 'hg',
            'UserName' => $aUser['UserName'],
            'Agents' => $aUser['Agents'],
            'World' => $aUser['World'],
            'Corprator' => $aUser['Corprator'],
            'Super' => $aUser['Super'],
            'Admin' => $aUser['Admin'],
            'CurType' => 'RMB',
            'Date' => $now,
            'Name' => $aUser['Alias'],
            'Waterno' => '',
            'Phone' => $aUser['Phone'],
            'Notes' => '即时入账',
            'Order_Code' => $orderId,
            'reason' => 'LY TO HG',
            'AuditDate' => $now,
            'test_flag' => $aUser['test_flag']
        ];
        $sInsData = '';
        foreach ($data as $key => $value){
            $sInsData .= "`$key` = '{$value}'" . ($key == 'test_flag' ? '' : ',');
        }
        $sql = "INSERT INTO `" . DBPREFIX . "web_sys800_data` SET $sInsData";
        if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
            $dbMasterLink->rollback();

            $status = '401.21';
            $describe = '额度更新失败，请您稍后重试！';
            original_phone_request_response($status,$describe);
        }
        $insertId = mysqli_insert_id($dbMasterLink); // 后续入账变

        // 3.3.入库账变
        $data = [
            $aUser['ID'],
            $aUser['UserName'],
            $aUser['test_flag'],
            $beforeBalance,
            $score,
            $afterBalance,
            35, // type：35 乐游棋牌
            1, // 旧版本
            $insertId,
            '乐游棋牌额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();

            $status = '401.22';
            $describe = '额度更新失败，请您稍后重试！';
            original_phone_request_response($status,$describe);
        }

        // 3.4.调用三方下分接口
        $params = [
            's' => 3,
            'account' => $aUser['UserName'],
            'money' => $score,
            'orderid' => $orderId
        ];
        $aResult = lyqpApi($params);
        if($aResult){
            if($aResult['d']['code'] === 0){
                $dbMasterLink->commit();
                $data = [
                    'ly_balance' => sprintf('%.2f', $aResult['d']['money']),
                    'hg_balance' => formatMoney($afterBalance)
                ];

                $status = '200';
                $describe = '额度转换成功！';
                original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
            }else{
                $dbMasterLink->rollback();

                $status = '401.23';
                $describe = '额度转换失败，请您稍后重试！';
                original_phone_request_response($status,$describe);
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = checkOrder($orderId);
            if($aResult){
                if($aResult['d']['code'] === 0){
                    switch ($aResult['d']['status']){
                        case -1:
                        case 2:
                            $dbMasterLink->rollback();
                            $status = '401.24';
                            $describe = '额度转换失败，请您稍后重试！';
                            original_phone_request_response($status,$describe);
                            break;
                        case 0:
                            $dbMasterLink->commit();
                            $data = [
                                'ly_balance' => sprintf('%.2f', $aResult['d']['money']),
                                'hg_balance' => formatMoney($afterBalance)
                            ];
                            $status = '200';
                            $describe = '额度转换成功！';
                            original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
                            break;
                    }
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();

                $status = '401.25';
                $describe = '额度转换失败，请您稍后重试！';
                original_phone_request_response($status,$describe);
            }
        }
        break;
    default:
        $status = '401.26';
        $describe = '抱歉，您的请求不予处理！';
        original_phone_request_response($status,$describe);
        break;
}


/**
 * 查询可下分余额
 * @param $username
 * @return array|mixed
 */
function checkBalance($username)
{
    $params = [
        's' => 1,
        'account' => $username
    ];
    $aResult = lyqpApi($params);
    return $aResult;
}

/**
 * 查询额度转换订单
 * @param $orderId
 * @return array|mixed
 */
function checkOrder($orderId)
{
    $params = [
        's' => 4,
        'orderid' => $orderId,
    ];
    $aResult = lyqpApi($params);
    return $aResult;
}

/**
 * @param $params
 * @return array|mixed
 */
function lyqpApi($params){
    $objectLy = new LyApiProxy();
    try {
        ob_start();
        $res = $objectLy->lyMain($params);
        return json_decode($res, true);
    } catch (Exception $e) {
        ob_end_clean();
        return array(
            'm' => 'lyProxy',
            's' => $params['s'],
            'd' => array(
                'code' => -1,
                'message' => $e->getMessage()
            )
        );
        // 写错误日志
    } finally {
        ob_end_flush();
        closelog();
    }
}
