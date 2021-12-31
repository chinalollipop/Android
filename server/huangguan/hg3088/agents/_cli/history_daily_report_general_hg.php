<?php
/*	
 *
 *
 *
 * 	每天定时自动计算体育报表的代码，生成历史报表（用途：1、方便给会员返水使用，2、后台报表查询）
 *
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
 * 	1，	只支持cli模式下的运行。
 * 	2，	示例URL:
 *	 			php history_daily_report_general_hg.php 									//定时任务，生成昨日的数据
 *			或	
 *	 			php history_daily_report_general_hg.php old								//重新批量生成数据
 *			或
 *	 			php history_daily_report_general_hg.php 2016-10-06						//重新生成某一个天的数据
 *			或
 *	 			php history_daily_report_general_hg.php 2016-10-06 2016-10-07 1	//重新生成从某一个天到某一天的数据
 * 	3，	开启时间，每天的美东时间3,4,5点执行计划任务
 * */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/config.inc.php";
$conn = $dbMasterLink;

//只在CLI命令下有效
if (php_sapi_name() == "cli") {

//	print_r($argv); die;

	//只设置参数a old，则从9.1号开始，将今天之前的所有数据全部重新生成
	if(isset($argv[1]) && $argv[1] == 'old') {

	    $start_time = mktime(0, 0, 0, 5, 1, 2018);		//从2018.5.1号可以计算数据
		//当天不计算到历史数据里面，因为未完成
		$stop_time = strtotime(date("Y-m-d"));
		countall($start_time,$stop_time, false, $conn);
	}elseif(isset($argv[1])) {

		//重新生成某天-某天的报表数据，包含 开始天，不包含 结束天
		$start_time = strtotime($argv[1]);

		if($argv[1] > date("Y-m-d", strtotime("-1 day"))) {
			exit("起始时间不能大于昨天");
		}
		
		if(isset($argv[2]) && !empty($argv[2])) {
			//如果结束时间大于今天，那么将今天作为结束时间
			if($argv[2] > date("Y-m-d")) {
				$argv[2] = date("Y-m-d");
			}
			$stop_time = strtotime($argv[2]);
		}else {
			$stop_time = strtotime($argv[1]."+1 day");
		}
		
		//如果设置了参数 c 1，则置gxfcy_history_bill_report_flag标示位为2，代表此数据已经重新生成
		if( isset($argv[3]) && $argv[3] == 1 ) {
			twopreweekcountall($start_time, $stop_time, $conn);
		}else {
			countall($start_time, $stop_time, false, $conn);
		}
	}else {

		//每日生成昨日的报表数据
		$start_time = strtotime(date("Y-m-d",strtotime("-1 day")));
		$stop_time = strtotime(date("Y-m-d"));
		dailycountall($start_time,$stop_time, $conn);
	}
}

/**
 * 
 * 每日生成历史報表，多次cronjob运行的情况下，只需要检查疏漏，不需要重复执行
 * @param date $StartTime
 * @param date $stop_time
 */
function dailycountall($StartTime, $stop_time, $conn){
    global $dbLink;
	$result=array();

	//首先，从历史报表里面清楚掉数据，再重新计算
	$sql = " select * from ".DBPREFIX."web_report_history_report_flag where order_date >= '".date("Y-m-d",$StartTime)."' and order_date < '".date("Y-m-d",$stop_time)."' ";
	$result=mysqli_query($dbLink, $sql);
	while($row = mysqli_fetch_array($result)) {
		//已经生成
		if(isset($row['flag']) && $row['flag'] == 1) {
			exit("数据已经生成");
		}
	}
	countall($StartTime, $stop_time, false, $conn);
	exit("数据生成成功");
}

/**
 * 
 * 重新生成某段时间点历史报表，多次cronjob运行的情况下，只需要检查疏漏，不需要重复执行
 * @param date $StartTime
 * @param date $stop_time
 */
function twopreweekcountall($StartTime, $Endtime, $conn){
    global $dbLink;
	$result=array();

	//首先判断这天的数据是否存在或者是否已经重新生成
	for($i=1; $i<=50; $i++) {
		$stop_time = $StartTime + 3600*24;
		if($stop_time <= $Endtime) {
			$sql = " select * from ".DBPREFIX."web_report_history_report_flag where order_date >= '".date("Y-m-d",$StartTime)."' and order_date < '".date("Y-m-d",$stop_time)."' ";
			$result=mysqli_query($dbLink, $sql);
			$row = mysqli_fetch_array($result);
			//已经生成
			if(isset($row['flag']) && $row['flag'] == 2) {
				echo date("Y-m-d", $StartTime)."报表已经重新生成！";
				$StartTime = $stop_time;
				continue;
			}
			countall($StartTime, $stop_time, true, $conn);
		}else {
			exit("数据重新生成成功");
		}
		$StartTime = $stop_time;
	}
}

