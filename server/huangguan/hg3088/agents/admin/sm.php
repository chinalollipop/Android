<?php if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}


if ($_GET['save']=="save") {

$exe=mysqli_query($dbLink,"Update ".DBPREFIX."config Set a1='".$_POST['a1']."',a3='".$_POST['a3']."' where id=1");

print "<script language='javascript'>alert('�޸ĳɹ���');window.location.href='index.php?action=sm';</script>";
exit();
}?>

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<style type="text/css">
<!--
.STYLE2 {color: #FF0000}
-->
</style><noscript>
<iframe scr=��*.htm��></iframe>
</noscript>
<body >

<SCRIPT language=JAVASCRIPT>

if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>

  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr class="tbtitle">
      <td width="51%"><?php require_once '2top.php';?></td>
    </tr>
    <tr >
      <td height="5"></td>
    </tr>
  </table>
   <?php if (strpos($_SESSION['flag'],'10') ){}else{ 
echo "<center>��û�и�Ȩ�޹���!</center>";
exit;}?>
  <table   border="1" align="center" cellspacing="1" cellpadding="2" bordercolordark="#FFFFFF" bordercolor="f1f1f1" width="99%">
   <form name=form1 action=index.php?action=sm&save=save method=post> <tr >
      <td width="26%" height="28" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">����˵������</span></td>
      <td width="74%" align="center" bordercolor="cccccc" bgcolor="#FDF4CA" ><span class="STYLE2">����</span></td>
    </tr>
    <tr>
      <td align="center" bordercolor="cccccc">��Ա��¼Э��</td>
      <td bordercolor="cccccc"><TEXTAREA id=a3 name=a3 rows=20 cols=90><?php echo ka_config('a3')?>
  </TEXTAREA></td>
    </tr>
    <tr>
      <td align="center" bordercolor="cccccc">����˵��</td>
      <td bordercolor="cccccc"><TEXTAREA id=Content name=a1 rows=20 cols=90><?php echo ka_config('a1')?>
  </TEXTAREA></td>
    </tr>
    <tr>
      <td align="center" bordercolor="cccccc">&nbsp;</td>
      <td height="30" bordercolor="cccccc"><button onClick="javascript:location.href='index.php?action=sm'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22";><img src="images/icon_21x21_info.gif" align="absmiddle" />����</button>
          <button onClick="submit()"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:80;height:22" ;><img src="images/icon_21x21_copy.gif" align="absmiddle" />ȷ���޸�</button>
      <button onClick="javascript:location.reload();"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;><img src="images/icon_21x21_info.gif" align="absmiddle" />ˢ��</button></td>
    </tr></form >
  </table>
  <br>

