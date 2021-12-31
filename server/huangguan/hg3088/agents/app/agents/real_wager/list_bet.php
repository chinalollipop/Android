<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../../agents/include/address.mem.php";
require ("../../agents/include/define_function_list.inc.php");  
require_once ("../../agents/include/config.inc.php");
include_once ("../include/redis.php");
checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$gid=$_REQUEST["gid"];
$type=$_REQUEST["type"];
$wtype=$_REQUEST["wtype"];
$page=$_REQUEST['page'];

require ("../../agents/include/traditional.$langx.inc.php");

if($_SESSION['Level'] == 'M') {
	$web=DBPREFIX.'web_system_data';
}else{
    $web=DBPREFIX.'web_agents_data';
}

$mysql = "select Level,UserName from $web where Oid='$uid' and UserName='$loginname' and Status<2";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$username=$row['UserName'];
$level=$row['Level'];
if ($level=='M'){
	$user="and Admin='$username'";//总监
}else if ($level=='A'){
	$user="and Super='$username'";//公司
}else if ($level=='B'){
	$user="and Corprator ='$username'";//股东
}else if ($level=='C'){
	$user="and World='$username'";//总代理
}else if ($level=='D'){
	$user="and Agents='$username'";//代理商
}
if ($page==''){
	$page=0;
}
if (in_array($wtype,array('R','OU','M','VR','VOU','VM','RE','ROU','RM','VRE','VROU','VRM','T','EO'))){
	$ktype="and Mtype='$type' and Ptype='$wtype'";
}else{
	$ktype="and Ptype='$wtype'";
}
$sql="select ID,MID,Active,LineType,BetTime,$middle as Middle,$bettype as BetType,BetScore,M_Result,M_Name,OpenType,OddsType from ".DBPREFIX."web_report_data where FIND_IN_SET($gid,MID)>0 ".$user." ".$ktype." order by BetTime asc";
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size";
//echo $mysql;
$result = mysqli_query($dbLink,$mysql);
if ($cou==0){
	$msg=wterror('未搜寻到指定相关资料');
	echo $msg;
	exit;
}
?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<SCRIPT Language="javaScript">
function onLoad() {
    var obj_page = document.getElementById('page');
    obj_page.value = '<?php echo $page?>';
}
// -->
</SCRIPT>
</head>
<body onLoad="onLoad()"; oncontextmenu="window.event.returnValue=false" text="#000000" leftmargin="0" topmargin="0" vlink="#0000FF" alink="#0000FF">
<FORM NAME="LAYOUTFORM" ACTION="" method="POST">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="m_tline">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>&nbsp;&nbsp;日期&nbsp;:&nbsp;2009-11-27~2009-11-27&nbsp;--&nbsp;下注管道&nbsp;:&nbsp;網路下注&nbsp;--&nbsp;下注總頁數&nbsp;:&nbsp;</td>
						<td>
							<select id="page" name="page" onChange="self.LAYOUTFORM.submit()" class="za_select">
								<?php
								for($i=0;$i<$page_count;$i++){
								    echo "<option value='$i'>".($i+1)."</option>";
								}
								?>
							</select>
						</td>
						<td>&nbsp;/&nbsp;<?php echo $page_count?>&nbsp;<?php echo $Mem_Page?></td>
					</tr>
				</table>
			</td>
			
		</tr>
		<tr>
			<td colspan="2" height="4"></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;&nbsp;<font color="#000099">線上操盤</font>&nbsp;--&nbsp;<font color="#CC0000">即時隊伍下注單</font></td>
		</tr>
	</table>
	<table width="780" border="0" cellspacing="1" cellpadding="0" class="m_tab">
		<tr class="m_title">
			<td width="55">時間</td>
			<td width="120">收中比</td>
			<td width="90">單號</td>
			<td width="275">內容</td>
			<td width="120">金額</td>
			<td width="120">結果</td>
		</tr>
