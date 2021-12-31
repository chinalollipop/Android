<?php
/**
 * 自定义三方彩票API
 * Date: 2018/8/23
 */
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
include_once('../include/config.inc.php');
require_once ROOT_DIR . '/common/thirdcp/ApiCp.php';

if(!isset($_SESSION['Oid']) || $_SESSION['Oid'] == ''){
    original_phone_request_response('401.1', '您的登录信息已过期，请您重新登录！');
}
$uid = $_SESSION['Oid'];

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM " . DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
if(!$stmt->affected_rows) {
    original_phone_request_response('401.1', '您的登录信息已过期，请您重新登录！');
}
$aUser = $stmt->get_result()->fetch_assoc();

//判断终端类型
if($_REQUEST['appRefer'] == 13 || $_REQUEST['appRefer'] == 14) { // 14 原生android，13 原生ios
    $playSource= $appRefer = $_REQUEST['appRefer'];

    switch ($appRefer){
        case 13:
            // 判断国民彩票是否维护（安卓）
            $pageMark = 'thirdcp';
            $aRow = getMaintainDataByCategory($pageMark);
            $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
            if ($aRow['state']==1 and in_array(13, $aTerminal)){
                $status = '555';
                $describe = $aRow['content'];
                original_phone_request_response($status,$describe,$aData);
            }
            break;
        case 14:
            // 判断国民彩票是否维护（苹果）
            $pageMark = 'thirdcp';
            $aRow = getMaintainDataByCategory($pageMark);
            $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
            if ($aRow['state']==1 and in_array(14, $aTerminal)){
                $status = '555';
                $describe = $aRow['content'];
                original_phone_request_response($status,$describe,$aData);
            }
            break;
    }
} else{
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $playSource=3;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $playSource=4;
    }else{
        $playSource=5;
    }

    // 判断国民彩票是否维护（m版）
    $pageMark = 'thirdcp';
    $aRow = getMaintainDataByCategory($pageMark);
    $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
    if ($aRow['state']==1 and in_array(1, $aTerminal)){
        $status = '555';
        $describe = $aRow['content'];
        original_phone_request_response($status,$describe);
    }
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
if($exchangeFrom == 'hg' && $exchangeTo == 'gmcp')
    $action = 'hg2gmcp';
if($exchangeFrom == 'gmcp' && $exchangeTo == 'hg')
    $action = 'gmcp2hg';
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2gmcp' || $action == 'gmcp2hg'){
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
// 3.检测登录皇冠会员
$gmcpExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "cp_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $gmcpExist = mysqli_num_rows($result);
    if(!$gmcpExist){ // 未创建账号前请求余额接口
        if($action == 'b'){
            $data = [
                'gmcp_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            original_phone_request_response('200', '获取用户余额成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
        }else if($action == 'hg2gmcp' || $action == 'gmcp2hg'){ // 未创建账号前请求额度转换接口
            original_phone_request_response('400.6', '抱歉，请您先进入彩票游戏后再尝试转账！');
        }
    }
}

