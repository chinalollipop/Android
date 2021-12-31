<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../../../../../app/member/include/address.mem.php";
require ("../../../../../app/member/include/config.inc.php");
require ("../../../../../app/member/include/define_function_list.inc.php");

$uid= $_SESSION['Oid'] ;
$langx= $_SESSION["langx"];
$username=$_SESSION['UserName'];
$realname = $_SESSION['Alias'];
$payPassword = $_SESSION['payPassword'];
$Bank_Name = $_SESSION['Bank_Name'];
$Bank_Account = $_SESSION['Bank_Account'] ;
$Bank_Address = $_SESSION['Bank_Address'] ;
$Usdt_Address = $_SESSION['Usdt_Address'] ;

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.location.href = '/';</script>";
    exit;
}
require ("../../../../../app/member/include/traditional.$langx.inc.php");

$member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where Oid='$uid'";
$member_query = mysqli_query($dbLink,$member_sql);
$memberinfo = mysqli_fetch_assoc($member_query);
$sUserlayer = $memberinfo['layer'];
// 检查当前会员是否设置不准操作额度分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
$layerId=3;
if ($sUserlayer==$layerId){
    $layer = getUserLayerById($layerId);
    if ($layer['status']==1) {
        echo "<script language=javascript>alert('账号分层异常，请联系我们在线客服'); window.location.href='/';</script>";
        exit;
    }
}

$membermessage = getMemberMessage($username,'2'); // 取款短信

