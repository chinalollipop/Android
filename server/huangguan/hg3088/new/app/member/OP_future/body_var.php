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
<HEAD><TITLE>其它變數值</TITLE>
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
	$mysql = "select MID,M_Date,M_Time,M_Type,MB_MID,TG_MID,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeR,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play from `".DBPREFIX."match_sports` where Type='OM' and `M_Date` >'$m_date' and S_Show=1 and $mb_team!='' ".$date." order by M_Start,MID";		
	$result = mysqli_query($dbMasterLink, $mysql);
	$cou_num=mysqli_num_rows($result);
	$page_size=60;
	$page_count=ceil($cou_num/$page_size);
	$offset=$page_no*60;	
	$mysql=$mysql."  limit $offset,$num;";
	$result = mysqli_query($dbMasterLink, $mysql);
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
		$MB_Dime_Rate=change_rate($open,$row["MB_Dime_Rate"]);
		$TG_Dime_Rate=change_rate($open,$row["TG_Dime_Rate"]);				
		$MB_LetB_Rate=change_rate($open,$row['MB_LetB_Rate']);
		$TG_LetB_Rate=change_rate($open,$row['TG_LetB_Rate']);
		$S_Single=change_rate($open,$row['S_Single_Rate']);
		$S_Double=change_rate($open,$row['S_Double_Rate']);
		
		$MB_Win_Rate_v=change_rate($open,$row["MB_Win_Rate_H"]);
		$TG_Win_Rate_v=change_rate($open,$row["TG_Win_Rate_H"]);
		$M_Flat_Rate_v=change_rate($open,$row["M_Flat_Rate_H"]);
		$MB_Dime_Rate_v=change_rate($open,$row["MB_Dime_Rate_H"]);
		$TG_Dime_Rate_v=change_rate($open,$row["TG_Dime_Rate_H"]);				
		$MB_LetB_Rate_v=change_rate($open,$row['MB_LetB_Rate_H']);
		$TG_LetB_Rate_v=change_rate($open,$row['TG_LetB_Rate_H']);
		
		if ($row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		    $show=3;
		}else if ($row['HPD_Show']==1 and $row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		    $show=4;
		}else{
		    $show=0;
		}
		$m_date=strtotime($row['M_Date']);
	    $dates=date("m-d",$m_date);	
		if ($row['M_Type']==1){
			echo "parent.GameFT[$K]= Array('$row[MID]','$dates<br>$row[M_Time]<br><font color=red>Running Ball</font>','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeR]','$row[M_LetB]','$MB_LetB_Rate','$TG_LetB_Rate','$row[MB_Dime]','$row[TG_Dime]','$TG_Dime_Rate','$MB_Dime_Rate','$MB_Win_Rate','$TG_Win_Rate','$M_Flat_Rate','$Odd','$Even','$S_Single','$S_Double','0','$row[ShowTypeHR]','$row[M_LetB_H]','$MB_LetB_Rate_v','$TG_LetB_Rate_v','$row[MB_Dime_H]','$row[TG_Dime_H]','$TG_Dime_Rate_v','$MB_Dime_Rate_v','$MB_Win_Rate_v','$TG_Win_Rate_v','$M_Flat_Rate_v','$show','$row[Eventid]','$row[Hot]','$row[Play]');\n";
		}else{
			echo "parent.GameFT[$K]= Array('$row[MID]','$dates<br>$row[M_Time]','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeR]','$row[M_LetB]','$MB_LetB_Rate','$TG_LetB_Rate','$row[MB_Dime]','$row[TG_Dime]','$TG_Dime_Rate','$MB_Dime_Rate','$MB_Win_Rate','$TG_Win_Rate','$M_Flat_Rate','$Odd','$Even','$S_Single','$S_Double','0','$row[ShowTypeHR]','$row[M_LetB_H]','$MB_LetB_Rate_v','$TG_LetB_Rate_v','$row[MB_Dime_H]','$row[TG_Dime_H]','$TG_Dime_Rate_v','$MB_Dime_Rate_v','$MB_Win_Rate_v','$TG_Win_Rate_v','$M_Flat_Rate_v','$show','$row[Eventid]','$row[Hot]','$row[Play]');\n";
		}
	$K=$K+1;	
	}
	break;
