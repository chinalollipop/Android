<?php
include "../app/member/include/config.inc.php";
include "../app/member/include/address.mem.php";

$uid=$_REQUEST['uid'];
$userid = $_SESSION['userid'];
$username = $_SESSION['UserName'];
$langx=$_SESSION['langx'];

$to = $_REQUEST['to'] ;


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="../../../images/favicon.ico" type="image/x-icon">
    <title>皇冠体育</title>
    <style>
        a,b,body,button,dd,div,dl,dt,em,h1,h2,h3,header,i,input,li,p,section,span,ul{margin:0;padding:0}
        body{font-size:12px;font-family:arial,PingFangSC-Regular,"Microsoft Yahei",Helvetica,sans-serif}
        li,ul{list-style:none}
        h1,h2,h3{font-weight:500;font-size:15px}
        button,img{border:0}
        input{outline:0}
        .fl{float:left}
        .fr{float:right}
        a{text-decoration:none}
        .header{position:fixed;left:0;right:0;padding:0 10px;text-align:center;z-index:99}
        .container{background-color:#F7F7F7;padding:70px 0 20px;overflow:hidden;min-width:1000px}
        .center{width:1000px;margin:0 auto;overflow:hidden}
        .slidebar{position:fixed;top:271px;width:194px;border-top:7px solid #2483D1;background:#fff;box-shadow:0 5px 6px rgba(0,0,0,.2);border-radius:4px;z-index:10}
        .slidebar .nav_up{position:relative;text-decoration:none;height:39px;padding:0 55px;border-bottom:1px solid #E3E3E3;line-height:39px;font-size:16px;font-weight:700;color:#2483D1;display:block}
        .slidebar .nav_up span{background:url(../../../images/cgpay/logo.png) no-repeat;width:30px;height:30px;position:absolute;left:15px;top:5px;background-size:100%}
        .slidebar ul li{margin:3px 0}
        .slidebar ul li a{position:relative;color:#838383;font-size:14px;text-decoration:none;display:block;height:28px;line-height:28px;padding-left:46px}
        .article{line-height:2.5;padding:190px 0 0 207px;font-size:14px}
        .section{overflow:hidden;padding-bottom:12px;background:#eee;margin:12px 0 0 0;border-bottom:1px solid #ECECEC}
        .section .page_header{position:relative;height:48px;line-height:50px;border-bottom:1px solid #E3E3E3;color:#2483D1;font-size:16px;font-weight:700;padding-left:57px}
        .setting-img{width:320px}
        .setting-img img{width:100%}
        .section ul li{float:left;margin:0 38px;text-align:center;font-size:15px;font-weight:700}
        .section ol li{font-size:15px;font-weight:700}
        .slidebar ul li a.act,.slidebar ul li a:hover{background-color:#DF3A47;color:#fff}
        .section ol li img{width:100%}

    </style>

</head>
<body>
<div class="header">
    <img src="../../../images/cgpay/10000.jpg"  alt="">
</div>
<div class="container" id="container">
    <div class="center">
        <div class="slidebar" id="slidebar">
            <a class="nav_up">
                <span></span>
                cgpay钱包
            </a>
            <ul class="nav">
                <li>
                    <a href="#noe" class="act">app下载流程</a>
                </li>
                <li>
                    <a href="#two">首次使用app教学流程</a>
                </li>
                <li>
                    <a href="#three">注册与登录</a>
                </li>
                <li>
                    <a href="#four">如何购买CGP?</a>
                </li>
                <li>
                    <a href="#five">平台入款流程</a>
                </li>
            </ul>
        </div>
        <div class="article" id="con">
            <div class="section" id="noe">
                <div class="page_header">
                    app下载流程
                </div>
                <ul>
                    <li>
                        方法一：在浏览器中输入网址【cgpy.pw】
                        <div class="setting-img">
                            <img src="../../../images/cgpay/pay_1.png" alt="">
                        </div>
                    </li>
                    <li>
                        方法二：扫图中二维码下载
                        <div class="setting-img">
                            <img src="../../../images/cgpay/pay_2.png" alt="">
                        </div>
                    </li>
                </ul>
                <div style="clear: both"></div>
            </div>
            <div class="section" id="two">
                <div class="page_header">
                    首次使用app教学流程
                </div>
                <ul>
                    <li>
                        1.找到【设置】
                        <div class="setting-img">
                            <img src="../../../images/cgpay/app_1.png" alt="">
                        </div>
                    </li>
                    <li>
                        2.【通用】 设备管理
                        <div class="setting-img">
                            <img src="../../../images/cgpay/app_2.png" alt="">
                        </div>
                    </li>
                    <li>
                        3.PHP【信用】
                        <div class="setting-img">
                            <img src="../../../images/cgpay/app_3.png" alt="">
                        </div>
                    </li>
                </ul>
                <div style="clear: both"></div>
            </div>
            <div class="section" id="three">
                <div class="page_header">
                    注册登录
                </div>
                <ul>
                    <li>
                        1.进入APP点击免费注册
                        <div class="setting-img">
                            <img src="../../../images/cgpay/zc_1.png" alt="">
                        </div>
                    </li>
                    <li>
                        2.填写基本数据进行注册
                        <div class="setting-img">
                            <img src="../../../images/cgpay/zc_3.png" alt="">
                        </div>
                    </li>
                    <li>
                        3.填写手机及邮箱收到的验证码
                        <div class="setting-img">
                            <img src="../../../images/cgpay/zc_3.png" alt="">
                        </div>
                    </li>
                    <li>
                        4.注册完成
                        <div class="setting-img">
                            <img src="../../../images/cgpay/zc_4.png" alt="">
                        </div>
                    </li>
                </ul>
                <div style="clear: both"></div>
            </div>
            <div class="section" id="four">
                <div class="page_header">
                    如何购买CGP币
                </div>
                <ul>
                    <li>
                        1.点击【我要买】
                        <div class="setting-img">
                            <img src="../../../images/cgpay/mai_1.png" alt="">
                        </div>
                    </li>
                    <li>
                        2.选择您想使用付款方式及数量【点击挂单】
                        <div class="setting-img">
                            <img src="../../../images/cgpay/mai_2.png" alt="">
                        </div>
                    </li>
                    <li>
                        3.点击心仪卖单
                        <div class="setting-img">
                            <img src="../../../images/cgpay/mai_3.png" alt="">
                        </div>
                    </li>
                    <li>
                        4.出现售价
                        <div class="setting-img">
                            <img src="../../../images/cgpay/mai_4.png" alt="">
                        </div>
                    </li>
                    <li>
                        5.点击【✔】确认 点击【✖】取消
                        <div class="setting-img">
                            <img src="../../../images/cgpay/mai_5.png" alt="">
                        </div>
                    </li>
                    <li>
                        6.点击【通知已付款】
                        <div class="setting-img">
                            <img src="../../../images/cgpay/mai_6.png" alt="">
                        </div>
                    </li>
                    <li>
                        7.点击【送出】通知卖方我已付款成功
                        <div class="setting-img">
                            <img src="../../../images/cgpay/mai_7.png" alt="">
                        </div>
                    </li>
                    <li>
                        8.点击【我的交易】查看订单
                        <div class="setting-img">
                            <img src="../../../images/cgpay/mai_8.png" alt="">
                        </div>
                    </li>
                    <li>
                        9.等待买家打币
                        <div class="setting-img">
                            <img src="../../../images/cgpay/mai_9.png" alt="">
                        </div>
                    </li>
                </ul>
                <div style="clear: both"></div>
            </div>
            <div class="section" id="five">
                <div class="page_header">
                    平台入款流程
                </div>
                <ol>
                    <li>
                        1.选择【用户登入】
                        <div>
                            <img src="../../../images/cgpay/pt_1.png" alt="">
                        </div>
                    </li>
                    <li>
                        2.点击【线上存款】
                        <div>
                            <img src="../../../images/cgpay/pt_2.png" alt="">
                        </div>
                    </li>
                    <li>
                        3.点击【快速存款】
                        <div>
                            <img src="../../../images/cgpay/pt_3.png" alt="">
                        </div>
                    </li>
                    <li>
                        4.填写会员账号→选择CGPAY→选择金额→确认额度→点击确认支付
                        <div>
                            <img src="../../../images/cgpay/pt_4.png" alt="">
                        </div>
                    </li>
                    <li>
                        5.打开CG钱包→点击右上角→扫码支付完毕→刷新账户查收(RMB)
                        <div>
                            <img src="../../../images/cgpay/pt_5.png" alt="">
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript">
    $(function () {
        function preLoad(){
            $imgs.each(function(){
                if(
                    ($(this).offset().top<$(window).scrollTop()+$(window).height()
                        && $(this).offset().top>$(window).scrollTop())
                    ||($(this).offset().top+$(this).height()<$(window).scrollTop()+$(window).height()
                    && $(this).offset().top+$(this).height()>$(window).scrollTop())
                ){
                    $(this).attr("src",$(this).attr("_src")).removeAttr("_src").css("height","auto");
                }
            });
        }
        function indexAct(){
            if(!enableIndexAct){
                return;
            }
            $heads.each(function(index) {
                if($(this).offset().top>$(window).scrollTop() && $(this).offset().top<$(window).scrollTop()+$(window).height()-50){
                    $targs.removeClass("act").eq(index).addClass("act");
                }

            });
        }
        function targsScrollTo($target){
            $('body,html').scrollTop($target.offset().top);
        }

        var $heads=$("#con").find(".page_header"),
            $targs=$("#slidebar").find(".nav a"),
            $imgs=$("#con").find("img[_src]"),
            enableIndexAct=true;
        $imgs.css("height","500px")
        $targs.eq(0).addClass("act");
        preLoad();
        $(window).scroll(function(){
            preLoad();
            if($(window).scrollTop()>$(window).height()){
                $("#toTop").fadeIn();
            }else{
                $("#toTop").fadeOut();
            }
            if($(window).scrollTop()+$("#slidebar").height()+82 >= $("#container").innerHeight()){
                $("#slidebar").css({top:$("#container").innerHeight()-$("#slidebar").height()-27,position:"absolute"});
            }else{
                $("#slidebar").css({top:"271px",position:"fixed"});
            }
            indexAct();
        });

        $targs.on({
            click:function(e){
                $targs.removeClass("act");
                $(this).addClass("act");
            },
            mousedown:function(){
                enableIndexAct=false;
            },
            mouseup:function(){
                setTimeout(function(){enableIndexAct=true;},100);
            }
        });
    })
</script>

</body>

</html>