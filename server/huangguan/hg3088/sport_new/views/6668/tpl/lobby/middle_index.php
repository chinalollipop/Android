<?php

session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$companyName = $_SESSION['COMPANY_NAME_SESSION'];
?>
<style>
    .swiper-pagination-bullet{width: 8px;height: 8px;}
    .swiper-pagination-bullet-active{background:#007aff }
    /* 公告 */
    .tip{text-align:center;width:100%;height:30px;line-height:30px;overflow:hidden;background:rgba(0,0,0,.9)}
    .tip img{float:left;margin-top:7px;margin-right:15px;margin-left:300px;width:1%}
    .tip .tipText{float:left;width:50%;color:#fff;text-align:center;background:rgba(0,0,0,.1)}
    /* 首页游戏 */
    .game_category_txt .icon_accessible{background:url(<?php echo $tplNmaeSession;?>images/bet_game_icon.png) no-repeat;height:85px;width:85px;display:inline-block;padding-top:55px;background-position-y:52px}
    .game_category_txt .icon_accessible.zr_game{background:url(<?php echo $tplNmaeSession;?>images/bet_game_icon1.png) no-repeat;background-position-y:52px}
    .game_category_txt .icon_accessible.dz_game{background:url(<?php echo $tplNmaeSession;?>images/bet_game_icon3.png) no-repeat;background-position-y:52px}
    .game_category_txt .icon_accessible.cp_game{background:url(<?php echo $tplNmaeSession;?>images/bet_game_icon4.png) no-repeat;background-position-y:52px}
    .game_category_txt .icon_accessible.qp_game{background:url(<?php echo $tplNmaeSession;?>images/bet_game_icon5.png) no-repeat;background-position-y:52px}
    .game_category_title{display:inline-block;vertical-align:16px;font-size:27px;text-align:left;margin-left:15px}
    .game_category_title p{font-size:18px;color:#4E5459}
    .game_category{width:100%;padding-bottom:.1px;background:#333}
    .game_category ul li{width:20%;float:left;position:relative;box-sizing:border-box;cursor: pointer;}
    .game_category_img img{height:310px;width:396px}
    .game_category ul li:hover{border:1px solid #FFE38F;box-sizing:border-box}
    .game_category ul li:hover .game_category_img{}
    .game_category ul li:hover .mask{background:none}
    .game_category ul li:hover .game_category_txt{color:#333}
    .mask{position:absolute;background:rgba( 0,0,0,.5 );height:100%;width:100%;top:0}
    .game_category_img img{width:100%}
    .game_category_txt{text-align:center;background:#2A292E;color:#fff;height:310px}
    .text_color{margin:auto;width:260px;text-align:left;font-size:16px;padding-top:10px;color:#4E5459}
    .game_category ul li:hover .game_category_txt{background:#FFE38F}
    .game_category ul li:hover .icon_accessible{background:url("<?php echo $tplNmaeSession;?>images/bet_game_icon_color.png") no-repeat;background-position-y:52px}
    .game_category ul li:hover .icon_accessible.zr_game{background:url("<?php echo $tplNmaeSession;?>images/bet_game_icon_color2.png") no-repeat;background-position-y:52px}
    .game_category ul li:hover .icon_accessible.dz_game{background:url("<?php echo $tplNmaeSession;?>images/bet_game_icon_color3.png") no-repeat;background-position-y:52px}
    .game_category ul li:hover .icon_accessible.cp_game{background:url("<?php echo $tplNmaeSession;?>images/bet_game_icon_color4.png") no-repeat;background-position-y:52px}
    .game_category ul li:hover .icon_accessible.qp_game{background:url("<?php echo $tplNmaeSession;?>images/bet_game_icon_color5.png") no-repeat;background-position-y:52px}
    .banner_base{text-align: center;}
    .banner_base a img{margin: 0 auto;width: 100px;animation: weuiLoading 1s steps(12) infinite;}
    @keyframes weuiLoading {
        0% {transform: rotate(0deg)}
        to {transform: rotate(1turn)}
    }
</style>
<!-- 轮播 -->
<div class="swiper-container" >
    <div class="swiper-wrapper">
        <div class="banner_base swiper-slide" >
            <a href="javascript:;" >
                <img src="/images/loading.svg">
            </a>
        </div>
  
    </div>
  <!--  <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>-->

    <!-- 如果需要分页器 -->
    <div class="swiper-pagination"></div>
</div>
<div class="tip clearfix">
    <img src="<?php echo $tplNmaeSession;?>images/bugle.png" alt="">
    <div class="tipText">
        <marquee>
            <?php echo $_SESSION['memberNotice']; ?>
        </marquee>
    </div>
</div>

<div class="game_category ">
    <ul>
        <li class="to_sports" data-rtype="r" data-showtype="today">
            <div style="width: 100%">
                <div class="game_category_img">
                    <img src="<?php echo $tplNmaeSession;?>images/a3.jpg?v=2" alt="">
                </div>
                <div class="game_category_txt">
                    <div class="icon_accessible"></div>
                    <div class="game_category_title">
                        体育竞技
                        <p>SPORTS</p>
                    </div>
                    <div class="text_color">
                        每周数万场精彩体育赛事
                        </br>
                        提供全亚洲最精准的盘口数据
                        </br>
                        最人性化的投注赔率
                        </br>
                        <?php echo $companyName;?>与您一起
                        </br>
                        共享体育竞技
                    </div>
                </div>
                <div class="mask"></div>
            </div>
        </li>
        <li class="to_lives">
            <div class="game_category_txt">
                <div class="icon_accessible zr_game"></div>
                <div class="game_category_title">
                    真人视讯
                    <p>LIVE CASINO</p>
                </div>
                <div class="text_color">
                    性感美女荷官比基尼美女陪您玩
                    </br>
                    多桌牌局系统一次下注全部赢取
                    </br>
                    多款经典游戏百家乐，龙虎，
                    </br>
                    骰宝，轮盘
                    </br>
                    尊贵体验犹如亲临拉斯维加斯
                </div>

            </div>
            <div class="game_category_img">
                <img src="<?php echo $tplNmaeSession;?>images/a4.jpg?v=2" alt="">
            </div>
            <div class="mask"></div>

        </li>
        <li class="to_games">
            <div class="game_category_img">
                <img src="<?php echo $tplNmaeSession;?>images/a2.jpg?v=2" alt="">
            </div>
            <div class="game_category_txt">
                <div class="icon_accessible dz_game"></div>
                <div class="game_category_title">
                    电子游艺
                    <p>GAME</p>
                </div>
                <div class="text_color">
                    全球最佳电子游戏平台
                    </br>
                    10000多款游戏任您选择
                    </br>
                    国际水准，充满享受的娱乐体验
                    </br>
                    <?php echo $companyName;?>电子十分稳定，爆分率高
                    </br>
                    更有亿万奖池随机爆
                </div>
            </div>
            <div class="mask"></div>

        </li>
        <li class="to_lotterys">
            <div class="game_category_txt">
                <div class="icon_accessible cp_game"></div>
                <div class="game_category_title">
                    彩票游戏
                    <p>LOTTERY</p>
                </div>
                <div class="text_color">
                    高清画质，最专业的彩票投注平台，
                    </br>
                    热门彩种，应有尽有，玩法多样、</br>简单，刺激，高赔率，大奖等您拿！
                </div>
            </div>
            <div class="game_category_img">
                <img src="<?php echo $tplNmaeSession;?>images/a1.jpg?v=2" alt="">
            </div>
            <div class="mask"></div>

        </li>
        <li class="to_chess">
            <div class="game_category_img">
                <img src="<?php echo $tplNmaeSession;?>images/a5.jpg?v=2" alt="">
            </div>
            <div class="game_category_txt">
                <div class="icon_accessible qp_game"></div>
                <div class="game_category_title">
                    棋牌游戏
                    <p>CHESS GAME</p>
                </div>
                <div class="text_color">
                    全球顶级竞技棋牌中心，万人
                    </br>
                    在线、火热PK。超高赔率，多种
                    </br>
                    玩法和模式。自由选择，选择匹配，
                    </br>
                    真人对战，公平公正，给你最舒心的
                    </br>
                    游戏体验，棋牌魅力享不停！
                </div>
            </div>
            <div class="mask"></div>

        </li>
    </ul>
    <div style="clear: both"></div>
</div>



<script type="text/javascript">
    $(function () {

        indexCommonObj.indexBannerAction();
        indexGameHeight(0.686) ;


    })


</script>