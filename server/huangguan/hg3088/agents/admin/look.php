<?php if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}

if ($_GET['kithe']!=""){$kithe=$_GET['kithe'];}else{$kithe=$Current_Kithe_Num;}
$bb=$_GET['id'];

$id=$_GET['id'];
 switch ($bb)
  {
    case 1:
      $class1="����";
      break;
	 case 2:
 $class1="����";
 $class2=$_GET['class2'];
  break;
 case 3:
 $class1="����";
  break;
 case 31:
 $class1="��1-6";
  break;
	case 4:
 $class1="����";
 $class2=$_GET['class2'];
  break;
	case 5:
 $class1="��Ф";
 $class2=$_GET['class2'];
  break;
case 6:
 $class1="�벨";
 $class2=$_GET['class2'];
  break;
case 61:
 $class1="��벨";
 $class2=$_GET['class2'];
  break;
case 611:
 $class1="��ɫ��";
 $class2=$_GET['class2'];
  break;
case 6111:
 $class1="��Ф";
 $class2=$_GET['class2'];
  break;
case 71:
 $class1="ͷ��";
 $class2=$_GET['class2'];
  break;
case 7:
 $class1="β��";
 $class2=$_GET['class2'];
  break;
case 72:
 $class1="����β��";
 $class2=$_GET['class2'];
  break;
case 8:
 $class1="����";
 $class2=$_GET['class2'];
  break;
case 9:
 $class1="����";
 $class2=$_GET['class2'];
  break;
case 10:
 $class1=$_GET['class1'];
 $class2=$_GET['class2'];
  break;
case 11:
 $class1="��Ф��";
 $class2=$_GET['class2'];
  break;
case 12:
 $class1="ȫ����";
 $class2=$_GET['class2'];
  break;
case 13:
 $class1="β����";
 $class2=$_GET['class2'];
  break;


    default:

     $class1="����";
      break;
  } 
  
  
  
?>

<link rel="stylesheet" href="xp.css?v=<?php echo AUTOVER; ?>" type="text/css">

<title>[<?php if ($class1=="����" or $class1=="����" ){?><?php echo $class1?>:<?php echo $_GET['class3']?><?php }else{?><?php echo $class1?>/<?php echo $_GET['class2']?>:<?php echo $_GET['class3']?><?php }?>]��ע��ϸ</title>
<?php if ($_GET['act']==""){?>
<style type="text/css">
<!--
.STYLE1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<iframe id=frmRight 
      style="Z-INDEX: 1; VISIBILITY: inherit; WIDTH: 100%; HEIGHT: 100%" 
      name=right src="index.php?action=look&act=main&lx=<?php echo $_GET['lx'];?>&kithe=<?php echo $kithe?>&id=<?php echo $_GET['id'];?>&class1=<?php echo $_GET['class1'];?>&class2=<?php echo $_GET['class2'];?>&class3=<?php echo $_GET['class3'];?>" frameborder=0></iframe>
	  
<?php }?>

<?php if ($_GET['act']=="main"){?>
<body scroll=yes leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onkeydown="return false";>


<table id="tb"  border="1" align="center" cellspacing="1" cellpadding="3" bordercolordark="#FFFFFF" bordercolor="f1f1f1" width="100%">
  <tr class="tbtitle">
    <td height="28" colspan="12" nowrap="nowrap" bordercolor="cccccc"><font color="#FFFFFF">&nbsp;<strong>[
        <?php if ($class1=="����" or $class1=="����" ){?>
      <?php echo $class1?>
      :
      <?php echo $_GET['class3']?>
      <?php }else{?>
      <?php echo $class1?>
      /
      <?php echo $_GET['class2']?>
      :
      <?php echo $_GET['class3']?>
      <?php }?>
      ]��ע��ϸ</strong></font></td>
  </tr>
  <tr >
    <td width="4%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">���</td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">������</td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">�µ�ʱ��</td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">��Ա</td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��ע�ܶ�</span></td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">Ӷ��</span></td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��%</span></td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��%</span></td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��%</span></td>
    <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA"><span class="STYLE2">��%</span></td>
  </tr>
  <?php

$a=1;
 if ($class1=="����" or $class1=="����" ){
$result = mysqli_query($dbLink,"select * from ka_tan where kithe='".$kithe."' and class1='".$class1."' and class3='".$_GET['class3']."' and lx=".$_GET['lx']."  order by id desc"); }else {
$result = mysqli_query($dbLink,"select * from ka_tan where kithe='".$kithe."' and class2='".$class2."' and class1='".$class1."' and class3='".$_GET['class3']."'  order by id desc"); }
//$result = mysqli_query($dbLink,"select * from ka_tan where kithe='".$kithe."' and class2='".$class2."' and class1='".$class1."' and class3='".$_GET['class3']."' and lx=".$_GET['lx']."  order by id desc"); }

 
while($rs = mysqli_fetch_assoc($result)){
?>
  <?php if ($rs['class2']=="��B" or $rs['class2']=="��B" ){?>
  <tr bgColor="#E8E4D0" onMouseOver="javascript:this.bgColor='#FDF4CA'" onMouseOut="javascript:this.bgColor='#E8E4D0'">
    <?php }else{?>
  <tr bgColor="#ffffff" onMouseOver="javascript:this.bgColor='#FDF4CA'" onMouseOut="javascript:this.bgColor='#ffffff'">
	<?php }?>
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $a?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['num']?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['adddate']?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['kithe']?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['username']?>
      .
        <?php echo $rs['abcd']?>
      .
      <?php echo abs($rs['user_ds'])?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><font color=ff6600>
      <?php echo $rs['sum_m']?>
    </font></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['rate']?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['sum_m']*$rs['user_ds']/100?></td>
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['dai_zc']*10?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['zong_zc']*10?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['guan_zc']*10?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['dagu_zc']*10?></td>
  </tr>
  <?php
			$sum_m+=$rs['sum_m'];
			$user_ds+=$rs['sum_m']*$rs['user_ds']/100;
$a=$a+1;
}?>
  <tr bgColor="#E0DCC0" >
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF"><font color=ff6600>�ܼ�</font></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF"><font color=ff6600>
      <?php echo $sum_m?>
    </font></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF"><font color=ff6600>
      <?php echo $user_ds?>
    </font></td>
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgColor="#E0DCC0" >
    <td height="28" colspan="12" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF"><table width="98%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="70"><div align="left"> </div></td>
        <td><div align="right" disabled><img src="images/slogo_10.gif" width="15" height="11" align="absmiddle"> ��ʾ������ɫ����[
                 <?php if  ($class1=="����") {?>
          ��B
          <?php }else{?>
          ��B
          <?php }?>
          ]��ע��ϸ!</div></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php }?>
