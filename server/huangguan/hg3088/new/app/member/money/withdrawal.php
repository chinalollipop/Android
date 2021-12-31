<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];


if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
require ("../include/traditional.$langx.inc.php");
$username=$_SESSION['UserName'];
$sql = "select ID,Bank_Name,Bank_Address,Bank_Account,UserName,Alias,Money from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
if(!$row['Alias']){ // 先设置真实姓名
    echo "<script language=javascript>alert('请先设置您的真实姓名！'); location.href = 'set_realname.php?uid=".$uid."&langx=".$langx."';</script>";
}
if( $row['Bank_Name']=='' || $row['Bank_Address']=='' || $row['Bank_Account']==''){
    echo "<script language=javascript>alert('请先设置您的银行账号信息！'); location.href = 'set_bank.php?uid=".$uid."&langx=".$langx."';</script>";
}
$hgId=$row['ID'];
$hgFund= intval($row['Money']); // 取整

$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
$sql = "select lcurrency from ".$database['cpDefault']['prefix']."user where hguid=".$hgId;
$result = mysqli_query($cpMasterDbLink,$sql);
$cou = mysqli_num_rows($result);
if($cou==0){
    echo "<script language=javascript>alert('彩票余额获取失败！');</script>";
}
$cprow = mysqli_fetch_assoc($result);
$cpFund = $cprow['lcurrency'];
$membermessage = getMemberMessage($username,'2'); // 取款短信

