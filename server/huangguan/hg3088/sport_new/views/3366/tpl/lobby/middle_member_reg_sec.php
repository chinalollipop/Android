<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
$intr= isset($_REQUEST['intr'])?$_REQUEST['intr']:'';  // 介绍人

$redisObj = new Ciredis();
$datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 取redis 设置的值
$datajson = json_decode($datajson,true) ; // 第三方配置信息

if($intr){
    $_SESSION['agent_account'] = $intr;
}

// 会员注册控制必填字段-20200114
$redisObj = new Ciredis();
$registerConf = $redisObj->getSimpleOne('member_register_set');
$registerSet = json_decode($registerConf, true);

?>


<style>
    .reg_sec_all{height: 885px;background: url(<?php echo TPL_NAME;?>images/regBg.jpg) center no-repeat;padding-top: 30px;}
    .reg_sec_all .bottom_tip {width: 60%;margin: 0 auto;color: #716e6e;line-height: 26px;}
</style>

<div class="reg_sec_all">
    <div class="w_1000">
        <form method="post" name="LoginForm" id="LoginForm" > <!--  action="/app/member/mem_reg_add.php?keys=add" -->
       <div class="top">
           <span>快速注册</span>
           <span >*标记的栏目为必填选项</span>
       </div>
        <div class="reg_left" style="width:100%;margin-left:30%;">
            <div class="group" style="display: none">
                <label>介绍人 <span class="red_color">*</span></label>
                <input type="text" name="introducer" id="introducer" value="<?php echo $_SESSION['agent_account'];?>" minlength="4" maxlength="15" autocomplete="off" placeholder="5-15位数字或字母,没有可不填写">
            </div>
            <div class="group">
                <label>用户账号 <span class="red_color">*</span></label>
                <input type="text" name="username" id="username" minlength="5" maxlength="15" autocomplete="off" placeholder="5-15位数字或字母，或手机号 微信 QQ号组成">
            </div>
            <div class="group">
                <label>登入密码 <span class="red_color">*</span>：</label>
                <input type="password" class="pw-input" name="password" id="password" minlength="6" maxlength="15" autocomplete="off" placeholder="必须由6-15位英文或数字且符合0-9或a-z字母">
            </div>
            <div class="group">
                <label>确认密码 <span class="red_color">*</span>：</label>
                <input type="password" class="pw-input" name="password2" id="password2" minlength="6" maxlength="15" autocomplete="off" placeholder="请再次输入您的登录密码">
            </div>
            <?php if(empty($registerSet) || $registerSet['telOn'] == 1) { ?>
                <div class="group">
                    <label>手机号码 <span class="red_color">*</span>：</label>
                    <input type="text" class="pw-input" name="phone" id="phone" minlength="11" maxlength="11" autocomplete="off" placeholder="请认真填写，以便有优惠活动可以及时通知您参与">
                </div>
            <?php } if($registerSet['chatOn'] == 1) { ?>
                <div class="group">
                    <label>微信号码 <span class="red_color">*</span>：</label>
                    <input type="text" class="pw-input" name="wechat" id="wechat" autocomplete="off" placeholder="请认真填写，以便有优惠活动可以及时通知您参与">
                </div>
            <?php } if($registerSet['qqOn'] == 1) { ?>
                <div class="group">
                    <label>QQ号码 <span class="red_color">*</span>：</label>
                    <input type="text" class="pw-input" name="qq" id="qq" autocomplete="off" placeholder="请认真填写，以便有优惠活动可以及时通知您参与">
                </div>
            <?php } if($registerSet['aliasOn'] == 1) { ?>
                <div class="group">
                    <label>真实姓名 <span class="red_color">*</span>：</label>
                    <input type="text" class="pw-input" name="alias" id="alias" autocomplete="off" placeholder="请认真填写真实姓名">
                </div>
            <?php } ?>
            <!--<div class="group">
                <label>验证码 <span class="red_color">*</span>：</label>
                <input type="text" id="verifycode" name="verifycode" minlength="4" maxlength="6" autocomplete="off" placeholder="请输入验证码">
                <span class="captchaImg">
                <img class="register_captchaImg" src="/app/member/include/validatecode/captcha.php" alt="验证码" >
              </span>
            </div>-->
            <input type="hidden" id="verifycode" name="verifycode" >
            <div id="field_agreement" class="group">
                <div class="ageCheck">
                    <input type="checkbox" value="None" class="ageCheck_input" name="ageCheck" checked style="display: none;">
                    <div class="input-label">
                        <p><!--<span class="reg_checkbox" ></span>--> <input type="checkbox" checked style="float: left;margin: 3px;" > 我已届满合法博彩年龄，且同意 <span class="account-terms open_agreement">各项开户条约，开户协议。</span></p>
                       <!-- <p>
                            <span class="reg_18" ></span>我已满18岁并且已阅读及同意。
                        </p>-->
                    </div>
                </div>
            </div>

        </div>

        <div class="submit-btn">
            立即注册
        </div>

            <p class="bottom_tip">
                备注：<br>
                1.手机与取款密码为取款金额时的凭证,请会员务必填写详细资料。<br>
                2.若公司有其它活动会E-MAIL通知，请客户填写清楚。
            </p>
    </form>

    </div>
</div>



<script type="text/javascript">

    $(function () {

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
               //var verifycode =$("#verifycode").val();
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
                if(alias != undefined && (alias=='' || !isChinese(alias))){
                    title = '请输入正确的真实姓名！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }

                <?php
                if(LOGIN_IS_VERIFY_CODE) {
                    // 验证通过
                    echo '$TNCODE.show();
                        $TNCODE.onsuccess(function () {';
                }
                ?>
                        regflage = true;
                        var actionurl = "/app/member/mem_reg_add.php";
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: actionurl,
                            data: {
                                keys: 'add',
                                introducer: introducer,
                                username: username,
                                password: passwd,
                                password2: passwd2,
                                phone: phone,
                                wechat: wechat,
                                qq: qq,
                                alias:alias,
                                verifycode: Math.random(),
                                thirdLottery: '<?php echo $datajson['agentid'];?>'  // 是否是第三方注册
                            },
                            success: function (res) {
                                if (res) {
                                    regflage = false;
                                    layer.msg(res.describe, {time: alertTime});
                                    if (res.status == 200) {
                                        window.location.href = '/';
                                    } else { // 注册失败
                                        $TNCODE.init();
                                    }
                                }

                            },
                            error: function () {
                                regflage = false;
                                layer.msg('稍后请重试', {time: alertTime});
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