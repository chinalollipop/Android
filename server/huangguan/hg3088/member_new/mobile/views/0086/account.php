<?php
	include_once('../../include/config.inc.php');

    if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
       echo "<script>alert('你的登录信息已过期，请先登录!');window.location.href='login.php';</script>";
    }
    $uid=$_SESSION["Oid"];
    $username = $_SESSION['UserName'];
	$Alias = $_SESSION['Alias'];
    $cpUrl = $_SESSION['cpUrl'] ;

?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
        <link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

        <title class="web-title"></title>
        <style type="text/css">
            .back-active{ display: none;}
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
                    <img src="images/user_ico.png">
                    <?php if($_SESSION['Agents'] == 'demoguest'){?>
                        <p id="accountcode">试玩玩家</p>
                    <?php }else{?>
                        <p id="accountcode"><?php echo $username;?></p>
                    <?php }?>
                    <h4 class="hg_money"></h4><span id="refresh" onclick="get_cp_blance('.hg_money')">刷新</span>
                </div>
                <ul class="account-list">
                    <li>
                        <a href="middle_lives_upgraded.php" class="to-page">
                            <span class="sj-live">
                            </span>
                            <p>真人升级</p>
                        </a>
                    </li>
                    <li>
                        <a href="middle_lives_upgraded.php?game_Type=sport"  class="to-page">
                            <span class="sj-sport">
                            </span>
                            <p>体育升级</p>
                        </a>
                    </li>
                    <?php if($_SESSION['Agents'] == 'demoguest'){?>
                    <li>
                        <a onclick="alert('非常抱歉，请您注册真实会员！')" class="to-page">
                            <span>
                                <i class="fa fa-database"></i>
                            </span>
                            <p>充值</p>
                            <!--<span class="account-item"></span>-->
                        </a>
                    </li>
                    <li>
                        <a href="tran.php" class="to-page">
                            <span>
                                <i class="fa fa-retweet"></i>
                            </span>
                            <p>额度转换</p>
                            <!--<span class="account-item"></span>-->
                        </a>
                    </li>
                    <li>
                        <a onclick="alert('非常抱歉，请您注册真实会员！')" class="to-page">
                            <span>
                                <i class="fa fa-credit-card"></i>
                            </span>
                            <p>银行卡</p>
                            <!--<span class="account-item"></span>-->
                        </a>
                    </li>

                    <li>
                        <a onclick="alert('非常抱歉，请您注册真实会员！')" class="to-page">
                            <span>
                                <i class="fa fa-usd"></i>
                            </span>
                            <p>提现</p>
                            <!--<span class="account-item"></span>-->
                        </a>
                    </li>
                    <li>
                        <a onclick="alert('非常抱歉，请您注册真实会员！')" class="to-page">
                            <span>
                                <i class="fa fa-list"></i>
                            </span>
                            <p>平台余额</p>
                            <!--<span class="account-item"></span>-->
                        </a>
                    </li>
                    <?php }else{?>
                        <li>
                            <a href="account/deposit_one.php" class="to-page">
                            <span>
                                <i class="fa fa-database"></i>
                            </span>
                                <p>充值</p>
                                <!--<span class="account-item"></span>-->
                            </a>
                        </li>
                        <li>
                            <a href="tran.php" class="to-page">
                            <span>
                                <i class="fa fa-retweet"></i>
                            </span>
                                <p>额度转换</p>
                                <!--<span class="account-item"></span>-->
                            </a>
                        </li>
                        <li>
                            <a href="account/mset_bank.php?action=add" class="to-page">
                            <span>
                                <i class="fa fa-credit-card"></i>
                            </span>
                                <p>银行卡</p>
                                <!--<span class="account-item"></span>-->
                            </a>
                        </li>

                        <li>
                            <a href="account/withdraw.php" class="to-page">
                            <span>
                                <i class="fa fa-usd"></i>
                            </span>
                                <p>提现</p>
                                <!--<span class="account-item"></span>-->
                            </a>
                        </li>
                        <li>
                            <a href="account/user_platform.php" class="to-page">
                            <span>
                                <i class="fa fa-list"></i>
                            </span>
                                <p>平台余额</p>
                                <!--<span class="account-item"></span>-->
                            </a>
                        </li>
                    <?php }?>
                    <li>
                        <a href="moremessage.php?msg_type=message"  class="to-page">
                            <span>
                                <i class="fa fa-envelope"></i>
                            </span>
                            <p>站内信</p>
                            <!--<span class="account-item"></span>-->
                        </a>
                    </li>
                    <?php if($_SESSION['Agents'] == 'demoguest'){?>
                        <li>
                            <a onclick="alert('非常抱歉，请您注册真实会员！');" class="to-page">
                                <span>
                                    <i class="fa fa-address-book"></i>
                                </span>
                                <p>账户中心</p>
                                <!--<span class="account-item"></span>-->
                            </a>
                        </li>
                    <?php }else{?>
                        <li>
                            <a href="account/changepwd.php" class="to-page">
                                <span>
                                    <i class="fa fa-address-book"></i>
                                </span>
                                <p>账户中心</p>
                                <!--<span class="account-item"></span>-->
                            </a>
                        </li>
                    <?php } ?>
                  <!--  <li>
                        <a href="/" class="to-page">
                            <span>
                                <i class="fa fa-list-ul"></i>
                            </span>
                            <p>转账记录</p>
                        </a>
                    </li>-->
                    <li>
                        <a href="/template/betrecord.php" class="to-page">
                            <span>
                                <i class="fa fa-life-ring"></i>
                            </span>
                            <p>投注记录</p>
                            <!--<span class="account-item"></span>-->
                        </a>
                    </li>
              <!--      <li>
                        <a href="account/depositrecord.php?type=S" class="to-page">
                            <span>
                                <i class="fa fa-list-ol"></i>
                            </span>
                            <p>交易记录</p>
                        </a>
                    </li>-->
                    <li>
                        <a href="account/depositrecord.php" class="to-page">
                            <span>
                                <i class="fa fa-fire"></i>
                            </span>
                            <p>流水记录</p>
                           <!-- <span class="account-item"></span>-->
                        </a>
                    </li>
                    <li>
                        <a onclick="loginOutSport()" href="javascript:;" class="to-page">
                            <span>
                                <i class="fa fa-sign-out"></i>
                            </span>
                            <p>登出</p>
                            <!-- <span class="account-item"></span>-->
                        </a>
                    </li>

                   <!-- <li>
                        <a href="qqwechat.php?servertype=contact" class="to-page" >
                            <label>联系我们</label>
                        </a>
                    </li>-->


             <div class="clear"></div>

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