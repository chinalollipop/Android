<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$gtype=$_REQUEST['gtype'];
$gdate=$_REQUEST['date_start'];
$gdate1=$_REQUEST['date_end'];
$mDate=$_REQUEST['today_gmt'];
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$username=$_SESSION['UserName'];
$page=$_REQUEST['page'];
$pagesize=10; // 每页显示条数
if ($page==''){
	$page=0;
}
if($page<=0){
    $page=0;
}

$view_date=explode('-',$mDate);
$abc=date('d')-$view_date[2];
$t = time()-$abc*24*60*60;
$xq = array("$His_Week_Sun","$His_Week_Mon","$His_Week_Tue","$His_Week_Wed","$His_Week_Thu","$His_Week_Fri","$His_Week_Sat");
if ($gtype=='ALL'){
	$gtype='FT';
}

$mysql = "select ID,MID,Active,LineType,Mtype,M_Date,BetTime,$bettype as BetType,$middle as Middle,BetScore,ShowType,M_Place,M_Rate,OddsType,VGOLD,M_Result,betid,Cancel,Confirmed from ".DBPREFIX."web_report_data where M_Name='$username' and M_Date='$mDate' and M_Result!='' order by orderby,bettime desc ";
$myresult = mysqli_query($dbLink, $mysql);
$cou_num=mysqli_num_rows($myresult); // 总数
$totalBetScore = $totalVgold = $totalResult = 0;
while($myrow=mysqli_fetch_assoc($myresult)){
    $totalBetScore+=$myrow['BetScore'];  //投注总计
    $totalVgold += $myrow['VGOLD']; //有效投注总计
    $totalResult+=$myrow['M_Result'];  //结果总计
}
$page_count=ceil($cou_num/$pagesize); // 总页数
//echo $page_count;
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<link rel="stylesheet" href="/style/member/mem_body_his.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<script>
function toPage(v){
	self.location='./history_view.php?today_gmt=<?php echo $mDate?>&uid=<?php echo $uid?>&langx=<?php echo $langx?>&page='+v;
}
</script>
<body id="M<?php echo $gtype?>" class="bodyset HIS overflow_y" >
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td class="top">
      <h1><em><?php echo $view_date[0]?><?php echo $His_year?><?php echo $view_date[1]?><?php echo $His_month?><?php echo $view_date[2]?><?php echo $His_date?></em></h1>
    </td>
  </tr>
  <tr>
    <td class="mem">
    <h2>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
              <tr>
                <td id="" width="100"><?php echo $page+1;?> / <?php echo $page_count?> 页 <select name='page' onChange="toPage(this.value);">
<?php
if ($page_count==0){
    $page_count=1;
	}
	for($i=0;$i<$page_count;$i++){
		if ($i==$page){
			echo "<option selected value='$i'>".($i+1)."</option>";
		}else{
			echo "<option value='$i'>".($i+1)."</option>";
		}
	}
?>  
            </select></td>
                <td align="right">
				  
                <span class="wag_btn"><a style="color:#FFF;" href="history_data.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>">返回账户历史摘要</a></span>
                
 
                </td>
              </tr>
            </table>          
     </h2>


    <table border="0" cellspacing="0" cellpadding="0" class="game">
      <tr> 
      	<th class="his_wag" style="width: 30px;">编号</th>
        <th class="his_wag"><?php echo $His_Betting_Time?></th>
        <th class="his_wag" ><?php echo $His_Wager_No?></th>
        <th class="his_time"><?php echo $His_Explain?></th>
        <th class="his_wag"><?php echo $His_Quinella?></th>
        <th class="his_wag">有效投注</th>
        <th class="his_wag"><?php echo $His_Result?></th>
        <th class="his_wag">注单状态</th>
      </tr>

