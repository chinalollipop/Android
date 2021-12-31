<?php

$uid=$_SESSION["Oid"];
$userid = $_SESSION['userid'];
$username = $_SESSION['UserName']; // 拿到用户名

// APP的6668的2020新年活动开关
$sRedPocketset = $redisObj->getSimpleOne('red_pocket_open_6668_2020'); // 取redis 设置的值
$aRedPocketset = json_decode($sRedPocketset,true) ;
$af_aRedPocketset = $aRedPocketset['redPocketOpen']=='open'?TRUE:FALSE;

?>
<style>
    .home_container{width:100%;margin:0 auto;overflow:hidden;top:0;left:0;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        flex-direction: column;
    }
    .home_container>div{-webkit-flex:none;flex: none;height: auto;}
    .home_container .header{height: 2.8rem;}
    /* app 下载提示 */
    .app_tip{display:none;background:url(<?php echo TPL_NAME;?>images/apptip/bg.png?v=2) center no-repeat;background-size:cover;text-align:left;padding: 5px 0 2px 6%;}
    .app_tip .app_tip_logo{display:inline-block;width: 2.7rem;height: 2.7rem;background:url(<?php echo TPL_NAME;?>images/apptip/logo.png?v=2) center no-repeat;background-size:100%;margin-right: 0.5rem;}
    .app_tip div{display:inline-block;vertical-align:top}
    .app_tip .title p{font-size: 1rem;}
    .app_tip .title p:first-child{/* margin-top: .1rem; */}
    .app_tip .download_btn{display:inline-block;background:#fff;border-radius:20px;color:#2a8fbd;padding:3px 8px 4px;float:right;margin: .5rem 10% 0 0;}
    .app_tip .download_btn span{display:inline-block;vertical-align:middle}
    .app_tip .icon{width:1.5rem;height:1.5rem}
    .app_tip .icon.and{background:url(<?php echo TPL_NAME;?>images/apptip/and.png?v=2) center no-repeat;background-size:100%}
    .app_tip .icon.ios{background:url(<?php echo TPL_NAME;?>images/apptip/ios.png?v=2) center no-repeat;background-size:100%}
    .app_tip .app_close{display:inline-block;width:1.4rem;height:1.4rem;background:url(<?php echo TPL_NAME;?>images/apptip/close.png?v=2) center no-repeat;background-size:100%;position:absolute;right:1%;top:.8%}

    /* 轮播 */
    .lb_gg{position: relative}
    .notice {padding: .4rem 0;margin:0 auto;}
    .index_notice{background: rgba(0,0,0,.5);padding: .4rem 3% .1rem;position: absolute;border-radius: 15px 15px 0 0;bottom: 0;z-index: 2;}
    .notice-cont {width: 100%;}
    .notice div,.notice span{ display: inline-block;}
    .notice .notice-icon{width: 2.2rem;height: 1.2rem;color: #2A8FBD;background: url(<?php echo TPL_NAME;?>images/notice.png?v=2) center no-repeat;background-size:contain;vertical-align: middle;}
    .notice .text {width: 87%;float: right;margin-left: 0.8rem;}
    .notice .more-notice a{color:#FE6B5A; }

    /* 首页 */
    .Menual{width: 94%;display:inline-block;margin-top: .5rem;height: 7rem;}
    .Menual a {display:block;color: #2A8FBD;font-size: 1.2rem;position: relative;}
    .Menual span{display: block;height: 7rem;margin: 0 auto .5rem;background-size: 100%;border-radius: 6px;-webkit-border-radius: 6px;-ms-border-radius: 6px;}
    .Menual .num{position:absolute;height:auto;top:28%;left:51%;color:#fff;font-size:1.6rem;}
    .Menual .num.hgSportNum{top:35%;left: 42%;width: 4rem;text-align: right;}
    .Menual .game-sport-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_sport.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-bbin-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_bb.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-oblive-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_allbet.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-live-ag-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_ag.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-live-og-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_og.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-live-bbin-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_bbin.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-kyqp-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_kg.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-lottery-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_vr.png?v=2) center no-repeat;background-size: 100%;}
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
    .Menual .game-lh-logo{background: url(<?php echo TPL_NAME;?>images/index/game-lh-logo.png?v=2) center no-repeat;background-size: 100%;}
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
    .Menual .game-chess-logo-kl{ background: url(<?php echo TPL_NAME;?>images/index/idx_chess_kl.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-chess-logo-hg{ background: url(<?php echo TPL_NAME;?>images/index/idx_chess_hg.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-chess-logo-ly{ background: url(<?php echo TPL_NAME;?>images/index/idx_chess_ly.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-fg-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_egame_fg.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-ag-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_egame_ag.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-mg-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_egame_mg.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-cq-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_egame_cq9.png?v=2) center no-repeat;background-size: 100%;}
    .Menual .game-mw-logo{background: url(<?php echo TPL_NAME;?>images/index/idx_egame_mw.png?v=2) center no-repeat;background-size: 100%;}
    /* 钱包中心 */
    .login_box{width:100%;height:6rem;padding:0 8px;background: url(<?php echo TPL_NAME;?>images/index/qb_bg.png?v=2) top center no-repeat;background-size: 100%;}
    .login_box .login_title{color: #707070;text-align:left;padding:0 1.3rem;height: 2.3rem;line-height:2.3rem;border-radius:5px 5px 0 0;overflow:hidden;}
    .login_box .login_title span {max-width: 100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;vertical-align: top;display: inline-block;}
    .login_box .management{padding:.5rem .8rem 0;overflow:hidden;border-top: 1px dashed #e8e8e8;}
    .login_box .wallet_center{display:inline-block;width: 65%;float: right;text-align: right;}
    .login_box .font_Library i{font-style:normal}
    i.login_icon{background-position:-212px -3px;margin-top:-.4rem}

    /* 游戏列表轮播 */
    .swiper-pagination-game.swiper-pagination-custom { display: -webkit-box;display: -webkit-flex;display: flex;width: 100%;background: #fff;box-shadow: 0 2px 4px rgba(169, 169, 169, 0.2);overflow: hidden;border-radius: 0;}
    .swiper-pagination-game .swiper-pagination-custom{-webkit-flex:auto;flex: auto;position:relative;bottom:0;width:auto;color:#000;height:3rem;line-height:4rem;}
    .swiper-pagination-game .swiper-pagination-custom:last-child{padding: 0;}
    .swiper-pagination-game .swiper-pagination-custom.active{line-height: 3.2rem;width: 30%;color: #fff;transform:scale(1.1);}
    .swiper-pagination-game .swiper-pagination-custom.active a{color: #fff;}
    .game-page-all{position: relative;width: 100%;z-index: 1;}
    .game_nav_on{position:absolute;width:35%;height: 3rem;background: url(<?php echo TPL_NAME;?>images/index/btn_active.png?v=2) center no-repeat;background-size: 100%;margin-top: .40rem;transition-duration:200ms;}
    .swiper-pagination-game .swiper-pagination-custom.active:before{position:absolute;width:100%;left:0;line-height:5.2rem;transform:scale(.65)}
    .swiper-pagination-game .swiper-pagination-custom:first-child.active:before{content:'SPORTS'}
    .swiper-pagination-game .swiper-pagination-custom:nth-child(2).active:before{content:'LIVE CASINO'}
    .swiper-pagination-game .swiper-pagination-custom:nth-child(3).active:before{content:'E-SPORTS'}
    .swiper-pagination-game .swiper-pagination-custom:nth-child(4).active:before{content:'BOARD GAMES'}
    .swiper-pagination-game .swiper-pagination-custom:nth-child(5).active:before{content:'LOTTERY'}
    .swiper-pagination-game .swiper-pagination-custom:nth-child(6).active:before{content:'SLOT GAME'}
    .swiper-pagination-game a{color: #000}
    .home_container .middle_content{overflow-y: scroll;-webkit-overflow-scrolling: touch;
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        flex: 1;}
    .hb_btn{position:absolute;display:inline-block;width: 5rem;height: 5rem;background: url(/images/hongbao/new/hb_icon.gif) center no-repeat;background-size: 100%;left: 0;z-index: 2;bottom: 4rem;}
</style>

<div class="lb_gg">
    <!-- 轮播图 区域-->
    <div class="carousel swiper-container">
        <div class="swiper-wrapper">

        </div>
        <!-- 如果需要分页器 -->
    <!-- <div class="swiper-pagination">

        </div>-->
    </div>
    <!-- 红包 -->
    <?php
        if($af_aRedPocketset){
            echo '<a href="/'.TPL_NAME.'promo.php?type=packet" class="hb_btn"></a>';
        }
    ?>

    <!-- 滚动公告 -->
    <div class="notice index_notice" onclick="javascript:void(0);location.href='<?php echo TPL_NAME;?>moremessage.php'">
        <div class="notice-cont">
                             <span class="notice-icon">
                                 <i class="fa fa-volume-down"></i>
                             </span>
            <div class="text">
                <marquee>
                    <?php echo getScrollMsg();?>
                </marquee>
            </div>

        </div>
    </div>

</div>
<!-- 钱包中心 -->
<div class="login_box">
    <div class="login_box_sec">
        <div class="login_title">
            <span><?php if($username=='' || !$username){echo '未登录'; }else{ echo $username;} ?></span>
            <div class="wallet_center">
            <?php
                if(!$uid){
                    echo '<div class="wallet" onclick="window.location.href=\''.TPL_NAME.'login.php\'"><span>请先登录 </span><i class="right qbzx_fa login_icon"></i></div>';
                }else{
                    echo '<div class="wallet">中心钱包： <span class="font_Library"><i>¥</i><span class="hg_money">0.00</span> </span></div> ';
                }
            ?>
            </div>

        </div>
        <div class="management">

            <ul class="financial">
                <li onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/deposit_one.php\',\'\',\''.$oid.'\')';?>">
                    <i class="qbzx_fa fa-deposit-card"></i>
                    <span>存款</span>
                </li>
                <li onclick="ifHasLogin('<?php echo TPL_NAME;?>tran.php','','<?php echo $oid?>')">
                    <i class="qbzx_fa fa-zz"></i>
                    <span>转账</span>
                </li>
                <li onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/withdraw.php\',\'\',\''.$oid.'\')';?>">
                    <i class="qbzx_fa fa-withdrow"></i>
                    <span>取款</span>
                </li>
                <li onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/mset_bank.php\',\'\',\''.$oid.'\')';?>">
                    <i class="qbzx_fa fa-yhk"></i>
                    <span>银行卡</span>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="game-page-all">
    <div class="game_nav_on"></div>
    <!-- 如果需要分页器 -->
    <div class="swiper-pagination-game swiper-pagination-custom">
        <li class="swiper-pagination-custom active"><a >体育赛事</a></li>
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
                    <a href="javascript:;" onclick="ifHasLogin('/thunfire/fire_api.php?action=getLaunchGameUrl','win','<?php echo $oid?>')" >
                        <span class="game-lh-logo"></span> <p class="num lhdjNum"> </p> </a>
                </div>
                <div class="Menual">
                    <a href="javascript:;" onclick="ifHasLogin('/avia/avia_api.php?action=getLaunchGameUrl','win','<?php echo $oid?>')" >
                        <span class="game-dianjing-logo"></span> <p class="num fydjNum"> </p> </a>
                </div>
            </div>
            <div class="listType" id="li_lottery">
                <div class="Menual">
                    <a onclick="ifHasLogin('<?php echo $cpUrl;?>','win','<?php echo $oid?>')" >
                        <span class="game-lottery-logo"></span>
                        <p class="num lotteryChessNum"> </p>
                    </a>
                </div>
            </div>
            <div class="listType" id="li_chess">
                <?php
                    if(($flage && $flage=='test')){
                        $vg_rul = 'https://sw.vgvip88.com'; // 试玩链接
                        //$kl_rul = 'http://tstqpint.z389.com/?c=default&a=playGame&gameId=100'; // 试玩链接
                        $ly_rul = 'https://demo.leg666.com'; // 试玩链接
                        $ky_rul = 'http://play.ky206.com/jump.do'; // 试玩链接
                    }else{
                        $vg_rul = '/vgqp/';
                        $kl_rul = '/klqp/';
                        $ly_rul = '/lyqp/';
                        $ky_rul = '/ky/';
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

               <!-- <div class="Menual">
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
