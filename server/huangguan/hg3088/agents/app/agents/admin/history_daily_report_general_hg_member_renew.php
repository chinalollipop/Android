<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
/*	
 *
 * 	重新生成本会员当天盘口历史报表
 *
 * 赔率小于0.5不计入有效投注
 * 独赢、单双、半场独赢、滚球独赢、滚球单双、滚球半场独赢，赔率小于1.5的不计入有效投注
 *
 * 独赢 1x2
 * 单双 Odd/Even
 * 半场独赢 1st Half 1x2
 * 滚球独赢 Running 1x2
 * 滚球半场独赢 1st Half Running 1x2
 *
 * */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$conn = $dbMasterLink;
$userid = isset($_REQUEST['userid']) ? $_REQUEST['userid'] : '';
$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
$StartTime = isset($_REQUEST['StartTime']) ? $_REQUEST['StartTime'] : '';

if(isset($StartTime) && $StartTime!='') {

    //重新生成某天-某天的报表数据，包含 开始天，不包含 结束天
    $start_time = strtotime($StartTime);

    if($StartTime > date("Y-m-d", strtotime("-1 day"))) {
        exit("起始时间不能大于昨天");
    }

    $stop_time = strtotime($StartTime."+1 day");

    countall($start_time, $stop_time, $userid, $username, false, $conn);
}

/**
 * 
 * 根据条件生成历史报表
 * @param date $StartTime
 * @param date $stop_time
 * @param int $userid
 * @param string $username
 * @param boolean $reGeneral
 * @param object $conn
 *
 */
