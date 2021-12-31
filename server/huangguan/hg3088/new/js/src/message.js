$(document).ready(function(){
    //消息中心模块切换
    /**
     * 收件箱消息获取
     * **/
    messageHandler($userId, 'get');
    var msgModule=$(".m-module"),msgItem=$(".m-item");
    msgModule.children().each(function(i){
        $(this).click(function(){
            messageHandler($(this).data('user-id'), $(this).data('type'));

            $(this).removeClass().addClass("active").siblings().removeClass();
            msgItem.removeClass("active").eq(i).addClass("active");
        });
    });

    /*===收件箱操作====================================*/
    //全选、反选
    $("#allIn").click(function(){
        $("#inbox").find("input[name='Id']").prop('checked', $(this).prop('checked'));
    });

    //删除信息
    $("#inboxDel").click(function(){
        var ids="";
        $("#inbox").find("input[name='Id']").each(function(){
            if(this.checked){
                if(ids==""){
                    ids += $(this).val();
                }else {
                    ids += ','+$(this).val();
                }
            }
        });
        if(ids==""){
            dialog({ title: '提示',content:'<div class="tips-content">没有要删除的消息</div>', fixed: true, okValue:'确定', ok:function (){} }).showModal();
            return false;
        }else{
            // alert("通过AJAX删除发件箱以下ID信息："+ids);
            //AJAX删除选择略  删除之后要刷新
            var $ids = ids;
            messageHandler($userId, 'delete', 0, '', '', '','',$ids);
            messageHandler($userId, 'get');
        }
    });

    /***
     * sim
     * 发件箱操作
     * //全选、反选
     * ***/
    $("#allOut").click(function(){
        $("#outbox").find("input[name='Id']").prop('checked', $(this).prop('checked'));
    });

    //删除信息
    $("#outboxDel").click(function(){
        var ids="";
        $("#outbox").find("input[name='Id']").each(function(){
            if(this.checked){
                if(ids==""){
                    ids += $(this).val();
                }else {
                    ids += ','+$(this).val();
                }
            }
        });
        if(ids==""){
            dialog({ title: '提示',content:'<div class="tips-content">没有要删除的消息</div>', fixed: true, okValue:'确定', ok:function (){} }).showModal();
            return false;
        }else{

            //AJAX删除选择略  删除之后要刷新
            var $ids = ids;
             //messageHandler($userId,'delete',$ids);
            messageHandler($userId, 'delete', 0, '', '', '','',$ids);
            messageHandler($userId, 'getS');
        }
    });

    /*===发送新消息====================================*/
    var btnSend=$("#btn-send"),msgType=$("#msgType"),msgTitle=$("#msgTitle"),msgContent=$("#msgContent"),addpople=$("#addpople");
    btnSend.click(function(){
        if(msgTitle.val()==""){
            dialog({ title: '提示',content:'<div class="tips-content">标题不能为空</div>', fixed: true, okValue:'确定', ok:function (){} }).showModal();
            return false;
        } else if(msgContent.val()==""){
            dialog({ title: '提示',content:'<div class="tips-content">内容不能为空</div>', fixed: true, okValue:'确定', ok:function (){} }).showModal();
            return false;
        }

        //这里添加通过AJAX提交表单，如果提交信息不法合，
        //返回的消息提示框可参考上方标题为空提示，
        //如果提交成功，则用以下提交成功提示框
        //并用AJAX重新加载收件箱和发件箱的相关信息填充到标签<ul class="mcj_g"></ul>里

        //提交成功提示框
        dialog({ title: '消息',content:'<div class="tips-content send-ok">已收到您的消息，我们尽快回复。谢谢！</div>', fixed: true, okValue:'确定', ok:function (){} }).showModal();
        var $msgType = $('select[name="msgType"]').val();
        var $msgTitle = $('input[name="msgTitle"]').val();
        var $addpople = $('input[name="addpople"]').val();
        var $msgContent = $('textarea[name="msgContent"]').val();
        messageHandler(0, 'send', 0, $msgType, $msgTitle, $msgContent,$addpople);
        $("#msgTitle").val("");
        $("#msgContent").val("");
        return false;

    });

    /*===收件箱消息阅读====================================*/
    $(document).on('click', '#inbox .mcj_j', function(){
        var target = $(this).find('a');
        dialog({ title: '消息',content:'<dl class="msg-content"><dt><h1>'+ $(target).data('title') +'</h1><p>时间：' + $(target).data('time') + '</p></dt><dd>' + $(target).data('content') + '</dd></dl>', fixed: true, okValue:'关闭', ok:function (){} }).showModal();
        //删除未阅读标记
        $(target).parent().parent().parent().removeClass("unread");

        messageHandler(0, 'read', $(target).data('id'));//阅读了一条刷新记录
        messageHandler($userId, 'get');
    });
    /*===收件箱消息阅读====================================*/
    $(document).on('click', '#outbox .mcj_j', function(){
        var target = $(this).find('a');
        dialog({ title: '消息',content:'<dl class="msg-content"><dt><h1>'+ $(target).data('title') +'</h1><p>时间：' + $(target).data('time') + '</p></dt><dd>' + $(target).data('content') + '</dd></dl>', fixed: true, okValue:'关闭', ok:function (){} }).showModal();
    });

    /***
     * sim
     * 选择页面
     * 收件箱
     * ***/
    $(document).on("change", "#chgPage", function () {
        var $page = $(this).val();
        var $pageNum = 5;
        messageHandler($userId, 'get', 0, '', '', '','','',$page,$pageNum);
    });
    /***
     * sim
     * 选择页面
     * 发件箱
     * ***/
    $(document).on("change", "#chgSPage", function () {
        var $page = $(this).val();
        var $pageNum = 5;
        messageHandler($userId, 'getS', 0, '', '', '','','',$page,$pageNum);
    });
});

