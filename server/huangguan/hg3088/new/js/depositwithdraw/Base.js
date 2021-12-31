$(function () {
    //if (location.host.split(".").length == 2) {
    //    var a = location.host;
    //    window.location = 'http://www.' + a;
    //    return;
    //}

    $(document).on("click", ".LiveChat", function () {
        if (isLogin == 'True') {
            var url = String.format('/{0}/Live800/Live800SetQuestion', lang);
            OpenFancybox(url, 730);
        } else {
            LiveChat();
        }
    });

    $(document).on("click", ".ContactUs", function () {
        ContactUs();
    });

    $(document).on("click", ".ContactUsBox", function () {
        ContactUs();
    });

    $(document).on("click", ".ResponsibleGame", function () {
        ResponsibleGame();
    });

    //登入會員資訊欄變化
    var userNameWith = $(".icon_cls").outerWidth(true) + $(".psl-name").outerWidth() + $(".lgo").outerWidth() + 5;
    var userContrlWith = $(".icon_cls").outerWidth(true) + $(".psl-info").outerWidth() + $(".lgo").outerWidth() + 5;
    $(".psl-name").css('display', 'none');
    $(".info-customer").find(".icon_cls").addClass("icon_opn");
    $(".openMenu").click(function () {
        if ($(".psl-name:hidden").length) {
            $(".info-customer").children(".btn4").animate({ width: userNameWith });
            $(".icon_cls").removeClass('icon_opn');
            $(".psl-info").css('display', 'none');
            $(".psl-name").css('display', 'block');
        } else {
            $(".info-customer").children(".btn4").animate(
            { width: userContrlWith },
            { complete: function () { $(".psl-info").css('display', 'block'); } }//執行完動畫才跑出欄位
            );
            $(".icon_cls").addClass('icon_opn');
            $(".psl-name").css('display', 'none');
        }
    });

    //語系選擇視窗
    $(".lg").click(function () {
        if ($(this).children(".lg-select:hidden").length) {
            $(this).children(".lg-select").slideDown();
            MaskAppend("languageMask");
        } else {
            $(this).children(".lg-select").slideUp();
            MaskRemove("languageMask");
        }
    });

    $(document).on('click', '.languageMask', function () {
        $(".lg").children(".lg-select").slideUp();
        MaskRemove("languageMask");
    });


    //回到頁面最頂端
    //$("body").append("<div class='goTop'></div>");
    $(".goTop").click(function () {
        $("html, body").animate({
            scrollTop: "0"
        }, 1000);
    });
    $(window).scroll(function () {
        var window_scrollTop = $(this).scrollTop();
        if ($(this).scrollTop() > 300) {
            $(".goTop").fadeIn("fast");
        } else {
            $(".goTop").stop().fadeOut("fast");
        }
    });

    //菜單欄動作
    $(".nav-items ul li").click(function () {
        $(this).parents().find("li a").removeClass("active");
        $(this).children("a").addClass("active");
    });

    //圖型漸變效果
    $(".ahover").bind("mouseover", function () {
        $(this).stop().animate({
            opacity: "1"
        });
    });
    $(".ahover").bind("mouseout", function () {
        $(this).stop().animate({
            opacity: ".5"
        });
    });

    //按鈕圓角，需載入jquery.corner.js
    $(".btn1").corner("left 20px");
    $(".btn2").corner("right 20px");
    $(".btn3").corner("16px");
    $(".btn4").corner("20px");
    $(".btn5").corner("20px");
    $(".newText").corner("10px");
    $(".help").corner("10px");

    //check圖片改變
    $(".check").click(function () {
        if ($(this).hasClass("checked")) {
            $(this).removeClass('checked');
        } else {
            $(this).addClass('checked');
        }
    });
});

AddAntiForgeryToken = function (data) {
    data.__RequestVerificationToken = $('#__AjaxAntiForgeryForm input[name=__RequestVerificationToken]').val();
    return data;
};


