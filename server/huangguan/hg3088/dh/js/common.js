
var tohtml ={ // 定义容器
    'ess_service_phone':'.ess_service_phone' , // 24小时客服电话
    'phl_service_phone':'.phl_service_phone' , // 投诉电话
    'sz_service_email':'.sz_service_email' , // 邮箱
    'qq_service_number':'.qq_service_number' , // 客服qq
    'agent_qq_service_number':'.agent_qq_service_number' , // 代理客服qq
    'wechat_service_number':'.wechat_service_number' , // 微信客服
    'yj_backup_web_url':'.yj_backup_web_url' , // 易记域名
    'gf_new_web_url':'.gf_new_web_url' , // 官方导航
    'app_download_page':'.app_download_page' , // APP
    'sy_content':'.sy_content' , //中间容器
    'member_login':'.member_login' , // 会员登录
    'agent_login':'.agent_login' , // 代理登录
    'to_service':'.to_service' , // 代理登录
    'to_index':'.to_index' , //跳转到首页
    'to_sports':'.to_sports' , //跳转到体育页面
    'to_lives':'.to_lives' , //跳转到真人页面
    'to_games':'.to_games' , //跳转到电子游戏页面
    'to_lotterys':'.to_lotterys' , //跳转到彩票页面
    'to_promos':'.to_promos' , //跳转到优惠活动页面
    'to_proxy':'.to_proxy' , //跳转到代理加盟页面
    'to_agentreg':'.to_agentreg' , //跳转到代理加盟注册页面
    'to_memberreg':'.to_memberreg' , //跳转到会员注册页面
    'to_terms':'.to_terms' , //跳转到使用条款页面
    'to_withdrawals':'.to_withdrawals' , //跳转到取款帮助页面
    'to_save':'.to_save' , //跳转到存款帮助页面
    'to_problem':'.to_problem' , //跳转到常见问题页面
    'to_aboutus':'.to_aboutus' , //跳转到关于我们页面
    'to_contactus':'.to_contactus' , //跳转到联系我们页面
    'to_company':'.to_company' , //跳转到联系我们页面
    'to_rule':'.to_rule' , //跳转到联系我们页面
    'to_responsibility':'.to_responsibility' , //跳转到负责任博彩页面
    'to_downloadapp':'.to_downloadapp' , //跳转到app 下载页面
    'to_chess':'.to_chess' , //跳转到棋牌页面
};
// 基本设置
function settingBaseWeb(){
    $(tohtml.ess_service_phone).text(web_config.service_phone_24)
    $(tohtml.phl_service_phone).text(web_config.service_phone_phl);
    $(tohtml.sz_service_email).text(web_config.service_email);
    $(tohtml.qq_service_number).text(web_config.service_qq);
    $(tohtml.agent_qq_service_number).text(web_config.agents_service_qq);
    $(tohtml.wechat_service_number).text(web_config.service_wechat);
    $(tohtml.yj_backup_web_url).text(web_config.backup_web_url).attr('href',web_config.backup_web_url);
    $(tohtml.app_download_page).text(web_config.download_app_page);
    $(tohtml.gf_new_web_url).text(web_config.new_web_url).attr('href',web_config.new_web_url);
}
/**
 * 解析URL参数
 */
function getStrParam() {
    var url = location.search; //获取url中"?"符后的字串
    var param = {};
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for(var i = 0; i < strs.length; i ++) {
            param[strs[i].split("=")[0]]= decodeURIComponent(strs[i].split("=")[1]);
        }
    }
    return param;
}

// 获取 id 选择器
function _$(i){
    return document.getElementById(i);
}
/* 首次初始化函数  */
function setFirstAction() {
    // var len = ulrarr.length ;
    // var num = Math.floor(Math.random() * len) ; // 随机生成整数
    // $(tohtml.member_login).attr('href','http://www.'+ulrarr[num]) ; // 随机配置会员登录域名
    // $(tohtml.agent_login).attr('href','http://ag.'+ulrarr[num]) ; // 随机配置代理登录域名
    $(tohtml.to_service).attr({'href':serviceurl,'target':'_blank'}) ; // 客服链接

}
// 会员公告
function setUserMsg(msg,nottip){
    if(nottip){
        var str = msg ;
    }else{
        var str = msg?('<p class="STYLE1">公 告<br>'+msg+'</p>'):'<p class="STYLE1">公 告<br></p>';
    }
    $('.user_msgnews').html(str) ;
}

