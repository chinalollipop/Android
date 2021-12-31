<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名


?>
<style>
    /* 捕鱼 */
    .page-fish-hunting{background:center bottom no-repeat url(<?php echo TPL_NAME;?>images/bg.jpg?v=2);background-size:cover;height:100%;position:relative}
    .actGame{position:absolute;width:190px;height:74px;background:url("<?php echo TPL_NAME;?>images/btn_fish.png");background-size: 100%;display:block;left:50%;bottom:130px;margin-left:-120px;transition:all 0.3s}
    .actGame:hover{transform:scale(1.1)}
    .fish_test{position:absolute;top:215px;left:50%;margin-left:160px;z-index: 9}
    .page-fish-hunting .play{width:98px;height:98px;}
    .page-fish-hunting .money{position:absolute;top:10px;left:50%;width:1186px;height:291px;margin-left:-530px}
    .page-fish-hunting .money,.page-fish-hunting .play{animation:floaty4 ease-in-out 4s 0s infinite forwards}
    .fish_zz{position:absolute;z-index:5;top:10px;left:5px}
    .fish_zz input{width:100%;border:0;background:transparent;color:#fff}
    .fish_zz >span{display:inline-block;background:#0f399a;border:1px solid #4278f7;border-radius:20px;color:#fff;font-weight:bold;padding:6px 18px;float:left}
    .fish_zz >span span{font-size:20px;}
    .fish_zz .show_fish_change{display:inline-block;width:144px;height:41px;background:url(<?php echo TPL_NAME;?>images/fish_btn.png) no-repeat;background-size:100%;margin-left:15px}
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
</style>

<div class="page-fish-hunting">
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

    <a href="javascript:;" class="fish_test" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>&gameid=6')">
        <img class="play" src="../<?php echo TPL_NAME;?>images/sw.png" alt="">
    </a>
    <img class="money" src="../<?php echo TPL_NAME;?>images/money.png" alt="">
    <a href="javascript:;" class="actGame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid; ?>&gameid=6')"></a>


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