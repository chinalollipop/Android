<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
require ("./include/config.inc.php");

$uid=$uidOriginal=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$live=$_REQUEST['live'];
require ("./include/traditional.$langx.inc.php");
$sql = "select Status,UserName,LoginName,Language,LoginDate,Credit,Money,Pay_Type from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status<=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
$Status=$row['Status'];
if($cou==0){
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}else{
	$memname=$row['UserName'];
	$loginname=$row['LoginName'];
	$langx=$row['Language'];
	$logindate=date("Y-m-d");
	$datetime=date('Y-m-d h:i:s');
	if ($row['LoginDate']!=$logindate){
		$credit=$row['Credit'];
	}else{
		$credit=$row['Money'];
	}
}

$show=$_REQUEST['show'];
if ($show==''){
	$show='N';
}

if($show=='Y'){
   $chk_fun='Show';
   $open='visible';
}else if($show=='N'){
   $chk_fun='None';
   $open='hidden';
}
if($row['Pay_Type']==0){
	$sel_line="$Sel_Credit_Line";
}else if($row['Pay_Type']==1){
	$sel_line="$Sel_Cash_Line";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css">
<link href="../../style/member/right_live.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>

<body  >
<div id="main_tv" class="live_main320">
    <div id="div_tv" class="live_DIV320">
        <div id="div_show" class="">
            <!-- title -->
            <div id="div_title" class="live_header" style="display: none; width: 310px;">
                <h1 id="ctl_tv" class="On">观看现场</h1>
                <span id="btn_game" onclick="showGameList();" class="live_tvListBTN_on" title="直播日程表">时间表</span>
                <span id="btn_open" onclick="showOpenLive();" class="live_tvBigBTN" title="放大显示"></span>
            </div>
            <!-- title End-->
            <!-- nogame title -->
            <div id="div_title_nogame" style="" class="live_header">
                <h1 id="ctl_tv02" class="On">观看现场</h1>
                <!-- 球类拉bar -->
                <div id="sel_gtype_nogame" class="live_allSportsBTN"><tt id="select_gtype_nogame">所有体育</tt>
                    <div id="show_gtype_nogame" style="display:none" class="live_MINImenu" tabindex="100"><span class="live_MINImenu_arr"></span>
                        <h1>选择体育</h1>
                        <ul id="option_gtype_nogame" class="live_MINIul">
                            <!-- 程式趴 -->
                            <li id="option_nogame_All" value="All">所有球类</li><li id="option_nogame_FT" value="FT">足球</li><li id="option_nogame_BK" value="BK">篮球 / 美式足球</li><li id="option_nogame_TN" value="TN">网球</li><li id="option_nogame_VB" value="VB">排球</li><li id="option_nogame_BM" value="BM">羽毛球</li><li id="option_nogame_TT" value="TT">兵乓球</li><li id="option_nogame_BS" value="BS">棒球</li><li id="option_nogame_SK" value="SK">斯诺克/台球</li><li id="option_nogame_OP" value="OP">其他</li></ul>
                    </div>
                </div>
                <!-- 球类拉bar End-->

                <!--没有赛事-->
                <div id="even_none_nogame" class="live_noList" style="display: none;">现在还没有现场和未来赛事的直播流。</div>
                <!--没有赛事 End-->

            </div>
            <!-- nogame title End-->
            <div id="ctl_tv_div">
                <div id="time_list" class="live_timeList">
                    <div id="div_gtype" class="live_listG">
                        <div id="gtype_bar" style="display: none; width: 310px;">
                            <h1>直播日程表</h1>
                            <!-- 球类拉bar -->
                            <div id="sel_gtype" class="live_allSportsBTN"><tt id="select_gtype">所有体育</tt>
                                <div id="show_gtype" style="display:none" class="live_MINImenu" tabindex="100"><span class="live_MINImenu_arr"></span>
                                    <h1>选择体育</h1>
                                    <ul id="option_gtype" class="live_MINIul">
                                        <!-- 程式趴 -->
                                        <li id="option_All" value="All">所有球类</li><li id="option_FT" value="FT">足球</li><li id="option_BK" value="BK">篮球 / 美式足球</li><li id="option_TN" value="TN">网球</li><li id="option_VB" value="VB">排球</li><li id="option_BM" value="BM">羽毛球</li><li id="option_TT" value="TT">兵乓球</li><li id="option_BS" value="BS">棒球</li><li id="option_SK" value="SK">斯诺克/台球</li><li id="option_OP" value="OP">其他</li></ul>
                                </div>
                            </div>
                            <!-- 球类拉bar End-->
                            <span id="showX" onclick="showGameList();" class="live_sportsCloseBTN" style="display: none;"></span>
                        </div>
                        <!--没有赛事-->
                        <div id="even_none" style="display:none;" class="live_noList">现在还没有现场和未来赛事的直播流。</div>
                        <!--没有赛事 End-->

                        <!-- 赛事列表 -->
                        <div id="even_list" class="live_evenH_1">
                            <div id="showlayers">
                                <h2>2018-03-20</h2>
                                <table cellspacing="0" cellpadding="0" class="live_listTB">
                                    <tbody><tr id="live_txt_FT_2041820_87CCBABCBCBCBABCBABCBDBCB8CCB38FCDC7CECBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2041820');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2041820_87CCBABCBCBCBABCBABCBDBCB8CCB38FCDC7CECBCFCDCBA9B3" width="50" class="live_tv_off">18:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2041820_87CCBABCBCBCBABCBABCBDBCB8CCB38FCDC7CECBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>沙古尔罗PE vs 森柏欧MA</td>
                                    </tr>
                                    <tr id="live_txt_FT_2041878_8DBCBCBCBCBCBABCBCBCB6CCBEBCB387C8C7CECBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2041878');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2041878_8DBCBCBCBCBCBABCBCBCB6CCBEBCB387C8C7CECBCFCDCBA9B3" width="50" class="live_tv_off">20:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2041878_8DBCBCBCBCBCBABCBCBCB6CCBEBCB387C8C7CECBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>圣保罗SP vs 圣卡坦奴SP</td>
                                    </tr>
                                    <tr id="live_txt_FT_2041831_87CCBCBCB9BCBABCBABCBDBCB8CCB38ECCC7CECBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2041831');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2041831_87CCBCBCB9BCBABCBABCBDBCB8CCB38ECCC7CECBCFCDCBA9B3" width="50" class="live_tv_off">20:45</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2041831_87CCBCBCB9BCBABCBABCBDBCB8CCB38ECCC7CECBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>巴海亚BA vs 阿尔托斯PI</td>
                                    </tr>
                                    <tr id="live_txt_FT_2041842_87CCBBBCBBBCBABCBABCBDBCB8CCB38DCBC7CECBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2041842');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2041842_87CCBBBCBBBCBABCBABCBDBCB8CCB38DCBC7CECBCFCDCBA9B3" width="50" class="live_tv_off">20:45</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2041842_87CCBBBCBBBCBABCBABCBDBCB8CCB38DCBC7CECBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>格罗宝RN vs ABC RN</td>
                                    </tr>
                                    <tr id="live_txt_FT_2041853_87CCBCBCB8CCBABCBABCBDBCB8CCB38CCAC7CECBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2041853');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2041853_87CCBCBCB8CCBABCBABCBDBCB8CCB38CCAC7CECBCFCDCBA9B3" width="50" class="live_tv_off">20:45</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2041853_87CCBCBCB8CCBABCBABCBDBCB8CCB38CCAC7CECBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>CSA AL vs 卡尔拉CE</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <h2>2018-03-21</h2>
                                <table cellspacing="0" cellpadding="0" class="live_listTB">
                                    <tbody><tr id="live_txt_FT_2034102_86CCBEBCBCBCBABCBCBCBEBCB6CCB38DCFCECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034102');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034102_86CCBEBCBCBCBABCBCBCBEBCB6CCB38DCFCECBCCCFCDCBA9B3" width="50" class="live_tv_off">00:01</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034102_86CCBEBCBCBCBABCBCBCBEBCB6CCB38DCFCECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>熊本深红 vs 大宫松鼠</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034282_86CCB8CCB7CCBABCBCBCBEBCB6CCB38DC7CDCBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034282');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034282_86CCB8CCB7CCBABCBCBCBEBCB6CCB38DC7CDCBCCCFCDCBA9B3" width="50" class="live_tv_off">00:01</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034282_86CCB8CCB7CCBABCBCBCBEBCB6CCB38DC7CDCBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>盛冈戈鲁拉 vs 横滨SCC</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034285_86CCB8CCBABCBABCBCBCBEBCB6CCB38AC7CDCBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034285');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034285_86CCB8CCBABCBABCBCBCBEBCB6CCB38AC7CDCBCCCFCDCBA9B3" width="50" class="live_tv_off">00:01</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034285_86CCB8CCBABCBABCBCBCBEBCB6CCB38AC7CDCBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>福岛联队 vs 鹿儿岛联</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034288_86CCB7CCB7CCBABCBCBCBEBCB6CCB387C7CDCBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034288');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034288_86CCB7CCB7CCBABCBCBCBEBCB6CCB387C7CDCBCCCFCDCBA9B3" width="50" class="live_tv_off">00:01</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034288_86CCB7CCB7CCBABCBCBCBEBCB6CCB387C7CDCBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>富山胜利 vs 大阪櫻花U23</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034291_86CCB7CCBEBCBABCBCBCBEBCB6CCB38EC6CDCBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034291');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034291_86CCB7CCBEBCBABCBCBCBEBCB6CCB38EC6CDCBCCCFCDCBA9B3" width="50" class="live_tv_off">00:01</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034291_86CCB7CCBEBCBABCBCBCBEBCB6CCB38EC6CDCBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>藤枝MYFC vs 北九州向日葵</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034109_86CCBEBCBEBCBABCBCBCBEBCB6CCB386CFCECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034109');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034109_86CCBEBCBEBCBABCBCBCBEBCB6CCB386CFCECBCCCFCDCBA9B3" width="50" class="live_tv_off">01:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034109_86CCBEBCBEBCBABCBCBCBEBCB6CCB386CFCECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>山形蒙迪奥 vs 横滨</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034116_86CCB7CCBABCBABCBCBCBEBCB6CCB389CECECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034116');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034116_86CCB7CCBABCBABCBCBCBEBCB6CCB389CECECBCCCFCDCBA9B3" width="50" class="live_tv_off">01:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034116_86CCB7CCBABCBABCBCBCBEBCB6CCB389CECECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>千叶市原 vs 卡马塔马尔赞岐</td>
                                    </tr>

                                    <tr id="live_txt_FT_2034123_86CCB6CCB9CCBABCBCBCBEBCB6CCB38CCDCECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034123');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034123_86CCB6CCB9CCBABCBCBCBEBCB6CCB38CCDCECBCCCFCDCBA9B3" width="50" class="live_tv_off">01:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034123_86CCB6CCB9CCBABCBCBCBEBCB6CCB38CCDCECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>甲府风林 vs 德岛沃堤</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034130_86CCB6CCBEBCBABCBCBCBEBCB6CCB38FCCCECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034130');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034130_86CCB6CCBEBCBABCBCBCBEBCB6CCB38FCCCECBCCCFCDCBA9B3" width="50" class="live_tv_off">01:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034130_86CCB6CCBEBCBABCBCBCBEBCB6CCB38FCCCECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>岐阜 vs 栃木SC</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034137_86CCBEBCB7CCBABCBCBCBEBCB6CCB388CCCECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034137');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034137_86CCBEBCB7CCBABCBCBCBEBCB6CCB388CCCECBCCCFCDCBA9B3" width="50" class="live_tv_off">01:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034137_86CCBEBCB7CCBABCBCBCBEBCB6CCB388CCCECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>大分三神 vs 水户霍利克</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034305_86CCB8CCB9CCBABCBCBCBEBCB6CCB38ACFCCCBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034305');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034305_86CCB8CCB9CCBABCBCBCBEBCB6CCB38ACFCCCBCCCFCDCBA9B3" width="50" class="live_tv_off">01:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034305_86CCB8CCB9CCBABCBCBCBEBCB6CCB38ACFCCCBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>SC相模原 vs 浅蓝沼津</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034308_86CCB8CCBEBCBABCBCBCBEBCB6CCB387CFCCCBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034308');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034308_86CCB8CCBEBCBABCBCBCBEBCB6CCB387CFCCCBCCCFCDCBA9B3" width="50" class="live_tv_off">01:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034308_86CCB8CCBEBCBABCBCBCBEBCB6CCB387CFCCCBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>长野琶扼搂 vs 东京U23</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034311_86CCB7CCB9CCBABCBCBCBEBCB6CCB38ECECCCBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034311');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034311_86CCB7CCB9CCBABCBCBCBEBCB6CCB38ECECCCBCCCFCDCBA9B3" width="50" class="live_tv_off">01:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034311_86CCB7CCB9CCBABCBCBCBEBCB6CCB38ECECCCBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>琉球 vs 鸟取SC</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034314_86CCB8CCBCBCBABCBCBCBEBCB6CCB38BCECCCBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034314');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034314_86CCB8CCBCBCBABCBCBCBEBCB6CCB38BCECCCBCCCFCDCBA9B3" width="50" class="live_tv_off">01:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034314_86CCB8CCBCBCBABCBCBCBEBCB6CCB38BCECCCBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>大阪飞脚U23 vs 秋田蓝色闪电</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034144_86CCB6CCBCBCBABCBCBCBEBCB6CCB38BCBCECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034144');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034144_86CCB6CCBCBCBABCBCBCBEBCB6CCB38BCBCECBCCCFCDCBA9B3" width="50" class="live_tv_off">02:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034144_86CCB6CCBCBCBABCBCBCBEBCB6CCB38BCBCECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>京都不死鸟 vs 冈山雉鸡</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034151_86CCBEBCB9CCBABCBCBCBEBCB6CCB38ECACECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034151');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034151_86CCBEBCB9CCBABCBCBCBEBCB6CCB38ECACECBCCCFCDCBA9B3" width="50" class="live_tv_off">02:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034151_86CCBEBCB9CCBABCBCBCBEBCB6CCB38ECACECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>雷诺法山口 vs 泽维真金泽</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034158_86CCB6CCBABCBABCBCBCBEBCB6CCB387CACECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034158');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034158_86CCB6CCBABCBABCBCBCBEBCB6CCB387CACECBCCCFCDCBA9B3" width="50" class="live_tv_off">02:30</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034158_86CCB6CCBABCBABCBCBCBEBCB6CCB387CACECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>新泻天鹅 vs 爱媛</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034165_86CCB6CCB7CCBABCBCBCBEBCB6CCB38AC9CECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034165');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034165_86CCB6CCB7CCBABCBCBCBEBCB6CCB38AC9CECBCCCFCDCBA9B3" width="50" class="live_tv_off">03:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034165_86CCB6CCB7CCBABCBCBCBEBCB6CCB38AC9CECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>东京日视 vs 福冈黄蜂</td>
                                    </tr>
                                    <tr id="live_txt_FT_2034172_86CCB7CCBCBCBABCBCBCBEBCB6CCB38DC8CECBCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2034172');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2034172_86CCB7CCBCBCBABCBCBCBEBCB6CCB38DC8CECBCCCFCDCBA9B3" width="50" class="live_tv_off">05:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2034172_86CCB7CCBCBCBABCBCBCBEBCB6CCB38DC8CECBCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>町田泽维亚 vs 松本山雅</td>
                                    </tr>
                                    <tr id="live_txt_FT_2045197_88CCBABCBEBCBABCBCBCB6CCBCBCB388C6CECACBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2045197');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2045197_88CCBABCBEBCBABCBCBCB6CCBCBCB388C6CECACBCFCDCBA9B3" width="50" class="live_tv_off">16:45</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2045197_88CCBABCBEBCBABCBCBCB6CCBCBCB388C6CECACBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>那西昂诺 vs 利伯泰德</td>
                                    </tr>
                                    <tr id="live_txt_FT_2043370_87CCBDBCBDBCBABCBABCBDBCB8CCB38FC8CCCCCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2043370');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2043370_87CCBDBCBDBCBABCBABCBDBCB8CCB38FC8CCCCCBCFCDCBA9B3" width="50" class="live_tv_off">18:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2043370_87CCBDBCBDBCBABCBABCBDBCB8CCB38FC8CCCCCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>费罗维里亚CE vs 维多利亚BA</td>
                                    </tr>

                                    <tr id="live_txt_FT_2045200_88CCBABCBABCBABCBCBCB6CCBCBCB38FCFCDCACBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2045200');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2045200_88CCBABCBABCBABCBCBCB6CCBCBCB38FCFCDCACBCFCDCBA9B3" width="50" class="live_tv_off">18:50</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2045200_88CCBABCBABCBABCBCBCB6CCBCBCB38FCFCDCACBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>波特诺 vs 独立FBC</td>
                                    </tr>

                                    <tr id="live_txt_FT_2042414_8DBCBCBCB7CCBABCBCBCB6CCBEBCB38BCECBCDCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2042414');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2042414_8DBCBCBCB7CCBABCBCBCB6CCBEBCB38BCECBCDCBCFCDCBA9B3" width="50" class="live_tv_off">20:45</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2042414_8DBCBCBCB7CCBABCBCBCB6CCBEBCB38BCECBCDCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>彭美拉斯SP vs 甘美奥诺瓦里桑蒂诺SP</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <h2>2018-03-22</h2>
                                <table cellspacing="0" cellpadding="0" class="live_listTB">
                                    <tbody><tr id="live_txt_FT_2044619_86CCB9BCBABCB7CCB7CCB386CEC9CBCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2044619');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2044619_86CCB9BCBABCB7CCB7CCB386CEC9CBCBCFCDCBA9B3" width="50" class="live_tv_off">07:35</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2044619_86CCB9BCBABCB7CCB7CCB386CEC9CBCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>中国 vs 威尔斯</td>
                                    </tr>
                                    <tr id="live_txt_FT_2044448_8ABCBCBCB9CCBABCBCBCBEBCB7CCB387CBCBCBCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2044448');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2044448_8ABCBCBCB9CCBABCBCBCBEBCB7CCB387CBCBCBCBCFCDCBA9B3" width="50" class="live_tv_off">09:40</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2044448_8ABCBCBCB9CCBABCBCBCBEBCB7CCB387CBCBCBCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>迪巴阿富查伊拉 vs 艾尔维达</td>
                                    </tr>
                                    <tr id="live_txt_FT_2044459_8ABCBDBCBCBCBABCBCBCBEBCB7CCB386CACBCBCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2044459');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2044459_8ABCBDBCBCBCBABCBCBCBEBCB7CCB386CACBCBCBCFCDCBA9B3" width="50" class="live_tv_off">12:15</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2044459_8ABCBDBCBCBCBABCBCBCBEBCB7CCB386CACBCBCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>瓦斯尔杜拜 vs 沙巴柏阿尔艾利杜拜</td>
                                    </tr>
                                    <tr id="live_txt_FT_2042426_8DBCBCBCBEBCBABCBCBCB6CCBEBCB389CDCBCDCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2042426');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2042426_8DBCBCBCBEBCBABCBCBCB6CCBEBCB389CDCBCDCBCFCDCBA9B3" width="50" class="live_tv_off">19:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2042426_8DBCBCBCBEBCBABCBCBCB6CCBEBCB389CDCBCDCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>哥连泰斯SP vs 伯拉根森SP</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <h2>2018-03-23</h2>
                                <table cellspacing="0" cellpadding="0" class="live_listTB">
                                    <tbody><tr id="live_txt_FT_2032142_88CCB6CCBDBCB6CCBBBCB38DCBCECDCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2032142');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2032142_88CCB6CCBDBCB6CCBBBCB38DCBCECDCCCFCDCBA9B3" width="50" class="live_tv_off">04:50</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2032142_88CCB6CCBDBCB6CCBBBCB38DCBCECDCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>阿德莱得联 vs 纽卡斯托</td>
                                    </tr>
                                    <tr id="live_txt_FT_2044630_86CCB9BCBBBCB7CCB7CCB38FCCC9CBCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2044630');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2044630_86CCB9BCBBBCB7CCB7CCB38FCCC9CBCBCFCDCBA9B3" width="50" class="live_tv_off">07:35</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2044630_86CCB9BCBBBCB7CCB7CCB38FCCC9CBCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>乌拉圭 vs 捷克</td>
                                    </tr>
                                    <tr id="live_txt_FT_2033139_89BCBEBCBEBCBABCBCBCB6CCBABCB386CCCECCCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2033139');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2033139_89BCBEBCBEBCBABCBCBCB6CCBABCB386CCCECCCCCFCDCBA9B3" width="50" class="live_tv_off">09:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2033139_89BCBEBCBEBCBABCBCBCB6CCBABCB386CCCECCCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>马其顿U21 vs 俄罗斯U21</td>
                                    </tr>
                                    <tr id="live_txt_FT_2044774_8CBCBDBCBCBCBABCBCBCBEBCB7CCB38BC8C8CBCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2044774');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2044774_8CBCBDBCBCBCBABCBCBCBEBCB7CCB38BC8C8CBCBCFCDCBA9B3" width="50" class="live_tv_off">12:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2044774_8CBCBDBCBCBCBABCBCBCBEBCB7CCB38BC8C8CBCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>俄罗斯 vs 巴西</td>
                                    </tr>
                                    <tr id="live_txt_FT_2040213_8EBCB6CCBCBCBABCBCBCB6CCB6CCB38CCECDCFCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2040213');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2040213_8EBCB6CCBCBCBABCBCBCB6CCB6CCB38CCECDCFCBCFCDCBA9B3" width="50" class="live_tv_off">13:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2040213_8EBCB6CCBCBCBABCBCBCB6CCB6CCB38CCECDCFCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>挪威 vs 澳洲</td>
                                    </tr>
                                    <tr id="live_txt_FT_2044709_8ABCBBBCBCBCBABCBCBCB6CCB6CCB386CFC8CBCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2044709');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2044709_8ABCBBBCBCBCBABCBCBCB6CCB6CCB386CFC8CBCBCFCDCBA9B3" width="50" class="live_tv_off">13:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2044709_8ABCBBBCBCBCBABCBCBCB6CCB6CCB386CFC8CBCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>保加利亚 vs 波斯尼亚和黑塞哥维那</td>
                                    </tr>
                                    <tr id="live_txt_FT_2040218_8ABCB6CCB9CCBABCBCBCBEBCB9CCB387CECDCFCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2040218');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2040218_8ABCB6CCB9CCBABCBCBCBEBCB9CCB387CECDCFCBCFCDCBA9B3" width="50" class="live_tv_off">13:30</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2040218_8ABCB6CCB9CCBABCBCBCBEBCB9CCB387CECDCFCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>土耳其 vs 爱尔兰</td>
                                    </tr>
                                    <tr id="live_txt_FT_2040223_8ABCB6CCBEBCBABCBCBCBEBCB9CCB38CCDCDCFCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2040223');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2040223_8ABCB6CCBEBCBABCBCBCBEBCB9CCB38CCDCDCFCBCFCDCBA9B3" width="50" class="live_tv_off">14:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2040223_8ABCB6CCBEBCBABCBCBCBEBCB9CCB38CCDCDCFCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>希腊 vs 瑞士</td>
                                    </tr>
                                    <tr id="live_txt_FT_2033309_8ABCB8CCBEBCBABCBCBCB6CCBABCB386CFCCCCCCCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2033309');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2033309_8ABCB8CCBEBCBABCBCBCB6CCBABCB386CFCCCCCCCFCDCBA9B3" width="50" class="live_tv_off">15:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2033309_8ABCB8CCBEBCBABCBCBCB6CCBABCB386CFCCCCCCCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>直布罗陀U21 vs 塞尔维亚U21</td>
                                    </tr>
                                    <tr id="live_txt_FT_2043908_86CCB7CCBBBCB6CCBABCB387CFC6CCCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2043908');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2043908_86CCB7CCBBBCB6CCBABCB387CFC6CCCBCFCDCBA9B3" width="50" class="live_tv_off">15:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2043908_86CCB7CCBBBCB6CCBABCB387CFC6CCCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>乌德勒支青年队 vs PSV燕豪芬青年队</td>
                                    </tr>
                                    <tr id="live_txt_FT_2043920_86CCB7CCB6CCB6CCBABCB38FCDC6CCCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2043920');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2043920_86CCB7CCB6CCB6CCBABCB38FCDC6CCCBCFCDCBA9B3" width="50" class="live_tv_off">15:00</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2043920_86CCB7CCB6CCB6CCBABCB38FCDC6CCCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>希蒙 vs 奥斯</td>
                                    </tr>
                                    <tr id="live_txt_FT_2044805_8BBCBBBCBABCBABCBCBCB6CCB6CCB38ACFC7CBCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2044805');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2044805_8BBCBBBCBABCBABCBCBCB6CCB6CCB38ACFC7CBCBCFCDCBA9B3" width="50" class="live_tv_off">15:30</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2044805_8BBCBBBCBABCBABCBCBCB6CCB6CCB38ACFC7CBCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>塞尔维亚  vs 摩洛哥</td>
                                    </tr>
                                    <tr id="live_txt_FT_2040228_8BBCB9CCBABCBABCBCBCB6CCBBBCB387CDCDCFCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2040228');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2040228_8BBCB9CCBABCBABCBCBCB6CCBBBCB387CDCDCFCBCFCDCBA9B3" width="50" class="live_tv_off">15:30</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2040228_8BBCB9CCBABCBABCBCBCB6CCBBBCB387CDCDCFCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>秘鲁  vs 克罗地亚</td>
                                    </tr>
                                    <tr id="live_txt_FT_2040248_8ABCB6CCBCBCBABCBCBCB6CCB6CCB387CBCDCFCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2040248');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2040248_8ABCB6CCBCBCBABCBCBCB6CCB6CCB387CBCDCFCBCFCDCBA9B3" width="50" class="live_tv_off">15:45</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2040248_8ABCB6CCBCBCBABCBCBCB6CCB6CCB387CBCDCFCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>波兰 vs 尼日利亚</td>
                                    </tr>
                                    <tr id="live_txt_FT_2044760_86CCB9BCBCBCB7CCB7CCB38FC9C8CBCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2044760');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2044760_86CCB9BCBCBCB7CCB7CCB38FC9C8CBCBCFCDCBA9B3" width="50" class="live_tv_off">15:45</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2044760_86CCB9BCBCBCB7CCB7CCB38FC9C8CBCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>苏格兰 vs 哥斯达黎加</td>
                                    </tr>
                                    <tr id="live_txt_FT_2044800_88CCB6CCB9CCBABCBCBCBEBCBDBCB38FCFC7CBCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2044800');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2044800_88CCB6CCB9CCBABCBCBCBEBCBDBCB38FCFC7CBCBCFCDCBA9B3" width="50" class="live_tv_off">15:45</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2044800_88CCB6CCB9CCBABCBCBCBEBCBDBCB38FCFC7CBCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>葡萄牙  vs 埃及</td>
                                    </tr>
                                    <tr id="live_txt_FT_2040233_87CCB7CCB9CCBABCBCBCB6CCBABCB38CCCCDCFCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2040233');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2040233_87CCB7CCB9CCBABCBCBCB6CCBABCB38CCCCDCFCBCFCDCBA9B3" width="50" class="live_tv_off">15:45</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2040233_87CCB7CCB9CCBABCBCBCB6CCBABCB38CCCCDCFCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>阿根廷  vs 意大利</td>
                                    </tr>
                                    <tr id="live_txt_FT_2040259_8CBCBABCB9CCBABCBCBCB6CCBBBCB386CACDCFCBCFCDCBA9B3" class="live_txt_off" onclick="OpenTV_chgType('FT_2040259');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                        <td id="live_tv_FT_2040259_8CBCBABCB9CCBABCBCBCB6CCBBBCB386CACDCFCBCFCDCBA9B3" width="50" class="live_tv_off">22:30</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                        <td id="live_gtype_FT_2040259_8CBCBABCB9CCBABCBCBCB6CCBBBCB386CACDCFCBCFCDCBA9B3" width="30" class="live_sc_off">&nbsp;</td>
                                        <td>墨西哥  vs 冰岛</td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- 赛事列表 End-->
                    </div>
                </div>
                <!-- body -->
                <div id="div_body" class="live_oddsG">
                    <!--计分板-->
                    <div id="div_info" class="live_scoreDIV" style="display: none;">
                        <!--足球-->
                        <div id="info_FT" style="display:none">
                            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_SC">
                                <tbody><tr>
                                    <td id="FT_clothes_h" width="20">&nbsp;</td>
                                    <td id="FT_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                                    <td class="live_score_team"><span id="FT_team_h"></span><span id="FT_red_h" style="display:none" class="live_score_redCard">0</span></td>
                                </tr>
                                <tr>
                                    <td id="FT_clothes_c">&nbsp;</td>
                                    <td id="FT_sc_c" class="TXTnowrap tuhuiWord">0</td>
                                    <td class="live_score_team"><span id="FT_team_c"></span><span id="FT_red_c" style="display:none" class="live_score_redCard">0</span></td>
                                </tr>
                                </tbody></table>
                        </div>

                        <!--篮球&棒球&其他-->
                        <div id="info_BK" style="display:none">
                            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_BK">
                                <tbody><tr>
                                    <td id="BK_clothes_h" width="20">&nbsp;</td>
                                    <td id="BK_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="BK_team_h" class="live_score_team"></td>
                                </tr>
                                <tr>
                                    <td id="BK_clothes_c">&nbsp;</td>
                                    <td id="BK_sc_c" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="BK_team_c" class="live_score_team"></td>
                                </tr>
                                </tbody></table>
                        </div>

                        <div id="info_BS" style="display:none">
                            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_BS">
                                <tbody><tr>
                                    <td id="BS_clothes_h" width="20">&nbsp;</td>
                                    <td id="BS_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="BS_team_h" class="live_score_team"></td>
                                </tr>
                                <tr>
                                    <td id="BS_clothes_c">&nbsp;</td>
                                    <td id="BS_sc_c" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="BS_team_c" class="live_score_team"></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="padd0">
                                        <table cellspacing="0" cellpadding="0" class="live_scoreTB_inside">
                                            <tbody><tr>
                                                <td width="35%">
                                                    <div class="live_LeiBaoG">
                                                        <span id="BS_base_1B" class="live_LeiBao01"></span><span id="BS_base_2B" class="live_LeiBao02"></span><span id="BS_base_3B" class="live_LeiBao03"></span>
                                                    </div>
                                                </td>
                                                <td width="65%" class="Word_Paddright">出局:<tt id="BS_out_count" class="dark_pink"></tt></td>
                                            </tr>
                                            </tbody></table>
                                    </td>
                                </tr>
                                </tbody></table>
                            <span id="BS_game_count" class="live_FTarr"></span><!--场次-->
                        </div>

                        <div id="info_OP" style="display:none">
                            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_BK">
                                <tbody><tr>
                                    <td id="OP_clothes_h" width="20">&nbsp;</td>
                                    <td id="OP_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                                    <td class="live_score_team"><span id="OP_team_h"></span><span id="OP_red_h" style="display:none" class="live_score_redCard">0</span></td>
                                </tr>
                                <tr>
                                    <td id="OP_clothes_c">&nbsp;</td>
                                    <td id="OP_sc_c" class="TXTnowrap tuhuiWord">0</td>
                                    <td class="live_score_team" <span="" id="OP_team_c">&gt;<span id="OP_red_c" style="display:none" class="live_score_redCard">0</span></td>
                                </tr>
                                </tbody></table>
                        </div>

                        <!--网球-->
                        <div id="info_TN" style="display:none">
                            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_TN">
                                <tbody><tr>
                                    <td id="TN_game_h" width="20" class="Word_Paddleft tuhuiWord">0</td>
                                    <td id="TN_set_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="TN_point_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="TN_serve_h" width="21">&nbsp;</td>
                                    <td id="TN_team_h" class="live_score_team"></td>
                                    <td rowspan="2" id="TN_best" class="live_score_best"></td>
                                </tr>
                                <tr>
                                    <td id="TN_game_c" class="Word_Paddleft tuhuiWord">0</td>
                                    <td id="TN_set_c" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="TN_point_c" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="TN_serve_c">&nbsp;</td>
                                    <td id="TN_team_c" class="live_score_team"></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="padd0">
                                        <table cellspacing="0" cellpadding="0" class="live_scoreTB_inside">
                                            <tbody><tr>
                                                <td width="50%"><span id="TN_before"></span><span id="TN_weather" class="RedWord" style="display:none">天气延赛</span></td>
                                                <td width="50%" class="Word_Paddright topTD">总局数 <tt id="TN_total" class="dark_pink"></tt></td>
                                            </tr>
                                            </tbody></table>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>

                        <!--羽毛 乒乓 排球-->
                        <div id="info_VB" style="display:none">
                            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_TN">
                                <tbody><tr>
                                    <td id="VB_set_h" width="20" class="Word_Paddleft tuhuiWord">0</td>
                                    <td id="VB_point_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="VB_serve_h" width="21" class="live_scoreIcon_a">&nbsp;</td>
                                    <td id="VB_team_h" class="live_score_team"></td>
                                    <td rowspan="2" id="VB_best" class="live_score_best"></td>
                                </tr>
                                <tr>
                                    <td id="VB_set_c" class="Word_Paddleft tuhuiWord">0</td>
                                    <td id="VB_point_c" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="VB_serve_c" class="live_scoreIcon_b">&nbsp;</td>
                                    <td id="VB_team_c" class="live_score_team"></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="padd0">
                                        <table cellspacing="0" cellpadding="0" class="live_scoreTB_inside">
                                            <tbody><tr>
                                                <td id="VB_before" width="50%"></td>
                                                <td width="50%" class="Word_Paddright topTD">总分数 <tt id="VB_total" class="dark_pink"></tt></td>
                                            </tr>
                                            </tbody></table>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>

                        <div id="info_BM" style="display:none">
                            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_TN">
                                <tbody><tr>
                                    <td id="BM_set_h" width="20" class="Word_Paddleft tuhuiWord">0</td>
                                    <td id="BM_point_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="BM_serve_h" width="21" class="live_scoreIcon_a">&nbsp;</td>
                                    <td id="BM_team_h" class="live_score_team"></td>
                                    <td rowspan="2" id="BM_best" class="live_score_best"></td>
                                </tr>
                                <tr>
                                    <td id="BM_set_c" class="Word_Paddleft tuhuiWord">0</td>
                                    <td id="BM_point_c" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="BM_serve_c" class="live_scoreIcon_b">&nbsp;</td>
                                    <td id="BM_team_c" class="live_score_team"></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="padd0">
                                        <table cellspacing="0" cellpadding="0" class="live_scoreTB_inside">
                                            <tbody><tr>
                                                <td id="BM_before" width="50%"></td>
                                                <td width="50%" class="Word_Paddright topTD">总分数 <tt id="BM_total" class="dark_pink"></tt></td>
                                            </tr>
                                            </tbody></table>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>

                        <div id="info_TT" style="display:none">
                            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_TN">
                                <tbody><tr>
                                    <td id="TT_set_h" width="20" class="Word_Paddleft tuhuiWord">0</td>
                                    <td id="TT_point_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="TT_serve_h" width="21" class="live_scoreIcon_a">&nbsp;</td>
                                    <td id="TT_team_h" class="live_score_team"></td>
                                    <td rowspan="2" id="TT_best" class="live_score_best"></td>
                                </tr>
                                <tr>
                                    <td id="TT_set_c" class="Word_Paddleft tuhuiWord">0</td>
                                    <td id="TT_point_c" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="TT_serve_c" class="live_scoreIcon_b">&nbsp;</td>
                                    <td id="TT_team_c" class="live_score_team"></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="padd0">
                                        <table cellspacing="0" cellpadding="0" class="live_scoreTB_inside">
                                            <tbody><tr>
                                                <td id="TT_before" width="50%"></td>
                                                <td width="50%" class="Word_Paddright topTD">总分数 <tt id="TT_total" class="dark_pink"></tt></td>
                                            </tr>
                                            </tbody></table>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>

                        <div id="info_SK" style="display:none">
                            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_BS">
                                <tbody><tr>
                                    <td id="SK_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="SK_team_h" class="live_score_team"></td>
                                    <td rowspan="2" id="SK_best" class="live_SK_left"></td>
                                </tr>
                                <tr>
                                    <td id="SK_sc_c" class="TXTnowrap tuhuiWord">0</td>
                                    <td id="SK_team_c" class="live_score_team"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--计分板 End-->

                    <!-- 没有TV播放 -->
                    <div valign="top" id="DemoImgLayer" class="live_demo_mini" style="display:none"></div>
                    <!-- 没有TV播放 End-->
                    <!--视讯影片区-->
                    <div id="FlahLayer" style="" class="live_movieDIV">

                        <!-- 320pxperfrome -->
                        <div id="live_320tv" class="live_320pxG" style="display:none;">
                            <span></span>
                            如果视频没有播放, 请点击电视屏幕.
                        </div>

                        <div id="videoFrame" style="display:none;" class="dome_L"></div>
                        <div id="dome_L" class="dome_L">
                            <iframe id="DefLive" name="DefLive" width="100%" height="289" src="" scrolling="no" frameborder="0" framespacing="0" cellspacing="0" cellpadding="0" style="display:none;"></iframe>
                        </div>

                        <!--TV未播放假图 -->
                        <div id="div_fake" class="live_TVdemoBG01" style="">点击播放。</div>
                        <!-- img : live_TVdemoBG01 / perform : live_TVdemoBG02 / unas : live_TVdemoBG03 -->
                        <!--TV未播放假图 End-->

                    </div>
                    <!--视讯影片区 End-->
                    <!--玩法没开-->
                    <div id="wtype_close" class="liveTV_closeDIV" style="">无提供交易。</div>
                    <!--无法投注-->
                    <div id="bet_none" class="liveTV_closeDIV" style="display:none">您所选的赛事暂时无法投注。</div>
                    <!--无法投注 End-->
                </div>
                <!-- body End -->
                <!--赛事直播日程表-->
                <div id="main" style="display:none">
                    <!-- 赛事模组 -->
                    <div id="tb_layer" style="display:none">
                        <xmp>

                            <h2>*GAME_DATE*</h2>
                            <table cellspacing="0" cellpadding="0" class="live_listTB">
                                *GAME_LIST*
                            </table>

                        </xmp>
                    </div>

                    <div id="tr_layer" style="display:none">
                        <xmp>
                            <tr id="live_txt_*ID*_*EVENTID*" *LIVE_TXT_CLASS* onClick="OpenTV_chgType('*ID*');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                                <td id="live_tv_*ID*_*EVENTID*" width="50" *LIVE_TV_CLASS*>*TIME*</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                                <td id="live_gtype_*ID*_*EVENTID*" width="30" *LIVE_GTYPE_CLASS*>&nbsp;</td>
                                <td>*TEAMH* vs *TEAMC*</td>
                            </tr>

                        </xmp>
                    </div>
                    <!-- 赛事模组 End -->
                </div>
                <!--赛事直播日程表 End-->

                <!-- 盘面 Start -->
                <div id="main_bet" style="display: none;">
                    <div id="bet_mem" class="bet_mem">

                        <div id="bet_div" style="display:none">
                            <iframe id="bet_order_frame" name="bet_order_frame" scrolling="NO" frameborder="NO" border="0" width="100%" height="483"></iframe>
                        </div>
                        <div id="LiveTV_mem" class="LiveTV_mem" style="display: none;">

                            <iframe id="Live_mem" name="Live_mem" scrolling="NO" frameborder="NO" border="0" width="100%" height="0"></iframe>
                        </div>
                    </div>
                </div>
                <!-- 盘面 End -->
                <!-- load data -->
                <iframe id="reloadPHP" name="reloadPHP" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>
                <iframe id="reloadgame" name="reloadgame" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>
                <iframe id="registLive" name="registLive" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>
                <!-- load data End-->

            </div>
        </div>

        <!--广告区域-->
        <div id="div_ad" class="live_adG CN live_NOTV_ad">
                        <span class="live_ad01">
                        	<span class="live_ad01L" onclick="parent.showMyAccount('NewFeatures');"></span>
                        	<span class="live_ad01R" onclick="parent.showMyAccount('setEmail');"></span>
                        </span>
            <span class="live_ad04"></span>
            <span class="live_ad05" onclick="parent.showMyAccount('NewFeatures');" style="cursor: pointer;"></span>
            <span class="live_ad02"></span>
            <!--<div id="hideAD">
                <a href="#" target="_blank"><span class="live_ad03"></span></a>
            </div>-->
        </div>

    </div>

</div>


</body>
</html>