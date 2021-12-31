<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");
include_once ("../include/redis.php");
checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
$userlv=$_REQUEST['userlv'] ; // 当前管理员层级
$name=$_REQUEST["name"];
$parents_id=$_REQUEST["parents_id"];
$active=$_REQUEST["active"];
$gtype=$_REQUEST["gtype"];

require ("../../agents/include/traditional.$langx.inc.php");


$agent=$_SESSION['UserName'];
	
$sql = "select * from ".DBPREFIX."web_agents_data where ID=$parents_id";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$username=$row["UserName"];
$admin=$row['Admin'];
$super=$row['Super'];
$corprator=$row['Corprator'];
$world=$row["World"];
$alias=$row["Alias"];

switch ($lv){
case 'A':
    $Title=$Mem_Super;
	$user="$admin";
	$ag="super='$name' and Level='B'";
	$data=DBPREFIX."web_agents_data";
	$webdata='web_system_data';
	$agname="Super='$username'";
	break;
case 'B':
    $Title=$Mem_Corprator;
	$user="$super";
	$ag="Corprator='$name' and Level='C'";
	$data=DBPREFIX."web_agents_data";
	$webdata=DBPREFIX.'web_agents_data';
	$agname="Corprator='$username'";
	break;
case 'C':
    $Title=$Mem_World;
	$user="$corprator";
	$ag="World='$name' and Level='D'";
	$data=DBPREFIX."web_agents_data";
	$webdata=DBPREFIX.'web_agents_data';
	$agname="World='$username'";
	break;
case 'D':
    $Title=$Mem_Agents;
	$user="$world";
	$ag="Agents='$name'";
	$data=DBPREFIX.MEMBERTABLE."";
	$webdata=DBPREFIX.'web_agents_data';
	$agname="Agents='$username'";
	break;
}
$loginfo='详细设置'.$Title.':'.$username;
$asql = "select * from $webdata where UserName='$user'";
$aresult = mysqli_query($dbLink,$asql);
$arow = mysqli_fetch_assoc($aresult);

if($data != DBPREFIX.MEMBERTABLE."") {
	$bsql = "select * from $data where $ag";
	$bresult = mysqli_query($dbLink,$bsql);
	$brow = mysqli_fetch_assoc($bresult);	
}

