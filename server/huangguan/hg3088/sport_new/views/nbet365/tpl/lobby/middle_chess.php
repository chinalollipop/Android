<?php
session_start();

include "../../../../app/member/include/config.inc.php";

$uid = $_SESSION['Oid'];
$kytesturl = 'http://play.ky206.com/jump.do' ; // 开元试玩链接
$lytesturl = $_SESSION['LYTEST_PLAY_SESSION']; // 乐游试玩链接
$testuid = '3e3d444a6054eae7c22cra8' ;

?>
<style>

    #rightsidebar { width:100%;}
    #sidebarbox{ width:1060px;}
    #new_vgbox{width:1000px;height:auto;padding-bottom:20px;padding-top:20px;margin:0 auto;overflow:hidden}
    #vg_banner{width:1000px;height:132px;margin-bottom:10px}
    #vgbox_ul li{float:left;width:327px;height:214px;margin-top:0;margin:0px;margin-left:5px;margin-top:5px;position:relative;-webkit-transition:all ease 0.2s;-moz-transition:all ease 0.2s;-o-transition:all ease 0.2s;transition:all ease 0.2s}
    #vgbox_ul li a{top:175px;left:218px;display:inline-block;position:absolute;width:109px;height:37px}
    #vgbox_ul li a.playbtn{background:url(<?php echo TPL_NAME;?>/images/lottery/btn_play.jpg) no-repeat}
    #vgbox_ul li a.jqqdbtn{background:#f00}
    #vgbox_ul li a.tg_sw{left:105px;background:url(<?php echo TPL_NAME;?>/images/lottery/btn_free.jpg) no-repeat}
    #vgbox_ul li a.playbtn_2{background:url(<?php echo TPL_NAME;?>/images/lottery/btn_play.jpg) no-repeat;left:218px}
    #vgbox_ul li a:hover{background-position:0 -2px}
    .tg_content{float:left;position:relative;width:242px;height:256px;margin-left:8px;margin-top:3px;overflow:hidden;display:inline-block}
    .light{cursor:pointer;position:absolute;left:-403px;top:0;width:242px;height:232px;background:-webkit-linear-gradient(0deg,rgba(255,255,255,0),rgba(255,255,255,0.3),rgba(255,255,255,0));background:-o-linear-gradient(0deg,rgba(255,255,255,0),rgba(255,255,255,0.3),rgba(255,255,255,0));background:-moz-linear-gradient(0deg,rgba(255,255,255,0),rgba(255,255,255,0.3),rgba(255,255,255,0));background:linear-gradient(0deg,rgba(255,255,255,0),rgba(255,255,255,0.5),rgba(255,255,255,0));transform:skew(25deg);-o-transform:skewx(-25deg);-moz-transform:skewx(-25deg);-webkit-transform:skewx(-25deg)}
    .tg_content:hover .light{left:403px;transition:1s;-moz-transition:1s;-o-transition:1s;-webkit-transition:1s}
    .tg_content .dla a{width:109px;height:37px;display:block;margin-top:70px;margin-left:200px}
    .tg_content:hover .dl{width:242px;height:166px;position:absolute;top:45px;background:url(<?php echo TPL_NAME;?>/images/chess/mb.png) top}
    .tg_content .dl a{width:109px;height:37px;display:block;margin-top:70px;margin-left:70px}
    .tg_w4{width:100px;height:90px;overflow:hidden;position:absolute;top:0px;left:0px}
    .tg_w4 .buy-icno{position:absolute;left:20px;margin-right:2px;z-index:5}
    .tg_w4 .buy-icno{-webkit-animation:transform 2.5s linear infinite forwards;-moz-animation:transform 2.5s linear infinite forwards;-ms-animation:transform 2.5s linear infinite forwards;animation:transform 2.5s linear infinite forwards}
    @-webkit-keyframes transform{0%{transform-origin:top center;-webkit-transform:rotate(0deg);transform:rotate(0deg)}
        25%{transform-origin:top center;-webkit-transform:rotate(20deg);transform:rotate(20deg)}
        50%{transform-origin:top center;-webkit-transform:rotate(0deg);transform:rotate(0deg)}
        75%{transform-origin:top center;-webkit-transform:rotate(-20deg);transform:rotate(-20deg)}
        100%{transform-origin:top center;-webkit-transform:rotate(0deg);transform:rotate(0deg)}
    }@keyframes transform{0%{-webkit-transform-origin:top center;transform-origin:top center;-webkit-transform:rotate(0deg);transform:rotate(0deg)}
         25%{-webkit-transform-origin:top center;transform-origin:top center;-webkit-transform:rotate(20deg);transform:rotate(20deg)}
         50%{-webkit-transform-origin:top center;transform-origin:top center;-webkit-transform:rotate(0deg);transform:rotate(0deg)}
         75%{-webkit-transform-origin:top center;transform-origin:top center;-webkit-transform:rotate(-20deg);transform:rotate(-20deg)}
         100%{-webkit-transform-origin:top center;transform-origin:top center;-webkit-transform:rotate(0deg);transform:rotate(0deg)}
     }.className{position:absolute;top:45px;left:40px}
    .className img{-moz-animation:heart_beat .5s ease-in-out;-webkit-animation:heart_beat .5s ease-in-out}
    @-moz-keyframes heart_beat{0%{-moz-transform:scale(1)}
        25%{-moz-transform:scale(1.70)}
        50%{-moz-transform:scale(0.9)}
        75%{-moz-transform:scale(1.55)}
        100%{-moz-transform:scale(1)}
    }@-webkit-keyframes heart_beat{0%{-webkit-transform:scale(1)}
         25%{-webkit-transform:scale(1.70)}
         50%{-webkit-transform:scale(0.9)}
         75%{-webkit-transform:scale(1.55)}
         100%{-moz-transform:scale(1)}
     }.classNamea{-webkit-animation:twinkling 5s infinite ease-in-out;position:absolute;top:55px;left:0px}
    .classNamec{-webkit-animation:twinkling 5s infinite ease-in-out;position:absolute;top:55px;left:0px;z-index:1}
    @-webkit-keyframes twinkling{0%{transform:translateX(0px)}
        50%{transform:translateX(30px)}
        100%{transform:translateX(0px)}
    }@keyframes twinkling{0%{transform:translateX(0px)}
         50%{transform:translateX(20px)}
         100%{transform:translateX(0px)}
     }.masked{width:240px;height:45px;top:211px;position:absolute;display:block;line-height:45px;text-align:center;background-image:-webkit-linear-gradient(left,#3498db,#f47920 10%,#d71345 20%,#f7acbc 30%,#ffd400 40%,#3498db 50%,#f47920 60%,#d71345 70%,#f7acbc 80%,#ffd400 90%,#3498db);color:transparent;-webkit-text-fill-color:transparent;-webkit-background-clip:text;background-size:200% 100%;animation:masked-animation 4s infinite linear}
    @keyframes masked-animation{0%{background-position:0 0}
        100%{background-position:-100% 0}
    }
</style>

<div class="chess_main">
    <div id="new-banner" style="background:url(<?php echo TPL_NAME;?>images/nav_qp.jpg) no-repeat center top; height:213px;">
    </div>

    <div id="sidebarwrap">
        <div id="sidebarbox">
            <div id="rightsidebar">
                <div id="new_vgbox">
                    <div class="tg_content">
                        <div class="tg_w4"><img class="classNamec" src="<?php echo TPL_NAME;?>/images/chess/new.png">  </div>
                        <img src="<?php echo TPL_NAME;?>/images/chess/vg.png">
                        <div class="classNamea"><img src="<?php echo TPL_NAME;?>/images/chess/vg_1.png">  </div>
                        <div class="className">  <img src="<?php echo TPL_NAME;?>/images/chess/vg_g.png?v=1">  </div>
                        <div class="masked">无需等待，随来随开</div> <div class="light"></div> <div class="dl">
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')"><img src="<?php echo TPL_NAME;?>/images/lottery/btn_play.jpg"></a>
                        </div>
                    </div>

                    <div class="tg_content">  <div class="tg_w4"><img class="classNamec" src="<?php echo TPL_NAME;?>/images/lottery/hot.png">  </div>  <img src="<?php echo TPL_NAME;?>/images/chess/ky.png">  <div class="classNamea"><img src="<?php echo TPL_NAME;?>/images/chess/ky_1.png">  </div>  <div class="className">  <img src="<?php echo TPL_NAME;?>/images/chess/ky_g.png?v=1">  </div>  <div class="masked">多种玩法，精彩刺激！</div> <div class="light"></div> <div class="dl">
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')"><img src="<?php echo TPL_NAME;?>/images/lottery/btn_play.jpg"></a>
                        </div></div>
                    <div class="tg_content">  <div class="tg_w4"><img class="classNamec" src="<?php echo TPL_NAME;?>/images/lottery/rd.png">  </div>  <img src="<?php echo TPL_NAME;?>/images/chess/le.png">  <div class="classNamea"><img src="<?php echo TPL_NAME;?>/images/chess/le_1.png">  </div>  <div class="className">  <img src="<?php echo TPL_NAME;?>/images/chess/le_g.png?v=1">  </div>  <div class="masked">有趣刺激，带给玩家无穷的乐趣</div> <div class="light"></div> <div class="dl">
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')"><img src="<?php echo TPL_NAME;?>/images/lottery/btn_play.jpg"></a></div></div>
                    <div class="tg_content">  <div class="tg_w4"><img class="classNamec" src="<?php echo TPL_NAME;?>/images/chess/new.png">  </div>  <img src="<?php echo TPL_NAME;?>/images/chess/kl.png">  <div class="classNamea"><img src="<?php echo TPL_NAME;?>/images/chess/fp_1.png">  </div>  <div class="className">  <img src="<?php echo TPL_NAME;?>/images/chess/fp_g.png?v=1">  </div>  <div class="masked">体验勇夺冠军的喜悦</div> <div class="light"></div> <div class="dl">
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/klqp/index.php?uid=<?php echo $uid;?>')"><img src="<?php echo TPL_NAME;?>/images/lottery/btn_play.jpg"></a>
                        </div>
                    </div>

                    </div>

                </div>

            </div>
        </div>
    </div>


</div>



<script type="text/javascript">
    $(function () {


    })
</script>