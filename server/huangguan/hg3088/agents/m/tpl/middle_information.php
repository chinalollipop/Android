<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Content-type: text/html; charset=utf-8");

require ("../../app/agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$navtitle = isset($_REQUEST['navtitle'])?$_REQUEST['navtitle']:'';


?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <META name="keywords" content="<?php echo COMPANY_NAME;?>,<?php echo COMPANY_NAME;?>登入,<?php echo COMPANY_NAME;?>平台">
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="/images/favicon_<?php echo TPL_FILE_NAME;?>.ico" type="image/x-icon"/>
    <link href="../css/main.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title> <?php echo COMPANY_NAME;?> </title>
    <style type="text/css">
        .contact_center .nav{line-height:3rem;padding:0 2%;background:#fff;border-bottom:1px solid #f1f1f1}
        .contact_center .nav a{font-size: 1.1rem;color:#4e525e;width:20%;margin-right:5%}
        .contact_center .nav a.active{border-bottom:2px solid #5da2ea}
        .contact_center .wbnr{font-size: 1rem;margin:.5rem auto;padding:0 2%;color: #acacac;}
        .contact_center .messageLi{border-bottom:1px solid #f1f1f1;padding: .5rem 0;}
        .contact_center .messageLi .text{text-align:left;display:inline-block;width:calc(100% - 25px);margin-left:5px}
        .contact_center .messageLi p{text-align:right}
        .contact_center .messageLi .icon{display:inline-block;color:#fff;width:20px;height:20px;line-height:20px;vertical-align:top;margin-top:5px}
    </style>
</head>
<body>
<div class="contentAll flex">
    <?php include 'middle_header.php'; ?>

    <div class="contentCenterAll contact_center">
        <div class="nav flex navAction">
            <a class="active" data-type="zxkf"> 公告通知 </a>
        </div>
        <div class="wbnr navShowAll">
            <div class="nav_xxgg" >

            </div>
        </div>
    </div>

    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    settingHeight('.contentAll');
    getMessage();
    // 获取公告
    function getMessage() {
        var url = '/api/messageApi.php';
        var $nav_xxgg = $('.nav_xxgg');
        $.ajax({
            type: 'POST',
            url:url,
            data:'',
            dataType:'json',
            success:function(res){
                var str = '';
                if(res.data){
                    for(var i=0;i<res.data.length;i++){
                        str +=  '<div class="messageLi">' +
                                '<div > <span class="icon linear_1">'+(i+1)+'</span>'+
                                '<span class="text">'+ res.data[i].Message +'</span>'+
                                '</div>'+
                                '<p>'+ res.data[i].Date +'</p>'+
                                '</div> ';
                    }
                }else{
                    str += '<div class="no_data">暂无消息</div>';
                }
                $nav_xxgg.html(str);

            },
            error:function(){
                layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
            }
        });
    }
</script>
</body>
</html>