function OpenFancyboxNoClose(href, width, height, options) {
    var autoSize = true;
    if (height != null)
        autoSize = false;
    var setting = {
        href: href,
        type: 'iframe',
        closeBtn: false,
        width: width,
        maxHeight: height,
        autoSize: autoSize,
        padding: 0,
        iframe: {
            scrolling: 'no'
        },
        helpers: {
            overlay: {
                closeClick: false,
            }
        },
    };
    setting = $.extend(setting, options);
    $.fancybox.open(setting);
};

function OpenFancybox(href, width, height, options) {
    var autoSize = true;
    if (height != null)
        autoSize = false;
    var setting = {
        href: href,
        type: 'iframe',
        width: width,
        maxHeight: height,
        autoSize: autoSize,
        padding: 0,
        iframe: {
            scrolling: 'no'
        },
        helpers: {
            overlay: {
                closeClick: false,
            }
        },
        onUpdate: function () {
            var iheight = $(".fancybox-iframe").contents().find("#wdsa").outerHeight(true);
            $(".fancybox-inner").height(iheight);
        },
    };
    setting = $.extend(setting, options);
    $.fancybox.open(setting);
};


function OpenFancyboxAlert(href, options) {
    var autoSize = true;
    var setting = {
        href: href,
        type: 'iframe',
        width: 470,
        maxHeight: 500,
        autoSize: autoSize,
        padding: 0,
        iframe: {
            scrolling: 'no'
        },
        helpers: {
            overlay: {
                closeClick: false,
            }
        },
    };
    setting = $.extend(setting, options);
    $.fancybox.open(setting);
};

function OpenAlertBox(QueryString, ShowBg) {
    if (ShowBg) {
        $.fancybox.open({
            href: String.format('/{0}/Base/AlertBox?{1}', lang, QueryString),
            type: 'iframe',
            closeBtn: false,
            width: 390,
            height: 220,
            autoSize: false,
            padding: 0,
            iframe: {
                scrolling: 'no'
            },
            helpers: {
                overlay: {
                    closeClick: false,
                }
            }
        });
    }
    else {
        $.fancybox.open({
            href: String.format('/{0}/Base/AlertBox?{1}', lang, QueryString),
            type: 'iframe',
            closeBtn: false,
            width: 390,
            height: 220,
            autoSize: false,
            padding: 0,
            iframe: {
                scrolling: 'no'
            },
            helpers: {
                overlay: null
            }
        });
    }
}

//Ajax載入畫面
GetView = function (actionPath, View, Async, IsntBlock) {
    if (Async == null)
        Async = true;
    if (IsntBlock == null)
        dontBlock = true;
    else
        dontBlock = IsntBlock;
    $.ajax({
        type: 'GET',
        url: actionPath,
        data: {},
        cache: false,
        async: Async,
        success: function (data) {
            $(View).html(data);
        },
        error: function (data, error) {
            ;
        }
    });
};

//Ajax載入畫面後設定高度
GetViewSetH = function (actionPath, View, Async, IsntBlock) {
    if (Async == null)
        Async = true;
    if (IsntBlock == null)
        dontBlock = true;
    else
        dontBlock = IsntBlock;
    $.ajax({
        type: 'get',
        url: actionPath,
        data: {},
        cache: false,
        async: Async,
        success: function (data) {
            $(View).html(data);
            SetMenuHeight();
        },
        error: function (data, error) {
        }
    });
};

function HtmlCheck(value) {
    if (JContains(value, '<') || JContains(value, '>'))
        return false;
    var reg = /(<([^>]+)>)/ig;
    return !reg.test(value);
}

function JContains(value, contains) {
    return value.indexOf(contains) >= 0;
}

function LiveChat() {
    var url = String.format('/{0}/Live800/Live800', lang);
    window.open(url, 'Live800', 'menubar=no,status=no,scrollbars=no,location=no,resizable=no,top=50,left=100,toolbar=no,width=500,height=658');
}

function ContactUs() {
    var url = String.format('/{0}/About/Index?CurrentMenu=v2-ContactUs&Menu=v2-ContactUs&Item=div_AMenu5&Index=1', lang);
    window.location.href = url;
}

