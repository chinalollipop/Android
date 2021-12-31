<?php
/**
 * 自定义CQ9电子API
 *
 *  执行任务 action （默认检查CQ9账号，或者创建账号）
 *       b  获取余额
 *       hg2cq  平台上分到cq
 *       cq2hg  cq下分到平台
 *       getLaunchGameUrl  真钱模式
 *       getDemoLaunchGameUrl  试玩模式
 */

error_reporting(E_ALL);
ini_set('display_errors','Off');
define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(__FILE__))))));
include_once "../include/config.inc.php";
include_once ROOT_DIR.'/common/cq9/api.php';

$uid = $_SESSION['Oid']?$_SESSION['Oid']:$_REQUEST['uid'];
$userid = $_SESSION['userid'] ;

// 判断CQ9电子是否维护（pc版）
$pageMark = 'cq';
$aRow = getMaintainDataByCategory($pageMark);
$aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
if ($aRow['state']==1 and in_array(1, $aTerminal)){
    $status = '555';
    $describe = 'CQ9电子维护中，请选择其他游戏';
    original_phone_request_response($status,$describe);
}


// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `LoginName`,  `Alias`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM ".DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();

if(!$stmt->affected_rows) {
    exit(json_encode( ['err' => '-1', 'msg' => '您的登录信息已过期，请您重新登录！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();

//echo '<pre>';
//var_dump($aUser);

if ($aUser['test_flag']){
    $status = '401.2';
    $describe = '请登录真实账号登入CQ9 Gaming';
    original_phone_request_response($status,$describe);
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$b = $score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
if($exchangeFrom == 'hg' && $exchangeTo == 'cq'){
    $action = 'hg2cq';
}

if($exchangeFrom == 'cq' && $exchangeTo == 'hg'){
    $action = 'cq2hg';
}
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2cq' || $action == 'cq2hg'){
    // 检查当前会员是否设置不准操作额度分层
    // 检查分层是否开启 status 1 开启 0 关闭
    // layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金 5 仅限可以投注体育，不能额度转换去其它馆
    if ($sUserlayer==3 || $sUserlayer==5){
        $layer = getUserLayerById($sUserlayer);
        if ($layer['status']==1) {
            $status = '400.66';
            $describe = '账号分层异常，请联系我们在线客服' ;
            original_phone_request_response($status,$describe,$aData);
        }
    }
}
$testFlag = isset($_REQUEST['flag']) && $_REQUEST['flag'] ? trim($_REQUEST['flag']) : '';

/* *
 *  3.检测登录CQ9会员
 *   (查询玩家账号是否存在) 查询数据表或者 check接口
 * */
$cqExist = 0;
$now = date('Y-m-d H:i:s');
if($action){
    // 账号是否记录
    $result = mysqli_query($dbLink, "SELECT `userid`,`username`,`password`,`ext_ref`,`cq9_user_info`  FROM `" . DBPREFIX . "cq9_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $cqExist = mysqli_num_rows($result);
    $res = mysqli_fetch_assoc($result);

    $account = $cq_prefix.$aUser['UserName'];
    $password = $cqExist ? $res['password'] : '';
    $nickname = $aUser['LoginName'] ? $aUser['LoginName'] : '';
    $result = checkAccount($api_token, $account);  //检测账号是否存在
    $checkExist = $result["success"];

    if(!$checkExist || !$cqExist){ // 接口或者数据表账号不存在

        if($action == 'b'){ // 未创建账号前请求余额接口
            $data = [
                'cq_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            $status = '200';
            $describe = '获取余额成功！';
            original_phone_request_response($status,$describe,$data);
        }else{

            $length = rand(6,10);
            $member_password = make_char($length);  //玩家密码

            if(!$checkExist && !$cqExist) { //接口账号、数据表账号不存在

                $createRes = createPlay($api_token, $account, $member_password, $nickname);   // 建立player,创建账号
                //var_DUMP($createRes);
                $password = $createRes['body']['password'];
            }else if($checkExist && !$cqExist) { //接口账号存在， 数据表账号不存在
                $createRes = pwdPlay($api_token, $account, $member_password);   // 更改密码
                $password = $member_password;
            }

            if ($createRes['success']){ // 创建或更改成功
                $data = [
                    'userid' => $aUser['ID'],
                    'username' => $account,
                    'password' => $password, //$member_password
                    'ext_ref' => $member_ext_ref,
                    'agents' => $aUser['Agents'],
                    'world' => $aUser['World'],
                    'corporator' => $aUser['Corprator'],
                    'super' => $aUser['Super'],
                    'admin' => $aUser['Admin'],
                    'register_time' => $now,
                    'last_launch_time' => $now,
                    'launch_times' => 1,
                    'is_test' => $aUser['test_flag'],
                    'cq9_user_info' => json_encode($createRes)
                ];
                $sInsData = '';
                foreach ($data as $key => $value){
                    $sInsData .= "`$key` = '{$value}'" . ($key == 'cq9_user_info' ? '' : ',');
                }
                $sql = "INSERT INTO `" . DBPREFIX . "cq9_member_data` SET $sInsData";
                if (!mysqli_query($dbMasterLink, $sql)) {
                    $status = '401.5';
                    $describe = 'CQ9账号异常，请您稍后重试！';
                    original_phone_request_response($status,$describe);
                }else{
                    $status = 222;
                    $describe = 'CQ9账号初始化成功，请您继续转账';
                    original_phone_request_response($status,$describe);
                }
            }else{
                $status = '401.6';
                $describe = '建立player异常,账号失败，请联系技术';
                original_phone_request_response($status,$describe);
            }
        }
    }
}

$result = mysqli_query($dbLink, "SELECT `userid`,`username`,`password`,`ext_ref`,`cq9_user_info`  FROM `" . DBPREFIX . "cq9_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
$res = mysqli_fetch_assoc($result);

switch ($action){
    case "cm":  // 会员 免费试玩或立即游戏
        if($testFlag !== 'test')  { // 正式会员

            $pResult = playLogin($api_token, $account , $res['password']);   //Player登入
            if($pResult["success"] !== true) {  //Player登入失败 $status = '401.6';
                $status = '401.7';
                $describe = 'Player登入失败！';
                original_phone_request_response($status,$describe);
            }

            $usertoken = $pResult['body']['usertoken']; //Player登入成功，获取usertoken
            if($cqExist && $usertoken){ // 账号存在，Player登入成功 , 更新会员信息
                mysqli_query($dbMasterLink, "update " . DBPREFIX . "cq9_member_data set launch_times = launch_times + 1, last_launch_time = '$now'  WHERE userid = '{$aUser['ID']}'");
            }
        }

        $linkRes = playLobbyLink($api_token, $usertoken, $language);
        if($linkRes['success'] == true) {
            exit(json_encode(['code' => 0, 'success' => $linkRes['success'], 'url' => $linkRes['body']['url']]));
            //header("Location:".$linkRes['body']['url']);
        }
        break;
    case 'b': //点击电子游艺   CQ9额度转换
        $res = getBalance($api_token, $account);
        if (!$res['success']){
            $status = '401.7';
            $describe = '余额获取失败，请联系技术';
            original_phone_request_response($status,$describe);
        }
        else{
            $data = [
                'hg_balance' => formatMoney($aUser['Money']),
                'cq_balance' => sprintf('%.2f', $res["body"]['balance'])
            ];
            $status = '200';
            $describe = '获取余额成功！';
            original_phone_request_response($status,$describe,$data);
        }
        break;
    case 'hg2cq':
        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score)){
            original_phone_request_response('401.8','转账金额格式错误，请重新输入!');
        }

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
            original_phone_request_response('2004','中心钱包不足！');
        }
        $afterBalance = bcsub($beforeBalance, $score, 4); // 转换之后余额

        // 更新会员余额
        if(!$updated = mysqli_query($dbMasterLink, 'UPDATE '.DBPREFIX.MEMBERTABLE.' SET `Money` = ' . $afterBalance . ' WHERE `ID` = ' . $aUser['ID'])) {
            $dbMasterLink->rollback();
            original_phone_request_response('2005','额度转换失败，请您稍后重试！');
        }

        // CQ9-orderId
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $orderId = 'IN' . date('YmdHis') . $sTime8 . $sUser6 . $gamehall; // 订单号生成规则

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
            'To' => 'cq',
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
            'reason' => 'HG TO CQ9',
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
            42, // type：42 体育转CQ9电子真人
            22,
            $insertId,
            'CQ9电子真人额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            original_phone_request_response('2007','额度转换失败，请您稍后重试！');
        }

        // 2.4.调用三方上分接口
        //{"data":{"balance":10,"currency":"CNY"},"status":{"code":"0","message":"Success","datetime":"2019-10-16T06:13:54-04:00","traceCode":"bBCuoaf7ws"}}
        $aResult = playDeposit ($api_token, $account, $score, $orderId);
        if($aResult){
            if($aResult['success'] == true){
                $dbMasterLink->commit();
                $data = [
                    'cq_balance' => sprintf('%.2f', $aResult['body']['balance']),
                    'hg_balance' => formatMoney($afterBalance)
                ];
                $status = '200';
                $describe = '恭喜上分成功';
                original_phone_request_response($status,$describe,$data);
                //exit(json_encode(['err' => 0, 'msg' => $data]));
            }else{
                $dbMasterLink->rollback();
                original_phone_request_response('2008','额度转换失败，请您稍后重试！');
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = recordMtcode($api_token,  $orderId); //单一交易查詢
            if($aResult){
                if($aResult['success'] == false){
                    $dbMasterLink->rollback();
                    original_phone_request_response('2009','额度转换失败，请您稍后重试！');
                    break;
                }
                if($aResult['success'] == true){
                    $dbMasterLink->commit();
                    $data = [
                        'cq_balance' => sprintf('%.2f', $aResult['body']['balance']),
                        'hg_balance' => formatMoney($afterBalance)
                    ];
                    $status = '200';
                    $describe = '获取余额成功！';
                    original_phone_request_response($status,$describe,$data);
                    //exit(json_encode(['err' => 0, 'msg' => $data]));
                    break;
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                original_phone_request_response('400','额度转换失败，请您稍后重试！');
            }
        }
        break;
    case 'cq2hg':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score)){
            //exit(json_encode([ 'err' => '401', 'msg' => '转账金额格式错误，请重新输入!' ]));
            original_phone_request_response('401','转账金额格式错误，请重新输入!');
        }

        if ($score > 10000000){
            original_phone_request_response('2011','单次下分不能超过一千万，请重新输入！');
        }
        // 2.查询CQ9电子可下分余额
        $aResult = getBalance($api_token, $account);
        if ($aResult['success'] == true){
            $cqBalance = sprintf('%.2f', $aResult['body']['balance']);
        } else{
            original_phone_request_response('2022','CQ9余额获取失败，请稍后重试！');
        }

        if(intval($cqBalance) < intval($score)){
            original_phone_request_response('2023','CQ9电子余额不足！');
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
            original_phone_request_response('2024','额度更新失败，请您稍后重试！');
        }
        // CQ9电子真人-orderId
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($userid), 0, 6)); // 6bit
        $orderId = 'IN' . date('YmdHis') . $sTime8 . $sUser6 . $gamehall; // 订单号生成规则

        // 3.2.入库额度转换表
        $data = [
            'userid' => $aUser['ID'],
            'Checked' => 1,
            'Gold' => $score,
            'moneyf' => $beforeBalance,
            'currency_after' => $afterBalance,
            'AddDate' => $now,
            'Type' => 'Q',
            'From' => 'cq',
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
            'reason' => 'CQ9 TO HG',
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
            43, // type：43 CQ9电子转体育
            22,
            $insertId,
            'CQ9电子额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            original_phone_request_response('2026','额度更新失败，请您稍后重试！');
        }

        // 3.4.调用三方下分接口
        $aResult = playWithdraw ($api_token, $account, $score, $orderId);
        if($aResult){
            if($aResult['success'] == true){
                $dbMasterLink->commit();
                $data = [
                    'cq_balance' => sprintf('%.2f', $aResult['body']['balance']),
                    'hg_balance' => formatMoney($afterBalance)
                ];
                $status = '200';
                $describe = '恭喜下分成功';
                original_phone_request_response($status,$describe,$data);
            }else{
                $dbMasterLink->rollback();
                original_phone_request_response('2008','额度转换失败，请您稍后重试！');
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = recordMtcode($api_token,  $orderId); //单一交易查詢
            if($aResult){
                if($aResult['success'] == false){
                    $dbMasterLink->rollback();
                    original_phone_request_response('2009','额度转换失败，请您稍后重试！');
                    //exit(json_encode(['err' => 2009, 'msg' => '额度转换失败，请您稍后重试！']));
                    break;
                }
                if($aResult['success'] == true){
                    $dbMasterLink->commit();
                    $data = [
                        'cq_balance' => sprintf('%.2f', $aResult['body']['balance']),
                        'hg_balance' => formatMoney($afterBalance)
                    ];
                    $status = '200';
                    $describe = '获取余额成功！';
                    original_phone_request_response($status,$describe,$data);
                    //exit(json_encode(['err' => 0, 'msg' => $data]));
                    break;
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                original_phone_request_response('400','额度转换失败，请您稍后重试！');
            }
        }
        break;
    case 'getLaunchGameUrl':
        $game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:1;

        $pResult = playLogin($api_token, $account , $res['password']);   //Player登入
        if($pResult["success"] !== true) {  //Player登入失败 $status = '401.6';
            $status = '401.7';
            $describe = 'Player登入失败！';
            original_phone_request_response($status,$describe);
        }

        $usertoken = $pResult['body']['usertoken']; //Player登入成功，获取usertoken
        $gameplat = 'web';  //填入 web 或 mobile
        $app = 'N';     //是否是透過app 執行遊戲，Y=是，N=否，預設為N
        $linkRes = playGameLink($api_token, $usertoken, $gamehall, $game_id, $gameplat, $language, $app);     //进入单个游戏
        if (!$linkRes['success']) {
//            exit(json_encode( [ 'err'=>'-1','msg'=>'游戏链接获取失败' ] ));
            exit("<script>alert('游戏链接获取失败".json_encode($linkRes)."');window.close();</script>");
        } else {
            //exit(json_encode(['code' => 0, 'success' => $linkRes['success'], 'url' => $linkRes['body']['url']]));
            header("Location:".$linkRes['body']['url']);
        }
        break;
    /*case 'getDemoLaunchGameUrl':
        $game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:1035;
        $res = getDemoLaunchGameUrl ($access_token, $game_id, $language);
        if (!$res['success']){
            exit(json_encode( [ 'err'=>'-1','msg'=>'游戏链接获取失败' ] ));
        }
        else{
            header("Location:".$res['body']);
        }
        break;*/
    case 'cqDianziGames':

        if($platform==13 || $platform==14) {
            foreach ($aCqGames as $k => $v){
                $data[$k]['name'] = $v['name'];
                $data[$k]['gameid'] = $v['gameid'];
                $data[$k]['gameurl'] = '/images/cqgame/'.$v['gameurl'];
            }
            $cqInfo = $data;
        }
        else{
            foreach ($aCqGames as $k => $v){
                $data[$k]['name'] = $v['name'];
                $data[$k]['gameid'] = $v['gameid'];
                $data[$k]['gameurl'] = '/images/game/cq9/'.$v['gameurl'];
            }
            $cqInfo['cqGames'] = $data;
        }

        $status = '200';
        $describe = '获取CQ9电子游戏列表成功！';
        original_phone_request_response($status,$describe,$cqInfo);

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
