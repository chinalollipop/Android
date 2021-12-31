<?php
/**
 * 显示支付通道的分类
 *
 * 银行卡线上
银行卡线下
微信第三方
微信扫码
微信转账
支付宝第三方
支付宝扫码
支付宝转账

qq扫码

 */
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
$depositTimes = $_SESSION['DepositTimes']; //会员存款次数

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
require ("../include/traditional.$langx.inc.php");
$username=$_SESSION['UserName'];
// 显示支付通道的分类
$sWhere ="AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$sWhere .= " AND `status` = 1 "; // 开启状态且小于会员存款次数
// 第三方
$sSql = 'SELECT id,account_company,depositNum FROM `'.DBPREFIX.'gxfcy_pay` WHERE 1 '.$sWhere." AND `depositNum` <= '$depositTimes'";

$oRes = mysqli_query($dbLink,$sSql);

//$iCou = mysqli_num_rows($oRes);
$aThird_bank_pay = $aThird_weixin_pay = $aThird_ali_pay = $aThird_qq_pay = array();
while ($aRow = mysqli_fetch_assoc($oRes)){
    switch ($aRow['account_company']){
        //得到第三方网银支付配置
        case 1: $aThird_bank_pay[] = $aRow; break;
        //得到第三方支付银行卡配置
        case 2: $aThird_bank_pay[] = $aRow; break;
        //得到第三方支付微信配置
        case 4: $aThird_weixin_pay[] = $aRow; break;
        //得到第三方支付支付宝配置
        case 5: $aThird_ali_pay[] = $aRow; break;
        //得到第三方支付QQ扫码配置
        case 6: $aThird_qq_pay[] = $aRow; break;
        default: break;
    }
}
$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式
$sWhere .=" AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` " ;

// 线下银行通道显示
$sSql = "SELECT id,bankcode,bank_name,issaoma FROM `".DBPREFIX."gxfcy_bank_data` WHERE 1 ".$sWhere;
$oRes = mysqli_query($dbLink,$sSql);

$aBank_pay = $aAli_pay = $aAli_saoma_pay = $aWx_saoma_pay = $aYlsm_ysf_saoma_pay = array();
while ($aRow = mysqli_fetch_assoc($oRes)){

    //得到银行卡支付
    if ($aRow['issaoma'] == 0 && $aRow['bankcode'] != 'WXSAOMA'  && $aRow['bankcode'] != 'ALISAOMA'){
        $aBank_pay[] = $aRow;
    }
    //得到支付宝支付
    /*if ($aRow['issaoma'] == 0 && $aRow['bank_name'] == '支付宝'){
        $aAli_pay[] = $aRow;
    }*/
    //得到微信扫码支付
    if ($aRow['issaoma'] == 1 && $aRow['bankcode'] == 'WXSAOMA'){
        $aWx_saoma_pay[] = $aRow;
    }
    //得到支付宝扫码支付
    if ($aRow['issaoma'] == 1 && $aRow['bankcode'] == 'ALISAOMA'){
        $aAli_saoma_pay[] = $aRow;
    }
    //银联扫码|云闪付
    if ($aRow['issaoma'] == 1 && $aRow['bankcode'] == 'YLSMYSF'){
        $aYlsm_ysf_saoma_pay[] = $aRow;
    }
}

$membermessage = getMemberMessage($username,'1'); // 存款短信

?>
<html ><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>在线存款</title>
    <link rel="stylesheet" type="text/css" href="../../../style/onlinepay.css?v=<?php echo AUTOVER; ?>">
    <link rel="stylesheet" type="text/css" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>">
    <style>
        .deposit-ui #d20 {
            background-position: -1638px -369px;
        }
        .deposit-ui #d20 h1 {
            background-position: -690px -457px;
        }
        .deposit-ui #d20:hover h1 {
            background-position: -459px -460px;
        }
        .deposit-ui #d20:hover p {
            color: #28b226
        }
    </style>
