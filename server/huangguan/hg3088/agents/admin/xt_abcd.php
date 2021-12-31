<?php
if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}

$result=mysqli_query($dbLink,"select * from ".DBPREFIX."adad order by id"); 
$row=mysqli_fetch_assoc($result);

	$best=$row['best'];
	
	$zm=$row['zm'];
	$zm6=$row['zm6'];
	$lm=$row['lm'];	
	$zlm=$row['zlm'];
	$ys=$row['ys'];
	$ls=$row['ls'];
	$dx=$row['dx'];
	$tm=$row['tm'];
	$spx=$row['spx'];
	$bb=$row['bb'];
	$zmt=$row['zmt'];
	$ws=$row['ws'];
	
	$zm1=$row['zm1'];
	$zm61=$row['zm61'];
	$lm1=$row['lm1'];	
	$zlm1=$row['zlm1'];
	$ys1=$row['ys1'];
	$ls1=$row['ls1'];
	$dx1=$row['dx1'];
	$tm1=$row['tm1'];
	$spx1=$row['spx1'];
	$bb1=$row['bb1'];
	$zmt1=$row['zmt1'];
	$ws1=$row['ws1'];
	
	$ps1=$row['ps1'];
	$ps=$row['ps'];
	
	


if ($_GET['save']=="save") {
	
	
	
	$exe=mysqli_query($dbLink,"Update ".DBPREFIX."config Set btm='".$_POST['btm']."',ctm='".$_POST['ctm']."',dtm='".$_POST['dtm']."',btmdx='".$_POST['btmdx']."',ctmdx='".$_POST['ctmdx']."',dtmdx='".$_POST['dtmdx']."',bzt='".$_POST['bzt']."',czt='".$_POST['czt']."',dzt='".$_POST['dzt']."',bztdx='".$_POST['bztdx']."',cztdx='".$_POST['cztdx']."',dztdx='".$_POST['dztdx']."',bzm='".$_POST['bzm']."',czm='".$_POST['czm']."',dzm='".$_POST['dzm']."',bzmdx='".$_POST['bzmdx']."',czmdx='".$_POST['czmdx']."',dzmdx='".$_POST['dzmdx']."',bzm6='".$_POST['bzm6']."',czm6='".$_POST['czm6']."',dzm6='".$_POST['dzm6']."',bbb='".$_POST['bbb']."',cbb='".$_POST['cbb']."',dbb='".$_POST['dbb']."',bsx='".$_POST['bsx']."',csx='".$_POST['csx']."',dsx='".$_POST['dsx']."',bsx6='".$_POST['bsx6']."',csx6='".$_POST['csx6']."',dsx6='".$_POST['dsx6']."',bsxp='".$_POST['bsxp']."',csxp='".$_POST['csxp']."',dsxp='".$_POST['dsxp']."',bth='".$_POST['bth']."',cth='".$_POST['cth']."',dth='".$_POST['dth']."',bzx='".$_POST['bzx']."',czx='".$_POST['czx']."',dzx='".$_POST['dzx']."',blx='".$_POST['blx']."',clx='".$_POST['clx']."',dlx='".$_POST['dlx']."' where id=1");

print "<script language='javascript'>alert('���óɹ���');window.location.href='index.php?action=xt_abcd';</script>";
exit();

}










 ?>

	

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<style type="text/css">
<!--
.STYLE2 {color: #FF0000}
-->
</style>

<noscript>
<iframe scr=��*.htm��></iframe>
</noscript>
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

  <table width="99%" border="1" align="center" cellpadding="3" cellspacing="1" bordercolor="f1f1f1">
   <form name=form1 action=index.php?action=xt_abcd&save=save method=post> <tr >
      <td width="8%" height="25" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��Ŀ</span></td>
      <td width="32%" align="center" bordercolor="cccccc" bgcolor="#FDF4CA" >B��</td>
      <td width="30%" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">C��</span></td>
      <td width="30%" align="center" bordercolor="cccccc" bgcolor="#FDF4CA" ><span class="STYLE2">D��</span></td>
    </tr>
    <tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">����</span></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc">��
        <input name="btm" type="text" id="btm" value="<?php echo $btm?>" size="8" />
        ˫��
      <input name="btmdx" type="text" id="btmdx" value="<?php echo $btmdx?>" size="8" /></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc">��
        <input name="ctm" type="text" id="ctm" value="<?php echo $ctm?>" size="10" />
        ˫��
      <input name="ctmdx" type="text" id="ctmdx" value="<?php echo $ctmdx?>" size="10" /></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc">��
        <input name="dtm" type="text" id="dtm" value="<?php echo $dtm?>" size="10" />
        ˫��
      <input name="dtmdx" type="text" id="dtmdx" value="<?php echo $dtmdx?>" size="10" /></td>
    </tr>
    <tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">����</span></td>
      <td align="center" bordercolor="cccccc">��
        <input name="bzt" type="text" id="bzt" value="<?php echo $bzt?>" size="8" />
        ˫��
      <input name="bztdx" type="text" id="bztdx" value="<?php echo $bztdx?>" size="8" /></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc">��
        <input name="czt" type="text" id="czt" value="<?php echo $czt?>" size="10" />
        ˫��
      <input name="cztdx" type="text" id="cztdx" value="<?php echo $cztdx?>" size="10" /></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc">��
        <input name="dzt" type="text" id="dzt" value="<?php echo $dzt?>" size="10" />
        ˫��
      <input name="dztdx" type="text" id="dztdx" value="<?php echo $dztdx?>" size="10" /></td>
    </tr>
    <tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">����</span></td>
      <td align="center" bordercolor="cccccc">��
        <input name="bzm" type="text" id="bzm" value="<?php echo $bzm?>" size="8" />
        ˫��
      <input name="bzmdx" type="text" id="bztdx" value="<?php echo $bzmdx?>" size="8" /></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc">��
        <input name="czm" type="text" id="czt" value="<?php echo $czm?>" size="10" />
        ˫��
      <input name="czmdx" type="text" id="cztdx" value="<?php echo $czmdx?>" size="10" /></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc">��
        <input name="dzm" type="text" id="dzt" value="<?php echo $dzm?>" size="10" />
        ˫��
      <input name="dzmdx" type="text" id="dztdx" value="<?php echo $dzmdx?>" size="10" /></td>
    </tr>
    <tr>
      <td height="25" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">����</span></td>
      <td align="center" bordercolor="cccccc"><input name="bzm6" type="text" id="bzm6" value="<?php echo $bzm6?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="czm6" type="text" id="czm6" value="<?php echo $czm6?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="dzm6" type="text" id="dzm6" value="<?php echo $dzm6?>" size="10" /></td>
    </tr>
    <tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">�벨</span></td>
      <td align="center" bordercolor="cccccc"><input name="bbb" type="text" id="bbb" value="<?php echo $bbb?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="cbb" type="text" id="cbb" value="<?php echo $cbb?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="dbb" type="text" id="dbb" value="<?php echo $dbb?>" size="10" /></td>
    </tr>
    <tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��Ф</span></td>
      <td align="center" bordercolor="cccccc"><input name="bsx" type="text" id="bsx" value="<?php echo $bsx?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="csx" type="text" id="csx" value="<?php echo $csx?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="dsx" type="text" id="dsx" value="<?php echo $dsx?>" size="10" /></td>
    </tr>
    <tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��Ф</span></td>
      <td align="center" bordercolor="cccccc"><input name="bsx6" type="text" id="bsx6" value="<?php echo $bsx6?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="csx6" type="text" id="csx6" value="<?php echo $csx6?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="dsx6" type="text" id="dsx6" value="<?php echo $dsx6?>" size="10" /></td>
    </tr>
    <tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">һФ</span></td>
      <td align="center" bordercolor="cccccc"><input name="bsxp" type="text" id="bsxp" value="<?php echo $bsxp?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="csxp" type="text" id="csxp" value="<?php echo $csxp?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="dsxp" type="text" id="dsxp" value="<?php echo $dsxp?>" size="10" /></td>
    </tr>
	<tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��벨</span></td>
      <td align="center" bordercolor="cccccc"><input name="bth" type="text" id="bth" value="<?php echo $bth?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="cth" type="text" id="cth" value="<?php echo $cth?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="dth" type="text" id="dth" value="<?php echo $dth?>" size="10" /></td>
    </tr>
    <tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">ȫ����</span></td>
      <td align="center" bordercolor="cccccc"><input name="bzx" type="text" id="bzx" value="<?php echo $bzx?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="czx" type="text" id="czx" value="<?php echo $czx?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="dzx" type="text" id="dzx" value="<?php echo $dzx?>" size="10" /></td>
    </tr>
    <tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��Ф��</span></td>
      <td align="center" bordercolor="cccccc"><input name="blx" type="text" id="blx" value="<?php echo $blx?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="clx" type="clx" id="clx" value="<?php echo $clx?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="dlx" type="dlx" id="dlx" value="<?php echo $dlx?>" size="10" /></td>
    </tr>
	<!--<tr>
      <td height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��벨</span></td>
      <td align="center" bordercolor="cccccc"><input name="bsxp" type="text" id="bsxp" value="<?php echo $bthdx?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="csxp" type="text" id="csxp" value="<?php echo $cthdx?>" size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="dsxp" type="text" id="dsxp" value="<?php echo $dthdx?>" size="10" /></td>
    </tr>-->
    <tr>
      <td height="25" colspan="4" align="center" bordercolor="cccccc">
          <button onclick="submit()"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:80;height:22" ;><img src="images/icon_21x21_copy.gif" align="absmiddle" />ȷ���޸�</button>
      <button onclick="javascript:location.reload();"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;><img src="images/icon_21x21_info.gif" align="absmiddle" />ˢ��</button></td>
    </tr></form >
</table>

