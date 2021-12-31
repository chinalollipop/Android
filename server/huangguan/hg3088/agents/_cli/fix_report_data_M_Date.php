<?php
/*	
 *
 * 	修复体育注单中的M_Date
 *
 *	auth: lincoin
 *	2018-06-06
 * */

if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");	
}

define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/config.inc.php";
$conn = $dbMasterLink;

//@error_log(date("Y-m-d H:i:s").'--------------- 修复体育注单 M_Date 开始'.PHP_EOL, 3, '/tmp/group/fixed_M_Date.log');

echo date("Y-m-d H:i:s")."\n";
echo "------------------------------------------ 修复体育注单 M_Date 开始 ------------------------------------------\n\n";
echo "------------------------------------------ 捞取错误注单开始 ------------------------------------------\n";
// 捞取错误注单，并更新每一笔注单 M_Date
$sql = "select ID,MID, Userid, M_result, checked from hgty78_web_report_data where M_Date = '0000-00-00' and Cancel = 0 ";
$res= mysqli_query($conn, $sql);
$cou = mysqli_num_rows($res);
if ($cou<=0){
    exit("---------------没有错误注单！");
}

echo "------------------------------------------ 总共条目：$cou ------------------------------------------\n";
echo "------------------------------------------ 捞取错误注单结束 ------------------------------------------\n\n";

$data = array();
while ($row = mysqli_fetch_assoc($res)){
    $data[]=$row;
}



foreach ($data as $k => $v){
    $sql = "select MID,M_Date from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID = '{$v['MID']}'";
    $res_sports= mysqli_query($conn, $sql);
    if(!$res_sports){
        echo "------------------------------------------ 查询 MID 报错 ------------------------------------\n";
        echo $sql."\n";
        continue;
    }
    $row_sports = mysqli_fetch_assoc($res_sports);
    if ( !isset($row_sports['MID']) || $row_sports['MID'] <= 0 || $row_sports['M_Date']<='0000-00-00'){
        echo "------------------------------------------ 盘口丢失 ------------------------------------------\n";
        continue;
    }

    echo "------------------------------------------ 修复注单:{$v['ID']}开始 ------------------------------------------\n";
    $sql = "update hgty78_web_report_data set M_Date='{$row_sports['M_Date']}',updateTime='".date('Y-m-d H:i:s',time())."' where ID = '{$v['ID']}' and MID ='{$v['MID']}'";
    $res= mysqli_query($conn, $sql);
    if (!$res){
        echo "------------------------------------------ 修复注单:{$v['ID']} 失败 ------------------------------------------\n";
        continue;
    }
    echo "------------------------------------------ 修复注单:{$v['ID']}结束 ------------------------------------------\n\n";
}

