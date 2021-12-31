<?php
/*
 * 	体育电竞晋级礼金
 *      每周一更新最新晋级信息
 *      并生成最新的晋级礼金记录，运营后台进行审核派发礼金
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

    $days = date('w')==0?13:date('w')+6;
    $last_monday = date('Y-m-d',time()-$days*86400); // 上周一
    $last_sunday = date('Y-m-d', strtotime('-1 sunday', time())); // 上周日
    if(isset($argv[1])) {
        //重新生成前一周的紧急数据，包含 开始天，包含 结束天
        $start_time = $argv[1];
        if($argv[1] > $last_monday) {
            exit("起始时间不能大于上周一");
        }
        if (date('w',strtotime($argv[1]))!=1 or date('w',strtotime($argv[2]))!=0 or ($argv[2]-$argv[1]!=7)){
            exit("开始日期、结束日期必须以周为单位");
        }

        if(isset($argv[2]) && !empty($argv[2])) {
            //如果结束时间大于今天，那么将上一周的最后一天作为结束天
            if($argv[2] > $last_sunday) {
                $argv[2] = $last_sunday;
            }
            $stop_time = $argv[2];
        }else {
            $stop_time = $last_sunday;
        }

        countall($start_time, $stop_time);
    }
    else{
        $start_time=$last_monday;
        $stop_time=$last_sunday;
        countall($start_time, $stop_time);
    }
}

/**
 *
 * 根据条件，统计数据（体育电竞、泛亚电竞）
 *
 * @param date $StartTime
 */
function countall($StartTime, $EndTime){
    global $dbMasterLink, $dbLink, $sportDjLevelSalaryInfo;

    $StartTime = '2020-04-01';

    @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/sport_dj_week_jinji.log');
    @error_log('--------------- 更新体育电竞晋级信息开始'.PHP_EOL, 3, '/tmp/group/sport_dj_week_jinji.log');

    $sql = "select userid, username, `level` from ".DBPREFIX."sport_dj_jinji_salary";
    $result_data = mysqli_query($dbLink,$sql);
    $cou=mysqli_num_rows($result_data);
    if ($cou>0){
        $jinji_salary_data=[];
        while ($row = mysqli_fetch_assoc($result_data)){
            $jinji_salary_data[$row['userid'].'_'.$row['level']]=$row;
        }
    }

    $sql = "select userid, username, sum(total) as total from ".DBPREFIX."sport_dj_week_report 
            where count_date_end<='".$EndTime."'
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

        $start=0;
        $start2=0;
        $datetime=date('Y-m-d H:i:s');
        $sql = "INSERT INTO ".DBPREFIX."sport_dj_jinji_salary (`userid`,`username`,`level`,Alias,Agents,Phone,`EventName`,`count_date_start`,`count_date_end`,`total`,`gift_gold`,`status`,`created_at`) VALUES ";
        foreach ($jinji_data as $k => $v){

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
                die('会员体育电竞数据晋级失败！ ' . mysqli_error($dbMasterLink));
            }else {
                $result=mysqli_query($dbMasterLink, "COMMIT");
                die('会员体育电竞数据晋级成功！ ');
            }
        }
        else{
            die('会员体育电竞数据晋级失败！没有会员升级 ');
        }

    }
    else{
        die('没有晋级数据 ');
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
