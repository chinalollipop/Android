<?php
/* *
 * @Description 智融宝API支付B2C在线支付接口范例
 **/
header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST[$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/zrbcallback.log');

/*
 * $_REQUEST = array{
  "p1_MerId"=> "1571"
  "r0_Cmd"=> "Buy"
  "r1_Code"=>"1"
  "r2_TrxId"=> "201809141040572071"
  "r3_Amt"=> "10.00"
  "r4_Cur"=>"RMB"
  "r5_Pid"=> "productname"
  "r6_Order"=>"zrb201809132240578143076360"
  "r7_Uid"=> ""
  "r8_MP"=>"xiaoji|81|4880|ICBC|2"
  "r9_BType"=> "2"
  "rp_PayDate"=>"2018/9/14 11:22:38"
  "hmac"=> "552e215d8f5e73119f849cf6aaf26ede"
}*/

include_once "../class/config.inc.php";
include_once "../model/Pay.php";
include_once "../model/Userlock.php";
include_once "../class/zrb/payCommon.php";

$MemberID = $_REQUEST["p1_MerId"]; //商户号
$tradeNo = $_REQUEST["r6_Order"]; //商户订单号
$orderAmount = $_REQUEST["r3_Amt"];  //支付金额
$extra_return_param = $_REQUEST["r8_MP"]; //商户扩展信息

// 返回数组   xiaoji|81|4880|ICBC|2  回传参数  会员名称|网银配置渠道id|用户id|支付方式银行代码| 支付类型2为银行卡支付，4微信支付，5为支付宝,6为QQ扫码
$aData = explode('|',$extra_return_param);

// 当前支付渠道
$sSql = "SELECT *  FROM `".DBPREFIX."gxfcy_pay` WHERE `id` =".$aData[1]." AND `status` = 1 limit 1";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
$aThirdPayRow = mysqli_fetch_assoc($oRes);
if($iCou==0){
    echo "<script type='text/javascript'>alert('渠道信息错误,未找到相关数据！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

// 当前登录账号
$sql = "select ID from ".DBPREFIX.MEMBERTABLE." where ID='$aData[2]' and Status<2";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    echo "<script type='text/javascript'>alert('该会员账户不存在！');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;
}

#	只有支付成功时API支付才会通知商户.
##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

$p1_MerId = $MemberID; //商户号
$merchantKey = $aThirdPayRow['business_pwd']; //商户密钥

#	判断返回签名是否正确（True/False）
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac,$p1_MerId,$merchantKey);
#	以上代码和变量不需要修改.
//@error_log('bRet:'.$bRet.PHP_EOL, 3, '/tmp/aaa.log');
#	校验码正确.
if($bRet){
    if($r1_Code=="1"){  //固定值 “1”, 代表支付成功
        #	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
        #	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.
        #   如果需要应答机制则必须回写流,以success开头,大小写不敏感.
        if($r9_BType=="1" || $r9_BType=="2"){ //为“1”： 浏览器重定向  // 为“2”： 服务器点对点通讯*
            $oUserLock = new Userlock_model($dbMasterLink);
            $userInfo=$oUserLock->lock($row['ID']);
            $userInfoArr = json_decode($userInfo,true);
            if( is_array($userInfoArr) && count($userInfoArr)>0){
                $oPayin = new Pay_model($dbMasterLink);
                //校验通过开始处理订单,$row当前会员信息, $aData回传参数 (会员名称|渠道id|用户id|支付方式代码),$MemberID商户号,$tradeNo支付平台订单号
                $result = $oPayin->UserPayin($userInfoArr, $aData, $MemberID, $tradeNo, $orderAmount, $aThirdPayRow);
                $oUserLock->commit_lock();
                echo "<script type='text/javascript'>alert('支付成功！');window.opener=null;window.open('', '_self');window.close();</script>";
                echo "success";
                exit;
            }else{
                echo "<script type='text/javascript'>alert('交易失败,数据操作错误!');window.opener=null;window.open('', '_self');window.close();</script>";
                exit;
            }
        }
    }

}else{
    //echo "交易信息被篡改";
    echo "<script type='text/javascript'>alert('智融宝通知验证数据错误!');window.opener=null;window.open('', '_self');window.close();</script>";
    exit;

}