</head>
<body >
<!-- 充值方式列表 开始-->
<div class="pay-list-all">
    <div class="deposit-ui">
        <?php if(!empty($aThird_bank_pay)) { // 第三方网银
                ?>
                <div id="d1" class="deposit-list">
                    <h1></h1>
                    <p>在线支付，即时入账</p>
                    <a href="pay_type_third_bank.php?uid=<?php echo $uid;?>&langx=<?php echo $langx;?>" data-val="online_pay">进入存款</a>
                </div>
                <?php
        }?>

        <?php if(!empty($aBank_pay)) {  // 线下公司入款
            ?>
            <div id="d2" class="deposit-list">
                <h1>公司入款</h1>
                <p>人工转账，需要审核</p>
                <a href="remittance.php?uid=<?php echo $uid;?>&langx=<?php echo $langx;?>" data-val="wx_pay">进入存款</a>
            </div>
            <?php
        }?>

        <?php if(!empty($aThird_weixin_pay)) { // 第三方微信
                ?>
                <div id="d4" class="deposit-list">
                    <h1><?php echo $value['title']; ?></h1>
                    <p>第三方支付，即时入账</p>
                    <a href="pay_type_third_wx.php?uid=<?php echo $uid;?>&langx=<?php echo $langx;?>" data-val="wx_pay">进入存款</a>
                </div>
                <?php
        }?>
        <?php if(!empty($aThird_ali_pay)) { // 第三方支付宝
                ?>
                <div id="d3" class="deposit-list">
                    <h1><?php echo $value['title']; ?></h1>
                    <p>第三方支付，即时入账</p>
                    <a href="pay_type_third_zfb.php?uid=<?php echo $uid;?>&langx=<?php echo $langx;?>" data-val="zfb_pay">进入存款</a>
                </div>
                <?php
        }?>
        <?php if(!empty($aThird_qq_pay)) { // 第三方QQ扫码
            ?>
            <div id="d11" class="deposit-list">
                <h1><?php echo $value['title']; ?></h1>
                <p>第三方支付，即时入账</p>
                <a href="pay_type_third_qq.php?uid=<?php echo $uid;?>&langx=<?php echo $langx;?>" data-val="qq_pay">进入存款</a>
            </div>
            <?php
        }?>
        <?php if(!empty($aAli_saoma_pay)) { // 线下支付宝扫码
            foreach ($aAli_saoma_pay as $key => $val) {
                ?>
                <div id="d3" class="deposit-list">
                    <h1>线下支付宝</h1>
                    <p>公司支付宝扫码，及时入账</p>
                    <a href="bank_type_ALISAOMA.php?uid=<?php echo $uid; ?>&langx=<?php echo $langx; ?>&bankid=<?php echo $val['id']?>"
                       data-val="zfb_pay">进入存款</a>
                </div>
                <?php
            }
        }?>
        <?php if(!empty($aWx_saoma_pay)) { // 线下微信扫码
            foreach ($aWx_saoma_pay as $key => $val) {
            ?>
            <div id="d4" class="deposit-list">
                <h1>线下微信</h1>
                <p>公司微信扫码，及时入账</p>
                <a href="bank_type_WESAOMA.php?uid=<?php echo $uid;?>&langx=<?php echo $langx;?>&bankid=<?php echo $val['id']?>" data-val="wx_pay">进入存款</a>
            </div>
            <?php
            }
        }?>
        <?php if(!empty($aYlsm_ysf_saoma_pay)) { // 银联扫码|云闪付
            foreach ($aYlsm_ysf_saoma_pay as $key => $val) {
                ?>
                <div id="d20" class="deposit-list">
                    <h1>银联扫码|云闪付扫码</h1>
                    <p>银联扫码|云闪付扫码，及时入账</p>
                    <a href="bank_type_YLSMYSF.php?uid=<?php echo $uid;?>&langx=<?php echo $langx;?>&bankid=<?php echo $val['id']?>" data-val="wx_pay">进入存款</a>
                </div>
                <?php
            }
        }?>



        <!--<div id="d3" class="deposit-list">
            <h1>第三方支付宝扫码</h1>
            <p>在线支付，即时入账</p>
            <a href="javascript:;" data-val="zfb_pay">进入存款</a>
        </div>
        <div id="d11" class="deposit-list">
            <h1>第三方QQ扫码</h1>
            <p>在线支付，即时入账</p>
            <a href="javascript:;" data-val="qq_pay">进入存款</a>
        </div>




        <div id="d2" class="deposit-list">
            <h1>公司银行卡号</h1>
            <p>［银行卡入款］银联＋网上银行转账 全部支持.存款金额1000以上建议使用.</p>
            <a href="../bank/Index.php?uid=<?php/*=$uid*/?>&langx=<?php/*=$langx*/?>" data-val="company_pay" >进入存款</a>
        </div>
        <div id="d3" class="deposit-list">
            <h1>公司支付宝扫码</h1>
            <p>通过支付宝入款</p>
            <a href="javascript:;" data-val="zfb_pay">进入存款</a>
        </div>-->

    </div>

    <!--<div class="deposit-ui">
        <?php /*if(!empty($aThird_ali_pay)) {*/?>
            <a href="">第三方网银</a>

        <?php /*}*/?>
        <?php /*if(!empty($aBank_pay)) {*/?>
            <a href='remittance.php?uid=<?php/*=$uid*/?>&langx=<?php/*=$langx*/?>&bank_user=<?php/*=$aBank_pay['bank_user']*/?>&bank_account=<?php/*=$aBank_pay['bank_account']*/?>&bank_addres=<?php/*=$aBank_pay['bank_addres']*/?>&bank_name=<?php/*=$aBank_pay['bank_name']*/?>'>公司银行卡号</a>

        <?php /*}*/?>
        <?php /*if(!empty($aAli_saoma_pay)) {*/?>
            公司支付宝扫码

        <?php /*}*/?>
        <?php /*if(!empty($wx_saoma_pay)) {*/?>
            公司微信二维码
        <?php /*}*/?>
        <?php /*if(!empty($aThird_ali_pay)) {*/?>
            第三方支付宝扫码

        <?php /*}*/?>
        <?php /*if(!empty($aThird_qq_pay)) {*/?>
            第三方QQ扫码

        <?php /*}*/?>
        <?php /*if(!empty($aAli_pay)) {*/?>
            <!--公司支付宝号-->
        <?php /*}*/?>
        <?php /*if(!empty($aThird_weixin_pay)) {*/?>
<!--            第三方微信-->
        <?php /*}*/?>

<!--    </div>-->
    <dl class="deposit-help">
        <dt>备用网址：<a href="<?php echo HTTPS_HEAD?>://www.hg0088.ph" target="_blank">www.hg0088.ph(易记收藏)</a></dt>
        <dd>
            <p>
                <strong>特别提示：</strong>
                <br>请客户在进行存款操作前首先确认持有的银行卡是否具有网上支付功能。若未开通或有其他疑问，请按银行"帮助中心"详细了解及处理。
                <br><strong>注意事项：</strong>
                <br>1、你所使用的存款银行需要开通网上银行业务。
                <br>2、当你在存款过程中遇到任何问题，请随联系我们在线客服咨询或邮箱。
            </p>
        </dd>
    </dl>
</div>

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>
<script>
    var depositNum = '<?php echo $membermessage['mcou']?>' ; // 是否有会员信息
    var depositMsg = '<?php echo $membermessage['mem_message']?>' ; // 会员信息
    // 弹窗信息
    if(depositNum>0){ // 有弹窗短信
        layer.alert(depositMsg, {
            title: '会员信息',
            icon: false , // 0,1
            skin: 'layer-ext-moon'
        }) ;
    }

</script>
</body>
</html>
