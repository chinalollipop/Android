<?php
if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}

if ($_POST['sdel']!=""){
    $del_num=count($_POST['sdel']); 
   for($i=0;$i<$del_num;$i++){ 
   
	mysqli_query($dbMasterLink,"Delete from ka_zi where id='$sdel[$i]'");
	
	 
             }  
    echo("<script type='text/javascript'>alert('ɾ���ɹ���');history.back();</script>"); 
 }
 
 if ($_GET['sdel']!=""){
   $dell=$_GET['sdel'];
  
	mysqli_query($dbMasterLink,"Delete from ka_zi where id='$sdel'");

	
    
    echo("<script type='text/javascript'>alert('ɾ���ɹ���');history.back();</script>"); 
 }
 
 
 //�޸���Ϣ
if ($_GET['act']=="�޸�") {
if (empty($_POST['kapassword1'])) {
       
  echo "<script>alert('���벻��Ϊ��!');window.history.go(-1);</script>"; 
  exit;
    }
$pass1 = md5($_POST['kapassword1']);


$sql="update ka_zi set kapassword='".$pass1."' where id=".$_POST['id'];
	
$exe=mysqli_query($dbLink,$sql) or  die("���ݿ��޸ĳ���");

echo "<script>alert('�޸ĳɹ�!');window.history.go(-1);</script>"; 
exit;
	}
	
	
	 //�޸���Ϣ
if ($_GET['act']=="���") {
if (empty($_POST['kauser1'])) {
       
  echo "<script>alert('�û�����Ϊ��!');window.history.go(-1);</script>"; 
  exit;
    }
if (empty($_POST['kapassword'])) {
       
  echo "<script>alert('���벻��Ϊ��!');window.history.go(-1);</script>"; 
  exit;
    }
	
	
	
$result1 = mysqli_query($dbLink,"Select Count(ID) As memnum2 From ka_zi Where kauser='".$_POST['kauser1']."' order by id desc");
$rsw = mysqli_fetch_assoc($result1);

if($rsw[0]!=0){
   echo "<script>alert('��һ�û������ѱ�ռ�ã���������룡!');window.history.go(-1);</script>"; 
  exit;
}

$result = mysqli_query($dbLink,"select count(*) from ka_mem  where kauser='".$_POST['kauser1']."'  order by id desc");   
$num = mysql_result($result,"0");

if($num!=0){
   echo "<script>alert('��һ�û������ѱ�ռ�ã���������룡!');window.history.go(-1);</script>"; 
  exit;
}
$result = mysqli_query($dbLink,"select count(*) from ka_guan  where kauser='".$_POST['kauser1']."'  order by id desc");   
$num = mysql_result($result,"0");

if($num!=0){
   echo "<script>alert('��һ�û������ѱ�ռ�ã���������룡!');window.history.go(-1);</script>"; 
  exit;
}
	

	
	$text=date("Y-m-d H:i:s"); 
 $pass = md5($_POST['kapassword']);
	$sql="INSERT INTO  ka_zi set kapassword='".$pass."',kauser='".$_POST['kauser1']."',guan='".$_POST['guan']."',guanid='".$_POST['guanid']."',adddate='".$text."',lx='".$_POST['lx']."'";
$exe=mysqli_query($dbMasterLink,$sql) or  die("���ݿ��޸ĳ���");
	echo "<script>alert('��ӳɹ�!');window.history.go(-1);</script>"; 
exit;
	}
	
	if ($_GET['ids']!=""){$ids=$_GET['ids'];}else{$ids=0;}
if ($ids==0){

if ($_POST['ids']!=0){$ids=$_POST['ids'];}else{$ids=0;}
}
 $result=mysqli_query($dbLink,"select * from ka_guan where id=".$ids."  order by id"); 
