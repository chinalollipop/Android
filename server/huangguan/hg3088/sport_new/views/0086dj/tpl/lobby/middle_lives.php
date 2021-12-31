<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];

$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

?>
<style>
    .qp_play a{background: #722420;box-shadow: 1px 2px #3e0b08;color: #fff;height: auto;padding: 10px 0;margin: 20px 0;}
    .qp_play a.qp_testplay_btn{margin-top: 0;}
    .live_play{width: 30%;}
</style>

<div class="container" >
    <div class="promo_menu float_parent">
        <div class="gm1 float_parent" style="padding:0 20px;">
            <div class="ggm" ><a  class="onselect">热门游戏</a>
                <div class="select_line"></div>
            </div>
           <!-- <div class="ggm"><a >旗舰厅</a></div>
            <div class="ggm"><a >国际厅</a></div>
            <div class="ggm"><a >竞咪厅</a></div>
            <div class="ggm"><a>其他游戏</a></div>-->
        </div>
    </div>

    <div >
        <div class="upper_holder" id="uh0">

            <div class="upmain"  >
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(1)" onmouseout="cms_hlOut(1)">
                        <img src="<?php echo TPL_NAME;?>images/hom_h2_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox1"
                         style="background-image: url(<?php echo TPL_NAME;?>images/ag_live.jpg); ">
                        <div class="cms_hl_txt" id="cms_hlt1">
                            <div class="cms_hlt1" >AG真人视讯</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2" >真人娱乐场</div>
                        </div>
                        <img src="<?php echo TPL_NAME;?>images/hom_h2_cover.png" width="100%">
                    </div>
                    <div class="qp_play live_play"  onmouseover="cms_hlHover(1)" onmouseout="cms_hlOut(1)">

                        <a href="javascript:;" class="qp_testplay_btn" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>')"> 免费试玩 </a>
                        <a href="javascript:;" class="qp_play_btn" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')"> 开始游戏 </a>
                    </div>
                </div>
            </div>


            <div class="upmain" >
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(2)" onmouseout="cms_hlOut(2)">
                        <img src="<?php echo TPL_NAME;?>images/hom_h2_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox2"
                         style="background-image: url(<?php echo TPL_NAME;?>images/og_live.jpg); ">
                        <div class="cms_hl_txt" id="cms_hlt2">
                            <div class="cms_hlt1" >OG真人视讯</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2" >真人娱乐场</div>
                        </div>
                        <img src="<?php echo TPL_NAME;?>images/hom_h2_cover.png" width="100%">
                    </div>
                    <div class="qp_play live_play"  onmouseover="cms_hlHover(2)" onmouseout="cms_hlOut(2)">
                        <a href="javascript:;" class="qp_play_btn" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')"> 开始游戏 </a>
                    </div>
                </div>
            </div>


            <div class="upmain" >
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(3)" onmouseout="cms_hlOut(3)" ><img src="<?php echo TPL_NAME;?>images/hom_h2_cover.png" width="100%">
                    </div>
                    <div class="cms_hl_box" id="cms_hlbox3" style="background-image: url(<?php echo TPL_NAME;?>images/bbin_live.jpg); ">
                        <div class="cms_hl_txt" id="cms_hlt3">
                            <div class="cms_hlt1" >BBIN真人视讯</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2" >真人娱乐场</div>
                        </div>
                        <img src="<?php echo TPL_NAME;?>images/hom_h2_cover.png" width="100%">
                    </div>
                    <div class="qp_play live_play"  onmouseover="cms_hlHover(3)" onmouseout="cms_hlOut(3)">
                        <a href="javascript:;" class="qp_play_btn" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')"> 开始游戏 </a>
                    </div>
                </div>
            </div>


        </div>


    </div>

    <div class="bottom_holder">
            <div class="fs_small"> <a href="javascript:;" ><img  src="<?php echo TPL_NAME;?>images/small.jpg"  width="100%"></a> </div>

            <div class="cycle-slideshow">
                <div class="swiper-container " >
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" >
                            <a href="javascript:;">
                                <img src="<?php echo TPL_NAME;?>images/lb.jpg" alt="">
                            </a>
                        </div>
                        <div class="swiper-slide" >
                            <a href="http://www.agpromotion.net/events/mastertour_s2" target="_blank">
                                <img src="<?php echo TPL_NAME;?>images/lb2.jpg" alt="">
                            </a>
                        </div>

                    </div>
                    <!-- 如果需要分页器 -->
                    <div class="swiper-pagination"></div>
                </div>

            </div>

            <div class="fs_down">
                <img src="<?php echo TPL_NAME;?>images/down.jpg" width="100%">
                <div class="download_exe">
                    <a class="download_win_exe" target="_blank"> </a>
                    <a class="download_mac_exe" target="_blank"> </a>
                </div>

            </div>

    </div>

</div>

<script type="text/javascript">
    $(function () {

        indexCommonObj.getUserQpBanlance(uid,'ag') ;
        $('.download_win_exe').attr('href',configbase.exeWinUrl);
        $('.download_mac_exe').attr('href',configbase.macWinUrl);
        indexCommonObj.bannerSwiper();
        indexGameHeight(0.739) ;
    })
</script>