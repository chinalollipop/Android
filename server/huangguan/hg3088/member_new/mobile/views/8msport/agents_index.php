<?php
session_start();
include_once('../../include/config.inc.php');
$tip = isset($_REQUEST['tip'])?$_REQUEST['tip']:'' ; // 用于app 跳转到这个页面 ?tip=app
$uid=$_SESSION['Oid'];

?>

<html class="zh-cn"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="../../../style/icalendar.css?v=<?php echo AUTOVER; ?>">
    <title class="web-title"></title>
<style type="text/css">
    body {background: url(images/dl_bg.jpg) center no-repeat;background-position-y: 3.5rem;background-size: 100%;}
    .agent_con{margin-top:71%;color:#000;line-height:2rem;padding:0 10%}
    .agent_con .btn a{display:block;width:70%;margin:1.5rem auto;line-height:2.8rem;border-radius:20px}
</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header <?php if($tip){echo 'hide-cont';}?>">

    </div>

    <!-- 中间注册表单内容 -->
    <div class="content-center">
        <div class="agent_con">
            <p>
                我们充满活力拥有品牌最诚信，并且有前瞻性以及高度的粘附力，期待您的加入！
            </p>
            <div class="btn">
                <a class="linear-color-1" href="agents_reg.php?type=lmfa"> 联盟方案 </a>
                <a class="linear-color-1" href="agents_reg.php?type=lmxy"> 联盟协议 </a>
                <a class="linear-color-1" href="agents_reg.php?type=dlzc"> 代理注册 </a>
            </div>
        </div>
    </div>

    <!-- 底部 -->
    <div id="footer" class="<?php if($tip){echo 'hide-cont';}?>">

    </div>

</div>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/validate.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">

    var uid = '<?php echo $uid?>' ;
    setLoginHeaderAction('注册') ;
    setFooterAction(uid) ; // 在 addServerUrl 前调用
    addServerUrl() ;
    agreeMentAction() ;


</script>

</body>
</html>