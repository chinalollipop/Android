<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid']; // 判断是否已登录

$host = $_SESSION['HOST_SESSION'];

?>
<style>
    .app_download{height:750px;background:url(<?php echo $tplNmaeSession;?>images/phoneBg.jpg);padding-top:50px;overflow:hidden}
    .app_download .app_wz{width:100%;height:250px;background:url(<?php echo $tplNmaeSession;?>images/appdowntip.png) bottom center no-repeat;background-size:100%}
    .app_xr_all{line-height:24px;color:#888;margin:25px auto 35px}
    .app_xr_all a{color:#ff9a02}
    .app_xr_all span{display:inline-block}
    .app_xr_all .question_icon{width:22px;height:22px;background:url(<?php echo $tplNmaeSession;?>images/app_qu.png) no-repeat}
    .app_download .app_txt a{position:relative;display:block;width:86px;height:36px;line-height:36px;border-radius:5px !important;font-size:16px;margin-bottom:20px;padding-left:52px}
    .app_download .app_txt a:before{position:absolute;display:inline-block;content:'';width:25px;height:25px;margin:5px -30px}
    .app_download .app_bottom .left{width:580px}
    .app_download .app_txt:nth-child(2) a:before{background:url(<?php echo $tplNmaeSession;?>images/andriod_icon.png) center no-repeat;background-size:86%}
    .app_download .app_txt:nth-child(1) a:before{background:url(<?php echo $tplNmaeSession;?>images/ios_icon.png) center no-repeat;background-size:86%}
    .app_download .phone_bg{width:680px;height:750px;background:url(<?php echo $tplNmaeSession;?>images/appphone.png?v=1) top center no-repeat}
    .app_xz{display:-webkit-flex;display:flex}
    .app_download .ewm{margin:0 15px}
    .app_download .ewm:before{position:absolute;content:'';display:inline-block;width:132px;height:134px;background:url(<?php echo $tplNmaeSession;?>images/app_k.png) center no-repeat}
    .app_download .ewm:last-child:before{display:none}
    .app_download .ewm p{color:#888;text-align:center;margin:20px 0 30px;line-height:24px}
    .app_download .ewm img,.app_download .ewm span{display:block;width:120px;height:120px;margin:6px;position:relative;background-size: 100% !important;}
    .app_download .ewm:last-child span{width: auto;}
    .app_download .ewm:last-child img{margin-left:26px}
    .app_download .ewm p span{color:#2594fc}
</style>

<div class="app_download">
    <div class="w_1200" style="width: 1300px;">
        <div class="app_bottom">
            <div class="left">
                <div class="app_wz"> </div>
                <div class="app_xr_all">
                   业内最高赔率，覆盖世界各地赛事，让球，大小，半全场，波胆单双，总入球，连串过关<br>
                    等多元竞猜。更有动画直播，视频直播，让您轻松体验聊球投注，乐在其中。
                </div>

                <div class="app_xz">
                    <div class="app_txt ewm ">
                        <span class="download_ios_app"></span>
                        <p> 扫二维码下载IOS </p>
                        <a class="btn_game"> 苹果app </a>
                    </div>
                    <div class="app_txt ewm  ">
                        <span class="download_android_app"></span>
                        <p> 扫二维码下载android </p>
                        <a class="btn_game"> 安卓app </a>
                    </div>
                    <div class="app_txt ewm  ">
                        <img src="<?php echo $tplNmaeSession;?>images/h5_icon.png" alt="h5">
                        <p> 使用浏览器输入以下网址<br>免下载访问<br>
                        <span> <?php echo getSysConfig('download_app_page');?> </span>
                        </p>
                    </div>

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