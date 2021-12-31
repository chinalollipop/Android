<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名


?>
<style>
    /* 捕鱼 */
    .page-fish-hunting{background:center no-repeat url(<?php echo TPL_NAME;?>images/fish/by_bg.jpg);height:700px;position:relative;padding-top: 40px;overflow: hidden;}
    .page-fish-hunting>div{position: relative;height: 100%;}
    .page-fish-hunting .dj_top_img{position: relative;height: 100%;width: 720px}
    .page-fish-hunting .dj_top_img span{position: absolute;display: inline-block;}
    .page-fish-hunting .icon_1{width: 100%;height: 495px;background:url(<?php echo TPL_NAME;?>images/fish/by_db.png) center no-repeat;bottom: 30px; }
    .page-fish-hunting .icon_2{z-index: 6;width: 227px;height: 203px;background:url(<?php echo TPL_NAME;?>images/fish/by_fish_4.png) center no-repeat;left: 215px; bottom: 65px;animation:bottomToTopRight 1s forwards}
    .page-fish-hunting .icon_3{z-index:5;width:257px;height:349px;background:url(<?php echo TPL_NAME;?>images/fish/by_fish_3.png) center no-repeat;left:370px;bottom:60px;animation:bottomToTopRight 2s forwards}
    .page-fish-hunting .icon_4{z-index:4;width:380px;height:585px;background:url(<?php echo TPL_NAME;?>images/fish/by_fish_2.png) center no-repeat;left:260px;bottom:115px;animation:bottomToTopRight 3s forwards}
    .page-fish-hunting .icon_5{z-index:3;width:245px;height:461px;background:url(<?php echo TPL_NAME;?>images/fish/by_fish_1.png) center no-repeat;left:173px;bottom:140px;animation:bottomToTopLeft 2s forwards}
    .page-fish-hunting .icon_6{width:140px;height:140px;background:url(<?php echo TPL_NAME;?>images/fish/by_fish_6.png) center no-repeat;right:80px;top:190px;animation:centerToLeft 1s forwards}
    .page-fish-hunting .icon_7{width:103px;height:99px;background:url(<?php echo TPL_NAME;?>images/fish/by_fish_5.png) center no-repeat;left:120px;top:35px;animation:go_up 5s infinite}

    /* 金币 */
    .page-fish-hunting .icon_jb{width:82px;height:72px}
    .page-fish-hunting .icon_8{background:url(<?php echo TPL_NAME;?>images/fish/jb_1.png) center no-repeat;right:65px;top:415px;animation:floaty4 ease-in-out 4s 0s infinite forwards}
    .page-fish-hunting .icon_9{background:url(<?php echo TPL_NAME;?>images/fish/jb_2.png) center no-repeat;right:25px;top:285px;animation:floaty3 ease-in-out 4s 0s infinite forwards}
    .page-fish-hunting .icon_10{background:url(<?php echo TPL_NAME;?>images/fish/jb_3.png) center no-repeat;left:35px;top:255px;animation:floaty4 ease-in-out 4s 0s infinite forwards}
    .page-fish-hunting .icon_11{background:url(<?php echo TPL_NAME;?>images/fish/jb_4.png) center no-repeat;left:95px;top:385px;animation:floaty3 ease-in-out 4s 0s infinite forwards}
    .page-fish-hunting .icon_12{width:500px;height:430px;background:url(<?php echo TPL_NAME;?>images/fish/qp_1.png) center no-repeat;left:130px;top:-20px;animation:colour_ease2 3s infinite ease-in-out;-webkit-animation:colour_ease2 3s infinite ease-in-out}
    .page-fish-hunting .icon_13{width:309px;height:234px;background:url(<?php echo TPL_NAME;?>images/fish/qp_2.png) center no-repeat;left:100px;bottom:75px;animation:PropagationMove 8s linear infinite;-webkit-animation:PropagationMove 8s linear infinite}
    .avia-login{width:470px;text-align:center}
    .avia-login .avia-play>a{transition:all .3s;display:inline-block;width:140px;height:35px;line-height:35px;cursor:pointer}
    .avia-login .avia-play>a:hover{transform:translateY(10px)}
    .avia-login .avia-play>a:last-child{margin-left:30px}
    .avia-login .dianjing-game-icon{width:100%;height:181px;margin-top:180px;background:center bottom no-repeat url(<?php echo TPL_NAME;?>images/fish/by_title.png);background-size:100%}
    .avia-login .tip{color:#626262;text-align:left;line-height:24px;margin:25px 0}

    /* 额度转换窗口 */
    .fish_zz{position:absolute;z-index:5;top:10px;right:20px}
    .fish_zz input{width:100%;border:0;background:transparent;color:#fff}
    .fish_zz >span{display:inline-block;background: #10b4b9;border:1px solid #a4f4f1;border-radius:20px;color:#fff;font-weight:bold;padding:6px 18px;float:left}
    .fish_zz >span span{font-size:20px;}
    .fish_zz .show_fish_change{display:inline-block;width:144px;height:41px;background:url(<?php echo TPL_NAME;?>images/fish/fish_btn.png) no-repeat;background-size:100%;margin-left:15px}
    .fish_zz .show_fish_change:hover {transform: scale(1.1);transition: .3s;}
    .fish_zz .tran{display:none;width:260px;background:#0171d0;padding:12px 16px;border-radius:5px;box-shadow:0 0 3px #0171d0;-webkit-box-shadow:0 0 3px #0171d0;margin:10px 0 0 0}
    .fish_zz .tran:before{content:'';position:absolute;width:0;height:0;border-left:7px solid transparent;border-right:7px solid transparent;border-bottom:9px solid #0171d0;margin:-20px 0 0 248px}
    .fish_zz .online_in{background-color:#ffb400;color:#fff;text-decoration:none;border-radius:4px;padding:2px 10px;position:relative;margin-left:15px}
    .fish_zz .online_in:before{position:absolute;top:6px;left:-8px;content:'';width:0;height:0;border-top:6px solid transparent;border-bottom:6px solid transparent;border-right:8px solid #ffb400}
    .fish_zz .tran .game{margin:5px 0 0 0;color:#fff}
    .fish_zz .tran tr.b_rig{border-top:1px solid #006cc6;text-align:center}
    .fish_zz .tran td{width:59%;padding:8px 0;display:inline-block}
    .fish_zz .tran td:first-child{width:40%}
    .fish_zz .game select{border:1px solid #205d90;border-radius:5px;height:25px;color:#fff;padding:0 3px;background:#0171d0}
    .fish_zz .jbox-button{border-radius:5px;background:#ffb400;cursor:pointer;border:0;height:30px;line-height:30px;color:#fff;font-size:16px;padding:1px 10px;width:100%}
    .fish_zz .top td{font-size:20px}
    .fish_zz input::placeholder{color:#fff}
    .fish_zz input::-webkit-input-placeholder{color:#fff}
    .fish_zz input:-moz-input-placeholder{color:#fff}
    .fish_zz input::-moz-input-placeholder{color:#fff}
    .fish_zz input:-ms-input-placeholder{color:#fff}

    @keyframes centerToLeft {
        0% {opacity: .5;transform: translate(-100px, 0);}
        100% {opacity: 1;transform: translate(0, 0);}
    }
    @keyframes bottomToTopRight {
        0% {opacity: .5;transform: translate(-80px, 20px);}
        100% {opacity: 1;transform: translate(0, 0);}
    }
    @keyframes bottomToTopLeft {
        0% {opacity: .5;transform: translate(100px, 80px);}
        100% {opacity: 1;transform: translate(0, 0);}
    }
    @keyframes PropagationMove{
        0%{transform:translate(0, 300px);opacity:1}
        70%{opacity:.6}
        100%{transform:translate(0, -300px);opacity:0}
    }
</style>

<div class="page-fish-hunting">
    <div class="w_1200">

        <div class="fish_zz">
            <span class="fish_je">
                <span>捕鱼金额：</span><span class="user_member_ag_amount yellow">0.00</span>
            </span>
            <a class="show_fish_change" href="javascript:;" alt="转账"></a>
            <!-- 额度转换窗口 -->
            <div class="tran" >

                <a href="javascript:;"  class="to_usercenter_content online_in" data-to="deposit">去存款</a>
                <table border="0" cellspacing="1" cellpadding="0" class="game">
                    <tbody>
                    <tr class="top" align="center">
                        <td clospan="2">额度转换</td>
                    </tr>
                    <tr class="b_rig">
                        <td align="left">中心钱包</td>
                        <td align="left"><span class="user_member_amount">0.00</span></td>

                    </tr>
                    <tr class="b_rig">
                        <td align="left">捕鱼余额</td>
                        <td align="left"><span class="user_member_ag_amount">0.00</span></td>
                    </tr>
                    <tr class="b_rig">
                        <td align="left" >
                            <select class="from_blance" >
                                <option value="hg" selected="selected" data-from="hg">中心钱包</option>
                                <option value="ag" data-from="ag">捕鱼余额</option>
                            </select>
                        </td>
                        <td align="left">
                            <select class="to_blance" >
                                <option value="hg" data-to="hg">中心钱包</option>
                                <option value="ag" selected="selected" data-to="ag">捕鱼余额</option>
                            </select>
                            <br>
                        </td>
                    </tr>
                    <tr class="b_rig">
                        <td align="left">
                            转换金额 &nbsp;￥
                        </td>
                        <td align="left">  <input type="number" class="transfer_input" placeholder="0.00" > </td>
                    </tr>

                    </tbody>
                </table>
                <input type="button" class="by_change_btn jbox-button" value="提交转换" data-platform="ag">
            </div>
        </div>

        <div class="dj_top_img left">
            <span class="icon icon_1"> </span>
            <span class="icon icon_2"> </span>
            <span class="icon icon_3"> </span>
            <span class="icon icon_4"> </span>
            <span class="icon icon_5"> </span>
            <span class="icon icon_6"> </span>
            <span class="icon icon_7"> </span>
            <span class="icon icon_jb icon_8"> </span>
            <span class="icon icon_jb icon_9"> </span>
            <span class="icon icon_jb icon_10"> </span>
            <span class="icon icon_jb icon_11"> </span>
            <span class="icon icon_12"> </span>
            <span class="icon icon_13"> </span>

        </div>

        <div class="avia-login right">
            <div class="dianjing-game-icon"></div>
            <p class="tip">
                捕鱼游戏是一种玩法简单的休闲游戏，人人都能上手的操作，<br>
                趣味性极强的玩法，琳琅满目的海底世界而独具特色！海量 <br>
                金币免费等你来战！
            </p>
            <div class="avia-play">
                <a href="javascript:;" class="btn_game by_try" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>&gameid=6')"> 免费试玩 </a>
                <a href="javascript:;" class="btn_game actGame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid; ?>&gameid=6')"> 开始游戏 </a>

            </div>

        </div>
    </div>

</div>

<script type="text/javascript">
    $(function () {

        indexCommonObj.getUserQpBanlance(uid,'ag') ;

        showChangeBtn();
        changeAgMoney();

        // 点击转账中心上下浮动 和额度转换弹窗出现
        function showChangeBtn(){
            $('.show_fish_change').click(function () {
                if(!uid){
                    layer.msg('请先登录',{time:alertTime});return;
                }
                event.stopPropagation();
                $('.tran').stop().fadeToggle("slow","linear");
                $(this).stop().addClass('button-move');
                setTimeout(function () {
                    $(this).stop().removeClass('button-move');
                }, 200)
            })
        }
        
        // 额度转换
        function changeAgMoney() {
            $('.by_change_btn').on('click',function () {
                var  plat = $(this).attr('data-platform');
                var  p_fm = $('.from_blance').find('option:selected').attr('data-from');
                var  p_to = $('.to_blance').find('option:selected').attr('data-to');
                var  mon  = $('.transfer_input').val() ; // 金额 ; // 金额
                if(!plat || !p_fm || !p_to){
                    layer.msg('请选择平台',{time:alertTime});
                    return ;
                }
                if( (p_fm !='hg' && p_to !='hg') || (p_fm =='hg' && p_to =='hg') ){
                    layer.msg('只能从体育转入或转出到其他平台',{time:alertTime});
                    return ;
                }

                if(mon ==0 || mon == NaN || mon ==null || mon=='加载中...'){
                    layer.msg('没有需要转入的金额',{time:alertTime});
                    return ;
                }
                indexCommonObj.transferAccounts(plat,p_fm,p_to,mon) ;
            })
        }


    })
</script>