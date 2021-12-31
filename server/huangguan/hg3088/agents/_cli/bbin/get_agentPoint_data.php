<?php
/**
 * 金星代理抓取点数变动数据
 * Date: 2019/12/9
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/bbin/api.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/bbin_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/agent_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time()+12*60*60; // bbin注单 转为北京时间拉取
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
    global $dbMasterLink, $dbLink, $bbin_prefix, $bbin_agent, $bbinSxInit;

    $md5Key = $bbinSxInit['data_api_md5_key'];
    $startDate = date('Y-m-d H:i:s',$startTime);
    $endDate = date('Y-m-d H:i:s',$endTime);

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s') . '-' . date('Y-m-d H:i:s') . "】，拉取时间区间：【{$startDate}-{$endDate}】");

    //查询代理点数变动
    $aResult = agentV1GetPointsChange($bbin_agent, $md5Key);

    $aUsernames = $aBetData = $agentPointData = [];
    if ($aResult['success'] && !empty($aResult['body']['data'])) {
        // 注单等交易数组
        foreach ($aResult['body']['data'] as $key => $value){

            if(strpos($value['username'] , strtoupper($bbin_prefix)) !== false){   // 筛选平台注单返回的用户名大写  CHDEVJOHN205
                $aBetData[$key] = $value;
                $aUsernames[] = $value['username'];  // 用于查询该会员是否存在
                $agentPointData[] = $value['order']; //注单号码
            }
        }
    }else{  // 获取注单失败
        writeLog("【" . date('Y-m-d H:i:s') . "】金星三方代理查询点数为空" . "返回错误码".json_encode($aResult));
        return false;
    }

    $aUsername = array_unique($aUsernames); // 会员去重
    $agentPointData = array_unique($agentPointData); // 注单号码去重,用于下注记录单号回传

    if(empty($aUsername) || empty($agentPointData)) {
        writeLog("【" . date('Y-m-d H:i:s') . "】金星三方代理会员或注单号码为空");
        return false;
    }


    // 2.查询BBIN会员
    writeLog("【" . date('Y-m-d H:i:s') . "】BBIN会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'jx_bbin_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】BBIN会员查询失败\n");
    $aUser = [];
    while ($row = mysqli_fetch_assoc($result)){
        $aUser[$row['username']] = [
            'username' => $row['username'],
        ];
    }

    // 3.整理入库数据
    $aInsertData = $aTemp = [];
    foreach ($aBetData as $key => &$value){

        $aTemp = [
            //'username' => $value['username'],   //用户名
            'ip' => isset($value['IP']) ? $value['IP'] : '',   //IP
            'orderid' => $value['order'],   //点数订单号
            'prebalance' => $value['prebalance'],     //之前点数
            'opebalance' => $value['opebalance'],     //变动点数
            'balance' => $value['balance'],     //当前点数
            'starttime' => date('Y-m-d H:i:s',strtotime($value['starttime'])-12*60*60),   //开始时间转美东时间
            'endtime' => date('Y-m-d H:i:s',strtotime($value['endtime'])-12*60*60),   //结束时间
            'createtime' => $value['createtime'],   //创建时间
            'reason' => $value['reason'],   //代理备注
        ];

        if($value['username'] == $aUser[$value['username']]['username']){ // CHDEVJOHN103
            $aInsertData[] = array_merge($aUser[$value['username']], $aTemp);
        }
        //$aInsertData[] = $aTemp;
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

    // 4.入库金星代理点数
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "jx_agent_point` {$keys} VALUES {$values}";

    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $dbMasterLink->rollback();
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime) . '至' . date('Y-m-d H:i:s', $endTime) . "】，时间戳：【{$startTime}至{$endTime}】，拉取注单数据失败，原因：入库失败【{$inserted}】", 1);
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】");
    $dbMasterLink->commit();

    // 5. 入库成功后，下注记录单号回传
    if($insertedRows) {
        $cWagersData = count($agentPointData);
        $str_WagersData = implode(',', $agentPointData);
        //$str_WagersData = '201912050407574472,2019120504444268238';

        $cResult = agentV1GetPointsChangeConfirm($bbin_agent, $md5Key, $str_WagersData); //注单下注记录单号回传

        // 回传成功
        if($cResult['success']) {
            //$cWagersData = count($aResult['body']['data']);
            writeLog("【" . date('Y-m-d H:i:s') . "】注单数据回传成功，回传单号【{$str_WagersData}】，回传成功总数【{$cWagersData}】");
        } else{
            writeLog("【" . date('Y-m-d H:i:s') . "】注单数据回传失败，回传单号【{$str_WagersData}】，回传失败总数【{$cWagersData}】");
        }
    }

    //6. 删除近一月以上数据
    $monthTime = date("Y-m-d H:i:s", strtotime("-1 month"));
    mysqli_query($dbMasterLink, "DELETE FROM " . DBPREFIX . "jx_agent_point WHERE  `endtime` <= '{$monthTime}'");


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
