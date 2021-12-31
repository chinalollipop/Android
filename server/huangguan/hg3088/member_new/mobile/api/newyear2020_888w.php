<?php
/*
1月24号-1月29号（美东时间）

美东时间24号存款，次日生成领取次数。
存款100元至1000元，领取1次
存款1000元至5000元，领取2次
存款5000元至3万元，领取3次
存款3万元至10万元，领取5次
存款10万元至50万元，领取8次
存款50万以上，领取15次

从25号开始，根据前一天的存款额度生成可以领取的次数。一直到最后一天30号生成29号的次数。
 **/
error_reporting(1);
ini_set('display_errors','On');
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

/**
美东时间24号存款，次日生成领取次数。
存款100元至1000元，领取1次
存款1000元至5000元，领取2次
存款5000元至3万元，领取3次
存款3万元至10万元，领取5次
存款10万元至50万元，领取8次
存款50万以上，领取15次
 */
//统计会员昨日存款总金额
//$depositAmountTime['begin_time'] = date('Y-m-d 00:00:00', strtotime("-1 day"));
//$depositAmountTime['end_time'] = date("Y-m-d 23:59:59", strtotime("-1 day"));
$depositAmountTime['begin_time'] = '2020-02-10 00:00:00';
$depositAmountTime['end_time'] = '2020-02-10 23:59:59';


switch ($_REQUEST['action']){
    case 'getGrabTimes':

        $YestodayDepositAmount = depositAmountNoYouhui($user_id,$depositAmountTime);
        if($YestodayDepositAmount < 100) {
            $status = '401.3';
            $describe = '前一天总存款金额不符合最低存款要求，不允许申请!';
//            original_phone_request_response($status,$describe);
        }

        $last_times = getGrabTimes();

        $data['lastTimes'] = $last_times;
        $status = '200';
        $describe = '';
        original_phone_request_response($status,$describe,$data);

        break;
    case 'receive_red_envelope':
        $YestodayDepositAmount = depositAmountNoYouhui($user_id,$depositAmountTime);
        if($YestodayDepositAmount < 100) {
            $status = '401.3';
            $describe = '前一天总存款金额不符合最低存款要求，不允许申请!';
            original_phone_request_response($status,$describe);
        }

        $last_times = getGrabTimes();

        // 活动于北京时间2月10号中午12:00-次日11:59期间领取红包
        $newYearBeginTime= '2020-02-10 00:00:00';
        $newYearEndTime = '2020-02-11 23:59:59';
        $curtime = date("Y-m-d H:i:s",time());
        if($curtime < $newYearBeginTime || $curtime > $newYearEndTime){
            $status = '401.2';
            $describe = '请于北京时间2月11号中午12:00-次日11:59期间领取红包哦!';
            original_phone_request_response($status,$describe);
        }

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

        // 检查会员注册时间：所有注册在2020年1月13日前，包含1月13日
        if ($row['AddDate'] > '2020-01-31 23:59:59' ){
            $status = '401.3';
            $describe = '抱歉，您的账号注册时间超过1月31号，无法领取!';
            original_phone_request_response($status,$describe);
        }

        // 入库存款彩金表，等待审核
        $now = date('Y-m-d H:i:s');
        $insertData = [
            'userid' => $user_id,
            'username' => trim($username),
            'EventName' => '分888万红包',
            'registered_at' => $row['AddDate'],
            'YestodayDepositAmount' => $YestodayDepositAmount,
            'gift_gold' => $giftGold,
            'status' => 2, // 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
            'created_at' => $now,
            'updated_at' => $now,
        ];
        foreach($insertData as $key => $val){
            $tmp[] = $key.'=\''.$val.'\'';
        }
        $sql = "INSERT INTO `" . DBPREFIX . "newyear_2020_888w` SET " . implode(',', $tmp);
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
    default: break;
}


/**
 * 获取可领取红包次数
 * @return int
 */
