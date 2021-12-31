<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
require ("./include/config.inc.php");

// 判断今日赛事是否维护-单页面维护功能
checkMaintain('game');

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$Status=$_SESSION['Status'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
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
    <link type="text/css" rel="stylesheet" href="../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../../style/member/game_page_common.css?v=<?php echo AUTOVER; ?>">


    <style>
        /* jquerybox */
        .game td, .more td {color: #c67777;}
        .jbox .jbox-title-icon {background: url(/images/jquerybox/game.png) 90px 0 no-repeat;}
        .jbox .jbox-button{background: url(/images/jquerybox/game_btn.png) no-repeat;background-size: 100%;}
        .game .tran_logo{background: url(/images/jquerybox/game_change.png) 5px 8px no-repeat;}
        .jbox .jbox-title-panel {background: #b30000;}
        .jbox .jbox-close, div.jbox .jbox-close-hover {background: url(/images/jquerybox/game_close.png) 0 -1px no-repeat;background-size: 100%;}
        .w_880{width:100%;}
        .game_top{width:100%;height:325px;background:url(../../images/member/2018/game/game_bg.jpg)}
        .game_bottom{width:100%;margin:0 auto}
        .game_bottom>div{display:inline-block}
        .game_bottom>div:nth-child(2n){margin-left:10px}
        .game_bottom>div:nth-child(n+2){margin-top:20px}
        .game_bottom_tran{display:inline-block;float:left;overflow:hidden;padding:0 5px;height:30px;line-height:30px;max-width:160px;min-width:130px;border-radius:50px;background:#554031;box-shadow:1px 2px 1px rgba(0,0,0,.3);color:#fff;text-overflow:ellipsis;white-space:nowrap;font-size:16px;transition:background .3s ease}
        .game_bottom a{display:inline-block;margin-left:25px;padding:0 5px;width:120px;height:28px;line-height:28px;border-radius:50px;background:#d1601a;box-shadow:1px 2px 1px rgba(0,0,0,.3);color:#fff;font-size:16px;cursor:pointer;transition:background .3s ease}
        .game_bottom a:hover{opacity: .8;transition:color .3s ease}
        .game_bottom i{float:left;display:inline-block;margin:2px 5px 0 0;width:21px;height:21px;background-size:contain}
        .game_bottom .tran_logo{background-image:url(../../images/live/live_tran.png)}
        .game_bottom .money_logo{background-image:url(../../images/live/live_money.png)}
        .game_bottom_ag .game_bottom_play{width:395px;height:263px;background:url(../../images/member/2018/game/ag_game.png) no-repeat;background-size:100%}
        .game_bottom_mg .game_bottom_play{width:395px;height:263px;background:url(../../images/member/2018/game/mg_game.png) no-repeat;background-size:100%}
        .game_bottom_change{overflow:hidden;width: 90%;margin: 0 auto 7px;}
        .game_bottom_play a{display:block;margin:0 28px 10px;width:88px;height:32px;line-height:32px;border-radius:3px;text-align:center;}
        .game_bottom_play .try_play {background: #755b47;}
        .game_bottom_play_btn{position:absolute;margin-top:136px}
        .float_right{float:right}

    </style>

</head>

<body >
<div class="w_880">
    <div class="game_top"></div>

    <div class="game_bottom">
        <!-- AG -->
        <div class="game_bottom_ag">
            <div class="game_bottom_change">
                <span class="game_bottom_tran">
                    <i class="money_logo"></i>额度：<span id="agmoney">0.00</span>
                </span>
                <a class="float_right" onclick="jb('ag')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="game_bottom_play">
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
                <a class="float_right" onclick="jb('mg')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="game_bottom_play">
                <div class="game_bottom_play_btn">
                    <a class="try_play" href="games.php?type=mg&uid=<?php echo $uid;?>" target="_blank">免费试玩</a>
                    <a href="games.php?type=mg&uid=<?php echo $uid;?>" target="_blank">立即游戏</a>
                </div>
            </div>

        </div>


    </div>

</div>



<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="../../js/jbox/jquery.jBox-zh-CN.js"></script>
<script type="text/javascript">

    get_balance();
    mg_blance();

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

</script>

</body>
</html>