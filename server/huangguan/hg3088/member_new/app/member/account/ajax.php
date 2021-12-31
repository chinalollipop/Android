<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");

$uid=$_REQUEST['uid'];
$langx = $_SESSION['langx'];
$userid = $_SESSION['userid'];
$userName = $_SESSION['UserName'];
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

$sendMailKey = $_SESSION['userid'].'_SENDMAIL';
/*var_dump($sendMailKey);
echo '<br/>';*/

$sendMailTotalKey = 'USER_SENDMAIL_TOTAL';
$redisObj = new Ciredis();
$lastSendTime = $redisObj->getSimpleOne($sendMailKey);

    //发邮件
    if(isset($_REQUEST['type']) && $_REQUEST['type']=='send'){
        if( time() - $lastSendTime > 100 ){
            $data['code'] = ''; $data['message'] = '';
            $msgType = $_REQUEST['msgType'];
            if( !in_array($msgType,array(1,2,3,4,5)) ){
                $data['code'] = -1; $data['message'] = '参数非法！';
            }else{
                $msgTitle = $_REQUEST['msgTitle'];
                preg_match_all('/[0-9\x{4e00}-\x{9fff}]+/u', $msgTitle, $matches_title);//只保留汉字和数字和英文字符
                $msgTitle = join(' ', $matches_title[0]);

                if( strlen($msgTitle) < 1 || strlen($msgTitle) > 150){
                    if( strlen($msgTitle) < 1 ){ $data['code'] = -4;$data['message'] = '邮件标题存在非法字符！'; }
                    if( strlen($msgTitle) > 150 ){ $data['code'] = -4;$data['message'] = '邮件标题太长，请概述！'; }
                }else{
                    $msgContent = $_REQUEST['msgContent'];
                    preg_match_all('/[0-9\x{4e00}-\x{9fff}]+/u', $msgContent, $matches_content);
                    $msgContent = join(' ', $matches_content[0]);
                    if( strlen($msgContent) <1 || strlen($msgContent) >2000 ){
                        if( strlen($msgContent) < 1 ){ $data['code'] = -5;$data['message'] = '邮件正文存在非法字符！'; }
                        if( strlen($msgContent) > 2000 ){ $data['code'] = -5;$data['message'] = '邮件正文太长，很简单描述！'; }
                    }else{
                        $time = date('Y-m-d H:i:s',time());
                        $res = mysqli_query($dbMasterLink, "insert into `".DBPREFIX."web_sendmail_data` set `userid` = {$userid},username='{$userName}',type={$msgType},title='{$msgTitle}',message='{$msgContent}',`time`='{$time}'");
                        //更新管理员未读消息数
                        if($res){
                            $userSendmailTotal = $redisObj->getSimpleOne($sendMailTotalKey);
                            if($userSendmailTotal>0){
                                $redisObj->setOne($sendMailTotalKey,$userSendmailTotal+1);
                            }else{
                                $redisObj->setOne($sendMailTotalKey,1);
                            }
                            $redisObj->setOne($sendMailKey,time());
                            $data['code'] = 200; $data['message'] = '已收到您的消息，我们会尽快回复。谢谢！';
                        }else{
                            $data['code'] = -2; $data['message'] = '发送失败，请稍后再试！';
                        }
                    }
                }
            }
        }else{
            $data['code'] = -3; $data['message'] = '禁止频繁发送！';
        }

        $dataJson = json_encode($data);
        echo $data['message'];

    }

    //发件箱分页处理
    if(isset($_REQUEST['type']) && $_REQUEST['type']=='sendMailsPage'){
        $sendmails = $sendmailArr = [];
        $pageSize = isset($_REQUEST['pageNum']) && $_REQUEST['pageNum']>0 ? $_REQUEST['pageNum']:8;
        $pageNumS = isset($_REQUEST['pageS']) && $_REQUEST['pageS']>0 ? $_REQUEST['pageS']:1;
        $sqlSendMails = "select id,userid,username,title,message,time,type from ".DBPREFIX."web_sendmail_data where Userid={$_SESSION['userid']}  order by id desc limit ".(($pageNumS - 1) * $pageSize) . "," . $pageSize;
        $resultSendMails = mysqli_query($dbLink,$sqlSendMails);
        while ($rowSend = mysqli_fetch_assoc($resultSendMails)) {
            $checkReplyResult = $checkReplyRow = '';
            $checkReplyResult = mysqli_query($dbLink,"select isAdmin from ".DBPREFIX."web_sendmail_reply_data where topid=".$rowSend['id']." ORDER BY `time` DESC LIMIT 1");
            $checkReplyRow = mysqli_fetch_assoc($checkReplyResult);
            $rowSend['isAdmin'] = isset($checkReplyRow['isAdmin'])? $checkReplyRow['isAdmin']:0;
            $sendmails[] = $rowSend;
        }

        $dataJson = json_encode($sendmails);
        echo $dataJson;

    }

    //读取收信箱计数 未启用
    if(isset($_REQUEST['type']) && $_REQUEST['type']=='readinbox') {
        $id = $_REQUEST['bh'];

        $datetimeCur = date('Y-m-d H:i:s',time());
        $sqlMessageCur = "select id from ".DBPREFIX."web_message_data where ( UserName='".$userName."' or UserName='ALL' ) and BeginTime < '$datetimeCur'  and EndTime > '$datetimeCur' ";
        $resultMessageCur = mysqli_query($dbLink,$sqlMessageCur);
        $numMessageCur = mysqli_num_rows($resultMessageCur);

        $readCountkey = 'Message_readinbox_'.$userid;
        $userSendmailTotal = '';
        $userSendmailTotal = $redisObj->getSimpleOne($readCountkey);
        if( strlen($userSendmailTotal)>0 ){
            $userMailReadArr = explode(':',$userSendmailTotal);
        }
        if(!in_array($id,$userMailReadArr)){
            $redisObj->setOne($readCountkey, $userSendmailTotal .$id.':');
            if($numMessageCur>1){
                $numMessageCur = $numMessageCur-1;
            }
        }
        while ($rowUnRead = mysqli_fetch_assoc($resultMessageCur)) {
            if(in_array($rowUnRead['id'],$userMailReadArr)){ $numMessageCur = $numMessageCur-1; }
        }

        $aData=array('unReadCount'=>$numMessageCur);
        $status = '200';
        $describe = 'succeed';
        original_phone_request_response($status,$describe,$aData);
    }

    //获取发件箱内容及回复信息
    if(isset($_REQUEST['type']) && $_REQUEST['type']=='opensendmail') {
        $messages = [];
        $topId=$_REQUEST['bh'];
        $sql = "select id,username,title,message,`time`,`type` from ".DBPREFIX."web_sendmail_data where id=".$topId;
        $result = mysqli_query($dbLink,$sql);
        $rowTop = mysqli_fetch_assoc($result);
        $detailSql = "select id,topid,lastid,userid,username,message,time,isAdmin from ".DBPREFIX."web_sendmail_reply_data where topid=".$topId." order by time desc";
        $detailResult = mysqli_query($dbLink,$detailSql);
        while ($detailsRow = mysqli_fetch_assoc($detailResult)){
            $messages[] = $detailsRow;
        }

        $rowTop['isAdmin'] = 0;
        $messages[] = $rowTop;

        /*echo '<pre>';
        print_r($messages);
        echo '<br/>';*/

        $messagesJson = json_encode($messages);
        echo $messagesJson;

    }

    //回复管理员信息
    if(isset($_REQUEST['type']) && $_REQUEST['type']=='replaysendmail') {
        if( time() - $lastSendTime > 100 ){
            $data['code'] = ''; $data['message'] = '';
            $msgContent = $_POST['text'];
            preg_match_all('/[0-9\x{4e00}-\x{9fff}]+/u', $msgContent, $matches_content);
            $msgContent = join(' ', $matches_content[0]);
            if( strlen($msgContent) <1 || strlen($msgContent) >2000 ){
                if( strlen($msgContent) < 1 ){ $data['code'] = -5;$data['message'] = '邮件正文存在非法字符！'; }
                if( strlen($msgContent) > 2000 ){ $data['code'] = -5;$data['message'] = '邮件正文太长，很简单描述！'; }
            }else{
                $replyId = $_POST['id'];
                $topId = $_POST['tid'];
                if( is_numeric($replyId) && is_numeric($topId) && $replyId>0 && $topId>0){
                    $checkSql = "select id from ".DBPREFIX."web_sendmail_reply_data where topid=".$topId." and lastid=".$replyId;
                    $checkResult = mysqli_query($dbLink,$checkSql);
                    $checkRow = mysqli_fetch_assoc($checkResult);
                    if( mysqli_num_rows($checkResult)==1 && is_numeric['id'] && $checkRow['id']>0 ){
                        $lastId = $checkRow['id'];
                        $mysqlRelay = "insert into ".DBPREFIX."web_sendmail_reply_data(topid,lastid,userid,username,message,time,isAdmin) values ($topId,$lastId,0,'$userName','$msgContent','".date('Y-m-d H:i:s',time())."',0)";
                        if(mysqli_query($dbMasterLink,$mysqlRelay)){
                            $data['code'] = 200; $data['message'] = '很高兴再次收到您的消息，我们会尽快回复。谢谢！';
                        }else{
                            $data['code'] = -4; $data['message'] = '发送失败，请稍后再试！';
                        }
                    }else{
                        $data['code'] = -2; $data['message'] = '缺少待回复邮件！';
                    }
                }else{
                    $data['code'] = -1; $data['message'] = '参数非法！';
                }
            }
        }else{
            $data['code'] = -3; $data['message'] = '邮件发送频繁，请稍后再试！';
        }

        $messagesJson = json_encode($data);
        echo $messagesJson;

    }





?>