<?php
/*
 * 	2020中秋节、国庆节双节活动，自动派发1个月的真人月俸禄
 *
 * 	1，	只支持cli模式下的运行。
 *  2， 每周一20点统计最新的晋级数据
 * */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require CONFIG_DIR."/app/agents/include/config.inc.php";
require_once CONFIG_DIR.'/../common/activity/config.php';

//只在CLI命令下有效
if (php_sapi_name() == "cli") {


    $last= strtotime("-1 month", time());
    $last_lastday = date("Y-m-t", $last);//上个月最后一天
    $last_firstday = date('Y-m-01', $last);//上个月第一天
    if(isset($argv[1])) {
        //重新生成前一周的紧急数据，包含 开始天，包含 结束天
        $start_time = $argv[1];
        if($argv[1] > $last_firstday) {
            exit("起始时间不能大于上周一");
        }
        if (date('w',strtotime($argv[1]))!=1 or date('w',strtotime($argv[2]))!=0 or ($argv[2]-$argv[1]!=7)){
            exit("开始日期、结束日期必须以周为单位");
        }

        if(isset($argv[2]) && !empty($argv[2])) {
            //如果结束时间大于今天，那么将上一周的最后一天作为结束天
            if($argv[2] > $last_lastday) {
                $argv[2] = $last_lastday;
            }
            $stop_time = $argv[2];
        }else {
            $stop_time = $last_lastday;
        }

        countall($start_time, $stop_time);
    }
    else{
        $start_time=$last_monday;
        $stop_time=$last_lastday;
        countall($last_firstday, $stop_time);
    }
}

/**
 *
 * 根据条件，统计数据（AG真人、OG真人、BBIN真人）
 *
 * @param date $StartTime
 */