if ($active=='edit_conf'){

$rscene=$arow["".$gtype."_R_Scene"];
$ouscene=$arow["".$gtype."_OU_Scene"];
$rescene=$arow["".$gtype."_RE_Scene"];
$rouscene=$arow["".$gtype."_ROU_Scene"];
$rmscene=$arow["".$gtype."_RM_Scene"];
$eoscene=$arow["".$gtype."_EO_Scene"];
$mscene=$arow["".$gtype."_M_Scene"];
$pdscene=$arow["".$gtype."_PD_Scene"];
$tscene=$arow["".$gtype."_T_Scene"];
$fscene=$arow["".$gtype."_F_Scene"];
$pscene=$arow["".$gtype."_P_Scene"];
$prscene=$arow["".$gtype."_PR_Scene"];
$p3scene=$arow["".$gtype."_P3_Scene"];
$fsscene=$arow["FS_FS_Scene"];

$rbet=$arow["".$gtype."_R_Bet"];
$oubet=$arow["".$gtype."_OU_Bet"];
$rebet=$arow["".$gtype."_RE_Bet"];
$roubet=$arow["".$gtype."_ROU_Bet"];
$rmbet=$arow["".$gtype."_RM_Bet"];
$eobet=$arow["".$gtype."_EO_Bet"];
$mbet=$arow["".$gtype."_M_Bet"];
$pdbet=$arow["".$gtype."_PD_Bet"];
$tbet=$arow["".$gtype."_T_Bet"];
$fbet=$arow["".$gtype."_F_Bet"];
$pbet=$arow["".$gtype."_P_Bet"];
$prbet=$arow["".$gtype."_PR_Bet"];
$p3bet=$arow["".$gtype."_P3_Bet"];
$fsbet=$arow["FS_FS_Bet"];

$scascene=$arow["".$gtype."_SCA_Scene"];
$scbscene=$arow["".$gtype."_SCB_Scene"];
$scaascene=$arow["".$gtype."_SCA_AOUEO_Scene"];
$scabscene=$arow["".$gtype."_SCA_BOUEO_Scene"];
$scarbgscene=$arow["".$gtype."_SCA_RBG_Scene"];
$acscene=$arow["".$gtype."_AC_Scene"];
$actscene=$arow["".$gtype."_AC_TOUEO_Scene"];
$ac6ascene=$arow["".$gtype."_AC6_AOUEO_Scene"];
$ac6bscene=$arow["".$gtype."_AC6_BOUEO_Scene"];
$ac6rbgscene=$arow["".$gtype."_AC6_RBG_Scene"];
$sxscene=$arow["".$gtype."_SX_Scene"];
$hwscene=$arow["".$gtype."_HW_Scene"];
$mtscene=$arow["".$gtype."_MT_Scene"];
$ecscene=$arow["".$gtype."_EC_Scene"];

$scabet=$arow["".$gtype."_SCA_Bet"];
$scbbet=$arow["".$gtype."_SCB_Bet"];
$scaabet=$arow["".$gtype."_SCA_AOUEO_Bet"];
$scabbet=$arow["".$gtype."_SCA_BOUEO_Bet"];
$scarbgbet=$arow["".$gtype."_SCA_RBG_Bet"];
$acbet=$arow["".$gtype."_AC_Bet"];
$actbet=$arow["".$gtype."_AC_TOUEO_Bet"];
$ac6abet=$arow["".$gtype."_AC6_AOUEO_Bet"];
$ac6bbet=$arow["".$gtype."_AC6_BOUEO_Bet"];
$ac6rbgbet=$arow["".$gtype."_AC6_RBG_Bet"];
$sxbet=$arow["".$gtype."_SX_Bet"];
$hwbet=$arow["".$gtype."_HW_Bet"];
$mtbet=$arow["".$gtype."_MT_Bet"];
$ecbet=$arow["".$gtype."_EC_Bet"];
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
if($data != DBPREFIX.MEMBERTABLE."") {
	$brscene=$brow["".$gtype."_R_Scene"];
	$bouscene=$brow["".$gtype."_OU_Scene"];
	$brescene=$brow["".$gtype."_RE_Scene"];
	$brouscene=$brow["".$gtype."_ROU_Scene"];
	$beoscene=$brow["".$gtype."_EO_Scene"];
	$brmscene=$brow["".$gtype."_RM_Scene"];
	$bmscene=$brow["".$gtype."_M_Scene"];
	$bpdscene=$brow["".$gtype."_PD_Scene"];
	$btscene=$brow["".$gtype."_T_Scene"];
	$bfscene=$brow["".$gtype."_F_Scene"];
	$bpscene=$brow["".$gtype."_P_Scene"];
	$bprscene=$brow["".$gtype."_PR_Scene"];
	$bp3scene=$brow["".$gtype."_P3_Scene"];
	$bfsscene=$brow["FS_FS_Scene"];
	
	$brbet=$brow["".$gtype."_R_Bet"];
	$boubet=$brow["".$gtype."_OU_Bet"];
	$brebet=$brow["".$gtype."_RE_Bet"];
	$broubet=$brow["".$gtype."_ROU_Bet"];
	$beobet=$brow["".$gtype."_EO_Bet"];
	$brmbet=$brow["".$gtype."_RM_Bet"];
	$bmbet=$brow["".$gtype."_M_Bet"];
	$bpdbet=$brow["".$gtype."_PD_Bet"];
	$btbet=$brow["".$gtype."_T_Bet"];
	$bfbet=$brow["".$gtype."_F_Bet"];
	$bpbet=$brow["".$gtype."_P_Bet"];
	$bprbet=$brow["".$gtype."_PR_Bet"];
	$bp3bet=$brow["".$gtype."_P3_Bet"];
	$bfsbet=$brow["FS_FS_Bet"];
}else {
	$brscene=constant($gtype."_R_Scene");
	$bouscene=constant($gtype."_OU_Scene");
	$brescene=constant($gtype."_RE_Scene");
	$brouscene=constant($gtype."_ROU_Scene");
	$beoscene=constant($gtype."_EO_Scene");
	$brmscene=constant($gtype."_RM_Scene");
	$bmscene=constant($gtype."_M_Scene");
	$bpdscene=constant($gtype."_PD_Scene");
	$btscene=constant($gtype."_T_Scene");
	$bfscene=constant($gtype."_F_Scene");
	$bpscene=constant($gtype."_P_Scene");
	$bprscene=constant($gtype."_PR_Scene");
	$bp3scene=constant($gtype."_P3_Scene");
	$bfsscene=FS_FS_Scene;
	
	$brbet=constant($gtype."_R_Bet");
	$boubet=constant($gtype."_OU_Bet");
	$brebet=constant($gtype."_RE_Bet");
	$broubet=constant($gtype."_ROU_Bet");
	$beobet=constant($gtype."_EO_Bet");
	$brmbet=constant($gtype."_RM_Bet");
	$bmbet=constant($gtype."_M_Bet");
	$bpdbet=constant($gtype."_PD_Bet");
	$btbet=constant($gtype."_T_Bet");
	$bfbet=constant($gtype."_F_Bet");
	$bpbet=constant($gtype."_P_Bet");
	$bprbet=constant($gtype."_PR_Bet");
	$bp3bet=constant($gtype."_P3_Bet");
	$bfsbet=FS_FS_Bet;
}

$bscascene=$arow["".$gtype."_SCA_Scene"];
$bscbscene=$arow["".$gtype."_SCB_Scene"];
$bscaascene=$arow["".$gtype."_SCA_AOUEO_Scene"];
$bscabscene=$arow["".$gtype."_SCA_BOUEO_Scene"];
$bscarbgscene=$arow["".$gtype."_SCA_RBG_Scene"];
$bacscene=$arow["".$gtype."_AC_Scene"];
$bactscene=$arow["".$gtype."_AC_TOUEO_Scene"];
$bac6ascene=$arow["".$gtype."_AC6_AOUEO_Scene"];
$bac6bscene=$arow["".$gtype."_AC6_BOUEO_Scene"];
$bac6rbgscene=$arow["".$gtype."_AC6_RBG_Scene"];
$bsxscene=$arow["".$gtype."_SX_Scene"];
$bhwscene=$arow["".$gtype."_HW_Scene"];
$bmtscene=$arow["".$gtype."_MT_Scene"];
$becscene=$arow["".$gtype."_EC_Scene"];

$bscabet=$arow["".$gtype."_SCA_Bet"];
$bscbbet=$arow["".$gtype."_SCB_Bet"];
$bscaabet=$arow["".$gtype."_SCA_AOUEO_Bet"];
$bscabbet=$arow["".$gtype."_SCA_BOUEO_Bet"];
$bscarbgbet=$arow["".$gtype."_SCA_RBG_Bet"];
$bacbet=$arow["".$gtype."_AC_Bet"];
$bactbet=$arow["".$gtype."_AC_TOUEO_Bet"];
$bac6abet=$arow["".$gtype."_AC6_AOUEO_Bet"];
$bac6bbet=$arow["".$gtype."_AC6_BOUEO_Bet"];
$bac6rbgbet=$arow["".$gtype."_AC6_RBG_Bet"];
$bsxbet=$arow["".$gtype."_SX_Bet"];
$bhwbet=$arow["".$gtype."_HW_Bet"];
$bmtbet=$arow["".$gtype."_MT_Bet"];
$becbet=$arow["".$gtype."_EC_Bet"];


	if ($_REQUEST["".$gtype."_R_SC"]>$rscene or $_REQUEST["".$gtype."_OU_SC"]>$ouscene or $_REQUEST["".$gtype."_RE_SC"]>$rescene or $_REQUEST["".$gtype."_ROU_SC"]>$rouscene or $_REQUEST["".$gtype."_EO_SC"]>$eoscene or $_REQUEST["".$gtype."_RM_SC"]>$rmscene or $_REQUEST["".$gtype."_M_SC"]>$mscene or $_REQUEST["".$gtype."_PD_SC"]>$pdscene or $_REQUEST["".$gtype."_T_SC"]>$tscene or $_REQUEST["".$gtype."_F_SC"]>$fscene or $_REQUEST["".$gtype."_P_SC"]>$pscene or $_REQUEST["".$gtype."_PR_SC"]>$prscene or $_REQUEST["".$gtype."_P3_SC"]>$p3scene or $_REQUEST["".$gtype."_SCA_SC"]>$scascene or $_REQUEST["".$gtype."_SCB_SC"]>$scbscene or $_REQUEST["".$gtype."_SCA_AOUEO_SC"]>$scaascene or $_REQUEST["".$gtype."_SCA_BOUEO_SC"]>$scabscene or $_REQUEST["".$gtype."_SCA_RBG_SC"]>$scarbgscene or $_REQUEST["".$gtype."_AC_SC"]>$acscene or $_REQUEST["".$gtype."_AC_TOUEO_SC"]>$actscene or $_REQUEST["".$gtype."_AC6_AOUEO_SC"]>$ac6ascene or $_REQUEST["".$gtype."_AC6_BOUEO_SC"]>$ac6bscene or $_REQUEST["".$gtype."_AC6_RBG_SC"]>$ac6rbgscene or $_REQUEST["".$gtype."_SX_SC"]>$sxscene or $_REQUEST["".$gtype."_HW_SC"]>$hwscene or $_REQUEST["".$gtype."_MT_SC"]>$mtscene or $_REQUEST["".$gtype."_EC_SC"]>$ecscene){
		echo wterror("此 $username 代理商的 单场限额 已超过 $user 代理商的 单场限额，请回上一面重新输入");
		$loginfo='详细设置'.$Title.':'.$username.'单场限额设置失败';
		$ip_addr = get_ip();
		$mysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$agent',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
		mysqli_query($dbMasterLink,$mysql);
		exit();
	}
	if ($_REQUEST["".$gtype."_R_SO"]>$rbet or $_REQUEST["".$gtype."_OU_SO"]>$oubet or $_REQUEST["".$gtype."_RE_SO"]>$rebet or $_REQUEST["".$gtype."_ROU_SO"]>$roubet or $_REQUEST["".$gtype."_EO_SO"]>$eobet or $_REQUEST["".$gtype."_RM_SO"]>$rmbet or $_REQUEST["".$gtype."_M_SO"]>$mbet or $_REQUEST["".$gtype."_PD_SO"]>$pdbet or $_REQUEST["".$gtype."_T_SO"]>$tbet or $_REQUEST["".$gtype."_F_SO"]>$fbet or $_REQUEST["".$gtype."_P_SO"]>$pbet or $_REQUEST["".$gtype."_PR_SO"]>$prbet or $_REQUEST["".$gtype."_P3_SO"]>$p3bet or $_REQUEST["".$gtype."_SCA_SO"]>$scabet or $_REQUEST["".$gtype."_SCB_SO"]>$scbbet or $_REQUEST["".$gtype."_SCA_AOUEO_SO"]>$scaabet or $_REQUEST["".$gtype."_SCA_BOUEO_SO"]>$scabbet or $_REQUEST["".$gtype."_SCA_RBG_SO"]>$scarbgbet  or $_REQUEST["".$gtype."_AC_SO"]>$acbet or $_REQUEST["".$gtype."_AC_TOUEO_SO"]>$actbet or $_REQUEST["".$gtype."_AC6_AOUEO_SO"]>$ac6abet or $_REQUEST["".$gtype."_AC6_BOUEO_SO"]>$ac6bbet or $_REQUEST["".$gtype."_AC6_RBG_SO"]>$ac6rbgbet or $_REQUEST["".$gtype."_SX_SO"]>$sxbet or $_REQUEST["".$gtype."_HW_SO"]>$hwbet or $_REQUEST["".$gtype."_MT_SO"]>$mtbet or $_REQUEST["".$gtype."_EC_SO"]>$ecbet){
		echo wterror("此 $username 代理商的 单注限额 已超过 $user 代理商的 单注限额，请回上一面重新输入");
		$loginfo='详细设置'.$Title.':'.$username.'单注限额设置失败';
		$ip_addr = get_ip();
		$mysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$agent',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
		mysqli_query($dbMasterLink,$mysql);
		exit();
	}
	switch ($gtype){
	case "FT":
		$loginfo='详细设置'.$Title.':'.$username.' 足球详细设置成功';
		echo "<Script Language=javascript>self.location='user_set.php?uid=$uid&lv=$lv&parents_id=$parents_id&name=$name&langx=$langx';</script>";
		break;
	case "BK":
		$loginfo='详细设置'.$Title.':'.$username.' 篮球详细设置成功';
		echo "<Script Language=javascript>self.location='user_set.php?uid=$uid&lv=$lv&parents_id=$parents_id&name=$name&langx=$langx';</script>";		
		break;
	case "BS":
		$loginfo='详细设置'.$Title.':'.$username.' 棒球详细设置成功';
		echo "<Script Language=javascript>self.location='user_set.php?uid=$uid&lv=$lv&parents_id=$parents_id&name=$name&langx=$langx';</script>";		
		break;
	case "TN":
	
		if($_REQUEST['TN_P3_SO']<$bp3bet){
		    $mysql="update ".DBPREFIX."web_agents_data set TN_P3_Bet=".$_REQUEST['TN_P3_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("TN代理操作失败P3!");
		}
		if($_REQUEST['TN_P3_SC']<$bp3scene){
		    $mysql="update ".DBPREFIX."web_agents_data set TN_P3_Scene=".$_REQUEST['TN_P3_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("TN代理操作失败P3!");
		}

		$mysql="update ".DBPREFIX."web_agents_data set TN_R_Bet=".$_REQUEST['TN_R_SO'].",TN_R_Scene=".$_REQUEST['TN_R_SC'].",TN_OU_Bet=".$_REQUEST['TN_OU_SO'].",TN_OU_Scene=".$_REQUEST['TN_OU_SC'].",TN_RE_Bet=".$_REQUEST['TN_RE_SO'].",TN_RE_Scene=".$_REQUEST['TN_RE_SC'].",TN_ROU_Bet=".$_REQUEST['TN_ROU_SO'].",TN_ROU_Scene=".$_REQUEST['TN_ROU_SC'].",TN_EO_Bet=".$_REQUEST['TN_EO_SO'].",TN_EO_Scene=".$_REQUEST['TN_EO_SC'].",TN_M_Bet=".$_REQUEST['TN_M_SO'].",TN_M_Scene=".$_REQUEST['TN_M_SC'].",TN_PD_Bet=".$_REQUEST['TN_PD_SO'].",TN_PD_Scene=".$_REQUEST['TN_PD_SC'].",TN_P_Bet=".$_REQUEST['TN_P_SO'].",TN_P_Scene=".$_REQUEST['TN_P_SC'].",TN_PR_Bet=".$_REQUEST['TN_PR_SO'].",TN_PR_Scene=".$_REQUEST['TN_PR_SC'].",TN_P3_Bet=".$_REQUEST['TN_P3_SO'].",TN_P3_Scene=".$_REQUEST['TN_P3_SC']." where ID='$parents_id'";
		mysqli_query($dbLink,$mysql) or die ("TN操作失败21!");
		$loginfo='详细设置'.$Title.':'.$username.' 网球详细设置成功';
		echo "<Script Language=javascript>self.location='user_set.php?uid=$uid&lv=$lv&parents_id=$parents_id&name=$name&langx=$langx';</script>";		
		break;
	case "VB":
		if($_REQUEST['VB_R_SO']<$brbet){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_R_Bet=".$_REQUEST['VB_R_SO']." where  $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败1!");
		}
		if($_REQUEST['VB_R_SC']<$brscene){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_R_Scene=".$_REQUEST['VB_R_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败2!");
		}
		if($_REQUEST['VB_OU_SO']<$boubet){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_OU_Bet=".$_REQUEST['VB_OU_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败1!");
		}
		if($_REQUEST['VB_OU_SC']<$bouscene){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_OU_Scene=".$_REQUEST['VB_OU_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败2!");
		}
		if($_REQUEST['VB_RE_SO']<$brebet){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_RE_Bet=".$_REQUEST['VB_RE_SO']." where  $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败1!");
		}
		if($_REQUEST['VB_RE_SC']<$brescene){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_RE_Scene=".$_REQUEST['VB_RE_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败2!");
		}
		
		if($_REQUEST['VB_ROU_SO']<$broubet){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_ROU_Bet=".$_REQUEST['VB_ROU_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败1!");
		}
		if($_REQUEST['VB_ROU_SC']<$brouscene){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_ROU_Scene=".$_REQUEST['VB_ROU_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败2!");
		}
		
		if($_REQUEST['VB_EO_SO']<$beobet){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_EO_Bet=".$_REQUEST['VB_EO_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败1!");
		}
		if($_REQUEST['VB_EO_SC']<$beoscene){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_EO_Scene=".$_REQUEST['VB_EO_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败2!");
		}
		
		if($_REQUEST['VB_M_SO']<$bmbet){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_M_Bet=".$_REQUEST['VB_M_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败M!");
		}
		if($_REQUEST['VB_M_SC']<$bmscene){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_M_Scene=".$_REQUEST['VB_M_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败M!");
		}
		
		if($_REQUEST['VB_PD_SO']<$bpdbet){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_PD_Bet=".$_REQUEST['VB_PD_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败PD!");
		}
		if($_REQUEST['VB_PD_SC']<$bpdscene){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_PD_Scene=".$_REQUEST['VB_PD_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败PD!");
		}
		
		if($_REQUEST['VB_P_SO']<$bpbet){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_P_Bet=".$_REQUEST['VB_P_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败P!");
		}
		if($_REQUEST['VB_P_SC']<$bpscene){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_P_Scene=".$_REQUEST['VB_P_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败P!");
		}
		
		if($_REQUEST['VB_PR_SO']<$bprbet){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_PR_Bet=".$_REQUEST['VB_PR_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败PR!");
		}
		if($_REQUEST['VB_PR_SC']<$bprscene){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_PR_Scene=".$_REQUEST['VB_PR_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败PR!");
		}
		
		if($_REQUEST['VB_P3_SO']<$bp3bet){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_P3_Bet=".$_REQUEST['VB_P3_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败P3!");
		}
		if($_REQUEST['VB_P3_SC']<$bp3scene){
		    $mysql="update ".DBPREFIX."web_agents_data set VB_P3_Scene=".$_REQUEST['VB_P3_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("VB代理操作失败P3!");
		}
		$mysql="update ".DBPREFIX."web_agents_data set VB_R_Bet=".$_REQUEST['VB_R_SO'].",VB_R_Scene=".$_REQUEST['VB_R_SC'].",VB_OU_Bet=".$_REQUEST['VB_OU_SO'].",VB_OU_Scene=".$_REQUEST['VB_OU_SC'].",VB_RE_Bet=".$_REQUEST['VB_RE_SO'].",VB_RE_Scene=".$_REQUEST['VB_RE_SC'].",VB_ROU_Bet=".$_REQUEST['VB_ROU_SO'].",VB_ROU_Scene=".$_REQUEST['VB_ROU_SC'].",VB_EO_Bet=".$_REQUEST['VB_EO_SO'].",VB_EO_Scene=".$_REQUEST['VB_EO_SC'].",VB_M_Bet=".$_REQUEST['VB_M_SO'].",VB_M_Scene=".$_REQUEST['VB_M_SC'].",VB_PD_Bet=".$_REQUEST['VB_PD_SO'].",VB_PD_Scene=".$_REQUEST['VB_PD_SC'].",VB_P_Bet=".$_REQUEST['VB_P_SO'].",VB_P_Scene=".$_REQUEST['VB_P_SC'].",VB_PR_Bet=".$_REQUEST['VB_PR_SO'].",VB_PR_Scene=".$_REQUEST['VB_PR_SC'].",VB_P3_Bet=".$_REQUEST['VB_P3_SO'].",VB_P3_Scene=".$_REQUEST['VB_P3_SC']." where ID='$parents_id'";
		mysqli_query($dbLink,$mysql) or die ("VB操作失败1!");
		$loginfo='详细设置'.$Title.':'.$username.' 排球详细设置成功';
		echo "<Script Language=javascript>self.location='user_set.php?uid=$uid&lv=$lv&parents_id=$parents_id&name=$name&langx=$langx';</script>";		
		break;
	case "OP":
		if($_REQUEST['OP_R_SO']<$brbet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_R_Bet=".$_REQUEST['OP_R_SO']." where  $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_R_SC']<$brscene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_R_Scene=".$_REQUEST['OP_R_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		if($_REQUEST['OP_OU_SO']<$boubet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_OU_Bet=".$_REQUEST['OP_OU_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_OU_SC']<$bouscene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_OU_Scene=".$_REQUEST['OP_OU_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		if($_REQUEST['OP_RE_SO']<$brebet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_RE_Bet=".$_REQUEST['OP_RE_SO']." where  $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_RE_SC']<$brescene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_RE_Scene=".$_REQUEST['OP_RE_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		
		if($_REQUEST['OP_ROU_SO']<$broubet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_ROU_Bet=".$_REQUEST['OP_ROU_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_ROU_SC']<$brouscene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_ROU_Scene=".$_REQUEST['OP_ROU_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		
		if($_REQUEST['OP_EO_SO']<$beobet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_EO_Bet=".$_REQUEST['OP_EO_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_EO_SC']<$beoscene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_EO_Scene=".$_REQUEST['OP_EO_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		
		if($_REQUEST['OP_M_SO']<$bmbet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_M_Bet=".$_REQUEST['OP_M_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_M_SC']<$bmscene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_M_Scene=".$_REQUEST['OP_M_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		
		if($_REQUEST['OP_PD_SO']<$bpdbet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_PD_Bet=".$_REQUEST['OP_PD_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_PD_SC']<$bpdscene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_PD_Scene=".$_REQUEST['OP_PD_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		
		if($_REQUEST['OP_T_SO']<$btbet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_T_Bet=".$_REQUEST['OP_T_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_T_SC']<$btscene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_T_Scene=".$_REQUEST['OP_T_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		
		if($_REQUEST['OP_F_SO']<$bfbet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_F_Bet=".$_REQUEST['OP_F_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_F_SC']<$bfscene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_F_Scene=".$_REQUEST['OP_F_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		
		if($_REQUEST['OP_P_SO']<$bpbet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_P_Bet=".$_REQUEST['OP_P_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_P_SC']<$bpscene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_P_Scene=".$_REQUEST['OP_P_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		
		if($_REQUEST['OP_PR_SO']<$bprbet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_PR_Bet=".$_REQUEST['OP_PR_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_PR_SC']<$bprscene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_PR_Scene=".$_REQUEST['OP_PR_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		
		if($_REQUEST['OP_P3_SO']<$bp3bet){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_P3_Bet=".$_REQUEST['OP_P3_SO']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败1!");
		}
		if($_REQUEST['OP_P3_SC']<$bp3scene){
		    $mysql="update ".DBPREFIX."web_agents_data set OP_P3_Scene=".$_REQUEST['OP_P3_SC']." where $agname";
			mysqli_query($dbLink,$mysql) or die ("OP代理操作失败2!");
		}
		$mysql="update ".DBPREFIX."web_agents_data set OP_R_Bet=".$_REQUEST['OP_R_SO'].",OP_R_Scene=".$_REQUEST['OP_R_SC'].",OP_OU_Bet=".$_REQUEST['OP_OU_SO'].",OP_OU_Scene=".$_REQUEST['OP_OU_SC'].",OP_RE_Bet=".$_REQUEST['OP_RE_SO'].",OP_RE_Scene=".$_REQUEST['OP_RE_SC'].",OP_ROU_Bet=".$_REQUEST['OP_ROU_SO'].",OP_ROU_Scene=".$_REQUEST['OP_ROU_SC'].",OP_EO_Bet=".$_REQUEST['OP_EO_SO'].",OP_EO_Scene=".$_REQUEST['OP_EO_SC'].",OP_M_Bet=".$_REQUEST['OP_M_SO'].",OP_M_Scene=".$_REQUEST['OP_M_SC'].",OP_PD_Bet=".$_REQUEST['OP_PD_SO'].",OP_PD_Scene=".$_REQUEST['OP_PD_SC'].",OP_T_Bet=".$_REQUEST['OP_T_SO'].",OP_T_Scene=".$_REQUEST['OP_T_SC'].",OP_F_Bet=".$_REQUEST['OP_F_SO'].",OP_F_Scene=".$_REQUEST['OP_F_SC'].",OP_P_Bet=".$_REQUEST['OP_P_SO'].",OP_P_Scene=".$_REQUEST['OP_P_SC'].",OP_PR_Bet=".$_REQUEST['OP_PR_SO'].",OP_PR_Scene=".$_REQUEST['OP_PR_SC'].",OP_P3_Bet=".$_REQUEST['OP_P3_SO'].",OP_P3_Scene=".$_REQUEST['OP_P3_SC']." where ID='$parents_id'";
		mysqli_query($dbLink,$mysql) or die ("OP操作失败1!");
		$loginfo='详细设置'.$Title.':'.$username.' 其他详细设置成功';
		echo "<Script Language=javascript>self.location='user_set.php?uid=$uid&lv=$lv&parents_id=$parents_id&name=$name&langx=$langx';</script>";
		break;
	}	
}

function turn_rate($start_rate,$rate_split,$end_rate,$sel_rate){
	$turn_rate='';
	echo $sel_rate;
	echo $end_rate;
	for($i=$start_rate;$i<$end_rate+$rate_split;$i+=$rate_split){
		if ($turn_rate==''){
			$turn_rate='<option>'.$i.'</option>';
		}else if($i==$sel_rate){
			$turn_rate=$turn_rate.'<option selected>'.$i.'</option>';
		}else{
			$turn_rate=$turn_rate.'<option>'.$i.'</option>';
		}
	}
	return $turn_rate;
}
?>
<script>var gtype_arr = new Array('FT','BK','BS','TN','VB','OP');</script>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">
    input.za_text {width: auto;}
</style> 
<script src="../../../js/agents/jquery.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JAVASCRIPT">

var formname1='';

function getfname(eOBJ) {formname1=eOBJ;}

function fkbkreset() {
	if(formname1!='')
	formname1.reset();
}

function count_so(SC,SO)
{
  b=eval(SC.value)/2;
  SO.value=b;
}
function CheckKey(){
	if(event.keyCode < 48 || event.keyCode > 57){alert("僅能輸入數字 !!"); return false;}
}
</SCRIPT>
</head>
<body onBeforeUnload="fkbkreset()" >
<dl class="main-nav">
    <dt><?php echo $Title.$Mem_Details.$Mem_Settings ?></dt>
    <dd><?php echo $Mem_Account?>:<?php echo $username?> -- <?php echo $Mem_Name?>:<?php echo $alias?>
        -- <a href="user_browse.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&userlv=<?php echo $userlv?>&langx=<?php echo $langx?>"><?php echo $Return_Back?></a>
    </dd>
</dl>
<div class="main-ui">
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab_ed">
        <form name="FT" method="post" action="">
            <tr  >
                <td width="70">足球</td>
                <td width='57'>讓球</td>
                <td width='57'>大小</td>
                <td width='57'>滾球讓球</td>
                <td width="57">滾球大小</td>
                <td width="57">單雙</td>
                <td width="57">独赢</td>
                <td width="57">滾球独赢</td>
                <td width="57">波膽</td>
                <td width="57">總入球</td>
                <td width="57">半全場</td>
                <td width="57">标准過關</td>
                <td width="57">让球過關</td>
                <td width="57"><?php echo $Rel_Parlay ?></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单场限额</td>
                <td ><input name=FT_R_SC type="text" value="<?php echo $row['FT_R_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_R_SC,document.FT.FT_R_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_OU_SC type="text" value="<?php echo $row['FT_OU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_OU_SC,document.FT.FT_OU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_RE_SC type="text" value="<?php echo $row['FT_RE_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_RE_SC,document.FT.FT_RE_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_ROU_SC type="text" value="<?php echo $row['FT_ROU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_ROU_SC,document.FT.FT_ROU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_EO_SC type="text" value="<?php echo $row['FT_EO_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_EO_SC,document.FT.FT_EO_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_M_SC type="text" value="<?php echo $row['FT_M_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_M_SC,document.FT.FT_M_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_RM_SC type="text" value="<?php echo $row['FT_RM_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_RM_SC,document.FT.FT_RM_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_PD_SC type="text" value="<?php echo $row['FT_PD_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_PD_SC,document.FT.FT_PD_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_T_SC type="text" value="<?php echo $row['FT_T_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_T_SC,document.FT.FT_T_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_F_SC type="text" value="<?php echo $row['FT_F_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_F_SC,document.FT.FT_F_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_P_SC type="text" value="<?php echo $row['FT_P_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_P_SC,document.FT.FT_P_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_PR_SC type="text" value="<?php echo $row['FT_PR_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_PR_SC,document.FT.FT_PR_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FT_P3_SC type="text" value="<?php echo $row['FT_P3_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.FT.FT_P3_SC,document.FT.FT_P3_SO)" onKeyPress="return CheckKey();"></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单注限额</td>
                <td><input name=FT_R_SO type="text" value="<?php echo $row['FT_R_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_R_TC value=0>
                <td><input name=FT_OU_SO type="text" value="<?php echo $row['FT_OU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_OU_TC value=0>
                <td><input name=FT_RE_SO type="text" value="<?php echo $row['FT_RE_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_RE_TC value=0>
                <td><input name=FT_ROU_SO type="text" value="<?php echo $row['FT_ROU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_ROU_TC value=0>
                <td><input name=FT_EO_SO type="text" value="<?php echo $row['FT_EO_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_EO_TC value=0>
                <td><input name=FT_M_SO type="text" value="<?php echo $row['FT_M_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_M_TC value=0>
                <td><input name=FT_RM_SO type="text" value="<?php echo $row['FT_RM_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_RM_TC value=0>
                <td><input name=FT_PD_SO type="text" value="<?php echo $row['FT_PD_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_PD_TC value=0>
                <td><input name=FT_T_SO type="text" value="<?php echo $row['FT_T_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_T_TC value=0>
                <td><input name=FT_F_SO type="text" value="<?php echo $row['FT_F_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_F_TC value=0>
                <td><input name=FT_P_SO type="text" value="<?php echo $row['FT_P_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_P_TC value=0>
                <td><input name=FT_PR_SO type="text" value="<?php echo $row['FT_PR_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_PR_TC value=0>
                <td><input name=FT_P3_SO type="text" value="<?php echo $row['FT_P3_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FT_P3_TC value=0>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">&nbsp;</td>
                <td colspan="13" ><input type="submit" name="ft_ch_ok" value="確定" class="za_button" onClick="getfname(document.FT)"></td>
            </tr>
            <input type=hidden name=active value="edit_conf">
            <input type=hidden name=gtype value="FT">
            <input type=hidden name=id value="<?php echo $id?>">
            <input type=hidden name=parents_id value="<?php echo $parents_id?>">
            <input type=hidden name=lv value="<?php echo $lv?>">
        </form>
    </table>
    <BR>
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab_ed">
        <form name="BK" method="post" action="">
            <tr>
                <td width="70">篮球</td>
                <td width='57'>讓球</td>
                <td width='57'>大小</td>
                <td width='57'>滾球讓球</td>
                <td width="57">滾球大小</td>
                <td width="57">單雙</td>
                <td width="57">讓球過關</td>
                <td width="57"><?php echo $Rel_Parlay ?></td>
                <td width="57">冠军</td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单场限额</td>
                <td ><input name=BK_R_SC type="text" value="<?php echo $row['BK_R_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BK.BK_R_SC,document.BK.BK_R_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BK_OU_SC type="text" value="<?php echo $row['BK_OU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BK.BK_OU_SC,document.BK.BK_OU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BK_RE_SC type="text" value="<?php echo $row['BK_RE_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BK.BK_RE_SC,document.BK.BK_RE_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BK_ROU_SC type="text" value="<?php echo $row['BK_ROU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BK.BK_ROU_SC,document.BK.BK_ROU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BK_EO_SC type="text" value="<?php echo $row['BK_EO_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BK.BK_EO_SC,document.BK.BK_EO_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BK_PR_SC type="text" value="<?php echo $row['BK_PR_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BK.BK_PR_SC,document.BK.BK_PR_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BK_P3_SC type="text" value="<?php echo $row['BK_P3_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BK.BK_P3_SC,document.BK.BK_P3_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=FS_FS_SC type="text" value="<?php echo $row['FS_FS_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BK.FS_FS_SC,document.BK.FS_FS_SO)" onKeyPress="return CheckKey();"></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单注限额</td>
                <td><input name=BK_R_SO type="text" value="<?php echo $row['BK_R_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BK_R_TC value=0>
                <td><input name=BK_OU_SO type="text" value="<?php echo $row['BK_OU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BK_OU_TC value=0>
                <td><input name=BK_RE_SO type="text" value="<?php echo $row['BK_RE_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BK_RE_TC value=0>
                <td><input name=BK_ROU_SO type="text" value="<?php echo $row['BK_ROU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BK_ROU_TC value=0>
                <td><input name=BK_EO_SO type="text" value="<?php echo $row['BK_EO_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BK_EO_TC value=0>
                <td><input name=BK_PR_SO type="text" value="<?php echo $row['BK_PR_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BK_PR_TC value=0>
                <td><input name=BK_P3_SO type="text" value="<?php echo $row['BK_P3_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BK_P3_TC value=0>
                <td><input name=FS_FS_SO type="text" value="<?php echo $row['FS_FS_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=FS_FS_TC value=0>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">&nbsp;</td>
                <td colspan="8" ><input type="submit" name="bk_ch_ok" value="確定" class="za_button" onClick="getfname(document.BK)"></td>
            </tr>
            <input type=hidden name=active value="edit_conf">
            <input type=hidden name=gtype value="BK">
            <input type=hidden name=id value="<?php echo $id?>">
            <input type=hidden name=parents_id value="<?php echo $parents_id?>">
            <input type=hidden name=lv value="<?php echo $lv?>">
        </form>
    </table>
    <BR>
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab_ed">
        <form name="BS" method="post" action="">
            <tr >
                <td width="70">棒球</td>
                <td width='57'>讓球</td>
                <td width='57'>大小</td>
                <td width='57'>滾球讓球</td>
                <td width="57">滾球大小</td>
                <td width="57">單雙</td>
                <td width="57">独赢</td>
                <td width="57">波膽</td>
                <td width="57">總入球</td>
                <td width="57">標準過關</td>
                <td width="57">讓球過關</td>
                <td width="57"><?php echo $Rel_Parlay ?></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单场限额</td>
                <td ><input name=BS_R_SC type="text" value="<?php echo $row['BS_R_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_R_SC,document.BS.BS_R_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BS_OU_SC type="text" value="<?php echo $row['BS_OU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_OU_SC,document.BS.BS_OU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BS_RE_SC type="text" value="<?php echo $row['BS_RE_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_RE_SC,document.BS.BS_RE_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BS_ROU_SC type="text" value="<?php echo $row['BS_ROU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_ROU_SC,document.BS.BS_ROU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BS_EO_SC type="text" value="<?php echo $row['BS_EO_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_EO_SC,document.BS.BS_EO_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BS_M_SC type="text" value="<?php echo $row['BS_M_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_M_SC,document.BS.BS_M_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BS_PD_SC type="text" value="<?php echo $row['BS_PD_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_PD_SC,document.BS.BS_PD_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BS_T_SC type="text" value="<?php echo $row['BS_T_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_T_SC,document.BS.BS_T_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BS_P_SC type="text" value="<?php echo $row['BS_P_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_P_SC,document.BS.BS_P_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BS_PR_SC type="text" value="<?php echo $row['BS_PR_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_PR_SC,document.BS.BS_PR_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=BS_P3_SC type="text" value="<?php echo $row['BS_P3_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.BS.BS_P3_SC,document.BS.BS_P3_SO)" onKeyPress="return CheckKey();"></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单注限额</td>
                <td><input name=BS_R_SO type="text" value="<?php echo $row['BS_R_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_R_TC value=0>
                <td><input name=BS_OU_SO type="text" value="<?php echo $row['BS_OU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_OU_TC value=0>
                <input type=hidden name=BS_VR_TC value=0>
                <input type=hidden name=BS_VOU_TC value=0>
                <td><input name=BS_RE_SO type="text" value="<?php echo $row['BS_RE_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_RE_TC value=0>
                <td><input name=BS_ROU_SO type="text" value="<?php echo $row['BS_ROU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_ROU_TC value=0>
                <input type=hidden name=BS_VRE_TC value=0>
                <input type=hidden name=BS_VROU_TC value=0>
                <td><input name=BS_EO_SO type="text" value="<?php echo $row['BS_EO_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_EO_TC value=0>
                <input type=hidden name=BS_1X2_TC value=0>
                <td><input name=BS_M_SO type="text" value="<?php echo $row['BS_M_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_M_TC value=0>
                <td><input name=BS_PD_SO type="text" value="<?php echo $row['BS_PD_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_PD_TC value=0>
                <td><input name=BS_T_SO type="text" value="<?php echo $row['BS_T_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_T_TC value=0>
                <input type=hidden name=BS_F_TC value=0>
                <td><input name=BS_P_SO type="text" value="<?php echo $row['BS_P_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_P_TC value=0>
                <td><input name=BS_PR_SO type="text" value="<?php echo $row['BS_PR_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_PR_TC value=0>
                <td><input name=BS_P3_SO type="text" value="<?php echo $row['BS_P3_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=BS_P3_TC value=0>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">&nbsp;</td>
                <td colspan="11"><input type="submit" name="bs_ch_ok" value="確定" class="za_button" onClick="getfname(document.BS)"></td>
            </tr>
            <input type=hidden name=active value="edit_conf">
            <input type=hidden name=gtype value="BS">
            <input type=hidden name=id value="<?php echo $id?>">
            <input type=hidden name=parents_id value="<?php echo $parents_id?>">
            <input type=hidden name=lv value="<?php echo $lv?>">
        </form>
    </table>
    <BR>
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab_ed">
        <form name="TN" method="post" action="">
            <tr >
                <td width="70">网球</td>
                <td width='57'>讓球</td>
                <td width='57'>大小</td>
                <td width='57'>滾球讓球</td>
                <td width="57">滾球大小</td>
                <td width="57">單雙</td>
                <td width="57">独赢</td>
                <td width="57">波膽</td>
                <td width="57">標準過關</td>
                <td width="57">讓球過關</td>
                <td width="57"><?php echo $Rel_Parlay ?></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单场限额</td>
                <td ><input name=TN_R_SC type="text" value="<?php echo $row['TN_R_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.TN.TN_R_SC,document.TN.TN_R_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=TN_OU_SC type="text" value="<?php echo $row['TN_OU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.TN.TN_OU_SC,document.TN.TN_OU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=TN_RE_SC type="text" value="<?php echo $row['TN_RE_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.TN.TN_RE_SC,document.TN.TN_RE_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=TN_ROU_SC type="text" value="<?php echo $row['TN_ROU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.TN.TN_ROU_SC,document.TN.TN_ROU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=TN_EO_SC type="text" value="<?php echo $row['TN_EO_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.TN.TN_EO_SC,document.TN.TN_EO_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=TN_M_SC type="text" value="<?php echo $row['TN_M_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.TN.TN_M_SC,document.TN.TN_M_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=TN_PD_SC type="text" value="<?php echo $row['TN_PD_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.TN.TN_PD_SC,document.TN.TN_PD_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=TN_P_SC type="text" value="<?php echo $row['TN_P_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.TN.TN_P_SC,document.TN.TN_P_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=TN_PR_SC type="text" value="<?php echo $row['TN_PR_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.TN.TN_PR_SC,document.TN.TN_PR_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=TN_P3_SC type="text" value="<?php echo $row['TN_P3_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.TN.TN_P3_SC,document.TN.TN_P3_SO)" onKeyPress="return CheckKey();"></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单注限额</td>
                <td><input name=TN_R_SO type="text" value="<?php echo $row['TN_R_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=TN_R_TC value=0>
                <td><input name=TN_OU_SO type="text" value="<?php echo $row['TN_OU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=TN_OU_TC value=0>
                <td><input name=TN_RE_SO type="text" value="<?php echo $row['TN_RE_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=TN_RE_TC value=0>
                <td><input name=TN_ROU_SO type="text" value="<?php echo $row['TN_ROU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=TN_ROU_TC value=0>
                <td><input name=TN_EO_SO type="text" value="<?php echo $row['TN_EO_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=TN_EO_TC value=0>
                <td><input name=TN_M_SO type="text" value="<?php echo $row['TN_M_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=TN_M_TC value=0>
                <td><input name=TN_PD_SO type="text" value="<?php echo $row['TN_PD_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=TN_PD_TC value=0>
                <td><input name=TN_P_SO type="text" value="<?php echo $row['TN_P_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=TN_P_TC value=0>
                <td><input name=TN_PR_SO type="text" value="<?php echo $row['TN_PR_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=TN_PR_TC value=0>
                <td><input name=TN_P3_SO type="text" value="<?php echo $row['TN_P3_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=TN_P3_TC value=0>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">&nbsp;</td>
                <td colspan="10" ><input type="submit" name="tn_ch_ok" value="確定" class="za_button" onClick="getfname(document.TN)"></td>
            </tr>
            <input type=hidden name=active value="edit_conf">
            <input type=hidden name=gtype value="TN">
            <input type=hidden name=id value="<?php echo $id?>">
            <input type=hidden name=parents_id value="<?php echo $parents_id?>">
            <input type=hidden name=lv value="<?php echo $lv?>">
        </form>
    </table>
    <BR>
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab_ed">
        <form name="VB" method="post" action="">
            <tr >
                <td width="70">排球</td>
                <td width='57'>讓球</td>
                <td width='57'>大小</td>
                <td width='57'>滾球讓球</td>
                <td width="57">滾球大小</td>
                <td width="57">單雙</td>
                <td width="57">独赢</td>
                <td width="57">波膽</td>
                <td width="57">標準過關</td>
                <td width="57">讓球過關</td>
                <td width="57"><?php echo $Rel_Parlay ?></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单场限额</td>
                <td ><input name=VB_R_SC type="text" value="<?php echo $row['VB_R_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.VB.VB_R_SC,document.VB.VB_R_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=VB_OU_SC type="text" value="<?php echo $row['VB_OU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.VB.VB_OU_SC,document.VB.VB_OU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=VB_RE_SC type="text" value="<?php echo $row['VB_RE_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.VB.VB_RE_SC,document.VB.VB_RE_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=VB_ROU_SC type="text" value="<?php echo $row['VB_ROU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.VB.VB_ROU_SC,document.VB.VB_ROU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=VB_EO_SC type="text" value="<?php echo $row['VB_EO_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.VB.VB_EO_SC,document.VB.VB_EO_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=VB_M_SC type="text" value="<?php echo $row['VB_M_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.VB.VB_M_SC,document.VB.VB_M_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=VB_PD_SC type="text" value="<?php echo $row['VB_PD_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.VB.VB_PD_SC,document.VB.VB_PD_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=VB_P_SC type="text" value="<?php echo $row['VB_P_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.VB.VB_P_SC,document.VB.VB_P_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=VB_PR_SC type="text" value="<?php echo $row['VB_PR_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.VB.VB_PR_SC,document.VB.VB_PR_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=VB_P3_SC type="text" value="<?php echo $row['VB_P3_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.VB.VB_P3_SC,document.VB.VB_P3_SO)" onKeyPress="return CheckKey();"></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单注限额</td>
                <td><input name=VB_R_SO type="text" value="<?php echo $row['VB_R_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=VB_R_TC value=0>
                <td><input name=VB_OU_SO type="text" value="<?php echo $row['VB_OU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=VB_OU_TC value=0>
                <td><input name=VB_RE_SO type="text" value="<?php echo $row['VB_RE_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=VB_RE_TC value=0>
                <td><input name=VB_ROU_SO type="text" value="<?php echo $row['VB_ROU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=VB_ROU_TC value=0>
                <td><input name=VB_EO_SO type="text" value="<?php echo $row['VB_EO_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=VB_EO_TC value=0>
                <td><input name=VB_M_SO type="text" value="<?php echo $row['VB_M_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=VB_M_TC value=0>
                <td><input name=VB_PD_SO type="text" value="<?php echo $row['VB_PD_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=VB_PD_TC value=0>
                <td><input name=VB_P_SO type="text" value="<?php echo $row['VB_P_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=VB_P_TC value=0>
                <td><input name=VB_PR_SO type="text" value="<?php echo $row['VB_PR_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=VB_PR_TC value=0>
                <td><input name=VB_P3_SO type="text" value="<?php echo $row['VB_P3_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=VB_P3_TC value=0>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">&nbsp;</td>
                <td colspan="10" ><input type="submit" name="tn_ch_ok" value="確定" class="za_button" onClick="getfname(document.VB)"></td>
            </tr>
            <input type=hidden name=active value="edit_conf">
            <input type=hidden name=gtype value="VB">
            <input type=hidden name=id value="<?php echo $id?>">
            <input type=hidden name=parents_id value="<?php echo $parents_id?>">
            <input type=hidden name=lv value="<?php echo $lv?>">
        </form>
    </table>
    <br>
    <table border="0" cellpadding="0" cellspacing="1" class="m_tab_ed">
        <form name="OP" method="post" action="">
            <tr  >
                <td width="70">其它</td>
                <td width='57'>讓球</td>
                <td width='57'>大小</td>
                <td width='57'>滾球讓球</td>
                <td width="57">滾球大小</td>
                <td width="57">單雙</td>
                <td width="57">独赢</td>
                <td width="57">波膽</td>
                <td width="57">總入球</td>
                <td width="57">半全場</td>
                <td width="57">標準過關</td>
                <td width="57">讓球過關</td>
                <td width="57"><?php echo $Rel_Parlay ?></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单场限额</td>
                <td ><input name=OP_R_SC type="text" value="<?php echo $row['OP_R_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_R_SC,document.OP.OP_R_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_OU_SC type="text" value="<?php echo $row['OP_OU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_OU_SC,document.OP.OP_OU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_RE_SC type="text" value="<?php echo $row['OP_RE_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_RE_SC,document.OP.OP_RE_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_ROU_SC type="text" value="<?php echo $row['OP_ROU_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_ROU_SC,document.OP.OP_ROU_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_EO_SC type="text" value="<?php echo $row['OP_EO_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_EO_SC,document.OP.OP_EO_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_M_SC type="text" value="<?php echo $row['OP_M_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_M_SC,document.OP.OP_M_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_PD_SC type="text" value="<?php echo $row['OP_PD_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_PD_SC,document.OP.OP_PD_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_T_SC type="text" value="<?php echo $row['OP_T_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_T_SC,document.OP.OP_T_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_F_SC type="text" value="<?php echo $row['OP_F_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_F_SC,document.OP.OP_F_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_P_SC type="text" value="<?php echo $row['OP_P_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_P_SC,document.OP.OP_P_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_PR_SC type="text" value="<?php echo $row['OP_PR_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_PR_SC,document.OP.OP_PR_SO)" onKeyPress="return CheckKey();"></td>
                <td><input name=OP_P3_SC type="text" value="<?php echo $row['OP_P3_Scene']?>" size="5" maxlength="8" class="za_text" onKeyUp="count_so(document.OP.OP_P3_SC,document.OP.OP_P3_SO)" onKeyPress="return CheckKey();"></td>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">单注限额</td>
                <td><input name=OP_R_SO type="text" value="<?php echo $row['OP_R_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_R_TC value=0>
                <td><input name=OP_OU_SO type="text" value="<?php echo $row['OP_OU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_OU_TC value=0>
                <td><input name=OP_RE_SO type="text" value="<?php echo $row['OP_RE_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_RE_TC value=0>
                <td><input name=OP_ROU_SO type="text" value="<?php echo $row['OP_ROU_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_ROU_TC value=0>
                <td><input name=OP_EO_SO type="text" value="<?php echo $row['OP_EO_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_EO_TC value=0>
                <td><input name=OP_M_SO type="text" value="<?php echo $row['OP_M_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_M_TC value=0>
                <td><input name=OP_PD_SO type="text" value="<?php echo $row['OP_PD_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_PD_TC value=0>
                <td><input name=OP_T_SO type="text" value="<?php echo $row['OP_T_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_T_TC value=0>
                <td><input name=OP_F_SO type="text" value="<?php echo $row['OP_F_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_F_TC value=0>
                <td><input name=OP_P_SO type="text" value="<?php echo $row['OP_P_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_P_TC value=0>
                <td><input name=OP_PR_SO type="text" value="<?php echo $row['OP_PR_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_PR_TC value=0>
                <td><input name=OP_P3_SO type="text" value="<?php echo $row['OP_P3_Bet']?>" size="5" maxlength="8" class="za_text" onKeyPress="return CheckKey();"></td>
                <input type=hidden name=OP_P3_TC value=0>
            </tr>
            <tr class="m_cen" >
                <td align="right" class="m_ag_ed">&nbsp;</td>
                <td colspan="12" ><input type="submit" name="op_ch_ok" value="確定" class="za_button" onClick="getfname(document.OP)"></td>
            </tr>
            <input type=hidden name=active value="edit_conf">
            <input type=hidden name=gtype value="OP">
            <input type=hidden name=id value="<?php echo $id?>">
            <input type=hidden name=parents_id value="<?php echo $parents_id?>">
            <input type=hidden name=lv value="<?php echo $lv?>">
        </form>
    </table>
    <br> 
</div>
<script>
	function fast_show(e){
		//  限額
		$("input[name='"+e+"_SC']").keyup(function(){
			$("form[name='"+e+"'] input[name$='SC']").val($(this).val());
		});
		$("input[name='"+e+"_SO']").keyup(function(){
			if(parseInt($(this).val()) > parseInt($("input[name='"+e+"_SC']").val())){
				alert("单注限额不可大於单场限额");
			}else{
				$("form[name='"+e+"'] input[name$='SO']").val($(this).val());
			}
		});
		$("input[name='"+e+"_TC']").keyup(function(){
			$("form[name='"+e+"'] input[name$='TC']").val($(this).val());
		});
		//	退水
		$("select[name='"+e+"_LINE_1']").change(function(i){
//			$("form[name='"+e+"'] select[name$='WAR_1']").val($(this).val());
			$("."+e+"_SMALL_1").val($(this).val());
		});
		$("select[name='"+e+"_LINE_2']").change(function(i){
//			$("form[name='"+e+"'] select[name$='WAR_2']").val($(this).val());
			$("."+e+"_SMALL_2").val($(this).val());
		});
		$("select[name='"+e+"_LINE_3']").change(function(i){
//			$("form[name='"+e+"'] select[name$='WAR_3']").val($(this).val());
			$("."+e+"_SMALL_3").val($(this).val());
		});
		$("select[name='"+e+"_LINE_4']").change(function(i){
//			$("form[name='"+e+"'] select[name$='WAR_4']").val($(this).val());
			$("."+e+"_SMALL_4").val($(this).val());
		});
		$("select[name='"+e+"_LINE_BIG']").change(function(i){
			$("."+e+"_BIG").val($(this).val());
			$(".SP_BIG").val($(this).val());
//			$("form[name='"+e+"'] select[name*='P']").val($(this).val());
//			$("form[name='"+e+"'] select[name*='M']").val($(this).val());
//			$("form[name='"+e+"'] select[name*='PD']").val($(this).val());
//			$("form[name='"+e+"'] select[name*='T']").val($(this).val());
//			$("form[name='"+e+"'] select[name*='F']").val($(this).val());
//			$("form[name='"+e+"'] select[name*='CS']").val($(this).val());
		});
	}
	
	$(document).ready(function(){
		for(i=0;i<gtype_arr.length;i++){
			fast_show(gtype_arr[i]);
		}	
	});
</script>
</body>
</html>
<?php
$ip_addr = get_ip();
$mysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$agent',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
mysqli_query($dbMasterLink,$mysql);
?>