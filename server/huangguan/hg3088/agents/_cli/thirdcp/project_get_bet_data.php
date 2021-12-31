<?php
/**
 * 定时抓取第三方彩票官方注单数据
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间1-5分钟，最大不能超过60分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2018/8/27
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/thirdcp/ApiProxy.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/thirdcp_log/project_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/third_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time();
    if(isset($argv[1]) && isset($argv[2])){
        //$startTime = trim($argv[1]) * 1000;
        //$endTime = trim($argv[2]) * 1000;
        $startTime = strtotime($argv[1] . ' 00:00:00') * 1000;
        $endTime = strtotime($argv[2] . ' 23:59:59') * 1000;
    }else{
        $startTime = ($time - 1380) * 1000;  //13分钟前   780    23分钟前 1380
        $endTime = ($time - 180) * 1000;    //3分钟前
        //$startTime = 1566144000 * 1000;  //2019-08-10 00:00:00
        //$endTime = 1566316800 * 1000;    //2019-08-16 00:00:00
    }
    getProjectBetData($startTime, $endTime);
}

function getProjectBetData($startTime, $endTime)
{
    global $dbMasterLink, $dbLink;

    $now = strtotime('+12 hour');

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s', $startTime / 1000) . '至' . date('Y-m-d H:i:s', $endTime / 1000) . "】，时间戳：【{$startTime}至{$endTime}】", 1);

    $params = [
        's' => 2,
        'startTime' => $startTime,
        'endTime' => $endTime
    ];

    $aResult = getThirdCpApi($params);

    if(isset($aResult) && $aResult['error'] != ''){ // 拉取失败
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $now) . "】，三方返回错误码【{$aResult['error']}】",1);
        return false;
    }

    if(empty($aResult['data']['totalCount'])  || empty($aResult['data']['data'])){  // 没有拉取到数据
        writeLog("【" . date('Y-m-d H:i:s') . "】同步时间【" . date('Y-m-d H:i:s', $now) . "】，暂无注单数据");
        return false;
    }

    $aUsername = [];
    foreach ($aResult['data']['data'] as $key => $value){
        //$aSerial[] = $value['id'];  // 注单号
        $aUsername[] = $value['username'];
    }

    // 3.查询第三方彩票会员
    $aUsername = array_unique($aUsername); // 去重
    writeLog("【" . date('Y-m-d H:i:s') . "】第三方彩票会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】", 1);

    $sql = 'SELECT `id`, `userid`, `username`, `line_code`,`agents`,`world`,`corporator`,`admin`,`is_lock`,`is_test` FROM ' . DBPREFIX  .'cp_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】第三方彩票会员查询失败\n");

    $aUser = [];
    while ($row = mysqli_fetch_assoc($result)){
        $aUser[$row['username']] = [
            'hg_uid' => $row['userid'],
            'username' => $row['username'],
            'agents' => $row['agents'], //会员代理
        ];
    }

    // 3.整理入库数据
    $aInsertData = $aTemp = $aNotUserName = [];
    foreach ($aResult['data']['data'] as $key => &$value){
        $aTemp = [
            'id' => $value['project_id'],
            'hg_uid'  => $aUser[$value['username']]['hg_uid'],
            'terminal_id' => $value['terminal_id'],
            'serial_number' => $value['serial_number'],
            'trace_id' => $value['trace_id'] ? $value['trace_id'] : 0,   //追号任务I
            'user_id' => $value['user_id'],  //投注用户ID
            'username' => $value['username'],
            'is_tester' => $value['is_tester'],
            //'thirdLotteryId' => $value['agents'],   // 注单
            'account_id' => $value['account_id'],
            'prize_group' => $value['prize_group'], //奖金组
            'lottery_id' => $value['lottery_id'],
            'issue' => $value['issue'],     //奖期
            'end_time' => $value['end_time'], // 入库美东时间
            'way_id' => $value['way_id'],
            'title' => $value['title'],
            'bet_number' => $value['bet_number'],   // 投注号码
            'way_total_count' => $value['way_total_count'],
            'single_count' => $value['single_count'],
            'bet_rate' => $value['bet_rate'],   //赔率
            'display_bet_number' => $value['display_bet_number'],   // 投注的号码信息
            'multiple' => $value['multiple'],   // 模式
            'coefficient' => $value['coefficient'],
            'amount' => $value['amount'],   //投注金额
            'winning_number' => $value['winning_number'],   //中奖号码
            'prize' => $value['prize'] ? $value['prize'] : 0,
            'status' => $value['status'],   // 中奖状态
            'status_prize' => $value['status_prize'] ? $value['status_prize'] : 0,
            'status_commission' => $value['status_commission'],
            'prize_set' => $value['prize_set'],
            'single_won_count' => $value['single_won_count'] ? $value['single_won_count'] : 0,
            'won_count' => $value['won_count'] ? $value['won_count'] : 0,
            'won_data' => $value['won_data'],
            'ip' => $value['ip'],
            'bought_at' => date('Y-m-d H:i:s', strtotime($value['bought_at']) - 12 * 3600), // 入库美东时间
            'counted_at' => date('Y-m-d H:i:s', strtotime($value['counted_at']) - 12 * 3600), // 入库美东时间
            'prize_sent_at' => $value['prize_sent_at'] ? date('Y-m-d H:i:s', strtotime($value['prize_sent_at']) - 12 * 3600) : '', // 入库美东时间
            'commission_sent_at' => $value['commission_sent_at'] ? date('Y-m-d H:i:s', strtotime($value['commission_sent_at']) - 12 * 3600) : '', // 入库美东时间
            'prize_added' => $value['prize_added'],
            'bought_time' => $value['bought_time'],
            'bet_commit_time' => $value['bet_commit_time'],
            'counted_time' => $value['counted_time'],
            'prize_sent_time' => $value['prize_sent_time'] ? $value['prize_sent_time'] : 0,
            'commission_sent_time' => $value['commission_sent_time'],
        ];

        if($value['username'] == $aUser[$value['username']]['username']){
        //if(strcasecmp($value['username'] , $aUser[$value['username']]['username'])==0){ // 不区分大小写
            $aInsertData[] = array_merge($aUser[$value['username']], $aTemp);
        }else{
            array_push($aNotUserName, $value['username']);
        }
    }

    if(!empty($aNotUserName)) { //cp_member_data表未匹配到会员名称
        $aNotUserName = array_unique($aNotUserName);
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取官方注单cp_member_data表未匹配到会员名称【" .implode(',', $aNotUserName) . "】", 1);
    }

    $keys = $values = '';
    $count = count($aInsertData);
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库第三方彩票官方盘注单表
    $dbMasterLink->autocommit(false);
    //$sql = "INSERT IGNORE INTO `" . DBPREFIX . "web_third_projects_data` {$keys} VALUES {$values}";
    $sql = "REPLACE  INTO `" . DBPREFIX . "web_third_projects_data` {$keys} VALUES {$values}";
    //writeLog("【" . date('Y-m-d H:i:s') . "】sql：【{$sql}】", 1);
    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $dbMasterLink->rollback();
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime / 1000) . '至' . date('Y-m-d H:i:s', $endTime / 1000) . "】，时间戳：【{$startTime}至{$endTime}】，拉取注单数据失败，原因：入库失败【{$inserted}】", 1);
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    $dbMasterLink->commit();
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】", 1);
    return true;
}

/**
 * 调用三方接口
 * @param $params
 * @return array|mixed
 */
function getThirdCpApi($params){
    $oThirdCp = new ThirdApiProxy();
    try {
        ob_start();
        $res = $oThirdCp->thirdMain($params);
        return json_decode($res, true);
    } catch (Exception $e) {
        ob_end_clean();
        return array(
            'm' => 'thirdProxy',
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