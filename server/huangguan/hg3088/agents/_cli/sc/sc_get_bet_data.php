<?php
/**
 * 定时抓取皇冠体育注单数据
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间1-5分钟，最大不能超过60分钟
 * 3.避免重复记录拉取（IGNORE）
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/sc/ApiSportCenter.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/sc_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/sc_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time();
    if(isset($argv[1]) && isset($argv[2])){
        $startTime = trim($argv[1]);
        $endTime = trim($argv[2]);
    }else{
        $startTime = $time - 780;
        $endTime = $time - 180;
    }
    getBetData($startTime, $endTime);
}

function getBetData($startTime, $endTime)
{
    global $dbMasterLink, $dbLink;

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s', $startTime) . '至' . date('Y-m-d H:i:s', $endTime) . "】，时间戳：【{$startTime}至{$endTime}】");

    $params = [
        'method' => 'getGameRecord',
        'startTime' => $startTime,
        'endTime' => $endTime
    ];
    $aResult = sportApi($params);
    if(isset($aResult) && $aResult['code'] != 1){ // 拉取失败
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime) . '-' . date('Y-m-d H:i:s', $endTime) . "】，三方返回错误码【{$aResult['code']}】", 1);
        return false;
    }

    if(empty($aResult['data'])){  // 没有拉取到数据
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime) . '-' . date('Y-m-d H:i:s', $endTime) . "】，暂无注单数据");
        return false;
    }

    $aUsername = [];
    foreach ($aResult['data'] as $key => &$value){
        $value['M_Name'] = substr($value['M_Name'], strpos($value['M_Name'],"_") + 1); // 去掉用户名前缀
        $aUsername[] = $value['M_Name'];
    }

    // 2.查询皇冠体育会员
    $aUsername = array_unique($aUsername); // 去重
    writeLog("【" . date('Y-m-d H:i:s') . "】皇冠体育会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");

    $sql = 'SELECT `id`, `userid`, `username`, `channel`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'sc_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】皇冠体育会员查询失败\n");
    $aUser = [];
    while ($row = mysqli_fetch_assoc($result)){
        $aUser[$row['username']] = [
            'Userid' => $row['userid'],
            'M_Name' => $row['username'],
            'Agents' => $row['agents'],
            'World' => $row['world'],
            'Corprator' => $row['corporator'],
            'Super' => $row['super'],
            'Admin' => $row['admin'],
            'testflag' => $row['is_test'],
        ];
    }

    // 3.整理入库数据
    $aInsertData = $aTemp = [];
    foreach ($aResult['data'] as $key => &$value){
        $aTemp = [
            'MID' => $value['MID'],
            'Active' => $value['Active'],
            'orderNo' => $value['orderNo'],
            'LineType' => $value['LineType'],
            'Mtype' => $value['Mtype'],
            'Pay_Type' => $value['Pay_Type'],
            'M_Date' => $value['M_Date'],
            'BetTime' => $value['BetTime'],
            'BetScore' => $value['BetScore'],
            'CurType' => $value['CurType'],
            'Middle' => $value['Middle'],
            'Middle_tw' => $value['Middle_tw'],
            'Middle_en' => $value['Middle_en'],
            'BetType' => $value['BetType'],
            'BetType_tw' => $value['BetType_tw'],
            'BetType_en' => $value['BetType_en'],
            'M_Place' => $value['M_Place'],
            'M_Rate' => $value['M_Rate'],
            'Gwin' => $value['Gwin'],
            'Glost' => $value['Glost'],
            'VGOLD' => $value['VGOLD'],
            'M_Result' => $value['M_Result'],
            'A_Result' => $value['A_Result'],
            'B_Result' => $value['B_Result'],
            'C_Result' => $value['C_Result'],
            'D_Result' => $value['D_Result'],
            'T_Result' => $value['T_Result'],
            'OpenType' => $value['OpenType'],
            'OddsType' => $value['OddsType'],
            'ShowType' => $value['ShowType'],
            'Cancel' => $value['Cancel'],
            'agent_url' => $value['agent_url'],
            'A_Point' => $value['A_Point'],
            'B_Point' => $value['B_Point'],
            'C_Point' => $value['C_Point'],
            'D_Point' => $value['D_Point'],
            'BetIP' => $value['BetIP'],
            'Type' => $value['Type'],
            'Ptype' => $value['Ptype'],
            'Gtype' => $value['Gtype'],
            'current' => $value['current'],
            'ratio' => $value['ratio'],
            'betid' => $value['betid'],
            'MB_MID' => $value['MB_MID'],
            'TG_MID' => $value['TG_MID'],
            'MB_ball' => $value['MB_ball'],
            'TG_ball' => $value['TG_ball'],
            'Edit' => $value['Edit'],
            'Orderby' => $value['Orderby'],
            'Checked' => $value['Checked'],
            'sendAwardTime' => $value['sendAwardTime'],
            'sendAwardIsAuto' => $value['sendAwardIsAuto'],
            'sendAwardName' => $value['sendAwardName'],
            'updateTime' => $value['updateTime'],
            'Soccer' => $value['Soccer'],
            'Confirmed' => $value['Confirmed'],
            'Danger' => $value['Danger'],
            'playSource' => $value['playSource'],
        ];
        if($value['M_Name'] == $aUser[$value['M_Name']]['M_Name']){
            $aInsertData[] = array_merge($aUser[$value['M_Name']], $aTemp);
        }
    }

    $count = count($aInsertData);
    $keys = $values = '';
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库皇冠体育注单表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "web_report_data` {$keys} VALUES {$values}";
    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime) . '-' . date('Y-m-d H:i:s', $endTime) . "】，错误【" . mysqli_error($dbMasterLink) . "】入库注单数据失败", 1);
        $dbMasterLink->rollback();
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    $dbMasterLink->commit();
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】");
    return true;
}

/**
 * 调用接口
 * @param $params
 * @return array|mixed
 */
function sportApi($params){
    $osc = new ApiSportCenter();
    try {
        ob_start();
        $res = $osc->main($params);
        return json_decode($res, true);
    } catch (Exception $e) {
        ob_end_clean();
        return array(
            'm' => 'scProxy',
            's' => $params['method'],
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