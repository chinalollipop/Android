<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "./include/address.mem.php";
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("./include/config.inc.php");

//checkAdminLogin(); // 同一账号不能同时登陆

if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "")) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_SESSION['Oid'];
$langx="zh-cn";

require ("./include/traditional.$langx.inc.php");


?>
<html>
<head>
<title>更改密码</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">

</style>
</head>
<body >
<dl class="main-nav">
    <dt>更改密码</dt>
    <dd></dd>
</dl>
<div class="main-ui all width_1000">

      <table width="280"  border="0" align="center" cellpadding="0" cellspacing="2" class="border m_tab_ed">
        <tr class="m_bc_ed">
          <td colspan="2" class="reg_tit1" align="left"><?php echo $Mem_Caption?></td>
        </tr>
        <form method="post"> <!-- onSubmit="return SubChk();" -->
          <tr class="m_bc_ed">
            <td class="ad_tit1"><?php echo $Mem_Old_Password?></td>
            <td class="ad_even" align="left">
              <input type="password" name="passwd_old" minlength="6" maxlength="15"  class="za_text">
            </td>
          </tr>
          <tr class="m_bc_ed">
            <td class="ad_tit1"><?php echo $Mem_New_Password?></td>
            <td class="ad_even" align="left">
              <input type="password" name="passwd" minlength="6" maxlength="15" class="za_text">
            </td>
          </tr>
          <tr class="m_bc_ed">
            <td class="ad_tit1"><?php echo $Mem_Cofirm_Password?></td>
            <td class="ad_even" align="left">
              <input type="password" name="REpasswd" minlength="6" maxlength="15" class="za_text">
            </td>
          </tr>
          <tr align="center"> 
            <td height="40" colspan="2" class="ad_even">◎<?php echo $Mem_Password_Guidelines?>：<?php echo $Mem_Pasread?></td>
          </tr>
          <tr> 
            <td colspan="2" class="ad_tit2">
              <input type="button" name="OK" value="确认提交" class="za_button" onclick="SubChk()">
              <input type=reset name="cancel" value="重新填写" class="za_button">
              <input type="hidden" name="action" value="1">
              <input type="hidden" name="uid" value="<?php echo $uid?>"> 
              <input type="hidden" name="type" value="1"> </td>
          </tr>
        </form>
    </table>

</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/jquery.md5.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript">
    function SubChk(){
        var $passwd_old = document.all.passwd_old;
        var passwd_old = $passwd_old.value;
        var $passwd = document.all.passwd;
        var pwd = $passwd.value; // 新密码
        var $REpasswd = document.all.REpasswd;
        var REpasswd = $REpasswd.value;

        if (passwd_old==''){
            $passwd_old.focus();
            alert("<?php echo $Mem_OldPasswordPleaseKeyin?>");
            return false;
        }
        if (pwd==''){
            $passwd.focus();
            alert("<?php echo $Mem_NewPasswordPleaseKeyin?>");
            return false;
        }
        if(pwd.length < 6 ){
            alert('<?php echo $Mem_NewPassword_6_Characters?>');
            return false;
        }
        if(pwd.length > 15 ){
            alert('<?php echo $Mem_NewPassword_12_CharactersMax?>');
            return false;
        }

        for (idx = 0; idx < pwd.length; idx++) {
            //====== 密碼只可使用字母(不分大小寫)與數字
            if(!((pwd.charAt(idx)>= "a" && pwd.charAt(idx) <= "z") || (pwd.charAt(idx)>= 'A' && pwd.charAt(idx) <= 'Z') || (pwd.charAt(idx)>= '0' && pwd.charAt(idx) <= '9'))){
                alert("<?php echo $Mem_PasswordEnglishNumber_6_Characters_12_CharactersMax?>");
                return false;
            }

        }

        if (REpasswd==''){
            $REpasswd.focus();
            alert("<?php echo $Mem_CofirmpasswordPleasekeyin?>");
            return false;
        }
        if(pwd != REpasswd){
            $REpasswd.focus();
            alert("<?php echo $Mem_PasswordConfirmError?>");
            return false;
        }
        var url = '/api/changePwdApi.php';
        var dataParams = {
            passwd_old:passwd_old,
            passwd:pwd,
            REpasswd:REpasswd
        };
        $.ajax({
            type: 'POST',
            url:url,
            data:dataParams,
            dataType:'json',
            success:function(res){
                if(res){
                    alert(res.describe);
                    if(res.status == '200'){ // 更改成功
                        top.location.href = '/';
                    }
                }

            },
            error:function(){
                alert('网络错误，请稍后重试');
            }
        });

    }
</script>
</body>
</html>