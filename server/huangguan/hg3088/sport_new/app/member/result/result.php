<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/curl_http.php");
require ("../include/define_function_list.inc.php");

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$gtype=$_REQUEST['game_type'];
$list_date=empty($_REQUEST['today_day'])?$_REQUEST['list_date']:$_REQUEST['today_day'];

$today_date = date("Y-m-d");
$yesday = date("Y-m-d",strtotime("-1 day"));
$yesday_1 = date("Y-m-d",strtotime("-2 day"));
$yesday_2 = date("Y-m-d",strtotime("-3 day"));
$yesday_3 = date("Y-m-d",strtotime("-4 day"));
$yesday_4 = date("Y-m-d",strtotime("-5 day"));
$yesday_5 = date("Y-m-d",strtotime("-6 day"));


if ($list_date==""){
    $today=$_REQUEST['today_day']; // 日期
    if (empty($today)){
        $today = date("Y-m-d");
        $tomorrow =	"";
        $lastday = date("Y-m-d",mktime (0,0,0,date("m"),date("d")-1,date("Y")));
    }else{
        $date_list_1=explode("-",$today);
        $d1	= mktime(0,0,0,$date_list_1[1],$date_list_1[2],$date_list_1[0]);
        $tomorrow= date('Y-m-d',$d1+24*60*60);
        $lastday =date('Y-m-d',$d1-24*60*60);

        if ($today>=date('Y-m-d')){
            $tomorrow='';
        }
    }

    $list_date=$today;
}else{
    $today = $list_date;
    $date_list=mktime(0,0,0,substr($list_date,5,2),substr($list_date,8,2),substr($list_date,0,4));
    $tomorrow = date("Y-m-d",mktime (0,0,0,date("m",$date_list),date("d",$date_list)+1,date("Y",$date_list)));
    $lastday  = date("Y-m-d",mktime (0,0,0,date("m",$date_list),date("d",$date_list)-1,date("Y",$date_list)));
    if (strcmp($tomorrow,date("Y-m-d"))>0){
        $tomorrow="";
    }
}

  if($gtype == 'NFS' || $gtype == 'FI') {
		$yesterday='<a href="result.php?game_type='.$gtype.'&today='.$lastday.'&uid='.$uid.'">前一天</a>';
		if (!empty($tomorrow)){
			$tomorrow='<a href="result.php?game_type='.$gtype.'&today='.$tomorrow.'&uid='.$uid.'">下一天</a>';
		}
  } else {
		$yesterday='<a href="result.php?game_type='.$gtype.'&list_date='.$lastday.'&uid='.$uid.'">前一天</a>';
		if (!empty($tomorrow)){
			$tomorrow='<a href="result.php?game_type='.$gtype.'&list_date='.$tomorrow.'&uid='.$uid.'">下一天</a>';
		}
  }

  $date_search=$yesterday;
  $tomorrow_date_search= $tomorrow;

require ("../include/traditional.$langx.inc.php");


$sql = "select MID,M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR ,Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='$gtype' and M_Date='$list_date' and (MB_Inball !='' or TG_Inball !='' or MB_Inball_HR !='' or TG_Inball_HR !='') order by M_Start,M_League,MB_Team asc" ;
//echo $sql ;
$result = mysqli_query($dbLink,$sql);
$count=mysqli_num_rows($result);
// $row_sou = mysqli_fetch_array($result);
$data_arr = array();
$data_arr_after = array();

while( $row_sou = mysqli_fetch_array($result) ){ // 赋值给数组

    if ($row_sou["MB_Inball_HR"]<0 and $row_sou["MB_Inball"]<0 and $row_sou["TG_Inball_HR"]<0 and $row_sou["TG_Inball"]<0) {
        $Intabll_HR = 'Score'.abs($row_sou['MB_Inball_HR']);
        $row_sou["MB_Inball_HR"] = $row_sou["MB_Inball"]=$row_sou["TG_Inball_HR"]=$row_sou["TG_Inball"]= ${$Intabll_HR};
    }
    $data_arr[] = $row_sou ;
}

foreach($data_arr as $k=>$v) { // 按联赛组合数组
    $data_arr_after[$v["M_League"]][] = $v;
}

