<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Content-type: text/html; charset=utf-8");

require ("../../app/agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$navtitle = isset($_REQUEST['navtitle'])?$_REQUEST['navtitle']:'';


$agent_qq = getSysConfig('agents_service_qq'); // 代理 qq
$mq_server = getSysConfig('service_meiqia'); // 美洽客服
$new_url = getSysConfig('new_web_url'); // 最新网址
if(TPL_FILE_NAME=='wnsr'){
    $agent_qq = getSysConfig('vns_agents_service_qq'); // 代理 qq
    $mq_server = getSysConfig('vns_service_meiqia'); // 美洽客服
    $new_url = getSysConfig('vns_new_web_url'); // 最新网址
}


?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <META name="keywords" content="<?php echo COMPANY_NAME;?>,<?php echo COMPANY_NAME;?>登入,<?php echo COMPANY_NAME;?>平台">
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="/images/favicon_<?php echo TPL_FILE_NAME;?>.ico" type="image/x-icon"/>
    <link href="../css/main.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title> <?php echo COMPANY_NAME;?> </title>
    <style type="text/css">
        .contact_center .nav{line-height:3rem;padding:0 2%;background:#fff;border-bottom:1px solid #f1f1f1}
        .contact_center .nav a{font-size: 1.1rem;color:#4e525e;width:20%;margin-right:5%}
        .contact_center .nav a.active{color:#5da2ea;border-bottom:2px solid #5da2ea}
        .contact_center .wbnr{margin:2rem auto;padding:0 2%}
        .contact_center .wbnr>div{display:none}
        .contact_center .wbnr .btn_zxkf{display:inline-block;width:100%;padding:.8rem 0}
        .contact_center .wbnr>div>div{line-height:2.5rem;justify-content:space-between;color:#000}
        .contact_center .wbnr .nav_dlqq>div{padding:0 4%}
        .contact_center .wbnr .nav_dlqq>div .qq_text,.contact_center .wbnr .nav_zxwz span{font-size:1.2rem}
        .contact_center .wbnr .nav_dlqq>div span{display:inline-block;width:2.5rem;height:2.5rem;background:url(../images/qq_logo.png) no-repeat;background-size:100%}
        .contact_center .wbnr .nav_dlqq .btn_dlqq{padding:0 2rem}
        .contact_center .wbnr .btn_zxwz{padding:0 3rem}
    </style>
</head>
<body>
<div class="contentAll flex">
    <?php include 'middle_header.php'; ?>

    <div class="contact_center">
        <div class="nav flex navAction">
            <a class="active" data-type="zxkf"> 在线客服 </a>
            <a data-type="dlqq"> 代理QQ </a>
            <a data-type="zxwz"> 最新网址 </a>
        </div>
        <div class="wbnr navShowAll">
            <div class="nav_zxkf" style="display: block">
                <a href="javascript:;" class="btn_zxkf linear_1" onclick="window.open('<?php echo $mq_server;?>')"> 点击在线客服 </a>
            </div>
            <div class="nav_dlqq" >
                <div class="flex">
                    <div class="qq_text flex"> <span class="icon"></span> &nbsp;&nbsp; <?php echo $agent_qq;?> </div>
                    <a class="btn_dlqq linear_1" href="javascript:window.open('mqqwpa://im/chat?chat_type=wpa&uin=<?php echo $agent_qq;?>&version=1&src_type=web&web_src=oicqzone.com');"> 立即联系 </a>
                </div>

            </div>
            <div class="nav_zxwz" >
                <div class="flex">
                    <span> <?php echo $new_url;?> </span>
                    <a class="btn_zxwz linear_1" href="javascript:;" onclick="window.open('<?php echo $new_url;?>')" > 会员电脑端 </a>
                </div>

            </div>
        </div>
    </div>

    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    settingHeight('.contentAll');
    changeTagNav();
    // 导航切换
    function changeTagNav() {
        $('.navAction').on('click','a',function () {
            var type = $(this).attr('data-type');
            $(this).addClass('active').siblings().removeClass('active');
            $('.navShowAll>div').hide();
            $('.nav_'+type).show();

        })
    }
</script>
</body>
</html>
