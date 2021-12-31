<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid']; // 判断是否已登录

?>
<style>
    .app_download{height:530px;background:url(<?php echo $tplNmaeSession;?>images/phoneBg.jpg);padding-top:70px}
    .app_download .app_wz{width:454px;height:174px;background:url(<?php echo $tplNmaeSession;?>images/appdowntip.png) no-repeat;}
    .app_xr_all {line-height: 80px;font-size: 20px;color: #888;padding-left: 12px;}
    .app_xr_all a{color: #ff9a02;}
    .app_xr_all span{display: inline-block;}
    .app_xr_all .question_icon{width: 22px;height: 22px;background:url(<?php echo $tplNmaeSession;?>images/app_qu.png) no-repeat;}
    .app_download .app_txt a{position:relative;display:block;width:240px;height:60px;line-height:60px;background:#ff9a02;background:linear-gradient(to bottom,#ffba02 0%,#ff9a02 100%);border-radius:50px;font-size:20px;text-align:center;margin-bottom:20px}
    .app_download .app_txt a:before{position:absolute;display:inline-block;content:'';width:32px;height:34px;margin:14px -38px}
    .app_download .app_txt span{display: inline-block;width: 143px;height: 143px;background-size: 100% !important;}
    .app_download .app_bottom .left .left a:first-child:before{background:url(<?php echo $tplNmaeSession;?>images/andriod_icon.png) no-repeat}
    .app_download .app_bottom .left .right a:first-child:before{background:url(<?php echo $tplNmaeSession;?>images/ios_icon.png) no-repeat}
    .app_download .phone_bg{width:307px;height:515px;background:url(<?php echo $tplNmaeSession;?>images/appphone.png) no-repeat}
    .app_download .ewm{margin: 0 10px;text-align: center;}
    .app_download .ewm img{width:143px; margin: 0 auto;}
</style>

<div class="app_download">
    <div class="w_1000">
        <div class="app_bottom">
            <div class="left">
                <div class="app_wz"> </div>
                <div class="app_xr_all">
                    <span class="question_icon"></span>
                    <span>
                        APP安装和授权 点此查看<a href="<?php echo $tplNmaeSession;?>tpl/lobby/middle_appTrust.php" target="_blank">IOS版教程</a>
                    </span>
                </div>
                <div class="app_txt ewm left">
                    <a >android</a>
                    <span class="download_android_app"></span>
                </div>
                <div class="app_txt ewm right">
                    <a >iPhone</a>
                    <span class="download_ios_app"></span>
                </div>
            </div>
            <div class="right phone_bg"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {


        
    })
</script>