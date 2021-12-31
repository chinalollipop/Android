<?php
/**
 * 定时抓取OG视讯注单数据
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间10分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2019/9/7
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/og/api.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/og_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/og_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time()+12*60*60; // og注单，转为北京时间拉取
    if(isset($argv[1]) && isset($argv[2])){
        $startTime = trim($argv[1])+12*60*60;
        $endTime = trim($argv[2])+12*60*60;
    }else{
        $startTime = ($time - 780);
        $endTime = ($time - 180);
    }
    getBetData($startTime, $endTime);
}

function getBetData($startTime, $endTime)
{
    global $dbMasterLink, $dbLink, $x_key, $x_operator, $og_auto_preifx;
    $startDate = date('Y-m-d H:i:s',$startTime);
    $endDate = date('Y-m-d H:i:s',$endTime);

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s') . '-' . date('Y-m-d H:i:s') . "】，拉取时间区间：【{$startDate}-{$endDate}】");

    $data=array(
        'Operator'=>$x_operator,
        'Key'=>$x_key,
        'SDate'=>$startDate,
        'EDate'=>$endDate,
        'Provider'=>'ogplus',
    );
    $aResult = getBetRecords ('POST', 'query_string', $data);

    $aUsername = $aBetData = [];
    if ($aResult['success']){
        // 注单等交易数组
        foreach ($aResult['body'] as $key => $value){
            $value['membername'] = explode($og_auto_preifx, $value['membername'])[1];;
            $aBetData[$key] = $value;
            $aUsername[] = $value['membername'];
        }
    }else{
        writeLog("【" . date('Y-m-d H:i:s') . "】三方返回错误码".json_encode($aResult));
        return false;
    }
    $aUsername = array_unique($aUsername); // 去重

    // 2.查询OG会员
    writeLog("【" . date('Y-m-d H:i:s') . "】OG会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'og_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】OG会员查询失败\n");
    $aUser = [];
    while ($row = mysqli_fetch_assoc($result)){
        $aUser[strtolower($row['username'])] = [
            'userid' => $row['userid'],
            'username' => strtolower($row['username']),
            'agents' => $row['agents'],
            'world' => $row['world'],
            'corporator' => $row['corporator'],
            'super' => $row['super'],
            'admin' => $row['admin'],
        ];
    }

//    print_r($aUser);
//    print_r($aBetData);
//    print_r($aUsername); die;

    // 3.整理入库数据
    $aInsertData = $aTemp = [];
    foreach ($aBetData as $key => &$value){
        $aTemp = [
            'gamename' => $value['gamename'],
            'bettingcode' => $value['bettingcode'],
            'bettingdate' => $value['bettingdate'],
            'md_bettingdate' => date('Y-m-d H:i:s',strtotime($value['bettingdate'])-12*60*60), // 投注时间，美东时间
            'gameid' => $value['gameid'],
            'roundno' => $value['roundno'],
            'game_information' => json_encode($value['game_information'], JSON_UNESCAPED_UNICODE),
            'result' => $value['result'],
            'bet' => $value['bet'],
            'winloseresult' => $value['winloseresult'],
            'bettingamount' => $value['bettingamount'],
            'validbet' => $value['validbet'],
            'winloseamount' => $value['winloseamount'],
            'balance' => $value['balance'],
            'currency' => $value['currency'],
            'status' => $value['status'],
            'gamecategory' => $value['gamecategory'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if($value['membername'] == $aUser[$value['membername']]['username']){
            $aInsertData[] = array_merge($aUser[$value['membername']], $aTemp);
        }
    }

    $count = count($aInsertData);
    if ($count<1){
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】");
        return false;
    }
    $keys = $values = '';
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库OG注单表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "og_projects` {$keys} VALUES {$values}";

//  echo $sql; die;

    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $dbMasterLink->rollback();
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime) . '至' . date('Y-m-d H:i:s', $endTime) . "】，时间戳：【{$startTime}至{$endTime}】，拉取注单数据失败，原因：入库失败【{$inserted}】", 1);
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】");
    $dbMasterLink->commit();

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

function printLine ($message) {
    echo "<BR>{$message}";
}