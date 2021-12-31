<?php
/**
 * 棋牌游戏
 * Date: 2018/11/12
 */
session_start();
include_once "./include/config.inc.php";
include_once "./include/address.mem.php";

// 判断会员是否登录，否则跳转登出页面
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

// 判断会员状态是否启用，否则退出
if ($_SESSION['Status'] != 0){
    echo "<script>alert('非常抱歉，您的账号已冻结或已停用，请您联系客服！')</script>";
    exit;
}
$uid = $_SESSION["Oid"];
$lydata = getLyQpSetting();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>棋牌游戏</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link type="text/css" rel="stylesheet" href="../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../../style/member/common.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../../style/member/game_page_common.css?v=<?php echo AUTOVER; ?>">
    <style type="text/css">
        .w_790{width:790px;margin:0 auto}
        .chess_top{overflow:hidden;position:relative}
        .chess_top_logo{width:600px;height:290px;background:url(../../images/chess/slide_6668.png) no-repeat;background-size:contain;float:left}
        .chess_top_right{margin-top:90px;margin-left:495px;position:absolute}
        .chess_top_right p{padding-bottom:5px;font-size:30px;color:#5a4033;border-bottom:1px solid #806456;text-align:center}
        .chess_top_right .chess_title_tip{width:230px;margin:0 auto}
        .chess_top_right .chess_title{margin-top:5px;line-height:28px;font-size:18px;border:none;color:rgba(0,0,0,.8)}
        .chess_bottom{width:786px;margin:0 auto}
        .chess_bottom>div{display:inline-block}
        .chess_bottom>div:nth-child(2n){margin-left:20px}
        .chess_bottom>div:nth-child(n+2){margin-top: 20px;}
        .chess_bottom_tran{display:inline-block;float:left;overflow:hidden;padding:0 5px;height:25px;line-height:25px;max-width:140px;min-width:120px;border-radius:50px;background:#554031;box-shadow:1px 2px 1px rgba(0,0,0,.3);color:#fff;text-overflow:ellipsis;white-space:nowrap;font-size:16px;transition:background .3s ease}
        .chess_bottom a{display:inline-block;margin-left:25px;padding:0 5px;width:120px;height:25px;line-height:25px;border-radius:50px;background:#d1601a;box-shadow:1px 2px 1px rgba(0,0,0,.3);color:#fff;font-size:16px;cursor:pointer;transition:background .3s ease}
        .chess_bottom a:hover{background:#e06e28;transition:color .3s ease}
        .chess_bottom i{float:left;display:inline-block;margin:2px 5px 0 0;width:21px;height:21px;background-size:contain}
        .chess_bottom .tran_logo{background-image:url(../../images/chess/tran_logo.png)}
        .chess_bottom .money_logo{background-image:url(../../images/chess/money_logo.png)}
        .chess_bottom_hg .chess_bottom_play{width:380px;height:172px;background:url(../../images/chess/hg_chess.png) no-repeat;background-size: contain;}
        .chess_bottom_ky .chess_bottom_play{width:380px;height:172px;background:url(../../images/chess/ky_chess.png) no-repeat;background-size: contain;}
        .chess_bottom_vg .chess_bottom_play{width:380px;height:172px;background:url(../../images/chess/vg_chess.png) no-repeat;background-size: contain;}
        .chess_bottom_ly .chess_bottom_play{width:380px;height:172px;background:url(../../images/chess/ly_chess.png) no-repeat;background-size: contain;}
        .chess_bottom_change{margin-bottom:5px;overflow:hidden}
        .chess_bottom_play a{display:block;margin:0 43px 10px;width:84px;height:32px;line-height:32px;border-radius:6px;text-align:center}
        .chess_bottom_play a.try_play{background:#644d3b}
        .chess_bottom_play a.try_play:hover{background:#755b47;transition:color .3s ease}
        .chess_bottom_play p{font-size:26px;color:#fff;text-align:right;padding:20px 30px}
        .float_right{float:right}
        /* jquerybox */
        .game td, .more td {color: #6e5842;}
        div.jbox .jbox-title-icon {background: url(/images/jquerybox/qp.png) 90px 0 no-repeat;}
        div.jbox .jbox-button{background: url(/images/jquerybox/qp_btn.png) no-repeat;background-size: 100%;}

    </style>
</head>
<body>
<div class="w_790">
    <div class="chess_top">
        <div class="chess_top_logo"></div>
        <div class="chess_top_right">
            <p class="chess_title_tip">棋 牌 游 戏</p>
            <p class="chess_title">
                专业棋牌竞技平台<br>
                邀你畅玩棋牌、万人在线、火热PK！
            </p>
        </div>

    </div>
    <div class="chess_bottom">

        <!-- VG -->
        <div class="chess_bottom_vg">
            <div class="chess_bottom_change">
                <span class="chess_bottom_tran">
                    <i class="money_logo"></i>额度：<span id="vg_money"> 0.00 </span>
                </span>
                <a class=" float_right" onclick="transaction('vgqp')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="chess_bottom_play">
                <div class="float_right">
                    <p>VG 棋 牌</p>
                    <a class="try_play" href="./vgqp/index.php?uid=<?php echo $uid;?>&flag=test" target="_blank">免费试玩</a>
                    <a href="javascript:;" onclick="window.open('./vgqp/index.php?uid=<?php echo $uid;?>')">立即游戏</a>
                </div>
            </div>

        </div>

        <!-- 乐游棋牌 -->
        <div class="chess_bottom_ly">
            <div class="chess_bottom_change">
                <span class="chess_bottom_tran">
                    <i class="money_logo"></i>额度：<span id="ly_money"> 0.00 </span>
                </span>
                <a class=" float_right" onclick="transaction('lyqp')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="chess_bottom_play">
                <div class="float_right">
                    <p>乐 游 棋 牌</p>
                    <a class="try_play" href="<?php echo $lydata['demourl'];?>" target="_blank">免费试玩</a>
                    <a href="javascript:;" onclick="window.open('./lyqp/index.php?uid=<?php echo $uid;?>')">立即游戏</a>
                </div>
            </div>

        </div>
        <!-- 皇冠 -->
        <div class="chess_bottom_hg">
            <div class="chess_bottom_change">
                <span class="chess_bottom_tran">
                    <i class="money_logo"></i>额度：<span id="ff_money"> 0.00 </span>
                </span>
                <a class=" float_right" onclick="transaction('hgqp')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="chess_bottom_play">
                <div class="float_right">
                    <p>皇 冠 棋 牌</p>
                    <a class="try_play" href="./hgqp/index.php?uid=<?php echo $uid;?>&flag=test" target="_blank">免费试玩</a>
                    <a href="javascript:;" onclick="window.open('./hgqp/index.php?uid=<?php echo $uid;?>')">立即游戏</a>
                </div>
            </div>

        </div>

        <!-- 开元 -->
        <div class="chess_bottom_ky">
            <div class="chess_bottom_change">
                <span class="chess_bottom_tran">
                    <i class="money_logo"></i>额度：<span id="ky_money"> 0.00 </span>
                </span>
                <a class="float_right" onclick="transaction('kyqp')">
                    <i class="tran_logo"></i>
                    额度转换
                </a>
            </div>
            <div class="chess_bottom_play">
                <div class="float_right">
                    <p>开 元 棋 牌</p>
                    <a class="try_play" href="http://play.ky206.com/jump.do" target="_blank">免费试玩</a>
                    <a href="javascript:;"  onclick="window.open('./ky/index.php?uid=<?php echo $uid;?>')" >立即游戏</a>
                </div>
            </div>

        </div>


    </div>

</div>

<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="../../js/jbox/jquery.jBox-zh-CN.js"></script>
<script type="text/javascript">
    var uid = '<?php echo $uid;?>';
    var agent = '<?php echo $_SESSION['Agents']?>';
    if(agent != 'demoguest') { // 正式账号
        get_ky_money();
        get_ff_money();
        get_vg_money();
        get_ly_money();
    }else{
        alert('您尚未注册真实账户，仅允许您进入棋牌试玩模式！');
    }
    function get_ky_money() {
        $('#ky_money').html('加载中');
        var data = {};
        data.uid = uid;
        data.action = 'b';
        $.ajax({
            type : 'POST',
            url : 'ky/ky_api.php?_=' + Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('#ky_money').html(item.data.ky_balance);
                } else {
                    alert(item.message);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_ff_money() {
        $('#ff_money').html('加载中');
        var data = {};
        data.uid = uid;
        data.action = 'b';
        $.ajax({
            type : 'POST',
            url : 'hgqp/hg_api.php?_=' + Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('#ff_money').html(item.data.ff_balance);
                } else {
                    alert(item.message);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_vg_money() {
        $('#vg_money').html('加载中');
        var data = {};
        data.uid = uid;
        data.action = 'b';
        $.ajax({
            type : 'POST',
            url : 'vgqp/vg_api.php?_=' + Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('#vg_money').html(item.data.vg_balance);
                } else {
                    alert(item.message);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }

    /*
      *  获取乐游棋牌游戏余额
      * */
    function get_ly_money() {
        $('#ly_money').html('加载中');
        var data = {};
        data.uid = uid;
        data.action = 'b';
        $.ajax({
            type : 'POST',
            url : 'lyqp/ly_api.php?_=' + Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('#ly_money').html(item.data.ly_balance);
                } else {
                    alert(item.message);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function transaction(type){
        if(agent != 'demoguest') {
            switch (type){
                case 'hgqp':
                    $.jBox('get:/app/member/hgqp/exchange.php?uid=' + uid, {
                        title: "皇冠棋牌额度转换",
                        buttons: {'关闭': true}
                    });
                    break;
                case 'kyqp':
                    $.jBox('get:/app/member/ky/exchange.php?uid=' + uid, {
                        title: "开元棋牌额度转换",
                        buttons: {'关闭': true}
                    });
                    break;
                case 'vgqp':
                    $.jBox('get:/app/member/vgqp/exchange.php?uid=' + uid, {
                        title: "VG棋牌额度转换",
                        buttons: {'关闭': true}
                    });
                    break;
                case 'lyqp':
                    $.jBox('get:/app/member/lyqp/exchange.php?uid=' + uid, {
                        title: "乐游棋牌额度转换",
                        buttons: {'关闭': true}
                    });
                    break;
            }
        }else{
            alert('您尚未注册真实账户，暂不允许进行额度转换！');
        }
    }
    top.loadag=false;
</script>
</body>
</html>
