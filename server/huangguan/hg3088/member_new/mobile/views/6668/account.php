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
        <!--<link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
        <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

        <title class="web-title"></title>
        <style type="text/css">
            .header{background: none;height: 3rem;line-height: 3rem;}
            .back-active{ display: none;}
            /* 会员中心 */
            .header-right .after_login{line-height: 3rem;}
            .account-list{ background: #fff;overflow: hidden;border-radius: 5px; }
            .account-list li{height: 5.6rem;line-height: 5.6rem;font-size:1.1rem;width: 25%;float: left;}
            .account-list label{color:#666;width:25%;display:inline-block;margin-right:.8rem}
            .account-list .to-page{ display: block;width: 100%;height: 100%;}
            .login-out a{display:block;height:3.928rem;line-height:3.928rem;margin:2.85rem 0 .9rem;background:#e64545;font-size:1.3rem;color:#fff}
            .accountcode span{background:#cbc8c9;padding:1px 15px;border-radius:5px}
            .user_top_name{margin-left:3%;text-align:left}
            .user_tip{display:inline-block;margin-left:2%}
            .user_bg{background:url(images/user_top_bg.png) no-repeat center top;height:12rem;width:100%;background-size:cover}
            .user_bg img{float:left;margin:0 0 4px 0;width:80px}
            .user_bg p{color:#fff;font-size:1em;line-height:1.5rem}
            .user_bg p span{color:#fff}
            .user_bg .user_join{color:rgb(161,199,251);font-size:1.1rem}
            .user_bg .user_join_jd{display:inline-block;width:70%;height:10px;background:#3f64d8;border-radius:10px}
            .user_center_bg{position:absolute;width:94%;height:10rem;background:url(images/user_center_bg.png) no-repeat center;background-size:100%;margin-top:-2rem;left:50%;margin-left:-47%}
            .user_center_bg .user_ye{height:3rem;color:#000;width:92%;margin:0 auto;text-align:left;padding:1.5rem 0 .8rem;font-size:1.2rem;border-bottom:1px dashed #e8e8e8}
            .user_center_bg .financial{margin-top:1rem;padding:0 3%}
            .financial i.fa-promos{background-position:-262px -3px;}
            .user_bottom{width:94%;margin:8rem auto 0}
            .user_bottom .user_gg a{display:inline-block;width:48%;height:6rem;border-radius:20px}
            .user_bottom .user_gg a:first-child{background: url(images/user_gg_img.jpg) no-repeat center ;background-size: 100%;margin-right: 2%;}
            .user_bottom .user_gg a:last-child{background: url(images/user_dl_img.jpg) no-repeat center ;background-size: 100%;}
            .user_bottom .my_gm{background:#fff;color:#000;font-weight:bold;font-size:1.2rem;height:2.7rem;line-height:2.7rem;text-align:left;padding-left:1.5rem;border-radius:5px;margin-bottom:.5rem}
            .user_bottom .my_gm:before{position:absolute;display:inline-block;content:'';width:5px;height:1.8rem;background:#008bfb;border-radius:8px;margin:0.4rem -.8rem}
            .account-list li a:nth-child(3n){border-right: none;}
            .account-list li a p{color: #666;height: 1.07rem;margin-top: -3rem;}
            .account-list li a span{display: inline-block;vertical-align: top;width: 48px;height: 50px;margin: 9px 0 9px 0;border-radius:50%;line-height: 32px;background: url(images/user_icon.png) no-repeat;transform: scale(.7);}
            .account-list li span i{font-size: 1.5rem;margin-top: .6rem;}
            .account-list li span.fa-database{background-position: 0 0;}
            .account-list li span.fa-retweet{background-position: -52px 0;}
            .account-list li span.fa-credit-card{background-position: -110px 0;}
            .account-list li span.fa-usd{background-position: -166px 0;}
            .account-list li span.fa-list{background-position: -656px 0;}
            .account-list li span.fa-envelope{background-position: -218px 0;}
            .account-list li span.fa-address-book{background-position: -276px 0;}
            .account-list li span.fa-life-ring{background-position: -332px 0;}
            .account-list li span.fa-fire{background-position: -384px 0;}
            .account-list li span.fa-xinshou{background-position: -438px 0;}
            .account-list li span.fa-lxwm{background-position: -494px 0;}
            .account-list li span.fa-agent{background-position: -548px 0;}
            .account-list li span.fa-gonggao{background-position: -600px 0;}

        </style>
    </head>
    <body>
    <div id="container" style="position: relative;">
            <!-- 头部 -->
           <!-- <div class="header ">

            </div>-->
        <!-- 中间主体部分 -->
            <div class="content-center">

                <div class="user_bg">
                    <div class="user_top header">
                        <div class="header-right"><i class="fa fa-database"></i><p class="hg_money after_login"></p></div>
                    </div>
                    <div class="user_top_name ">
                        <img src="images/user_ico.png?v=1">
                        <div class="user_tip">
                            <p class="accountcode">
                            <?php echo ($flage?'试玩玩家':$username); ?>
                                <span id="refresh" onclick="get_cp_blance('.hg_money')">刷新</span>
                            </p>

                            <p class="user_join"> 您已加入<?php echo COMPANY_NAME;?> <span class="user_join_day"> </span></p>
                          <!--  <p> <span class="user_join_jd"></span> <span> VIP </span></p>
                            <p class="user_join"> 升级需要<span class="user_level_de"> 500.00 </span>存款<span class="user_level_ls">和3000.00</span>流水</p>-->
                        </div>
                    </div>

                </div>
                <div class="user_center_bg">
                    <div class="user_ye">
                        <p>中心钱包：</p>
                        <p class="hg_money"></p>
                    </div>
                    <ul class="financial">
                        <li onclick="<?php echo $flage?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/deposit_one.php\',\'\',\''.$uid.'\')';?>">
                            <i class="qbzx_fa fa-deposit-card"></i>
                            <span>存款</span>
                        </li>
                        <li onclick="<?php echo $flage?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'account/withdraw.php\',\'\',\''.$uid.'\')';?>">
                            <i class="qbzx_fa fa-withdrow"></i>
                            <span>取款</span>
                        </li>
                        <li onclick="ifHasLogin('tran.php','','<?php echo $uid?>')">
                            <i class="qbzx_fa fa-zz"></i>
                            <span>转账</span>
                        </li>
                        <li onclick="ifHasLogin('promo.php','','<?php echo $uid;?>')">
                            <i class="qbzx_fa fa-promos"></i>
                            <span>优惠活动</span>
                        </li>
                    </ul>
                </div>

                <div class="user_bottom">
                    <div class="user_gg">
                        <a href="promo.php"></a>
                        <a href="agents_reg.php"></a>
                    </div>
                    <p class="my_gm"> 我的功能 </p>
                <ul class="account-list">
                    <?php if($flage){?>
                    <li>
                        <a onclick="alert('非常抱歉，请您注册真实会员！')" class="to-page">
                            <span class="fa-database">
                                <i class="fa fa-database"></i>
                            </span>
                            <p>充值金额</p>
                        </a>
                    </li>
                    <li>
                        <a href="tran.php" class="to-page">
                            <span class="fa-retweet">
                                <i class="fa "></i>
                            </span>
                            <p>额度转换</p>
                        </a>
                    </li>
                    <li>
                        <a onclick="alert('非常抱歉，请您注册真实会员！')" class="to-page">
                            <span class="fa-credit-card">
                                <i class="fa "></i>
                            </span>
                            <p>银行卡</p>
                        </a>
                    </li>

                    <li>
                        <a onclick="alert('非常抱歉，请您注册真实会员！')" class="to-page">
                            <span class="fa-usd">
                                <i class="fa "></i>
                            </span>
                            <p>提款</p>
                        </a>
                    </li>
                    <li>
                        <a onclick="alert('非常抱歉，请您注册真实会员！')" class="to-page">
                            <span class="fa-list">
                                <i class="fa "></i>
                            </span>
                            <p>平台余额</p>
                        </a>
                    </li>
                    <?php }else{?>
                        <li>
                            <a href="account/deposit_one.php" class="to-page">
                            <span class="fa-database">
                                <i class="fa "></i>
                            </span>
                                <p>充值金额</p>
                            </a>
                        </li>
                        <li>
                            <a href="tran.php" class="to-page">
                            <span class="fa-retweet">
                                <i class="fa "></i>
                            </span>
                                <p>额度转换</p>
                            </a>
                        </li>
                        <li>
                            <a href="account/mset_bank.php?action=add" class="to-page">
                            <span class="fa-credit-card">
                                <i class="fa "></i>
                            </span>
                                <p>银行卡</p>
                            </a>
                        </li>

                        <li>
                            <a href="account/withdraw.php" class="to-page">
                            <span class="fa-usd">
                                <i class="fa "></i>
                            </span>
                                <p>提款</p>
                            </a>
                        </li>
                        <li>
                            <a href="account/user_platform.php" class="to-page">
                            <span class="fa-list">
                                <i class="fa "></i>
                            </span>
                                <p>平台余额</p>
                            </a>
                        </li>
                    <?php }?>
                    <li>
                        <a href="moremessage.php?msg_type=message"  class="to-page">
                            <span class="fa-envelope">
                                <i class="fa "></i>
                            </span>
                            <p>站内信</p>
                        </a>
                    </li>
                    <?php if($flage){?>
                        <li>
                            <a onclick="alert('非常抱歉，请您注册真实会员！');" class="to-page">
                                <span class="fa-address-book">
                                    <i class="fa "></i>
                                </span>
                                <p>账户中心</p>
                            </a>
                        </li>
                    <?php }else{?>
                        <li>
                            <a href="account/changepwd.php" class="to-page">
                                <span class="fa-address-book">
                                    <i class="fa "></i>
                                </span>
                                <p>账户中心</p>
                            </a>
                        </li>
                    <?php } ?>

                    <li>
                        <a href="/template/betrecord.php" class="to-page">
                            <span class="fa-life-ring">
                                <i class="fa"></i>
                            </span>
                            <p>投注记录</p>
                        </a>
                    </li>

                    <li>
                        <a href="account/depositrecord.php" class="to-page">
                            <span class="fa-fire">
                                <i class="fa"></i>
                            </span>
                            <p>流水记录</p>
                        </a>
                    </li>
                    <li>
                        <a href="help.php" class="to-page">
                            <span class="fa-xinshou">
                                <i class="fa"></i>
                            </span>
                            <p>新手教程</p>
                        </a>
                    </li>
                    <li>
                        <a href="qqwechat.php" class="to-page">
                            <span class="fa-lxwm">
                                <i class="fa"></i>
                            </span>
                            <p>联系我们</p>
                        </a>
                    </li>
                    <li>
                        <a href="agents_reg.php" class="to-page">
                            <span class="fa-agent">
                                <i class="fa"></i>
                            </span>
                            <p>代理加盟</p>
                        </a>
                    </li>
                    <li>
                        <a href="moremessage.php" class="to-page">
                            <span class="fa-gonggao">
                                <i class="fa"></i>
                            </span>
                            <p>皇冠公告</p>
                        </a>
                    </li>

                </ul>
                    <ul class="account-list" style="margin-top: .5rem;">
                        <li style="width:100%;height: 4rem;line-height: 4rem;">
                            <a onclick="loginOutSport()" href="javascript:;" class="to-page" style="font-size: 1.3rem;color: rgb(110,110,110)">
                               退出登录
                            </a>
                        </li>
                    </ul>
                <div class="clear"></div>
             </div>
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
       // setLoginHeaderAction('我的帐户','','',usermon,uid) ;
        setFooterAction(uid) ; // 在 addServerUrl 前调用
        addServerUrl() ;
        // console.log(usermon);
        
    </script>
    </body>
</html>