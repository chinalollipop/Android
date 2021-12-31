<?php
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("app/member/include/config.inc.php");

$isSysMaintain = isset($_REQUEST['issys'])?$_REQUEST['issys']:'';

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>维护提示</title>
    <style type="text/css">
        html,body,div,p{margin:0px;padding:0px;font-size:12px;font-family:微软雅黑,宋体,Verdana,Arial}
        .content{text-align:center;width:500px;border:1px solid #493721;margin:50px auto;font-size:14px}
        .content .title{text-align:center;background-color:#493721;margin:0;line-height:40px;color:#fff;font-size:16px}
        .tips{color:black;font-size:14px;padding:0;padding-top:20px;width:60%;overflow:hidden;margin:0 auto}
        .tips span{color:red}
        .content .ico{text-align:center;margin:0 auto}
        .content .bottom{padding-bottom:20px;font-size:14px}
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
<div class="content">
    <p class="title"><?php echo $title?></p>
    <div class="tips"><?php echo $content?></div>
    <p class="ico"><img src="images/ico.png" width="150" height="150" alt="维护"></p>
    <p class="bottom"><?php echo $isSysMaintain == 1 ? '' : '您可以进行平台其他游戏！'?><br>感谢您的耐心等候。</p>
</div>
</body>
</html>
