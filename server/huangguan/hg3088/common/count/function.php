<?php
/**
 * 统计公共方法
 * User: windows 7
 * Date: 2019/11/29
 */

/**
 * 计算日期
 * @param $start_date
 * @param $end_date
 * @param string $type
 * @return array
 */
function dayRange($start_date, $end_date, $type = 'day'){
    $dateRange = [];
    $dt_start = strtotime($start_date);
    $dt_end = strtotime($end_date);
    while ($dt_start <= $dt_end){
        $dateRange[] = date($type == 'day' ? 'Y-m-d' : 'Y-m', $dt_start);
        $dt_start = strtotime('+1 ' . $type, $dt_start);
    }
    return $dateRange;
}

/**
 * 新进代理统计
 * @param $dateStart
 * @param $dateEnd
 * @return array
 */
function countAgent($dateStart, $dateEnd){
    global $dbLink;
    $agentCountData = [
        'C' => 0,
        'D' => 0
    ];
    $sql = 'SELECT count(*) AS totalCount, `Level` FROM ' . DBPREFIX . 'web_agents_data WHERE `AddDate` BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '" GROUP BY `Level`';
    $result = mysqli_query($dbLink, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        if($row['Level'] == 'C'){
            $agentCountData['C'] = $row['totalCount'];
        }else if($row['Level'] == 'D'){
            $agentCountData['D'] = $row['totalCount'];
        }
    }
    return $agentCountData;
}

/**
 * 新进会员统计方法
 * @param $dateStart
 * @param $dateEnd
 * @param string $agent
 * @return array
 */
function countMember($dateStart, $dateEnd, $agent = ''){
    global $dbLink;
    $where = ' `AddDate` BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '"';
    if($agent){
        $where .= ' AND Agents = "' . $agent . '"';
    }
    $memberReg = $memberDeposit = [];
    // 新注册会员
    $sqlReg = 'SELECT `ID` FROM ' . DBPREFIX . 'web_member_data WHERE ' . $where;
    $resultReg = mysqli_query($dbLink, $sqlReg);
    while ($rowReg = mysqli_fetch_assoc($resultReg)){
        $memberReg[] = $rowReg['ID'];
    }
    // todo 彩金、优惠、返水
    // 存款会员（包括三方、公司等，不包括彩金、优惠、返水）
    $sqlDeposit = 'SELECT `userid` FROM ' . DBPREFIX . 'web_sys800_data WHERE `Type` ="S" AND Checked = 1 AND `discounType` NOT IN (3, 4) AND `Payway` NOT IN ("O", "G") AND ' . $where;
    $resultDeposit = mysqli_query($dbLink, $sqlDeposit);
    while ($rowDeposit = mysqli_fetch_assoc($resultDeposit)){
        $memberDeposit[] = $rowDeposit['userid'];
    }
    // 新注册会员中的存款会员
    $memberRegDeposit = array_intersect($memberReg, $memberDeposit);
    return [
        'reg' => count($memberReg),
        'deposit' => count($memberRegDeposit)
    ];
}


/**
 * 存取款、返点统计
 * @param $dateStart
 * @param $dateEnd
 * @return array
 */
function countRST($dateStart, $dateEnd){
    global $dbLink;
    $countSRT = [
        'S' => [
            'total_num' => 0,
            'total_money' => '0.00',
        ],
        'R' => [
            'total_num' => 0,
            'total_money' => '0.00',
        ],
        'T' => [
            'total_num' => 0,
            'total_money' => '0.00',
        ]
    ];
    // todo 彩金、优惠、返水
    // 存款总量（不包括优惠、返水、彩金）
    $sqlDeposit = 'SELECT COUNT(userid) AS total_num, SUM(moneyf) AS total_before, SUM(currency_after) AS total_after, `Type` FROM ' . DBPREFIX . 'web_sys800_data WHERE addDate BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '" AND `Type` ="S" AND Checked = 1 AND `discounType` NOT IN (3, 4) AND `Payway` NOT IN ("O", "G") GROUP BY `userid`';
    $resultDeposit = mysqli_query($dbLink, $sqlDeposit);
    while ($rowDeposit = mysqli_fetch_assoc($resultDeposit)) {
        $countSRT['S']['total_num'] ++;
        $countSRT['S']['total_money'] = $rowDeposit['total_after'] - $rowDeposit['total_before'];
    }

    // 出款总量&返点总量
    $sql = 'SELECT COUNT(userid) AS total_num, SUM(moneyf) AS total_before, SUM(currency_after) AS total_after, `Type` FROM ' . DBPREFIX . 'web_sys800_data WHERE addDate BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '" AND `Type` IN ("R","T") AND Checked = 1  GROUP BY `Type`, `userid`';
    $result = mysqli_query($dbLink, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        $countSRT[$row['Type']]['total_num'] ++;
        $countSRT[$row['Type']]['total_money'] += ($row['total_after'] - $row['total_before']);
    }

    // 遗漏统计的返点总量（人工补单返点）
    $manualBackNum = $manualBackMoney = 0;
    $sqlManual = 'SELECT COUNT(userid) AS total_num, SUM(moneyf) AS total_before, SUM(currency_after) AS total_after, `Type` FROM ' . DBPREFIX . 'web_sys800_data WHERE addDate BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '" AND `Type` = "S" AND Checked = 1 AND `discounType` = 3 GROUP BY `userid`';
    $resultManual = mysqli_query($dbLink, $sqlManual);
    while ($rowManual = mysqli_fetch_assoc($resultManual)) {
        $manualBackNum ++;
        $manualBackMoney = $rowManual['total_after'] - $rowManual['total_before'];
    }
    if($manualBackNum) {
        $countSRT['R']['total_num'] += $manualBackNum;
        $countSRT['R']['total_money'] += $manualBackMoney;
    }
    return $countSRT;
}

/**
 * 会员历史数据统计
 * @param $dateStart
 * @param $dateEnd
 * @param string $userId
 * @return mixed
 */
