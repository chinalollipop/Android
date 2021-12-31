<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
include_once ROOT_DIR."/common/bankNameList.php";

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$action=$_REQUEST['action'];
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$sql = "select Bank_Name,Bank_Address,Bank_Account from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
if( $row['Bank_Name']!=''&& $row['Bank_Address']!='' && $row['Bank_Account']!=''){
	echo "<script language=javascript>alert('您已经设置过银行账号信息！'); location.href = 'withdrawal.php?uid=".$uid."&langx=".$langx."';</script>";
}
if ($action==1){
	$mysql="update ".DBPREFIX.MEMBERTABLE." set Bank_Address='".$_REQUEST["Bank_Address"]."',Bank_Account='".$_REQUEST["Bank_Account"]."' , Online=1 , OnlineTime=now() where UserName='".$_REQUEST["username"]."'";
	mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
	echo "<script language=javascript>alert('银行账号信息设置成功！'); location.href = 'withdrawal.php?uid=".$uid."&langx=".$langx."';</script>";
}

$bankList = returnBnakName();

?>
<html>
<head>
<title>History</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
#creditsChangeBox div {margin-top: 15px;padding: 10px 15px;background: #f2eedd;overflow: hidden;}
#creditsChangeBox div input {transition: background 0.3s ease;float: left;cursor: pointer;border: 0px;width: 48%;height: 34px;background: #b98e2f;font-size: 14px;font-weight: bold;color: #fff;box-shadow: inset 1px 1px 1px rgba(0,0,0,0.3);border-radius: 35px;}
#creditsChangeBox div input#btnClose {float: right; background: #676767; }
.not-setbank{ line-height: 35px;float: left;margin-right: 5px;}
div.jbox .jbox-close{ display: none !important;}
.msg-div .fm label{width: 140px;text-align: center;}
</style>

</HEAD>
<BODY id="MFT">
<div class="mv ui-main">
    <div class="mc-con3">
        <div class="mc-rtct" id="div_Bg">
            <div class="mc-ct" id="div_Main">



            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="../../../js/jquery.js"></script>

<script type="text/javascript" src="../../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script>
    var tpl_file_name = '<?php echo TPL_FILE_NAME;?>';
    var uid='<?php echo $uid;?>';
    var langx='<?php echo $langx?>';
    var bank_name_list = $.parseJSON('<?php echo json_encode($bankList, JSON_UNESCAPED_UNICODE);?>');

    var html = '<div class="msg-div">' +
        '<div class="fm" style="color:#c52000;">为了您的银行帐号安全，我们不建议您经常更换！</div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;";>*</span>开户银行：</label>' +
        '<select id="chg_bank" style="width:210px" name="chg_bank"><span style="color:#c52000">*</span>' +
        '<option value="1" selected="selected" >***选择银行***</option>';

        for(var i=0;i<bank_name_list.length;i++){
            html += '<option value="'+bank_name_list[i]+'">'+bank_name_list[i]+'</option>';
        }
    html +=' </select></div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;";>*</span>银行账户：</label>' +
            '<input class="mn-ipt" type="text" id="chg_bank_account" name="chg_bank_account" style="width:210px">' +
        '</div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>银行地址：</label>' +
            '<input class="mn-ipt" type="text" id="chg_bank_address" name="chg_bank_address" style="width:210px">' +
        '</div>' ;

    // html +='<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>TRC20的提币地址：</label>' +
    //     '<input class="mn-ipt" type="text" id="chg_usdt_address" name="chg_usdt_address" style="width:210px" readonly>' +
    //     '<p class="red_color" style="margin-top: -15px;">如需修改提币地址，请联系客服</p>'+
    //     '</div>' ;

    html +='<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>提款密码：</label>' +
        '<input class="mn-ipt" type="password" id="chg_paypassword1" name="chg_paypassword1" minlength="6" maxlength="6" style="width:210px"></div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>确认密码：</label>' +
        '<input class="mn-ipt" type="password" id="chg_paypassword2" name="chg_paypassword2" minlength="6" maxlength="6" style="width:210px"></div>' +

        '</div>';

    var submit = function (v, h, f) {
        if (v == true) {
            var usdt_add = $("#chg_usdt_address").val(); // usdt地址

            if(!usdt_add){
                if (f.chg_bank ==1 || f.chg_bank == "") {
                    jBox.tip("请选择开户银行！", "error");
                    return false;
                }
                if (f.chg_bank_account == "") {
                    jBox.tip("请输入银行账号！", "error");
                    return false;
                }
                if (f.chg_bank_address == "") {
                    jBox.tip("请输入银行地址！", "error");
                    return false;
                }
            }

            if (f.chg_paypassword1 == '') {
                jBox.tip("请输入您的提款密码。", "error");
                return false;
            }
            if (f.chg_paypassword2 == '') {
                jBox.tip("请输入您的确认提款密码。", "error");
                return false;
            }
            if (f.chg_paypassword1 != f.chg_paypassword2) {
                jBox.tip("抱歉，您输入的提款密码不一致！", "error");
                return false;
            }
            var paypasswordreg = /^\d{6,6}$/;
            if (f.chg_paypassword1 && !paypasswordreg.test(f.chg_paypassword1)) {
                jBox.tip("抱歉，提款密码不符合规范，请输入6位数字！", "error");
                return false;
            }

            var dat = {};
            dat.uid = uid;
            dat.bank_name = $("#chg_bank").val(); // 银行名称
            dat.bank_address = $("#chg_bank_address").val(); // 银行地址
            dat.bank_account = $("#chg_bank_account").val(); // 银行账号
            dat.paypassword1 = $("#chg_paypassword1").val();
            dat.paypassword2 = $("#chg_paypassword2").val();
            //dat.usdt_address = usdt_add; // usdt 账号
            dat.action = 'add';
            $.ajax({
                type: 'POST',
                url: '/app/member/money/updatebank.php',
                data: dat,
                dataType: 'json',

                success: function (ret) {
                    if (ret.code ==1) { // 更换成功

                        jBox.tip("更换成功", 'success');

                    } else {
                        jBox.tip("更换失败", 'success');
                    }
                    location.href = 'withdrawal.php?uid='+uid+'&langx='+langx;

                },
                error: function (res) {
                    jBox.tip("数据更新失败，请稍后再试",'success');

                }
            });

        }else{
            if(document.referrer.indexOf('realname') != -1){
                history.go(-2); // 取消更换返回上一页
            }else{
                history.go(-1); // 取消更换返回上一页
            }
        }

    };

    jBox.confirm(html, "设置银行帐号", submit, {
        id: 'creditsChangeBank',
        width:370,
        showScrolling: false,
        buttons: {'提交设置': true, '取消设置': false}
    });
</script>
</BODY>
</HTML>
