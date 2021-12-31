<?php
session_start();
header ("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

require ("app/member/include/config.inc.php");


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>填写手机号-试玩登入</title>
    <link rel="shortcut icon" href="images/favicon_<?php echo TPL_FILE_NAME;?>.ico" type="image/x-icon">
    <link href="style/Reg.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
    <style type="text/css">
        .ui-header{ background:url(../images/logoreg_<?php echo TPL_FILE_NAME;?>.png) center center no-repeat #f1ae33;}
        <?php /* 新皇冠*/
           if(TPL_FILE_NAME=='newhg'){
               echo ' .ui-header{ background:url(../images/member/2018/logo_'.TPL_FILE_NAME.'.png) center center no-repeat #424242;}';
           }
       ?>
    </style>
</head>
<body ondragstart="window.event.returnValue=false" onselectstart="event.returnValue=false" oncontextmenu="window.event.returnValue=false">
<div class="ui-header"></div>
    <center>
        <div id="Login" class="register">
            <h1 align="left" style="width: 175px;">
                填写手机号-提交登入试玩</h1>
            <table width="880" class="lineJL" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <form action="" method="post" name="LoginForm" id="index_LoginForm"  target="_top">

                <tr>
                    <td align="right" class="style1" valign="top">
                        <span class="red">*</span>手机号码：
                    </td>
                    <td class="style3" valign="top">
                        <input name="phone" type="text"  id="phone" minlength="11" maxlength="11"><span class="Reginput"></span>
                    </td>

                </tr>
                <tr>
                    <td align="right" class="style1" valign="top">
                        <span class="red">*</span>验证码：
                    </td>
                    <td class="style3" valign="top">
                        <input id="verifycode" name="verifycode" type="text" tabindex="2" style="width:100px; height:30px" minlength="4" maxlength="4" >
                        <img title="点击刷新" class="yzm_code" border='1' src="app/member/include/validatecode/captcha.php" align="absbottom" onclick="this.src='app/member/include/validatecode/captcha.php?'+Math.random();"/>
                    </td>
                </tr>

                <tr>
                    <td class="style2">
                    </td>
                    <td class="style4">
                        <input type="button" value="确认提交" onclick="guest_login_save_phone_submit();">
                        <input type="reset" value="重新输入" >
                    </td>

                </tr>
			</form>
            </tbody></table>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/register/validate.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    $('#verifycode').focus(function () { // 更新验证码
        $('.yzm_code').attr('src','app/member/include/validatecode/captcha.php?v='+Math.random());
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
                    window.location.href = '/login.php?demoplay=Yes';

                }else {
                    alert(ret.describe);
                }
            },
            error: function (XMLHttpRequest, status) {
                alert('网络错误，稍后请重试!!');
            }
        });

    }
    
</script>

</body>
</html>
