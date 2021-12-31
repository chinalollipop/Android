<?php
/*	
 * 	每天定时将15天前的订单数据转移到历史订单表
 * 	1，	只支持cli模式下的运行。
 * 	2，	开启时间，美东时间每天的3,4,5点定时执行计划任务
 *	auth: lincoin
 *	2018-06-16
 * */


define("INCLUDE_DIR",  dirname(dirname(dirname(__FILE__))));
require INCLUDE_DIR."/common/config.php";

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
	$sql = "insert into ".DBPREFIX."ag_buyu_projects_history(`billno`,`username`,`prefix`,`roomid`,`betx`,`hunted`,`fishId`,`fishcost`,`src_amount`,`account`,`dst_amount`,`sceneid`,`billtime`,`cus_account`,`productid`,`jackpotcontribute`,`devicetype`) 
	select * from ".DBPREFIX."ag_buyu_projects where `billtime` < '{$stop_time}' ";
	$result=mysqli_query($conn, $sql);
	if(!$result) {
		$result=mysqli_query($conn, "ROLLBACK");
	    die('数据转移失败！ ' . mysqli_error($conn));
	}
	
	$sql = "delete from ".DBPREFIX."ag_buyu_projects where `billtime`<'{$stop_time}' ";
	$result=mysqli_query($conn, $sql);
	if(!$result) {
		$result=mysqli_query($conn, "ROLLBACK");
	    die('清理往期订单数据失败！' . mysqli_error($conn));
	}else {
		$result=mysqli_query($conn, "COMMIT");
		exit("历史订单数据转移成功！");
	}
}