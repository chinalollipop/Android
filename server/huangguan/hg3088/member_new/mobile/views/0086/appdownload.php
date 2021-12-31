<?php
include_once('../../include/config.inc.php');

$uid = $_SESSION['Oid'];
?>
<html class="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <title class="web-title"></title>
<style>
    .app_content {padding: 20px 10px;max-width: 640px;margin: 0 auto;overflow: hidden;}
    .app_bg {width: 12rem;height: 12rem;background: url(images/appdownload_bg.png) no-repeat;background-size: 100%;}
    .app_bg img {width: 95%;}
    .app_title {width: calc(100% - 14rem);margin-top: 1rem;}
    .app_title p {color: #2c8dbc;}
    .title_sm {font-size: 1.2rem;}
    .title_big {font-size: 1.8rem;}
    .app_title>a {display:block;background: #2a8ebf;margin: 10px auto;padding: 4px 0;border-radius: 50px;width: 80%;display: flex;font-size: .9rem;}
    .app_title>a .icon {display: inline-block;flex: 1.5;}
    .app_title>a .icon i {display: inline-block;width: 18px;height: 18px;}
    .app_title>a .text {flex: 3;text-align: left;}
    .ios_icon {background: url(images/ios.png) no-repeat;background-size: 100%;}
    .android_icon {background: url(images/az.png) no-repeat;background-size: 100%;}

</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->

    <div class="content-center deposit">
        <div class="app_content">
            <div class="left app_bg">
                <img src="<?php echo getPicConfig('download_ios_url'); ?>" alt="APP下载"/>
            </div>
            <div class="right app_title">
                <p class="title_sm"> 扫一扫二维码下载 </p>
                <p class="title_big"> 手机APP </p>
                <a class="appdownload_url ios" target="_blank">
                    <span class="icon ">
                        <i class="ios_icon"> </i>
                    </span>
                    <span class="text"> ios下载 </span>
                </a>
                <a class="appdownload_url android" target="_blank">
                    <span class="icon">
                         <i class="android_icon"> </i>
                    </span>
                    <span class="text"> 安卓下载 </span>
                </a>
            </div>
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
<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    var title = 'APP下载' ;

    $('.appdownload_url').attr('href',web_configbase.download_app_page); // APP下载链接

    setLoginHeaderAction(title,'','',usermon,uid) ;
    setFooterAction(uid) ;



</script>
</body>
</html>