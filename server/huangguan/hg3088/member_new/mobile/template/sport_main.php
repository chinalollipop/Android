<?php
session_start();
include_once('../include/config.inc.php');
$uid = $_SESSION['Oid'];
if(!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "") { // 已登录
    echo "<script>alert('请重新登录!');window.location.href='/".TPL_NAME."login.php';</script>";
    exit;
}
$showtype = isset($_REQUEST['showtype'])?$_REQUEST['showtype']:'' ;

$useragent=$_SERVER['HTTP_USER_AGENT'];

$dcRedisObj = new Ciredis('datacenter');
$FT_Running_Num = $dcRedisObj->getSimpleOne("FT_Running_Num");
$FT_Today_Num = $dcRedisObj->getSimpleOne("FT_Today_Num");
$FT_Future_Num = $dcRedisObj->getSimpleOne("FT_Future_Num");
$BK_Running_Num = $dcRedisObj->getSimpleOne("BK_Running_Num");
$BK_Today_Num = $dcRedisObj->getSimpleOne("BK_Today_Num");
$BK_Future_Num = $dcRedisObj->getSimpleOne("BK_Future_Num");

$username = $_SESSION['UserName']; // 拿到用户名

if(strpos($_SESSION['gameSwitch'],'|')>0){
	$gameArr=explode('|',$_SESSION['gameSwitch']);	
}else{
      if(strlen($_SESSION['gameSwitch'])>0){
      	$gameArr[]=$_SESSION['gameSwitch'];	
      }else{
      	$gameArr=array();	
      }
}

