<?php
/**
 * 客户端首页轮播图
 * Date: 2018/8/1
 */

include_once('include/config.inc.php');
//@error_log(json_encode($_SERVER).PHP_EOL, 3, '/tmp/banner.log');

//$iCount = isset($_REQUEST['count']) ? intval($_REQUEST['count']) : 4; // 默认4个轮播图
$sql = 'SELECT `id`, `pic_url`,`name` FROM ' . DBPREFIX . 'web_banners WHERE `is_closed` = 0 ORDER BY `sort` ASC, `id` DESC';
//empty($iCount) or $sql .= ' LIMIT ' . $iCount;
$oResult = mysqli_query($dbLink, $sql);

$data = [];
while($aRow = mysqli_fetch_assoc($oResult)){
    $data[] = [
        'img_path' => bHttps() ? 'https://' . $_SERVER['HTTP_HOST'] .'/'.TPL_NAME. $aRow['pic_url'] : 'http://' . $_SERVER['HTTP_HOST'] .'/'.TPL_NAME. $aRow['pic_url'],
        'name' => $aRow['name']
    ];
}

original_phone_request_response(200, 'success', $data);
