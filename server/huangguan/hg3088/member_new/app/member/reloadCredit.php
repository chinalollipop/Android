<?php
session_start();
require ("./include/config.inc.php");
$gtype=$_REQUEST['gtype'];
$uid=$_REQUEST['uid'];
$m_date=date('Y-m-d');

// 避免再次加载文件，连接数据库
if(!$_SESSION['AUTOVER_SESSION'] || $_SESSION['AUTOVER_SESSION'] !=AUTOVER){ // 避免重复设置
    $_SESSION['AUTOVER_SESSION'] = AUTOVER;
    $_SESSION['COMPANY_NAME_SESSION'] = COMPANY_NAME;
    $_SESSION['TPL_FILE_NAME_SESSION'] = TPL_FILE_NAME;
    $_SESSION['TPL_NAME_SESSION'] = TPL_NAME;
    $_SESSION['AGENT_LOGIN_URL'] = returnAgentUrl(); // 代理登录链接
    $_SESSION['HTTPS_HEAD_SESSION'] = HTTPS_HEAD;
    $_SESSION['HOST_SESSION'] = $host = getMainHost();
    $lydata = getLyQpSetting();
    $_SESSION['LYTEST_PLAY_SESSION'] = $lydata['demourl']; // 乐游试玩链接
    $_SESSION['AGSX_INIT_SESSION'] = $agsxInit; // AG配置

}

if(isset($_SESSION['userid']) && $_SESSION['userid'] != "") {
	$sql="select money from ".DBPREFIX.MEMBERTABLE." where ID='".$_SESSION['userid']."'";
}else {
	$sql="select money from ".DBPREFIX.MEMBERTABLE." where oid='".$uid."'";
}
$result=mysqli_query($dbLink,$sql);
$rs=mysqli_fetch_array($result);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script>
parent.reloadCredit('RMB <?php echo formatMoney($rs[0]) ?>');
</script>