?>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/memberaccount.css?v=<?php echo AUTOVER; ?>" >
<style>
    .top_title{display:-webkit-flex;display:flex;width:100%;padding:15px 0;margin-bottom:15px;justify-content:space-between;border-bottom:1px solid #ECE8E9;border-top:1px solid #ECE8E9}
    .top_title .tip_title{border:0;padding:0;margin-bottom:0}
    .top_title .top_btn a{display:inline-block;padding:5px 15px;border-radius:5px !important;margin-right:10px}
    .ed-selection li a{display: none;}
    .btn-nextstep{width:125px;height:38px;border:0;border-radius:20px;background:#708ae8;background:linear-gradient(to right,#708ae8 0%,#5ea0ea 100%);font-size:16px;text-align:center;color:#fff;margin-left:50px}
    .withdrawal-amount .input-field:before{left:100px}
    .withdrawal-amount .title{line-height:1em;height:auto;margin:0 0 1em}
    .withdrawal-amount .input-field{font-size:16px;width:360px;height:38px;line-height:38px;position:relative}
    .withdrawal-amount .input-field input{padding:0 20px;width:100%;height:100%;border-radius:5px;font-size:22px;text-align:right;position:absolute}
    .withdrawal-amount input::placeholder{color:#cacaca;font-size:16px}
    .withdrawal-amount input::-webkit-input-placeholder{color:#cacaca;font-size:16px}
    .withdrawal-amount input:-moz-input-placeholder{color:#cacaca;font-size:16px}
    .withdrawal-amount input::-moz-input-placeholder{color:#cacaca;font-size:16px}
    .withdrawal-amount input:-ms-input-placeholder{color:#cacaca;font-size:16px}
    .withdrawal-amount{margin:0px auto 0 auto}
    .withdrawal-amount .input-field .error-message{margin:5px 0 10px 205px;color:#ff0000;display:none;position:absolute}
    .withdrawal-amount .balance-message{height:50px;margin:6px 0 0 0;color:#606060;letter-spacing:1px}
    .withdrawal-amount .balance-message div{height:25px;padding:5px 0 0 0}
    .withdrawal-amount .balance-message div.alert{padding:0}
    .withdrawal-amount .balance-message span{color:#bd4700;letter-spacing:0px}
    .withdrawal-amount .balance-message,.dollar-values{padding-left:80px}
    .withdrawal-amount .alert,.bank-card-selection .alert{padding:0;margin:0;border-color:#ffffff;background-color:#ffffff}
    .withdrawal_passwd{padding-left:5px;border-radius:5px;height:40px}
    .bank-card-selection{height:auto;min-height:150px}
    .bank-card-selection .title{line-height:90px}
    .bank-card-selection .bank-list{width:100%;min-height:60px;overflow-x:visible}
    .bank-card-selection .bank-card,#addNewBankCard{margin:auto 40px 5px 0;width:296px;height:136px;border:2px solid #ccc;border-radius:5px}
    .bank-card .logo{width:41px;height:40px;margin:0 auto;line-height:55px}
    .bank-card .bank{width:90%;margin:15px auto;display:-webkit-flex;display:flex}
    .bank-card .bank .name,.bank-card .bank .bank-account-number{width:100%;height:30px;white-space:nowrap;border-bottom:1px dashed #ccc}
    .bank-card .bank .bank-account-number span{margin:0 0 0 2px}
    .bank-card .txt{position:relative;width:80px;line-height:25px;text-align:center;background:#2f4159;color:#fff}
    .bank-card .txt:after{position:absolute;content:'';width:0;height:0;border-top:25px solid #2f4159;border-right:15px solid transparent;right:-15px}
    .new-bank-card .logo{width:100px;height:100%;background:url(<?php echo TPL_NAME;?>images/add_icon.png) center no-repeat}
    .new-bank-card .text{width:70%;height:100%;line-height:136px;font-size:16px;font-weight:500;letter-spacing:normal;flex:1;cursor:pointer}
    .dollar-values{height:65px}
    .dollar-values .btn{float:left;width:98px;height:35px;line-height:35px;margin:auto 10px auto 0;padding:0;border:1px solid #ddd;border-radius:5px;text-align:center;background:transparent}
    .dollar-values .btn:hover,.dollar-values .btn.selected{border:1px solid #b5d3f5}
</style>

<div class="memberWrap">
    <div class="memberTit clearfix">
        <span class="account_icon fl titImg withdraw_nav"></span>
    </div>
    <form method="post" name="main" onSubmit="return false;">
        <div class="payWay">
            <div class="top_title">
                <div class="tip_title">
                    <span class="btn_game">1</span>提款中心
                </div>
                <div class="top_btn">
                    <a href="javascript:;" class="btn_game btn_retrieve"> 一键回收 </a>
                </div>
            </div>

            <input id="Bank_Name" name="Bank_Name" type="hidden" value="<?php echo $_SESSION['Bank_Name']?>">
           <!-- <input id="Bank_Account" name="Bank_Account" type="hidden" value="<?php /*echo $_SESSION['Bank_Account']*/?>">-->
            <input id="Bank_Address" name="Bank_Address" type="hidden" value="<?php echo $_SESSION['Bank_Address']?>">

            <!--<input id="Usdt_Address" name="usdt_address" type="hidden" value="<?php /*echo $_SESSION['Usdt_Address']*/?>">-->
            <input id="usdt_rate" name="usdt_rate" type="hidden" > <!-- 用户最近一笔 usdt 充值汇率 -->

            <section class="ed-selection edzh_list">
                <ul>
                    <li> <span class="user_member_amount">加载中...</span> <span>中心钱包</span> </li>
                    <!--<li> 皇冠体育<span class="ye_text user_member_sc_amount">加载中...</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="sc" >一键转出</a></li>-->
                    <li><span class="ye_text user_member_third_lottery_amount">加载中...</span> <span>彩票</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="gmcp" >一键转出</a></li>
                    <!--li> 彩票<span class="ye_text user_member_lottery_amount">加载中...</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="cp" >一键转出</a> </li-->
                    <li> <span class="ye_text user_member_ag_amount">加载中...</span> <span>AG视讯与AG捕鱼</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="ag" >一键转出</a></li>
                    <li> <span class="ye_text user_member_ky_amount">加载中...</span> <span>开元棋牌</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="ky" >一键转出</a></li>
                    <li> <span class="ye_text user_member_ly_amount">加载中...</span> <span>乐游棋牌</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="ly" >一键转出</a></li>
                    <li> <span class="ye_text user_member_vg_amount">加载中...</span> <span>VG棋牌</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="vg" >一键转出</a></li>
                    <li> <span class="ye_text user_member_kl_amount">加载中...</span> <span>快乐棋牌</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="kl" >一键转出</a></li>
                    <!--<li> <span class="user_member_hg_amount">加载中...</span> <span>皇冠棋牌</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="ff" >一键转出</a> </li>-->
                    <li> <span class="ye_text user_member_mg_amount">加载中...</span> <span>MG电子</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="mg" >一键转出</a></li>
                    <li> <span class="ye_text user_member_og_amount">加载中...</span> <span>OG视讯</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="og" >一键转出</a></li>
                    <li> <span class="ye_text user_member_bbin_amount">加载中...</span> <span>BBIN视讯</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="bbin" >一键转出</a></li>
                    <li> <span class="ye_text user_member_mw_amount">加载中...</span> <span>MW电子</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="mw" >一键转出</a></li>
                    <li> <span class="ye_text user_member_cq_amount">加载中...</span> <span>CQ9电子</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="cq" >一键转出</a></li>
                    <li> <span class="ye_text user_member_fg_amount">加载中...</span> <span>FG电子</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="fg" >一键转出</a></li>
                    <li> <span class="ye_text user_member_avia_amount">加载中...</span> <span>泛亚电竞</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="avia" >一键转出</a></li>
                    <li> <span class="ye_text user_member_fire_amount">加载中...</span> <span>雷火电竞</span> <a class="btn_game transfer_btn" href="javascript:;" data-platform="fire" >一键转出</a></li>
                </ul>

            </section>

            <div class="tip_title"><span class="btn_game">2</span>额度转换</div>

            <section class="ed-selection">
                <!--<button class="btn-nextstep platMoney_change" >额度转换</button>-->
                <div class="edzh_div">

                </div>

                <div class="title">提款打码量：<span id="owe_bet">加载中...</span></div>
                <div class="title">已打码量：<span id="total_bet">加载中...</span>&nbsp;&nbsp;&nbsp;&nbsp;<button id="bet_detail" class="btn-nextstep">查看详情</button></div>
            </section>

            <div class="tip_title"><span class="btn_game">3</span>选择银行卡:</div>
            <section class="bank-card-selection">
                <div id="bankcard" class="bank-list flex-row-wrap flex-start">
                    <div id="bankList" class="flex-row-wrap flex-start">

                        <div class="card bank-card cur-p bank-card-active" >
                            <div class="bank flex-row">
                                <div class="flex-col name show_bank_name"> <?php echo $_SESSION['Bank_Name']?> </div>
                                <div class="flex-col bank-account-number"> 卡号：<span class="last-4-number show_bank_account"> <?php echo returnBankAccount($_SESSION['Bank_Account']) ;?> </span></div>
                            </div>
                            <div class="txt">储蓄卡</div>

                        </div>


                        <div id="addNewBankCard" class="card new-bank-card flex-row cur-p">
                            <div class="logo">　</div>
                            <div class="text change_user_bank">更换您的银行卡</div>
                        </div>
                    </div>

                </div>
                <label class="alert alert-danger" for="bankname"></label>
            </section>

            <div class="tip_title"><span class="btn_game">4</span>提款金额:</div>

            <section class="withdrawal-amount validations">
                <div class="input-field">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;金额： <input type="number" min="0" id="Money" name="Money" class="form-control" maxlength="15" placeholder="请输入提款金额" value="">

                </div>
                <div class="balance-message">
                    <div>可提款额度<span id="hgmoney" class="user_member_amount"> 加载中...</span> 元</div>

                </div>
            </section>

            <section  class="betAmount dollar-values flex-row">
                <ul>
                    <li class="btn dollar" value="100"><span class="value+100+">100</span>元</li>
                    <li class="btn dollar" value="500"><span class="value+500+">500</span>元</li>
                    <li class="btn dollar" value="1000"><span class="value+1000+">1000</span>元</li>
                    <li class="btn dollar" value="5000"><span class="value+5000+">5000</span>元</li>
                    <li class="btn dollar" value="10000"><span class="value+10000+">10000</span>元</li>
                    <li class="btn dollar" value="50000"><span class="value+50000+">50000</span>元</li>
                </ul>

            </section>

            <section  class="betAmount flex-row">
                <div class="balance-message">
                    <div class="title">提款密码:
                        <input type="password" class="withdrawal_passwd" id="withdrawal_passwd" name="withdrawal_passwd" onkeyup="this.value=this.value.replace(/\D/g,'')"  minlength="4" maxlength="6"/>
                    </div>

                </div>
                <button class="btn-nextstep" id="mainSubmit" >确认提款</button>
            </section>

        </div>
    </form>


</div>



<script>
    // usdt 金额输入与计算
    function countUsdtMount(){
        var $pay_to_usdt = $('.pay_to_usdt');
        var usdt_rate = Number($('#usdt_rate').val());
        var cz_val = $('#Money').val();
        if(!usdt_rate){
            return;
        }
        var zf_val = cz_val/(usdt_rate); // 需要转入的usdt
        zf_val = indexCommonObj.advFormatNumber(zf_val,2); // 保留两位小数
        $pay_to_usdt.text(zf_val);
    }

$(function () {
    var withdrawflage = false ;

    var withdrawNum = '<?php echo $membermessage['mcou']?>' ; // 是否有会员信息
    var withdrawMsg = '<?php echo $membermessage['mem_message']?>' ; // 会员信息
    // 弹窗信息
    if(withdrawNum>0){ // 有弹窗短信
        layer.alert(withdrawMsg, {
            title: '会员信息',
            icon: false , // 0,1
            skin: 'layer-ext-moon'
        }) ;
    }
    var sum = 0;
    $(".betAmount li").each(function () {
        $(this).click(function(){
            sum += $(this).val();
            $("#Money").val(sum);
            countUsdtMount();
        });
    });

    chooseWithdraw();

    if(!'<?php echo $Bank_Name;?>' || !'<?php echo returnBankAccount($Bank_Account);?>' || '<?php echo returnBankAccount($Bank_Account);?>'=='******' || !'<?php echo $Bank_Address;?>' ){ // 未绑定银行资料
        layer.msg('请先绑定银行卡资料!',{time:alertTime});
        indexCommonObj.loadUserCenterPage() ;
        return ;
    }else {
        getUserUsdtRate();
        indexCommonObj.getUserBetDetail(); // 会员打码量
        indexCommonObj.getUserAllPlateMoney(uid) ;
        //indexCommonObj.getUserQpBanlance(uid,'gmcp') ;
        indexCommonObj.getUserQpBanlance(uid,'ky') ;
        indexCommonObj.getUserQpBanlance(uid,'ly') ;
        //indexCommonObj.getUserQpBanlance(uid,'ff') ;
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

    }

    // 选择提款方式
    function chooseWithdraw() {
        $('.choose_w_type').off().on('click',function () {
            var type = $(this).attr('data-type');
            $('.mainSubmit').attr({'data-type':type});
            $(this).addClass('active').parent().siblings().find('.choose_w_type').removeClass('active');
            if(type=='usdt'){
                $('.show_usdt').show();
            }else{
                $('.show_usdt').hide();
            }
        })
    }
    // 获取usdt账号信息
    function getUserUsdtRate(){
        var ajaxurl = '/app/member/api/usdtRateApi.php';
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {action:'getUsdtAddress'},
            dataType: 'json',
            success: function (res) { // 有返回 usdt 汇率才可以使用 usdt 提款
                if(res){
                    if(res.status =='200'){ // 有充值过 usdt
                        $('#usdt_rate').val(res.data.withdrawals_usdt_rate);
                        $('.new_usdt_rate').text(res.data.withdrawals_usdt_rate);
                        $('#Usdt_Address').val(res.data.Usdt_Address);
                        if(res.data.Usdt_Address){
                            $('.has_usdt').show(); // 显示USDT提款金额
                        }
                    }
                }
            },
            error: function () {
                layer.msg('网络错误，请稍后重试!',{time:alertTime});
            }
        });
    }

    // 提款提交
    function withdrawSubmit() {
        $('.mainSubmit').off().on('click',function () {
            if(withdrawflage){
                return ;
            }
            var $mominput = $('#Money') ;
            var withdrawmon = $mominput.val() ; // 提款金额
            var memmoney = Number($('#hgmoney').text()) ; // 用户当前余额
            var Bank_Name = $('#Bank_Name').val() ;
            //var Bank_Account = $('#Bank_Account').val() ;
            var Bank_Address = $('#Bank_Address').val() ;
            //var Usdt_Address = $('#Usdt_Address').val() ;
            var $withdrawalpasswd = $('#withdrawal_passwd');
            var withdrawal_passwd = $withdrawalpasswd.val() ;
            var usdt_rate = $('#usdt_rate').val() ; // 最后一次存款的usdt汇率
            var type = $(this).attr('data-type') ; // 提款类型

            var pars ={
                Bank_Name:Bank_Name,
                //Bank_Account:Bank_Account,
                Bank_Address:Bank_Address,
                Money:withdrawmon,
                withdrawal_passwd:withdrawal_passwd,
            }

            if (withdrawmon== "") {
                layer.msg('请输入提款金额!',{time:alertTime});
                $mominput.focus();
                return false;
            }
            if (withdrawmon !="") {
                if(withdrawmon > memmoney ){
                    layer.msg('提款金额不能大于帐号金额!',{time:alertTime});
                    $mominput.focus();
                    return false;
                }
            }
            if (withdrawmon !="") {
                if(withdrawmon <100 ){
                    layer.msg('提款金额不能小于100元!',{time:alertTime});
                    $mominput.focus();
                    return false;
                }
            }

            if (withdrawal_passwd == "") {
                layer.msg('请输入取款密码!',{time:alertTime});
                $withdrawalpasswd.focus();
                return false;
            }
            if(type=='usdt'){ // usdt 提款
                pars.usdt_rate = usdt_rate; // 传多一个参数
                // if(!Usdt_Address){
                //     layer.msg('请先绑定USDT账号!',{time:alertTime});
                //     return false;
                // }
            }

            withdrawflage = true ;

            var ajaxurl = '/app/member/money/take.php' ;

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: pars,
                dataType: 'json',
                success: function (res) {
                    if(res){
                        $mominput.val('');
                        $withdrawalpasswd.val('');
                        countUsdtMount();
                        layer.msg(res.describe,{time:alertTime});
                        setTimeout(function () {
                            withdrawflage = false ;
                        },1000)
                        // if(res.status == '200'){ // 提款成功
                        //     window.location.href = '/' ;
                        // }
                    }
                },
                error: function () {
                    withdrawflage = false ;
                    layer.msg('网络错误，请稍后重试!',{time:alertTime});
                }
            });

        })

        }
        // 额度转换
        function changeMoneyToHg(){
            // $('.platMoney_change').on('click',function () {
                var zh_str = '<div class="ed_change_all"> ' +
                    '<div class="ed_top"> <div class="ed_select"> '+
                    '转出： <select class="transfer_select_fm" >' +
                    ' <option value="">请选择钱包</option>' +
                   // ' <option data-platform="sc" value="sc">皇冠体育</option>' +
                    // ' <option data-platform="cp" value="cp">彩票</option>' +
                    ' <option data-platform="gmcp" value="gmcp">彩票</option>' +
                    ' <option data-platform="ag" value="ag">AG视讯与AG捕鱼</option>' +
                    ' <option data-platform="ky" value="ky">开元棋牌</option>' +
                    ' <option data-platform="ly" value="ly">乐游棋牌</option>' +
                   // ' <option data-platform="ff" value="ff">皇冠棋牌</option>' +
                    ' <option data-platform="vg" value="vg">VG棋牌</option>' +
                    ' <option data-platform="kl" value="kl">快乐棋牌</option>' +
                    ' <option data-platform="mg" value="mg">MG电子</option>' +
                    ' <option data-platform="og" value="og">OG视讯</option>' +
                    ' <option data-platform="bbin" value="bbin">BBIN视讯</option>' +
                    ' <option data-platform="mw" value="mw">MW电子</option>' +
                    ' <option data-platform="cq" value="cq">CQ9电子</option>' +
                    ' <option data-platform="fg" value="fg">FG电子</option>' +
                    ' <option data-platform="avia" value="avia">泛亚电竞</option>' +
                    ' <option data-platform="fire" value="fire">雷火电竞</option>' +
                    '</select>'+
                    '转入：<select class="transfer_select_to" >' +
                    '      <option data-platform="hg" value="hg">中心钱包</option>' +
                    '</select>'+
                    '</div>'+
                    '<div class="ed_money_input"> 金额：<input type="number" class="transfer_input ed_change_inpout" placeholder="请输入转换金额"/></div> '+
                    '</div><div class="modalFooter"><button type="button" class="btn-add transfer_btn_ed btn_game">确认转换</button></div>'+
                    '</div>';

                $('.edzh_div').html(zh_str);
                // layer.open({
                //     type: 1,
                //     area: ['400px', '230px'],
                //     title: '额度转换',
                //     shadeClose: true, //点击遮罩关闭
                //     content: zh_str
                // });

            // })

            // 转换额度到体育
            $('body').off().on('click','.transfer_btn_ed',function () {
                var  plat = $('.transfer_select_fm').find('option:selected').attr('data-platform');
                var  p_fm = $('.transfer_select_fm').val();
                var  p_to = $('.transfer_select_to').val();
                var  mon = $(indexCommonObj.transfer_input).val() ; // 金额

                if(!plat || !p_fm || !p_to){
                    layer.msg('请选择平台',{time:alertTime});
                    return ;
                }

                if(mon ==0 || mon == NaN || mon ==null ){
                    layer.msg('没有需要转入的金额',{time:alertTime});
                    return ;
                }
                indexCommonObj.transferAccounts(plat,p_fm,p_to,mon) ;
            });

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

        changeMoneyToHg();
        withdrawSubmit() ;
        oneRecovery();
    });


</script>