// ----------------------------------打码量限制。1倍流水或称全额投注 Start*/
/*
// 存款金额
$result_deposit = mysqli_query($dbLink,"select SUM(Gold) as deposit_total from ".DBPREFIX."web_sys800_data WHERE UserName = '{$row['UserName']}' AND Payway in ('N','W') AND Checked=1");
$cou_deposit=mysqli_num_rows($result_deposit);
$row_deposit=mysqli_fetch_assoc($result_deposit);
$deposit_total=$row_deposit['deposit_total'];
if ($cou_deposit==0 or $deposit_total['deposit_total']<0 or !isset($deposit_total['deposit_total'])){
    echo "<Script language=javascript>alert('提款失败!原因:请先存款!');history.back();</script>";
    die();
}

// 打码量
// 从每日报表中捞取打码量
// 体育
$result_bet_history = mysqli_query($dbLink, "select sum(total) as history_bet_total from ".DBPREFIX."web_report_history_report_data WHERE username = '{$row['UserName']}'");
$cou_bet_history=mysqli_num_rows($result_bet_history);
if ($cou_bet_history>0){
    $row_bet_history=mysqli_fetch_assoc($result_bet_history);
    $history_bet_total = $row_bet_history['history_bet_total'];
}

// AG
$result_ag_bet_history = mysqli_query($dbLink, "select sum(total) as history_ag_bet_total from ".DBPREFIX."ag_projects_history_report WHERE username = '{$row['UserName']}'");
$cou_ag_bet_history=mysqli_num_rows($result_ag_bet_history);
if ($cou_ag_bet_history>0){
    $row_ag_bet_history=mysqli_fetch_assoc($result_ag_bet_history);
    $history_ag_bet_total = $row_ag_bet_history['history_ag_bet_total'];
}

// 彩票
$aCp_default = $database['cpDefault'];
$cpDbLink = @mysqli_connect($aCp_default['host'],$aCp_default['user'],$aCp_default['password'],$aCp_default['dbname'],$aCp_default['port']) or die("mysqli connect error".mysqli_connect_error());
$result_cp_bet_history = mysqli_query($cpDbLink, "select sum(total) as history_cp_bet_total from gxfcy_history_bill_report WHERE username = '{$row['UserName']}'");
$cou_cp_bet_history=mysqli_num_rows($result_cp_bet_history);
if ($cou_cp_bet_history>0){
    $row_cp_bet_history=mysqli_fetch_assoc($result_cp_bet_history);
    $history_cp_bet_total = $row_cp_bet_history['history_cp_bet_total'];
}

$today_bet_total = $today_ag_bet_total = $today_cp_bet_total =0;
// 当天的打码量
// 当天时间
if((int)date("G") < 3){

    $current_start_day = date("Y-m-d", strtotime("-1 day"));
    $current_end_day = date('Y-m-d H:i:s',time());

    // 体育
    $result_bet_today = mysqli_query($dbLink, "select sum(BetScore) as today_bet_total from ".DBPREFIX."web_report_data WHERE M_Name = '{$row['UserName']}'and BetTime BETWEEN '".$current_start_day."' and '".$current_end_day."'");
    $cou_bet_today=mysqli_num_rows($result_bet_today);
    if ($cou_bet_today!=0){
        $row_bet_today=mysqli_fetch_assoc($result_bet_today);
        $today_bet_total=$row_bet_today['today_bet_total'];
    }

    // AG
    $result_ag_bet_today = mysqli_query($dbLink, "select sum(amount) as today_ag_bet_total from ".DBPREFIX."ag_projects WHERE username = '{$row['UserName']}' and bettime BETWEEN '".$current_start_day."' and '".$current_end_day."'");
    $cou_ag_bet_today=mysqli_num_rows($result_ag_bet_today);
    if ($cou_ag_bet_today!=0){
        $row_ag_bet_today=mysqli_fetch_assoc($result_ag_bet_today);
        $today_ag_bet_total=$row_ag_bet_today['today_ag_bet_total'];
    }
    // 彩票
    $current_end_day = date('Y-m-d H:i:s',time()+86400);
    $result_cp_bet_today = mysqli_query($cpDbLink, "select sum(total) as today_cp_bet_total from gxfcy_bill WHERE username = '{$row['UserName']}' and bet_time BETWEEN '".strtotime($current_start_day)."' and '".strtotime($current_end_day)."'");
    $cou_cp_bet_today=mysqli_num_rows($result_cp_bet_today);
    if ($cou_cp_bet_today!=0){
        $row_cp_bet_today=mysqli_fetch_assoc($result_cp_bet_today);
        $today_cp_bet_total=$row_cp_bet_today['today_cp_bet_total'];
    }
}

$total = $history_bet_total+$history_ag_bet_total+$history_cp_bet_total+$today_bet_total+$today_ag_bet_total+$today_cp_bet_total;


if ($deposit_total > $total){
    echo "<Script language=javascript>alert('提款失败!原因:投注金额小于存款金额!');history.back();</script>";
    die();
}*/

