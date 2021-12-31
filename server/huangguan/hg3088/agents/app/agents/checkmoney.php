<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "./include/address.mem.php";
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("./include/config.inc.php");

$noread = 0;
if( isset($_SESSION['Oid']) && $_SESSION['Oid'] != "" && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == ADMINLOGINFLAG ) {
	//$sql="select ID from ".DBPREFIX."web_sys800_data where Checked<>1";
	$sql="select ID from ".DBPREFIX."web_sys800_data where Checked=0";
	$noreadrs = mysqli_query($dbLink,$sql);
	$noread=mysqli_num_rows($noreadrs);
}

?>
<html>
<head>
<title>800系統</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="refresh" content="10" />
<?php if($noread>0){ ?>
<!--<bgsound src="ring11.wav" loop="2" volume="0" balance="0" />-->
<bgsound src="t.mp3" loop="2" volume="0" balance="0" />
<?php } ?>
</head>
<body>
</body>
</html>