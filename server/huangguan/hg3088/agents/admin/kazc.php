<table   width="99%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="f1f1f1" id="tb">
  <tr >
    <td width="4%" height="28" align="center" nowrap="NOWRAP" bordercolor="cccccc" bgcolor="#CD9A99">���</td>
    <td width="80" align="center" nowrap bordercolor="cccccc" bgcolor="#CD9A99">����</td>
    <td width="40" align="center" nowrap bordercolor="cccccc" bgcolor="#CD9A99">ע��</td>
    <td width="9%" align="center" nowrap bordercolor="cccccc" bgcolor="#CD9A99">��ע�ܶ�</td>
    <td width="8%" align="center" nowrap bordercolor="cccccc" bgcolor="#CD9A99">ռ��</td>
    <td width="8%" align="center" nowrap bordercolor="cccccc" bgcolor="#CD9A99">Ӷ��</td>
    <td width="9%" align="center" nowrap bordercolor="cccccc" bgcolor="#CD9A99">�ʽ�</td>
    <td width="9%" align="center" nowrap bordercolor="cccccc" bgcolor="#CD9A99">Ԥ��ӯ��</td>
    <td width="8%" align="center" nowrap bordercolor="cccccc" bgcolor="#CD9A99">�߷�</td>
    <td width="8%" align="center" nowrap bordercolor="cccccc" bgcolor="#CD9A99">�߷ɽ��</td>
    <td align="center" nowrap bordercolor="cccccc" bgcolor="#CD9A99">��ǰ����</td>
  </tr>
  <tr >
    <td height="28" align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td height="28" align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td height="28" align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
    <td align="center" nowrap="nowrap">&nbsp;</td>
  </tr>
</table><?php
if($_POST['img_text']=='news')
{


}
else
{?>
<table width="99%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="15%"><font color="#FFFFFF"> <strong>
                      <?php echo $ids?>
                      ��������</strong></font></td>
                    <td width="85%"><div align="right">
                     
					 <button onClick="javascript:location.href='main.php?action=rake_pl3yszh&ids=һ�����';"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle"><SPAN id=rtm1 STYLE='color:<?php echo $z1color?>;'>һ�����</span></button>
             <button onClick="javascript:location.href='main.php?action=rake_pl3yszh&ids=�ٶ�λ';"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle"><SPAN id=rtm2 STYLE='color:<?php echo $z2color?>;'>�ٶ�λ</span></button>
             <button onClick="javascript:location.href='main.php?action=rake_pl3yszh&ids=ʰ��λ';"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle"><SPAN id=rtm2 STYLE='color:<?php echo $z3color?>;'>ʰ��λ</span></button>
             <button onClick="javascript:location.href='main.php?action=rake_pl3yszh&ids=����λ';"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle"><SPAN id=rtm2 STYLE='color:<?php echo $z4color?>;'>����λ</span></button>
                    </div></td>
                  </tr>
                </table>


<?php
}
?>