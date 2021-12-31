<?php
/**
 * 定时拉取VG棋牌注单数据
 * 1.按固定时间间隔循环调用，5-10s只允许调用一次，每次最大返回100条未同步数据。
 * 2.返回已同步数据记录id给游戏平台。只保留近一个月的数据。每次请求最多返回两天的数据。
 * Date: 2018/11/8
 * Time: 14:20
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
//require_once CONFIG_DIR . '/app/agents/include/vgqp/ApiVg.php';
require_once ROOT_DIR . '/common/vgqp/api.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/vg_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/vg_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    getBetData();
}

function getBetData()
{
    global $dbMasterLink, $dbLink;


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
    //$datastr = getVgQpSetting();
    //$agentChannel = $datastr['agentid'];

    $now = strtotime('+12 hour');

    // 1. 获取注单表最大id
//    $p_sql = 'SELECT  max(`serial`) as serial FROM ' . DBPREFIX . 'vg_projects WHERE game_endtime=(SELECT max(`game_endtime`) FROM ' . DBPREFIX . 'vg_projects)';
    $p_sql = 'SELECT `serial` FROM ' . DBPREFIX . 'vg_projects ORDER BY `createtime` DESC limit 1';
    $p_result = mysqli_query($dbLink, $p_sql) or die("【" . date('Y-m-d H:i:s') . "】VG棋牌注单查询失败\n");
    $p_row = mysqli_fetch_assoc($p_result);
    $serial = !empty($p_row['serial']) ?  $p_row['serial'] : 0;
    // 2.调用三方接口拉取数据
    $params = [
        'action' => 'GetRecordByID',
        'recordID' => strval($serial),
        'apitoken' => $apitoken,
    ];
    $aResult = vgApi($params);

    if(isset($aResult) && $aResult['state'] != 0){ // 拉取失败
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $now) . "】，三方返回错误码【{$aResult['state']}】", 1);
        return false;
    }

    if($aResult['value']['Num'] == 0 ){  // 没有拉取到数据
        writeLog("【" . date('Y-m-d H:i:s') . "】同步时间【" . date('Y-m-d H:i:s', $now) . "】，暂无注单数据");
        return false;
    }

    $aUsername = [];
    foreach ($aResult['value']['GameRecords'] as $key => $value){
        $aSerial[] = $value['id'];  // 注单号
        $aUsername[] = explode('_' , $value['Username'], 2)['1'];
    }

    // 3.查询VG棋牌会员
    $aUsername = array_unique($aUsername); // 去重
    writeLog("【" . date('Y-m-d H:i:s') . "】VG棋牌会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");

    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'vg_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】VG棋牌会员查询失败\n");
    $aUser = [];
    while ($row = mysqli_fetch_assoc($result)){
        $aUser[$row['username']] = [
            'userid' => $row['userid'],
            'username' => $row['username'],
            'agents' => $row['agents'],
            'world' => $row['world'],
            'corporator' => $row['corporator'],
            'super' => $row['super'],
            'admin' => $row['admin'],
            'is_test' => $row['is_test'],
        ];
    }

    // 4.整理入库数据
    $aInsertData = $aTemp = [];
    foreach ($aResult['value']['GameRecords'] as $key => &$value){

        //注单名 H777_john104  转换成 john104
        $value['UserName'] = explode('_' , $value['Username'], 2)['1'];

        $aTemp = [
            'serial' => $value['ID'],   //唯一id 注单号
            'channel' => $value['Channel'],
            'createtime' => date('Y-m-d H:i:s', strtotime($value['CreateTime']) - 12 * 3600), // 入库美东时间,游戏创建时间  date('Y-m-d H:i:s', strtotime($value['createtime']) - 12 * 3600)
            'gametype' => $value['GameType'],   //游戏类型ID
            'roomid' => $value['RoomID'],   //房间编号
            'tableid' => $value['TableID'], //桌号
            'roundid' => $value['RoundID'], //局ID
            'betamount' => $value['BetAmount'], // 投注额 元模式
            'validbetamount' => $value['ValidBetAmount'], // 有效投注额
            'betpoint' => $value['BetPoint'],     // 下注点
            'odds' => $value['Odds'],   // 赔率(翻倍数)
            'money' => $value['Money'], // 输赢金额
            'servicemoney' => $value['ServiceMoney'], // 服务费
            'begintime' => date('Y-m-d H:i:s', strtotime($value['BeginTime']) - 12 * 3600), //游戏开始时间  // 入库美东时间
            'game_endtime' => date('Y-m-d H:i:s', strtotime($value['EndTime']) - 12 * 3600),  //游戏结束时间  // 入库美东时间
            'isbanker' => $value['IsBanker'],   //庄闲 1 表示庄 0 表示闲
            'gameinfo' => $value['GameInfo'],   //发牌情况
            'gameresult' => $value['GameResult'],   //游戏结果，0=输，1=赢，2=和
            'beforebalance' => $value['BeforeBalance'], //开局时用户余额
            'info1' => $value['Info1'],
            'info2' => $value['Info2'],
            'info3' => $value['Info3'],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if($value['UserName'] == $aUser[$value['UserName']]['username']){
            $aInsertData[] = array_merge($aUser[$value['UserName']], $aTemp);
        }
    }

    $keys = $values = '';
    $count = count($aInsertData);
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 5.入库VG棋牌注单表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "vg_projects` {$keys} VALUES {$values}";
    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $now) . "】，错误【" . mysqli_error($dbMasterLink) . "】入库注单数据失败", 1);
        $dbMasterLink->rollback();
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    $dbMasterLink->commit();
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】");
    return true;
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
 * 调用三方接口
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

/**
 * 记录日志文件
 * @param $log
 * @param bool $isError
 */
function writeLog($log, $isError = false)
{
    global $logFile;
    echo $log . "\n";
    if($isError)
        @file_put_contents($logFile, $log . "\n", FILE_APPEND);
}