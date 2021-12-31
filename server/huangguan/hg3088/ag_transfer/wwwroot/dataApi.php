<?php
/**
 * 响应各个平台AG数据的请求
 * 每隔4分钟抓取一次数据（减去5分钟，获取前面5分钟的注单）
 *
 */

error_reporting(1);
ini_set('display_errors','On');
require("../common/config.php");

$action = $_REQUEST['action'];
$aCondition['platform'] = $_REQUEST['platform'];
$time = time();
if (isset($_REQUEST['startdate']) && isset($_REQUEST['enddate'])) {
    $aCondition['startdate'] = $_REQUEST['startdate'];
    $aCondition['enddate'] = $_REQUEST['enddate'];
}
else{
    $aCondition['startdate'] = date('Y-m-d H:i:s', $time - 600);
    $aCondition['enddate'] = date('Y-m-d H:i:s', $time - 300);
}

switch ($action){
    case 'get_projects': // 捞取真人注单
        if ( strpos($aCondition['platform'], 'BT5') !== FALSE){
            $aData = getProjects();
            $sData = json_encode($aData,JSON_UNESCAPED_UNICODE);
            exit($sData);
        }
        else{
            exit('platform参数错误');
        }
        break;
    case 'get_dz_projects': // 捞取电子注单

        if ( strpos($aCondition['platform'], 'BT5') !== FALSE){
            $aData = getDzProjects();
            $sData = json_encode($aData,JSON_UNESCAPED_UNICODE);
            exit($sData);
        }
        else{
            exit('platform参数错误');
        }
        break;
    case 'get_buyu_projects': // 捞取捕鱼注单

        if ( strpos($aCondition['platform'], 'BT5') !== FALSE){
            $aData = getBuyuProjects();
            $sData = json_encode($aData,JSON_UNESCAPED_UNICODE);
            exit($sData);
        }
        else{
            exit('platform参数错误');
        }
        break;
    case 'get_buyu_scene': // 捞取捕鱼场景数据

        if ( strpos($aCondition['platform'], 'BT5') !== FALSE){
            $aData = getBuyuScene();
            $sData = json_encode($aData,JSON_UNESCAPED_UNICODE);
            exit($sData);
        }
        else{
            exit('platform参数错误');
        }
    default:
        exit('参数错误');
        break;
}

/**
 * 捞取真人注单
 * @return mixed
 */
function getProjects(){
    global $dbLink, $aCondition;

    $sql = "select * from ".DBPREFIX."ag_projects where prefix = '{$aCondition['platform']}' and betTime BETWEEN '".$aCondition['startdate']."' and '".$aCondition['enddate']."'";
    $result = mysqli_query($dbLink, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
    $aData['total'] = count($data);
    $aData['rows'] = $data;

    return $aData;
}

/**
 * 捞取电子注单
 * @return mixed
 */
function getDzProjects(){
    global $dbLink, $aCondition;

    $sql = "select * from ".DBPREFIX."ag_dz_projects where prefix = '{$aCondition['platform']}' and billtime BETWEEN '".$aCondition['startdate']."' and '".$aCondition['enddate']."'";
    $result = mysqli_query($dbLink, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
    $aData['total'] = count($data);
    $aData['rows'] = $data;

    return $aData;
}

/**
 * 捞取捕鱼注单
 * @return mixed
 */
function getBuyuProjects(){
    global $dbLink, $aCondition;

    $startdate = date('Y-m-d H:i:s', $aCondition['startdate']);
    $enddate = date('Y-m-d H:i:s', $aCondition['enddate']);
    $sql = "select * from ".DBPREFIX."ag_buyu_projects where prefix = '{$aCondition['platform']}' and billtime BETWEEN '".$startdate."' and '".$enddate."'";
    $result = mysqli_query($dbLink, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)){
        $row['billtime'] = strtotime($row['billtime']);
        $data[] = $row;
    }
    $aData['total'] = count($data);
    $aData['rows'] = $data;

    return $aData;
}

/**
 * 捞取捕鱼场景数据
 * @return mixed
 */
function getBuyuScene(){
    global $dbLink, $aCondition;

    $startdate = date('Y-m-d H:i:s', $aCondition['startdate']);
    $enddate = date('Y-m-d H:i:s', $aCondition['enddate']);
    $sql = "select * from ".DBPREFIX."ag_buyu_scene where prefix = '{$aCondition['platform']}' and billtime BETWEEN '".$startdate."' and '".$enddate."'";
    $result = mysqli_query($dbLink, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)){
        $row['starttime'] = strtotime($row['starttime']);
        $row['endtime'] = strtotime($row['endtime']);
        $row['billtime'] = strtotime($row['billtime']);
        $data[] = $row;
    }
    $aData['total'] = count($data);
    $aData['rows'] = $data;

    return $aData;
}