<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
if( !isset($uid) || $uid == "" ) {
    echo "<script>window.location.href='/'</script>";
    exit;
}
$username=$_SESSION['UserName'];
$onlinetime=$_SESSION['OnlineTime'];
$Alias=$_SESSION['Alias'];
$birthday=$_SESSION['birthday'];
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';

//  单页面维护功能


?>

<link rel="stylesheet" type="text/css" href="/<?php echo TPL_NAME;?>/style/memberaccount.css?v=<?php echo AUTOVER; ?>" >

<div class="changpwform form-horizontal">
    <div class="form-group">
        <label class="control-label col-xs-5">
            <span class="btn-block text-left font-13 normal-weight">旧密码</span>
        </label>
        <div class="col-xs-6">
            <div class="input-group">
                <input type="password" class="form-control form-extend" id="oldpassword" name="oldpassword" minlength="4" maxlength="15">
                <span class="input-group-btn reveal-pw-toggle">
                        <span class="eye_icon pull-right"></span>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-5 newPassword">
            <span class="btn-block text-left font-13 normal-weight">新密码</span>
        </label>
        <div class="col-xs-6">
            <div class="input-group">
                <input type="password" class="form-control form-extend" id="password" name="password" minlength="6" maxlength="15">
                <span class="input-group-btn reveal-pw-toggle">
                        <span class="eye_icon pull-right"></span>
                </span>
            </div>
            <?php
                if($type =='loginpwd'){
                    echo ' <span class="form-hint">密码必须是数字和字母的组合且长度为6-15位</span>' ;
                }else{
                    echo ' <span class="form-hint">密码必须6位纯数字</span>' ;
                }
            ?>

        </div>
    </div>

    <div class="form-group">
        <label for="confPassword" class="control-label col-xs-5 confirmPassword">
            <span class="btn-block text-left font-13 normal-weight">确认密码</span>
        </label>
        <div class="col-xs-6">
            <div class="input-group">
                <input type="password" class="form-control form-extend" id="confirmpassword" name="confirmpassword" minlength="6" maxlength="15">
                <span class="input-group-btn reveal-pw-toggle">
                        <span class="eye_icon pull-right"></span>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group form-action clearfix">
        <div class="col-xs-12">
            <div class="instructions font-13" translate="msgJoinUsCaptchaNote">输入以下图形验证码</div>
        </div>
        <div class="col-xs-9 form-inline">
            <img class="pwd_captchaImg" src="/app/member/include/validatecode/captcha.php" alt="captcha" onclick="this.src='/app/member/include/validatecode/captcha.php?v='+Math.random();">
            <input type="text" class="in_verifycode form-control text-center" id="verifycode" name="verifycode" minlength="4" maxlength="6">
        </div>
        <div class="col-xs-3 text-right">
            <input type="submit" class="change_submit btn btn-default" value="提交">
        </div>
    </div>

</div>


<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/layer/layer.js"></script>
<script type="text/javascript" src="/js/loadpage_common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">


    $(function () {

        $('#verifycode').focus(function () { // 更新验证码
            $('.pwd_captchaImg').attr('src','/app/member/include/validatecode/captcha.php?v='+Math.random());
        })

        // 密码可见
        function showpsw() {
            $('.reveal-pw-toggle').on('click',function () {
                var $input = $(this).parents('.input-group').find('input');
                var ifshow = $input.attr('type') ;
                if(ifshow=='password'){
                    $input[0].type = "text" ;
                    $(this).addClass('eye_open') ;
                }else{
                    $input[0].type = "password" ;
                    $(this).removeClass('eye_open') ;
                }
            })

        }
        // 更换密码
        function changePassWordAction(){
            var changeflage = false ;
            $('.change_submit').on('click',function () {
                if(changeflage){ return }

                var type = '<?php echo $type;?>';
                var flage = 1 ;
                var oldpwd = $('#oldpassword').val() ;
                var newpwd = $('#password').val() ;
                var conpwd = $('#confirmpassword').val() ;
                var verifycode = $('#verifycode').val() ;

                if(type=='paypwd'){ // 支付密码
                    flage = 2 ;
                }
                if(!oldpwd){
                    layer.msg('请输入原密码!',{time:alertTime});
                    return;
                }
                if(!newpwd){
                    layer.msg('请输入新密码!',{time:alertTime});
                    return;
                }
                if(type == 'loginpwd'){ // 登录密码
                    if(newpwd.length <6 || newpwd.length>15){
                        layer.msg('请输入6-15位登录密码!',{time:alertTime});
                        return;
                    }
                }else{
                    if(newpwd.length !=6){
                        layer.msg('请输入6位支付密码!',{time:alertTime});
                        return;
                    }
                }
                if(newpwd != conpwd){
                    layer.msg('新密码与确认密码不一致!',{time:alertTime});
                    return;
                }
                if(!verifycode){
                    layer.msg('请输入验证码!',{time:alertTime});
                    return;
                }

                changeflage = true ;
                var url = '/app/member/account/chg_passwd.php';
                var logouturl = '/app/member/logout.php' ;
                $.ajax({
                    type : 'POST',
                    dataType : 'json',
                    url : url ,
                    data : {
                        flag_action:flage,
                        oldpassword:oldpwd,
                        password:newpwd,
                        verifycode:verifycode,
                    },
                    success:function(res) {
                        if(res){
                            changeflage = false ;
                            layer.msg(res.describe,{time:alertTime});
                            if(type == 'loginpwd'){ // 修改登录密码成功
                                if(res.status ==200){
                                    parent.location.href = logouturl ;
                                }
                            }else{  // 修改支付密码成功
                                if(res.status ==200){
                                    setTimeout(function () {
                                        parent.layer.closeAll() ;
                                    },alertTime)
                                }
                            }
                        }

                    },
                    error:function(){
                        changeflage = false ;
                        layer.msg('稍后请重试',{time:alertTime});
                    }
                });
            })
        }

        showpsw();
        changePassWordAction();


    })



</script>