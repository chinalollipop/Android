/* $Id : common.js 4865 2007-01-31 14:04:10Z paulgao $ */

/*
2018新增开始
* */

addRightKf();

// 设置cookie
function setCookieAction(theName,theValue,theDay){
    if((theName != "")&&(theValue !="")){
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
    return false;
};
// 删除cookie
function delCookieAction(theName){
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookieAction(theName);
    if(cval!=null){
        document.cookie= theName + "='';path=/;expires="+exp.toGMTString();
    }

};



function getAttr(cat_id) {
    var tbodies = document.getElementsByTagName('tbody');
    for (i = 0; i < tbodies.length; i ++ ) {
        if (tbodies[i].id.substr(0, 10) == 'goods_type')tbodies[i].style.display = 'none';
    }

    var type_body = 'goods_type_' + cat_id;
    try {
        document.getElementById(type_body).style.display = '';
    } catch (e) {

    }
}

/* *
 * 四舍五入保留小数
 * num 保留几位
 */
function advFormatNumber(value, num) {
    var a_str = formatNumber(value, num);
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
            a_str = formatNumber(a_int + bonus_int, num)
        }
    }
    return a_str;
}

function formatNumber(value, num){
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
}


function hash(string, length) {
    var length = length ? length : 32;
    var start = 0;
    var i = 0;
    var result = '';
    filllen = length - string.length % length;
    for(i = 0; i < filllen; i++) {
        string += "0";
    }
    while(start < string.length) {
        result = stringxor(result, string.substr(start, length));
        start += length;
    }
    return result;
}

function stringxor(s1, s2) {
    var s = '';
    var hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var max = Math.max(s1.length, s2.length);
    for(var i=0; i<max; i++)
    {
        var k = s1.charCodeAt(i) ^ s2.charCodeAt(i);
        s += hash.charAt(k % 52);
    }
    return s;
}

var evalscripts = new Array();
function evalscript(s) {
    if(s.indexOf('<script') == -1) return s;
    var p = /<script[^\>]*?src=\"([^\>]*?)\"[^\>]*?(reload=\"1\")?(?:charset=\"([\w\-]+?)\")?><\/script>/ig;
    var arr = new Array();
    while(arr = p.exec(s)) appendscript(arr[1], '', arr[2], arr[3]);
    return s;
}
// 向页面添加js
function appendscript(src, text, reload, charset) {
    var id = hash(src + text);
    if(!reload && in_array(id, evalscripts)) return;
    if(reload && document.getElementById(id))
    {
        document.getElementById(id).parentNode.removeChild(document.getElementById(id));
    }
    evalscripts.push(id);
    var scriptNode = document.createElement("script");
    scriptNode.type = "text/javascript";
    scriptNode.id = id;
    //scriptNode.charset = charset;
    try
    {
        if(src)
        {
            scriptNode.src = src;
        }
        else if(text)
        {
            scriptNode.text = text;
        }
        document.getElementById('append_parent').appendChild(scriptNode);
    }
    catch(e)
    {}
}

function in_array(needle, haystack) {
    if(typeof needle == 'string' || typeof needle == 'number')
    {
        for(var i in haystack)
        {
            if(haystack[i] == needle)
            {
                return true;
            }
        }
    }
    return false;
}

//数字验证 过滤非法字符
function clearNoNum(obj){
    //先把非数字的都替换掉，除了数字和.
    obj.value = obj.value.replace(/[^\d.]/g,"");
    //必须保证第一个为数字而不是.
    obj.value = obj.value.replace(/^\./g,"");
    //保证只有出现一个.而没有多个.
    obj.value = obj.value.replace(/\.{2,}/g,".");
    //保证.只出现一次，而不能出现两次以上
    obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
    if(obj.value != ''){
        var re=/^\d+\.{0,1}\d{0,2}$/;
        if(!re.test(obj.value))
        {
            obj.value = obj.value.substring(0,obj.value.length-1);
            return false;
        }
    }
}

//是否是中文
function isChinese(str){
    return /[\u4E00-\u9FA0]/.test(str);
}

// 跳转到指定链接
function url(u){
    window.location.href=u;
}

// 金额快速选取
function change(money){
    var $mon = $('.fast_choose');
    var val = $mon.val() ;
    if(val == ''){
        val = 0;
    }
    $mon.val( parseInt(money) + parseInt(val) );
}


