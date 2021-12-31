<?php

/* 体育彩票活动类 */
class ActivityLottery{

    /**
     * 获取有效投注（彩票）
     *
     * 2,45,46   //2 欢乐生肖
     * 3,47,48   // 3 广东快乐十分 47 重庆幸运农场
     * 168   //幸运飞艇
     * 222    //极速飞艇
     * 51   //北京赛车PK拾
     * 189  //极速赛车
     * 207  //分分彩
     * 407  // 三分彩 时时彩
     * 507  // 五分彩 时时彩
     * 607  // 腾讯二分彩 时时彩
     * 69   //香港六合彩
     * 159  //江苏快三
     * 304  //PC蛋蛋
     * 384  //极速快三
     *
     * */
    public function get_lottery_valid_money($gamecode, $username, $aCp_default=0){

        global $aCp_default, $userid, $username;

        $h = date('G');
        // -------------------------------------------------------------------------------------------------------昨日有效金额-彩票，2点之前捞注单表、2点以后捞历史报表Start
//    $time['begin_time'] = date('Y-m-d 00:00:00',strtotime('-1 day')); // 北京时间 17:13 相当于 05:13
//    $time['end_time'] = date("Y-m-d 23:59:59", strtotime('-1 day'));  //昨日开始时间截止时间:2019-12-21 00:00:00----2019-12-21 23:59:59

        //开始时间截止时间:2019-12-21 00:00:00----2019-12-22 00:00:00
        $yestoday = date('Y-m-d 00:00:00', strtotime('-1 day')); // 昨日美东时间 (比北京时间慢12h)
        $start_day_cp = strtotime($yestoday);
        $end_day_cp = $start_day_cp + 60*60*24;
        $cpDbLink = @mysqli_connect($aCp_default['host'],$aCp_default['user'],$aCp_default['password'],$aCp_default['dbname'],$aCp_default['port']) or die("mysqli connect error".mysqli_connect_error());
        if ($h >= 2){
            $sql_cp = "select sum(valid_money) as valid_money from gxfcy_history_bill_report_less_12hours where username = '$username' AND game_code = '$gamecode' AND  bet_time BETWEEN '".$start_day_cp."' and '".$end_day_cp."' ";
            $res_cp = mysqli_query($cpDbLink, $sql_cp);
            $row_cp = mysqli_fetch_assoc($res_cp);
            $cp_valid_money = $row_cp['valid_money'];
        }else{
            $sql_cp = "select sum(valid_money) as valid_money from gxfcy_bill where `count`=1 and username = '$username' AND game_code = '$gamecode' and bet_time BETWEEN '" . $start_day_cp . "' and '" . $end_day_cp . "' ";
            $res_cp = mysqli_query($cpDbLink, $sql_cp);
            $row_cp = mysqli_fetch_assoc($res_cp);
            $cp_valid_money = $row_cp['valid_money'];
        }
        // ----------------------------------------------------------------------------------------------------昨日有效金额-彩票，2点之前捞注单表、2点以后捞历史报表End

        // 有效投注（体育彩票）
        $valid_money = sprintf("%.2f" , $cp_valid_money);

        $data['valid_money'] = $valid_money>0?$valid_money:0;
        return $data;
    }

