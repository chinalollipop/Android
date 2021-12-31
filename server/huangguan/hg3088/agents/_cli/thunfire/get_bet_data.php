<?php
/**
 * 定时抓取雷火fire注单数据
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间10分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2019/7/20
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/thunfire/api.php';

date_default_timezone_set ("PRC"); // 转为北京时区，然后去拉取fire注单

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/fire_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/fire_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time();
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
    //$startTime = '1586880000';  //2020-04-15 00:00:00
    //$endTime = '1590681599';    //2020-04-28 23:59:59
    $start_time = date("c", $startTime); // 取得时戳 格式為RFC3339
    $end_time = date("c", $endTime); // 取得时戳 格式為RFC3339

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s',$startTime) . '至' . date('Y-m-d H:i:s',$endTime) . "】，时间戳：【{$startTime}至{$endTime}】");

    /**
     *  params 获取注单
     *  orderID 注单号ID、 LoginName 会员ID、 bet_type 盘口类型、from_datetime 下注时间(从)、to_datetime 下注时间 (到)、
     *  from_set_datetime 结算时间 (从)、to_set_datetime 结算时间 (到)、 set_status 结算状态、
     *  from_modified_datetime 更改时间 (从)、 to_modified_datetime更改时间 (到)  参数获取最新更新注单
     **/
    $LoginName = '';    // 会员ID
    $from_datetime = '';   // 下注时间 (从)
    $to_datetime = '';   // 下注时间 (到)
    $from_set_datetime = '';    // 结算时间 (从)
    $to_set_datetime = '';   // 结算时间 (到)
    $from_mod_datetime = $start_time;    // 更改时间 (从) 这种方式只需要安排一次的任务，建议使用这个方式
    $to_mod_datetime = $end_time;  // 更改时间 (到)
    $page = 1; // 第几页
    $page_size = 1000; // 每页条数
    $aResult = getBetTransaction ('', $LoginName, null, $from_datetime, $to_datetime, $from_set_datetime, $to_set_datetime, null, $from_mod_datetime, $to_mod_datetime, $page, $page_size);

    if ($aResult['success'] && $aResult['body']['count'] > 0){
        // 判断分页，两种情况
        //  1. 只有1页直接插入
        //  2. 多页，循环插入
        if ($aResult['body']['count']>0 and $aResult['body']['count']<=$page_size){
            // 1. 只有1页直接插入
            $res = addBetData($aResult, $startTime, $endTime);
            if (!$res){
                return false;
            }
        }
        else{

            //  2. 多页，先执行第一页的添加，循环从第2页开始循环添加
            $res = addBetData($aResult, $startTime, $endTime);
            if (!$res){
                return false;
            }
            // 间隔15秒后继续抓取
            sleep(10);
            // 总页数 = 向上取整（总条数/每页条数）
            $totalpages = ceil( $aResult['body']['count'] / $page_size );
            for ($i = 2; $i <= $totalpages; $i++){

                writeLog("【" . date('Y-m-d H:i:s') . "】 {$i}/{$totalpages} 页数据开始");
                $page = $i;
                $aResult = getBetTransaction (null, $LoginName, null, $from_datetime, $to_datetime, $from_set_datetime, $to_set_datetime, null, $from_mod_datetime, $to_mod_datetime, $page, $page_size);
                //$aResult = json_decode($res, true);
                $res = addBetData($aResult, $startTime, $endTime);
                if (!$res){
                    return false;
                }

                // 处理完最后一页正常结束，无需sleep
                if ($i < $totalpages) sleep(10);
            }
        }


        // 查询连串注单
        /*foreach ($aResult['body']['results'] as $key => &$value){
            // 连串插入
            if($value['is_combo'] == 'true') {
                $combo_ids[] = $value['id'];
                $aResult = getBetTransaction ($value['id'], null, null, $from_datetime, $to_datetime, $from_set_datetime, $to_set_datetime, null, $from_mod_datetime, $to_mod_datetime, $page, $page_size);
                echo '<pre>';
                var_DUMP($value['tickets']);exit;

            }
        }
        $combo_ids = array_unique($combo_ids); // 去重
        writeLog("【" . date('Y-m-d H:i:s') . "】，查询连串注单号【" . implode(',', $combo_ids) . "】");*/

    }else{
        writeLog("【" . date('Y-m-d H:i:s') . "】注单该时间段内数据为空！");
        return false;
    }

}

