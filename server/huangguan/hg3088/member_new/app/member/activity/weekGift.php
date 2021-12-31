<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activityBill.class.php");

/**
 * 1. 会员请求，每周日申请，
 * 2. 上一周负盈利(周存款-周取款)，扣除返点和优惠金额， 确认领取彩金
 * 3. 数据表插入
 *      如果用户不存在 插入
 *      如果用户存在  获取最早添加时间  如果大于一月，更新数据。 小于一月，再次插入
 */
$user_id = $_SESSION['userid'];
$username = $_SESSION['UserName'];

if(!$user_id) {
    $status = array('status'=>'0', 'info'=>'请重新登录哦!');
    echo json_encode($status);exit;
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
        $status = array('status'=>'401.66', 'info'=>'账号分层异常，请联系我们在线客服');
        echo json_encode($status);exit;
    }
}

//活动申请时间为  美东时间每周一00:00至次日00:00   北京时间周一中午16：00至次日12：00之前
$nowMonday =  mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y'))+4*60*60; //本周一start
$nowTuesday = mktime(0,0,0,date('m'),date('d')-date('w')+2,date('Y')); //本周二start

if(time() < $nowMonday || time() >= $nowTuesday){
    $status = array('status'=>'0', 'info'=>'请于北京时间每周一16:00至次日12:00申请转运金哦!');
    echo json_encode($status);exit;
}

//异常点击周周好礼领取
$redisObj = new Ciredis();
$attTime = $redisObj->getSimpleOne('activity_weekgift_useid_'.$user_id);
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<2*60) {
        $status = array('status'=>'0', 'info'=>'不允许多次点击,请稍后申请!');
        echo json_encode($status);exit;
    }
}
// 插入当前申请时间，存入redis, 确保不允许重复申请
$redisObj->insert('activity_weekgift_useid_'.$user_id, time(), 3*60);

//获取上周起始时间戳和结束时间戳  用于统计存取款
$time['beginLastweek']=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
$time['endLastweek']=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
$aCp_default = $database['cpDefault'];
$memberAddDate = $_SESSION['AddDate'];

// 会员账单活动
$activityBill = new ActivityBill();
// 查询账单表
//$numBets = $activityBill->lastWeekBill($user_id,$username,$time);


$userWinTotal = getGameReport($user_id, $username, $time)*-1;//报表总输赢
$rebateTotal = getRebateGold($user_id, $time);//返水
$youhuiTotal = getDepositYouhui($user_id, $time);//充值优惠
$caijinTotal = getCaijin($user_id, $time);//彩金

//实际输赢=报表总输赢-返水-优惠-彩金
$numBets = $userWinTotal-$rebateTotal-$youhuiTotal-$caijinTotal;

// 如果上周负盈利小于1千， 不允许会员申请
if($numBets < 1000){
    $status = array('status'=>'0', 'info'=>'上周负盈利不符合要求，不允许申请!');
    echo json_encode($status);exit;
}

// 如果当前周一已申请过，不允许重复申请
$check_att_sql = "select * from ".DBPREFIX."web_transfer where userid='$user_id' and add_time BETWEEN '".date("Y-m-d H:i:s",$nowMonday)."' and '".date("Y-m-d H:i:s",$nowTuesday)."'";
$checkresult = mysqli_query($dbLink,$check_att_sql);
$todayData = mysqli_fetch_assoc($checkresult);
if($todayData){
    $status = array('status'=>'0', 'info'=>'您已在本周一申请过本活动，不允许重复申请哦!');
    echo json_encode($status);exit;
}

// (周周负盈利活动表只保留一月数据)//检查一个月以上数据
$checkTime = date("Y-m-d 23:59:59", strtotime(-date('d').'day')); //上月最后时间
$att_sql = "select * from ".DBPREFIX."web_transfer where userid='$user_id' and add_time <= '$checkTime'";
$result = mysqli_query($dbLink,$att_sql);
$att_statis = mysqli_fetch_assoc($result);



