<?php
/**
 * 开元棋牌
 * Date: 2018/8/22
 */

session_start();
include_once "../include/address.mem.php";
include_once "../include/config.inc.php";

// 判断棋牌是否维护，若维护自动调转维护页面
checkMaintain('ky');

// 判断会员是否登录，否则跳转登出页面
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
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
    <title>开元棋牌</title>
    <meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="full-screen" content="true" />
    <meta name="screen-orientation" content="landscape" />
    <meta name="x5-fullscreen" content="true" />
    <meta name="360-fullscreen" content="true" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="shortcut icon" href="../../../images/favicon.ico" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="../../../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">
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
        body{margin:0;padding:0;font-family:Arial,'Microsoft JhengHei','微軟正黑體','微软正黑体';background-color:#fff}
        :focus{outline:0}
        *{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box}
        [ng-click]{cursor:pointer}
        ol,ul{display:inline-block;padding:0;margin:0;list-style:none}
        .wrapper{position:relative;width:850px;margin:0 auto}
        #header{font-size:0}
        #logo{position:relative;display:inline-block;height:80px}
        #logo img{position:absolute;top:0;left:0;bottom:0;right:0;margin:auto}
        #search{position:absolute;top:20px;right:0;width:200px;height:40px;padding:5px;background-color:#333;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px}
        #search>form>*{display:inline-block;height:30px;vertical-align:top;border:none}
        #search form input{width:150px;margin-right:5px;padding:0 5px;color:#000;font-size:12px;font-family:Arial,'Microsoft JhengHei','微軟正黑體','微软正黑体';line-height:30px;background-color:#fff;border:none}
        #search>form>input::-webkit-input-placeholder{color:#000;-moz-transition:all .3s;-o-transition:all .3s;-webkit-transition:all .3s;transition:all .3s}
        #search>form>input::-moz-placeholder{color:#000;-moz-transition:all .3s;-o-transition:all .3s;-webkit-transition:all .3s;transition:all .3s}
        #search>form>input:-ms-input-placeholder{color:#000;-moz-transition:all .3s;-o-transition:all .3s;-webkit-transition:all .3s;transition:all .3s}
        #search>form>input.placeholdersjs{color:#000;-moz-transition:all .3s;-o-transition:all .3s;-webkit-transition:all .3s;transition:all .3s}
        #search>form>input:focus::-webkit-input-placeholder{color:rgba(32,30,30,.3)}
        #search>form>input:focus::-moz-placeholder{color:rgba(32,30,30,.3)}
        #search>form>input:focus:-ms-input-placeholder{color:rgba(32,30,30,.3)}
        #search>form>input.placeholdersjs{color:rgba(32,30,30,.3)}
        #search form button{width:30px;color:#fff;font-size:20px;background-color:transparent;cursor:pointer;border:none;outline:0}
        nav{margin-bottom:15px;padding:10px 0 0;min-height:50px;background-color:#eee}
        #game-nav{display:block;font-size:0}
        #game-nav>li{position:relative;display:inline-block;margin:0 10px 10px 0;vertical-align:top}
        #game-nav>li>span{display:inline-block;padding:0 15px;color:#fff;font-size:15px;line-height:30px}
        #game-nav>li:hover>ol.subnav{display:block}
        ol.subnav{display:none;position:absolute;top:100%;left:50%;margin-left:-50px;width:100px;padding:10px 0 0;text-align:center;z-index:3}
        ol.subnav>li{color:#000;font-size:14px;font-weight:400;line-height:30px;background-color:#fff;cursor:pointer}
        #content #banner>img{width:100%}
        #game-list{position:relative;margin:0 auto;display:block;padding:0;font-size:0;text-align:left}
        #game-list li{position:relative;display:inline-block;width:160px;height:180px;margin:5px;vertical-align:top;z-index:0;background-color:#fff;border:1px solid #ccc;-moz-border-radius:10px;-webkit-border-radius:10px;border-radius:10px}
        #game-list li img.icon{position:absolute;z-index:2}
        #game-list li img.icon.icon01{top:9px;left:9px}
        #game-list li img.icon.icon02{top:7px;right:10px}
        #game-list li img.icon.icon03{right:5px;bottom:30px}
        #game-list li img.icon.icon04{left:-10px;bottom:30px}
        #game-list li .game-logo{position:relative;height:100%;padding-top:9px;text-align:center}
        #game-list li:hover .game-logo:before{content:'';position:absolute;top:9px;left:9px;width:140px;height:140px;z-index:1;background-repeat:no-repeat;background-position:center center}
        #game-list li .game-logo img{position:relative;display:inline-block;width:140px;height:140px}
        #game-list li .game-logo img:before{content:'';position:absolute;top:0;right:0;bottom:0;left:0;background-color:#000}
        #game-list li .game-text{position:absolute;bottom:5px;width:100%;color:#000;font-size:16px;text-align:center;white-space:nowrap;overflow:hidden;-ms-text-overflow:ellipsis;-o-text-overflow:ellipsis;text-overflow:ellipsis}
        #pager{display:block;margin:20px auto 15px;text-align:center;font-size:0}
        #pager>li{display:inline-block;margin:0 3px;padding:0 10px;color:#000;height:25px;font-size:16px;line-height:25px;vertical-align:top;background-color:#ccc}
        @media (max-width:870px){.wrapper{width:680px}
            #game-list li{margin:5px}
        }
        @media (max-width:700px){.wrapper{width:510px}
            #game-nav>li span,ol.subnav>li{font-size:13px}
        }
        @media (max-width:530px){#header{text-align:center}
            #header #logo{display:block}
            #header #search{position:static;margin:0 auto 15px}
            #game-nav>li{margin:0 0 10px 0}
            .wrapper{width:340px}
            #game-list li{width:109px;height:126px;margin:2px}
            #game-list li img.icon.icon01{left:6px}
            #game-list li img.icon.icon02{right:3px}
            #game-list li .game-logo img{width:95px;height:95px}
            #game-list li:hover .game-logo:before{left:6px;width:95px;height:95px}
            #game-list li .game-text{bottom:1px;font-size:14px}
        }
        .hot>span{color:#24e12b;-moz-animation-duration:.5s;-o-animation-duration:.5s;-webkit-animation-duration:.5s;animation-duration:.5s;-moz-animation-name:blink;-o-animation-name:blink;-webkit-animation-name:blink;animation-name:blink;-moz-animation-iteration-count:infinite;-o-animation-iteration-count:infinite;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-moz-animation-direction:alternate;-o-animation-direction:alternate;-webkit-animation-direction:alternate;animation-direction:alternate;-moz-animation-timing-function:linear;-o-animation-timing-function:linear;-webkit-animation-timing-function:linear;animation-timing-function:linear}
        @-moz-keyframes blink{from{color:#24e12b}
            to{color:#147dff}
        }
        @-webkit-keyframes blink{from{color:#24e12b}
            to{color:#147dff}
        }
        @keyframes blink{from{color:#24e12b}
            to{color:#147dff}
        }
        #search{background:#edd171;background:-webkit-linear-gradient(#edd171,#eeaf45);background:-o-linear-gradient(#edd171,#eeaf45);background:-moz-linear-gradient(#edd171,#eeaf45);background:linear-gradient(#edd171,#eeaf45);-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px}
        nav{background:#edd171;background:-webkit-linear-gradient(#edd171,#eeaf45);background:-o-linear-gradient(#edd171,#eeaf45);background:-moz-linear-gradient(#edd171,#eeaf45);background:linear-gradient(#edd171,#eeaf45)}
        #game-nav>li span{font-weight:700}
        #game-nav>li span:hover,#game-nav>li.active span{background:#be8218}
        ol.subnav>li{color:#fff;font-size:14px;font-weight:400;background-color:#eeaf45}
        ol.subnav>li:hover{background:#be8218}
        #game-list li{border:1px solid #eeaf45}
        #game-list li:hover{color:#fff;background:#edd171;background:-webkit-linear-gradient(#edd171,#eeaf45);background:-o-linear-gradient(#edd171,#eeaf45);background:-moz-linear-gradient(#edd171,#eeaf45);background:linear-gradient(#edd171,#eeaf45)}
        /*   #game-list li:hover .game-logo:before{background:url(../../../images/kyqp/hover.png) no-repeat center;cursor: pointer;}*/
        #pager>li{color:#fff;background-color:#56606a}
        #pager>li:hover{background-color:#f2941a}
        .money{float:right;margin:0}
        .money-cont{background:url(../../../images/kyqp/input.png) no-repeat;width:224px;height:34px}
        .money-btn{position:absolute;right:0;top:2px}
        .money-btn a{font-size:14px;position:absolute;width:100%;height:100%;left:0;text-align:center;line-height:29px;color:#fff;cursor:pointer}
        .money-txt{font-size:23px;position:absolute;top:4px;left:40px;margin:0;color:#fff}
        .money-icon{display:inline-block;width:26px;height:26px;background:url(../../../images/kyqp/money.png) no-repeat;margin-top:4px;margin-left:5px}
        .money-seting{width:100%;height:100%;position:fixed;left:0;top:0;background:rgba(0,0,0,.1)}
        .money-box{border:6px solid rgba(0,0,0,.4);box-shadow:1px 1px 50px rgba(0,0,0,.3);-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;position:absolute;top:50%;left:50%;margin-left:-176px;margin-top:-83px}
        .jbox-close{background:url(../../../images/kyqp/jbox-close1.gif) no-repeat;position:absolute;display:block;cursor:pointer;top:5px;right:4px;width:15px;height:15px}
        .jbox-title-panel{background:#febb05;background:-webkit-gradient(linear,left top,left bottom,from(#fed752),to(#fdad02));background:-moz-linear-gradient(top,#fed752,#fdad02);border-bottom:1px solid #999;height:25px;cursor:move}
        .jbox-title{font-weight:700;color:#fff}
        .jbox-title-icon{background:url(../../../images/kyqp/jbox-title-icon.gif) 3px 5px no-repeat;float:left;width:300px;line-height:24px;padding-left:18px;overflow:hidden;text-overflow:ellipsis;word-break:break-all}
        .game{background-color:#B9B9A3}
        .b_rig{background-color:#FFF;text-align:right;white-space:nowrap}
        .game td{padding:1px 4px;font-size:12px;border-right:1px solid #B9B9A3;border-bottom:1px solid #B9B9A3;font-family:Arial,Helvetica,SimSun,sans-serif}
        .jbox-button{background:#febb05;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fed752', endColorstr='#fdad02');background:-webkit-gradient(linear,left top,left bottom,from(#fed752),to(#fdad02));background:-moz-linear-gradient(top,#fed752,#fdad02);border:1px solid #d89413;color:#fff;border-radius:3px;margin:1px 7px 0 0;height:22px;cursor:default}
        .jbox-button-panel{border-top:1px solid #CCC;background-color:#EEE;height:36px !important;padding:5px 0 5px 0;text-align:right}
        .ng-scope .hover {
            background: rgba(0,0,0,.6);
            width: 140px;
            height: 140px;
            position: absolute;
            left: 9px;
            top: 8px;
            overflow: hidden;
            transition: all 0.4s;
            filter: alpha(opacity = 0);
            -moz-opacity: 0;
            opacity: 0;
            /* border-radius: 10px; */
        }
        .ng-scope .hover a{display: block;z-index: 10;margin: 0 auto; width: 105px; height: 30px; line-height: 30px; text-align: center; color: #fff; font-size: 14px; font-family: "Microsoft Yahei"; border-radius: 5px; position:absolute; top:40px; left:15.5px;text-decoration: none;transition: all 0.3s;}
        .ng-scope .hover .enter { background: #f39800;}
        .ng-scope .hover .testplay { background: #f39800; top:80px; }
        .ng-scope:hover .hover { filter: alpha(opacity = 100); -moz-opacity: 1; opacity: 1; }
        .ng-scope .hover a:hover { background:#09F;  }



    </style>
</head>
<body>

<header id="header">
    <div class="wrapper">
        <div id="logo">
            <a href="#">
                <img src="../../../images/member/2018/logo_<?php echo TPL_FILE_NAME;?>.png?v=3">
            </a>
        </div>
        <div id="search">
            <form class="ng-pristine ng-valid">
                <input type="text" placeholder="请输入游戏名称..." class="ng-pristine ng-valid" id="seachgame">
                <button id="search-btn" type="submit" title="搜寻"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
</header>
<nav>
    <div class="wrapper">
        <ul id="game-nav">
            <li class="ng-scope active">
                <span class="ng-binding">全部游戏</span>
            </li>
            <li class="money" style="float: right;margin: 0">
                <div class="money-cont">
                    <i class="money-icon"></i>
                    <p class="money-txt">0.00</p>
                    <div class="money-btn"><img src="../../../images/kyqp/btn.png"><a onclick="transaction()">额度转换</a></div>
                </div>
            </li>
        </ul>
    </div>
</nav>
<div id="content" class="wrapper">
    <a id="banner" target="_blank">
        <img src="../../../images/kyqp/nav.jpg">
    </a>
    <ul id="game-list">
        <li class="ng-scope" >
            <div class="game-logo">
                <img src="../../../images/kyqp/l1.png">
                <div class="game-text ng-binding" title="KG抢庄牛牛">KG 抢庄牛牛</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <div class="game-logo">
                <img src="../../../images/kyqp/l2.png">
                <div class="game-text ng-binding" title="KG炸金花">KG 炸金花</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <div class="game-logo">
                <img src="../../../images/kyqp/l3.png">
                <div class="game-text ng-binding" title="KG二八杠">KG 二八杠</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <img class="icon icon01" src="../../../images/kyqp/NewGame.gif">
            <div class="game-logo">
                <img src="../../../images/kyqp/l4.png">
                <div class="game-text ng-binding" title="KG三公">KG 三公</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <img class="icon icon01" src="../../../images/kyqp/NewGame.gif">
            <div class="game-logo">
                <img src="../../../images/kyqp/l5.png">
                <div class="game-text ng-binding" title="KG21点">KG 21点</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <div class="game-logo">
                <img src="../../../images/kyqp/l6.png">
                <div class="game-text ng-binding" title="KG德州扑克">KG 德州扑克</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <img class="icon icon01" src="../../../images/kyqp/NewGame.gif">
            <div class="game-logo">
                <img src="../../../images/kyqp/l7.png">
                <div class="game-text ng-binding" title="KG抢庄牌九">KG 抢庄牌九</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>

        </li>
        <li class="ng-scope" >
            <img class="icon icon01" src="../../../images/kyqp/NewGame.gif">
            <div class="game-logo">
                <img src="../../../images/kyqp/l8.png">
                <div class="game-text ng-binding" title="KG通比牛牛">KG 通比牛牛</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <img class="icon icon01" src="../../../images/kyqp/NewGame.gif">
            <div class="game-logo">
                <img src="../../../images/kyqp/l9.png">
                <div class="game-text ng-binding" title="KG押庄龙虎">KG 押庄龙虎</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <img class="icon icon01" src="../../../images/kyqp/NewGame.gif">
            <div class="game-logo">
                <img src="../../../images/kyqp/l10.png">
                <div class="game-text ng-binding" title="KG十三水">KG 十三水</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <img class="icon icon01" src="../../../images/kyqp/NewGame.gif">
            <div class="game-logo">
                <img src="../../../images/kyqp/l11.png">
                <div class="game-text ng-binding" title="KG幸运五张">KG 幸运五张</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <img class="icon icon01" src="../../../images/kyqp/NewGame.gif">
            <div class="game-logo">
                <img src="../../../images/kyqp/l12.png">
                <div class="game-text ng-binding" title="KG极速炸金花">KG 极速炸金花</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>

        <li class="ng-scope" >
            <img class="icon icon01" src="../../../images/kyqp/NewGame.gif">
            <div class="game-logo">
                <img src="../../../images/kyqp/l13.png">
                <div class="game-text ng-binding" title="KG斗地主">KG 斗地主</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
        <li class="ng-scope" >
            <img class="icon icon01" src="../../../images/kyqp/NewGame.gif">
            <div class="game-logo">
                <img src="../../../images/kyqp/l14.png">
                <div class="game-text ng-binding" title="KG百家乐">KG 百家乐</div>
            </div>
            <div class="hover">
                <a href="javascript:;" class="enter" onclick="goToChess()">进入游戏</a>
                <a href="javascript:;" class="testplay" onclick="goToChess('test')">免费试玩</a>
            </div>
        </li>
    </ul>

    <ul id="pager">
        <li>&lt;&lt;</li>
        <li>&lt;</li>
        <li><span class="ng-binding">1</span>/<span class="ng-binding">1</span></li>
        <li>&gt;</li>
        <li>&gt;&gt;</li>
    </ul>
</div>
    <script type="text/javascript" src="../../../../js/jquery.js"></script>
    <script type="text/javascript" src="../../../../js/jbox/jquery.jBox-2.3.min.js"></script>
    <script type="text/javascript" src="../../../../js/jbox/jquery.jBox-zh-CN.js"></script>
    <script>
        var uid = '<?php echo $_SESSION["Oid"];?>';
        var agent = '<?php echo $_SESSION['Agents']?>';
        $(function () {
            if(agent != 'demoguest'){
                var data = {};
                data.uid = uid;
                data.action = 'b';
                $.ajax({
                    type : 'POST',
                    url : 'ky_api.php?_=' + Math.random(),
                    data : data,
                    dataType : 'json',
                    success:function(item) {
                        if(item.code == 0) {
                            $('.money-txt').html(item.data.ky_balance);
                        } else {
                            alert(item.message);
                        }
                    },
                    error:function(){
                        alert('网络异常，请稍后重试！');
                    }
                });
            }else{
                alert('您尚未注册真实账户，仅允许您进入开元棋牌试玩模式！');
            }
        });
        function transaction(){
            if(agent != 'demoguest') {
                $.jBox('get:/app/member/ky/exchange.php?uid=' + uid, {
                    title: "开元棋牌额度转换",
                    buttons: {'关闭': true}
                });
            }else{
                alert('您尚未注册真实账户，暂不允许进行额度转换！');
            }
        }
        // 进入棋牌游戏
        function goToChess(test) {
            var url = 'index.php?uid='+uid ; // 正式链接
            if(test){ // 试玩
                url = 'http://play.ky206.com/jump.do' ; // 正式链接
            }
            window.open(url) ;
        }
        // 搜索游戏
        function seachGame() {
            $("#seachgame").bind('input porpertychange',function(){
                var thisTxt = $("#seachgame").val();
                thisTxt = thisTxt.toUpperCase() ;
                $('#game-list li').find('.game-text').each(function () {
                    var gamename = $(this).attr('title');
                    if(gamename.indexOf(thisTxt)>=0){
                        $(this).parents('li').show() ;
                    }else{
                        $(this).parents('li').hide() ;
                    }
                });

            });
        }
        seachGame();
    </script>
</body>
</html>