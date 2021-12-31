<?php
// 第三方银行存款
// 输入金额，跳转第三方或者添加记录

include_once('../../../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('请重新登录!');window.location.href='../login.php';</script>";
    exit;
}
$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$username = $_SESSION['UserName'];

$kscz_url = $_SESSION['kscz_url'] ; // 快速充值链接

$membermessage = getMemberMessage($username,'1'); // 存款短信



?>
<html class="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="../../../style/icalendar.css?v=<?php echo AUTOVER; ?>">

    <title class="web-title"></title>
<style type="text/css">

</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->
    <div class="kscz-content-center " style="margin: 3.25rem 0 .5rem; height: 100%;">
        <iframe id="kscz_url" name="kscz_url"  src="<?php echo $kscz_url?>" frameborder="NO" border="0" framespacing="0"  height="100%" width="100%"></iframe>

    </div>


</div>
<script type="text/javascript" src="../../../js/zepto.min.js"></script>
 <script type="text/javascript" src="../../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/layer/mobile/layer.js"></script>

<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    setLoginHeaderAction('快速充值','','',usermon,uid) ;

    var depositNum = '<?php echo $membermessage['mcou']?>' ; // 是否有会员信息
    var depositMsg = '<?php echo $membermessage['mem_message']?>' ; // 会员信息
    // 弹窗信息
    if(depositNum>0){ // 有弹窗短信
        alert(depositMsg);
        /*layer.open({
            content: depositMsg
            ,btn: '确定'
        });*/
    }

</script>

</body>
</html>