<?php
/**
 * 意见投诉接口
 */
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");

$user_id = $_SESSION['userid'];
if(!$user_id or $_SESSION['test_flag']==1) {
    $status='400.03';
    $describe="请先登录真实账号，再提交投诉建议!";
    original_phone_request_response($status,$describe);
}

// 意见投诉提交防止重复点击
$redisObj = new Ciredis();
$attTime = $redisObj->getSimpleOne('opiniton_complaint_'.$user_id);
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<600) { // 10分钟内不允许提交多次
        $status='400.07';
        $describe="10分钟内不允许多次提交";
        original_phone_request_response($status,$describe);
    }
}
// 插入当前申请时间，存入redis, 确保不允许重复申请
$redisObj->insert('opiniton_complaint_'.$user_id, time(), 10*60);

$aCategory = array(
    'tiyu'=>'体育赛事',
    'live'=>'视讯直播',
    'dianzi'=>'电子游艺',
    'caipiao'=>'彩票游戏',
    'qipai'=>'棋牌游戏',
    'buyu'=>'捕鱼',
    'dianjing'=>'电子竞技',
    'youhui'=>'优惠活动',
    'appdown'=>'APP下载',
    'jianyi'=>'建议/投诉',
);
if (!array_key_exists($_REQUEST['category'],$aCategory)){
    $status='400.04';
    $describe="分类错误，请选择分类，重新提交";
    original_phone_request_response($status,$describe);
}

if (mb_strlen($_REQUEST['content'], "utf-8")>500){
    $status='400.05';
    $describe="投诉内容不能超过500字！";
    original_phone_request_response($status,$describe);
}

$phone_email=$_REQUEST['phone_email']; //联系方式（手机号、邮箱地址、微信、QQ）
if(!isPhone($phone_email)){
    if (!isEmail($phone_email)){
        if (!isWechat($phone_email)){
            if (!isQqNumber($phone_email)){
                $status='400.02';
                $describe="联系方式不符合规范（仅限手机号、邮箱地址、微信、QQ）";
                original_phone_request_response($status,$describe);
            }
        }
    }
}

$sql="insert into ".DBPREFIX."web_opinion_complaint set ";
$sql.="username='".$_SESSION['UserName']."',";
$sql.="phone_email='".$phone_email."',";
$sql.="category='".$_REQUEST['category']."',";
$sql.="content='".$_REQUEST['content']."',";
$sql.="createtime='".date('Y-m-d H:i:s')."'";

//echo $sql; die;

if(mysqli_query($dbMasterLink,$sql)) {
    $status='200';
    $describe="投诉建议提交成功";
    original_phone_request_response($status,$describe);
}else {
    $status='400.06';
    $describe="操作失败!!!";
    original_phone_request_response($status,$describe);
}