function ResponsibleGame() {
    var url = String.format('/{0}/About/Index?CurrentMenu=v2-ResponsibleGaming&Menu=v2-ResponsibleGaming&Item=div_AMenu2&Index=1', lang);
    window.location.href = url;
}


//JQ-String.format
String.format = function () {
    if (arguments.length == 0)
        return null;
    var str = arguments[0];
    for (var i = 1; i < arguments.length; i++) {
        var re = new RegExp('\\{' + (i - 1) + '\\}', 'gm');
        str = str.replace(re, arguments[i]);
    }
    return str;
};

//一進來先focus第一個欄位
FocusFirst = function (obj) {
    setTimeout(function () {
        obj.focus();
    }, 1000);
}

//手機號碼開頭0捨去
ContactFormat = function (obj) {
    var Value = obj.val();
    if (Value.charAt(0) == 0) {
        Value = Value.substring(1, Value.length);
        obj.val(Value);
    }
};

//設定個人資訊頁動作
SetUserInfoSession = function (UserInfoSubMenu, PageAction) {
    var actionPath = String.format('/{0}/Member/UserInfo/SetUserInfoSession', lang);
    var dataString = { UserInfoSubMenu: UserInfoSubMenu, PageAction: PageAction };
    dontBlock = false;
    $.ajax({
        type: 'POST',
        url: actionPath,
        data: dataString,
        dataType: 'json',
        cache: false,
        success: function (data) {
            if (data == "1") {
                window.top.location.href = String.format('/{0}/Member/UserInfo/Index', lang);
            }
        },
        error: function (data, error) {
        }
    });
}

//設定財務中心頁動作
SetFundsSession = function (FundsSubMenu, PageAction) {
    var actionPath = String.format('/{0}/Funds/PaymentAgent/SetFundsSession', lang);
    var dataString = { FundsSubMenu: FundsSubMenu, PageAction: PageAction };
    dontBlock = false;
    $.ajax({
        type: 'POST',
        url: actionPath,
        data: dataString,
        dataType: 'json',
        cache: false,
        success: function (data) {
            if (data == "1") {
                window.top.location.href = String.format('/{0}/Funds/PaymentAgent/Index', lang);
            }
        },
        error: function (data, error) {
        }
    });
}

//設定Menu Active
SubMenuActive = function (obj) {
    $(".menu").children(".active").find("ul").show();
    $(".mn1").find(".submenu").hide();
    $(".mn1").click(function () {
        $(".mn1").removeClass('active');
        $(".mn1sub").removeClass('active');
        $(this).addClass("active");
        $(".submenu").slideUp("fast");
    })

    //整個畫面只能有一個子Menu的選單,不然會有bug
    $(".mn1sub").click(function () {
        $(".mn1").removeClass('active');
        $(".mn1sub").removeClass('active');
        $(this).addClass("active");
        $(this).find(".submenu").slideDown("fast");
        SetMenuHeight();
    })
}

//設定畫面高度
SetMenuHeight = function () {
    $(".menu").removeClass("absl");
    $(".mc-rtct").removeClass("absl");
    if ($(".menu").height() > $(".mc-rtct").height()) {
        $(".mc-rtct").addClass("absl");
        $(".menu").removeClass("absl");
    }
    else {
        $(".mc-rtct").removeClass("absl");
        $(".menu").addClass("absl");
    }
}

//下拉動作共用
function SltAction() {
    $(".slt").find(".slt-ct").hide();
    $(".slt-ct").css('z-index', '12');
    $(".slt").each(function () {
        if ($(this).find('.slt-select').length == 0) {
            var padding = $(this).css('padding');
            $(this).prepend(String.format('<div class="slt-select" style="position: absolute;left: 0;top: 0;padding:{0};z-index: 10;bottom: 0;right: 0;"></div>', padding));

            $(this).find(".slt-select").on("click", function () {
                if ($(this).siblings(".slt-ct").css("display") == "block") {
                    $(this).siblings(".slt-ct").slideUp(400);
                    MaskRemove('maskselect');
                    return;
                }
                $(this).siblings(".slt-ct").slideDown(400);
                MaskAppend('maskselect', 'slt');
            });
        }
    })
    $(".slt-ct").click(function () {
        $(this).slideUp(400);
        MaskRemove('maskselect');
    });
    $(document).on('click', '.maskselect', function () {
        if ($(this).data("type") == 'slt')
            $(".slt-ct").slideUp(400);
        MaskRemove('maskselect');
    });
};

