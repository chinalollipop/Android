<?php
/*
 * 新闻资讯接口
 *
 *  1、首页缩略图 action=thumb
 *  2、太阳城分彩分页 10条/页  action=list&page=0
 *  3、内容页 action=content&id=1
 * */

session_start();
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");

$action = $_REQUEST['action'];

switch ($action){
    case 'thumb':
        $sql = "SELECT id,title,subtitle,cover FROM " . DBPREFIX . "web_article WHERE `status`=1 and `is_hot`=1 order by ID DESC limit 0,100";
        $result = mysqli_query($dbLink, $sql);
        $data=array();
        while ($row = mysqli_fetch_assoc($result)){
            $data[]=$row;
        }

        if (count($data)>0){
            foreach ($data as $k => $v){
                $data[$k]['cover'] = PROMOS_PIC_DOMAIN.$v['cover'];
            }
        }

        $status = '200';
        $describe = '拉取成功';
        original_phone_request_response($status,$describe,$data);
        break;
    case 'list':
        $page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 0;

        $sql = "SELECT id,title,subtitle,cover FROM " . DBPREFIX . "web_article WHERE `status`=1 order by ID DESC";
        $result = mysqli_query($dbLink, $sql);
        $count = mysqli_num_rows($result);

        // 分页
        $page_size = 12;
        $page_count = ceil($count / $page_size);
        $offset = $page * $page_size;
        $sql = $sql . "  limit $offset, $page_size";
        $result = mysqli_query($dbLink, $sql);

        $data=array();
        while ($row = mysqli_fetch_assoc($result)){
            $data['list'][]=$row;
        }

        $data['current_page'] = $page;
        $data['page_count'] = $page_count;
        $data['page_size'] = $page_size;
        $data['count'] = $count;

        if (count($data['list'])>0){
            foreach ($data['list'] as $k => $v){
                $data['list'][$k]['cover'] = PROMOS_PIC_DOMAIN.$v['cover'];
            }
        }

        $status = '200';
        $describe = '拉取成功';
        original_phone_request_response($status,$describe,$data);

        break;
    case 'content':

        $id = $_REQUEST['id'];
        $sql = "SELECT id,title,subtitle,content FROM " . DBPREFIX . "web_article WHERE id='{$id}'";
        $result = mysqli_query($dbLink, $sql);
        $row = mysqli_fetch_assoc($result);

        $row['content'] = str_replace('/uploads/image',PROMOS_PIC_DOMAIN.'/image',$row['content']);

        $status = '200';
        $describe = '拉取成功';
        original_phone_request_response($status,$describe,$row);

        break;
    default:
        $status = '500';
        $describe = '参数错误';
        original_phone_request_response($status,$describe);
        break;
}
