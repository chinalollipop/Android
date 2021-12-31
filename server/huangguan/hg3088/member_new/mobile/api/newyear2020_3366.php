<?php
/*
 * 3366的2020新年活动——亿元红包豪礼
 * 1月24号-31号期间，只要存款并投注的会员即可参加抢红包活动，天天抢，天天送
 **/
error_reporting(1);
ini_set('display_errors','On');
session_start();
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activity.class.php");
include_once "../../../common/count/function.php";

$user_id = $_REQUEST['user_id'];
$username = $_REQUEST['username'];

if(!$user_id) {
    $status = '502.2';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}

$member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where ID='$user_id'";
$member_query = mysqli_query($dbLink,$member_sql);
$memberinfo = mysqli_fetch_assoc($member_query);
$sUserlayer = $memberinfo['layer'];
// 检查当前会员是否设置不准领取彩金分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
$layerId=4;
if ($sUserlayer==$layerId){
    $layer = getUserLayerById($layerId);
    if ($layer['status']==1) {
        $status = '502.66';
        $describe = '账号分层异常，请联系我们在线客服';
        original_phone_request_response($status,$describe);
    }
}

//统计会员当天存款总金额
$aTime['begin_time'] = date('Y-m-d 00:00:00');
$aTime['end_time'] = date("Y-m-d 23:59:59");
$activity= new Activity();
$todayDepositAmount = $activity->depositAmount($user_id,$aTime);

// 统计当天全部的有效码量
$countBetMember = countCurrentBetMember($aTime['begin_time'], $aTime['end_time'], $user_id);
//print_r($countBetMember);
$validBetCount = 0;
foreach ($countBetMember as $k => $v){
    $validBetCount+=$v;
}
//print_r($validBetCount); die;

// 获取次数
// 领取红包 返回剩余次数、领取到的红包金额
switch ($_REQUEST['action']){
    case 'getGrabTimes':
        $last_times = getGrabTimes();

        $data['lastTimes'] = $last_times;
        $status = '200';
        $describe = '';
        original_phone_request_response($status,$describe,$data);
        break;
    case 'receive_red_envelope':

        // 美东时间2020年1月24日，截止至2020年1月31日
        $newYearBeginTime= '2020-01-24 00:00:00';
        $newYearEndTime = '2020-01-26 23:59:59';
//        $newYearBeginTime= '2020-01-19 00:00:00';
//        $newYearEndTime = '2020-01-31 23:59:59';
        $curtime = date("Y-m-d H:i:s");
        if($curtime < $newYearBeginTime || $curtime > $newYearEndTime){
            $status = '401.2';
            $describe = '请于美东时间1月24号-1月26号领取红包!';
            original_phone_request_response($status,$describe);
        }

        if($todayDepositAmount < 500) {
            $status = '401.3';
            $describe = '今日总存款金额不符合最低存款要求，不允许申请!';
            original_phone_request_response($status,$describe);
        }

        if ($validBetCount < 500){
            $status = '401.4';
            $describe = '今日打码量不符合最低打码量要求，不允许申请!';
            original_phone_request_response($status,$describe);
        }

        $last_times = getGrabTimes();
        // 校验可领次数
        if ($last_times == 0){
            $status = '401.4';
            $describe = '可领次数不足不能领取';
            original_phone_request_response($status,$describe);
        }

        // ------------------------------------------------------------------------------------------抽取红包开始，初始化一些红包池数据Start
        // 总几率100%，根据金额还有几率，生成100个金额
        // 随机在100个值中抽取1个金额，给会员进行派发
        $sql = "select *  from ".DBPREFIX."newyear_2020_888w_config";
        $res = mysqli_query($dbLink,$sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($res)){
            $rows[]=$row;
        }

        $red_envelope_pool = array(); // 红包池，将下面生成的红包金额放入，总共100个
        foreach ($rows as $k => $v){
            $red_envelope_nums = $v['probability']*100;
            $keys = array_keys($red_envelope_pool);
            $last_key = max($keys); // 红包池最后一位key
            $tmp_arr = array_fill($last_key+1, $red_envelope_nums, $v['money']);
            $red_envelope_pool = array_merge($red_envelope_pool , $tmp_arr);
        }

        $rand = rand(0,99); // 0到99随机生成1个数字
        $giftGold = $red_envelope_pool[$rand]; //要派发给会员的幸运红包金额

        // 查询会员输赢  WinLossCredit  负数会员赢， 正数会员输
        $sql = "SELECT test_flag,DepositTimes,WinLossCredit,AddDate FROM `".DBPREFIX.MEMBERTABLE."` WHERE ID='$user_id' AND Status<2 ";
        $result = mysqli_query($dbLink,$sql);
        $row = mysqli_fetch_assoc($result);
        $cou = mysqli_num_rows($result);
        if ($cou==0){
            $status = '401.5';
            $describe = '账号异常!';
            original_phone_request_response($status,$describe);
        }

        // 入库存款彩金表，等待审核
        $now = date('Y-m-d H:i:s');
        $insertData = [
            'userid' => $user_id,
            'username' => trim($username),
            'EventName' => '3366的2020新年活动',
            'registered_at' => $row['AddDate'],
            'validBet' => $validBetCount,
            'depositMoney' => $todayDepositAmount,
            'gift_gold' => $giftGold,
            'status' => 2, // 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
            'created_at' => $now,
            'updated_at' => $now,
        ];
        foreach($insertData as $key => $val){
            $tmp[] = $key.'=\''.$val.'\'';
        }
        $sql = "INSERT INTO `" . DBPREFIX . "newyear_2020_3366` SET " . implode(',', $tmp);
        //echo $sql; die;
        if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
            $status = '500.6';
            $describe = '系统繁忙，请稍后再试吧!';
            original_phone_request_response($status,$describe);
        }else{
            $data['lastTimes'] = $last_times-1;
            $data['giftGold'] = $giftGold;
            $status = '200';
            $describe = '领取成功，请联系24小时客服进行审核';
            original_phone_request_response($status,$describe,$data);
        }

        break;
}


