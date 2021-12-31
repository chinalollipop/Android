<?php
/**
 * 自定义开元棋牌API
 * 1.登录游戏
 * Date: 2018/8/23
 */
ini_set('display_errors', 'OFF');
include_once "../include/config.inc.php";
include_once "../include/ky/ApiProxy.php";

$uid = isset($_REQUEST['uid']) && $_REQUEST['uid'] ? trim($_REQUEST['uid']) : '';

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM " . DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
if(!$stmt->affected_rows) {
    exit(json_encode( ['code' => '422', 'message' => '您的登录信息已过期，请您重新登录！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;

// 判断KY棋牌是否维护-单页面维护功能
if($action=='getLaunchGameUrl'){ // 打开游戏，判断维护
    checkMaintain('ky');
}else if($action=='fundLimitTrans'){ // 转账

    $pageMark = 'ky';
    $aRow = getMaintainDataByCategory($pageMark);
    $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
    if ($aRow['state']==1 and in_array(1, $aTerminal)){
        exit(json_encode(['code' => 555, 'message' => '开元棋牌维护中，请选择其他游戏']));
    }
}

if($exchangeFrom == 'hg' && $exchangeTo == 'ky')
    $action = 'hg2ky';
if($exchangeFrom == 'ky' && $exchangeTo == 'hg')
    $action = 'ky2hg';
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2ky' || $action == 'ky2hg'){
    // 检查当前会员是否设置不准操作额度分层
    // 检查分层是否开启 status 1 开启 0 关闭
    // layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金 5 仅限可以投注体育，不能额度转换去其它馆
    if ($sUserlayer==3 || $sUserlayer==5){
        $layer = getUserLayerById($sUserlayer);
        if ($layer['status']==1) {
            exit(json_encode([ 'code' => 2066, 'message'=>'账号分层异常，请联系我们在线客服' ]));
        }
    }
}
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
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            exit(json_encode(['code' => 200, 'data' => $data]));
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
        if($aResult['d']['code'] == 0) { // 成功请求登录
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
        if($aResult['d']['code'] == 0){
            $data = [
                'ky_balance' => sprintf('%.2f', $aResult['d']['money']),
                'hg_balance' => formatMoney($aUser['Money']),
            ];
            exit(json_encode(['code' => 200, 'data' => $data]));
        }else{
            exit(json_encode(['code' => $aResult['d']['code'], 'message' => '开元余额获取失败，请稍后重试！']));
        }
        break;
    case 'hg2ky':
        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score))
            exit(json_encode([ 'code' => 2002, 'message' => '转账金额格式错误，请重新输入!' ]));

        if ($score > 10000000){
            exit(json_encode([ 'code' => 2003, 'message'=>'单次上分不能超过一千万，请重新输入！' ]));
        }

        // 2.事务处理
        $dbMasterLink->autocommit(false);

        // 2.1.事务内查询用户余额，后续用于更新用户余额
        $result = mysqli_query($dbMasterLink, 'SELECT `ID`, `Money` FROM ' . DBPREFIX.MEMBERTABLE.' WHERE `ID` = ' . $aUser['ID'] . ' FOR UPDATE');
        $aForUpdate = mysqli_fetch_assoc($result);
        $beforeBalance = $aForUpdate['Money']; // 转换之前余额
        if(intval($beforeBalance) < intval($score)) { // 余额不足
            exit(json_encode(['code' => 2004, 'message' => '中心钱包不足！']));
        }
        $afterBalance = bcsub($beforeBalance, $score, 4); // 转换之后余额

        // 更新会员余额
        if(!$updated = mysqli_query($dbMasterLink, 'UPDATE ' . DBPREFIX.MEMBERTABLE.' SET `Money` = ' . $afterBalance . ' WHERE `ID` = ' . $aUser['ID'])) {
            $dbMasterLink->rollback();
            exit(json_encode(['code' => 2005, 'message' => '额度转换失败，请您稍后重试！']));
        }
        // 开元-orderId
        $time_str = timestamp_str('YmdHis', 'Etc/GMT+4'); // 美东时间
        $orderId = $kyConfig['agent'] . $time_str . $aUser['UserName'];

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
            'To' => 'ky',
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
            'reason' => 'HG TO TY',
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
            exit(json_encode(['code' => 2006, 'message' => '额度转换失败，请您稍后重试！']));
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
            26, // type：26开元棋牌
            22, //'投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓,22 综合版',
            $insertId,
            '开元棋牌额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            exit(json_encode(['code' => 2007, 'message' => '额度转换失败，请您稍后重试！']));
        }

        // 2.4.调用三方上分接口
        $params = [
            's' => 2,
            'account' => $aUser['UserName'],
            'orderid' => $orderId,
            'money' => $score,
        ];
        $aResult = kyApi($params);
        if($aResult){
            if($aResult['d']['code'] === 0){
                $dbMasterLink->commit();
                $data = [
                    'ky_balance' => sprintf('%.2f', $aResult['d']['money']),
                    'hg_balance' => formatMoney($afterBalance)
                ];
                exit(json_encode(['code' => 200, 'data' => $data]));
            }else{
                $dbMasterLink->rollback();
                exit(json_encode(['code' => 2008, 'message' => '额度转换失败，请您稍后重试！']));
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
                                'hg_balance' => formatMoney($afterBalance)
                            ];
                            exit(json_encode(['code' => 200, 'data' => $data]));
                            break;
                    }
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                exit(json_encode(['code' => 2010, 'message' => '额度转换失败，请您稍后重试！']));
            }
        }
        break;
    case 'ky2hg':
        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score))
            exit(json_encode([ 'code' => 2010, 'message' => '转账金额格式错误，请重新输入!' ]));

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
            27, // type：26开元棋牌
            22, //'投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓,22 综合版',
            $insertId,
            '开元棋牌额度转换'
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
                    'hg_balance' => formatMoney($afterBalance)
                ];
                exit(json_encode(['code' => 200, 'data' => $data]));
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
                                'hg_balance' => formatMoney($afterBalance)
                            ];
                            exit(json_encode(['code' => 200, 'data' => $data]));
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
