<?php
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require ("../../app/agents/include/config.inc.php");

$langx = "zh-cn";
$level = "D";
$actiontype = isset($_REQUEST['actionType'])?$_REQUEST['actionType']:''; // 手机代理注册成功后跳转
$username = isset($_REQUEST['UserName'])?$_REQUEST['UserName']:''; // 手机代理注册成功后跳转
$password = isset($_REQUEST['PassWord'])?$_REQUEST['PassWord']:''; // 手机代理注册成功后跳转

$logo = "logo/logo_".TPL_FILE_NAME.".png";

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <META name="keywords" content="<?php echo COMPANY_NAME;?>,<?php echo COMPANY_NAME;?>登入,<?php echo COMPANY_NAME;?>平台">
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="/images/favicon_<?php echo TPL_FILE_NAME;?>.ico" type="image/x-icon"/>
    <link href="../css/main.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title> <?php echo COMPANY_NAME;?> </title>
    <style type="text/css">
        /* 登录 */
        .contentLogin{padding:1rem 8%}
        .login-top{width:16rem;height:8rem;margin:3rem auto;background:url(../images/<?php echo $logo;?>) center no-repeat;background-size:100%}
        <?php
            if(TPL_FILE_NAME=='wnsr'){
               echo ' .login-top{height:10rem;background-size: 80%;}';
            }
        ?>
        .login-middle div{height:3rem;margin-top:1rem;border-bottom:1px solid #dcdcdc}
        .login-middle div span{display:inline-block;width:2rem;height:2rem;float:left;margin:.5rem 0 0 .5rem}
        .login-middle div .username-logo{background:url(/images/<?php echo $logoUrl;?>/account_icon.png) no-repeat}
        .login-middle div .password-logo{background:url(/images/<?php echo $logoUrl;?>/password_icon.png) no-repeat}
        .login-middle input{height:100%;line-height:3rem;width:86%;padding:0 5px}

    </style>
</head>
<body>
<div class="contentLogin">

    <div class="login-top"> </div>
    <div class="centerContent">
        <div class="login-middle">
            <input type="hidden" name="level" class="userLevel" value="D">
            <div>
                <span class="username-logo"></span>
                <input type="text" class="loginUserName" placeholder="请输入用户名" minlength="4" maxlength="15"/>
            </div>
            <div>
                <span class="password-logo"></span>
                <input type="password" class="loginPassWord" placeholder="请输入密码" minlength="6" maxlength="15"/>
            </div>
        </div>
        <div class="remember-me" style="display:none;line-height: 44px;text-align: left;">
            <label class="label-login">
                <input name="checkbox" type="checkbox"  id="Checkbox" style="float: left;">
                <span  class="login-remember-me" style="color: #3c3941;font-size: 1rem;">记住我的帐号</span>
            </label>
        </div>

    </div>
    <a href="javascript:;" class="submit_login btn  linear_1"> 登录 </a>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    $(function () {
        var tipMobile = '<?php echo $actiontype;?>';
        var username = '<?php echo $username;?>';
        var password = '<?php echo $password;?>';
        loginAccountAction('mobile');
        regAutoLogin();

        // 处理手机版代理注册自动登录
        function regAutoLogin() {
            if(tipMobile){
                $(".loginUserName").val(username);
                $(".loginPassWord").val(password);
                $(".submit_login").click();

            }
        }

    })

</script>
</body>
</html>
