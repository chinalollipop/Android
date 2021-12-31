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
        .bg_content{background: url(/<?php echo TPL_NAME;?>images/weihu.jpg) #110d0c no-repeat;background-size: contain;color: #ccc;}
        div,p{margin: 0;padding: 0;}
        .wh_content {width: 50%;max-width: 600px;padding: 23% 0 15% 50%;font-size: 14px;}
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
       <!-- <p class="title"><?php /*echo $title*/?></p>-->
        <div class="tips"><?php echo $content?></div>
       <!-- <p class="ico"><img src="images/ico.png" width="150" height="150" alt="维护"></p>-->
        <p class="bottom"><?php echo $isSysMaintain == 1 ? '' : '您可以进行平台其他游戏！'?><br>感谢您的耐心等候。</p>
    </div>
</div>
</body>
</html>
