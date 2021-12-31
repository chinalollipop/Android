<?php
require ("./include/config.inc.php");
$gtype=$_REQUEST['gtype'];
$uid=$_REQUEST['uid'];
$m_date=date('Y-m-d');

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
parent.reloadCredit('RMB <?php echo floor($rs[0]) ?>');
</script>