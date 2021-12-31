var alertTime = 3000; // 弹窗提示时间
var tplName = getCookieAction('tplname');
var companyname = getCookieAction('companyname');
var web_configbase = JSON.parse(localStorage.getItem('webconfigbase'));
var config = {
    onlineserver: web_configbase.service_meiqia,
    webname: companyname,
    telephone: web_configbase.service_phone_24,
    complaintel: web_configbase.service_phone_phl,
    email: web_configbase.service_email,
    errormsg: "获取数据失败，请稍后再试！",
    closebetmsg: "赛事已关闭！",
    loginmsg: "请重新登录！",
};
/* 2018 */

inputAddClass();
/* 解决输入框获取焦点时底部错位问题 */
function inputAddClass() {
    var $footer = $('#footer');
    var $input = $('input');
    $input.focus(function(){
        $footer.hide();
    });
    $input.blur(function(){
        $footer.show();
    });
}

/*
 *  刪除登录后信息
 * */
function delLoginStatus() {
    //$('#cp_loginout_url').attr('src',cp_url+'main/out') ; // 退出彩票登录
    delCookieAction('mymsg');
    localStorage.removeItem('p3BetArray'); // 删除综合过关已选择的数据
    localStorage.removeItem('username'); // 删除用户名
    localStorage.removeItem('myoid'); // 删除 myoid
    localStorage.removeItem('cpUrlArr'); // 删除彩票登录链接
    localStorage.removeItem('third_cp_url_num'); // 删除彩票登录链接
    localStorage.removeItem('sport_url_num'); // 删除皇冠体育登录链接
    localStorage.setItem('agentlogintime',1); // 代理商域名登录记录次数
}

/*
 ** randomWord 产生任意长度随机字母数字组合
 ** randomFlag-是否任意长度 min-任意长度最小位[固定位数] max-任意长度最大位
 ** 生成3-32位随机串：randomWord(true, 3, 32)
 **  生成43位随机串：randomWord(false, 43)
 */
function randomWord(randomFlag, min, max){
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
}

/*
 * 保留两位小数，roundUp 参数四舍五入
 * */
