
<style type="text/css">
    .sy_m{ background:url(./images/bg.jpg) no-repeat center  40px;}
    .url_info {color: #ffff00;}
    .yzm_img {position: absolute;right: 0;top:3px;}
    i {font-style: normal;}
</style>

<!-- banner -开始 -->
<div class="sy_ban">
    <div class="sy_bann">
        <div class="sy_bannz">
            <div id="show" class="d1">
                <div class="loading"><img src="images/loading-2.gif" style="position: absolute;top: 50%;left: 50%;"/></div>
                <ul>
                   <!-- <li><a href="#"><img src="images/banner1.jpg?v=1" width="711" height="425"/></a></li>
                    <li><a href="#"><img src="images/banner2.jpg?v=1" width="711" height="425"/></a></li>
                    <li><a href="#"><img src="images/banner3.jpg?v=1" width="711" height="425"/></a></li>
                    <li><a href="#"><img src="images/banner4.jpg?v=1" width="711" height="425"/></a></li>
                    <li><a href="#"><img src="images/banner5.jpg?v=1" width="711" height="425"/></a></li>-->

                </ul>
            </div>
        </div>


        <form method="post" name="LoginForm" action="" target="_blank" id="LoginForm" onsubmit="return do_login();">
        	<input type=HIDDEN name="demoplay" id="demoplay" value="">
            <input type="hidden" name="Website" value="">
            <input type="hidden" name="uid" value="">
            <input type="hidden" name="langx" value="zh-cn">
            <input type="hidden" name="mac" value="">
            <input type="hidden" name="ver" value="">
            <input type="hidden" name="radio" value="web_new">
            <input type="hidden" name="JE" value="1">
            <div class="sy_banny">
                <span class="sy_bannys">皇冠会员登陆</span>
                <div class="sy_bannyc" style="height:115px">
                    <div class="sy_bannyc1">
                        <img class="sy_bannyc11" src="images/sy_52.png" />
                        <input type="text" name="username" class="sy_bannyc12" id="ausername" placeholder="帐号" autocomplete="off"/>
                    </div>
                    <div class="sy_bannyc1">
                        <img class="sy_bannyc11" src="images/sy_60.png" />
                        <input type="password" name="password" class="sy_bannyc13" id="apassword" placeholder="密码" autocomplete="off" />
                        <a href="javascript:;"  class="sy_bannyc14 to_service">忘记密码</a> <!--set_forget_url-->
                    </div>
                    <div class="sy_bannyc1" style="position: relative;">
                        <img class="sy_bannyc11" src="images/sy_60.png" />
                        <input type="text" name="yzm_input" class="sy_bannyc13" id="yzm_input" placeholder="验证码" autocomplete="off" minlength="4" maxlength="6"/>
                        <img class="yzm_img" alt="验证码"/>
                    </div>
                </div>
                <div class="sy_bannyx">
                    <a href="javascript:;" class="sy_bannyx1" onclick="do_login()">用户登入</a>
                    <a href="javascript:;" class="to_memberreg sy_bannyx2">免费注册</a>
                </div>
                <div class="sy_bannyx">
                    <a href="javascript:;" onclick="addTryPlay()" class="sy_bannys" style="background: #d4a41d;border-radius: 4px;margin: 0px 8px 5px 2px;" onclick="do_login()">试玩参观</a>
                </div>
                <div class="sy_bannyb"><img src="images/sy_63.png" />公告</div>
                <div id="demo" style="height: 100px;">
                    <div id="demo1">
                        <p class="sy_bannybx">
                        <marquee style="cursor: pointer" id="msgNews" class="user_msgnews" onmouseover="this.stop();" onmouseout="this.start();" direction="up" height="120" width="230" scrollamount="3" scrolldelay="140" align="left">

                        </marquee>
                        </p>
                    </div>
                    <div id="demo2"></div>
                </div>
            </div>
        </form>

    </div>
</div>
<!-- banner -结束 -->

<!-- 滚动图 -开始 -->
<div class="sy_gdt">
    <div class="sy_gdtn">
        <div class="sy_gdtc">
            <div class="cont" id="ISL_Cont_1">
                <ul class="box" id="boxid">
                    <li><a href="javascript:;" class="to_sports"><img src="images/sy1.png" /></a></li>
                    <li><a href="javascript:;" class="to_sports"><img src="images/sy2.png" /></a></li>
                    <li><a href="javascript:;" class="to_lives"><img src="images/sy3.png" /></a></li>
                    <li><a href="javascript:;" class="to_lotterys"><img src="images/sy4.png" /></a></li>
                    <li><a href="javascript:;" class="to_games"><img src="images/sy5.png" /></a></li>
                    <li><a href="javascript:;" class="to_sports"><img src="images/sy6.png" /></a></li>
                    <li><a href="javascript:;" class="to_sports"><img src="images/sy7.png" /></a></li>
                    <li><a href="javascript:;" class="to_sports"><img src="images/sy8.png" /></a></li>
                    <li><a href="javascript:;" class="to_sports"><img src="images/sy9.png" /></a></li>
                    <li><a href="javascript:;" class="to_sports"><img src="images/sy10.png" /></a></li>
                    <li><a href="javascript:;" class="to_sports"><img src="images/sy11.png" /></a></li>
                </ul>
            </div>
            <div>
                <div><a href="javascript:;" id='LeftArr' class="sy_gdtz"><img src="images/sy_55.png" /></a></div>
                <div><a href="javascript:;" id='RightArr' class="sy_gdty"><img src="images/sy_49.png" /></a></div>
            </div>
        </div>
    </div>
</div>
<!-- 滚动图 -结束 -->


<!-- 左浮动-最新消息-开始 -->
<div class="box_small"></div>
<div id="box">
    <span id="closeButton"><img src="images/sy_05.png" /></span>
    <div class="bd" id="bottomPart">
        <div class="sy_news">
            <div class="sy_newss"></div>
            <div class="sy_newsx "> <!-- user_msgnews-->
                <p style="text-indent:0;">
                    本公司官方网被大陆多个不法之徒冒用，敬请客户留意，谨防上当。<br>
                    请认准正网品牌：<i class="app_download_page"> </i><br>
                    皇冠易记网址：<i class="yj_backup_web_url"> </i><br>
                    菲律宾专线：<i class="phl_service_phone"> </i><br>
                    如无菲律宾电话者，均为大陆私庄，信誉资金不受保障，若被骗者，一律与本公司无关！谢谢！<br>
                    <a class="url_info"> 客服热线：<i class="ess_service_phone"> </i> </a><br>
                    <a class="url_info"> 投诉电话：<i class="phl_service_phone"> </i> </a><br>
                    <a class="url_info"> 皇冠菲律宾总代理：www.hgw6668.com </a>

                </p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
   $(function () {

       urlSetAction(HTTPS_HEAD,FETCH_NUM);
       setUserMsg(usermessage,'nottip') ;
       indexBannerAction();
       setFirstAction();
       /* 右侧-二维码-结束 */
       // 悬浮窗口
       $("#closeButton").click(function() {
           $("#closeButton").css("width", '0');
           $("#box").css('height', '0');
           $(".box_small").show();
       })

       $(".box_small").hover(function() {
           $("#closeButton").css("width", '17px');
           $("#box").css('height', '241px');
       });

       /* 获取轮播 */
       function indexBannerAction() {
           var $swiperClass = $('#show ul');
           $.ajax({
               url:'/indexBannerApi.php',
               type:'POST',
               dataType:'json',
               data: {action:'ad'} ,
               success:function(res){
                   var str = '';
                   if(res.status==200){ // 成功
                       for(var i=0;i<res.data.length;i++){
                           str +=' <li><a ><img src="'+res.data[i].img_path+'" width="711" height="425"/></a></li>';
                       }
                       $swiperClass.html(str);
                       mainfunction() ;
                   }


               },
               error:function () {

               }
           });
       }

   })

   /*  悬浮窗口结束 */
   function addTryPlay(){
       document.getElementById("demoplay").value="Yes";
       document.getElementById("ausername").value="demoguest";
       document.getElementById("apassword").value="qwertyu";
       document.getElementById("LoginForm").submit();
   }
    // 验证码
    var yzmurl = HTTPS_HEAD+'://'+urlArray[FETCH_NUM]+'/app/member/include/validatecode/captcha.php' ;
    $('.yzm_img').attr({'src':yzmurl,'onclick':'this.src="'+yzmurl+'?v='+Math.random()+'"'});

    $('#yzm_input').focus(function () { // 更新验证码
        $('.yzm_img').attr('src',yzmurl+'?v='+Math.random());
    })


</script>

