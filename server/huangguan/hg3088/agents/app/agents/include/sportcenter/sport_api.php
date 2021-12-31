<?php
/**
 * 自定义皇冠体育API
 * Date: 2019/09/27
 */
define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
include_once "../config.inc.php";
include_once '../redis.php';
require_once ROOT_DIR.'/common/sc/ApiSportCenter.php';

$username = isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM " . DBPREFIX.MEMBERTABLE." where `UserName` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
if(!$stmt->affected_rows) {
    exit(json_encode( ['code' => '422', 'message' => '您的登录信息已过期，请您重新登录！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$key = isset($_REQUEST['key']) && $_REQUEST['key'] ? trim($_REQUEST['key']) : ''; // 登录密码（加密）
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
if($exchangeFrom == 'hg' && $exchangeTo == 'sc')
    $action = 'hg2sc';
if($exchangeFrom == 'sc' && $exchangeTo == 'hg')
    $action = 'sc2hg';
$testFlag = isset($_REQUEST['flag']) && $_REQUEST['flag'] ? trim($_REQUEST['flag']) : '';

// 3.检测登录皇冠体育会员
$scExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "sc_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $scExist = mysqli_num_rows($result);
    if(!$scExist){ // 未创建账号前请求余额接口
        if($action == 'b'){
            $data = [
                'sc_balance' => '0.00',
               // 'hg_balance' => sprintf('%.2f', $aUser['Money'])
                'hg_balance' => floor($aUser['Money'])
            ];
            exit(json_encode(['code' => 0, 'data' => $data]));
        }else if($action == 'hg2sc' || $action == 'sc2hg'){ // 未创建账号前请求额度转换接口
            exit(json_encode(['code' => 2001, 'message' => '抱歉，请您先进入皇冠体育后再尝试转账！']));
        }
    }
}

switch ($action){
    case 'b':
        $aResult = checkBalance($aUser['UserName']);
        $data = [];
        if($aResult['code'] == 1){
            $data = [
                'sc_balance' => sprintf('%.2f', $aResult['data']['money']),
                'hg_balance' => floor($aUser['Money'])
            ];
            exit(json_encode(['code' => 0, 'data' => $data]));
        }else{
            exit(json_encode(['code' => $aResult['code'], 'message' => '皇冠体育余额获取失败，请稍后重试！']));
        }
        break;
    case 'sc2hg':
        // 1.参数校验-后台资金归集不需要验证
//        if(!preg_match("/^[1-9][0-9]*$/", $score))
//            exit(json_encode([ 'code' => 2010, 'message' => '转账金额格式错误，请重新输入!' ]));

        if ($score > 10000000){
            exit(json_encode([ 'code' => 2011, 'message' => '单次下分不能超过一千万，请重新输入！' ]));
        }
        // 2.查询皇冠体育可下分余额
        $aResult = checkBalance($aUser['UserName']);
        if(isset($aResult['code']) && $aResult['code'] == 1){
            $scBalance = $aResult['data']['money'];
        }else{
            exit(json_encode(['code' => 2012, 'message' => '皇冠体育余额获取失败，请稍后重试！']));
        }
        if(intval($scBalance) < intval($score))
            exit(json_encode(['code' => 2013, 'message' => '皇冠体育余额不足！']));

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
        // 皇冠体育-orderId
        $orderId = uniqid($aUser['UserName'], true);

        // 3.2.入库额度转换表
        $data = [
            'userid' => $aUser['ID'],
            'Checked' => 1,
            'Gold' => $score,
            'moneyf' => $beforeBalance,
            'currency_after' => $afterBalance,
            'AddDate' => $now,
            'Type' => 'Q',
            'From' => 'sc',
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
            'reason' => 'TY TO SC',
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
            47, // type：皇冠体育
            22, //'投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓,22 综合版',
            $insertId,
            '皇冠体育额度转换'
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
        $aResult = sportApi($params);
        if($aResult){
            if($aResult['code'] == 1){
                $dbMasterLink->commit();
                $data = [
                    'sc_balance' => sprintf('%.2f', $aResult['data']['money']),
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
                if($aResult['code'] == 1) {
                    $dbMasterLink->commit();
                    $data = [
                        'sc_balance' => sprintf('%.2f', $aResult['data']['money']),
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
    $aResult = sportApi($params);
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
        'orderid' => $orderId,
    ];
    $aResult = sportApi($params);
    return $aResult;
}

/**
 * 调用接口
 * @param $params
 * @return array|mixed
 */
function sportApi($params){
    $osc = new ApiSportCenter();
    try {
        ob_start();
        $res = $osc->main($params);
        return json_decode($res, true);
    } catch (Exception $e) {
        ob_end_clean();
        return array(
            'm' => 'scProxy',
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
