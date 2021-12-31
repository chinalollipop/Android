<?php
session_start();

$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid'];
$kytesturl = 'http://play.ky206.com/jump.do' ; // 开元试玩链接
$lytesturl = $_SESSION['LYTEST_PLAY_SESSION']; // 乐游试玩链接
$testuid = '3e3d444a6054eae7c22cra8' ;

?>


<div class="container">
    <div>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bottom_holder">
            <tbody>
            <tr>
                <td>
                    <a ><img  src="<?php echo $tplNmaeSession;?>images/chess.jpg" width="100%"></a>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="upper_holder" >

            <div class="upmain" >
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(1)" onmouseout="cms_hlOut(1)">
                        <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox1" style="background-image: url(<?php echo $tplNmaeSession;?>images/c1.jpg); ">
                        <div class="cms_hl_txt" id="cms_hlt1">
                            <div class="cms_hlt1">开元棋牌</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2">棋牌游戏</div>
                        </div>
                        <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%">

                    </div>
                    <div class="qp_play"  onmouseover="cms_hlHover(1)" onmouseout="cms_hlOut(1)">
                        <a href="javascript:;" data-testplay="yes" class="qp_play_btn" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')"></a>
                        <a href="javascript:;" class="qp_testplay_btn" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $kytesturl;?>')"></a>
                    </div>

                </div>
            </div>


            <div class="upmain" >
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(2)" onmouseout="cms_hlOut(2)">
                        <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox2" style="background-image: url(<?php echo $tplNmaeSession;?>images/c2.jpg); " >
                        <div class="cms_hl_txt" id="cms_hlt2">
                            <div class="cms_hlt1">乐游棋牌</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2">棋牌游戏</div>
                        </div>
                        <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%">
                    </div>
                    <div class="qp_play"  onmouseover="cms_hlHover(1)" onmouseout="cms_hlOut(1)">
                        <a href="javascript:;" data-testplay="yes" class="qp_play_btn" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')"></a>
                        <a href="javascript:;" class="qp_testplay_btn" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $lytesturl;?>')"></a>
                    </div>
                </div>
            </div>


            <div class="upmain" >
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(3)" onmouseout="cms_hlOut(3)">
                        <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox3" style="background-image: url(<?php echo $tplNmaeSession;?>images/c3.jpg); " >
                        <div class="cms_hl_txt" id="cms_hlt3">
                            <div class="cms_hlt1">快乐棋牌</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2">棋牌游戏</div>
                        </div>
                        <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%">
                    </div>
                    <div class="qp_play"  onmouseover="cms_hlHover(1)" onmouseout="cms_hlOut(1)">
                        <a href="javascript:;" class="qp_play_btn" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/klqp/index.php?uid=<?php echo $uid;?>')"></a>
                        <!--<a href="javascript:;" class="qp_testplay_btn" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','http://tstqpint.z389.com/?c=default&a=playGame&gameId=100')"></a>-->
                    </div>
                </div>
            </div>


            <div class="upmain">
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(4)" onmouseout="cms_hlOut(4)">
                        <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox4" style="background-image: url(<?php echo $tplNmaeSession;?>images/c4.jpg); " >
                        <div class="cms_hl_txt" id="cms_hlt4">
                            <div class="cms_hlt1">VG棋牌</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2">棋牌游戏</div>
                        </div>
                        <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%">
                    </div>
                    <div class="qp_play"  onmouseover="cms_hlHover(1)" onmouseout="cms_hlOut(1)">
                        <a href="javascript:;" class="qp_play_btn" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')"></a>
                        <a href="javascript:;" class="qp_testplay_btn" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?flag=test&uid=<?php echo $uid;?>')"></a>
                    </div>
                </div>
            </div>


            <div class="upmain">
                <div class="cms_hlredbg">
                    <div class="cms_active_hl" onmouseover="cms_hlHover(5)" onmouseout="cms_hlOut(5)">
                        <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%"></div>
                    <div class="cms_hl_box" id="cms_hlbox5" style="background-image: url(<?php echo $tplNmaeSession;?>images/c5.jpg); " >
                        <div class="cms_hl_txt" id="cms_hlt5">
                            <div class="cms_hlt1">更多精彩</div>
                            <div class="cms_hl_moveline" ></div>
                            <div class="cms_hlt2">敬请期待</div>
                        </div>
                        <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%">
                    </div>
                </div>
            </div>

        </div>




    </div>



</div>

<script type="text/javascript">
    $(function () {
        indexGameHeight(0.995) ;
    })
</script>