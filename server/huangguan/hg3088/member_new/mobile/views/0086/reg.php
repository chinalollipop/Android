<?php
session_start();
include_once('../../include/config.inc.php');

if(isset($_SESSION['Oid']) || $_SESSION['Oid'] != "" ) { // 如果已登录，返回到首页
    echo "<script>window.location.href='/';</script>";
    exit;
}

// 会员注册控制必填字段-20200114
$redisObj = new Ciredis();
$registerConf = $redisObj->getSimpleOne('member_register_set');
$registerSet = json_decode($registerConf, true);

?>

<html class="zh-cn"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link href="../../style/tncode/style.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" />
    <!--<link rel="stylesheet" href="style/mobiscroll.css?v=<?php echo AUTOVER; ?>">-->
<!--    <link rel="stylesheet" href="../../style/icalendar.css?v=--><?php //echo AUTOVER; ?><!--">-->
    <title class="web-title"></title>
<style type="text/css">
    .content-center{bottom: 0;}
</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间注册表单内容 -->
    <div class="content-center">
        <div class="member_reg" >
        <input type="hidden" name="keys" value="add">
        <div class="textbox-list">
            <ul>
                <li>
                    <div>
                        <label>
                            <span class="account-icon">介绍人</span>
                        </label>
                        <span class="textbox">
                            <!--<input type="text" name="introducer" id="introducer" value="<?php /*echo $_SESSION['agents_name']*/?>" <?php /*if($_SESSION['agents_name']){echo 'readonly';} */?> minlength="4" maxlength="15" class="inp-txt" placeholder="没有可不填写">-->
                            <input type="text" name="introducer" id="introducer" value="" minlength="4" maxlength="15" class="inp-txt" placeholder="没有可不填写">

                        </span>
                    </div>
                </li>
                <li>
                    <div>
                        <label>
                            <span class="account-icon"><em class="red_color">*</em> 帐号</span>
                        </label>
                        <span class="textbox">
                            <input type="text" name="username" id="username" minlength="5" maxlength="15" class="inp-txt" placeholder="会员帐号（5-15位数字或字母）">
                        </span>
                    </div>
                </li>
                <li>
                    <div>
                        <label>
                            <span class="pwd-icon"><em class="red_color">*</em> 密码</span>
                        </label>
                        <span class="textbox">
                            <input type="password" name="password" id="password"  minlength="6" maxlength="15" class="inp-txt" placeholder="密码（6-15个字符）">
                        </span>
                    </div>
                </li>
                <li>
                    <div>
                        <label>
                            <span class="pwd-icon2"><em class="red_color">*</em> 确认密码</span>
                        </label>
                        <span class="textbox">
                              <input type="password" name="password2" id="password2" minlength="6" maxlength="15" class="inp-txt" placeholder="确认密码（6-15个字符）">
                        </span>
                    </div>
                </li>
                <?php if(empty($registerSet) || $registerSet['telOn'] == 1) { ?>
                    <li>
                        <div>
                            <label>
                                <span class="text"><em class="red_color">*</em> 手机号码</span>
                            </label>
                            <span class="textbox">
                                <input type="text" name="phone" id="phone" minlength="11" maxlength="11" class="inp-txt" placeholder="请输入11位手机号码">
                            </span>
                        </div>
                    </li>
                <?php } if($registerSet['chatOn'] == 1) { ?>
                    <li>
                        <div>
                            <label>
                                <span class="text"><em class="red_color">*</em> 微信号码</span>
                            </label>
                            <span class="textbox">
                            <input type="text" name="wechat" id="wechat" class="inp-txt" placeholder="请输入微信号码">
                        </span>
                        </div>
                    </li>
                <?php } if($registerSet['qqOn'] == 1) { ?>
                    <li>
                        <div>
                            <label>
                                <span class="text"><em class="red_color">*</em> QQ号码</span>
                            </label>
                            <span class="textbox">
                            <input type="text" name="qq" id="qq" class="inp-txt" placeholder="请输入QQ号码">
                        </span>
                        </div>
                    </li>
                <?php } ?>
             <!--   <li>
                    <div>
                        <label>
                            <span class="pwd-icon2"><em class="red_color">*</em> 验证码</span>
                        </label>
                        <span class="textbox">
                            <input id="verifycode" class="inp-txt" name="verifycode" type="text" tabindex="2"  minlength="4" maxlength="4" placeholder="请输入验证码" >
                            <img title="点击刷新" class="yzm_code" style="position: absolute;right: 4.3%;"  src="/include/validatecode/captcha.php" align="absbottom" onclick="this.src='/include/validatecode/captcha.php?'+Math.random();"/>
                        </span>
                    </div>
                </li>-->
                <li class="site-origin">
                    <div>
                        <label>
                            <span class="text">如何得知本站</span>
                        </label>
                        <span class="textbox">
                                    <select name="know_site" id="know_site">
                                        <option value="3" selected>网络广告</option>
                                        <option value="2">比分网</option>
                                        <option value="1">朋友推荐</option>
                                        <option value="4">论坛</option>
                                    </select>
                                </span>
                    </div>
                </li>
            </ul>
        </div>
