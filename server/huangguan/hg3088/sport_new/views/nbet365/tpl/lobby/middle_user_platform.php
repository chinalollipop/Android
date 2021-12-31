<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
if( !isset($uid) || $uid == "" ) {
    echo "<script>window.location.href='/'</script>";
    exit;
}
//  单页面维护功能

$cpUrl = $_SESSION['LotteryUrl'];

?>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>/style/memberaccount.css?v=<?php echo AUTOVER; ?>" >

<div class="memberWrap">
    <div class="memberTit clearfix">
        <span class="fl titImg account_icon" ></span>
    </div>
    <div class="payWay">
        <div class="payWayTit">余额</div>
        <table class="account-table" cellspacing="0" cellpadding="0">
            <tr>
                <td>中心钱包</td>
                <td class="user_member_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <a href="javascript:;" class="opengame refurbish_money" >刷新</a>
                    </div>
                </td>
            </tr>
          <!--  <tr>
                <td>皇冠体育余额</td>
                <td class="user_every_amount user_member_sc_amount">加载中...</td>
                <td><div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="sc" data-from="hg" data-to="sc" data-toall="hg"> </span>
                        <span class="transfer_btn account_icon zc" data-platform="sc" data-from="sc" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.loadSportsPage('','r','today')" >开始游戏</a>
                    </div>
                </td>
            </tr>-->
            <tr>
                <td>彩票余额 </td>
                <td class="user_every_amount user_member_lottery_amount">加载中...</td>
                <td><div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="cp" data-from="hg" data-to="cp" data-toall="hg"> </span>
                        <span class="transfer_btn account_icon zc" data-platform="cp" data-from="cp" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')" >开始游戏</a>
                    </div></td>
            </tr>
           <!-- <tr>
                <td>彩票余额 </td>
                <td class="user_every_amount user_member_third_lottery_amount">加载中...</td>
                <td><div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="gmcp" data-from="hg" data-to="gmcp" data-toall="hg"> </span>
                        <span class="transfer_btn account_icon zc" data-platform="gmcp" data-from="gmcp" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','<?php /*echo TPL_NAME;*/?>/tpl/lobby/middle_lottery_third.php')" >开始游戏</a>
                    </div></td>
            </tr>-->
            <tr>
                <td>AG余额 </td>
                <td class="user_every_amount user_member_ag_amount">加载中...</td>
                <td><div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="ag" data-from="hg" data-to="ag" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="ag" data-from="ag" data-to="hg" > </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')">开始游戏</a> </div></td>
            </tr>
            <tr>
                <td>OG余额 </td>
                <td class="user_every_amount user_member_og_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="og" data-from="hg" data-to="og" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="og" data-from="og" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/login.php')">开始游戏</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>BBIN视讯余额 </td>
                <td class="user_every_amount user_member_bbin_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="bbin" data-from="hg" data-to="bbin" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="bbin" data-from="bbin" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/login.php')">开始游戏</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>开元棋牌余额 </td>
                <td class="user_every_amount user_member_ky_amount">加载中...</td>
                <td><div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="ky" data-from="hg" data-to="ky" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="ky" data-from="ky" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')">开始游戏</a> </div></td>
            </tr>
            <tr>
                <td>乐游棋牌余额 </td>
                <td class="user_every_amount user_member_ly_amount">加载中...</td>
                <td><div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="ly" data-from="hg" data-to="ly" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="ly" data-from="ly" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')">开始游戏</a> </div></td>
            </tr>
           <!--<tr>
                <td>皇冠棋牌余额 </td>
                <td class="user_every_amount user_member_hg_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="ff" data-from="hg" data-to="ff" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="ff" data-from="ff" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')">开始游戏</a>
                    </div>
                </td>
            </tr>-->
            <tr>
                <td>VG棋牌余额 </td>
                <td class="user_every_amount user_member_vg_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="vg" data-from="hg" data-to="vg" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="vg" data-from="vg" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')">开始游戏</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>快乐棋牌余额 </td>
                <td class="user_every_amount user_member_kl_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="kl" data-from="hg" data-to="kl" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="kl" data-from="kl" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/klqp/index.php?uid=<?php echo $uid;?>')">开始游戏</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>MG电子余额 </td>
                <td class="user_every_amount user_member_mg_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="mg" data-from="hg" data-to="mg" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="mg" data-from="mg" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/mg/mg_api.php?action=getLaunchGameUrl')">开始游戏</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>CQ9电子余额 </td>
                <td class="user_every_amount user_member_cq_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="cq" data-from="hg" data-to="cq" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="cq" data-from="cq" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/cq9/index.php?uid=<?php echo $uid;?>')">开始游戏</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>MW电子余额 </td>
                <td class="user_every_amount user_member_mw_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="mw" data-from="hg" data-to="mw" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="mw" data-from="mw" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/mw/mw_api.php?action=gameLobby')">开始游戏</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>FG电子余额 </td>
                <td class="user_every_amount user_member_fg_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="fg" data-from="hg" data-to="fg" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="fg" data-from="fg" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/fg/fg_api.php?action=getLaunchGameUrl')">开始游戏</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>泛亚电竞余额 </td>
                <td class="user_every_amount user_member_avia_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="avia" data-from="hg" data-to="avia" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="avia" data-from="avia" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/avia/avia_api.php?action=getLaunchGameUrl')">开始游戏</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>雷火电竞余额 </td>
                <td class="user_every_amount user_member_fire_amount">加载中...</td>
                <td>
                    <div class="ch_btn">
                        <span class="transfer_btn account_icon zz" data-platform="fire" data-from="hg" data-to="fire" data-toall="hg" > </span>
                        <span class="transfer_btn account_icon zc" data-platform="fire" data-from="fire" data-to="hg"> </span>
                        <a href="javascript:;" class="opengame" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/thunfire/fire_api.php?action=getLaunchGameUrl')">开始游戏</a>
                    </div>
                </td>
            </tr>
        <!--     <tr>
                 <td>总额 </td>
                 <td>加载中...</td>
                 <td> <span class="account_icon zz_all"> </span> </td>
             </tr>-->
        </table>
    </div>
    <div class="payWay">
        <div class="payWayTit">转账范围</div>
        <table class="tableSubmit" cellspacing="0" cellpadding="0">
            <tbody>

            <tr>
                <td><span class="red">*</span>转出</td>
                <td>
                    <select class="transfer_select" data-type="fm">
                        <option value="">请选择钱包</option>
                        <option data-platform="hg" value="hg">中心钱包余额</option>
                        <!--<option data-platform="sc" value="sc">皇冠体育余额</option>-->
                        <option data-platform="cp" value="cp">彩票余额</option>
                        <!--<option data-platform="gmcp" value="gmcp">彩票余额</option>-->
                        <option data-platform="ag" value="ag">AG余额</option>
                        <option data-platform="og" value="og">OG余额</option>
                        <option data-platform="bbin" value="bbin">BBIN视讯余额</option>
                        <option data-platform="ky" value="ky">开元棋牌余额</option>
                        <option data-platform="ly" value="ly">乐游棋牌余额</option>
                        <!--<option data-platform="ff" value="ff">皇冠棋牌余额</option>-->
                        <option data-platform="vg" value="vg">VG棋牌余额</option>
                        <option data-platform="kl" value="kl">快乐棋牌余额</option>
                        <option data-platform="mg" value="mg">MG电子余额</option>
                        <option data-platform="cq" value="cq">CQ9电子余额</option>
                        <option data-platform="mw" value="mw">MW电子余额</option>
                        <option data-platform="fg" value="fg">FG电子余额</option>
                        <option data-platform="avia" value="avia">泛亚电竞余额</option>
                        <option data-platform="fire" value="fire">雷火电竞余额</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><span class="red">*</span>转账至</td>
                <td>
                    <select id="t_blance" class="transfer_select" data-type="to">
                        <option value="">请选择钱包</option>
                        <option data-platform="hg" value="hg">中心钱包余额</option>
                        <!--<option data-platform="sc" value="sc">皇冠体育余额</option>-->
                        <option data-platform="cp" value="cp">彩票余额</option>
                        <!--<option data-platform="gmcp" value="gmcp">彩票余额</option>-->
                        <option data-platform="ag" value="ag">AG余额</option>
                        <option data-platform="og" value="og">OG余额</option>
                        <option data-platform="bbin" value="bbin">BBIN视讯余额</option>
                        <option data-platform="ky" value="ky">开元棋牌余额</option>
                        <option data-platform="ly" value="ly">乐游棋牌余额</option>
                        <!--<option data-platform="ff" value="ff">皇冠棋牌余额</option>-->
                        <option data-platform="vg" value="vg">VG棋牌余额</option>
                        <option data-platform="kl" value="kl">快乐棋牌余额</option>
                        <option data-platform="mg" value="mg">MG电子余额</option>
                        <option data-platform="cq" value="cq">CQ9电子余额</option>
                        <option data-platform="mw" value="mw">MW电子余额</option>
                        <option data-platform="fg" value="fg">FG电子余额</option>
                        <option data-platform="avia" value="avia">泛亚电竞余额</option>
                        <option data-platform="fire" value="fire">雷火电竞余额</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>金额</td>
                <td> <input type="number" class="transfer_input"> </td>
            </tr>

            </tbody></table>
        <div class="btnWrap clearfix">
            <button style="margin-left: 150px;" class="transfer_btn_last nextBtn transfer_btn ">转账</button>

        </div>


    </div>

