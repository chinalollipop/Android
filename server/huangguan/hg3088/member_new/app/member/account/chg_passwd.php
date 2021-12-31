<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");

// 连接彩票主库
$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$action=$_REQUEST['action'];
$mtype=$_REQUEST['mtype'];
require ("../include/traditional.$langx.inc.php");
$mysql="Select ID,UserName,PassWord,Address,EditDate from ".DBPREFIX.MEMBERTABLE." where Oid='$uid'";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);

$UserName=$row['UserName']; // 登录用户名
$password=$row['PassWord']; // 登录密码
$pay_password=$row['Address']; // 支付密码
$editdate=$row['EditDate'];
if ($action==1){
	$flag_action=$_REQUEST["flag_action"]; // 用户判断是更改登录密码还是支付密码
    $oldpasd = trim($_REQUEST["oldpassword"]); // 原登录密码
    $pasd = trim($_REQUEST["password"]); // 新登录密码
    if(TPL_FILE_NAME !='newhg'){ // 新皇冠不需要强制转小写
        $oldpasd = strtolower($oldpasd);
        $pasd = strtolower($pasd);
    }
    $mdoldpasd = passwordEncryption($oldpasd,$UserName);
	$mdpasd = passwordEncryption($pasd,$UserName); // md5加密后
    $pay_oldpasd=strtolower($_REQUEST["pay_oldpassword"]); // 原支付密码
    $pay_pasd=strtolower($_REQUEST["pay_password"]); // 新支付密码

	$date=date("Y-m-d");
	$todaydate=strtotime(date("Y-m-d"));
	$editdate=strtotime($editdate);
	$time=($todaydate-$editdate)/86400;
	if($flag_action =='1'){ // 修改登录密码
        if(passwordEncryption($oldpasd,$UserName) != $password){ // 原密码不正确
            echo "<Script language=javascript>alert('原登录密码错误！'); history.back(-1);</script>";exit() ;
        }

        $mysql = "update ".DBPREFIX.MEMBERTABLE." set PassWord='$mdpasd',EditDate='$date' , Online=1 , OnlineTime=now() where Oid='$uid'";
        $result = mysqli_query($dbMasterLink, $mysql) or die ("操作失败!");
        if (!$result) {
            // 更改失败还原会员密码
            $rallbacksql = "update ".DBPREFIX.MEMBERTABLE." set PassWord='$mdoldpasd',EditDate='$date' , Online=1 , OnlineTime=now() where Oid='$uid'";
            mysqli_query($dbMasterLink,$rallbacksql);
            echo "<Script language=javascript>alert('密码修改失败')";
            exit;
        }
        $cpsql = "UPDATE gxfcy_user SET userpsw='".$mdpasd."' where hguid=".$row['ID'];
        $updateUserPass = mysqli_query($cpMasterDbLink,$cpsql);//更新彩票用户密码
        if($updateUserPass) {
            if ($time>30){
                echo "<Script language=javascript>alert('已成功的变更了您的密码~~请回首页重新登入');window.open('".BROWSER_IP."','_top');window.close();</script>";
            }else{
                echo "<Script language=javascript>alert('已成功的变更了您的密码~~请回首页重新登入');opener.top.location='".BROWSER_IP."';window.close();</script>";
            }
        }
    }else{ // 修改支付密码
        if($pay_oldpasd != $pay_password){ // 原密码不正确
            echo "<Script language=javascript>alert('原支付密码错误！'); history.back(-1);</script>";exit() ;
        }
        $mysql="update ".DBPREFIX.MEMBERTABLE." set Address='$pay_pasd',EditDate='$date' , Online=1 , OnlineTime=now() where Oid='$uid'";
        mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
        if ($time>30){
            echo "<Script language=javascript>alert('已成功的变更了您的密码');window.close();</script>";
        }else{
            echo "<Script language=javascript>alert('已成功的变更了您的密码');window.close();</script>";
        }
    }

}
?>
<html>
<head>
<title>Please Key in Password</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/member/mem_pass.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<body id="CHG" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<!-- 登录密码开始 -->
<?php if($_SESSION['Agents']=='demoguest'){?>
<div class="main">
        <table class="edit-pwd">
            <thead><tr><td><center>请注册正式用户！</center></td></tr></thead>
        </table>
</div>
<!-- 提款密码结束 -->
<?php }else{ ?>
<div class="main">
	<form method=post name="chg_log_password" id="chg_log_password" onSubmit="return SubChk();">
		<input type="hidden" name="action" value="1">
		<input type="hidden" name="uid" value="<?php echo $uid?>">
		<input type="hidden" name="flag_action" value="1"> <!-- 1 为修改登录密码，2 为修改支付密码 -->
		<div class="main_bg">
            <div class="main_box">
                <table border="0" cellpadding="0" cellspacing="0" class="edit-pwd">
                <thead>
                <tr>
                    <th>更新登录密码</th>
                    <td colspan="2">为了您的帐户安全，我们强烈建议您每30天修改一次密码。</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>现用密码：</th>
                    <td width="100"><input type="password" name="oldpassword" placeholder="请输入原登录密码" minlength="6" maxlength="15"  class="inp-txt"></td>
                    <td rowspan="3" class="dec">
                        <strong>说明:</strong>
                       <!-- <br>1. 您的新密码必须由6-15个字母和数字<br>　 (A-Z 和 0-9)组成。
                        <br>2. 您的新密码不能和现用的密码相同。-->
                        <br>1.<?php echo $Chg_Password_must_involve_numbers_and_letters?>
                        <br>2.<?php echo $Chg_Password_must_include_6_12_words?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $Chg_Password?>：</th>
                    <td><input type="password" name="password" placeholder="请输入新登录密码" minlength="6" maxlength="15" class="inp-txt"></td>
                </tr>
                <tr>
                    <th><?php echo $Chg_Confirm_Password?>：</th>
                    <td><input type="password" name="REpassword"  placeholder="请确认新登录密码" minlength="6" maxlength="15" class="inp-txt"></td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3">
                        <input type="submit" name="OK" value="确认提交" class="btn1">
                        <input type="button" name="cancel" value="取消操作" class="btn6" onclick="javascript:window.close();">
                        <input name="day" type="button" value="30天候再提醒" style="display:none;">
                    </td>
                </tr>
                </tfoot>
                </table>

            </div>
        </div>
	</form>
</div>
<!-- 登录密码结束 -->
<!-- 提款密码开始 -->
<div class="main">
    <form method="post" name="chg_pay_password" id="chg_pay_password" onSubmit="return paySubChk()">
        <input type="hidden" name="action" value="1">
        <input type="hidden" name="uid" value="<?php echo $uid?>">
        <input type="hidden" name="flag_action" value="2"> <!-- 1 为修改登录密码，2 为修改支付密码 -->
        <table class="edit-pwd">
            <thead>
            <tr>
                <th class="c4">更改提款密码</th>
                <td colspan="2">为了您的资金安全，我们强烈建议您不定时修改密码。</td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>现用密码：</th>
                <td width="100"><input type="password" name="pay_oldpassword" minlength="4" maxlength="6" placeholder="请输入原支付密码" class="inp-txt"></td>
                <td rowspan="3" class="dec">
                    <strong>说明:</strong>
                    <br>1. 您的新密码必须由6位数字 (0-9) 组成。
                    <br>2. 您的新密码不能和现用的密码相同。
                </td>
            </tr>
            <tr>
                <th>新密码：</th>
                <td><input type="password" name="pay_password" minlength="6" maxlength="6"  placeholder="请输入新支付密码" class="inp-txt"></td>
            </tr>
            <tr>
                <th>确认新密码：</th>
                <td><input type="password" name="pay_REpassword" minlength="6" maxlength="6"  placeholder="请确认新支付密码" class="inp-txt"></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3">
                    <input type="submit" name="OK" value="确认提交" class="btn3"  >
                    <input type="button" name="cancel" value="取消操作" class="btn6" onclick="javascript:window.close();">
                    <input name="day" type="button" value="30天候再提醒" style="display:none;">
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<?php } ?>
<script type="text/javascript" src="../../../js/<?php echo $langx?>.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    var pass = "<?php echo $password ?>"; // 原登录密码
    var pay_pass = "<?php echo $pay_password ?>"; // 原支付密码

    // 登录密码验证
    function SubChk(){
        var Numflag = 0;
        var Letterflag = 0;

        var $oldpwd = document.chg_log_password.oldpassword ; // 原密码
        var $pwd = document.chg_log_password.password ;
        var $REpwd = document.chg_log_password.REpassword ;
        var oldpwd =  $oldpwd.value; // 原密码
        var pwd = $pwd.value;
        var repwd = $REpwd.value ;

        if(oldpwd ==''){
            alert(top.str_input_oldpwd);
            $oldpwd.focus();
            return false;
        }
        /*if(oldpwd != pass){  // 原密码不正确
            alert(top.str_input_notoldpwd);
            document.chg_log_password.oldpassword.focus();
            return false;
        }*/

        if (pwd == pass) {  // 现密码原原密码相同
            alert(top.str_pwd_NoChg);
            return false;
        }
        if (pwd ==''){
            $pwd.focus();
            alert(top.str_input_pwd);
            return false;
        }
        if (repwd ==''){
            $REpwd.focus();
            alert(top.str_input_repwd);
            return false;
        }
        if (pwd.length < 6 || pwd.length > 15) {
            alert(top.str_pwd_limit);
            return false;
        }
        if(pwd != repwd){
            $pwd.focus();
            alert(top.str_err_pwd);
            return false;
        }
        for (idx = 0; idx < pwd.length; idx++) {
            //====== 密碼只可使用字母(不分大小寫)與數字
            if(!((pwd.charAt(idx)>= "a" && pwd.charAt(idx) <= "z") || (pwd.charAt(idx)>= 'A' && pwd.charAt(idx) <= 'Z') || (pwd.charAt(idx)>= '0' && pwd.charAt(idx) <= '9'))){
                alert(top.str_pwd_limit);
                return false;
            }
            if ((pwd.charAt(idx)>= "a" && pwd.charAt(idx) <= "z") || (pwd.charAt(idx)>= 'A' && pwd.charAt(idx) <= 'Z')){
                Letterflag++;
            }
            if ((pwd.charAt(idx)>= "0" && pwd.charAt(idx) <= "9")){
                Numflag++;
            }
        }
        //====== 密碼需使用字母加上數字
        var msg = "";
        if (Numflag == 0 || Letterflag == 0) {
            alert(top.str_pwd_limit2);
            return false;
        } else if (Letterflag >= 1 && Letterflag <= 3) {
            msg = "1";
        } else if (Letterflag >= 4 && Letterflag <= 8) {
            msg = "2";
        } else if (Letterflag >= 9 && Letterflag <= 11) {
            msg = "3";
        } else {
            return false;
        }

        return true;
    }
    // 支付密码验证
    function paySubChk(){
        var Numflag = 0;
        var $oldpwd = document.chg_pay_password.pay_oldpassword ;
        var $pwd = document.chg_pay_password.pay_password ;
        var $REpwd = document.chg_pay_password.pay_REpassword ;
        var oldpwd =  $oldpwd.value; // 原密码
        var pwd = $pwd.value;
        var repwd = $REpwd.value ;

        if(oldpwd ==''){
            alert(top.str_input_oldpwd);
            $oldpwd.focus();
            return false;
        }

        if (pwd == pay_pass) {  // 现密码原原密码相同
            alert(top.str_pwd_NoChg);
            return false;
        }
        if ($pwd.value==''){
            $pwd.focus();
            alert(top.str_input_pwd);
            return false;
        }
        if (repwd ==''){
            $REpwd.focus();
            alert(top.str_input_repwd);
            return false;
        }
        if (pwd.length < 4 || pwd.length > 6) {
            alert(top.str_pay_pwd_limit);
            return false;
        }
        if(pwd != repwd){
            $pwd.focus();
            alert(top.str_err_pwd);
            return false;
        }
        for (idx = 0; idx < pwd.length; idx++) {
            // 密碼只可使用数字
            if(!(pwd.charAt(idx)>= '0' && pwd.charAt(idx) <= '9')){
                alert(top.str_pay_pwd_limit);
                return false;
            }

            if ((pwd.charAt(idx)>= "0" && pwd.charAt(idx) <= "9")){
                Numflag++;
            }
        }
        //====== 密碼需使用数字
        var msg = "";
        if (Numflag == 0 ) {
            alert(top.str_pay_pwd_limit);
            return false;
        }

      // document.getElementById('chg_pay_password').removeAttribute('onsubmit').submit() ;
       return true;
    }
</script>

</body>
</html>