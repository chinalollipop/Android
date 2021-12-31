<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");
include_once('../include/config.inc.php');

$pageMark = isset($_REQUEST['type'])?$_REQUEST['type']:'mobile'; // 默认 mobile 系统维护

$sql = 'SELECT `title`, `state`, `content`, `mark`, `terminal_id` FROM ' . DBPREFIX . 'cms_article WHERE mark = "' . $pageMark . '" LIMIT 1';
$oResult = mysqli_query($dbLink, $sql);
$maintenanceDate = [];
$aRow = mysqli_fetch_assoc($oResult);
$aTerminal = explode(',', $aRow['terminal_id']); // 增加终端限制
$maintenanceDate = [
    'title' => $aRow['title'],
    'state' => $aRow['state'] == 1 && in_array(1, $aTerminal) ? 1 : 0,
    'content' => $aRow['content'],
];
$status = 200;
$describe = '获取数据成功';
$data = $maintenanceDate;
original_phone_request_response($status,$describe,$data);

?>
