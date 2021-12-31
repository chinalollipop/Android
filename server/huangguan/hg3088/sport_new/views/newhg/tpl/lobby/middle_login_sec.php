<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];

?>


<style>

</style>
<div class="modal-body login_sec_all">

    <div class="w_1200 hb_box cl">
        <div class="LoginPerson"> </div>
            <div class="LoginBox">
                <div class="Head">
                    登录
                </div>
                <div class="FillIn">
                    <div class="FillInInput">
                        <div class="el-input el-input-group el-input-group--prepend">
                            <div class="el-input-group__prepend">
                                <i class="iconfont icon-icon_account">
                                </i>
                            </div>
                            <input type="text" id="username" minlength="5" maxlength="15" autocomplete="off" placeholder="请输入用户名" class="el-input__inner">
                        </div>
                    </div>
                    <div class="FillInInput">
                        <div class="el-input el-input-group el-input-group--prepend el-input--suffix">
                            <div class="el-input-group__prepend">
                                <i class="iconfont icon-mima">
                                </i>
                            </div>
                            <input type="password" id="password" minlength="6" maxlength="15" autocomplete="off" placeholder="请输入密码" class="el-input__inner">
                            <span class="el-input__suffix">
										<span class="el-input__suffix-inner"></span>
									</span>
                        </div>
                        <!--<span class="to_forgetpassword wlmm">
									忘了密码?
								</span>-->
                        <a href="javascript:;" class="to_livechat wlmm">
									忘了密码?
								</a>
                    </div>
           
                    <label role="checkbox" class="el-checkbox auto_login">
								<span aria-checked="mixed" class="el-checkbox__input">
									<span class="el-checkbox__inner">
									</span>
									<input type="checkbox" aria-hidden="true" class="rememberme el-checkbox__original">
								</span>
                        <span class="el-checkbox__label">
									自动登陆
								</span>
                    </label>
                    <div class="Submit">
                        <button type="button" class="submit-btn el-button them_bg_color_gradient click_on el-button--primary el-button--medium is-round">
									<span>
										立&nbsp;即&nbsp;登&nbsp;录
									</span>
                        </button>
                    </div>
                    <div class="Register">
                        <button type="button" class="to_memberreg el-button btn_zhuce hover_theme_font el-button--text">
									<span>
										立即注册
									</span>
                        </button>
                        <button type="button" class="el-button btn_zhuce hover_theme_font el-button--text <?php echo GUEST_LOGIN_MUST_INPUT_PHONE?'to_testphone':'to_testplaylogin' ?> ">
									<span>
										免费试玩
									</span>
                        </button>

                    </div>
                    <div class="logo_box">
                        <img src="<?php echo TPL_NAME;?>images/logo_app.png">
                        <p class="logo_text">
                            亚洲<?php echo COMPANY_NAME;?>体育平台
                        </p>
                        <p >
                            顶级品牌 引领精彩人生
                        </p>
                    </div>
                </div>
            </div>
        </div>

</div>



<script type="text/javascript">


    $(function () {

        indexCommonObj.loadPageAction();

        $('#verifycode').focus(function () { // 更新验证码
            $('.register_captchaImg').attr('src','/app/member/include/validatecode/captcha.php?v='+Math.random());
        })

        memberLoginAction() ;
        enterSubmitAction();

        getRmemberMeAction() ;

        // 登录
        function memberLoginAction() {

            var loginflage = false ;
            $('.submit-btn').on('click',function () {

                if (loginflage) {
                    return false;
                }
                rememberMeAction();
                var username = $("#username").val();
                var passwd = $("#password").val();
                var verifycode = $("#verifycode").val(); // 验证码
                var title = '';

                if (username == "") {
                    title = '账号不能为空!';
                    layer.msg(title, {time: alertTime});
                    return false;
                }
                if (!isNum(username)) {
                    title = '请输入正确的账号！格式：以英文+数字,长度5-15!';
                    layer.msg(title, {time: alertTime});
                    return false;
                }
                if (username.length < 5 || username.length > 15) {
                    title = '账号需在5-15位之间!';
                    layer.msg(title, {time: alertTime});
                    return false;
                }
                if (passwd == "") {
                    title = '密码不能为空！';
                    layer.msg(title, {time: alertTime});
                    return false;
                }
                if (passwd.length < 6 || passwd.length > 15) {
                    title = '密码需在6-15位之间！';
                    layer.msg(title, {time: alertTime});
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