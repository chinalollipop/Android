<?php
/**
 * USDT二维码。手工扫码付款
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../../../../../app/member/include/address.mem.php";
require ("../../../../../app/member/include/config.inc.php");
require ("../../../../../app/member/include/define_function_list.inc.php");

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$bankid=$_REQUEST['bankid'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.location.href='/'</script>";
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

?>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/memberaccount.css?v=<?php echo AUTOVER; ?>" >
<style>
    .saoma_notes p {padding: 5px 0;font-size: 24px;font-weight: bold;}
    .saoma_notes span {font-size: 14px;}
    .saoma_source img {max-width: 200px;}
    .usdt_price_font { color:red; font-size: 16px; font-weight: bolder;}
    .copy_icon{display: inline-block;vertical-align:middle;width: 30px;height: 30px;background: url(/images/bank/fuzhi.png) center no-repeat;background-size: cover;}
    .cz_title{line-height: 30px;float: left;font-size: 16px;}
    .left_50{margin-left: 50px;}
    .cz_url{width: 300px;text-align: center;}
    .czjc_btn{display: inline-block;width: 120px;height: 38px;line-height: 38px;float: left;text-align: center;background: #ccc;border-radius: 5px;color: blue;margin: 20px 15px;}
    .tip_title{width: 100px;line-height: 50px;text-align: center;border: 1px solid #ccc;border-radius: 5px;font-size: 20px;}
    .tip_top{background: linear-gradient(to bottom, #fef9ed, #f1dbb0);padding: 0 10px;}
    .icon_chat{display: inline-block;width: 20px;height: 25px;background: url(/images/bank/chat_icon.png) center no-repeat;background-size:100%;vertical-align: middle;margin-left: 5px;}
</style>

<!-- USDT二维码扫码切换页面 开始-->

<div class="memberWrap">
    <div class="memberTit clearfix">
        <span class="account_icon fl titImg deposit_nav"></span>
        <a class="fr to_deposit" href="javascript:;"> <img class="backImg" src="/images/back.png" alt=""></a>
    </div>
    <div class="payWay">
        <form method="post" name="deposit_form" class="deposit_form"  onsubmit="return false"> <!-- bank_type_SAOMA_save.php -->
        <input type="hidden" name="uid" value="<?php echo $uid?>">
        <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']?>">
        <input type="hidden" name="langx" value="<?php echo $langx?>">
        <input type="hidden" name="payid" id="payid" value="<?php echo $aRow['id'];?>">
        <input type="hidden" name="bank_user" id="bank_user" value="<?php echo $aRow['bank_user'];?>">

        <div class=" bank_deposit_1">
            <div class="payWayTit">USDT支付</div>
            <table  class="tableSubmit"  cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="3" style="text-align: left;line-height: 30px;">
                        <p>请注意：请在金额转出之后务必填写网页下方的汇款信息表格，以便我们财务人员能及时为您确认添加金额到您的会员账户。<br>
                            本公司最低存款金额为100元，每次存款赠送最高2%红利。
                        </p>

                    </td>
                </tr>
                <tr>
                    <td> 充值金额</td>
                    <td><input type="number" step="0.01" class="v_amount" name="v_amount"  onkeyup="clearNoNum(this)" placeholder="请输入存款金额"></td>
                </tr>
                <tr>
                    <td> 支付通道</td>
                    <td> <img style="width: 20px; height: 20px; display: inline;" src="/images/usdt.png" alt=""> &nbsp;USDT-极速</td>
                </tr>

            </table>

        </div>

        <div class="bank_deposit_2">
            <table  class="tableSubmit"  cellspacing="0" cellpadding="0">
                <tr>
                    <td rowspan="2" ></td>
                    <td >
                        <div class="tip_title left_50">TRC20</div>
                        <p class="left_50">
                            <span class="cz_title">请转入 <span class="usdt_price_font pay_to_usdt">0</span> USDT </span>
                            <a class="copy_icon copy_btn" href="javascript:;" data-clipboard-target=".pay_to_usdt" title="点击复制"></a>
                        </p>
                        <div class="content_right">
                            <dl class="saoma_source left_50" >
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
                        <p>1.请勿向上述地址支付任何非TRC20 USDT 资产，否则资产将无法找回。<br>
                            2.当前<?php echo getSysConfig('usdt_jiaoyisuo') ? getSysConfig('usdt_jiaoyisuo') : 'Okex/火币/币安';?>交易所 USDT 最新场外卖出单价 <span class="usdt_price_font new_usdt_rate"> </span> 元。<br>
                            3.请确保收款地址收到 <span class="usdt_price_font pay_to_usdt"> 0 </span> USDT  <span class="usdt_price_font" style="font-size: 14px;">【不含转账手续费】</span> ，否则无法到账。<br>
                            4.您支付至上述地址后，需要整个网络节点的确认，请耐心等待。
                        </p>

                    </td>
                </tr>
            </table>
        </div>

        <div class="btnWrap clearfix">
            <a class="czjc_btn" href="/tpl/usdtCourse.php" target="_blank">USDT充值教程</a>
            <button class="nextBtn deposit_xx_dsf">申请存款</button>

        </div>

    </form>
    </div>
</div>



<!-- 第三方支付切换页面 结束-->


<script type="text/javascript" src="/js/common.js?v=<?php echo AUTOVER; ?>"></script>


<script type="text/javascript">
    $(function () {

        var bankid = '<?php echo $bankid ?>' ;

        indexCommonObj.addLiveUrl();
        copyBnakAction();
        getUsdtRate();
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

        // usdt 金额输入与计算
        function countUsdtMount(){
            var $pay_to_usdt = $('.pay_to_usdt');
            var usdt_rate = Number($('.new_usdt_rate').text()); // 汇率
            var cz_val = Number($('.v_amount').val()) ; // 充值金额
            var zf_val = cz_val/(usdt_rate); // 需要转入的usdt
            zf_val = advFormatNumber(zf_val,2); // 保留两位小数
            $pay_to_usdt.text(zf_val);
        }
        $('.v_amount').on('keyup',function () { // 监听输入
            countUsdtMount();
        })
        // 获取usdt汇率
        function getUsdtRate() {
            var rateUrl = '/app/member/api/usdtRateApi.php';
            $.ajax({
                type: 'POST',
                url: rateUrl,
                data: {},
                dataType: 'json',
                success: function (res) {
                    if(res){
                        $('.new_usdt_rate').text(res.data.usdt_rate); // 更新汇率
                    }
                },
                error: function () {
                    layer.msg('网络错误，请稍后重试!',{time:alertTime});
                }
            });
        }
        // 在线支付提交申请存款
        function savePayAction() {
            var deposit_zfb_flage = false;
            $('.deposit_xx_dsf').on('click',function () {
                if(deposit_zfb_flage){
                    return ;
                }
                var val = $('.v_amount').val() ; // 充值金额
                var minval = 100 ; // 最小金额
                var maxval = parseInt('<?php echo $aRow['maxmoney']?>') ; // 最大金额

                if(val =='' || !val ){
                    layer.msg('请输入正确的存款金额',{time:alertTime});
                    $('.v_amount').focus();
                    return false ;
                }
                if(val <minval ){
                    layer.msg('最低存款金额为'+minval+'元!',{time:alertTime});
                    $('.v_amount').focus();
                    return false ;
                }
                if( val>maxval ){
                    layer.msg('最高存款金额为'+maxval+'元!',{time:alertTime});
                    $('.v_amount').focus();
                    return false ;
                }

                deposit_zfb_flage = true;
                var ajaxurl = '/app/member/onlinepay/bank_type_SAOMA_save.php' ;
                var pars ={
                    payid:bankid,
                    bank_user:"<?php echo $aRow['bank_user'];?>",
                    v_amount:val
                }
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: pars,
                    dataType: 'json',
                    success: function (res) {
                        if(res){
                            setTimeout(function () {
                                deposit_zfb_flage = false ;
                            },1000)
                            layer.msg(res.describe,{time:alertTime});
                            if(res.status == '200'){
                               // countUsdtMount();
                                indexCommonObj.loadUserPlatformPage() ; // 存款成功跳转到额度转换

                            }
                        }
                    },
                    error: function () {
                        deposit_zfb_flage = false ;
                        layer.msg('网络错误，请稍后重试!',{time:alertTime});
                    }
                });

            })


        }

        savePayAction();

    })


</script>