?>
<html>
<head>
<title>FT_result</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/<?php echo TPL_NAME;?>style/common.css?v=<?php echo AUTOVER; ?>" type="text/css">
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    body{background: #fff;}

</style>
</head>

<!--<body id="MFT" onLoad="onLoad();" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false">-->
<body id="MFT" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false" onload="init();">

<div id="div_state" class="acc_leftMain">
    <!--<div class="acc_header noFloat"><span class="acc_refreshBTN" onclick="parent.reload_var();"></span><h1>赛果</h1></div>-->
    <form name="game_result" action="result.php?uid=<?php echo $uid ?>" method=POST>
    <div class="acc_state_head">
          <span class="acc_result_ball">

         <input name="game_type" type="hidden" value="<?php echo $gtype;?>"/>
         <input name="today_day" type="hidden" value="<?php echo $list_date?>"/>
         <input type="submit" class="seach_btn" value="查询" name="submit" style="display: none">
         <ul class="acc_selectMS" id="type_acc_selectMS">

        </ul>
           </span>

        <span class="acc_result_small">

         <ul class="acc_selectMS">
             <li id="sel_type" onclick="showOption('type');" class="acc_selectMS_first" >赛事</li>
        	<ul id="chose_type" class="acc_selectMS_options" style="display:none;">
                <li id="Matches" value="" class="On">赛事</li>
            	<li id="Outright" value="FS">冠军</li>
            </ul>
        </ul>
          </span>

        <span class="acc_result_small">

          <span class="acc_state_title">选择日期</span>
         <ul class="acc_selectMS">
                <li  class="acc_selectMS_first" onclick="showOption('date');"><?php echo $list_date;?></li>
                <ul id="chose_date" class="acc_selectMS_options" style="display:none;">
                     <li class="acc_selectMS_first <?php echo ($list_date==$today_date?'On':'');?>" onclick="showDate(this);"><?php echo $today_date;?></li>
                     <li class="acc_selectMS_first <?php echo ($list_date==$yesday?'On':'');?>" onclick="showDate(this);"><?php echo $yesday;?></li>
                     <li class="acc_selectMS_first <?php echo ($list_date==$yesday_1?'On':'');?>" onclick="showDate(this);"><?php echo $yesday_1;?></li>
                     <li class="acc_selectMS_first <?php echo ($list_date==$yesday_2?'On':'');?>" onclick="showDate(this);"><?php echo $yesday_2;?></li>
                     <li class="acc_selectMS_first <?php echo ($list_date==$yesday_3?'On':'');?>" onclick="showDate(this);"><?php echo $yesday_3;?></li>
                     <li class="acc_selectMS_first <?php echo ($list_date==$yesday_4?'On':'');?>" onclick="showDate(this);"><?php echo $yesday_4;?></li>
                     <li class="acc_selectMS_first <?php echo ($list_date==$yesday_5?'On':'');?>" onclick="showDate(this);"><?php echo $yesday_5;?></li>
                </ul>

         </ul>
         </span>

        <span class="acc_previous_btn" onclick=""> <?php echo $date_search?> </span>
        <?php
        if($tomorrow_date_search){
            echo '<span class="acc_next_btn" onclick="">'. $tomorrow_date_search.'</span>' ;
        }
        ?>

    </div>
   <table border="0" cellpadding="0" cellspacing="0" class="acc_results_table">
       <tr class="acc_results_tr_title">
           <td class="acc_results_timew"></td>
           <td class="acc_results_teamw"></td>
           <td class="acc_results_otherw">上半场</td>
           <td class="acc_results_otherw">全场</td>
           <td class="acc_results_otherw"></td>
       </tr>

    <?php
      // if($res[1]==''){ // 没有数据
       if($count==0 || $count==''){ // 没有数据
    ?>
      <tr>
          <td colspan="5">这个日期没有赛果</td>
        </tr>


  <?php }else {   // 有数据
           foreach($data_arr_after as $key=>$row){
               // var_dump($row) ;
               echo '<tr class="acc_results_league"><td colspan="5" ><span>'.$key.'</span></td></tr>';
               for($i=0;$i<count($row);$i++){
                    echo '<tr class="acc_result_tr_top" >
                           <td rowspan="2" class="acc_result_time">'.substr($row[$i]["M_Date"], 5).'<br>'.$row[$i]["M_Time"].'</td>
                           <td class="acc_result_team">'.$row[$i]["MB_Team"].'</td>                         
                           <td class="acc_result_bg"><span class="BlackWord">'. $row[$i]["MB_Inball_HR"].'</span></td>
                           <td class="acc_result_full"><span class="acc_cont_bold">'.$row[$i]["MB_Inball"] .'</span></td>
                           <td rowspan="2" class="acc_result_bg"></td>
                       </tr>' ;
                    echo ' <tr class="acc_result_tr_other" >
                           <td class="acc_result_team">'.$row[$i]["TG_Team"].'</td>
                           <td class="acc_result_bg"><span class="BlackWord">'.$row[$i]["TG_Inball_HR"].'</span></td>
                           <td class="acc_result_full"><span class="BlackWord">'.$row[$i]["TG_Inball"].'</span></td>   
                       </tr>';
               }

           }

  }
 ?>
</table>

   </form>

</div>




<script language="javascript" src="../../../js/jquery.js"></script>
<script language="javascript" src="../../../js/result.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/lib/util.js?v=<?php echo AUTOVER; ?>"></script>

<script>
    var langx='<?php echo $langx?>';
    var game_type ='<?php echo $gtype?>' ;
    var chg_type = 'Matches';
    var game_date = '<?php echo $today;?>';
    var max_day ='<?php echo $today;?>';
    var myleg = new Array('');
    var lasttr ='';//最後的tr是否為藍色
    setGameType(game_type) ;


</script>

</body>
</html>
