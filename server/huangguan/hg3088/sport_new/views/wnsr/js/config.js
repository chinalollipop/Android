
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