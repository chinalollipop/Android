<?php
/**
 * 查询会员打码量
 * 包括提款打码量、已打码量、已打码量详情
 * Date: 2019/12/23
 */
include('../include/config.inc.php');
require_once ROOT_DIR.'/common/count/function.php';

if(!isset($_SESSION['Oid']) || $_SESSION['Oid'] == ''){
    original_phone_request_response('401.1', '您的登录信息已过期，请您重新登录！');
}
$uid = $_SESSION['Oid'];

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `UserName`, `Money`, `test_flag`, `owe_bet`, `owe_bet_time`, `Agents`, `World`, `Corprator`, `Super`, `Admin` FROM " . DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
if(!$stmt->affected_rows) {
    original_phone_request_response('401.1', '您的登录信息已过期，请您重新登录！');
}
$aUser = $stmt->get_result()->fetch_assoc();

// 统计会员已打码量
$userId = $aUser['ID'];
$countTime = (empty($aUser['owe_bet_time']) || $aUser['owe_bet_time'] == '0000-00-00 00:00:00' ? '1969-12-31 20:00:00' : $aUser['owe_bet_time']); // 开始统计时间
$countData = countBet($countTime, $userId);
$resultData = [
    'owe_bet' => $aUser['owe_bet'],
    'total_bet' => $countData['total'],
    'bet_list' => [],
];
foreach ($countData as $key => $value){
    if($key != 'total'){
        $resultData['bet_list'][] = [
            'key' => $key,
            'value' => $value,
            'msg' => typeMsg($key),
        ];
    }
}
original_phone_request_response('200', '获取用户打码量成功！', $resultData);
