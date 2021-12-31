<?php
/**
 * 自定义VG棋牌API
 * Date: 2018/8/23
 */
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
include_once('../include/config.inc.php');
//include_once "../include/vgqp/ApiVg.php";
include_once ROOT_DIR . '/common/vgqp/api.php';

if(!isset($_SESSION['Oid']) || $_SESSION['Oid'] == ''){
    original_phone_request_response('401.1', '您的登录信息已过期，请您重新登录！');
}
$uid = $_SESSION['Oid'];

// 一.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM " . DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");  //创建预编译对象
$stmt->bind_param('s', $uid);   //绑定参数
$stmt->execute();
if(!$stmt->affected_rows) {
    original_phone_request_response('401.1', '您的登录信息已过期，请您重新登录！');
}
$aUser = $stmt->get_result()->fetch_assoc();

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;    //转账金额
if($exchangeFrom == 'hg' && $exchangeTo == 'vg') {
    $action = 'hg2vg';
}
if($exchangeFrom == 'vg' && $exchangeTo == 'hg') {
    $action = 'vg2hg';
}

//判断终端类型
if($_REQUEST['appRefer'] == 13 || $_REQUEST['appRefer'] == 14) {
    $playSource = $_REQUEST['appRefer'];

    switch ($playSource){
        case 13:
            // 判断VG棋牌是否维护（安卓）
            $pageMark = 'vgqp';
            $aRow = getMaintainDataByCategory($pageMark);
            $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
            if ($aRow['state']==1 and in_array(13, $aTerminal)){
                $status = '555';
                $describe = $aRow['content'];
                original_phone_request_response($status,$describe);
            }
            break;
        case 14:
            // 判断VG棋牌电子是否维护（苹果）
            $pageMark = 'vgqp';
            $aRow = getMaintainDataByCategory($pageMark);
            $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
            if ($aRow['state']==1 and in_array(14, $aTerminal)){
                $status = '555';
                $describe = $aRow['content'];
                original_phone_request_response($status,$describe);
            }
            break;
    }
}
else{
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $playSource = 3;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $playSource = 4;
    }else{
        $playSource = 5;
    }

    // 判断VG棋牌是否维护（m版）
    $pageMark = 'vgqp';
    $aRow = getMaintainDataByCategory($pageMark);
    $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
    if ($aRow['state']==1 and in_array(1, $aTerminal)){
        $status = '555';
        $describe = $aRow['content'];
        original_phone_request_response($status,$describe);
    }
}

$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2vg' || $action == 'vg2hg'){
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
$testFlag = isset($_REQUEST['flag']) && $_REQUEST['flag'] ? trim($_REQUEST['flag']) : '';

/**
 * 3.检测VG会员
 *  获取Token
 *  会员不存在
 *      正式会员 1）create  2)插入vg_member_data  3）登录loginWithChannel
 *      试玩  登录
 *  会员存在登录
 */

$vgExist = 0;
$now = date('Y-m-d H:i:s');

// 获取apitoken
$redisObj = new Ciredis();
$apitoken = $redisObj->getSimpleOne('get_security_token');
if(!$apitoken) {
    //Array (
    //'state' => '0',
    //'message' => 'Success',
    //'value' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJVc2VySUQiOiJINzc3IiwiQXV0aFRpbWUiOiIyMDIwLTA3LTE0VDExOjM2OjQ5LjA1NTQ2MDkrMDg6MDAiLCJBdXRoSVAiOiI0My4yNDAuMjM5LjE1MCJ9.lEgCQnqf2IzSY31f9vXjidJcEXubelKQ91nnd11XU2I'
    //);
    $tokenResults = GetSecurityToken();
    if($tokenResults['state']  !== 0) {
        exit(json_encode(['code' => $tokenResults['state'], 'message' => 'token获取失败！']));
    }
    $apitoken = $tokenResults['value'];
    $redisObj->insert('get_security_token', $apitoken, 24*60*60);
}

$datastr = getVgQpSetting();
$agentChannel = $datastr['agentid'];

