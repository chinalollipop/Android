$(document).ready(function () {
    var menu = $('.nav-area');
    var dx, dy;
    var mask = null;
    var moving = false;
    var adjust = 2;
    menu.on("mousedown", function (event) {
        event.preventDefault();
        if (!mask) {
            mask = $('<div style="position:fixed;z-index:20000;left:0;right:0;top:0;bottom:0;cursor:move;"></div>');
            mask.appendTo(document.body);
        }
        mask.show();
        var wh = $(window).height();
        var mh = menu.outerHeight();
        var ww = $(window).width();
        var mw = menu.outerWidth();
        var os = menu.offset();
        var scrollTop = $(document).scrollTop();
        var scrollLeft = $(document).scrollLeft();
        dx = mw - (event.pageX - os.left);
        dy = mh - (event.pageY - os.top);
        $(document).on("mousemove.drag", function (e) {
            if(Math.abs(event.pageX-e.pageX)>5||Math.abs(event.pageY-e.pageY)>5){
                moving = true;
                menu.css({bottom: Math.max(adjust, Math.min(wh - mh - adjust, wh - e.pageY - dy + scrollTop)) + "px", right: Math.max(adjust, Math.min(ww - mw - adjust, ww - e.pageX - dx + scrollLeft)) + "px"});
            }
        }).on("mouseleave.drag mouseup.drag", function () {
            setTimeout(function(){
                moving = false;
            }, 200);
            $(document).off("mousemove.drag");
            if (mask) {
                mask.hide();
            }
        });
    });

    $(window).resize(function () {
        var wh = $(window).height();
        var mh = menu.outerHeight();
        var ww = $(window).width();
        var mw = menu.outerWidth();
        var mb = menu.css("bottom").replace("px", "");
        var mr = menu.css("right").replace("px", "");
        menu.css({bottom: Math.max(adjust, Math.min(wh - mh - adjust, mb)) + "px", right: Math.max(adjust, Math.min(ww - mw - adjust, mr)) + "px"});
    });
});