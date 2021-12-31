<?php
/**
 * 定时抓取快乐棋牌注单数据  (北京时间)
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间10分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2019/9/7
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/klqp/api.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/kl_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/kl_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time()+12*60*60; // 快乐棋牌注单北京时间 +12*60*60
    if(isset($argv[1]) && isset($argv[2])) {
        $startTime = strtotime($argv[1] . ' 00:00:00');
        $endTime = strtotime($argv[2] . ' 23:59:59');
    } else {
        $startTime = ($time - 780); //前13分钟
        $endTime = ($time - 180);  // 前3分
    }
    getBetData($startTime, $endTime);
}

function getBetData($startTime, $endTime)
{
    global $dbMasterLink, $dbLink,  $klqp_merchant,$klqp_prefix;

    $startDate = date('Y-m-d H:i:s',$startTime);
    $endDate = date('Y-m-d H:i:s',$endTime);

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s') . '-' . date('Y-m-d H:i:s') . "】，拉取时间区间：【{$startDate}-{$endDate}】");

    $aResult = getProjectlist($klqp_merchant['merchantId'], $startDate, $endDate); //注单

    $aUsername = $aBetData = [];
    if($aResult['success'] != 1) { // 获取快乐棋牌注单失败
        writeLog("【" . date('Y-m-d H:i:s') . "】三方快乐棋牌注单拉取失败!");
        return false;
    }

    if(count($aResult['body']['data']) <= 0) { // 获取快乐棋牌注单为空
        writeLog("【" . date('Y-m-d H:i:s') . "】三方快乐棋牌注单为空!");
        return false;
    }

    $data = json_decode($aResult['body']['data'] , true);
    // 注单等交易数组
    foreach ($data as $key => $value){
        $value['username'] = explode($klqp_prefix, $value['username'])[1];
        $aBetData[$key] = $value;
        $aUsername[] = $value['username'];
    }

    $aUsername = array_unique($aUsername); // 去重

    // 2.查询快乐棋牌会员
    writeLog("【" . date('Y-m-d H:i:s') . "】快乐棋牌会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'kl_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】快乐棋牌会员查询失败\n");
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
    foreach ($aBetData as $key => &$value) {
        $aTemp = [
            'project_id' => $value['project_id'],   //注单ID
            'merchant_id' => $value['merchant_id'], //商户ID
            'channel' => $klqp_merchant['merchantname'], //渠道号
            'game_id' => $value['game_id'],         //游戏ID
            'game_name' => $value['game_name'],     //游戏名称
            'room_id' => $value['room_id'],         //房间ID
            'table_id' => $value['table_id'],       //桌子号ID
            'round_id' => $value['round_id'],       //第几张牌
            'issue_id' => $value['issue_id'],       //彩池奖金
            '_method_id' => $value['_method_id'],   //玩法id
            'code' => $value['code'],               //投注号码
            'open_code' => $value['open_code'],     //开奖号码
            'amount' => $value['amount'],           //投注金额
            'prize' => $value['prize'],             //派奖
            'gametime' => date('Y-m-d H:i:s',strtotime($value['create_time'])-12*60*60),   //注单时间转为美东时间
            '_cancel_status' => $value['_cancel_status'],   //是否撤单(0未撤单 1用户撤单 4未开撤单 9管理员撤单
            'frm' => $value['frm'],                 // 1网页 2客户端 3WAP 4安卓APP 5苹果APP
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if($value['username'] == $aUser[$value['username']]['username']){
            $aInsertData[] = array_merge($aUser[$value['username']], $aTemp);
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

    // 4.入库快乐棋牌注单表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "kl_projects` {$keys} VALUES {$values}";

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
