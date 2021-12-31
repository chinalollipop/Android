<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require ("../include/address.mem.php");
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
require ("../include/curl_http.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$rtype=$_REQUEST['rtype'];
$league_id=$_REQUEST['league_id'];
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
if ($page_no==''){
    $page_no=0;
}
$m_date=date('Y-m-d');
$date=date('m-d');
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
<?php 
switch ($rtype){
case "r":
	$mysql = "select MID,M_Date,M_Time,M_Type,MB_MID,TG_MID,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,ShowTypeR,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Dime_H,TG_Dime_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='OP' and `M_Start` > now( ) AND `M_Date` ='$m_date' and S_Show=1 and $mb_team!='' and Open=1 order by M_Start,MID";
	$result = mysqli_query($dbMasterLink, $mysql);
	$cou_num=mysqli_num_rows($result);
	$page_size=60;
	$page_count=ceil($cou_num/$page_size);
	$offset=$page_no*60;	
	$mysql=$mysql."  limit $offset,$num;";
	$result = mysqli_query($dbMasterLink, $mysql);
	$cou=mysqli_num_rows($result);
	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_RH','ior_RC','ratio_o','ratio_u','ior_OUH','ior_OUC','ior_MH','ior_MC','ior_MN','str_odd','str_even','ior_EOO','ior_EOE','hgid','hstrong','hratio','ior_HRH','ior_HRC','hratio_o','hratio_u','ior_HOUH','ior_HOUC','ior_HMH','ior_HMC','ior_HMN','more','eventid','hot','play');";
	echo "parent.retime=180;\n";
	echo "parent.str_renew = '$second_auto_update';\n";
	echo "parent.game_more=1;\n";
	echo "parent.str_more='$play_more';\n";	
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
		$MB_Dime_Rate_hr=change_rate($open,$row["MB_Dime_Rate_H"]);
		$TG_Dime_Rate_hr=change_rate($open,$row["TG_Dime_Rate_H"]);				
		$MB_LetB_Rate_hr=change_rate($open,$row['MB_LetB_Rate_H']);
		$TG_LetB_Rate_hr=change_rate($open,$row['TG_LetB_Rate_H']);
		
		if ($row['HPD_Show']==1 and $row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		    $show=4;
		}else if ($row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		    $show=3;
		}else{
		    $show=0;
		}
		if($S_Single_Rate){
			$o1=$o;
		}else{
			$o1="";
		}
		if($S_Double_Rate){
			$e1=$e;
		}else{
			$e1='';
		}				
		if ($row['M_Type']==1){
			echo "parent.GameFT[$K]=new Array('$row[MID]','$date<br>$row[M_Time]<br><font color=red>Running Ball</font>','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeR]','$row[M_LetB]','$MB_LetB_Rate','$TG_LetB_Rate','$row[MB_Dime]','$row[TG_Dime]','$TG_Dime_Rate','$MB_Dime_Rate','$MB_Win_Rate','$TG_Win_Rate','$M_Flat_Rate','$o1','$e1','$S_Single_Rate','$S_Double_Rate','0','$row[ShowTypeHR]','$row[M_LetB_H]','$MB_LetB_Rate_hr','$TG_LetB_Rate_hr','$row[MB_Dime_H]','$row[TG_Dime_H]','$TG_Dime_Rate_hr','$MB_Dime_Rate_hr','$MB_Win_Rate_hr','$TG_Win_Rate_hr','$M_Flat_Rate_hr','$show','$row[Eventid]','$row[Hot]','$row[Play]');\n";
		}else{
			echo "parent.GameFT[$K]=new Array('$row[MID]','$date<br>$row[M_Time]','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeR]','$row[M_LetB]','$MB_LetB_Rate','$TG_LetB_Rate','$row[MB_Dime]','$row[TG_Dime]','$TG_Dime_Rate','$MB_Dime_Rate','$MB_Win_Rate','$TG_Win_Rate','$M_Flat_Rate','$o1','$e1','$S_Single_Rate','$S_Double_Rate','0','$row[ShowTypeHR]','$row[M_LetB_H]','$MB_LetB_Rate_hr','$TG_LetB_Rate_hr','$row[MB_Dime_H]','$row[TG_Dime_H]','$TG_Dime_Rate_hr','$MB_Dime_Rate_hr','$MB_Win_Rate_hr','$TG_Win_Rate_hr','$M_Flat_Rate_hr','$show','$row[Eventid]','$row[Hot]','$row[Play]');\n";
		}
	$K=$K+1;	
	}
	break;

case "re":
	$mysql = "select datasite,datasite_en,datasite_tw,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";
	$result = mysqli_query($dbMasterLink,$mysql);
	$row = mysqli_fetch_assoc($result);
	switch($langx)	{
	case "zh-cn":
		$suid=$row['uid_tw'];
		$site=$row['datasite_tw'];
		break;
	case "zh-cn":
		$suid=$row['uid'];
		$site=$row['datasite'];
		break;
	case "en-us":
		$suid=$row['uid_en'];
		$site=$row['datasite_en'];
		break;
	case "th-tis":
		$suid=$row['uid_en'];
		$site=$row['datasite_en'];
		break;
	}
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$site."/app/member/OP_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	$html_data=$curl->fetch_url("".$site."/app/member/OP_browse/body_var.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	preg_match_all("/]=new Array\((.+?)\);/is",$html_data,$matches);
	//echo $html_data;
	$cou=sizeof($matches[0]);
	echo "parent.GameHead = new Array('gid','timer','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_RH','ior_RC','ratio_o','ratio_u','ior_OUH','ior_OUC','no1','no2','no3','score_h','score_c','hgid','hstrong','hratio','ior_HRH','ior_HRC','hratio_o','hratio_u','ior_HOUH','ior_HOUC','redcard_h','redcard_c','lastestscore_h','lastestscore_c','eventid','hot','play','datetime');";
	echo "parent.str_renew = '$second_auto_update';\n";
	echo "parent.gamount=$cou;\n";
	$page_size=60;
	$page_count=ceil($cou/$page_size);
	echo "parent.t_page=$page_count;\n";
	echo "parent.retime=60;\n";	
	for($i=0;$i<$cou;$i++){
		$messages=$matches[0][$i];
		$messages=str_replace("]=new Array(","",$messages);
	    $messages=str_replace(");","",$messages);
	    $messages=str_replace("'","",$messages);
	    $datainfo=explode(",",$messages);
				
		$opensql = "select * from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Type`='OP'";
		//echo $opensql;
	    $openresult = mysqli_query($dbMasterLink,$opensql);
	    $openrow=mysqli_fetch_assoc($openresult);
		if ($openrow['Open']==1){
		$sql = "update ".DBPREFIX."match_sports set ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[25]',TG_Dime_RB_H='$datainfo[26]',MB_Dime_Rate_RB_H='$datainfo[28]',TG_Dime_Rate_RB_H='$datainfo[27]',MB_Ball='$datainfo[18]',TG_Ball='$datainfo[19]',MB_Card='$datainfo[29]',TG_Card='$datainfo[30]',MB_Red='$datainfo[31]',TG_Red='$datainfo[32]',Eventid='$datainfo[34]',Hot='$datainfo[34]',Play='$datainfo[35]',Open='$fp',RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='OP'";
		mysqli_query($dbMasterLink,$sql) or die("error");	
		if ($datainfo[9]<>''){
			$datainfo[9]=change_rate($open,$datainfo[9]);
			$datainfo[10]=change_rate($open,$datainfo[10]);
		}
		if ($datainfo[13]<>''){
			$datainfo[13]=change_rate($open,$datainfo[13]);
			$datainfo[14]=change_rate($open,$datainfo[14]);
		}			
		if ($datainfo[23]<>''){
		    $datainfo[23]=change_rate($open,$datainfo[23]);
			$datainfo[24]=change_rate($open,$datainfo[24]);
		}
		if ($datainfo[28]<>''){
		    $datainfo[28]=change_rate($open,$datainfo[28]);
			$datainfo[27]=change_rate($open,$datainfo[27]);
		}
		$datainfo[19]=$datainfo[19]+0;
		$datainfo[18]=$datainfo[18]+0;			
		echo "parent.GameFT[$K]=new Array('$datainfo[0]','$datainfo[1]','$datainfo[2]','$datainfo[3]','$datainfo[4]','$datainfo[5]','$datainfo[6]','$datainfo[7]','$datainfo[8]','$datainfo[9]','$datainfo[10]','$datainfo[11]','$datainfo[12]','$datainfo[13]','$datainfo[14]','$datainfo[15]','$datainfo[16]','$datainfo[17]','$datainfo[18]','$datainfo[19]','$datainfo[20]','$datainfo[21]','$datainfo[22]','$datainfo[23]','$datainfo[24]','$datainfo[25]','$datainfo[26]','$datainfo[27]','$datainfo[28]','$datainfo[29]','$datainfo[30]','$datainfo[31]','$datainfo[32]','$datainfo[33]','$datainfo[34]','$datainfo[35]','$datainfo[36]');\n";
		$K=$K+1;
		
		if ($gmid==''){
			$gmid=$datainfo[0];
		}else{
			$gmid=$gmid.','.$datainfo[0];
		}
		}
	}
	$sql="update ".DBPREFIX."match_sports set RB_Show=0 where RB_Show=1 and locate(MID,'$gmid')<1";
	mysqli_query($dbMasterLink,$sql) or die ("巨ア毖!");
	break;
case "p3":
	$mysql = "select MID,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_MID,TG_MID,ShowTypeP,MB_P_LetB_Rate,TG_P_LetB_Rate,M_P_LetB,MB_P_Dime,TG_P_Dime,MB_P_Dime_Rate,TG_P_Dime_Rate,S_P_Single_Rate,S_P_Double_Rate,MB_P_Win_Rate,TG_P_Win_Rate,M_P_Flat_Rate,ShowTypeHP,M_P_LetB_H,MB_P_LetB_Rate_H,TG_P_LetB_Rate_H,MB_P_Dime_H,TG_P_Dime_H,MB_P_Dime_Rate_H,TG_P_Dime_Rate_H,MB_P_Win_Rate_H,TG_P_Win_Rate_H,M_P_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='OP' and `M_Start` > now( ) AND `M_Date` ='$m_date'  and P3_Show=1 and $mb_team!='' order by M_Start,MID";
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
	if ($row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		$show=3;
	}else if ($row['HPD_Show']==1 and $row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		$show=4;
	}else{
		$show=0;
	}
	if (strlen($row['M_Time'])==5){
		$pdate=$date.'<br>0'.$row[M_Time];
	}else{
		$pdate=$date.'<br>'.$row[M_Time];
	}
		echo "parent.GameFT[$K]=new Array('$row[MID]','$pdate','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeP]','$row[M_P_LetB]','$MB_P_LetB_Rate','$TG_P_LetB_Rate','$row[MB_P_Dime]','$row[TG_P_Dime]','$MB_P_Dime_Rate','$TG_P_Dime_Rate','$S_P_Single_Rate','$S_P_Double_Rate','$MB_P_Win_Rate','$TG_P_Win_Rate','$M_P_Flat_Rate','$row[MB1TG0]','$row[MB2TG0]','$row[MB2TG1]','$row[MB3TG0]','$row[MB3TG1]','$row[MB3TG2]','$row[MB4TG0]','$row[MB4TG1]','$row[MB4TG2]','$row[MB4TG3]','$row[MB0TG0]','$row[MB1TG1]','$row[MB2TG2]','$row[MB3TG3]','$row[MB4TG4]','$row[UP5]','$row[MB0TG1]','$row[MB0TG2]','$row[MB1TG2]','$row[MB0TG3]','$row[MB1TG3]','$row[MB2TG3]','$row[MB0TG4]','$row[MB1TG4]','$row[MB2TG4]','$row[MB3TG4]','','$row[S_0_1]','$row[S_2_3]','$row[S_4_6]','$row[S_7UP]','$row[MBMB]','$row[MBFT]','$row[MBTG]','$row[FTMB]','$row[FTFT]','$row[FTTG]','$row[TGMB]','$row[TGFT]','$row[TGTG]','0','$row[ShowTypeHP]','$row[M_P_LetB_H]','$MB_P_LetB_Rate_H','$TG_P_LetB_Rate_H','$row[MB_P_Dime_H]','$row[TG_P_Dime_H]','$TG_P_Dime_Rate_H','$MB_P_Dime_Rate_H','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','$MB_P_Win_Rate_H','$TG_P_Win_Rate_H','$M_P_Flat_Rate_H','$show','$row[MID]','3','10');\n";
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
	parent.body_browse.document.getElementById('download').title='DownLoad 58';
 
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
