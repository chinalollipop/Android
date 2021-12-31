<?php
/**
 * 定时抓取MW电子注单数据
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间10分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2019/10/19
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/mw/api.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/mw_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/mw_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time()+12*60*60; // mw注单，转为北京时间拉取
    if(isset($argv[1]) && isset($argv[2])){
        $startTime = trim($argv[1])+12*60*60;
        $endTime = trim($argv[2])+12*60*60;
    }else{
        $startTime = ($time - 1000);
        $endTime = ($time - 300);
    }
    getBetData($startTime, $endTime);
}

function getBetData($startTime, $endTime)
{
    global $dbMasterLink, $dbLink, $domainUrl;
    $startDate = date('Y-m-d H:i:s',$startTime);
    $endDate = date('Y-m-d H:i:s',$endTime);

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s') . '-' . date('Y-m-d H:i:s') . "】，拉取时间区间：【{$startDate}-{$endDate}】");

    // 公用获取最新的域名
    $domainUrl= getDomainUrl();
    $domainUrl = str_replace('as-lobby', 'as-service',$domainUrl); // 从 domain 接口获取到的地址，将 as-lobby 替换为 as-service
    $toURL = $domainUrl.'api/siteUsergamelog?';
    $aResult = siteUsergamelog ($toURL, 'siteUsergamelog', $startDate, $endDate);
    $aUsername = $aBetData = [];
    if ($aResult['ret']=='0000'){
        if ($aResult['total']>0){
            // 注单等交易数组
            foreach ($aResult['userGameLogs'] as $key => $value){
                $aBetData[$key] = $value;
                $aUsername[] = $value['uid'];
            }
        }
        else{
            writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间区间：【{$startDate}-{$endDate}】，注单数据为空");
            return false;
        }
    }else{
        writeLog("【" . date('Y-m-d H:i:s') . "】三方返回错误码".json_encode($aResult));
        return false;
    }
    $aUsername = array_unique($aUsername); // 去重

    // 2.查询MW会员
    writeLog("【" . date('Y-m-d H:i:s') . "】MW会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'mw_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】MW会员查询失败\n");
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

//    print_r($aBetData);
//    print_r($aUsername); die;

    $aBetData = array_sort($aBetData,'logDate',$type='asc');

    // 3.整理入库数据
    $aInsertData = $aTemp = [];
    foreach ($aBetData as $key => &$value){
        $aTemp = [
            'merchantId' => $value['merchantId'],
            'gameId' => $value['gameId'],
            'gameName' => $value['gameName'],
            'gameType' => $value['gameType'],
            'gameNum' => $value['gameNum'],
            'playMoney' => $value['playMoney'],
            'winMoney' => $value['winMoney'],
            'logDate' => $value['logDate'],
            'commission' => $value['commission'],
            'category' => $value['category'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if($value['uid'] == $aUser[$value['uid']]['username']){
            $aInsertData[] = array_merge($aUser[$value['uid']], $aTemp);
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

    // 4.入库MW注单表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "mw_projects` {$keys} VALUES {$values}";

//    echo $sql; die;

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