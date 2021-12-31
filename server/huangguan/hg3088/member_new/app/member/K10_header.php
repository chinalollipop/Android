<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "./include/address.mem.php";
require ("./include/config.inc.php");

include "./ip.php";
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$showtype=$_REQUEST['showtype'];
require ("./include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$memname=$_SESSION['UserName'];
$username=$_SESSION['UserName'];
switch ($showtype){
case "future":
	$style='HFU';
	$Mtype="early";
	break;
case "":
	$style='HFT';
	$Mtype="today";	
	break;
}
$showtime = date("Y/m/d H:i:s");
$paysql = "select Address from ".DBPREFIX."web_payment_data where Switch=1";
$payresult = mysqli_query($dbLink,$paysql);
$payrow=mysqli_fetch_assoc($payresult);
$address=$payrow['Address'];
//echo iconv("GB2312","UTF-8//IGNORE",convertip(get_real_ip()));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
echo "<script>if(self == top) parent.location='".BROWSER_IP."'\n;</script>";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="/style/member/mem_header_ft_cn.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script type="text/javascript" src="../../js/jquery.js"></script><script type="text/javascript" src="../../js/common.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../js/header.js?v=<?php echo AUTOVER; ?>"></script>
<script>
document.oncontextmenu=new Function("event.returnValue=false");
document.onselectstart=new Function("event.returnValue=false");
</script>

</head>
<body id="<?php echo $style ?>" class="bodyset" onLoad="SetRB('FT','<?php echo $uid?>');onloaded();" >
<div style="z-index:3000;float: left; display:none;">
	<iframe id="memOnline" name="memOnline" scrolling="NO" frameborder="NO" border="0" height="500" width="800" ></iframe>
</div>
<div id="container">
  <input type="hidden" id="uid" name="uid" value="<?php echo $uid?>">
  <input type="hidden" id="langx" name="langx" value="<?php echo $langx?>">

  <div id="header"><span><h1>&nbsp;</h1></span></div>
  <div id="welcome">
	<ul>
  	  <!--會員帳號-->
      <script type="text/javascript" language="javascript">

window.onload=function (){
stime();
}
var c=0;
var Y=<?php echo date('Y')?>,M=<?php echo date('m')?>,D=<?php echo date('d')?>;
function stime() {
c++
sec=<?php echo (date('H')*3600+date('i')*60+date('s'))?>+c;
H=Math.floor(sec/3600)%24
I=Math.floor(sec/60)%60
S=sec%60
if(S<10) S='0'+S;
if(I<10) I='0'+I;
if(H<10) H='0'+H;
if (H=='00' & I=='00' & S=='00') D=D+1; //日进位
if (M==2) { //判断是否为二月份******
if (Y%4==0 && !Y%100==0 || Y%400==0) { //是闰年(二月有28天)
if (D==30){M+=1;D=1;} //月份进位
}
else { //非闰年(二月有29天)
if (D==29){M+=1;D=1;} //月份进位
}
}
else { //不是二月份的月份******
if (M==4 || M==6 || M==9 || M==11) { //小月(30天)
if (D==31) {M+=1;D=1;} //月份进位
}
else { //大月(31天)
if (D==32){M+=1;D=1;} //月份进位
}
}
if (M==13) {Y+=1;M=1;} //年份进位
//setInterval(stime,1000);
setTimeout("stime()", 1000);
document.getElementById("head_year").innerHTML = Y+'年'+M+'月'+D+'日 '+H+':'+I;
}

</script>
      <li class="name">您好, <strong id="userid"><?php echo $memname ?></strong>
      	<div id="head_date"><span id="head_year"></span></div>
      </li>

	  <?php
	  $mysql = "select datasite,datasite_tw,datasite_en,Uid,Uid_tw,Uid_en from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
switch($langx)	{
case "zh-cn":
	$suid=$row['Uid_tw'];
	$site=$row['datasite_tw'];
	break;
case "zh-cn":
	$suid=$row['Uid'];
	$site=$row['datasite'];
	break;
case "en-us":
	$suid=$row['Uid_en'];
	$site=$row['datasite_en'];
	break;
case "th-tis":
	$suid=$row['Uid_en'];
	$site=$row['datasite_en'];
	break;
}

$m_date=date('Y-m-d');
$date=date('m-d');
$cou_num=0;

	  require ("./include/curl_http.php");

		?>
     
<!--      <li class="early" id=""><span id="todayType" style="display:none;">在线存款</span><a href="--><?php//=BROWSER_IP?><!--/app/member/money/chbank.php?uid=--><?php//=$uid ?><!--&langx=--><?php//=$langx ?><!--&username=--><?php//=$username?><!--"  target="body"id="todayshow" style="display:;">在线存款</a></li>-->
<!--      <li class="early" id=""><span id="earlyType" style="display:none;">在线提款</span><a href="--><?php//=BROWSER_IP?><!--/app/member/money/withdrawal.php?uid=--><?php//=$uid?><!--&langx=--><?php//=$langx?><!--&username=--><?php//=$username?><!--" target="body" id="earlyshow" style="display:;">在线提款</a></li>-->
<!--      <li class="early" id=""><span id="todayType" style="display:none;">存取款记录</span><a href="--><?php//=BROWSER_IP?><!--/app/member/onlinepay/record.php?uid=--><?php//=$uid?><!--&langx=--><?php//=$langx?><!--&username=--><?php//=$username?><!--" target="body" id="todayshow" style="display:;">财务记录</a></li>-->
<!--    -->
    </ul>
  </div>
  <div id="nav">
    <ul class="level1">

      <li class="ft"><span class="ball"><a onclick="chg_button_bg('FT','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/FT_header.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=zh-cn&mtype=4','<?php echo BROWSER_IP?>/app/member/FT_<?php echo ($showtype=='future')?"future":"browse" ?>/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2');">足球
<?php 
$m_date=date('Y-m-d');
$date=date('m-d');
$cou_num=0;
//**********************足球*********************/
	if ($showtype=="future"){
	//R
	$mysql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FU' and `M_Date` >'$m_date' and `S_Show`=1  and `Open`=1 order by M_Start,MB_MID";
	$result = mysqli_query($dbLink, $mysql);
	$cou_num=intval(mysqli_num_rows($result));
		}
else{	
	//R
	$mysql="select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FT' and `M_Start` > now( ) AND `M_Date` ='$m_date' and `S_Show`=1 and `Open`=1 order by M_Start,$m_league,MB_MID";
	$result = mysqli_query($dbLink, $mysql);
	$cou_num=intval(mysqli_num_rows($result));
		//RE
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$site."/app/member/FT_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	$html_data=$curl->fetch_url("".$site."/app/member/FT_browse/body_var.php?rtype=re&uid=$suid&langx=$langx&mtype=3");	
	preg_match_all("/parent.gamount=(.+?);/is",$html_data,$matches);
	$cou_num+=$matches[1][0];
	}
?>
(<strong class="game_sum" id="FT_games"><?php echo $cou_num?></strong>)</a></span></li>
<?php
$cou_num=0;
//**********************篮球*********************/
	if ($showtype=="future"){
	//R
	$mysql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BU' and `M_Date` >'$m_date' and S_Show=1 and $mb_team!='' order by M_Start,$mb_team,MB_MID";
	$result = mysqli_query($dbLink, $mysql);
	$cou_num=intval(mysqli_num_rows($result));		
	}
else{	
	//R
	$mysql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and  `M_Start` > now( ) AND `M_Date` ='$m_date' and S_Show=1 and RQ_Show!=1 and $mb_team!='' order by M_Start,MB_MID";	
	$result = mysqli_query($dbLink, $mysql);
	$cou_num+=intval(mysqli_num_rows($result));		
		//RE
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$site."/app/member/BK_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	$html_data=$curl->fetch_url("".$site."/app/member/BK_browse/body_var.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	preg_match_all("/parent.gamount=(.+?);/is",$html_data,$matches);
	$cou_num+=$matches[1][0];	
}
?>

      <li class="bk"><span class="ball"><a onclick="chg_button_bg('BK','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/BK_header.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=zh-cn&mtype=4','<?php echo BROWSER_IP?>/app/member/BK_<?php echo ($showtype=='future')?"future":"browse" ?>/index.php?rtype=all&uid=<?php echo $uid?>&langx=zh-cn&mtype=4',parent.BK_lid_type,'SI2');">籃球 <span class="ball_nf"><img src="/images/member/head_ball_nf.gif" class="nf_icon"></span> 美式足球 (<strong class="game_sum" id="BK_games"><?php echo $cou_num?></strong>)</a></span></li>
<?php

$cou_num=0;
//**********************网球*********************/
if ($showtype=="future"){
	//R
	$mysql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TU' and `M_Date` >'$m_date' and S_Show=1 and $mb_team<>'' order by m_start,mid";
	$result = mysqli_query($dbLink, $mysql);
	$cou_num=intval(mysqli_num_rows($result));		
	}
else{
	//R
	$mysql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and `M_Start` > now( ) AND `M_Date` ='$m_date' and S_Show=1 and $mb_team!='' order by m_start,mid";	
	$result = mysqli_query($dbLink, $mysql);
	$cou_num=intval(mysqli_num_rows($result));	
		//RE
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("".$site."/app/member/TN_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	$html_data=$curl->fetch_url("".$site."/app/member/TN_browse/body_var.php?rtype=re&uid=$suid&langx=$langx&mtype=3");	
	preg_match_all("/parent.gamount=(.+?);/is",$html_data,$matches);
	$cou_num+=$matches[1][0];		
	}
?>
      <li class="tn"><span class="ball"><a onclick="chg_button_bg('TN','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/TN_header.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=zh-cn&mtype=4','<?php echo BROWSER_IP?>/app/member/TN_<?php echo ($showtype=='future')?"future":"browse" ?>/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4',parent.TN_lid_type,'SI2');">網球 (<strong class="game_sum" id="TN_games"><?php echo $cou_num?></strong>)</a></span></li>
<?php
$cou_num=0;
//**********************排球*********************/
if ($showtype=="future"){
	//R
	$mysql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='VU' and `M_Date` >'$m_date' and S_Show=1 and $mb_team<>''  order by m_start,mid";	
	$result = mysqli_query($dbLink, $mysql);
	$cou_num=intval(mysqli_num_rows($result));	
}
else{

	//R
	$mysql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='VB' and `M_Start` > now( ) AND `M_Date` ='$m_date' and S_Show=1 and $mb_team!='' order by M_Start,$mb_team,MB_MID desc";	
	$result = mysqli_query($dbLink, $mysql);
	$cou_num=intval(mysqli_num_rows($result));	
		//RE
	$curl = new Curl_HTTP_Client();
    $curl->store_cookies("cookies.txt"); 
    $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    $curl->set_referrer("".$site."/app/member/VB_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	$html_data=$curl->fetch_url("".$site."/app/member/VB_browse/body_var.php?rtype=re&uid=$suid&langx=$langx&mtype=3");	
	preg_match_all("/parent.gamount=(.+?);/is",$html_data,$matches);
	$cou_num+=$matches[1][0];		
}
?>
      <li class="vb"><span class="ball"><a onclick="chg_button_bg('VB','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/VB_header.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=zh-cn&mtype=4','<?php echo BROWSER_IP?>/app/member/VB_<?php echo ($showtype=='future')?"future":"browse" ?>/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4',parent.VB_lid_type,'SI2');">排球 (<strong class="game_sum" id="VB_games"><?php echo $cou_num?></strong>)</a></span></li>
      <?php
$cou_num=0;
//**********************棒球*********************/
if ($showtype=="future"){
	//R
	$mysql="select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BE' and `M_Date` >'$m_date' and S_Show=1 and $mb_team!='' and Open=1 order by M_Start,MID";
	$result = mysqli_query($dbLink, $mysql);
	$cou_num=intval(mysqli_num_rows($result));	
}
else{
	//R
	$mysql="select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BS' and `M_Start` > now() AND `M_Date` ='$m_date' and S_Show=1 and $mb_team!='' and Open=1 order by M_Start,MID";
	$result = mysqli_query($dbLink, $mysql);
	$cou_num=intval(mysqli_num_rows($result));		
		//RE
	$curl = new Curl_HTTP_Client();
    $curl->store_cookies("cookies.txt"); 
    $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    $curl->set_referrer("".$site."/app/member/BS_browse/index.php?rtype=re&uid=$suid&langx=$langx&mtype=3");
	$html_data=$curl->fetch_url("".$site."/app/member/BS_browse/body_var.php?rtype=re&uid=$suid&langx=$langx&mtype=3");	
	preg_match_all("/parent.gamount=(.+?);/is",$html_data,$matches);
	$cou_num+=$matches[1][0];		
}
?>
	  <li class="bs"><span class="ball"><a onclick="chg_button_bg('BS','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/BS_header.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=zh-cn&mtype=4','<?php echo BROWSER_IP?>/app/member/BS_<?php echo ($showtype=='future')?"future":"browse" ?>/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4',parent.BS_lid_type,'SI2');">棒球 (<strong class="game_sum" id="BS_games"><?php echo $cou_num?></strong>)</a></span></li>

      <!--<li class="op"><span class="ball"><a href="javascript:chg_button_bg('OP','<?php /*echo $Mtype */?>');chg_index('<?php /*echo BROWSER_IP*/?>/app/member/SIX_header.php?uid=<?php /*echo $uid*/?>&showtype=<?php /*echo  $showtype */?>&langx=zh-cn&mtype=4','<?php /*echo BROWSER_IP*/?>/app/member/six/index.php?action=k_tm',parent.OP_lid_type,'SI2');">六合彩</a></span></li>
      <li class="op"><span class="ball"><a href="javascript:chg_button_bg('OP','<?php /*echo $Mtype */?>');chg_index('<?php /*echo BROWSER_IP*/?>/app/member/SSC_header.php?uid=<?php /*echo $uid*/?>&showtype=<?php /*echo  $showtype */?>&langx=zh-cn&mtype=4','<?php /*echo BROWSER_IP*/?>/app/member/ssc/templates/sGame_cq.php?uid=<?php /*echo $uid*/?>&g=g1',parent.OP_lid_type,'SI2');">时时彩</a></span></li>
      <li class="op"><span class="ball"><a href="javascript:chg_button_bg('OP','<?php /*echo $Mtype */?>');chg_index('<?php /*echo BROWSER_IP*/?>/app/member/K10_header.php?uid=<?php /*echo $uid*/?>&showtype=<?php /*echo  $showtype */?>&langx=zh-cn&mtype=4','<?php /*echo BROWSER_IP*/?>/app/member/ssc/templates/sGame_sm.php?uid=<?php /*echo $uid*/?>&g=g9',parent.OP_lid_type,'SI2');">快乐十分</a></span></li>-->

    </ul>
      </li>      
    </ul>
  </div>
  <div id="type">
    <ul>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame_sm.php?uid=<?php echo $uid?>&g=g9');">双面盘</a></li>	  
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame.php?uid=<?php echo $uid?>&g=g1');">第一球</a></li>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame.php?uid=<?php echo $uid?>&g=g2');">第二球</a></li>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame.php?uid=<?php echo $uid?>&g=g3');">第三球</a></li>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame.php?uid=<?php echo $uid?>&g=g4');">第四球</a></li>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame.php?uid=<?php echo $uid?>&g=g5');">第五球</a> </li>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame.php?uid=<?php echo $uid?>&g=g6');">第六球</a></li>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame.php?uid=<?php echo $uid?>&g=g7');">第七球</a></li>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame.php?uid=<?php echo $uid?>&g=g8');">第八球</a></li>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame_l.php?uid=<?php echo $uid?>&g=k1');">龙虎</a></li>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/sGame_k.php?uid=<?php echo $uid?>&g=k2');">连码</a> </li>
<li><a href="javascript:void(0);" onClick="chg_type('<?php echo BROWSER_IP?>/app/member/ssc/templates/result.php?id=1');">开奖记录</a></li>
    </ul>
  </div>
  
  
