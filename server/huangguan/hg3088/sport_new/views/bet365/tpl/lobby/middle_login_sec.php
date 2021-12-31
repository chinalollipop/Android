<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];

?>


<div class="modal-body login_sec_all">
    <form method="post" name="LoginForm" id="LoginForm" >
        <div class="login_all">
            <div class="logo"></div>
            <p>登录账号</p>
            <div class="group">
                <input type="text" name="username" id="username" minlength="5" maxlength="15" autocomplete="off" placeholder="请输入账号">
            </div>
            <div class="group">
                <input type="password" class="pw-input" name="password" id="password" minlength="6" maxlength="15" autocomplete="off" placeholder="请输入密码">
            </div>

            <div class="group" style="min-height: 60px;">
                <input type="hidden" id="verifycode" name="verifycode" minlength="4" maxlength="6" >
                <!--<input type="text" id="verifycode" name="verifycode" minlength="4" maxlength="6" autocomplete="off" placeholder="请输入验证码">-->
                <!--<span class="captchaImg">
                    <img class="register_captchaImg" src="/app/member/include/validatecode/captcha.php" alt="验证码" onclick="this.src='/app/member/include/validatecode/captcha.php?v='+Math.random();">
                </span>-->
                <div class="label-login">
                    <input name="checkbox" type="checkbox" class="rememberme">
                    <a href="javascript:;" class="login-remember-me">记住账号</a>
                </div>
            </div>
            <div class="bottom">
                <div class="submit-btn">登录</div>
                <a href="javascript:;" class="to_livechat sec_forget_psw">
                    忘记密码
                </a>

                <div class="not_account">
                    <span> </span> &nbsp;还没有账号？&nbsp;<span></span>
                </div>
                <a href="javascript:;" class="to_memberreg sec_memberreg"> 马上注册 </a>
                <a href="javascript:;" class="<?php echo GUEST_LOGIN_MUST_INPUT_PHONE?'to_testphone':'to_testplaylogin' ?> sec_testplay"> 免费试玩 </a>
            </div>


        </div>
    </form>
</div>



<script type="text/javascript">
    $(function () {

        indexCommonObj.loadPageAction();

        $('#verifycode').focus(function () { // 更新验证码
            $('.register_captchaImg').attr('src','/app/member/include/validatecode/captcha.php?v='+Math.random());
        })

        //memberLoginAction() ;
        enterSubmitAction();

        getRmemberMeAction() ;

        // 登录
        function memberLoginAction() {

            var loginflage = false ;
            $('.submit-btn').on('click',function () {

                if(loginflage){
                    return false ;
                }
                rememberMeAction();
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
                /*if(!verifycode){
                    title = '请输入验证码！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }*/

                <?php
                    if(LOGIN_IS_VERIFY_CODE) {
                        // 验证通过
                        echo '$TNCODE.show();
                            $TNCODE.onsuccess(function () {';
                    }
                ?>

                        loginflage = true;
                        var actionurl = "/app/member/login.php";
                        $.ajax({
                            type: 'POST',
                            url: actionurl,
                            data: {username: username, password: passwd, verifycode: Math.random()},
                            dataType: 'json',
                            success: function (res) {
                                if (res) {
                                    loginflage = false;
                                    layer.msg(res.describe, {time: alertTime});
                                    if (res.status == 200) {
                                        window.location.href = '/';
                                    } else { // 登录失败
                                        $TNCODE.init();
                                    }
                                }

                            },
                            error: function () {
                                loginflage = false;
                                layer.msg('稍后请重试', {time: alertTime});
                            }
                        });

                <?php
                    if(LOGIN_IS_VERIFY_CODE) {
                        echo '})';
                    }
                ?>

            })

        }

        // 记住我的帐号
        function rememberMeAction() {
            var ifremeber = $('.rememberme').prop('checked') ;
            var username = $('#username').val() ;
            var password = $('#password').val() ;
            //console.log(ifremeber) ;
            // console.log(document.all.username.value) ;
            // console.log(document.all.password.value) ;
            if(ifremeber){
                localStorage.setItem('ifremeberme',ifremeber) ;
                localStorage.setItem('username',username) ;
                localStorage.setItem('password',password) ;
            }else {
                localStorage.setItem('ifremeberme','') ;
                localStorage.setItem('username','') ;
                localStorage.setItem('password','') ;

            }
        }
        // 判断是否有记住帐号
        function getRmemberMeAction() {
            var username = localStorage.getItem('username') ;
            var password = localStorage.getItem('password') ;
            var ifremeber = localStorage.getItem('ifremeberme') ;
            // console.log(username) ;
            if(ifremeber){
                $('.rememberme').prop('checked',true);
            }
            if(username){
                $('#username').val(username) ;
            }
            if(password){
                $('#password').val(password) ;
            }

        }



    })
</script>