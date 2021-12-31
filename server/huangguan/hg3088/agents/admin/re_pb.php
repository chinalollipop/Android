<?php if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}

 if (strpos($_SESSION['flag'],'09') ){}else{ 
echo "<center>��û�и�Ȩ�޹���!</center>";
exit;}

?>


<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" src="tip.js?v=<?php echo AUTOVER; ?>"></script>
<style type="text/css">
<!--
.style1 {
	color: #666666;
	font-weight: bold;
}
.style2 {color: #FF0000}
.STYLE3 {color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<div align="center">
<link rel="stylesheet" href="xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<script src="inc/forms.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" type="text/JavaScript">
function SelectAllPub() {
	for (var i=0;i<document.form1.flag.length;i++) {
		var e=document.form1.flag[i];
		e.checked=!e.checked;
	}
}
function SelectAllAdm() {
	for (var i=0;i<document.form1.flag.length;i++) {
		var e=document.form1.flag[i];
		e.checked=!e.checked;
	}
}
</script>

<SCRIPT>
<!--
 function onSubmit()
 {
  kind_obj = document.getElementById("ac");
  form_obj = document.getElementById("myFORM");
  if(kind_obj.value == "A")
   form_obj.action = "index.php?action=re_all";
  else
   form_obj.action = "index.php?action=re_class";
  return true;
 }
-->
</SCRIPT>

<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr class="tbtitle">
    <td width="29%"><span class="STYLE3">�����ѯ</span></td>
    <td width="34%">&nbsp;</td>
    <td width="37%">&nbsp;</td>
  </tr>
  <tr >
    <td height="5" colspan="3"></td>
  </tr>
</table>
<table width="100%"  border="1" cellpadding="2" cellspacing="2" bordercolor="f1f1f1">
  <form id="myFORM" name="FrmData"  action="index.php?action=re_all" method="post">
    <tr>
      <td height="25" align="right" bordercolor="cccccc" ><span class="STYLE1">ͶעƷ�֣� </span></td>
      <td bordercolor="cccccc" ><select name="class2" id="class2">
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
        <option value="�岻��" >���У��岻��</option>
        <option value="�߲���" >���У��߲���</option>
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
    </tr>
    <tr>
      <td height="25" align="right" bordercolor="cccccc" ><span class="STYLE1">�������䣺 </span></td>
      <td bordercolor="cccccc" ><table cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td><input name="txt8" type="text" class="input1" value="<?php echo date("Y-m-d")?>" size="18" />
            </td>
            <td><img src="images/date.gif" align="absmiddle" class="cursor" onclick="javascript:popdate(txt8)" /> </td>
            <td align="middle" width="20">~</td>
            <td><input name="txt9" type="text" class="input1" value="<?php echo date("Y-m-d")?>" size="18" />
            </td>
            <td><img src="images/date.gif" align="absmiddle" class="cursor" onclick="javascript:popdate(txt9)" /></td>
            <td align="right" width="200"></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td height="25" align="right" bordercolor="cccccc" ><span class="STYLE1">ѡ�������� </span></td>
      <td bordercolor="cccccc" ><select name="kithe" id="kithe">
        <option value="" selected="selected">��ʱ������</option>
        <?php
		$result = mysqli_query($dbLink,"select * from ka_kithe order by nn desc");   
while($image = mysqli_fetch_assoc($result)){
			     echo "<OPTION value=".$image['nn'];
				echo ">��".$image['nn']."��</OPTION>";
			  }
		?>
      </select>
          <span class="style2">(���ѡ��������,��������ڽ���Ч��)</span> </td>
    </tr>
    <tr>
      <td width="16%" height="25" bordercolor="cccccc">&nbsp;</td>
      <td width="84%" bordercolor="cccccc"><table width="100" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="6"></td>
        </tr>
      </table>
          <input  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'"  type="submit" value="��ѯ" name="SUBMIT" />
          <table width="100" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="10"></td>
            </tr>
        </table></td>
    </tr>
  </form>
</table>
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><div align="left"> </div></td>
    <td height="35"><div align="right" disabled="disabled"><img src="images/slogo_10.gif" width="15" height="11" align="absmiddle" /> ������ʾ������밴ʱ������ѯѡ������ʱ����ѡ��[��ʱ������]�����ѡ��������������ʱ������ѯ����</div></td>
  </tr>
</table>
</div>
