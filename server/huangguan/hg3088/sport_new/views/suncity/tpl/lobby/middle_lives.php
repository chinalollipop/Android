<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];

$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

?>
<style>
    .game_banner{height:295px;background:url(<?php echo TPL_NAME;?>images/zr_banner.jpg) center no-repeat}
    .live_mainBody{height:667px;background:url(<?php echo TPL_NAME;?>images/live_con_bg.jpg) center no-repeat}
    .live_mainBody ul{background:#212025;margin:10px 0;padding:15px 35px;overflow:hidden}
    .live_mainBody ul li{position:relative;cursor:pointer;width:242px;height:308px;float:left;margin-right:40px}
    .live_mainBody ul li:hover:before{transition:.3s;display:inline-block;content:'';width:242px;height:308px;background:url(<?php echo TPL_NAME;?>images/live_bg.png) top center no-repeat}
    .live_mainBody ul li:hover p{transition:.3s;color:#2b1905}
    .live_mainBody ul li:hover:after{background-position:0 -52px}
    .live_mainBody ul li:nth-child(4n){margin-right:0}
    .live_mainBody ul li:after{position:absolute;display:inline-block;content:'';width:50px;height:50px;background:url(<?php echo TPL_NAME;?>images/live_btn.png) no-repeat;bottom:15px;right:10px}
    .live_mainBody ul li:last-child:after{display:none}
    .live_mainBody ul li.live_1{background:url(<?php echo TPL_NAME;?>images/live_1.png?v=1) top center no-repeat}
    .live_mainBody ul li.live_2{background:url(<?php echo TPL_NAME;?>images/live_2.png) top center no-repeat}
    .live_mainBody ul li.live_3{background:url(<?php echo TPL_NAME;?>images/live_3.png) top center no-repeat}
    .live_mainBody ul li.live_4{background:url(<?php echo TPL_NAME;?>images/live_4_off.png) top center no-repeat}
    .live_mainBody ul li.live_5{background:url(<?php echo TPL_NAME;?>images/live_5.png) top center no-repeat}
    .live_mainBody ul li.live_6{background:url(<?php echo TPL_NAME;?>images/live_6.png) top center no-repeat}
    .live_mainBody ul li.live_7{background:url(<?php echo TPL_NAME;?>images/live_7.png) top center no-repeat}
    .live_mainBody ul li.live_8{background:url(<?php echo TPL_NAME;?>images/live_more.png) top center no-repeat}
    .live_mainBody ul li p{position:absolute;bottom:12px;font-size:20px;padding-left:30px}

</style>

<div class="game_banner">

        <div class="noticeContent">
            <div class="w_1200">
                <span></span>
                <marquee behavior="" direction="">
                    <?php echo $_SESSION['memberNotice']; ?>
                </marquee>
            </div>
        </div>

</div>

<div class="mainBody pr live_mainBody">
    <div class="w_1160 clearfix">
        <ul>
            <li class="live_1" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>')">
                <div class="img"></div>
                <p>AG视讯</p>
            </li>
            <li class="live_3" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">
                <div class="img"></div>
                <p>OG视讯</p>
            </li>
            <li class="live_2" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')">
                <div class="img"></div>
                <p>BBIN视讯</p>
            </li>

            <li class="live_4">
                <div class="img"></div>
                <p>敬请期待</p>
            </li>
            <li class="live_5">
                <div class="img"></div>
                <p>敬请期待</p>
            </li>
            <li class="live_6">
                <div class="img"></div>
                <p>敬请期待</p>
            </li>
            <li class="live_7">
                <div class="img"></div>
                <p>敬请期待</p>
            </li>
            <li class="live_8">
                <div class="img"></div>
            </li>

        </ul>

    </div>
</div>



<script type="text/javascript">
    $(function () {

        indexCommonObj.getUserQpBanlance(uid,'ag') ;

    })
</script>