// 查询周周负盈利活动申请表是否有该用户数据
if(empty($att_statis)){  // 用户不存在
    $flag = 1;//进行插入操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['weekProfit'] = $numBets; //负盈利
    $data['EventName'] = '周转运金';
    $levelResult = $activityBill->transferGoldLevel($data['weekProfit']); //  transferGold彩金 status状态
    $data['transferGold'] = sprintf("%.2f",$levelResult['transferGold']);  // 转运金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['review_time'] = date("Y-m-d H:i:s"); // 派发时间
    $data['review_name'] = ''; // 审核人
    $data['status'] = $levelResult['status'];
} else{  // 用户存在
    $flag = 0;//进行修改操作
    $data['userid'] = $user_id;
    $data['UserName'] = trim($username);
    $data['weekProfit'] = $numBets; //负盈利
    $data['EventName'] = '周转运金';
    $levelResult = $activityBill->transferGoldLevel($data['weekProfit']); //  transferGold彩金 status状态
    $data['transferGold'] = sprintf("%.2f",$levelResult['transferGold']);  // 领取金额
    $data['add_time'] = date("Y-m-d H:i:s"); // 添加时间
    $data['upd_time'] = date("Y-m-d H:i:s"); // 修改时间
    $data['review_time'] = date("Y-m-d H:i:s");; // 派发时间
    $data['review_name'] = ''; // 审核人
    $data['status'] = $levelResult['status'];
}
foreach($data as $key=>$val){
    $tmp[]=$key.'=\''.$val.'\'';
}
if($flag==1){ // 用户不存在
    $sqlinsert="insert into ".DBPREFIX."web_transfer set ".implode(',',$tmp);
    $res = mysqli_query($dbMasterLink,$sqlinsert);
}else{ // 用户存在
    $sqlupdate="update ".DBPREFIX."web_transfer set ".implode(',',$tmp)." where ID = {$att_statis['ID']}";
    //@error_log($sqlupdate.PHP_EOL,  3,  '/tmp/aaa.log');
    $res = mysqli_query($dbMasterLink,$sqlupdate);
}
if(!$res){
    $status = array('status'=>'0', 'info'=>'系统繁忙，请稍后再试!');
    echo json_encode($status);
} else {
    $status = array('status'=>'1', 'info'=>'已申请周周转运金,请联系客服等待派发!');
    echo json_encode($status);
}

/**
 * 查询输赢报表
 */
