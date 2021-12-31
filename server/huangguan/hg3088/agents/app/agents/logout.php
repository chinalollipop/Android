<?php
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
require_once './include/config.inc.php';
include "./include/address.mem.php";
$uid=$_REQUEST['uid'];
$sql = "update ".DBPREFIX."web_agents_data set Oid='logout',Online=0,LogoutTime=now() where Oid='$uid'";
$result = mysqli_query($dbMasterLink,$sql);
mysqli_query($dbMasterLink,$sql) or die ("操作失败!");
echo "<script>window.location='/'</script>";
?>
