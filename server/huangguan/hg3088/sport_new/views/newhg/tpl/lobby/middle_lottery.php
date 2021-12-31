<?php
session_start();

include "../../../../app/member/include/config.inc.php";

//  单页面维护功能
checkMaintain('thirdcp');

$cpUrl = $_SESSION['LotteryUrl'];
$uid = $_SESSION['Oid']; // 判断是否已登录


?>
<style>
    .lottery_wrapper{background:url(<?php echo TPL_NAME;?>images/lottery/lottery_bg.jpg) top center no-repeat;height: 76%;background-size: cover;position:relative}
    .lottery_wrapper .left_women{float:left}
    .lottery_wrapper .left_women .zijia_women{height:100%}
    .lottery_wrapper .left_women img{width:44%;position:absolute;bottom:0}
    .lottery_wrapper .platform_box{width:100%}

    .lottery_wrapper .platform_box .lottery_platform li{display:inline-block;width:193px;height:86px;border-radius:15px;border:1px solid #666768;text-align:center;margin-right:57px}
    .lottery_wrapper .platform_box .lottery_platform li:hover .platform_logo{background-position-y:-53px}
    .lottery_wrapper .platform_box .lottery_platform li[data-v-5b24bf1f]:last-child{margin-right:0}
    .lottery_wrapper .platform_box .lottery_platform li .platform_name{font-size:25px;line-height:23px;color:#9d9d9d}
    .lottery_wrapper .platform_box .lottery_platform li .menhuan{line-height:40px;font-size:19px;color:#9d9d9d;}
    .lottery_wrapper .platform_box .lottery_platform li .platform_logo{height:40px;text-align:center;margin-bottom:5px;margin-top:6px;background-repeat:no-repeat;background-position-x:center}
    .lottery_wrapper .platform_box .lottery_platform li .platform_logo img{height:100%}
    .lottery_wrapper .right_content{float:right;margin-right:100px;text-align:center}
    .lottery_wrapper .right_content .my_lottery_wrapper{width:56%;margin-top:80px;position:relative;z-index:1;float:right}
    .lottery_wrapper .right_content .my_lottery_wrapper .my_lottery_box{width:100%}
    .lottery_wrapper .right_content .my_lottery_wrapper .my_lottery_box li{color:#333;text-align:center;width:14%;float:left;margin-right:3%;margin-bottom:36px;min-height:140px}
    .lottery_wrapper .right_content .my_lottery_wrapper .my_lottery_box li img{width:100%;margin-bottom:13px;border-radius:50%}
    .lottery_wrapper .right_content .my_lottery_wrapper .my_lottery_box li img:hover{animation:abc-data-v-5b24bf1f 1s linear infinite;-moz-animation:abc-data-v-5b24bf1f 1s infinite linear;-webkit-animation:abc-data-v-5b24bf1f 1s linear infinite;-o-animation:abc-data-v-5b24bf1f 1s infinite linear}
    .lottery_wrapper .right_content .my_lottery_wrapper .my_lottery_box li:nth-child(6n){margin-right:0}

    @-webkit-keyframes abc-data-v-5b24bf1f{
        0%{-webkit-box-shadow:-1px 2px 13px 3px #2652ba;box-shadow:-1px 2px 13px 3px #2652ba}
        50%{-webkit-box-shadow:0 0 0 0 #2652ba;box-shadow:0 0 0 0 #2652ba}
        to{-webkit-box-shadow:-1px 2px 13px 3px #2652ba;box-shadow:-1px 2px 13px 3px #2652ba}
    }
    @keyframes abc-data-v-5b24bf1f {
        0% {
            -webkit-box-shadow: -1px 2px 13px 3px #2652ba;
            box-shadow: -1px 2px 13px 3px #2652ba
        }
        50% {
            -webkit-box-shadow: 0 0 0 0 #2652ba;
            box-shadow: 0 0 0 0 #2652ba
        }
        to {
            -webkit-box-shadow: -1px 2px 13px 3px #2652ba;
            box-shadow: -1px 2px 13px 3px #2652ba
        }
    }

</style>

<div class="lottery_wrapper cl router_view_mian" >
    <div class="left_women">
        <img src="<?php echo TPL_NAME;?>images/lottery/lottery_rw.png"
             alt="" class="zijia_women">
        <div style="display: none;">
        </div>
    </div>
    <div class="platform_box cl"></div>
    <div class="right_content">
        <div>
            <div class="my_lottery_wrapper">
                <ul class="my_lottery_box cl">

                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="7">
                        <div>
                            <img src="<?php echo TPL_NAME;?>images/lottery/cqssc.png" alt="">
                        </div>
                        <p>
                            重庆时时彩
                        </p>
                    </li>

                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="76">
                        <div>
                            <img src="<?php echo TPL_NAME;?>images/lottery/bjpk10.png" alt="">
                        </div>
                        <p>
                            北京赛车
                        </p>
                    </li>
                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="70">
                        <div>
                            <img src="<?php echo TPL_NAME;?>images/lottery/xglhc.png" alt="">
                        </div>
                        <p>
                            六合彩
                        </p>
                    </li>
                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="51">
                        <div>
                            <img src="<?php echo TPL_NAME;?>images/lottery/jspk10.png" alt="">
                        </div>
                        <p>
                            极速赛车
                        </p>
                    </li>

                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="72">
                        <div>
                            <img src="<?php echo TPL_NAME;?>images/lottery/wflhc.png" alt="">
                        </div>
                        <p>
                            五分六合彩
                        </p>
                    </li>
                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="2">
                        <div>
                            <img src="<?php echo TPL_NAME;?>images/lottery/ffc.png" alt="">
                        </div>
                        <p>
                            分分彩
                        </p>
                    </li>
                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="76">
                        <div>
                            <img src="<?php echo TPL_NAME;?>images/lottery/wfc.png" alt="">
                        </div>
                        <p>
                            五分彩
                        </p>
                    </li>
                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="61">
                        <div>
                            <img src="<?php echo TPL_NAME;?>images/lottery/cqklsf.png" alt="">
                        </div>
                        <p>
                            重庆快乐十分
                        </p>
                    </li>

                </ul>
            </div>
        </div>
    </div>

</div>


<script type="text/javascript">
    $(function () {

        
    })
</script>