<?php
session_start();
include_once('../../include/config.inc.php');

// 判断会员是否登录，否则跳转登出页面
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期，请您重新登录!');window.location.href='login.php';</script>";
    exit;
}

// 判断会员状态是否启用，否则退出
if ($_SESSION['Status'] != 0){
    echo "<script>alert('非常抱歉，您的账号已冻结或已停用，请您联系客服！')</script>";
    exit;
}

$redisObj = new Ciredis();
$sportCenterSet = $redisObj->getSimpleOne('sport_center_set');
$sportConfig = json_decode($sportCenterSet,true);

$gameUrl = $_SESSION['Agents'] == 'demoguest' ? $sportConfig['tryUrl'] : $sportConfig['apiUrl']; // 是否试玩账号
$aTemp = explode('://', $gameUrl);
$head = $aTemp[0];
$domain = $aTemp[1];
$sportCenterUrl = $head . '://' . HG_MOBILE_URL . '.' . $domain;
?>
<html class="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="style/iphone.css?v=<?php echo AUTOVER; ?>" >
    <style>
        iframe{height:100%;}
    </style>
</head>
<body>
<!--<iframe name="sport_cp_url" id="sport_cp_url"  noresize src="--><?php //echo $sportCenterUrl;?><!--" ></iframe>-->

<script type="text/javascript" src="../../js/zepto.min.js"></script>

<script type="text/javascript">
    $(function () {
        window.location.href = "<?php echo $sportCenterUrl;?>";
    })
</script>
</body>
</html>