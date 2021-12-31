<?php
/*
 * 6668的2020新年活动
 * 限制23号之后注册的不能领取
没有存款次数的会员，8元
负盈利1万内，红包金额设置38.58.88.
负盈利1万至5万，红包金额设置58.88.128
负盈利5万至10万，红包金额设置128.158.188
负盈利10万至50万，红包金额设置188.288.388
负盈利50万至100万，红包金额设置388.588.888
负盈利100万至300万，红包金额设置888.1388.1588
负盈利300万至500万，红包金额设置1888.2888.3888
负盈利500万以上红包金额.3888.5888.8888

盈利5000内，红包金额设置18.38.58
盈利5000至1万，红包金额设置38.58.88
盈利1万至5万，红包金额设置58.88.128
盈利5万至10万，红包金额设置88.138.158
盈利10万至30万，红包金额设置138.158.188
盈利30万至50万，红包金额设置188.288.388
盈利50万以上红包金额288.388.588
 **/

session_start();
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activity.class.php");

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

// 检查今天是否已申请过，每天不允许重复申请
$hour = date('G');
// 美东时间转北京时间 来判断
if ($hour>=12){
    $curStartDate = date('Y-m-d', strtotime('+1 day')).' 00:00:00'; // 2020-01-25 00:00:00
    $curEndDate = date('Y-m-d', strtotime('+1 day')).' 23:59:59'; // 2020-01-25 23:59:59
}
else{
    $curStartDate = date('Y-m-d').' 00:00:00'; // 2020-01-24 00:00:00
    $curEndDate = date('Y-m-d').' 23:59:59'; // 2020-01-24 23:59:59
}
$check_att_sql = "select username from ".DBPREFIX."newyear_2020_6668 where userid='$user_id' and bj_created_at >'$curStartDate' and bj_created_at<='$curEndDate'";
//echo $check_att_sql;
$checkresult = mysqli_query($dbLink,$check_att_sql);
$todayData = mysqli_fetch_assoc($checkresult);
if($todayData){
    $status = '401.4';
    $describe = '您已领取今日红包，不允许重复申请哦!';
    original_phone_request_response($status,$describe);
}

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
if ($row['DepositTimes']<1){ // 没有存款次数

    // 北京时间2020年1月21日，截止至2020年1月28日
    $newYearBeginTime= '2020-01-21 00:00:00';
    $newYearEndTime = '2020-01-28 23:59:59';
    //$newYearBeginTime= '2020-01-13 00:00:00';
    //$newYearEndTime = '2020-01-28 23:59:59';
    $curtime = date("Y-m-d H:i:s",time()+12*60*60);

    if($curtime < $newYearBeginTime || $curtime > $newYearEndTime){
        $status = '401.2';
        $describe = '无存款次数请于北京时间1月21号-1月28号领取红包哦!';
        original_phone_request_response($status,$describe);
    }

    // 注册时间超过北京时间 21号23时59分59秒的拒绝参加红包活动
    if ($row['AddDate'] > '2020-01-21 11:59:59' ){
        $status = '401.3';
        $describe = '抱歉，您的账号注册时间超过1月21号，无法领取!';
        original_phone_request_response($status,$describe);
    }

    $giftGold = 8;
}
else{

    // 北京时间2020年1月24日，截止至2020年1月28日
    $newYearBeginTime= '2020-01-24 00:00:00';
    $newYearEndTime = '2020-01-28 23:59:59';
    //$newYearBeginTime= '2020-01-13 00:00:00';
    //$newYearEndTime = '2020-01-28 23:59:59';
    $curtime = date("Y-m-d H:i:s",time()+12*60*60);

    if($curtime < $newYearBeginTime || $curtime > $newYearEndTime){
        $status = '401.2';
        $describe = '请于北京时间1月24号-1月28号领取红包哦!';
        original_phone_request_response($status,$describe);
    }

    // 注册时间超过北京时间 23号23时59分59秒的拒绝参加红包活动
    if ($row['AddDate'] > '2020-01-23 11:59:59' ){
        $status = '401.3';
        $describe = '抱歉，您的账号注册时间超过1月23号，无法领取!';
        original_phone_request_response($status,$describe);
    }

    // 查询等级红包金额及几率
    $redisObj = new Ciredis();
    $probabilitydata = $redisObj->getSimpleOne('newyear_2020_6668_config');
    $probabilitydata = json_decode($probabilitydata,true) ;


    $giftGold = returnGiftMoney($row['WinLossCredit']);
}

