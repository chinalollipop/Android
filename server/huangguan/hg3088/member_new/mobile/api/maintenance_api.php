<?php
/**
 * 客戶端單頁維護接口（為了區分ios和android）
 * Date: 2018/11/6
 */
include_once('../include/config.inc.php');

if(!isset($_REQUEST['appRefer'])){
//    exit(json_encode(['status' => 0, 'describe' => '缺少参数！']));
    $status=0;
    $describe='缺少参数！';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

$terminalId = intval($_REQUEST['appRefer']);
$aTerminal = mysqli_query($dbLink, 'SELECT id FROM ' . DBPREFIX . 'web_terminals WHERE id = ' . $terminalId);
if(!mysqli_num_rows($aTerminal)){
//    exit(json_encode(['status' => 0, 'describe' => '非法终端！']));
    $status=0;
    $describe='非法终端！';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

$data = [];
// 单页维护
$sql = 'SELECT `title`, `state`, `content`, `mark`, `terminal_id` FROM ' . DBPREFIX . 'cms_article WHERE `category_id` !=9 ';
$oResult = mysqli_query($dbLink, $sql);
while ($aRow = mysqli_fetch_assoc($oResult)){
    $aTerminal = explode(',', $aRow['terminal_id']);
    $data[] = [
        'type' => $aRow['mark'],
        'title' => $aRow['title'],
        'state' => $aRow['state'] == 1 && in_array($terminalId, $aTerminal) ? 1 : 0,
        'content' => $aRow['content']
    ];
}
original_phone_request_response(200, 'success', $data);

