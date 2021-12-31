<?php
if(!defined('PHPYOU_VER')) {
	exit('�Ƿ�����');
}

if ($_POST['sdel']!=""){
    $del_num=count($_POST['sdel']); 
   for($i=0;$i<$del_num;$i++){ 
    mysqli_query($dbMasterLink,"Delete from ka_kithe where id='$sdel[$i]'"); 
             }  
    echo("<script type='text/javascript'>alert('ɾ���ɹ���');history.back();</script>"); 
 }
 
 if ($_GET['sdel']!=""){
   $dell=$_GET['sdel'];
    mysqli_query($dbMasterLink,"Delete from ka_kithe where id='$dell'"); 
    echo("<script type='text/javascript'>alert('ɾ���ɹ���');history.back();</script>"); 
 }
 //״̬
 if ($_GET['t0']=="��") {
$sql="update ka_kithe set lx=1 where id=".$_GET['newsid'];
$exe=mysqli_query($dbMasterLink,$sql) or  die("���ݿ��޸ĳ���");
echo "<script>alert('�޸ĵ�[<".$_GET['name'].">]��Ϊ��ʾ���û�Ա������Բ鿴���ݣ�!');history.back();</script>"; 
exit;

}
if ($_GET['t0']=="��") {
$sql="update ka_kithe set lx=0 where id=".$_GET['newsid'];
$exe=mysqli_query($dbMasterLink,$sql) or  die("���ݿ��޸ĳ���");
echo "<script>alert('�޸ĵ�[<".$_GET['name'].">]��Ϊ���أ��û�Ա����鿴��������!');history.back();</script>"; 
exit;

}



 // ��ҳ
function cpmulti($num, $perpage, $curpage, $mpurl) {
	$multipage = '';
	$mpurl .= '&';
	if($num > $perpage) {
		$page = 10;
		$offset = 5;
		$pages = @ceil($num / $perpage);
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $curpage + $page - $offset - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $curpage - $pages + $to;
				$to = $pages;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$from = $pages - $page + 1;
				}
			}
		}


$multipage= ($curpage >= 0 ? '<a href="'.$mpurl.'page=1" class="p_redirect"><img src="images/prev_top.gif" border="0" align="absmiddle"></a>&nbsp;' : '');
		$multipage.= ( $curpage<=1 ? '<a href="'.$mpurl.'page=1" class="p_redirect"><img src="images/prev.gif"  border="0" align="absmiddle"></a>&nbsp;' : '').($curpage > 1 ? '<a href="'.$mpurl.'page='.($curpage - 1).'" class="p_redirect"><img src="images/prev.gif"  border="0" align="absmiddle"></a>&nbsp;' : '');
		
		
		
		$multipage .= ($curpage < $pages ? '<a href="'.$mpurl.'page='.($curpage + 1).'" class="p_redirect"><img src="images/next.gif" align="absmiddle" border="0" ></a>&nbsp;' : '').($to == $curpage ? '<a href="'.$mpurl.'page='.$pages.'" class="p_redirect"><img src="images/next.gif" align="absmiddle" border="0" ></a>&nbsp;' : '');
		
		$multipage .=  ($curpage >= 0 ? '<a href="'.$mpurl.'page='.$to.'" class="p_redirect"><img border="0" src="images/prev_end.gif" align="absmiddle"></a>' : '');
		
		
		$multipage = $multipage ? '�ܼ�:'.$num.'��&nbsp;&nbsp;��'.$to.'ҳ&nbsp;&nbsp;��ǰ:<font color=ff0000>'.$curpage.'ҳ</font>  &nbsp;&nbsp;'.$multipage.'&nbsp; ' : '';
	}
	return $multipage;
}
if ($_GET['id']!=""){$id=$_GET['id'];}else{$id=0;}

?>

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" src="tip.js?v=<?php echo AUTOVER; ?>"></script>

<SCRIPT language=JAVASCRIPT>

if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>

<div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr class="tbtitle">
    <td width="23%"><?php require_once '1top.php';?></td>
	<td width="29%"><table>
      <form  action="index.php?action=kakithe&amp;id=0" method="post" name="regstep1" id="regstep1">
        <tr>
          <td colspan="2" align="center" nowrap="nowrap"><p align="right"><font color="#FFFFFF">������</font></p></td>
          <td align="center" colspan="6"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><input name="key" type="text" class="input1" id="key" size="20" /></td>
                <td width="80" align="center"><input type="submit" value="ȷ������" name="B1"   class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;></td>
                <td>&nbsp;</td>
              </tr>
          </table></td>
        </tr>
      </form>
	  </table><td width="20%">&nbsp;</td><td width="1%"></td>
	<td width="27%"><div align="right">
      <button onclick="javascript:location.href='index.php?action=kakithe&amp;id=0'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle" /><font <?php if ($id==0) {?>color=ff0000<?php }else{?>color=000000<?php }?>>��������</font></button>&nbsp;<button onclick="javascript:location.href='index.php?action=kakithe&amp;id=2'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle" /><font <?php if ($id==2) {?>color=ff0000<?php }else{?>color=000000<?php }?>>��ʾ</font></button>&nbsp;<button onclick="javascript:location.href='index.php?action=kakithe&amp;id=1'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle" /><font <?php if ($id==1) {?>color=ff0000<?php }else{?>color=000000<?php }?>>����</font></button>&nbsp;</div></td>
  </tr>
  <tr >
    <td height="5" colspan="3"></td>
    </tr>
