<?php
/**
 * 皇冠棋牌
 * Date: 2018/11/7
 */
session_start();
include_once "../include/address.mem.php";
include_once "../include/config.inc.php";

// 判断棋牌是否维护，若维护自动调转维护页面
checkMaintain('hgqp');

// 判断会员是否登录，否则跳转登出页面
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
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
<title>皇冠棋牌</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="full-screen" content="true" />
<meta name="screen-orientation" content="landscape" />
<meta name="x5-fullscreen" content="true" />
<meta name="360-fullscreen" content="true" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="shortcut icon" href="../../../images/favicon.ico" type="image/x-icon">
<link type="text/css" rel="stylesheet" href="../../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">
<script language="javascript">
    if (top.location != self.location)
        top.location=self.location;
    //防止页面后退
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });
</script>
<style>
    html,body{
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
        overflow: hidden;
    }
    .nav-area{
        position: fixed;
        z-index: 30000;
        cursor: move;
        text-align: center;
        display: inline-block;
    }
    .nav-area img {border-radius: 5px;}
    @media only screen and (min-width: 1030px){
        .nav-area{ top: 2%;right: 20%;}
    }
    @media only screen and (max-width: 1029px){
        @media all and (orientation : landscape) { /*　　这是匹配横屏的状态，横屏时的css代码　　*/
            .nav-area{ right: 16%;top:0;}
            .nav-area img {
                width: 58%;
                /* transform: rotate(90deg);
                 -ms-transform: rotate(90deg);
                 -webkit-transform: rotate(90deg);*/
            }
            @media only screen and (min-width: 700px) and (max-width: 1024px){
                .nav-area{ right: 22%;top:2%;}
                .nav-area img {
                    width: 73%;
                    /* transform: rotate(90deg);
                     -ms-transform: rotate(90deg);
                     -webkit-transform: rotate(90deg);*/
                }
            }
        }
        @media all and (orientation : portrait){ /*　　这是匹配竖屏的状态，竖屏时的css代码　　*/
            .nav-area{ right: -3%;bottom: 18%;}
            .nav-area img {
                width: 58%;
                /* transform: rotate(90deg);
                 -ms-transform: rotate(90deg);
                 -webkit-transform: rotate(90deg);*/
            }
            @media only screen and (min-width: 700px) and (max-width: 1024px){
                .nav-area{ right: 1%;bottom: 22%;}
                .nav-area img {
                    width: 73%;
                    /* transform: rotate(90deg);
                     -ms-transform: rotate(90deg);
                     -webkit-transform: rotate(90deg);*/
                }
            }

        }
    }
</style>
</head>
<body>
<iframe id="myiframe" scrolling="no" frameborder="0" style="width:100%;" allowfullscreen="true"></iframe>
    <script type="text/javascript" src="../../../js/jquery.js"></script>
    <script type="text/javascript" src="../../../js/jbox/jquery.jBox-2.3.min.js"></script>
    <script type="text/javascript" src="../../../js/jbox/jquery.jBox-zh-CN.js"></script>
    <script>
        window.onload = function () {
            var data = {};
            data.uid = '<?php echo $_REQUEST['uid'];?>';
            data.action='cm';
            data.flag='<?php echo $_REQUEST['flag']?>';
            $.ajax({
                dataType : "json",
                type : "POST",
                url : 'hg_api.php?_=' + Math.random(),
                data : data,
                success:function(item) {
                    if(item.code == 1) {
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
                        alert(item.message);
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