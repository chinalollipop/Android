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
.terms-content li{font-size: 14px;line-height: 25px;}
.terms-button-wrapper .terms-button{font-size: 16px;}
</style>

<div class="reg_sec_all">
    <div id="new-banner">
        <div id="new-banner-box">
            <div id="banner"><img src="<?php echo TPL_NAME;?>images/live/6.jpg"></div>
            <div class="msg-connet">

                <div class="left" style="margin-lefT:8px;">
                    <div><a href="javascript:;" class="to_lives ylc_top"></a></div>
                    <div> <a href="javascript:;" class="to_lives ylc_left"></a>
                        <a href="javascript:;" class="to_lives ylc_right"></a> </div>
                </div>

            </div>
        </div>
    </div>

    <div id="sidebarwrap">
        <div id="sidebarbox">
            <div id="leftsidebar">
                <ul>
                    <li class="bbin"><a href="javascript:;" class="to_lives cur">BBIN娱乐</a></li>
                    <li class="mg"><a href="javascript:;" class="to_lives">AG娱乐</a></li>
                    <li class="sports"><a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">体育投注</a></li>
                    <li class="lot"><a href="javascript:;" class="to_lotterys">彩票游戏</a></li>
                    <li class="ele"><a href="javascript:;" class="to_games">电子游艺</a></li>
                </ul>
                <div id="ads1"><a href="javascript:;" class="to_promos"></a></div>
                <div id="ads2"><a href="javascript:;" class="to_promos"></a></div>
            </div>
            <div id="rightsidebar">
                <div id="main" class="reg">
                    <div id="middle">
                        <div class="reg_bg" id="registerbg"></div>
                        <div class="form">
                            <div class="reg_top" id="reg_top"></div>
                            <div class="reg_head">
                                <p style="background:url(<?php echo TPL_NAME;?>images/reg/reg.ico) no-repeat left center; background-size:48px 48px;" class="add_title">立即加入</p></div>
                            <form id="register">
                                <h2>账户信息</h2>
                                <div class="use"><label><span>用户名:</span><input name="username" id="username" minlength="5" maxlength="15" type="text" value=""> * 5-15个英文和数字组成,至少一个字母  </label></div>
                                <div class="pass"><label><span>密    码:</span><input name="password" id="password" minlength="6" maxlength="15" type="password" value=""> * 6-15个任意字符组成 </label></div>
                                <div class="rep"><label><span>确认密码:</span><input name="repassword" id="password2" type="password" minlength="6" maxlength="15" value=""> * 6-15个任意字符组成 </label></div>
                                <div class="reg_bottom"></div>
                                <h2>个人资料</h2>
                                <?php if(empty($registerSet) || $registerSet['telOn'] == 1) { ?>
                                    <div><label><span>联系电话:</span><input type="text" name="tel"  id="phone" minlength="11" maxlength="11" value=""> * 请填写您的固定电话或手机 </label></div>
                                <?php } if($registerSet['chatOn'] == 1) { ?>
                                    <div><label><span>微信号码:</span><input type="text" name="wechat" id="wechat" value=""> * 填写真实微信号码 </label></div>
                                <?php } if($registerSet['qqOn'] == 1) { ?>
                                    <div><label><span>QQ号码:</span><input type="text" name="qq" id="qq" value=""> * 填写真实QQ号码</label></div>
                                    <div class="group">
                                        <label>QQ号码 <span class="red_color">*</span>：</label>
                                        <input type="text" class="pw-input" name="qq" id="qq" autocomplete="off" placeholder="请认真填写，以便有优惠活动可以及时通知您参与">
                                    </div>
                                <?php } ?>

                                <div><label><span>推荐ID:</span><input type="text" id="introducer" value="<?php echo $_SESSION['agent_account'];?>" minlength="4" maxlength="15"> 没有推荐人可以不填</label></div>
                                <div class="check">
                                    <input type="checkbox" value="Y" checked="checked">我已届满合法博彩年龄﹐且同意各项开户条约。
                                    <a href="javascript:;" class="open_agreement">开户协议</a>
                                </div>
                                <div class="submitDiv verifyRandom">
                                    <input name="submit" type="button" class="submit-btn" id="submitbutton" value=" 提 交 ">
                                </div>
                                <div class="reg_bottom"></div>
                            </form>
                            <dl>
                                <dt>备注：</dt>
                                <dd>1.标记有<span> *</span> 者为必填项目。</dd>
                                <dd>2.手机与取款密码为取款金额时的凭证,请会员务必填写详细资料。</dd>
                                <dd>3.若公司有其它活动会E-MAIL通知，请客户填写清楚。</dd>
                            </dl>
                        </div>

                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
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
                                verifycode: Math.random(),
                                thirdLottery: '<?php echo $datajson['agentid'];?>'  // 是否是第三方注册
                            },
                            success: function (res) {
                                if (res) {
                                    regflage = false;
                                    layer.msg(res.describe, {time: alertTime});
                                    if (res.status == 200) {
                                        window.location.href = '/';
                                    } else {
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