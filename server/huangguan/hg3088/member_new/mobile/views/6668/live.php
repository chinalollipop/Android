<?php
include_once('../../include/config.inc.php');
$uid=$_SESSION["Oid"];
$userid = $_SESSION['userid'];
$username = $_SESSION['UserName']; // 拿到用户名


?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <!--<link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
        <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

        <title class="web-title"></title>

    </head>
    <body >
    <div id="container" class="dialog-content">

        <!-- 头部 -->
        <div class="header ">

        </div>

        <!-- 中间部分 -->
        <div class="content-center">

            <div class="liveNav">
                <span class="live-money">真人额度：￥<span id="video_blance" class="live_money"></span></span>
                <a class="live-change limit-toggle"><span class="change-icon"></span>额度转换</a>
            </div>

            <div class="liv-game-list">
                <a class="type1" onclick="ifHasLogin('/zrsx_login.php?uid=<?php echo $uid;?>','win','<?php echo $uid;?>')" ><div></div>
                    <!--<span class="type-icon"></span><p>百家乐</p>-->
                </a>
                <a class="type2" onclick="ifHasLogin('/zrsx_login.php?uid=<?php echo $uid;?>','win','<?php echo $uid;?>')" ><div></div>
                    <!--<span class="type-icon"></span><p>龙虎斗</p>-->
                </a>
                <a class="type3" onclick="ifHasLogin('/zrsx_login.php?uid=<?php echo $uid;?>','win','<?php echo $uid;?>')" ><div></div>
                    <!--<span class="type-icon"></span><p>轮盘</p>-->
                </a>
                <a class="type4" onclick="ifHasLogin('/zrsx_login.php?uid=<?php echo $uid;?>','win','<?php echo $uid;?>')" ><div></div>
                    <!--<span class="type-icon"></span><p>骰子</p>-->
                </a>

                <div class="clear"></div><!-- 清除浮动 -->
            </div>
        </div>

        <!-- 底部footer -->
        <div id="footer">

        </div>

     </div>

    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/animate.js"></script>
    <script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
     <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
    <script type="text/javascript" src="../../js/animate.js"></script>

    <script type="text/javascript">
        var uid = '<?php echo $uid;?>';
        if( uid!='' ) {
            get_blance('balance');

        }


        var usermon = getCookieAction('member_money') ; // 获取信息cookie

        setLoginHeaderAction('真人视讯','','',usermon,uid) ;
        setFooterAction(uid);  // 在 addServerUrl 前调用



    </script>


    </body>
</html>