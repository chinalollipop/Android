<?php
session_start();

include "../../../../app/member/include/config.inc.php";

$uid = $_SESSION['Oid']; // 判断是否已登录

?>
<style>
    .bzinput { width:220px;}
    .bzinput1 { width:180px;display: inline-block;}
    .bzshua { margin:0; padding:0; height:30px; padding-top:30px; font-weight:bold; font-size:14px; padding-left:300px;}
    .cnyouhui #centerwraphead { display:none;}
    .mainlines { width:880px;}
    .mainlines #middle { width:740px; margin-top:10px;}
    #centerwraphead{  height:30px; width:880px; margin:0 auto;}
    #centerwraphead span { font-size:14px; font-weight:bold; height:30px; line-height:30px; font-family:Arial, Helvetica, sans-serif; padding-left:10px; color:#FFFFFF;}
    .linesul { padding-top:10px;}
    #lineswwbox { margin:0; height:auto; background:url(<?php echo TPL_NAME;?>images/linesense/bj2.png) no-repeat center top; padding-top:150px;}
    #linesulbox { width:538px; margin:0 auto; height:auto;}
    #linesulbox input { border:0; background:none; color:#FFFFFF; float:left; height:30px; line-height:30px;}
    #middle #linesulbox ul { margin:0; border-bottom:1px solid #999; margin-top:10px; height:30px; list-style-type:none;}
    #middle #linesulbox ul li { float:left; height:30px; line-height:30px; margin-top:0px; clear:none; color:#FFFFFF;}
    #publiclinesshua { margin:20px auto 10px auto; width:236px; height:32px; line-height:32px; color:#000000; background:url(<?php echo TPL_NAME;?>images/linesense/buttonbg.jpg) no-repeat; text-align:center; font-size:16px;}
    #middle #publiclinesshua a { display:block; width:236px; height:32px; color:#000000; text-decoration:none;}
    #linesulbox ul li a.linesboxa { display:block;  width:64px; height:26px; line-height:26px; text-align:center; background:url(<?php echo TPL_NAME;?>images/linesense/buttonbg.jpg) no-repeat; color:#000000;}

</style>

<div class="sense_main">
    <div id="new-banner">
        <div id="new-banner-box">
            <div id="banner"><img src="<?php echo TPL_NAME;?>images/live/6.jpg"></div>
            <div class="msg-connet">

                <div class="left" style="margin-lefT:8px;">
                    <div><a href="javascript:;" class="to_lives ylc_top"></a></div>
                    <div> <a href="javascript:;" class="to_lives ylc_left"></a>
                        <a href="javascript:;" class="to_lives ylc_right"></a> </div>
                </div>

            </div>
        </div>
    </div>
    <div id="sidebarwrap">
        <div id="sidebarbox">
            <div id="leftsidebar">
                <ul>
                    <li class="bbin"><a href="javascript:;" class="to_lives cur">BBIN娱乐</a></li>
                    <li class="mg"><a href="javascript:;" class="to_lives">AG娱乐</a></li>
                    <li class="sports"><a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">体育投注</a></li>
                    <li class="lot"><a href="javascript:;" class="to_lotterys">彩票游戏</a></li>
                    <li class="ele"><a href="javascript:;" class="to_games">电子游艺</a></li>
                </ul>
                <div id="ads1"><a href="javascript:;" class="to_promos"></a></div>
                <div id="ads2"><a href="javascript:;" class="to_promos"></a></div>
            </div>
            <div id="rightsidebar">
                <div id="middle">
                    <div id="lineswwbox">
                        <div id="linesulbox">
                            <!--<ul>
                                <li><input type="text" class="bzinput" value="http://www.sss365.tt" readonly=""></li>
                                <li>&nbsp;&nbsp;-&gt;&nbsp;&nbsp;</li>
                                <li><span class="line_time bzinput1">测速中...</span></li>
                                <li>&nbsp;&nbsp;-&gt;&nbsp;&nbsp;</li><li><a class="linesboxa" href="http://www.sss365.tt" target="_blank">打开链接</a></li>
                            </ul>-->

                        </div>
                        <div id="publiclinesshua"><a href="javascript:;" onclick="indexCommonObj.loadLineSense()"><span>点击这里重新检测刷新访问速度</span></a></div>
                        <!--<div class="bzshua"><font style=" font-size:12px">(数字值越小速度越快)</font>&nbsp;&nbsp;&nbsp;<a href="/cn/lines">刷新</a></div>-->
                        <!-- 测速 -->
                        <div id="site_peed"> </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    clearInterval(autourlInterval);
    var autourlInterval ='';
    var autourl = [
        'http://www.sss365.tt',
        'http://www.dkk365.com',
        'http://www.fkk365.com',
        'http://www.ubb365.com',
        'http://www.81bet365.com',
        'http://www.82bet365.com',
        'http://www.8800bet365.com',
        'http://www.8811bet365.com',
        'http://www.9999bet365.com'
    ];

    urltime = 1;
    autourlInterval = setInterval("urltime++", 100)

    function autoUrl(url, b) {
        $(".surl").eq(b).text(url);
        if (urltime > 200) {
            $(".line_time").eq(b).text("超时");
        } else {
            $(".line_time").eq(b).text(urltime*10 + "ms");
        }
    }
    function runUrl() {
        var htmlstr = '';
        var $site_peed = document.getElementById("site_peed");
        for ( var i = 0; i < autourl.length; i++) {
            htmlstr += "<img style='display:none' src=" + autourl[i]
                + "?i=" + Math.random()
                + " width=1 height=0 onerror=autoUrl('" + autourl[i]
                + "'," + i + ")>";
        }
        $site_peed.innerHTML = htmlstr;
    }

    /* 遍历域名 */
    function getLineSenseUrl() {
        var $linesulbox = $('#linesulbox');
        var str ='';
        for(var i=0;i<autourl.length;i++){
            str +='<ul>'+
                    '<li><input type="text" class="bzinput" value="'+autourl[i]+'" readonly=""></li>'+
                    '<li>&nbsp;&nbsp;-&gt;&nbsp;&nbsp;</li>'+
                    '<li><span class="line_time bzinput1">测速中...</span></li>'+
                    '<li>&nbsp;&nbsp;-&gt;&nbsp;&nbsp;</li>' +
                    '<li><a class="linesboxa" href="'+autourl[i]+'" target="_blank">打开链接</a></li>'+
                 '</ul>';
        }

        $linesulbox.html(str);

        runUrl();
    }

    getLineSenseUrl();


</script>