<?php
error_reporting(1);
ini_set('display_errors','On');
/**
 *
 * 3月1日以后注册的
首存限制100起 会员可以转动幸运大转盘一次，
二存后就不允许转动
如会员首存摇奖摇到【二存赠送彩金】，会员需进行二存金额不能低于500，（如会员抽中二存赠送彩金，会有一个审核页面，等会员二存我这边在手动审核就可以派发）
手机号，IP，姓名，账号，只能申请一次，有其中相同的都不能申请
 *
 */
include_once("../include/address.mem.php");
require ("../include/config.inc.php");
//载入 app_config 文件
include_once('../../../../common/submail/app_config.php');
//载入 SUBMAILAutoload 文件
include_once('../../../../common/submail/SUBMAILAutoload.php');

// 接收参数
$mobile = trim($_REQUEST['mem_phone']);
$userid = $_SESSION['userid']?$_SESSION['userid']:$_REQUEST['user_id'];
$mem_yzm = mysqli_real_escape_string($dbLink, $_REQUEST['mem_yzm']); // 验证码
$action = $_REQUEST['action'];
// 总几率100%，根据金额还有几率，生成100个
// 随机在100个值中抽取1个，给会员进行派发
$sql = "select *  from ".DBPREFIX."best_lucky_config";
$res = mysqli_query($dbLink,$sql);
$rows = array();
while ($row = mysqli_fetch_assoc($res)){
    $rows[$row['id']]=$row;
}

$rowsForJack = array_values($rows);
foreach ($rowsForJack as $k => $v){
    $rowsForJack2[$k]['id'] = $v['id'];
    $rowsForJack2[$k]['best_lucky_content'] = $v['best_lucky_content'];
}

if( !isset($userid) || $userid == "" ) {
    $status = '501.1';
    $describe = '请先登录，再转动抽奖';
    original_phone_request_response($status,$describe,$rowsForJack2);
}

// 查询会员表手机号是否存在
$member_sql = "select ID,UserName,Alias,Agents,Phone,AddDate from ".DBPREFIX.MEMBERTABLE." where ID='$userid'";
$member_query = mysqli_query($dbLink,$member_sql);
$memberinfo = mysqli_fetch_assoc($member_query);
if (!$memberinfo['ID']){
    $status = '401.5';
    $describe = '账户异常！';
    original_phone_request_response($status,$describe);
}

$registerDate='2020-03-01';
// 检查会员注册时间：注册在2020年3月1日前的不允许转动
if ($memberinfo['AddDate'] < $registerDate ){
    $status = '401.3';
    $describe = '注册时间错误，仅限2020年3月1日后注册的会员转动幸运大转盘';
    original_phone_request_response($status,$describe,$rowsForJack2);
}

// 存款（不包括优惠、返水、彩金）
$sqlDeposit = 'SELECT userid, moneyf, currency_after, `Type` FROM ' . DBPREFIX . 'web_sys800_data WHERE userid="'.$userid.'" AND addDate >= "'.$registerDate.'" 
    AND `Type` ="S" AND Checked = 1 AND `discounType` NOT IN (3, 4) AND `Payway` NOT IN ("O", "G")';
