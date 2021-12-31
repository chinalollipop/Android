<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("../include/config.inc.php");
require ("../include/address.mem.php");
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$gtype=$_REQUEST['gtype'];
$gdate=$_REQUEST['gdate'];
if ($gtype=='All'){
	$gtype="";
}else{
	$gtype="and Type='$gtype'";
}
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
?>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<script>
parent.gamedate = '<?php echo $gdate?>';
parent.GameData = new Array();
<?php
$K=0;
$mysql="select MID,Type,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `M_Date` ='$gdate' and MB_Inball='' and Eventid!='' $gtype order by MID";
$result = mysqli_query($dbLink, $mysql);
while ($row=mysqli_fetch_assoc($result)){
       echo "parent.GameData[$K] = new Array('$row[Type]','$row[Eventid]','$row[M_Time]','$row[MB_Team]','$row[TG_Team]','','$row[Play]','','','$row[M_League]');\n";
       $K=$K+1;
}
?>
parent.reload_game();
</script>
