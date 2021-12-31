<?php
/*	
 * 	每天定时将15天前的订单数据转移到历史订单表
 * 	1，	只支持cli模式下的运行。
 * 	2，	开启时间，美东时间每天的3,4,5点定时执行计划任务
 *	auth: lincoin
 *	2018-06-16
 * */


define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/config.inc.php";

//只在CLI命令下有效
if (php_sapi_name() == "cli") {
    $now=time();
    $Last = $now - 15*24*60*60; // 15天前
    $stop_time = date("Y-m-d", $Last);

	transfer_history_bill($stop_time, $dbMasterLink);
}

/**
 * 
 * 每周一将上上周的订单数据转移到历史订单数据里面
 * @param date $Endtime
 * @param object $conn
 */
function transfer_history_bill($stop_time, $conn){
	
	$result=array();
	
	$result=mysqli_query($conn, "START TRANSACTION");
	if (!$result) {
	    die('事务开启失败！ ' . mysqli_error($conn));
	}

	// ag注单表中的数据都是已结算完毕的数据
	$sql = "insert into ".DBPREFIX."ag_projects_history(`projectid`,`userid`,`username`,`Agents`,`World`,`Corprator`,`Super`,`Admin`,`platform`,`amount`,`valid_money`,`bonus`,`iswin`,`bettime`,`createtime`,`slottype`,`return_point`,
	`gamename`,`originalbetsid`,`gamecode`,`playType`,`thirdprojectid`,`type`,`mainbillno`,`profit`,`devicetype`,`flag`) select * from ".DBPREFIX."ag_projects where bettime < '{$stop_time}' and flag=1 ";
	$result=mysqli_query($conn, $sql);
	if(!$result) {
		$result=mysqli_query($conn, "ROLLBACK");
	    die('数据转移失败！ ' . mysqli_error($conn));
	}
	
	$sql = "delete from ".DBPREFIX."ag_projects where bettime<'{$stop_time}' and flag=1 ";
	$result=mysqli_query($conn, $sql);
	if(!$result) {
		$result=mysqli_query($conn, "ROLLBACK");
	    die('清理往期订单数据失败！' . mysqli_error($conn));
	}else {
		$result=mysqli_query($conn, "COMMIT");
		exit("历史订单数据转移成功！");
	}
}