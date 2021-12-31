<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
$intr= isset($_REQUEST['intr'])?$_REQUEST['intr']:'';  // 介绍人

if($intr){
    $_SESSION['agent_account'] = $intr;
}

// 会员注册控制必填字段-20200114
$redisObj = new Ciredis();
$registerConf = $redisObj->getSimpleOne('member_register_set');
$registerSet = json_decode($registerConf, true);

?>
<link rel="stylesheet" type="text/css" href="/style/tncode/style.css?v=<?php echo AUTOVER; ?>"  />

<style>
    .register_container{position:fixed;width:700px;height:600px;background:#ccc;margin-top:-300px;top:50%;margin-left:-350px;left:50%;z-index:1999;border-radius:10px}
    .register_top{width:100%;height:100px;padding:10px 0;position:relative;border-bottom:1px solid #f1f1f1}
    .register_top h4{width:100%;font-size:24px;text-align:center;height:30px;color:#1e1e1e;line-height:30px;margin-bottom:10px}
    .register_top p{width:100%;font-size:12px;text-align:center;line-height:18px;color:#8c8c8c}
    .register_top a{color:#ff4001}
    .register_info{width:330px;padding-top:20px;padding-bottom:20px;position:absolute;margin:15px 0 0 -165px;left:50%;background:#ccc}
    .input_username,.input_pwd,.input_pwd_again,.input_phone{position:relative}
    .input_username_text,.input_pwd_text,.input_pwd_again_text,.input_phone_text{padding:4px 20px;box-sizing:border-box;width:100%;border:none;outline:none;font-size:14px;border-bottom:1px solid #8c8c8c;background:transparent;color:#000}
    .input_username:before{content:"";display:block;width:20px;height:20px;background:url(/<?php echo TPL_NAME;?>images/icon3.png) no-repeat -10px -11px;position:absolute;left:0;top:3px}
    .input_phone:before{content:"";display:block;width:20px;height:20px;background:url(/<?php echo TPL_NAME;?>images/icon3.png) no-repeat -50px -11px;position:absolute;left:0;top:3px}
    .error_message{display:block;width:100%;height:20px;line-height:20px;color:#E82626;padding-left:20px;margin-bottom:15px;text-align:left;font-size:14px}
    .input_pwd:before,.input_pwd_again:before{content:"";display:block;width:20px;height:20px;background:url(/<?php echo TPL_NAME;?>images/icon3.png) no-repeat -90px -11px;position:absolute;left:0;top:2px}
    .register_now{width:100%;background:#E82626;text-align:center;padding:10px 0;color:white;font-size:14px;border:none;outline:none;cursor:pointer;margin-bottom:15px;border-radius:5px;box-shadow:3px 3px 2px #000}
    .read_agreement{width:100%}
    label{cursor:pointer}
    #agree{left:0;top:4px;position:relative}
    #agree+span{font-size:14px;color:#000;position:relative;top:2px}
    .read_agreement_text{color:#ff4001;font-size:14px;position:relative;top:2px}
    .captchaImg {position: absolute;top: -6px;right: 0;}
    .tip_first,.tip_second{width:140px;height:100px;position:absolute;left:-150px;top:-30px;border:1px solid #666;display:none;border-radius:10px}
    .tip_first ul,.tip_second ul{display:flex;width:100%;height:100%;justify-content:space-around;align-items:center;flex-direction:column}
    .tip_first ul li,.tip_second ul li{font-size:12px;width:100%;height:28px;line-height:28px;text-align:left;position:relative;padding-left:18px}
    .tip_first ul li:before,.tip_second ul li:before{content:"!";display:inline-block;position:absolute;width:14px;height:14px;border:1px solid #ff4001;background:#ff4001;border-radius:50%;left:0;margin-top:-8px;top:50%;text-align:center;line-height:14px;color:#fff}
    .tip_third{padding-left:18px;width:140px;height:40px;border:1px solid #666;position:absolute;left:-150px;font-size:12px;text-align:center;line-height:40px;margin-top:-20px;top:50%;display:none;border-radius:10px}
    .tip_third:before,.tip_fourth:before,.tip_fifth:before{content:"!";display:inline-block;position:absolute;width:14px;height:14px;border:1px solid #ff4001;background:#ff4001;border-radius:50%;left:0;margin-top:-8px;top:50%;text-align:center;line-height:14px;color:#fff}
    .tip_fourth,.tip_fifth{padding-left:18px;width:140px;height:40px;border:1px solid #666;position:absolute;left:-150px;font-size:12px;text-align:center;line-height:20px;margin-top:-20px;top:50%;display:none;border-radius:10px}
    .tip_fourth:before,.tip_fifth:before{top:25%}
    .navhidediv{position:fixed;left:0;right:0;top:-100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000}

</style>

<div class="register_container">
    <div class="register_top">
        <h4>注册成为澳门线上娱乐在线客户</h4>
        <p>
            每位玩家只能在澳门线上娱乐拥有一个账号
        </p>
        <!--<div class="closeR">X</div>-->
    </div>
    <div class="register_info">
        <input type="hidden" name="introducer" id="introducer" value="<?php echo $_SESSION['agent_account'];?>" minlength="4" maxlength="15" autocomplete="off">
        <div class="input_username">
            <input type="text" id="username" placeholder="请输入账号" minlength="5" nmaxlength="15" class="input_username_text">
            <span class="error_message">*请输入账号</span>
            <div class="tip_first">
                <ul>
                    <li>5-16个字符</li>
                    <li>0-9，a-z，A-Z组成</li>
                    <li>第一个必须为字母</li>
                </ul>
            </div>
        </div>
        <div class="input_pwd">
            <input type="password" id="password" placeholder="6-15位密码" minlength="6" maxlength="15" class="input_pwd_text">
            <span class="error_message">*请输入密码</span>
            <div class="tip_second">
                <ul>
                    <li>6-15个字符</li>
                    <li>必须包含数字和字母</li>
                    <li>不允许连续三位相同</li>
                </ul>
            </div>
        </div>
        <div class="input_pwd_again">
            <input type="password" id="password2" placeholder="确认密码" minlength="6" maxlength="15" class="input_pwd_again_text">
            <span class="error_message">*请确认密码</span>
            <div class="tip_third">
                请您再次输入您的密码
            </div>
        </div>
        <?php if(empty($registerSet) || $registerSet['telOn'] == 1) { ?>
            <div class="input_phone">
                <input type="text" id="phone" class="input_phone_text" placeholder="手机号码" minlength="11" maxlength="11">
                <span class="error_message">*请输入手机号码</span>
                <div class="tip_fourth">
                    为您的账户安全,请您填写完整的手机号码
                </div>
            </div>
        <?php } if($registerSet['chatOn'] == 1) { ?>
            <div class="input_username">
                <input type="text" id="wechat" placeholder="微信号码" class="input_phone_text">
                <span class="error_message">*请输入微信号码</span>
                <div class="tip_fourth">
                    为您的账户安全,请您填写完整的微信号码
                </div>
            </div>
        <?php } if($registerSet['qqOn'] == 1) { ?>
            <div class="input_username">
                <input type="text" id="qq" placeholder="QQ号码" class="input_phone_text">
                <span class="error_message">*请输入QQ号码</span>
                <div class="tip_fourth">
                    为您的账户安全,请您填写完整的QQ号码
                </div>
            </div>
        <?php } if($registerSet['aliasOn'] == 1) { ?>
            <div class="input_username">
                <input type="text" id="alias" placeholder="真实姓名" class="input_phone_text">
                <span class="error_message">*请输入真实姓名</span>
                <div class="tip_fourth">
                    为您的账户安全,请您填写真实姓名
                </div>
            </div>
        <?php } ?>
        <input type="hidden" id="verifycode" class="input_phone_text">
        <!--<div class="input_phone">
            <input type="text" id="verifycode" class="input_phone_text" placeholder="验证码" minlength="4" maxlength="6">
            <span class="error_message">*请输入验证码</span>
            <div class="tip_fourth">
                请输入验证码
            </div>
            <span class="captchaImg">
                    <img class="register_captchaImg" src="/app/member/include/validatecode/captcha.php" alt="验证码" onclick="this.src='/app/member/include/validatecode/captcha.php?v='+Math.random();">
                  </span>
        </div>-->
       <!-- <div class="auth_code">
            <input type="text" placeholder="手机验证码" class="auth_code_input">
            <a href="#" class="auth_code_hint">发送验证码</a>
            <span class="error_message">*请输入手机验证码</span>
            <div class="tip_fifth">
                请将右侧的验证码填入到输入框内
            </div>
        </div>-->
        <button class="submit-btn register_now"><div >立即注册</div></button>
            <div class="read_agreement">
            <label for="agree">
                <input type="checkbox" id="agree" checked>
                <span>我已阅读并同意</span>
            </label>
            <a href="javascript:;" class="open_agreement read_agreement_text">
                《澳门线上娱乐用户服务协议》
            </a>
            <span class="error_message">*请勾选协议</span>
        </div>
    </div>
</div>

<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/layer/layer.js"></script>
<script type="text/javascript" src="/style/tncode/tn_code.js?v=<?php echo AUTOVER; ?>" ></script>
<script type="text/javascript" src="/js/register/validate.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="/js/loadpage_common.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">

    $(function () {

        // 初始化验证码
        var $TNCODE = tncode;
        tncode.init();

        /*$('#verifycode').focus(function () { // 更新验证码
            $('.register_captchaImg').attr('src','/app/member/include/validatecode/captcha.php?v='+Math.random());
        })*/
        memberRegAction() ;
        enterSubmitAction();

        // 注册
        function memberRegAction() {
            // 查看协议
            $('.open_agreement').on('click',function () {
                var xy_str = '<div class="terms" style="padding: 20px 20px 0; color: #6e6e6e; font-size: 14px; line-height: 1.5;">' +
                    '        <div class="terms-header" style=" font-weight:bold;font-size:16px;color:#717171;background-color:#e3e3e3;padding:15px;margin:10px 0;">开户协议</div>' +
                    '        <span>立即开通' +
                    '        <span class="brand-name-txt"></span>立即开通账户，享受最优惠的各项红利!</span>' +
                    '        <ul class="terms-content">' +
                    '            <li>' +
                    '                <span class="brand-name-txt"></span>只接受合法博彩年龄的客户申请。同时我们保留要求客户提供其年龄证明的权利。' +
                    '            </li>' +
                    '            <li>在进行注册时所提供的全部信息必须在各个方面都是准确和完整的。在使用借记卡或信用卡时，持卡人的姓名必须与在网站上注册时的一致。' +
                    '            </li>' +
                    '            <li>在开户后进行一次有效存款，恭喜您成为有效会员!' +
                    '            </li>' +
                    '            <li>存款免手续费，开户最低入款金额100人民币</li>' +
                    '            <li>成为有效会员后，客户有责任以电邮、联系在线客服、在网站上留言等方式，随时向本公司提供最新的个人资料。' +
                    '            </li>' +
                    '            <li>经发现会员有重复申请账号行为时，有权将这些账户视为一个联合账户。我们保留取消、收回会员所有优惠红利，以及优惠红利所产生的盈利之权利。每位玩家、每一住址、每一电子邮箱、 每一电话号码、相同支付卡/信用卡号码，以及共享计算机环境 (例如:网吧、其他公共用计算机等)只能够拥有一个会员账号，各项优惠只适用于每位客户在 唯一的账户。' +
                    '            </li>' +
                    '            <li>' +
                    '                <span class="brand-name-txt"></span>是提供互联网投注服务的机构。请会员在注册前参考当地政府的法律，在博彩不被允许的地区，如有会员在 注册、下注，为会员个人行为， 不负责、承担任何相关责任。' +
                    '            </li>' +
                    '            <li>无论是个人或是团体，如有任何威胁、滥用优惠的行为，保留权利取消、收回由优惠产生的红利，并保留权利追讨最高50%手续费。' +
                    '            </li>' +
                    '            <li>所有的优惠是特别为玩家而设，在玩家注册信息有争议时，为确保双方利益、杜绝身份盗用行为，保留权利要求客户向我们提供充足有效的证件， 并以各种方式辨别客户是否符合资格享有我们的任何优惠。' +
                    '            </li>' +
                    '            <li>客户一经注册开户，将被视为接受所有颁布在 网站上的规则与条例。' +
                    '            </li>' +
                    '            <li>本公司是使用 现金网所提供的在线娱乐软件，若发现您在同系统的娱乐城上开设多个会员账户，并进行套利下注；本公司有权取消您的会员账号及下注盈利所得！' +
                    '            </li>' +
                    '        </ul>' +
                    '        <div class="terms-button-wrapper" style="text-align: center;">' +
                    '        <span onclick="parent.layer.close(regAgreement)" class="terms-button" style="color:#fff;background-color:#404040;border-radius:5px;display:inline-block;padding:15px;margin:20px auto;cursor:pointer;">' +
                    '          我已满合法博彩年龄並同意各项开户条约' +
                    '        </span>' +
                    '        </div>' +
                    '    </div>';

                regAgreement = parent.layer.open({
                    type: 1,
                    area: ['600px', '786px'],
                    skin: 'layui-layer-agreement', //样式类名
                    closeBtn: 0, //不显示关闭按钮
                    anim: 2,
                    shadeClose: true, //开启遮罩关闭
                    content: xy_str
                });
            });

            var regflage = false ;
            var aliasOn = '<?php echo $registerSet['aliasOn']; ?>';
         $('.submit-btn').on('click',function () {
             if(regflage){
                 return false ;
             }
             var introducer = $("#introducer").val();
             var username = $("#username").val();
             var passwd = $("#password").val();
             var passwd2 =$("#password2").val();
             var phone =$("#phone").val();
             var wechat = $("#wechat").val();
             var qq = $("#qq").val();
             var alias = $("#alias").val();
             var verifycode =Math.random();
             var title = '' ;

             if (username == "" ) {
                 title = '账号不能为空!';
                 layer.msg(title,{time:alertTime});

                 return false;
             }
             if (!isNum(username)){
                 title = '请输入正确的账号！格式：以英文+数字,长度5-15!';
                 layer.msg(title,{time:alertTime});

                 return false;
             }
             if (username.length < 5 || username.length > 15) {
                 title = '账号需在5-15位之间!';
                 layer.msg(title,{time:alertTime});

                 return false;
             }
             if ( passwd == "" ) {
                 title = '密码不能为空！';
                 layer.msg(title,{time:alertTime});

                 return false;
             }
             if (passwd.length < 6 || passwd.length > 15) {
                 title = '密码需在6-15位之间！';
                 layer.msg(title,{time:alertTime});

                 return false;
             }
             if ( passwd2 != passwd ) {
                 title = '密码与确认密码不一致！';
                 layer.msg(title,{time:alertTime});

                 return false;
             }
             if(phone != undefined && (phone=='' || !isMobel(phone))){
                 title = '请输入正确的手机号码！';
                 layer.msg(title,{time:alertTime});
                 return false;
             }
             if(wechat != undefined && (wechat=='' || !isWechat(wechat))){
                 title = '请输入正确的微信号码！';
                 layer.msg(title,{time:alertTime});
                 return false;
             }
             if(qq != undefined && (qq=='' || !isQQNumber(qq))){
                 title = '请输入正确的QQ号码！';
                 layer.msg(title,{time:alertTime});
                 return false;
             }
             if(aliasOn == 1){
                 if(alias != undefined && (alias=='' || !isChinese(alias))){
                     title = '请输入正确的真实姓名！';
                     layer.msg(title,{time:alertTime});
                     return false;
                 }
             }
             <?php
             if(LOGIN_IS_VERIFY_CODE) {
                 // 验证通过
                 echo '$TNCODE.show();
                        $TNCODE.onsuccess(function () {';
             }
             ?>
             regflage = true ;
             var actionurl = "/app/member/mem_reg_add.php" ;
             $.ajax({
                 type : 'POST',
                 dataType : 'json',
                 url : actionurl ,
                 data : {
                     keys:'add',
                     introducer:introducer,
                     username:username,
                     password:passwd,
                     password2:passwd2,
                     phone:phone,
                     wechat: wechat,
                     qq: qq,
                     alias: alias,
                     verifycode:verifycode
                 },
                 success:function(res) {
                     if(res){
                         regflage = false ;
                         layer.msg(res.describe,{time:alertTime});
                         if(res.status ==200){
                             top.location.href = '/' ;
                         }else{ // 注册失败
                             $TNCODE.init();
                         }
                     }

                 },
                 error:function(){
                     regflage = false ;
                     layer.msg('稍后请重试',{time:alertTime});
                 }
             });

             <?php
             if(LOGIN_IS_VERIFY_CODE) {
                 echo '})';
             }
             ?>
         })
        }
    })
</script>