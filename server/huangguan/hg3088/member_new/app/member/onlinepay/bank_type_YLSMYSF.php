<?php
/**
 * 银联扫码|云闪付扫码。手工扫码付款
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$bankid=$_REQUEST['bankid'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式
$sSql = "SELECT id,bank_user,photo_name,bank_user,maxmoney,notice FROM `".DBPREFIX."gxfcy_bank_data` WHERE `id`= '$bankid' AND `bankcode` = 'YLSMYSF' AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class) AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ";
$oRes = mysqli_query($dbLink,$sSql);
$iCou=mysqli_num_rows($oRes);

if( $iCou == 0 ){
    exit('支付方式有误，请重新选择~！');
}

$aRow = mysqli_fetch_assoc($oRes);
//var_dump($aData); die;

?>
<html >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>在线存款-输入金额</title>
    <link rel="stylesheet" type="text/css" href="../../../style/onlinepay.css?v=<?php echo AUTOVER; ?>">
    <link rel="stylesheet" type="text/css" href="../../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">
    <style type="text/css">
        .content{ padding: 5px 30px 50px; }
        .goBack { margin-left: 0;}
        .content_right{float: right; width: 296px; margin: 0 50px 0 0;}
        .saoma_source{border: #CCC 1px solid;}
        .saoma_source dt{text-align: center; border: #CCC 1px solid; background-color: #b8b8b8; color: #ffffff; padding-top: 0px; text-align: center; font-size: 16px; line-height: 52px;}
        .saoma_source dd{width: 100%; height: 200px; margin: 0px auto;}
        .saoma_source dd img{margin: 10px 0 0 60px; width: 180px; height: 180px;}
        .saoma_notes{font-size: 14px;font-weight: bold;color: red;width: 100%; margin: 10px 0;}
        .mc-table{ width: 490px; vertical-align: baseline; background: transparent; border-collapse: collapse; border-spacing: 0;}
        .mc-table tr th{ text-shadow: 0 0 1px #767474; background-color: #b8b8b8; padding: 19px 10px; color: #ffffff;     font-weight: bold;}
        .mc-table tr td{    padding: 0 10px;     position: relative;    display: table-cell;}
        .alipay_enter{ color:#c52000; margin: -5px 0 0 20px;}
        .alipay_enter2{ margin: -5px 0 0 60px;}

    </style>
</head>
<body >

<!-- 银联扫码|云闪付扫码二维码扫码切换页面 开始-->
<div class="third_pay pay-list-each" style="display: block;">

    <form method="post" name="form1" id="form1" action="bank_type_SAOMA_save.php" onsubmit="return savePayAction()">
        <input type="hidden" name="uid" value="<?php echo $uid?>">
        <input type="hidden" name="langx" value="<?php echo $langx?>">
        <input type="hidden" name="payid" id="payid" value="<?php echo $aRow['id'];?>">
        <input type="hidden" name="bank_user" id="bank_user" value="<?php echo $aRow['bank_user'];?>">


        <div class="content" style="margin: 0 auto; ">
            <div class="goBack" onclick="javascript:history.back(-1);">返回上一页</div>

            <br><p>银联扫码|云闪付扫码转帐时，请使用您本人帐号；转帐金额与您申请时填写的金额保持一致，会加快到帐速度。<br>
                支付遇到困难？请联系我们的线上客服获得帮助。</p><br>

            <div>
                <div class="content_right">
                    <dl class="saoma_source" >
                        <dt>银联扫码|云闪付扫码，轻松支付</dt>
                        <dd>
                            <img src="<?php echo $aRow['photo_name'];?>" /><br>
                        </dd>
                    </dl>
                    <div class="saoma_notes">请不要使用整数进行存款否则无法成功，<br>请使用例如：101或者123等！</div>
                </div>
                <table class="mc-table">
                    <tr>
                        <th>姓名</th>
                        <td><?php echo $aRow['bank_user'];?></td>
                    </tr>
                    <tr>
                        <th>转账账号</th>
                        <td style="background-color:#b8b8b8 !important;border-left:1px solid white;">
                            二维码时时更换请勿保存<a href="javascript:void(0);" class="alipay_enter2" id="d_clip_button" data-clipboard-target="MailAddress">复制</a>
                        </td>
                    </tr>
                    <tr>
                        <th>存入金额</th>
                        <td>
                            <input name="v_amount" class="v_amount" onkeyup="clearNoNum(this)" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th style=" color: #ff0000;"><?php echo $aRow['notice']?></th>
                        <td><input name="memo" id="memo" type="text"></td>
                    </tr>
                    <tr>
                        <th>汇款日期</th>
                        <td><input name="cn_date" type="text" id="cn_date" value="" size="22" readonly /></td>
                    </tr>


                </table>
            </div>

            <div class="btnbox">
                <input type="button" class="transbtn2 online-pay-back" onclick="javascript:history.back();" value="返回" >
                <input type="submit" class="transbtn trans_btn_2"  value="申请存款" >
            </div>
        </div>
    </form>
</div>



<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/register/laydate.min.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript">

    // 时间配置
    var beginTime = {
        elem: '#cn_date',
        format: 'YYYY-MM-DD hh:mm:ss',
        istime: true ,
        istoday: false ,
        defaultValue:setAmerTime('#cn_date'),
        choose: function (datas) {

        }
    };
    laydate(beginTime);

    // 在线支付提交申请存款
    function savePayAction() {
        var val = $('.v_amount').val() ; // 充值金额
        var minval = 100 ; // 最小金额
        var maxval = parseInt('<?php echo $aRow['maxmoney']?>') ; // 最大金额
        var memo = $('#memo').val() ; //  交易单号
        var save_time =$('#cn_date').val() ;  // 时间

        if(val =='' || !val ){
            alert('请输入正确的存款金额！');
            $('.v_amount').focus();
            return false ;
        }
        if(val <minval ){
            alert('最低存款金额为'+minval+'元!');
            $('.v_amount').focus();
            return false ;
        }
        if( val>maxval ){
            alert('最高存款金额为'+maxval+'元!');
            $('.v_amount').focus();
            return false ;
        }
        if(memo == ''){
            alert('请输入交易订单号！');
            $('#memo').focus();
            return false;
        }

        if(save_time == ''){
            alert('请选择汇款日期！');
            $('#cn_date').focus();
            return false;
        }
    }

</script>

</body>
</html>
