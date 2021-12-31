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
	
	
	
 
 
//�ɶ�����

$ygid=$_POST['ygid'];
$yg=$_POST['m'];
$ygb=$_POST['ygb'];
$ygc=$_POST['ygc'];
$ygd=$_POST['ygd'];
$xx=$_POST['mm'];
$xxx=$_POST['mmm'];

for ($I=0; $I<=count($ygid); $I=$I+1)
{

$exe=mysqli_query($dbLink,"Update ka_guands Set yg='".$yg[$I]."',ygb='".$ygb[$I]."',ygc='".$ygc[$I]."',ygd='".$ygd[$I]."',xx='".$xx[$I]."',xxx='".$xxx[$I]."' where ID=".$ygid[$I]);

} 

print "<script language='javascript'>alert('���óɹ���');window.location.href='index.php?action=xt_ds';</script>";
exit();

}








$result=mysqli_query($dbLink,"Select ID,drop_sort,drop_value,drop_unit,low_drop from ka_drop order by ID");
$drop_table = array();
$y=0;
while($image = mysqli_fetch_assoc($result)){
$y++;
array_push($drop_table,$image);

}

$drop_count=$y-1;


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
   <form name=form1 action=index.php?action=xt_ds&save=save method=post> <tr>
      <td width="90" height="25" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">����</span> </td>
      <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">Ӷ��%A</td>
      <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA" >Ӷ��%B</td>
      <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA" >Ӷ��%C</td>
      <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA" >Ӷ��%D</td>
      <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA" >��ע�޶�</td>
      <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA" >����(��)�޶�</td>
    </tr>
    <?php  $t=0;
				 $result=mysqli_query($dbLink,"select * from  ka_guands where lx=0 and style='���ϲ�' order by id"); 
while($rst = mysqli_fetch_assoc($result)){
    if ($rst['ds'] == "����") continue;
$t++;?>
    <tr>
      <td height="20" align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><?php echo $rst['ds']?>
          <input name="ds[]" type="hidden" id="ds[]" value="<?php echo $rst['ds']?>" />
      <input name="ygid[]" type="hidden" id="ygid[]" value="<?php echo $rst['id']?>" /></td>
      <td align="center" bordercolor="cccccc"><input name="m[]" class="input1" id="m[]" value='<?php echo $rst['yg']?>' size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="ygb[]" class="input1" id="mm[]" value='<?php echo $rst['ygb']?>' size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="ygc[]" class="input1" id="ygc[]" value='<?php echo $rst['ygc']?>' size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="ygd[]" class="input1" id="ygd[]" value='<?php echo $rst['ygd']?>' size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="mm[]" class="input1" id="mm[]" value='<?php echo $rst['xx']?>' size="10" /></td>
      <td align="center" bordercolor="cccccc"><input name="mmm[]" class="input1" id="mmm[]" value='<?php echo $rst['xxx']?>' size="10" /></td>
    </tr>
    
    <?php }?>
	<tr>
      <td height="20" colspan="7" align="center" bordercolor="cccccc"><button onclick="submit()"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:80;height:22" ;><img src="images/icon_21x21_copy.gif" align="absmiddle" />ȷ���޸�</button>
      <button onclick="javascript:location.reload();"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;><img src="images/icon_21x21_info.gif" align="absmiddle" />ˢ��</button></td>
    </tr>  </form >
</table>