function changeTwoDecimal(x,roundUp) {
    var f_x = parseFloat(x);
    // console.log(typeof (f_x));
    if (isNaN(f_x) || f_x==0) {
        return '';
    }
    if(roundUp){ // 四舍五入

        var f_x = Math.round(x * 100) / 100;
    }else{ // 不四舍五入，直接保留两位小数

        var f_x = x * 100/100 ;
    }

    var s_x = f_x.toString();
    var pos_decimal = s_x.indexOf('.');
    var arr = s_x.split('.');

    if(pos_decimal>0){
        if(arr[1].length>1){
            s_x = arr[0]+'.'+arr[1].substr(0,2);
        }else{
            while (s_x.length <= pos_decimal + 2) {
                s_x += '0';
            }
        }
    }else{
        if (pos_decimal < 0) {
            pos_decimal = s_x.length;
            s_x += '.';
        }
        while (s_x.length <= pos_decimal + 2) {
            s_x += '0';
        }
    }

    return s_x;
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
// 美东时间设置
function setAmerTime(el,dayoff) {
    var today = new Date();
    today.setHours(today.getHours() - 12);
    var y = today.getFullYear();
    var m = today.getMonth() + 1;
    var d = today.getDate();
    var h = today.getHours();
    var mm = today.getMinutes();
    var s = today.getSeconds();
    m =  checkTime(m);
    d = checkTime(d);
    h = checkTime(h);
    mm = checkTime(mm);
    s = checkTime(s);
    if(el =='#time_textbox'){
        $(el).val(y+"-"+m+"-"+d+" "+h+":"+mm); // 只到分
    }else if(dayoff=='dayoff'){ // 到天
        $(el).val(y+"-"+m+"-"+d); // 只到分
    }else if(dayoff=='daystart'){ // 开始时间 00:00
        $(el).val(y+"-"+m+"-"+d+" 00:00");
    }else if(dayoff=='dayend') { // 开始时间 23:59
        $(el).val(y+"-"+m+"-"+d+" 23:59");
    }else{
        $(el).val(y+"-"+m+"-"+d+" "+h+":"+mm+":"+s);
    }

}
/**
 * 1位数补0为2位数
 * @param i
 * @returns {*}
 */
function checkTime(i) {
    if (i<10) { i="0" + i ;}
    return i ;
}
/*
 * 保留 n 位小数
 * */
function numberToFloat(val,n) {
    n = n ? parseInt(n) : 0;
    //val = val ? val : 0;
    val = parseFloat(val).toFixed(n);
    return val ;
}
// 判断金额是否为整数,
function checkInputInt(val,can) {
    var ret = /^[1-9]{1}[0-9]*$/ ; // 不能以0开头
    if(can){
        ret = /^[0-9]+$/g; // 可以0 开头,提款密码
    }
    return ret.test(val) ;
}
// 判断金额是否为两位以内的小数，最多两位( 存款 )
function checkInputFloat(val) {
    var ret = /^\d+(\.\d{1,2})?$/ ;
    return ret.test(val) ;
}
/*
 * 还原金额，去除逗号
 * */
function returnMoney(s) {
    return parseFloat(s.replace(/[^\d\.-]/g, ""));
}
// 设置cookie
function setCookieAction(theName,theValue,theDay){
    // if((theName != "")&&(theValue !="")){
    if(theName != "" && theName){
        expDay = "Web,01 Jan 2026 18:56:35 GMT";
        if(theDay != null){
            theDay = eval(theDay);
            setDay = new Date();
            setDay.setTime(setDay.getTime()+(theDay*1000*60*60*24));
            expDay = setDay.toGMTString();
        }
        //document.cookie = theName+"="+escape(theValue)+";expires="+expDay;
        document.cookie = theName+"="+escape(theValue)+";path=/;expires="+expDay+";";
        return true;
    }
    return false;
}
// 获取cookie
function getCookieAction(theName){
    theName += "=";
    theCookie = document.cookie+";";
    start = theCookie.indexOf(theName);
    if(start != -1){
        end = theCookie.indexOf(";",start);
        return unescape(theCookie.substring(start+theName.length,end));
    }
    // return false;
    return 0 ;
}
// 删除cookie
function delCookieAction(theName){
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookieAction(theName);
    if(cval!=null){
        document.cookie= theName + "='';path=/;expires="+exp.toGMTString();
    }

}

// 设置定期 LocalStorage
function setLocalStorage(key, value) {
    var curtime = new Date().getTime(); // 获取当前时间 ，转换成JSON字符串序列
    var valueDate = JSON.stringify({
        val: value,
        timer: curtime
    });
    try {
        localStorage.setItem(key, valueDate);
    } catch(e) {
        // 兼容性写法
        if(isQuotaExceeded(e)) {
            console.log("Error: 本地存储超过限制");
            localStorage.clear();
        } else {
            console.log("Error: 保存到本地存储失败");
        }
    }
}

// 去除所有空格
function removeAllSpace(str) {
    return str.replace(/\s+/g, "");
}

// 公用弹窗,hasinput 是否有输入框
function setPublicPop(title,ptime) {
    if(!ptime){
        ptime = alertTime; // 默认3秒
    }
    var str ='<div class="pop_all"><div class="pop_bg"></div>' +
        '<div id="all_pop"></div>' +
        '<div class="Pop-up pop_close">' +
        '<div class="btn_close pop_cls close_event"><i class="fa fa-times"></i></div>' ;
    // if(hasinput){ // 有输入框
    //     str += '<input class="alert_input money-textbox" placeholder="请输入转账金额" name="alert_blance" id="alert_blance">';
    // }else{ // 没有输入框
    //     str += '<p id="pop_text">'+title+'</p>';
    // }
    str += '<p id="pop_text">'+title+'</p>';
    str +='<button class="login_btn">确定</button>' +
        '</div></div>';
    $('body').append(str);

    // 显示弹窗
    $(".pop_bg , .Pop-up").show();

    var poptimer = setTimeout(function () { // 默认3秒关闭窗口
        $(".pop_bg,.pop_close").hide();
        $('.pop_all').remove();
    },ptime);

    // 关闭弹窗
    $(document).off().on("click", '.pop_bg , .close_event,.Pop-up button ', function(){
        clearTimeout(poptimer) ;
        $(".pop_bg,.pop_close").hide();
        $('.pop_all').remove();

    });

}

// 获取 id 选择器
function _$(i){
    return document.getElementById(i);
}

// 返回上一步 ,index 返回到首页
function goBackAction(index) {
    if(index){ // 登录页面返回，统一到首页
        window.location.href='/';
    }else{
        history.go(-1) ;
    }

}

// 弹窗公用
function alertComing(msg) {
    if(!msg){ // 默认敬请期待弹窗
        msg = '即将推出,敬请期待！' ;
    }
    alert(msg) ;
}
// 回到顶部公用
function goToTop() {
    var str ='<div class="go_to_top"><i class="fa fa-chevron-up" ></i></div>' ;
    $('#container').append(str) ;
    var $go_to_top = $('.go_to_top') ;

    // 监听滚动
    $(window).scroll(function(){
        var scr_top = $(this).scrollTop() ;
        //console.log(scr_top)
        if(scr_top >= 1000){
            $go_to_top.show() ;
        } else{
            $go_to_top.hide() ;
        }
    });

    $go_to_top.on('click',function () {
        scrollTo(0,0);
    }) ;

}

// 增加客服链接
function addServerUrl() {
    $('.agents_qq_number').text(web_configbase.agents_service_qq);// 代理QQ
    var $website = document.getElementsByClassName('website-url')[0] ;
    var $webtitle = document.getElementsByClassName('web-title')[0] ;
    var $setitle = document.getElementsByClassName('web-title')[1] ;
    if($webtitle){ // 网站名称
        $webtitle.innerHTML = config.webname ;
    }
    if($setitle){  // 网站名称
        $setitle.innerHTML = config.webname ;
    }
    if($website){
        $website.innerHTML = config.webname ; // website
    }
}

// 设置公用底部，登录页面
function setFooterAction(uid,card) {
    // 底部
    var $footer = $('#footer') ;
    var str = '';
    if(card){
        str += '<a onclick="" href="javascript:;" class="bet_card_num"><span >0</span> <p>交易单</p></a>' ; /* <i class="fa fa-usd"></i> */
    }
    if(tplName=='views/6668/' || tplName=='views/8msport/'){
        if(tplName=='views/6668/'){
            str += '<a href="/'+tplName+'promo.php" class="to-promos"><i class="index_fa fa-promo"></i> <p>优惠</p></a>'+
                '<a onclick="ifHasLogin(\'/'+tplName+'\account/deposit_one.php\',\'\',\''+uid+'\')" href="javascript:;" class="to-deposit" ><i class="index_fa fa fa-credit-card"></i> <p>存款</p></a>' ;
        }else{
            str += '<a onclick="ifHasLogin(\'/'+tplName+'\account/deposit_one.php\',\'\',\''+uid+'\')" href="javascript:;" class="to-deposit" ><i class="index_fa fa fa-credit-card"></i> <p>存款</p></a>'+
                '<a href="/'+tplName+'promo.php" class="to-promos"><i class="index_fa fa-promo"></i> <p>优惠</p></a>';
        }
        str += '<a href="/" class="to-home active"><i class="index_fa fa fa-home"></i> <p>首页</p></a>' ;
    }else {
        str += '<a href="/" class="to-home active"><i class="index_fa fa fa-home"></i> <p>首页</p></a>' ;
        str += '<a onclick="ifHasLogin(\'/'+tplName+'\account/deposit_one.php\',\'\',\''+uid+'\')" href="javascript:;" class="to-deposit" ><i class="index_fa fa fa-credit-card"></i> <p>存款</p></a>' +
            '<a href="/'+tplName+'promo.php" class="to-promos"><i class="index_fa fa-promo"></i> <p>优惠活动</p></a>' ;

    }
    str += '<a href="javascript:;" class="online-server" onclick="window.open(config.onlineserver)"><i class="index_fa fa fa-commenting"></i> <p>客服</p></a>' +
        '<a onclick="ifHasLogin(\'/'+tplName+'\account.php\',\'\',\''+uid+'\')" href="javascript:;" class="to-myaccount"><i class="index_fa fa fa-user-circle"></i> <p>我的</p></a>';
    $footer.html(str) ;

    // 底部页面切换
    $footer.on('click','a',function () {
        var cl = $(this).attr('class').split(' ')[0] ;
        localStorage.setItem('footnav',cl) ;
    }) ;

    $footer.find('a').each(function () {
        var cla = $(this).attr('class').split(' ')[0] ;
        var nav = localStorage.getItem('footnav') ;
        if(!nav){ // 默认首页
            nav ='to-home' ;
        }
        if(cla == nav){
            $(this).addClass('active').siblings().removeClass('active') ;
        }
    });

    goToTop(); // 回到顶部

}

// 设置登录页面，客服页面公用头部，title 头部文字，has 注册页有登录按钮，底部 ； login 登录页参数，返回到首页,mon 用户余额, oid 用户是否登录
function setLoginHeaderAction(title,has,login,mon,oid) {
    // 头部
    var $head = document.getElementsByClassName('header')[0] ;
    var hstr ;
    if(!oid || oid==''){ // 未登录
        var top_right = '';
    }else{ // 已登录
        var top_right = '<div class="header-right">' +
            '<i class="fa fa-database"></i>' +
            '<p class="hg_money after_login">'+mon+'</p>' +
            '</div>' ;
    }

    if(login){ // 登录页返回
        hstr = ' <a href="javascript:;" class="icon-back back-active" onclick="goBackAction(\'login\')">&nbsp;&nbsp;返回</a> <span class="header_logo">'+title+'</span>' ; // 去掉 title 了
    }else{
        hstr = ' <a href="javascript:;" class="icon-back back-active" onclick="goBackAction()">&nbsp;&nbsp;返回</a> <span class="header_logo"></span>'+top_right ; // 去掉 title 了
    }
    if(has){ // 注册页面的登录按钮
        hstr +='<a class="reg-login-btn" href="login.php">立即登录</a>' ;
    }
    if($head){
        $head.innerHTML = hstr ;
    }
}

// 未登录情况下需要跳转到登录页,win 是否新窗口
function ifHasLogin(url,win,oid) {
    // var oid = localStorage.getItem('myoid') ;
    if(!oid || oid ==''){ // 未登录
        alertComing('请先登录！');
        window.location.href = '/'+tplName+'login.php' ;
        return false ;
    }else{ // 已登录
        // 检测是否单页维护
        var maintainType = '';
        var ifweihu = 0; // 默认不维护
        if(url.match(/mc./)){
            maintainType = 'lottery';
        }else if(url.match(/zrsx_login.php\?gameid/)){ // ag电子 和 ag捕鱼
            maintainType = 'game';
        }else if(url.match(/live/) || url.match(/zrsx_login/)){
            maintainType = 'video';
        }else if(url.match(/og\/login.php/)){
            maintainType = 'og';
        }else if(url.match(/bbin\/login.php/)){
            maintainType = 'bbin';
        }else if(url.match(/sport_main/)){
            maintainType = 'sport';
        }else if(url.match(/ky/)){
            maintainType = 'ky';
        }else if(url.match(/hgqp/)){
            maintainType = 'hgqp';
        }else if(url.match(/vgqp/)){
            maintainType = 'vgqp';
        }else if(url.match(/lyqp/)){
            maintainType = 'ly';
        }else if(url.match(/klqp/)){
            maintainType = 'klqp';
        }else if(url.match(/mg/)){
            maintainType = 'mg';
        }else if(url.match(/cq/)){
            maintainType = 'cq';
        }else if(url.match(/avia/)){
            maintainType = 'avia';
        }else if(url.match(/fire/)){
            maintainType = 'thunfire';
        }else if(url.match(/lotteryThird/)){
            maintainType = 'thirdcp';
        }else if(url.match(/mw/)){
            maintainType = 'mw';
        }else if(url.match(/fg/)){
            maintainType = 'fg';
        }
        if(maintainType){
            ifweihu = getPageMaintenance(maintainType,'val');
        }
        // console.log(maintainType)
        // console.log(ifweihu)
        if(ifweihu ==0){ // 没有维护
            if(tplName =='views/0086dj/' && (maintainType=='avia' || maintainType=='thunfire')){ // 电竞当前打开
                body_dzjj.location.replace(url);
            }else{
                if(win){ // 新窗口打开
                    window.open(url);
                }else{
                    window.location.href = url ;
                }
            }
        }

    }
}


// 设置公用联系我们，登录页，充值，提款页等
function setPublicContact() {
    var $contact = document.getElementsByClassName('contact-us')[0] ;
    var str = ' <a class="online-server btn-primary" onclick="window.open(config.onlineserver)">在线客服</a>' +
        '<span>热线电话：'+ config.telephone +'</span>' ;
    if($contact){
        $contact.innerHTML = str ;
    }

}

// 金额快速选取,sel 当前选择的金额(对应 checkedMoney),type 当前充值方式(对应 urltype)
function chooseAction(sel) { //  2017/12/15 新加
    var $select = $('.money-textbox') ;
    $('.moneychoose').off().on('click', 'span,button', function() {
        sel = Number($(this).text());
        $(this).parent('td').addClass('active').siblings('td').removeClass('active');
        $(this).parents('tr').siblings('tr').find('td').removeClass('active');
        $(this).addClass('active').siblings().removeClass('active');
        $select.val(Number($select.val()) + sel);
    });
}

// 删除金额,mon 当前金额
function deleteMoney(mon) {
    $('.textbox-close').on('click', function() {
        mon = '' ;
        $(this).prev().val('');
        $(this).parents('.form').find('.money tbody td').removeClass('active');
    }) ;
}

// 额度转换统一弹出窗，type 当前类型，chagtype 转入转出类型(live 真人，game 电子) ,mainmoney 帐户余额，typemoney 当前游戏余额
function limitDialogAction(type,chagtype,mainmoney,thismoney,uid) {
    var str = '<div class="quota-manage" id="dialog">' +
        '<div class="quota-info clearfix">' +
        '<div><span class="label">账户余额：</span><span class="quota hg_money">'+mainmoney+'元</span></div>' +
        '<div><span class="label">'+type+'余额：</span><span class="quota third_game_money">'+thismoney+'元</span></div>' +
        '</div>' +
        '<div class="textbox-wrap">' +
        '<input type="text" class="limit_gold" onkeyup="this.value=this.value.replace(/\\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\\D/g,\'\')" placeholder="输入金额">' +
        '</div>' +
        '<div class="btn-wrap clearfix">' +
        '<a href="javascript:;" class="zx_submit" onclick="changeLimitAction(\''+chagtype+'\',\'out\')">'+type+'转出</a>' +
        '<a href="javascript:;" class="zx_submit" onclick="changeLimitAction(\''+chagtype+'\',\'in\')">'+type+'转入</a>' +
        '</div>' +
        '</div>' ;
    $('.dialog-content').find('.quota-manage').remove();
    $('.content-center').after(str) ;

    // 初始化
    var $dialog = $('#dialog'),
        dialogHeight = $dialog.height(),
        footerHeight = $('#footer').height();
    $dialog.css('bottom', -dialogHeight + 'px');

    // 显示与隐藏
    $('.limit-toggle').click(function() {
        if($dialog.css('bottom').indexOf('-') > -1) {
            $dialog.addClass('show').animate({'bottom': footerHeight + 'px', duration: 'fast'});
        } else {
            $dialog.animate({'bottom': -dialogHeight + 'px', duration: 'fast'})
        }
    });

}

// 额度转换函数,type 转入转出类型(live 为真人，game 为电子，mggame)，chg 转出或者转入(out 转出，in 转入)
function changeLimitAction(type,chg) {
    var $limit_gold = $('.limit_gold') ;
    var val = $limit_gold.val() ; // 输入金额
    var ajaxurl ; // 接口地址
    if(val == ''||val == 0){
        alertComing('请输入金额！');
        return false;
    }
    var senddata = {
        value:val // 金额
    };
    senddata.b=val;
    if (type=='mggame'){
        ajaxurl = '/mg/mg_api.php';
        if( chg =='in' ){ // 体育转到MG电子
            senddata.f='hg';
            senddata.t='mg';
        }
        if( chg =='out' ){ // MG电子转到体育
            senddata.f='mg';
            senddata.t='hg';
        }
        $.ajax({
            url:  ajaxurl ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success:function(ret){

                if(ret.status != '200'){
                    alertComing(ret.describe)
                }else{
                    if(ret.status=='200'){
                        alertComing('转账成功，请查看余额')
                        // location.reload();
                        $('#video_blance,.third_game_money').html(ret.data.mg_balance); // mg 余额
                        $('.hg_money').html(ret.data.hg_balance) ; // 更新导航栏皇冠余额
                        // 初始化
                        var $dialog = $('#dialog'),
                            dialogHeight = $dialog.height();
                        $dialog.animate({'bottom': -dialogHeight + 'px', duration: 'fast'});
                    }
                }
            },
            error: function (ii,jj,kk) {
                alertComing('额度转换错误');
            }
        });
    }
    else if (type=='mwgame'){
        ajaxurl = '/mw/mw_api.php';
        if( chg =='in' ){ // 体育转到MW电子
            senddata.f='hg';
            senddata.t='mw';
        }
        if( chg =='out' ){ // MW电子转到体育
            senddata.f='mw';
            senddata.t='hg';
        }
        $.ajax({
            url:  ajaxurl ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success:function(ret){

                if(ret.status != '200'){
                    alertComing(ret.describe)
                }else{
                    if(ret.status=='200'){
                        alertComing('转账成功，请查看余额')
                        // location.reload();
                        $('#video_blance,.third_game_money').html(ret.data[0].mw_balance); // mw 余额
                        $('.hg_money').html(ret.data[0].hg_balance) ; // 更新导航栏皇冠余额
                        // 初始化
                        var $dialog = $('#dialog'),
                            dialogHeight = $dialog.height();
                        $dialog.animate({'bottom': -dialogHeight + 'px', duration: 'fast'});
                    }
                }
            },
            error: function (ii,jj,kk) {
                alertComing('额度转换错误');
            }
        });
    }
    else if (type=='cqgame'){
        ajaxurl = '/cq9/cq9_api.php';
        if( chg =='in' ){ // 体育转到CQ9电子
            senddata.f='hg';
            senddata.t='cq';
        }
        if( chg =='out' ){ // CQ9电子转到体育
            senddata.f='cq';
            senddata.t='hg';
        }
        $.ajax({
            url:  ajaxurl ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success:function(ret){

                if(ret.status != '200'){
                    alertComing(ret.describe)
                }else{
                    if(ret.status=='200'){
                        alertComing('转账成功，请查看余额')
                        // location.reload();
                        $('#video_blance,.third_game_money').html(ret.data.cq_balance); // CQ9 余额
                        $('.hg_money').html(ret.data.hg_balance) ; // 更新导航栏皇冠余额
                        // 初始化
                        var $dialog = $('#dialog'),
                            dialogHeight = $dialog.height();
                        $dialog.animate({'bottom': -dialogHeight + 'px', duration: 'fast'});
                    }
                }
            },
            error: function (ii,jj,kk) {
                alertComing('额度转换错误');
            }
        });
    }
    else if (type=='fggame'){
        ajaxurl = '/fg/fg_api.php';
        if( chg =='in' ){ // 体育转到FG电子
            senddata.f='hg';
            senddata.t='fg';
        }
        if( chg =='out' ){ // FG电子转到体育
            senddata.f='fg';
            senddata.t='hg';
        }
        $.ajax({
            url:  ajaxurl ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success:function(ret){

                if(ret.status != '200'){
                    alertComing(ret.describe)
                }else{
                    if(ret.status=='200'){
                        alertComing('转账成功，请查看余额')
                        // location.reload();
                        $('#video_blance,.third_game_money').html(ret.data.fg_balance); // FG 余额
                        $('.hg_money').html(ret.data.hg_balance) ; // 更新导航栏皇冠余额
                        // 初始化
                        var $dialog = $('#dialog'),
                            dialogHeight = $dialog.height();
                        $dialog.animate({'bottom': -dialogHeight + 'px', duration: 'fast'});
                    }
                }
            },
            error: function (ii,jj,kk) {
                alertComing('额度转换错误');
            }
        });
    }
    else{
        ajaxurl = '/ag_api.php' ;
        if( chg =='in' ){ // 体育转到AG真人
            senddata.f='hg';
            senddata.t='ag';
        }
        if( chg =='out' ){ // AG真人转到体育
            senddata.f='ag';
            senddata.t='hg';
        }
        $.ajax({
            url:  ajaxurl ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success:function(ret){

                if(ret.status != '200'){
                    alertComing(ret.describe)
                }else{
                    if(ret.status=='200'){
                        alertComing('转账成功，请查看余额')
                        location.reload();
                    }
                }
            },
            error: function (ii,jj,kk) {
                alertComing('额度转换错误');
            }
        });
    }


}

// 获取余额（体育、AG、MG、CQ9、MW、FG） 电子，AG真人
function get_blance(balance){
    $('#video_blance,.ag_money,.hg_money').html('加载中');
    var dat={};
    dat.action='b';
    var ajaxUrl = '/ag_api.php'; // 默认AG
    switch (balance){
        case 'mg':
            ajaxUrl = '/mg/mg_api.php';
            break;
        case 'mw':
            ajaxUrl = '/mw/mw_api.php';
            break;
        case 'cq':
            ajaxUrl = '/cq9/cq9_api.php';
            break;
        case 'fg':
            ajaxUrl = '/fg/fg_api.php';
            break;
    }

    $.ajax({
        type: 'POST',
        //async: false,
        url:ajaxUrl,
        data:dat,
        dataType:'json',
        success:function(ret){
            if(ret.status=='200'){
                // console.log(ret)
                if(ret.data[0]){ // MW
                    setCookieAction('member_money',ret.data[0].hg_balance,1) ; // 用户金额，cookie 有效期 1天
                    $('.hg_money').html(ret.data[0].hg_balance); // 体育 余额
                }else{
                    if(ret.data.hg_balance){ // MG
                        setCookieAction('member_money',ret.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                        $('.hg_money').html(ret.data.hg_balance); // 体育 余额
                    }else{
                        setCookieAction('member_money',ret.data.balance_hg,1) ; // 用户金额，cookie 有效期 1天
                        $('.hg_money').html(ret.data.balance_hg); // 体育 余额
                    }

                }

                if(ret.data.balance_ag || ret.data.balance_ag==0){
                    setCookieAction('ag_balance',ret.data.balance_ag,1) ; // ag金额，cookie 有效期 1天
                    $('#video_blance,.ag_money').html(ret.data.balance_ag); // ag 余额
                    $('.cp_money').html(ret.data.balance_cp) ; // 彩票余额
                    limitDialogAction('AG电子','game',ret.data.balance_hg,ret.data.balance_ag) ;
                }
                if(ret.data.mg_balance){
                    setCookieAction('mg_balance',ret.data.mg_balance,1) ; // ag金额，cookie 有效期 1天
                    $('#video_blance').html(ret.data.mg_balance); // mg 余额
                    limitDialogAction('MG电子','mggame',ret.data.hg_balance,ret.data.mg_balance) ;
                }
                if(ret.data[0]){
                    if(ret.data[0].mw_balance){
                        setCookieAction('mw_balance',ret.data[0].mw_balance,1) ; // ag金额，cookie 有效期 1天
                        $('#video_blance').html(ret.data[0].mw_balance); // mw 余额
                        limitDialogAction('MW电子','mwgame',ret.data[0].hg_balance,ret.data[0].mw_balance) ;
                    }
                }

                if(ret.data.cq_balance){
                    setCookieAction('cq_balance',ret.data.cq_balance,1) ; // cq9金额，cookie 有效期 1天
                    $('#video_blance').html(ret.data.cq_balance); // cq9 余额
                    limitDialogAction('CQ9电子','cqgame',ret.data.hg_balance,ret.data.cq_balance) ;
                }
                if(ret.data.fg_balance){
                    setCookieAction('fg_balance',ret.data.fg_balance,1) ; // ag金额，cookie 有效期 1天
                    $('#video_blance').html(ret.data.fg_balance); // fg 余额
                    limitDialogAction('FG电子','fggame',ret.data.hg_balance,ret.data.fg_balance) ;
                }

            }
            else{
                $('#video_blance,.ag_money').html('0.00');
            }
        },
        error:function(ii,jj,kk){
            alertComing('网络错误，请稍后重试');
        }
    });

}

// 获取平台和皇冠体育余额
function get_sc_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/sportcenter/sport_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('sc_chess_mon',item.data.sc_balance,1) ; // 用户皇冠体育金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠体育余额
                $(cal).html(item.data.sc_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}

// 获取皇冠和彩票的余额
function get_cp_blance(cal,cal2){
    $(cal).html('加载中');
    var dat={};
    dat.action='cp_b';
    $.ajax({
        type: 'POST',
        url:'/account_api.php?_='+Math.random(),
        data:dat,
        dataType:'json',
        success:function(ret){
            if(ret.data){
                setCookieAction('member_money',ret.data.balance_hg,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('cp_user_mon',ret.data.balance_cp,1) ; // 用户彩票金额，cookie 有效期 1天
                $(cal).html(ret.data.balance_hg); // 更新导航栏皇冠余额
                $('.hg_money').html(ret.data.balance_hg); // 更新导航栏皇冠余额
                $('.user_join_day').html(ret.data.joinDays+'天') ; // 用户加入天数
                $(cal2).html(ret.data.balance_cp) ; // 彩票余额
            } else{
                $(cal).html('0.00');
            }
        },
        error:function(){
            alertComing('网络错误，请稍后重试');
        }
    });
}

// 获取皇冠和三方彩票的余额
function get_gmcp_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/gmcp/cp_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('gmcp_chess_mon',item.data.gmcp_balance,1) ; // 用户皇冠金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.gmcp_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}

// 获取 皇冠 和 ky 余额
function get_ky_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/ky/ky_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('ky_chess_mon',item.data.ky_balance,1) ; // 用户开元金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.ky_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}

// 获取 皇冠 和 皇冠棋牌 余额
function get_ff_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/hgqp/hg_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('ky_chess_mon',item.data.ff_balance,1) ; // 用户皇冠金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.ff_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}

// 获取 皇冠 和 VG棋牌 余额
function get_vg_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/vgqp/vg_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('ky_chess_mon',item.data.vg_balance,1) ; // 用户VG金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.vg_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}
// 获取 皇冠 和 快乐棋牌 余额
function get_kl_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/klqp/kl_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('kl_chess_mon',item.data.kl_balance,1) ; // 用户快乐金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.kl_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}
// 获取 皇冠 和 乐游棋牌 余额
function get_ly_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/lyqp/ly_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('ly_chess_mon',item.data.ly_balance,1) ; // 用户皇冠金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.ly_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}
// 获取 皇冠 和 MG电子 余额
function get_mg_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/mg/mg_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('mg_chess_mon',item.data.mg_balance,1) ; // 用户MG电子金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.mg_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}
// 获取 皇冠 和 CQ9电子 余额
function get_cq_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/cq9/cq9_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('cq_chess_mon',item.data.cq_balance,1) ; // 用户CQ9电子金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.cq_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}
// 获取 皇冠 和 泛亚电竞 余额
function get_avia_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/avia/avia_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data[0].hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('avia_chess_mon',item.data[0].avia_balance,1) ; // 用户泛亚电竞金额，cookie 有效期 1天
                $('.hg_money').html(item.data[0].hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data[0].avia_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}
// 获取 皇冠 和 雷火电竞 余额
function get_fire_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/thunfire/fire_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('fire_chess_mon',item.data.fire_balance,1) ; // 用户雷火电竞金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.fire_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}
// 获取 皇冠 和 MW电子 余额
function get_mw_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/mw/mw_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data[0].hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('mw_chess_mon',item.data[0].mw_balance,1) ; // 用户MW电子金额，cookie 有效期 1天
                $('.hg_money').html(item.data[0].hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data[0].mw_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}
// 获取 皇冠 和 OG视讯 余额
function get_og_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/og/og_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            // console.log(item)
            if(item.status == '200') {
                setCookieAction('member_money',item.data[0].hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('og_chess_mon',item.data[0].og_balance,1) ; // 用户OG视讯金额，cookie 有效期 1天
                $('.hg_money').html(item.data[0].hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data[0].og_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}
// 获取 皇冠 和 BBIN视讯 余额
function get_bbin_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/bbin/bbin_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            // console.log(item)
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('bbin_chess_mon',item.data.bbin_balance,1) ; // 用户BBIN视讯金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.bbin_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}
// 获取 皇冠 和 FG电子 余额
function get_fg_balance(cal) {
    $(cal).html('加载中');
    var data = {};
    data.action = 'b';
    $.ajax({
        type : 'POST',
        url : '/fg/fg_api.php?_=' + Math.random(),
        data : data,
        dataType : 'json',
        success:function(item) {
            if(item.status == '200') {
                setCookieAction('member_money',item.data.hg_balance,1) ; // 用户金额，cookie 有效期 1天
                setCookieAction('fg_chess_mon',item.data.fg_balance,1) ; // 用户FG电子金额，cookie 有效期 1天
                $('.hg_money').html(item.data.hg_balance) ; // 更新导航栏皇冠余额
                $(cal).html(item.data.fg_balance);
            } else {
                alertComing(item.describe);
            }
        },
        error:function(){
            alertComing('网络异常，请稍后重试！');
        }
    });
}

/*
 * 彩票联合登陆
 * {"status":"200","describe":"彩票登录成功","timestamp":"20180920234454","data":[{"cpUrl":"http://mc.huangguan.lcn/"}],"sign":""}
 * */
function loginLotteryAction() {
    $.ajax({
        url:  '/index_api.php' ,
        type: 'POST',
        dataType: 'json',
        data: {appRefer:'mobile'} ,
        success:function(res){
            if(res.status==200){ // 登录成功
                // cp_url_num 用于首次登录需要
                var cpUrlArr = {
                    cp_url:res.data[0].urlLogin,
                    cp_url_num:1
                };
                localStorage.setItem('cpUrlArr',JSON.stringify(cpUrlArr)) ; // 彩票登录地址
                location.href = '/';
            }
        },
        error: function () {
            alertComing('网络错误，稍后请重试!');
        }
    });
}

/*　
 * 　获取网站维护信息
 * type : mobile 整站维护
 * */
function getPageMaintenance(type,returnVal) {
    var pagestatus = 0;
    $.ajax({
        async: false,
        type: 'POST',
        url:'/api/pageMaintenanceApi.php' ,
        data:{type:type} ,
        dataType:'json',
        success:function(res){
            pagestatus = res.data.state ;
            var wh_url = '/'+tplName+'maintenance.php?type='+type+'&content='+encodeURI(res.data.content)+'&title='+encodeURI(res.data.title);
            if(res.data.state ==1){ // 维护
                if(tplName =='views/0086dj/' && (type=='avia' || type=='thunfire')) { // 电竞当前打开
                    body_dzjj.location.replace(wh_url);
                }else{
                    window.location.href = wh_url;
                }
            }

        },
        error:function(){

        }
    });
    if(returnVal){
        return pagestatus;
    }
}

function getUserBetDetail() {
    var user_bet = {};
    $.ajax({
        url: '/account/user_bet.php?appRefer=4',
        type: 'GET',
        dataType: 'json',
        //async: false,
        success: function (response) {
            if (response.status == '200') {
                user_bet = response.data;
                $('#total_bet').val(user_bet.total_bet);

                var str = '<div class="pop_all" >' +
                    '<div class="pop_bg" style="display: block; opacity: 1;"></div>' +
                    '<div id="all_pop"></div>' +
                    '<div class="Pop-up pop_close" style="display: block; opacity: 1; top: 20%; height: 60%; padding: 10px 0; background: #ffffff;"><span style="color: #999;">打码量列表</span>' +
                    '<div class="btn_close pop_cls close_event"><i class="fa fa-times"></i></div>' +
                    '<div id="pop_text" style="overflow:auto; height: 90%">' +
                    '<table width="100%" border="0" id="table_record" class="table_record">' +
                    '<thead><tr><th style="width: 50%">类别</th><th style="width: 50%">打码量</th></tr></thead>' +
                    '<tbody>';
                $.each(user_bet.bet_list, function (i, v) {
                    str += '<tr><td>' + v.msg + '</td><td>' + v.value + '</td></tr>';
                });
                str += '</tbody></table></div><p><button>总计：' + user_bet.total_bet + '</button></p></div></div>';

                $('#user_bet_list').on('click', function(){
                    $('body').append(str);
                    // 显示弹窗
                    $(".pop_bg , .Pop-up").fadeIn('400');
                    // 关闭弹窗
                    $(document).off().on("click", '.pop_bg , .close_event,.Pop-up button ', function(){
                        $(".pop_bg").fadeOut('400');
                        $(".pop_close").fadeOut('400');
                        $('.pop_all').remove();
                    });
                });

            }
        }
    });

}
