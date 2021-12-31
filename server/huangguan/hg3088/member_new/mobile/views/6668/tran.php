<?php
session_start();
include_once('../../include/config.inc.php');
include('../../include/address.mem.php');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('你的登录信息已过期，请先登录!');window.location.href='login.php';</script>";
    exit;
}
$langx=$_SESSION['Language'];
$UserName = $_SESSION['UserName'];
$uid = $_SESSION['Oid'];
$hgId = $_SESSION['userid'];

?>
<html>
<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="apple-mobile-web-app-title" content="<?php echo COMPANY_NAME;?>">
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
        <!--<link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
        <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/add-logo.png"> <!-- 添加到桌面 -->
        <title class="web-title"></title>

</head>
<body >
<!--额度转换-->
<div id="container" class="dialog-content">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->
    <div class="content-center">
            <div id="creditsChangeBox" class="user">
                <ul>
                    <?php if($_SESSION['Agents'] == 'demoguest'){?>
                        <li>
                            <h3>转出</h3>
                            <select  name="f_blance" id="f_blance">
                                <option value="">请选择钱包</option>
                                <option value="hg">中心钱包</option>
                                <!--<option value="sc">皇冠体育余额</option>-->
                                <option value="cp">彩票余额</option>
                            </select>
                            <div class="blc" id="ccl_form">请选择平台</div>
                        </li>
                        <li>
                            <h3>转入</h3>
                            <select name="t_blance" id="t_blance" >
                                <option value="">请选择钱包</option>
                                <option value="hg">中心钱包</option>
                                <!--<option value="sc">皇冠体育余额</option>-->
                                <option value="cp" >彩票余额</option>
                            </select>
                            <div class="blc" id="ccl_to">请选择平台</div>
                        </li>
                    <?php }else{?>
                        <li>
                            <h3>转出</h3>
                            <select  name="f_blance" id="f_blance">
                                <option value="">请选择钱包</option>
                                <option value="hg">中心钱包</option>
                                <!--<option value="sc">皇冠体育余额</option>-->
                                <option value="cp">彩票余额</option>
                                <!--<option value="gmcp">国民彩票余额</option>-->
                                <option value="ag">AG余额</option>
                                <option value="og">OG视讯余额</option>
                                <option value="bbin">BBIN视讯余额</option>
                                <option value="ky">开元棋牌余额</option>
                               <!-- <option value="ff">皇冠棋牌余额</option>-->
                                <option value="vg">VG棋牌余额</option>
                                <option value="kl">快乐棋牌余额</option>
                                <option value="ly">乐游棋牌余额</option>
                                <option value="mg">MG电子余额</option>
                                <option value="cq">CQ9电子余额</option>
                                <option value="mw">MW电子余额</option>
                                <option value="fg">FG电子余额</option>
                                <option value="avia">泛亚电竞余额</option>
                                <option value="fire">雷火电竞余额</option>
                            </select>
                            <div class="blc" id="ccl_form">请选择平台</div>
                        </li>
                        <li>
                            <h3>转入</h3>
                            <select name="t_blance" id="t_blance" >
                                <option value="">请选择钱包</option>
                                <option value="hg">中心钱包</option>
                                <!--<option value="sc">皇冠体育余额</option>-->
                                <option value="cp" >彩票余额</option>
                                <!--<option value="gmcp">国民彩票余额</option>-->
                                <option value="ag" >AG余额</option>
                                <option value="og">OG视讯余额</option>
                                <option value="bbin">BBIN视讯余额</option>
                                <option value="ky">开元棋牌余额</option>
                               <!-- <option value="ff">皇冠棋牌余额</option>-->
                                <option value="vg">VG棋牌余额</option>
                                <option value="kl">快乐棋牌余额</option>
                                <option value="ly">乐游棋牌余额</option>
                                <option value="mg">MG电子余额</option>
                                <option value="cq">CQ9电子余额</option>
                                <option value="mw">MW电子余额</option>
                                <option value="fg">FG电子余额</option>
                                <option value="avia">泛亚电竞余额</option>
                                <option value="fire">雷火电竞余额</option>
                            </select>
                            <div class="blc" id="ccl_to">请选择平台</div>
                        </li>
                    <?php }?>

                    <li>
                        <h3>金额：</h3><input class="enter money-textbox" placeholder="金额" name="blance" id="blance">
                    </li>
                    <li>
                        <h3>全转：</h3>
                        <div class="sbtn moneychoose">
                            <button value="100">100</button>
                            <button value="500">500</button>
                            <button value="1000">1000</button>
                            <button value="2000">2000</button>
                            <button value="5000">5000</button>
                        </div>
                    </li>
                    <button class="close_ft_nav" name="trans_blance" id="trans_blance">确认转帐</button>
                    <li>
                        <h3>温馨提示：</h3>
                        <ol>
                            <li>1.转账前请退出游戏或游戏投注界面。</li>
                            <li>2.不参与活动时, 户内转账金额不能少于 1元，户内转账不收取任何手续费。</li>
                            <li>3.如遇网速较慢时，请耐心等候片刻，不要多次重复提交。</li>
                        </ol>
                    </li>
                </ul>
            </div>
    </div>

    <!-- 底部footer -->
    <div id="footer">

    </div>
</div>

<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
<script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/usercenter.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var uid = '<?php echo $uid?>' ;
    var id = "<?php echo $hgId;?>";
    var userName = "<?php echo $UserName;?>";
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    var checkedMoney = '';
    setLoginHeaderAction('额度转换','','',usermon,uid) ;
    setFooterAction(uid) ;
    chooseAction(checkedMoney);
    // 检查创建AG账号
    get_blance('balance'); // 获取AG余额

    tranUserMoney();

</script>  
</body>
</html>