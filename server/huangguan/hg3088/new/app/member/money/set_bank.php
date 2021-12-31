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
        .not-setbank{ line-height: 35px;float: left;margin-right: 5px;}
        div.jbox .jbox-close{ display: none !important;}
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

    var uid='<?php echo $uid;?>';
    var langx='<?php echo $langx?>';
    var username='<?php echo $_SESSION['UserName'];?>';

    var html = '<div class="msg-div">' +
        '<div class="fm" style="color:#c52000;">为了您的银行帐号安全，我们不建议您经常更换！</div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;";>*</span>开户银行：</label><select id="chg_bank" style="width:210px" name="chg_bank"><span style="color:#c52000">*</span>' +
        '<option value="1" selected="selected" >***选择银行***</option>' +
        '<option value="中国工商银行">中国工商银行</option>' +
        '<option value="中国建设银行">中国建设银行</option>' +
        '<option value="中国农业银行">中国农业银行</option>' +
        '<option value="中国银行">中国银行</option>' +
        '<option value="交通银行">交通银行</option>' +
        '<option value="招商银行">招商银行</option>' +
        '<option value="中国民生银行">中国民生银行</option>' +
        '<option value="邮政储蓄银行">邮政储蓄银行</option>' +
        '<option value="中信银行">中信银行</option>' +
        '<option value="光大银行">光大银行</option>' +
        '<option value="浦发银行">浦发银行</option>' +
        '<option value="兴业银行">兴业银行</option>' +
        '<option value="华夏银行">华夏银行</option>' +
        '<option value="广发银行">广发银行</option>' +
        '<option value="平安银行">平安银行</option>' +
        '<option value="上海银行">上海银行</option>' +
        '<option value="江苏银行">江苏银行</option>' +
        '<option value="安徽省农村信用社联合社">安徽省农村信用社联合社</option>' +
        '<option value="鞍山市商业银行">鞍山市商业银行</option>' +
        '<option value="包商银行股份有限公司">包商银行股份有限公司</option>' +
        '<option value="北京农村商业银行">北京农村商业银行</option>' +
        '<option value="北京顺义银座村镇银行">北京顺义银座村镇银行</option>' +
        '<option value="北京银行">北京银行</option>' +
        '<option value="渤海银行">渤海银行</option>' +
        '<option value="沧州银行">沧州银行</option>' +
        '<option value="长安银行">长安银行</option>' +
        '<option value="长沙银行">长沙银行</option>' +
        '<option value="常熟农村商业银行">常熟农村商业银行</option>' +
        '<option value="成都银行">成都银行</option>' +
        '<option value="承德银行">承德银行</option>' +
        '<option value="重庆农村商业银行">重庆农村商业银行</option>' +
        '<option value="重庆黔江银座村镇银行">重庆黔江银座村镇银行</option>' +
        '<option value="重庆银行股份有限公司">重庆银行股份有限公司</option>' +
        '<option value="重庆渝北银座村镇银行">重庆渝北银座村镇银行</option>' +
        '<option value="大连银行">大连银行</option>' +
        '<option value="德阳银行">德阳银行</option>' +
        '<option value="德州银行">德州银行</option>' +
        '<option value="东莞农村商业银行">东莞农村商业银行</option>' +
        '<option value="东莞银行">东莞银行</option>' +
        '<option value="东亚银行（中国）有限公司">东亚银行（中国）有限公司</option>' +
        '<option value="东营莱商村镇银行股份有限公司">东营莱商村镇银行股份有限公司</option>' +
        '<option value="东营银行">东营银行</option>' +
        '<option value="鄂尔多斯银行">鄂尔多斯银行</option>' +
        '<option value="福建海峡银行">福建海峡银行</option>' +
        '<option value="福建省农村信用社">福建省农村信用社</option>' +
        '<option value="阜新银行结算中心">阜新银行结算中心</option>' +
        '<option value="富滇银行">富滇银行</option>' +
        '<option value="赣州银行">赣州银行</option>' +
        '<option value="广东华兴银行">广东华兴银行</option>' +
        '<option value="广东南粤银行股份有限公司">广东南粤银行股份有限公司</option>' +
        '<option value="广东省农村信用社联合社">广东省农村信用社联合社</option>' +
        '<option value="广发银行股份有限公司">广发银行股份有限公司</option>' +
        '<option value="广西北部湾银行">广西北部湾银行</option>' +
        '<option value="广西农村信用社">广西农村信用社（合作银行）</option>' +
        '<option value="广州农村商业银行">广州农村商业银行</option>' +
        '<option value="广州银行">广州银行</option>' +
        '<option value="贵阳银行">贵阳银行</option>' +
        '<option value="桂林银行股份有限公司">桂林银行股份有限公司</option>' +
        '<option value="哈尔滨银行结算中心">哈尔滨银行结算中心</option>' +
        '<option value="海口联合农村商业银行">海口联合农村商业银行</option>' +
        '<option value="海南省农村信用社">海南省农村信用社</option>' +
        '<option value="邯郸市商业银行">邯郸市商业银行</option>' +
        '<option value="韩亚银行">韩亚银行</option>' +
        '<option value="汉口银行">汉口银行</option>' +
        '<option value="杭州银行">杭州银行</option>' +
        '<option value="河北银行股份有限公司">河北银行股份有限公司</option>' +
        '<option value="恒丰银行">恒丰银行</option>' +
        '<option value="衡水银行">衡水银行</option>' +
        '<option value="湖北农信">湖北农信</option>' +
        '<option value="湖北银行">湖北银行</option>' +
        '<option value="湖州银行">湖州银行</option>' +
        '<option value="葫芦岛银行">葫芦岛银行</option>' +
        '<option value="华夏银行">华夏银行</option>' +
        '<option value="黄河农村商业银行">黄河农村商业银行</option>' +
        '<option value="徽商银行">徽商银行</option>' +
        '<option value="交通银行">交通银行</option>' +
        '<option value="吉林农村信用社">吉林农村信用社</option>' +
        '<option value="吉林银行">吉林银行</option>' +
        '<option value="济宁银行">济宁银行</option>' +
        '<option value="嘉兴银行清算中心">嘉兴银行清算中心</option>' +
        '<option value="江苏长江商行">江苏长江商行</option>' +
        '<option value="江苏省农村信用社联合社">江苏省农村信用社联合社</option>' +
        '<option value="江苏银行股份有限公司">江苏银行股份有限公司</option>' +
        '<option value="江西赣州银座村镇银行">江西赣州银座村镇银行</option>' +
        '<option value="江阴农商银行">江阴农商银行</option>' +
        '<option value="锦州银行">锦州银行</option>' +
        '<option value="晋城银行">晋城银行</option>' +
        '<option value="晋商银行网上银行">晋商银行网上银行</option>' +
        '<option value="九江银行股份有限公司">九江银行股份有限公司</option>' +
        '<option value="昆仑银行">昆仑银行</option>' +
        '<option value="昆山农村商业银行">昆山农村商业银行</option>' +
        '<option value="莱商银行">莱商银行</option>' +
        '<option value="兰州银行股份有限公司">兰州银行股份有限公司</option>' +
        '<option value="廊坊银行">廊坊银行</option>' +
        '<option value="临商银行">临商银行</option>' +
        '<option value="柳州银行">柳州银行</option>' +
        '<option value="龙江银行">龙江银行</option>' +
        '<option value="洛阳银行">洛阳银行</option>' +
        '<option value="漯河市商业银行">漯河市商业银行</option>' +
        '<option value="绵阳市商业银行">绵阳市商业银行</option>' +
        '<option value="南昌银行">南昌银行</option>' +
        '<option value="南充市商业银行">南充市商业银行</option>' +
        '<option value="南京银行">南京银行</option>' +
        '<option value="内蒙古银行">内蒙古银行</option>' +
        '<option value="宁波通商银行股份有限公司">宁波通商银行股份有限公司</option>' +
        '<option value="宁波银行">宁波银行</option>' +
        '<option value="宁夏银行">宁夏银行</option>' +
        '<option value="攀枝花市商业银行">攀枝花市商业银行</option>' +
        '<option value="平安银行（原深圳发展银行）">平安银行（原深圳发展银行）</option>' +
        '<option value="平顶山银行">平顶山银行</option>' +
        '<option value="齐鲁银行">齐鲁银行</option>' +
        '<option value="齐商银行">齐商银行</option>' +
        '<option value="企业银行">企业银行</option>' +
        '<option value="青岛银行">青岛银行</option>' +
        '<option value="青海银行">青海银行</option>' +
        '<option value="泉州银行">泉州银行</option>' +
        '<option value="日照银行">日照银行</option>' +
        '<option value="山东省农联社">山东省农联社</option>' +
        '<option value="上海农商银行">上海农商银行</option>' +
        '<option value="上海浦东发展银行">上海浦东发展银行</option>' +
        '<option value="上海银行">上海银行</option>' +
        '<option value="上饶银行">上饶银行</option>' +
        '<option value="绍兴银行">绍兴银行</option>' +
        '<option value="深圳福田银座村镇银行">深圳福田银座村镇银行</option>' +
        '<option value="深圳农商行">深圳农商行</option>' +
        '<option value="深圳前海微众银行">深圳前海微众银行</option>' +
        '<option value="盛京银行">盛京银行</option>' +
        '<option value="顺德农村商业银行">顺德农村商业银行</option>' +
        '<option value="四川省联社">四川省联社</option>' +
        '<option value="苏州银行">苏州银行</option>' +
        '<option value="厦门国际银行">厦门国际银行</option>' +
        '<option value="厦门银行">厦门银行</option>' +
        '<option value="台州银行">台州银行</option>' +
        '<option value="太仓农商行">太仓农商行</option>' +
        '<option value="泰安市商业银行">泰安市商业银行</option>' +
        '<option value="天津滨海农村商业银行股份有限公司">天津滨海农村商业银行股份有限公司</option>' +
        '<option value="天津农商银行">天津农商银行</option>' +
        '<option value="天津银行">天津银行</option>' +
        '<option value="威海市商业银行">威海市商业银行</option>' +
        '<option value="潍坊银行">潍坊银行</option>' +
        '<option value="温州银行">温州银行</option>' +
        '<option value="乌鲁木齐市商业银行">乌鲁木齐市商业银行</option>' +
        '<option value="吴江农村商业银行">吴江农村商业银行</option>' +
        '<option value="武汉农村商业银行">武汉农村商业银行</option>' +
        '<option value="西安银行">西安银行</option>' +
        '<option value="新韩银行中国">新韩银行中国</option>' +
        '<option value="兴业银行">兴业银行</option>' +
        '<option value="邢台银行">邢台银行</option>' +
        '<option value="烟台银行">烟台银行</option>' +
        '<option value="鄞州银行">鄞州银行</option>' +
        '<option value="营口银行">营口银行</option>' +
        '<option value="友利银行">友利银行</option>' +
        '<option value="云南省农村信用社">云南省农村信用社</option>' +
        '<option value="枣庄银行">枣庄银行</option>' +
        '<option value="张家港农村商业银行">张家港农村商业银行</option>' +
        '<option value="张家口银行股份有限公司">张家口银行股份有限公司</option>' +
        '<option value="浙江稠州商业银行">浙江稠州商业银行</option>' +
        '<option value="浙江景宁银座村镇银行">浙江景宁银座村镇银行</option>' +
        '<option value="浙江民泰商业银行">浙江民泰商业银行</option>' +
        '<option value="浙江三门银座村镇银行">浙江三门银座村镇银行</option>' +
        '<option value="浙江省农村信用社">浙江省农村信用社</option>' +
        '<option value="浙江泰隆商业银行">浙江泰隆商业银行</option>' +
        '<option value="浙商银行">浙商银行</option>' +
        '<option value="郑州银行">郑州银行</option>' +
        '<option value="中原银行">中原银行</option>' +
        '<option value="珠海华润银行清算中心">珠海华润银行清算中心</option>' +
        '<option value="自贡市商业银行清算中心">自贡市商业银行清算中心</option>' +
        '<option value="贵州省农村信用社">贵州省农村信用社</option>' +
        ' </select></div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;";>*</span>银行账户：</label>' +
        '<input class="mn-ipt" type="text" id="chg_bank_account" name="chg_bank_account" style="width:210px"></div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>银行地址：</label>' +
        '<input class="mn-ipt" type="text" id="chg_bank_address" name="chg_bank_address" style="width:210px"></div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;";>*</span>提款密码：</label>' +
        '<input class="mn-ipt" type="password" id="chg_paypassword1" name="chg_paypassword1" minlength="6" maxlength="6" style="width:210px"></div>' +
        '<div class="fm"><label><span style="color:#c52000; vertical-align:middle;">*</span>确认密码：</label>' +
        '<input class="mn-ipt" type="password" id="chg_paypassword2" name="chg_paypassword2" minlength="6" maxlength="6" style="width:210px"></div>' +
        //        '<div class="fm">' +
        //        '<input class="mn-ipt" type="button" id="sumit" onclick="VerifyBank()" name="sumit" value="提交更换"/></div>' +
        //        '<div class="fm">' +
        //        '<input class="" type="button" id="chg_bank" name="chg_bank" value="取消更换"/></div>' +
        '</div>';

    var submit = function (v, h, f) {
        if (v == true) {
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
            // history.back(-1); // 取消更换返回上一页
            location.href = '../onlinepay/deposit_withdraw.php?uid='+uid+'&langx='+langx+'&username='+username;
        }

    };

    jBox.confirm(html, "设置银行帐号", submit, {
        id: 'creditsChangeBank',
        showScrolling: false,
        buttons: {'提交设置': true, '取消设置': false}
    });
</script>
</BODY>
</HTML>
