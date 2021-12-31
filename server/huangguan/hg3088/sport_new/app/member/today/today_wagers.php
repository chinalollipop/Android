<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
// 
require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$chk_cw=$_REQUEST['chk_cw'];
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>top.location.href='/'</script>";
	exit;
}
$name=$_SESSION['UserName'];
if ($chk_cw=='' or $chk_cw=='Y'){
	$chk_cw='N';
	$display='Y';
	$ncancel=" and Cancel=0 and M_Result='' ";
	$caption=$Tod_Watch_Canceled_Wagers;
	$nosql = "select ID from ".DBPREFIX."web_report_data where M_Name='$name' and Cancel=1 and M_Date='".date('Y-m-d')."' order by BetTime desc";
}else{
	$chk_cw='Y';
	$display='N';
	$ncancel=" and Cancel=1 and M_Date='".date('Y-m-d')."'";	
	$caption=$Tod_Watch_Normal_Wagers;
	$nosql = "select ID from ".DBPREFIX."web_report_data where M_Name='$name' and Cancel=0 and M_Result='' order by BetTime desc";
}
$mDate=date('Y-m-d');
$sql = "select ID,MID,LineType,Active,Gtype,M_Date,BetTime,orderNo,$middle as Middle,$bettype as BetType,BetScore,Gwin,MB_ball,TG_ball,OddsType,Cancel,Danger,Confirmed from ".DBPREFIX."web_report_data where M_Name='$name' ".$ncancel." order by BetTime desc";
//echo $sql;
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result); // 总数


$resultn = mysqli_query($dbLink,$nosql);
$nocount=mysqli_num_rows($resultn);
//$result = mysqli_query($dbMasterLink,$sql);
//$cou=mysqli_num_rows($result);
$page=$_REQUEST['page'];
if ($page==''){
	$page=0;
}
$page_size=10;
$page_count=ceil($cou/$page_size); // 总页数
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
$result = mysqli_query($dbLink, $mysql);
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<link rel="stylesheet" href="/style/member/mem_body_his.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script>
function wagers_sta(cw){
	self.location='./today_wagers.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&chk_cw='+cw;
}
function toPage(v){
	self.location='./today_wagers.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&page='+v;
}
</script>
</head>

<body id="M<?php echo $gtype?>" class="bodyset HIS overflow_y" >
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td class="top">
      <h1><em><?php echo $Transaction_Record?></em></h1>
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
				<?php if ($display=='Y'){ ?>
				<?php if($nocount>0){ ?>
				<span onClick="wagers_sta('<?php echo $chk_cw?>');"  class="wag_btn">您有<span class="wag_none"> (<?php echo $nocount ?>) </span>张取消注单</span>
                <?php }else{ ?>
                    <span class="wag_btn2" onClick="wagers_sta('<?php echo $chk_cw?>');">您有<span class="wag_none"> (<?php echo $nocount ?>) </span>张取消注单</span>
                <?php } ?>
                
                <?php }else{ ?>
                <span onClick="wagers_sta('<?php echo $chk_cw?>');"  class="wag_btn">您有<span class="wag_none"> (<?php echo $nocount ?>) </span>张有效注单</span>
                
                <?php } ?>
                </td>
              </tr>
            </table>          
     </h2>


    <table border="0" cellspacing="0" cellpadding="0" class="game">
