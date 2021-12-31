<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "include/config.inc.php";

// 判断会员状态是否启用，否则退出
if ($_SESSION['Status'] != 0){
    echo "<script>alert('非常抱歉，您的账号已冻结或已停用，请您联系客服！')</script>";
    exit;
}

// todo 维护

// todo 跳转条件
$uid = $_SESSION['Oid'];
$flag = isset($_REQUEST['flag'])?$_REQUEST['flag']:'';
$mtype = 4 ;
$langx = $_SESSION['langx'];
$showtype = isset($_REQUEST['showtype'])?$_REQUEST['showtype']:'today';
$rtype = isset($_REQUEST['rtype'])?$_REQUEST['rtype']:'r';
$username = $_SESSION['UserName'];

// 皇冠体育域名
$sportCenterUrl = $_SESSION['sportCenterUrl'];
?>

<link rel="stylesheet" type="text/css" href="../../style/member/sports_common.css?v=<?php echo AUTOVER; ?>" >
<div class="sport_content_all">
    <iframe id="sport_center" name="sport_center" noresize="" scrolling="NO" src="<?php echo $sportCenterUrl . '/?uid='.$uid .'&rtype='.$rtype.'&showtype='.$showtype.'&mtype='.$mtype;?>" width="100%" height="100%" frameborder="0"></iframe>
</div>
