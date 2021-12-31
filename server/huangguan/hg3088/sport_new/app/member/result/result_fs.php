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
$today=$_REQUEST['today_day']; // 日期
$list_date=empty($today)?$_REQUEST['list_date']:$today;

$today_date = date("Y-m-d");
$yesday = date("Y-m-d",strtotime("-1 day"));
$yesday_1 = date("Y-m-d",strtotime("-2 day"));
$yesday_2 = date("Y-m-d",strtotime("-3 day"));
$yesday_3 = date("Y-m-d",strtotime("-4 day"));
$yesday_4 = date("Y-m-d",strtotime("-5 day"));
$yesday_5 = date("Y-m-d",strtotime("-6 day"));

require ("../include/traditional.$langx.inc.php");
if($list_date==""){
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


$mysql = "select Uid_ms,datasite_ms_new from ".DBPREFIX."web_system_data where ID=1";

$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);

$newsite=$row['datasite_ms_new'] ;
$newsuid =$row['Uid_ms'] ;

    $filename="".$newsite."/app/member/result/result_fs.php?game_type=$gtype&uid=$newsuid&langx=$langx&today=$today";
   // echo $filename;
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt");
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$newsite."/app/member/FT_browse/index.php?rtype=re&uid=$newsuid&langx=$langx&mtype=3");
    $html_data=$curl->fetch_url($filename);
	$html_data=str_replace($newsuid,$uid,$html_data);
	//echo $html_data;
    $res=explode('<div>',$html_data);
?>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/<?php echo TPL_NAME;?>style/common.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        body{background: #fff;}
    </style>
</head>

<body id="MFT" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false" onload="init();">

<div id="div_state" class="acc_leftMain">
   <!-- <div class="acc_header noFloat"><span class="acc_refreshBTN" onclick="parent.reload_var();"></span><h1>赛果</h1></div>-->
    <form name="game_result" action="result_fs.php?uid=<?php echo $uid ?>" method=POST>
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
             <li id="sel_type" onclick="showOption('type');" class="acc_selectMS_first" >冠军</li>
        	 <ul id="chose_type" class="acc_selectMS_options" style="display:none;">
              <li id="Matches" value="" >赛事</li>
            	<li id="Outright" value="FS" >冠军</li>
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
        <?php
        if($res[1]==''){ // 没有数据
            ?>
            <table border="0" cellpadding="0" cellspacing="0" class="acc_results_table">
                <tr class="acc_results_tr_title">
                    <td class="acc_results_timew"></td>
                    <td class="acc_results_outteamw"></td>
                    <td class="acc_results_outotherw">赛果</td>
                    <td class="acc_results_outbtnw"></td>
                </tr>

                <tr>
                    <td colspan="5">这个日期没有赛果</td>
                </tr>
            </table>
        <?php } ?>

        <?php
        if($res[1]){ // 有数据
            echo $res[1];
        }
        ?>

    </form>

</div>




<script language="javascript" src="../../../js/jquery.js"></script>
<script language="javascript" src="../../../js/result.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/lib/util.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var langx='<?php echo $langx?>';
    var game_type ='<?php echo $gtype?>' ;
    var chg_type = 'Outright';
    var game_date = '<?php echo $today;?>';
    var max_day ='<?php echo $today;?>';
    var myleg = new Array('');
    var lasttr ='';//最後的tr是否為藍色
    setGameType(game_type) ;


</script>

</body>
</html>
