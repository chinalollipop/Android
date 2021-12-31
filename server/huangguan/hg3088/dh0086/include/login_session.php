<?
require ("config.inc.php");
$uid=$_REQUEST['uid'];
$datetime=date('Y-m-d H:i:s',time());
$outtime=date('Y-m-d H:i:s',time()-60*30);
$sql = "update ".DBPREFIX."web_member_data set Online=1,OnlineTime='$datetime' where Oid='$uid'";
mysql_query($sql) or die ("����ʧ��!");
$outsql = "update ".DBPREFIX."web_member_data set Online=0 where OnlineTime<'$outtime'";
mysql_query($outsql) or die ("����ʧ��!");
?>
