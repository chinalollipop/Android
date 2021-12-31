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

if($select_date=='all' || $select_date==''){ // 全部
    $sWhere .= "Date<='{$today_date}'" ;
    $daytitle ='全部' ;
}
if($select_date=='0'){ // 今天
    $sWhere .= "Date='{$today_date}'" ;
    $daytitle ='今日' ;
}
if($select_date == '-1'){ // 昨天
    $sWhere .= "Date='{$yester_day}'" ;
    $daytitle ='昨日' ;
}
if($select_date == '-2'){ // 昨天之前
    $sWhere .= "Date<'{$yester_day}'" ;
    $daytitle ='昨日之前' ;
}


//$mysql = "select datasite,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";
//$result = mysqli_query($dbLink,$mysql);
//$row = mysqli_fetch_assoc($result);
//$site=$row['datasite'];
//switch($langx)	{
//case "zh-cn":
//	$suid=$row['uid_tw'];
//	break;
//case "zh-cn":
//	$suid=$row['uid'];
//	break;
//case "en-us":
//	$suid=$row['uid_en'];
//	break;
//case "th-tis":
//	$suid=$row['uid_en'];
//	break;
//}
//
//	$curl = new Curl_HTTP_Client();
//	$curl->store_cookies("/tmp/cookies.txt");
//	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
//$curl->set_referrer("".$site."/app/member/FT_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
// $html_data=$curl->fetch_url("http://w117.hg3088.com/app/member/scroll_history.php?uid=$suid&langx=$langx");
//echo "".$site."/app/member/scroll_history.php?uid=$suid&langx=$langx&mtype=3";
//echo $html_data;
// exit;

?>
<html>
<head>
    <title>History</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <link rel="stylesheet" href="../../style/member/mem_body<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css"><link rel="stylesheet" href="../../style/my_account.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>

<body id="MFT">
<div id="div_state" class="acc_leftMain">
    <div class="acc_header noFloat"><h1><?php echo $News_History?></h1></div>

    <div class="acc_searchDIV noFloat">
        <!--特制下拉罢--->
        <ul class="acc_selectSP">
            <li id="sel_type" onclick="parent.showOption('type');" class="acc_selectSP_first"><?php echo $daytitle?></li>
            <ul id="chose_type" class="acc_selectSP_options" style="display:none;">
                <li id="all" onclick="chg_date('all');" class="<?php echo ($select_date=='all')?'On':'' ?>">全部</li>
                <li id="today" onclick="chg_date(0);" class="<?php echo ($select_date=='0')?'On':'' ?>">今日</li>
                <li id="yesterday" onclick="chg_date(-1);" class="<?php echo ($select_date==-1)?'On':'' ?>"> 昨日</li>
                <li id="before" onclick="chg_date(-2);" class="<?php echo ($select_date==-2)?'On':'' ?>"> 昨日之前</li>
            </ul>
        </ul>

        <div class="acc_ann_input">
            <input type="text" id="findField"><span id="acc_ann_delBTN" class="acc_ann_delBTN" style="display:none"></span>
        </div>
        <span id="findbutton" name=""  class="acc_ann_searchBTN">搜寻</span>
    </div>

    <!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" class="menu_set">
                <tbody><tr class="table_main_settings_tr">
                    <td id="page_no"><span id="pg_txt"></span>
                        <span style="display: none;" id="t_pge"></span>
                        <span id="today" onclick="chg_date(0);" class="scr_out <?php /*echo ($select_date==0)?'scr_on':'' */?>">今日</span>
                        / <span id="yesterday" onclick="chg_date(-1);" class="scr_out <?php /*echo ($select_date==-1)?'scr_on':'' */?>"> &nbsp;昨日</span>
                        / <span id="before" onclick="chg_date(-2);" class="scr_out <?php /*echo ($select_date==-2)?'scr_on':'' */?>"> &nbsp;昨日之前</span>

                    </td>
                    <td>&nbsp;</td>

                </tr>
                </tbody>
            </table>-->
    <?php
    // $icount=1;
    // $sql="select Date,$message as Message from ".DBPREFIX."web_marquee_data where  Level='MEM' and $sWhere order by ID desc limit 0,25";
    $sql="select Date,$message as Message from ".DBPREFIX."web_marquee_data where  Level='MEM' and $sWhere order by ID desc ";
    // echo $sql ;
    $result = mysqli_query($dbLink,$sql);
    $cou=mysqli_num_rows($result); // 数量
    ?>
    <div class="acc_ann_header noFloat">
        <div id="Important" class="acc_ann_msgBTN_on">
            <span>重要</span>
            <!--<span id="ImportantMessage" class="acc_ann_icon" ><?php /*echo $cou*/?></span>-->
        </div>
        <!--        <div id="Personal" class="acc_ann_msgBTN" onclick="chg_important(2,'scroll_history')"><span>个人</span><span id="PersonalMessage" class="acc_ann_icon" style="display:none"></span></div>
                <div id="General" class="acc_ann_msgBTN" onclick="chg_important(0,'scroll_history')">一般</div>-->
    </div>
    <table border="0" cellspacing="1" cellpadding="0" class="acc_ann_msgTXT">

        <?php

        while ($row = mysqli_fetch_assoc($result)){
// $time=strtotime($row['Date']);
// $times=date("y-m-d",$time);
            $times =$row['Date'] ;
            ?>
            <tr >
                <!--<td align="center"><?php /*echo $icount*/?></td>
          <td align="center"><?php /*echo $times*/?></td>-->
                <td colspan="3">
                    <h1> <?php echo $times?><span class="acc_ann_msgNew"></span> </h1>
                    <p><?php echo trim($row['Message'])?> </p>
                </td>
            </tr>
            <?php
            $icount=$icount+1;
        }
        ?>
    </table>

</div>
<script type="text/javascript" src="../../js/scroll_history.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    top.uid ='<?php echo $uid?>';
</script>
</body>
</html>
