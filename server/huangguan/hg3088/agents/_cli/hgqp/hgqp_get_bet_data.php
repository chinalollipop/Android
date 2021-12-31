<?php
/**
 * 定时拉取皇冠棋牌注单数据
 * 1.按固定时间间隔循环调用，5-10s只允许调用一次，每次最大返回100条未同步数据。
 * 2.返回已同步数据记录id给游戏平台。只保留近一个月的数据。每次请求最多返回两天的数据。
 * Date: 2018/11/8
 * Time: 14:20
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR . '/app/agents/include/hgqp/ApiHg.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/ff_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/ff_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    getBetData();
}

function getBetData()
{
    global $dbMasterLink, $dbLink;

    $now = strtotime('+12 hour');

    // 1.调用三方接口拉取数据
    $params = [
        'method' => 'getGameRecord',
        'time' => $now
    ];
    $aResult = ffApi($params);
    if(isset($aResult) && $aResult['code'] != 1){ // 拉取失败
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $now) . "】，三方返回错误码【{$aResult['code']}】", 1);
        return false;
    }

    if(empty($aResult['data'])){  // 没有拉取到数据
        writeLog("【" . date('Y-m-d H:i:s') . "】同步时间【" . date('Y-m-d H:i:s', $now) . "】，暂无注单数据");
        return false;
    }

    $aUsername = $aSerial = [];
    foreach ($aResult['data'] as $key => $value){
        $aUsername[] = $value['sitemid'];
        $aSerial[] = $value['serial'];
    }

    // 2.查询皇冠棋牌会员
    $aUsername = array_unique($aUsername); // 去重
    writeLog("【" . date('Y-m-d H:i:s') . "】皇冠棋牌会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");

    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'ff_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】皇冠棋牌会员查询失败\n");
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
    foreach ($aResult['data'] as $key => &$value){
        // 斗地主，抢庄牛牛，通比牛牛，德州扑克 bet表示下注倍数;百人牛牛，龙虎斗，百人扎金花，二八杠 bet表示下注金额
        $multiple = 1;
        $bet = $value['valid_bet']; // 默认有效投注金额为下注金额
        if(in_array($value['ssid'], ['3012', '3015', '3018', '3019'])){
            $multiple = $value['bet'];
        }else{
            $bet = $value['bet'];
        }
        $aTemp = [
            'mid' => $value['mid'],
            'sid' => $value['sid'],
            'channel' => $value['channel'],
            'ssid' => $value['ssid'],
            'level' => $value['level'],
            'scoins' => $value['scoins'] / 100,         // 分模式转元模式
            'wincoins' => $value['wincoins'] / 100,     // 分模式转元模式
            'multiple' => $multiple,                    // 下注倍数
            'bet' => $bet / 100,                        // 分模式转元模式
            'valid_bet' => $value['valid_bet'] / 100,   // 分模式转元模式
            'board_fee' => $value['board_fee'] / 100,   // 分模式转元模式
            'points' => $value['points'],
            'banker_uid' => $value['banker_uid'],
            'banker_points' => $value['banker_points'],
            'bottom_points' => $value['bottom_points'],
            'game_endtime' => date('Y-m-d H:i:s', $value['time']), // 入库美东时间
            'board_id' => $value['board_id'],
            'serial' => $value['serial'],
            'sync' => $value['sync'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if($value['sitemid'] == $aUser[$value['sitemid']]['username']){
            $aInsertData[] = array_merge($aUser[$value['sitemid']], $aTemp);
        }
    }

    $keys = $values = '';
    $count = count($aInsertData);
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库皇冠棋牌注单表
    $dbMasterLink->autocommit(false);
    $sql = "REPLACE INTO `" . DBPREFIX . "ff_projects` {$keys} VALUES {$values}";
    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $now) . "】，错误【" . mysqli_error($dbMasterLink) . "】入库注单数据失败", 1);
        $dbMasterLink->rollback();
        return false;
    }

    // 5.调用三方接口同步数据
    $paramsSync = [
        'method' => 'updateGameRecordSyncStatus',
        'ids' => implode(',', $aSerial),
        'time' => $now
    ];
    $aResultSync = ffApi($paramsSync);
    if(isset($aResultSync) && $aResultSync['code'] != 1){ // 同步失败
        $dbMasterLink->rollback();
        writeLog("【" . date('Y-m-d H:i:s') . "】同步时间【" . date('Y-m-d H:i:s', $now) . "】，三方返回错误码【{$aResultSync['code']}】", 1);
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
function ffApi($params){
    global $ffConfig;
    $oKy = new \app\agents\ApiHg($ffConfig);
    try {
        ob_start();
        $res = $oKy->main($params);
        return json_decode($res, true);
    } catch (Exception $e) {
        ob_end_clean();
        return array(
            'm' => 'ffProxy',
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