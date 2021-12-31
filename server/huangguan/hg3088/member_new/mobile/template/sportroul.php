<?php
session_start();
include_once('../include/config.inc.php');

$tip = isset($_REQUEST['tip'])?$_REQUEST['tip']:'' ; // 用于app 跳转到这个页面 ?tip=app

$uid=$_SESSION["Oid"];
$username = $_SESSION['UserName']; // 拿到用户名

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="/<?php echo TPL_NAME;?>images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <?php
    if(TPL_FILE_NAME=='0086' || TPL_FILE_NAME=='0086dj'){
            echo '<link href="/style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>';
        }
    ?>
    <link href="/<?php echo TPL_NAME;?>style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title class="web-title"></title>
    <style type="text/css">
        <?php
           if((TPL_FILE_NAME=='3366' || TPL_FILE_NAME=='8msport')){
               echo '.header{background:#c1c1c1}';
           }
       ?>

        .header-right{width:14%}
        ul,ol{margin:1em 0;padding:0 0 0 40px}
        h2{display:block;margin:18px 0 20px 0;font-size:14px;font-weight:bold;color:#323232}
        .rule_box{color:#323232;text-align:left;margin:24px 0 16px 0;padding:0 10px;clear:both}
        .rule_box h1{margin-bottom:8px;font-size:16px;font-weight:500;color:#be9553}
        .rules_update{display:block;font-size:12px;color:rgba(75,67,57,0.54);margin-bottom:10px}

        /*div#STATEM.RESUL { height:100vh;}*/
        @viewport { viewport-fit: cover; } /*for iPhoneX*/
        .rule_box {
            margin: 24px 0 16px 0;
            padding:0 10px;
            clear:both;
        }
        .parlay_rule{
            margin: 24px 0 16px 0;
            padding:0 9px;
            clear:both;
        }


        @media only screen and (min-width: 480px) {.parlay_rule { padding:20px; font-size:1.2em;}}
        @media only screen and (min-width: 768px) {.parlay_rule { padding:40px; font-size:1.4em;}}
        .parlay_rule ul{margin:0;padding:0}
        .parlay_rule li{list-style-type:none}
        .parlay_rule strong{font-weight:bold}
        .rule_title{font-size:16px;font-weight:bold;line-height:1.21;margin-bottom:17px}
        .rule_info{font-size:16px;line-height:1.21;margin-bottom:12px}
        .tutorial_page .rule_pic{padding:0;margin-bottom:21px}
        .tutorial_page .rule_info ol{margin:0;padding:0 0 0 18px}
        .tutorial_page .rule_info ol li{list-style-type:disc}
        .rule_pic{text-align:center;padding:9px 0 12px}
        .RULE strong{padding:0 5px}
        .help_info{padding:0 0 17px;font-size:17px;font-family:simsun;text-align:justify;color:#463D2F}
        .help_pic{text-align:center;padding:0 0 18px}
        .LayoutDiv1_contact{clear:both;float:left;margin-left:0;width:100%;display:block;background-color:#503F32;color:white;line-height:48px;margin-bottom:8px}
        .tutorial_page .LayoutDiv1_contact{margin-bottom:17px}
        .LayoutDiv1_contact + .rule_box{padding-top:10px}
        .help_rule{padding:10px 10px 10px;clear:both}
        .backtop{width:100%;height:48px;line-height:48px;text-align:center;font-size:15px;border-radius:3px;color:#5a5249;background-color:#e5e0dc;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
        .backtop:active{background-color:#cfc6be!important}

        /* New Rules */
        .RESUL h1, .RESUL h2, .HELP h1, .HELP h2 {font-size:14px; text-transform:uppercase;}
        .rules_update {
            display:block;
            font-size:12px;
            color: rgba(75, 67, 57, 0.54);
            margin-bottom:10px;
        }
        .rules_update em{
            font-style: normal;
        }
        .RESUL p, .RESUL ul, .HELP p, .HELP ul  { font-size:14px; font-weight:normal; line-height:1.6em;}
        .rule_box li {	list-style-type: decimal;}
        .rule_box ul {list-style: decimal outside!important;}
        .rule_box ul ul {list-style-type: lower-alpha!important;}
        /*FT menu*/
        .css_tr {display: table-row;}
        .rules_group { display:table; width:100%;}
        .rules_btn { display:table-cell; height:40px;line-height:40px; width:33.33% !important; vertical-align:middle; /*width:calc((100% - 30px)/3); */ text-align: center; border-right:15px solid #FFF; border-bottom:15px solid #FFF;background-image: url(/<?php echo TPL_NAME;?>images/home_layer_4.jpg);	background-size: auto 100%;}
        .rules_btn:last-child {border-right:0px none;}
        .rules_btn a { display:block; /*line-height:40px; */color:#FFF; }
        .rules_btn:active {opacity:0.8;}
        .Soccer_Title { line-height:34px; padding-left:10px; color:#FFF; font-weight:normal;
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#492300+0,492300+44,331a00+100 */
            background: #492300; /* Old browsers */
            background: -moz-linear-gradient(top,  #492300 0%, #492300 44%, #331a00 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(top,  #492300 0%,#492300 44%,#331a00 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to bottom,  #492300 0%,#492300 44%,#331a00 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#492300', endColorstr='#331a00',GradientType=0 ); /* IE6-9 */
        }
        .acc_rulesTB{ border-collapse:collapse; border:1px solid #333; background-color:#fff; text-align:center; margin-bottom:18px;}
        .acc_rulesTB th, .acc_rulesTB td{border-bottom:1px solid #333; border-right:1px solid #333; height:30px; font-size:14px; vertical-align:middle;}
        .acc_rulesTB th{ background-color:#ff9700;}
        .RESUL { padding-bottom: 50px !important;}
        .RESUL .popBox { background-image:none; border:none !important; box-shadow:none;}

        :lang(en) .rules_btn a { line-height:1em;  padding:5px;-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
        .RESUL.DARTS .rule_box ul ul, .RESUL.BS .rule_box ul ul {list-style-type:decimal !important;}
        .acc_backUL ul {list-style-type:decimal !important;}

        /* for KO Hide*/
        .hide{display:none;visibility:hidden}
        .RESUL h1,.help_pwdforgot h1{margin-bottom:8px;font-size:16px;font-weight:500;color:#be9553}
        .RESUL h2,.help_pwdforgot h2{display:block;margin:18px 0 20px 0;font-size:14px;font-weight:bold;color:#323232}
        .RESUL p,.help_pwdforgot p{color:#323232;margin:0}
        .RESUL .terms_box ul{padding:0 16px 0 14px}
        .terms_box h1{margin-bottom:20px;color:#323232}
        .terms_box ul ~ h1{margin-top:25px}
        .terms_box>ul>li{margin-bottom:20px;margin-left:14px;line-height:1.21;font-size:14px}
        .help_pwdforgot .recovery_box{padding:0 10px}
        .help_newtv .recovery_box{padding:0 13px 40px}
        .help_newtv .new_img{margin-bottom:18px}
        .qa_help_page .LayoutDiv1_contact{margin-bottom:17px}
        .qa_help_page .help_rule{padding:0 9px}
        .qa_help_page .help_info{margin-bottom:12px;padding:0}

        /*2018 New Game*/
        .NEW_GAME *{font-family: PingFangSC-Regular, sans-serif;}
        .NEW_GAME h3 {
            margin: 0 0 13px;
            font-size: 15px;
            font-weight: 600;
            color: #705636;}
        .NEW_GAME .pnl_link { margin-left: 14px; font-size: 13px;	color: #326da8;}
        [class^="newgame_"]{margin:14px auto 24px; text-align: center; }
        .newgame_01 { width: 289px;	height: 106px; background: url(images/Penalty_01_tw.svg) no-repeat;}
        .newgame_02 { width: 289px;	height: 61px; background: url(images/Penalty_02_tw.svg) no-repeat;}
        .newgame_03 { width: 289px;	height: 62px; background: url(images/Penalty_03_tw.svg) no-repeat; margin-bottom: 58px;}
        .newgame_04 { width: 290px;	height: 58px; background: url(images/Penalty_04_tw.svg) no-repeat;}
        .newgame_05 { width: 290px;	height: 62px; background: url(images/Penalty_05_tw.svg) no-repeat;}

        :lang(zh-CN) .newgame_01 { width: 289px;	height: 106px; background: url(images/Penalty_01_cn.svg) no-repeat;}
        :lang(zh-CN) .newgame_02 { width: 289px;	height: 61px; background: url(images/Penalty_02_cn.svg) no-repeat;}
        :lang(zh-CN) .newgame_03 { width: 289px;	height: 62px; background: url(images/Penalty_03_cn.svg) no-repeat;}
        :lang(zh-CN) .newgame_04 { width: 290px;	height: 58px; background: url(images/Penalty_04_cn.svg) no-repeat;}
        :lang(zh-CN) .newgame_05 { width: 290px;	height: 62px; background: url(images/Penalty_05_cn.svg) no-repeat;}

        :lang(en) .newgame_01 { width: 290px;	height: 106px; background: url(images/Penalty_01_en.svg) no-repeat;}
        :lang(en) .newgame_02 { width: 289px;	height: 61px; background: url(images/Penalty_02_en.svg) no-repeat;}
        :lang(en) .newgame_03 { width: 290px;	height: 62px; background: url(images/Penalty_03_en.svg) no-repeat;}
        :lang(en) .newgame_04 { width: 290px;	height: 56px; background: url(images/Penalty_04_en.svg) no-repeat;}
        :lang(en) .newgame_05 { width: 290px;	height: 62px; background: url(images/Penalty_05_en.svg) no-repeat;}

        .NEW_GAME h4 {font-size: 13px;	color: #443b34; margin: 0;}
        .NEW_GAME .penalty_txt {font-size: 13px;	color: #393939; margin-top: 14px;}

        .text_bold{font-weight: bold!important;}
        .no_list_number>li{
            list-style-type: none;
        }

        ul.acc_lower_alpha  > li {list-style-type: lower-alpha}/*abc*/
        ul.acc_romaUL li {list-style-type: lower-roman;}/*i*/
        .indent_outside {text-indent: -1.2em;}

    </style>
</head>
<body >
<div id="container">

    <!-- 顶部导航栏 -->
    <div class="header header_<?php echo TPL_FILE_NAME;?> sport_header <?php if($tip){echo 'hide-cont';}?>">
        <div class="header_left">
            <a href="sport_main.php" class="back-active sport_back_icon" ></a>

        </div>
        <div class="header-center">
            <a href="sport_main.php?showtype=rb" class="header_live " >滚球</a>
            <a href="sport_main.php?showtype=today" class="header_today " >今日</a>
            <a href="sport_main.php?showtype=future" class="header_early " >早盘</a>
        </div>
        <div class="header-right" >
            <span class="menu_icon" data-num="1" onclick="showDownMenu(this)"> </span>
        </div>
    </div>

    <!-- 中间部分 -->
    <div class="content-center sport-content-center">

        <!-- 下拉菜单 -->
        <div class="subaccountform_menu" style="display: none">

            <div class="menu_user">
                <div class="float_left user_2"><?php echo $username?></div>
                <div class="dropdown_sub_right float_right">
                    RMB
                    <span id="acc_credit" name="acc_credit" class="curr_amount_2"> </span>
                    <span id="curr_reload" class="float_right curr_reload "></span>
                </div>
            </div>

        </div>


        <!-- 导航切换 -->
        <div class="sportNav">

            <div class="sportNav_title">
                <div id="title_gtype" name="title_gtype" class="game_title">体育规则</div>
            </div>


        </div>

        <div class="selection">
            <select name="sportsdropdown" class="sportsdropdown" id="sportsdropdown" onchange="chg_roul(this.options[this.selectedIndex].value);">
                <option value="/template/roul/sport_rule.html" selected="selected">一般体育说明</option>
                <option value="/template/roul/outright.html">冠军</option>
                <option value="/template/roul/parlays-multiples.html">综合过关</option>
                <option value="/template/roul/football.html">足球</option>
                <option value="/template/roul/basketball.html">篮球</option>
                <option value="/template/roul/tennis.html">网球</option>
                <option value="/template/roul/volleyball.html">排球</option>
                <option value="/template/roul/badminton.html">羽毛球</option>
                <option value="/template/roul/table-tennis.html">乒乓球</option>
                <option value="/template/roul/baseball.html">棒球</option>
                <option value="/template/roul/snooker_ruo.html">斯诺克 / 台球</option>
                <option value="/template/roul/roul_nf.html">美式足球</option>
                <option value="/template/roul/archery.html">射箭和射击</option>
                <option value="/template/roul/athletic.html">田径</option>
                <option value="/template/roul/aussie.html">澳式足球</option>
                <option value="/template/roul/beach-soccer.html">沙滩足球</option>
                <option value="/template/roul/beach-volleyball.html">沙滩排球</option>
                <option value="/template/roul/boxing.html">拳击 / 搏斗</option>
                <option value="/template/roul/cricket.html">板球</option>
                <option value="/template/roul/cycling.html">自行车</option>
                <option value="/template/roul/darts.html">飞镖</option>
                <option value="/template/roul/e-sports.html">电子竞技</option>
                <option value="/template/roul/field-hockey.html">曲棍球</option>
                <!--<option value="/template/roul/financial-bets.html">金融投注</option>-->
                <option value="/template/roul/futsal.html">室内足球</option>
                <option value="/template/roul/golf.html">高尔夫</option>
                <option value="/template/roul/gymnastics.html">体操</option>
                <option value="/template/roul/handball.html">手球</option>
                <option value="/template/roul/ice-hockey.html">冰球</option>
                <option value="/template/roul/judo.html">柔道、摔交、跆拳道</option>
                <option value="/template/roul/long-field-hockey.html">长曲棍球</option>
                <option value="/template/roul/all-medal-betting.html">体育 / 奖章投注</option>
                <!--<option value="/template/roul/major-league-lacrosse.html">袋棍球</option>-->
                <!--<option value="/template/roul/medal-betting.html">奖牌投注</option>-->
                <option value="/template/roul/motor-sports.html">赛车</option>
                <option value="/template/roul/olympics.html">奥林匹克或相关事件投注</option>
                <option value="/template/roul/rowing.html">赛艇和皮划艇</option>
                <option value="/template/roul/rugby-league.html">橄榄球联盟</option>
                <!--<option value="/template/roul/snooker.html">桌球/9号球巡回赛</option>-->
                <option value="/template/roul/softball.html">垒球</option>
               <!-- <option value="/template/roul/swimming.html">游泳</option>-->
                <!--<option value="/template/roul/taekwondo.html">跆拳道</option>-->
                <option value="/template/roul/tamp.html">三项全能和现代五项运</option>
                <!--<option value="/template/roul/ultimate-fighting.html">终极格斗锦标赛（综合格斗大赛）</option>-->
                <option value="/template/roul/water-polo.html">水球</option>
                <option value="/template/roul/weightlifting.html">举重</option>
                <!--<option value="/template/roul/wrestling.html">角力</option>-->
                <option value="/template/roul/wintersports.html">冬季运动&冬季奥运会 / 比赛</option>
            </select>
        </div>

        <!-- 内容区域 -->
        <div id="rule_box" class="rule_box">

        </div>

    </div>


    <div class="clear"></div>

    <!-- 底部footer -->
    <div id="footer" class="sport_footer <?php if($tip){echo 'hide-cont';}?>">

    </div>


</div>

<script type="text/javascript" src="../js/zepto.min.js"></script>
<script type="text/javascript" src="../js/animate.js"></script>
<script type="text/javascript" src="../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../js/main.js?v=<?php echo AUTOVER; ?>"></script>


<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    $('#acc_credit').html(usermon) ;

    setFooterAction(uid);  // 在 addServerUrl 前调用

    // 下拉显示与隐藏
    function showDownMenu(obj) {
        var num = $(obj).data('num') ;
        if(num=='1'){
            $('.subaccountform_menu').show();
            $(obj).attr('data-num','2').addClass('menu_icon_active');
        }else{
            $('.subaccountform_menu').hide();
            $(obj).attr('data-num','1').removeClass('menu_icon_active');
        }

    }

    function chg_roul(url){
        $('#rule_box').load(url,function () {

        }) ;
    }
    chg_roul('/template/roul/sport_rule.html') ; // 默认体育规则页面


</script>


</body>
</html>