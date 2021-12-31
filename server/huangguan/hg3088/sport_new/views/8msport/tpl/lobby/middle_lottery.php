<?php
session_start();

include "../../../../app/member/include/config.inc.php";

//  单页面维护功能
checkMaintain('thirdcp');

$cpUrl = $_SESSION['LotteryUrl'];
$uid = $_SESSION['Oid']; // 判断是否已登录


?>
<style>
    .lottery_main{height:770px;background:url(<?php echo TPL_NAME;?>images/lottery/lottery_bg.jpg) center no-repeat;padding-top:30px}
    .lottery_main>div{position:relative}
    .show_act.show_gf{padding-top:70px}
    .lottery_left{position:relative;height:100%}
    .lottery_left span{position:absolute;display:inline-block}
    .lottery_left .jb_bg{width:518px;height:612px;background:url(<?php echo TPL_NAME;?>images/lottery/g_bg.png) no-repeat;animation:right-left-move 12s -2s infinite alternate both;left:70px}
    .lottery_left .icon{width:84px;height:70px;transform:scale(.9);animation:go_up 5s infinite}
    .lottery_left .icon_1{background:url(<?php echo TPL_NAME;?>images/lottery/g_icon_1.png) no-repeat;top:85px;left:5px}
    .lottery_left .icon_2{background:url(<?php echo TPL_NAME;?>images/lottery/g_icon_2.png) no-repeat;left:545px;top:70px}
    .lottery_left .icon_3{background:url(<?php echo TPL_NAME;?>images/lottery/g_icon_3.png) no-repeat;top:340px}
    .lottery_left .icon_4{background:url(<?php echo TPL_NAME;?>images/lottery/g_icon_4.png) no-repeat;left:495px;top:395px;animation:right-small-move 7s infinite alternate both}
    .lottery_main .title{width:466px;height:182px;background:url(<?php echo TPL_NAME;?>images/lottery/g_title.png) center no-repeat;margin:165px auto 20px;animation:colour_ease2 3s infinite ease-in-out}
    .lottery_main .lottery_tip{color:#626262;line-height:24px}
    .lottery_main .btn_game{transition:.3s;display:block;width:140px;height:35px;line-height:35px;border-radius:50px !important;text-align:center;font-size:18px;margin:40px auto}
    .lottery_main .btn_game:hover{transform:translateY(10px)}
    .live_right_top{height:90px;position:absolute;right:130px;top:120px}
    .live_right_top a{transition:.3s;position:relative;display:inline-block;width:145px;height:53px;line-height:53px;color:#626262;text-align:center;font-size:18px;background:#fff;background:linear-gradient(to bottom,#fff,#e0dddd);border-radius:10px;margin-left:15px;box-shadow:0 7px 10px rgba(0,0,0,0.2)}
    .live_right_top a.active{color:#fff;background:url(<?php echo TPL_NAME;?>images/live/hover.png) no-repeat center;box-shadow:none;background-size:100%}
    .xy_lottery .lottery_left .jb_bg{width:815px;height:730px;background:url(<?php echo TPL_NAME;?>images/lottery/x_bg.png) no-repeat;left:-130px}
    .lottery_main .xy_lottery .title{background:url(<?php echo TPL_NAME;?>images/lottery/x_title.png) no-repeat center}
    .lottery_main .xy_lottery .title{margin-top:235px}
    .xy_lottery .lottery_left .icon{width:90px;height:95px}
    .xy_lottery .lottery_left .icon_1{background:url(<?php echo TPL_NAME;?>images/lottery/x_icon_1.png) no-repeat;top:140px;left:180px}
    .xy_lottery .lottery_left .icon_2{background:url(<?php echo TPL_NAME;?>images/lottery/x_icon_2.png) no-repeat;left:485px;top:175px}
    .xy_lottery .lottery_left .icon_3{background:url(<?php echo TPL_NAME;?>images/lottery/x_icon_3.png) no-repeat;top:320px}
    .xy_lottery .lottery_left .icon_4{background:url(<?php echo TPL_NAME;?>images/lottery/x_icon_4.png) no-repeat;left:230px;top:400px;animation:right-small-move 7s infinite alternate both}
</style>

<div class="lottery_main">
    <div class="w_1200">
        <div class="live_right_top gameChangeTab">
            <a href="javascript:;" class="active" data-to="gf"> 官方彩票 </a>
            <a href="javascript:;" data-to="xy"> 信用彩票 </a>
        </div>
        <!-- 官方盘 开始 -->
        <div class="show_act show_gf">
            <div class="left lottery_left">
                <span class="jb_bg"></span>
                <span class="icon icon_1"></span>
                <span class="icon icon_2"></span>
                <span class="icon icon_3"></span>
                <span class="icon icon_4"></span>
            </div>
            <div class="right">
                <div class="title"></div>
                <p class="lottery_tip">
                    彩票领域多年, 提供三种合作模式, 游戏多元, 遍及世界各地的当红<br>
                    彩票玩法, 精心研发多达50款以上的独家创新彩种游戏, 兼具两面盘与<br>
                    官方盘玩法, 让您的游戏体验更加完整刺激 !
                </p>
                <a class="to_lotterys_third btn_game" href="javascript:;" title="立即游戏">立即游戏</a>
            </div>
        </div>
       <!-- 官方盘 结束 -->

        <!-- 信用盘 开始 -->
        <div class="show_act show_xy xy_lottery hide" >
            <div class="left lottery_left">
                <span class="jb_bg"></span>
                <span class="icon icon_1"></span>
                <span class="icon icon_2"></span>
                <span class="icon icon_3"></span>
                <span class="icon icon_4"></span>
            </div>
            <div class="right">
                <div class="title"></div>
                <p class="lottery_tip">
                    广泛彩票游戏网站，玩法简单、赔率多元、公开公正，完整彩票游戏<br>
                    历史数据呈现，游戏包括北京赛车、香港六合彩、重庆时时彩<br>
                    分分彩、欢乐生肖等 !
                </p>
                <a class="to_lotterys_third btn_game" href="javascript:;" title="立即游戏" data-to="1">立即游戏</a>
            </div>
        </div>
        <!-- 信用盘 结束 -->


    </div>
</div>

<script type="text/javascript">
    $(function () {
        changeGameTab();
        
    })
</script>