// 美东时间设置
function setAmerTime(el) {
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
    if(el =='#ymd_date'){
        $(el).val(y+"-"+m+"-"+d+" "+h+":"+mm); // 只到分
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
    if(isNaN(i)){
        i=0;
    }
    if (i<10) { i="0" + i ;}
    return i ;
}

// 增加右侧客服

function addRightKf() {
    var $body = $('body') ;
    var str ='';
    if(top.red_pocket_type && top.tplfilename=='0086'){ // 0086 2021新年活动，需要配置开关
        str +='   <div class="new_year_con" style="cursor:pointer;position:fixed;bottom:0;right:0;z-index:20;width: 360px;height: 453px;background: url(/images/hongbao/newy_btn.png) no-repeat;background-size: 100%;">' +
            '        <a class="close_new_year" onclick="$(this).parent().hide(200)" style="display: block; position: absolute; width: 40px; height: 40px; right: 45px; top: 32px;"></a>' +
            '        <a class="to_promos" href="/tpl/promos.php?prokey=newyear_hb" target="body" data-keys="newyear_hb" style="display: block;height: 80%;width: 100%;margin-top: 100px;"></a>' +
            '        <div class="new_year_time" style="font-size:14px;text-align:center;width: 100%;height: 40px;line-height:40px;position: absolute;bottom: 64px;color: #c30202;">红包活动开启中</div>' +
            '    </div>';
    }
    if(top.red_pocket_type && top.tplfilename=='6668'){ // 6668 2021新年活动，需要配置开关
        str +='<div class="new_year_con" style="cursor:pointer;position:fixed;bottom:0;right:0;z-index:20;width: 330px;height: 360px;background: url(/images/hongbao/newy_btn_6668.gif) no-repeat;background-size: 100%;">' +
            '<a class="close_new_year" onclick="$(this).parent().hide(200)" style="display: block; position: absolute; width: 40px; height: 40px; right: 13px; top: 14px;"></a>' +
            '<a class="to_promos_details" target="body" href="/tpl/allpictem.php?showbg=bg&to=7&title=&api=/app/member/activity/newyear2021HbApi.php&keys=/images/hongbao/hb_bg_6668.jpg&flag=newyear_hb" style="display: block;height: 80%;width: 100%;margin-top: 60px;"></a>' +
            '</div>';
    }
    str += '<div  class="kf_right" style="position: fixed;bottom: 10px;right: 1px;z-index: 11;">' ;
    if(top.tplfilename=='0086'){
        str += '<a href="'+top.configbase.service_meiqia+'" target="_blank" class="zxkf_sec" style="position:absolute;z-index:5;right:-77px;bottom: 45px;display:block;height:30px;width:110px;background:url(../../../images/right_online_cs.png) ;background-size: 100%; "> </a>';
    }else{
        str += '<div class="kf_less_all" style="position:absolute;font-size:14px;text-align: center;right:-170px;bottom: 0;">' +
            '<a href="javascript:;" class="zxkf"  style="position:relative;z-index:6;float:left;display:block;height:105px;width:30px;background:url(../../../images/phonecallback/chat-btn.gif) ;background-size: 100%; "> </a>' +

            '<div class="kf_ess"  style="position:relative;z-index: 6;padding: 20px 0;margin-left: 30px;height:320px;width:168px;background: #b8a684;background: -moz-linear-gradient(top, #b8a684 0%, #ffffff 100%); background: -webkit-linear-gradient(top, #b8a684 0%, #ffffff 100%); background: -webkit-gradient(linear, left top, left bottom, from(#b8a684), to(#ffffff)); background: -o-linear-gradient(top, #b8a684 0%, #ffffff 100%); background: linear-gradient(to bottom, #b8a684 0%, #ffffff 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#b8a684\', endColorstr=\'#ffffff\',GradientType=0 );">' +
            '<img src="../../../images/phonecallback/live_chat.png" class="live-chat" style="display: block;margin: 0 auto;">'+
            '<a href="'+ top.configbase.service_meiqia +'" class="chat_btn" target="_blank" style="display:block;border-radius: 3px;margin:5px auto;width: 96px;height: 36px;line-height:36px;color:#fff;background: #564031;" onmouseover="this.style.color=\'#FDAE03\'" onmouseout="this.style.color=\'#fff\'">在线咨询</a>'+
            '<a class="phone_call_btn" href="javascript:;" style="display:block;border-radius: 3px;margin:5px auto;width: 96px;height: 36px;line-height:36px;color:#fff;background: #564031;" onmouseover="this.style.color=\'#FDAE03\'" onmouseout="this.style.color=\'#fff\'">电话回拨</a>'+
            '<div class="contact_title" style="height: 30px;line-height: 30px; background: #cab898; color: #FFF;">' +
            '                    <p class="home_custom" style="margin:0;background: url(../../../images/phonecallback/nav2-icon.png) no-repeat; background-position: -75px 1px;">服务热线</p>' +
            '                    <img src="../../../images/phonecallback/down_icon.png" class="down_icon" style="margin-top: -1px;">' +
            '                    <p class="hongKongParent" style="line-height: 16px;margin: 10px 20px 10px;padding-bottom: 15px;font-size: 12px;color: #564031;border-bottom: 1px solid #e2dbd4;">' +
            '                        <span style="display:block;color: #ae8a48;">菲律宾客服电话</span>' +
            '                        <ss class="hongKong1">'+top.configbase.service_phone_phl+'<br></ss>' +
            '                    </p>' +
            '                    <p style="line-height: 16px;font-size: 12px;color: #564031;">' +
            '                        <span style="display:block;color: #ae8a48;">投诉电话</span>' +
            '                        '+ top.configbase.service_phone_phl +'' +
            '                    </p>' +
            '                </div>'+
            '</div> '+
            '</div>';

    }
    str += '<a href="javascript:;" class="whkf whkf_off" style="position:absolute;z-index:5;right:-77px;bottom: '+(top.tplfilename=='0086'?10:160)+'px;display:block;height:30px;width:110px;background:url(../../../images/right_wechat_cs.png) ;background-size: 100%;"> </a>' +
        '<div class="wc_logo" style="position:absolute;right:-145px;bottom: '+(top.tplfilename=='0086'?0:150)+'px;height:142px;width:140px;background:#c5740f;"> ' +
        '<p style="margin: 0;text-align: center;color: #fff;font-size: 14px;">扫一扫</p>'+
        '<span style="display:block;height:100px;width:100px;margin:0 auto;border: 2px solid #fff;background:url('+top.webPicConfig.server_wechat_code+') no-repeat ;background-size: 100%;"> </span>'+
        '<p style="margin: 0;text-align: center;color: #fff;font-size: 14px;">微信客服</p>'+
        '</div>' ;
    '</div>' ;

    str += '<div class="login-pop-up4 " style="display:none;font-size: 14px;position: absolute;width: 396px;height: 130px;right:170px;bottom:57px;background: #f3f3f3;border-radius: 10px;padding: 37px 15px 8px 0px;">' +
        '<div class="left" style="float: left;margin: -5px 0 0 34px;">' +
        '<img src="../../../images/phonecallback/3mins_add_1.png" class="three-mins">' +
        '</div>' +
        '<div style="float: left;width: 212px;margin-left: 22px;"> ' +
        '<p class="title" style="font-size: 15px;color: #564031;margin: -3px 0 10px;">24小时电话客服为您致电服务</p>' +
        '<div id="phoneCallBackForm" class="form-group">' +
        '<input type="text" id="callBackPhoneNo" name="callBackPhoneNo" minlength="11" maxlength="11" placeholder="请输入您的手机号码" aria-required="true" style="padding: 0; border-radius: 3px;background:#fff;font-size: 13px;border: 1px solid #ccc;width: 178px; height: 32px;line-height: 32px;padding-left: 8px;color: #564031;">' +
        '</div>' +
        '<a href="javascript:;" class="call-back-btn" style="display:inline-block;width: 180px;height: 34px;line-height:34px;text-align:center;background-color: #be4822;color: #f3f3f3;border-radius: 4px; margin: 10px 0;">客服回拨</a>' +
        '<img src="../../../images/phonecallback/fubao-close-1.png" class="login4-close-popup close-btn" style="position: absolute;top: 3px;right: 2px;cursor: pointer;">' +
        '</div>'+
        '</div> ';

    $body.append(str) ;

    $body.find('.zxkf_sec').mouseover(function () {
        $(this).animate({'right':'0'});
    }).mouseout(function () {
        $(this).animate({'right':'-77px'});
    })

    $body.find('.zxkf').toggle(
        function(){
            $body.find('.kf_less_all').stop().animate({'right':'0'});},
        function(){
            $body.find('.kf_less_all').stop().animate({'right':'-170px'});}
    );
    $body.find('.phone_call_btn').on('click',function () { // 显示回拨
        $('.login-pop-up4').show();
    });
    $body.find('.close-btn').on('click',function () { // 隐藏回拨
        $('.login-pop-up4').hide();
    });

    /*
    *  回拨电话
    * */
    var submitphoneflage = false ;
    $body.find('.call-back-btn').on('click',function () {
        if(submitphoneflage){
            return false ;
        }
        var tel = /^1[3|4|5|6|7|8|9|][0-9]{9}$/;
        var value = $body.find('#callBackPhoneNo').val() ;
        var ajaxurl = '../../../app/member/api/phonecallApi.php' ;
        if(!tel.test(value)){
            alert('请输入正确手机号码');
            return false ;
        }
        submitphoneflage = true ;
        $.ajax({
            type: 'POST',
            url: ajaxurl ,
            data: {userPhone:value} ,
            dataType:'json',
            success:function(res){
                if(res){
                    alert(res.describe) ;
                    submitphoneflage = false
                }

            },
            error:function(){
                submitphoneflage = false
            }
        });


    });


    $body.find('.whkf_off').mouseover(function () {
        $body.find('.wc_logo').stop().animate({'right':'38px'});
    }).mouseout(function () {
        $body.find('.wc_logo').stop().animate({'right':'-145px'});
    });

    if(top.red_pocket_type && top.tplfilename=='0086') { // 0086 2021新年活动，需要配置开关
        setTimerAc('.new_year_time'); // 需要判断是否开启活动
    }

}

// cal 容器 new_year_time
function setTimerAc(cal,year,month,day,hour,minute,second){
    var leftTime = (new Date(year,month-1,day,hour,minute,second)) - (new Date()); //计算剩余的毫秒数
    var days = parseInt(leftTime / 1000 / 60 / 60 / 24 , 10); //计算剩余的天数
    var hours = parseInt(leftTime / 1000 / 60 / 60 % 24 , 10); //计算剩余的小时
    var minutes = parseInt(leftTime / 1000 / 60 % 60, 10);//计算剩余的分钟
    var seconds = parseInt(leftTime / 1000 % 60, 10);//计算剩余的秒数
    days = checkTime(days);
    hours = checkTime(hours);
    minutes = checkTime(minutes);
    seconds = checkTime(seconds);
    var timer_in,timer_in_1;
    var str_1 = '红包活动开启中';
    if(seconds>=0){
        timer_in = setTimeout("setTimerAc('.new_year_time','2021','02','11','00','00','00');",1000); // 领取开始时间 如 2021,02,11,00,00,00, 这里是北京时间
        str_1 = days+"天" + hours+"时" + minutes+"分"+seconds+"秒"; // 侧边区域
        if(cal=='.new_year_time_de'){ // 活动内页
            timer_in_1 = setTimeout("setTimerAc('.new_year_time_de','2021','02','12','23','59','59');",1000); // 领取结束时间
            $('.timer_d').html('你目前还剩下 '+days+' 天时间');
            str_1 = '剩余时间<span class="timer_d">'+days+'</span>天' +
                '<span class="timer_h">'+hours+'</span>时' +
                '<span class="timer_m">'+minutes+'</span>分' +
                '<span class="timer_s">'+seconds+'</span>秒';
        }
    }else {
        clearTimeout(timer_in);
        clearTimeout(timer_in_1);
        if(cal=='.new_year_time_de'){ // 活动内页
            $('.newyear_hby_btn').hide();// 召唤红包雨按钮
            $('.timer_d').html('你目前还剩下 '+days+' 天时间');
            str_1 = '剩余时间<span class="timer_d">00</span>天' +
                '<span class="timer_h">00</span>时' +
                '<span class="timer_m">00</span>分' +
                '<span class="timer_s">00</span>秒';
        }

    }
    $(cal).html(str_1);
}

// 第三方彩票接口，查询和转账
function getUserBanlance(uid,type,data){
    if(!uid){ // 未登录
        return ;
    }
    // if(userTestFlag == 1){ // 试玩账号不获取第三方余额
    //     return false;
    // }
    var url ;
    var $hgmoney = $('.hgmoney') ;
    var $objtxt ;
    switch (type){
        case 'gmcp':
            url = '/app/member/gmcp/cp_api.php';
            $objtxt = $('.gmcpmoney') ;
            break;

    }
    if(!data){ // 默认查询余额
        data = {uid:uid,action:'b'};
    }
    $.ajax({
        type : 'POST',
        url : url ,
        data : data,
        dataType : 'json',
        success:function(res) {
            //console.log(res)
            if(res.code=='200'){
                if(res.data){
                    if(res.data.gmcp_balance){ // 三方彩票
                        $hgmoney.html(res.data.hg_balance).attr('title',res.data.hg_balance);
                        $objtxt.html(res.data.gmcp_balance).attr('title',res.data.gmcp_balance);
                    }
                }else{
                    $objtxt.html('0.00').attr('title','0.00');
                }

            }else{
                $objtxt.html('0.00').attr('title','0.00');
                alert(res.message);
            }

        },
        error:function(){
            alert('网络异常');
        }
    });

}

