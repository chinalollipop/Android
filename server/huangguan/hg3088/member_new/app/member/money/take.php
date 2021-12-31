<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

/*
 * // 安全考虑，暴露客户信息，不要传输 真实姓名、银行卡号
if (empty($_REQUEST["Alias"]) || strlen($_REQUEST["Alias"])>50){
    $status = '401.5';
    $describe = '提款失败!原因:真实姓名异常.';
    original_phone_request_response($status,$describe);
}
if (empty($_REQUEST["Bank_Account"]) || strlen($_REQUEST["Bank_Account"])>30){
    $status = '401.6';
    $describe = '提款失败!原因:银行账号异常.';
    original_phone_request_response($status,$describe);
}
if (empty($_REQUEST["Bank_Address"]) || strlen($_REQUEST["Bank_Address"])>255){
    $status = '401.7';
    $describe = '提款失败!原因:银行地址异常.';
    original_phone_request_response($status,$describe);
}*/

$usdt_rate=isset($_REQUEST["usdt_rate"])?$_REQUEST["usdt_rate"]:'';
$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$user=$_REQUEST["user"];
$phone=$_REQUEST["Phone"];
$address = $_POST['address1'].$_POST['address2'].$_POST['address3'].$_POST['address4'].$_POST['address5'].$_POST['address6'];
$notes=$_REQUEST["Notes"];
$money=$_REQUEST["Money"];
$bank=$_REQUEST["Bank_Name"];
$key=$_REQUEST["Key"];
$adddate=date("Y-m-d");
$date=date("Y-m-d H:i:s");

if ($user==''){
    $sql = "select CurType,Address,Money,ID,Bank_Account,Bank_Address,usdt_address,Alias,layer from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
}else{
	$sql = "select CurType,Address,Money,ID,Bank_Account,Bank_Address,usdt_address,Alias,layer from ".DBPREFIX.MEMBERTABLE." where UserName='$user'";
}
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$sUserlayer = $row['layer'];
// 检查当前会员是否设置不准操作额度分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
$layerId=3;
if ($sUserlayer==$layerId){
    $layer = getUserLayerById($layerId);
    if ($layer['status']==1) {
        echo "<script language=javascript>alert('账号分层异常，请联系我们在线客服'); history.go('-1');</script>";
        exit;
    }
}

$alias=$row["Alias"]; // 提款人姓名
$bank_account=$row["Bank_Account"];
$bank_Address=$row["Bank_Address"];
$Usdt_Address=$row["usdt_address"];    // 使用USDT提款
$username=$_SESSION['UserName'];
$curtype=$row['CurType'];
$agents=$_SESSION['Agents'];
$world=$_SESSION['World'];
$corprator =$_SESSION['Corprator'];
$super=$_SESSION['Super'];
$admin=$_SESSION['Admin'];
$contact="";
$order_code = 'TK'.date("YmdHis",time()).$_SESSION['userid'].rand(100000,999999);
//echo $address."<br/>";
//echo $row['Address'];
//die();
if ($address!=$row['Address'])
{
	echo "<Script language=javascript>alert('提款失败!原因:提款密码错误!');history.back();</script>";
	die();
}
if(!empty($usdt_rate)) { // usdt 提款
    $rate = returnUsdtRate();
    $rate['usdt_amount'] = round($money/ $rate['withdrawals_usdt_rate'],2);
    $IntoBank = 'USDT提款'.'-'.$rate['withdrawals_usdt_rate'].'-'. $rate['usdt_amount'];
    $InType= " InType = '$IntoBank', PayName = 'USDT',";
}
if ($money>$row['Money']){
	echo "<Script language=javascript>alert('提款失败!原因:提款金额大于账户资金.');history.back();</script>";
	die();
}else{

    if(!preg_match("/^[1-9][0-9]*$/",$money)){
        echo "<Script language=javascript>alert('提款失败!原因:提款金额只支持正整数.');history.back();</script>";
        die();
    }

	if($key=="Y"){
        //$moneyf = $row['Money']; // 用户充值前余额 	^ 0 ^ 这里不是提款么？为什么写充值？
        //$currency_after = $row['Money']-$money; // 用户充值后的余额	^ 0 ^ 这里不是提款么？为什么写充值？
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");
        $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where Username='$username' for update");
		if( $beginFrom && $resultMem){
			$rowMem = mysqli_fetch_assoc($resultMem);
			if($money>$rowMem['Money']){
				echo "<Script language=javascript>alert('提款失败!原因:提款金额大于账户资金.');history.back();</script>";
				die();
			}
			$moneyf = $rowMem['Money'];
			$currency_after = $rowMem['Money']-$money;
			$sql="insert into ".DBPREFIX."web_sys800_data set userid='".$rowMem['ID']."',Gold='$money',moneyf='$moneyf',currency_after='$currency_after',Name= '$alias', AddDate='$adddate',Type='T',Phone='".$_POST['Phone']."',UserName='$username',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='$curtype',Date='$date',Contact='$contact',Notes='$notes',Bank_Account='$bank_account',Bank_Address='$bank_Address',Bank='$bank',$InType Order_Code='$order_code'";
			if(mysqli_query($dbMasterLink,$sql)){
				$mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money-$money,Credit=Credit-$money,Online=1,OnlineTime=now() where Username='$username'";
				$moneyLogRes=addAccountRecords(array($rowMem['ID'],$username,$rowMem['test_flag'],$rowMem['Money'],$money*-1,$currency_after,12,1,mysqli_insert_id($dbMasterLink),"提款出账"));
				if(mysqli_query($dbMasterLink,$mysql)&&$moneyLogRes){
					mysqli_query($dbMasterLink,"COMMIT");
				}else{
					mysqli_query($dbMasterLink,"ROLLBACK");
					echo "<Script language=javascript>alert('提款失败!原因:数据操作错误.');history.back();</script>";
				}
			}else{
				mysqli_query($dbMasterLink,"ROLLBACK");
				echo "<Script language=javascript>alert('提款失败!原因:数据操作错误.');history.back();</script>";
			}
		}else{
			mysqli_query($dbMasterLink,"ROLLBACK");
			echo "<Script language=javascript>alert('提款失败!原因:数据操作错误.');history.back();</script>";
		}

        echo "<Script language=javascript>self.location.href='../onlinepay/record.php?uid=".$uid."&langx=".$langx."&username=".$username."&thistype=T&date_start=".$adddate."&date_end=".$adddate."';</script>";
	}
}
?>
