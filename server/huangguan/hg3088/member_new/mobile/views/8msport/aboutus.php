<?php
include_once('../../include/config.inc.php');
$uid = $_SESSION['Oid'];
$tip = isset($_REQUEST['tip'])?$_REQUEST['tip']:'' ; // 用于app 跳转到这个页面

$companyName = COMPANY_NAME;

?>

<html class="zh-cn"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title class="web-title"></title>
    <style type="text/css">
        .timebox{background:#fff;width:94%;margin:.8rem auto .5rem;text-align:left}
        .timebox img{width:100%}
        .timebox .url{color:#3c3941;font-size:1rem;font-weight:normal;border-bottom:1px dashed #3c3941;transition:all .5s ease;padding-bottom:.5rem;margin:.5rem}
        .timebox p{color: #ccc;margin: 0 .5rem;padding-bottom: 1rem}
    </style>
</head>
<body class="dedede">
<div id="container" >
    <!-- 头部 -->
    <div class="header <?php if($tip){echo 'hide-cont';}?>">

    </div>

    <!-- 中间内容 -->
    <div class="content-center">

        <div class="about_us" >
            <div class="timebox " >
                <div class="timeMain">
                    <div class="imgBox "><img class="img" src="images/presence/presence_1.png">
                    </div>
                    <h3 class="url"><?php echo $companyName;?>赛车队（捷凯）率先赢得澳门房车杯选拔赛三甲</h3>
                    <p><?php echo $companyName;?>赞助的赛车队（捷凯）再度与赛车场上扬威，于5月24日至26日期间的澳门房车杯1600CC及1950CC选拔赛第一及第二回合成功夺取佳绩。</p></div>

            </div>
            <div class="timebox" >
                <div class="timeMain">
                    <div class="imgBox "><img class="img" src="images/presence/presence_2.png">
                    </div>
                    <h3 class="url"><?php echo $companyName;?>冠名赞助 "澳门小姐竞选2019"</h3>
                    <p>由<?php echo $companyName;?>冠名赞助，太阳娱乐文化协办 "澳门小姐竞选2019" 于6月5日在澳门旅游塔会展中心举办新闻发布会，宣布大赛正式启动，开始全澳招募参赛佳丽。</p></div>

            </div>
            <div class="timebox" >
                <div class="timeMain">
                    <div class="imgBox "><img class="img" src="images/presence/presence_3.png">
                    </div>
                    <h3 class="url"><?php echo $companyName;?>首创全球至尊综合型会籍 "尊华会" 隆重登场</h3>
                    <p>在2019年全面革新会籍制度,运用集团多元化产业及多个海外业务的优势,把旅游 影视娱乐 餐饮 购物 度假村酒店项目发展等领域结合，创立全球至尊级综合型会籍"尊华会"。</p></div>

            </div>


        </div>

    </div>

    <!-- 底部footer -->
    <div id="footer" class="<?php if($tip){echo 'hide-cont';}?>">

    </div>
</div>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    setLoginHeaderAction('存取帮助','','',usermon,uid) ;
    setFooterAction(uid) ; // 在 addServerUrl 前调用



</script>

</body>
</html>