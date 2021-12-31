<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("../app/member/include/config.inc.php");


?>

<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>手机客户端下载</title>

    <style>
        body,html{overflow-y:auto;height: 100%;}
        html { margin:0px;padding:0px;background:#fff; }
        body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,select,textarea,blockquote,th,td,p { margin:0px;padding:0px;font-size:12px;color:#767676;font-family:"Microsoft Yahei",sans-serif,Arial,Verdana; }
        fieldset,img { border:0px; }
        a{ text-decoration:none;}
        .download-bg { width:100%;min-height:500px;background:#272727 url(../images/down-bg.jpg) no-repeat center top;background-size:auto 100%;overflow:hidden; }
        .download { margin:35px auto 0 auto;padding:35px 0 55px 0;width:1000px;height:490px;background:url(../images/connet_<?php echo TPL_FILE_NAME;?>.png) no-repeat;overflow:hidden; }
        .download dl { float:right;margin:70px 0px 0px 30px;border:0px solid #12b7f5;width:265px;overflow:hidden; }
        .download dl dt { padding-top:8px;padding-right:15px;text-align:center; }
        .download dl dt img { width:135px;border:1px solid #656565;border-radius:5px;box-shadow:0px 0px 8px rgba(0,0,0,0.5); }
        .download dl dt p { line-height:24px;font-size:12px;text-shadow:0px 0px 5px rgba(0,0,0,0.3);color:#fff; }
        .download dl dd { padding:20px 0px;font-size:16px;line-height:2em;color:#fff;text-shadow:0px 0px 5px rgba(0,0,0,0.3); }
        .download dl dd a { cursor:default;display:inline-block;margin-top:16px;width:250px;height:59px;line-height:59px;border-top:1px solid #258101;background:#49c719;text-align:center;color:#fff;font-size:20px;border-radius:4px;box-shadow:2px 2px 5px rgba(0,0,0,0.2);overflow:hidden; }
        .download dl dd a span { float:left;display:inline-block;width:59px;height:59px; }
        .download dl dd .android span { background:#42b316 url(../images/android.png) no-repeat center center; }
        .download dl dd .ios span { background:#42b316 url(../images/apple.png) no-repeat center center; }

        .download2 dl { float:right;margin:70px 0px 0px 30px;border:0px solid #12b7f5;width:265px;overflow:hidden; }
        .download2 dl dt { padding-top:8px;padding-right:15px;text-align:center; }
        .download2 dl dt img { border:1px solid #656565;border-radius:5px;box-shadow:0px 0px 8px rgba(0,0,0,0.5); }
        .download2 dl dt p { line-height:24px;font-size:12px;text-shadow:0px 0px 5px rgba(0,0,0,0.3);color:#fff; }
        .download2 dl dd { padding:20px 0px;font-size:16px;line-height:2em;color:#fff;text-shadow:0px 0px 5px rgba(0,0,0,0.3); }
        .download2 dl dd a { cursor:default;display:inline-block;margin-top:16px;width:250px;height:59px;line-height:59px;border-top:1px solid #258101;background:#49c719;text-align:center;color:#fff;font-size:20px;border-radius:4px;box-shadow:2px 2px 5px rgba(0,0,0,0.2);overflow:hidden; }
        .download2 dl dd a span { float:left;display:inline-block;width:59px;height:59px; }
        .download2 dl dd .android span { background:#42b316 url(../images/android.png) no-repeat center center; }
        .download dl dd .ios span { background:#42b316 url(../images/apple.png) no-repeat center center; }
        .download dl dd a.app {cursor:pointer;background: linear-gradient(#ffba00,#fb5214);background: -webkit-linear-gradient(#ffba00,#fb5214);background: -ms-linear-gradient(#ffba00,#fb5214);border-top: 1px solid #c16514;}
        .download dl dd .app span {background: url(../images/appxz.png) no-repeat center center;}
    </style>

</head>
<body>
<div class="all_content">


<div class="download-bg" id="download" style="height: 807px;">
    <div class="download">
        <dl>
            <dt><img src="<?php echo getPicConfig('download_android_url');?>"><p>扫描二维码下载安卓版</p></dt>
            <dd>
                文件大小: 5355.52KB
                <br>发布日期: 2018-10-14
                <a class="android" href="javascript:"><span></span>Android版</a>
                <a class="app" href="/tpl/promos.php" target="body">
                    <span> </span>
                    领取APP免费彩金
                </a>
            </dd>
        </dl>
        <dl>
            <dt><img src="<?php echo getPicConfig('download_ios_url');?>"><p>扫描二维码下载苹果版</p></dt>
            <dd>
                文件大小: 5355.52KB
                <br>发布日期: 2018-10-14
                <br><a class="ios" href="javascript:"><span></span>iPhone版</a>
                <a class="ios" href="allpictem.php?to=ios" style="cursor: pointer"><span></span>IOS信任教程</a>
            </dd>
        </dl>
    </div>
</div>
</div>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
    var download=$("#download");
    download.css({height:$(window).height()});
    window.onresize=init;
    function init(){
        download.css({height:$(window).height()});
    }
</script>


</body></html>