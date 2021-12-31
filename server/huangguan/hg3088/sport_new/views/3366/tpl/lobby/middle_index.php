<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid']; // 判断是否已登录
$cpUrl = $_SESSION['LotteryUrl'];

?>
<style>
    .swiper-slide a img {width: 100%;}
    .index_part_bg{background: url(<?php echo TPL_NAME;?>images/index/index_bg.jpg) center no-repeat;}
    .index_part_new{height: 650px;}
    .index_part_new ul{position: relative;height: 109px;margin-bottom: 30px;}
    .index_part_new ul.ls_bottom{height: 500px;}
    .index_part_new .ls_top li,.index_part_new .ls_bottom li{cursor:pointer;float: left;position: absolute;height: 100%;transition: .3s;}
    .index_part_new .ls_top li:hover,.index_part_new .ls_bottom li:hover{transform: scale(1.06)}
    .index_part_new .ls_top .ls_1{width: 321px;background: url(<?php echo TPL_NAME;?>images/index/ls_1.png) center no-repeat;}
    .index_part_new .ls_top .ls_2{width: 296px;background: url(<?php echo TPL_NAME;?>images/index/ls_2.png) center no-repeat;left: 277px;}
    .index_part_new .ls_top .ls_3{width: 342px;background: url(<?php echo TPL_NAME;?>images/index/ls_3.png) center no-repeat;left: 550px;}
    .index_part_new .ls_top .ls_4{width: 337px;background: url(<?php echo TPL_NAME;?>images/index/ls_4.png) center no-repeat;left: 855px;}
    .index_part_new .ls_bottom .ls_bottom_1{width: 422px;height:170px;background: url(<?php echo TPL_NAME;?>images/index/biaoti.png) center no-repeat;}
    .index_part_new .ls_bottom .ls_bottom_2{width: 424px;height:294px;background: url(<?php echo TPL_NAME;?>images/index/qipai.png) center no-repeat;left: 360px;z-index: 1;}
    .index_part_new .ls_bottom .ls_bottom_3{width: 404px;height:453px;background: url(<?php echo TPL_NAME;?>images/index/tiyu.png) center no-repeat;left: 520px;top: 55px;}
    .index_part_new .ls_bottom .ls_bottom_4{width: 376px;height:188px;background: url(<?php echo TPL_NAME;?>images/index/caipiao.png) center no-repeat;left: 780px;top: 15px;}
    .index_part_new .ls_bottom .ls_bottom_5{width: 389px;height:305px;background: url(<?php echo TPL_NAME;?>images/index/jingji.png) center no-repeat;top: 203px;}
    .index_part_new .ls_bottom .ls_bottom_6{width: 390px;height:205px;background: url(<?php echo TPL_NAME;?>images/index/buyu.png) center no-repeat;left: 230px;top: 303px;}
    .index_part_new .ls_bottom .ls_bottom_7{width: 372px;height:275px;background: url(<?php echo TPL_NAME;?>images/index/dianzi.png) center no-repeat;left: 815px;top: 215px;}

    .index_part_1{width:100%;height:460px;background:url(<?php echo TPL_NAME;?>images/index/part1-bg.png) center center no-repeat;margin-bottom:10px}
    .index_part_1>div{padding-top:20px}
    .index_part_1 .index_part_p{width:490px;margin-top:46px}
    .index_part_1 img{display:inline-block}
    .index_part_1 .right_p{margin-left:-9px}
    .index_part_1 .right_icon{position:absolute;margin:48px -50px 0}
    .index_part_1 .index_part_sport{width:690px}
    .index_part_1 .part1_top{color:#ffb400;font-size:20px;margin-bottom:10px}
    .index_part_1 .part1_top span{color:#898989}
    .index_part_1 .part1_top:after{content:'';display:inline-block;width:678px;height:2px;background:url(<?php echo TPL_NAME;?>images/index/sport-line.png) no-repeat;margin-top:5px}
    .index_part_1 .part1_top_content{text-align:center;width:640px;height:160px;padding:10px;background:linear-gradient(to top,#fbfbfb 0%,#eee 100%);border-radius:5px; margin-bottom: 10px;}
    .index_part_1 .part1_top_content_half{width:308px;height:180px;display:inline-block}
    .index_part_1 .part1_top_content>div{width:50%;color:#333;overflow:hidden}
    .index_part_1 .part1_top_content_half>div{width:100%}
    .index_part_1 .part1_top_content .title{font-size:16px;color:#ff8e01;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;}
    .index_part_1 .part1_top_content .time{margin:5px auto}
    .index_part_1 .part1_top_content .team li{float:left;width:100px;position:relative}
    .index_part_1 .part1_top_content .team li:nth-child(2){line-height:96px;font-size:28px;color:#ffa12b;font-weight:bold}
    .index_part_1 .part1_top_content .team_logo{display:inline-block;width:75px;height:75px;background-size:100%}
    .index_part_1 .part1_top_content .team_logo_1{background-image:url(<?php echo TPL_NAME;?>images/index/sport_icon_4.png)}
    .index_part_1 .part1_top_content .team_logo_2{background-image:url(<?php echo TPL_NAME;?>images/index/sport_icon_3.png)}
    .index_part_1 .part1_top_content .team_logo_3{background-image:url(<?php echo TPL_NAME;?>images/index/sport_icon_2.png)}
    .index_part_1 .part1_top_content .team_logo_4{background-image:url(<?php echo TPL_NAME;?>images/index/sport_icon_1.png)}
    .index_part_1 .sport_rate a{display:block;float:left;background:#fff;width:30%;height:40px;margin-left:3%;color:#777676;border-radius:5px;margin-bottom:10px}
    .index_part_1 .sport_rate a:hover,.index_part_1 .half_rate a:hover{background:#ffa12b;color:#fff}
    .index_part_1 .sport_rate a:nth-child(3n+1){line-height:40px;font-size:16px}
    .index_part_1 a.sport_bet_btn{width:170px;height:30px;line-height:30px;color:#fff;margin:10px 0 0 8%;}
    .index_part_1 .part1_top_content_half a.sport_bet_btn{width:92px;font-size:14px;font-weight:normal;display:inline-block;margin:30px 0}
    .index_part_1 .half_rate{margin-top:5px}
    .index_part_1 .half_rate a{display:inline-block;background:#fff;color:#777676;padding:5px 15px;border-radius:5px;margin-right:10px}
    .index_part_2{overflow: hidden;width:100%;height: 516px;background:url(<?php echo TPL_NAME;?>images/index/part2-bg.png) center center no-repeat;padding-top: 10px;}
    .index_part_2 .index_part_2_ul li{position:relative;transition:.3s;cursor:pointer;width:170px;padding:0 5px;height:85px;background:#fff;margin:15px 0;color:#000;border-radius: 8px;}
    .index_part_2 .index_part_2_ul li:after{content:'';position:absolute;display:inline-block;width: 115px;height: 84px;bottom:0;right:0;background-image:url(<?php echo TPL_NAME;?>images/index/live_logo.png);background-size:100%;}
    .index_part_2 .index_part_2_ul li:nth-child(1):after{height: 98px;width: 100px;}
    .index_part_2 .index_part_2_ul li:nth-child(2):after{background-image:url(<?php echo TPL_NAME;?>images/index/small_game.png);width: 125px;height: 88px;}
    .index_part_2 .index_part_2_ul li:nth-child(3):after{background-image:url(<?php echo TPL_NAME;?>images/index/big-lottery.png)}
    .index_part_2 .index_part_2_ul li:nth-child(4):after{height: 100px;background-image:url(<?php echo TPL_NAME;?>images/index/jj-ren.png)}
    .index_part_2 .index_part_2_ul li:nth-child(5):after{height: 96px;width: 120px;background-image:url(<?php echo TPL_NAME;?>images/index/qp-ren-sm.png);}
    .index_part_2 .index_part_2_ul li.active,.index_part_2 .index_part_2_ul li:hover{background:#fa9602;background:linear-gradient(to right,#fa9602 0%,#fec707 100%)}
    .index_part_2 .index_part_2_ul p{line-height:28px;position:relative;z-index:1}
    .index_part_2 .index_part_2_ul p:first-child{font-size:16px;padding-top:10px}
    .index_part_2 .index_part_2_show{position:relative;display:inline-block;padding-top:80px;width:1000px;height:100%}
    .index_part_2 .index_part_2_show .hide{display:none}
    .index_part_2 .live_left{position:relative;width:420px;color:#515151;text-align:right}
    .index_part_2 .title{font-size:24px;color:#eb993b}
    .index_part_2 .small_title{font-size:20px;color:#9f9e9d;margin:5px 0}
    .index_part_2 .tip{font-size:15px;line-height:30px;color:#515151;margin:10px 0}
    .index_part_2 .icon-logo{display:inline-block;width:480px;height:50px;margin:30px 0}
    .index_part_2 .live .icon-logo{background:url(<?php echo TPL_NAME;?>images/index/live-icon.png) no-repeat;width:350px}
    .index_part_2 .game .icon-logo{background:url(<?php echo TPL_NAME;?>images/index/game-icon.png) no-repeat;width:219px}
    .index_part_2 .lottery .icon-logo{background:url(<?php echo TPL_NAME;?>images/index/tubiao.png) no-repeat;width:275px}
    .index_part_2 .dzjj .icon-logo{background:url(<?php echo TPL_NAME;?>images/index/jj-im.png) no-repeat;width:420px;background-size:100%}
    .index_part_2 .chess .icon-logo{background:url(<?php echo TPL_NAME;?>images/index/qp-tubiaop.png) no-repeat;width:297px}
    .index_part_2 .live_left a{display:block;width:105px;text-align:center;padding:6px 0;position:absolute;right:0}
    .index_part_2 .live_right{position:absolute;width: 580px;height: 512px;right: 10px;bottom: 82px;}
    .index_part_2 .game .live_right{width:435px;background:url(<?php echo TPL_NAME;?>images/index/big_game.png) bottom no-repeat;background-size:100%;bottom: -28px;right: 115px;}
    .index_part_2 .lottery .live_right{width:465px;right:100px;background-size:70%}
    .index_part_2 .dzjj .live_right{width:480px;background:url(<?php echo TPL_NAME;?>images/index/jj-shi.png) bottom no-repeat;background-size:100%;right:120px;bottom:0}
    .index_part_2 .chess .live_right{width:430px;right:-40px;bottom:46px}
    .index_part_2 .live_right span{display:inline-block;position:absolute}
    .index_part_2 .live .live-icon-1{width:89px;height:83px;background:url(<?php echo TPL_NAME;?>images/index/live-icon-left.png) no-repeat;top: 136px;left:75px;}
    .index_part_2 .live .live-icon-2{width:75px;height:66px;background:url(<?php echo TPL_NAME;?>images/index/live-icon-right.png) no-repeat;top: 130px;right:85px;}
    .index_part_2 .live .live-icon-3{width:165px;height:168px;background:url(<?php echo TPL_NAME;?>images/index/zp_live.png) no-repeat;bottom: 30px;left: 20px;}
    .index_part_2 .live .live-icon-4{width:288px;height:511px;background:url(<?php echo TPL_NAME;?>images/index/big_live.png) no-repeat;left: 155px;z-index: 1;}
    .index_part_2 .live .live-icon-5{width:206px;height:206px;background:url(<?php echo TPL_NAME;?>images/index/lp_live.png) no-repeat;right: 20px;bottom: 0;}
    .index_part_2 .game .live-icon-1{width:275px;height:523px;background:url(<?php echo TPL_NAME;?>images/index/game-bg.png) no-repeat;top: -118px;right: 26px;}
    .index_part_2 .game .live-icon-2{width:73px;height:63px;background:url(<?php echo TPL_NAME;?>images/index/game_m.png) no-repeat;top: 30px;left: 135px;}
    .index_part_2 .game .live-icon-3{width:66px;height:57px;background:url(<?php echo TPL_NAME;?>images/index/game_jb.png) no-repeat;top: -45px;left: 220px;}
    .index_part_2 .game .live-icon-4{width:53px;height:44px;background:url(<?php echo TPL_NAME;?>images/index/game_jb_1.png) no-repeat;top: 18px;right: -30px;}
    .index_part_2 .lottery .live-icon-1{width: 490px;height: 380px;background: url(<?php echo TPL_NAME;?>images/index/big-lottery.png) bottom center no-repeat;background-size: 84%;bottom:0;left: 145px;z-index: 1;}
    .index_part_2 .lottery .live-icon-2{width:216px;height:142px;background:url(<?php echo TPL_NAME;?>images/index/lotery-icon.png) no-repeat;top:63px;right:-67px}
    .index_part_2 .lottery .live-icon-3{width:88px;height:74px;background:url(<?php echo TPL_NAME;?>images/index/game3.png) no-repeat;top:170px;left:90px}
    .index_part_2 .lottery .live-icon-4{width:327px;height:219px;background:url(<?php echo TPL_NAME;?>images/index/lottery-card.png) left bottom no-repeat;bottom: 0;left: 0;}
    .index_part_2 .dzjj .live-icon-1{width:216px;height:227px;background:url(<?php echo TPL_NAME;?>images/index/jj-tou.png) no-repeat;top:-10px;left:168px}
    .index_part_2 .dzjj .live-icon-2{width:490px;height:487px;background:url(<?php echo TPL_NAME;?>images/index/jj-ren.png) no-repeat;top: -42px;left:98px;}
    .index_part_2 .chess .live-icon-1{width:63px;height:94px;background:url(<?php echo TPL_NAME;?>images/index/qp-a.png) no-repeat;bottom:65px;left:-180px}
    .index_part_2 .chess .live-icon-2{width:71px;height:75px;background:url(<?php echo TPL_NAME;?>images/index/qp-tx.png) no-repeat;top:0px;left:-130px}
    .index_part_2 .chess .live-icon-3{width:430px;height:500px;background:url(<?php echo TPL_NAME;?>images/index/qp-ren.png) bottom no-repeat;bottom:8px;right:170px;z-index: 1;}
    .index_part_2 .chess .live-icon-4{width:337px;height:315px;background:url(<?php echo TPL_NAME;?>images/index/qp-pk.png) bottom no-repeat;bottom: 0;right: 45px;}
    .index_part_3{overflow:hidden;width:100%;height:541px;background:url(<?php echo TPL_NAME;?>images/index/part3-bg.png) center center no-repeat;color:#515151;/*padding-top:68px*/}
    .index_part_3 .part_3_left_bg{/*overflow:hidden;width:619px;height:397px;background:url(<?php echo TPL_NAME;?>images/index/app-left.png) center center no-repeat;*/margin:60px 0 0; }
    .index_part_3 .title{width: 444px;height: 105px;background:url(<?php echo TPL_NAME;?>images/index/part3-title.png) center center no-repeat;margin-left: 20px;}
    .app_xr_all {line-height: 80px;font-size: 20px;color: #888;padding-left: 12px;}
    .app_xr_all a{color: #ff9a02;}
    .app_xr_all span{display: inline-block;}
    .app_xr_all .question_icon{width: 22px;height: 22px;background:url(<?php echo TPL_NAME;?>images/app_qu.png) no-repeat;}
    .index_part_4 .title{color:#ffa46b;font-size:22px}
    .index_part_3 .tip{margin:15px 0;line-height:22px}
    .index_part_3 .part_3_left_icon{width:330px;height:64px;background:url(<?php echo TPL_NAME;?>images/index/app-icon.png) no-repeat}
    .index_part_3 .part_3_btn{width:430px;height:38px;line-height:38px;margin:0 0 30px;border-radius:20px}
    .index_part_3 .part_3_btn a{display:inline-block;height:100%;color:#000;text-align:center;font-size:16px;border:1px solid #fcb004;width:198px;border-radius: 20px;}
    .index_part_3 .part_3_btn a:first-child{margin: 0 14px;}
    .index_part_3 .part_3_btn a.btn_game{margin: 0;}
    .index_part_3 .part_3_bottom .app_ewm{width:90px;height:90px;background:url(<?php echo TPL_NAME;?>images/appdownload_android.jpg) no-repeat;background-size:100%}
   /* .index_part_3 .part_3_bottom .right{width:395px;line-height:24px;padding-top:30px}*/
    .index_part_3 .app_txt a{position:relative;display:block;width:200px;height:50px;line-height:50px;background:#ff9a02;background:linear-gradient(to bottom,#ffba02 0%,#ff9a02 100%);border-radius:50px;font-size:20px;text-align:center; margin-top: 15px; margin-bottom: 10px;}
    .index_part_3 .app_txt a:before{position:absolute;display:inline-block;content:'';width:32px;height:34px;margin:14px -38px}
    .index_part_3 .part_3_bottom .android a:first-child:before{background:url(<?php echo TPL_NAME;?>images/andriod_icon.png) no-repeat;}
    /*.index_part_3 .part_3_bottom .ios{ margin-left: 10px;}*/
    .index_part_3 .part_3_bottom .ios a:first-child:before{background:url(<?php echo TPL_NAME;?>images/ios_icon.png) no-repeat;}
    .index_part_3 .ewm{width: 200px;}
    .index_part_3 .ewm span{display:block;height:150px;width:150px;background-size:100% !important;margin:0 auto}
    .index_part_3 .ewm.ios {margin-left: 27px;}
    .index_part_3 .ewm img{width:150px; margin: 0 auto 20px;}
    .index_part_3 .ewm p{text-align: center;color: #6f6f6f;}
    .index_part_4{overflow:hidden;width:100%;height:371px;/*background:url(<?php echo TPL_NAME;?>images/index/part4-bg.png) center center no-repeat;*/color:#515151;padding:50px 0}
    .index_part_4 .index_part_4_top{height:230px}
    .index_part_4 .title span{color:#a8a8a8}
    .index_part_4 .index_part_4_ys{padding-top:30px}
    .index_part_4 .index_part_4_ys .ys_icon{width:610px;height:83px;background:url(<?php echo TPL_NAME;?>images/index/part4-tip.png) center no-repeat}
    .index_part_4 .index_part_4_ys a{color:#000;display:inline-block;width:32.8%;text-align:center;margin-top:15px}
    .index_part_4 .index_part_4_ys a p:first-child{font-size:20px;margin-bottom:5px}
    .index_part_4 .index_part_4_ys a p:last-child{font-size:12px}
    .index_part_4_top .ys_right{width:560px;padding-top:20px}
    .index_part_4_top .ys_right p{font-size:20px}
    .index_part_4_top .ys_right li{float:left;width:260px;margin-top:20px}
    .index_part_4_top .ys_right li .lxwm_icon{display:inline-block;width:47px;height:46px;background:url(<?php echo TPL_NAME;?>images/index/part4-icon.png);margin-right:8px}
    .index_part_4_top .ys_right li .lxwm_email_icon{background-position-y:-50px}
    .index_part_4_top .ys_right li .lxwm_qq_icon{background-position-y:-102px}
    .index_part_4_top .ys_right li .lxwm_dl_icon{background-position-y:-154px}
    .index_part_4_top .ys_right li .lxwm_right a{color:#a82c2c;font-size:20px}
    .ys_right .lxwm_right p{font-size:12px}
    .index_part_4_bottom li{float:left;width:29%}
    .index_part_4_bottom .title{margin-bottom:40px}
    .index_part_4_bottom  .fw_top{width:220px;padding-right:65px}
    .index_part_4_bottom  .fw_top span{font-size:24px;color:#000}
    .index_part_4_bottom .fw_time_tip{text-align:right}
    .index_part_4_bottom .fw_time_tip p:first-child{font-size:16px;color:#000}
    .index_part_4_bottom .fw_time_tip p:last-child{font-size:12px}
    .index_part_4_bottom .fw_jdt{display:block;width:220px;height:10px;border:1px solid #a8a8a8;border-radius:20px;margin-top:10px}
    .index_part_4_bottom .fw_jdt span{display:inline-block;height:12px;background:#ff9d2c;border-radius:20px;margin:-1px}
    .ck_animate_time{animation:ease-in-out ckanimate 1s forwards}
    .qk_animate_time{animation:ease-in-out qkanimate 1s forwards}
    .index_part_4_bottom  .fw_yhzf{width:205px}
    .index_part_4_bottom  .fw_yhzf p:first-child{font-size:20px;color:#333;font-weight:bold}
    .index_part_4_bottom .fw_yhzf p:last-child{font-size:18px;margin-top:5px}
    .index_part_4_bottom .visa_icon{display:inline-block;margin-top:6px;position:absolute}
    .index_part_4_bottom .yhzf_num{font-size:38px;color:#333}
    .index_part_4_bottom .fw_tj_num{font-size:20px;color:#a8a8a8;width:71px}
    .index_part_2 .active .live_left,
    .index_part_2 .live.active .live-icon-4,
    .index_part_2 .game.active .live_right,
    .index_part_2 .lottery.active .live-icon-1,
    .index_part_2 .dzjj.active .live-icon-2,
    .index_part_2 .chess.active .live-icon-3{animation: bottomToTop 2s forwards;}
    .index_part_2 .live.active .live-icon-2,
    .index_part_2 .lottery.active .live-icon-2,
    .index_part_2 .game .live-icon-4{animation: topToRight 1.5s forwards;}

    .index_part_2 .live.active .live-icon-1,
    .index_part_2 .chess.active .live-icon-2,
    .index_part_2 .lottery.active .live-icon-3,
    .index_part_2 .game .live-icon-2{animation: topToLeft 1.5s forwards;}

    .index_part_2 .live.active .live-icon-3,
    .index_part_2 .live.active .live-icon-5,
    .index_part_2 .lottery.active .live-icon-4,
    .index_part_2 .dzjj.active .live-icon-1,
    .index_part_2 .chess.active .live-icon-1,
    .index_part_2 .game .live-icon-3{animation: smallToBig 3s forwards;}
    .index_part_2 .chess.active .live-icon-4{animation: rightToLeft 2s forwards;}

    .banner_base{text-align: center;}
    .banner_base a img{margin: 0 auto;width: 100px;animation: weuiLoading 1s steps(12) infinite;}
    @keyframes weuiLoading {
        0% {transform: rotate(0deg)}
        to {transform: rotate(1turn)}
    }

</style>

<!-- 轮播 -->
<div class="banner">
    <div class="jBanners banner">
        <div class="swiper-container" >
            <div class="swiper-wrapper">
                <div class="banner_base swiper-slide" >
                    <a href="javascript:;" >
                        <img src="/images/loading.svg">
                    </a>
                </div>

            </div>
            <!-- 分页器 -->
            <div class="swiper-pagination"> </div>
        </div>

        <div class="noticeContent">
            <div class="w_1000">
                <span></span>
                <marquee behavior="" direction="">
                    <?php echo $_SESSION['memberNotice']; ?>
                </marquee>
            </div>
        </div>
    </div>
</div>
<div class="index_part_bg">


    <div class="index_part_new">
    <div class="w_1200">
        <ul class="to_sports ls_top" data-rtype="r" data-showtype="today">
            <li class="ls_1"></li>
            <li class="ls_2"></li>
            <li class="ls_3"></li>
            <li class="ls_4"></li>
        </ul>
        <ul class="ls_bottom">
            <li class="ls_bottom_1"></li>
            <li class="to_chess ls_bottom_2"></li>
            <li  class="to_sports ls_bottom_3" data-rtype="r" data-showtype="today"></li>
            <li class="to_lotterys ls_bottom_4"></li>
            <li class="to_dianjing ls_bottom_5"></li>
            <li class="to_fish ls_bottom_6"></li>
            <li class="to_games ls_bottom_7"></li>
        </ul>
    </div>
</div>

<!--<div class="index_part_1">-->
<!--    <div class="w_1200">-->
<!--        <div class="index_part_p left">-->
<!--            <img src="<?php echo TPL_NAME;?>images/index/p1-img.png">-->
<!--            <img class="right_p" src="<?php echo TPL_NAME;?>images/index/p2-img.png">-->
<!--            <img class="right_icon" src="<?php echo TPL_NAME;?>images/index/sport_2020.png">-->
<!--        </div>-->
<!--        <div class="index_part_sport right">-->
<!--            <div class="part1_top">-->
<!--                &nbsp;SPORTS <span> 精彩体育赛事 </span>-->
<!--            </div>-->
<!--            <div class="part1_top_sport">-->
<!--                <div class="part1_top_content recommendmatch_first">-->
<!--                   <!-- <div class="left">-->
<!--                        <p class="title">国际欧洲杯</p>-->
<!--                        <p class="time">2019-07-23 23:45</p>-->
<!--                        <div class="team">-->
<!--                            <ul>-->
<!--                                <li>-->
<!--                                    <span class="team_logo team_logo_1"></span>-->
<!--                                    <p class="team_name">托尔斯港</p>-->
<!--                                </li>-->
<!--                                <li>VS</li>-->
<!--                                <li>-->
<!--                                    <span class="team_logo team_logo_2"></span>-->
<!--                                    <p class="team_name">林菲尔德</p>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="right">-->
<!--                        <div class="sport_rate">-->
<!--                            <a > 胜<span class="rate">1.62</span></a>-->
<!--                            <a > <p>单</p><span class="rate">2.03</span></a>-->
<!--                            <a > <p>大3</p><span class="rate">1.84</span></a>-->
<!--                            <a > 负<span class="rate">1.62</span></a>-->
<!--                            <a > <p>双</p><span class="rate">1.89</span></a>-->
<!--                            <a > <p>大3</p><span class="rate">2.06</span></a>-->
<!--                            <a > 平<span class="rate">4.05</span></a>-->
<!--                            <a href="javascript:;" class="to_sports btn_game sport_bet_btn" data-rtype="re" showtype="rb" >立即投注</a>-->
<!--                        </div>-->
<!--                    </div>-->-->
<!--                </div>-->
<!--                <div class="part1_top_content part1_top_content_half recommendmatch_second">-->
<!--                   <!-- <div >-->
<!--                        <p class="title">国际欧洲杯</p>-->
<!--                        <p class="time">2019-07-23 23:45</p>-->
<!--                        <div class="team">-->
<!--                            <ul>-->
<!--                                <li>-->
<!--                                    <span class="team_logo team_logo_1"></span>-->
<!--                                    <p class="team_name">托尔斯港</p>-->
<!--                                </li>-->
<!--                                <li> <a href="javascript:;" class="to_sports btn_game sport_bet_btn" data-rtype="re" showtype="rb"  >立即投注</a></li>-->
<!--                                <li>-->
<!--                                    <span class="team_logo team_logo_2"></span>-->
<!--                                    <p class="team_name">林菲尔德</p>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!---->
<!--                    </div>-->
<!--                    <div class="half_rate">-->
<!--                        <a > 胜<span class="rate">1.62</span></a>-->
<!--                        <a > 负<span class="rate">1.12</span></a>-->
<!--                        <a > 平<span class="rate">3.75</span></a>-->
<!--                    </div>-->-->
<!--                </div>-->
<!--                <div class="part1_top_content part1_top_content_half recommendmatch_third">-->
<!--                  <!--  <div >-->
<!--                        <p class="title">国际欧洲杯</p>-->
<!--                        <p class="time">2019-07-23 23:45</p>-->
<!--                        <div class="team">-->
<!--                            <ul>-->
<!--                                <li>-->
<!--                                    <span class="team_logo team_logo_3"></span>-->
<!--                                    <p class="team_name">托尔斯港</p>-->
<!--                                </li>-->
<!--                                <li> <a href="javascript:;" class="to_sports btn_game sport_bet_btn" data-rtype="re" showtype="rb"  >立即投注</a></li>-->
<!--                                <li>-->
<!--                                    <span class="team_logo team_logo_4"></span>-->
<!--                                    <p class="team_name">林菲尔德</p>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!---->
<!--                    </div>-->
<!--                    <div class="half_rate">-->
<!--                        <a > 胜<span class="rate">1.62</span></a>-->
<!--                        <a > 负<span class="rate">1.12</span></a>-->
<!--                        <a > 平<span class="rate">3.75</span></a>-->
<!--                    </div>-->-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<!--<div class="index_part_2">
    <div class="w_1200">
        <ul class="index_part_2_ul left">
            <li class="active" data-type="live">
                <p>真人视讯</p>
                <p>Casino</p>
            </li>
            <li data-type="game">
                <p>电子游艺</p>
                <p>Games</p>
            </li>
            <li data-type="lottery">
                <p>彩票游戏</p>
                <p>Lottery</p>
            </li>
            <li data-type="dzjj">
                <p>电子竞技</p>
                <p>E-sports</p>
            </li>
            <li data-type="chess">
                <p>棋牌游戏</p>
                <p>Chess</p>
            </li>
        </ul>
        <div class="index_part_2_show right">
            <div class="live">
                <div class="left live_left">
                    <p class="title"> 真人视讯 </p>
                    <p class="small_title"> Live Casino </p>
                    <p class="tip">
                        为了让线上玩家们体验真实赌场般感受，HG3366为您提供
                        竞咪、百家乐、骰宝、保险百家乐、轮盘、牛牛等多种精彩游戏
                        玩家能够透过PC、平板、手机等设备进行游戏体验。
                    </p>
                    <span class="icon-logo"></span>
                    <a href="javascript:;" class="btn_game" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/zrsx/login.php?uid=<?php /*echo $uid;*/?>')">开始游戏</a>
                </div>
                <div class="right live_right">
                    <span class="live-icon-1"></span>
                    <span class="live-icon-2"></span>
                    <span class="live-icon-3"></span>
                    <span class="live-icon-4"></span>
                    <span class="live-icon-5"></span>
                </div>
            </div>
            <div class="hide game">
                <div class="left live_left">
                    <p class="title"> 电子游艺 </p>
                    <p class="small_title"> Electronic Games </p>
                    <p class="tip">
                        平台接入多款电子游艺产品，包含MG、
                        捕鱼天下、AG等电子游戏，提供数百款机率游戏，包含老虎机、
                        桌上游戏、刮刮乐、实时游戏以及红利积分游戏等项目，
                        务求玩法简单、紧张刺激，提供玩家最佳互动体验。
                    </p>
                    <span class="icon-logo"></span>
                    <a href="javascript:;" class="to_games btn_game" title="开始游戏">开始游戏</a>
                </div>
                <div class="right live_right">
                    <span class="live-icon-1"></span>
                    <span class="live-icon-2"></span>
                    <span class="live-icon-3"></span>
                    <span class="live-icon-4"></span>
                </div>
            </div>
            <div class="hide lottery">
                <div class="left live_left">
                    <p class="title"> 彩票游戏 </p>
                    <p class="small_title">Lottery Game</p>
                    <p class="tip">
                        HG3366彩票提供时下最热门、最齐全的彩票游戏
                        娱乐性强，深受广大客户的喜欢。
                        彩种包含：幸运28、时时彩、香港六合彩、北京赛车、
                        快三、11选5、时时乐、福彩3D等。
                    </p>
                    <span class="icon-logo"></span>
                    <a href="javascript:;" class="btn_game" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','<?php /*echo $cpUrl;*/?>')">开始游戏</a>
                </div>
                <div class="right live_right">
                    <span class="live-icon-1"></span>
                    <span class="live-icon-2"></span>
                    <span class="live-icon-3"></span>
                    <span class="live-icon-4"></span>
                </div>
            </div>
            <div class="hide dzjj">
                <div class="left live_left">
                    <p class="title"> 电子竞技 </p>
                    <p class="small_title">E-sports</p>
                    <p class="tip">
                        HG3366拥有最专业的电子竞技比赛投注项目，
                        包含英雄联盟，PUBG，CSGO等各大种类，
                        让您体验最优秀的赛事投注娱乐，为您带来最便捷的电竞直播
                    </p>
                    <span class="icon-logo"></span>
                    <a href="javascript:;" class="btn_game" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/avia/avia_api.php?action=getLaunchGameUrl')">开始游戏</a>
                </div>
                <div class="right live_right">
                    <span class="live-icon-1"></span>
                    <span class="live-icon-2"></span>
                    <span class="live-icon-3"></span>
                </div>
            </div>
            <div class="hide chess">
                <div class="left live_left">
                    <p class="title"> 棋牌游戏 </p>
                    <p class="small_title">Chess Game</p>
                    <p class="tip">
                        HG336给您提供牛牛、斗地主、
                        赢三张、三公、德州、炸金花、等多种玩法，更便捷、更轻松、
                        更好玩的经典棋牌游戏.千万玩家在线，
                        全国各地棋牌高手欢聚一堂各类大师赛事等你展现牌技
                    </p>
                    <span class="icon-logo"></span>
                    <a href="javascript:;" class="to_chess btn_game" title="开始游戏">开始游戏</a>
                </div>
                <div class="right live_right">
                    <span class="live-icon-1"></span>
                    <span class="live-icon-2"></span>
                    <span class="live-icon-3"></span>
                    <span class="live-icon-4"></span>
                </div>
            </div>

        </div>
    </div>
</div>-->

    <div class="index_part_3">
    <div class="w_1200">
        <div class="left part_3_left_bg">
            <p class="title"><!-- HG3366原生APP全新上线--> </p>
            <div class="app_xr_all">
                <span class="question_icon"></span>
                <span>
                        APP安装和授权 点此查看<a href="<?php echo TPL_NAME;?>tpl/lobby/middle_appTrust.php" target="_blank">IOS版教程</a>
                    </span>
            </div>
          <!--  <p class="tip">
                全面支持手机在线存款、提款、转账等功能。 <br>
                唯让您体验轻松聊球，娱乐投注两不误。
            </p>-->
            <!--<div class="part_3_left_icon"></div>-->
            <div class="part_3_btn">
                <a href="javascript:;" class="btn_game" data-to="ios">IOS下载</a>
                <a class="right" href="javascript:;" data-to="android">安卓下载</a>
            </div>
            <div class="part_3_bottom">
                <div class="app_txt ewm left android">
                    <span class="download_ios_app"></span>
                    <p > 建议使用自带摄像头 </p>
                </div>
                <div class="app_txt ewm left ios">
                    <span class="download_android_app"></span>
                    <p >建议使用自带浏览器扫码工具 </p>
                </div>
            </div>
        </div>
      <!--  <div class="right">

        </div>-->
    </div>
</div>

    <div class="index_part_4" id="index_part_4">
    <div class="w_1200">
        <div class="index_part_4_top">
            <div class="left">
                <p class="title">品牌优势/ <span>Brand advantages </span></p>
                <div class="index_part_4_ys">
                    <div class="ys_icon"></div>
                    <a href="javascript:;">
                        <p>安全性</p>
                        <p>了解详情+</p>
                    </a>
                    <a href="javascript:;">
                        <p>专业性</p>
                        <p>了解详情+</p>
                    </a>
                    <a href="javascript:;">
                        <p>便捷性</p>
                        <p>了解详情+</p>
                    </a>
                </div>
            </div>
            <div class="right ys_right">
                <p>联系我们</p>
                <p class="grey">contact us</p>
                <ul>
                    <li>
                        <span class="left lxwm_icon lxwm_kf_icon"></span>
                        <div class="lxwm_right">
                            <a class="to_livechat">在线客服</a>
                            <p >点击与客服人员联系</p>
                        </div>
                    </li>
                    <li>
                        <span class="left lxwm_icon lxwm_email_icon"></span>
                        <div class="lxwm_right">
                            <a href="javascript:;">客服信箱</a>
                            <p >发送邮件至<span class="sz_service_email"> </span></p>
                        </div>
                    </li>
                    <li>
                        <span class="left lxwm_icon lxwm_qq_icon"></span>
                        <div class="lxwm_right">
                            <a href="javascript:;">客服QQ</a>
                            <p ><span class="qq_service_number"> </span></p>
                        </div>
                    </li>
                    <li>
                        <span class="left lxwm_icon lxwm_dl_icon"></span>
                        <div class="lxwm_right">
                            <a href="javascript:;">代理QQ</a>
                            <p ><span class="agent_service_number"> </span></p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="index_part_4_bottom">
            <p class="title">服务优势/ <span>Service advantage </span></p>
            <ul>
                <li>
                    <span class="left">
                        <img src="<?php echo TPL_NAME;?>images/index/ck_time.png">
                    </span>
                    <div class="right">
                        <div class="fw_top">
                             <span class="fw_ck_time" id="fw_ck_time">23</span>秒
                             <div class="right fw_time_tip">
                                 <p>存款时间</p>
                                 <p>平均时间</p>
                             </div>
                        </div>
                        <span class="fw_jdt fw_jdt_ck">
                            <span ></span>
                        </span>
                    </div>
                </li>
                <li>
                    <span class="left">
                        <img src="<?php echo TPL_NAME;?>images/index/qk_time.png">
                    </span>
                    <div class="right">
                        <div class="fw_top">
                            <span class="fw_qk_time" id="fw_qk_time">108</span>秒
                            <div class="right fw_time_tip">
                                <p>取款时间</p>
                                <p>平均时间</p>
                            </div>
                        </div>
                        <span class="fw_jdt fw_jdt_qk">
                            <span ></span>
                        </span>
                    </div>
                </li>
                <li>
                    <span class="left">
                         <img src="<?php echo TPL_NAME;?>images/index/bjzf.png">
                    </span>
                    <div class="right">
                        <div class="fw_yhzf left">
                            <p>便捷的银行支付</p>
                            <p>我们支付机构有：
                                <span class="visa_icon">
                                     <img src="<?php echo TPL_NAME;?>images/index/visa.png">
                                </span>
                            </p>
                        </div>
                        <div class="fw_tj_num right">
                            <span class="yhzf_num" id="yhzf_num">36</span> 家
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</div>
</div>

<script type="text/javascript">
    $(function () {

        indexCommonObj.indexBannerAction();

        changeUlNav();
        addIndexScrollTop();
       // appChangeTab();
       // getRecommendMatch();

        // 切换
        function changeUlNav() {
            $('.index_part_2_ul li').on('click',function () {
               var type = $(this).attr('data-type');
               $(this).addClass('active').siblings().removeClass('active');
               $('.index_part_2_show').find('.'+type).fadeIn().addClass('active').siblings().hide();
            });
        }

        // 监听滚动
        function addIndexScrollTop() {
            $(window).on('scroll',function(){
                var scrollTop = $(window).scrollTop();
                var ac_time = 1000;
                if( scrollTop>400 ){ // 真人
                    $('.live').addClass('active');
                }
                if(scrollTop>1200 && !$('.fw_jdt_ck span').hasClass('ck_animate_time')){ // 服务优势
                    $('.fw_jdt_ck span').addClass('ck_animate_time');
                    $('.fw_jdt_qk span').addClass('qk_animate_time');
                    animateValue("fw_ck_time", 0, 23, ac_time);
                    animateValue("fw_qk_time", 0, 108, ac_time);
                    animateValue("yhzf_num", 0, 36, ac_time);
                }

            });
        }

        function animateValue(id, start, end, duration) {
            var range = end - start;
            var current = start;
            var increment = end > start ? 1 : -1;
            var stepTime = Math.abs(Math.floor(duration / range));
            var obj = document.getElementById(id);
            var timer = setInterval(function () {
                current += increment;
                if(obj){
                    obj.innerHTML = current;
                }
                if (current == end) {
                    clearInterval(timer);
                }
            }, stepTime);
        }

        // app 下载切换按钮
        function appChangeTab() {
            $('.part_3_btn a').on('click',function () {
                var apptype = $(this).attr('data-to');
                $(this).addClass('btn_game').siblings().removeClass('btn_game');
                $('.app_txt').hide();
                $('.'+apptype).fadeIn();
            })
        }
        
        // 获取推荐赛事
        function getRecommendMatch () {
            var ajaxurl = '/app/member/api/recommentedMatchs.php';
            $.ajax({
                url: ajaxurl ,
                type: 'POST',
                dataType: 'json',
                data: '' ,
                success: function (res) {
                    var $recommendmatch_first = $('.recommendmatch_first');
                    var $recommendmatch_second = $('.recommendmatch_second');
                    var $recommendmatch_third = $('.recommendmatch_third');
                    var firtstr = '';
                    var secondstr = '';
                    var thirdstr = '';
                    if(res.status==200) { // 有数据返回 MB_Win_Rate_RB 主队独赢，TG_Win_Rate_RB 客队独赢，M_Flat_Rate_RB 和，MB_Dime_RB 主队大球数( MB_Dime_Rate_RB 主队大的赔率)，TG_Dime_RB 客队大球数( TG_Dime_Rate_RB 客队大的赔率),S_Single_Rate_RB 单，S_Double_Rate_RB 双
                        if(res.data[0]){
                            firtstr += ' <div class="left">' +
                                '                        <p class="title">'+ res.data[0].M_League +'</p>' +
                                '                        <p class="time">'+ res.data[0].M_Start +'</p>' +
                                '                        <div class="team">' +
                                '                            <ul>' +
                                '                                <li>' +
                                '                                    <span class="team_logo team_logo_1" style="background-image: url('+res.data[0].mb_team_logo_url+');"></span>' +
                                '                                    <p class="team_name">'+ res.data[0].MB_Team +'</p>' +
                                '                                </li>' +
                                '                                <li>VS</li>' +
                                '                                <li>' +
                                '                                    <span class="team_logo team_logo_2" style="background-image: url('+res.data[0].tg_team_logo_url+');"></span>' +
                                '                                    <p class="team_name">'+ res.data[0].TG_Team +'</p>' +
                                '                                </li>' +
                                '                            </ul>' +
                                '                        </div>' +
                                '                    </div>' +
                                '                    <div class="right">' +
                                '                        <div class="sport_rate">' +
                                '                            <a > 胜<span class="rate">'+ res.data[0].MB_Win_Rate_RB +'</span></a>' +
                                '                            <a > <p>单</p><span class="rate">'+ res.data[0].S_Single_Rate_RB +'</span></a>' +
                                '                            <a > <p>'+ res.data[0].MB_Dime_RB +'</p><span class="rate">'+ res.data[0].MB_Dime_Rate_RB +'</span></a>' +
                                '                            <a > 负<span class="rate">'+ res.data[0].TG_Win_Rate_RB +'</span></a>' +
                                '                            <a > <p>双</p><span class="rate">'+ res.data[0].S_Double_Rate_RB +'</span></a>' +
                                '                            <a > <p>'+ res.data[0].TG_Dime_RB +'</p><span class="rate">'+ res.data[0].TG_Dime_Rate_RB +'</span></a>' +
                                '                            <a > 平<span class="rate">'+ res.data[0].M_Flat_Rate_RB +'</span></a>' +
                                '                            <a href="javascript:;" class="to_sports btn_game sport_bet_btn" data-rtype="re" showtype="rb" >立即投注</a>' +
                                '                        </div>' +
                                '                    </div>';
                        }

                        if(res.data[1]){
                            secondstr = returnMatchStr(res.data[1]);
                        }
                        if(res.data[2]){
                            thirdstr = returnMatchStr(res.data[2]);
                        }

                        $recommendmatch_first.html(firtstr);
                        $recommendmatch_second.html(secondstr);
                        $recommendmatch_third.html(thirdstr);

                        }

                },
                error: function (msg) {
                    layer.msg('网络异常',{time:alertTime});
                }
            });
        }
        
        // 返回公用赛事节点
        function returnMatchStr(data) {
            var str = '';
            str +=' <div >' +
                '                        <p class="title">'+ data.M_League +'</p>' +
                '                        <p class="time">'+ data.M_Start +'</p>' +
                '                        <div class="team">' +
                '                            <ul>' +
                '                                <li>' +
                '                                    <span class="team_logo team_logo_1" style="background-image: url('+data.mb_team_logo_url+');"></span>' +
                '                                    <p class="team_name">'+ data.MB_Team +'</p>' +
                '                                </li>' +
                '                                <li> <a href="javascript:;" class="to_sports btn_game sport_bet_btn" data-rtype="re" showtype="rb"  >立即投注</a></li>' +
                '                                <li>' +
                '                                    <span class="team_logo team_logo_2" style="background-image: url('+data.tg_team_logo_url+');"></span>' +
                '                                    <p class="team_name">'+ data.TG_Team +'</p>' +
                '                                </li>' +
                '                            </ul>' +
                '                        </div>' +
                '                    </div>' +
                '                    <div class="half_rate">' +
                '                        <a > 胜<span class="rate">'+ data.MB_Win_Rate_RB +'</span></a>' +
                '                        <a > 负<span class="rate">'+ data.TG_Win_Rate_RB +'</span></a>' +
                '                        <a > 平<span class="rate">'+ data.M_Flat_Rate_RB +'</span></a>' +
                '                    </div>';
            
            return str;
        }


    })



</script>