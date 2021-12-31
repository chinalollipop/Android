<?php
/**
 * 手机版-MW电子
 * Date: 2019/10/24
 */
session_start();
include_once('../include/config.inc.php');

// 判断会员是否登录，否则跳转登出页面
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期，请您重新登录!');window.location.href='../login.php';</script>";
    exit;
}

if ($_SESSION['test_flag']){
    exit("<script>alert('请登录真实账号登入MW电子');window.close();</script>");
}

// 判断会员状态是否启用，否则退出
if ($_SESSION['Status'] != 0){
    echo "<script>alert('非常抱歉，您的账号已冻结或已停用，请您联系客服！')</script>";
    exit;
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
    <title class="web-title">MW电子</title>
</head>
<body>
<script type="text/javascript" src="../js/zepto.min.js"></script>
<script type="text/javascript" src="../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    window.onload = function () {
        $.ajax({
            dataType : "json",
            type : "POST",
            url : 'mw_api.php?_=' + Math.random(),
            data : {'action': 'appGameLobby'},
            success:function(item) {
                if(item.status == '200') {
                    window.location.href=item.data[0].toUrl;
                }
                else if(item.status == '222'){ // 初始化账号
                    alert(item.describe);
                    window.close();
                }
                else if(item.status == '401.1'){ // 重新登录
                    window.location.href='../login.php';
                }
                else{
                    alert(item.describe);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
</script>
</body>
</html>

