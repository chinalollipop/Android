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

$companyName = COMPANY_NAME;

// 会员注册控制必填字段-20200114
$redisObj = new Ciredis();
$registerConf = $redisObj->getSimpleOne('member_register_set');
$registerSet = json_decode($registerConf, true);

?>


<style>
    .rcontwz{font-size:15px;color:#fff;padding:10px 0px;line-height:28px}
    .rcontwz a{color:#fff}
    .rcontwz a:hover{color:red}
</style>

<div class="page_banner">
    <div class="promlink">
        <div class="centre clearFix">
            <div class="title"><img src="<?php echo TPL_NAME;?>images/wel.jpg"></div>
            <div class="marqueeWarp">
                <p style="text-align: center">
                    <marquee id="msgNews" scrollamount="4" scrolldelay="100" direction="left" onmouseover="this.stop();" onmouseout="this.start();" style="cursor: pointer;height: 30px;line-height: 30px;width: 950px;color: #fff;">
                        <?php echo $_SESSION['memberNotice']; ?>
                    </marquee>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="agencyWrap">
    <div class="nav fl">
        <div class="about-title"> 热门游戏列表 </div>
        <ul class="about-nav">
            <li><a href="javascript:;" class="to_lives">  视讯直播 </a></li>
            <li><a href="javascript:;" class="to_fish">  捕鱼达人 </a></li>
            <li><a href="javascript:;" class="to_chess">  KY棋牌 </a></li>
            <li><a href="javascript:;" class="to_chess">  LEG棋牌 </a></li>
            <li><a href="javascript:;" class="to_games" >  AG电子 </a></li>
            <li><a href="javascript:;" class="to_games" data-type="mg">  MG电子 </a></li>
            <li><a href="javascript:;" class="to_lotterys">  彩票游戏 </a></li>
        </ul>
        <span class="bottomLogo"></span>
    </div>

<div class="articleWrap modal-body reg_sec_all">
    <form method="post" name="LoginForm" id="LoginForm" > <!--  action="/app/member/mem_reg_add.php?keys=add" -->
       <div class="top">
           <span class="red_color">欢迎光临<?php echo $companyName;?>67.tt</span>
       </div>
        <div class="rcontwz">
            <p>1.<?php echo $companyName;?>同您携手博出未来，开启您的博彩圆梦之旅<br>
                2.加入<?php echo $companyName;?>，将会不定期的推出最顶端最给力的优惠活动！<br>
                3.24小时提款0审核0冻结0手续费，存取款0-3分钟火速到账！<br>
                4.10元即可存款，全面支持支付宝、微信、信用卡、银联、在线支付全程担保！<br>
                5.不计输赢，天天返水,首存1000元赠送88元，更多优惠等着您，详情请您查看【优惠活动】<br>
                6.尚未注册/存款的亲们强烈建议您注册/存款，超多优惠等着您！<br>
                <?php echo $companyName;?>官方网址 <a href="<?php echo getSysConfig('vns_backup_web_url');?>" target="_blank"><?php echo getSysConfig('vns_backup_web_url');?></a>
                备用网址 <a href="https://vns2088.com" target="_blank">vns2088.com</a>
                备用网址<a href="https://vns3088.com" target="_blank">vns3088.com</a>
            </p>
        </div>
        <div class="reg_left">
            <div class="group" style="display: none">
                <label>推荐人 ：</label>
                <input type="hidden" name="introducer" id="introducer" value="<?php echo $_SESSION['agent_account'];?>" minlength="4" maxlength="15" autocomplete="off" <?php if($_SESSION['agent_account']){ echo 'readonly';}?> >
                <span class="reg_tip"> <span class="red_color">*</span>如果没有介绍人，可以不填写!</span>
            </div>
            <div class="group">
                <label>*账号 ：</label>
                <input type="text" name="username" id="username" minlength="5" maxlength="15" autocomplete="off" >
                <span class="reg_tip"> <span class="red_color">*</span>5-15位数字或字母，或手机号 微信 QQ号组成!</span>
            </div>
            <div class="group">
                <label>*会员密码 ：</label>
                <input type="password" class="pw-input" name="password" id="password" minlength="6" maxlength="15" autocomplete="off" >
                <span class="reg_tip"> <span class="red_color">*</span>必须由6-15位英文或数字且符合0-9或a-z字母!</span>
            </div>
            <div class="group">
                <label>*确认密码 ：</label>
                <input type="password" class="pw-input" name="password2" id="password2" minlength="6" maxlength="15" autocomplete="off" >
                <span class="reg_tip"> <span class="red_color">*</span>请再次输入您的登录密码!</span>
            </div>
            <?php if(empty($registerSet) || $registerSet['telOn'] == 1) { ?>
                <div class="group">
                    <label>*手机号码 ：</label>
                    <input type="text" class="pw-input" name="phone" id="phone" minlength="11" maxlength="11" autocomplete="off" >
                    <span class="reg_tip"> <span class="red_color">*</span>请认真填写，以便有优惠活动可以及时通知您参与!</span>
                </div>
            <?php } if($registerSet['chatOn'] == 1) { ?>
                <div class="group">
                    <label>*微信号码 ：</label>
                    <input type="text" class="pw-input" name="wechat" id="wechat" autocomplete="off" >
                    <span class="reg_tip"> <span class="red_color">*</span>请认真填写，以便有优惠活动可以及时通知您参与!</span>
                </div>
            <?php } if($registerSet['qqOn'] == 1) { ?>
                <div class="group">
                    <label>*QQ号码 ：</label>
                    <input type="text" class="pw-input" name="qq" id="qq" autocomplete="off" >
                    <span class="reg_tip"> <span class="red_color">*</span>请认真填写，以便有优惠活动可以及时通知您参与!</span>
                </div>
            <?php } ?>
            <!--<div class="group">
                <label>*验证码 ：</label>
                <input type="text" class="pw-input" id="verifycode" name="verifycode" minlength="4" maxlength="6" autocomplete="off" placeholder="验证码">
                <span class="captchaImg">
                    <img class="register_captchaImg" src="/app/member/include/validatecode/captcha.php" alt="点击更新验证码" onclick="$('.register_captchaImg').attr('src','/app/member/include/validatecode/captcha.php?v='+Math.random())">
                </span>

            </div>-->
        </div>
        <div id="field_agreement" class="group">
            <div class="ageCheck">
                <input type="checkbox" value="None" class="ageCheck_input" name="ageCheck" checked style="display: none;">
                <div class="input-label">

                    <p>
                        <span class="reg_checkbox" ></span>我已满18岁并且已阅读及同意<span class="account-terms open_agreement">开户协议</span>。
                    </p>
                </div>
            </div>
        </div>

        <div class="submit-btn">
            创建账号
        </div>

        <div class="f14 cw">
            备注：
            <br>1.标记有 <span class="red_color">*</span> 者为必填项目。
            <br>2.手机与取款密码为取款金额时的凭证,请会员务必填写详细资料。
            <br>3.若公司有其它活动会E-MAIL通知，请客户填写清楚。
        </div>

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

                    // 验证通过
                    $TNCODE.show();
                    $TNCODE.onsuccess(function () {
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
                                    } else { // 失败
                                        $TNCODE.init();
                                    }
                                }
                            },
                            error: function () {
                                regflage = false;
                                layer.msg('稍后请重试', {time: alertTime});
                            }
                        });
                        // document.getElementById("LoginForm").submit();
                    });


            })
        }
    })
</script>