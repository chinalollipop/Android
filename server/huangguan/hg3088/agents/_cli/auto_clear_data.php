<?php
/**
 * 定时清理账变数据
 * Date: 2018/7/27
 */

define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/config.inc.php";
define("CLEAR_DATA_BACKUP", '/tmp/clear_data_backup/');

if (php_sapi_name() == "cli")
{
    if (!is_dir(CLEAR_DATA_BACKUP))
        @mkdir (CLEAR_DATA_BACKUP, 0777);
    $keepDays = isset($argv[1]) ? $argv[1] : 15; // 默认保留时间段数据
    $endDate = date("Y-m-d", strtotime("-{$keepDays} days"));
    $func = 'clear' . (isset($argv[2]) ? $argv[2] : 'Bill') . 'Data';
    $func($endDate, $dbMasterLink);
}

/**
 * 账变数据清理
 * @param $endDate
 * @param $dbMasterLink
 */
function clearBillData($endDate, $dbMasterLink)
{
    $endDate = strtotime($endDate); // 数据库时间戳处理
    $bBackUp = backUpData('web_account_change_records', "addTime < '{$endDate}'");
    if($bBackUp == 0) {
        $sql = "delete from " . DBPREFIX . "web_account_change_records where addTime < '{$endDate}'";
        mysqli_query($dbMasterLink, $sql) or die("清理账变数据异常!!!" . mysqli_error($dbMasterLink));
    }
}

/**
 * 用户操作日志数据清理
 * @param $endDate
 * @param $dbMasterLink
 */
function clearOperateData($endDate, $dbMasterLink)
{
    $bBackUp = backUpData('web_mem_log_data', "LoginTime < '{$endDate}'");
    if($bBackUp == 0){
        $sql = "delete from " . DBPREFIX . "web_mem_log_data where LoginTime < '{$endDate}'";
        mysqli_query($dbMasterLink, $sql) or die("清理用户操作数据异常!!!" . mysqli_error($dbMasterLink));
    }
}

/**
 * 根据条件备份数据
 * @param $tableName
 * @param $condition
 * @return mixed
 */
function backUpData($tableName, $condition)
{
    GLOBAL $database, $endDate;
    $backupFile = $tableName . '_' . $endDate . '.bak';
    $backupDir = CLEAR_DATA_BACKUP . $backupFile;
    if(file_exists($backupDir)) // 避免重复执行，覆盖备份文件
        return 0;
    exec("mysqldump -h{$database['gameDefault']['host']} -P{$database['gameDefault']['port']} -u{$database['gameDefault']['user']} -p{$database['gameDefault']['password']} {$database['gameDefault']['dbname']} " . DBPREFIX . $tableName . " --where=\"" . $condition . "\"> $backupDir 2>&1", $output, $returnVal);
    if($returnVal != 0)
        die("数据备份失败!!!" . var_dump($output));
    return $returnVal;
}