<!--        <div class="textbox-list user-info">-->
<!--            <ul>-->
<!--                <li>-->
<!--                    <div>-->
<!--                        <label>-->
<!--                            <span class="text"><em class="red_color">*</em> 真实姓名</span>-->
<!--                        </label>-->
<!--                        <span class="textbox">-->
<!--                            <input type="text" name="alias" id="alias"  maxlength="10" class="inp-txt" placeholder="提款行卡的姓名，用于提款">-->
<!--                        </span>-->
<!--                    </div>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <div>-->
<!--                        <label>-->
<!--                            <span class="text"><em class="red_color">*</em> 提款密码</span>-->
<!--                        </label>-->
<!--                        <span class="textbox">-->
<!--                            <input type="password" name="paypassword" id="paypassword"  minlength="6" maxlength="6" class="inp-txt" placeholder="请输入6位纯数字">-->
<!--                        </span>-->
<!--                    </div>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <div>-->
<!--                        <label>-->
<!--                            <span class="text"><em class="red_color">*</em> 手机号码</span>-->
<!--                        </label>-->
<!--                        <span class="textbox">-->
<!--                              <input type="text" name="phone" id="phone"  minlength="11" maxlength="11" class="inp-txt" placeholder="请输入11位手机号码">-->
<!--                        </span>-->
                       <!-- <span class="textbox">
                                    <select id="question" name="question">
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
                                </span>-->
<!--                    </div>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <div>-->
<!--                        <label>-->
<!--                            <span class="text"><em class="red_color">*</em> 微信号码</span>-->
<!--                        </label>-->
<!--                        <span class="textbox">-->
<!--                            <!--<input id="answer" maxlength="12" size="30" type="text" name="answer" placeholder="可取回提款密码的答案，须谨慎填写!">-->
<!--                            <input id="wechat"  type="text" name="wechat" placeholder="微信号码">-->
<!--                        </span>-->
<!--                    </div>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <div>-->
<!--                        <label>-->
<!--                            <span class="text"><em class="red_color">*</em> 出生日期</span>-->
<!--                        </label>-->
<!--                        <span class="textbox">-->
<!--                            <input id="birthday" maxlength="12"  type="text" name="birthday" placeholder="请填写出生年月日" readonly /> <!-- onclick="chooseDate()" -->
<!--                        </span>-->
<!--                    </div>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <div>-->
<!--                        <label>-->
<!--                            <span class="text"><em class="red_color">*</em> 居住城市</span>-->
<!--                        </label>-->
<!--                        <span class="textbox">-->
<!--                                    <input value="中国" id="country" class="txt4_3" maxlength="10" name="country" readonly="">-->
<!--                                    <!--<input value="上海" id="city" class="txt4_3" name="city" type="hidden" maxlength="10">-->
<!--                                </span>-->
<!--                    </div>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </div>-->
        <div class="agree-div">
            <span class="checkbox-item checked">
                <span class="icon"></span>
                <span class="text">同意本站《协议条款》</span>
            </span>
        </div>
        <!-- 错误提示 -->
        <div class="error-msg">
            <span class="text" id="error_msg"></span>
        </div>
        <div class="btn-wrap">
            <a href="javascript:reqSubmit();" class="zx_submit before_yz">立即注册</a>

        </div>
    </div>
    </div>


