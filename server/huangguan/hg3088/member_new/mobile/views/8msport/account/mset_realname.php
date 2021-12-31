<?php
/**
 * 提款时-设置真实姓名
 * Date: 2018/10/18
 */
session_start();
include_once('../../../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('请重新登录!');window.location.href='../login.php';</script>";
    exit;
}

$uid = $_SESSION['Oid'];
$langx = $_SESSION['Language'];
$username = $_SESSION['UserName'];

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
    <style type="text/css"></style>
</head>
<body class="bg_f9f9f9">
<div id="container" >
    <!-- 头部 -->
    <div class="header "></div>
    <!-- 中间内容 -->
    <div class="content-center deposit-two">
        <div class="" data-area="bank_pay">
            <form method="post" name="setinfo" id="setinfo" action="">
            <div class="form-item form-select">
                    <span class="label">
                        <span class="text">真实姓名:</span>
                        <span class="line"></span>
                    </span>
                <span class="textbox">
                    <input type="text" name="realname" id="realname" class="bank-account" placeholder="提款行卡的姓名，用于提款" />
                </span>
            </div>
            <!--<div class="form-item form-select">
                <span class="label">
                    <span class="text">手机号码:</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input type="text" name="phone" id="phone" class="bank-account" placeholder="请输入11位手机号码" />
                </span>
            </div>-->
            <!--<div class="form-item form-select">
                <span class="label">
                    <span class="text">微信</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input type="text" name="wechat" id="wechat" class="bank-address" placeholder="微信号码" />
                 </span>
            </div>
            <div class="form-item form-select">
                <span class="label">
                    <span class="text">生日</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input id="birthday" maxlength="12"  type="text" name="birthday" placeholder="请填写出生年月日" readonly />
                 </span>
            </div>-->
            <div class="btn-wrap">
                <a href="javascript:;" class="zx_submit" onclick="doAction()">提交设置</a>
                <a href="/<?php echo TPL_NAME;?>account.php" class="zx_submit btn-reg">取消设置</a>
            </div>
             </form>
        </div>
    </div>
    <!-- 底部 -->
    <div id="footer">
    </div>
</div>
<script type="text/javascript" src="../../../js/zepto.min.js"></script>
 <script type="text/javascript" src="../../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/usercenter.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/validate.js?v=<?php echo AUTOVER; ?>"></script>
<!--<script type="text/javascript" src="../../../js/mobiscroll.js?v=--><?php //echo AUTOVER; ?><!--"></script>-->
<script type="text/javascript" src="../../../js/icalendar.min.js"></script>
<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie

    setLoginHeaderAction('设置个人信息','','',usermon, uid);
    setFooterAction(uid);
</script>
</body>
</html>