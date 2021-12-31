<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];

$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

?>
<style>
    .casion_wrapper{background:url(<?php echo TPL_NAME;?>images/live/live_bg.jpg) top center no-repeat;height: 76%;background-size: cover;}
    .casion_wrapper .play_logo_box{width:40%;height:100%;float:left;position:relative;left:4%}
    .casion_wrapper .play_logo_box img{width:100%;-webkit-animation:img_animat .5s;animation:img_animat .5s;position:absolute;bottom:0}
    .casion_wrapper .play_game_box{float:left;text-align:center;width:26%;margin-left:6%;height:100%}
    .casion_wrapper .play_game_box .play_game_show_content{width:100%;margin-top:271px}
    .casion_wrapper .play_game_box .play_game_show_content .ganme_logo_box{padding-bottom:10px;border-bottom:1px solid #000;margin:auto}
    .casion_wrapper .play_game_box .play_game_show_content .game_desc{line-height:21px;font-size:13px;margin:5px 0}
    .casion_wrapper .play_game_box .play_game_show_content .btn_play{width:177px;height:40px;text-align:center;border-radius:21px;line-height:40px;font-size:18px;margin:auto}
    .casion_wrapper .game_list{padding-top:5%;/*float:right;*/text-align:center;}
    .casion_wrapper .game_list .ganme_list_wrapper{height:556px;width:25%;overflow:hidden}
    .casion_wrapper .game_list .ganme_list_continues li{height:70px;position:relative;margin:0 auto;width:100%}
    .casion_wrapper .game_list .ganme_list_continues .game_option{border:1px solid #000;font-size:18px;line-height:20px;width:50%;height:66%;text-align:center;border-radius:40px;position:absolute;top:0;bottom:0;left:0;right:0;margin:auto}
    .casion_wrapper .game_list .ganme_list_continues .game_option .ganme_en_name{border-bottom:1px solid #fff;width:88px;margin:auto}
    .casion_wrapper .game_list .ganme_list_continues li .them_bg_color_gradient{color:#fff!important;border:none!important;width:66%;height:88%;line-height:28px;}
    .casion_wrapper .game_list .ganme_list_continues li .them_bg_color_gradient .ganme_en_name{border-bottom-color:#fff!important}
</style>

<div class="casion_wrapper cl router_view_mian" >
    <div class="play_logo_box">
        <img id="img_0" src="<?php echo TPL_NAME;?>images/live/ren.png" alt="">
        <img id="img_1" src="<?php echo TPL_NAME;?>images/live/ren_og.png" alt="" style="display: none;">
        <img id="img_3" src="<?php echo TPL_NAME;?>images/live/ren_bbin.png" alt="" style="display: none;">
    </div>
    <div id="play_0" class="play_game_box">
        <div class="play_game_show_content">
            <p class="ganme_logo_box them_border_bottom_color">
                <img src="<?php echo TPL_NAME;?>images/live/ag.png"alt="">
            </p>
            <div class="game_desc game_ them_font_color">
                <p >
                    视讯盛宴 极速稳定的真人娱乐体验
                </p>
                <p >
                    VIP级尊贵奢华享受！同时支持电脑版，手机网页版和手机应用！
                </p>
            </div>
            <p class="btn_play them_bg_color_gradient" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')">
                开始游戏
            </p>
        </div>
    </div>
    <div id="play_1" class="play_game_box" style="display: none;">
        <div class="play_game_show_content">
            <p class="ganme_logo_box them_border_bottom_color">
                <img src="<?php echo TPL_NAME;?>images/live/og.png" alt="">
            </p>
            <div class="game_desc game_ them_font_color">
                <p >
                    视讯盛宴 极速稳定的真人娱乐体验
                </p>
                <p >
                    VIP级尊贵奢华享受！同时支持电脑版，手机网页版和手机应用！
                </p>
            </div>
            <p class="btn_play them_bg_color_gradient " onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">
                开始游戏
            </p>
        </div>
    </div>
    <div id="play_3" class="play_game_box" style="display: none;">
        <div class="play_game_show_content">
            <p class="ganme_logo_box them_border_bottom_color">
                <img src="<?php echo TPL_NAME;?>images/live/bbin.png" alt="">
            </p>
            <div class="game_desc game_ them_font_color">
                <p >
                    视讯盛宴 极速稳定的真人娱乐体验
                </p>
                <p >
                    VIP级尊贵奢华享受！同时支持电脑版，手机网页版和手机应用！
                </p>
            </div>
            <p class="btn_play them_bg_color_gradient" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')">
                开始游戏
            </p>
        </div>
    </div>

    <div class="game_list">
        <div class="ganme_list_wrapper">
            <ul class="ganme_list_continues">
                <li id="btn_0" class="click_on">
                    <div class="game_option them_font_color_two active_border_color them_bg_color_gradient">
                        <p class="ganme_en_name them_border_bottom_color_tow">
                            AG
                        </p>
                        <p >
                            亚洲厅
                        </p>
                    </div>
                </li>
                <li id="btn_1" class="click_on">
                    <div class="game_option them_font_color_two active_border_color">
                        <p class="ganme_en_name them_border_bottom_color_tow">
                            OG
                        </p>
                        <p  >
                            东方厅
                        </p>
                    </div>
                </li>
                <li id="btn_3" class="click_on">
                    <div class="game_option them_font_color_two active_border_color">
                        <p class="ganme_en_name them_border_bottom_color_tow">
                            BBIN
                        </p>
                        <p >
                            台湾厅
                        </p>
                    </div>
                </li>

            </ul>
        </div>
    </div>

</div>


<script type="text/javascript">
    $(function () {

        indexCommonObj.getUserQpBanlance(uid,'ag') ;

        changeLiveTab();
        // 视讯切换
        function changeLiveTab() {
            $(".click_on").click(function () {
                var id = $(this).attr("id");
                var num = id.substr(id.lastIndexOf());
                $(this).find("div").addClass("them_bg_color_gradient");
                $(this).siblings().find("div").removeClass("them_bg_color_gradient");
                zhuanhuan(num);
            });
        }
        function zhuanhuan(id){
            $("#img_"+id).css("display","block");
            $("#img_"+id).siblings().css("display","none");
            $(".play_game_box").css("display","none");
            $("#play_"+id).css("display","block");
        }
    })
</script>