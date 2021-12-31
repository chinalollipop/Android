<?php
// 第三方银行存款
// 输入金额，跳转第三方或者添加记录

include_once('../../../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
        $status = '401.1';
        $describe = '请重新登录!';
        original_phone_request_response($status,$describe);
    }else {
        echo "<script>alert('请重新登录!');window.location.href='../login.php';</script>";
        exit;
    }
}
$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$username = $_SESSION['UserName'];
$depositTimes = $_SESSION['DepositTimes']; //会员存款次数
// 第三方银行支付
$sSql = "SELECT id,thirdpay_code,url,minCurrency,maxCurrency,title FROM `".DBPREFIX."gxfcy_pay` WHERE `account_company` = 2 AND `depositNum` <= $depositTimes AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$oRes = mysqli_query($dbLink,$sSql);
$iCou=mysqli_num_rows($oRes);
if( $iCou==0 ){
    if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
        $status = '401.2';
        $describe = '支付方式有误，请重新选择~！';
        original_phone_request_response($status,$describe);
    }else {
        echo "<script>alert('支付方式有误，请重新选择~！');history.go(-1)</script>";
        exit;
    }
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
foreach ($aData as $k => $v){
    $aPid[$k] = $v['id'];
    $aMinCurrency[$k] = bcmul(floatval($v['minCurrency']) , 1);
    $aMaxCurrency[$k] = bcmul(floatval($v['maxCurrency']) , 1);
    switch ( $v['thirdpay_code']){

        case 'sf':
            $aUrl[$k] =  $v['url'].'/sfpay.php';
            $aBanklist[$k] = array(
                '3001'=>'招商银行（借）','3002'=>'中国工商银行（借）','3003'=>'中国建设银行（借）','3004'=>'上海浦东发展银行（借）','3005'=>'中国农业银行（借）','3006'=>'中国民生银行（借）','3009'=>'兴业银行（借）','3020'=>'中国交通银行（借）','3022'=>'中国光大银行（借）','3026'=>'中国银行（借）','3032'=>'北京银行（借）','3035'=>'平安银行（借）','3036'=>'广发银行|CGB（借）','3037'=>'上海农商银行（借）','3038'=>'中国邮政储蓄银行（借）','3039'=>'中信银行（借）','3050'=>'华夏银行（借）','3059'=>'上海银行（借）','3060'=>'北京农商银行（借）',
            );
            break;

        case 'yb':
            $aUrl[$k] =  $v['url'].'/yeepay.php';
            $aBanklist[$k] = array(
                'ICBC-NET-B2C'=>'工商银行','CMBCHINA-NET-B2C'=>'招商银行','CCB-NET-B2C'=>'建设银行','BOCO-NET-B2C'=>'交通银行[借]','CIB-NET-B2C'=>'兴业银行','CMBC-NET-B2C'=>'中国民生银行','CEB-NET-B2C'=>'光大银行','BOC-NET-B2C'=>'中国银行','PINGANBANK-NET-B2C'=>'平安银行','ECITIC-NET-B2C'=>'中信银行','SDB-NET-B2C'=>'深圳发展银行','GDB-NET-B2C'=>'广发银行','SHB-NET-B2C'=>'上海银行','SPDB-NET-B2C'=>'上海浦东发展银行','HXB-NET-B2C'=>'华夏银行「借」','BCCB-NET-B2C'=>'北京银行','ABC-NET-B2C'=>'中国农业银行','POST-NET-B2C'=>'中国邮政储蓄银行「借」','BJRCB-NET-B2C'=>'北京农村商业银行「借」-暂不可用',
            );
            break;

        case 'rx':// 仁信
            $aUrl[$k] = $v['url'] . '/rxpay.php';
            $aBanklist[$k] = array(
                'ICBC'=>'工商银行','ABC'=>'农业银行','CCB'=>'建设银行','BOC'=>'中国银行','CMB'=>'招商银行','BCCB'=>'北京银行','BOCO'=>'交通银行','CIB'=>'兴业银行','NJCB'=>'南京银行','CMBC'=>'民生银行','CEB'=>'光大银行','PINGANBANK'=>'平安银行','CBHB'=>'渤海银行','HKBEA'=>'东亚银行','NBCB'=>'宁波银行','CTTIC'=>'中信银行','GDB'=>'广发银行','SHB'=>'上海银行','SPDB'=>'上海浦东发展银行','PSBS'=>'中国邮政','HXB'=>'华夏银行','BJRCB'=>'北京农村商业银行','SRCB'=>'上海农商银行','SDB'=>'深圳发展银行','CZB'=>'浙江稠州商业银行',
            );
            break;

        case 'fkt': //福卡通
            $aUrl[$k] = $v['url'] . '/fktpay.php';
            $aBanklist[$k] = array(
                'ABC' => '中国农业银行', 'BOC' => '中国银行','BOCOM' => '交通银行', 'CCB' => '中国建设银行', 'ICBC' => '中国工商银行','PSBC' => '中国邮政储蓄银行', 'CMBC' => '招商银行','SPDB' => '浦发银行', 'CEBBANK' => '中国光大银行','ECITIC' => '中信银行','PINGAN' => '平安银行', 'CMBCS' => '中国民生银行', 'HXB' => '华夏银行', 'CGB' => '广发银行','BCCB' => '北京银行','BOS' => '上海银行','CIB' => '兴业银行',
            );
            break;

        case 'sft': //顺付通
                $aUrl[$k] = $v['url'] . '/sftpay.php';
                $aBanklist[$k] = array(
                    'ABC' => '中国农业银行','BCCB' => '北京银行','CCB' => '中国建设银行','CEB' => '中国光大银行','CMB' => '招商银行','ICBC' => '中国工商银行','PSBC' => '中国邮政储蓄银行','BOC' => '中国银行','COMM' => '交通银行','SPDB' => '浦发银行','CNCB' => '中信银行','PAB' => '平安银行','CMBC' => '中国民生银行','HXB' => '华夏银行','BOS' => '上海银行','CIB' => '兴业银行','CBHB' => '渤海银行','GDB' => '广发银行',
                );
            break;

        case 'db': // 得宝
                $aUrl[$k] = $v['url'] . '/dbpay.php';
                $aBanklist[$k] = array(
                    'ABC'  => '农业银行','ICBC' => '工商银行','CCB'  => '建设银行','BCOM' => '交通银行','BOC'  => '中国银行','CMB'  => '招商银行','CMBC' => '民生银行','CEBB' => '光大银行','BOB'  => '北京银行','SHB'  => '上海银行','NBB'  => '宁波银行','HXB' => '华夏银行','CIB'  => '兴业银行','PSBC'  => '中国邮政银行','SPABANK' => '平安银行','SPDB'  => '浦发银行','ECITIC'  => '中信银行','HZB'  => '杭州银行','GDB'  => '广发银行',
                );
            break;
        case 'zrb': // 智融宝
            $aUrl[$k] = $v['url'] . '/zrbpay.php';
            $aBanklist[$k] = array(
                'BOC'  => '中国银行','ICBC' => '工商银行','CCB'  => '建设银行','CMBCHINA'  => '招商银行','GDB'  => '广发银行','POST'  => '中国邮政','ABC'  => '农业银行','CMBC' => '中国民生银行','CEB' => '光大银行','BOCO' => '交通银行',
            );
            break;
        case 'xft': // 信付通
            $aUrl[$k] = $v['url'] . '/xftpay.php';
            $aBanklist[$k] = array(
                'CMB' => '招商银行','ICBC' => '工商银行','CCB' => '建设银行','BOC' => '中国银行','ABC' => '农业银行','BOCM' => '交通银行','SPDB' => '浦发银行','CGB' => '广发银行','CITIC' => '中信银行','CEB' => '光大银行','CIB' => '兴业银行','PAYH' => '平安银行','CMBC' => '民生银行','HXB' => '华夏银行','PSBC' => '邮储银行','BCCB' => '北京银行','SHBANK' => '上海银行','WXPAY' => '微信支付','ALIPAY' => '支付宝支付', 'QQPAY' => 'QQ扫码','JDPAY' => '京东扫码','QUICKPAY' => '快捷支付','UNIONPAY' => '中国银联','BDPAY' => '百度钱包','UNIONQRPAY' => '银联扫码',
            );
            break;
        case 'zb': // 众宝
            $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/zhongbaopay.php';
            $aBanklist[$k] = array(
                '962'=>'中信银行','963'=>'中国银行','964'=>'农业银行','965'=>'建设银行','967'=>'工商银行','970'=>'招商银行','971'=>'邮储银行','972'=>'兴业银行','977'=>'浦发银行','979'=>'南京银行','980'=>'民生银行','981'=>'交通银行','983'=>'杭州银行','985'=>'广发银行','986'=>'光大银行','987'=>'东亚银行','989'=>'北京银行','990'=>'平安银行','991'=>'华夏银行','992'=>'上海银行','1000'=>'微信扫码','1002'=>'微信直连','1003'=>'支付宝扫码','1004'=>'支付宝直连','1005'=>'QQ钱包扫码','1006'=>'QQ钱包直连','1007'=>'京东钱包扫码','1008'=>'京东钱包直连','1009'=>'银联扫码',
            );
            break;
    }
}

