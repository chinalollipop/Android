<?php

$uid=$_SESSION["Oid"];
$userid = $_SESSION['userid'];
$username = $_SESSION['UserName']; // 拿到用户名


?>

<style type="text/css">
    .home_container{width:100%;margin:0 auto;overflow:hidden;top:0;left:0;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        flex-direction: column;
    }
    .home_container .header{height:3rem;box-shadow: 0 0 3.2rem rgba(0,0,0,.1) inset;margin-bottom: 0;}
    .home_container>div{-webkit-flex:none;flex:none;height:auto}
    .swiper-container{width:98%;min-height:6rem;margin:auto}
    .swiper-wrapper{height: auto;}
    .swiper-slide{width:90%}
    .carousel img{width:100%}
    .notice{width:98%;margin:auto;background:#6b91e9;background:linear-gradient(to right,#6b91e9 0%,#5da2ea 100%);border-radius:5px 5px 0 0;font-size:1rem;height:2rem !important;line-height:2rem}
    .notice-cont {width: 100%;height: 100%}
    .notice div,.notice span{ display: inline-block;}
    .notice .notice-icon{width:3rem;height: 2.5rem;position: static;float: left;margin-top: -0.2rem;background-image:url(<?php echo TPL_NAME;?>images/index/gonggao.png);background-size: 60%;background-position: 10px 4px;}
    .notice .text {width: 85%;height:100%;float: left;}
    .notice .more-notice a{color:#FE6B5A; }

    /* 首页游戏列表 */
    .Menual{width: 98%;display:inline-block;height: 7rem;}
    .listType:first-child .Menual:first-child {margin-top: .5rem;}
    .Menual a {display:block;color: #2A8FBD;font-size: 1.2rem;position: relative;}
    .Menual span{display: block;height: 7rem;margin: 0 auto .5rem;background-size: 100%;border-radius: 6px;-webkit-border-radius: 6px;-ms-border-radius: 6px;}
    .Menual .num{display:none;position:absolute;height:auto;top:28%;left:51%;color:#fff;font-size:1.6rem;}
    .Menual .num.hgSportNum{top:35%;left: 42%;width: 4rem;text-align: right;}
    .Menual .game-sport-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_sport.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-sport-logo-bk{background: url(<?php echo TPL_NAME;?>images/index/idx_sport_bk.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-bbin-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_bb.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-oblive-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_allbet.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-live-ag-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_ag.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-live-og-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_og.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-live-bbin-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_bbin.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-kyqp-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_kg.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-lottery-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_vr.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-lottery-logo-xy{background: url(<?php echo TPL_NAME;?>images/index/idx_vr_xy.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-byw-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_fish.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-pkw-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_honey.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-qhb-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_hongbao.jpg?v=2) center no-repeat;background-size: 100%100%;}
    .Menual .game-xchb-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_yearhb.jpg?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-xchb68-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_yearhb68.jpg?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-promo-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_pro.jpg?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-ydhg-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_yd.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-dljm-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_agent.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-pc-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_pc.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-by-logo{background: url(<?php echo TPL_NAME;?>images/index/game-by-logo.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-dianjing-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_dianjing.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-dianjing-lh-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_dianjing_lh.png) center no-repeat;background-size: 100%;}
    .Menual .game-app-logo{background: url(<?php echo TPL_NAME;?>images/index/appload.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-xldh-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_wifi.jpg?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-lxwm-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_contact.jpg?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-hggg-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_remind.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-help-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_newbie.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-qq-logo{ background: url(<?php echo TPL_NAME;?>images/home_qq.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-wechat-logo{ background: url(<?php echo TPL_NAME;?>images/home_wechat.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-chess-logo{ background: url(<?php echo TPL_NAME;?>images/index/idx_chess.jpg?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-chess-logo-ky{ background: url(<?php echo TPL_NAME;?>images/index/idx_chess_ky.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-chess-logo-vg{ background: url(<?php echo TPL_NAME;?>images/index/idx_chess_vg.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-chess-logo-hg{ background: url(<?php echo TPL_NAME;?>images/index/idx_chess_hg.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-chess-logo-ly{ background: url(<?php echo TPL_NAME;?>images/index/idx_chess_ly.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-chess-logo-kl{ background: url(<?php echo TPL_NAME;?>images/index/idx_chess_kl.png) center no-repeat;background-size: 100%;}
    .Menual .game-fg-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_egame_fg.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-ag-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_egame_ag.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-mg-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_egame_mg.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-cq-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_egame_cq9.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-mw-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_egame_mw.png?v=2) center no-repeat;background-size: 100%;}

    .login_box{width:98%;margin:0 auto .4rem;color:#7c7c7c;font-size:1rem;background:#fff;border-radius:0 0 5px 5px}
    .login_box .login_title{background:linear-gradient(to right,#ca9024 1%,#eeaf46 100%);color:#fff;text-align:left;padding:0 1.3rem;height:2.3rem;line-height:2.3rem;border-radius:5px 5px 0 0;overflow:hidden}
    .login_box .login_title span{display:inline-block;max-width:40%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .login_box .login_txt{display:inline-block;width:6rem;height:2.3rem;background:url(<?php echo TPL_NAME;?>images/logo_icon.png) no-repeat;background-size:100%}
    .login_box  .login_grzl{border:1px solid #fff;border-radius:7px;margin-left:1.5rem;padding:.2rem .8rem}
    .login_box .management{padding:.5rem 0 .3rem;overflow:hidden}
    .login_box .wallet_center{display:inline-block;width:44%;float:left}
    .login_box .wallet_center p{-webkit-background-clip:text;color:transparent;-webkit-text-fill-color:transparent}
    .login_box .font_Library span{color:#000;font-weight: 700;}
    .login_box .font_Library i{font-style:normal;color:#d89a37}
    .login_box .financial li{float:left;width:14%;margin-left:1%;background:#fff;border-radius:8px;padding:5px 5px 0}
    .login_box .financial li i{margin:-.5rem -1.6rem;position:static}
    @media only screen and (max-width:320px){
        .login_box .financial li {margin-left: 0;transform: scale(.95);}
    }

    /* 游戏列表轮播 */
    .swiper-pagination-game.swiper-pagination-custom { display: -webkit-box;display: -webkit-flex;display: flex;width: 100%;overflow: hidden;padding: 5px;}
    .swiper-pagination-game .swiper-pagination-custom{-webkit-flex:auto;flex: auto;position:relative;bottom:0;width:auto;color:#060606;height:2.3rem;line-height:2.3rem;}
    .swiper-pagination-game .swiper-pagination-custom:last-child{padding: 0;}
    .swiper-pagination-game .swiper-pagination-custom.active{width:20%;/*border-radius: 20px;background: #7387e8;background: linear-gradient(to right,#7387e8 0%,#5f9fea 100%);*/color: #fff;}
    .swiper-pagination-game .swiper-pagination-custom.active a{color: #fff;}
    .swiper-pagination-game .swiper-pagination-custom.active:first-child a{margin-right:20%}
    .game-page-all{position:relative;width:98%;z-index:1;background:#fff;border-radius:5px;margin:auto}
    .game_nav_on{position:absolute;width:25%;height:2.3rem;border-radius:20px;background:#7387e8;background:linear-gradient(to right,#7387e8 0%,#5f9fea 100%);margin:5px 0 0 0;transition-duration:200ms}
    .swiper-pagination-game a{color: #000}
    .home_container .middle_content{overflow-y: scroll;-webkit-overflow-scrolling: touch;
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        flex: 1;}

</style>

<!-- 轮播图 区域-->
<div class="carousel swiper-container">
    <div class="swiper-wrapper">

    </div>
    <!-- 如果需要分页器 -->
   <!-- <div class="swiper-pagination"></div>-->
</div>
<!-- 滚动公告 -->
<div class="notice index_notice" onclick="javascript:void(0);location.href='<?php echo TPL_NAME;?>moremessage.php'">
    <div class="notice-cont">
        <i class="notice-icon index_fa">

        </i>
        <div class="text">
            <marquee>
                <?php echo getScrollMsg();?>
            </marquee>
        </div>
    </div>
</div>

<div class="login_box">
    <div class="login_box_sec">
        <div class="management">
            <div class="wallet_center">
                <p class="linear-color"><?php if($username=='' || !$username){echo '未登录'; }else{ echo '欢迎您，'.$username.'';} ?></p>
                <span class="font_Library">钱包中心:<span class="hg_money">0.00</span> </span>
            </div>
            <ul class="financial">
                <li onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/deposit_one.php\',\'\',\''.$oid.'\')';?>">
                    <i class="index_fa fa-ck"></i>
                    <div >
                        <p>存款</p>
                    </div>
                </li>
                <li onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/withdraw.php\',\'\',\''.$oid.'\')';?>">
                    <i class="index_fa fa-withdrow"></i>
                    <div >
                        <p>取款</p>
                    </div>

                </li>
                <li onclick="ifHasLogin('<?php echo TPL_NAME;?>tran.php','','<?php echo $oid?>')">
                    <i class="index_fa fa-zz"></i>
                    <div >
                        <p>转账</p>
                    </div>


                </li>

            </ul>
        </div>
    </div>
</div>

<div class="game-page-all">
    <div class="game_nav_on"></div>
    <!-- 如果需要分页器 -->
    <div class="swiper-pagination-game swiper-pagination-custom">
        <li class="swiper-pagination-custom active"><a >体育</a></li>
        <li class="swiper-pagination-custom"><a >真人</a></li>
        <li class="swiper-pagination-custom"><a >电竞</a></li>
        <li class="swiper-pagination-custom"><a >彩票</a></li>
        <li class="swiper-pagination-custom"><a >棋牌</a></li>
        <li class="swiper-pagination-custom"><a >电游</a></li>
    </div>
</div>

<div class="middle_content">

    <!-- 轮播图 区域-->
    <div class="carousel swiper-container-game">
        <div class="gameListAll">
            <div class="listType" id="li_sport">
                <div class="Menual">
                    <a href="javascript:;" onclick="ifHasLogin('/template/sport_main.php','','<?php echo $oid?>')" ><span class="game-sport-logo"></span> <p class="num hgSportNum"> </p> </a>
                </div>
                <div class="Menual">
                    <a href="javascript:;" onclick="ifHasLogin('/template/sport_main.php','','<?php echo $oid?>')" ><span class="game-sport-logo-bk"></span> <p class="num hgSportNum"> </p> </a>
                </div>
            </div>

            <div class="listType" id="li_live">
                <div class="Menual">
                    <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/zrsx_login.php?uid='.$oid.'\',\'win\',\''.$oid.'\')';?>"><span class="game-live-ag-logo"></span> <p class="num agLiveNum"> </p> </a>
                </div>
                <div class="Menual">
                    <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/og/login.php?uid='.$oid.'\',\'win\',\''.$oid.'\')';?>"><span class="game-live-og-logo"></span> <p class="num ogLiveNum"> </p> </a>
                </div>
                <div class="Menual">
                    <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/bbin/login.php?uid='.$oid.'\',\'win\',\''.$oid.'\')';?>"><span class="game-live-bbin-logo"></span> <p class="num bbinLiveNum"> </p> </a>
                </div>
            </div>
            <div class="listType" id="li_dianjing">
                <div class="Menual">
                    <a href="javascript:;" onclick="ifHasLogin('/avia/avia_api.php?action=getLaunchGameUrl','win','<?php echo $oid?>')" >
                        <span class="game-dianjing-logo"></span> <p class="num fydjNum"> </p> </a>
                </div>
                <div class="Menual">
                    <a href="javascript:;" onclick="ifHasLogin('/thunfire/fire_api.php?action=getLaunchGameUrl','win','<?php echo $oid?>')" >
                        <span class="game-dianjing-lh-logo"></span> <p class="num lhdjNum"> </p> </a>
                </div>
            </div>
            <div class="listType" id="li_lottery">
                <div class="Menual">
                    <a onclick="ifHasLogin('<?php echo TPL_NAME;?>lotteryThird.php','win','<?php echo $oid?>')" >
                        <span class="game-lottery-logo"></span>
                        <p class="num lotteryChessNum"> </p>
                    </a>
                </div>
                <div class="Menual">
                    <a onclick="ifHasLogin('<?php echo TPL_NAME;?>lotteryThird.php?type=1','win','<?php echo $oid?>')" >
                        <span class="game-lottery-logo-xy"></span>
                        <p class="num lotteryChessNum"> </p>
                    </a>
                </div>
            </div>

            <div class="listType" id="li_chess">
                <?php
                if(($flage && $flage=='test')){
                    $vg_rul = 'https://sw.vgvip88.com'; // 试玩链接
                    $ly_rul = 'https://demo.leg666.com'; // 试玩链接
                    $ky_rul = 'http://play.ky206.com/jump.do'; // 试玩链接
                    //$kl_rul = 'http://tstqpint.z389.com/?c=default&a=playGame&gameId=100'; // 试玩链接
                }else{
                    $vg_rul = '/vgqp/';
                    $ly_rul = '/lyqp/';
                    $ky_rul = '/ky/';
                    $kl_rul = '/klqp/';
                }
                ?>
                <div class="Menual">
                    <a onclick="ifHasLogin('<?php echo $ky_rul;?>','','<?php echo $oid?>')">
                        <span class="game-chess-logo-ky"></span>
                        <p class="num kyChessNum"> </p>
                    </a>
                </div>
                <div class="Menual">
                    <a onclick="ifHasLogin('<?php echo $ly_rul;?>','','<?php echo $oid?>')">
                        <span class="game-chess-logo-ly"> </span>
                        <p class="num lyChessNum"> </p>
                    </a>
                </div>
                <div class="Menual">
                    <a onclick="ifHasLogin('<?php echo $vg_rul;?>','','<?php echo $oid?>')">
                        <span class="game-chess-logo-vg"> </span>
                        <p class="num vgChessNum"> </p>
                    </a>
                </div>
                <div class="Menual">
                    <a onclick="ifHasLogin('<?php echo $kl_rul;?>','','<?php echo $oid?>')">
                        <span class="game-chess-logo-kl"> </span>
                        <p class="num klChessNum"> </p>
                    </a>
                </div>
              <!--  <div class="Menual">
                    <a onclick="<?php /*echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/hgqp/index.php\',\'\',\''.$oid.'\')';*/?>">
                        <span class="game-chess-logo-hg"> </span>
                        <p class="num hgChessNum"> </p>
                    </a>
                </div>-->

            </div>

            <div class="listType" id="li_game">
                <div class="Menual">
                    <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/zrsx_login.php?gameid=6&uid='.$oid.'\',\'win\',\''.$oid.'\')';?>" >
                        <span class="game-by-logo"></span> </a>
                </div>
                <div class="Menual">
                    <a href="javascript:;" onclick="ifHasLogin('<?php echo TPL_NAME;?>games.php?gametype=ag','','<?php echo $oid?>')" >
                        <span class="game-ag-logo"></span></a>
                </div>
                <div class="Menual">
                    <a href="javascript:;" onclick="ifHasLogin('<?php echo TPL_NAME;?>games.php?gametype=mg','','<?php echo $oid?>')" >
                        <span class="game-mg-logo"></span></a>
                </div>
                <div class="Menual">
                    <a href="javascript:;" onclick="ifHasLogin('<?php echo TPL_NAME;?>games.php?gametype=mw','','<?php echo $oid?>')" >
                        <span class="game-mw-logo"></span></a>
                </div>
                <div class="Menual">
                    <a href="javascript:;" onclick="ifHasLogin('<?php echo TPL_NAME;?>games.php?gametype=cq','','<?php echo $oid?>')" >
                        <span class="game-cq-logo"></span></a>
                </div>
                <div class="Menual" style="margin-bottom: 6rem;">
                    <a href="javascript:;" onclick="ifHasLogin('<?php echo TPL_NAME;?>games.php?gametype=fg','','<?php echo $oid?>')" >
                        <span class="game-fg-logo"></span></a>
                </div>

                <!-- 占位 -->
                <!--<div class="Menual" style="visibility: hidden;">
                    <a href="javascript:;"  >
                        <span class="game-mw-logo"></span></a>
                </div>-->
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>


<script type="text/javascript">


</script>
