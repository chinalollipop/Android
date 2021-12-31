<?php
// AG真人

session_start();
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

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

$gamerule = 'https://gci.aghg0086.com/agingame/rules/new/zh/index.jsp?bid=true&vip=true&bac_db=true&bac_pairs=true&bac_superSix=true&bac_in=true&nn=true&bj=true&zjh=true&bf=true&goodRoad=true&stamp=190517_1.80_1';
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
    .money-tips{width: 40%;margin-top: 20%;z-index:99;height:38px;right: 15px;}

    .banner .tutu{position:absolute; left: 0;margin-left: 16px;}
    .banner .tutu img{width: 100%;}
    .tran_live{float:left;overflow:hidden;padding:0 5px;height: 30px;width: 100%;border-radius:50px; background:transparent; color: #d1601a;text-overflow:ellipsis;white-space:nowrap;font-size: 20px;line-height: 30px;transition:background .3s ease;border: 1px solid #d1601a;text-align: center;font-family: monospace;}
    .tran_live_logo {background: url(/images/member/2018/live/0086/live_tran.png) center no-repeat;}
    .liv_list li .money-tips a:hover{background:transparent;}
    .liv_list{margin-bottom: 30px;margin-top: -65px;overflow: hidden;}
    .liv_list li{position:relative;display:inline-block;padding: 5px 0;background-position:center bottom;background-repeat:no-repeat;color:#fff;vertical-align:middle;text-align:right;float: left;background-size: contain;margin-right: 10px;}
    .liv_list li:nth-child(3n+1) {margin-left: 15px;}
    .liv_list li.item1{background-image:url(/images/member/2018/live/0086/min1.png);}
    .liv_list li.item2{background-image:url(/images/member/2018/live/0086/min2.png)}
    .liv_list li.item3{background-image:url(/images/member/2018/live/0086/min3.png)}
    .liv_list li.item4{background-image:url(/images/member/2018/live/0086/min4.png)}
    .liv_list li.item5{background-image:url(/images/member/2018/live/0086/min5.png)}
    .liv_list li.item6{background-image:url(/images/member/2018/live/0086/min6.png)}
    .liv_list .live_all_a{float: left;}
    .liv_list li a{transition:color .3s ease;margin-bottom: 10px;background: #d1601a;color: #fff; border:0;text-align:center;font-size: 18px;font-family:monospace;border-radius:0;}
    .liv_list li a.btn_df {background: #765b48;}
    .liv_list li a:hover{opacity: .8}
    .liv_list li a.btn_df{background: #765b48;}

    @media only screen and (max-width:1200px)  {
        .money-tips{right: 23px;;}
        .banner{height: 328px;}
        .banner .tutu{width: 1040px;}
        .liv_list li{width: 500px;height: 200px;margin-top: 20px;}
        .liv_list .live_all_a{margin: 50px 8px 0 80px;}
        .liv_list li:nth-child(2n+1) {margin-left: 10px;}
        .liv_list li:nth-child(4){margin-left: 0;}
        .liv_list li a{width: 100px;height: 24px;line-height: 24px;}
    }
    @media only screen and (min-width:1201px) and (max-width:1450px) {
        .money-tips{margin-left: 185px;}
        .banner{height: 345px;}
        .banner .tutu{width: 1105px;}
        .liv_list li{width:363px;height:165px;}
        .liv_list .live_all_a{margin: 45px 0 0 35px;}
        .liv_list li a{width: 110px;height: 26px;line-height: 26px;}
    }
    @media only screen and (min-width:1451px) {
        .money-tips{right: 22px;}
        .banner{height: 435px;}
        .banner .tutu{width: 1400px;}
        .liv_list li{width:460px;height:195px;}
        .liv_list .live_all_a{margin: 50px 35px 0 65px;}
        .liv_list li a{width: 118px;height: 30px;line-height: 30px;}

    }
    .liv_list li .money-tips a{display:inline-block;margin-top: 10px;padding:0 5px;width:100%;height: 30px;border-radius:50px;background: none;/* box-shadow:1px 2px 1px rgba(0,0,0,.3); */color: #d1601a;font-size: 20px;line-height: 30px;cursor:pointer;transition:background .3s ease;border: 1px solid #d1601a;text-align: left;}
    .money-tips i{float:left;display:inline-block;margin: 0;width: 30px;height: 30px;background-size: 65%;}

</style>
</head>
<body>
<div class="ui-header">

    <div class="banner">

        <ul class="tutu">
            <li class="cur" style="display: block;"><a class=""><img src="/images/member/2018/live/0086/live_banner1.jpg" alt=""></a></li>
        </ul>
        <div class="yuandian">

        </div>
    </div>
</div>

<!-- 游戏 -->
<ul class="liv_list">

    <li class="item1">
        <!--<h2>竞咪</h2>
        <p>独家首创：自主切牌。玩家不仅可以与主播进行语音文字聊天，还可以全程主导整个游戏节奏，全方位拉近玩家与现场真实游戏互动的体验。</p>-->
        <div class="live_all_a">
            <a class="btn" href="javascript:;" onclick="window.open('og/login.php?uid=<?php echo $uid;?>')">立即游戏</a>
            <a class="btn btn_df" href="<?php echo $og_game_rule_url;?>" target="_blank">游戏规则</a>
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
    <li class="item3">
        <!-- <h2>多台</h2>
         <p>私人定制不同类型的游戏同时投注，随心所欲，多张桌枱任意切换。</p>-->
        <div class="live_all_a">
            <a class="btn" href="javascript:;" onclick="window.open('login.php?type=aglive&uid=<?php echo $uid;?>')">立即游戏</a>
            <a class="btn btn_df" href="<?php echo $gamerule;?>" target="_blank">游戏规则</a>
            <a class="btn btn_df" href="login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>" target="_blank">免费试玩</a>
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
    <!--<li class="item2">
        <div class="live_all_a">
            <a class="btn" href="javascript:;" onclick="">立即游戏</a>
            <a class="btn btn_df" href="javascript:;" target="_blank">游戏规则</a>
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
            <a class="btn btn_df" href="javascript:;" onclick="alert('请进入游戏查看详情')">游戏规则</a>
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
	                //location.reload();
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
	                //location.reload();
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
                    //location.reload();
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


   // huxiFn('.banner') ;

</script>

</body></html>
