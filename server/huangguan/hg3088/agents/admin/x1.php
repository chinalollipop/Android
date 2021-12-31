<?php if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}

if (strpos($_SESSION['flag'],'11') ){}else{ 
echo "<center>��û�и�Ȩ�޹���!</center>";
exit;}



if ($_GET['kithe']!=""){$kithe=$_GET['kithe'];}else{$kithe=$Current_Kithe_Num;}







?>

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<SCRIPT language=JAVASCRIPT>

if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>






<style type="text/css">
<!--
.STYLE3 {color: #FF3300}
.STYLE4 {color: #FFFFFF}
-->
</style>
<body  >
<noscript>
<iframe scr=��*.htm��></iframe>
</noscript>

<div align="center">
<link rel="stylesheet" href="xp.css?v=<?php echo AUTOVER; ?>" type="text/css">


<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr class="tbtitle">
    <td width="15%"><strong><font color="#FFFFFF">ע����ѯ[
      <?php echo $kithe?>
    ��]</font></strong></td>
    <td width="48%"><table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td width="73" align="right" nowrap><span class="STYLE4">ѡ��������</span> </td>
        <td width="36" nowrap><SELECT class=zaselect_ste name=temppid onChange="var jmpURL=this.options[this.selectedIndex].value ; if(jmpURL!='') {window.location=jmpURL;} else {this.selectedIndex=0 ;}">
            <?php
		$result = mysqli_query($dbLink,"select * from ka_kithe order by nn desc");   
while($image = mysqli_fetch_assoc($result)){
			     echo "<OPTION value=index.php?action=x1&ids=".$ids."&kithe=".$image['nn'];
				 if ($kithe!="") {
				 if ($kithe==$image['nn']) {
				  echo " selected=selected ";
				  }				
				}
				 echo ">��".$image['nn']."��</OPTION>";
			  }
		?>
        </SELECT></td>
        <td width="697">&nbsp;</td>
      </tr>
    </table></td>
    <td width="37%" align="right"><button onClick="javascript:window.print();"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;><img src="images/asp.gif" width="16" height="16" align="absmiddle" />��ӡ</button></td>
  </tr>
  <tr >
    <td height="5" colspan="3"></td>
  </tr>
</table>

<table id="tb"  border="1" align="center" cellspacing="1" cellpadding="1" bordercolordark="#FFFFFF" bordercolor="f1f1f1" width="99%">
  <tr >
    <td width="4%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" >���</td>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA" >�ɶ�</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">ע��</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">��ע���</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">ʵͶ</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">��˾ռ��</TD>
    <TD align="center" nowrap bordercolor="cccccc" bgcolor="#FDF4CA">�ɶ�ռ��</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">�ܴ�ռ��</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">����ռ��</TD>
  </tr>
  <?php
		$z_re=0;
		$z_st=0;
		$z_sum=0;
		$z_dagu=0;
		$z_guan=0;
		$z_zong=0;
		$z_dai=0;
		
		 $result = mysqli_query($dbLink,"select distinct(guan)   from   ka_tan where Kithe='".$kithe."'  order by guan desc");   
$ii=0;
while($rs = mysqli_fetch_assoc($result)){



$result1 = mysqli_query($dbLink,"Select sum(sum_m) as sum_m,count(*) as re,sum(sum_m*dagu_zc/10) as dagu_zc,sum(sum_m*guan_zc/10) as guan_zc,sum(sum_m*zong_zc/10) as zong_zc,sum(sum_m*dai_zc/10) as dai_zc,sum(sum_m-sum_m*user_ds/100) as sum_st from ka_tan   where Kithe='".$kithe."' and guan='".$rs['guan']."'");

$Rs5 = mysqli_fetch_assoc($result1);



$ii++;
$re=$Rs5['re'];

$sum_m=$Rs5['sum_m'];
$dagu_zc=$Rs5['dagu_zc'];
$guan_zc=$Rs5['guan_zc'];
$zong_zc=$Rs5['zong_zc'];
$dai_zc=$Rs5['dai_zc'];
$z_st+=$Rs5['sum_st'];


$z_re+=$Rs5['re'];
$z_sum+=$Rs5['sum_m'];
$z_dagu+=$Rs5['dagu_zc'];
$z_guan+=$Rs5['guan_zc'];
$z_zong+=$Rs5['zong_zc'];
$z_dai+=$Rs5['dai_zc'];


$result2=mysqli_query($dbLink,"select * from ka_guan where  kauser='".$rs['guan']."' order by id"); 
$row11=mysqli_fetch_assoc($result2);


if ($row11!=""){$xm="<font color=ff6600> (".$row11['xm'].")</font>";}

?>
  <tr >
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $ii?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['guan']?>
        <?php echo $xm?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $Rs5['re']?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><button onClick="javascript:location.href='index.php?action=x2&kithe=<?php echo $kithe?>&username=<?php echo $rs['guan']?>'"  class="headtd4" style="width:80;height:22" ;><font color=ff6600>
      <?php echo $Rs5['sum_m']?>
    </font></button></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo round($Rs5['sum_st'],2)?></td>
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo round($Rs5['dagu_zc'],2)?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo round($Rs5['guan_zc'],2)?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo round($Rs5['zong_zc'],2)?></td>
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo round($Rs5['dai_zc'],2)?></td>
  </tr>
  <?php }?>
  <tr >
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><span class="STYLE3">�ܼ�</span></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><span class="STYLE3">
      <?php echo $z_re?>
    </span></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><span class="STYLE3">
      <?php echo $z_sum?>
    </span></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo round($z_st,2)?></td>
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc"><span class="STYLE3">
      <?php echo round($z_dagu,2)?>
    </span></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><span class="STYLE3">
      <?php echo round($z_guan,2)?>
    </span></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc"><span class="STYLE3">
      <?php echo round($z_zong,2)?>
    </span></td>
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc"><span class="STYLE3">
      <?php echo round($z_dai,2)?>
    </span></td>
  </tr>
</table>
<br>
<table border="1" align="center" cellspacing="1" cellpadding="2" bordercolor="f1f1f1" bordercolordark="#FFFFFF" width="99%">
  <tr>
    <form name=form55 action="index.php?action=xx5" method=post>
      <td><table width="100" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td nowrap>��ѯ���ࣺ</td>
          <td nowrap><select name="class" id="class">
            <option  value="" selected>-ȫ��-</option>
            <option value="1">��Ա�˺�</option>
            <option value="2">��ע����</option>
            <option value="3">��ע����</option>
          </select>
                <input name="key"  class="input1" type="text" id="key" size="8"></td>
          <td nowrap>ͶעƷ�֣�</td>
          <td nowrap><select name="class2" id="class2">
   
           <option value="" selected="selected">-----ȫ��-----</option>
        <option value="��A">���룺��A</option>
        <option value="��B">���룺��B</option>
        <option value="��A">���룺��A</option>
        <option value="��B">���룺��B</option>
        <option value="��1��">���أ���1��</option>
        <option value="��2��">���أ���2��</option>
        <option value="��3��">���أ���3��</option>
        <option value="��4��" >���أ���4��</option>
        <option value="��5��" >���أ���5��</option>
        <option value="��6��" >���أ���6��</option>
        <option value="����1" >��1-6������1</option>
        <option value="����2"  >��1-6������2</option>
        <option value="����3"  >��1-6������3</option>
        <option value="����4"  >��1-6������4</option>
        <option value="����5" >��1-6������5</option>
        <option value="����6" >��1-6������6</option>
        <option value="����" >����</option>
        <option value="��ȫ��" >���룺��ȫ��</option>
        <option value="���ж�" >���룺���ж�</option>
        <option value="��ȫ��" >���룺��ȫ��</option>
        <option value="������"  >���룺������</option>
        <option value="�ش�" >���룺�ش�</option>
        <option value="��Ф" >��Ф����Ф</option>
        <option value="��Ф"  >��Ф����Ф</option>
        <option value="��Ф" >��Ф����Ф</option>
        <option value="��Ф"  >��Ф����Ф</option>
		<option value="��Ф" >��Ф����Ф</option>
		<option value="��Ф" >��Ф����Ф</option>
        <option value="һФ" >��Ф��һФ</option>
        <option value="�벨" >�벨</option>
        <option value="��벨" >��벨</option>
        <option value="ͷ��">ͷ��</option>
        <option value="β��">β��</option>
        <option value="����β��">����β��</option>
        <option value="��Ф" >��Ф</option>
        <option value="��ɫ��" >��ɫ��</option>
          </select>
                <input name="ac2" type="hidden" id="ac" value="A" /></td>
          <td nowrap>�������䣺</td>
          <td><table cellspacing="0" cellpadding="0" border="0">
            <tbody>
              <tr>
                <td><input name="txt8" type="text" class="input1" value="<?php echo date("Y-m-d")?>" size="12">
                </td>
                <td><img src="images/date.gif" align="absmiddle" class="cursor" onClick="javascript:popdate(txt8)"> </td>
                <td align="middle" width="20">~</td>
                <td><input name="txt9" type="text" class="input1" value="<?php echo date("Y-m-d")?>" size="12">
                </td>
                <td><img src="images/date.gif" align="absmiddle" class="cursor" onClick="javascript:popdate(txt9)"></td>
                <td align="right" width="200"></td>
              </tr>
            </tbody>
          </table></td>
          <td nowrap>ѡ��������</td>
          <td><select name="kithe" id="kithe">
            <option value="" selected="selected">��ʱ������</option>
            <?php
		$result = mysqli_query($dbLink,"select * from ka_kithe order by nn desc");   
while($image = mysqli_fetch_assoc($result)){
			     echo "<OPTION value=".$image['nn'];
				echo ">��".$image['nn']."��</OPTION>";
			  }
		?>
          </select></td>
          <td><INPUT  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'"  type=submit value=��ѯ name=SUBMIT></td>
        </tr>
      </table></td>
    </FORM>
  </tr>
</table>
<div align="center"></div>
