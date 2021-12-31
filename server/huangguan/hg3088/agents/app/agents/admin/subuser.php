<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../../agents/include/address.mem.php");
require ("../../agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];

require ("../../agents/include/traditional.$langx.inc.php");
$addNew=$_REQUEST["addNew"];
$deluser=$_REQUEST["deluser"];
$edituser=$_REQUEST["edituser"];
$suspend=$_REQUEST["suspend"];
$username=$_REQUEST["username"];
$name=$_SESSION['UserName'];
$sort=$_REQUEST["sort"];
$orderby=$_REQUEST["orderby"];
$page=$_REQUEST["page"];
if ($sort==""){
	$sort='USERNAME';
}
if ($orderby==""){
	$orderby='ASC';
}
if ($page==''){
	$page=0;
}
$loginfo='查看子帐号';
if ($suspend=='N'){
    $loginfo='停用子帐号:'.$username.'';	
    $mysql="update ".DBPREFIX."web_system_data set Status=2 where ID=".$_REQUEST["id"];
    mysqli_query($dbMasterLink,$mysql);
}else if ($suspend=='Y'){
    $loginfo='启用子帐号:'.$username.'';	
    $mysql="update ".DBPREFIX."web_system_data set Status=0 where ID=".$_REQUEST["id"];
    mysqli_query($dbMasterLink,$mysql);
}
if ($deluser=='Y'){
    $loginfo='删除子帐号:'.$username.'';	
	$mysql="delete from ".DBPREFIX."web_system_data where ID=".$_REQUEST["id"];
	$result = mysqli_query($dbMasterLink,$mysql);
}
if ($edituser=='Y'){
	$e_user=substr($name,0,1).trim($_REQUEST["e_user"]);
	$e_pass=$_REQUEST["e_pass"];
	$e_alias=$_REQUEST["e_alias"];
	$mysql="update ".DBPREFIX."web_system_data set UserName='$e_user',PassWord='$e_pass',Alias='$e_alias' where ID=".$_REQUEST["id"];
	$result = mysqli_query($dbMasterLink,$mysql);
	$loginfo='修改子帐号:'.$e_user.' 密码:'.$e_pass.' 名称:'.$e_alias.'';
	echo "<script language=javascript>document.location='subuser.php?uid=$uid&langx=$langx&lv=$lv';</script>";
}
if ($addNew=='Y'){
	$new_user=substr($name,0,1).trim($_REQUEST["new_user"]);
	$new_pass=$_REQUEST["new_pass"];
	$new_alias=$_REQUEST["new_alias"];
	$AddDate=date('Y-m-d H:i:s');
	$mysql="select * from ".DBPREFIX."web_system_data where UserName='$new_user'";
	$result = mysqli_query($dbLink,$mysql);
	$cou=mysqli_num_rows($result);
	if ($cou==0){
	    $Competence = '1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,';
		$mysql="insert into ".DBPREFIX."web_system_data(Level,UserName,LoginName,PassWord,Alias,Status,SubName,AddDate,Competence) values('$lv','$new_user','$new_user','$new_pass','$new_alias','0','$name','$AddDate','$Competence')";
		echo $mysql;
		exit;
		mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
		$loginfo='新增子帐号:'.$new_user.' 密码:'.$new_pass.' 名称:'.$new_alias.'';
		echo "<Script Language=javascript>self.location='subuser.php?uid=$uid&langx=$langx&lv=$lv';</script>";
	}else{
		$msg=wterror('您添加的子帐号已经存在，请重新输入！！');
		echo $msg;
	}	
}
$sql = "select * from ".DBPREFIX."web_system_data where subname='$name' order by ".$sort." ".$orderby;
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$page_size=20;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
//echo $mysql;
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);
if ($cou==0){
	$page_count=1;
}
?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        .m_tab_ed .m_title_edit{ background-image: none;}
    </style>
</head>
<body onLoad="onLoad()"; onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<!-- 顶部表单 -->
<dl class="main-nav"><dt>子帐号</dt>
    <dd>
    <table >
    <form name="myFORM" action="subuser.php?&uid=<?php echo $uid?>&level=<?php echo $level?>&langx=<?php echo $langx?>" method=POST>
    <tr class="m_tline">
            <td>&nbsp;&nbsp;
              <?php echo $Mem_radio_Order_by?>:
              <select name="sort" onChange="self.myFORM.submit();" class="za_select">
               <option label="<?php echo $Mem_Account?>" value="USERNAME" selected="selected"><?php echo $Mem_Account?></option>
               <option label="<?php echo $Mem_Add?><?php echo $Mem_Date?>" value="ADDDATE"><?php echo $Mem_Add?><?php echo $Mem_Date?></option>

              </select>
              <select name="orderby" onChange="self.myFORM.submit()" class="za_select">
               <option label="<?php echo $Mem_ASC?>" value="ASC" selected="selected"><?php echo $Mem_ASC?></option>
               <option label="<?php echo $Mem_DESC?>" value="DESC"><?php echo $Mem_DESC?></option>
              </select>

                -- <?php echo $Mem_Totalpage?> :
              <select name="page" onChange="self.myFORM.submit()" class="za_select">
              <?php
              for($i=0;$i<$page_count;$i++){
                  echo "<option value='$i'>".($i+1)."</option>";
                 }
              ?>              </select>

                / <?php echo $page_count?>  <?php echo $Mem_Page?>  --

              <input type=BUTTON name="append" value="<?php echo $Mem_Add?>" onClick="show_win();" class="za_button">
            </td>

          </tr>
        </form>
    </table>
    </dd>
