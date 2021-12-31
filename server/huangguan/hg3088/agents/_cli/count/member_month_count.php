<?php
/**
 * 会员月结统计
 *
 * 统计项目包括：
 *
 * 每月会员投注总人数
 *
 * Date: 2019/11/19
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR . "/app/agents/include/redis.php";

if (php_sapi_name() == "cli") {
    $month = date('Y-m');
    $now = date('Y-m-d H:i:s');
    $lastMonth = date('Y-m', strtotime('-1 month'));
    $startTime = isset($argv[1]) && $argv[1] ? $argv[1] : $lastMonth;
    $endTime = isset($argv[2]) && $argv[2] ? $argv[2] : $month;
    $isRepeat = isset($argv[3]) && $argv[3] ? $argv[3] : 0;

//    if($startTime > $lastMonth)
//        exit("【" . date('Y-m-d H:i:s') . "】开始时间>上月【{$startTime} > {$lastMonth}】\n");

    countMemberMonth();
}

function countMemberMonth(){
    global $dbMasterLink, $dbLink, $now, $startTime, $endTime, $isRepeat;
    // 1. 判断数据是否已统计
    if($isRepeat){
        echo "【" . date('Y-m-d H:i:s') . "】会员月结重新统计数据\n";
    }else{
        $sql = "SELECT `id`, `last_report_date`, `is_count` FROM " . DBPREFIX . "member_month_report_log 
            WHERE `is_count` = 1 
            ORDER BY `last_report_date` DESC LIMIT 1";
        $result  = mysqli_query($dbLink, $sql);
        $row = mysqli_fetch_assoc($result);
        $iCount = mysqli_num_rows($result);
        if($iCount){
            $lastCountDate = $row['last_report_date'];
            if(date('Y-m-d', strtotime("{$lastCountDate} + 1 month")) != $startTime){
                exit("【" . date('Y-m-d H:i:s') . "】会员月结统计日期错误【{$startTime}~{$endTime}】，最近最后一次统计日期【{$lastCountDate}】\n");
            }
        }
    }
    echo "【" . date('Y-m-d H:i:s') . "】会员月结统计日期美东时间：【{$startTime}~{$endTime}】\n";

    // 2.统计
    // 2.1.删除重复历史统计数据&日志报表数据
    mysqli_query($dbMasterLink, "DELETE FROM " . DBPREFIX . "web_member_month_count WHERE `count_date` >= '{$startTime}' AND `count_date` < '{$endTime}'");
    mysqli_query($dbMasterLink, "DELETE FROM " . DBPREFIX . "member_month_report_log WHERE `last_report_date` >= '{$startTime}' AND `last_report_date` < '{$endTime}'");

    $dateStart = $startTime . '-01 00:00:00';
    $dateEnd = $startTime . '-31 23:59:59';
    $countData = getCount($dateStart, $dateEnd);

    // 3.入库
    $dbMasterLink->autocommit(false);

    // 入库日志表（先记录日志，确保无数据时，也能正常记录）
    $logData = [
        'last_report_date' => date('Y-m', strtotime($startTime)),
        'is_count' => 1,
        'created_at' => $now,
        'updated_at' => $now
    ];
    foreach($logData as $key => $val){
        $logTmp[] = $key . '=\'' . $val . '\'';
    }
    $sql = "INSERT INTO " . DBPREFIX . "member_month_report_log SET " . implode(',', $logTmp);
    if(!$inserted = mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】会员月结统计报表生成失败，原因：入库日志表失败【{$inserted}】\n");
    }

    if(empty($countData)){
        exit("【" . date('Y-m-d H:i:s') . "】会员月结统计暂无数据\n");
    }

    // 入库会员月统计表
    $insertData = [
        'total_bet_member' => $countData['memberBet']['mem_count'],
        'count_date' => date('Y-m', strtotime($startTime)),
        'created_at' => $now,
        'updated_at' => $now,
    ];

    $tmp = $logTmp = [];
    foreach($insertData as $key => $val){
        $tmp[] = $key . '=\'' . $val . '\'';
    }
    $sql = "INSERT INTO " . DBPREFIX . "web_member_month_count SET " . implode(',', $tmp);
    if(!$inserted = mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】会员月结统计报表生成失败，原因：入库报表失败【{$inserted}】\n");
    }
    $dbMasterLink->commit();
    exit("【" . date('Y-m-d H:i:s') . "】会员月结统计报表生成成功\n");
}

function getCount($dateStart, $dateEnd){
    // 4.投注数据（统计投注日结报表）
    $memberBet = countHistoryBetMember($dateStart, $dateEnd);
    return [
        'memberBet' => $memberBet,
    ];
}