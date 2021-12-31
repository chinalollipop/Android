<?php
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("../../app/member/include/config.inc.php");

$isSysMaintain = isset($_REQUEST['issys'])?$_REQUEST['issys']:'';
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>维护提示</title>
    <style type="text/css">
        body{margin: 0;padding: 0;overflow-x: hidden;}
        .bg_content{background: url(/<?php echo TPL_NAME;?>images/weihu.jpg) #363636 top center no-repeat;background-size: contain;color: #ccc;}
        div,p{margin: 0;padding: 0;}
        .wh_content {width: 50%;min-width: 530px;height: 694px;padding: 10% 0 0 25%;font-size: 14px;font-weight: bold;}
        .wh_content .wh_logo{width: 400px;height:95px;background:url(/<?php echo TPL_NAME;?>images/logo_app.png) no-repeat;}
        .wh_content .title{font-size:30px;color:#e68829;margin:10px 0 20px}
        .wh_content .tips{font-size:16px;line-height:30px}
        .wh_content .wh_kf{display:inline-block;padding:5px 26px;background:#e68929;color:#fff;text-decoration:none;margin:30px 0;transition:.3s}
        .wh_content .wh_kf:hover{opacity:.9}
    </style>
<body>
<?php
if($isSysMaintain == 1){
    $sysNotice = $_SESSION['sysMaintenanceData'];
    $title = '系统维护';
    $content = $sysNotice['content'];
}else{
    $pageMark = isset($_REQUEST['type']) && $_REQUEST['type'] ? trim($_REQUEST['type']) : 'rb';
    $pageNotice = maintenance($pageMark);
    $title = $pageNotice[$pageMark]['title'];
    $content = $pageNotice[$pageMark]['content'];
}
?>
<div class="bg_content">

    <div class="wh_content">
        <div class="wh_logo">

        </div>
        <p class="title"><?php echo $title?></p>

        <div class="tips"><?php echo $content?></div>
       <!-- <p class="ico"><img src="images/ico.png" width="150" height="150" alt="维护"></p>-->
        <p class="bottom"><?php echo $isSysMaintain == 1 ? '' : '您可以进行平台其他游戏！'?> </p>
        <a class="to_livechat wh_kf">在线客服</a>
    </div>
</div>
<script type="text/javascript" src="/js/jquery.js"></script>
<script>
    $(function () {
        var web_configbase = JSON.parse(localStorage.getItem('webconfigbase'));
        $('.to_livechat').attr({'href':web_configbase.service_meiqia,'target':'_blank'});
    })

</script>
</body>
</html>
