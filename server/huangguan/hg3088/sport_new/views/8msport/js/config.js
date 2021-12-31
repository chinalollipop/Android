var forgeMemFlage = false ; // 防止重复提交
$(function () {
    showLoginBox();
    getRmemberMeAction();
    forgetPwdSubmit();
    guestLoginPhoneSubmit();
})
// 导航 hover
function indexHoverNav(){
    $('.nav-drop-ac').hover(function () {
        $(this).find('.nav-drop').stop(true).animate({'height': '230px'});
    },function () {
        $(this).find('.nav-drop').stop(true).animate({'height': '0'});
    })
}

// 加入收藏
function addUrlFavorite() {
    var url = window.location;
    var title = document.title;
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf("msie 8") > -1) {
        external.AddToFavoritesBar(url, title, '');//IE8
    } else {
        try {
            window.external.addFavorite(url, title);
        } catch (e) {
            try {
                window.sidebar.addPanel(title, url, "");//firefox
            } catch (e) {
                alert("加入收藏失败，请使用Ctrl+D进行添加");
            }
        }
    }
}
// 设为首页
function setHome(obj){
    var url = window.location;
    try{
        obj.style.behavior='url(#default#homepage)';
        obj.setHomePage(url);
    }catch(e){
        if(window.netscape){
            try{
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }catch(e){
                alert("抱歉，此操作被浏览器拒绝！\n\n请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为'true'");
            }
        }else{
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动将【"+url+"】设置为首页。");
        }
    }
}

// 登录，注册切换，顶部显示登录窗口
function showLoginBox() {
    // 显示登录窗
    $('.show-top-login-box').on('click',function () {
        $('.top-login-box').animate({'right':0});
        $('.top_username').focus();// 用户名聚焦
    });
    // 关闭登录窗
    $('.close-login').on('click',function () {
        $('.top-login-box').animate({'right':'-35%'});
    });
    var $formBoxAll = $('.formBoxAll');
    var $formBoxTestAll = $('.formBoxTestAll');

    // 登录注册切换
    $('.loginReg-btn').on('click','a',function () {
        var type = $(this).attr('data-type');

        var lg_text;
        $formBoxAll.show();
        $formBoxTestAll.hide();

        if(type == 'login'){ // 登录
            $('.show_login').show();
            $('.show_register').hide();
            lg_text = '立即登录';
        }else{ // 注册
            $('.show_register').show();
            $('.show_login').hide();
            lg_text = '立即注册';
        }
        $('.login-submit-btn').attr({'data-type':type}).text(lg_text);
    })

    // 登录与忘记密码切换
    $('.changeBoxBtn').on('click',function () {
        var type = $(this).attr('data-type');
        $('.register-bottom').hide();
        $('.'+type+'-btn').show();
        if(type=='forget'){ // 切换至忘记密码
            $formBoxAll.css({'transform':'translateX(-54%)'})
        }else{ // 登录
            $formBoxAll.css({'transform':'translateX(-3%)'})
        }
    })
    
    // 试玩填写手机号
    $('.to_top_testphone').on('click',function () {

        $formBoxAll.hide();
        $formBoxTestAll.show();

    })

    // 查看注册协议
    $('.open_agreement').on('click',function () {
        var $openObj = parent;
        if(top.body){
            $openObj = body.body_var;
        }
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
            '        <span onclick="$(\'.layui-layer-shade\').click()" class="terms-button" style="color:#fff;background-color:#404040;border-radius:5px;display:inline-block;padding:15px;margin:20px auto;cursor:pointer;">' +
            '          我已满合法博彩年龄並同意各项开户条约' +
            '        </span>' +
            '        </div>' +
            '    </div>';


        $openObj.layer.open({
            type: 1,
            area: ['600px', '786px'],
            skin: 'layui-layer-agreement', //样式类名
            closeBtn: 0, //不显示关闭按钮
            anim: 2,
            shadeClose: true, //开启遮罩关闭
            content: xy_str
        });

    });


}

// 记住我的帐号
function rememberMeAction() {
    var ifremeber = $('.rememberme').prop('checked') ;
    var username = $('.top_username').val() ;
    var password = $('.top_password').val() ;
    if(ifremeber){
        localStorage.setItem('ifremeberme',ifremeber) ;
        localStorage.setItem('username',username) ;
        localStorage.setItem('password',password) ;
    }else {
        localStorage.setItem('ifremeberme','') ;
        localStorage.setItem('username','') ;
        localStorage.setItem('password','') ;

    }
}
// 判断是否有记住帐号
function getRmemberMeAction() {
    var username = localStorage.getItem('username') ;
    var password = localStorage.getItem('password') ;
    var ifremeber = localStorage.getItem('ifremeberme') ;
    // console.log(username) ;
    if(ifremeber){
        $('.rememberme').prop('checked',true);
    }
    if(username){
        $('.top_username').val(username) ;
    }
    if(password){
        $('.top_password').val(password) ;
    }

}

