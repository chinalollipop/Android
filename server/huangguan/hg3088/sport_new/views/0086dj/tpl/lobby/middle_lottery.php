<?php
session_start();

include "../../../../app/member/include/config.inc.php";

//  单页面维护功能
checkMaintain('lottery');

$cpUrl = $_SESSION['LotteryUrl'];
$uid = $_SESSION['Oid']; // 判断是否已登录


?>

<div class="container" >
    <div >
        <div class="upper_holder">

            <div class="upmain" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')" >
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(1)" onmouseout="cms_hlOut(1)"
                         onclick="clickbox(1);"><img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox1" style="background-image: url(<?php echo TPL_NAME;?>images/l1.jpg);">
                        <div class="cms_hl_txt" id="cms_hlt1">
                            <div class="cms_hlt1" >欢乐生肖</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2" >彩票游戏</div>
                        </div>
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%">
                    </div>
                </div>
            </div>


            <div class="upmain" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')">
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(2)" onmouseout="cms_hlOut(2)">
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox2" style="background-image: url(<?php echo TPL_NAME;?>images/l2.jpg);" >
                        <div class="cms_hl_txt" id="cms_hlt2">
                            <div class="cms_hlt1" >北京赛车（pk10）</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2" >彩票游戏</div>
                        </div>
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%">
                    </div>
                </div>
            </div>


            <div class="upmain" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')">
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(3)" onmouseout="cms_hlOut(3)">
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox3" style="background-image: url(<?php echo TPL_NAME;?>images/l3.jpg);" >
                        <div class="cms_hl_txt" id="cms_hlt3">
                            <div class="cms_hlt1" >极速赛车</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2" >彩票游戏</div>
                        </div>
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%">
                    </div>
                </div>
            </div>


            <div class="upmain" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')">
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(4)" onmouseout="cms_hlOut(4)">
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox4" style="background-image: url(<?php echo TPL_NAME;?>images/l4.jpg);" >
                        <div class="cms_hl_txt" id="cms_hlt4">
                            <div class="cms_hlt1" >分分彩</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2" >彩票游戏</div>
                        </div>
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%">
                    </div>
                </div>
            </div>


            <div class="upmain" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')">
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(5)" onmouseout="cms_hlOut(5)">
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox5"
                         style="background-image: url(<?php echo TPL_NAME;?>images/l5.jpg);">
                        <div class="cms_hl_txt" id="cms_hlt5">
                            <div class="cms_hlt1" >三分彩</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2" >彩票游戏</div>
                        </div>
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%">
                    </div>
                </div>
            </div>

            <div class="upmain" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')">
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(6)" onmouseout="cms_hlOut(6)">
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox6" style="background-image: url(<?php echo TPL_NAME;?>images/l6.jpg);" >
                        <div class="cms_hl_txt" id="cms_hlt6">
                            <div class="cms_hlt1" >更多游戏</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2" >彩票游戏</div>
                        </div>
                        <img src="<?php echo TPL_NAME;?>images/hom_hl_cover.png" width="100%">
                    </div>
                </div>
            </div>

        </div>


    </div>

    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bottom_holder">
        <tbody>
        <tr>
            <td>
                <a href="javascript:;"><img  src="<?php echo TPL_NAME;?>images/h1.png" width="100%"></a>
            </td>

            <td >
                <a href="javascript:;"><img src="<?php echo TPL_NAME;?>images/h2.png" width="100%"></a>
            </td>
        </tr>
        </tbody>
    </table>

</div>

<script type="text/javascript">
    $(function () {
        indexGameHeight(1.481) ;
        
    })
</script>