/**
 * 获取可领取红包次数
 * @return int
 */
function getGrabTimes(){
    global $dbLink, $todayDepositAmount, $validBetCount, $user_id;
    $todayDepositAmountTmp = $validBetCountTmp = min($todayDepositAmount, $validBetCount);
    
    $newyear2020_3366_times_level[0] = array('depost_amount'=>500, 'valid_bet'=>500, 'grab_red_envelope_times'=>2);
    $newyear2020_3366_times_level[1] = array('depost_amount'=>3000, 'valid_bet'=>3000, 'grab_red_envelope_times'=>3);
    $newyear2020_3366_times_level[2] = array('depost_amount'=>5000, 'valid_bet'=>5000, 'grab_red_envelope_times'=>5);
    $newyear2020_3366_times_level[3] = array('depost_amount'=>10000, 'valid_bet'=>10000, 'grab_red_envelope_times'=>6);
    $newyear2020_3366_times_level[4] = array('depost_amount'=>50000, 'valid_bet'=>50000, 'grab_red_envelope_times'=>10);
    $newyear2020_3366_times_level[5] = array('depost_amount'=>100000, 'valid_bet'=>100000, 'grab_red_envelope_times'=>15);

    // -------------------------------------------------------------------------------------------------------- 检查会员领取红包次数是否用尽Start
    $grab_red_envelope_times=0;
    foreach ($newyear2020_3366_times_level as $k => $v){
        if ($k<5){
            // 有效次数档位从第一档到第六档
            if ($todayDepositAmountTmp >= $v['depost_amount'] and $todayDepositAmountTmp < $newyear2020_3366_times_level[$k+1]['depost_amount']
                and $validBetCountTmp >= $v['valid_bet'] and $validBetCountTmp < $newyear2020_3366_times_level[$k+1]['valid_bet']
            ){
                $grab_red_envelope_times = $v['grab_red_envelope_times']; // 可领总次数
                break;
            }
        }else{
            // 有效次数档位最后一档
            if ($todayDepositAmountTmp >= $v['depost_amount'] and $validBetCountTmp >= $v['valid_bet']){
                $grab_red_envelope_times = $v['grab_red_envelope_times']; // 可领总次数
            }
        }
    }
    if($grab_red_envelope_times==0){ // 可领取次数为0
        return $grab_red_envelope_times;
    }
    // 获取可领取红包次数
    // 剩余次数 = 存款总金额对应的次数 - 今天已领取的次数

    // 捞取今天已领取的几次
    $startTime = date('Y-m-d 00:00:00');
    $endTime = date('Y-m-d 00:00:00',strtotime('+1 day'));
    $sql = "select count(1) as cou from ".DBPREFIX."newyear_2020_3366 where userid = $user_id and created_at between '$startTime' and '$endTime' ";
    $res =  mysqli_query($dbLink, $sql);
    $row = mysqli_fetch_assoc($res);
    $last_times = $grab_red_envelope_times - $row['cou']; // 会员剩余可领次数

    // ------------------------------------------------------------------------------------------------------- 检查会员领取红包次数是否用尽End

    return $last_times;
}