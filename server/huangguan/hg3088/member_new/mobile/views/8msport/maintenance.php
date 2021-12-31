<?php
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include_once('../../include/config.inc.php');

$uid = isset($_SESSION["Oid"]) ? $_SESSION["Oid"] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <title class="web-title">维护提示</title>
    <style type="text/css">
        html,body,div,p{margin:0px;padding:0px;font-size:12px;font-family:微软雅黑,宋体,Verdana,Arial}
        .content{width:300px;border:1px solid #493721;margin:50px auto;font-size:14px}
        .content .title{text-align:center;background-color:#493721;margin:0;line-height:40px;color:#fff;font-size:16px}
        .tips{color:black;font-size:14px;padding:0;padding-top:20px;width:60%;overflow:hidden;margin:0 auto}
        .tips span{color:red}
        .content .ico{text-align:center;margin:0 auto}
        .content .bottom{text-align:center;padding-bottom:20px;font-size:14px;color: #000;}
    </style>
<body>
<?php
$pageMark = isset($_REQUEST['type']) && $_REQUEST['type'] ? trim($_REQUEST['type']) : 'rb';
$content =  isset($_REQUEST['content']) && $_REQUEST['content'] ? $_REQUEST['content'] : '';
$title =  isset($_REQUEST['title']) && $_REQUEST['title'] ? $_REQUEST['title'] : '手机维护中';

?>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>
    <!-- 中间主体内容 -->
    <div class="content-center">
        <div class="content">
            <p class="title"><?php echo $title;?></p>
            <div class="tips"><?php echo $content;?></div>
            <p class="ico"><img src="images/ico.png" width="150" height="150" alt="维护"></p>
            <p class="bottom">您可以进行平台其他游戏！<br>感谢您的耐心等候。</p>
        </div>
    </div>
    <!-- 底部footer -->
    <div id="footer">

    </div>
</div>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    setLoginHeaderAction('系统维护','','',usermon,uid) ;
    setFooterAction(uid) ;
</script>
</body>
</html>
