<?php
/**
 * 定时抓取CQ9电子注单数据  美东时间
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间10分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2019/9/7
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/cq9/api.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/cq_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/cq_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time(); // cq注单 +12*60*60
    if(isset($argv[1]) && isset($argv[2])){
        $startTime = strtotime($argv[1] . ' 00:00:00');
        $endTime = strtotime($argv[2] . ' 23:59:59');
    }else{
        $startTime = ($time - 780);
        $endTime = ($time - 180);
    }
    getBetData($startTime, $endTime);
}

function getBetData($startTime, $endTime)
{
    global $dbMasterLink, $dbLink, $root_url, $gamehall, $page, $pagesize,$api_token;

    /*$startDate = date('Y-m-d H:i:s',$startTime);
    $endDate = date('Y-m-d H:i:s',$endTime);*/
    $startDate = date("c", $startTime); // 取得时戳 格式為RFC3339
    $endDate = date("c", $endTime); // 取得时戳 格式為RFC3339

        // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s') . '-' . date('Y-m-d H:i:s') . "】，拉取时间区间：【{$startDate}-{$endDate}】");

    $aResult = orderView($api_token, $startDate, $endDate, '', $page, $pagesize); //注单查询

    $aUsername = $aBetData = [];
    if ($aResult['success']){
        // 注单等交易数组
        foreach ($aResult['body']['Data'] as $key => $value){
            //$value['membername'] = explode($og_auto_preifx, $value['membername'])[1];
            $aBetData[$key] = $value;
            $aUsername[] = $value['account'];
        }
    }else{  // 获取开奖注单失败
        writeLog("【" . date('Y-m-d H:i:s') . "】三方CQ9注单返回错误码".json_encode($aResult));
        return false;
    }
    $aUsername = array_unique($aUsername); // 去重

    // 2.查询CQ9会员
    writeLog("【" . date('Y-m-d H:i:s') . "】CQ9会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'cq9_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】CQ9会员查询失败\n");
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

    // 3.整理入库数据
    $aInsertData = $aTemp = [];
    foreach ($aBetData as $key => &$value){
        $aTemp = [
            'gamehall' => $value['gamehall'],   //游戏商名称
            'gametype' => $value['gametype'],   //游戏种类
            'gameplat' => $value['gameplat'],   //游戏平台
            'gamecode' => $value['gamecode'],   //游戏代码
            'round' => $value['round'],     //局号
            'balance' => $value['balance'],     //游戏后余额
            'win' => $value['win'],   //游戏赢分
            'bet' => $value['bet'],     //下注金额
            'jackpot' => $value['jackpot'],   //彩池奖金
            'jackpotcontribution' => implode(',',$value['jackpotcontribution']),   //彩池奖金贡献值 array
            'jackpottype' => $value['jackpottype'],   //彩池奖金类别
            'status' => $value['status'],   //游戏状态 complete:完成
            'endroundtime' => dateTimeFormat($value['endroundtime']),   //游戏结束时间
            'createtime' => dateTimeFormat($value['createtime']),   //注單結算時間及報表結算時間都是
            'bettime' => dateTimeFormat($value['bettime']),   //下注时间
            'detail' => detailData($value['detail']),   //详情 array
            'singlerowbet' => $value['singlerowbet'] ? 1 : 0,   //是否为再旋转形成的注单  [true|false]
            'gamerole' => $value['gamerole'],   // 庄(banker) or 闲(player)
            'bankertype' => $value['bankertype'],  //對戰玩家是否有真人[pc|human]  pc：对战玩家沒有真人  human：对战玩家有真人
            'rake' => $value['rake'],   //抽水金额
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if($value['account'] == $aUser[$value['account']]['username']){
            $aInsertData[] = array_merge($aUser[$value['account']], $aTemp);
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

    // 4.入库CQ9注单表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "cq9_projects` {$keys} VALUES {$values}";

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

// RFC3339时间转换
function dateTimeFormat($time) {
    $date = new DateTime($time);
    return $date->format('Y-m-d H:i:s');
}

function detailData($arr) {
    foreach ($arr as $val) {
        $val = join(",", $val);
        $temp_array[] = $val;
    }
    $str = implode(",", $temp_array);
    return $str;
}