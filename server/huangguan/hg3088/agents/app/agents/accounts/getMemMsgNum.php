<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
require_once ("../../agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!!');top.location.href='/';</script>";
    exit;
}

$langx=$_SESSION["langx"];

require ("../../agents/include/traditional.$langx.inc.php");


$redisObj = new Ciredis();


// 获取会员未回复的消息条数
$type = $_REQUEST['type'] ;
if ($type =="mem_msg_num"){
    $result = mysqli_query($dbLink,"select id,username,title,message,`time`,`type` from ".DBPREFIX."web_sendmail_data");
    while ($row = mysqli_fetch_assoc($result)){
        $aDataWs[] = $row;
    }

    $result = mysqli_query($dbLink,"select isAdmin, topid from ".DBPREFIX."web_sendmail_reply_data");
    while ($row = mysqli_fetch_assoc($result)){
        $aDataWsr[] = $row;
    }
    $mem_msg_num = 0;
    foreach ($aDataWs as $k => $v){
        $id = $v['id'];
        $item = array_filter($aDataWsr, function($t) use ($id) { return $t['topid'] == $id; });
        $item = reset($item);
        if ($item['isAdmin']!=1){
            $mem_msg_num +=1;
        }
    }

    $redisObj->setOne('USER_SENDMAIL_TOTAL',$mem_msg_num) ;

    $data['mem_msg_num']=$mem_msg_num;
    $status = '200';
    $describe = '请求数据成功。';
    original_phone_request_response($status,$describe,$data);

}
