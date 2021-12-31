<?php
/**
 * 设置账户个人信息-真实姓名
 * Date: 2018/10/9
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
include "../include/config.inc.php";
include "../include/define_function_list.inc.php";

$uid = $_REQUEST["uid"];
$langx = $_REQUEST["langx"];
include "../include/traditional.$langx.inc.php";

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

$alias_allows_duplicate = getSysConfig('alias_allows_duplicate'); // 验证会员昵称是否重复

// ajax设置真实姓名等相关信息
if ($_REQUEST['action'] && $_REQUEST['action'] == 'reset'){
    $realname = $_REQUEST['realname'];
//    $phone = $_REQUEST['phone'];
    $wechat = $_REQUEST['wechat'];
    $birthday = $_REQUEST['birthday'];
    // 必填校验
    if(!$realname)
        exit(json_encode(['code' => -1, 'msg' => '真实姓名不能为空!']));
//    if(!$phone)
//        exit(json_encode(['code' => -1, 'msg' => '手机号码不能为空!']));
    if(!$wechat)
        exit(json_encode(['code' => -1, 'msg' => '微信号码不能为空!']));
    // 参数格式校验
    if($realname && !isTrueName($realname))
        exit(json_encode(['code' => -1, 'msg' => '真实姓名不符合规范!']));

    if($alias_allows_duplicate && $realname) {
        $msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where Alias='$realname'";
        $mresult = mysqli_query($dbLink,$msql);
        $mcou = mysqli_num_rows($mresult);
        if ($mcou>0){
            exit(json_encode(['code' => -1, 'msg' => '真实姓名已存在!']));
        }
    }
//    if($phone && !isPhone($phone))
//        exit(json_encode(['code' => -1, 'msg' => '手机号码不符合规范!']));
    if($wechat && !isWechat($wechat))
        exit(json_encode(['code' => -1, 'msg' => '微信号码不符合规范!']));

    $stmtMember = $dbMasterLink->prepare("UPDATE ".DBPREFIX.MEMBERTABLE." SET `Alias` = ?, `E_Mail` = ?, `birthday` = ?, `EditDate` = now(), Online=1 , OnlineTime = now() WHERE Oid = '$uid' AND Status < 2");
    $stmtMember->bind_param("sss", $realname, $wechat, $birthday);
    $stmtMember->execute();
    $updateMember = $stmtMember->affected_rows;
    if($updateMember){
        exit(json_encode(['code' => 200, 'msg' => '真实姓名设置成功！']));
    }else{
        exit(json_encode(['code' => -1, 'msg' => '抱歉，真实姓名设置失败！']));
    }
}

// 判断是否已设置真实姓名，若设置直接跳转提款页面
$sql = "SELECT `Alias` FROM ".DBPREFIX.MEMBERTABLE." WHERE Oid = '$uid' AND Status = 0";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
if($row['Alias'])
    echo "<script language=javascript>location.href = '../onlinepay/deposit_withdraw.php?uid=".$uid."&langx=".$langx."';</script>";
//    echo "<script language=javascript>location.href = 'withdrawal.php?uid=".$uid."&langx=".$langx."';</script>";
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
        div.jbox .jbox-close{ display: none !important;}
        .note{top:10px;margin-bottom: 10px;display: inline-block;}
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
<script type="text/javascript" src="../../../js/register/laydate.min.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="../../../js/jbox/jquery.jBox-zh-CN.js"></script>
<script type="text/javascript" src="../../../js/register/validate.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var uid ='<?php echo $uid;?>';
    var langx ='<?php echo $langx?>';
    var html = '<div class="msg-div">' +
        '<div class="fm" style="color:#c52000;">绑定真实姓名可提高帐号的安全性！</div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;";>*</span>真实姓名：</label>' +
        '<input class="mn-ipt" type="text" id="chg_realname" name="chg_realname" style="width:210px">' +
        '<span class="note">姓名必须与你用于提款的银行户口名字一致，否则无法提款！</span></div>' +
//        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;";>*</span>手机号码：</label>' +
//        '<input class="mn-ipt" type="text" id="chg_phone" name="chg_phone" style="width:210px"></div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>微&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;信：</label>' +
        '<input class="mn-ipt" type="text" id="chg_wechat" name="chg_wechat" style="width:210px"></div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>生&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日：</label>' +
        '<input class="mn-ipt" type="text" id="chg_birthday" name="chg_birthday" style="width:210px" onclick="laydate({istime: false, format: \'YYYY-MM-DD\'})" readonly>' +
        '<span class="note">用于取回密码的答案，需谨记！</span></div>' +
        '</div>';

    var submit = function (v, h, f) {
        if (v == true) {
            if (f.chg_realname == '') {
                jBox.tip("请输入您的真实姓名。", "error");
                return false;
            }
//            if (f.chg_phone == '') {
//                jBox.tip("请输入您的电话号码。", "error");
//                return false;
//            }
            if (f.chg_wechat == '') {
                jBox.tip("请输入您的微信号码。", "error");
                return false;
            }
            if (f.chg_birthday == '') {
                jBox.tip("请输入您的生日。", "error");
                return false;
            }
            if(f.chg_realname && !isChinese(f.chg_realname)){
                jBox.tip("真实姓名不符合规范！", "error");
                return false;
            }

            if(f.chg_wechat && !isWechat(f.chg_wechat)){
                jBox.tip("微信号码不符合规范！", "error");
                return false;
            }
            var data = {};
            data.uid = uid;
            data.langx = langx;
            data.realname = $("#chg_realname").val();
//            data.phone = $("#chg_phone").val();
            data.wechat = $("#chg_wechat").val();
            data.birthday = $("#chg_birthday").val();
            $.ajax({
                type: 'POST',
                url: '/app/member/money/set_realname.php?action=reset',
                data: data,
                dataType: 'json',
                success: function (response) {
                    if(response.code == 200){
                        // location.href = 'set_bank.php?uid='+uid+'&langx='+langx;
                        location.href = '../onlinepay/deposit_withdraw.php?uid='+uid+'&langx='+langx;
                    }else{
                        alert(response.msg);
                        location.href = 'set_realname.php?uid='+uid+'&langx='+langx;
                    }
                },
                error: function (response) {
                    jBox.tip("数据更新失败，请您稍后再试！", 'error');
                }
            });
        }else{
            history.go(-1);
        }
    };
    jBox.confirm(html, "设置个人资料", submit, {
        id: 'changeUserInfo',
        showScrolling: false,
        buttons: {'提交设置': true, '取消设置': false}
    });
</script>
</BODY>
</HTML>