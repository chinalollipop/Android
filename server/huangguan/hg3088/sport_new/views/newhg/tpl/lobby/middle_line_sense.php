<?php
session_start();

include "../../../../app/member/include/config.inc.php";

$uid = $_SESSION['Oid']; // 判断是否已登录

?>
<style>
    img{display: inline-block;}
    .sense_main{background:url(<?php echo TPL_NAME;?>images/linesense/xl_bg.jpg?v=1) no-repeat;min-height:600px;padding:10px 0 50px}
    .xl_top{margin:50px 0 10px}
    .xl_top p,.xl_bottom p{color:#626262;}
    .ts{margin: 5px 0 25px;}
    .xz_ac{overflow:hidden;margin:30px 0}
    .xz_ac a{float:left;width:170px}
    .xz_ac a span{display:inline-block}
    .xz_ac .xz_ac_p{font-size:16px}
    .xz_ac .tip{font-size:12px}
    .xl_top img{float:left}
    .xl_top .title{width:280px;height:53px;background:url(<?php echo TPL_NAME;?>images/linesense/zx_title.png?v=1) no-repeat}
    .xl_bottom .title{width:346px;height:53px;background:url(<?php echo TPL_NAME;?>images/linesense/xljc_title.png?v=1) no-repeat}
    .right_tip{color:#878b8d;margin:50px 0 0}
    .right_tip p{line-height:26px;margin-bottom:20px}
    .wz_ul li{margin-bottom:20px}
    .wz_ul span{display:inline-block;height:30px;line-height:30px;color:#878b8d;text-align:center;margin-right:10px}
    .wz_ul .line_icon{width:21px;height:10px;background:url(<?php echo TPL_NAME;?>images/linesense/jiantou.png?v=1) no-repeat}
    .wz_ul span.line_time,.wz_ul span.line_url{border:1px solid #4d4d4c}
    .wz_ul span.line_time{width:148px}
    .wz_ul span.line_btn{width:124px;height:30px;background:linear-gradient(to right,#ff9e03,#db8b18);transition:.3s}
    .wz_ul span.line_btn:hover{opacity: .8;}
    .wz_ul span.line_btn a{display:inline-block;width:100%;height:100%}
    .wz_ul span.line_url{padding:0 15px;min-width:288px}
    .xl_bottom {margin-top: 40px;}
</style>

<div class="sense_main">
    <div class="w_1200" style="width: 1080px;">
        <div class="left">
            <div class="xl_top">
                <div class="title"> </div>
                <p class="ts"> 温馨提示：反应时间越小，网站打开速度越快，找到最小ms数值的网址并打开链接</p>
                <div class="xz_ac">
                    <a href="javascript:;" class="to_memberreg">
                        <img src="<?php echo TPL_NAME;?>images/linesense/xl_reg.png?v=1">
                        <span>
                            <p class="xz_ac_p"> 立即开户</p>
                            <p class="tip"> OPEN AN ACCOUNT</p>
                        </span>
                    </a>
                    <a href="javascript:;" class="to_livechat">
                        <img src="<?php echo TPL_NAME;?>images/linesense/xl_kf.png?v=1">
                        <span>
                            <p class="xz_ac_p"> 在线客服</p>
                            <p class="tip"> ONLINE SERVE</p>
                        </span>
                    </a>
                    <a href="javascript:;" class="to_index ">
                        <img src="<?php echo TPL_NAME;?>images/linesense/xl_home.png?v=1">
                        <span>
                            <p class="xz_ac_p"> 官方首页 </p>
                            <p class="tip"> HOME PAGE</p>
                        </span>
                    </a>
                    <a href="javascript:;" class="to_promos">
                        <img src="<?php echo TPL_NAME;?>images/linesense/xl_pro.png?v=1">
                        <span>
                            <p class="xz_ac_p"> 优惠大厅</p>
                            <p class="tip"> PROMORS </p>
                        </span>
                    </a>
                </div>
                <!-- 网址列表 -->
                <ul class="wz_ul wz_ul_top">
                    <li>       <span class="line_time"> 测速中... </span>       <span class="line_icon"></span>       <span class="line_url"> http://hg81.com </span>       <span class="line_btn"><a href="http://hg81.com" target="_blank"> 进入网站</a></span> </li>
                    <li>       <span class="line_time"> 测速中... </span>       <span class="line_icon"></span>       <span class="line_url"> http://hg81a.com </span>       <span class="line_btn"><a href="http://hg81a.com" target="_blank"> 进入网站</a></span> </li>

                </ul>

            </div>

            <div class="xl_bottom">
                <div class="title"> </div>
                <p class="ts"> 温馨提示：反应时间越小，网站打开速度越快，找到最小ms数值的网址并打开链接</p>
                <!-- 网址列表 -->
                <ul class="wz_ul wz_ul_bottom">
                    <li>       <span class="line_time"> 测速中... </span>       <span class="line_icon"></span>       <span class="line_url"> http://hg81b.com </span>       <span class="line_btn"><a href="http://hg81a.com" target="_blank"> 进入网站</a></span> </li>
                    <li>       <span class="line_time"> 测速中... </span>       <span class="line_icon"></span>       <span class="line_url"> http://hg81c.com </span>       <span class="line_btn"><a href="http://hg81c.com" target="_blank"> 进入网站</a></span> </li>
                    <li>       <span class="line_time"> 测速中... </span>       <span class="line_icon"></span>       <span class="line_url"> http://hg81d.com </span>       <span class="line_btn"><a href="http://hg81d.com" target="_blank"> 进入网站</a></span> </li>
                    <li>       <span class="line_time"> 测速中... </span>       <span class="line_icon"></span>       <span class="line_url"> http://hg81e.com </span>       <span class="line_btn"><a href="http://hg81e.com" target="_blank"> 进入网站</a></span> </li>
                    <li>       <span class="line_time"> 测速中... </span>       <span class="line_icon"></span>       <span class="line_url"> http://hg81f.com </span>       <span class="line_btn"><a href="http://hg81f.com" target="_blank"> 进入网站</a></span> </li>
                    <li>       <span class="line_time"> 测速中... </span>       <span class="line_icon"></span>       <span class="line_url"> http://hg81g.com </span>       <span class="line_btn"><a href="http://hg81g.com" target="_blank"> 进入网站</a></span> </li>

                </ul>

            </div>
            <!-- 测速 -->
            <div id="site_peed"> </div>
        </div>

        <div class="right">
            <div class="rw">
                <img src="<?php echo TPL_NAME;?>images/linesense/img_nme.png?v=1">
            </div>
            <div class="right_tip">
                <p>
                    如果我们的检测中心对您有帮助，<br>
                    请按 Ctrl+D收藏
                </p>
                <p>
                    如果检测后还不能登录请按以下操作方式:<br>
                    操作步骤：打开IE浏览器：<br>
                    选择：工具-》Internet选项-》<br>
                    在选择 (删除历史浏览记录)-》删除-》重启IE
                </p>
                <div class="xl_img">
                    <img src="<?php echo TPL_NAME;?>images/linesense/img03.png?v=1">
                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    clearInterval(autourlInterval);
    var autourlInterval ='';
    var autourl = [
        'http://hg81.com',
        'http://hg81a.com',
        'http://hg81b.com',
        'http://hg81c.com',
        'http://hg81d.com',
        'http://hg81e.com',
        'http://hg81f.com',
        'http://hg81g.com'
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
    runUrl();

</script>