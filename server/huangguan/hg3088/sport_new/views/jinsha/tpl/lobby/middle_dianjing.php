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
    .page-dianjing{background: center no-repeat url(<?php echo TPL_NAME;?>images/dianjing/dianjing_bg.jpg?v=1);height:834px;position:relative;}
    .dj_content{position:absolute;z-index:2;width:800px;left:50%;margin:0 -400px 0;bottom:120px;text-align:center;display:-webkit-flex;display: flex;justify-content: space-between;}
    .dj_li{width: 48%}
    .dj_logo{width: 100%;height: 100px;background:url(/images/navxl/logo_lh.png) center no-repeat;margin-bottom: 30px;}
    .dj_li_fy .dj_logo{background:url(/images/navxl/logo_fy.png) center no-repeat;}
    .avia-login .avia-play>div{transition:all .3s;display:inline-block;width:160px;height:70px;cursor:pointer}
    .avia-login .avia-play>div:hover{transform:scale(1.1)}
    .avia-login .dianjing-start{ background:url(<?php echo TPL_NAME;?>images/dianjing/dz_game_ks.png) no-repeat;background-size: 100%;}
    .avia-login .dianjing-test{background:url(<?php echo TPL_NAME;?>images/dianjing/dz_game_sw.png) no-repeat;background-size: 100%;margin-left: 20px;}
    .dj_bottom {position: absolute;width: 100%;bottom: 30px;}
    .dj_bottom .dianjing-game-icon{width:100%; height:60px; background:center no-repeat url(<?php echo TPL_NAME;?>images/dianjing/dianjing_game_icon.png);}
</style>

<div class="page-dianjing">

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
    <div class="dj_bottom">
        <div class="dianjing-game-icon"></div>
    </div>
</div>
