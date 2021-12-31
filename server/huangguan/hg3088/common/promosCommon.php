<?php
/*
 * 优惠活动公用
 * */


/*
 *  返回游戏分类
 * */
function returnGameType(){
    $arr = [
       'all'=>'全部' ,
       'sport'=>'体育' ,
       'lottery'=>'彩票' ,
       'live'=>'视讯' ,
       'chess'=>'棋牌' ,
       'game'=>'电子' ,
       'bygame'=>'捕鱼' ,
       'gaming'=>'电竞' ,
       //'avia'=>'泛亚电竞'
    ];
    return $arr;
}

/*
 *  返回优惠信息
 * */
function returnPromoSet($type){
    global $dbLink;
    $resdata =[];
    $table = DBPREFIX."web_promos_rule";
    $seaksql = "SELECT `title`,`leader`,`statisticsDayType`,`statisticsDay`,`receiveDayType`,`receiveDay`,`receiveTime`,`promolqDatetimeTip`,`Payway`,`discounType`,`depositLimits`,`depositDays`,`depositDaysFirst`,`validBet`,`bonus`,`usdtbonus`,`profitable`,`gameType` ,`gameTypeDetails`,`gameTypeChoose`,`mergeOrSplit` FROM " . $table . " WHERE `name`='$type'";
    // echo $seaksql;
    $result = mysqli_query($dbLink, $seaksql);
    if($result) {
        while ($aRow = mysqli_fetch_assoc($result)) {
            $resdata[] = $aRow;
        }
    }
    return $resdata;
}

/*
 *  统计用户存款
 * $payway 存款方式
 * $discountype 类型
 * */
function getUserDeposit($user_id,$time,$payway,$discountype,$depositdaysfirst){
    global $dbLink;
    if($payway =='ALL'){ // 所有存款方式
        $payway = "('O','N','W','A','G')";
    }else{
        $payway = "('$payway')";
    }

    $begin_time = $time['begin_time'];
    $end_time =  $time['end_time'];
    $timeWhere = " AND `AddDate`>= '$begin_time' AND `AddDate`<= '$end_time'" ; //存款时间范围

    if($payway == "('U')") {// USDT存款
        $gs_where = " AND Payway in ('N') AND Type='S' AND Checked=1 AND Bank_Address ='TRC20'";
    }else{
        $gs_where = " AND Payway in $payway AND Type='S' AND Checked=1";  // 公司卡存款
    }

    if($discountype){
        $gs_where .= "AND discounType='$discountype'";
    }

    if($depositdaysfirst == 1) { //只统计当日第一笔
        $Gold = " Gold as Gold";
        $gs_where .= " ORDER BY Date  limit 1";
    }else{
        $Gold = " sum(Gold) as Gold";
    }
    //公司卡存款
    $sql = "select $Gold from ".DBPREFIX."web_sys800_data where userid='$user_id' $timeWhere $gs_where";
    // echo $sql;
    $query = mysqli_query($dbLink, $sql);
    $cou_res = mysqli_num_rows($query);
    if($cou_res > 0) {
        $result = mysqli_fetch_assoc($query); //公司存款额
        $money = !empty($result['Gold']) ? sprintf("%01.2f",$result['Gold']):0;
    }

    return $money;  // 当前日期会员公司存款额
}
/*
 *  查询用户存款天数
 * */
function returnDepositDays($user_id, $time, $flag=null){
    global $dbLink;
    $table = DBPREFIX."web_sys800_data";
    $begin_time = $time['begin_time'];  // 上月开始时间截止时间
    $end_time = $time['end_time'];
    $ck_Where = " and `AddDate`>= '$begin_time' and `AddDate`<= '$end_time'" ; // 存款时间
    //$third_where ="AND Payway='W' AND Type='S' AND Checked =1 AND PayType>0";  // 第三方存款 discounType='0' User=''
    $kscz_where = " AND Payway='W' AND Type='S' AND Checked =1 AND discounType in (0,9)";  // 快速充值  审核人User!='' discounType=9
    $gs_where = " AND Payway='N' AND Type='S' AND Checked=1";  // 公司卡存款
    $usdt_where = " AND Payway='N' AND Type='S' AND Checked=1 AND Bank_Address ='TRC20'";  // USDT存款
    // 人工存款 Payway=W  'discounType' , array('1','2','3','4','5','7') Checked ==1  不算存款天数

    !empty($flag) ?  $ck_Where .= " AND Gold >= '{$flag}' ": ''; // 签到次数

    // 第三方快速充值
    $ks_sql = "select DISTINCT AddDate from ".$table." where userid='$user_id' $ck_Where $kscz_where";
    $third_sql = mysqli_query($dbLink, $ks_sql);
    $thridDate = array();
    while($memThridRow = mysqli_fetch_assoc($third_sql)){
        $thridDate[] = $memThridRow['AddDate'];
    }
    //公司卡存款
    $gs_sql = "select DISTINCT AddDate from ".$table." where userid='$user_id' $ck_Where $gs_where";
    $company_sql = mysqli_query($dbLink, $gs_sql);
    //@error_log("gs_sql:".$gs_sql.PHP_EOL,  3,  '/tmp/aaa.log');
    $CompanyDate = array();
    while($memCompanyRow = mysqli_fetch_assoc($company_sql)){
        $CompanyDate[] = $memCompanyRow['AddDate'];
    }

    //USDT存款
    $usdt_sql = "select DISTINCT AddDate from ".$table." where userid='$user_id' $ck_Where $usdt_where";
    $usdt_query = mysqli_query($dbLink, $usdt_sql);
    $UsdtDate = array();
    while($memUsdtRow = mysqli_fetch_assoc($usdt_query)){
        $UsdtDate[] = $memUsdtRow['AddDate'];
    }

    $depositReturnDays['depositusdt'] = count($UsdtDate); //usdt充值天数
    $depositReturnDays['depositCount'] = count(array_unique(array_merge($thridDate,$CompanyDate))); // 存款总天数去重
    return $depositReturnDays;
}

/**
 * $type
 * rebate : 查询返水（时时返水、天天返水）
 * youhui : 查询充值优惠总额
 * caijin : 查询活动彩金总额
 */
function getUserCashGold($memberAddDate,$userid, $time,$type){
    global $dbLink;
    $table = DBPREFIX."web_sys800_data";
    $gold = 0;
    $beginTime = $time['begin_time'];
    $endTime = $time['end_time'];
    $diszd = $disSql ='';
    switch ($type){
        case 'rebate': // 返水
            $diszd = " sum(Gold) as Gold,Bank,DepositAccount ";
            $disSql .= " AND `Type`='R' AND `Checked`=1 ";
            break;
        case 'youhui': // 优惠总额
            $diszd .= " currency_after,moneyf,Bank,DepositAccount ";
            $disSql .= " AND `Type`='S' AND Payway='N' AND Preferential=1 AND Checked=1 ";
            break;
        case 'caijin': // 彩金总额
            $diszd = " sum(Gold) as Gold,Bank,DepositAccount ";
            $disSql .= " AND `Type`='S' AND Checked=1 AND `Payway` IN ('O', 'G') AND `discounType` = 0 ";
            break;
    }
    $sql = "select ".$diszd." from ".$table."  where userid=$userid ".$disSql." AND AddDate >= '".$beginTime."' and AddDate<='".$endTime."'";
   // echo $sql.'<br>';
    $res = mysqli_query($dbLink,$sql);
    $cou = mysqli_num_rows($res);
    if ($cou>0){
        while ($row = mysqli_fetch_assoc($res)){
            if($type=='youhui'){  // 优惠总额
                $Gold_no_youhui = $row['currency_after'] - $row['moneyf']; //存款金额（无优惠）
                $aDepositAccount = explode('-',$row['DepositAccount']);
                $youhui_row = preferentialGold($Gold_no_youhui, $memberAddDate,$row['Bank'],$aDepositAccount[3]);
                $gold += $youhui_row;
            }else{
                $gold += $row['Gold'];
            }

        }
    }
    return $gold;
}

/*
 *  查询用户报表输赢，有效投注
 *  支持单独查询游戏
 *  $paramData : 活动返回的数据
 * */

