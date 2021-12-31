<?php
if(!defined('PHPYOU')) {
	exit('Access Denied');
}

$usernn=$_SESSION['jxadmin666'];
session_destroy();
unset($_SESSION);
mysqli_query($dbMasterLink, "delete from ".DBPREFIX."tj where username='".$usernn."' ", $conn );
echo "<meta http-equiv=refresh content=\"0;URL=index.php\">";exit;
?>
