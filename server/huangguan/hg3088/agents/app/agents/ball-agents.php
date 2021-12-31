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
// require ("./include/look_area.php"); // 这个php 文件会造成页面有空行

$sql = "select Website from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
if ($row['Website']==1){
	echo "<script>window.open('/upup/upup.php','_top')</script>";
}
//过滤地区IP
$ip = get_ip();
$ip_arr=explode(",",$ip);

$langx = $_REQUEST['langx'];
if ($langx==''){ // 默认简体
	$langx="zh-cn";
}
require ("./include/traditional.$langx.inc.php");

$agenturl = explode(';',$registeredAgent);
$fetch_num = array_rand($agenturl,1);
$afterurl = $agenturl[$fetch_num]; // 随机取一个配置的域名

/*switch($langx){
case 'zh-tw':
	$a1="<a href=".BROWSER_IP."/app/agents/translate.php?set=big5&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/big5/login_tw.jpg' border=0 title='繁體中文'></a>";
	$a2="<a href=".BROWSER_IP."/app/agents/translate.php?set=gb&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/gb/login_cn.jpg'  border=0 title='简体中文'></a>";
	$a3="<a href=".BROWSER_IP."/app/agents/translate.php?set=en-us&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/us/login_us.jpg'  border=0 title='English'></a>";
	$a4="<a href=".BROWSER_IP."/app/agents/translate.php?set=th-tis&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/tis/login_tis.jpg'  border=0 title='Thailand'></a>";
	$size="5";
	$t1="請輸入帳號!!";
	$t2="請輸入密碼!!";
	$t3="請輸入檢查碼!!";
	$t4="檢查碼輸入錯誤請重新輸入!!";
	$t5="檢&nbsp;查&nbsp; 碼:";

	break;
case 'zh-cn':
	$a1="<a href=".BROWSER_IP."/app/agents/translate.php?set=big5&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/big5/login_tw.jpg' border=0 title='繁體中文'></a>";
	$a2="<a href=".BROWSER_IP."/app/agents/translate.php?set=gb&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/gb/login_cn.jpg'  border=0 title='简体中文'></a>";
	$a3="<a href=".BROWSER_IP."/app/agents/translate.php?set=en-us&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/us/login_us.jpg'  border=0 title='English'></a>";
	$a4="<a href=".BROWSER_IP."/app/agents/translate.php?set=th-tis&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/tis/login_tis.jpg'  border=0 title='Thailand'></a>";
	$size="5";
	$t1="请输入帐号!!";
	$t2="请输入密码!!";
	$t3="请输入检查码!!";
	$t4="检查码输入错误请重新输入!!";
	$t5="检 查 码:";
	break;

case 'en-us':
	$a1="<a href=".BROWSER_IP."/app/agents/translate.php?set=big5&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/big5/login_tw.jpg' border=0 title='繁體中文'></a>";
	$a2="<a href=".BROWSER_IP."/app/agents/translate.php?set=gb&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/gb/login_cn.jpg'  border=0 title='简体中文'></a>";
	$a3="<a href=".BROWSER_IP."/app/agents/translate.php?set=en-us&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/us/login_us.jpg'  border=0 title='English'></a>";
	$a4="<a href=".BROWSER_IP."/app/agents/translate.php?set=th-tis&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/tis/login_tis.jpg'  border=0 title='Thailand'></a>";
	$size="4";
	$css='_en';
	$t1="Please enter your username!";
	$t2="Please enter your password!";
	$t3="Enter the code you see below!";
	$t4="Please refill-in the correct code!!";
	$t5="Enter the<br> code shown";
	$rule_bottom="Copyright by 888royal.com Better view and performance with IE 5.0 800*600 or above ";
	break;

case 'th-tis':
	$a1="<a href=".BROWSER_IP."/app/agents/translate.php?set=big5&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/big5/login_tw.jpg' border=0 title='繁體中文'></a>";
	$a2="<a href=".BROWSER_IP."/app/agents/translate.php?set=gb&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/gb/login_cn.jpg'  border=0 title='简体中文'></a>";
	$a3="<a href=".BROWSER_IP."/app/agents/translate.php?set=en-us&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/us/login_us.jpg'  border=0 title='English'></a>";
	$a4="<a href=".BROWSER_IP."/app/agents/translate.php?set=th-tis&url=".BROWSER_IP."/app/agents/ball-home.php&uid=$uid><img src='/images/agents/tis/login_tis.jpg'  border=0 title='Thailand'></a>";
	$size="4";
	$css='_en';
	$t1="Please enter your username!";
	$t2="Please enter your password!";
	$t3="Enter the code you see below!";
	$t4="Please refill-in the correct code!!";
	$t5="Enter the<br> code shown";
	$rule_bottom="Copyright by 888royal.com Better view and performance with IE 5.0 800*600 or above ";
	break;
}*/

if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','jinsha'])){
    $_SESSION['template'] = 'old';
}else{
    $_SESSION['template'] = 'new'; // 新彩票
}

?>
<html>
<head>
<title>代理登录后台</title>

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
    <a >繁體版</a> <!-- href="./ball-agents.php?langx=zh-tw" -->
    <a  class="link_on">简体版</a> <!-- href="./ball-agents.php?langx=zh-cn" -->
    <a>English</a> <!--  href="./ball-agents.php?langx=en-us" -->
  <!--  <a href="./ball-agents.php?langx=jis-jp">日本語</a>
    <a href="./ball-agents.php?langx=lit-tu">Türkçe</a>-->
</div>
<div class="all">
    <?php
        if( $_SESSION['template']=='old'){
            echo '<div class="top-logo"> </div>';
        }
    ?>
    <div class="login-table">

            <form name="LoginForm" method="post" > <!-- action="chk_login.php"  onSubmit="return chk_acc('<?php echo $Please_enter_your_username?>','<?php echo $Please_enter_your_password?>');" -->

                <input type="hidden" name="level" class="userLevel" value="D" />
                <div class="login-top">
                   <!-- <input name="level" type="radio" value="A">
                    总监
                    <input name="level" type="radio" value="B">
                    股东
                    <input name="level" type="radio" value="C">
                    总代理
                    <input name="level" type="radio" value="D" checked>
                    代理商-->
                    <?php
                    if( $_SESSION['template']=='old'){
                        echo '<span class="log_btn">登入 1</span>
                            <span class="log_btn">登入 2</span>
                            <span class="log_btnON">登入 3</span>';
                    }
                    ?>

                </div>
                <div class="login-middle">
                    <div ><span class="username-logo"></span> <input id="UserName" class="loginUserName" type="text" name="UserName" placeholder="登录帐号" minlength="4" maxlength="15" value="" /> </div>
                    <div ><span class="password-logo"></span> <input id="PassWord" class="loginPassWord" type="password" name="PassWord" placeholder="密码" minlength="6" maxlength="15" autocomplete="off" /> </div>
                </div>
                <div class="remember-me">
                    <label class="label-login">
                        <input name="checkbox" type="checkbox"  id="Checkbox">
                        <span  class="login-remember-me">记住我的帐号</span>
                    </label>
                </div>
                <div class="login-bottom">
                    <input class="submit_login login" name="Submit" id="Forms Button1" value="登入" readonly /> <!-- type="submit" -->
                    <a class="login" href="<?php echo $afterurl.'?agent';?>" target="_top">代理注册</a>
                </div>
        </form>
    </div>


</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="../../js/agents/<?php echo $langx?>.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" type="text/JavaScript">
    loginAccountAction();

</script>

</body>
</html>

