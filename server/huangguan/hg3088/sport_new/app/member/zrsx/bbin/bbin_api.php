<?php
/**
 * 自定义BBIN真人API
 *
 *  执行任务 action （默认检查BBIN账号，或者创建账号）
 *       b  获取余额
 *       hg2bbin  平台上分到bbin
 *       bbin2hg  bbin下分到平台
 *       getLaunchGameUrl  真钱模式
 */

//error_reporting(E_ALL);
//ini_set('display_errors','Off');
define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
include_once "../../include/config.inc.php";
include_once ROOT_DIR . '/common/bbin/api.php';

$uid = $_SESSION['Oid']?$_SESSION['Oid']:$_REQUEST['uid'];
$userid = $_SESSION['userid'] ;

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `layer`, `test_flag`, `UserName`, `LoginName`,  `Alias`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone`, `birthday` FROM ".DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
if(!$stmt->affected_rows) {
    $status = '401.1';
    $describe = '您的登录信息已过期，请您重新登录！';
    original_phone_request_response($status,$describe);
}
$aUser = $stmt->get_result()->fetch_assoc();

if ($aUser['test_flag']){
    $status = '401.2';
    $describe = '请登录真实账号登入BBIN视讯！';
    original_phone_request_response($status,$describe);
}

// 2.接收参数
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? trim($_REQUEST['action']) : '';
$exchangeFrom = isset($_REQUEST['f']) && $_REQUEST['f'] ? trim($_REQUEST['f']) : '';
$exchangeTo = isset($_REQUEST['t']) && $_REQUEST['t'] ? trim($_REQUEST['t']) : '';
$b = $score = isset($_REQUEST['b']) && $_REQUEST['b'] ? trim($_REQUEST['b']) : 0;
$platform = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:'' ;
$testFlag = isset($_REQUEST['flag']) && $_REQUEST['flag'] ? trim($_REQUEST['flag']) : '';

if(!$action){
    $status = '401.0';
    $describe = '参数异常！';
    original_phone_request_response($status,$describe);
}
// 判断BBIN视讯是否维护-单页面维护功能
if($action=='getLaunchGameUrl'){ // 打开游戏，判断维护
    checkMaintain('bbin');
}else if($action=='fundLimitTrans'){ // 转账

    $pageMark = 'bbin';
    $aRow = getMaintainDataByCategory($pageMark);
    $aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
    if ($aRow['state']==1 and in_array(1, $aTerminal) and ($_REQUEST['action'] != 'b') ) {
        $status = '555';
        $describe = 'BBIN视讯维护中，请选择其他游戏';
        original_phone_request_response($status,$describe);
    }
}

if($exchangeFrom == 'hg' && $exchangeTo == 'bbin'){
    $action = 'hg2bbin';
}

if($exchangeFrom == 'bbin' && $exchangeTo == 'hg'){
    $action = 'bbin2hg';
}
$sUserlayer = $aUser['layer'];
// 转账时，校验操作额度分层
if ($action=='hg2bbin' || $action == 'bbin2hg'){
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
// 3.检测登录BBIN会员
$bbinExist = 0;
$now = date('Y-m-d H:i:s');
$md5Key = $bbinSxInit['data_api_md5_key'];
if($action){
    $result = mysqli_query($dbLink, "SELECT `userid`,`username`,`password`,`ext_ref`,`bbin_user_info` FROM `" . DBPREFIX . "jx_bbin_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
    $bbinExist = mysqli_num_rows($result);
    $res = mysqli_fetch_assoc($result);

    $username = $bbin_username=strtoupper($bbin_prefix.$aUser['UserName']);  // 用户名大写 CHDEVJOHN107
    //$username = explode('_', $bbin_username , 2)['1'];
    $password = $bbinExist ? $res['password'] : '';

    if(!$bbinExist){

        if($action == 'b') { // 未创建账号前请求余额接口
            $balance = [
                'bbin_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            $status = '200';
            $describe = '获取余额成功！';
            original_phone_request_response($status,$describe,$balance);
        }else {

            $length = rand(10,12);
            $password = make_char($length);  //玩家密码

            $createRes = createMemberSignUp($bbin_agent, $md5Key, $username, $password);    // 创建会员

            if ($createRes['success']){
                $data = [
                    'userid' => $aUser['ID'],
                    'username' => $bbin_username,
                    'password' => $password, //$member_password
                    'ext_ref' => $bbin_agent,
                    'agents' => $aUser['Agents'],
                    'world' => $aUser['World'],
                    'corporator' => $aUser['Corprator'],
                    'super' => $aUser['Super'],
                    'admin' => $aUser['Admin'],
                    'register_time' => $now,
                    'last_launch_time' => $now,
                    'launch_times' => 1,
                    'is_test' => $aUser['test_flag'],
                    'bbin_user_info' => json_encode($createRes)
                ];
                $sInsData = '';
                foreach ($data as $key => $value){
                    $sInsData .= "`$key` = '{$value}'" . ($key == 'bbin_user_info' ? '' : ',');
                }
                $sql = "INSERT INTO `" . DBPREFIX . "jx_bbin_member_data` SET $sInsData";

                if (!mysqli_query($dbMasterLink, $sql)) {
//                    exit(json_encode(['err' => -5, 'msg' => 'BBIN账号异常，请您稍后重试！']));
                    $status = '401.5';
                    $describe = 'BBIN账号异常，请您稍后重试！';
                    original_phone_request_response($status,$describe);
                }else{
                    $status = '200';
                    $describe = 'BBIN账号初始化成功，请您继续提交转账';
                    original_phone_request_response($status,$describe);
                    //echo '<script>alert("BBIN账号初始化成功，请您继续转账");location.reload();</script>'; die;
                }
            }else{
//                exit(json_encode(['err' => -6, 'msg' => '注册OG账号失败'.json_encode($res)]));
                $status = '401.6';
                $describe = '注册BBIN账号失败，请联系技术';
                original_phone_request_response($status,$describe);
            }
        }
    }
}