if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {

    $status = '200';
    $describe = 'success';

    $aBanklist2 = array(); // 声明新的银行列表给原生接口
    foreach ($aBanklist as $k =>$v){

        foreach ($v as $k2 => $v2){
            $aBanklist2[$k][$k2]['bankcode'] = $k2;
            $aBanklist2[$k][$k2]['bankname'] = $v2;
        }
        $aBanklist2[$k] = array_values($aBanklist2[$k]); // 去掉key
    }
    $aBanklist = array(); // 注销旧的银行列表

    foreach ($aData as $k =>$v){
        $aData[$k]['userid']=$_SESSION['userid'];
        $aData[$k]['url'] = $aUrl[$k];
        $aData[$k]['bankList'] = $aBanklist2[$k];
    }

    original_phone_request_response($status,$describe,$aData);

}
else{

    $membermessage = getMemberMessage($username,'1'); // 存款短信

?>
<html class="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="../../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="../../../style/icalendar.css?v=<?php echo AUTOVER; ?>">

    <title class="web-title"></title>
<style type="text/css">

</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->
    <div class="content-center deposit-two">
      <!--  <div class="top-deposit">
            <div class="login-logo"></div>
            <h2 class="web-title"></h2>
        </div>-->
        <!-- 在线支付开始 -->
        <div class="" data-area="third_bank_pay">
            <form method="post" name="onlinepay" id="onlinepay" action="" target="_self">
                <div class="form">
                <div class="form-item">
                        <span class="label clearfix" id="test">
                            <span class="text">充值金额</span>
                            <span class="line"></span>
                        </span>
                    <span class="textbox">
                            <input class="deposit-input money-textbox" name="order_amount" type="number"  placeholder="请输入汇款金额" /> <!-- id="money-textbox1" -->
                            <a class="textbox-close" href="javascript:;">╳</a>
                        </span>
                </div>
                <div class="tip error hide">
                    <span class="icon"></span>
                    <span class="text">必填</span>
                </div>
                <table class="money moneychoose" >

                    <tbody>
                    <tr>
                        <td><span>100</span></td>
                        <td><span>300</span></td>
                        <td><span>500</span></td>
                        <td><span>800</span></td>
                    </tr>
                    <tr>
                        <td><span>1000</span></td>
                        <td><span>2000</span></td>
                        <td><span>3000</span></td>
                        <td><span>5000</span></td>
                    </tr>
                    </tbody>
                </table>
                <div class="form-item form-select">
                    <span class="label">
                        <span class="text">支付渠道</span>
                        <span class="line"></span>
                    </span>

                        <span class="dropdown">
                        <select name="pid" id="select_bank"  onchange="getBanklist(this)">
                            <option value="">请选择支付渠道</option>
                            <?php foreach ($aData as $k => $v){ ?>
                                <option value="<?php echo $k; ?>"><?php echo $v['title']; ?></option>
                            <?php } ?>
                        </select>
                    </span>
                </div>
                <div class="form-item form-select" id="bankList_container" style="display: none;">
                    <span class="label">
                        <span class="text">转入银行</span>
                        <span class="line"></span>
                    </span>

                    <span class="dropdown">
                        <select name="banklist" id="onlineIntoBank">
                            <option value="">请选择转入银行</option>

                        </select>
                    </span>
                </div>
                <div class="tip error">
                    <span class="icon"></span>
                    <span class="text">必填</span>
                </div>
                <div class="btn-wrap">
                    <a href="javascript:;" class="zx_submit" onclick="checkOnlinePay()">申请存款</a>
                    <a href="javascript:;" class="zx_submit btn-reg reset" >重置金额</a> <!--  id="reset1" -->
                </div>

            </div>
                <input type="hidden" name="uid" value="<?php echo $uid;?>"/>
                <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']?>">
                <input type="hidden" name="payid" id="payid" value=""/>
                <input type="hidden" name="min_money" id="min_money" value="">
                <input type="hidden" name="max_money" id="max_money" value="">
            </form>
        </div>

        <!-- 公用 联系我们-->
        <div class="contact-us">

        </div>

    </div>

    <!-- 底部 -->
    <div id="footer"> 

    </div>
</div>
<script type="text/javascript" src="../../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../../js/animate.js"></script>
<script type="text/javascript" src="../../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/icalendar.min.js"></script>
<!--<script type="text/javascript" src="../../../js/layer/mobile/layer.js"></script>-->

<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    var alerttitle ={ // 配置提示信息
        int:'汇款金额必须为数字(最多两位小数)！' ,
        mon:'汇款金额不能小于100元！' ,
        bank:'请输入转入银行！' ,
        banktype:'请选择汇款方式！' ,
        time:'请选择汇款时间！' ,
    }
    // 进来判断属于哪种支付方式才需要显示
    var urltype = getStrParam().type ; // 当前支付类型
    var urltitle = getStrParam().title ; // 支付方式名称

    var checkedMoney = '',
        $reset = $('.reset') ;

    function getBanklist(obj){

        // 银行卡列表json结构数据
        var pidPayID = <?php echo json_encode($aBanklist); ?>;
        var ourl = <?php echo json_encode($aUrl); ?>;
        var oPid = <?php echo json_encode($aPid); ?>;
        var oMinCurrency = <?php echo json_encode($aMinCurrency); ?>;
        var oMaxCurrency = <?php echo json_encode($aMaxCurrency); ?>;

        // 获得渠道下拉框的对象与key值
        var val = obj.value ;

        //获得银行列表下拉框的对象
        var $banklist=document.onlinepay.onlineIntoBank;
        var str ='' ;
        $.each(pidPayID[val], function (i,v) {
            str +='<option value="'+i+'">'+v+'</option>' ;

        }) ;

        // 渲染数据
        $banklist.innerHTML=str ;
        $("#onlinepay").attr("action",ourl[val]); // url
        $("#bankList_container").css("display", "block"); // 显示银行卡列表
        $("#payid").val(oPid[val]);
        $("#min_money").val(oMinCurrency[val]); // 最小额
        $("#max_money").val(oMaxCurrency[val]); // 最小额

    }
    function chooseTypeAction() {
        $('.pay-type').each(function () {
            var paytype = $(this).data('area') ;
            //console.log(urltype) ;
            if(urltype == paytype){
                $(this).removeClass('hide-cont') ;
            }
        }) ;

    }

    // 重置金额
    $reset.click(function() {
        $('.textbox-close').click() ;
    });

    // 在线支付验证
    function checkOnlinePay() {
        var mon = $('.money-textbox').val() ;  //string
        var tmp_min_money = $('#min_money').val();
        var max_money = $('#max_money').val();
        if(tmp_min_money) {
            min_money = $('input[name=min_money]').val();  //string
        } else {
            min_money = 100; //number
        }

        var flag = true ;
        if(!checkInputFloat(mon)){
            setPublicPop(alerttitle.int);
            flag = false ;
            return false;
        }
        if(mon < parseInt(min_money)) {
            setPublicPop("汇款金额不能小于"+ min_money +"元");
            flag = false ;
            return false;
        }
        if(mon > parseInt(max_money)) {
            setPublicPop("汇款金额不能大于"+ max_money +"元");
            flag = false ;
            return false;
        }
        if($('#onlineIntoBank').val() ==''){
            setPublicPop(alerttitle.bank);
            flag = false ;
            return false;
        }
        window.onlinepay.submit() ;
   }
    setLoginHeaderAction('银行卡线上','','',usermon,uid) ;
    chooseAction(checkedMoney) ;
    deleteMoney(checkedMoney) ;
    // chooseTypeAction() ;
    setPublicContact() ;
    addServerUrl() ;
    setFooterAction(uid);

    var depositNum = '<?php echo $membermessage['mcou']?>' ; // 是否有会员信息
    var depositMsg = '<?php echo $membermessage['mem_message']?>' ; // 会员信息
    // 弹窗信息
    if(depositNum>0){ // 有弹窗短信
        alert(depositMsg);
        /*layer.open({
            content: depositMsg
            ,btn: '确定'
        });*/
    }

</script>

</body>
</html>
<?php
}
?>