function countHistoryBetMember($dateStart, $dateEnd, $userId = ''){
    global $dbLink, $database;

    // 初始化投注统计日结表
    $data_history_hg = $data_history_cp = $data_history_ag = $data_history_ag_dianzi = $data_history_ag_dayu = $data_history_ky = $data_history_hgqp = $data_history_vgqp = $data_history_lyqp = $data_history_klqp
        = $data_history_mg = $data_history_avia = $data_history_fire = $data_history_ssc = $data_history_project = $data_history_trace = $data_history_og = $data_history_mw = $data_history_cq = $data_history_fg
        = $data_history_bbin = [];

    // 初始化投注统计人数
    $mem_hg = $mem_cp = $mem_ag = $mem_ag_dianzi = $mem_ag_dayu = $mem_ky = $mem_hgqp = $mem_vgqp = $mem_lyqp = $mem_klqp = $mem_mg = $mem_avia = $mem_fire = $mem_ssc = $mem_project = $mem_trace = $mem_og = $mem_mw = $mem_cq = $mem_fg = $mem_bbin = [];

    $sWhere = ' 1 ';
    $sWhere_hg = $sWhere_cp = $sWhere_ag = $sWhere_ky = $sWhere_hgqp = $sWhere_vgqp = $sWhere_lyqp = $sWhere_klqp = $sWhere_mg = $sWhere_avia = $sWhere_fire = $sWhere_thirdcp = $sWhere_og = $sWhere_mw = $sWhere_cq = $sWhere_fg = $sWhere_bbin = $sWhere;
    if($userId){
        if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票
            $aCp_default = $database['cpDefault'];
            $cpDbLink = @mysqli_connect($aCp_default['host'], $aCp_default['user'], $aCp_default['password'], $aCp_default['dbname'], $aCp_default['port']) or die("mysqli connect error" . mysqli_connect_error());
            // 查询体育彩票在体育平台的hguid
            $sql = 'SELECT id, hguid FROM gxfcy_user WHERE hguid = ' . $userId . ' LIMIT 1';
            $result = mysqli_query($cpDbLink, $sql);
            $cpUser = mysqli_fetch_assoc($result);
            $sWhere_cp = $sWhere. " AND userid = " . $cpUser['id']; // 日结报表中的userid是彩票平台的userid
        }else{
            $sWhere_thirdcp = $sWhere. " AND hg_uid = '$userId'";
        }
        $sWhere_hg = $sWhere . " AND `userid` = '$userId'";
        $sWhere_ag = $sWhere. " AND userid = '$userId'";
        $sWhere_ky = $sWhere. " AND userid = '$userId'";
        $sWhere_hgqp = $sWhere. " AND userid = '$userId'";
        $sWhere_vgqp = $sWhere. " AND userid = '$userId'";
        $sWhere_lyqp = $sWhere. " AND userid = '$userId'";
        $sWhere_klqp = $sWhere. " AND userid = '$userId'";
        $sWhere_mg = $sWhere. " AND userid = '$userId'";
        $sWhere_avia = $sWhere. " AND userid = '$userId'";
        $sWhere_fire = $sWhere. " AND userid = '$userId'";
        $sWhere_og = $sWhere. " AND userid = '$userId'";
        $sWhere_mw = $sWhere. " AND userid = '$userId'";
        $sWhere_cq = $sWhere. " AND userid = '$userId'";
        $sWhere_fg = $sWhere. " AND userid = '$userId'";
        $sWhere_bbin = $sWhere. " AND userid = '$userId'";
    }

    // 体育主数据
    $res_hg = mysqli_query($dbLink, "SELECT `userid`,`username`, SUM(`count_pay`) AS count_pay, SUM(`total`) AS total, SUM(`valid_money`) AS valid_money, SUM(`user_win`) AS user_win FROM " . DBPREFIX . "web_report_history_report_data WHERE $sWhere_hg AND M_Date >= '" . $dateStart . "' AND M_Date<='" . $dateEnd . "' GROUP BY `userid`");
    $cou_hg = mysqli_num_rows($res_hg);
    if ($cou_hg > 0) {
        while ($row_hg = mysqli_fetch_assoc($res_hg)){
            $data_history_hg['valid_money'] += $row_hg['valid_money'];
            $data_history_hg['user_win'] += $row_hg['user_win'];
            $mem_hg[] = $row_hg['userid'];
        }
    }
    if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票
        // 彩票主数据，测试代理线不统计
        $aCp_default = $database['cpDefault'];
        $cpDbLink = @mysqli_connect($aCp_default['host'], $aCp_default['user'], $aCp_default['password'], $aCp_default['dbname'], $aCp_default['port']) or die("mysqli connect error" . mysqli_connect_error());
        $sql = "SELECT `userid`,`username`, SUM(`count_pay`) AS count_pay, SUM(`total`) AS total, SUM(`valid_money`) AS valid_money, SUM(`user_win`) AS user_win 
            FROM gxfcy_history_bill_report_less_12hours 
            WHERE $sWhere_cp AND hg_agent_uid!=521 AND hg_agent_uid!=522 AND bet_time BETWEEN '" . strtotime($dateStart) . "' AND '" . strtotime($dateEnd) . "' GROUP BY `userid`";
        $res_cp = mysqli_query($cpDbLink, $sql);
        $cou_cp = mysqli_num_rows($res_cp);
        if ($cou_cp > 0) {
            while ($row_cp = mysqli_fetch_assoc($res_cp)){
                $data_history_cp['valid_money'] += $row_cp['valid_money'];
                $data_history_cp['user_win'] += $row_cp['user_win'];
                $mem_cp[] = $row_cp['userid'];
            }
        }
    } else { // 太阳城-10001、金沙-10002、威尼斯人-10003、3366-10004
        // 第三方彩票信用主数据（报表数据）
        $sql = "SELECT `hg_uid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
            FROM " . DBPREFIX . "web_third_ssc_history_report 
            WHERE $sWhere_thirdcp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `hg_uid`";
        $res_ssc = mysqli_query($dbLink, $sql);
        $cou_ssc = mysqli_num_rows($res_ssc);
        if ($cou_ssc > 0) {
            while ($row_ssc = mysqli_fetch_assoc($res_ssc)){
                $row_ssc['user_win'] = $row_ssc['user_win'] - $row_ssc['valid_money']; // 会员输赢（赢：+；输：-）
                $data_history_ssc['valid_money'] += $row_ssc['valid_money'];
                $data_history_ssc['user_win'] += $row_ssc['user_win'];
                if( false !== strpos($row_ssc['username'], '_')){ // 拉取报表中的用户名有带前缀也有不带前缀的处理
                    $row_ssc['username'] = substr($row_ssc['username'],strripos($row_ssc['username'],"_") + 1);
                }
                $mem_ssc[] = $row_ssc['hg_uid'];
            }
        }

        // 第三方彩票官方主数据（报表数据）
        $sql = "SELECT `hg_uid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
            FROM " . DBPREFIX . "web_third_projects_history_report 
            WHERE $sWhere_thirdcp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `hg_uid`";
        $res_project = mysqli_query($dbLink, $sql);
        $cou_project = mysqli_num_rows($res_project);
        if ($cou_project > 0) {
            while ($row_project = mysqli_fetch_assoc($res_project)){
                $row_project['user_win'] = $row_project['user_win'] - $row_project['valid_money']; // 会员输赢（赢：+；输：-）
                $data_history_project['valid_money'] += $row_project['valid_money'];
                $data_history_project['user_win'] += $row_project['user_win'];
                if( false !== strpos($row_project['username'], '_')){ // 拉取报表中的用户名有带前缀也有不带前缀的处理
                    $row_project['username'] = substr($row_project['username'],strripos($row_project['username'],"_") + 1);
                }
                $mem_project[] = $row_project['hg_uid'];
            }
        }

        // 第三方彩票官方追号主数据（报表数据）
        $sql = "SELECT `hg_uid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
            FROM " . DBPREFIX . "web_third_traces_history_report
            WHERE $sWhere_thirdcp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `hg_uid`";
        $res_trace = mysqli_query($dbLink, $sql);
        $cou_trace = mysqli_num_rows($res_trace);
        if ($cou_trace > 0) {
            while ($row_trace = mysqli_fetch_assoc($res_trace)){
                $row_trace['user_win'] = $row_trace['user_win'] - $row_trace['valid_money']; // 会员输赢（赢：+；输：-）
                $data_history_trace['valid_money'] += $row_trace['valid_money'];
                $data_history_trace['user_win'] += $row_trace['user_win'];
                if( false !== strpos($row_trace['username'], '_')){ // 拉取报表中的用户名有带前缀也有不带前缀的处理
                    $row_trace['username'] = substr($row_trace['username'],strripos($row_trace['username'],"_") + 1);
                }
                $mem_trace[] = $row_trace['hg_uid'];
            }
        }
    }

    // AG视讯主数据
    $res_ag = mysqli_query($dbLink, "SELECT `userid`,`username`, SUM(`count_pay`) AS count_pay, SUM(`total`) AS total, SUM(`valid_money`) AS valid_money, SUM(`profit`) AS user_win FROM " . DBPREFIX . "ag_projects_history_report WHERE $sWhere_ag AND bet_time BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "' AND game_code = 'BR' GROUP BY `userid`");
    $cou_ag = mysqli_num_rows($res_ag);
    if ($cou_ag > 0) {
        while ($row_ag = mysqli_fetch_assoc($res_ag)){
            $data_history_ag['valid_money'] += $row_ag['valid_money'];
            $data_history_ag['user_win'] += $row_ag['user_win'];
            $mem_ag[] = $row_ag['userid'];
        }
    }

    // AG电子主数据
    $res_ag_dianzi = mysqli_query($dbLink, "SELECT `userid`,`username`, SUM(`count_pay`) AS count_pay, SUM(`total`) AS total, SUM(`valid_money`) AS valid_money, SUM(`profit`) AS user_win FROM " . DBPREFIX . "ag_projects_history_report WHERE $sWhere_ag AND bet_time BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "' AND (game_code = '' OR game_code = 'SLOT') GROUP BY `userid`");
    $cou_ag_dianzi = mysqli_num_rows($res_ag_dianzi);
    if ($cou_ag_dianzi > 0) {
        while ($row_ag_dianzi = mysqli_fetch_assoc($res_ag_dianzi)){
            $data_history_ag_dianzi['valid_money'] += $row_ag_dianzi['valid_money'];
            $data_history_ag_dianzi['user_win'] += $row_ag_dianzi['user_win'];
            $mem_ag_dianzi[] = $row_ag_dianzi['userid'];
        }
    }

    // AG捕鱼王打鱼主数据
    $res_ag_dayu = mysqli_query($dbLink, "SELECT `userid`,`UserName`, SUM(`BulletOutNum`) as count_pay, SUM(`Cost`) as valid_money, SUM(`Earn`) as shouru FROM " . DBPREFIX . "ag_buyu_scene WHERE $sWhere_ag AND EndTime BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "' GROUP BY `userid`");
    $cou_ag_dayu = mysqli_num_rows($res_ag_dayu);
    if ($cou_ag_dayu>0) {
        while ($row_ag_dayu = mysqli_fetch_assoc($res_ag_dayu)){
            $row_ag_dayu['user_win'] = $row_ag_dayu['shouru'] - $row_ag_dayu['valid_money']; // 会员输赢（赢：+；输：-）
            $data_history_ag_dayu['valid_money'] += $row_ag_dayu['valid_money'];
            $data_history_ag_dayu['user_win'] += $row_ag_dayu['user_win'];
            $mem_ag_dayu[] = $row_ag_dayu['userid'];
        }
    }

    // KY主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` FROM " . DBPREFIX . "ky_history_report WHERE $sWhere_ky AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_ky = mysqli_query($dbLink, $sql);
    $cou_ky = mysqli_num_rows($res_ky);
    if ($cou_ky > 0) {
        while ($row_ky = mysqli_fetch_assoc($res_ky)){
            $data_history_ky['valid_money'] += $row_ky['valid_money'];
            $data_history_ky['user_win'] += $row_ky['user_win'];
            $mem_ky[] = $row_ky['userid'];
        }
    }

    // HGQP主数据
//    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` FROM " . DBPREFIX . "ff_history_report WHERE $sWhere_hgqp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
//    $res_hgqp = mysqli_query($dbLink, $sql);
//    $cou_hgqp = mysqli_num_rows($res_hgqp);
//    if ($cou_hgqp > 0) {
//        while ($row_hgqp = mysqli_fetch_assoc($res_hgqp)){
//            $data_history_hgqp['valid_money'] += $row_hgqp['valid_money'];
//            $data_history_hgqp['user_win'] += $row_hgqp['user_win'];
//            $mem_hgqp[] = $row_hgqp['userid'];
//        }
//    }

    // VGQP主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` FROM " . DBPREFIX . "vg_history_report WHERE $sWhere_vgqp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_vgqp = mysqli_query($dbLink, $sql);
    $cou_vgqp = mysqli_num_rows($res_vgqp);
    if ($cou_vgqp > 0) {
        while ($row_vgqp = mysqli_fetch_assoc($res_vgqp)){
            $data_history_vgqp['valid_money'] += $row_vgqp['valid_money'];
            $data_history_vgqp['user_win'] += $row_vgqp['user_win'];
            $mem_vgqp[] = $row_vgqp['userid'];
        }
    }

    // 乐游棋牌主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` FROM " . DBPREFIX . "ly_history_report WHERE $sWhere_lyqp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_lyqp = mysqli_query($dbLink, $sql);
    $cou_lyqp = mysqli_num_rows($res_lyqp);
    if ($cou_lyqp > 0) {
        while ($row_lyqp = mysqli_fetch_assoc($res_lyqp)){
            $data_history_lyqp['valid_money'] += $row_lyqp['valid_money'];
            $data_history_lyqp['user_win'] += $row_lyqp['user_win'];
            $mem_lyqp[] = $row_lyqp['userid'];
        }
    }

    // 快乐棋牌主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "kl_history_report WHERE $sWhere_klqp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_klqp = mysqli_query($dbLink, $sql);
    $cou_klqp = mysqli_num_rows($res_klqp);
    if ($cou_klqp > 0) {
        while ($row_klqp = mysqli_fetch_assoc($res_klqp)){
            $data_history_klqp['valid_money'] += $row_klqp['valid_money'];
            $data_history_klqp['user_win'] += $row_klqp['user_win'];
            $mem_klqp[] = $row_klqp['userid'];
        }
    }

    // MG电子主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "mg_history_report WHERE $sWhere_mg AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_mg = mysqli_query($dbLink, $sql);
    $cou_mg = mysqli_num_rows($res_mg);
    if ($cou_mg > 0) {
        while ($row_mg = mysqli_fetch_assoc($res_mg)){
            $data_history_mg['valid_money'] += $row_mg['valid_money'];
            $data_history_mg['user_win'] += $row_mg['user_win'];
            $mem_mg[] = $row_mg['userid'];
        }
    }

    // 泛亚电竞主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "avia_history_report WHERE $sWhere_avia AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_avia = mysqli_query($dbLink, $sql);
    $cou_avia = mysqli_num_rows($res_avia);
    if ($cou_avia > 0) {
        while ($row_avia = mysqli_fetch_assoc($res_avia)){
            $data_history_avia['valid_money'] += $row_avia['valid_money'];
            $data_history_avia['user_win'] += $row_avia['user_win'];
            $mem_avia[] = $row_avia['userid'];
        }
    }

    // 雷火电竞主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "fire_history_report WHERE $sWhere_fire AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_fire = mysqli_query($dbLink, $sql);
    $cou_fire = mysqli_num_rows($res_fire);
    if ($cou_fire > 0) {
        while ($row_fire = mysqli_fetch_assoc($res_fire)){
            $data_history_fire['valid_money'] += $row_fire['valid_money'];
            $data_history_fire['user_win'] += $row_fire['user_win'];
            $mem_fire[] = $row_fire['userid'];
        }
    }

    // OG视讯主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "og_history_report 
        WHERE $sWhere_og AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_og = mysqli_query($dbLink, $sql);
    $cou_og = mysqli_num_rows($res_og);
    if ($cou_og > 0) {
        while ($row_og = mysqli_fetch_assoc($res_og)){
            $data_history_og['valid_money'] += $row_og['valid_money'];
            $data_history_og['user_win'] += $row_og['user_win'];
            $mem_og[] = $row_og['userid'];
        }
    }

    // MW电子主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "mw_history_report 
        WHERE $sWhere_mw AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_mw = mysqli_query($dbLink, $sql);
    $cou_mw = mysqli_num_rows($res_mw);
    if ($cou_mw > 0) {
        while ($row_mw = mysqli_fetch_assoc($res_mw)){
            $data_history_mw['valid_money'] += $row_mw['valid_money'];
            $data_history_mw['user_win'] += $row_mw['user_win'];
            $mem_mw[] = $row_mw['userid'];
        }
    }

    // CQ9电子主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "cq9_history_report 
        WHERE $sWhere_cq AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_cq = mysqli_query($dbLink, $sql);
    $cou_cq = mysqli_num_rows($res_cq);
    if ($cou_cq > 0) {
        while ($row_cq = mysqli_fetch_assoc($res_cq)){
            $row_cq['user_win'] = $row_cq['user_win'] - $row_cq['valid_money']; // 会员输赢（赢：+；输：-）
            $data_history_cq['valid_money'] += $row_cq['valid_money'];
            $data_history_cq['user_win'] += $row_cq['user_win'];
            $mem_cq[] = $row_cq['userid'];
        }
    }

    // FG电子主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "fg_history_report 
        WHERE $sWhere_fg AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_fg = mysqli_query($dbLink, $sql);
    $cou_fg = mysqli_num_rows($res_fg);
    if ($cou_fg > 0) {
        while ($row_fg = mysqli_fetch_assoc($res_fg)){
            $row_fg['user_win'] = $row_fg['user_win'] - $row_fg['valid_money']; // 会员输赢（赢：+；输：-）
            $data_history_fg['valid_money'] += $row_fg['valid_money'];
            $data_history_fg['user_win'] += $row_fg['user_win'];
            $mem_fg[] = $row_fg['userid'];
        }
    }

    // BBIN视讯主数据
    $sql = "SELECT `userid`,`username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "jx_bbin_history_report 
        WHERE $sWhere_bbin AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_bbin = mysqli_query($dbLink, $sql);
    $cou_bbin = mysqli_num_rows($res_bbin);
    if ($cou_bbin > 0) {
        while ($row_bbin = mysqli_fetch_assoc($res_bbin)){
            $row_bbin['user_win'] = $row_bbin['user_win'] - $row_bbin['valid_money']; // 会员输赢（赢：+；输：-）
            $data_history_bbin['valid_money'] += $row_bbin['valid_money'];
            $data_history_bbin['user_win'] += $row_bbin['user_win'];
            $mem_bbin[] = $row_bbin['userid'];
        }
    }

    if($userId){ // 会员有效投注为打码量
        $memDataCount['hg'] = isset($data_history_hg['valid_money']) ? $data_history_hg['valid_money'] : 0;
        if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票
            $memDataCount['cp'] = isset($data_history_cp['valid_money']) ? $data_history_cp['valid_money'] : 0;
        } else { // 三方彩票
            $memDataCount['ssc'] = isset($data_history_ssc['valid_money']) ? $data_history_ssc['valid_money'] : 0;
            $memDataCount['project'] = isset($data_history_project['valid_money']) ? $data_history_project['valid_money'] : 0;
            $memDataCount['trace'] = isset($data_history_trace['valid_money']) ? $data_history_trace['valid_money'] : 0;
        }
        $memDataCount['ag'] = isset($data_history_ag['valid_money']) ? $data_history_ag['valid_money'] : 0;
        $memDataCount['ag_dianzi'] = isset($data_history_ag_dianzi['valid_money']) ? $data_history_ag_dianzi['valid_money'] : 0;
        $memDataCount['ag_dayu'] =  isset($data_history_ag_dayu['valid_money']) ? $data_history_ag_dayu['valid_money'] : 0;
        $memDataCount['ky'] = isset($data_history_ky['valid_money']) ? $data_history_ky['valid_money'] : 0;
        //$memDataCount['hgqp'] = isset($data_history_hgqp['valid_money']) ? $data_history_hgqp['valid_money'] : 0;
        $memDataCount['vgqp'] = isset($data_history_vgqp['valid_money']) ? $data_history_vgqp['valid_money'] : 0;
        $memDataCount['lyqp'] = isset($data_history_lyqp['valid_money']) ? $data_history_lyqp['valid_money'] : 0;
        $memDataCount['klqp'] = isset($data_history_klqp['valid_money']) ? $data_history_klqp['valid_money'] : 0;
        $memDataCount['mg'] = isset($data_history_mg['valid_money']) ? $data_history_mg['valid_money'] : 0;
        $memDataCount['avia'] = isset($data_history_avia['valid_money']) ? $data_history_avia['valid_money'] : 0;
        $memDataCount['fire'] = isset($data_history_fire['valid_money']) ? $data_history_fire['valid_money'] : 0;
        $memDataCount['og'] = isset($data_history_og['valid_money']) ? $data_history_og['valid_money'] : 0;
        $memDataCount['mw'] = isset($data_history_mw['valid_money']) ? $data_history_mw['valid_money'] : 0;
        $memDataCount['cq'] = isset($data_history_cq['valid_money']) ? $data_history_cq['valid_money'] : 0;
        $memDataCount['fg'] = isset($data_history_fg['valid_money']) ? $data_history_fg['valid_money'] : 0;
        $memDataCount['bbin'] = isset($data_history_bbin['valid_money']) ? $data_history_bbin['valid_money'] : 0;
    }else{ // 默认返回统计数据
        $memDataCount['mem_count'] = count(array_unique(array_merge($mem_hg, $mem_cp, $mem_ag, $mem_ag_dianzi, $mem_ag_dayu, $mem_ky, $mem_hgqp, $mem_vgqp, $mem_lyqp, $mem_klqp, $mem_mg, $mem_avia, $mem_fire, $mem_ssc, $mem_project, $mem_trace, $mem_og, $mem_mw, $mem_cq, $mem_fg, $mem_bbin)));
        $memDataCount['valid_money'] = $data_history_hg['valid_money'] + $data_history_cp['valid_money'] + $data_history_ag['valid_money'] + $data_history_ag_dianzi['valid_money'] + $data_history_ag_dayu['valid_money'] + $data_history_ky['valid_money'] +
            $data_history_hgqp['valid_money'] + $data_history_vgqp['valid_money'] + $data_history_lyqp['valid_money'] + $data_history_klqp['valid_money'] + $data_history_mg['valid_money'] + $data_history_avia['valid_money'] + $data_history_fire['valid_money'] + $data_history_ssc['valid_money'] + $data_history_project['valid_money'] +
            $data_history_trace['valid_money'] + $data_history_og['valid_money'] + $data_history_mw['valid_money'] + $data_history_cq['valid_money'] + $data_history_fg['valid_money'] + $data_history_bbin['valid_money'];
        $memDataCount['user_win'] = $data_history_hg['user_win'] + $data_history_cp['user_win'] + $data_history_ag['user_win'] + $data_history_ag_dianzi['user_win'] + $data_history_ag_dayu['user_win'] + $data_history_ky['user_win'] + $data_history_hgqp['user_win'] +
            $data_history_vgqp['user_win'] + $data_history_lyqp['user_win'] + $data_history_klqp['user_win'] + $data_history_mg['user_win'] + $data_history_avia['user_win'] + $data_history_fire['user_win'] + $data_history_ssc['user_win'] + $data_history_project['user_win'] + $data_history_trace['user_win'] + $data_history_og['user_win'] +
            $data_history_mw['user_win'] + $data_history_cq['user_win'] + $data_history_fg['user_win'] + $data_history_bbin['user_win'];
    }
    return $memDataCount;
}

/**
 * 未入库历史报表的数据统计
 * @param $dateStart
 * @param $dateEnd
 * @param string $userId
 * @return array
 */
function countCurrentBetMember($dateStart, $dateEnd, $userId = ''){
    global $dbLink, $database;

    // 初始化投注统计日结表
    $data_current_hg = $data_current_cp = $data_current_ag = $data_current_ag_dianzi = $data_current_ag_dayu = $data_current_ky = $data_current_hgqp = $data_current_vgqp = $data_current_lyqp = $data_current_klqp
        = $data_current_mg = $data_current_avia = $data_current_fire = $data_current_ssc = $data_current_project = $data_current_trace = $data_current_og = $data_current_mw = $data_current_cq = $data_current_fg
        = $data_current_bbin = [];

    // 初始化投注统计人数
    $mem_hg = $mem_cp = $mem_ag = $mem_ag_dianzi = $mem_ag_dayu = $mem_ky = $mem_hgqp = $mem_vgqp = $mem_lyqp = $mem_klqp = $mem_mg = $mem_avia = $mem_fire = $mem_ssc = $mem_project = $mem_trace = $mem_og = $mem_mw = $mem_cq = $mem_fg = $mem_bbin = [];

    $sWhere = ' 1 ';
    $sWhere_hg = $sWhere_cp = $sWhere_ag = $sWhere_ky = $sWhere_hgqp = $sWhere_vgqp = $sWhere_lyqp = $sWhere_klqp = $sWhere_mg = $sWhere_avia = $sWhere_fire = $sWhere_thirdcp = $sWhere_og = $sWhere_mw = $sWhere_cq = $sWhere_fg = $sWhere_bbin = $sWhere;
    if($userId){
        if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票
            $aCp_default = $database['cpDefault'];
            $cpDbLink = @mysqli_connect($aCp_default['host'], $aCp_default['user'], $aCp_default['password'], $aCp_default['dbname'], $aCp_default['port']) or die("mysqli connect error" . mysqli_connect_error());
            // 查询体育彩票在体育平台的hguid
            $sql = 'SELECT id, hguid FROM gxfcy_user WHERE hguid = ' . $userId . ' LIMIT 1';
            $result = mysqli_query($cpDbLink, $sql);
            $cpUser = mysqli_fetch_assoc($result);
            $sWhere_cp = $sWhere. " AND userid = " . $cpUser['id']; // 日结报表中的userid是彩票平台的userid
        }else{
            $sWhere_thirdcp = $sWhere. " AND hg_uid = '$userId'";
        }
        $sWhere_hg = $sWhere . " AND `userid` = '$userId'";
        $sWhere_ag = $sWhere. " AND userid = '$userId'";
        $sWhere_ky = $sWhere. " AND userid = '$userId'";
        $sWhere_hgqp = $sWhere. " AND userid = '$userId'";
        $sWhere_vgqp = $sWhere. " AND userid = '$userId'";
        $sWhere_lyqp = $sWhere. " AND userid = '$userId'";
        $sWhere_klqp = $sWhere. " AND userid = '$userId'";
        $sWhere_mg = $sWhere. " AND userid = '$userId'";
        $sWhere_avia = $sWhere. " AND userid = '$userId'";
        $sWhere_fire = $sWhere. " AND userid = '$userId'";
        $sWhere_og = $sWhere. " AND userid = '$userId'";
        $sWhere_mw = $sWhere. " AND userid = '$userId'";
        $sWhere_cq = $sWhere. " AND userid = '$userId'";
        $sWhere_fg = $sWhere. " AND userid = '$userId'";
        $sWhere_bbin = $sWhere. " AND userid = '$userId'";
    }

    // 皇冠体育（有效投注）
    $res_hg = mysqli_query($dbLink, "SELECT M_Name, count(1) AS count_pay, sum(BetScore) AS total, sum(VGOLD) AS valid_money, sum(M_Result) AS user_win FROM ".DBPREFIX."web_report_data WHERE $sWhere_hg AND updateTime BETWEEN '".$dateStart."' and '".$dateEnd."' AND `checked` = 1 AND `testflag` = 0 AND `Cancel` = 0 GROUP BY `userid`");
    while ($row_hg = mysqli_fetch_assoc($res_hg)) {
        $data_current_hg['valid_money'] += $row_hg['valid_money'];
        $data_current_hg['user_win'] += $row_hg['user_win'];
        $mem_hg[] = $row_hg['M_Name'];
    }

    if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票
        // 体育彩票（使用美东时间）
        $dateStart_cp = strtotime($dateStart);
        $dateEnd_cp = strtotime($dateEnd);
        $aCp_default = $database['cpDefault'];
        $cpDbLink = @mysqli_connect($aCp_default['host'], $aCp_default['user'], $aCp_default['password'], $aCp_default['dbname'], $aCp_default['port']) or die("mysqli connect error" . mysqli_connect_error());
        $res_cp = mysqli_query($cpDbLink, "SELECT username, count(1) AS count_pay, sum(drop_money) AS total, sum(valid_money) AS valid_money, sum(user_win) AS user_win FROM gxfcy_bill WHERE $sWhere_cp AND bet_time BETWEEN '" . $dateStart_cp . "' AND '" . $dateEnd_cp . "' GROUP BY `userid`");
        while ($row_cp = mysqli_fetch_assoc($res_cp)) {
            $data_current_cp['valid_money'] += $row_cp['valid_money'];
            $data_current_cp['user_win'] += $row_cp['user_win'];
            $mem_cp[] = $row_cp['username'];
        }
    } else { // 太阳城-10001、金沙-10002、威尼斯人-10003、3366-10004
        // 第三方彩票信用数据
        // status 0: 正常；1：已撤销；2：未中奖；3：已中奖；4：和局；5：系统撤销
        $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`money`) AS `valid_money`, SUM(`money`) AS `total`, SUM(`bonus`) AS `user_win`, SUM(`rebateMoney`) AS `total_revenue`
            FROM " . DBPREFIX . "web_third_ssc_data
            WHERE $sWhere_thirdcp AND `status` NOT IN (5) AND `counted_at` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `uid`";
        $res_cpssc = mysqli_query($dbLink, $sql);
        while ($row_cpssc = mysqli_fetch_assoc($res_cpssc)) {
            $row_cpssc['user_win'] = $row_cpssc['user_win'] - $row_cpssc['valid_money']; // 会员输赢（赢：+；输：-）
            $data_current_ssc = $row_cpssc;
            $data_current_ssc['valid_money'] += $row_cpssc['valid_money'];
            $data_current_ssc['user_win'] += $row_cpssc['user_win'];
            if( false !== strpos($row_cpssc['username'], '_')){ // 拉取报表中的用户名有带前缀也有不带前缀的处理
                $row_cpssc['username'] = substr($row_cpssc['username'],strripos($row_cpssc['username'],"_") + 1);
            }
            $mem_ssc[] = $row_cpssc['username'];
        }

        // 第三方彩票官方数据
        // status 0: 正常；1：已撤销；2：未中奖；3：已中奖；4：已派奖；5：系统撤销
        $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`, SUM(`prize`) AS `user_win`, SUM(`status_prize`) AS `total_revenue`
            FROM " . DBPREFIX . "web_third_projects_data
            WHERE $sWhere_thirdcp AND `status` NOT IN (1, 5) AND `counted_at` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `user_id`";
        $res_cpproject = mysqli_query($dbLink, $sql);
        while ($row_cpproject = mysqli_fetch_assoc($res_cpproject)) {
            $row_cpproject['user_win'] = $row_cpproject['user_win'] - $row_cpproject['valid_money']; // 会员输赢（赢：+；输：-）
            $data_current_project['valid_money'] += $row_cpproject['valid_money'];
            $data_current_project['user_win'] += $row_cpproject['user_win'];
            if( false !== strpos($row_cpproject['username'], '_')){ // 拉取报表中的用户名有带前缀也有不带前缀的处理
                $row_cpproject['username'] = substr($row_cpproject['username'],strripos($row_cpproject['username'],"_") + 1);
            }
            $mem_project[] = $row_cpproject['username'];
        }

        // 第三方彩票官方追号数据
        // status 0: 进行中；1：已完成；2：会员终止；3：管理员终止；4：系统终止
        $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`finished_amount`) AS `valid_money`, SUM(`finished_amount`) AS `total`, SUM(`prize`) AS `user_win`, SUM(`position`) AS `total_revenue`
            FROM " . DBPREFIX . "web_third_traces_data
            WHERE $sWhere_thirdcp AND `status` NOT IN (2,3,4,5) AND `bought_at` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `user_id`";
        $res_cptrace = mysqli_query($dbLink, $sql);
        while ($row_cptrace = mysqli_fetch_assoc($res_cptrace)) {
            $row_cptrace['user_win'] = $row_cptrace['user_win'] - $row_cptrace['valid_money']; // 会员输赢（赢：+；输：-）
            $data_current_trace['valid_money'] += $row_cptrace['valid_money'];
            $data_current_trace['user_win'] += $row_cptrace['user_win'];
            if( false !== strpos($row_cptrace['username'], '_')){ // 拉取报表中的用户名有带前缀也有不带前缀的处理
                $row_cptrace['username'] = substr($row_cptrace['username'],strripos($row_cptrace['username'],"_") + 1);
            }
            $mem_project[] = $row_cptrace['username'];
        }
    }

    // AG视讯
    $res_ag = mysqli_query($dbLink, "select username, count(1) AS count_pay, sum(amount) AS total, sum(valid_money) AS valid_money, sum(profit) AS user_win FROM ".DBPREFIX."ag_projects WHERE $sWhere_ag AND bettime BETWEEN '".$dateStart."' AND '".$dateEnd."' AND `type`='BR' GROUP BY `userid`");
    while ($row_ag = mysqli_fetch_assoc($res_ag)) {
        $data_current_ag['valid_money'] += $row_ag['valid_money'];
        $data_current_ag['user_win'] += $row_ag['user_win'];
        $mem_ag[] = $row_ag['username'];
    }

    // AG电子
    $res_ag_dianzi = mysqli_query($dbLink, "select username, count(1) AS count_pay, sum(amount) AS total, sum(valid_money) AS valid_money, sum(profit) AS user_win FROM ".DBPREFIX."ag_projects WHERE $sWhere_ag AND bettime BETWEEN '".$dateStart."' AND '".$dateEnd."' AND (`type`='' OR `type`='SLOT') GROUP BY `userid`");
    while ($row_ag_dianzi = mysqli_fetch_assoc($res_ag_dianzi)) {
        $data_current_ag_dianzi['valid_money'] += $row_ag_dianzi['valid_money'];
        $data_current_ag_dianzi['user_win'] += $row_ag_dianzi['user_win'];
        $mem_ag_dianzi[] = $row_ag_dianzi['username'];
    }

    // AG捕鱼王打鱼
    $res_ag_dayu = mysqli_query($dbLink, "select username, sum(BulletOutNum) as count_pay, sum(Cost) as valid_money, sum(Earn) as shouru from ".DBPREFIX."ag_buyu_scene where $sWhere_ag AND EndTime BETWEEN '".$dateStart."' and '".$dateEnd."' GROUP BY `userid`");
    while ($row_ag_dayu = mysqli_fetch_assoc($res_ag_dayu)) {
        $row_ag_dayu['user_win'] = $row_ag_dayu['shouru'] - $row_ag_dayu['valid_money']; // 会员输赢（赢：+；输：-）
        $data_current_ag_dayu['valid_money'] += $row_ag_dayu['valid_money'];
        $data_current_ag_dayu['user_win'] += $row_ag_dayu['user_win'];
        $mem_ag_dayu[] = $row_ag_dayu['username'];
    }

    // KY主数据
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`cellscore`) AS `valid_money`, SUM(`allbet`) AS `total`, SUM(`profit`) AS `user_win`, SUM(`revenue`) AS `total_revenue`
            FROM " . DBPREFIX . "ky_projects 
            WHERE $sWhere_ky AND `game_endtime` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_ky = mysqli_query($dbLink, $sql);
    while ($row_ky = mysqli_fetch_assoc($res_ky)) {
        $data_current_ky['valid_money'] += $row_ky['valid_money'];
        $data_current_ky['user_win'] += $row_ky['user_win'];
        $mem_ky[] = $row_ky['username'];
    }

    // HGQP数据
//    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`valid_bet`) AS `valid_money`, SUM(`bet`) AS `total`, SUM(`wincoins`) AS `user_win`, SUM(`board_fee`) AS `total_revenue`
//            FROM " . DBPREFIX . "ff_projects
//            WHERE $sWhere_hgqp AND `game_endtime` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
//    $res_hgqp = mysqli_query($dbLink, $sql);
//    while ($row_hgqp = mysqli_fetch_assoc($res_hgqp)) {
//        $data_current_hgqp['valid_money'] += $row_hgqp['valid_money'];
//        $data_current_hgqp['user_win'] += $row_hgqp['user_win'];
//        $mem_hgqp[] = $row_hgqp['username'];
//    }

    // VGQP数据
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`validbetamount`) AS `valid_money`, SUM(`betamount`) AS `total`, SUM(`money`) AS `user_win`, SUM(`serviceMoney`) AS `total_revenue`
            FROM " . DBPREFIX . "vg_projects 
            WHERE $sWhere_vgqp AND `game_endtime` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_vgqp = mysqli_query($dbLink, $sql);
    while ($row_vgqp = mysqli_fetch_assoc($res_vgqp)) {
        $data_current_vgqp['valid_money'] += $row_vgqp['valid_money'];
        $data_current_vgqp['user_win'] += $row_vgqp['user_win'];
        $mem_vgqp[] = $row_vgqp['username'];
    }

    // 乐游棋牌数据
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`cellscore`) AS `valid_money`, SUM(`allbet`) AS `total`, SUM(`profit`) AS `user_win`, SUM(`revenue`) AS `total_revenue`
            FROM " . DBPREFIX . "ly_projects 
            WHERE $sWhere_lyqp AND `game_endtime` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_lyqp = mysqli_query($dbLink, $sql);
    while ($row_lyqp = mysqli_fetch_assoc($res_lyqp)) {
        $data_current_lyqp['valid_money'] += $row_lyqp['valid_money'];
        $data_current_lyqp['user_win'] += $row_lyqp['user_win'];
        $mem_lyqp[] = $row_lyqp['username'];
    }

    // 快乐棋牌数据
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`, SUM(`prize`) AS `user_win`
            FROM " . DBPREFIX . "kl_projects 
            WHERE $sWhere_klqp AND `gametime` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_klqp = mysqli_query($dbLink, $sql);
    while ($row_klqp = mysqli_fetch_assoc($res_klqp)) {
        $data_current_klqp['valid_money'] += $row_klqp['valid_money'];
        $data_current_klqp['user_win'] += $row_klqp['user_win'];
        $mem_klqp[] = $row_klqp['username'];
    }

    // MG电子当天数据
    $total_mg = $total_payout_mg = 0;
    // 总盈利额
    $sql_total_payout = "SELECT SUM(`amount`) AS `total_payout`
            FROM " . DBPREFIX . "mg_projects 
            WHERE $sWhere_mg AND `transaction_time` >= '{$dateStart}' AND `transaction_time` < '{$dateEnd}' AND category='PAYOUT' GROUP BY `userid`";
    $res_total_payout = mysqli_query($dbLink, $sql_total_payout);
    while ($row_total_payout = mysqli_fetch_assoc($res_total_payout)) {
        $total_payout_mg += $row_total_payout['total_payout'];
    }
    // 总投注、有效投注
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`
            FROM " . DBPREFIX . "mg_projects 
            WHERE $sWhere_mg AND `transaction_time` BETWEEN '{$dateStart}' AND '{$dateEnd}' AND category='WAGER' GROUP BY `userid`";
    $res_mg = mysqli_query($dbLink, $sql);
    while ($row_mg = mysqli_fetch_assoc($res_mg)) {
        $data_current_mg['valid_money'] += $row_mg['valid_money'];
        $total_mg += $row_mg['total'];
        $mem_mg[] = $row_mg['username'];
    }
    $data_current_mg['user_win'] = $total_mg - $total_payout_mg;

    // 泛亚电竞主数据（泛亚是时时统计的，可以依然查历史表）
    $sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
            FROM " . DBPREFIX . "avia_history_report 
            WHERE $sWhere_avia AND `created_at` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_avia = mysqli_query($dbLink, $sql);
    while ($row_avia = mysqli_fetch_assoc($res_avia)) {
        $data_current_avia['valid_money'] += $row_avia['valid_money'];
        $data_current_avia['user_win'] += $row_avia['user_win'];
        $mem_avia[] = $row_avia['username'];
    }

    // 雷火电竞数据
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`amount`) AS `valid_money`, SUM(`amount`) AS `total`, SUM(`reward`) AS `user_win`
            FROM " . DBPREFIX . "fire_projects 
            WHERE $sWhere_fire AND `settlement_datetime` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_fire = mysqli_query($dbLink, $sql);
    while ($row_fire = mysqli_fetch_assoc($res_fire)) {
        $data_current_fire['valid_money'] += $row_fire['valid_money'];
        $data_current_fire['user_win'] += $row_fire['user_win'];
        $mem_fire[] = $row_fire['username'];
    }

    // OG视讯数据
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`validbet`) AS `valid_money`, SUM(`bettingamount`) AS `total`, SUM(`winloseamount`) AS `user_win`
            FROM " . DBPREFIX . "og_projects 
            WHERE $sWhere_og AND `md_bettingdate` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_og = mysqli_query($dbLink, $sql);
    while ($row_og = mysqli_fetch_assoc($res_og)) {
        $data_current_og['valid_money'] += $row_og['valid_money'];
        $data_current_og['user_win'] += $row_og['user_win'];
        $mem_og[] = $row_og['username'];
    }

    // MW电子数据（使用北京时间）
    $dateStart_mw = date('Y-m-d H:i:s',strtotime($dateStart)+12*60*60);
    $dateEnd_mw = date('Y-m-d H:i:s',strtotime($dateEnd)+12*60*60);
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`playMoney`) AS `valid_money`, SUM(`playMoney`) AS `total`, SUM(`winMoney`) AS `user_win`
            FROM " . DBPREFIX . "mw_projects 
            WHERE $sWhere_mw AND `logDate` BETWEEN '{$dateStart_mw}' AND '{$dateEnd_mw}' GROUP BY `userid`";
    $res_mw = mysqli_query($dbLink, $sql);
    while ($row_mw = mysqli_fetch_assoc($res_mw)) {
        $row_mw['user_win'] = $row_mw['user_win'] - $row_mw['valid_money']; // 会员输赢（赢：+；输：-）
        $data_current_mw['valid_money'] += $row_mw['valid_money'];
        $data_current_mw['user_win'] += $row_mw['user_win'];
        $mem_mw[] = $row_mw['username'];
    }

    // CQ9电子数据
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`bet`) AS `valid_money`, SUM(`bet`) AS `total`, SUM(`win`) AS `user_win`
            FROM " . DBPREFIX . "cq9_projects 
            WHERE $sWhere_cq AND `endroundtime` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_cq = mysqli_query($dbLink, $sql);
    while ($row_cq = mysqli_fetch_assoc($res_cq)) {
        $row_cq['user_win'] = $row_cq['user_win'] - $row_cq['valid_money']; // 会员输赢（赢：+；输：-）
        $data_current_cq['valid_money'] += $row_cq['valid_money'];
        $data_current_cq['user_win'] += $row_cq['user_win'];
        $mem_cq[] = $row_cq['username'];
    }

    // FG电子数据（使用美东时间）
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`all_bets`) AS `valid_money`, SUM(`all_bets`) AS `total`, SUM(`all_wins`) AS `user_win`
            FROM " . DBPREFIX . "fg_projects 
            WHERE $sWhere_fg AND `endtime` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_fg = mysqli_query($dbLink, $sql);
    while ($row_fg = mysqli_fetch_assoc($res_fg)) {
        $row_fg['user_win'] = $row_fg['user_win'] - $row_fg['valid_money']; // 会员输赢（赢：+；输：-）
        $data_current_fg['valid_money'] += $row_fg['valid_money'];
        $data_current_fg['user_win'] += $row_fg['user_win'];
        $mem_fg[] = $row_fg['username'];
    }

    // BBIN视讯数据
    $sql = "SELECT username, SUM(1) AS `count_pay`, SUM(`Commissionable`) AS `valid_money`, SUM(`BetAmount`) AS `total`, SUM(`Payoff`) AS `user_win`
            FROM " . DBPREFIX . "jx_bbin_projects 
            WHERE {$sWhere_bbin} AND `WagersDate` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `userid`";
    $res_bbin = mysqli_query($dbLink, $sql);
    while ($row_bbin = mysqli_fetch_assoc($res_bbin)) {
        $data_current_bbin['valid_money'] += $row_bbin['valid_money'];
        $data_current_bbin['user_win'] += $row_bbin['user_win'];
        $mem_bbin[] = $row_bbin['username'];
    }

    if($userId){ // 会员有效投注为打码量
        $memDataCount['hg'] = isset($data_current_hg['valid_money']) ? $data_current_hg['valid_money'] : 0;
        if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票
            $memDataCount['cp'] = isset($data_current_cp['valid_money']) ? $data_current_cp['valid_money'] : 0;
        } else { // 三方彩票
            $memDataCount['ssc'] = isset($data_current_ssc['valid_money']) ? $data_current_ssc['valid_money'] : 0;
            $memDataCount['project'] = isset($data_current_project['valid_money']) ? $data_current_project['valid_money'] : 0;
            $memDataCount['trace'] = isset($data_current_trace['valid_money']) ? $data_current_trace['valid_money'] : 0;
        }
        $memDataCount['ag'] = isset($data_current_ag['valid_money']) ? $data_current_ag['valid_money'] : 0;
        $memDataCount['ag_dianzi'] = isset($data_current_ag_dianzi['valid_money']) ? $data_current_ag_dianzi['valid_money'] : 0;
        $memDataCount['ag_dayu'] = isset($data_current_ag_dayu['valid_money']) ? $data_current_ag_dayu['valid_money'] : 0;
        $memDataCount['ky'] = isset($data_current_ky['valid_money']) ? $data_current_ky['valid_money'] : 0;
        //$memDataCount['hgqp'] = isset($data_current_hgqp['valid_money']) ? $data_current_hgqp['valid_money'] : 0;
        $memDataCount['vgqp'] = isset($data_current_vgqp['valid_money']) ? $data_current_vgqp['valid_money'] : 0;
        $memDataCount['lyqp'] = isset($data_current_lyqp['valid_money']) ? $data_current_lyqp['valid_money'] : 0;
        $memDataCount['klqp'] = isset($data_current_klqp['valid_money']) ? $data_current_klqp['valid_money'] : 0;
        $memDataCount['mg'] = isset($data_current_mg['valid_money']) ? $data_current_mg['valid_money'] : 0;
        $memDataCount['avia'] = isset($data_current_avia['valid_money']) ? $data_current_avia['valid_money'] : 0;
        $memDataCount['fire'] = isset($data_current_fire['valid_money']) ? $data_current_fire['valid_money'] : 0;
        $memDataCount['og'] = isset($data_current_og['valid_money']) ? $data_current_og['valid_money'] : 0;
        $memDataCount['mw'] = isset($data_current_mw['valid_money']) ? $data_current_mw['valid_money'] : 0;
        $memDataCount['cq'] = isset($data_current_cq['valid_money']) ? $data_current_cq['valid_money'] : 0;
        $memDataCount['fg'] = isset($data_current_fg['valid_money']) ? $data_current_fg['valid_money'] : 0;
        $memDataCount['bbin'] = isset($data_current_bbin['valid_money']) ? $data_current_bbin['valid_money'] : 0;
    }else { // 默认返回统计数据
        $memDataCount['mem_count'] = count(array_unique(array_merge($mem_hg, $mem_cp, $mem_ag, $mem_ag_dianzi, $mem_ag_dayu, $mem_ky, $mem_hgqp, $mem_vgqp, $mem_lyqp, $mem_klqp, $mem_mg, $mem_avia, $mem_ssc, $mem_project, $mem_trace, $mem_og, $mem_mw, $mem_cq, $mem_fg, $mem_bbin)));
        $memDataCount['valid_money'] = $data_current_hg['valid_money'] + $data_current_cp['valid_money'] + $data_current_ag['valid_money'] + $data_current_ag_dianzi['valid_money'] + $data_current_ag_dayu['valid_money'] +
            $data_current_ky['valid_money'] + $data_current_hgqp['valid_money'] + $data_current_vgqp['valid_money'] + $data_current_lyqp['valid_money'] + $data_current_klqp['valid_money'] + $data_current_mg['valid_money'] + $data_current_avia['valid_money']  + $data_current_fire['valid_money']+
            $data_current_ssc['valid_money'] + $data_current_project['valid_money'] + $data_current_trace['valid_money'] + $data_current_og['valid_money'] + $data_current_mw['valid_money'] + $data_current_cq['valid_money'] +
            $data_current_fg['valid_money'] + $data_current_bbin['valid_money'];
        $memDataCount['user_win'] = $data_current_hg['user_win'] + $data_current_cp['user_win'] + $data_current_ag['user_win'] + $data_current_ag_dianzi['user_win'] + $data_current_ag_dayu['user_win'] +
            $data_current_ky['user_win'] + $data_current_hgqp['user_win'] + $data_current_vgqp['user_win'] + $data_current_lyqp['user_win'] + $data_current_klqp['user_win'] + $data_current_mg['user_win'] + $data_current_avia['user_win'] + $data_current_fire['user_win'] +
            $data_current_ssc['user_win'] + $data_current_project['user_win'] + $data_current_trace['user_win'] + $data_current_og['user_win'] + $data_current_mw['user_win'] + $data_current_cq['user_win'] +
            $data_current_fg['user_win'] + $data_current_bbin['user_win'];
    }
    return $memDataCount;
}

/**
 * 获取会员打码量开始统计时间
 * （以此时间点统计需要的提款打码量和已打码量）
 * @param $userId
 * @return string
 */
function countBetTime($userId){
    global $dbLink;
    // 查询最近一次提款时间（提款审核成功时间）
    $sqlWithdraw = "SELECT AuditDate FROM `" . DBPREFIX . "web_sys800_data` WHERE `userid`={$userId} and Type = 'T' AND Checked=1 ORDER BY ID DESC LIMIT 1";
    $resultWithdraw = mysqli_query($dbLink, $sqlWithdraw);
    $countWithdraw = mysqli_num_rows($resultWithdraw);
    if($countWithdraw == 0){
        // 查询会员首次充值时间（充值审核时间）
        $sqlDeposit = "SELECT AuditDate FROM `" . DBPREFIX . "web_sys800_data` WHERE `userid`={$userId} and Type = 'S' AND Checked=1 AND owe_bet!=0 ORDER BY ID ASC LIMIT 1";
        $resultDeposit = mysqli_query($dbLink, $sqlDeposit);
        $countDeposit = mysqli_num_rows($resultDeposit);
        if($countDeposit == 0){ // 若无充值记录（统计时间=这次充值审核时间）
            $countBetTime = ''; // 返回空，因这次充值审核成功的时间在审核处
        }else{ // 若有充值记录（统计时间=首次充值审核时间）
            $rowDeposit = mysqli_fetch_assoc($resultDeposit);
            $countBetTime = $rowDeposit['AuditDate']; // 若正常统计，统计时间可以不进行更新
            // todo 首次计算打码量，上线前统计好所有会员充值申请成功后打码量和打码量统计时间，或规定正常统计的时间点
        }
    }else{
        $rowWithdraw = mysqli_fetch_assoc($resultWithdraw);
        $countBetTime = $rowWithdraw['AuditDate']; // 审核通过时间
    }
    return $countBetTime;
}

/**
 * 统计会员的打码量
 * @param $dateCount
 * @param $userId
 * @return array
 */
function countBet($dateCount, $userId){
    $totalData = [
        'hg' => 0,
        'cp' => 0,
        'ssc' => 0,
        'project' => 0,
        'trace' => 0,
        'ag' => 0,
        'ag_dianzi' => 0,
        'ag_dayu' => 0,
        'ky' => 0,
       // 'hgqp' => 0,
        'vgqp' => 0,
        'lyqp' => 0,
        'klqp' => 0,
        'mg' => 0,
        'avia' => 0,
        'fire' => 0,
        'og' => 0,
        'mw' => 0,
        'cq' => 0,
        'fg' => 0,
        'bbin' => 0,
        'total' => 0,
    ];

    if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票
        unset($totalData['ssc'], $totalData['project'], $totalData['trace']);
    } else { // 三方彩票
        unset($totalData['cp']);
    }

    if($dateCount == '1969-12-31 20:00:00' || empty($dateCount)){ // 若会员未充值提款或从未计算过打码量
        return $totalData;
    }

    $now = date('Y-m-d H:i:s');
    $today = date('Y-m-d');
    $current_start_day = $dateCount;
    $current_end_day = $now;

    $isHistory = false; // 默认不查询历史日结报表
    $dateStart = $dateEnd = '';
    // 开始统计时间据当前时间相差天数（已打码量 = 历史日结前两天的数据总计 + 前一天注单时时报表数据总计）
    $diffDays = round((strtotime($today) - strtotime(date('Y-m-d', strtotime($current_start_day)))) / 3600 / 24);
    if($diffDays >= 2){ // 从前两天查询历史日结报表
        $isHistory = true;
        $dateStart = date('Y-m-d 12:00:00', strtotime('-' . $diffDays . ' day'));
        //$dateEnd = date('Y-m-d 11:59:59', strtotime('-2 day')); // $diffDays 为2的时候会造成结束时间比开始时间还要小
        $dateEnd = date('Y-m-d 11:59:59', strtotime('-1 day'));
        // 从前一天开始查询注单报表
        $current_start_day = date('Y-m-d 12:00:00', strtotime('-1 day'));
        $current_end_day = $now;
    }

    $totalData = countCurrentBetMember($current_start_day, $current_end_day, $userId);
    if($isHistory){
        $historyData = countHistoryBetMember($dateStart, $dateEnd, $userId);
        foreach ($totalData as $key => $value){
            $totalData[$key] += $historyData[$key];
        }
    }
    $totalData['total'] = array_sum($totalData);
    return $totalData;
}


/**
 * 按月统计会员的打码量,默认上月
 * @param $dateCount
 * @param $userId
 * @return array
 */
function countBetMonth($dateCount, $userId){
    $totalData = [
        'hg' => 0,
        'cp' => 0,
        'ssc' => 0,
        'project' => 0,
        'trace' => 0,
        'ag' => 0,
        'ag_dianzi' => 0,
        'ag_dayu' => 0,
        'ky' => 0,
        // 'hgqp' => 0,
        'vgqp' => 0,
        'lyqp' => 0,
        'klqp' => 0,
        'mg' => 0,
        'avia' => 0,
        'fire' => 0,
        'og' => 0,
        'mw' => 0,
        'cq' => 0,
        'fg' => 0,
        'bbin' => 0,
        'total' => 0,
    ];

    if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票
        unset($totalData['ssc'], $totalData['project'], $totalData['trace']);
    } else { // 三方彩票
        unset($totalData['cp']);
    }

    if(empty($dateCount) || empty($dateCount['dateStart']) || empty($dateCount['dateEnd'])){ // 验证时间
        return $totalData;
    }

    $dateStart = $dateCount['dateStart'];
    $dateEnd = $dateCount['dateEnd'];
    $date_start = date('Y-m-d',strtotime($dateStart));
    $date_end = date('Y-m-d',strtotime($dateEnd));

    // 查询时间判断
    $isCurrent = false;
    $isHistory = true;
    if($date_start == date("Y-m-d") && $date_end >= date("Y-m-d") ) {
        $isCurrent = true;

        $current_start_day = date("Y-m-d");
        $current_end_day = date("Y-m-d 23:59:59");

        $history_date_end = date("Y-m-d 23:59:59", strtotime("-1 day"));
    }elseif($date_start < date("Y-m-d") && $date_end >= date("Y-m-d") && (int)date("G") < 3) {
        $isCurrent = true;
        $current_start_day = date("Y-m-d", strtotime("-1 day"));
        $current_end_day = date("Y-m-d 23:59:59");

        //从历史报表里面搜索的截止时间为前天晚上的23:59:59
        $history_date_end = date("Y-m-d 23:59:59", strtotime("-2 day"));
    }else if($date_start < date("Y-m-d") && $date_end == date("Y-m-d", strtotime("-1 day")) && (int)date("G") < 3) {
        // 每月最后一日还未生成。
        $isCurrent = true;
        //$current_start_day = date('Y-m-d 00:00:00',strtotime(-date('d').'day'));
        //$current_end_day = date("Y-m-d 23:59:59", strtotime(-date('d').'day'));
        $current_start_day = date('Y-m-d', strtotime($dateEnd));
        $current_end_day = date('Y-m-d 23:59:59',strtotime($dateEnd));

        //从历史报表里面搜索的截止时间为前天晚上的23:59:59
        $history_date_end = date("Y-m-d 23:59:59", strtotime("-2 day"));
    }else if($date_start <= date("Y-m-d") && $date_end >= date("Y-m-d") && (int)date("G") >= 3) {
        $isCurrent = true;
        $current_start_day = date("Y-m-d");
        $current_end_day = date("Y-m-d 23:59:59");

        $history_date_end = date("Y-m-d 23:59:59", strtotime("-1 day"));
    }else{
        //$history_date_end = date("Y-m-d  23:59:59", strtotime("-1 day"));    //当前时间前一天
        $history_date_end = date("Y-m-d  23:59:59", strtotime($dateEnd));   // 默认上月最后一天
    }

    if($isCurrent) {
        $totalData = countCurrentBetMember($current_start_day, $current_end_day, $userId);
    }

    if($isHistory){
        $historyData = countHistoryBetMember($dateStart, $history_date_end, $userId);

        foreach ($totalData as $key => $value){
            $totalData[$key] += $historyData[$key];
        }
    }

    $totalData['total'] = array_sum($totalData);
    return $totalData;
}


/**
 * 代理统计（代理佣金查询）
 * @param $dateStart
 * @param $dateEnd
 * @param array $username
 * @return array
 */
function countHistoryBetAgent($dateStart, $dateEnd, $username = []){
    global $dbLink, $database;

    // 初始化投注统计日结表-代理数据
    $data_history_agent_hg = $data_history_agent_cp = $data_history_agent_ag = $data_history_agent_ag_dianzi = $data_history_agent_ag_dayu = $data_history_agent_ky = $data_history_agent_hgqp = $data_history_agent_vgqp = $data_history_agent_lyqp = $data_history_agent_klqp
        = $data_history_agent_mg = $data_history_agent_avia = $data_history_agent_fire = $data_history_agent_ssc = $data_history_agent_project = $data_history_agent_trace = $data_history_agent_og = $data_history_agent_mw = $data_history_agent_cq = $data_history_agent_fg
        = $data_history_agent_bbin = [];

    $sWhere = ' 1 ';
    $sWhere_hg = $sWhere_cp = $sWhere_ag = $sWhere_ky = $sWhere_hgqp = $sWhere_vgqp = $sWhere_lyqp = $sWhere_klqp = $sWhere_mg = $sWhere_avia = $sWhere_fire = $sWhere_thirdcp = $sWhere_og = $sWhere_mw = $sWhere_cq = $sWhere_fg = $sWhere_bbin = $sWhere;

    if(!empty($username)){ // 查询的代理商
        // 仅为兼容体育彩票
        $sql = 'SELECT `ID`, `UserName` FROM ' . DBPREFIX . 'web_agents_data WHERE `Level`= "D" AND `UserName` IN ("' . implode('","', $username) . '")';
        $result = mysqli_query($dbLink, $sql);
        $agentId = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $agentId[] = $row['ID'];
        }

        $sWhere_hg = $sWhere . " AND `Agents` IN ('" . implode("','", $username) . "')";
        $sWhere_cp = $sWhere . " AND `hg_agent_uid` IN (" . implode(',', $agentId) . ")";
        $sWhere_ag = $sWhere . " AND `Agents` IN ('" . implode("','", $username) . "')";
        $sWhere_ky = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_hgqp = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_vgqp = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_lyqp = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_klqp = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_mg = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_avia = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_fire = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_thirdcp = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_og = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_mw = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_cq = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_fg = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
        $sWhere_bbin = $sWhere . " AND `agents` IN ('" . implode("','", $username) . "')";
    }

    // 体育主数据
    $sql = "SELECT `Agents`, SUM(`count_pay`) AS count_pay, SUM(`total`) AS total, SUM(`valid_money`) AS valid_money, SUM(`user_win`) AS user_win FROM " . DBPREFIX . "web_report_history_report_data WHERE $sWhere_hg AND M_Date >= '" . $dateStart . "' AND M_Date<='" . $dateEnd . "' GROUP BY `Agents`";
    $res_hg = mysqli_query($dbLink, $sql);
    $cou_hg = mysqli_num_rows($res_hg);
    if ($cou_hg > 0) {
        while ($row_hg = mysqli_fetch_assoc($res_hg)) {
            $data_history_agent_hg[trim($row_hg['Agents'])] = [
                'user_win' => $row_hg['user_win'],
                'valid_money' => $row_hg['valid_money'],
                'water_rate' => agentWaterRate($row_hg['user_win'])['hg'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_hg['user_win']))['hg'], // 代理退佣设置输赢金额（不管输赢都计算）
            ];
        }
    }

    if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票
        // 彩票主数据，测试代理线不统计
        $aCp_default = $database['cpDefault'];
        $cpDbLink = @mysqli_connect($aCp_default['host'], $aCp_default['user'], $aCp_default['password'], $aCp_default['dbname'], $aCp_default['port']) or die("mysqli connect error" . mysqli_connect_error());
        $sql = "SELECT `hg_agent_uid`, SUM(`count_pay`) AS count_pay, SUM(`total`) AS total, SUM(`valid_money`) AS valid_money, SUM(`user_win`) AS user_win FROM gxfcy_history_bill_report_less_12hours WHERE $sWhere_cp AND hg_agent_uid!=521 AND hg_agent_uid!=522 AND bet_time BETWEEN '" . strtotime($dateStart) . "' AND '" . strtotime($dateEnd) . "' GROUP BY `hg_agent_uid`";
        $res_cp = mysqli_query($cpDbLink, $sql);
        $cou_cp = mysqli_num_rows($res_cp);
        if ($cou_cp > 0) {
            while ($row_cp = mysqli_fetch_assoc($res_cp)) {
                $data_history_agent_cp[$row_cp['hg_agent_uid']] = [
                    'user_win' => $row_cp['user_win'],
                    'valid_money' => $row_cp['valid_money'],
                    'water_rate' => agentWaterRate($row_cp['user_win'])['cp'], // 代理退水设置输赢金额
                    'commission_rate' => agentCommissionRate(abs($row_cp['user_win']))['cp'], // 代理退佣设置输赢金额
                ];
            }
        }
    } else {
        // 第三方彩票信用主数据（报表数据）
        $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "web_third_ssc_history_report WHERE $sWhere_thirdcp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
        $res_ssc = mysqli_query($dbLink, $sql);
        $cou_ssc = mysqli_num_rows($res_ssc);
        if ($cou_ssc > 0) {
            while ($row_ssc = mysqli_fetch_assoc($res_ssc)) {
                $row_ssc['user_win'] = $row_ssc['user_win'] - $row_ssc['valid_money']; // 会员输赢（赢：+；输：-）
                $data_history_agent_ssc[$row_ssc['agents']] = [
                    'user_win' => $row_ssc['user_win'],
                    'valid_money' => $row_ssc['valid_money'],
                    'water_rate' => agentWaterRate($row_ssc['user_win'])['ssc'], // 代理退水设置输赢金额
                    'commission_rate' => agentCommissionRate(abs($row_ssc['user_win']))['ssc'], // 代理退佣设置输赢金额
                ];
            }
        }

        // 第三方彩票官方主数据（报表数据）
        $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "web_third_projects_history_report WHERE $sWhere_thirdcp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
        $res_project = mysqli_query($dbLink, $sql);
        $cou_project = mysqli_num_rows($res_project);
        if ($cou_project > 0) {
            while ($row_project = mysqli_fetch_assoc($res_project)) {
                $row_project['user_win'] = $row_project['user_win'] - $row_project['valid_money']; // 会员输赢（赢：+；输：-）
                $data_history_agent_project[$row_project['agents']] = [
                    'user_win' => $row_project['user_win'],
                    'valid_money' => $row_project['valid_money'],
                    'water_rate' => agentWaterRate($row_project['user_win'])['project'], // 代理退水设置输赢金额
                    'commission_rate' => agentCommissionRate(abs($row_project['user_win']))['project'], // 代理退佣设置输赢金额
                ];
            }
        }

        // 第三方彩票官方追号主数据（报表数据）
        $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "web_third_traces_history_report
        WHERE $sWhere_thirdcp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
        $res_trace = mysqli_query($dbLink, $sql);
        $cou_trace = mysqli_num_rows($res_trace);
        if ($cou_trace > 0) {
            while ($row_trace = mysqli_fetch_assoc($res_trace)) {
                $row_trace['user_win'] = $row_trace['user_win'] - $row_trace['valid_money']; // 会员输赢（赢：+；输：-）
                $data_history_agent_trace[$row_trace['agents']] = [
                    'user_win' => $row_trace['user_win'],
                    'valid_money' => $row_trace['valid_money'],
                    'water_rate' => agentWaterRate($row_trace['user_win'])['trace'], // 代理退水设置输赢金额
                    'commission_rate' => agentCommissionRate(abs($row_trace['user_win']))['trace'], // 代理退佣设置输赢金额
                ];
            }
        }
    }

    // AG视讯主数据
    $sql = "SELECT `Agents`, SUM(`count_pay`) AS count_pay, SUM(`total`) AS total, SUM(`valid_money`) AS valid_money, SUM(`profit`) AS user_win FROM " . DBPREFIX . "ag_projects_history_report WHERE $sWhere_ag AND bet_time BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "' AND game_code = 'BR' GROUP BY `Agents`";
    $res_ag = mysqli_query($dbLink, $sql);
    $cou_ag = mysqli_num_rows($res_ag);
    if ($cou_ag > 0) {
        while ($row_ag = mysqli_fetch_assoc($res_ag)) {
            $data_history_agent_ag[$row_ag['Agents']] = [
                'user_win' => $row_ag['user_win'],
                'valid_money' => $row_ag['valid_money'],
                'water_rate' => agentWaterRate($row_ag['user_win'])['ag'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_ag['user_win']))['ag'], // 代理退佣设置输赢金额
            ];
        }
    }

    // AG电子主数据
    $sql = "SELECT `Agents`, SUM(`count_pay`) AS count_pay, SUM(`total`) AS total, SUM(`valid_money`) AS valid_money, SUM(`profit`) AS user_win FROM " . DBPREFIX . "ag_projects_history_report WHERE $sWhere_ag AND bet_time BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "' AND (game_code = '' OR game_code = 'SLOT') GROUP BY `Agents`";
    $res_ag_dianzi = mysqli_query($dbLink, $sql);
    $cou_ag_dianzi = mysqli_num_rows($res_ag_dianzi);
    if ($cou_ag_dianzi > 0) {
        while ($row_ag_dianzi = mysqli_fetch_assoc($res_ag_dianzi)) {
            $data_history_agent_ag_dianzi[$row_ag_dianzi['Agents']] = [
                'user_win' => $row_ag_dianzi['user_win'],
                'valid_money' => $row_ag_dianzi['valid_money'],
                'water_rate' => agentWaterRate($row_ag_dianzi['user_win'])['ag_dianzi'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_ag_dianzi['user_win']))['ag_dianzi'], // 代理退佣设置输赢金额
            ];
        }
    }

    // AG捕鱼王打鱼主数据
    $sql = "SELECT `Agents`, SUM(`BulletOutNum`) as count_pay, SUM(`Cost`) as valid_money, SUM(`Earn`) as shouru FROM " . DBPREFIX . "ag_buyu_scene WHERE $sWhere_ag AND EndTime BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "' GROUP BY `Agents`";
    $res_ag_dayu = mysqli_query($dbLink, $sql);
    $cou_ag_dayu = mysqli_num_rows($res_ag_dayu);
    if ($cou_ag_dayu>0) {
        while ($row_ag_dayu = mysqli_fetch_assoc($res_ag_dayu)) {
            $row_ag_dayu['user_win'] = $row_ag_dayu['shouru'] - $row_ag_dayu['valid_money']; // 会员输赢（赢：+；输：-）
            $data_history_agent_ag_dayu[$row_ag_dayu['Agents']] = [
                'user_win' => $row_ag_dayu['user_win'],
                'valid_money' => $row_ag_dayu['valid_money'],
                'water_rate' => agentWaterRate($row_ag_dayu['user_win'])['ag_dayu'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_ag_dayu['user_win']))['ag_dayu'], // 代理退佣设置输赢金额
            ];
        }
    }

    // KY主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` FROM " . DBPREFIX . "ky_history_report WHERE $sWhere_ky AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_ky = mysqli_query($dbLink, $sql);
    $cou_ky = mysqli_num_rows($res_ky);
    if ($cou_ky > 0) {
        while ($row_ky = mysqli_fetch_assoc($res_ky)) {
            $data_history_agent_ky[$row_ky['agents']] = [
                'user_win' => $row_ky['user_win'],
                'valid_money' => $row_ky['valid_money'],
                'water_rate' => agentWaterRate($row_ky['user_win'])['ky'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_ky['user_win']))['ky'], // 代理退佣设置输赢金额
            ];
        }
    }

    // HGQP主数据
