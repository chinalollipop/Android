<?php
if(!defined('PHPYOU_VER')) {
	exit('�Ƿ�����');
}

if ($_GET['save']=="save"){



$exe=mysqli_query($dbLink,"update ka_bl set rate=mrate,blrate=mrate");


echo "<script>alert('����Ĭ�ϻ�ԭ�ɹ�!');window.location.href='index.php?action=rake_hy';</script>"; 

 
}

?>


<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" src="tip.js?v=<?php echo AUTOVER; ?>"></script>

<SCRIPT language=JAVASCRIPT>

if(window.location.host!=top.location.host){top.location=window.location;} 



</SCRIPT>

<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr class="tbtitle">
    <td width="100%"><?php require_once 'retop.php';?></td>
  </tr>
  <tr >
    <td height="5" colspan="2"></td>
  </tr>
</table>
<table width="99%" border="1" align="center" cellpadding="2" cellspacing="1" bordercolor="f1f1f1" class="about">
  
  <tr>
    <td bordercolor="cccccc" bgcolor="#FDF4CA">��ԭ����</td>
  </tr>
  <tr>
    <td height="50" align="center" bordercolor="cccccc"><button onclick="javascript:if(confirm('��ȷ��Ҫ��ԭ�𣿱��������޷��ָ���')){location.href='index.php?action=rake_hy&amp;save=save'}"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:200;height:22" ;><img src="images/address.gif" width="16" height="16" align="absmiddle" />&nbsp;&nbsp;<font color="ff0000">��ԭĬ������</font></button></td>
  </tr>
</table>
<div align="center">
  <table width="98%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="70"><div align="left"> </div></td>
      <td height="35"><div align="right" disabled="disabled"><img src="images/slogo_10.gif" width="15" height="11" align="absmiddle" /> ��ʾ����С�Ļ�ԭ,һ����ԭ���������޷��ָ�.</div></td>
    </tr>
  </table>
  <br>
<br>
</div>
