<?php
/**
 * 自定义开元棋牌API
 * 1.登录游戏
 * Date: 2018/8/23
 */
include_once "../config.inc.php";
include_once "ApiProxy.php";

$username = isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM " . DBPREFIX.MEMBERTABLE." where `UserName` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
if(!$stmt->affected_rows) {
    exit(json_encode( ['code' => '422', 'message' => '此会员不存在，请确认后查询！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
$adminLevel = isset($_REQUEST['lv']) ? trim($_REQUEST['lv']) : ''; // 管理员层级
$adminName = isset($_REQUEST['loginname'])? trim($_REQUEST['loginname']) : ''; // 管理员用户名

if($exchangeFrom == 'hg' && $exchangeTo == 'ky')
    $action = 'hg2ky';
if($exchangeFrom == 'ky' && $exchangeTo == 'hg')
    $action = 'ky2hg';

// 3.检测登录开元会员
$kyExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "ky_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $kyExist = mysqli_num_rows($result);
    if(!$kyExist){ // 未创建账号前请求余额接口
        if($action == 'b'){
            $data = [
                'ky_balance' => '0.00',
                'hg_balance' => sprintf('%.2f', $aUser['Money'])
            ];
            exit(json_encode(['code' => 0, 'data' => $data]));
        }else if($action == 'hg2ky' || $action == 'ky2hg'){ // 未创建账号前请求额度转换接口
            $data = [
                'userid' => $aUser['ID'],
                'username' => $aUser['UserName'],
                'line_code' => $kyConfig['lineCode'],
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
            $sql = "INSERT INTO `" . DBPREFIX . "ky_member_data` SET $sInsData";
            if (!mysqli_query($dbMasterLink, $sql)) {
                exit(json_encode(['code' => 2001, 'message' => '开元账号异常，请您稍后重试！']));
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
        $aResult = kyApi($params);
        if($aResult['d']['code'] === 0) { // 成功请求登录
            if($kyExist == 0){ // 若无平台开元账号，则注册会员入库
                $data = [
                    'userid' => $aUser['ID'],
                    'username' => $aUser['UserName'],
                    'line_code' => $kyConfig['lineCode'],
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
                $sql = "INSERT INTO `" . DBPREFIX . "ky_member_data` SET $sInsData";
                if (!mysqli_query($dbMasterLink, $sql)) {
                    exit(json_encode(['code' => 2001, 'message' => '开元账号异常，请您稍后重试！']));
                }
            }else{ // 若有平台开元账号，则更新登录信息
                mysqli_query($dbMasterLink, "update " . DBPREFIX . "ky_member_data set launch_times = launch_times + 1, last_launch_time = '$now'  WHERE userid = '{$aUser['ID']}'");
            }
            exit(json_encode($aResult['d']));
        } else {
            exit(json_encode(['code' => $aResult['d']['code'], 'message' => '开元账号异常，请您稍后重试！']));
        }
        break;
    case 'b':
        $aResult = checkBalance($aUser['UserName']);
        $data = [];
        if($aResult['d']['code'] === 0){
            $data = [
                'ky_balance' => sprintf('%.2f', $aResult['d']['money']),
                'hg_balance' => sprintf('%.2f', $aUser['Money'])
            ];
            exit(json_encode(['code' => 0, 'data' => $data]));
        }else{
            exit(json_encode(['code' => $aResult['d']['code'], 'message' => '开元余额获取失败，请稍后重试！']));
        }
        break;
    case 'ky2hg':
        // 1.参数校验-后台资金归集不限制数据类型
        if(!preg_match("/^[1-9][0-9]*$/", $score)) {
            exit(json_encode([ 'code' => 2010, 'message' => '转账金额格式错误，请重新输入!' ]));
        }

        if ($score > 10000000){
            exit(json_encode([ 'code' => 2011, 'message' => '单次下分不能超过一千万，请重新输入！' ]));
        }
        // 2.查询开元棋牌可下分余额
        $aResult = checkBalance($aUser['UserName']);
        if(isset($aResult['d']) && $aResult['d']['code'] == 0){
            $kyBalance = $aResult['d']['money'];
        }else{
            exit(json_encode(['code' => 2012, 'message' => '开元棋牌余额获取失败，请稍后重试！']));
        }
        if(intval($kyBalance) < intval($score))
            exit(json_encode(['code' => 2013, 'message' => '开元棋牌余额不足！']));

        // 3.事务处理
        $dbMasterLink->autocommit(false);

        // 3.1.事务内查询用户余额，后续用于更新用户余额
        $result = mysqli_query($dbMasterLink, 'SELECT `ID`, `Money` FROM ' . DBPREFIX.MEMBERTABLE.' WHERE `ID` = ' . $aUser['ID'] . ' FOR UPDATE');
        $aForUpdate = mysqli_fetch_assoc($result);
        $beforeBalance = $aForUpdate['Money']; // 下分转换之前余额
        $afterBalance = bcadd($beforeBalance, $score, 4); // 下分转换之后余额

        // 更新会员余额
        if(!$updated = mysqli_query($dbMasterLink, 'UPDATE ' . DBPREFIX.MEMBERTABLE.' SET `Money` = ' . $afterBalance . ' WHERE `ID` = ' . $aUser['ID'])) {
            $dbMasterLink->rollback();
            exit(json_encode(['code' => 2014, 'message' => '额度更新失败，请您稍后重试！']));
        }
        // 开元-orderId
        $time_str = timestamp_str('YmdHis', 'Etc/GMT+4'); // 美东时间
        $orderId = $kyConfig['agent'] . $time_str . $aUser['UserName'];

        // 3.2.入库额度转换表
        $data = [
            'userid' => $aUser['ID'],
            'Checked' => 1,
            'Gold' => $score,
            'moneyf' => $beforeBalance,
            'currency_after' => $afterBalance,
            'AddDate' => $now,
            'Type' => 'Q',
            'From' => 'ky',
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
            'reason' => 'TY TO HG',
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
            exit(json_encode(['code' => 2015, 'message' => '额度更新失败，请您稍后重试！']));
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
            27, // type：27开元棋牌到体育
            6, // 后台管理
            $insertId,
            '资金归集'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            exit(json_encode(['code' => 2016, 'message' => '额度更新失败，请您稍后重试！']));
        }

        // 3.4.调用三方下分接口
        $params = [
            's' => 3,
            'account' => $aUser['UserName'],
            'money' => $score,
            'orderid' => $orderId
        ];
        $aResult = kyApi($params);
        if($aResult){
            if($aResult['d']['code'] === 0){
                $dbMasterLink->commit();
                $data = [
                    'ky_balance' => sprintf('%.2f', $aResult['d']['money']),
                    'hg_balance' => sprintf('%.2f', $afterBalance)
                ];
                exit(json_encode(['code' => 0, 'data' => $data]));
            }else{
                $dbMasterLink->rollback();
                exit(json_encode(['code' => 2017, 'message' => '额度转换失败，请您稍后重试！']));
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = checkOrder($orderId);
            if($aResult){
                if($aResult['d']['code'] === 0){
                    switch ($aResult['d']['status']){
                        case -1:
                        case 2:
                            $dbMasterLink->rollback();
                            exit(json_encode(['code' => 2009, 'message' => '额度转换失败，请您稍后重试！']));
                            break;
                        case 0:
                            $dbMasterLink->commit();
                            $data = [
                                'ky_balance' => sprintf('%.2f', $aResult['d']['money']),
                                'hg_balance' => sprintf('%.2f', $afterBalance)
                            ];
                            exit(json_encode(['code' => 0, 'data' => $data]));
                            break;
                    }
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                exit(json_encode(['code' => 2010, 'message' => '额度转换失败，请您稍后重试！']));
            }
        }
        break;
    default:
        exit(json_encode(['code' => -1, 'message' => '抱歉，您的请求不予处理！']));
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
    $aResult = kyApi($params);
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
    $aResult = kyApi($params);
    return $aResult;
}

/**
 * 调用三方接口
 * @param $params
 * @return array|mixed
 */
function kyApi($params){
    global $kyConfig;
    $oKy = new ApiProxy($kyConfig);
    try {
        ob_start();
        $res = $oKy->main($params);
        return json_decode($res, true);
    } catch (Exception $e) {
        ob_end_clean();
        return array(
            'm' => 'kyProxy',
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