function countall($StartTime, $EndTime){
    global $dbMasterLink, $dbLink;


//    @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/zhenren_month_salary.log');
//    @error_log('--------------- 真人月俸禄派发开始'.PHP_EOL, 3, '/tmp/group/zhenren_month_salary.log');

    $sql = "select * from ".DBPREFIX."double_holiday_salary_2020 
            where count_date_start>='".$StartTime."' and count_date_end<='".$EndTime."' and EventName='真人月俸禄'";
    $result_data = mysqli_query($dbLink,$sql);
    $cou=mysqli_num_rows($result_data);
    if ($cou>0){
        die('真人派发月俸禄失败！不可重复派发 '.$cou);
    }

    // 真人视讯数据
    $sql = "select userid, username, sum(total) as total from ".DBPREFIX."zhenren_week_report 
            group by userid";
    $result_data = mysqli_query($dbLink,$sql);
    $cou=mysqli_num_rows($result_data);
    if ($cou>0){
        $jinji_data=[];
        while ($row = mysqli_fetch_assoc($result_data)){
            $jinji_data[$row['userid']]=$row;
        }
    }

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

        foreach ($jinji_data as $k => $v){

            $result=mysqli_query($dbMasterLink, "START TRANSACTION");
            if (!$result) {
                die('事务开启失败！ ' . mysqli_error($dbMasterLink));
            }

            // 添加资金锁
            $lockMoney = mysqli_query($dbMasterLink, "select ID,test_flag, Agents, World, Corprator, Super, Admin,Alias,Phone,Money from " . DBPREFIX.MEMBERTABLE." WHERE ID = '{$v['userid']}' for update ");
            if ($lockMoney){

                $aUser = mysqli_fetch_assoc($lockMoney);
                $moneyf = $aUser['Money'];
                $currency_after = $aUser['Money'] + $v['gift_gold'];
                // 更新会员资金
                $result = mysqli_query($dbMasterLink, "update " . DBPREFIX.MEMBERTABLE." set Money=Money + " . $v['gift_gold'] . " where ID = '" . $v['userid'] . "' ");
                if ($result) {

                    $oDatetime = new DateTime('NOW');
                    $sTime8 = dechex($oDatetime->format('U')); // 8bit
                    $sUser6 = sprintf("%06s", substr(dechex($aUser['ID']), 0, 6)); // 6bit
                    $sTrans_no = 'livesalary' . $sTime8 . $sUser6; //AG平台 订单号生成规则

                    $data['userid'] = $v['userid'];
                    $data['Checked'] = 1;
                    $data['Payway'] = 'O'; // Rebate
                    $data['AuditDate'] = date("Y-m-d H:i:s");
                    $data['Gold'] = $v['gift_gold'];
                    $data['moneyf'] = $moneyf;
                    $data['currency_after'] = $currency_after;
                    $data['AddDate'] = date("Y-m-d", time());
                    $data['Type'] = 'S';
                    $data['UserName'] = $v['username'];
                    $data['Agents'] = $aUser['Agents'];
                    $data['World'] = $aUser['World'];
                    $data['Corprator'] = $aUser['Corprator'];
                    $data['Super'] = $aUser['Super'];
                    $data['Admin'] = $aUser['Admin'];
                    $data['CurType'] = 'RMB';
                    $data['Date'] = date("Y-m-d H:i:s", time());
                    $data['Name'] = $aUser['Alias'];
                    $data['Waterno'] = '';
                    $data['Phone'] = $aUser['Phone'];
                    $data['Notes'] = '真人月俸禄';
                    $data['test_flag'] = $aUser['test_flag'];
                    $data['Order_Code'] = $sTrans_no;

                    $sInsData = '';
                    foreach ($data as $key => $value) {
                        if ($key == 'Order_Code') {
                            $sInsData .= "`$key` = '{$value}'";
                        } else {
                            $sInsData .= "`$key` = '{$value}',";
                        }
                    }

                    // 插入入账记录
                    $in = mysqli_query($dbMasterLink, "insert into `" . DBPREFIX . "web_sys800_data` set $sInsData");
                    if ($in) {
                        $datetime=date('Y-m-d H:i:s');
                        $sql = "INSERT INTO ".DBPREFIX."double_holiday_salary_2020 (`userid`,`username`,`level`,Alias,Agents,Phone,`EventName`,`count_date_start`,`count_date_end`,`total`,`gift_gold`,`status`,`created_at`,`audited_at`,`auditor`) VALUES 
                            ('".$v['userid']."','".$v['username']."','".$v['level']."','".$v['Alias']."','".$v['Agents']."','".$v['Phone']."','真人月俸禄','".$StartTime."','".$EndTime."','".$v['total']."','".$v['gift_gold']."',1,'".$datetime."','".$datetime."','计划任务')";
                        $result = mysqli_query($dbMasterLink, $sql);
                        if ($result) {
                            // 插入返水账变        0用户id|1用户名|2测试/正式|3操作前金额|4操作金额|5操作后金额|6操作类型|7来源|8数据id或订单号|9描述可为空
                            // 添加返水类型备注（type -4 - 返水，source - 5-后台）
                            $moneyLogRes=addAccountRecords(array($v['userid'],$v['username'],$aUser['test_flag'],$moneyf,$v['gift_gold'],$currency_after,13,6,'',"{$StartTime}——{$EndTime}月俸禄入账,操作人:计划任务"));
                            if($moneyLogRes) {
                                mysqli_query($dbMasterLink, "COMMIT");
                            }else{
                                mysqli_query($dbMasterLink, "ROLLBACK");
                                die(json_encode(["err" => -9, "msg" => "添加月俸禄账变日志失败！"]));
                            }
                        }else{
                            mysqli_query($dbMasterLink, "ROLLBACK");
                            die(json_encode(["err" => -5, "msg" => "返水状态变更失败！"]));
                        }

                    } else {
                        mysqli_query($dbMasterLink, "ROLLBACK");
                        die(json_encode(["err" => -7, "msg" => "账变记录插入失败！"]));
                    }
                }else{
                    mysqli_query($dbMasterLink, "ROLLBACK");
                    die(json_encode(["err" => -4, "msg" => "更新会员资金失败！"]));
                }
            }
            else{
                mysqli_query($dbMasterLink, "ROLLBACK");
                die(json_encode(["err" => -3, "msg" => "锁定会员资金失败！"]));
            }
        }

    }
    else{
        die('上个月无月俸禄');
    }

}

