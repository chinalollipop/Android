<?php
/**
 * 代理日结统计（暂用于代理佣金查询报表）
 *
 * 统计项目包括
 * 入款总量
 * 出款总量
 * 优惠总量
 * 彩金总量
 * Date: 2019/12/19
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";

if (php_sapi_name() == "cli") {
    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $startTime = isset($argv[1]) && $argv[1] ? $argv[1] : $yesterday;
    $endTime = isset($argv[2]) && $argv[2] ? $argv[2] : $today;

    if($startTime > $yesterday)
        exit("【" . date('Y-m-d H:i:s') . "】开始时间>昨日【{$startTime} > {$yesterday}】\n");

    countAgentDaily();
}

function countAgentDaily() {
    global $dbMasterLink, $now, $startTime, $endTime;

    echo "【" . date('Y-m-d H:i:s') . "】代理日结统计日期美东时间：【{$startTime}~{$endTime}】\n";
    // 1.统计
    $dateStart = $startTime . ' 00:00:00';
    $dateEnd = $startTime . ' 23:59:59';
    $countData = getCount($dateStart, $dateEnd);
    if(empty($countData)){
        exit("【" . date('Y-m-d H:i:s') . "】代理日结统计暂无数据\n");
    }
    // 2.入库
    $dbMasterLink->autocommit(false);
    $insertData = [];
    foreach ($countData as $key => $value){
        $insertData[] = [
            'agents' => $key,
            'deposit' => $value['deposit'],
            'withdraw' => $value['withdraw'],
            'extra' => $value['extra'],
            'gift' => $value['gift'],
            'count_date' => $startTime,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
    $keys = $values = '';
    foreach ($insertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "'),";
    }
    $sql = "REPLACE INTO `" . DBPREFIX . "web_agents_daily_count` {$keys} VALUES " . substr($values ,0 ,-1);
    if(!$inserted = mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】代理日结统计报表生成失败，原因：入库报表失败【{$inserted}】\n");
    }
    $dbMasterLink->commit();
    exit("【" . date('Y-m-d H:i:s') . "】代理日结统计报表生成成功\n");
}

/**
 * 统计代理存取款数据
 * @param $dateStart
 * @param $dateEnd
 * @return array
 */
function getCount($dateStart, $dateEnd) {
    global $dbLink;

    $agentsD = $agentsW = $agentsG = $depositData = $extraData = $withdrawData = $giftData = $agentsData = [];
    // 存款总量（不包括优惠、返水、彩金）
    $sql = 'SELECT `Agents`, SUM(Gold) AS Gold ,SUM(moneyf) AS total_before, SUM(currency_after) AS total_after FROM ' . DBPREFIX . 'web_sys800_data WHERE addDate BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '" AND `Type` ="S" AND Checked = 1 AND `discounType` NOT IN (3, 4) AND `Payway` NOT IN ("O", "G") GROUP BY `Agents`';
    $result = mysqli_query($dbLink, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $depositData[$row['Agents']]['deposit'] = $row['total_after'] - $row['total_before'];
        $extraData[$row['Agents']]['extra'] = $row['Gold'] - $depositData[$row['Agents']]['deposit']; // 优惠总量
        $agentsD[] = $row['Agents'];
    }
    // 取款总量
    $sql = 'SELECT `Agents`, SUM(`Gold`) AS `withdraw` FROM ' . DBPREFIX . 'web_sys800_data WHERE addDate BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '" AND `Type` = "T" AND Checked = 1 GROUP BY `Agents`';
    $result = mysqli_query($dbLink, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $withdrawData[$row['Agents']]['withdraw'] = $row['withdraw'];
        $agentsW[] = $row['Agents'];
    }
    // 彩金总量
    $sql = 'SELECT `Agents`, SUM(`Gold`) AS `gift` FROM ' . DBPREFIX . 'web_sys800_data WHERE addDate BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '" AND `Payway` IN ("O", "G") AND `Type` = "S" AND Checked = 1 AND `discounType` = 0 GROUP BY `Agents`';
    $result = mysqli_query($dbLink, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $giftData[$row['Agents']]['gift'] = $row['gift'];
        $agentsG[] = $row['Agents'];
    }

    $agents = array_unique(array_merge($agentsD, $agentsW, $agentsG));
    foreach ($agents as $agent){
        $agentsData[$agent] = [
            'deposit' => isset($depositData[$agent]) ? $depositData[$agent]['deposit'] : 0,
            'extra' => isset($extraData[$agent]) ? $extraData[$agent]['extra'] : 0,
            'withdraw' => isset($withdrawData[$agent]) ? $withdrawData[$agent]['withdraw'] : 0,
            'gift' => isset($giftData[$agent]) ? $giftData[$agent]['gift'] : 0,
        ];
    }
    return $agentsData;
}