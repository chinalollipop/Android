<?php 

if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");	
}

define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/configUserCli.php";

$outtime=date('Y-m-d H:i:s',time()-60*30);
$outsql = "update ".DBPREFIX.MEMBERTABLE." set Online=0 where Online=1 AND OnlineTime<'$outtime'";
mysqli_query($dbMasterLink,$outsql) or die ("操作失败！");

?>