<?php
while ($row=mysqli_fetch_assoc($result)){
$datetime=strtotime($row['BetTime']);
$time=date("H:i:s",$datetime);
$active=$row['Active'];
switch($row['Active']){
case 1:
	$Title=$Rep_Soccer;
	break;
case 11:
	$Title=$Rep_Soccer;
	break;
case 2:
	$Title=$Rep_Bask;
	break;
case 22:
	$Title=$Rep_Bask;
	break;
case 3:
	$Title=$Rep_Base;
	break;
case 33:
	$Title=$Rep_Base;
	break;
case 4:
	$Title=$Rep_Tennis;
	break;
case 44:
	$Title=$Rep_Tennis;
	break;
case 5:
	$Title=$Rep_Volley;
	break;
case 55:
	$Title=$Rep_Volley;
	break;
case 6:
	$Title=$Rep_Other;
	break;
case 66:
	$Title=$Rep_Other;
	break;
case 7:
	$Title=$Rep_Outright;
	break;
}
switch ($row['OddsType']){
case 'H':
    $Odds='<font color =green>'.$Rep_HK.'</font><BR>';
	break;
case 'M':
    $Odds='<font color =green>'.$Rep_Malay.'</font><BR>';
	break;
case 'I':
    $Odds='<font color =green>'.$Rep_Indo.'</font><BR>';
	break;
case 'E':
    $Odds='<font color =green>'.$Rep_Euro.'</font><BR>';
	break;
case '':
    $Odds='';
	break;
}
$icount+=1;
$score=$score+$row['BetScore'];
$mresult=$mresult+$row['M_Result'];
?>
		<tr class="m_cen">
			<td align="center"><?php echo $time?></td>
			<td><?php echo $row['M_Name']?><BR><?php echo $row['OpenType']?>&nbsp;&nbsp;</td>
			<td nowrap><?php echo $Title?><?php echo $row['BetType']?><BR><?php echo $Odds?><?php echo substr(show_voucher($row['LineType'],$row['ID']),2,10)?></td>
			<td align="right">
<?php
if ($row['Active']==$active){	
	if ($row['LineType']==8){
		$midd=explode('<br>',$row['Middle']);
		$mid=explode(',',$row['MID']);
		$show=explode(',',$row['ShowType']);
		$mtype=explode(',',$row['Mtype']);
		for($t=0;$t<(sizeof($midd)-1)/3;$t++){
			echo $midd[3*$t].'<br>';
			$mysql="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$mid[$t];
			$result1 = mysqli_query($dbLink,$mysql);
			$row1 = mysqli_fetch_assoc($result1);
		    if ($row1["MB_Inball"]=='-1'){
	            $font_a3='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-2'){     
	            $font_a3='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';	
		    }else if ($row1["MB_Inball"]=='-3'){      
	            $font_a3='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';	
		    }else if ($row1["MB_Inball"]=='-4'){     
	            $font_a3='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-5'){     
	            $font_a3='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-6'){     
	            $font_a3='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-7'){     
	            $font_a3='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-8'){     
	            $font_a3='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-9'){     
	            $font_a3='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-10'){     
	            $font_a3='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-11'){
	            $font_a3='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-12'){     
	            $font_a3='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';	
		    }else if ($row1["MB_Inball"]=='-13'){      
	            $font_a3='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';	
		    }else if ($row1["MB_Inball"]=='-14'){     
	            $font_a3='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-15'){     
	            $font_a3='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-16'){     
	            $font_a3='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-17'){     
	            $font_a3='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-18'){     
	            $font_a3='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-19'){     
	            $font_a3='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';	  	 	  
		    }else{
		    	$font_a3='<font color="#009900"><b>'.$row1["TG_Inball"].'</b> : <b>'.$row1["MB_Inball"].'</b></font>&nbsp;';
		    	$font_a4='<font color="#009900"><b>'.$row1["MB_Inball"].'</b> : <b>'.$row1["TG_Inball"].'</b></font>&nbsp;';
		    }
			echo $midd[3*$t+1].'<br>';
		    if ($show[$t]=='C' and ($mtype[$t]=='RH' or $mtype[$t]=='RC') and $row['LineType']==8){
			    echo $font_a3;
		    }else{
			    echo $font_a4;
		    }
			echo $midd[3*$t+2].'<br>';
		}
	}else if ($row['LineType']==16){
		$midd=explode('<br>',$row['Middle']);
		for($t=0;$t<sizeof($midd)-1;$t++){
			echo $midd[$t].'<br>';
		}
			$mysql="select MB_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where ID=".$row['MID'];
			$result1 = mysqli_query($dbLink,$mysql);
			$row1 = mysqli_fetch_assoc($result1);
		    if ($row1["MB_Inball"]=='-1'){
	            $lnball='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-2'){     
	            $lnball='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-3'){      
	            $lnball='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-4'){     
	            $lnball='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-5'){     
	            $lnball='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-6'){     
	            $lnball='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-7'){     
	            $lnball='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-8'){     
	            $lnball='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-9'){     
	            $lnball='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-10'){     
	            $lnball='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-11'){
	            $lnball='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-12'){     
	            $lnball='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-13'){      
	            $lnball='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-14'){     
	            $lnball='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-15'){     
	            $lnball='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-16'){     
	            $lnball='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-17'){     
	            $lnball='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-18'){     
	            $lnball='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';	  
		    }else if ($row1["MB_Inball"]=='-19'){     
	            $lnball='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';	  	 	  
		    }else{
		    	$lnball='<font color="#009900"><b>'.$row1["MB_Inball"].'</b></font>&nbsp;';
		    }
		    if ($row1["MB_Inball"]==1){
			    echo '<font color="#009900"><b>冠军&nbsp;</b></font>';
			}else if ($row1["MB_Inball"]==0){
			    echo '<font color="#009900"><b>失败&nbsp;</b></font>';
		    }else if ($row1["MB_Inball"]<0){
			    echo $lnball;
		    }
			echo $midd[sizeof($midd)-1];
	}else{
		$midd=explode('<br>',$row['Middle']);
		for($t=0;$t<sizeof($midd)-1;$t++){
			echo $midd[$t].'<br>';
		}
		$mysql="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$row['MID'];
		$result1 = mysqli_query($dbLink,$mysql);
		$row1 = mysqli_fetch_assoc($result1);
		
        if ($row1["MB_Inball"]=='-1'){
            if($row1["MB_Inball_HR"]=='-1' and $row1["MB_Inball"]=='-1'){
	           $font_a1='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-2'){
            if($row1["MB_Inball_HR"]=='-2' and $row1["MB_Inball"]=='-2'){
	           $font_a1='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-3'){
            if($row1["MB_Inball_HR"]=='-3' and $row1["MB_Inball"]=='-3'){
	           $font_a1='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-4'){
            if($row1["MB_Inball_HR"]=='-4' and $row1["MB_Inball"]=='-4'){
	           $font_a1='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-5'){
            if($row1["MB_Inball_HR"]=='-5' and $row1["MB_Inball"]=='-5'){
	           $font_a1='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-6'){
            if($row1["MB_Inball_HR"]=='-6' and $row1["MB_Inball"]=='-6'){
	           $font_a1='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-7'){
            if($row1["MB_Inball_HR"]=='-7' and $row1["MB_Inball"]=='-7'){
	           $font_a1='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-8'){
            if($row1["MB_Inball_HR"]=='-8' and $row1["MB_Inball"]=='-8'){
	           $font_a1='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-9'){
            if($row1["MB_Inball_HR"]=='-9' and $row1["MB_Inball"]=='-9'){
	           $font_a1='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-10'){
            if($row1["MB_Inball_HR"]=='-10' and $row1["MB_Inball"]=='-10'){
	           $font_a1='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-11'){
            if($row1["MB_Inball_HR"]=='-11' and $row1["MB_Inball"]=='-11'){
	           $font_a1='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-12'){
            if($row1["MB_Inball_HR"]=='-12' and $row1["MB_Inball"]=='-12'){
	           $font_a1='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-13'){
            if($row1["MB_Inball_HR"]=='-13' and $row1["MB_Inball"]=='-13'){
	           $font_a1='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-14'){
            if($row1["MB_Inball_HR"]=='-14' and $row1["MB_Inball"]=='-14'){
	           $font_a1='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-15'){
            if($row1["MB_Inball_HR"]=='-15' and $row1["MB_Inball"]=='-15'){
	           $font_a1='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-16'){
            if($row1["MB_Inball_HR"]=='-16' and $row1["MB_Inball"]=='-16'){
	           $font_a1='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-17'){
            if($row1["MB_Inball_HR"]=='-17' and $row1["MB_Inball"]=='-17'){
	           $font_a1='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-18'){
            if($row1["MB_Inball_HR"]=='-18' and $row1["MB_Inball"]=='-18'){
	           $font_a1='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-19'){
            if($row1["MB_Inball_HR"]=='-19' and $row1["MB_Inball"]=='-19'){
	           $font_a1='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
            }  
        }else{
	           $font_a3='<font color="#009900"><b>'.$row1["TG_Inball"].'</b> : <b>'.$row1["MB_Inball"].'</b></font> &nbsp;';
	           $font_a4='<font color="#009900"><b>'.$row1["MB_Inball"].'</b> : <b>'.$row1["TG_Inball"].'</b></font>&nbsp; ';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp; ';
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp; ';
        }
		
		if ($row['LineType']==11 or $row['LineType']==12 or $row['LineType']==13 or $row['LineType']==14 or $row['LineType']==19 or $row['LineType']==20 or $row['LineType']==31){
			if ($row['ShowType']=='C' and ($row['LineType']==12 or $row['LineType']==19)){
				echo $font_a1;
			}else{
				echo $font_a2;
			}
		}else{
			if ($row['ShowType']=='C' and ($row['LineType']==2 or $row['LineType']==9)){
				echo $font_a3;
		    }else{
			    echo $font_a4;
		    }
		}
	    echo $midd[sizeof($midd)-1];
}

}else{
	echo $row['Middle'];
}
?>
			</td>
			<td align="right"><?php echo number_format($row['BetScore'],2)?></td>
			<td align="right"><?php echo number_format($row['M_Result'],2)?></td>
		</tr>
<?php
}
?>
		<tr class="m_rig">
			<td colspan="3">&nbsp;</td>
			<td bgcolor="dcdcdc"><?php echo $icount?></td>
			<td bgcolor="dcdcdc"><?php echo number_format($score,2)?></td>
			<td ><?php echo number_format($mresult,2)?></td>
		</tr>
	</table>
</form>
</body>
</html>

