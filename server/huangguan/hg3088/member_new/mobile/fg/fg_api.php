<?php
/**
 * 自定义FG电子API
 *
 *  执行任务 action （默认检查FG账号，或者创建账号）
 *       b  获取余额
 *       hg2fg  平台上分到fg
 *       fg2hg  fg下分到平台
 *       getLaunchGameUrl  真钱模式
 *       getDemoLaunchGameUrl  试玩模式
 */

//error_reporting(E_ALL);
//ini_set('display_errors','Off');
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
include_once "../include/config.inc.php";
include_once ROOT_DIR.'/common/fg/api.php';

$uid = $_SESSION['Oid']?$_SESSION['Oid']:$_REQUEST['uid'];
$userid = $_SESSION['userid'] ;
$platform = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:'' ;
$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:'' ; // 兼容改版手机版 进入游戏


// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `LoginName`,  `Alias`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM ".DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();

if(!$stmt->affected_rows) {
    $status = '401.1';
    $describe = '您的登录信息已过期，请您重新登录！';
    original_phone_request_response($status,$describe);
}
$aUser = $stmt->get_result()->fetch_assoc();

if ($aUser['test_flag']){
//    exit("<script>alert('请登录真实账号登入FG Gaming');window.close();</script>");
    $status = '401.2';
    $describe = '请使用真实账号登入FG Gaming';
    original_phone_request_response($status,$describe);
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$b = $score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
$_REQUEST['appRefer'] =  $platform = isset($_REQUEST['appRefer']) && $_REQUEST['appRefer'] ? trim($_REQUEST['appRefer']) : 5;

if($exchangeFrom == 'hg' && $exchangeTo == 'fg'){
    $action = 'hg2fg';
}

if($exchangeFrom == 'fg' && $exchangeTo == 'hg'){
    $action = 'fg2hg';
}
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2fg' || $action == 'fg2hg'){
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
            // 判断FG电子是否维护（安卓）
            $pageMark = 'fg';
            $aRow = getMaintainDataByCategory($pageMark);
            $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
            if ($aRow['state']==1 and in_array(13, $aTerminal)){
                $status = '555';
                $describe = 'FG电子维护中，请选择其他游戏';
                original_phone_request_response($status,$describe);
            }
            break;
        case 14:
            // 判断MW电子是否维护（苹果）
            $pageMark = 'fg';
            $aRow = getMaintainDataByCategory($pageMark);
            $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
            if ($aRow['state']==1 and in_array(14, $aTerminal)){
                $status = '555';
                $describe = 'FG电子维护中，请选择其他游戏';
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

    // 判断FG电子是否维护（m版）
    $pageMark = 'fg';
    $aRow = getMaintainDataByCategory($pageMark);
    $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
    if ($aRow['state']==1 and in_array(1, $aTerminal)){
        $status = '555';
        $describe = 'FG电子维护中，请选择其他游戏';
        original_phone_request_response($status,$describe);
    }
}

/* *
 *  3.检测登录FG会员
 *   (查询玩家账号是否存在) 查询数据表或者 check接口
 * */
$fgExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    // 账号是否记录
    $result = mysqli_query($dbLink, "SELECT `userid`,`username`,`password`,`ext_ref`,`openid`  FROM `" . DBPREFIX . "fg_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $fgExist = mysqli_num_rows($result);
    $res = mysqli_fetch_assoc($result);

    $account = $fg_prefix.$aUser['UserName'];
    /*$password = $fgExist ? $res['password'] : '';
    $nickname = $aUser['LoginName'] ? $aUser['LoginName'] : '';*/
    $checkResult = checkPlayerNames($api_merchant, $account);  //检测账号是否存在
    $checkExist = $checkResult["success"];
    /*if($checkExist) {
        $checkOpenId = $checkResult['body']['openid'];
    }*/

    if(!$fgExist || !$checkExist) { // 接口或者数据表账号不存在
        if($action == 'b'){ // 未创建账号前请求余额接口
            $data = [
                'fg_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];

            $status = '200';
            $describe = '获取余额成功！';
            original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
        }else{
            $length = rand(6,10);
            $member_password = make_char($length);  //玩家密码

            if(!$checkExist && !$fgExist) { //接口账号、数据表账号不存在
                $createRes = v3Players($api_merchant, $account, $member_password); // 请求创建游戏账号接口
            }else if($checkExist && !$fgExist) { //接口账号存在， 数据表账号不存在
                $createRes['body']['openid'] = $checkResult['body']['openid'];
            }


            if ($createRes['success'] || !$fgExist){ // 创建或更改成功
                $data = [
                    'userid' => $aUser['ID'],
                    'username' => $account,
                    'password' => $member_password, //$member_password
                    'ext_ref' => $fg_member_ext_ref,
                    'agents' => $aUser['Agents'],
                    'world' => $aUser['World'],
                    'corporator' => $aUser['Corprator'],
                    'super' => $aUser['Super'],
                    'admin' => $aUser['Admin'],
                    'register_time' => $now,
                    'last_launch_time' => $now,
                    'launch_times' => 1,
                    'is_test' => $aUser['test_flag'],
                    'openid' => $createRes['body']['openid'],
                ];
                $sInsData = '';
                foreach ($data as $key => $value){
                    $sInsData .= "`$key` = '{$value}'" . ($key == 'openid' ? '' : ',');
                }
                $sql = "INSERT INTO `" . DBPREFIX . "fg_member_data` SET $sInsData";
                if (!mysqli_query($dbMasterLink, $sql)) {

                    $status = '401.5';
                    $describe = '插入FG账号异常，请您稍后重试！';
                    original_phone_request_response($status,$describe);
                }else{
                    $status = 222;
                    $describe = 'FG账号初始化成功，请您继续转账';
                    original_phone_request_response($status,$describe);
                }
            }else{
                $status = '401.6';
                $describe = '创建账号失败,请重试！';
                original_phone_request_response($status,$describe);
            }
        }
    }
}

$result = mysqli_query($dbLink, "SELECT `userid`,`username`,`password`,`ext_ref`,`openid`  FROM `" . DBPREFIX . "fg_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
$res = mysqli_fetch_assoc($result);
$openid = strval($res['openid']);   // 玩家openid

switch ($action){
    case "cm":  // 会员 免费试玩或立即游戏
        if($testFlag !== 'test') { // 正式会员

            if($fgExist && $openid){ // 账号存在，Player登入成功 , 更新会员信息
                mysqli_query($dbMasterLink, "update " . DBPREFIX . "fg_member_data set launch_times = launch_times + 1, last_launch_time = '$now'  WHERE userid = '{$aUser['ID']}'");
            }
            $linkRes = launch_lobby($api_merchant, $openid, $language, $owner_id);
        }

        if($linkRes['success'] == true) {
            //exit(json_encode(['code' => 0, 'success' => $linkRes['success'], 'url' => $linkRes['body']['url']]));
            //header("Location:".$linkRes['body']['url']);

            if($playSource==13 || $playSource==14) {
                $status = '200';
                $describe = 'success';
                $aData['url'] = $linkRes['body']['lobby_url'];
                original_phone_request_response($status,$describe,$aData);
            }else {
                header("Location:".$linkRes['body']['lobby_url']);
            }
        }else {
            //exit(json_encode(['err' => -500, 'msg' => '进入大厅失败！']));
            original_phone_request_response(-500,"进入大厅失败！");
        }


        break;
    case 'b': //点击电子游艺   FG额度转换

        // 查询玩家筹码   //{"code":0,"data":{"balance":0,"currency":"CNY"},"msg":"success"}
        $res = getPlayerChips($api_merchant, $openid);
        if (!$res['success']){
            $status = '401.7';
            $describe = '余额获取失败，请联系技术';
            original_phone_request_response($status,$describe);
        }
        else{
            $data = [
                'fg_balance' => sprintf('%.2f', $res['body']['balance'] / 100), // 分模式转元模式
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            $status = '200';
            $describe = '获取余额成功！';
            original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
        }

        break;
    case 'hg2fg':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score))
            original_phone_request_response('401.8','转账金额格式错误，请重新输入!');

        if ($score > 10000000){
            original_phone_request_response('401.9','单次上分不能超过一千万，请重新输入！');
        }

        // 2.事务处理
        $dbMasterLink->autocommit(false);

        // 2.1.事务内查询用户余额，后续用于更新用户余额
        $result = mysqli_query($dbMasterLink, 'SELECT `ID`, `Money` FROM '.DBPREFIX.MEMBERTABLE.' WHERE `ID` = ' . $aUser['ID'] . ' FOR UPDATE');
        $aForUpdate = mysqli_fetch_assoc($result);
        $beforeBalance = $aForUpdate['Money']; // 转换之前余额
        if(intval($beforeBalance) < intval($score)) { // 余额不足
            original_phone_request_response('2004','皇冠体育余额不足！');
        }
        $afterBalance = bcsub($beforeBalance, $score, 4); // 转换之后余额

        // 更新会员余额
        if(!$updated = mysqli_query($dbMasterLink, 'UPDATE '.DBPREFIX.MEMBERTABLE.' SET `Money` = ' . $afterBalance . ' WHERE `ID` = ' . $aUser['ID'])) {
            $dbMasterLink->rollback();
            original_phone_request_response('2005','额度转换失败，请您稍后重试！');
        }

        // FG-orderId
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
            'To' => 'fg',
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
            'reason' => 'HG TO FG',
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
            original_phone_request_response('2006','额度转换失败，请您稍后重试！');
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
            48, // type：48 体育到FG电子
            $playSource, // 3 4 13 14
            $insertId,
            'FG电子真人额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            original_phone_request_response('2007','额度转换失败，请您稍后重试！');
        }

        // 2.4.调用三方上分接口
        //{"data":{"balance":10,"currency":"CNY"},"status":{"code":"0","message":"Success","datetime":"2019-10-16T06:13:54-04:00","traceCode":"bBCuoaf7ws"}}
        $score = $score * 100;  // 元模式转分模式
        $aResult = getPlayerUchips ($api_merchant, $openid, $score, $orderId);
        if($aResult){
            if($aResult['success'] == true){
                $dbMasterLink->commit();

                $chipsRes = getPlayerChips($api_merchant, $openid);      // 查询额度
                if ($chipsRes['success']){
                    $aResult['body']['balance'] = sprintf('%.2f', $chipsRes['body']['balance'] / 100); // 分模式转元模式
                }
                $data = [
                    'fg_balance' => sprintf('%.2f', $aResult['body']['balance']),
                    'hg_balance' => formatMoney($afterBalance)
                ];
                $status = '200';
                $describe = '恭喜上分成功';
                original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
            }else{
                $dbMasterLink->rollback();
                original_phone_request_response('2008.01','额度转换失败，请您稍后重试！');
            }
        }else{ // 转账失败 超时or错误返回NULL，则查询订单状态
            //response -{"code":0,"data":{"amount":1,"end_chips":25,"externaltransactionid":"2019110523165dc23b08","member_code":"john104","time":1573010184},"msg":"success"}
            $aResult = playerUchipsCheck($api_merchant,  $orderId); //单一交易查詢
            if($aResult){
                if($aResult['success'] == false){
                    $dbMasterLink->rollback();
                    original_phone_request_response('2009','额度转换失败，请您稍后重试！');
                    break;
                }
                if($aResult['success'] == true){
                    $dbMasterLink->commit();

                    $chipsRes = getPlayerChips($api_merchant, $openid);      // 查询额度
                    if ($chipsRes['success']){
                        $aResult['body']['balance'] = sprintf('%.2f', $chipsRes['body']['balance'] / 100); // 分模式转元模式
                    }
                    $data = [
                        'fg_balance' => sprintf('%.2f', $aResult['body']['balance']),
                        'hg_balance' => formatMoney($afterBalance)
                    ];
                    $status = '200';
                    $describe = '获取余额成功！';
                    original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
                    break;
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                original_phone_request_response('400','额度转换失败，请您稍后重试！');
            }
        }
        break;
    case 'fg2hg':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score))
            original_phone_request_response('401','转账金额格式错误，请重新输入!');

        if ($score > 10000000){
            original_phone_request_response('2011','单次下分不能超过一千万，请重新输入！');
        }
        // 2.查询FG电子可下分余额
        $aResult = getPlayerChips($api_merchant, $openid);      // 查询额度
        if ($aResult['success'] == true){
            $fgBalance = sprintf('%.2f', $aResult['body']['balance'] / 100);
        } else{
            original_phone_request_response('2022','FG电子余额获取失败，请稍后重试！');
        }

        if(intval($fgBalance) < intval($score))
            original_phone_request_response('2023','FG电子余额不足！');

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
            original_phone_request_response('2024','额度更新失败，请您稍后重试！');
        }
        // FG电子真人-orderId
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        //$sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
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
            'From' => 'fg',
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
            'reason' => 'FG TO HG',
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
            original_phone_request_response('2025','额度更新失败，请您稍后重试！');
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
            49, // type：49 FG电子到体育
            $playSource, // 3 4 13 14
            $insertId,
            'FG电子额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            original_phone_request_response('2026','额度更新失败，请您稍后重试！');
        }

        // 3.4.调用三方下分接口
        //$aResult = playWithdraw ($api_token, $account, $score, $orderId);
        $score = $score * 100;  // 元模式转分模式
        $aResult = getPlayerUchips ($api_merchant, $openid, -($score), $orderId);
        if($aResult){
            if($aResult['success'] == true){
                $dbMasterLink->commit();
                $chipsRes = getPlayerChips($api_merchant, $openid);      // 查询额度
                if ($chipsRes['success']){
                    $aResult['body']['balance'] = sprintf('%.2f', $chipsRes['body']['balance'] / 100);  // 分模式转元模式
                }
                $data = [
                    'fg_balance' => sprintf('%.2f', $aResult['body']['balance']),
                    'hg_balance' => formatMoney($afterBalance)
                ];
                $status = '200';
                $describe = '恭喜下分成功';
                original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
            }else{
                $dbMasterLink->rollback();
                original_phone_request_response('2027','额度转换失败，请您稍后重试！');
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = playerUchipsCheck($api_merchant,  $orderId); //单一交易查詢
            if($aResult){
                if($aResult['success'] == false){
                    $dbMasterLink->rollback();
                    original_phone_request_response('2009','额度转换失败，请您稍后重试！');
                    break;
                }
                if($aResult['success'] == true){
                    $dbMasterLink->commit();

                    $chipsRes = getPlayerChips($api_merchant, $openid);      // 查询额度
                    if ($chipsRes['success']){
                        $aResult['body']['balance'] = sprintf('%.2f', $chipsRes['body']['balance'] / 100); // 分模式转元模式
                    }
                    $data = [
                        'fg_balance' => sprintf('%.2f', $aResult['body']['balance']),
                        'hg_balance' => formatMoney($afterBalance)
                    ];
                    $status = '200';
                    $describe = '获取余额成功！';
                    original_phone_request_response($status,$describe,$playSource == 13 || $playSource == 14 ? [$data] : $data);
                    break;
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                original_phone_request_response('2019','额度转换失败，请您稍后重试！');
            }
        }
        break;
    case 'getDemoLaunchGameUrl':    //单个游戏试玩
        $game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:2001;  // 默认变形金刚
        if($platform==13 || $platform==14) {
            $game_type = 'app';
        }else {
            $game_type = 'h5'; //h5
        }

        $res = V3LaunchFreeGame ($api_merchant, $game_id, $game_type, $language, $owner_id);

        if (!$res['success']){
            exit("<script>alert('游戏链接获取失败".json_encode($res)."');window.close();</script>");
        }

        $toURL = $res['body']['game_url'] . '&token=' . $res['body']['token'];
        $data[0]['toUrl'] = $toURL;

        if($platform==13 || $platform==14) {
            $status = '200';
            $describe = '恭喜成功获取APP试玩地址';
            original_phone_request_response($status,$describe,$data);
        }
        else{
            header("Location:".$toURL);
        }
        break;
    case 'getLaunchGameUrl':    //进入单个游戏
        $game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:2001;  // 默认变形金刚
        if($platform==13 || $platform==14) {
            $game_type = 'app';
        }else {
            $game_type = 'h5'; //h5
        }

        $res = V3LaunchGame ($api_merchant, $openid, $game_id, $game_type, $language, $owner_id);

        if (!$res['success']){
//            exit(json_encode( [ 'err'=>'-1','msg'=>'游戏链接获取失败' ] ));
            exit("<script>alert('游戏链接获取失败".json_encode($res)."');window.close();</script>");
        }

        $toURL = $res['body']['game_url'] . '&token=' . $res['body']['token'];
        $data[0]['toUrl'] = $toURL;
        if($platform==13 || $platform==14 || $gametype) {
            $status = '200';
            $describe = '恭喜成功获取APP地址';
            original_phone_request_response($status,$describe,$data);
        }
        else{
            header("Location:".$toURL);
        }
        break;
    case 'fgGames': // 游戏列表
    case 'gamelist_dianzi': // 兼容，跟AG一样统一
        foreach ($aFgGames as $k => $v){
            $data[$k]['gameid'] = $v['service_id'];
            $data[$k]['name'] = $v['name'];
            $data[$k]['gameurl'] = '/images/fggame/' . $v['service_id'] . '.png';
            //$data[$k]['gameRuleUrl'] = $v['game_url'];
        }
        $fgGames = $data;

        $status = '200';
        $describe = 'FG游戏列表获取成功';
        original_phone_request_response($status,$describe,$fgGames);

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
