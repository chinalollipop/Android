<?php
/**
 * MW电子日结报表（每次执行的时间区间必须为1个天）
 * 体育后台只能查看MW历史报表，查看MW当天报表只能登录MW查看
 * Date: 2019/10/19
 */

define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/mw/api.php';

if (php_sapi_name() == "cli") {

    if(isset($argv[1]) && isset($argv[2])){
        $startTime = trim($argv[1]).' 12:00:00';
        $endTime = trim($argv[2]).' 12:00:00';
        $isRepeat = isset($argv[3]) && $argv[3] ? $argv[3] : 0; // 是否重新拉取之前某一天的报表

        $startTime2 = date('Y-m-d 12:00:00', strtotime('-1 day')); // 判断用
        if($startTime > $startTime2)
            exit("【" . date('Y-m-d H:i:s') . "】开始时间>昨日【{$startTime} > {$startTime2}】\n");
    }
    else{ // 默认拉取前一天的报表
        $startTime = date('Y-m-d 12:00:00', strtotime('-1 day'));
        $endTime = date('Y-m-d 12:00:00');
    }

    if(strtotime($endTime)-strtotime($startTime)>24*60*60){
        exit("【" . date('Y-m-d H:i:s') . "】日期错误【{$startTime} > {$startTime2}】，每次只能拉取1天\n");
    }

    countDaiMWWinLoss();
}

function countDaiMWWinLoss()
{
    global $dbMasterLink, $dbLink, $startTime, $endTime, $isRepeat;

//    $startTime = date('2019-10-18 12:00:00');
//    $endTime = date('2019-10-20 12:00:00');

    $sDate = date('Y-m-d', strtotime($startTime));
    // 1. 判断数据是否已统计
    if($isRepeat){
        echo "【" . date('Y-m-d H:i:s') . "】MW电子重新统计数据\n";
    }else{
//        $sql = "SELECT `id`, `last_report_date`, `is_count` FROM " . DBPREFIX . "mw_report_log
//            WHERE `is_count` = 1
//            ORDER BY `last_report_date` DESC LIMIT 1";
//        $result  = mysqli_query($dbLink, $sql);
//        $row = mysqli_fetch_assoc($result);
//        $iCount = mysqli_num_rows($result);
//        if($iCount){
//            $lastCountDate = $row['last_report_date'];
//            if(date('Y-m-d', strtotime("{$lastCountDate} + 1 day")) != $sDate){
//                exit("【" . date('Y-m-d H:i:s') . "】MW电子统计日期错误【{$sDate}】，最近最后一次统计日期【{$lastCountDate}】\n");
//            }
//        }
    }
    echo "【" . date('Y-m-d H:i:s') . "】MW电子统计日期北京时间：【{$startTime}~{$endTime}】，美东天的日期{$sDate}\n";

    // 2.统计
    // 2.1.删除重复历史统计数据&日志报表数据
    mysqli_query($dbMasterLink, "DELETE FROM " . DBPREFIX . "mw_history_report WHERE `count_date` = '{$sDate}'");
    mysqli_query($dbMasterLink, "DELETE FROM " . DBPREFIX . "mw_report_log WHERE `last_report_date` = '{$sDate}'");

    // 2.2.事务处理
    $dbMasterLink->autocommit(false);

    // 公用获取最新的域名
    $domainUrl= getDomainUrl();
    $toURL = $domainUrl.'api/usersgm?';
    $aResult = usersgm ($toURL, 'usersgm', $startTime, $endTime);
    if ($aResult['ret']=='0000'){
        if ($aResult['total']>0){
            $aReportData = $aUsername = array();
            foreach ($aResult['userSgms'] as $key => $value){

                $aUsername[] = $value['uid'];
                $aReportData[] = [
                    'username' => $value['uid'],
                    'total_times' => $value['playLoop'],
                    'total_cellscore' => $value['playAmount'],
                    'total_bet' => $value['playAmount'],
                    'total_profit' => $value['playJifenAmount'],
                    'count_date' => $sDate,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        else{
            exit("【" . date('Y-m-d H:i:s') . "】MW电子报表获取失败，原因：记录数【{$aResult['total']}】\n");
        }
    }
    else{
        exit("【" . date('Y-m-d H:i:s') . "】三方返回错误码".json_encode($aResult['msg']));
    }
    $aUsername = array_unique($aUsername); // 去重

    // 查询MW会员
    echo("【" . date('Y-m-d H:i:s') . "】MW会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】\n");
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'mw_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】MW会员查询失败\n");
    $aUser = [];
    while ($row = mysqli_fetch_assoc($result)){
        $aUser[$row['username']] = [
            'userid' => $row['userid'],
            'username' => $row['username'],
            'agents' => $row['agents'],
            'is_test' => $row['is_test'],
        ];
    }

    $aInsertData = $aTemp = [];
    foreach ($aReportData as $key => &$value){
        $aTemp = $value;
        if($value['username'] == $aUser[$value['username']]['username']){
            $aInsertData[] = array_merge($aUser[$value['username']], $aTemp);
        }
    }

    $count = count($aInsertData);
    $keys = $values = '';
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }
    $sql = "INSERT INTO " . DBPREFIX . "mw_history_report {$keys} VALUES {$values}";
    if(!$inserted = mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】MW电子统计报表生成失败，原因：入库报表失败【{$inserted}】\n");
    }

    $logData[] = [
        'last_report_date' => $sDate,
        'is_count' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    $count = count($logData);
    // 批量入库日志表
    $keys = $values = '';
    foreach ($logData as $key => $value){
        $keys = '(' . implode(',', array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    $sql = "INSERT INTO " . DBPREFIX . "mw_report_log {$keys} VALUES {$values} ON DUPLICATE KEY UPDATE `is_count` = 1";
    if(!$inserted = mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】MW电子统计报表生成失败，原因：入库日志表失败【{$inserted}】\n");
    }
    $dbMasterLink->commit();
    exit("【" . date('Y-m-d H:i:s') . "】MW电子统计报表成功\n");
}
