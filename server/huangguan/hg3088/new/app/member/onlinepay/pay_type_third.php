<?php

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
$iPayid = $_REQUEST['payid'];
$sPaycode = $_REQUEST['paycode'];
// 默认查询当天的数据
$m_date=date('Y-m-d');
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$username = $_SESSION['UserName'] ;
// 第三方支付
$sSql = "SELECT url FROM `".DBPREFIX."gxfcy_pay` WHERE `id` = '. $iPayid .' AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class) limit 1";
$oRes = mysqli_query($dbMasterLink,$sSql);

if( $aRow = mysqli_fetch_array($oRes)){
    switch ($iPayid){
        case 65:
            $sUrl = $aRow['url'].'/yeepay.php';
            $aBanklist = array(
                'ICBC-NET-B2C'=>'工商银行','CMBCHINA-NET-B2C'=>'招商银行','CCB-NET-B2C'=>'建设银行','BOCO-NET-B2C'=>'交通银行[借]','CIB-NET-B2C'=>'兴业银行','CMBC-NET-B2C'=>'中国民生银行','CEB-NET-B2C'=>'光大银行','BOC-NET-B2C'=>'中国银行','PINGANBANK-NET-B2C'=>'平安银行','ECITIC-NET-B2C'=>'中信银行','SDB-NET-B2C'=>'深圳发展银行','GDB-NET-B2C'=>'广发银行','SHB-NET-B2C'=>'上海银行','SPDB-NET-B2C'=>'上海浦东发展银行','HXB-NET-B2C'=>'华夏银行「借」','BCCB-NET-B2C'=>'北京银行','ABC-NET-B2C'=>'中国农业银行','POST-NET-B2C'=>'中国邮政储蓄银行「借」','BJRCB-NET-B2C'=>'北京农村商业银行「借」-暂不可用',
            ); break;
        case 11:
            $sUrl = $aRow['url'].'/sfpay.php';
            $aBanklist = array(
                '3001'=>'招商银行（借）','3002'=>'中国工商银行（借）','3003'=>'中国建设银行（借）','3004'=>'上海浦东发展银行（借）','3005'=>'中国农业银行（借）','3006'=>'中国民生银行（借）','3009'=>'兴业银行（借）','3020'=>'中国交通银行（借）','3022'=>'中国光大银行（借）','3026'=>'中国银行（借）','3032'=>'北京银行（借）','3035'=>'平安银行（借）','3036'=>'广发银行|CGB（借）','3037'=>'上海农商银行（借）','3038'=>'中国邮政储蓄银行（借）','3039'=>'中信银行（借）','3050'=>'华夏银行（借）','3059'=>'上海银行（借）','3060'=>'北京农商银行（借）',
            ); break;
        case 12: $sUrl = $aRow['url'].'/sfpay.php'; break;
        case 13: $sUrl = $aRow['url'].'/sfpay.php'; break;
        case 68: $sUrl = $aRow['url'].'/fktpay.php';break;
        default: break;
    }
}else{
    echo '支付方式有误，请重新选择~！';
    die;
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

    <form method="post" action="<?php echo $sUrl?>" target="_blank" onsubmit="return savePayAction()">
        <input type="hidden" name="uid" value="<?php echo $uid?>">
        <input type="hidden" name="langx" value="<?php echo $langx?>">
        <input type="hidden" name="payid" value="<?php echo $iPayid?>">


        <div class="content">
            <div class="goBack" onclick="javascript:history.back(-1);">返回上一页</div>

            <div id="bankList">

                <?php
                if(!empty($aBanklist)){
                    ?>
                    <h1><span style="font-size: 16px; color:#8b1306;">在线充值—</span>请选择银行：</h1><br>
                <?php
                    foreach ($aBanklist as $key => $value){
                ?>
                        <li><input type="radio" name="bankPayId" value="<?php echo $key?>" ><?php echo $value?></li>

                <?php }
                }else{
                ?>
                    <h1><span style="font-size: 16px; color:#8b1306;">在线充值—</span>请输入金额：</h1>
                <?php
                }
                ?>

            </div>
            <div class="pay_money">
                <label for="money">存入金额:</label>
                <input type="text" class="order_amount" name="order_amount" id="order_amount" value="" onkeyup="clearNoNum(this)">
                <span class="tip"> <span class="left_s"><i class="caret"></i></span> 单笔限额存款为：<em><span class="min_money">100</span> -<span class="max_money">3000</span></em>元 </span>

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

        // 在线支付提交申请存款
        function savePayAction() {
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

            if(val =='' || val <minval || val>maxval ){
                alert('请输入正确的存款金额！');
                return false ;
            }
            paySuccessAlert('在线支付',u_id,u_lang,u_name,val,m_date,m_date) ;

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
            '<a target="_blank" href="'+configbase.onlineserve+'" class="btn-blue button">联系客服</a>\n' +
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