</dl>
<!-- 主体表格 -->
<div class="main-ui">
    <table class="m_tab">
      <tr class="m_title">
        <td width="150"><?php echo $Mem_Account?></td>
        <td width="150"><?php echo $Mem_Password?></td>
        <td width="150"><?php echo $Mem_Name?></td>
        <td width="150"><?php echo $Mem_Date?></td>
        <td width="80"><?php echo $Mem_Account?><?php echo $Mem_Status?></td>
        <td width="180"><?php echo $Mem_Function?></td>
      </tr>
    <?php
    $cou=mysqli_num_rows($result);
    if ($cou==0){
    ?>
         <FORM NAME="AG_<?php echo $row['ID']?>" ACTION="" METHOD=POST target='_self'>
         <INPUT TYPE="HIDDEN" NAME="id" value="<?php echo $row['ID']?>">
         <INPUT TYPE="HIDDEN" NAME="edituser" value="Y">

        <tr  class="m_cen" >
          <td>
            <font color="Black"><?php echo substr($name,0,1)?></font><input type="text" name="e_user" value="<?php echo $sub_message?>" size="8" class="za_text" >      </td>
          <td>
            <input type="text" name="e_pass" value="" size="8" class="za_text">      </td>
          <td>
            <input type="text" name="e_alias" value="" size="8" class="za_text">      </td>
          <td></td>
          <td align="left"></td>
          <td align="left"></td>
        </tr>
        </FORM>
    <?php
    }else{
    while ($row = mysqli_fetch_array($result)){
    ?>
         <FORM NAME="AG_<?php echo $row['ID']?>" ACTION="" METHOD=POST target='_self'>
         <INPUT TYPE="HIDDEN" NAME="id" value="<?php echo $row['ID']?>">
         <INPUT TYPE="HIDDEN" NAME="edituser" value="Y">
        <tr  class="m_cen" >
          <td>
            <font color="Black"><?php echo substr($name,0,1)?></font><input type="text" name="e_user" value="<?php echo substr($row['UserName'],1,strlen($row['UserName']))?>" size="8" class="za_text" >
         </td>
          <td>
            <input type="text" name="e_pass" value="<?php echo $row['PassWord']?>" size="8" class="za_text">      </td>
          <td>
            <input type="text" name="e_alias" value="<?php echo $row['Alias']?>" size="8" class="za_text">      </td>
          <td><?php echo $row['AddDate']?></td>
          <td>
    <?php
    if ($row['Status']==2){
    ?>
            <SPAN STYLE='background-color: Red;'><?php echo $Mem_Disable?></SPAN>
    <?php
    }else{
    ?>
            <?php echo $Mem_Enable?>
    <?php
    }
    ?>
          </td>
          <td align="left">
          <a onClick="javascript:ChkData('<?php echo $row['ID']?>')" style="cursor:hand;"><?php echo $Mem_Edit?></a> /
    <?php
    if ($row['Status']==0){
    ?>
          <a href="javascript:CheckSUSPEND('subuser.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>&suspend=N&id=<?php echo $row['ID']?>&username=<?php echo $row['UserName']?>')"><?php echo $Mem_Disable?></a> /
    <?php
    }else if ($row['Status']==2){
    ?>
          <a href="javascript:CheckSUSPEND('subuser.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>&suspend=Y&id=<?php echo $row['ID']?>&username=<?php echo $row['UserName']?>')"><?php echo $Mem_Enable?></a> /
    <?php
    }
    ?>
          <a href="competence.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&id=<?php echo $row['ID']?>&username=<?php echo $row['UserName']?>">权限</a> /
          <a href="javascript:CheckDEL('subuser.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>&deluser=Y&id=<?php echo $row['ID']?>&username=<?php echo $row['UserName']?>')"><?php echo $Mem_Delete?></a> /</td>
        </tr>
      </FORM>
    <?php
    }
    }
    ?>
    </table>
    <div id=acc_window style="display: none;position:absolute">
      <FORM name="addUSER" action="" method=post target="_self" onSubmit="return Chk_acc();">
    <table width="200" border="0" cellspacing="1" cellpadding="2" bgcolor="00558E">
      <tr>
        <td bgcolor="#FFFFFF"><table width="200" border="0" cellspacing="0" cellpadding="0" bgcolor="#A4C0CE" class="m_tab_fix" >
          <tr bgcolor="0163A2">
            <td  id=r_title width="116" ><font color="#FFFFFF"><?php echo $Mem_Add?><?php echo $Mem_User?></font></td>
            <td  align="right" valign="top" ><a style="cursor:hand;" onClick="close_win();"><img src="/images/agents/top/edit_dot.gif" width="16" height="14"></a></td>
          </tr>
          <tr>
            <td colspan="2" height="1" bgcolor="#000000"></td>
          </tr>
          <tr>
            <td align="center" ><?php echo $Mem_Account?>&nbsp;&nbsp;<font color="Black"><?php echo substr($name,0,1)?></font>&nbsp;</td>
            <td ><input type=text name=new_user value="" class="za_text" size="12" maxlength="10"></td>
          </tr>
          <tr bgcolor="#000000">
            <td colspan="2" height="1"></td>
          </tr>
          <tr>
            <td align="center" ><?php echo $Mem_Password?>&nbsp;&nbsp; &nbsp;</td>
            <td ><input type=text name=new_pass value="" class="za_text" size="12" maxlength="10"></td>
          </tr>
          <tr bgcolor="#000000">
            <td colspan="2" height="1"></td>
          </tr>
          <tr>
            <td align="center" ><?php echo $Mem_Name?>&nbsp;&nbsp; &nbsp;</td>
            <td ><input type=text name=new_alias value="" class="za_text" size="12" maxlength="10"></td>
          </tr>
          <tr bgcolor="#000000">
            <td colspan="2" height="1"></td>
          </tr>
          <tr>
            <td colspan="2">◎<?php echo $Mem_Password_Guidelines?>：<?php echo $Mem_Pasread?></td>
          </tr>
          <tr bgcolor="#000000">
            <td colspan="2" height="1"></td>
          </tr>
          <tr align="center">
            <td colspan="2" ><input type=submit name=acc_ok value="<?php echo $Mem_Confirm?>" class="za_button">
                  <input type="hidden" name="addNew" value="Y">        </td>
          </tr>
        </table></td>
      </tr>
    </table>
      </FORM>
    </div>
    <!----------------------結帳視窗---------------------------->

