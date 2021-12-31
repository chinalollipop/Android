<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid']; // 判断是否已登录
$cpUrl = $_SESSION['LotteryUrl'];

?>
<style>
    /* 轮播 */
    .swiper-pagination-bullet{width: 30px;height: 6px;margin: 0 3px;border-radius: 0;background: rgba(0,0,0,.5);}
    .swiper-container-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet{margin: 0 2px;}
    .swiper-pagination-bullet-active{background: #F99E1D}
    .swiper-slide a img{width: 100%;}
    .FourColor{font-size:12px;text-align:center;padding:20px 0;width:100%;overflow:hidden;color:#6e706f;background: #f2f2f2 url(<?php echo TPL_NAME;?>images/index/title_bg.png?v=1) no-repeat;background-size:100% 100%;}
    .FourColor .boxCol{padding:0 85px;border-right:1px solid transparent}
    .Four_border_color{border-color:#f39814!important}
    .FourColor .titleFont{font-size:20px;font-weight:700;padding:10px 0;color: #f39814;}
    .hbBloodSports{width:100%;background: #f2f2f2 url(<?php echo TPL_NAME;?>images/index/part1-bg.jpg?v=1)}
    .hbBloodSports .content{width:90%;margin:0 auto;padding:10px 0 0;display:table;position: relative;}
    .hbBloodSports .content .left,.hbBloodSports .content .right{display:table-cell}
    .hbBloodSports .content .left{width:67.5%;position: absolute;bottom: 0;}
    .hbBloodSports .content .left img{width:100%}
    .hbBloodSports .content .right{vertical-align:middle;width:32%;}
/*    .hbBloodSports .content .right .right_box{
        padding:0 33px 0 0
    }*/

    .hbBloodSports .content .right .right_box .purple .zrsxylcBox{text-align:center}
    .hbBloodSports .content .right .right_box .purple .zrsxylcBox .zrsxylc{width:301px;height:92px;margin:0 auto}
    .hbBloodSports .content .right .right_box .purple .zrsxylcBox .them_font_home_color{padding:10px 0 37px}
    .them_font_home_color{color:#d98141!important}
    .hbBloodSports .content .right .right_box .purple img{width:100%;height:100%;-webkit-animation:img_animat_two .5s;animation:img_animat_two .5s}
    .hbBloodSports .content .right .right_box .footertitle{text-align:center}
    .hbBloodSports .content .right .right_box .footertitle .btn_dat{padding:6px 50px;border-radius:20px}
    .hbBloodSports .content .right .right_box .footertitle .text{padding:0 0 30px;color:#3d3d3d;}
    .hbBloodSports .content .right .right_box .footertitle .them_font_color{padding:30px 60px 0}
    .hbBloodSports .content .right .right_box .footertitle .pushLink{padding-bottom:20px}
    .hbBloodSports .content .right .right_box .footerQrCode{padding-top:30px}
    .hbBloodSports .content .right .right_box .footerQrCode ul{width:100%;display:table}
    .hbBloodSports .content .right .right_box .footerQrCode ul li{display:table-cell}
    .hbBloodSports .content .right .right_box .footerQrCode ul li .img_li{width:120px;height:120px;margin:0 auto;border:1px solid transparent}
    .hbBloodSports .content .right .right_box .footerQrCode ul li .img_li img,.hbBloodSports .content .right .right_box .footerQrCode ul li .img_li span{display:inline-block;width:100%;height:100%;background-size: 100% !important;}
    .hbBloodSports .content .right .right_box .footerQrCode ul li p{padding-top:8px;font-size:16px;text-align:center}
    .hbBloodSports .content .right .right_box .footerQrCode ul li p label{padding-right:5px}

    .FiveType{width:100%;overflow:hidden;background: #f2f2f2 url(<?php echo TPL_NAME;?>images/index/part2-bg.jpg?v=1) no-repeat;background-size:100% 100%;-moz-background-size:100% 100%;-webkit-background-size:100% 100%;-o-background-size:100% 100%}
    .FiveType .no_more{color:#fff;font-size:16px;width:133px;margin:10px auto 0}
    .FiveType .purple{cursor:pointer;transition:.3s;width:100%;max-width: 260px;height:390px;position:relative;text-align:center;margin:10px 0;background-position-y:0 !important; }
    .FiveType .purple:hover{opacity: .8;}
    .FiveType .purple:after{position: absolute;content: '';display: inline-block;width: 100%;height: 40px;bottom: 20px;left: 50%;margin: 0 -50% 0;background: url(<?php echo TPL_NAME;?>images/index/qipai.png) no-repeat 50%;}
    .FiveType .purple1{background:url(<?php echo TPL_NAME;?>images/index/chess.png) no-repeat 50%;background-size:100%}
    .FiveType .purple2{background:url(<?php echo TPL_NAME;?>images/index/game.png) no-repeat 50%;background-size:100%}
    .FiveType .purple3{background:url(<?php echo TPL_NAME;?>images/index/dianjing.png) no-repeat 50%;background-size:100%}
    .FiveType .purple4{background:url(<?php echo TPL_NAME;?>images/index/fish.png) no-repeat 50%;background-size:100%}
    .FiveType .purple5{background:url(<?php echo TPL_NAME;?>images/index/lottery.png) no-repeat 50%;background-size:100%}
    .FiveType .purple2:after{background: url(<?php echo TPL_NAME;?>images/index/dianzi.png) no-repeat 50%;}
    .FiveType .purple3:after{background: url(<?php echo TPL_NAME;?>images/index/dianji.png) no-repeat 50%;}
    .FiveType .purple4:after{background: url(<?php echo TPL_NAME;?>images/index/buyu.png) no-repeat 50%;}
    .FiveType .purple5:after{background: url(<?php echo TPL_NAME;?>images/index/caipiao.png) no-repeat 50%;}

    .Promotion{background:#fff}
    .Promotion .PromotionHeaderImg{text-align:center;padding:20px 0 10px}
    .Promotion .PromotionHeaderImg img{width:252px;height:67px}
    .Promotion .PromotionBox{width:95%;padding:30px 0;margin:0 auto}
    .Promotion .PromotionBox .ulBox{width:100%;display:table}
    .Promotion .PromotionBox .ulBox .liBox{width:13%;display:table-cell;text-align:center}
    .Promotion .PromotionBox .ulBox .liBox .Col{width:214px;height:235px;display:inline-block;overflow:hidden}
    .Promotion .PromotionBox .ulBox .liBox .Col .Title{padding-top:54px}
    .Promotion .PromotionBox .ulBox .liBox .Col .more{padding-top:100px}
    .Promotion .PromotionBox .PromotionColBg{position:relative;background:url('<?php echo TPL_NAME;?>images/index/promo.png') no-repeat}
    .Promotion .PromotionBox .PromotionColBg .PromotionBtn{text-align:center}
    .Promotion .PromotionBox .PromotionColBg .PromotionBtn .PromotionA{font-size:18px;color:#d96217;padding:0 10px 13px}
    .Promotion .PromotionBox .PromotionColBg .PromotionBtn .PromotionC{font-size:12px;color:#6c6918;padding:0 10px 13px}
    .Promotion .PromotionBox .PromotionColBg .PromotionBtn .PromotionP{display:inline-block;position:absolute;left:0;right:0;bottom:20px;margin:0 auto}
    .Promotion .PromotionBox .PromotionColBg .PromotionBtn .PromotionP .btn_1{background:none;border:1px solid #E6A23C}
    .Promotion .PromotionBox .PromotionColBg .PromotionBtn .PromotionP .btn_2{background:none;border:1px solid #c87914}
    .Promotion .PromotionBox .PromotionColBg:hover{background:url(<?php echo TPL_NAME;?>images/index/promo_hover.png) no-repeat}
    .Promotion .PromotionBox .PromotionColBg:hover .PromotionA{font-size:18px;color:#fff}
    .Promotion .PromotionBox .PromotionColBg:hover .PromotionC{font-size:12px;color:#faf103}
    .Promotion .PromotionBox .PromotionColBg:hover .PromotionP .btn_1{color:#fff;background-image:linear-gradient(82deg,#EBB563,#E6A23C);-moz-background-image:linear-gradient(82deg,#EBB563,#E6A23C);-webkit-background-image:linear-gradient(82deg,#EBB563,#E6A23C);-o-background-image:linear-gradient(82deg,#EBB563,#E6A23C)}
    .Promotion .PromotionBox .PromotionColBg:hover .PromotionP .btn_2{color:#fff;background-image:linear-gradient(82deg,#e99d3f,#e7b13b);-moz-background-image:linear-gradient(82deg,#e99d3f,#e7b13b);-webkit-background-image:linear-gradient(82deg,#e99d3f,#e7b13b);-o-background-image:linear-gradient(82deg,#e99d3f,#e7b13b)}
    .Promotion .PromotionBox .PromotionHoverBg{background:url(<?php echo TPL_NAME;?>images/index/promo_hover.png) no-repeat}
    .el-button.is-round {border-radius: 20px;padding: 10px 20px;}
    .banner_base{text-align: center;}
    .banner_base a img{margin: 0 auto;width: 100px;animation: weuiLoading 1s steps(12) infinite;}
    @keyframes weuiLoading {
        0% {transform: rotate(0deg)}
        to {transform: rotate(1turn)}
    }
    @media (min-width:1600px){
        .hbBloodSports .content{min-height: 600px;}
    }
</style>

<!-- 轮播 -->
<div class="banner">
    <div class="jBanners banner">
        <div class="swiper-container" >
            <div class="swiper-wrapper">
                <div class="banner_base swiper-slide" >
                    <a href="javascript:;" >
                        <img src="/images/loading.svg" >
                    </a>
                </div>

            </div>
            <!-- 分页器 -->
            <div class="swiper-pagination"> </div>
        </div>

      <!--  <div class="noticeContent">
            <div class="w_1000">
                <span></span>
                <marquee behavior="" direction="">
                    <?php /*echo $_SESSION['memberNotice']; */?>
                </marquee>
            </div>
        </div>-->
    </div>
</div>

<!-- 列表介绍 -->
<div class="el-col el-col-24">
    <div  class="FourColor title_bg">
        <div  class="el-row" style="margin-left: -10px; margin-right: -10px;">
            <div  class="el-col el-col-6" style="padding-left: 10px; padding-right: 10px;">
                <div  class="boxCol Four_border_color">
                    <p  class="titleFont">实力品牌推荐</p>
                    <p >亚洲最专业的技术团队，业界标杆、信誉领航保障您全天24小时安全省心游戏。</p>
                </div>
            </div>
            <div  class="el-col el-col-6" style="padding-left: 10px; padding-right: 10px;">
                <div  class="boxCol Four_border_color">
                    <p  class="titleFont">游戏类型齐全</p>
                    <p >每天近千场精彩体育赛事，更有真人、彩票、电子游戏等多种娱乐方式选择，让您拥有完美游戏体验。</p>
                </div>
            </div>
            <div  class="el-col el-col-6" style="padding-left: 10px; padding-right: 10px;">
                <div  class="boxCol Four_border_color">
                    <p  class="titleFont">多种支付方式</p>
                    <p >本平台携手亚洲顶级IT技术团队,80家合作支付平台，让您随时随地轻松投注</p>
                </div>
            </div>
            <div  class="el-col el-col-6" style="padding-left: 10px; padding-right: 10px;">
                <div  class="boxCol"><p  class="titleFont">交易安全快捷</p>
                    <p >独家开发，加密技术和严格的安全管理体系，客户资金最完善的保障，让您全情尽享娱乐、赛事投注，无后顾之忧！</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 体育 APP -->
<div  class="el-col el-col-24">
    <div class="hbBloodSports">
        <div  class="content">
          <!--  <div  class="left">
                <img  src="<?php /*echo TPL_NAME;*/?>images/index/app_load.png?v=1" alt="APP下载">
            </div>-->
            <div  class="right">
                <div  class="right_box">
                    <div  class="purple">
                        <div  class="zrsxylcBox">
                            <div  class="zrsxylc">
                                <img  src="<?php echo TPL_NAME;?>images/LOGO.png" alt="">
                            </div>
                            <p  class="them_font_home_color">自主研发体育平台，赛事多元化，投注玩法多样化。</p>
                        </div>
                    </div>
                    <div  class="footertitle">
                        <p  class="text">
                            独家研发的体育平台，最专业的技术团队，最全的游戏玩法。业内赔率最高！覆盖世界各地赛事,让球、大小、半全场、波胆、单双、总入球、连串过关等多元竞猜。更有动画直播、视频直播，让您体验轻松聊球，娱乐投注两不误 。
                        </p>
                        <div  class="pushLink">
                            <span data-rtype="r" data-showtype="today" class="to_sports click_on them_bg_color_gradient btn_dat">进入游戏</span>
                        </div>
                       <!-- <p  class="them_font_color">扫码下载APP</p>-->
                    </div>
                    <div  class="footerQrCode">
                        <ul >
                            <li >
                                <div  class="img_li theme_border_color">
                                    <span class="download_android_app"></span>
                                </div>
                                <p  class="them_font_color">
                                    Android版
                                </p>
                            </li>
                            <li >
                                <div  class="img_li theme_border_color">
                                    <span class="download_ios_app"></span>
                                </div>
                                <p  class="them_font_color">
                                    IOS版
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 游戏列表 -->
<div  class="el-col el-col-24">
    <div class="FiveType">
        <div class="el-row is-justify-space-around el-row--flex" style="margin-left: -10px; margin-right: -10px;">
            <div class="el-col el-col-4" style="padding-left: 10px; padding-right: 10px;">
                <div class="to_chess purple purple1">

                </div>
            </div>
            <div class="el-col el-col-4" style="padding-left: 10px; padding-right: 10px;">
                <div class="to_lotterys purple purple5">

                </div>
            </div>

            <div class="el-col el-col-4" style="padding-left: 10px; padding-right: 10px;">
                <div class="to_dianjing purple purple3">

                </div>
            </div>
            <div class="el-col el-col-4" style="padding-left: 10px; padding-right: 10px;">
                <div class="to_fish purple purple4">

                </div>
            </div>
            <div class="el-col el-col-4" style="padding-left: 10px; padding-right: 10px;">
                <div class="to_games purple purple2">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- 优惠活动 -->
<!--<div  class="el-col el-col-24">
    <div  class="Promotion">
        <div class="PromotionHeaderImg">
            <img src="<?php /*echo TPL_NAME;*/?>images/index/yhhd.png" alt="">
        </div>
        <div class="PromotionBox">
            <ul class="ulBox">
                <li class="liBox">
                    <div class="Col PromotionColBg">
                        <div class="PromotionBtn Title">
                            <p class="PromotionA">皇冠上上签天天享好礼</p>
                            <p class="PromotionC"></p>
                            <p class="PromotionP">
                                <button type="button" class="to_promos el-button btn_1 el-button--primary el-button--medium is-plain is-round">
                                    <span>查看详情</span>
                                </button>
                            </p>
                        </div>
                    </div>
                </li>
                <li class="liBox">
                    <div class="Col PromotionColBg">
                        <div class="PromotionBtn Title">
                            <p class="PromotionA">好友邀请</p>
                            <p class="PromotionC"></p>
                            <p class="PromotionP">
                                <button type="button" class="to_promos el-button btn_1 el-button--primary el-button--medium is-plain is-round">
                                    <span>查看详情</span>
                                </button>
                            </p>
                        </div>
                    </div>
                </li>
                <li class="liBox">
                    <div class="Col PromotionColBg">
                        <div class="PromotionBtn Title">
                            <p class="PromotionA">皇冠嘉年华</p>
                            <p class="PromotionC"></p>
                            <p class="PromotionP">
                                <button type="button" class="to_promos el-button btn_1 el-button--primary el-button--medium is-plain is-round">
                                    <span>查看详情</span>
                                </button>
                            </p>
                        </div>
                    </div>
                </li>
                <li class="liBox">
                    <div class="Col PromotionColBg">
                        <div class="PromotionBtn Title">
                            <p class="PromotionA">五大联赛</p>
                            <p class="PromotionC"></p>
                            <p class="PromotionP">
                                <button type="button" class="to_promos el-button btn_1 el-button--primary el-button--medium is-plain is-round">
                                    <span>查看详情</span>
                                </button>
                            </p>
                        </div>
                    </div>
                </li>
                <li class="liBox">
                    <div class="Col PromotionColBg">
                        <div class="PromotionBtn Title">
                            <p class="PromotionA">体育连赢6场 即得13888现金</p>
                            <p class="PromotionC">活动介绍</p>
                            <p class="PromotionP">
                                <button type="button" class="to_promos el-button btn_1 el-button--primary el-button--medium is-plain is-round">
                                    <span>查看详情</span>
                                </button>
                            </p>
                        </div>
                    </div>
                </li>
                <li class="liBox">
                    <div class="Col PromotionHoverBg">
                        <div class="PromotionBtn more">
                            <button type="button" class="to_promos el-button el-button--warning el-button--medium is-round" title="更多优惠">
                                <span>更多优惠</span>
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</div>-->

<div class="clear"></div>

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