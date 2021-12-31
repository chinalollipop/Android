<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
require ("../include/curl_http.php");
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$rtype=$_REQUEST['rtype'];
$league_id=$_REQUEST['league_id'];
$g_date=$_REQUEST['g_date'];
$page_no=$_REQUEST['page_no'];
require ("../include/traditional.$langx.inc.php");



if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$sql = "select OpenType,UserName,Money from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbMasterLink,$sql);

$row = mysqli_fetch_assoc($result);
$open    = $row['OpenType'];
$memname = $row['UserName'];
$credit  = $row['Money'];

if ($league_id==''){
	$num=60;
}else{
	$num=1024;
}
if($g_date=="ALL" or $g_date=="undefined" or $g_date==""){
   $date="";
}else{
   $date="and M_Date='$g_date'";
}
if ($page_no==''){
	$page_no=0;
}
$m_date=date('Y-m-d');
$K=0;
?>
<HEAD><TITLE>网球變數值</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<SCRIPT language=JavaScript>
<!--
parent.flash_ior_set='Y';
parent.minlimit_VAR='';
parent.maxlimit_VAR='';
parent.username='<?php echo $memname?>';

parent.code='人民幣(RMB)';
parent.uid='<?php echo $uid?>';

parent.ltype='3';
parent.str_even = '<?php echo $str_even?>';
parent.str_submit = '<?php echo $str_submit?>';
parent.str_reset = '<?php echo $str_reset?>';
parent.langx='<?php echo $langx?>';
parent.rtype='<?php echo $rtype?>';
parent.sel_lid='<?php echo $league_id?>';
parent.g_date = 'ALL';
<?php 
switch ($rtype){
case "r":
	$mysql = "select MID,M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate,TG_Win_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,MB_MID,TG_MID,ShowTypeR,S_Single_Rate,S_Double_Rate,M_Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TU' and `M_Date` >'$m_date' and S_Show=1 and $mb_team<>'' ".$date." order by m_start,mid";
	$result = mysqli_query($dbMasterLink, $mysql);

	$cou_num=mysqli_num_rows($result);
	$page_size=40;
	$page_count=ceil($cou_num/$page_size);
	$offset=$page_no*40;	
	$mysql=$mysql."  limit $offset,$num";
	$result = mysqli_query($dbMasterLink, $mysql);
	$cou=mysqli_num_rows($result);
	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_RH','ior_RC','ratio_o','ratio_u','ior_OUH','ior_OUC','ior_MH','ior_MC','ior_MN','str_odd','str_even','ior_EOO','ior_EOE','more','eventid','hot','play');";	
	echo "parent.retime=180;\n";	
	echo "parent.str_renew = '$second_auto_update';\n";
	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	
	while ($row=mysqli_fetch_array($result)){
	    $MB_Win_Rate=change_rate($open,$row['MB_Win_Rate']);
		$TG_Win_Rate=change_rate($open,$row['TG_Win_Rate']);
		$MB_Dime_Rate=change_rate($open,$row["MB_Dime_Rate"]);
		$TG_Dime_Rate=change_rate($open,$row["TG_Dime_Rate"]);				
		$MB_LetB_Rate=change_rate($open,$row['MB_LetB_Rate']);
		$TG_LetB_Rate=change_rate($open,$row['TG_LetB_Rate']);
		$S_Single_Rate=change_rate($open,$row['S_Single_Rate']);
		$S_Double_Rate=change_rate($open,$row['S_Double_Rate']);
		$m_date=strtotime($row['M_Date']);
	    $dates=date("m-d",$m_date);	
		if ($row['M_Type']==1){
			echo "parent.GameFT[$K]= Array('$row[MID]','$dates<br>$row[M_Time]<br><font style=background-color=red>Running Ball</font>','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeR]','$row[M_LetB]','$MB_LetB_Rate','$TG_LetB_Rate','$row[MB_Dime]','$row[TG_Dime]','$TG_Dime_Rate','$MB_Dime_Rate','$MB_Win_Rate','$TG_Win_Rate','0','$o','$e','$S_Single_Rate','$S_Double_Rate','0','0');\n";
		}else{
			echo "parent.GameFT[$K]= Array('$row[MID]','$dates<br>$row[M_Time]','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeR]','$row[M_LetB]','$MB_LetB_Rate','$TG_LetB_Rate','$row[MB_Dime]','$row[TG_Dime]','$TG_Dime_Rate','$MB_Dime_Rate','$MB_Win_Rate','$TG_Win_Rate','0','$o','$e','$S_Single_Rate','$S_Double_Rate','0','0');\n";
		}
	$K=$K+1;	
	}
	break;
case "pd":
	$mysql = "select MID,M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB2TG0,MB2TG1,MB3TG0,MB3TG1,MB3TG2,MB0TG2,MB1TG2,MB0TG3,MB1TG3,MB2TG3,ShowTypeR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TU' and `M_Date` >'$m_date' and PD_Show=1 and MB2TG1!=0 and $mb_team<>'' ".$date." order by m_start,mid";
	$result = mysqli_query($dbMasterLink, $mysql);

	$cou_num=mysqli_num_rows($result);
	$page_size=20;
	$page_count=ceil($cou_num/$page_size);
	$offset=$page_no*20;	
	$mysql=$mysql."  limit $offset,$num";
	$result = mysqli_query($dbMasterLink, $mysql);
	$cou=mysqli_num_rows($result);
	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3');";
	echo "parent.retime=0;\n";
	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";	
	while ($row=mysqli_fetch_array($result)){
	    $m_date=strtotime($row['M_Date']);
	    $dates=date("m-d",$m_date);	
		echo "parent.GameFT[$K]= Array('$row[MID]','$dates<br>$row[M_Time]','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeR]','$row[MB2TG0]','$row[MB2TG1]','$row[MB3TG0]','$row[MB3TG1]','$row[MB3TG2]','$row[MB0TG2]','$row[MB1TG2]','$row[MB0TG3]','$row[MB1TG3]','$row[MB2TG3]');\n";
		$K=$K+1;	
	}
	break;
case "p":
	$mysql = "select MID,M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_P_Win_Rate,TG_P_Win_Rate,MB_MID,TG_MID,ShowTypeP from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TU' and `M_Date` >'$m_date' and P_Show=1 and $mb_team<>'' order by m_start,mid";		
	$result = mysqli_query($dbMasterLink, $mysql);

	$cou=mysqli_num_rows($result);
	$page_size=20;
	$page_count=ceil($cou/$page_size);
	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_PRH','ior_PRC','ratio_o','ratio_u','ior_POUC','ior_POUH','gidm','par_minlimit','par_maxlimit');";	
	echo "parent.retime=0;\n";
	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	while ($row=mysqli_fetch_array($result)){
	    $m_date=strtotime($row['M_Date']);
	    $dates=date("m-d",$m_date);	
		$mb_team=str_replace("[$bzmb]","",$row['MB_Team']);
		if (strlen(ltrim($row['M_Time']))<=5){
			$pdate=$dates.'<br>0'.$row[M_Time];
		}else{
			$pdate=$dates.'<br>'.$row[M_Time];
		}
		$MB_P_Win_Rate=change_rate($open,$row['MB_P_Win_Rate']);
		$TG_P_Win_Rate=change_rate($open,$row['TG_P_Win_Rate']);
		echo "parent.GameFT[$K]= Array('$row[MID]','$pdate','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$mb_team','$row[TG_Team]','$row[ShowTypeP]','$MB_P_Win_Rate','$TG_P_Win_Rate');\n";
		$K=$K+1;	
	}
	break;

case "p3":
	$mysql = "select MID,M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_MID,TG_MID,ShowTypeP,M_P_LetB,MB_P_LetB_Rate,TG_P_LetB_Rate,MB_P_Dime,TG_P_Dime,MB_P_Dime_Rate,TG_P_Dime_Rate from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TU' and `M_Date` >'$m_date' and PR_Show=1 and $mb_team<>'' order by m_start,mid";		
	$result = mysqli_query($dbMasterLink, $mysql);

	$cou=mysqli_num_rows($result);
	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_PRH','ior_PRC','ratio_o','ratio_u','ior_POUC','ior_POUH','gidm','par_minlimit','par_maxlimit');";
	$page_size=20;
	$page_count=ceil($cou/$page_size);	
	echo "parent.retime=0;\n";
	echo "parent.t_page=$page_count;\n";	
	echo "parent.gamount=$cou;\n";
	while ($row=mysqli_fetch_array($result)){
	    $MB_PR_LetB_Rate=change_rate($open,$row['MB_P_LetB_Rate']);
	    $TG_PR_LetB_Rate=change_rate($open,$row['TG_P_LetB_Rate']);
	    $MB_PR_Dime_Rate=change_rate($open,$row['MB_P_Dime_Rate']);
	    $TG_PR_Dime_Rate=change_rate($open,$row['TG_P_Dime_Rate']);
	    $m_date=strtotime($row['M_Date']);
	    $dates=date("m-d",$m_date);			
		$M_letb=$row['M_PR_LetB'];
		$mb_team=trim($row['MB_Team']);		
		
		if (strlen($row['M_Time'])==5){
			$pdate=$dates.'<br>0'.$row[M_Time];
		}else{
			$pdate=$dates.'<br>'.$row[M_Time];
		}
		echo "parent.GameFT[$K]= Array('$row[MID]','$pdate','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeP]','$row[M_PR_LetB]','$MB_PR_LetB_Rate','$TG_PR_LetB_Rate','$row[MB_PR_Dime]','$row[TG_PR_Dime]','$MB_PR_Dime_Rate','$TG_PR_Dime_Rate','$row[MID]','3','10');\n";
		$K=$K+1;	
	}
	break;
}
?>
//

 function onLoad() {
  //if(parent.parent.mem_order.location == 'about:blank'){
	//	parent.parent.mem_order.location = '<?php //echo BROWSER_IP?>///app/member/select.php?uid=<?php //echo $uid?>//&langx=<?php //echo $langx?>//';
	//}
 if(parent.retime > 0)
   parent.retime_flag='Y';
  else
   parent.retime_flag='N';
  parent.loading_var = 'N';
  if(parent.loading == 'N' && parent.ShowType != '')
  {
   parent.ShowGameList();
   parent.body_browse.document.all.LoadLayer.style.display = 'none';
  }
 }
 
 function onUnLoad()
 {
  x = parent.body_browse.pageXOffset;
  y = parent.body_browse.pageYOffset;
  parent.body_browse.scroll(x,y);
  obj_layer = parent.body_browse.document.getElementById('LoadLayer');
  obj_layer.style.display = 'block';
 }
 
// -->
</script>
</head>
<body bgcolor="#FFFFFF" onLoad="onLoad()" onUnLoad="onUnLoad()">
</body>
</html>
