<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../../include/config.inc.php");
require ("../../include/curl_http.php");
require ("../../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$gtype=$_REQUEST['game_type'];
$list_date=empty($_REQUEST['today'])?$_REQUEST['list_date']:$_REQUEST['today'];

if ($list_date==""){
  	$today=$_REQUEST['today']; // 日期
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

require ("../../include/traditional.$langx.inc.php");
//	$mysql = "select Uid_ms,datasite_ms_new from ".DBPREFIX."web_system_data where ID=1";
//
//	$result = mysqli_query($dbLink,$mysql);
//	$row = mysqli_fetch_assoc($result);
//	$newsite=$row['datasite_ms_new'] ;
//    $newsuid =$row['Uid_ms'] ;

	/*switch($langx)	{
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

	}*/

/*	switch($gtype){
		case "VB":
		$filename="".$site."/app/member/result/result_vb.php?game_type=$gtype&uid=$suid&langx=$langx&list_date=$list_date";
		break;
		case "TN":
		$filename="".$site."/app/member/result/result_tn.php?game_type=$gtype&uid=$suid&langx=$langx&list_date=$list_date";
		break;
		default:
		//$filename="".$site."/app/member/result/result.php?game_type=$gtype&uid=$suid&langx=$langx&list_date=$list_date";
            // 新版链接更改
		$filename="".$newsite."/app/member/account/result/result.php?game_type=$gtype&uid=$suid&langx=$langx&list_date=$list_date";
	}*/

//    $filename="".$newsite."/app/member/account/result/result.php?game_type=$gtype&uid=$newsuid&langx=$langx&list_date=$list_date";
//	$curl = new Curl_HTTP_Client();
//	$curl->store_cookies("/tmp/cookies.txt");
//	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
//
//	$html_data=$curl->fetch_url($filename);
//	$html_data=str_replace($newsuid,$uid,$html_data);
//	$res=explode('<div>',$html_data);

$sql = "select MID,M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR ,Open from `".DBPREFIX."match_sports` where `Type`='$gtype' and M_Date='$list_date' and (MB_Inball !='' or TG_Inball !='' or MB_Inball_HR !='' or TG_Inball_HR !='') order by M_Start,M_League,MB_Team asc" ;
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
<link rel="stylesheet" href="../../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css">
<link rel="stylesheet" href="../../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<link rel="stylesheet" href="../../../../style/my_account.css?v=<?php echo AUTOVER; ?>" type="text/css">
<link rel="stylesheet" href="../../../../style/member/calendar.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    .acc_result_small .acc_selectMS_first{ width: 76px;}
    .acc_result_small input.acc_selectMS_first{ width: 116px;}
</style>
</head>

<!--<body id="MFT" onLoad="onLoad();" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false">-->
<body id="MFT" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false" onload="init();">

<div id="div_state" class="acc_leftMain">
    <div class="acc_header noFloat"><span class="acc_refreshBTN" onclick="parent.reload_var();"></span><h1>赛果</h1></div>
    <form name="game_result" action="result.php?uid=<?php echo $uid ?>" method=POST>
    <div class="acc_state_head">
          <span class="acc_result_ball">

         <input name="game_type" type="hidden" value=""/>
         <input name="today" type="hidden" value="<?php echo $today?>"/>
         <input type="submit" class="seach_btn" value="查询" name="submit" style="display: none">
         <ul class="acc_selectMS" id="type_acc_selectMS">

        </ul>
           </span>

        <span class="acc_result_small">

         <ul class="acc_selectMS">
             <li id="sel_type" onclick="parent.showOption('type');" class="acc_selectMS_first" >赛事</li>
        	<ul id="chose_type" class="acc_selectMS_options" style="display:none;">
              <li id="Matches" value="" class="On">赛事</li>
            	<li id="Outright" value="FS">冠军</li>
            </ul>
        </ul>
          </span>

        <span class="acc_result_small">

          <span class="acc_state_title">选择日期</span>
         <ul class="acc_selectMS">
<!--             <input id="date_start"  class="acc_selectMS_first" name="today" value="--><?php //echo $today;?><!--" onclick="showDate();" readonly>-->
             <li id="date_start"  class="acc_selectMS_first" name="today"  onclick="showDate();"><?php echo $today;?></li>
         </ul> <!-- onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" -->
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
           <td class="acc_results_otherw">全场</td>
           <td class="acc_results_otherw">上半场</td>
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
                           <td class="acc_result_full"><span class="acc_cont_bold">'.$row[$i]["MB_Inball"] .'</span></td>
                           <td class="acc_result_bg"><span class="BlackWord">'. $row[$i]["MB_Inball_HR"].'</span></td>
                           <td rowspan="2" class="acc_result_bg"></td>
                       </tr>' ;
                    echo ' <tr class="acc_result_tr_other" >
                           <td class="acc_result_team">'.$row[$i]["TG_Team"].'</td>
                           <td class="acc_result_full"><span class="BlackWord">'.$row[$i]["TG_Inball"].'</span></td>
                           <td class="acc_result_bg"><span class="BlackWord">'.$row[$i]["TG_Inball_HR"].'</span></td>
                       </tr>';
               }

           }

  }
 ?>
</table>


         <!--  <tr class="acc_results_league"><td colspan="5" id="S_101877_3318304" onclick="showLEG('101877');"><span>室内五人足球-日本F联赛</span></td></tr>
           <tr class="acc_result_tr_top" id="TR_101877_3318304">
               <td rowspan="2" class="acc_result_time">08-04<br>00:30a</td>
               <td class="acc_result_team">滨松 &nbsp;&nbsp;</td>
               <td class="acc_result_full"><span class="acc_cont_bold">2</span></td>
               <td class="acc_result_bg"><span class="BlackWord">0</span></td>
               <td rowspan="2" class="acc_result_bg"><span class="acc_result_btn" onclick="showResult_new('d927ygcm19413686l103050','FT','3318304','zh-cn');">所有赛果</span></td>
           </tr>
           <tr class="acc_result_tr_other" id="TR_1_101877_3318304">
               <td class="acc_result_team">北海道 &nbsp;&nbsp;</td>
               <td class="acc_result_full"><span class="BlackWord">0</span></td>
               <td class="acc_result_bg"><span class="BlackWord">0</span></td>
           </tr>-->


<!--    --><?php
//    if($res[1]){ // 有数据
//        echo $res[1];
//    }
//    ?>

   </form>

</div>




<script language="javascript" src="../../../../js/jquery.js"></script>
<script language="javascript" src="../../../../js/result.js?v=<?php echo AUTOVER; ?>"></script>
<!--<script type="text/javascript" src="../../../../js/register/laydate.min.js?v=<?php /*echo AUTOVER; */?>"></script>-->
<script type="text/javascript" src="../../../../js/lib/util.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../../js/lib/ClassFankCal_history.js?v=<?php echo AUTOVER; ?>"></script>
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