<?php
session_start();
/**
 * 会员公告
 *
 */

require ("../include/config.inc.php");

$redisObj = new Ciredis();
$username = $_SESSION['UserName'];
$userid = $_SESSION['userid'];
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'' ;
$emailMount = isset($_REQUEST['emailMount'])?$_REQUEST['emailMount']:'' ; // 只获取短信 数量
$uid = $_SESSION['Oid'] ;
$data = array() ;
if( !isset($uid) || $uid == "" ) {
    $status = '502';
    $describe = '你已退出登录，请重新登录';
    original_phone_request_response($status,$describe,$data);
}
if(!$action){
    $status = '503';
    $describe = '参数异常';
    original_phone_request_response($status,$describe,$data);
}
if($action == 'notice'){ // 会员公告

    $iCount = isset($_REQUEST['carousel']) && $_REQUEST['carousel'] == 1 ? 3 : 10;
    $sql = 'SELECT `Time`, `Message` FROM ' . DBPREFIX . 'web_marquee_data ORDER BY `ID` DESC LIMIT ' .  $iCount;
    $oResult = mysqli_query($dbLink, $sql);

    while ($aRow = mysqli_fetch_assoc($oResult)){
        $data[] = [
            'notice' => $aRow['Message'],
            'created_time' => $aRow['Time'],
            'type' => 'notice'
        ];
    }

}else{ // 短信
//    $keyarry = array(0,1,2) ;
//    $keyarry = array(0) ;
//    foreach ($keyarry as $key){ // 0 系统短信 (财务公告) ,1 代表存款公告,2 代表取款公告
//        $data[] = [
//            'notice' => isset(getMemberMessage($username,$key)['mem_message'])?getMemberMessage($username,$key)['mem_message']:'',
//            'created_time' => getMemberMessage($username,$key)['addtime'],
//            'type' => $key
//        ];
//    }

    $msql = "select Message,Time from ".DBPREFIX."web_message_data where UserName='$username' AND MsType='0' order by Time desc LIMIT 5"; // 查询单个会员
    // echo $msql;
    $mresult = mysqli_query($dbLink,$msql);
    $mcou = mysqli_num_rows($mresult);
    if($mcou ==0){ // 单个会员没有短信，读取全部的
        $allsql = "select Message,Time from ".DBPREFIX."web_message_data where type='1' AND MsType='0' order by Time desc LIMIT 5";
        $mresult = mysqli_query($dbLink,$allsql);
        $mcou = mysqli_num_rows($mresult);
    }

    $readstatus = $redisObj->getSimpleOne($username.'_userEmail'); // 获取该会员消息读取状态

    if(!$readstatus || $readstatus=='readed'){
        $memsql = "SELECT message_status FROM `".DBPREFIX.MEMBERTABLE."` WHERE ID='$userid' AND Oid='$uid' ";
        $memresult = mysqli_query($dbLink,$memsql);
        $memrow = mysqli_fetch_assoc($memresult);
        $readstatus = $memrow['message_status'];
    }

    if($emailMount =='yes'){ // 只获取会员系统消息数量
        if($readstatus && $readstatus=='readed'){ // 已读取消息 readed notread
            $data['emailMount'] = 0 ;
        }else{ // 未读取消息
            $data['emailMount'] = $mcou ;
        }
        original_phone_request_response(200, 'success', $data);
    }

    // 设置已读取消息
    $redisRes = $redisObj->setOne($username.'_userEmail','readed'); // 该会员已读取消息
    if($readstatus =='notread' || $readstatus==''){ // 更新已读 首次为空
        $memmysql="update ".DBPREFIX.MEMBERTABLE." set message_status='readed' WHERE ID='$userid' AND Oid='$uid' "; // 更新所有会员信息读取状态
        mysqli_query($dbMasterLink,$memmysql);
    }

    // 返回会员系统消息
    while ($mrow = mysqli_fetch_assoc($mresult)){
        $data[] = [
            'notice' => $mrow['Message'],
            'created_time' => $mrow['Time'],
            'type' => 0
        ];
    }


}

original_phone_request_response(200, 'success', $data);