function getGrabTimes(){
    global $dbLink, $YestodayDepositAmount, $user_id;

    $newyear2020_888w_times_level[0] = array('depost_amount'=>100, 'grab_red_envelope_times'=>1);
    $newyear2020_888w_times_level[1] = array('depost_amount'=>1000, 'grab_red_envelope_times'=>2);
    $newyear2020_888w_times_level[2] = array('depost_amount'=>5000, 'grab_red_envelope_times'=>3);
    $newyear2020_888w_times_level[3] = array('depost_amount'=>30000, 'grab_red_envelope_times'=>5);
    $newyear2020_888w_times_level[4] = array('depost_amount'=>100000, 'grab_red_envelope_times'=>8);
    $newyear2020_888w_times_level[5] = array('depost_amount'=>500000, 'grab_red_envelope_times'=>12);

    // -------------------------------------------------------------------------------------------------------- 检查会员领取红包次数是否用尽Start
    foreach ($newyear2020_888w_times_level as $k => $v){
        if ($k<5){
            // 有效次数档位从第一档到第九档
            if ($YestodayDepositAmount > $v['depost_amount'] and $YestodayDepositAmount <= $newyear2020_888w_times_level[$k+1]['depost_amount']){
                $grab_red_envelope_times = $v['grab_red_envelope_times']; // 可领总次数
                break;
            }
        }else{
            // 有效次数档位第十档
            if ($YestodayDepositAmount > $v['depost_amount']){
                $grab_red_envelope_times = $v['grab_red_envelope_times']; // 可领总次数
            }
        }
    }
    // 获取可领取红包次数
    // 剩余次数 = 存款总金额对应的次数 - 今天已领取的次数

    // 捞取今天已领取的几次
    $startTime = date('Y-m-d 00:00:00');
    $endTime = date('Y-m-d 00:00:00',strtotime('+1 day'));
    $sql = "select count(1) as cou from ".DBPREFIX."newyear_2020_888w where userid = $user_id and created_at between '$startTime' and '$endTime' ";
    $res =  mysqli_query($dbLink, $sql);
    $row = mysqli_fetch_assoc($res);
    $last_times = $grab_red_envelope_times - $row['cou']; // 会员剩余可领次数

    // ------------------------------------------------------------------------------------------------------- 检查会员领取红包次数是否用尽End

    return $last_times;
}

// 会员存款金额无优惠
function depositAmountNoYouhui($user_id,$time){
    global $dbLink;
    $begin_time = $time['begin_time'];
    $end_time =  $time['end_time'];
    $timeWhere = " AND `AddDate`>= '$begin_time' AND `AddDate`<= '$end_time'" ; //存款时间范围
    $kscz_where = " AND Payway='W' AND Type='S' AND Checked =1 AND discounType in (0,9)";  // 快速充值
    $gs_where = " AND Payway='N' AND Type='S' AND Checked=1";  // 公司卡存款

    // 第三方,快速充值
    $third_sql = "select sum(Gold) as Gold from ".DBPREFIX."web_sys800_data where userid='$user_id' $timeWhere $kscz_where";
    $third_query = mysqli_query($dbLink, $third_sql);
    $third_cou_res = mysqli_num_rows($third_query);
    if($third_cou_res > 0) {
        $result_third = mysqli_fetch_assoc($third_query); //第三方,快速充值存款额
        $third_money = !empty($result_third['Gold']) ? sprintf("%01.2f",$result_third['Gold']):0;
    }

    //公司卡存款
    $company_sql = "select userid,Gold,currency_after,moneyf from ".DBPREFIX."web_sys800_data where userid='$user_id' $timeWhere $gs_where";
    $company_query = mysqli_query($dbLink, $company_sql);
    $company_cou_res = mysqli_num_rows($company_query);
    if($company_cou_res > 0) {
        $company_money = 0;
        while ($result_company = mysqli_fetch_assoc($company_query)){
            //公司存款金额，无优惠
            $company_money += ($result_company['currency_after'] - $result_company['moneyf']);
        }
        $company_money = sprintf("%01.2f",$company_money);
    }

    $depositGold = $third_money + $company_money;
    return $depositGold;
}