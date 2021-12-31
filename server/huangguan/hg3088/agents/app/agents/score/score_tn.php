<?php
require ("../include/config.inc.php");
require ('../include/curl_http.php');
require ("../include/traditional.zh-cn.inc.php");

require_once("../include/address.mem.php");
/*判断IP是否在白名单*/
if(CHECK_IP_SWITCH) {
	if(!checkip()) {
		exit('登录失败!!\\n未被授权访问的IP!!');
	}
}

$mysql = "select * from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$sid=$row['Uid'];
$site=$row['datasite'];
$settime=$row['udp_tn_score'];
$time=$row['udp_tn_results'];
$list_date=date('Y-m-d',time()-$time*60*60);

$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$curl->set_referrer("".$site."/app/member/TN_index.php?uid=$sid&langx=zh-cn&mtype=3");
$html_date=$curl->fetch_url("".$site."/app/member/result/result_tn.php?game_type=TN&list_date=$list_date&uid=$sid&langx=zh-cn");
$html_date = strtolower($html_date);
$a = array(
"<script>",
"</script>",
'"',
"\n\n",
"<br>",
" ",
'</b></font>',
"<td>",
"<tdalign=left>",
"<fontcolor=#cc0000>",
"<fontcolor=red>",
"<b>",
"</b>",
"</a>",
"</font>",
"<spanstyle=overflow:hidden;>",
"</span>",
"&nbsp;&nbsp;",
"array"
);
$b = array(
"",
"",
"",
"",
"-",
"",
'',
"",
"",
"",
"",
"",
"",
"",
"",
"",
"",
""
);
$msg = str_replace($a,$b,$html_date);
$data1=explode("<tableborder=0cellspacing=0cellpadding=0class=game>",$msg);
$m=0;
$data=explode("<trclass=b_cenid=tr_",$data1[2]);
for ($i=1;$i<sizeof($data);$i++){
	$abcde=explode("<trid=tr_",$data[$i]);
	
	//首盘优胜者比分
	$ms=explode("<tdclass=hr_main_tn>",$abcde[1]);
	$m1=explode("</td>",$ms[1]);
	$t1=explode("</td>",$ms[2]);
	$mb_inball1=$m1[0];
	$tg_inball1=$t1[0];
	
	//次盘优胜者比分
	$ms2=explode("<tdclass=hr_main_tn>",$abcde[2]);
	$m2=explode("</td>",$ms2[1]);
	$t2=explode("</td>",$ms2[2]);
	$mb_inball2=$m2[0];
	$tg_inball2=$t2[0];
	
	//第三盘优胜者比分
	$ms3=explode("<tdclass=hr_main_tn>",$abcde[3]);
	$m3=explode("</td>",$ms3[1]);
	$t3=explode("</td>",$ms3[2]);
	$mb_inball3=$m3[0];
	$tg_inball3=$t3[0];
	
	//第四盘优胜者比分
	$ms4=explode("<tdclass=hr_main_tn>",$abcde[4]);
	$m4=explode("</td>",$ms4[1]);
	$t4=explode("</td>",$ms4[2]);
	$mb_inball4=$m4[0];
	$tg_inball4=$t4[0];
	
	//第五盘优胜者比分
	$ms5=explode("<tdclass=hr_main_tn>",$abcde[5]);
	$m5=explode("</td>",$ms5[1]);
	$t5=explode("</td>",$ms5[2]);
	$mb_inball5=$m5[0];
	$tg_inball5=$t5[0];
	
	//局数获胜者比分
	$ms6=explode("<tdclass=hr_main_tn>",$abcde[6]);
	$m6=explode("</td>",$ms6[1]);
	$t6=explode("</td>",$ms6[2]);
	$mb_inball6=$m6[0];
	$tg_inball6=$t6[0];
	
	//完场比分
	$ms0=explode("<tdclass=full_main_tn>",$abcde[7]);
	$m0=explode("</td>",$ms0[1]);
	$t0=explode("</td>",$ms0[2]);
	$mb_inball=$m0[0];
	$tg_inball=$t0[0];
	
	//获取MID
	$mid_m=explode("style=display",$abcde[1]);
	$mid_m=explode("_",$mid_m[0]);
	$mid_m=substr($mid_m[1],6,6);
	
	
	if(preg_match("/$Score2/is",$tg_inball)){
		$mb_inball='-2';
		$mb_inball_hr='-2';
		$tg_inball_hr='-2';
		$tg_inball='-2';
		$mb_inball2='-2';
		$tg_inball2='-2';
		$mb_inball3='-2';
		$tg_inball3='-2';
		$mb_inball4='-2';
		$tg_inball4='-2';
		$mb_inball5='-2';
		$tg_inball5='-2';
		$mb_inball6='-2';
		$tg_inball6='-2';
	}
	if ($tg_inball==$Score1){
		$mb_inball='-1';
		$mb_inball_hr='-1';
		$tg_inball_hr='-1';
		$tg_inball='-1';	
		$mb_inball2='-1';
		$tg_inball2='-1';
		$mb_inball3='-1';
		$tg_inball3='-1';
		$mb_inball4='-1';
		$tg_inball4='-1';
		$mb_inball5='-1';
		$tg_inball5='-1';
		$mb_inball6='-1';
		$tg_inball6='-1';
	}else if ($tg_inball==$Score2){
		$mb_inball='-2';
		$mb_inball_hr='-2';
		$tg_inball_hr='-2';
		$tg_inball='-2';	
		$mb_inball2='-2';
		$tg_inball2='-2';
		$mb_inball3='-2';
		$tg_inball3='-2';
		$mb_inball4='-2';
		$tg_inball4='-2';
		$mb_inball5='-2';
		$tg_inball5='-2';
		$mb_inball6='-2';
		$tg_inball6='-2';
	}else if ($tg_inball==$Score3){
		$mb_inball='-3';
		$mb_inball_hr='-3';
		$tg_inball_hr='-3';
		$tg_inball='-3';	
		$mb_inball2='-3';
		$tg_inball2='-3';
		$mb_inball3='-3';
		$tg_inball3='-3';
		$mb_inball4='-3';
		$tg_inball4='-3';
		$mb_inball5='-3';
		$tg_inball5='-3';
		$mb_inball6='-3';
		$tg_inball6='-3';				
	}else if ($tg_inball==$Score4){
		$mb_inball='-4';
		$mb_inball_hr='-4';
		$tg_inball_hr='-4';
		$tg_inball='-4';
		$mb_inball2='-4';
		$tg_inball2='-4';
		$mb_inball3='-4';
		$tg_inball3='-4';
		$mb_inball4='-4';
		$tg_inball4='-4';
		$mb_inball5='-4';
		$tg_inball5='-4';
		$mb_inball6='-4';
		$tg_inball6='-4';
	}else if ($tg_inball==$Score5){
		$mb_inball='-5';
		$mb_inball_hr='-5';
		$tg_inball_hr='-5';
		$tg_inball='-5';
		$mb_inball2='-5';
		$tg_inball2='-5';
		$mb_inball3='-5';
		$tg_inball3='-5';
		$mb_inball4='-5';
		$tg_inball4='-5';
		$mb_inball5='-5';
		$tg_inball5='-5';
		$mb_inball6='-5';
		$tg_inball6='-5';				
	}else if ($tg_inball==$Score6){
		$mb_inball='-6';
		$mb_inball_hr='-6';
		$tg_inball_hr='-6';
		$tg_inball='-6';
		$mb_inball2='-6';
		$tg_inball2='-6';
		$mb_inball3='-6';
		$tg_inball3='-6';
		$mb_inball4='-6';
		$tg_inball4='-6';
		$mb_inball5='-6';
		$tg_inball5='-6';
		$mb_inball6='-6';
		$tg_inball6='-6';
	}else if ($tg_inball==$Score7){
		$mb_inball='-7';
		$mb_inball_hr='-7';
		$tg_inball_hr='-7';
		$tg_inball='-7';	
		$mb_inball2='-7';
		$tg_inball2='-7';
		$mb_inball3='-7';
		$tg_inball3='-7';
		$mb_inball4='-7';
		$tg_inball4='-7';
		$mb_inball5='-7';
		$tg_inball5='-7';
		$mb_inball6='-7';
		$tg_inball6='-7';				
	}else if ($tg_inball=="球员弃权"){
		$mb_inball='-8';
		$mb_inball_hr='-8';
		$tg_inball_hr='-8';
		$tg_inball='-8';
		$mb_inball2='-8';
		$tg_inball2='-8';
		$mb_inball3='-8';
		$tg_inball3='-8';
		$mb_inball4='-8';
		$tg_inball4='-8';
		$mb_inball5='-8';
		$tg_inball5='-8';
		$mb_inball6='-8';
		$tg_inball6='-8';				
	}else if ($tg_inball=='队名错误'){
		$mb_inball='-9';
		$mb_inball_hr='-9';
		$tg_inball_hr='-9';
		$tg_inball='-9';
		$mb_inball2='-9';
		$tg_inball2='-9';
		$mb_inball3='-9';
		$tg_inball3='-9';
		$mb_inball4='-9';
		$tg_inball4='-9';
		$mb_inball5='-9';
		$tg_inball5='-9';
		$mb_inball6='-9';
		$tg_inball6='-9';
	}else if ($tg_inball==$Score10){
		$mb_inball='-10';
		$mb_inball_hr='-10';
		$tg_inball_hr='-10';
		$tg_inball='-10';
		$mb_inball2='-10';
		$tg_inball2='-10';
		$mb_inball3='-10';
		$tg_inball3='-10';
		$mb_inball4='-10';
		$tg_inball4='-10';
		$mb_inball5='-10';
		$tg_inball5='-10';
		$mb_inball6='-10';
		$tg_inball6='-10';
	}else if ($tg_inball==$Score11){
		$mb_inball='-11';
		$mb_inball_hr='-11';
		$tg_inball_hr='-11';
		$tg_inball='-11';
		$mb_inball2='-11';
		$tg_inball2='-11';
		$mb_inball3='-11';
		$tg_inball3='-11';
		$mb_inball4='-11';
		$tg_inball4='-11';
		$mb_inball5='-11';
		$tg_inball5='-11';
		$mb_inball6='-11';
		$tg_inball6='-11';
	}else if ($tg_inball==$Score12){
		$mb_inball='-12';
		$mb_inball_hr='-12';
		$tg_inball_hr='-12';
		$tg_inball='-12';	
		$mb_inball2='-12';
		$tg_inball2='-12';
		$mb_inball3='-12';
		$tg_inball3='-12';
		$mb_inball4='-12';
		$tg_inball4='-12';
		$mb_inball5='-12';
		$tg_inball5='-12';
		$mb_inball6='-12';
		$tg_inball6='-12';
	}else if ($tg_inball==$Score13){
		$mb_inball='-13';
		$mb_inball_hr='-13';
		$tg_inball_hr='-13';
		$tg_inball='-13';
		$mb_inball2='-13';
		$tg_inball2='-13';
		$mb_inball3='-13';
		$tg_inball3='-13';
		$mb_inball4='-13';
		$tg_inball4='-13';
		$mb_inball5='-13';
		$tg_inball5='-13';
		$mb_inball6='-13';
		$tg_inball6='-13';				
	}else if ($tg_inball==$Score14){
		$mb_inball='-14';
		$mb_inball_hr='-14';
		$tg_inball_hr='-14';
		$tg_inball='-14';
		$mb_inball2='-14';
		$tg_inball2='-14';
		$mb_inball3='-14';
		$tg_inball3='-14';
		$mb_inball4='-14';
		$tg_inball4='-14';
		$mb_inball5='-14';
		$tg_inball5='-14';
		$mb_inball6='-14';
		$tg_inball6='-14';
	}else if ($tg_inball==$Score15){
		$mb_inball='-15';
		$mb_inball_hr='-15';
		$tg_inball_hr='-15';
		$tg_inball='-15';	
		$mb_inball2='-15';
		$tg_inball2='-15';
		$mb_inball3='-15';
		$tg_inball3='-15';
		$mb_inball4='-15';
		$tg_inball4='-15';
		$mb_inball5='-15';
		$tg_inball5='-15';
		$mb_inball6='-15';
		$tg_inball6='-15';				
	}else if ($tg_inball==$Score16){
		$mb_inball='-16';
		$mb_inball_hr='-16';
		$tg_inball_hr='-16';
		$tg_inball='-16';
		$mb_inball2='-16';
		$tg_inball2='-16';
		$mb_inball3='-16';
		$tg_inball3='-16';
		$mb_inball4='-16';
		$tg_inball4='-16';
		$mb_inball5='-16';
		$tg_inball5='-16';
		$mb_inball6='-16';
		$tg_inball6='-16';
	}else if ($tg_inball==$Score17){
		$mb_inball='-17';
		$mb_inball_hr='-17';
		$tg_inball_hr='-17';
		$tg_inball='-17';	
		$mb_inball2='-17';
		$tg_inball2='-17';
		$mb_inball3='-17';
		$tg_inball3='-17';
		$mb_inball4='-17';
		$tg_inball4='-17';
		$mb_inball5='-17';
		$tg_inball5='-17';
		$mb_inball6='-17';
		$tg_inball6='-17';				
	}else if ($tg_inball==$Score18){
		$mb_inball='-18';
		$mb_inball_hr='-18';
		$tg_inball_hr='-18';
		$tg_inball='-18';	
		$mb_inball2='-18';
		$tg_inball2='-18';
		$mb_inball3='-18';
		$tg_inball3='-18';
		$mb_inball4='-18';
		$tg_inball4='-18';
		$mb_inball5='-18';
		$tg_inball5='-18';
		$mb_inball6='-18';
		$tg_inball6='-18';				
	}else if ($tg_inball==$Score19){
		$mb_inball='-19';
		$mb_inball_hr='-19';
		$tg_inball_hr='-19';
		$tg_inball='-19';
		$mb_inball2='-19';
		$tg_inball2='-19';
		$mb_inball3='-19';
		$tg_inball3='-19';
		$mb_inball4='-19';
		$tg_inball4='-19';
		$mb_inball5='-19';
		$tg_inball5='-19';
		$mb_inball6='-19';
		$tg_inball6='-19';
	}

	$sql="select * from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid_m." and M_Date='".$list_date."'";
	$result = mysqli_query($dbLink, $sql);
	$cou=mysqli_num_rows($result);
	$row = mysqli_fetch_assoc($result);
	if($cou>0){
		$MB_Team=$row['MB_Team'];
		$TG_Team=$row['TG_Team'];
		$M_League=$row["M_League"];
		$m=$m+1;
		if($mb_team<>"" or $tg_team<>""){
			$sqlq="select * from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MB_Team='$MB_Team' and TG_Team='$TG_Team' and M_League='$M_League' and M_Date='".$list_date."'";
			$resultq = mysqli_query($dbLink, $sqlq);
			while($rowq = mysqli_fetch_assoc($resultq)){
				$mids=$rowq["MID"];
				
				
				if ($rowq['MB_Inball']==""){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball',TG_Inball='$tg_inball',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mids;
					mysqli_query($dbMasterLink, $mysql) or die($mysql);
				}else if ($row['MB_Inball']<0){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball',TG_Inball='$tg_inball',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Cancel=1 where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mids;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else{
					$m_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID='".(int)$mids."' and M_Date='".$list_date."'";
					$m_result = mysqli_query($dbLink,$m_sql);
					$m_row = mysqli_fetch_assoc($m_result);
					$a=	$m_row['MB_Inball'].$m_row['TG_Inball'].$m_row['MB_Inball_HR'].$m_row['TG_Inball_HR'];
					$b=	trim($mb_inball).trim($tg_inball).trim($mb_inball_hr).trim($tg_inball_hr);
					if ($a!=$b){
					$check=1;
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball."',TG_Inball='".(int)$tg_inball."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."',Checked='".$check."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mids;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}else{
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball."',TG_Inball='".(int)$tg_inball."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mids;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}
				}	
				
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
				$mid1=$mids+1;
				$sql="select MID,MB_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid1." and M_Date='".$list_date."'";
				$result = mysqli_query($dbLink, $sql);
				$cou=mysqli_num_rows($result);
				$row = mysqli_fetch_assoc($result);
				if ($cou==0){
					$sql="select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and TG_MID=".(int)$mid1." and M_Date='".$list_date."'";
					$result = mysqli_query($dbLink, $sql);
					$row = mysqli_fetch_assoc($result);		
				}
				$mid=$row['MID'];
				if ($row['MB_Inball']==""){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball1',TG_Inball='$tg_inball1',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else if ($row['MB_Inball']<0){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball1',TG_Inball='$tg_inball1',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Cancel=1 where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else{
					$m_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID='".(int)$mid."' and M_Date='".$list_date."'";
					$m_result = mysqli_query($dbLink,$m_sql);
					$m_row = mysqli_fetch_assoc($m_result);
					$a=	$m_row['MB_Inball'].$m_row['TG_Inball'].$m_row['MB_Inball_HR'].$m_row['TG_Inball_HR'];
					$b=	trim($mb_inball1).trim($tg_inball1).trim($mb_inball_hr).trim($tg_inball_hr);
					if ($a!=$b){
					$check=1;
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball1."',TG_Inball='".(int)$tg_inball1."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."',Checked='".$check."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}else{
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball1."',TG_Inball='".(int)$tg_inball1."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}
				}
	
	
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
				$mid2=$mids+2;
				$sql="select MID,MB_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid2." and M_Date='".$list_date."'";
				$result = mysqli_query($dbLink, $sql);
				$cou=mysqli_num_rows($result);
				$row = mysqli_fetch_assoc($result);
				if ($cou==0){
					$sql="select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid2." and M_Date='".$list_date."'";
					$result = mysqli_query($dbLink, $sql);
					$row = mysqli_fetch_assoc($result);		
				}
				$mid=$row['MID'];
				if ($row['MB_Inball']==""){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball2',TG_Inball='$tg_inball2',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else if ($row['MB_Inball']<0){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball2',TG_Inball='$tg_inball2',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Cancel=1 where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else{
					$m_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID='".(int)$mid."' and M_Date='".$list_date."'";
					$m_result = mysqli_query($dbLink,$m_sql);
					$m_row = mysqli_fetch_assoc($m_result);
					$a=	$m_row['MB_Inball'].$m_row['TG_Inball'].$m_row['MB_Inball_HR'].$m_row['TG_Inball_HR'];
					$b=	trim($mb_inball2).trim($tg_inball2).trim($mb_inball_hr).trim($tg_inball_hr);
					if ($a!=$b){
					$check=1;
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball2."',TG_Inball='".(int)$tg_inball2."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."',Checked='".$check."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}else{
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball2."',TG_Inball='".(int)$tg_inball2."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}
				}
	
	
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
				$mid3=$mids+3;
				$sql="select MID,MB_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid3." and M_Date='".$list_date."'";
				$result = mysqli_query($dbLink, $sql);
				$cou=mysqli_num_rows($result);
				$row = mysqli_fetch_assoc($result);
				if ($cou==0){
					$sql="select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid3." and M_Date='".$list_date."'";
					$result = mysqli_query($dbLink, $sql);
					$row = mysqli_fetch_assoc($result);		
				}
				$mid=$row['MID'];
				if ($row['MB_Inball']==""){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball3',TG_Inball='$tg_inball3',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else if ($row['MB_Inball']<0){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball3',TG_Inball='$tg_inball3',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Cancel=1 where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else{
					$m_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID='".(int)$mid."' and M_Date='".$list_date."'";
					$m_result = mysqli_query($dbLink,$m_sql);
					$m_row = mysqli_fetch_assoc($m_result);
					$a=	$m_row['MB_Inball'].$m_row['TG_Inball'].$m_row['MB_Inball_HR'].$m_row['TG_Inball_HR'];
					$b=	trim($mb_inball3).trim($tg_inball3).trim($mb_inball_hr).trim($tg_inball_hr);
					if ($a!=$b){
					$check=1;
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball3."',TG_Inball='".(int)$tg_inball3."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."',Checked='".$check."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}else{
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball3."',TG_Inball='".(int)$tg_inball3."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}
				}
	
	
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
				$mid1=$mids+4;
				$sql="select MID,MB_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid4." and M_Date='".$list_date."'";
				$result = mysqli_query($dbLink, $sql);
				$cou=mysqli_num_rows($result);
				$row = mysqli_fetch_assoc($result);
				if ($cou==0){
					$sql="select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid4." and M_Date='".$list_date."'";
					$result = mysqli_query($dbLink, $sql);
					$row = mysqli_fetch_assoc($result);		
				}
				$mid=$row['MID'];
				if ($row['MB_Inball']==""){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball4',TG_Inball='$tg_inball4',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else if ($row['MB_Inball']<0){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball4',TG_Inball='$tg_inball4',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Cancel=1 where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else{
					$m_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID='".(int)$mid."' and M_Date='".$list_date."'";
					$m_result = mysqli_query($dbLink,$m_sql);
					$m_row = mysqli_fetch_assoc($m_result);
					$a=	$m_row['MB_Inball'].$m_row['TG_Inball'].$m_row['MB_Inball_HR'].$m_row['TG_Inball_HR'];
					$b=	trim($mb_inball4).trim($tg_inball4).trim($mb_inball_hr).trim($tg_inball_hr);
					if ($a!=$b){
					$check=1;
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball4."',TG_Inball='".(int)$tg_inball4."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."',Checked='".$check."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}else{
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball4."',TG_Inball='".(int)$tg_inball4."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}
				}
	
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
				$mid5=$mids+5;
				$sql="select MID,MB_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid5." and M_Date='".$list_date."'";
				$result = mysqli_query($dbLink, $sql);
				$cou=mysqli_num_rows($result);
				$row = mysqli_fetch_assoc($result);
				if ($cou==0){
					$sql="select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid5." and M_Date='".$list_date."'";
					$result = mysqli_query($dbLink, $sql);
					$row = mysqli_fetch_assoc($result);		
				}
				$mid=$row['MID'];
				if ($row['MB_Inball']==""){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball5',TG_Inball='$tg_inball5',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else if ($row['MB_Inball']<0){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball5',TG_Inball='$tg_inball5',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Cancel=1 where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else{
					$m_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID='".(int)$mid."' and M_Date='".$list_date."'";
					$m_result = mysqli_query($dbLink,$m_sql);
					$m_row = mysqli_fetch_assoc($m_result);
					$a=	$m_row['MB_Inball'].$m_row['TG_Inball'].$m_row['MB_Inball_HR'].$m_row['TG_Inball_HR'];
					$b=	trim($mb_inball5).trim($tg_inball5).trim($mb_inball_hr).trim($tg_inball_hr);
					if ($a!=$b){
					$check=1;
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball5."',TG_Inball='".(int)$tg_inball5."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."',Checked='".$check."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}else{
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball5."',TG_Inball='".(int)$tg_inball5."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and MID=".(int)$mid;
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}
				}
	
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
				$mid6=$mids+6;
				$sql="select * from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid6." and M_Date='".$list_date."'";
				$result = mysqli_query($dbLink, $sql);
				$cou=mysqli_num_rows($result);
				$row = mysqli_fetch_assoc($result);
				if ($cou==0){
					$sql="select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID=".(int)$mid6." and M_Date='".$list_date."'";
					$result = mysqli_query($dbLink, $sql);
					$row = mysqli_fetch_assoc($result);		
				}
				$mid=$row['MID'];
				$TG_Team1=$row["TG_Team"];
				$MB_Team1=$row["MB_Team"];
				$M_League1=$row["M_League"];
				if ($row['MB_Inball']==""){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball6',TG_Inball='$tg_inball6',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and (MID=".(int)$mid." or (M_League='$M_League1' and  TG_Team='$TG_Team1' and MB_Team='$MB_Team1'))";
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else if ($row['MB_Inball']<0){
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball6',TG_Inball='$tg_inball6',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Cancel=1 where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and (MID=".(int)$mid." or (M_League='$M_League1' and  TG_Team='$TG_Team1' and MB_Team='$MB_Team1'))";
					mysqli_query($dbMasterLink, $mysql) or die('abc');
				}else{
					$m_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and MID='".(int)$mid."' and M_Date='".$list_date."'";
					$m_result = mysqli_query($dbLink,$m_sql);
					$m_row = mysqli_fetch_assoc($m_result);
					$a=	$m_row['MB_Inball'].$m_row['TG_Inball'].$m_row['MB_Inball_HR'].$m_row['TG_Inball_HR'];
					$b=	trim($mb_inball6).trim($tg_inball6).trim($mb_inball_hr).trim($tg_inball_hr);
					if ($a!=$b){
					$check=1;
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball6."',TG_Inball='".(int)$tg_inball6."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."',Checked='".$check."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and (MID=".(int)$mid." or (M_League='$M_League1' and  TG_Team='$TG_Team1' and MB_Team='$MB_Team1'))";
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}else{
					$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball6."',TG_Inball='".(int)$tg_inball6."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."' where Type='TN' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='".$list_date."' and (MID=".(int)$mid." or (M_League='$M_League1' and  TG_Team='$TG_Team1' and MB_Team='$MB_Team1'))";
					mysqli_query($dbMasterLink, $mysql) or die('abc');
					}
				}
				
			}
		}
	}
	

}



echo '<br>目前比分以结算出'.$m;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>网球接比分</title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<script> 

var limit="<?php echo $settime?>" 
if (document.images){ 
	var parselimit=limit
} 
function beginrefresh(){ 
if (!document.images) 
	return 
if (parselimit==1) 
	window.location.reload() 
else{ 
	parselimit-=1 
	curmin=Math.floor(parselimit) 
	if (curmin!=0) 
		curtime=curmin+"秒后自动本页获取最新数据！" 
	else 
		curtime=cursec+"秒后自动本页获取最新数据！" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 

window.onload=beginrefresh 

</script>
<body>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" align="center"><br><?php echo $list_date?><br><br><span id="timeinfo"></span><br>
      <input type=button name=button value="网球更新" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
