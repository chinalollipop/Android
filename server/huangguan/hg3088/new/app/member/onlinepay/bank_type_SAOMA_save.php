<?php
/**
 * 支付宝，微信二维码。手工扫码付款
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
checkDepositOrder('old');
$uid=$_SESSION['uid'];
$langx=$_SESSION['langx'];
$payid = $_REQUEST['payid'];
$sSql = "select id,maxmoney,bank_name,bank_account,bank_addres,bankcode from `".DBPREFIX."gxfcy_bank_data` where FIND_IN_SET('{$_SESSION['pay_class']}',class) AND `status` = 1 and `id` = $payid ";
$aRes = mysqli_query($dbLink,$sSql);
$aRow=mysqli_fetch_array($aRes);
$iCou=mysqli_num_rows($aRes);
if($iCou == 0){
    echo "支付方式有误，请重新选择"; exit;
}

$aBankpay = $aRow;  //公司支付类型
$memo = $_REQUEST['memo'];
$bank_name = $_REQUEST['bank_user'];
$userid=$_SESSION['userid'];
$sql = "SELECT Money FROM `".DBPREFIX.MEMBERTABLE."` WHERE ID='$userid' ";
$result = mysqli_query($dbLink,$sql);
$cou = mysqli_num_rows($result);
if ($cou==0){
    echo "<script>alert('登录错误！请检查用户名或密码');history.back();</script>";
    exit;
}
$row = mysqli_fetch_assoc($result);

$cash = $_REQUEST['v_amount'];
$moneyf = sprintf("%01.2f",$row['Money']); // 用户充值前余额
$currency_after = $moneyf+$cash; // 用户充值后的余额
$myname = $_SESSION['Alias'];
$username = $_SESSION['UserName'];
$getday = $_REQUEST['cn_date'];
if (strlen($bank_name)>255){
    echo "<script>alert('银行名称异常请联系客服！');history.back();</script>";
}
if (strlen($memo)>255){
    echo "<script>alert('订单号异常请重新输入！');history.back();</script>";
}
if (!is_numeric($cash))
    echo "<script>alert('汇款金额只能输入数字！');history.back();</script>";
if ($cash>$aBankpay['maxmoney']){
    echo "<script>alert('汇款金额不能超过{$aBankpay['maxmoney']}！');history.back();</script>";
}
if ( $getday == "")
    echo "<script>alert('您的名字和汇款日期必须填写完整！');history.back();</script>";
$agents=$_SESSION['Agents'];
$world=$_SESSION['World'];
$corprator=$_SESSION['Corprator'];
$super=$_SESSION['Super'];
$admin=$_SESSION['Admin'];
$phone=$_SESSION['Phone'];
$contact="";
// $notes=$bank_name.'-'.$memo;
$notes= $memo;  // 交易订单号后四位
$bank = $aBankpay['bank_name'];
$bank_account=$aBankpay['bank_account'];
$bank_address=$aBankpay['bank_addres'];
$order_code = date("YmdHis",time()).rand(100000,999999);
$paytype = $aBankpay['id']; // 线下银行公司入款 支付宝微信扫码 id
$payname = $aBankpay['bankcode'];
$test_flag=$_SESSION['test_flag'];
$IntoBank = $bank_name.'-'.$notes ;
$sql = "insert into `".DBPREFIX."web_sys800_data` set DepositAccount='$IntoBank',userid='$userid',Checked=0,Payway='N',Gold='$cash',moneyf='$moneyf',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='$username',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$myname',Waterno='',Bank='$bank',Cancel='0',contact='$contact',Bank_Account='$bank_account',Bank_Address='$bank_address',Phone='$phone',Order_Code='$order_code',PayType='$paytype',PayName='$payname',test_flag='$test_flag'";
//echo $sql;echo '<br>';
mysqli_query($dbMasterLink,$sql) or die(mysqli_connect_error());

?>
<table width="100%" border="0" cellspacing="10" cellpadding="0">

    <tr>

        <td align="center"><font color="#999999">您好：您的汇款信息已提交成功,请等待工作人员的审核，并请于10分钟之内查询您的帐户余额。</font><a href="pay_type.php?uid=<?php echo ($uid)?>&langx=<?php echo ($langx)?>">返回继续操作</a></td>
        <td align="center"><font color="#999999"></font><a href="../onlinepay/record.php?uid=<?php echo ($uid)?>&username=<?php echo ($username)?>&langx=<?php echo ($langx)?>&thistype=S&date_start=<?php echo $m_date?>&date_end=<?php echo $m_date?>">查看记录</a></td>

    </tr>
</table>