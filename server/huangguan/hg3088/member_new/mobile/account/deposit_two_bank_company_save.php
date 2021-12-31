<?php
/**
 * 公司卡号入款记录入库
 *
 */

include_once('../include/config.inc.php');


$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$payid = $_REQUEST['payid']; // 银行 id
$cardName=$_REQUEST['v_Name']; // 持卡人姓名
$memo = $_REQUEST['memo']; // 备注
$InType = $_REQUEST['InType']; // 汇款方式
$money = $_REQUEST['v_amount']; // 存款金额
if ($money<100){
    $status = '401.7';
    $describe = '汇款金额不能小于100元';
    original_phone_request_response($status,$describe);
}

$cn_date = $_REQUEST['cn_date']; // 存款时间
$IntoBank = isset($_REQUEST['IntoBank'])?$_REQUEST['IntoBank']:''; // 汇款银行

// 默认查询当天的数据
$m_date=date('Y-m-d');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
        $status = '401.1';
        $describe = '请重新登录!';
        original_phone_request_response($status,$describe);

}
checkDepositOrder();
if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) { // 客户端汇款时间减12h， 和PC m版统一
    $cn_date = date('Y-m-d H:i:s' , strtotime($cn_date) - 12*60*60);
}

$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式
$sSql = "select `bank_name`,`bank_account`,`bank_addres`,`bankcode` from `".DBPREFIX."gxfcy_bank_data` where FIND_IN_SET('{$_SESSION['pay_class']}',class) AND `status` = 1 AND `id` = $payid  AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ";
$aRes = mysqli_query($dbLink,$sSql);
$aRow=mysqli_fetch_array($aRes);
$iCou=mysqli_num_rows($aRes);
if($iCou == 0){
        $status = '401.2';
        $describe = '支付方式有误，请重新选择~！';
        original_phone_request_response($status,$describe);

}

$aBankpay = $aRow;
$userid=$_SESSION['userid'];
// 重复提交检查，10分钟内提交过未审核的，不允许重复提交
//$aRes = mysqli_query($dbMasterLink,"select ID from ".DBPREFIX."web_sys800_data where userid = $userid and Checked=0 and `Date`='$cn_date' ");
//$iCou=mysqli_num_rows($aRes);
//if($iCou > 0){
//    $status = '401.3';
//    $describe = '您有待审核的申请,请您等待管理员审核后,再提交';
//    original_phone_request_response($status,$describe);
//}

$sql = "SELECT Money FROM `".DBPREFIX.MEMBERTABLE."` WHERE ID='$userid' for update";
$result = mysqli_query($dbMasterLink,$sql);
$row = mysqli_fetch_assoc($result);

$cash = $money;
$moneyf = $row['Money']; // 用户充值前余额
$currency_after = $row['Money']+$money; // 用户充值后的余额
$realName = $_SESSION['Alias']; // 用户的真实姓名
$username = $_SESSION['UserName'];
$getday = $cn_date;
if (!is_numeric($cash)){

        $status = '401.5';
        $describe = '汇款金额只能输入数字！';
        original_phone_request_response($status,$describe);

}
if ($cardName == "" || $getday == ""){

        $status = '401.6';
        $describe = '您的名字和汇款日期必须填写完整！';
        original_phone_request_response($status,$describe);
}

$test_flag=$_SESSION['test_flag'];
$agents=$_SESSION['Agents'];
$world=$_SESSION['World'];
$corprator=$_SESSION['Corprator'];
$super=$_SESSION['Super'];
$admin=$_SESSION['Admin'];
$phone=$_SESSION['Phone'];
$contact="";
//$notes=$InType.'-'.$memo;
$notes=$memo;
$bank = $aBankpay['bank_name'];
$bank_account=$aBankpay['bank_account'];
$bank_address=$aBankpay['bank_addres'];
$order_code = date("YmdHis",time()).rand(100000,999999);
$paytype = $payid; // 公司入款汇款银行id
$payname = $aBankpay['bankcode'];
$sql = "insert into `".DBPREFIX."web_sys800_data` set DepositAccount='$IntoBank',InType='$InType',userid='$userid',Checked=0,Payway='N',Gold='$cash',moneyf='$moneyf',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='$username',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',CardName='$cardName',Name='$realName',Waterno='',Bank='$bank',Cancel='0',contact='$contact',notes='$notes',Bank_Account='$bank_account',Bank_Address='$bank_address',Phone='$phone',Order_Code='$order_code',PayType='$paytype',PayName='$payname',test_flag='$test_flag'";
$id = mysqli_query($dbMasterLink,$sql);

if ($id){

        $status = '200';
        $describe = '您好：您的汇款信息已提交成功,请等待工作人员的审核，并请于10分钟之内查询您的帐户余额。';
        original_phone_request_response($status,$describe);


}else{

        $status = '500';
        $describe = '入款申请异常，请重试';
        original_phone_request_response($status,$describe);

}
