<?php
/**
 * 手机版-开元棋牌
 * Date: 2018/9/5
 */
session_start();
include_once('../include/config.inc.php');

// 判断会员是否登录，否则跳转登出页面
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期，请您重新登录!');window.location.href='../login.php';</script>";
    exit;
}

// 判断会员状态是否启用，否则退出
if ($_SESSION['Status'] != 0){
    echo "<script>alert('非常抱歉，您的账号已冻结或已停用，请您联系客服！')</script>";
    exit;
}

// 三方通知回调-开元棋牌会员下线（暂不做处理）
if($_REQUEST['agent'] && $_REQUEST['account'] && $_REQUEST['money']){

}
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="<?php echo TPL_NAME;?>images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="/<?php echo TPL_NAME;?>style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <title class="web-title"></title>
   <!-- <script language="javascript">
        if (top.location != self.location)
            top.location=self.location;
        //防止页面后退
        history.pushState(null, null, document.URL);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
        });
    </script>-->
<style type="text/css">
    .header{ position: static;background: #090810;height: 2.5rem;line-height: 2.5rem;margin-bottom: 0; }
    .header .header_logo{display: none;margin-top: .2rem;}
    <?php
       if(TPL_FILE_NAME == '8msport'){
           echo '.icon-back:before{margin: 26% 0 0 -10%;}';
       }else{
           echo '.icon-back:before{margin: 31% 0 0 -10%;}';
       }
       ?>
</style>
</head>
<body>
<!-- 头部 -->
<div class="header" >

</div>
<div id="container" class="dialog-content">
    <iframe id="myiframe" scrolling="no" frameborder="0" style="width:100%;" allowfullscreen="true"></iframe>
</div>
<script type="text/javascript" src="../js/zepto.min.js"></script>
<script type="text/javascript" src="../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    setLoginHeaderAction('开元棋牌','','') ;
    window.onload = function () {
        $.ajax({
            dataType : "json",
            type : "POST",
            url : 'ky_api.php?_=' + Math.random(),
            data : {'action': 'cm'},
            success:function(item) {
                if(item.status == '200') {
                    var href = location.href;
                    var url = item.data.url + "&returnUrl=" + href;
                    if(item.data.url !== undefined) {
                        if (window.location.protocol === "https:" && !/^https\:/.test(url)) {
                            window.location = url;
                        } else {
                            var ifm= document.getElementById("myiframe");
                            ifm.src = url;
                            changeFrameHeight();
                        }
                    }
                }else{
                    alert(item.describe);
                    if(item.status == '422'){ // 重新登录
                        window.location.href='../login.php';
                    }
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function changeFrameHeight(){
        var ifm= document.getElementById("myiframe");
        ifm.height=document.documentElement.clientHeight;
    }
    window.onresize=function(){
        changeFrameHeight();
    }
</script>
</body>
</html>

