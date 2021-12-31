<?php
session_start();
include "../../../../app/member/include/config.inc.php";
require ("../../../../app/member/include/address.mem.php");
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid']; // 判断是否已登录
$host = getMainHost();
$m_url = HG_MOBILE_URL.".".$host; // 手机域名
?>
<style>
    #rightsidebar { width:100%;}
    #sidebarbox{ width:1060px;}

    #mwebbox{width:100%;background:#232323;height:auto;overflow:hidden;min-width:1000px;color:#FFF}
    #mwebboxmid{width:100%;padding:30px 0;height:auto;margin:0 auto}
    .setcmid{width:100%;height:auto;overflow:hidden;margin:0 auto;border-bottom:1px solid #4d4d4d}
    .sect0{width:615px;height:400px;background:url(<?php echo TPL_NAME;?>images/app/sect0.png) no-repeat left top;margin:0 auto;margin-top:30px;padding-left:435px}
    .sect0top{height:38px;line-height:38px;font-size:28px}
    .sect0top span{display:block;float:left;height:38px;line-height:38px;font-size:36px;margin-right:15px;color:#FFF}
    .sect0top span.appico{width:90px;background:url(<?php echo TPL_NAME;?>images/app/appico.png) no-repeat left center}
    .sect0info{line-height:22px;padding-top:10px;padding-right:10px;font-size:13px;color:#ccc;line-height:34px}
    .sect0info a{color:#FFF}
    .sect1{width:1000px;height:413px;background:url(<?php echo TPL_NAME;?>images/app/mobile_web_img1_2.png) no-repeat right top;margin:0 auto;margin-top:30px}
    .sect1title{height:34px;line-height:34px;font-size:36px;padding-left:65px}
    .fr,.right{float:right;display:inline}
    .fl,.left{float:left;display:inline}
    .mwebleft{float:left;width:520px;margin-left:65px}
    #mwebboxmid h3{font-size:18px;color:#fff;padding-top:25px;font-weight:normal;line-height:32px}
    #mwebboxmid .inner{padding-top:20px;height:auto;overflow:hidden}
    .mqr-code{float:left;width:126px;height:auto;overflow:hidden}
    .mqr-code-box{width:118px;height:118px;padding:3px;border:1px solid #575757;background:#FFF;overflow:hidden;position:relative}
    .mqr-code-box  img{position:absolute;top:3px;left:3px}
    .mqr-code p{height:25px;line-height:25px;text-align:center;font-size:13px;color:#FF0}
    .mwinfo{float:left;width:365px;line-height:22px;height:auto;overflow:hidden;padding-left:20px}
    .mwinfo span.ft1{font-size:16px;line-height:30px}
    .mwtxt{margin:0;height:45px;line-height:45px;margin-top:10px}
    .mwtxt span.wleft{float:left;display:block;width:28px;height:45px;background:url(<?php echo TPL_NAME;?>images/app/wleft.png) no-repeat}
    .mwtxt span.wright{float:left;display:block;width:48px;height:45px;background:url(<?php echo TPL_NAME;?>images/app/wright.png) no-repeat}
    .mwtxt a{display:block;float:left;color:#FFFFFF;font-weight:bold;padding:0 10px 0 0px;height:45px;line-height:45px;background:url(<?php echo TPL_NAME;?>images/app/wmid.png) repeat-x;text-align:center}
    .sect2{width:1000px;height:635px;background:url(<?php echo TPL_NAME;?>images/app/sect2.png) no-repeat center bottom;margin:0 auto;overflow:hidden}
    .sect2top{margin-top:60px;height:180px;padding-left:248px}
    .sect2left{float:left;width:244px}
    .s2qr{float:left;border:1px solid #575757;padding:6px;width:235px;height:110px}
    .s2qr span{display: inline-block;width: 110px;height: 100%;background-size: cover !important;}
    .s2qr span:last-child{margin-left: 11px;}
    .s2qrtxt{line-height:22px;text-align:center;color:#f5fe02;padding-top:8px;font-size:12px}
    .sect2right{float:left;padding-left:20px;width:350px;height:auto;overflow:hidden}
    .sect2right font.agid{background:#F00;color:#FFF;line-height:24px;padding:3px}
    .se2rt{font-size:36px;color:#FFF;height:50px;line-height:50px}
    .sect2right p{line-height:35px;font-size:16px;color:#ccc;margin:0px}
    .sect2right p a{color:#ebfa02}
</style>

<div class="app_download">
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
            <div id="rightsidebar">
                <div id="mwebbox">
                    <div id="mwebboxmid">

                        <div class="clear"></div>
                        <div class="setcmid">
                            <div class="sect1">
                                <div class="sect1title">手机Web版</div>
                                <div class="mwebleft">
                                    <h3>多元化移动娱乐平台、精彩随时随地、一切尽在掌中！美女荷官相伴，刺激不打烊</h3>
                                    <div class="inner">
                                        <div class="mqr-code fl">
                                            <div class="mqr-code-box" id="code-box" > </div>
                                            <img id="qr-img" width="118" height="118" style="border: solid 1px #989393; background: #fff; padding: 2px;" >
                                            <div class="clear"></div>
                                            <div class="s2qrtxt" style="padding-top:1px; ">uc浏览器扫一扫开玩</div>
                                        </div>
                                        <div class="mwinfo">
                      <span class="ft1"><font color="#c6d20a">一机在手</font>，娱乐无穷。视讯/电子/彩票/体育，即时下注，让您<font color="#c6d20a">走到哪玩到哪</font>！<br>手机浏览器输入
                      <div class="mwtxt">
                          <span class="wleft"></span>
                          <a id="mobileurl" target="_blank"><?php echo $m_url;?></a>
                          <span class="wright"></span>
                      </div>
                  </span></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <a id="1"></a>
                        <div class="setcmid">
                            <div class="sect0">
                                <div class="sect0top">
                                    <span>APP客户端</span>
                                    <span class="appico"></span>
                                </div>
                                <div class="sect2top" style="padding-left:0px; margin-top: 40px; ">
                                    <div class="sect2left">
                                        <div class="s2qr">
                                            <span class="download_android_app"></span>
                                            <span class="download_ios_app"></span>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="s2qrtxt"><span>Andriod</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>浏览器扫码下载</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>Ios</span></div>
                                    </div>
                                    <div class="sect2right">
                                        <!--<p>①&nbsp;&nbsp;手机扫描二维码，进入APP下载页面，点击下载</p>-->
                                        <p>①&nbsp;&nbsp;手机浏览器扫描二维码，进入下载页面，点击下载</p>
                                        <p>②&nbsp;&nbsp;APP安装和授权 <a href="<?php echo $tplNmaeSession;?>tpl/lobby/middle_appTrust.php" target="_blank">点此查看IOS版教程</a></p>
                                        <p>③&nbsp;&nbsp;注册会员和登录操作教程，<a href="<?php echo $tplNmaeSession;?>tpl/lobby/middle_appTrust.php?type=reg" target="_blank">点击查看</a></p>
                                        <p>④&nbsp;&nbsp;&nbsp;<a class="download_android_exe">点此下载客户端</a></p>
                                    </div>
                                </div>
                                <div class="sect0info">
                                    <font color="#f00">推荐使用谷歌，QQ，和手机自带浏览器下载</font><br>
                                    <font color="#f5fe02">注意：</font>第一步使用QQ扫描下载的用户，请在扫描后，点击“确认”即可下载，或者使用浏览器扫描下载。
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>

                    </div>
                </div>
                <div style="display:none;" id="testbox">
                </div>


            </div>
        </div>
    </div>

</div>
<script type="text/javascript" src="/js/jquery.qrcode.min.js"></script>
<script type="text/javascript">
    $(function () {

        $('.download_android_exe').attr({'href':web_config.download_android_exe,'target':'_blank'}); // app 客户端

        var phttp;
        var purl = window.location.href;
        var pof =purl.indexOf('https://');
        if(pof<0){
            phttp='http';
        }else{
            var httpcode=0;
            if(httpcode==200){
                phttp='https';
            }else{
                phttp='http';
            }
        }

        var str = '<?php echo HTTPS_HEAD."://".$m_url;?>';
        var qrcode = $("#code-box").qrcode({
            width: 118,
            height:118,
            text: str
        }).hide();
        var canvas=qrcode.find('canvas').get(0);
        $('#qr-img').attr('src',canvas.toDataURL('image/jpg'))

        
    })
</script>