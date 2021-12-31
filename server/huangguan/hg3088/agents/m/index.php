<?php
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require ("../app/agents/include/config.inc.php");

$langx = "zh-cn";
$level = "D";
$actiontype = isset($_REQUEST['actionType'])?$_REQUEST['actionType']:''; // 手机代理注册成功后跳转
$username = isset($_REQUEST['UserName'])?$_REQUEST['UserName']:''; // 手机代理注册成功后跳转
$password = isset($_REQUEST['PassWord'])?$_REQUEST['PassWord']:''; // 手机代理注册成功后跳转


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
    <link href="css/main.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title> <?php echo COMPANY_NAME;?> </title>
    <style type="text/css">


    </style>
</head>
<body>

<!-- 内容区域 -->
<iframe class="loadPageBox" name="loadPageBox" frameborder="no"> </iframe> <!-- scrolling="no"  -->

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    var tipMobile = '<?php echo $actiontype;?>';
    var username = '<?php echo $username;?>';
    var password = '<?php echo $password;?>';
    loadPageAction('login');

    // 加载登录页
    function loadPageAction(type) {
        var url = '';
        switch (type){
            case 'login':
                url = '/m/tpl/middle_login.php?actionType='+tipMobile+'&UserName='+username+'&PassWord='+password;
                break;
            // case 'home':
            //     url = '/m/tpl/middle_index.php?type='+type;
            //     break;
            // case 'user':
            //     url = '/m/tpl/middle_member.php?type='+type;
            //     break;

        }
        settingHeight('.loadPageBox');
        loadPageBox.location.replace(url);
    }

</script>
</body>
</html>
