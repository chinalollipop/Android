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
    .page-dianjing{background:center bottom no-repeat url(<?php echo TPL_NAME;?>images/dianjing/dianjing_bg.jpg);background-size:cover;height:1080px;position:relative}
    .renwu{background:center bottom no-repeat url(<?php echo TPL_NAME;?>images/dianjing/renwu.png?v=1);background-size:cover;height:100%;position:relative}
    .title-pic{position:relative;z-index:2;width: 95%; height:50%;background:center bottom no-repeat url(<?php echo TPL_NAME;?>images/dianjing/title_pic.png?v=1)}
    .dj_content{position:absolute;z-index:2;width:1000px;left:50%;margin:0 -460px 0;bottom:290px;text-align:center;display:-webkit-flex;display: flex;justify-content: space-between;}
    .dj_li{width: 44%}
    .dj_logo{width: 100%;height: 177px;background:url(<?php echo TPL_NAME;?>images/dianjing/logo_lh.png) center no-repeat;margin-bottom: 30px;}
    .dj_li_fy .dj_logo{background:url(<?php echo TPL_NAME;?>images/dianjing/logo_fy.png) center no-repeat;background-size: 90%;}
    .avia-login .avia-play>div{display:inline-block;width:210px;height:70px;cursor:pointer}
    .avia-login .dianjing-start{background:url(<?php echo TPL_NAME;?>images/dianjing/avia_login.png) no-repeat;cursor:pointer; }
    .avia-login .dianjing-start:hover{background-position:0px -79px;}
    .avia-login .dianjing-test{background:url(<?php echo TPL_NAME;?>images/dianjing/test_avia_login.png) no-repeat;cursor:pointer; }
    .avia-login .dianjing-test:hover{background-position:0px -75px; }
    .dianjing-game-icon{position:absolute;bottom:120px;width:100%; height:50px;background:center bottom no-repeat url(<?php echo TPL_NAME;?>images/dianjing/dianjing_game_icon.png);}
</style>

<div class="page-dianjing">
    <div class="renwu">
        <div class="title-pic"></div>

        <div class="dj_content">
            <div class="dj_li">
                <div class="dj_logo"></div>
                <div class="avia-login">
                    <div class="avia-play">
                        <!-- 下面div是试玩按钮 -->
                        <div class="dianjing-test" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $thunFireTryDomain ?>') "></div>
                        <!-- 下面div是开始游戏按钮 -->
                        <div class="dianjing-start" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/thunfire/fire_api.php?action=getLaunchGameUrl')"></div>

                    </div>
                </div>
            </div>
            <div class="dj_li dj_li_fy">
                <div class="dj_logo"></div>
                <div class="avia-login">
                    <div class="avia-play">
                        <!-- 下面div是试玩按钮 -->
                        <div class="dianjing-test" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $aviaTryDomain ?>') "></div>
                        <!-- 下面div是开始游戏按钮 -->
                        <div class="dianjing-start" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/avia/avia_api.php?action=getLaunchGameUrl')"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dianjing-game-icon"></div>

    </div>
</div>
