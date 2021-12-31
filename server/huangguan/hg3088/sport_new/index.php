<?php
session_start();
header ("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

$_SESSION['Language'] = 'zh-cn';
require ("app/member/include/config.inc.php");
require ("app/member/include/address.mem.php");

limitIpSee();

// 避免再次加载文件，连接数据库
$_SESSION['COMPANY_NAME_SESSION'] = COMPANY_NAME;
$_SESSION['TPL_NAME_SESSION'] = TPL_NAME;
$_SESSION['TPL_FILE_NAME_SESSION'] = TPL_FILE_NAME;
$_SESSION['AGENT_LOGIN_URL'] = returnAgentUrl(); // 代理登录链接
$_SESSION['HTTPS_HEAD_SESSION'] = HTTPS_HEAD;
$_SESSION['HOST_SESSION'] = $host = getMainHost();
$lydata = getLyQpSetting();
$_SESSION['LYTEST_PLAY_SESSION'] = $lydata['demourl']; // 乐游试玩链接


$pctip= isset($_REQUEST['topc'] )?$_REQUEST['topc']:'' ; // 从手机端跳转到pc端标志

// 判断是否手机登录
$useragent=$_SERVER['HTTP_USER_AGENT'];
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
        $mobile_url = HTTPS_HEAD."://".HG_MOBILE_URL.".".$host."?intr=".$ag_intr;
        if($agent){ // 跳转到手机代理注册
            $mobile_url = HTTPS_HEAD."://".HG_MOBILE_URL.".".$host."/agent";
        }
        echo "<script>window.location.href ='".$mobile_url."'</script>";
        exit;
    }
}

// 系统维护
ifSysMaintain();

// 新年活动是否开启
$sRedPocketset = $redisObj->getSimpleOne('red_pocket_open'); // 取redis 设置的值
$aRedPocketset = json_decode($sRedPocketset,true) ;
$af_aRedPocketset = $aRedPocketset['redPocketOpen']=='open'?TRUE:FALSE;

echo "<script>var red_pocket_type ='".$af_aRedPocketset."'</script>";

$test_flag = $_SESSION['test_flag']; // 0 正式帐号，1 测试账号
$uid = $_SESSION['Oid']; // 判断是否已登录
$username = $_SESSION['UserName'] ;

$redisObj = new Ciredis();
//创建登录旧版连接地址
$oldUnionCode = getUnionCode();
$redisRes = $redisObj->setOne($_SESSION['userid'].'_NEW_CHANGE_OLD',serialize($oldUnionCode));
$keyOld = md5($pwd.$oldUnionCode.md5($name));
$idOld = $_SESSION['userid']+9876889;

$afterurl = returnNewOldVersion('old'); // 随机取一个配置的域名

if(!$afterurl){
    $afterurl = HTTPS_HEAD.'://'.$host;
}
//if($uid){ // 已登录
//    $oldLogin = $afterurl.'/login.php?id='.$idOld.'&sign=newchangeold&username='.$username.'&password='.$_SESSION['password'].'&key='.$keyOld ;
//}else{
    $oldLogin = $afterurl;
// }
$_SESSION['toOldLogin'] = $oldLogin; // 旧版地址

// 彩票登录链接
$urlall = $_SERVER["HTTP_HOST"] ; // 判断当前域名是否带 www 标志
$urlalltip = substr_count($urlall,'www') ; // $urlalltip ,0 不带www ,1 带 www
//$cpUrl=HTTPS_HEAD."://".CP_URL.'.'.$host."/main?sign=".$urlalltip.'.asf.newtolottery'; // .newtolottery  新版到彩票标志
$cpUrl=HTTPS_HEAD."://".CP_URL.'.'.$host."/main?sign=".$urlalltip.'.asf'; // .newtolottery  新版到彩票标志
$_SESSION['LotteryUrl'] = $cpUrl;

