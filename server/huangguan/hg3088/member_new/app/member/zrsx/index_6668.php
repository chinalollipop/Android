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

$uid=$_SESSION['Oid'];
$userid = $_SESSION['userid'];
$langx=$_SESSION['langx'];
$test_username = explode('_',$_SESSION['AGSX_INIT_SESSION']['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>top.location.href='/tpl/logout_warn.html';</script>";
	exit;
}

$Status=$_SESSION['Status'];
if ($Status==1){
exit;
}

$gamerule = 'https://gci.6668ag.com/agingame/rules/new/zh/index.jsp?bid=true&vip=true&bac_db=true&bac_pairs=true&bac_superSix=true&bac_in=true&nn=true&bj=true&zjh=true&bf=true&goodRoad=true&stamp=190517_1.80_1';
$og_game_rule_url = "http://video.n80tu2.com/game/rules/ch/index.html";
$bbin_game_rule_url = "https://777.boeingbc.com/infe/rule/sport/rule";
?>
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>真人视讯</title>
    <link type="text/css" rel="stylesheet" href="../../../style/member/jbox_skin2.css?v=<?php echo $_SESSION['AUTOVER_SESSION']; ?>">
    <link type="text/css" rel="stylesheet" href="../../../style/member/game_page_common.css?v=<?php echo $_SESSION['AUTOVER_SESSION']; ?>">

<style>
    /* jquerybox */
    .game td, .more td {color: #6e5842;}
    div.jbox .jbox-title-icon {background: url(/images/jquerybox/live.png) 90px 0 no-repeat;}
    div.jbox .jbox-title-panel {background: #4d3a2c;}
    .money-tips{ width: 100%;margin-top: 260px;margin-left: -13px;}
    .liv_list li{width: 29%;}
    div.jbox{top: 20% !important;}
</style>
</head>
<body>

<div class="ui-header">

    <div class="banner">
        <ul class="tutu">
            <li class="cur" style="display: block;"><a class=""><img src="/images/member/2018/live/live_banner1.jpg" alt=""></a></li>
            <li class="" style="display: none;"><a class=""><img src="/images/member/2018/live/live_banner2.jpg" alt=""></a></li>

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
            <a class="btn" href="javascript:;" onclick="window.open('login.php?type=aglive&uid=<?php echo $uid;?>')">立即游戏</a>
            <a class="btn" href="<?php echo $gamerule;?>" target="_blank">游戏规则</a>
            <a class="btn" href="login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>" target="_blank">免费试玩</a>
        </div>
        <!-- 额度 -->
        <div class="money-tips">
            <div class="tran_live">
                <!--<i class="money_live_logo"></i>-->
                额度：<span id="ag_blance" >0.00</span>
            </div>

            <a class="tran_btn" onclick="jb_ag();">
                <i class="tran_live_logo"></i>
                额度转换
            </a>
        </div>
    </li>
    <li class="item1">
        <!--<h2>竞咪</h2>
        <p>独家首创：自主切牌。玩家不仅可以与主播进行语音文字聊天，还可以全程主导整个游戏节奏，全方位拉近玩家与现场真实游戏互动的体验。</p>-->
        <div class="live_all_a">
            <a class="btn" href="javascript:;" onclick="window.open('og/login.php?uid=<?php echo $uid;?>')">立即游戏</a>
            <a class="btn" href="<?php echo $og_game_rule_url;?>" target="_blank">游戏规则</a>
        </div>

        <div class="money-tips" >
            <div class="tran_live">
                <!--<i class="money_live_logo"></i>-->
                额度：<span id="og_blance" >0.00</span>
            </div>

            <a class="tran_btn" onclick="jb_og();" >
                <i class="tran_live_logo"></i>
                额度转换
            </a>
        </div>

    </li>
    <!--<li class="item2">
        <div class="live_all_a">
            <a class="btn" href="javascript:;" onclick="">立即游戏</a>
            <a class="btn" href="javascript:;" target="_blank">游戏规则</a>
        </div>
        <div class="money-tips" >
            <div class="tran_live">
                额度：<span id="og_blance" >0.00</span>
            </div>

            <a class="tran_btn" onclick="jb_og();" >
                <i class="tran_live_logo"></i>
                额度转换
            </a>
        </div>
    </li>-->
    <li class="item4">
        <div class="live_all_a">
            <a class="btn" href="javascript:;" onclick="window.open('bbin/login.php?uid=<?php echo $uid;?>')">立即游戏</a>
            <a class="btn" href="javascript:;" onclick="alert('请进入游戏查看详情')">游戏规则</a>
        </div>
        <div class="money-tips" >
            <div class="tran_live">
                <!--<i class="money_live_logo"></i>-->
                额度：<span id="bbin_blance" >0.00</span>
            </div>

            <a class="tran_btn" onclick="jb_bbin();" >
                <i class="tran_live_logo"></i>
                额度转换
            </a>
        </div>
    </li>

</ul>


<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="../../../js/jbox/jquery.jBox-zh-CN.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo $_SESSION['AUTOVER_SESSION']; ?>"></script>
<script type="text/javascript">

    var userAgents='<?php echo $_SESSION['Agents'];?>';

    get_ag_blance(); // 获取AG余额
    get_og_blance(); // 获取OG余额
    get_bbin_blance(); // 获取BBIN余额


    // 第一次进入游戏页面，创建账号
    function create_game_account() {
        var dat={};
        dat.uid='<?php echo $uid;?>';
        dat.action='cga';
        $.ajax({
            type: 'POST',
            url:'ag_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err == 0){
                    alert("AG游戏账号已创建，请进行游戏~~~");
                }else{
                    alert("AG游戏账号创建失败~~");
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    function get_ag_blance(){
        $('#ag_blance').html('加载中');
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

                    $('#ag_blance').html(ret.balance_ag).attr('title',ret.balance_ag);
                }
                else{
                    $('#ag_blance').html('0.00').attr('title','0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    function get_og_blance(){
        $('#og_blance').html('加载中');
        var dat={};
        dat.uid='<?php echo $uid;?>';
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'og/og_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    $('#og_blance').html(ret.balance_og).attr('title',ret.balance_og);
                }
                else{
                    $('#og_blance').html('0.00').attr('title','0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    function get_bbin_blance(){
        $('#bbin_blance').html('加载中');
        var dat={};
        dat.uid='<?php echo $uid;?>';
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'bbin/bbin_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    $('#bbin_blance').html(ret.bbin_balance).attr('title',ret.bbin_balance);
                }
                else{
                    $('#bbin_blance').html('0.00').attr('title','0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    function jb_ag(ctr){
    	if(userAgents=='demoguest'){
    		alert("请注册真实用户！");
    	}else{
	        $.jBox('get:/app/member/zrsx/tran.php?uid=<?php echo $uid;?>&ctr='+ctr, {
	            title: "AG真人视讯额度转换",
	            buttons: { '关闭': true },
	            closed:function(){
	               // location.reload();
	            }
	        });
        }
    }
    function jb_og(ctr){
    	if(userAgents=='demoguest'){
    		alert("请注册真实用户！");
    	}else{
	        $.jBox('get:/app/member/zrsx/og/tran.php?uid=<?php echo $uid;?>&ctr='+ctr, {
	            title: "OG真人视讯额度转换",
	            buttons: { '关闭': true },
	            closed:function(){
	               // location.reload();
	            }
	        });
        }
    }
    function jb_bbin(ctr){
        if(userAgents=='demoguest'){
            alert("请注册真实用户！");
        }else{
            $.jBox('get:/app/member/zrsx/bbin/tran.php?uid=<?php echo $uid;?>&ctr='+ctr, {
                title: "BBIN真人视讯额度转换",
                buttons: { '关闭': true },
                closed:function(){
                   // location.reload();
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
