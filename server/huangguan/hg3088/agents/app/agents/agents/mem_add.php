<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../../agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$langx=$_SESSION["langx"];
$uid=$_SESSION['Oid'];
$lv=$_REQUEST["lv"];
$userlv=$_SESSION['admin_level'] ; // 当前管理员层级

require ("../../agents/include/traditional.$langx.inc.php");

$id=$_SESSION['ID'];

?>
    <html>
    <head>
        <title>main</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

    </head>
    <body >

    <dl class="main-nav"><dt><?php echo $Mem_Add.$Mem_Member ?></dt><dd></dd></dl>
    <div class="all main-ui">
        <FORM NAME="myFORM" METHOD=POST >

            <INPUT TYPE=HIDDEN NAME="id" VALUE="<?php echo $id?>">
            <INPUT TYPE=HIDDEN NAME="ratio" VALUE="">
            <INPUT TYPE=HIDDEN NAME="new_ratio" VALUE="">
            <input type=HIDDEN name="SS" value="63245520">
            <input type=HIDDEN name="SR" value="15115679280">
            <input type=HIDDEN name="TS" value="16280170738">
            <input type="hidden" name="s_low_order_gold" value="">
            <input type="hidden" name="s_low_order_gold_pc" value="">

            <table border="0" cellspacing="1" cellpadding="0" class="m_tab_ed">
                <tr class="m_title_edit">
                    <td colspan="2" ><h4><?php echo $Mem_Basic_data?><?php echo $Mem_Settings?></h4></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_mem_ed m_mem_ed_140">
                        <?php echo $Mem_Account?>:&nbsp;
                    </td>
                    <td align="left">
                        <input type="text" name="user_count" id="user_count" size="10" minlength="5" maxlength="15" class="za_text">
                        ◎<?php echo $Mem_Account_Rules?>：须为<font color="red"><b>5~15位英文字母或数字</b></font>且符合0~9及a~z字。
                        <input type=button name="chk" value="<?php echo $Mem_Check?><?php echo $Mem_Account?>" class="za_button" onclick='ChkMem();'>
                    </td>
                </tr>
                </td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_mem_ed"><?php echo $Mem_Password?>:</td>
                    <td align="left">
                        <input type="password" name="password" value="" minlength="6" maxlength="15" class="za_text">
                        ◎<?php echo $Mem_Password_Guidelines?>：<?php echo $Mem_Pasread?></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_mem_ed"><?php echo $Mem_Cofirm_Password?>:</td>
                    <td align="left">
                        <input type="password" name="passwd" value="" minlength="6" maxlength="15" class="za_text">
                    </td>
                </tr>
                <tr class="m_bc_ed zsxm">
                    <td class="m_mem_ed"><?php echo $Mem_Member_name?>:</td>
                    <td align="left">
                        <input type="text" name="alias" value="" size=10 maxlength="10" class="za_text">
                        <?php echo $Mem_Member_name_rule ;?>
                    </td>
                </tr>
            </table>
            <table  border="0" cellspacing="1" cellpadding="0" class="m_tab_ed">
                <tr class="m_title_edit">
                    <td colspan="2" ><h4><?php echo $Mem_Betting_data?><?php echo $Mem_Settings?></h4></td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_mem_ed m_mem_ed_140"><?php echo $Mem_Games_Available?>:</td>
                    <td align="left">
                        <select name="type" class="za_select" > <!-- onChange="show_count(0,this.value);" -->
                            <!--  <option label="A<?php /*echo $Mem_Line*/?>" value="A">A<?php /*echo $Mem_Line*/?></option>
       								<option label="B<?php /*echo $Mem_Line*/?>" value="B">B<?php /*echo $Mem_Line*/?></option>
       								<option label="C<?php /*echo $Mem_Line*/?>" value="C">C<?php /*echo $Mem_Line*/?></option>-->
                            <option label="<?php echo REG_OPEN_TYPE;?><?php echo $Mem_Line?>" value="<?php echo REG_OPEN_TYPE;?>" selected="selected"><?php echo REG_OPEN_TYPE;?><?php echo $Mem_Line?></option> <!-- 目前盘口默认为D -->
                        </select>
                    </td>
                </tr>
                <input type=hidden name="sp" value="0">
                <tr class="m_bc_ed">
                    <td class="m_mem_ed"><?php echo $Mem_Bet_Way?>:</td>
                    <td align="left">
                        <select name="pay_type" class="za_select" >
                            <!--<option label="<?php /*echo $Mem_Credit*/?>" value="0" selected="selected"><?php /*echo $Mem_Credit*/?></option>-->
                            <option label="<?php echo $Mem_Cash?>" value="1" selected="selected"><?php echo $Mem_Cash?></option>
                        </select>
                    </td>
                </tr>
               <!-- <tr class="m_bc_ed">
                    <td class="m_mem_ed"><?php /*echo $Mem_Currency_setup*/?>:</td>
                    <td align="left">
                        <select name="currency" class="za_select" onChange="Chg_Mcy('now');Chg_Mcy('mx')" >
                            <option label="<?php /*echo $Mem_radio_RMB*/?>-&gt;<?php /*echo $Mem_radio_RMB*/?>" value="RMB" selected="selected"><?php /*echo $Mem_radio_RMB*/?></option>
                        </select>
                        <?php /*echo $Mem_Today_Exchange*/?>:<font color="#FF0033" id="mcy_now">0</font>&nbsp;(<?php /*echo $Mem_Today_Exchange_Reference*/?>)
                    </td>
                </tr>-->

                <!--   <tr class="m_bc_ed">
                       <td class="m_mem_ed">性别:</td>
                       <td align="left">
                           <label><input id="rdomale" value="0" checked="" type="radio" name="Sex"> 男</label> &nbsp;
                           <label><input id="rdofemale" value="1" type="radio" name="Sex">女</label>
                       </td>
                   </tr>-->
                <tr class="m_bc_ed">
                    <td class="m_mem_ed">手机号码:</td>
                    <td align="left">
                        <input type="text" name="phone"  minlength="11" maxlength="11" class="za_text">
                    </td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_mem_ed">微信号码:</td>
                    <td align="left">
                        <input type="text" name="wechat"  minlength="5"  class="za_text">
                    </td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_mem_ed">取款密码:</td>
                    <td align="left">
                        <input type="password" name="pay_password"  minlength="6" maxlength="6" class="za_text">
                    </td>
                </tr>
                <tr class="m_bc_ed">
                    <td class="m_mem_ed">生日:</td>
                    <td align="left">
                        <input type="text" name="birthday"  class="za_text" placeholder="请选择出生日期" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" readonly>
                    </td>
                </tr>
            <!--    <tr class="m_bc_ed">
                    <td class="m_mem_ed">国家:</td>
                    <td align="left">
                        <select class="za_select" name="country">
                            <option  value="中国" selected="selected">中国</option>
                        </select>
                    </td>
                </tr>-->

                <!--<tr id='credit_0' class="m_bc_ed">
      <td class="m_mem_ed"><?php /*echo $Mem_Credit_Amount*/?>:</td>
      <td align="left">
      <input type="text" name="maxcredit" value="0" size=10 maxlength=15 class="za_text" onKeyUp="Chg_Mcy('mx');" onKeyPress="return CheckKey();">
      <?php /*echo $Mem_radio_RMB*/?>:<font color="#FF0033" id="mcy_mx">0</font></td>
    </tr>-->
                <tr id='credit_1' class="m_bc_ed" >
                    <td class="m_mem_ed"><?php echo $Mem_Cash?>:</td>
                    <td align="left">0 </td>
                </tr>

            </table>
            <table border="0" cellspacing="1" cellpadding="0" class="m_tab_ed">
                <tr >
                    <td class="m_mem_ed m_mem_ed_140"></td>
                    <td align="left">
                        <input type="button" name="OK2" value="<?php echo $Mem_Confirm?>" class="za_button sub_za_button" onclick="SubChk()">
                        <input type="button" name="CANCEL2" value="<?php echo $Mem_Cancle?>" id="CANCEL2" onClick="window.location.replace('user_browse.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&userlv=<?php echo $userlv?>&enable=Y&langx=<?php echo $langx?>');" class="za_button sub_za_button">
                    </td>
                </tr>
            </table>
        </form>
        <iframe name="getData" id="getData" src="" width="0" height="0"></iframe>
    </div>