function countall($StartTime, $stop_time, $userid, $username, $reGeneral=false, $conn){
    global $dbLink;
	$result=array();
	
	//如果结束时间大于当天凌晨，则将当天凌晨当做结束时间
	if($stop_time > strtotime(date("Y-m-d"))) {
		$stop_time = strtotime(date("Y-m-d"));
	}

//    echo date('YmdHis')."  插入库开始\n";
	//首先，从历史报表里面清楚掉数据，再重新计算
	$sql = " DELETE from ".DBPREFIX."web_report_history_report_data where userid='{$userid}' and M_Date >= '".date("Y-m-d",$StartTime)."' and M_Date < '".date("Y-m-d",$stop_time)."'";
	$result=mysqli_query($conn, $sql);
	$sql = " DELETE from ".DBPREFIX."web_report_history_report_flag where order_date >= '".date("Y-m-d",$StartTime)."' and order_date < '".date("Y-m-d",$stop_time)."' ";
	$result=mysqli_query($conn, $sql);
	
	for($i=1; $i<=50; $i++) {

        $end_time = $StartTime + 3600*24;

		$result=mysqli_query($conn, "START TRANSACTION");
		if (!$result) {
		    echo('事务开启失败！ ' . mysqli_error($conn));
		    continue;
		}

		// 全部捞出，然后根据（游戏类别、用户名、日期）将数据归类
		if($end_time <= $stop_time) {

            @error_log(date('Y-m-d H:i:s')."----------------------计算注单量、下注总额、输赢汇总 Start".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_user_renew.php.log');
            $sql = "select Userid, M_Name as username, Agents, World, Corprator, Super, Admin, Active as game_code,sum(1) as count_pay,sum(BetScore) as total, sum(M_Result) as user_win,M_date,BetTime as bet_time,now() as create_time from ".DBPREFIX."web_report_data 
            where Userid = '{$userid}' and M_Date='".date('Y-m-d',$StartTime)."' and testflag=0 and `Cancel`=0 
            group by username,Active";
            $result=mysqli_query($dbLink, $sql);
			if(!$result) {
				$result=mysqli_query($dbLink, "ROLLBACK");
			    die('计算报表数据失败11！ ' . mysqli_error($conn));
			}
            $cou = mysqli_num_rows($result);
			if ($cou>0){

                $data_total=[];
			    while ($row = mysqli_fetch_assoc($result)){
			        $data_total[]=$row;
                }

                @error_log(date('Y-m-d H:i:s')."----------------------计算有效下注总额 Start".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_member_renew.log');
                // valid_money 有效下注总额（用户，分类）
                $sql = "select Userid, M_Name as username, sum(VGOLD) as valid_money, Active as game_code from ".DBPREFIX."web_report_data 
                where Userid = '{$userid}' and M_Date='".date('Y-m-d',$StartTime)."' and 
                checked = 1 and testflag=0 and `Cancel`=0 
                group by username,Active";
                $result=mysqli_query($dbLink, $sql);
                if(!$result) {
                    $result=mysqli_query($conn, "ROLLBACK");
                    die('计算报表数据失败22！ ' . mysqli_error($conn));
                }
                $cou = mysqli_num_rows($result);
                if ($cou>0) {
                    $data_valid_money = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data_valid_money[] = $row;
                    }
                }else{
                    @error_log("计算有效下注金额:0 ".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_member_renew.log');
                }

                @error_log(date('Y-m-d H:i:s')."----------------------计算返水有效投注金额 Start".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_member_renew.log');
                // valid_money 有效下注总额（用户，分类）
                $sql = "select Userid, M_Name as username, BetType_en, M_Rate, VGOLD, Active as game_code from ".DBPREFIX."web_report_data 
                where Userid = '{$userid}' and  M_Date='".date('Y-m-d',$StartTime)."' and 
                checked = 1 and testflag=0 and `Cancel`=0 ";
                $result=mysqli_query($dbLink, $sql);
                if(!$result) {
                    $result=mysqli_query($conn, "ROLLBACK");
                    die('计算报表数据失败33！ ' . mysqli_error($conn));
                }
                $cou = mysqli_num_rows($result);
                if ($cou>0) {
                    $data_rebate_valid_money=[];
                    while ($row = mysqli_fetch_assoc($result)) {
                        if($row['BetType_en'] == '1x2' or $row['BetType_en'] == 'Odd/Even' or $row['BetType_en']=='1st Half 1x2' or
                            $row['BetType_en'] =='Running 1x2' or $row['BetType_en']=='1st Half Running 1x2'){

                            if ($row['M_Rate']>=1.5){ // 单双、独赢、半场独赢，赔率小于1.5的不算入有效投注
                                $data_rebate_valid_money[]=$row;
                            }
                        }else{
                            if ($row['M_Rate']>=0.5){ // 0.5以下的不算入有效投注
                                $data_rebate_valid_money[]=$row;
                            }
                        }
                    }

                }else{
                    @error_log("计算返水有效投注金额: ".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_member_renew.log');
                }

                // 按照用户名、游戏类别 归类下注金额、有效投注金额、有效返水投注金额
                foreach ($data_total as $k => $v){

                    foreach ($data_valid_money as $k1 => $v1){

                        if ($v['game_code'] == $v1['game_code'] && $v['username'] == $v1['username']){
                            $data_total[$k]['valid_money'] += $v1['valid_money'];
                        }
                    }

                    foreach ($data_rebate_valid_money as $k3 => $v3){
                        if ($v['game_code'] == $v3['game_code'] && $v['username'] == $v3['username']){
                            $data_total[$k]['valid_money_rebate'] += $v3['VGOLD'];
                        }
                    }

                }

                foreach ($data_total as $k =>$v){

                    $sql = "insert into ".DBPREFIX."web_report_history_report_data(userid, username, Agents, World, Corprator, Super, Admin, game_code,count_pay,total,valid_money,valid_money_rebate,user_win,M_date,bet_time,create_time)
                    VALUE( ".$v['Userid'].",'".$v['username']."','".$v['Agents']."','".$v['World']."','".$v['Corprator']."','".$v['Super']."','".$v['Admin']."',
                    '".$v['game_code']."','".$v['count_pay']."','".$v['total']."','".$v['valid_money']."','".$v['valid_money_rebate']."','".$v['user_win']."','".$v['M_date']."','".$v['bet_time']."','".$v['create_time']."' ) ";

                    @error_log(date('Y-m-d H:i:s')."----------------------记录报表 end".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_member_renew.log');
//                    @error_log($sql.PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_member_renew.log');
                    $result=mysqli_query($conn, $sql);
                    if(!$result) {
                        $result=mysqli_query($conn, "ROLLBACK");
                        die('计算报表数据失败！ ' . mysqli_error($conn));
                    }
                }

            }

            if($reGeneral) {
				$sql = " insert into ".DBPREFIX."web_report_history_report_flag(order_date, flag) value('".date("Y-m-d",$StartTime)."', 2) ";
			}else {
				$sql = " insert into ".DBPREFIX."web_report_history_report_flag(order_date, flag) value('".date("Y-m-d",$StartTime)."', 1) ";
			}

			$result=mysqli_query($conn, $sql);
			if(!$result) {
				$result=mysqli_query($conn, "ROLLBACK");
			    echo('插入计算成功表示符失败！' . mysqli_error($conn));
			    continue;
			}else {
				$result=mysqli_query($conn, "COMMIT");
			}
		}else {
			echo "会员 {$username}，".date("Y-m-d", $StartTime-86400)."所有的计算完成！\n";
			break;
		}
		$StartTime = $end_time;
	}
}

echo "<a href='query.php?uid={$_SESSION['Oid']}&langx=zh-cn&lv=M'>查询注单</a>";