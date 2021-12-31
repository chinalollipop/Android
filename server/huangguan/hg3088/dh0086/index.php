<?php
session_start();
require ("include/config.inc.php");
//require ("include/redis.php");
require ("include/address.mem.php");

limitIpSee();

$redisObj = new Ciredis();

// 判断是否手机登录
$allulrarr = explode(',',$ulrarr) ;
$fetch_num = array_rand($allulrarr,1);
$afterurl = $allulrarr[$fetch_num]; // 随机取一个配置的域名

$useragent=$_SERVER['HTTP_USER_AGENT'];
$pctip= isset($_REQUEST['topc'] )?$_REQUEST['topc']:'' ;
$_SESSION['this_url'] = getMainHost() ; // 获取当前url  $_SERVER["HTTP_HOST"]
$thisurl = $_SESSION['this_url'] ;
$intr= isset($_REQUEST['intr'] )?$_REQUEST['intr']:'' ; // 从代理推广码
$maxintr= isset($_REQUEST['Intr'] )?$_REQUEST['Intr']:'' ; // 从代理推广码
$agent= strstr($_SERVER['REQUEST_URI'] , 'agent'); // 跳转到代理注册
if($intr){
    $ag_intr = $intr ;
}else{
    $ag_intr = $maxintr ;
}

if(!$pctip){ // 从手机打开带参数不需要判断
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
        // header('Location: http://'.HG_MOBILE_URL.'.'.$host);
        $urlsql = "select ID,UserName from ".DBPREFIX."web_agents_data where agent_url LIKE '%$thisurl%'"; // 匹配当前域名
        $urlresult = mysqli_query($dbLink,$urlsql);
        $urlrow = mysqli_fetch_assoc($urlresult);
        $agentname = $urlrow['UserName'] ; // 代理名称
        if($agentname){ // 代理推广域名存在
            $ag_intr_name = $agentname ;
        }else{
            $ag_intr_name = $ag_intr ;
        }
        $mobile_url = HTTPS_HEAD."://".HG_MOBILE_URL.".".$afterurl."?intr=".$ag_intr_name ;
        if($agent){ // 跳转到手机代理注册
            $mobile_url = HTTPS_HEAD."://".HG_MOBILE_URL.".".$afterurl."/".TPL_NAME."agents_reg.php";
        }
        echo "<script>parent.location.href ='".$mobile_url."'</script>";
        exit;

    }
}

$datajson = $redisObj->getSimpleOne('new_version_set'); // 取redis 设置的值
$datastr = json_decode($datajson,true) ;
$data_arr = explode(';',$datastr['oldurl']);
$fetch_num = array_rand($data_arr,1);
$newLogin = $data_arr[$fetch_num]?$data_arr[$fetch_num]:'/'; // 随机取一个配置的域名

$msg_member = getScrollMsg(); // 简体

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="shortcut icon" href="images/favicon.ico?v=<?php echo AUTOVER; ?>" type="image/x-icon">
<link type="text/css" rel="stylesheet" href="css/default.css?v=<?php echo AUTOVER; ?>" />
<link type="text/css" rel="stylesheet" href="css/reset.css?v=<?php echo AUTOVER; ?>" />
<title>Welcome to  <?php echo COMPANY_NAME;?> </title>

</head>
<body>
<!-- 左浮动-快速选单-开始 -->


<div class="rightdao">
    <div class="fl">
        <div class="tac"><a href="javascript:;" class="to_memberreg sy_zfd1"><img src="images/ldao1.png?v=<?php echo AUTOVER; ?>" alt=""></a></div>
        <div class="tac"><a href="javascript:;" target="_blank" class="set_mainurl sy_zfd1"><img src="images/ldao2.png?v=<?php echo AUTOVER; ?>" alt=""></a></div>
        <div class="tac"><a href="javascript:;" class="to_agentreg sy_zfd1"><img src="images/ldao3.png?v=<?php echo AUTOVER; ?>" alt=""></a></div>
        <div class="tac"><a href="javascript:;" target="_blank" class="set_agents_mainurl "><img src="images/ldao4.png?v=<?php echo AUTOVER; ?>" alt=""></a></div>
        <div class="tac"><a href="javascript:;" class="to_promos sy_zfd1"><img src="images/ldao5.png?v=<?php echo AUTOVER; ?>" alt=""></a></div>
        <div class="tac"><a href="javascript:;" class="to_service sy_zfd1" ><img src="images/ldao6.png?v=<?php echo AUTOVER; ?>" alt=""></a></div>
    </div>
    <div class="fl"><img src="images/ldao7.png?v=<?php echo AUTOVER; ?>" alt=""></div>
    <div class="cl"></div>

