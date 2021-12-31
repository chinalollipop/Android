<?php
/**
 * 定时抓取第三方彩票注单数据
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间1-5分钟，最大不能超过60分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2018/8/27
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/thirdcp/ApiProxy.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/thirdcp_log/ssc_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/third_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time();
    if(isset($argv[1]) && isset($argv[2])){
        //$startTime = trim($argv[1]) * 1000;
        //$endTime = trim($argv[2]) * 1000;
        $startTime = strtotime($argv[1] . ' 00:00:00') * 1000;
        $endTime = strtotime($argv[2] . ' 23:59:59') * 1000;
    }else{
        $startTime = ($time - 1380) * 1000;  //13分钟前   780    23分钟前 1380
        $endTime = ($time - 180) * 1000;    //3分钟前
        //$startTime = 1566144000 * 1000;  //2019-08-19 00:00:00
        //$endTime = 1566316800 * 1000;    //2019-08-21 00:00:00
    }
    getBetData($startTime, $endTime);
}

function getBetData($startTime, $endTime)
{
    global $dbMasterLink, $dbLink;

    $now = strtotime('+12 hour');

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s', $startTime / 1000) . '至' . date('Y-m-d H:i:s', $endTime / 1000) . "】，时间戳：【{$startTime}至{$endTime}】", 1);

    $params = [
        's' => 1,
        'startTime' => $startTime,
        'endTime' => $endTime
    ];

    $aResult = getThirdCpApi($params);

    if(isset($aResult) && $aResult['error'] != ''){ // 拉取失败
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $now) . "】，三方返回错误码【{$aResult['error']}】");
        return false;
    }

    if(empty($aResult['data']['totalCount'])  || empty($aResult['data']['data'])){  // 没有拉取到数据
        writeLog("【" . date('Y-m-d H:i:s') . "】同步时间【" . date('Y-m-d H:i:s', $now) . "】，暂无注单数据");
        return false;
    }

    $aUsername = [];
    foreach ($aResult['data']['data'] as $key => $value){
        //$aSerial[] = $value['id'];  // 注单号
        $aUsername[] = $value['username'];      //注单用户名
    }

    // 3.查询第三方彩票会员
    $aUsername = array_unique($aUsername); // 去重
    writeLog("【" . date('Y-m-d H:i:s') . "】第三方彩票会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】", 1);

    $sql = 'SELECT `id`, `userid`, `username`, `line_code`,`agents`,`world`,`corporator`,`admin`,`is_lock`,`is_test` FROM ' . DBPREFIX  .'cp_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】第三方彩票会员查询失败\n");

    $aUser = [];
    while ($row = mysqli_fetch_assoc($result)){
        $aUser[$row['username']] = [
            //'is_agent' => $row['is_agent'],
            'username' => $row['username'],
            'hg_uid' => $row['userid'],
            'agents' => $row['agents'], //会员代理
        ];
    }

    // 3.整理入库数据
    $aInsertData = $aTemp = $aNotUserName =[];
    foreach ($aResult['data']['data'] as $key => &$value){
        $aTemp = [
            'id' => $value['sscBetId'], //信用盘投注Id
            'hg_uid' => $aUser[$value['username']]['hg_uid'],
            'wjorderId' => $value['orderNo'],
            'uid' => $value['uid'],  //投注用户ID
            'username' => $value['username'],
            'nickname' => $value['nickname'],
            'type' => $value['gameId'],  //投注种类，对应ssc_type.id
            'playedGroup' => $value['playCateId'],  //玩法组ID
            'playedId' => $value['playId'],   //玩法ID
            'Groupname' => $value['Groupname'], //第几球、冠亚军
            'actionNo' => $value['turnNum'],       //投注期号
            'actionData' => $value['actionData'],   //投注号码
            'actionTime' => $value['addTime'], // 入库美东时间
            'odds' => $value['odds'],
            'rebate' => $value['rebate'],
            'rebateMoney' => $value['rebateMoney'],
            'fanDian' => $value['fanDian'],
            'fanDianAmount' => $value['fanDianAmount'],
            'bonus' => $value['bonus'], //中奖金额不包括退水金额
            'money' => $value['money'],
            'lotteryNo' => $value['lotteryNo'],
            'kjTime' => $value['openTime'], // 入库美东时间
            'zjCount' => $value['zjCount'],
            'flag' => $value['flag'],
            'isDelete' => $value['isDelete'],
            'orderId' => $value['orderId'],
            'totalNums' => $value['totalNums'],
            'totalMoney' => $value['totalMoney'],
            'betInfo' => $value['betInfo'],
            'status' => $value['status'],
            'won_count' => $value['won_count'],
            'counted_at' => date('Y-m-d H:i:s', strtotime($value['counted_at']) - 12 * 3600), // 入库美东时间
            'status_bonus' => $value['status_bonus'],
            'locked_bonus' => $value['locked_bonus'],
            'bonus_sent_at' => isset($value['bonus_sent_at']) ? date('Y-m-d H:i:s', strtotime($value['bonus_sent_at']) - 12 * 3600) : '',// 入库美东时间
            'status_rebate' => $value['status_rebate'],
            'created_at' => isset($value['created_at']) ? date('Y-m-d H:i:s', strtotime($value['created_at']) - 12 * 3600) : '', // 入库美东时间
            'updated_at' => isset($value['updated_at']) ? date('Y-m-d H:i:s', strtotime($value['updated_at']) - 12 * 3600) : '', // 入库美东时间
            'is_tester' => $value['is_tester'],
        ];

        if($value['username'] == $aUser[$value['username']]['username']){
        //if(strcasecmp($value['username'] , $aUser[$value['username']]['username'])==0){ // 不区分大小写
            $aInsertData[] = array_merge($aUser[$value['username']], $aTemp);
        }else{
           /* $aNotUserName[] = $value['username'];
            $aNotUserName = array_unique($aNotUserName);
            writeLog("【" . date('Y-m-d H:i:s') . "】拉取ssc注单cp_member_data表未匹配到会员名称【" .implode(',', $aNotUserName) . "】", 1);
            continue;*/
            array_push($aNotUserName, $value['username']);
        }
    }

    if(!empty($aNotUserName)) { //cp_member_data表未匹配到会员名称
        $aNotUserName = array_unique($aNotUserName);
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取ssc注单cp_member_data表未匹配到会员名称【" .implode(',', $aNotUserName) . "】", 1);
    }

    $keys = $values = '';
    $count = count($aInsertData);
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库第三方彩票信用盘注单表
    $dbMasterLink->autocommit(false);
    //$sql = "INSERT IGNORE INTO `" . DBPREFIX . "web_third_ssc_data` {$keys} VALUES {$values}";
    $sql = "REPLACE  INTO `" . DBPREFIX . "web_third_ssc_data` {$keys} VALUES {$values}";
    //writeLog("【" . date('Y-m-d H:i:s') . "】sql：【{$sql}】", 1);
    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $dbMasterLink->rollback();
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime / 1000) . '至' . date('Y-m-d H:i:s', $endTime / 1000) . "】，时间戳：【{$startTime}至{$endTime}】，拉取注单数据失败，原因：入库失败【{$inserted}】", 1);
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    $dbMasterLink->commit();
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】", 1);
    return true;
}

/**
 * 调用三方接口
 * @param $params
 * @return array|mixed
 */
function getThirdCpApi($params){
    $oThirdCp = new ThirdApiProxy();
    try {
        ob_start();
        $res = $oThirdCp->thirdMain($params);
        return json_decode($res, true);
    } catch (Exception $e) {
        ob_end_clean();
        return array(
            'm' => 'thirdProxy',
            's' => $params['s'],
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