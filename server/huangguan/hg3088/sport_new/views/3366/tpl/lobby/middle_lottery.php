<?php
session_start();

include "../../../../app/member/include/config.inc.php";

//  单页面维护功能
checkMaintain('thirdcp');

$cpUrl = $_SESSION['LotteryUrl'];
$uid = $_SESSION['Oid']; // 判断是否已登录


?>
<style>
    .lottery_main{height:655px;background:url(<?php echo TPL_NAME;?>images/lottery/lottery_bg.jpg) center no-repeat;padding-top:50px}
    .lottery_left{position:relative;height:640px}
    .lottery_left span{position:absolute;display:inline-block}
    .lottery_left .jb_bg{width:208px;height:251px;background:url(<?php echo TPL_NAME;?>images/lottery/lottery_a4.png) no-repeat;left:200px}
    .lottery_left .sb_q{width:68px;height:69px;background:url(<?php echo TPL_NAME;?>images/lottery/lottery_48.png) no-repeat;left:470px;top:50px;animation: left-pig-move 10s 1.6s infinite alternate both;}
    .lottery_left .xq_r{width:51px;height:60px;background:url(<?php echo TPL_NAME;?>images/lottery/lottery_q.png) no-repeat;left:660px;bottom:300px;animation: right-small-move 7s infinite alternate both;}
    .lottery_left .big_q{width:550px;height:430px;background:url(<?php echo TPL_NAME;?>images/index/big-lottery.png) no-repeat;background-size:100%;bottom:140px;left:140px;z-index:1;animation: right-left-move 12s -2s infinite alternate both;}
    .lottery_left .big_st{width:800px;height:283px;background:url(<?php echo TPL_NAME;?>images/lottery/lottery_stone.png) no-repeat;bottom:0}
    .lottery_main .title{width:250px;height:60px;background:url(<?php echo TPL_NAME;?>images/lottery/lottery_title.png) no-repeat;margin-top:75px}
    .lottery_main .lottery_icon{width:456px;height:60px;background:url(<?php echo TPL_NAME;?>images/lottery/lottery_icon.png) no-repeat;margin:40px 0}
    .lottery_main .lottery_tip{color:#515151;line-height:30px}
    .lottery_main .btn_game{transition:.3s;display:block;width:165px;height:50px;line-height:50px;border-radius:50px !important;text-align:center;font-size:24px;margin:60px auto}
    .lottery_main .btn_game:hover {transform: translateY(10px);}
</style>

<div class="lottery_main">
    <div class="w_1200">
        <div class="left lottery_left">
           <span class="jb_bg"></span>
           <span class="sb_q"></span>
           <span class="xq_r"></span>
           <span class="big_q"></span>
           <span class="big_st"></span>
        </div>
        <div class="right">
            <div class="title"></div>
            <div class="lottery_icon"></div>
            <p class="lottery_tip">
                专注于彩票游戏行业多年，拥有经典彩种、玩法。还有超多独家创新玩法，<br>
                足够新颖，极易操作的游戏界面，更是在您的游戏过程中增光添彩！
            </p>
            <!--<a class="btn_game" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','<?php /*echo $cpUrl;*/?>')" title="立即游戏">立即游戏</a>-->
            <a class="to_lotterys_third btn_game" href="javascript:;" title="立即游戏">立即游戏</a>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        
    })
</script>