</table>

 
  
    <table width="99%" height="83" border="1" align="center" cellpadding="5" cellspacing="1"  bordercolor="f1f1f1" bgcolor="ffffff">
       <tr>
	     <td colspan="2" align="center" bgcolor="#FDF4CA">���ϲ�</td>
	   </tr>
      <form name="form1" method="post" action="index.php?action=kakithe">
      <tr >
        <td width="50" height="28" rowspan="2" bordercolor="cccccc" bgcolor="#FDF4CA"><div align="center">
          <input type="checkbox" name="sele" value="checkbox" onclick="javascript:checksel(this.form);" />
        </div></td>
        <td width="60" rowspan="2" bordercolor="cccccc" bgcolor="#FDF4CA"><div align="center">����</div></td>
        <td width="135" rowspan="2" align="center" bordercolor="cccccc" bgcolor="#FDF4CA">����ʱ��</td>
        <td colspan="7" align="center" bordercolor="cccccc" bgcolor="#FDF4CA">�������</td>
        <td width="95" rowspan="2" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">��Ф</td>
        <td width="80" rowspan="2" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">�Ƿ񿪽� </td>
        <td width="50" rowspan="2" align="center" bordercolor="cccccc" bgcolor="#FDF4CA">״̬</td>
        <td width="150" rowspan="2" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"><div align="center">����</div></td>
      </tr>
      <tr >
        <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">ƽ1</td>
        <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">ƽ2</td>
        <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">ƽ3</td>
        <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">ƽ4</td>
        <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">ƽ5</td>
        <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">ƽ6</td>
        <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
      </tr>
      <?php

$xc=$_POST['xc'];
$key=$_POST['key'];
if ($xc==""){$xc=$_GET['xc'];}
if ($key==""){$xc=$_GET['key'];}

$vvv=" where na<>0 ";
$vvvv="&id=".$id."";
if ($key<>""){$vvv.=" and nn LIKE '%$key%'  ";

$vvvv.="&key=".$key."";}

if ($id==1){$vvv.=" and lx=0  ";}
if ($id==2){$vvv.=" and lx=1  ";}




$result = mysqli_query($dbLink,"select count(*) from ka_kithe  ".$vvv."  order by id desc");   
$num = mysql_result($result,"0");
if(!$num){
     //cpmsg("Ŀǰû�пɱ༭��ͼƬ");
	// exit;
	echo "<tr align=center><td colspan=14>��������</td></tr>";
}
$curpage = intval($page);
$perpage = 10;
$pagesa = @ceil($num / $perpage);
if($curpage) {
	$start_limit = ($curpage - 1) * $perpage;
} else {
	$start_limit = 0;
	$curpage = 1;
}

$multipage = cpmulti($num, $perpage, $curpage, "?action=kakithe".$vvvv."");
?>
      <?php
