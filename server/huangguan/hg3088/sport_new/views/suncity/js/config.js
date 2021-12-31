
// 威尼斯人滚动客服
function kfRunning(){
    $(window).scroll(function(){
        // var scroll_top = $(window).scrollTop();
        var scroll_top = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
        //console.log(scroll_top);
        var normal = 140;
        var change = normal+Number(scroll_top);
        //console.log(change);
        $('.flowLeft,.flowRight').css({'top':change,'transition':'all .6s'});
    });
}

// 牌照展示
function showLicense(){
    $('.pz_content').show(); // 显示牌照
    $('.coverflow-item').mouseover(function () {
        $(this).prev().attr('checked','checked');
    })
}

// 导航 hover
function indexHoverNav(){
    $('.nav-drop-ac').hover(function () {
        $(this).find('.nav-drop').show();
    },function () {
        $(this).find('.nav-drop').hide();

    })
}

// 首页顶部滚动
function indexTopBanner() {
    var lb_speed = 25;
    var colee2=document.getElementById("colee2");
    var colee1=document.getElementById("colee1");
    var colee=document.getElementById("colee");
    colee2.innerHTML=colee1.innerHTML; //克隆colee1为colee2
    // console.log(colee.clientHeight)
    var gdLbInter=setInterval(Marquee1,lb_speed)//设置定时器
    function Marquee1(){
        //当滚动至colee1与colee2交界时
        if(colee2.offsetTop-colee.scrollTop<=0){
            clearInterval(gdLbInter);
            colee.scrollTop-=colee1.offsetHeight; //colee跳到最顶端
        }else if(colee.scrollTop== colee.clientHeight){ // 循环滚动 clearInterval(gdLbInter);
            clearInterval(gdLbInter);
            colee.scrollTop =0;
        }else{
            colee.scrollTop ++;
        }
        if(colee.scrollTop %40 ==0){ // 等于当前图片高度
            clearInterval(gdLbInter);
            setTimeout(function () { // 停留一秒
                gdLbInter=setInterval(Marquee1,lb_speed)
            },1000)
        }
        //console.log(colee.scrollTop)
    }

    //鼠标移上时清除定时器达到滚动停止的目的
    // colee.onmouseover=function() {clearInterval(gdLbInter)}
    // //鼠标移开时重设定时器
    // colee.onmouseout=function(){gdLbInter=setInterval(Marquee1,lb_speed)}
}

// 太阳城右侧底部在线客服
function rightBottomKf(){
    var $body = $('body') ;
    $body.find('.turn-status').toggle(function () {
            $('.service-btn').css('transform', 'translateX(0)')
        },
        function(){
            $('.service-btn').removeAttr('style')
        })

    $body.find('.whkf_off').mouseover(function () {
        $body.find('.wc_logo').stop().animate({'right':'10px'});
    }).mouseout(function () {
        $body.find('.wc_logo').stop().animate({'right':'-160px'});
    });

}