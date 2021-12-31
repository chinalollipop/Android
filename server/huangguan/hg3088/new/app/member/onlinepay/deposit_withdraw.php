<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$mtype=$_REQUEST['mtype'];
$langx=$_SESSION['langx'];

// 默认查询当天的数据
$m_date=date('Y-m-d');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

$userid = $_SESSION['userid'];
$member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where ID='$userid'";
$member_query = mysqli_query($dbLink,$member_sql);
$memberinfo = mysqli_fetch_assoc($member_query);
$sUserlayer = $memberinfo['layer'];
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

// 判断是否已设置真实姓名，若设置直接跳转提款页面
$sql = "SELECT `Alias` FROM ".DBPREFIX.MEMBERTABLE." WHERE Oid='$uid' AND Status = 0";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
if(!$row['Alias']){
    echo "<script language=javascript>alert('请先设置您的真实姓名！'); location.href = '../money/set_realname.php?uid=".$uid."&langx=".$langx."';</script>";
    exit;
}
$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式
// 显示支付通道的分类
$sWhere ="AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$sWhere .= " AND `bankcode` = 'KSCZ' AND `status` = 1";
$sWhere .= " AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ";
// 非第三方通道分类显示 查询快速充值链接 后台线下银行配置
$sSql = "SELECT id,bankcode,bank_name,issaoma,photo_name FROM `".DBPREFIX."gxfcy_bank_data` WHERE 1 ".$sWhere;
$oRes = mysqli_query($dbLink,$sSql);
$aRow = mysqli_fetch_assoc($oRes);

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>paycontroller</title>
    <link rel="stylesheet" type="text/css" href="../../../style/depositwithdraw.css?v=<?php echo AUTOVER; ?>">
</head>
<body style="background-color: rgb(237, 237, 237);">

<div class="mw">

    <div class="mc-con3">
        <!--右側選單　menu--><!--右側填寫內容頁 member center right center-->
        <div class="mc-rtct" id="div_Bg">
            <div class="deposit-ui">
                <?php
                // 7557显示快速充值 98985不显示
                if(DEPOSIT_WITHDRAW_SWITCH && !empty($aRow)) {
                    ?>
                    <div id="d4">
                        <h1>快速充值</h1>
                        <p>最新支付通道，支持手机与电脑存款，更多支付通道支付宝/微信扫码/网银汇款/等，点击会弹出一个网址，请认真输入会员账号，充值成功自动到账！</p>
                        <a href="<?php echo  $aRow['photo_name']?>" target="_blank" >立即进入</a>
                    </div>
                <?php } ?>
                <div id="d1">
                    <h1>在线存款</h1>
                    <p>我们为您提供了四种存款方式，包括在线支付，网银汇款，支付宝转帐和微信支付等，点击进入存款！</p>
                    <a href="pay_type.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>" target="body" >立即进入</a>

                </div>
                <div id="d2">
                    <h1>在线提款</h1>
                    <p>在线提款，申请的提款将提至您的银行卡帐号上。</p>
                    <a href="../money/withdrawal.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>" >立即进入</a>
                </div>
                <div id="d3">

                    <h1>存取款记录</h1>
                    <p>查看所有交易记录，包括存款记录，取款记录，额度转换记录，汇款记录回查记录等等...</p>

                    <a href="../onlinepay/record.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&username=<?php echo $_SESSION['UserName']?>&thistype=S&date_start=<?php echo $m_date?>&date_end=<?php echo $m_date?>" >立即进入</a>

                </div>
            </div>
            <dl class="deposit-help">


                <dt>备用网址：<a href="http://www.hg0088.ph" target="_blank">www.hg0088.ph(易记收藏)</a></dt>



                <dd>
                    <p> <strong>特别提示：</strong> <br>
                        请客户在进行存款操作前首先确认持有的银行卡是否具有网上支付功能。若未开通或有其他疑问，请按银行"帮助中心"详细了解及处理。 <br>
                        <strong>注意事项：</strong> <br>
                        1、你所使用的存款银行需要开通网上银行业务。 <br>
                        2、当你在存款过程中遇到任何问题，请随联系我们在线客服咨询或邮箱。 </p>
                </dd>
            </dl>
            <a class="cgpay_link" href="../../../tpl/cgpay.php" target="_blank"></a>
            <div class="deposit-shadow"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>

<script type="text/javascript" src="../../../js/jquery.js"></script>


</body></html>