<?php
session_start();
include "../../../../app/member/include/config.inc.php";

?>

<style>
    .reg_bg{height: 555px;}
</style>
<div id="new-banner">
    <div id="new-banner-box">
        <div id="banner"><img src="<?php echo TPL_NAME;?>images/live/6.jpg"></div>
        <div class="msg-connet">

            <div class="left" style="margin-lefT:8px;">
                <div><a href="javascript:;" class="to_lives ylc_top"></a></div>
                <div> <a href="javascript:;" class="to_lives ylc_left"></a>
                    <a href="javascript:;" class="to_lives ylc_right"></a> </div>
            </div>

        </div>
    </div>
</div>

<div id="sidebarwrap">
    <div id="sidebarbox">
        <div id="leftsidebar">
            <ul>
                <li class="bbin"><a href="javascript:;" class="to_lives cur">BBIN娱乐</a></li>
                <li class="mg"><a href="javascript:;" class="to_lives">AG娱乐</a></li>
                <li class="sports"><a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">体育投注</a></li>
                <li class="lot"><a href="javascript:;" class="to_lotterys">彩票游戏</a></li>
                <li class="ele"><a href="javascript:;" class="to_games">电子游艺</a></li>
            </ul>
            <div id="ads1"><a href="javascript:;" class="to_promos"></a></div>
            <div id="ads2"><a href="javascript:;" class="to_promos"></a></div>
        </div>
        <div id="rightsidebar">
            <div id="main" class="reg">
                <div id="middle">
                    <div class="reg_bg" id="registerbg"></div>
                    <div class="form">
                        <div class="reg_top" id="reg_top"></div>
                        <div class="reg_head">
                            <p style="background:url(<?php echo TPL_NAME;?>images/reg/reg.ico) no-repeat left center; background-size:48px 48px;" class="add_title">找回密码</p></div>

                            <form id="register" class="formWrap_one">
                                <h2>账户信息</h2>
                                <div class="use"><label><span>姓名:</span><input name="alias" maxlength="20" type="text"> * 必须与你用于提款的银行户口姓名一致 </label></div>
                                <div class="use"><label><span>用户名:</span><input name="username" id="username" minlength="5" maxlength="15" type="text"> * 5-15个英文和数字组成,至少一个字母  </label></div>
                                <div class="pass"><label><span>提款密码:</span><input name="paypassword" minlength="4" maxlength="6" type="password" > * 须为六位数字密码 </label></div>
                                <div class="rep">
                                    <label>
                                        <span>验证码:</span>
                                        <input type="text" id="verifycode" name="verifycode" class="codeIpt fl" minlength="4" maxlength="6">
                                        <img class="codeImg fl" src="/app/member/include/validatecode/captcha.php" alt="验证码" onclick="this.src='/app/member/include/validatecode/captcha.php?v='+Math.random();">
                                    </label></div>

                                <div class="submitDiv verifyRandom">
                                    <input name="submit" type="button" class="submit_action"  data-step="one" value=" 提 交 ">
                                </div>
                                <div class="reg_bottom"></div>
                            </form>

                        <form id="register" class="formWrap_two hide">
                            <h2>重设密码</h2>
                            <div class="pass"><label><span>新密码:</span><input type="password" name="password" id="password" minlength="6" maxlength="15"> * 须为6~15码英文或数字夹杂 </label></div>
                            <div class="pass"><label><span>确认新密码:</span><input type="password" name="password2" id="password2" minlength="6" maxlength="15"> * 须为6~15码英文或数字夹杂 </label></div>

                            <div class="submitDiv verifyRandom">
                                <input name="submit" type="button" class="submit_action"  data-step="two" value=" 提 交 ">
                            </div>
                            <div class="reg_bottom"></div>
                        </form>

                    </div>

                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">


    $(function () {

        $('#verifycode').focus(function () { // 更新验证码
            $('.codeImg').attr('src','/app/member/include/validatecode/captcha.php?v='+Math.random());
        })

        var mem_flage = false ; // 防止重复提交
        function reqSubmit() {
            $('.submit_action').on('click',function () {
                var this_step = $(this).attr('data-step');
                var username = $("#username").val();
                var password = $("#password").val();
                var password2 = $("#password2").val();
                var alias = $("input[name='alias']").val();
                var paypassword = $("input[name='paypassword']").val();
                var verifycode = $("input[name='verifycode']").val();
                if(this_step == 'one'){ // 第一步
                    if (!check_null(alias) ) {
                        layer.msg('真实姓名不能为空',{time:alertTime});
                        return false;
                    }else if( !isChinese(alias) ){
                        layer.msg('请输入正确的真实姓名',{time:alertTime});
                        return false;
                    }
                    console.log(username)
                    if (username == "") {
                        layer.msg('所需帐号不能为空',{time:alertTime});
                        return false;
                    }
                    if ( !isNum(username) ){
                        layer.msg('请输入正确的账号！格式：以英文+数字,长度5-15',{time:alertTime});
                        return false;
                    }
                    if (username.length < 5 || username.length > 15) {
                        layer.msg('账号需在5-15位之间',{time:alertTime});
                        return false;
                    }
                    if(paypassword =='' || !isNumber(paypassword) || paypassword.length < 4 || paypassword.length > 6){
                        layer.msg('请输入正确的提款密码',{time:alertTime});
                        return false;
                    }
                    if(!verifycode){
                        layer.msg('请输入验证码',{time:alertTime});
                        return false;
                    }

                }else{ // 第二步
                    if (password == "") {
                        layer.msg('新密码不能为空',{time:alertTime});
                        return false;
                    }

                    if (password.length < 6 || password.length > 15) {
                        layer.msg('新密码需在6-15位之间',{time:alertTime});
                        return false;
                    }
                    if (password != password2 ) {
                        layer.msg('请检查账户密码与确认密码一致',{time:alertTime});
                        return false;
                    }
                }


                if(mem_flage){
                    return false ;
                }
                mem_flage = true ;


                var senddata = {
                    steptype:this_step, //  是  int  终端ID
                    action_type:'reset', // String  1.check；2：recheck；3：reset(避免交互过多，可填好相关信息，直接传 reset
                    username:username,
                    realname:alias, // 真实姓名 action_type是recheck和reset时必填  String  用户真实账号
                    withdraw_password:paypassword, // action_type是recheck和reset时必填  String  用户提款密码
                    // birthday:birthday, //  action_type是recheck和reset时必填  Date  用户生日
                    new_password:password, // action_type是reset时必填  String  新密码
                    password_confirmation:password2, // action_type是reset时必填  String  确认密码
                    verifycode:verifycode,
                }
                var actionurl = "/app/member/api/forgetPassword.php" ;
                $.ajax({
                    url: actionurl ,
                    type: 'POST',
                    dataType: 'json',
                    data: senddata ,
                    success: function (res) {
                        if(res){
                            mem_flage = false ;
                            layer.msg(res.describe,{time:alertTime});

                            if(res.status == 200.1){ // 第一步验证信息
                                $('.formWrap_'+this_step).hide();
                                $('.formWrap_two').show();

                            }else if(res.status == 200.2){ // 更改密码成功
                                $('.formWrap_'+this_step).hide();
                                $('.formWrap_three').show();
                                setTimeout(indexCommonObj.loadIndex,alertTime) ; // 加载登录
                            }

                        }

                    },
                    error: function (msg) {
                        mem_flage = false ;
                        layer.msg('更改密码异常',{time:alertTime});
                    }
                });
            })

        }

        reqSubmit();

    })
</script>