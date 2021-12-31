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
    <link rel="stylesheet" href="../../style/icalendar.css?v=<?php echo AUTOVER; ?>">
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
    <!--<video class="video-all" muted autoplay="autoplay" loop="loop">
        <source src="images/login.mp4" type="video/mp4" />
        Your browser does not support the video tag.
    </video>-->

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
                            <span class="logaccount-icon"></span>
                            <input type="text" name="username" id="username" minlength="5" maxlength="15" class="za_text" placeholder="* 账号">
                        </li>
                        <li>
                            <span class="logaccount-icon"></span>
                             <input type="text" name="alias" id="alias"  maxlength="10" class="inp-txt" placeholder="* 请输入您的真实姓名">
                        </li>

                        <li>
                            <span class="logpwd-icon"></span>
                            <input type="password" name="paypassword" id="paypassword"  minlength="4" maxlength="6" class="inp-txt" placeholder="* 4-6位纯数字资金密码">
                        </li>

                        <!--<li>
                            <div>
                                <label>
                                    <span class="text"><em class="red_color">*</em> 出生日期</span>
                                </label>
                                <span class="textbox">
                                    <input id="birthday" maxlength="12"  type="text" name="birthday" placeholder="请填写出生年月日" readonly />
                                </span>
                            </div>
                        </li>-->

                        <li>
                            <span class="logpwd-icon"></span>
                            <input type="password" name="password" id="password"  minlength="6" maxlength="15" class="inp-txt" placeholder="* 新登录密码">
                        </li>
                        <li>
                            <span class="logpwd-icon"></span>
                            <input type="password" name="password2" id="password2" minlength="6" maxlength="15" class="inp-txt" placeholder="* 确认新密码">
                        </li>
                    </ul>
                </div>

                <div class="btn-wrap">
                    <a href="javascript:reqSubmit();" class="zx_submit">提交</a>
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
<!--<script type="text/javascript" src="../../js/icalendar.min.js"></script>-->
<script type="text/javascript">

    var mem_flage = false ; // 防止重复提交
    function reqSubmit() {
        if (removeAllSpace(_$('username').value) == "") {
            setPublicPop('所需帐号不能为空');
            return false;
        }
        if (!isNum(removeAllSpace(_$('username').value))){
            setPublicPop('请输入正确的账号！格式：以英文+数字,长度5-15');
            return false;
        }
        if (removeAllSpace(_$('username').value).length < 5 || removeAllSpace(_$('username').value).length > 15) {
            setPublicPop('账号需在5-15位之间');
            return false;
        }

        if (!check_null(removeAllSpace(_$('alias').value))) {
            setPublicPop('真实姓名不能为空');
            return false;
        }else if(!isChinese(removeAllSpace(_$('alias').value))){
            setPublicPop('请输入正确的真实姓名');
            return false;
        }
        if(removeAllSpace(_$('paypassword').value) =='' || !isNumber(removeAllSpace(_$('paypassword').value) ) || removeAllSpace(_$('paypassword').value).length < 4 || removeAllSpace(_$('paypassword').value).length > 6){
            setPublicPop('请输入正确的提款密码');
            return false;
        }
        // if ( _$('birthday').value ==''){
        //     setPublicPop('请选择出生日期!');
        //     flag =false ;
        //     return false;
        // }
        if (removeAllSpace(_$('password').value) == "") {
            setPublicPop('新密码不能为空');
            return false;
        }
        if (removeAllSpace(_$('password').value).length < 6 || removeAllSpace(_$('password').value).length > 15) {
            setPublicPop('新密码需在6-15位之间');
            return false;
        }
        if (removeAllSpace(_$('password').value) != removeAllSpace(_$('password2').value)) {
            setPublicPop('请检查账户密码与确认密码一致');
            return false;
        }


        if(mem_flage){
            return false ;
        }
        mem_flage = true ;


        var username = removeAllSpace($("input[name='username']").val());
        var password = removeAllSpace($("input[name='password']").val());
        var password2 = removeAllSpace($("input[name='password2']").val());
        var alias = removeAllSpace($("input[name='alias']").val());
        var paypassword = removeAllSpace($("input[name='paypassword']").val());
       // var birthday = removeAllSpace($("input[name='birthday']").val());

        var senddata = {
            appRefer:'', //  是  int  终端ID
            action_type:'reset', // String  1.check；2：recheck；3：reset(避免交互过多，可填好相关信息，直接传 reset
            username:username,
            realname:alias, // 真实姓名 action_type是recheck和reset时必填  String  用户真实账号
            withdraw_password:paypassword, // action_type是recheck和reset时必填  String  用户提款密码
           // birthday:birthday, //  action_type是recheck和reset时必填  Date  用户生日
            new_password:password, // action_type是reset时必填  String  新密码
            password_confirmation:password2, // action_type是reset时必填  String  确认密码
        }

        /**  ret.err
         *  -1 您输入的推荐代理 $agent 不存在
         *  -2 帐户已经有人使用，请重新注册！
         *  -3 插入新账户信息 数据库操作失败!!!
         *  -4 更新代理信息操作失败
         *  0  注册成功
         */
        $.ajax({
            url: '/forget_pwd.php' ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success: function (ret) {

                if(ret.status=='200'){ // 注册成功
                    mem_flage = false ;
                    alertComing(ret.describe);
                    window.location.href ='/login.php' ;

                }else {
                    mem_flage = false ;
                    alertComing(ret.describe);
               }
            },
            error: function (msg) {
                mem_flage = false ;
                setPublicPop('更改密码异常');
            }
        });
    }


    // var calendar = new lCalendar();   // 时间插件初始化
    // calendar.init({
    //     'trigger': '#birthday',
    //    // 'type': 'datetime', // 显示时分秒等
    //     'type': 'date', // 只显示年月日
    // });

    setLoginHeaderAction('注册','login') ;
    addServerUrl() ;
    agreeMentAction() ;


</script>

</body>
</html>