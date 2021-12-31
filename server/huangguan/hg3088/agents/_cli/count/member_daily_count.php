<?php
/**
 * 会员日结统计
 *
 * 统计项目包括：
 * 每日新增总代理人数、代理人数
 * 每日新进会员（注册人数、注册且充值人数）
 * 每日会员返水总额、返水总人数
 * 每日会员存取款总额、存取款总人数
 * 每日会员有效投注、总损益、投注总人数（注明：体育、彩票等三方投注）
 * Date: 2019/11/19
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR . "/app/agents/include/redis.php";

if (php_sapi_name() == "cli") {
    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $startTime = isset($argv[1]) && $argv[1] ? $argv[1] : $yesterday;
    $endTime = isset($argv[2]) && $argv[2] ? $argv[2] : $today;
    $isRepeat = isset($argv[3]) && $argv[3] ? $argv[3] : 0;

    if($startTime > $yesterday)
        exit("【" . date('Y-m-d H:i:s') . "】开始时间>昨日【{$startTime} > {$yesterday}】\n");

    countMemberDaily();
}

function countMemberDaily(){
    global $dbMasterLink, $dbLink, $now, $startTime, $endTime, $isRepeat;
    // 1. 判断数据是否已统计
    if($isRepeat){
        echo "【" . date('Y-m-d H:i:s') . "】会员日结重新统计数据\n";
    }else{
        $sql = "SELECT `id`, `last_report_date`, `is_count` FROM " . DBPREFIX . "member_daily_report_log 
            WHERE `is_count` = 1 
            ORDER BY `last_report_date` DESC LIMIT 1";
        $result  = mysqli_query($dbLink, $sql);
        $row = mysqli_fetch_assoc($result);
        $iCount = mysqli_num_rows($result);
        if($iCount){
            $lastCountDate = $row['last_report_date'];
            if(date('Y-m-d', strtotime("{$lastCountDate} + 1 day")) != $startTime){
                exit("【" . date('Y-m-d H:i:s') . "】会员日结统计日期错误【{$startTime}~{$endTime}】，最近最后一次统计日期【{$lastCountDate}】\n");
            }
        }
    }
    echo "【" . date('Y-m-d H:i:s') . "】会员日结统计日期美东时间：【{$startTime}~{$endTime}】\n";

    // 2.统计
    // 2.1.删除重复历史统计数据&日志报表数据
    mysqli_query($dbMasterLink, "DELETE FROM " . DBPREFIX . "web_member_daily_count WHERE `count_date` >= '{$startTime}' AND `count_date` < '{$endTime}'");
    mysqli_query($dbMasterLink, "DELETE FROM " . DBPREFIX . "member_daily_report_log WHERE `last_report_date` >= '{$startTime}' AND `last_report_date` < '{$endTime}'");

    $dateStart = $startTime . ' 00:00:00';
    $dateEnd = $startTime . ' 23:59:59';
    $countData = getCount($dateStart, $dateEnd);

    // 3.入库
    $dbMasterLink->autocommit(false);

    // 入库日志表（先记录日志，确保无数据时，也能正常记录）
    $logData = [
        'last_report_date' => $startTime,
        'is_count' => 1,
        'created_at' => $now,
        'updated_at' => $now
    ];
    foreach($logData as $key => $val){
        $logTmp[] = $key . '=\'' . $val . '\'';
    }
    $sql = "INSERT INTO " . DBPREFIX . "member_daily_report_log SET " . implode(',', $logTmp);
    if(!$inserted = mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】会员日结统计报表生成失败，原因：入库日志表失败【{$inserted}】\n");
    }

    if(empty($countData)){
        exit("【" . date('Y-m-d H:i:s') . "】会员日结统计暂无数据\n");
    }

    // 入库会员日统计表
    $insertData = [
        'total_agent_c' => $countData['agentC'],
        'total_agent_d' => $countData['agentD'],
        'total_reg' => $countData['memberReg'],
        'total_reg_deposit' => $countData['memberRegDeposit'],
        'total_rebate' => $countData['R']['total_num'],
        'total_rebate_member' => $countData['R']['total_money'],
        'total_deposit' => $countData['S']['total_num'],
        'total_deposit_member' => $countData['S']['total_money'],
        'total_withdraw' => $countData['T']['total_num'],
        'total_withdraw_member' => $countData['T']['total_money'],
        'total_valid_bet' => $countData['memberBet']['valid_money'],
        'total_win_loss' => $countData['memberBet']['user_win'],
        'total_bet_member' => $countData['memberBet']['mem_count'],
        'count_date' => $startTime,
        'created_at' => $now,
        'updated_at' => $now,
    ];

    $tmp = $monthTmp = $logTmp = [];
    foreach($insertData as $key => $val){
        $tmp[] = $key . '=\'' . $val . '\'';
    }
    $sql = "INSERT INTO " . DBPREFIX . "web_member_daily_count SET ".implode(',', $tmp);
    if(!$inserted = mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】会员日结统计报表生成失败，原因：入库报表失败【{$inserted}】\n");
    }

    $dbMasterLink->commit();
    exit("【" . date('Y-m-d H:i:s') . "】会员日结统计报表生成成功\n");
}

/**
 * 统计
 * @param $dateStart
 * @param $dateEnd
 * @return array
 */
function getCount($dateStart, $dateEnd){
    // 1.代理资讯
    $agent = countAgent($dateStart, $dateEnd);
    // 2.新进会员
    $memberNew = countMember($dateStart, $dateEnd);
    // 4.投注数据（统计投注日结报表）
    $memberBet = countHistoryBetMember($dateStart, $dateEnd);
    // 3.返点资讯
    // 5.存款资讯
    // 6.取款资讯
    $countRST = countRST($dateStart, $dateEnd);
    return [
        'agentC' => $agent['C'],
        'agentD' => $agent['D'],
        'memberReg' => $memberNew['reg'],
        'memberRegDeposit' => $memberNew['deposit'],
        'R' => $countRST['R'],
        'S' => $countRST['S'],
        'T' => $countRST['T'],
        'memberBet' => $memberBet,
    ];
}
