<?php
/**
 * 使用第三方QQ存款
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
// 默认查询当天的数据
require ("../../../../../app/member/include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.location.href='/'</script>";
    exit;
}
$username = $_SESSION['UserName'] ;
$depositTimes = $_SESSION['DepositTimes']; //会员存款次数
//查出第三方通道次数小于会员存款次数的通道
$sSql = "SELECT id,minCurrency,maxCurrency,thirdpay_code,url,title FROM `".DBPREFIX."gxfcy_pay` WHERE `account_company` = 6 AND `status` = 1 AND `depositNum` <= $depositTimes AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$oRes = mysqli_query($dbLink,$sSql);

$iCou=mysqli_num_rows($oRes);

if( $iCou == 0 ){
    exit('支付方式有误，请重新选择~！');
}
$aData = [];
while($aRow = mysqli_fetch_assoc($oRes)){
    $aData[]=$aRow;
}

$aPid = [];
$aUrl = [];
$aMinCurrency = [];
$aMaxCurrency = [];
foreach ($aData as $k => $v){
    $aPid[$k] = $v['id'];
    $aMinCurrency[$k] = bcmul(floatval($v['minCurrency']) , 1);
    $aMaxCurrency[$k] = bcmul(floatval($v['maxCurrency']) , 1);
    // 闪付
    if ( $v['thirdpay_code'] == 'sf' ){
        $aUrl[$k] =  $v['url'].'/sfpay.php';
    }
    // 仁信
    if ( $v['thirdpay_code'] == 'rx' ){
        $aUrl[$k] =  $v['url'].'/rxpay.php';
    }
    if ( $v['thirdpay_code'] == 'zb' ){ // 众宝
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/zhongbaopay.php';
    }
    if ( $v['thirdpay_code'] == 'wdf' ){ // 维多付
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/weiduofupay.php';
    }
    if ( $v['thirdpay_code'] == 'csj' ){ // 创世纪
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/csjpay.php';
    }
}


?>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/memberaccount.css?v=<?php echo AUTOVER; ?>" >
<style>
    .pay_box{margin:15px;padding:10px;border:1px solid #bb0b26;font-size:14px}
    .button-container a{display:inline-block;width:100px;height:40px;line-height:40px;text-align:center;color:#fff;background:#bb0b26;border-radius:5px;margin:20px}

</style>

<!-- 第三方支付切换页面 开始-->

<div class="memberWrap">
    <div class="memberTit clearfix">
        <span class="account_icon fl titImg deposit_nav"></span>
        <a class="fr to_deposit" href="javascript:;"> <img class="backImg" src="/images/back.png" alt=""></a>
    </div>
    <form method="post" name="deposit_form" class="deposit_form" target="_blank" onsubmit="return savePayAction()">
        <input type="hidden" name="uid" value="<?php echo $uid?>">
        <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']?>">
        <input type="hidden" name="langx" value="<?php echo $langx?>">
        <input type="hidden" name="payid" id="payid" value="">

        <div class="payWay">
            <div class="tip_title"><span class="btn_game">2</span>QQ第三方支付</div>
            <table  class="tableSubmit"  cellspacing="0" cellpadding="0">
                <tr>
                    <td>用户账号</td>
                    <td><?php echo $username;?></td>
                </tr>
                <tr>
                    <td><span class="red">*</span>支付渠道</td>
                    <td>
                        <select name="pid" id="select_bank" onchange="getBanklist(this)">
                            <option>请选择</option>
                            <?php
                            foreach ($aData as $k => $v){
                                ?>

                                <option value="<?php echo $k; ?>"><?php echo $v['title']; ?></option>

                                <?php
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td><span class="red">*</span>存款金额</td>
                    <td><input type="number" step="0.01" class="fast_choose order_amount" name="order_amount" id="order_amount" value="" onkeyup="clearNoNum(this)" placeholder="请输入存款金额"></td>
                </tr>
                <tr>
                    <td>快速设置金额</td>
                    <td class="moneyType">
                        <a href="javascript:change(100)" class="quickM">￥100</a>
                        <a href="javascript:change(500)" class="quickM">￥500</a>
                        <a href="javascript:change(1000)" class="quickM">￥1000</a>
                        <a href="javascript:change(5000)" class="quickM">￥5000</a>
                        <a href="javascript:change(10000)" class="quickM">￥10000</a>
                        <a href="javascript:change(50000)" class="quickM">￥50000</a>
                    </td>
                </tr>

            </table>
            <div class="btnWrap clearfix">
                <button class="nextBtn" onclick="$('.deposit_form')[0].reset();return false;">重新填写</button>
                <button class="nextBtn">提交信息</button>

            </div>


        </div>
    </form>

</div>



<!-- 第三方支付切换页面 结束-->



<script type="text/javascript" src="/js/common.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">

    var m_date = <?php echo '\''.$m_date.'\'' ?> ;

    function getBanklist(obj){

        // 银行卡列表json结构数据
        var ourl = <?php echo json_encode($aUrl); ?>;
        var oPid = <?php echo json_encode($aPid); ?>;
        var oMinCurrency = <?php echo json_encode($aMinCurrency); ?>;
        var oMaxCurrency = <?php echo json_encode($aMaxCurrency); ?>;

        //获得渠道下拉框的对象
        var val = obj.value ;

        // 渲染数据

        $(".deposit_form").attr("action",ourl[val]); // url
        $("#payid").val(oPid[val]);
        $("#min_money").html(oMinCurrency[val]);
        $("#max_money").html(oMaxCurrency[val]);

    }

    // 在线支付提交申请存款
    function savePayAction() {
        var pay_type = $("#select_bank").val();
        var $title=$("#select_bank").find("option:selected").text();
        var $bank = $('input[name="bankPayId"]') ;
        var val = $('.order_amount').val() ; // 充值金额
        var minval = parseInt($('.min_money').html()) ; // 最小金额
        var maxval = parseInt($('.max_money').html()) ; // 最大金额
        var num =0 ; // 是否有选择银行

        if(!pay_type || pay_type== '请选择'){
            layer.msg('请选择支付渠道',{time:alertTime});
            return false ;
        }
        if($bank.length > 0){ // 如果是在线支付，有银行列表
            $bank.each(function () {
                if(this.checked){
                    num += 1 ;
                }
            }) ;
            if(num < 1){
                layer.msg('请选择存款银行',{time:alertTime});
                return false ;
            }
        }

        if(val =='' || !val ){
            layer.msg('请输入正确的存款金额',{time:alertTime});
            return false ;
        }
        if(val <minval ){
            layer.msg('最低存款金额为'+minval+'元!',{time:alertTime});
            return false ;
        }
        if( val>maxval ){
            layer.msg('最高存款金额为'+maxval+'元!',{time:alertTime});
            return false ;
        }
        var alstr = paySuccessAlert($title,val,m_date,m_date) ;
        top.alertcon = layer.open({
            type: 1,
            skin: 'layui-layer-login', //样式类名
            title: $title,
            shadeClose: true,
            shade:0.5,
            area: ['360px', '270px'],
            content: alstr
        });

    }

    // 支付成功弹窗, title 标题，uid 用户id ,lang 语言， username 用户名，mon 充值金额
    function paySuccessAlert(title,mon,date_start,date_end) {
        var str = '<div class="pay_box" >\n'+
            '<div id="paneliframe" class="ui-dialog-content ui-widget-content" >\n'+
            '<div class="field-d">\n'+
            '<div class="popup">\n'+
            '<div class="popupinternal order-submit">\n'+
            '<div class="loading-container">\n'+
            '<h4>支付信息</h4>\n'+
            '<p>支付方式：'+title+'</p>\n'+
            '<p id="pay_money">支付金额：￥'+mon+'</p>\n'+
            '<p>\n'+
            '1. 成功付款后将会自动到帐，并弹出到帐提示。<br>\n'+
            '2. 长时间无反应，请联系客服。\n'+
            '</p>\n'+
            '</div>\n'+
            '<div class="button-container">\n'+
            '<a href="javascript:;" class="to_userbetaccount btn-blue button">查看交易</a>\n'+
            '<a target="_blank" href="'+configbase.onlineserve+'" class="btn-blue button">联系客服</a>\n' +
            '</div>\n' +
            '</div>\n' +
            '</div>\n' +
            '</div>\n' +
            '</div>\n' +
            '</div>' ;

        return str ;

    }


</script>

