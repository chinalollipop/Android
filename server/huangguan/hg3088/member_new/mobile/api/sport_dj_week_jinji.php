<?php
/*
 *
 * 体育电竞晋级礼金
 *      djWeekJinjiApply
 *      每周一登录会员申请最新晋级信息
 *      申请时间 体育电竞晋级礼金活动申请时间  美东时间每周一08:00至次日上午08:00。 (北京时间，周一晚上八点 20:00 ，截止到星期二晚上八点 20:00 申请)
 *      并生成最新的晋级礼金记录，运营后台进行审核派发礼金
 * */
//error_reporting(1);
//ini_set('display_errors','On');
session_start();

define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));

require_once ROOT_DIR.'/common/activity/config.php';
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activity.class.php");

if($_REQUEST['action'] == 'djWeekJinjiApply'){
    $status = '501.3';
    $describe = '体育升级活动维护中，请联系客服!';
    original_phone_request_response($status,$describe);

    $user_id = $_SESSION['userid'];
    $username = $_SESSION['UserName']?$_SESSION['UserName']:$_REQUEST['username'];

    if(!$user_id) { // 兼容旧版跳转新版
        $memberinfo = returnMemberID($username);
        $user_id = $_SESSION['userid'] = $memberinfo['ID'];
    }

    if ($username!=$_REQUEST['username']){
        $status = '502.3';
        $describe = '查询请输入本人真实账号!';
        original_phone_request_response($status,$describe);
    }

    //体育电竞晋级礼金活动申请时间  美东时间每周一08:00至次日上午08:00。 (北京时间，周一晚上八点 20:00 ，截止到星期二晚上八点 20:00 申请)
    $nowMondayEight =  mktime(8,0,0,date('m'),date('d')-date('w')+1,date('Y')); //本周一start
    $nowTuesdayEight = mktime(8,0,0,date('m'),date('d')-date('w')+2,date('Y')); //本周二start
    if(time() < $nowMondayEight || time() >= $nowTuesdayEight){
        $status = '502.4';
        $describe = '请于北京时间每周一20:00至次日20:00申请体育电竞晋级礼金哦!';
        original_phone_request_response($status,$describe);
    }

    // 上周时间
    $days = date('w')==0?13:date('w')+6;
    $start_time = $last_monday = date('Y-m-d',time()-$days*86400); // 上周一
    $stop_time = $last_sunday = date('Y-m-d', strtotime('-1 sunday', time())); // 上周日

    // 验证参数
    $param['user_id'] = $user_id;
    $param['username'] = $username;
    $param['nowMondayEight'] = date('Y-m-d H:i:s' ,  $nowMondayEight);
    $param['nowTuesdayEight'] = date('Y-m-d H:i:s' , $nowTuesdayEight);

    // 检查当前会员是否设置不准领取彩金分层
    // 检查分层是否开启 status 1 开启 0 关闭
    // layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
    $sUserlayer = $_SESSION['layer']?$_SESSION['layer']:$memberinfo['layer'];
    $layerId=4;
    if ($sUserlayer==$layerId){
        $layer = getUserLayerById($layerId);
        if ($layer['status']==1) {
            $status = '400.66';
            $describe = '账号分层异常，请联系我们在线客服';
            original_phone_request_response($status,$describe,$resdata);
        }
    }

    // 查询当前登录会员是否申请
    $applyResult = searchApply($param);
    if($applyResult) {
        $status = '502.5';
        $describe = '你已在本周申请过体育电竞晋级礼金，请投注后下周一再次申请！';
        original_phone_request_response($status,$describe);
    }

    countall($start_time, $stop_time, $param);

}else {
    $status = '502.1';
    $describe = '参数非法！';
    original_phone_request_response($status,$describe);
}

/**
 *
 * 根据条件，统计数据（体育电竞、泛亚电竞）
 *
 * @param date $StartTime
 */
