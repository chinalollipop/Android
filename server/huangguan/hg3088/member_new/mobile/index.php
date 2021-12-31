<?php
session_start();
include_once('include/config.inc.php');
include ("include/address.mem.php");

    limitIpSee();

	if($_REQUEST['from']=="CP"){//cp无session跳回清楚session
		session_destroy();
		Header("Location: index.php");
	}

// 避免再次加载文件，连接数据库
if(!$_SESSION['AUTOVER_SESSION'] || $_SESSION['AUTOVER_SESSION'] !=AUTOVER) { // 避免重复设置
    $_SESSION['AUTOVER_SESSION'] = AUTOVER;
    $_SESSION['COMPANY_NAME_SESSION'] = COMPANY_NAME;
    $_SESSION['TPL_NAME_SESSION'] = TPL_NAME;
    $_SESSION['AGENT_LOGIN_URL'] = returnAgentUrl(); // 代理登录链接
    $_SESSION['HTTPS_HEAD_SESSION'] = HTTPS_HEAD;
}
    $todaydate=date('Y-m-d');

	$agenttip = isset($_REQUEST['agenttip'] )?$_REQUEST['agenttip']:'' ; // 从代理商推广域名
    $intr= isset($_REQUEST['intr'] )?$_REQUEST['intr']:'' ; // 从代理推广码
    $maxintr= isset($_REQUEST['Intr'] )?$_REQUEST['Intr']:'' ; // 从代理推广码
    if($intr){
        $ag_intr = $intr ;
    }else{
        $ag_intr = $maxintr ;
    }
    $_SESSION['agents_name'] = $ag_intr ;
//    if($_SESSION['agents_name'] || $_SESSION['agents_name'] !=''){ // 有代理账号,直接跳转到注册页面，不跳到注册页面了
//        Header("Location: reg.php");
//    }

    $host = getMainHost();
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    // 不需要跳转到 PC
//    if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
//        header('Location: '.HTTPS_HEAD.'://'.$host);
//        exit;
//    }

    $weburl= HTTPS_HEAD.'://'.$host.'?topc=yes'; // 电脑版网址
    $username = $_SESSION['UserName']; // 拿到用户名
    $oid = $_SESSION['Oid']; // 拿到oid
    $hgid = $_SESSION['userid'] ;
    $hgpwd = $_SESSION['password'];
    $cpUrl=HTTPS_HEAD."://".CP_MOBILE_URL.'.'.$host."/";

// 会员弹窗信息
$membermessage = getMemberMessage($username,'0'); // 系统短信
$flage = ($_SESSION['test_flag']==1) ? 'test' : ''; //棋牌试玩
$apptip = ($_REQUEST['tip']== 'app') ? 'app' : ''; //棋牌头部返回按钮

if(TPL_NAME=='views/0086/' || TPL_NAME=='views/6668/'|| TPL_NAME=='views/0086dj/'){
    $_SESSION['cpUrl'] = $cpUrl ;// 其他页面需要用
    $linkcss = '<link href="style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>';
    $topicon = '<i class="fa fa-user-o"></i>';
    if(TPL_NAME=='views/6668/'){
        $linkcss = '';
        $topicon = '<i class="index_fa fa-user-o"></i>';
    }
    $cpthird = false; // 第三方彩票 或者体育彩票
}else{
    $_SESSION['cpUrl'] = 'lotteryThird.php?type=1' ; // 彩票链接,其他页面需要用
    $linkcss = '';
    $topicon = '<i class="index_fa fa-user-o"></i>';
    $cpthird = true;
}
if(TPL_NAME=='views/jinsha/'){ // 金沙
    $topapp = '<a href="'.TPL_NAME.'appdownload.php" class=" app_download" style="display: inline-block;float: left;margin-left: 2%;"> APP下载 </a>';
}
if(TPL_NAME=='views/suncity/' || TPL_NAME=='views/wnsr/'){
    $topapp = '<a href="'.TPL_NAME.'appdownload.php" class="app_download" > <i class="index_fa fa-app"></i> <p> APP </p></a>';
}

