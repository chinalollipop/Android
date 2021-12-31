<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
//echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$Ttimetop=strtotime(date('Y-m-d'));
require ("../include/traditional.$langx.inc.php");
$sumall=0;
$rsumall=0;

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}else{
	$memname=$_SESSION['UserName'];
	$mid=$_SESSION['userid'];
	$gtype=strtoupper($_REQUEST['gtype']);
	if($gtype=='' or $gtype=='ALL'){
		$gtype='ALL';
		$style='_fu';
		$active='';		
	}else{
		if($gtype=="FT"){
			$active=" and game_code in (1,11)";		
		}elseif($gtype=="BK"){
			$active=" and game_code in (2,22)";
		}
	}
	
    $de_date_start = date('Y-m-d ',($Ttimetop- 7 * 24 * 3600)) ; // 默认开始时间
    $de_date_end = date('Y-m-d ',$Ttimetop) ; // 默认结束时间

    $r_date_start=$_REQUEST['date_start']; // 开始时间
    $r_date_end=$_REQUEST['date_end'];  // 结束时间
    if($r_date_start>$r_date_end){ // 开始时间大于结束时间
        $date_start = $r_date_end ;
        $date_end = $r_date_start ;
    }else{
        $date_start = $r_date_start ;
        $date_end = $r_date_end ;
    }
    if($date_start !='' && $date_end !=''){
        $de_date_start = $date_start ;
        $de_date_end = $date_end ;
    }

    $xq = array("$His_Week_Sun","$His_Week_Mon","$His_Week_Tue","$His_Week_Wed","$His_Week_Thu","$His_Week_Fri","$His_Week_Sat");
    for($i=0;$i<=14;$i++) {
        $t = $Ttimetop - $i * 24 * 3600;
        $todaytop = date('Y-m-d ', $t);
        if($de_date_end==$todaytop){ // 当前日期
            $datetimestr .= '<option value="'.$todaytop.'" selected>'.$todaytop.'</option>' ; // 结束日期
        }else{
            $datetimestr .= '<option value="'.$todaytop.'">'.$todaytop.'</option>' ; // 结束日期
        }
        if($de_date_start==$todaytop){ // 当前日期
            $startdatetimestr .= '<option value="'.$todaytop.'" selected>'.$todaytop.'</option>' ; // 开始日期
        }else{
            $startdatetimestr .= '<option value="'.$todaytop.'">'.$todaytop.'</option>' ; // 开始日期
        }
    }
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<link rel="stylesheet" href="/style/member/mem_body_his.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script>
function onLoad(){
    var select_object = document.getElementById("gtype");
    select_object.value = '<?php echo $gtype ?>';
    
}
function changeGtpye(){
    sel_gtype.submit();
}

function changeUrl(a){
 self.location=a;
//alert(a);
}
function overbars(obj,color){
  //alert(obj.cells["d_date"].className);
  var className=obj.cells["d_date"].className;
  if (className=="his_list_none") return;  
    obj.cells["d_date"].className=color;

}
function outbars(obj,color){
var className=obj.cells["d_date"].className;
  if (className=="his_list_none") return;
    obj.cells["d_date"].className=color;
    //alert("out--"+obj.cells["d_date"].className);
}


</script>
</head>

<body id="M<?php echo $gtype?>" class="bodyset HIS overflow_y" onLoad="onLoad()">
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td class="top">
      <h1><em>帐户历史摘要</em></h1>
    </td>
  </tr>
  <tr>
    <td class="mem">
    <h2>
        <form method="post" id="sel_gtype" style="display:inline;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
              <tr>
                <td>
                    接体育查看记录:
                  <select name="gtype" id="gtype" onChange="changeGtpye();">
                    <option value="ALL"><?php echo $His_All?></option>
                    <option value="FT"><?php echo $His_Soccer?></option>
                    <option value="BK"><?php echo $His_Baseketball?></option>
                    <!--<option value="TN"><?php echo $His_Tennis?></option>
                    <option value="VB"><?php echo $His_VolleyBall?></option>
                    <option value="BS"><?php echo $His_BaseBall?></option>
                    <option value="FS"><?php echo $His_Outright?></option>
                    <option value="OP"><?php echo $His_Other?></option>
                  --></select>
                </td>
                  <td>日期：
                      <select name="date_start" id="date_start">
                          <?php echo $startdatetimestr;?>
                      </select>
                  </td>
                  <td>到
                      <select name="date_end" id="date_end">
                          <?php echo $datetimestr;?>
                      </select>
                  </td>
                  <td>
                        <input type=submit value="<?php echo $His_Search?>">
                        <!--<input type="button" value="六合历史" class="" onClick="location.href='<?php /*echo BROWSER_IP*/?>/app/member/six/index.php?action=l&uid=<?php /*echo $uid*/?>';">
                                     <input type="button" value="时时彩历史" class="" onClick="location.href='<?php /*echo BROWSER_IP*/?>/app/member/ssc/templates/repore.php';">-->
                  </td>


              </tr>
            </table>
        </form>
     </h2>

    <table border="0" cellspacing="0" cellpadding="0" class="game">
      <tr> 
        <th class="his_time"><?php echo $His_Date?></th>
        <th class="his_wag" ><?php echo $His_Bet_Amount?></th>
        <th class="his_wag"><?php echo $His_Valid_Amount?></th>
        <th class="his_wag">派彩結果</th>
        <!--th width="25%">有效金额</th-->
      </tr>
<?php

    $datearr = getDateFromRange($de_date_start,$de_date_end);
    krsort($datearr) ; // 倒序
    foreach($datearr as $date){

        // 执行处理
        $tx =strtotime($date) ; // 转时间戳
        $today=date('m月d日 ',$tx).$week.$xq[date("w",$tx)];

        // 美东时间 0点到3点，前一天的历史报表数据还未生成，数据从注单表汇总捞取
        // 将时间分3段（1 当天捞取当天、2 当天3点前捞取前一天、3 当天3点后 捞取前一天以及其他前一天之前的数据）
        $previous_day = date('Y-m-d',strtotime('-1 day')); // 前一天
        if( $date == date('Y-m-d') ){ // 当天
            $sql="select sum(VGOLD) as vgold,sum(BetScore) as betscore,sum(M_Result) as m_result from ".DBPREFIX."web_report_data where M_Result!='' and M_Date='".$date."' and M_Name='$memname'".$active;
        }elseif ( $date == $previous_day && (int)date("G") < 3 ){ // 前一天数据，3点前从注单表捞取，否则捞取注单历史报表
            $sql="select sum(VGOLD) as vgold,sum(BetScore) as betscore,sum(M_Result) as m_result from ".DBPREFIX."web_report_data where M_Result!='' and M_Date='".$date."' and M_Name='$memname'".$active;
        }else{
            $sql="select sum(valid_money) as vgold,sum(total) as betscore,sum(user_win) as m_result from ".DBPREFIX."web_report_history_report_data where M_Date='".$date."' and userid={$mid}".$active;
        }

        $result = mysqli_query($dbLink,$sql);
        $row = mysqli_fetch_assoc($result);
        $sum=$row['betscore']+0 ;    // 交易金额
        $rsum=$row['m_result']+0 ;  // 派彩结果

        $aa=$aa+$row['betscore'];
        $bb=$bb+$row['m_result'];
        $vgold=$vgold+$row['vgold'];

        if($sum>0){
            $link='<a href="'."history_view.php?uid=$uid&member_id=$mid&tmp_flag=N&today_gmt=".$date."&gtype=$gtype&date_start=$date_start&date_end=$date_end&langx=$langx".'">'.$today.'</a>';
        }else{
            $link=$today;
        }

        $Tclass=($i%2)?"color_bg2":"color_bg1";
        echo '<tr class="'.$Tclass.'" onMouseOver="overbars(this,\'his_over\');" onMouseOut="outbars(this,\'his_list\')" > 
		    <td class="his_list_none" id="d_date"><span >'.$link.'</span></td>
	    	<td class="his_td" ><span class="fin_gold">'.number_format($sum).'</span></td>
		    <td class="his_td" >'.number_format($row['vgold']).'</td>
	    	<td class="his_td pai_result" >'.number_format($rsum, 2).'</td>
		  </tr>';
    }

?>
      

      <tr class="sum_bar right">
        <td class="center his_total">总计</td>
        <td class="his_total"><?php echo number_format($aa,2)?></td>
        <td class="his_total"><?php echo number_format($vgold,2)?></td>
        <td class="his_total"><?php echo number_format($bb,2)?></td>
        <!--td>-</td-->
      </tr>
    </table> 
    </td>
  </tr>
  <tr><td id="foot"><b>&nbsp;</b></td></tr>
</table>


</body>
</html>
<?php
}
?>