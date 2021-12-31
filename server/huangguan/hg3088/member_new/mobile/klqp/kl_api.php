<?php
/**
 * 自定义快乐棋牌API
 *
 *  执行任务 action （默认检查快乐棋牌账号，或者创建账号）
 *       b  获取余额
 *       hg2kl  平台上分到kl
 *       kl2hg  kl下分到平台
 *       getLaunchGameUrl  真钱模式
 */

error_reporting(E_ALL);
ini_set('display_errors','Off');
include_once('../include/config.inc.php');
include_once ROOT_DIR.'/common/klqp/api.php';

if(!isset($_SESSION['Oid']) || $_SESSION['Oid'] == ''){
    $status = '401.1';
    $describe = '您的登录信息已过期，请您重新登录！';
    original_phone_request_response($status,$describe);
}

$uid = $_SESSION['Oid']?$_SESSION['Oid']:$_REQUEST['uid'];

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `LoginName`,  `Alias`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM ".DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();

if(!$stmt->affected_rows) {
    $status = '401.2';
    $describe = '您的登录信息已过期，请您重新登录！';
    original_phone_request_response($status,$describe);
}
$aUser = $stmt->get_result()->fetch_assoc();

if ($aUser['test_flag'] || ($_SESSION['Agents']=='demoguest') ){
    $status = '401.2';
    $describe = '请使用真实账号登入快乐棋牌';
    original_phone_request_response($status,$describe);
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
$_REQUEST['appRefer'] =  $platform = isset($_REQUEST['appRefer']) && $_REQUEST['appRefer'] ? trim($_REQUEST['appRefer']) : 5;
if($exchangeFrom == 'hg' && $exchangeTo == 'kl'){
    $action = 'hg2kl';
}

if($exchangeFrom == 'kl' && $exchangeTo == 'hg'){
    $action = 'kl2hg';
}
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2kl' || $action == 'kl2hg'){
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

//判断终端类型
if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
    $playSource=$_REQUEST['appRefer'];

    switch ($platform){
        case 13:
            // 判断快乐棋牌是否维护（安卓）
            $pageMark = 'klqp';
            $aRow = getMaintainDataByCategory($pageMark);
            $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
            if ($aRow['state']==1 and in_array(13, $aTerminal)){
                $status = '555';
                $describe = '快乐棋牌维护中，请选择其他游戏';
                original_phone_request_response($status,$describe);
            }
            break;
        case 14:
            // 判断快乐棋牌是否维护（苹果）
            $pageMark = 'klqp';
            $aRow = getMaintainDataByCategory($pageMark);
            $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
            if ($aRow['state']==1 and in_array(14, $aTerminal)){
                $status = '555';
                $describe = '快乐棋牌维护中，请选择其他游戏';
                original_phone_request_response($status,$describe);
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

    // 判断快乐棋牌是否维护（m版）
    $pageMark = 'klqp';
    $aRow = getMaintainDataByCategory($pageMark);
    $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
    if ($aRow['state']==1 and in_array(1, $aTerminal)   and ($_REQUEST['action'] != 'b') ){
        $status = '555';
        $describe = '快乐棋牌维护中，请选择其他游戏';
        original_phone_request_response($status,$describe);
    }
}


/* *
 *  3.检测登录快乐棋牌会员
 *   (查询玩家账号是否存在) 查询数据表或者 check接口
 * */
$klExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "kl_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $klExist = mysqli_num_rows($result);
    if(!$klExist){ // 未创建账号前请求余额接口
        if($action == 'b'){
            $data = [
                'kl_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            original_phone_request_response('200', '获取用户余额成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
        } else { // 未创建账号前请求额度转换接口

            $aResult = loginUser($aUser['UserName'] , $klqp_merchant['merchantId']); // 请求商户登录接口自动创建

            if($aResult['success'] && is_array($aResult['body'])) {  //success 1 创建成功
                $data = [
                    'userid' => $aUser['ID'],
//                    'username' => explode('_', $aResult['body']['username'],2)['1'],
                    'username' => $aUser['UserName'],
                    'channel' => $klqp_merchant['merchantname'],
                    'agents' => $aUser['Agents'],
                    'world' => $aUser['World'],
                    'corporator' => $aUser['Corprator'],
                    'super' => $aUser['Super'],
                    'admin' => $aUser['Admin'],
                    'register_time' => $now,
                    'last_launch_time' => $now,
                    'launch_times' => 1,
                    'token' => $aResult['body']['token'],
                    'is_test' => $aUser['test_flag']
                ];
                $sInsData = '';
                foreach ($data as $key => $value){
                    $sInsData .= "`$key` = '{$value}'" . ($key == 'is_test' ? '' : ',');
                }
                $sql = "INSERT INTO `" . DBPREFIX . "kl_member_data` SET $sInsData";
                if (!mysqli_query($dbMasterLink, $sql)) {
                    original_phone_request_response('401.3','账号添加异常，请您稍后重试！');
                }else{
                    original_phone_request_response('200','棋牌账号初始化成功，请您继续转账！');
                }
            }else{
                original_phone_request_response('401.3','商户棋牌创建账号异常，请您稍后重试！');
            }
        }
    }
}

$result = mysqli_query($dbLink, "SELECT `userid`,`username`,`channel`,`token`  FROM `" . DBPREFIX . "kl_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
$res = mysqli_fetch_assoc($result);
$playUser = $res['username']; // 棋牌账号

switch ($action){
    case "cm":  // 会员 免费试玩或立即游戏
        if($testFlag !== 'test') { // 正式会员
            $aResult = loginUser($playUser , $klqp_merchant['merchantId']); //更新token
            if($klExist && $aResult['success']){ // 账号存在，Player登入成功 , 更新会员信息
                $token = $aResult['body']['token'];
                mysqli_query($dbMasterLink, "update " . DBPREFIX . "kl_member_data set launch_times = launch_times + 1, last_launch_time = '$now', token = '$token'  WHERE userid = '{$aUser['ID']}'");
            }

            if($aResult['success'] == false) {
                original_phone_request_response('500','更新用户token失败！');
            }

            $userInfo['username'] = $playUser; // 名称
            $userInfo['user_id'] = $aResult['body']['userId'];

            $gResult = getGamelobby($userInfo , $klqp_merchant['merchantId'] , $token); //游戏大厅地址

            if (!$gResult['success']){
                original_phone_request_response('500.01','游戏链接获取失败!');
            }

            $gResult['data']['url'] = $gResult['body']['gameUrl'];

            original_phone_request_response('200','登录成功！',$gResult['data']);

        }else {
            original_phone_request_response('501','请用正式账号登录！');
        }

        break;
    case 'b': //  额度转换
        // 查询玩家筹码
        $res = getPlayerInfo($playUser , $klqp_merchant['merchantId']);
        if (!$res['success']){
            original_phone_request_response('502','余额获取失败!');
        }
        else{
            $data = [
                'kl_balance' => sprintf('%.2f', $res['body']['balance']), // 元模式
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            original_phone_request_response('200', '获取用户余额成功！', $playSource == 13 || $playSource == 14 ? [$data] : $data);
        }

        break;
    case 'hg2kl':
        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score)) {
            $status = '401.7';
            $describe = '转账金额格式错误，请重新输入!';
            original_phone_request_response($status,$describe);
        }

        if ($score > 10000000){
            $status = '401.8';
            $describe = '单次上分不能超过一千万，请重新输入!';
            original_phone_request_response($status,$describe);
        }

        // 查询快乐棋牌余额 负数不让转
        $aResult = getPlayerInfo($playUser , $klqp_merchant['merchantId']);// 查询额度
        if ($aResult['success'] == true){
            $klBalance = sprintf('%.2f', $aResult['body']['balance']);  // 元模式
            if($klBalance < 0) {
                original_phone_request_response('401.81', '请查看该用户额度在转入！');
            }

            if ($aResult['body']['is_online'] != 0){  //是否在线，哪款游戏在线
                original_phone_request_response('401.82', '三方游戏在线中,稍后再转！');
            }
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

        // KLQP-orderId
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        //$sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $orderId = date('YmdHi') . $sTime8; // 订单号生成规则

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
            'To' => 'kl',
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
            'reason' => 'HG TO KLQP',
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
            57, // type：57 体育到快乐棋牌
            1, // 旧版本
            $insertId,
            '体育到快乐棋牌'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            $status = '401.12';
            $describe = '额度转换失败，请您稍后重试！';
            original_phone_request_response($status,$describe);
        }

        // 2.4.调用三方上分接口
        //response -{"errno":"0","errstr":"","username":"hgtest_john103","pre_balance":"27.00","balance":"29","ref_id":"D20200218002248886034"}
        $aResult = transferIn($playUser , $klqp_merchant['merchantId'], $score);
        if($aResult){
            if($aResult['success'] == true){
                $dbMasterLink->commit();

                $data = [
                    'kl_balance' => sprintf('%.2f', $aResult['body']['balance']),
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
        }else{ // 转账失败 超时or错误返回NULL，则查询订单状态
            //response -{"errno":"0","errstr":"","ref_id":"D20200218002248886034","username":"hgtest_john103","amount":"2","status":"8","create_time":"2020-02-18 00:22:48"}
            $tResult = transferOrder($aResult['body']['ref_id'], $klqp_merchant['merchantId'] ); //单一交易查詢
            if($tResult){
                if($tResult['success'] == false){
                    $dbMasterLink->rollback();
                    $status = '401.14';
                    $describe = '额度转换失败，请您稍后重试！';
                    original_phone_request_response($status,$describe);
                }
                if($tResult['success'] == true){
                    $dbMasterLink->commit();

                    $playRes = getPlayerInfo($playUser , $klqp_merchant['merchantId']);// 查询额度
                    if ($playRes['success']){
                        $pResult['body']['balance'] = sprintf('%.2f', $playRes['body']['balance']); // 元模式
                    }
                    $data = [
                        'kl_balance' => sprintf('%.2f', $pResult['body']['balance']),
                        'hg_balance' => formatMoney($afterBalance)
                    ];
                    $status = '200';
                    $describe = '额度转换成功';
                    original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                original_phone_request_response('401.15', '额度转换失败，请您稍后重试！');
            }
        }
        break;
    case 'kl2hg':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score)) {
            original_phone_request_response('401.16', '转账金额格式错误，请重新输入!');
        }

        if ($score > 10000000){
            original_phone_request_response('401.17', '单次下分不能超过一千万，请重新输入！');
        }
        // 2.查询快乐棋牌可下分余额
        $aResult = getPlayerInfo($playUser , $klqp_merchant['merchantId']);// 查询额度
        if ($aResult['success'] == true){
            $klBalance = sprintf('%.2f', $aResult['body']['balance']);  // 元模式
        } else{
            original_phone_request_response('401.18', '快乐棋牌余额获取失败，请稍后重试！');
        }
        if ($aResult['body']['is_online'] != 0){  //是否在线，哪款游戏在线
            original_phone_request_response('401.181', '三方游戏在线中,稍后再转！');
        }

        if(intval($klBalance) < intval($score)) {
            original_phone_request_response('401.19', '快乐棋牌余额不足！');
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
        // 快乐棋牌-orderId
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $orderId = date('YmdHi') . $sTime8; // 订单号生成规则

        // 3.2.入库额度转换表
        $data = [
            'userid' => $aUser['ID'],
            'Checked' => 1,
            'Gold' => $score,
            'moneyf' => $beforeBalance,
            'currency_after' => $afterBalance,
            'AddDate' => $now,
            'Type' => 'Q',
            'From' => 'kl',
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
            'reason' => 'KLQP TO HG',
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
            58, // type：58 快乐棋牌到体育
            1, // 旧版本
            $insertId,
            '快乐棋牌到体育'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();

            $status = '401.22';
            $describe = '额度更新失败，请您稍后重试！';
            original_phone_request_response($status,$describe);
        }

        // 3.4.调用三方下分接口
        $aResult = transferOut($playUser , $klqp_merchant['merchantId'], $score);
        if($aResult){
            if($aResult['success'] == true){
                $dbMasterLink->commit();

                $data = [
                    'kl_balance' => sprintf('%.2f', $aResult['body']['balance']),
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
            $tResult = transferOrder($aResult['body']['ref_id'], $klqp_merchant['merchantId'] ); //单一交易查詢
            if($tResult){
                if($tResult['success'] == false){
                    $dbMasterLink->rollback();

                    $status = '401.24';
                    $describe = '额度转换失败，请您稍后重试！';
                    original_phone_request_response($status,$describe);
                }
                if($tResult['success'] == true){
                    $dbMasterLink->commit();

                    $playRes = getPlayerInfo($playUser , $klqp_merchant['merchantId']);// 查询额度
                    if ($playRes['success']){
                        $pResult['body']['balance'] = sprintf('%.2f', $playRes['body']['balance']); // 元模式
                    }
                    $data = [
                        'kl_balance' => sprintf('%.2f', $pResult['body']['balance']),
                        'hg_balance' => formatMoney($afterBalance)
                    ];
                    $status = '200';
                    $describe = '额度转换成功！';
                    original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                $status = '401.25';
                $describe = '额度转换失败，请您稍后重试！';
                original_phone_request_response($status,$describe);
            }
        }
        break;
    /*case 'getLaunchGameUrl':    //进入游戏
        $game_id = $_REQUEST['game_id'] ? $_REQUEST['game_id'] : $hallGameId;  // 默认大厅

        $aResult = loginUser($playUser , $klqp_merchant['merchantId']); //更新token
        if($aResult['success'] == false) {
            original_phone_request_response('401.26','更新用户token失败!');
        }

        $userInfo['username'] = $playUser; // 名称
        $userInfo['user_id'] = $aResult['body']['userId'];

        $gResult = getGamelobby($userInfo , $klqp_merchant['merchantId'] , $token, $platform); //游戏大厅地址
        //exit(json_encode(['code' => 0, 'success' => $gResult['success'], 'url' => $gResult['body']['gameUrl']]));

        if (!$gResult['success']){
            original_phone_request_response('401.27','游戏链接获取失败!');
        }

        $toURL = $gResult['body']['gameUrl'];
        $data[0]['toUrl'] = $toURL;

        if($platform==13 || $platform==14) {
            $status = '200';
            $describe = '恭喜成功获取APP地址';
            original_phone_request_response($status,$describe,$data);
        }
        else{
            header("Location:".$toURL);
        }
        break;*/
    default:
        $status = '401.26';
        $describe = '抱歉，您的请求不予处理！';
        original_phone_request_response($status,$describe);
        break;
}


function printLine ($message) {
    echo "<BR>{$message}";
}