?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="apple-mobile-web-app-title" content="<?php echo COMPANY_NAME;?>">
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="<?php echo TPL_NAME;?>images/favicon.ico" type="image/x-icon"/>
        <?php
           echo $linkcss;
        ?>
        <link href="style/swiper-3.4.2.min.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="<?php echo TPL_NAME;?>style/iphone.css?v=<?php echo AUTOVER;?>" rel="stylesheet" type="text/css" media="screen"/>
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo TPL_NAME;?>images/add-logo.png"> <!-- 添加到桌面 -->
        <title class="web-title"><?php echo COMPANY_NAME;?></title>
    </head>
    <body >
    <?php
        // 推广人
        if($ag_intr){
            echo "<script> localStorage.setItem('agent_account','$ag_intr'); </script>";
        }
        if($username){ // 设置 session
            echo "<script> localStorage.setItem('username','$username'); localStorage.setItem('myoid','$oid'); </script>";

        }
    ?>
         <div class="home_container">
             <?php
                 if( !(TPL_NAME=='views/jinsha/' || TPL_NAME=='views/0086dj/')){
             ?>

             <!-- APP 下载提示 -->
             <div class="app_tip">
                 <span class="app_tip_logo <?php echo (TPL_NAME=='views/0086/'?'icon':'');?>"></span>
                 <div class="title">
                     <p> <?php echo COMPANY_NAME;?> APP</p>
                     <p> 轻便下载，安全使用</p>
                 </div>
                 <a href="javascript:;" class="linear-color-1 download_btn" target="_blank">
                     <span class="icon"></span>
                     <span >立即下载</span>
                 </a>
                 <a href="javascript:;" class="app_close"></a>
             </div>
             <?php
             }
             ?>
             <!-- 顶部导航栏 -->
            <div class="header">
                <?php echo $topapp; ?>
               <!-- <span class="left-icon to-slide"></span>-->
               <!-- <span class="web-title title"></span>-->
                <span class="header_logo"></span>
                <div class="header-right" >
                    <?php if($username=='' || !$username){ ?>
                        <!-- 登录前-->
                        <a href="<?php echo TPL_NAME;?>login.php" >
                            <?php
                                echo $topicon;
                            ?>
                            <p>登入/注册</p>
                        </a>

                    <?php }else{ ?>
                        <!--登录后-->
                        <i class="fa fa-database"></i>
                        <p class="hg_money after_login" >0</p>

                    <?php } ?>

                </div>
            </div>
             <!-- 中间部分 -->
             <?php
                 if(!(TPL_NAME =='views/6668/' || TPL_NAME =='views/8msport/')){
                     echo '<div class="content-center">';
                 }
             ?>

                 <?php include TPL_NAME.'middle_index.php'; ?>

             <?php
                 if(!(TPL_NAME =='views/6668/' || TPL_NAME =='views/8msport/')){
                     echo ' </div>';
                 }
             ?>

            <!-- 底部footer -->
            <div id="footer">

            </div>


      </div>

    <!-- 皇冠体育联合登陆 -->
    <!--<iframe name="sport_login_url" id="sport_login_url" scrolling="NO" noresize src="" style="display: none;"></iframe>-->


	<script type="text/javascript" src="js/zepto.min.js"></script>
    <script type="text/javascript" src="js/animate.js"></script>
    <script type="text/javascript" src="js/zepto.animate.alias.js"></script>
    <script type="text/javascript" src="js/swiper-3.4.2.jquery.min.js"></script>
    <script type="text/javascript" src="js/main.js?v=<?php echo AUTOVER;?>"></script>

    <script type="text/javascript">
        var tplName='<?php echo TPL_NAME;?>';
        var tplfilename='<?php echo TPL_FILE_NAME;?>';
        var companyname='<?php echo COMPANY_NAME;?>';
        var myusername = <?php echo  '\''.$username.'\'' ?> ;
        var myoid = <?php echo  '\''.$oid.'\'' ?> ;
        var msgcou = '<?php echo $membermessage['mcou']?>' ;
        var memmsg = '<?php echo $membermessage['mem_message']?>' ;
        var agent_name = '<?php echo $ag_intr?>';
        var bannercachetime = <?php echo BANNERCACHETIME ;?> ;
        var cpthird = '<?php echo $cpthird;?>';
        var webConfig = '<?php echo str_replace("\\/", "/", json_encode(getSysConfig(), JSON_UNESCAPED_UNICODE));?>';　// 基础设置
        var configbase = $.parseJSON(webConfig);
        if(tplfilename=='wnsr'){
            configbase.agents_service_qq = configbase.vns_agents_service_qq;
            configbase.service_qq = configbase.vns_service_qq;
            configbase.service_meiqia = configbase.vns_service_meiqia;
            configbase.service_email = configbase.vns_service_email;
            configbase.service_phone_24 = configbase.vns_service_phone_24;
        }
        localStorage.setItem('webconfigbase',JSON.stringify(configbase));
       // console.log(configbase)

        setCookieAction('tplname',tplName) ;
        setCookieAction('companyname',companyname) ;
        if(agent_name){ // 是否有推广账号
            setCookieAction('agent_account',agent_name);
        }
        if(myoid){ // 已经登录账户
            get_cp_blance('.hg_money','') ;
        }

        var pathindex = window.location.pathname.replace('/','') ;
        var paths = pathindex.split('.') ;
        //console.log(window.location) ;
        //console.log(paths) ;
        if(paths[0] == 'main' || paths[0] == ''){ // 判断是否在首页
            localStorage.setItem('footnav','to-home') ;
        }
        setFooterAction(myoid);

        $(document).ready(function () {

            var thirdCpUrlNum = localStorage.getItem('third_cp_url_num') ;
            if(myoid && (!thirdCpUrlNum || thirdCpUrlNum ==1 ) && cpthird){ // 三方彩票
                getThirdLotteryAction('login');
            }

            var sport_url_num = localStorage.getItem('sport_url_num') ;
            // if(myoid && (!sport_url_num || sport_url_num ==1 )){
            //     loginSportAction();
            // }
            if(!cpthird){ // 体育彩票
               doLotteryLogin();
            }

            getBannerAction() ;
            if(tplfilename !='0086dj'){
                applyBanner();
            }
            var getmsg = getCookieAction('mymsg') ; // 获取信息cookie

        // alert(window.screen.width) ;  // 设备宽度
            if(msgcou>0 && getmsg <1){ // 有会员短信,只弹出一次
                if(myusername && msgcou>0){
                    setCookieAction('mymsg',3,1) ; // cookie 有效期 1天
                    setPublicPop(memmsg,10000);
                }

            }
            getPageMaintenance('mobile'); // 判断是否系统维护
            changeGameTag();

            if(tplfilename =='6668' || tplfilename =='8msport'){ // 设置首页高度
                var indexGameflag = true; // 是否滚动
                var gameNum = [0,1,3,5,6,10]; // 每种分类对应的游戏数量
                var baseNum = 12; // 基数
                if(tplfilename =='8msport'){
                    gameNum = [0,2,4,6,8,12]; // 每种分类对应的游戏数量
                    baseNum = 4;
                }
                $('.home_container').css({'height':window.innerHeight});
                setGameSwiper();
                addIndexScrollTop();
            }

            // 首页游戏切换
            function changeGameTag() {
                $('.am-tabs-default-bar-content .am-tabs-default-bar-tab').click(function () {
                    var i = $(this).index();
                    //console.log(i)
                    $(this).addClass('active').siblings().removeClass('active');
                    $('.am-tabs-content-wrap .am-tabs-pane-wrap').eq(i).newshow(300).siblings().newhide(300);

                })
            }

            // 首页游戏滚动轮播配置
            function setGameSwiper() {
                var $game_nav_on = $('.game_nav_on');
                //给每个页码绑定跳转的事件
                $('.swiper-pagination-game').on('click','li',function(){
                    indexGameflag = false;
                    var index = $(this).index();
                    var gameDivH = $('.Menual').height(); // 每个游戏标签高度
                   // console.log(gameDivH)
                    var scrTop = 0;
                    if(index==0 || index==1){ // 体育和真人
                        scrTop = baseNum*gameNum[index] + gameNum[index]*gameDivH;
                    }else{
                        scrTop = baseNum*gameNum[index] + (gameNum[index]+1)*gameDivH;
                    }

                    $(this).addClass('active').siblings().removeClass('active');
                    var on_left = parseInt($('.swiper-pagination-game').find('.active').position().left)+4;
                    if(index>0){
                        $game_nav_on.css({'transform':'translate3d('+on_left+'px, 0px, 0px)'});
                    }else{
                        $game_nav_on.css({'transform':'translate3d(0px, 0px, 0px)'});
                    }
                   // console.log(scrTop)
                   // $('.gameListAll').css({'transform':'translate3d(0px, -'+scrTop+'px, 0px)'});
                    $('.middle_content').scrollTop(scrTop);

                });
            }

            // 监听滚动
            function addIndexScrollTop() {
                var gameDivH = $('.Menual').height(); // 每个游戏标签高度
                var $gameLi = $('.swiper-pagination-game li');
                var $game_nav_on = $('.game_nav_on');
                var $middle_content = document.querySelector('.middle_content');
                $middle_content.addEventListener('touchstart', handler, { passive: false });
                $middle_content.addEventListener('scroll', handler, { passive: false }); // 滚动监听

                function handler(e) {
                    switch (e.type) {
                        case 'touchstart':
                            indexGameflag = true;
                            break;
                        case 'scroll':
                            if (!indexGameflag) {
                               return;
                            }
                            $('.gameListAll').removeAttr('style');
                            var scrollH = this.scrollTop ; // 滚动高度
                            //console.log(scrollH)
                            for(var i=0;i<gameNum.length;i++){
                                if(i==0 || i==1){ // 真人
                                    if(scrollH > baseNum*gameNum[i] + gameNum[i]*gameDivH ){
                                        $gameLi.eq(i).addClass('active').siblings().removeClass('active');
                                    }
                                }else{
                                    if(scrollH > baseNum*gameNum[i] + (gameNum[i]+1)*gameDivH ){
                                        $gameLi.eq(i).addClass('active').siblings().removeClass('active');
                                    }
                                }

                            }
                            var on_left = parseInt($('.swiper-pagination-game').find('.active').position().left)+4;
                            if($('.swiper-pagination-game').find('.active').index() ==0){
                                $game_nav_on.css({'transform':'translate3d(0px, 0px, 0px)'});
                            }else{
                                $game_nav_on.css({'transform':'translate3d('+on_left+'px, 0px, 0px)'});
                            }
                            break;
                    }

                }

            }


        }) ;

        // 体育彩票登录处理，非三方彩票
        function doLotteryLogin() {
            var cpUrlArr = JSON.parse(localStorage.getItem('cpUrlArr')) || new Array() ;
            var newcpUrlArr = new Array() ;
            var str = '';
            if(cpUrlArr.cp_url_num==1){ // 首次加载
                newcpUrlArr = {
                    cp_url:cpUrlArr.cp_url,
                    cp_url_num:2
                }
                localStorage.setItem('cpUrlArr',JSON.stringify(newcpUrlArr)) ; // 彩票登录地址
                str +='<iframe name="cp_login_url" id="cp_login_url" scrolling="NO" noresize src="'+ cpUrlArr.cp_url +'" style="display: none;"></iframe>';
                $('body').append(str);
                //$('#cp_login_url').attr('src',cpUrlArr.cp_url) ;
            }
        }


        // 获取轮播图
        function getBannerAction() {
            var bannerarr = [] ;
            var curtime = new Date().getTime(); // 获取当前时间
            var localbannerarr = JSON.parse(localStorage.getItem('bannerArry')) ;
            if(localbannerarr){ // 防止初始情况
                var timeout = curtime-localbannerarr.timer
                var curminute = Math.floor(timeout/1000/60) ; // 已过去多少分钟
                // console.log(curminute) ;
                if(curminute < bannercachetime){ // 轮播图1小时缓存
                    return false ;
                }
            }

            localStorage.removeItem('bannerArry'); // 设置前删除

            $.ajax({
               // url:'/banner.php',
                url:'/api/indexBannerApi.php',
                type:'POST',
                async:false ,
                dataType:'json',
                data: {action:'mobile'} ,
                success:function(res){
                    if(res.status==200){ // 成功
                        bannerarr = res.data ;
                        setLocalStorage('bannerArry', bannerarr) ;
                    }

                },
                error:function () {

                }

        });

            return bannerarr ;

        }

        // 轮播图渲染
        function applyBanner(){
            var localbannerarr = JSON.parse(localStorage.getItem('bannerArry')) ;
            // console.log(localbannerarr) ;
            var str = '';
            if(localbannerarr.val){
                for(var i=0;i<localbannerarr.val.length;i++){
                    str += ' <div class="swiper-slide">' ;
                            if(localbannerarr.val[i].name.indexOf('promo')>=0){ // 跳转到优惠活动页面
                                str += '<a href="'+tplName+'promo.php?prokey='+(localbannerarr.val[i].name.split('?')[1]?(localbannerarr.val[i].name.split('?')[1]+'#promos_id_'+localbannerarr.val[i].name.split('?')[1]):'')+'"><img src="'+ localbannerarr.val[i].img_path+'" alt=""></a>' ;
                            }else if(localbannerarr.val[i].name.indexOf('lives_upgraded')>=0){ // 升级
                                str += '<a href="'+tplName+'middle_lives_upgraded.php?game_Type='+(localbannerarr.val[i].name.split('?')[1]?(localbannerarr.val[i].name.split('?')[1]):'')+'"><img src="'+ localbannerarr.val[i].img_path+'" alt=""></a>' ;
                            }else{
                                str += '<a href="javascript:;"><img src="'+ localbannerarr.val[i].img_path+'" alt=""></a>' ;
                            }

                    str += '</div>' ;
                }
            }
            $('.swiper-container .swiper-wrapper').html(str) ;
            bannerSwiper();
        }

        // 代理商推广域名需要跳转到配置的主域名登录 开始
        var agenttip = '<?php echo $agenttip ?>' ;
        var agentlogintime = localStorage.getItem('agentlogintime') || 1 ;

        if(agenttip==1 && agentlogintime==1){
            doLoginAvtion() ;
        }
        // 会员登录
        function doLoginAvtion() {
            var usename = removeAllSpace('<?php echo $_REQUEST["username"]?>') ;
            var passwd = removeAllSpace('<?php echo $_REQUEST["passwd"]?>') ;
            if (usename == "") {
                alertComing('请输入帐号') ;
                return false;
            } else if (passwd == "") {
                alertComing('请输入密码') ;
                return false;
            }

            var ajaxurl = '/login_api.php';
            var senddata ={
                username: usename ,
                passwd: passwd ,
                agenttip: agenttip
            };
            $.ajax({
                url:  ajaxurl ,
                type: 'POST',
                dataType: 'json',
                data: senddata ,
                success:function(res){
                    if(res.status){ // 有结果返回
                        //  {"status":"200","describe":"登录成功!","timestamp":"20180819044600","data":{"UserName":"jack001","Agents":"dleden001","LoginTime":"2018-08-19 04:46:00","birthday":"1986-08-01","Money":"20660.0257","Phone":"13688988898","test_flag":"0","Oid":"03ae9981e16d7f254be9ra6","Alias":"发发发","BindCard_Flag":"1","BetMinMoney":"20","BetMaxMoney":"5000000"},"sign":"5227afe7e560e3a2676d4da07c609193"}
                        if(res.status=='200'){ // 登录成功
                            localStorage.setItem('agentlogintime',2); // 代理商域名登录记录次数
                            setCookieAction('member_money',res.data.Money,1) ; // 用户金额，cookie 有效期 1天
                            loginLotteryAction() ;

                        }
                    }
                }
            });
        }

        // 代理商推广域名需要跳转到配置的主域名登录 结束

        // 皇冠体育登录
        function loginSportAction() {
            $.ajax({
                type : 'POST',
                url : '/sportcenter/sport_api.php',
                data : {'action':'cm'},
                dataType : 'json',
                success:function(res) {
                    if(res.status == 200){ // 登录成功
                        // todo
                        if(res.data.url !== undefined) {
                            localStorage.setItem('sport_url_num', '2'); // 记录体育登录次数
                            $('#sport_login_url').attr('src',res.data.url) ;
                        }
                    }else{
                        setPublicPop(res.describe);
                    }
                }
            });
        }

        /*
        *  获取彩票登录链接
        *  type : login 登录
        *
        * */
        function getThirdLotteryAction(type){
            var url = '/api/thirdLotteryApi.php';
            $.ajax({
                type: 'POST',
                url: url,
                data: {actype:type},
                dataType: 'json',
                success: function (res) {
                    if(res){

                    }
                }
            });
        }

        // app 下载处理，判断客户端类型
        function judgeUserAgent() {
            var u = navigator.userAgent;
            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
            var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
            var $download_btn = $('.download_btn');
            var $app_tip = $('.app_tip');

            var andurl = configbase.download_android_exe;
            var iosurl = configbase.download_ios_exe;
            if(tplName=='views/wnsr/'){
                andurl = configbase.vns_download_android_exe;
                iosurl = configbase.vns_download_ios_exe;
            }
            if(isAndroid){
                $app_tip.find('.icon').addClass('and');
                $download_btn.attr('href',andurl);
            }
            if(isiOS){
                $app_tip.find('.icon').addClass('ios');
                $download_btn.attr('href',iosurl);
            }
            var showapp = sessionStorage.getItem('appDownLoadTip');
            if(showapp==null || showapp==0){
                $app_tip.show()
            }else{
                $app_tip.hide();
            }

            // 关闭
            $('.app_close').on('click',function () {
                $app_tip.hide();
                sessionStorage.setItem('appDownLoadTip',1);
            })

        }

        function bannerSwiper() {
            // 滚动轮播配置
            var mySwiper = new Swiper ('.swiper-container', {
                direction: 'horizontal',
                autoHeight: true,
                loop: true,
                autoplay : 5000,
                autoplayDisableOnInteraction:false, // 手动切换后继续自动切换
                // 如果需要分页器
                pagination: '.swiper-pagination'
            });
        }

        // 获取首页游戏数量
        function getIndexGameNum() {
            $.ajax({
                type : 'POST',
                url : '/api/indexGameNumApi.php',
                dataType : 'json',
                success:function(res) {
                    if(res.status == '200'){
                        $('.hgSportNum').text(res.data.hgSportNum);
                        $('.agLiveNum').text(res.data.agLiveNum);
                        $('.ogLiveNum').text(res.data.ogLiveNum);
                        $('.bbinLiveNum').text(res.data.bbinLiveNum);
                        $('.fydjNum').text(res.data.fydjNum);
                        $('.lhdjNum').text(res.data.lhdjNum);
                        $('.hgChessNum').text(res.data.hgChessNum);
                        $('.vgChessNum').text(res.data.vgChessNum);
                        $('.lyChessNum').text(res.data.lyChessNum);
                        $('.kyChessNum').text(res.data.kyChessNum);
                        $('.klChessNum').text(res.data.klChessNum);
                        $('.lotteryChessNum').text(res.data.lotteryChessNum);
                    }
                }
            });
        }

        // 电竞首页切换
        function djChangeTag() {
            $('.dj_top').on('click','a',function () {
                $(this).addClass('active').siblings().removeClass('active');
            });
            if(myoid){
                $('.lhdj_btn').click();
            }

        }
        if(tplfilename=='0086dj'){
            bannerSwiper();
            djChangeTag();
        }
        getIndexGameNum();
        judgeUserAgent();


    </script>


    </body>
</html>