//    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` FROM " . DBPREFIX . "ff_history_report WHERE $sWhere_hgqp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
//    $res_hgqp = mysqli_query($dbLink, $sql);
//    $cou_hgqp = mysqli_num_rows($res_hgqp);
//    if ($cou_hgqp > 0) {
//        while ($row_hgqp = mysqli_fetch_assoc($res_hgqp)) {
//            $data_history_agent_hgqp[$row_hgqp['agents']] = [
//                'user_win' => $row_hgqp['user_win'],
//                'valid_money' => $row_hgqp['valid_money'],
//                'water_rate' => agentWaterRate($row_hgqp['user_win'])['hgqp'], // 代理退水设置输赢金额
//                'commission_rate' => agentCommissionRate(abs($row_hgqp['user_win']))['hgqp'], // 代理退佣设置输赢金额
//            ];
//        }
//    }

    // VGQP主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` FROM " . DBPREFIX . "vg_history_report WHERE $sWhere_vgqp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_vgqp = mysqli_query($dbLink, $sql);
    $cou_vgqp = mysqli_num_rows($res_vgqp);
    if ($cou_vgqp > 0) {
        while ($row_vgqp = mysqli_fetch_assoc($res_vgqp)) {
            $data_history_agent_vgqp[$row_vgqp['agents']] = [
                'user_win' => $row_vgqp['user_win'],
                'valid_money' => $row_vgqp['valid_money'],
                'water_rate' => agentWaterRate($row_vgqp['user_win'])['vgqp'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_vgqp['user_win']))['vgqp'], // 代理退佣设置输赢金额
            ];
        }
    }

    // 乐游棋牌主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` FROM " . DBPREFIX . "ly_history_report WHERE $sWhere_lyqp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_lyqp = mysqli_query($dbLink, $sql);
    $cou_lyqp = mysqli_num_rows($res_lyqp);
    if ($cou_lyqp > 0) {
        while ($row_lyqp = mysqli_fetch_assoc($res_lyqp)) {
            $data_history_agent_lyqp[$row_lyqp['agents']] = [
                'user_win' => $row_lyqp['user_win'],
                'valid_money' => $row_lyqp['valid_money'],
                'water_rate' => agentWaterRate($row_lyqp['user_win'])['lyqp'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_lyqp['user_win']))['lyqp'], // 代理退佣设置输赢金额
            ];
        }
    }

    // 快乐棋牌主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "kl_history_report WHERE $sWhere_klqp AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_klqp = mysqli_query($dbLink, $sql);
    $cou_klqp = mysqli_num_rows($res_klqp);
    if ($cou_klqp > 0) {
        while ($row_klqp = mysqli_fetch_assoc($res_klqp)) {
            $data_history_agent_klqp[$row_klqp['agents']] = [
                'user_win' => ($row_klqp['user_win']-$row_klqp['total']),
                'valid_money' => $row_klqp['valid_money'],
                'water_rate' => agentWaterRate($row_klqp['user_win'])['klqp'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_klqp['user_win']))['klqp'], // 代理退佣设置输赢金额
            ];
        }
    }

    // MG电子主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "mg_history_report WHERE $sWhere_mg AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_mg = mysqli_query($dbLink, $sql);
    $cou_mg = mysqli_num_rows($res_mg);
    if ($cou_mg > 0) {
        while ($row_mg = mysqli_fetch_assoc($res_mg)) {
            $data_history_agent_mg[$row_mg['agents']] = [
                'user_win' => $row_mg['user_win'],
                'valid_money' => $row_mg['valid_money'],
                'water_rate' => agentWaterRate($row_mg['user_win'])['mg'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_mg['user_win']))['mg'], // 代理退佣设置输赢金额
            ];
        }
    }

    // 泛亚电竞主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "avia_history_report WHERE $sWhere_avia AND `created_at` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_avia = mysqli_query($dbLink, $sql);
    $cou_avia = mysqli_num_rows($res_avia);
    if ($cou_avia > 0) {
        while ($row_avia = mysqli_fetch_assoc($res_avia)) {
            $data_history_agent_avia[$row_avia['agents']] = [
                'user_win' => $row_avia['user_win'],
                'valid_money' => $row_avia['valid_money'],
                'water_rate' => agentWaterRate($row_avia['user_win'])['avia'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_avia['user_win']))['avia'], // 代理退佣设置输赢金额
            ];
        }
    }

    // 雷火电竞主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` FROM " . DBPREFIX . "fire_history_report WHERE $sWhere_fire AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_fire = mysqli_query($dbLink, $sql);
    $cou_fire = mysqli_num_rows($res_fire);
    if ($cou_fire > 0) {
        while ($row_fire = mysqli_fetch_assoc($res_fire)) {
            $data_history_agent_fire[$row_fire['agents']] = [
                'user_win' => $row_fire['user_win'],
                'valid_money' => $row_fire['valid_money'],
                'water_rate' => agentWaterRate($row_fire['user_win'])['fire'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_fire['user_win']))['fire'], // 代理退佣设置输赢金额
            ];
        }
    }

    // OG视讯主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "og_history_report 
        WHERE $sWhere_og AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_og = mysqli_query($dbLink, $sql);
    $cou_og = mysqli_num_rows($res_og);
    if ($cou_og > 0) {
        while ($row_og = mysqli_fetch_assoc($res_og)) {
            $data_history_agent_og[$row_og['agents']] = [
                'user_win' => $row_og['user_win'],
                'valid_money' => $row_og['valid_money'],
                'water_rate' => agentWaterRate($row_og['user_win'])['og'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_og['user_win']))['og'], // 代理退佣设置输赢金额
            ];
        }
    }

    // MW电子主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "mw_history_report 
        WHERE $sWhere_mw AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_mw = mysqli_query($dbLink, $sql);
    $cou_mw = mysqli_num_rows($res_mw);
    if ($cou_mw > 0) {
        while ($row_mw = mysqli_fetch_assoc($res_mw)) {
            $data_history_agent_mw[$row_mw['agents']] = [
                'user_win' => $row_mw['user_win'],
                'valid_money' => $row_mw['valid_money'],
                'water_rate' => agentWaterRate($row_mw['user_win'])['mw'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_mw['user_win']))['mw'], // 代理退佣设置输赢金额
            ];
        }
    }

    // CQ9电子主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "cq9_history_report 
        WHERE $sWhere_cq AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_cq = mysqli_query($dbLink, $sql);
    $cou_cq = mysqli_num_rows($res_cq);
    if ($cou_cq > 0) {
        while ($row_cq = mysqli_fetch_assoc($res_cq)) {
            $row_cq['user_win'] = $row_cq['user_win'] - $row_cq['valid_money']; // 会员输赢（赢：+；输：-）
            $data_history_agent_cq[$row_cq['agents']] = [
                'user_win' => $row_cq['user_win'],
                'valid_money' => $row_cq['valid_money'],
                'water_rate' => agentWaterRate($row_cq['user_win'])['cq'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_cq['user_win']))['cq'], // 代理退佣设置输赢金额
            ];
        }
    }

    // FG电子主数据
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "fg_history_report 
        WHERE $sWhere_fg AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_fg = mysqli_query($dbLink, $sql);
    $cou_fg = mysqli_num_rows($res_fg);
    if ($cou_fg > 0) {
        while($row_fg = mysqli_fetch_assoc($res_fg)) {
            $row_fg['user_win'] = $row_fg['user_win'] - $row_fg['valid_money']; // 会员输赢（赢：+；输：-）
            $data_history_agent_fg[$row_fg['agents']] = [
                'user_win' => $row_fg['user_win'],
                'valid_money' => $row_fg['valid_money'],
                'water_rate' => agentWaterRate($row_fg['user_win'])['fg'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_fg['user_win']))['fg'], // 代理退佣设置输赢金额
            ];
        }
    }

    // BBIN视讯
    $sql = "SELECT `agents`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "jx_bbin_history_report 
        WHERE $sWhere_bbin AND `count_date` BETWEEN '{$dateStart}' AND '{$dateEnd}' GROUP BY `agents`";
    $res_bbin = mysqli_query($dbLink, $sql);
    $cou_bbin = mysqli_num_rows($res_bbin);
    if ($cou_bbin > 0) {
        while($row_bbin = mysqli_fetch_assoc($res_bbin)) {
            $data_history_agent_bbin[$row_bbin['agents']] = [
                'user_win' => $row_bbin['user_win'],
                'valid_money' => $row_bbin['valid_money'],
                'water_rate' => agentWaterRate($row_bbin['user_win'])['bbin'], // 代理退水设置输赢金额
                'commission_rate' => agentCommissionRate(abs($row_bbin['user_win']))['bbin'], // 代理退佣设置输赢金额
            ];
        }
    }

    // 会员输赢 1
    // 有效投注 1
    // 代理退水比例 1
    // 代理退佣比例 1
    // 会员返水总额（另外查询）
    // 该馆行政费[0.15]（另外查询）
    // 该馆所得佣金(0 - 会员输赢 - 返水总额 - 行政费) x 退佣比例 + (有效投注 x 退水比例) = 厅室佣金 行政费：厅室输赢(取正数) x 行政费比例（另外计算）
    $memDataCount = [
        'hg' => $data_history_agent_hg,
        'ag' => $data_history_agent_ag,
        'ag_dianzi' => $data_history_agent_ag_dianzi,
        'ag_dayu' => $data_history_agent_ag_dayu,
        'ky' => $data_history_agent_ky,
        //'hgqp' => $data_history_agent_hgqp,
        'vgqp' => $data_history_agent_vgqp,
        'lyqp' => $data_history_agent_lyqp,
        'klqp' => $data_history_agent_klqp,
        'mg' => $data_history_agent_mg,
        'avia' => $data_history_agent_avia,
        'fire' => $data_history_agent_fire,
        'og' => $data_history_agent_og,
        'mw' => $data_history_agent_mw,
        'cq' => $data_history_agent_cq,
        'fg' => $data_history_agent_fg,
        'bbin' => $data_history_agent_bbin,
    ];

    if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) { // 体育彩票
        $memDataCount['cp'] = $data_history_agent_cp;
    } else { // 三方彩票
        $memDataCount['ssc'] = $data_history_agent_ssc;
        $memDataCount['project'] = $data_history_agent_project;
        $memDataCount['trace'] = $data_history_agent_trace;
    }

    return $memDataCount;
}

