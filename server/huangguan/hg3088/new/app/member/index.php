<?php

header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "include/address.mem.php";
require ("include/config.inc.php");
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";


$str   = time();
$uid   = $_REQUEST['uid'];
$langx = $_REQUEST['langx'];


if ($uid==''){
	$uid=substr(md5($str),0,8);
}
if ($langx==''){
	$langx="zh-cn";
}
$swf="tw";
switch($langx){
case 'zh-cn':
	$a1="繁體版";
	$a2="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >简体版</a>";
	$a3="<a href=".BROWSER_IP."/app/member/translate.php?set=en-us&url=".BROWSER_IP."/app/member/index.php&uid=$uid >English</a>";
	$a4="<a href=".BROWSER_IP."/app/member/translate.php?set=th-tis&url=".BROWSER_IP."/app/member/index.php&uid=$uid >ภาษาไทย</a>";
	$size='250';
	$swf="tw";
	break;
case 'zh-cn':
	$a1="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >繁體版</a></td>";
	$a2="简体版";
	$a3="<a href=".BROWSER_IP."/app/member/translate.php?set=en-us&url=".BROWSER_IP."/app/member/index.php&uid=$uid >English</a>";
	$a4="<a href=".BROWSER_IP."/app/member/translate.php?set=th-tis&url=".BROWSER_IP."/app/member/index.php&uid=$uid >ภาษาไทย</a>";
	$size='250';
	$swf="cn";
	break;
case 'en-us':
	$a1="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >繁體版</a>";
	$a2="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >简体版</a>";
	$a3="English";
	$a4="<a href=".BROWSER_IP."/app/member/translate.php?set=th-tis&url=".BROWSER_IP."/app/member/index.php&uid=$uid >ภาษาไทย</a>";
	$size='300';
	$swf="us";
	break;
case 'th-tis':
	$a1="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >繁體版</a>";
	$a2="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >简体版</a>";
	$a3="<a href=".BROWSER_IP."/app/member/translate.php?set=en-us&url=".BROWSER_IP."/app/member/index.php&uid=$uid >English</a>"; 
	$a4="ภาษาไทย";
	$swf="us";
	$size='300';
	break;
}

