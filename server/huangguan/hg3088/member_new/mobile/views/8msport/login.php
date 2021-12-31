<?php
	include_once('../../include/config.inc.php');
	include('../../include/address.mem.php');
    $oid = $_SESSION['Oid']; // 拿到oid
    if(isset($_SESSION['Oid']) || $_SESSION['Oid'] != "" ) { // 如果已登录，返回到首页
        echo "<script>window.location.href='/';</script>";
        exit;
    }
$host = getMainHost();
$weburl= HTTPS_HEAD.'://'.$host.'?topc=yes'; // 电脑版网址

?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
        <link href="../../style/tncode/style.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" />
        <title class="web-title"></title>
    <style type="text/css">
        body{background: #fff;}
        .yzm_code{position: absolute;right: 1.5rem;top: .4rem;}
        .login_div .login_bottom{margin-top:2rem;display:-webkit-flex;display:flex;justify-content:space-between}
        .login_div .login_bottom a{width:46%;height: 40px;line-height: 40px;background:none;color:#5da1ea;border:1px solid #5da1ea}
    </style>

    </head>
    <body >
         <div id="container">
             <!-- 头部 -->
           <!-- <div class="header ">

            </div>-->

             <div class="content-center" >
                     <div class="login_div">
                         <div class="login-logo"></div>
                         <input type=hidden name="demoplay" id="demoplay" value="">
         <!--         <div class="plat-idv">
                      <h2 class="web-title"></h2>
                  </div>-->
                       <!-- <form name="LoginForm" action="" method="post" onsubmit="return inputCheck();">-->
                         <div class="login_center">
                            <div class="big_div">
                                <ul class="login_form">
                                    <li>
                                        <span class="logaccount-icon">
                                           <!-- <i class="fa fa-user-circle"></i>-->
                                        </span>
                                        <input autocomplete="off" name="username" type="text" class="za_text" id="username"  placeholder="账号" />
                                    </li>
                                    <li class="psw_li">
                                        <span class="logpwd-icon">
                                            <!--<i class="fa fa-unlock-alt"></i>-->
                                        </span><input autocomplete="off" name="passwd" type="password" class="za_text" id="passwd" value="" maxlength="15"  placeholder="密码"/>
                                        <a class="see_psw see_psw_close" onclick="showpsw(this)"></a>
                                    </li>
                                   <!-- <li style="position: relative;">
                                        <span class="logpwd-icon">
                                        </span>
                                        <input autocomplete="off" name="yzm_input" type="text" class="za_text" id="yzm_input" maxlength="4" size="6" placeholder="验证码"/>
                                        <img title="点击刷新" class="yzm_code"  src="/include/validatecode/captcha.php" onclick="this.src='/include/validatecode/captcha.php?v='+Math.random();">
                                    </li>-->

                                </ul>
                                <div class="login_forget">
                                     <div class="remember_psw checkbox-item ">
                                         <span class="checkbox-item ">
                                            <span class="icon"></span>
                                            <span class="text">记住账号</span>
                                        </span>

                                    </div>

                                                                    <a class="forgot_psw" href="forgetpwd.php"><p>忘记密码?</p></a>
<!--                                    <a class="forgot_psw" href="#" onclick="window.open(config.onlineserver)"><p>忘记密码?</p></a>-->
                                </div>
                                <a onclick="doLoginAvtion(this);"  class="zx_submit before_yz" > 立即登录 </a>

                                <div class="login_bottom">
                                    <?php
                                    // 试玩必须输入手机号的开关：TRUE 跳转到输入手机号的页面，FALSE 直接登入试玩
                                    if (GUEST_LOGIN_MUST_INPUT_PHONE){
                                        echo '<a href="guest_login.php"  class="zx_submit try_paly" > 先去逛逛 </a>';
                                    }
                                    else{
                                        echo '<a onclick="doLoginAvtion(this,\'try\');"  class="zx_submit try_paly" > 试玩参观 </a>';
                                    }
                                    ?>
                                    <a class="zx_submit to_pc" href="<?php echo $weburl;?>"> 电脑版 </a>
                                </div>


                            </div>

                         </div>
                       <!-- </form>-->
                   </div>
             </div>
             <div class="clear"></div>
         
           
      </div>
         <!-- 底部footer -->
         <div id="footer" class="footer_dis">
            <div class="footer_tip"> 还没有账号？请<a class="tip" href="reg.php"> &nbsp;注册</a> </div>
         </div>

     <script type="text/javascript" src="../../js/zepto.min.js"></script>
     <script type="text/javascript" src="../../js/animate.js"></script>
     <script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
     <script type="text/javascript" src="../../style/tncode/tn_code.js?v=<?php echo AUTOVER; ?>" ></script>
      <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
     <script type="text/javascript" src="../../js/validate.js?v=<?php echo AUTOVER; ?>"></script>
     <script type="text/javascript">
         LOGIN_IS_VERIFY_CODE=<?php echo LOGIN_IS_VERIFY_CODE ? 1:0;?>;
         if(LOGIN_IS_VERIFY_CODE) {
             // 初始化验证码
             var $TNCODE = tncode;
             $TNCODE.init();
         }

         delLoginStatus();

        // setLoginHeaderAction('用户登录','','login') ;
         setPublicContact() ;
         addServerUrl() ;

         agreeMentAction();
         rememberPwd() ;

         $('#yzm_input').focus(function () { // 更新验证码
             $('.yzm_code').attr('src','include/validatecode/captcha.php?v='+Math.random());
         })
         // 会员登录
         function doLoginAvtion(obj,Ltype) {
             $(obj).attr('disabled','disabled');
             if(Ltype=='try'){
                 var demoplay = 'Yes';
                 var usename = "demoguest";
                 var passwd = "nicainicainicaicaicaicai";
             }else{
                 var demoplay = '';
                 var usename = removeAllSpace($('#username').val()) ;
                 var passwd = removeAllSpace($('#passwd').val()) ;
                // var yzm = removeAllSpace($('#yzm_input').val()) ;
             }

             var $error = document.getElementsByClassName('error-msg')[0] ;
             var iconstr = '<span class="error-icon">!</span>' ;
             var ifremeberpsw = $('.remember_psw').hasClass('checked'); // 是否记住密码

             if (usename == "") {
                 // $error.innerHTML= iconstr+' 请输入帐号';
                 // $('#username').focus();
                 setPublicPop('请输入帐号') ;
                 $(obj).removeAttr('disabled');
                 return false;
             } else if (passwd == "") {
                 // $error.innerHTML= iconstr+' 请输入密码';
                 // $('#passwd').focus();
                 setPublicPop('请输入密码') ;
                 $(obj).removeAttr('disabled');
                 return false;
             }

             if(LOGIN_IS_VERIFY_CODE) {
                 // 验证通过

                 $TNCODE.show() ;
                 $TNCODE.onsuccess(function () {
                     //验证通过
                     // $error.innerHTML='' ;
                     if (ifremeberpsw) { // 记住密码
                         setCookieAction('username', usename);
                         setCookieAction('userpwd', passwd);
                     } else {
                         delCookieAction('username');
                         delCookieAction('userpwd');
                     }

                     var ajaxurl = '/login_api.php';
                     var senddata = {
                         demoplay: demoplay,
                         username: usename,
                         passwd: passwd,
                         yzm_input: Math.random(),
                     }
                     $.ajax({
                         url: ajaxurl,
                         type: 'POST',
                         dataType: 'json',
                         data: senddata,
                         success: function (ret) {
                             if (ret.status) { // 有结果返回
                                 //  {"status":"200","describe":"登录成功!","timestamp":"20180819044600","data":{"UserName":"jack001","Agents":"dleden001","LoginTime":"2018-08-19 04:46:00","birthday":"1986-08-01","Money":"20660.0257","Phone":"13688988898","test_flag":"0","Oid":"03ae9981e16d7f254be9ra6","Alias":"发发发","BindCard_Flag":"1","BetMinMoney":"20","BetMaxMoney":"5000000"},"sign":"5227afe7e560e3a2676d4da07c609193"}
                                 if (ret.describe) {
                                     setPublicPop(ret.describe);
                                 }
                                 $(obj).removeAttr('disabled');
                                 if (ret.status == '200') { // 登录成功
                                     setCookieAction('member_money', ret.data.Money, 1); // 用户金额，cookie 有效期 1天
                                     loginLotteryAction();

                                 } else if (ret.status == '300.1') {
                                     window.location.href = ret.data.agentchangeurl;
                                 } else { // 登录失败

                                     $TNCODE.init();
                                 }
                             }
                         },
                         error: function (XMLHttpRequest, status) {
                             setPublicPop('网络错误，稍后请重试!!');
                             $(obj).removeAttr('disabled');
                         }
                     });
                 });
             }else{
                 //验证通过
                 // $error.innerHTML='' ;
                 if (ifremeberpsw) { // 记住密码
                     setCookieAction('username', usename);
                     setCookieAction('userpwd', passwd);
                 } else {
                     delCookieAction('username');
                     delCookieAction('userpwd');
                 }

                 var ajaxurl = '/login_api.php';
                 var senddata = {
                     demoplay: demoplay,
                     username: usename,
                     passwd: passwd,
                     yzm_input: Math.random(),
                 }
                 $.ajax({
                     url: ajaxurl,
                     type: 'POST',
                     dataType: 'json',
                     data: senddata,
                     success: function (ret) {
                         if (ret.status) { // 有结果返回
                             //  {"status":"200","describe":"登录成功!","timestamp":"20180819044600","data":{"UserName":"jack001","Agents":"dleden001","LoginTime":"2018-08-19 04:46:00","birthday":"1986-08-01","Money":"20660.0257","Phone":"13688988898","test_flag":"0","Oid":"03ae9981e16d7f254be9ra6","Alias":"发发发","BindCard_Flag":"1","BetMinMoney":"20","BetMaxMoney":"5000000"},"sign":"5227afe7e560e3a2676d4da07c609193"}
                             if (ret.describe) {
                                 setPublicPop(ret.describe);
                             }
                             $(obj).removeAttr('disabled');
                             if (ret.status == '200') { // 登录成功
                                 setCookieAction('member_money', ret.data.Money, 1); // 用户金额，cookie 有效期 1天
                                 loginLotteryAction();

                             } else if (ret.status == '300.1') {
                                 window.location.href = ret.data.agentchangeurl;
                             }
                         }
                     },
                     error: function (XMLHttpRequest, status) {
                         setPublicPop('网络错误，稍后请重试!!');
                         $(obj).removeAttr('disabled');
                     }
                 });
             }

         }
         // 密码可见
         function showpsw(obj) {
             $passwd = $(obj).parents('.psw_li').find('input') ;
             var ifshow = $passwd.attr('type') ;
             if(ifshow=='password'){
                 $passwd.attr('type','text') ;
                 $(obj).addClass('see_psw_open').removeClass('see_psw_close') ;
             }else{
                 $passwd.attr('type','password') ;
                 $(obj).addClass('see_psw_close').removeClass('see_psw_open') ;
             }
         }
         
         // 记住密码处理
         function rememberPwd() {
             var username = getCookieAction('username') ;
             var userpwd = getCookieAction('userpwd') ;
             if(username){
                 $('#username').val(username) ;
                 $('#passwd').val(userpwd) ;
                 $('.remember_psw').addClass('checked') ;
                 $('#remember').attr('checked','checked') ;
             }
         }

     </script>
    </body>
</html>