/**
 * 查询代理商下级会员数(代理佣金报表)
 * @param $dateStart
 * @param $dateEnd
 * @param array $agent
 * @return array
 */
function countAgentMemberNum($dateStart, $dateEnd, $agent = []){
    global $dbLink;
    $sWhere = "`AddDate` BETWEEN '$dateStart' AND '$dateEnd'"; // 默认开始统计时间为当年第一个月，到目前为止的会员数量
    if($agent){
        $sWhere .= " AND `Agents` IN ('" . implode("','", $agent) . "')";
    }
    $memberNum = [];
    $sql = "SELECT COUNT(`ID`) AS `member_num`, `Agents` FROM " . DBPREFIX . "web_member_data WHERE $sWhere GROUP BY `Agents`";
    $result = mysqli_query($dbLink, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $memberNum[$row['Agents']] = [
            'member_num' => $row['member_num'],
        ];
    }
    return $memberNum;
}

/**
 * 代理返水统计（代理佣金查询）
 * @param $dateStart
 * @param $dateEnd
 * @param array $agent
 * @return array
 */
function countAgentRebate($dateStart, $dateEnd, $agent = []){
    global $dbLink;
    $agentRebate = $agentRebateHour = $agentDayCount = $agentHourCount = [];

    $sWhere = '';
    if ($agent) {
        $sWhere .= ' AND m.Agents IN( "' . implode('","', $agent) . '")';
    }
    // 日结返水
    $sqlDay = "SELECT m.Agents AS `agents`,SUM(r.`R_total`) AS `rebate_total`,SUM(r.`R_total_hg`) AS `rebate_hg`,SUM(r.`R_total_ag`) AS `rebate_ag`,SUM(r.`R_total_ag_dianzi`) AS `rebate_ag_dianzi`,
	    SUM(r.`R_total_ag_dayu`) AS `rebate_ag_dayu`,SUM(r.`R_total_cp`) AS `rebate_cp`,SUM(r.`R_total_ky`) AS `rebate_ky`,SUM(r.`R_total_hgqp`) AS `rebate_hgqp`,SUM(r.`R_total_lyqp`) AS `rebate_lyqp`,SUM(r.`R_total_klqp`) AS `rebate_klqp`,
        SUM(r.`R_total_vgqp`) AS `rebate_vgqp`,SUM(r.`R_total_mg`) AS `rebate_mg`,SUM(r.`R_total_avia`) AS `rebate_avia`,SUM(r.`R_total_fire`) AS `rebate_fire`,SUM(r.`R_total_mw`) AS `rebate_mw`,SUM(r.`R_total_cq`) AS `rebate_cq`,
        SUM(r.`R_total_fg`) AS `rebate_fg`,SUM(r.`R_total_og`) AS `rebate_og`,SUM(r.`R_total_bbin`) AS `rebate_bbin` FROM ".DBPREFIX."rebate_history_report r LEFT JOIN ".DBPREFIX."web_member_data m ON r.userid = m.ID
        WHERE r.R_date BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "' $sWhere GROUP BY m.Agents";
    $resultDay = mysqli_query($dbLink, $sqlDay);
    while ($rowDay = mysqli_fetch_assoc($resultDay)) {
        $agentDayCount[] = $rowDay['agents'];
        $agentRebate[$rowDay['agents']] = [
            "total" => $rowDay['rebate_total'],
            "hg" => $rowDay['rebate_hg'],
            "cp" => $rowDay['rebate_cp'],
            "ag" => $rowDay['rebate_ag'],
            "ag_dianzi" => $rowDay['rebate_ag_dianzi'],
            "ag_dayu" => $rowDay['rebate_ag_dayu'],
            "ky" => $rowDay['rebate_ky'],
            //"hgqp" => $rowDay['rebate_hgqp'],
            "vgqp" => $rowDay['rebate_vgqp'],
            "lyqp" => $rowDay['rebate_lyqp'],
            "klqp" => $rowDay['rebate_klqp'],
            "mg" => $rowDay['rebate_mg'],
            "avia" => $rowDay['rebate_avia'],
            "fire" => $rowDay['rebate_fire'],
            "og" => $rowDay['rebate_og'],
            "mw" => $rowDay['rebate_mw'],
            "cq" => $rowDay['rebate_cq'],
            "fg" => $rowDay['rebate_fg'],
            "bbin" => $rowDay['rebate_bbin'],
        ];
    }

    // 时时返水
    $sqlHour = "SELECT m.Agents AS `agents`,SUM(r.`R_total`) AS `rebate_total`,SUM(r.`R_total_hg`) AS `rebate_hg`,SUM(r.`R_total_ag`) AS `rebate_ag`,SUM(r.`R_total_ag_dianzi`) AS `rebate_ag_dianzi`,
	    SUM(r.`R_total_ag_dayu`) AS `rebate_ag_dayu`,SUM(r.`R_total_cp`) AS `rebate_cp`,SUM(r.`R_total_ky`) AS `rebate_ky`,SUM(r.`R_total_hgqp`) AS `rebate_hgqp`,SUM(r.`R_total_lyqp`) AS `rebate_lyqp`,SUM(r.`R_total_klqp`) AS `rebate_klqp`,
        SUM(r.`R_total_vgqp`) AS `rebate_vgqp`,SUM(r.`R_total_mg`) AS `rebate_mg`,SUM(r.`R_total_avia`) AS `rebate_avia`,SUM(r.`R_total_fire`) AS `rebate_fire`,SUM(r.`R_total_mw`) AS `rebate_mw`,SUM(r.`R_total_cq`) AS `rebate_cq`,
        SUM(r.`R_total_fg`) AS `rebate_fg`,SUM(r.`R_total_og`) AS `rebate_og`,SUM(r.`R_total_bbin`) AS `rebate_bbin` FROM ".DBPREFIX."rebate_hour_hour_report r LEFT JOIN ".DBPREFIX."web_member_data m ON r.userid = m.ID
        WHERE r.R_date_hour BETWEEN '" . $dateStart . "' AND '" . $dateEnd . "' $sWhere GROUP BY m.Agents";
    $resultHour = mysqli_query($dbLink, $sqlHour);
    while ($rowHour = mysqli_fetch_assoc($resultHour)) {
        $agentHourCount[] = $rowHour['agents'];
        $agentRebateHour[$rowHour['agents']] = [
            "total" => $rowHour['rebate_total'],
            "hg" => $rowHour['rebate_hg'],
            "cp" => $rowHour['rebate_cp'],
            "ag" => $rowHour['rebate_ag'],
            "ag_dianzi" => $rowHour['rebate_ag_dianzi'],
            "ag_dayu" => $rowHour['rebate_ag_dayu'],
            "ky" => $rowHour['rebate_ky'],
            //"hgqp" => $rowHour['rebate_hgqp'],
            "vgqp" => $rowHour['rebate_vgqp'],
            "lyqp" => $rowHour['rebate_lyqp'],
            "klqp" => $rowHour['rebate_klqp'],
            "mg" => $rowHour['rebate_mg'],
            "avia" => $rowHour['rebate_avia'],
            "fire" => $rowHour['rebate_fire'],
            "og" => $rowHour['rebate_og'],
            "mw" => $rowHour['rebate_mw'],
            "cq" => $rowHour['rebate_cq'],
            "fg" => $rowHour['rebate_fg'],
            "bbin" => $rowHour['rebate_bbin'],
        ];
    }

    $agentCount = array_unique(array_merge($agentDayCount, $agentHourCount));
    foreach ($agentCount as $agent){
        if(isset($agentRebateHour[$agent])){
            $agentRebate[$agent]['total'] += $agentRebateHour[$agent]['rebate_total'];
            $agentRebate[$agent]['hg'] += $agentRebateHour[$agent]['rebate_hg'];
            $agentRebate[$agent]['cp'] += $agentRebateHour[$agent]['rebate_cp'];
            $agentRebate[$agent]['ag'] += $agentRebateHour[$agent]['rebate_ag'];
            $agentRebate[$agent]['ag_dianzi'] += $agentRebateHour[$agent]['rebate_ag_dianzi'];
            $agentRebate[$agent]['ag_dayu'] += $agentRebateHour[$agent]['rebate_ag_dayu'];
            $agentRebate[$agent]['ky'] += $agentRebateHour[$agent]['rebate_ky'];
           // $agentRebate[$agent]['hgqp'] += $agentRebateHour[$agent]['rebate_hgqp'];
            $agentRebate[$agent]['vgqp'] += $agentRebateHour[$agent]['rebate_vgqp'];
            $agentRebate[$agent]['lyqp'] += $agentRebateHour[$agent]['rebate_lyqp'];
            $agentRebate[$agent]['klqp'] += $agentRebateHour[$agent]['rebate_klqp'];
            $agentRebate[$agent]['mg'] += $agentRebateHour[$agent]['rebate_mg'];
            $agentRebate[$agent]['avia'] += $agentRebateHour[$agent]['rebate_avia'];
            $agentRebate[$agent]['fire'] += $agentRebateHour[$agent]['rebate_fire'];
            $agentRebate[$agent]['og'] += $agentRebateHour[$agent]['rebate_og'];
            $agentRebate[$agent]['mw'] += $agentRebateHour[$agent]['rebate_mw'];
            $agentRebate[$agent]['cq'] += $agentRebateHour[$agent]['rebate_cq'];
            $agentRebate[$agent]['fg'] += $agentRebateHour[$agent]['rebate_fg'];
            $agentRebate[$agent]['bbin'] += $agentRebateHour[$agent]['rebate_bbin'];
        }
    }
    return $agentRebate;
}