<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>" ></script>

<SCRIPT>
var userlv = '<?php echo $userlv;?>';

function SubChk(){

    var $username = document.all.user_count;
    var username = $username.value;
    var $password = document.all.password;
    var pwd = $password.value;
    var $passwd = document.all.passwd;
    var passwd = $passwd.value;
    var $alias = document.all.alias;
    var alias = $alias.value;
    var $phone = document.all.phone;
    var phone = $phone.value;
    var $wechat = document.all.wechat;
    var wechat = $wechat.value;
    var $pay_password = document.all.pay_password;
    var pay_password = $pay_password.value;
    var $birthday = document.all.birthday;
    var birthday = $birthday.value;
    var type = document.all.type.value;
    var pay_type = document.all.pay_type.value;


    if(!isNum(username)){
        $username.focus();
        alert("<?php echo $Mem_Check_Username?>");
        return false;
    }
    if(username.length<5){
        $username.focus();
        alert("<?php echo $Mem_Input?> :<?php echo $Mem_Full_Account?>!!!");
        return false;
    }
    if(pwd==''){ // 密码
        $password.focus(); alert("<?php echo $Mem_Input?>:<?php echo $Mem_Password?>!!"); return false;
    }
    if(pwd.length < 6 ){ // 密码
        alert('<?php echo $Mem_NewPassword_6_Characters?>');return false;
    }
    if(pwd.length > 15 ){ // 密码
        alert('<?php echo $Mem_NewPassword_12_CharactersMax?>');return false;
    }
    if(pwd != passwd){
        $passwd.focus(); alert("<?php echo $Mem_PasswordConfirmError?>!!"); return false;
    }
    if(passwd==''){  // 确认密码
        $passwd.focus(); alert("<?php echo $Mem_Input?>:<?php echo $Mem_Cofirm_Password?>!!");
        return false;
    }
    if(alias=='' || !isChinese(alias)){  // 真实姓名
        $alias.focus(); alert("请输入真实姓名!!");
        return false;
    }
    if(phone=='' || !isMobel(phone)){  // 取款密码
        $phone.focus(); alert("请输入正确的手机号码!!");
        return false;
    }
    if(wechat=='' || !isWechat(wechat)){  // 微信
        $wechat.focus(); alert("请输入正确的微信号码!!");
        return false;
    }
    if(pay_password==''){  // 取款密码
        $pay_password.focus(); alert("请输入取款密码!!");
        return false;
    }

    if(birthday==''){  // 生日
        $birthday.focus(); alert("请选择出生日期!!");
        return false;
    }

    for (idx = 0; idx < pwd.length; idx++) {
        //====== 密碼只可使用字母(不分大小寫)與數字
        if(!((pwd.charAt(idx)>= "a" && pwd.charAt(idx) <= "z") || (pwd.charAt(idx)>= 'A' && pwd.charAt(idx) <= 'Z') || (pwd.charAt(idx)>= '0' && pwd.charAt(idx) <= '9'))){
            alert("<?php echo $Mem_PasswordEnglishNumber_6_Characters_12_CharactersMax?>");
            return false;
        }

    }

    //if(document.all.maxcredit.value=='') {
    //    document.all.maxcredit.focus(); alert("<?php //echo $Mem_Input?>// :<?php //echo $Mem_Credit?>// !!"); return false;
    //}
    if(!confirm("<?php echo $Mem_Whether_Write?><?php echo $Mem_Member?>?")){ // 取消提交
        document.all.OK2.disabled = false;
        document.all.CANCEL2.disabled = false;
        return false;
    }

    document.all.OK2.disabled = true;
    document.all.CANCEL2.disabled = true;
    //document.myFORM.submit();
    var url = '/api/AddMemberApi.php';
    var dataParams = {
        keys:'add',
        user_count:username,
        password:pwd,
        alias:alias,
        type:type,
        pay_type:pay_type,
        currency:'RMB',
        phone:phone,
        wechat:wechat,
        pay_password:pay_password,
        birthday:birthday
    };
    $.ajax({
        type: 'POST',
        url:url,
        data:dataParams,
        dataType:'json',
        success:function(res){
            if(res){
                document.all.OK2.disabled = false;
                document.all.CANCEL2.disabled = false;
                alert(res.describe);
                if(res.status == '200'){ // 新增会员成功
                    window.location.replace('user_browse.php?lv=MEM&userlv='+userlv) ;
                }
            }

        },
        error:function(){
            document.all.OK2.disabled = false;
            document.all.CANCEL2.disabled = false;
            alert('网络错误，请稍后重试');
        }
    });



}
function ChkMem(){
    var username=document.all.user_count.value;
    if(!isNum(username)){
        document.all.user_count.focus();
        alert("<?php echo $Mem_Check_Username?>");
        return false;
    }else if(username.length<5){
        document.all.user_count.focus();
        alert("<?php echo $Mem_Input?> :<?php echo $Mem_Full_Account?>!!!");
        return false;
    }else{
        document.getElementById('getData').src='check_id.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&lv=<?php echo $lv?>&username='+username;
    }
}

