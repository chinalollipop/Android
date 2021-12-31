<?php
session_start();
include ("../include/address.mem.php");
require ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST['uid'];
$langx=$_REQUEST["langx"];
require ("../include/traditional.$langx.inc.php");

$action=$_REQUEST['action'];
$page=$_REQUEST['page'];
if ($page==""){
	$page=0;
}
$active=$_REQUEST['active'];
$id=$_REQUEST['id'];
$username=$_REQUEST['username'];
if($active=='Y'){
	$notes=$_REQUEST['notes'];
	$Phone=$_REQUEST['Phone'];
	$Alias=$_REQUEST['Alias'];	
	print_r($_REQUEST);
    $mysql="update ".DBPREFIX.MEMBERTABLE." set notes='$notes',Phone='$Phone',Alias='$Alias' where ID='$id'";
	mysqli_query($dbMasterLink,$mysql);
	echo "<Script language=javascript>self.location='userinfo.php?uid=$uid&langx=$langx&action=$action&page=$page';</script>";
}else if ($active=='del'){
	echo "<Script language=javascript>self.location='userinfo.php?uid=$uid&langx=$langx&action=$action&page=$page';</script>";
}
$search=$_REQUEST['search'];
if ($search!=''){
    $num=25;
    $search="and (UserName like '%$search%' or Date like '%$search%' or Bank_Account like '%$search%' or Phone like '%$search%' or Notes like '%$search%')";
}else{
    $num=25;
}
$sql = "select ID,UserName,PassWord,OnlineTime,Notes,Phone,Alias,Money,LoginIP,Bank_Address from ".DBPREFIX.MEMBERTABLE." where id<>0 $search order by ID desc";
$result = mysqli_query($dbLink, $sql);
$cou=mysqli_num_rows($result);
$page_size=$num;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
//echo $mysql;
$result = mysqli_query($dbLink, $mysql);
?>
<html>
<head>
<title>会员信息</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript">
function onLoad(){
  var obj_page = document.getElementById('page');
  obj_page.value = '<?php echo $page?>';
}
function sbar(st){
st.style.backgroundColor='#BFDFFF';
}
function cbar(st){
st.style.backgroundColor='';
}
function Delete(str)
{
 if(confirm("是否确定删除纪录?"))
  document.location=str;
}
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0"  onLoad="onLoad()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="m_tline">&nbsp;&nbsp;
    <a href=system.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?> target="main" title="系统参数" onMouseOver="window.status='系统参数'; return true;" onMouseOut="window.status='';return true;">系统参数</a>&nbsp;&nbsp;
    <a href=add_notice.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?> target="main" title="系统公告" onMouseOver="window.status='系统公告'; return true;" onMouseOut="window.status='';return true;">系统公告</a>&nbsp;&nbsp;
    <a href=news.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>&action=opennews target="main" title="系统短信" onMouseOver="window.status='系统短信'; return true;" onMouseOut="window.status='';return true;">系统短信</a>&nbsp;&nbsp;
    <a href=news.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>&action=sitenews target="main" title="系统消息" onMouseOver="window.status='系统消息'; return true;" onMouseOut="window.status='';return true;">系统消息</a>&nbsp;&nbsp;
    <a href="access.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=S">会员存款</a>&nbsp;&nbsp;
    <a href="access.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=T">会员提款</a>&nbsp;&nbsp;
    <a href="userinfo.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=T">会员信息</a>&nbsp;&nbsp;
    <a href="credit.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>">额度检查</a>
	</td>
    
  </tr>
  <tr> 
    <td colspan="2" height="4"></td>
  </tr> 
</table>
<table width="975" border="0" cellpadding="2" cellspacing="1" class="m_tab">

	<tr class="m_title">
	  <td colspan="10">会员信息</td>
  </tr>
	<tr class="m_title">
      <FORM id="myFORM" ACTION="" METHOD=POST name="FrmData">
	  <td colspan="9">关键字查找:
	  <input type=TEXT name="search" size=10 value="" maxlength=20 class="za_text">
      <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
	  </td>     
	  <td width="89">
	  <select name='page' onChange="self.myFORM.submit()">
	  
<?php
if ($page_count==0){
    $page_count=1;
	}
	for($i=0;$i<$page_count;$i++){
		if ($i==$page){
			echo "<option selected value='$i'>".($i+1)."</option>";
		}else{
			echo "<option value='$i'>".($i+1)."</option>";
		}
	}
?>  
  </select> 共<?php echo $page_count?> 页 
	  </td></FORM>
	</tr>
	<tr class="m_title">
	  <td width="30">编号</td>
	  <td width="70">帐号密码</td>
	  <td width="70">日期时间</td>
	  <td width="195">备注</td>
	  <td width="80">电话号码</td>
	  <td width="80">姓名</td>
	  <td width="85">可用余额</td>
	  <td width="90">IP</td>
	  <td width="135">联系方式</td>
      <td>操作</td>
	</tr>
<?php
$i=1;
while ($row = mysqli_fetch_array($result)){
$id=$row['ID'];
?>
  <tr class="m_cen" onmouseover=sbar(this) onmouseout=cbar(this)> 
    <form  method=post target='_self'>  
    <td align="center"><?php echo $i?></td>
    <td align="center"><font color=red><?php echo $row['UserName']?></font><br><font color="#000" style="background:#FFFF00"><?php echo $row['PassWord']?></font></td>
    <td align="center"><?php echo $row['OnlineTime']?></td>
	<td align="left"><textarea name="notes" cols="22" rows="3"><?php echo $row['Notes']?></textarea></td>
    <td align="center"><input type="text" name="Phone" value="<?php echo $row['Phone']?>" style=" width:90px;"></td>
    <td align="center"><input type="text" name="Alias" value="<?php echo $row['Alias']?>" style=" width:70px;"></td>
    <td align="center"><?php echo $row['Money']?></td>
    <td align="center"><?php echo $row['LoginIP']?></td>
    <td align="center"><?php echo $row['Bank_Address']?></td>
	<td align="center">

	
	<input type=submit name=send value='更改' onClick="return confirm('确定更改用户信息')" class="za_button">
    <input type=hidden name=active value=Y>
	<input type=hidden name=id value=<?php echo $row['ID']?>>
    <input type=hidden name=uid value=<?php echo $uid?>>
    <a href="javascript:Delete('userinfo.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&active=del&langx=<?php echo $langx?>&action=<?php echo $action?>&page=<?php echo $page?>')">删除</a>
	</td>
    </form>
    <?php
$i=$i+1;
}
?>

  </tr>
</table>
</body>
</html>
