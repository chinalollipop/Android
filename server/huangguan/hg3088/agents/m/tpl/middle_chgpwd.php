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
    <link href="../css/main.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title> <?php echo COMPANY_NAME;?> </title>
    <style type="text/css">
        .centerContent{padding:0 2%}
        .boxMiddle div{height:3rem;margin-top:.5rem;border-bottom:1px solid #f1f1f1}
        .boxMiddle div span{display:inline-block;width:5rem;line-height:3rem;color:#000;font-size:1.1rem;text-align:right}
        .boxMiddle input{height:100%;line-height:3rem;width:80%;padding:0 5px;text-align:right}

    </style>
</head>
<body>
<div class="contentAll flex">
    <?php include 'middle_header.php'; ?>

    <div class="centerContent">
        <div class="boxMiddle">
            <input type="hidden" name="level" class="userLevel" value="D">
            <div class="flex">
                <span>旧密码</span>
                <input type="password" class="passwd_old" placeholder="请输入原始密码" minlength="6" maxlength="15"/>
            </div>
            <div class="flex">
                <span>新密码</span>
                <input type="password" class="passwd_new" placeholder="请输入新密码" minlength="6" maxlength="15"/>
            </div>
            <div class="flex">
                <span>确认密码</span>
                <input type="password" class="passwd_new_re" placeholder="请再次输入新密码" minlength="6" maxlength="15"/>
            </div>
        </div>

        <a href="javascript:;" class="submit_changePwd btn linear_1"> 确认 </a>
    </div>

    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    settingHeight('.contentAll');
    changePassword();
    // 修改密码
    function changePassword() {
        $('.submit_changePwd').off().on('click',function () {
            var url = '/api/changePwdApi.php';
            var reurl = '/m/tpl/middle_login.php';
            var passwd_old = $('.passwd_old').val();
            var pwd = $('.passwd_new').val();
            var REpasswd = $('.passwd_new_re').val();
            if(passwd_old==''){
                layer.msg('请输入原密码',{time:alertComTime,shade: [0.2, '#000']});
                return false;
            }
            if(pwd=='' || REpasswd==''){
                layer.msg('请输入新密码',{time:alertComTime,shade: [0.2, '#000']});
                return false;
            }
            if(pwd!=REpasswd){
                layer.msg('新密码与确认密码不一致',{time:alertComTime,shade: [0.2, '#000']});
                return false;
            }
            var dataParams = {
                passwd_old:passwd_old,
                passwd:pwd,
                REpasswd:REpasswd
            };
            $.ajax({
                type: 'POST',
                url:url,
                data:dataParams,
                dataType:'json',
                success:function(res){
                    if(res){
                        layer.msg(res.describe,{time:alertComTime,shade: [0.2, '#000']});
                        if(res.status == '200'){ // 更改成功
                            setTimeout(function () {
                                parent.loadPageBox.location.replace(reurl);
                            },alertComTime)
                        }
                    }

                },
                error:function(){
                    layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
                }
            });
        })

    }
</script>
</body>
</html>
