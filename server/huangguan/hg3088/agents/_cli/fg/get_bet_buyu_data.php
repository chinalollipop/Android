<?php
/**
 * 定时抓取FG电子注单数据
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间10分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2019/10/19
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/fg/api.php';


if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/fg_log/fg_buyu_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/fg_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time(); // fg注单美东时间，若转为北京时间拉取 +12*60*60
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
    global $dbMasterLink, $dbLink, $api_merchant;
    //$startDate = date('Y-m-d H:i:s',$startTime);
    //$endDate = date('Y-m-d H:i:s',$endTime);
    $startDate = $startTime;
    $endDate = $endTime;

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s') . '-' . date('Y-m-d H:i:s') . "】，拉取时间区间：【{$startDate}-{$endDate}】");

    /*
     * 1. 分页采集的使用说明：FG 提供了 4 大类游戏接口对应的 gt 简称为hunter(捕猎),chess(棋牌),slot(老虎机),arcade(水果机),
     * 2. 由于技术架构 4 类游戏存放在不同的数据表中,所以接入方要启动 4 个定时器来采集数据.
     *        2.1分页采集接口接入方要保留每次返回的 page_key,下一页的采集需要传入新的page_key.
     *        2.2该接口没有提供时间参数,固定使用[0,当前 unix 时间戳－10]时间范围来进行分页.-10 说明数据有 10 秒的延迟
     *        2.3下级代理采集数据时需要提供时间范围（即 start_time、end_time 字段，详见 4.2 接口），否则 start_time 默认设置为当前时间-12 小时，end_time 默认设置为当前时间+12 小时
     * 3. 只限制一次拉取的条数3000，请求次数没限制，下一次采集根据上一次采集返回的page_key来继续后面的数据采集
     * 4. 拉取时间范围最好一天
     */

    $page_size = 3000; // 每页条数
    $id = isset($id) ? $id :'';    //单号

    //$gts = ['chess' , 'slot', 'arcade'];    //chess(棋牌),slot(老虎机),arcade(水果机)
    $gt = 'hunter';

    /**
     * 1.先获取游戏总记录数。
     * 2.小于3000 则直接调取v3_1/agent/log_by_page 这个方法。
     * 3.大于3000， 第一页返回的page_key再请求获取。
     * */
    //foreach($gts as $key => $gt){

    $cResult = getV3LogByCount($api_merchant, $startDate, $endDate, $gt); //获取注单总记录数
    if(!$cResult['success']) {
        writeLog("【" . date('Y-m-d H:i:s') . "】三方FG获取“ . $gt . ”注单总记录数返回错误码".json_encode($cResult), 1);
        return false;
    }

    $aResult = getV3LogRecords($api_merchant, $startDate, $endDate, $gt, '', $id); //首次获取采集注单数据
    if($aResult['body']['page_key'] != 'none' || count($aResult['body']['data']) !== 0) {
        $page = $page_key = $aResult['body']['page_key'];   // 下一页page_key
    } else {
        writeLog("【" . date('Y-m-d H:i:s') . "】三方FG获取“ . $gt . ”时注单数据为空", 1);
        return false;
    }


    // 拉取注单数据成功
    if($aResult['success']) {
        // 判断分页，两种情况
        //  1. 只有1页直接插入
        //  2. 多页，循环插入
        if ($aResult['body']['data']>0 and $cResult['body']['total']<$page_size){
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
            $totalpages = ceil( $cResult['body']['total'] / $page_size );

            // 默认第一次请求返回的page_key
            $tmp_page_key = isset($tmp_page_key) ? $tmp_page_key : $page;

            for ($i = 2; $i <= $totalpages; $i++){

                writeLog("【" . date('Y-m-d H:i:s') . "】 {$i}/{$totalpages} 页数据开始" , 1);
                //$page = $i;

                //writeLog("tmp_page_key:" . $tmp_page_key . $i , 1);
                $aResult = getV3LogRecords($api_merchant, $startDate, $endDate, $gt, $tmp_page_key, $id); //获取注单数据

                if($aResult['body']['page_key'] == 'none' || count($aResult['body']['data']) == 0) {
                    writeLog("【" . date('Y-m-d H:i:s') . "】三方FG获取“ . $gt . ”游戏时 " . $i. " 页注单数据为空", 1);
                    return false;
                }

                if($aResult['body']['page_key'] !== 'none') {   // 分页符
                    unset($tmp_page_key);
                    $tmp_page_key = $page = $page_key = $aResult['body']['page_key'];
                }

                $res = addBetData($aResult, $startTime, $endTime);
                if (!$res){
                    return false;
                }

                // 处理完最后一页正常结束，无需sleep
                if ($i < $totalpages) sleep(10);
            }
        }
    } else{
        writeLog("【" . date('Y-m-d H:i:s') . "】三方FG注单返回错误码".json_encode($aResult), 1);
        return false;
    }

    //}

}