$result = mysqli_query($dbLink, "SELECT `userid`,`username`,`password`,`ext_ref`,`bbin_user_info`  FROM `" . DBPREFIX . "jx_bbin_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
$res = mysqli_fetch_assoc($result);

switch ($action){
    case "cm":  // 会员 免费试玩或立即游戏
        if($testFlag !== 'test')  { // 正式会员
            $status = '401.2';
            $describe = '请登录真实账号登入BBIN视讯！';
            original_phone_request_response($status,$describe);

        }
        $res = bbinForwardGame($bbin_agent, $md5Key, $username, $password, $language);  //进入游戏
        if (!$res['success']){
            //exit(json_encode( [ 'err'=>'-1','msg'=>'游戏链接获取失败' ] ));
            $status = '-1';
            $describe = 'BBIN游戏链接获取失败';
            original_phone_request_response($status,$describe);
            //exit("<script>alert('游戏链接获取失败".json_encode($res)."');window.close();</script>");
        }

        $toURL = $res['body']['data']['loginurl'];

        if($bbinExist && $res['success']){ // 账号存在，登入成功
            mysqli_query($dbMasterLink, "update " . DBPREFIX . "jx_bbin_member_data set launch_times = launch_times + 1, last_launch_time = '$now'  WHERE userid = '{$aUser['ID']}'");
        }

        if($platform==13 || $platform==14) {
            $status = '200';
            $describe = '恭喜成功获取APP地址';
            $data['url'] = $toURL;
            original_phone_request_response($status,$describe,$data);
        }
        else{
            //header("Location:".$toURL);
            exit(json_encode(['code' => 0, 'success' => $linkRes['success'], 'url' => $toURL]));
            //header("Location:".$linkRes['body']['url']);
        }
        break;
    case 'b':
        $res = bbinGetBalance ($bbin_agent, $md5Key, $username, $password); //获取账号余额
        if (!$res['success']){
//            exit(json_encode( [ 'err'=>'-1','msg'=>'余额获取失败'.json_encode($res) ] ));
            /*$status = '401.7';
            $describe = '余额获取失败，请联系技术';*/
            $balance = [
                'bbin_balance' => '0.00',
                'hg_balance' => formatMoney($aUser['Money'])
            ];
            $status = '200';
            $describe = '获取余额成功！';
            original_phone_request_response($status,$describe,$balance);
        }
        else{
//            exit( json_encode( ['err'=>0, 'balance_og'=>number_format($res["body"]['balance'],2) ] ) );
            $balance = [
                'bbin_balance' => sprintf('%.2f',$res["body"]['data']['credit']),
                'hg_balance' => formatMoney($aUser['Money'])
            ];

            $status = '200';
            $describe = '获取余额成功！';
            original_phone_request_response($status,$describe,$balance);
        }
        break;
    case 'hg2bbin':
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
            original_phone_request_response('2004','皇冠体育余额不足！');
        }
        $afterBalance = bcsub($beforeBalance, $score, 4); // 转换之后余额

        // 更新会员余额
        if(!$updated = mysqli_query($dbMasterLink, 'UPDATE '.DBPREFIX.MEMBERTABLE.' SET `Money` = ' . $afterBalance . ' WHERE `ID` = ' . $aUser['ID'])) {
            $dbMasterLink->rollback();
            original_phone_request_response('2005','额度转换失败，请您稍后重试！');
        }

        // BBIN-orderId
        //$time_str = date('YmdHis'); // 美东时间
        $orderId = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

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
            'To' => 'bbin',
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
            'reason' => 'HG TO BBIN',
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
            52, // type：52 体育转BBIN真人视讯
            22,
            $insertId,
            '体育转BBIN真人视讯'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            original_phone_request_response('2007','额度转换失败，请您稍后重试！');
        }

        // 2.4.调用三方上分接口
        //{"data":{"balance":10,"currency":"CNY"},"status":{"code":"0","message":"Success","datetime":"2019-10-16T06:13:54-04:00","traceCode":"bBCuoaf7ws"}}
        $aResult = bbinTransferMoney ($bbin_agent, $md5Key, $username, $orderId, $type = 0, $score); //HG TO BBIN
        if($aResult){
            if($aResult['success'] == true){
                $dbMasterLink->commit();

                $getBalanceRes = bbinGetBalance($bbin_agent, $md5Key, $username, $password); //获取账号余额
                if($getBalanceRes['success']) {
                    $aResult['body']['balance'] = sprintf('%.2f',$getBalanceRes["body"]['data']['credit']);
                }
                $data = [
                    'bbin_balance' => $aResult['body']['balance'],
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
            $aResult = bbinTransferQuery ($bbin_agent, $md5Key, $orderId); //单一交易查詢
            if($aResult){
                if($aResult['success'] == false){
                    $dbMasterLink->rollback();
                    original_phone_request_response('2009','额度转换失败，请您稍后重试！');
                    break;
                }
                if($aResult['success'] == true){
                    $dbMasterLink->commit();

                    $getBalanceRes = bbinGetBalance($bbin_agent, $md5Key, $username, $password); //获取账号余额
                    if ($getBalanceRes['success']){
                        $aResult['body']['balance'] = sprintf('%.2f',$getBalanceRes["body"]['data']['credit']); //元模式
                    }
                    $data = [
                        'bbin_balance' => sprintf('%.2f',$aResult['body']['balance']),
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
    case 'bbin2hg':

        // 1.参数校验
        if(!preg_match("/^[1-9][0-9]*$/", $score)){
            //exit(json_encode([ 'err' => '401', 'msg' => '转账金额格式错误，请重新输入!' ]));
            original_phone_request_response('401','转账金额格式错误，请重新输入!');
        }

        if ($score > 10000000){
            original_phone_request_response('2011','单次下分不能超过一千万，请重新输入！');
        }
        // 2.查询BBIN视讯可下分余额
        $aResult = bbinGetBalance($bbin_agent, $md5Key, $username, $password); //获取账号余额
        if ($aResult['success'] == true){
            $bbinBalance = sprintf('%.2f',$aResult["body"]['data']['credit']);
        } else{
            original_phone_request_response('2022','BBIN余额获取失败，请稍后重试！');
        }

        if(intval($bbinBalance) < intval($score)){
            original_phone_request_response('2023','BBIN视讯余额不足！');
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
        // BBIN-orderId
        //$time_str = date('YmdHis'); // 美东时间
        $orderId = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8); // 订单号生成规则

        // 3.2.入库额度转换表
        $data = [
            'userid' => $aUser['ID'],
            'Checked' => 1,
            'Gold' => $score,
            'moneyf' => $beforeBalance,
            'currency_after' => $afterBalance,
            'AddDate' => $now,
            'Type' => 'Q',
            'From' => 'bbin',
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
            'reason' => 'BBIN TO HG',
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
            53, // type：53 BBIN视讯转体育
            22,
            $insertId,
            'BBIN真人视讯转体育'
        ];
        if(!$inserted = addAccountRecords($data)){
            $dbMasterLink->rollback();
            original_phone_request_response('2026','额度更新失败，请您稍后重试！');
        }

        // 3.4.调用三方下分接口
        $aResult = bbinTransferMoney ($bbin_agent, $md5Key, $username, $orderId, $type = 1, $score); //BBIN TO HG
        if($aResult){
            if($aResult['success'] == true){
                $dbMasterLink->commit();

                $getBalanceRes = bbinGetBalance($bbin_agent, $md5Key, $username, $password); //获取账号余额
                if($getBalanceRes['success']) {
                    $aResult['body']['balance'] = sprintf('%.2f',$getBalanceRes["body"]['data']['credit']);
                }
                $data = [
                    'bbin_balance' => sprintf('%.2f', $aResult['body']['balance']),
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
            $aResult = bbinTransferQuery ($bbin_agent, $md5Key, $orderId); //单一交易查詢
            if($aResult){
                if($aResult['success'] == false){
                    $dbMasterLink->rollback();
                    original_phone_request_response('2009','额度转换失败，请您稍后重试！');
                    //exit(json_encode(['err' => 2009, 'msg' => '额度转换失败，请您稍后重试！']));
                    break;
                }
                if($aResult['success'] == true){
                    $dbMasterLink->commit();

                    $getBalanceRes = bbinGetBalance($bbin_agent, $md5Key, $username, $password); //获取账号余额
                    if ($getBalanceRes['success']){
                        $aResult['body']['balance'] = sprintf('%.2f',$getBalanceRes["body"]['data']['credit']); //元模式
                    }
                    $data = [
                        'bbin_balance' => sprintf('%.2f', $aResult['body']['balance']),
                        'hg_balance' => formatMoney($afterBalance)
                    ];
                    $status = '200';
                    $describe = '获取余额成功！';
                    original_phone_request_response($status,$describe,$data);
                    break;
                }
            }else{ // 超时or错误返回NULL
                $dbMasterLink->rollback();
                original_phone_request_response('400','额度转换失败，请您稍后重试！');
            }
        }
        break;
    case 'getLaunchGameUrl':

        // 获取游戏地址
        $res = BbinForwardGame($bbin_agent, $md5Key, $username, $password, $bbinGamePresentLive , $language);

        if (!$res['success']){
//            exit(json_encode( [ 'err'=>'-1','msg'=>'游戏链接获取失败' ] ));
            exit("<script>alert('游戏链接获取失败".json_encode($res)."');window.close();</script>");
        }

        $toURL = $res['body']['data']['loginurl'];

        if($platform==13 || $platform==14) {
            $status = '200';
            $describe = '恭喜成功获取APP地址';
            $data['url'] = $toURL;
            original_phone_request_response($status,$describe,$data);
        }
        else{
            header("Location:".$toURL);
        }
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