function addBetData($aResult, $startTime, $endTime){
    global $dbMasterLink, $dbLink;
    $aUsername = $aBetData = [];
    if ($aResult['success']){

        // 注单等交易数组
        foreach ($aResult['body']['results'] as $key => $value){
            $aBetData[] = $value;
            $aUsername[] = $value['member_code']; //用户名
        }
    }else{
        writeLog("【" . date('Y-m-d H:i:s') . "】三方返回错误码".json_encode($aResult));
        return false;
    }
    $aUsername = array_unique($aUsername); // 去重

    // 2.查询fire会员
    writeLog("【" . date('Y-m-d H:i:s') . "】Fire会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'fire_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】FIRE会员查询失败\n");
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
        $value['competition_name'] = str_replace("\"",'', $value['competition_name']); // 去除双引号
        $value['competition_name'] = str_replace('\'','', $value['competition_name']); // 去除单引号
        $aTemp = [
            'orderID' => $value['id'],  // 注单id
            'odds' => $value['odds'],   // 赔率
            'malay_odds' => $value['malay_odds'],           // 马来赔率
            'euro_odds' => $value['euro_odds'],             // 欧盘赔率
            'member_odds' => isset($value['member_odds']) ? $value['member_odds'] : '',         // 会员赔率
            'member_odds_style' => isset($value['member_odds_style']) ? $value['member_odds_style'] : '',// 会员下的盘
            'game_type_id' => isset($value['game_type_id']) ? $value['game_type_id'] : '',       // 游戏ID
            'game_type_name' => isset($value['game_type_name']) ? $value['game_type_name'] : '',   // 游戏名称
            'game_market_name' => $value['game_market_name'],// 盘口名称
            'market_option' => $value['market_option'],     // 盘口局分
            'map_num' => isset($value['map_num']) ? $value['map_num'] : '',        // 第几局 MAP(第一局) Q1(第一节) FIRST HALF(上半场) SECOND HALF(下半场)
            'bet_type_name' => $value['bet_type_name'],     // 盘口类型
            'competition_name' => isset($value['competition_name']) ? $value['competition_name'] : '',// 比赛名称
            'event_id' => $value['event_id'],               // 赛事ID
            'event_name' => isset($value['event_name']) ? $value['event_name'] : '',           // 赛事名称
            'event_datetime' => date('Y-m-d H:i:s',strtotime( $value['event_datetime'])-12*60*60),   // 赛事开始时间  将UTC时间转成格式化时间 date('Y-m-s H:i:s',strtotime('2019-01-09T18:19:39+08:00'));
            'date_created' => date('Y-m-d H:i:s',strtotime( $value['date_created'])-12*60*60),       // 下注时间
            'settlement_datetime' => isset($value['settlement_datetime']) ? date('Y-m-d H:i:s',strtotime( $value['settlement_datetime'])-12*60*60) : '',// 结算时间
            'modified_datetime' => date('Y-m-d H:i:s',strtotime( $value['modified_datetime'])-12*60*60), // 更改时间
            'bet_selection' => isset($value['bet_selection']) ? $value['bet_selection'] : '',     // 下注选项
            'currency' => $value['currency'],               // 货币
            'amount' => $value['amount'],                   // 下注金额
            'settlement_status' => $value['settlement_status'],//注单状况
            'is_unsettled' => $value['is_unsettled'],       // 是否重新结算
            'result_status' => isset($value['result_status']) ?  $value['result_status'] : '',   // 注单结果
            'result' => isset($value['result']) ?  addslashes($value['result'])  : '',           // 盘口结果
            'earnings' => isset($value['earnings']) ?  $value['earnings'] : '',      // 输赢额
            'reward' => getRewardWith($value),        // 自定义 根据赛事获取盈利，平台报表使用
            'handicap' => isset($value['handicap']) ? $value['handicap'] : '',       // 让分数
            'is_combo' => $value['is_combo'],        // 是否连串
            'request_source' => $value['request_source'],    // 下注渠道
            'ticket_type' => isset($value['ticket_type']) ? $value['ticket_type'] : '',    // 注单下注状况  db=早盘 live=滚球
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if($value['member_code'] == $aUser[$value['member_code']]['username']){
            $aInsertData[] = array_merge($aUser[$value['member_code']], $aTemp);
        }
    }

    $count = count($aBetData);
    $keys = $values = '';
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库fire注单表  INSERT IGNORE INTO (已存在)
    $dbMasterLink->autocommit(false);
    $sql = "REPLACE INTO `" . DBPREFIX . "fire_projects` {$keys} VALUES {$values}";

    //echo $sql; die;
    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $dbMasterLink->rollback();
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime) . '至' . date('Y-m-d H:i:s', $endTime) . "】，时间戳：【{$startTime}至{$endTime}】，拉取注单数据失败，原因：入库失败【{$inserted}】", 1);
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】");
    $dbMasterLink->commit();
    return true;
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

