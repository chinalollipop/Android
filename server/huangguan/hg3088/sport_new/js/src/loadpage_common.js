
// 加载页面公用
var gameJackPort='';
var getMessageInt ='';
var getAllMonFlage = false ; // 加载用户金额
var getMonFlage = false ; // 加载用户金额
var alertTime = 3000 ; // 弹窗提示时间
var transferFlage = false ; // 额度转换
var testalert = '请注册真实账号';
var logalert = '请先登录账户';
var time_out = 8000; // ajax 超时时间
var sportTimerAc = null ; // 新版体育定时器

var indexCommonObj = {
    'download_android_app':'.download_android_app' , // 安卓 APP下载二维码
    'download_ios_app':'.download_ios_app' , // IOS APP下载二维码
    'server_wechat_img':'.server_wechat_img' , // 微信客服
    'float_pic_left':'.float_pic_left' , // 首页左浮动图
    'float_pic_right':'.float_pic_right' , // 首页右浮动图
    'ess_service_phone':'.ess_service_phone' , // 24小时客服电话
    'phl_service_phone':'.phl_service_phone' , // 投诉电话
    'sz_service_email':'.sz_service_email' , // 邮箱
    'qq_service_number':'.qq_service_number' , // 客服qq
    'agent_service_number':'.agent_service_number' , // 代理QQ
    'wechat_service_number':'.wechat_service_number' , // 微信客服
    'backup_web_url':'.backup_web_url' , // 官方网址
    'to_service':'.to_service' , // 代理登录
    'to_index':'.to_index' , //跳转到首页
    'to_sports':'.to_sports' , //跳转到体育页面
    'to_sec_sports':'.to_sec_sports' , //跳转到体育新版二级页面
    'to_lives':'.to_lives' , //跳转到真人页面
    'to_lives_upgraded':'.to_lives_upgraded' , //跳转到真人升级页面
    'to_games':'.to_games' , //跳转到电子游戏页面
    'to_lotterys':'.to_lotterys' , //跳转到彩票页面
    'to_lotterys_third':'.to_lotterys_third' , //跳转到第三方彩票页面
    'to_promos':'.to_promos' , //跳转到优惠活动页面
    'to_promos_details':'.to_promos_details' , //跳转到优惠活动详情页面
    'to_proxy':'.to_proxy' , //跳转到代理加盟页面
    'to_agentreg':'.to_agentreg' , //跳转到代理加盟注册页面
    'to_memberreg':'.to_memberreg' , //跳转到会员注册页面
    'to_testphone':'.to_testphone' , // 会员试玩手机号登录
    'to_memberlogin':'.to_memberlogin' , //跳转到会员登录页面
    'to_testplaylogin':'.to_testplaylogin' , // 会员试玩登录
    'to_terms':'.to_terms' , //跳转到使用条款页面
    'to_save':'.to_save' , //跳转到存款帮助页面
    'to_aboutus':'.to_aboutus' , //跳转到关于我们页面
    'to_presence':'.to_presence' , //跳转到风采页面
    'to_livechat':'.to_livechat' , //跳转到在线客服链接
    'to_company':'.to_company' , //跳转到联系我们页面
    'to_responsibility':'.to_responsibility' , //跳转到负责任博彩页面
    'to_downloadapp':'.to_downloadapp' , //跳转到app 下载页面
    'to_chess':'.to_chess' , //跳转到棋牌页面
    'to_dianjing':'.to_dianjing' , //跳转到电竞页面
    'to_fish':'.to_fish' , //跳转到捕鱼页面
    'to_platform_tranfer':'.to_platform_tranfer' , //跳转到转账页面
    'to_deposit':'.to_deposit' , //跳转到存款页面
    'to_withdraw':'.to_withdraw' , //跳转到提款页面
    'to_usercenter':'.to_usercenter' , //跳转到会员中心页面
    'to_usercenter_content':'.to_usercenter_content' , //跳转到会员中心总页面
    'to_userbetaccount':'.to_userbetaccount' , //跳转到账户记录页面
    'to_forgetpassword':'.to_forgetpassword' , //跳转到忘记密码页面
    'to_user_email':'.to_user_email' , //跳转到会员信息页面
    'to_line_sense':'.to_line_sense' , //跳转到线路检测页面
    'change_user_bank':'.change_user_bank' , //跳转到更换银行卡页面
    'to_suggestion':'.to_suggestion' , // 意见/投诉
    'transfer_input':'.transfer_input' , // 用户额度转换输入框
    'user_member_amount':'.user_member_amount' , // 用户金额框
    'user_member_lottery_amount':'.user_member_lottery_amount' , // 用户彩票金额框
    'user_member_ag_amount':'.user_member_ag_amount' , // 用户AG金额框
    'user_member_ky_amount':'.user_member_ky_amount' , // 用户开元金额框
    'user_member_ly_amount':'.user_member_ly_amount' , // 用户乐游金额框
    'user_member_hg_amount':'.user_member_hg_amount' , // 用户皇冠金额框
    'user_member_vg_amount':'.user_member_vg_amount' , // 用户VG金额框
    'user_member_kl_amount':'.user_member_kl_amount' , // 用户快乐棋牌金额框
    'user_member_mg_amount':'.user_member_mg_amount' , // 用户MG金额框
    'user_member_cq_amount':'.user_member_cq_amount' , // 用户CQ9金额框
    'user_member_og_amount':'.user_member_og_amount' , // 用户OG视讯金额框
    'user_member_bbin_amount':'.user_member_bbin_amount' , // BBIN视讯
    'user_member_third_lottery_amount':'.user_member_third_lottery_amount' , // 三方彩票余额
    'user_member_sc_amount':'.user_member_sc_amount', // 皇冠体育余额
    'user_member_mw_amount':'.user_member_mw_amount' , // WM电子
    'user_member_fg_amount':'.user_member_fg_amount' , // FG电子
    'user_member_avia_amount':'.user_member_avia_amount' , // 用户泛亚电竞金额框
    'user_member_fire_amount':'.user_member_fire_amount' , // 用户雷火电竞金额框
    middlecontent : $('.middle_content'),
    middle_usercenter_content : $('.middle_usercenter_content'), // 会员中心公用
    'owe_bet':'#owe_bet', // 会员提款打码量
    'total_bet':'#total_bet', // 会员已打码量
    'bet_detail':'#bet_detail', // 会员打码量详情按钮

    // 基本设置
    settingBaseWeb: function(){
        $(indexCommonObj.ess_service_phone).text(web_config.service_phone_24)
        $(indexCommonObj.phl_service_phone).text(web_config.service_phone_phl);
        $(indexCommonObj.sz_service_email).text(web_config.service_email);
        $(indexCommonObj.qq_service_number).text(web_config.service_qq);
        $(indexCommonObj.agent_service_number).text(web_config.agents_service_qq);
        $(indexCommonObj.wechat_service_number).text(web_config.service_wechat);
        $(indexCommonObj.backup_web_url).text(web_config.backup_web_url);
        $(indexCommonObj.download_android_app).css({'background':'url('+webPicConfig.download_android_url+') center no-repeat'});
        $(indexCommonObj.download_ios_app).css({'background':'url('+webPicConfig.download_ios_url+') center no-repeat'});
        $(indexCommonObj.server_wechat_img).css({'background':'url('+webPicConfig.server_wechat_code+') center no-repeat'});
        $(indexCommonObj.float_pic_left).css({'background':'url('+webPicConfig.index_pic_left+') center no-repeat'});
        $(indexCommonObj.float_pic_right).css({'background':'url('+webPicConfig.index_pic_right+') center no-repeat'});

    },
    /* *
     * 四舍五入保留小数
     * num 保留几位
     */
    advFormatNumber: function (value, num) {
        var a_str = indexCommonObj.formatNumber(value, num);
        var a_int = parseFloat(a_str);
        if (value.toString().length > a_str.length) {
            var b_str = value.toString().substring(a_str.length, a_str.length + 1);
            var b_int = parseFloat(b_str);
            if (b_int < 5) {
                return a_str;
            } else {
                var bonus_str, bonus_int;
                if (num == 0) {
                    bonus_int = 1;
                } else {
                    bonus_str = "0."
                    for (var i = 1; i < num; i ++ )
                        bonus_str += "0";
                    bonus_str += "1";
                    bonus_int = parseFloat(bonus_str);
                }
                a_str = indexCommonObj.formatNumber(a_int + bonus_int, num)
            }
        }
        return a_str;
    },
    formatNumber: function (value, num){
        var a, b, c, i;
        a = value.toString();
        b = a.indexOf('.');
        c = a.length;
        if (num == 0) {
            if (b != - 1) {
                a = a.substring(0, b);
            }
        } else {
            if (b == - 1) {
                a = a + ".";
                for (i = 1; i <= num; i ++ ) {
                    a = a + "0";
                }
            } else {
                a = a.substring(0, b + num + 1);
                for (i = c; i <= b + num; i ++ ) {
                    a = a + "0";
                }
            }
        }
        return a;
    },
    // 获取用户短信
    getUserMessage: function(){
        if(!uid){
            return;
        }
        var url = '/app/member/api/userEmailsApi.php?v='+Math.random() ;
        $.ajax({
            type: 'POST',
            url: url,
            data: {action:'message',emailMount:'yes'},
            dataType: 'json',
            timeout:time_out,
            success: function (res) {
                if(res.data){
                    $('.dis_for_email_mount').html('('+ res.data.emailMount +')');
                    $('.dis_for_email_mount_1').html(res.data.emailMount);
                    if(res.data.emailMount>0){ // 有短信
                        $('.for_email_mount').show();
                    }else{
                        $('.for_email_mount').hide();
                    }
                }

            },
            error: function (res) {
                layer.msg('获取数据失败，请稍后再试!',{time:alertTime});

            }
        });
    },

    // 重新封装 load 方法
    JqueryReload: function(url,callback,obj){
        // var loadPage = layer.load(0, { // 加载层
        //     shade: [0.6,'#000'],
        //   });
        clearTimeout(sportTimerAc); // 清理体育定时器
        if(!obj){ obj = indexCommonObj.middlecontent ; }
        var loadPageObj = obj.load(url,function (responseText,textStatus,xhr) { // 请求状态：success、error、notmodified、timeout

            window.scrollTo(0,0); // 页面置顶
            if(callback){  callback() ;}
            // console.log(url.indexOf('middle_user_emails'));
            if(url.indexOf('middle_user_emails') =='-1'){ // 当前页面不在站内信页面
                clearInterval(getMessageInt);
            }
            if($.fn.fullpage){
                try {
                    $.fn.fullpage.destroy('all'); // 销毁
                }catch (e) {

                }
            }
            if(url.indexOf('middle_agent') =='-1'){
                $('html,body').css({'overflow':''});
            }else{ // 当前页面是代理加盟
                if($('#fullpage').length>0){
                    indexCommonObj.fullPageScroll($('#fullpage'));
                }
            }

            // if(textStatus=='success'){
            //     layer.close(loadPage);
            // }
        })
        // console.log(loadPageObj)
        // console.log(loadPageObj.context.activeElement)
    },
    // 未登录弹窗提示
    noLoginAlert: function(){
        var str =  '<div class="game_play_all">' +
            '<span class="game_logo"></span>'+
            '<p > 请先登录账号，以便进行真钱投注。 </p>'+
            '<div class="game_center"> <a href="javascript:;" onclick="indexCommonObj.loadMemberLogin()"> 马上登录 </a> ' ;
        str +=  '</div>'+
            '<div class="game_bottom" onclick="indexCommonObj.loadMemberReg()"><span class="user_icon"></span> 没有账号？马上注册</div>'+
            '</div>' ;
        layer.open({
            type: 1
            ,skin: 'layui-layer-gameplay'
            // ,closeBtn:0
            , anim: 2 // 动画风格
            // ,offset: 'auto' //具体配置参考：offset参数项
            ,area: ['560px', '278px']
            ,content: str
            ,shadeClose: true
            ,shade: 0.5
            ,yes: function(){
                layer.closeAll();
            }
        });

    },

    addLiveUrl: function(){ // 增加在线客服链接
        $(indexCommonObj.to_livechat).attr({'href':configbase.onlineserve,'target':'_blank'});
        indexCommonObj.settingBaseWeb();
    },
    // 获取用户 体育，AG彩票余额
    getUserAllPlateMoney: function(uid){
        if(!uid || getAllMonFlage){
            return ;
        }
        getAllMonFlage = true ;
        var url = '/app/member/money/withdrawal_tran_api.php';
        $.ajax({
            type : 'POST',
            url : url ,
            data : {uid:uid,action:'b'},
            dataType : 'json',
            timeout:time_out,
            success:function(res) { // {"err":0,"ag_balance":"10.00","hg_balance":"22,747.14","balance_cp":"100.00"}
                if(res){
                    getAllMonFlage = false ;
                    if(res.data){
                        $(indexCommonObj.user_member_amount).html(res.data.hg_balance).attr('title',res.data.hg_balance); // 体育余额
                        $(indexCommonObj.user_member_lottery_amount).html(res.data.balance_cp).attr('title',res.data.balance_cp); // 彩票余额
                        $(indexCommonObj.user_member_ag_amount).html(res.data.ag_balance).attr('title',res.data.ag_balance); // AG余额
                    }
                    if(res.status != '200'){
                        layer.msg(res.describe,{time:alertTime});
                    }
                }

            },
            error:function(){
                layer.msg('网络异常',{time:alertTime});
                getAllMonFlage = false ;
            }
        });
    },
    // 获取用户体育余额，下注体育用于刷新余额
    getUserMoneyAction :function(uid){
        if(!uid || getMonFlage){
            return false;
        }
        getMonFlage = true ;
        var url = '/app/member/api/reloadCredit.php?v='+Math.random() ;
        $.ajax({
            type : 'POST',
            url : url ,
            data : {uid:uid},
            dataType : 'json',
            timeout:time_out,
            success:function(res) {
                if(res){
                    getMonFlage = false ;
                    if(res.status =='502'){ // 已登出
                        indexCommonObj.logoutAlert() ;
                    }
                    if(res.data){
                        $(indexCommonObj.user_member_amount).html(res.data.monval).attr('title',res.data.monval);
                    }
                    if(res.status != 200){
                        layer.msg(res.describe,{time:alertTime});
                    }
                }

            },
            error:function(){
                layer.msg('网络异常',{time:alertTime});
                getMonFlage = false ;
            }
        });
    },
    // 获取棋牌余额 , 获取AG余额,首次创建AG账号，MG余额，泛亚电竞余额，OG视讯余额，WM电子余额
    getUserQpBanlance: function(uid,type){
        if(!uid){ // 未登录
            return ;
        }
        if(userTestFlag == 1){ // 试玩账号不获取第三方余额
            return false;
        }
        var qpurl ;
        var $objtxt ;
        switch (type){
            case 'ky':
                qpurl = '/app/member/ky/ky_api.php';
                $objtxt = $(indexCommonObj.user_member_ky_amount) ;
                break;
            case 'ly':
                qpurl = '/app/member/lyqp/ly_api.php';
                $objtxt = $(indexCommonObj.user_member_ly_amount) ;
                break;
            case 'ff':
                qpurl = '/app/member/hgqp/hg_api.php';
                $objtxt = $(indexCommonObj.user_member_hg_amount) ;
                break;
            case 'vg':
                qpurl = '/app/member/vgqp/vg_api.php';
                $objtxt = $(indexCommonObj.user_member_vg_amount) ;
                break;
            case 'kl':
                qpurl = '/app/member/klqp/kl_api.php';
                $objtxt = $(indexCommonObj.user_member_kl_amount) ;
                break;
            case 'ag': // 获取AG余额,首次创建AG账号
                qpurl = '/app/member/zrsx/ag_api.php';
                $objtxt = $(indexCommonObj.user_member_ag_amount) ;
                break;
            case 'mg': // 获取MG余额,首次创建MG账号
                qpurl = '/app/member/mg/mg_api.php';
                $objtxt = $(indexCommonObj.user_member_mg_amount) ;
                break;
            case 'cq': // 获取CQ9电子余额,首次创建CQ9账号
                qpurl = '/app/member/cq9/cq9_api.php';
                $objtxt = $(indexCommonObj.user_member_cq_amount) ;
                break;
            case 'avia': // 获取泛亚电竞余额,首次创建泛亚电竞账号
                qpurl = '/app/member/avia/avia_api.php';
                $objtxt = $(indexCommonObj.user_member_avia_amount) ;
                break;
            case 'fire': // 获取雷火电竞余额,首次创建雷火电竞账号
                qpurl = '/app/member/thunfire/fire_api.php';
                $objtxt = $(indexCommonObj.user_member_fire_amount) ;
                break;
            case 'og': // 获取OG视讯余额,首次创建泛亚电竞账号
                qpurl = '/app/member/zrsx/og/og_api.php';
                $objtxt = $(indexCommonObj.user_member_og_amount) ;
                break;
            case 'gmcp':
                qpurl = '/app/member/gmcp/cp_api.php';
                $objtxt = $(indexCommonObj.user_member_third_lottery_amount) ;
                break;
            case 'sc':
                qpurl = '/app/member/sportcenter/sport_api.php';
                $objtxt = $(indexCommonObj.user_member_sc_amount) ;
                break;
            case 'mw':
                qpurl = '/app/member/mw/mw_api.php';
                $objtxt = $(indexCommonObj.user_member_mw_amount) ;
                break;
            case 'fg':
                qpurl = '/app/member/fg/fg_api.php';
                $objtxt = $(indexCommonObj.user_member_fg_amount) ;
                break;
            case 'bbin': // 获取BBIN余额,首次创建BBIN账号
                qpurl = '/app/member/zrsx/bbin/bbin_api.php';
                $objtxt = $(indexCommonObj.user_member_bbin_amount) ;
                break;
        }
        $.ajax({
            type : 'POST',
            url : qpurl ,
            data : {uid:uid,action:'b'},
            dataType : 'json',
            timeout:time_out,
            success:function(res) {
                //console.log(res)
                if(res){
                    if(res.data){
                        if(res.data.hg_balance){
                            $(indexCommonObj.user_member_amount).html(res.data.hg_balance).attr('title',res.data.hg_balance);
                        }
                        if(res.data.gmcp_balance){ // 三方彩票
                            $objtxt.html(res.data.gmcp_balance).attr('title',res.data.gmcp_balance);
                        }
                        if(res.data.sc_balance){ // 皇冠体育
                            $objtxt.html(res.data.sc_balance).attr('title',res.data.sc_balance);
                        }
                        if(res.data.ky_balance){ // 开元
                            $objtxt.html(res.data.ky_balance).attr('title',res.data.ky_balance);
                        }
                        if(res.data.ly_balance){ // 乐游
                            $objtxt.html(res.data.ly_balance).attr('title',res.data.ly_balance);
                        }
                        if(res.data.ff_balance){ // 皇冠
                            $objtxt.html(res.data.ff_balance).attr('title',res.data.ff_balance);
                        }
                        if(res.data.vg_balance){ // vg
                            $objtxt.html(res.data.vg_balance).attr('title',res.data.vg_balance);
                        }
                        if(res.data.kl_balance){ // kl
                            $objtxt.html(res.data.kl_balance).attr('title',res.data.kl_balance);
                        }
                        if(res.data.ag_balance){ // ag
                            $objtxt.html(res.data.ag_balance).attr('title',res.data.ag_balance);
                        }
                        if(res.data.mg_balance){ // mg
                            $objtxt.html(res.data.mg_balance).attr('title',res.data.mg_balance);
                        }
                        if(res.data.cq_balance){ // cq
                            $objtxt.html(res.data.cq_balance).attr('title',res.data.cq_balance);
                        }
                        if (type == 'avia'){
                            if(res.data[0].avia_balance){ // avia
                                $objtxt.html(res.data[0].avia_balance).attr('title',res.data[0].avia_balance);
                            }
                        }
                        if (type == 'og'){
                            if (res.status == 403){
                                layer.msg(res.describe,{time:alertTime});
                            }
                            else{
                                $objtxt.html(res.data[0].og_balance).attr('title',res.data[0].og_balance);
                            }
                        }
                        if (type == 'mw'){
                            if (res.status == 555){
                                layer.msg(res.describe,{time:alertTime});
                            }
                            else{
                                $objtxt.html(res.data[0].mw_balance).attr('title',res.data[0].mw_balance);
                            }
                        }
                        if(res.data.gmcp_balance){ // 三方彩票
                            $objtxt.html(res.data.gmcp_balance).attr('title',res.data.gmcp_balance);
                        }
                        if(res.data.fg_balance){ // fg电子
                            $objtxt.html(res.data.fg_balance).attr('title',res.data.fg_balance);
                        }
                        if(res.data.bbin_balance){ //bbin视讯
                            $objtxt.html(res.data.bbin_balance).attr('title',res.data.bbin_balance);
                        }
                        if(res.data.fire_balance){ //thunFire
                            $objtxt.html(res.data.fire_balance).attr('title',res.data.fire_balance);
                        }
                    }else{
                        $objtxt.html('0.00').attr('title','0.00');
                    }
                    if(type == 'ag'||type == 'mg'||type == 'avia'||type == 'fire'||type == 'og'||type == 'cq'||type == 'mw'||type == 'fg'||type == 'bbin'){ // ag ，mg，avia，og, cq, mw, fg
                        if(res.status != 200){
                            layer.msg(res.describe,{time:alertTime});
                        }
                    }else{ // 棋牌
                        if(res.code != 200){
                            layer.msg(res.message,{time:alertTime});
                        }
                    }

                }

            },
            error:function(){
                layer.msg('网络异常',{time:alertTime});
            }
        });

    },
    // 转账 fm 转出平台，to 转入平台
    transferAccounts: function(plat,p_fm,p_to,mon,tip) {
        if(tip =='yjhs'){ // 仅用于一键回收
            transferFlage = false;
        }
        if(transferFlage || mon<1){ // 余额不足
            return false;
        }
        if(plat =='hg'){
            if( p_fm =='hg'){
                plat = p_to ;
            }else{
                plat = p_fm ;
            }
        }

        var ajaxurl;
        var $objtxt ;
        switch (plat){
            case 'sc':
                ajaxurl = '/app/member/sportcenter/sport_api.php';
                $objtxt = $(indexCommonObj.user_member_sc_amount) ;
                break;
            case 'cp':
                ajaxurl = '/app/member/api/ajaxTran.php';
                break;
            case 'ag':
                ajaxurl = '/app/member/zrsx/ag_api.php';
                break;
            case 'ky':
                ajaxurl = '/app/member/ky/ky_api.php';
                $objtxt = $(indexCommonObj.user_member_ky_amount) ;
                break;
            case 'ly':
                ajaxurl = '/app/member/lyqp/ly_api.php';
                $objtxt = $(indexCommonObj.user_member_ly_amount) ;
                break;
            case 'ff': // 皇冠棋牌
                ajaxurl = '/app/member/hgqp/hg_api.php';
                $objtxt = $(indexCommonObj.user_member_hg_amount) ;
                break;
            case 'vg':
                ajaxurl = '/app/member/vgqp/vg_api.php';
                $objtxt = $(indexCommonObj.user_member_vg_amount) ;
                break;
            case 'kl':
                ajaxurl = '/app/member/klqp/kl_api.php';
                $objtxt = $(indexCommonObj.user_member_kl_amount) ;
                break;
            case 'mg':
                ajaxurl = '/app/member/mg/mg_api.php';
                $objtxt = $(indexCommonObj.user_member_mg_amount) ;
                break;
            case 'cq':
                ajaxurl = '/app/member/cq9/cq9_api.php';
                $objtxt = $(indexCommonObj.user_member_cq_amount) ;
                break;
            case 'avia':
                ajaxurl = '/app/member/avia/avia_api.php';
                $objtxt = $(indexCommonObj.user_member_avia_amount) ;
                break;
            case 'fire':
                ajaxurl = '/app/member/thunfire/fire_api.php';
                $objtxt = $(indexCommonObj.user_member_fire_amount) ;
                break;
            case 'og':
                ajaxurl = '/app/member/zrsx/og/og_api.php';
                $objtxt = $(indexCommonObj.user_member_og_amount) ;
                break;
            case 'gmcp':
                ajaxurl = '/app/member/gmcp/cp_api.php';
                $objtxt = $(indexCommonObj.user_member_third_lottery_amount) ;
                break;
            case 'mw':
                ajaxurl = '/app/member/mw/mw_api.php';
                $objtxt = $(indexCommonObj.user_member_mw_amount) ;
                break;
            case 'fg':
                ajaxurl = '/app/member/fg/fg_api.php';
                $objtxt = $(indexCommonObj.user_member_fg_amount) ;
                break;
            case 'bbin':
                ajaxurl = '/app/member/zrsx/bbin/bbin_api.php';
                $objtxt = $(indexCommonObj.user_member_bbin_amount) ;
                break;
        }
        transferFlage = true ;
        $.ajax({
            type : 'POST',
            url : ajaxurl ,
            data : {
                uid: uid,
                action: 'fundLimitTrans', // 彩票转账
                f: p_fm,
                t: p_to,
                b: mon // 金额
            },
            dataType : 'json',
            timeout:time_out,
            success:function(res) {
                if(res){
                    transferFlage = false ;
                    if((plat == 'mg'||plat == 'avia'||plat == 'fire'||plat == 'og' ||plat == 'cq' ||plat == 'bbin') && res.status != 200){

                        layer.msg(res.describe,{time:alertTime});
                    }else{
                        if(res.data){
                            if(plat =='cp' || plat =='ag'){ // 更新余额
                                indexCommonObj.getUserAllPlateMoney(uid) ;
                            }else { // 棋牌 类
                                if (plat == 'avia') {
                                    if (res.data[0].avia_balance) { // avia
                                        $objtxt.html(res.data[0].avia_balance).attr('title', res.data[0].avia_balance);
                                    }
                                    $(indexCommonObj.user_member_amount).html(res.data[0].hg_balance).attr('title', res.data[0].hg_balance);
                                } else if (plat == 'og') {
                                    if (res.data[0].og_balance) { // og
                                        $objtxt.html(res.data[0].og_balance).attr('title', res.data[0].og_balance);
                                    }
                                    $(indexCommonObj.user_member_amount).html(res.data[0].hg_balance).attr('title', res.data[0].hg_balance);
                                } else if (plat == 'mw') {
                                    if (res.data[0].mw_balance) { // mw
                                        $objtxt.html(res.data[0].mw_balance).attr('title', res.data[0].mw_balance);
                                    }
                                    $(indexCommonObj.user_member_amount).html(res.data[0].hg_balance).attr('title', res.data[0].hg_balance);
                                } else {
                                    $(indexCommonObj.user_member_amount).html(res.data.hg_balance).attr('title', res.data.hg_balance);
                                }
                                if (res.data.ky_balance) { // 开元
                                    $objtxt.html(res.data.ky_balance).attr('title', res.data.ky_balance);
                                }
                                if (res.data.ly_balance) { // 乐游
                                    $objtxt.html(res.data.ly_balance).attr('title', res.data.ly_balance);
                                }
                                if (res.data.ff_balance) { // 皇冠
                                    $objtxt.html(res.data.ff_balance).attr('title', res.data.ff_balance);
                                }
                                if (res.data.vg_balance) { // vg
                                    $objtxt.html(res.data.vg_balance).attr('title', res.data.vg_balance);
                                }
                                if (res.data.kl_balance) { // 快乐棋牌
                                    $objtxt.html(res.data.kl_balance).attr('title', res.data.kl_balance);
                                }
                                if (res.data.mg_balance) { // mg
                                    $objtxt.html(res.data.mg_balance).attr('title', res.data.mg_balance);
                                }
                                if (res.data.cq_balance) { // cq
                                    $objtxt.html(res.data.cq_balance).attr('title', res.data.cq_balance);
                                }
                                if (res.data.gmcp_balance) { // 三方彩票
                                    $objtxt.html(res.data.gmcp_balance).attr('title', res.data.gmcp_balance);
                                }
                                if (res.data.sc_balance) { // 皇冠体育
                                    $objtxt.html(res.data.sc_balance).attr('title', res.data.sc_balance);
                                }
                                if (res.data.fg_balance) { // fg
                                    $objtxt.html(res.data.fg_balance).attr('title', res.data.fg_balance);
                                }
                                if(res.data.bbin_balance){ // bbin
                                    $objtxt.html(res.data.bbin_balance).attr('title',res.data.bbin_balance);
                                }
                                if(res.data.fire_balance){ // thunFire
                                    $objtxt.html(res.data.fire_balance).attr('title',res.data.fire_balance);
                                }
                            }
                            $(indexCommonObj.transfer_input).val('') ; // 清空转账金额

                        }
                    }

                    if(res.code){
                        if(res.code == 200){ // 棋牌
                            layer.msg('转账成功!',{time:alertTime});
                        }else{
                            layer.msg(res.message,{time:alertTime});
                        }
                    }else if(res.status){ // ag 彩票
                        layer.msg(res.describe,{time:alertTime});
                    }



                }

            },
            error:function(){
                transferFlage = false ;
                layer.msg('网络异常',{time:alertTime});
            }
        });

    },
    bannerSwiper :function(lbtime) { // 轮播
        var mySwiper = new Swiper(".swiper-container",{
            autoplay : lbtime?lbtime:4000,
            // lazyLoading : true,
            // lazyLoadingInPrevNext : true,
            // lazyLoadingInPrevNextAmount : 2,
            prevButton:'.swiper-button-prev',
            nextButton:'.swiper-button-next',
            autoHeight: true,
            // 如果需要分页器
            pagination: '.swiper-pagination',
            paginationClickable :true, // 点击分页切换
            autoplayDisableOnInteraction : false, // 点击切换后是否自动播放 (默认true 不播放)
            loop:true
        });
    },

    // 首页轮播处理
    indexBannerAction :function(){
        var $swiperClass = $('.swiper-container .swiper-wrapper');
        $.ajax({
            url:'/app/member/api/indexBannerApi.php',
            type:'POST',
            // async:false ,
            dataType:'json',
            data: {action:'pc'} ,
            timeout:time_out,
            success:function(res){
                var str = '';
                if(res.status==200){ // 成功
                    if(res.data){
                        for(var i=0;i<res.data.length;i++){
                            str +=  '<div class="swiper-slide" >' +
                                '    <a href="javascript:;" class="'+(res.data[i].name.indexOf('promo')>=0?'to_promos':(res.data[i].name.indexOf('lives_upgraded')>=0?'to_lives_upgraded':'to_'+res.data[i].name))+'" data-keys="'+((res.data[i].name.indexOf('promo')>=0 ||res.data[i].name.indexOf('lives_upgraded')>=0)?(res.data[i].name.split('?')[1]?res.data[i].name.split('?')[1]:''):'')+'" data-rtype="r" data-showtype="today" >' +
                                '        <img src="'+res.data[i].img_path+'" class="swiper-lazy" alt="">' +
                                '   </a>' +
                                ' </div>';
                        }
                        $swiperClass.html(str);
                    }
                    indexCommonObj.bannerSwiper();
                }


            },
            error:function () {

            }
        });
    },
    // 首页右下角广告
    rightBottomAd :function (uid) {
        // if($('.index-tg-class').length>0){
        //     $('.index-tg-class').remove();
        // }
        $('.index-tg-class').show();
        $('.hide-index-tg').hide();
        var str = '<div class="index-layer-title" >'+companyname+' 即时帮助</div><span class="title-close" onclick="$(\'.index-tg-class\').hide();$(\'.hide-index-tg\').show()"></span>' +
            '<div class="index-tg">' +
            '        <div class="tg-title">' +
            '            <p>期待您的加入！</p>' +
            '            <span>立即注册成为'+companyname+'会员 <br> 尽享无限精彩 </span>' +
            '        </div>' +
            '        <div class="tg-btn">' +
            '            <a href="javascript:;" class="tg-later" onclick="$(\'.index-tg-class\').hide();$(\'.hide-index-tg\').show()"> 稍后</a>' ;
        if(!uid){
            str +=   ' <a href="javascript:;" class="tg-reg to_memberreg"> 注册</a>' ;
        }

        str += '  <a href="javascript:;" class="tg-online to_livechat"> 联系客服 </a>' +
            '    </div>' +
            '</div>';

        //边缘弹出
        if($('.index-tg-class').length == 0){
            layer.open({
                type: 1
                ,title:''+companyname+' 即时帮助'
                ,skin: 'index-tg-class'
                ,closeBtn:0
                , anim: 2 // 动画风格
                ,offset: 'rb' //具体配置参考：offset参数项
                ,area: ['400px', '170px']
                ,content: str
                // ,btn: '关闭全部'
                //  ,btnAlign: 'c' //按钮居中
                ,shade: 0 //不显示遮罩
                ,yes: function(){
                    layer.closeAll();
                }
            });
        }
        indexCommonObj.addLiveUrl();
    },
    loadIndex :function () { // 加载首页
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_index.php',indexCommonObj.addLiveUrl);
    },
    loadSportsPage :function (gtype,rtype,showtype) { // 加载体育页面 ,今日：rtype: r showtype: today ，滚球 rtype: re   showtype: rb
        if(!gtype){
            gtype='ft';
        }
        indexCommonObj.JqueryReload('tpl/sporttpl/sport_second.php?gtype='+gtype+'&rtype='+rtype+'&showtype='+showtype,indexCommonObj.addLiveUrl);
        // if(sportflushway=='ra686'){ // 6686 水源
        //     indexCommonObj.JqueryReload('tpl/sporttpl/sport_second.php?gtype='+gtype+'&rtype='+rtype+'&showtype='+showtype,indexCommonObj.addLiveUrl);
        // }else{
        //     indexCommonObj.JqueryReload('app/member/FT_index.php?gtype='+gtype+'&rtype='+rtype+'&showtype='+showtype,indexCommonObj.addLiveUrl);
        // }

    },
    loadSportsSecPage :function (gtype,rtype,showtype) { // 加载体育页面 ,今日：rtype: r showtype: today ，滚球 rtype: re   showtype: rb
        if(!gtype){
            gtype='ft';
        }
        clearTimeout(sportTimerAc); // 清理体育定时器
        indexCommonObj.middlecontent.find('.middle_sport_content').load('/tpl/sporttpl/sport_league.php?gtype='+gtype+'&rtype='+rtype+'&showtype='+showtype,function () {

        });
    },
    loadLives :function () { // 加载真人
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_lives.php');
    },
    loadLivesUpgraded :function (type) { // 加载真人升级
        window.open(tplName+'tpl/lobby/middle_lives_upgraded.php?game_Type='+type);
    },
    loadLottery :function () { // 加载彩票
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_lottery.php');
    },
    loadLotteryThird :function (type,gametype){ // 加载第三方彩票 type: 不传即是默认官方，要到信用就传 1 ，gametype：进入到某个彩种，不传即是默认彩种，官方是欢乐生肖(buy/bet/xcqssc)，信用是北京赛车( userthirdplat/login/ssc/76 )
        if(!uid){
            indexCommonObj.noLoginAlert();
            return false;
        }
        var cpload = layer.load(0, { // 加载层
            shade: [0.5,'#000'],
            time:alertTime
        });
        //indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_lottery_third.php')
        top.open(tplName+'tpl/lobby/middle_lottery_third.php?type='+type+'&gametype='+gametype);
    },
    loadFish :function () { // 加载捕鱼
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_fish.php');
    },
    loadChess :function () { // 加载棋牌
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_chess.php');
    },
    loadGamePage :function (type) { // 加载电子
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_games.php?gametype='+type)
    },
    loadDianjing :function (type) { // 加载电竞
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_dianjing.php?gametype='+type)
    },
    loadPromosPage :function (key) { // 加载优惠活动
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_promos.php?prokey='+key)
    },
    loadPromosDetailsPage :function (keys,title,type,api,flag) { // 加载优惠活动详情
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_promos_details.php?key='+keys+'&title='+title+'&type='+type+'&api='+api+'&flag='+flag)
    },
    loadAgentRegPage :function (keys) { // 加载代理注册
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_agents.php?key='+keys,indexCommonObj.addLiveUrl)
    },
    loadAboutUsPage :function (keys) { // 加载关于我们
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_aboutus.php?key='+keys,indexCommonObj.settingBaseWeb)
    },
    loadStagePresencePage :function () { // 加载风采页面
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_stage_presence.php')
    },
    loadUserPlatformPage :function () { // 加载转账页面
        if(userAgents =='demoguest' || userTestFlag=='1'){ // 测试账号
            // if(top.body){
            //     body.body_var.bodyVarAlert(testalert) ;
            // }
            layer.msg(testalert,{time:alertTime});
            return ;
        }
        if(ucentertip){ // 太阳城等不一样
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_user_platform.php','',indexCommonObj.middle_usercenter_content)
        }else{
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_user_platform.php')
        }

    },
    loadDepositPage :function () { // 加载存款页面
        if(userAgents =='demoguest' || userTestFlag=='1'){ // 测试账号
            // if(top.body){
            //     body.body_var.bodyVarAlert(testalert) ;
            // }
            layer.msg(testalert,{time:alertTime});
            return ;
        }
        if(!uid){
            layer.msg(logalert,{time:alertTime});
            return false;
        }
        if(ucentertip){ // 太阳城等不一样
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/onlinepay/pay_type.php','',indexCommonObj.middle_usercenter_content)
        }else{
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/onlinepay/pay_type.php')
        }

    },
    loadWithdrawPage :function () { // 加载提款页面
        if(userAgents =='demoguest' || userTestFlag=='1'){ // 测试账号
            // if(top.body){
            //     body.body_var.bodyVarAlert(testalert) ;
            // }
            layer.msg(testalert,{time:alertTime});
            return ;
        }
        if(ucentertip){ // 太阳城等不一样
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/money/withdrawal.php','',indexCommonObj.middle_usercenter_content)
        }else{
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/money/withdrawal.php')
        }

    },
    loadDownloadAppPage :function () { // 加载APP下载页面
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_appdownload.php',indexCommonObj.settingBaseWeb)
    },
    loadUserCenterPage :function () { // 加载会员中心页面
        if(ucentertip){ // 太阳城等不一样
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_user_center.php','',indexCommonObj.middle_usercenter_content)
        }else{
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_user_center.php')
        }

    },
    loadUserCenterAllPage :function (type) { // 加载会员中心总页面
        if(type =='deposit' || type =='withdraw' || type =='tranfer'){
            if(userAgents =='demoguest' || userTestFlag=='1'){ // 测试账号
                // if(top.body){
                //     body.body_var.bodyVarAlert(testalert) ;
                // }
                layer.msg(testalert,{time:alertTime});
                return ;
            }
        }
        if(!uid){
            layer.msg(logalert,{time:alertTime});
            return false;
        }

        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_user_all_center.php?type='+type)
    },

    loadUserAccountPage :function () { // 加载账户记录页面
        if(ucentertip){ // 太阳城等不一样
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_user_account.php','',indexCommonObj.middle_usercenter_content)
        }else{
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_user_account.php')
        }

    },
    loadForgetPassword :function () { // 加载忘记密码页面
        if(tplName =='views/8msport/'){ // 8M
            $('.show-top-login-box,.show_login_btn').click();
            layer.closeAll();
        }else{
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_forget_password.php','',indexCommonObj.middlecontent)
        }

    },
    loadUserMails :function () { // 加载站内信页面
        if(ucentertip){ // 太阳城等不一样
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_user_emails.php','',indexCommonObj.middle_usercenter_content)
        }else{
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_user_emails.php')
        }

    },
    loadLineSense: function() {
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_line_sense.php', indexCommonObj.addLiveUrl)
    },
    loadGuestPhone :function () { // 加载试玩输入手机号页面
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_guest_phone.php')
    },
    loadChangeBankPage :function () { // 加载更换银行卡页面
        if(userAgents =='demoguest' || userTestFlag=='1'){ // 测试账号
            // if(top.body){
            //     body.body_var.bodyVarAlert(testalert) ;
            // }
            layer.msg(testalert,{time:alertTime});
            return ;
        }
        var churl = tplName+'tpl/lobby/middle_change_bank.php';
        layer.open({
            type: 2,
            area: ['400px', '430px'],
            title: '添加银行卡',
            shadeClose: true, //点击遮罩关闭
            content:churl
        });

    },
    loadSuggestionPage :function (){
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_suggestion.php')
    },
    // 提款前判定是否绑定真实姓名和取款密码
    ifBindRealName: function(alias) {
        var str = '<form class="BankCardInfo input-group clearfix flex-col">' ;

        str +=  '        <div class="form-group input_same">' +
            '            <div class="forValidations">' +
            '                <input type="text"  class="realname " value="'+ alias +'" minlength="2" maxlength="20" '+ (alias?'readonly':'') +'>' +
            '                <label class="titleLabel " >真实姓名  <span class="subTitle">(请设置您的真实姓名)</span></label>' +
            '            </div>' +
            '        </div>' ;

        str += '        <div class="form-group input_same">' +
            '            <div class="forValidations">' +
            '                <input type="password" onkeyup="this.value=this.value.replace(/\\D/g,\'\')" class="paypassword" minlength="6" maxlength="6" >' +
            '                <label class="titleLabel">提款密码  <span class="subTitle">(请输入六位数字)</span></label>' +
            '            </div>' +
            '        </div>' +
            '        <div class="form-group input_same">' +
            '            <input type="password" class="con_paypassword" minlength="6" maxlength="6">' +
            '            <label class="titleLabel" for="bankaddress"> 确认提款密码 </label>' +
            '        </div>' +
            '    </form>' +
            '    <div class="modalFooter"><button type="button" class="btn-add bind_real_submit">确认提交</button></div>' ;

        layer.open({
            type: 1,
            //closeBtn:0 , // 0 不要关闭按钮
            area: ['400px', '350px'],
            title: '绑定个人资料',
            shadeClose: false, //点击遮罩关闭
            content:str
        });

        // 绑定提交
        var bindRealnameflage = false ;
        $('body').off('click','.bind_real_submit').on('click','.bind_real_submit',function () {
            if(bindRealnameflage){ return ;}

            var realname = $('.realname').val();
            var paypassword = $('.paypassword').val();
            var con_paypassword = $('.con_paypassword').val();
            if(!realname){
                layer.msg('请输入真实姓名!',{time:alertTime});
                return ;
            }
            if(paypassword.length !=6 ){
                layer.msg('请输入6位纯数字提款密码!',{time:alertTime});
                return ;
            }
            if(paypassword != con_paypassword){
                layer.msg('两次密码不一致!',{time:alertTime});
                return ;
            }
            var up_data = {
                realname : realname,
                paypassword : paypassword,
            }
            var url = '/app/member/api/updateRealName.php';
            bindRealnameflage = true ;
            $.ajax({
                type: 'POST',
                url: url,
                data: up_data,
                dataType: 'json',
                timeout:time_out,
                success: function (res) {
                    if(res){
                        layer.msg(res.describe,{time:alertTime});
                        setTimeout(function () {
                            bindRealnameflage = false ;
                            layer.closeAll();
                            $('#realName').val(res.data.realName);
                            $('#withdraw-pw').val('******');
                            $('.change_user_details').remove();
                            // if(res.status =='200'){
                            //     window.location.href = '/';
                            // }
                        },alertTime)

                    }
                },
                error: function (res) {
                    bindRealnameflage = false ;
                    layer.msg('数据更新失败，请稍后再试!',{time:alertTime});

                }
            });

        })


    },

    loadMemberTestPlayLogin: function (type){ // 试玩登录
        var actionurl = "/app/member/login.php" ;
        if(type == '1'){
            indexCommonObj.loadGuestPhone();


        }else{
            $.ajax({
                type : 'POST',
                url : actionurl ,
                data : {demoplay:'Yes'},
                dataType : 'json',
                timeout:time_out,
                success:function(res) {
                    if(res){
                        layer.msg(res.describe,{time:alertTime});
                        if(res.status ==200){
                            window.location.href = '/' ;
                        }
                    }

                },
                error:function(){
                    layer.msg('稍后请重试',{time:alertTime});
                }
            });
        }

    },

    loadMemberLogin :function () { // 加载登录
        layer.closeAll();
        if(tplName =='views/8msport/'){ // 8M
            $('.show-top-login-box,.show_login_btn').click();
        }else{
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_login_sec.php',indexCommonObj.addLiveUrl);
        }
        $('body').find('.title-close').click();
        // top.layerLogin = layer.open({
        //     type: 2,
        //     skin: 'layui-layer-login', //样式类名
        //     title: '快速登录',
        //     shadeClose: true,
        //     shade:0.5,
        //     area: ['400px', '337px'],
        //     content: tplName+'/tpl/lobby/middle_login.php' // url
        // });
    },

    loadMemberReg :function (val) { // 加载注册
        if(!val){
            val='';
        }
        layer.closeAll();
        if(tplName =='views/jinsha/'){ // 弹窗
            if(top.body){
                body.body_var.loadMemberRegSport() ;
                return ;
            }
            layer.open({
                type: 2,
                skin: 'layui-layer-login', //样式类名
                title: '快速注册',
                shadeClose: false,
                shade:0.5,
                area: ['700px', '600px'],
                content: tplName+'/tpl/lobby/middle_member_reg.php?intr='+val // url
            });
        }else if(tplName =='views/8msport/'){ // 8M
            $('.show-top-login-box,.show_reg_btn').click();
        }else{
            indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_member_reg_sec.php?intr='+val,indexCommonObj.addLiveUrl)
        }
        $('body').find('.title-close').click();
    },

    loadTestPhone :function (val) { // 加载试玩填写手机号页面
        indexCommonObj.JqueryReload(tplName+'tpl/lobby/middle_guest_phone.php')
    },
    // 会员注册的时候获取域名后面的参数, 域名参数 ?promo 优惠活动，?agent 跳转到代理注册
    getIntroducer :function () {
        var url = window.location.search ;
        if (url.indexOf("?") != -1) {
            url = url.split('?') ;
            var intr = url[1].toLowerCase() ;
            var intrarr = intr.split('=');
            var flag = intrarr[0] ;
            // console.log(intr);
            // console.log(flag);
            // console.log(intrarr[1]);
            switch (flag){
                case 'intr': // 跳转到注册页
                    if(intrarr[1] && !uid){ // 不能为空
                        indexCommonObj.loadMemberReg(intrarr[1]) ;
                        return flag;
                    }
                    break;
                case 'login': // 跳转到登录页
                    indexCommonObj.loadMemberLogin() ;
                    return flag;
                    break;
                case 'changepwd': // 跳转到修改密码页
                    indexCommonObj.loadForgetPassword() ;
                    return flag;
                    break;
                case 'promo': // 跳转到优惠活动页
                    indexCommonObj.loadPromosPage() ;
                    return flag;
                    break;
                case 'live': // 跳转到真人视讯页
                    indexCommonObj.loadLives() ;
                    return flag;
                    break;
                case 'games': // 跳转到电子游戏页
                    indexCommonObj.loadGamePage() ;
                    return flag;
                    break;
                case 'chess': // 跳转到棋牌页
                    indexCommonObj.loadChess() ;
                    return flag;
                    break;
                case 'agent': // 跳转到代理注册页
                    indexCommonObj.loadAgentRegPage('') ;
                    return flag;
                    break;
                case 'app': // 跳转到app下载页
                    indexCommonObj.loadDownloadAppPage() ;
                    return flag;
                    break;

            }

        }

    },

    loadPageAction :function (uid) {
        $(document).off('click',indexCommonObj.to_index).on('click',indexCommonObj.to_index,function () { // 首页
            indexCommonObj.changeTabClass(this) ;
            if(localStorage.getItem('pageTabType')=='index'){
                return ;
            }

            indexCommonObj.loadIndex() ;
            localStorage.setItem('pageTabType','index');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_sports).on('click',indexCommonObj.to_sports,function () { // 体育页面
            indexCommonObj.changeTabClass(this) ;
            var gtype = $(this).attr('data-gtype') || '';
            var rtype = $(this).attr('data-rtype') || '';
            var showtype = $(this).attr('data-showtype') || '';
            indexCommonObj.loadSportsPage(gtype,rtype,showtype) ;
            localStorage.setItem('pageTabType','sport');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_sec_sports).on('click',indexCommonObj.to_sec_sports,function () { // 体育页面
            $(this).addClass('on').siblings().removeClass('on') ;
            var gtype = $(this).attr('data-gtype') || '';
            var rtype = $(this).attr('data-rtype') || '';
            var showtype = $(this).attr('data-showtype') || '';
            indexCommonObj.loadSportsSecPage(gtype,rtype,showtype) ;
        });

        $(document).off('click',indexCommonObj.to_lives).on('click',indexCommonObj.to_lives,function () { // 真人
            indexCommonObj.changeTabClass(this) ;
            if(localStorage.getItem('pageTabType')=='live'){
                return ;
            }

            indexCommonObj.loadLives() ;
            localStorage.setItem('pageTabType','live');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_lives_upgraded).on('click',indexCommonObj.to_lives_upgraded,function () { // 真人 升级
            var type = $(this).attr('data-keys')?$(this).attr('data-keys'):'';
            if(!uid){
                layer.msg(logalert,{time:alertTime});
                return false;
            }
            indexCommonObj.changeTabClass(this) ;
            indexCommonObj.loadLivesUpgraded(type) ;
            localStorage.setItem('pageTabType','liveUpgraded');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_lotterys).on('click',indexCommonObj.to_lotterys,function () { // 彩票
            indexCommonObj.changeTabClass(this) ;
            if(localStorage.getItem('pageTabType')=='lottery'){
                return ;
            }

            indexCommonObj.loadLottery() ;
            localStorage.setItem('pageTabType','lottery');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_lotterys_third).on('click',indexCommonObj.to_lotterys_third,function () { // 第三方彩票
            var type = $(this).attr('data-to')?$(this).attr('data-to'):'';
            var gametype = $(this).attr('data-gametype')?$(this).attr('data-gametype'):'';
            indexCommonObj.changeTabClass(this) ;
            indexCommonObj.loadLotteryThird(type,gametype) ;
        });
        $(document).off('click',indexCommonObj.to_fish).on('click',indexCommonObj.to_fish,function () { // 捕鱼
            indexCommonObj.changeTabClass(this) ;
            if(localStorage.getItem('pageTabType')=='fish'){
                return ;
            }

            indexCommonObj.loadFish() ;
            localStorage.setItem('pageTabType','fish');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_chess).on('click',indexCommonObj.to_chess,function () { // 棋牌
            indexCommonObj.changeTabClass(this) ;
            if(localStorage.getItem('pageTabType')=='chess'){
                return ;
            }

            indexCommonObj.loadChess() ;
            localStorage.setItem('pageTabType','chess');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_dianjing).on('click',indexCommonObj.to_dianjing,function () { // 电竞
            indexCommonObj.changeTabClass(this) ;
            var type = $(this).attr('data-type') || ''; // 默认不传就是fydj , lh 传lhdj
            if(localStorage.getItem('pageTabType')=='dianjing'){
                return ;
            }

            indexCommonObj.loadDianjing(type) ;
            localStorage.setItem('pageTabType','dianjing');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_games).on('click',indexCommonObj.to_games,function () { // 电子
            indexCommonObj.changeTabClass(this) ;
            var type = $(this).attr('data-type') || ''; // 默认不传就是ag , mg 传mg
            if(localStorage.getItem('pageTabType')=='game'){
                return ;
            }

            indexCommonObj.loadGamePage(type) ;
            localStorage.setItem('pageTabType','game');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_promos).on('click',indexCommonObj.to_promos,function () { // 优惠活动
            var keys = $(this).attr('data-keys') ;
            indexCommonObj.changeTabClass(this) ;
            if(localStorage.getItem('pageTabType')=='promo'){
                return ;
            }

            indexCommonObj.loadPromosPage(keys) ;
            localStorage.setItem('pageTabType','promo');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_promos_details).on('click',indexCommonObj.to_promos_details,function () { // 优惠活动详情
            var keys = $(this).attr('data-keys') ;
            var title = $(this).attr('data-title') ;
            var type = $(this).attr('data-type') ;
            var api = $(this).attr('data-api') ;
            var flag = $(this).attr('data-flag') ;
            indexCommonObj.loadPromosDetailsPage(keys,encodeURI(title),type,api,flag) ;
            localStorage.setItem('pageTabType','promodetail');// 记住当前页面标签
        });

        $(document).off('click',indexCommonObj.to_memberlogin).on('click',indexCommonObj.to_memberlogin,function () { // 登录
            if(localStorage.getItem('pageTabType')=='login'){
                return ;
            }
            indexCommonObj.loadMemberLogin() ;
            localStorage.setItem('pageTabType','login');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_testplaylogin).on('click',indexCommonObj.to_testplaylogin,function () { // 试玩登录
            indexCommonObj.loadMemberTestPlayLogin(guest_login_phone_turn) ;
            localStorage.setItem('pageTabType','testplay');// 记住当前页面标签
        });

        $(document).off('click',indexCommonObj.to_memberreg).on('click',indexCommonObj.to_memberreg,function () { // 注册
            indexCommonObj.loadMemberReg() ;
            localStorage.setItem('pageTabType','reg');// 记住当前页面标签
        });

        $(document).off('click',indexCommonObj.to_testphone).on('click',indexCommonObj.to_testphone,function () { // 试玩填写手机号登录
            indexCommonObj.loadTestPhone() ;
            localStorage.setItem('pageTabType','testphone');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_agentreg).on('click',indexCommonObj.to_agentreg,function () { // 代理加盟
            var keys = $(this).attr('data-index') ;
            indexCommonObj.changeTabClass(this) ;
            if(localStorage.getItem('pageTabType')=='agentreg'){
                return ;
            }

            indexCommonObj.loadAgentRegPage(keys) ;
            localStorage.setItem('pageTabType','agentreg');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_aboutus).on('click',indexCommonObj.to_aboutus,function () { // 关于我们
            var index = $(this).attr('data-index');
            indexCommonObj.loadAboutUsPage(index) ;
            localStorage.setItem('pageTabType','aboutus');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_presence).on('click',indexCommonObj.to_presence,function () { // 风采页面
            indexCommonObj.changeTabClass(this) ;
            if(localStorage.getItem('pageTabType')=='presence'){
                return ;
            }

            indexCommonObj.loadStagePresencePage() ;
            localStorage.setItem('pageTabType','presence');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_platform_tranfer).on('click',indexCommonObj.to_platform_tranfer,function () { // 转账
            $(this).parents('.font_ch').removeClass('index_moveup');
            indexCommonObj.loadUserPlatformPage() ;
            localStorage.setItem('pageTabType','platform');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_deposit).on('click',indexCommonObj.to_deposit,function () { // 存款页面
            $(this).parents('.font_ch').removeClass('index_moveup');
            indexCommonObj.loadDepositPage() ;
            localStorage.setItem('pageTabType','deposit');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_withdraw).on('click',indexCommonObj.to_withdraw,function () { // 提款页面
            $(this).parents('.font_ch').removeClass('index_moveup');
            indexCommonObj.loadWithdrawPage() ;
            localStorage.setItem('pageTabType','withdraw');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_downloadapp).on('click',indexCommonObj.to_downloadapp,function () { // APP下载页面
            indexCommonObj.changeTabClass(this) ;
            if(localStorage.getItem('pageTabType')=='downloadapp'){
                return ;
            }
            indexCommonObj.loadDownloadAppPage() ;
            localStorage.setItem('pageTabType','downloadapp');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_usercenter).on('click',indexCommonObj.to_usercenter,function () { // 会员中心页面
            $(this).parents('.font_ch').removeClass('index_moveup');
            indexCommonObj.loadUserCenterPage() ;
            localStorage.setItem('pageTabType','usercenter');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_usercenter_content).on('click',indexCommonObj.to_usercenter_content,function () { // 会员中心总页面
            var type = $(this).attr('data-to');
            $(this).parents('.font_ch').removeClass('index_moveup');
            indexCommonObj.loadUserCenterAllPage(type) ;
            localStorage.setItem('pageTabType','usercenterall');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.change_user_bank).on('click',indexCommonObj.change_user_bank,function () { // 会员绑定银行页面
            indexCommonObj.loadChangeBankPage() ;
            localStorage.setItem('pageTabType','changebank');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_suggestion).on('click',indexCommonObj.to_suggestion,function () { // 意见/投诉
            indexCommonObj.changeTabClass(this) ;
            if(localStorage.getItem('pageTabType')=='suggestion'){
                return ;
            }
            indexCommonObj.loadSuggestionPage() ;
            localStorage.setItem('pageTabType','suggestion');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_userbetaccount).on('click',indexCommonObj.to_userbetaccount,function () { // 会员帐户记录页面
            if(!uid){
                layer.msg(logalert,{time:alertTime});
                return ;
            }
            $(this).parents('.font_ch').removeClass('index_moveup');
            layer.close(top.alertcon);
            indexCommonObj.loadUserAccountPage() ;
            localStorage.setItem('pageTabType','account');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_forgetpassword).on('click',indexCommonObj.to_forgetpassword,function () { // 忘记密码页面
            if(localStorage.getItem('pageTabType')=='forgetpwd'){
                return ;
            }
            parent.layer.close(top.layerLogin);
            indexCommonObj.loadForgetPassword() ;
            localStorage.setItem('pageTabType','forgetpwd');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_user_email).on('click',indexCommonObj.to_user_email,function () { // 会员帐户记录页面
            $('.font_ch').removeClass('index_moveup');
            indexCommonObj.loadUserMails() ;
            localStorage.setItem('pageTabType','usermail');// 记住当前页面标签
        });
        $(document).off('click',indexCommonObj.to_line_sense).on('click',indexCommonObj.to_line_sense,function () { // 线路检测
            if(localStorage.getItem('pageTabType')=='linesense'){
                return ;
            }
            indexCommonObj.loadLineSense() ;
            localStorage.setItem('pageTabType','linesense');// 记住当前页面标签
        });


    },
    // 导航栏 切换类
    changeTabClass :function(obj){
        var $Objtip ;
        if($(obj).parent('.cms_top-menu').length>0){
            $Objtip = $(obj).parent('.cms_top-menu');
        }else if($(obj).parent('li').length>0){
            $Objtip = $(obj).parent('li');
        }
        if($Objtip){
            $Objtip.addClass('active').siblings().removeClass('active') ;
        }
    },
    // 账号登出提示
    logoutAlert :function(){
        window.location.href = tplName+'tpl/lobby/middle_logout.php';
    },
    // 打开游戏 uid
    openGameCommon :function(obj,uid,url) { // type: chess 打开棋牌游戏
        if(!uid){
            if(top.body){
                body.body_var.bodyVarAlert() ;
                return false;
            }
            // layer.open({
            //     type: 1,
            //     skin: 'layui-layer-alert', //样式类名
            //     closeBtn: 0, //不显示关闭按钮
            //     anim: 2,
            //     time: alertTime , // 消失时间
            //     shadeClose: true, //开启遮罩关闭
            //     content: '<div class="layer_padding">请先登录</div>',
            // });
            var test_url = $(obj).next('.qp_testplay_btn').attr('onclick'); // 棋牌试玩
            var test_p = $(obj).attr('data-testplay'); // 只有开元和乐游有这个试玩

            var str =  '<div class="game_play_all">' +
                '<span class="game_logo"></span>'+
                '<p > 请先登录账号，以便进行真钱投注。 </p>'+
                '<div class="game_center"> <a href="javascript:;" class="to_memberlogin"> 马上登录 </a> ' ;
            if(test_p){
                str +=  '<a href="javascript:;" onclick="('+ test_url +')"> 我要试玩 </a>  ' ;
            }

            str +=  '</div>'+
                '<div class="to_memberreg game_bottom"><span class="user_icon"></span> 没有账号？马上注册</div>'+
                '</div>' ;
            layer.open({
                type: 1
                ,skin: 'layui-layer-gameplay'
                // ,closeBtn:0
                , anim: 2 // 动画风格
                // ,offset: 'auto' //具体配置参考：offset参数项
                ,area: ['560px', '278px']
                ,content: str
                ,shadeClose: true
                ,shade: 0.5
                ,yes: function(){
                    layer.closeAll();
                }
            });

            return false ;
        }
        // og(og_api), mg ,mw,cq9,fg(getLaunchGameUrl) ,ky ,lyqp ,vg(vgqp),hg(hgqp),klqp, avia,fire 试玩账号不支持进入
        if(userTestFlag ==1){
            if(url.indexOf('og_api')>0 || url.indexOf('mg')>0 || url.indexOf('mw')>0 || url.indexOf('cq9')>0 || url.indexOf('getLaunchGameUrl')>0 || url.indexOf('ky/index.php')>0 || url.indexOf('lyqp')>0 || (url.indexOf('hgqp')>0) || (url.indexOf('vgqp')>0 ) || (url.indexOf('klqp')>0 ) || (url.indexOf('fire')>0 ) ){
                layer.msg(testalert,{time:alertTime});
                return false;
            }
        }
        if(tplName=='views/0086dj/' && ( url.indexOf('avia')>0 || url.indexOf('fire')>0 )){ // 电竞
            var g_type = (url.indexOf('avia')>0?'fydj':'lhdj');
            indexCommonObj.changeTabClass(obj) ;
            indexCommonObj.loadDianjing(g_type) ;
        }else{
            window.open(url) ;
        }

    },
    /*
     ** randomWord 产生任意长度随机字母数字组合
     ** randomFlag-是否任意长度 min-任意长度最小位[固定位数] max-任意长度最大位
     ** 生成3-32位随机串：randomWord(true, 3, 32)
     **  生成43位随机串：randomWord(false, 43)
     * randomWord(false, 32) ; // 下注随机数
     */
    randomWord :function(randomFlag, min, max){
        var str = "",
            range = min,
            arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        // 随机产生
        if(randomFlag){
            range = Math.round(Math.random() * (max-min)) + min;
        }
        for(var i=0; i<range; i++){
            pos = Math.round(Math.random() * (arr.length-1));
            str += arr[pos];
        }
        return str;
    },
    fullPageScroll :function (sel) {
        /*屏幕滚动*/
        sel.fullpage({
            'navigation': true,
            afterLoad: function (anchorLink, index) {
                if (index == 2) {
                    $('#section2 .section-header').addClass('active');
                    $('#section2 .section-content').addClass('active');

                }
                if (index == 3) {
                    $('#section3 .section-header').addClass('active');
                    $('#section3 .section-content').addClass('active');
                }
                if (index == 4) {

                    $('#section4 .section-header').addClass('active');
                    $('#section4 .section-content').addClass('active');
                }
            },
            onLeave: function (index, direction) {
                if (index == 2) {
                    $('#section2 .section-header').removeClass('active');
                    $('#section2 .section-content').removeClass('active');

                }
                if (index == 3) {
                    $('#section3 .section-header').removeClass('active');
                    $('#section3 .section-content').removeClass('active');
                }
                if (index == 4) {

                    $('#section4 .section-header').removeClass('active');
                    $('#section4 .section-content').removeClass('active');
                }
            }

        });
    },
    getNewsRecommend :function (type,id,page) {
        var url = '/app/member/api/articleApi.php' ;
        var str = '';
        var $big_title = $('.big_title');
        var $small_title = $('.small_title');
        var $gamelist = $('.game-list');
        var $stage_presence_content = $('.stage_presence_content'); // 风采
        var $presence_pagination = $('.presence_pagination'); // 风采

        $.ajax({
            type : 'POST',
            url : url ,
            data : {action:type,id:id,page:page}, // thumb : 首页缩略图，内容页: content&id=1 ,太阳城分彩分页 10条/页  action=list&page=0
            dataType : 'json',
            timeout:time_out,
            success:function(res) {
                if(res.data){
                    switch (type){
                        case 'thumb': // 首页获取推荐新闻
                            if(res.data[0]){
                                $big_title.html(res.data[0].title).parent('.news_title').attr('data-id',res.data[0].id);
                                $small_title.html(res.data[0].subtitle);
                            }

                            for(var i=0;i<res.data.length;i++){
                                str +='<li class="show_news_content" data-id="'+ res.data[i].id +'"><a href="javascript:;"><img src="'+ res.data[i].cover +'"></a></li>';
                            }
                            $gamelist.html(str);
                            break;
                        case 'content': // 详情
                            layer.open({
                                type: 1,
                                title: res.data.title
                                ,skin: 'layui-layer-news'
                                // ,closeBtn:0
                                , anim: 2 // 动画风格
                                // ,offset: 'auto' //具体配置参考：offset参数项
                                ,area: ['1000px', '600px']
                                ,content: res.data.content
                                ,shadeClose: true
                                ,shade:0.5
                                ,yes: function(){
                                    layer.closeAll();
                                }
                            });
                            break;
                        case 'list': // 风采页面列表
                            if(page == 0){
                                $presence_pagination.Page({
                                    totalPages:res.data.page_count,
                                    activeClass: 'active' ,//active类
                                    hasPrv: false,//是否有前一页按钮
                                    hasNext: false,//是否有后一页按钮
                                    callBack:function(pg){
                                        //console.log(pg);
                                    }
                                });
                                // 页码切换
                                $presence_pagination.on('click','.page_click',function () {
                                    if($(this).hasClass('none')){ // 只有一页
                                        return false;
                                    }
                                    var thispage = Number($(this).attr('topage'))-1 ;
                                    indexCommonObj.getNewsRecommend('list','',thispage);
                                })

                            }
                            if(res.data.list){
                                for(var i=0;i<res.data.list.length;i++){
                                    str +='<div class="show_news_content timebox grid-item grid-item-height new" data-id="'+ res.data.list[i].id +'">' +
                                        '                        <div class="timeMain">' +
                                        '                            <div class="imgBox url"><img class="img" src="'+ res.data.list[i].cover +'">' +
                                        '                            </div>' +
                                        '                            <h3 class="url">'+ res.data.list[i].title +'</h3>' +
                                        '                            <p>'+ res.data.list[i].subtitle +'</p></div>' +
                                        '                    </div>';
                                }
                                $stage_presence_content.html(str);
                            }

                            break;
                    }


                }

            },
            error:function(){

            }
        });
    },

    getUserBetDetail : function () {
        var user_bet = {};
        $.ajax({
            url: '/app/member/api/user_bet.php?appRefer=4',
            type: 'GET',
            dataType: 'json',
            async: false,
            timeout:time_out,
            success: function (response) {
                if(response.status == '200'){
                    user_bet = response.data;
                    $(indexCommonObj.owe_bet).html(user_bet.owe_bet);
                    $(indexCommonObj.total_bet).html(user_bet.total_bet);
                } else {
                    layer.msg(response.describe, {time:alertTime});
                }
            }
        });

        var str = '<div id="user_bet_list" class="static-content-user">' +
            '<table class="detail_table">' +
            '<thead><tr class="st-hdr"><th width="50%">类型</th><th width="50%">打码量</th></tr></thead>' +
            '<tbody class="bet_record_content">';
        $.each(user_bet.bet_list, function (i,v) {
            str += '<tr class="wagers"><td><p>' + v.msg + '</p></td><td><p>' + v.value + '</p></td></tr>';
        });
        str += '</tbody></table></div>';

        $(indexCommonObj.bet_detail).on('click', function () {
            layer.open({
                type: 1,
                title: '打码量列表',
                moveType: 1,
                area: ['400px', '450px'],
                scrollbars: 1,
                shadeClose: true, //点击遮罩关闭
                content: str
            });
        });
    }

}
