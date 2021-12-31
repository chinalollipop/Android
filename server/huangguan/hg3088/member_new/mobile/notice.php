<?php
/**
 * 首页轮播公告&公告列表
 * Date: 2018/8/2
 */

include_once('include/config.inc.php');

if(!isset($_REQUEST['appRefer']))
    exit(json_encode(['status' => 0, 'describe' => '缺少参数！']));

$terminalId = intval($_REQUEST['appRefer']);
$aTerminal = mysqli_query($dbLink, 'SELECT id FROM ' . DBPREFIX . 'web_terminals WHERE id = ' . $terminalId);
if(!mysqli_num_rows($aTerminal))
    exit(json_encode(['status' => 0, 'describe' => '非法终端！']));

$iCount = isset($_REQUEST['carousel']) && $_REQUEST['carousel'] == 1 ? 3 : 20; // 默认4个轮播图
$sql = 'SELECT `Date`, `Message` FROM ' . DBPREFIX . 'web_marquee_data ORDER BY `ID` DESC LIMIT ' .  $iCount;
$oResult = mysqli_query($dbLink, $sql);

$data = [];
while ($aRow = mysqli_fetch_assoc($oResult)){
    $data[] = [
        'notice' => $aRow['Message'],
        'created_time' => $aRow['Date']
    ];
}

original_phone_request_response(200, 'success', $data);