<?php
/**
 * 定时抓取开元棋牌注单数据
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间1-5分钟，最大不能超过60分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2018/8/27
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR . '/app/agents/include/ky/ApiProxy.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/ky_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/ky_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time();
    if(isset($argv[1]) && isset($argv[2])){
        $startTime = trim($argv[1]) * 1000;
        $endTime = trim($argv[2]) * 1000;
//        if($limitTime + 180 > $time) // 若结束时间大于当前时间前3分钟
//            $limitTime = $time - 180;
//
//        $times = ceil(($limitTime - $startTime) / 600); // 10分钟抓取一次（注：时间间隔不宜过长）
//        for($i = 1; $i <= $times; $i ++){
//            $endTime = $times == $i ? $limitTime : $startTime + 600; // 若最后一次抓取
//            sleep(10);
//            writeLog("【" . date('Y-m-d H:i:s') . "】开元棋牌手动重新拉取数据，分【" . $times . "】次拉取，第【" . $i . "】次拉取");
//            getBetData($startTime * 1000, $endTime * 1000);
//            $startTime = $endTime;
//        }
    }else{
        $startTime = ($time - 780) * 1000;
        $endTime = ($time - 180) * 1000;
    }
    getBetData($startTime, $endTime);
}

function getBetData($startTime, $endTime)
{
    global $dbMasterLink, $dbLink;

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s', $startTime / 1000) . '至' . date('Y-m-d H:i:s', $endTime / 1000) . "】，时间戳：【{$startTime}至{$endTime}】");

    $params = [
        's' => 16,
        'startTime' => $startTime,
        'endTime' => $endTime
    ];
    $aResult = kyApi($params);
    $aUsername = $aBetData = [];
    if(isset($aResult['d']) && $aResult['d']['code'] == 0){
        $count = $aResult['d']['count'];
        if($startTime != $aResult['d']['start'] || $endTime != $aResult['d']['end']){
            writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取时间【" . date('Y-m-d H:i:s', $startTime / 1000) . '至' . date('Y-m-d H:i:s', $endTime / 1000) . "】，时间戳：【{$startTime}至{$endTime}】", 1);
            writeLog("【" . date('Y-m-d H:i:s') . "】与实际拉取时间不符，接口返回时间【" . date('Y-m-d H:i:s', $aResult['d']['start'] / 1000) . '至' . date('Y-m-d H:i:s', $aResult['d']['end'] / 1000) . "】，时间戳：【{$aResult['d']['start']}至{$aResult['d']['end']}】", 1);
            return false;
        }
        foreach ($aResult['d']['list'] as $key => $value){
            for($i = 0; $i < $count; $i ++){
                if($key == 'Accounts')
                    $aUsername[] = substr($value[$i], strpos($value[$i], '_') + 1);
                $aBetData[$i][$key] = in_array($key, ['Accounts', 'LineCode']) ? substr($value[$i], strpos($value[$i], '_') + 1) : $value[$i];
            }
        }
    }else{
        if($aResult['d']['code'] == 16){
            writeLog("【" . date('Y-m-d H:i:s') . "】三方返回错误码【16-没有注单数据】");
        }else{
            writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime / 1000) . '至' . date('Y-m-d H:i:s', $endTime / 1000) . "】，时间戳：【" . $startTime / 1000 . '至' . $endTime / 1000 . "】，三方返回错误码【{$aResult['d']['code']}】（若为空需重新拉取数据！！！）", 1);
        }
        return false;
    }
    $aUsername = array_unique($aUsername); // 去重

    // 2.查询开元会员
    writeLog("【" . date('Y-m-d H:i:s') . "】开元会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");

    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'ky_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】开元会员查询失败\n");
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

    // 3.整理入库数据
    $aInsertData = $aTemp = [];
    foreach ($aBetData as $key => &$value){
        $aTemp = [
            'gameid' => $value['GameID'],
            'serverid' => $value['ServerID'],
            'kindid' => $value['KindID'],
            'tableid' => $value['TableID'],
            'chairid' => $value['ChairID'],
            'usercount' => $value['UserCount'],
            'cellscore' => $value['CellScore'],
            'allbet' => $value['AllBet'],
            'curscore' => $value['CurScore'],
            'profit' => $value['Profit'],
            'revenue' => $value['Revenue'],
            'game_starttime' => date('Y-m-d H:i:s', strtotime($value['GameStartTime']) - 12 * 3600), // 入库美东时间
            'game_endtime' => date('Y-m-d H:i:s', strtotime($value['GameEndTime']) - 12 * 3600), // 入库美东时间
            'cardvalue' => $value['CardValue'],
            'channelid' => $value['ChannelID'],
            'linecode' => $value['LineCode'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if($value['Accounts'] == $aUser[$value['Accounts']]['username']){
            $aInsertData[] = array_merge($aUser[$value['Accounts']], $aTemp);
        }
    }

    $keys = $values = '';
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库开元注单表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "ky_projects` {$keys} VALUES {$values}";

    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $dbMasterLink->rollback();
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime / 1000) . '至' . date('Y-m-d H:i:s', $endTime / 1000) . "】，时间戳：【{$startTime}至{$endTime}】，拉取注单数据失败，原因：入库失败【{$inserted}】", 1);
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    $dbMasterLink->commit();
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】");
    return true;
}

/**
 * 调用三方接口
 * @param $params
 * @return array|mixed
 */
function kyApi($params){
    global $kyConfig;
    $oKy = new ApiProxy($kyConfig);
    try {
        ob_start();
        $res = $oKy->main($params);
        return json_decode($res, true);
    } catch (Exception $e) {
        ob_end_clean();
        return array(
            'm' => 'kyProxy',
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