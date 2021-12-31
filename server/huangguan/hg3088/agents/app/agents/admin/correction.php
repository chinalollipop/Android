<?php 
session_start();
include ("../include/address.mem.php");
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$loginname = $_SESSION['UserName'];
$lv =  $_SESSION['admin_level'] ;
$account = $_POST['account'] ;

/**
 * 处理误差
 * @param string current 当前修正账号id
 * @param string amount 目前额度
 * @param string difference 误差
 *
 *  1. 目前额度为负数的正常显示，误差要显示出来
 *  2. 取款总数 已经审核的 ， 正在审核中的 ，审核失败的 要区分
 *  目前额度    误差
 *  1    -0.75 修正前(负数是用户差我们)
 *	0     0.25  修正后(正数是我们差客户)
 *	1    -0.25 修正后
 *	0     0.75  修正前
 */

if (($_POST['difference']<=-1 || $_POST['difference']>=1) && $_POST['difference'] && $_POST['currentid'] && $_POST['amount']) {

	 if($_POST['difference'] >= 1) {
		$amount = round($_POST['amount'],2) + $_POST['difference'];
	} elseif($_POST['difference'] <= -1) { // 加负数就是减去
		$amount = round($_POST['amount'],2) + $_POST['difference'];
	}
	$currentid = intval($_POST['currentid']);
	$beginFrom = mysqli_query($dbMasterLink,"start transaction");
	if($beginFrom){
		$resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID={$currentid} for update");
		if($resultMem){
			$rowMem = mysqli_fetch_assoc($resultMem);
			$result = mysqli_query($dbMasterLink,"update ".DBPREFIX.MEMBERTABLE." set Money='$amount' where ID=".$currentid);
			if($result) {
				$moneyLogRes=addAccountRecords(array($currentid,$account,$rowMem['test_flag'],$rowMem['Money'],$_POST['difference'],$amount,25,6,$currentid,"[游戏管理-检查额度-修正]操作人:$loginname"));
				if($moneyLogRes){
					mysqli_query($dbMasterLink,"COMMIT");	
					$status = array('status' => '1',  'info' => '修正成功');
			        /* 插入系统日志 */
			        $loginfo = $loginname.' 在额度检查中<font class="red"> 修正了 </font> 会员帐号 <font class="green">'.$account.'</font>, 金额为 <font class="red">'.$_POST['amount'].'</font> ,id 为 <font class="green">'.$currentid.'</font> ' ;
			        innsertSystemLog($loginname,$lv,$loginfo);		
				}else{
					mysqli_query($dbMasterLink,"ROLLBACK"); 
					$status = array('status' => '0',  'info' => '用户资金账变日志写入失败');
				}
			} else {
				mysqli_query($dbMasterLink,"ROLLBACK"); 
				$status = array('status' => '0',  'info' => '修正失败');
			}		
		}else{
			mysqli_query($dbMasterLink,"ROLLBACK"); 
			$status = array('status' => '0',  'info' => '锁定用户资金账户失败');		
		}
	}else{
		mysqli_query($dbMasterLink,"ROLLBACK"); 
		$status = array('status' => '0',  'info' => '事务开启失败');		
	}
} else {
	$status = array('status' => '0',  'info' => '请检查参数！');
}

echo json_encode($status);

?>