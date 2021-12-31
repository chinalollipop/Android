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

	$sql = "insert into ".DBPREFIX."web_report_history_data(`ID`,`MID`,`Userid`,`testflag`,`Active`,`orderNo`,`LineType`,`Mtype`,`Pay_Type`,`M_Date`,`BetTime`,`BetScore`,`CurType`,`Middle`,`Middle_tw`,`Middle_en`,`BetType`,`BetType_tw`,
	`BetType_en`,`M_Place`,`M_Rate`,`M_Name`,`Gwin`,`Glost`,`VGOLD`,`M_Result`,`A_Result`,`B_Result`,`C_Result`,`D_Result`,`T_Result`,`OpenType`,`OddsType`,`ShowType`,`Cancel`,`Agents`,`agent_url`,`World`,`Corprator`,`Super`,
	`Admin`,`A_Point`,`B_Point`,`C_Point`,`D_Point`,`BetIP`,`Type`,`Ptype`,`Gtype`,`current`,`ratio`,`betid`,`MB_MID`,`TG_MID`,`MB_ball`,`TG_ball`,`Edit`,`Orderby`,`Checked`,`sendAwardTime`,
	`sendAwardIsAuto`,`sendAwardName`,`updateTime`,`Soccer`,`Confirmed`,`Danger`,`playSource`,`QQ83068506`) select * from ".DBPREFIX."web_report_data where M_Date < '{$stop_time}' and Checked=1";
	$result=mysqli_query($conn, $sql);
	if(!$result) {
		$result=mysqli_query($conn, "ROLLBACK");
	    die('数据转移失败！ ' . mysqli_error($conn));
	}
	
	$sql = "delete from ".DBPREFIX."web_report_data where M_Date<'{$stop_time}' and Checked=1";
	$result=mysqli_query($conn, $sql);
	if(!$result) {
		$result=mysqli_query($conn, "ROLLBACK");
	    die('清理往期订单数据失败！' . mysqli_error($conn));
	}else {
		$result=mysqli_query($conn, "COMMIT");
		exit("历史订单数据转移成功！");
	}
}