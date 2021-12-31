$(function(){
    setInterval("changeColor('cclo','yellow|red|blue')",500);

    if (document.addEventListener) {
        document.addEventListener("keypress", fireFoxHandler, true);
    } else  {
        document.attachEvent("onkeypress", ieHandler);
    }

    $('.lang').click(function(){
        if($('.Language_fname').css('display') == 'none'){
            $('.Language_fname').css('display','block');
            $('.service_fname').css('display','none');
        }else{
            $('.Language_fname').css('display','none');
        }
    })
    $('.service').click(function(){
        if($('.service_fname').css('display') == 'none'){
            $('.service_fname').css('display','block');
            $('.Language_fname').css('display','none');
        }else{
            $('.service_fname').css('display','none');
        }
    })
})

// 导航 hover
function indexHoverNav(){
    $("li.LS-live, li.nav-ele, li.nav-lot, li.nav-sports,li.LS-es").subTabs();
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

function changeColor(id, color) {
    color = color.split("|");
    if(document.getElementById(id)){
        document.getElementById(id).style.color = color[parseInt(Math.random() * color.length)];
    }
}

function fireFoxHandler(evt){
    if (evt.which== 13)   {
        inputCheck();
    }
}
function ieHandler(evt)   {
    if (evt.keyCode == 13)   {
        inputCheck();
    }
}

/*
 **inDelay: 顯示前延遲
 **outDelay: 隱藏前延遲
 **showTime: 動畫時間
 **sub: 子選單區塊
 **clearTab: 隱藏所有子選單區塊
 **inTab: 顯示
 **outTab: 隱藏
 **clickTab: 點擊顯示
 */
$.fn.subTabs = function(options) {
    var conf = {
        "inDelay": 400,
        "outDelay": 600,
        "showTime": 300,
        "notOver": 1 //防止超出版面
    };

    $.extend(conf, options);

    return this.each(function(){
        var _o = $(this);
        var tClass = _o.attr("class").split(' ')[0];
        var sub = $("div[class=" + tClass+']');
        var targetWid = _o.width();
        var posX =  _o.position().left;
        var moveVal = (posX - (sub.width() - targetWid) / 2) - _o.parent().position().left - 8;

        var tout , tin;

        //移除title
        $(this).find('a').removeAttr('title');
        sub.find('a').removeAttr('title');

        if(moveVal < 0 && conf.notOver == 1) {
            moveVal = 0;
        }

        if (conf.left != undefined) {
            moveVal = parseInt(conf.left) - parseInt(sub.width() / 2);
        }

        //2012.09.28 新增垂直參數設定
        if(conf.posTop) {
            sub.css("top", conf.posTop);
        }

        sub.css("left", moveVal);
        sub.hide();

        $("." + tClass).hover(function(){
            clearTimeout(tout);
            tin = setTimeout(function(){ inTab(); }, "400");
        }, function(){
            clearTimeout(tin);
            tout = setTimeout(function(){ outTab(); }, "400");
        });

        _o.bind("click", function(){
            if(sub.is(":visible")){
                clickTab();
                //return false;
            }else{
                clickTab();
            }
        });

        function clearTab (){
            sub.parent().find("div").hide();
        }

        function inTab(){
            sub.stop(true, true).fadeIn(conf.showTime);
            //position();
        }

        function outTab(){
            sub.stop(true, true).fadeOut(conf.showTime);
        }

        function clickTab(){
            clearTab();
            sub.stop(true, true).fadeIn(conf.showTime);
        }

        function position(){
            var m = parseInt(conf.left) - parseInt(sub.width() / 2);
            sub.css("left", m + "px");
        }
    });
};


