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
    .mainBody{height: 624px;background:url(<?php echo TPL_NAME;?>images/lottery/bg.jpg) no-repeat center;}
    .article-top{width:100%;height:129px;margin-bottom:15px;background:url(<?php echo TPL_NAME;?>images/lottery/award_bg.png) no-repeat left top}
    .article-top>div{display:inline-block;vertical-align:top}
    .article-top .jackpot{width:310px;padding:87px 0 0 119px;color:#fff;font-size:22px}
    .article-top .winners-list{width:685px;padding:15px 36px 0 35px;white-space:nowrap}
    .article-top .winners-list .item{display:inline-block;line-height:26px}
    .article-top .winners-list .item+.item{margin-left:68px}
    .article-top .winners-list span{display:inline-block;color:red;font-size:14px}
    .article-top .winners-list span.white{color:#fff}
    .article-top .winners-list span.blue{color:#0ff}
    .article-top .winners-list span.green{color:#09fa03}
    .article-top .winners-list span.yel{color:#ff0}
    .article-top .winners-list .spa-1{width:81px}
    .article-top .winners-list .item+.item .spa-1{width:85px}
    .article-top .winners-list .spa-2{width:112px}
    .article-top .winners-list .item+.item .spa-2{width:130px}
    .article-top .winners-list .spa-3{width:58px}
    .lobby{display:inline-block;color:#fff;font-size:12px;width:100%}
    .lobby>ul.game-list{font-size:0;text-align:left}
    .game-list>li{cursor:pointer;position:relative;display:inline-block;vertical-align:top;background-repeat:no-repeat;width:252px;height:189px;margin:10px 4px;padding:11px 20px 0 220px;background-color:#222;background-position:left top;-moz-transition:transform 1s;-o-transition:transform 1s;-webkit-transition:transform 1s;transition:transform 1s}
    .game-list>li:hover{-moz-transform:translateY(-5px);-ms-transform:translateY(-5px);-o-transform:translateY(-5px);-webkit-transform:translateY(-5px);transform:translateY(-5px)}
    .game-list>li[game-box="bb"]{background-image:url(<?php echo TPL_NAME;?>images/lottery/bb_img.jpg)}
    .game-list>li[game-box="ig"]{background-image:url(<?php echo TPL_NAME;?>images/lottery/ig_img.jpg)}
    .game-list>li[game-box="ig6"]{background-image:url(<?php echo TPL_NAME;?>images/lottery/ig6_img.jpg)}
    .game-list>li[game-box="lx"]{background-image:url(<?php echo TPL_NAME;?>images/lottery/lx_img.jpg)}
    .game-list>li[game-box="rg"]{background-image:url(<?php echo TPL_NAME;?>images/lottery/rg_img.jpg)}
    .game-list>li[game-box="vr"]{background-image:url(<?php echo TPL_NAME;?>images/lottery/vr_img.jpg)}
    .game-list>li[game-box="gpk-sport"]{background-image:url(<?php echo TPL_NAME;?>images/lottery/gpk_sport.jpg)}
    .game-list>li[game-box="more"]{background-image:url(<?php echo TPL_NAME;?>images/lottery/more.jpg)}
    .game-list>li[game-box="more"]:after{display:none}
    .game-list>li[game-box="bb"] .title{background-image:url(<?php echo TPL_NAME;?>images/huo.png)}
    .game-list>li[game-box="ig"] .title{background-image:url(<?php echo TPL_NAME;?>images/xy.png)}
    .game-list>li i{position:absolute;background:rgba(255,255,255,.2);-moz-transition:all .5s;-o-transition:all .5s;-webkit-transition:all .5s;transition:all .5s}
    .game-list>li i.line-left{width:4px;height:0;left:0;bottom:0}
    .game-list>li i.line-right{width:4px;height:0;top:0;right:0}
    .game-list>li i.line-top{width:0;height:4px;top:0;left:0}
    .game-list>li i.line-bottom{width:0;height:4px;right:0;bottom:0}
    .game-list>li:hover i.line-left,.game-list>li:hover i.line-right{height:100%}
    .game-list>li:hover i.line-top,.game-list>li:hover i.line-bottom{width:100%}
    .game-list>li .title{color:#fff;font-size:16px;line-height:50px;text-align:right;background:no-repeat left center;border-bottom:1px solid #474747}
    .game-list>li p{height:60px;color:#989898;font-size:12px;line-height:20px}
    .game-list>li:after{content:'马上进入';display:block;height:33px;color:#fff;font-size:12px;line-height:31px;text-align:center;border:1px solid #4f4f4f}
    .game-list>li.hover:after{color:#f7bf15;border:1px solid #f7bf15}
    .game-list>li i{background:#fbf490}
    .game-list>li i.line-left{width:2px}
    .game-list>li i.line-right{width:2px}
    .game-list>li i.line-top{height:2px}
    .game-list>li i.line-bottom{height:2px}
    .game-list>li[game-box="more"] i{display:none}

</style>
<div class="page_banner">
    <div class="promlink">
        <div class="centre clearFix">
            <div class="title"><img src="<?php echo TPL_NAME;?>images/lottery/lottery_banner.jpg"></div>
            <div class="marqueeWarp">
                <p style="text-align: center">
                    <marquee id="msgNews" scrollamount="4" scrolldelay="100" direction="left" onmouseover="this.stop();" onmouseout="this.start();" style="cursor: pointer;height: 30px;line-height: 30px;width: 950px;color: #fff;">
                        <?php echo $_SESSION['memberNotice']; ?>
                    </marquee>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="mainBody">
    <div class="w_1000">
        <!--<div class="article-top">
            <div class="jackpot">
                <span ></span>.
                <span></span>
            </div>
            <div class="winners-list" >

            </div>
        </div>-->

        <div class="lobby">
            <ul class="game-list lottery-game-list">
                <li game-box="bb" class="to_lotterys_third">
                    <div class="title">官方彩票</div>
                    <p>提供丰富的游戏玩法：六合彩、3D彩、排列3、时时彩、快乐彩、快乐8…</p>
                </li>
                <li game-box="ig" class="to_lotterys_third" data-to="1">
                    <div class="title">信用彩票</div>
                    <p>IG彩票，提供了福彩3D，体彩P3，时时彩系列、上海、天津、重庆、江西、新疆、陆续推出更多快开采种游戏项目。</p>
                </li>
                <li game-box="ig6" class="to_lotterys_third">
                    <div class="title">更多彩票</div>
                    <p>IG六合彩，提供了福彩3D，体彩P3，时时彩系列、上海、天津、重庆、江西、新疆、陆续推出更多快开采种游戏项目。</p>
                </li>
                <li game-box="lx" class="to_lotterys_third" data-to="1">
                    <div class="title">更多彩票</div>
                    <p>秉承为玩家提供优质产品和流畅体验的理念，还将不断开发更多元化，让您在娱乐中享受生活。</p>
                </li>
            </ul>
        </div>

    </div>

</div>

<script type="text/javascript">
    $(function () {

        hoverLotteryList();
        function hoverLotteryList() {
            $('.lottery-game-list li').hover(function () {
                $(this).addClass('hover');
            },function () {
                $(this).removeClass('hover');
            })
        }
    })
</script>