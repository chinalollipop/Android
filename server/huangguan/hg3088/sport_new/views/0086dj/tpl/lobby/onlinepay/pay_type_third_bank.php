<?php
/**
 * 使用第三方银行卡存款
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
$sSql = "SELECT * FROM `".DBPREFIX."gxfcy_pay` WHERE `account_company` = 2 AND `status` = 1 AND `depositNum` <= $depositTimes AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$oRes = mysqli_query($dbLink,$sSql);
$iCou=mysqli_num_rows($oRes);

if( $iCou == 0 ){
    echo "<script>alert('支付方式有误，请重新选择~！');</script>";
}
$aData = [];
while($aRow = mysqli_fetch_assoc($oRes)){
	$aData[]=$aRow;
}

$aPid = [];
$aBanklist = [];
$aUrl = [];
$aMinCurrency = [];
$aMaxCurrency = [];
foreach ($aData as $k => $v) {
    $aPid[$k] = $v['id'];
    $aMinCurrency[$k] = bcmul(floatval($v['minCurrency']) , 1);
    $aMaxCurrency[$k] = bcmul(floatval($v['maxCurrency']) , 1);

    if ($v['thirdpay_code'] == 'sf') { //闪付
        $aUrl[$k] = $v['url'] . '/sfpay.php';
        $aBanklist[$k] = array(
            '3001' => '招商银行（借）', '3002' => '中国工商银行（借）', '3003' => '中国建设银行（借）', '3004' => '上海浦东发展银行（借）', '3005' => '中国农业银行（借）', '3006' => '中国民生银行（借）', '3009' => '兴业银行（借）', '3020' => '中国交通银行（借）', '3022' => '中国光大银行（借）', '3026' => '中国银行（借）', '3032' => '北京银行（借）', '3035' => '平安银行（借）', '3036' => '广发银行|CGB（借）', '3037' => '上海农商银行（借）', '3038' => '中国邮政储蓄银行（借）', '3039' => '中信银行（借）', '3050' => '华夏银行（借）', '3059' => '上海银行（借）', '3060' => '北京农商银行（借）',
        );
    }

    if ($v['thirdpay_code'] == 'yb') { // 易宝
        $aUrl[$k] = $v['url'] . '/yeepay.php';
        $aBanklist[$k] = array(
            'ICBC-NET-B2C' => '工商银行', 'CMBCHINA-NET-B2C' => '招商银行', 'CCB-NET-B2C' => '建设银行', 'BOCO-NET-B2C' => '交通银行[借]', 'CIB-NET-B2C' => '兴业银行', 'CMBC-NET-B2C' => '中国民生银行', 'CEB-NET-B2C' => '光大银行', 'BOC-NET-B2C' => '中国银行', 'PINGANBANK-NET-B2C' => '平安银行', 'ECITIC-NET-B2C' => '中信银行', 'SDB-NET-B2C' => '深圳发展银行', 'GDB-NET-B2C' => '广发银行', 'SHB-NET-B2C' => '上海银行', 'SPDB-NET-B2C' => '上海浦东发展银行', 'HXB-NET-B2C' => '华夏银行「借」', 'BCCB-NET-B2C' => '北京银行', 'ABC-NET-B2C' => '中国农业银行', 'POST-NET-B2C' => '中国邮政储蓄银行「借」', 'BJRCB-NET-B2C' => '北京农村商业银行「借」-暂不可用',
        );
    }

    if ($v['thirdpay_code'] == 'rx') { // 仁信
        $aUrl[$k] = $v['url'] . '/rxpay.php';
        $aBanklist[$k] = array(
            'ICBC'=>'工商银行','ABC'=>'农业银行','CCB'=>'建设银行','BOC'=>'中国银行','CMB'=>'招商银行','BCCB'=>'北京银行','BOCO'=>'交通银行','CIB'=>'兴业银行','NJCB'=>'南京银行','CMBC'=>'民生银行','CEB'=>'光大银行','PINGANBANK'=>'平安银行','CBHB'=>'渤海银行','HKBEA'=>'东亚银行','NBCB'=>'宁波银行','CTTIC'=>'中信银行','GDB'=>'广发银行','SHB'=>'上海银行','SPDB'=>'上海浦东发展银行','PSBS'=>'中国邮政','HXB'=>'华夏银行','BJRCB'=>'北京农村商业银行','SRCB'=>'上海农商银行','SDB'=>'深圳发展银行','CZB'=>'浙江稠州商业银行',
        );
    }

    if ($v['thirdpay_code'] == 'fkt') { //福卡通
        $aUrl[$k] = $v['url'] . '/fktpay.php';
        $aBanklist[$k] = array(
            'ABC' => '中国农业银行', 'BOC' => '中国银行','BOCOM' => '交通银行', 'CCB' => '中国建设银行', 'ICBC' => '中国工商银行','PSBC' => '中国邮政储蓄银行', 'CMBC' => '招商银行','SPDB' => '浦发银行', 'CEBBANK' => '中国光大银行','ECITIC' => '中信银行','PINGAN' => '平安银行', 'CMBCS' => '中国民生银行', 'HXB' => '华夏银行', 'CGB' => '广发银行','BCCB' => '北京银行','BOS' => '上海银行','CIB' => '兴业银行',
        );
    }

    if ($v['thirdpay_code'] == 'sft') { //顺付通
        $aUrl[$k] = $v['url'] . '/sftpay.php';
        $aBanklist[$k] = array(
            'ABC' => '中国农业银行','BCCB' => '北京银行','CCB' => '中国建设银行','CEB' => '中国光大银行','CMB' => '招商银行','ICBC' => '中国工商银行','PSBC' => '中国邮政储蓄银行','BOC' => '中国银行','COMM' => '交通银行','SPDB' => '浦发银行','CNCB' => '中信银行','PAB' => '平安银行','CMBC' => '中国民生银行','HXB' => '华夏银行','BOS' => '上海银行','CIB' => '兴业银行','CBHB' => '渤海银行','GDB' => '广发银行',
        );
    }

    if($v['thirdpay_code'] == 'db') { // 得宝
        $aUrl[$k] = $v['url'] . '/dbpay.php';
        $aBanklist[$k] = array(
            'ABC'  => '农业银行','ICBC' => '工商银行','CCB'  => '建设银行','BCOM' => '交通银行','BOC'  => '中国银行','CMB'  => '招商银行','CMBC' => '民生银行','CEBB' => '光大银行','BOB'  => '北京银行','SHB'  => '上海银行','NBB'  => '宁波银行','HXB' => '华夏银行','CIB'  => '兴业银行','PSBC'  => '中国邮政银行','SPABANK' => '平安银行','SPDB'  => '浦发银行','ECITIC'  => '中信银行','HZB'  => '杭州银行','GDB'  => '广发银行',
        );
    }

    if($v['thirdpay_code'] == 'zrb') { // 智融宝
     $aUrl[$k] = $v['url'] . '/zrbpay.php';
        $aBanklist[$k] = array(
            'BOC'  => '中国银行','ICBC' => '工商银行','CCB'  => '建设银行','CMBCHINA'  => '招商银行','GDB'  => '广发银行','POST'  => '中国邮政','ABC'  => '农业银行','CMBC' => '中国民生银行','CEB' => '光大银行','BOCO' => '交通银行',
        );
    }

    if($v['thirdpay_code'] == 'xft') { // 信付通
        $aUrl[$k] = $v['url'] . '/xftpay.php';
        $aBanklist[$k] = array(
            'CMB' => '招商银行','ICBC' => '工商银行','CCB' => '建设银行','BOC' => '中国银行','ABC' => '农业银行','BOCM' => '交通银行','SPDB' => '浦发银行','CGB' => '广发银行','CITIC' => '中信银行','CEB' => '光大银行','CIB' => '兴业银行','PAYH' => '平安银行','CMBC' => '民生银行','HXB' => '华夏银行','PSBC' => '邮储银行','BCCB' => '北京银行','SHBANK' => '上海银行','WXPAY' => '微信支付','ALIPAY' => '支付宝支付', 'QQPAY' => 'QQ扫码','JDPAY' => '京东扫码','QUICKPAY' => '快捷支付','UNIONPAY' => '中国银联','BDPAY' => '百度钱包','UNIONQRPAY' => '银联扫码',
        );
    }

    if($v['thirdpay_code'] == 'huiyun') { // 慧云
        $aUrl[$k] = $v['url'] . '/huiyunpay.php';
        $aBanklist[$k] = array(
            'CNCB'=>'中信银行', 'BOCSH'=>'中国银行', 'ABC'=>'中国农业银行', 'CCB'=>'中国建设银行', 'ICBC'=>'中国工商银行', 'CMB'=>'招商银行', 'PSBC'=>'邮政储蓄', 'CIB'=>'兴业银行', 'SDB'=>'深圳发展银行', 'SPDB'=>'浦东发展银行', 'PAB'=>'平安银行', 'CMBC'=>'民生银行', 'BOCOM'=>'交通银行', 'GDB'=>'广东发展银行', 'CEB'=>'光大银行', 'BCCB'=>'北京银行',
        );
    }
    if($v['thirdpay_code'] == 'zb') { // 众宝
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/zhongbaopay.php';
        $aBanklist[$k] = array(
            '962'=>'中信银行','963'=>'中国银行','964'=>'农业银行','965'=>'建设银行','967'=>'工商银行','970'=>'招商银行','971'=>'邮储银行','972'=>'兴业银行','977'=>'浦发银行','979'=>'南京银行','980'=>'民生银行','981'=>'交通银行','983'=>'杭州银行','985'=>'广发银行','986'=>'光大银行','987'=>'东亚银行','989'=>'北京银行','990'=>'平安银行','991'=>'华夏银行','992'=>'上海银行','1000'=>'微信扫码','1002'=>'微信直连','1003'=>'支付宝扫码','1004'=>'支付宝直连','1005'=>'QQ钱包扫码','1006'=>'QQ钱包直连','1007'=>'京东钱包扫码','1008'=>'京东钱包直连','1009'=>'银联扫码',
        );
    }
    if($v['thirdpay_code'] == 'wdf') { // 维多付
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/weiduofupay.php';
        $aBanklist[$k] = array(
            //'w_union'=>'银联闪付','w_alibank'=>'阿里网关','w_alipayqr'=>'支付宝转支',/*'w_alipay'=>'支付宝转卡','w_alipayh5'=>'支付宝H5','w_wechat'=>'微信扫码','w_wechath5'=>'微信转卡',*/
            '1'=>'银联闪付','2'=>'阿里网关','3'=>'支付宝转支',/*'4'=>'支付宝转卡','5'=>'支付宝H5','6'=>'微信扫码','7'=>'微信转卡',*/
        );
    }
    if($v['thirdpay_code'] == 'clzldz') { // 村里最靓的仔
        $aUrl[$k] = $v['url'] . '/clzldzpay.php';
        $aBanklist[$k] = array(
            '920'=>'支付宝或网银',
        );
    }
    if($v['thirdpay_code'] == 'ccx') { // 璀璨星
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/ccxpay.php';
        //支付类型有：（0：支付宝转卡;1:微信扫码【无可用】；2：银联扫码；3：综合支付;4：微信转账【维护】；5：支付宝转账【维护】；6：手机银行转账;7：银联快捷【维护】;8：支付宝个码【至少1000元】；9: 支付宝wap2/支付宝H5【无可用】）
        $aBanklist[$k] = array(
            '0'=>'支付宝转卡','2'=>'银联扫码','3'=>'综合支付','4'=>'微信转卡','6'=>'手机银行转账','7'=>'银联快捷',
        );
    }
    if($v['thirdpay_code'] == 'csj') { // 创世纪
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/csjpay.php';
        // 支付类型 '0'=>'支付宝转卡','1'=>'微信扫码','2'=>'银联扫码','3'=>'网银支付','4'=>'微信转账','5'=>'支付宝转账','6'=>'手机银行转账','7'=>'银联快捷','8'=>'支付宝个码','9'=>'支付宝wap2/支付宝H5',
        $aBanklist[$k] = array(
            '0'=>'支付宝转卡','2'=>'银联扫码','3'=>'网银支付','8'=>'支付宝个码',
        );
    }

    if($v['thirdpay_code'] == 'autopay') { // autopay
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/AutoPaypay.php';
        $aBanklist[$k] = array(
            '5'=>'网银支付',
        );
    }

    if($v['thirdpay_code'] == 'xingchen') { // 星辰
        $url = $v['url'] . '/'.$v['thirdpay_code'] .'/xingchenpay.php';
        $aBanklist = array(
            'bank_transfer'=>'银行卡转账',
        );
    }

}
//print_r( $aBanklist ); die;


