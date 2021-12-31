<?php
/**
 * 生成第三方彩票历史官方追号数据
 * Date: 2018/8/28
 */

define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";

if(php_sapi_name() == 'cli')
{
    $limitDay = date('Y-m-d', strtotime('-15 day'));
    //$limitDay = date('Y-m-d', time());
    $startTime = (isset($argv[1]) && $argv[1] ? $argv[1] : $limitDay) . ' 00:00:00';
    $limitTime = (isset($argv[2]) && $argv[2] ? $argv[2] : $limitDay) . ' 23:59:59';
    backUpBetData();
}

function backUpBetData()
{
    global $dbMasterLink, $startTime, $limitTime;
    $dbMasterLink->autocommit(false);
    $sql = 'INSERT INTO ' . DBPREFIX . 'web_third_traces_history_data SELECT * FROM ' . DBPREFIX . 'web_third_traces_data WHERE `bought_at` BETWEEN "' . $startTime . '" AND "' . $limitTime . '"';

    if(!$inserted = mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】转移历史注单失败，原因：入库失败【{$inserted}】\n");
    }
    $sql = 'DELETE FROM ' . DBPREFIX . 'web_third_traces_data WHERE `bought_at` <= "' . $limitTime . '"';
    if(!mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】删除日期【<= {$limitTime}】的注单数据失败\n");
    }
    $dbMasterLink->commit();
    exit("【" . date('Y-m-d H:i:s') . "】生成日期【{$startTime}~{$limitTime}】的历史注单成功\n");
}