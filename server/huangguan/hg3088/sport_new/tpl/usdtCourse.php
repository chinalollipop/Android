<?php
session_start();
include "../app/member/include/config.inc.php";

header ("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

$companyName = COMPANY_NAME;

?>

<html>
<head>
    <title> USDT充值教程 </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="/<?php echo TPL_NAME;?>images/favicon.ico" type="image/x-icon">
    <meta name="keywords" content="<?php echo $companyName;?>">
    <meta name="description" content="<?php echo $companyName;?>">
    <link rel="stylesheet" type="text/css" href="/<?php echo TPL_NAME;?>style/common.css?v=<?php echo AUTOVER; ?>" >
    <style>
        body{padding: 20px 10px;}
        .all p{line-height: 24px;font-size: 16px;}
        .img {margin: 10px 0;}
        .img img{display: inline-block;}
    </style>
</head>

<body >

<div class="all">
    <!--<p>推荐您使用Okex /火币/币安 交易所USDT进行交易</p>-->
    <p>推荐您使用 <?php echo getSysConfig('usdt_jiaoyisuo') ? getSysConfig('usdt_jiaoyisuo') : 'Okex/火币/币安';?> 交易所USDT进行交易</p>
    <!--<p>Okex官网：<a href="https://www.okex.com/cn" target="_blank"> https://www.okex.com/cn </a></p>-->
    <p>火币官网：<a href="https://www.huobi.fm/zh-cn" target="_blank">https://www.huobi.fm/zh-cn</a></p>
    <p>币安官网：<a href="https://www.binancezh.com/cn" target="_blank">https://www.binancezh.com/cn</a></p>
    <p>【*币安充值货币需要24小时到账】</p>
    <p>请您打开进行注册与登陆，也可以联系在线客服咨询快捷下载二维码，</p>
    <p>充值流程：</p>
    <div class="img">
        <img src="/images/usdtjc/set_1.png">
        <img src="/images/usdtjc/set_2.png">
        <img src="/images/usdtjc/set_3.png">
    </div>
    <p>充值成功后，1至5分钟内会自动到账。</p>
    <p>这是查询账户可用额度</p>
    <img src="/images/usdtjc/set_4.png">
    <p>转账我司充值流程如下：</p>
    <div class="img">
        <img src="/images/usdtjc/set_5.png">
        <img src="/images/usdtjc/set_6.png">
        <img src="/images/usdtjc/set_7.png">
    </div>
    <p>转账成功后，系统会在1--3分钟内自动到账，如超过5分钟未到账，请您联系在线客服咨询，谢谢！</p>


</div>


</body>
<script type="text/javascript" src="/js/jquery.js"></script>

</html>