// function messageHandler($uid, $type, $mid = 0, $msgType = '', $msgTitle = '', $msgContent = '',$addpople = '',$ids = '',$page = '',$pageNum = ''){
function messageHandler($uid, $type, $mid, $msgType, $msgTitle, $msgContent,$addpople,$ids,$page,$pageNum){
    $.post('ajax.php', {'uid':$uid, 'type':$type, 'mid':$mid, 'msgType':$msgType, 'msgTitle':$msgTitle, 'msgContent':$msgContent ,'addpople':$addpople,'ids':$ids,'page':$page,'pageNum':$pageNum}, function(response){
        if($type == 'get' || $type == 'getS'){
            messageGet(response,$type);
        }
    });
}

Date.prototype.pattern=function(fmt) {
    var o = {
        "M+" : this.getMonth()+1, //月份
        "d+" : this.getDate(), //日
        "h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时
        "H+" : this.getHours(), //小时
        "m+" : this.getMinutes(), //分
        "s+" : this.getSeconds(), //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S" : this.getMilliseconds() //毫秒
    };
    var week = {
        "0" : "/u65e5",
        "1" : "/u4e00",
        "2" : "/u4e8c",
        "3" : "/u4e09",
        "4" : "/u56db",
        "5" : "/u4e94",
        "6" : "/u516d"
    };
    if(/(y+)/.test(fmt)){
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }
    if(/(E+)/.test(fmt)){
        fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "/u661f/u671f" : "/u5468") : "")+week[this.getDay()+""]);
    }
    for(var k in o){
        if(new RegExp("("+ k +")").test(fmt)){
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
        }
    }
    return fmt;
}

function messageGet(response,$type){
    var $target = '';
    var $tpl = '';
    var $pageTpl = '';
    var $length = $(response[0]).size();
    if($type== 'getS'){
        $target = $('#outbox > .mcj_g');
    }else {
        $target = $('#inbox > .mcj_g');
    }
    if($length == 0){
        $tpl = "<h3 class=\"message-no\">您还未有任何信息哦~</h3>";
        $target.append($tpl);
    }
    $target.html('');
    if($type != 'getS'){
        $unreadMsg = response.unReadCount;
        $("#receive-newcounts").html($unreadMsg);
    }
    for (var i = 0; i < $length; i++) {
        var $_class = '';
        if(response[0][i].user_read == 0){
            $_class = 'unread';
        }
        $title_type = response[0][i].title_type;
        if($title_type==1){
            $title_typev = "财务问题";
        }else if ($title_type==2){
            $title_typev = "技术问题";
        }else if ($title_type==3){
            $title_typev = "业务咨询";
        }else if ($title_type ==4){
            $title_typev = "意见建议";
        }else if ($title_type==5){
            $title_typev = "其他问题";
        }else {
            $title_typev = $title_type;
        }
        var addtime = response[0][i].addtime;
        var unixTimestamp = new Date(addtime* 1000);
        var showTime = unixTimestamp.pattern("yyyy-MM-dd HH:mm:ss")
        // (" + $title_typev + ")
        if($type == 'getS'){
            $tpl += "<li>"+
                "<div class=\"clearfix\">"+
                "<div class=\"mcj_h\"><input type=\"checkbox\" name=\"Id\" value=\"" + response[0][i].id + "\"></div>"+
                "<div class=\"mcj_j\"><a href=\"#\" data-title=\"" + response[0][i].title + "\" data-content=\'" + response[0][i].content + "\' data-time='"+showTime+"' data-id=\"" + response[0][i].id + "\">" + response[0][i].title + "</a></div>"+
                "<div class=\"mcj_k\">"+showTime+"</div>"+
                "</div>"+
                "</li>";
        }else {
            $tpl += "<li class=\"" + $_class + "\">"+
                "<div class=\"clearfix\">"+
                "<div class=\"mcj_h\"><input type=\"checkbox\" name=\"Id\" value=\"" + response[0][i].id + "\"></div>"+
                "<div class=\"mcj_i\"></div>"+
                "<div class=\"mcj_j\"><a href=\"#\" data-title=\"" + response[0][i].title + "\" data-content=\'" + response[0][i].content + "\' data-time='"+showTime+"' data-id=\"" + response[0][i].id + "\">" + response[0][i].title + "</a></div>"+
                "<div class=\"mcj_k\">"+showTime+"</div>"+
                "</div>"+
                "</li>";
        }

    };
    $('.mcj_l').show();
    if($type== 'getS'){
        $pageTpl += "当前第&nbsp;&nbsp;<select id='chgSPage'>";
    }else {
        $pageTpl += "当前第&nbsp;&nbsp;<select id='chgPage'>";
    }
    for(var i=1;i<=response.pages;i++){
        $pageTpl += "<option value='"+i+"'";
        if(i==response.page){
            $pageTpl += "selected";
        }
        $pageTpl += ">"+i+"</option>";
    }
    $pageTpl += "</select>&nbsp;&nbsp;页&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $pageTpl += "共<em>"+response.pages+"</em>页&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $pageTpl += "共<em>"+response.count+"</em>条记录";
    if($type== 'getS'){
        $('#outboxPage').html('');
        $('#outboxPage').append($pageTpl);
    }else {
        $('#inboxPage').html('');
        $('#inboxPage').append($pageTpl);
    }
    $target.append($tpl);
}