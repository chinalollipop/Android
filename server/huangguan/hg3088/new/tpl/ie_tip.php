<?php
include "../app/member/include/config.inc.php";
include "../app/member/include/address.mem.php";

$oldLogin=HTTPS_HEAD.'://'.getMainHost(); // 旧版地址


?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width">
    <title> </title>
    <link type="text/css" rel="stylesheet" href="../style/member/common.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../style/warn_web.css?v=<?php echo AUTOVER; ?>">

</head>
<body style="background-color: transparent" >
<DIV id=bsDIV class=browserDIV>
    <DIV class=browserG>
        <H1>您的浏览器版本太旧。</H1>
        <UL>
            <LI>您必须升级您的浏览器才可以浏览此网站。</LI>
            <LI>一旦浏览器不在被支援，<B>保护您的网络安全程式</B>将不是最新的，这也会增加您被骇和资料被盗取的可能性。</LI>
            <LI>我们强烈建议您下载以下最新的浏览器以便有较好的体验和保持目前最高的网络安全级别。不然，您可以继续使用我们的旧网站登录。<A
                        class=browser_clickBTN href="<?php echo $oldLogin?>" target="_top">立即点击旧站</A></LI></UL>
        <DIV class="browserBTNG noFloat">
            <DIV class=browser_ChromeBTN><SPAN
                        class=browser_recommend></SPAN><TT>谷歌浏览器</TT>
                <A class="download_a" href="https://www.google.cn/chrome/" target=_blank>
                    <SPAN class=browser_downloadBTN>下载</SPAN>
                </A></DIV>
            <DIV class=browser_FirefoxBTN><TT>火狐浏览器</TT>
                <A class="download_a" href="https://www.mozilla.org/zh-CN/firefox/new" target=_blank>
                    <SPAN class=browser_downloadBTN>下载</SPAN>
                </A>
            </DIV>
            <DIV class=browser_Safari><TT>苹果浏览器</TT>
                <A class="download_a" href="https://support.apple.com/zh-cn/HT204416" target=_blank>
                    <SPAN class=browser_downloadBTN>下载</SPAN>
                </A></DIV></DIV>
        <H2>为什么有升级浏览器的必要？</H2>
        <DIV class="browserWordG noFloat"><SPAN class=browserWord_txt01>
<H3>安全性</H3>
<H4>新版浏览器可以更好的保护您免受诈骗，病毒，木马，网络钓鱼和其他等的威胁。而且也修正旧浏览器的安全漏洞。</H4></SPAN><SPAN
                    class=browserWord_txt02>
<H3>兼容性</H3>
<H4>有使用新技术的网站能优化画面的显示和其性能。</H4></SPAN><SPAN class=browserWord_txt03>
<H3>速度</H3>
<H4>每新一代的浏览器速度会提升。</H4></SPAN><SPAN class=browserWord_txt04>
<H3>更好的体验</H3>
<H4>随着新功能的增加和现有功能的延伸，您将有一个更舒适的网络体验。</H4></SPAN>
</DIV>
        <div style="text-align: center">
            <a class=browser_nextBTN href="<?php echo $oldLogin?>" target="_top">继续</a>
        </div>
    </DIV>
</DIV>
<div id=showURL></div>

</body>
</html>
