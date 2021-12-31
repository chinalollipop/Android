<?php
if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}


//�޸���Ϣ
if ($_GET['act']=="���") {


if (empty($_POST['pass'])){
  echo "<script>alert('���벻��Ϊ�˿�!');window.history.go(-1);</script>"; 
  exit;
    }
if ($_POST['pass']!=$_POST['pass2']){
  echo "<script>alert('��������������벻һ��!');window.history.go(-1);</script>"; 
  exit;
    }
	
	 $pass = md5($_POST['pass']);
	 $sql="update  ka_admin set password='".$pass."' where username='".$_SESSION['jxadmin666']."'";
	
$exe=mysqli_query($dbLink,$sql) or  die("���ݿ��޸ĳ���");
	


	echo "<script>alert('�û��޸ĳɹ�!');window.location.href='index.php?action=modify_pass';</script>"; 
exit;
}
	

$result=mysqli_query($dbLink,"select * from ka_admin where username='".$_SESSION['jxadmin666']."' order by id desc LIMIT 1"); 
$row=mysqli_fetch_assoc($result);	

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
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
<form name="form1" method="post" action="index.php?action=modify_pass&act=���">
    <tr> 
      <td valign="top"><fieldset>
      <legend>��̨�û��޸�</legend> 
      <br>
        <div align="center"> 
          <table border="1" align="center" cellspacing="0" cellpadding="5" bordercolor="888888" bordercolordark="#FFFFFF" width="98%">
            <tr> 
              <td> 
                <div align="right"></div>
                <div align="right">
				 <button onClick="javascript:location.href='index.php?action=admin_main'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:80;height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle">�û�����</button>              
  &nbsp;<button onClick="javascript:location.href='index.php?action=admin_add'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:80;height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle">����û�</button>              

                </div>
              </td>
            </tr>
          </table>
          <br>
          <table border="1" align="center" cellspacing="0" cellpadding="5" bordercolor="888888" bordercolordark="#FFFFFF" width="98%">
            <tr> 
              <td> 
                <div align="left">
				
				
                  <table width="100%"  border="0" cellspacing="2" cellpadding="2">
                    <tr>
                      <td width="16%" height="30" align="right">�û�����</td>
                      <td width="84%"><?php echo $_SESSION['jxadmin666']?></td>
                    </tr>
                    <tr>
                      <td height="30" align="right">���룺</td>
                      <td><input name="pass" type="password" id="pass">
                        <span class="forumRow"></span></td>
                    </tr>
                    <tr>
                      <td height="30" align="right">�ڴ��������룺</td>
                      <td><input name="pass2" type="password" id="pass2"></td>
                    </tr>
                    <tr>
                      <td height="30">&nbsp;</td>
                      <td><br>
                        <table width="100" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td height="6"></td>
                          </tr>
                        </table>
                                         <button onClick="javascript:location.href='index.php?action=modify_pass&act=main'"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22";><img src="images/icon_21x21_info.gif" width="16" height="16" align="absmiddle">����</button>
                                         &nbsp;<button onClick="submit();"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:80;height:22" ;><img src="images/icon_21x21_copy.gif" width="16" height="16" align="absmiddle">�����Ա</button>
                                      &nbsp;<button onClick="javascript:location.reload();"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="width:60;height:22" ;><img src="images/icon_21x21_info.gif" width="16" height="16" align="absmiddle">ˢ��</button>
                        <br>
                        <table width="100" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td height="10"></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
</div>
                
              </td>
            </tr>
          </table>
          <br>
          <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr> 
              <td> 
                <div align="left"> </div>
              </td>
              <td> 
                <div align="right" disabled><img src="images/slogo_10.gif" width="15" height="11" align="absmiddle"> 
                  ������ʾ������޸�����,����������������һ����</div>
              </td>
            </tr>
          </table>
          <table width="100" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td height="10"></td>
            </tr>
          </table> 
        </div></fieldset>
      </td>
    </tr>
  </form></table>

</div>