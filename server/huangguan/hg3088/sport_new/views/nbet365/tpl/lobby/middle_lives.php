<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];

$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

?>
<style>

</style>

<div class="live_main">
    <div id="new-banner">
        <div id="new-banner-box">
            <img style="width: 950px;" src="<?php echo TPL_NAME;?>images/live/nav_zr.jpg">
        </div>
    </div>


    <div id="visualwrap">
        <div id="visualbox">
            <div id="visualboxleft">
                <div id="c1"><a href="/cn/youhui"></a></div>
                <div id="c2"><a href="/cn/youhui"></a></div>
            </div>
            <div id="visualboxright">

                <div class="visualww">
                    <div class="visualww1">
                        <div class="visualwwpic">
                            <a href="javascript:;"><img src="<?php echo TPL_NAME;?>images/live/ag.jpg" dynsrc="" style="width:225px;"></a>
                        </div>
                        <div class="jianjie">
                            <a href="javascript:;">
                                AG厅Asian gaming（寰宇）平台， 是目前最具创意及创新的合法博彩娱乐平台,真实赌场通过高清大屏幕再现...
                            </a>
                        </div>
                        <div class="clear"></div>
                        <div class="visualbottom">
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>')">免费试玩</a>
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')">立即开始</a>
                        </div>
                    </div>
                </div>

                <div class="visualww">
                    <div class="visualww1">
                        <div class="visualwwpic">
                            <a href="javascript:;"><img src="<?php echo TPL_NAME;?>images/live/bb.jpg" dynsrc="" style="width:225px;"></a>
                        </div>
                        <div class="jianjie">
                            <a href="javascript:;">
                                BBIN平台，提供多元网络娱乐服务平台和游戏商品开发，无论是在运动投注、真人视讯、电子游艺、桌上游戏、乐透...
                            </a>
                        </div>
                        <div class="clear"></div>
                        <div class="visualbottom">
                            <!--<a href="/visual/bbin-test" target="_blank">免费试玩</a>-->
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')">立即开始</a>
                        </div>
                    </div>
                </div>

                <div class="visualww">
                    <div class="visualww1">
                        <div class="visualwwpic">
                            <a href="javascript:;"><img src="<?php echo TPL_NAME;?>images/live/og.jpg" dynsrc="" style="width:225px;"></a>
                        </div>
                        <div class="jianjie">
                            <a href="javascript:;">
                                OG厅是遵照赌场专业严谨的流程，以清晰的直播画面提供百家乐、龙虎，轮盘或骰宝多款热门产品，保证玩家...
                            </a>
                        </div>
                        <div class="clear"></div>
                        <div class="visualbottom">
                            <!--<a href="/visual/og-test" target="_blank">免费试玩</a>-->
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">立即开始</a>
                        </div>
                    </div>
                </div>

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