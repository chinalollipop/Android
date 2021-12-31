<?php
/**
 * 自定义三方彩票API
 * Date: 2018/8/23
 */
define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
include_once "../config.inc.php";
include_once '../redis.php';
require_once ROOT_DIR.'/common/thirdcp/ApiCp.php';

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
if($exchangeFrom == 'hg' && $exchangeTo == 'gmcp')
    $action = 'hg2gmcp';
if($exchangeFrom == 'gmcp' && $exchangeTo == 'hg')
    $action = 'gmcp2hg';
$testFlag = isset($_REQUEST['flag']) && $_REQUEST['flag'] ? trim($_REQUEST['flag']) : '';

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
                'hg_balance' => floor($aUser['Money'])
            ];
            exit(json_encode(['code' => 0, 'data' => $data]));
        }else if($action == 'hg2gmcp' || $action == 'gmcp2hg'){ // 未创建账号前请求额度转换接口
            exit(json_encode(['code' => 2000, 'message' => '抱歉，请您先进入彩票游戏后再尝试转账！']));
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
                'hg_balance' => floor($aUser['Money'])
            ];
            exit(json_encode(['code' => 0, 'data' => $data]));
        }else{
            exit(json_encode(['code' => $aResult['errno'], 'message' => '三方彩票余额获取失败，请稍后重试！']));
        }
        break;
    case 'gmcp2hg':
        // 1.参数校验-后台资金归集不需要验证
//        if(!preg_match("/^[1-9][0-9]*$/", $score))
//            exit(json_encode([ 'code' => 2010, 'message' => '转账金额格式错误，请重新输入!' ]));

        if ($score > 10000000){
            exit(json_encode([ 'code' => 2011, 'message' => '单次下分不能超过一千万，请重新输入！' ]));
        }
        // 2.查询三方彩票可下分余额
        $aResult = checkBalance($aUser['UserName']);
        if(!$aResult['errno']){
            $gmcpBalance = $aResult['data']['money'];
        }else{
            exit(json_encode(['code' => 2012, 'message' => '三方彩票余额获取失败，请稍后重试！']));
        }
        if(intval($gmcpBalance) < intval($score))
            exit(json_encode(['code' => 2013, 'message' => '三方彩票余额不足！']));

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
            39, // type：39三方彩票
            6, // 后台管理
            $insertId,
            '后台管理'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            exit(json_encode(['code' => 2016, 'message' => '额度更新失败，请您稍后重试！']));
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
                    'hg_balance' => floor($afterBalance)
                ];
                exit(json_encode(['code' => 0, 'data' => $data]));
            }else{
                $dbMasterLink->rollback();
                exit(json_encode(['code' => 2017, 'message' => '额度转换失败，请您稍后重试！']));
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = checkOrder($aUser['UserName'] ,$orderId);
            if($aResult){
                if(!$aResult['errno']){
                    $dbMasterLink->commit();
                    $data = [
                        'gmcp_balance' => sprintf('%.2f', $aResult['data']['money']),
                        'hg_balance' => floor($afterBalance)
                    ];
                    exit(json_encode(['code' => 0, 'data' => $data]));
                }else{
                    $dbMasterLink->rollback();
                    exit(json_encode(['code' => 2009, 'message' => '额度转换失败，请您稍后重试！']));
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