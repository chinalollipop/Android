<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
require_once ROOT_DIR.'/common/count/function.php';
include_once ROOT_DIR."/common/bankNameList.php";

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];


if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
require ("../include/traditional.$langx.inc.php");

$username=$_SESSION['UserName'];
$sql = "select ID,Bank_Name,Bank_Address,Bank_Account,Usdt_Address,UserName,owe_bet,owe_bet_time,Alias,Money from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
if(!$row['Alias']){ // 先设置真实姓名
    echo "<script language=javascript>alert('请先设置您的真实姓名！'); location.href = 'set_realname.php?uid=".$uid."&langx=".$langx."';</script>";
}

$Bank_Name = $row['Bank_Name'];
$Bank_Account = $row['Bank_Account'] ;
$Bank_Address = $row['Bank_Address'] ;
$Usdt_Address = $row['Usdt_Address'] ;

if( $Bank_Name=='' || $Bank_Address=='' || $Bank_Account==''){
	echo "<script language=javascript>alert('请先设置您的银行账号信息！'); location.href = 'set_bank.php?uid=".$uid."&langx=".$langx."';</script>";
}
$hgId=$row['ID'];
$hgFund= formatMoney($row['Money']); // 取整

$membermessage = getMemberMessage($username,'2'); // 取款短信

// 统计会员已打码量
$countTime = (empty($row['owe_bet_time']) || $row['owe_bet_time'] == '0000-00-00 00:00:00' ? '1969-12-31 20:00:00' : $row['owe_bet_time']); // 开始统计时间
$countData = countBet($countTime, $hgId);
$resultData = [];
foreach ($countData as $key => $value){
    $resultData[$key] = [
        'msg' => typeMsg($key),
        'value' => $value
    ];
}

$bankList = returnBnakName();

