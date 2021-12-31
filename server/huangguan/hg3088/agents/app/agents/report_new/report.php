<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../../agents/include/address.mem.php");
require ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
//print_r($_REQUEST);

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid = $_SESSION['Oid'];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lever"];
require ("../../agents/include/traditional.$langx.inc.php");

if($_SESSION['Level'] == 'M') {
	$web=DBPREFIX.'web_system_data';
}else{
    $web=DBPREFIX.'web_agents_data';
}
//$sql = "select ID,UserName,SubUser,SubName from $web  where Oid='$uid' and UserName='$loginname'";
//$result = mysqli_query($dbLink,$sql);
//$row = mysqli_fetch_assoc($result);
//$cou=mysqli_num_rows($result);
//$id=$row['ID'];
//if ($row['SubUser']==0){
//	$username=$row['UserName'];
//}else{
//	$username=$row['SubName'];
//}
switch ($lv){
case 'M':
    $lever='A';
	break;
case 'A':
    $lever='B';
	break;
case 'B':
    $lever='C';
	break;
case 'C':
    $lever='D';
	break;
case 'D':
    $lever='MEM';
	break;
}
//$period=pdate();
$date_s=$_REQUEST['date_start'];
$date_e=$_REQUEST['date_end'];
if ($date_s==''){
	$date_s=date('Y-m-d 00:00:00');
	$date_e=date('Y-m-d 23:59:59');
}
if (date(w,time())==0){
    $num=6;
}else{
    $num=date(w,time()-60*60*24);
}
?>
<html>
<head>
<title>reports</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
.m_title_re {text-align: right;}
.m_cal {  padding-top: 2px}
.show_ok {background-color: gold; color: blue}
.show_no {background-color: yellow; color: red}
.m_title_ce {background-color: #669999; text-align: center; color: #FFFFFF}
.small {
	font-size: 11px;
	background-color: #7DD5D2;
	text-align: center;
}
.small_top {
	font-size: 11px;
	color: #FFFFFF;
	background-color: #669999;
	text-align: center;
}

</style>
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<body >
<dl class="main-nav"><dt><?php echo $Mnu_Report.$Rep_Manager?></dt><dd></dd></dl>
<div class="main-ui">
<FORM id="myFORM" action="report_top.php" method="GET" name="FrmData" onSubmit="sel_type();">
  <input type=HIDDEN name="uid" value="<?php echo $uid?>">
  <input type=HIDDEN name="langx" value="<?php echo $langx?>">
  <input type=HIDDEN name="lever" value="<?php echo $lever?>">
  <input type=HIDDEN name="first" value="Y">    

<table width="100%">
  <tr>
	<td>
  <table  border="0" cellspacing="1" cellpadding="0" class="m_tab_ed">
    <tr class="m_bc"> 
      <td width="100" class="m_title_re"><?php echo $Rep_Report_Period?></td>
      <td>
          <input type="text" name="date_start" id="date_start" value="<?php echo $date_s?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD 00:00:00'})" size=15 maxlength=11 class="za_text" readonly>
           至
           <input type="text" name="date_end" id="date_end" value="<?php echo $date_e?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD 23:59:59'})" size=15 maxlength=11 class="za_text" readonly>
          <!-- l 昨日  t 今日  n 明日  w 本星期  lw 上星期 m 本月 lm 上个月 -->
            <input type="button" class="za_button" onClick="chg_date('l')" value="<?php echo $Rep_Yestoday?>">
            <input type="button" class="za_button" onClick="chg_date('t')" value="<?php echo $Rep_Today?>">
            <input type="button" class="za_button" onClick="chg_date('n')" value="<?php echo $Rep_Tommrow?>">
            <input type="button" class="za_button" onClick="chg_date('w')" value="<?php echo $Rep_One_week?>">
            <input type="button" class="za_button" onClick="chg_date('lw')" value="<?php echo $Rep_Last_week?>">
            <input type="button" class="za_button" onClick="chg_date('m')" value="本月">
            <input type="button" class="za_button" onClick="chg_date('lm')" value="上个月">
      </td>
    </tr>

      <tr class="m_bc"> <!-- 游戏分类-->
          <td class="m_title_re">游戏分类</td>
          <td >
              <select name="gtype" class="za_select" >
                  <option value=""><?php echo $Rep_All?></OPTION>
                  <option value="FT" ><?php echo $Rep_Soccer?></OPTION>
                  <option value="BK" ><?php echo $Rep_Bask?></OPTION>
                  <option value="TN" ><?php echo $Rep_Tennis?></OPTION>
                  <option value="VB" ><?php echo $Rep_Voll?></OPTION>
                  <option value="BS" ><?php echo $Rep_Base?></OPTION>
                  <option value="OP" ><?php echo $Rep_Other?></OPTION>
                  <option value="FU" ><?php echo $Rep_Stock?></OPTION>
                  <option value="FS" ><?php echo $Rep_Outright?></OPTION>
                  <option value="SIX" ><?php echo $Rep_MarkSix?></OPTION>
                  <option value="AG" >AG视讯</OPTION>
                  <option value="CP" >彩票</OPTION>
                  <option value="KY" >开元棋牌</OPTION>
                  <option value="LYQP" >开元棋牌</OPTION>
                  <!--<option value="HGQP" >皇冠棋牌</OPTION>-->
                  <option value="VGQP" >VG棋牌</OPTION>
                  <option value="KLQP" >VG棋牌</OPTION>
              </select>
          </td>
      </tr>
    <tr class="m_bc"> 
      <td class="m_title_re"><?php echo $Rep_Kind?></td>
      <td > 
        <select name="report_kind" id="report_kind" class="za_select">
          <option value="A" selected><?php echo $Rep_Kind_a?></option>
          <option value="C" ><?php echo $Rep_Kind_c?></option>
          <option value="D" ><?php echo $Rep_Kind_d?></option>
          <option value="E" ><?php echo $Rep_Kind_e?></option>
        </select>
      </td>
    </tr>
    <tr class="m_bc"> 
      <td class="m_title_re"><?php echo $Rep_Pay_Type?></td>
      <td > 
        <select name="pay_type" class="za_select">
          <option value="" SELECTED><?php echo $Rep_All?></option>
          <option value="0"><?php echo $Rep_Credit?></option>
          <option value="1"><?php echo $Rep_Cash?></option>
        </select>
      </td>
    </tr>
    <tr class="m_bc"> 
      <td class="m_title_re"><?php echo $Rep_Wtype?></td>
      <td > 
        <select name="type" class="za_select">
          <option value="" SELECTED><?php echo $Rep_All?></option>
		  <option value="M"><?php echo $Rep_Wtype_m?></option>
		  <option value="R"><?php echo $Rep_Wtype_r?></option>
		  <option value="OU"><?php echo $Rep_Wtype_ou?></option>
		  <option value="EO"><?php echo $Rep_Wtype_eo?></option>
		  <option value="VM"><?php echo $Rep_Wtype_vm?></option>
		  <option value="VR"><?php echo $Rep_Wtype_vr?></option>
		  <option value="VOU"><?php echo $Rep_Wtype_vou?></option>
		  <option value="VEO"><?php echo $Rep_Wtype_veo?></option>
		  <option value="UR"><?php echo $Rep_Wtype_ur?></option>
		  <option value="UOU"><?php echo $Rep_Wtype_uou?></option>
		  <option value="UEO"><?php echo $Rep_Wtype_ueo?></option>
		  <option value="QR"><?php echo $Rep_Wtype_qr?></option>
		  <option value="QOU"><?php echo $Rep_Wtype_qou?></option>
		  <option value="QEO"><?php echo $Rep_Wtype_qeo?></option>
		  <option value="RM"><?php echo $Rep_Wtype_rm?></option>
		  <option value="RB"><?php echo $Rep_Wtype_rb?></option>
		  <option value="ROU"><?php echo $Rep_Wtype_rou?></option>
		  <option value="VRM"><?php echo $Rep_Wtype_vrm?></option>
		  <option value="VRB"><?php echo $Rep_Wtype_vrb?></option>
		  <option value="VROU"><?php echo $Rep_Wtype_vrou?></option>
		  <option value="URB"><?php echo $Rep_Wtype_urb?></option>
		  <option value="UROU"><?php echo $Rep_Wtype_urou?></option>
		  <option value="PD"><?php echo $Rep_Wtype_pd?></option>
		  <option value="VPD"><?php echo $Rep_Wtype_vpd?></option>
		  <option value="T"><?php echo $Rep_Wtype_t?></option>
		  <option value="F"><?php echo $Rep_Wtype_f?></option>
		  <option value="PC"><?php echo $Rep_Wtype_pc?></option>
		  <option value="CS"><?php echo $Rep_Wtype_cs?></option>
        </select>
      </td>
    </tr>
    <tr class="m_bc"> 
      <td class="m_title_re"><?php echo $Rep_Bet_State?></td>
      <td>
	   <select name="result_type" class="za_select">
	    <option value=""><?php echo $Rel_All?></option>
        <option value="Y"><?php echo $Rep_Results?></option>
        <option value="N"><?php echo $Rep_No_Results?></option>
       </select>
      </td>
    </tr>
      <tr class="m_bc">
          <td class="m_title_re"> </td>
          <td>
              <input type="submit" name="submit" value="<?php echo $Rep_Query?>" class="za_button sub_za_button">
              <input type="submit" name="cancel" value="<?php echo $Rep_Cancel?>" onClick="javascript:history.go(-1)" class="za_button sub_za_button">
          </td>
      </tr>
<?php
	$mdate_t=date('Y-m-d');
	$mdate_y=date('Y-m-d',time()-24*60*60);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and M_Date='$mdate_t' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and M_Date='$mdate_t'";
	$result = mysqli_query($dbLink,$mysql);
	$cou1=mysqli_num_rows($result);
	if ($cou1==0){
		$ft_caption=$Rep_readme2;//今日没有比赛
	}else if ($cou1-$cou==0){			
		$ft_caption=$Rep_readme1;//今日输入完毕
	}else{			
		$ft_caption=str_replace('{}',$cou1-$cou,$Rep_readme0);//今日尚有多少场未输入完毕
	}	
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and M_Date='$mdate_y' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou2=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and M_Date='$mdate_y'";
	$result = mysqli_query($dbLink,$mysql);
	$cou3=mysqli_num_rows($result);
	if ($cou3==0){		
		$ft_caption1=$Rep_readme2;//昨日没有比赛
	}else if ($cou3-$cou2==0){		
		$ft_caption1=$Rep_readme1;//昨日输入完毕
	}else{	
		$ft_caption1=str_replace('{}',$cou3-$cou2,$Rep_readme0);//昨日尚有多少场未输入完毕
	}
?>        
    <tr align="center">
    <td height="30" colspan="3" >
      <table  border="0" cellpadding="0" cellspacing="1">
        <tr>
          <td ><div align="left" class="sai_1"><font color='red'><?php echo date('Y-m-d')?>&nbsp;<?php echo $Rep_Soccer?><?php echo $ft_caption?></font></div></td>
          <td ><div align="left" class="sai_2"><?php echo date('Y-m-d',time()-24*60*60)?>&nbsp;<?php echo $Rep_Soccer?><?php echo $ft_caption1?></div></td>
        </tr>
      </table>
    </td>
    </tr>	    
<?php
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and M_Date='$mdate_t' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou=mysqli_num_rows($result);

	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and M_Date='$mdate_t'";
	$result = mysqli_query($dbLink,$mysql);
	$cou1=mysqli_num_rows($result);
	if ($cou1==0){
		$bk_caption=$Rep_readme2;//今日没有比赛
	}else if ($cou1-$cou==0){			
		$bk_caption=$Rep_readme1;//今日输入完毕
	}else{			
		$bk_caption=str_replace('{}',$cou1-$cou,$Rep_readme0);//今日尚有多少场未输入完毕
	}	
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and M_Date='$mdate_y' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou2=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and M_Date='$mdate_y'";
	$result = mysqli_query($dbLink,$mysql);
	$cou3=mysqli_num_rows($result);
	if ($cou3==0){		
		$bk_caption1=$Rep_readme2;//昨日没有比赛
	}else if ($cou3-$cou2==0){		
		$bk_caption1=$Rep_readme1;//昨日输入完毕
	}else{	
		$bk_caption1=str_replace('{}',$cou3-$cou2,$Rep_readme0);//昨日尚有多少场未输入完毕
	}
?>          
    <tr align="center" bgcolor="#FFFFFF">
    <td height="30" colspan="3" >
	  <table  border="0" cellpadding="0" cellspacing="1">
        <tr>
          <td ><div align="left"><font color='red'><?php echo date('Y-m-d')?>&nbsp;<?php echo $Rep_Bask?><?php echo $bk_caption?></font></div></td>
          <td ><div align="left"><?php echo date('Y-m-d',time()-24*60*60)?>&nbsp;<?php echo $Rep_Bask?><?php echo $bk_caption1?></div></td>
        </tr>
      </table>
	</td>
	</tr>
<?php
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BS' and M_Date='$mdate_t' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BS' and M_Date='$mdate_t'";
	$result = mysqli_query($dbLink,$mysql);
	$cou1=mysqli_num_rows($result);
	if ($cou1==0){
		$be_caption=$Rep_readme2;//今日没有比赛
	}else if ($cou1-$cou==0){			
		$be_caption=$Rep_readme1;//今日输入完毕
	}else{			
		$be_caption=str_replace('{}',$cou1-$cou,$Rep_readme0);//今日尚有多少场未输入完毕
	}	
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BS' and M_Date='$mdate_y' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou2=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BS' and M_Date='$mdate_y'";
	$result = mysqli_query($dbLink,$mysql);
	$cou3=mysqli_num_rows($result);
	if ($cou3==0){		
		$be_caption1=$Rep_readme2;//昨日没有比赛
	}else if ($cou3-$cou2==0){		
		$be_caption1=$Rep_readme1;//昨日输入完毕
	}else{	
		$be_caption1=str_replace('{}',$cou3-$cou2,$Rep_readme0);//昨日尚有多少场未输入完毕
	}
?>
    <tr align="center" bgcolor="#FFFFFF">
    <td height="30" colspan="3" >
      <table  border="0" cellpadding="0" cellspacing="1">
        <tr>
          <td ><div align="left" class="sai_bs"><font color='red'><?php echo date('Y-m-d')?>&nbsp;<?php echo $Rep_Base?><?php echo $be_caption?></font></div></td>
          <td ><div align="left" class="sai_bs"><?php echo date('Y-m-d',time()-24*60*60)?>&nbsp;<?php echo $Rep_Base?><?php echo $be_caption1?></div></td>
        </tr>
      </table>
	</td>
    </tr>
<?php
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and M_Date='$mdate_t' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou=mysqli_num_rows($result);

	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and M_Date='$mdate_t'";
	$result = mysqli_query($dbLink,$mysql);
	$cou1=mysqli_num_rows($result);
	if ($cou1==0){
		$tn_caption=$Rep_readme2;//今日没有比赛
	}else if ($cou1-$cou==0){			
		$tn_caption=$Rep_readme1;//今日输入完毕
	}else{			
		$tn_caption=str_replace('{}',$cou1-$cou,$Rep_readme0);//今日尚有多少场未输入完毕
	}	
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and M_Date='$mdate_y' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou2=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='TN' and M_Date='$mdate_y'";
	$result = mysqli_query($dbLink,$mysql);
	$cou3=mysqli_num_rows($result);
	if ($cou3==0){		
		$tn_caption1=$Rep_readme2;//昨日没有比赛
	}else if ($cou3-$cou2==0){		
		$tn_caption1=$Rep_readme1;//昨日输入完毕
	}else{	
		$tn_caption1=str_replace('{}',$cou3-$cou2,$Rep_readme0);//昨日尚有多少场未输入完毕
	}
?>
    <tr align="center" bgcolor="#FFFFFF">
    <td height="30" colspan="3" >
      <table  border="0" cellpadding="0" cellspacing="1">
        <tr>
          <td ><div align="left"><font color='red'><?php echo date('Y-m-d')?>&nbsp;<?php echo $Rep_Tennis?><?php echo $tn_caption?></font></div></td>
          <td ><div align="left"><?php echo date('Y-m-d',time()-24*60*60)?>&nbsp;<?php echo $Rep_Tennis?><?php echo $tn_caption1?></div></td>
        </tr>
      </table>	
	</td>
    </tr>
<?php
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='VB' and M_Date='$mdate_t' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='VB' and M_Date='$mdate_t'";
	$result = mysqli_query($dbLink,$mysql);
	$cou1=mysqli_num_rows($result);
	if ($cou1==0){
		$vb_caption=$Rep_readme2;//今日没有比赛
	}else if ($cou1-$cou==0){			
		$vb_caption=$Rep_readme1;//今日输入完毕
	}else{			
		$vb_caption=str_replace('{}',$cou1-$cou,$Rep_readme0);//今日尚有多少场未输入完毕
	}	
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='VB' and M_Date='$mdate_y' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou2=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='VB' and M_Date='$mdate_y'";
	$result = mysqli_query($dbLink,$mysql);
	$cou3=mysqli_num_rows($result);
	if ($cou3==0){		
		$vb_caption1=$Rep_readme2;//昨日没有比赛
	}else if ($cou3-$cou2==0){		
		$vb_caption1=$Rep_readme1;//昨日输入完毕
	}else{	
		$vb_caption1=str_replace('{}',$cou3-$cou2,$Rep_readme0);//昨日尚有多少场未输入完毕
	}
?>
    <tr align="center" >
    <td height="30" colspan="3" >
      <table  border="0" cellpadding="0" cellspacing="1">
        <tr>
          <td ><div align="left"><font color='red'><?php echo date('Y-m-d')?>&nbsp;<?php echo $Rep_Voll?><?php echo $vb_caption?></font></div></td>
          <td ><div align="left"><?php echo date('Y-m-d',time()-24*60*60)?>&nbsp;<?php echo $Rep_Voll?><?php echo $vb_caption1?></div></td>
        </tr>
      </table>	
	</td>
    </tr>
<?php
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='OP' and M_Date='$mdate_t' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='OP' and M_Date='$mdate_t'";
	$result = mysqli_query($dbLink,$mysql);
	$cou1=mysqli_num_rows($result);
	if ($cou1==0){
		$vb_caption=$Rep_readme2;//今日没有比赛
	}else if ($cou1-$cou==0){			
		$vb_caption=$Rep_readme1;//今日输入完毕
	}else{			
		$vb_caption=str_replace('{}',$cou1-$cou,$Rep_readme0);//今日尚有多少场未输入完毕
	}	
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='OP' and M_Date='$mdate_y' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou2=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='OP' and M_Date='$mdate_y'";
	$result = mysqli_query($dbLink,$mysql);
	$cou3=mysqli_num_rows($result);
	if ($cou3==0){		
		$vb_caption1=$Rep_readme2;//昨日没有比赛
	}else if ($cou3-$cou2==0){		
		$vb_caption1=$Rep_readme1;//昨日输入完毕
	}else{	
		$vb_caption1=str_replace('{}',$cou3-$cou2,$Rep_readme0);//昨日尚有多少场未输入完毕
	}
?>
    <tr align="center" >
    <td height="30" colspan="3" >
      <table  border="0" cellpadding="0" cellspacing="1">
        <tr>
          <td ><div align="left"><font color='red'><?php echo date('Y-m-d')?>&nbsp;<?php echo $Rep_Other?><?php echo $vb_caption?></font></div></td>
          <td ><div align="left"><?php echo date('Y-m-d',time()-24*60*60)?>&nbsp;<?php echo $Rep_Other?><?php echo $vb_caption1?></div></td>
        </tr>
      </table>	
	</td>
    </tr>
<?php
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='SK' and M_Date='$mdate_t' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='SK' and M_Date='$mdate_t'";
	$result = mysqli_query($dbLink,$mysql);
	$cou1=mysqli_num_rows($result);
	if ($cou1==0){
		$fs_caption=$Rep_readme2;//今日没有比赛
	}else if ($cou1-$cou==0){			
		$fs_caption=$Rep_readme1;//今日输入完毕
	}else{			
		$fs_caption=str_replace('{}',$cou1-$cou,$Rep_readme0);//今日尚有多少场未输入完毕
	}	
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='SK' and M_Date='$mdate_y' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou2=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='SK' and M_Date='$mdate_y'";
	$result = mysqli_query($dbLink,$mysql);
	$cou3=mysqli_num_rows($result);
	if ($cou3==0){		
		$fs_caption1=$Rep_readme2;//昨日没有比赛
	}else if ($cou3-$cou2==0){		
		$fs_caption1=$Rep_readme1;//昨日输入完毕
	}else{	
		$fs_caption1=str_replace('{}',$cou3-$cou2,$Rep_readme0);//昨日尚有多少场未输入完毕
	}
?>
    <tr align="center" >
    <td height="30" colspan="3" >
      <table  border="0" cellpadding="0" cellspacing="1">
        <tr>
          <td ><div align="left"><font color='red'><?php echo date('Y-m-d')?>&nbsp;<?php echo $Rep_Stock?><?php echo $fs_caption?></font></div></td>
          <td ><div align="left"><?php echo date('Y-m-d',time()-24*60*60)?>&nbsp;<?php echo $Rep_Stock?><?php echo $fs_caption1?></div></td>
        </tr>
      </table>	
	</td>
    </tr>
<?php
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FS' and M_Date='$mdate_t' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FS' and M_Date='$mdate_t'";
	$result = mysqli_query($dbLink,$mysql);
	$cou1=mysqli_num_rows($result);
	if ($cou1==0){
		$fs_caption=$Rep_readme2;//今日没有比赛
	}else if ($cou1-$cou==0){			
		$fs_caption=$Rep_readme1;//今日输入完毕
	}else{			
		$fs_caption=str_replace('{}',$cou1-$cou,$Rep_readme0);//今日尚有多少场未输入完毕
	}	
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FS' and M_Date='$mdate_y' and MB_Inball!=''";
	$result = mysqli_query($dbLink,$mysql);
	$cou2=mysqli_num_rows($result);
	$mysql="select Type from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FS' and M_Date='$mdate_y'";
	$result = mysqli_query($dbLink,$mysql);
	$cou3=mysqli_num_rows($result);
	if ($cou3==0){		
		$fs_caption1=$Rep_readme2;//昨日没有比赛
	}else if ($cou3-$cou2==0){		
		$fs_caption1=$Rep_readme1;//昨日输入完毕
	}else{	
		$fs_caption1=str_replace('{}',$cou3-$cou2,$Rep_readme0);//昨日尚有多少场未输入完毕
	}
?>
    <tr align="center" >
    <td height="30" colspan="3" >
      <table  border="0" cellpadding="0" cellspacing="1">
        <tr>
          <td ><div align="left"><font color='red'><?php echo date('Y-m-d')?>&nbsp;<?php echo $Rep_Guan?><?php echo $fs_caption?></font></div></td>
          <td ><div align="left"><?php echo date('Y-m-d',time()-24*60*60)?>&nbsp;<?php echo $Rep_Guan?><?php echo $fs_caption1?></div></td>
        </tr>
      </table>	
	</td>
    </tr>   	
  </table>
	  </td>
      <!-- 2018 修改注掉 -->
			<!--<td valign="top">
				<table width=246 border=0 cellpadding=0 cellspacing=1 class="m_tab_ed">
				  <tr>
				    <td height="9" colspan=2 class="small_top" align="center"><div >2010<?php /*echo $Rep_Clear_Period*/?></div></td>
				  </tr>
				  <tr>
				    <td width="70" height="10" class="small"><?php /*echo $Rep_Article*/?>1<?php /*echo $Rep_Period*/?></td>
				    <td width="174" class="m_cen_top"  id="2010_1">2009/12/28 ~ 2010/01/24</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>2<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_2">2010/01/25 ~ 2010/02/21</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>3<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_3">2010/02/22 ~ 2010/03/21</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>4<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_4">2010/03/22 ~ 2010/04/18</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>5<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_5">2010/04/19 ~ 2010/05/16</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>6<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_6">2010/05/17 ~ 2010/06/13</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>7<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_7">2010/06/14 ~ 2010/07/11</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>8<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_8">2010/07/12 ~ 2010/08/08</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>9<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_9">2010/08/09 ~ 2010/09/05</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>10<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_10">2010/09/06 ~ 2010/10/03</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>11<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_11">2010/10/04 ~ 2010/10/31</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>12<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2010_12">2010/11/01 ~ 2010/11/28</td>
				  </tr>
				  <tr>
				    <td height="10" class="small"><?php /*echo $Rep_Article*/?>13<?php /*echo $Rep_Period*/?></td>
				    <td class="m_cen_top" id="2011_1">2010/11/29 ~ 2010/12/26</td>
				  </tr>
				</table>
	  </td>-->
	</tr>
  </table>
</form>

<!--只有BB,KK,BB2要放-->
<!--FLASH廣告開的視窗-->
<!--div id='expandOpen' style='position:absolute;right: 0px; top:0px;width:100%;'>
	<span style="float:right;width:115px;">
		<TABLE cellSpacing=0 cellPadding=0 border=0  style="margin:0px;padding=0px" align="center" width="115">
		<TR><TD align="right" background="/images/agents/top/bady.gif">
	  
			<A onClick="ExpandIO('small');return false;"  href="#">
			<IMG  src="/images/agents/top/S.gif" border=0 align="top">
			</A>
			<A onClick="ExpandIO('close');return false;"  href="#">
			<IMG  src="/images/agents/top/C.gif" border=0 align="top">
			</A>	
		</TD>
		</TR>
		<TR><TD>
			<a href=# onClick="window_open();" ><img border="0" src="/images/agents/top/banner01.gif"></a>
		</TD></TR>
		</TABLE>
	</span>
</div-->
<!--FLASH廣告關的視窗-->
<!--div id='expandClose' style='position:absolute;visibility:hidden;right: 0px; top:0px;width:100%;'>
	<span style="float:right;width:115px;">
		<TABLE cellSpacing=0 cellPadding=0 border=0  style="margin:0px;padding=0px" align="center" width="115">
		<TR><TD align="right" background="/images/agents/top/bady.gif">
	  
			<A onClick="ExpandIO('open');return false;"  href="#">
			<IMG  src="/images/agents/top/B.gif" border=0 align="top">
			</A>
			<A onClick="ExpandIO('close');return false;"  href="#">
			<IMG  src="/images/agents/top/C.gif" border=0 align="top">
			</A>
		</TD>
		</TR>
		</TABLE>
	</span>
</div-->

</div>

<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script type="text/javascript">
    var date_num='<?php echo $period[0]?>';
    var langx='<?php echo $langx?>';
    function sel_type(){
        kind_obj = document.getElementById("report_kind");
        form_obj = document.getElementById("myFORM");
        var date_start = document.getElementById("date_start").value;
        var date_end = document.getElementById("date_end").value;
        var date_ba ="2018-01-01";
        if(date_end >= date_ba){
            if(date_start < date_ba ){
                alert("日期区间不可跨7~8期");
                return false;
            }
        }
        if(kind_obj.value == 'C')
            form_obj.action = "report_class.php";
        else if(kind_obj.value == 'D')
            form_obj.action = "report_top.php?cancel=1";
        else if(kind_obj.value == 'E')
            form_obj.action = "report_top.php?confirmed=-17";
        else if(kind_obj.value == 'A')
            form_obj.action = "report_top.php";
    }
    function onChangeOption(){
        var obj_select=document.getElementsByTagName('select');
        var reload_str='report.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&';
        for(var i=0;i<obj_select.length;i++){
            reload_str+=obj_select[i].name+'='+obj_select[i].value+'&';
        }
        var reloadStringObj=new String(reload_str);
        reload_str=reloadStringObj.substr(0,reloadStringObj.length-1);
        location.replace(reload_str);
    }
    function chg_date(range,num1,num2){
        //  l 昨日  t 今日  n 明日  w 本星期  lw 上星期 m 本月 lm 上个月
        var date_start;
        var date_end;
        switch (range){
            case 'l': // 昨日
                date_start = '<?php echo date('Y-m-d H:i:s',strtotime($date_s)-86400);?>';
                date_end = '<?php echo date('Y-m-d H:i:s',strtotime($date_e)-86400);?>';
                break;
            case 't': // 今日
                date_start = '<?php echo $date_s;?>';
                date_end = '<?php echo $date_e;?>';
                break;
            case 'n': // 明日
                date_start = '<?php echo date('Y-m-d H:i:s',strtotime($date_s)+86400);?>';
                date_end = '<?php echo date('Y-m-d H:i:s',strtotime($date_e)+86400);?>';
                break;
            case 'w': // 本周
                date_start = '<?php echo date('Y-m-d 00:00:00', strtotime("this week"));?>';
                date_end = '<?php echo date('Y-m-d 23:59:59', strtotime("last day next week +1 day"));?>';
                break;
            case 'lw': // 上周
                date_start = '<?php echo date('Y-m-d 00:00:00', strtotime("last week Monday"));?>';
                date_end = '<?php echo date('Y-m-d 23:59:59', strtotime("last week Sunday"));?>';
                break;
            case 'm': // 本月
                date_start = '<?php echo date('Y-m-d 00:00:00', strtotime(date('Y-m', time()) . '-01 00:00:00'));?>';
                date_end = '<?php echo date('Y-m-d 23:59:59', strtotime(date('Y-m', time()) . '-' . date('t', time()) . ' 00:00:00'));?>';
                break;
            case 'lm': // 上个月
                date_start = '<?php echo date('Y-m-d 00:00:00', strtotime('-1 month', strtotime(date('Y-m', time()) . '-01 00:00:00')));?>';
                date_end = '<?php echo date('Y-m-d 23:59:59', strtotime(date('Y-m', time()) . '-01 00:00:00') - 86400);?>';
                break;
        }
        FrmData.date_start.value = date_start;
        FrmData.date_end.value = date_end;
    }
    // 个位补 0
    function padZero(num) {
        return ((num <= 9) ? ("0" + num) : num);
    }


    /* 顯示隱藏FLASH廣告的js*/
    function ExpandIO(flg,DivName){
        if(!(document.all || document.getElementById)){return false;}
        if(flg == null){var flg = "close";}
        if(flg == "small"){
            document.getElementById("expandOpen").style.visibility = "hidden";
            document.getElementById("expandClose").style.visibility = "visible";
        }else if (flg=="open"){
            document.getElementById("expandOpen").style.right = "0px";
            document.getElementById("expandOpen").style.visibility = "visible";
            document.getElementById("expandClose").style.visibility = "hidden";
        }else{
            document.getElementById("expandOpen").style.visibility = "hidden";
            document.getElementById("expandClose").style.visibility = "hidden";
        }
    }
    <!-- 新開廣告視窗 -->
    function window_open() {
        win = window.open('https://rp.kc080.com/tpl/images/commercial/banner.html','','height=430, width=600, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
    }

</script>

</body>
</html>

<?php
$loginfo='查看报表管理';
innsertSystemLog($loginname,$lv,$loginfo);

?>
