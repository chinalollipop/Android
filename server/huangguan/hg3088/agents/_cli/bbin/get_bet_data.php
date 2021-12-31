<?php
/**
 * 定时抓取BBIN真人视讯注单数据
 * 1.拉取当前时间3分钟之前的数据 （不用）
 * 2.拉取区间10分钟  （不用）
 * 3.避免重复记录拉取（IGNORE）
 * 真人和电子，棋牌，捕鱼实时结算
 * 彩票 、 体育结算会有延迟
 *
 * App.Platform_Bbin_V1.GetBet      获取下注记录
 *      money：真人额度转换记录
        bet:真人下注记录 视讯直播(BB视讯)
        bet_sport:体育下注记录 体育赛事(BB体育)
        bet_slots:电子游艺注单 电子游艺(捕鱼机  BB电子)
        bet_lottery：彩票注单 彩票游戏(BB彩票)
        bet_3d:3D彩票
 *
 * App.Platform_Bbin_V1.GetBetConfirm  回传下注单号
        money：真人额度转换记录(ID=TransID)
        bet:真人下注记录(ID=WagersID)
        bet_sport:体育下注记录(ID=WagersID)
        bet_slots:电子游艺注单(ID=WagersID)
        bet_lottery：彩票注单(ID=WagersID)
        bet_3d:3D彩票(ID=WagersID)
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
    $logFile = $logPath . '/bbin_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

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
    global $dbMasterLink, $dbLink, $platform_owner_id, $bbin_prefix, $bbin_agent, $bbinSxInit, $betGameType,$bbinGameTypeKind;

    $md5Key = $bbinSxInit['data_api_md5_key'];
    $startDate = date('Y-m-d H:i:s',$startTime);
    $endDate = date('Y-m-d H:i:s',$endTime);

        // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s') . '-' . date('Y-m-d H:i:s') . "】，拉取时间区间：【{$startDate}-{$endDate}】");

    $type = 'bet';  //真人下注记录 视讯直播(BB视讯)
    $aResult = bbinV1GetBet($bbin_agent, $md5Key, $type); //注单查询


    $aUsernames = $aBetData = $WagersData = [];
    if ($aResult['success'] && !empty($aResult['body']['data'])) {
        // 注单等交易数组
        foreach ($aResult['body']['data'] as $key => $value){

            if(strpos($value['UserName'] , $bbin_prefix) !== false){   //chdev 筛选平台注单
                $aBetData[$key] = $value;
                $aUsernames[] = strtoupper($value['UserName']);  // 用于查询该会员是否存在  chdevjohn205  拼接成 CHDEVJOHN205
                $WagersData[] = $value['WagersID']; //注单号码
            }
        }
    }else{  // 获取注单失败
        writeLog("【" . date('Y-m-d H:i:s') . "】三方BBIN" . $betGameType[$type] . "返回错误码".json_encode($aResult));
        return false;
    }

    $aUsername = array_unique($aUsernames); // 会员去重
    $WagersData = array_unique($WagersData); // 注单号码去重,用于下注记录单号回传


    // 2.查询BBIN会员
    writeLog("【" . date('Y-m-d H:i:s') . "】BBIN会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'jx_bbin_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】BBIN会员查询失败\n");
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
    foreach ($aBetData as $key => &$value){
        //注单名 chdevjohn205  转换成 CHDEVJOHN205
        $value['UserName'] = strtoupper($value['UserName']);

        $aTemp = [
            'WagersID' => $value['WagersID'],   //注单号码
            'GameType' => $value['GameType'],   //游戏种类
            'Result' => $value['Result'],       //注单结果 BB视讯、BB体育、BB电子、BB彩票、3D彩票
            'SerialID' => isset($value['SerialID']) ? $value['SerialID'] : '',    //BB视讯 局号
            'RoundNo' => isset($value['RoundNo']) ? $value['RoundNo'] : '',       //BB视讯 场次
            'WagerDetail' => isset($value['WagerDetail']) ? $value['WagerDetail'] : '',     //BB视讯 玩法
            'GameKind' => isset($bbinGameTypeKind[$type]) ? $bbinGameTypeKind[$type] : '',  //BBIN BB视讯
            'GameCode' => isset($value['GameCode']) ? $value['GameCode'] : '',       //BB视讯 桌号
            'ResultType' => isset($value['ResultType']) ? $value['ResultType'] : '', //BB视讯 注单结果 -1：注销、0：未结算
            'Card' => isset($value['Card']) ? $value['Card'] : '',     //BB视讯 结果牌
            'BetAmount' => $value['BetAmount'],     //下注金额
            'Payoff' => $value['Payoff'],   //派彩金额
            'Currency' => $value['Currency'],   //货币符号
            'ExchangeRate' => $value['ExchangeRate'],     //与人民币的汇率
            'Commissionable' => $value['Commissionable'],     //会员有效投注额
            'Commission' => isset($value['Commission']) ? $value['Commission'] : '',   //BB彩票退水
            'IsPaid' => isset($value['IsPaid']) ? $value['IsPaid'] : '',   //BB彩票 派彩标识（Y：已派彩、N：未派彩）;
            'Origin' => $value['Origin'],   //下注装置
            'prefix' => $value['prefix'],   //前缀标识
            'copyFlag' => $value['copyFlag'],   //标识号
            'WagersDate' => date('Y-m-d H:i:s',strtotime($value['WagersDate'])-12*60*60),   //游戏下注时间转美东时间
            'updatedatetime' => date('Y-m-d H:i:s',strtotime($value['updatedatetime'])-12*60*60),   //回传接口更新时间
            'copyFlag' => $value['copyFlag'],   //标识号
            //'detail' => detailData($value['detail']),   //详情 array
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if($value['UserName'] == $aUser[$value['UserName']]['username']){ // CHDEVJOHN103
            $aInsertData[] = array_merge($aUser[$value['UserName']], $aTemp);
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

    // 4.入库BBIN注单表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "jx_bbin_projects` {$keys} VALUES {$values}";
    //echo $sql;

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
        $cWagersData = count($WagersData);
        $str_WagersData = implode(',', $WagersData);
        $cResult = bbinV1GetBetConfirm($bbin_agent, $md5Key, $type, $str_WagersData); //注单下注记录单号回传
        // 回传成功
        if($cResult['success']) {
            writeLog("【" . date('Y-m-d H:i:s') . "】注单数据回传成功，回传单号【{$str_WagersData}】，回传成功总数【{$cWagersData}】");
        } else{
            writeLog("【" . date('Y-m-d H:i:s') . "】注单数据回传失败，回传单号【{$str_WagersData}】，回传失败总数【{$cWagersData}】");
        }
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

function printLine ($message) {
    echo "<BR>{$message}";
}


/*function detailData($arr) {
    foreach ($arr as $val) {
        $val = join(",", $val);
        $temp_array[] = $val;
    }
    $str = implode(",", $temp_array);
    return $str;
}*/