// 忘记密码
function forgetPwdSubmit() {
    $('.forget-submit-btn').on('click',function () {
        var $formBoxAll = $('.formBoxAll');
        var this_step = $(this).attr('data-step');
        var username = $(".top_forget_name").val();
        var password = $(".top_forget_pwd").val();
        var password2 = $(".top_forget_pwd2").val();
        var alias = $(".top_forget_zzxm").val();
        var paypassword = $(".top_forget_paypwd").val();
        var verifycode = $(".top_forget_yzm").val();
        if(this_step == 'one'){ // 第一步
            if (alias=='') {
                alert('真实姓名不能为空');
                return false;
            }
            if (username == "") {
                alert('所需帐号不能为空');
                return false;
            }
            if (username.length < 5 || username.length > 15) {
                alert('账号需在5-15位之间');
                return false;
            }
            if(paypassword =='' || !isNumber(paypassword) || paypassword.length < 4 || paypassword.length > 6){
                alert('请输入正确的提款密码');
                return false;
            }
            if(!verifycode){
                alert('请输入验证码');
                return false;
            }

        }else{ // 第二步
            if (password == "") {
                alert('新密码不能为空');
                return false;
            }

            if (password.length < 6 || password.length > 15) {
                alert('新密码需在6-15位之间');
                return false;
            }
            if (password != password2 ) {
                alert('请检查账户密码与确认密码一致');
                return false;
            }
        }

        if(forgeMemFlage){
            return false ;
        }
        forgeMemFlage = true ;

        var senddata = {
            steptype:this_step, //  是  int  终端ID
            action_type:'reset', // String  1.check；2：recheck；3：reset(避免交互过多，可填好相关信息，直接传 reset
            username:username,
            realname:alias, // 真实姓名 action_type是recheck和reset时必填  String  用户真实账号
            withdraw_password:paypassword, // action_type是recheck和reset时必填  String  用户提款密码
            // birthday:birthday, //  action_type是recheck和reset时必填  Date  用户生日
            new_password:password, // action_type是reset时必填  String  新密码
            password_confirmation:password2, // action_type是reset时必填  String  确认密码
            verifycode:verifycode,
        }
        var actionurl = "/app/member/api/forgetPassword.php" ;
        $.ajax({
            url: actionurl ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success: function (res) {
                if(res){
                    forgeMemFlage = false ;
                    layer.msg(res.describe,{time:alertTime});
                    if(res.status == 200.1){ // 第一步验证信息
                        $('.formWrap_'+this_step).hide();
                        $('.formWrap_two').show();

                    }else if(res.status == 200.2){ // 更改密码成功
                        $('.formWrap_'+this_step).hide();
                        $('.formWrap_one').show();
                        $formBoxAll.css({'transform':'translateX(-3%)'}) // 加载登录
                    }

                }

            },
            error: function (msg) {
                forgeMemFlage = false ;
                layer.msg('更改密码异常',{time:alertTime});
            }
        });
    })

}

// 提交手机号试玩登录
function guestLoginPhoneSubmit() {
    $('.testPlay-submit-btn').on('click',function () {
        var ajaxurl='/app/member/guest_login_save_phone.php';
        var testphone = $('.top_testPlay_phone').val();
        var verifycode = $('.top_testPlay_yzm').val();
        if(testphone == '' || testphone.length != 11){
            alert('请填写正确手机号');
            return false;
        }
        if(verifycode == ''){
            alert('请填写验证码');
            return false;
        }
        var senddata={
            phone: testphone,
            verifycode: verifycode
        };
        if(forgeMemFlage){
            return false ;
        }
        forgeMemFlage = true ;
        $.ajax({
            url:  ajaxurl ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success:function(res){
                if(res){
                    forgeMemFlage = false ;
                    if(res.status=='200'){ // 登录成功
                        indexCommonObj.loadMemberTestPlayLogin();
                    }else {
                        alert(res.describe);
                    }
                }

            },
            error: function (XMLHttpRequest, status) {
                forgeMemFlage = false ;
                alert('网络错误，稍后请重试');
            }
        });
    })

}

// 视讯切换，彩票，棋牌切换
function changeGameTab() {
    $('.gameChangeTab').on('click','a',function () {
        var type = $(this).attr('data-to');
        if(!type){
            return false;
        }else{
            $(this).addClass('active').siblings().removeClass('active');
            $('.show_act').hide();
            $('.show_'+type).fadeIn();
        }
    });
}
