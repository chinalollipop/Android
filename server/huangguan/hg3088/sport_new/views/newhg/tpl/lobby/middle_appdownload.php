<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid']; // 判断是否已登录

?>
<style>
    .download_wrppaer{background:url(<?php echo $tplNmaeSession;?>images/phoneBg.jpg?v=1);background-size:100% 100%;padding:54px 0 0;overflow: hidden;}
    .download_wrppaer .mian_conten{width:1439px;margin:auto}
    .download_wrppaer .mian_conten .left_img {
        float: left;
        position: absolute;
        top: -2%;
        left: -5%;
        z-index: 0;
        width: 70%;
    }
    .download_wrppaer .mian_conten .left_img img{width:100%;min-width:1000px}
    .download_wrppaer .mian_conten .right_box{float:right;width:48%;text-align:center;position:relative;z-index:1}
    .download_wrppaer .mian_conten .right_box .right_logo{height:87px}
    .download_wrppaer .mian_conten .right_box .right_logo img{height:100%}
    .download_wrppaer .mian_conten .right_box .text_one{font-size:40px;color:#d96217;margin-top:30px;padding-bottom:12px;border-bottom:1px solid #d96217;display:inline-block;width:-webkit-max-content;width:-moz-max-content;width:max-content;margin-bottom:13px}
    .download_wrppaer .mian_conten .right_box .text_two{font-size:26px;color:#FEA219;margin-bottom:35px}
    .download_wrppaer .mian_conten .right_box .qr_list_wrapper{width:547px;margin:auto;overflow: hidden;}
    .download_wrppaer .mian_conten .right_box .qr_list_wrapper .qr_box{float:left;width:250px;text-align:center}
    .download_wrppaer .mian_conten .right_box .qr_list_wrapper .qr_box .qr_img_wrapper{width:139px;height:130px;line-height:128px;border-radius:20px;margin:auto}
    .download_wrppaer .mian_conten .right_box .qr_list_wrapper .qr_box .qr_img_wrapper img,.download_wrppaer .mian_conten .right_box .qr_list_wrapper .qr_box .qr_img_wrapper span{display:inline-block;width:110px;height:110px;background-size: 100% !important;}
    .download_wrppaer .mian_conten .right_box .qr_list_wrapper .qr_box .qr_text{margin-top:23px;font-size:24px}
    .download_wrppaer .mian_conten .right_box .qr_list_wrapper .qr_box .qr_desc{margin-top:22px;color:#4c4c4c}
    .download_wrppaer .mian_conten .right_box .safety{margin-top:63px;font-size:20px;color:#b97550;height:43px;line-height:43px}
    .download_wrppaer .mian_conten .right_box .text_three{margin:19px auto;color:#b97550;width: 80%;}
    @media (min-width:1750px){
        .download_wrppaer{padding-bottom: 145px;}
        .download_wrppaer .mian_conten .right_box{width: 38%;}
    }
</style>

<div class="download_wrppaer router_view_mian">
    <div class="mian_conten cl">
        <div class="left_img">
            <img src="<?php echo $tplNmaeSession;?>images/appphone.png?v=1" alt="">
        </div>
        <div class="right_box">
            <p class="right_logo">
                <img src="<?php echo $tplNmaeSession;?>images/logo_app.png?v=1" alt="">
            </p>
            <p class="text_one">
                手机游玩新模式 轻松赚大钱
            </p>
            <p class="text_two">
                美女游戏，体育投注，随时享受!
            </p>
            <div class="qr_list_wrapper cl">
                <div class="qr_box">
                    <p class="qr_img_wrapper them_bg_color_three">
                        <span class="download_android_app"></span>
                    </p>
                    <p class="them_font_color qr_text">
                        Android版
                    </p>
                    <p class="qr_desc">
                        兼容各种品牌Android系统的手机引领同行业移动互联网潮流，娱乐完美体验
                    </p>
                </div>
                <div class="qr_box">
                    <p class="qr_img_wrapper them_bg_color_three">
                        <span class="download_ios_app"></span>
                    </p>
                    <p class="them_font_color qr_text">
                        IOS版
                    </p>
                    <p class="qr_desc">
                        兼容各种品牌IOS系统的手机引领同行业移动互联网潮流，娱乐完美体验
                    </p>
                </div>
            </div>
            <p class="safety">
                安全有保障 服务新趋势
            </p>
            <p class="text_three">
                登入密码保障，充值、提现迅速到帐为您打造最安全的服务平台，让您畅玩无忧、领奖无虑~
            </p>
        </div>
    </div>

</div>


<script type="text/javascript">
    $(function () {


        
    })
</script>