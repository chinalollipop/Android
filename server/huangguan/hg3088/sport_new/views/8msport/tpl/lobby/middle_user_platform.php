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
<style>
    .top_title{display:-webkit-flex;display:flex;width:100%;padding:15px 0;margin-bottom:15px;justify-content:space-between;border-bottom:1px solid #ECE8E9;border-top:1px solid #ECE8E9}
    .top_title .tip_title{border:0;padding:0;margin-bottom:0}
    .top_title .top_btn a{display:inline-block;padding:5px 15px;border-radius:5px !important;margin-right:10px}
    .modalFooter{margin: 20px;}
    .ed-selection li{height: 100px;}
    .ed-selection li a {padding: 2px 15px;}
</style>
<div class="memberWrap">
    <div class="memberTit clearfix">
        <span class="fl titImg account_icon" ></span>
    </div>

    <div class="payWay">
        <div class="top_title">
            <div class="tip_title">
                <span class="btn_game">1</span>平台余额
            </div>
            <div class="top_btn">
                <a href="javascript:;" class="refurbish_money btn_game"> 全部更新 </a>
                <a href="javascript:;" class="btn_game btn_retrieve"> 一键回收 </a>
            </div>
        </div>

        <section class="ed-selection edzh_list">
            <ul>
                <li> <span class="user_member_amount">加载中...</span> <span>中心钱包</span> </li>
                <!--<li> 皇冠体育<span class="ye_text user_member_sc_amount">加载中...</span></li>-->
                <li><span class="ye_text user_member_third_lottery_amount">加载中...</span> <span>彩票</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="gmcp" data-from="hg" data-to="gmcp" data-toall="hg">一键转入</a> </li>
                <!--li> 彩票<span class="ye_text user_member_lottery_amount">加载中...</span> <a class="btn_game" href="javascript:;" data-platform="cp">一键转入</a> </li-->
                <li> <span class="ye_text user_member_ag_amount">加载中...</span> <span>AG视讯与AG捕鱼</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="ag" data-from="hg" data-to="ag" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_ky_amount">加载中...</span> <span>开元棋牌</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="ky" data-from="hg" data-to="ky" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_ly_amount">加载中...</span> <span>乐游棋牌</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="ly" data-from="hg" data-to="ly" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_vg_amount">加载中...</span> <span>VG棋牌</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="vg" data-from="hg" data-to="vg" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_kl_amount">加载中...</span> <span>快乐棋牌</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="kl" data-from="hg" data-to="kl" data-toall="hg">一键转入</a> </li>
               <!-- <li> <span class="ye_text user_member_hg_amount">加载中...</span> <span>皇冠棋牌</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="ff" data-from="hg" data-to="ff" data-toall="hg">一键转入</a> </li>-->
                <li> <span class="ye_text user_member_mg_amount">加载中...</span> <span>MG电子</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="mg" data-from="hg" data-to="mg" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_og_amount">加载中...</span> <span>OG视讯</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="og" data-from="hg" data-to="og" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_bbin_amount">加载中...</span> <span>BBIN视讯</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="bbin" data-from="hg" data-to="bbin" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_mw_amount">加载中...</span> <span>MW电子</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="mw" data-from="hg" data-to="mw" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_cq_amount">加载中...</span> <span>CQ9电子</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="cq" data-from="hg" data-to="cp" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_fg_amount">加载中...</span> <span>FG电子</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="fg" data-from="hg" data-to="fg" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_avia_amount">加载中...</span> <span>泛亚电竞</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="avia" data-from="hg" data-to="avia" data-toall="hg">一键转入</a> </li>
                <li> <span class="ye_text user_member_fire_amount">加载中...</span> <span>雷火电竞</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="fire" data-from="hg" data-to="fire" data-toall="hg">一键转入</a> </li>
            </ul>

        </section>

        <div class="tip_title"><span class="btn_game">2</span>转账范围</div>

        <section>
            <div class="edzh_div">
                <div class="ed_change_all">
                    <div class="ed_top">
                        <div class="ed_select"> 转出：
                            <select class="transfer_select transfer_select_fm" data-type="fm">
                                <option value="">请选择钱包</option>
                                <option data-platform="hg" value="hg">中心钱包</option>
                                <option data-platform="gmcp" value="gmcp">彩票</option>
                                <option data-platform="ag" value="ag">AG视讯与AG捕鱼</option>
                                <option data-platform="ky" value="ky">开元棋牌</option>
                                <option data-platform="ly" value="ly">乐游棋牌</option>
                               <!-- <option data-platform="ff" value="ff">皇冠棋牌</option>-->
                                <option data-platform="vg" value="vg">VG棋牌</option>
                                <option data-platform="kl" value="kl">快乐棋牌</option>
                                <option data-platform="mg" value="mg">MG电子</option>
                                <option data-platform="og" value="og">OG视讯</option>
                                <option data-platform="bbin" value="bbin">BBIN视讯</option>
                                <option data-platform="mw" value="mw">MW电子</option>
                                <option data-platform="cq" value="cq">CQ9电子</option>
                                <option data-platform="fg" value="fg">FG电子</option>
                                <option data-platform="avia" value="avia">泛亚电竞</option>
                                <option data-platform="fire" value="fire">雷火电竞</option>
                            </select>
                            转入：
                            <select class="transfer_select transfer_select_to" data-type="to">
                                <option value="">请选择钱包</option>
                                <option data-platform="hg" value="hg">中心钱包</option>
                                <option data-platform="gmcp" value="gmcp">彩票</option>
                                <option data-platform="ag" value="ag">AG视讯与AG捕鱼</option>
                                <option data-platform="ky" value="ky">开元棋牌</option>
                                <option data-platform="ly" value="ly">乐游棋牌</option>
                                <!--<option data-platform="ff" value="ff">皇冠棋牌</option>-->
                                <option data-platform="vg" value="vg">VG棋牌</option>
                                <option data-platform="kl" value="kl">快乐棋牌</option>
                                <option data-platform="mg" value="mg">MG电子</option>
                                <option data-platform="og" value="og">OG视讯</option>
                                <option data-platform="bbin" value="bbin">BBIN视讯</option>
                                <option data-platform="mw" value="mw">MW电子</option>
                                <option data-platform="cq" value="cq">CQ9电子</option>
                                <option data-platform="fg" value="fg">FG电子</option>
                                <option data-platform="avia" value="avia">泛亚电竞</option>
                                <option data-platform="fire" value="fire">雷火电竞</option>
                            </select>
                        </div>
                        <div class="ed_money_input">
                            金额：<input type="number" class="transfer_input ed_change_inpout" placeholder="请输入转换金额">
                        </div>
                    </div>
                    <div class="modalFooter">
                        <button type="button" class="transfer_btn_last transfer_btn btn-add btn_game">确认转换</button>
                    </div>
                </div>
            </div>
        </section>

    </div>

</div>


<script type="text/javascript">


    $(function () {
        var uid = '<?php echo $uid;?>' ;

        // 获取余额
        function getUserAllMoney() {
            indexCommonObj.getUserAllPlateMoney(uid) ;
            // indexCommonObj.getUserQpBanlance(uid,'sc') ;
            indexCommonObj.getUserQpBanlance(uid,'gmcp') ;
            indexCommonObj.getUserQpBanlance(uid,'ky') ;
            indexCommonObj.getUserQpBanlance(uid,'ly') ;
            // indexCommonObj.getUserQpBanlance(uid,'ff') ;
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
        }
        getUserAllMoney();
        transferAction();
        changePlat() ;
        refurbishMoney() ;
        oneRecovery();

        // 选择平台
        function changePlat() {
            $('.transfer_select').off().on('change',function () {
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
                getUserAllMoney();
            })
        }

        // 一键回收
        function oneRecovery() {
            var yjhs_transferFlage = false;
            $('.btn_retrieve').off().on('click',function () {
                if(yjhs_transferFlage){
                    return false;
                 }
                yjhs_transferFlage = true;
                $('.edzh_list li').each(function (i,v) {
                    var f_blance = $(this).find('a').attr('data-platform'); // 转出平台
                    var t_blance = 'hg'; // 转入平台
                    var blance = $(this).find('.ye_text').text(); // 金额
                    setTimeout(function () { // 防止短时间内重复请求
                        yjhs_transferFlage = false;
                    },5000)
                   // console.log(blance)
                    if(blance=='加载中' || blance=='加载中...'){
                        blance ='0';
                    }
                    if(f_blance){
                        blance = blance.replace(',',''); // 去掉千位符,需要字符串，不能是 number
                        blance = Math.floor(blance);
                        indexCommonObj.transferAccounts(f_blance,f_blance,t_blance,blance,'yjhs') ;
                    }

                })
            })

        }

    })
</script>