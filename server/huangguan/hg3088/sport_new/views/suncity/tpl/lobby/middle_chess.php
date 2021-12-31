<?php
session_start();

$uid = $_SESSION['Oid'];
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$kytesturl = 'http://play.ky206.com/jump.do' ; // 开元试玩链接
$lytesturl = $_SESSION['LYTEST_PLAY_SESSION']; // 乐游试玩链接
$testuid = '3e3d444a6054eae7c22cra8' ;

?>
<style>
    .chess_mainBody{height:695px;background:url(<?php echo $tplNmaeSession;?>images/chess_bg.png) center no-repeat}
    .chess_mainBody .chess_ul{padding-top:235px}
    .chess_mainBody .chess_ul li{cursor:pointer;position:relative;float:left;width:215px;height:215px}
    .chess_mainBody .chess_ul li .li_bg{transition:.5s;position:absolute;width:100%;height:100%;background:url(<?php echo $tplNmaeSession;?>images/qp_bg.png) center no-repeat}
    .chess_mainBody .chess_ul li:hover .li_bg{transform:rotate(90deg);background:url(<?php echo $tplNmaeSession;?>images/qp_bg_hover.png) center no-repeat}
    .chess_mainBody .chess_ul li .title{position:absolute;width:100%}
    .chess_mainBody .chess_ul li .img{display:block;width:100px;height:53px;margin:42px auto 0}
    .chess_mainBody .chess_ul li.ky_li .img{background:url(<?php echo $tplNmaeSession;?>images/qp1.png) center no-repeat}
    .chess_mainBody .chess_ul li.ly_li .img{background:url(<?php echo $tplNmaeSession;?>images/qp2.png) center no-repeat}
    .chess_mainBody .chess_ul li.vg_li .img{background:url(<?php echo $tplNmaeSession;?>images/qp3.png) center no-repeat}
    .chess_mainBody .chess_ul li.hg_li .img{background:url(<?php echo $tplNmaeSession;?>images/qp4.png) center no-repeat}
    .chess_mainBody .chess_ul li.fg_li .img{background:url(<?php echo $tplNmaeSession;?>images/qp5.png) center no-repeat}
    .chess_mainBody .chess_ul li.mt_li .img{background:url(<?php echo $tplNmaeSession;?>images/qp6.png) center no-repeat}
    .chess_mainBody .chess_ul li.mw_li .img{background:url(<?php echo $tplNmaeSession;?>images/qp7.png) center no-repeat}
    .chess_mainBody .chess_ul li.hlqp_li .img{background:url(<?php echo $tplNmaeSession;?>images/qp8.png) center no-repeat}
    .chess_mainBody .chess_ul li .title p{text-align:center;line-height:38px}
    .chess_mainBody .chess_ul li .zh_title{font-size:20px}
    .chess_mainBody .chess_ul li .en_title{color:#805645}
    .chess_mainBody .chess_ul li .chess_btn{display:none;position:absolute;transition:.5s;width:100%;height:110px;padding-top:48px}
    .chess_mainBody .chess_ul li:hover .chess_btn{display:block}
    .chess_mainBody .chess_ul li .chess_btn a{display:block;transition:.3s;text-align:center;width:109px;height:35px;line-height:35px;background:url(<?php echo $tplNmaeSession;?>images/chess_play.png) center no-repeat;margin:15px auto}
    .chess_mainBody .chess_ul li .chess_btn a.test{background:url(<?php echo $tplNmaeSession;?>images/chess_test.png) center no-repeat}
    .chess_mainBody .chess_ul li .chess_btn a:hover{opacity: .8;}
    .chess_mainBody .chess_ul li .chess_btn a.qd{background: #000;margin-top: 38px;color: #afafaf;border-radius: 20px;}
</style>

<div class="mainBody pr chess_mainBody">
    <div class="warp clearfix" style="width: 868px;">
        <ul class="chess_ul">
            <li class="ky_li">
                <div class="li_bg"></div>
                <div class="title">
                    <span class="img"></span>
                    <p class="zh_title">开元棋牌</p>
                    <p class="en_title">KY CARDS</p>
                </div>
                <div class="chess_btn">
                    <a href="javascript:;" title="免费试玩" class="test" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $kytesturl;?>')"></a>
                    <a href="javascript:;" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')"></a>
                </div>
            </li>
            <li class="ly_li">
                <div class="li_bg"></div>
                <div class="title">
                    <span class="img"></span>
                    <p class="zh_title">LEG棋牌</p>
                    <p class="en_title">LEG CARDS</p>
                </div>
                <div class="chess_btn">
                    <a href="javascript:;" title="免费试玩" class="test" onclick="indexCommonObj.openGameCommon(this,'<?php echo $testuid;?>','<?php echo $lytesturl;?>')"></a>
                    <a href="javascript:;" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')"></a>
                </div>
            </li>
            <li class="vg_li">
                <div class="li_bg"></div>
                <div class="title">
                <span class="img"></span>
                <p class="zh_title">VG棋牌</p>
                <p class="en_title">VG CARDS</p>
                    </div>
                <div class="chess_btn">
                    <a href="javascript:;" title="免费试玩" class="test" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?flag=test&uid=<?php echo $uid;?>')"></a>
                    <a href="javascript:;" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')"></a>
                </div>
            </li>
            <!--<li class="hg_li">
                <div class="li_bg"></div>
                <div class="title">
                    <span class="img"></span>
                    <p class="zh_title">HG棋牌</p>
                    <p class="en_title">HG CARDS</p>
                </div>
                <div class="chess_btn">
                    <a href="javascript:;" title="免费试玩" class="test" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?flag=test&uid=<?php /*echo $uid;*/?>')"></a>
                    <a href="javascript:;" title="开始游戏" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')"></a>
                </div>
            </li>-->
            <li class="fg_li">
                <div class="li_bg"></div>
                <div class="title">
                    <span class="img"></span>
                    <p class="zh_title">FG棋牌</p>
                    <p class="en_title">FG CARDS</p>
                </div>
                <div class="chess_btn">
                    <a class="qd"> 敬请期待</a>
                </div>
            </li>
            <li class="mt_li">
                <div class="li_bg"></div>
                <div class="title">
                    <span class="img"></span>
                    <p class="zh_title">MT棋牌</p>
                    <p class="en_title">MT CARDS</p>
                </div>
                <div class="chess_btn">
                    <a class="qd"> 敬请期待</a>
                </div>
            </li>
            <li class="mw_li">
                <div class="li_bg"></div>
                <div class="title">
                    <span class="img"></span>
                    <p class="zh_title">MW棋牌</p>
                    <p class="en_title">MW CARDS</p>
                </div>
                <div class="chess_btn">
                    <a class="qd"> 敬请期待</a>
                </div>
            </li>
            <li class="hlqp_li">
                <div class="li_bg"></div>
                <div class="title">
                    <span class="img"></span>
                    <p class="zh_title">HLQP棋牌</p>
                    <p class="en_title">HLQP CARDS</p>
                </div>
                <div class="chess_btn">
                    <a class="qd"> 敬请期待</a>
                </div>
            </li>

        </ul>

    </div>

    <div class="noticeContent">
        <div class="w_1200">
            <span></span>
            <marquee behavior="" direction="">
                <?php echo $_SESSION['memberNotice']; ?>
            </marquee>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {

    })
</script>