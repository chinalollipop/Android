<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$chk_cw=$_REQUEST['chk_cw'];
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
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
$result = mysqli_query($dbMasterLink, $mysql);
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css"><link rel="stylesheet" href="../../../style/my_account.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>

<body id="M<?php echo $gtype?>" class="bodyset HIS" >
<div class="acc_leftMain">
    <!-- 头部 -->
    <div class="acc_header noFloat">
        <!--伸缩页码-->
     <!--   <div id="acc_pg" class="acc_pageDIV">页 <span id="now_page">1</span>/<span id="total_page">1</span>
            <div id="div_page" class="acc_pageG" style="display:none" tabindex="100">
                <span class="acc_MINImenu_arr"></span>
                <ul id="page">
                    <li id="page_0" class="acc_pageTitle">页</li>
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>
                </ul>
            </div>
        </div>-->
        <span onclick="javascript:parent.reload_var()" class="acc_refreshBTN"></span>

        <?php if ($display=='Y'){ ?>
            <?php if($nocount>0){ ?>
                <span class="acc_CancelWord" onClick="wagers_sta('<?php echo $chk_cw?>');" >您有<span class="wag_none"> (<?php echo $nocount ?>) </span>张取消注单</span>
            <?php }else{ ?> <!-- 0 张取消订单 -->
                <span class="acc_CancelWord no_pointer" onClick="wagers_sta('<?php echo $chk_cw?>');">您有<span class="wag_none"> (<?php echo $nocount ?>) </span>张取消注单</span>
            <?php } ?>

        <?php }else{ ?>
            <span  class="acc_CancelWord" onClick="wagers_sta('<?php echo $chk_cw?>');" >您有<span class="wag_none"> (<?php echo $nocount ?>) </span>张有效注单</span>

        <?php } ?>

        <h1 id="WAGERS"><?php echo $Transaction_Record?></h1>
        <h1 id="WAGERS_CANCEL" style="display:none">已取消注单</h1>
    </div>

<!--    <div >--><?php //echo $page+1;?><!-- / --><?php //echo $page_count?><!-- 页 <select name='page' onChange="toPage(this.value);">-->
<!--            --><?php
//            if ($page_count==0){
//                $page_count=1;
//            }
//            for($i=0;$i<$page_count;$i++){
//                if ($i==$page){
//                    echo "<option selected value='$i'>".($i+1)."</option>";
//                }else{
//                    echo "<option value='$i'>".($i+1)."</option>";
//                }
//            }
//            ?>
<!--        </select>-->
<!--    </div>-->

    <table border="0" cellspacing="0" cellpadding="0" class="acc_openbetTB">