/**
 * 代理退水设置（返回输赢金额）
 * @param $userWin
 * @return array
 */
function agentWaterRate($userWin){
    global $dbLink;

    $waterWinLoss = [
        'hg' => 0,
        'cp' => 0,
        'ssc' => 0,
        'project' => 0,
        'trace' => 0,
        'ag' => 0,
        'ag_dianzi' => 0,
        'ag_dayu' => 0,
        'ky' => 0,
        //'hgqp' => 0,
        'vgqp' => 0,
        'lyqp' => 0,
        'klqp' => 0,
        'mg' => 0,
        'avia' => 0,
        'fire' => 0,
        'og' => 0,
        'mw' => 0,
        'cq' => 0,
        'fg' => 0,
        'bbin' => 0,
    ];
    // 按升序查询代理退水比例
    $sql = "SELECT money,rebate FROM " . DBPREFIX . 'agent_rebate_set ORDER BY `money` ASC';
    $result = mysqli_query($dbLink, $sql);
    $nums = mysqli_num_rows($result);
    if($nums > 0){
        $agentWinLoss = $agentWaterRate = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $agentWinLoss[] = $row['money']; // 输赢金额数组
            $agentWaterRate[$row['money']] = json_decode($row['rebate'], true);
        }

        $count = count($agentWinLoss);
        if(bccomp($userWin, $agentWinLoss[$count-1], 4) == 1){
            $waterWinLoss = $agentWaterRate[$agentWinLoss[$count-1]];
        }elseif($count == 1 && bccomp($userWin, $agentWinLoss[0], 4) == 1){
            $waterWinLoss = $agentWaterRate[$agentWinLoss[0]];
        }else{
            for ($i = 0; $i < $count; $i ++) {
                if (bccomp($userWin, $agentWinLoss[$i], 4) >=0 && bccomp($userWin, $agentWinLoss[$i+1], 4) == -1) {
                    $waterWinLoss = $agentWaterRate[$agentWinLoss[$i]];
                }
            }
        }
    }
    return $waterWinLoss;
}

