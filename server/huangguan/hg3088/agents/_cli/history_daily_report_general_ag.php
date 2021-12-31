<?php
/*	
 * 
 * 	每天定时自动计算AG报表的代码，生成历史报表（用途：1、方便给会员返水使用，2、后台报表查询）
 * 	1，	只支持cli模式下的运行。
 * 	2，	示例URL:
 *	 			php history_daily_report_general_ag.php 									//定时任务，生成昨日的数据
 *			或	
 *	 			php history_daily_report_general_ag.php old								//重新批量生成数据
 *			或
 *	 			php history_daily_report_general_ag.php 2016-10-06						//重新生成某一个天的数据
 *			或
 *	 			php history_daily_report_general_ag.php 2016-10-06 2016-10-07 1	//重新生成从某一个天到某一天的数据
 * 	3，	开启时间，每天的美东时间3,4,5点执行计划任务
 * */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/config.inc.php";
$conn = $dbMasterLink;

//只在CLI命令下有效
if (php_sapi_name() == "cli") {


	//只设置参数a old，则从9.1号开始，将今天之前的所有数据全部重新生成
	if(isset($argv[1]) && $argv[1] == 'old') {

	    $start_time = mktime(0, 0, 0, 9, 1, 2017);		//从2016.9.1号可以计算数据
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
		
		//如果设置了参数 c 1，则置gxfcy_history_bill_report_flag标示位为2，代表此数据已经重新生成（主要是为了包含香港六合彩的数据）
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
	$sql = " select * from ".DBPREFIX."ag_projects_history_report_flag where order_date >= '".date("Y-m-d",$StartTime)."' and order_date < '".date("Y-m-d",$stop_time)."' ";
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
			$sql = " select * from ".DBPREFIX."ag_projects_history_report_flag where order_date >= '".date("Y-m-d",$StartTime)."' and order_date < '".date("Y-m-d",$stop_time)."' ";
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
	$sql = " DELETE from ".DBPREFIX."ag_projects_history_report where bet_time >= '".date("Y-m-d",$StartTime)."' and bet_time < '".date("Y-m-d",$stop_time)."'";
	$result=mysqli_query($conn, $sql);
	$sql = " DELETE from ".DBPREFIX."ag_projects_history_report_flag where order_date >= '".date("Y-m-d",$StartTime)."' and order_date < '".date("Y-m-d",$stop_time)."' ";
	$result=mysqli_query($conn, $sql);
	
	for($i=1; $i<=50; $i++) {

        $end_time = $StartTime + 3600*24;

		$result=mysqli_query($conn, "START TRANSACTION");
		if (!$result) {
		    echo('事务开启失败！ ' . mysqli_error($conn));
		    continue;
		}

		if($end_time <= $stop_time) {

            $sql = "insert into ".DBPREFIX."ag_projects_history_report(`userid`, `username`, Agents, `game_code`, `count_pay`, `total`, `valid_money`, `bonus`, `M_Date`, `bet_time`, `create_time`, `profit`) 
select userid, username, Agents, `type` as game_code, sum(1) as count_pay,sum(amount) as total, sum(valid_money) as valid_money, sum(bonus) as bonus, bettime as M_Date, bettime, now() as create_time, sum(profit) as profit from ".DBPREFIX."ag_projects
where bettime>='".date('Y-m-d',$StartTime)."' and bettime<'".date('Y-m-d',$end_time)."' group by type,username";
//			echo $sql."\n"; die;
            $result=mysqli_query($conn, $sql);
			if(!$result) {
				$result=mysqli_query($conn, "ROLLBACK");
			    die('计算报表数据失败！ ' . mysqli_error($conn));
			}

			if($reGeneral) {
				$sql = " insert into ".DBPREFIX."ag_projects_history_report_flag(order_date, flag) value('".date("Y-m-d",$StartTime)."', 2) ";
			}else {
				$sql = " insert into ".DBPREFIX."ag_projects_history_report_flag(order_date, flag) value('".date("Y-m-d",$StartTime)."', 1) ";
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