?>
<html>
<head>
<title>History</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--<link rel="stylesheet" href="/style/member/mem_body<?php/*=$css*/?>.css?v=<?php echo AUTOVER; ?>" type="text/css">-->
    <link rel="stylesheet" href="../../../style/onlinepay.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <link rel="stylesheet" href="../../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    #MFT #box{width:780px}
    #MFT .news{white-space:normal!important;color:#300;text-align:left;padding:2px 4px}
    .STYLE1{color:#FF0000}
    .frm-tab{margin-bottom:10px;position:relative;border:navajowhite;background:transparent}
    .edzh-btn{border-radius:20px;margin:15px 0 28px 72px}
    .btn3,.btn4{width:auto}
    div.jbox .jbox-title-panel{background:#bf0058;background:-webkit-gradient(linear,left top,left bottom,from(#D01313),to(#990046));background:-moz-linear-gradient(top,#D01313,#990046);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#D01313',endColorstr='#990046')}
    div.jbox .jbox-button{background:#bf0058;background:-webkit-gradient(linear,left top,left bottom,from(#BD0C24),to(#990046));background:-moz-linear-gradient(top,#D01313,#990046);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#D01313',endColorstr='#990046')}
    div.jbox .jbox-button-hover{background:#bf0058;background:-webkit-gradient(linear,left top,left bottom,from(#bf0058),to(#730035));background:-moz-linear-gradient(top,#bf0058,#730035);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#bf0058',endColorstr='#730035')}
    div.jbox .jbox-button-active{background:-webkit-gradient(linear,left top,left bottom,from(#730035),to(#bf0058));background:-moz-linear-gradient(top,#730035,#bf0058);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#730035',endColorstr='#bf0058')}
    .jbox-content>div{margin:5px !important;padding-left:0px !important}
    #creditsChangeBox div{margin-top:15px;padding:10px 15px;background:#f2eedd;overflow:hidden}
    #creditsChangeBox div input{transition:background 0.3s ease;float:left;cursor:pointer;border:0px;width:48%;height:34px;background:#b98e2f;font-size:14px;font-weight:bold;color:#fff;box-shadow:inset 1px 1px 1px rgba(0,0,0,0.3);border-radius:35px}
    #creditsChangeBox div input#btnClose{float:right;background:#676767}
    .game{background-color:#B9B9A3;font-size:0.75em;width:350px}
    .b_rig{background-color:#FFF;text-align:center;white-space:nowrap}
    .game td,.more td{padding:1px 4px;font-size:12px;border-right:1px solid #B9B9A3;border-bottom:1px solid #B9B9A3;font-family:Arial,Helvetica,SimSun,sans-serif}
    .wechat_img{display: inline-block;width: 130px;height: 130px;position: absolute;background-size: 100% !important;top: 38px;left: 7px;}
    .msg-div .fm label{width: 140px;text-align: center;}
    .fm input[type="radio"]{width: 20px;height: 20px;margin: 8px 5px 0 0;}
</style>

</HEAD>
<BODY id="MFT" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<div class="mv ui-main">
    <div class="mc-con3">
        <div class="mc-rtct" id="div_Bg">
            <div class="mc-ct" id="div_Main">
                <div class="goBack" ><a href="../onlinepay/deposit_withdraw.php?uid=<?php echo $uid?>&langx=zh-cn" target="body">返回上一页</a></div>
                <form method="post" name="main" action="take.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>" onSubmit="return VerifyData();">


                    <div class="fd-box3 mc-mn-bg" id="div_Detail">
                        <div class="clear"></div>
                        <div class="UserBankWD fm w100">

                            <div id="OnlineBank">

                            </div>

                        </div>
                        <div class="clear"></div>

                        <div class="clear"></div>
                        <div class="frm-tab" style="margin-bottom:10px;position: relative;">
                            <div class="ll l1">
                                <div>体育额度：<span class="hgmoney"><?php echo $hgFund;?></span></div>
                                <?php
                                if(TPL_FILE_NAME=='newhg') { // 新皇冠 彩票
                                    echo '<div>彩票额度：<span class="gmcpmoney">0</span></div>';
                                }else{
                                    echo '<div>彩票额度：<span class="cpmoney">0</span></div>';
                                }
                                ?>

                                <div>AG真人视讯与电子额度：<span style="line-height:25px;" class="agmoney"></span></div><br>
                                <div>开元棋牌额度：<span class="kymoney"></span></div>
                               <!-- <div>皇冠棋牌额度：<span class="ffmoney"></span></div>-->
                                <div> VG棋牌额度：<span class="vgmoney"></span></div>
                                <div> 乐游棋牌额度：<span class="lymoney"></span></div><br>
                                <div> 快乐棋牌额度：<span class="klmoney"></span></div>
                                <div> MG电子额度：<span class="mgmoney"></span></div>
                                <div> OG视讯额度：<span class="ogmoney"></span></div><br>
                                <div> BBIN视讯额度：<span class="bbinmoney"></span></div>
                                <div> MW电子额度：<span class="mwmoney"></span></div>
                                <div> CQ9电子额度：<span class="cqmoney"></span></div><br>
                                <div> FG电子额度：<span class="fgmoney"></span></div>
                                <div> 泛亚电竞额度：<span class="aviamoney"></span></div>
                                <div> 雷火电竞额度：<span class="firemoney"></span></div>
                            </div>
                            <span style="width: 145px;height: 207px;  position: absolute;top: 0;right:65px;">
                                <?php
                                    if(TPL_FILE_NAME !='newhg'){
                                        echo '<span class="wechat_img" style="background: url('.getPicConfig('server_wechat_code').')"></span><img src="../../../images/2018/deposit/kefupic.jpg">';
                                    }
                                ?>

                            </span>
                            <a class="btn3 btn4 wsnwp edzh-btn" href="javascript:void(0);" name="btnSubmit" id="btnSubmit" onclick="agjb('<?php echo $uid;?>',0);">
                                <span class="icon_enter"></span>
                                <span>额度转换</span>
                            </a>

                            <div class="wrongMsg2" style="left:90px ;display:none;">
                                <table>
                                    <tbody>
                                    <tr>
                                        <td><span class="icon_er1"></span></td>
                                        <td id="td_errmsg"></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <span class="lftArrow"></span>
                            </div>

                            <div class="clear"></div>
                            <div class="fm items">
                                <label>提款打码量:</label>
                                <div class="right" id="owe_bet"><?php echo $row['owe_bet']?></div>
                            </div>
                            <div class="fm items">
                                <label>已打码量:</label>
                                <div class="mn-ipt">
                                    <span id="total_bet" style="line-height: 35px;"><?php echo $resultData['total']['value']?></span>
                                    <span style="color:#F00">&nbsp;&nbsp;
                                    <a href="javascript:;" id="show_user_bet" class="btn3 btn4" onclick="betDetail()" style="float: none;">
                                        <span>查看详情</span>
                                    </a>
                                    </span>
                                </div>
                            </div>
                            <div class="fm">

                                <input type="radio" name="choose_type" class="checkbox choose_w_type" checked data-type="bank">

                                <label style="width: 72px;">选择银行:</label>
                                <div class="mn-ipt">
                                    <span id="spn_bank" style="line-height: 35px;"><?php echo $Bank_Name?></span>
                                    <input id="Bank_Name" name="Bank_Name" type="hidden" value="<?php echo $Bank_Name?>">
                                    <span style="color:#F00">&nbsp;&nbsp;
									<a id="tranbank" class="btn3 btn4" onclick="tranSetbank('<?php echo $uid ?>')" style="float: none;">
										<span class="icon_enter"></span>
                                        <span>更换银行或USDT账号</span>

									</a>
								</span>
                                </div>
                            </div>

                            <div class="has_usdt fm" style="display: none">
                                <input type="radio" name="choose_type" class="checkbox choose_w_type" data-type="usdt">
                                <label style="width: 160px;">USDT(TRC20)提款地址:</label>
                                <div class="mn-ipt">
                                    <span id="spn_usdt" style="line-height: 35px;"><?php echo returnBankAccount($Usdt_Address);?></span>

                                </div>
                            </div>

                        </div>
                        <div class="fm items">
                            <label>会员名称:</label>
                            <div class="right" id="realname"><?php echo returnRealName($row['Alias'])?></div>
                            <!--<input id="Alias" name="Alias" type="hidden" value="<?php /*echo $row['Alias']*/?>">-->
                        </div>

                        <div class="fm items">
                            <label>银行账号:</label>
                            <div class="right">
                                <span id="spn_bank_account"> <?php echo returnBankAccount($Bank_Account) ;?> </span>
                                <span style="color:#F00">&nbsp;&nbsp;<span style="color:#ff0000;">&nbsp;&nbsp;&nbsp;&nbsp;注：</span>为了您的账号安全,账号已做加密!</span>
                                <!--<input id="Bank_Account" name="Bank_Account" type="hidden" value="<?php /*echo $Bank_Account*/?>">-->
                                <input id="Bank_Address" name="Bank_Address" type="hidden" value="<?php echo $Bank_Address?>">

                               <!-- <input id="Usdt_Address" name="usdt_address" type="hidden" value="<?php /*echo $Usdt_Address*/?>">-->
                                <input id="usdt_rate" name="usdt_rate" type="hidden" > <!-- 用户最近一笔 usdt 充值汇率 -->
                            </div>
                        </div>

                        <div class="fm items">
                            <p>此银行卡的开户名必须与您帐号所填真实姓名一致，否则提款可能会失败。<br>您所填写的银行不需要开通网银亦可以提款。</p>
                        </div>

                        <div class="fm items" style="width: 100%;line-height: 20px;">
                            <label>提款金额:</label>
                            <div class="right" style="position: relative;">
                                <input id="Money" name="Money" data-val="<?php echo  $row['Money'] ?>" maxlength="10" onkeyup="this.value=this.value.replace(/\D/g,'');countUsdtMount()" onafterpaste="this.value=this.value.replace(/\D/g,'')" style="width: 262px; " placeholder="请绑定银行卡">
                                <span class="ord_delBTN" style="top: 5px; left: 295px;"></span>
                                <!-- 快捷金额按钮-->
                                <div class="betAmount" style="width: 52%; height: 32px;padding: 5px 0 0 6px; float:left;">
                                    <ul>
                                        <li value="100" style="width: 56px">100</li>
                                        <li value="500" style="width: 56px">500</li>
                                        <li value="1000" style="width: 56px">1000</li>
                                        <li value="5000" style="width: 56px">5,000</li>
                                        <li value="10000" style="width: 56px">10,000</li>
                                        <li value="50000" style="width: 56px">50,000</li>
                                    </ul>
                                </div>
                                <!-- 有充值过才显示usdt 金额 -->
                                <div class="show_usdt" style="display:none;margin-left: 90px;font-size: 18px;line-height: 40px;">
                                    <span>USDT提币数量：<span class="red_color pay_to_usdt">0</span></span>
                                    <p>实时汇率：<span class="red_color new_usdt_rate"></span></p>
                                </div>
                                <span style="color:#ff0000;padding-left: 90px;">注：最低提款（元）：100</span>
                            </div>
                        </div>

                        <div class="fm items">
                            <label>取款密码:</label>
                            <div class="wdpsw right" id="withdrawal_passwd">
                                <input type="text" onfocus="this.type='password'" id="address1" name="address1" value="" maxlength="1" class="withdrawpassword2" autocomplete="off">
                                <input type="text" onfocus="this.type='password'" id="address2" name="address2" value="" maxlength="1" class="withdrawpassword2">
                                <input type="text" onfocus="this.type='password'" id="address3" name="address3" value="" maxlength="1" class="withdrawpassword2">
                                <input type="text" onfocus="this.type='password'" id="address4" name="address4" value="" maxlength="1" class="withdrawpassword2 ">
                                <input type="text" onfocus="this.type='password'" id="address5" name="address5" value="" maxlength="1" class="withdrawpassword2">
                                <input type="text" onfocus="this.type='password'" id="address6" name="address6" value="" maxlength="1" class="withdrawpassword2 ">
                                <ul class="pwd_num" style="z-index: 999999;margin-left: 150px;">
                                    <li class="num">0</li><li class="num">1</li><li class="num">2</li><li class="num">3</li><li class="num">4</li><li class="num">5</li>
                                    <li class="num">6</li><li class="num">7</li><li class="num">8</li><li class="num">9</li><li class="close">关闭</li><li id="delete">清空</li>
                                </ul>
                            </div>
                            <!--<span style="color:#ff0000;padding-left: 4px;">注：若您的密码为四位，请填写前四位即可</span>-->
                        </div>

                        <div class="fm items">
                            <input type="hidden" name="Key" id="Key" value="Y">
                            <a class="mainSubmit btn3 btn4 wsnwp" name="mainSubmit" id="mainSubmit" onclick="mainSubmit(this)" data-type="bank">
                                <span class="icon_enter"></span>
                                <span id="next">确认提款</span>
                            </a>

                            <a class="btn3 btn4 wsnwp" href="javascript:void(0);" name="btnReset" id="btnReset" style="margin-left: 15px;" >
                                <span class="icon_enter"></span>
                                <span>重新填写</span>
                            </a>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<div id="user_bet" style="display: none">
    <table border="0" cellspacing="1" cellpadding="0" class="game" style="width:100%;">
        <tr class="b_rig">
            <td width="50%">类型</td>
            <td width="50%">打码量</td>
        </tr>
        <?php foreach ($resultData as $key => $value){ ?>
            <tr class="b_rig">
                <td><?php echo $value['msg'];?></td>
                <td><?php echo $value['value'];?></td>
            </tr>
        <?php }?>
        <tr class="b_rig">
            <td colspan="2">
                <input type="button" class="jbox-button jbox-button-focus" value="确定" id="trans_blance" onclick="javascript:$.jBox.close();" style="padding:1px 10px; font-weight:bold; cursor:pointer;" />
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/withdrawal.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>
<script>
    var tpl_file_name = '<?php echo TPL_FILE_NAME;?>';
    var uid = '<?php echo $uid;?>';
    var dataParams = {uid:uid,action:'b'};
    var bank_name_list = $.parseJSON('<?php echo json_encode($bankList, JSON_UNESCAPED_UNICODE);?>');
    var Bank_Name = '<?php echo $Bank_Name;?>';
    var hide_Bank_Account = '<?php echo returnBankAccount($Bank_Account);?>' ;
    var Bank_Address = '<?php echo $Bank_Address;?>';
    var hide_Usdt_Address = '<?php echo returnBankAccount($Usdt_Address);?>';

    if(tpl_file_name=='newhg'){
        getUserBanlance(uid,'gmcp'); // 三方彩票
    }else {
        getUserUsdtRate();
        chooseWithdraw();
    }
    get_blance();
    get_ky_balance();
    //get_ff_balance();
    get_vg_balance();
    get_ly_balance();
    get_kl_balance();
    get_mg_balance();
    get_og_balance();
    get_bbin_balance();
    get_mw_balance();
    get_cq_balance();
    get_fg_balance();
    get_avia_balance();
    get_fire_balance();


    // usdt 金额输入与计算
    function countUsdtMount(){
        var $pay_to_usdt = $('.pay_to_usdt');
        var usdt_rate = Number($('#usdt_rate').val());
        var cz_val = $('#Money').val();
        if(!usdt_rate){
            return;
        }
        var zf_val = cz_val/(usdt_rate); // 需要转入的usdt
        zf_val = advFormatNumber(zf_val,2); // 保留两位小数
        $pay_to_usdt.text(zf_val);
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

    function get_blance(){
        $('.agmoney').html('加载中');
        $.ajax({
            type: 'POST',
            url:'withdrawal_tran_api.php?_='+Math.random(),
            data:dataParams,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    $('.hgmoney').html(ret.balance_hg);
                    $('.agmoney').html(ret.balance_ag);
                    $('.cpmoney').html(ret.balance_cp);
                }
                else{
                    $('.agmoney').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }
    function get_ky_balance() {
        $('.kymoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../ky/ky_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('.hgmoney').html(item.data.hg_balance);
                    $('.kymoney').html(item.data.ky_balance);
                } else {
                    alert(item.message);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_ff_balance() {
        $('.ffmoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../hgqp/hg_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('.hgmoney').html(item.data.hg_balance);
                    $('.ffmoney').html(item.data.ff_balance);
                } else {
                    alert(item.message);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_vg_balance() {
        $('.vgmoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../vgqp/vg_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('.hgmoney').html(item.data.hg_balance);
                    $('.vgmoney').html(item.data.vg_balance);
                } else {
                    alert(item.message);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_ly_balance() {
        $('.lymoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../lyqp/ly_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('.hgmoney').html(item.data.hg_balance);
                    $('.lymoney').html(item.data.ly_balance);
                } else {
                    alert(item.message);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_kl_balance() {
        $('.klmoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../klqp/kl_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('.hgmoney').html(item.data.hg_balance);
                    $('.klmoney').html(item.data.kl_balance);
                } else {
                    alert(item.message);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_mg_balance() {
        $('.mgmoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../mg/mg_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.err == 0) {
                    $('.hgmoney').html(item.hg_balance);
                    $('.mgmoney').html(item.balance_mg);
                } else {
                    alert(item.msg);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_og_balance() {
        $('.ogmoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../zrsx/og/og_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                //console.log(item)
                if(item.err == 0) {
                    $('.hgmoney').html(item.hg_balance);
                    $('.ogmoney').html(item.balance_og);
                } else {
                    alert(item.msg);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_bbin_balance() {
        $('.bbinmoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../zrsx/bbin/bbin_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                //console.log(item)
                if(item.err == 0) {
                    $('.hgmoney').html(item.hg_balance);
                    $('.bbinmoney').html(item.bbin_balance);
                } else {
                    alert(item.msg);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_mw_balance() {
        $('.mwmoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../mw/mw_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.err == 0) {
                    $('.hgmoney').html(item.msg.hg_balance);
                    $('.mwmoney').html(item.msg.mw_balance);
                } else {
                    alert(item.msg);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_cq_balance() {
        $('.cqmoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../cq9/cq9_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.err == 0) {
                    $('.hgmoney').html(item.msg.hg_balance);
                    $('.cqmoney').html(item.msg.cq_balance);
                } else {
                    alert(item.msg);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_fg_balance() {
        $('.fgmoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../fg/fg_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.err == 0) {
                    $('.hgmoney').html(item.msg.hg_balance);
                    $('.fgmoney').html(item.msg.fg_balance);
                } else {
                    alert(item.msg);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_avia_balance() {
        $('.aviamoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../avia/avia_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.err == 0) {
                    $('.hgmoney').html(item.msg.hg_balance);
                    $('.aviamoney').html(item.msg.avia_balance);
                } else {
                    alert(item.msg);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }
    function get_fire_balance() {
        $('.firemoney').html('加载中');
        $.ajax({
            type : 'POST',
            url : '../thunfire/fire_api.php?_=' + Math.random(),
            data : dataParams,
            dataType : 'json',
            success:function(item) {
                if(item.err == 0) {
                    $('.hgmoney').html(item.msg.hg_balance);
                    $('.firemoney').html(item.msg.fire_balance);
                } else {
                    alert(item.msg);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }

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
    function betDetail() {
        $.jBox('id:user_bet', {
            title: "打码量列表",
            width: 400,height: "auto",border: 0,showIcon: false,buttons: {}
        });
    }
</script>
</BODY>
</HTML>
