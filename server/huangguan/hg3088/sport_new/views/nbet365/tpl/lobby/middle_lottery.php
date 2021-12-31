<?php
session_start();

include "../../../../app/member/include/config.inc.php";

//  单页面维护功能
checkMaintain('lottery');

$cpUrl = $_SESSION['LotteryUrl'];
$uid = $_SESSION['Oid']; // 判断是否已登录


?>
<style>
    .cpcontent{height: 802px;background: url(<?php echo TPL_NAME;?>images/lottery/tt.jpg?v=1) no-repeat center top;}

    /*2019-11-25 new version*/
    .cp_xy{width: 333px; height: 600px; float: left;position: relative;}
    .cp_logo{ padding: 2px; display: block; position: absolute;bottom: 350px; right: 40px;z-index: 30; }

    /*左右移动 */
    .cp_rw{-webkit-animation: twinkling 5s infinite ease-in-out; }
    .cp_moveup{-webkit-animation: moveup 5s infinite ease-in-out; }
    @-webkit-keyframes twinkling{
        0% {
            transform: translateX(0px);
        }
        50% {
            transform: translateX(30px);
        }
        100%{
            transform: translateX(0px);
        }
    }
    @keyframes twinkling{
        0% {
            transform: translateX(0px);
        }
        50% {
            transform: translateX(20px);
        }
        100%{
            transform: translateX(0px);
        }
    }
    @-webkit-keyframes moveup{
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(30px);
        }
        100%{
            transform: translateY(0px);
        }
    }
    @keyframes moveup{
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(20px);
        }
        100%{
            transform: translateY(0px);
        }
    }
    a.cp_lj{transition: .3s; display: block;width: 297px; height: 87px;position: absolute;background: url(<?php echo TPL_NAME;?>images/lottery/an.png?v=1) center; bottom: 50px; left: 70px; z-index: 14; }
    a.cp_lj:hover{background: url(<?php echo TPL_NAME;?>images/lottery/an2.png?v=1) center;}
   .cp_right{width: 600px;height: 100%;float: right;position: relative}
   .cp_right span{display:inline-block;width: 156px;height: 156px;position: absolute;background: url(<?php echo TPL_NAME;?>images/lottery/ball.png) center no-repeat;left: 430px;top:40px;}
   .cp_right span.ball_2{background-image: url(<?php echo TPL_NAME;?>images/lottery/ball2.png);left: 100px;top: 60px;}
   .cp_right span.ball_3{background-image: url(<?php echo TPL_NAME;?>images/lottery/ball3.png);left: 320px;top: 610px;}

</style>

<div class="lottery_main">

<div id="sidebarwrap">
    <div class="cpcontent">
        <div style=" width: 1000px;margin:0px auto; ">
            <div class="cp_xy">
                <div class="cp_logo"><img src="<?php echo TPL_NAME;?>images/lottery/ttlogo.png?v=1"></div>
                <a href="javascript:;" class="cp_lj" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')">

                </a>
            </div>
            <div class="cp_right">
                <span class="ball_1 cp_rw"></span>
                <span class="ball_2 cp_moveup"></span>
                <span class="ball_3 cp_rw"></span>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">
    $(function () {

    })
</script>