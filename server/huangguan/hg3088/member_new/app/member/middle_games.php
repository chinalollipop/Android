<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$Status=$_SESSION['Status'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>top.location.href='/tpl/logout_warn.html';</script>";
    exit;
}


if ($Status==1){
    exit;
}



?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>电子游艺</title>
    <link type="text/css" rel="stylesheet" href="../../style/member/jbox_skin2.css?v=<?php echo $_SESSION['AUTOVER_SESSION']; ?>">
    <link type="text/css" rel="stylesheet" href="../../style/member/game_page_common.css?v=<?php echo $_SESSION['AUTOVER_SESSION']; ?>">


    <style>
        /* jquerybox */
        .float_right{float:right}
        .game td, .more td {color: #c67777;}
        .jbox .jbox-title-icon {background: url(/images/jquerybox/game.png) 90px 0 no-repeat;}
        .jbox .jbox-button{background: url(/images/jquerybox/game_btn.png) no-repeat;background-size: 100%;}
        .game .tran_logo{background: url(/images/jquerybox/game_change.png) 5px 8px no-repeat;}
        .jbox .jbox-title-panel {background: #b30000;}
        .jbox .jbox-close, div.jbox .jbox-close-hover {background: url(/images/jquerybox/game_close.png) 0 -1px no-repeat;background-size: 100%;}
        .w_880{width:880px;margin-left:10px}
        .w_920{width:920px;margin-left:1px}
        .gm_notice{color: #fff;font-size: 18px;width:100%;height:50px;line-height: 50px;background:url(../../images/member/2018/game/kuang3.png) no-repeat;}
        .gm_notice marquee{width: 88%;}
        .gm_notice span {float: left;padding-left: 1%;}
        .gm_notice .start{display:inline-block;width:22px;height:21px;background:url(../../images/member/2018/game/start.png) no-repeat;margin-top: 14px;}
        .game_top{width:100%;height:248px;background:url(../../images/member/2018/game/game_top_bg.jpg) no-repeat;}
        .game_bottom{width:100%;margin:0 auto;position: relative;}
        .game_bottom>div{width:457px;height:129px;display:inline-block;background: url(../../images/member/2018/game/kuang.png) no-repeat;position: relative;}
        .game_bottom>div:nth-child(2n){margin-left:1px}
        .game_bottom>div:nth-child(n+2){margin-top:4px}
        .game_bottom_tran,.game_bottom a{margin: 18px 10px 0;display:inline-block;float:left;overflow:hidden;padding:0 5px;height:43px;line-height:36px;width:150px;border-radius:50px;background:url(../../images/member/2018/game/anniu.png) center no-repeat;/*box-shadow:1px 2px 1px rgba(0,0,0,.3);*/color:#0c0510;text-overflow:ellipsis;white-space:nowrap;font-size:16px;font-weight: bold;transition:background .3s ease}
        .game_bottom a:hover{opacity: .8;transition:color .3s ease}
        /* .game_bottom i{float:left;display:inline-block;margin:2px 5px 0 0;width:21px;height:21px;background-size:contain}
       .game_bottom .tran_logo{background-image:url(../../images/member/2018/live/live_tran.png)}
        .game_bottom .money_logo{background-image:url(../../images/member/2018/live/live_money.png)}*/
        .game_bottom .game_bottom_play{float: left;width:277px;height:100%;/*background:url(../../images/member/2018/game/ag_game.png) no-repeat;background-size:100%*/}
        .game_bottom_change{overflow:hidden;width: 180px;float: right;text-align: center;}
        .game_bottom_play a{background:#d1601a;display:block;margin:10px 89.5px 0;width:88px;height:32px;line-height:32px;border-radius:3px;text-align:center;color: #fff;font-weight: normal;}
        .game_bottom_play .try_play {background: #755b47;}
        .game_bottom_play_btn{display:none;position:absolute;width:277px;height:108px;top:4px;background:rgba(0,0,0,.5);padding-top:20px}
        /*.game_bottom_play_btn_tr2{display:none;position:absolute;width:277px;height:108px;top:142px;background:rgba(0,0,0,.5);padding-top:20px}*/
        .game_bottom .game_bottom_play .icon{display:inline-block;width:100px;height:56px;float:left}
        .game_bottom .game_bottom_play .game_play_title{padding:38px 15px}
        .game_bottom .game_bottom_fg .game_bottom_play .icon{background:url(../../images/member/2018/game/fg.png) center center no-repeat}
        .game_bottom .game_bottom_ag .game_bottom_play .icon{background:url(../../images/member/2018/game/ag.png) center center no-repeat}
        .game_bottom .game_bottom_mg .game_bottom_play .icon{background:url(../../images/member/2018/game/mg.png) center center no-repeat}
        .game_bottom .game_bottom_cq .game_bottom_play .icon{background:url(../../images/member/2018/game/cq9.png) center center no-repeat}
        .game_bottom .game_bottom_mw .game_bottom_play .icon{background:url(../../images/member/2018/game/mw.png) center center no-repeat}
        .game_bottom .game_bottom_play .type_title{display:inline-block;color:#fff;font-size:16px;line-height:26px}
        .game_bottom .game_bottom_play .type_title p{font-size:30px;color:#fff}
        
    </style>

</head>

<body >
<div class="w_920">
    <div class="gm_notice">
        <span >最新消息：</span>
        <marquee scrollAmount="5" onMouseOver="this.stop();" onMouseOut="this.start();">
            <span class="start"></span><?php echo $_SESSION['USER_MESSAGE_SESSION'];?>
        </marquee>
    </div>
    <div class="game_top"></div>

    <div class="game_bottom">
        <!-- FG -->
        <div class="game_bottom_fg">

            <div class="game_bottom_change">
                <span class="game_bottom_tran">
                    <i class="money_logo"></i>额度：<span id="fgmoney">0.00</span>
                </span>
                <a href="javascript:;" class="float_right" onclick="jb('fg')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="game_bottom_play">
                <div class="game_play_title">
                    <span class="icon"></span>
                    <div class="type_title">
                        <p> FG电子 </p>
                        FG gaming
                    </div>
                </div>
                <div class="game_bottom_play_btn">
                    <a class="try_play" href="https://www.fungaming.com/index.html#/xboxOne" target="_blank">免费试玩</a>
                    <!--<a href="./fg/index.php?uid=<?php /*echo $uid;*/?>"  target="_blank">立即游戏</a>-->
                    <a href="games.php?type=fg&uid=<?php echo $uid;?>" target="_blank">立即游戏</a>
                </div>
            </div>

        </div>

        <!-- AG -->
        <div class="game_bottom_ag">
            <div class="game_bottom_change">
                <span class="game_bottom_tran">
                    <i class="money_logo"></i>额度：<span id="agmoney">0.00</span>
                </span>
                <a href="javascript:;" class="float_right" onclick="jb('ag')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="game_bottom_play">
                <div class="game_play_title">
                    <span class="icon"></span>
                    <div class="type_title">
                        <p> AG电子 </p>
                        AG gaming
                    </div>
                </div>
                <div class="game_bottom_play_btn">
                    <a class="try_play" href="games.php?type=ag&uid=<?php echo $uid;?>" target="_blank">免费试玩</a>
                    <a href="games.php?type=ag&uid=<?php echo $uid;?>" target="_blank">立即游戏</a>
                </div>
            </div>

        </div>

        <!-- MG -->
        <div class="game_bottom_mg">
            <div class="game_bottom_change">
                <span class="game_bottom_tran">
                    <i class="money_logo"></i>额度：<span id="mgmoney">0.00</span>
                </span>
                <a href="javascript:;" class="float_right" onclick="jb('mg')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="game_bottom_play">
                <div class="game_play_title">
                    <span class="icon"></span>
                    <div class="type_title">
                        <p> MG电子 </p>
                        MG gaming
                    </div>
                </div>
                <div class="game_bottom_play_btn">
                    <a class="try_play" href="games.php?type=mg&uid=<?php echo $uid;?>" target="_blank">免费试玩</a>
                    <a href="games.php?type=mg&uid=<?php echo $uid;?>" target="_blank">立即游戏</a>
                </div>
            </div>

        </div>

        <!-- CQ9 -->
        <div class="game_bottom_cq">

            <div class="game_bottom_change">
                <span class="game_bottom_tran">
                    <i class="money_logo"></i>额度：<span id="cqmoney">0.00</span>
                </span>
                <a href="javascript:;" class="float_right" onclick="jb('cq')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="game_bottom_play">
                <div class="game_play_title">
                    <span class="icon"></span>
                    <div class="type_title">
                        <p> CQ9电子 </p>
                        CQ9 gaming
                    </div>
                </div>
                <div class="game_bottom_play_btn">
                    <a class="try_play" href="https://demo.cqgame.games" target="_blank">免费试玩</a>
                    <a href="./cq9/index.php?uid=<?php echo $uid;?>"  target="_blank">立即游戏</a>
                </div>
            </div>

        </div>

        <!-- MW -->
        <div class="game_bottom_mw">
            <div class="game_bottom_change">
                <span class="game_bottom_tran">
                    <i class="money_logo"></i>额度：<span id="mwmoney">0.00</span>
                </span>
                <a href="javascript:;" class="float_right" onclick="jb('mw')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="game_bottom_play">
                <div class="game_play_title">
                    <span class="icon"></span>
                    <div class="type_title">
                        <p> MW电子 </p>
                        MW gaming
                    </div>
                </div>
                <div class="game_bottom_play_btn">
                    <a href="javascript:;" onclick="enterGame('mw')">立即游戏</a>
                </div>
            </div>

        </div>


    </div>

</div>



<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="../../js/jbox/jquery.jBox-zh-CN.js"></script>
<script type="text/javascript">

    function enterGame() {

        var dat={};
        dat.uid='<?php echo $uid;?>';
        dat.action='gameLobby';
        $.ajax({
            type: 'POST',
            url:'mw/mw_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    window.open(ret.msg);
                }
                else{
                    alert(ret.msg);
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });

    }


    get_balance();
    mg_blance();
    cq_blance();
    mw_blance();
    fg_blance();
    showGameBtn();

    function get_balance(){
        $('#agmoney').html('加载中');
        var dat={};
        dat.uid='<?php echo $uid;?>';
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'zrsx/ag_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){

                    $('#agmoney').html(ret.balance_ag);
                }
                else{
                    $('#agmoney').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    var uid = <?php echo '\''.$uid.'\'' ?> ;
    var userAgents='<?php echo $_SESSION['Agents'];?>';
    function jb(ctr) {
    	if(userAgents=='demoguest'){
    		alert("请注册真实用户！");
    	}else{
	        jQuery.jBox('get:tran_dianzi.php?uid='+uid+'&ctr=' + ctr, {
	            title: "电子游戏额度转换",
	            buttons: {'关闭': true}
	        });
        }
    }


    function mg_blance() {
        $('#mgmoney').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'mg/mg_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    // console.log(ret)
                    $('#mgmoney').html(ret.balance_mg);
                }
                else{
                    $('#mgmoney').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    function cq_blance() {
        $('#cqmoney').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'cq9/cq9_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    console.log(ret)
                    $('#cqmoney').html(ret.msg.cq_balance);
                }
                else{
                    $('#cqmoney').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    function mw_blance() {
        $('#mwmoney').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'mw/mw_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    // console.log(ret)
                    $('#mwmoney').html(ret.msg.mw_balance);
                }
                else{
                    $('#mwmoney').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    function fg_blance() {
        $('#fgmoney').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'fg/fg_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                     //console.log(ret)
                    $('#fgmoney').html(ret.msg.fg_balance);
                }
                else{
                    $('#fgmoney').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    // 显示开始游戏按钮
    function showGameBtn() {
    /*    $('.game_play_title').mouseover(function(){
            $(this).next('.game_bottom_play_btn').show();
        })*/
        $('.game_bottom_play').hover(function(){
            $(this).find('.game_bottom_play_btn').show();
            $(this).find('.game_bottom_play_btn_tr2').show();
        },function () {
            $(this).find('.game_bottom_play_btn').hide();
            $(this).find('.game_bottom_play_btn_tr2').hide();
        })
    }

</script>

</body>
</html>