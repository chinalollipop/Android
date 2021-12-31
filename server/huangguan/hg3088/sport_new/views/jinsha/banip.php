<?php
session_start();
header ("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

require ("../../app/member/include/config.inc.php");
require ("../../app/member/include/address.mem.php");

$ip_addr =get_ip();

?>

<html>
<head>
    <title>Welcome </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="<?php echo TPL_NAME;?>images/favicon.ico" type="image/x-icon">
    <meta name="keywords" content="澳门线上娱乐,澳门线上娱乐登入,澳门线上娱乐平台">
    <meta name="description" content="澳门线上娱乐">
    <link rel="stylesheet" type="text/css" href="style/common.css?v=<?php echo AUTOVER; ?>" >
    <style>
        .banip_bg{background:url(/<?php echo TPL_NAME;?>images/limit_ip_bg.jpg) top no-repeat;height:100%;background-size:cover}
        .banip_bg .logo{width:270px;height:95px;background:url(/<?php echo TPL_NAME;?>images/header/LOGO.png?v=3) center no-repeat;padding:50px}
        .ban_center{overflow:hidden;width:800px;margin:70px auto 0;color:#fff}
        .ban_center .f_left{width:150px;height:150px;background:url(/<?php echo TPL_NAME;?>images/limit_ip_icon.png) no-repeat}
        .ban_center p{font-size:20px;line-height:50px}
        .ban_center p:first-child{font-size:30px}
        .ban_center p .tip{color:#63636c}
        .ban_footer{position:absolute;color:#fff;bottom:20px;width:420px;left:50%;margin-left:-210px;text-align:center}
        .ip{color:#af5d18}

    </style>
</head>

<body >
    <div class="banip_bg">
        <div class="logo"></div>
        <div class="w_1000">
            <div class="ban_center">
                <div class="f_left">

                </div>
                <div class="f_right">
                    <p>当前地区受限制<span class="tip"> （请更换网络） </span></p>
                    <p>你当前地区不属于我们的服务范围 您的IP来自：<span class="ip"> <?php echo $ip_addr;?> </span></p>
                    <p> 如有任何疑问请联系<a class="to_livechat" style="color: #7aa74c;text-decoration: underline;">在线客服</a> 或拨打到：<span class="service_phone"></span> </p>
                </div>
            </div>
            <div class="ban_footer"> COPYRIGHT  © <?php echo date('Y');?>澳门线上娱乐 ALL RIGHTS RESERVED版权所有 </div>
        </div>

    </div>

</body>
<script type="text/javascript" src="/js/jquery.js"></script>
<script>
    $(function () {
        var web_configbase = JSON.parse(localStorage.getItem('webconfigbase'));
        $('.to_livechat').attr({'href':web_configbase.service_meiqia,'target':'_blank'});
        $('.service_phone').text(web_configbase.service_phone_phl);
    })

</script>
</html>
