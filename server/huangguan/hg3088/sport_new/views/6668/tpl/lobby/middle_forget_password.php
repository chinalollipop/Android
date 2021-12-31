<?php
session_start();


?>


<div class="forgetWrap">
    <div class="forgetBox">
        <h1 class="forget-tit">
            忘记密码
        </h1>
        <ul class="forget-Nav clearfix">
            <li class="active"> <span>1</span> 信息验证</li>
            <li> <span>2</span> 设置新密码</li>
            <li> <span>3</span> 设置成功</li>
        </ul>
        <div class="formWrap_one formWrap clearfix ">
            <div class="formItem clearfix">
                <div class="form-text fl">
                    姓名
                </div>
                <div class="form-ipt fl">
                    <input type="text" name="alias">
                    <p><span class="red">*</span>必须与你用于提款的银行户口姓名一致</p>
                </div>
            </div>
            <div class="formItem clearfix">
                <div class="form-text fl">
                    账户名
                </div>
                <div class="form-ipt fl">
                    <input type="text" name="username" minlength="6" maxlength="15">
                    <p><span class="red">*</span>须为6~15位英文或数字夹杂</p>
                </div>
            </div>
            <div class="formItem clearfix">
                <div class="form-text fl">
                    提款密码
                </div>
                <div class="form-ipt fl">
                    <input type="password" name="paypassword" minlength="4" maxlength="6">
                    <p><span class="red">*</span>须为六位数字密码</p>
                </div>
            </div>
            <div class="formItem clearfix">
                <div class="form-text fl">
                    验证码
                </div>
                <div class="form-ipt fl clearfix">
                    <input type="text" id="verifycode" name="verifycode" class="codeIpt fl" minlength="4" maxlength="6">
                    <img class="codeImg fl" src="/app/member/include/validatecode/captcha.php" alt="验证码" onclick="this.src='/app/member/include/validatecode/captcha.php?v='+Math.random();">
                </div>
            </div>
            <button class="submit_action form-Btn fr" data-step="one">提交</button>
        </div>

        <div class="formWrap_two formWrap clearfix hide" >
            <div class="formItem clearfix">
                <div class="form-text fl">
                    新密码
                </div>
                <div class="form-ipt fl">
                    <input type="password" name="password" minlength="6" maxlength="15">
                    <p><span class="red">*</span>须为6~15码英文或数字夹杂</p>
                </div>
            </div>
            <div class="formItem clearfix">
                <div class="form-text fl">
                    确认新密码
                </div>
                <div class="form-ipt fl">
                    <input type="password" name="password2" minlength="6" maxlength="15">
                    <p><span class="red">*</span>请输入与上面相同的密码</p>
                </div>
            </div>

            <button  class="submit_action form-Btn fr" data-step="two">提交</button>
        </div>
        <div  class="formWrap_three formWrap clearfix hide" action="">
            <p style="color: #000000;font-size: 15px;text-align: center;padding:20px 0px;">重设密码成功</p>
            <button class="to_index form-Btn fr success" >完成</button>
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
                var username = $("input[name='username']").val();
                var password = $("input[name='password']").val();
                var password2 = $("input[name='password2']").val();
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
                                setTimeout(indexCommonObj.loadMemberLogin,alertTime) ; // 加载登录
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