if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "vg_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $vgExist = mysqli_num_rows($result);
    if(!$vgExist){ // 未创建账号前请求余额接口
        if($action == 'b'){
            $data = [
                'vg_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            original_phone_request_response('200', '获取用户余额成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
        }else if($action == 'hg2vg' || $action == 'vg2hg'){ // 未创建账号前请求额度转换接口
            $aResult = createAccount($aUser , $apitoken); // 请求VG创建游戏账号接口
            
            if(in_array($aResult['state'] , array(0 , 602))) {  //state 0 创建成功  602 用户已存在
                $data = [
                    'userid' => $aUser['ID'],
                    'username' => $aUser['UserName'],
                    'channel' => $agentChannel,
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
                $sql = "INSERT INTO `" . DBPREFIX . "vg_member_data` SET $sInsData";
                if (!mysqli_query($dbMasterLink, $sql)) {
                    original_phone_request_response('401.2', 'VG棋牌账号添加异常，请您稍后重试！');
                }
            }else{
                original_phone_request_response('401.3', 'VG棋牌账号异常，请您稍后重试！');
            }
        }
    }
}

switch ($action){
    case "cm":  // 会员 免费试玩或立即游戏
        if($testFlag !== 'test')  { // 免费试玩不创建VG用户
            $cResult = createAccount($aUser , $apitoken); // 请求VG创建游戏账号接口
            if(!in_array($cResult['state'] , array(0 ,602))) {  //state 0 创建成功  602 用户已存在
                original_phone_request_response('401.31', 'VG棋牌创建账号异常，请您稍后重试！');
            }
        }
        $aResult = $testFlag == 'test' ? tryGame($apitoken) : loginGame($aUser , $apitoken); // 试玩 或 登录进入游戏

        if($aResult['state'] == 0) { // vg登录接口成功
            if($vgExist == 0 ){ // 若本地未添加VG账号，则添加
                if($testFlag !== 'test') {     //试玩本地不添加账号
                    $data = [
                        'userid' => $aUser['ID'],
                        'username' => $aUser['UserName'],
                        'channel' => $agentChannel,
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
                    $sql = "INSERT INTO `" . DBPREFIX . "vg_member_data` SET $sInsData";
                    if (!mysqli_query($dbMasterLink, $sql)) {
                        original_phone_request_response('401.4', 'VG棋牌账号添加异常，请您稍后重试！',$aData);
                    }
                }
            }else{ // 若有平台VG账号，则更新登录信息
                mysqli_query($dbMasterLink, "update " . DBPREFIX . "vg_member_data set launch_times = launch_times + 1, last_launch_time = '$now'  WHERE userid = '{$aUser['ID']}'");
            }
            $aResult['data']['url'] = $aResult['value'];
            original_phone_request_response('200', '恭喜！创建VG棋牌账号成功！', $aResult['data']);
        } else {
            original_phone_request_response('401.5', 'VG棋牌账号登录游戏异常，请您稍后重试！');
        }
        break;
    case 'b':   //点击棋牌游戏页面   额度转换
        $aResult = checkBalance($aUser['UserName'], $apitoken);
        $data = [];
        if($aResult['state'] == 0){ //获取余额成功
            $data = [
                'vg_balance' => sprintf('%.2f', $aResult['value']['Money']), // 返回vg会员账户余额
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            original_phone_request_response('200', '获取用户余额成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
        }else{
            original_phone_request_response('401.6', 'VG棋牌余额获取失败，请稍后重试！');
        }
        break;
    case 'hg2vg':
        // 1.参数校验

        if(!preg_match("/^[1-9][0-9]*$/", $score))
            original_phone_request_response('401.7', '转账金额格式错误，请重新输入!');

        if ($score > 10000000){
            original_phone_request_response('401.8', '单次上分不能超过一千万，请重新输入!');
	}

        // 2.事务处理
        $dbMasterLink->autocommit(false);

        // 2.1.事务内查询用户余额，后续用于更新用户余额
        $result = mysqli_query($dbMasterLink, 'SELECT `ID`, `Money` FROM '.DBPREFIX.MEMBERTABLE.' WHERE `ID` = ' . $aUser['ID'] . ' FOR UPDATE');
        $aForUpdate = mysqli_fetch_assoc($result);
        $beforeBalance = $aForUpdate['Money']; // 转换之前余额
        if(intval($beforeBalance) < intval($score)) { // 余额不足
            original_phone_request_response('401.9', '中心钱包不足!');
        }
        $afterBalance = bcsub($beforeBalance, $score, 4); // 转换之后余额

        // 更新会员余额
        if(!$updated = mysqli_query($dbMasterLink, 'UPDATE '.DBPREFIX.MEMBERTABLE.' SET `Money` = ' . $afterBalance . ' WHERE `ID` = ' . $aUser['ID'])) {
            $dbMasterLink->rollback();
            original_phone_request_response('401.10', '额度转换失败，请您稍后重试!');
        }
        // VG棋牌-orderId
        $time_str = date('YmdHis'); // 美东时间
        $orderId = $agentChannel . $time_str . $aUser['UserName'];

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
            'To' => 'vg',
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
            'reason' => 'HG TO VG',
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
            30, // type：30 VG棋牌 hg2vg
            $playSource, // 类型
            $insertId,
            'VG棋牌额度转换'
        ];

        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            original_phone_request_response('401.12', '额度转换失败，请您稍后重试!');
        }

        // 2.4.调用三方上分接口
        $params = [
            'action' => 'Deposit',
            'username' => $aUser['UserName'],
            'amount' => $score, // 元模式
            'serial' => $orderId,
            'apitoken' => $apitoken,
        ];
        $aResult = vgApi($params);
        if($aResult){
            if($aResult['state'] == 0){ // 存款成功
                $dbMasterLink->commit();
                $data = [
                    'vg_balance' => sprintf('%.2f', $aResult['value']['Money']), // 元模式
                    'hg_balance' => formatMoney($afterBalance)
                ];
                original_phone_request_response('200', '额度转换成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
            }else{
                $dbMasterLink->rollback();
                original_phone_request_response('401.13', '额度转换失败，请您稍后重试!');
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = checkOrder($aUser['UserName'], $orderId, $apitoken);
            if($aResult){
                if($aResult['state'] == 0) {
                    $dbMasterLink->commit();
                    $data = [
                        'vg_balance' => sprintf('%.2f', $aResult['value']['AfterBalance']), // 元模式
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
    case 'vg2hg':
        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score))
            original_phone_request_response('401.16', '转账金额格式错误，请重新输入!');

        if ($score > 10000000){
            original_phone_request_response('401.17', '单次下分不能超过一千万，请重新输入!');
	}

        // 2.查询VG棋牌可下分余额
        $vgBalance = '0.00';
        $aResult = checkBalance($aUser['UserName'], $apitoken);
        if(isset($aResult['state']) && $aResult['state'] == 0){
            $vgBalance = formatMoney($aResult['value']['Money']);
        }else{
            original_phone_request_response('401.18', 'VG棋牌余额获取失败，请稍后重试!');
        }
        if(intval($vgBalance) < intval($score))
            original_phone_request_response('401.19', 'VG棋牌余额不足!');

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
            original_phone_request_response('401.20', '额度转换失败，请您稍后重试!');
        }
        // VG棋牌-orderId
        $time_str = date('YmdHis'); // 美东时间
        $orderId = $agentChannel . $time_str . $aUser['UserName'];

        // 3.2.入库额度转换表
        $data = [
            'userid' => $aUser['ID'],
            'Checked' => 1,
            'Gold' => $score,
            'moneyf' => $beforeBalance,
            'currency_after' => $afterBalance,
            'AddDate' => $now,
            'Type' => 'Q',
            'From' => 'vg',
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
            'reason' => 'VG TO HG',
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
            31, // type：31VG棋牌
            $playSource, // 类型
            $insertId,
            'VG棋牌额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            original_phone_request_response('401.22', '额度转换失败，请您稍后重试!');
        }

        // 3.4.调用三方下分接口
        $params = [
            'action' => 'Withdraw',
            'username' => $aUser['UserName'],
            'amount' => $score, // 元模式
            'serial' => $orderId,
            'apitoken' => $apitoken,
        ];

        $aResult = vgApi($params);
        if($aResult){
            if($aResult['state'] == 0){
                $dbMasterLink->commit();
                $data = [
                    'vg_balance' => sprintf('%.2f', $aResult['value']['Money']), // 元模式
                    'hg_balance' => formatMoney($afterBalance)
                ];
                original_phone_request_response('200', '额度转换成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
            }else{
                $dbMasterLink->rollback();
                original_phone_request_response('401.23', '额度转换失败，请您稍后重试!');
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = checkOrder($aUser['UserName'] ,$orderId, $apitoken);
            if($aResult){
                if($aResult['state'] == 0) {
                    $dbMasterLink->commit();
                    $data = [
                        'vg_balance' => sprintf('%.2f', $aResult['value']['AfterBalance']), // 元模式
                        'hg_balance' => formatMoney($afterBalance)
                    ];
                    original_phone_request_response('200', '额度转换成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
                }else{
                    $dbMasterLink->rollback();
                    original_phone_request_response('401.24', '额度转换失败，请您稍后重试!');
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                original_phone_request_response('401.25', '额度转换失败，请您稍后重试!');
            }
        }
        break;
    default:
        original_phone_request_response('401.26', '抱歉，您的请求不予处理!');
        break;
}

/**
 * 获取 Token
 * @param action  GetToken
 * @return array|mixed
 */
function GetSecurityToken()
{
    $params = [
        'action' => 'GetToken', // 获取 Token
    ];
    $aResult = vgApi($params);
    return $aResult;
}


/**
 * 创建游戏账号
 * @param $aUser
 * @return array|mixed
 */
function createAccount($aUser , $apitoken)
{
    $params = [
        'action' => 'CreateUser', // 创建游戏账号
        'username' => $aUser['UserName'],
        'agent' => $aUser['Agents'],
        'apitoken' => $apitoken,
    ];
    $aResult = vgApi($params);
    return $aResult;
}

/**
 * 登录游戏
 * @param $aUser
 * @return array|mixed
 */
function loginGame($aUser , $apitoken)
{
    $params = [
        'action' => 'loginWithChannel', // 登录游戏
        'username' => $aUser['UserName'],
        'gametype'  => 1000,   //游戏类型  1000游戏大厅
        'apitoken' => $apitoken,
    ];
    $aResult = vgApi($params);
    return $aResult;
}

/**
 * 游戏试玩
 * @param $aUser
 * @return array|mixed
 */
function tryGame($apitoken)
{
    $params = [
        'action' => 'TryGame', // 登录游戏
        'gametype'  => 1000,   //游戏类型  1000游戏大厅
        'apitoken' => $apitoken,
    ];
    $aResult = vgApi($params);
    return $aResult;
}

/**
 * 查询可下分余额
 * @param $username
 * @return array|mixed
 */
function checkBalance($username , $apitoken)
{
    $params = [
        'action' => 'GetBalance',
        'username' => $username,
        'apitoken' => $apitoken,
    ];
    $aResult = vgApi($params);
    return $aResult;
}

/**
 * 查询额度转换订单
 * @param $username
 * @param $orderId
 * @return array|mixed
 */
function checkOrder($username, $orderId, $apitoken)
{
    $params = [
        'action' => 'GetTransRecord',
        'username' => $username,
        'serial' => $orderId,
        'apitoken' => $apitoken,
    ];
    $aResult = vgApi($params);
    return $aResult;
}

/**
 * 调用接口
 * @param $params
 * @return array|mixed
 */
function vgApi($params){
    //global $vgConfig;
    $objectVg = new ApiVg();
    try {
        ob_start();
        $res = $objectVg->main($params);
        return json_decode($res, true);
    } catch (Exception $e) {
        ob_end_clean();
        return array(
            'm' => 'vgProxy',
            's' => $params['action'],
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