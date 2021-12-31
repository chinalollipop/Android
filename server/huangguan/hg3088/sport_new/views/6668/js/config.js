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