function getUserGameReport($userid='', $username, $time,$paramData,$gametypesecdetails=''){
    global $dbLink, $database, $aCp_default;
    $dUserWin = $userValid = 0;
    $begin_time = $time['begin_time'];
    $end_time = $time['end_time'];
    $gameType = $paramData['gameType']; // 统计游戏类型
    $gameTypeDetails = explode(';',$paramData['gameTypeDetails']);
    $gameTypeChoose = $paramData['gameTypeChoose'];
    $tjdaytype = $paramData['statisticsDayType']; // 统计时间类型，查询历史报表：lastMon 上月，thisMon 本月，lastWeek 上周，thisWeek 本周； 查询注单报表 ：yesterday 昨天，today 今天
    $mergeorsplit = $paramData['mergeOrSplit']; // 是否需要分开统计，yes 需要分开统计,not 不需要分开
    $type ='';
    if($tjdaytype=='yesterday' || $tjdaytype=='today' || $tjdaytype=='other'){
        $type ='cur';
    }
   // echo $gameType.'##'.$gameTypeChoose;
  // echo $gametypesecdetails;

    // 体育输赢
    if($gameType=='all' || ($gameType=='sport' && $gameTypeChoose=='main') || ($gameType!='sport' && $gameTypeChoose=='over') ){
        if($gameType=='all' || (in_array('hgsport',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('hgsport',$gameTypeDetails) && $gameTypeChoose=='over')){
            $sql_hg = "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from ".DBPREFIX."web_report_history_report_data  
                    where userid=$userid  AND M_Date >= '{$begin_time}' and M_Date<='{$end_time}' ";
            if($type =='cur'){
                $sql_hg = "SELECT count(1) AS count_pay, sum(BetScore) AS total, sum(VGOLD) AS valid_money, sum(M_Result) AS user_win FROM ".DBPREFIX."web_report_data 
                           WHERE userid = '$userid' AND updateTime BETWEEN '".$begin_time."' and '".$end_time."' AND `checked` = 1 AND `testflag` = 0 AND `Cancel` = 0 ";
            }
            $res_hg = mysqli_query($dbLink, $sql_hg);
            $cou_hg = mysqli_num_rows($res_hg);
            if ($cou_hg>0){
                $row_hg = mysqli_fetch_assoc($res_hg);
                $dUserWin += $row_hg['user_win'];
                $userValid += $row_hg['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('bbinsport',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('bbinsport',$gameTypeDetails) && $gameTypeChoose=='over')){
            // BBIN体育输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "jx_bbin_history_report 
                    WHERE  userid=$userid AND GameKind=1 AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`Commissionable`) AS `valid_money`, SUM(`BetAmount`) AS `total`, SUM(`Payoff`) AS `user_win`  FROM " . DBPREFIX . "jx_bbin_projects 
                        WHERE userid=$userid AND GameKind=1 AND `WagersDate` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_bbin = mysqli_query($dbLink, $sql);
            $cou_bbin = mysqli_num_rows($res_bbin);
            if ($cou_bbin > 0) {
                $row_bbin = mysqli_fetch_assoc($res_bbin);
                $dUserWin += $row_bbin['user_win'];
                $userValid += $row_bbin['valid_money']; // 有效投注
            }

        }
    }

    if($gameType=='all' || ($gameType=='lottery' && $gameTypeChoose=='main') || ($gameType!='lottery' && $gameTypeChoose=='over') ){ // 彩票
        $sWhere_thirdcp_gf = '';
        $sWhere_thirdcp_xy = '';
        if(in_array(TPL_FILE_NAME, ['0086', '6668'])) {
            if($mergeorsplit=='yes'){
                switch ($gametypesecdetails){
                    case 'wfcSeries':
                        $sWhere_thirdcp_xy .= " AND game_code in('507')";
                        break;
                    case 'sfcSeries':
                        $sWhere_thirdcp_xy .= " AND game_code in('407')";
                        break;
                    case 'ffcSeries':
                        $sWhere_thirdcp_xy .= " AND game_code in('207') ";
                        break;
                }

            }
            $aCp_default = $database['cpDefault'];
            // 体育彩票输赢
            $start_day_cp = strtotime($begin_time); // 体育彩票时间需要转化为时间戳
            $end_day_cp = strtotime($end_time); // 体育彩票时间需要转化为时间戳
            $cpDbLink = @mysqli_connect($aCp_default['host'], $aCp_default['user'], $aCp_default['password'], $aCp_default['dbname'], $aCp_default['port']) or die("mysqli connect error" . mysqli_connect_error());
           // AND game_code

            $sql = "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from gxfcy_history_bill_report_less_12hours 
                   where username='{$username}' $sWhere_thirdcp_xy AND bet_time BETWEEN '" . $start_day_cp . "' and '" . $end_day_cp . "' ";
            if($type =='cur'){
                $sql = "SELECT count(1) AS count_pay, sum(drop_money) AS total, sum(valid_money) AS valid_money, sum(user_win) AS user_win FROM gxfcy_bill WHERE username='{$username}' $sWhere_thirdcp_xy AND bet_time BETWEEN '" . $start_day_cp . "' AND '" . $end_day_cp ."'";
            }
            $res_cp = mysqli_query($cpDbLink, $sql);
            $cou_cp = mysqli_num_rows($res_cp);
            if ($cou_cp > 0) {
                $row_cp = mysqli_fetch_assoc($res_cp);
                $dUserWin += $row_cp['user_win'];
                $userValid += $row_cp['valid_money']; // 有效投注
            }
        }
        else{ // 太阳城-10001、金沙-10002、威尼斯人-10003、3366-10004

            if($mergeorsplit=='yes'){
                switch ($gametypesecdetails){
                    case 'wfcSeries':
                        $sWhere_thirdcp_gf .= " AND lottery_id in('28','50') ";
                        $sWhere_thirdcp_xy .= " AND type in('6','73')";
                        break;
                    case 'sfcSeries':
                        $sWhere_thirdcp_gf .= " AND lottery_id in('16','51') ";
                        $sWhere_thirdcp_xy .= " AND type in('5','74')";
                        break;
                    case 'ffcSeries':
                        $sWhere_thirdcp_gf .= " AND lottery_id in('13') ";
                        $sWhere_thirdcp_xy .= " AND type in('2','75') ";
                        break;
                }

            }

            // 第三方彩票信用输赢 , status 0: 正常；1：已撤销；2：未中奖；3：已中奖；4：和局；5：系统撤销
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
                    FROM " . DBPREFIX . "web_third_ssc_history_report WHERE hg_uid=$userid $sWhere_thirdcp_xy AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`money`) AS `valid_money`, SUM(`money`) AS `total`, SUM(`bonus`) AS `user_win` 
                        FROM " . DBPREFIX . "web_third_ssc_data  WHERE hg_uid=$userid $sWhere_thirdcp_xy AND `status` NOT IN (5) AND `counted_at` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
           // echo $sql;
            $res_ssc = mysqli_query($dbLink, $sql);
            $cou_ssc = mysqli_num_rows($res_ssc);
            if ($cou_ssc > 0) {
                $row_ssc = mysqli_fetch_assoc($res_ssc);
                $dUserWin += $row_ssc['user_win']-$row_ssc['total'];
                $userValid += $row_ssc['valid_money']; // 有效投注
            }

            // 第三方彩票官方输赢 , status 0: 正常；1：已撤销；2：未中奖；3：已中奖；4：已派奖；5：系统撤销
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
                    FROM " . DBPREFIX . "web_third_projects_history_report 
                    WHERE hg_uid=$userid $sWhere_thirdcp_gf AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`, SUM(`prize`) AS `user_win` 
                        FROM " . DBPREFIX . "web_third_projects_data 
                        WHERE hg_uid=$userid  $sWhere_thirdcp_gf AND `status` NOT IN (1, 5) AND `counted_at` BETWEEN '{$begin_time}' AND '{$end_time}' ";
            }
            $res_project = mysqli_query($dbLink, $sql);
            $cou_project = mysqli_num_rows($res_project);
            if ($cou_project > 0) {
                $row_project = mysqli_fetch_assoc($res_project);
                $dUserWin += $row_project['user_win']-$row_project['total'];
                $userValid += $row_project['valid_money']; // 有效投注
            }

            // 第三方彩票官方追号输赢 ,status 0: 进行中；1：已完成；2：会员终止；3：管理员终止；4：系统终止
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
                    FROM " . DBPREFIX . "web_third_traces_history_report 
                    WHERE hg_uid=$userid $sWhere_thirdcp_gf AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`finished_amount`) AS `valid_money`, SUM(`finished_amount`) AS `total`, SUM(`prize`) AS `user_win` FROM " . DBPREFIX . "web_third_traces_data 
                WHERE hg_uid=$userid $sWhere_thirdcp_gf AND `status` NOT IN (2,3,4,5) AND `bought_at` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_trace = mysqli_query($dbLink, $sql);
            $cou_trace = mysqli_num_rows($res_trace);
            if ($cou_trace > 0) {
                $row_trace = mysqli_fetch_assoc($res_trace);
                $dUserWin += $row_trace['user_win']-$row_trace['total'];
                $userValid += $row_trace['valid_money']; // 有效投注
            }

        }
        if($gameType=='all' || (in_array('bbinlottery',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('bbinlottery',$gameTypeDetails) && $gameTypeChoose=='over')){
            // BBIN彩票输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "jx_bbin_history_report 
                    WHERE  userid=$userid AND GameKind=12 AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`Commissionable`) AS `valid_money`, SUM(`BetAmount`) AS `total`, SUM(`Payoff`) AS `user_win`  FROM " . DBPREFIX . "jx_bbin_projects 
                        WHERE userid=$userid AND GameKind=12 AND `WagersDate` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_bbin = mysqli_query($dbLink, $sql);
            $cou_bbin = mysqli_num_rows($res_bbin);
            if ($cou_bbin > 0) {
                $row_bbin = mysqli_fetch_assoc($res_bbin);
                $dUserWin += $row_bbin['user_win'];
                $userValid += $row_bbin['valid_money']; // 有效投注
            }

        }
    }

    if($gameType=='all' || ($gameType=='live' && $gameTypeChoose=='main') || ($gameType!='live' && $gameTypeChoose=='over')){ // 视讯
        if( $gameType=='all' || (in_array('aglive',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('aglive',$gameTypeDetails) && $gameTypeChoose=='over') ){
            // AG视讯输赢
            $sql_ag = "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(profit) as user_win from ".DBPREFIX."ag_projects_history_report 
                      where userid=$userid AND bet_time BETWEEN '{$begin_time}' and '{$end_time}' and game_code='BR'";
            if($type =='cur'){
                $sql_ag = "select count(1) AS count_pay, sum(amount) AS total, sum(valid_money) AS valid_money, sum(profit) AS user_win FROM ".DBPREFIX."ag_projects 
                            WHERE userid=$userid AND bettime BETWEEN '".$begin_time."' AND '".$end_time."' AND `type`='BR' ";
            }
            $res_ag = mysqli_query($dbLink, $sql_ag);
            $cou_ag = mysqli_num_rows($res_ag);
            if ($cou_ag>0) {
                $row_ag = mysqli_fetch_assoc($res_ag);
                $dUserWin += $row_ag['user_win'];
                $userValid += $row_ag['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('oglive',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('oglive',$gameTypeDetails) && $gameTypeChoose=='over')){
            // OG视讯输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`  FROM " . DBPREFIX . "og_history_report 
                    WHERE userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`validbet`) AS `valid_money`, SUM(`bettingamount`) AS `total`, SUM(`winloseamount`) AS `user_win` FROM " . DBPREFIX . "og_projects 
                        WHERE userid=$userid AND `md_bettingdate` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_og = mysqli_query($dbLink, $sql);
            $cou_og = mysqli_num_rows($res_og);
            if ($cou_og > 0) {
                $row_og = mysqli_fetch_assoc($res_og);
                $dUserWin += $row_og['user_win'];
                $userValid += $row_og['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('bbinlive',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('bbinlive',$gameTypeDetails) && $gameTypeChoose=='over')){
            // BBIN真人视讯输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "jx_bbin_history_report 
                    WHERE  userid=$userid AND GameKind=3 AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`Commissionable`) AS `valid_money`, SUM(`BetAmount`) AS `total`, SUM(`Payoff`) AS `user_win`  FROM " . DBPREFIX . "jx_bbin_projects 
                        WHERE userid=$userid AND GameKind=3 AND `WagersDate` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_bbin = mysqli_query($dbLink, $sql);
            $cou_bbin = mysqli_num_rows($res_bbin);
            if ($cou_bbin > 0) {
                $row_bbin = mysqli_fetch_assoc($res_bbin);
                $dUserWin += $row_bbin['user_win'];
                $userValid += $row_bbin['valid_money']; // 有效投注
            }

        }

    }
    if($gameType=='all' || ($gameType=='chess' && $gameTypeChoose=='main') || ($gameType!='chess' && $gameTypeChoose=='over')){ // 棋牌
        if($gameType=='all' || (in_array('kyqp',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('kyqp',$gameTypeDetails) && $gameTypeChoose=='over')){
            // KY输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`  FROM " . DBPREFIX . "ky_history_report 
                    WHERE userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`cellscore`) AS `valid_money`, SUM(`allbet`) AS `total`, SUM(`profit`) AS `user_win`    FROM " . DBPREFIX . "ky_projects 
                        WHERE userid=$userid AND `game_endtime` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }

            $res_ky = mysqli_query($dbLink, $sql);
            $cou_ky = mysqli_num_rows($res_ky);
            if ($cou_ky > 0) {
                $row_ky = mysqli_fetch_assoc($res_ky);
                $dUserWin += $row_ky['user_win'];
                $userValid += $row_ky['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('hgqp',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('hgqp',$gameTypeDetails) && $gameTypeChoose=='over')){
            // HGQP输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`  FROM " . DBPREFIX . "ff_history_report 
                    WHERE userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`valid_bet`) AS `valid_money`, SUM(`bet`) AS `total`, SUM(`wincoins`) AS `user_win`  FROM " . DBPREFIX . "ff_projects 
                        WHERE userid=$userid AND `game_endtime` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_hgqp = mysqli_query($dbLink, $sql);
            $cou_hgqp = mysqli_num_rows($res_hgqp);
            if ($cou_hgqp > 0) {
                $row_hgqp = mysqli_fetch_assoc($res_hgqp);
                $dUserWin += $row_hgqp['user_win'];
                $userValid += $row_hgqp['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('vgqp',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('vgqp',$gameTypeDetails) && $gameTypeChoose=='over')){
            // VGQP输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`  FROM " . DBPREFIX . "vg_history_report 
                    WHERE userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT  SUM(1) AS `count_pay`, SUM(`validbetamount`) AS `valid_money`, SUM(`betamount`) AS `total`, SUM(`money`) AS `user_win` FROM " . DBPREFIX . "vg_projects 
                        WHERE userid=$userid AND `game_endtime` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_vgqp = mysqli_query($dbLink, $sql);
            $cou_vgqp = mysqli_num_rows($res_vgqp);
            if ($cou_vgqp > 0) {
                $row_vgqp = mysqli_fetch_assoc($res_vgqp);
                $dUserWin += $row_vgqp['user_win'];
                $userValid += $row_vgqp['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('lyqp',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('lyqp',$gameTypeDetails) && $gameTypeChoose=='over')){
            // 乐游棋牌输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`  FROM " . DBPREFIX . "ly_history_report 
                    WHERE userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`cellscore`) AS `valid_money`, SUM(`allbet`) AS `total`, SUM(`profit`) AS `user_win` FROM " . DBPREFIX . "ly_projects 
                      WHERE userid=$userid AND `game_endtime` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_lyqp = mysqli_query($dbLink, $sql);
            $cou_lyqp = mysqli_num_rows($res_lyqp);
            if ($cou_lyqp > 0) {
                $row_lyqp = mysqli_fetch_assoc($res_lyqp);
                $dUserWin += $row_lyqp['user_win'];
                $userValid += $row_lyqp['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('klqp',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('klqp',$gameTypeDetails) && $gameTypeChoose=='over')){
            // 快乐棋牌输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
                    FROM " . DBPREFIX . "kl_history_report 
                    WHERE userid=$userid AND `count_date` BETWEEN '{$begin_time}' AND '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`, SUM(`prize`) AS `user_win` FROM " . DBPREFIX . "kl_projects 
                        WHERE userid=$userid AND `gametime` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_klqp = mysqli_query($dbLink, $sql);
            $cou_klqp = mysqli_num_rows($res_klqp);
            if ($cou_klqp > 0) {
                $row_klqp = mysqli_fetch_assoc($res_klqp);
                $dUserWin += $row_klqp['user_win']-$row_klqp['total'];
                $userValid += $row_klqp['valid_money']; // 有效投注
            }
        }

    }

    if($gameType=='all' || ($gameType=='game' && $gameTypeChoose=='main') || ($gameType!='game' && $gameTypeChoose=='over')){ // 电子
        if($gameType=='all' || (in_array('aggame',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('aggame',$gameTypeDetails) && $gameTypeChoose=='over')){
            // AG电子输赢
            $sql = "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(profit) as user_win from ".DBPREFIX."ag_projects_history_report 
                            where userid=$userid AND bet_time BETWEEN '{$begin_time}' and '{$end_time}' and (game_code='' or game_code='SLOT') ";
            if($type =='cur'){
                $sql = "select count(1) AS count_pay, sum(amount) AS total, sum(valid_money) AS valid_money, sum(profit) AS user_win FROM ".DBPREFIX."ag_projects 
                        WHERE userid=$userid AND bettime BETWEEN '".$begin_time."' AND '".$end_time."' AND (`type`='' OR `type`='SLOT') ";
            }
            $res_ag_dianzi = mysqli_query($dbLink, $sql);
            $cou_ag_dianzi = mysqli_num_rows($res_ag_dianzi);
            if ($cou_ag_dianzi>0) {
                $row_ag_dianzi = mysqli_fetch_assoc($res_ag_dianzi);
                $dUserWin += $row_ag_dianzi['user_win'];
                $userValid += $row_ag_dianzi['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('mggame',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('mggame',$gameTypeDetails) && $gameTypeChoose=='over')){
            // MG电子输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
                    FROM " . DBPREFIX . "mg_history_report 
                    WHERE userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
//                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total` FROM " . DBPREFIX . "mg_projects
//                        WHERE userid=$userid AND `transaction_time` BETWEEN '{$begin_time}' AND '{$end_time}' AND category='WAGER'";
                $sql = "SELECT `category`, `amount` FROM " . DBPREFIX . "mg_projects 
                        WHERE userid=$userid AND `transaction_time` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_mg = mysqli_query($dbLink, $sql);
            $cou_mg = mysqli_num_rows($res_mg);
            if ($cou_mg > 0) {
                if($type =='cur'){
                    while($row = mysqli_fetch_assoc($res_mg)){
                        if ($row['category'] == 'WAGER'){
                            $userValid += $row['amount']; // 有效投注
                            $dUserWin += $row['amount']; // 输赢
                        }elseif ($row['category'] == 'PAYOUT'){
                            $dUserWin -= $row['amount']; // 输赢
                        }
                    }
                }else{ // 历史注单
                    $row_mg = mysqli_fetch_assoc($res_mg);
                    $dUserWin -= $row_mg['user_win']; // MG 是相减
                    $userValid += $row_mg['valid_money']; // 有效投注
                }

            }
        }
        if($gameType=='all' || (in_array('mwgame',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('mwgame',$gameTypeDetails) && $gameTypeChoose=='over')){
            // WM电子输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
                    FROM " . DBPREFIX . "mw_history_report 
                    WHERE  userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`playMoney`) AS `valid_money`, SUM(`playMoney`) AS `total`, SUM(`winMoney`) AS `user_win` 
                        FROM " . DBPREFIX . "mw_projects 
                        WHERE userid=$userid AND `logDate` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_mw = mysqli_query($dbLink, $sql);
            $cou_mw = mysqli_num_rows($res_mw);
            if ($cou_mw > 0) {
                $row_mw = mysqli_fetch_assoc($res_mw);
                $dUserWin += $row_mw['user_win'];
                $userValid += $row_mw['valid_money']; // 有效投注
            }

        }
        if($gameType=='all' || (in_array('cqgame',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('cqgame',$gameTypeDetails) && $gameTypeChoose=='over')){
            // CQ9电子输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
                    FROM " . DBPREFIX . "cq9_history_report 
                    WHERE userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`bet`) AS `valid_money`, SUM(`bet`) AS `total`, SUM(`win`) AS `user_win` 
                        FROM " . DBPREFIX . "cq9_projects 
                        WHERE userid=$userid AND `endroundtime` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_cq = mysqli_query($dbLink, $sql);
            $cou_cq = mysqli_num_rows($res_cq);
            if ($cou_cq > 0) {
                $row_cq = mysqli_fetch_assoc($res_cq);
                $dUserWin += $row_cq['user_win']-$row_cq['total'];
                $userValid += $row_cq['valid_money']; // 有效投注
            }

        }
        if($gameType=='all' || (in_array('fggame',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('fggame',$gameTypeDetails) && $gameTypeChoose=='over')){
            // FG电子输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
                    FROM " . DBPREFIX . "fg_history_report 
                    WHERE  userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`all_bets`) AS `valid_money`, SUM(`all_bets`) AS `total`, SUM(`all_wins`) AS `user_win` 
                        FROM " . DBPREFIX . "fg_projects 
                        WHERE userid=$userid AND `endtime` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_fg = mysqli_query($dbLink, $sql);
            $cou_fg = mysqli_num_rows($res_fg);
            if ($cou_fg > 0) {
                $row_fg = mysqli_fetch_assoc($res_fg);
                $dUserWin += $row_fg['user_win']-$row_fg['total'];
                $userValid += $row_fg['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('bbingame',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('bbingame',$gameTypeDetails) && $gameTypeChoose=='over')){
            // BBIN电子输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "jx_bbin_history_report 
                    WHERE  userid=$userid AND GameKind=5 AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`Commissionable`) AS `valid_money`, SUM(`BetAmount`) AS `total`, SUM(`Payoff`) AS `user_win`  FROM " . DBPREFIX . "jx_bbin_projects 
                        WHERE userid=$userid AND GameKind=5 AND `WagersDate` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_bbin = mysqli_query($dbLink, $sql);
            $cou_bbin = mysqli_num_rows($res_bbin);
            if ($cou_bbin > 0) {
                $row_bbin = mysqli_fetch_assoc($res_bbin);
                $dUserWin += $row_bbin['user_win'];
                $userValid += $row_bbin['valid_money']; // 有效投注
            }

        }
    }

    if($gameType=='all' || ($gameType=='bygame' && $gameTypeChoose=='main') || ($gameType!='bygame' && $gameTypeChoose=='over')){ // 捕鱼
        if($gameType=='all' || (in_array('agby',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('agby',$gameTypeDetails) && $gameTypeChoose=='over')){
            // AG捕鱼王打鱼输赢，历史、当天同一张表
            $sql = "select sum(BulletOutNum) as count_pay, sum(Cost) as total, sum(Cost) as valid_money, sum(Earn) as shouru from ".DBPREFIX."ag_buyu_scene 
                        where userid=$userid AND EndTime BETWEEN '{$begin_time}' and '{$end_time}' ";
            $res_ag_dayu = mysqli_query($dbLink, $sql);
            $cou_ag_dayu = mysqli_num_rows($res_ag_dayu);
            if ($cou_ag_dayu>0) {
                $row_ag_dayu = mysqli_fetch_assoc($res_ag_dayu);
                $dUserWin += $row_ag_dayu['shouru']- $row_ag_dayu['valid_money'];
                $userValid += $row_ag_dayu['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('bbinby',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('bbinby',$gameTypeDetails) && $gameTypeChoose=='over')){
            // BBIN（捕鱼达人、捕鱼大师）输赢
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "jx_bbin_history_report 
                    WHERE  userid=$userid AND GameKind in (30,38) AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT SUM(1) AS `count_pay`, SUM(`Commissionable`) AS `valid_money`, SUM(`BetAmount`) AS `total`, SUM(`Payoff`) AS `user_win`  FROM " . DBPREFIX . "jx_bbin_projects 
                        WHERE userid=$userid AND GameKind in (30,38) AND `WagersDate` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_bbin = mysqli_query($dbLink, $sql);
            $cou_bbin = mysqli_num_rows($res_bbin);
            if ($cou_bbin > 0) {
                $row_bbin = mysqli_fetch_assoc($res_bbin);
                $dUserWin += $row_bbin['user_win'];
                $userValid += $row_bbin['valid_money']; // 有效投注
            }
        }
    }

    if($gameType=='all' || ($gameType=='gaming' && $gameTypeChoose=='main') || ($gameType!='gaming' && $gameTypeChoose=='over')) { // 电竞
        if ($gameType == 'all' || ($gameType == 'avia' && $gameTypeChoose == 'main') || ($gameType != 'avia' && $gameTypeChoose == 'over')) { // 泛亚电竞
            // 泛亚电竞输赢（实时统计报表、无需计算当天的报表数据），历史、当天同一张表
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
                FROM " . DBPREFIX . "avia_history_report 
                WHERE userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            $res_avia = mysqli_query($dbLink, $sql);
            $cou_avia = mysqli_num_rows($res_avia);
            if ($cou_avia > 0) {
                $row_avia = mysqli_fetch_assoc($res_avia);
                $dUserWin += $row_avia['user_win'];
                $userValid += $row_avia['valid_money']; // 有效投注
            }
        }
        if($gameType=='all' || (in_array('fire',$gameTypeDetails) && $gameTypeChoose=='main') || (!in_array('fire',$gameTypeDetails) && $gameTypeChoose=='over')){ // 雷火电竞
            $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
                    FROM " . DBPREFIX . "fire_history_report 
                    WHERE  userid=$userid AND `count_date` BETWEEN '{$begin_time}' and '{$end_time}'";
            if($type =='cur'){
                $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`, SUM(`reward`) AS `user_win` 
                        FROM " . DBPREFIX . "fire_projects 
                        WHERE userid=$userid AND `settlement_datetime` BETWEEN '{$begin_time}' AND '{$end_time}'";
            }
            $res_fire = mysqli_query($dbLink, $sql);
            $cou_fire = mysqli_num_rows($res_fire);
            if ($cou_fire > 0) {
                $row_fire = mysqli_fetch_assoc($res_fire);
                $dUserWin += $row_fire['user_win'];
                $userValid += $row_fire['valid_money']; // 有效投注
            }
        }
    }

    $returnData = array(
        'user_win'=>$dUserWin,
        'valid_money'=>$userValid,
    );
    return $returnData;
}

/*
 * 返回彩票彩种 gameCode
 * $gx : gf 官方， xy 信用
 * 彩票:欢乐生肖-cqssc,北京赛车-bjsc,幸运农场-xync,江苏快三-jsks,广东快乐十分-gdklsf,PC蛋蛋-pcdd,香港六合彩-xglhc,极速赛车-jssc,分分彩-ffc,二分彩-efc,
 *  三分彩-sfc,五分彩-wfc,极速快三-jsjsks,极速飞艇-jsft,(国民:新重庆时时彩-xcqssc,五分快三-jsk35fc,三分快三-jsk33fc,极速3D-gw3d,极速六合彩-jslhc
 * 体育彩票：
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
 * 国民彩票：
 * 官方：
 * 13 分分时时彩，16 三分时时彩，28 五分时时彩，17 快三分分彩，51 三分快三，50 五分快三，53 新重庆时时彩，52 北京赛车五分彩，19 极速赛车，49 幸运飞艇，1 欢乐生肖，10 北京PK拾，
 * 2 山东11选5，5 江西时时彩，20 极速3D，
 * 信用：
 * 2 分分时时彩，4 二分彩，5 三分时时彩，6 五分时时彩，75 一分快三，74 三分快三，73 五分快三，51 极速赛车，76 五分北京赛车，7 新重庆时时彩，70 香港六合彩，72 极速六合彩，
 * 55 幸运飞艇，50 北京PK拾，1 欢乐生肖，66 PC蛋蛋，10 江苏快3，61 重庆幸运农场，65 北京快乐8，21 广东11选5，60 广东快乐十分
 * */
function returnLotteryCode($type,$gx=''){
    $code ='';
    if(in_array(TPL_FILE_NAME, ['0086', '6668'])) {
        switch ($type){
            case 'cqssc':
                $code = 2;
            break;
            case 'bjsc':
                $code = 51;
                break;
            case 'xync':
                $code = 47;
                break;
            case 'jsks':
                $code = 159;
                break;
            case 'gdklsf':
                $code = 3;
                break;
            case 'pcdd':
                $code = 304;
                break;
            case 'xglhc':
                $code = 69;
                break;
            case 'jssc':
                $code = 189;
                break;
            case 'ffc':
            case 'ffcSeries':
                $code = 207;
                break;
            case 'efc':
                $code = 607;
                break;
            case 'sfc':
            case 'sfcSeries':
                $code = 407;
                break;
            case 'wfc':
            case 'wfcSeries':
                $code = 507;
                break;
            case 'jsjsks':
                $code = 384;
                break;
            case 'xyft':
                $code = 168;
                break;
            case 'jsft':
                $code = 222;
                break;
        }
    }else{
        if($gx=='xy'){ // 信用
            switch ($type){
                case 'ffcSeries':
                    $code = '2;13';
                    break;
                case 'sfcSeries':
                    $code = '5;74';
                    break;
                case 'wfcSeries':
                    $code = '6;73';
                    break;
                case 'ffc':
                    $code = 2;
                    break;
                case 'efc':
                    $code = 4;
                    break;
                case 'sfc':
                    $code = 5;
                    break;
                case 'wfc':
                    $code = 6;
                    break;
                case 'jsks': // 江苏快3
                    $code = 10;
                    break;
                case 'jsjsks':
                    $code = 75;
                    break;
                case 'jsk33fc':
                    $code = 74;
                    break;
                case 'jsk35fc':
                    $code = 73;
                    break;
                case 'cqssc': // 欢乐生肖
                    $code = 1;
                    break;
                case 'xcqssc':
                    $code = 7;
                    break;
                case 'bjsc': // 北京PK拾
                    $code = 50;
                    break;
                case 'bjpk105fc': // 五分北京赛车
                    $code = 76;
                    break;
                case 'jssc': // 极速赛车
                    $code = 51;
                    break;
                case 'xyft': // 幸运飞艇
                    $code = 55;
                    break;
                case 'xglhc': // 香港六合彩
                    $code = 70;
                    break;
                case 'jslhc': // 极速六合彩
                    $code = 72;
                    break;
                case 'pcdd': // PC蛋蛋
                    $code = 66;
                    break;
                case 'gdklsf': // 广东快乐十分
                    $code = 60;
                    break;


            }
        }else{ // 官方彩
            switch ($type){
                case 'ffcSeries':
                    $code = '13';
                    break;
                case 'sfcSeries':
                    $code = '16;51';
                    break;
                case 'wfcSeries':
                    $code = '28;50';
                    break;
                case 'ffc':
                    $code = 13;
                    break;
                case 'sfc':
                    $code = 16;
                    break;
                case 'wfc':
                    $code = 28;
                    break;
                case 'jsjsks':
                    $code = 17;
                    break;
                case 'jsk33fc':
                    $code = 51;
                    break;
                case 'jsk35fc':
                    $code = 50;
                    break;
                case 'cqssc':
                    $code = 1;
                    break;
                case 'xcqssc':
                    $code = 53;
                    break;
                case 'bjsc':
                    $code = 10;
                    break;
                case 'bjpk105fc':
                    $code = 52;
                    break;
                case 'jssc':
                    $code = 19;
                    break;
                case 'xyft':
                    $code = 49;
                    break;
                case 'jsft':
                    $code = 222;
                    break;
                case 'gw3d':
                    $code = 20;
                    break;
            }
        }

    }
    return $code;
}

/*
 *  检查是当天是否已经存在该优惠
 * */
function checkUserPromos($checkData){
    global $dbLink;
    // 手机号，IP，姓名，账号，只能申请一次，有其中相同的都不能申请
    $ip_addr = get_ip();
    $resdata =[];
    $week_day = date('w'); // 返回当天的星期;数字0表示是星期天,数字123456表示星期一到六
    $cur_month = date('Y-m'); // 当月
    $todayNow = date('Y-m-d'); // 当天

    $beginweek =  mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y')); // 本周一
    $endweek = mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y')); // 本周日
    $cur_date_s = date('Y-m-d',$beginweek); // 本周一
    $cur_date_e =  date('Y-m-d',$endweek); // 本周日
    if($week_day==0) { // 当前时间是周日的时候特殊处理
        $weekday_s = ($week_day + 6) % 7; // 本周一
        $weekday_e = ($week_day) % 7; // 本周末，周日
        $cur_date_s = date('Y-m-d',strtotime("-{$weekday_s} day")); // 本周一
        $cur_date_e = date('Y-m-d',strtotime("-{$weekday_e} day")); // 本周末，周日
    }

    // echo $cur_date_s.'=='.$cur_date_e.'=='.$todayNow;
    $table = DBPREFIX."web_promos_receiveList";
    $userId = $checkData['userid'];
    $proname = $checkData['name']; // 活动标识
    $daytype = $checkData['statisticsDayType']; // 活动统计时间类型
    $Phone = trim($checkData['Phone']);
    $Alias = trim($checkData['Alias']);
    $tip ='天';

    if($proname == 'dragon' || $proname == 'euro') {  // 端午节不验证ip
        $limitsql = " (userid='$userId' ".($Phone?"or Phone='$Phone'":"")." or Alias='$Alias') "; //
    }else{
        $limitsql = " (userid='$userId' ".($Phone?"or Phone='$Phone'":"")." or Alias='$Alias' or applyIp='$ip_addr') "; //
    }

    $whereSql = "$limitsql and name='$proname'";
    if($daytype=='lastMon' || $daytype=='thisMon'){ // 按月
        $tip ='月';
        $whereSql .=" and add_month='$cur_month'";
    }else if($daytype=='lastWeek'){
        $tip ='周';
        $whereSql .= " and add_day between '$cur_date_s' and '$cur_date_e' ";
    }else{
        $whereSql .=" and add_day = '$todayNow'";
    }
    $check_sql = "select ID from ".$table." where $whereSql";
    // echo $check_sql;
    $result = mysqli_query($dbLink,$check_sql);
    $cou = mysqli_num_rows($result);
    // $aRow = mysqli_fetch_assoc($result);
    if($cou>0){ // 已经领取过活动
        // $nextsql="update ".$table." set ".implode(',',$afterData)." where ID = {$aRow['ID']}";
        $status = '400.99';
        $describe = '您已在当'.$tip.'申请过本活动，不允许重复申请';
        original_phone_request_response($status,$describe,$resdata);
    }
}
/*
 *  插入或者更新活动领取
 *  $addData 插入数据
 * */
function addPromosAction($addData){
    global $dbMasterLink;
    $table = DBPREFIX."web_promos_receiveList";
    foreach($addData as $key=>$val){
        $afterData[]=$key.'=\''.$val.'\'';
    }
    $nextsql = "insert into ".$table." set ".implode(',',$afterData);
    $res = mysqli_query($dbMasterLink,$nextsql);
    return $res;
}


/*
 * 活动统一接口
 * $afterpromoData
 *  彩票游戏活动：
 * array(17) {
 * ["title"]=>
 * string(21) "新皇冠洗码之王"
 * ["leader"]=>
 * string(9) "promoyxtz"
 * ["statisticsDayType"]=>
 * string(9) "yesterday"
 * ["statisticsDay"]=>
 * string(0) ""
 * ["receiveDayType"]=>
 * string(5) "today"
 * ["receiveDay"]=>
 * string(0) ""
 * ["receiveTime"]=>
 * string(5) "00-24"
 * ["Payway"]=>
 * string(3) "ALL"
 * ["discounType"]=>
 * string(1) "0"
 * ["depositLimits"]=>
 * string(0) ""
 * ["depositDays"]=>
 * string(0) ""
 * ["validBet"]=>
 * string(96) "3000;5000;10000;50000;80000;100000;300000;500000;800000;1000000;3000000;5000000;8000000;10000000"
 * ["bonus"]=>
 * string(178) "6-7-8;9-10-11;16-17-18;33-36-46;55-60-75;88-96-120;110-120-150;330-360-450;550-600-750;880-960-1200;1100-1200-1500;3300-3600-4500;5500-6000-7500;8800-9600-12000;11000-12000-15000"
 * ["profitable"]=>
 * string(0) ""
 * ["gameType"]=>
 * string(7) "lottery"
 * ["gameTypeDetails"]=>
 * string(29) "wfcSeries;sfcSeries;ffcSeries"
 * ["mergeOrSplit"]=>
 * string(3) "yes"
 *}
 *
 *全勤奖：
 * array(17) {
 * ["title"]=>
 * string(18) "新皇冠全勤奖"
 * ["leader"]=>
 * string(9) "promoyxtz"
 * ["statisticsDayType"]=>
 * string(7) "lastMon"
 * ["statisticsDay"]=>
 * string(0) ""
 * ["receiveDayType"]=>
 * string(5) "month"
 * ["receiveDay"]=>
 * string(8) "01;02;28"
 * ["receiveTime"]=>
 * string(5) "00-24"
 * ["Payway"]=>
 * string(3) "ALL"
 * ["discounType"]=>
 * string(1) "0"
 * ["depositLimits"]=>
 * string(0) ""
 * ["depositDays"]=>
 * string(23) "10;21;10;21;10;21;10;21"
 * ["validBet"]=>
 * string(63) "100000;100000;1000000;1000000;5000000;5000000;10000000;10000000"
 * ["bonus"]=>
 * string(37) "388;888;1888;2888;3888;5888;6888;8888"
 * ["profitable"]=>
 * string(0) ""
 * ["gameType"]=>
 * string(3) "all"
 * ["gameTypeDetails"]=>
 * string(3) "all"
 * ["mergeOrSplit"]=>
 * string(3) "not"
 *}
 *
 *周周转运金：
 * array(17) {
 * ["title"]=>
 * string(15) "周周转运金"
 * ["leader"]=>
 * string(8) "promofyl"
 * ["statisticsDayType"]=>
 * string(8) "lastWeek"
 * ["statisticsDay"]=>
 * string(0) ""
 * ["receiveDayType"]=>
 * string(4) "week"
 * ["receiveDay"]=>
 * string(1) "5"
 * ["receiveTime"]=>
 * string(5) "00-24"
 * ["Payway"]=>
 * string(3) "ALL"
 * ["discounType"]=>
 * string(1) "0"
 * ["depositLimits"]=>
 * string(0) ""
 * ["depositDays"]=>
 * string(0) ""
 * ["validBet"]=>
 * string(0) ""
 * ["bonus"]=>
 * string(40) "18;28;58;88;288;888;1688;3888;8888;18888"
 * ["profitable"]=>
 * string(61) "1000;2000;5000;10000;20000;50000;100000;200000;500000;1000000"
 * ["gameType"]=>
 * string(3) "all"
 * ["gameTypeDetails"]=>
 * string(3) "all"
 * ["mergeOrSplit"]=>
 * string(3) "not"
 *}
 *
 * $type_flag 活动标识 hgty_web_promos_rule 中name
 * */
function promosApiAction($user_id,$username,$type_flag){
    global $dbLink;
    $timeNow = date('Y-m-d H:i:s');
    $todayNow = date('Y-m-d'); // 当天
    $yesterday= date('Y-m-d',strtotime('-1 day' )); // 昨天
    $cur_month = date('Y-m'); // 当月
    $today_d = date('d'); // 当天-日
    $today_d_h = date('H'); // 当天-小时,H 24小时制，h 12小时制
    // echo $today_d.'=='.$today_d_h.'==';

// 当天开始时间
    $beginTodayTime = date('Y-m-d 00:00:00');
    $endTodayTime = date('Y-m-d 23:59:59');

// 昨天天开始时间
    $beginYesterday = date('Y-m-d 00:00:00',strtotime('-1 day' ));
    $endYesterday = date('Y-m-d 23:59:59',strtotime('-1 day' ));
    $beginYesterdayTime = strtotime($beginYesterday);
    $endYesterdayTime = strtotime($endYesterday);

    // 本月第一天
    $beginCurmonthd = date('Y-m-01 00:00:00', strtotime(date("Y-m-d")));
    $beginCurmonth = strtotime($beginCurmonthd);

// 获取月起始时间戳和结束时间戳
    $beginLastmonthd = date('Y-m-01 00:00:00',strtotime('-1 month'));  // 上月开始时间截止时间
    $endLastmonthd = date("Y-m-d 23:59:59", strtotime(-date('d').'day')); // 上月结束时间截止时间
    $beginLastmonth = strtotime($beginLastmonthd); // 转为时间戳
    $endLastmonth = strtotime($endLastmonthd);  // 转为时间戳
// echo $beginLastmonth.'=='.$endLastmonth;

// 获取上周起始时间戳和结束时间戳  用于统计存取款
    $beginLastweek = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
    $endLastweek = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));

    $depositBonusDisArr = $promosValidBetArr = $gameTypeDetailsArr = $promosValidBet = $profitableArr = $promosBonusSecArr = $promosBonusArr = $usdtBonusArr = $depositDaysArr = $depositLimitsArr = $promoData = $afterpromoData = $resdata = array();
    $depositLimits = 0; // 存款要求
    $valid_money = 0; // 有效投注
    $userWinTotal = 0; // 负盈利
    $depositDays = 0 ; // 存款天数
    $depositBonus = 0 ; // 派发金额
    $promosValidBetCount = 0 ;// 有效投注累加, >0 代表有其中一项达到条件

    if(!$user_id) {
        $status = '400.01';
        $describe = '您的登录信息已过期,请重新登录!';
        original_phone_request_response($status,$describe,$resdata);
    }

//异常点击必发活动领取
    $redisObj = new Ciredis();
    $attTime = $redisObj->getSimpleOne('activity_promos_useid_'.$user_id);
    if($attTime) {
        $allowtime = time()-$attTime;
        if($allowtime<5) { // 5 秒
            $status = '400.02';
            $describe = '不允许多次点击,请稍后申请';
            original_phone_request_response($status,$describe,$resdata);
        }
    }
// 插入当前申请时间，存入redis, 确保不允许重复申请
    $redisObj->setOne('activity_promos_useid_'.$user_id, time());

    $member_sql = "select ID,UserName,DepositTimes,Alias,Phone,Bank_Account,AddDate,layer from ".DBPREFIX.MEMBERTABLE." where ID='$user_id'";
    $member_query = mysqli_query($dbLink,$member_sql);
    $memberinfo = mysqli_fetch_assoc($member_query);
    $sUserlayer = $memberinfo['layer'];

    $memberAddDate = $memberinfo['AddDate']; // 会员注册日期
    $Alias = $memberinfo['Alias']; // 会员真实姓名
    $Phone = $memberinfo['Phone']; // 会员手机号码
    $Bank_Account = $memberinfo['Bank_Account']; // 银行账号

// 检查当前会员是否设置不准领取彩金分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
    $layerId=4;
    if ($sUserlayer==$layerId){
        $layer = getUserLayerById($layerId);
        if ($layer['status']==1) {
            $status = '400.66';
            $describe = '账号分层异常，请联系我们在线客服';
            original_phone_request_response($status,$describe,$resdata);
        }
    }

    $promoData = returnPromoSet($type_flag); // 获取活动基础设置
    $afterpromoData = $promoData[0]; // 组合数据
    $receiveDayArr = explode(';',$afterpromoData['receiveDay']); // 活动领取时间，天
    $receiveTimeArr = explode('-',$afterpromoData['receiveTime']); // 活动领取时间，小时，如 00-24

  // var_dump($afterpromoData);
  // var_dump($receiveTimeArr);
// echo date('d').'=='.$afterpromoData['receiveDay'];
// var_dump($receiveDayArr);

    // 端午佳节活动验证会员注册时间限制
    if(in_array($type_flag , ['dragon']) &&  (date('Y-m-d' , strtotime($memberAddDate)) > '2020-06-22')  ) {
        $status = '400.09';
        $describe = '会员注册时间不符合申请条件';
        original_phone_request_response($status,$describe,$resdata);
    }
    // 2020欧洲杯活动验证会员存款次数
    if(in_array($type_flag , ['euro']) &&  ($memberinfo['DepositTimes'] < 5)  ) {
        $status = '400.091';
        $describe = '阁下的账号不符合领取条件';
        original_phone_request_response($status,$describe,$resdata);
    }

    $checkData = array(
        'userid'=>$user_id,
        'name'=>$type_flag,
        'statisticsDayType'=>$afterpromoData['statisticsDayType'],
        'Phone'=>$Phone,
        'Alias'=>$Alias
    );
    checkUserPromos($checkData); // 先检查是否已经领取过此项优惠

    $week_day = date('w'); // 返回当天的星期;数字0表示是星期天,数字123456表示星期一到六
    switch ($afterpromoData['receiveDayType']){ // 领取时间以月，周，日为单位
        case 'month':
            $lq_day = $today_d;
            $tipD = '月';
            break;
        case 'week':
            $lq_day = $week_day;
            $tipD = '周';
            break;
    }

    if( !in_array($lq_day,$receiveDayArr) || ($today_d_h<$receiveTimeArr[0] || $today_d_h>$receiveTimeArr[1]) ){
        $status = '400.03';
        //$describe = '请于美东时间每'.$tipD.$afterpromoData['receiveDay'].'，'.$receiveTimeArr[0].'到'.$receiveTimeArr[1].'点申请活动';
        $describe = $afterpromoData['promolqDatetimeTip'];
        original_phone_request_response($status,$describe,$resdata);
    }

    $startTimeTj = strtotime($beginTodayTime); // 活动统计戳，默认当天
    $startEndTj = strtotime($endTodayTime); // 活动统计时间，默认当天

    if($afterpromoData['depositLimits']){ // 活动存款要求全部数据,存款范围
        $depositLimitsArr = explode(';',$afterpromoData['depositLimits']);
    }
    if($afterpromoData['depositDays']){ // 活动存款天数,存款天数
        $depositDaysArr = explode(';',$afterpromoData['depositDays']);
    }
    if($afterpromoData['bonus']){ // 活动派发金额,派送金额
        $promosBonusArr = explode(';',$afterpromoData['bonus']);
    }
    if($afterpromoData['usdtbonus']){ // 全勤奖活动usdt派发金额
        $usdtBonusArr = explode(';',$afterpromoData['usdtbonus']);
    }

    if( !($afterpromoData['depositLimits'] || $afterpromoData['profitable'] || $afterpromoData['validBet']) ){ // 未配置任何参数
        $status = '400.04';
        $describe = '活动参数设置异常';
        original_phone_request_response($status,$describe,$resdata);
    }
// echo count($receiveDayArr).'+++';
// 活动主导类型
    switch ($afterpromoData['leader']){
        case 'promolq': // 领取时间为主导
            foreach ($receiveDayArr as $k=>$v){
                foreach ($depositLimitsArr as $kk=>$vv){ // 存款要求
                    if($v==$lq_day){
                        $depositLimits = $depositLimitsArr[$k];
                        $startTimeTj = mktime(0,0,0,date('m'),$v,date('Y'));
                        $startEndTj = mktime(23,59,59,date('m'),$v,date('Y'));
                    }
                }
                foreach ($depositDaysArr as $kkk=>$vvv){ // 存款天数要求
                    if($v==$lq_day){
                        $depositDays = $depositDaysArr[$k];
                    }
                }
                if(count($receiveDayArr)>1) { // 根据领取天数
                    foreach ($promosBonusArr as $kkkk=>$vvvv){ // 活动派发金额
                        if($v==$lq_day){
                            $depositBonus = $promosBonusArr[$k];
                        }
                    }
                }

            }
            break;
        case 'promoyxtz': // 有效投注为主导
        case 'promofyl': // 负盈利为主导
            if($afterpromoData['validBet']){ // 活动有效投注要求,打码量
                $promosValidBet = explode(';',$afterpromoData['validBet']);
            }
            if($afterpromoData['profitable']){ // 活动负盈利要求
                $profitableArr = explode(';',$afterpromoData['profitable']);
            }
            switch ($afterpromoData['statisticsDayType']){ // 活动统计时间判断
                case 'thisMon': // 本月
                    $startTimeTj = $beginCurmonth;
                    break;
                case 'lastMon': // 上月
                    $startTimeTj = $beginLastmonth;
                    $startEndTj = $endLastmonth;
                    break;
                case 'lastWeek': // 上周
                    $startTimeTj = $beginLastweek;
                    $startEndTj = $endLastweek;
                    break;
                case 'yesterday': // 昨天
                    $startTimeTj = $beginYesterdayTime;
                    $startEndTj = $endYesterdayTime;
                    break;

            }

            break;
    }

// 最后时间格式 2020-02-28 00:00:00
    $time['begin_time'] = date('Y-m-d H:i:s' , $startTimeTj); // 活动统计时间
    $time['end_time'] = date('Y-m-d H:i:s' , $startEndTj); // 活动统计时间

    if ($type_flag == 'sj_holiday'){ // 国庆中秋活动
        $time['begin_time'] = '2020-10-01 00:00:00';
        $time['end_time'] = '2020-10-07 23:59:59';
    }

  //echo $time['begin_time'].'==='.$time['end_time'];

    if($afterpromoData['validBet']) { // 活动有效投注要求
        if($afterpromoData['depositDays']) { // 活动存款天数
            $depositReturnDays = returnDepositDays($user_id, $time); // 获取用户存款天数和usdt天数
            $userDepositDays = $depositReturnDays['depositCount']; // 总充值天数
            $userUsdtDepositDays = $depositReturnDays['depositusdt']; // Usdt充值天数

            /*$userDepositDays = 21;
            $userUsdtDepositDays = 11;*/
        }
        if($afterpromoData['mergeOrSplit']=='yes'){ // 需要分开统计 (只有king洗码之王yes)
            $gameTypeDetailsArr =  explode(';',$afterpromoData['gameTypeDetails']); // 游戏分类
            $order =0;
            foreach ($gameTypeDetailsArr as $kk=>$vv){ // 每个分类的有效投注
                $promosValidBetArr[$vv]= getUserGameReport($user_id, $username, $time,$afterpromoData,$vv); // 获取会员报表盈利
                $promosValidBetArr[$vv]['order']=$order;
                $order++;
            }
            foreach ($promosValidBetArr as $k_v=>$v_v){
                if($v_v['valid_money']>=$promosValidBet[0]){
                    $promosValidBetCount++;
                }
            }
        }else{
            $historybetmember = getUserGameReport($user_id, $username, $time,$afterpromoData); // 获取会员报表盈利
            $valid_money = $historybetmember['valid_money']; // 有效投注

            //$valid_money = '360000';
        }

        $depositDays = $userDepositDays;
        $vbet_length = count($promosValidBet); //后台有效投注打码量
        @error_log('有效投注数量:'.$vbet_length.'==用户存款天数:'.$userDepositDays.'==用户存款usdt天数:'.$userUsdtDepositDays.'==有效投注:'.$valid_money.'##有效投注累加:'.$promosValidBetCount .PHP_EOL, 3, '/tmp/group/promos.log');

        for ($jj=0;$jj<$vbet_length;$jj++){

            if($afterpromoData['depositDays'] && $type_flag != 'chess') { // 活动存款天数, 0086棋牌游戏需要统计usdt是否充值，这里无需验证存款天数
                if($userDepositDays<$depositDaysArr[0]){ // 没有达到最低条件 存款天数
                    $status = '400.05';
                    $describe = '您的存款天数为'.$userDepositDays.'天，没有达到要求，不允许申请!';
                    original_phone_request_response($status,$describe,$resdata);
                }
            }
            if($afterpromoData['mergeOrSplit']=='yes') { // yes 需要分开统计,not 不需要分开
                if($promosValidBetCount==0){
                    $status = '400.06';
                    $describe = '有效投注没有达到要求，不允许申请!';
                    original_phone_request_response($status,$describe,$resdata);
                }
            }else{
                if($valid_money<$promosValidBet[0]){ // 没有达到最低条件 有效投注
                    $status = '400.07';
                    $describe = '您当前的有效投注为'.$valid_money.'，没有达到要求，不允许申请!';
                    original_phone_request_response($status,$describe,$resdata);
                }
            }

            if($promosValidBet && $depositDaysArr){ // 有效投注(打码量)，存款天数都需要满足
                //echo '投注标准promosValidBet_'.$jj.':'.$promosValidBet[$jj].'--天数depositDaysArr'.$jj.':'.$depositDaysArr[$jj].'--银行彩金promosBonusArr'.$jj.':'.$promosBonusArr[$jj].'--usdtBonusArr'.$jj.':'.$usdtBonusArr[$jj];
                //@error_log('投注标准promosValidBet_'.$jj.':'.$promosValidBet[$jj].'--promosValidBet_jj+2:'.$promosValidBet[$jj+2].'--天数depositDaysArr'.$jj.':'.$depositDaysArr[$jj].'--银行彩金promosBonusArr'.$jj.':'.$promosBonusArr[$jj].'--usdtBonusArr'.$jj.':'.$usdtBonusArr[$jj].PHP_EOL, 3, '/tmp/group/promos.log');
                /**
                 * 有效投注前两位一样，
                 * 全勤奖根据有效投注和充值天数，
                 *     如果满足usdt充值天数，领取usdt彩金
                 *     否则领取银行彩金
                        投注标准promosValidBet_0:100000--promosValidBet_jj+2:1000000--天数depositDaysArr0:10--银行彩金promosBonusArr0:288--usdtBonusArr0:388
                        投注标准promosValidBet_1:100000--promosValidBet_jj+2:1000000--天数depositDaysArr1:21--银行彩金promosBonusArr1:688--usdtBonusArr1:888
                        投注标准promosValidBet_2:1000000--promosValidBet_jj+2:5000000--天数depositDaysArr2:10--银行彩金promosBonusArr2:1588--usdtBonusArr2:1888
                        投注标准promosValidBet_3:1000000--promosValidBet_jj+2:5000000--天数depositDaysArr3:21--银行彩金promosBonusArr3:2488--usdtBonusArr3:2888
                        投注标准promosValidBet_4:5000000--promosValidBet_jj+2:10000000--天数depositDaysArr4:10--银行彩金promosBonusArr4:3388--usdtBonusArr4:3888
                        投注标准promosValidBet_5:5000000--promosValidBet_jj+2:10000000--天数depositDaysArr5:21--银行彩金promosBonusArr5:5188--usdtBonusArr5:5888
                        投注标准promosValidBet_6:10000000--promosValidBet_jj+2:--天数depositDaysArr6:10--银行彩金promosBonusArr6:6188--usdtBonusArr6:6888
                        投注标准promosValidBet_7:10000000--promosValidBet_jj+2:--天数depositDaysArr7:21--银行彩金promosBonusArr7:7888--usdtBonusArr7:8888
                 * */
                if($promosValidBet[0] ==$promosValidBet[1] ){ // 单双一样,如 100000;100000;1000000;1000000
                    if($jj==($vbet_length-1) ){ // 最后一个  vbet_length=8  jj=7情况下
                        if($type_flag == 'attendance' && !empty($userUsdtDepositDays) && $valid_money>=$promosValidBet[$jj] && $userUsdtDepositDays>=$depositDaysArr[$jj] ){ // 判断usdt充值天数是否在区间范围内
                            $depositBonus = $usdtBonusArr[$jj]; //  全勤奖活动usdt派发金额
                        }elseif( $valid_money>=$promosValidBet[$jj] && $userDepositDays>=$depositDaysArr[$jj] ){ // 判断是否在区间范围内
                            $depositBonus = $promosBonusArr[$jj]; //  活动派发金额
                        }
                    }else{
                        if($type_flag == 'attendance' && !empty($userUsdtDepositDays) && $valid_money>=$promosValidBet[$jj] && $valid_money<$promosValidBet[$jj+2] && $userUsdtDepositDays>=$depositDaysArr[$jj] ){ // 判断usdt充值天数是否在区间范围内
                            //@error_log('全勤奖活动usdt派发金额:'.$usdtBonusArr[$jj].'存款天数区间:'.$depositDaysArr[$jj].PHP_EOL, 3, '/tmp/group/promos.log');
                            $depositBonus = $usdtBonusArr[$jj]; //  全勤奖活动usdt派发金额
                        }elseif( $valid_money>=$promosValidBet[$jj] && $valid_money<$promosValidBet[$jj+2] && $userDepositDays>=$depositDaysArr[$jj] ){ // 判断银行天数是否在区间范围内 不包含1000w以上,jj在0-5特殊情况下
                            //@error_log('全勤奖活动银行派发金额:'.$promosBonusArr[$jj].'存款天数区间:'.$depositDaysArr[$jj].PHP_EOL, 3, '/tmp/group/promos.log');
                            $depositBonus = $promosBonusArr[$jj]; //  活动派发金额
                        }elseif( $valid_money>=$promosValidBet[$jj] && $valid_money>$promosValidBet[$jj] && $userDepositDays>=$depositDaysArr[$jj] ){ // 判断银行天数是否在区间范围内 包含1000w以上,jj=6特殊情况下
                            $depositBonus = $promosBonusArr[$jj]; //  活动派发金额
                        }
                    }

                }else{
                    //@error_log('投注标准promosValidBet_'.$jj.':'.$promosValidBet[$jj].'--userDepositDays:'.$userDepositDays.'--天数depositDaysArr'.$jj.':'.$depositDaysArr[$jj].'--银行彩金promosBonusArr'.$jj.':'.$promosBonusArr[$jj].'--usdtBonusArr'.$jj.':'.$usdtBonusArr[$jj] .PHP_EOL, 3, '/tmp/group/promos.log');
                    /* * 棋牌得意彩金根据有效投注，
                     *     如果有usdt充值，领取usdt彩金
                     *     否则领取银行彩金
                            投注标准promosValidBet_1:2000--userDepositDays:1--天数depositDaysArr1:--银行彩金promosBonusArr1:14--usdtBonusArr1:18
                            投注标准promosValidBet_2:5000--userDepositDays:1--天数depositDaysArr2:--银行彩金promosBonusArr2:22--usdtBonusArr2:28
                            投注标准promosValidBet_3:10000--userDepositDays:1--天数depositDaysArr3:--银行彩金promosBonusArr3:38--usdtBonusArr3:58
                            投注标准promosValidBet_4:50000--userDepositDays:1--天数depositDaysArr4:--银行彩金promosBonusArr4:68--usdtBonusArr4:88
                            投注标准promosValidBet_5:100000--userDepositDays:1--天数depositDaysArr5:--银行彩金promosBonusArr5:138--usdtBonusArr5:188
                            投注标准promosValidBet_6:500000--userDepositDays:1--天数depositDaysArr6:--银行彩金promosBonusArr6:388--usdtBonusArr6:588
                            投注标准promosValidBet_7:1000000--userDepositDays:1--天数depositDaysArr7:--银行彩金promosBonusArr7:888--usdtBonusArr7:1288
                            投注标准promosValidBet_8:5000000--userDepositDays:1--天数depositDaysArr8:--银行彩金promosBonusArr8:3888--usdtBonusArr8:5888
                            投注标准promosValidBet_9:10000000--userDepositDays:1--天数depositDaysArr9:--银行彩金promosBonusArr9:6888--usdtBonusArr9:8888
                    */

                    if($jj==($vbet_length-1) ){ // 最后一个
                        if($type_flag == 'chess' && !empty($userUsdtDepositDays) && $valid_money>=$promosValidBet[$jj] ) { // 说明usdt有充值

                            $depositBonus = $usdtBonusArr[$jj]; //  棋牌得意usdt派发金额
                        }elseif( $valid_money>=$promosValidBet[$jj] && $userDepositDays>=$depositDaysArr[$jj] ){ // 判断是否在区间范围内

                            $depositBonus = $promosBonusArr[$jj]; //  活动派发金额
                        }
                    }else{

                        if($type_flag == 'chess' && !empty($userUsdtDepositDays) && $valid_money>=$promosValidBet[$jj] ) { // 说明usdt有充值

                            $depositBonus = $usdtBonusArr[$jj]; //  棋牌得意usdt派发金额
                        }elseif( $valid_money>=$promosValidBet[$jj] && $valid_money<$promosValidBet[$jj+1] && $userDepositDays>=$depositDaysArr[$jj] ){ // 判断是否在区间范围内

                            $depositBonus = $promosBonusArr[$jj]; //  活动派发金额
                        }
                    }

                }

            }else if($promosValidBet && !$depositDaysArr){ // 只满足 有效投注
                if($afterpromoData['mergeOrSplit']=='yes') { // yes 需要分开统计,not 不需要分开
                    $promosBonusSecArr = explode('-',$promosBonusArr[$jj]); // 横向彩金，如五分彩系列，三分彩系列，分分彩系列
                    // var_dump($promosBonusSecArr);
                    if($jj==($vbet_length-1) ){ // 最后一个
                        if( $v_v['valid_money']>=$promosValidBet[$jj]){ // 判断是否在区间范围内
                            // $depositBonus = $promosBonusArr[$jj]; //  活动派发金额
                            foreach ($promosValidBetArr as $k_v=>$v_v) {
                                $depositBonusDisArr[$k_v] = $promosBonusSecArr[$v_v['order']]; //  活动派发金额
                            }
                        }
                    }else{
                        foreach ($promosValidBetArr as $k_v=>$v_v){
                            if( $v_v['valid_money']>=$promosValidBet[$jj] && $v_v['valid_money']<$promosValidBet[$jj+1] ){ // 判断是否在区间范围内
                                // $depositBonus = $promosBonusArr[$jj]; //  活动派发金额
                                $depositBonusDisArr[$k_v] = $promosBonusSecArr[$v_v['order']]; //  活动派发金额
                            }
                        }

                    }
                }else{
                    if($jj==($vbet_length-1) ){ // 最后一个
                        if( $valid_money>=$promosValidBet[$jj]){ // 判断是否在区间范围内
                            $depositBonus = $promosBonusArr[$jj]; //  活动派发金额
                        }
                    }else{
                        if( $valid_money>=$promosValidBet[$jj] && $valid_money<$promosValidBet[$jj+1] ){ // 判断有效投注是否在区间范围内
                            $depositBonus = $promosBonusArr[$jj]; //  活动派发金额
                        }
                    }
                }

            }else if(!$promosValidBet && $depositDaysArr){ // 只满足 存款天数
                if( $userDepositDays>=$depositDaysArr[$jj]){ // 判断是否在区间范围内
                    $depositBonus = $promosBonusArr[$jj]; //  活动派发金额
                }
            }

        }

    }

// var_dump($promosValidBetArr);
//var_dump($gameTypeDetailsArr);
//var_dump($depositBonusDisArr);
//var_dump($promosBonusArr);
//exit($depositBonus.'+++++++++');


    if($afterpromoData['profitable']) { // 活动负盈利要求
        $historybetmember = getUserGameReport($user_id, $username, $time,$afterpromoData); // 获取会员报表盈利
        $validBetCount = $historybetmember['user_win']*-1; // 报表负盈利
        $rebateTotal = getUserCashGold($memberAddDate,$user_id, $time,'rebate');//返水
        $youhuiTotal = getUserCashGold($memberAddDate,$user_id, $time,'youhui');//充值优惠
        $caijinTotal = getUserCashGold($memberAddDate,$user_id, $time,'caijin');//彩金
        //实际输赢=报表总输赢-返水-优惠-彩金
        $userWinTotal = $validBetCount-$rebateTotal-$youhuiTotal-$caijinTotal; // 总负盈利
         // echo ($validBetCount.'=='.$rebateTotal.'=='.$youhuiTotal.'=='.$caijinTotal);
        // echo ($userWinTotal.'--');
        $length_1 = count($profitableArr);
        for ($ii=0;$ii<$length_1;$ii++){
            if($userWinTotal<$profitableArr[0]){ // 没有达到最低条件
                $status = '400.08';
                $describe = '您当前负盈利为'.$userWinTotal.'元，不符合要求，不允许申请!';
                original_phone_request_response($status,$describe,$resdata);
            }
            if($ii ==($length_1-1) ){ // 最后一个
                if( $userWinTotal>=$profitableArr[$ii]){ // 判断是否在区间范围内
                    $depositBonus = $promosBonusArr[$ii]; //  活动派发金额
                }
            }else{
                if( $userWinTotal>=$profitableArr[$ii] && $userWinTotal<$profitableArr[$ii+1] ){ // 判断是否在区间范围内
                    $depositBonus = $promosBonusArr[$ii]; //  活动派发金额
                }
            }


        }

    }

// echo $depositLimits.'=='.$depositBonus.'=='.$userWinTotal.'=='.$valid_money.'--'.$depositDays ;exit;

    if($afterpromoData['depositLimits']){ // 是否需要存款要求

        if($type_flag == 'euro') { // 2020欧洲杯活动单独处理
            $memAddYear = date('Y' , strtotime($memberAddDate));
            $length_2 = count($depositLimitsArr);   //15  2007;2008;2009;2010;2011;2012;2013;2014;2015;2016;2017;2018;2019;2020;2021
            for ($ii=0;$ii<$length_2;$ii++) { // 会员注册时间循环判断

                if(($ii ==($length_2-1)) &&  ($memAddYear >= 2021)){ // 最后一个  $depositLimitsArr[$ii] = 2021

                    if($memberAddDate <= '2021-05-31'){ // 注册时间小于2021-05-31
                        $depositBonus = $promosBonusArr[$ii];   // 活动派发金额 88
                    }else{
                        $depositBonus = 0;
                    }
                    //@error_log('注册时间:'.$memberAddDate.'-年:'.$memAddYear .'--length_2-1:'.($length_2-1) .'--depositLimits:'.$depositLimitsArr[$ii].'--ii:'.$ii .'--BonusArr:'.$promosBonusArr[$ii].'--Bonus:'.$depositBonus.PHP_EOL, 3, '/tmp/group/promos.log');
                }else if( $memAddYear == $depositLimitsArr[$ii] ){ // 判断是否在区间范围内
                    $depositBonus = $promosBonusArr[$ii]; //  活动派发金额
                }
            }


        } else {
            // 查询会员存款
            $depositAmount = getUserDeposit($user_id,$time,$afterpromoData['Payway'],$afterpromoData['discounType'],$afterpromoData['depositDaysFirst']); // 获取用户存款额度
           // echo $depositAmount.'=='.$depositLimits;exit;
            // 如果当天小于对应存款， 不允许会员申请
            if($depositAmount < $depositLimits){
                $status = '400.09';
                $describe = '你当前存款为'.$depositAmount.'，'.$afterpromoData['title'].'活动不符合存款要求，不允许申请，请先存款';
                original_phone_request_response($status,$describe,$resdata);
            }
            if($afterpromoData['leader'] =='promock'){ // 存款范围为主导
                $length_2 = count($depositLimitsArr);
                for ($ii=0;$ii<$length_2;$ii++) { // 存款要求
                    if($ii ==($length_2-1) ){ // 最后一个
                        if( $depositAmount>=$depositLimitsArr[$ii]){ // 判断是否在区间范围内
                            $depositBonus = $promosBonusArr[$ii]; //  活动派发金额
                        }
                    }else if( $depositAmount>=$depositLimitsArr[$ii] && $depositAmount<$depositLimitsArr[$ii+1] ){ // 判断是否在区间范围内
                        $depositBonus = $promosBonusArr[$ii]; //  活动派发金额
                    }
                }
            }
        }
    }
   // exit($depositBonus.'-----');

   $ip_addr = get_ip(); // 获取 IP
// 需要插入的数据
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['Alias'] = $Alias; // 会员真实姓名
    $data['Phone'] = $Phone; // 会员手机号码
    $data['bankAccount'] = $Bank_Account; // 银行账号
    $data['name'] = $type_flag;
    $data['deposit'] = $depositAmount; // 存款金额
    $data['depositDay'] = $depositDays; // 存款天数
    $data['profitable'] = $userWinTotal; // 负盈利
    $data['eventName'] = $afterpromoData['title'];
    $data['gameType'] = $afterpromoData['gameType']; // 游戏名称
    $data['applyIp'] = $ip_addr; // 申请IP
    $data['add_month'] = $cur_month; // 添加月份
    $data['add_day'] = $todayNow; // 添加日期
    $data['add_time'] = $timeNow; // 添加时间
    $data['upd_time'] = $timeNow; // 修改时间
    $data['review_time'] = $timeNow; // 派发时间
    $data['review_name'] = ''; // 审核人
    $data['status'] = 2;

    if($afterpromoData['mergeOrSplit']=='yes') { // yes 需要分开统计,not 不需要分开

        foreach ($gameTypeDetailsArr as $kk=>$vv) { // 每个分类的有效投注
            foreach($depositBonusDisArr as $hh=>$val){
                if($vv==$hh){
                    foreach ($promosValidBetArr as $k_v=>$v_v){
                        if($vv==$k_v){
                            $data['totalBet'] = $v_v['valid_money']; // 有效投注
                        }
                    }
                    $data['promoGold'] = sprintf("%.2f", $val);  // 转运金额
                    $data['gameTypeDetails'] = $vv; // 游戏名称分类

                    $promoGet = addPromosAction($data); // 查询与插入
                }
            }
        }

    }else{
        if($depositBonus == 0){
            $status = '400.10';
            $describe = '不符合申请条件';  //派送金额统计不能为0
            original_phone_request_response($status,$describe,$resdata);
        }
        $data['totalBet'] = $valid_money; // 有效投注
        $data['promoGold'] = sprintf("%.2f", $depositBonus);  // 转运金额
        $data['gameTypeDetails'] = $afterpromoData['gameTypeDetails']; // 游戏名称分类

        $promoGet = addPromosAction($data); // 查询与插入
    }

    if($promoGet){
        $status = '200';
        $describe = '已申请'.$afterpromoData['title'].'活动,请等待派发'.$data['promoGold'].'元彩金';
        original_phone_request_response($status,$describe,$resdata);
    }else{
        $status = '500';
        $describe = '系统繁忙，请稍后再试';
        original_phone_request_response($status,$describe,$resdata);
    }
}

?>