<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../../include/config.inc.php");
require ("../../include/curl_http.php");
require ("../../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$gtype=$_REQUEST['gtype'];
$game_id=$_REQUEST['game_id'] ;

$sql = "select Language from ".DBPREFIX.MEMBERTABLE." where oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
if($cou==0){
    setcookie('login_uid','');
    echo "<script>window.open('/tpl/logout_warn.html','_top')</script>";
    exit;
}
$row = mysqli_fetch_assoc($result);
$langx=$row['Language'];
require ("../../include/traditional.$langx.inc.php");

$mysql = "select Uid_ms,datasite_ms,datasite_ms_new,Name_ms,Passwd_ms,datasite,datasite_en,datasite_tw,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";

$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);

$newsuid = $row['Uid_ms'];
$newsite = $row['datasite_ms_new']; // 域名

$filename="".$newsite."/app/member/account/result/BK_result_new.php?gtype=$gtype&game_id=$game_id&uid=$newsuid&langx=$langx";
// echo $filename;
$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
// $curl->set_referrer("".$newsite."/app/member/BK_browse/index.php?rtype=re&uid=$newsuid&langx=$langx&mtype=3");

$html_data=$curl->fetch_url($filename);

$html_data=str_replace($newsuid,$uid,$html_data);
// echo($html_data);
$res=explode('<div class="acc_select_bg">',$html_data);

?>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <link rel="stylesheet" href="../../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <link rel="stylesheet" href="../../../../style/my_account.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        .acc_right_ul{ left: 645px;}
        .acc_right_refresh,.acc_right_close,.acc_right_top{ width: auto;color: #fff;}
    </style>
</head>

<body marginwidth="0" marginheight="0" id="MFT" >
<div class="acc_select_bg">
    <?php
    if($res[1]){ // 有数据
        echo $res[1];
    }
    ?>

</div>
<script type="text/javascript" src="../../../../js/result_new.js?v=<?php echo AUTOVER; ?>"></script>

</body>
</html>
