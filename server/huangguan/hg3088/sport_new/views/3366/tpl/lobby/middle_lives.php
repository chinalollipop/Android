<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];

$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

?>
<style>
    .live_main{height:630px;background:url(<?php echo TPL_NAME;?>images/live/live_bg.jpg) no-repeat center;padding-top:60px}
    .live_main .live_left{position:relative;height:100%}
    .live_main .live_left>div{position:absolute}
    .live_main .live_left .live_rw{width:600px;height:624px;background:url(<?php echo TPL_NAME;?>images/live/ren.png) no-repeat center;background-size:100%;bottom:-40px}
    .live_main .live_left .live_zp{width:100px;height:81px;background:url(<?php echo TPL_NAME;?>images/live/puke.png) no-repeat center;background-size:100%;left:120px;top:25px;animation: left-pig-move 10s 1.6s infinite alternate both;}
    .live_main .live_left .live_cm{width:60px;height:79px;background:url(<?php echo TPL_NAME;?>images/live/chouma.png) no-repeat center;background-size:100%;left:560px;top:290px;animation: right-small-move 7s infinite alternate both;}
    .live_main .live_right{text-align:center;padding-top:30px}
    .live_main .live_right_top a{display:inline-block;width:135px;height:45px;line-height:45px;color:#000;text-align:center;font-size:18px;background:#e0dddd;background:linear-gradient(to bottom,#fafaf9,#e0dddd);border-radius:5px;margin-right:20px}
    .live_main .live_right_top a.active{background:#ff9521;background:linear-gradient(to bottom,#ffe953,#ff9521)}
    .live_main .live_right p{height:37px;font-size:28px;color:#9f9e9d;margin:30px 0}
    .live_main .ag_icon{width:330px;height:155px;background:url(<?php echo TPL_NAME;?>images/live/ag.png) no-repeat center;background-size:100%;margin:20px auto}
    .live_main .live_icon{width:530px;height:92px;background:url(<?php echo TPL_NAME;?>images/live/tubiao.png) no-repeat center;background-size:100%}
    .live_main .live_right .btn_game{transition:.3s;display:inline-block;width:160px;height:46px;line-height:46px;font-size:20px;border-radius:50px !important;margin:30px}
    .live_main .live_right .btn_game:hover{transform: translateY(10px)}
    /* og */
    .og,.bbin{display: none;}
    .live_main .live_left.og .live_rw{background:url(<?php echo TPL_NAME;?>images/live/ren_og.png) no-repeat center;background-size:90%;bottom: 0;}
    .live_main .live_left.og .live_zp{background:url(<?php echo TPL_NAME;?>images/live/og_cm.png) no-repeat center;background-size:60%;left: 48px;}
    .live_main .live_left.og .live_cm{background:url(<?php echo TPL_NAME;?>images/live/og_zp.png) no-repeat center;}
    .live_main .og_icon{width:330px;height:155px;background:url(<?php echo TPL_NAME;?>images/live/og.png) no-repeat center;background-size:100%;margin:20px auto}

    /* bbin */
    .live_main .live_left.bbin .live_rw{background:url(<?php echo TPL_NAME;?>images/live/ren_bbin.png) no-repeat center;background-size:96%;bottom: -1px;}
    .live_main .live_left.bbin .live_zp{background:url(<?php echo TPL_NAME;?>images/live/bbin_cm.png) no-repeat center;background-size:60%;left: 48px;}
    .live_main .live_left.bbin .live_cm{background:url(<?php echo TPL_NAME;?>images/live/bbin_zp.png) no-repeat center;left: 510px;top: 105px;}
    .live_main .bbin_icon{width:330px;height:155px;background:url(<?php echo TPL_NAME;?>images/live/bbin.png) no-repeat center;background-size:100%;margin:20px auto}


</style>

<div class="live_main">
    <div class="w_1200">
        <!-- ag -->
        <div class="left live_left show_ag show_act">
            <div class="live_rw"></div>
            <div class="live_zp"></div>
            <div class="live_cm"></div>
        </div>
        <!-- og -->
        <div class="og left live_left show_og show_act">
            <div class="live_rw"></div>
            <div class="live_zp"></div>
            <div class="live_cm"></div>
        </div>
        <!-- bbin -->
        <div class="bbin left live_left show_bbin show_act">
            <div class="live_rw"></div>
            <div class="live_zp"></div>
            <div class="live_cm"></div>
        </div>

        <div class="right live_right">
            <div class="live_right_top">
                <a href="javascript:;" class="active" data-to="ag"> AG视讯 </a>
                <a href="javascript:;" data-to="og"> OG视讯 </a>
                <a href="javascript:;" data-to="bbin"> BBIN视讯 </a>
                <a href="javascript:;" > 敬请期待 </a>
            </div>
            <p>  </p> <!-- 高品质真实体验 让您身临其境 -->
            <!--  ag -->
            <div class="show_ag show_act">
                <div class="ag_icon"></div>
                <div class="live_icon"></div>
                <a href="javascript:;" class="btn_game" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>')">免费试玩</a>
                <a href="javascript:;" class="btn_game" title="立即游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')">立即游戏</a>
            </div>
            <!-- og -->
            <div class="og show_og show_act">
                <div class="og_icon"></div>
                <div class="live_icon"></div>
                <!--<a href="javascript:;" class="btn_game" title="免费试玩" >免费试玩</a>-->
                <a href="javascript:;" class="btn_game" title="立即游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">立即游戏</a>
            </div>
            <!-- bbin -->
            <div class="bbin show_bbin show_act">
                <div class="bbin_icon"></div>
                <div class="live_icon"></div>
                <a href="javascript:;" class="btn_game" title="立即游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')">立即游戏</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        indexCommonObj.getUserQpBanlance(uid,'ag') ;
        $('.download_win_exe').attr('href',configbase.exeWinUrl);
        $('.download_mac_exe').attr('href',configbase.macWinUrl);

        changeLiveTab();
        // 视讯切换
        function changeLiveTab() {
            $('.live_right_top').on('click','a',function () {
               var type = $(this).attr('data-to');
               if(!type){
                   return false;
               }else{
                   $(this).addClass('active').siblings().removeClass('active');
                    $('.show_act').hide();
                    $('.show_'+type).fadeIn();
               }
            });
        }
    })
</script>