/**
 * 代理退佣设置（返回输赢金额）
 * @param $userWin
 * @return array
 */
function agentCommissionRate($userWin){
    global $dbLink;

    $waterWinLoss = [
        'hg' => 0,
        'cp' => 0,
        'ssc' => 0,
        'project' => 0,
        'trace' => 0,
        'ag' => 0,
        'ag_dianzi' => 0,
        'ag_dayu' => 0,
        'ky' => 0,
       // 'hgqp' => 0,
        'vgqp' => 0,
        'lyqp' => 0,
        'klqp' => 0,
        'mg' => 0,
        'avia' => 0,
        'fire' => 0,
        'og' => 0,
        'mw' => 0,
        'cq' => 0,
        'fg' => 0,
        'bbin' => 0,
    ];
    // 按升序查询代理退佣比例
    $sql = "SELECT money,rebate FROM " . DBPREFIX . 'agent_commission_set ORDER BY `money` ASC';
    $result = mysqli_query($dbLink, $sql);
    $nums = mysqli_num_rows($result);
    if($nums > 0){
        $agentWinLoss = $agentCommissionRate = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $agentWinLoss[] = $row['money']; // 输赢金额数组
            $agentCommissionRate[$row['money']] = json_decode($row['rebate'], true);
        }

        $count = count($agentWinLoss);
        if(bccomp($userWin, $agentWinLoss[$count-1], 4) == 1){
            $waterWinLoss = $agentCommissionRate[$agentWinLoss[$count-1]];
        }elseif($count == 1 && bccomp($userWin, $agentWinLoss[0], 4) == 1){
            $waterWinLoss = $agentCommissionRate[$agentWinLoss[0]];
        }else{
            for ($i = 0; $i < $count; $i ++) {
                if (bccomp($userWin, $agentWinLoss[$i], 4) >=0 && bccomp($userWin, $agentWinLoss[$i+1], 4) == -1) {
                    $waterWinLoss = $agentCommissionRate[$agentWinLoss[$i]];
                }
            }
        }
    }
    return $waterWinLoss;
}

