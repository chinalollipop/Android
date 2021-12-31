<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Content-type: text/html; charset=utf-8");

require ("../../app/agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$navtype = isset($_REQUEST['type'])?$_REQUEST['type']:'';
$navtitle = isset($_REQUEST['navtitle'])?$_REQUEST['navtitle']:'';

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <META name="keywords" content="<?php echo COMPANY_NAME;?>,<?php echo COMPANY_NAME;?>登入,<?php echo COMPANY_NAME;?>平台">
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="/images/favicon_<?php echo TPL_FILE_NAME;?>.ico" type="image/x-icon"/>
    <link href="/style/icalendar.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="../css/main.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title> <?php echo COMPANY_NAME;?> </title>
    <style type="text/css">
        .contentAll .centerContent{padding-top:0;}
        .centerContent .nav{line-height:3rem;padding:0 2%;background:#f1f6fb;border-bottom:1px solid #f1f1f1}
        .centerContent .nav a{position:relative;font-size: 1.1rem;color:#4e525e;}
        .centerContent .nav a:after{position: absolute;content: '';width: 4rem;height: 2px;background: #5da2ea;bottom: 0;left: 20%;}
        .boxMiddle div{height:3rem;margin-top:.5rem;border-bottom:1px solid #f1f1f1}
        .boxMiddle div span{display:inline-block;width:5.5rem;line-height:3rem;color:#000;font-size:1.1rem;text-align:right}
        .boxMiddle input{height:100%;line-height:3rem;width:80%;padding:0 5px;}
        .boxMiddle select{width:76%;margin-left: 5px;border: 0;}
        .btn_bottom {width: 80%;margin: 0 auto;}
        .btn_bottom .btn{width: 47%;margin: 2rem 0;}
        .btn_bottom .btn:first-child {margin-right: 4%;}
    </style>
</head>
<body>
<div class="contentAll flex">
    <?php include 'middle_header.php'; ?>

    <div class="contentCenterAll centerContent">

        <div class="boxMiddle">
            <div class="nav flex">
                <a > 基本资料设定 </a>
            </div>
            <input type="hidden" name="kyes" class="kyes" value="add">
            <div class="flex">
                <span>账号:</span>
                <input type="text" class="userName" placeholder="须为5~15位英文字母或数字组合" minlength="5" maxlength="15"/>
            </div>
            <div class="flex">
                <span>密码:</span>
                <input type="password" class="passwd" placeholder="须为6~15位英文字母或数字组合" minlength="6" maxlength="15"/>
            </div>
            <div class="flex">
                <span>确认密码:</span>
                <input type="password" class="passwd_re" placeholder="须为6~15位英文字母或数字组合" minlength="6" maxlength="15"/>
            </div>
            <div class="flex">
                <span>真实姓名:</span>
                <input type="text" class="userAlias" placeholder="须与用于提款的银行户口名字一致" />
            </div>

            <div class="nav flex">
                <a > 下注资料设定 </a>
            </div>
            <div class="flex">
                <span>开放盘口:</span>
                <select class="select_kfpk">
                    <option value="C" selected> C 盘</option>
                </select>
            </div>
            <div class="flex">
                <span>投注方式:</span>
                <select class="select_tzfs">
                    <option value="1" selected> 现金额度 </option>
                </select>
            </div>
            <div class="flex">
                <span>手机号码:</span>
                <input type="text" class="userphone" placeholder="手机号码" minlength="11" maxlength="11"/>
            </div>
            <div class="flex">
                <span>微信号码:</span>
                <input type="text" class="userWechat" placeholder="微信号码" minlength="5" />
            </div>
            <div class="flex">
                <span>取款密码:</span>
                <input type="password" class="payPwd" placeholder="6位纯数字取款密码" minlength="6" maxlength="6"  />
            </div>
            <div class="flex">
                <span>生日:</span>
                <input type="text" class="birthDay" placeholder="选择出生日期" readonly/>
            </div>
            <div class="flex">
                <span>现金额度:</span>
                <input type="text" class="xjed" value="0" readonly />
            </div>
        </div>
        <div class="btn_bottom">
            <a href="javascript:;" class="submit_btn_action btn linear_1"> 确认 </a>
            <a href="javascript:;" class="btn linear_1" onclick="history.go(-1)"> 取消 </a>
        </div>

    </div>

    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/icalendar.min.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    settingHeight('.contentAll');
    addMemberAction();

    var calendar = new lCalendar();   // 时间插件初始化
    calendar.init({
        'trigger': '.birthDay',
        'type': 'date',
        //defaultValue:setAmerTime('.birthDay'),
    });

    // 修改密码
    function addMemberAction() {
        var subFlage = false;
        $('.submit_btn_action').off().on('click',function () {
            var url = '/api/AddMemberApi.php';
            var reurl = '/m/tpl/middle_memberDetail.php?type=user&navtitle=会员信息';
            var username = $('.userName').val();
            var pwd = $('.passwd').val();
            var REpasswd = $('.passwd_re').val();
            var alias = $('.userAlias').val();
            var type = $('.select_kfpk').val();
            var pay_type = $('.select_tzfs').val();
            var phone = $('.userphone').val();
            var wechat = $('.userWechat').val();
            var pay_password = $('.payPwd').val();
            var birthday = $('.birthDay').val();

            if(username=='' || username.length<5 || username.length>15){
                layer.msg('请输入账号',{time:alertComTime,shade: [0.2, '#000']});
                return false;
            }

            if(pwd=='' || REpasswd=='' || pwd.length<6 || pwd.length>15){
                layer.msg('请输入密码',{time:alertComTime,shade: [0.2, '#000']});
                return false;
            }
            if(pwd!=REpasswd){
                layer.msg('密码与确认密码不一致',{time:alertComTime,shade: [0.2, '#000']});
                return false;
            }
            if(alias==''){
                layer.msg('请输入真实姓名',{time:alertComTime,shade: [0.2, '#000']});
                return false;
            }
            if(phone=='' || phone.length !=11){
                layer.msg('请输入手机号码',{time:alertComTime,shade: [0.2, '#000']});
                return false;
            }
            if(wechat==''){
                layer.msg('请输入微信号码',{time:alertComTime,shade: [0.2, '#000']});
                return false;
            }
            if(pay_password=='' || pay_password.length !=6){
                layer.msg('请输入6位纯数字支付密码',{time:alertComTime,shade: [0.2, '#000']});
                return false;
            }

            var dataParams = {
                keys:'add',
                user_count:username,
                password:pwd,
                alias:alias,
                type:type,
                pay_type:pay_type,
                currency:'RMB',
                phone:phone,
                wechat:wechat,
                pay_password:pay_password,
                birthday:birthday
            };
            if(subFlage){
                return false;
            }
            subFlage = true ;
            $.ajax({
                type: 'POST',
                url:url,
                data:dataParams,
                dataType:'json',
                success:function(res){
                    if(res){
                        subFlage = false ;
                        layer.msg(res.describe,{time:alertComTime,shade: [0.2, '#000']});
                        if(res.status == '200'){ // 新增会员成功
                            setTimeout(function () {
                                parent.loadPageBox.location.replace(reurl);
                            },alertComTime)
                        }
                    }

                },
                error:function(){
                    subFlage = false ;
                    layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
                }
            });

        })

    }
</script>
</body>
</html>