</div>


<script type="text/javascript">


    $(function () {
        var uid = '<?php echo $uid;?>' ;

        indexCommonObj.getUserAllPlateMoney(uid) ;
        // indexCommonObj.getUserQpBanlance(uid,'sc') ;
        //indexCommonObj.getUserQpBanlance(uid,'gmcp') ;
        indexCommonObj.getUserQpBanlance(uid,'ky') ;
        indexCommonObj.getUserQpBanlance(uid,'ly') ;
        //indexCommonObj.getUserQpBanlance(uid,'ff') ;
        indexCommonObj.getUserQpBanlance(uid,'vg') ;
        indexCommonObj.getUserQpBanlance(uid,'kl') ;
        indexCommonObj.getUserQpBanlance(uid,'mg') ;
        indexCommonObj.getUserQpBanlance(uid,'cq') ;
	    indexCommonObj.getUserQpBanlance(uid,'og') ;
        indexCommonObj.getUserQpBanlance(uid,'bbin') ;
        indexCommonObj.getUserQpBanlance(uid,'mw') ;
        indexCommonObj.getUserQpBanlance(uid,'fg') ;
	    indexCommonObj.getUserQpBanlance(uid,'avia') ;
        indexCommonObj.getUserQpBanlance(uid,'fire') ;

        transferAction();
        changePlat() ;
        refurbishMoney() ;
        // 选择平台
        function changePlat() {
            $('.transfer_select').on('change',function () {
                var val = $(this).val() ;
                var type = $(this).data('type') ;
                var plat = $(this).find('option:selected').data('platform') || '' ; // 平台
                var $transferbtnlast = $('.transfer_btn_last') ;

                if(type == 'fm'){ // 转出
                    $transferbtnlast.attr({'data-from':val,'data-platform':plat});
                }else if(type =='to'){
                    $transferbtnlast.attr({'data-to':val,'data-platform':plat});
                }
            });

        }
        // 一键开始转账
        function transferAction() {
            $('.transfer_btn').off().on('click',function () {
                var  plat = $(this).attr('data-platform');
                var  p_fm = $(this).attr('data-from');
                var  p_to = $(this).attr('data-to');
                var  mon = 0 ; // 金额
                var toall = $(this).attr('data-toall');

                // console.log(mon);
                // console.log(plat);
                if(toall =='hg'){ // 全部转入
                    mon = $(indexCommonObj.user_member_amount).html() || $(indexCommonObj.transfer_input).val() ; // 金额
                }else{
                    mon = $(this).parents('tr').find('.user_every_amount').html() || $(indexCommonObj.transfer_input).val() ; // 金额
                }
                mon = mon.replace(',',''); // 去掉千位符

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
                mon = Math.floor(mon);
                indexCommonObj.transferAccounts(plat,p_fm,p_to,mon) ;
            });
        }

        // 刷新金额
        function refurbishMoney() {
            $('.refurbish_money').on('click',function () {
                indexCommonObj.getUserAllPlateMoney(uid) ;
                // indexCommonObj.getUserQpBanlance(uid,'sc') ;
                //indexCommonObj.getUserQpBanlance(uid,'gmcp') ;
                indexCommonObj.getUserQpBanlance(uid,'ky') ;
                indexCommonObj.getUserQpBanlance(uid,'ly') ;
               // indexCommonObj.getUserQpBanlance(uid,'ff') ;
                indexCommonObj.getUserQpBanlance(uid,'vg') ;
                indexCommonObj.getUserQpBanlance(uid,'kl') ;
                indexCommonObj.getUserQpBanlance(uid,'mg') ;
                indexCommonObj.getUserQpBanlance(uid,'og') ;
                indexCommonObj.getUserQpBanlance(uid,'bbin') ;
                indexCommonObj.getUserQpBanlance(uid,'mw') ;
                indexCommonObj.getUserQpBanlance(uid,'cq') ;
                indexCommonObj.getUserQpBanlance(uid,'fg') ;
		indexCommonObj.getUserQpBanlance(uid,'avia') ;
                indexCommonObj.getUserQpBanlance(uid,'fire') ;
            })
        }



    })
</script>