<?php
/**
 * 新版首页轮播图
 * Date: 2018/8/1
 * 接口 banner 名称 会在IOS部分机型的UC浏览器兼容有问题
 */

include_once("../include/config.inc.php");

//$iCount = isset($_REQUEST['count']) ? intval($_REQUEST['count']) : 4; // 默认4个轮播图

/*$sql = 'SELECT `id`, `pic_url`,`name` FROM ' . DBPREFIX . 'web_banners WHERE `is_closed` = 0 ORDER BY `sort` ASC, `id` DESC';
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
*/

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'mobile';
$sitename = isset($_REQUEST['sitename']) ? $_REQUEST['sitename'] : TPL_FILE_NAME;    // 国民区分tyc和 wnsr

$redisObj = new Ciredis();
$carouseData = $redisObj->getSimpleOne('carouse_pic_set');  //获取Redis轮播数据
$carouseData = json_decode($carouseData,true) ;

//模板名称，0086 6668 3366 suncity jinsha wnsr  newhg  8msport 其中suncity wnsr 需要区分
// redis不存在
if(empty($carouseData)) {
    $sql = "SELECT `id`, `title`, `pic_url`, `pic_url_mobile`, `pic_url_ad`, `cartype`,`category_id`,`sitename`, `website`,`status`,`sequence`  FROM " . DBPREFIX . "web_carousel WHERE `status`=1  AND `is_show`=1  AND `sitename`='" .TPL_FILE_NAME. "' order by sequence ASC limit 0,100";
    $result = mysqli_query($dbLink, $sql);
    $carouseData=array();
    while ($row = mysqli_fetch_assoc($result)){
        $carouseData[]=$row;
    }
}

switch ($action){
     //支持的站点展示 1:新版；2：旧版；3：手机版；4：广告版
    case 'pc':  //新版
        if (count($carouseData)>0){
            foreach ($carouseData as $k => $v){
                $aWebsite = explode(',', $v['website']);
                if(in_array(1, $aWebsite) && $v['sitename'] == $sitename) {
                    $data[$k]['id'] = $v['id'];
                    $data[$k]['title'] = $v['title'];
                    $data[$k]['name'] = $v['cartype'];
                    $data[$k]['img_path'] = !empty($v['pic_url']) ? PROMOS_PIC_DOMAIN . $v['pic_url'] : '';
                    //$data[$k]['category_id'] = $v['category_id'];
                }
            }
        }

        $data = array_values($data);

        $status = '200';
        $describe = 'success';
        original_phone_request_response($status,$describe,$data);
        break;
    case 'mobile':
        if (count($carouseData)>0){
            foreach ($carouseData as $k => $v){
                $aWebsite = explode(',', $v['website']);
                if(in_array(3, $aWebsite) && $v['sitename'] == $sitename) {
                    $data[$k]['id'] = $v['id'];
                    $data[$k]['title'] = $v['title'];
                    $data[$k]['name'] = $v['cartype'];
                    $data[$k]['img_path'] = !empty($v['pic_url_mobile']) ? PROMOS_PIC_DOMAIN . $v['pic_url_mobile'] : '';
                }
            }
        }
        $data = array_values($data);

        $status = '200';
        $describe = 'success';
        original_phone_request_response($status,$describe,$data);

        break;
    case 'ad':
        if (count($carouseData)>0){
            foreach ($carouseData as $k => $v){
                $aWebsite = explode(',', $v['website']);
                if(in_array(4, $aWebsite) && $v['sitename'] == $sitename) { // 1:新版；2：旧版；3：手机版；4：广告版
                    $data[$k]['id'] = $v['id'];
                    $data[$k]['title'] = $v['title'];
                    $data[$k]['name'] = $v['cartype'];
                    $data[$k]['img_path'] = !empty($v['pic_url_ad']) ? PROMOS_PIC_DOMAIN . $v['pic_url_ad'] : '';
                }
            }
        }
        $data = array_values($data);

        $status = '200';
        $describe = 'success';
        original_phone_request_response($status,$describe,$data);

        break;
    default:
        $status = '500';
        $describe = '参数错误';
        original_phone_request_response($status,$describe);
        break;
}
