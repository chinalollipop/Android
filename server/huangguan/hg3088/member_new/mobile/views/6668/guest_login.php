<?php
session_start();
include_once('../../include/config.inc.php');

if(isset($_SESSION['Oid']) || $_SESSION['Oid'] != "" ) { // 如果已登录，返回到首页
    echo "<script>window.location.href='/';</script>";
    exit;
}
?>

<html class="zh-cn"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <title class="web-title"></title>
<style type="text/css">
    html,body{height: 100%;-webkit-overflow-scrolling: touch;}
    body {background: url(images/login.gif);background-size: cover;}
    .video-all {position: absolute;width: 100%;left: 0;top: 0;}
    .content-center{position: relative;}
</style>
</head>
<body>
<div id="container">

    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间注册表单内容 -->
    <div class="content-center">
        <div class="member_reg" >
            <div class="login-logo"></div>
            <input type="hidden" name="keys" value="add">
            <div class="login_center">
                <div class="login_form">
                    <ul>
                        <li>
                            <span class="phone-icon"></span>
                            <input type="text" name="phone" id="phone" minlength="11" maxlength="11" class="za_text" placeholder="* 请输入11位手机号码">
                        </li>

                        <li>
                           <span class="logpwd-icon"></span>
                            <span >
                                <input id="verifycode" class="inp-txt" name="verifycode" type="text" tabindex="2"  minlength="4" maxlength="4" placeholder="* 请输入验证码" >
                                <img title="点击刷新" class="yzm_code" style="position: absolute;right: 4.3%;"  src="/include/validatecode/captcha.php" align="absbottom" onclick="this.src='/include/validatecode/captcha.php?'+Math.random();"/>
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="btn-wrap">
                    <a href="javascript:guest_login_save_phone_submit();" class="zx_submit">提交</a>
                </div>
            </div>
    </div>
    </div>

</div>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/validate.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">

    $('#verifycode').focus(function () { // 更新验证码
        $('.yzm_code').attr('src','/include/validatecode/captcha.php?v='+Math.random());
    })

    var mem_flage = false ; // 防止重复提交
    function guest_login_save_phone_submit() {
        if(removeAllSpace(_$('phone').value)=='' || !isMobel(removeAllSpace(_$('phone').value))){
            setPublicPop('请输入正确的手机号码!');
            return false;
        }
        if(mem_flage){
            return false ;
        }
        mem_flage = true ;
        var phone = removeAllSpace($("input[name='phone']").val());
        var verifycode = $('#verifycode').val() ;

        var senddata = {
            phone:phone,
            verifycode:verifycode,
        }

        /**  ret.err
         *  400.01 你已被禁止登录!
         *  400.02 手机号码不符合规范!
         *  400.03 请输入验证码!
         *  400.04 验证码输入错误!
         *  400.05 操作失败!!!
         *  200     手机号提交成功
         */
        $.ajax({
            url: '/guest_login_save_phone_api.php' ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success: function (ret) {
                if(ret.status=='200'){ // 手机号提交成功，下一步试玩登入

                    // 试玩登入
                    var ajaxurl = '/login_api.php';
                    var senddata ={
                        demoplay: 'Yes',
                        username: 'demoplay' ,
                        passwd: 'nicainicai' ,
                    }
                    $.ajax({
                        url:  ajaxurl ,
                        type: 'POST',
                        dataType: 'json',
                        data: senddata ,
                        success:function(ret){
                            if(ret.status){ // 有结果返回
                                //  {"status":"200","describe":"登录成功!","timestamp":"20180819044600","data":{"UserName":"jack001","Agents":"dleden001","LoginTime":"2018-08-19 04:46:00","birthday":"1986-08-01","Money":"20660.0257","Phone":"13688988898","test_flag":"0","Oid":"03ae9981e16d7f254be9ra6","Alias":"发发发","BindCard_Flag":"1","BetMinMoney":"20","BetMaxMoney":"5000000"},"sign":"5227afe7e560e3a2676d4da07c609193"}
                                if(ret.describe){
                                    setPublicPop(ret.describe) ;
                                }
                                if(ret.status=='200'){ // 登录成功
                                    setCookieAction('member_money',ret.data.Money,1) ; // 用户金额，cookie 有效期 1天
                                    loginLotteryAction() ;

                                }else if(ret.status=='300.1') {
                                    window.location.href = ret.data.agentchangeurl ;
                                }
                            }
                        },
                        error: function (XMLHttpRequest, status) {
                            setPublicPop('网络错误，稍后请重试!!');
                            $(obj).removeAttr('disabled');
                        }
                    });


                } else {
                    setPublicPop(ret.describe);
                }
            },
            error: function (msg) {
                mem_flage = false ;
                setPublicPop('注册账号异常');
            }
        });
    }

    setLoginHeaderAction('试玩参观','login') ;
    addServerUrl() ;
    agreeMentAction() ;


</script>

</body>
</html>