</div>
<script Language="javaScript">

    function show_win() {
        acc_window.style.top=document.body.scrollTop+event.clientY+15;
        acc_window.style.left=document.body.scrollLeft+event.clientX-20;
        document.all["acc_window"].style.display = "block";
        document.addUSER.new_user.value= "";
        document.addUSER.new_pass.value= "";
        document.addUSER.new_alias.value= "";
        document.addUSER.new_user.focus();
    }

    function close_win() {
        document.all["acc_window"].style.display = "none";
    }

    function Chk_acc(){
        if(document.all.new_user.value==''){
            document.all.new_user.focus();
            alert("<?php echo $Mem_Input?><?php echo $Mem_Account?>!!");
            return false;
        }
        if(document.all.new_pass.value==''){
            document.all.new_pass.focus();
            alert("<?php echo $Mem_Input?><?php echo $Mem_Password?>!!");
            return false;
        }
        if(document.all.new_alias.value==''){
            document.all.new_alias.focus();
            alert("<?php echo $Mem_Input?><?php echo $Mem_Name?>!!");
            return false;
        }
        close_win();
        return true;
    }

    function ChkData(i){

        e_user="document.AG_"+i+".e_user.value";
        e_pass="document.AG_"+i+".e_pass.value";
        e_alias="document.AG_"+i+".e_alias.value";
        if(e_user=='')
        {
            alert('<?php echo $Mem_Input?><?php echo $Mem_Account?>');
            eval("document.AG_"+i+".e_user.focus()");
            return false;
        }
        if(e_pass=='')
        {
            alert('<?php echo $Mem_Input?><?php echo $Mem_Password?>');
            eval("document.AG_"+i+".e_pass.focus()");
            return false;
        }
        if(e_alias=='')
        {
            alert('<?php echo $Mem_Input?><?php echo $Mem_Name?>');
            eval("document.AG_"+i+".e_alias.focus()");
            return false;
        }
        eval("document.AG_"+i+".submit()");
        return true;
    }

    function CheckDEL(str)
    {
        if(confirm("<?php echo $Mem_Confirm?><?php echo $Mem_Delete?><?php echo $Mem_Account?>?"))
            document.location=str;
    }

    function CheckSUSPEND(str) {
        if(confirm("<?php echo $Mem_Confirm?><?php echo $Mem_Disable?>/<?php echo $Mem_Enable?>?"))
            document.location=str;
    }
    function onLoad() {
        var obj_page = document.getElementById('page');
        obj_page.value = '<?php echo $page?>';
        var obj_sort=document.getElementById('sort');
        obj_sort.value='<?php echo $sort?>';
        var obj_orderby=document.getElementById('orderby');
        obj_orderby.value='<?php echo $orderby?>';
    }
</script>

</body>
</html>
<?php
innsertSystemLog($name,$lv,$loginfo);

?>