// 入库存款彩金表，等待审核
$now = date('Y-m-d H:i:s');
$bj_now = date('Y-m-d H:i:s', time()+12*60*60);
$insertData = [
    'userid' => $user_id,
    'username' => trim($username),
    'EventName' => '6668的2020新年红包',
    'registered_at' => $row['AddDate'],
    'WinLossCredit' => $row['WinLossCredit'],
    'gift_gold' => $giftGold,
    'status' => 2, // 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
    'created_at' => $now,
    'bj_created_at' => $bj_now,
    'updated_at' => $now,
];
foreach($insertData as $key => $val){
    $tmp[] = $key.'=\''.$val.'\'';
}
$sql = "INSERT INTO `" . DBPREFIX . "newyear_2020_6668` SET " . implode(',', $tmp);
//echo $sql; die;
if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
    $status = '500.6';
    $describe = '系统繁忙，请稍后再试吧!';
    original_phone_request_response($status,$describe);
}else{
    $resdata = array('giftGold'=>$giftGold);
    $status = '200';
    $describe = '领取成功、系统自动派发。';
    original_phone_request_response($status,$describe,$resdata);
}

/*
 * 返回红包金额,
 * $WinLossCredit 输赢
 * */
function returnGiftMoney($WinLossCredit){
    global $redisObj,$probabilitydata;
    $levelData = array();

    $thound = 1000;
    if ($WinLossCredit>0){
        if ($WinLossCredit<$thound*10){
            $levelData = $probabilitydata[1];
        }
        elseif ($WinLossCredit>=$thound*10 && $WinLossCredit<$thound*50){
            $levelData = $probabilitydata[2];
        }
        elseif ($WinLossCredit>=$thound*50 && $WinLossCredit<$thound*100){
            $levelData = $probabilitydata[3];
        }
        elseif ($WinLossCredit>=$thound*100 && $WinLossCredit<$thound*500){
            $levelData = $probabilitydata[4];
        }
        elseif ($WinLossCredit>=$thound*500 && $WinLossCredit<$thound*1000){
            $levelData = $probabilitydata[5];
        }
        elseif ($WinLossCredit>=$thound*1000 && $WinLossCredit<$thound*3000){
            $levelData = $probabilitydata[6];
        }
        elseif ($WinLossCredit>=$thound*3000 && $WinLossCredit<$thound*5000){
            $levelData = $probabilitydata[7];
        }
        elseif ($WinLossCredit>=$thound*5000){
            $levelData = $probabilitydata[8];
        }
    }
    else{ // 负的会员赢
        if ($WinLossCredit<=0 && $WinLossCredit>(-$thound*5)){  // 0到 负的5000
            $levelData = $probabilitydata[9];
        }
        elseif ($WinLossCredit<=(-$thound*5) && $WinLossCredit>(-$thound*10)){
            $levelData = $probabilitydata[10];
        }
        elseif ($WinLossCredit<=(-$thound*10) && $WinLossCredit>(-$thound*50)){
            $levelData = $probabilitydata[11];
        }
        elseif ($WinLossCredit<=(-$thound*50) && $WinLossCredit>(-$thound*100)){
            $levelData = $probabilitydata[12];
        }
        elseif ($WinLossCredit<=(-$thound*100) && $WinLossCredit>(-$thound*300)){
            $levelData = $probabilitydata[13];
        }
        elseif ($WinLossCredit<=(-$thound*300) && $WinLossCredit>(-$thound*500)){
            $levelData = $probabilitydata[14];
        }
        elseif ($WinLossCredit<=(-$thound*500)){
            $levelData = $probabilitydata[15];
        }
    }

//    print_r($levelData);die;

    $red_envelope_pool = array(); // 红包池，将下面生成的红包金额放入，总共100个
    foreach ($levelData as $k => $v){
        $red_envelope_nums = $v['probability']*100; // 几率
        $keys = array_keys($red_envelope_pool);
        $last_key = max($keys); // 红包池最后一位key
        $tmp_arr = array_fill($last_key+1, $red_envelope_nums, $v['giftGold']);
        $red_envelope_pool = array_merge($red_envelope_pool , $tmp_arr);
    }

    $rand = rand(0,99); // 0到99随机生成1个数字
    $gold = $red_envelope_pool[$rand]; //要派发给会员的幸运红包金额

    return $gold;

}

