<?php
if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}

	
	


if ($_GET['save']=="save") {
	
	for ($i=1;$i<=49;$i=$i+1){
	
	$xr=$_POST['Num_'.$i];
	$class3=$_POST['class3_'.$i];
$exe=mysqli_query($dbLink,"update ka_bl set xr=".$xr." where class2='��A' and  class3='".$class3."'");
$exe=mysqli_query($dbLink,"update ka_bl set xr=".$xr." where class2='��B' and  class3='".$class3."'");

}
print "<script language='javascript'>alert('���óɹ���');window.location.href='index.php?action=xt_nn';</script>";
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


function quick0()
{
	var mm = document.all.money.value;
	
	
	for (var i=1; i<50; i++) {
	
				document.all["Num_"+i].value = mm;
			
		}
		
	

	
	
}

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

<?php $result=mysqli_query($dbLink,"Select xr, class3 from ka_bl where class2='��A' order by ID");
$drop_table = array();
$y=0;
while($image = mysqli_fetch_assoc($result)){
$y++;
//echo $image['class3'];
array_push($drop_table,$image);

}
?>
  <table   border="1" align="center" cellspacing="1" cellpadding="1" bordercolordark="#FFFFFF" bordercolor="f1f1f1" width="99%">
    
	   <form name=form1 action=index.php?action=xt_nn&save=save method=post> <tr >
      <td width="4%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
      <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">�޶�</td>
      <td width="4%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
      <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">�޶�</td>
      <td width="4%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
      <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">�޶�</td>
      <td width="4%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
      <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">�޶�</td>
      <td width="4%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
      <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">�޶�</td>
    </tr>
    <?php

for ($I=1; $I<=10; $I=$I+1)
{

	
	?>
    <tr>
      <td height="25" align="center" bordercolor="cccccc"><img src="images/num<?php echo $I?>.gif" /></td>
      <td height="25" align="center" bordercolor="cccccc"><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="8" value="<?php echo $drop_table[$I-1][0]?>" name="Num_<?php echo $I?>" />
      <input name="class3_<?php echo $I?>" value="<?php echo $drop_table[$I-1][1]?>" type="hidden"></td>
      <td height="25" align="center" bordercolor="cccccc"><img src="images/num<?php echo $I+10?>.gif" /></td>
      <td height="25" align="center" bordercolor="cccccc"><input      style="HEIGHT: 18px"  class="input1" maxlength="6" size="8" value="<?php echo $drop_table[$I+10-1][0]?>" name="Num_<?php echo $I+10?>" />
      <input name="class3_<?php echo $I+10?>" value="<?php echo $drop_table[$I+10-1][1]?>" type="hidden"></td>
      <td height="25" align="center" bordercolor="cccccc"><img src="images/num<?php echo $I+20?>.gif" /></td>
      <td height="25" align="center" bordercolor="cccccc"><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="8" value="<?php echo $drop_table[$I+20-1][0]?>" name="Num_<?php echo $I+20?>" />
      <input name="class3_<?php echo $I+20?>" value="<?php echo $drop_table[$I+20-1][1]?>" type="hidden"></td>
      <td height="25" align="center" bordercolor="cccccc"><img src="images/num<?php echo $I+30?>.gif" /></td>
      <td height="25" align="center" bordercolor="cccccc"><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="8" value="<?php echo $drop_table[$I+30-1][0]?>" name="Num_<?php echo $I+30?>" />
      <input name="class3_<?php echo $I+30?>" value="<?php echo $drop_table[$I+30-1][1]?>" type="hidden"></td>
      <?php if ($I!=10){?>
      <td height="25" align="center" bordercolor="cccccc"><img src="images/num<?php echo $I+40?>.gif" /></td>
      <td height="25" align="center" bordercolor="cccccc"><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="8" value="<?php echo $drop_table[$I+40-1][0]?>" name="Num_<?php echo $I+40?>" />
      <input name="class3_<?php echo $I+40?>" value="<?php echo $drop_table[$I+40-1][1]?>" type="hidden"></td>
      <?php }else{ ?>
      <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
      <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
      <?php }?>
    </tr>
    <?php }?>

    <tr>
      <td height="25" colspan="12" align="center" bordercolor="cccccc">&nbsp;<font color="ff0000">ͳ���޸�</font>
          <input class="input1" size="4" name="money" />
        &nbsp;
      <input name="button2" class="button_c" type="button" onclick="quick0()" value="�D��" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button onclick="submit()"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:80;height:22" ;><img src="images/icon_21x21_copy.gif" align="absmiddle" />ȷ���޸�</button>
      <button onclick="javascript:location.reload();"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;><img src="images/icon_21x21_info.gif" align="absmiddle" />ˢ��</button></td>
    </tr>
		</form>
  </table>
  