    /*
 *  获取国民三方彩票的有效投注
 * $type 彩种种类：wfc 五分, sfc 三分，ffc 分分
 * 官方：28 五分时时彩，16 三分时时彩，13 分分时时彩，50 五分快三，51 三分快三
 * 信用：6 五分时时彩，2 分分时时彩，5 三分时时彩，73 五分快三，74 三分快三，75 一分快三
 * $dateStart 开始时间，$dateEnd 结束时间
 * $userId 用户ID
 * */
    function getLotteryValidMonery($type,$dateStart,$dateEnd,$userId = ''){
        global $dbLink;
        $memDataCount = $data_history_ssc = $data_history_project = $data_history_trace = [];
        $sWhere_thirdcp_gf = '';
        $sWhere_thirdcp_xy = '';
        $sWhere = ' 1 ';
        $sWhere_thirdcp = $sWhere. " AND hg_uid = '$userId'";

        switch ($type){
            case 'wfc':
                $sWhere_thirdcp_gf = "AND lottery_id in('28','50')";
                $sWhere_thirdcp_xy = "AND type in('6','73')";
                break;
            case 'sfc':
                $sWhere_thirdcp_gf = "AND lottery_id in('16','51')";
                $sWhere_thirdcp_xy = "AND type in('5','74')";
                break;
            case 'ffc':
                $sWhere_thirdcp_gf = "AND lottery_id in('13')";
                $sWhere_thirdcp_xy = "AND type in('2','75')";
                break;
        }


        // 第三方彩票信用主数据（报表数据）
        // status 0: 正常；1：已撤销；2：未中奖；3：已中奖；4：和局；5：系统撤销
        $sql = "SELECT username, SUM(`money`) AS `valid_money`
            FROM " . DBPREFIX . "web_third_ssc_data
            WHERE $sWhere_thirdcp $sWhere_thirdcp_xy AND `status` NOT IN (5) AND `counted_at` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `uid`";
        $res_ssc = mysqli_query($dbLink, $sql);
        $cou_ssc = mysqli_num_rows($res_ssc);
        if ($cou_ssc > 0) {
            while ($row_ssc = mysqli_fetch_assoc($res_ssc)){
                $data_history_ssc['valid_money'] += $row_ssc['valid_money'];
                if( false !== strpos($row_ssc['username'], '_')){ // 拉取报表中的用户名有带前缀也有不带前缀的处理
                    $row_ssc['username'] = substr($row_ssc['username'],strripos($row_ssc['username'],"_") + 1);
                }
                $mem_ssc[] = $row_ssc['username'];
            }
        }

        // 第三方彩票官方主数据（报表数据）
        // status 0: 正常；1：已撤销；2：未中奖；3：已中奖；4：已派奖；5：系统撤销
        $sql = "SELECT username, SUM(`amount`) AS `valid_money`
            FROM " . DBPREFIX . "web_third_projects_data
            WHERE $sWhere_thirdcp $sWhere_thirdcp_gf AND `status` NOT IN (1, 5) AND `counted_at` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `user_id`";
        $res_project = mysqli_query($dbLink, $sql);
        $cou_project = mysqli_num_rows($res_project);
        if ($cou_project > 0) {
            while ($row_project = mysqli_fetch_assoc($res_project)){
                $data_history_project['valid_money'] += $row_project['valid_money'];
                if( false !== strpos($row_project['username'], '_')){ // 拉取报表中的用户名有带前缀也有不带前缀的处理
                    $row_project['username'] = substr($row_project['username'],strripos($row_project['username'],"_") + 1);
                }
                $mem_project[] = $row_project['username'];
            }
        }

        // 第三方彩票官方追号主数据（报表数据）
        // status 0: 进行中；1：已完成；2：会员终止；3：管理员终止；4：系统终止
        $sql = "SELECT username, SUM(`finished_amount`) AS `valid_money`
            FROM " . DBPREFIX . "web_third_traces_data
            WHERE $sWhere_thirdcp $sWhere_thirdcp_gf AND `status` NOT IN (2,3,4,5) AND `bought_at` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `user_id`";
        $res_trace = mysqli_query($dbLink, $sql);
        $cou_trace = mysqli_num_rows($res_trace);
        if ($cou_trace > 0) {
            while ($row_trace = mysqli_fetch_assoc($res_trace)){
                $data_history_trace['valid_money'] += $row_trace['valid_money'];
                if( false !== strpos($row_trace['username'], '_')){ // 拉取报表中的用户名有带前缀也有不带前缀的处理
                    $row_trace['username'] = substr($row_trace['username'],strripos($row_trace['username'],"_") + 1);
                }
                $mem_trace[] = $row_trace['username'];
            }
        }

        $sscValid = isset($data_history_ssc['valid_money']) ? $data_history_ssc['valid_money'] : 0;
        $projectValid = isset($data_history_project['valid_money']) ? $data_history_project['valid_money'] : 0;
        $traceValid = isset($data_history_trace['valid_money']) ? $data_history_trace['valid_money'] : 0;
        $memDataCount['valid_money'] = $sscValid+$projectValid+$traceValid; // 所有有效投注
        return $memDataCount;

    }


    /**
     * 时时彩系列
     * @param $numBets
     * @param $gameCode  207 => 分分彩, 407 => 三分彩, 507 => 五分彩
     *
     * @return string
     */
    public function LotterySeries($numBets , $gameCode){
        $tenThousand = 10000;
        if(TPL_FILE_NAME=='0086' || TPL_FILE_NAME=='6668') {
            $jsSSC_code = 207;//分分彩
            $sfcSSC_code = 407;// 三分彩 时时彩
            $wfcSSC_code = 507;// 五分彩 时时彩
        }else{
            $jsSSC_code = 'ffc';//分分彩
            $sfcSSC_code = 'sfc';// 三分彩
            $wfcSSC_code = 'wfc';// 五分彩
        }
        if($numBets >= 3000 && $numBets < 5000) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "8";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "7";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "6";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 5000 && $numBets < 1*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "11";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "10";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "9";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 1*$tenThousand && $numBets < 3*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "18";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "17";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "16";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 3*$tenThousand && $numBets < 5*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "45";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "36";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "33";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 5*$tenThousand && $numBets < 8*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "75";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "60";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "55";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 8*$tenThousand && $numBets < 10*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "120";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "96";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "88";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 10*$tenThousand && $numBets < 30*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "150";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "120";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "110";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 30*$tenThousand && $numBets < 50*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "450";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "360";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "330";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 50*$tenThousand && $numBets < 80*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "750";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "600";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "550";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 80*$tenThousand && $numBets < 100*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "1200";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "960";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "880";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 100*$tenThousand && $numBets < 300*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "1500";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "1200";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "1100";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 300*$tenThousand && $numBets < 500*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "4500";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "3600";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "3300";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 500*$tenThousand && $numBets < 800*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "7500";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "6000";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "5500";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 800*$tenThousand && $numBets < 1000*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "12000";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "9600";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "8800";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } elseif($numBets >= 1000*$tenThousand) {
            switch ($gameCode){
                case $jsSSC_code:
                    $result['kingGold'] = "15000";$result['gameCode'] = $jsSSC_code;
                    break;
                case $sfcSSC_code:
                    $result['kingGold'] = "12000";$result['gameCode'] = $sfcSSC_code;
                    break;
                case $wfcSSC_code:
                    $result['kingGold'] = "11000";$result['gameCode'] = $wfcSSC_code;
                    break;
            }
        } else{ // 不满足条件 回馈金额0
            $result['kingGold'] = "0";$result['gameCode'] = "0";
        }
        return $result;
    }

}

?>