$result = mysqli_query($dbLink,"select * from ka_kithe  ".$vvv."  order by id desc limit $start_limit, $perpage");   
while($image = mysqli_fetch_assoc($result)){
?>
      <tr>
        <td height="25" bordercolor="cccccc" bgcolor="#FFFFFF"><div align="center">
          <input name="sdel[]" type="checkbox" id="sdel[]" value="<?php echo $image['id']?>" <?php if($image['score']==1){ ?>disabled="disabled"<?php } ?> />
        </div></td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="#FFFFFF"><?php echo $image['nn']?>        </td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="#FFFFFF"><?php echo $image['nd']?></td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="f1f1f1"><img src="images/num<?php echo $image['n1']?>.gif" /></td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="f1f1f1"><img src="images/num<?php echo $image['n2']?>.gif" /></td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="f1f1f1"><img src="images/num<?php echo $image['n3']?>.gif" /></td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="f1f1f1"><img src="images/num<?php echo $image['n4']?>.gif" /></td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="f1f1f1"><img src="images/num<?php echo $image['n5']?>.gif" /></td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="f1f1f1"><img src="images/num<?php echo $image['n6']?>.gif" /></td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="f1f1f1"><img src="images/num<?php echo $image['na']?>.gif" /></td>
        <td align="center" bordercolor="cccccc" bgcolor="#FFFFFF"><?php echo $image['x1']?><?php echo $image['x2']?><?php echo $image['x3']?><?php echo $image['x4']?><?php echo $image['x5']?><?php echo $image['x6']?>+<?php echo $image['sx']?></td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="#FFFFFF"><?php
			if ($image['na']<>0){
			 echo "�ѿ���";
			 }else{
			  echo "<font color=ff0000>δ����</font>";
			 }?>        </td>
        <td height="25" align="center" bordercolor="cccccc" bgcolor="#FFFFFF"><?php
			if ($image['n1']<>0){
			?>
            <a href="index.php?action=kakithe&amp;newsid=<?php echo $image['id']?>&amp;t0=<?php if($image['lx']==1){ ?>��<?php }else{?>��<?php }?>&amp;id=<?php echo $image['id']?>&amp;ids=<?php echo $ids?>&amp;page=<?php echo $curpage?>&amp;name=<?php echo $image['nn']?>&amp;key=<?php echo $_POST['key']?>"><img src="images/<?php if ($image['lx']==1){ ?>icon_21x21_selectboxon.gif<?php }else{ ?>icon_21x21_selectboxoff.gif<?php }?>" name="test_b<?php echo $image['id']?>" width="21" height="21" border="0" id="test_b<?php echo $image['id']?>"  value="<?php if($image['lx']==1){ ?>True<?php }else{?>False<?php }?>"></a>
            <?php }else{?>
            <font color="ff0000">δ����</font>
            <?php }?>        </td>
        <td nowrap="nowrap" bordercolor="cccccc" bgcolor="#FFFFFF"><div align="center">
            <button onclick="javascript:location.href='index.php?action=kawin&amp;kithe=<?php echo $image['nn']?>'" <?php if($image['score']==1){ ?>disabled="disabled"<?php } ?>  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:50;height:22" ;><img src="images/banner.gif" width="16" height="16" align="absmiddle" />����</button>&nbsp;<button onclick="javascript:location.href='index.php?action=kithe_edit&amp;id=<?php echo $image['id']?>'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:50;height:22" <?php if($image['score']==1){ ?>disabled="disabled"<?php } ?>><img src="images/icon_21x21_edit01.gif" align="absmiddle" />�޸�</button>&nbsp;<button onclick="javascript:location.href='index.php?action=kakithe&amp;act=ɾ��&amp;page=<?php echo $curpage?>&amp;id=<?php echo $image['id']?>&amp;sdel=<?php echo $image['id']?><?php if ($username<>""){ ?>&amp;username=<?php echo $username?>&amp;xc=<?php echo $xc?><?php }?>'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:50;height:22" <?php if($image['score']==1){ ?>disabled="disabled"<?php } ?>><img src="images/icon_21x21_del.gif" align="absmiddle" />ɾ��</button>
        </div></td>
      </tr>
      <?php
}
?>
      <tr>
        <td height="25" colspan="14" bordercolor="cccccc" bgcolor="#FFFFFF"><table width="98%" border="0" align="center" cellpadding="2" cellspacing="0" bordercolordark="#FFFFFF" bordercolor="888888">
            <tr>
              <td width="180" height="26" nowrap="nowrap"><div align="left">
                  <input type="hidden" name="idtxt" />
                  <input name="id" type="hidden" id="id" value="<%=id%>" />
                  <?php if ($_POST['username']<>"") {?>
                  <input name="username" type="hidden" id="username" value="<?php echo $_POST['username']?>" />
                  <input name="xc" type="hidden" id="xc" value="<?php echo $_POST['xc']?>" />
                  <?php }?>
                  <button onclick="submit()"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:95;height:22" ;><img src="images/icon_21x21_del.gif" align="absmiddle" />ɾ��ѡ������</button>&nbsp;<button onclick="javascript:location.reload();"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;><img src="images/icon_21x21_info.gif" align="absmiddle" />ˢ��</button>
              </div></td>
              <td height="26"><div align="center"> <?php echo $multipage;?>&nbsp;</div></td>
              <td height="26" width="60"><div align="center">
                  <select name="page" onchange="location.href='index.php?action=kakithe&amp;id=<?php echo $id?><?php if ($_POST['username']<>"") {?>&amp;username=<?php echo $username?>&amp;xc=<?php echo $xc?><?php }?>&amp;page='+this.options[selectedIndex].value">
                    <?php for($i =1; $i <= $pagesa; $i++) {?>
                    <option value="<?php echo $i?>" <?php if ($curpage==$i){ ?>selected<?php }?>>��
                      <?php echo $i?>
                      ҳ</option>
                    <?php }?>
                  </select>
              </div></td>
            </tr>
          </table>
        </td>
      </tr>  </form>
  </table>

  <table width="98%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="70"><div align="left"> </div></td>
    <td><div align="right" disabled="disabled"><img src="images/slogo_10.gif" width="15" height="11" align="absmiddle" /> ��ʾ������޸�ĳһ�ڵ��κ�һ����ź��������½�����ڣ�״̬��������˹�ǰ̨��Ա����ſ��Բ鿴���ڵ����ݷ����޷��鿴!</div></td>
  </tr>
</table>
</div>

