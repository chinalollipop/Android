<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../include/address.mem.php");
require_once ("../include/config.inc.php");
require ("../include/define_function.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$gid=$_REQUEST['gid'];
$mb_in=$_REQUEST['mb_inball']; // 全场主队
$tg_in=$_REQUEST['tg_inball']; // 全场客队
$mb_in_v=$_REQUEST['mb_inball_v']; // 半场主队
$tg_in_v=$_REQUEST['tg_inball_v']; // 半场客队
$gtype=$_REQUEST['gtype'];
$lv=$_REQUEST['lv'];
$page=$_REQUEST['page'];
require ("../include/traditional.$langx.inc.php");

$result = mysqli_query($dbLink,"select MB_Team,TG_Team,`Type`,M_League,M_Start from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Mid=$gid");
$row = mysqli_fetch_assoc($result);

$MB_Team=$row['MB_Team'];
$TG_Team=$row['TG_Team'];
$Type=$row['Type'];
$M_League=$row['M_League'];
$M_Start=$row['M_Start'];

$setArray=array();

if($mb_in!='' && $mb_in>=0){
    $setArray[]="MB_Inball={$mb_in}" ;
    $loginfo = $loginname.' 在审核比分中 <font class="red">对全场比分</font> 进行了结算操作,主队比分为 <font class="red">'.$mb_in.'</font>, 客队比分为 <font class="green">'.$tg_in.'</font>, gid(MID) 为<font class="red">'.$gid.'</font>,gtype 为<font class="red">'.$gtype.'</font> ' ;
}
if($tg_in!='' && $tg_in>=0) $setArray[]="TG_Inball={$tg_in}";
if($mb_in_v!='' && $mb_in_v>=0){
    $setArray[]="MB_Inball_HR={$mb_in_v}";
    $loginfo = $loginname.' 在审核比分中 <font class="red">对半场比分</font> 进行了结算操作,主队比分为 <font class="red">'.$mb_in_v.'</font>, 客队比分为 <font class="green">'.$tg_in_v.'</font>, gid(MID) 为<font class="red">'.$gid.'</font>,gtype 为<font class="red">'.$gtype.'</font> ' ;
}
if($tg_in_v!='' && $tg_in_v>=0) $setArray[]="TG_Inball_HR={$tg_in_v}";

if($mb_in!='' && $mb_in>=0 && $mb_in_v!='' && $mb_in_v>=0){ // 全部都有比分
    $loginfo = $loginname.' 在审核比分中 <font class="red">对全场,半场比分</font> 进行了结算操作,全场主队比分为 <font class="red">'.$mb_in.'</font>, 全场客队比分为 <font class="green">'.$tg_in.'</font>, 半场主队比分为 <font class="red">'.$mb_in_v.'</font>, 半场客队比分为 <font class="green">'.$tg_in_v.'</font>, gid(MID) 为<font class="red">'.$gid.'</font>,gtype 为<font class="red">'.$gtype.'</font> ' ;
}

//var_dump($setArray)  ;die ;
$setArray[] = "Score_Source=3";
$setArray[] = "Checked=1";
$setStr=implode(',',$setArray);

if($setStr && strlen($setStr)>0){
	$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ".$setStr." where Type='".$Type."' and MB_Team='".$MB_Team."' and TG_Team='".$TG_Team."' and M_League='".$M_League."' and M_Start='$M_Start'";
}

if($_REQUEST['ajax']==1){ // 审核比分 操作
	//$res = mysqli_query($dbMasterLink,$mysql) or die ("error!");
    $res = mysqli_query($dbMasterLink,$mysql);
	echo $res;
    innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
}else{ // 审核比分 提交操作
	mysqli_query($dbMasterLink,$mysql);
    innsertSystemLog($loginname,$lv,$loginfo);  /* 插入系统日志 */
	echo "<SCRIPT language='javascript'>self.location='match.php?uid=$uid&langx=$langx&gtype=$gtype&page=$page';</script>";
}

/*echo "<SCRIPT language='javascript'>javascript:history.go( -3 );</script>";*/
?>
