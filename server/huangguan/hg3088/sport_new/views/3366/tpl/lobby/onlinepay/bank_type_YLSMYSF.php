<?php
/**
 * 银联扫码|云闪付扫码。手工扫码付款
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
$sSql = "SELECT id,bank_user,photo_name,bank_user,maxmoney,notice FROM `".DBPREFIX."gxfcy_bank_data` WHERE `id`= '$bankid' AND `bankcode` = 'YLSMYSF' AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class) AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ";
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
    .saoma_source {float: left;margin-right: 5px;}
    .saoma_notes p {padding: 5px 0;font-size: 24px;font-weight: bold;}
    .saoma_notes span {font-size: 14px;}
    .saoma_source img {max-width: 150px;}
</style>

<!-- 支付宝二维码扫码切换页面 开始-->

<div class="memberWrap">
    <div class="memberTit clearfix">
        <span class="account_icon fl titImg deposit_nav"></span>
        <a class="fr to_deposit" href="javascript:;"> <img class="backImg" src="/images/back.png" alt=""></a>
    </div>
    <form method="post" name="deposit_form" class="deposit_form"  onsubmit="return false"> <!-- bank_type_SAOMA_save.php -->
        <input type="hidden" name="uid" value="<?php echo $uid?>">
        <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']?>">
        <input type="hidden" name="langx" value="<?php echo $langx?>">
        <input type="hidden" name="payid" id="payid" value="<?php echo $aRow['id'];?>">
        <input type="hidden" name="bank_user" id="bank_user" value="<?php echo $aRow['bank_user'];?>">

        <div class="payWay">
            <div class="payWayTit">银联扫码|云闪付扫码，轻松支付</div>
            <table  class="tableSubmit"  cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="3" style="text-align: left;line-height: 30px;">
                        <p>银联扫码|云闪付扫码转帐时，请使用您本人帐号；转帐金额与您申请时填写的金额保持一致，会加快到帐速度。
                            <br>
                            支付遇到困难？请联系我们的线上客服获得帮助。
                        </p>

                    </td>
                </tr>
                <tr>
                    <td style="width: 18%">姓名</td>
                    <td><?php echo $aRow['bank_user'];?></td>
                    <td rowspan="5" style="width: 34%;">
                        <div class="content_right">
                            <dl class="saoma_source" >
                                <dd>
                                    <img src="<?php echo $aRow['photo_name'];?>" /><br>
                                </dd>
                            </dl>
                            <div class="saoma_notes ">
                                <p> 手机扫一扫<br>轻松支付 </p>
                                <span class="red"> 请不要使用整数进行存款否则无法成功，请使用例如：101或者123等！</span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td> 转账账号</td>
                    <td>
                        二维码时时更换请勿保存
                    </td>
                </tr>
                <tr>
                    <td> 存款金额</td>
                    <td><input type="number" step="0.01" class="v_amount" name="v_amount"  onkeyup="clearNoNum(this)" placeholder="请输入存款金额"></td>
                </tr>
                <tr>
                    <td> <span class="red"><?php echo $aRow['notice']?></span></td>
                    <td><input type="text" class="order_number" name="memo" id="memo" ></td>
                </tr>
                <tr>
                    <td> 汇款日期</td>
                    <td><input name="cn_date" type="text" id="cn_date" value="" size="22" readonly /></td>
                </tr>

            </table>
            <div class="btnWrap clearfix">
                <button class="nextBtn deposit_xx_dsf">申请存款</button>
            </div>


        </div>
    </form>

</div>



<!-- 第三方支付切换页面 结束-->


<script type="text/javascript" src="/js/common.js?v=<?php echo AUTOVER; ?>"></script>


<script type="text/javascript">
    $(function () {
        var bankid = '<?php echo $bankid ?>' ;

        // 时间配置
        laydate.render({
            elem: '#cn_date',
            format: 'yyyy-MM-dd HH:mm:ss',
            istime: true ,
            defaultValue:setAmerTime('#cn_date'),
            done: function(value, date){ //时间改变回掉
                // console.log(value)
            }
        });

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
                var memo = $('#memo').val() ; //  交易单号
                var save_time =$('#cn_date').val() ;  // 时间

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
                if(memo == ''){
                    layer.msg('请输入交易订单号!',{time:alertTime});
                    $('#memo').focus();
                    return false;
                }
                deposit_zfb_flage = true;
                var ajaxurl = '/app/member/onlinepay/bank_type_SAOMA_save.php' ;
                var pars ={
                    payid:bankid,
                    bank_user:"<?php echo $aRow['bank_user'];?>",
                    v_amount:val,
                    memo:memo,
                    cn_date:save_time,
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
                            if(res.status == '200'){ // 提款成功
                                // window.location.href = '/' ;
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

