<?php
/*
 * /account/take.php   提款订单提交
 * Bank_Address
 * Bank_Account
 * Bank_Name
 * Money
 * Withdrawal_Passwd
 * Alias
 */
include_once('../include/config.inc.php');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {

    $status = '401.1';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}
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
$userid=$_SESSION['userid'];
$address = $_REQUEST['Withdrawal_Passwd']; // 提款密码
$money=$_REQUEST["Money"]; // 提款金额
$bank=$_REQUEST["Bank_Name"];  // 银行名称
$bank_account=$_REQUEST["Bank_Account"]; // 银行账号
$bank_Address=$_REQUEST["Bank_Address"]; // 银行地址
$Usdt_Address=$_REQUEST["usdt_address"];    // 使用USDT提款
$adddate=date("Y-m-d");
$date=date("Y-m-d H:i:s");

if(!empty($usdt_rate)) {
    $rate = returnUsdtRate();
    $rate['usdt_amount'] = round($money/ $rate['withdrawals_usdt_rate'],2);
    $IntoBank = 'USDT提款'.'-'.$rate['withdrawals_usdt_rate'].'-'. $rate['usdt_amount'];
    $InType= " InType = '$IntoBank', PayName = 'USDT',";
}

$sql = "select UserName,Money,CurType,OpenType,Agents,World,Corprator,Super,Admin,Address,Bank_Account,Bank_Address,usdt_address,Alias,layer from ".DBPREFIX.MEMBERTABLE." where ID='$userid' and Status<2 for update ";

$result = mysqli_query($dbMasterLink,$sql);
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
$username=$row['UserName'];
$curtype=$row['CurType'];
$agents=$row['Agents'];
$world=$row['World'];
$corprator =$row['Corprator'];
$super=$row['Super'];
$admin=$row['Admin'];
$order_code = 'TK'.date("YmdHis",time()).$_SESSION['userid'].rand(100000,999999);

if ($address!=$row['Address'])
{
    $status = '401.2';
    $describe = '提款失败!原因:提款密码错误!';
    original_phone_request_response($status,$describe);

}
if ($money>$row['Money']){

    $status = '401.3';
    $describe = '提款失败!原因:提款金额大于账户资金.';
    original_phone_request_response($status,$describe);

}else{

    $beginFrom = mysqli_query($dbMasterLink,"start transaction");
    $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where Username='$username' for update");
    if( $beginFrom && $resultMem){
        $rowMem = mysqli_fetch_assoc($resultMem);
        if($money>$rowMem['Money']){
            $status = '401.4';
            $describe = '提款失败!原因:提款金额大于账户资金.';
            original_phone_request_response($status,$describe);

        }
        $moneyf = $rowMem['Money'];
        $currency_after = $rowMem['Money']-$money;
        $sql="insert into ".DBPREFIX."web_sys800_data set userid='".$rowMem['ID']."',Gold='$money',moneyf='$moneyf',currency_after='$currency_after',Name= '$alias', AddDate='$adddate',Type='T',Phone='".$_POST['Phone']."',UserName='$username',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='$curtype',Date='$date',Contact='$contact',Notes='$notes',Bank_Account='$bank_account',Bank_Address='$bank_Address',Bank='$bank',$InType Order_Code='$order_code'";
        if(mysqli_query($dbMasterLink,$sql)){
            $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money-$money,Credit=Credit-$money,Online=1,OnlineTime=now() where Username='$username'";

            //判断终端类型
            if ($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) { // 14 原生android，13 原生ios
                $playSource=$_REQUEST['appRefer'];
            }else{
                if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
                    $playSource=3;
                }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
                    $playSource=4;
                }else{
                    $playSource=5;
                }
            }
            $moneyLogRes=addAccountRecords(array($rowMem['ID'],$username,$rowMem['test_flag'],$rowMem['Money'],$money*-1,$currency_after,12,$playSource,mysqli_insert_id($dbMasterLink),"提款出账"));
            if(mysqli_query($dbMasterLink,$mysql)&&$moneyLogRes){
                mysqli_query($dbMasterLink,"COMMIT");
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");

                $status = '500.1';
                $describe = '提款失败!原因:数据操作错误.';
                original_phone_request_response($status,$describe);

            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $status = '500.2';
            $describe = '提款失败!原因:数据操作错误.';
            original_phone_request_response($status,$describe);

        }
    }else{
        mysqli_query($dbMasterLink,"ROLLBACK");
        $status = '500.3';
        $describe = '提款失败!原因:数据操作错误.';
        original_phone_request_response($status,$describe);

    }

    $status = '200';
    $describe = '提款已递交成功，我司财务会尽快为您处理，如长时间未收到您的款项，可以联系24小时在线客服咨询处理，谢谢！';
    original_phone_request_response($status,$describe);

}
