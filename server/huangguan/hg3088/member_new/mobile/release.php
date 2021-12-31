<?php

/**
 * 版本升级接口（适用于手机客户端web）
 * Date: 2018/7/28
 */

include_once('include/config.inc.php');

if(!isset($_GET['appRefer']))
    exit(json_encode(['status' => 0, 'describe' => '缺少参数']));

$terminalId = intval($_GET['appRefer']);
$aTerminal = mysqli_query($dbLink, 'SELECT id FROM ' . DBPREFIX . 'web_terminals WHERE id = ' . $terminalId);
$rowCount = mysqli_num_rows($aTerminal);
if(!$rowCount)
    exit(json_encode(['status' => 0, 'describe' => '非法终端']));

$sql = 'SELECT version, file_path, description, is_force FROM ' . DBPREFIX . 'web_releases WHERE terminal_id = ' . $terminalId . ' AND status = 1 ORDER BY ID DESC';
$aRelease = mysqli_query($dbLink, $sql) or die('数据异常！！！' . mysqli_error($dbLink));
$aRow = mysqli_fetch_assoc($aRelease);
exit(json_encode(['status' => 200, 'describe' => 'success', 'data' => $aRow]));






