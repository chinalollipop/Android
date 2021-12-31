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
    .page-dianjing{background:url(<?php echo TPL_NAME;?>images/dianjing/dianjing_bg.png) no-repeat center ;height:890px;position:relative}
    .dj_content{position:absolute;z-index:2;width:800px;left:50%;margin:0 -400px 0;bottom:25px;text-align:center;display:-webkit-flex;display: flex;justify-content: space-between;}
    .dj_li{width: 40%}
    .dj_logo{width: 100%;height: 100px;background:url(<?php echo TPL_NAME;?>images/navxl/dj_lh.png) center no-repeat;background-size: 70%;}
    .dj_li_fy .dj_logo{background:url(<?php echo TPL_NAME;?>images/navxl/dj_fy.png) center no-repeat;background-size: 70%;}
    .avia-login .avia-play>div{transition:all .3s;display:inline-block;width:150px;height:80px;cursor:pointer}
    /*.avia-login .avia-play>div:hover{transform:scale(1.1)}*/
    .avia-login .dianjing-start{background:url(<?php echo TPL_NAME;?>images/dianjing/dz_game_ks.png) center no-repeat;background-size:100%;}
    .avia-login .dianjing-start:hover{background:url(<?php echo TPL_NAME;?>images/dianjing/dz_game_ks_hover.png) no-repeat;background-size:100%;}
    .avia-login .dianjing-test{background:url(<?php echo TPL_NAME;?>images/dianjing/dz_game_sw.png) center no-repeat;background-size:100%}
    .avia-login .dianjing-test:hover{background:url(<?php echo TPL_NAME;?>images/dianjing/dz_game_sw_hover.png) no-repeat;background-size:100%}

</style>

<div class="page-dianjing">
    <div class="w_1200 ">
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

    </div>
</div>
