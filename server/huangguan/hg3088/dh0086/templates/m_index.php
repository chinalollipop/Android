<?php
require ("../include/config.inc.php");

?>
<style type="text/css">
    .box_small{position:fixed;z-index:999;bottom:0;left:0;width:267px;height:33px;background:url(images/sy_04.png);display:none}
    #box{width:267px;position:fixed;z-index:999;bottom:0;left:0;overflow:hidden}
    #closeButton{position:absolute;top:8px;right:10px;width:17px;height:17px;overflow:hidden;cursor:pointer;display:block;color:#fff}
    .sy_news{float:left;display:inline;width:267px;height:241px;background:url(images/sy_04.png);color:#fff}
    .sy_newss{float:left;display:inline;width:100%;height:40px}
    .sy_newsx{float:left;display:inline;width:240px;height:186px;margin:6px 13px;overflow:hidden}
    .sy_newsx p{float:left;display:inline;width:100%;font-size:12px;color:#fff;line-height:16px;text-indent:24px}
    .sy_newsx span{float:left;display:inline;width:230px;font-size:12px;color:#fcff00;line-height:16px;margin:3px 13px 0 23px}
    i{font-style: normal;}
</style>

<!-- banner -开始 -->

<div class="banner">
   <!-- <div class="inner psr">
        <div class="dengbox" style="height:280px;right:3%">
            <div class="denginx">
                <div class="engtl">会员登入 LOGIN</div>
                <div class="denginbg">
                    <form method="post" name="LoginForm1" action="" target="_blank" id="LoginForm1" >
                    	<input type=HIDDEN name="demoplay" id="demoplay1" value="">
                        <input type="hidden" name="Website" value=" ">
                        <input type="hidden" name="uid" value="">
                        <input type="hidden" name="langx" value="zh-cn">
                        <input type="hidden" name="mac" value="">
                        <input type="hidden" name="ver" value="">
                        <input type="hidden" name="JE" value="1">
                        <ul class="denginul">
                            <li>
                                <input name="username" id="ausername1" type="text" maxlength="15" placeholder="登录名" tabindex="1" class="denginpt sinpt dicon1" oldval="登录名">
                            </li>
                            <li>
                                <input name="password"  id="apassword1" type="password" placeholder="****" maxlength="15" tabindex="2" class="denginpt sinpt dicon2" typeval="password" oldval="****">
                            </li>

                        </ul>
                        <div class="cl"></div>
                    </form>
                </div>
                <div class="cl h11"></div>
                <a id="btnLogin" onclick="do_login('sec')" tabindex="4" class="dbtn1 fl"><img src="images/dbtn1.png"></a>
                <a href="javascript:;" class="to_memberreg dbtn1 fr"><img src="images/dbtn2.png" alt=""></a>
                <div class="cl"></div>
				<div class="engtl"><a onclick="addTryPlay1()" style="display:block;font-size:18px;line-height:38px;text-align:center;height:38px;width:243px;background: #d4a41d;border-radius:4px;margin-top:6px;">试玩参观</a></div>
                <div class="cl"></div>
                <div class="wangji">
                    <a href="javascript:;" class="set_forget_url">忘记密码？</a>
                </div>
                <div class="cl"></div>
            </div>
        </div>

        <div class="newbox" style="top:300px;left:732px;">
            <div class="newin">
                <div class="newtl"><img src="images/newtl.png" alt=""></div>
                <div class="cl h12"></div>
                <div class="luntpbx">
                    <marquee style="cursor: pointer;height: 65px;color:#fff" class="user_msgnews" onmouseover="this.stop();" onmouseout="this.start();" direction="up" scrollamount="3" scrolldelay="140" align="left">
                        世界杯与您狂欢，开户即送77元彩金，向在线客服申请领取，祝您游戏愉快！
                    </marquee>

                </div>
            </div>
        </div>
    </div>-->
    <ul class="tutu">
        <li class="cur" style="display: block;"><a >
                <img src="images/loading-2.gif" ></a>
        </li>
       <!-- <li ><a ><img src="images/banner/2.jpg?v=2" alt=""></a></li>
        <li ><a ><img src="images/banner/3.jpg?v=2" alt=""></a></li>
        <li ><a ><img src="images/banner/4.jpg?v=2" alt=""></a></li>
        <li ><a ><img src="images/banner/5.jpg?v=2" alt=""></a></li>
        <li ><a ><img src="images/banner/6.jpg?v=2" alt=""></a></li>-->
    </ul>
    <div class="yuandian">

    </div>
</div>

<!-- banner -结束 -->

<!-- 滚动图 -开始 -->
<div class="psr">
    <div class="banyuan">

    </div>
</div>
<div class="content">
    <div class="cont1">
        <div class="inner">
            <div class="dextum fl">
                <div class=""><img src="images/tl.png?v=1" alt=""></div>

                <div class="drntul">
                    <ul>
                        <li><a href="javascript:;" class="to_lotterys"><img src="images/dm1.png" alt=""></a></li>
                        <li><a href="javascript:;" class="to_chess"><img src="images/index_qp.png" alt=""></a></li>

                    </ul>
                </div>
            </div>

            <div class="dextum fl">
                <div class=""><img src="images/t2.png?v=1" alt=""></div>

                <div class="drntul">
                    <ul>
                        <li><a href="javascript:;" class="to_games"><img src="images/dm3.png" alt=""></a></li>
                        <li><a href="javascript:;" class="to_games"><img src="images/dm4.png" alt=""></a></li>

                    </ul>
                </div>
            </div>
            <div class="cl"></div>
        </div>
    </div>

    <div class="cont2">
        <div class="inner">
            <div class="mtuul">
                <ul>
                    <li><a href="javascript:;" class="to_lives"><img src="images/mtu1.png" alt=""></a></li>
                    <li><a href="javascript:;" class="to_sports"><img src="images/mtu2.png" alt=""></a></li>

                </ul>
            </div>
            <div class="cl"></div>
        </div>
    </div>
</div>
<!-- 滚动图 -结束 -->


<!-- 左浮动-最新消息-开始 -->
<div class="box_small"></div>
<div id="box">
    <span id="closeButton"><img src="images/sy_05.png?v=1"></span>
    <div class="bd" id="bottomPart">
        <div class="sy_news">
            <div class="sy_newss"></div>
            <div class="sy_newsx "> <!-- user_msgnews -->
                <p style="text-indent:0;">
                    尊敬的会员您好：<br>
                    我司易记域名<a class="url_info yj_backup_web_url" target="_blank"> </a>！<br>
                    官方导航：<a class="gf_new_web_url url_info" target="_blank"> </a><br>
                    如有疑问请咨询24小时在线客服 <br>
                    <a class="url_info">客服热线：<i class="ess_service_phone"> </i></a> <br>
                    如被跳转至其他网站，请第一时间联系微信客服公众号【<a class="url_info"><i class="wechat_service_number"> </i></a>】，谢谢!
                </p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    /* 右侧-二维码-结束 */
    // 悬浮窗口
    $(function () {
        $("#closeButton").click(function() {
            $("#closeButton").css("width", '0');
            $("#box").css('height', '0');
            $(".box_small").show();
        })

        $(".box_small").hover(function() {
            $("#closeButton").css("width", '17px');
            $("#box").css('height', '241px');
        });
        /*  悬浮窗口结束 */

        /* 获取轮播 */
        function indexBannerAction() {
            var $swiperClass = $('.tutu');
            $.ajax({
                url:'/indexBannerApi.php',
                type:'POST',
                dataType:'json',
                data: {action:'ad'} ,
                success:function(res){
                    var str = '';
                    if(res.status==200){ // 成功
                        for(var i=0;i<res.data.length;i++){
                           str +=' <li class="'+(i==0?'cur':'')+'" ><a ><img src="'+res.data[i].img_path+'" alt=""></a></li>';
                        }
                        $swiperClass.html(str);
                        huxiFn('.banner');
                    }


                },
                error:function () {

                }
            });
        }

        /* 轮播*/
        function huxiFn(id){
            var iNowxx = 0;
            var timexx = null;
            var outTime=800;
            var inTime = 600;
            var stopTime = 3000;
            var yuandian = $(id).find(".yuandian")
            var tutu = $(id).find('ul.tutu')
            var len2 = tutu.children('li').length;
            for(var i=0; i<len2;i++){
                yuandian.append('<span></span>')
            }
            yuandian.children().eq(0).addClass('cur')
            var rightbtn = function(){
                if(iNowxx < len2 -1){
                    tutu.children('li').eq(iNowxx).stop().fadeOut(outTime);
                    iNowxx++;
                    yuandian.children().eq(iNowxx).addClass('cur').siblings().removeClass('cur')
                    tutu.children('li').eq(iNowxx).stop().fadeIn(inTime);
                }else{
                    tutu.children('li').eq(iNowxx).stop().fadeOut(outTime);
                    iNowxx= 0;
                    tutu.children('li').eq(iNowxx).stop().fadeIn(inTime);
                    yuandian.children().eq(iNowxx).addClass('cur').siblings().removeClass('cur')
                }
            }
            var leftbtn = function(){
                if(iNowxx > 0){
                    tutu.children('li').eq(iNowxx).stop().fadeOut(outTime);
                    iNowxx--;
                    yuandian.children().eq(iNowxx).addClass('cur').siblings().removeClass('cur')
                    tutu.children('li').eq(iNowxx).stop().fadeIn(inTime);
                }else{
                    tutu.children('li').eq(iNowxx).stop().fadeOut(outTime);
                    iNowxx= len2 -1;
                    tutu.children('li').eq(iNowxx).stop().fadeIn(inTime);
                    yuandian.children().eq(iNowxx).addClass('cur').siblings().removeClass('cur')
                }
            }
            timexx = window.setInterval(function(){
                rightbtn();
            },stopTime)
            $(id).hover(function(){
                window.clearInterval(timexx);
            },function(){
                timexx = window.setInterval(function(){
                    rightbtn();
                },stopTime)
            })
            yuandian.children().click(function(){
                tutu.children('li').eq(iNowxx).fadeOut(outTime);
                iNowxx = $(this).index()
                yuandian.children().eq(iNowxx).addClass('cur').siblings().removeClass('cur')
                tutu.children('li').eq(iNowxx).fadeIn(inTime);
            })
        }

        indexBannerAction();

    }) ;

    urlSetAction(HTTPS_HEAD,FETCH_NUM);
    setUserMsg(usermessage,'nottip') ;

    function addTryPlay1(){
		document.getElementById("demoplay1").value="Yes";
		document.getElementById("ausername1").value="demoguest";
		document.getElementById("apassword1").value="qwertyu";
		document.getElementById("LoginForm1").submit();
	}


</script>