</div>
<!-- 左浮动-快速选单-结束 -->

<!-- 右浮动-开始 -->
<div class="lim_float_icon">
    <div onmouseenter="$(this).parent().css('right','0')" onmouseout="$(this).parent().css('right','-125px')">
        <a id="live800iconlink" class="to_service" >
            <span class="wechat_code_img" onmouseenter="$(this).parents('.lim_float_icon').css('right','0')"></span>
            <img src="/images/online.png" border="0" style="cursor:pointer;">
        </a>
</div>
</div>
<!-- 右浮动-结束 -->

<!-- top-开始 导航 -开始 -->
<a class="go_to_new" href="<?php echo $newLogin;?>" target="_blank">

</a>
<div class="header">
    <div class="top">
        <div class="inner psr">
            <div class="nowtime" id="">美东时间： <span class="" id="nowTime"> </span></div>

            <div class="topnav">


                <a href="http://dns0086.com/DNS一键优化.exe" target="_blank" class="hong" id="blink1">一键修改DNS</a>
                |
                <a href=" http://dns0086.com" target="_blank" class="huang" id="blink2">网站被跳转？预防及解决教程！</a>
                |
                <a href="javascript:;" class="to_downloadapp huang">手机投注</a>
                |

                <a href="javascript:void(0)" onclick="SetHome(this,window.location)">设为首页</a>
                |
                <a href="javascript:void(0)" onclick="shoucang(document.title,window.location)"> 加入收藏 </a>

                <a href="#" class=""><img src="images/gq.png" class="vm" alt=""></a>

            </div>
            <div class="cl"></div>


        </div>
       <!-- <div>
            <a href="https://www.2018-hg0086.com/" target="_blank">
                <img style="position: absolute;top: 33px;left: 1px;" src="images/sjb2018.png">
            </a>
        </div>-->
    </div>
    <div class="nav">
        <div class="inner">
            <div class="tiqiu"><img src="images/tqiu.png?v=2" alt=""></div>

            <ul class="navul">
                <li ><a href="javascript:;" class="bgimg to_index cur"><p class="navzw">网站首页</p><p class="navyw">HOME</p></a></li>
                <li><a href="javascript:;" class="bgimg to_sports"><p class="navzw">体育竞技</p><p class="navyw">SPORTS</p></a></li>
                <li><a href="javascript:;" class="bgimg to_lives"><p class="navzw">真人视讯</p><p class="navyw">LIVE DEALER</p></a></li>
                <li><a href="javascript:;" class="bgimg to_games"><p class="navzw">电子游艺</p><p class="navyw">CASINO</p></a></li>
                <li><a href="javascript:;" class="bgimg to_lotterys"><p class="navzw">彩票游戏</p><p class="navyw">LOTTERY</p></a></li>
                <li><a href="javascript:;"  class="bgimg to_chess"><p class="navzw">棋牌游戏</p><p class="navyw">CHESS</p></a></li>
                <li><a href="javascript:;" class="hong bgimg to_promos"><p class="navzw">优惠活动</p><p class="navyw">PROMOTION</p></a></li>
                <!--<li><a href="javascript:;" class="hong bgimg to_downloadapp"><p class="navzw">APP下载</p><p class="navyw">MOBILE</p></a></li>-->
                <li><a href="javascript:;" class="bgimg to_proxy"><p class="navzw">代理加盟</p><p class="navyw">AGENT</p></a></li>


            </ul>
            <div class="cl"></div>
        </div>
    </div>
</div>

<!-- top-结束 导航 -结束-->

