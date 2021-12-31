<?php
session_start();

include "../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];

?>


<div class="modal-body">
    <form method="post" name="LoginForm" id="LoginForm" > <!-- action="/app/member/login.php"  -->
        <div class="group">
            <input type="text" name="username" id="username" minlength="5" maxlength="15" autocomplete="off">
            <span class="bar"></span>
            <div class="error"></div>
            <label>账号
                <span class="details">(请输入您的账号)</span>
            </label>
        </div>
        <div class="group">
            <input type="password" class="pw-input" name="password" id="password" minlength="6" maxlength="15" autocomplete="off">
            <div class="to_livechat forget_psw"> <!-- to_forgetpassword -->
                <span>?</span>忘记密码
            </div>
            <span class="bar"></span>
            <div class="error"></div>
            <label>会员密码
                <span class="details">(请输入您的密码)</span>
            </label>
            <span class="showPassword"></span>
        </div>

        <div class="group">
            <input type="text" id="verifycode" name="verifycode" minlength="4" maxlength="6" autocomplete="off">
            <span class="bar"></span>
            <div class="error"></div>
            <label>验证码
                <span class="details">请输入验证码</span>
            </label>
            <span class="captchaImg">
                    <img class="register_captchaImg" src="/app/member/include/validatecode/captcha.php" alt="验证码" onclick="this.src='/app/member/include/validatecode/captcha.php?v='+Math.random();">
                  </span>
        </div>
        <div class="submit-btn">
            登录
        </div>
    </form>
</div>

<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/layer/layer.js"></script>
<script type="text/javascript" src="/js/loadpage_common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="/js/register/validate.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">
    $(function () {

        indexCommonObj.loadPageAction();

        $('#verifycode').focus(function () { // 更新验证码
            $('.register_captchaImg').attr('src','/app/member/include/validatecode/captcha.php?v='+Math.random());
        })

        memberLoginAction() ;
        enterSubmitAction();
        // 登录
        function memberLoginAction() {
            var loginflage = false ;
            $('.submit-btn').on('click',function () {
                if(loginflage){
                    return false ;
                }
                var username=$("#username").val();
                var passwd=$("#password").val();
                var verifycode=$("#verifycode").val(); // 验证码
                var title = '' ;

                if (username == "" ) {
                    title = '账号不能为空!';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if (!isNum(username)){
                    title = '请输入正确的账号！格式：以英文+数字,长度5-15!';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if (username.length < 5 || username.length > 15) {
                    title = '账号需在5-15位之间!';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if ( passwd == "" ) {
                    title = '密码不能为空！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if (passwd.length < 6 || passwd.length > 15) {
                    title = '密码需在6-15位之间！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(!verifycode){
                    title = '请输入验证码！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                loginflage = true ;
                var actionurl = "/app/member/login.php" ;
                $.ajax({
                    type : 'POST',
                    url : actionurl ,
                    data : {username:username,password:passwd,verifycode:verifycode},
                    dataType : 'json',
                    success:function(res) {
                        if(res){
                            loginflage = false ;
                            layer.msg(res.describe,{time:alertTime});
                            if(res.status ==200){
                                window.location.href = '/' ;
                            }
                        }

                    },
                    error:function(){
                        loginflage = false ;
                        layer.msg('稍后请重试',{time:alertTime});
                    }
                });

               // document.getElementById("LoginForm").submit();

            })


        }


    })
</script>