<?php if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}




if ($_GET['kithe']!="" or $_GET['kithe']!="" ){

if ($_GET['kithe']!=""){
$kithe=$_GET['kithe'];}else{$kithe=$_GET['kithe'];}


}
$guanname=$_GET['guanname'];   
$agentname=$_GET['agentname'];   
$dai=$_GET['dai'];
$username=$_GET['username'];

?>

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<SCRIPT language=JAVASCRIPT>

if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>






<style type="text/css">
<!--
.STYLE3 { font-weight: bold;}
.STYLE4 {color: #FFFFFF;
	font-weight: bold;
}
.STYLE5 {color: #FFFFFF}
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
    <td width="31%" nowrap><span class="STYLE4"><?php if( $username!="" and $kithe!="" ){?>[<?php echo $username?>]��Աע��[<?php echo $kithe?>��]��ѯ�б�<?php }else{echo "ע����ѯ���";}?></span></td>
    <td width="69%"><table border="0" align="center" cellspacing="0" cellpadding="1" bordercolor="888888" bordercolordark="#FFFFFF" width="99%">
      <tr>
        <td width="74%"><span class="STYLE5">&nbsp;��ǰ����-->>
            <?php if( $kithe!=""){?>
          ���
            <?php echo $kithe?>
            ��
            <?php }else{?>
          �������䣺
            <?php echo $_GET['txt8']?>
            -----
            <?php echo $_GET['txt9']?>
            
          <?php }?>
&nbsp;&nbsp;&nbsp;&nbsp;ͶעƷ�֣�
            <?php if ($_GET['class2']!=""){?>
            <?php echo $_GET['class2']?>
            <?php }else{?>
            ȫ��
            <?php }?>
          </span> </td>
        <td width="26%"><div align="right">
            <button onClick="javascript:location.href='index.php?action=re_dai&kithe=<?php echo $kithe?>&guanname=<?php echo $guanname?>&agentname=<?php echo $agentname?>&username=<?php echo $dai?>'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:100;height:22" ;><img src="images/cal_date_picker.gif" width="15" height="12" align="absmiddle" />���ش���</button>
          <button onClick="javascript:window.print();"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;><img src="images/asp.gif" width="16" height="16" align="absmiddle" />��ӡ</button>
        </div></td>
      </tr>
    </table></td>
  </tr>
  <tr >
    <td height="5" colspan="2"></td>
  </tr>
</table>
<table id="tb"  border="1" align="center" cellspacing="1" cellpadding="1" bordercolordark="#FFFFFF" bordercolor="f1f1f1" width="99%">
  <tr >
    <td width="4%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">���</td>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">��Ա</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">�µ�ʱ�� </TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">����</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA">��ע���</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA" class="m_title_reall">����</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA" class="m_title_reall">����</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA" class="m_title_reall">��ԱӶ��</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3">��Ա�ո�</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA" class="m_title_reall">����Ӷ��</TD>
    <TD align="center" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3">�����ո�</TD>
  </tr>
  <?php
		$z_re=0;
		$z_sum=0;
		$z_dagu=0;
		$z_guan=0;
		$z_zong=0;
		$z_dai=0;
		$re=0;
		$z_user=0;
		$z_userds=0;
		$z_daids=0;
		
		$vvv="where 1=1";
		if ($kithe!=""){
		$vvv.=" and kithe='".$kithe."' ";
		}else{
		if ($_GET['txt8']!="" and $_GET['txt9']!="" ){
		
		$stime=$_GET['txt8']." 00:00:00";
   $etime=$_GET['txt9']." 23:59:59";
		 
		$vvv.=" and adddate>='".$stime."' and adddate<='".$etime."' ";
		
		}else{$vvv.=" and Kithe='".$kithe."' ";}
		
		}
		
	
		
		if ($_GET['class2']!=""){
		$vvv.=" and class2='".$_GET['class2']."' ";
		}
		
		
		if ($_GET['username']!=""){
		$vvv.=" and username='".$_GET['username']."' ";
		}
		
		
		
		
		 $result = mysqli_query($dbLink,"select *  from   ka_tan ".$vvv." order by id desc");   
$ii=0;
while($rs = mysqli_fetch_assoc($result)){




$ii++;
$z_re++;






$z_sum+=$rs['sum_m'];
//$z_dagu+=$Rs5['dagu_zc'];
//$z_guan+=$Rs5['guan_zc'];
//$z_zong+=$Rs5['zong_zc'];
//$z_dai+=$Rs5['dai_zc'];

$result2=mysqli_query($dbLink,"select * from ka_mem where  kauser='".$rs['username']."' order by id"); 
$row11=mysqli_fetch_assoc($result2);


if ($row11!=""){$xm="<font color=ff6600> (".$row11['xm'].")</font>";}
?>
  <form action="index.php?action=x5&save=save&id=<?php echo $rs['id']?>&kithe=<?php echo $kithe?>&username=<?php echo $rs['username']?>" method="post" name="form" id="form">
    <tr <?php if ($rs['bm']==1){?>bgcolor="#FFFF99"<?php }?>>
      <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc" ><?php echo $ii?></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['username']?>
          <?php echo $xm?>
        .
        <?php echo $rs['abcd']?></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['adddate']?></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['kithe']?>��</td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['sum_m']?></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['class1']?></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php echo $rs['class2']?>
        :<font color=ff6600>
          <?php echo $rs['class3']?>
          </font> @ <font color=ff0000><strong>
            <?php echo $rs['rate']?>
          </strong></font></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php if ($rs['bm']==2){echo 0;}else{echo $rs['sum_m']*abs($rs['user_ds'])/100;$z_userds+=$rs['sum_m']*abs($rs['user_ds'])/100;}?></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc" class="STYLE3" style="color:<?php if ((-$rs['sum_m']+$rs['sum_m']*abs($rs['user_ds'])/100)>0) echo 'black'; else echo 'red'; ?>;"><?php if ($rs['bm']==2){echo 0;}elseif($rs['bm']==1){echo $rs['sum_m']*$rs['rate']-$rs['sum_m']+$rs['sum_m']*abs($rs['user_ds'])/100;$z_user+=$rs['sum_m']*$rs['rate']-$rs['sum_m']+$rs['sum_m']*abs($rs['user_ds'])/100;}else{echo -$rs['sum_m']+$rs['sum_m']*abs($rs['user_ds'])/100;$z_user+=-$rs['sum_m']+$rs['sum_m']*abs($rs['user_ds'])/100;}?></td>
      <td align="center" nowrap="nowrap" bordercolor="cccccc"><?php if ($rs['bm']==2){echo 0;}else{echo $rs['sum_m']*abs($rs['dai_ds']-$rs['user_ds'])/100*(10-$rs['dai_zc'])/10;$z_daids+=$rs['sum_m']*abs($rs['dai_ds']-$rs['user_ds'])/100*(10-$rs['dai_zc'])/10;}?></td>
      <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc" class="STYLE3" style="color:<?php if (($rs['sum_m']*Abs($rs['dai_ds']-$rs['user_ds'])/100+$rs['sum_m']*$rs['dai_zc']/10-$rs['sum_m']*($rs['dai_zc'])/10*$rs['dai_ds']/100)>0) echo 'black'; else echo 'red'; ?>;"><?php if ($rs['bm']==2){echo 0;}elseif($rs['bm']==1){echo $rs['sum_m']*$rs['dai_zc']/10-$rs['sum_m']*$rs['rate']*$rs['dai_zc']/10+$rs['sum_m']*($rs['dai_ds']-$rs['user_ds'])/100*(10-$rs['dai_zc'])/10-$rs['sum_m']*$rs['user_ds']/100*($rs['dai_zc'])/10;
		   
$z_dai+=$rs['sum_m']*$rs['dai_zc']/10-$rs['sum_m']*$rs['rate']*$rs['dai_zc']/10+$rs['sum_m']*($rs['dai_ds']-$rs['user_ds'])/100*(10-$rs['dai_zc'])/10-$rs['sum_m']*$rs['user_ds']/100*($rs['dai_zc'])/10;
		   }else{
		   echo $rs['sum_m']*Abs($rs['dai_ds']-$rs['user_ds'])/100+$rs['sum_m']*$rs['dai_zc']/10-$rs['sum_m']*($rs['dai_zc'])/10*$rs['dai_ds']/100;
		   
$z_dai+=$rs['sum_m']*Abs($rs['dai_ds']-$rs['user_ds'])/100+$rs['sum_m']*$rs['dai_zc']/10-$rs['sum_m']*($rs['dai_zc'])/10*$rs['dai_ds']/100;
		   
		   
		   }?>      </td>
    </tr>
  </form>
  <?php }?>
  <tr >
    <td height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" >&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3">�ܼ�</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3"><?php echo $z_re?>
      ע </td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3"><?php echo $z_sum?>    </td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3">&nbsp;</td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3"><?php echo number_format($z_userds,2)?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3" style="color:<?php if ($z_user>0) echo 'black'; else echo 'red'; ?>;"><?php echo number_format($z_user,2)?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3"><?php echo number_format($z_daids,2)?></td>
    <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA" class="STYLE3" style="color:<?php if ($z_dai>0) echo 'black'; else echo 'red'; ?>;"><?php echo number_format($z_dai,2)?></td>
  </tr>
</table>