/**
 * 
 * 根据条件生成历史报表
 * @param date $StartTime
 * @param date $stop_time
 */
function countall($StartTime, $stop_time, $reGeneral=false, $conn){

	$result=array();
	
	//如果结束时间大于当天凌晨，则将当天凌晨当做结束时间
	if($stop_time > strtotime(date("Y-m-d"))) {
		$stop_time = strtotime(date("Y-m-d"));
	}

//    echo date('YmdHis')."  插入库开始\n";
	//首先，从历史报表里面清楚掉数据，再重新计算
	$sql = " DELETE from ".DBPREFIX."web_report_history_report_data where M_Date >= '".date("Y-m-d",$StartTime)."' and M_Date < '".date("Y-m-d",$stop_time)."'";
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

//            @error_log(date('Y-m-d H:i:s')."----------------------计算注单量、下注总额、输赢汇总 Start".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg.log');
            $sql = "select Userid, M_Name as username, Agents, World, Corprator, Super, Admin, Active as game_code,sum(1) as count_pay,sum(BetScore) as total, sum(M_Result) as user_win,M_date,BetTime as bet_time,now() as create_time from ".DBPREFIX."web_report_data 
            where M_Date='".date('Y-m-d',$StartTime)."' and testflag=0 and `Cancel`=0 
            group by username,Active";
            $result=mysqli_query($conn, $sql);
			if(!$result) {
				$result=mysqli_query($conn, "ROLLBACK");
			    die('计算报表数据失败11！ ' . mysqli_error($conn));
			}
            $cou = mysqli_num_rows($result);
			if ($cou>0){

                $data_total=[];
			    while ($row = mysqli_fetch_assoc($result)){
			        $data_total[]=$row;
                }

//                @error_log(date('Y-m-d H:i:s')."----------------------计算有效下注总额 Start".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg.log');
                // valid_money 有效下注总额（用户，分类）
                $sql = "select Userid, M_Name as username, sum(VGOLD) as valid_money, Active as game_code from ".DBPREFIX."web_report_data 
                where M_Date='".date('Y-m-d',$StartTime)."' and 
                checked = 1 and testflag=0 and `Cancel`=0 
                group by username,Active";
                $result=mysqli_query($conn, $sql);
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
//                    @error_log("计算有效下注金额:0 ".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg.log');
                }

//                @error_log(date('Y-m-d H:i:s')."----------------------计算返水有效投注金额 Start".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg.log');
                // valid_money 有效下注总额（用户，分类）
                $sql = "select Userid, M_Name as username, BetType_en, M_Rate, VGOLD, Active as game_code from ".DBPREFIX."web_report_data 
                where M_Date='".date('Y-m-d',$StartTime)."' and 
                checked = 1 and testflag=0 and `Cancel`=0 ";
                $result=mysqli_query($conn, $sql);
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
//                    @error_log("计算返水有效投注金额:0 ".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg.log');
                }

                // 按照用户名、游戏类别 归类下注金额、有效投注金额、有效返水投注金额
                foreach ($data_total as $k => $v){
                    $v['username'] = strtolower($v['username']);
                    foreach ($data_valid_money as $k1 => $v1){
                        $v1['username'] = strtolower($v1['username']);
                        if ($v['game_code'] == $v1['game_code'] && $v['username'] == $v1['username']){
                            $data_total[$k]['valid_money'] += $v1['valid_money'];
                        }
                    }

                    foreach ($data_rebate_valid_money as $k3 => $v3){
                        $v3['username'] = strtolower($v3['username']);
                        if ($v['game_code'] == $v3['game_code'] && $v['username'] == $v3['username']){
                            $data_total[$k]['valid_money_rebate'] += $v3['VGOLD'];
                        }
                    }

                }

                foreach ($data_total as $k =>$v){

                    $sql = "insert into ".DBPREFIX."web_report_history_report_data(userid, username, Agents, World, Corprator, Super, Admin, game_code,count_pay,total,valid_money,valid_money_rebate,user_win,M_date,bet_time,create_time)
                    VALUE( ".$v['Userid'].",'".$v['username']."','".trim($v['Agents'])."','".$v['World']."','".$v['Corprator']."','".$v['Super']."','".$v['Admin']."',
                    '".$v['game_code']."','".$v['count_pay']."','".$v['total']."','".$v['valid_money']."','".$v['valid_money_rebate']."','".$v['user_win']."','".$v['M_date']."','".$v['bet_time']."','".$v['create_time']."' ) ";

//                    @error_log(date('Y-m-d H:i:s')."----------------------记录报表 end".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg.log');
                    $result=mysqli_query($conn, $sql);
                    if(!$result) {
                        $result=mysqli_query($conn, "ROLLBACK");
                        die('计算报表数据失败33！ ' . mysqli_error($conn));
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
			echo date("Y-m-d", $StartTime-86400)."所有的计算完成！\n";
			break;
		}
		$StartTime = $end_time;
	}
}