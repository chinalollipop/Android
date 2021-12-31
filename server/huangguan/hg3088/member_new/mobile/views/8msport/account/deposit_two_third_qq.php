<?php
// 第三方QQ存款
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
// 第三方支付
$sSql = "SELECT id,thirdpay_code,url,minCurrency,maxCurrency,title FROM `".DBPREFIX."gxfcy_pay` WHERE `account_company` = 6 AND `depositNum` <= $depositTimes AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
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
$aUrl = [];
$aMinCurrency = [];
$aMaxCurrency = [];
foreach ($aData as $k => $v){
    $aPid[$k] = $v['id'];
    $aMinCurrency[$k] = bcmul(floatval($v['minCurrency']) , 1);
    $aMaxCurrency[$k] = bcmul(floatval($v['maxCurrency']) , 1);
    if ( $v['thirdpay_code'] == 'sf' ){ // 闪付QQ
        $aUrl[$k] =  $v['url'].'/sfpay.php';
    }

    if ( $v['thirdpay_code'] == 'rx' ){  // 仁信QQ
        $aUrl[$k] =  $v['url'].'/rxpay.php';
    }
    if ( $v['thirdpay_code'] == 'zb' ){ // 众宝
        $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/zhongbaopay.php';
    }
}

if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {

    $status = '200';
    $describe = 'success';

    foreach ($aData as $k =>$v){
        $aData[$k]['userid']=$_SESSION['userid'];
        $aData[$k]['url'] = $aUrl[$k];
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
    <!--<link href="../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
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
    <div class="content-center deposit-two deposit-different">

        <!-- 在线支付开始 -->
        <div class="" data-area="third_ali_pay">
            <form method="post" name="third_ali_pay_form" id="third_ali_pay_form" action="" target="_self">
                <div class="bg_yy">
                    <div class="tip_title"><span class="linear-color-1">2</span>填写存款金额</div>
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
                    <div class="form-item">
                        <span class="label clearfix" >
                            <span class="text">充值金额</span>
                            <span class="line"></span>
                        </span>
                        <span class="textbox">
                            <input class="deposit-input money-textbox" name="order_amount"  id="third_ali_pay_amount" type="text"  placeholder="请输入汇款金额" />
                            <a class="textbox-close" href="javascript:;">╳</a>
                        </span>
                    </div>
                </div>
                <div class="bg_yy">
                    <div class="tip_title"><span class="linear-color-1">3</span>选择支付渠道</div>
                    <div class="form-item form-select">
                        <span class="label">
                            <span class="text">支付渠道</span>
                            <span class="line"></span>
                        </span>

                            <span class="textbox dropdown">
                            <select name="pid" id="select_bank"  onchange="getBanklist(this)">
                                <option value="">请选择支付渠道</option>
                                <?php foreach ($aData as $k => $v){ ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v['title']; ?></option>
                                <?php } ?>
                            </select>
                        </span>
                    </div>
                </div>
                <div class="btn-wrap">
                    <a href="javascript:;" class="zx_submit" onclick="checkThird_ali_pay()">申请存款</a>
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


    <!-- 底部 -->
    <div id="footer">

    </div>
</div>
<script type="text/javascript" src="../../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../../js/animate.js"></script>
<script type="text/javascript" src="../../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/icalendar.min.js"></script>
<script type="text/javascript" src="../../../js/layer/mobile/layer.js"></script>

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
    var checkedMoney = '',
        $reset = $('.reset') ;
    function getBanklist(obj){

        // 银行卡列表json结构数据
        var ourl = <?php echo json_encode($aUrl); ?>;
        var oPid = <?php echo json_encode($aPid); ?>;
        var oMinCurrency = <?php echo json_encode($aMinCurrency); ?>;
        var oMaxCurrency = <?php echo json_encode($aMaxCurrency); ?>;

        // 获得渠道下拉框的对象与key值
        var val = obj.value ;

        $("#third_ali_pay_form").attr("action",ourl[val]); // url
        $("#payid").val(oPid[val])
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

    // 闪付支付宝验证
    function checkThird_ali_pay() {
        var mon = $('#third_ali_pay_amount').val() ;
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

        window.third_ali_pay_form.submit() ;
    }
    setLoginHeaderAction('QQ支付','','',usermon,uid) ;
    chooseAction(checkedMoney) ;
    deleteMoney(checkedMoney) ;

    setFooterAction(uid);
    addServerUrl() ;

    var depositNum = '<?php echo $membermessage['mcou']?>' ; // 是否有会员信息
    var depositMsg = '<?php echo $membermessage['mem_message']?>' ; // 会员信息
    // 弹窗信息
    if(depositNum>0){ // 有弹窗短信
        alert(depositMsg);
       /* layer.open({
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
