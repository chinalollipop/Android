<?php
session_start();

include "../../../../app/member/include/config.inc.php";


?>


<style>
    .reg_sec_all{height: 885px;background: url(<?php echo TPL_NAME;?>images/regBg.jpg) center no-repeat;padding-top: 30px;}
    .reg_sec_all .bottom_tip {width: 60%;margin: 0 auto;color: #716e6e;line-height: 26px;}
</style>

<div class="reg_sec_all">
    <div class="w_1000">
        <form method="post" name="LoginForm" id="LoginForm" > <!--  action="/app/member/mem_reg_add.php?keys=add" -->
            <div class="top">
                <span>填写手机号-提交登入试玩</span>
                <span >*标记的栏目为必填选项</span>
            </div>
            <div class="reg_left">
                <div class="group">
                    <label>手机号码 <span class="red_color">*</span>：</label>
                    <input type="text" class="pw-input" name="phone" id="phone" minlength="11" maxlength="11" autocomplete="off" placeholder="请认真填写，以便有优惠活动可以及时通知您参与">
                </div>

                <div class="group">

                    <label>验证码 <span class="red_color">*</span>：</label>
                    <input type="text" id="verifycode" name="verifycode" minlength="4" maxlength="6" autocomplete="off" placeholder="请输入验证码">
                    <span class="captchaImg">
                    <img class="register_captchaImg" src="/app/member/include/validatecode/captcha.php" alt="验证码" >
                  </span>
                </div>

            </div>

            <div class="submit-btn" onclick="guest_login_save_phone_submit();">
                提交登入试玩
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        $('#verifycode').focus(function () { // 更新验证码
            $('.register_captchaImg').attr('src','/app/member/include/validatecode/captcha.php?v='+Math.random());
        })
        enterSubmitAction();

    })

    function guest_login_save_phone_submit() {
        var ajaxurl='app/member/guest_login_save_phone.php';
        var senddata={
            phone: $('#phone').val(),
            verifycode: $('#verifycode').val()
        };
        $.ajax({
            url:  ajaxurl ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success:function(ret){
                // console.log(ret);
                if(ret.status=='200'){ // 登录成功
                    indexCommonObj.loadMemberTestPlayLogin();

                }else {
                    layer.msg(ret.describe,{time:alertTime});
                }
            },
            error: function (XMLHttpRequest, status) {
                layer.msg('网络错误，稍后请重试',{time:alertTime});
            }
        });

    }
</script>