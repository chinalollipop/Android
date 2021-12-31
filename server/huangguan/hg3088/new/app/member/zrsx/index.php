<?php
// AG真人

session_start();
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
include "../include/agproxy.php";

// 判断视讯是否维护-单页面维护功能
checkMaintain('video');

$uid=$_SESSION['Oid'];
$userid = $_SESSION['userid'];
$langx=$_SESSION['langx'];
$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$Status=$_SESSION['Status'];
if ($Status==1){
exit;
}

// 1 未登录，展示游戏列表
// 2 已登录
//   检查AG账号是否已注册，
//          更新 AG（真人+电子）访问次数、访问时间
//       1） 已注册 获取AG余额
//       2） 未注册 1 先注册AG账号，2 获取AG余额
if( isset($_SESSION['Oid']) and $_SESSION['Oid'] != "" ) { // 已登录

    // 更新登录次数、最后登录时间
    $ag_sql = "select 1 from `" . DBPREFIX . "ag_users` where `userid` = '{$userid}'";
    $ag_result = mysqli_query($dbLink, $ag_sql);
    $ag_cou = mysqli_num_rows($ag_result);
    if ($ag_cou > 0) {
        $last_launch_time = date('Y-m-d H:i:s');
        $res = mysqli_query($dbMasterLink, "update " . DBPREFIX . "ag_users set launch_number = launch_number + 1, last_launch_time = '{$last_launch_time}'  WHERE userid = '{$userid}'");

    }

}
$gamerule = 'https://gci.6668ag.com/agingame/rules/new/zh/index.jsp?bid=true&vip=true&bac_db=true&bac_pairs=true&bac_superSix=true&bac_in=true&nn=true&bj=true&zjh=true&bf=true&goodRoad=true&stamp=190517_1.80_1';

?>