if(TPL_FILE_NAME=='3366' || TPL_FILE_NAME=='8msport'){
    $topStr='<a href="/" class="back-active icon-back" >&nbsp;&nbsp;返回</a>';
}else{
    $topStr='<a href="/" class="back-active sport_back_icon" ></a>';
}

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
    if(TPL_FILE_NAME=='3366'){
        echo '.back-active{position: relative;}
             .header-right{line-height: 3.57rem;}';
    }
    if((TPL_FILE_NAME=='3366' || TPL_FILE_NAME=='8msport')){
        echo '.selection_HK_box a{color: #000 !important;}
             .sport_rule{border-color: #fff !important;}';
    }

    ?>

    .selection_HK{display:inline-block;width:48.5%;height:3.57rem;line-height:3.57rem;clear:both;position:relative}
    .selection_HK:before{content:"";position:absolute;top:1rem;left:53px;display:inline-block;width:24px;height:24px;opacity:0.8;background:url(images/arrow_godown.svg) no-repeat center center;-webkit-transform:rotate(-180deg);-moz-transform:rotate(-180deg);transform:rotate(-180deg)}
    .selection_HK_box a{display: inline-block;width: 48%;height: 3.57rem;line-height: 3.57rem;color:rgba(255,255,255,0.72);padding-left: 1%;}
    .sport_rule {float: right; border-left: 1px solid #726156;}
    .HK_dropdown option{color: #818181;}
    /* Firefox */
    @-moz-document url-prefix(){
        .selection_HK{
            background:transparent url(images/arrow_godown.svg) no-repeat 55%;
            width: 100%;height: 48px;
        }
        .selection_HK::before{
            margin-top: 16px;
        }
        .HK_dropdown{
            background:transparent ; color:#C00;
            width: 105%;
        }
    }
    .HK_dropdown {
        display: block;
        width: 100%;
        height: 3.5rem;
        padding-left: 3%;
        border: none;
        color: rgba(255,255,255,0.72);
        background-color: transparent;
        -webkit-appearance: none;
        -moz-appearance:none;
    }
    .HK_dropdown .HK_dropdown{
        padding-left: 0;
    }
    /* For IE */
    @media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
        select , .HK_dropdown {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance:none;
            -ms-appearance: none;

        }
    }
    /* IE11 selector Hack*/
    _:-ms-fullscreen, :root .selector {background:transparent url(images/arrow_godown.svg) no-repeat 55%;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance:none;
        -ms-appearance: none;
    }
</style>
</head>
<body >
<div id="container">

    <!-- 顶部导航栏 -->
    <div class="header sport_header">
        <div class="header_left">
           <?php echo $topStr;?>
            <div class="wel">
                <?php if($_SESSION['Agents'] == 'demoguest'){?>
                您好!<p id="acc_username" class="acc_username">试玩玩家</p>
                <?php }else{?>
                您好<p id="acc_username" class="acc_username"><?php echo $username?></p>
                <?php } ?>
            </div>
        </div>
        <div class="header-right">
                <span class="rmb_color">RMB</span> <p class="hg_money mon_color after_login"> </p>
        </div>
    </div>

    <!-- 中间部分 -->
    <div class="content-center sport-content-center">
        <div class="tab">
            <div class="sport-nav">
                <a href="javascript:;" data-action="1" class="item " style="display: <?php if($showtype=='rb' || $showtype==''){ echo 'block';}else{echo 'none';}?>">
                    <span>滚球赛事</span>
                </a>
                <div class="sport_expand sport_expand_1" style="display: <?php if($showtype=='rb'){ echo 'block';}?>">
                    <ul>
                        <li class="football2">
                            <a href="sport.php?gtype=FT&showtype=RB&sorttype=league">
                                <span class="football-r-icon"></span>
                                <span class="text">足球(<?php echo $FT_Running_Num;?>)</span>

                            </a>
                        </li>
  						<?php if(!in_array('BK',$gameArr)){ ?>
                        <li class="basketball2_rb">
                            <a href="sport.php?gtype=BK&showtype=RB&sorttype=league">
                                <span class="basketball-r-icon"></span>
                                <span class="text">篮球/美式足球(<?php echo $BK_Running_Num;?>)</span>

                            </a>
                        </li>
                        <?php }?>
                      <!--  <li class="tnball1">
                            <a href="javascript:;" onclick="setPublicPop('敬请期待!')">
                                <span class="tnball-icon"></span>
                                <span class="text">网球(0)</span>

                            </a>
                        </li>
                        <li class="vbball1">
                            <a href="javascript:;" onclick="setPublicPop('敬请期待!')">
                                <span class="vbball-icon"></span>
                                <span class="text">排球(0)</span>

                            </a>
                        </li>-->
                    </ul>
                </div>
                <a href="javascript:;" data-action="2" class="item today_item" style="display: <?php if($showtype=='today' || $showtype==''){ echo 'block';}else{echo 'none';}?>" >
                    <span>今日赛事</span>
                </a>
                <div class="sport_expand sport_expand_2" style="display: <?php if($showtype=='today' || $showtype==''){ echo 'block';}?>" >
                    <ul>
                        <li class="football1">
                            <a href="sport.php?gtype=FT&showtype=FT&sorttype=league">
                                <span class="football-icon"></span>
                                <span class="text">足球(<?php echo $FT_Today_Num;?>)</span>

                            </a>
                        </li>
                        <?php if(!in_array('BK',$gameArr)){ ?>
                        <li class="basketball1">
                            <a href="sport.php?gtype=BK&showtype=FT&sorttype=league">
                                <span class="basketball-icon"></span>
                                <span class="text">篮球/美式足球(<?php echo $BK_Today_Num;?>)</span>

                            </a>
                        </li>
                        <?php } ?>
                      <!--  <li class="tnball1">
                            <a href="javascript:;" onclick="setPublicPop('敬请期待!')">
                                <span class="tnball-icon"></span>
                                <span class="text">网球(0)</span>

                            </a>
                        </li>
                        <li class="vbball1">
                            <a href="javascript:;" onclick="setPublicPop('敬请期待!')">
                                <span class="vbball-icon"></span>
                                <span class="text">排球(0)</span>
                            </a>
                        </li>-->
                    </ul>
                </div>
                <a href="javascript:;" data-action="3" class="item " style="display: <?php if($showtype=='future' || $showtype==''){ echo 'block';}else{echo 'none';}?>">
                    <span>早盘赛事</span>
                </a>
                <div class="sport_expand sport_expand_3" style="display: <?php if($showtype=='future'){ echo 'block';}?>">
                    <ul>
                        <li class="football1">
                            <a href="sport.php?gtype=FT&showtype=FU&sorttype=league">
                                <span class="football-icon"></span>
                                <span class="text">足球(<?php echo $FT_Future_Num;?>)</span>
                            </a>
                        </li>
                        <?php if(!in_array('BK',$gameArr)){ ?>
                        <li class="basketball1">
                            <a href="sport.php?gtype=BK&showtype=FU&sorttype=league">
                                <span class="basketball-icon"></span>
                                <span class="text">篮球/美式足球(<?php echo $BK_Future_Num;?>)</span>

                            </a>
                        </li>
                        <?php } ?>
                      <!--  <li class="tnball1">
                            <a href="javascript:;" onclick="setPublicPop('敬请期待!')">
                                <span class="tnball-icon"></span>
                                <span class="text">网球(0)</span>

                            </a>
                        </li>
                        <li class="vbball1">
                            <a href="javascript:;" onclick="setPublicPop('敬请期待!')">
                                <span class="vbball-icon"></span>
                                <span class="text">排球(0)</span>
                            </a>
                        </li>-->
                    </ul>
                </div>
            </div>
            <div class="sport-bottom">
                <div class="selection_HK_box">
                    <!--<span class="selection_HK">
                        <select id="header_odds" name="header_odds" class="HK_dropdown">
                            <option value="H">香港盘</option>
                            <option value="M">马来盘</option>
                            <option value="I">印尼盘</option>
                            <option value="E">欧洲盘</option>
                        </select>
                    </span>-->
                    <a href="gameresult.php" class="sport_result">赛果</a>
                    <a href="/template/sportroul.php" class="sport_rule">体育规则</a>
                </div>

            </div>
        </div>
    </div>


    <div class="clear"></div>

    <!-- 底部footer -->
    <div id="footer" class="sport_footer">

    </div>


</div>

<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie

    setFooterAction(uid);  // 在 addServerUrl 前调用
    changeSportTab() ;
    $('.hg_money').html(usermon) ;

// 标签切换
    function changeSportTab() {
        $('.sport-nav').on('click','.item',function () {
            var act = $(this).data('action') ;
            var has = document.getElementsByClassName('sport_expand_'+act)[0].style.display ;
            $('.sport_expand ').hide() ;
            // console.log(has)
            if(has=='block'){
                $('.sport_expand_'+act).hide() ;
            }else{
                $('.sport_expand_'+act).show() ;
            }

        }) ;
    }

</script>


</body>
</html>