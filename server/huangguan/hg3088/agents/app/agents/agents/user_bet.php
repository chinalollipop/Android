<?php
/**
 * 查询会员打码量
 * 包括提款打码量、已打码量、已打码量详情
 * Date: 2019/12/23
 */
include('../include/config.inc.php');
require_once ROOT_DIR.'/common/count/function.php';

$username = isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : '';
$type = isset($_REQUEST['type']) && $_REQUEST['type'] ? trim($_REQUEST['type']) : '';

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `test_flag`, `owe_bet`, `owe_bet_time`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM " . DBPREFIX.MEMBERTABLE." where `UserName` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
if(!$stmt->affected_rows) {
    exit(json_encode( ['code' => '422', 'message' => '此会员不存在，请确认后查询！'] ) );
}
$aUser = $stmt->get_result()->fetch_assoc();

// 统计会员已打码量
$userId = $aUser['ID'];
if(!empty($type)) {  //根据时间查询，默认上月
    $last= strtotime("-1 month", time());
    $countTime['dateStart'] = date('Y-m-01 00:00:00', $last);   //上月第一天
    $countTime['dateEnd'] = date("Y-m-t 23:59:59", $last);      //上月最后一天

    $countData = countBetMonth($countTime, $userId);
} else {
    $countTime = (empty($aUser['owe_bet_time']) || $aUser['owe_bet_time'] == '0000-00-00 00:00:00' ? '1969-12-31 20:00:00' : $aUser['owe_bet_time']); // 开始统计时间
    $countData = countBet($countTime, $userId);
}
$resultData = [
    'owe_bet' => $aUser['owe_bet'],
    'total_bet' => $countData['total'],
    'bet_list' => [],
];

// 根据时间查询,返回搜索时间
if(!empty($type)) {
    $resultData['dateStart'] = $countTime['dateStart'];
    $resultData['dateEnd'] = $countTime['dateEnd'];
}

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
