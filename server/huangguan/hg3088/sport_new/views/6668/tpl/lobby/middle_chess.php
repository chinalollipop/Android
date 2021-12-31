<?php
session_start();

$uid = $_SESSION['Oid'];
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$kytesturl = 'http://play.ky206.com/jump.do' ; // 开元试玩链接
$lytesturl = $_SESSION['LYTEST_PLAY_SESSION']; // 乐游试玩链接
$testuid = '3e3d444a6054eae7c22cra8' ;

?>
<style>
    .chess_bg{width:100%;height:930px;background:url("<?php echo $tplNmaeSession;?>images/chess/chess_bg.jpg") no-repeat center center;position:relative}
    .animation{position:absolute;top:-110px;left:30%;width:100%;margin-left:-530px;animation:floaty4 ease-in-out 3s 0s infinite forwards}
    .chess_choose_box{width:1200px;margin-top:550px;position:absolute;z-index:999999;left:23%}
    .chess_choose_box ul li{float:left;margin-right:23px;transition:all 0.3s}
    .chess_choose_box ul li:hover{transform:scale(1.1)}
    .chess_choose_box ul li .choose_icon{width:187px;height:178px;background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon1.png") no-repeat}
    .chess_choose_box ul li:nth-child(2) .choose_icon{width:187px;height:178px;background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon2.png") no-repeat}
    .chess_choose_box ul li:nth-child(3) .choose_icon{width:187px;height:178px;background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon_kl.png") no-repeat}
    .chess_choose_box ul li:nth-child(4) .choose_icon{width:187px;height:178px;background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon4.png") no-repeat}
    .chess_choose_box ul li:nth-child(5) .choose_icon{width:187px;height:178px;background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon5.png") no-repeat}
    .btn_chess{text-align:center;background:#E8BE48;color:#d02918;width:120px;height:35px;line-height:35px;margin:0 auto;margin-top:15px;border-radius:7px;font-weight:600;font-size:16px;cursor:pointer}
    .btn_chess_qh{background:#7953C4;color:#ceb5ff}
    .chess_choose_box ul li:hover .btn_chess{color:#ff1800;background:#fbe050}
    .chess_choose_box ul li:hover .btn_chess_qh{background:#9568ec;color:#fff}
    .chess_choose_box ul li:hover .choose_icon.choose_icon_ky{background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon_bg1.png") no-repeat}
    .chess_choose_box ul li:hover .choose_icon.choose_icon_ly{background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon_bg2.png") no-repeat}
    .chess_choose_box ul li:hover .choose_icon.choose_icon_hg{background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon_bg3.png") no-repeat}
    .chess_choose_box ul li:hover .choose_icon.choose_icon_vg{background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon_bg4.png") no-repeat}
    .chess_choose_box ul li:hover .choose_icon.choose_icon_qd{background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon_bg5.png") no-repeat}
    .chess_choose_box ul li:hover .choose_icon.choose_icon_kl{background:url("<?php echo $tplNmaeSession;?>images/chess/chess_icon_kl_hover.png") no-repeat}
</style>
<div class="chess_box">
    <div class="chess_bg">
        <div class="wrap clearfix">
            <div class="chess_choose_box">
                <ul>
                    <li>
                        <div class="choose_icon choose_icon_ky"></div>
                        <div class="btn_chess btn_chess_ks" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/ky/index.php?uid=<?php echo $uid;?>')">开始游戏</div>
                        <div class="btn_chess btn_chess_qh btn_chess_sw" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $kytesturl;?>')">免费试玩</div>
                    </li>
                    <li>
                        <div class="choose_icon choose_icon_ly"></div>
                        <div class="btn_chess btn_chess_ks" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/lyqp/index.php?uid=<?php echo $uid;?>')">开始游戏</div>
                        <div class="btn_chess btn_chess_qh btn_chess_sw" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $lytesturl;?>')">免费试玩</div>
                    </li>
                   <!-- <li>
                        <div class="choose_icon choose_icon_hg"></div>
                        <div class="btn_chess btn_chess_ks" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','/app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')">开始游戏</div>
                        <div class="btn_chess btn_chess_qh btn_chess_sw" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','/app/member/hgqp/index.php?flag=test&uid=<?php /*echo $uid;*/?>')">免费试玩</div>
                    </li>-->
                    <li>
                        <div class="choose_icon choose_icon_kl"></div>
                        <div class="btn_chess btn_chess_ks" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/klqp/index.php?uid=<?php echo $uid;?>')">开始游戏</div>
                        <!--<div class="btn_chess btn_chess_qh btn_chess_sw" title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','http://tstqpint.z389.com/?c=default&a=playGame&gameId=100')">免费试玩</div>-->
                    </li>
                    <li>
                        <div class="choose_icon choose_icon_vg"></div>
                        <div class="btn_chess btn_chess_ks"　title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/vgqp/index.php?uid=<?php echo $uid;?>')">开始游戏</div>
                        <div class="btn_chess btn_chess_qh btn_chess_sw"　title="免费试玩" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/vgqp/index.php?flag=test&uid=<?php echo $uid;?>')">免费试玩</div>
                    </li>
                    <li>
                        <div class="choose_icon choose_icon_qd"></div>
                        <div class="btn_chess btn_chess_ks" onclick="layer.msg('敬请期待!',{time:alertTime});">开始游戏</div>
                        <div class="btn_chess btn_chess_qh btn_chess_sw" onclick="layer.msg('敬请期待!',{time:alertTime});">免费试玩</div>
                    </li>
                </ul>
                <div style="clear: both"></div>
            </div>
            <img class="animation" src="<?php echo $tplNmaeSession;?>images/chess/chess_animation.png" alt="">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        indexGameHeight(0.995) ;
    })
</script>