<?php
session_start();

$uid = $_SESSION['Oid'];
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$kytesturl = 'http://play.ky206.com/jump.do' ; // 开元试玩链接
$lytesturl = $_SESSION['LYTEST_PLAY_SESSION']; // 乐游试玩链接
$testuid = '3e3d444a6054eae7c22cra8' ;

?>
<style>
    /* 棋牌游戏 */
    .swiper-wrapper {height: 200px;}
    .chess_content_box{position:absolute;top:10px;width:1200px;left:50%;margin-left:-600px;z-index: 1}
    .chess_content_box .content_box_left{width:500px}
    .chess_content_box .chessLogo{width:430px;margin-top:110px}
    .chess_content_box .chessText{font-size:26px;margin:25px 0;line-height:40px;color:#616161}
    .chess_content_box .smallIcon li{float:left;margin-right:10px}
    .chess_content_box .smallIcon li:last-child{margin-right:0}
    .chess_content_box .smallIcon img{width:62px}
    .chess_content_box a{transition:.4s;display:inline-block;width:120px;height:35px;line-height:35px;font-size:16px;text-align:center;border-radius:5px !important;margin:40px 10px}
    .chess_content_box a:hover{transform:translateY(10px)}
    .chess_content_box .content_box_right{position:relative;width:700px;height:640px}
    .chess_content_box .content_box_right>div{position:absolute}
    .chess_content_box .content_box_right .chess_rw{animation:right-left-move 12s -2s infinite alternate both}
    .chess_content_box .content_box_right .chess_icon_1{animation:right-pig-move 8s infinite alternate both}
    .chess_content_box .content_box_right .chess_icon_2{animation:left-pig-move 10s 1.6s infinite alternate both}
    .chess_content_box .content_box_right .chess_icon_3{animation:right-small-move 7s infinite alternate both}
    /* vg */
    .chess_content_box .content_box_right.vg .chess_rw{width:700px;height:640px;background:url(<?php echo $tplNmaeSession;?>images/chess/vg/chess-boy.png) bottom right no-repeat}
    .chess_content_box .content_box_right.vg .chess_icon_1{width:250px;height:289px;background:url(<?php echo $tplNmaeSession;?>images/chess/vg/zp.png) no-repeat;bottom:160px;left:-35px}
    .chess_content_box .content_box_right.vg .chess_icon_2{width:55px;height:90px;background:url(<?php echo $tplNmaeSession;?>images/chess/vg/a1.png) no-repeat;top:40px;right:190px}
    .chess_content_box .content_box_right.vg .chess_icon_3{width:160px;height:150px;background:url(<?php echo $tplNmaeSession;?>images/chess/vg/chess-k.png) no-repeat;bottom:90px;right:0}

    /* 开元 */
    .chess_content_box .content_box_right.ky .chess_lp{width:480px;height:480px;background:url(<?php echo $tplNmaeSession;?>images/chess/ky/chess_yuan.png) bottom right no-repeat;bottom:50px;left:120px}
    .chess_content_box .content_box_right.ky .chess_rw{width:700px;height:640px;background:url(<?php echo $tplNmaeSession;?>images/chess/ky/chess_girl.png) bottom center no-repeat;background-size:65%}
    .chess_content_box .content_box_right.ky .chess_icon_1{width:200px;height:320px;background:url(<?php echo $tplNmaeSession;?>images/chess/ky/chess_icon2.png) no-repeat;bottom:0;left:55px}
    .chess_content_box .content_box_right.ky .chess_icon_2{width:520px;height:460px;background:url(<?php echo $tplNmaeSession;?>images/chess/ky/chess_bgicon.png) no-repeat;top:50px;right:90px}
    .chess_content_box .content_box_right.ky .chess_icon_3{width:205px;height:236px;background:url(<?php echo $tplNmaeSession;?>images/chess/ky/chess_icon1.png) no-repeat;bottom:90px;right:-60px}
    /* 皇冠 */
    .chess_content_box .content_box_right.hg .chess_lp{width:665px;height:205px;background:url(<?php echo $tplNmaeSession;?>images/chess/hg/tkb-icon4.png) bottom right no-repeat;bottom:0;left:120px}
    .chess_content_box .content_box_right.hg .chess_rw{width:700px;height:640px;background:url(<?php echo $tplNmaeSession;?>images/chess/hg/tkb-icon1.png) center right no-repeat;right:70px;bottom:-25px;z-index:1}
    .chess_content_box .content_box_right.hg .chess_icon_1{width:245px;height:245px;background:url(<?php echo $tplNmaeSession;?>images/chess/hg/tkb-icon3.png) no-repeat;top:220px;left:130px}
    .chess_content_box .content_box_right.hg .chess_icon_2{width:105px;height:135px;background:url(<?php echo $tplNmaeSession;?>images/chess/hg/tkb-icon2.png) no-repeat;top:80px;left:220px}
    .chess_content_box .content_box_right.hg .chess_icon_3{width:317px;height:176px;background:url(<?php echo $tplNmaeSession;?>images/chess/hg/tkb-icon6.png) no-repeat;bottom:90px;right:-103px}

    /* 乐游 */
    .chess_content_box .content_box_right.ly .chess_lp{width:900px;height:200px;background:url(<?php echo $tplNmaeSession;?>images/chess/ly/qian.png) bottom right no-repeat;bottom:-80px;left:-115px}
    .chess_content_box .content_box_right.ly .chess_rw{width:700px;height:640px;background:url(<?php echo $tplNmaeSession;?>images/chess/ly/rw.png) bottom right no-repeat;right:70px;bottom:45px;z-index:1}
    .chess_content_box .content_box_right.ly .chess_icon_1{width:230px;height:230px;background:url(<?php echo $tplNmaeSession;?>images/chess/ly/money.png) no-repeat;bottom:50px;left:0;background-size:50%}
    .chess_content_box .content_box_right.ly .chess_icon_2{width:200px;height:288px;background:url(<?php echo $tplNmaeSession;?>images/chess/ly/zhuanshi.png) no-repeat;background-size:50%;top:50px;left:105px}
    .chess_content_box .content_box_right.ly .chess_icon_3{width:150px;height:213px;background:url(<?php echo $tplNmaeSession;?>images/chess/ly/ma.png) no-repeat;background-size:80%;top:85px;right:120px}
    .chess_main .chess_bg{width:100%;height:680px;background:#ebebeb;background:linear-gradient(to bottom,#ebebeb,#dfdfe0)}
    .chess_main .chess_fs_bg{position:absolute;width:100%;top:0;height:650px;background:url(<?php echo $tplNmaeSession;?>images/chess/bg.png) center no-repeat;transform:scale(.65);animation:bg-animate 10s 2s linear infinite both paused}
    .chess_main .swiper-slide-active .chess_fs_bg{animation-play-state:running}
    .chess_main .chess_bg:before,.chess_main .chess_bg:after{position:absolute;display:inline-block;content:'';bottom:30px}
    .chess_main .chess_bg:before{width:380px;height:300px;background:url(<?php echo $tplNmaeSession;?>images/chess/chess_left.png) no-repeat;left:0}
    .chess_main .chess_bg:after{width:360px;height:300px;background:url(<?php echo $tplNmaeSession;?>images/chess/chess_right.png) no-repeat;right:0}
    .chess_main .chessList{margin:30px auto}
    .chess_main .chessList li{width:25%;float:left;transition:all 0.3s}
    .chess_main .chessList li:hover{transform:translateY(-10px)}
    .chess_main .chessList li:hover .chessText h1,.chess_main .chessList li:hover .chessText p{color:#f39800}
    .chess_main .chessList li .chessText{margin-left:15px}
    .chess_main .chessList li .chessText h1{color:#616161;font-size:16px;font-weight:normal}
    .chess_main .chessList li .chessText p{color:#616161;font-size:13px;margin-top:10px}
    .chess .bd li .wrap{position:relative}
</style>

<div class="chess_main">
    <div class="swiper-container" >
        <div class="swiper-wrapper">
            <!-- vg 棋牌 -->
            <div class="swiper-slide" >
               <div class="chess_bg"> </div>
               <div class="chess_fs_bg"> </div>
                <div class="chess_content_box">

                        <div class="fl content_box_left">
                            <img class="chessLogo" src="<?php echo $tplNmaeSession;?>images/chess/vg/title.png" alt="">
                            <p class="chessText">万人游戏房间，玩家在线斗智斗勇，将刺激与快乐进行到底！</p>
                            <ul class="smallIcon clearfix">
                                <li><img src="<?php echo $tplNmaeSession;?>images/chess/vg/icon1.png" alt=""></li>
                                <li><img src="<?php echo $tplNmaeSession;?>images/chess/vg/icon2.png" alt=""></li>
                                <li><img src="<?php echo $tplNmaeSession;?>images/chess/vg/icon3.png" alt=""></li>
                                <li><img src="<?php echo $tplNmaeSession;?>images/chess/vg/icon4.png" alt=""></li>
                                <li><img src="<?php echo $tplNmaeSession;?>images/chess/vg/icon5.png" alt=""></li>
                                <li><img src="<?php echo $tplNmaeSession;?>images/chess/vg/icon6.png" alt=""></li>
                                <li><img src="<?php echo $tplNmaeSession;?>images/chess/vg/icon7.png" alt=""></li>
                            </ul>

                            <a class="btn_game" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')" >立即游戏</a>
                            <a class="btn_game" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?flag=test&uid=<?php echo $uid;?>')">免费试玩</a>
                        </div>
                       <div class="fr content_box_right vg">
                            <div class="chess_rw"></div>
                            <div class="chess_icon_1"></div>
                            <div class="chess_icon_2"></div>
                            <div class="chess_icon_3"></div>
                       </div>


                </div>
            </div>

            <!-- 开元棋牌 -->
            <div class="swiper-slide" >
                <div class="chess_bg"> </div>
                <div class="chess_fs_bg"> </div>
                <div class="chess_content_box">

                    <div class="fl content_box_left">
                        <img class="chessLogo" src="<?php echo $tplNmaeSession;?>images/chess/ky/title.png" alt="">
                        <p class="chessText">业界最知名的棋牌游戏平台，多款主题游戏，更有惊天巨奖等您拿！</p>
                        <ul class="smallIcon clearfix">
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ky/icon1.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ky/icon2.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ky/icon3.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ky/icon4.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ky/icon5.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ky/icon6.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ky/icon7.png" alt=""></li>
                        </ul>

                        <a class="btn_game" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')"　>立即游戏</a>
                        <a class="btn_game" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $kytesturl;?>')">免费试玩</a>
                    </div>
                    <div class="fr content_box_right ky">
                        <div class="chess_lp"></div>
                        <div class="chess_rw"></div>
                        <div class="chess_icon_1"></div>
                        <div class="chess_icon_2"></div>
                        <div class="chess_icon_3"></div>
                    </div>


                </div>
            </div>

            <!-- 皇冠棋牌 -->
            <div class="swiper-slide" >
                <div class="chess_bg"> </div>
                <div class="chess_fs_bg"> </div>
                <div class="chess_content_box">

                    <div class="fl content_box_left">
                        <img class="chessLogo" src="<?php echo $tplNmaeSession;?>images/chess/hg/title_kl.png" alt="">
                        <p class="chessText">快乐棋牌游戏独创欢乐场玩法，换牌，透视，如同赌神附体</p>
                        <ul class="smallIcon clearfix">
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/hg/icon1.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/hg/icon2.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/hg/icon3.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/hg/icon4.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/hg/icon5.png" alt=""></li>
                        </ul>

                        <a class="btn_game" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/klqp/index.php?uid=<?php echo $uid;?>')">立即游戏</a>
                        <!--<a class="btn_game" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?flag=test&uid=<?php /*echo $uid;*/?>')">免费试玩</a>-->
                    </div>
                    <div class="fr content_box_right hg">
                        <div class="chess_lp"></div>
                        <div class="chess_rw"></div>
                        <div class="chess_icon_1"></div>
                        <div class="chess_icon_2"></div>
                        <div class="chess_icon_3"></div>
                    </div>


                </div>
            </div>

            <!-- 乐游棋牌 -->
            <div class="swiper-slide" >
                <div class="chess_bg"> </div>
                <div class="chess_fs_bg"> </div>
                <div class="chess_content_box">

                    <div class="fl content_box_left">
                        <img class="chessLogo" src="<?php echo $tplNmaeSession;?>images/chess/ly/title.png" alt="">
                        <p class="chessText">真打造了如德州扑克、斗地主、扎金花、抢庄牛牛等数十款经典的棋牌类游戏</p>
                        <ul class="smallIcon clearfix">
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ly/icon1.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ly/icon2.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ly/icon3.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ly/icon4.png" alt=""></li>
                            <li><img src="<?php echo $tplNmaeSession;?>images/chess/ly/icon5.png" alt=""></li>
                        </ul>

                        <a class="btn_game" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')">立即游戏</a>
                        <a class="btn_game" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $lytesturl;?>')">免费试玩</a>
                    </div>
                    <div class="fr content_box_right ly">
                        <div class="chess_lp"></div>
                        <div class="chess_rw"></div>
                        <div class="chess_icon_1"></div>
                        <div class="chess_icon_2"></div>
                        <div class="chess_icon_3"></div>
                    </div>


                </div>
            </div>

        </div>
        <!-- 如果需要分页器 -->
        <div class="swiper-pagination" style="display: none;"></div>

    </div>

    <div class="w_1200">
        <ul class="chessList clearfix">
            <li class="on">
                <a href="javascript:;">
                    <div class="fl">
                        <img src="<?php echo $tplNmaeSession;?>images/chess/vgqp.png" alt="">
                    </div>
                    <div class="chessText fl">
                        <h1>VG棋牌</h1>
                        <p>绿色公平公正的对战模式</p>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="javascript:;">
                    <div class="fl">
                        <img src="<?php echo $tplNmaeSession;?>images/chess/kyqp.png" alt="">
                    </div>
                    <div class="chessText fl">
                        <h1>开元棋牌</h1>
                        <p>体验最火爆的热门棋牌玩法</p>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="javascript:;">
                    <div class="fl">
                        <img src="<?php echo $tplNmaeSession;?>images/chess/hgqp.png" alt="">
                    </div>
                    <div class="chessText fl">
                        <h1>快乐棋牌</h1>
                        <p>全球上万牌友在线切磋</p>
                    </div>
                </a>
            </li>
            <li >
                <a href="javascript:;">
                    <div class="fl">
                        <img src="<?php echo $tplNmaeSession;?>images/chess/lyqp.png" alt="">
                    </div>
                    <div class="chessText fl">
                        <h1>乐游棋牌</h1>
                        <p>各种最经典最新玩法的棋牌</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>

</div>



<script type="text/javascript">
    $(function () {
        chessBanner();

        // 轮播
        function chessBanner() {
            var chessSwiper = new Swiper(".swiper-container",{
                autoplay : 500000,
                loop:true,
                effect: 'fade',
                prevButton:'.swiper-button-prev',
                nextButton:'.swiper-button-next',
                autoHeight: true,
                // 如果需要分页器
                pagination: '.swiper-pagination',
                paginationClickable :true, // 点击分页切换
                autoplayDisableOnInteraction : false, // 点击切换后是否自动播放 (默认true 不播放)

            });
            // 切换游戏
            $('.chessList li').off().hover(function () {
                var index = $(this).index();
                $('.swiper-pagination').find('.swiper-pagination-bullet').eq(index).click();
            })

        }



    })
</script>