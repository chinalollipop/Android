<?php
/**
 * 定时抓取MG电子注单数据
 * 1.拉取当前时间3分钟之前的数据
 * 2.拉取区间10分钟
 * 3.避免重复记录拉取（IGNORE）
 * Date: 2019/6/28
 */
define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";
require_once ROOT_DIR . '/common/mg/api.php';

if (php_sapi_name() == "cli") {
    // 记录抓取的错误日志，若有攻击或其他问题，可根据时间重新拉取数据
    $logPath = '/tmp/mg_log';
    if (!file_exists($logPath)) {
        mkdir($logPath, 0777, true);
        chmod($logPath, 0777);
    }
    $logFile = $logPath . '/mg_get_bet_data_' . date('Ymd', strtotime('+ 12 hour'));

    $time = time();
    if(isset($argv[1]) && isset($argv[2])){
        $startTime = trim($argv[1]);
        $endTime = trim($argv[2]);
    }else{
        $startTime = ($time - 780);
        $endTime = ($time - 180);
    }
    getBetData($startTime, $endTime);
}

//$startTime='1561711800';
//$endTime='1561712400';
//getBetData($startTime, $endTime);

function getBetData($startTime, $endTime)
{
    global $dbMasterLink, $dbLink, $parent_id;

    // 1.调用三方接口拉取数据
    writeLog("【" . date('Y-m-d H:i:s') . "】实际拉取数据日期区间【" . date('Y-m-d H:i:s') . '至' . date('Y-m-d H:i:s') . "】，时间戳：【{$startTime}至{$endTime}】");

    // 首先从redis读取access_token
    // 为空则doLogin，保存access_token、refresh_token到redis，保存一小时
    /**
     *
    主要API讲解：
    1. 登入: 在采取任何动作前，您需要有一个有效的Token。在确认您的身份后，才能使用其他的API窗口。辅助说明：
    access_token与refresh_token的有效时限为一个小时，他们差别在于前者是用于其他窗口拨叫的认证用途，而后者是为以下重刷Token所用。

    2. 重刷Token: 为了方便使用，我们鼓励用户们存取并在有效时限内，重复使用同一个Token，这样的大量的减少用户们对我们的登入窗口过度的依赖。
    如以上，每个Token的有效时限为一个小时，用户们只需要在每个Token过期前重刷一个新的Token来替代就行了。
     */
    $redisObj = new Ciredis();
    $resp = $redisObj->getSimpleOne('mg_access_token_refresh_token');
    $resp = json_decode($resp,true);
    if ($resp['success']){

        $resp_body = $resp["body"];
        $access_token = $resp_body["access_token"];
        $refresh_token = $resp_body["refresh_token"];

        // 超过1个小时则doRefreshToken
        $hour = 60*60;
        if ( (time()-strtotime($resp_body['token_last_update_time'])) > $hour){
            // redis倒计时剩余1分钟时，进行refresh
            $resp = doRefreshToken($refresh_token);
            if ($resp['success']){
                $resp_body = $resp["body"];
                $access_token = $resp_body["access_token"];
                $refresh_token = $resp_body["refresh_token"];
                $resp['body']['token_last_update_time'] = date('Y-m-d H:i:s');
                $redisObj->setOne('mg_access_token_refresh_token',json_encode($resp));
            }else{
                // 过期超过1小时没有刷新，doLogin
                $resp = doLogin();
                if ($resp['success']){
                    $resp_body = $resp["body"];
                    $access_token = $resp_body["access_token"];
                    $refresh_token = $resp_body["refresh_token"];
                    $resp['body']['token_last_update_time'] = date('Y-m-d H:i:s');
                    $redisObj->setOne('mg_access_token_refresh_token',json_encode($resp));// 设置redis
                }else{
                    exit(json_encode(['code' => 400, 'message' => 'doLogin失败'.json_encode($resp)]));
                }
            }
        }

    }
    else{
        $resp = doLogin();
        if ($resp['success']){
            $resp_body = $resp["body"];
            $access_token = $resp_body["access_token"];
            $refresh_token = $resp_body["refresh_token"];
            $resp['body']['token_last_update_time'] = date('Y-m-d H:i:s');
            $redisObj->setOne('mg_access_token_refresh_token',json_encode($resp));// 设置redis
        }else{
            exit(json_encode(['code' => 400, 'message' => 'doLogin失败'.json_encode($resp)]));
        }
    }

    $company_id = $parent_id;
//    $start_time = "2019-06-28T16:40:00";
//    $end_time = "2019-06-28T17:00:00";
    $start_time = str_replace(' ','T',date('Y-m-d H:i:s',$startTime));
    $end_time = str_replace(' ','T',date('Y-m-d H:i:s',$endTime));
//    echo $start_time.'--'.$end_time;die;
    $include_transfers = false;
    $include_end_round = false;
    $page = 1;
    $page_size = 10000;
    $aResult = getTransactionByCompanyId ($access_token, $company_id, $include_transfers, $start_time, $end_time, $include_end_round, $page, $page_size);

    $aUsername = $aBetData = [];
    if ($aResult['success']){

        /**
         * 以下为范例，让您判别注单内容是否为同一局:
        "round_id": "1001-3403-20404761-86" 可参考该参数的最后一个86为该局局号
        ext_item_id => 玩家娱乐的游戏名称

        同一个玩家、同款游戏、同一局号，即可表示该注单是同一局
         *
         *
         *  category
         *
         *  TRANSFER  转账
        WAGER 投注
        PAYOUT 派彩
        END_ROUND 是指每一游戏局中，玩家已经将该局结束，会收到一笔END_ROUND call 表示将不会再收到任何关于这局的资讯
        REFUND 撤单退款
         */

        // 注单等交易数组
        foreach ($aResult['body'] as $key => $value){
            if (in_array($value['category'], ['WAGER', 'PAYOUT', 'REFUND'])){ // 投注、派彩、撤单退款
                $aBetData[] = $value;
                $aUsername[] = $value['account_ext_ref'];
            }
        }
    }else{
        writeLog("【" . date('Y-m-d H:i:s') . "】三方返回错误码".json_encode($aResult));
        return false;
    }
    $aUsername = array_unique($aUsername); // 去重

    // 2.查询MG会员
    writeLog("【" . date('Y-m-d H:i:s') . "】MG会员数【" . count($aUsername) . "】，查询会员【" . implode(',', $aUsername) . "】");
    $sql = 'SELECT `id`, `userid`, `username`, `agents`, `world`, `corporator`, `super`, `admin`, `is_test` FROM ' . DBPREFIX . 'mg_member_data WHERE `username` IN ("' . implode('","', $aUsername) . '")';
    $result = mysqli_query($dbLink, $sql) or die("【" . date('Y-m-d H:i:s') . "】MG会员查询失败\n");
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

//    print_r($aUser);die;

    // 3.整理入库数据
    $aInsertData = $aTemp = [];
    foreach ($aBetData as $key => &$value){
        $aTemp = [
            'mgid' => $value['id'],
            'category' => $value['category'],
            'type' => $value['type'],
            'amount' => $value['amount'],
            'platform' => $value['meta_data']['context']['platform'],
            'application_id' => $value['application_id'],
            'ext_item_id' => $value['meta_data']['ext_item_id'],
            'gameid' => $value['meta_data']['mg']['game_id'],
            'serverid' => $value['meta_data']['mg']['server_id'],
            'roundid' => $value['meta_data']['round_id'],
            'itemid' => $value['meta_data']['item_id'],
            'balance' => $value['balance'],
            'transaction_time' => $value['transaction_time'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        if($value['account_ext_ref'] == $aUser[$value['account_ext_ref']]['username']){
            $aInsertData[] = array_merge($aUser[$value['account_ext_ref']], $aTemp);
        }
    }

//    print_r($aInsertData);die;
    $count = count($aInsertData);
    $keys = $values = '';
    foreach ($aInsertData as $key => $value){
        $keys = '(' . implode(",", array_keys($value)) . ')';
        $values .= "('" . implode("','", array_values($value)) . "')" . ($key + 1 == $count ? '' : ',');
    }

    // 4.入库MG注单表
    $dbMasterLink->autocommit(false);
    $sql = "INSERT IGNORE INTO `" . DBPREFIX . "mg_projects` {$keys} VALUES {$values}";

//    echo $sql; die;

    if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
        $dbMasterLink->rollback();
        writeLog("【" . date('Y-m-d H:i:s') . "】拉取时间【" . date('Y-m-d H:i:s', $startTime) . '至' . date('Y-m-d H:i:s', $endTime) . "】，时间戳：【{$startTime}至{$endTime}】，拉取注单数据失败，原因：入库失败【{$inserted}】", 1);
        return false;
    }

    $insertedRows = mysqli_affected_rows($dbMasterLink);
    $dbMasterLink->commit();
    writeLog("【" . date('Y-m-d H:i:s') . "】拉取注单数据成功，拉取记录数【{$count}】，入库记录数【{$insertedRows}】");
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