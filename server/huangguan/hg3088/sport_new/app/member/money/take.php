<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$usdt_rate=isset($_REQUEST["usdt_rate"])?$_REQUEST["usdt_rate"]:'';
$uid= $_SESSION['Oid'];
$langx=$_SESSION["langx"];
$user=$_REQUEST["user"];
$phone=$_REQUEST["Phone"];
//$address = $_POST['address1'].$_POST['address2'].$_POST['address3'].$_POST['address4'].$_POST['address5'].$_POST['address6'];
$address = $_POST['withdrawal_passwd'] ;
$notes=$_REQUEST["Notes"];
$money=$_REQUEST["Money"];
$bank=$_REQUEST["Bank_Name"];

$adddate=date("Y-m-d");
$date=date("Y-m-d H:i:s");
if(!isset($uid) || $uid == ""){
    $status = '400.01';
    $describe = '你已退出登录，请重新登录!';
    original_phone_request_response($status,$describe);
}
/*
 * // 安全考虑，暴露客户信息，不要传输 真实姓名、银行卡号
if (empty($alias) || strlen($alias)>50){
    $status = '400.1';
    $describe = '提款失败!原因:真实姓名异常.';
    original_phone_request_response($status,$describe);
}
if (empty($bank_account) || strlen($bank_account)>30){
    $status = '400.2';
    $describe = '提款失败!原因:银行账号异常.';
    original_phone_request_response($status,$describe);
}
if (empty($bank_Address) || strlen($bank_Address)>255){
    $status = '400.3';
    $describe = '提款失败!原因:银行地址异常.';
    original_phone_request_response($status,$describe);
}*/


if(!empty($usdt_rate)) {
    $rate = returnUsdtRate();
    $rate['usdt_amount'] = round($money/ $rate['withdrawals_usdt_rate'],2);
    $IntoBank = 'USDT提款'.'-'.$rate['withdrawals_usdt_rate'].'-'. $rate['usdt_amount'];
    $InType= " InType = '$IntoBank', PayName = 'USDT',";
}

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
        $status = '401.2';
        $describe = '账号分层异常，请联系我们在线客服';
        original_phone_request_response($status,$describe);
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

if ($address!=$row['Address']) {

    $status = '400.4';
    $describe = '提款失败!原因:提款密码错误!';
    original_phone_request_response($status,$describe);
}
if ($money>$row['Money']){
    $status = '400.5';
    $describe = '提款失败!原因:提款金额大于账户资金!';
    original_phone_request_response($status,$describe);
}else{

    if(!preg_match("/^[1-9][0-9]*$/",$money)){
        $status = '400.6';
        $describe = '提款失败!原因:提款金额只支持正整数!';
        original_phone_request_response($status,$describe);
    }

        //$moneyf = $row['Money']; // 用户充值前余额 	^ 0 ^ 这里不是提款么？为什么写充值？
        //$currency_after = $row['Money']-$money; // 用户充值后的余额	^ 0 ^ 这里不是提款么？为什么写充值？
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");
        $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where Username='$username' for update");
		if( $beginFrom && $resultMem){
			$rowMem = mysqli_fetch_assoc($resultMem);
			if($money>$rowMem['Money']){
                $status = '400.6';
                $describe = '提款失败!原因:提款金额大于账户资金!';
                original_phone_request_response($status,$describe);
			}
			$moneyf = $rowMem['Money'];
			$currency_after = $rowMem['Money']-$money;
			$sql="insert into ".DBPREFIX."web_sys800_data set userid='".$rowMem['ID']."',Gold='$money',moneyf='$moneyf',currency_after='$currency_after',Name= '$alias', AddDate='$adddate',Type='T',Phone='".$_POST['Phone']."',UserName='$username',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='$curtype',Date='$date',Contact='$contact',Notes='$notes',Bank_Account='$bank_account',Bank_Address='$bank_Address',Bank='$bank',$InType Order_Code='$order_code'";
			if(mysqli_query($dbMasterLink,$sql)){
				$mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money-$money,Credit=Credit-$money,Online=1,OnlineTime=now() where Username='$username'";
				$moneyLogRes=addAccountRecords(array($rowMem['ID'],$username,$rowMem['test_flag'],$rowMem['Money'],$money*-1,$currency_after,12,22,mysqli_insert_id($dbMasterLink),"提款出账"));
				if(mysqli_query($dbMasterLink,$mysql)&&$moneyLogRes){
					mysqli_query($dbMasterLink,"COMMIT");
				}else{
					mysqli_query($dbMasterLink,"ROLLBACK");

                    $status = '400.7';
                    $describe = '款失败!原因:数据操作错误!';
                    original_phone_request_response($status,$describe);
				}
			}else{
				mysqli_query($dbMasterLink,"ROLLBACK");

                $status = '400.8';
                $describe = '提款失败!原因:数据操作错误!';
                original_phone_request_response($status,$describe);
			}
		}else{
			mysqli_query($dbMasterLink,"ROLLBACK");

            $status = '400.9';
            $describe = '提款失败!原因:数据操作错误!';
            original_phone_request_response($status,$describe);
		}

        $status = '200';
        $describe = '提款成功!';
        original_phone_request_response($status,$describe);

}
?>
