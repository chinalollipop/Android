<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require ("../include/address.mem.php");
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

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
$result = mysqli_query($dbLink,$sql);
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
<HEAD><TITLE>棒球變數值</TITLE>
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
parent.retime=0;
<?php 
switch ($rtype){
case "r":
	$mysql="select MID,M_Date,M_Time,M_Type,MB_MID,TG_MID,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeR,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Dime_H,TG_Dime_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,T_Show,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BE' and `M_Date` >'$m_date' and S_Show=1 and $mb_team!='' and Open=1 ".$date." order by M_Start,MID";
	//echo $mysql;exit;
	$result = mysqli_query($dbLink, $mysql);
	$cou_num=mysqli_num_rows($result);
	$page_size=60;
	$page_count=ceil($cou_num/$page_size);
	$offset=$page_no*60;	
	$mysql=$mysql."  limit $offset,$num";
	//echo $mysql;
	$result = mysqli_query($dbLink, $mysql);
	$cou=mysqli_num_rows($result);
	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_RH','ior_RC','ratio_o','ratio_u','ior_OUH','ior_OUC','ior_MH','ior_MC','ior_MN','str_odd','str_even','ior_EOO','ior_EOE','hgid','hstrong','hratio','ior_HRH','ior_HRC','hratio_o','hratio_u','ior_HOUH','ior_HOUC','ior_HMH','ior_HMC','ior_HMN','more','eventid','hot','play');";	
	echo "parent.str_renew = '$manual_update';\n";
	echo "parent.game_more=1;\n";
	echo "parent.str_more='$more';\n";
	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	while ($row=mysqli_fetch_assoc($result)){
	    $MB_Win_Rate=change_rate($open,$row["MB_Win_Rate"]);
		$TG_Win_Rate=change_rate($open,$row["TG_Win_Rate"]);
		$M_Flat_Rate=change_rate($open,$row["M_Flat_Rate"]);
		$MB_LetB_Rate=change_rate($open,$row['MB_LetB_Rate']);
		$TG_LetB_Rate=change_rate($open,$row['TG_LetB_Rate']);
		$MB_Dime_Rate=change_rate($open,$row["MB_Dime_Rate"]);
		$TG_Dime_Rate=change_rate($open,$row["TG_Dime_Rate"]);				
		$S_Single_Rate=change_rate($open,$row['S_Single_Rate']);
		$S_Double_Rate=change_rate($open,$row['S_Double_Rate']);
		
		$MB_Win_Rate_hr=change_rate($open,$row["MB_Win_Rate_H"]);
		$TG_Win_Rate_hr=change_rate($open,$row["TG_Win_Rate_H"]);
		$M_Flat_Rate_hr=change_rate($open,$row["M_Flat_Rate_H"]);
		$MB_LetB_Rate_hr=change_rate($open,$row['MB_LetB_Rate_H']);
		$TG_LetB_Rate_hr=change_rate($open,$row['TG_LetB_Rate_H']);
		$MB_Dime_Rate_hr=change_rate($open,$row["MB_Dime_Rate_H"]);
		$TG_Dime_Rate_hr=change_rate($open,$row["TG_Dime_Rate_H"]);	
		
		if ($row['PD_Show']==1 and $row['T_Show']==1){
		    $show=2;
		}else{
		    $show=0;
		}
		if($S_Single_Rate){
			$o1=$o;	
		}else{
			$o1='';	
		}
		if($S_Double_Rate){
			$e1=$e;	
		}else{
			$e1='';	
		}		
		$m_date=strtotime($row['M_Date']);
	    $dates=date("m-d",$m_date);	
		if ($row['M_Type']==1){
			echo "parent.GameFT[$K]=new Array('$row[MID]','$dates<br>$row[M_Time]<br><font color=red>Running Ball</font>','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowType]','$row[M_LetB]','$MB_LetB_Rate','$TG_LetB_Rate','$row[MB_Dime]','$row[TG_Dime]','$TG_Dime_Rate','$MB_Dime_Rate','$MB_Win_Rate','$TG_Win_Rate','$M_Flat_Rate','$o1','$e1','$S_Single_Rate','$S_Double_Rate','0','$row[ShowTypeH]','$row[M_LetB_H]','$MB_LetB_Rate_hr','$TG_LetB_Rate_hr','$row[MB_Dime_H]','$row[TG_Dime_H]','$TG_Dime_Rate_hr','$MB_Dime_Rate_hr','$MB_Win_Rate_hr','$TG_Win_Rate_hr','$M_Flat_Rate_hr','$show','$row[Eventid]','$row[Hot]','$row[Play]');\n";
		}else{
			echo "parent.GameFT[$K]=new Array('$row[MID]','$dates<br>$row[M_Time]','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowType]','$row[M_LetB]','$MB_LetB_Rate','$TG_LetB_Rate','$row[MB_Dime]','$row[TG_Dime]','$TG_Dime_Rate','$MB_Dime_Rate','$MB_Win_Rate','$TG_Win_Rate','$M_Flat_Rate','$o1','$e1','$S_Single_Rate','$S_Double_Rate','0','$row[ShowTypeH]','$row[M_LetB_H]','$MB_LetB_Rate_hr','$TG_LetB_Rate_hr','$row[MB_Dime_H]','$row[TG_Dime_H]','$TG_Dime_Rate_hr','$MB_Dime_Rate_hr','$MB_Win_Rate_hr','$TG_Win_Rate_hr','$M_Flat_Rate_hr','$show','$row[Eventid]','$row[Hot]','$row[Play]');\n";
		}
	$K=$K+1;	
	}
	break;

case "p3":
	$mysql = "select MID,M_Date,M_Time,MB_MID,TG_MID,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeP,M_P_LetB,MB_P_LetB_Rate,TG_P_LetB_Rate,MB_P_Dime,TG_P_Dime,MB_P_Dime_Rate,TG_P_Dime_Rate from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BE' and `M_Date` >'$m_date' and PR_Show=1 and $mb_team<>'' and Open=1 ".$date." order by M_Start,MID";
	$result = mysqli_query($dbLink, $mysql);

	$cou=mysqli_num_rows($result);
	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_PRH','ior_PRC','ratio_o','ratio_u','ior_POUH','ior_POUC','gidm','par_minlimit','par_maxlimit');";
	echo "parent.retime=0;\n";
	echo "parent.t_page=1;\n";
	echo "parent.gamount=$cou;\n";
//echo $mysql;exit;
	while ($row=mysqli_fetch_assoc($result)){
	$MB_P_LetB_Rate=change_rate($open,$row['MB_P_LetB_Rate']);
	$TG_P_LetB_Rate=change_rate($open,$row['TG_P_LetB_Rate']);
	$MB_P_Dime_Rate=change_rate($open,$row['MB_P_Dime_Rate']);
	//echo $MB_P_Dime_Rate;exit;
	$TG_P_Dime_Rate=change_rate($open,$row['TG_P_Dime_Rate']);		
    $m_date=strtotime($row['M_Date']);
	$dates=date("m-d",$m_date);	
	if (strlen($row['M_Time'])==5){
		$pdate=$dates.'<br>0'.$row[M_Time];
	}else{
		$pdate=$dates.'<br>'.$row[M_Time];
	}
		echo "parent.GameFT[$K]= Array('$row[MID]','$pdate','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeP]','$row[M_P_LetB]','$MB_P_LetB_Rate','$TG_P_LetB_Rate','$row[MB_P_Dime]','$row[TG_P_Dime]','$MB_P_Dime_Rate','$MB_P_Dime_Rate','$row[MID]','3','10');\n";
		$K=$K+1;	
}
	break;
}
?>

