<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../include/config.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
require ("../include/traditional.$langx.inc.php");

$winCount=0;
$uid=$_REQUEST["uid"];
$action=$_REQUEST['action'];
$gid=$_REQUEST['gid'];
$gtype=$_REQUEST['gtype'];
$rtype=$_REQUEST['rtype'];
$lv=$_REQUEST['lv'];
$page=$_REQUEST['page'];
$sql = "select ID,MID,M_Start,MB_Team,M_League,M_Item,M_Rate,Gid,win from ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." where MID='".$gid."'";

$result = mysqli_query($dbLink, $sql);
while($row=mysqli_fetch_assoc($result)){
	$rows[]=$row;
}
$rowNums=count($rows);
?>
<html>
<head>
<title>reports_member</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<body>
<dl class="main-nav">
    <dt>注单结算</dt>
    <dd> 线上操盘－<font color="#CC0000">冠军输入&nbsp;</font> -- <a href="javascript:history.go( -1 );">回上一页</a></dd>
</dl>
<div class="main-ui">
    <FORM NAME="LAYOUTFORM" action="../clearing/clearingFS.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>" method="POST">
<table class="m_tab">
  <tr class="m_title" > 
      <td width="80">时间</td>
      <td width="200">联赛名称</td>
      <td width="180">赛事名称</td>
      <td width="275">比赛队伍</td>
  </tr>
  <tr class="m_cen"> 
      <td width="80"  align="center"><?php echo $rows[0]['M_Start'];?></td>
      <td width="275" align="center"><?php echo $rows[0]['M_League'];?></td>
      <td width="70"  align="center"><?php echo $rows[0]['MB_Team'];?></td>
	  <td width="70"  align="center">		
		<?php foreach($rows as $key=>$val){?> 
			<input type="checkbox" name="guanjun[]" <?php if($val['win']==1){ echo 'checked';$winCount=$winCount+1; } ?> value="<?php echo $val['Gid']?>" /><?php echo  $val['M_Item']."<br/>"; ?>
      	<?php } ?>
	  </td>
  </tr>
  <tr class="m_cen">
        <td colspan="4">
            <input type="submit" value=" 提 交 " name="subject" class="za_button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="reset" value=" 清 除 " name="cancel" class="za_button">
        </td>
  </tr>
  <input type="hidden" value="<?php echo $page?>" name="page" class="za_button"/>
  <input type="hidden" value="<?php echo $gid?>" name="gid" class="za_button"/>
  <input type="hidden" value="<?php echo $rows[0]['M_Start'];?>" name="start" class="za_button"/>
  <input type="hidden" value="<?php echo $rows[0]['M_League'];?>" name="League" class="za_button"/>
  <input type="hidden" value="<?php echo $rows[0]['MB_Team'];?>" name="mbTeam" class="za_button"/>
  </table>
</form>
</div>
<script charset="utf-8" src="../../../js/agents/jquery.js" ></script>
<script charset="utf-8" src="../../../js/agents/jquery.js" ></script>
<script language="JavaScript">
</script>
</body>
</html>