function countall($StartTime, $EndTime, $param){
    global $dbMasterLink, $dbLink, $sportDjLevelSalaryInfo;

    $StartTime = '2020-04-01';
    $user_id = $param['user_id'];

    // 当前登录会员已申请体育电竞晋级礼金，返回月俸禄表  返回会员、等级
    $sql = "select userid, username, `level` from ".DBPREFIX."sport_dj_jinji_salary where userid = $user_id and EventName='体育电竞晋级礼金'";
    $result_data = mysqli_query($dbLink,$sql);
    $cou=mysqli_num_rows($result_data);
    if ($cou>0){
        $jinji_salary_data=[];
        while ($row = mysqli_fetch_assoc($result_data)){
            $jinji_salary_data[$row['userid'].'_'.$row['level']]=$row;
        }
    }

    // 体育电竞晋级礼金用户信息,用户ID、用户名、总码量信息
    $sql = "select userid, username, sum(total) as total from ".DBPREFIX."sport_dj_week_report 
            where count_date_end<='".$EndTime."'
            AND userid = $user_id";
    $result_data = mysqli_query($dbLink,$sql);
    $cou=mysqli_num_rows($result_data);
    if ($cou>0){
        $jinji_data=[];
        while ($row = mysqli_fetch_assoc($result_data)){
            $jinji_data[$row['userid']]=$row;	// 体育电竞晋级礼金用户信息 从每周统计中返回总打码
        }
    }

    // 已达到有效投注的每周统计体育电竞晋级礼金用户信息（返回总打码、等级、晋级礼金、距下一级打码量）
    $jinji_data = getCurrentInfo($jinji_data);

    $cou = count($jinji_data);
    if ($cou>0){

        $ids = implode(',', array_keys($jinji_data));
        $sql = "select ID,Alias,Agents,Phone  from ".DBPREFIX."web_member_data where ID in ($ids)";
        $res = mysqli_query($dbLink,$sql);
        while ($row = mysqli_fetch_assoc($res)){
            $jinji_data[$row['ID']]['Alias']=$row['Alias'];
            $jinji_data[$row['ID']]['Agents']=$row['Agents'];
            $jinji_data[$row['ID']]['Phone']=$row['Phone'];
        }

        $result=mysqli_query($dbMasterLink, "START TRANSACTION");
        if (!$result) {
            die('事务开启失败！ ' . mysqli_error($dbMasterLink));
        }

        $start=0;
        $datetime=date('Y-m-d H:i:s');
        $sql = "INSERT INTO ".DBPREFIX."sport_dj_jinji_salary (`userid`,`username`,`level`,Alias,Agents,Phone,`EventName`,`count_date_start`,`count_date_end`,`total`,`gift_gold`,`status`,`created_at`) VALUES ";
        // 达到有效投注的每周统计体育电竞晋级礼金用户信息（返回总打码、等级、晋级礼金、距下一级打码量）
        foreach ($jinji_data as $k => $v){

            // 达到投注的用户，在申请体育电竞晋级礼金、月俸禄表中申请过。 有则返回键，无则false
            $jsdKey = array_search($v['userid'], array_column($jinji_salary_data, 'userid'));

            if ($jsdKey!==false){
                // 真人晋级彩金，如果大于1级，则判断之前是否有晋级，有则撤销此级别，继续判断下一级别，以此类推直到最后一级别
                // 补齐之前的晋级记录一起入库审核
                $numbers=range(1,$v['level']);
                // 判断级别是否已派发，只派发此前没有的级别礼金
                foreach ($numbers as $k2 => $v2){
                    if (isset($jinji_salary_data[$k.'_'.$v2])){
                        unset($numbers[$v2-1]);
                        continue;
                    }
                }

                foreach ($numbers as $k3 => $v3){
                    $gift_money = $sportDjLevelSalaryInfo[$v3]['jinji_salary'];
                    if ($start==0){
                        $sql .= "('".$v['userid']."','".$v['username']."','".$v3."','".$v['Alias']."','".$v['Agents']."','".$v['Phone']."','体育电竞晋级礼金','".$StartTime."','".$EndTime."','".$v['total']."','".$gift_money."',2,'".$datetime."')";
                    }else{
                        $sql .= ",('".$v['userid']."','".$v['username']."','".$v3."','".$v['Alias']."','".$v['Agents']."','".$v['Phone']."','体育电竞晋级礼金','".$StartTime."','".$EndTime."','".$v['total']."','".$gift_money."',2,'".$datetime."')";
                    }
                    $start++;
                }
            }
            else{
                $numbers=range(1,$v['level']);
                foreach ($numbers as $k3 => $v3){
                    $gift_money = $sportDjLevelSalaryInfo[$v3]['jinji_salary'];
                    if ($start==0){
                        $sql .= "('".$v['userid']."','".$v['username']."','".$v3."','".$v['Alias']."','".$v['Agents']."','".$v['Phone']."','体育电竞晋级礼金','".$StartTime."','".$EndTime."','".$v['total']."','".$gift_money."',2,'".$datetime."')";
                    }else{
                        $sql .= ",('".$v['userid']."','".$v['username']."','".$v3."','".$v['Alias']."','".$v['Agents']."','".$v['Phone']."','体育电竞晋级礼金','".$StartTime."','".$EndTime."','".$v['total']."','".$gift_money."',2,'".$datetime."')";
                    }
                    $start++;
                }
            }
        }

        if ($start>0){
            $result=mysqli_query($dbMasterLink, $sql);
            if (!$result){
                $result=mysqli_query($dbMasterLink, "ROLLBACK");
                $status = '503.2';
                $describe = '会员体育电竞数据晋级失败！' . mysqli_error($dbMasterLink);
                original_phone_request_response($status,$describe);
            }else {
                $result=mysqli_query($dbMasterLink, "COMMIT");
                $status = '200';
                $describe = '会员体育电竞数据晋级成功！';
                original_phone_request_response($status,$describe);
            }
        }
        else{
            $status = '503.3';
            $describe = '您尚未达到下一等级领取条件，请您继续投注体育电竞，在点击领取！'; //会员体育电竞数据晋级失败！没有会员升级
            original_phone_request_response($status,$describe);
        }

    }
    else{
        $status = '503.1';
        $describe = '您尚未达到下一等级领取条件，请您继续投注体育电竞后，在点击领取！'; // 会员体育电竞没有晋级数据
        original_phone_request_response($status,$describe);
    }

}

