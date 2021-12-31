<?php
/*
 * 	生成体育和电竞的每周报表
 *
 * 	1，	只支持cli模式下的运行。
 *  2， 每周一20点统计最新的晋级数据
 * */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
require CONFIG_DIR."/app/agents/include/config.inc.php";

$sAg_prefix = $agsxInitp['data_api_cagent'].$agsxInitp['data_api_user_prefix'].'_'; // AG用户名前缀 BT5A_，返水需要转为体育的用户名

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
 * 根据条件，统计数据（AG真人、OG真人、BBIN真人）
 *
 * @param date $StartTime
 */
function countall($StartTime, $EndTime, $reGeneral=false){
    global $dbMasterLink, $dbLink;

    if ($StartTime<'2020-05-01'){
        exit("起始时间从5月1号开始。当前时间起始时间为：".$StartTime);
    }

    // 声明变量
    $data_ag = $data_og = $data_bbin = [];

    @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/sport_dj_week_report.log');
    @error_log('--------------- 体育电竞周报表开始'.PHP_EOL, 3, '/tmp/group/sport_dj_week_report.log');

    //首先，从返水报表里面清除掉数据，再重新计算
    $sql = " DELETE from ".DBPREFIX."sport_dj_week_report where count_date_start >= '$StartTime' and count_date_end <= '$EndTime' and is_free='0'";
    $result=mysqli_query($dbMasterLink, $sql);
    $sql = " DELETE from ".DBPREFIX."sport_dj_week_report_flag where count_date_start >= '$StartTime' and count_date_end <= '$EndTime' ";
    $result=mysqli_query($dbMasterLink, $sql);

    // 体育 电竞
    $sql = "select userid, username, sum(valid_money) as total from ".DBPREFIX."web_report_history_report_data 
            where M_Date >= '".$StartTime."' and M_Date<='".$EndTime."' group by userid ";
    $res_hg = mysqli_query($dbLink, $sql);
    $cou_hg = mysqli_num_rows($res_hg);
    if ($cou_hg>0){
        $data_hg=[];
        while ($row = mysqli_fetch_assoc($res_hg)){
            $data_hg[$row['userid']] = $row;
        }
    }

    // 泛亚电竞数据 total_bet 投注，total_cellscore 有效投注
    $avia_sql = "SELECT `userid`, `username`, SUM(`total_cellscore`) AS `total` 
                    FROM " . DBPREFIX . "avia_history_report 
                    WHERE `count_date` >= '" . $StartTime . "' and `count_date` <= '" . $EndTime . "' GROUP BY `userid`";
    $result_data_avia = mysqli_query($dbLink, $avia_sql);
    $cou_avia=mysqli_num_rows($result_data_avia);
    if ($cou_avia>0){
        $data_avia=[];
        while ($row = mysqli_fetch_assoc($result_data_avia)){
            $data_avia[$row['userid']]=$row;
        }
    }

    // 雷火电竞数据 total_bet 投注，total_cellscore 有效投注
    $fire_sql = "SELECT `userid`, `username`, SUM(`total_cellscore`) AS `total` 
                    FROM " . DBPREFIX . "fire_history_report 
                    WHERE `count_date` >= '" . $StartTime . "' and `count_date` <= '" . $EndTime . "' GROUP BY `userid`";
    $result_data_fire = mysqli_query($dbLink, $fire_sql);
    $cou_fire=mysqli_num_rows($result_data_fire);
    if ($cou_fire>0){
        $data_fire=[];
        while ($row = mysqli_fetch_assoc($result_data_fire)){
            $data_fire[$row['userid']]=$row;
        }
    }

    // 统计体育电竞晋级礼金用户信息 并更新
    // 统计体育会员
    if (count($data_hg)>0){
        foreach ($data_hg as $k => $v){
            $jinji_data[$k]['userid'] = $v['userid'];
            $jinji_data[$k]['total'] = $v['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'];
            $jinji_data[$k]['total_hg'] = $v['total'];
            $jinji_data[$k]['total_avia'] = 0 + $data_avia[$k]['total'];
            $jinji_data[$k]['total_fire'] = 0 + $data_fire[$k]['total'];
            unset($data_hg[$k]);
            unset($data_avia[$k]);
            unset($data_fire[$k]);
        }
    }

    // 统计泛亚电竞会员
    if (count($data_avia)>0){
        foreach ($data_avia as $k => $v){
            $jinji_data[$k]['userid'] = $v['userid'];
            $jinji_data[$k]['total'] = $v['total'] + $data_fire[$k]['total'];
            $jinji_data[$k]['total_hg'] = 0;
            $jinji_data[$k]['total_avia'] = $v['total'];
            $jinji_data[$k]['total_fire'] = 0 + $data_fire[$k]['total'];
            unset($data_avia[$k]);
            unset($data_fire[$k]);
        }
    }

    // 统计雷火电竞会员
    if (count($data_fire)>0){
        foreach ($data_fire as $k => $v){
            $jinji_data[$k]['userid'] = $v['userid'];
            $jinji_data[$k]['total'] = $v['total'] + $data_fire[$k]['total'];
            $jinji_data[$k]['total_hg'] = 0;
            $jinji_data[$k]['total_fire'] = 0 + $v['total'];
            unset($data_avia[$k]);
        }
    }

    // 拼凑 username
    $aUserids = array_keys($jinji_data);
    $sUserids = implode(',',$aUserids);
    $sql = "SELECT `ID`,`username` FROM " . DBPREFIX . "web_member_data where ID in ($sUserids)";
    $result = mysqli_query($dbLink, $sql);
    $cou=mysqli_num_rows($result);
    if ($cou>0){
        while ($row = mysqli_fetch_assoc($result)){
            $jinji_data[$row['ID']]['username']=$row['username'];
            $jinji_data[$row['ID']]['count_date_start']=$StartTime;
            $jinji_data[$row['ID']]['count_date_end']=$EndTime;
            $jinji_data[$row['ID']]['created_at']=date('Y-m-d H:i:s');
        }
    }

    $cou = count($jinji_data);
    if ($cou>0){

        $result=mysqli_query($dbMasterLink, "START TRANSACTION");
        if (!$result) {
            die('事务开启失败！ ' . mysqli_error($dbMasterLink));
        }
        $start=0;
        $sql = "REPLACE INTO ".DBPREFIX."sport_dj_week_report (userid, username, total, total_hg, total_avia, total_fire, count_date_start, count_date_end, created_at) VALUES ";
        foreach ($jinji_data as $k => $v){

            if ($start==0){
                $sql .= "('".$v['userid']."','".$v['username']."','".$v['total']."','".$v['total_hg']."','".$v['total_avia']."','".$v['total_fire']."','".$v['count_date_start']."','".$v['count_date_end']."','".$v['created_at']."')";
            }else{
                $sql .= ",('".$v['userid']."','".$v['username']."','".$v['total']."','".$v['total_hg']."','".$v['total_avia']."','".$v['total_fire']."','".$v['count_date_start']."','".$v['count_date_end']."','".$v['created_at']."')";
            }

            $start++;
        }

        if ($start>0){
            $result=mysqli_query($dbMasterLink, $sql);
            if (!$result){
                $result=mysqli_query($dbMasterLink, "ROLLBACK");
                die('计算报表数据失败！ ' . mysqli_error($dbMasterLink));
            }
        }

        if($reGeneral) {
            $sql = "insert into ".DBPREFIX."sport_dj_week_report_flag(count_date_start, count_date_end, flag) value('$StartTime', '$EndTime', 2) ";
        }else {
            $sql = "insert into ".DBPREFIX."sport_dj_week_report_flag(count_date_start, count_date_end, flag) value('$StartTime', '$EndTime', 1) ";
        }
        $result=mysqli_query($dbMasterLink, $sql);
        if(!$result) {
            $result=mysqli_query($dbMasterLink, "ROLLBACK");
            die('插入计算成功表示符失败！' . mysqli_error($dbMasterLink));
        }else {
            $result=mysqli_query($dbMasterLink, "COMMIT");
        }
    }

}
