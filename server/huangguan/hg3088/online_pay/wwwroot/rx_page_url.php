<?php

header ( 'Content-Type: text/html; charset=utf-8' );
foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}
@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/rxback_page.log');

include ("../class/config.inc.php");
$MemberID=$_REQUEST['MemberID'];//商户号
$TerminalID =$_REQUEST['TerminalID'];//商户终端号
$TransID =$_REQUEST['TransID'];//流水号
$Result=$_REQUEST['Result'];//支付结果
$ResultDesc=$_REQUEST['ResultDesc'];//支付结果描述
$FactMoney=$_REQUEST['FactMoney'];//实际成功金额
$AdditionalInfo=$_REQUEST['AdditionalInfo'];//订单附加消息   hg308877|13|0a74e7362d0192023a7bra4|57   会员名称|渠道id|用户Oid|支付方式代码
$SuccTime=$_REQUEST['SuccTime'];//支付完成时间
$Md5Sign=$_REQUEST['Md5Sign'];//md5签名

$Md5key = $aRow['business_pwd'];
$MARK = "~|~";
//MD5签名格式
$WaitSign=md5('MemberID='.$MemberID.$MARK.'TerminalID='.$TerminalID.$MARK.'TransID='.$TransID.$MARK.'Result='.$Result.$MARK.'ResultDesc='.$ResultDesc.$MARK.'FactMoney='.$FactMoney.$MARK.'AdditionalInfo='.$AdditionalInfo.$MARK.'SuccTime='.$SuccTime.$MARK.'Md5Sign='.$Md5key);
if ($Md5Sign == $WaitSign) {
    if($Result == 1) {
        echo "<script type='text/javascript'>alert('支付成功！');window.opener=null;window.open('', '_self');window.close();</script>";
    }else {
        echo "<script type='text/javascript'>alert('支付失败！');window.opener=null;window.open('', '_self');window.close();</script>";
    }
} else {
    echo "<script type='text/javascript'>alert('不合法数据！');window.opener=null;window.open('', '_self');window.close();</script>";
}
