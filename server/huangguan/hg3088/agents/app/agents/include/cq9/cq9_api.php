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
define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
include "../../include/address.mem.php";
include_once "../../include/config.inc.php";
require_once ROOT_DIR.'/common/cq9/api.php';

$username = isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : '';

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `test_flag`, `UserName`, `LoginName`,  `Alias`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM ".DBPREFIX.MEMBERTABLE." where `UserName` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();

if(!$stmt->affected_rows) {
    exit(json_encode( ['code' => '-1', 'message' => '您的登录信息已过期，请您重新登录！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();

$userid = $aUser['ID'] ;
//echo '<pre>';
//var_dump($aUser);

if ($aUser['test_flag']){
    exit("<script>alert('请登录真实账号登入CQ9 Gaming');window.close();</script>");
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
                'hg_balance' => sprintf('%.2f', $aUser['Money'])
            ];
            exit(json_encode(['code' => 0, 'data' => $data]));
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
                    //original_phone_request_response('-5' , 'CQ9账号异常，请您稍后重试！');
                    exit(json_encode(['code' => -5, 'message' => '创建CQ9账号异常，请您稍后重试！']));
                }
            }else{
                exit(json_encode(['code' => -6, 'message' => '建立player异常,账号失败'.json_encode($createRes)]));
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
            if($pResult["success"] !== true) {  //Player登入失败
                //exit(json_encode(['code' => $pResult['body']['code'], 'message' => $pResult['body']['message']]));
                exit(json_encode(['code' => $pResult['body']['code'], 'msg' => 'Player登入失败！']));
            }

            $usertoken = $pResult['body']['usertoken']; //Player登入成功，获取usertoken
            if($cqExist && $usertoken){ // 账号存在，Player登入成功 , 更新会员信息
                mysqli_query($dbMasterLink, "update " . DBPREFIX . "cq9_member_data set launch_times = launch_times + 1, last_launch_time = '$now'  WHERE userid = '{$aUser['ID']}'");
            }
        }

        $linkRes = playLobbyLink($api_token, $usertoken, $language);
        if($linkRes['success'] == true) {
            exit(json_encode(['code' => 0, 'success' => $linkRes['success'], 'url' => $linkRes['body']['url']]));
        }

        /*
           exit(json_encode(['code' => $aResult['errcode'], 'message' => 'VG棋牌账号登录游戏异常4，请您稍后重试！']));
          */
        break;
    case 'b': //点击电子游艺   CQ9额度转换
        $res = getBalance($api_token, $account);
        if (!$res['success']){
            exit(json_encode( [ 'code'=>'-1','message'=>'余额获取失败'.json_encode($res) ] ));
        }
        else{
            $data = [
                'cq_balance' => number_format($res["body"]['balance'],2),
                'hg_balance' => sprintf('%.2f', $aUser['Money'])
            ];
            exit( json_encode( ['code'=>0, 'data' => $data ] ) );
        }

        break;
    case 'hg2cq':
        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score))
            exit(json_encode([ 'code' => 2002, 'message' => '转账金额格式错误，请重新输入!' ]));

        if ($score > 10000000){
            exit(json_encode([ 'code' => 2003, 'message'=>'单次上分不能超过一千万，请重新输入！' ]));
        }

        // 2.事务处理
        $dbMasterLink->autocommit(false);

        // 2.1.事务内查询用户余额，后续用于更新用户余额
        $result = mysqli_query($dbMasterLink, 'SELECT `ID`, `Money` FROM '.DBPREFIX.MEMBERTABLE.' WHERE `ID` = ' . $aUser['ID'] . ' FOR UPDATE');
        $aForUpdate = mysqli_fetch_assoc($result);
        $beforeBalance = $aForUpdate['Money']; // 转换之前余额
        if(intval($beforeBalance) < intval($score)) { // 余额不足
            exit(json_encode(['code' => 2004, 'message' => '皇冠体育余额不足！']));
        }
        $afterBalance = bcsub($beforeBalance, $score, 4); // 转换之后余额

        // 更新会员余额
        if(!$updated = mysqli_query($dbMasterLink, 'UPDATE '.DBPREFIX.MEMBERTABLE.' SET `Money` = ' . $afterBalance . ' WHERE `ID` = ' . $aUser['ID'])) {
            $dbMasterLink->rollback();
            exit(json_encode(['code' => 2005, 'message' => '额度转换失败，请您稍后重试！']));
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
            42, // type：42 体育到CQ9电子
            6,
            $insertId,
            'CQ9电子真人额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            exit(json_encode(['code' => 2007, 'message' => '额度转换失败，请您稍后重试！']));
        }

        // 2.4.调用三方上分接口
        //{"data":{"balance":10,"currency":"CNY"},"status":{"code":"0","message":"Success","datetime":"2019-10-16T06:13:54-04:00","traceCode":"bBCuoaf7ws"}}
        $aResult = playDeposit ($api_token, $account, $score, $orderId);
        if($aResult){
            if($aResult['success'] == true){
                $dbMasterLink->commit();
                $data = [
                    'cq_balance' => sprintf('%.2f', $aResult['body']['balance']),
                    'hg_balance' => sprintf('%.2f', $afterBalance)
                ];
                exit(json_encode(['code' => 0, 'data' => $data]));
            }else{
                $dbMasterLink->rollback();
                exit(json_encode(['code' => 2008, 'message' => '额度转换失败，请您稍后重试！']));
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = recordMtcode($api_token,  $orderId); //单一交易查詢
            if($aResult){
                if($aResult['success'] == false){
                    $dbMasterLink->rollback();
                    exit(json_encode(['code' => 2009, 'message' => '额度转换失败，请您稍后重试！']));
                    break;
                }
                if($aResult['success'] == true){
                    $dbMasterLink->commit();
                    $data = [
                        'cq_balance' => sprintf('%.2f', $aResult['body']['balance']),
                        'hg_balance' => sprintf('%.2f', $afterBalance)
                    ];
                    exit(json_encode(['code' => 0, 'data' => $data]));
                    break;
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                exit(json_encode(['code' => 400, 'message' => '额度转换失败，请您稍后重试！']));
            }
        }
        break;
    case 'cq2hg':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score)) {
            exit(json_encode([ 'code' => 401, 'message' => '转账金额只支持正整数，请重新输入!' ]));
        }

        if ($score > 10000000){
            exit(json_encode([ 'code' => 2011, 'message' => '单次下分不能超过一千万，请重新输入！' ]));
        }
        // 2.查询CQ9电子可下分余额
        $aResult = getBalance($api_token, $account);
        if ($aResult['success'] == true){
            $cqBalance = sprintf('%.2f', $aResult['body']['balance']);
        } else{
            exit(json_encode( [ 'code'=>'-1','message'=>'CQ9余额获取失败，请稍后重试！' ] ));
        }

        if(intval($cqBalance) < intval($score))
            exit(json_encode(['code' => 2013, 'message' => 'CQ9电子余额不足！']));

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
            exit(json_encode(['code' => 2014, 'message' => '额度更新失败，请您稍后重试！']));
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
            43, // type：43 CQ9电子到体育
            6,
            $insertId,
            'CQ9电子额度转换'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            exit(json_encode(['code' => 2016, 'message' => '额度更新失败，请您稍后重试！']));
        }

        // 3.4.调用三方下分接口
        $aResult = playWithdraw ($api_token, $account, $score, $orderId);
        if($aResult){
            if($aResult['success'] == true){
                $dbMasterLink->commit();
                $data = [
                    'cq_balance' => sprintf('%.2f', $aResult['body']['balance']),
                    'hg_balance' => sprintf('%.2f', $afterBalance)
                ];
                exit(json_encode(['code' => 0, 'data' => $data]));
            }else{
                $dbMasterLink->rollback();
                exit(json_encode(['code' => 2008, 'message' => '额度转换失败，请您稍后重试！']));
            }
        }else{ // 超时or错误返回NULL，则查询订单状态
            $aResult = recordMtcode($api_token,  $orderId); //单一交易查詢
            if($aResult){
                if($aResult['success'] == false){
                    $dbMasterLink->rollback();
                    exit(json_encode(['code' => 2009, 'message' => '额度转换失败，请您稍后重试！']));
                    break;
                }
                if($aResult['success'] == true){
                    $dbMasterLink->commit();
                    $data = [
                        'cq_balance' => sprintf('%.2f', $aResult['body']['balance']),
                        'hg_balance' => sprintf('%.2f', $afterBalance)
                    ];
                    exit(json_encode(['code' => 0, 'data' => $data]));
                    break;
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                exit(json_encode(['code' => 400, 'message' => '额度转换失败，请您稍后重试！']));
            }
        }
        break;
    case 'getLaunchGameUrl':    //进入单个游戏
        $game_id = $_REQUEST['game_id']?$_REQUEST['game_id']:1;
        $res = getLaunchGameUrl ($access_token, $member_account_id, $game_id, $language);
        if (!$res['success']){
//            exit(json_encode( [ 'err'=>'-1','msg'=>'游戏链接获取失败' ] ));
            exit("<script>alert('游戏链接获取失败".json_encode($res)."');window.close();</script>");
        }
        else{
            header("Location:".$res['body']);
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

        $cqInfo = array(
            array(
                'name'=>'钻石水果王', 'gameid'=>'1',
                'gameurl'=>'/images/game/cq9/1_FruitKing.png',
            ),
            array(
                'name'=>'棋圣', 'gameid'=>'2',
                'gameurl'=>'/images/game/cq9/2_GodofChess.png',
            ),
            array(
                'name'=>'杰克高手', 'gameid'=>'3',
                'gameurl'=>'/images/game/cq9/3_VampireKiss.png',
            ),
            array(
                'name'=>'森林泰后', 'gameid'=>'4',
                'gameurl'=>'/images/game/cq9/4_WildTarzan.png',
            ),
            array(
                'name'=>'金大款', 'gameid'=>'5',
                'gameurl'=>'/images/game/cq9/5_MrRich.png',
            ),
            array(
                'name'=>'1945', 'gameid'=>'6',
                'gameurl'=>'/images/game/cq9/6_1945.png',
            ),
            array(
                'name'=>'跳起來', 'gameid'=>'7',
                'gameurl'=>'/images/game/cq9/7_RaveJump.png',
            ),
            array(
                'name'=>'甜蜜蜜So', 'gameid'=>'8',
                'gameurl'=>'/images/game/cq9/8_SoSweet.png',
            ),
            array(
                'name'=>'钟馗运财', 'gameid'=>'9',
                'gameurl'=>'/images/game/cq9/9_ZhongKui.png',
            ),
            array(
                'name'=>'五福临门', 'gameid'=>'10',
                'gameurl'=>'/images/game/cq9/10_LuckyBats.png',
            ),
            array(
                'name'=>'梦游仙境2', 'gameid'=>'11',
                'gameurl'=>'/images/game/cq9/11_Wonderland.png',
            ),
            array(
                'name'=>'金玉满堂', 'gameid'=>'12',
                'gameurl'=>'/images/game/cq9/12_TreasureHouse.png',
            ),
            array(
                'name'=>'樱花妹子', 'gameid'=>'13',
                'gameurl'=>'/images/game/cq9/13_SakuraLegend.png',
            ),
            array(
                'name'=>'绝赢巫师', 'gameid'=>'14',
                'gameurl'=>'/images/game/cq9/14_RichWitch.png',
            ),
            array(
                'name'=>'GuGuGu', 'gameid'=>'15',
                'gameurl'=>'/images/game/cq9/15_GuGuGu.png',
            ),
            array(
                'name'=>'五行', 'gameid'=>'16',
                'gameurl'=>'/images/game/cq9/16_Super5.png',
            ),
            array(
                'name'=>'祥狮献瑞', 'gameid'=>'17',
                'gameurl'=>'/images/game/cq9/17_GreatLion.png',
            ),
            array(
                'name'=>'雀王', 'gameid'=>'18',
                'gameurl'=>'/images/game/cq9/18_MahjongKing.png',
            ),
            array(
                'name'=>'风火轮', 'gameid'=>'19',
                'gameurl'=>'/images/game/cq9/19_HotSpin.png',
            ),
            array(
                'name'=>'发发发', 'gameid'=>'20',
                'gameurl'=>'/images/game/cq9/20_888.png',
            ),
            array(
                'name'=>'野狼传说', 'gameid'=>'21',
                'gameurl'=>'/images/game/cq9/21_Legendofthewolf.png',
            ),
            array(
                'name'=>'庶务西游二课', 'gameid'=>'22',
                'gameurl'=>'/images/game/cq9/22_Monkeyofficelegend.png',
            ),
            array(
                'name'=>'金元宝', 'gameid'=>'23',
                'gameurl'=>'/images/game/cq9/23_YuanBao.png',
            ),
            array(
                'name'=>'跳起来2', 'gameid'=>'24',
                'gameurl'=>'/images/game/cq9/24_2.png',
            ),
            array(
                'name'=>'扑克拉霸', 'gameid'=>'25',
                'gameurl'=>'/images/game/cq9/25_PokerSLOT.png',
            ),
            array(
                'name'=>'777', 'gameid'=>'26',
                'gameurl'=>'/images/game/cq9/26_777.png',
            ),
            array(
                'name'=>'魔法世界', 'gameid'=>'27',
                'gameurl'=>'/images/game/cq9/27_MagicWorld.png',
            ),
            array(
                'name'=>'食神', 'gameid'=>'28',
                'gameurl'=>'/images/game/cq9/28_GodofCookery.png',
            ),
            array(
                'name'=>'水世界', 'gameid'=>'29',
                'gameurl'=>'/images/game/cq9/29_WaterWorld.png',
            ),
            array(
                'name'=>'三国序', 'gameid'=>'30',
                'gameurl'=>'/images/game/cq9/30_Warriorlegend.png',
            ),
            array(
                'name'=>'武圣', 'gameid'=>'31',
                'gameurl'=>'/images/game/cq9/31_GodofWar.png',
            ),
            array(
                'name'=>'通天神探狄仁杰', 'gameid'=>'32',
                'gameurl'=>'/images/game/cq9/32_DetectiveDee.png',
            ),
            array(
                'name'=>'火烧连环船', 'gameid'=>'33',
                'gameurl'=>'/images/game/cq9/33_FireChibi.png',
            ),
            array(
                'name'=>'地鼠战役', 'gameid'=>'34',
                'gameurl'=>'/images/game/cq9/34_GophersWar.png',
            ),
            array(
                'name'=>'疯狂哪吒', 'gameid'=>'35',
                'gameurl'=>'/images/game/cq9/35_CrazyNaza.png',
            ),
            array(
                'name'=>'夜店大亨', 'gameid'=>'36',
                'gameurl'=>'/images/game/cq9/36_PubTycoon.png',
            ),
            array(
                'name'=>'舞力全开', 'gameid'=>'38',
                'gameurl'=>'/images/game/cq9/38_AllWilds.png',
            ),
            array(
                'name'=>'飞天', 'gameid'=>'39',
                'gameurl'=>'/images/game/cq9/39_Apsaras.png',
            ),
            array(
                'name'=>'镖王争霸', 'gameid'=>'40',
                'gameurl'=>'/images/game/cq9/40_DartsChampion.png',
            ),
            array(
                'name'=>'水球大战', 'gameid'=>'41',
                'gameurl'=>'/images/game/cq9/41_WaterBalloons.png',
            ),
            array(
                'name'=>'福尔摩斯', 'gameid'=>'42',
                'gameurl'=>'/images/game/cq9/42_holmes.png',
            ),
            array(
                'name'=>'恭贺新禧', 'gameid'=>'43',
                'gameurl'=>'/images/game/cq9/43_gonghe.png',
            ),
            array(
                'name'=>'豪华水果王', 'gameid'=>'44',
                'gameurl'=>'/images/game/cq9/44_FruitKingII.png',
            ),
            array(
                'name'=>'超级发', 'gameid'=>'45',
                'gameurl'=>'/images/game/cq9/45_Super8.png',
            ),
            array(
                'name'=>'狼月', 'gameid'=>'46',
                'gameurl'=>'/images/game/cq9/46_wolfmoon.png',
            ),
            array(
                'name'=>'法老宝藏', 'gameid'=>'47',
                'gameurl'=>'/images/game/cq9/47_pharoahtreasures.png',
            ),
            array(
                'name'=>'莲', 'gameid'=>'48',
                'gameurl'=>'/images/game/cq9/48_LOTUS.png',
            ),
            array(
                'name'=>'寂寞星球', 'gameid'=>'49',
                'gameurl'=>'/images/game/cq9/49_LonelyPlanet.png',
            ),
            array(
                'name'=>'鸿福齐天', 'gameid'=>'50',
                'gameurl'=>'/images/game/cq9/50_GoodFortune.png',
            ),
            array(
                'name'=>'嗨爆大马戏', 'gameid'=>'51',
                'gameurl'=>'/images/game/cq9/51_EcstaticCircus.png',
            ),
            array(
                'name'=>'跳高高', 'gameid'=>'52',
                'gameurl'=>'/images/game/cq9/52_JumpObsession.png',
            ),
            array(
                'name'=>'来电99', 'gameid'=>'53',
                'gameurl'=>'/images/game/cq9/53_LoveNight.png',
            ),
            array(
                'name'=>'火草泥马', 'gameid'=>'54',
                'gameurl'=>'/images/game/cq9/54_FunnyAlpaca.png',
            ),
            array(
                'name'=>'Dragonheart', 'gameid'=>'55',
                'gameurl'=>'/images/game/cq9/55_Dragonheart.png',
            ),
            array(
                'name'=>'黯夜公爵', 'gameid'=>'56',
                'gameurl'=>'/images/game/cq9/56_Dracula.png',
            ),
            array(
                'name'=>'神兽争霸', 'gameid'=>'57',
                'gameurl'=>'/images/game/cq9/57_TheBeastWar.png',
            ),
            array(
                'name'=>'金鸡报囍2', 'gameid'=>'58',
                'gameurl'=>'/images/game/cq9/58_HappyRooster.png',
            ),
            array(
                'name'=>'夏日猩情', 'gameid'=>'59',
                'gameurl'=>'/images/game/cq9/59_RadiantQueen.png',
            ),
            array(
                'name'=>'丛林舞会', 'gameid'=>'60',
                'gameurl'=>'/images/game/cq9/60_JungleParty.png',
            ),
            array(
                'name'=>'天天吃豆', 'gameid'=>'61',
                'gameurl'=>'/images/game/cq9/61_Mr.Bean.png',
            ),
            array(
                'name'=>'非常钻', 'gameid'=>'62',
                'gameurl'=>'/images/game/cq9/62_SuperDiamonds.png',
            ),
            array(
                'name'=>'寻龙诀', 'gameid'=>'63',
                'gameurl'=>'/images/game/cq9/63_TheGhouls.png',
            ),
            array(
                'name'=>'宙斯', 'gameid'=>'64',
                'gameurl'=>'/images/game/cq9/64_Zeus.png',
            ),
            array(
                'name'=>'足球世界杯', 'gameid'=>'65',
                'gameurl'=>'/images/game/cq9/65_GoldenKick.png',
            ),
            array(
                'name'=>'火爆777', 'gameid'=>'66',
                'gameurl'=>'/images/game/cq9/66_Fire777.png',
            ),
            array(
                'name'=>'赚金蛋', 'gameid'=>'67',
                'gameurl'=>'/images/game/cq9/67_Goldeneggs.png',
            ),
            array(
                'name'=>'悟空偷桃', 'gameid'=>'68',
                'gameurl'=>'/images/game/cq9/68_5Dragons.png',
            ),
            array(
                'name'=>'發財神', 'gameid'=>'69',
                'gameurl'=>'/images/game/cq9/69_facaishen.png',
            ),
            array(
                'name'=>'万饱龙', 'gameid'=>'70',
                'gameurl'=>'/images/game/cq9/70_wbl.png',
            ),
            array(
                'name'=>'好运年年', 'gameid'=>'72',
                'gameurl'=>'/images/game/cq9/72_WildWays.png',
            ),
            array(
                'name'=>'聚宝盆', 'gameid'=>'74',
                'gameurl'=>'/images/game/cq9/74_jubaopen.png',
            ),
            array(
                'name'=>'旺旺旺', 'gameid'=>'76',
                'gameurl'=>'/images/game/cq9/76_Won_Won_Won.png',
            ),
            array(
                'name'=>'火凤凰', 'gameid'=>'77',
                'gameurl'=>'/images/game/cq9/77_huofenghuang.png',
            ),
            array(
                'name'=>'阿波罗', 'gameid'=>'78',
                'gameurl'=>'/images/game/cq9/78_aboluo.png',
            ),
            array(
                'name'=>'变色龙', 'gameid'=>'79',
                'gameurl'=>'/images/game/cq9/79_WinningMask.png',
            ),
            array(
                'name'=>'传奇海神', 'gameid'=>'80',
                'gameurl'=>'/images/game/cq9/80_chuanqihaishen.png',
            ),
            array(
                'name'=>'金银岛', 'gameid'=>'81',
                'gameurl'=>'/images/game/cq9/81_TreasureIsland.png',
            ),
            array(
                'name'=>'火之女王', 'gameid'=>'83',
                'gameurl'=>'/images/game/cq9/83_FireQueen.png',
            ),
            array(
                'name'=>'奇幻魔术', 'gameid'=>'84',
                'gameurl'=>'/images/game/cq9/84_WildMagic.png',
            ),
            array(
                'name'=>'牛逼快跑', 'gameid'=>'86',
                'gameurl'=>'/images/game/cq9/86_RunningToro.png',
            ),
            array(
                'name'=>'集电宝', 'gameid'=>'87',
                'gameurl'=>'/images/game/cq9/87_ChilliHeat.png',
            ),
            array(
                'name'=>'金喜鹊桥', 'gameid'=>'88',
                'gameurl'=>'/images/game/cq9/88_HappyMagpies.png',
            ),
            array(
                'name'=>'雷神', 'gameid'=>'89',
                'gameurl'=>'/images/game/cq9/89_BigRedWay.png',
            ),
            array(
                'name'=>'2018世界杯', 'gameid'=>'92',
                'gameurl'=>'/images/game/cq9/92_2018.png',
            ),
            array(
                'name'=>'世界杯明星', 'gameid'=>'93',
                'gameurl'=>'/images/game/cq9/93_wordstar.png',
            ),
            array(
                'name'=>'世界杯球衣', 'gameid'=>'94',
                'gameurl'=>'/images/game/cq9/94_wordCup.png',
            ),
            array(
                'name'=>'世界杯球鞋', 'gameid'=>'95',
                'gameurl'=>'/images/game/cq9/95_wordSneakers.png',
            ),
            array(
                'name'=>'足球宝贝', 'gameid'=>'96',
                'gameurl'=>'/images/game/cq9/96_wordBaby.png',
            ),
            array(
                'name'=>'世界杯球场', 'gameid'=>'97',
                'gameurl'=>'/images/game/cq9/97_wordCourt.png',
            ),
            array(
                'name'=>'世界杯全明星', 'gameid'=>'98',
                'gameurl'=>'/images/game/cq9/98_wordStar.png',
            ),
            array(
                'name'=>'跳更高', 'gameid'=>'99',
                'gameurl'=>'/images/game/cq9/99_jump.png',
            ),
            array(
                'name'=>'宾果消消消', 'gameid'=>'100',
                'gameurl'=>'/images/game/cq9/100_Bingo.png',
            ),
            array(
                'name'=>'星星消消乐', 'gameid'=>'101',
                'gameurl'=>'/images/game/cq9/101_Eliminate.png',
            ),
            array(
                'name'=>'水果派对', 'gameid'=>'102',
                'gameurl'=>'/images/game/cq9/102_FruitParty.png',
            ),
            array(
                'name'=>'宝石配对', 'gameid'=>'103',
                'gameurl'=>'/images/game/cq9/103_Gempairing.png',
            ),
            array(
                'name'=>'海滨消消乐', 'gameid'=>'104',
                'gameurl'=>'/images/game/cq9/104_Beachside.png',
            ),
            array(
                'name'=>'单手跳高高', 'gameid'=>'105',
                'gameurl'=>'/images/game/cq9/105_One-handed.png',
            ),
            array(
                'name'=>'直式跳更高', 'gameid'=>'108',
                'gameurl'=>'/images/game/cq9/108_StraightJump.png',
            ),
            array(
                'name'=>'單手跳起來', 'gameid'=>'109',
                'gameurl'=>'/images/game/cq9/109_JumpWith.png',
            ),
            array(
                'name'=>'飞起来', 'gameid'=>'111',
                'gameurl'=>'/images/game/cq9/111_Fly.png',
            ),
            array(
                'name'=>'盗法老墓', 'gameid'=>'112',
                'gameurl'=>'/images/game/cq9/112_PirateOld.png',
            ),
            array(
                'name'=>'飞天财神', 'gameid'=>'113',
                'gameurl'=>'/images/game/cq9/113_FlyingGod.png',
            ),
            array(
                'name'=>'钻饱宝', 'gameid'=>'114',
                'gameurl'=>'/images/game/cq9/114_Drilling.png',
            ),
            array(
                'name'=>'冰雪女王', 'gameid'=>'115',
                'gameurl'=>'/images/game/cq9/115_SnowQueen.png',
            ),
            array(
                'name'=>'梦游仙境', 'gameid'=>'116',
                'gameurl'=>'/images/game/cq9/116_Sleepwalking.png',
            ),
            array(
                'name'=>'东方神起', 'gameid'=>'117',
                'gameurl'=>'/images/game/cq9/117_DongBang.png',
            ),
            array(
                'name'=>'老司机', 'gameid'=>'118',
                'gameurl'=>'/images/game/cq9/118_OldDriver.png',
            ),
            array(
                'name'=>'直式跳起來2', 'gameid'=>'121',
                'gameurl'=>'/images/game/cq9/121_StraightJump2.png',
            ),
            array(
                'name'=>'印加祖瑪', 'gameid'=>'122',
                'gameurl'=>'/images/game/cq9/122_IncaZuma.png',
            ),
            array(
                'name'=>'直式五福臨門', 'gameid'=>'123',
                'gameurl'=>'/images/game/cq9/123_fiveBlessings.png',
            ),
            array(
                'name'=>'锁象无敌', 'gameid'=>'124',
                'gameurl'=>'/images/game/cq9/124_LockIcon.png',
            ),
            array(
                'name'=>'直式宙斯', 'gameid'=>'125',
                'gameurl'=>'/images/game/cq9/125_StraightZeus.png',
            ),
            array(
                'name'=>'轉珠豬', 'gameid'=>'126',
                'gameurl'=>'/images/game/cq9/126_TurningPig.png',
            ),
            array(
                'name'=>'直式武圣', 'gameid'=>'127',
                'gameurl'=>'/images/game/cq9/127_StraightWusheng.png',
            ),
            array(
                'name'=>'转大钱', 'gameid'=>'128',
                'gameurl'=>'/images/game/cq9/128_Turnbigmoney.png',
            ),
            array(
                'name'=>'直式金鸡报喜2', 'gameid'=>'129',
                'gameurl'=>'/images/game/cq9/129_StraightGolden2.png',
            ),
            array(
                'name'=>'偷金妹子', 'gameid'=>'130',
                'gameurl'=>'/images/game/cq9/130_Stealing.png',
            ),
            array(
                'name'=>'直式发财神', 'gameid'=>'131',
                'gameurl'=>'/images/game/cq9/131_Straightfortune.png',
            ),
            array(
                'name'=>'再喵一个', 'gameid'=>'132',
                'gameurl'=>'/images/game/cq9/132_OneMore.png',
            ),
            array(
                'name'=>'直式洪福齐天', 'gameid'=>'133',
                'gameurl'=>'/images/game/cq9/133_StraightHongfu.png',
            ),
            array(
                'name'=>'家裡有矿', 'gameid'=>'134',
                'gameurl'=>'/images/game/cq9/134_mines.png',
            ),
            array(
                'name'=>'奔跑吧猛兽', 'gameid'=>'136',
                'gameurl'=>'/images/game/cq9/136_Run.png',
            ),
            array(
                'name'=>'直式蹦迪', 'gameid'=>'137',
                'gameurl'=>'/images/game/cq9/137_Straight.png',
            ),
            array(
                'name'=>'跳过來', 'gameid'=>'138',
                'gameurl'=>'/images/game/cq9/138_Jump.png',
            ),
            array(
                'name'=>'直式火燒連環船', 'gameid'=>'139',
                'gameurl'=>'/images/game/cq9/139_StraightFire.png',
            ),
            array(
                'name'=>'火烧连环船2', 'gameid'=>'140',
                'gameurl'=>'/images/game/cq9/140_FireBurning.png',
            ),
            array(
                'name'=>'圣诞来啦', 'gameid'=>'141',
                'gameurl'=>'/images/game/cq9/141_Christmas.png',
            ),
            array(
                'name'=>'火神', 'gameid'=>'142',
                'gameurl'=>'/images/game/cq9/142_Hephaestus.png',
            ),
            array(
                'name'=>'发财福娃', 'gameid'=>'143',
                'gameurl'=>'/images/game/cq9/143_FortuneFuwa.png',
            ),
            array(
                'name'=>'钻更多', 'gameid'=>'144',
                'gameurl'=>'/images/game/cq9/144_Drillmore.png',
            ),
            array(
                'name'=>'印金工厂', 'gameid'=>'145',
                'gameurl'=>'/images/game/cq9/145_Yinjin.png',
            ),
            array(
                'name'=>'九莲宝灯', 'gameid'=>'146',
                'gameurl'=>'/images/game/cq9/146_Jiulian.png',
            ),
            array(
                'name'=>'花开富贵', 'gameid'=>'147',
                'gameurl'=>'/images/game/cq9/147_Blossoming.png',
            ),
            array(
                'name'=>'有如神柱', 'gameid'=>'148',
                'gameurl'=>'/images/game/cq9/148_pillar.png',
            ),
            array(
                'name'=>'龙舟', 'gameid'=>'149',
                'gameurl'=>'/images/game/cq9/149_Dragon.png',
            ),
            array(
                'name'=>'寿星大发', 'gameid'=>'150',
                'gameurl'=>'/images/game/cq9/150_Shouxing.png',
            ),
            array(
                'name'=>'龙虎水果机 ', 'gameid'=>'151',
                'gameurl'=>'/images/game/cq9/151_FruitMachine.png',
            ),
            array(
                'name'=>'双飞', 'gameid'=>'152',
                'gameurl'=>'/images/game/cq9/152_Doubleflight.png',
            ),
            array(
                'name'=>'六顆糖', 'gameid'=>'153',
                'gameurl'=>'/images/game/cq9/153_Sixsugar.png',
            ),
            array(
                'name'=>'宙斯他爹', 'gameid'=>'154',
                'gameurl'=>'/images/game/cq9/154_Kronos.png',
            ),
            array(
                'name'=>'五形拳', 'gameid'=>'157',
                'gameurl'=>'/images/game/cq9/157_Boxing.png',
            ),
            array(
                'name'=>'贏光派對', 'gameid'=>'159',
                'gameurl'=>'/images/game/cq9/159_NeonBoozeUp8.png',
            ),
            array(
                'name'=>'发財神2', 'gameid'=>'160',
                'gameurl'=>'/images/game/cq9/160_FaCaiShen.png',
            ),
            array(
                'name'=>'大力神', 'gameid'=>'161',
                'gameurl'=>'/images/game/cq9/161_Hercules.png',
            ),
            array(
                'name'=>'哪吒再临', 'gameid'=>'163',
                'gameurl'=>'/images/game/cq9/163_NeZhaAdvent.png',
            ),
            array(
                'name'=>'幸运星', 'gameid'=>'164',
                'gameurl'=>'/images/game/cq9/164_LuckStar.png',
            ),
            array(
                'name'=>'豪运十倍', 'gameid'=>'165',
                'gameurl'=>'/images/game/cq9/165_TenfoldLottery.png',
            ),
            array(
                'name'=>'猪事大吉', 'gameid'=>'168',
                'gameurl'=>'/images/game/cq9/168_GoldenPigs.png',
            ),
            array(
                'name'=>'中国锦鲤', 'gameid'=>'170',
                'gameurl'=>'/images/game/cq9/170_ChinaKoi.png',
            ),
        );

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
