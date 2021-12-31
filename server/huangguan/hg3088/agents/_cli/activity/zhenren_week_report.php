<?php
/*
 * 	生成真人的每周报表
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

//    @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/zhenren_jinji.log');
//    @error_log('--------------- 更新真人晋级信息开始'.PHP_EOL, 3, '/tmp/group/zhenren_jinji.log');

    //首先，从返水报表里面清除掉数据，再重新计算
    $sql = " DELETE from ".DBPREFIX."zhenren_week_report where count_date_start >= '$StartTime' and count_date_end <= '$EndTime' and is_free='0'";
    $result=mysqli_query($dbMasterLink, $sql);
    $sql = " DELETE from ".DBPREFIX."zhenren_week_report_flag where count_date_start >= '$StartTime' and count_date_end <= '$EndTime' ";
    $result=mysqli_query($dbMasterLink, $sql);


    // AG真人视讯数据
    $sql = "select userid, username, sum(valid_money) as total from ".DBPREFIX."ag_projects_history_report 
            where M_Date>='".$StartTime."' and M_Date<='".$EndTime."' and `game_code`='BR' 
            group by userid";
    $result_data_ag = mysqli_query($dbLink,$sql);
    $cou_ag=mysqli_num_rows($result_data_ag);
    if ($cou_ag>0){
        $data_ag=[];
        while ($row = mysqli_fetch_assoc($result_data_ag)){
            $data_ag[$row['userid']]=$row;
        }
    }

//    @error_log('--------------- AG真人视讯数据已捞出，共'.count($data_ag).'条'.PHP_EOL, 3, '/tmp/group/zhenren_jinji.log');

    // OG视讯数据 total_bet 投注，total_cellscore 有效投注
    $og_sql = "SELECT `userid`, `username`, SUM(`total_cellscore`) AS `total` 
                    FROM " . DBPREFIX . "og_history_report 
                    WHERE `count_date` >= '" . $StartTime . "' and `count_date` <= '" . $EndTime . "' GROUP BY `userid`";
    $result_data_og = mysqli_query($dbLink, $og_sql);
    $cou_og=mysqli_num_rows($result_data_og);
    if ($cou_og>0){
        $data_og=[];
        while ($row = mysqli_fetch_assoc($result_data_og)){
            $data_og[$row['userid']]=$row;
        }
    }
//    @error_log('--------------- OG真人视讯数据已捞出，共'.count($data_ag).'条'.PHP_EOL, 3, '/tmp/group/zhenren_jinji.log');

    // BBIN视讯数据 total_bet 投注，total_cellscore 有效投注
    $bbin_sql = "SELECT `userid`, `username`, SUM(`total_cellscore`) AS `total`
                    FROM " . DBPREFIX . "jx_bbin_history_report 
                    WHERE `count_date` >= '" . $StartTime . "' and `count_date` <= '" . $EndTime . "' GROUP BY `username`";
    $result_data_bbin = mysqli_query($dbLink, $bbin_sql);
    $cou_bbin=mysqli_num_rows($result_data_bbin);
    if ($cou_bbin>0) {
        $data_bbin = [];
        while ($row = mysqli_fetch_assoc($result_data_bbin)) {
            $data_bbin[$row['userid']] = $row;
        }
    }
//    @error_log('--------------- BBIN视讯数据已捞出，共'.count($data_bbin).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

    // 统计真人晋级礼金用户信息 并更新
    // 统计AG视讯会员
    if (count($data_ag)>0){
        foreach ($data_ag as $k => $v){
            $jinji_data[$k]['userid'] = $v['userid'];
            $jinji_data[$k]['total'] = $v['total'] + $data_og[$k]['total'] + $data_bbin[$k]['total'];
            $jinji_data[$k]['total_ag'] = $v['total'];
            $jinji_data[$k]['total_og'] = 0 + $data_og[$k]['total'];
            $jinji_data[$k]['total_bbin'] = 0 + $data_bbin[$k]['total'];
            unset($data_ag[$k]);
            unset($data_og[$k]);
            unset($data_bbin[$k]);
        }
    }

    // 统计OG视讯会员
    if (count($data_og)>0){
        foreach ($data_og as $k => $v){
            $jinji_data[$k]['userid'] = $v['userid'];
            $jinji_data[$k]['total'] = $v['total'] + $data_bbin[$k]['total'];
            $jinji_data[$k]['total_ag'] = 0;
            $jinji_data[$k]['total_og'] = $v['total'];
            $jinji_data[$k]['total_bbin'] = 0 + $data_bbin[$k]['total'];
            unset($data_og[$k]);
            unset($data_bbin[$k]);
        }
    }

    // 统计BBIN视讯会员
    if (count($data_bbin)>0){
        foreach ($data_bbin as $k => $v){
            $jinji_data[$k]['userid'] = $v['userid'];
            $jinji_data[$k]['total'] = $v['total'];
            $jinji_data[$k]['total_ag'] = 0;
            $jinji_data[$k]['total_og'] = 0;
            $jinji_data[$k]['total_bbin'] = $v['total'];
            unset($data_bbin[$k]);
        }
    }

//    @error_log('--------------- 数据统计完成，共'.count($jinji_data).'条-------'.PHP_EOL, 3, '/tmp/group/zhenren_jinji.log');

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
        $sql = "REPLACE INTO ".DBPREFIX."zhenren_week_report (userid, username, total, total_ag, total_og, total_bbin, count_date_start, count_date_end, created_at) VALUES ";
        foreach ($jinji_data as $k => $v){

            if ($start==0){
                $sql .= "('".$v['userid']."','".$v['username']."','".$v['total']."','".$v['total_ag']."','".$v['total_og']."','".$v['total_bbin']."','".$v['count_date_start']."','".$v['count_date_end']."','".$v['created_at']."')";
            }else{
                $sql .= ",('".$v['userid']."','".$v['username']."','".$v['total']."','".$v['total_ag']."','".$v['total_og']."','".$v['total_bbin']."','".$v['count_date_start']."','".$v['count_date_end']."','".$v['created_at']."')";
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
            $sql = "insert into ".DBPREFIX."zhenren_week_report_flag(count_date_start, count_date_end, flag) value('$StartTime', '$EndTime', 2) ";
        }else {
            $sql = "insert into ".DBPREFIX."zhenren_week_report_flag(count_date_start, count_date_end, flag) value('$StartTime', '$EndTime', 1) ";
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
