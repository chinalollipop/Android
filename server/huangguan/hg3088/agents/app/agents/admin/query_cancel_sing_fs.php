<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../include/address.mem.php");
require_once ("../include/config.inc.php");

$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$id=$_REQUEST['id'];
$page=$_REQUEST['page'];
$sort=$_REQUEST['sort'];
$ball=$_REQUEST['ball'];
$type=$_REQUEST['type'];
$username=$_REQUEST['username'];
$date_start=$_REQUEST['date_start'];
$date_end=$_REQUEST['date_end'];

$returnUrl="query.php?uid={$_SESSION['Oid']}&username={$username}&date_start={$date_start}&date_end={$date_end}&page={$page}&sort={$sort}&ball={$ball}&type={$type}";

require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    	echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    	exit;
}

	$sql = "SELECT ID,Mid,M_Result,checked,Cancel,Confirmed FROM `".DBPREFIX."web_report_data` WHERE ID=".$id;
	$result = mysqli_query($dbLink, $sql);
	$row=mysqli_fetch_assoc($result);

if($_REQUEST['editKey']=='cancelCUR'){
	$id = $_REQUEST['id'];
	$chaunGuan = $_REQUEST['chaunguan'];
	$rowMid=explode(',',$row['Mid']);
	if(count($chaunGuan)==count($rowMid)){
		echo "<script>alert('不能全部取消');location.href='".$returnUrl."';</script>";
		exit;	
	}
	$chaunGuanStr = implode(',',$chaunGuan);
	
	$u_sql = "update ".DBPREFIX."web_report_data set Confirmed='".$chaunGuanStr."',updateTime='".date('Y-m-d H:i:s',time())."' where ID=".$id;
	$updateRes = mysqli_query($dbMasterLink,$u_sql);
	if($updateRes){
		echo "<script>alert('操作成功！');location.href='".$returnUrl."';</script>";
	    exit;
	}else{
		echo "<script>alert('操作失败！');location.href='".$returnUrl."';</script>";
	    exit;
	}
}else{
	$confirmedArr = explode(',',$row['Confirmed']);
	
	if(strlen($row['M_Result'])>0 && $row['M_Result']>=0){
		echo "<script>alert('此注单已结算,不能单串取消');location.href='".$returnUrl."';</script>";
	    exit;
	}
	
	if($row['Cancel']==1 || $row['Confirmed']<0){
		echo "<script>alert('此注单已被取消,不能单串取消');location.href='".$returnUrl."';</script>";
	    exit;
	}
	
	$matcheSql="SELECT Mid,MB_Team,TG_Team,M_League FROM `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE MID IN(".$row['Mid'].")";
	$resultM = mysqli_query($dbLink, $matcheSql);
	
}

?>
<html>
<head>
<title>reports_member</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<body>
<dl class="main-nav">
    <dt>查询注单</dt>
    <dd> 线上操盘－<font color="#CC0000">&nbsp;综合过关-单串取消</font> -- <a href="<?php echo $returnUrl;?>">回上一页</a></dd>
</dl>
<div class="main-ui">
    <FORM NAME="LAYOUTFORM" onSubmit="return SubChk();" action="query_cancel_sing_fs.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>" method="POST">
<table class="m_tab">
  <tr class="m_title" > 
      <td width="80"></td>
      <td width="180">主队</td>
      <td width="275">客队</td>
      <td width="200">联赛</td>
  </tr>
  <?php while($rowM=mysqli_fetch_assoc($resultM)){?>
  <tr class="m_cen"> 
  	  <td width="70"  align="center"><input type="checkbox" name="chaunguan[]" value="<?php echo $rowM['Mid']?>" <?php if(in_array($rowM['Mid'],$confirmedArr)){  echo 'checked'; } ?>  /></td>
      <td width="80"  align="center"><?php echo $rowM['MB_Team'];?></td>
      <td width="275" align="center"><?php echo $rowM['TG_Team'];?></td>
      <td width="70"  align="center"><?php echo $rowM['M_League'];?></td>
  </tr>
  <?php } ?>
  <tr class="m_cen">
        <td colspan="4">
            <input type="submit" value=" 提 交 " name="subject" class="za_button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="reset" value=" 清 除 " name="cancel" class="za_button">
        </td>
  </tr>
  <input type="hidden" value="<?php echo $id?>" name="id" />
  <input type="hidden" value="<?php echo $page?>" name="page" />
  <input type="hidden" value="<?php echo $sort?>" name="sort" />
  <input type="hidden" value="<?php echo $ball?>" name="ball" />
  <input type="hidden" value="<?php echo $type?>" name="type" />
  <input type="hidden" value="<?php echo $username?>" name="username" />
  <input type="hidden" value="<?php echo $date_start?>" name="date_start" />
  <input type="hidden" value="<?php echo $date_end?>" name="date_end" />
  <input type="hidden" value="cancelCUR" name="editKey" class="za_button"/>
  </table>
</form>
</div>
<script charset="utf-8" src="../../../js/agents/jquery.js" ></script>
<script language="JavaScript">
</script>
</body>
</html>
