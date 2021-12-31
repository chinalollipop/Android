<?php
include_once('../../include/config.inc.php');
$type = $_REQUEST['servertype'];
$uid = $_SESSION['Oid'];
?>
<html class="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <title class="web-title"></title>
<style>
    .deposit-nav .item:after{display: none;}
    .add_contanct_btn {height: 3rem;line-height: 3rem;width: 6.3rem;text-align: center;border: 1px solid #EEE;margin-top: .85rem;border-radius: 5px;}
    .server-img {position: absolute;right: 4%;max-height:0px; overflow:hidden;transition:500ms; -webkit-transition:500ms; -moz-transition:500ms; -o-transition:500ms; -ms-transition:500ms;}
    .triggered .server-img{max-height: none;visibility: visible;opacity: 1;overflow: initial;}
    .server-img img{width: 10rem;}
    .deposit .tab .item{padding: .3rem 1%;}
</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->

    <div class="content-center deposit">
        <div class="tab">
            <div class="deposit-nav">
                <div class="item">
                    <i class="bank_img qq_icon"></i><span>&nbsp;QQ客服 <strong class="qq_number">  </strong></span>
                    <a class="add_contanct_btn add_qq_contanct_btn right"  >开始聊天</a>
                    <!--  或者 http://wpa.qq.com/msgrd?v=3&uin=59901788&site=qq&menu=yes  -->
                </div>
                <div class="item">
                    <i class="bank_img wechat_icon"></i><span>&nbsp;微信公众号： <strong class="wechat_number">  </strong></span>
                    <a class="add_contanct_btn add_wechat_contanct_btn  right" href="javascript:;" >开始聊天</a>
                </div>

            </div>

            <div class="server-img "> <img src="<?php echo getPicConfig('server_wechat_code');?>" alt="微信公众号"> </div>
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
<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    // 进来判断是哪种客服类型
    function getMessage() {
        var title = '客服' ;
        var type = '<?php echo $type?>' ;

        setLoginHeaderAction(title,'','',usermon,uid) ;

    }

    // 获取qq，微信
    function getConfig(){
        var temp = '/<?php echo TPL_NAME;?>';
        $('.qq_number').html(web_configbase.service_qq);
        $('.add_qq_contanct_btn').attr({'href':'javascript:window.open(\'mqqwpa://im/chat?chat_type=wpa&uin='+web_configbase.service_qq+'&version=1&src_type=web&web_src=oicqzone.com\');'});
        $('.wechat_number').html(web_configbase.service_wechat);

    }

    getConfig();
    getMessage() ;
    setFooterAction(uid) ;
    openWechat() ;

    // 微信客服
    function openWechat() {
        var actionButton = $(".add_wechat_contanct_btn");
        actionButton.on("click", function(e) {
            e.preventDefault();
            $(this).closest(".tab").toggleClass("triggered");
            $(".server-img ").toggle("fade", 350);
        });
    }

</script>
</body>
</html>