/*
* 登录，域名处理
* */
function urlSetAction(htp,num) {
    var url =window.location.host ; // 拿到当前域名
    // var mainurl = url.replace('ad','www'); // 替换成 www
    var mainurlarr = url.split('.'); // 分割
    var mainurl ; // 主域名
    var lurl  ;
    var urllen = urlArray.length ;
    // console.log(urllen);
    if(mainurlarr.length <3){ // 不带www 域名
        mainurl = mainurlarr[0] ; // 取第一位
        lurl = mainurlarr[1];
    }else{
        mainurl = mainurlarr[1] ;  // 取第二位
        lurl = mainurlarr[2] ;
    }
    var $loginform = document.getElementById('LoginForm') ; // 登录
    var $loginform1 = document.getElementById('LoginForm1') ; // 登录
    var $dlUrl = document.getElementById('dlUrl') ; // 注册链接
    var $set_mainurl = $('.set_mainurl') ; // 会员登录
    var $set_agents_mainurl = $('.set_agents_mainurl') ; // 代理登录
    if($loginform){
        $loginform.setAttribute('action',htp+'://'+urlArray[num]+'/login.php') ;
    }
    if($loginform1){
        $loginform1.setAttribute('action',htp+'://'+urlArray[num]+'/login.php') ;
    }
    // if($dlUrl){ // 注册链接
    //     $dlUrl.value=htp+'://'+urlArray[num]+'/reg.php?intr=您的代理编号';
    // }
    $('input[name="Website"]').val(htp+'://'+mainurl+'.'+lurl+'') ; // 登录表单
    // $('.set_forget_url').attr('href','http://'+mainurl+'.'+lurl+'/app/member/account/forget_psw.php') ; // 忘记密码链接
    $('.set_forget_url').attr('href',htp+'://'+urlArray[num]+'/app/member/account/forget_psw.php') ; // 忘记密码链接

    if($set_mainurl){
        // $set_mainurl.attr('href','http://'+mainurl+'.'+lurl+'') ;
        $set_mainurl.attr('href',htp+'://'+urlArray[num]) ;
    }
    //$set_agents_mainurl.attr('href','http://ag.'+mainurl+'.'+lurl+'')
    $set_agents_mainurl.attr('href',htp+'://ag.'+urlArray[num])

}


/*  顶部字体随机换色 */
function changeColor() {
    var color="yellow|green|blue|gray|pink"; //在内存变量color里定义5种颜色
    color=color.split("|"); //定义数组5个元素分别放置5种颜色
    //下面这一行是把名★叫blink的对象★的color属性随机改成5种颜色的一种
    document.getElementById("blink1").style.color=color[parseInt(Math.random() * color.length)];
    document.getElementById("blink2").style.color=color[parseInt(Math.random() * color.length)];
}


