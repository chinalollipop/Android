<?php
session_start();
include ("../include/address.mem.php");
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}else{

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$username=$_REQUEST['username'];
require ("../include/traditional.$langx.inc.php");

$active=$_REQUEST['active'];
$id=$_REQUEST['id'];
$gid=$_REQUEST['gid'];
$date_start=$_REQUEST['date_start'];

function filiter_team($repteam){
	$repteam=trim(str_replace("[主]","",$repteam));
	$repteam=trim(str_replace("[中]","",$repteam));
	$repteam=trim(str_replace("[い]","",$repteam));
	$repteam=trim(str_replace("[]","",$repteam));
	$repteam=trim(str_replace("[Home]","",$repteam));
	$repteam=trim(str_replace("[Mid]","",$repteam));
	$filiter_team=$repteam;
	return $filiter_team;
}

switch ($active){
case 1:
	$mysql="select * from ".DBPREFIX."web_report_data where id='$id'";
	$result = mysqli_query($dbLink,$mysql);
	$row = mysqli_fetch_assoc($result);
	$info=explode("<br>",$row[Middle]);
	switch ($row[LineType]){
  	case 2:
		switch ($row['OpenType']){
		case "A":
			$rate=1.84-$row['M_Rate'];
			break;
		case "B":
			$rate=1.88-$row['M_Rate'];
			break;
		case "C":
			$rate=1.92-$row['M_Rate'];
			break;
		case "D":
			$rate=1.95-$row['M_Rate'];
			break;			
		}
		$rate=number_format($rate,2);
		$gwin=$row['BetScore']*$rate;
		$info   =explode("<br>",$row[Middle]);
		$info_tw=explode("<br>",$row[Middle_tw]);
		$info_en=explode("<br>",$row[Middle_en]);
		if ($row[Active]==7){			
			$middle=$info[0].'<br>'.$info[1].'<br>';
			$middle_tw=$info_tw[0].'<br>'.$info_tw[1].'<br>';
			$middle_en=$info_en[0].'<br>'.$info_en[1].'<br>';
			$team=explode("&nbsp;&nbsp;",$info[2]);
			$team_tw=explode("&nbsp;&nbsp;",$info_tw[2]);
			$team_en=explode("&nbsp;&nbsp;",$info_en[2]);
		}else{
			$sid=$info[1];
			$middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
			$middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
			$middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
			$team_c=explode("&nbsp;&nbsp;",$info[2]);
			$team=explode("&nbsp;",$team_c[1]);
			$team_t=explode("&nbsp;&nbsp;",$info_tw[2]);
			$team_tw=explode("&nbsp;",$team_t[1]);
			$team_e=explode("&nbsp;&nbsp;",$info_en[2]);
			$team_en=explode("&nbsp;",$team_e[1]);
		}
		
		if ($row[ShowType]=='H'){
			$mb_team=$team[0];
			$tg_team=$team[2];
			$mb_team_tw=$team_tw[0];
			$tg_team_tw=$team_tw[2];
			$mb_team_en=$team_en[0];
			$tg_team_en=$team_en[2];
			if ($row[Mtype]=='RH'){
				$mtype='RC';
				$m_place=$tg_team;
				$m_place_tw=$tg_team_tw;
				$m_place_en=$tg_team_en;
			}else{
				$mtype='RH';
				$m_place=$mb_team;
				$m_place_tw=$mb_team_tw;
				$m_place_en=$mb_team_en;
			}	
		}else{
			$mb_team=$team[0];
			$tg_team=$team[2];
			$mb_team_tw=$team_tw[0];
			$tg_team_tw=$team_tw[2];
			$mb_team_en=$team_en[0];
			$tg_team_en=$team_en[2];
			if ($row[Mtype]=='RH'){
				$mtype='RC';
				$m_place=$mb_team;
				$m_place_tw=$mb_team_tw;
				$m_place_en=$mb_team_en;
			}else{
				$mtype='RH';
				$m_place=$tg_team;
				$m_place_tw=$tg_team_tw;
				$m_place_en=$tg_team_en;
			}	
		}
		$lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';			
		$mysql="update ".DBPREFIX."web_report_data set Mtype='$mtype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Rate='$rate',Gwin='$gwin',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Cancel=0,Confirmed=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
		break;
	case 3:
		switch ($row['OpenType']){
		case "A":
			$rate=1.84-$row['M_Rate'];
			break;
		case "B":
			$rate=1.88-$row['M_Rate'];
			break;
		case "C":
			$rate=1.92-$row['M_Rate'];
			break;
		case "D":
			$rate=1.95-$row['M_Rate'];
			break;
		}
		$rate=number_format($rate,2);
		$gwin=$row['BetScore']*$rate;
		$info   =explode("<br>",$row[Middle]);
		$info_tw=explode("<br>",$row[Middle_tw]);
		$info_en=explode("<br>",$row[Middle_en]);
		
		if ($row[Active]==7){
			$middle=$info[0].'<br>'.$info[1].'<br>';
			$middle_tw=$info_tw[0].'<br>'.$info_tw[1].'<br>';
			$middle_en=$info_en[0].'<br>'.$info_en[1].'<br>';
		}else{
			$sid=$info[1];
			$middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
			$middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
			$middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
		}

		$pan=substr($row['M_Place'],1,strlen($row['M_Place']));
		if ($row[Mtype]=='OUC'){
			$mtype='OUH';
			$m_place='大&nbsp;'.$pan;
			$m_place_tw='大&nbsp;'.$pan;
			$m_place_en='O'.$pan;
		}else{
			$mtype='OUC';
			$m_place='小&nbsp;'.$pan;
			$m_place_tw='小&nbsp;'.$pan;
			$m_place_en='U'.$pan;
		}	
		$lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';			
		$lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';			
		$mysql="update ".DBPREFIX."web_report_data set Mtype='$mtype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Place='$m_place_en',M_Rate='$rate',Gwin='$gwin',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Cancel=0,Confirmed=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
		break;
	case 8:
		$middle=explode('<br>',$row['Middle']);
		$middle_tw=explode('<br>',$row['Middle_tw']);
		$middle_en=explode('<br>',$row['Middle_en']);

		$abc=strtolower(substr($letb[$i],0,1));	
		$bid=explode(',',$row['MID']);
		$type=explode(',',$row['Mtype']);
		$rate=explode(',',$row['M_Rate']);
		$letb=explode(',',$row['M_Place']);
		$show=explode(',',$row['ShowType']);
		$cou=sizeof($bid);

		$lines2='';
		$lines2_tw='';
		$lines2_en='';

		$m_type='';
		$m_rate='';
		$m_letb='';
		$vgwin=1;
		for($k=0;$k<$cou;$k++){
			if ($gid==$bid[$k]){
				switch ($row['OpenType']){
				case "A":
					$mrate=1.84-$rate[$k];
					break;
				case "B":
					$mrate=1.86-$rate[$k];
					break;
				case "C":
					$mrate=1.90-$rate[$k];
					break;
				}
				$mrate=number_format($mrate,3);
				$lines2=$lines2.$middle[2*($k)].'<br>';
				$lines2_tw=$lines2_tw.$middle_tw[2*($k)].'<br>';
				$lines2_en=$lines2_en.$middle_en[2*($k)].'<br>';
				$abc=strtolower(substr($letb[$k],0,1));	
				if ($abc=='o' or $abc=='u'){
					$pan=substr($letb[$k],1,strlen($letb[$k]));
					if ($type[$k]=='C'){
						$otype='H';
						$m_place='小 '.$pan;
						$m_place_tw=' '.$pan;
						$m_place_en='U'.$pan;
					}else{
						$otype='C';
						$m_place='大 '.$pan;
						$m_place_tw=' '.$pan;
						$m_place_en='O'.$pan;
					}
					if ($m_type==''){
						$m_type=$otype;
						$m_rate=$mrate;
						$m_letb=$m_place_en;	
					}else{
						$m_type=$m_type.','.$otype;
						$m_rate=$m_rate.','.$mrate;
						$m_letb=$m_letb.','.$m_place_en;	
					}
					
					$lines2=$lines2.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$mrate.'</b></font>'.'<br>';
					$lines2_tw=$lines2_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$mrate.'</b></font>'.'<br>';
					$lines2_en=$lines2_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$mrate.'</b></font>'.'<br>';
				}else{
					//下让盘
					$abcd=str_replace("<font color=#0000BB><b>","&nbsp;&nbsp;",$middle[2*($k)]);
					$abcd=str_replace("</b></b>","</b>",$abcd);
					$abcd=str_replace("</b></font>","&nbsp;&nbsp;",$abcd);

					$abcd_tw=str_replace("<font color=#0000BB><b>","&nbsp;&nbsp;",$middle_tw[2*($k)]);
					$abcd_tw=str_replace("</b></b>","</b>",$abcd_tw);
					$abcd_tw=str_replace("</b></font>","&nbsp;&nbsp;",$abcd_tw);

					$abcd_en=str_replace("<font color=#0000BB><b>","&nbsp;&nbsp;",$middle_en[2*($k)]);
					$abcd_en=str_replace("</b></b>","</b>",$abcd_en);
					$abcd_en=str_replace("</b></font>","&nbsp;&nbsp;",$abcd_en);

					$team=explode("&nbsp;&nbsp;",$abcd);
					$team_tw=explode("&nbsp;&nbsp;",$abcd_tw);
					$team_en=explode("&nbsp;&nbsp;",$abcd_en);
					
					if ($show[$k]=='H'){
						$mb_team=$team[0];
						$tg_team=$team[2];
						$mb_team_tw=$team_tw[0];
						$tg_team_tw=$team_tw[2];
						$mb_team_en=$team_en[0];
						$tg_team_en=$team_en[2];
						if ($type[$k]=='H'){
							$otype='C';
							$m_place=$tg_team;
							$m_place_tw=$tg_team_tw;
							$m_place_en=$tg_team_en;
						}else{
							$otype='H';
							$m_place=$mb_team;
							$m_place_tw=$mb_team_tw;
							$m_place_en=$mb_team_en;
						}
					}else{
						$mb_team=$team[0];
						$tg_team=$team[2];
						$mb_team_tw=$team_tw[0];
						$tg_team_tw=$team_tw[2];
						$mb_team_en=$team_en[0];
						$tg_team_en=$team_en[2];
						if ($mtype[$k]=='H'){
							$otype='C';
							$m_place=$mb_team;
							$m_place_tw=$mb_team_tw;
							$m_place_en=$mb_team_en;
						}else{
							$otype='H';
							$m_place=$tg_team;
							$m_place_tw=$tg_team_tw;
							$m_place_en=$tg_team_en;
						}
					}
					if ($m_type==''){
						$m_type=$otype;
						$m_rate=$mrate;
						$m_letb=$letb[$k];
					}else{
						$m_type=$m_type.','.$otype;
						$m_rate=$m_rate.','.$mrate;
						$m_letb=$m_letb.','.$letb[$k];
					}
					$lines2=$lines2.'<FONT color=#cc0000>'.trim($m_place).'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$mrate.'</b></FONT>'.'<br>';
					$lines2_tw=$lines2_tw.'<FONT color=#cc0000>'.trim($m_place_tw).'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$mrate.'</b></FONT>'.'<br>';
					$lines2_en=$lines2_en.'<FONT color=#cc0000>'.trim($m_place_en).'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$mrate.'</b></FONT>'.'<br>';
		
				}
				$vgwin=$vgwin*(1+$mrate);
			}else{
				if ($m_type==''){
					$m_type=$type[$k];
					$m_rate=$rate[$k];
					$m_letb=$letb[$k];
				}else{
					$m_type=$m_type.','.$type[$k];
					$m_rate=$m_rate.','.$rate[$k];
					$m_letb=$m_letb.','.$letb[$k];
				}
				$lines2=$lines2.$middle[2*($k)].'<br>'.$middle[2*($k)+1].'<br>';
				$lines2_tw=$lines2_tw.$middle_tw[2*($k)].'<br>'.$middle_tw[2*($k)+1].'<br>';
				$lines2_en=$lines2_en.$middle_en[2*($k)].'<br>'.$middle_en[2*($k)+1].'<br>';
				$vgwin=$vgwin*(1+$rate[$k]);
			}
		}
		$vgwin=($vgwin-1)*$row['BetScore'];
		$mysql="update ".DBPREFIX."web_report_data set m_place='$m_letb',vgold='',m_result='',a_result='',w_result='',c_result='',s_result='',d_result='',confirmed=0,cancel=0,gwin='$vgwin',m_rate='$m_rate',mtype='".$m_type."',middle='".$lines2."',middle_tw='".$lines2_tw."',middle_en='".$lines2_en."',updateTime='".date('Y-m-d H:i:s',time())."' where id=$id";
		break;
	case 9:
		switch ($row['OpenType']){
		case "A":
			$rate=1.84-$row['M_Rate'];
			break;
		case "B":
			$rate=1.88-$row['M_Rate'];
			break;
		case "C":
			$rate=1.92-$row['M_Rate'];
			break;
		case "D":
			$rate=1.95-$row['M_Rate'];
			break;
		}
		$rate=number_format($rate,2);
		$gwin=$row['BetScore']*$rate;
		$info   =explode("<br>",$row[Middle]);
		$info_tw=explode("<br>",$row[Middle_tw]);
		$info_en=explode("<br>",$row[Middle_en]);
		$sid=$info[1];
		$middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
		$middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
		$middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
		$team_c=explode("&nbsp;&nbsp;",$info[2]);
		$team=explode("&nbsp;",$team_c[1]);
		$team_t=explode("&nbsp;&nbsp;",$info_tw[2]);
		$team_tw=explode("&nbsp;",$team_t[1]);
		$team_e=explode("&nbsp;&nbsp;",$info_en[2]);
		$team_en=explode("&nbsp;",$team_e[1]);
		
		if ($row[ShowType]=='H'){
			$mb_team=$team[0];
			$tg_team=$team[2];
			$mb_team_tw=$team_tw[0];
			$tg_team_tw=$team_tw[2];
			$mb_team_en=$team_en[0];
			$tg_team_en=$team_en[2];
			if ($row[Mtype]=='RRH'){
				$otype='RRC';
				$m_place=$tg_team;
				$m_place_tw=$tg_team_tw;
				$m_place_en=$tg_team_en;
			}else{
				$otype='RRH';
				$m_place=$mb_team;
				$m_place_tw=$mb_team_tw;
				$m_place_en=$mb_team_en;
			}	
		}else{
			$mb_team=$team[0];
			$tg_team=$team[2];
			$mb_team_tw=$team_tw[0];
			$tg_team_tw=$team_tw[2];
			$mb_team_en=$team_en[0];
			$tg_team_en=$team_en[2];
			if ($row[Mtype]=='RRH'){
				$otype='RRC';
				$m_place=$mb_team;
				$m_place_tw=$mb_team_tw;
				$m_place_en=$mb_team_en;
			}else{
				$otype='RRH';
				$m_place=$tg_team;
				$m_place_tw=$tg_team_tw;
				$m_place_en=$tg_team_en;
			}	
		}
		$lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$mysql="update ".DBPREFIX."web_report_data set Mtype='$otype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Rate='$rate',Gwin='$gwin',vgold='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Confirmed=0,Cancel=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID=$id";
		break;
	case 19:
		switch ($row['OpenType']){
		case "A":
			$rate=1.84-$row['M_Rate'];
			break;
		case "B":
			$rate=1.88-$row['M_Rate'];
			break;
		case "C":
			$rate=1.92-$row['M_Rate'];
			break;
		case "D":
			$rate=1.95-$row['M_Rate'];
			break;
		}
		$rate=number_format($rate,2);
		$gwin=$row['BetScore']*$rate;
		$info   =explode("<br>",$row[Middle]);
		$info_tw=explode("<br>",$row[Middle_tw]);
		$info_en=explode("<br>",$row[Middle_en]);
		$sid=$info[1];
		$middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
		$middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
		$middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
		$team_c=explode("&nbsp;&nbsp;",$info[2]);
		$team=explode("&nbsp;",$team_c[1]);
		$team_t=explode("&nbsp;&nbsp;",$info_tw[2]);
		$team_tw=explode("&nbsp;",$team_t[1]);
		$team_e=explode("&nbsp;&nbsp;",$info_en[2]);
		$team_en=explode("&nbsp;",$team_e[1]);
		if ($row[ShowType]=='H'){
			$mb_team=$team[0];
			$tg_team=$team[2];
			$mb_team_tw=$team_tw[0];
			$tg_team_tw=$team_tw[2];
			$mb_team_en=$team_en[0];
			$tg_team_en=$team_en[2];
			if ($row[Mtype]=='VRRH'){
				$otype='VRRC';
				$m_place=$tg_team;
				$m_place_tw=$tg_team_tw;
				$m_place_en=$tg_team_en;
			}else{
				$otype='VRRH';
				$m_place=$mb_team;
				$m_place_tw=$mb_team_tw;
				$m_place_en=$mb_team_en;
			}	
		}else{
			$mb_team=$team[0];
			$tg_team=$team[2];
			$mb_team_tw=$team_tw[0];
			$tg_team_tw=$team_tw[2];
			$mb_team_en=$team_en[0];
			$tg_team_en=$team_en[2];
			if ($row[Mtype]=='VRRH'){
				$otype='VRRC';
				$m_place=$mb_team;
				$m_place_tw=$mb_team_tw;
				$m_place_en=$mb_team_en;
			}else{
				$otype='VRRH';
				$m_place=$tg_team;
				$m_place_tw=$tg_team_tw;
				$m_place_en=$tg_team_en;
			}	
		}	
		$lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=gray>-[1st]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';	
		$mysql="update ".DBPREFIX."web_report_data set Mtype='$otype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Rate='$rate',Gwin='$gwin',vgold='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Confirmed=0,Cancel=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID=$id";
		break;
	case 10:
		switch ($row['OpenType']){
		case "A":
			$rate=1.84-$row['M_Rate'];
			break;
		case "B":
			$rate=1.88-$row['M_Rate'];
			break;
		case "C":
			$rate=1.90-$row['M_Rate'];
			break;
		case "D":
			$rate=1.92-$row['M_Rate'];
			break;
		}
		$rate=number_format($rate,2);
		$gwin=$row['BetScore']*$rate;
		$info   =explode("<br>",$row[Middle]);
		$info_tw=explode("<br>",$row[Middle_tw]);
		$info_en=explode("<br>",$row[Middle_en]);
		$sid=$info[1];
		$middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
		$middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
		$middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
		
		$pan=substr($row['M_Place'],1,strlen($row['M_Place']));
		if ($row[Mtype]=='ROUC'){
			$mtype='ROUH';
			$m_place='大&nbsp;'.$pan;
			$m_place_tw='大&nbsp;'.$pan;
			$m_place_en='O'.$pan;
		}else{
			$mtype='ROUC';
			$m_place='小&nbsp;'.$pan;
			$m_place_tw='小&nbsp;'.$pan;
			$m_place_en='U'.$pan;
		}	
		$lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$mysql="update ".DBPREFIX."web_report_data set Mtype='$mtype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Place='$m_place_en',M_Rate='$rate',Gwin='$gwin',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Cancel=0,Confirmed=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
		//echo $mysql;
		//exit;
		break;
	case 20:
		switch ($row['OpenType']){
		case "A":
			$rate=1.84-$row['M_Rate'];
			break;
		case "B":
			$rate=1.88-$row['M_Rate'];
			break;
		case "C":
			$rate=1.90-$row['M_Rate'];
			break;
		case "D":
			$rate=1.92-$row['M_Rate'];
			break;
		}
		$rate=number_format($rate,2);
		$gwin=$row['BetScore']*$rate;
		$info   =explode("<br>",$row[Middle]);
		$info_tw=explode("<br>",$row[Middle_tw]);
		$info_en=explode("<br>",$row[Middle_en]);
		$sid=$info[1];
		$middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
		$middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
		$middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
		
		$pan=substr($row['M_Place'],1,strlen($row['M_Place']));
		if ($row[Mtype]=='VROUC'){
			$mtype='VROUH';
			$m_place='大&nbsp;'.$pan;
			$m_place_tw='大&nbsp;'.$pan;
			$m_place_en='O'.$pan;
		}else{
			$mtype='VROUC';
			$m_place='小&nbsp;'.$pan;
			$m_place_tw='小&nbsp;'.$pan;
			$m_place_en='U'.$pan;
		}	
		$lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
		$lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
		$lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=gray>-[1st]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
		$mysql="update ".DBPREFIX."web_report_data set Mtype='$mtype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Place='$m_place_en',M_Rate='$rate',Gwin='$gwin',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Cancel=0,Confirmed=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
		break;
	case 12:
		switch ($row['OpenType']){
		case "A":
			$rate=1.84-$row['M_Rate'];
			break;
		case "B":
			$rate=1.88-$row['M_Rate'];
			break;
		case "C":
			$rate=1.92-$row['M_Rate'];
			break;
		case "D":
			$rate=1.95-$row['M_Rate'];
			break;
		}
		$rate=number_format($rate,2);
		$gwin=$row['BetScore']*$rate;
		$info   =explode("<br>",$row[Middle]);
		$info_tw=explode("<br>",$row[Middle_tw]);
		$info_en=explode("<br>",$row[Middle_en]);
		if ($row[Active]==7){
			$middle=$info[0].'<br>'.$info[1].'<br>';
			$middle_tw=$info_tw[0].'<br>'.$info_tw[1].'<br>';
			$middle_en=$info_en[0].'<br>'.$info_en[1].'<br>';
			$team=explode("&nbsp;&nbsp;",$info[1]);
			$team_tw=explode("&nbsp;&nbsp;",$info_tw[1]);
			$team_en=explode("&nbsp;&nbsp;",$info_en[1]);
			$data=explode("</font>",$info[2]);
			$data_tw=explode("</font>",$info_tw[2]);
			$data_en=explode("</font>",$info_en[2]);

		}else{
			$sid=$info[1];
			$middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
			$middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
			$middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
			//$team=explode("&nbsp;&nbsp;",$info[2]);
			//$team_tw=explode("&nbsp;&nbsp;",$info_tw[2]);
			//$team_en=explode("&nbsp;&nbsp;",$info_en[2]);
			//$data=explode("</font>",$info[3]);
			//$data_tw=explode("</font>",$info_tw[3]);
			//$data_en=explode("</font>",$info_en[3]);
			
			$team_c=explode("&nbsp;&nbsp;",$info[2]);
			$team=explode("&nbsp;",$team_c[1]);
			$team_t=explode("&nbsp;&nbsp;",$info_tw[2]);
			$team_tw=explode("&nbsp;",$team_t[1]);
			$team_e=explode("&nbsp;&nbsp;",$info_en[2]);
			$team_en=explode("&nbsp;",$team_e[1]);
		}
	
		if ($row[ShowType]=='H'){
			$mb_team=$team[0];
			$tg_team=$team[2];
			$mb_team_tw=$team_tw[0];
			$tg_team_tw=$team_tw[2];
			$mb_team_en=$team_en[0];
			$tg_team_en=$team_en[2];
			if ($row[Mtype]=='VRH'){
				$mtype='VRC';
				$m_place=$tg_team;
				$m_place_tw=$tg_team_tw;
				$m_place_en=$tg_team_en;
			}else{
				$mtype='VRH';
				$m_place=$mb_team;
				$m_place_tw=$mb_team_tw;
				$m_place_en=$mb_team_en;
			}	
		}else{
			$mb_team=$team[0];
			$tg_team=$team[2];
			$mb_team_tw=$team_tw[0];
			$tg_team_tw=$team_tw[2];
			$mb_team_en=$team_en[0];
			$tg_team_en=$team_en[2];
			if ($row[Mtype]=='VRH'){
				$mtype='VRC';
				$m_place=$mb_team;
				$m_place_tw=$mb_team_tw;
				$m_place_en=$mb_team_en;
			}else{
				$mtype='VRH';
				$m_place=$tg_team;
				$m_place_tw=$tg_team_tw;
				$m_place_en=$tg_team_en;
			}	
		}
		$lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
		$lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';		
		$lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[1st]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';		
		$mysql="update ".DBPREFIX."web_report_data set Mtype='$mtype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Rate='$rate',Gwin='$gwin',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Checked=0,Cancel=0,Confirmed=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
		break;
	case 13:
		switch ($row['OpenType']){
		case "A":
			$rate=1.84-$row['M_Rate'];
			break;
		case "B":
			$rate=1.88-$row['M_Rate'];
			break;
		case "C":
			$rate=1.90-$row['M_Rate'];
			break;
		case "D":
			$rate=1.92-$row['M_Rate'];
			break;
		}
		$rate=number_format($rate,2);
		$gwin=$row['BetScore']*$rate;
		$info   =explode("<br>",$row[Middle]);
		$info_tw=explode("<br>",$row[Middle_tw]);
		$info_en=explode("<br>",$row[Middle_en]);
		$sid=$info[1];
		$middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
		$middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
		$middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';

		$data=explode("</font>",$info[3]);
		$data_tw=explode("</font>",$info_tw[3]);
		$data_en=explode("</font>",$info_en[3]);

		$team=explode("&nbsp;&nbsp;",$info[2]);
		$team_tw=explode("&nbsp;&nbsp;",$info_tw[2]);
		$team_en=explode("&nbsp;&nbsp;",$info_en[2]);
		$pan=substr($row['M_Place'],1,strlen($row['M_Place']));
		if ($row[Mtype]=='VOUC'){
			$mtype='VOUH';
			$m_place='大&nbsp;'.$pan;
			$m_place_tw='大&nbsp;'.$pan;
			$m_place_en='O'.$pan;
		}else{
			$mtype='VOUC';
			$m_place='小&nbsp;'.$pan;
			$m_place_tw='小&nbsp;'.$pan;
			$m_place_en='U'.$pan;
		}
		$lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'&nbsp;-&nbsp;<font color=#666666>[1st]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';		
		$mysql="update ".DBPREFIX."web_report_data set Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Place='$m_place_en',M_Rate='$rate',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Confirmed=0,Cancel=0,Gwin='$gwin',Mtype='$mtype',updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
		break;
	}
	mysqli_query($dbMasterLink,$mysql);
	$sql='update match_foot set score=0 where mid='.$gid;
	mysqli_query($dbMasterLink,$sql);
	echo "<script languag='JavaScript'>self.location='chkvou.php?uid=$uid&username=$username&date_start=$date_start&langx=$langx'</script>";
	break;
case 2:
	$sql='select betscore,m_result,m_name,pay_type from '.DBPREFIX.'web_report_data where id='.$id;
	$result = mysqli_query($dbLink,$sql);
	$row = mysqli_fetch_assoc($result);
	if ($row['pay_type']==1){
		if ($row['m_result']==''){
			$sql="update ".DBPREFIX.MEMBERTABLE." set money=money+$row[betscore] where memname='".$row[m_name]."'";
		}else{
			$sql="update ".DBPREFIX.MEMBERTABLE." set money=money-$row[m_result] where memname='".$row[m_name]."'";
		}
		mysqli_query($dbMasterLink,$sql);
		
	}
	$sql='update '.DBPREFIX.'web_report_data set vgold=0,m_result=0,a_result=0,w_result=0,c_result=0,s_result=0,d_result=0,confirmed=-11,cancel=1,updateTime=\''.date('Y-m-d H:i:s',time()).'\' where id='.$id;
	mysqli_query($dbMasterLink,$sql);
	echo "<script languag='JavaScript'>self.location='chkvou.php?uid=$uid&username=$username&date_start=$date_start&langx=$langx'</script>";
	break;
case 3:
	$sql='select betscore,m_result,m_name,pay_type from '.DBPREFIX.'web_report_data where id='.$id;
	$result = mysqli_query($dbLink,$sql);
	$row = mysqli_fetch_assoc($result);
	if ($row['pay_type']==1){
		if ($row['m_result']==''){
			$sql="update ".DBPREFIX.MEMBERTABLE." set money=money+$row[betscore] where memname='".$row[m_name]."'";
		}else{
			$sql="update ".DBPREFIX.MEMBERTABLE." set money=money-$row[m_result] where memname='".$row[m_name]."'";
		}
		mysqli_query($dbMasterLink,$sql);
		
	}else{
		$sql="update ".DBPREFIX.MEMBERTABLE." set money=money+$row[betscore] where memname='".$row[m_name]."'";
		mysqli_query($dbMasterLink,$sql);
		
	}
	$mysql="delete from ".DBPREFIX."web_report_data where id=".$id;
	mysqli_query($dbMasterLink,$mysql);
	echo "<script languag='JavaScript'>self.location='chkvou.php?uid=$uid&username=$username&date_start=$date_start&langx=$langx'</script>";
	break;
case 4:
	$sql='select betscore,m_name,pay_type from '.DBPREFIX.'web_report_data where id='.$id;
	$result = mysqli_query($dbLink,$sql);
	$row = mysqli_fetch_assoc($result);
	if ($row['pay_type']==1){
		$sql="update ".DBPREFIX.MEMBERTABLE." set money=money-$row[betscore] where memname='".$row[m_name]."'";
		mysqli_query($dbMasterLink,$sql);
		
	}
	$sql="update ".DBPREFIX."web_report_data set vgold='',m_result='',a_result='',w_result='',c_result='',s_result='',d_result='',confirmed=0,cancel=0,updateTime='".date('Y-m-d H:i:s',time())."' where id=".$id;
	mysqli_query($dbMasterLink,$sql);
	echo "<script languag='JavaScript'>self.location='chkvou.php?uid=$uid&username=".$username."&date_start=".$date_start."'</script>";
	break;

}
$mysql="select ID,MID,Active,LineType,Mtype,Pay_Type,M_Date,BetTime,BetScore,CurType,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,ShowType,Cancel from ".DBPREFIX."web_report_data where M_Name='$username' and M_Date='$date_start' and (linetype=2 or linetype=12 or linetype=22 or linetype=32  or linetype=3  or linetype=13  or linetype=23  or linetype=33  or linetype=9 or linetype=19 or linetype=29 or linetype=39 or linetype=10 or linetype=20 or linetype=30 or linetype=40)  order by BetTime,linetype,mtype";
$result = mysqli_query($dbLink,$mysql);
?>
<HTML>
<HEAD>
<TITLE></TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<META content="Microsoft FrontPage 4.0" name=GENERATOR>
<script language=javascript>
function sbar(st){
st.style.backgroundColor='#BFDFFF';
}
function cbar(st){
st.style.backgroundColor='';
}
</script>
</HEAD>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<form name="myform" method="post" action="finish_score.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
          <td class="m_tline" width="965">注单管理&nbsp;&nbsp;&nbsp;&nbsp;投注日期：<font color="#cc0000"> 
            <?php echo $date_start?>
            </font>&nbsp;&nbsp;&nbsp;&nbsp;帐号:<font color="cc0000"> 
            <?php echo $username?>
            </font>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:history.go( -1 );"> 回上一页</a>&nbsp;&nbsp;</font></font></td>
    
  </tr>
  <tr>
    <td colspan="2" height="4">
<table width="769" border="0" align="left" cellPadding="0" cellSpacing="0" background="/images/body_title_ph12b.gif" class="b_title">
  <tbody>

    <tr>
       <td width="394"><div align="right"></div></td>
                  <td width="375">&nbsp;</td>
    </tr>

  </tbody>
</table>
    </td>
  </tr>
</table>
      <table width="975" border="0" cellspacing="1" cellpadding="0" class="m_tab" bgcolor="#000000">
        <tr class="m_rig_re"> 
          <td width="110"align="center">投注时间</td>
          <td width="70" align="center">用户名称</td>
          <td width="100" align="center">球赛种类</td>
          <td width="273" align="center">內容</td>
          <td width="73" align="center">投注</td>
          <td width="73" align="center">会员</td>
          <td width="105" align="center">注销</td>
          <td width="162" align="center">功能</td>
        </tr>
<?php
while ($row = mysqli_fetch_assoc($result)){
switch($row['Active']){
case 1:
	$betinfo=$Mnu_Soccer;
	break;
case 2:
	$betinfo=$Mnu_Soccer;
	break;
case 3:
	$betinfo=$Mnu_BasketBall;
	break;
case 4:
	$betinfo=$Mnu_Base;
	break;
case 5:
	$betinfo=$Mnu_Tennis;
	break;
case 6:
	$betinfo=$Mnu_Voll;
	break;
case 7:
	$betinfo=$Mnu_Guan;
	break;
}
?>
        <tr class="m_rig"onmouseover=sbar(this) onmouseout=cbar(this)>
          <td align="center"><?php echo $row['BetTime']?></td>
          <td align="center"><?php echo $row['M_Name']?><br><font color="#CC0000"><?php echo $row['OpenType']?></font></td>
          <td align="center"><?php echo $betinfo?><?php echo $row['BetType']?><br><font color="#0000CC"><?php echo show_voucher($row['LineType'],$row['ID'])?></font></td>
          <td align="right"><?php echo $row['Middle']?></td>
          <td align="right"><?php echo $row['BetScore']?></td>
          <td align="right"><?php echo number_format($row['M_result'],1)?></td>
          <td align="center">
<?php
if ($row['Cancel']==1){
	echo '<font color=red><b>已注销</b></font>';
}else{
	echo '<font color=blue><b>正常</b></font>';
}
?>
          </td>
          <td align="center">
		  <a href="chkvou.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&active=1&username=<?php echo $username?>&date_start=<?php echo $date_start?>&langx=<?php echo $langx?>">对调</a>/ 
		  <a href="chkvou.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&active=3&username=<?php echo $username?>&date_start=<?php echo $date_start?>&langx=<?php echo $langx?>">删除</a>
		  </td>
        </tr>
        <?php
}
}
?>
      </table>
</form>       
</BODY>
</html>