switch ($action){
    case 'b':
        $aResult = checkBalance($aUser['UserName']);
        $data = [];
        if(!$aResult['errno']){
            $data = [
                'gmcp_balance' => sprintf('%.2f', $aResult['data']['money']), // 返回余额
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            original_phone_request_response('200', '获取用户余额成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
        }else{
            original_phone_request_response('401.6', '三方彩票余额获取失败，请稍后重试！');
        }
        break;
    case 'hg2gmcp':
        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score))
            original_phone_request_response('401.7', '转账金额格式错误，请重新输入!');

        if ($score > 10000000){
            original_phone_request_response('401.8', '单次上分不能超过一千万，请重新输入!');
        }

        // 2.事务处理
        $dbMasterLink->autocommit(false);

        // 2.1.事务内查询用户余额，后续用于更新用户余额
        $result = mysqli_query($dbMasterLink, 'SELECT `ID`, `Money` FROM ' . DBPREFIX.MEMBERTABLE.' WHERE `ID` = ' . $aUser['ID'] . ' FOR UPDATE');
        $aForUpdate = mysqli_fetch_assoc($result);
        $beforeBalance = $aForUpdate['Money']; // 转换之前余额
        if(intval($beforeBalance) < intval($score)) { // 余额不足
            original_phone_request_response('401.9', '中心钱包不足!');
        }
        $afterBalance = bcsub($beforeBalance, $score, 4); // 转换之后余额

        // 更新会员余额
        if(!$updated = mysqli_query($dbMasterLink, 'UPDATE ' . DBPREFIX.MEMBERTABLE.' SET `Money` = ' . $afterBalance . ' WHERE `ID` = ' . $aUser['ID'])) {
            $dbMasterLink->rollback();
            original_phone_request_response('401.10', '额度转换失败，请您稍后重试!');
        }
        // 三方彩票-orderId
        $orderId = strtoupper(uniqid($aUser['ID'], true));

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
            'To' => 'gmcp',
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
            'reason' => '3CP TO TY',
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
            original_phone_request_response('401.11', '额度转换失败，请您稍后重试!');
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
            38, // type：38三方彩票
            $playSource, // 类型
            $insertId,
            '三方彩票额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            original_phone_request_response('401.12', '额度转换失败，请您稍后重试!');
        }

        // 2.4.调用三方上分接口
        $params = [
            'method' => 'depositMoney',
            'sitemid' => $aUser['UserName'],
            'amount' => $score, // 元模式转分模式
            'order_id' => $orderId
        ];
        $aResult = cp3Api($params);
        if($aResult){
            if(!$aResult['errno']){
                $dbMasterLink->commit();
                $data = [
                    'gmcp_balance' => sprintf('%.2f', $aResult['data']['money']),
                    'hg_balance' => formatMoney($afterBalance)
                ];
                original_phone_request_response('200', '额度转换成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
            }else{
                $dbMasterLink->rollback();
                original_phone_request_response('401.13', '额度转换失败，请您稍后重试!');
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = checkOrder($aUser['UserName'], $orderId);
            if($aResult){
                if(!$aResult['errno']) {
                    $dbMasterLink->commit();
                    $data = [
                        'gmcp_balance' => sprintf('%.2f', $aResult['data']['money']),
                        'hg_balance' => formatMoney($afterBalance)
                    ];
                    original_phone_request_response('200', '额度转换成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
                }else{
                    $dbMasterLink->rollback();
                    original_phone_request_response('401.14', '额度转换失败，请您稍后重试!');
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                original_phone_request_response('401.15', '额度转换失败，请您稍后重试!');
            }
        }
        break;
    case 'gmcp2hg':
        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score))
            original_phone_request_response('401.16', '转账金额格式错误，请重新输入!');

        if ($score > 10000000){
            original_phone_request_response('401.17', '单次下分不能超过一千万，请重新输入!');
        }
        // 2.查询三方彩票可下分余额
        $gmcpBalance = '0.00';
        $aResult = checkBalance($aUser['UserName']);
        if(!$aResult['errno']){
            $gmcpBalance = $aResult['data']['money'];
        }else{
            original_phone_request_response('401.18', '三方彩票余额获取失败，请稍后重试!');
        }
        if(intval($gmcpBalance) < intval($score))
            original_phone_request_response('401.19', '三方彩票余额不足!');

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
            original_phone_request_response('401.20', '额度转换失败，请您稍后重试!');
        }
        // 三方彩票-orderId
        $orderId = strtoupper(uniqid($aUser['ID'], true));

        // 3.2.入库额度转换表
        $data = [
            'userid' => $aUser['ID'],
            'Checked' => 1,
            'Gold' => $score,
            'moneyf' => $beforeBalance,
            'currency_after' => $afterBalance,
            'AddDate' => $now,
            'Type' => 'Q',
            'From' => 'gmcp',
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
            'reason' => 'TY TO 3CP',
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
            original_phone_request_response('401.21', '额度转换失败，请您稍后重试!');
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
            39, // type：39三方彩票
            $playSource, // 类型
            $insertId,
            '三方彩票额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            original_phone_request_response('401.22', '额度转换失败，请您稍后重试!');
        }

        // 3.4.调用三方下分接口
        $params = [
            'method' => 'debitMoney',
            'sitemid' => $aUser['UserName'],
            'amount' => $score,
            'order_id' => $orderId
        ];
        $aResult = cp3Api($params);
        if($aResult){
            if(!$aResult['errno']){
                $dbMasterLink->commit();
                $data = [
                    'gmcp_balance' => sprintf('%.2f', $aResult['data']['money']),
                    'hg_balance' => formatMoney($afterBalance)
                ];
                original_phone_request_response('200', '额度转换成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
            }else{
                $dbMasterLink->rollback();
                original_phone_request_response('401.23', '额度转换失败，请您稍后重试!');
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = checkOrder($aUser['UserName'] ,$orderId);
            if($aResult){
                if(!$aResult['errno']){
                    $dbMasterLink->commit();
                    $data = [
                        'gmcp_balance' => sprintf('%.2f', $aResult['data']['money']),
                        'hg_balance' => formatMoney($afterBalance)
                    ];
                    original_phone_request_response('200', '额度转换成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
                }else{
                    $dbMasterLink->rollback();
                    original_phone_request_response('401.23', '额度转换失败，请您稍后重试!');
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                original_phone_request_response('401.23', '额度转换失败，请您稍后重试!');
            }
        }
        break;
    default:
        original_phone_request_response('401.26', '抱歉，您的请求不予处理!');
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
        'method' => 'getUserMoney',
        'sitemid' => $username
    ];
    $aResult = cp3Api($params);
    return $aResult;
}

/**
 * 查询额度转换订单
 * @param $username
 * @param $orderId
 * @return array|mixed
 */
function checkOrder($username, $orderId)
{
    $params = [
        'method' => 'getTransRecord',
        'sitemid' => $username,
        'order_id' => $orderId,
    ];
    $aResult = cp3Api($params);
    return $aResult;
}

/**
 * 调用接口
 * @param $params
 * @return array|mixed
 */
function cp3Api($params){
    $ogmcp = new ApiCp();
    try {
        ob_start();
        $res = $ogmcp->main($params);
        return json_decode($res, true);
    } catch (Exception $e) {
        ob_end_clean();
        return array(
            'm' => 'cp3Api',
            's' => $params['method'],
            'd' => array(
                'code' => -1,
                'message' => $e->getMessage()
            )
        );
    } finally {
        ob_end_flush();
        closelog();
    }
}