<?php
session_start();
header ("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

require ("app/member/include/config.inc.php");

// $introducer = $_REQUEST['intr'] ; // 推广码
$introducer =strtolower(json_encode($_GET)) ; // 推广码转小写
$introducer =json_decode($introducer) ;
$introducer = $introducer ->intr ;

// 系统维护
ifSysMaintain();

// 会员注册控制必填字段-20200114
$redisObj = new Ciredis();
$registerConf = $redisObj->getSimpleOne('member_register_set');
$registerSet = json_decode($registerConf, true);

$m_date=date('Y-m-d',time()+12*60*60);
$end_date='2021-07-15'; // 欧洲杯期间显示

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>用户注册</title>
    <link rel="shortcut icon" href="images/favicon_<?php echo TPL_FILE_NAME;?>.ico" type="image/x-icon">
    <link href="style/Reg.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
    <script src="style/tncode/tn_code.js?v=<?php echo AUTOVER; ?>" type="text/javascript" ></script>
    <link href="style/tncode/style.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css"  />
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
            <h1 align="left">
                注册帐号</h1>
                <!--<div>完成注册开户后，该帐号将作为您的交易帐号，请牢记并妥善保管您的用户名和密码，防止帐户信息泄露或被盗！ </div>-->
            <table width="880" class="lineJL" border="0" cellspacing="0" cellpadding="0">
                <tbody>
			<form action="/app/member/mem_reg_add.php?keys=add" method="post" name="main" id="main" onsubmit="return VerifyData('reg');"  target="">
                <tr>
                    <td align="right" class="style1" valign="top">
                        <span class="red"></span>介绍人：
                    </td>
                    <td class="style3" valign="top">
                        <input name="introducer" type="text" id="introducer" value="<?php echo $introducer ?>" minlength="4" maxlength="15"><span class="Reginput" ></span>
                    </td>
                    <td align="left" class="sty04" valign="top">
                        <span class="red">*</span>没有可不填写<br>
                    </td>
                </tr>
				<tr>
                    <td align="right" class="style1" valign="top">
                        <span class="red">*</span>会员账号：
                    </td>
                    <td class="style3" valign="top">
                        <input name="username" type="text" id="username" minlength="5" maxlength="15"><span class="Reginput" id="span_CheckUsername"></span>
                    </td>
                    <td align="left" class="sty04" valign="top">
                        <span class="red">*</span>帐号规则：帐号必须为5-15位数字和字母组合<br>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="style1" valign="top">
                        <span class="red">*</span>会员密码：
                    </td>
                    <td class="style3" valign="top">
                        <input name="password" type="password" class="input3" id="password" minlength="6" maxlength="15"><span class="Reginput" id="CheckRePassWord"></span>
                    </td>
                    <td align="left" class="sty03 sty04" valign="top">
                        <span class="red">*</span>密码规则：密码长度要有6-15个字符，以及必须含有数字和字母组合！
                    </td>
                </tr>
                <tr>
                    <td align="right" class="style1" valign="top">
                        <span class="red">*</span>密码确认：
                    </td>
                    <td class="style3" valign="top">
                        <input name="password2" type="password" class="input3" id="password2" minlength="6" maxlength="15"><span class="Reginput" id="ReCheckRePassWord"></span>
                    </td>
                    <td align="left" class="sty03 sty04" valign="top">
                        <span class="red">*</span>密码规则：同上
                    </td>
                    <td align="left">
                    </td>
                </tr>
<!--                <tr>-->
<!--                    <td align="right" class="style1" valign="top">-->
<!--                        首选货币：-->
<!--                    </td>-->
<!--                    <td class="style3" valign="top">-->
<!--                        <input name="hb" type="text" value="RMB" readonly ><span class="Reginput" ></span>-->
<!--                    </td>-->
<!--                    <td align="left" class="sty04" valign="top">-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td align="right" class="style1" valign="top">-->
<!--                        <span class="red">*</span>真实姓名：-->
<!--                    </td>-->
<!--                    <td class="style3" valign="top">-->
<!--                  <input name="alias" type="text"  id="alias" maxlength="10"><span class="Reginput" id="span1"></span>-->
<!--                    </td>-->
<!--                    <td align="left" class="sty04" valign="top">-->
<!--                        <span class="red">*</span>姓名必须与你用于提款的银行户口名字一致，否则无法提款！<br>-->
<!--                    </td>-->
<!--                </tr>-->
                <?php if(empty($registerSet) || $registerSet['telOn'] == 1) { ?>
                <tr>
                    <td align="right" class="style1" valign="top">
                        <span class="red">*</span>手机号码：
                    </td>
                    <td class="style3" valign="top">
                        <input name="phone" type="text"  id="phone" minlength="11" maxlength="11"><span class="Reginput"></span>
                    </td>
                   <!-- <td class="style3" valign="top">
                        <select name="question" id="question" >
                            <option value="">请选择</option>
                            <option value="您的车牌号码">您的车牌号码</option>
                            <option value="您所在的城市">您所在的城市</option>
                            <option value="您的生日">您的生日</option>
                            <option value="您的名字">您的名字</option>
                            <option value="您父亲的名字">您父亲的名字</option>
                            <option value="您母亲的名字">您母亲的名字</option>
                            <option value="您儿女的名字">您儿女的名字</option>
                            <option value="您妻子的名字">您妻子的名字</option>
                            <option value="您喜欢的数字">您喜欢的数字</option>
                            <option value="您喜欢的品牌">您喜欢的品牌</option>
                            <option value="您喜欢的运动">您喜欢的运动</option>
                            <option value="您喜欢的颜色">您喜欢的颜色</option>
                            <option value="您喜欢的球队">您喜欢的球队</option>
                            <option value="您喜欢的球星">您喜欢的球星</option>
                        </select>
                        <span class="Reginput"></span>
                    </td>-->
<!--                    <td align="left" class="sty04" valign="top">-->
<!--                        <br>-->
<!--                    </td>-->
                </tr>
                <?php } if($registerSet['chatOn'] == 1) { ?>
                <tr>
                    <td align="right" class="style1" valign="top">
                        <span class="red">*</span>微信号码：
                    </td>
                    <td class="style3" valign="top">
<!--                        <input name="answer" type="text" id="answer"><span class="Reginput"></span>-->
                        <input name="wechat" type="text" id="wechat"><span class="Reginput"></span>
                    </td>
                    <td align="left" class="sty04" valign="top">

                    </td>
                </tr>
                <?php } if($registerSet['qqOn'] == 1) { ?>
                <tr>
                    <td align="right" class="style1" valign="top">
                        <span class="red">*</span>QQ号码：
                    </td>
                    <td class="style3" valign="top">
                        <input name="qq" type="text" id="qq"><span class="Reginput"></span>
                    </td>
                </tr>
                <?php } if($registerSet['aliasOn'] == 1) { ?>
                    <tr>
                        <td align="right" class="style1" valign="top">
                            <span class="red">*</span>真实姓名：
                        </td>
                        <td class="style3" valign="top">
                            <input name="alias" type="text" id="alias"><span class="Reginput"></span>
                        </td>
                    </tr>
                <?php } ?>
                <!--<tr>
                    <td align="right" class="style1" valign="top">
                        性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：
                    </td>
                    <td class="style3" valign="top">
                        <label>
                            <input type="radio" name="radio" value="0" checked="" class="radio-box"> 男</label> &nbsp;
                        <label><input type="radio" name="radio" value="1" class="radio-box"> 女</label>
                        <span class="Reginput"></span>
                    </td>

                    <td align="left" class="sty04" valign="top">

                    </td>
                </tr>-->
<!--                <tr>-->
<!--                    <td align="right" class="style1" valign="top">-->
<!--                        <span class="red">*</span>取款密码：-->
<!--                    </td>-->
<!--                    <td class="style3" valign="top">-->
<!--                        <input name="paypassword" type="password"  id="paypassword" minlength="4" maxlength="6"><span class="Reginput" id="span2"></span>-->
<!--                    </td>-->
<!--                    <td align="left" class="sty04" valign="top">-->
<!--                        <span class="red">*</span>提款认证必须，请务必记住！<br>-->
<!--                    </td>-->
<!--                </tr>-->
<!--                <tr>-->
<!--                    <td align="right" class="style1" valign="top">-->
<!--                        <span class="red">*</span>生&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日：-->
<!--                    </td>-->
<!--                    <td class="style3" valign="top">-->
<!--                        <input name="birthday" id="birthday" type="text" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" readonly><span class="Reginput" ></span>-->
<!--                    </td>-->
<!--                    <td align="left" class="sty04" valign="top">-->
<!--                        <span class="red">*</span>用于取回密码的答案，需谨记！<br>-->
<!--                    </td>-->
<!--                </tr>-->
               <!-- <tr>
                    <td align="right" class="style1" valign="top">
                        国&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;家：
                    </td>
                    <td class="style3" valign="top">
                        <input name="country" type="text" value="中国"><span class="Reginput" ></span>
                    </td>
                    <td align="left" class="sty04" valign="top">
                        <br>
                    </td>
                </tr>-->
               <!-- <tr>
                    <td align="right" class="style1" valign="top">
                        城&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;市：
                    </td>
                    <td class="style3" valign="top">
                        <input name="city" type="text" value="上海"><span class="Reginput" ></span>
                    </td>
                    <td align="left" class="sty04" valign="top">
                        <br>
                    </td>
                </tr>-->
                <!--<tr>
                    <td align="right" class="style1" valign="top">
                        <span class="red">*</span>验证码：
                    </td>
                    <td class="style3" valign="top">
                        <input id="verifycode" name="verifycode" type="text" tabindex="2" style="width:100px; height:30px" minlength="4" maxlength="4" >
                        <img title="点击刷新" class="yzm_code" border='1' src="app/member/include/validatecode/captcha.php" align="absbottom" onclick="this.src='app/member/include/validatecode/captcha.php?'+Math.random();"/>
                    </td>
                </tr>-->
                <input id="verifycode" name="verifycode" type="hidden" tabindex="2" style="width:100px; height:30px" >
                <tr>
                    <td align="right" class="style1" valign="top">
                        如何得知本站：
                    </td>
                    <td  colspan="2" class="style3" valign="top">
                        <label><input type="radio" name="know_site" value="3" class="radio-box" checked=""> 网络广告</label>&nbsp;
                        <label><input type="radio" name="know_site" value="2" class="radio-box"> 比分网</label>&nbsp;
                        <label><input type="radio" name="know_site" value="1" class="radio-box"> 朋友推荐</label>&nbsp;
                        <label><input type="radio" name="know_site" value="4" class="radio-box"> 论坛</label>
                        <label><input type="radio" name="know_site" value="5" class="radio-box"> 试玩参观</label>
                        <span class="Reginput" ></span>
                    </td>

                </tr>
                <?php
                if($end_date>=$m_date && TPL_FILE_NAME=='0086'){
                    echo '<tr>
                            <td></td>
                            <td class="style1 " valign="top" >
                                <span class="red">欧洲杯期间新会员存款1000送186，只限投注体育竞技</span>
                                <span class="Reginput"></span>
                            </td>
                            </tr>';
                }
                ?>
                <tr>
                    <td align="right" class="style1" valign="top">

                    </td>
                    <td class="style3" valign="top">
                        <span class="red">*</span><input name="checkbox" type="checkbox" class="checkbox" id="checkbox" checked="">
                        <strong>我已满18周岁，同意以上条约。</strong>
                        <span class="Reginput" ></span>
                    </td>

                </tr>
                <!--<tr>
                    <td align="right" class="style1" valign="top">
                        联络电话(手机)：
                    </td>
                    <td class="style3" valign="top">
                        <input name="phone" type="text" class="input3" value=""  id="phone" maxlength="11"><span class="Reginput" id="span3"></span>
                    </td>
                    <td align="left" class="sty04" valign="top">
                        您本人的电话或手机，以便能通过电话或手机与您取得联系！<br>
                    </td>
                </tr>-->
				<!--<tr>
                    <td align="right" class="style1" valign="top">
                       电子邮件：
                    </td>
                    <td class="style3" valign="top">
                        <input id="e_mail" onfocusout="isEmail(this)" maxLength="25" name="e_mail" class="inpt"><span class="Reginput" id="span3"></span>
                    </td>
                    <td align="left" class="sty04" valign="top">
                        要求真实,以便接收账户通知等资讯,例如:1234567@qq.com<br>
                    </td>
                </tr>-->

                <tr>
                    <td class="style2">
                    </td>
                    <td class="style4">
                        <input class="tncode" type="button" value="确认提交">
                        <input type="reset" value="重新输入" >
                    </td>

                </tr>
			</form>
            </tbody></table>

 <script type="text/javascript" src="js/jquery.js"></script>
 <script type="text/javascript" src="js/register/validate.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    LOGIN_IS_VERIFY_CODE=<?php echo LOGIN_IS_VERIFY_CODE ? 1:0;?>;
    if(LOGIN_IS_VERIFY_CODE==1){
        var $TN = tncode;
        var _old_onload = window.onload;
        $TN.onsuccess(function(){
            //验证通过
            $('input[name="verifycode"]').val(Math.random());
            $("#main").submit();
        });
    }else{
        $('.tncode').click(function(){
            $('input[name="verifycode"]').val(Math.random());
            $("#main").submit();
        });
    }

    window.onload = function (){
        if(typeof _old_onload == 'function'){
            _old_onload();
        }
        if(LOGIN_IS_VERIFY_CODE==1){
            tncode.init();
        }
    };

//    $('#verifycode').focus(function () { // 更新验证码
//        $('.yzm_code').attr('src','app/member/include/validatecode/captcha.php?v='+Math.random());
//    })

</script>

</body>
</html>