//下拉選擇共用
function SltBind(li_id, sel_id) {
    $(document).on("click", "." + li_id, function () {
        SltBindEvent(li_id, sel_id, $(this));
    });
}
function SltBindEvent(li_id, sel_id, obj) {
    $("." + li_id).removeClass("selected");
    obj.addClass("selected");
    var code = obj.attr("code");
    var text = obj.html();
    $("#" + sel_id).attr("code", code);
    $("#" + sel_id).html(text);
}

function SltBindAddAttr(li_id, sel_id, span_id) {
    $("." + li_id).bind("click", function () {
        var code = $(this).attr("code");
        var countryid = $(this).attr("countryid");
        var text = $(this).find("." + span_id).html();
        $("#" + sel_id).attr("code", code);
        $("#" + sel_id).attr("countryid", countryid);
        $("#" + sel_id).html(text);
    });
}

function SltBindReplaceCss(li_id, sel_id, span_class) {
    $("." + li_id).bind("click", function () {
        var code = $(this).attr("code");
        var text = $(this).html().replace('Replace', span_class);
        $("#" + sel_id).attr("code", code);
        $("#" + sel_id).html(text);
    });
}

//錯誤訊息相關
function ShowErrMsg(errmsg) {
    $(".wrongMsg2").each(function () {
        var css = $(this).attr('class');
        if (css.indexOf('wrongMsgSC') == -1) {
            var wml = $(this).closest("a").outerWidth() + 20;
            $(this).css("left", wml);
            $(this).show();
            $(".errormsg").html(errmsg);
        }
    })
}

function ShowErrMsgByObj(errmsg, obj) {
    HideErrMsg();
    var wrongMsg2 = obj.find(".wrongMsg2");
    var wml = wrongMsg2.closest("a").outerWidth() + 20;
    wrongMsg2.css("left", wml);
    wrongMsg2.show();
    $(".errormsg").html(errmsg);
}

function HideErrMsg() {
    $(".wrongMsg2").hide();
    $(".errormsg").html('');
}

//radio選取功能
function RdoBind(rdo_id) {
    $(document).on("click", "." + rdo_id, function () {
        $("." + rdo_id).removeClass("radioed");
        $(this).addClass("radioed");
        var code = $(this).attr("code");
        $(this).parent().attr("code", code);
    });
}

//radio預設
function RdoDefo(rdo_item, rdo_id) {
    var defo = $("#" + rdo_id).attr('code');
    $("." + rdo_item).each(function () {
        var code = $(this).attr("code");
        if (code == defo) {
            $(this).addClass("radioed");
            return false;
        }
    })
}

//AlertBox使用
function BindJsonData(JData) {
    var JsonData = new Array();
    JsonData.push(JData);
    return JSON.stringify(JsonData);
}
//AlertBox使用(AlertError)
function BindJsonText(p_data) {
    var JsonData = new Array();
    var JData = new Object();
    JData.Text = encodeURIComponent(p_data);
    JsonData.push(JData);
    return JSON.stringify(JsonData);
}


//加入遮罩
function MaskAppend(maskclass, type) {
    if ($("body").find("." + maskclass).length == 0)
        $("body").append(String.format("<div class='clsarea {0}' style='z-index: 11;' data-type='{1}'></div>", maskclass, type));
}

//移除遮罩
function MaskRemove(maskclass) {
    if ($("body").find("." + maskclass).length > 0)
        $("." + maskclass).remove();
}

//下拉加入事件
$.fn.onchange = function (handler) {
    return this.each(function () {
        var self = $(this);
        self.on("click", ":not([selected])", function () {
            self.children().removeAttr("selected");
            $(this).attr("selected", "selected");
            if (handler) {
                handler.call($(this));
            }
        });
    });
};

function login_click() {
    $(".loginPage").trigger("click");
}
