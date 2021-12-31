<?php
/**
 * MW电子
 */

session_start();
include_once "../include/address.mem.php";
include_once "../include/config.inc.php";

// 判断是否维护，若维护自动调转维护页面
// checkMaintain('ly');

$uid = $_SESSION['Oid'] ;
// 判断会员是否登录，否则跳转登出页面
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>top.location.href='/'</script>";
    exit;
}

// 判断会员是否是试玩账号，如果是试玩则提示注册真实账号
if($_SESSION['Agents'] == 'demoguest'){
    echo "<script>alert('非常抱歉，请您注册真实会员！')</script>";
    exit;
}

// 判断会员状态是否启用，否则退出
if ($_SESSION['Status'] != 0){
    echo "<script>alert('非常抱歉，您的账号已冻结或已停用，请您联系客服！')</script>";
    exit;
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>MW电子</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="full-screen" content="true" />
    <meta name="screen-orientation" content="landscape" />
    <meta name="x5-fullscreen" content="true" />
    <meta name="360-fullscreen" content="true" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="shortcut icon" href="../../../images/favicon.ico" type="image/x-icon">
</head>
<body>

<script type="text/javascript" src="../../../js/jquery.js"></script>

<script>
    $(function () {
        getMwLoginUrl();

        // 获取接口信息
        function getMwLoginUrl() {
            var dat={};
            dat.action='gameLobby';
            $.ajax({
                type: 'POST',
                url:'/app/member/mw/mw_api.php?_='+Math.random(),
                data:dat,
                dataType:'json',
                success:function(ret){
                    if (ret.status==222){
                        alert(ret.describe);
                        window.close();
                    }
                    else if(ret.status==200){
                        window.location.href = (ret.data[0].toUrl);
                    }
                    else{
                        alert(ret.describe);
                        window.close();
                    }
                },
                error:function(ii,jj,kk){
                    alert('网络错误，请稍后重试');
                }
            });

        }
    });

</script>
</body>
</html>