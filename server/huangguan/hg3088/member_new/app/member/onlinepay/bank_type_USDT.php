<?php
/**
 * USDT虚拟币二维码。手工扫码付款
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
$sSql = "SELECT * FROM `".DBPREFIX."gxfcy_bank_data` WHERE `id`= '$bankid' AND `bankcode` = 'USDT' AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class) AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ";
$oRes = mysqli_query($dbLink,$sSql);
$iCou=mysqli_num_rows($oRes);

if( $iCou == 0 ){
    exit('支付方式有误，请重新选择~！');
}
$aRow = mysqli_fetch_assoc($oRes);
//var_dump($aData); die;

$afterurl = returnNewOldVersion('new'); // 随机取一个配置的域名
$newLogin = $afterurl;

$rate = returnUsdtRate();

?>
<html >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>在线存款-输入金额</title>
    <link rel="stylesheet" type="text/css" href="../../../style/onlinepay.css?v=<?php echo AUTOVER; ?>">
    <link rel="stylesheet" type="text/css" href="../../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">
    <style type="text/css">
        .w_800{width: 800px;margin: 0 auto;}
        .mc-table tr {height: 40px;line-height: 40px;}
        .mc-table tr.bg_dis {background:linear-gradient(to bottom, #fef9ed, #f1dbb0)}
        .mc-table .bg_dis td{font-size: 16px;padding-left: 10px;}

        input,select{width:100%;height: 40px;}
        .list-tab thead td{background:linear-gradient(to bottom,#fef9ed,#f1dbb0)}
        .list-tab thead td h1{background:none;font-size:18px;text-indent:30px}
        .frm-tab tbody th{padding:12px 10px}
        .company_pay{width:1080px}
        .list-tab{margin-bottom:15px}

        .deposit_bank_next{cursor:pointer;border:0;display:block;width:100%;height:40px;line-height:40px;text-align:center;font-size:16px;color:#fff !important;background:linear-gradient(to bottom, #fdcc33, #cf5200);border-radius:5px;margin-bottom:15px}
        .back_btn{font-size: 18px;float: right;height: 100%;width: 60px;text-align: center;color: #d70000}
        .czjc_btn{display: inline-block;width: 100%;height: 40px;line-height: 40px;float: left;text-align: center;background: #ccc;border-radius: 5px;color: blue;font-size: 16px;margin-bottom: 20px;}
        /* 二维码区域 */
        .saoma_source img {max-width: 200px;}
        .usdt_price_font { color:red; font-size: 16px; font-weight: bolder;}
        .copy_icon{display: inline-block;vertical-align:middle;width: 30px;height: 30px;background: url(/images/2018/deposit/fuzhi.png) center no-repeat;background-size: cover;}
        .cz_title{line-height: 30px;float: left;font-size: 16px;}
        .cz_url{width: 300px;text-align: center;}
        .cz_url p,.cz_url a{font-size: 14px}
        .cz_url a{color: blue}
        .tip_title{width: 100px;line-height: 50px;text-align: center;border: 1px solid #ccc;border-radius: 5px;font-size: 20px;}
        .tip_top{background: linear-gradient(to bottom, #fef9ed, #f1dbb0);padding: 0 10px;}
        .icon_chat{display: inline-block;width: 20px;height: 25px;background: url(/images/2018/deposit/chat_icon.png) center no-repeat;background-size:100%;vertical-align: middle;margin-left: 5px;}

    </style>
</head>
<body >

<!-- 支付宝二维码扫码切换页面 开始-->
<div class="company_pay pay-list-each" style="display: block;">

    <form method="post" name="form1" id="form1" action="bank_type_SAOMA_save.php" onsubmit="return savePayAction()">
        <input type="hidden" name="uid" value="<?php echo $uid?>">
        <input type="hidden" name="langx" value="<?php echo $langx?>">
        <input type="hidden" name="payid" id="payid" value="<?php echo $aRow['id'];?>">
        <input type="hidden" name="bank_user" id="bank_user" value="<?php echo $aRow['bank_user'];?>">

        <div class="bank_deposit_1">

            <table class="list-tab" >
                <thead>
                <tr>
                    <td colspan="6"><h1 class="c3">存款</h1> <a href="javascript:history.back(-1);" class="back_btn"> 返回</a></td>
                </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
            <div class="w_800">
                <table class="mc-table w_800">
                    <tr class="bg_dis"><td>充值金额</td></tr>
                    <tr>
                        <td>
                            <input name="v_amount" class="v_amount" onkeyup="clearNoNum(this);countUsdtMount()" type="text" placeholder="请输入充值金额">
                        </td>
                    </tr>
                    <tr class="bg_dis"><td>支付通道</td></tr>
                    <tr>
                        <td><img style="width: 20px; height: 20px; display: inline;" src="/images/2018/deposit/usdt.png" alt=""> &nbsp;USDT-极速</td>
                    </tr>


                </table>

                <p style="line-height: 28px;">请注意：请在金额转出之后务必填写网页下方的汇款信息表格，以便我们财务人员能及时为您确认添加金额到您的会员账户。<br>
                    本公司最低存款金额为100元，每次存款赠送最高2%红利。
                </p>

            </div>
        </div>

        <!-- 二维码 -->
        <div class="w_800 bank_deposit_2">
            <table  class="tableSubmit"  cellspacing="0" cellpadding="0">
                <tr>
                    <td >
                        <div class="tip_title ">TRC20</div>
                        <p >
                            <span class="cz_title">请转入 <span class="usdt_price_font pay_to_usdt"> 0</span> USDT </span>
                            <a class="copy_icon copy_btn" href="javascript:;" data-clipboard-target=".pay_to_usdt" title="点击复制"></a>
                        </p>
                        <div class="content_right">
                            <dl class="saoma_source" >
                                <dd>
                                    <img src="<?php echo $aRow['photo_name'];?>" /><br>
                                </dd>
                            </dl>
                            <div class="cz_url">
                                <p>充值地址</p>
                                <span class="cz_title" style="float: none;"><span class="pay_to_usdt_url"> <?php echo $aRow['deposit_address'];?>  </span> </span></br>
                                <a class="copy_btn" href="javascript:;" data-clipboard-target=".pay_to_usdt_url" title="点击复制"><span class="copy_icon"></span> 复制地址 </a>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;line-height: 30px;">

                        <p class="tip_top">支付完成请等待<span class="usdt_price_font">5-10</span>分钟到账，支付失败请<a style="margin-left: 5px;" target="_blank" class="to_livechat"><span class="usdt_price_font">咨询客服</span><span class="icon_chat"></span></a> </p>
                        <h3>*注意：</h3>
                        <p>
                            1.请勿向上述地址支付任何非TRC20 USDT 资产，否则资产将无法找回。<br>
                            2.当前<?php echo getSysConfig('usdt_jiaoyisuo') ? getSysConfig('usdt_jiaoyisuo') : 'Okex/火币/币安';?>交易所 USDT 最新场外卖出单价 <span class="usdt_price_font new_usdt_rate"> <?echo $rate['usdt_rate'];?></span> 元。<br>
                            3.请确保收款地址收到 <span class="usdt_price_font pay_to_usdt"> 0</span> USDT  <span class="usdt_price_font" style="font-size: 14px;">【不含转账手续费】</span> ，否则无法到账。<br>
                            4.您支付至上述地址后，需要整个网络节点的确认，请耐心等待。
                        </p>

                    </td>
                </tr>
            </table>
        </div>
    <div class="w_800">
        <input type="submit" class="transbtn deposit_bank_next"  value="申请存款" >
        <a class="czjc_btn" href="<?php echo $newLogin;?>/tpl/usdtCourse.php" target="_blank">USDT充值教程</a>
    </div>
    </form>
</div>




<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/register/laydate.min.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/clipboard.min.js"></script>
<script type="text/javascript">
    var alertTime = 2000;
    $('.to_livechat').attr({"href":top.configbase.service_meiqia}); // 在线客服
    copyBnakAction();
    // usdt 金额输入与计算
    function countUsdtMount(){
        var $pay_to_usdt = $('.pay_to_usdt');
        var usdt_rate = Number($('.new_usdt_rate').text()); // 汇率
        var cz_val = Number($('.v_amount').val()) ; // 充值金额
        var zf_val = cz_val/(usdt_rate); // 需要转入的usdt
        zf_val = advFormatNumber(zf_val,2); // 保留两位小数
        $pay_to_usdt.text(zf_val);

    }

    // 复制
    function copyBnakAction() {
        $('.copy_btn').each(function (num) {
            // console.log(num);
            var clipboard = new ClipboardJS(this, {
                text: function () {
                    return $(this).prev().text();
                }
            });
            clipboard.on('success', function (e) {
                //console.log(e);
                layer.msg('复制成功!', {time: alertTime});
                e.clearSelection();
            });
            clipboard.on('error', function (e) {
                //console.log(e);
                layer.msg('请选择“拷贝”进行复制!', {time: alertTime});
            });
        });
    }
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