$resultDeposit = mysqli_query($dbLink, $sqlDeposit);
$countDeposit = [];
while ($rowDeposit = mysqli_fetch_assoc($resultDeposit)) {
    $rowDeposit['money'] = $rowDeposit['currency_after'] - $rowDeposit['moneyf'];
    $countDeposit[] = $rowDeposit;
}
if (count($countDeposit) == 0){
    $status = '401.4';
    $describe = '转动幸运大转盘，必须存款1次';
    original_phone_request_response($status,$describe,$rowsForJack2);
}
elseif(count($countDeposit) == 1){

    if ($countDeposit[0]['money']<100){
        $status = '401.5';
        $describe = '幸运大转盘首存不能低于100元';
        original_phone_request_response($status,$describe,$rowsForJack2);
    }
    else{

        switch ($action){
            case 'check';
                $status = '200';
                $describe = '符合抽奖条件';
                original_phone_request_response($status,$describe,$rowsForJack2);
                break;
            case 'draw'; break;
            default:
                $status = '500';
                $describe = 'action参数异常';
                original_phone_request_response($status,$describe,$rowsForJack2);
                break;
        }

        // 手机号，IP，姓名，账号，只能申请一次，有其中相同的都不能申请
        $ip_addr = get_ip();
        $sql = "select count(1) as cou from ".DBPREFIX."best_lucky where (userid = $userid) or (Phone='{$mobile}') or (Alias='{$memberinfo['Alias']}') or (applyIP='{$ip_addr}') ";
        $res =  mysqli_query($dbLink, $sql);
        $row = mysqli_fetch_assoc($res);
        if ($row['cou'] > 0){
            $status = '401.4';
            $describe = '手机号、IP、姓名或账号 相同的，只能申请一次';
            original_phone_request_response($status,$describe);
        }

        $redisObj = new Ciredis();
        $userMobileKey = $userid . '_' . $mobile . '_code';  // redis  key
        $memCode = $redisObj->getSimpleOne($userMobileKey);
        if(($mem_yzm != $memCode) || ($mem_yzm=='')) {
            original_phone_request_response('4041','短信验证码校验失败！');
        }
        if(!isset($userid) || $userid=="") {
            $describe = '参数异常userid';
            original_phone_request_response('4001',$describe);
        }
        if(!isPhone($mobile)){
            original_phone_request_response('4002','请输入有效的手机号!');
        }
//        if ($memberinfo['Phone']!=$mobile){
//            $status = '400.66';
//            $describe = '账号【'.$memberinfo['UserName'].'】绑定的手机号码不匹配';
//            original_phone_request_response($status,$describe);
//        }

        // 单日发送不得超过10次
        $checkSendNum = checkUserSms($userid);
        if($checkSendNum && $checkSendNum['usercount'] > 9) {
            original_phone_request_response('4007','当日不可频繁发送!');
        }


        $red_envelope_pool = array(); // 红包池，将下面生成的红包金额放入，总共100个
        // 红包池只能存放为概率的ID，
        // 然后随机抽取，
        // 最后将抽到的id匹配奖品内容，
        // 计算出红包金额
        foreach ($rows as $k => $v){
            $red_envelope_nums = $v['probability']*100;
            $keys = array_keys($red_envelope_pool);
            $last_key = max($keys); // 红包池最后一位key
            $tmp_arr = array_fill($last_key+1, $red_envelope_nums, $v['id']);
            $red_envelope_pool = array_merge($red_envelope_pool , $tmp_arr);
        }
        $rand = rand(0,99); // 0到99随机生成1个数字
        $id = $red_envelope_pool[$rand]; //要派发给会员的幸运红包金额
        switch ($id){
            case 1:
                $giftGold = 0;
                break;
            case 2:
                $giftGold = $countDeposit[0]['money']*0.1;
                break;
            case 3:
                $giftGold = $countDeposit[0]['money']*0.2;
                break;
            case 4:
            case 6:
                $giftGold = 86;
                break;
            case 5:
            case 7:
                $giftGold = 186;
                break;
        }
        $bestLuckyContent = $rows[$id]['best_lucky_content'];

        // 入库存款彩金表，等待审核
        $now = date('Y-m-d H:i:s');
        $insertData = [
            'userid' => $userid,
            'username' => $memberinfo['UserName'],
            'Alias' => $memberinfo['Alias'],
            'Agents' => $memberinfo['Agents'],
            'Phone' => $mobile,
            'EventName' => '幸运大转盘',
            'applyIP' => $ip_addr,
            'registered_at' => $memberinfo['AddDate'],
            'best_lucky_content' => $bestLuckyContent,
            'gift_gold' => $giftGold,
            'status' => 2, // 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
            'created_at' => $now,
            'updated_at' => $now,
        ];
        foreach($insertData as $key => $val){
            $tmp[] = $key.'=\''.$val.'\'';
        }
        $sql = "INSERT INTO `" . DBPREFIX . "best_lucky` SET " . implode(',', $tmp);
        if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
            $status = '500.6';
            $describe = '系统繁忙，请稍后再试吧!';
            original_phone_request_response($status,$describe);
        }else{
            $status = '200';
            $describe = '领取成功，请联系24小时客服进行审核';
            original_phone_request_response($status,$describe,['gift_gold'=>$giftGold,'best_lucky_content'=>$bestLuckyContent]);
        }
        exit;
    }
}
else{
    $status = '401.6';
    $describe = '存款次数错误，只有存款1次才可转动幸运大转盘';
    original_phone_request_response($status,$describe);
}

/**
 * 检测当日用户发送消息次数
 * @param $userid
 * @return array $check
 */
function checkUserSms($userid) {
    global $dbLink;
    $start_day = date("Y-m-d 00:00:00");
    $end_day = date("Y-m-d 23:59:59");
    $sql = "select COUNT(`userid`) AS usercount from " . DBPREFIX . "web_member_sms where userid='$userid' AND `created_at` BETWEEN '{$start_day}' AND '{$end_day}'";
    $result = mysqli_query($dbLink, $sql);
    $myrow = mysqli_fetch_assoc($result);
    return $myrow;
}