<?php
if($cou==0){
?>
      <tr> 
        <td height="70" class="b_cen"><?php echo $Tod_text?></td>
      </tr>
<?php
}else{
?>
      <tr> 
     	<th class="his_wag" style="width:30px;">编号</th>
        <th class="his_wag"><?php echo $Tod_Betting_Time?></th>
        <th class="his_wag" ><?php echo $Tod_Wager_No?></th>
        <th class="his_time"><?php echo $Tod_Explain?></th>
        <th class="his_wag"><?php echo $Tod_Quinella?></th>
        <th class="his_wag"><?php echo $Tod_Estimated_Payout?></th>
        <th class="his_wag">即时比分</th>
        <th class="his_wag">注单状态</th>
      </tr>
<?php
$tod_num=0;
$tod_bet=0;
$tod_win=0;
while ($row = mysqli_fetch_assoc($result)){
$time=strtotime($row['BetTime']);
$times=date("m月d日, H:i:s",$time);
switch($row['Active']){
	case 1:
		$Title=$Tod_Soccer;
		break;
	case 11:
		$Title=$Tod_Soccer;
		break;
	case 2:
		$Title=$Tod_Basketball;
		break;
	case 22:
		$Title=$Tod_Basketball;
		break;
	case 3:
		$Title=$Tod_Baseball;
		break;
	case 33:
		$Title=$Tod_Baseball;
		break;
	case 4:
		$Title=$Tod_Tennis;
		break;
	case 44:
		$Title=$Tod_Tennis;
		break;
	case 5:
		$Title=$Tod_VolleyBall;
		break;
	case 55:
		$Title=$Tod_VolleyBall;
		break;
	case 6:
		$Title=$Tod_Other;
		break;
	case 66:
		$Title=$Tod_Other;
		break;
	case 7:
		$Title=$Tod_Outright;
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
	case '':
	    $Odds='';
		break;
}
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
if ($row['M_Date']>$mDate){
	$tDate='<b>'.$row['M_Date'].'</b>';
	if ($row['LineType']==7 or $row['LineType']==8){
		$middle="<font color=#000000>".$tDate."</font>&nbsp;&nbsp;&nbsp;".$row['Middle'];
	}else{
		if ($row['active']!=6){
			$data1=explode("<br>",$row['Middle']);
			$middle=$data1[0].'<br>';
			$middle=$middle."<font color=#000000>$tDate</font>&nbsp;&nbsp;&nbsp;";
			for($j=1;$j<sizeof($data1);$j++){
				$middle=$middle.$data1[$j].'<br>';
			}
		}else{
			$data1=explode("<br>",$row['Middle']);
				
			$middle="<font color=#000000>$tDate</font>&nbsp;&nbsp;&nbsp;";
			for($j=0;$j<sizeof($data1);$j++){
				$middle=$middle.$data1[$j].'<br>';
			}
		}
	}
	$mor='';	
}else{
	$mor='';
	$middle=$row['Middle'];
}
if ($row['Danger']==1 or $row['LineType']==9 or $row['LineType']==19 or $row['LineType']==10 or $row['LineType']==20 or $row['LineType']==21 or $row['LineType']==31 or $row['LineType']==50 or
	$row['LineType']==104 or $row['LineType']==105 or $row['LineType']==106 or $row['LineType']==107 or $row['LineType']==115 or $row['LineType']==118 or 
	$row['LineType']==119 or $row['LineType']==120 or $row['LineType']==161 or $row['LineType']==122 or $row['LineType']==123 or $row['LineType']==124 or  
	$row['LineType']==129 or $row['LineType']==130 or $row['LineType']==134 or $row['LineType']==135 or $row['LineType']==137 or $row['LineType']==141 or 
	$row['LineType']==144 or $row['LineType']==142 or $row['LineType']==204 or $row['LineType']==206 and $row['Cancel']==0){
	if ($row['Danger']==1 and $row['Cancel']==0){
        $type="<br><font color='green'><span><b>&nbsp;".$Order_Pending."&nbsp;</b></span></font>";
    }else if ($row['Danger']==0 and $row['Cancel']==0 and $row['Gtype']=="FT"){
        $type="<br><font color='green'><span><b>&nbsp;".$Order_Confirmed."&nbsp;</b></span></font>";
	}
	//$datetime="<font color='#FFFFFF'><span style='background-color: #FF0000'>".$times."</span></font>";
}else if ($row['Danger']==0){
	//$datetime=$times;
	$type='';
}
$datetime=$times;
if ($row['Cancel']==1){
	$win="<font color=#cc0000><b>".$zt."</b></font>";
}else{
	$win=number_format($row['Gwin'],2);
}
?>
      <tr class="b_rig<?php echo $mor?>">
      	<td align="center"><?php echo $row['orderNo'];?></td>
        <td align="left" style="width:240px;"><?php echo $datetime?><?php echo $Odds?></td>
        <td align="center"><?php echo $Title?><br/><?php echo $row['BetType']?></td>
        <td class="explain" align="left" style="width:370px;"><span class="bet_top"> <?php echo $middle?></span><?php echo $type?></td>
        <td align="center bet_money"><?php echo number_format($row['BetScore'],2)?></td>
        <td align="center"><?php echo $win;?></td>
          <td align="center">
              <?php
              // 即时比分
              // 未开赛
              // 比赛中 分钟  进球数
              // 完
//              $midd=explode('<br>',$row['Middle_tw']);
//              $mid=explode(',',$row['MID']);

    if ($row['LineType'] != 8) {
        $mysqlL = "select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where MID=" . $row['MID'];
        $result1L = mysqli_query($dbLink, $mysqlL);
        $row1L = mysqli_fetch_assoc($result1L);

        $mysql = "select MB_Team,TG_Team,M_League,M_Start,M_Time,M_Duration,MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where MID=" . $row['MID'];
        $result1 = mysqli_query($dbCenterSlaveDbLink, $mysql);
        $row1 = mysqli_fetch_assoc($result1);

        if (date('Y-m-d H:i:s') < $row1['M_Start']) {
            echo '<table border="0" cellpadding="0" cellspacing="0" class="rb_box rb_bk_box">';
            echo '<tbody><tr><td class="rb_time"> 未开赛 </td></tr>';
            echo '<tr><td class="rb_score">  0-<span style="color:#FF0000">0</span> </td></tr>';
            echo '</tbody></table>';
//            echo "<font color='blue'><b>未开赛</b></font> <br> <font color='red'><b>" . $row1['M_Time'] . "</b></font>";
        } else {
            $row1['MB_Ball'] = $row1['MB_Ball'] > 0 ? $row1['MB_Ball'] : 0;
            $row1['TG_Ball'] = $row1['TG_Ball'] > 0 ? $row1['TG_Ball'] : 0;
            $row1L['MB_Inball'] = $row1L['MB_Inball'] > 0 ? $row1L['MB_Inball'] : 0;
            $row1L['TG_Inball'] = $row1L['TG_Inball'] > 0 ? $row1L['TG_Inball'] : 0;
            // 足球是上下两场，共90分钟+伤停补时+15分钟中场休息，
            // 篮球是48分钟
            if($row['Gtype']=="FT" ){
                if(time()-strtotime($row1['M_Start']) > 120*60){
                    echo "<font color='red'><b>" . $row1L['MB_Inball'] . ":" . $row1L['TG_Inball'] . "</b></font>";
                    echo "<font color='blue'><b>[完]</b></font>";
                }else{
                    // 2H^26:05  赛程2小时，目前进行到 下半场已进行26分5秒
                    $M_Duration = explode('^',$row1['M_Duration']);

                    if ($M_Duration[1] > 1) {
                        $team_active = '';
                        switch ($M_Duration[0]) {
                            case '1H':
                                $team_active = '上';
                                break;
                            case '2H':
                                $team_active = '下';
                                break;
                            case 'OT':
                                $team_active = '加时';
                                break;
                            case 'HT':
                                $team_active = '半场';
                                break;
                            case 'MTIME':
                                $team_active = '中场';
                                break;
                        }
                    }

                    echo '<table class="rb_box"><tbody><tr><td class="rb_time">'.$team_active.$M_Duration[1].'</td></tr><tr><td class="rb_score">'.$row['MB_ball'].'&nbsp;-&nbsp;'.$row['TG_ball'].'</td></tr></tbody></table>';

//                    echo "<font color='blue'><b>[".$team_active.$M_Duration[1]."]</b></font><br>";
//                    echo "<font color='red'><b>" . $row1['MB_Inball'] . ":" . $row1['TG_Inball'] . "</b></font>";
                }
            }elseif($row['Gtype']=="BK"){
                $M_Duration = explode('-',$row1['M_Duration']);
                if ($M_Duration[1] > 1){
                    $team_active='' ;
                    switch ($M_Duration[0]) {
                        case 'Q1':
                            $team_active ='第一节';
                            break;
                        case 'Q2':
                            $team_active ='第二节';
                            break;
                        case 'Q3':
                            $team_active ='第三节';
                            break;
                        case 'Q4':
                            $team_active ='第四节';
                            break;
                        case 'H1':
                            $team_active ='上半场';
                            break;
                        case 'H2':
                            $team_active ='下半场';
                            break;
                        case 'OT':
                            $team_active ='加时';
                            break;
                        case 'HT':
                            $team_active ='半场';
                            break;
                    }
                    $team_time ='';
                    if($M_Duration[1] && $M_Duration[1] > 0){ // 转化时间
                        $team_hour = floor($M_Duration[1]/3600); // 小时不要
                        $team_minute = floor(($M_Duration[1]-3600 * $team_hour)/60);
                        $team_second = floor((($M_Duration[1]-3600 * $team_hour) - 60 * $team_minute) % 60);
                        $team_time = ($team_minute>9?$team_minute:"0".$team_minute).':'.($team_second>9?$team_second:"0".$team_second );
                    }

                	if((isset($row1['MB_Ball'])&&$row1['MB_Ball']<=0) && (isset($row1['TG_Ball'])&&$row1['TG_Ball']<=0)){
						$mysqlBall = "select MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and Open=1 and MB_Team='".$row1['MB_Team']."' and TG_Team='".$row1['TG_Team']."' and M_League='".$row1['M_League']."' and M_Start='".$row1['M_Start']."' limit 1";
						$resultBall = mysqli_query($dbCenterSlaveDbLink,$mysqlBall);
						$rowBall = mysqli_fetch_assoc($resultBall);
						$row1['MB_Ball'] = $rowBall['MB_Ball'];
						$row1['TG_Ball'] = $rowBall['TG_Ball'];	
					}
                    
                    echo '<table border="0" cellpadding="0" cellspacing="0" class="rb_box rb_bk_box">';
                    echo '<tbody><tr><td class="rb_time"> '.$team_active.' <span class="rb_time_color">'.$team_time.'</span></td></tr>';
                    echo '<tr><td class="rb_score">  '.$row1['MB_Ball'].'-<span style="color:#FF0000">'.$row1['TG_Ball'].'</span> </td></tr>';
                    echo '</tbody></table>';
                }else{
                	 if((isset($row1L['MB_Inball'])&&$row1L['MB_Inball']<=0) && (isset($row1L['TG_Inball'])&&$row1L['TG_Inball']<=0)){
						$mysqlBall = "select MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and Open=1 and MB_Team='".$row1['MB_Team']."' and TG_Team='".$row1['TG_Team']."' and M_League='".$row1['M_League']."' and M_Start='".$row1['M_Start']."' limit 1";
						$resultBall = mysqli_query($dbCenterSlaveDbLink,$mysqlBall);
						$rowBall = mysqli_fetch_assoc($resultBall);
                         $row1L['MB_Inball'] = $rowBall['MB_Ball'];
                         $row1L['TG_Inball'] = $rowBall['TG_Ball'];
						echo "<font color='red'><b>" . $row1L['MB_Inball'] . ":" . $row1L['TG_Inball'] . "</b></font>";
                    }else{
						echo "<font color='red'><b>" . $row1L['MB_Inball'] . ":" . $row1L['TG_Inball'] . "</b></font>";
                    	echo "<font color='blue'><b>[完]</b></font>";
					}
                }
            }
        }

    }
              ?>
          </td>
        <td align="center">
            <?php
            if ($row['Cancel']==0){
                echo '正常';
            }else{
                echo "<font color=#cc0000><b>".$zt."</b></font>";
            }
            ?>
        </td>
      </tr>
<?php
if ($row['Cancel']==0){
    $tod_win=$tod_win+$row['Gwin'];
}
$tod_num=$tod_num+1;
$tod_bet=$tod_bet+$row['BetScore'];
$tDate='';
}
?>
      <tr class="sum_bar right">
        <td class="his_total" colspan="4">此页面统计：</td>
        <td class="his_total" align="center"><?php echo number_format($tod_bet)?></td>
        <td class="his_total" align="center"><?php echo number_format($tod_win)?></td>
        <td class="his_total"></td>
        <td class="his_total"></td>
        <!--td>-</td-->
      </tr>      
<?php
}
?>	  
    </table></td>
  </tr>
  <tr>
  <td class="mem" colspan="7">
  <h2>
      <table style="font-size:12px; color:#CCC;" width="100%" border="0" cellpadding="0" cellspacing="0" id="favb">
        <tr>
          <td height="30" align="center" valign="middle"><strong>&lt;&lt;</strong>
              <?php
              if(($page+1)==1){
                  echo '<a style="color:#ccc; font-weight: normal;" >上一页</a>';
              }else{
                  echo '<a style="color:#ccc; font-weight: normal;" href="./today_wagers.php?uid='.$uid.'&langx='.$langx.'&page='.($page-1).'">上一页</a>';
              }
              ?>

              &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
              <?php
              if($page_count==($page+1)){
                  echo '<a style="color:#ccc; font-weight: normal;" >下一页</a>';
              }else{
                  echo '<a style="color:#ccc; font-weight: normal;" href="./today_wagers.php?uid='.$uid.'&langx='.$langx.'&page='.($page+1).'">下一页</a>';
              }
              ?>

              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&gt;&gt;</strong></td>
        </tr>
      </table>
      </h2>
  </td>
  </tr>
  <tr><td id="foot"><b>&nbsp;</b></td></tr>
</table>
</body>
</html>
