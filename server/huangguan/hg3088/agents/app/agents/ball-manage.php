<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "./include/address.mem.php";
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("./include/config.inc.php");
require ("include/google.authenticator.php");
$langx = $_REQUEST['langx'];
if ($langx==''){ // 默认简体
	$langx="zh-cn";
}
// 生成二维码信息
$ga = new PHPGangsta_GoogleAuthenticator();
$qrCodeUrl = $ga->getQRCodeGoogleUrl(Identification, SecretKey);

require ("./include/traditional.$langx.inc.php");

if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','jinsha'])){
    $_SESSION['template'] = 'old';
}else{
    $_SESSION['template'] = 'new'; // 新彩票
}

?>
<html>
<head>
<title>管理员登录</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../style/agents/control_index.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
 <?php
 if($_SESSION['template'] == 'new'){
     echo '<link href="../../style/'.TPL_FILE_NAME.'.css?v='.AUTOVER.'" rel="stylesheet" type="text/css">';
 }
 ?>
</head>
<body >
<div class="top-header">
    <a >繁體版</a> <!-- href="./ball-manage.php?langx=zh-tw" -->
    <a  class="link_on">简体版</a> <!-- href="./ball-manage.php?langx=zh-cn" -->
    <a >English</a> <!-- href="./ball-manage.php?langx=en-us" -->
    <!--  <a href="./ball-manage.php?langx=jis-jp">日本語</a>
      <a href="./ball-manage.php?langx=lit-tu">Türkçe</a>-->
</div>
<div class="all">
        <?php
        if( $_SESSION['template']=='old'){
            echo '<div class="top-logo"> </div>';
        }
        ?>

    <div class="login-table">

        <form name="LoginForm" method="post" > <!-- action="chk_login.php" onSubmit="return chk_acc('<?php echo $Please_enter_your_username?>','<?php echo $Please_enter_your_password?>');" -->

            <input type="hidden" name="level" class="userLevel" value="M">
            <div class="login-top">
               <!-- <input name="level" type="radio" value="A">
                总监
                <input name="level" type="radio" value="B">
                股东
                <input name="level" type="radio" value="C">
                总代理
                <input name="level" type="radio" value="D" >
                代理商-->
            </div>
            <div class="login-middle">
                <div ><span class="username-logo"></span> <input id="UserName" class="loginUserName"  type="text" name="UserName" placeholder="登录帐号" minlength="4" maxlength="15" value="" /> </div>
                <div ><span class="password-logo"></span> <input id="PassWord" class="loginPassWord" type="password" name="PassWord" placeholder="密码" minlength="6" maxlength="15" autocomplete="off" /> </div>
                <?php
                    if(CHECK_CODE_SWITCH) {
                        echo '<div ><span class="password-logo"></span> <input id="captcha" class="loginCaptcha" type="text" name="captcha" placeholder="验证码"  value="" /> </div>';
                    }
                ?>
            </div>
            <div class="remember-me">
                <label class="label-login">
                    <input name="checkbox" type="checkbox"  id="Checkbox">
                    <span  class="login-remember-me">记住我的帐号</span>
                </label>
            </div>
            
            <div class="login-bottom">
                <input class="submit_login login" name="Submit" id="Forms Button1" align="middle" value="登入" readonly /> <!-- type="submit"  -->
            </div>
        </form>
    </div>


</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/<?php echo $langx?>.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" type="text/JavaScript">
    loginAccountAction();

</script>
</body>
</html>

