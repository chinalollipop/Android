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

        a.online_in{font-weight:400;background-color:#b98e2f;color:#fff;text-decoration:none;border-radius:4px;padding:2px 10px;position:relative;margin-left:15px}
        a.online_in:before{position:absolute;top:6px;left:-8px;content:'';width:0;height:0;border-top:6px solid transparent;border-bottom:6px solid transparent;border-right:8px solid #b98e2f}
        .tran{position:absolute;display:none;bottom:398px;left:393px;width:280px;z-index:999;height:345px;background-color:#fff;background:#fff;padding:20px;border-top-left-radius:5px;border-top-right-radius:5px;box-shadow:0 0 30px #928989;-webkit-box-shadow:0 0 30px #928989}
        .mg-page .tran{left:10%}
        .tran-on{opacity:1;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}
        .jbox-container .jbox-title-panel{line-height:44px!important;color:#888;padding:20px 0;margin:0;text-align:center;font-size:20px}
        .jbox-button{border-radius:5px!important;background:#dca252!important;font-weight:400!important;float:none!important;cursor:pointer;border:0;height:30px;margin:4px 20px 0 0;line-height:30px;color:#fff;font-size:16px}
        .pt-page .layui-layer-msg{top:150px!important;left:49.5%!important;width:170px}
        .mg-page .layui-layer-msg{top:150px!important;left:69%!important;width:200px}
        .tran tr.b_rig{border-top:1px solid #f1e8e8;text-align:center!important}
        .tran td,.tran th{border:none;color:#827c7c;font-size:16px;text-align:left!important;line-height:40px}
        .tran td,.tran th{display:inline-block;font-weight:400;font-size:16px}
        .tran td{width:50%;border:none!important}
        .game tr td:first-child,.tran th{width:40%}
        .tran thead th{text-align:center!important;height:50px;font-size:24px;width:100%;margin-left:0}
        .tran tr.b_rig:last-child{border-bottom:1px solid #f1e8e8}
        input#trans_blance{position:absolute;bottom:3px;left:50%;margin-left:-43%;width:86%;height:35px;line-height:35px;font-size:21px}
        .jbox-button-panel .jbox-button{margin-top:13px;outline:0}
        .tran #f_blance,.tran #t_blance{border:1px solid #ccc;border-radius:4px;height:25px;color:#827c7c;padding:0 3px}
        .tran .game{background-color:#fff;font-size:.75em;border-radius:3px;border-collapse:collapse}
        .money-input{position:relative}
        .tran #blance{width:100%;float:right;border:none!important;font-size:17px;border-radius:0}
        .money-input .tran-txt{position:absolute;left:0;top:0;height:26px;line-height:26px;text-align:center;color:#554f4f;font-size:16px;font-family:sans-serif;z-index:3}
        .ico-tranfer{content:'';display:inline-block;background-size:contain;width:24px;height:20px;vertical-align:middle}
        .layui-layer-content{color:#fff}

        /*电子竞技*/
        .dianjing-container{position:relative;overflow:hidden;width:100%;height:100%;min-height:660px;background:url(../../images/dianjing/dianjing_bg.jpg) no-repeat;background-size:cover;}
        .dianjing-container .dianjing-container-renwu{position:relative;overflow:hidden;width:100%;height:100%;background:url(../../images/dianjing/renwu.png?v=1) bottom no-repeat;background-size: 100%;}
        .dianjing-container .middle-contain{position:absolute;top:0;left:50%;z-index:2;width:1080px; margin-left: -450px;}
        .dianjing-container .middle-contain .title-pic-dj{width:839px;height:504px;background:url(../../images/dianjing/title_pic.png) no-repeat}
        .dianjing-container .dianjing-blance{width: 46%;position:absolute;z-index:1;left: 1.5%;text-align: center;}
        .dianjing-container .dianjing-blance-fy{left: auto;right: 5%;}
        .dianjing-container .dj-btn {margin: 25px 0;}
        .dianjing-container .dj-btn a{display: inline-block;width:190px; height:75px;cursor:pointer;}
        .dianjing-container .middle-contain .dianjing-start{background:url(../../images/dianjing/avia_login.png) no-repeat;}
        .dianjing-container .middle-contain .dianjing-start:hover{background-position:0px -79px; }
        .dianjing-container .middle-contain .dianjing-test{background:url(../../images/dianjing/test_avia_login.png) no-repeat;margin-left: 20px;}
        .dianjing-container .middle-contain .dianjing-test:hover{background-position:0px -75px; }
        .dianjing-container .dianjing-blance .money{width: 100%;}
        .dianjing-container .dianjing-blance .money-blance{line-height: 45px;}
        .dianjing-container .dianjing-blance .money-blance span{font-weight:700; font-size:26px;}
        .dianjing-container .dianjing-blance .money-blance span:first-child{color:#ffe84e;}
        .dianjing-container .dianjing-blance .money-blance span:nth-child(2){color:yellow;}
        .dianjing-container .dianjing-blance .money>div{display: inline-block;}
        .dianjing-container .dianjing-blance .money-change{float:right;width: 48%;height:44px;background:url(../../images/dianjing/transter_button.png?v=1) center no-repeat;cursor:pointer}
        .dianjing-container .dianjing-blance .money-change:hover{background:url(../../images/dianjing/transter_button_hover.png?v=1) center no-repeat;cursor:pointer}
        .dj-content .logo{width: 100%;height: 230px;background: url(../../images/dianjing/logo_lh.png) center no-repeat;background-size: 90%;}
        .dianjing-blance-fy .dj-content .logo{background: url(../../images/dianjing/logo_fy.png) center no-repeat;background-position-x: 50px;background-size: 80%;}
        .dianjing-game-icon{position: absolute;margin-left: 200px;bottom: -70px;}


    </style>
</head>
<body>

<!-- 游戏 -->
<div class="dianjing-container">
    <div class="dianjing-container-renwu">
    <div class="middle-contain">

        <div class="title-pic-dj"> </div>
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
                    </td>
                    <td align="left">
                        <select name="t_blance" id="t_blance" onchange="f_t('t','f');">
                            <option value="hg">体育余额</option>
                            <option value="avia">泛亚电竞余额</option>
                            <option value="fire">雷火电竞余额</option>
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

        <img class="dianjing-game-icon"  src="../../images/dianjing/dianjing_game_icon.png"/>
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
    $('.dianjing-container').click(function(){
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
