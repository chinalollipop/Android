<?php
/**
 * 新版图片配置接口
 * Date: 2018/8/1
 */

include_once("../include/config.inc.php");

$data = [];
$picConData = getPicConfig();  //获取Redis图片数据

// redis不存在
if(empty($picConData)) {

    $sql = "SELECT `id`, `key`, `pic_url`, `category_id`, `remark` FROM " . DBPREFIX . "web_pconfig WHERE status = 1";
    $result = mysqli_query($dbLink, $sql);
    $picConData=array();
    while ($row = mysqli_fetch_assoc($result)){
        $picConData[$row['key']] = !empty($row['pic_url']) ? PROMOS_PIC_DOMAIN . $row['pic_url'] : '';
    }
}

foreach($picConData as $key => $value) {
    $data[$key] = $value;
}


original_phone_request_response(200, 'success', $data);