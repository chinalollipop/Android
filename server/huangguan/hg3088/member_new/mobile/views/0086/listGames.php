<?php
session_start();
$uid=$_SESSION["Oid"];
$userid = $_SESSION['userid'];
$username = $_SESSION['UserName']; // 拿到用户名
$tplname = $_SESSION['TPL_NAME_SESSION'];
$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:'ag'; // 不传默认ag ,ag mg cq9 mw

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="/style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="style/iphone.css?v=<?php echo $_SESSION['AUTOVER_SESSION']; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title class="web-title"></title>
<style type="text/css">
    .game_list{width:94%;margin:5% auto;overflow:hidden}
    .game_list li{width:47%;height:5rem;float:left;background:rgba(250,250,250,1);border-radius:5px;box-shadow:0px 0px 4px #bbb;margin:2%;position:relative}
    .game_list li:nth-child(1):after,.game_list li:nth-child(2):after{position:absolute;content:"";display:inline-block;width:2.2rem;height:2.2rem;top:0;right:0;background:url(/images/gameicon/remen.png) no-repeat;background-size:contain}
    .game_list li:nth-child(2n-1){margin-left:1%}
    .game_list li:nth-child(2n){margin-right:1%}
    .game_list li a{display:inline-block;width:100%;height:100%;color:rgba(124,124,124,1);font-size:1.2rem}
    .game_list li a span{position:relative;display:inline-block;line-height:5rem;width:54%;text-align:left}
    .game_list li a .gamelogo{float:left;width:40%;height:3rem;margin:1rem 0 0 3%}
    .game_list li a .fg_icon{background:url("/images/gameicon/icon_fg.png") center no-repeat;background-size:contain}
    .game_list li a .ag_icon{background:url("/images/gameicon/icon_ag.png") center no-repeat;background-size:contain}
    .game_list li a .cq9_icon{background:url("/images/gameicon/icon_cq9.png") center no-repeat;background-size:contain}
    .game_list li a .mg_icon{background:url("/images/gameicon/icon_mg.png") center no-repeat;background-size:contain}
    .game_list li a .mw_icon{background:url("/images/gameicon/icon_mw.png") center no-repeat;background-size:contain}
    .game_list li a span:last-child:after{content:"";display:inline-block;vertical-align:middle;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-box-sizing:border-box;box-sizing:border-box;position:absolute;height:.8rem;width:.8rem;border-right:2px solid #8a8a8a;border-top:2px solid #8a8a8a;margin:2rem 0 0 0;right:7%}
</style>
</head>
<body >
<div id="container" class="dialog-content">

    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间部分 -->
    <div class="content-center">
        <ul class="game_list">
            <li>
                <a href="/<?php echo $tplname;?>games.php?gametype=fg">
                    <span class="gamelogo fg_icon"></span>
                    <span>FG电子</span>
                </a>
            </li>
            <li>
                <a href="/<?php echo $tplname;?>games.php?gametype=ag">
                    <span class="gamelogo ag_icon"></span>
                    <span>AG电子</span>
                </a>
            </li>
            <li>
                <a href="/<?php echo $tplname;?>games.php?gametype=cq">
                    <span class="gamelogo cq9_icon"></span>
                    <span>CQ9电子</span>
                </a>
            </li>
            <li>
                <a href="/<?php echo $tplname;?>games.php?gametype=mg">
                    <span class="gamelogo mg_icon"></span>
                    <span>MG电子</span>
                </a>
            </li>
            <li>
                <a href="/<?php echo $tplname;?>games.php?gametype=mw">
                    <span class="gamelogo mw_icon"></span>
                    <span>MW电子</span>
                </a>
            </li>
        </ul>

    </div>


    <div class="clear"></div>

    <!-- 底部footer -->
    <div id="footer">

    </div>


</div>

<script type="text/javascript" src="/js/zepto.min.js"></script>
<script type="text/javascript" src="/js/main.js?v=<?php echo $_SESSION['AUTOVER_SESSION']; ?>"></script>


<script type="text/javascript">
    var uid = '<?php echo $uid;?>';
    var gametype = '<?php echo $gametype;?>';
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    setLoginHeaderAction('电子游戏','','',usermon,uid) ;
    setFooterAction(uid);  // 在 addServerUrl 前调用


</script>


</body>
</html>