case "p3":
	$mysql = "select MID,M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_MID,TG_MID,ShowTypeP,MB_P_LetB_Rate,TG_P_LetB_Rate,M_P_LetB,MB_P_Dime,TG_P_Dime,MB_P_Dime_Rate,TG_P_Dime_Rate,S_P_Single_Rate,S_P_Double_Rate,MB_P_Win_Rate,TG_P_Win_Rate,M_P_Flat_Rate,ShowTypeHP,M_P_LetB_H,MB_P_LetB_Rate_H,TG_P_LetB_Rate_H,MB_P_Dime_H,TG_P_Dime_H,MB_P_Dime_Rate_H,TG_P_Dime_Rate_H,MB_P_Win_Rate_H,TG_P_Win_Rate_H,M_P_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show from `".DBPREFIX."match_sports` where Type='OM' and `M_Start` > now( ) AND `M_Date` ='$m_date'  and P3_Show=1 and $mb_team!='' order by M_Start,MID";
	$result = mysqli_query($dbMasterLink, $mysql);

	$cou=mysqli_num_rows($result);
	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_PRH','ior_PRC','ratio_o','ratio_u','ior_POUC','ior_POUH','ior_PO','ior_PE','ior_MH','ior_MC','ior_MN','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC','ior_T01','ior_T23','ior_T46','ior_OVER','ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC','hgid','hstrong','hratio','ior_HPRH','ior_HPRC','hratio_o','hratio_u','ior_HPOUH','ior_HPOUC','ior_HH1C0','ior_HH2C0','ior_HH2C1','ior_HH3C0','ior_HH3C1','ior_HH3C2','ior_HH4C0','ior_HH4C1','ior_HH4C2','ior_HH4C3','ior_HH0C0','ior_HH1C1','ior_HH2C2','ior_HH3C3','ior_HH4C4','ior_HOVH','ior_HH0C1','ior_HH0C2','ior_HH1C2','ior_HH0C3','ior_HH1C3','ior_HH2C3','ior_HH0C4','ior_HH1C4','ior_HH2C4','ior_HH3C4','ior_HOVC','ior_HPMH','ior_HPMC','ior_HPMN','more','gidm','par_minlimit','par_maxlimit');";	
	echo "parent.retime=0;\n";
	echo "parent.game_more=1;\n";
	echo "parent.str_more='$more';\n";
	echo "parent.gamount=$cou;\n";
	$page_size=60;
	$page_count=ceil($cou/$page_size);
	echo "parent.t_page=$page_count;\n";
	echo "parent.retime=60;\n";			
	while ($row=mysqli_fetch_assoc($result)){
	$MB_P_Win_Rate=change_rate($open,$row["MB_P_Win_Rate"]);
	$TG_P_Win_Rate=change_rate($open,$row["TG_P_Win_Rate"]);
	$M_P_Flat_Rate=change_rate($open,$row["M_P_Flat_Rate"]);
	$MB_P_LetB_Rate=change_rate($open,$row['MB_P_LetB_Rate']);
	$TG_P_LetB_Rate=change_rate($open,$row['TG_P_LetB_Rate']);
	$MB_P_Dime_Rate=change_rate($open,$row['MB_P_Dime_Rate']);
	$TG_P_Dime_Rate=change_rate($open,$row['TG_P_Dime_Rate']);
	$S_P_Single_Rate=change_rate($open,$row['S_P_Single_Rate']);
	$S_P_Double_Rate=change_rate($open,$row['S_P_Double_Rate']);
		
	$MB_P_Win_Rate_H=change_rate($open,$row["MB_P_Win_Rate_H"]);
	$TG_P_Win_Rate_H=change_rate($open,$row["TG_P_Win_Rate_H"]);
	$M_P_Flat_Rate_H=change_rate($open,$row["M_P_Flat_Rate_H"]);
	$MB_P_LetB_Rate_H=change_rate($open,$row['MB_P_LetB_Rate_H']);
	$TG_P_LetB_Rate_H=change_rate($open,$row['TG_P_LetB_Rate_H']);
	$MB_P_Dime_Rate_H=change_rate($open,$row["MB_P_Dime_Rate_H"]);
	$TG_P_Dime_Rate_H=change_rate($open,$row["TG_P_Dime_Rate_H"]);				

	$mb_team=trim($row['MB_Team']);	
	$m_date=strtotime($row['M_Date']);
	$dates=date("m-d",$m_date);	
	if (strlen($row['M_Time'])==5){
		$pdate=$dates.'<br>0'.$row[M_Time];
	}else{
		$pdate=$dates.'<br>'.$row[M_Time];
	}
	if ($row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		$show=3;
	}else if ($row['HPD_Show']==1 and $row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		$show=4;
	}else{
		$show=0;
	}
		echo "parent.GameFT[$K]=new Array('$row[MID]','$pdate','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeP]','$row[M_P_LetB]','$MB_P_LetB_Rate','$TG_P_LetB_Rate','$row[MB_P_Dime]','$row[TG_P_Dime]','$MB_P_Dime_Rate','$TG_P_Dime_Rate','$S_P_Single_Rate','$S_P_Double_Rate','$MB_P_Win_Rate','$TG_P_Win_Rate','$M_P_Flat_Rate','$row[MB1TG0]','$row[MB2TG0]','$row[MB2TG1]','$row[MB3TG0]','$row[MB3TG1]','$row[MB3TG2]','$row[MB4TG0]','$row[MB4TG1]','$row[MB4TG2]','$row[MB4TG3]','$row[MB0TG0]','$row[MB1TG1]','$row[MB2TG2]','$row[MB3TG3]','$row[MB4TG4]','$row[UP5]','$row[MB0TG1]','$row[MB0TG2]','$row[MB1TG2]','$row[MB0TG3]','$row[MB1TG3]','$row[MB2TG3]','$row[MB0TG4]','$row[MB1TG4]','$row[MB2TG4]','$row[MB3TG4]','','$row[S_0_1]','$row[S_2_3]','$row[S_4_6]','$row[S_7UP]','$row[MBMB]','$row[MBFT]','$row[MBTG]','$row[FTMB]','$row[FTFT]','$row[FTTG]','$row[TGMB]','$row[TGFT]','$row[TGTG]','0','$row[ShowTypeHP]','$row[M_P_LetB_H]','$MB_P_LetB_Rate_H','$TG_P_LetB_Rate_H','$row[MB_P_Dime_H]','$row[TG_P_Dime_H]','$TG_P_Dime_Rate_H','$MB_P_Dime_Rate_H','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','$MB_P_Win_Rate_H','$TG_P_Win_Rate_H','$M_P_Flat_Rate_H','$show','$row[MID]','3','10');\n";
		$K=$K+1;	
	}
	break;
}
?>
function onLoad(){
	if(parent.parent.mem_order.location == 'about:blank'){
		parent.parent.mem_order.location = '<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>';
	}
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
</script>
</head>
<body bgcolor="#FFFFFF" onLoad="onLoad()" onUnLoad="onUnLoad()">
</body>
</html>
