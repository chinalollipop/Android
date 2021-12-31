<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];

$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

?>
<style>
    .live_main{height:755px;background:url(<?php echo TPL_NAME;?>images/live/live_bg.jpg) no-repeat center;padding-top:45px}
    .live_main .live_left{position:relative;width: 720px;height: 670px;}
    .live_main .live_left>div{position:absolute}
    .live_main .live_left .live_rw,.live_main .live_left .live_rw_1{width:570px;height:624px;background:url(<?php echo TPL_NAME;?>images/live/ren.png) no-repeat top center;background-size:100%;bottom:0px;left:40px}
    .live_main .live_left .live_rw_1{animation:amplification 4s infinite}
    .live_main .live_left .icon{width:89px;height:89px;transform:scale(.9);animation:go_up 5s infinite}
    .live_main .live_left .icon_1{background:url(<?php echo TPL_NAME;?>images/live/ag_sz_1.png) no-repeat center;bottom:50px;left:110px}
    .live_main .live_left .icon_2{background:url(<?php echo TPL_NAME;?>images/live/ag_sz_2.png) no-repeat center;bottom:110px;right:20px}
    .live_main .live_left .icon_3{background:url(<?php echo TPL_NAME;?>images/live/ag_sz_3.png) no-repeat center;top:155px;right:80px}
    .live_main .live_left .icon_4{background:url(<?php echo TPL_NAME;?>images/live/ag_sz_4.png) no-repeat center;top:180px;left:85px}
    .live_main .live_left .live_zp{width:100px;height:81px;background:url(<?php echo TPL_NAME;?>images/live/puke.png) no-repeat center;background-size:100%;top:345px;right:30px;animation:left-pig-move 10s 1.6s infinite alternate both}
    .live_main .live_right{text-align:center;padding-top:30px;width: 398px;margin-left: 80px;}
    .live_main .live_right_top {height: 85px;position: relative;z-index: 5;}
    .live_main .live_right_top a{transition:.3s;position: relative;display:inline-block;width:170px;height:68px;padding-right: 17px;line-height:70px;color:#626262;text-align:right;font-size:18px;background:#fff;background:linear-gradient(to bottom,#fff,#e0dddd);border-radius:10px;margin-left:15px;box-shadow: 0 7px 10px rgba(0, 0, 0, 0.2);}
    .live_main .live_right_top a.active{color:#fff;background:url(<?php echo TPL_NAME;?>images/live/hover.png) no-repeat center;box-shadow: none;}
    .live_main .live_right_top a:before{content: '';display: inline-block;position: absolute;width: 84px;height: 100%;left: 10px;background:url(<?php echo TPL_NAME;?>images/live/nav.png) no-repeat;transform: scale(.85);}
    .live_main .live_right_top a:nth-child(2):before{background-position: -90px 0;}
    .live_main .live_right_top a:nth-child(3):before{background-position: -180px 0;}
    .live_main .live_right_top a:last-child{text-align: center;padding: 0 8.5px;}
    .live_main .live_right_top a:last-child:before{background: none;}
    .live_main .live_right_top a.active:before{background-position-y: -66px;}
    .live_main .live_right p{height:37px;font-size:28px;color:#9f9e9d;margin:30px 0}
    .live_main .show_act{text-align: left;}
    .live_main .icon{width:385px;height:223px;}
    .live_main .ag_icon{background:url(<?php echo TPL_NAME;?>images/live/ag.png) no-repeat center;}
    .live_main .live_icon{color:#626262;line-height:24px;margin:30px 0 0}
    .live_main .live_right .btn_game{text-align:center;transition:.3s;display:inline-block;width:140px;height:35px;line-height:35px;font-size:18px;border-radius:50px !important;margin:30px 30px 0 0}
    .live_main .live_right .btn_game:hover{transform:translateY(10px)}

    /* og */
    .live_main .live_left.og .live_rw,.live_main .live_left.og .live_rw_1{background:url(<?php echo TPL_NAME;?>images/live/ren_og.png) no-repeat top center;background-size:79%;bottom: 0;left: 145px;}
    .live_main .live_left.og .icon{width: 205px;height: 181px;}
    .live_main .live_left.og .icon_1{background:url(<?php echo TPL_NAME;?>images/live/og_1.png) no-repeat center;bottom:210px;left:auto;right:-60px;animation:right-small-move 5s infinite alternate both}
    .live_main .live_left.og .icon_2{background:url(<?php echo TPL_NAME;?>images/live/og_2.png) no-repeat center;bottom:110px;right:0;left:80px;}
    .live_main .live_left.og .icon_3{background:url(<?php echo TPL_NAME;?>images/live/og_3.png) no-repeat center;top:490px;right:50px}
    .live_main .live_left.og .icon_4{background:url(<?php echo TPL_NAME;?>images/live/og_4.png) no-repeat center;top:65px;left:auto;right:-10px}
    .live_main .live_left.og .icon_5{background:url(<?php echo TPL_NAME;?>images/live/og_5.png) no-repeat center;top:110px;left:60px}
    .live_main .og_icon{background:url(<?php echo TPL_NAME;?>images/live/og.png) no-repeat center;}

    /* bbin */
    .live_main .live_left.bbin .live_rw,.live_main .live_left.bbin .live_rw_1{background:url(<?php echo TPL_NAME;?>images/live/ren_bbin.png) no-repeat top center;background-size:71%;bottom: 0;left: 80px;}
    .live_main .live_left.bbin .icon{width: 280px;height: 276px;}
    .live_main .live_left.bbin .icon_1{background:url(<?php echo TPL_NAME;?>images/live/bbin_puke.png) no-repeat center;bottom:30px;left:auto;right:-35px;background-size:90%;animation:right-small-move 5s infinite alternate both}
    .live_main .live_left.bbin .icon_2{background:url(<?php echo TPL_NAME;?>images/live/bbin_q_1.png) no-repeat center;bottom:-75px;right:0;left:20px}
    .live_main .live_left.bbin .icon_3{background:url(<?php echo TPL_NAME;?>images/live/bbin_q_2.png) no-repeat center;top:40px;right:auto;left:10px}
    .live_main .live_left.bbin .icon_4{background:url(<?php echo TPL_NAME;?>images/live/bbin_sz_1.png) no-repeat center;top:65px;left:auto;right:0}
    .live_main .live_left.bbin .icon_5{background:url(<?php echo TPL_NAME;?>images/live/bbin_sz_2.png) no-repeat center;top:215px;left:10px}
    .live_main .bbin_icon{background:url(<?php echo TPL_NAME;?>images/live/bbin.png) no-repeat center;}


</style>

<div class="live_main">
    <div class="w_1200">
        <div class="w_1000">
            <div class="live_right_top gameChangeTab">
                <a href="javascript:;" class="active" data-to="ag"> AG视讯 </a>
                <a href="javascript:;" data-to="og"> OG视讯 </a>
                <a href="javascript:;" data-to="bbin"> BBIN视讯 </a>
                <a href="javascript:;" > 敬请期待 </a>
            </div>
        </div>

        <!-- ag -->
        <div class="right live_left show_ag show_act">
            <div class="live_rw"></div>
            <div class="live_rw_1"></div>
            <div class="live_zp"></div>
            <div class="icon icon_1"></div>
            <div class="icon icon_2"></div>
            <div class="icon icon_3"></div>
            <div class="icon icon_4"></div>
        </div>
        <!-- og -->
        <div class="og right live_left show_og show_act hide">
            <div class="live_rw"></div>
            <div class="live_rw_1"></div>
            <div class="icon icon_1"></div>
            <div class="icon icon_2"></div>
            <div class="icon icon_3"></div>
            <div class="icon icon_4"></div>
            <div class="icon icon_5"></div>

        </div>
        <!-- bbin -->
        <div class="bbin right live_left show_bbin show_act hide">
            <div class="live_rw"></div>
            <div class="live_rw_1"></div>
            <div class="icon icon_1"></div>
            <div class="icon icon_2"></div>
            <div class="icon icon_3"></div>
            <div class="icon icon_4"></div>
            <div class="icon icon_5"></div>
        </div>

        <div class="left live_right">
            <p>  </p> <!-- 高品质真实体验 让您身临其境 -->
            <!--  ag -->
            <div class="show_ag show_act">
                <div class="ag_icon icon"></div>
                <div class="live_icon">
                    AG视讯提供五彩缤纷的线上娱乐产品，在真人<br>
                    游戏的互动体验上，堪称业界佼佼者,可以在竞咪厅<br>
                    一边咪牌，桌边更有中文主播和你实时聊天互动！
                </div>
                <a href="javascript:;" class="btn_game" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>')">免费试玩</a>
                <a href="javascript:;" class="btn_game" title="立即游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')">立即游戏</a>
            </div>
            <!-- og -->
            <div class="og show_og show_act hide">
                <div class="og_icon icon"></div>
                <div class="live_icon">
                    OG视讯平台拥有丰富多样的游戏产品，同时为客户提供<br>
                    最新最全最正规的在线娱乐体验官方网站！
                </div>
                <!--<a href="javascript:;" class="btn_game" title="免费试玩" >免费试玩</a>-->
                <a href="javascript:;" class="btn_game" title="立即游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">立即游戏</a>
            </div>
            <!-- bbin -->
            <div class="bbin show_bbin show_act hide">
                <div class="bbin_icon icon"></div>
                <div class="live_icon">
                    BBIN真人视讯平台是亚洲知名的真人娱乐平台，<br>
                    真人荷官为您服务，确保游戏玩家随时随地进行游戏！
                </div>
                <a href="javascript:;" class="btn_game" title="立即游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')">立即游戏</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        indexCommonObj.getUserQpBanlance(uid,'ag') ;
        changeGameTab();

    })
</script>