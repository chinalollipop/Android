<?php
session_start();
/**
 * 支付宝二维码。手工扫码付款
 */
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
$bankid=$_REQUEST['bankid'];
$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式
$sSql = "SELECT id,bank_user,photo_name,bank_user,maxmoney,notice FROM `".DBPREFIX."gxfcy_bank_data` WHERE `id`= '$bankid' AND `bankcode` = 'ALISAOMA' AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class) AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ";
$oRes = mysqli_query($dbLink,$sSql);
$iCou=mysqli_num_rows($oRes);

if( $iCou == 0 ){
    if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
        $status = '401.2';
        $describe = '支付方式有误，请重新选择~！';
        original_phone_request_response($status,$describe);
    }else {
        exit('支付方式有误，请重新选择~！');
    }
}
$aRow = mysqli_fetch_assoc($oRes);

if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
    $status = '200';
    $describe = 'success';
    original_phone_request_response($status,$describe,$aData);
}

//var_dump($aData); die;
$nowDate = date('Y-m-d H:i:s');

$membermessage = getMemberMessage($username,'1'); // 存款短信


?>
<html >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="../../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="../../../style/icalendar.css?v=<?php echo AUTOVER; ?>">
    <title class="web-title"></title>
    <style>
        .form-item .label {width: 35%;}
    </style>
</head>
<body >

<!-- 支付宝二维码扫码切换页面 开始-->
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>
    <!-- 中间内容 -->
    <div class="content-center deposit-two">

        <input type="hidden" name="payid" id="payid" value="<?php echo $aRow['id'];?>">
        <input type="hidden" name="bank_user" id="v_Name" value="<?php echo $aRow['bank_user'];?>">

        <div class="content_right">
            <dl class="saoma_source" >
                <dt>手机扫一扫，轻松支付</dt>
                <dd>
                    <img src="<?php echo $aRow['photo_name'];?>" /><br>
                </dd>
            </dl>
            <div class="saoma_notes">请不要使用整数进行存款否则无法成功，<br>请使用例如：101或者123等！</div>
        </div>
            <div class="form-item">
                <span class="label clearfix" id="test">
                    <span class="text">支付宝姓名</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input class="deposit-input " type="text" value="<?php echo $aRow['bank_user'];?>" readonly >
                </span>
            </div>
            <div class="tip error hide">
                <span class="icon"></span>
                <span class="text"></span>
            </div>
            <div class="form-item">
                <span class="label clearfix" id="test">
                    <span class="text">存入金额</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input class="deposit-input money-textbox" name="v_amount" type="number" placeholder="请输入汇款金额" >
                    <a class="textbox-close" href="javascript:;" onclick="$('[name=\'v_amount\']').val('')">╳</a>
                </span>
            </div>
            <div class="tip error hide">
                <span class="icon"></span>
                <span class="text">必填</span>
            </div>
            <div class="form-item">
                <span class="label clearfix" id="test">
                    <span class="text"><?php echo $aRow['notice']?></span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input class="deposit-input " name="memo" id="memo" type="text" placeholder="请输入<?php echo $aRow['notice']?>" >
                </span>
            </div>
            <div class="tip error hide">
                <span class="icon"></span>
                <span class="text">必填</span>
            </div>
            <div class="form-item">
                <span class="label clearfix" id="test">
                    <span class="text">汇款日期</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input class="deposit-input " name="cn_date" type="text" placeholder="请选择汇款日期" id="time_textbox" readonly >
                </span>
            </div>
            <div class="tip error hide">
                <span class="icon"></span>
                <span class="text">必填</span>
            </div>
            <div class="btn-wrap">
                <a href="javascript:;" class="zx_submit" onclick="depositeBankAction()">确认存款</a>
            </div>
        <p style="text-align: left;">支付宝转帐时，请使用您本人支付宝帐号；转帐金额与您申请时填写的金额保持一致，会加快到帐速度。
            支付遇到困难？请联系我们的线上客服获得帮助。
        </p>

    </div>
    <!-- 底部 -->
    <div id="footer">

    </div>
</div>

<!-- 第三方支付切换页面 结束-->


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
        memo:'请输入交易订单号！' ,
        bank:'请输入转入银行！' ,
        banktype:'请选择汇款方式！' ,
        time:'请选择汇款时间！' ,
        realname:'请输入存款人姓名！',
        remark:'请输入存款备注！',
    }
    // 公司入款输入验证
    var submitflag = false ; // 防止重复提交
    function depositeBankAction(){
        if(submitflag){
            return false ;
        }
        var payid = $('#payid').val(); // 银行卡 id
        var mon = $('.money-textbox').val() ; // 存款金额
        var v_Name = $('#v_Name').val() ; //  真实姓名
        var save_time =$('#time_textbox').val() ;  // 时间
        var memo = $('#memo').val() ; //  交易单号
        var maxval = parseInt('<?php echo $aRow['maxmoney']?>') ; // 最大金额
        if(!checkInputFloat(mon)){
            setPublicPop(alerttitle.int);
            $('.money-textbox').focus();
            return false;
        }
        if(mon < 100){
            setPublicPop(alerttitle.mon);
            $('.money-textbox').focus();
            return false;
        }
        if(mon > maxval){
            setPublicPop('汇款金额不能高于'+maxval+'元！');
            $('.money-textbox').focus();
            return false;
        }
        if( v_Name ==''){
            setPublicPop(alerttitle.realname);
            $('#v_Name').focus();
            return false;
        }
        if( payid ==''){
            setPublicPop(alerttitle.bank);
            return false;
        }
         if(save_time == ''){
             setPublicPop(alerttitle.time);
            return false;
        }
        if(memo == ''){
            setPublicPop(alerttitle.memo);
            $('#memo').focus();
            return false;
        }
        var datapars ={
            payid: payid , // 银行卡 id
            bank_user: v_Name , // 真实姓名
            v_amount: mon ,
            cn_date: save_time ,
            memo: memo
        }
        submitflag = true ;
        $.ajax({
            url: '/account/bank_type_SAOMA_save.php' ,
            type: 'POST',
            dataType: 'json',
            data: datapars ,
            success: function (res) {
                if(res.status !='200'){ // 有错误
                    submitflag = false ;
                    alertComing(res.describe);

                }else { // 成功
                    submitflag = false ;
                    alertComing(res.describe);
                    window.location.href ='depositrecord.php' ;
                }
            },
            error: function (msg) {
                submitflag = false ;
                alertComing(config.errormsg);
            }
        });

    }

    setLoginHeaderAction('支付宝扫码入款','','',usermon,uid) ; // 充值方式标题
    setFooterAction(uid) ; // 在 addServerUrl 前调用
    var calendar = new lCalendar();   // 时间插件初始化 ，公司入款
    var zfbcalendar = new lCalendar();   // 时间插件初始化 ，支付宝支付
    calendar.init({
        'trigger': '#time_textbox',
        'type': 'datetime',
        defaultValue:setAmerTime('#time_textbox'),
    });

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