<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>真人视讯</title>
    <link type="text/css" rel="stylesheet" href="../../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../../../style/member/game_page_common.css?v=<?php echo AUTOVER; ?>">
    <style>
        /* jquerybox */
        .game td, .more td {color: #6e5842;}
        div.jbox .jbox-title-icon {background: url(/images/jquerybox/live.png) 90px 0 no-repeat;}
        div.jbox .jbox-title-panel {background: #4d3a2c;}

    </style>
</head>
<body>
<div class="ui-header">

    <div class="banner">
        <!-- 额度 -->
        <div class="money-tips">
            <div class="tran_live">
                <i class="money_live_logo"></i>
                额度：<span id="video_blance" ></span>
            </div>

            <a onclick="jb();">
                <i class="tran_live_logo"></i>
                额度转换
            </a>
        </div>

        <ul class="tutu">
            <li class="cur" style="display: block;"><a class=""><img src="/images/live/live_banner1.jpg" alt=""></a></li>
            <li class="" style="display: none;"><a class=""><img src="/images/live/live_banner2.jpg" alt=""></a></li>

        </ul>
        <div class="yuandian">

        </div>
    </div>
</div>

<!-- 游戏 -->
<ul class="liv_list">
    <li class="item3">
        <!-- <h2>多台</h2>
         <p>私人定制不同类型的游戏同时投注，随心所欲，多张桌枱任意切换。</p>-->
        <div class="live_all_a">
            <a class="btn" href="javascript:;" onclick="window.open('login.php?uid=<?php echo $uid;?>')">立即游戏</a>
            <a class="btn" href="<?php echo $gamerule;?>" target="_blank">游戏规则</a>
            <a class="btn" href="login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>" target="_blank">免费试玩</a>
        </div>
    </li>
    <li class="item1">
        <!--<h2>竞咪</h2>
        <p>独家首创：自主切牌。玩家不仅可以与主播进行语音文字聊天，还可以全程主导整个游戏节奏，全方位拉近玩家与现场真实游戏互动的体验。</p>-->
        <div class="live_all_a">
            <a class="btn" href="javascript:;" onclick="window.open('login.php?uid=<?php echo $uid;?>')">立即游戏</a>
            <a class="btn" href="<?php echo $gamerule;?>" target="_blank">游戏规则</a>
            <a class="btn" href="login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>" target="_blank">免费试玩</a>
        </div>
    </li>
    <li class="item2">
        <!-- <h2>包桌</h2>
         <p>VIP包桌可自行控制游戏节奏，尽享更换荷官、飞牌及换靴等优越功能，成就更高级及自主的娱乐享受。</p>-->
        <div class="live_all_a">
            <a class="btn" href="javascript:;" onclick="window.open('login.php?uid=<?php echo $uid;?>')">立即游戏</a>
            <a class="btn" href="<?php echo $gamerule;?>" target="_blank">游戏规则</a>
            <a class="btn" href="login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>" target="_blank">免费试玩</a>
        </div>
    </li>

</ul>

<!--<div class="live-main">
	    <div class="games g1">
        <a class="mask" href="javascript:;" onclick="window.open('login.php?uid=<?php /*echo $uid;*/?>')">立即游戏</a>
        <a class="rule" href="http://gci.zhenrensx777.com:81/agingame/rules/new/zh/b5.jsp?stamp=250517_208886_0&bid=true&bac_db=true&bid=true&bac_db=true&bac_in=1&ft=0&ulpk=0&nn=1&bj=1" target="_blank">游戏规则</a>
        <a class="start" href="javascript:;" onclick="window.open('login.php?uid=<?php /*echo $uid;*/?>')">立即游戏</a>
        <a class="guest" href="login.php?uid=<?php /*echo $uid;*/?>&username=<?php /*echo $test_username;*/?>" target="_blank">免费试玩</a>
	    </div>
	    <div class="games g2 r">
	        <a class="mask" href="javascript:;" onclick="window.open('login.php?uid=<?php /*echo $uid;*/?>')">立即游戏</a>
	        <a class="rule" href="http://gci.zhenrensx777.com:81/agingame/rules/new/zh/b5.jsp?stamp=250517_208886_0&bid=true&bac_db=true&bid=true&bac_db=true&bac_in=1&ft=0&ulpk=0&nn=1&bj=1" target="_blank">游戏规则</a>
	        <a class="start" href="javascript:;" onclick="window.open('login.php?uid=<?php /*echo $uid;*/?>')">立即游戏</a>
	        <a class="guest" href="login.php?uid=<?php /*echo $uid;*/?>&username=<?php /*echo $test_username;*/?>" target="_blank">免费试玩</a>

	    </div>
	    <div class="games g3">
	        <a class="mask" href="javascript:;" onclick="window.open('login.php?uid=<?php /*echo $uid;*/?>')">立即游戏</a>
	        <a class="rule" href="http://gci.zhenrensx777.com:81/agingame/rules/new/zh/b5.jsp?stamp=250517_208886_0&bid=true&bac_db=true&bid=true&bac_db=true&bac_in=1&ft=0&ulpk=0&nn=1&bj=1" target="_blank">游戏规则</a>
	        <a class="start" href="javascript:;" onclick="window.open('login.php?uid=<?php /*echo $uid;*/?>')">立即游戏</a>
	        <a class="guest" href="login.php?uid=<?php /*echo $uid;*/?>&username=<?php /*echo $test_username;*/?>" target="_blank">免费试玩</a>

	    </div>
	    <div class="games g4 r">
	        <a class="mask" href="javascript:;" onclick="window.open('login.php?uid=<?php /*echo $uid;*/?>')">立即游戏</a>
	        <a class="rule" href="http://gci.zhenrensx777.com:81/agingame/rules/new/zh/b5.jsp?stamp=250517_208886_0&bid=true&bac_db=true&bid=true&bac_db=true&bac_in=1&ft=0&ulpk=0&nn=1&bj=1" target="_blank">游戏规则</a>
	        <a class="start" href="javascript:;" onclick="window.open('login.php?uid=<?php /*echo $uid;*/?>')">立即游戏</a>
	        <a class="guest" href="login.php?uid=<?php /*echo $uid;*/?>&username=<?php /*echo $test_username;*/?>" target="_blank">免费试玩</a>
	    </div>
</div>-->

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="../../../js/jbox/jquery.jBox-zh-CN.js"></script>

<script type="text/javascript">

    var userAgents='<?php echo $_SESSION['Agents'];?>';

        get_blance(); // 获取AG余额


    function get_blance(){
        $('#video_blance').html('加载中');
        var dat={};
        dat.uid='<?php echo $uid;?>';
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'ag_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){

                    $('#video_blance').html(ret.balance_ag).attr('title',ret.balance_ag);
                }
                else{
                    $('#video_blance').html('0.00').attr('title','0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    function jb(ctr){
    	if(userAgents=='demoguest'){
    		alert("请注册真实用户！");
    	}else{
	        $.jBox('get:/app/member/zrsx/tran.php?uid=<?php echo $uid;?>&ctr='+ctr, {
	            title: "真人视讯额度转换",
	            buttons: { '关闭': true },
	            closed:function(){
	                location.reload();
	            }
	        });
    	}
    }

    top.loadag=false;

    /* 轮播*/
    function huxiFn(id){
        var iNowxx = 0;
        var timexx = null;
        var outTime=800;
        var inTime = 600;
        var stopTime = 3000;
        var yuandian = $(id).find(".yuandian")
        var tutu = $(id).find('ul.tutu')
        var len2 = tutu.children('li').length;
        for(var i=0; i<len2;i++){
            yuandian.append('<span></span>')
        }
        yuandian.children().eq(0).addClass('cur')
        var rightbtn = function(){
            if(iNowxx < len2 -1){
                tutu.children('li').eq(iNowxx).stop().fadeOut(outTime);
                iNowxx++;
                yuandian.children().eq(iNowxx).addClass('cur').siblings().removeClass('cur')
                tutu.children('li').eq(iNowxx).stop().fadeIn(inTime);
            }else{
                tutu.children('li').eq(iNowxx).stop().fadeOut(outTime);
                iNowxx= 0;
                tutu.children('li').eq(iNowxx).stop().fadeIn(inTime);
                yuandian.children().eq(iNowxx).addClass('cur').siblings().removeClass('cur')
            }
        }
        var leftbtn = function(){
            if(iNowxx > 0){
                tutu.children('li').eq(iNowxx).stop().fadeOut(outTime);
                iNowxx--;
                yuandian.children().eq(iNowxx).addClass('cur').siblings().removeClass('cur')
                tutu.children('li').eq(iNowxx).stop().fadeIn(inTime);
            }else{
                tutu.children('li').eq(iNowxx).stop().fadeOut(outTime);
                iNowxx= len2 -1;
                tutu.children('li').eq(iNowxx).stop().fadeIn(inTime);
                yuandian.children().eq(iNowxx).addClass('cur').siblings().removeClass('cur')
            }
        }
        timexx = window.setInterval(function(){
            rightbtn();
        },stopTime)
        $(id).hover(function(){
            window.clearInterval(timexx);
        },function(){
            timexx = window.setInterval(function(){
                rightbtn();
            },stopTime)
        })
        yuandian.children().click(function(){
            tutu.children('li').eq(iNowxx).fadeOut(outTime);
            iNowxx = $(this).index()
            yuandian.children().eq(iNowxx).addClass('cur').siblings().removeClass('cur')
            tutu.children('li').eq(iNowxx).fadeIn(inTime);
        })
    }


    huxiFn('.banner') ;

</script>

</body></html>