function getGameReport($userid, $username, $time){
    global $dbLink, $aCp_default;
    $dUserWin=0;
    $beginLastweek = date('Y-m-d H:i:s', $time['beginLastweek']);
    $endLastweek = date('Y-m-d H:i:s', $time['endLastweek']);

    // 体育输赢
    $res_hg = mysqli_query($dbLink, "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from ".DBPREFIX."web_report_history_report_data 
        where userid=$userid  AND M_Date >= '{$beginLastweek}' and M_Date<='{$endLastweek}' ");
    $cou_hg = mysqli_num_rows($res_hg);
    if ($cou_hg>0){
        $row_hg = mysqli_fetch_assoc($res_hg);
        $dUserWin += $row_hg['user_win'];
    }

    if(in_array(TPL_FILE_NAME, ['0086', '6668'])) {

        // 体育彩票输赢
        $start_day_cp = $time['beginLastweek'];
        $end_day_cp = $time['endLastweek'];
        $cpDbLink = @mysqli_connect($aCp_default['host'], $aCp_default['user'], $aCp_default['password'], $aCp_default['dbname'], $aCp_default['port']) or die("mysqli connect error" . mysqli_connect_error());
        $sql = "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(user_win) as user_win from gxfcy_history_bill_report_less_12hours 
        where username='{$username}' AND bet_time BETWEEN '" . $start_day_cp . "' and '" . $end_day_cp . "' ";
        $res_cp = mysqli_query($cpDbLink, $sql);
        $cou_cp = mysqli_num_rows($res_cp);
        if ($cou_cp > 0) {
            $row_cp = mysqli_fetch_assoc($res_cp);
            $dUserWin += $row_cp['user_win'];
        }
    }
    else{ // 太阳城-10001、金沙-10002、威尼斯人-10003、3366-10004

        // 第三方彩票信用输赢
        $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "web_third_ssc_history_report 
        WHERE hg_uid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
        $res_ssc = mysqli_query($dbLink, $sql);
        $cou_ssc = mysqli_num_rows($res_ssc);
        if ($cou_ssc > 0) {
            $row_ssc = mysqli_fetch_assoc($res_ssc);
            $dUserWin += $row_ssc['user_win'];
        }

        // 第三方彩票官方输赢
        $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "web_third_projects_history_report 
        WHERE hg_uid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
        $res_project = mysqli_query($dbLink, $sql);
        $cou_project = mysqli_num_rows($res_project);
        if ($cou_project > 0) {
            $row_project = mysqli_fetch_assoc($res_project);
            $dUserWin += $row_project['user_win'];
        }

        // 第三方彩票官方追号输赢
        $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "web_third_traces_history_report 
        WHERE hg_uid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
        $res_trace = mysqli_query($dbLink, $sql);
        $cou_trace = mysqli_num_rows($res_trace);
        if ($cou_trace > 0) {
            $row_trace = mysqli_fetch_assoc($res_trace);
            $dUserWin += $row_trace['user_win'];
        }

    }

    // AG视讯输赢
    $res_ag = mysqli_query($dbLink, "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(profit) as user_win from ".DBPREFIX."ag_projects_history_report 
        where userid=$userid AND bet_time BETWEEN '{$beginLastweek}' and '{$endLastweek}' and game_code='BR'");
    $cou_ag = mysqli_num_rows($res_ag);
    if ($cou_ag>0) {
        $row_ag = mysqli_fetch_assoc($res_ag);
        $dUserWin += $row_ag['user_win'];
    }

    // KY输赢
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` 
        FROM " . DBPREFIX . "ky_history_report 
        WHERE userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_ky = mysqli_query($dbLink, $sql);
    $cou_ky = mysqli_num_rows($res_ky);
    if ($cou_ky > 0) {
        $row_ky = mysqli_fetch_assoc($res_ky);
        $dUserWin += $row_ky['user_win'];
    }

    // AG电子输赢
    $res_ag_dianzi = mysqli_query($dbLink, "select sum(count_pay) as count_pay, sum(total) as total, sum(valid_money) as valid_money, sum(profit) as user_win from ".DBPREFIX."ag_projects_history_report 
        where userid=$userid AND bet_time BETWEEN '{$beginLastweek}' and '{$endLastweek}' and (game_code='' or game_code='SLOT') ");
    $cou_ag_dianzi = mysqli_num_rows($res_ag_dianzi);
    if ($cou_ag_dianzi>0) {
        $row_ag_dianzi = mysqli_fetch_assoc($res_ag_dianzi);
        $dUserWin += $row_ag_dianzi['user_win'];
    }

    // AG捕鱼王打鱼输赢
    $res_ag_dayu = mysqli_query($dbLink, "select sum(BulletOutNum) as count_pay, sum(Cost) as total, sum(Cost) as valid_money, sum(Earn) as shouru from ".DBPREFIX."ag_buyu_scene 
        where userid=$userid AND EndTime BETWEEN '{$beginLastweek}' and '{$endLastweek}' ");
    $cou_ag_dayu = mysqli_num_rows($res_ag_dayu);
    if ($cou_ag_dayu>0) {
        $row_ag_dayu = mysqli_fetch_assoc($res_ag_dayu);
        $dUserWin += $row_ag_dayu['shouru']- $row_ag_dayu['valid_money'];
    }

    // HGQP输赢
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` 
        FROM " . DBPREFIX . "ff_history_report 
        WHERE userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_hgqp = mysqli_query($dbLink, $sql);
    $cou_hgqp = mysqli_num_rows($res_hgqp);
    if ($cou_hgqp > 0) {
        $row_hgqp = mysqli_fetch_assoc($res_hgqp);
        $dUserWin += $row_hgqp['user_win'];
    }

    // VGQP输赢
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` 
        FROM " . DBPREFIX . "vg_history_report 
        WHERE userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_vgqp = mysqli_query($dbLink, $sql);
    $cou_vgqp = mysqli_num_rows($res_vgqp);
    if ($cou_vgqp > 0) {
        $row_vgqp = mysqli_fetch_assoc($res_vgqp);
        $dUserWin += $row_vgqp['user_win'];
    }

    // 乐游棋牌输赢
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`, SUM(`total_revenue`) AS `total_revenue` 
        FROM " . DBPREFIX . "ly_history_report 
        WHERE userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_lyqp = mysqli_query($dbLink, $sql);
    $cou_lyqp = mysqli_num_rows($res_lyqp);
    if ($cou_lyqp > 0) {
        $row_lyqp = mysqli_fetch_assoc($res_lyqp);
        $dUserWin += $row_lyqp['user_win'];
    }

    // MG电子输赢
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "mg_history_report 
        WHERE userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_mg = mysqli_query($dbLink, $sql);
    $cou_mg = mysqli_num_rows($res_mg);
    if ($cou_mg > 0) {
        $row_mg = mysqli_fetch_assoc($res_mg);
        $dUserWin += $row_mg['user_win'];
    }

    // 泛亚电竞输赢（实时统计报表、无需计算当天的报表数据）
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win`
        FROM " . DBPREFIX . "avia_history_report 
        WHERE userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_avia = mysqli_query($dbLink, $sql);
    $cou_avia = mysqli_num_rows($res_avia);
    if ($cou_avia > 0) {
        $row_avia = mysqli_fetch_assoc($res_avia);
        $dUserWin += $row_avia['user_win'];
    }

    // OG视讯输赢
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "og_history_report 
        WHERE userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_og = mysqli_query($dbLink, $sql);
    $cou_og = mysqli_num_rows($res_og);
    if ($cou_og > 0) {
        $row_og = mysqli_fetch_assoc($res_og);
        $dUserWin += $row_og['user_win'];
    }

    // WM电子输赢
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "mw_history_report 
        WHERE  userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_mw = mysqli_query($dbLink, $sql);
    $cou_mw = mysqli_num_rows($res_mw);
    if ($cou_mw > 0) {
        $row_mw = mysqli_fetch_assoc($res_mw);
        $dUserWin += $row_mw['user_win'];
    }

    // CQ9电子输赢
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "cq9_history_report 
        WHERE  userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_cq = mysqli_query($dbLink, $sql);
    $cou_cq = mysqli_num_rows($res_cq);
    if ($cou_cq > 0) {
        $row_cq = mysqli_fetch_assoc($res_cq);
        $dUserWin += $row_cq['user_win'];
    }

    // FG电子输赢
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "fg_history_report 
        WHERE  userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_fg = mysqli_query($dbLink, $sql);
    $cou_fg = mysqli_num_rows($res_fg);
    if ($cou_fg > 0) {
        $row_fg = mysqli_fetch_assoc($res_fg);
        $dUserWin += $row_fg['user_win'];
    }

    // BBIN真人视讯输赢
    $sql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money`, SUM(`total_bet`) AS `total`, SUM(`total_profit`) AS `user_win` 
        FROM " . DBPREFIX . "jx_bbin_history_report 
        WHERE  userid=$userid AND `count_date` BETWEEN '{$beginLastweek}' and '{$endLastweek}'";
    $res_bbin = mysqli_query($dbLink, $sql);
    $cou_bbin = mysqli_num_rows($res_bbin);
    if ($cou_bbin > 0) {
        $row_bbin = mysqli_fetch_assoc($res_bbin);
        $dUserWin += $row_bbin['user_win'];
    }

    return $dUserWin;
}

/**
 * 查询返水（时时返水、天天返水）
 */
function getRebateGold($userid, $time){
    global $dbLink;
    $gold = 0;
    $res = mysqli_query($dbLink, "select sum(Gold) as Gold from ".DBPREFIX."web_sys800_data 
        where userid=$userid AND `Type`='R' AND `Checked`=1 AND AddDate >= '".date('Y-m-d',$time['beginLastweek'])."' and AddDate<='".date('Y-m-d',$time['endLastweek'])."' ");
    $cou = mysqli_num_rows($res);
    if ($cou>0){
        while ($row = mysqli_fetch_assoc($res)){
            $gold += $row['Gold'];
        }
    }
    return $gold;
}

/**
 * 查询充值优惠总额
 */
function getDepositYouhui($userid, $time){
    global $dbLink, $memberAddDate;
    $youhui_total = 0;

    $res = mysqli_query($dbLink, "select userid,Gold,currency_after,moneyf,Bank,DepositAccount from ".DBPREFIX."web_sys800_data 
        where userid=$userid AND `Type`='S' AND Payway='N' AND Preferential=1 AND Checked=1 and AddDate >= '".date('Y-m-d',$time['beginLastweek'])."' and AddDate<='".date('Y-m-d',$time['endLastweek'])."' ");
    $cou = mysqli_num_rows($res);
    if ($cou>0){
        while ($row = mysqli_fetch_assoc($res)){
            $Gold_no_youhui = $row['currency_after'] - $row['moneyf']; //存款金额（无优惠）
            $aDepositAccount = explode('-',$row['DepositAccount']);
            $youhui_row = preferentialGold($Gold_no_youhui, $memberAddDate,$row['Bank'],$aDepositAccount[3]);
            $youhui_total += $youhui_row;
        }
    }
    return $youhui_total;
}

/**
 * 查询活动彩金总额
 */
function getCaijin($userid, $time){
    global $dbLink;
    $gold = 0;
    $sql = "select sum(Gold) as Gold from ".DBPREFIX."web_sys800_data 
        where userid=$userid AND `Type`='S' AND Checked=1 AND `Payway` IN ('O', 'G') AND `discounType` = 0 and 
        AddDate >= '".date('Y-m-d',$time['beginLastweek'])."' and AddDate<='".date('Y-m-d',$time['endLastweek'])."'";
    $res = mysqli_query($dbLink, $sql);
    $cou = mysqli_num_rows($res);
    if ($cou){
        $row = mysqli_fetch_assoc($res);
        $gold = $row['Gold'];
    }
    return $gold;
}
