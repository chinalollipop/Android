<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../include/address.mem.php");
require_once ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$gid=$_REQUEST['gid'];
$gtype=$_REQUEST['gtype'];
$mysql = "select * from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where mid='".$gid."' and Type='".$gtype."'";
$result = mysqli_query($dbCenterSlaveDbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$action=$_REQUEST['action'];

if ($action==1){
	$m_Date=$_REQUEST['m_date'];
	$m_Time=$_REQUEST['m_time'];
	
	$m_letb=$_REQUEST['m_letb'];
	$mb_letb_rate=$_REQUEST['mb_letb_rate'];	
	$tg_letb_rate=$_REQUEST['tg_letb_rate'];	
	
	$mb_dime=$_REQUEST['mb_dime'];
	$tg_dime=$_REQUEST['tg_dime'];	
	$mb_dime_rate=$_REQUEST['mb_dime_rate'];
	$tg_dime_rate=$_REQUEST['tg_dime_rate'];
	
	
	$m_letb_h=$_REQUEST['m_letb_h'];
	$mb_letb_rate_h=$_REQUEST['mb_letb_rate_h'];	
	$tg_letb_rate_h=$_REQUEST['tg_letb_rate_h'];
	
	$mb_dime_h=$_REQUEST['mb_dime_h'];
	$tg_dime_h=$_REQUEST['tg_dime_h'];	
	$mb_dime_rate_h=$_REQUEST['mb_dime_rate_h'];
	$tg_dime_rate_h=$_REQUEST['tg_dime_rate_h'];
	
		
	$hhmmstr=explode(":",$m_Time);
	$hh=$hhmmstr[0];
	$ap=substr($m_Time,strlen($m_Time)-1,1); 
	
	if ($ap=='p' and $hh<>12){
		$hh+=12;
	}
	$timestamp =$m_Date." ".$hh.":".substr($hhmmstr[1],0,strlen($hhmmstr[1])-1).":00";


	$mysql="update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set m_start='$timestamp',M_Date='$m_Date',M_Time='$m_Time',M_LetB='$m_letb',MB_LetB_Rate='$mb_letb_rate',TG_LetB_Rate='$tg_letb_rate',MB_Dime='$mb_dime',TG_Dime='$tg_dime',MB_Dime_Rate='$mb_dime_rate',TG_Dime_Rate='$tg_dime_rate',M_LetB_H='$m_letb_h',MB_LetB_Rate_H='$mb_letb_rate_h',TG_LetB_Rate_H='$tg_letb_rate_h',MB_Dime_H='$mb_dime_h',TG_Dime_H='$tg_dime_h',MB_Dime_Rate_H='$mb_dime_rate_h',TG_Dime_Rate_H='$tg_dime_rate_h' where mid='$gid'";
	//echo $mysql;
	//exit;
	mysqli_query($dbCenterMasterDbLink,$mysql);
	echo "<SCRIPT language='javascript'>self.location='./play_game.php?uid=$uid&langx=$langx';</script>";
}
?>
<html>
<head>
<title>reports_member</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    input.za_text_auto {width: 100%;}
    td p input:last-child {
        margin-top: 5px;
    }
</style>
</head>

<body >
<dl class="main-nav"><dt>变更赔率</dt><dd> 线上操盘－<font color="#CC0000">比赛时间赔率变更&nbsp;</font>&nbsp;&nbsp;&nbsp;日期:<?php echo $row["M_Date"]?>~<?php echo $row["M_Date"]?> -- 下注管道:网路下注 -- <a href="javascript:history.go( -1 );">回上一页</a> </dd></dl>
<div class="main-ui">
        <FORM NAME="LAYOUTFORM" onSubmit="return SubChk();" ACTION="retime.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gid=<?php echo $gid?>&action=1" METHOD=POST>

<table class="m_tab">
  <tr class="m_title" > 
      <td width="81"> 时间</td>
    <td width="163"> 主客队伍</td>
      <td width="90">全场盘口</td>
      <td width="90">全场赔率</td>
      <td width="90">全场大小</td>
      <td width="90">全场大小</td>
      <td width="90">半场盘口</td>
      <td width="90">半场赔率</td>
      <td width="90">半场大小</td>
      <td width="90">半场大小</td>
    </tr>
    <tr class="m_cen"> 
      <td width="81"><p align="center"><input name="m_date" type="text" size="8" class="za_text_auto" value="<?php echo $row["M_Date"]?>"><br><input name="m_time" type="text" size="3" class="za_text_auto" value="<?php echo $row["M_Time"]?>"></p></td>

      <td width="163" align="left"><?php echo $row["MB_Team"]?><br><?php echo $row["TG_Team"]?></td>
      <td width="90"><p align="center"><input name="m_letb" type="text" size="3" class="za_text_auto" value="<?php echo $row['M_LetB']?>"></p></td>
      <td width="90"><p align="center"><input name="mb_letb_rate" type="text" size="3" class="za_text_auto" value="<?php echo $row["MB_LetB_Rate"]?>"><br><input name="tg_letb_rate" type="text" size="3" class="za_text_auto" value="<?php echo $row["TG_LetB_Rate"]?>"></p></td>
      <td width="90"><p align="center"><input name="mb_dime" type="text" size="4" class="za_text_auto" value="<?php echo $row['MB_Dime'];?>"><br><input name="tg_dime" type="text" size="4" class="za_text_auto" value="<?php echo $row["TG_Dime"]?>"></p></td>
      <td width="90"><p align="center"><input name="mb_dime_rate" type="text" size="3" class="za_text_auto" value="<?php echo $row['MB_Dime_Rate'];?>"><br><input name="tg_dime_rate" type="text" size="3" class="za_text_auto" value="<?php echo $row['TG_Dime_Rate'];?>"></p></td>
      <td width="90"><p align="center"><input name="m_letb_h" type="text" size="3" class="za_text_auto" value="<?php echo $row['M_LetB_H'];?>"></p></td>
      <td width="90"><p align="center"><input name="mb_letb_rate_h" type="text" size="3" class="za_text_auto" value="<?php echo $row["MB_LetB_Rate_H"]?>"><br><input name="tg_letb_rate_h" type="text" size="3" class="za_text_auto" value="<?php echo $row["TG_LetB_Rate_H"]?>"></p></td>
      <td width="90"><p align="center"><input name="mb_dime_h" type="text" size="4" class="za_text_auto" value="<?php echo $row['MB_Dime_H']?>"><br><input name="tg_dime_h" type="text" size="4" class="za_text_auto" value="<?php echo $row["TG_Dime_H"]?>"></p></td>
      <td width="90"><p align="center"><input name="mb_dime_rate_h" type="text" size="3" class="za_text_auto" value="<?php echo $row['MB_Dime_Rate_H'];?>"><br><input name="tg_dime_rate_h" type="text" size="3" class="za_text_auto" value="<?php echo $row['TG_Dime_Rate_H']?>"></p></td>
    </tr>

    <tr class="m_cen">
        <td colspan="10">
            <input type="submit" value=" 提 交 " name="B1" class="za_button">
            <input type="reset" value=" 重 置 " name="B2" class="za_button">
        </td>
    </tr>
  </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="15" width="436">
    <p align="center">说明：比赛时间为均为<font color="#CC0000">美东</font>时间！(a表示上午，p表示下午)</p>
</td>
</tr>
</table>
 
</form>
</div>
    <script language="JavaScript">
    function SubChk() {
        if (document.all.m_date.value==''){
            document.all.m_date.focus();
            alert("请输入比赛日期!!");
            return false;
        }
        if (document.all.m_time.value==''){
            document.all.m_time.focus();
            alert("请输入比赛时间!!");
            return false;
        }
        if(!confirm("日期更改为："+document.all.m_date.value+"\n时间更改为："+document.all.m_time.value+"\n\n请确定输入是否正确?")){return false;}
    }
</script>

</body>
</html>
