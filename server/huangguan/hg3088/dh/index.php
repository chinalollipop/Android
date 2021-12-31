<?php
session_start();
require ("include/config.inc.php");
//require ("include/redis.php");
require ("include/address.mem.php");

limitIpSee();

//print_r($ulrarr);
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
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[16]i|770s|802s|a wa|abac|ac(er|oo|s\)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\(n|u)|c55\/|capi|ccwa|cdm\|cell|chtm|cldc|cmd\|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\s|devi|dica|dmob|do(c|p)o|ds(12|\d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([47]0|os|wa|ze)|fetc|fly(\|_)|g1 u|g560|gene|gf\5|g\mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\(m|p|t)|hei\|hi(pt|ta)|hp( i|ip)|hs\c|ht(c(\| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\(20|go|ma)|i230|iac( |\|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\[aw])|libw|lynx|m1\w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[02]|n20[23]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\([18]|c))|phil|pire|pl(ay|uc)|pn\2|po(ck|rt|se)|prox|psio|pt\g|qa\a|qc(07|12|21|32|60|\[27]|i\)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\|oo|p\)|sdk\/|se(c(\|0|1)|47|mc|nd|ri)|sgh\|shar|sie(\|m)|sk\0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\|v\|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\|tdg\|tel(i|m)|tim\|t\mo|to(pl|sh)|ts(70|m\|m3|m5)|tx\9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[03]|\v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\|your|zeto|zte\/i',substr($useragent,0,4))) {
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

$msg_member = getScrollMsg(); // 简体

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link type="text/css" rel="stylesheet" href="css/default.css?v=<?php echo AUTOVER; ?>" />
<title>Welcome to <?php echo COMPANY_NAME;?> </title>

</head>
<body>
<!-- 左浮动-快速选单-开始 -->
<div id="divQQbox" class="QQbox">
	<div id="divOnline" class="Qlist">
		<div class="OnlineLeft sy_zfd">
		
            <div class="sy_zfdz">
                <a href="javascript:;" class="to_memberreg sy_zfd1">会员注册</a>
                <a href="javascript:;" target="_blank" class="set_mainurl sy_zfd1">会员登陆</a> <!-- member_login   -->
                <a href="javascript:;" class="to_agentreg sy_zfd1">代理注册</a>
                <a href="javascript:;" target="_blank" class="set_agents_mainurl sy_zfd1">代理登陆</a> <!-- agent_login -->
                <a href="javascript:;" class="to_promos sy_zfd1">最新优惠</a>
                <a href="javascript:;" class="to_service sy_zfd1" >在线客服</a>
            </div>
            <a href="javascript:;" class="sy_zfdy"></a>

		</div>
		<div class="OnlineBtn"></div>
	</div>
</div>
<!-- 左浮动-快速选单-结束 -->


<!-- 右浮动-开始 -->
<div class="ewm_small"></div>
<div class="keifu">
	<a href="javascript:;" class="to_service keifu_box">
        <span class="sy_ewm">
            <span class="sy_ewm1 wechat_code_img"></span>
           <a href="javascript:void(0)" class="keifu_close sy_ewm2"></a>
        </span>

	</a>

</div>
<!-- 右浮动-结束 -->

<!-- top-开始 -->
<div class="sy_top">
	<div class="sy_topn">
    	<a class="sy_logo"><img src="images/sy_03.png" /></a>
        <div class="sy_topny">
        	<div class="sy_topnys">
            	<a href="javascript:;" class="sy_topnys1"><img src="images/sy_18.png" /></a>
            	<a href="javascript:;" class="sy_topnys1"><img src="images/sy_16.png" /></a>
            	<a href="javascript:;" class="sy_topnys1"><img src="images/sy_14.png" /></a>
            	<a href="javascript:;" class="sy_topnys2">收藏本站</a>
            	<span class="sy_topnys3">|</span>
            	<a href="javascript:;" class="sy_topnys2">设为首页</a>
            </div>
            <div class="sy_topnyx">
            	<a class="sy_topnyx1"><img src="images/sy_06.png?v=2" /></a>
            	<a target="_blank" class="sy_topnyx2 set_mainurl" ><img src="images/sy_09.png" /></a> <!--  -->
            	<a href="javascript:;" class="to_memberreg sy_topnyx3"><img src="images/sy_11.png" /></a>
            </div>
        </div>
    </div>
    <!--<div>
        <a href="https://2018-hg6668.com" target="_blank">
            <img style="position: absolute;top: 5px;left: 1px;z-index: 9" src="images/sjb2018-1.png">
        </a>
    </div>-->
</div>
<!-- top-结束 -->

<!-- 导航 -开始 -->
<div class="sy_nav">
    <div class="sy_navn">
        <div class="sy_navnr">
            <a href="javascript:;" class="to_index sy_navn1 sy_navn1_h">
                <span class="sy_navn11">网站首页</span>
                <span class="sy_navn12">HOME</span>
            </a>
            <span class="sy_navn2"><img src="images/sy_35.png" /></span>

            <a href="javascript:;" class="to_sports sy_navn1">
                <span class="sy_navn11">体育博弈</span>
                <span class="sy_navn12">SPORTS</span>
            </a>
            <span class="sy_navn2"><img src="images/sy_35.png" /></span>

            <a href="javascript:;" class="to_lives sy_navn1">
                <span class="sy_navn11">真人视讯</span>
                <span class="sy_navn12">LIVE CASINO</span>
            </a>
            <span class="sy_navn2"><img src="images/sy_35.png" /></span>

            <a href="javascript:;" class="to_games sy_navn1">
                <span class="sy_navn11">电子游艺</span>
                <span class="sy_navn12">SPORTS</span>
            </a>
            <span class="sy_navn2"><img src="images/sy_35.png" /></span>

            <a href="javascript:;" class="to_lotterys sy_navn1">
                <span class="sy_navn11">彩票游戏</span>
                <span class="sy_navn12">LOTTERY</span>
            </a>
            <span class="sy_navn2"><img src="images/sy_35.png" /></span>
            <a href="javascript:;" class="to_chess sy_navn1" >
                <span class="sy_navn11">棋牌游戏</span>
                <span class="sy_navn12">CHESS</span>
            </a>
            <span class="sy_navn2"><img src="images/sy_35.png" /></span>
            <a href="javascript:;" class="to_promos sy_navn1">
                <span class="sy_navn11">优惠活动</span>
                <span class="sy_navn12">PROMOTIONS</span>
            </a>
            <span class="sy_navn2"><img src="images/sy_35.png" /></span>

            <a href="javascript:;" class="to_downloadapp sy_navn1">
                <span class="sy_navn11">手机APP</span>
                <span class="sy_navn12">MOBILE</span>
            </a>
            <span class="sy_navn2"><img src="images/sy_35.png" /></span>

            <a href="javascript:;" class="to_proxy sy_navn1">
                <span class="sy_navn11">代理加盟</span>
                <span class="sy_navn12">AGENT</span>
            </a>



        </div>
    </div>
</div>
<!-- 导航 -结束 -->

<!-- main -开始 中间容器-->
<div class="sy_m sy_content">


</div>
<!-- main -结束 -->

<!-- foot -开始 -->
<div class="sy_foot">
	<div class="sy_footn">
    	<div class="sy_footns">
        	<span class="sy_footns1"><img src="images/sy_86.png" />在线电话：<span class="ess_service_phone"> </span></span>
        	<span class="sy_footns1"><img src="images/sy_86.png" />投诉电话：<span class="phl_service_phone"> </span></span>
        	<span class="sy_footns1"><img src="images/sy_89.png" />联系邮箱：<span class="sz_service_email"> </span></span>
        </div>
        <div class="sy_footnc">
        	<div class="sy_footncn">
            	<img src="images/sy_107.png" />
                <div class="sy_footncny">
                	<span class="sy_footncny1">金牌信誉</span>
                	<span class="sy_footncny2">政府颁发执照/安全有保障</span>
                </div>
            </div>
        	<div class="sy_footncn">
            	<img src="images/sy_101.png" />
                <div class="sy_footncny">
                	<span class="sy_footncny1">银行服务</span>
                	<span class="sy_footncny2">24小时存取款/3分钟到账</span>
                </div>
            </div>
        	<div class="sy_footncn">
            	<img src="images/sy_98.png" />
                <div class="sy_footncny">
                	<span class="sy_footncny1">支付方式</span>
                	<span class="sy_footncny2">如何提彩？ 如何充值？</span>
                </div>
            </div>
        	<div class="sy_footncn sy_footncn4">
            	<img src="images/sy_104.png" />
                <div class="sy_footncny">
                	<span class="sy_footncny1">皇冠网址</span>
                	<span class="sy_footncny2">最新线路：<span class="yj_backup_web_url"> </span> </span>
                </div>
            </div>
        	<div class="sy_footncn sy_footncn5">
            	<img src="images/sy_105.png" />
                <div class="sy_footncny">
                	<span class="sy_footncny1">代理加盟</span>
                    <span class="sy_footncny2">
                        <a href="javascript:;" class="to_agentreg">代理注册 </a>
                        <a href="javascript:;" class="set_agents_mainurl" target="_blank">&nbsp;&nbsp;代理登陆 </a>
                    </span>
                </div>
            </div>
        </div>
        <div class="sy_footnx">
        	<div class="sy_footnxs">
                <a href="javascript:;" class="to_aboutus sy_footnx1">关于我们</a><span>|</span>
                <a href="javascript:;" class="to_rule sy_footnx1">规则说明</a><span>|</span>
                <a href="javascript:;" class="to_save sy_footnx1">存款帮助</a><span>|</span>
                <a href="javascript:;" class="to_withdrawals sy_footnx1">取款帮助</a><span>|</span>
                <a href="javascript:;" class="to_problem sy_footnx1">常见问题</a><span>|</span>
                <a href="javascript:;" class="to_terms sy_footnx1">使用条款</a><span>|</span>
                <a href="javascript:;" class="to_proxy sy_footnx1">代理加盟</a><span>|</span>
                <a href="javascript:;" class="to_responsibility sy_footnx1">负责任博彩</a>
            </div>
        	<div class="sy_footnxx">
            	&nbsp;© 版权所有 2011-<?php echo date('Y')?> 属于皇冠6668所有
            </div>
        </div>
    </div>
</div>
<!-- foot -结束 -->

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/common.js?v=<?php echo AUTOVER; ?>"></script>
    <script type="text/javascript" src="js/banner.js?v=<?php echo AUTOVER; ?>"></script> <!--banner-->
    <script type="text/javascript" src="js/ScrollPic.js?v=<?php echo AUTOVER; ?>" ></script>  <!--点击滚动图-->
    <script type="text/javascript" src="js/wdatepicker/WdatePicker.js?v=<?php echo AUTOVER; ?>"></script>
<script  type="text/javascript">
    var urlStr = '<?php echo $ulrarr?>';
    var urlArray = urlStr.split(',') ;
    var usermessage = '<?php echo $msg_member?>' ; // 会员公告
    var HTTPS_HEAD = '<?php echo HTTPS_HEAD?>'; // https
    var FETCH_NUM = '<?php echo $fetch_num?>'; // https
    var webPicConfig = $.parseJSON('<?php echo str_replace("\\/", "/", json_encode(getPicConfig(), JSON_UNESCAPED_UNICODE));?>');　// 二维码等
    var webConfig = '<?php echo str_replace("\\/", "/", json_encode(getSysConfig(), JSON_UNESCAPED_UNICODE));?>'; // 基础设置
    var web_config = $.parseJSON(webConfig);
    localStorage.setItem('webconfigbase',JSON.stringify(web_config));
   var serviceurl = web_config.service_meiqia;
    // console.log(urlStr);
   //  console.log(urlArray);
   //  console.log(urlArray.length);
    $(function(){
        loadIndex() ; // 默认加载首页
        loadPagesAction() ;
        setFirstAction() ;
        // setInterval("changeColor()",200); //●设置一个定时器，每200毫秒调用一次变色函数
        getIntroducer() ;
        urlSetAction(HTTPS_HEAD,FETCH_NUM) ;

       /*  左浮动-快速选单-开始 */
        $("#divQQbox").hover(function(){
            $(this).stop(true,false);
            $(this).animate({left:0},300);
        },function(){
            $(this).animate({left:-124},300);
        });
        /* 左浮动-快速选单-结束 */

        /* 右侧-二维码-开始  */
        $(".keifu_close").click(function() {
            $(".keifu").animate({width:0},300);
            $(".ewm_small").show();
        });

        $(".ewm_small").hover(function() {
            $(".keifu").animate({width:'138px'},300);
            $(".ewm_small").hide();
        });

        // 微信客服
        $('.wechat_code_img').css({'background':'url('+webPicConfig.server_wechat_code+')'});


    });




</script>
<div class="tong_ji" style="display: none;">
   <!-- <script src="https://s22.cnzz.com/z_stat.php?id=1273656429&web_id=1273656429" language="JavaScript"></script>-->
</div>


</body>
</html>
