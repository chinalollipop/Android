<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_SESSION['Oid'];
$lv = $_REQUEST['lv'] ;


// 会员统计功能 Status  0 启用 1 冻结 2 停用
$sql = "SELECT `Status`, SUM(`Money`) as balance FROM " . DBPREFIX.MEMBERTABLE." GROUP BY `Status`"; // 优化单语句查询
$result = mysqli_query($dbLink, $sql);
$balance = [];
while ($row = mysqli_fetch_assoc($result)){
    $balance[$row['Status']] = $row['balance'];
}

// 彩票余额
$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'], $database['cpDefault']['user'],$database['cpDefault']['password'], $database['cpDefault']['dbname'],
    $database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
$sql = "select sum(lcurrency) as lottery_balance from " . $database['cpDefault']['prefix'] . "user";
$result = mysqli_query($cpMasterDbLink, $sql);
$lotteryRow = mysqli_fetch_assoc($result);

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">
    .m_title{    font-weight: bold;}
</style>
</head>
<body >
    <dl class="main-nav"><dt>统计功能</dt><dd></dd></dl>

    <div class="main-ui width_1300">
        <table class="m_tab">
            <tbody>
            <tr class="m_title">
                <td>应用总和</td>
                <td>停用总和</td>
                <td>冻结总和</td>
                <td>彩票额度</td>
            </tr>
            <tr class="m_cen">
                <td><?php echo sprintf("%01.2f", isset($balance[0]) ? $balance[0] : '0.00');?></td>
                <td><?php echo sprintf("%01.2f", isset($balance[2]) ? $balance[2] : '0.00');?></td>
                <td><?php echo sprintf("%01.2f", isset($balance[1]) ? $balance[1] : '0.00');?></td>
                <td><?php echo sprintf("%01.2f",$lotteryRow['lottery_balance']);?></td>
            </tr>
            </tbody>
        </table>
    </div>
</body>
</html>