// 皇冠体育链接
$sportCenterSet = $redisObj->getSimpleOne('sport_center_set');
$sportConfig = json_decode($sportCenterSet,true);
$sportCenterUrl = ($uid && $_SESSION['Agents'] == 'demoguest') ? $sportConfig['tryUrl'] : $sportConfig['apiUrl']; // 是否试玩账号
$_SESSION['sportCenterUrl'] = $sportCenterUrl;

$memberNotice = getScrollMsg();
$_SESSION['memberNotice'] = isset($memberNotice)?$memberNotice:'欢迎光临';

if(TPL_NAME=='views/6668/' || TPL_NAME=='views/0086/' || TPL_NAME=='views/0086dj/' || TPL_NAME=='views/jinsha/'){ // 加载不同的会员中心页面处理
    $ucentertip = '';
}else{
    $ucentertip = 'dis';
}

if(TPL_NAME=='views/6668/' || TPL_NAME=='views/0086/'|| TPL_NAME=='views/0086dj/'|| TPL_NAME=='views/bet365/' || TPL_NAME=='views/nbet365/'){
    $logincp = 'sport'; // 体育彩票
}else{
    $logincp = 'new'; // 新彩票
}

// 是否需要加载第三方余额
if(TPL_NAME=='views/3366/' || TPL_NAME=='views/8msport/' || TPL_NAME=='views/wnsr/' || TPL_NAME=='views/newhg/'){
    $needThird = false;
}else{
    $needThird = true;
}


?>
<html>
<head>
    <title> <?php echo COMPANY_NAME;?> </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="<?php echo TPL_NAME;?>images/favicon.ico" type="image/x-icon">
    <meta name="keywords" content="<?php echo COMPANY_NAME.','.COMPANY_NAME.'登入'.','.COMPANY_NAME.'平台';?>">
    <meta name="description" content="<?php echo COMPANY_NAME;?>成立2003年，总部在菲律宾注册资金100多亿 ，专业提供足球，篮球，排球网上投注，平台得到百万体育爱好者的好评认可！力争打造世界最大的现金网，公司全天24小时在线服务，欢迎咨询！">
    <link href="style/swiper-3.4.2.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="style/tncode/style.css?v=<?php echo AUTOVER; ?>"  />
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/common.css?v=<?php echo AUTOVER; ?>" >
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/index_login.css?v=<?php echo AUTOVER; ?>" >
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/Reg.css?v=<?php echo AUTOVER; ?>">
    <link rel="stylesheet" type="text/css" href="style/new_sport.css?v=<?php echo AUTOVER; ?>" />
    <!--    --><?php
//    if(SPORT_FLUSH_WAY=='ra686'){
//        echo '<link rel="stylesheet" type="text/css" href="style/new_sport.css"  />';
//    }
//    ?>

</head>

<body >
<div class="all_wrapper">
    <?php include TPL_NAME.'header.php'; ?>

    <!-- 中间内容 -->
    <div class="middle_content middle_content_<?php echo TPL_FILE_NAME;?>">

    </div>

    <?php include TPL_NAME.'footer.php'; ?>

    <!-- 彩票联合登陆 -->
    <iframe name="cp_login_url" id="cp_login_url" scrolling="NO" noresize src="" style="display: none;"></iframe>

    <!-- 三方彩票登录 -->
    <iframe name="third_cp_login_url" id="third_cp_login_url" scrolling="NO" noresize src="" style="display: none;"></iframe>
    <!-- 正式登陆POST ，测试登陆GET-->
    <!-- name="toXinyong" 是否跳转到信用盘：是就传 1，要不就空 -->
    <form id="index_third_form"  target="third_cp_login_url" method="<?php echo $test_flag==1?'get':'post'; ?>">
        <input type="hidden" name="params" class="cp_params" />
        <input type="hidden" name="thirdLotteryId" class="cp_thirdlotteryid" />
        <input type="hidden" name="toXinyong" />

    </form>

    <!-- 体育联合登陆 -->
    <!--<iframe name="sport_login_url" id="sport_login_url" scrolling="NO" noresize src="" style="display: none;"></iframe>-->
