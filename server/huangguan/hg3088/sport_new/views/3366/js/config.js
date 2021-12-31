// 导航 hover
function indexHoverNav(){
    $('.nav-drop-ac').hover(function () {
        $(this).find('.nav-drop').stop(true).animate({'height': '143px'});
    },function () {
        $(this).find('.nav-drop').stop(true).animate({'height': '0'});
    })
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
// 设为首页
function setHome(obj){
    var url = window.location;
    try{
        obj.style.behavior='url(#default#homepage)';
        obj.setHomePage(url);
    }catch(e){
        if(window.netscape){
            try{
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }catch(e){
                alert("抱歉，此操作被浏览器拒绝！\n\n请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为'true'");
            }
        }else{
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动将【"+url+"】设置为首页。");
        }
    }
}

