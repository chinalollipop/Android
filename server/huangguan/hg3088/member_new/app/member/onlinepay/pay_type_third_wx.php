<?php
/**
 * 使用第三方微信存款
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
// 默认查询当天的数据
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$username = $_SESSION['UserName'] ;
$depositTimes = $_SESSION['DepositTimes']; //会员存款次数
//查出第三方通道次数小于会员存款次数的通道
$sSql = "SELECT id,thirdpay_code,url,minCurrency,maxCurrency,title FROM `".DBPREFIX."gxfcy_pay` WHERE `account_company` = 4 AND `status` = 1 AND `depositNum` <= $depositTimes AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";

$oRes = mysqli_query($dbMasterLink,$sSql);
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
    // 智融宝
    if ( $v['thirdpay_code'] == 'zrb' ){
        $aUrl[$k] =  $v['url'].'/zrbpay.php';
    }

    if ( $v['thirdpay_code'] == 'flg' ){ // 菲利谷支付宝
        $aUrl[$k] =  $v['url'].'/flgpay.php';
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
<html >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>在线存款-输入金额</title>
    <link rel="stylesheet" type="text/css" href="../../../style/onlinepay.css?v=<?php echo AUTOVER; ?>">
    <link rel="stylesheet" type="text/css" href="../../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">

    <style type="text/css">
        .content{ padding: 5px 30px 50px; }
        .goBack { margin-left: 0;}
        #bankList{ overflow: hidden; }
        #bankList h1{ font-weight: normal; font-size: 16px; }
        #bankList li {display: inline-block;cursor: pointer;margin: 10px 15px 0px 0px;width: 120px;height: 37px;border: 1px solid #a1a1a1;overflow: hidden;border-radius: 5px;}
        /*  弹窗红色样式 */
        div.jbox .jbox-title-panel{background: #bf0058;background: -webkit-gradient(linear, left top, left bottom, from(#D01313), to(#990046));background: -moz-linear-gradient(top,  #D01313,  #990046);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#D01313', endColorstr='#990046');}
        div.jbox .jbox-button{background: #bf0058;background: -webkit-gradient(linear, left top, left bottom, from(#BD0C24), to(#990046));background: -moz-linear-gradient(top,  #D01313,  #990046);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#D01313', endColorstr='#990046');}
        div.jbox .jbox-button-hover{background: #bf0058;background: -webkit-gradient(linear, left top, left bottom, from(#bf0058), to(#730035));background: -moz-linear-gradient(top,  #bf0058,  #730035);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#bf0058', endColorstr='#730035');}
        div.jbox .jbox-button-active{background: -webkit-gradient(linear, left top, left bottom, from(#730035), to(#bf0058));background: -moz-linear-gradient(top,  #730035,  #bf0058);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#730035', endColorstr='#bf0058');}
        .jbox-content>div {margin: 15px !important;padding: 10px !important;border: 1px solid #bb0b26;line-height: 20px;}
        div.jbox .jbox-button-panel {padding: 5px 15px !important;}
        .jbox-button-panel button{display: none;}

    </style>
</head>
<body >

<!-- 第三方支付切换页面 开始-->
<div class="third_pay pay-list-each" style="display: block;">

    <form method="post" name="form1" id="form1" target="_blank" onsubmit="return savePayAction()">
        <input type="hidden" name="uid" value="<?php echo $uid?>">
        <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']?>">
        <input type="hidden" name="langx" value="<?php echo $langx?>">
        <input type="hidden" name="payid" id="payid" value="">


        <div class="content">
            <div class="goBack" onclick="javascript:history.back(-1);">返回上一页</div>

            <br><p style="">微信第三方：</p><br>
            <div class="type_third">
                <label >支付渠道:</label>
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
            </div>

            <div class="pay_money">
                <label for="money">存入金额:</label>
                <input type="text" class="order_amount" name="order_amount" id="order_amount" value="" onkeyup="clearNoNum(this)">
                <span class="tip"> <span class="left_s"><i class="caret"></i></span> 单笔限额存款为：<em><span id="min_money" class="min_money"><?php echo bcmul(floatval($v['minCurrency']), 1); ?></span> -<span id="max_money" class="max_money"><?php echo bcmul(floatval($v['maxCurrency']), 1) ?></span></em>元 </span>

            </div>
            <div id="third_money">
                <p style=" color: red; font-size: 14px;margin-left: 60px;top: 21px; line-height: 25px;">
                    特别声明：本渠道同一时间存款过多会导致存款失败，请多尝试几次！

                    <br><br>※ 无需手续费 支付完成 立即到账
                    <br>※ 支付成功后，请等待1分钟到3分钟，提示「入账成功」后再关闭支付窗口，刷新游戏平台款项会即时充值到账户中。
                    <br>※ 如遇任何存款问题请联系7*24小时在线客服咨询。
                </p>
            </div>
            <div class="btnbox">
                <input type="button" class="transbtn2 online-pay-back" onclick="javascript:history.back();" value="返回" >
                <input type="submit" class="transbtn trans_btn_2"  value="申请存款" >
            </div>
        </div>
    </form>
</div>

<!-- 第三方支付切换页面 结束-->


<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript">
  var u_id = <?php echo '\''.$uid.'\'' ?> ;
  var u_lang = <?php echo '\''.$langx.'\'' ?> ;
  var u_type = <?php echo '\''.$showtype.'\'' ?> ;
  var u_name = <?php echo '\''.$username.'\'' ?> ;
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
      $("#form1").attr("action",ourl[val]); // url
      $("#payid").val(oPid[val]);
      $("#min_money").html(oMinCurrency[val]);
      $("#max_money").html(oMaxCurrency[val]);

  }

        // 在线支付提交申请存款
        function savePayAction() {
            var $title=$("#select_bank").find("option:selected").text();
            var $bank = $('input[name="bankPayId"]') ;
            var val = $('.order_amount').val() ; // 充值金额
            var minval = parseInt($('.min_money').html()) ; // 最小金额
            var maxval = parseInt($('.max_money').html()) ; // 最大金额
            var num =0 ; // 是否有选择银行

            if($bank.length > 0){ // 如果是在线支付，有银行列表
                $bank.each(function () {
                    if(this.checked){
                        num += 1 ;
                    }
                }) ;
                if(num < 1){
                    alert('请选择存款银行！');
                    return false ;
                }
            }

            if(val =='' || !val ){
                alert('请输入正确的存款金额！');
                return false ;
            }
            if(val <minval ){
                alert('最低存款金额为'+minval+'元!');
                return false ;
            }
            if( val>maxval ){
                alert('最高存款金额为'+maxval+'元!');
                return false ;
            }
            paySuccessAlert($title,u_id,u_lang,u_name,val,m_date,m_date) ;

        }

        // 支付成功弹窗, title 标题，uid 用户id ,lang 语言， username 用户名，mon 充值金额
    function paySuccessAlert(title,uid,lang,username,mon,date_start,date_end) {
        var str = '<div id="pay_box" >\n'+
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
            '<a target="body" href="../onlinepay/record.php?uid='+uid+'&langx='+lang+'&username='+username+'&thistype=S&date_start='+date_start+'&date_end='+date_end+'" class="btn-blue button">查看交易</a>\n'+
            '<a target="_blank" href="'+top.configbase.service_meiqia+'" class="btn-blue button">联系客服</a>\n' +
            '</div>\n' +
            '</div>\n' +
            '</div>\n' +
            '</div>\n' +
            '</div>\n' +
            '</div>' ;
        jBox.confirm(str , title , {
            id: 'creditsChangeBank',
            showScrolling: false,
           // buttons: {'提交更换': true, '取消更换': false}
        });

    }


</script>

</body>
</html>