function getCurrentInfo($jinji_data){
    global $sportDjLevelSalaryInfo;

    // 拼凑 level
    // 20万以上的，才有等级
    foreach ($jinji_data as $k => $v){
        if ($v['total']<200000){
            unset($jinji_data[$k]);
        }
        else{
            if ($v['total']>=200000 and $v['total']<500000){
                $jinji_data[$k]['level'] = 1; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[1]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 500000-$v['total'];
            }
            if ($v['total']>=500000 and $v['total']<1000000){
                $jinji_data[$k]['level'] = 2; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[2]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 1000000-$v['total'];
            }
            if ($v['total']>=1000000 and $v['total']<2000000){
                $jinji_data[$k]['level'] = 3; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[3]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 2000000-$v['total'];
            }
            if ($v['total']>=2000000 and $v['total']<3000000){
                $jinji_data[$k]['level'] = 4; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[4]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 3000000-$v['total'];
            }
            if ($v['total']>=3000000 and $v['total']<5000000){
                $jinji_data[$k]['level'] = 5; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[5]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 5000000-$v['total'];
            }
            if ($v['total']>=5000000 and $v['total']<7000000){
                $jinji_data[$k]['level'] = 6; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[6]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 7000000-$v['total'];
            }
            if ($v['total']>=7000000 and $v['total']<10000000){
                $jinji_data[$k]['level'] = 7; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[7]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 10000000-$v['total'];
            }
            if ($v['total']>=10000000 and $v['total']<13000000){
                $jinji_data[$k]['level'] = 8; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[8]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 13000000-$v['total'];
            }
            if ($v['total']>=13000000 and $v['total']<16000000){
                $jinji_data[$k]['level'] = 9; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[9]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 16000000-$v['total'];
            }
            if ($v['total']>=16000000 and $v['total']<20000000){
                $jinji_data[$k]['level'] = 10; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[10]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 20000000-$v['total'];
            }
            if ($v['total']>=20000000 and $v['total']<25000000){
                $jinji_data[$k]['level'] = 11; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[11]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 25000000-$v['total'];
            }
            if ($v['total']>=25000000 and $v['total']<30000000){
                $jinji_data[$k]['level'] = 12; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[12]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 30000000-$v['total'];
            }
            if ($v['total']>=30000000 and $v['total']<45000000){
                $jinji_data[$k]['level'] = 13; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[13]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 45000000-$v['total'];
            }
            if ($v['total']>=45000000 and $v['total']<60000000){
                $jinji_data[$k]['level'] = 14; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[14]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 60000000-$v['total'];
            }
            if ($v['total']>=60000000 and $v['total']<80000000){
                $jinji_data[$k]['level'] = 15; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[15]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 80000000-$v['total'];
            }
            if ($v['total']>=80000000 and $v['total']<110000000){
                $jinji_data[$k]['level'] = 16; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[16]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 110000000-$v['total'];
            }
            if ($v['total']>=110000000 and $v['total']<130000000){
                $jinji_data[$k]['level'] = 17; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[17]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 130000000-$v['total'];
            }
            if ($v['total']>=130000000 and $v['total']<150000000){
                $jinji_data[$k]['level'] = 18; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[18]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 150000000-$v['total'];
            }
            if ($v['total']>=150000000 and $v['total']<200000000){
                $jinji_data[$k]['level'] = 19; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[19]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 200000000-$v['total'];
            }
            if ($v['total']>=200000000 and $v['total']<250000000){
                $jinji_data[$k]['level'] = 20; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[20]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 250000000-$v['total'];
            }
            if ($v['total']>=250000000 and $v['total']<300000000){
                $jinji_data[$k]['level'] = 21; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[21]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 300000000-$v['total'];
            }
            if ($v['total']>=300000000 and $v['total']<350000000){
                $jinji_data[$k]['level'] = 22; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[22]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 350000000-$v['total'];
            }
            if ($v['total']>=350000000 and $v['total']<550000000){
                $jinji_data[$k]['level'] = 23; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[23]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 550000000-$v['total'];
            }
            if ($v['total']>=550000000 and $v['total']<850000000){
                $jinji_data[$k]['level'] = 24; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[24]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 850000000-$v['total'];
            }
            if ($v['total']>=850000000 and $v['total']<1200000000){
                $jinji_data[$k]['level'] = 25; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[25]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 1200000000-$v['total'];
            }
            if ($v['total']>=1200000000 and $v['total']<1700000000){
                $jinji_data[$k]['level'] = 26; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[26]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 1700000000-$v['total'];
            }
            if ($v['total']>=1700000000 and $v['total']<2500000000){
                $jinji_data[$k]['level'] = 27; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[27]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 2500000000-$v['total'];
            }
            if ($v['total']>=2500000000 and $v['total']<3800000000){
                $jinji_data[$k]['level'] = 28; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[28]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 3800000000-$v['total'];
            }
            if ($v['total']>=3800000000 and $v['total']<6000000000){
                $jinji_data[$k]['level'] = 29; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[29]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 6000000000-$v['total'];
            }
            if ($v['total']>=6000000000){
                $jinji_data[$k]['level'] = 30; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[30]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 0;
            }

        }
    }
    return $jinji_data;
}

// 查询当日是否申请
function searchApply($param) {
    global $dbLink;
    $user_id = $param['user_id'];
    $nowMondayEight = $param['nowMondayEight'];
    $nowTuesdayEight = $param['nowTuesdayEight'];
    $sql = "select userid, username, `level`, `EventName`, `created_at` from ".DBPREFIX."sport_dj_jinji_salary where EventName = '体育电竞晋级礼金' AND userid = $user_id  order by `id`  DESC LIMIT 1";
    //@error_log('sql:'. $sql.PHP_EOL, 3, '/tmp/group/api_sport_dj_week_jinji.log');
    $result_data = mysqli_query($dbLink,$sql);
    $cou=mysqli_num_rows($result_data);
    $result_assoc = mysqli_fetch_assoc($result_data);
    if ($cou>0){
        $created_at = $result_assoc['created_at'];
        if($created_at > $nowMondayEight && $created_at < $nowTuesdayEight) {
            return true;    // 已申请
        }else{
            return false;   // 未申请
        }

    } else {
        return false;   // 无记录
    }
}