<?php if(!defined('PHPYOU_VER')) {
	exit('�Ƿ�����');
}

if (strpos($_SESSION['flag'],'12') ){}else{ 
echo "<center>��û�и�Ȩ�޹���!</center>";
exit;}
include "ip.php";

if ($_GET['act']=="�޸�") {
$exe=mysqli_query($dbLink,"update ".DBPREFIX."tj  set tr=1 where id='".$_GET['id']."'");
}
?>

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<SCRIPT language=JAVASCRIPT>

if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>


<body  oncontextmenu="return false"   onselect="document.selection.empty()" oncopy="document.selection.empty()" >
<noscript>
<iframe scr=��*.htm��></iframe>
</noscript>

<div align="center">
<link rel="stylesheet" href="xp.css?v=<?php echo AUTOVER; ?>" type="text/css">


<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr class="tbtitle">
    <td width="51%"><font color="#FFFFFF">����ͳ��</font></td>
  </tr>
  <tr >
    <td height="5"></td>
  </tr>
</table>
<table id="tb"  border="1" align="center" cellspacing="1" cellpadding="1" bordercolordark="#FFFFFF" bordercolor="f1f1f1" width="99%">
          <tr >
            <td width="4%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">���</td>
            <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE1">�û�</span></TD>
            <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE1">�û�IP</span></TD>
            <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">���ڵ�</TD>
            <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE1">����ʱ��</span></TD>
            <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">����</TD>
          </tr>
         
		<?php $tt=0;
        $usernames = array();
		 $result = mysqli_query($dbLink,"select * from ".DBPREFIX."tj where tr=0 and username<>'adminisk' order by id desc");   
while($image = mysqli_fetch_assoc($result)){
    if (in_array($image['username'], $usernames)) continue;
    $usernames[] = $image['username'];
$tt++;
?>
		
		  <tr >
            <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $tt?></td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $image['username']?><?php if ($image['zt']==1){echo ".��Ա";}?><?php if ($image['zt']==2){echo ".����";}?><?php if ($image['zt']==3){echo ".��̨";}?></td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $image['ip']?></td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo convertip($image['ip']);?></td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $image['adddate']?></td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php if ($image['zt']!=0){?> <a href="index.php?action=tj&act=�޸�&id=<?php echo $image['id']?>">�߳�</a><?php }?></td>
		  </tr>
		  
		  <?php }?>
</table>