/*
* 加载各页面函数
* */
function loadPagesAction() {

    $(document).off('click',tohtml.to_index).on('click',tohtml.to_index,function () {
        navClickAction($(this),tohtml.to_index) ;
        loadIndex() ;
    });
    $(document).off('click',tohtml.to_sports).on('click',tohtml.to_sports,function () {
        /*     var cl = $(this).attr('class').split(' ') ;
       $.each(cl,function (i,v) {
           var pointcl = '.'+v ;
           if(pointcl == tohtml.to_sports){
               $(tohtml.to_sports).addClass('sy_navn1_h').siblings().removeClass('sy_navn1_h') ;
           }

       }) ;*/
        navClickAction($(this),tohtml.to_sports) ;
        loadSports() ;
    });
    $(document).off('click',tohtml.to_lives).on('click',tohtml.to_lives,function () {
        navClickAction($(this),tohtml.to_lives) ;
        loadLives() ;
    });
    $(document).off('click',tohtml.to_games).on('click',tohtml.to_games,function () {
        navClickAction($(this),tohtml.to_games) ;
        loadGames() ;
    });
    $(document).off('click',tohtml.to_lotterys).on('click',tohtml.to_lotterys,function () {
        navClickAction($(this),tohtml.to_lotterys) ;
        loadLotterys() ;
    });
    $(document).off('click',tohtml.to_promos).on('click',tohtml.to_promos,function () {
        navClickAction($(this),tohtml.to_promos) ;
        loadPromos() ;
    });
    $(document).off('click',tohtml.to_proxy).on('click',tohtml.to_proxy,function () {
        navClickAction($(this),tohtml.to_proxy) ;
        loadProxy() ;
        sessionStorage.setItem('topage',tohtml.to_proxy) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_terms).on('click',tohtml.to_terms,function () {
        loadTerms() ;
        sessionStorage.setItem('topage',tohtml.to_terms) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_withdrawals).on('click',tohtml.to_withdrawals,function () {
        loadwWithdrawals() ;
        sessionStorage.setItem('topage',tohtml.to_withdrawals) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_save).on('click',tohtml.to_save,function () {
        loadSave() ;
        sessionStorage.setItem('topage',tohtml.to_save) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_problem).on('click',tohtml.to_problem,function () {
        loadProblem() ;
        sessionStorage.setItem('topage',tohtml.to_problem) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_aboutus).on('click',tohtml.to_aboutus,function () {
        loadAboutus() ;
        sessionStorage.setItem('topage',tohtml.to_aboutus) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_contactus).on('click',tohtml.to_contactus,function () {
        loadContactus() ;
        sessionStorage.setItem('topage',tohtml.to_contactus) ; // 用于判断点击当前页面
    });

    $(document).off('click',tohtml.to_company).on('click',tohtml.to_company,function () {
        loadCompany() ;
        sessionStorage.setItem('topage',tohtml.to_company) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_agentreg).on('click',tohtml.to_agentreg,function () {
        loadAgentreg() ;
        sessionStorage.setItem('topage',tohtml.to_agentreg) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_rule).on('click',tohtml.to_rule,function () {
        loadRule() ;
        sessionStorage.setItem('topage',tohtml.to_rule) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_responsibility).on('click',tohtml.to_responsibility,function () {
        loadResponsibility() ;
        sessionStorage.setItem('topage',tohtml.to_responsibility) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_memberreg).on('click',tohtml.to_memberreg,function () {
        loadMemberreg() ;
        sessionStorage.setItem('topage',tohtml.to_memberreg) ; // 用于判断点击当前页面
    });
    $(document).off('click',tohtml.to_downloadapp).on('click',tohtml.to_downloadapp,function () {
        loadDownloadApp() ;
    });
    $(document).off('click',tohtml.to_chess).on('click',tohtml.to_chess,function () {
        navClickAction($(this),tohtml.to_chess) ;
        loadChessCards() ;
    });


}

// 加载首页
function loadIndex() {
    $(tohtml.sy_content).load('./templates/m_index.php',function () {
        gameMoveAction() ;
        settingBaseWeb();
    }) ;
}
// 加载体育赛事页面
function loadSports() {
    $(tohtml.sy_content).load('./templates/sports.html',function () {

    }) ;
}
// 加载真人页面
function loadLives() {
    $(tohtml.sy_content).load('./templates/lives.html',function () {

    }) ;
}
// 加载电子游戏页面
function loadGames() {
    $(tohtml.sy_content).load('./templates/games.html',function () {

    }) ;
}
// 加载彩票页面
function loadLotterys() {
    $(tohtml.sy_content).load('./templates/lotterys.php',function () {

    }) ;
}

// 加载优惠活动页面
function loadPromos() {
    $(tohtml.sy_content).load('./templates/promos.php',function () {
        promosAction() ;
    }) ;
}

// 加载代理加盟页面
function loadProxy() {
    $(tohtml.sy_content).load('./templates/proxy.php',function () {
        settingBaseWeb();
    }) ;
}
// 加载使用条款页面
function loadTerms() {
    $(tohtml.sy_content).load('./templates/terms.html',function () {

    }) ;
}
// 加载取款帮助页面
function loadwWithdrawals() {
    $(tohtml.sy_content).load('./templates/withdrawals.html',function () {

    }) ;
}
// 加载存款帮助页面
function loadSave() {
    $(tohtml.sy_content).load('./templates/save.html',function () {

    }) ;
}
// 加载常见问题页面
function loadProblem() {
    $(tohtml.sy_content).load('./templates/problem.html',function () {

    }) ;
}
// 加载关于我们页面
function loadAboutus() {
    $(tohtml.sy_content).load('./templates/aboutus.html',function () {

    }) ;
}
// 加载联系我们页面
function loadContactus() {
    $(tohtml.sy_content).load('./templates/contactus.html',function () {
        settingBaseWeb();
    }) ;
}
// 加载联系我们页面
function loadCompany() {
    $(tohtml.sy_content).load('./templates/company.html',function () {
        settingBaseWeb();
    }) ;
}
// 加载代理注册页面
function loadAgentreg() {
    $(tohtml.sy_content).load('./templates/agentreg.php',function () {

    }) ;
}

// 加载规则说明页面
function loadRule() {
    $(tohtml.sy_content).load('./templates/rule.html',function () {

    }) ;
}

// 加载负责任博彩页面
function loadResponsibility() {
    $(tohtml.sy_content).load('./templates/responsibility.html',function () {

    }) ;
}
// 加载会员注册页面
function loadMemberreg(val) {
    $(tohtml.sy_content).load('./templates/memberreg.php',function () {
        if(val){
            $('.sy_content').find('#introducer').val(val) ; // 介绍人
        }
    }) ;
}
function loadDownloadApp() {
    $(tohtml.sy_content).load('./templates/downloadapp.html',function () {

    }) ;
}
function loadChessCards() {
    $(tohtml.sy_content).load('./templates/chess_cards.php',function () {

    }) ;
}

// 导航栏点击处理 ,e 当前 $(this)，tar 当前点击对象，
function navClickAction(e,tar) {
    var cl = e.attr('class').split(' ') ;
    $.each(cl,function (i,v) {
        var pointcl = '.'+v ;
        if(pointcl == tar){
            $(tar).addClass('sy_navn1_h').siblings().removeClass('sy_navn1_h') ;
        }
    })

}

/* 底部游戏列表滚动 */
function gameMoveAction() {
    // 点击滚动图
    if($('#boxid > li').length > 4){
        var scrollPic_02 = new ScrollPic();
        scrollPic_02.scrollContId   = "ISL_Cont_1"; //内容容器ID
        scrollPic_02.arrLeftId      = "LeftArr";//左箭头ID
        scrollPic_02.arrRightId     = "RightArr"; //右箭头ID
        scrollPic_02.frameWidth     = 1016;//显示框宽度
        scrollPic_02.pageWidth      = 254; //翻页宽度
        scrollPic_02.speed          = 10; //移动速度(单位毫秒，越小越快)
        scrollPic_02.space          = 10; //每次移动像素(单位px，越大越快)
        scrollPic_02.autoPlay       = true; //自动播放
        scrollPic_02.autoPlayTime   = 5; //自动播放间隔时间(秒)
        scrollPic_02.initialize(); //初始化

    }
}

/* 优惠活动点击事件处理 */
function promosAction() {
    $(".yhhd_mn span").each(function(index){
        $(this).click(function(){
            if($(".yhhd_mn_con").is(":visible")==false){
                $(".yhhd_mn_con:eq("+index+")").show()
            }
            else{
                $(".yhhd_mn_con").hide();}
        })
    })
}

/*  代理加盟，关于我们等左侧公用选单 */
function loadLeftNav() {
    var  str = '<div class="gywm_mnbl_pic1"><img src="images/gywm_rmyx.jpg"></div>' ;
    str +='<div class="gywm_mnbl_pic2">' +
            '<a href="javascript:;" class="to_terms"><div class="gywm_mnbl_list">使用条款</div><span class="gywm-icon" ></span></a>' +
            '<a href="javascript:;" class="to_proxy"><div class="gywm_mnbl_list">合作伙伴</div><span class="gywm-icon" ></span></a>' +
            '<a href="javascript:;" class="to_withdrawals"><div class="gywm_mnbl_list">取款帮助</div><span class="gywm-icon" ></span></a>' +
            '<a href="javascript:;" class="to_save"><div class="gywm_mnbl_list">存款帮助</div><span class="gywm-icon" ></span></a>' +
            '<a href="javascript:;" class="to_problem"><div class="gywm_mnbl_list">常见问题</div><span class="gywm-icon" ></span></a>' +
            '<a href="javascript:;" class="to_aboutus"><div class="gywm_mnbl_list">关于我们</div><span class="gywm-icon" ></span></a>' +
            '<a href="javascript:;" class="to_company"><div class="gywm_mnbl_list">联系我们</div><span class="gywm-icon" ></span></a>' +
            '<a href="javascript:;" class="to_agentreg"><div class="gywm_mnbl_list">代理注册</div><span class="gywm-icon" ></span></a>' +
            '<a href="javascript:;" class="to_memberreg"><div class="gywm_mnbl_list">会员注册</div><span class="gywm-icon" ></span></a>' +
        '</div>';
    str +='<div class="gywm_mnbl_pic1"><img src="'+webPicConfig.server_wechat_code+'"></div>' ;

    $('body').find('.gywm_mnbl').html(str) ;

    var topage = sessionStorage.getItem('topage') ;
    $('.gywm_mnbl').find('a').each(function (i,v) {
        var cl = $(this).attr('class').split(' ') ;
        var pointcl = '.'+cl ;
        // console.log(cl)
        if(pointcl == topage){
            $(this).addClass('gywm_mnbl_cur').siblings().removeClass('gywm_mnbl_cur') ;
        }
    }) ;

}

/* 登录函数 */

//登陆账号密码错误弹窗  xw 2017-03-01
function do_login(sec){
    var username=$("#ausername").val();
    var username1=$("#ausername1").val();
    var passwd=$("#apassword").val();
    var passwd1=$("#apassword1").val();
    var langx=$("#langx").val();

    if(sec){
        if (username1 == "" ) {
            var $title = '帐号不能为空！';
            alert($title);
            return false;
        }
        if ( passwd1 == "" ) {
            var $title = '密码不能为空！';
            alert($title);
            return false;
        }
        document.getElementById("LoginForm1").submit();
    }else{
        if (username == "" ) {
            var $title = '帐号不能为空！';
            alert($title);
            return false;
        }
        if ( passwd == "" ) {
            var $title = '密码不能为空！';
            alert($title);
            return false;
        }

        document.getElementById("LoginForm").submit();
    }
}


/* 会员注册的时候获取域名后面的参数, 域名参数 ?promo 优惠活动，?agent 跳转到代理注册*/
function getIntroducer() {
    var url = window.location.search ;
    if (url.indexOf("?") != -1) {
        url = url.split('?') ;
        var intr = url[1].toLowerCase() ;
        var intrarr = intr.split('=');
        var flag = intrarr[0] ;
        //  console.log(intr);
        //  console.log(flag);
        switch (flag){
            case 'intr': // 跳转到注册页
                loadMemberreg(intrarr[1]) ;
                break;
            case 'promo': // 跳转到优惠活动页
                loadPromos() ;
                break;
            case 'agent': // 跳转到代理注册页
                loadAgentreg() ;
                break;

        }

    }

}
// 美东时间
function getTime() {
    var nowDate = new Date(new Date().getTime() - 43200000),
        nY = nowDate.getFullYear(),
        nM = nowDate.getMonth() + 1,
        nD = nowDate.getDate(),
        nH = nowDate.getHours(),
        nMi = nowDate.getMinutes(),
        nS = nowDate.getSeconds();
    nM = nM < 10 ? '0' + nM : nM;
    nD = nD < 10 ? '0' + nD : nD;
    nH = nH < 10 ? '0' + nH : nH;
    nMi = nMi < 10 ? '0' + nMi : nMi;
    nS = nS < 10 ? '0' + nS : nS;

    var fullTime = nY + '-' + nM + '-' + nD + ' ' + nH + ':' + nMi + ':' + nS;
    $('#nowTime').text(fullTime);
}
// 复制文本
function copyUrl2(id) {
    var Url2=document.getElementById(id);
    Url2.select(); // 选择对象
    document.execCommand("Copy"); // 执行浏览器复制命令
    alert("已复制好，可贴粘。");
}

// 设置为主页

function SetHome(obj, vrl) {
    try {
        obj.style.behavior = 'url(#default#homepage)';
        obj.setHomePage(vrl);
    } catch (e) {
        if (window.netscape) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            } catch (e) {
                alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
            }
            var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
            prefs.setCharPref('browser.startup.homepage', vrl);
        } else {
            alert("您的浏览器不支持，请按照下面步骤操作：1.打开浏览器设置。2.点击设置网页。3.输入：" + vrl + "点击确定。");
        }
    }
}
// 加入收藏 兼容360和IE6

function shoucang(sTitle, sURL) {
    try {
        window.external.addFavorite(sURL, sTitle);
    } catch (e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "");
        } catch (e) {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