function onLoad(){
	//if(parent.parent.mem_order.location == 'about:blank'){
	//	parent.parent.mem_order.location = '<?php //echo BROWSER_IP?>///app/member/select.php?uid=<?php //echo $uid?>//&langx=<?php //echo $langx?>//';
	//}
	if(parent.retime > 0)
		parent.retime_flag='Y';
	else
		parent.retime_flag='N';
	    parent.loading_var = 'N';
	if(parent.loading == 'N' && parent.ShowType != ''){
		parent.ShowGameList();
		//parent.body_browse.document.all.LoadLayer.style.display = 'none';
	}
}
function onUnLoad(){
	x = parent.body_browse.pageXOffset;
	y = parent.body_browse.pageYOffset;
	parent.body_browse.scroll(x,y);
	//obj_layer = parent.body_browse.document.getElementById('LoadLayer');
	//obj_layer.style.display = 'block';

}
// -->
window.defaultStatus="Wellcome................."
</script>
</head>
<body bgcolor="#FFFFFF" onLoad="onLoad();" onUnLoad="onUnLoad()">
	<img id=im0 width=0 height=0><img id=im1 width=0 height=0><img id=im2 width=0 height=0><img id=im3 width=0 height=0><img id=im4 width=0 height=0>
<img id=im5 width=0 height=0><img id=im6 width=0 height=0>
</body>
</html>