function addBetData($aResult, $startTime, $endTime){
    global $dbMasterLink, $dbLink;
    $aUsername = $aBetData = [];
    if ($aResult['success']){
        // 注单等交易数组
        foreach ($aResult['body']['data'] as $key => $value){
            $aBetData[] = $value;
            $aUsername[] = $value['player_name'];
        }
    }else{
        writeLog("【" . date('Y-m-d H:i:s') . "】FG三方返回错误码".json_encode($aResult));
        return false;
    }
    $aUsername = array_unique($aUsername); // 去重

    // 2.查询FG会员
    writeLog("【" . date('Y-m-d H:i:s') . "】FG会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】", 1);
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'fg_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】FG会员查询失败\n");
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
        $aTemp = [
            'orderid' => $value['id'],           //游戏单号
            'total_agent_uid' => $value['total_agent_uid'],     //总代理商id
            'agent_uid' => $value['agent_uid'],  //代理商id
            //'player_name' => $value['player_name'],    //玩家名称
            'game_id' => $value['game_id'],
            'gt' => $value['gt'],                //游戏类型代码(slot 老虎机,arcade 街机,chess 棋牌,hunter 捕猎)
            'start_chips' => isset($value['start_chips']) ? $value['start_chips'] : '',   //开始筹码
            'end_chips' => $value['end_chips'],  //结束筹码
            'all_bets' => $value['all_bets'],    //总投注 (chess 对应后台的是有效打码)
            'all_wins' => $value['all_wins'],    //总奖金
            'total_bets' => isset($value['total_bets']) ? $value['total_bets'] : '',   //总下注(chess 对应后台的总下注字段包括预扣金额)
            'jackpot_bonus' => $value['jackpot_bonus'], //jackpot 奖金保留４位小数
            'jp_contri' => $value['jp_contri'],  //JP贡献保留４位小数
            'result' => $value['result'],        //all_wins-all_bets(对应后台的收支)
            'scene_id' => isset($value['scene_id']) ? $value['scene_id'] : '',   //捕猎编号ID
            'bullet_count' => isset($value['bullet_count']) ? $value['bullet_count'] : '',   //捕猎子弹个数
            'type' => isset($value['type']) ? $value['type'] : '',    //1捕鱼账单2jp奖账单6买鱼注单...
            'currency' => $value['currency'],    //货币符号
            'ip' => $value['ip'],                //玩家IP
            'begintime' => isset($value['start_time']) ? date('Y-m-d H:i:s' , $value['start_time']) : '',  //游戏开始时间
            'endtime' => date('Y-m-d H:i:s' , $value['time']),  //游戏结束时间
            'device' => $value['device'],       //设备类型 (1PC 2IOS横 3IOS竖 4android横 5android竖 6其他横 7其他竖
            'created_at' =>  date('Y-m-d H:i:s'),
        ];
        if($value['player_name'] == $aUser[$value['player_name']]['username']){
            $aInsertData[] = array_merge($aUser[$value['player_name']], $aTemp);
        }
    }

    $count = count($aBetData);
    $keys = $values = '';
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库FG注单表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "fg_projects` {$keys} VALUES {$values}";

//    echo $sql; die;

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


// 获取注单数据
/*function getV3Records ($api_merchant, $startDate, $endDate, $gt, $page, $id) {
    $aResult = getV3LogRecords($api_merchant, $startDate, $endDate, $gt, $page, $id); //获取注单数据
    return $aResult;
}*/

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