</div>
<!--input  id=downloadBTN type=button style="width:80px;visibility:'hidden'"  onclick="onclickDown()" value="下載"-->
    <!--主選單-->
    <div id="top_back" class="header_menu">

  </div>   


<div id="mem_box">
  <div id="mem_main"><span class="his"><a href="<?php echo BROWSER_IP?>/app/member/history/history_data.php?uid=<?php echo $uid?>&langx=zh-cn" target="body">帐户历史</a></span> | <span class="wag"><a href="<?php echo BROWSER_IP?>/app/member/today/today_wagers.php?uid=<?php echo $uid?>&langx=zh-cn" target="body">交易状况</a></span></div>
  <div id="credit_main"><span id="credit"></span><input type="button" class="re_credit" onClick="javascript:reloadCrditFunction();"></div>
</div>
<!--div class="info" id="informaction" onMouseOver="OnMouseOverEvent()">
  <table border="0" cellpadding="0" cellspacing="0" id="mose" onMouseOut="OnMouseOutEvent();">
    <tr>
      <td><a href="#"><font id="chg_pwd" onClick="Go_Chg_pass();" style="cursor:hand">&bull;&nbsp; 修改密碼</font></a></td>
      <td ><a href="<?php echo BROWSER_IP?>/tpl/member/zh-cn/roul_new/roul.html" target="_blank">&bull;&nbsp; 規則說明</a></td>
      <td><a href="/tpl/member/zh-cn/virus_site01.html" target="_blank">&bull;&nbsp; 防毒軟件設置說明</a></td>
    </tr>
    <tr>
      <td><a href="<?php echo BROWSER_IP?>/app/member/account/mem_data.php?uid=<?php echo $uid?>&langx=zh-cn" target="body">&bull;&nbsp; 會員資料</a></td>
      <td><a href="<?php echo BROWSER_IP?>/tpl/member/zh-cn/roul_mp.html" target="_blank">&bull;&nbsp; 手機交易</a></td>
      <td><a href="javascript://" onClick="javascript: window.open('/tpl/member/zh-cn/way.html','','menubar=no,status=yes,scrollbars=no,top=150,left=200,toolbar=no,width=540,height=510')">&bull;&nbsp; 盤口使用方法</a></td>
    </tr>
    <tr id="QA_row">
      <td title="籌備中"><a href="http://122.146.29.39" target="_blank" >&bull;&nbsp; 網路安全工具</a></td>
      <td><a href="http://web.asd10000.com/" target="_blank">&bull;&nbsp; 服務中心</a></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div-->

<!--幫助視窗-->
<div id="qaView" style="display:none;" class="qaView">
    <!--div class="leg_head" onMousedown="initializedragie('legView')"></div-->
    <div><iframe id="qaFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>
    <div class="qa_foot"></div>
</div>



<iframe id="reloadPHP" name="reloadPHP"  width="0" height="0"></iframe>
<iframe id="reloadPHP" name="reloadPHP1"  width="0" height="0"></iframe>

<script type="text/javascript" language="javascript">
setInterval("reloadCrditFunction()",3000);  var br_ip = <?php echo  '\''.BROWSER_IP.'\'' ?> ; var u_id = <?php echo '\''.$uid.'\'' ?> ; var u_lang = <?php echo '\''.$langx.'\'' ?> ; var u_type = <?php echo '\''.$showtype.'\'' ?> ; var u_name = <?php echo '\''.$username.'\'' ?> ;setHeaderAction(br_ip,u_id) ;

</script>
</body>
</html>
