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
    .page-dianjing{background:center no-repeat url(<?php echo TPL_NAME;?>images/dianjing/dianjing_bg.jpg?v=2);background-size:cover;height:820px;position:relative}
    .renwu{height:100%;position:relative;/*background:center bottom no-repeat url(<?php echo TPL_NAME;?>images/dianjing/renwu.png);background-size:cover;*/}
    .avia-login{position:absolute;z-index:2;width: 396px; height: 80px; bottom: 150px; left: 50%;margin-left: -700px;transform: scale(.8);}
    .avia-login.avia-login-lh {margin-left: 390px;}
    .avia-login>div {display: inline-block;width:195px;height:75px; }
    .avia-login .dianjing-start{background:url(<?php echo TPL_NAME;?>images/dianjing/avia_login.png?v=2) no-repeat;cursor:pointer; }
    .avia-login .dianjing-start:hover{background-position:0px -79px; }
    .avia-login .dianjing-test{ background:url(<?php echo TPL_NAME;?>images/dianjing/test_avia_login.png?v=2) no-repeat;cursor:pointer; }
    .avia-login .dianjing-test:hover{background-position:0px -75px; }
    .avia-login .dianjing-game-icon{width:100%; height:50px; margin-left: 78px; margin-top:100px; background:center bottom no-repeat url(<?php echo TPL_NAME;?>images/dianjing/dianjing_game_icon.png);}
</style>

<div class="page-dianjing">
    <div class="renwu">

        <div class="avia-login">
            <!-- 下面div是试玩按钮 -->
            <div class="dianjing-test" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $aviaTryDomain ?>') "></div>

            <!-- 下面div是开始游戏按钮 -->
            <div class="dianjing-start" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/avia/avia_api.php?action=getLaunchGameUrl')"></div>

        </div>

        <div class="avia-login avia-login-lh">
            <!-- 下面div是试玩按钮 -->
            <div class="dianjing-test" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $thunFireTryDomain ?>') "></div>

            <!-- 下面div是开始游戏按钮 -->
            <div class="dianjing-start" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/thunfire/fire_api.php?action=getLaunchGameUrl')"></div>

        </div>

    </div>
</div>
