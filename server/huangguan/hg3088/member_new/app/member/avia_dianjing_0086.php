<?php
// AVIA

session_start();
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require ("include/config.inc.php");


// 判断泛亚、雷火电竞是否维护-单页面维护功能
//checkMaintain('avia');
//checkMaintain('thunfire');

$aviaTryDomain = getSysConfig('avia_try_domain'); // 泛亚电竞试玩地址
$thunFireTryDomain = getSysConfig('thunfire_try_domain'); // 雷火电竞试玩地址

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>top.location.href='/tpl/logout_warn.html';</script>";
	exit;
}

$Status=$_SESSION['Status'];
if ($Status!=0){
exit;
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>泛亚电竞</title>
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
        a.online_in{font-weight:normal;background-color:#b98e2f;color:#fff;text-decoration:none;border-radius:4px;padding:2px 10px;position:relative;margin-left:15px}
        a.online_in:before{position:absolute;top:6px;left:-8px;content:'';width:0;height:0;border-top:6px solid transparent;border-bottom:6px solid transparent;border-right:8px solid #b98e2f}
        .tran{position:absolute;display:none;bottom:185px;left:400px;width:300px;z-index:999;height:345px;background-color:#fff;background:#fff;padding:20px;border-top-left-radius:5px;border-top-right-radius:5px;box-shadow:0 0 30px #928989;-webkit-box-shadow:0 0 30px #928989}
        .mg-page .tran{left:10%}
        .tran-on{opacity:1;filter:alpha(opacity=100);-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}
        .jbox-container .jbox-title-panel{line-height:44px!important;color:#888;padding:20px 0;margin:0;text-align:center;font-size:20px}
        .jbox-button{border-radius:5px!important;background:#dca252!important;font-weight:normal!important;float:none!important;cursor:pointer;border:0px;height:30px;margin:4px 20px 0 0;line-height:30px;color:#fff;font-size:16px}
        .pt-page .layui-layer-msg{top:150px!important;left:49.5%!important;width:170px}
        .mg-page .layui-layer-msg{top:150px!important;left:69%!important;width:200px}
        .tran tr.b_rig{border-top:1px solid #f1e8e8;text-align:center!important}
        .tran  th,.tran  td{border:none;color:#827c7c;font-size:16px;text-align:left !important;line-height:40px}
        .tran  th,.tran  td{display:inline-block;font-weight:normal;font-size:16px}
        .tran  td{width:50%;border:none!important}
        .tran  th,.game tr td:first-child{width:42%}
        .tran thead th{text-align:center !important;height:50px;font-size:24px;width:100%;margin-left:0}
        .tran  tr.b_rig:last-child{border-bottom:1px solid #f1e8e8}
        input#trans_blance{position:absolute;bottom:3px;left:50%;margin-left:-43%;width:86%;height:35px;line-height:35px;font-size:21px}
        .jbox-button-panel .jbox-button{margin-top:13px;outline:none}
        .tran #f_blance,.tran #t_blance{border:1px solid #ccc;border-radius:4px;height:25px;color:#827c7c;padding:0 3px}
        .tran .game{background-color:#fff;font-size:0.75em;border-radius:3px;border-collapse:collapse}
        .money-input{position:relative}
        .tran #blance{width:100%;float:right;border:none!important;font-size:17px;border-radius:0}
        .money-input .tran-txt{position:absolute;left:0;top:0;height:26px;line-height:26px;text-align:center;color:#554f4f;font-size:16px;font-family:sans-serif;z-index:3}
        .ico-tranfer{content:'';display:inline-block;background-size:contain;width:24px;height:20px;vertical-align:middle}
        .layui-layer-content{color:#fff;}

        /* 电竞 */
        .dianjing-container-0086{position:relative;overflow:hidden;width:100%;min-width:1160px;height:100%;min-height:650px;background:url(../../images/dianjing/0086/bg.png?v=1) top center no-repeat;background-size:100%;}
        .dianjing-container-0086 .dianjing-blance{width: 400px;position:absolute;z-index:1;padding-top: 31.5%;transform: scale(.9);left: 1.5%;text-align: center;}
        .dianjing-container-0086 .dianjing-blance-fy{left: auto;right: 5%;}
        .dianjing-container-0086 .dj-btn {margin: 15px 0;}
        .dianjing-container-0086 .dj-btn a{transition:.3s;display: inline-block;width:180px; height:60px;cursor:pointer;}
        .dianjing-container-0086 .dj-btn a:hover{transform: scale(1.05);}
        .dianjing-container-0086 .dianjing-blance .dianjing-test{background:url(../../images/dianjing/0086/test.png?v=1) no-repeat;background-size:100%;margin-left: 35px;}
        .dianjing-container-0086 .dianjing-blance .dianjing-start{background:url(../../images/dianjing/0086/login.png?v=1) no-repeat;background-size:100%;}
        .dianjing-container-0086 .dianjing-blance .money{width: 300px;height: 90px;margin-left: 50px;background: url(../../images/dianjing/0086/money_bg.png?v=1) center no-repeat;  }
        .dianjing-container-0086 .dianjing-blance .money-blance{line-height: 45px;}
        .dianjing-container-0086 .dianjing-blance .money-blance span{font-weight:700; font-size:24px;}
        .dianjing-container-0086 .dianjing-blance .money-blance span:first-child{color:#fff;}
        .dianjing-container-0086 .dianjing-blance .money-blance span:nth-child(2){color:yellow;}
        .dianjing-container-0086 .dianjing-blance .money-change{width: 100%;height:35px;background:url(../../images/dianjing/0086/transter_button.png) center no-repeat;cursor:pointer}
        .dj-content .logo{width: 100%;height: 117px;background: url(../../images/dianjing/0086/lh_logo.png) center no-repeat;}
        .dianjing-blance-fy .dj-content .logo{background: url(../../images/dianjing/0086/fy_logo.png) center no-repeat;background-position-x: -5px;background-size: 80%;}
    </style>
</head>
<body>

<!-- 游戏 -->
<div class="dianjing-container-0086">
    <!-- 雷火电竞 -->
    <div class="dianjing-blance">
        <div class="dj-content">
            <div class="logo"></div>
            <div class="dj-btn">
                <!-- 下面div是开始游戏按钮 -->
                <a class="dianjing-start" onclick="jb('fire')"></a>
                <!-- 下面div是试玩按钮 -->
                <a class="dianjing-test" onclick="window.open('<?php echo $thunFireTryDomain ?>')"></a>
            </div>

            <!-- 下面div是转账中心按钮 -->
            <div class="money">

                <div class="money-blance">
                    <span>额度：</span>
                    <span id="fire_blance">0.00</span>
                </div>
                <div class="money-change"></div>
            </div>

        </div>
    </div>
    <!-- 泛亚电竞 -->
    <div class="dianjing-blance dianjing-blance-fy">
        <div class="dj-content">
            <div class="logo"></div>
            <div class="dj-btn">
                <!-- 下面div是开始游戏按钮 -->
                <a class="dianjing-start" onclick="jb('avia')"></a>
                <!-- 下面div是试玩按钮 -->
                <a class="dianjing-test" onclick="window.open('<?php echo $aviaTryDomain ?>')"></a>
            </div>

            <!-- 下面div是转账中心按钮 -->
            <div class="money">

                <div class="money-blance">
                    <span>额度：</span>
                    <span id="avia_blance">0.00</span>
                </div>
                <div class="money-change"></div>
            </div>

        </div>
    </div>
    <div class="middle-contain">
        <!-- 额度转换弹窗 -->
        <div class="tran" id="jbox">

            <a href="onlinepay/pay_type.php" target="body" class="online_in">去存款</a>
            <table border="0" cellspacing="1" cellpadding="0" class="game" width="100%" style="width:100%;">
                <thead>
                <tr>
                    <th clospan="2">额度转换</th>
                </tr>
                </thead>
                <tbody>
                <tr class="b_rig">
                    <td>体育余额</td>
                    <td><span id="user_blance">0</span></td>

                </tr>
                <tr class="b_rig">
                    <td>泛亚电竞余额</td>
                    <td><span id="tran_avia_blance">0.00</span></td>
                </tr>
                <tr class="b_rig">
                    <td>雷火电竞余额</td>
                    <td><span id="tran_fire_blance">0.00</span></td>
                </tr>
                <tr class="b_rig">
                    <td align="left" clospan="2" >
                        <select name="f_blance" id="f_blance" onchange="f_t('f','t');">
                            <option value="hg" selected="selected">体育余额</option>
                            <option value="avia">泛亚电竞余额</option>
                            <option value="fire">雷火电竞余额</option>
                        </select>
                        <!-- <span class="ico-tranfer"></span>-->
                    </td>
                    <td align="left">
                        <select name="t_blance" id="t_blance" onchange="f_t('t','f');">
                            <option value="hg">体育余额</option>
                            <option value="avia" >泛亚电竞余额</option>
                            <option value="fire" >雷火电竞余额</option>
                        </select>
                        <br>
                    </td>
                </tr>
                <tr class="b_rig">
                    <td >
                        转换金额 &nbsp;￥
                    </td>
                    <td>  <input type="text" name="blance" id="blance" value="" placeholder="0.00"> </td>
                </tr>

                </tbody>
            </table>
            <input type="button" class="jbox-button jbox-button-focus" value="提交转换" id="trans_blance" style=" padding:1px 10px; font-weight:bold; cursor:pointer;">
        </div>
    </div>

</div>

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">


    var userAgents='<?php echo $_SESSION['Agents'];?>';
    function jb(ctr) {
        if(userAgents=='demoguest'){
            alert("请注册真实用户！");
        }else if(ctr == 'avia') {
            window.open('avia/avia_api.php?action=getLaunchGameUrl');
        }else if(ctr == 'fire') {
            window.open('thunfire/fire_api.php?action=getLaunchGameUrl');
        }
    }

    avia_blance(); // 获取AVIA余额
    fire_blance(); // 获取thunFire余额

    function avia_blance() {
        $('#aviamoney').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'avia/avia_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                // console.log(ret)
                if(ret.err==0){
                    $('#avia_blance,#tran_avia_blance').html(ret.msg.avia_balance);
                    $('#user_blance').html(ret.msg.hg_balance); // 体育余额
                }
                else{
                    $('#avia_blance,#tran_avia_blance').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }
    function fire_blance() {
        $('#firemoney').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'thunfire/fire_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                 console.log(ret)
                if(ret.err==0){
                    $('#fire_blance,#tran_fire_blance').html(ret.msg.fire_balance);
                    $('#user_blance').html(ret.msg.hg_balance); // 体育余额
                }
                else{
                    $('#fire_blance,#tran_fire_blance').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    top.loadag=false;


    // 点击页面其他位置弹出框消失
    $('.dianjing-container-0086').click(function(){
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

            if(dat.f == 'avia' ||  dat.t == 'avia') {
                dj_url = 'avia/avia_api.php?_='+Math.random();
            } else if(dat.f == 'fire' ||  dat.t == 'fire') {
                dj_url = 'thunfire/fire_api.php?_='+Math.random();
            }

            jQuery.ajax({
                type: 'POST',
                url:dj_url,
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
                        avia_blance() ;
                        fire_blance();
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
