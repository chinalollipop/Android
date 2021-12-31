<?php
session_start();

include "../../../../app/member/include/config.inc.php";


?>


<style>
    .captchaImg{position:absolute;display:block;width:100px;height:35px;border-radius:5px;right:8px;top:5px}
</style>

<div class="modal-body login_sec_all">

    <div class="w_1200 hb_box cl">
        <div class="LoginPerson"> </div>
        <div class="LoginBox">
            <div class="Head">
                免费试玩
            </div>
            <div class="FillIn">
                <div class="FillInInput">
                    <div class="el-input el-input-group el-input-group--prepend">
                        <div class="el-input-group__prepend">
                            <i class="iconfont icon-phone">
                            </i>
                        </div>
                        <input type="text" id="phone" minlength="11" maxlength="11" autocomplete="off" placeholder="请输入手机号" class="el-input__inner">
                    </div>
                </div>

                <div class="FillInInput">
                    <div class="el-input el-input-group el-input-group--prepend">
                        <div class="el-input-group__prepend">
                            <i class="iconfont icon-icon_account">
                            </i>
                        </div>
                        <input type="text" id="verifycode" minlength="4" maxlength="6" autocomplete="off" placeholder="请输入验证码" class="el-input__inner">
                        <span class="captchaImg"> <img class="register_captchaImg" src="/app/member/include/validatecode/captcha.php" alt="验证码" >　</span>
                    </div>
                </div>

                <div class="Submit">
                    <button type="button" onclick="guest_login_save_phone_submit();" class="el-button them_bg_color_gradient click_on el-button--primary el-button--medium is-round">
									<span>
										提&nbsp;交&nbsp;登&nbsp;入&nbsp;试&nbsp;玩
									</span>
                    </button>
                </div>


            </div>
        </div>
    </div>

</div>


<script type="text/javascript" src="/js/register/validate.js?v=<?php echo AUTOVER; ?>"></script>
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