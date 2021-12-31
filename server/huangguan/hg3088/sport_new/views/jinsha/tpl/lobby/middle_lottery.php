<?php
session_start();

include "../../../../app/member/include/config.inc.php";

//  单页面维护功能
checkMaintain('thirdcp');

$cpUrl = $_SESSION['LotteryUrl'];
$uid = $_SESSION['Oid']; // 判断是否已登录
// to_lotterys_third

?>
<style>
    .lottery_mainbody{position: relative;background: rgb(61, 61, 61);width: 970px;}
    .banner{width:950px;margin:0 auto}
    .wrap_nav{position:absolute;top:214px;left:10px;width:950px;height:69px;padding:17px 0 17px 16px;background:linear-gradient(to right,#111111 50%,rgba(17,17,17,0) 100%);z-index:1}
    .wrap_nav>div:first-child a{float:left;margin-left:10px;padding:0 15px;box-sizing:border-box;border-radius:35px;border:1px solid #AAA;width:108px;line-height:33px;font-size:14px;color:#AAA;cursor:pointer;text-align:center;text-decoration:none;text-overflow:ellipsis;overflow:hidden;white-space:nowrap}
    .wrap_main{margin-top:10px;width:79%;position:relative}
    .hang{display:inline-block;width:49%}
    .title1{border-top-color:#64C8F3;color:#64C8F3;border-top:4px solid #64C8F3}
    .title2{border-top-color:#F8B865;color:#F8B865;border-top:4px solid #F8B865}
    .x_title{position:relative;background:#232632;border-bottom-color:#282B35;padding:22px 0 0 16px;width:100%;height:108px}
    .x_title h1{font-size:26px;line-height:35px;margin-bottom:2px;font-family:Arial,'Microsoft JhengHei',sans-serif}
    .x_title div{border-radius:99em;position:absolute;top:22px;right:16px;font-size:14px;border:1px solid;padding:0 25px;height:35px;line-height:35px;cursor:pointer}
    .x_main li{margin-top:4px;background:#222;height:55px;line-height:55px;font-size:17px;width:100%;cursor:pointer;position:relative}
    .x_main li:hover{background:rgb(19,18,18)}
    .x_main li div{padding-left:16px;color:#CCC;width:76%;margin-right:0 !important}
    .x_main li a{position:absolute;width:39px;height:39px;right:16px;top:10px;z-index:5;background:url(<?php echo TPL_NAME;?>images/lottery/download.png) 0px 0px no-repeat}
    .x_main li a:hover{background:url('<?php echo TPL_NAME;?>images/lottery/download.png') -39px 0px no-repeat}
    .wrap_main>img{display:inline-block;position:absolute;right:-192px}
    .xnew:before{position:absolute;top:0;left:0;content:"";background:url(<?php echo TPL_NAME;?>images/lottery/new.png) 0 0 no-repeat;width:29px;height:29px}
</style>

<div class="w_1000 lottery_mainbody">

    <div class="banner">
        <div class="jBanners banner">
        <div class="swiper-container" >
            <div class="swiper-wrapper">
                <div class="swiper-slide" >
                    <a href="javascript:;" >
                        <!--<img data-src="<?php echo TPL_NAME;?>images/1.jpg" class="swiper-lazy" alt="">-->
                        <img src="<?php echo TPL_NAME;?>images/lottery/BBFT01.jpg" class="swiper-lazy" alt="">
                        <!--<div class="swiper-lazy-preloader"></div>-->
                    </a>
                </div>
                <div class="swiper-slide" >
                    <a href="javascript:;" >
                        <img src="<?php echo TPL_NAME;?>images/lottery/BBFT02.jpg" class="swiper-lazy" alt="">
                        <!--<div class="swiper-lazy-preloader"></div>-->
                    </a>
                </div>
                <div class="swiper-slide" >
                    <a href="javascript:;" >
                        <img src="<?php echo TPL_NAME;?>images/lottery/BBFT03.jpg" class="swiper-lazy" alt="">
                        <!--<div class="swiper-lazy-preloader"></div>-->
                    </a>
                </div>
                <div class="swiper-slide" >
                    <a href="javascript:;" >
                        <img src="<?php echo TPL_NAME;?>images/lottery/BBFT04.jpg" class="swiper-lazy" alt="">
                        <!--<div class="swiper-lazy-preloader"></div>-->
                    </a>
                </div>
                <div class="swiper-slide" >
                    <a href="javascript:;" >
                        <img src="<?php echo TPL_NAME;?>images/lottery/BBFT05.jpg" class="swiper-lazy" alt="">
                        <!--<div class="swiper-lazy-preloader"></div>-->
                    </a>
                </div>
                <div class="swiper-slide" >
                    <a href="javascript:;" >
                        <img src="<?php echo TPL_NAME;?>images/lottery/BBFT06.jpg" class="swiper-lazy" alt="">
                        <!--<div class="swiper-lazy-preloader"></div>-->
                    </a>
                </div>

            </div>
           <!-- <div class="swiper-pagination"></div>-->

        </div>

    </div>
    </div>
    <div class="wrap_nav">
        <div>
            <a href="javascript:;" class="to_lotterys_third" data-to="1">投注入门</a>
            <a href="javascript:;" class="to_lotterys_third" data-to="1">进入游戏</a>
            <a href="javascript:;" class="to_lotterys_third" data-to="1">游戏规则</a>
        </div>
        <!-- 轮播图导航栏 -->
        <div>
            <ul>
                <li><a class="" href="javascript:;"><span></span></a></li>
                <li><a href="javascript:;" class="wrap_active"><span></span></a></li>
                <li><a href="javascript:;" class=""><span></span></a></li>
                <li><a href="javascript:;" class=""><span></span></a></li>
                <li><a href="javascript:;" class=""><span></span></a></li>
                <li><a href="javascript:;" class=""><span></span></a></li>
            </ul>
        </div>
    </div>

    <div class="wrap_main">
        <div class="hang">
            <div class="x_title title1">
                <h1>信用盘</h1>
                <div class="to_lotterys_third" data-to="1">更多游戏</div>
                <p>群组投注,少走弯路</p>
            </div>
            <div class="x_main">
                <ul>
                    <li class="to_lotterys_third"  data-to="1" data-gametype="70" >
                        <div>香港六合彩</div>
                        <a href="javascript:;"></a>
                    </li>
                    <li class="to_lotterys_third"  data-to="1" data-gametype="51" >
                        <div>极速赛车</div>
                        <a href="javascript:;"></a>
                    </li>

                    <li class="to_lotterys_third"  data-to="1" data-gametype="76" >
                        <div>北京赛车</div>
                        <a href="javascript:;"></a>
                    </li>
                    <li class="to_lotterys_third"  data-to="1" data-gametype="55" >
                        <div>幸运飞艇</div>
                        <a href="javascript:;"></a>
                    </li>
                    <li class="to_lotterys_third"  data-to="1" data-gametype="10" >
                        <div>江苏快三</div>
                        <a href="javascript:;"></a>
                    </li>
                    <li class="to_lotterys_third"  data-to="1" data-gametype="61" >
                        <div>幸运农场</div>
                        <a href="javascript:;"></a>
                    </li>
                </ul>

            </div>
        </div>
        <div class="hang">
            <div class="x_title title2">
                <h1>传统盘</h1>
                <div class="to_lotterys_third" >更多游戏</div>
                <p>彩种齐全,历久不衰</p>
            </div>
            <div class="x_main">
                <ul>
                    <li class="to_lotterys_third xnew" data-gametype="bjpk105fc">
                        <div>北京赛车</div>
                        <a href="javascript:;"></a>
                    </li>
                    <li class="to_lotterys_third xnew" data-gametype="cqssc">
                        <div>欢乐生肖</div>
                        <a href="javascript:;"></a>
                    </li>
                    <li class="to_lotterys_third" data-gametype="xcqssc">
                        <div>重庆时时彩</div>
                        <a href="javascript:;"></a>
                    </li>
                    <li class="to_lotterys_third" data-gametype="xyft">
                        <div>幸运飞艇</div>
                        <a href="javascript:;"></a>
                    </li>
                    <li class="to_lotterys_third" data-gametype="gw3d">
                        <div>极速3D</div>
                        <a href="javascript:;"></a>
                    </li>
                    <li class="to_lotterys_third" data-gametype="jsk3">
                        <div>江苏快三</div>
                        <a href="javascript:;"></a>
                    </li>
                </ul>

            </div>
        </div>
        <img src="<?php echo TPL_NAME;?>images/lottery/bj2.jpg" alt="">
    </div>


    
</div>


<script type="text/javascript">
    $(function () {
        indexCommonObj.bannerSwiper();
        
    })
</script>