function getCurrentInfo($jinji_data){
    global $zhenrenLevelSalaryInfo;

    // 拼凑 level
    // 20万以上的，才有等级
    foreach ($jinji_data as $k => $v){
        if ($v['total']<200000){
            unset($jinji_data[$k]);
        }
        else{
            if ($v['total']>=200000 and $v['total']<500000){
                $jinji_data[$k]['level'] = 1; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[1]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 500000-$v['total'];
            }
            if ($v['total']>=500000 and $v['total']<1000000){
                $jinji_data[$k]['level'] = 2; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[2]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 1000000-$v['total'];
            }
            if ($v['total']>=1000000 and $v['total']<2000000){
                $jinji_data[$k]['level'] = 3; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[3]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 2000000-$v['total'];
            }
            if ($v['total']>=2000000 and $v['total']<3000000){
                $jinji_data[$k]['level'] = 4; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[4]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 3000000-$v['total'];
            }
            if ($v['total']>=3000000 and $v['total']<5000000){
                $jinji_data[$k]['level'] = 5; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[5]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 5000000-$v['total'];
            }
            if ($v['total']>=5000000 and $v['total']<7000000){
                $jinji_data[$k]['level'] = 6; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[6]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 7000000-$v['total'];
            }
            if ($v['total']>=7000000 and $v['total']<10000000){
                $jinji_data[$k]['level'] = 7; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[7]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 10000000-$v['total'];
            }
            if ($v['total']>=10000000 and $v['total']<13000000){
                $jinji_data[$k]['level'] = 8; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[8]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 13000000-$v['total'];
            }
            if ($v['total']>=13000000 and $v['total']<16000000){
                $jinji_data[$k]['level'] = 9; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[9]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 16000000-$v['total'];
            }
            if ($v['total']>=16000000 and $v['total']<20000000){
                $jinji_data[$k]['level'] = 10; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[10]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 20000000-$v['total'];
            }
            if ($v['total']>=20000000 and $v['total']<25000000){
                $jinji_data[$k]['level'] = 11; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[11]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 25000000-$v['total'];
            }
            if ($v['total']>=25000000 and $v['total']<30000000){
                $jinji_data[$k]['level'] = 12; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[12]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 30000000-$v['total'];
            }
            if ($v['total']>=30000000 and $v['total']<35000000){
                $jinji_data[$k]['level'] = 13; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[13]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 35000000-$v['total'];
            }
            if ($v['total']>=35000000 and $v['total']<40000000){
                $jinji_data[$k]['level'] = 14; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[14]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 40000000-$v['total'];
            }
            if ($v['total']>=40000000 and $v['total']<50000000){
                $jinji_data[$k]['level'] = 15; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[15]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 50000000-$v['total'];
            }
            if ($v['total']>=50000000 and $v['total']<60000000){
                $jinji_data[$k]['level'] = 16; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[16]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 60000000-$v['total'];
            }
            if ($v['total']>=60000000 and $v['total']<80000000){
                $jinji_data[$k]['level'] = 17; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[17]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 80000000-$v['total'];
            }
            if ($v['total']>=80000000 and $v['total']<100000000){
                $jinji_data[$k]['level'] = 18; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[18]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 100000000-$v['total'];
            }
            if ($v['total']>=100000000 and $v['total']<120000000){
                $jinji_data[$k]['level'] = 19; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[19]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 120000000-$v['total'];
            }
            if ($v['total']>=120000000 and $v['total']<150000000){
                $jinji_data[$k]['level'] = 20; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[20]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 150000000-$v['total'];
            }
            if ($v['total']>=150000000 and $v['total']<200000000){
                $jinji_data[$k]['level'] = 21; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[21]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 200000000-$v['total'];
            }
            if ($v['total']>=200000000 and $v['total']<250000000){
                $jinji_data[$k]['level'] = 22; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[22]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 250000000-$v['total'];
            }
            if ($v['total']>=250000000 and $v['total']<300000000){
                $jinji_data[$k]['level'] = 23; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[23]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 300000000-$v['total'];
            }
            if ($v['total']>=300000000 and $v['total']<400000000){
                $jinji_data[$k]['level'] = 24; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[24]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 400000000-$v['total'];
            }
            if ($v['total']>=400000000 and $v['total']<500000000){
                $jinji_data[$k]['level'] = 25; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[25]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 500000000-$v['total'];
            }
            if ($v['total']>=500000000 and $v['total']<700000000){
                $jinji_data[$k]['level'] = 26; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[26]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 700000000-$v['total'];
            }
            if ($v['total']>=700000000 and $v['total']<950000000){
                $jinji_data[$k]['level'] = 27; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[27]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 950000000-$v['total'];
            }
            if ($v['total']>=950000000 and $v['total']<1200000000){
                $jinji_data[$k]['level'] = 28; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[28]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 1200000000-$v['total'];
            }
            if ($v['total']>=1200000000 and $v['total']<1500000000){
                $jinji_data[$k]['level'] = 29; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[29]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 1500000000-$v['total'];
            }
            if ($v['total']>=1500000000){
                $jinji_data[$k]['level'] = 30; // 当前等级
                $jinji_data[$k]['gift_gold'] = $zhenrenLevelSalaryInfo[30]['month_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 0;
            }

        }
    }
    return $jinji_data;
}