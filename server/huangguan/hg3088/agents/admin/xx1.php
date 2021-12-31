<?php if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}

if (strpos($_SESSION['flag'],'11') ){}else{ 
echo "<center>��û�и�Ȩ�޹���!</center>";
exit;}



if ($_GET['kithe']!=""){$kithe=$_GET['kithe'];}else{$kithe=$Current_Kithe_Num;}







?>

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">

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

<br>
<table border="1" align="center" cellspacing="1" cellpadding="2" bordercolor="f1f1f1" bordercolordark="#FFFFFF" width="99%">
  <tr>
    <form name=form55 action="index.php?action=xxx5" method=post>
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
   
            <option value="��A" selected>���룺��A</option>
            <option value="��B">���룺��B</option>
            <option value="��A">���룺��A</option>
            <option value="��B">���룺��B</option>
            <option value="��1��">���أ���1��</option>
            <option value="��2��">���أ���2��</option>
            <option value="��3��">���أ���3��</option>
            <option value="��4��" >���أ���4��</option>
            <option value="��5��" >���أ���5��</option>
            <option value="��6��" >���أ���6��</option>
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
            <option value="һФ" >��Ф��һФ</option>
            <option value="�벨" >�벨</option>
            <option value="��벨" >��벨</option>
            <option value="ͷ��">ͷ��</option>
            <option value="β��">β��</option>
            <option value="����β��">����β��</option>
            <option value="��Ф" >��Ф</option>
            <option value="��ɫ��" >��ɫ��</option>
			<option value="����">����</option>
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
