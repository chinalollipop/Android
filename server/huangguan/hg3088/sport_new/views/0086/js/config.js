
// 导航 hover
function indexHoverNav(){
    $('.nav-drop-ac').hover(function () {
        $(this).find('.nav-drop').stop(true).animate({'height': '78px'});
    },function () {
        $(this).find('.nav-drop').stop(true).animate({'height': '0'});
    })
}

// 右侧底部在线客服
function rightBottomKf(){
    var $body = $('body') ;
    $('body').find('.whkf_off').mouseover(function () {
        $body.find('.wc_logo').stop().animate({'right':'10px'});
    }).mouseout(function () {
        $body.find('.wc_logo').stop().animate({'right':'-160px'});
    });
}

// 游戏图片高度处理 rate : 0.686
function indexGameHeight(rate) {
    var needh = Number($('.cms_hl_col').width())*rate || Number($('.upmain').width())*rate ;
    // console.log(needh) ;
    $('.cms_hlredbg').css('height',needh);
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
function checkTime(num){ //将0-9的数字前面加上0，例1变为01
    if(isNaN(num)){
        num=0;
    }
    return num < 10 & num >= 0 ? '0' + num : num;
}