<div class="subdeng">
    <div class="inner">
        <div class="subdengin">
            <div class="cl h5"></div>
            <form action="" target="_blank" method="post" name="LoginForm" id="LoginForm">
            	<input type=HIDDEN name="demoplay" id="demoplay" value="">
                <input type="hidden" name="Website" value=" ">
                <input type="hidden" name="uid" value="">
                <input type="hidden" name="langx" value="zh-cn">
                <input type="hidden" name="mac" value="">
                <input type="hidden" name="ver" value="">
                <input type="hidden" name="JE" value="1">
                <ul class="denginul denginulx fl">
                    <li>
                        <input  name="username" id="ausername" type="text" maxlength="15" value="" placeholder="登录名" tabindex="1" class="denginpt sinpt dicon1" >
                    </li>
                    <li>
                        <input  name="password" id="apassword" type="password" placeholder="密码" maxlength="15" tabindex="2" class="denginpt sinpt dicon2" typeval="password" >
                    </li>
                    <li style="position: relative">
                        <input  name="yzm_input" id="yzm_input" type="text" placeholder="验证码" maxlength="6"  class="denginpt sinpt dicon2" >
                        <img class="yzm_img" alt="验证码" style="position: absolute;width: 75px;right: 0;top:2px;"/>
                    </li>

                </ul>
            </form>
            <div class="fl pl5" style="padding-left:20px;">
                <a id="btnLogin" onclick="do_login();" tabindex="4" class="dbtn1 "><img src="images/dbtn1.png" width="94" height="29" alt=""></a>
                <a href="javascript:;" class="dbtn1 to_memberreg "><img src="images/dbtn2.png" width="94" height="29" alt=""></a>
			</div>
            <div class="fl pl5" style="margin-left:4px;font-size:15px;line-height:29px;text-align:center;height:29px;width:94px;background:#d4a41d;border-radius:3px;">
                <a onclick="addTryPlay()"><font color='#fefb85'>试玩参观</font></a>
            </div>
            <div class="fl subwangj">
                <a href="javascript:; " class="to_service ">忘记密码？</a> <!-- set_forget_url -->
            </div>
        </div>
    </div>
</div>
<!-- top-开始 -->

<div class="subnew">
    <div class="inner">
        <div class="sunnewin">
            <div class="lunleft">
                <marquee style="cursor:pointer;color:#fef0a7;height:30px;line-height: 30px; " class="user_msgnews" onmouseout="this.start();" onmouseover="this.stop();" direction="left" scrolldelay="150" scrollamount="5">

                </marquee>
            </div>
        </div>
    </div>

</div>

<!-- main -开始 中间容器-->
<div class="sy_m sy_content">


</div>
<!-- main -结束 -->

<!-- foot -开始 -->

<div class="footer">
    <div class="inner">
        <div class="fnav">
            皇冠现金网(正网)所提供的产品和服务，是由菲律宾政府授权和监管，我们将不遗余力的为您提供优质的服务和可靠的资金保障。
            <div class="cl"></div>

            <a href="javascript:;" class="to_aboutus sy_footnx1">关于我们</a>
            |
            <a href="javascript:;" class="to_contactus ">联系我们</a>
            |
            <a href="javascript:;" class="to_save sy_footnx1">存款帮助</a>
            |
            <a href="javascript:;" class="to_withdrawals sy_footnx1">取款帮助</a>
            |
            <a href="javascript:;" class="to_problem sy_footnx1">常见问题</a>
            |
            <a href="javascript:;" class="to_rule sy_footnx1">规则说明</a>
            |
            <a href="javascript:;" class="to_terms sy_footnx1">使用条款</a>
            |
            <a href="javascript:;" class="to_responsibility sy_footnx1">博彩责任</a>
            |
            <a href="javascript:;" class="to_proxy sy_footnx1">代理加盟</a>


            <div class="cl"></div>
            copyright © 皇冠现金网 reserved　24小时服务热线：<span class="ess_service_phone"> </span>　投诉电话：<span class="phl_service_phone"> </span>　邮箱：<span class="sz_service_email"> </span>

