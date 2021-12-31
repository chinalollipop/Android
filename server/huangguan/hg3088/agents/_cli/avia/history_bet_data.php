<?php
/**
 * 生成泛亚电竞历史注单数据
 * Date: 2018/8/29
 */

define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require_once CONFIG_DIR . "/app/agents/include/config.inc.php";
require_once CONFIG_DIR."/app/agents/include/redis.php";

if(php_sapi_name() == 'cli')
{
    $limitDay = date('Y-m-d', strtotime('-15 day'));
    $startTime = (isset($argv[1]) && $argv[1] ? $argv[1] : $limitDay) . ' 00:00:00';
    $limitTime = (isset($argv[2]) && $argv[2] ? $argv[2] : $limitDay) . ' 23:59:59';
    backUpBetData();
}

function backUpBetData()
{
    global $dbMasterLink, $startTime, $limitTime;
    $dbMasterLink->autocommit(false);
    $sql = 'INSERT INTO ' . DBPREFIX . 'avia_history_projects SELECT * FROM ' . DBPREFIX . 'avia_projects WHERE `updateAt` BETWEEN "' . $startTime . '" AND "' . $limitTime . '" AND status in (\'Win\',\'Lose\',\'WinHalf\',\'LoseHalf\')';
    if(!$inserted = mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】转移历史注单失败，原因：入库失败【{$inserted}】\n");
    }
    $sql = 'DELETE FROM ' . DBPREFIX . 'avia_projects WHERE `updateAt` <= "' . $limitTime . '"';
    if(!mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit("【" . date('Y-m-d H:i:s') . "】删除日期【<= {$limitTime}】的注单数据失败\n");
    }
    $dbMasterLink->commit();
    exit("【" . date('Y-m-d H:i:s') . "】生成日期【{$startTime}~{$limitTime}】的历史注单成功\n");
}