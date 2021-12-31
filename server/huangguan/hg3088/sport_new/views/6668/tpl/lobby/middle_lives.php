<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

?>
<style>
    .zr_box{position: relative;}
    .zr_box .zr_bg{width:100%;height:700px;background:url(<?php echo TPL_NAME;?>images/live/ag_zr_bg.jpg) no-repeat center center;position:relative}
    .zr_box .zr_og_box{width:100%;height:700px;background:url(<?php echo TPL_NAME;?>images/live/og_zr_bg.jpg) no-repeat center center;position:relative}
    .zr_box .zr_bbin_box{width:100%;height:700px;background:url(<?php echo TPL_NAME;?>images/live/bbin_zr_bg.jpg) no-repeat center center;position:relative}
    .zr_box .wrap{width:1200px;margin:0 auto;position:relative}
    .wrap .content-box{width:50%;margin-top:120px;padding-left:70px;box-sizing:border-box}
    .wrap  .smallIcon{margin-top:20px}
    .wrap .smallIcon li{float:left;height:auto;width:auto;margin-right:20px;background:none;transition:all 0.3s}
    .wrap .smallIcon li:hover{transform:scale(1.2)}
    .img-box{width:50%;position:relative;height:100%}
    .fl{float:left}
    .fr{float:right}
    .img-box img{position:absolute;cursor: pointer;}
    .AG_logo{top:140px;left:350px}
    .video_choose{color:#ffd798;position:absolute;top:220px;left:300px;font-size:25px}
    .video_choose div{font-size:14px;margin-top:10px}
    .btn_video{margin-top:170px !important;margin-left:10px}
    .video_choose{display:none}
    .btn_try{position:absolute;top:110px;left:310px;cursor: pointer;}
    .btn_try img{animation: floaty4 ease-in-out 3s 0s infinite forwards;}
    .zr_box .zr_bottom{position:absolute;bottom:30px;left:50%;margin-left:-280px;z-index: 5}
    .zr_bottom a{position:relative;float:left;text-align:center;display:inline-block;width:140px;height:68px;line-height:68px;color:#b9b7b7;background:url(<?php echo TPL_NAME;?>images/live/live_btn.png) no-repeat;background-position:-135px 0}
    .zr_bottom a.active/*,.zr_bottom a:nth-child(-n+2):hover*/{background-position: 3px 0;}
    .zr_bottom a:nth-child(-n+3):before{content: '';display:inline-block;position:absolute;width: 130px;height: 50px;background: url(<?php echo TPL_NAME;?>images/live/live_logo.png) no-repeat;left: 4px;top: 8px;background-position: 25px 0;}
    .zr_bottom a.change_og:before{background-position: -127px 0;}
    .zr_bottom a.change_bbin:before{background-position: -287px 0;}
    .zr_og_box .btn_video,.zr_bbin_box .btn_video{margin-top: 220px !important;}
</style>

<div class="zr_box">
    <div class="swiper-container" >
        <div class="swiper-wrapper">
            <!-- ag -->
            <div class="swiper-slide" >
                <div class="zr_bg zr_ag_box">
                <div class="wrap clearfix">
                    <div class="content-box fl">
                        <div class="btn_try" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>')">
                            <img src="<?php echo TPL_NAME;?>images/live/sw.png" alt="">
                        </div>
                    </div>
                    <div class="img-box fr">
                        <div class="choose_wf">
                            <div class="video_choose" style="display: block">
                              <!--  <span>百家乐</span>
                                <div>最专业的百家乐平台，提供经典百家乐、竞咪百家乐、保险百家乐、龙宝百家乐等多种玩法。</div>-->
                                <div class="btn_video" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/login.php?uid=<?php echo $uid;?>')">
                                    <img src="<?php echo TPL_NAME;?>images/live/video_game.png" alt="">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </div>
            <!-- OG  -->
            <div class="swiper-slide" >
                <div class="zr_bg zr_og_box">
                <div class="wrap clearfix">
                    <div class="img-box fr">
                        <div class="choose_wf">
                            <div class="video_choose" style="display: block">
                                <div class="btn_video" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">
                                    <img src="<?php echo TPL_NAME;?>images/live/video_game.png" alt="">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </div>
            <!-- BBIN  -->
            <div class="swiper-slide" >
                <div class="zr_bg zr_bbin_box" >
                <div class="wrap clearfix">
                    <div class="img-box fr">
                        <div class="choose_wf">
                            <div class="video_choose" style="display: block">
                                <div class="btn_video" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')">
                                    <img src="<?php echo TPL_NAME;?>images/live/video_game.png" alt="">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <!-- 如果需要分页器 -->
        <div class="swiper-pagination" style="display: none;"></div>
    </div>
    <div class="zr_bottom">
        <a class="active change_ag" href="javascript:;" data-to="ag"></a>
        <a class="change_og" href="javascript:;" data-to="og"></a>
        <a class="change_bbin" href="javascript:;" data-to="bbin"></a>
        <a class="change_qd" href="javascript:;" >敬请期待</a>
    </div>

</div>

<script type="text/javascript">
    $(function () {

        indexCommonObj.getUserQpBanlance(uid,'ag') ;
         indexCommonObj.bannerSwiper(6000);


        $('.smallIcon li').on('mouseover',function () {
            var i  = $(this).index();
            $('.choose_wf .video_choose').eq(i).show().siblings().hide();
        })

        $('.zr_bottom a').on('click',function () {
            var type = $(this).attr('data-to');
            var index = $(this).index();
            if(!type){
                return false;
            }
            $('.swiper-pagination').find('.swiper-pagination-bullet').eq(index).click();
            // $('.zr_bg').hide();
             $(this).addClass('active').siblings().removeClass('active');
            // $('.zr_'+type+'_box').fadeIn();
        })
    })
</script>