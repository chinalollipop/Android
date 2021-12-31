<?php
if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}
 if (strpos($_SESSION['flag'],'08') ){}else{ 
echo "<center>��û�и�Ȩ�޹���!</center>";
exit;}

 if ($_GET['sdel']!=""){
   $dell=$_GET['sdel'];
   $username1=$_GET['username1'];
	mysqli_query($dbMasterLink,"Delete from ka_tan where username='$username1'");
	mysqli_query($dbMasterLink,"Delete from ka_mem where id='$sdel'");
	mysqli_query($dbMasterLink,"Delete from ka_quota where flag=1  and userid='$sdel'");
    echo("<script type='text/javascript'>alert('ɾ���ɹ���');history.back();</script>"); 
 }


//״̬
 if ($_GET['t1']=="��") {
 
$sql="update ka_mem set stat=0 where id=".$_GET['newsid'];
$exe=mysqli_query($dbLink,$sql) or  die("���ݿ��޸ĳ���");
echo "<script>alert('�޸��û�[<".$_GET['name'].">]Ϊ������!');history.back();</script>"; 
exit;

}
if ($_GET['t1']=="��") {
$sql="update ka_mem set stat=1 where id=".$_GET['newsid'];
$exe=mysqli_query($dbLink,$sql) or  die("���ݿ��޸ĳ���");
echo "<script>alert('�޸��û�[<".$_GET['name'].">]Ϊ��ֹ!');history.back();</script>"; 
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


if ($_GET['ids']!=""){$ids=$_GET['ids'];}else{$ids=0;}
if ($ids==0){

if ($_POST['ids']!=0){$ids=$_POST['ids'];}else{$ids=0;}
}

if ($_GET['ids1']!=""){$ids1=$_GET['ids1'];}else{$ids1=0;}
if ($ids1==0){

if ($_POST['ids1']!=0){$ids1=$_POST['ids1'];}else{$ids1=0;}
}


if ($_GET['ids2']!=""){$ids2=$_GET['ids2'];}else{$ids2=0;}
if ($ids2==0){

if ($_POST['ids2']!=0){$ids2=$_POST['ids2'];}else{$ids2=0;}
}

$result=mysqli_query($dbLink,"select * from ka_guan where id=".$ids." and lx=1 order by id"); 
$row11=mysqli_fetch_assoc($result);
if ($row11!=""){
$guan="[".$row11['kauser']."��]";

}
$result=mysqli_query($dbLink,"select * from ka_guan where id=".$ids1." and lx=2 order by id"); 
$row11=mysqli_fetch_assoc($result);
if ($row11!=""){
$zong="[".$row11['kauser']."��]";

}
$result=mysqli_query($dbLink,"select * from ka_guan where id=".$ids2." and lx=3 order by id"); 
$row11=mysqli_fetch_assoc($result);
if ($row11!=""){
$dan="[".$row11['kauser']."��]";

}




 
?>

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" src="tip.js?v=<?php echo AUTOVER; ?>"></script>

<SCRIPT language=JAVASCRIPT>

if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>

<style type="text/css">
<!--
.STYLE1 {color: #FFFFFF}
-->
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr class="tbtitle">
    <form action="index.php?action=kamem" method="post" name="form3" id="form3">
      <td width="35%"><font color="ffffff">�ɶ�
        <SELECT name=ids class=zaselect_ste id="ids" 
            onchange=self.form3.submit()>
            
			 <option value="0" <?php if ($ids==0) {?>selected="selected"<?php }?>>ȫ��</option>  
			<?php $result = mysqli_query($dbLink,"select * from ka_guan where lx=1  order by id desc");   
while($image = mysqli_fetch_assoc($result)){
?> 
			 
			  <option value="<?php echo $image['id']?>" <?php if ($ids==$image['id']) {?>selected="selected"<?php }?>><?php echo $image['kauser']?></option>
			    <?php }?>
				
			
		</SELECT>--�ܴ�<SELECT name=ids1 class=zaselect_ste id="ids1" 
            onchange=self.form3.submit()>
            
			 <option value="0" <?php if ($ids1==0) {?>selected="selected"<?php }?>>ȫ��</option>  
			<?php $result = mysqli_query($dbLink,"select * from ka_guan where lx=2  order by id desc");   
while($image = mysqli_fetch_assoc($result)){
?> 
			 
			  <option value="<?php echo $image['id']?>" <?php if ($ids1==$image['id']) {?>selected="selected"<?php }?>><?php echo $image['kauser']?></option>
			    <?php }?>
				
			
			</SELECT>--����<SELECT name=ids2 class=zaselect_ste id="ids2" 
            onchange=self.form3.submit()>
            
			 <option value="0" <?php if ($ids2==0) {?>selected="selected"<?php }?>>ȫ��</option>  
			<?php $result = mysqli_query($dbLink,"select * from ka_guan where lx=3  order by id desc");   
while($image = mysqli_fetch_assoc($result)){
?> 
			 
			  <option value="<?php echo $image['id']?>" <?php if ($ids2==$image['id']) {?>selected="selected"<?php }?>><?php echo $image['kauser']?></option>
			    <?php }?>
				
			
			</SELECT>
      </font></td>
    </form>
    <td width="26%"><table>
      <form  action="index.php?action=kamem&amp;id=0" method="post" name="regstep1" id="regstep1">
        <tr>
          <td colspan="2" align="center" nowrap="nowrap"><p align="right" class="STYLE1">��Ա�˺ţ�</p></td>
          <td align="center" colspan="6"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><input name="key" type="text" class="input1" id="key" size="10" /></td>
                <td width="80" align="center"><input type="submit" value="ȷ������" name="B1"   class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;></td>
                <td>&nbsp;</td>
              </tr>
          </table></td>
        </tr>
      </form>
    </table></td>
    <td width="39%"><div align="right">
      <button onclick="javascript:location.href='index.php?action=kamem&amp;id=0'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle" /><font <?php if ($id==0) {?>color=ff0000<?php }else{?>color=000000<?php }?>>���л�Ա</font></button>&nbsp;<button onclick="javascript:location.href='index.php?action=kamem&amp;id=2'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle" /><font <?php if ($id==2) {?>color=ff0000<?php }else{?>color=000000<?php }?>>����
      
        </font></button>&nbsp;<button onclick="javascript:location.href='index.php?action=kamem&amp;id=1'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle" /><font <?php if ($id==1) {?>color=ff0000<?php }else{?>color=000000<?php }?>>
        <?php?>
        ��ֹ
        
        </font></button>&nbsp;<button onclick="javascript:location.href='index.php?action=mem_add'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:80;height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle" />��ӻ�Ա</button>
    </div></td>
  </tr>
  <tr >
    <td height="5" colspan="3"></td>
  </tr>
</table><table border="1" align="center" cellspacing="1" height="84" cellpadding="5" bordercolordark="#FFFFFF" bordercolor="f1f1f1" width="99%">
             <form name="form1" method="post" action="index.php?action=kamem"> <tr> 
            <td width="50" height="28" bordercolor="cccccc" bgcolor="#FDF4CA"> 
              <div align="center">���</div></td>
            <td bordercolor="cccccc" bgcolor="#FDF4CA"> 
              <div align="center">�˺�</div>            </td>
            <td width="50" align="center" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">���ö��/���</td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">�ܴ�</td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">�ɶ�</td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">��%</td>
            <td align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">��%</td>
            <td width="30" align="center" bordercolor="cccccc" bgcolor="#FDF4CA">��%</td>
            <td width="40" align="center" bordercolor="cccccc" bgcolor="#FDF4CA">��˾%</td>
            <td width="40" align="center" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
            <td width="30" align="center" bordercolor="cccccc" bgcolor="#FDF4CA">״��</td>
            <td align="center" bordercolor="cccccc" bgcolor="#FDF4CA">ע��ʱ��</td>
            <td width="50" align="center" bordercolor="cccccc" bgcolor="#FDF4CA">��¼����</td>
            <td nowrap bordercolor="cccccc" bgcolor="#FDF4CA"> 
              <div align="center">����</div>            </td>
          </tr>
		  
		   <?php

$xc=$_POST['xc'];
$key=$_POST['key'];
if ($xc==""){$xc=$_GET['xc'];}
if ($key==""){$xc=$_GET['key'];}

$vvv=" where 3=3 ";
$vvvv="&id=".$id."";
if ($key<>""){$vvv.=" and kauser LIKE '%$key%'  ";

$vvvv.="&key=".$key."";}

if ($id==1){$vvv.=" and stat=1  ";}
if ($id==2){$vvv.=" and stat=0  ";}
if ($ids<>0){$vvv.=" and guanid=".$ids."  ";
$vvvv.="&ids=".$ids."";
}
if ($ids1<>0){$vvv.=" and zongid=".$ids1."  ";
$vvvv.="&ids1=".$ids1."";
}

if ($ids2<>0){$vvv.=" and danid=".$ids2."  ";
$vvvv.="&ids2=".$ids2."";
}



$result = mysqli_query($dbLink,"select count(*) from ka_mem  ".$vvv."  order by id desc");   
$num = mysql_result($result,"0");
if(!$num){
     //cpmsg("Ŀǰû�пɱ༭��ͼƬ");
	// exit;
	echo "<tr align=center><td colspan=16>��������</td></tr>";
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

$multipage = cpmulti($num, $perpage, $curpage, "?action=kamem".$vvvv."");
?>
<?php
$jj=0;
$result = mysqli_query($dbLink,"select * from ka_mem  ".$vvv."  order by id desc limit $start_limit, $perpage");   
while($image = mysqli_fetch_assoc($result)){
$jj+=1;


	
	 
	


?>
		  
		  


          <tr> 
            <td height="25" bordercolor="cccccc"> 
              <div align="center"><?php echo $jj?></div>            </td>
            <td height="25" align="center" bordercolor="cccccc"> 
            <?php echo $image['kauser']?>         </td>
            <td height="25" align="center" bordercolor="cccccc"><?php echo $image['xm']?> </td>
            <td height="25" align="center" bordercolor="cccccc">
			<?php echo $image['cs']?> /<font color=ff0000><?php echo $image['ts']?> </font>			</td>
            <td align="center" bordercolor="cccccc">
		<button class=headtd4  onmouseover="this.className='headtd3';window.status='��Ա����'; return true;" onMouseOut="this.className='headtd4';window.status='��Ա����';return true;"  onClick="javascript:location.href='index.php?action=kamem&ids2=<?php echo $image['danid']?>'"><?php echo $image['dan']?></button>			</td>
            <td align="center" bordercolor="cccccc">
			<button class=headtd4  onmouseover="this.className='headtd3';window.status='��Ա����'; return true;" onMouseOut="this.className='headtd4';window.status='��Ա����';return true;"  onClick="javascript:location.href='index.php?action=kamem&ids1=<?php echo $image['zongid']?>'"><?php echo $image['zong']?></button>			  </td>
            <td height="25" align="center" bordercolor="cccccc">
			<button class=headtd4  onmouseover="this.className='headtd3';window.status='��Ա����'; return true;" onMouseOut="this.className='headtd4';window.status='��Ա����';return true;"  onClick="javascript:location.href='index.php?action=kamem&ids=<?php echo $image['guanid']?>'"><?php echo $image['guan']?></button>			  </td>
            <td align="center" bordercolor="cccccc"><?php echo $image['dan_zc']*10?>%</td>
            <td height="25" align="center" bordercolor="cccccc"><?php echo $image['zong_zc']*10?>%</td>
            <td height="25" align="center" bordercolor="cccccc"><?php echo $image['guan_zc']*10?>%</td>
            <td align="center" bordercolor="cccccc"><?php echo $image['dagu_zc']*10?>%</td>
            <td align="center" bordercolor="cccccc"><?php echo $image['abcd']?>��&nbsp;</td>
            <td height="25" align="center" bordercolor="cccccc">
			<a href="index.php?action=kamem&newsid=<?php echo $image['id']?>&amp;t1=<?php if($image['stat']==0){ ?>��<?php }else{?>��<?php }?>&amp;id=<?php echo $image['id']?>&amp;ids=<?php echo $ids?>&amp;page=<?php echo $curpage?>&amp;name=<?php echo $image['kauser']?>&amp;key=<?php echo $_POST['key']?>"><img src="images/<?php if ($image['stat']==0){ ?>icon_21x21_selectboxon.gif<?php }else{ ?>icon_21x21_selectboxoff.gif<?php }?>" name="test_b<?php echo $image['id']?>" width="21" height="21" border="0" id="test_b<?php echo $image['id']?>"  value="<?php if($image['stat']==0){ ?>True<?php }else{?>False<?php }?>" ></a>			</td>
            <td height="25" align="center" bordercolor="cccccc"><?php echo $image['adddate']?></td>
            <td height="25" align="center" bordercolor="cccccc"><?php echo $image['look']?></td>
            <td nowrap bordercolor="cccccc"> 
              <div align="center">
			
			 <button onClick="javascript:location.href='index.php?action=mem_edit&id=<?php echo $image['id']?>'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:50;height:22" ;><img src="images/icon_21x21_edit01.gif" align="absmiddle">�޸�</button>
			   <button onClick="javascript:location.href='index.php?action=kamem&act=ɾ��&username1=&<?php echo $image['kauser']?>&page=<?php echo $curpage?>&id=<?php echo $image['id']?>&sdel=<?php echo $image['id']?>'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:50;height:22" ;><img src="images/icon_21x21_del.gif" align="absmiddle">ɾ��</button> 
</div>            </td>
          </tr>
          

<?php
}
?>
     
	 <tr>
            <td height="25" colspan="16" bordercolor="cccccc"><table width="98%" border="0" align="center" cellpadding="1" cellspacing="0" bordercolordark="#FFFFFF" bordercolor="888888">
              <tr>
                <td height="26"><div align="left">
                    <input type="hidden" name="idtxt" />
                    <input name="id" type="hidden" id="id" value="<?php echo $image['id']?>" />
                    <?php if ($_POST['username']<>"") {?>
                    <input name="username" type="hidden" id="username" value="<?php echo $_POST['username']?>" />
                    <input name="xc" type="hidden" id="xc" value="<?php echo $_POST['xc']?>" />
                    <?php }?>
                  &nbsp;
                  <button onclick="javascript:location.reload();"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;><img src="images/icon_21x21_info.gif" align="absmiddle" />ˢ��</button>
                </div></td>
                <td height="26" ><div align="center"> <?php echo $multipage;?>&nbsp;</div></td>
                <td height="26" width="60"><div align="center">
                    <select name="page" onchange="location.href='index.php?action=kamem&amp;id=<?php echo $id?><?php if ($_POST['username']<>"") {?>&amp;username=<?php echo $username?>&amp;xc=<?php echo $xc?><?php }?>&amp;page='+this.options[selectedIndex].value">
                      <?php for($i =1; $i <= $pagesa; $i++) {?>
                      <option value="<?php echo $i?>" <?php if ($curpage==$i){ ?>selected<?php }?>>��
                        <?php echo $i?>
                        ҳ</option>
                      <?php }?>
                    </select>
                </div></td>
              </tr>
            </table></td>
            </tr>   </form></table>