function CheckKey(){
    if(event.keyCode < 48 || event.keyCode > 57){alert("<?php echo $Mem_Enter_Numbers?>"); return false;}
}

function onload() {
    Chg_Mcy('now');
    Chg_Mcy('mx');
}

function show_count(w,s) {
    //alert(w+' - '+s);
    var org_str=document.all.user_count.value;//org_str.substr(1,5)
    if (s!=''){
        switch(w){
            case 0:
                switch(s){
                    case 'A':document.all.user_count.value = 'a'+org_str.substr(1,8);break;
                    case 'B':document.all.user_count.value = 'b'+org_str.substr(1,8);break;
                    case 'C':document.all.user_count.value = 'c'+org_str.substr(1,8);break;
                    case 'D':document.all.user_count.value = 'd'+org_str.substr(1,8);break;
                }
                break;
            case 1:document.all.user_count.value = org_str.substr(0,3)+s+org_str.substr(4,4);break;
            case 2:document.all.user_count.value = org_str.substr(0,4)+s+org_str.substr(5,3);break;
            case 3:document.all.user_count.value = org_str.substr(0,5)+s+org_str.substr(6,2);break;
            case 4:document.all.user_count.value = org_str.substr(0,6)+s+org_str.substr(7,1);break;

        }
    }
}

</SCRIPT>
</body>
</html>