/**
 * 代理存取款、优惠、彩金统计
 * (代理佣金报表)
 * @param $dateStart
 * @param $dateEnd
 * @param array $agent
 * @return array
 */
function agentOrderCount($dateStart, $dateEnd, $agent = []) {
    global $dbLink;

    $sWhere = '`count_date` BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '"';
    if (!empty($agent)) {
        $sWhere .= ' AND `agents` IN ( "' . implode('","', $agent) . '")';
    }

    $agentOrder = [];
    $sql = "SELECT `agents`, SUM(`deposit`) AS total_deposit, SUM(`withdraw`) AS total_withdraw, SUM(`extra`) AS total_extra, SUM(`gift`) AS total_gift FROM " . DBPREFIX . "web_agents_daily_count WHERE " . $sWhere . " GROUP BY `agents`";
    $result = mysqli_query($dbLink, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $agentOrder[$row['agents']] = $row;
    }

    return $agentOrder;
}

/**
 * 游戏类型
 * @param $type
 * @return mixed
 */
function typeMsg($type){
    $typeMsg = [
        "hg" => "皇冠体育",
        "cp" => "体育彩票",
        "ag" => "AG视讯",
        "ag_dianzi" => "AG电子",
        "ag_dayu" => "AG打鱼捕鱼",
        "ky" => "开元棋盘",
       // "hgqp" => "皇冠棋牌",
        "vgqp" => "VG棋牌",
        "lyqp" => "乐游棋牌",
        "klqp" => "快乐棋牌",
        "mg" => "MG电子",
        "avia" => "泛亚电竞",
        "fire" => "雷火电竞",
        "ssc" => "彩票信用盘",
        "project" => "彩票官方盘",
        "trace" => "彩票官方追号",
        "og" => "OG视讯",
        "mw" => "MW电子",
        "cq" => "CQ电子",
        "fg" => "FG电子",
        "bbin" => "BBIN视讯",
        "total" => "合计",
    ];
    return $typeMsg[$type];
}

/**
 * 查询月份（代理佣金）
 * @param $count
 * @return array
 */
function monthRange($count){
    $monthRange = [];
    $cYear = floor(date("Y"));
    $cMonth = floor(date("m"));
    for($i = 0; $i < $count; $i++){
        $nMonth = $cMonth - $i;
        $cYear = $nMonth == 0 ? ($cYear - 1) : $cYear;
        $nMonth = $nMonth <= 0 ? 12 + $nMonth : $nMonth;
        $monthRange[] = $cYear . '-' . (strlen($nMonth) == 1 ? '0' . $nMonth : $nMonth);
    }
    return $monthRange;
}