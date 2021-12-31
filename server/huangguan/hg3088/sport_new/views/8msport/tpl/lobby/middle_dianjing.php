<?php
session_start();

include "../../../../app/member/include/config.inc.php";

$uid = $_SESSION['Oid'];
$testuid = '3e3d444a6054eae7c22cra8' ;
$aviaTryDomain = getSysConfig('avia_try_domain'); // 泛亚电竞试玩地址
$thunFireTryDomain = getSysConfig('thunfire_try_domain'); // 雷火电竞试玩地址

?>
<style>
    /* 电竞 */
    .page-dianjing{background:url(<?php echo TPL_NAME;?>images/dianjing/dianjing_bg.jpg) no-repeat center ;height:760px;position:relative}
    .page-dianjing .dj_top_img{position: relative;height: 100%;width: 880px}
    .page-dianjing .dj_top_img span{position: absolute;display: inline-block;}
    .page-dianjing .dj_top_img .dj_renwu_0,.page-dianjing .dj_top_img .dj_renwu_1{z-index: 3;width: 100%;height: 680px;background:url(<?php echo TPL_NAME;?>images/dianjing/icon_rw.png) no-repeat center ;background-size: 100%;}
    .page-dianjing .dj_top_img .dj_renwu_1{animation: amplification 4s infinite;}
    .page-dianjing .dj_top_img .dj_renwu_2{width: 320px;height: 190px;background:url(<?php echo TPL_NAME;?>images/dianjing/icon_1.png) no-repeat center ;background-size: 100%;left: 100px;top: 140px;animation: left-pig-move 10s 1.6s infinite alternate both;}
    .page-dianjing .dj_top_img .dj_renwu_3{width: 350px;height: 200px;background:url(<?php echo TPL_NAME;?>images/dianjing/icon_2.png) no-repeat center ;background-size: 100%;left: 295px;top: 140px;}
    .avia-login{width:415px;text-align:center}
    .avia-login .avia-play>a{transition:all .3s;display:inline-block;width:140px;height:35px;line-height:35px;cursor:pointer}
    .avia-login .avia-play>a:hover{transform: translateY(10px);}
    .avia-login .avia-play>a:last-child{margin-left:30px}
    .avia-login .dianjing-game-icon{width:100%;height:170px;margin-top:180px;background:center bottom no-repeat url(<?php echo TPL_NAME;?>images/dianjing/fydj.png);background-size: 100%;}
    .avia-login .tip {color: #626262;text-align: left;line-height: 24px;margin: 25px 0;}

    .live_right_top{height:90px;position:absolute;right:90px;top:80px}
    .live_right_top a{transition:.3s;position:relative;display:inline-block;width:150px;padding-right: 30px;height:60px;line-height:60px;color:#626262;text-align:right;font-size:18px;background:#fff;background:linear-gradient(to bottom,#fff,#e0dddd);border-radius:10px;margin-left:15px;box-shadow:0 7px 10px rgba(0,0,0,0.2)}
    .live_right_top a.active{color:#fff;background:url(<?php echo TPL_NAME;?>images/live/hover.png) no-repeat center;box-shadow:none;background-size:100%}
    .live_right_top a:before{content: '';display: inline-block;position: absolute;width: 84px;height: 100%;left: 10px;background:url(<?php echo TPL_NAME;?>images/dianjing/nav.png) no-repeat;transform: scale(.85);}
    .live_right_top a:nth-child(2):before{background-position: -90px 0;}
    .live_right_top a.active:before{background-position-y: -63px;}
    .lhdj_all .avia-login .dianjing-game-icon{background-image:url(<?php echo TPL_NAME;?>images/dianjing/lhdj.png);}
    .page-dianjing .lhdj_all .dj_top_img .dj_renwu_0,.page-dianjing .lhdj_all .dj_top_img .dj_renwu_1{background-image:url(<?php echo TPL_NAME;?>images/dianjing/icon_rw_lh.png);}
    .page-dianjing .lhdj_all .dj_top_img .dj_renwu_2{background-image:url(<?php echo TPL_NAME;?>images/dianjing/icon_3.png);}
</style>

<div class="page-dianjing">
    <div class="w_1200 dianjingAll" style="width: 1300px;">
        <div class="live_right_top gameChangeTab">
            <a href="javascript:;" class="active" data-to="lhdj"> 雷火电竞 </a>
            <a href="javascript:;" data-to="fydj"> 泛亚电竞 </a>
        </div>
        <!-- 雷火电竞 开始 -->
        <div class="show_act show_lhdj lhdj_all">
            <div class="dj_top_img left">
                <span class="dj_renwu_0"> </span>
                <span class="dj_renwu_1"> </span>
                <span class="dj_renwu_2"> </span>
                <span class="dj_renwu_3"> </span>
            </div>
            <div class="avia-login right">
                <div class="dianjing-game-icon"></div>
                <p class="tip">
                    雷火竞技保证了游戏快速、安全、公平，同时提供超过数万场电<br>
                    竞比赛、超过30种竞猜玩法、日均50+比赛,业界No.1.,该平台为<br>
                    用户带来王者荣耀、吃鸡等游戏的比赛直播,更有详细游戏攻略信<br>
                    息与比赛选手的数据等！
                </p>
                <div class="avia-play">
                    <!-- 下面div是试玩按钮 -->
                    <a class="btn_game" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $thunFireTryDomain ?>') ">免费试玩</a>
                    <!-- 下面div是开始游戏按钮 -->
                    <a class="btn_game" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/thunfire/fire_api.php?action=getLaunchGameUrl')">开始游戏</a>
                </div>
            </div>

        </div>

        <!-- 泛亚电竞 开始 -->
        <div class="show_act show_fydj">
            <div class="dj_top_img left">
                <span class="dj_renwu_0"> </span>
                <span class="dj_renwu_1"> </span>
                <span class="dj_renwu_2"> </span>
                <span class="dj_renwu_3"> </span>
            </div>
            <div class="avia-login right">
                <div class="dianjing-game-icon"></div>
                <p class="tip">
                    电子竞技是由一群资深专业的电子竞技玩家研发的电竞博彩平台。<br>
                    不同于其他竞争对手使用的传统体育产品界面，电子竞技是为各种<br>
                    电竞玩家和爱好者设计的。电竞博彩平台能让您的电竞玩家轻松上<br>
                    手，一目了然，轻松投注。
                </p>
                <div class="avia-play">
                    <!-- 下面div是试玩按钮 -->
                    <a class="btn_game" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $aviaTryDomain ?>') ">免费试玩</a>
                    <!-- 下面div是开始游戏按钮 -->
                    <a class="btn_game" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/avia/avia_api.php?action=getLaunchGameUrl')">开始游戏</a>
                </div>
            </div>
        </div>

    </div>

</div>

<script type="text/javascript">
    $(function () {
        changeGameTab();

    })
</script>