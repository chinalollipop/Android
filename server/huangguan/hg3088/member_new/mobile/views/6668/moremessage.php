
<?php
session_start();
include_once('../../include/config.inc.php');
$uid=$_SESSION["Oid"];
$username = $_SESSION['UserName']; // 拿到用户名
$select_date=$_REQUEST['select_date'] ; // 当前选取是哪个时间段
$today_date=date('Y-m-d') ; // 今天
$yester_day =date("Y-m-d",strtotime("-1 day"));
$check_date = $today_date ; // 默认今天
$msgtype = $_REQUEST['msg_type']; //  公告 notice 站内信 message
if(!$msgtype){
    $msgtype = 'notice'; // 默认消息
}
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
        .message-list{color:#5a5959;width:94%;margin:1rem auto;text-align:left}
        .message{background:#ececec;margin-bottom:1rem;padding:.8rem;border-radius:5px}
        .message-content h3,.message-content .time{display:inline-block}
        .message-content .time{float:right;color:#ccc}
        .message-content .message-content-without-thumbnail{color:#504f4f;padding:1rem 0 0}
    </style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>
    <!-- 中间主体内容 -->
    <div class="content-center">
        <div class="message-list">

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
    var msg_type = '<?php echo $msgtype;?>';
    setLoginHeaderAction('更多消息公告','','',usermon,uid) ;
    setFooterAction(uid) ;

    function getUserEmalis(type) {
        var $messagelist = $('.message-list') ;

        var url = '/api/userEmailsApi.php?v='+Math.random() ;
        $.ajax({
            type: 'POST',
            url: url,
            data: {action:type},
            dataType: 'json',
            success: function (res) {
                if(res){
                    // if(res.status != 200){ // 登录已过期
                    //     window.location.href = '/' ;
                    //     return ;
                    // }
                    var str = '' ;
                    var title = '赛事公告';
                    for(var i=0;i<res.data.length;i++){
                        switch (res.data[i].type){
                            case 0:
                                title = '财务公告';
                                break;
                        }
                        if(res.data[i].notice){
                            str +=' <div class="message" >' +
                                '                <div class="message-content" >' +
                                '                    <div><h3 class="isRead-false">'+ title +'</h3>' +
                                '                        <p class="time isRead-true">'+ res.data[i].created_time +'</p></div>' +
                                '                    <p class="isRead-false message-content-without-thumbnail">'+ res.data[i].notice +'</p>' +
                                // '                    <button  class="message-content__read-more">查看详情</button>' +
                                '                </div>' +
                                '            </div>';
                        }

                    }

                    $messagelist.html(str) ;
                }
            },
            error: function (res) {
                setPublicPop('获取数据失败，请稍后再试!');

            }
        });
    }

    getUserEmalis(msg_type) ; // 默认公告

</script>
</body>
</html>