<?php
$num=0;
$quinella=0;
$m_result=0;
$sql = "select ID,MID,Userid,Active,orderNo,LineType,Mtype,Pay_Type,M_Date,BetTime,BetScore,CurType,Middle,Middle_tw,Middle_en,BetType,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,OddsType,ShowType,Cancel,Ptype,MB_ball,TG_ball,Edit,Confirmed,Gtype,Danger,Checked,sendAwardTime,sendAwardIsAuto,playSource,betid from ".DBPREFIX."web_report_data where M_Name='$username' and M_Date='$mDate' and M_Result!='' order by orderby,bettime desc limit ". $page*$pagesize .", $pagesize";
$result=mysqli_query($dbLink,$sql);
while($row=mysqli_fetch_assoc($result)){
$time=strtotime($row['BetTime']);
$times=date("m月d日, H:i:s",$time);

switch($row['Active']){
case 1:
	$Title=$His_Soccer;
	$data='match_foot';
	break;
case 11:
	$Title=$His_Soccer;
	$data='match_foot';
	break;
case 2:
	$Title=$His_Baseketball;
	$data='match_bask';
	break;
case 22:
	$Title=$His_Baseketball;
	$data='match_bask';
	break;
case 3:
	$Title=$His_BaseBall;
	$data='match_base';
	break;
case 33:
	$Title=$His_BaseBall;
	$data='match_base';
	break;
case 4:
	$Title=$His_Tennis;
	$data='match_tennis';
	break;
case 44:
	$Title=$His_Tennis;
	$data='match_tennis';
	break;
case 5:
	$Title=$His_VolleyBall;
	$data='match_volley';
	break;
case 55:
	$Title=$His_VolleyBall;
	$data='match_volley';
	break;
case 6:
	$Title=$His_Other;
	$data='match_other';
	break;
case 66:
	$Title=$His_Other;
	$data='match_other';
	break;
case 7:
	$Title=$His_Outright;
	$data='match_crown';
	break;
}

switch ($row['OddsType']){
case 'H':
    $Odds='<BR>(<font color =green>'.$Tod_HK.'</font>)';
	break;
case 'M':
    $Odds='<BR>(<font color =green>'.$Tod_Malay.'</font>)';
	break;
case 'I':
    $Odds='<BR>(<font color =green>'.$Tod_Indo.'</font>)';
	break;
case 'E':
    $Odds='<BR>(<font color =green>'.$Tod_Euro.'</font>)';
	break;
default:
    $Odds='';
	break;
}

if ($row['Cancel']==1 or $row['LineType']==9 or $row['LineType']==19 or $row['LineType']==10 or $row['LineType']==20 or $row['LineType']==21 or $row['LineType']==31){
	//$datetime="<font color='#FFFFFF'><span style='background-color: #FF0000'>".$times."</span></font>";
}
	$datetime=$times;	   
if ($row['Cancel']==1) {
    $betscore='<S>'.number_format($row['BetScore'],2).'</S>';
}else{
    $betscore=number_format($row['BetScore'],2);
}
?>
        <tr class="b_rig">
        <td align="center"><?php echo $row['orderNo']?></td>
          <td align="left">
          <?php echo $datetime?>
          <?php echo $Odds?></td>
          <td align="center" nowrap data-linetype="<?php echo $row['LineType']?>" data-mtype="<?php echo $row['Mtype']?>" data-oddtype="<?php echo $row['OddsType']?>" data-showtype="<?php echo $row['ShowType']?>" data-ptype="<?php echo $row['Ptype']?>" data-canl="<?php echo $row['Cancel']?>" ><?php echo $Title?><br>
<?php echo $row['BetType']?></td>
          <td class="explain" align="left">		  
<?php
	if ($row['LineType']==8){
		$midd=explode('<br>',$row['Middle']);
		$mid=explode(',',$row['MID']);
		$show=explode(',',$row['ShowType']);
		$mtype=explode(',',$row['Mtype']);
		for($t=0;$t<(sizeof($midd)-1)/3;$t++){
			echo $midd[3*$t].'<br>';
			$mysql="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where MID=".$mid[$t];
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
		    }else if ($row1["MB_Inball"]=='-42'){
	            $font_a3='<font color="#009900"><b>'.$Score42.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score42.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-51'){
	            $font_a3='<font color="#009900"><b>'.$Score51.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score51.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-52'){
	            $font_a3='<font color="#009900"><b>'.$Score52.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score52.'</b></font>&nbsp;';
		    }else if ($row1["MB_Inball"]=='-53'){
	            $font_a3='<font color="#009900"><b>'.$Score53.'</b></font>&nbsp;';
	            $font_a4='<font color="#009900"><b>'.$Score53.'</b></font>&nbsp;';
		    }else{
		    	$font_a3='<font color="#009900"><b>'.$row1["TG_Inball"].':'.$row1["MB_Inball"].'</b></font>&nbsp;';
		    	$font_a4='<font color="#009900"><b>'.$row1["MB_Inball"].':'.$row1["TG_Inball"].'</b></font>&nbsp;';
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
			$mysql="select MB_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where MID=".$row['MID'];
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
		$mysql="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,Cancel from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where MID=".$row['MID'];
		$result1 = mysqli_query($dbLink,$mysql);
		$row1 = mysqli_fetch_assoc($result1);
		
        if ($row1["MB_Inball"]=='-1'){
            if($row1["MB_Inball_HR"]=='-1' and $row1["MB_Inball"]=='-1'){
	           $font_a1='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
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
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
            }  
        }else if ($row1["MB_Inball"]=='-51'){
            if($row1["MB_Inball_HR"]=='-51' and $row1["MB_Inball"]=='-51'){
	           $font_a1='<font color="#009900"><b>'.$Score51.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score51.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score51.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score51.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score51.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score51.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-52'){
            if($row1["MB_Inball_HR"]=='-52' and $row1["MB_Inball"]=='-52'){
	           $font_a1='<font color="#009900"><b>'.$Score52.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score52.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score52.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score52.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score52.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score52.'</b></font>&nbsp;';
            }
        }else if ($row1["MB_Inball"]=='-53'){
            if($row1["MB_Inball_HR"]=='-53' and $row1["MB_Inball"]=='-53'){
	           $font_a1='<font color="#009900"><b>'.$Score53.'</b></font>&nbsp;';
	           $font_a2='<font color="#009900"><b>'.$Score53.'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score53.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score53.'</b></font>&nbsp;';
            }else{
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp;';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp;';
	           $font_a3='<font color="#009900"><b>'.$Score53.'</b></font>&nbsp;';
	           $font_a4='<font color="#009900"><b>'.$Score53.'</b></font>&nbsp;';
            }
        }else{
	           $font_a3='<font color="#009900"><b>'.$row1["TG_Inball"].':'.$row1["MB_Inball"].'</b></font> &nbsp;';
	           $font_a4='<font color="#009900"><b>'.$row1["MB_Inball"].':'.$row1["TG_Inball"].'</b></font>&nbsp; ';
	           $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].':'.$row1["MB_Inball_HR"].'</b></font>&nbsp; ';
	           $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].':'.$row1["TG_Inball_HR"].'</b></font>&nbsp; ';
        }
        $font_a='';
        if( in_array($row['LineType'],array(11,12,13,14,15,16,19,20,31,46,50,165,204,205,206,244)) && $row['Gtype']=="FT"){
            if ($row['ShowType']=='C' and ($row['LineType']==12 or $row['LineType']==19)){
                echo $font_a1;
                $font_a=$font_a1;
            }else{
                echo $font_a2;
                $font_a=$font_a2;
            }
        } else{
			if ($row['ShowType']=='C' and ( $row['LineType']==2 or $row['LineType']==9 or $row['LineType']==39 or $row['LineType']==139)){ // 主队
                echo $font_a3;
                $font_a=$font_a3;
            }else{
                echo $font_a4;
                $font_a=$font_a4;
			}
		}
	    echo $midd[sizeof($midd)-1];
        echo '<input type="hidden" data-mbball="'.$row1['MB_Inball'].'" data-tgball="'.$row1['TG_Inball'].'" data-mbhrball="'.$row1['MB_Inball_HR'].'" data-tghrball="'.$row1['TG_Inball_HR'].'">';
    }

?>
          </td>
          <td align="center" class="bet_amount">
<?php
if ($row['Cancel']!=1){
    echo number_format($row["BetScore"],2);
}else{
    echo "<S>".number_format($row["BetScore"],2)."</S>";
}
?>
        </td>
        <td align="center"><?php echo number_format($row["VGOLD"],2);?></td>
        <td align="center">
<?php
if ($row['Cancel']==1){
	switch($row['Confirmed']){
		case -1:
		$zt=$Score21;
		break;
		case -2:
		$zt=$Score22;
		break;
		case -3:
		$zt=$Score23;
		break;
		case -4:
		$zt=$Score24;
		break;
		case -5:
		$zt=$Score25;
		break;
		case -6:
		$zt=$Score26;
		break;
		case -7:
		$zt=$Score27;
		break;
		case -8:
		$zt=$Score28;
		break;
		case -9:
		$zt=$Score29;
		break;
		case -10:
		$zt=$Score30;
		break;
		case -11:
		$zt=$Score31;
		break;
		case -12:
		$zt=$Score32;
		break;
		case -13:
		$zt=$Score33;
		break;
		case -14:
		$zt=$Score34;
		break;
		case -15:
		$zt=$Score35;
		break;
		case -16:
		$zt=$Score36;
		break;
		case -17:
		$zt=$Score37;
		break;
		case -18:
		$zt=$Score38;
		break;
		case -19:
		$zt=$Score39;
		break;
		case -20:
		$zt=$Score40;
		break;
		case -21:
		$zt=$Score41;
		break;
	}
	 echo "<font color=#cc0000><b>".$zt."</b></font>";
}else{
	 echo number_format($row['M_Result'],2);
}
?>
          </td>
          <td align="center"><?php 
          	if($row['Cancel']==1){
          		echo "已退款<br/>";
          	}else{
	          	if(number_format($row['M_Result'],2)>0){
	          		if($row['Gwin']/$row['M_Result']==2){
		  				echo "赢一半<br/>";		
		  			}else{
		  				echo "赢<br/>";	
		  			}
	            }elseif(number_format($row['M_Result'],2)<0){
		  			if($row['BetScore']/$row['M_Result']==-2){
		  				echo "输一半<br/>";		
		  			}else{
		  				echo "输<br/>";		
		  			}
		  		}elseif(number_format($row['M_Result'],2)==0){
		  			echo "和局退款<br/>";
		  		}
          	}
            
			if($row['LineType']==8){
                $midd=explode('<br>',$row['Middle']);
                $show=explode(',',$row['ShowType']);
                for($t=0;$t<(sizeof($midd)-1)/3;$t++){
                    $mysql="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where MID=".$mid[$t];
                    $result1 = mysqli_query($dbLink,$mysql);
                    $row1 = mysqli_fetch_assoc($result1);
                    $font_a3='<font color="#009900"><b>'.$row1["TG_Inball"].':'.$row1["MB_Inball"].'</b></font>&nbsp;<br>';
                    $font_a4='<font color="#009900"><b>'.$row1["MB_Inball"].':'.$row1["TG_Inball"].'</b></font>&nbsp;<br>';
                    if ($show[$t]=='C' and ($mtype[$t]=='RH' or $mtype[$t]=='RC') and $row['LineType']==8){
                        echo $font_a3;
                    }else{
                        echo $font_a4;
                    }
                }

            }else{
                $font_a = str_replace('#009900','red',$font_a);
                echo $font_a;
            }
		  ?></td>
        </tr>
<?php
$num++;
$quinella+=$row['BetScore'];  //单页面投注
$valid_money+=$row['VGOLD'];  //单页面有效投注
$m_result+=$row['M_Result'];  //单页面结果
}
?>

      <tr class="sum_bar right">
        <td class="his_total" colspan="4">此页面统计：</td>
        <td class="his_total" align="center"><?php echo number_format($quinella, 2)?></td>
        <td class="his_total"><?php echo number_format($valid_money, 2)?></td>
        <td class="his_total"><?php echo number_format($m_result, 2)?></td>
        <td></td>
      </tr>
        <tr class="sum_bar right">
            <td class="his_total" colspan="4">页面共计：</td>
            <td class="his_total" align="center"><?php echo number_format($totalBetScore, 2)?></td>
            <td class="his_total"><?php echo number_format($totalVgold, 2)?></td>
            <td class="his_total"><?php echo number_format($totalResult, 2)?></td>
            <td></td>
        </tr>
    </table> 
    </td>
  </tr>
  <tr>
    <td class="mem">      <h2><table style="font-size:12px; color:#CCC;" width="100%" border="0" cellpadding="0" cellspacing="0" id="favb">
      <tr>
        <td height="30" align="center" valign="middle">
            <strong>&lt;&lt;</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php
            if(($page+1)==1){
                echo '<a style="color:#ccc; font-weight: normal;" >上一页</a>';
            }else{
                echo '<a style="color:#ccc; font-weight: normal;" href="./history_view.php?today_gmt='.$mDate.'&uid='.$uid.'&langx='.$langx.'&page='.($page-1).'">上一页</a>';
            }
            ?>
            &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <?php
            if($page_count==($page+1)){
                echo '<a style="color:#ccc; font-weight: normal;" >下一页</a>';
            }else{
                echo '<a style="color:#ccc; font-weight: normal;" href="./history_view.php?today_gmt='.$mDate.'&uid='.$uid.'&langx='.$langx.'&page='.($page+1).'">下一页</a>';
            }
            ?>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>&gt;&gt;</strong>
        </td>
      </tr>
    </table></h2></td></tr>
  <tr><td id="foot"><b>&nbsp;</b></td></tr>
</table>


</body>
</html>
