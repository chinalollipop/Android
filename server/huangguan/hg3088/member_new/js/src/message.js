/* 读取消息 */
function messageReadCount(e, t,n) {
    $.ajax({
        type: "post",
        url: "ajax.php",
        data:{uid: e, type: t, bh:n} ,
        dataType: "json",
        success: function(res){
            $('#receive-newcounts').text(res.data.unReadCount);  // 更新数量
            window.parent.frames['header'].document.getElementById("message_num").innerHTML=res.data.unReadCount; // 更新数量
        },
        error:function(){
            console.log('error');
        }
    });
}

function messageHandler(e, t, n, a, i, s, d, l, o, c) {
    $.post("ajax.php", {
        uid: e,
        type: t,
        mid: n,
        msgType: a,
        msgTitle: i,
        msgContent: s,
        addpople: d,
        ids: l,
        page: o,
        pageNum: c
    }, function(e) {
        if (t == 'send') {
            dialog({
                title: "消息",
                content: '<div class="tips-content send-ok">' + e + '</div>',
                fixed: !0,
                okValue: "确定",
                ok: function() {}
            }).showModal()
        } else {
            //"get" != t && "getS" != t || messageGet(e, t)
        }
    })
}

$(document).ready(function() {
    //messageHandler($userId, "get");
    var e = $(".m-module"),
        t = $(".m-item");
    e.children().each(function(e) {
        $(this).click(function() {
            messageHandler($(this).data("user-id"), $(this).data("type")), $(this).removeClass().addClass("active").siblings().removeClass(), t.removeClass("active").eq(e).addClass("active")
        })
    }), $("#allIn").click(function() {
        $("#inbox").find("input[name='Id']").prop("checked", $(this).prop("checked"))
    }), $("#inboxDel").click(function() {
        var e = "";
        if ($("#inbox").find("input[name='Id']").each(function() {
            this.checked && (e += "" == e ? $(this).val() : "," + $(this).val())
        }), "" == e) return dialog({
            title: "提示",
            content: '<div class="tips-content">没有要删除的消息</div>',
            fixed: !0,
            okValue: "确定",
            ok: function() {}
        }).showModal(), !1;
        messageHandler($userId, "delete", 0, "", "", "", "", e), messageHandler($userId, "get")
    }), $("#allOut").click(function() {
        $("#outbox").find("input[name='Id']").prop("checked", $(this).prop("checked"))
    }), $("#outboxDel").click(function() {
        var e = "";
        if ($("#outbox").find("input[name='Id']").each(function() {
            this.checked && (e += "" == e ? $(this).val() : "," + $(this).val())
        }), "" == e) return dialog({
            title: "提示",
            content: '<div class="tips-content">没有要删除的消息</div>',
            fixed: !0,
            okValue: "确定",
            ok: function() {}
        }).showModal(), !1;
        messageHandler($userId, "delete", 0, "", "", "", "", e), messageHandler($userId, "getS")
    });
    var n = $("#btn-send"),
        a = ($("#msgType"), $("#msgTitle")),
        i = $("#msgContent");
    $("#addpople");
    n.click(function() {
        if ("" == a.val()) return dialog({
            title: "提示",
            content: '<div class="tips-content">标题不能为空</div>',
            fixed: !0,
            okValue: "确定",
            ok: function() {}
        }).showModal(), !1;
        if ("" == i.val()) return dialog({
            title: "提示",
            content: '<div class="tips-content">内容不能为空</div>',
            fixed: !0,
            okValue: "确定",
            ok: function() {}
        }).showModal(), !1;
        var e = $('select[name="msgType"]').val(),
            t = $('input[name="msgTitle"]').val(),
            n = $('input[name="addpople"]').val();
        return messageHandler(0, "send", 0, e, t, $('textarea[name="msgContent"]').val(), n), $("#msgTitle").val(""), $("#msgContent").val(""), !1
    }), $(document).on("click", "#inbox .mcj_j", function() {//打开收信箱
        var e = $(this).find("a");
        dialog({
            title: "消息",
            content: '<dl class="msg-content"><dt><h1>' + $(e).data("title") + "</h1><p>时间：" + $(e).data("time") + "</p></dt><dd>" + $(e).data("content") + "</dd></dl>",
            fixed: !0,
            okValue: "关闭",
            ok: function() {}
        }).showModal();
        $(this).parents('.sxx_li').addClass("mem_readed");
        messageReadCount($userId,'readinbox',$(e).data("id"));
    }), $(document).on("click", "#outbox .mcj_j", function() {//打开发件箱
        var e = $(this).find("a");
        var id = $(e).data("id");
        var messagepars = {type :'opensendmail',bh :id,uid :$userId};
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            dataType: 'json',
            data: messagepars ,
            success: function (result) {
                //var infosObj = JSON.parse(res));
                var outinfosHTML = '';
                $.each(result, function(idx, obj) {
                    if(obj.isAdmin==0){ outinfosHTML += '<dl class="msg-content"><dt><p>You&nbsp;&nbsp;' + obj.time + "</p></dt><dd>" + obj.message + "</dd></dl><br/>"; }
                    if(obj.isAdmin==1){
                        if(idx==0){
                            outinfosHTML += '<dl class="msg-content"><dt><p>Admin[回复]&nbsp;&nbsp;' + obj.time + "</p></dt><dd>" + obj.message + "&nbsp;&nbsp;<a data-tid="+obj.topid+" data-id="+obj.lastid+" href=\"javascript:void(0);\" id=\"replayToAdmin\"><font color=\"blue\">[回复管理员]</font></a></dd></dl><br/>";
                        }else{
                            outinfosHTML += '<dl class="msg-content"><dt><p>Admin[回复]&nbsp;&nbsp;' + obj.time + "</p></dt><dd>" + obj.message + "</dd></dl><br/>";
                        }
                    }
                });
                dialog({
                    title: $(e).data("title"),
                    content: outinfosHTML,
                    fixed: !0,
                    okValue: "关闭",
                    ok: function() {}
                }).showModal();
            }
        })
    })
}), Date.prototype.pattern = function(e) {
    var t = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours() % 12 == 0 ? 12 : this.getHours() % 12,
        "H+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        S: this.getMilliseconds()
    };
    for (var n in /(y+)/.test(e) && (e = e.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length))), /(E+)/.test(e) && (e = e.replace(RegExp.$1, (1 < RegExp.$1.length ? 2 < RegExp.$1.length ? "/u661f/u671f" : "/u5468" : "") + {
        0: "/u65e5",
        1: "/u4e00",
        2: "/u4e8c",
        3: "/u4e09",
        4: "/u56db",
        5: "/u4e94",
        6: "/u516d"
    }[this.getDay() + ""])), t) new RegExp("(" + n + ")").test(e) && (e = e.replace(RegExp.$1, 1 == RegExp.$1.length ? t[n] : ("00" + t[n]).substr(("" + t[n]).length)));
    return e
};

$(document).on("click", "#replayToAdmin", function() {
    var id = $(this).data('id');
    var topid = $(this).data('tid');
    $(this).parents('tr').next().find('button').trigger("click");
    dialog({
        title: '回复管理员',
        content: '<textarea class="mcj_t" name="msgContent" id="replayContent" maxlength="1000" placeholder="请详细描述您要咨询的问题，我们的客服人员会及时的回复您的消息，谢谢！（限1000个中文字符）"></textarea>',
        fixed: !0,
        okValue: "确定回复",
        ok: function() {
            var message = $('#replayContent').val();
            var replaypars = {type :'replaysendmail',id:id,tid:topid,text:message,uid :$userId};
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                dataType: 'json',
                data: replaypars ,
                success: function (result) {
                    dialog({
                        title: "消息",
                        content: '<div class="tips-content send-ok">' + result.message + '</div>',
                        fixed: !0,
                        okValue: "确定",
                        ok: function() {}
                    }).showModal()
                }
            })

        }
    }).showModal();
})

