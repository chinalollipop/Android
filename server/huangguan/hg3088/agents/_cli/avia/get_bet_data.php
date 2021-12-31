<?php
/**
 * 定时抓取avia注单数据
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间10分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2019/7/20
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/avia/api.php';

date_default_timezone_set ("PRC"); // 转为北京时区，然后去拉取avia注单

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/avia_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/avia_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time();
    if(isset($argv[1]) && isset($argv[2])){
        $startTime = trim($argv[1]);
        $endTime = trim($argv[2]);
    }else{
        $startTime = ($time - 780);
        $endTime = ($time - 180);
    }
    getBetData($startTime, $endTime);
}

function getBetData($startTime, $endTime)
{
    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s',$startTime) . '至' . date('Y-m-d H:i:s',$endTime) . "】，时间戳：【{$startTime}至{$endTime}】");

    $start_time = date('Y-m-d H:i:s',$startTime);
    $end_time = date('Y-m-d H:i:s',$endTime);

    $page = 1; // 第几页
    $page_size = 1000; // 每页条数
    $res = getTransaction ('UpdateAt' , $start_time, $end_time, $page, $page_size);
    $aResult = json_decode($res, true);

    if ($aResult['success']){
        // 判断分页，两种情况
        //  1. 只有1页直接插入
        //  2. 多页，循环插入
        if ($aResult['info']['RecordCount']>0 and $aResult['info']['RecordCount']<=$page_size and $aResult['info']['PageIndex']==1){
            // 1. 只有1页直接插入
            $res = addBetData($aResult, $startTime, $endTime);
            if (!$res){
                return false;
            }
        }
        else{

            //  2. 多页，先执行第一页的添加，循环从第2页开始循环添加
            $res = addBetData($aResult, $startTime, $endTime);
            if (!$res){
                return false;
            }
            // 间隔15秒后继续抓取
            sleep(10);
            // 总页数 = 向上取整（总条数/每页条数）
            $totalpages = ceil( $aResult['info']['RecordCount'] / $aResult['info']['PageSize'] );
            for ($i = 2; $i <= $totalpages; $i++){

                writeLog("【" . date('Y-m-d H:i:s') . "】 {$i}/{$totalpages} 页数据开始");
                $page = $i;
                $res = getTransaction ('UpdateAt' , $start_time, $end_time, $page, $page_size);
                $aResult = json_decode($res, true);
                $res = addBetData($aResult, $startTime, $endTime);
                if (!$res){
                    return false;
                }

                // 处理完最后一页正常结束，无需sleep
                if ($i < $totalpages) sleep(10);
            }
        }

    }else{
        writeLog("【" . date('Y-m-d H:i:s') . "】三方返回错误码".json_encode($aResult));
        return false;
    }

}

function addBetData($aResult, $startTime, $endTime){
    global $dbMasterLink, $dbLink;
    $aUsername = $aBetData = [];
    if ($aResult['success']){

        // 注单等交易数组
        foreach ($aResult['info']['list'] as $key => $value){
            $aBetData[] = $value;
            $aUsername[] = $value['UserName'];
        }
    }else{
        writeLog("【" . date('Y-m-d H:i:s') . "】三方返回错误码".json_encode($aResult));
        return false;
    }
    $aUsername = array_unique($aUsername); // 去重

    // 2.查询AVIA会员
    writeLog("【" . date('Y-m-d H:i:s') . "】AVIA会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'avia_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】AVIA会员查询失败\n");
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
            'orderID' => $value['OrderID'],
            'cateID' => $value['CateID'],
            'code' => $value['Code'],
            'xxxindex' => $value['Index'],
            'player' => $value['Player'],
            'category' => $value['Category'],
            'leagueID' => $value['LeagueID'],
            'league' => $value['League'],
            'matchID' => $value['MatchID'],
            'match_avia' => $value['Match'],
            'betID' => $value['BetID'],
            'bet' => $value['Bet'],
            'content' => $value['Content'],
            'result' => $value['Result'],
            'betAmount' => $value['BetAmount'],
            'betMoney' => $value['BetMoney'],
            'money' => $value['Money'],
            'status' => $value['Status'],
            'createAt' => $value['CreateAt'],
            'updateAt' => $value['UpdateAt'],
            'startAt' => $value['StartAt'],
            'endAt' => $value['EndAt'],
            'resultAt' => $value['ResultAt'],
            'rewardAt' => $value['RewardAt'],
            'odds' => $value['Odds'],
            'ip' => $value['IP'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if($value['UserName'] == $aUser[$value['UserName']]['username']){
            $aInsertData[] = array_merge($aUser[$value['UserName']], $aTemp);
        }
    }

    $count = count($aBetData);
    $keys = $values = '';
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库AVIA注单表
    $dbMasterLink->autocommit(false);
    $sql = "REPLACE INTO `" . DBPREFIX . "avia_projects` {$keys} VALUES {$values}";
    $sql = rtrim($sql,',');

    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $dbMasterLink->rollback();
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime) . '至' . date('Y-m-d H:i:s', $endTime) . "】，时间戳：【{$startTime}至{$endTime}】，拉取注单数据失败，原因：入库失败【{$inserted}】", 1);
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】");
    $dbMasterLink->commit();
    return true;
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