</div>
</body>
<script type="text/javascript" src="js/jquery.js"></script>
<!--<script type="text/javascript" src="js/jquery.easing.js"></script>-->
<script type="text/javascript" src="js/layer/layer.js"></script>
<script type="text/javascript" src="js/register/laydate.min.js"></script>
<script type="text/javascript" src="js/jquery.page.js"></script>
<script type="text/javascript" src="js/swiper-3.4.2.jquery.min.js"></script>
<script type="text/javascript" src="style/tncode/tn_code.js?v=<?php echo AUTOVER; ?>" ></script>
<script type="text/javascript" src="<?php echo TPL_NAME;?>js/config.js?v=<?php echo AUTOVER; ?>" ></script>
<script type="text/javascript" src="js/loadpage_common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="js/digitalScroll.js"></script>
<script type="text/javascript" src="js/register/validate.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="js/clipboard.min.js"></script>
<!-- 新版水源需要引用 js 开始 -->
<script type="text/javascript" src="/js/league_list.js?v=<?php echo AUTOVER; ?>"></script>
<!-- 新版水源需要引用 js 结束 -->
<?php
if(TPL_FILE_NAME=='3366' || TPL_FILE_NAME=='newhg'){
    echo '<script type="text/javascript" src="js/jquery.fullPage.js"></script>';
}
?>