<!-- foot -结束 -->

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="js/ScrollPic.js" ></script>  <!--点击滚动图-->
<script type="text/javascript" src="js/wdatepicker/WdatePicker.js"></script>
<script  type="text/javascript">
    var urlStr = '<?php echo $ulrarr?>';
    var urlArray = urlStr.split(',') ;
    var usermessage = '<?php echo $msg_member?>' ; // 会员公告
    var HTTPS_HEAD = '<?php echo HTTPS_HEAD?>'; // https
    var FETCH_NUM = '<?php echo $fetch_num?>'; // https
    var webPicConfig = $.parseJSON('<?php echo str_replace("\\/", "/", json_encode(getPicConfig(), JSON_UNESCAPED_UNICODE));?>'); // 二维码等
    var webConfig = '<?php echo str_replace("\\/", "/", json_encode(getSysConfig(), JSON_UNESCAPED_UNICODE));?>';　// 基础设置
    var web_config = $.parseJSON(webConfig);
    localStorage.setItem('webconfigbase',JSON.stringify(web_config));
    var serviceurl = web_config.service_meiqia;

   // console.log(HTTPS_HEAD);
    $(function() {

        loadPagesAction();
        loadIndex(); // 默认加载首页
        setFirstAction();
        getIntroducer();

        urlSetAction(HTTPS_HEAD,FETCH_NUM);
        getTime();
        setInterval(getTime, 1000);
        setUserMsg(usermessage,'nottip') ;

        // 验证码
        var yzmurl = HTTPS_HEAD+'://'+urlArray[FETCH_NUM]+'/app/member/include/validatecode/captcha.php' ;
        $('.yzm_img').attr({'src':yzmurl,'onclick':'this.src="'+yzmurl+'?v='+Math.random()+'"'});
        $('#yzm_input').focus(function () { // 更新验证码
            $('.yzm_img').attr('src',yzmurl+'?v='+Math.random());
        })
        /*  左浮动-快速选单-开始 */
        $(window).scroll(function () {
            var sc = $(window).scrollTop();
            $(".rightdao").stop().animate({
                top: sc + 120
            }, 400);
        });

        /* 左浮动-快速选单-结束 */

        // 头部菜单
        $(window).scroll(function () {
            var sc = $(window).scrollTop();
            if (sc > 250) {
                $(".header").addClass("cur");
            } else {
                $(".header").removeClass("cur");
            }
        });

        // 微信客服
        $('.wechat_code_img').css({'background':'url('+webPicConfig.server_wechat_code+')'});

    });

    /*  浮动-快速选单-开始 */
    var left_top = 150, right_top = 150, float_list = [];
    $(window).load(function() {
        // 廳主自改 - 浮動圖
        float_list['0'] = $('#TplFloatPic_0');
        float_list['1'] = $('#TplFloatPic_1');

        for (var i in float_list) {
            var self = float_list[i], picfloat = (self.attr('picfloat') == 'right') ? 1 : 0;

            // self.show().Float({'floatRight' : picfloat, 'topSide' : ((picfloat == 1) ? right_top : left_top)});

            // ie6 png bug
            if (navigator.userAgent.toLowerCase().indexOf('msie 6') != -1) {
                $.each(self.find('img'), function(){
                    $(this).css({'width':$(this).width(),'height' : $(this).height()});
                });
            }
            if (picfloat) {
                right_top = right_top + 10 + (self.height() || 150);
            } else {
                left_top = left_top + 10 + (self.height() || 150);
            }

            self.hover(function() {
                $(this).find('a > img:first').css('display', 'none');
                $(this).find('a > img:last').css('display', 'block');
            }, function() {
                $(this).find('a > img:last').css('display', 'none');
                $(this).find('a > img:first').css('display', 'block');
            }).find('div').click(function() {
                event.cancelBubble = true;
                $(this).parent('div').hide();
            });
        }
    });
    /*  浮动-快速选单-结束 */
	function addTryPlay(){
		document.getElementById("demoplay").value="Yes";
		document.getElementById("ausername").value="demoguest";
		document.getElementById("apassword").value="qwertyu";
		document.getElementById("LoginForm").submit();
	}

</script>
<div class="tong_ji" style="display: none;">
    
   <!-- <script src="https://s22.cnzz.com/z_stat.php?id=1273656429&web_id=1273656429" language="JavaScript"></script>-->

</div>


</body>
</html>

