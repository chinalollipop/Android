<?php
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "include/address.mem.php";
require ("include/config.inc.php");
//echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";

$afterurl = returnNewOldVersion('new');
$loginVerifyRealname = getSysConfig('login_verify_realname');

$str   = time();
$uid   = $_REQUEST['uid'];
$langx = $_REQUEST['langx'];
$pctip= isset($_REQUEST['topc'] )?$_REQUEST['topc']:'' ; // 从手机端跳转到pc端标志

if ($uid==''){
	$uid=substr(md5($str),0,8);
}
if ($langx==''){
	$langx="zh-cn";
}


require ("include/traditional.$langx.inc.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="../../images/favicon_<?php echo TPL_FILE_NAME;?>.ico" type="image/x-icon">
<meta name="Robots" contect="none">
<title></title>
<link href="../../../style/member/index_login.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
<link href="../../../style/tncode/style.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css"  />
<script type="text/javascript" src="../../style/tncode/tn_code.js?v=<?php echo AUTOVER; ?>" ></script>
<style>
    body{background: url(/images/member/2018/bg_login.jpg);background-size: cover;}
    <?php /* 新皇冠*/
          if(TPL_FILE_NAME=='newhg'){
              echo ' body{background: url(/images/member/2018/bg_login_'.TPL_FILE_NAME.'.jpg);background-size: cover;}';
          }
      ?>
    .language_tip{width:330px;height:33px;line-height:30px;margin:0 auto;position:relative}
    .language_tip a{position:relative;color:#7e7e7e;display:inline-block;margin:0 10px}
    .language_tip a.active{color:#ff9201}
    .language_tip a:first-child:before,.language_tip a:last-child:after{content:'';position:absolute;width:80px;height:2px;background:#7e7e7e;right:65px;top:14px}
    .language_tip a:last-child:after{right:-100px}
    .test_play{float:left;background:#8c6e18}
    .download_exe{width:100%;margin:10px auto;overflow-y:hidden}
    .download_exe a{display:inline-block;width:49%;height:45px;line-height:45px;background:#eee;background-size:100%;float:left;text-decoration:none;color:#5a5a5a}
    .download_exe a:last-child{float:right}
    html,body{overflow-x:hidden}
    .kf_right{display:none}
    .login_yzm{position:absolute;right:5px}
    .xin_jiu{position:absolute;height:50px;line-height:50px;width:360px;left:50%;margin:0px -180px;border-bottom:1px solid #eee}
    .xin_jiu a{position:relative;display:inline-block;width:50%;margin:0 -4px;text-decoration:none;color:#565656;background:#eee}
    .xin_jiu a.active{background:#fff}
    .xin_jiu a:first-child{border-radius:5px 0 0 0}
    .xin_jiu a:last-child{border-radius:0 5px 0 0}
    .xin_jiu a.active:before,.xin_jiu a.active:after{content:'';position:absolute;width:0;height:0;border:10px solid transparent;border-bottom-color:#dcdcdc;top:31px;left:80px}
    .xin_jiu a.active:before{top:33px;border-bottom-color:#ffffff;z-index:1}
    .xin_jiu .new_icon{display:inline-block;width:26px;height:20px;background:url(/images/member/2018/new_icon.png);background-size:100%;position:relative;top:4px;margin-right:5px}
    .index_top_pic{background:url(/images/member/2018/logo_<?php echo TPL_FILE_NAME;?>.png) center no-repeat;background-size:100%}
    /* 新年浮窗 */
    .new_year_con{display: none !important;}
</style>

<script>
    var LOGIN_IS_VERIFY_CODE='<?php echo LOGIN_IS_VERIFY_CODE ? 1:0;?>';
    var login_name='<?php echo $loginVerifyRealname;?>';
    //alert(LOGIN_IS_VERIFY_CODE);

if ((""+top.nametop)=="undefined") top.nametop="";
if ((""+top.selLang)=="undefined") top.selLang="web_new";
function show(){
	var strDomain = (document.domain).split(".");
	var strChkDomain="";
	var showVersion="N";
	for( var i=0;i<strDomain.length;i++){
		if(isNaN(strDomain[i])){
			showVersion="Y";
			break;	
		}
	}
	window.onresize = scrollFun;	
	document.forms.LoginForm.username.focus(); 
	document.forms.LoginForm.username.value=top.nametop;

}

function chk_acc(){
    // 初始化
    if(LOGIN_IS_VERIFY_CODE==1){
        var $TNCODE = tncode;
        tncode.init();
    }

	if(document.all.username.value==""){
		hr_info.innerHTML=top.account;
		document.all.username.focus();
		return false;
	}
	if(document.all.password.value==""){
		hr_info.innerHTML=top.password;
		document.all.password.focus();
		return false;
	}
	if(login_name==1){ // 登录开启真实姓名
	    if(document.all.realname.value==""){
            hr_info.innerHTML='请输入真实姓名';
            document.all.realname.focus();
            return false;
        }
    }
    rememberMe() ;
    sessionStorage.setItem('m_type','') ; // 每次登录后清空
    sessionStorage.setItem('g_type','') ; // 每次登录后清空

    if(LOGIN_IS_VERIFY_CODE==1) {
        // 验证通过
        $TNCODE.show();
        $TNCODE.onsuccess(function () {
            //验证通过
            $('input[name="yzm_input"]').val(Math.random());
            $('#index_LoginForm').submit();
            return true;
        });
    }else{
        $('input[name="yzm_input"]').val(Math.random());
        $('#index_LoginForm').submit();
        return true;
    }

}

/*function click_new(){
	top.selLang="web_new";
	document.getElementById("lang_tis").style.display = "none";

	if(document.all.langx.value=="th-tis"){
		self.location.href="http://"+document.domain+"/app/member/translate.php?set=en-us&url=app/member/index.php";
	}
}*/

function scrollFun(){
	window.scroll(document.body.scrollWidth,0);	
}

// 2018新增忘记密码
function openforgetpwd(){
    window.open('account/forget_psw.php','_blank','width=390px,height=650px,top=0,left=0px,titlebar=0,toolbar=0');
}

</script>

</head>

<body  onLoad="show();">

<div class="mem">
    <!--<a class="go_to_new" href="<?php /*echo $afterurl;*/?>" target="_blank"> </a>-->

    <div class="index_top_pic"></div>
    <div class="language_tip">
       <!-- <a > 繁体版 </a>
        <a class="active"> 简体版 </a>-->
    </div>
    <div class="xin_jiu">
        <a href="javascript:;" class="active" data-type="old">旧版</a>
        <a href="javascript:;" data-type="new"><span class="new_icon" ></span>新版</a>
    </div>
    <div class="log" > <!-- style="height:430px" -->
    <form action="/login.php" method="post" name="LoginForm" id="index_LoginForm" target="_top">
    	<input type="hidden" name="demoplay" id="demoplay" value="">
        <input type="hidden" name="langx" value="zh-cn">
        <input type="hidden" name="topc" value="<?php echo $pctip;?>">
        <input type="hidden" name="yzm_input" value="">
        <input type="hidden" name="sign" value="">


            <table border="0" cellpadding="0" cellspacing="0" class="bord">
                <tr>
                    <td class="index_ID index_line"><img src="../../images/member/2018/index_ID.png"></td>
                    <td class="index_line"><input type="text" name="username" id="username" size="15" class="index_input" maxlength="20" placeholder="帐&nbsp;&nbsp;&nbsp;&nbsp;号：" ></td>
                </tr>
                <tr>
                    <td class="index_ID index_line"><img src="../../images/member/2018/index_pass.png"></td>
                    <td class="index_line"><input type="password" name="password" id="password" size="15" class="index_input" maxlength="20" placeholder="密&nbsp;&nbsp;&nbsp;&nbsp;码："> </td>
                </tr>
                <?php

                if ($loginVerifyRealname==1) {
                    ?>
                    <tr>
                        <td class="index_ID index_line"><img src="../../images/member/2018/index_ID.png"></td>
                        <td class="index_line"><input type="text" name="realname" id="realname" size="15" class="index_input" maxlength="20" placeholder="阁下名字："> </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div style="color:red; text-align: center;">注：请输入阁下账户名字，以确保是本人操作！</div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <!--<tr>-->
                    <!--<td class="index_line" style=" position: relative;">
                        <input type="text" name="yzm_input" id="yzm_input" minlength="4" maxlength="6" class="index_input" placeholder="验&nbsp;&nbsp;证&nbsp;&nbsp;码：" >
                        <img class="login_yzm" title="点击刷新" border="1" src="include/validatecode/captcha.php"  onclick="this.src='include/validatecode/captcha.php?v='+Math.random();">
                    </td>-->
                    <!--<td colspan="2"><div class="btn tncode"></div></td>-->
                <!--</tr>-->
            </table>
            <div class="err_info"><font id="hr_info"></font></div>
            <div class="index_re">
                 <span class="err_position">
                  <label class="label-login">
                      <input name="checkbox" type="checkbox" class="validate[required]" id="Checkbox" >
                      <a href="javascript:;" class="login-remember-me" >记住我的帐号</a>
                  </label>
<!--                   <span class="login-forget" onclick="openforgetpwd();"><img src="../../images/member/2018/ico-getpwd.png" width="15" height="15" align="middle" style="vertical-align:text-top">&nbsp;忘记密码</span>-->
                   <span class="login-forget to_livechat" "><img src="../../images/member/2018/ico-getpwd.png" width="15" height="15" align="middle" style="vertical-align:text-top">&nbsp;忘记密码</span>
                 </span>
            </div>
            <div class="btn ">
               <input type="button" class="za_button" onclick="chk_acc()"value="登入" readonly >
            </div>
            <div class="btn" style="margin-top:10px;">
                <input class="reg_button" type="button" onclick="window.open('/reg.php','_register');"  value="会员注册">
            </div>
            <div class="btn" style="margin-top: 10px;">
                <?php
                // 试玩必须输入手机号的开关：TRUE 跳转到输入手机号的页面，FALSE 直接登入试玩
                if (GUEST_LOGIN_MUST_INPUT_PHONE){
                    echo '<input class="cs_button test_play" type="button" value="试玩参观" onclick="window.open(\'/guest_login.php\',\'_guest_login\');" >';
                }
                else{
                    echo '<input class="cs_button tncode test_play" type="button" value="试玩参观" style="float:left;background:#8c6e18;width:49%;height:45px;cursor:hand;border:none;color:#fff;font-size:16px;font-family:simsun;border-radius: 0px;" onclick="addTryPlay()" >';
                }
                ?>
                <input class="cs_button to_livechat" type="button" value="在线客服">
            </div>
         </form>
        <?php
            if(TPL_FILE_NAME !='newhg'){
                echo '
                     <div class="download_exe">
                        <a class="downloadWinExe" target="_blank">win客户端</a>
                        <a class="downloadMacExe" target="_blank">mac客户端</a>
                    </div>
                    ';
            }
        ?>

        </div>

</div>

<!--<img style="margin-top: 10px;" src="../../images/member/2018/6668_login.png">-->
<div class="news">
    <!--<a class="to_sports" href="http://0088-2018.com" target="_blank"></a>-->
<!--    <span class="upgrade"></span>-->
<!--    <span class="tv"></span>-->
<!--    <span class="moblie"></span>-->
</div>
<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../js/zh-cn.js"></script>
<script src="../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
window.onload = function (){

    var newurl = '<?php echo $afterurl;?>';
    // console.log(newurl);

    // 基本配置
    function getBaseSetting() {
        $('.to_livechat').attr({"onclick":"window.open('"+top.configbase.service_meiqia+"','','width=600,height=510')"}); // 在线客服
        $('.downloadWinExe').attr({"href":top.configbase.download_win_exe}); // win
        $('.downloadMacExe').attr({"href":top.configbase.download_win_exe}); // mac

    }
    
    // 新旧版切换
    function changeNewOld() {
        $('.xin_jiu').on('click','a',function () {
            var type = $(this).attr('data-type');
            var login_url='/login.php';
            var sign = '';
            $(this).addClass('active').siblings().removeClass('active');
            if(type=='new'){ // 新版
                login_url = newurl+login_url;
                sign = 'oldtonew';
            }
            $('[name="sign"]').val(sign);
            $('[name="LoginForm"]').attr('action',login_url);

        })
    }
    // 判断是否有记住帐号
    function getRmemberMe() {
        var username = getCookieAction('username') ;
        var password = getCookieAction('password') ;
        var ifremeber = getCookieAction('ifremeberme') ;
        // console.log(username) ;
        if(ifremeber){
            document.all.checkbox.checked = true;
        }
        if(username){
            document.all.username.value = username ;
        }
        if(password){
            document.all.password.value = password ;
        }

    }
    getRmemberMe() ;
    changeNewOld();
    getBaseSetting();

}
// 记住我的帐号
function rememberMe() {
    var ifremeber = document.all.checkbox.checked ;
    var username = document.all.username.value ;
    var password = document.all.password.value ;
    // console.log(ifremeber) ;
    // console.log(document.all.username.value) ;
    // console.log(document.all.password.value) ;
    if(ifremeber){
        setCookieAction('ifremeberme',ifremeber) ;
        setCookieAction('username',username) ;
        setCookieAction('password',password) ;
    }else {
        delCookieAction('ifremeberme') ;
        delCookieAction('username') ;
        delCookieAction('password') ;

    }
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
