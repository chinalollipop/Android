<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
require ("./include/config.inc.php");
require ("./include/define_function_list.inc.php");
require ("./include/curl_http.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$select_date=$_REQUEST['select_date'] ; // 当前选取是哪个时间段
$today_date=date('Y-m-d') ; // 今天
$yester_day =date("Y-m-d",strtotime("-1 day"));
$check_date = $today_date ; // 默认今天
//echo $yester_day;
require ("./include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$admin=$_SESSION['Admin'];

$mysql = "select datasite,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$site=$row['datasite'];
switch($langx)	{
case "zh-cn":
	$suid=$row['uid_tw'];
	break;
case "zh-cn":
	$suid=$row['uid'];
	break;
case "en-us":
	$suid=$row['uid_en'];
	break;
case "th-tis":
	$suid=$row['uid_en'];
	break;
}

	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$site."/app/member/FT_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	//http://hg1088.com/app/member/scroll_history.php?uid=14940627m6323264l22938231&langx=zh-cn
	$html_data=$curl->fetch_url("http://w117.hg3088.com/app/member/scroll_history.php?uid=$suid&langx=$langx");
//echo "".$site."/app/member/scroll_history.php?uid=$suid&langx=$langx&mtype=3";
	echo $html_data;
	// exit;
?>
<html>
<head>
<title>History</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/member/mem_body<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>

<body id="MFT">
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td class="top">
  	  <h1><em><?php echo $News_History?></em></h1>
	</td>
  </tr>
  <tr>
    <td class="mem">
        <div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="menu_set">
                <tbody><tr class="table_main_settings_tr">
                    <td id="page_no"><span id="pg_txt"></span>

                        <span style="display: none;" id="t_pge"></span>

                        <span id="today" onclick="chg_date(0);" class="scr_out <?php echo ($select_date==0)?'scr_on':'' ?>">今日</span>
                        / <span id="yesterday" onclick="chg_date(-1);" class="scr_out <?php echo ($select_date==-1)?'scr_on':'' ?>"> &nbsp;昨日</span>
                        / <span id="before" onclick="chg_date(-2);" class="scr_out <?php echo ($select_date==-2)?'scr_on':'' ?>"> &nbsp;昨日之前</span>

                    </td><td>&nbsp;</td>
                    <td class="rsu_refresh"><div onclick="reload_var();"><font id="refreshTime"></font></div></td>
                </tr>
                </tbody></table>
        </div>
      <table border="0" cellspacing="1" cellpadding="0" class="game">
        <thead>
          <th><?php echo $Scr_Number?></th>
          <th><?php echo $Scr_Time?></th>
          <th><?php echo $Scr_News?></th>
        </thead>
<?php

if($select_date=='0' || $select_date==''){ // 今天
    $sWhere .= "Date='{$today_date}'" ;
}
if($select_date == '-1'){ // 昨天
    $sWhere .= "Date='{$yester_day}'" ;
}
if($select_date == '-2'){ // 昨天之前
    $sWhere .= "Date<'{$yester_day}'" ;
}

$icount=1;
$sql="select Date,$message as Message from ".DBPREFIX."web_marquee_data where  Level='MEM' and $sWhere order by ID desc limit 0,25";
// echo $sql ;
$result = mysqli_query($dbLink,$sql);
while ($row = mysqli_fetch_assoc($result)){
// $time=strtotime($row['Date']);
// $times=date("y-m-d",$time);
$times =$row['Date'] ;
?>
		<tr class="b_rig" >
          <td align="center"><?php echo $icount?></td>
          <td align="center"><?php echo $times?></td>
          <td class="news"><?php echo trim($row['Message'])?></td>
        </tr>
<?php
$icount=$icount+1; 
}
?>
      </table> 
	</td>
  </tr>
  <tr><td id="foot"><b>&nbsp;</b></td></tr>
</table>

<script type="text/javascript" src="../../js/scroll_history.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    var select_date='';
    var t_page='1';
    var page_no='';
    var langx='zh-cn';
</script>
</body>
</html>