$row11=mysqli_fetch_assoc($result);
if ($row11!=""){
$glname="[".$row11['kauser']."]";

}
 ?>

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" src="tip.js?v=<?php echo AUTOVER; ?>"></script>
<style type="text/css">
<!--
.STYLE1 {color: #FF0000}
-->
</style>
<div align="center">
<link rel="stylesheet" href="xp.css?v=<?php echo AUTOVER; ?>" type="text/css">

<script src="inc/forms.js?v=<?php echo AUTOVER; ?>"></script>
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td><fieldset><legend><?php echo $glname?>���˺Ź���</legend> <br>
       
          <table border="1" align="center" cellspacing="0" cellpadding="5" bordercolor="888888" bordercolordark="#FFFFFF" width="98%">
            <form name="form1" method="post" action="index.php?action=kazi&act=���&ids=<?php echo $ids?>">
			
			
			 <tr>
              <td>                <div align="right">
                <input name="lx" type="hidden" id="lx" value="<?php echo $row11['lx']?>" />
                <input name="guanid" type="hidden" id="guanid" value="<?php echo $row11['id']?>" />
                <input name="guan" type="hidden" id="guan" value="<?php echo $row11['kauser']?>" />
                �û�����
                    <input name="kauser1" type="text" class="input1" id="kauser1" value="" size="15" >
                ���룺
                    <input name="kapassword" type="text"  class="input1" id="kapassword" value="" size="15" >
&nbsp; </div></td>
              <td width="100">
                <div align="center">
                  <button onClick="submit()"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:110" ;><img src="images/add.gif" align="absmiddle">ȷ�����</button>
              </div></td>
            </tr>  </form> 
          </table>
       
        <br>
      <table border="1" align="center" cellspacing="0" height="55" cellpadding="5" bordercolordark="#FFFFFF" bordercolor="888888" width="98%">
        <tr class="tbtitle"> 
          <td height="28" width="50"> 
            <div align="center">���</div>          </td>
          <td width="115"> 
            <div align="center">��������</div>          </td>
          <td width="191" align="center">����</td>
          <td align="center">��������</td>
          <td align="center">����ʱ��</td>
          <td> 
            <div align="center">����</div>          </td>
        </tr>
        
		<?php $result = mysqli_query($dbLink,"select * from ka_zi where guanid=".$ids."   order by id desc");   
while($image = mysqli_fetch_assoc($result)){?>
		
		
        <form name="form1" method="post" action="index.php?action=kazi&act=�޸�&ids=<?php echo $ids?>"><tr> 
          <td height="25"> 
            <div align="center"><?php echo $image['id']?></div>          </td>
          <td height="25"> 
            <div align="center"><?php echo $image['kauser']?>
              <input name="id" type="hidden" id="id" value="<?php echo $image['id']?>">
</div>          </td>
          <td height="25" align="center"><input name="kapassword1" type="text"  class="input1" id="kapassword1" value="" size="15">
            <span class="STYLE1">���޸�������</span></td>
          <td height="25" align="center"><?php echo $image['guan']?></td>
          <td height="25" align="center"><?php echo $image['adddate']?></td>
          <td width="140"> 
            <div align="center"> 
              <button onClick="submit()"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:55;height:22" ;><img src="images/icon_21x21_edit01.gif" width="16" height="16" align="absmiddle">�޸�</button> <button onClick="javascript:location.href='index.php?action=kazi&sdel=<?php echo $image['id']?>&ids=<?php echo $ids?>'"   class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:55;height:22" ;><img src="images/icon_21x21_del.gif" width="16" height="16" align="absmiddle">ɾ��</button>
            </div>          </td>
        </tr>
	  </form>
	  
	  
<?php }?> 
      </table>
      <div align="center"><br>
        <table width="98%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="70"> 
              <div align="left"> 
                <button onClick="javascript:location.href='index.php?action=kazi&ids=<?php echo $ids?>';"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;><img src="images/icon_21x21_info.gif" width="16" height="16" align="absmiddle">ˢ��</button>
              </div>
            </td>
            <td> 
              <div align="right" disabled><img src="images/slogo_10.gif" width="15" height="11" align="absmiddle"> 
              </div>
            </td>
          </tr>
        </table>
        <br>
      </div></fieldset>
    </td>
  </tr>
</table>

