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
    .live_main{height:755px;background:url(<?php echo $tplNmaeSession;?>images/chess/chess_bg.jpg) no-repeat center;padding-top:45px}
    .live_main .live_left{position:relative;width: 720px;height: 670px;}
    .live_main .live_left>div{position:absolute}
    /* vg */
    .live_main .live_left .live_rw,.live_main .live_left .live_rw_1{z-index:1;width:570px;height:675px;background:url(<?php echo $tplNmaeSession;?>images/chess/vg_rw.png) no-repeat top center;background-size:100%;bottom:0px;left:40px}
    .live_main .live_left .live_rw_1{animation:amplification 4s infinite}
    .live_main .live_left .icon{width:89px;height:89px;transform:scale(.9);animation:go_up 5s infinite}
    .live_main .live_left .icon_1{background:url(<?php echo $tplNmaeSession;?>images/chess/vg_icon_1.png) no-repeat center;bottom:200px;left:5px}
    .live_main .live_left .icon_2{background:url(<?php echo $tplNmaeSession;?>images/chess/vg_icon_2.png) no-repeat center;top:110px;right:170px}
    .live_main .live_left .icon_3{background:url(<?php echo $tplNmaeSession;?>images/chess/vg_icon_3.png) no-repeat center;top:485px;right:145px}
    .live_main .live_right{text-align:center;padding-top:30px;width: 448px;margin-right: 30px;}
    .live_main .live_right_top {height: 85px;position: relative;z-index: 5;}
    .live_main .live_right_top a{transition:.3s;position: relative;display:inline-block;width:170px;height:68px;padding-right: 17px;line-height:70px;color:#626262;text-align:right;font-size:18px;background:#fff;background:linear-gradient(to bottom,#fff,#e0dddd);border-radius:10px;margin-left:15px;box-shadow: 0 7px 10px rgba(0, 0, 0, 0.2);}
    .live_main .live_right_top a.active{color:#fff;background:url(<?php echo $tplNmaeSession;?>images/live/hover.png) no-repeat center;box-shadow: none;}
    .live_main .live_right_top a:before{content: '';display: inline-block;position: absolute;width: 84px;height: 100%;left: 10px;background:url(<?php echo $tplNmaeSession;?>images/chess/nav.png?v=1) no-repeat;transform: scale(.7);}
    .live_main .live_right_top a:nth-child(2):before{background-position: -90px 0;}
    .live_main .live_right_top a:nth-child(3):before{background-position: -180px 0;}
    .live_main .live_right_top a:nth-child(4):before{background-position: -270px 0;}
    .live_main .live_right_top a.active:before{background-position-y: -66px;}
    .live_main .live_right p{height:37px;font-size:28px;color:#9f9e9d;margin:30px 0}
    .live_main .show_act{text-align: left;}
    .live_main .icon{width:385px;height:230px;}
    .live_main .vg_icon{background:url(<?php echo $tplNmaeSession;?>images/chess/vg_title.png) no-repeat center;}
    .live_main .live_icon{color:#626262;line-height:24px;margin:30px 0 0}
    .live_main .live_right .btn_game{text-align:center;transition:.3s;display:inline-block;width:140px;height:35px;line-height:35px;font-size:18px;border-radius:50px !important;margin:30px 30px 0 0}
    .live_main .live_right .btn_game:hover{transform:translateY(10px)}

    /* ky */
    .live_main .live_left.ky .live_rw,.live_main .live_left.ky .live_rw_1{background:url(<?php echo $tplNmaeSession;?>images/chess/ky_rw.png) no-repeat top center;background-size:84%;bottom:0;left:35px}
    .live_main .live_left.ky .icon{width:205px;height:181px}
    .live_main .live_left.ky .icon_1{background:url(<?php echo $tplNmaeSession;?>images/chess/ky_icon_1.png) no-repeat center;bottom:210px;left:-40px;animation:right-small-move 5s infinite alternate both}
    .live_main .live_left.ky .icon_2{background:url(<?php echo $tplNmaeSession;?>images/chess/ky_icon_2.png) no-repeat center;top:35px;right:60px}
    .live_main .live_left.ky .icon_3{background:url(<?php echo $tplNmaeSession;?>images/chess/ky_icon_3.png) no-repeat center;top:390px;right:100px}
    .live_main .ky_icon{background:url(<?php echo $tplNmaeSession;?>images/chess/ky_title.png) no-repeat center;}

    /* ly */
    .live_main .live_left.ly .live_rw,.live_main .live_left.ly .live_rw_1{background:url(<?php echo $tplNmaeSession;?>images/chess/ly_rw.png) no-repeat top center;background-size:95%;bottom:0;left:50px}
    .live_main .live_left.ly .icon{width:280px;height:276px}
    .live_main .live_left.ly .icon_1{background:url(<?php echo $tplNmaeSession;?>images/chess/ly_icon_1.png) no-repeat center;top:150px;left:-40px;animation:right-small-move 5s infinite alternate both}
    .live_main .live_left.ly .icon_2{background:url(<?php echo $tplNmaeSession;?>images/chess/ly_icon_2.png) no-repeat center;top:-70px;right:110px}
    .live_main .live_left.ly .icon_3{background:url(<?php echo $tplNmaeSession;?>images/chess/ly_icon_3.png) no-repeat center;top:395px;right:95px}
    .live_main .ly_icon{background:url(<?php echo $tplNmaeSession;?>images/chess/ly_title.png) no-repeat center;background-size: 100%;}

    /* kl */
    .live_main .live_left.kl .live_rw,.live_main .live_left.kl .live_rw_1{width: 715px;height: 650px;background:url(<?php echo $tplNmaeSession;?>images/chess/kl_rw.png) no-repeat top center;background-size:95%;bottom:0;left:0px}
    .live_main .live_left.kl .icon{width:280px;height:276px}
    .live_main .live_left.kl .icon_1{background:url(<?php echo $tplNmaeSession;?>images/chess/kl_icon_1.png) no-repeat center;top:150px;left:-100px;animation:right-small-move 5s infinite alternate both}
    .live_main .live_left.kl .icon_2{background:url(<?php echo $tplNmaeSession;?>images/chess/kl_icon_2.png) no-repeat center;top:60px;right:50px}
    .live_main .live_left.kl .icon_3{background:url(<?php echo $tplNmaeSession;?>images/chess/kl_icon_3.png) no-repeat center;top:395px;right:95px}
    .live_main .kl_icon{background:url(<?php echo $tplNmaeSession;?>images/chess/kl_title.png) no-repeat center;background-size: 100%;}

</style>

<div class="live_main">
    <div class="w_1200">
        <div class="w_1000">
            <div class="live_right_top gameChangeTab">
                <a href="javascript:;" class="active" data-to="vg"> VG棋牌 </a>
                <a href="javascript:;" data-to="ky"> KY棋牌 </a>
                <a href="javascript:;" data-to="ly"> LEG棋牌 </a>
                <a href="javascript:;" data-to="kl"> 快乐棋牌 </a>
            </div>
        </div>

        <!-- vg 棋牌 -->
        <div class="left live_left show_vg show_act">
            <div class="live_rw"></div>
            <div class="live_rw_1"></div>
            <div class="icon icon_1"></div>
            <div class="icon icon_2"></div>
            <div class="icon icon_3"></div>
        </div>
        <!-- ky 棋牌-->
        <div class="ky left live_left show_ky show_act hide">
            <div class="live_rw"></div>
            <div class="live_rw_1"></div>
            <div class="icon icon_1"></div>
            <div class="icon icon_2"></div>
            <div class="icon icon_3"></div>

        </div>
        <!-- ly 棋牌-->
        <div class="ly left live_left show_ly show_act hide">
            <div class="live_rw"></div>
            <div class="live_rw_1"></div>
            <div class="icon icon_1"></div>
            <div class="icon icon_2"></div>
            <div class="icon icon_3"></div>
        </div>

        <!-- 快乐棋牌-->
        <div class="kl left live_left show_kl show_act hide">
            <div class="live_rw"></div>
            <div class="live_rw_1"></div>
            <div class="icon icon_1"></div>
            <div class="icon icon_2"></div>
            <div class="icon icon_3"></div>
        </div>

        <div class="right live_right">
            <p>  </p> <!-- 高品质真实体验 让您身临其境 -->
            <!--  vg -->
            <div class="show_vg show_act">
                <div class="vg_icon icon"></div>
                <div class="live_icon">
                    VG棋牌是一款全新的棋牌游戏，延续了传统的游戏规则，<br>
                    新增多种趣味模式，引入棋牌美女对局设计，可玩性极高,<br>
                    同时拥有强大的防作弊系统和丰富的游戏礼包！
                </div>

                <a href="javascript:;" class="btn_game" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?flag=test&uid=<?php echo $uid;?>')">免费试玩</a>
                <a href="javascript:;" class="btn_game" title="立即游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')" >立即游戏</a>

            </div>
            <!-- ky -->
            <div class="ky show_ky show_act hide">
                <div class="ky_icon icon"></div>
                <div class="live_icon">
                    开元棋牌是一款真人在线竞技棋牌游戏，热门火爆经典玩法应有尽有，<br>
                    每一款都是精品，大家可随时在线轻松玩耍竞技，想玩就加入，超多<br>
                    游戏种类，等你来玩！
                </div>
                <a href="javascript:;" class="btn_game" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $kytesturl;?>')">免费试玩</a>
                <a href="javascript:;" class="btn_game" title="立即游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')"　>立即游戏</a>

            </div>
            <!-- ly -->
            <div class="ly show_ly show_act hide">
                <div class="ly_icon icon"></div>
                <div class="live_icon">
                    LEG棋牌是款比较具有代表性的棋牌游戏大厅,绚丽的游<br>
                    戏画面效果,给您完美的娱乐盛宴,百家乐、斗地主、炸金花、<br>
                    牛牛、麻将、跑得快等棋牌游戏一应俱全！
                </div>
                <a href="javascript:;" class="btn_game" title="免费试玩"  onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $lytesturl;?>')">免费试玩</a>
                <a href="javascript:;" class="btn_game" title="立即游戏"  onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')">立即游戏</a>

            </div>

            <!-- 快乐棋牌 -->
            <div class="kl show_kl show_act hide">
                <div class="kl_icon icon"></div>
                <div class="live_icon">
                    快乐棋牌是一款非常好玩的棋牌休闲手游平台。平台集合了<br>
                    多款热门棋牌游戏，真实玩家在线，实时联网，随机匹配<br>
                    入场参加对战，多种福利豪礼领不停!
                </div>

                <a href="javascript:;" class="btn_game" title="立即游戏"  onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/klqp/index.php?uid=<?php echo $uid;?>')">立即游戏</a>

            </div>

        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        changeGameTab();
    })
</script>