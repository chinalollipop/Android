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
    .page-dianjing{background:url(<?php echo TPL_NAME;?>images/dianjing/dianjing_bg.png) no-repeat center ;height:790px;position:relative}
    .dj_bottom{width:100%;background:no-repeat url(<?php echo TPL_NAME;?>images/dianjing/dianjing_dt.png) center;height:362px;position:absolute;bottom:0;z-index: 1}
    .dj_top_img{width:830px;margin:0 auto}
    .dj_top_img>div{position:absolute}
    .dj_top_img .dj_left{width:353px;height:669px;background:no-repeat url(<?php echo TPL_NAME;?>images/dianjing/dianjing_left.png)}
    .dj_top_img .dj_right{width:384px;height:637px;background:no-repeat url(<?php echo TPL_NAME;?>images/dianjing/dianjing_right.png);left:50%;margin-left:-100px}
    .dj_top_img .dj_renwu{width:750px;height:700px;top:0;left: 50%;margin-left: -375px;}
    .dj_content{position:absolute;z-index:2;width:1500px;left:50%;margin:0 -750px 0;bottom:200px;text-align:center;display:-webkit-flex;display: flex;justify-content: space-between;}
    .dj_li{width: 40%}
    .dj_logo{width: 100%;height: 300px;background:url(<?php echo TPL_NAME;?>images/dianjing/lh_logo.png) center no-repeat;background-size:92%;}
    .dj_li_fy .dj_logo{background:url(<?php echo TPL_NAME;?>images/dianjing/fy_logo.png) center no-repeat;background-size:92%;background-position-x: 100px;}
    .dj_li_fy .avia-play{margin-left: 160px;}
    .avia-login .avia-play>div{transition:all .3s;display:inline-block;width:170px;height:70px;cursor:pointer}
    .avia-login .avia-play>div:hover{transform:scale(1.1)}
    .avia-login .dianjing-start{background:url(<?php echo TPL_NAME;?>images/dianjing/dz_game_ks.png) no-repeat;background-size:100%;}
    .avia-login .dianjing-test{background:url(<?php echo TPL_NAME;?>images/dianjing/dz_game_sw.png) no-repeat;background-size:100%;margin-left:20px}
    .dianjing-game-icon{position:absolute;z-index:2;bottom:60px;width:1000px;height:60px;left:50%;margin-left:-500px;background:center bottom no-repeat url(<?php echo TPL_NAME;?>images/dianjing/dianjing_game_icon.png)}
    .dj_top_img .dj_renwu span{display: inline-block;position: absolute;width: 374px;height: 358px;}
    .dj_top_img .dj_renwu .dj_renwu_1{background: url("<?php echo TPL_NAME;?>images/dianjing/dianjing_rw_1.png") no-repeat;bottom: 137px;left: 280px;z-index: 1;}
    .dj_top_img .dj_renwu .dj_renwu_2{background: url("<?php echo TPL_NAME;?>images/dianjing/dianjing_rw_2.png") no-repeat;bottom: 121px;left: 84px;z-index: 1;animation: rightToLeft 2s forwards;}
    .dj_top_img .dj_renwu .dj_renwu_3{background: url("<?php echo TPL_NAME;?>images/dianjing/dianjing_rw_3.png") no-repeat;bottom: 137px;left: 145px;z-index: 1;animation: rightToLeft 1.5s forwards;}
    .dj_top_img .dj_renwu .dj_renwu_4{background: url("<?php echo TPL_NAME;?>images/dianjing/dianjing_rw_4.png") no-repeat;bottom: 129px;right: -107px;z-index: 1;animation: centerToLeft 2s forwards;}
    .dj_top_img .dj_renwu .dj_renwu_5{background: url("<?php echo TPL_NAME;?>images/dianjing/dianjing_rw_5.png") no-repeat;bottom: 131px;right: -185px;z-index: 1;animation: centerToLeft 1.5s forwards;}
    .dj_top_img .dj_renwu .dj_renwu_6{background: url("<?php echo TPL_NAME;?>images/dianjing/dianjing_rw_6.png") no-repeat;top: 63px;left: 210px;animation: bottomToTopLeft 2s forwards;}
    .dj_top_img .dj_renwu .dj_renwu_7{background: url("<?php echo TPL_NAME;?>images/dianjing/dianjing_rw_7.png") no-repeat;top: 59px;left: 315px;animation: bottomToTop 1.5s forwards;}
    .dj_top_img .dj_renwu .dj_renwu_8{background: url("<?php echo TPL_NAME;?>images/dianjing/dianjing_rw_8.png") no-repeat;top: 82px;right: -85px;animation: bottomToTopRight 2s forwards;}

</style>

<div class="page-dianjing">
    <div class="w_1200 ">
        <div class="dj_top_img">
            <div class="dj_left"> </div>
            <div class="dj_right"> </div>
            <div class="dj_renwu">
                <span class="dj_renwu_1"> </span>
                <span class="dj_renwu_2"> </span>
                <span class="dj_renwu_3"> </span>
                <span class="dj_renwu_4"> </span>
                <span class="dj_renwu_5"> </span>
                <span class="dj_renwu_6"> </span>
                <span class="dj_renwu_7"> </span>
                <span class="dj_renwu_8"> </span>
            </div>
        </div>

        <div class="dj_content">
            <div class="dj_li">
                <div class="dj_logo"></div>
                <div class="avia-login">
                    <div class="avia-play">
                        <!-- 下面div是开始游戏按钮 -->
                        <div class="dianjing-start" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/thunfire/fire_api.php?action=getLaunchGameUrl')"></div>
                        <!-- 下面div是试玩按钮 -->
                        <div class="dianjing-test" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $thunFireTryDomain ?>') "></div>
                    </div>
                </div>
            </div>
            <div class="dj_li dj_li_fy">
                <div class="dj_logo"></div>
                <div class="avia-login">
                    <div class="avia-play">
                        <!-- 下面div是开始游戏按钮 -->
                        <div class="dianjing-start" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/avia/avia_api.php?action=getLaunchGameUrl')"></div>
                        <!-- 下面div是试玩按钮 -->
                        <div class="dianjing-test" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $aviaTryDomain ?>') "></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dianjing-game-icon"></div>


    </div>

    <div class="dj_bottom"> </div>


</div>