<script type="text/javascript">
    var uid = '<?php echo $uid;?>' ;
    var userAgents = '<?php echo $_SESSION['Agents'];?>' ;
    var userTestFlag = '<?php echo $test_flag;?>' ;
    var sportflushway='<?php echo SPORT_FLUSH_WAY;?>'; // 刷水渠道匹配模板
    var tplName='<?php echo TPL_NAME;?>';
    var tplfilename='<?php echo TPL_FILE_NAME;?>';
    var companyname='<?php echo COMPANY_NAME;?>';
    var ucentertip ='<?php echo $ucentertip;?>';
    var logincp ='<?php echo $logincp;?>';
    var guest_login_phone_turn = '<?php echo GUEST_LOGIN_MUST_INPUT_PHONE; ?>';
    var webPicConfig = $.parseJSON('<?php echo str_replace("\\/", "/", json_encode(getPicConfig(), JSON_UNESCAPED_UNICODE));?>');　// 二维码等
    var webConfig = '<?php echo str_replace("\\/", "/", json_encode(getSysConfig(), JSON_UNESCAPED_UNICODE));?>';　// 基础设置
    var web_config = $.parseJSON(webConfig);

    if(tplfilename=='wnsr'){
        web_config.agents_service_qq = web_config.vns_agents_service_qq;
        web_config.service_qq = web_config.vns_service_qq;
        web_config.service_meiqia = web_config.vns_service_meiqia;
        web_config.service_email = web_config.vns_service_email;
        web_config.service_phone_24 = web_config.vns_service_phone_24;
        webPicConfig.download_android_url = webPicConfig.vns_download_android_url;
        webPicConfig.download_ios_url = webPicConfig.vns_download_ios_url;
        webPicConfig.index_pic_left = webPicConfig.vns_index_pic_left;
        webPicConfig.index_pic_right = webPicConfig.vns_index_pic_right;
        webPicConfig.server_wechat_code = webPicConfig.vns_server_wechat_code;
    }
    localStorage.setItem('pageTabType',''); // 刷新初始化
    localStorage.setItem('webconfigbase',JSON.stringify(web_config));
    var configbase={
        onlineserve:web_config.service_meiqia,
        exeWinUrl:web_config.download_win_exe,
        macWinUrl:web_config.download_mac_exe,
    };
    // console.log(web_config)

    if(!uid){ // 未登录
        // 初始化验证码
        var $TNCODE = tncode;
        tncode.init();
    }


    /*
     * 转化为千位符
     *  */
    function fortMoney(s, n) {
        n = n > 0 && n <= 20 ? n : 2;
        s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
        var l = s.split(".")[0].split("").reverse(),
            r = s.split(".")[1];
        var t = "";
        for(var i = 0; i < l.length; i ++ ){
            t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");
        }
        return t.split("").reverse().join("") + "." + r;
    }
    /* jinsha 数字动态处理 */
    function jackPortNumber() {
        $('.cjcj_num').each(function () {
            var num = parseFloat($(this).text().replace(/,/g,'')) ;
            // console.log(num)
            // console.log(typeof(num)) ;
            var f_num = parseFloat(Math.random().toFixed(2)/2) ; // 随机生成小数
            $(this).text(fortMoney((num+f_num).toFixed(2),2)) ;
            //console.log(typeof(f_num)) ;
        })
    }

    $(document).ready(function () {
        var memberNum = '<?php echo $_SESSION['membermessage']['mcou'];?>' ; // 是否有会员信息
        var memberMsg = '<?php echo $_SESSION['membermessage']['mem_message'];?>' ; // 会员信息
        var ifIntr = indexCommonObj.getIntroducer()
        if(tplfilename=='jinsha' || tplfilename=='8msport'){
            if(!ifIntr || ifIntr=='intr' || ifIntr=='login' || ifIntr=='changepwd' || ( tplfilename=='jinsha' && ifIntr=='intr')){
                indexCommonObj.loadIndex() ;
            }
        }else{
            if(!ifIntr ){ // 是否有跳转
                indexCommonObj.loadIndex() ;
            }
        }

        indexCommonObj.loadPageAction(uid) ;
        if(red_pocket_type && tplfilename=='0086'){ // 需要判断是否开启活动
            setTimerAc('.new_year_time');
        }

        if(uid){
            indexCommonObj.getUserMoneyAction(uid);
            indexCommonObj.getUserMessage();

            var indexInterval = setInterval(function () {
                indexCommonObj.getUserMoneyAction(uid);
                indexCommonObj.getUserMessage();
            },8000)

            $(document).on('visibilitychange', (function() { // 避免过度
                // 页面变为可见时触发
                if (document.visibilityState == 'visible') {
                    indexInterval = setInterval(function () {
                        indexCommonObj.getUserMoneyAction(uid);
                        indexCommonObj.getUserMessage();
                    },8000)
                }else{
                    clearInterval(indexInterval);
                }
            }).bind(this));

        }

        var sport_url_num = localStorage.getItem('sport_url_num');
        var cp_url_num = localStorage.getItem('cp_url_num');
        var third_cp_url_num = localStorage.getItem('third_cp_url_num');

        // if(uid && (!sport_url_num || sport_url_num ==1 )){ // 皇冠体育
        //     getSportUrl();
        // }
        if(logincp == 'sport'){
            if(uid && (!cp_url_num || cp_url_num ==1 )){ // 彩票登录
                getLotteryUrl();
            }
        }else{
            if(uid && (!third_cp_url_num || third_cp_url_num ==1 )){ // 三方彩票
                getThirdLotteryAction('login');
            }
        }

        if(!uid){ // 未登录
            indexCommonObj.rightBottomAd(uid) ;
            localStorage.setItem('sport_url_num','1'); // 记录皇冠体育登录次数,1 为初始化
            localStorage.setItem('cp_url_num',1) ; // 记录彩票登录次数,1 为初始化
            localStorage.setItem('third_cp_url_num',1) ; // 记录彩票登录次数,1 为初始化
            localStorage.setItem('memberMsg',0) ; // 未登录重置会员信息状态
        }

        function getSportUrl() {
            var data = {};
            data.uid = '<?php echo $uid;?>';
            data.action = 'cm';
            $.ajax({
                type : 'POST',
                url : '/app/member/sportcenter/sport_api.php?_'+ Math.random(),
                data : data,
                dataType : 'json',
                success:function(res) {
                    if(res.code == 1){
                        if(res.data.url !== undefined) {
                            localStorage.setItem('sport_url_num', '2'); // 记录体育登录次数
                            $('#sport_login_url').attr('src',res.data.url) ;
                        }
                    }else{
                        alert(res.message);
                    }
                },
                error:function(){
                    layer.msg('网络异常，请稍后重试！',{time:alertTime});
                }
            });
        }

        function getLotteryUrl(){
            $.ajax({
                type : 'POST',
                url : '/app/member/api/lotteryUrlApi.php' ,
                data : '',
                dataType : 'json',
                success:function(res) {
                    if(res){
                        localStorage.setItem('cp_url_num',2) ; // 记录彩票登录次数,1 为初始化
                        $('#cp_login_url').attr('src',res.data.lotteryUrl) ;
                    }

                },
                error:function(){
                    layer.msg('请求彩票登录地址异常',{time:alertTime});
                }
            });
        }
        /*
    *  获取彩票登录链接
    *  type : login 登录
    *
    * */
        function getThirdLotteryAction(type){
            var url = '/app/member/api/thirdLotteryApi.php';
            $.ajax({
                type: 'POST',
                url: url,
                data: {actype:type},
                dataType: 'json',
                success: function (res) {
                    if(res){
                        if(res.status =='200'){
                            switch (type){
                                case 'login': // 登录
                                    $('#index_third_form').attr({'action':res.data.third_cpUrl});
                                    $('.cp_params').val(res.data.params);
                                    $('.cp_thirdlotteryid').val(res.data.thirdLotteryId);
                                    localStorage.setItem('third_cp_url_num',2) ; // 记录彩票登录次数,1 为初始化
                                    document.getElementById('index_third_form').submit(); // 打开彩票链接
                                    break;
                            }

                        }else{ // 异常
                            layer.msg(res.describe,{time:alertTime});
                        }

                    }
                },
                error: function (res) {
                    layer.msg('数据获取失败，请稍后再试!',{time:alertTime});

                }
            });
        }

        // 会员弹窗信息
        function alertMemberMsg() {
            if(memberNum >0 && ( !localStorage.getItem('memberMsg') || localStorage.getItem('memberMsg') < 1) ){
                layer.alert(memberMsg, {
                    title: '会员信息',
                    icon: false , // 0,1
                    skin: 'layer-ext-moon'
                }) ;
                localStorage.setItem('memberMsg',1) ;
            }

        }

        // 滚动导航栏处理
        function scrollAuto() {
            $(document).on("scroll", function(){
                if($(document).scrollTop() > 20){
                    $(".cms_main_nav").addClass("shrink");
                    $(".cms_main_logo").addClass("cms_main_logo_small");
                    $(".cms_uti_navi").css({"right":"90px","top":"42px"});
                    $(".cms_short_no").hide();
                } else {
                    $(".cms_main_nav").removeClass("shrink");
                    $(".cms_main_logo").removeClass("cms_main_logo_small");

                    $(".cms_uti_navi").css({"right":"0px","top":"0px"});
                    $(".cms_short_no").show();
                }
            });
        }

        // 下拉显示更多菜单
        function showMoreTags(){
            var $top_user_nav = $('.top_user_nav') ;
            var thirdMoneyMore = false ;
            $top_user_nav.on('click','.topnav_money',function () {
                if($top_user_nav.find('.show_operate').hasClass('index_moveup')){
                    $top_user_nav.find('.show_operate').removeClass('index_moveup') ;
                }else{
                    $top_user_nav.find('.show_operate').addClass('index_moveup') ;
                    $top_user_nav.find('.show_personal,.show_balance').removeClass('index_moveup') ;
                }

            });
            $top_user_nav.on('click','.topnav_user',function () {
                if($top_user_nav.find('.show_personal').hasClass('index_moveup')){
                    $top_user_nav.find('.show_personal').removeClass('index_moveup') ;
                }else{
                    $top_user_nav.find('.show_personal').addClass('index_moveup') ;
                    $top_user_nav.find('.show_operate,.show_balance').removeClass('index_moveup') ;
                }

            });
            $top_user_nav.on('click','.topnav_add',function () { // 下拉展示更多余额
                if($top_user_nav.find('.show_balance').hasClass('index_moveup')){
                    $top_user_nav.find('.show_balance').removeClass('index_moveup') ;
                }else{
                    $top_user_nav.find('.show_balance').addClass('index_moveup') ;
                    $top_user_nav.find('.show_operate,.show_personal').removeClass('index_moveup') ;
                }
                if('<?php echo $needThird;?>' && !thirdMoneyMore){ // 放在这避免首次加载太多数据
                    if(logincp == 'new'){
                        indexCommonObj.getUserQpBanlance(uid,'gmcp') ;
                    }
                    indexCommonObj.getUserQpBanlance(uid,'ky') ;
                    indexCommonObj.getUserQpBanlance(uid,'ly') ;
                    indexCommonObj.getUserQpBanlance(uid,'vg') ;
                    indexCommonObj.getUserQpBanlance(uid,'kl') ;
                    // if(tplfilename !='newhg'){
                    //     indexCommonObj.getUserQpBanlance(uid,'ff') ;
                    // }
                    indexCommonObj.getUserQpBanlance(uid,'mg') ;
                    indexCommonObj.getUserQpBanlance(uid,'og') ;
                    indexCommonObj.getUserQpBanlance(uid,'cq') ;
                    indexCommonObj.getUserQpBanlance(uid,'mw') ;
                    indexCommonObj.getUserQpBanlance(uid,'fg') ;
                    // indexCommonObj.getUserQpBanlance(uid,'sc') ;
                    indexCommonObj.getUserQpBanlance(uid,'avia') ;
                    indexCommonObj.getUserQpBanlance(uid,'fire') ;
                    setTimeout(function () {
                        indexCommonObj.getUserAllPlateMoney(uid) ; // ag,彩票 额度，接口太慢
                    },2000)

                }
                thirdMoneyMore = true;
            });
        }

        // 美东时间
        function getAmericaTime(cla) {
            var nowDate = new Date(new Date().getTime() - 43200000),
                nY = nowDate.getFullYear(),
                nM = nowDate.getMonth() + 1,
                nD = nowDate.getDate(),
                nH = nowDate.getHours(),
                nMi = nowDate.getMinutes(),
                nS = nowDate.getSeconds();
            nM = nM < 10 ? '0' + nM : nM;
            nD = nD < 10 ? '0' + nD : nD;
            nH = nH < 10 ? '0' + nH : nH;
            nMi = nMi < 10 ? '0' + nMi : nMi;
            nS = nS < 10 ? '0' + nS : nS;

            var fullTime = nY + '-' + nM + '-' + nD + ' ' + nH + ':' + nMi + ':' + nS;
            $(cla).text(fullTime);
        }

        // 登录
        function memberLoginAction() {
            var loginflage = false ;
            $('.login-submit-btn').on('click',function () {
                if(loginflage){
                    return false ;
                }

                var username= $(".top_username").val();
                var passwd= $(".top_password").val();
                var realname= $(".top_realname").val();
                var title = '' ;
                var actionType = $(this).attr('data-type'); // register 注册
                var introducer = $(".top_introducer").val(); // 介绍人
                var passwd2 =$(".top_password_confirm").val();
                var phone =$(".top_phone").val();

                if (username == "" ) {
                    title = '账号不能为空!';
                    // if(top.body){
                    //     body.body_var.layer.msg(title,{time:alertTime});
                    // }else{
                    //     layer.msg(title,{time:alertTime});
                    // }
                    alert(title);
                    return false;
                }
                if (!isNum(username)){
                    title = '请输入正确的账号！格式：以英文+数字,长度5-15!';
                    // if(top.body){
                    //     body.body_var.layer.msg(title,{time:alertTime});
                    // }else{
                    //     layer.msg(title,{time:alertTime});
                    // }
                    alert(title);
                    return false;
                }
                if (username.length < 5 || username.length > 15) {
                    title = '账号需在5-15位之间!';
                    // if(top.body){
                    //     body.body_var.layer.msg(title,{time:alertTime});
                    // }else{
                    //     layer.msg(title,{time:alertTime});
                    // }
                    alert(title);
                    return false;
                }
                if ( passwd == "" ) {
                    title = '密码不能为空！';
                    // if(top.body){
                    //     body.body_var.layer.msg(title,{time:alertTime});
                    // }else{
                    //     layer.msg(title,{time:alertTime});
                    // }
                    alert(title);
                    return false;
                }
                if (passwd.length < 6 || passwd.length > 15) {
                    title = '密码需在6-15位之间！';
                    // if(top.body){
                    //     body.body_var.layer.msg(title,{time:alertTime});
                    // }else{
                    //     layer.msg(title,{time:alertTime});
                    // }
                    alert(title);
                    return false;
                }
                if($(".top_realname").length>0){
                    if(realname==''){
                        title = '请输入真实姓名';
                        alert(title);
                        return false;
                    }
                }
                try{
                    rememberMeAction();
                }catch (e) {

                }

                var actionurl = "/app/member/login.php";
                var actionData = { // 登录
                    username: username,
                    password: passwd,
                    realname:realname,
                    verifycode: Math.random()
                };
                if(actionType =='register'){ // 注册

                    if ( passwd2 != passwd ) {
                        title = '密码与确认密码不一致！';
                        alert(title);
                        return false;
                    }
                    if(phone=='' || !isMobel(phone)){
                        title = '请输入正确的手机号码！';
                        alert(title);
                        return false;
                    }
                    actionurl = "/app/member/mem_reg_add.php";
                    actionData = {
                        keys: 'add',
                        introducer: introducer,
                        username: username,
                        password: passwd,
                        password2: passwd2,
                        verifycode: Math.random(),
                        phone: phone,
                        thirdLottery: '<?php echo $datajson['agentid'];?>'  // 是否是第三方注册
                    };
                }

                <?php
                    if(LOGIN_IS_VERIFY_CODE) {
                        // 验证通过
                        echo '$TNCODE.show();
                            $TNCODE.onsuccess(function () {';
                    }
                ?>
                loginflage = true;
                    $.ajax({
                        type: 'POST',
                        url: actionurl,
                        data: actionData,
                        dataType: 'json',
                        success: function (res) {
                            if (res) {
                                loginflage = false;

                                // if(top.body){
                                //     body.body_var.layer.msg(res.describe,{time:alertTime});
                                // }else{
                                //     layer.msg(res.describe,{time:alertTime});
                                // }
                                if (res.status == 200) {
                                    window.location.href = '/';
                                } else { // 登录失败
                                    alert(res.describe);
                                    $TNCODE.init();
                                }
                            }

                        },
                        error: function () {
                            loginflage = false;
                            alert('稍后请重试');
                            // if(top.body){
                            //     body.body_var.layer.msg('稍后请重试',{time:alertTime});
                            // }else{
                            //     layer.msg('稍后请重试',{time:alertTime});
                            // }

                        }
                    });

                <?php
                if(LOGIN_IS_VERIFY_CODE) {
                    echo '})';
                }
                ?>

            })

        }

        jackPortNum = setInterval(jackPortNumber,100) ;

        memberLoginAction();
        scrollAuto() ;
        getAmericaTime('.getAmericaTime');
        setInterval(function () {
            getAmericaTime('.getAmericaTime')
        },1000);
        showMoreTags() ;
        alertMemberMsg();

        try {
            indexHoverNav();
        }catch (e) {
        }
        try { // tyc
            rightBottomKf();
            kfRunning();
            showLicense();
            indexTopBanner();
        }catch (e) {
        }
        try { // wnsr
            kfRunning();
        }catch (e) {
        }

    });

</script>
</html>
