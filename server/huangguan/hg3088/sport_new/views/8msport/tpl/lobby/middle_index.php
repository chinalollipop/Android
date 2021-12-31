<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid']; // 判断是否已登录
$cpUrl = $_SESSION['LotteryUrl'];

?>
<style>
    /* 首页轮播 */
    .banner .swiper-wrapper{height: 300px;}
    .gameListAll .swiper-wrapper{max-height: 500px;}
    .swiper-pagination-bullet{width: 45px;height: 5px;border: 0;background: #bed1e9;border-radius: 5px;}
    .swiper-pagination-bullet-active{background: #31486b;}
    .swiper-container-horizontal>.swiper-pagination-bullets {bottom: 35px;}
    .swiper-slide a img {width: 100%;}
    .index_part_new{height: 650px;}
    .index_part_new .gameListAll{position: relative;}
    .index_part_new .title{width:100%;height: 100px;background: url(<?php echo TPL_NAME;?>images/index/game_title.png) center no-repeat;}
    .index_part_new .ls_top>div{background:#fff;float: left;width: 260px;height: 460px; box-shadow: 0 0 20px rgba(35,35,35,.2);border-radius: 20px;text-align: center;}
    .index_part_new .ls_top>div>div{width: 100%;}
    .index_part_new .ls_top .ls_1{background: url(<?php echo TPL_NAME;?>images/index/live.png) top center no-repeat;background-size: 90%;}
    .index_part_new .ls_top .ls_2{background: url(<?php echo TPL_NAME;?>images/index/tiyu.png) top center no-repeat;background-size: 90%;}
    .index_part_new .ls_top .ls_3{background: url(<?php echo TPL_NAME;?>images/index/caipiao.png) top center no-repeat;background-size: 90%;}
    .index_part_new .ls_top .ls_4{background: url(<?php echo TPL_NAME;?>images/index/qipai.png) top center no-repeat;background-size: 90%;}
    .index_part_new .ls_top .ls_5{background: url(<?php echo TPL_NAME;?>images/index/dianzi.png) top center no-repeat;background-size: 90%;}
    .index_part_new .ls_top .ls_6{background: url(<?php echo TPL_NAME;?>images/index/buyu.png) top center no-repeat;background-size: 90%;}
    .index_part_new .ls_top .ls_7{background: url(<?php echo TPL_NAME;?>images/index/dianji.png) top center no-repeat;background-size: 90%;}
    .index_part_new .ls_top p{color:rgb(102, 102, 102);margin: 15px 0 20px;}
    .index_part_new .ls_top .big{font-size:22px;color:#2b7ceb;font-weight:bold;margin-top:280px}
    .index_part_new .ls_top .ls_1 .big{color:#6e5a80}
    .index_part_new .ls_top .ls_3 .big{color:#932629}
    .index_part_new .ls_top .ls_4 .big{color:#69a6e1}
    .index_part_new .ls_top .ls_5 .big{color:#b1d4c0}
    .index_part_new .ls_top .ls_6 .big{color:#b29b3e}
    .index_part_new .ls_top .ls_7 .big{color:#6692a4}
    .index_part_new .ls_top a{padding:10px 33px;font-size:16px}

    .index_part_1{width:100%;height:554px;background:url(<?php echo TPL_NAME;?>images/index/part1-bg.png) center center no-repeat}
    .index_part_1>div{padding-top:20px}
    .index_part_1 .index_part_sport{width:745px}
    .index_part_1 .part1_top{font-size:18px;margin-bottom:20px;text-align:right;padding-right:30px}
    .index_part_1 .part1_top a{background:#fff;border:1px solid #64a4ea;border-radius:20px;display:inline-block;width:110px;text-align:center;padding:8px 0;color:#64a4ea}
    .index_part_1 .part1_top_content{text-align:center;width:720px;height:175px;padding:10px;background:#fff;border-radius:10px;margin-bottom:20px}
    .index_part_1 .part1_top_content_half{width:342px;height:210px;display:inline-block}
    .index_part_1 .part1_top_content_half:last-child{margin-left:13px}
    .index_part_1 .part1_top_content>div{width:50%;color:#333;overflow:hidden}
    .index_part_1 .part1_top_content_half>div{width:100%}
    .index_part_1 .part1_top_content .title{font-size:22px;color:#0f0f0f;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;}
    .index_part_1 .part1_top_content .time{margin:5px auto}
    .index_part_1 .part1_top_content .team li{float:left;width:112px;position:relative}
    .index_part_1 .part1_top_content .team li:nth-child(2){line-height:96px;font-size:28px;color:#0f0f0f;font-weight:bold}
    .index_part_1 .part1_top_content .team li p{overflow: hidden;white-space: nowrap;text-overflow: ellipsis;}
    .index_part_1 .part1_top_content .team_logo{display:inline-block;width:80px;height:90px;background-size:100%;background-repeat: no-repeat;}
    .index_part_1 .part1_top_content .team_logo_1,.index_part_1 .part1_top_content .team_logo_3{background-image:url(<?php echo TPL_NAME;?>images/index/tj_logo_left.png)}
    .index_part_1 .part1_top_content .team_logo_2,.index_part_1 .part1_top_content .team_logo_4{background-image:url(<?php echo TPL_NAME;?>images/index/tj_logo_right.png)}
    .index_part_1 .sport_rate a{display:block;float:left;background:#f5f5f5;width:30%;height:42px;margin-left:3%;color:#777676;border-radius:5px;margin-bottom:20px}
    .index_part_1 .sport_rate a:hover,.index_part_1 .half_rate a:hover{background: #708ae8;background: linear-gradient(to right,#708ae8 0%,#5ea0ea 100%);color:#fff}
    .index_part_1 .sport_rate a:nth-child(3n+1){line-height:40px;font-size:16px}
    .index_part_1 .sport_rate a p {margin-top: 2px;}
    .index_part_1 a.sport_bet_btn{width: 170px;height: 35px;line-height: 35px;margin: 3px 0 0 8%;font-size: 18px;}
    .index_part_1 .part1_top_content_half a.sport_bet_btn{width:92px;font-size:14px;font-weight:normal;display:inline-block;margin:30px 0}
    .index_part_1 .half_rate{margin-top:10px}
    .index_part_1 .half_rate a{display:inline-block;background:#f5f5f5;color:#777676;padding:6px 15px;border-radius:5px;margin-right:10px}
    .index_part_2{overflow: hidden;width:100%;height: 498px;background:url(<?php echo TPL_NAME;?>images/index/part2-bg.png) center center no-repeat;}

    .game-swiper-container {width: 93%;padding-top: 50px;margin: 0 auto;overflow-x: hidden;}
    .game-swiper-container .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
        transition: 300ms;
        transform: scale(.85);
    }
    .game-swiper-container .swiper-slide-prev ,.game-swiper-container .swiper-slide-next{transform: scale(.95);}
    .game-swiper-container .swiper-slide-active,.game-swiper-container .swiper-slide-duplicate-active{transform: scale(1.1);z-index: 1;}
    .swiper-button{height:36px ;width:36px ;}
    .swiper-button-prev{background:url(<?php echo TPL_NAME;?>images/z.png)no-repeat;left: 0}
    .swiper-button-next{background:url(<?php echo TPL_NAME;?>images/y.png)no-repeat;right: 0}
    .index_part_3{overflow:hidden;width:100%;height:527px;background:url(<?php echo TPL_NAME;?>images/index/part3-bg.png?v=1) center center no-repeat;color:#515151;/*padding-top:68px*/}
    .index_part_3 .part_3_bottom {margin: 214px 0 0 579px;}
    .index_part_3 .part_3_bottom .android a:first-child:before{background:url(<?php echo TPL_NAME;?>images/andriod_icon.png) no-repeat;}
    .index_part_3 .part_3_bottom .ios a:first-child:before{background:url(<?php echo TPL_NAME;?>images/ios_icon.png) no-repeat;}
    .index_part_3 .ewm{width: 200px;}
    .index_part_3 .ewm.ios {margin-left: 88px;}
    .index_part_3 .ewm img,.index_part_3 .ewm span{display:block;width:165px;height: 165px;margin: 0 auto 20px;background-size: 100% !important;}

    .index_part_4{overflow:hidden;width:100%;padding:50px 0}
    .index_part_4 .index_part_4_ys{padding-top:30px;height:86px;display: -webkit-flex;display: flex;}
    .index_part_4 .index_part_4_ys>div{flex: 1;background: url(<?php echo TPL_NAME;?>images/index/ys_icon_1.png) center left no-repeat;}
    .index_part_4 .index_part_4_ys>div:nth-child(2){background: url(<?php echo TPL_NAME;?>images/index/ys_icon_2.png) center left no-repeat;}
    .index_part_4 .index_part_4_ys>div:nth-child(3){background: url(<?php echo TPL_NAME;?>images/index/ys_icon_3.png) center left no-repeat;}
    .index_part_4 .index_part_4_ys>div:nth-child(4){background: url(<?php echo TPL_NAME;?>images/index/ys_icon_4.png) center left no-repeat;}
    .index_part_4 .index_part_4_ys>div p{padding-left:95px;color:#353f4b}
    .index_part_4 .index_part_4_ys>div p:first-child{margin-top:16px;font-size:20px}
    .index_part_4_bottom ul{width:850px;overflow:hidden}
    .index_part_4_bottom li{position:relative;float:left;width:140px;background:url(<?php echo TPL_NAME;?>images/index/gundong.png) top center no-repeat}
    .index_part_4_bottom li:nth-child(n+2){margin-left:212px}
    .index_part_4_bottom li:nth-child(n+2):before{position:absolute;content:'';display:inline-block;width:215px;height:13px;background:url(<?php echo TPL_NAME;?>images/index/line.png) center no-repeat;top:64px;left:-213px}
    .index_part_4_bottom li .title{font-size:20px;color:#708ae8;text-align:center}
    .index_part_4_bottom .fw_top{position:relative;width:100%;height:99px;margin-bottom:10px;text-align:center;overflow:hidden}
    .index_part_4_bottom .fw_top p{font-size:30px;padding-top:5px}
    .index_part_4_bottom .fw_center{color:#fff;width:84px;height:84px;position:absolute;top:27px;left:27px;background:#5fa0ea;background:linear-gradient(to right,#7189e8 0%,#5ea0ea 100%);border-radius:100%;animation:colour_ease2 3s infinite ease-in-out}
    .index_part_4_bottom .fw_jdt{display:inline-block;width:200px;height:10px;background: #fff;border-radius:20px;margin-top:10px}
    .index_part_4_bottom .fw_jdt span{display:inline-block;height:12px;background: linear-gradient(to right,#7189e8 0%,#5ea0ea 100%);border-radius:20px;margin:-1px}
    .ck_animate_time{animation:ease-in-out ckanimate 1s forwards}
    .qk_animate_time{animation:ease-in-out qkanimate 1s forwards}
    .index_part_4_bottom .fw_yhzf{width:205px}
    .index_part_4_bottom .fw_yhzf p:first-child{font-size:20px;color:#333;font-weight:bold}
    .index_part_4_bottom .fw_yhzf p:last-child{font-size:18px;margin-top:5px}
    .index_part_4_bottom .visa_icon{display:inline-block;margin-top:6px;position:absolute}
    .index_part_4_bottom .fw_tj_num{font-size:20px;color:#a8a8a8;width:71px}
    .index_part_4_bottom .data_right{width: 320px;}
    .index_part_4_bottom .title_2{height: 40px;width: 240px;background: url(<?php echo TPL_NAME;?>images/index/ess_title.png) top center no-repeat;}
    .index_part_4_bottom .tip{font-size:15px;line-height:33px;color:#000}

    .banner_base{text-align: center;}
    .banner_base a img{margin: 0 auto;width: 100px;animation: weuiLoading 1s steps(12) infinite;}
    @keyframes weuiLoading {
        0% {transform: rotate(0deg)}
        to {transform: rotate(1turn)}
    }
</style>

<!-- 轮播 -->
<div class="banner">
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
</div>
<div class="noticeContent">
    <div class="w_1200">
       <span class="fl">  最新消息：</span>
        <marquee behavior="" direction="" loop="loop" onmouseout="this.start()" onmouseover="this.stop()" >
            <?php echo $_SESSION['memberNotice']; ?>
        </marquee>
    </div>
</div>
<div class="index_part_bg">

    <div class="index_part_1">
    <div class="w_1200">
        <div class="index_part_sport right">
            <div class="part1_top">
                <a href="javascript:;" class="btn_game" data-type="FT"> 足球 </a>
                <a href="javascript:;" data-type="BK"> 篮球 </a>
            </div>
            <div class="part1_top_sport">
                <div class="part1_top_content recommendmatch_first">
                    <div class="left">
                        <p class="title">国际欧洲杯</p>
                        <p class="time">2019-07-23 23:45</p>
                        <div class="team">
                            <ul>
                                <li>
                                    <span class="team_logo team_logo_1"></span>
                                    <p class="team_name">托尔斯港</p>
                                </li>
                                <li>VS</li>
                                <li>
                                    <span class="team_logo team_logo_2"></span>
                                    <p class="team_name">林菲尔德</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="right">
                        <div class="sport_rate">
                            <a > 胜<span class="rate">1.62</span></a>
                            <a > <p>单</p><span class="rate">2.03</span></a>
                            <a > <p>大3</p><span class="rate">1.84</span></a>
                            <a > 负<span class="rate">1.62</span></a>
                            <a > <p>双</p><span class="rate">1.89</span></a>
                            <a > <p>小3</p><span class="rate">2.06</span></a>
                            <a > 平<span class="rate">4.05</span></a>
                            <a href="javascript:;" class="to_sports btn_game sport_bet_btn" data-rtype="re" showtype="rb" >立即投注</a>
                        </div>
                    </div>
                </div>
                <div class="part1_top_content recommendmatch_first">
                    <div class="left">
                        <p class="title">国际欧洲杯</p>
                        <p class="time">2019-07-23 23:45</p>
                        <div class="team">
                            <ul>
                                <li>
                                    <span class="team_logo team_logo_1"></span>
                                    <p class="team_name">托尔斯港</p>
                                </li>
                                <li>VS</li>
                                <li>
                                    <span class="team_logo team_logo_2"></span>
                                    <p class="team_name">林菲尔德</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="right">
                        <div class="sport_rate">
                            <a > 胜<span class="rate">1.62</span></a>
                            <a > <p>单</p><span class="rate">2.03</span></a>
                            <a > <p>大3</p><span class="rate">1.84</span></a>
                            <a > 负<span class="rate">1.62</span></a>
                            <a > <p>双</p><span class="rate">1.89</span></a>
                            <a > <p>小3</p><span class="rate">2.06</span></a>
                            <a > 平<span class="rate">4.05</span></a>
                            <a href="javascript:;" class="to_sports btn_game sport_bet_btn" data-rtype="re" showtype="rb" >立即投注</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="index_part_2">

    </div>
    <div class="index_part_new">
        <div class="w_1200">
            <div class="title"> </div>
            <div class="gameListAll">
                <div class="swiper-button swiper-button-next"></div>
                <div class="swiper-button swiper-button-prev"></div>
                <div class="game-swiper-container">
                        <div class="swiper-wrapper ls_top">
                            <div class="swiper-slide">
                                <div class="ls_1">
                                    <p class="big"> 真人娱乐 </p>
                                    <p > 玩法简单，赔率多元。 </p>
                                    <a href="javascript:;" class="to_live btn_game " >立即投注</a>
                                </div>
                            </div>
                             <div class="swiper-slide">
                                <div class="ls_2">
                                    <p class="big"> 体育竞技 </p>
                                    <p > 百个体育赛事，等你来投！ </p>
                                    <a href="javascript:;" class="to_sports btn_game" data-rtype="r" showtype="today"  >立即投注</a>
                                </div>
                              </div>
                             <div class="swiper-slide">
                                <div class="ls_3">
                                    <p class="big"> 彩票娱乐 </p>
                                    <p > 玩法简单，赔率多元。 </p>
                                    <a href="javascript:;" class="to_lotterys btn_game " >立即投注</a>
                                </div>
                              </div>
                             <div class="swiper-slide">
                                <div class="ls_4">
                                    <p class="big"> 棋牌游戏 </p>
                                    <p > 棋乐无穷，自然乐享其中。 </p>
                                    <a href="javascript:;" class="to_chess btn_game " >立即投注</a>
                                </div>
                             </div>
                             <div class="swiper-slide">
                                <div class="ls_5">
                                    <p class="big"> 电子游戏 </p>
                                    <p > 千种项目，极致体验。 </p>
                                    <a href="javascript:;" class="to_games btn_game " >立即投注</a>
                                </div>
                             </div>
                             <div class="swiper-slide">
                                <div class="ls_6">
                                    <p class="big"> 捕鱼游戏 </p>
                                    <p > 天天捕，天天赢，乐不可支。 </p>
                                    <a href="javascript:;" class="to_fish btn_game " >立即投注</a>
                                </div>
                              </div>
                             <div class="swiper-slide">
                                    <div class="ls_7">
                                        <p class="big"> 电竞投注 </p>
                                        <p > 电竞之巅，竞在我手！ </p>
                                        <a href="javascript:;" class="to_dianjing btn_game " >立即投注</a>
                                    </div>
                               </div>
                        </div>

                </div>
            </div>
        </div>
    </div>

    <div class="index_part_3">
    <div class="w_1200">
        <div class="part_3_bottom">
            <div class="app_txt ewm left android">
                <span class="download_android_app"></span>
            </div>
            <div class="app_txt ewm left ios">
                <span class="download_ios_app"></span>
            </div>
        </div>
    </div>
</div>

    <div class="index_part_4" id="index_part_4">
    <div class="w_1200">

        <div class="index_part_4_bottom">
            <ul class="left">
                <li>
                    <div class="fw_top">
                        <div class="fw_center">
                            <p class="fw_ck_time" id="fw_ck_time">23</p>秒
                        </div>
                    </div>
                    <p class="title">平均存款速度</p>
                </li>
                <li>
                    <div class="fw_top">
                        <div class="fw_center">
                            <p class="fw_qk_time" id="fw_qk_time">108</p>秒
                        </div>
                    </div>
                    <p class="title">平均提现速度</p>
                </li>
                <li>
                    <div class="fw_top">
                        <div class="fw_center">
                            <p class="fw_hz_time" id="yhzf_num">88</p>家
                        </div>
                    </div>
                    <p class="title">合作游戏平台</p>
                </li>
            </ul>
            <div class="data_right right">
                <div class="title_2"></div>
                <div class="tip">
                    <span class="text">活跃用户数</span>
                    <span class="fw_jdt fw_jdt_ck">
                        <span ></span>
                    </span>
                </div>
                <div class="tip">
                    <span class="text">累计注单量</span>
                    <span class="fw_jdt fw_jdt_qk">
                        <span ></span>
                    </span>
                </div>
                <div class="tip">
                    <span class="text">累计存提款</span>
                    <span class="fw_jdt fw_jdt_ctk">
                        <span ></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="index_part_4_top">
            <div class="index_part_4_ys">
                <div >
                    <p>实力品牌</p>
                    <p>联赛赞助商，信誉领航</p>
                </div>
                <div>
                    <p>安全可靠</p>
                    <p>加密技术和严格的管理</p>
                </div>
                <div>
                    <p>游戏类型</p>
                    <p>海量体育、真人等娱乐</p>
                </div>
                <div>
                    <p>优质服务</p>
                    <p>服务团队，24小时客服</p>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<script type="text/javascript">
    $(function () {

        indexCommonObj.indexBannerAction();

        addIndexScrollTop();
        getRecommendMatch();
        changeRecommented();

        // 首页游戏列表滚动
        var indexGameSwiper = new Swiper('.game-swiper-container',{
            autoplay : 3000, // 自动滚动
            slidesPerView : 5,
            spaceBetween : 0, // 图片间隔
            centeredSlides: true, // 当前焦点图片居中
            speed:500,
            loop : true ,
            prevButton:'.swiper-button-prev',
            nextButton:'.swiper-button-next',
            autoplayDisableOnInteraction : false, // 点击切换后是否自动播放 (默认true 不播放)
            //spaceBetween : '10%',按container的百分比
        })

        // 监听滚动
        function addIndexScrollTop() {
            $(window).on('scroll',function(){
                var scrollTop = $(window).scrollTop();
                var ac_time = 1000;
                // if( scrollTop>400 ){ // 真人
                //     $('.live').addClass('active');
                // }
                if(scrollTop>1900 && !$('.fw_jdt_ck span').hasClass('ck_animate_time')){ // 服务优势
                    $('.fw_jdt_ck span,.fw_jdt_ctk span').addClass('ck_animate_time');
                    $('.fw_jdt_qk span').addClass('qk_animate_time');
                    animateValue("fw_ck_time", 0, 23, ac_time);
                    animateValue("fw_qk_time", 0, 108, ac_time);
                    animateValue("yhzf_num", 0, 88, ac_time);
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

        // 获取推荐赛事 type : FT  BK
        function getRecommendMatch (type) {
            if(!type){
                type = 'FT';
            }
            var ajaxurl = '/app/member/api/recommentedMatchs.php';
            $.ajax({
                url: ajaxurl ,
                type: 'POST',
                dataType: 'json',
                data: {type:type} ,
                success: function (res) {
                    var $recommendmatch_first = $('.recommendmatch_first');
                    var $part1_top_sport = $('.part1_top_sport');
                    var $recommendmatch_second = $('.recommendmatch_second');
                    var $recommendmatch_third = $('.recommendmatch_third');
                    var firtstr = '';
                    var secondstr = '';
                    var thirdstr = '';
                    if(res.status==200) { // 有数据返回 MB_Win_Rate_RB 主队独赢，TG_Win_Rate_RB 客队独赢，M_Flat_Rate_RB 和，MB_Dime_RB 主队大球数( MB_Dime_Rate_RB 主队大的赔率)，TG_Dime_RB 客队大球数( TG_Dime_Rate_RB 客队大的赔率),S_Single_Rate_RB 单，S_Double_Rate_RB 双
                        for(var i=0;i<2;i++){ // 固定两场
                            firtstr += ' <div class="part1_top_content recommendmatch_first"> <div class="left">' +
                                '                        <p class="title" title="'+res.data[i].M_League+'">'+ res.data[i].M_League +'</p>' +
                                '                        <p class="time">'+ res.data[i].M_Start +'</p>' +
                                '                        <div class="team">' +
                                '                            <ul>' +
                                '                                <li>' +
                                '                                    <span class="team_logo team_logo_1" ></span>' +
                                '                                    <p class="team_name">'+ res.data[i].MB_Team +'</p>' +
                                '                                </li>' +
                                '                                <li>VS</li>' +
                                '                                <li>' +
                                '                                    <span class="team_logo team_logo_2" ></span>' +
                                '                                    <p class="team_name">'+ res.data[i].TG_Team +'</p>' +
                                '                                </li>' +
                                '                            </ul>' +
                                '                        </div>' +
                                '                    </div>' +
                                '                    <div class="right">' +
                                '                        <div class="sport_rate">' +
                                '                            <a > 胜<span class="rate">'+ res.data[i].MB_Win_Rate +'</span></a>' +
                                '                            <a > <p>单</p><span class="rate">'+ res.data[i].S_Single_Rate +'</span></a>' +
                                '                            <a > <p>'+ res.data[i].MB_Dime +'</p><span class="rate">'+ res.data[i].MB_Dime_Rate +'</span></a>' +
                                '                            <a > 负<span class="rate">'+ res.data[i].TG_Win_Rate +'</span></a>' +
                                '                            <a > <p>双</p><span class="rate">'+ res.data[i].S_Double_Rate +'</span></a>' +
                                '                            <a > <p>'+ res.data[i].TG_Dime +'</p><span class="rate">'+ res.data[i].TG_Dime_Rate +'</span></a>' +
                                '                            <a > 平<span class="rate">'+ res.data[i].M_Flat_Rate +'</span></a>' +
                                '                            <a href="javascript:;" class="to_sports btn_game sport_bet_btn" data-rtype="r" showtype="today" >立即投注</a>' +
                                '                        </div>' +
                                '                    </div> </div> ';
                        }

                        // if(res.data[1]){
                        //     secondstr = returnMatchStr(res.data[1]);
                        // }
                        // if(res.data[2]){
                        //     thirdstr = returnMatchStr(res.data[2]);
                        // }

                        $part1_top_sport.html(firtstr);
                        // $recommendmatch_second.html(secondstr);
                        // $recommendmatch_third.html(thirdstr);

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
                '                                    <span class="team_logo team_logo_1"></span>' +
                '                                    <p class="team_name">'+ data.MB_Team +'</p>' +
                '                                </li>' +
                '                                <li> <a href="javascript:;" class="to_sports btn_game sport_bet_btn" data-rtype="r" showtype="today"  >立即投注</a></li>' +
                '                                <li>' +
                '                                    <span class="team_logo team_logo_2"></span>' +
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

        // 推荐赛事切换
        function changeRecommented() {
            $('.part1_top').on('click','a',function () {
                var type = $(this).attr('data-type');
                $(this).addClass('btn_game').siblings().removeClass('btn_game');
                getRecommendMatch (type);
            })
        }

    })



</script>