<?php
session_start();
include_once('../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '502';
    $describe = '请重新登录!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}
$test_flag = $_SESSION['test_flag']; // 0 正式帐号，1 测试账号
$user_id = $_SESSION['userid'];
$UserName = $_SESSION['UserName'];
$appRefer = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:''; // 13 苹果，14 安卓
$actype = isset($_REQUEST['actype'])?$_REQUEST['actype']:'';
$typearr = array('checked','sign','receive'); // checked 查询上周签到天数以及本周签到情况，sign 签到，receive 领取

$resdata = array();
$redisObj = new Ciredis();

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
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
}
//if($appRefer==13){
//    $appRefer = 3;
//}else if($appRefer==14){
//    $appRefer = 4;
//}else{
//    $appRefer = -3;
//}
if(!in_array($actype,$typearr)){
    $status = '502.1';
    $describe = '参数不正确!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}
if($test_flag){ // 试玩账号不支持签到
    $status = '502.2';
    $describe = '试玩账号不支持签到!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

// 获取签到达标金额
$standard = $redisObj->getSimpleOne('attendance_set_standard'); // 取redis 设置的值
$standard = json_decode($standard,true) ;
if(!$standard){
    $standard['standardmoney'] = 1000;
    $standard['maxstandardMoney'] = 888;
    $standard['standardswitch'] = 'not';
}

if($standard['standardswitch']!='open'){ // 平台是否开通签到功能
    $status = '500.1';
    $describe = '暂未开通签到功能!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

// 查询等级红包金额及几率
$attendancedata = $redisObj->getSimpleOne('attendance_set_probability');
$attendancedata = json_decode($attendancedata,true) ;

$AuditDate = date("Y-m-d H:i:s");
$date = date('Y-m-d'); // 当天日期
$curweekday = 0; // 本周已签到天数

$beginweek=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y')); // 上周一
$endweek=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y')); // 上周日
$last_date_s = date('Y-m-d',$beginweek);
$last_date_e =  date('Y-m-d',$endweek);

$beginweek = mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y')); // 本周一
$endweek = mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y')); // 本周日
$cur_date_s = date('Y-m-d',$beginweek); // 本周一
$cur_date_e =  date('Y-m-d',$endweek); // 本周日

$week_day = date('w'); // 返回当天的星期;数字0表示是星期天,数字123456表示星期一到六
$weekday_s = ($week_day + 6) % 7; // 本周一
$weekday_e = ($week_day) % 7; // 本周末，周日

if($week_day==0){ // 当前时间是周日的时候特殊处理
    $last_date_s = date('Y-m-d', strtotime('-2 monday', time())); // 上周一
    $last_date_e = date('Y-m-d', strtotime('-1 sunday', time())); // 上周日
    $cur_date_s = date('Y-m-d',strtotime("-{$weekday_s} day")); // 本周一
    $cur_date_e = date('Y-m-d',strtotime("-{$weekday_e} day")); // 本周末，周日
}

//echo $week_day.'==='.$last_date_s.'=='.$last_date_e.'++++';
//echo $cur_date_s.'=='.$cur_date_e;

switch ($actype){
    case 'checked': // 查询本周和上周签到情况

        $sql_user_id = "userid='$user_id' ";
        $lasttime =" AND signdate BETWEEN '".$cur_date_s."' and '".$cur_date_e."'" ;
        $sql = "select signdate,status from ".DBPREFIX."web_attendance_list where $sql_user_id $lasttime order by ID desc";
        $result = mysqli_query($dbLink,$sql); // 结算

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
       // var_dump($data);

        $newallWeekData = returnWeekDay($data,''); // 需要返回的本周的新数组

        foreach ($newallWeekData as $k => $row) {
            $resdata['rows'][$k] = $row;
        }

        $after_attendancedata = array(); // 返回天数等级数据
       // $attendanceDay = array(); // 返回天数等级数据
        foreach ($attendancedata as $key=>$value){ // 按天数等级排列
            for($i=0;$i<count($value);$i++) { // 按等级 level 组合数组
                $after_attendancedata[$value[$i]["attendanceDay"]][] = $value[$i];
            }
        }
        // var_dump($after_attendancedata);
        foreach ($after_attendancedata as $k=>$value) { // 按天数等级排列
            $resdata['attendanceDay'][] = $k;
        }

        $resdata['standardmoney'] = $standard['standardmoney']; // 签到达标金额
        $resdata['maxstandardMoney'] = $standard['maxstandardMoney']; // 签到最高领取金额
        $resdata['curweekday'] = $curweekday; // 本周签到总天数
        if($date == $cur_date_s ){ // 只有 周一中午12点到周二中午12点 返回上周签到总天数
            $resdata['lastweekday'] = returnLastSignDay(); // 上周签到总天数，如果总天数大于等于3 前端就显示领取按钮
        }else{  // 不在领取时间内重置
            $resdata['lastweekday'] = 0;
        }

        $status = '200';
        $describe = '查询成功';
        original_phone_request_response($status,$describe,array($resdata));

        break;
    case 'sign': // 签到
        $attTime = $redisObj->getSimpleOne('attendance_action_useid_'.$user_id);
        if($attTime) {
            $allowtime = time()-$attTime;
            if($allowtime<3) { // 3 秒
                $status = '400.02';
                $describe = '不允许多次点击,请稍后申请';
                original_phone_request_response($status,$describe,$resdata);
            }
        }
        // 插入当前申请时间，存入redis, 确保不允许重复申请
        $redisObj->setOne('attendance_action_useid_'.$user_id, time());

        checkEveryDayDes();

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        if($beginFrom){
            $sql="insert into ".DBPREFIX."web_attendance_list set userid='{$user_id}',status='1',UserName='{$UserName}',signdate='{$date}',AddDate='$AuditDate'";
           // echo $sql;
            $res = mysqli_query($dbMasterLink,$sql);
            if ($res){
                mysqli_query($dbMasterLink, "COMMIT");
                $status = '200';
                $describe = '恭喜，签到成功';
                original_phone_request_response($status,$describe,$resdata);
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $status = '502.3';
                $describe = '插入会员签到记录失败';
                exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $status = '502.9';
            $describe = '事务开启失败';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'receive': // 领取，只能美东时间周一领取
        // echo $date.'==='.$cur_date_s;
        if($date != $cur_date_s ){
            $status = '502.6';
            $describe = '请于周一中午12点到周二中午12点领取彩金';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }
        $attTime = $redisObj->getSimpleOne('attendance_re_action_useid_'.$user_id);
        if($attTime) {
            $allowtime = time()-$attTime;
            if($allowtime<3) { // 3 秒
                $status = '400.03';
                $describe = '不允许多次点击,请稍后申请';
                exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
            }
        }
        // 插入当前申请时间，存入redis, 确保不允许重复申请
        $redisObj->setOne('attendance_re_action_useid_'.$user_id, time());


        checkReceived();// 查询本周是否已领取红包
        $lastsigndays = returnLastSignDay(); // 上周签到总天数
        if($lastsigndays <3){
            $status = '502.7';
            $describe = '您上周签到天数'.$lastsigndays.'天，不足3天，不能领取彩金';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }
        $giftGold = returnGiftMoney($lastsigndays); // 领取的红包金额
       // echo $giftGold;


        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        if($beginFrom){
            $resultMem = mysqli_query($dbMasterLink,"select ID,Money from  ".DBPREFIX.MEMBERTABLE." where ID='$user_id' for update");
            if($resultMem){
                $rowMem = mysqli_fetch_assoc($resultMem);
                $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$giftGold where ID='$user_id'";
                if(mysqli_query($dbMasterLink,$mysql)){

                    $currency_after = $rowMem['Money']+$giftGold; // 用户充值后的余额
                    $agents=$_SESSION['Agents'];
                    $world=$_SESSION['World'];
                    $corprator=$_SESSION['Corprator'];
                    $super=$_SESSION['Super'];
                    $admin=$_SESSION['Admin'];
                    $getday= $AuditDate;
                    $realName = $_SESSION['Alias'];
                    $notes='APP签到红包'; // 备注
                    $bank = $_SESSION['Bank_Name'];
                    $bank_account=$_SESSION['Bank_Account'];
                    $bank_address=$_SESSION['Bank_Address'];
                    $order_code = date("YmdHis",time()).rand(100000,999999);

                    $sql = "insert into `".DBPREFIX."web_sys800_data` set userid='{$user_id}',Checked=1,Payway='O',Gold='$giftGold',moneyf='{$rowMem['Money']}',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='{$UserName}',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$realName',notes='$notes',Bank_Account='$bank_account',Bank='$bank',Bank_Address='$bank_address',Order_Code='$order_code',AuditDate='$AuditDate',Cancel='0',test_flag='$test_flag'";
                    //@error_log($sql.PHP_EOL,  3,  '/tmp/aaa.log');
                    $res = mysqli_query($dbMasterLink,$sql);
                    if ($res) {

                        $sqlBill="insert into ".DBPREFIX."web_attendanceSignin_bill set userid='{$user_id}',UserName='{$UserName}',LuckyGold='{$giftGold}',signdays='$lastsigndays',AddDate='$date',BillAddDate='$AuditDate'";
                        $resBill = mysqli_query($dbMasterLink,$sqlBill);
                        if ($resBill){
                            $moneyLogRes = addAccountRecords(array($user_id, $UserName, $test_flag, $rowMem['Money'], $giftGold, $rowMem['Money'] + $giftGold, 11, $appRefer, $user_id, "[$notes],成功入账"));
                            if ($moneyLogRes) {
                                mysqli_query($dbMasterLink, "COMMIT");
                                $status = '200';
                                $describe = '恭喜，领取签到红包'.$giftGold.'元';
                                $resdata['balance_hg'] = $currency_after;  // 用户余额
                                $resdata['data_gold'] = $giftGold;  // 抽取彩金
                                original_phone_request_response($status,$describe,array($resdata));

                            } else {
                                mysqli_query($dbMasterLink, "ROLLBACK");
                                $status = '401.10';
                                $describe = '插入用户资金账变记录失败';
                                exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
                            }
                        }else{
                            $status = '401.9';
                            $describe = '插入会员彩金记录失败';
                            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
                        }
                    } else {
                        mysqli_query($dbMasterLink, "ROLLBACK");
                        $status = '401.8';
                        $describe = '插入800账变记录失败';
                        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $status = '401.7';
                    $describe = '更新会员资金失败';
                    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $status = '401.6';
                $describe = '锁定会员资金失败';
                exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $status = '401.5';
            $describe = '事务开启失败';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }

        break;
}

// 判断每天存款金额是否达到要求，查询会员当天是否有已经签到
function checkEveryDayDes(){
    global $dbLink,$dbMasterLink,$user_id,$standard,$date ;
    // 当天存款
    $date_s = $date; // 开始时间
    $date_e =  $date; // 结束时间
    $sql_user_id = "userid='$user_id' ";
    $time =" AND addDate BETWEEN '".$date_s."' and '".$date_e."'" ;
    $payType = "AND Payway NOT IN ('O', 'R')";    // 申请礼金、返水不统计
    $Type = " AND Type IN ('S') AND discounType NOT IN (1,2,3,4,5,6,7,8) AND Checked=1 ";  // discounType in (1,2,3,4,5,6,7,8) 人工存款不算

    // 检查当天是否已签到
    $chksql = "select ID from ".DBPREFIX."web_attendance_list where $sql_user_id and signdate= '$date' order by ID desc";
    $res_chk = mysqli_query($dbMasterLink, $chksql);
    $cou =mysqli_num_rows($res_chk);
    if($cou>0){
        $status = '502.5';
        $describe = '您今天已签到!请不要重复签到';
//        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }

    // 统计会员一周存款, 线下银行存款优惠, 返水, 申请礼金不统计在内
    $deposit_sql = "select ID,userid,Checked,Payway,discounType,Gold,moneyf,currency_after,Type,UserName,Cancel,Preferential from ".DBPREFIX."web_sys800_data where $sql_user_id $time  $payType $Type order by ID desc";
    $res_deposit = mysqli_query($dbLink, $deposit_sql);
    $depositGold = 0;
    while ($row = mysqli_fetch_assoc($res_deposit)){
        if($row['Type'] == 'S') {
            if($row['Preferential'] == 1) {
                $row['Gold'] = $row['currency_after']-$row['moneyf']; //存款实际金额, 不算优惠
            }
            $depositGold += $row['Gold']; // 上周总存款
        }
    }

    if(bccomp($depositGold,$standard['standardmoney']) < 0){ // 等于为0，大于为 1，小于为 -1
        $status = '502.4';
        $describe = '本日存款金额'.$depositGold.'元，未达标，请先存款!';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }



}

// 返回本周和上周所有日期数据,$last :last 上周
function returnWeekDay($data,$last){
    global $curweekday,$week_day,$weekday_e;
    $allWeekData = array();
    $weekDay = array(); // 本周所有日期
    $number = array(0,1,2,3,4,5,6);
    if($week_day==0){ // 当前时间是周日的时候特殊处理
        $number = array(6,5,4,3,2,1,0);
    }

    if($last){ // 上周日期
//        foreach ($number as $k){ // 上周日期
//            $cur_day = mktime(0,0,0,date('m'),date('d')-date('w')+$k-7,date('Y'));
//            $after_day = date('Y-m-d',$cur_day);
//            array_push($weekDay, $after_day);
//        }
    }else{ // 本周日期
        foreach ($number as $k){ // 本周日期
            $cur_day = mktime(0,0,0,date('m'),date('d')-date('w')+1+$k,date('Y'));
            $after_day = date('Y-m-d',$cur_day);
              if($week_day ==0){ // 当前时间是周日的时候特殊处理
                  $after_weekday = ($weekday_e + $k) % 7;
                  $after_day = date('Y-m-d',strtotime("-{$after_weekday} day"));
              }

            array_push($weekDay, $after_day);
        }
    }

    foreach ($weekDay as $week){
        array_push($allWeekData,array('signdate'=>$week,'status'=>'0'));
    }

    $newallWeekData = array(); // 需要返回的新数组
    foreach ($allWeekData as $k => $weekday) {
        foreach ($data as $i => $row) {
            if($row['signdate'] == $weekday['signdate']){
                $weekday['status'] = '1';
                $curweekday++;
            }
        }
        $newallWeekData[] = array('signdate'=>$weekday['signdate'],'status'=>$weekday['status']);

    }

   // return $weekDay;
   // return $allWeekData;
    return $newallWeekData;

}

// 返回上周签到天数
function returnLastSignDay(){
    global $dbLink,$user_id,$last_date_s,$last_date_e;
   // echo $last_date_s.'=='.$last_date_e;
    $sql_user_id = "userid='$user_id' ";
    $lasttime =" AND signdate BETWEEN '".$last_date_s."' and '".$last_date_e."'" ;
    $sql = "select signdate,status from ".DBPREFIX."web_attendance_list where $sql_user_id $lasttime order by ID desc";
    $result = mysqli_query($dbLink,$sql); // 结算
    $cou=mysqli_num_rows($result); // 总数
    return $cou;
}

/*
 * 返回红包金额,
 * $lastsigndays 上周签到天数
 * */
function returnGiftMoney($lastsigndays){
    global $redisObj,$attendancedata;
    $levelData = array();
    switch ($lastsigndays){ // 1 2 3 三个等级
        case '3':
        case '4':
        $levelData = $attendancedata[1];
            break;
        case '5':
        case '6':
        $levelData = $attendancedata[2];
            break;
        case '7':
            $levelData = $attendancedata[3];
            break;
    }

    $red_envelope_pool = array(); // 红包池，将下面生成的红包金额放入，总共100个
    foreach ($levelData as $k => $v){
        $red_envelope_nums = $v['probability']*100; // 几率
        $keys = array_keys($red_envelope_pool);
        $last_key = max($keys); // 红包池最后一位key
        $tmp_arr = array_fill($last_key+1, $red_envelope_nums, $v['attendanceMoney']);
        $red_envelope_pool = array_merge($red_envelope_pool , $tmp_arr);
    }

    $rand = rand(0,99); // 0到99随机生成1个数字
    $gold = $red_envelope_pool[$rand]; //要派发给会员的幸运红包金额

    return $gold;

}

// 检验本周是否已经领取过彩金
function checkReceived(){
    global $dbLink,$dbMasterLink,$user_id,$cur_date_s,$cur_date_e;

    $sql_user_id = "userid='$user_id' ";
    $lasttime =" AND AddDate BETWEEN '".$cur_date_s."' and '".$cur_date_e."'" ;
    $sql = "select ID from ".DBPREFIX."web_attendanceSignin_bill where $sql_user_id $lasttime order by ID desc";
    // echo $sql;
    $result = mysqli_query($dbMasterLink,$sql);
    $cou=mysqli_num_rows($result); // 总数
    if($cou>0){
        $status = '502.8';
        $describe = '本周您已领取过红包!请不要重复领取';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }

}

?>