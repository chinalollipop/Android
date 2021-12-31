<?php
	include_once('../../include/config.inc.php');

    if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
       echo "<script>alert('你的登录信息已过期，请先登录!');window.location.href='login.php';</script>";
    }
    $uid=$_SESSION["Oid"];
    $username = $_SESSION['UserName'];
	$Alias = $_SESSION['Alias'];
    $cpUrl = $_SESSION['cpUrl'] ;
$flage = $_SESSION['test_flag'] ;

?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
        <!--<link href="style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
        <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

        <title class="web-title"></title>
        <style type="text/css">

            .back-active{display:none}
            .account-list{background:#fff;width:98%;margin:0 auto 10px;border-radius:5px}
            .account-list-sec{padding-bottom: 15px;}
            .account-list li{margin:0 5%;height:4rem;line-height:4rem;border-bottom:1px solid #f0f0f0;font-size:1.1rem;text-align:left}
            .account-list label{color:#666;width:25%;display:inline-block;margin-right:.8rem}
            .account-item:after{margin-top:1.5rem;border-right: 2px solid #e6e6e6;border-top:2px solid #e6e6e6}
            .account-list .to-page{display:block;width:100%;height:100%}
            .login-out a{display:block;height:3.928rem;line-height:3.928rem;margin:2.85rem 0 .9rem;background:#e64545;font-size:1.3rem;color:#fff}
            .user_bg{text-align:center;background:#fff;height:12rem;width:88%;margin:10px auto;border-radius:5px;box-shadow:0 0px 6px rgba(0,0,0,.2);padding:0 5%}
            .user_bg_top{display:-webkit-flex;display:flex}
            .user_bg img{margin:15px 0 4px 0;width:70px;height:70px;float:left}
            .user_bg .user_tip{display:inline-block;margin-left:5%;padding-top:30px;text-align:left}
            .user_bg .user_bg_top p{color:#5ea0ea;font-size:1.3rem;}
            .user_bg .user_bg_top .user_join{color:#acacac;font-size:1rem}
            .account-list li a:nth-child(3n){border-right:none}
            .account-list li a p{color:#333;height:1.07rem;font-size:1.2rem;display:inline-block}
            .account-list li a span{display:inline-block;vertical-align:top;width:3rem;max-width:66px;height:3rem;max-height:55px;background-size: 85%;background-repeat: no-repeat;background-position: center;}
            .account-list-sec li a span{margin-top: .2rem}
            .account-list li span i{font-size:1.5rem;margin-top:.6rem}
            .account-list li .fa-retweet{background-image:url(images/ucenter/icon_edzh.png)}
            .account-list li .fa-chongzhi{background-image:url(images/ucenter/icon_ck.png)}
            .account-list li .fa-usd{background-image:url(images/ucenter/icon_qk.png)}
            .account-list li .fa-usd-1{background-image:url(images/ucenter/icon_tk.png)}
            .account-list li .fa-promos{background-image:url(images/ucenter/icon_yh.png)}
            .account-list li .fa-czje{background-image:url(images/ucenter/icon_czje.png)}
            .account-list li .fa-edzh{background-image:url(images/ucenter/icon_edzh.png)}
            .account-list li .fa-credit-card{background-image:url(images/ucenter/icon_yhk.png)}
            .account-list li .fa-list{background-image:url(images/ucenter/icon_ptye.png)}
            .account-list li .fa-gonggao{background-image:url(images/ucenter/icon_gg.png)}
            .account-list li .fa-envelope{background-image:url(images/ucenter/icon_znx.png)}
            .account-list li .fa-address-book{background-image:url(images/ucenter/icon_zhzx.png)}
            .account-list li .fa-life-ring{background-image:url(images/ucenter/icon_tzjl.png)}
            .account-list li .fa-fire{background-image:url(images/ucenter/icon_lsjl.png)}
            .account-list li .fa-xinshou{background-image:url(images/ucenter/icon_sxjc.png)}
            .account-list li .fa-lxwm{background-image:url(images/ucenter/icon_lxwm.png)}
            .account-list li .fa-app {background-image:url(images/ucenter/icon_app.png)}
            .user_bg_bottom .user_bg_bottom_left{width:100%;margin-top: .5rem;}
            .user_bg_bottom .account-list{width:100%}
            .user_bg_bottom .account-list li{text-align:center;position:relative;width:25%;border:0;float:left;margin:0}
            .user_bg_bottom .account-list li a p{font-size:1rem;color:#333;display:block;text-align:center;margin-top:-1rem;}
            .user_gg{font-size:1.2rem;display:-webkit-flex;display:flex;background:#fff;border-radius:5px;width:98%;margin:0 auto 10px}
            .user_gg a{color:#000;position:relative;-webkit-flex:1;flex:1;margin:1rem 0 .8rem}
            .user_gg a:first-child{border-right:1px solid #e8e8e8}
            .user_gg a:last-child span{display:inline-block;width:3rem;height:2.5rem;background:url(images/ucenter/icon_dlhz.png) center -2px no-repeat;background-size:100%}
            .user_gg a:last-child:before{content:'代理加盟';position:absolute;left:50%;top:50%;transform:translate(-50%,10%)}
            .user_gg a p{display:-webkit-flex;display:flex;justify-content:center;line-height:2.3rem;color:#5ea0ea}
            .user_gg a p.sx{color: #000;}
            .user_gg a .icon_refresh{display: inline-block;width: 2.3rem;height: 2.3rem;background:url(images/ucenter/icon_reload.png) center no-repeat;background-size: 100%;margin-left: 1rem;}
        </style>
    </head>
    <body>
    <div id="container">
            <!-- 头部 -->
            <div class="header ">

            </div>
        <!-- 中间主体部分 -->
            <div class="content-center">

                <div class="user_bg">
                    <div class="user_bg_top">
                        <img src="images/ucenter/user_ico.png">
                        <div class="user_tip">
                            <p id="accountcode"> <?php echo ($flage?'试玩玩家':$username); ?> </p>
                            <p class="user_join"> 您已加入8M体育 <span class="user_join_day"> </span></p>
                        </div>
                    </div>
                    <div class="user_bg_bottom">
                        <div class="left user_bg_bottom_left">
                            <ul class="account-list">
                                    <li>
                                        <a class="to-page" onclick="<?php echo $flage?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/deposit_one.php\',\'\',\''.$uid.'\')';?>">
                                            <span class="fa-chongzhi">

                                            </span>
                                            <p>充值</p>
                                        </a>
                                    </li>
                                <li>
                                    <a class="to-page" onclick="<?php echo $flage?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/withdraw.php\',\'\',\''.$uid.'\')';?>">
                                            <span class="fa-usd">

                                            </span>
                                        <p>取款</p>
                                    </a>
                                </li>
                                    <li>
                                        <a class="to-page" onclick="ifHasLogin('tran.php','','<?php echo $uid?>')">
                                            <span class="fa-retweet">

                                            </span>
                                            <p>转账</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="promo.php" class="to-page">
                                            <span class="fa-promos">

                                            </span>
                                            <p>优惠</p>
                                        </a>
                                    </li>

                            </ul>
                        </div>
                    </div>

                </div>

                <div class="user_gg">
                    <a >
                        <p class="sx">中心钱包 <span class="icon_refresh" onclick="get_cp_blance('.hg_money')"></span></p>
                        <p>￥<span class="hg_money"> 0 </span></p>
                    </a>
                    <a href="agents_index.php">
                        <span class="icon"></span>
                    </a>
                </div>

                <ul class="account-list account-list-sec">
                    <li>
                        <a class="to-page" onclick="<?php echo $flage?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/deposit_one.php\',\'\',\''.$uid.'\')';?>">
                            <span class="fa-czje">

                            </span>
                            <p>充值金额</p>
                            <i class="account-item"></i>
                        </a>
                    </li>
                    <li>
                        <a class="to-page" onclick="ifHasLogin('tran.php','','<?php echo $uid?>')">
                            <span class="fa-edzh">

                            </span>
                            <p>转账</p>
                            <i class="account-item"></i>
                        </a>
                    </li>
                    <li>
                        <a class="to-page" onclick="<?php echo $flage?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/mset_bank.php?action=add\',\'\',\''.$uid.'\')';?>">
                            <span class="fa-credit-card">
                                <i class="fa "></i>
                            </span>
                            <p>银行卡</p>
                            <i class="account-item"></i>
                        </a>
                    </li>
                    <li>
                        <a class="to-page" onclick="<?php echo $flage?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/withdraw.php\',\'\',\''.$uid.'\')';?>">
                            <span class="fa-usd-1">

                            </span>
                            <p>提款</p>
                            <i class="account-item"></i>
                        </a>
                    </li>
                   <!-- <li>
                        <a class="to-page" onclick="<?php /*echo $flage?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/user_platform.php\',\'\',\''.$uid.'\')';*/?>" >
                            <span class="fa-list">

                            </span>
                            <p>平台余额</p>
                            <i class="account-item"></i>
                        </a>
                    </li>-->

                    <li>
                        <a href="moremessage.php?msg_type=message"  class="to-page">
                            <span class="fa-envelope">

                            </span>
                            <p>站内信</p>
                            <i class="account-item"></i>
                        </a>
                    </li>
                    <li>
                        <a class="to-page" onclick="<?php echo $flage?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/changepwd.php\',\'\',\''.$uid.'\')';?>">
                                <span class="fa-address-book">

                                </span>
                            <p>账户中心</p>
                            <i class="account-item"></i>
                        </a>
                    </li>
                    <li>
                        <a href="/template/betrecord.php" class="to-page">
                            <span class="fa-life-ring">

                            </span>
                            <p>投注记录</p>
                            <i class="account-item"></i>
                        </a>
                    </li>
                    <li>
                        <a href="account/depositrecord.php" class="to-page">
                            <span class="fa-fire">

                            </span>
                            <p>流水记录</p>
                            <i class="account-item"></i>
                        </a>
                    </li>
                    <li>
                        <a href="help.php" class="to-page">
                            <span class="fa-xinshou">

                            </span>
                            <p>新手教学</p>
                            <i class="account-item"></i>
                        </a>
                    </li>

                    <li>
                        <a href="qqwechat.php" class="to-page">
                            <span class="fa-lxwm">

                            </span>
                            <p>联系我们</p>
                            <i class="account-item"></i>
                        </a>
                    </li>
                    <li>
                        <a href="moremessage.php" class="to-page">
                            <span class="fa-gonggao">

                            </span>
                            <p>8M公告</p>
                            <i class="account-item"></i>
                        </a>
                    </li>

                    <li>
                        <a href="appdownload.php" class="to-page">
                            <span class="fa-app">

                            </span>
                            <p>下载APP</p>
                            <i class="account-item"></i>
                        </a>
                    </li>

                    <div class="clear"></div>
                </ul>
                <ul class="account-list">
                    <li style="border: 0">
                        <a onclick="loginOutSport()" href="javascript:;" class="to-page" style="text-align: center;">

                            <p> 安全退出 </p>

                        </a>
                    </li>
                </ul>

            </div>
        <!-- 底部 -->
        <div id="footer">

        </div>
    </div>
    <!-- 彩票联合退出登陆 -->
    <!--<iframe name="cp_loginout_url" id="cp_loginout_url" scrolling="NO" noresize src="" style="display: none;"></iframe>-->

    <script type="text/javascript" src="../../js/zepto.min.js"></script>
     <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
    <script type="text/javascript" src="../../js/usercenter.js?v=<?php echo AUTOVER; ?>"></script>
    <script type="text/javascript">

        get_cp_blance('.hg_money');  // 获取余额

        var uid = '<?php echo $uid?>' ;
        var usermon = getCookieAction('member_money') ; // 获取信息cookie
        var cp_url = '<?php echo $cpUrl?>' ;
        setLoginHeaderAction('我的帐户','','',usermon,uid) ;
        setFooterAction(uid) ; // 在 addServerUrl 前调用
        addServerUrl() ;
        // console.log(usermon);

        
    </script>
    </body>
</html>