?>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/memberaccount.css?v=<?php echo AUTOVER; ?>" >
<style>
    .pay_box{margin:15px;padding:10px;border:1px solid #bb0b26;font-size:14px}
    .button-container a{display:inline-block;width:100px;height:40px;line-height:40px;text-align:center;color:#fff;background:#bb0b26;border-radius:5px;margin:20px}
    .bankList_container{display: none}
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
            <div class="payWayTit">支付宝支付</div>
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
                <tr class="bankList_container">
                    <td><span class="red">*</span>选择银行</td>
                    <td class="type_third" >
                        <select name="banklist" id="banklist_select"> </select>

                    </td>
                </tr>
                <tr>
                    <td><span class="red">*</span>存款金额</td>
                    <td>
                        <input type="number" step="0.01" class="fast_choose order_amount" name="order_amount" id="order_amount" value="" onkeyup="clearNoNum(this)" placeholder="请输入存款金额">
                        <span class="tip"> <span class="left_s"><i class="caret"></i></span> 单笔限额存款为：<em><span id="min_money" class="min_money"><?php echo bcmul(floatval($v['minCurrency']), 1); ?></span> -<span id="max_money" class="max_money"><?php echo bcmul(floatval($v['maxCurrency']), 1) ?></span></em>元 </span>
                    </td>
                </tr>
                <tr>
                    <td>快速设置金额</td>
                    <td class="moneyType">
                        <a href="javascript:change(100)" class="quickM">100</a>
                        <a href="javascript:change(500)" class="quickM">500</a>
                        <a href="javascript:change(1000)" class="quickM">1000</a>
                        <a href="javascript:change(5000)" class="quickM">5000</a>
                        <a href="javascript:change(10000)" class="quickM">10000</a>
                        <a href="javascript:change(50000)" class="quickM">50000</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p style=" color: red; font-size: 14px;margin-left: 60px;top: 21px; line-height: 25px;text-align: left;">
                            特别声明：本渠道同一时间存款过多会导致存款失败，请多尝试几次！

                            <br><br>※ 无需手续费 支付完成 立即到账
                            <br>※ 支付成功后，请等待1分钟到3分钟，提示「入账成功」后再关闭支付窗口，刷新游戏平台款项会即时充值到账户中。
                            <br>※ 如遇任何存款问题请联系7*24小时在线客服咨询。
                        </p>
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
      var pidPayID = <?php echo json_encode($aBanklist); ?>;
      var ourl = <?php echo json_encode($aUrl); ?>;
      var oPid = <?php echo json_encode($aPid); ?>;
      var oMinCurrency = <?php echo json_encode($aMinCurrency); ?>;
      var oMaxCurrency = <?php echo json_encode($aMaxCurrency); ?>;
      //获得渠道下拉框的对象
      // var $select_bank = document.getElementById('select_bank') ;
      var val = obj.value ;
      //获得银行列表下拉框的对象
      var $banklist=$('#banklist_select');

      var str ='' ;
      $.each(pidPayID[val], function (i,v) {
          str +='<option value="'+i+'">'+v+'</option>' ;
      }) ;

      // 渲染数据
      $banklist.html(str) ;
      $(".deposit_form").attr("action",ourl[val]); // url
      $(".bankList_container").css("display", "table-row"); // 显示银行卡列表
      $("#payid").val(oPid[val])
      $("#min_money").html(oMinCurrency[val]);
      $("#max_money").html(oMaxCurrency[val]);

  }

        // 在线支付提交申请存款
        function savePayAction() {
            var title=$("#select_bank").find("option:selected").text(); // 已选择的支付渠道
           // var $bank = $('input[name="bankPayId"]') ;
            var val = $('.order_amount').val() ; // 充值金额
            var minval = parseInt($('.min_money').html()) ; // 最小金额
            var maxval = parseInt($('.max_money').html()) ; // 最大金额
            if(title=='请选择'){
                layer.msg('请选择存款银行',{time:alertTime});
                return false ;
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

            var alstr = paySuccessAlert(title,val,m_date,m_date) ;
            top.alertcon = layer.open({
                type: 1,
                skin: 'layui-layer-login', //样式类名
                title: title,
                shadeClose: true,
                shade:0.5,
                area: ['360px', '270px'],
                content: alstr
            });

        }

        // 支付成功弹窗, title 标题，uid 用户id ,lang 语言， username 用户名，mon 充值金额
    function paySuccessAlert(title,mon,date_start,date_end) {
        var str = '<div class="pay_box"  >\n'+
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


