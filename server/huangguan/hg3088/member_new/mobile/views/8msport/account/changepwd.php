<?php
include_once('../../../include/config.inc.php');
$uid=$_SESSION['Oid'];
$username=$_SESSION['UserName'];
$Alias= $_SESSION['Alias'];
$cpUrl = $_SESSION['cpUrl'] ;

?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
        <!--<link href="../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
        <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

        <title class="web-title"></title>
        <style>

        </style>
    </head>
    <body >
        <div id="container" >

                <!-- 头部 -->
                <div class="header ">

                </div>
            <!-- 中间主体部分 -->
            <div class="content-center">

                <div class="nav_counter ">
                    <a class="current" href="javascript:void(0);" data-val="1">个人资料</a>
                    <a href="javascript:void(0);" data-val="2">登录密码修改</a>
                    <a href="javascript:void(0);" data-val="3">提款密码修改</a>
                </div>

                <!--  个人资料 -->
                <div class="tab_person_item user user_content_1" style="display: block;">
                    <ul>
                        <li>
                            <h3> <img src="../images/ucenter/name.png" alt=""/> 用户名</h3><h4 id="accountcode"><?php echo $username?></h4>
                        </li>
                        <li>
                            <h3> <img src="../images/ucenter/name.png" alt=""/> 姓名</h3><h4 style="color:red"><?php echo returnRealName($Alias)?></h4> <!-- 请至绑订银行卡做相关设定 -->
                        </li>
                       <!-- <li>
                            <h3>性别：</h3>
                            <h4>
                                <select id="change_gender" class="show_fix_button">
                                    <option value="">请选择</option>
                                    <option value="m">男</option>
                                    <option value="f">女</option>
                                </select> <a href="javascript:void(0)" class="change_fix" style="display:none" data-inputid="change_gender">修改</a>
                            </h4>
                        </li>-->
                        <li>
                            <h3> <img src="../images/ucenter/phone.png" alt=""/> 手机号</h3><h4><input type="text" pattern="\d*" class="show_fix_button" value="<?php echo yc_phone($_SESSION['Phone'])?>" readonly="true" id="check_phone">

                            </h4>
                        </li>
                       <!-- <li>
                            <h3>验证码：</h3><h4><input type="text" pattern="\d*" class="show_fix_button" id="check_number"> <a href="javascript:void(0)" class="check_number" style="display:none">确定</a> </h4>
                        </li>
                        <li>
                            <h3>邮箱：</h3><h4></h4>
                        </li>-->
                        <li>
                            <h3> <img src="../images/ucenter/wechat.png" alt=""/> 微信号</h3>
                            <h4>
                                <input type="text" class="show_fix_button" id="change_wechat" value="<?php echo $_SESSION['E_Mail']?>" readonly />
                                <a href="javascript:void(0)" class="change_fix" style="display:none" data-inputid="change_wechat">修改</a>
                            </h4>
                        </li>
                        <li>
                            <h3> <img src="../images/ucenter/day.png" alt=""/> 生日</h3>
                            <h4>
                                <input type="text" class="show_fix_button" id="change_birthday" value="<?php echo $_SESSION['birthday']?>" readonly />
                            </h4>
                        </li>
                    </ul>
                </div>

                <div class="change_pswd user hide user_content_2" >
                    <!-- 修改登录密码 -->
                    <div class="change-loginpsw" >
                     <!--   <div class="password-tip">
                            <p>修改登录密码</p>
                            <span>为了您的帐户安全,我们强烈建议您每30天修改一次密码。</span>
                        </div>-->
                        <form  name="chg_log_password" id="chg_log_password" >
                            <input type="hidden" name="action" value="1">
                            <input type="hidden" name="uid" value="<?php echo $uid?>">
                            <input type="hidden" name="flag_action" value="1"> <!-- 1 为修改登录密码，2 为修改支付密码 -->
                            <ul class="textbox-list">
                                <li>
                                    <h3>旧登录密码：</h3>
                                     <input type="password" name="oldpassword" id="oldpassword" minlength="6" maxlength="15" class="enter" placeholder="原密码" autocomplete="off" >
                                </li>
                                <li>
                                    <h3>新登录密码：</h3>
                                    <input type="password" name="password" id="password" minlength="6" maxlength="15" class="enter" placeholder="新密码（6-15个字符）" autocomplete="off" >
                                </li>
                                <li>
                                    <h3>密码确认：</h3>
                                    <input type="password" name="REpassword" id="REpassword" minlength="6" maxlength="15" class="enter" placeholder="确认密码（6-15个字符）" autocomplete="off" >
                                </li>


                            </ul>

                            <div class="changepsw-bottom">
                                <?php if($_SESSION['Agents'] != 'demoguest'){?>
                                    <input type="text" name="OK" class="zx_submit " value="确认更改" onclick="SubChk()" readonly />
                                <?php } ?>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="change_pswd user hide user_content_3">
                    <!-- 修改支付密码 -->
                    <div class="change-loginpsw">
                        <!-- <div class="password-tip">
                             <p>修改提款密码</p>
                             <span>为了您的资金安全,我们强烈建议您不定时修改密码。</span>
                         </div>-->
                        <form method="post" name="chg_pay_password" id="chg_pay_password" >
                            <input type="hidden" name="action" value="1">
                            <input type="hidden" name="uid" value="<?php echo $uid?>">
                            <input type="hidden" name="flag_action" value="2"> <!-- 1 为修改登录密码，2 为修改支付密码 -->
                            <ul class="textbox-list">
                                <li>
                                    <h3>旧提款密码：</h3>
                                    <input type="password" name="pay_oldpassword" id="pay_oldpassword" minlength="4" maxlength="6" class="inp-txt" placeholder="原密码" autocomplete="off" >
                                </li>
                                <li>
                                    <h3>新提款密码：</h3>
                                    <input type="password" name="pay_password" id="pay_password" minlength="6" maxlength="6" class="inp-txt" placeholder="新密码（6位纯数字）" autocomplete="off" >
                                </li>
                                <li>
                                    <h3>密码确认：</h3>
                                    <input type="password" name="pay_REpassword" id="pay_REpassword" minlength="6" maxlength="6" class="inp-txt" placeholder="确认密码（6位纯数字）" autocomplete="off" >
                                </li>

                            </ul>
                            <div class="changepsw-bottom">
                                <?php if($_SESSION['Agents'] != 'demoguest'){?>
                                    <input type="text" name="OK" class="sure_changepsw zx_submit " onclick="paySubChk()" value="确认更改" readonly />
                                <?php } ?>
                                <!--<input type="reset" name="cancel" class="cancle_changepsw" value="取消" onclick="goBackAction()" />-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 底部 -->
            <div id="footer">

            </div>

        </div>
        <script type="text/javascript" src="../../../js/zepto.min.js"></script>
        <script type="text/javascript" src="../../../js/animate.js"></script>
        <script type="text/javascript" src="../../../js/zepto.animate.alias.js"></script>
         <script type="text/javascript" src="../../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
        <script type="text/javascript" src="../../../js/usercenter.js?v=<?php echo AUTOVER; ?>"></script>
        <script type="text/javascript">
            var uid = '<?php echo $uid?>' ;
            var usermon = getCookieAction('member_money') ; // 获取信息cookie
            var cp_url = '<?php echo $cpUrl?>' ;
            setLoginHeaderAction('密码管理','','',usermon,uid) ;
            setFooterAction(uid);

            var top_tip = {
                str_input_oldpwd:'请输入原密码！',
                str_pwd_NoChg:'现密码与原密码不能相同！',
                str_input_pwd:'请输入新密码！',
                str_input_repwd:'请确认新密码！',
                str_pwd_limit:'请输入6-15位数字或者字母密码！',
                str_pay_pwd_limit:'请输入4-6位数字密码！',
                str_err_pwd:'密码与确认密码需要一致！'
            };

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
                    setPublicPop(top_tip.str_input_oldpwd);
                    $oldpwd.focus();
                    return false;
                }

                if (pwd == pass) {  // 现密码与原密码相同
                    setPublicPop(top_tip.str_pwd_NoChg);
                    return false;
                }
                if (pwd ==''){
                    $pwd.focus();
                    setPublicPop(top_tip.str_input_pwd);
                    return false;
                }
                if (repwd ==''){
                    $REpwd.focus();
                    setPublicPop(top_tip.str_input_repwd);
                    return false;
                }
                if (pwd.length < 6 || pwd.length > 15) {
                    setPublicPop(top_tip.str_pwd_limit);
                    return false;
                }
                if(pwd != repwd){
                    $pwd.focus();
                    setPublicPop(top_tip.str_err_pwd);
                    return false;
                }
                for (idx = 0; idx < pwd.length; idx++) {
                    //====== 密碼只可使用字母(不分大小寫)與數字
                    if(!((pwd.charAt(idx)>= "a" && pwd.charAt(idx) <= "z") || (pwd.charAt(idx)>= 'A' && pwd.charAt(idx) <= 'Z') || (pwd.charAt(idx)>= '0' && pwd.charAt(idx) <= '9'))){
                        setPublicPop(top_tip.str_pwd_limit);
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
                    setPublicPop(top_tip.str_pwd_limit);
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

               // return true;

                $.ajax({
                    url: '/account/changepwd_save.php',
                    type: 'POST',
                    dataType: 'json',
                    data: $('#chg_log_password').serialize() ,
                    success:function(res){
                        if(res.status=='200'){ // 修改密码成功
                            alertComing(res.describe) ;
                            loginOutSport(); // 退出帐号
                        }else{ // 失败
                            setPublicPop(res.describe);
                        }
                    },
                    error:function () {
                        setPublicPop(config.errormsg);
                    }
                });


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
                    setPublicPop(top_tip.str_input_oldpwd);
                    $oldpwd.focus();
                    return false;
                }

                if (pwd == pay_pass) {  // 现密码原原密码相同
                    setPublicPop(top_tip.str_pwd_NoChg);
                    return false;
                }
                if ($pwd.value==''){
                    $pwd.focus();
                    setPublicPop(top_tip.str_input_pwd);
                    return false;
                }
                if (repwd ==''){
                    $REpwd.focus();
                    setPublicPop(top_tip.str_input_repwd);
                    return false;
                }
                if (pwd.length < 4 || pwd.length > 6) {
                    setPublicPop(top_tip.str_pay_pwd_limit);
                    return false;
                }
                if(pwd != repwd){
                    $pwd.focus();
                    setPublicPop(top_tip.str_err_pwd);
                    return false;
                }
                for (idx = 0; idx < pwd.length; idx++) {
                    // 密碼只可使用数字
                    if(!(pwd.charAt(idx)>= '0' && pwd.charAt(idx) <= '9')){
                        setPublicPop(top_tip.str_pay_pwd_limit);
                        return false;
                    }

                    if ((pwd.charAt(idx)>= "0" && pwd.charAt(idx) <= "9")){
                        Numflag++;
                    }
                }
                //====== 密碼需使用数字
                var msg = "";
                if (Numflag == 0 ) {
                    setPublicPop(top_tip.str_pay_pwd_limit);
                    return false;
                }

                // document.getElementById('chg_pay_password').removeAttribute('onsubmit').submit() ;
               // return true;

                $.ajax({
                    url: '/account/changepwd_save.php',
                    type: 'POST',
                    dataType: 'json',
                    data: $('#chg_pay_password').serialize() ,
                    success:function(res){
                        if(res.status=='200'){ // 修改密码成功
                            alertComing(res.describe) ;
                           window.location.href ='/<?php echo TPL_NAME;?>account.php' ;
                        }else{ // 失败
                            setPublicPop(res.describe);
                        }
                    },
                    error:function () {
                        setPublicPop(config.errormsg);
                    }
                });

            }
            
            // 标题切换
            function tabChange() {
                $('.nav_counter ').on('click','a',function () {
                    var val = $(this).data('val') ;
                    $(this).addClass('current').siblings().removeClass('current') ;
                    $('.user').hide();
                    $('.user_content_'+val).show();
                });
            }
            tabChange();
        </script>

    </body>
</html>