// ----------------------------------打码量限制。1倍流水或称全额投注 End
?>
<html>
<head>
    <title>History</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--<link rel="stylesheet" href="/style/member/mem_body<?php/*=$css*/?>.css?v=<?php echo AUTOVER; ?>" type="text/css">-->
    <link rel="stylesheet" href="../../../style/onlinepay.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <link rel="stylesheet" href="../../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>

        #MFT #box { width:780px;}
        #MFT .news { white-space: normal!important; color:#300; text-align:left; padding:2px 4px;}
        .STYLE1 {color: #FF0000}
        .frm-tab{ margin-bottom:10px;position: relative;border: navajowhite;background: transparent;}
        .edzh-btn{ border-radius: 20px;margin:15px 0 28px 72px;}
        .btn3, .btn4{ width: auto;}
        div.jbox .jbox-title-panel{background: #bf0058;background: -webkit-gradient(linear, left top, left bottom, from(#D01313), to(#990046));background: -moz-linear-gradient(top,  #D01313,  #990046);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#D01313', endColorstr='#990046');}
        div.jbox .jbox-button{background: #bf0058;background: -webkit-gradient(linear, left top, left bottom, from(#BD0C24), to(#990046));background: -moz-linear-gradient(top,  #D01313,  #990046);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#D01313', endColorstr='#990046');}
        div.jbox .jbox-button-hover{background: #bf0058;background: -webkit-gradient(linear, left top, left bottom, from(#bf0058), to(#730035));background: -moz-linear-gradient(top,  #bf0058,  #730035);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#bf0058', endColorstr='#730035');}
        div.jbox .jbox-button-active{background: -webkit-gradient(linear, left top, left bottom, from(#730035), to(#bf0058));background: -moz-linear-gradient(top,  #730035,  #bf0058);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#730035', endColorstr='#bf0058');}
        .jbox-content>div{ margin: 5px !important;padding-left: 0px !important; }
        #creditsChangeBox div {
            margin-top: 15px;
            padding: 10px 15px;
            background: #f2eedd;
            overflow: hidden;
        }
        #creditsChangeBox div input {
            transition: background 0.3s ease;
            float: left;
            cursor: pointer;
            border: 0px;
            width: 48%;
            height: 34px;
            background: #b98e2f;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            box-shadow: inset 1px 1px 1px rgba(0,0,0,0.3);
            border-radius: 35px;
        }
        #creditsChangeBox div input#btnClose {
            float: right;
            background: #676767;
        }
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
                                <div>体育额度：<span id="hgmoney"><?php echo $hgFund;?></span></div><br>
                                <div>彩票额度：<span id="cpmoney"><?php echo $cpFund;?></span></div>
                                <div>国民彩票额度：<span id="gmcpmoney"></span></div><br>
                                <div>真人视讯与电子额度：<span style="line-height:25px;" id="agmoney"></span></div>
                                <!--                                <div>电子额度：<span id="ptmoney">111</span></div>-->
                                <div>开元棋牌额度：<span id="kymoney"></span></div><br>
                                <div>皇冠棋牌额度：<span id="ffmoney"></span></div>
                                <div> VG棋牌额度：<span id="vgmoney"></span></div><br>
                                <div> 乐游棋牌额度：<span id="lymoney"></span></div>
                                <div> MG电子额度：<span id="mgmoney"></span></div><br>
                            </div>
                            <span style="width: 145px;height: 207px;  position: absolute;top: 0;right:65px;">
                                <img src="../../../images/deposit/kefupic.png?v=1"></span>
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

                            <div class="fm">
                                <label style="width: 72px;">选择银行:</label>
                                <div class="mn-ipt">
                                    <span id="spn_bank" style="line-height: 35px;"><?php echo $row['Bank_Name']?></span>
                                    <input id="Bank_Name" name="Bank_Name" type="hidden" value="<?php echo $row['Bank_Name']?>">
                                    <span style="color:#F00">&nbsp;&nbsp;
									<a id="tranbank" class="btn3 btn4" onclick="tranSetbank('<?php echo $uid ?>')" style="float: none;">
										<span class="icon_enter"></span><span>更换银行</span>
									</a>
								</span>
                                </div></div></div>
                        <div class="fm items">
                            <label>会员名称:</label>
                            <div class="right" id="realname"><?php echo returnRealName($row['Alias'])?></div>
                            <input id="Alias" name="Alias" type="hidden" value="<?php echo $row['Alias']?>">
                        </div>

                        <div class="fm items">
                            <label>银行账号:</label>
                            <div class="right">
                                <span id="spn_bank_account"> <?php echo returnBankAccount($row['Bank_Account']) ;?> </span>
                                <span style="color:#F00">&nbsp;&nbsp;<span style="color:#ff0000;">&nbsp;&nbsp;&nbsp;&nbsp;注：</span>为了您的账号安全,账号已做加密!</span>
                                <input id="Bank_Account" name="Bank_Account" type="hidden" value="<?php echo $row['Bank_Account']?>">
                                <input id="Bank_Address" name="Bank_Address" type="hidden" value="<?php echo $row['Bank_Address']?>">

                            </div>
                        </div>

                        <div class="fm items">
                            <p>此银行卡的开户名必须与您帐号所填真实姓名一致，否则提款可能会失败。<br>您所填写的银行不需要开通网银亦可以提款。</p>
                        </div>

                        <div class="fm items" style="width: 100%">
                            <label>提款金额:</label>
                            <div class="right" style="position: relative;">
                                <input id="Money" name="Money" data-val="<?php echo  $row['Money'] ?>" size="22" maxlength="10" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" style="width: 262px; " placeholder="请绑定银行卡">
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

                                <span style="color:#ff0000;padding-left: 126px;">注：最低提款（元）：100</span>
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
                            <a class="btn3 btn4  wsnwp" href="javascript:void(0);" name="mainSubmit" id="mainSubmit" onclick="mainSubmit()">
                                <span class="icon_enter"></span>
                                <span id="next">下一步</span>
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
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/withdrawal.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>
<script>

    get_blance();
    get_gmcp_balance();
    get_ky_balance();
    get_ff_balance();
    get_vg_balance();
    get_ly_balance();
    get_mg_balance();

    function get_blance(){
        //$('#hgmoney').html('加载中');
        $('#agmoney').html('加载中');
        // $('#cpmoney').html('加载中');
        var dat={};
        dat.uid='<?php echo $uid;?>';
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'withdrawal_tran_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){

                    $('#hgmoney').html(ret.balance_hg);
                    $('#agmoney').html(ret.balance_ag);
                    $('#cpmoney').html(ret.balance_cp);
                }
                else{
                    //$('#hgmoney').html('0.00');
                    $('#agmoney').html('0.00');
                    // $('#cpmoney').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    function get_ky_balance() {
        $('#kymoney').html('加载中');
        var data = {};
        data.uid = '<?php echo $uid;?>';
        data.action = 'b';
        $.ajax({
            type : 'POST',
            url : '../ky/ky_api.php?_=' + Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('#hgmoney').html(item.data.hg_balance);
                    $('#kymoney').html(item.data.ky_balance);
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
        $('#ffmoney').html('加载中');
        var data = {};
        data.uid = '<?php echo $uid;?>';
        data.action = 'b';
        $.ajax({
            type : 'POST',
            url : '../hgqp/hg_api.php?_=' + Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('#hgmoney').html(item.data.hg_balance);
                    $('#ffmoney').html(item.data.ff_balance);
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
        $('#vgmoney').html('加载中');
        var data = {};
        data.uid = '<?php echo $uid;?>';
        data.action = 'b';
        $.ajax({
            type : 'POST',
            url : '../vgqp/vg_api.php?_=' + Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('#hgmoney').html(item.data.hg_balance);
                    $('#vgmoney').html(item.data.vg_balance);
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
        $('#lymoney').html('加载中');
        var data = {};
        data.uid = '<?php echo $uid;?>';
        data.action = 'b';
        $.ajax({
            type : 'POST',
            url : '../lyqp/ly_api.php?_=' + Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('#lymoney').html(item.data.ly_balance);
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
        $('#mgmoney').html('加载中');
        var data = {};
        data.uid = '<?php echo $uid;?>';
        data.action = 'b';
        $.ajax({
            type : 'POST',
            url : '../mg/mg_api.php?_=' + Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                console.log(item)
                if(item.err == 0) {
                    $('#mgmoney').html(item.balance_mg);
                } else {
                    alert(item.msg);
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }

    function get_gmcp_balance() {
        $('#gmcpmoney').html('加载中');
        var data = {};
        data.uid = '<?php echo $uid;?>';
        data.action = 'b';
        $.ajax({
            type : 'POST',
            url : '../gmcp/cp_api.php?_=' + Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                if(item.code == 0) {
                    $('#hgmoney').html(item.data.hg_balance);
                    $('#gmcpmoney').html(item.data.gmcp_balance);
                } else {
                    alert(item.message);
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

</script>
</BODY>
</HTML>
