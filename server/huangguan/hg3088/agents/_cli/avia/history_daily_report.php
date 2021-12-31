<?php
/**
 * 泛亚电竞日结报表
 * Date: 2018/8/29
 */
error_reporting(E_ALL);
ini_set('display_errors','Off');
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/avia/api.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/avia_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/avia_get_user_report_data_' . date('Ymd', strtotime('+ 12 hour'));

    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $startTime = isset($argv[1]) && $argv[1] ? $argv[1] : $yesterday;
    $endTime = isset($argv[2]) && $argv[2] ? $argv[2] : $today;

    if($startTime > $yesterday)
        exit("【" . date('Y-m-d H:i:s') . "】开始时间>昨日【{$startTime} > {$yesterday}】\n");

    countDailyWinLoss($startTime, $endTime);
}

function countDailyWinLoss($startTime, $endTime)
{
    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取历史注单报表数据日期区间【" . $startTime . '-' . $endTime . "】");

    $page = 1; // 第几页
    $page_size = 1000; // 每页条数
    $res = getUserReport0 ( $startTime, $endTime, $page, $page_size);
    $aResult = json_decode($res, true);

    if ($aResult['success']){
        // 判断分页，两种情况
        //  1. 只有1页直接插入
        //  2. 多页，循环插入
        if ($aResult['info']['RecordCount']>0 and $aResult['info']['RecordCount']<=$page_size and $aResult['info']['PageIndex']==1){
            // 1. 只有1页直接插入
            $res = addUserReport($aResult, $startTime, $endTime);
            if (!$res){
                return false;
            }
        }
        else{

            //  2. 多页，先执行第一页的添加，循环从第2页开始循环添加
            $res = addUserReport($aResult, $startTime, $endTime);
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
                $res = getTransaction ($startTime, $endTime, $page, $page_size);
                $aResult = json_decode($res, true);
                $res = addUserReport($aResult, $startTime, $endTime);
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

function addUserReport($aResult, $startTime, $endTime){
    global $dbMasterLink, $dbLink;

    // 2.统计
    // 2.1.删除重复历史统计数据&日志报表数据
    mysqli_query($dbMasterLink, "DELETE FROM " . DBPREFIX . "avia_history_report WHERE `count_date` >= '{$startTime}' AND `count_date` <= '{$endTime}'");

    $aUsername = $aReportData = [];
    if ($aResult['success']){
        foreach ($aResult['info']['list'] as $key => $value){
            $aReportData[] = $value;
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
            'is_test' => $row['is_test'],
        ];
    }

    // 3.整理入库数据
    $aInsertData = $aTemp = [];
    foreach ($aReportData as $key => &$value){
        $aTemp = [
            'total_times' => $value['OrderCount'], // 当日结算的订单数量
            'total_cellscore' => $value['BetAmount'],
            'total_bet' => $value['BetMoney'], // 投注金额
            'total_profit' => $value['Money'], // 盈亏
            'count_date' => $value['Date'], // 报表日期
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if($value['UserName'] == $aUser[$value['UserName']]['username']){
            $aInsertData[] = array_merge($aUser[$value['UserName']], $aTemp);
        }
    }

    $count = count($aReportData);
    $keys = $values = '';
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库AVIA历史注单报表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "avia_history_report` {$keys} VALUES {$values}";

    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $dbMasterLink->rollback();
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . $startTime . '至' . $endTime . "】，拉取历史注单报表数据失败，原因：入库失败【{$inserted}】", 1);
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取历史注单报表数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】");

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

