<?php
session_start();

include "../../../../app/member/include/config.inc.php";

//  单页面维护功能
checkMaintain('lottery');

$cpUrl = $_SESSION['LotteryUrl'];
$uid = $_SESSION['Oid']; // 判断是否已登录


?>
<style>
    .cp_content{width:1000px;height:492px;margin:0 auto;background:url(<?php echo TPL_NAME;?>/images/lottery/bg.png)}
    .tgc_content{position:relative;width:442px;height:216px;padding-top:20px;overflow:hidden;display:inline-block;float:left}
    .tgc_light{cursor:pointer;position:absolute;left:-463px;top:0;width:412px;height:232px;background:-webkit-linear-gradient(0deg,rgba(255,255,255,0),rgba(255,255,255,0.3),rgba(255,255,255,0));background:-o-linear-gradient(0deg,rgba(255,255,255,0),rgba(255,255,255,0.3),rgba(255,255,255,0));background:-moz-linear-gradient(0deg,rgba(255,255,255,0),rgba(255,255,255,0.3),rgba(255,255,255,0));background:linear-gradient(0deg,rgba(255,255,255,0),rgba(255,255,255,0.5),rgba(255,255,255,0));transform:skew(25deg);-o-transform:skewx(-25deg);-moz-transform:skewx(-25deg);-webkit-transform:skewx(-25deg)}
    .tgc_content:hover .tgc_light{left:503px;transition:1s;-moz-transition:1s;-o-transition:1s;-webkit-transition:1s}
    .tgc_content:hover .tgc_dl{width:417px;height:179px;position:absolute;top:50px;background:url(<?php echo TPL_NAME;?>/images/lottery/mb.png) top}
    .tgc_content .tgc_dl a{width:109px;height:37px;display:block;margin-top:70px;margin-left:170px}
    .tgc_w4{width:100px;height:90px;overflow:hidden;position:absolute;top:0px;left:10px}
    .tgc_w4 .buy-icno{position:absolute;left:20px;margin-right:2px;z-index:5}
    .tgc_w4 .buy-icno{-webkit-animation:transform 2.5s linear infinite forwards;-moz-animation:transform 2.5s linear infinite forwards;-ms-animation:transform 2.5s linear infinite forwards;animation:transform 2.5s linear infinite forwards}
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
     }@-moz-keyframes heart_beat{0%{-moz-transform:scale(1)}
          25%{-moz-transform:scale(1.70)}
          50%{-moz-transform:scale(0.9)}
          75%{-moz-transform:scale(1.55)}
          100%{-moz-transform:scale(1)}
      }@-webkit-keyframes heart_beat{0%{-webkit-transform:scale(1)}
           25%{-webkit-transform:scale(1.70)}
           50%{-webkit-transform:scale(0.9)}
           75%{-webkit-transform:scale(1.55)}
           100%{-moz-transform:scale(1)}
       }.cp_role{-webkit-animation:twinkling 5s infinite ease-in-out;position:absolute;top:0px;left:0px}
    .cp_tag{-webkit-animation:twinkling 5s infinite ease-in-out;position:absolute;top:55px;left:0px;z-index:1}
    @-webkit-keyframes twinkling{0%{transform:translateX(0px)}
        50%{transform:translateX(30px)}
        100%{transform:translateX(0px)}
    }@keyframes twinkling{0%{transform:translateX(0px)}
         50%{transform:translateX(20px)}
         100%{transform:translateX(0px)}
     }.masked{width:240px;height:45px;top:141px;left:0px;position:absolute;display:block;line-height:45px;text-align:center;font-size:22px;font-weight:bold;background-image:-webkit-linear-gradient(left,#3498db,#f47920 10%,#d71345 20%,#f7acbc 30%,#ffd400 40%,#3498db 50%,#f47920 60%,#d71345 70%,#f7acbc 80%,#ffd400 90%,#3498db);color:transparent;-webkit-text-fill-color:transparent;-webkit-background-clip:text;background-size:200% 100%;animation:masked-animation 4s infinite linear}
    @keyframes masked-animation{0%{background-position:0 0}
        100%{background-position:-100% 0}
    }

    #rightsidebar { width:100%;}
    #sidebarbox{ width:1060px;}
</style>

<div class="lottery_main">
    <div id="new-banner">
        <div id="new-banner-box">
            <div id="banner"><img src="<?php echo TPL_NAME;?>images/live/6.jpg"></div>
            <div class="msg-connet">

                <div class="left" style="margin-lefT:8px;">
                    <div><a href="javascript:;" class="to_lives ylc_top"></a></div>
                    <div> <a href="javascript:;" class="to_lives ylc_left"></a>
                        <a href="javascript:;" class="to_lives ylc_right"></a> </div>
                </div>

            </div>
        </div>
    </div>

    <div id="sidebarwrap">
        <div id="sidebarbox">
            <div id="rightsidebar">
                <div class="cp_content">
                    <div class="tgc_content " style=" margin-left: 330px;">
                        <div class="tgc_w4">
                            <img class="cp_tag" src="<?php echo TPL_NAME;?>/images/lottery/hot.png">
                        </div>
                        <img src="<?php echo TPL_NAME;?>/images/lottery/tt.png?v=2">
                        <div class="cp_role"><img src="<?php echo TPL_NAME;?>/images/lottery/tt1.png"></div>
                        <div class="masked" style="left: 160px">官方玩法</div>
                        <div class="masked" style="font-size: 14px; top: 165px;left: 170px">最具创意及创新的合法平台</div>
                        <div class="tgc_light"></div>
                        <div class="tgc_dl">
                            <!--<a style="margin-top: 40px" href="javascript:;" class="to_lotterys_third" >-->
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')">
                                <img src="<?php echo TPL_NAME;?>/images/lottery/btn_play.jpg">
                            </a>
                        </div>
                    </div>
                    <div class="tgc_content " style="   margin-left: 80px;">
                        <div class="tgc_w4">
                            <img class="cp_tag" src="<?php echo TPL_NAME;?>/images/lottery/rd.png">
                        </div>
                        <img src="<?php echo TPL_NAME;?>/images/lottery/xy.png?v=2">
                        <div class="cp_role" style="left: 220px"><img src="<?php echo TPL_NAME;?>/images/lottery/xy1.png"></div>
                        <div class="masked">信用玩法</div>
                        <div class="masked" style="font-size: 14px; top: 165px">赔率高，开奖快</div>
                        <div class="tgc_light"></div>
                        <div class="tgc_dl">
                            <!--<a href="javascript:;" class="to_lotterys_third" data-to="1">-->
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')">
                                <img src="<?php echo TPL_NAME;?>/images/lottery/btn_play.jpg">
                            </a>
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