</div>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
<script type="text/javascript" src="../../style/tncode/tn_code.js?v=<?php echo AUTOVER; ?>" ></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/validate.js?v=<?php echo AUTOVER; ?>"></script>
<!--<script type="text/javascript" src="../../js/mobiscroll.js?v=<?php echo AUTOVER; ?>"></script>-->
<!--<script type="text/javascript" src="../../js/icalendar.min.js"></script>-->
<script type="text/javascript">
    LOGIN_IS_VERIFY_CODE=<?php echo LOGIN_IS_VERIFY_CODE ? 1:0;?>;
    if(LOGIN_IS_VERIFY_CODE) {
        // 初始化验证码
        var $TNCODE = tncode;
        $TNCODE.init();
    }

    $('#verifycode').focus(function () { // 更新验证码
        $('.yzm_code').attr('src','/include/validatecode/captcha.php?v='+Math.random());
    })

    getAgentUrl() ;
    // 获取代理推广码
    function getAgentUrl() {
        var agent_name = localStorage.getItem('agent_account') ;
        var ck_agent_name = getCookieAction('agent_account') ;
        if(agent_name){
            $('#introducer').val(agent_name).attr('readonly',true) ;
        }
        if(ck_agent_name){
            $('#introducer').val(ck_agent_name).attr('readonly',true) ;
        }
    }

    var mem_flage = false ; // 防止重复提交
    function reqSubmit() {
        if(!VerifyData()){ // 没有通过前端验证
            return false ;
        }

        if(LOGIN_IS_VERIFY_CODE) {
            // 验证通过

            $TNCODE.show() ;
            $TNCODE.onsuccess(function () {
                if (mem_flage) {
                    return false;
                }
                mem_flage = true;
                var introducer = removeAllSpace($("input[name='introducer']").val());
                var keys = removeAllSpace($("input[name='keys']").val());
                var username = removeAllSpace($("input[name='username']").val());
                var password = removeAllSpace($("input[name='password']").val());
                var password2 = removeAllSpace($("input[name='password2']").val());
                // var alias = removeAllSpace($("input[name='alias']").val());
                // var paypassword = removeAllSpace($("input[name='paypassword']").val());
                // var question  = main.question.value ;
                // var answer = $("input[name='answer']").val();
                var phone = $("input[name='phone']").val();
                var wechat = $("input[name='wechat']").val();
                var qq = $("input[name='qq']").val();
                if(phone != undefined){
                    phone = removeAllSpace(phone);
                }
                if(wechat != undefined){
                    wechat = removeAllSpace(wechat);
                }
                if(qq != undefined){
                    qq = removeAllSpace(qq);
                }
                //  var birthday = removeAllSpace($("input[name='birthday']").val());
                //  var country = removeAllSpace($("input[name='country']").val());
                var know_site = $('#know_site').val();
                // var verifycode = $('#verifycode').val() ;

                var senddata = {
                    introducer: introducer,
                    keys: keys,
                    username: username,
                    password: password,
                    password2: password2,
                    // alias:alias,
                    // paypassword:paypassword,
                    phone: phone,
                    wechat: wechat,
                    qq: qq,
                    // birthday:birthday,
                    // country:country,
                    know_site: know_site,
                    verifycode: Math.random()
                }

                /**  ret.err
                 *  -1 您输入的推荐代理 $agent 不存在
                 *  -2 帐户已经有人使用，请重新注册！
                 *  -3 插入新账户信息 数据库操作失败!!!
                 *  -4 更新代理信息操作失败
                 *  0  注册成功
                 */
                $.ajax({
                    url: '/mem_reg_add.php',
                    type: 'POST',
                    dataType: 'json',
                    data: senddata,
                    success: function (ret) {
                        // setPublicPop(ret.describe);
                        // 注册成功后自动登录
                        // {"status":"200","describe":"用户登录成功","timestamp":"20180910000837",
                        // "data":{"UserName":"jack005","Agents":"dzfjazajzyj","LoginTime":"2018-09-10 00:08:37","birthday":"1989-09-10","Money":"0.0000","Phone":"13899989879","test_flag":"0","Oid":"e4e623ec55b59c82d723ra2","Alias":"发发发","BindCard_Flag":"0","BetMinMoney":"20","BetMaxMoney":"5000000"},"sign":""}

                        if (ret.status == '200') { // 注册成功
                            mem_flage = false;
                            setCookieAction('member_money', ret.data.Money, 1); // 用户金额，cookie 有效期 1天
                            loginLotteryAction();
                        } else if (ret.status == '300.1') { // 代理商独立域名跳转
                            window.location.href = ret.data.agentchangeurl;
                        } else {

                            $TNCODE.init();
                            mem_flage = false;
                            setPublicPop(ret.describe);
                        }
                    },
                    error: function (msg) {
                        mem_flage = false;
                        setPublicPop('注册账号异常');
                    }
                });
            });
        }else{
            if (mem_flage) {
                return false;
            }
            mem_flage = true;
            var introducer = removeAllSpace($("input[name='introducer']").val());
            var keys = removeAllSpace($("input[name='keys']").val());
            var username = removeAllSpace($("input[name='username']").val());
            var password = removeAllSpace($("input[name='password']").val());
            var password2 = removeAllSpace($("input[name='password2']").val());
            // var alias = removeAllSpace($("input[name='alias']").val());
            // var paypassword = removeAllSpace($("input[name='paypassword']").val());
            // var question  = main.question.value ;
            // var answer = $("input[name='answer']").val();
            var phone = $("input[name='phone']").val();
            var wechat = $("input[name='wechat']").val();
            var qq = $("input[name='qq']").val();
            if(phone != undefined){
                phone = removeAllSpace(phone);
            }
            if(wechat != undefined){
                wechat = removeAllSpace(wechat);
            }
            if(qq != undefined){
                qq = removeAllSpace(qq);
            }
            //  var birthday = removeAllSpace($("input[name='birthday']").val());
            //  var country = removeAllSpace($("input[name='country']").val());
            var know_site = $('#know_site').val();
            // var verifycode = $('#verifycode').val() ;

            var senddata = {
                introducer: introducer,
                keys: keys,
                username: username,
                password: password,
                password2: password2,
                // alias:alias,
                // paypassword:paypassword,
                phone: phone,
                wechat: wechat,
                qq: qq,
                // birthday:birthday,
                // country:country,
                know_site: know_site,
                verifycode: Math.random()
            }

            /**  ret.err
             *  -1 您输入的推荐代理 $agent 不存在
             *  -2 帐户已经有人使用，请重新注册！
             *  -3 插入新账户信息 数据库操作失败!!!
             *  -4 更新代理信息操作失败
             *  0  注册成功
             */
            $.ajax({
                url: '/mem_reg_add.php',
                type: 'POST',
                dataType: 'json',
                data: senddata,
                success: function (ret) {
                    // setPublicPop(ret.describe);
                    // 注册成功后自动登录
                    // {"status":"200","describe":"用户登录成功","timestamp":"20180910000837",
                    // "data":{"UserName":"jack005","Agents":"dzfjazajzyj","LoginTime":"2018-09-10 00:08:37","birthday":"1989-09-10","Money":"0.0000","Phone":"13899989879","test_flag":"0","Oid":"e4e623ec55b59c82d723ra2","Alias":"发发发","BindCard_Flag":"0","BetMinMoney":"20","BetMaxMoney":"5000000"},"sign":""}

                    if (ret.status == '200') { // 注册成功
                        mem_flage = false;
                        setCookieAction('member_money', ret.data.Money, 1); // 用户金额，cookie 有效期 1天
                        loginLotteryAction();
                    } else if (ret.status == '300.1') { // 代理商独立域名跳转
                        window.location.href = ret.data.agentchangeurl;
                    } else {

                        mem_flage = false;
                        setPublicPop(ret.describe);
                    }
                },
                error: function (msg) {
                    mem_flage = false;
                    setPublicPop('注册账号异常');
                }
            });
        }

    }


    // var calendar = new lCalendar();   // 时间插件初始化
    // calendar.init({
    //     'trigger': '#birthday',
       // 'type': 'datetime', // 显示时分秒等
       //  'type': 'date', // 只显示年月日
    // });
    // 时间插件配置
    // function chooseDate() {
    //     $.mobiscroll.setDefaults({   //日期控件
    //         theme: 'ios', //皮肤样式 android
    //         lang: 'zh',
    //         dateFormat: 'yy/mm/dd',  // 日期格式
    //         mode: 'scroller', //日期选择模式 mixed
    //         display: 'bottom',
    //         startYear: 2017, //开始年份
    //         endYear:2020 //结束年份
    //     });
    //     $("#birthday").mobiscroll().date({ });
    //
    // }

    setLoginHeaderAction('注册','login') ;
    addServerUrl() ;
    agreeMentAction() ;


</script>

</body>
</html>