<?php
if($cou==0){ // 没有注单
?>
      <tr>
          <div class="acc_noData">
              <?php echo $Tod_text?>
          </div>
      </tr>
<?php
}else{
?>
      <tr> 
     	<th class="his_wag" style="width:30px;">编号</th>
        <th class="his_wag"><?php echo '注单号/'.$Tod_Betting_Time?></th>
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
        $type="<br><img src='order_icon.gif'><font color='green'><span><b>&nbsp;".$Order_Pending."&nbsp;</b></span></font>";
    }else if ($row['Danger']==0 and $row['Cancel']==0 and $row['Gtype']=="FT"){
        $type="<br><img src='order_icon.gif'><font color='green'><span><b>&nbsp;".$Order_Confirmed."&nbsp;</b></span></font>";
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
      	<td align="center"><?php echo $row['ID']?></td>
        <td align="left" style="width:180px;"><?php echo $row['orderNo'];?><br/><?php echo $datetime?><?php echo $Odds?></td>
          <td align="center" style="min-width:40px;"><?php
              if ($row['LineType']==8){
                  echo $Title.'<br/>'.$row['BetType'];
              }else{
                  if($row['Gtype']=='BK' && $row['LineType']==13){
                      echo $Title.'<br/>'.$row['BetType'];
                  }else{
                      echo $Title.'<br/>'.$hg_game_type[$row['LineType']];
                  }
              }
              ?></td>
        <td class="explain" align="left" style="width:370px;"><?php echo $middle?><?php echo $type?></td>
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
        $mysql = "select M_Start,MB_Team,TG_Team,M_League,M_Start,M_Time,Checked,M_Duration,MB_Inball,TG_Inball,MB_Ball,TG_Ball,MB_Inball_HR,TG_Inball_HR from ".DBPREFIX."match_sports where MID=" . $row['MID'];
        $result1 = mysqli_query($dbLink, $mysql);
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
            $row1['MB_Inball'] = $row1['MB_Inball'] > 0 ? $row1['MB_Inball'] : 0;
            $row1['TG_Inball'] = $row1['TG_Inball'] > 0 ? $row1['TG_Inball'] : 0;
            // 足球是上下两场，共90分钟+伤停补时+15分钟中场休息，
            // 篮球是48分钟
            if($row['Gtype']=="FT" ){
                if(time()-strtotime($row1['M_Start']) > 120*60){
                    echo "<font color='red'><b>" . $row1['MB_Inball'] . ":" . $row1['TG_Inball'] . "</b></font>";
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
						$mysqlBall = "select MB_Ball,TG_Ball from `".DBPREFIX."match_sports` where Type='BK' and Open=1 and MB_Team='".$row1['MB_Team']."' and TG_Team='".$row1['TG_Team']."' and M_League='".$row1['M_League']."' and M_Start='".$row1['M_Start']."' limit 1";
						$resultBall = mysqli_query($dbLink,$mysqlBall);
						$rowBall = mysqli_fetch_assoc($resultBall);
						$row1['MB_Ball'] = $rowBall['MB_Ball'];
						$row1['TG_Ball'] = $rowBall['TG_Ball'];	
					}
                    
                    echo '<table border="0" cellpadding="0" cellspacing="0" class="rb_box rb_bk_box">';
                    echo '<tbody><tr><td class="rb_time"> '.$team_active.' <span class="rb_time_color">'.$team_time.'</span></td></tr>';
                    echo '<tr><td class="rb_score">  '.$row1['MB_Ball'].'-<span style="color:#FF0000">'.$row1['TG_Ball'].'</span> </td></tr>';
                    echo '</tbody></table>';

                }else{
                	if((isset($row1['MB_Inball'])&&$row1['MB_Inball']<=0) && (isset($row1['TG_Inball'])&&$row1['TG_Inball']<=0)){
						$mysqlBall = "select MB_Ball,TG_Ball from `".DBPREFIX."match_sports` where Type='BK' and Open=1 and MB_Team='".$row1['MB_Team']."' and TG_Team='".$row1['TG_Team']."' and M_League='".$row1['M_League']."' and M_Start='".$row1['M_Start']."' limit 1";
						$resultBall = mysqli_query($dbLink,$mysqlBall);
						$rowBall = mysqli_fetch_assoc($resultBall);
						$row1['MB_Inball'] = $row['MB_Ball'];
						$row1['TG_Inball'] = $row['TG_Ball'];	
						echo "<font color='red'><b>" . $row1['MB_Inball'] . ":" . $row1['TG_Inball'] . "</b></font>";
					}else{
                    	echo "<font color='red'><b>" . $row1['MB_Inball'] . ":" . $row1['TG_Inball'] . "</b></font>";
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
if ($row['Cancel']==0) {
    $tod_win = $tod_win + $row['Gwin'];
}
$tod_num=$tod_num+1;
$tod_bet=$tod_bet+$row['BetScore'];
$tDate='';
}
?>

      <tr class="acc_state_tr_total">
        <td class="acc_state_total_color" colspan="4">此页面统计：</td>
        <td class="his_total" align="center"><?php echo number_format($tod_bet)?></td>
        <td class="his_total"><?php echo number_format($tod_win);?></td>
        <td class="his_total"></td>
        <td class="his_total"></td>
        <!--td>-</td-->
      </tr>      
<?php
}
?>	  

</table>

    <!-- 分页 -->
    <div id="show_page_txt" class="bet_page_bot_rt">

    </div>

</div>
<script>
    var t_page= <?php echo $page_count?> ; // 总页数
    var pg=<?php echo $page?>; // 当前页码

    show_page() ;
    // 分页
    function show_page(){
        pg_str='';
        var obj_pg =document.getElementById('show_page_txt');
        if (t_page==0){
            t_page=1;
        }
        var disabled="";
        if (t_page==1){
            disabled="disabled";
        }
        var pghtml ='';
        if((pg*1+1)==1){ // 当前在第一页
            pghtml += '<span id="top_left"  class="bet_page_Lleft_out"></span>\n' +
                '<span id="pg_left" class="bet_page_left_out"></span>\n' ;
        }else{
            pghtml += '<span id="top_left" onclick="chg_pg(0)" class="bet_page_Lleft"></span>\n' +
                '<span id="pg_left" onclick="chg_pg(\'del\')" class="bet_page_left"></span>\n' ;
        }
        pghtml += '<tt id="num" class="bet_page_text">'+(pg*1+1)+" / " +t_page+'</tt>\n' ;
        if((pg*1+1)==t_page){ // 达到尾页
            pghtml += '<span id="pg_right"  class="bet_page_right_out"></span>' ;
        }else{
            pghtml += '<span id="pg_right" onclick="chg_pg(\'add\')" class="bet_page_right"></span>' ;
        }
        obj_pg.innerHTML = pghtml;

    }
    // 赛事切换分页
    function chg_pg(sel){
        if (sel==pg) {return;}
        if(sel ==="add"){
            pg++;
        }else if(sel ==="del"){
            pg--;
        }else if(sel==0){
            pg=0;
        }
        toPage(pg);
    }

    function wagers_sta(cw){
        self.location='./today_wagers.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&chk_cw='+cw;
    }
    function toPage(v){
        self.location='./today_wagers.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&page='+v;
    }
</script>

</body>
</html>
