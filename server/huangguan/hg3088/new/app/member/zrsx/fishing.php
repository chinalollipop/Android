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
    $ag_sql = "select 1 from `" . DBPREFIX . "ag_users` where `userid` = '{$userid}'";
    $ag_result = mysqli_query($dbMasterLink, $ag_sql);
    $ag_cou = mysqli_num_rows($ag_result);
    if ($ag_cou > 0) {
        $last_launch_time = date('Y-m-d H:i:s');
        $res = mysqli_query($dbMasterLink, "update " . DBPREFIX . "ag_users set launch_number = launch_number + 1, last_launch_time = '{$last_launch_time}'  WHERE userid = '{$userid}'");

    }
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>真人视讯</title>
    <link type="text/css" rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../../../style/member/game_page_common.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">
    <style>
        #creditsChangeBox { padding-top:15px;background:#fff;overflow:hidden; }
        #creditsChangeBox p { margin:0px;padding:5px 15px;font-size:14px;font-weight:bold;color:#666;overflow:hidden; }
        #creditsChangeBox p input { width:50%;height:26px;text-indent:5px;border:1px solid #c6c6c6;font-size:18px;box-shadow:inset 1px 1px 4px rgba(0,0,0,0.1);border-radius:4px; }
        #creditsChangeBox p input:hover { border-color:#b10000; }
        #creditsChangeBox p select { padding:3px;height:28px;width:100px;text-indent:5px;border:1px solid #c6c6c6;font-size:14px;box-shadow:inset 1px 1px 4px rgba(0,0,0,0.1);border-radius:4px; }
        #creditsChangeBox span { color:#b10000;font-size:16px; }
        #creditsChangeBox div { margin-top:15px;padding:10px 15px;background:#f2eedd;overflow:hidden; }
        #creditsChangeBox div input { transition:background 0.3s ease;float:left;cursor:pointer;border:0px;width:48%;height:34px;background:#b98e2f;font-size:14px;font-weight:bold;color:#fff;box-shadow:inset 1px 1px 1px rgba(0,0,0,0.3);border-radius:35px; }
        #creditsChangeBox div input:hover { background:#b10000; }
        #creditsChangeBox div input#btnClose { float:right;background:#676767; }
        #creditsChangeBox div input#btnClose:hover { background:#b10000; }
        a.online_in {
            font-weight: normal;
            background-color: #b98e2f;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            padding: 2px 10px;
            position: relative;
            margin-left: 15px;
        }
        a.online_in:before {
            position: absolute;
            top: 6px;
            left: -8px;
            content: '';
            width: 0;
            height: 0;
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
            border-right: 8px solid #b98e2f;
        }
        .tran {
            position: absolute;
            display: none;
            bottom: 398px;
            left: 393px;
            width: 240px;
            z-index: 999;
            height: 280px;
            background-color: #fff;
            background: #fff;
            padding: 20px 30px 30px 20px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            box-shadow: 0 0 30px #928989;
            -webkit-box-shadow: 0 0 30px #928989;
        }
        .mg-page .tran {
            left: 10%;
        }
        .tran-on {
            opacity: 1;
            filter: alpha(opacity=100);
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }
        .jbox-container .jbox-title-panel {
            line-height: 44px!important;
            color: #888;
            padding: 20px 0;
            margin: 0;
            text-align: center;
            font-size: 20px;
        }

        .jbox-button {
            border-radius: 5px!important;
            background: #dca252!important;
            font-weight: normal!important;
            float: none!important;
            cursor: pointer;
            border: 0px;
            height: 30px;
            margin: 4px 20px 0 0;
            line-height: 30px;
            color: #fff;
            font-size: 16px;
        }
        .pt-page .layui-layer-msg {
            top: 150px!important;
            left: 49.5%!important;
            width: 170px;
        }
        .mg-page .layui-layer-msg {
            top: 150px!important;
            left: 69%!important;
            width: 200px;
        }
        .tran tr.b_rig {
            border-top: 1px solid #f1e8e8;
            text-align: center!important;
        }
        .tran  th,
        .tran  td {
            border: none;
            color: #827c7c;
            height: 40px;
            font-size: 16px;
            text-align: left;
            line-height: 40px;
        }
        .tran  th,
        .tran  td {
            display: inline-block;
            font-weight: normal;
            font-size: 16px;
        }
        .tran  td {
            width: 50%;
            text-align: left;
            border: none!important;
        }
        .tran  th {
            width: 40%;
            margin-left: 18px;
        }
        .tran thead th {
            text-align: center;
            height: 50px;
            font-size: 24px;
            width: 100%;
            margin-left: 0;
        }
        .tran  tr.b_rig:last-child {
            border-bottom: 1px solid #f1e8e8;
        }
        .tran  tr.b_rig:last-child td,
        .tran  tr.b_rig:nth-child(3) td {
            width: 100%;
            text-align: center;
        }
        .tran  tr.b_rig:last-child td {
            height: 60px;
            line-height: 40px;
        }
        input#trans_blance {
            position: absolute;
            bottom: 9px;
            left: 50%;
            margin-left: -43%;
            width: 86%;
            height: 35px;
            line-height: 35px;
            font-size: 21px;
        }

        .jbox-button-panel .jbox-button {
            margin-top: 13px;
            outline: none;
        }
        .tran #f_blance,
        .tran #t_blance,
        .tran #blance {
            border: 1px solid #ccc;
            border-radius: 4px;
            height: 25px;
            margin: 5px;
            color: #827c7c;
            padding: 0 3px;
        }
        .tran .game {
            background-color: #fff;
            font-size: 0.75em;
            border-radius: 3px;
            border-collapse: collapse;
        }
        .money-input {
            position: relative;
        }
        #blance {
            padding: 0 0 0 55px;
            border: none!important;
            position: absolute;
            top: 25px;
            left: 30px;
            font-size: 17px;
            border-radius: 0;
        }
        .money-input .ioc-rmb {
            position: absolute;
            left: 0;
            top: 26px;
            width: 50px;
            height: 26px;
            line-height: 26px;
            text-align: center;
            color: #554f4f;
            font-size: 26px;
            font-family: sans-serif;
            z-index: 3;
        }
        .money-input .tran-txt {
            position: absolute;
            top: -8px;
            font-size: 16px;
            left: 15px;
        }

        .ico-tranfer {
            content: '';
            display: inline-block;
            /*background: url(../images/ico-arrow-right.png) no-repeat center center;*/
            background-size: contain;
            width: 24px;
            height: 20px;
            vertical-align: middle;
        }
        .layui-layer-content{color:#fff;}
    </style>
</head>
<body>

<!-- 游戏 -->
<div class="fish-container">
    <div class="middle-contain">
        <div class="fish-transform"></div>
        <div class="left-fish"></div>
        <div class="right-fish"></div>
        <div class="title-pic"></div>
        <!-- 额度转换弹窗 -->
        <div class="tran" id="jbox">

            <a href="../onlinepay/pay_type.php?uid=<?php echo $uid?>langx=<?php echo $langx?>" target="body" class="online_in">去存款</a>
            <table border="0" cellspacing="1" cellpadding="0" class="game" width="100%" style="width:100%;">
                <thead>
                <tr>
                    <th clospan="2">额度转换</th>
                </tr>
                </thead>
                <tbody><tr class="b_rig">
                    <th>体育余额</th>
                    <td><span id="user_blance">0</span></td>

                </tr>
                <tr class="b_rig">
                    <th>捕鱼余额</th>
                    <td><span id="video_blance">0.00</span></td>
                </tr>
                <tr class="b_rig">
                    <td align="left" clospan="2" style="display:flex"><select name="f_blance" id="f_blance" onchange="f_t('f','t');">
                            <option value="hg" selected="selected">体育余额</option>
                            <option value="ag">捕鱼余额</option>
                        </select>
                        <span class="ico-tranfer"></span>
                        <select name="t_blance" id="t_blance" onchange="f_t('t','f');">
                            <option value="hg">体育余额</option>
                            <option value="ag" selected="selected">捕鱼余额</option>
                        </select>
                        <br>
                    </td></tr>
                <tr class="b_rig">
                    <td clospan="2">
                        <div class="money-input">
                            <span class="tran-txt">转换金额</span>
                            <span class="ioc-rmb">￥</span>
                            <input type="text" name="blance" id="blance" value="" placeholder="0.00">
                        </div>
                        <input type="button" class="jbox-button jbox-button-focus" value="提交转换" id="trans_blance" style=" padding:1px 10px; font-weight:bold; cursor:pointer;">
                    </td>
                </tr>
                </tbody>
            </table>

        </div>

        <div class="transaction-area">
            <div class="fish-blance">
                <div class="money-blance">
                        <span>
                            捕鱼余额 :
                        </span>
                    <span id="fish_blance">0.00</span>
                </div>
                <!-- 下面div是转账中心按钮 -->
                <div class="money-change "></div> <!--  onclick="jb();" -->
            </div>

            <div class="game-start">
                <p>
                    海量丰厚的
                    <strong class="jackpot"> JACKPOT </strong>奖励、捕鱼王奖励。派彩活动福利随便领，一夜变身高富帅，称霸渔场
                </p>
                <p>
                    更高级的炮台，更炫酷的技能，让你炸到酣畅淋漓,
                </p>
                <p>
                    一炮炸出
                    <strong>50亿 </strong>礼包送不停
                </p>
            </div>
            <div class="game-login">
                <!-- 下面div是试玩按钮 -->
                <div class="fish-test" onclick="window.open('login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>&gameid=6','ag83')"></div>
                <!-- 下面div是开始游戏按钮 -->
                <div class="fish-start" onclick="window.open('login.php?uid=<?php echo $uid; ?>&gameid=6','ag83')"></div>
            </div>
        </div>
    </div>
    <div class="propagation"></div>
    <div class="propagation_second"></div>
</div>

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>

<script type="text/javascript">


    var userAgents='<?php echo $_SESSION['Agents'];?>';
    get_blance(); // 获取AG余额


    function get_blance(){
        $('#fish_blance').html('加载中');
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
                    $('#user_blance').html(ret.balance_hg); // 体育余额
                    $('#fish_blance,#video_blance').html(ret.balance_ag);
                }
                else{
                    $('#fish_blance,#video_blance').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    //function jb(ctr){
    //	if(userAgents=='demoguest'){
    //		alert("请注册真实用户！");
    //	}else{
	 //       $.jBox('get:/app/member/zrsx/tran.php?uid=<?php //echo $uid;?>//&ctr='+ctr, {
	 //           title: "真人视讯额度转换",
	 //           buttons: { '关闭': true },
	 //           closed:function(){
	 //               location.reload();
	 //           }
	 //       });
    //    }
    //}

    top.loadag=false;



    // 点击页面其他位置弹出框消失
    $('.fish-container').click(function(){
        $('#jbox').hide();
        return false;
    })

    // 点击试玩、开始游戏按钮上下浮动
    $('.game-login').click(function () {
        $('.game-login').stop().addClass('button-move');
        setTimeout(function () {
            $('.game-login').stop().removeClass('button-move');
        }, 200)


    })

    // 点击转账中心上下浮动 和额度转换弹窗出现
    $('.money-change').click(function () {
        event.stopPropagation();
        $('#jbox').stop().fadeToggle("slow","linear");
        $('.money-change').stop().addClass('button-move');
        setTimeout(function () {
            $('.money-change').stop().removeClass('button-move');
        }, 200)
    })

    $('#jbox').click(function(event){
        event.stopPropagation();
    })

    // 鼠标悬停试玩、开始游戏按钮分别切换背景图片
    $('.fish-test').hover(
        function () {
            $('.game-login').addClass('fish-test-hover')
        },
        function () {
            $('.game-login').removeClass('fish-test-hover')
        }
    )
    $('.fish-start').hover(
        function () {
            $('.game-login').addClass('fish-start-hover')
        },
        function () {
            $('.game-login').removeClass('fish-start-hover')
        }
    )

    /* 额度转换开始 */

    var uid='<?php echo $uid;?>';
    jQuery(document).ready(function(){
        jQuery('#trans_blance').bind('click',function(){
            jQuery('#trans_blance').attr('disabled',true);
            jQuery('#trans_blance').attr('value','转换中..');
            var dat={};
            dat.uid=uid;
            dat.action='a';
            if(jQuery('#f_blance').val()==jQuery('#t_blance').val()){
                layer.msg('额度转换，转入转出不能一样。');
                jQuery('#trans_blance').attr('disabled',false);
                jQuery('#trans_blance').attr('value','提交转换');
                return false;
            }
            if(jQuery('#blance').val()==''){
                layer.msg('请输入额度。');
                jQuery('#trans_blance').attr('disabled',false);
                jQuery('#trans_blance').attr('value','提交转换');
                return false;
            }
            dat.f=jQuery('#f_blance').val();
            dat.t=jQuery('#t_blance').val();
            dat.b=jQuery('#blance').val();

            jQuery.ajax({
                type: 'POST',
                url:'ag_api.php?_='+Math.random(),
                data:dat,
                dataType:'json',
                success:function(ret){
                    if(ret.err =='0'){ // 转账成功
                        layer.msg('转账成功');
                    }else{
                        layer.msg(ret.msg);
                    }

                    jQuery('#trans_blance').attr('disabled',false);
                    jQuery('#trans_blance').attr('value','提交转换');
                    if(ret.err==0){
                        jQuery('#blance').val('');
                        get_blance();
                    }
                },
                error:function(ii,jj,kk){
                    jQuery('#trans_blance').attr('disabled',false);
                    jQuery('#trans_blance').attr('value','提交转换');
                    layer.msg('网络错误，请稍后重试！');
                }
            });
        });
    });
    function f_t(f,t){
        var h=jQuery('#'+f+'_blance').val();
        if(h=='hg'){
            jQuery('#'+t+'_blance').val('ag');
        }
        else{
            jQuery('#'+t+'_blance').val('hg');
        }
    }


    /* 额度转换结束 */

</script>

</body></html>