require ("include/traditional.$langx.inc.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="../../images/favicon.ico" type="image/x-icon">
<meta name="Robots" contect="none">
<title></title>
<link href="../../../style/member/index_login.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
<style>
    .login_yzm {position: absolute;right: 5px;}
</style>
</head>

<body  onLoad="show();">
<div class="title">
  <table class="language" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td id="lang_tw" class="lang" width="72">繁體版</td>
      <td id="lang_cn" class="lang2" width="72">简体版</td>
      <td id="lang_en" class="lang" width="72">English</td>
    </tr>
</table>
</div>
<div class="mem">
    <!--<div class="top_left"></div>-->
    <div class="index_top_pic"></div>
    <div class="log" style="height:470px">
    <form action="./login.php" method="post" name="LoginForm" onSubmit="return chk_acc();">
    	<input type=HIDDEN name="demoplay" id="demoplay" value="">
        <input type=HIDDEN name="uid" value="">
        <input type=HIDDEN name="langx" value="zh-cn">
        <input type=HIDDEN name="mac" value="">
        <input type=HIDDEN name="ver" value="">
        <input type="hidden" name="JE" value="">
        <div id="div_site" class="index_on_btn">
            <span id="oldspan" value="1" class="index_btn_left index_old_btn_out" onclick="changeVersion(this,'<?php echo HTTPS_HEAD?>')">旧网站</span>
            <span id="newspan" value="2" class="index_btn_right index_new_btn_on" onclick="changeVersion(this,'<?php echo HTTPS_HEAD?>')">新网站</span>
        </div>
            <table border="0" cellpadding="0" cellspacing="0" class="bord">
                <tr>
                    <td class="index_ID index_line"><img src="../../images/index_ID.jpg"></td>
                    <td class="index_line"><input type="text" name="username" id="username" size="15" class="index_input" maxlength="20" placeholder="帐&nbsp;&nbsp;&nbsp;&nbsp;号：" ></td>
                </tr>
                <tr>
                    <td class="index_ID"><img src="../../images/index_pass.jpg"></td>
                    <td ><input type="password" name="password" id="password" size="15" class="index_input" maxlength="20" placeholder="密&nbsp;&nbsp;&nbsp;&nbsp;码："> </td>
                </tr>

                <tr>
                    <td class="index_ID"><img src="../../images/index_pass.jpg"></td>
                    <td style="position: relative;">
                        <input type="text" name="yzm_input" id="yzm_input" minlength="4" maxlength="6" class="index_input" placeholder="验&nbsp;&nbsp;证&nbsp;&nbsp;码：">
                        <img class="login_yzm" title="点击刷新" border="1" src="include/validatecode/captcha.php"  onclick="this.src='include/validatecode/captcha.php?'+Math.random();">
                    </td>
                </tr>

            </table>
            <div class="err_info"><font id="hr_info"></font></div>
            <div class="index_re">
                 <span class="err_position">
                  <label class="label-login">
                   <!--<input name="checkbox" type="checkbox" class="index_box" id="remember" checked="">-->
                      <input name="checkbox" type="checkbox" class="validate[required]" id="Checkbox" >
                      <a href="javascript:;" class="login-remember-me" >记住我的帐号</a>
                  </label>
                   <span class="login-forget" onclick="openforgetpwd();"><img src="../../images/ico-getpwd.png" width="15" height="15" align="middle" style="vertical-align:text-top">&nbsp;忘记密码</span>
                 </span>
            </div>
        	<!--<table border="0" cellspacing="0" cellpadding="0" class="version">
              <tr>
                <td id="vs_new" class="vs_new" onClick="click_new()"><input type="radio" name="radio" id="vs_change" value="web_new" checked>升级版</td>
                <td id="vs_old" class="vs_old" onClick="click_old()"><input type="radio" name="radio" id="vs_change" value="web_old">传统版</td>
              </tr>
            </table>-->
			<div class="btn">
                <input type="submit"  class="za_button"  value="登入">
            </div>
            <div class="btn" style="margin-top:10px;">
                <input type="submit" value="试玩参观" onclick="addTryPlay()" style="height:55px;width:320px;cursor: pointer;cursor: hand;border: none;background: #8c6e18;color: #fff;font-size: 16px;">
            </div>
            <div class="btn2" style="margin-top: 10px;width: 322px;">
                <input class="reg_button" type="button" onclick="window.open('/reg.php','_register');"  value="会员注册">
                <input class="cs_button" type="button" onclick="window.open('https://static.meiqia.com/dist/standalone.html?_=t&eid=106062','','width=600,height=510');" value="在线客服">
            </div>
         </form>
        </div>
</div>

<div class="news">
    <!--<a class="to_sports" href="http://0088-2018.com" target="_blank"></a>-->
   <!-- <span class="upgrade"></span>
    <span class="tv"></span>
    <span class="moblie"></span>-->
</div>

<script type="text/javascript" src="../../js/jquery.js"></script>
<script class="language_choose" type="text/javascript" src="../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script>
<script src="../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script src="../../js/index.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    if(self == top) location='/';
    top.game_alert='';
    top.today_gmt = '<?php echo date("Y-m-d") ?>';

window.onload = function (){
    getRmemberMe() ;
}

function addTryPlay(){
	document.getElementById("demoplay").value="Yes";
	document.getElementById("username").value="demoguest";
	document.getElementById("password").value="qwertyu";
}
    $('#yzm_input').focus(function () { // 更新验证码
        $('.login_yzm').attr('src','include/validatecode/captcha.php?v='+Math.random());
    })

</script>
</body>
</html>
