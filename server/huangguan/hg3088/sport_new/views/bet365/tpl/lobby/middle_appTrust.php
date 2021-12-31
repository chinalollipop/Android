<?php
session_start();
include "../../../../app/member/include/config.inc.php";

$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid']; // 判断是否已登录
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';

?>
<link rel="shortcut icon" href="/<?php echo TPL_NAME;?>images/app/ios/favicon.ico" type="image/x-icon">

<style>
    *{margin:0;padding:0;box-sizing: border-box;}
    body{font-family:"Microsoft YaHei"!important;font-size:14px;background:#e2f2fd}
    hr{height:1px;width:80%;background:#ccc;margin:0 auto}
    a{text-decoration: none;}
    p{color:#ffffff}
    img{border: 0;vertical-align: middle;}
    .container{width:100%}
    .shell{margin:0 auto;max-width:572px}
    .header{width:100%;height:80px;text-align:center;background:#007aff;color:#ffffff;line-height:80px;font-size:20px}
    .header-logo{margin:0 auto}
    .proc p{display:block}
    .con-txt{height:80px;margin-top:20px}
    .txt-install1{height:54px}
    .con-txt h2.num-1{height:20px}
    .con-txt h2.num-2{height:60px}
    .con-txt h2.num-3{height:120px}
    .title-app{padding:1em 0;font-size:1.25em;font-weight:bold;border-bottom:1px solid #ddd}
    .proc h2{padding-left:5px;float:left;display:block;font-size:16px;margin:5px 10px 10px 5px;color:#ffffff}
    .proc .proc-title{cursor:pointer;overflow:hidden;background:#007aff url(/<?php echo TPL_NAME;?>images/app/ios/tips.png) center right no-repeat;line-height:34px}
    .proc .proc-title-hover{background:#007aff url(/<?php echo TPL_NAME;?>images/app/ios/tips_hover.png) center right no-repeat}
    .proc .proc-title p{padding:0 20px 0 0;line-height: 44px;}
    .con-proc{overflow:hidden;padding:10px 0;clear:both;display:none}
    .myblock{display:block}
    .con-proc img{width:49.6%;margin:10px 0}
    .con-proc img.step-img2{width:100%}
    .proc{margin-top:20px}
    .proc a{color:#FFF}
    .proc .highlight{margin:0 auto;color:#007aff}
    .footer{width:100%;padding:2em 0em;margin-top:20px}
    .footer .footer-logo{margin:0 auto;display:block;width:92px;height:30px}
    .footer .copyright{color:#848484;text-align:center;font-size:0.5em;font-family:arial;margin-top:0.5em}
    .mod-btn{width:152px;height:44px;line-height:44px;font-size:14px;color:#fff;margin:15px auto 0px auto;letter-spacing:0;text-align:center;background:#007aff;cursor:pointer;border-radius:5px;-webkit-border-radius:5px;-moz-border-radius:5px;-ms-border-radius:5px;-o-border-radius:5px}
    .mod-btn a{text-decoration:none;display:block;color:#fff}
    @media (max-width:320px){.title-app{font-size:1.2em}
        .proc p{font-size:0.9em}
        .proc h2{font-size:1em}
        .con-txt h2.num-1{height:30px}
        .proc .proc-title{line-height:18px}
        .proc .proc-title p{padding:2px 20px 0 0}
        .proc .proc-title{background:#007aff url(/<?php echo TPL_NAME;?>images/app/ios/tips320.png) center right no-repeat}
        .proc .proc-title-hover{background:#007aff url(/<?php echo TPL_NAME;?>images/app/ios/tips_hover320.png) center right no-repeat}
        .footer .copyright{font-size:0.6em}
    }@media (min-width:321px) and (max-width:480px){.title-app{font-size:1.2em}
        .proc p{font-size:0.9em}
        .proc .proc-title p{padding:6px 30px 0 0}
        .con-txt h2.num-1{height:32px}
        .con-txt h2.num-2{height:60px}
        .con-txt h2.num-3{height:140px}
        .proc .proc-title{line-height:22px}
        .footer .copyright{font-size:0.6em}
    }
</style>

<div class="container">
    <div class="shell">
    <?php
    if($type=='reg'){ // 注册教程

    ?>
            <div class="header">移动端​​APP注册和登录教程</div>


            <div class="proc">
                <div class="proc-title proc-title-hover">
                    <h2>注册：</h2>
                    <p>从
                        <a id="app_reg_jc" target="_blank"> </a>，获取识别码即可注册
                    </p>
                </div>
                <div class="con-proc myblock">
                    <p></p><center><img class="step-img2" src="/<?php echo TPL_NAME;?>images/app/ios/reg1.png"></center><p></p></div></div>
            <div class="proc">
                <div class="proc-title">
                    <h2>登录第一步：</h2><p>登入网页版本获取自己的会员帐号,密码和网页版本一致</p></div>
                <div class="con-proc">
                    <p></p><center><img class="step-img2" src="/<?php echo TPL_NAME;?>images/app/ios/reg2.png" width="519"></center><p></p></div></div>
            <div class="proc">
                <div class="proc-title">
                    <h2>登录第二步：</h2><p>使用获取到的会员帐号和密码进行登录</p></div>
                <div class="con-proc">
                    <p></p><center><img class="step-img2" src="/<?php echo TPL_NAME;?>images/app/ios/reg3.png"></center><p></p></div></div>

        </div>
    <?php
    }else{

    ?>

        <!-- ios 信任教程 -->
            <div class="header">移动端APP信任授权教程</div>
            <div class="proc">
                <div class="proc-title proc-title-hover">
                    <h2>第一步：</h2><p>设置 ＞ 通用</p></div>
                <div class="con-proc myblock">
                    <p><img class="step-img" src="/<?php echo TPL_NAME;?>images/app/ios/IMG_00531.png">
                        <img class="step-img" src="/<?php echo TPL_NAME;?>images/app/ios/IMG_00541.png"></p></div></div>
            <div class="proc">
                <div class="proc-title">
                    <h2>第二步：</h2><p>设备管理 ＞ 授权确认</p></div>
                <div class="con-proc">
                    <p><img class="step-img" src="/<?php echo TPL_NAME;?>images/app/ios/IMG_00551.png">
                        <img class="step-img" src="/<?php echo TPL_NAME;?>images/app/ios/IMG_00571.png"></p></div></div>
            <div class="proc">
                <div class="proc-title">
                    <h2>第三步：</h2><p>完成！授权完毕</p></div>
                <div class="con-proc">
                    <p></p>
                    <center><img class="step-img" src="/<?php echo TPL_NAME;?>images/app/ios/IMG_00581.png"></center><p></p>
                </div>
            </div>


    <?php
    }
    ?>
            <div class="footer">
                <p class="copyright">Copyright © All Rights Reserved.</p>
            </div>
        </div>
</div>

<script type="text/javascript">
    var $cur = document.getElementById('app_reg_jc');
    var url = '<?php echo HTTPS_HEAD;?>://'+window.location.host+'?app';
    $cur.setAttribute('href',url);
    $cur.innerHTML = url;
    //.write('<a href="http://'+window.location.host+'/cn/mobile#1" target="_blank"></a>');
</script>